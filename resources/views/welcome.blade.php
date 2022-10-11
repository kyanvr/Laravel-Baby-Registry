@extends('layouts.app')


@section('content')

<div class="container w-100 vh-75 flex justify-content-center align-items-center">
    <div
        class="container flex justify-content-between align-items-center h-50 flex-column shadow p-3 rounded shadow-none">
        <div class="d-flex justify-content-center align-items-center flex-column mb-5">
            <img src="/images/baby-head.png" alt="logo" class="img mb-3">
            <h1 class="h2 title">{{ __('Welcome') }}!</h1>
        </div>

        <div class="container w-75">
            <div class="row w-100">
                <div class="col col-md-4 d-flex justify-content-around align-items-center flex-column mb-5">
                    <p class="lead text-center">{{ __('Welcome_info') }}</p>
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <p class="text-center mb-2"><small>{{ __('Disclaimer')}}</small></p>
                        <div class="d-flex justify-content-center  m-0">
                            <div class="d-flex justify-content-center w-50">
                                <a href="/login" class="btn btn-primary" role="button">{{ __('Login') }}</a>
                            </div>
                            <div class="d-flex justify-content-center ">
                                <a href="/register" class="btn btn-outline-primary" role="button"> {{ __('Register') }} </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8 d-flex justify-content-center">
                    <img src="/images/gifts.svg" alt="gifts" class="imgWelcome">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="shape-divider">
    <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
        <path
            d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V0H0V27.35A600.21,600.21,0,0,0,321.39,56.44Z"
            class="shape-fill"></path>
    </svg>
</div>

@endsection
