@extends('layouts.app')

@section('content')
    
    <div class="container">
        <div class="row">
            <div class="col-sm-8 offset-sm-2">
                <h1 class="h1 pt-5">Scraper</h1>
                <form action="{{ route('scrape.categories') }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="shop"></label>
                        <select name="shop" id="shop" class="form-control">
                            @foreach ($shops as $key => $shop)
                                <option value="{{ $key }}">
                                    {{ $shop }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group py-3">
                        <div>
                            <label for="url">
                                Collection url
                            </label>
                            <input type="url" name="url" id="url" placeholder="e.g. http://bol.com/speelgoed" class="form-control rounded" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <button  class="btn btn-primary" type="submit">
                            {{ __('Scrape categories') }}!
                        </button>
                    </div>
                </form>

                <table class="table table-striped my-5">

                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->id }}</td>
                            <td>{{ $category->title }}</td>
                            <td>
                                <form action="{{ route('scrape.products') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="url" value="{{ $category->url }}">
                                    <input type="hidden" name="store_id" value="{{ $category->store_id }}">
                                    <input type="hidden" name="category_id" value="{{ $category->id }}">
                                    
                                    <button type="submit" class="btn btn-warning">{{ __('Scrape articles') }}!</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                </table>

                <form action="{{ route('scrape.images') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary mb-5">{{ __('Download images') }}</button>
                </form>
            </div>
        </div>
    </div>

@endsection