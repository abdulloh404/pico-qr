<?php

namespace App\Http\Controllers;

use App\Events\SendMail;
use App\Models\Category;
use App\Models\EmailTemplate;
use App\Models\Item;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Restaurant;
use App\Models\User;
use App\Models\UserPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class CustomerController extends Controller
{
    public function index()
    {
        $authUser = auth()->user();
        if ($authUser->type == 'restaurant_owner') {
            $data['customers'] = User::where('type', 'user')->where('restaurant_owner_id', $authUser->id)->orderBy('created_at', 'desc')->get();
        } else {
            $data['customers'] = User::where('type', 'restaurant_owner')->orderBy('created_at', 'desc')->get();
        }

        return view('customer.index', $data);
    }

    public function create()
    {
        $authUser = auth()->user();
        $data['plans'] = Plan::where('status', 'active')->where('id', '!=', 1)->get();
        if ($authUser->type=='restaurant_owner'){
            $data['roles']=Role::where('restaurant_id',$authUser->id)->get();
        }
        $data['restaurants'] = auth()->user()->active_restaurants;
        return view('customer.create', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:191',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'role' => 'max:191',
        ]);
        $authUser = auth()->user();
        if (!$request->restaurant_id && $authUser->type != 'admin'){
            return redirect()->back()->withErrors(['msg' => trans('layout.create_a_restaurant')]);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = bcrypt($request->password);
        $user->email_verified_at = now();
        if ($authUser->type == 'restaurant_owner') {
            $user->restaurant_owner_id = $authUser->id;
            $user->restaurant_id = $request->restaurant_id;
            $user->type = 'user';
        } else {
            $user->type = 'restaurant_owner';
        }
        $user->save();

        if ($request->plan_id) {


            $plan = Plan::findOrFail($request->plan_id);
            $expiredDate = null;
            if ($plan->recurring_type == 'weekly') {
                $expiredDate = now()->addWeek();
            } else if ($plan->recurring_type == 'monthly') {
                $expiredDate = now()->addMonth();
            } else if ($plan->recurring_type == 'yearly') {
                $expiredDate = now()->addYear();
            }
            $userPlan = new UserPlan();
            $userPlan->user_id = $user->id;
            $userPlan->plan_id = $plan->id;
            $userPlan->start_date = now();
            $userPlan->expired_date = $expiredDate;
            $userPlan->is_current = 'yes';
            $userPlan->cost = $plan->cost;
            $userPlan->recurring_type = $plan->recurring_type;
            $userPlan->table_limit = $plan->table_limit;
            $userPlan->restaurant_limit = $plan->restaurant_limit;
            $userPlan->item_limit = $plan->item_limit;
            $userPlan->item_unlimited = $plan->item_unlimited;
            $userPlan->table_unlimited = $plan->table_unlimited;
            $userPlan->restaurant_unlimited = $plan->restaurant_unlimited;
            $userPlan->status = 'approved';
            $userPlan->save();
        }
        if ($authUser->type == 'admin') {
            $role = Role::findOrCreate('restaurant_owner');
            $user->assignRole($role);
        }elseif ($authUser->type == 'restaurant_owner'){
            $role=$request->role;
            $user->assignRole($role);
        }


        return redirect()->back()->with('success', trans('layout.message.restaurant_owner_created'));
    }

    public function edit(User $user)
    {
        $authUser = auth()->user();

        if($authUser->type=='restaurant_owner' && $authUser->id == $user->restaurant_owner_id){
            $data['roles']=Role::where('restaurant_id',$authUser->id)->get();
            $data['customer'] = $user;
        }elseif($authUser->type=='admin'){
            $data['customer'] = $user;
        }else{
            return abort('404');
        }
        $data['restaurants'] = auth()->user()->active_restaurants;
        $data['plans'] = Plan::where('status', 'active')->where('id', '!=', 1)->get();
        $data['userPlans'] = isset($user->current_plans[0]) ? $user->current_plans[0] : '';

        return view('customer.edit', $data);

    }

    public function update(Request $request, User $user)
    {

        $request->validate([
            'name' => 'required|max:191',
            'transaction_id' => 'max:191',
            'email' => 'required|email|unique:users,email,' . $user->id
        ]);
        $authUser=auth()->user();
        $requested_plan_id = $request->plan_id;
        $customer = $user;
        $customer->name = $request->name;
        $customer->email = $request->email;
        $customer->role = $request->role;
        if ($request->password) {
            $customer->password = bcrypt($request->password);
        }
        $customer->save();

        if ($request->plan_id) {

            $plan = Plan::where('id', $requested_plan_id)->first();
            if (isset($customer->current_plans[0]) && $customer->current_plans[0]->plan_id != $requested_plan_id) {
                try {
                    $emailTemplate = EmailTemplate::where('type', 'plan_accepted')->first();
                    if ($emailTemplate) {
                        $planChangeTemp = str_replace('{customer_name}', $customer->name, $emailTemplate->body);
                        $planChangeTemp = str_replace('{plan_from}', $customer->current_plans[0]->plan->title, $planChangeTemp);
                        $planChangeTemp = str_replace('{plan_to}', $plan->title, $planChangeTemp);
                        SendMail::dispatch($customer->email, $emailTemplate->subject, $planChangeTemp);
                    }
                } catch (\Exception $ex) {
                    Log::error($ex->getMessage());
                }
            }


            $userPlan = UserPlan::where('user_id', $customer->id)->first();
            $expiredDate = null;
            if ($plan->recurring_type == 'weekly') {
                $expiredDate = now()->addWeek();
            } else if ($plan->recurring_type == 'monthly') {
                $expiredDate = now()->addMonth();
            } else if ($plan->recurring_type == 'yearly') {
                $expiredDate = now()->addYear();
            }
            $userPlan->plan_id = $requested_plan_id;
            $userPlan->status = 'approved';
            $userPlan->is_current = 'yes';
            $userPlan->recurring_type = $plan->recurring_type;
            $userPlan->table_limit = $plan->table_limit;
            $userPlan->restaurant_limit = $plan->restaurant_limit;
            $userPlan->item_limit = $plan->item_limit;
            $userPlan->item_unlimited = $plan->item_unlimited;
            $userPlan->table_unlimited = $plan->table_unlimited;
            $userPlan->restaurant_unlimited = $plan->restaurant_unlimited;
            $userPlan->expired_date = $expiredDate;
            $userPlan->transaction_id = '';
            $userPlan->save();
        }

        if ($authUser->type == 'admin') {
            $role = Role::findOrCreate('restaurant_owner');
            $user->assignRole($role);
        }elseif ($authUser->type == 'restaurant_owner'){
            $role=$request->role;
            $user->assignRole($role);
        }

        return redirect()->route('customers.index')->with('success', trans('layout.message.customer_edit_success'));
    }

    public function user()
    {
        $authUser = auth()->user();
        if ($authUser->type == 'admin') {
            $data['users'] = User::where('type', 'customer')->orderBy('created_at', 'desc')->get();
            $data['restaurant_ids'] = $authUser->restaurants()->pluck('id');
        } else {
            $restaurants = Restaurant::where('user_id', $authUser->id)->pluck('id');
            $user_ids = Order::whereIn('restaurant_id', $restaurants)->whereNotNull('user_id')->pluck('user_id');
            $data['users'] = User::where('type', 'customer')->whereIn('id', $user_ids)->get();
            $data['restaurant_ids'] = $authUser->restaurants()->pluck('id');
        }
        return view('restaurant.user', $data);
    }

    public function destroy(User $user)
    {
        if (auth()->user()->type != 'admin' || $user->type != 'restaurant_owner') {
            abort(404);
        }
        if ($user->type == 'restaurant_owner') {
            $category = Category::where('user_id', $user->id)->first();
            $item = Item::where('user_id', $user->id)->first();
            $restaurant = Restaurant::where('user_id', $user->id)->first();
            if ($restaurant) {
                return redirect()->back()->withErrors(['failed' => 'This user already used , can not delete at this moment']);
            }
            if ($item) {
                return redirect()->back()->withErrors(['failed' => 'This user already used , can not delete at this moment']);
            }
            if ($category) {
                return redirect()->back()->withErrors(['failed' => 'This user already used , can not delete at this moment']);
            }
        }
        $role = Role::findOrCreate('restaurant_owner');
        $user->removeRole($role);
        $user->delete();
        return redirect()->back()->with('success', trans('layout.message.customer_deleted'));
    }

    public function verified(Request $request){
        $user= User::where('id', $request->id)->firstOrFail();
        $user->email_verified_at=now();
        $user->status='approved';
        $user->save();

        return redirect()->back()->with('success', trans('layout.message.user_successfully_verified'));
    }
    public function user_banned(Request $request){
        $user= User::where('id', $request->id)->firstOrFail();
        $user->status='banned';
        $user->save();

        return redirect()->back()->with('success', trans('layout.message.user_successfully_banned'));
    }
    public function user_approved(Request $request){
        $user= User::where('id', $request->id)->firstOrFail();
        $user->status='approved';
        $user->save();

        return redirect()->back()->with('success', trans('layout.message.user_successfully_approved'));
    }

}
