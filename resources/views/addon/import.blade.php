@extends('layouts.dashboard')

@section('title',trans('layout.import'))

@section('css')

@endsection

@section('main-content')
    <div class="row page-titles mx-0">
        <div class="col-sm-6 p-md-0">
            <div class="welcome-text">
                <h4>{{trans('layout.import')}}</h4>
                <p class="mb-0"></p>
            </div>
        </div>
        <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{trans('layout.home')}}</a></li>
                <li class="breadcrumb-item active"><a href="javascript:void(0)">{{trans('layout.import')}}</a></li>
            </ol>
        </div>
    </div>
    <!-- row -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">{{trans('layout.import')}}</h2>
                    <div class="float-right">

                        <a class="btn btn-sm btn-primary"
                           href="{{route('addon.index')}}">{{trans('layout.back')}}</a>

                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body">

                    <form action="{{route('addon.import.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="form-group">
                                    <label for="choose_file"{{trans('layout.choose_addon_zip_file')}}</label>
                                    <input type="file" name="addon" accept=".zip">
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-sm btn-primary"
                                            type="submit">{{trans('layout.submit')}}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
@endsection

@section('js')

@endsection
