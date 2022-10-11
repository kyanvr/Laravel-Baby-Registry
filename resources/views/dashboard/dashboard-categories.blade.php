@extends('layouts.app')

@section('content')

<a href="{{ url('dashboard') }}" class="btn btn-link ml-5">{{ __('Back') }}</a>

<div class="container w-75">
    @if (Auth::user()->hasRole('admin'))
    <div class="container-fluid">
        <table class="table table-striped mt-3">
        @foreach ($categories as $category)
            
            <tr>
                <td>{{ $category->title }}</td>
            </tr>
            
        @endforeach
        </table>
    </div>
    @endif
</div>
@endsection
