<ul class="metismenu sidebar-height" id="menu">
    @if(auth()->user()->type=='customer')
        @php $customer=auth()->user();  $menu= \App\Models\Menu::where('user_id',$customer->id)->orderBy('created_at','desc')->limit(1)->get(); @endphp
        @if(isset($menu[0]) && $menu[0])
            <li><a class="ai-icon" href="{{route('show.restaurant',['slug'=>$menu[0]->url])}}" aria-expanded="false">
                    <i class="flaticon-381-networking"></i>
                    <span class="nav-text">{{trans('Menu')}}</span>
                </a>
            </li>
        @endif
    @endif

    @can('restaurant_manage')
        <li><a class="ai-icon" href="{{route('dashboard')}}" aria-expanded="false">
                <i class="flaticon-381-networking"></i>
                <span class="nav-text">{{trans('layout.dashboard')}}</span>
            </a>
        </li>
    @endcan
    @if(auth()->user()->type=='restaurant_owner')
        <li class="{{isSidebarActive('live.order')}} active-no-child"><a class="ai-icon"
                                                                         href="{{route('live.order')}}"
                                                                         aria-expanded="false">
                <i class="flaticon-381-notepad "></i>
                <span class="nav-text">{{trans('layout.live_order')}} <div class="pulse-css live-order-blink"></div></span>
            </a>

        </li>
    @endif

    @can('order_list')
        <li class="{{isSidebarActive('order*')}} active-no-child"><a class="ai-icon" href="{{route('order.index')}}"
                                                                     aria-expanded="false">
                <i class="flaticon-381-notepad "></i>
                <span class="nav-text">{{trans('layout.orders')}}</span>
            </a>

        </li>
    @endcan
    @if(auth()->user()->type=='restaurant_owner')
        <li class="{{isSidebarActive('call.waiter')}} active-no-child"><a class="ai-icon"
                                                                          href="{{route('call.waiter')}}"
                                                                          aria-expanded="false">
                <i class="flaticon-381-television"></i>
                <span class="nav-text">{{trans('layout.call_waiter')}}</span>
            </a>
        </li>
    @endif
        @php
            $modules = modules_status('MultiRestaurant');
        @endphp
        @can('table_booking_manage')
            @if($modules)
                <li class="{{isSidebarActive('multirestaurant::tables.booking')}} active-no-child"><a class="ai-icon"
                                                                                  href="{{route('multirestaurant::tables.booking')}}"
                                                                                  aria-expanded="false">
                        <i class="flaticon-381-television"></i>
                        <span class="nav-text">{{trans('multirestaurant::layout.table_booking')}}<small class="addon">({{trans('multirestaurant::layout.addon')}})</small></span>
                    </a>
                </li>
            @endif
        @endcan
    @can('billing')
        <li class="{{isSidebarActive('billings')}} active-no-child"><a class="ai-icon"
                                                                       href="{{route('billings')}}"
                                                                       aria-expanded="false">
                <i class="flaticon-381-network "></i>
                <span class="nav-text">{{trans('layout.billings')}}</span>
            </a>
        </li>
    @endcan
    @can('category_manage')
        <li class="{{isSidebarActive('category*')}} active-no-child"><a class="ai-icon"
                                                                        href="{{route('category.index')}}"
                                                                        aria-expanded="false">
                <i class="flaticon-381-television"></i>
                <span class="nav-text">{{trans('layout.category')}}</span>
            </a>
        </li>
    @endcan


    @can('item_manage')
        <li class="{{isSidebarActive('item*')}} active-no-child"><a class="ai-icon" href="{{route('item.index')}}"
                                                                    aria-expanded="false">
                <i class="flaticon-381-network"></i>
                <span class="nav-text">{{trans('layout.items')}}</span>
            </a>
        </li>
    @endcan
    @can('restaurant_manage')
        <li class="{{isSidebarActive('restaurant*')}} active-no-child"><a class="ai-icon"
                                                                          href="{{route('restaurant.index')}}"
                                                                          aria-expanded="false">
                <i class="flaticon-381-television"></i>
                <span class="nav-text">{{trans('layout.restaurant')}}</span>
            </a>
        </li>
    @endcan
    @can('table_manage')
        <li class="{{isSidebarActive('table*')}} active-no-child"><a class="ai-icon" href="{{route('table.index')}}"
                                                                     aria-expanded="false">
                <i class="flaticon-381-layer-1 "></i>
                <span class="nav-text">{{trans('layout.table')}}</span>
            </a>

        </li>
    @endcan
    @can('tax_manage')
        <li class="{{isSidebarActive('tax*')}} active-no-child"><a class="ai-icon" href="{{route('tax.index')}}"
                                                                   aria-expanded="false">
                <i class="flaticon-381-layer-1 "></i>
                <span class="nav-text">{{trans('layout.tax')}}</span>
            </a>

        </li>
    @endcan
    @can('qr_manage')
        <li class="{{isSidebarActive('qr*')}} active-no-child"><a class="ai-icon" href="{{route('qr.maker')}}"
                                                                  aria-expanded="false">
                <i class="fa fa-qrcode"></i>
                <span class="nav-text">{{trans('layout.qr_maker')}}</span>
            </a>
        </li>
    @endcan
    @can('plan_list')
        <li class="{{isSidebarActive('plan.list')}} active-no-child"><a class="ai-icon" href="{{route('plan.list')}}"
                                                                        aria-expanded="false">
                <i class="flaticon-381-network "></i>
                <span class="nav-text">{{trans('layout.plan_list')}}</span>
            </a>

        </li>
    @endcan
    @can('manage_user')
        <li class="{{isSidebarActive('customers.index')}} active-no-child"><a class="ai-icon"
                                                                              href="{{route('customers.index')}}"
                                                                              aria-expanded="false">
                <i class="fa fa-user-circle"></i>
                <span class="nav-text">
                    @if(auth()->user()->type=='restaurant_owner')
                        {{trans('layout.staff')}}
                    @elseif(auth()->user()->type=='admin')
                        {{trans('layout.users')}}
                    @endif
                </span>
            </a>

        </li>
    @endcan
    @can('report')
        <li class="{{isSidebarActive('report*')}} active-no-child"><a class="ai-icon" href="{{route('report.index')}}"
                                                                      aria-expanded="false">
                <i class="flaticon-381-layer-1 "></i>
                <span class="nav-text">{{trans('layout.report')}}</span>
            </a>

        </li>
    @endcan
        @can('plan_manage')
            <li class="{{isSidebarActive('plan*')}} active-no-child"><a class="ai-icon" href="{{route('plan.index')}}"
                                                                        aria-expanded="false">
                    <i class="flaticon-381-network "></i>
                    <span class="nav-text">{{trans('layout.plan')}}</span>
                </a>

            </li>
        @endcan
        @can('user_plan_change')
            <li class="{{isSidebarActive('user.plan')}} active-no-child"><a class="ai-icon" href="{{route('user.plan')}}"
                                                                            aria-expanded="false">
                    <i class="flaticon-381-network "></i>
                    <span class="nav-text">{{trans('layout.user_plan')}}</span>
                </a>

            </li>

        @endcan
        @can('city_manage')
            @if($modules)
                <li class="{{isSidebarActive('multirestaurant::cities')}} active-no-child"><a class="ai-icon" href="{{route('multirestaurant::cities')}}" aria-expanded="false">
                        <i class="ti-map"></i>
                        <span class="nav-text">{{trans('multirestaurant::layout.cities')}}@if (env('APP_DEMO'))<small class="addon">({{trans('multirestaurant::layout.addon')}})</small>@endif</span>
                    </a>
                </li>
            @endif
        @endcan
        @can('template_manage')
            <li><a class="has-arrow ai-icon" href="javascript:void(0)" aria-expanded="false">
                    <i class="ti-layout-cta-right"></i>
                    <span class="nav-text">{{trans('layout.template')}}</span>
                </a>
                <ul aria-expanded="false">
                    @if($modules)
                        <li>
                            <a href="{{route('multirestaurant::multirestaurant.template')}}">{{trans('multirestaurant::layout.multi_restaurant')}}@if (env('APP_DEMO'))<small class="addon">({{trans('multirestaurant::layout.addon')}})</small>@endif</a>
                        </li>
                    @endif
                    <li><a href="{{route('template.index')}}">{{trans('layout.frontend_template')}}</a></li>
                </ul>
            </li>
        @endcan
    @if(auth()->user()->type!='user')
        <li class="{{isSidebarActive('settings*')}} active-no-child"><a href="{{route('settings')}}" class="ai-icon"
                                                                        aria-expanded="false">
                <i class="flaticon-381-settings-2"></i>
                <span class="nav-text">{{trans('layout.settings')}}</span>
            </a>
        </li>
    @endif
    @can('restaurant_owner_manage')
        <li class="{{isSidebarActive('customers.index')}} active-no-child"><a class="ai-icon"
                                                                              href="{{route('customers.index')}}"
                                                                              aria-expanded="false">
                <i class="fa fa-user-circle"></i>
                <span class="nav-text">   {{trans('layout.customer')}}  </span>
            </a>

        </li>
    @endcan
        @can('addon_manage')
            <li class="{{isSidebarActive('addon.index')}} active-no-child"><a class="ai-icon" href="{{route('addon.index')}}" aria-expanded="false">
                    <i class="ti-plug"></i>
                    <span class="nav-text">{{trans('layout.addon')}}</span>
                </a>
            </li>
        @endcan
</ul>


<div class="copyright">
    <p><strong>{{json_decode(get_settings('site_setting'))->name}} </strong>
        © {{date('Y')}} {{trans('layout.all_right_reserved')}}</p>
</div>



