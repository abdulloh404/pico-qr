<?php

namespace App\Http\Controllers;

use App\Events\ModuleDisabled;
use App\Events\ModuleEnabled;
use App\Events\ModuleInstalled;
use App\Events\ModuleUninstalled;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddonController extends Controller
{
    public function index()
    {
        $modules = \Module::all();
        $newArray = [];
        foreach ($modules as $module) {
            $newArray[] = [
                'name' => $module->getName(),
                'status' => $module->isEnabled(),
            ];
        }
        $data['modules'] = $newArray;
        return view('addon.index', $data);
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'status' => 'required|in:enable,disable'
        ]);

        $module = \Module::find($request->name);
        if (!$module) return redirect()->back()->withErrors(['msg' => trans('Invalid request')]);

        if ($request->status == 'enable') {
            $module->enable();
            return redirect()->route('addon.event',[
                "redirect_to"=>route('addon.index'),
                "message"=>trans('Module enabled successfully'),
                "event"=>'App\Events\ModuleEnabled',
                "event_params"=>$request->name,
            ]);
        } else if ($request->status == 'disable') {
            ModuleDisabled::dispatch($request->name);
            $module->disable();
        }

        return redirect()->back()->with('success', trans('Module status changed successfully'));
    }

    public function uninstall(Request $request)
    {
        if(env('APP_DEMO')){
            return redirect()->back()->withErrors(['msg' => trans('Uninstall not available on demo')]);
        }
        $request->validate([
            'name' => 'required',
        ]);

        $module = \Module::find($request->name);
        if (!$module) return redirect()->back()->withErrors(['msg' => trans('Invalid request')]);
        \Artisan::call("module:migrate-rollback " . $request->name);
        ModuleUninstalled::dispatch($request->name);
        $module->delete();
        return redirect()->back()->with('success', trans('Module uninstalled successfully'));

    }

    public function import()
    {
        return view('addon.import');
    }

    public function import_store(Request $request)
    {
        $request->validate([
            'addon' => 'required|mimes:zip'
        ]);
        $search_this = [
            'composer.json',
            'Config',
            'Controllers',
            'module.json',
            'package.json',
            'Resources',
            'views',
            'web.php',
            'webpack.mix.js',
        ];
        if ($request->hasFile('addon')) {
            $file = $request->file('addon');
            $path = \Module::getPath();
            $zip = new \ZipArchive();
            $res = $zip->open($file);
            if ($res === TRUE) {
                $addonName = explode('/', trim($zip->getNameIndex(0)))[0];
                $fileNames = [];
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $stat = $zip->statIndex($i);
                    $fileNames[] = basename($stat['name']);
                }
                $containsAllValues = !array_diff($search_this, $fileNames);
                if (!$containsAllValues) {
                    return redirect()->back()->withErrors(['msg' => trans('Invalid module selected')]);
                }


                $zip->extractTo($path);
                $zip->close();


                \Artisan::call("module:publish $addonName");
                \Artisan::call("module:update $addonName");
                \Artisan::call("module:migrate $addonName");

                $module = \Module::find($addonName);
                $module->enable();

                return redirect()->route('addon.event',[
                    "redirect_to"=>route('addon.index'),
                    "message"=>trans('Module installed successfully'),
                    "event"=>'App\Events\ModuleInstalled',
                    "event_params"=>$addonName,
                ]);
            } else {
                return redirect()->back()->withErrors(['msg' => trans('Invalid module')]);
            }
        }
    }

    public function eventTrigger(Request $request)
    {
        $request->validate([
            'redirect_to'=>'required',
            'event'=>'required',
        ]);
        ($request->event)::dispatch($request->event_params);
        return redirect()->to($request->redirect_to)->with('success', $request->message);
    }
}

