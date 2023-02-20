<?php

use App\Models\EmailTemplate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
$modules = modules_status('MultiRestaurant');

if ($modules){
    Route::get('/home', [\App\Http\Controllers\FrontController::class, 'home'])->name('home');
}

Route::get('/', [\App\Http\Controllers\FrontController::class, 'index'])->name('index');
Route::get('/privacy/policy', [\App\Http\Controllers\FrontController::class, 'privacy_policy'])->name('privacy.policy');
Route::get('/terms/conditions', [\App\Http\Controllers\FrontController::class, 'terms_conditions'])->name('terms.conditions');

//guest
Route::group(['middleware' => 'guest'], function () {
    Route::get('/login', [\App\Http\Controllers\Auth\AuthController::class, 'login'])->name('login');
    Route::get('/registration', [\App\Http\Controllers\Auth\AuthController::class, 'registration'])->name('registration');
    Route::post('/user/store', [\App\Http\Controllers\Auth\AuthController::class, 'store'])->name('user.store');
    Route::post('/authenticate', [\App\Http\Controllers\Auth\AuthController::class, 'authenticate'])->name('authenticate');
    Route::get('/forget-password', [\App\Http\Controllers\Auth\AuthController::class, 'forgetPassword'])->name('forget.password');
    Route::post('/password/reset', [\App\Http\Controllers\Auth\AuthController::class, 'sendForgetPasswordCode'])->name('password.reset');
    Route::get('/password/reset/{token}', [\App\Http\Controllers\Auth\AuthController::class, 'passwordResetForm'])->name('password.reset.form')->middleware('signed');
    Route::post('/password/reset/confirm', [\App\Http\Controllers\Auth\AuthController::class, 'passwordResetConfirm'])->name('password.reset.confirm');

});


