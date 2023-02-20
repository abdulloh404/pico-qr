<?php

namespace App\Http\Controllers;

use App\Events\SendMail;
use App\Models\EmailTemplate;
use App\Models\Item;
use App\Models\ItemExtra;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\OrderExtra;
use App\Models\Restaurant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Modules\MultiRestaurant\Entities\Cart;
use PayPal\Api\Payment;
use paytm\paytmchecksum\PaytmChecksum;
use Unicodeveloper\Paystack\Paystack;

class OrderController extends Controller
{


    public function index(Request $request)
    {
        $user = auth()->user();
        if ($user->type == 'user') {
//            $restaurants = Restaurant::where('user_id', $user->restaurant_id)->pluck('id');
            $data['orders'] = Order::where('restaurant_id', $user->restaurant_id)->orderBy('created_at', 'desc')->get();
        } else if ($user->type == 'customer') {
            $data['orders'] = Order::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();

        } else {
            $restaurants = Restaurant::where('user_id', auth()->id())->pluck('id');

                $orders = Order::whereIn('restaurant_id', $restaurants);
                if($request->paid){
                    $orders =  $orders->where('payment_status','paid');
                }
                if ($request->unpaid){
                    $orders =  $orders->where('payment_status','unpaid');
                }

                if ($request->to_date && $request->from_date){
                        $orders =  $orders->whereBetween('created_at',[$request->to_date,$request->to_date]);
                }

            $data['orders']=$orders->get();
        }

        return view('order.index', $data);
    }

    public function liveOrder(){

        return view('order.live_order');
    }

    public function liveOrderResponse(Request $request)
    {
        $request_time = $request->time;
        if (!$request->time) {
            $request_time = Carbon::now();
        }

        $time = Carbon::createFromTimeString($request_time);
        $user = auth()->user();
        if ($user->type == 'user') {
            $restaurants = Restaurant::where('user_id', $user->restaurant_id)->pluck('id');
            $orders = Order::whereIn('restaurant_id', $restaurants)->orWhere('user_id', $user->restaurant_id)->where('created_at', '>', $time)->orderBy('created_at', 'desc')->get();

        } else {
            $restaurants = Restaurant::where('user_id', auth()->id())->pluck('id');
            $orders = Order::whereIn('restaurant_id', $restaurants)->orWhere('user_id', $user->id)->where('created_at', '>', $time)->orderBy('created_at', 'desc')->get();
        }
        $approvedItemList = [];
        $onTheWayItemList = [];
        $deliveredItemList = [];
        $itemList = [];
        $pendingOrder = [];
        $approvedOrder = [];
        $onTheWayOrder = [];
        $deliveredOrder = [];
        foreach ($orders as $order) {
            if ($order->status == 'pending') {
                foreach ($order->details as $key=>$detail) {
                    $item_name = str_replace(',','',$detail->item->name);
                    $itemList[$key] = "<li class='mt-2'>$item_name</li>";
                }

                $pendingOrder[] = [
                    'id' => $order->id,
                    'created_at' => $order->created_at->diffForHumans(),
                    'live_created_at' => $order->created_at->format("Y-m-d H:m:s"),
                    'order_status' => $order->status,
                    'delivered_within' => str_replace('_', ' ', $order->delivered_within),
                    'type' => $order->type == 'pay_on_table' ? ($order->table->name . '(' . $order->table->position . ')') : ucfirst($order->type),
                    'total_price' => ($order->restaurant->currency_symbol ? $order->restaurant->currency_symbol : '$') . '' . $order->total_price,
                    'status' => str_replace('_', ' ', ucfirst($order->status)),
                    'item_name' => $itemList,
                ];
            }

            if ($order->status == 'approved') {
                foreach ($order->details as $key=>$detail) {
                    $item_name = str_replace(',','',$detail->item->name);
                    $approvedItemList[$key]= "<li class='mt-2'>$item_name</li>";
                }
                $approvedOrder[] = [
                    'id' => $order->id,
                    'live_created_at' => $order->created_at->format("Y-m-d H:m:s"),
                    'created_at' => $order->created_at->diffForHumans(),
                    'order_status' => $order->status,
                    'delivered_within' => str_replace('_', ' ', $order->delivered_within),
                    'type' => $order->type == 'pay_on_table' ? ($order->table->name . '(' . $order->table->position . ')') : ucfirst($order->type),
                    'total_price' => ($order->restaurant->currency_symbol ? $order->restaurant->currency_symbol : '$') . '' . $order->total_price,
                    'status' => str_replace('_', ' ', ucfirst($order->status)),
                    'item_name' => $approvedItemList,
                ];
            }

            if ($order->status == 'ready_for_delivery') {
                foreach ($order->details as $key=>$detail) {
                    $item_name = str_replace(',','',$detail->item->name);
                    $onTheWayItemList[$key] = "<li class='mt-2'>$item_name</li>";
                }
                $onTheWayOrder[] = [
                    'id' => $order->id,
                    'live_created_at' => $order->created_at->format("Y-m-d H:m:s"),
                    'created_at' => $order->created_at->diffForHumans(),
                    'order_status' => $order->status,
                    'delivered_within' => str_replace('_', ' ', $order->delivered_within),
                    'type' => $order->type == 'pay_on_table' ? ($order->table->name . '(' . $order->table->position . ')') : ucfirst($order->type),
                    'total_price' => ($order->restaurant->currency_symbol ? $order->restaurant->currency_symbol : '$') . '' . $order->total_price,
                    'status' => str_replace('_', ' ', ucfirst($order->status)),
                    'item_name' => $onTheWayItemList,
                ];
            }

            if ($order->status == 'delivered') {
                foreach ($order->details as $key=>$detail) {
                    $item_name = str_replace(',','',$detail->item->name);
                    $deliveredItemList[$key] = "<li class='mt-2'>$item_name</li>";
                }
                $deliveredOrder[] = [
                    'id' => $order->id,
                    'live_created_at' => $order->created_at->format("Y-m-d H:m:s"),
                    'created_at' => $order->created_at->diffForHumans(),
                    'order_status' => $order->status,
                    'delivered_within' => str_replace('_', ' ', $order->delivered_within),
                    'type' => $order->type == 'pay_on_table' ? ($order->table->name . '(' . $order->table->position . ')') : ucfirst($order->type),
                    'total_price' => ($order->restaurant->currency_symbol ? $order->restaurant->currency_symbol : '$') . '' . $order->total_price,
                    'status' => str_replace('_', ' ', ucfirst($order->status)),
                    'item_name' => $deliveredItemList,
                ];
            }
        }

        return response()->json(['status'=>'success','data' => ['pending_orders'=>$pendingOrder, 'approved_orders'=>$approvedOrder, 'ready_for_delivery_orders'=>$onTheWayOrder, 'delivered_orders'=>$deliveredOrder]]);
    }


