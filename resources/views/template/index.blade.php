@extends('layouts.dashboard')

@section('title',trans('layout.frontend_template'))

@section('main-content')
    @php $template = json_decode(get_settings('frontend_template')); @endphp
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>{{trans('layout.frontend_template')}}</h4>
                <p class="mb-0"></p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{trans('layout.home')}}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.frontend_template')}}</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="row">
        <div class="col-xl-12 col-xxl-12">
            <div class="card">
                <form action="{{route('template.store')}}" method="post" id="step-form-horizontal"
                      class="step-form-horizontal" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{old('id')?old('id'):(isset($template->id)?$template->id:'')}}">
                    <div class="card-header">
                        <h4 class="card-title text-primary">{{trans('layout.banner_section')}}</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <section>
                                <div class="row">
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.title')}}*</label>
                                            <input value="{{old('title')?old('title'):(isset($template->title)?$template->title:'')}}" type="text" name="title"
                                                   class="form-control" placeholder="Ex: It's the food and groceries" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label class="text-label">{{trans('layout.background_image')}} <span class="text-danger">({{trans('layout.expecting_image_size')}} : 500px by 500px)</span></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="bg_image" type="file" class="custom-file-input">
                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="card-header">
                        <h4 class="card-title text-primary">{{trans('layout.features_section')}}</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <section>
                                <div class="row">
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.main_title')}}*</label>
                                            <input value="{{old('main_title')?old('main_title'):(isset($template->main_title)?$template->main_title:'')}}" type="text" name="main_title"
                                                   class="form-control" placeholder="Ex: You prepare the food" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.first_title')}}*</label>
                                            <input value="{{old('first_title')?old('first_title'):(isset($template->first_title)?$template->first_title:'')}}" type="text" name="first_title"
                                                   class="form-control" placeholder="Ex: You prepare the food" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label class="text-label">{{trans('layout.first_img')}} <span class="text-danger">({{trans('layout.expecting_image_size')}} : 200px by 200px)</span></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="first_img" type="file" class="custom-file-input">
                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.first_description')}}*</label>
                                            <textarea class="form-control" name="first_description" cols="30" rows="5" placeholder="Ex: Would you like millions" required>{{old('first_description')?old('first_description'):(isset($template)?$template->first_description:'')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.sec_title')}}*</label>
                                            <input value="{{old('sec_title')?old('sec_title'):(isset($template->sec_title)?$template->sec_title:'')}}" type="text" name="sec_title"
                                                   class="form-control" placeholder="Ex: You prepare the food" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label class="text-label">{{trans('layout.sec_img')}} <span class="text-danger">({{trans('layout.expecting_image_size')}} : 200px by 200px)</span></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="sec_img" type="file" class="custom-file-input">
                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.sec_description')}}*</label>
                                            <textarea class="form-control" name="sec_description" cols="30" rows="5" placeholder="Ex: Would you like millions" required>{{old('sec_description')?old('sec_description'):(isset($template)?$template->sec_description:'')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.thr_title')}}*</label>
                                            <input value="{{old('thr_title')?old('thr_title'):(isset($template->thr_title)?$template->thr_title:'')}}" type="text" name="thr_title"
                                                   class="form-control" placeholder="Ex: You prepare the food" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label class="text-label">{{trans('layout.thr_img')}} <span class="text-danger">({{trans('layout.expecting_image_size')}} : 200px by 200px)</span></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="thr_img" type="file" class="custom-file-input">
                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.thr_description')}}*</label>
                                            <textarea class="form-control" name="thr_description" cols="30" rows="5" placeholder="Ex: Would you like millions" required>{{old('thr_description')?old('thr_description'):(isset($template)?$template->thr_description:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="card-header">
                        <h4 class="card-title text-primary">{{trans('layout.scan_section')}}</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <section>
                                <div class="row">
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.title')}}*</label>
                                            <input value="{{old('section_three_title')?old('section_three_title'):(isset($template->section_three_title)?$template->section_three_title:'')}}" type="text" name="section_three_title"
                                                   class="form-control" placeholder="Ex: You prepare the food" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label class="text-label">{{trans('layout.background_image')}} <span class="text-danger">({{trans('layout.expecting_image_size')}} : 500px by 280px)</span></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="section_three_bg_image" type="file" class="custom-file-input">
                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.description')}}*</label>
                                            <textarea class="form-control" name="section_three_description" cols="30" rows="5" placeholder="Ex: Would you like millions" required>{{old('section_three_description')?old('section_three_description'):(isset($template->section_three_description)?$template->section_three_description:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="card-header">
                        <h4 class="card-title text-primary">{{trans('layout.plan_section')}}</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <section>
                                <div class="row">
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.title')}}*</label>
                                            <input value="{{old('section_four_title')?old('section_four_title'):(isset($template->section_four_title)?$template->section_four_title:'')}}" type="text" name="section_four_title"
                                                   class="form-control" placeholder="Ex: List your restaurant" required>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="card-header">
                        <h4 class="card-title text-primary">{{trans('layout.section_comment')}}</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <section>
                                <div class="row">
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.first_name')}}*</label>
                                            <input value="{{old('first_name')?old('first_name'):(isset($template->first_name)?$template->first_name:'')}}" type="text" name="first_name"
                                                   class="form-control" placeholder="Ex: List your restaurant" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label class="text-label">{{trans('layout.first_img')}} <span class="text-danger">({{trans('layout.expecting_image_size')}} : 160px by 147px)</span></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="first_comment_img" type="file" class="custom-file-input">
                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.first_comment')}}*</label>
                                            <textarea class="form-control" name="first_comment" cols="30" rows="5" placeholder="Ex: Would you like millions" required>{{old('first_comment')?old('first_comment'):(isset($template->first_comment)?$template->first_comment:'')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.sec_name')}}*</label>
                                            <input value="{{old('sec_name')?old('sec_name'):(isset($template->sec_name)?$template->sec_name:'')}}" type="text" name="sec_name"
                                                   class="form-control" placeholder="Ex: List your restaurant" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label class="text-label">{{trans('layout.sec_img')}} <span class="text-danger">({{trans('layout.expecting_image_size')}} : 160px by 147px)</span></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="sec_comment_img" type="file" class="custom-file-input">
                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.sec_comment')}}*</label>
                                            <textarea class="form-control" name="sec_comment" cols="30" rows="5" placeholder="Ex: Would you like millions" required>{{old('sec_comment')?old('sec_comment'):(isset($template->sec_comment)?$template->sec_comment:'')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.thr_name')}}*</label>
                                            <input value="{{old('thr_name')?old('thr_name'):(isset($template->thr_name)?$template->thr_name:'')}}" type="text" name="thr_name"
                                                   class="form-control" placeholder="Ex: List your restaurant" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-2">
                                        <label class="text-label">{{trans('layout.thr_img')}} <span class="text-danger">({{trans('layout.expecting_image_size')}} : 160px by 147px)</span></label>
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input name="thr_comment_img" type="file" class="custom-file-input">
                                                <label class="custom-file-label">{{trans('layout.choose')}}</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.thr_comment')}}*</label>
                                            <textarea class="form-control" name="thr_comment" cols="30" rows="5" placeholder="Ex: Would you like millions" required>{{old('thr_comment')?old('thr_comment'):(isset($template->thr_comment)?$template->thr_comment:'')}}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="card-header">
                        <h4 class="card-title text-primary">{{trans('layout.subscribe_section')}}</h4>
                    </div>
                    <div class="card-body">
                        <div>
                            <section>
                                <div class="row">
                                    <div class="col-lg-12 mb-2">
                                        <div class="form-group">
                                            <label class="text-label">{{trans('layout.title')}}*</label>
                                            <input value="{{old('section_five_title')?old('section_five_title'):(isset($template->section_five_title)?$template->section_five_title:'')}}" type="text" name="section_five_title"
                                                   class="form-control" placeholder="Ex: List your restaurant" required>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                        <div class="pull-right mb-4">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection

@section('js')

@endsection
