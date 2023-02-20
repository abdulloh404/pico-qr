@csrf
<div>
    <section>
        <div class="row">

            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.item_name')}}*</label>
                    <input value="{{old('name')?old('name'):(isset($category)?$category->name:'')}}" type="text" name="name"
                           class="form-control" placeholder="Ex: Burger" required>
                </div>
            </div>
            <div class="col-lg-6 mb-2">
                <div class="form-group">
                    <label class="text-label">{{trans('layout.status')}}*</label>
                    <select name="status" class="form-control">
                        <option {{isset($category) && $category->status=='active'?'selected':''}} value="active">{{trans('layout.active')}}</option>
                        <option {{isset($category) && $category->status=='inactive'?'selected':''}} value="inactive">{{trans('layout.inactive')}}</option>
                    </select>
                </div>
            </div>

        </div>
    </section>

</div>