    public function show(Request $request)
    {
        $data['order'] = $order = Order::with(['details', 'extras'])->find($request->id);
        if (!$order) return redirect()->back()->withErrors(['msg' => 'Order not found']);

        return view('order.details', $data);

    }

    public function destroy(Request $request)
    {
        //
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'item_id.*' => 'required',
            'item_quantity.*' => 'required',
            'name' => 'required|max:191',
            'restaurant' => 'required',
            'address' => 'max:191',
            'phone_number' => 'max:20',
            'comment' => 'max:191',
        ]);
        $modules = modules_status('MultiRestaurant');
        if ($modules && auth()->user()){
            $itemIds = [];
            foreach ($request->item_id as $item_id) {
                $itemIds[] = $item_id;
            }
            Cart::whereIn('item_id',$itemIds)->where('customer_id',auth()->user()->id)->delete();
        }

        $restaurant = Restaurant::find($request->restaurant);
        if (!$restaurant) return redirect()->back()->withErrors(['msg' => trans('layout.message.order_not_found')]);

        $orderStatus = json_decode(get_settings('manage_place_order'));
        if (isset($orderStatus->admin_order_status) && $orderStatus->admin_order_status == 'disable' && isset($restaurant->order_status) && $restaurant->order_status == 'disable') {
            return redirect()->back()->withErrors(['fail' => trans('You can not place order right now, please try again later')]);
        }

        $auth = auth()->user();
        if (!$auth && $request->selectDeliveryType != 'delivery') {
            if ($request->selectDeliveryType == 'takeaway') {
                if (!$request->paymentMethod) {
                    return redirect()->back()->withErrors(['fail' => trans('layout.message.select_payment_method')]);
                }
            } elseif ($request->selectDeliveryType == 'pay_on_table') {
                if (!$request->table_id) {
                    return redirect()->back()->withErrors(['fail' => trans('layout.message.select_a_table')]);
                }
            }else{
                return redirect()->back()->withErrors(['fail' => 'Please select a delivery type first']);
            }

            if (!$request->phone) {
                return redirect()->back()->withErrors(['fail' => 'Please provide your phone number, then you can make order']);
            }
            if (!$request->email) {
                return redirect()->back()->withErrors(['fail' => 'Please provide your email address, then you can make order']);
            }
        }

        $order = new Order();
        $order->user_id = $auth ? $auth->id : null;
        $order->name = $request->name;

        $order->restaurant_id = $request->restaurant;
        if ($auth) {
            $order->email = $request->email;
        }

        if ($request->selectDeliveryType == 'table') {
            $order->type = $order->type = 'pay_on_table';
            $order->table_id = $request->table_id;
        } elseif ($request->selectDeliveryType == 'delivery') {
            $order->type = 'delivery';
            $order->address = $request->address;
        } elseif ($request->selectDeliveryType == 'takeaway') {
            $order->type = 'takeaway';
        }

        $order->phone_number = $request->phone;
        if ($request->pay_type == 'pay_on_table') {
            $order->payment_status = 'unpaid';
        }
        $order->comment = $request->comment;
        $order->save();

        $totalPrice = 0;
        $totalTax = 0;
        $orderDetailsData = [];
        $i = 0;
        foreach ($request->item_id as $key => $item_id) {
            $orderQuantity = $request->item_quantity[$key];
            $item = Item::where(['id' => $item_id, 'restaurant_id' => $request->restaurant])->first();
            $price = $item->price;
            $discountPrice = 0;

            if ($item) {
                if ($item->discount > 0) {
                    if ($item->discount_type == 'flat') {
                        $discountPrice = $item->discount;
                        $price = $item->price - $discountPrice;
                    } elseif ($item->discount_type == 'percent') {
                        $discountPrice = ($item->price * $item->discount) / 100;
                        $price = $item->price - $discountPrice;
                    }
                } else {
                    $price = $item->price;
                }
                $taxAmount = 0;
                if ($item->tax && $item->tax->type) {
                    $taxAmount = $item->tax->amount;
                    if ($item->tax->type == 'percentage') {
                        $taxAmount = ($taxAmount * $price) / 100;
                    }
                }
                $totalTax += $taxAmount * $orderQuantity;

                $orderDetailsData[$i]['order_id'] = $order->id;
                $orderDetailsData[$i]['item_id'] = $item->id;
                $orderDetailsData[$i]['price'] = $price;
                $orderDetailsData[$i]['quantity'] = $orderQuantity;
                $orderDetailsData[$i]['discount'] = $discountPrice;
                $orderDetailsData[$i]['total'] = $price * $orderQuantity;
                $orderDetailsData[$i]['tax_amount'] = $taxAmount * $orderQuantity;
                $orderDetailsData[$i]['status'] = 'approved';
                $orderDetailsData[$i]['created_at'] = now();
                $orderDetailsData[$i]['updated_at'] = now();
                $totalPrice += ($price * $orderQuantity);
                $i++;
            }
        }

        OrderDetails::insert($orderDetailsData);


        if ($request->extra_quantity) {
            foreach ($request->extra_quantity as $extra_id => $quantity) {
                $itemExtra = ItemExtra::find($extra_id);
                if ($itemExtra) {
                    $orderExtra = new OrderExtra();
                    $orderExtra->order_id = $order->id;
                    $orderExtra->item_id = $itemExtra->item_id;
                    $orderExtra->item_extra_id = $itemExtra->id;
                    $orderExtra->title = $itemExtra->title;
                    $orderExtra->price = $itemExtra->price;
                    $orderExtra->quantity = (double)$quantity;
                    $orderExtra->save();
                    $totalPrice += $itemExtra->price * (double)$quantity;
                }
            }
        }
        $order->total_price = $totalPrice + $totalTax;
        $order->save();

        if ($order->user_id)
            notification('order', $order->id, $order->user_id, "A new order has been placed");

        notification('order', $order->id, $restaurant->user_id, "A new order has been placed");

        try {
            $emailTemplate = EmailTemplate::where('type', 'order_placed')->first();
            if ($emailTemplate) {

                if ($auth) {
                    $customerEmailTemp = str_replace('{customer_name}', $auth->name, $emailTemplate->body);
                    $customerEmailTemp = str_replace('{order_no}', $order->id, $customerEmailTemp);
                    $customerEmailTemp = str_replace('{total_amount}', formatNumberWithCurrSymbol($order->total_price), $customerEmailTemp);
                    SendMail::dispatch($auth->email, $emailTemplate->subject, $customerEmailTemp);
                }

                if (!$auth) {
                    if ($order->email) {
                        $customerEmailTemp = str_replace('{customer_name}', $order->name, $emailTemplate->body);
                        $customerEmailTemp = str_replace('{order_no}', $order->id, $customerEmailTemp);
                        $customerEmailTemp = str_replace('{total_amount}', formatNumberWithCurrSymbol($order->total_price), $customerEmailTemp);
                        SendMail::dispatch($order->email, $emailTemplate->subject, $customerEmailTemp);
                    }
                }

                if ($restaurant->user) {
                    $resEmailTemp = str_replace('{customer_name}', $restaurant->user->name, $emailTemplate->body);
                    $resEmailTemp = str_replace('{order_no}', $order->id, $resEmailTemp);
                    $resEmailTemp = str_replace('{total_amount}', formatNumberWithCurrSymbol($order->total_price), $resEmailTemp);
                    SendMail::dispatch($restaurant->user->email, $emailTemplate->subject, $resEmailTemp);
                }
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }

        if ($request->pay_type == 'pay_now') {
            if ($request->paymentMethod == 'paypal') {
                try {

                    $payment = $this->paypalPayment($order, $restaurant);
                    if ($payment)
                        return redirect()->to($payment->getApprovalLink());

                } catch (\Exception $ex) {
                    Log::error($ex);
                    return redirect()->back()->withErrors(['msg' => trans('layout.message.invalid_payment')]);
                }
            } else if ($request->paymentMethod == 'stripe') {
                try {

                    $payment = $this->stripePayment($order, $request);
                    Log::info($payment->amount);
                    Log::info(number_format($order->total_price, 2) * 100);

                    if (!isset($payment->status) || $payment->status != 'succeeded' || $payment->amount != number_format($order->total_price, 2) * 100) {
                        throw new \Exception(trans('layout.message.invalid_payment'));
                    }
                    $order->transaction_id = $payment->id;
                    $order->payment_status = 'paid';
                    $order->save();
                    return redirect()->back()->with('order-success', trans('layout.message.order_placed'));
                } catch (\Exception $ex) {
                    Log::error($ex);
                    return redirect()->back()->withErrors(['msg' => trans('layout.message.invalid_payment')]);
                }
            } else if ($request->paymentMethod == 'paytm') {
                try {
                    $paytmData = $this->payTmPayment($order, $restaurant);

                    return view('payment.paytm', $paytmData);
                    //  return redirect()->back()->with('order-success', trans('layout.message.order_placed'));
                } catch (\Exception $ex) {
                    Log::error($ex->getMessage());
                    return redirect()->back()->withErrors(['msg' => trans('layout.message.invalid_payment')]);
                }
            } else if ($request->paymentMethod == 'mollie') {
                try {
                    $mollieData = $this->molliePayment($order, $restaurant);
                    if ($mollieData && $mollieData->id) {
                        $order->transaction_id = $mollieData->id;
                        $order->save();
                        return redirect()->to($mollieData->getCheckoutUrl());
                    }
                } catch (\Exception $ex) {
                    Log::error($ex->getMessage());
                    return redirect()->back()->withErrors(['msg' => trans('layout.message.invalid_payment')]);
                }
            } else if ($request->paymentMethod == 'paystack') {
                try {
                    $paystackData = $this->payStackPayment($order, $request, $restaurant);
                    if ($paystackData) {
                        return $paystackData->redirectNow();
                    }
                } catch (\Exception $ex) {
                    Log::error($ex->getMessage());
                    return redirect()->back()->withErrors(['msg' => trans('layout.message.invalid_payment')]);
                }
            }
            // $order->time = $request->time;
//            $order->save();
        }

//        if ($request->pay_type == 'pay_on_table') {
//            return redirect()->back()->with('order-success', trans('layout.message.order_placed'));
//        }

//        if ($request->pay_type == 'takeaway') {
//
//            return redirect()->back()->with('order-success', trans('layout.message.order_placed'));
//        }
        return redirect()->back()->with('order-success', trans('layout.message.order_placed'));

    }

    public function updateStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        if (!$order) return response()->json(['failed' => trans('layout.message.order_not_found')]);
        if ($request->pay_status) {
            $order->update(['payment_status' => $request->pay_status]);

            //  $orderDetails=OrderDetails::where('order_id',$order->id)->status('status',$request->status);
            if ($order->user_id) {
                $customer = User::find($order->user_id);
                try {
                    $data['order'] = $order = Order::with(['details', 'extras'])->find($request->order_id);
                    $data['currency'] = $order->restaurant->user->currency;
                    $customPaper = array(0, 0, 567.00, 283.40);
                    $pdf = \PDF::loadView('pdf.order_details', $data)->setPaper($customPaper, 'landscape');

                    Storage::put('Assets/invoice' . '' . $order->id . '' . '.pdf', $pdf->output());
                    $getPdf = Storage::get('Assets/invoice' . '' . $order->id . '' . '.pdf');

                    SendMail::dispatch($customer->email, 'Payment', 'Payment has been successfully', $order->id);

                } catch (\Exception $ex) {
                    Log::error($ex);
                }
            } else {
                if ($order->email) {
                    try {
                        $data['order'] = $order = Order::with(['details', 'extras'])->find($request->order_id);
                        $data['currency'] = $order->restaurant->user->currency;
                        $customPaper = array(0, 0, 567.00, 283.40);
                        $pdf = \PDF::loadView('pdf.order_details', $data)->setPaper($customPaper, 'landscape');

                        Storage::put('Assets/invoice' . '' . $order->id . '' . '.pdf', $pdf->output());
                        $getPdf = Storage::get('Assets/invoice' . '' . $order->id . '' . '.pdf');

                        SendMail::dispatch($order->email, 'Payment', 'Payment has been successfully', $order->id);

                    } catch (\Exception $ex) {
                        Log::error($ex);
                    }
                }
            }
        } else if ($request->status) {
            if ($request->status == 'approved') {
                $request->validate([
                    'time' => 'required|numeric',
                    'type' => 'required|in:minutes,hours,days',
                ]);
                $order->update(['status' => $request->status, 'approved_at' => now(), 'delivered_within' => $request->time . '_' . $request->type]);
            } else {
                $order->update(['status' => $request->status]);
            }
        }
        if ($order->user_id)
            notification('order', $order->id, $order->user_id, "Your order #" . $order->id . " status has been updated");
        $customer = User::find($order->user_id);
        try {
            $emailTemplate = EmailTemplate::where('type', 'order_status')->first();
            if ($emailTemplate) {
                if ($customer) {
                    $customerEmailTemp = str_replace('{customer_name}', $customer->name, $emailTemplate->body);
                    $customerEmailTemp = str_replace('{order_no}', $order->id, $customerEmailTemp);
                    $customerEmailTemp = str_replace('{status}', $order->status, $customerEmailTemp);
                    SendMail::dispatch($customer->email, $emailTemplate->subject, $customerEmailTemp);
                } else {
                    $customerEmailTemp = str_replace('{customer_name}', $order->name, $emailTemplate->body);
                    $customerEmailTemp = str_replace('{order_no}', $order->id, $customerEmailTemp);
                    $customerEmailTemp = str_replace('{status}', $order->status, $customerEmailTemp);
                    SendMail::dispatch($order->email, $emailTemplate->subject, $customerEmailTemp);
                }
            }
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
        }

        if (!$request->ajax()) return redirect()->back()->with('success', trans('layout.message.order_status_update'));

        return response()->download($getPdf)->json(['success' => trans('layout.message.order_status_update')]);
    }

    public function getData(Request $request)
    {

        $authUser = auth()->user();
        if ($authUser->type == 'restaurant_owner') {
            $restaurants = Restaurant::where('user_id', $authUser->id)->pluck('id');
         // $orders = Order::whereIn('restaurant_id', $restaurants)->orWhere('user_id', $authUser->id)->orderBy('created_at', 'desc')->get();

            $orders = Order::whereIn('restaurant_id', $restaurants);

            if($request->paid){
                $orders =  $orders->where('payment_status','paid');
            }
            if ($request->unpaid){
                $orders =  $orders->where('payment_status','unpaid');
            }
            if ($request->to_date && $request->from_date){
                $orders =  $orders->whereBetween('created_at',[$request->from_date,$request->to_date]);
            }
            $orders = $orders->get();

        } elseif ($authUser->type == 'user') {
            $orders = Order::where('restaurant_id', $authUser->restaurant_id)->orderBy('created_at', 'desc')->get();
        } else if ($authUser->type == 'customer') {
            $orders = Order::where('user_id', $authUser->id)->orderBy('created_at', 'desc')->get();
        } else {
            $orders = Order::orderBy('created_at', 'desc')->get();
        }
        $newItem = 0;


        $newData = [];
        if ($authUser->hasPermissionTo('order_payment_status_change')) {
            $paidString = "<div class=\"btn-group mb-1 show\">
                                <div class=\"btn-group mb-1\">
                                    <button  class=\"btn btn-success light btn-xs dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"false\">"
                . trans('layout.paid') . "</button>
                                     <div class=\"dropdown-menu\" x-placement=\"top-start\" style=\"position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -193px, 0px);\">
                                            <a data-message='" . trans('layout.message.order_status_warning', ['status' => 'unpaid']) .
                "' data-method='post' data-action='#{data_action}' data-input='#{data_input}' data-toggle=\"modal\" data-isAjax=\"true\" data-target=\"#modal-confirm\" class=\"dropdown-item\"
                                         href=\"#\">" . trans('layout.unpaid') . "</a>
                                     </div>
                                </div>
                          </div>";

            $unpaidString = "<div class=\"btn-group mb-1 show\">
                                <div class=\"btn-group mb-1\">
                                    <button  class=\"btn btn-danger light btn-xs dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"false\">"
                . trans('layout.unpaid') . "</button>
                                    <div class=\"dropdown-menu\" x-placement=\"top-start\" style=\"position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -193px, 0px);\">
                                        <a data-message='" . trans('layout.message.order_status_warning', ['status' => 'paid']) .
                "' data-method='post' data-action='#{data_action}' data-input='#{data_input}' data-toggle=\"modal\" data-isAjax=\"true\" data-target=\"#modal-confirm\"
                                                class=\"dropdown-item\" href=\"#\">" . trans('layout.paid') . "</a>
                                    </div>
                                </div>
                             </div>";

            $reviewString = "<div class=\"btn-group mb-1 show\">
                                <div class=\"btn-group mb-1\">
                                    <button  class=\"btn btn-danger light btn-xs dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" aria-expanded=\"false\">"
                . trans('layout.review') . "</button>
                                    <div class=\"dropdown-menu\" x-placement=\"top-start\" style=\"position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -193px, 0px);\">
                                        <a data-message='" . trans('layout.message.order_status_warning', ['status' => 'paid']) .
                "' data-method='post' data-action='#{data_action}' data-input='#{data_input}' data-toggle=\"modal\" data-isAjax=\"true\" data-target=\"#modal-confirm\"
                                                class=\"dropdown-item\" href=\"#\">" . trans('layout.paid') . "</a>
                                    </div>
                                </div>
                             </div>";

        } else {
            $paidString = "<button type='button' class='btn btn-success light btn-xs'>" . trans('layout.paid') . "</button>";
            $unpaidString = "<button type='button' class='btn btn-danger light btn-xs'>" . trans('layout.unpaid') . "</button>";
            $reviewString = "<button type='button' class='btn btn-danger light btn-xs'>" . trans('layout.review') . "</button>";
        }

        foreach ($orders as $key => $order) {
            $vars = [
                '#{data_input}' => json_encode(['pay_status' => $order->payment_status == 'paid' ? 'unpaid' : 'paid', 'order_id' => $order->id]),
                '#{data_action}' => route('order.update.status')
            ];
            $newData[$key]['row'] = $key + 1;
            $newData[$key]['id'] = $order->id;
            $newData[$key]['name'] = $order->name;
            $newData[$key]['restaurant_name_table'] = $order->restaurant->name . '(' . $order->table->name . ')';
            $newData[$key]['order_type'] = $order->type;
            $newData[$key]['type'] = str_replace('_', ' ', $order->type . '(' . $order->address . ')');
            if ($order->time) $newData[$key]['type'] .= "(" . $order->time . ")";
            // $newData[$key]['table'] = $order->table->name;
            $newData[$key]['total_price'] = isset($order->restaurant->currency_symbol) ? $order->restaurant->currency_symbol . '' . $order->total_price : formatNumberWithCurrSymbol($order->total_price);
            if ($order->approved_at)
                $newData[$key]['delivered_within'] = $order->delivered_within . ' <span style="front-size: 10px">(approved: ' . $order->approved_at->diffForHumans() . ')</span>';
            else
                $newData[$key]['delivered_within'] = $order->delivered_within;
            if ($order->payment_status == 'unpaid')
                $newData[$key]['payment_status'] = strtr($unpaidString, $vars);
            else if ($order->payment_status == 'review')
                $newData[$key]['payment_status'] = strtr($reviewString, $vars);
            else if ($order->payment_status == 'paid')
                $newData[$key]['payment_status'] = strtr($paidString, $vars);

            $status = '';
            if ($order->status == 'pending')
                $status = '<span class="badge badge-warning">' . trans('layout.pending') . '</span>';
            elseif ($order->status == 'approved')
                $status = '<span class="badge badge-primary">' . trans('layout.processing') . '</span>';
            elseif ($order->status == 'rejected')
                $status = '<span class="badge badge-danger">' . trans('layout.rejected') . '</span>';
            elseif ($order->status == 'ready_for_delivery')
                $status = '<span class="badge  badge-info">' . trans('layout.on_the_way') . '</span>';
            elseif ($order->status == 'delivered')
                $status = '<span class="badge badge-success">' . trans('layout.delivered') . '</span>';

            $orderDetails = OrderDetails::where('order_id', $order->id)->where('status', 'pending')->count();

            $newItemBtn = '<button data-order-id="' . $order->id . '" class="badge btn btn-sm badge-danger light details"> <small>' . $orderDetails . '</small> new</button>';

            $newData[$key]['raw_status'] = $status;
            $newData[$key]['status'] = $order->status;
            $newData[$key]['new_item'] = $newItemBtn;
            $newData[$key]['action'] = "";
        }

        return response()->json(['data' => $newData, "draw" => 1,
            "recordsTotal" => $orders->count(),
            "recordsFiltered" => $orders->count()]);
    }

    public function printDetails(Request $request)
    {
        $data['order'] = $order = Order::with(['details', 'extras'])->find($request->id);
        $data['currency'] = $order->restaurant->user->currency;
        if (!$order) return abort(404);

        $customPaper = array(0, 0, 567.00, 283.40);

        $pdf = \PDF::loadView('pdf.order_details', $data)->setPaper($customPaper, 'landscape');
        if ($request->type == 'pdf') {
            return $pdf->download(time() . '-order-' . $order->id . '.pdf');
        } else
            return $pdf->stream('order.pdf');

        // return view('pdf.order_details', $data);
    }


