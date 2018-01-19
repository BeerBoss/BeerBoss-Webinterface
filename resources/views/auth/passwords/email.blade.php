@extends('layouts.login')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/adminlte/plugins/iCheck/square/blue.css')}}">
@stop
@section('body')
    <div class="login-box">
        <div class="login-logo">
            <b>Beer</b>Boss
        </div>
        <!-- /.login-logo -->
        <div class="login-box-body">
            <p class="login-box-msg">Type in your email address to reset your password</p>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            <form method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }} has-feedback">
                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email address" autofocus required>
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                    <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                </div>
                <div>
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Send reset email</button>
                </div>

                <div class="row">
                    <!-- /.col -->

                    <!-- /.col -->
                </div>
            </form>
        </div>
        <!-- /.login-box-body -->
    </div>
    <!-- /.login-box -->
@stop