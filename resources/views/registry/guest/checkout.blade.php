@extends('layouts.app')

@section('content')

<div class="container w-50">
    <h1 class="h1 title text-center mt-5">{{ __('Checkout overview') }}</h1>
    <table class="table table-striped">
        @if ($cart->getContent()->count() > 0)
        <thead>
            <tr>
                <th scope="col">{{ __('Product') }}</th>
                <th scope="col">{{ __('Price') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cart->getContent() as $item)
            <tr>
                <td class="mt-3">
                    <p>{{ $item->name }}</p>
                </td>
                <td class="mt-3">
                    <p>&#8364;{{ $item->price }}</p>
                </td>
            </tr>
            @endforeach

        </tbody>
        @endif

    </table>

    <div class="btn btn-primary disabled mb-5">
        {{ __('Total') }} : &#8364;{{ $cart->getTotal() }}
    </div>

    <h2 class="lead text-center mb-2">{{ __('Let them know who bought it and give them a personalised message') }}</h2>
    <form action="{{ route('checkout') }}" method="POST" class="form-group d-flex flex-column">
        @csrf
        <label for="name" class="form-label">
            {{ __('Name') }}
            <input type="text" name="name" class="form-control rounded" required>
        </label>
        <label for="remarks" class="form-label">
            {{ __('Special message') }}
            <textarea type="text" name="remarks" class="form-control rounded"></textarea>
        </label>

        <button class="btn btn-primary mb-5 mt-2" type="submit">{{ __('Checkout') }}</button>
    </form>
</div>

@endsection
