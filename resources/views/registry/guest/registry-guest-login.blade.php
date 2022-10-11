@extends('layouts.app')

@section('content')
    
<form method="POST" action="{{ route('registry.checkPassword', ['url' => $url]) }}" class="container-fluid flex justify-content-center align-items-center flex-column">
    @csrf
    <img src="/images/guest.svg" alt="guest" class="imgGuest mt-5 mb-5">
        <label for="password" class="form-label text-center mb-3 title">
            {{ __('Password') }}
            <input type="password" class="form-control mt-3" id="password" name="password"></input>

        </label>
    <button role="button" class="btn btn-primary">{{__('Enter')}}</button>
    </form>

@endsection