//    payment related

// #section paypal
    public function processSuccess(Request $request)
    {
        $restaurant = Restaurant::find($request->restaurant);
        if (!$restaurant) abort(404);

        $credentials = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentials->value) ? json_decode($credentials->value) : '';
        if (!isset($credentials->paypal_client_id) || !isset($credentials->paypal_secret_key) || !$credentials->paypal_client_id || !$credentials->paypal_secret_key) {
            return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->withErrors(['msg' => trans('layout.message.invalid_payment')]);
        }
        $apiContext = $this->getPaypalApiContext($credentials->paypal_client_id, $credentials->paypal_secret_key);

        $paymentId = $request->paymentId;
        $order_id = $request->order;

        if (!$paymentId || !$order_id) {
            return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->withErrors(['msg' => trans('layout.message.invalid_payment')]);
        }

        try {
            $payment = Payment::get($paymentId, $apiContext);
        } catch (\Exception $ex) {
            exit(1);
        }

        if (!$payment) return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->withErrors(['msg' => trans('layout.message.invalid_payment')]);


        $url = $payment->getRedirectUrls();
        $parsed_url = parse_url($url->getReturnUrl());
        $query_string = $parsed_url["query"];
        parse_str($query_string, $array_of_query_string);

        if ($array_of_query_string["restaurant"] != $restaurant->id || $array_of_query_string["order"] != $order_id || $array_of_query_string['paymentId'] != $paymentId) {
            return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->withErrors(['msg' => trans('layout.message.invalid_payment')]);
        }

        $order = Order::where(['id' => $order_id, 'restaurant_id' => $restaurant->id])->where(function ($q) use ($paymentId) {
            $q->whereNotIn('transaction_id', [$paymentId])->orWhereNull('transaction_id');
        })->first();

        if (!$order) {
            return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->withErrors(['msg' => trans('layout.message.invalid_payment')]);
        }

        $order->payment_status = 'paid';
        $order->transaction_id = $paymentId;
        $order->save();

        return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->with('order-success', trans('layout.message.order_placed'));

    }

    function paypalPayment($order, $restaurant)
    {
        $credentialValue = get_restaurant_gateway_settings($restaurant->user_id);

        $credentials = isset($credentialValue->value) ? json_decode($credentialValue->value) : '';
        if (!isset($credentials->paypal_client_id) || !isset($credentials->paypal_secret_key) || !$credentials->paypal_client_id || !$credentials->paypal_secret_key) {
            throw new \Exception(trans('layout.message.invalid_payment'));
        }
        $apiContext = $this->getPaypalApiContext($credentials->paypal_client_id, $credentials->paypal_secret_key);
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod('paypal');

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal($order->total_price);

        if ($restaurant->currency_code) {
            $amount->setCurrency($restaurant->currency_code); //TODO:: get the currency
        } else {
            $amount->setCurrency(get_currency()); //TODO:: get the currency
        }


        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl(route('order.payment.process.success', ['restaurant' => $restaurant->id, 'order' => $order->id]))
            ->setCancelUrl(route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id]));

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions(array($transaction))
            ->setRedirectUrls($redirectUrls);

        try {
            $payment->create($apiContext);
            return $payment;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            // This will print the detailed information on the exception.
            //REALLY HELPFUL FOR DEBUGGING
            throw new \Exception($ex->getData());
        }

    }

    function getPaypalApiContext($client_id, $secret_key)
    {

        return new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $client_id,     // ClientID
                $secret_key      // ClientSecret
            )
        );
    }

