@extends('adminlte::page')
<!-- page title -->
@section('title', 'Settings | ' . Config::get('adminlte.title'))

@section('content_header')
    <h1>Settings</h1>
@stop

@section('content')
    {{--Show message if any--}}
    @include('layouts.flash-message')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Setup Your System</h3>
        </div>

        {{ Form::open(array('url' => route($data->form_action), 'method' => 'POST','autocomplete' => 'off', 'files' => true)) }}
        {{ Form::hidden('id', $data->id, array('id' => 'id')) }}

        <div class="card-body">

            <div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">Marquee</strong>
                </div>
                <div class="col-sm-10 col-content">
                    {{ Form::text('marquee', $data->marquee, array('class' => 'form-control')) }}
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> The marquee will show at display token. Leave blank if you don't want to display it.
                    </small>
                </div>
            </div>
            <hr>
            <div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">Twilio SID</strong>
                </div>
                <div class="col-sm-10 col-content">
                    {{ Form::text('twilio_sid', $data->twilio_sid, array('class' => 'form-control')) }}
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> Your Twilio SID, info: <a href="https://twilio-cms-prod.s3.amazonaws.com/images/aZ76nKkfYuZGC-WSf4VkwwQ0kHQcnKG7IDAtLsRIe2WiwV.width-500.png">more here</a>. Leave blank if you don't want to use SMS.
                    </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">Twilio Auth Token</strong>
                </div>
                <div class="col-sm-10 col-content">
                    {{ Form::text('twilio_token', $data->twilio_token, array('class' => 'form-control')) }}
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> Your Twilio auth token, info: <a href="https://twilio-cms-prod.s3.amazonaws.com/images/aZ76nKkfYuZGC-WSf4VkwwQ0kHQcnKG7IDAtLsRIe2WiwV.width-500.png">more here</a>. Leave blank if you don't want to use SMS.
                    </small>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-2 col-form-label">
                    <strong class="field-title">Twilio Phone Number</strong>
                </div>
                <div class="col-sm-10 col-content">
                    {{ Form::text('twilio_number', $data->twilio_number, array('class' => 'form-control')) }}
                    <small id="passwordHelpBlock" class="form-text text-muted">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> Your Twilio phone number [E.164] format, info: <a href="https://twilio-cms-prod.s3.amazonaws.com/images/-X_oWOL4HY_P-gvBBNYkL25mVAbQM9Noz5wVfQ32j5sCSa.width-500.png">more here</a>. Leave blank if you don't want to use SMS.
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
@stop
