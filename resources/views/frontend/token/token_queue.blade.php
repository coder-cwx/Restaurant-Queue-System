@extends('frontend.template.page')
<!-- page title -->
@section('title', 'Get Your Token | ' . Config::get('adminlte.title'))

@section('content')
    <!-- Masthead -->
    <header class="masthead text-white text-center">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                {{--If token is wrong--}}
                @if($token === 0 && $current_token === 0)
                <div class="col-xl-9 mx-auto">
                    <h3 class="mb-2">{{ __("token_queue.token_fail") }}</h3>
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                    <p>
                        {!! __("token_queue.token_fail_message") !!} <a href="{{ route('token') }}">{{ __("token_queue.here_lang") }}</a>
                    </p>
                </div>
                {{--If token is expired--}}
                @elseif($token === -1 && $current_token === -1)
                <div class="col-xl-9 mx-auto">
                    <h3 class="mb-2">{{ __("token_queue.token_expired") }}</h3>
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                    <p>{!! __("token_queue.token_expired_message") !!} <a href="{{ route('token') }}">{{ __("token_queue.here_lang") }}</a></p>
                </div>
                {{--Show the token--}}
                @else
                <div class="col-xl-9 mx-auto">
                    <h3 class="mb-2">{{ __("token_queue.token_success") }}</h3>
                </div>
                <div class="col-md-10 col-lg-8 col-xl-7 mx-auto">
                    <p class="token-number">{{ $token }}</p>
                    <p><span>{{ __("token_queue.token_success_branch") }}: <span class="branch-name">{{ $branch }}</span></span><br>{{ __("token_queue.token_success_department") }}: <span id="dept">{{ $department }}</span><br>{{ date('Y-m-d') }}</p>

                    <form class="mb-5">
                        <div class="form-row">
                            <div class="col-12 col-md-9 mb-2 mb-md-0">
                                <input class="form-control form-control-lg" placeholder="{{ __("token_queue.token_email_placeholder") }}" id="email">
                            </div>
                            <div class="col-12 col-md-3">
                                <button type="button" id="send" class="btn btn-block btn-lg btn-primary"><i class="fa fa-paper-plane"></i> {{ __("token_queue.token_email_button") }}</button>
                            </div>
                        </div>
                    </form>
                    <p>{{ __("token_queue.token_current") }}</p>
                    <p class="current-token-number" id="currentToken">{{ $current_token }}</p>
                </div>
                @endif
            </div>
        </div>
    </header>
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('vendor/sweetalert2/sweetalert2.all.js') }}"></script>
    <script>
        var token = '{{ $token }}';
        var current_token = '{{ $current_token }}';
    </script>
    <script src="{{ asset('js/frontend/token/token_queue.js'). '?v=' . rand(99999,999999) }}"></script>
@stop
