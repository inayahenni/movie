@extends('layout.template')
@section('title', 'Input Data Movie')
@section('content')
    <a href="/movies/data" class="btn btn-primary mt-4">List Movie</a>
    <h2 class="mb-4">Tambah Movie Baru</h2>
    <form action="/movies" method="POST" enctype="multipart/form-data">
        @csrf
        @include('partials.movie-form-fields')
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
@endsection
