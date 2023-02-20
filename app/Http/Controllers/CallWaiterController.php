<?php

namespace App\Http\Controllers;

use App\Models\CallWaiter;
use App\Models\Restaurant;
use App\Models\Table;
use App\Models\User;
use Illuminate\Http\Request;

class CallWaiterController extends Controller
{
    public function store(Request $request)
    {
        $user = auth()->user();

        $call_waiter = CallWaiter::where('table_id', $request->table_id)->where('status', 'pending');

        if ($user) {
            $call_waiter->where('user_id', $user->id);
        }
        $call_waiter = $call_waiter->first();

        if ($call_waiter) {
            return redirect()->back()->withErrors(['failed' => trans('layout.message.already_called_waiter')]);
        }
        $restaurant = Restaurant::where('id', $request->restaurant)->firstOrFail();

        $table = Table::where('id', $request->table_id)->firstOrFail();
        $waiter = new CallWaiter();
        $waiter->user_id = $user ? $user->id : null;
        $waiter->restaurant_id = $restaurant->id;
        $waiter->table_id = $table->id;
        $waiter->status = 'pending';
        $waiter->save();

        notification('call_waiter', $waiter->id, $restaurant->user_id, "A new waiter called request has been placed");
        return redirect()->back()->with('success', trans('layout.message.waiter_called'));
    }

    public function show()
    {
        $user = auth()->user();
        $restIds = $user->restaurants()->pluck('id');
        $data['call_waiters'] = CallWaiter::whereIn('restaurant_id', $restIds)->orderBy('created_at', 'desc')->get();

        return view('restaurant.call_waiter', $data);
    }

    public function status(Request $request)
    {

        $user = auth()->user();
        $restaurant = Restaurant::where('id', $request->restaurant)->where('user_id', $user->id)->firstOrFail();
        $call_waiter = CallWaiter::where('id', $request->id)->where('restaurant_id', $restaurant->id)->firstOrFail();
        $call_waiter->status = 'solved';
        $call_waiter->save();
        if ($call_waiter->user_id) {
            notification('call_waiter', $call_waiter->id, $call_waiter->user_id, "The waiter will reach you shortly");
        }

        return redirect()->back()->with('success', 'Status Changed Successfully');
    }

    public function delete(Request $request)
    {
        $user = auth()->user();
        $call_waiter = CallWaiter::where('id', $request->id)->firstOrFail();

        $restaurant = Restaurant::where('id', $call_waiter->restaurant_id)->where('user_id', $user->id)->firstOrFail();
        if ($restaurant) {
            $call_waiter->delete();
        } else {
            abort(404);
        }
        return redirect()->back()->with('success', trans('layout.message.call_waiter_request_delete'));
    }
}
