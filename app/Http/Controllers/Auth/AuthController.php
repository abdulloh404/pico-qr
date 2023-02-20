<?php

namespace App\Http\Controllers\Auth;

use App\Events\SendMail;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Plan;
use App\Models\User;
use App\Models\Menu;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function registration()
    {
        return view('auth.registration');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:5',
        ]);

        if(json_decode(get_settings('site_setting')) && isset(json_decode(get_settings('site_setting'))->recaptcha_secret_key)) {
            $data = array(
                'secret' => json_decode(get_settings('site_setting'))->recaptcha_secret_key,
                'response' => $request->grecaptcha_response,
            );
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($verify);

            $captcha = json_decode($res);

            if ($captcha->success == false) {
                return redirect()->back()->withErrors(['failed'=>'Invalid Captcha, You are a freakin robot!'])->withInput();
            }
        }

        $user = new  User();
        $user->name = $request->name;
        $user->email = $request->email;


        if (isset($request->type) && $request->type == 'customer') {
            $user->type = 'customer';
            $user->email_verified_at = now();
        } else {
            $user->type = 'restaurant_owner';
        }

        $user->password = bcrypt($request->password);
        $user->save();
        if (isset($request->type) && $request->type == 'customer') {
            $user->assignRole('customer');
        } else {
            $user->assignRole('restaurant_owner');

        }

        if ($user->type = 'customer' && $request->slug && $request->restaurant) {
            $slug = $request->slug;
            $restaurant = $request->restaurant;
            $menu = new Menu();
            $menu->user_id = $user->id;
            $menu->url = $slug . '?' . 'id=' . $restaurant;
            $menu->save();
        }

        $plan = Plan::where('recurring_type', 'onetime')->first();
        if (isset($request->type) && $request->type == 'customer'){
            auth()->login($user);
            $modules = modules_status('MultiRestaurant');
            if ($user->type = 'customer' && $modules){
                return redirect()->route('multirestaurant::index');
            }else{
                return redirect()->route('dashboard')->with('success', trans('layout.message.registration_success'));
            }
        }else{
            $userPlan = new UserPlan();
            $userPlan->user_id = $user->id;
            $userPlan->plan_id = $plan->id;
            $userPlan->start_date = now();
            $userPlan->is_current = 'yes';
            $userPlan->recurring_type = $plan->recurring_type;
            $userPlan->status = 'pending';
            $userPlan->save();
        }

        try {
            $emailTemplate = EmailTemplate::where('type', 'registration')->first();
            if ($emailTemplate) {
                $arr=['id' => $user->id, 'hash' => sha1($user->email)];
                if(isset($request->plan) && $request->plan!=1){
                    $arr['plan']=$request->plan;
                }


                $route = URL::temporarySignedRoute('verification.verify', now()->addHours(4), $arr);
                $regTemp = str_replace('{customer_name}', $user->name, $emailTemplate->body);
                $regTemp = str_replace('{click_here}', "<a href=" . $route . ">".trans('layout.click_here')."</a>", $regTemp);
                SendMail::dispatch($user->email, $emailTemplate->subject, $regTemp);
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }
        auth()->login($user);
        return redirect()->route('dashboard')->with('success', trans('layout.message.registration_success'));

    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {

        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(json_decode(get_settings('site_setting')) && isset(json_decode(get_settings('site_setting'))->recaptcha_secret_key)) {
            $data = array(
                'secret' => json_decode(get_settings('site_setting'))->recaptcha_secret_key,
                'response' => $request->grecaptcha_response,
            );
            $verify = curl_init();
            curl_setopt($verify, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
            curl_setopt($verify, CURLOPT_POST, true);
            curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($verify, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
            $res = curl_exec($verify);

            $captcha = json_decode($res);

            if ($captcha->success == false) {
                return redirect()->back()->withErrors(['failed'=>'Invalid Captcha, You are a freakin robot!'])->withInput();
            }
        }

        $remember = isset($request->remember_me) ? true : false;

        $credentials = $request->only('email', 'password');



        if (auth()->attempt($credentials, $remember)) {
                if(auth()->user()->type=='restaurant_owner' && auth()->user()->status=='banned'){
                    auth()->logout();
                    return redirect()->back()->withErrors(['fail' => trans('auth.banned')]);
                }
            $modules = modules_status('MultiRestaurant');
            if(auth()->user()->type=='customer'){
                if ($modules){
                    return redirect()->route('multirestaurant::index');
                }else{
                    return redirect()->route('order.index');
                }

            }
            if (auth()->user()->type=='admin') {
                $role = Role::findOrCreate('admin');

                auth()->user()->assignRole($role);
            }
            return redirect()->intended('dashboard');

        } else
            return redirect()->back()->withErrors(['fail' => trans('auth.failed')]);

    }

    public function forgetPassword()
    {
        return view('auth.forget_password');
    }

    public function sendForgetPasswordCode(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) return redirect()->back()->withErrors(['msg' => trans('layout.message.user_not_found')]);
        $token = sha1($user->email);
        $data = [
            'email' => $user->email,
            'token' => $token
        ];
        DB::table('password_resets')->insert($data);

        try {
            $emailTemplate = EmailTemplate::where('type', 'forget_password')->first();
            if ($emailTemplate) {
                $route = URL::temporarySignedRoute('password.reset.form', now()->addHours(1), ['token' => $token]);
                $temp = str_replace('{customer_name}', $user->name, $emailTemplate->body);
                $temp = str_replace('{reset_url}', "<a href=" . $route . ">".trans('layout.click_here')."</a>", $temp);
                SendMail::dispatch($user->email, $emailTemplate->subject, $temp);
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }

        return redirect()->route('login')->with('success', trans('layout.message.reset_link_send'));

    }

    public function passwordResetForm($token)
    {
        $resetTable = DB::table('password_resets')->where('token', $token)->first();
        if (!$resetTable) {
            return redirect()->route('login')->withErrors(['msg' => trans('layout.message.token_expired')]);
        }

        $data['token'] = $token;
        return view('auth.new_password_form', $data);
    }

    public function passwordResetConfirm(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|min:5',
            'token' => 'required'
        ]);

        $resetTable = DB::table('password_resets')->where('token', $request->token)->first();
        if (!$resetTable) {
            return redirect()->route('login')->withErrors(['msg' => trans('layout.message.token_expired')]);
        }

        $user = User::where('email', $resetTable->email)->first();
        if (!$user) {
            return redirect()->route('login')->withErrors(['msg' => trans('layout.message.token_expired')]);
        }

        $user->password = bcrypt($request->password);
        $user->save();
        DB::table('password_resets')->where('token', $request->token)->delete();
        return redirect()->route('login')->with('success', trans('layout.message.reset_successful'));

    }
}


