{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Dashboard  | ' . Config::get('adminlte.title'))

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            Hi, and Welcome!
        </div>
    </div>

    @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('admin'))
    <div class="row">
        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $users->count() }}</h3>

                    <p>Total Users</p>
                </div>
                <div class="icon">
                    <i class="fa fa-user-plus"></i>
                </div>
                <a href="{{ route('users') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-dark">
                <div class="inner">
                    <h3>{{ $departmentCount }}</h3>

                    <p>Total Departments</p>
                </div>
                <div class="icon">
                    <i class="fa fa-tags"></i>
                </div>
                <a href="{{ route('departments') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $queueCalledCount }}</h3>

                    <p>Total Today Have Called</p>
                </div>
                <div class="icon">
                    <i class="fa fa-phone-volume"></i>
                </div>
                <a href="{{ route('calls') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $queueHaventCalledCount }}</h3>

                    <p>Total Today Haven't Called</p>
                </div>
                <div class="icon">
                    <i class="fa fa-phone-alt"></i>
                </div>
                <a href="{{ route('calls') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
    </div>
    @endif

    @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('admin') || Auth::user()->hasRole('staff'))
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Members Offline and Online</h3>

            <div class="card-tools">
                <span class="badge badge-danger">{{ $users->count() }} Members</span>
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <!-- /.card-header -->
        <div class="card-body p-0">
            <ul class="users-list clearfix">
                @foreach($users as $user)
                    @if($user->is_online == 0)
                        @php($status = 'Offline')
                    @else
                        @php($status = 'Online')
                    @endif
                    <li>
                        <img src="{{ asset('uploads/'. $user->image) }}" alt="{{ $user->name }}" width="80">
                        <a class="users-list-name" href="#">{{ $user->name }}</a>
                        <span class="users-list-name"><i class="fa fa-building" aria-hidden="true"></i> {{ $user->id_branch != null ? $user->branch->name : '-' }}</span>
                        <span class="users-{{ $status }}">{{ $status }}</span>
                    </li>
                @endforeach

            </ul>
            <!-- /.users-list -->
        </div>
        <!-- /.card-body -->
        @if (Auth::user()->hasRole('administrator') || Auth::user()->hasRole('admin'))
        <div class="card-footer text-center">
            <a href="{{ route('users') }}">View All Users</a>
        </div>
        @endif
        <!-- /.card-footer -->
    </div>
    @endif
@stop

@section('css')
@stop

@section('js')
    <script src="{{ asset('js/backend/dashboard/index.js'). '?v=' . rand(99999,999999) }}"></script>
@stop
