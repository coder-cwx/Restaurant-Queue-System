@extends('adminlte::page')
<!-- page title -->
@section('title', 'Create and Update Departments ' . Config::get('adminlte.title'))

@section('content_header')
    <h1>Departments</h1>
@stop

@section('content')
    {{--Show message if any--}}
    @include('layouts.flash-message')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add or Update</h3>
        </div>

        {{ Form::open(array('url' => route($data->form_action), 'method' => 'POST','autocomplete' => 'off', 'files' => true)) }}
        {{ Form::hidden('id', $data->id, array('id' => 'id')) }}

        <div class="card-body">

            <div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">Name</strong>
                </div>
                <div class="col-sm-10 col-content">
                    {{ Form::text('name', $data->name, array('class' => 'form-control', 'required')) }}
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> Name of your department. Ex: Customer Service (CS), or Support, or Registration.
                    </small>
                </div>
            </div>
 	 	 	<div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">Letter</strong>
                </div>
                <div class="col-sm-10 col-content">
                    {{ Form::text('letter', $data->letter, array('class' => 'form-control', 'required')) }}
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> Letter for queue. Ex: A, or B, or C.
                    </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">Branch</strong>
                </div>
                <div class="col-sm-10 col-content">
                    {{ Form::select('id_branch', $branch, $data->id_branch, array('id' => 'id_branch', 'class' => 'form-control', 'placeholder' => 'Please Choose Branch', 'required')) }}
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> Branch name.
                    </small>
                </div>
            </div>
        </div>

        <div class="card-footer">
            <div id="form-button">
                <div class="col-sm-12 text-center top20">
                    <button type="submit" name="submit" id="btn-admin-member-submit" class="btn btn-primary">{{ $data->button_text }}</button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>

    <!-- /.card -->
    </div>
    <!-- /.row -->
    <!-- /.content -->
@stop

@section('css')
@stop

@section('js')
    <script>var typePage = "{{ $data->page_type }}";</script>
    <script src="{{ asset('js/backend/departments/form.js'). '?v=' . rand(99999,999999) }}"></script>
@stop
