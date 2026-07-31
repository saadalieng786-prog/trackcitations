@extends('layout.partials.body')

@section('body')
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-main relative">
            <div class="auth-wrapper v1 flex items-center w-full h-full min-h-screen">
                <div class="auth-form flex items-center justify-center grow flex-col min-h-screen bg-cover relative p-6 bg-[url('{{ asset('images/authentication/img-auth-bg.jpg') }}')] dark:bg-none dark:bg-themedark-bodybg">
                    <div class="card sm:my-12 w-full max-w-[480px] shadow-none">
                        <div class="card-body !p-10">
                            <div class="text-center">
                                <a href="#"><img src="{{ asset('images/logo-dark.png') }}" alt="img" class="mx-auto h-header-height"/></a>
                            </div>
                            <div class="relative my-5">
                                <div aria-hidden="true" class="absolute flex inset-0 items-center">
                                    <div class="w-full border-t border-theme-border dark:border-themedark-border"></div>
                                </div>
                                <div class="relative flex justify-center">
                                    <span class="px-4 bg-theme-cardbg dark:bg-themedark-cardbg">Reset Password</span>
                                </div>
                            </div>
                            <div class="mb-3">
                                @if (session('status'))
                                    <div class="mb-4 font-medium text-sm text-green-600">
                                        {{ session('status') }}
                                    </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" id="floatingInput" value="{{ old('email') }}" placeholder="Email Address" required autofocus />
                                @if ($errors->has('email'))
                                    <span class="invalid-feedback text-danger">
                                        <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" id="password" placeholder="Password" required />
                                @if ($errors->has('password'))
                                    <span class="invalid-feedback text-danger">
                                        <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required />
                                @if ($errors->has('password_confirmation'))
                                    <span class="invalid-feedback text-danger">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary w-full">{{ __('Reset Password') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
