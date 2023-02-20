<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function index(){
        $user = auth()->user();
        $restaurants = Restaurant::where('user_id', auth()->id())->pluck('id');
        $data['orders'] = Order::whereIn('restaurant_id', $restaurants)->orWhere('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        return view('billing.index',$data);
    }
}
