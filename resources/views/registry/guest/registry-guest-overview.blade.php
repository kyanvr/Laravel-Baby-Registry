@extends('layouts.app')

@section('content')

<div class="container w-100 mt-5">
    
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
                <img src="/images/baby-illustration.svg" alt="illustration" class="imgWelcome">
                
            </div>
            <div class="col col-md-8 d-flex flex-wrap justify-content-center">
                @foreach ($products as $product)
                <div class="card m-2" style="width: 12rem;">
                    <img src="{{ $product['image_src'] }}" class="card-img-top" alt="product">
                    <div class="card-body d-flex justify-content-between align-items-center flex-column">
                        <h5 class="card-title"><strong>{{ $product['title'] }}</strong></h5>
                        <p class="card-text">&#8364;{{$product['price']}}</p>
                        @if ($product['status'] !== 1)
                            <form method="POST" action="{{ route('registry.storeGuest', ['url' => $url]) }}">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product['product_id'] }}">
                                <button type="submit" class="btn btn-primary">{{ __('Buy this product') }}</button>
                            </form>
                        @else
                            <button class="btn btn-warning disabled">{{ __('Bought') }}!</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>

</div>
@endsection
