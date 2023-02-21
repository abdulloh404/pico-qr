function getLiveOrders() {

    Visibility.every(5500, function () {
        let time = $('.live-order-created-at').first().attr('data-date');
        if (!time)
            time = '';

        $.ajax({
            method: 'get',
            url: '/check/recent/order',
            data: {time: time},
            success: function (res) {
                if (res.status == 'success') {
                    let html = '';
                    let approved_order = '';
                    let pending_order = '';
                    let ready_for_delivery = '';
                    let delivered_order = '';
                    $.each(res.data.pending_orders, function (index, value) {
                        if (value) {
                            pending_order +=`<div><div class="card order-card">
                                                <div class="card-header order-card-header">
                                                    <strong>Order  #${value.id}</strong>
                                                </div>
                                                <div class="card-body">
                                                    <ul>
                                                            <li class="mt-2 live-order-created-at" data-date="${value.live_created_at}">${value.created_at}</li>
                                                            <li class="mt-2">Delivered Within  ${value.delivered_within}</li>
                                                              ${value.item_name}
                                                              <li class="mt-2">${value.type}</li>
                                                            <li class="mt-2">
                                                                <strong>${value.total_price}</strong>
                                                            </li>
                                                    </ul>

                                                    <div class="mt-3">
                                                        <button data-order-id="${value.id}"
                                                                class="btn btn-primary btn-sm float-left details">
                                                            Details
                                                        </button>

                                                        <button type="button"
                                                                    class="btn light btn-sm ${value.order_status=='pending'?'badge-danger':'badge-success'} btn-sm dropdown-toggle float-right"
                                                                    data-toggle="dropdown" aria-expanded="false">
                                                            ${value.status}
                                                        </button>

                                                        <div class="dropdown-menu float-right"
                                                             x-placement="bottom-start"
                                                             style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">

                                                               <button class="dropdown-item" data-toggle="modal"
                                                                        data-input={"status":"approved","order_id":"${value.id}"}
                                                                        type="button"
                                                                        data-target="#delivered_within_modal">Approved</button>

                                                                        <button class="dropdown-item" type="button"
                                                                        data-message="Are you sure you to change the status to  Rejected ?"
                                                                       data-action="/order/status/update"
                                                                        data-input={"status":"rejected","order_id":"${value.id}"}
                                                                        data-toggle="modal"
                                                                        data-isAjax="true"
                                                                        data-toggle="modal"
                                                                        data-target="#modal-confirm">Rejected</button>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div></div>`;
                        }
                    });

                    $.each(res.data.approved_orders, function (index, value){
                        console.log(value.item_name);
                        approved_order +=`<div><div class="card  order-card">
            <div class="card-header order-card-header">
                <strong>Order #${value.id}</strong>
            </div>
            <div class="card-body">
                <ul>
                    <li class="mt-2 live-order-created-at" data-date="${value.live_created_at}">${value.created_at}</li>
                    <li class="mt-2">Delivered Within  ${value.delivered_within}</li>
                    <li class="mt-2">${value.item_name}</li>
                    <li class="mt-2">${value.type}</li>
                    <li class="mt-2">
                        <strong>${value.total_price}</strong>
                    </li>
                </ul>

                <div class="mt-3">
                    <button data-order-id="${value.id}"
                            class="btn btn-primary btn-xs float-left details">
                        Details
                    </button>

                    <button type="button"
                            class="btn light btn-sm ${value.order_status=='pending'?'badge-danger':'badge-success'} btn-sm dropdown-toggle float-right"
                            data-toggle="dropdown" aria-expanded="false">
                        Approved
                    </button>

                    <div class="dropdown-menu float-right"
                         x-placement="bottom-start"
                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">


                        <button class="dropdown-item" type="button"
                                data-message="Are you sure you to change the status to  Ready For Delivery ?"
                                data-action="/order/status/update"
                                data-input={"status":"ready_for_delivery","order_id":"${value.id}"}
                                data-toggle="modal"
                                data-isAjax="true"
                                data-toggle="modal"
                                data-target="#modal-confirm">Ready For Delivery</button>
                    </div>
                </div>
            </div>
        </div> </div>`
                    });

                    $.each(res.data.ready_for_delivery_orders, function (index, value){
                        ready_for_delivery +=`<div><div class="card order-card">
            <div class="card-header order-card-header">
                <strong>Order #${value.id}</strong>
            </div>
            <div class="card-body">
                <ul>
                    <li class="mt-2 live-order-created-at" data-date="${value.live_created_at}">${value.created_at}</li>
                    <li class="mt-2">Delivered Within  ${value.delivered_within}</li>
                    ${value.item_name}
                    <li class="mt-2">${value.type}</li>
                    <li class="mt-2">
                        <strong>${value.total_price}</strong>
                    </li>
                </ul>

                <div class="mt-3">
                    <button data-order-id="${value.id}"
                            class="btn btn-primary btn-xs float-left details d-block">
                        Details
                    </button>


                    <button type="button"
                            class="btn light btn-sm ${value.order_status=='pending'?'badge-danger':'badge-success'} d-block btn-sm dropdown-toggle float-right"
                            data-toggle="dropdown" aria-expanded="false">
                        Ready For Delivery
                    </button>


                    <div class="dropdown-menu float-right"
                         x-placement="bottom-start"
                         style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, 38px, 0px);">

                        <button class="dropdown-item" type="button"
                                data-message="Are you sure you to change the status to Delivered ?"
                                data-action="/order/status/update"
                                data-input={"status":"delivered","order_id":"${value.id}"}
                                data-toggle="modal"
                                data-isAjax="true"
                                data-toggle="modal"
                                data-target="#modal-confirm">Delivered</button>
                    </div>
                </div>
            </div>
        </div></div>`
                    });

                    $.each(res.data.delivered_orders, function (index, value){
                        delivered_order +=`<div>
        <div class="card order-card">
            <div class="card-header order-card-header">
                <strong>Order #${value.id}</strong>
            </div>
            <div class="card-body">
                <ul>
                    <li class="mt-2 live-order-created-at" data-date="${value.live_created_at}">${value.created_at}</li>
                    <li class="mt-2">Delivered Within ${value.delivered_within}</li>
                    ${value.item_name}
                    <li class="mt-2">${value.type}</li>
                    <li class="mt-2">
                        <strong>${value.total_price}</strong>
                    </li>
                </ul>

                <div class="mt-3">
                    <button data-order-id="${value.id}"
                            class="btn btn-primary btn-xs float-left details">
                        Details
                    </button>
                </div>
            </div>
        </div>
    </div>`
                    });

                    $('#liveOrderTable').html(`<div class="col-sm-3 col-lg-3 col-md-3">${pending_order}</div> <div class="col-sm-3 col-lg-3 col-md-3">${approved_order}</div> <div class="col-sm-3 col-lg-3 col-md-3">${ready_for_delivery}</div> <div class="col-sm-3 col-lg-3 col-md-3">${delivered_order}</div>`);
                }
            }

        });
    });
}

getLiveOrders();
