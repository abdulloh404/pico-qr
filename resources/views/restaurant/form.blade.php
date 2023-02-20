@csrf
<div>
    <h4>{{trans('layout.general_info')}}</h4>
    <section>
        <div class="row">
            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.name')}}*</label>
                    <input value="{{old('name')?old('name'):(isset($restaurant)?$restaurant->name:'')}}" type="text"
                           name="name" class="form-control" placeholder="Ex: The Disaster Cafe" required>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.location')}}</label>
                    <input value="{{old('location')?old('location'):(isset($restaurant)?$restaurant->location:'')}}"
                           type="text" name="location" class="form-control" id="locationInput"
                           placeholder="Ex: 2806 Montague Rd, BC, Canada">
                    <div class="city-par d-none">
                        <ul class="city-name pt-2 pb-2" id="inputShowCity"></ul>
                    </div>
                </div>
                <input type="hidden" id="lat" name="lat" value="{{old('lat')?old('lat'):(isset($restaurant) && isset($restaurant->direction)?json_decode($restaurant->direction)->lat:'')}}">
                <input type="hidden" id="long" name="long" value="{{old('long')?old('long'):(isset($restaurant) && isset($restaurant->direction)?json_decode($restaurant->direction)->long:'')}}">
            </div>
            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.email')}}</label>
                    <input value="{{old('email')?old('email'):(isset($restaurant)?$restaurant->email:'')}}" type="email"
                           name="email" class="form-control" placeholder="example@example.com">
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.phone_number')}}</label>
                    <input
                        value="{{old('phone_number')?old('phone_number'):(isset($restaurant)?$restaurant->phone_number:'')}}"
                        type="text" name="phone_number" class="form-control" placeholder="(+0)000-000-0000">
                </div>
            </div>

            <div class="{{auth()->user()->type=='restaurant_owner'?'col-lg-6':'col-lg-4'}} mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.currency_code')}}</label>
                    <input
                        value="{{old('currency_code')?old('currency_code'):(isset($restaurant)?$restaurant->currency_code:'')}}"
                        type="text" name="currency_code" class="form-control" placeholder="{{trans('Ex: usd or eur')}}">
                </div>
            </div>
            <div class="{{auth()->user()->type=='restaurant_owner'?'col-lg-6':'col-lg-4'}} mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.currency_symbol')}}</label>
                    <input
                        value="{{old('currency_symbol')?old('currency_symbol'):(isset($restaurant)?$restaurant->currency_symbol:'')}}"
                        type="text" name="currency_symbol" class="form-control">
                </div>
            </div>
            @if(auth()->user()->type=='admin')
                <div class="col-lg-4 mb-2">
                    <div class="form-group">
                        <label for="">{{trans('layout.select_a_user')}}</label>
                        <select name="user_id" class="form-control">
                            @foreach($customers as $customer)
                                <option
                                    {{isset($customer) && isset($restaurant->user_id) && $restaurant->user_id==$customer->id?'selected':''}} value="{{$customer->id}}">{{$customer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @php  $orderStatus = json_decode(get_settings('manage_place_order')); @endphp
            <div class="{{isset($orderStatus->admin_order_status) && $orderStatus->admin_order_status=='enable'?'col-lg-4':'col-lg-6'}} mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.timing')}}</label>
                    <input value="{{old('timing')?old('timing'):(isset($restaurant)?$restaurant->timing:'')}}"
                           type="text" name="timing" class="form-control" placeholder="Ex: 8:00 - 20:00">
                </div>
            </div>
            <div class="{{isset($orderStatus->admin_order_status) && $orderStatus->admin_order_status=='enable'?'col-lg-4':'col-lg-6'}} mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.status')}}*</label>
                    <select name="status" class="form-control">
                        <option
                            {{isset($restaurant) && $restaurant->status=='active'?'selected':''}} value="active">{{trans('layout.active')}}</option>
                        <option
                            {{isset($restaurant) && $restaurant->status=='inactive'?'selected':''}} value="inactive">{{trans('layout.inactive')}}</option>
                    </select>
                </div>
            </div>

            @if(isset($orderStatus->admin_order_status) && $orderStatus->admin_order_status=='enable')
            <div class="col-lg-4 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.order_settings')}}</label>
                    <select name="order_status" class="form-control">
                        <option
                            {{isset($restaurant) && $restaurant->order_status=='enable'?'selected':''}} value="enable">{{trans('layout.enable')}}</option>
                        <option
                            {{isset($restaurant) && $restaurant->order_status=='disable'?'selected':''}} value="disable">{{trans('layout.disable')}}</option>
                    </select>
                </div>
            </div>
            @endif
            @php
                $modules = modules_status('MultiRestaurant');
            @endphp
            @if($modules)
            <div class="col-lg-12 mb-3">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="cash_on_delivery_checkbox" value="yes" name="cash_on_delivery" {{isset($restaurant) && $restaurant->cash_on_delivery=='yes'?'checked':''}}>
                    <label class="form-check-label" for="cash_on_delivery_checkbox">Cash on Delivery @if (env('APP_DEMO'))<small class="addon">({{trans('multirestaurant::layout.addon')}})</small>@endif</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="takeaway_checkbox" value="yes" name="takeaway" {{isset($restaurant) && $restaurant->takeaway=='yes'?'checked':''}}>
                    <label class="form-check-label" for="takeaway_checkbox">Takeaway @if (env('APP_DEMO'))<small class="addon">({{trans('multirestaurant::layout.addon')}})</small>@endif</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" id="table_booking_checkbox" value="yes" name="table_booking" {{isset($restaurant) && $restaurant->table_booking=='yes'?'checked':''}}>
                    <label class="form-check-label" for="table_booking_checkbox">Table Book @if (env('APP_DEMO'))<small class="addon">({{trans('multirestaurant::layout.addon')}})</small>@endif</label>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.delivery_fee')}} @if (env('APP_DEMO'))<small class="addon">({{trans('multirestaurant::layout.addon')}})</small>@endif</label>
                    <input value="{{old('delivery_fee')?old('delivery_fee'):(isset($restaurant)?$restaurant->delivery_fee:'')}}" type="text"
                           name="delivery_fee" class="form-control" placeholder="Ex: 10" required>
                </div>
            </div>
                <div class="col-lg-6 mb-2">
                    <div class="form-group">
                        <label class="text-label" >{{trans('layout.publish_on_multiretaurant')}} @if (env('APP_DEMO'))<small class="addon">({{trans('multirestaurant::layout.addon')}})</small> @endif <i data-toggle="tooltip" data-placement="top" title="{{trans('multirestaurant::layout.publish_waring')}}" id="multirestaurant-tooltip" class="ti-info-alt"></i></label>
                        <select name="on_multi_restaurant" class="form-control">
                            <option
                                {{isset($restaurant) && $restaurant->on_multi_restaurant=='unpublish'?'selected':''}} value="unpublish">{{trans('layout.unpublish')}}</option>
                            <option
                                {{isset($restaurant) && $restaurant->on_multi_restaurant=='publish'?'selected':''}} value="publish">{{trans('layout.publish')}}</option>
                        </select>
                    </div>
                </div>
            @endif
            <div class="col-lg-12 mb-3">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.description')}}*</label>
                    <textarea rows="10" name="description" class="form-control"
                              placeholder="Ex: The Disaster Cafe will deliver, with 7.8 richter scale earthquakes simulated during meals"
                              required>{{old('description')?old('description'):(isset($restaurant)?$restaurant->description:'')}}</textarea>
                </div>
            </div>
        </div>
    </section>
    <h4>{{trans('layout.image_upload')}}</h4>
    <section>
        <div class="row">
            <div class="col-lg-12 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.profile')}}</label>
                    @if(isset($restaurant) && $restaurant->profile_image)
                        <img style="max-width: 50px" src="{{asset('uploads').'/'.$restaurant->profile_image}}"
                             alt="{{$restaurant->profile_image}}">
                    @endif
                    <input type="file" name="profile_file" class="form-control" accept="image/*">
                </div>
            </div>
            <div class="col-lg-12 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.cover')}}</label>
                    @if(isset($restaurant) && $restaurant->cover_image)
                        <img style="max-width: 50px" src="{{asset('uploads').'/'.$restaurant->cover_image}}"
                             alt="{{$restaurant->cover_image}}">
                    @endif
                    <input type="file" name="cover_file" class="form-control" accept="image/*">
                </div>
            </div>
        </div>
    </section>
    <h4>{{trans('layout.choose_template')}}</h4>
    <section>
        <h4 class="mb-4">{{trans('layout.choose_template')}}</h4>
        <div class="form-group mb-0">
            <label class="radio-inline mr-3 restaurant-template"><input
                    {{isset($restaurant) && $restaurant->template=='classic'?'checked':''}} type="radio" name="template"
                    value="classic">
                <span>{{trans('layout.classic')}}</span>
                <img class="max-h-300" src="{{asset('images/classic_template.jpg')}}" alt="Classic Template">

            </label>
            <label class="radio-inline mr-3 restaurant-template"><input
                    {{isset($restaurant) && $restaurant->template=='modern'?'checked':''}} type="radio" name="template"
                    value="modern">
                <span>{{trans('layout.modern')}}</span>
                <img class="max-h-300" src="{{asset('images/modern_template.png')}}" alt="Modern Template">

            </label>
            <label class="d-none radio-inline mr-3 restaurant-template"><input
                    {{isset($restaurant) && $restaurant->template=='flipbook'?'checked':''}} type="radio"
                    name="template" value="flipbook">
                <span>{{trans('layout.flipbook')}}</span>
                <img class="max-h-300" src="{{asset('images/classic_template.jpg')}}" alt="Flipbook Template">
            </label>
            <label class="d-none radio-inline mr-3 restaurant-template"><input
                    {{isset($restaurant) && $restaurant->template=='custom'?'checked':''}} type="radio" name="template"
                    value="custom">
                <span>{{trans('layout.custom')}}</span>
                <img class="max-h-300" src="{{asset('images/custom-preview.svg')}}" alt="Custom Template">
            </label>
        </div>
    </section>
</div>
