@extends('layouts.auth', ['title' => 'Lock'])

@section('content')

<div class="card">
    <div class="card-body p-0 bg-black auth-header-box rounded-top">
        <div class="text-center p-3">
            <a href="{{ route('any', 'index')}}" class="logo logo-admin">
                <img src="/images/logo-sm.png" height="50" alt="logo" class="auth-logo">
            </a>
            <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Enter Password</h4>
            <p class="text-muted fw-medium mb-0">Hello Mark, enter your password to unlock the screen !</p>
        </div>
    </div>
    <div class="card-body pt-0">
        <form class="my-4" action="{{ route('any', 'index')}}">
            <div class="form-group mb-2">
                <label class="form-label" for="userpassword">Password</label>
                <input type="password" class="form-control" name="password" id="userpassword" placeholder="Enter password">
            </div><!--end form-group-->

            <div class="form-group mb-0 row">
                <div class="col-12">
                    <div class="d-grid mt-3">
                        <button class="btn btn-primary" type="button">Unlock <i class="fas fa-sign-in-alt ms-1"></i></button>
                    </div>
                </div><!--end col-->
            </div> <!--end form-group-->
        </form><!--end form-->
        <div class="text-center  mb-2">
            <p class="text-muted">Not you ? return <a href="{{ route('second', ['auth', 'register'])}}" class="text-primary ms-2">Sign in here</a></p>
        </div>
    </div><!--end card-body-->
</div><!--end card-->

@endsection