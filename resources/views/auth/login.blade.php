@extends('layouts.auth', ['title' => 'Login'])

@section('content')
    <div class="card">
        <div class="card-body p-0 bg-black auth-header-box rounded-top">
            <div class="text-center p-3">
                <img src="/images/logo-sm.png" height="50" alt="logo" class="auth-logo">
                <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Welcome to Insure Pesa</h4>
                <p class="text-muted fw-medium mb-0">Sign in to continue.</p>
            </div>
        </div>
        <div class="card-body pt-0">
            <form class="my-4" method="POST" action="{{ route('login') }}">

                @csrf
                @if (sizeof($errors) > 0)
                    @foreach ($errors->all() as $error)
                        <p class="text-danger mb-3">{{ $error }}</p>
                    @endforeach
                @endif

                <div class="form-group mb-2">
                    <label class="form-label" for="email"><i class="iconoir-at-sign"></i> Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" name="email">
                </div><!--end form-group-->

                <div class="form-group">
                    <label class="form-label" for="password"><i class="iconoir-lock"></i> Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter password">
                </div><!--end form-group-->

                <div class="form-group row mt-3">
                    <div class="col-sm-6">
                        <div class="form-check form-switch form-switch-success">
                            <input class="form-check-input" type="checkbox" id="customSwitchSuccess">
                            <label class="form-check-label" for="customSwitchSuccess">
                                <i class="iconoir-bookmark"></i> Remember me
                            </label>
                        </div>
                    </div><!--end col-->
                    <div class="col-sm-6 text-end">
                        <a href="#" class="text-muted font-13">
                            <i class="iconoir-lock"></i> Forgot password?
                        </a>
                    </div><!--end col-->
                </div><!--end form-group-->

                <div class="form-group mb-0 row">
                    <div class="col-12">
                        <div class="d-grid mt-3">
                            <button class="btn btn-primary" type="submit">
                                <i class="iconoir-log-in"></i> Log In
                            </button>
                        </div>
                    </div><!--end col-->
                </div> <!--end form-group-->
            </form><!--end form-->
            <div class="text-center mb-2">
                <p class="text-muted">
                    <i class="iconoir-user"></i> Don't have an account?
                    <a href="#" class="text-primary ms-2">Register</a>
                </p>
            </div>
        </div><!--end card-body-->
    </div><!--end card-->
@endsection
