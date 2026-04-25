@extends('layout.template')
@section('title', 'Input Data Movie')
@section('content')
    <h2 class="mb-4">Edit Movie</h2>
    <form action="{{ route('movies.update', ['id' => $movie->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('partials.movie-form-fields')
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
@endsection
