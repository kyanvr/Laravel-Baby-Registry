@extends('layouts.app')

@section('content')

<a href="{{ url('dashboard') }}" class="btn btn-link ml-5">{{ __('Back') }}</a>

<div class="container w-75">
    @if (Auth::user()->hasRole('admin'))
    <div class="container-fluid">
        <div class="row">
            @foreach ($products as $product)
            <div class="card m-2" style="width: 12rem;">
                <img src="{{ url('storage/products/' . $product->image_internal) }}" class="card-img-top" alt="product">
                <div class="card-body">
                    <h5 class="card-title">{{ $product->title }}</h5>
                    <p class="card-text"> {{$product->price}} </p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
