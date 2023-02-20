<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\TemplateTwo;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->type != 'admin'){
            abort('404');
        }
        return view('template.index');
    }
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->type != 'admin'){
            abort('404');
        }
        $data_template = Setting::where('name','frontend_template')->first();
        if ($data_template){
            $template = json_decode($data_template->value);
        }
        if(isset($template->bg_image_file_name) && $template->bg_image_file_name){
            $request['bg_image_file_name'] = $template->bg_image_file_name;
        }
        if ($request->hasFile('bg_image')) {
            $file = $request->file('bg_image');
            $imageOneName = time().'_1' . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageOneName);
            $request['bg_image_file_name'] = $imageOneName;
        }
        if(isset($template->first_img_file_name) && $template->first_img_file_name){
            $request['first_img_file_name'] = $template->first_img_file_name;
        }

        if ($request->hasFile('first_img')) {
            $file = $request->file('first_img');
            $imageTwoName = time().'_2' . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageTwoName);
            $request['first_img_file_name'] = $imageTwoName;
        }
        if(isset($template->sec_img_file_name) && $template->sec_img_file_name){
            $request['sec_img_file_name'] = $template->sec_img_file_name;
        }

        if ($request->hasFile('sec_img')) {
            $file = $request->file('sec_img');
            $imageThreeName = time().'_3' . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageThreeName);
            $request['sec_img_file_name'] = $imageThreeName;
        }
        if(isset($template->thr_img_file_name) && $template->thr_img_file_name){
            $request['thr_img_file_name'] = $template->thr_img_file_name;
        }

        if ($request->hasFile('thr_img')) {
            $file = $request->file('thr_img');
            $imageFourName = time().'_4' . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageFourName);
            $request['thr_img_file_name'] = $imageFourName;
        }
        if(isset($template->section_three_bg_image_file_name) && $template->section_three_bg_image_file_name){
            $request['section_three_bg_image_file_name'] = $template->section_three_bg_image_file_name;
        }

        if ($request->hasFile('section_three_bg_image')) {
            $file = $request->file('section_three_bg_image');
            $imageFiveName = time().'_5' . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageFiveName);
            $request['section_three_bg_image_file_name'] = $imageFiveName;
        }
        if(isset($template->first_comment_img_file_name) && $template->first_comment_img_file_name){
            $request['first_comment_img_file_name'] = $template->first_comment_img_file_name;
        }

        if ($request->hasFile('first_comment_img')) {
            $file = $request->file('first_comment_img');
            $imageSixName = time().'_6' . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageSixName);
            $request['first_comment_img_file_name'] = $imageSixName;
        }
        if(isset($template->sec_comment_img_file_name) && $template->sec_comment_img_file_name){
            $request['sec_comment_img_file_name'] = $template->sec_comment_img_file_name;
        }

        if ($request->hasFile('sec_comment_img')) {
            $file = $request->file('sec_comment_img');
            $imageSevenName = time().'_7' . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageSevenName);
            $request['sec_comment_img_file_name'] = $imageSevenName;
        }
        if(isset($template->thr_comment_img_file_name) && $template->thr_comment_img_file_name){
            $request['thr_comment_img_file_name'] = $template->thr_comment_img_file_name;
        }

        if ($request->hasFile('thr_comment_img')) {
            $file = $request->file('thr_comment_img');
            $imageEightName = time().'_8' . '.' . $file->extension();
            $file->move(public_path('/uploads'), $imageEightName);
            $request['thr_comment_img_file_name'] = $imageEightName;
        }
        if (isset($data_template) && $data_template->name == 'frontend_template'){
            $template = Setting::where('name', '=', 'frontend_template')->first();
            $template->value = json_encode($request->only('title','main_title','first_title','first_description','sec_title','sec_description','thr_title','thr_description','section_three_title','section_three_description','section_four_title','first_name','first_comment','sec_name','sec_comment','thr_name','thr_comment','first_img_file_name','sec_img_file_name','thr_img_file_name','section_three_bg_image_file_name','sec_comment_img_file_name','thr_comment_img_file_name','section_five_title','bg_image_file_name','first_comment_img_file_name'));
            $template->save();
        }else{
            $template = new Setting();
            $template->name = 'frontend_template';
            $template->value = json_encode($request->only('title','main_title','first_title','first_description','sec_title','sec_description','thr_title','thr_description','section_three_title','section_three_description','section_four_title','first_name','first_comment','sec_name','sec_comment','thr_name','thr_comment','first_img_file_name','sec_img_file_name','thr_img_file_name','section_three_bg_image_file_name','sec_comment_img_file_name','thr_comment_img_file_name','section_five_title','bg_image_file_name','first_comment_img_file_name'));
            $template->save();
        }
        cache()->flush();

        return redirect()->back()->with('success', trans('layout.template_updated_successfully'));
    }
}
