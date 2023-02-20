@extends('layouts.dashboard')

@section('title',trans('layout.live_order'))

@section('css')
    <link href="{{asset('vendor/datatables/css/jquery.dataTables.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">
    <script>
        let orderDataTable = '';
    </script>
    <style>


        .order-card-header {
            padding: 10px 11px !important;
        }

        .order-table {
            overflow-y: scroll;
            height: 415px;
        }

        #liveOrderTable .card-body {
            padding: 6px 11px -1px 11px !important;
        }

        #liveOrderTable {
            height: 445px;
            overflow-y: scroll;
        }

        .live-order-loader-section {
            width: 24%;
            height: 200px;
            margin: 6% auto;
        }

        .live-order-loader {
            width: 100%;
            height: 100%;
        }

        .details {
            padding: 2px 7px !important;
            font-size: 12px !important;
        }

        .btn-sm.dropdown-toggle {
            padding: 3px 7px !important;
            font-size: 12px !important;
        }

        @media (max-width: 400px) {
            #liveOrderTable {
                overflow: scroll;
            }
        }
    </style>

@endsection

@section('main-content')
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>{{trans('layout.live_order')}}</h4>
                <p class="mb-0">{{trans('layout.live_order')}}</p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{trans('layout.home')}}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.live_order')}}</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                <div class="col-sm-4 col-lg-3 col-md-3">
                    <strong class="font-w700"> {{trans('layout.new_order')}}</strong>
                </div>
                <div class="col-sm-4 col-lg-3 col-md-3">
                    <strong class="font-w700"> {{trans('layout.approved')}}</strong>
                </div>
                <div class="col-sm-4 col-lg-3 col-md-3">
                    <strong class="font-w700"> {{trans('layout.ready_for_delivery')}}</strong>
                </div>
                <div class="col-sm-4 col-lg-3 col-md-3">
                    <strong class="font-w700"> {{trans('layout.delivered')}}</strong>
                </div>
            </div>
            <div class="row mt-4" id="liveOrderTable">
                <div class="col-sm-12 text-cemter">
                    <div class="live-order-loader-section">
                        <img src="{{asset('images/ajax-loader.gif')}}" class="live-order-loader" alt="">
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

@endsection

@section('js')
    <script src="{{asset('vendor/datatables/js/jquery.dataTables.min.js')}}"></script>

    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
        <script src="{{asset("js/live_order.js")}}"></script>
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

                            html += `<tr>
                                                    <td class="center">${value.key}</td>
                                                    <td>${value.item_name}</td>
                                                    <td>${value.quantity}</td>
                                                    <td>${value.currency_symbol}${value.price}</td>
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
                                              <a class="btn btn-sm btn-info" target="_blank" href="/order/print/${res.info.order_id}"
                                        type="button">{{trans('layout.print')}}</a>
                                <a class="btn btn-sm btn-info" target="_blank" href="/order/print/${res.info.order_id}&type=pdf"
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


    </script>

@endsection
