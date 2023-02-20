@extends('layouts.dashboard')

@section('title',trans('layout.addon'))

@section('css')

@endsection

@section('main-content')
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>{{trans('layout.addon')}}</h4>
                <p class="mb-0"></p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{trans('layout.home')}}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.addon')}}</a></li>
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
                        <a href="{{route('addon.import')}}"
                           class="btn btn-sm btn-primary">{{trans('layout.import')}}</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-responsive-md">
                            <thead class="text-center">
                            <tr>
                                <th><strong>{{trans('layout.name')}}</strong></th>
                                <th><strong>{{trans('layout.status')}}</strong></th>
                                <th><strong>{{trans('layout.action')}}</strong></th>
                            </tr>
                            </thead>
                            <tbody class="text-center">
                            @if($modules)
                                @foreach($modules as $module)
                                    <tr>
                                        <td>{{$module['name']}}</td>
                                        <td>
                                            @if($module['status']=='true')
                                                <span
                                                    class="badge light badge-success">{{trans('layout.enable')}}</span>
                                            @else
                                                <span
                                                    class="badge light badge-warning">{{trans('layout.disable')}}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($module['status']=='true')
                                                <button class="btn btn-sm btn-danger" type="button"
                                                        data-message="{{trans('layout.message.are_you_sure_you_want_to_disable_this_module')}}"
                                                        data-action='{{route('addon.changeStatus')}}'
                                                        data-input={"_method":"post","name":"{{$module['name']}}","status":"disable"}
                                                        data-toggle="modal"
                                                        data-target="#modal-confirm">{{trans('layout.disable')}}</button>
                                            @else
                                                <button class="btn btn-info btn-sm " type="button"
                                                        data-message="{{trans('layout.message.are_you_sure_you_want_to_enable_this_module')}}"
                                                        data-action='{{route('addon.changeStatus')}}'
                                                        data-input={"_method":"post","name":"{{$module['name']}}","status":"enable"}
                                                        data-toggle="modal"
                                                        data-target="#modal-confirm">{{trans('layout.enable')}}</button>
                                            @endif
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-sm " type="button"
                                                    data-message="{{trans('layout.message.are_you_sure_you_want_to_uninstall_this_module')}}"
                                                    data-action='{{route('addon.uninstall')}}'
                                                    data-input={"_method":"post","name":"{{$module['name']}}"}
                                                    data-toggle="modal"
                                                    data-target="#modal-confirm">{{trans('layout.uninstall')}}</button>
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
