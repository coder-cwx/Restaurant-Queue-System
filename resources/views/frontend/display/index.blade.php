@extends('frontend.template.page')
<!-- page title -->
@section('title', 'Display Token | ' . Config::get('adminlte.title'))

@section('content')
    <!-- Masthead -->
    <header class="display text-white text-center">
        <div class="overlay"></div>
        <div class="container-fluid">
            <div class="row div-vh">
                <div class="col-4">
                    <div class="d-flex flex-column scrolling">
                        @php($i=0)
                        @foreach($data as $singleData)
                        <div class="font-mar textSelect{{ $i }}">
                            <span class="blue-color" id="dept-list">{{ $singleData->department->name }}</span><br>
                            <span id="counter-list">{{ $singleData->counter->name }}</span><br>
                            <span class="red-color" id="number-list">{{ $singleData->department->letter }}{{ str_pad($singleData->number, 4, '0', STR_PAD_LEFT) }}</span>

                        </div>
                        @php($i++)
                        @endforeach
                    </div>
                </div>

                <div class="col-8 padtb-16rem">
                    <span class="font-main-dept blue-color" id="dept">-</span><br>
                    <span class="font-main-dept" id="counter">-</span><br>
                    <span class="font-main-number red-color" id="number">-</span>
                    @if($setting_data->marquee != null && $setting_data->marquee != '')
                    <div class="marquee-text">
                        <p>{{ $setting_data->marquee }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </header>
@stop

@section('css')
@stop

@section('js')
    <script>var id_department = '{{ $id_department }}'</script>
    <script src="{{ asset('js/frontend/display/index.js'). '?v=' . rand(99999,999999) }}"></script>
@stop
