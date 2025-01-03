@extends('layouts.dashboard')

@section('title',trans('layout.settings_title'))
@section('css')
    <link rel="stylesheet" href="{{asset('vendor/select2/css/select2.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.css" integrity="sha512-ngQ4IGzHQ3s/Hh8kMyG4FC74wzitukRMIcTOoKT3EyzFZCILOPF0twiXOQn75eDINUfKBYmzYn2AA8DkAk8veQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .custom-nav.active {
            color: #2f4cdd !important;
            box-shadow: none !important;
        }

        .nav-flex {
            display: flex;
            flex-direction: column;
        }

        .flex-content {
            width: fit-content;
        }
    </style>
@endsection

@section('main-content')

    <div class="col-xl-12">
        <div class="card">
            <div class="card-body">
                <!-- Nav tabs -->
                <div class="custom-tab-1">
                    <ul class="nav nav-tabs">
                        @can('general_setting')
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab"
                                   href="#generalSettings">{{trans('layout.general')}}</a>
                            </li>
                        @endcan
                        @can('email_setting')
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab"
                                   href="#emailSettings">{{trans('layout.email_settings')}}</a>
                            </li>
                        @endcan
                        @can('email_template_setting')
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab"
                                   href="#emailTemplate">{{trans('layout.email_template')}}</a>
                            </li>
                        @endcan
                        @can('payment_gateway_setting')
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab"
                                   href="#paymentGateway">{{trans('layout.payment_gateway')}}</a>
                            </li>
                        @endcan
                        @can('role_permission')
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab"
                                   href="#rolePermission">{{trans('layout.role_permission')}}</a>
                            </li>
                        @endcan
                        @if(auth()->user()->type=='admin')
                            <li class="nav-item">
                                <a class="nav-link" target="_blank"
                                   href="{{url('translations')}}">{{trans('layout.translation')}}</a>
                            </li>
                        @endif
                        @can('sms_gateway_setting')
                            <li class="nav-item d-none">
                                <a class="nav-link" data-toggle="tab"
                                   href="#smsGateway">{{trans('layout.sms_gateway')}}</a>
                            </li>
                        @endcan
                    </ul>
                    <div class="tab-content">

                        <div class="tab-pane fade show active" id="generalSettings" role="tabpanel">
                            <div class="pt-4">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <div class="nav nav-pills mb-3 nav-flex">
                                                    @can('general_setting')
                                                        <a href="#v-pills-generalSettings" data-toggle="pill"
                                                           class="nav-link active show custom-nav flex-content">{{trans('layout.general_settings')}}</a>
                                                    @endcan
                                                    @can('change_password')
                                                        <a href="#v-pills-passwordChange" data-toggle="pill"
                                                           class="nav-link custom-nav flex-content">{{trans('layout.password_change')}}</a>
                                                    @endcan
                                                    @can('site_setting')
                                                        <a href="#v-pills-siteSettings" data-toggle="pill"
                                                           class="nav-link custom-nav flex-content">{{trans('layout.site_settings')}}</a>
                                                    @endcan
                                                        @if(auth()->user()->type=='admin')
                                                            <a href="#v-pills-orderSettings" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.order_settings')}}</a>
                                                        @endif

                                                    @can('local_setting')
                                                        <a href="#v-pills-localSettings" data-toggle="pill"
                                                           class="nav-link custom-nav flex-content">{{trans('layout.local_settings')}}</a>
                                                    @endcan

                                                </div>
                                            </div>
                                            <div class="col-sm-8">
                                                <div class="tab-content">
                                                    @can('general_setting')
                                                        <div id="v-pills-generalSettings"
                                                             class="tab-pane fade active show">
                                                            <form role="form" action="{{route('general')}}"
                                                                  method="post"
                                                                  enctype="multipart/form-data">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleInputEmail1">{{trans('layout.name')}}</label>
                                                                    <input type="text" name="name"
                                                                           value="{{isset($admin->name)?$admin->name:''}}"
                                                                           class="form-control"
                                                                           placeholder="{{trans('layout.name')}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleInputEmail1">{{trans('layout.phone_number')}}</label>
                                                                    <input type="tel" name="phone_number"
                                                                           value="{{isset($admin->phone_number)?$admin->phone_number:''}}"
                                                                           class="form-control"
                                                                           placeholder="{{trans('layout.phone_number')}}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleInputEmail1">{{trans('layout.email')}}</label>
                                                                    <input type="email" name="email"
                                                                           value="{{isset($admin->email)?$admin->email:''}}"
                                                                           class="form-control"
                                                                           id="exampleInputEmail1"
                                                                           placeholder="{{trans('layout.email')}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.picture')}}</label>
                                                                    <div class="input-group">
                                                                            <div class="custom-file">
                                                                                <input name="picture" value="{{isset($admin->picture)?$admin->picture:''}}" type="file" class="custom-file-input">
                                                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                                                            </div>
                                                                    </div>
                                                                </div>
                                                                <button type="submit"
                                                                        class="btn btn-primary float-right">{{trans('layout.submit')}}</button>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                    @can('change_password')
                                                        <div id="v-pills-passwordChange"
                                                             class="tab-pane fade">
                                                            <form role="form" action="{{route('password.update')}}"
                                                                  method="post">
                                                                @csrf
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.old_password')}}</label>
                                                                    <input type="password" name="old_password"
                                                                           class="form-control"
                                                                           placeholder="{{trans('layout.password')}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleInputPassword1">{{trans('layout.new_password')}}</label>
                                                                    <input type="password" name="new_password"
                                                                           class="form-control"
                                                                           placeholder="{{trans('layout.password')}}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label
                                                                        for="exampleInputPassword1">{{trans('layout.password')}}</label>
                                                                    <input type="password" name="confirm_password"
                                                                           class="form-control"
                                                                           placeholder="{{trans('layout.confirm_password')}}">

                                                                </div>
                                                                <button type="submit"
                                                                        class="btn btn-primary float-right">{{trans('layout.submit')}}</button>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                    @can('site_setting')
                                                        <div id="v-pills-siteSettings"
                                                             class="tab-pane fade">
                                                            <form action="{{route('side.bar.settings')}}" method="post"
                                                                  enctype="multipart/form-data">
                                                                @csrf

                                                                @isset($site_setting_id)
                                                                    <input type="hidden" name="settings_id"
                                                                           value="{{$site_setting_id}}">
                                                                @endisset
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.name')}} </label>

                                                                    <input
                                                                        value="{{isset($site_setting->name)?$site_setting->name:''}}"
                                                                        class="form-control" type="text"
                                                                        name="name">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.fav_icon')}}</label>
                                                                    @isset($site_setting->favicon)
                                                                        <img class="height-30"
                                                                             src="{{asset('uploads/'.$site_setting->favicon)}}"
                                                                             alt="">
                                                                    @endisset
                                                                    <div class="custom-file">
                                                                        <input name="fav_icon" type="file" class="custom-file-input">
                                                                        <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.logo')}}</label>
                                                                    @isset($site_setting->logo)
                                                                        <img class="height-30"
                                                                             src="{{asset('uploads/'.$site_setting->logo)}}"
                                                                             alt="">
                                                                    @endisset
                                                                    <div class="custom-file">
                                                                        <input name="logo" type="file" class="custom-file-input">
                                                                        <label class="custom-file-label">{{trans('layout.choose')}}</label>

                                                                    </div>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.live_notification')}}</label>
                                                                    <select class="form-control"
                                                                            name="live_notification"
                                                                            id="live_notification">
                                                                        <option
                                                                            {{isset($site_setting->live_notification) && $site_setting->live_notification=='enable'?'selected':''}} value="enable">{{trans('layout.enable')}}</option>
                                                                        <option
                                                                            {{isset($site_setting->live_notification) && $site_setting->live_notification=='disable'?'selected':''}} value="disable">{{trans('layout.disable')}}</option>
                                                                    </select>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.cookie_consent')}}</label>
                                                                    <select class="form-control"
                                                                            name="cookie_consent"
                                                                            id="cookie_consent">
                                                                        <option
                                                                            {{isset($site_setting->cookie_consent) && $site_setting->cookie_consent=='enable'?'selected':''}} value="enable">{{trans('layout.enable')}}</option>
                                                                        <option
                                                                            {{isset($site_setting->cookie_consent) && $site_setting->cookie_consent=='disable'?'selected':''}} value="disable">{{trans('layout.disable')}}</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>{{trans('layout.crips_token')}}</label>
                                                                    <input type="text" name="crips_token"
                                                                           value="{{isset($site_setting->crips_token)?$site_setting->crips_token:''}}"
                                                                           class="form-control">
                                                                    <small class="float-right text-blue"
                                                                           style="text-decoration-line: underline;"><a
                                                                            target="_blank"
                                                                            href="https://crisp.chat/en/">{{trans('layout.crips_account')}}</a></small>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.google_api_key')}}</label>
                                                                    <input type="text" name="api_key"
                                                                           value="{{isset($site_setting->api_key)?$site_setting->api_key:''}}"
                                                                           class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('Recaptcha Site Key')}}</label>
                                                                    <input type="text" name="recaptcha_site_key"
                                                                           value="{{isset($site_setting->recaptcha_site_key)?$site_setting->recaptcha_site_key:''}}"
                                                                           class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('Recaptcha Secret key')}}</label>
                                                                    <input type="text" name="recaptcha_secret_key"
                                                                           value="{{isset($site_setting->recaptcha_secret_key)?$site_setting->recaptcha_secret_key:''}}"
                                                                           class="form-control">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.privacy_policy')}}</label>
                                                                    <textarea class="form-control summernote" cols="30" type="text" name="privacy_policy"
                                                                              rows="5">{{isset($site_setting->privacy_policy)?$site_setting->privacy_policy:''}}</textarea>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>{{trans('layout.terms_conditions')}}</label>
                                                                    <textarea class="form-control summernote" cols="30" type="text" name="terms_conditions"
                                                                              rows="5">{{isset($site_setting->terms_conditions)?$site_setting->terms_conditions:''}}</textarea>
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>{{trans('layout.meta_title')}}</label>
                                                                    <input type="text" name="meta_title"
                                                                           value="{{isset($site_setting->meta_title)?$site_setting->meta_title:''}}"
                                                                           class="form-control">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>{{trans('layout.meta_description')}}</label>
                                                                    <textarea class="form-control" cols="30" type="text" name="meta_description"
                                                                              rows="5">{{isset($site_setting->meta_description)?$site_setting->meta_description:''}}</textarea>
                                                                </div>

                                                                <button type="submit"
                                                                        class="btn btn-primary">{{trans('layout.submit')}}</button>

                                                            </form>
                                                        </div>
                                                    @endcan
                                                    <div id="v-pills-orderSettings"
                                                         class="tab-pane fade">
                                                        <form action="{{route('order.status.settings')}}" method="post"
                                                              enctype="multipart/form-data">
                                                            @csrf

                                                            @isset($order_setting_id)
                                                                <input type="hidden" name="settings_id"
                                                                       value="{{$order_setting_id}}">
                                                            @endisset
                                                            <div
                                                                class="form-group {{auth()->user()->type=='admin'?'d-block':'d-none'}}">
                                                                <label>{{trans('layout.place_order')}}</label>
                                                                <select class="form-control"
                                                                        name="admin_order_status">
                                                                    <option
                                                                        {{isset($order_setting->admin_order_status) && $order_setting->admin_order_status=='enable'?'selected':''}} value="enable">{{trans('layout.enable')}}</option>
                                                                    <option
                                                                        {{isset($order_setting->admin_order_status) && $order_setting->admin_order_status=='disable'?'selected':''}} value="disable">{{trans('layout.disable')}}</option>
                                                                </select>
                                                            </div>
                                                                <button type="submit"
                                                                        class="btn btn-primary">{{trans('layout.submit')}}</button>



                                                        </form>
                                                    </div>

                                                    @can('local_setting')
                                                        <div id="v-pills-localSettings"
                                                             class="tab-pane fade">
                                                            <form action="{{route('settings.local')}}" method="post">
                                                                @csrf

                                                                @isset($local_setting_id)
                                                                    <input type="hidden" name="local_setting_id"
                                                                           value="{{$local_setting_id}}">
                                                                @endisset
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.language')}} </label>
                                                                            <select name="language"
                                                                                    class="form-control">
                                                                                @foreach($all_locales as $locale)
                                                                                    <option
                                                                                        {{isset($local_setting->language) && $local_setting->language==$locale?'selected':''}} value="{{$locale}}">{{$locale}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.direction')}} </label>
                                                                            <select name="direction"
                                                                                    class="form-control">
                                                                                <option
                                                                                    {{isset($local_setting->direction) && $local_setting->direction=='ltr'?'selected':''}} value="ltr">
                                                                                    LTR
                                                                                </option>
                                                                                <option
                                                                                    {{isset($local_setting->direction) && $local_setting->direction=='rtl'?'selected':''}} value="rtl">
                                                                                    RTL
                                                                                </option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.date_time_format')}} </label>

                                                                            <select name="date_time_format"
                                                                                    class="form-control">
                                                                                <option
                                                                                    {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='d m Y'?'selected':''}} value="d m Y">{{trans('30 12 2021')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='m d Y'?'selected':''}} value="m d Y">{{trans('12 30 2021')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='Y d m'?'selected':''}} value="Y d m">{{trans('2021 30 12')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='Y m d'?'selected':''}} value="Y m d">{{trans('2021 12 30')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='d_M,Y'?'selected':''}}  value="d_M,Y">{{trans('17 July,2021')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->date_time_format) && $local_setting->date_time_format=='M_d,Y'?'selected':''}}  value="M_d,Y">{{trans('July 17,2021')}}</option>
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.date_time_separator')}} </label>

                                                                            <select name="date_time_separator"
                                                                                    class="form-control">
                                                                                <option
                                                                                    {{isset($local_setting->date_time_separator) && $local_setting->date_time_separator=='-'?'selected':''}} value="-">{{trans('-')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->date_time_separator) && $local_setting->date_time_separator=='/'?'selected':''}} value="/">{{trans('/')}}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.timezone')}} </label>

                                                                            <select id="timezone" name="timezone"
                                                                                    class="form-control">

                                                                                @foreach(getAllTimeZones() as $time)
                                                                                    <option
                                                                                        {{isset($local_setting->timezone) && $local_setting->timezone==$time['zone']?'selected':''}} value="{{$time['zone']}}">
                                                                                        ({{$time['GMT_difference']. ' ) '.$time['zone']}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.decimal_point')}} </label>
                                                                            <select name="decimal_format"
                                                                                    class="form-control">
                                                                                <option
                                                                                    {{isset($local_setting->decimal_format) && $local_setting->decimal_format==','?'selected':''}} value=",">{{trans('Comma (,)')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->decimal_format) && $local_setting->decimal_format=='.'?'selected':''}} value=".">{{trans('Dot (.)')}}</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.currency_symbol')}} </label>

                                                                            <input
                                                                                value="{{isset($local_setting->currency_symbol) ?$local_setting->currency_symbol:''}}"
                                                                                class="form-control" type="text"
                                                                                name="currency_symbol">

                                                                        </div>
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.currency_code')}} </label>

                                                                            <input
                                                                                value="{{isset($local_setting->currency_code) ?$local_setting->currency_code:''}}"
                                                                                class="form-control" type="text"
                                                                                name="currency_code"
                                                                                placeholder="Ex: usd or eur">
                                                                            <a target="_blank" class="pull-right"
                                                                               href="https://www.iban.com/currency-codes">{{trans('layout.find_yours')}}</a>

                                                                        </div>

                                                                        <div class="col-12">
                                                                            <label>{{trans('layout.currency_symbol_position')}} </label>
                                                                            <select name="currency_symbol_position"
                                                                                    class="form-control">
                                                                                <option
                                                                                    {{isset($local_setting->currency_symbol_position) && $local_setting->currency_symbol_position=='before'?'selected':''}} value="before">{{trans('layout.before')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->currency_symbol_position) && $local_setting->currency_symbol_position=='after'?'selected':''}} value="after">{{trans('layout.after')}}</option>
                                                                            </select>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                                <div class="form-group">
                                                                    <div class="row">
                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.decimals')}} </label>

                                                                            <input
                                                                                value="{{isset($local_setting->decimals) ?$local_setting->decimals:'0'}}"
                                                                                class="form-control" type="number"
                                                                                name="decimals">

                                                                        </div>

                                                                        <div class="col-6">
                                                                            <label>{{trans('layout.thousand_separator')}} </label>
                                                                            <select name="thousand_separator"
                                                                                    class="form-control">
                                                                                <option
                                                                                    {{isset($local_setting->thousand_separator) && $local_setting->thousand_separator==','?'selected':''}} value=",">{{trans('Comma (,)')}}</option>
                                                                                <option
                                                                                    {{isset($local_setting->thousand_separator) && $local_setting->thousand_separator=='.'?'selected':''}} value=".">{{trans('Dot (.)')}}</option>
                                                                            </select>
                                                                        </div>

                                                                    </div>

                                                                </div>
                                                                <button type="submit"
                                                                        class="btn btn-sm btn-primary">{{trans('layout.submit')}}</button>

                                                            </form>
                                                        </div>
                                                    @endcan

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @can('email_setting')
                            <div class="tab-pane fade" id="emailSettings">
                                <div class="pt-4">
                                    <div class="card">
                                        <form role="form" action="{{route('email.settings')}}" method="post">
                                            @csrf
                                            <div class="card-body">

                                                @isset($email_setting_id)
                                                    <input type="hidden" name="setting_id"
                                                           value="{{$email_setting_id}}">
                                                @endisset
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputPassword1">{{trans('layout.name')}}</label>
                                                            <input
                                                                value="{{isset($email_setting->name)?$email_setting->name:''}}"
                                                                type="text" name="name" class="form-control"
                                                                placeholder="{{trans('layout.name')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputPassword1">{{trans('layout.email_from')}}</label>
                                                            <input
                                                                value="{{isset($email_setting->email_from)?$email_setting->email_from:''}}"
                                                                type="text" name="email_from" class="form-control"
                                                                placeholder="{{trans('layout.email')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputPassword1">{{trans('layout.username')}}</label>
                                                            <input
                                                                value="{{isset($email_setting->username)?$email_setting->username:''}}"
                                                                type="text" name="username" class="form-control"
                                                                placeholder="{{trans('layout.username')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputPassword1">{{trans('layout.password')}}</label>
                                                            <input
                                                                value="{{isset($email_setting->password)?$email_setting->password:''}}"
                                                                type="password" name="password" class="form-control"
                                                                placeholder=" {{trans('layout.password')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputPassword1">{{trans('layout.host')}}</label>
                                                            <input
                                                                value="{{isset($email_setting->host)?$email_setting->host:''}}"
                                                                type="text" name="host" class="form-control"
                                                                placeholder="{{trans('layout.host')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputPassword1">{{trans('layout.port')}}</label>
                                                            <input
                                                                value="{{isset($email_setting->port)?$email_setting->port:''}}"
                                                                type="text" name="port" class="form-control"
                                                                placeholder="{{trans('layout.port')}}">
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="form-group">
                                                            <label
                                                                for="exampleInputPassword1">{{trans('layout.encrypt_type')}}</label>
                                                            <select name="encryption_type" class="form-control">
                                                                <option
                                                                    {{isset($email_setting->encryption_type) && $email_setting->encryption_type=='tls'?'selected':''}} value="tls">
                                                                    tls
                                                                </option>
                                                                <option
                                                                    {{isset($email_setting->encryption_type) && $email_setting->encryption_type=='ssl'?'selected':''}} value="ssl">
                                                                    ssl
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-footer text-right">
                                                <button type="submit"
                                                        class="btn btn-sm btn-primary">{{trans('layout.submit')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('email_template_setting')
                            <div class="tab-pane fade" id="emailTemplate">
                                <div class="pt-4">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xl-4">
                                                        <div class="nav nav-pills mb-3 nav-flex">
                                                            <a href="#v-pills-registration" data-toggle="pill"
                                                               class="nav-link active show custom-nav flex-content">{{trans('layout.registration')}}</a>
                                                            <a href="#v-pills-forgetPass" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.forget_password')}}</a>
                                                            <a href="#v-pills-orderPlaced" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.order_placed')}}</a>
                                                            <a href="#v-pills-orderStatus" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.order_status')}}</a>
                                                            <a href="#v-pills-planRequest" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.plan_request')}}</a>
                                                            <a href="#v-pills-planAccepted" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.plan_accept')}}</a>
                                                            <a href="#v-pills-planExpire" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.plan_expire')}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-8">
                                                        <div class="tab-content">
                                                            <div id="v-pills-registration"
                                                                 class="tab-pane fade active show">
                                                                <form action="{{route('email.template.store')}}"
                                                                      method="post">
                                                                    @csrf
                                                                    @isset($emailTemplateReg)
                                                                        <input type="hidden"
                                                                               value="{{$emailTemplateReg->id}}"
                                                                               name="emailTemplateID">
                                                                    @endisset
                                                                    <input type="hidden" name="type"
                                                                           value="{{isset($emailTemplateReg->type)?$emailTemplateReg->type:'registration'}}">
                                                                    <textarea class="form-control" name="subject"
                                                                              rows="2"
                                                                              placeholder="{{trans('layout.email_subject')}}">{{isset($emailTemplateReg->subject)?$emailTemplateReg->subject:''}}</textarea>
                                                                    <textarea class="form-control mt-2" name="body"

                                                                              rows="5"
                                                                              placeholder="{{trans('layout.email_body')}}">{{isset($emailTemplateReg->body)?$emailTemplateReg->body:''}}</textarea>

                                                                    <div>{customer_name} = {{trans('layout.customer_name')}}</div>
                                                                    <div>{click_here} = {{trans('layout.for_verification_link')}}</div>
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right mt-4">
                                                                        {{trans('layout.submit')}}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div id="v-pills-forgetPass" class="tab-pane fade">
                                                                <form action="{{route('email.template.store')}}"
                                                                      method="post">
                                                                    @csrf
                                                                    @isset($emailTemplatePass)
                                                                        <input type="hidden"
                                                                               value="{{$emailTemplatePass->id}}"
                                                                               name="emailTemplateID">
                                                                    @endisset
                                                                    <input type="hidden" name="type"
                                                                           value="{{isset($emailTemplatePass->type)?$emailTemplatePass->type:'forget_password'}}">
                                                                    <textarea class="form-control" name="subject"
                                                                              rows="2"
                                                                              placeholder="{{trans('layout.email_subject')}}">{{isset($emailTemplatePass->subject)?$emailTemplatePass->subject:''}}</textarea>
                                                                    <textarea class="form-control mt-2" name="body"

                                                                              rows="5"
                                                                              placeholder="{{trans('layout.email_body')}}">{{isset($emailTemplatePass->body)?$emailTemplatePass->body:''}}</textarea>
                                                                    <div>{customer_name} = {{trans('layout.customer_name')}}</div>
                                                                    <div>{reset_url} = {{trans('layout.reset_url_link')}}</div>
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right mt-4">
                                                                        {{trans('layout.submit')}}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div id="v-pills-orderPlaced" class="tab-pane fade">
                                                                <form action="{{route('email.template.store')}}"
                                                                      method="post">
                                                                    @csrf
                                                                    @isset($emailTemplateOrderPlaced)
                                                                        <input type="hidden"
                                                                               value="{{$emailTemplateOrderPlaced->id}}"
                                                                               name="emailTemplateID">
                                                                    @endisset
                                                                    <input type="hidden" name="type"
                                                                           value="{{isset($emailTemplateOrderPlaced->type)?$emailTemplateOrderPlaced->type:'order_placed'}}">
                                                                    <textarea class="form-control" name="subject"
                                                                              rows="2"
                                                                              placeholder="{{trans('layout.email_subject')}}">{{isset($emailTemplateOrderPlaced->subject)?$emailTemplateOrderPlaced->subject:''}}</textarea>
                                                                    <textarea class="form-control mt-2" name="body"

                                                                              rows="5"
                                                                              placeholder="{{trans('layout.email_body')}}">{{isset($emailTemplateOrderPlaced->body)?$emailTemplateOrderPlaced->body:''}}</textarea>

                                                                    <div>{customer_name} = {{trans('layout.customer_name')}}</div>
                                                                    <div>{order_no} = {{trans('layout.order_no')}}</div>
                                                                    <div>{total_amount} = {{trans('layout.total_amount')}}</div>
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right mt-4">
                                                                        {{trans('layout.submit')}}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div id="v-pills-orderStatus" class="tab-pane fade">
                                                                <form action="{{route('email.template.store')}}"
                                                                      method="post">
                                                                    @csrf
                                                                    @isset($emailTemplateOrderStatus)
                                                                        <input type="hidden"
                                                                               value="{{$emailTemplateOrderStatus->id}}"
                                                                               name="emailTemplateID">
                                                                    @endisset
                                                                    <input type="hidden" name="type"
                                                                           value="{{isset($emailTemplateOrderStatus->type)?$emailTemplateOrderStatus->type:'order_status'}}">
                                                                    <textarea class="form-control" name="subject"
                                                                              rows="2"
                                                                              placeholder="{{trans('layout.email_subject')}}">{{isset($emailTemplateOrderStatus->subject)?$emailTemplateOrderStatus->subject:''}}</textarea>
                                                                    <textarea class="form-control mt-2" name="body"

                                                                              rows="5"
                                                                              placeholder="{{trans('layout.email_body')}}">{{isset($emailTemplateOrderStatus->body)?$emailTemplateOrderStatus->body:''}}</textarea>

                                                                    <div>{customer_name} = {{trans('layout.customer_name')}}</div>
                                                                    <div>{order_no} = {{trans('layout.order_no')}}</div>
                                                                    <div>{status} = {{trans('layout.status')}}</div>
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right mt-4">
                                                                        {{trans('layout.submit')}}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div id="v-pills-planRequest" class="tab-pane fade">
                                                                <form action="{{route('email.template.store')}}"
                                                                      method="post">
                                                                    @csrf
                                                                    @isset($emailTemplatePlanRequest)
                                                                        <input type="hidden"
                                                                               value="{{$emailTemplatePlanRequest->id}}"
                                                                               name="emailTemplateID">
                                                                    @endisset
                                                                    <input type="hidden" name="type"
                                                                           value="{{isset($emailTemplatePlanRequest->type)?$emailTemplatePlanRequest->type:'plan_request'}}">
                                                                    <textarea class="form-control" name="subject"
                                                                              rows="2"
                                                                              placeholder="{{trans('layout.email_subject')}}">{{isset($emailTemplatePlanRequest->subject)?$emailTemplatePlanRequest->subject:''}}</textarea>
                                                                    <textarea class="form-control mt-2" name="body"

                                                                              rows="5"
                                                                              placeholder="{{trans('layout.email_body')}}">{{isset($emailTemplatePlanRequest->body)?$emailTemplatePlanRequest->body:''}}</textarea>

                                                                    <div>{customer_name} = {{trans('layout.customer_name')}}</div>
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right mt-4">
                                                                        {{trans('layout.submit')}}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div id="v-pills-planAccepted" class="tab-pane fade">
                                                                <form action="{{route('email.template.store')}}"
                                                                      method="post">
                                                                    @csrf
                                                                    @isset($emailTemplatePlanAccepted)
                                                                        <input type="hidden"
                                                                               value="{{$emailTemplatePlanAccepted->id}}"
                                                                               name="emailTemplateID">
                                                                    @endisset
                                                                    <input type="hidden" name="type"
                                                                           value="{{isset($emailTemplatePlanAccepted->type)?$emailTemplatePlanAccepted->type:'plan_accepted'}}">
                                                                    <textarea class="form-control" name="subject"
                                                                              rows="2"
                                                                              placeholder="{{trans('layout.email_subject')}}">{{isset($emailTemplatePlanAccepted->subject)?$emailTemplatePlanAccepted->subject:''}}</textarea>
                                                                    <textarea class="form-control mt-2" name="body"

                                                                              rows="5"
                                                                              placeholder="{{trans('layout.email_body')}}">{{isset($emailTemplatePlanAccepted->body)?$emailTemplatePlanAccepted->body:''}}</textarea>

                                                                    <div>{customer_name} = {{trans('layout.customer_name')}}</div>
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right mt-4">
                                                                        {{trans('layout.submit')}}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div id="v-pills-planExpire" class="tab-pane fade">
                                                                <form action="{{route('email.template.store')}}"
                                                                      method="post">
                                                                    @csrf
                                                                    @isset($emailTemplatePlanExpire)
                                                                        <input type="hidden"
                                                                               value="{{$emailTemplatePlanExpire->id}}"
                                                                               name="emailTemplateID">
                                                                    @endisset
                                                                    <input type="hidden" name="type"
                                                                           value="{{isset($emailTemplatePlanExpire->type)?$emailTemplatePlanExpire->type:'plan_expired'}}">
                                                                    <textarea class="form-control" name="subject"
                                                                              rows="2"
                                                                              placeholder="{{trans('layout.email_subject')}}">{{isset($emailTemplatePlanExpire->subject)?$emailTemplatePlanExpire->subject:''}}</textarea>
                                                                    <textarea class="form-control mt-2" name="body"

                                                                              rows="5"
                                                                              placeholder="{{trans('layout.email_body')}}">{{isset($emailTemplatePlanExpire->body)?$emailTemplatePlanExpire->body:''}}</textarea>

                                                                    <div>{customer_name} = {{trans('layout.customer_name')}}</div>
                                                                    <button type="submit"
                                                                            class="btn btn-primary float-right mt-4">
                                                                        {{trans('layout.submit')}}
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('payment_gateway_setting')
                            <div class="tab-pane fade" id="paymentGateway">
                                <div class="pt-4">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xl-3">
                                                        <div class="nav nav-pills mb-3 nav-flex">
                                                            <a href="#v-pills-paypalPay" data-toggle="pill"
                                                               class="nav-link active show custom-nav flex-content">{{trans('layout.paypal')}}</a>
                                                            <a href="#v-pills-stripePay" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.stripe')}}</a>
                                                            <a href="#v-pills-paytm" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.paytm')}}</a>
                                                            <a href="#v-pills-mollie" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.mollie')}}</a>
                                                            <a href="#v-pills-paystack" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.paystack')}}</a>
                                                            <a href="#v-pills-offline" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.offline')}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-9">
                                                        <form action="{{route('payment.gateway')}}" method="post">
                                                            <div class="tab-content">

                                                                @php $rest_gateways=get_restaurant_gateway_settings(auth()->id()) @endphp

                                                                @csrf
                                                                @if(auth()->user()->type=='admin')
                                                                    @isset($payment_gateway_id)
                                                                        <input type="hidden"
                                                                               name="payment_gateway_id"
                                                                               value="{{$payment_gateway_id}}">
                                                                    @endisset
                                                                    @include('settings.admin_payment_gateway')

                                                                @else
                                                                    @if($rest_gateways)
                                                                        <input type="hidden"
                                                                               name="rest_payment_gateway_id"
                                                                               value="{{$rest_gateways->id}}">
                                                                        @php $rest_gateways_credentials=json_decode(get_restaurant_gateway_settings(auth()->id())->value); @endphp
                                                                    @endif

                                                                    @include('settings.restaurant_payment_gateway')

                                                                @endif

                                                            </div>
                                                            <button type="submit"
                                                                    class="btn btn-primary float-right mt-4">
                                                                {{trans('layout.submit')}}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('sms_gateway_setting')
                            <div class="tab-pane fade" id="smsGateway">
                                <div class="pt-4">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-xl-3">
                                                        <div class="nav nav-pills mb-3 nav-flex">
                                                            <a href="#v-pills-twilio" data-toggle="pill"
                                                               class="nav-link active show custom-nav flex-content">{{trans('layout.twilio')}}</a>
                                                            <a href="#v-pills-voyager" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.voyager')}}</a>
                                                            <a href="#v-pills-signalwire" data-toggle="pill"
                                                               class="nav-link custom-nav flex-content">{{trans('layout.signalwire')}}</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-xl-9">
                                                        <form action="{{route('sms.gateway')}}" method="post">
                                                            <div class="tab-content">
                                                                <div id="v-pills-twilio"
                                                                     class="tab-pane fade active show">
                                                                    @csrf
                                                                    @isset($sms_gateway_id)
                                                                        <input type="hidden" name="sms_gateway_id"
                                                                               value="{{$sms_gateway_id}}">
                                                                    @endisset
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="exampleInputPassword1">{{trans('layout.sid')}}</label>
                                                                        <input
                                                                            value="{{isset($sms_gateway->twilio_sid)?$sms_gateway->twilio_sid:''}}"
                                                                            type="text" name="twilio_sid"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="exampleInputPassword1">{{trans('layout.token')}}</label>
                                                                        <input
                                                                            value="{{isset($sms_gateway->twilio_token)?$sms_gateway->twilio_token:''}}"
                                                                            type="text" name="twilio_token"
                                                                            class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div id="v-pills-voyager" class="tab-pane fade">
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="exampleInputPassword1">{{trans('layout.api_key')}}</label>
                                                                        <input
                                                                            value="{{isset($sms_gateway->voyager_api)?$sms_gateway->voyager_api:''}}"
                                                                            type="text" name="voyager_api"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="exampleInputPassword1">{{trans('layout.api_secret')}}</label>
                                                                        <input
                                                                            value="{{isset($sms_gateway->voyager_api_secret)?$sms_gateway->voyager_api_secret:''}}"
                                                                            type="text" name="voyager_api_secret"
                                                                            class="form-control">
                                                                    </div>
                                                                </div>
                                                                <div id="v-pills-signalwire" class="tab-pane fade">
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="exampleInputPassword1">{{trans('layout.project_id')}}</label>
                                                                        <input
                                                                            value="{{isset($sms_gateway->signalwire_project_id)?$sms_gateway->signalwire_project_id:''}}"
                                                                            type="text" name="signalwire_project_id"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="exampleInputPassword1">{{trans('layout.space_url')}}</label>
                                                                        <input
                                                                            value="{{isset($sms_gateway->signalware_url)?$sms_gateway->signalware_url:''}}"
                                                                            type="text" name="signalware_url"
                                                                            class="form-control">
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <label
                                                                            for="exampleInputPassword1">{{trans('layout.token')}}</label>
                                                                        <input
                                                                            value="{{isset($sms_gateway->signalware_token)?$sms_gateway->signalware_token:''}}"
                                                                            type="text" name="signalware_token"
                                                                            class="form-control">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <button type="submit"
                                                                    class="btn btn-primary float-right mt-4">
                                                                {{trans('layout.submit')}}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                        @can('role_permission')
                            <div class="tab-pane fade" id="rolePermission">
                                <div class="pt-4">
                                    <div class="col-xl-12">
                                        <div class="card">
                                            <form class="{{auth()->user()->type=='admin'?'':'d-none'}}"
                                                  action="{{route('settings.permission.update')}}" method="post">
                                                @csrf
                                                <div class="card-body">
                                                    <div class="row mb-2 d-none">
                                                        <div class="col-lg-2">
                                                            <label>Admin</label>
                                                        </div>
                                                        <div class="col-lg-10">
                                                            <div class="row">
                                                                @foreach($permissions as $permission)
                                                                    <div class="col-4">
                                                                        <div
                                                                            class="custom-control custom-checkbox mb-3 checkbox-info">
                                                                            <input
                                                                                {{in_array($permission->name,$admin_permissions)?'checked':''}} name="admin_permission[]"
                                                                                value="{{$permission->name}}"
                                                                                type="checkbox"
                                                                                class="custom-control-input"
                                                                                id="admin-permission-{{$permission->id}}">
                                                                            <label class="custom-control-label"
                                                                                   for="admin-permission-{{$permission->id}}">{{ucfirst(str_replace('_',' ',$permission->name))}}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row mb-2 {{auth()->user()->type=='admin'?'':'d-none'}}">
                                                        <div class="col-lg-2">
                                                            <label>{{trans('layout.restaurant_owner')}}</label>
                                                        </div>
                                                        <div class="col-lg-10">
                                                            <div class="row">
                                                                @foreach($permissions->whereIn('name',get_restaurant_permissions()) as $permission)
                                                                    <div class="col-4">
                                                                        <div
                                                                            class="custom-control custom-checkbox mb-3 checkbox-info">
                                                                            <input
                                                                                {{in_array($permission->name,$rest_owner_permissions)?'checked':''}} name="rest_owner_permission[]"
                                                                                value="{{$permission->name}}"
                                                                                type="checkbox"
                                                                                class="custom-control-input"
                                                                                id="rest_owner-permission-{{$permission->id}}">
                                                                            <label class="custom-control-label"
                                                                                   for="rest_owner-permission-{{$permission->id}}">{{ucfirst(str_replace('_',' ',$permission->name))}}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr class="{{auth()->user()->type=='admin'?'':'d-none'}}">
                                                    <div class="row mb-2 {{auth()->user()->type=='admin'?'':'d-none'}}">
                                                        <div class="col-lg-2">
                                                            <label>{{trans('layout.customer')}}</label>
                                                        </div>
                                                        <div class="col-lg-10">
                                                            <div class="row">
                                                                @foreach($permissions->whereIn('name',get_customer_permissions()) as $permission)
                                                                    <div class="col-4">
                                                                        <div
                                                                            class="custom-control custom-checkbox mb-3 checkbox-info">
                                                                            <input
                                                                                {{in_array($permission->name,$customer_permissions)?'checked':''}} name="customer_permission[]"
                                                                                value="{{$permission->name}}"
                                                                                type="checkbox"
                                                                                class="custom-control-input"
                                                                                id="customer-permission-{{$permission->id}}">
                                                                            <label class="custom-control-label"
                                                                                   for="customer-permission-{{$permission->id}}">{{ucfirst(str_replace('_',' ',$permission->name))}}</label>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer text-right">
                                                    <button
                                                        class="btn btn-sm btn-primary">{{trans('layout.submit')}}</button>
                                                </div>
                                            </form>
                                            @if(auth()->user()->type=='restaurant_owner')
                                                <form action="{{route('user.permission.update')}}" method="post">
                                                    @csrf
                                                    @method('put')
                                                    @foreach($roles as $role)
                                                        @if($role->name !='admin' && $role->name !='customer' &&  $role->name != 'restaurant_owner')
                                                            @if(auth()->user()->id==$role->restaurant_id)
                                                                <div class="row mb-2" id="show_role_{{$role->name}}">
                                                                    <div class="col-lg-2">
                                                                        <label>{{$role->name}} <span
                                                                                class="float-right ml-3"><a
                                                                                    href="#"><i
                                                                                        data-role="{{$role->name}}"
                                                                                        class="fa fa-trash text-danger"></i></a></span></label>
                                                                    </div>
                                                                    <div class="col-lg-10">
                                                                        <div class="row">
                                                                            <input type="hidden" value="{{$role->name}}"
                                                                                   name="role_name[]">
                                                                            @php $rolePermissions= \Spatie\Permission\Models\Role::findByName($role->name)->getAllPermissions()->pluck('name')->toArray();@endphp
                                                                            @foreach(get_user_permission() as $key=>$permission)
                                                                                <div class="col-sm-4 display">
                                                                                    <div class="form-group clearfix">
                                                                                        <div class="icheck-success">
                                                                                            <input
                                                                                                name="permission[{{$role->id}}][]"
                                                                                                value="{{$permission}}"
                                                                                                {{in_array($permission,$rolePermissions)?'checked':''}} type="checkbox"
                                                                                                id="checkboxSuccess_{{$permission}}_{{$role->name}}">
                                                                                            <label
                                                                                                for="checkboxSuccess_{{$permission}}_{{$role->name}}"
                                                                                                class="text-muted d-inline ml-2">
                                                                                                {{strtoupper(str_replace('_',' ',$permission))}}
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-right mt-2">
                                                                        <button type="submit"
                                                                                class="btn btn-primary">{{trans('layout.submit')}}</button>
                                                                </div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </form>
                                                <div class="text-right p-4">
                                                    <button type="button"
                                                            class="btn btn-md btn-primary float-left"
                                                            data-toggle="modal"
                                                            data-target="#modal-default">{{trans('layout.add_new_role')}}
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{route('user.permission.store')}}" method="post">
                    @csrf
                    @method('put')
                    <div class="modal-header">
                        <h4 class="modal-title">{{trans('layout.role_permission')}}</h4>
                        <button type="button" class="close" data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">


                                <div class="form-group">
                                    <label for="">{{trans('layout.role_name')}}</label>
                                    <input type="text" name="role_name"
                                           class="form-control">
                                </div>
                            </div>
                            @foreach($permissions->whereIn('name',get_user_permission()) as $permission)
                                <div class="col-sm-4" style="text-wrap: inherit">
                                    <div class="form-group clearfix display">
                                        <div class="icheck-success">
                                            <input type="checkbox"
                                                   id="checkboxSuccess_{{$permission->name}}"
                                                   value="{{$permission->name}}"
                                                   name="permission[]">
                                            <label class="text-muted d-inline"
                                                   for="checkboxSuccess_{{$permission->name}}">
                                                {{strtoupper(str_replace('_',' ',$permission->name))}}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">
                                {{trans('layout.close')}}
                        </button>
                        <button type="submit"
                                class="btn btn-primary">{{trans('layout.submit')}}</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

@endsection

@section('js')

    <script src="{{asset('vendor/select2/js/select2.full.min.js')}}"></script>
    <script !src="">
        $(document).on('click', '.fa-trash', function (e) {
            e.preventDefault();

            const role = $(this).attr('data-role');
            $.ajax({
                method: "get",
                url: "{{route('user.role.delete')}}",
                data: {
                    role: role,
                },

                success: function (res) {
                    let html = '';
                    if (res.status == 'success') {
                        $('#show_role_' + role).remove();
                    } else {
                        toastr.error(res.message, 'failed', {timeOut: 5000});
                    }
                }
            })
        });

        $(document).ready(function () {
            $("#timezone").selectpicker({
                liveSearch: true
            });
        });
    </script>

    Sarwar Ahmed  3:50 PM
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-bs4.min.js" integrity="sha512-ZESy0bnJYbtgTNGlAD+C2hIZCt4jKGF41T5jZnIXy4oP8CQqcrBGWyxNP16z70z/5Xy6TS/nUZ026WmvOcjNIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('.summernote').summernote({
            placeholder: '{{trans('layout.type_here')}}',
            tabsize: 2,
            height: 100,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'hr']],
                //['view', ['fullscreen', 'codeview']],
                ['help', ['help']]
            ],
        });
    </script>
@endsection
