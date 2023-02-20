@extends('layouts.auth')

@section('title',trans('layout.registration'))

@section('main-content')
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
                                    <h4 class="text-center mb-4">{{trans('auth.sign_up_title')}}</h4>
                                    <form action="{{route('user.store')}}" method="post" id="signUpForm">
                                        @csrf
                                        @if(request()->has('type'))
                                            <input type="hidden" name="type" value="{{request()->get('type')}}">
                                        @endif
                                        @if(request()->has('plan'))
                                            <input type="hidden" name="plan" value="{{request()->get('plan')}}">
                                        @endif
                                        @if(request()->has('slug'))
                                            <input type="hidden" name="slug" value="{{request()->get('slug')}}">
                                        @endif

                                        @if(request()->has('restaurant'))
                                            <input type="hidden" name="restaurant"
                                                   value="{{request()->get('restaurant')}}">
                                        @endif
                                        <div class="form-group">
                                            <label class="mb-1"><strong>{{trans('auth.name')}}</strong></label>
                                            <input type="text" class="form-control"
                                                   placeholder="{{trans('auth.name_ex')}}" value="{{old('name')}}"
                                                   name="name">
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>{{trans('auth.email')}}</strong></label>
                                            <input type="email" class="form-control" value="{{old('email')}}"
                                                   placeholder="{{trans('auth.email_ex')}}" name="email">
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1"><strong>{{trans('auth.password')}}</strong></label>
                                            <input type="password" class="form-control" value=""
                                                   placeholder="{{trans('auth.password')}}" name="password">
                                        </div>
                                        <div>
                                            <span>{{trans('auth.agreement')}}</span> <a class="text-blue"
                                                                                        href="#">{{trans('auth.terms_condition')}}</a> {{trans('auth.and')}}
                                            <a class="text-blue" href="#">{{trans('auth.privacy_policy')}}</a>
                                        </div>
                                        <div id="g_append">

                                        </div>

                                        <div class="text-center mt-4">
                                            <button type="{{json_decode(get_settings('site_setting')) && isset(json_decode(get_settings('site_setting'))->recaptcha_site_key)?'button':'submit'}}" id="submitForm"
                                                    class="btn btn-primary btn-block">{{trans('auth.sign_up_btn')}}</button>
                                        </div>

                                    </form>

                                    <div class="new-account mt-3">
                                        <p>{{trans('auth.allready_sign_in')}} <a class="text-primary"
                                                                                 href="{{route('login',['type'=>request()->get('type')])}}">{{trans('auth.sign_in_attr')}}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

    @if(json_decode(get_settings('site_setting')) && isset(json_decode(get_settings('site_setting'))->recaptcha_site_key))
    <script src="https://www.google.com/recaptcha/api.js?render={{json_decode(get_settings('site_setting'))->recaptcha_site_key}}"></script>
    <script>
       $(document).on('click', '#submitForm',  function (e) {
            e.preventDefault();
            grecaptcha.ready(function() {
                grecaptcha.execute('{{json_decode(get_settings('site_setting'))->recaptcha_site_key}}', {action: 'submit'}).then(function(token) {
                    // Add your logic to submit to your backend server here.
                    if(token){
                        $('#g_append').html(`<input type="hidden" name="grecaptcha_response" value="${token}">`);
                        $('#signUpForm').submit();
                    }
                });
            });
        })

    </script>
    @endif
@endsection
