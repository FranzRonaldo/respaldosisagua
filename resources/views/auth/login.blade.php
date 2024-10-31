@extends('layouts.master-without-nav')
@section('title')
    @lang('translation.Login')
@endsection
@section('content')
    
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="text-center">
                        <a href="{{ url('index') }}" class="mb-5 d-block auth-logo">
                            <img src="{{ URL::asset('/assets/images/logoagua.png') }}" alt="" height="180"
                                class="logo logo-dark">
                            <img src="{{ URL::asset('/assets/images/logo-light.png') }}" alt="" height="22"
                                class="logo logo-light">
                        </a>
                    </div>
                </div>
            </div>
            <div class="row align-items-center justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div class="card">

                        <div class="card-body p-4">
                            <div class="text-center mt-2">
                                <h5 class="text-primary">¡Bienvenido a Guadalupe!</h5>
                                <p class="text-muted">Inicia sesion para entrar al sistema.</p>
                            </div>
                            <div class="p-2 mt-4">
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf

                                    <div class="mb-3">
                                        <label class="form-label" for="email">Correo Electronico</label>
                                        <input type="text" class="form-control @error('email') is-invalid @enderror"
                                            name="email" value="{{ old('email') }}" id="email"
                                            placeholder="Escriba su correo electronico">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="userpassword">Contraseña</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                                            value="" name="password" id="userpassword" placeholder="Ingrese su contraseña">
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        
                                    </div>
                                    
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="auth-remember-check"
                                            name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="auth-remember-check">Recuerdame</label>
                                    </div>

                                    <div class="mt-3 text-center">
                                        <button class="btn btn-primary w-sm waves-effect waves-light" type="submit">Iniciar Sesion</button>
                                    </div>
                                    <div class="float-begin">
                                            @if (Route::has('password.request'))
                                                <a href="{{ route('password.request') }}" class="text-muted">¿Olvido su contraseña?</a>
                                            @endif
                                        </div>
                                    <div class="mt-4 text-center">
                                        <p class="mb-0">¿No tiene una cuenta? <a href="{{ url('register') }}"
                                                class="fw-medium text-primary"> Registrarse </a> </p>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>

                    

                </div>
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
@endsection
