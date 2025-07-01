@extends('layouts.auth')

@section('content')
<div class="auth-wrapper auth-v2">
    <div class="auth-inner row m-0">
        <!-- Brand logo-->
        <a class="brand-logo" href="{{ route('admin.dashboard') }}">
            <h2 class="brand-text text-primary ml-1">ResQin</h2>
        </a>
        <!-- /Brand logo-->
        <div class="d-none d-lg-flex col-lg-8 align-items-center p-5">
            <div class="w-100 d-lg-flex align-items-center justify-content-center px-5">
                <img class="img-fluid" src="{{ asset('images/pages/forgot-password-v2.svg') }}" alt="Forgot Password V2" />
            </div>
        </div>
        <!-- /Left Text-->
        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                <h2 class="card-title font-weight-bold mb-1">Forgot Password? 🔒</h2>
                <p class="card-text mb-2">Don't worry, reset your password here</p>

                @if (session('status'))
                    <div class="alert alert-success mb-2" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="auth-forgot-password-form mt-2" action="{{ route('admin.password.email') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="forgot-password-email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" type="email" id="forgot-password-email" name="email" value="{{ old('email') }}" placeholder="john@example.com" tabindex="1" autofocus />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <button class="btn btn-primary btn-block btn-lg waves-effect waves-float waves-light" tabindex="2">Send reset link</button>
                </form>

                <p class="text-center mt-2">
                    <a href="{{ route('admin.login') }}">
                        <i class="bx bx-chevron-left bx-sm"></i>
                        <span>Back to login</span>
                    </a>
                </p>
            </div>
        </div>
        <!-- /Forgot Password-->
    </div>
</div>
@endsection
