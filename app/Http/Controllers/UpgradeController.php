<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UpgradeController extends Controller
{
    public function process()
    {
        auth()->logout();
        cache()->flush();

        if (env('APP_DEBUG')){
            echo "<h1>Upgrading...</h1>";
                try{
                    Artisan::call('migrate');
                }catch (\Exception $ex){
                    return redirect()->route('login')->withErrors(['msg'=>'Failed! Please contact with administration']);
                }

            echo "Database upgrade has been finished <br/>";
            echo "<a href='" . route('login') . "'>Back to login page</a>";
        }else{
            return redirect()->route('login')->withErrors(['msg'=>'Please enable APP_DEBUG']);
        }
    }
}
