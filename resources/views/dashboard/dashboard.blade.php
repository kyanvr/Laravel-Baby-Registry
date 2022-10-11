@extends('layouts.app')

@section('content')

<div class="container w-75">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 d-flex justify-content-between align-items-center">
                    {{ __('Welcome back') }}, {{auth()->user()->name}}!
                    <img src="/images/welcome.svg" alt="welcome" class="img">
                </div>
            </div>
        </div>
    </div>

    @if (Auth::user()->hasRole('admin'))
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card text-center border-primary vh-50">
                    <div class="card-body">
                        <a href="/dashboard/categories" class="btn btn-link">{{ __('Categories') }}</a>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card text-center border-primary">
                    <div class="card-body">
                        <a href="/dashboard/products" class="btn btn-link">{{ __('Products') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if (Auth::user()->hasRole('user'))
    <h3 class="h3 title">{{ __('Your registries') }}</h3>
    <table class="table table-striped">

        @if ($registries->count() > 0)
        <thead>
            <tr>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Registry link') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($registries as $registry)
            <tr>
                <td class="mt-3">
                    <p>{{ $registry->name }}</p>
                </td>
                <td class="mt-3">
                    <a href="{{ route('registry.showSpecific', ['url' => $registry->url]) }}"
                        class="link">{{ $registry->url }}</a>
                </td>
            </tr>
            @endforeach
        </tbody>
        @else
        <p class="mt-3">{{ __('No registries yet') }}</p>
        @endif

    </table>

    <a role="button" href="{{ route('registry.index') }}" class="btn btn-primary">{{ __('Create a registry') }}</a>
    @endif

</div>
@endsection
