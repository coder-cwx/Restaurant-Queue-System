@extends('adminlte::page')
<!-- page title -->
@section('title', 'Calls | ' . Config::get('adminlte.title'))

@section('content_header')
    <h1>Calls</h1>
@stop

@section('content')
    {{--Show message if any--}}
    @include('layouts.flash-message')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group card">
                <div class="card-header">
                    <h3 class="card-title">Online / Offline</h3>
                </div>
                <div class="card-body" style=" height: 96px; ">
                    <div class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                        <input type="checkbox" class="custom-control-input" id="is_online">
                        <label class="custom-control-label switch-label" data-on="1" data-off="0" for="is_online">Click to swith online or offline.</label>
                    </div>
                    <span class="form-text text-muted" style="color: #b1b1b1 !important; font-size: 15px; text-align: left; ">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> You can still call the token number even when offline.
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-6">
                <div class="form-group card">
                    <div class="card-header">
                        <h3 class="card-title">Shortcut Keyboard</h3>
                    </div>
                    <div class="card-body">
                        <span class="form-text text-muted shortcut" style="color: #b1b1b1 !important; font-size: 15px; text-align: left; "></span>
                        <span class="form-text text-muted department" style="color: #b1b1b1 !important; font-size: 15px; text-align: left; "></span>
                        <span class="form-text text-muted counter" style="color: #b1b1b1 !important; font-size: 15px; text-align: left; "></span>
                    </div>
                </div>
        </div>
    </div>

    <div class="background-calling" style="display: none;"></div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add</h3><br>
                    <span class="form-text text-muted" style="color: #b1b1b1 !important; font-size: 15px; text-align: left; ">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> Call the token queue
                    </span>
                </div>

                {{ Form::open(array('url' => route($data->form_action), 'method' => 'POST','autocomplete' => 'off', 'files' => true)) }}
                <input type="hidden" name="csrf-token" id="csrf-token" value="{{ csrf_token() }}">

                <div class="card-body">

                    <div class="form-group row">
                        <div class="col-sm-4 col-form-label">
                            <strong class="field-title">Department</strong>
                        </div>
                        <div class="col-sm-8 col-content">
                            {{ Form::select('id_department', $department, null, array('id' => 'id_department', 'class' => 'form-control', 'placeholder' => 'Please Choose Department', 'required')) }}
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-sm-4 col-form-label">
                            <strong class="field-title">Counter</strong>
                        </div>
                        <div class="col-sm-8 col-content">
                            {{ Form::select('id_counter', $counter, null, array('id' => 'id_counter', 'class' => 'form-control', 'placeholder' => 'Please Choose Counter', 'required')) }}
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div id="form-button">
                        <div class="col-sm-12 text-center top20">
                            <button type="button" name="shortcut" id="shortcut" class="btn btn-success">Shortcut <i class="fa fa-keyboard"></i></button>
                            <button type="button" name="submit" id="btn-calls-submit" class="btn btn-primary">Next {{ $data->button_text }} <i class="fa fa-arrow-circle-right"></i></button>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List Queue</h3><br>
                    <span class="form-text text-muted" style="color: #b1b1b1 !important; font-size: 15px; text-align: left; ">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> If your customer loses their token, you can help with click button icon 'plane paper'
                    </span>
                </div>
                <div class="card-body size-scroll">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Department</th>
                                <th>Counter</th>
                                <th>Number</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Send Token</th>
                            </tr>
                            </thead>
                            <tbody id="loadCalls">
                            @if($data->count() > 0)
                                @foreach($data as $singleData)
                                    @if($singleData->email_client != null)
                                        @php $emailClient = '<td><button type="button" id="send" data-email="'. $singleData->email_client .'" data-id="'. $singleData->crypt .'" class="btn btn-primary"><i class="fa fa-paper-plane"></i></button></td>'; @endphp
                                    @else
                                        @php $emailClient = '<td> No Email Client </td>'; @endphp
                                    @endif

                                    <tr>
                                        <td>{{ $singleData->branch->name }}</td>
                                        <td>{{ $singleData->department->name }}</td>
                                        <td>{{ $singleData->id_counter!=null?$singleData->counter->name:'-' }}</td>
                                        <td>{{ $singleData->department->letter }}{{ str_pad($singleData->number, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $singleData->status==null?'Waiting for a queue':$singleData->status }}</td>
                                        <td>{{ date_format($singleData->date, 'Y-m-d') }}</td>
                                        {!! $emailClient !!}
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7" class="text-center">There is no queue.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.card -->
    </div>
    <!-- /.row -->

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List Queue Have Called Today</h3><br>
                    <span class="form-text text-muted" style="color: #b1b1b1 !important; font-size: 15px; text-align: left; ">
                        <i class="fa fa-question-circle" aria-hidden="true"></i> This is the list of the token queue have called today
                    </span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                            <tr>
                                <th>Branch</th>
                                <th>Department</th>
                                <th>Counter</th>
                                <th>Number</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody id="loadHaveCalled">
                            @if($have_called->count() > 0)
                                @foreach($have_called as $singleData)
                                    <tr>
                                        <td>{{ $singleData->branch->name }}</td>
                                        <td>{{ $singleData->department->name }}</td>
                                        <td>{{ $singleData->id_counter!=null?$singleData->counter->name:'-' }}</td>
                                        <td>{{ $singleData->department->letter }}{{ str_pad($singleData->number, 4, '0', STR_PAD_LEFT) }}</td>
                                        <td>{{ $singleData->status }}</td>
                                        <td>{{ date_format($singleData->date, 'Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">There is no data.</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- /.card -->
    </div>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('js/backend/calls/form.js'). '?v=' . rand(99999,999999) }}"></script>
    <script>
        var user = "{{ Auth::user()->id }}";
        var branch = "{{ Auth::User()->id_branch }}";
    </script>
@stop
