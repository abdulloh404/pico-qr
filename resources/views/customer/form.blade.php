@csrf
<div>
    <section>
        <div class="row">
            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.name')}}*</label>
                    <input value="{{isset($customer)?$customer->name:''}}" type="text" name="name"
                           class="form-control" placeholder="Ex: John Doe" required>
                </div>
            </div>

            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.email')}}</label>
                    <input readonly onfocus="this.removeAttribute('readonly');"
                           value="{{isset($customer)?$customer->email:''}}" type="email" class="form-control"
                           placeholder="Ex: hello@example.com" name="email">
                </div>
            </div>


            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.password')}}</label>
                    <input readonly onfocus="this.removeAttribute('readonly');" type="password" class="form-control"
                           placeholder="{{trans('layout.password')}}"
                           name="password">
                </div>
            </div>

            @if(auth()->user()->type=='admin')
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="mb-1">{{trans('layout.choose_plan')}}</label>
                        <select name="plan_id" id="plan" class="form-control">
                            @foreach($plans as $plan)
                                <option
                                    {{isset($customer) && isset($customer->current_plans[0]) && $customer->current_plans[0]->plan_id==$plan->id?'selected':''}} value="{{$plan->id}}">{{$plan->title}}
                                    ({{formatNumberWithCurrSymbol($plan->cost)}})
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
            @endif

            @if(auth()->user()->type=='restaurant_owner')
                <div class="col-lg-6">
                    <div class="form-group">
                        <label class="mb-1">{{trans('layout.role')}}</label>
                        <select name="role" class="form-control">

                            @foreach($roles as $role)
                                <option
                                    {{isset($customer->role) && $customer->role==$role->name?'selected':''}} value="{{$role->name}}">{{$role->name}}
                                </option>
                            @endforeach

                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="mb-1">{{trans('layout.restaurant')}}</label>
                        <select name="restaurant_id" class="form-control">
                            @foreach($restaurants as $restaurant)
                                <option
                                    {{isset($customer->restaurant_id) && $customer->restaurant_id==$restaurant->id?'selected':''}} value="{{$restaurant->id}}">{{$restaurant->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

        </div>
    </section>

</div>

