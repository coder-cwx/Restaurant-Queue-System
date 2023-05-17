@extends('adminlte::page')
<!-- page title -->
@section('title', 'Set Display | ' . Config::get('adminlte.title'))

@section('content_header')
    <h1>Display</h1>
@stop

@section('content')
    {{--Show message if any--}}
    @include('layouts.flash-message')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Set Display</h3>
        </div>

        <div class="card-body">
            <div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">Department</strong>
                </div>
                <div class="col-sm-10 col-content">
                    {{ Form::select('id', $department, null, array('id' => 'department', 'class' => 'form-control', 'required')) }}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">URL</strong>
                </div>
                <div class="col-sm-10 col-content">
                    <span class="form-text text-muted url"></span>
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> Now you can visit this URL to show the current token number in another TV without login.
                    </small>
                </div>
            </div>
        </div>
    </div>

    <div class="card collapsed-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fa fa-question-circle" aria-hidden="true"></i> Sound not coming out in the browser</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">Click here <i class="fas fa-plus"></i>
                </button>
            </div>
        </div>

        <!-- /.card-header -->
        <div class="card-body p-0" style="text-align: center;padding: 20px !important;">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/0S2ycIvI7eA" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
        <!-- /.card-body -->
    <!-- /.card-footer -->
    </div>

    <!-- /.card -->
    </div>
    <!-- /.row -->
    <!-- /.content -->
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/backend/display/index.js'). '?v=' . rand(99999,999999) }}"></script>
@stop
