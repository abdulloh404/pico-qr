@extends('layouts.dashboard')

@section('title',trans('layout.order_list'))

@section('css')
    <link href="{{asset('vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
    <script>
        let orderDataTable = '';
    </script>
<style>
    .dropdown-menu{
        max-width: 30rem !important;
    }
</style>
@endsection

@section('main-content')
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>{{trans('layout.order')}}</h4>
                <p class="mb-0">{{trans('layout.your_order')}}</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{trans('layout.home')}}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.orders')}}</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-lg-4">
                            <h4 class="card-title">{{trans('layout.list')}}</h4>
                        </div>
                        <div class="col-lg-8">
                            <div class="dropdown show" style="width:500px !important;">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{trans('layout.filtering')}}
                                </a>

                                <div class="dropdown-menu" style="width: 100% !important;" aria-labelledby="dropdownMenuLink">
                                    <div class="card-body">
                                        <form action="{{route('order.index')}}" method="get" id="filtering-form" class="step-form-horizontal" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>{{trans('layout.from_date')}}</label>
                                                        <div class="input-group">
                                                            <input type="date" class="form-control float-right" name="from_date" id="filtering_from_date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>{{trans('layout.to_date')}}</label>
                                                        <div class="input-group">
                                                            <input type="date" class="form-control float-right" name="to_date" id="filtering_to_date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="row">
                                                        <div class="col-lg-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="yes" id="filtering_paid" name="paid">
                                                                <label class="form-check-label" for="flexCheckDefault">
                                                                    {{trans('layout.paid')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" value="yes" id="filtering_unpaid" name="unpaid">
                                                                <label class="form-check-label" for="flexCheckChecked">
                                                                    {{trans('layout.unpaid')}}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="pull-right">
                                                <button class="btn btn-primary btn-sm" type="submit" id="filter-btn">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pull-right">
                        <button type="button" id="check_new_order"
                        class="btn btn-sm btn-info">{{trans('layout.check_new_order')}}</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table dt-responsive nowrap w-100" id="orderTable">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><strong>{{trans('layout.name')}}</strong></th>
                                <th><strong>{{trans('layout.restaurant')}}/{{trans('layout.table')}}</strong></th>
                                <th><strong>{{trans('layout.type')}}</strong></th>
                                <th><strong>{{trans('layout.add_new_item')}}</strong></th>
                                <th><strong>{{trans('layout.status')}}</strong></th>
                                <th><strong>{{trans('layout.amount')}}</strong></th>
                                <th><strong>{{trans('layout.delivered_within')}}</strong></th>
                                <th><strong>{{trans('layout.payment_status')}}</strong></th>


                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{--    Order Details Quick View --}}
    <div class="container p-2">
        <div class="modal fade" id="viewOrderDetails">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="pull-right print-section">

                            </div>
                        </div>
                    </div>
                    <div class="row" id="printableSection">
                        <div class="col-lg-12">

                            <div class="card mt-3">
                                <div class="card-header" id="detailsHeader">
                                </div>
                                <div class="card-body">
                                    <div class="row mb-5">
                                        <div class="mt-4 col-xl-3 col-lg-3 col-md-6 col-sm-12" id="customerInfo">

                                        </div>

                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>{{trans('layout.item')}}</th>
                                                <th>{{trans('layout.quantity')}}</th>
                                                <th>{{trans('layout.price')}}</th>
                                                <th>{{trans('layout.status')}}</th>
                                                <th>{{trans('layout.discount')}}</th>
                                                <th>{{trans('layout.tax')}}</th>
                                                <th>{{trans('layout.total_price')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody id="showOrderDetails">

                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="detailsSingleInfo">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--    End--}}
    <div class="modal fade" id="statusModal" style="z-index: 9999">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header border-0 p-3">
                    <h4 class="modal-title">{{trans('Item status changed')}}</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="orderId" name="order_id">
                    <input type="hidden" id="detailsId" name="details_id">
                    <input type="hidden" id="status" name="status">
                    <h5 id="confirmation"></h5>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer p-2">
                    <button id="itemStatusConfirm" type="submit"
                            class="btn btn-primary">{{trans('layout.confirm')}}</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>

    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script !src="">
        "use strict";
        function generateActionButton(order) {
            let html = '';
            const deleteHtml = `<button class="dropdown-item" type="button"
                                                        data-message="{{trans('layout.message.order_delete_warning')}}"
                                                        data-action='{{route('order.delete')}}'
                                                        data-input={"id":"${order.id}","_method":"delete"}
                                                        data-toggle="modal"
                                                        data-target="#modal-confirm">{{trans('layout.delete')}}</button>`;
            if (order.status == 'pending') {
                html = `@if(auth()->user()->hasPermissionTo('order_approved'))<button class="dropdown-item" data-toggle="modal"   data-input={"status":"approved","order_id":"${order.id}"}
                type="button" data-target="#delivered_within_modal">{{trans('layout.approve')}}</button> @endif`;
            } else if (order.status == 'approved') {
                html = `@can('ready_for_delivery') <button class="dropdown-item" type="button"
                                                        data-message="{{trans('layout.message.order_status_warning',['status'=>'ready for delivery'])}}"
                                                        data-action='{{route('order.update.status')}}'
                                                        data-input={"status":"ready_for_delivery","order_id":"${order.id}"}
                                                        data-toggle="modal"
                                                        data-isAjax="true"
                                                        data-target="#modal-confirm">{{trans('layout.ready_for_delivery')}}</button> @endcan`;
            }
            if (order.status == 'ready_for_delivery' || (order.order_type == 'takeaway' && order.status != 'delivered')) {
                html += `@can('delivered') <button class="dropdown-item" type="button"
                                                        data-message="{{trans('layout.message.order_status_warning',['status'=>'delivered'])}}"
                                                        data-action='{{route('order.update.status')}}'
                                                        data-input={"status":"delivered","order_id":"${order.id}"}
                                                        data-toggle="modal"
                                                        data-isAjax="true"
                                                        data-toggle="modal"
                                                        data-target="#modal-confirm">{{trans('layout.delivered')}}</button> @endcan`;
            }

            return html;

        }


        const queryString=window.location.search;
        orderDataTable = $('#orderTable').DataTable({
            processing: true,
            //   serverSide: true,
            ajax: {
                "url": '{{route('order.getAll')}}'+queryString,
                "dataSrc": "data",
                "type": "GET",
                "data": function(d){
                    d.form = $("#filtering-form").serializeArray();
                }
            },
            columnDefs: [
                {targets: 0, visible: false}
            ],
            columns: [
                {data: 'row'},
                {data: 'name'},
                {data: 'restaurant_name_table'},
                {data: 'type'},
                {data: 'new_item'},
                {data: 'raw_status'},
                {data: 'total_price'},
                {data: 'delivered_within'},
                {data: 'payment_status'},


                {
                    data: function (row) {
                        let html = `<div class="dropdown">
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

                        ${generateActionButton(row)}
                        <a href="" data-order-id="${row.id}" class="dropdown-item details">{{trans('layout.details')}}</a>
                                            </div>
                                        </div>`;
                        return html;
                    }
                },
            ],
            order: [[0, 'asc']],
            bInfo: false,
            bLengthChange: false,
        });

        $('#check_new_order').on('click', function (e) {
            e.preventDefault();
            orderDataTable.ajax.reload();
        })
    </script>
    <script !src="">
        "use strict";
        $(document).on('click', '.details', function (e) {
            e.preventDefault();

            const orderId = $(this).attr('data-order-id');

            $('#viewOrderDetails').modal('show');
            $.ajax({
                method: "get",
                url: "{{route('quick.order.details')}}",
                data: {
                    orderId: orderId,
                },

                success: function (res) {
                    let html = '';
                    if (res.status == 'success') {

                        $.each(res.data, function (index, value) {
                            let status = '';
                            let button = '';
                            if (value.detail_status=='pending'){
                                status=`@can('order_approved')<button data-order-id="${value.order_id}"  data-details-id="${value.id}" data-status="approved" data-confirmation="{{trans('Are you sure to approved this item ?')}}" type="button"
                                                                                        data-confirmation="{{trans('layout.category_inactive_confirmation')}}"  class="changeStatus dropdown-item detailsStatus_${value.id}">
                                                                                        Approved
                                                                 </button> @endcan`
                            }else if(value.detail_status=='approved'){
                                status=`@can('ready_for_delivery')<button data-order-id="${value.order_id}" data-details-id="${value.id}" data-status="ready_for_delivery"
                                                                data-confirmation="{{trans('Are you sure to ready-for delivered this item ?')}}" type="button"
                                                                data-confirmation="{{trans('layout.category_inactive_confirmation')}}"  class="changeStatus dropdown-item detailsStatus_${value.id}">
                                                                                        {{trans('layout.ready_for_delivery')}}
                                        </button> @endcan`
                            }else if(value.detail_status=='ready_for_delivery'){
                                status=`@can('delivered')<button data-order-id="${value.order_id}"  data-details-id="${value.id}" data-status="delivered"
                                                                data-confirmation="{{trans('Are you sure to delivered this item ?')}}" type="button"
                                                                data-confirmation="{{trans('layout.category_inactive_confirmation')}}"  class="changeStatus dropdown-item detailsStatus_${value.id}">
                                                                                        {{trans('layout.delivered')}}
                                </button> @endcan`
                            }else if(value.detail_status=='delivered'){
                                status=`<button  data-confirmation="{{trans('Are you sure to approved this item ?')}}" type="button"  class="disabled dropdown-item detailsStatus_${value.id}">
                                                                                        {{trans('layout.delivered')}}
                                </button>`
                            }

                            if (value.detail_status=='pending'){

                                button=`<button type="button" class="btn light btn-outline-danger btn-sm dropdown-toggle detailsStatus_${value.id}" data-toggle="dropdown" aria-expanded="false">
                                                                        ${value.detail_status}
                                                    </button>`
                            }else if(value.detail_status=='approved'){
                                button=`<button type="button" class="btn light btn-outline-info  btn-sm dropdown-toggle detailsStatus_${value.id}" data-toggle="dropdown" aria-expanded="false">
                                                                        ${value.detail_status}
                                                    </button>`
                            }else if(value.detail_status=='ready_for_delivery'){
                                button=`<button type="button" class="btn light btn-outline-info btn-sm dropdown-toggle detailsStatus_${value.id}" data-toggle="dropdown" aria-expanded="false">
                                                                        ${value.detail_status}
                                                    </button>`

                            }else if(value.detail_status=='delivered'){
                                button=`<button type="button" class="btn light btn-outline-success btn-sm dropdown-toggle detailsStatus_${value.id}" data-toggle="dropdown" aria-expanded="false">
                                                                        ${value.detail_status}
                                                    </button>`
                            }


                            html += `<tr>
                                                    <td class="center">${value.key}</td>
                                                    <td>${value.item_name}</td>
                                                    <td>${value.quantity}</td>
                                                    <td>${value.currency_symbol}${value.price}</td>
                                                    <td>
                                                     ${button}
                                                        <div class="dropdown-menu" x-placement="bottom-start"
                                                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">

                                                               ${status}

                                                        </div>
                                                    </td>
                                                    <td>${value.currency_symbol}${value.discount}</td>
                                                    <td>${value.currency_symbol}${value.tax_amount}</td>
                                                    <td>${value.currency_symbol}${value.total}</td>
                                                  </tr>`
                        });

                        const info = `
                                    <div class="row">

                                    <div class="col-lg-4 col-sm-5 ml-auto">
                                        <table class="table table-clear">
                                            <tbody>
                                            <tr>
                                                <td class="left"><strong>{{trans('layout.total_discount')}}</strong>
                                                    </td>
                                                    <td class="right">${res.info.currency_symbol}${res.info.total_discount}</td>
                                                </tr>
                                                <tr>
                                                    <td class="left"><strong>{{trans('layout.total_tax')}}</strong></td>
                                                    <td class="right">${res.info.currency_symbol}${res.info.total_tax}</td>
                                                </tr>
                                                <tr>
                                                    <td class="left"><strong>{{trans('layout.total')}}</strong></td>
                                                    <td class="right">${res.info.currency_symbol}${res.info.total_price}</td>
                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    `;
                        const detailsHeader = `
                                              {{trans('layout.details')}} <strong>{{trans('layout.order')}}
                        ${res.info.order_id}</strong> <span
                                              class="float-right">
                                          <strong>{{trans('layout.status')}}:</strong> ${res.info.order_status}</span>`;

                        const customer_info = `
                                              <h6>{{trans('layout.customer')}}:</h6>
                                                            <div><strong>${res.info.customer_name}</strong></div>

                                              <div>{{trans('layout.email')}}: ${res.info.customer_email}</div>

                                              <div id="address">{{trans('layout.phone')}}: ${res.info.phone}</div>

                                              <div>{{trans('layout.delivery_address')}}: ${res.info.address}</div>

                              `;
                        const print_pdf = `
                                              <a class="btn btn-sm btn-info" target="_blank" href="order/print/${res.info.order_id}"
                                        type="button">{{trans('layout.print')}}</a>
                                <a class="btn btn-sm btn-info" target="_blank" href="order/print/${res.info.order_id}&type=pdf"
                                        type="button">{{trans('layout.pdf')}}</a>`

                        $('#showOrderDetails').html(html);
                        $('#detailsSingleInfo').html(info);
                        $('#detailsHeader').html(detailsHeader);
                        $('#customerInfo').html(customer_info);
                        $('.print-section').html(print_pdf);


                    } else {
                        toastr.error(res.message, 'failed', {timeOut: 5000});
                    }
                }
            })

        });
        $(document).on('click', '.changeStatus', function (e) {
            e.preventDefault();
            const order_id = $(this).attr('data-order-id');
            const details_id = $(this).attr('data-details-id');
            const status = $(this).attr('data-status');
            const confirmation = $(this).attr('data-confirmation');

            $('#orderId').val(order_id);
            $('#detailsId').val(details_id);
            $('#status').val(status);
            $('#confirmation').text(confirmation);
            $('#statusModal').modal('show');
        });

        $(document).on('click', '#itemStatusConfirm', function (e) {
            e.preventDefault();

            const orderId = $('#orderId').val();
            const details_id = $('#detailsId').val();
            const status = $('#status').val();
            $(this).html(' <i class="fa fa-spinner fa-spin"></i> Loading');


            $.ajax({
                method: "post",
                url: "{{route('order.details.status')}}",
                data: {
                    orderId: orderId, details_id: details_id, status: status, _token: '{{csrf_token()}}',
                },

                success: function (res) {
                    let html = '';
                    if (res.status == 'success') {
                        toastr.success(res.message, 'success', {timeOut: 5000});
                        $('#statusModal').modal('hide');
                        $('#itemStatusConfirm').html('{{trans('layout.confirm')}}');
                        $('.detailsStatus_' + details_id).text(status);
                        $('#viewOrderDetails').modal('hide');
                    } else {
                        toastr.error(res.message, 'failed', {timeOut: 5000});
                        $('#itemStatusConfirm').html('{{trans('layout.confirm')}}');
                    }
                }
            })
        });

    </script>

@endsection
