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
                <img class="img-fluid" src="{{ asset('images/pages/register-v2.svg') }}" alt="Register V2" />
            </div>
        </div>
        <!-- /Left Text-->
        <div class="d-flex col-lg-4 align-items-center auth-bg px-2 p-lg-5">
            <div class="col-12 col-sm-8 col-md-6 col-lg-12 px-xl-2 mx-auto">
                <h2 class="card-title font-weight-bold mb-1">Welcome to ResQin! 👋</h2>
                <p class="card-text mb-2">Please register to create an admin account</p>

                @if (session('status'))
                    <div class="alert alert-success mb-2" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <form class="auth-register-form mt-2" action="{{ route('admin.register') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label class="form-label" for="register-name">Name</label>
                        <input class="form-control @error('name') is-invalid @enderror" type="text" id="register-name" name="name" value="{{ old('name') }}" placeholder="John Doe" tabindex="1" autofocus />
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="register-email">Email</label>
                        <input class="form-control @error('email') is-invalid @enderror" type="email" id="register-email" name="email" value="{{ old('email') }}" placeholder="john@example.com" tabindex="2" />
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="register-password">Password</label>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input class="form-control form-control-merge @error('password') is-invalid @enderror" type="password" id="register-password" name="password" tabindex="3" placeholder="············" aria-describedby="password" />
                            <div class="input-group-append">
                                <span class="input-group-text cursor-pointer">
                                    <i data-feather="eye"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="register-password-confirmation">Password Confirmation</label>
                        <div class="input-group input-group-merge form-password-toggle">
                            <input class="form-control form-control-merge" type="password" id="register-password-confirmation" name="password_confirmation" tabindex="4" placeholder="············" />
                            <div class="input-group-append">
                                <span class="input-group-text cursor-pointer">
                                    <i data-feather="eye"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary btn-block btn-lg waves-effect waves-float waves-light" tabindex="5">Sign up</button>
                </form>

                <p class="text-center mt-2">
                    <span>Already have an account?</span>
                    <a href="{{ route('admin.login') }}">
                        <span>&nbsp;Sign in instead</span>
                    </a>
                </p>
            </div>
        </div>
        <!-- /Register-->
    </div>
</div>
@endsection
