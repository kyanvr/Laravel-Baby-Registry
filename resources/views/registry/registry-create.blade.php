@extends('layouts.app')

@section('content')

<a href="{{ route('dashboard') }}" class="btn btn-link ml-5"><span class="arrow-back">&#10554;</span> {{ __('Back') }}</a>
    
<form method="POST" action="{{ route('registry.create') }}" class="container-fluid pt-5 flex justify-content-center align-items-center flex-column">
    @csrf
    <div class="mb-3 w-50">
        <label for="name" class="form-label">{{ __('Name registry') }}</label>
        <input type="text" class="form-control rounded" id="name" name="name">
    </div>
    <div class="mb-3 w-50">
        <label for="babyname" class="form-label">{{ __('Name baby') }}</label>
        <input type="text" class="form-control rounded" id="babyname" name="babyname">
    </div>
    <div class="mb-3 w-50">
        <label for="password" class="form-label">{{ __('Password') }}</label>
        <input type="password" class="form-control rounded" id="password" name="password"></input>
    </div>
    <div class="mb-3 w-50">
        <label for="date" class="form-label">{{ __('Date of birth') }}</label>
        <input type="date" class="form-control rounded" id="date" name="date"></input>
    </div>
    <button role="button" class="btn btn-primary">{{__('Create')}}</button>
    </form>
@endsection
