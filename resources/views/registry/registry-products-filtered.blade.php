@extends('layouts.app')

@section('content')

<a href="{{ route('dashboard') }}" class="btn btn-link ml-5"><span class="arrow-back">&#10554;</span> {{ __('Back') }}</a>

<div class="container w-100 mt-5">
    @if (Auth::user()->hasRole('user'))
    <div class="container-fluid">
        <div class="row w-100">
            <div class="col-3">
                <form method="POST" action="{{ route('registry.filter')}}" class="list-group">
                    @csrf
                    <label class="h5">{{ __('Categories') }}</label>
                    @foreach ($categories as $category)
                    @if ($category->id == $category_id)
                    <label for="category_id" class="list-group-item list-group-item-action active" aria-current="true">
                        <input type="hidden" name="registry_id" value="{{ $registry->id }}">
                        <input type="radio" name="category_id" value="{{ $category->id }}">
                        {{ ucfirst($category->title) }}
                    </label>
                    @else
                    <label for="category_id" class="list-group-item list-group-item-action">
                        <input type="hidden" name="registry_id" value="{{ $registry->id }}">
                        <input type="radio" name="category_id" value="{{ $category->id }}">
                        {{ ucfirst($category->title) }}
                    </label>
                    @endif
                    @endforeach
                    <button class="btn btn-outline-primary mt-3">{{ __('Filter') }}</button>
                </form>
            </div>

            <div class="col-8 d-flex flex-wrap justify-content-center">

                @foreach ($products as $product)
                <form method="POST" action="{{ route('registry.add') }}" class="card m-2" style="width: 12rem;">
                    @csrf
                    <img src="{{ $product->image_src }}" class="card-img-top" alt="product">
                    <div class="card-body d-flex justify-content-between align-items-center flex-column">
                        <h5 class="card-title"><strong>{{ $product->title }}</strong></h5>
                        <p class="card-text">&#8364; {{$product->price}} </p>
                        {{-- <input type="hidden" name="registry_id" value="{{ $registry->id }}"> --}}
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="product_id" value="{{ $product->category_id }}">
                        <button class="btn btn-primary">{{ __('Add') }}</button>
                    </div>
                </form>
                @endforeach
            </div>
        </div>

        <div class="w-50">
            {{ $products->onEachSide(5)->links() }}
        </div>



    </div>
    @endif
</div>
@endsection
