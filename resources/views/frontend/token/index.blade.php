@extends('frontend.template.page')
<!-- page title -->
@section('title', __("main.get_your_token") . ' | ' . Config::get('adminlte.title'))

@section('content')
    <!-- Masthead -->
    <header class="masthead text-white text-center">
        <div class="overlay"></div>
        <div class="container">
            <div id="row-token">
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <h1 class="mb-5">{{ __("main.get_your_token") }}</h1>
                    </div>

                    <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                        <form>
                            <div class="form-row">
                                <div class="col-12 col-md-12 mb-2 mb-md-0">
                                    {{ Form::select('id_branch', $branch, null, array('id' => 'id_branch', 'class' => 'form-control form-control-lg', 'placeholder' => __("main.choose_branch_placeholder"), 'required')) }}
                                    <small class="form-text text-muted text-help-style">
                                        {{ __("main.choose_branch_help_block") }}
                                    </small>
                                </div>
                                <div class="col-12 col-md-12 mb-2 mb-md-0">
                                    {{ Form::select('id_department', $department ?? '', null, array('id' => 'id_department', 'placeholder' => __("main.choose_department_placeholder"), 'class' => 'form-control form-control-lg', 'required')) }}
                                    <small class="form-text text-muted text-help-style">
                                        {{ __("main.choose_department_help_block") }}
                                    </small>
                                </div>
                                @if($settings->twilio_sid != null &&  $settings->twilio_sid != '' && $settings->twilio_token != null &&  $settings->twilio_token != '' && $settings->twilio_number != null &&  $settings->twilio_number != '')
                                <div class="col-12 col-md-12 mb-2 mb-md-0">
                                    {{ Form::input('phone', '', '', array('id' => 'phone', 'placeholder' => __("main.sms_placeholder"), 'class' => 'form-control form-control-lg')) }}
                                    <small class="form-text text-muted text-help-style">
                                        {{ __("main.sms_help_block") }}
                                    </small>
                                </div>
                                <h2 class="or-style">{{ __("main.or_text") }}</h2>
                                @endif
                                <div class="col-12 col-md-12 mb-2 mb-md-0">
                                    {{ Form::input('email', '', '', array('id' => 'email', 'placeholder' => __("main.email_placeholder"), 'class' => 'form-control form-control-lg')) }}
                                    <small class="form-text text-muted text-help-style">
                                        {{ __("main.email_help_block") }}
                                    </small>
                                </div>
                                <div class="col-12 col-md-12">
                                    <button type="button" id="get-it" class="btn btn-block btn-lg btn-primary">{{ __("main.button_get_it") }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div id="row-offline" style="display: none">
                <div class="row">
                    <div class="col-xl-9 mx-auto">
                        <h1 class="mb-5">{{ __("main.registration_close_title") }}</h1>
                    </div>

                    <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                        <p>{{ __("main.registration_close_message") }}</p>
                    </div>
                </div>
            </div>
        </div>
    </header>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.js') }}"></script>
    <script src="{{ asset('js/frontend/token/index.js'). '?v=' . rand(99999,999999) }}"></script>
@stop
