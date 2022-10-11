@extends('layouts.app')

@section('content')

<div class="container w-100 mt-5">
    @auth
        
    <a href="{{ route('dashboard') }}" class="btn btn-link ml-5"><span class="arrow-back">&#10554;</span>
    {{ __('Back') }}</a>
        <div class="container-fluid">
        <div class="row w-100 m-0">
            <div class="sidebar col col-md-3 mr-5 d-flex flex-column justify-content-between align-items-center">
                <div class="registryInfo text-center">
                    <h2 class="h2 info-color mt-3 title">{{ $registry->name }}</h2>
                    <p class="mt-3">{{ __('Name baby') }}:</p>
                    <p class="h4 info-color">{{ $registry->baby_name }}</p>
                    <p class="mt-3">{{ __('Date of birth') }}:</p>
                    <p class="info-color">{{ $registry->birthdate }}</p>
                </div>
                <button id="modalBtn" class="btn btn-primary">{{ __('Share') }}</button>
                <img src="/images/baby-illustration.svg" alt="illustration" class="imgWelcome">
                
                {{-- Modal --}}
                <div id="modal" class="modal">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h4 class="h4 title mb-5">{{ __('Share your registry with your family and friends') }}!</h4>
                        <p class="mb-5">{{ __('share_message') }}</p>
                        <p>{{ __('Personal link') }}:</p>
                        <p class="mb-2"><strong>{{ env('APP_REGISTRY_URL') }}{{ $registry->url }}/login</strong></p>
                        <p>{{ __('Password') }}:</p>
                        <p><strong>{{ $registry->password }}</strong></p>
                    </div>

                </div>
            </div>
            <div class="col col-md-8 d-flex flex-wrap justify-content-center">
                @if (count($products) > 0)
                @foreach ($products as $product)
                <div class="card m-2" style="width: 12rem;">
                    <img src="{{ $product['image_src'] }}" class="card-img-top" alt="product">
                    <div class="card-body d-flex justify-content-between align-items-center flex-column">
                        <h5 class="card-title"><strong>{{ $product['title'] }}</strong></h5>
                        <p class="card-text">&#8364;{{$product['price']}}</p>
                        @if ($product['status'] !== 0)
                        <button type="button" class="btn btn-primary" disabled>{{ __('Bought') }}!</button>
                        @endif
                    </div>
                </div>
                @endforeach
                @else
                <p>{{ __('No products yet') }}</p>
                @endif
            </div>
        </div>

    </div>
    @endauth

</div>
@endsection