// #endsection

    function stripePayment($order, $req)
    {
        $restaurant = Restaurant::find($order->restaurant_id);
        $credentialValue = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentialValue->value) ? json_decode($credentialValue->value) : '';


        if (!$req->stripeToken || !isset($credentials->stripe_publish_key) || !isset($credentials->stripe_secret_key) || !$credentials->stripe_publish_key || !$credentials->stripe_secret_key) {
            throw new \Exception(trans('layout.message.invalid_payment'));
        }
        $stripe = new \Stripe\StripeClient($credentials->stripe_secret_key);

        return $stripe->paymentIntents->retrieve($req->stripeToken);
    }


    function processPaytmOrderRedirect(Request $request)
    {

        if (!$request->ORDERID || !$request->TXNID || !$request->TXNAMOUNT || !$request->STATUS || !$request->CHECKSUMHASH) {
            return redirect()->route('login')->withErrors(['msg' => trans('layout.message.invalid_payment')]);
        }
        $orderId = $request->ORDERID;
        $orderId = isset(explode('_', $orderId)[1]) ? explode('_', $orderId)[1] : '';

        $order = Order::find($orderId);
        if (!$order) return redirect()->route('login')->withErrors(['msg' => trans('layout.message.invalid_payment')]);

        $restaurant = Restaurant::find($order->restaurant_id);
        $credentials = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentials->value) ? json_decode($credentials->value) : '';
        if (!$credentials->paytm_environment || !$credentials->paytm_mid || !$credentials->paytm_secret_key || !$credentials->paytm_website || !$credentials->paytm_txn_url) {
            throw new \Exception(trans('layout.message.invalid_payment'));
        }

        $paytmParams = $_POST;

        $paytmChecksum = $_POST['CHECKSUMHASH'];
        unset($paytmParams['CHECKSUMHASH']);

        $isVerifySignature = PaytmChecksum::verifySignature($paytmParams, $credentials->paytm_secret_key, $paytmChecksum);
        if (!$isVerifySignature) return redirect()->route('login')->withErrors(['msg' => trans('layout.message.invalid_payment')]);


        if ($request->TXNAMOUNT != format_number($order->total_price, 2)) return redirect()->route('login')->withErrors(['msg' => trans('layout.message.invalid_payment')]);

        if ($request->STATUS != 'TXN_SUCCESS') return redirect()->route('login')->withErrors(['msg' => trans('layout.message.cancel_payment')]);

        $order->transaction_id = $request->TXNID;
        $order->payment_status = 'review';
        $order->save();

        return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->with('order-success', trans('layout.message.order_placed'));

    }

    //Mollie Payment
    function molliePayment($order, $restaurant)
    {

        $restaurant = Restaurant::find($order->restaurant_id);
        $credentialValue = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentialValue->value) ? json_decode($credentialValue->value) : '';

        if ($restaurant->currency_code) {
            $currencyCode = $restaurant->currency_code;
        } else {
            $currencyCode = get_currency();
        }

        if (!$credentials->mollie_api_key) {
            throw new \Exception(trans('layout.message.invalid_payment'));
        }

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($credentials->mollie_api_key);
        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => $currencyCode,
                "value" => $order->total_price . ""
            ],
            "description" => "For Order #" . $order->id,
            "redirectUrl" => route('payment.mollie.redirect-order', ['restaurant' => $order->restaurant_id]),
            "webhookUrl" => route('payment.mollie.webhook', ['id' => $order->id]),
        ]);

        return $payment;
    }

    public function processMollieOrderRedirect(Request $request)
    {
        $restaurant = Restaurant::find($request->restaurant);
        if (!$restaurant) exit("Invalid request");
        return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->with('order-success', trans('layout.message.order_placed'));

    }

    public function processMollieWebhook($order_id, Request $request)
    {
        if (!$order_id) {
            Log::info("order not found");
            exit;
        };

        $order = Order::find($order_id);

        if (!$order) {
            Log::info("order not found -" . $order->id);
            exit;
        };

        $restaurant = Restaurant::find($order->restaurant_id);
        $credentialValue = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentialValue->value) ? json_decode($credentialValue->value) : '';
        if (!$credentials || !$credentials->mollie_api_key || $credentials->mollie_status != 'active') {
            Log::info(trans('layout.message.invalid_payment'));
            exit();
        }

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey($credentials->mollie_api_key);
        $payment = $mollie->payments->get($request->id);
        if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
            $order->payment_status = 'paid';
            $order->save();
        }

    }

    //End Mollie Payment

    //PayStack
    function payStackPayment($order, $request, $restaurant)
    {

        $restaurant = Restaurant::find($order->restaurant_id);
        $credentialValue = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentialValue->value) ? json_decode($credentialValue->value) : '';

        if (!isset($credentials->paystack_public_key) || !$credentials->paystack_secret_key || $credentials->paystack_status != 'active') {
            throw new \Exception(trans('layout.message.invalid_payment'));
        }

        $data = [
            'secretKey' => $credentials->paystack_secret_key,
            'publicKey' => $credentials->paystack_public_key,
            'paymentUrl' => $credentials->paystack_payment_url
        ];

        if ($credentials->paystack_merchant_email) {
            $data['merchantEmail'] = $credentials->paystack_merchant_email;
        }

        if ($restaurant->currency_code) {
            $currencyCode = $restaurant->currency_code;
        } else {
            $currencyCode = get_currency();
        }

        Config::set('paystack', $data);

        $paystack = new Paystack();
        $user = auth()->user();
        $request->email = $user ? $user->email : 'no_user@demo.com';
        $request->orderID = "ORD_" . $order->id;
        $request->amount = $order->total_price * 100;
        $request->quantity = 1;
        $request->currency = $currencyCode;
        $request->reference = $paystack->genTranxRef();
        $request->callback_url = route('order.payment.paystack.process', ['order' => $order->id]);
        $request->metadata = json_encode(['user_order' => $order->id]);
        return $paystack->getAuthorizationUrl();

    }


    public function processPaystackPayment(Request $request)
    {

        $order_id = $request->order;
        if (!$order_id) {
            Log::info("order id not found ");
            exit;
        };

        $order = Order::find($order_id);

        if (!$order) {
            Log::info("order not found -" . $order_id);
            exit;
        };

        $restaurant = Restaurant::find($order->restaurant_id);
        if (!$restaurant) {
            Log::info("Restaurant not found -" . $order->restaurant_id);
            exit;
        };
        $credentialValue = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentialValue->value) ? json_decode($credentialValue->value) : '';

        if (!isset($credentials->paystack_public_key) || !$credentials->paystack_secret_key || $credentials->paystack_status != 'active') {
            Log::info("Credentials not found");
            return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->withErrors(['msg' => trans('layout.message.invalid_request')]);
        }

        $data = [
            'secretKey' => $credentials->paystack_secret_key,
            'publicKey' => $credentials->paystack_public_key,
            'paymentUrl' => $credentials->paystack_payment_url
        ];

        if ($credentials->paystack_merchant_email) {
            $data['merchantEmail'] = $credentials->paystack_merchant_email;
        }
        Config::set('paystack', $data);

        $paymentDetails = paystack()->getPaymentData();

        if (isset($paymentDetails['data']) && isset($paymentDetails['data']['id'])) {
            $order_id = isset($paymentDetails['data']['metadata']['user_order']) ? $paymentDetails['data']['metadata']['user_order'] : '';
            if (!$order_id || ($order_id != $order->id)) {
                Log::info("order not matched");
                return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->withErrors(['msg' => trans('layout.message.invalid_payment')]);

            };

            $order->transaction_id = $paymentDetails['data']['id'];
            $order->payment_status = 'paid';
            $order->save();

            return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->with('order-success', trans('layout.message.order_placed'));

        } else {
            return redirect()->route('show.restaurant', ['slug' => $restaurant->slug, 'id' => $restaurant->id])->withErrors(['msg' => trans('layout.message.invalid_payment')]);

        }
    }


    //end PayStack

    //get stripe token
    public function getStripeToken(Request $request)
    {
        $paymentSetting = json_decode(get_restaurant_gateway_settings($request->user_id)->value);

        if ($request->currency_code) {
            $currency_code = $request->currency_code;
        } else {
            $currency_code = get_currency();
        }
        if (isset($paymentSetting->stripe_secret_key) && $paymentSetting->stripe_status == 'active') {
            \Stripe\Stripe::setApiKey($paymentSetting->stripe_secret_key);
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $request->amount * 100,
                'currency' => $currency_code,
            ]);
            $client_secret = isset($paymentIntent->client_secret) ? $paymentIntent->client_secret : '';

            return response()->json(['status' => 'success', 'client_secret' => $client_secret]);
        }
        return response()->json(['status' => 'fail', 'client_secret' => '']);

    }

    public function getOrder(Request $request)
    {
        $authUser = auth()->user();
        $data = [];
        $order = Order::where('user_id', $authUser->id)->where('restaurant_id', $request->rest_id)->orderBy('created_at', 'desc')->firstOrFail();
        $orderDetails = OrderDetails::where('order_id', $order->id)->get();

        foreach ($orderDetails as $key => $orderDetail) {
            $data[$key]['order_id'] = $orderDetail->order_id;
            $data[$key]['id'] = $orderDetail->id;
            $data[$key]['item'] = $orderDetail->item->name;
            $data[$key]['total'] = $orderDetail->total;
            $data[$key]['quantity'] = $orderDetail->quantity;
        }
        $val = [
            'total' => $order->total_price
        ];
        return response()->json(['status' => 'success', 'data' => $data, 'val' => $val]);

    }

    public function orderUpdate(Request $request)
    {
        $authUser = auth()->user();

        $order = Order::where('user_id', $authUser->id)->where('id', $request->orderId)->where('status', '!=', 'delivered')->firstOrFail();
        $orderPreTotal = $order->total_price;
        $orderDetails = OrderDetails::where('id', $request->details_id)->where('order_id', $order->id)->first();


        $quantity = $request->quantity - $orderDetails->quantity;

        $orderDetails->quantity = $quantity;
        $orderDetails->save();

        if ($request->quantity <= $orderDetails->quantity) {
            return response()->json(['status' => 'failed']);
        }

        $orderDetail = new OrderDetails();
        $orderDetail->order_id = $orderDetails->order_id;
        $orderDetail->item_id = $orderDetails->item_id;
        $orderDetail->price = $orderDetails->price;
        $orderDetail->quantity = $quantity;
        $orderDetail->discount = $orderDetails->discount;
        $orderDetail->total = $orderDetails->total;
        $orderDetail->status = 'pending';
        $orderDetail->tax_amount = $orderDetails->tax_amount;
        $orderDetail->created_at = now();
        $orderDetail->updated_at = now();
        $orderDetail->save();
        $order->status = 'pending';
        $order->save();

        return response()->json(['status' => 'success', 'message' => trans('Order item has been updated')]);

    }

    public function addNewOrderItem(Request $request)
    {
        $authUser = auth()->user();
        $order = Order::where('user_id', $authUser->id)->orderBy('created_at', 'desc')->first();
        $orderPreTotal = $order->total_price;
        $item = Item::where('id', $request->item_id)->where('restaurant_id', $request->restaurant_id)->first();

        if ($item->discount > 0) {
            if ($item->discount_type == 'flat') {
                $discountPrice = $item->discount;
                $price = $item->price - $discountPrice;
            } elseif ($item->discount_type == 'percent') {
                $discountPrice = ($item->price * $item->discount) / 100;
                $price = $item->price - $discountPrice;
            }
        } else {
            $price = $item->price;
        }
        $taxAmount = 0;
        if ($item->tax && $item->tax->type) {
            $taxAmount = $item->tax->amount;
            if ($item->tax->type == 'percentage') {
                $taxAmount = ($taxAmount * $price) / 100;
            }
        }
        $totalAmount = $request->quantity * $item->price;
        $orderDetail = new OrderDetails();
        $orderDetail->order_id = $order->id;
        $orderDetail->item_id = $item->id;
        $orderDetail->price = $item->price;
        $orderDetail->quantity = $request->quantity;
        $orderDetail->discount = $totalAmount - $price;
        $orderDetail->total = $price;
        $orderDetail->status = 'pending';
        $orderDetail->tax_amount = $taxAmount;
        $orderDetail->created_at = now();
        $orderDetail->updated_at = now();
        $orderDetail->save();
        $order->status = 'pending';
        $order->save();

        return response()->json(['status' => 'success', 'message' => trans('New item has been added in your order')]);
    }

    public function quickOrderDetails(Request $request)
    {
        $data = [];
        $order_info = [];
        $total_tax = 0;
        $total_discount = 0;
        $order = Order::where('id', $request->orderId)->first();
        $orderDetails = OrderDetails::where('order_id', $order->id)->get();
        foreach ($orderDetails as $key => $orderDetail) {
            $data[$key]['key'] = ++$key;
            $data[$key]['item_name'] = $orderDetail->item->name;
            $data[$key]['currency_symbol'] = isset($order->restaurant->currency_symbol) ?
                $order->restaurant->currency_symbol : json_decode(get_settings('local_setting'))->currency_symbol;

            $data[$key]['order_id'] = $orderDetail->order_id;
            $data[$key]['id'] = $orderDetail->id;
            $data[$key]['quantity'] = $orderDetail->quantity;
            $data[$key]['price'] = $orderDetail->item->price;
            $data[$key]['discount'] = $orderDetail->discount;
            $data[$key]['detail_status'] = $orderDetail->status;
            $data[$key]['tax_amount'] = $orderDetail->tax_amount;
            $data[$key]['total'] = $orderDetail->total + $orderDetail->tax_amount;
            $total_discount += $orderDetail->discount;
            $total_tax += $orderDetail->tax_amount;
        }


        $order_info = [
            'total_tax' => $total_tax,
            'total_discount' => $total_discount,
            'total_price' => $order->total_price,
            'order_id' => $order->id,
            'order_status' => $order->status,
            'customer_name' => $order->name,
            'customer_email' => isset($order->user_id) && $order->user->email,
            'phone' => $order->phone_number,
            'address' => $order->type = 'delivary' ? $order->address : '',
            'currency_symbol' => isset($order->restaurant->currency_symbol) ?
                $order->restaurant->currency_symbol : json_decode(get_settings('local_setting'))->currency_symbol,
        ];

        return response()->json(['status' => 'success', 'data' => $data, 'info' => $order_info]);
    }


    public function settelementMode(Request $request)
    {
        $authUser = auth()->user();
        $order = Order::where('user_id', $authUser->id)->where('status', 'delivered')->orderBy('created_at', 'desc')->first();

        if ($request->payment_type == 'cash') {
            $order->payment_status = 'review';
            $order->save();
        } elseif ($request->payment_type == 'paytm') {
            try {
                $paytmData = $this->payTmPayment($order);

                return view('payment.paytm', $paytmData);
                //  return redirect()->back()->with('order-success', trans('layout.message.order_placed'));
            } catch (\Exception $ex) {
                Log::error($ex->getMessage());
                return redirect()->back()->withErrors(['msg' => trans('layout.message.invalid_payment')]);
            }
        }


        return redirect()->back()->with('success', trans('You payment has been success, you will get transaction mail ASAP'));
    }

    public function detailsStatus(Request $request)
    {
        $order = Order::where('id', $request->orderId)->first();
        $orderDetails = OrderDetails::where('id', $request->details_id)->where('order_id', $order->id)->first();

        if (!$orderDetails) {
            return response()->json(['status' => 'failed']);
        }
        $orderDetails->status = $request->status;
        $orderDetails->save();

        if ($request->status == 'approved') {
            $preTotal = $order->total_price;
            $order->total_price = $preTotal + $orderDetails->total;
            $order->save();
        }

        return response()->json(['status' => 'success', 'message' => 'Item status changed successfully']);
    }


    function payTmPayment($order)
    {
        $restaurant = Restaurant::find($order->restaurant_id);
        $credentials = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentials->value) ? json_decode($credentials->value) : '';
        if (!$credentials->paytm_environment || !$credentials->paytm_mid || !$credentials->paytm_secret_key || !$credentials->paytm_website || !$credentials->paytm_txn_url) {
            throw new \Exception(trans('layout.message.invalid_payment'));
        }

        $paytmParams = array();

        $orderId = "ORDERID_" . $order->id;
        $mid = $credentials->paytm_mid;
        $paytmParams["body"] = array(
            "requestType" => "Payment",
            "mid" => $mid,
            "websiteName" => $credentials->paytm_website,
            "orderId" => $orderId,
            "callbackUrl" => route('payment.paytm.redirect-order'),
            "txnAmount" => array(
                "value" => $order->total_price,
                "currency" => "INR",
            ),
            "userInfo" => array(
                "custId" => "CUST_" . $order->user_id,
            ),
        );

        $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $credentials->paytm_secret_key);

        $paytmParams["head"] = array(
            "signature" => $checksum
        );
        $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

        if ($credentials->paytm_environment == 'staging') {
            /* for Staging */
            $url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=" . $mid . "&orderId=" . $orderId;

        }

        if ($credentials->paytm_environment == 'production') {
            /* for Production */
            $url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=" . $mid . "&orderId=" . $orderId;

        }


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        $response = curl_exec($ch);
        Log::error($response);
        $response = json_decode($response);
        if (!isset($response->body) || !isset($response->body->resultInfo) || $response->body->resultInfo->resultStatus != 'S') {
            Log::error($response->body);
            throw new \Exception(trans('layout.message.invalid_payment'));
        }

        $data['response'] = $response;
        $data['mid'] = $mid;
        $data['order_id'] = $orderId;
        $data['environment'] = $credentials->paytm_environment;
        return $data;

    }

    public function stripePaymentIntent(Request $request)
    {

        $restaurant = Restaurant::find($request->restaurant_id);

        $credentialValue = get_restaurant_gateway_settings($restaurant->user_id);
        $credentials = isset($credentialValue->value) ? json_decode($credentialValue->value) : '';

        if (!isset($credentials->stripe_secret_key)) {
            throw new \Exception(trans('layout.message.invalid_payment'));
        }
        if (isset($credentials->stripe_secret_key) && $credentials->stripe_status == 'active') {
            \Stripe\Stripe::setApiKey($credentials->stripe_secret_key);
            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => $request->order_total_amount * 100,
                'currency' => get_currency(),
            ]);
            $data = isset($paymentIntent->client_secret) ? $paymentIntent->client_secret : '';
            return response()->json(['status' => 'success', 'data' => $data]);
        } else
            return response()->json(['status' => 'failed']);
    }

}
