<ul class="metismenu sidebar-height" id="menu">
    @if(isset(auth()->user()->type) && auth()->user()->type=='customer')
        <li><a class="ai-icon" href="{{route('dashboard')}}" aria-expanded="false">
                <i class="flaticon-381-networking"></i>
                <span class="nav-text">{{trans('layout.dashboard')}}</span>
            </a>
        </li>
    @endif
    @if(auth()->check())
        @php $menu= \App\Models\Menu::where('user_id',auth()->user()->id)->orderBy('created_at','desc')->limit(1)->get(); @endphp
        @if(isset($menu[0]) && $menu[0])
            <li><a class="ai-icon" href="{{route('show.restaurant',['slug'=>$menu[0]->url])}}"
                   aria-expanded="false">
                    <i class="flaticon-381-add"></i>
                    <span class="nav-text">{{trans('layout.menu')}}</span>
                </a>
            </li>
        @endif
    @endif

    @foreach($rest_categories as $cat)
        <li><a data-id="{{$cat->id}}" class="ai-icon item-category" href="javascript:void(0)" aria-expanded="false">
                <i class="flaticon-381-networking"></i>
                <span class="nav-text">{{$cat->name}}</span>
            </a>
        </li>
    @endforeach
    @if(!auth()->check())
        <li><a class="ai-icon" href="{{route('registration',['type'=>'customer'])}}"
               aria-expanded="false">
                <i class="flaticon-381-add"></i>
                <span class="nav-text">{{trans('layout.signup')}}</span>
            </a>
        </li>
    @endif
</ul>

<div class="copyright mt-4">
    <p><strong>{{json_decode(get_settings('site_setting'))->name}} </strong> © {{date('Y')}} All Rights Reserved</p>
</div>
