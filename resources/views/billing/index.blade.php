@extends('layouts.dashboard')

@section('title',trans('layout.billings'))


@section('main-content')
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>{{trans('layout.billings')}}</h4>
                <p class="mb-0">{{trans('layout.billings')}}</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{trans('layout.home')}}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.billings')}}</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table dt-responsive nowrap w-100" id="orderTable">

                            <thead class="text-center">
                            <tr>
                                <th>#</th>
                                <th><strong>{{trans('layout.name')}}</strong></th>
                                <th><strong>{{trans('layout.restaurant')}}/{{trans('layout.table')}}</strong></th>
                                <th><strong>{{trans('layout.amount')}}</strong></th>

                                <th><strong>{{trans('layout.billing_status')}}</strong></th>
                                <th></th>

                            </tr>
                            </thead>
                            <tbody class="text-center">
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->id}}</td>
                                    <td>{{$order->name}}</td>
                                    <td>{{$order->restaurant->name .'(' .$order->table->name . ')'}}</td>
                                    <td>
                                        {{formatNumberWithCurrSymbol($order->total_price)}}
                                    </td>
                                    <td>
                                        @if($order->payment_status=='paid')
                                            <button class="btn-sm btn badge-success light">{{trans('layout.paid')}}</button>
                                        @else
                                            <button class="btn btn-sm badge-danger light">{{trans('layout.unpaid')}}</button>
                                        @endif
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