//email verification
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware(['auth'])->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (\Illuminate\Foundation\Auth\EmailVerificationRequest $request) {
    $request->fulfill();

    if(isset($request->plan) && $request->plan!=1){
        return redirect()->route('payment',['id'=>$request->plan])->with('success', trans('layout.message.verify'));
    }else{
        return redirect()->route('dashboard')->with('success', trans('layout.message.verify'));
    }
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Illuminate\Http\Request $request) {
    $user = $request->user();
    try {
        $emailTemplate = EmailTemplate::where('type', 'registration')->first();
        if ($emailTemplate) {
            $route = URL::temporarySignedRoute('verification.verify', now()->addHours(4), ['id' => $user->id, 'hash' => sha1($user->email)]);
            $regTemp = str_replace('{customer_name}', $user->name, $emailTemplate->body);
            $regTemp = str_replace('{click_here}', "<a href=" . $route . ">" . trans('layout.click_here') . "</a>", $regTemp);

            Mail::send('sendMail', ['htmlData' => $regTemp], function ($message) use ($user, $emailTemplate) {
                $message->to($user->email)->subject
                ($emailTemplate->subject);
            });
        }
    } catch (\Exception $ex) {

    }

    return back()->with('success', trans('layout.message.reset_link_send'));
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

//end email verification


Route::group(['middleware' => ['auth', 'verified']], function () {

    //Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    //Restaurant
    Route::resource('restaurant', \App\Http\Controllers\RestaurantController::class)->except('show')->middleware('can:restaurant_manage');
    Route::get('restaurant/custom-menu/{id}', [\App\Http\Controllers\RestaurantController::class, 'customMenuGenerate'])->name('restaurant.custom-menu')->middleware('can:restaurant_manage');
    Route::post('restaurant/custom-menu/store', [\App\Http\Controllers\RestaurantController::class, 'storeCustomMenu'])->name('restaurant.custom-menu.store')->middleware('can:restaurant_manage');
    Route::get('login-as', [\App\Http\Controllers\RestaurantController::class, 'loginAs'])->name('restaurant.login.as')->middleware('can:restaurant_manage');

    Route::get('show-qr', [\App\Http\Controllers\RestaurantController::class, 'showQr'])->middleware('can:qr_manage');

    //Item
    Route::resource('item', \App\Http\Controllers\ItemController::class)->middleware('can:item_manage');
    //Orders

    Route::get('check/recent/order', [\App\Http\Controllers\OrderController::class, 'liveOrderResponse'])->name('live.order.response');
    Route::get('live/order', [\App\Http\Controllers\OrderController::class, 'liveOrder'])->name('live.order')->middleware('can:order_list');
    Route::get('orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('order.index')->middleware('can:order_list');
    Route::get('orders/all', [\App\Http\Controllers\OrderController::class, 'getData'])->name('order.getAll')->middleware('can:order_list');
    Route::get('order/details', [\App\Http\Controllers\OrderController::class, 'show'])->name('order.show')->middleware('can:order_list');
    Route::get('order/print/{id}/', [\App\Http\Controllers\OrderController::class, 'printDetails'])->name('order.print')->middleware('can:order_list');
    Route::get('order/filtering/{type}', [\App\Http\Controllers\OrderController::class, 'order_filtering'])->name('order.filtering')->middleware('can:order_list');

    Route::delete('order/delete', [\App\Http\Controllers\OrderController::class, 'destroy'])->name('order.delete')->middleware('can:order_manage');
    Route::post('order/status/update', [\App\Http\Controllers\OrderController::class, 'updateStatus'])->name('order.update.status');

    //Settings
    Route::get('/settings', [\App\Http\Controllers\SettingsController::class, 'settings'])->name('settings');
    Route::post('', [\App\Http\Controllers\SettingsController::class, 'general'])->name('general')->middleware('can:general_setting');
    Route::post('/settings/password', [\App\Http\Controllers\SettingsController::class, 'password_update'])->name('password.update')->middleware('can:change_password');
    Route::post('/settings/email', [\App\Http\Controllers\SettingsController::class, 'emailSetting'])->name('email.settings')->middleware('can:email_setting');
    Route::post('/settings/side-bar', [\App\Http\Controllers\SettingsController::class, 'site_settings'])->name('side.bar.settings')->middleware('can:site_setting');
    Route::post('/settings/manage-place-order', [\App\Http\Controllers\SettingsController::class, 'managePlaceOrder'])->name('order.status.settings');
    Route::post('/settings/local', [\App\Http\Controllers\SettingsController::class, 'local_settings'])->name('settings.local')->middleware('can:local_setting');
    Route::post('/email-template/store', [\App\Http\Controllers\SettingsController::class, 'templateStore'])->name('email.template.store')->middleware('can:email_template_setting');

    Route::post('/payment-gateway/store', [\App\Http\Controllers\SettingsController::class, 'paymentGateway'])->name('payment.gateway')->middleware('can:payment_gateway_setting');
    Route::post('/sms-gateway/store', [\App\Http\Controllers\SettingsController::class, 'smsGateway'])->name('sms.gateway')->middleware('can:sms_gateway_setting');
    Route::post('/permission/update', [\App\Http\Controllers\SettingsController::class, 'permissionUpdate'])->name('settings.permission.update')->middleware('can:role_permission');
    Route::get('/permission/generate', [\App\Http\Controllers\SettingsController::class, 'permissionGenerate'])->name('permission.generate')->middleware('can:role_permission');
//PERMISSION
    Route::put('/permission-store', [\App\Http\Controllers\PermissionController::class, 'permissionStore'])->name('user.permission.store');
    Route::put('/user/permission/update', [\App\Http\Controllers\PermissionController::class, 'userPermissionUpdate'])->name('user.permission.update');
    Route::get('/user/role/delete', [\App\Http\Controllers\PermissionController::class, 'roleDelete'])->name('user.role.delete');

    Route::get('/payment', [\App\Http\Controllers\PaymentController::class, 'payment'])->name('payment')->middleware('can:billing');
    Route::post('/payment/process', [\App\Http\Controllers\PaymentController::class, 'process'])->name('payment.process')->middleware('can:billing');
    Route::get('/payment/process/success', [\App\Http\Controllers\PaymentController::class, 'processSuccess'])->name('payment.process.success')->middleware('can:billing');
    Route::get('/payment/process/cancelled', [\App\Http\Controllers\PaymentController::class, 'processCancelled'])->name('payment.process.cancel')->middleware('can:billing');

    //mollie payment
    Route::get('/payment/process/mollie', [\App\Http\Controllers\PaymentController::class, 'processMollieSuccess'])->name('payment.mollie.success')->middleware('can:billing');

//    Cancel Subscription
    Route::get('/cancel/subscription', [\App\Http\Controllers\PaymentController::class, 'subscriptionCancel'])->name('cancel.subscription');


    //Plans
    Route::get('/plan-list', [\App\Http\Controllers\PlanController::class, 'planList'])->name('plan.list')->middleware('can:billing');
    Route::resource('plan', \App\Http\Controllers\PlanController::class)->middleware('can:plan_manage');

    //User_plan
    Route::get('/user-plan', [\App\Http\Controllers\UserPlanController::class, 'index'])->name('user.plan')->middleware('can:user_plan_change');
    Route::post('/user-plan/approved', [\App\Http\Controllers\UserPlanController::class, 'status_change'])->name('user.plan.change')->middleware('can:user_plan_change');
    Route::post('/user-plan/rejected', [\App\Http\Controllers\UserPlanController::class, 'rejected'])->name('user.plan.reject')->middleware('can:user_plan_change');

    Route::resource('category', \App\Http\Controllers\CategoryController::class)->middleware('can:category_manage');

    //Table
    Route::resource('table', \App\Http\Controllers\TableController::class)->middleware('can:table_manage');

    //QR Maker
    Route::get('qr-maker', [\App\Http\Controllers\QrMakerController::class, 'index'])->name('qr.maker')->middleware('can:qr_manage');

    //Tax
    Route::resource('tax',\App\Http\Controllers\TaxController::class)->middleware('can:table_manage');

    Route::get('notification/check',[\App\Http\Controllers\SettingsController::class,'checkNotification']);

    //customer
    Route::get('/verified/user', [\App\Http\Controllers\CustomerController::class, 'verified'])->name('verified.user');
    Route::resource('/customers', \App\Http\Controllers\CustomerController::class)->parameter('customers','user');
    Route::get('/user/banned/{id}', [\App\Http\Controllers\CustomerController::class, 'user_banned'])->name('user.banned');
    Route::get('/user/approved/{id}', [\App\Http\Controllers\CustomerController::class, 'user_approved'])->name('user.approved');

    //Report
    Route::get('/reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('report.index')->middleware('can:report');

    Route::get('/stripe-token',[\App\Http\Controllers\OrderController::class,'getStripeToken'])->name('get.stripe.token');
    Route::get('/billings',[\App\Http\Controllers\BillingController::class,'index'])->name('billings');

//    Call Waiter
    Route::get('/call-waiter/',[\App\Http\Controllers\CallWaiterController::class,'show'])->name('call.waiter');
    Route::get('/call-waiter/status',[\App\Http\Controllers\CallWaiterController::class,'status'])->name('call.waiter.status');
    Route::get('/call-waiter/delete',[\App\Http\Controllers\CallWaiterController::class,'delete'])->name('call.waiter.delete');

    // Addon
    Route::get('/addon', [\App\Http\Controllers\AddonController::class, 'index'])->name('addon.index')->middleware('can:addon_manage');
    Route::post('/addon/changetatus', [\App\Http\Controllers\AddonController::class, 'changeStatus'])->name('addon.changeStatus')->middleware('can:addon_manage');
    Route::post('/addon/uninstall', [\App\Http\Controllers\AddonController::class, 'uninstall'])->name('addon.uninstall')->middleware('can:addon_manage');
    Route::get('/addon/import', [\App\Http\Controllers\AddonController::class, 'import'])->name('addon.import')->middleware('can:addon_manage');
    Route::post('/addon/import/store', [\App\Http\Controllers\AddonController::class, 'import_store'])->name('addon.import.store')->middleware('can:addon_manage');
    Route::get('/addon/event', [\App\Http\Controllers\AddonController::class, 'eventTrigger'])->name('addon.event')->middleware('can:addon_manage');

});

//Call Waiter
Route::post('/call-waiter/store',[\App\Http\Controllers\CallWaiterController::class,'store'])->name('call.waiter.store');

//process paytm payment
Route::post('/payment/paytm/success', [\App\Http\Controllers\PaymentController::class, 'processPaytmRedirect'])->name('payment.paytm.redirect');
Route::post('/payment/paytm/success-order', [\App\Http\Controllers\OrderController::class, 'processPaytmOrderRedirect'])->name('payment.paytm.redirect-order');

//process mollie payment
Route::get('/payment/mollie/success-order', [\App\Http\Controllers\OrderController::class, 'processMollieOrderRedirect'])->name('payment.mollie.redirect-order');
Route::post('/payment/mollie/webhook/{id}', [\App\Http\Controllers\OrderController::class, 'processMollieWebhook'])->name('payment.mollie.webhook');
Route::post('/payment/plan-change/mollie/{id}', [\App\Http\Controllers\PaymentController::class, 'processMollieWebhook'])->name('payment.changeplan.mollie.webhook');
// PayStack payment process
Route::get('/payment/paystack/process', [\App\Http\Controllers\PaymentController::class, 'processPaystackPayment'])->name('payment.paystack.process');
Route::get('/order/payment/paystack/process', [\App\Http\Controllers\OrderController::class, 'processPaystackPayment'])->name('order.payment.paystack.process');


Route::get('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('restaurants/{slug}', [\App\Http\Controllers\FrontController::class, 'show'])->name('show.restaurant');

Route::post('restaurant/order/place', [\App\Http\Controllers\OrderController::class, 'placeOrder'])->name('order.place');

//Stripe Payment Intend
Route::get('/stripe/payment-intent', [\App\Http\Controllers\OrderController::class, 'stripePaymentIntent'])->name('stripe.payment-intent');

Route::get('restaurant/order/payment/process', [\App\Http\Controllers\OrderController::class, 'processSuccess'])->name('order.payment.process.success');
Route::get('restaurant/order/payment/cancel', [\App\Http\Controllers\OrderController::class, 'processCancelled'])->name('order.payment.process.cancel');
Route::get('locale/{type}', [\App\Http\Controllers\FrontController::class, 'setLocale'])->name('set.locale');
Route::get('/process/upgrade', [\App\Http\Controllers\UpgradeController::class, 'process'])->name('process.upgrade');
Route::post('/subscribe', [\App\Http\Controllers\FrontController::class, 'subscribe'])->name('subscribe');
//Get Order Details
Route::get('/get-order', [\App\Http\Controllers\OrderController::class, 'getOrder'])->name('get.order');
Route::post('/order-update', [\App\Http\Controllers\OrderController::class, 'orderUpdate'])->name('order.update');
Route::post('/add-new/order/item', [\App\Http\Controllers\OrderController::class, 'addNewOrderItem'])->name('add.new.order.item');
Route::get('/quick-order/details', [\App\Http\Controllers\OrderController::class, 'quickOrderDetails'])->name('quick.order.details');
Route::get('/settelement/mode', [\App\Http\Controllers\OrderController::class, 'settelementMode'])->name('settelement.mode');
Route::post('/details-status', [\App\Http\Controllers\OrderController::class, 'detailsStatus'])->name('order.details.status');

Route::get('/template', [\App\Http\Controllers\TemplateController::class, 'index'])->name('template.index');
Route::post('/template/store', [\App\Http\Controllers\TemplateController::class, 'store'])->name('template.store');
