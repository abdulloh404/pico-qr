<?php

namespace App\Http\Controllers;

use App\Events\SendMail;
use App\Models\CallWaiter;
use App\Models\CustomMenu;
use App\Models\Item;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Plan;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index()
    {
        $data['plans'] = Plan::where(['status' => 'active'])->where('id', '!=', 1)->get();
        $modules = modules_status('MultiRestaurant');
        if ($modules){
            return redirect()->route('multirestaurant::index');
        }else{
            return view('front.index', $data);
        }

    }
    public function home()
    {
        $data['plans'] = Plan::where(['status' => 'active'])->where('id', '!=', 1)->get();
        return view('front.index', $data);
    }

    public function preOrder(Request $request)
    {
        $authUser = auth()->user();
        $order = Order::where('user_id', $authUser->id)->first();
        $orderDetails = OrderDetails::where('order_id', $order)->where('item_id', $request->item_id)->get();
        return response()->json(['status', 'success', 'orderDetails' => $orderDetails]);
    }

    public function show($slug, Request $request)
    {
        // dd($slug);
        $data['restaurant'] = $restaurant = Restaurant::where('slug', $slug)->where('id', $request->id)->firstOrFail();
        $authUser = auth()->user();
        if ($authUser) {
            $data['order'] = Order::where('user_id', $authUser->id)->where('restaurant_id',$request->id)->orderBy('created_at', 'desc')->first();
            $currentUrl = $slug . '?' . 'id=' . $request->id;
            $userMenu = Menu::where('user_id', $authUser->id)->where('url', $currentUrl)->orderBy('created_at','desc')->limit(1)->get();
            if (isset($userMenu[0]) && $userMenu[0]){

            }else {
                $menu = new Menu();
                $menu->user_id = $authUser->id;
                $menu->url = $slug . '?' . 'id=' . $request->id;
                $menu->save();
            }
        }


        $rest_categories = [];
        foreach ($restaurant->items as $item) {
            if (!in_array($item->category, $rest_categories)) {
                $rest_categories[] = $item->category;
            }
        }
        $data['rest_categories'] = $rest_categories;


        $data['tables'] = $restaurant->tables;

        if ($restaurant->template == 'custom' || $request->type == 'custom') {
            $data['categories'] = CustomMenu::with('category')->where('restaurant_id', $restaurant->id)->get()->groupBy(function ($item, $key) {
                return $item->category->name;
            });
            $data['customFooter'] = $restaurant->footer;
            return view('restaurant.show_custom_restaurant', $data);
        } else {
            $data['categories'] = Item::with('category')->where('restaurant_id', $restaurant->id)->get()->groupBy(function ($item, $key) {
                return $item->category->name;
            });

            if ($restaurant->template == 'modern') {
                return view('restaurant.show_restaurant_modern', $data);
            } else {

                return view('restaurant.show_restaurant', $data);
            }

        }


    }

    public function setLocale($type)
    {
        $availableLang = get_available_languages();

        if (!in_array($type, $availableLang)) abort(400);

        session()->put('locale', $type);

        // dd(session()->get('locale'));
        return redirect()->back();
    }

    public function subscribe(Request $request)
    {
        $email = $request->email;
        if (!$email) abort(400);
        SendMail::dispatch(config('mail.from.address'), "Newsletter Subscription", "You have got a new newsletter subscription of " . $email);
        return redirect()->back()->with('success', 'Thanks for your subscription');
    }
    public function privacy_policy()
    {
        return view('front.privacy_policy');
    }
    public function terms_conditions()
    {
        return view('front.terms_conditions');
    }

}
