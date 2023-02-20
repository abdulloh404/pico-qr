@extends('layouts.dashboard')

@section('title',trans('layout.call_waiter'))

@section('css')

@endsection

@section('main-content')
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>{{trans('layout.call_waiter')}}</h4>
                <p class="mb-0"></p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{trans('layout.home')}}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.call_waiter')}}</a></li>
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
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead class="text-center">
                            <tr>
                                <th><strong>{{trans('layout.username')}}</strong></th>
                                <th><strong>{{trans('layout.table')}}(position)</strong></th>
                                <th><strong>{{trans('layout.status')}}</strong></th>
                                <th><strong>{{trans('layout.action')}}</strong></th>
                            </tr>
                            </thead>
                            <tbody class="text-center">
                            @if($call_waiters)
                                @foreach($call_waiters as $call_waiter)
                                    <tr>
                                        <td>{{$call_waiter->user->name}}</td>
                                        <td>{{$call_waiter->table->name.'('.$call_waiter->table->position.')'}}</td>
                                        <td>
                                                @if($call_waiter->status=='pending')
                                                <button type="button" class="btn light badge-danger btn-sm dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                                                    Pending
                                                </button>
                                                @else
                                                <button type="button" class="btn light badge-success btn-sm dropdown-toggle " data-toggle="dropdown" aria-expanded="false">
                                                    Solved
                                                </button>
                                                @endif

                                            <div class="dropdown-menu" x-placement="bottom-start"
                                                 style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">
                                                @if($call_waiter->status=='pending')
                                                <button class="dropdown-item" type="button"
                                                        data-message="{{trans('Are you sure to solve this request ?')}}"
                                                        data-action='{{route('call.waiter.status',['restaurant'=>$call_waiter->restaurant_id,'id'=>$call_waiter->id])}}'
                                                        data-input={"_method":"get"}
                                                        data-toggle="modal"
                                                        data-target="#modal-confirm">{{trans('Solved')}}</button>
                                                @else
                                                <button disabled="disabled" class="btn btn-default disabled">{{$call_waiter->status}}</button>
                                                @endif
                                            </div>

                                        </td>
                                        <td>
                                            <button class="btn badge-danger light" type="button"
                                                    data-message="{{trans(trans('layout.message.call_waiter_request_delete_warn'))}}"
                                                    data-action='{{route('call.waiter.delete',['id'=>$call_waiter->id])}}'
                                                    data-input={"_method":"get"}
                                                    data-toggle="modal"
                                                    data-target="#modal-confirm">{{trans('delete')}}</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
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
