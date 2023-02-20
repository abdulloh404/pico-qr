@extends('layouts.dashboard')

@if (auth()->user()->type =='admin')
    @section('title',trans('layout.customer'))
@else
    @section('title',trans('layout.staff'))
@endif

@section('css')

@endsection

@section('main-content')
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                @if(auth()->user()->type =='admin')
                    <h4>{{trans('layout.customer')}}</h4>
                @else
                    <h4>{{trans('layout.staff')}}</h4>
                @endif
                <p class="mb-0"></p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{trans('layout.home')}}</a></li>
                @if(auth()->user()->type =='admin')
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.customer')}}</a></li>
                @else
                    <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.staff')}}</a></li>
                @endif
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">{{trans('layout.list')}}</h4>
                    <div class="pull-right">
                        <a href="{{route('customers.create')}}"
                           class="btn btn-sm btn-primary">{{trans('layout.create')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead>
                            <tr>
                                <th><strong>#</strong></th>
                                <th><strong>{{trans('layout.name')}}</strong></th>
                                @if(auth()->user()->type =='restaurant_owner')
                                <th><strong>{{trans('layout.role')}}</strong></th>
                                    <th><strong>{{trans('layout.restaurant')}}</strong></th>
                                @endif
                                @if(auth()->user()->type =='admin')
                                    <th><strong>{{trans('layout.plan_name')}}</strong></th>
                                    <th><strong>{{trans('layout.expiry_date')}}</strong></th>
                                    <th><strong>{{trans('layout.plan_status')}}</strong></th>
                                    <th><strong>{{trans('layout.user_status')}}</strong></th>
                                @else
                                    <th></th>
                                    <th></th>
                                @endif
                                <th><strong>{{trans('layout.action')}}</strong></th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $key=> $customer)
                                @php $currentPlan=isset($customer->current_plans[0])?$customer->current_plans[0]:''; @endphp
                                <tr>
                                    <td>{{++$key}}</td>
                                    <td>{{$customer->name}}</td>
                                    @if(auth()->user()->type =='restaurant_owner')
                                    <td>{{ucfirst(str_replace('_', ' ', $customer->role))}}</td>
                                        <td>{{$customer->restaurant->name}}</td>
                                    @endif

                                    @if(auth()->user()->type=='admin')
                                        @if(isset($currentPlan->plan) && $currentPlan->plan->id == 1)
                                            <td>{{trans('layout.choose_a_plan')}}</td>
                                        @else
                                            <td>{{$currentPlan?ucwords($currentPlan->plan->title):''}}</td>
                                        @endif
                                        <td>{{$currentPlan?$customer->current_plans[0]->expired_date:''}}</td>
                                        <td>
                                            @if($currentPlan?ucwords($customer->current_plans[0]->status=='approved'):'')
                                                <span class="badge light badge-success">{{trans('layout.approved')}}</span>
                                            @else
                                                <span class="badge light badge-warning">{{trans('layout.pending')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($customer->status=='pending')
                                                <span class="badge light badge-info">{{trans('layout.pending')}}</span>
                                            @elseif($customer->status=='banned')
                                                <span class="badge light badge-warning">{{trans('layout.banned')}}</span>
                                            @else
                                                <span class="badge light badge-success">{{trans('layout.approved')}}</span>
                                            @endif
                                        </td>
                                    @else
                                        <td></td>
                                        <td></td>
                                    @endif
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn btn-success light sharp"
                                                    data-toggle="dropdown">
                                                <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1">
                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                        <rect x="0" y="0" width="24" height="24"/>
                                                        <circle fill="#000000" cx="5" cy="12" r="2"/>
                                                        <circle fill="#000000" cx="12" cy="12" r="2"/>
                                                        <circle fill="#000000" cx="19" cy="12" r="2"/>
                                                    </g>
                                                </svg>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="{{route('customers.edit',[$customer])}}" class="dropdown-item"
                                                   type="button">{{trans('layout.edit')}}</a>
                                                <button class="dropdown-item" type="button"
                                                        data-message="{{trans('layout.message.customer_delete_warning')}}"
                                                        data-action='{{route('customers.destroy',[$customer])}}'
                                                        data-input={"_method":"delete"}
                                                        data-toggle="modal"
                                                        data-target="#modal-confirm">{{trans('layout.delete')}}</button>
                                                @if($customer->status == 'approved')
                                                <button class="dropdown-item" type="button"
                                                        data-message="{{trans('layout.message.customer_banned_warning')}}"
                                                        data-action='{{route('user.banned',[$customer])}}'
                                                        data-input={"_method":"get","banned_id":"{{$customer->id}}"}
                                                        data-toggle="modal"
                                                        data-target="#modal-confirm">{{trans('layout.banned')}}</button>
                                                @elseif($customer->status == 'banned')
                                                    <button class="dropdown-item" type="button"
                                                            data-message="{{trans('layout.message.customer_approved_warning')}}"
                                                            data-action='{{route('user.approved',[$customer])}}'
                                                            data-input={"_method":"get","approved_id":"{{$customer->id}}"}
                                                            data-toggle="modal"
                                                            data-target="#modal-confirm">{{trans('layout.approved')}}</button>
                                                @else
                                                    <button class="dropdown-item" type="button"
                                                            data-message="{{trans('layout.message.customer_banned_warning')}}"
                                                            data-action='{{route('user.banned',[$customer])}}'
                                                            data-input={"_method":"get","banned_id":"{{$customer->id}}"}
                                                            data-toggle="modal"
                                                            data-target="#modal-confirm">{{trans('layout.banned')}}</button>
                                                    <button class="dropdown-item" type="button"
                                                            data-message="{{trans('layout.message.customer_approved_warning')}}"
                                                            data-action='{{route('user.approved',[$customer])}}'
                                                            data-input={"_method":"get","approved_id":"{{$customer->id}}"}
                                                            data-toggle="modal"
                                                            data-target="#modal-confirm">{{trans('layout.approved')}}</button>
                                                @endif

                                                @if(auth()->user()->type=='admin')
                                                    @if(!isset($customer->email_verified_at))
                                                    <a class="dropdown-item" href="{{route('verified.user', ['id'=>$customer->id])}}">{{trans('layout.verify')}}</a>
                                                    @endif
                                                    <button class="dropdown-item" type="button"
                                                            data-message="{{trans('layout.message.login_as_warning')}}"
                                                            data-action='{{route('restaurant.login.as',['id'=>$customer->id])}}'
                                                            data-input={"_method":"get"}
                                                            data-toggle="modal"
                                                            data-target="#modal-confirm">{{trans('layout.login_as')}}</button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')

@endsection
