<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMovieRequest;
use App\Http\Requests\UpdateMovieRequest;
use App\Services\MovieService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MovieController extends Controller
{
    public function __construct(protected MovieService $movieService) {}

    public function index(Request $request): View
    {
        $movies = $this->movieService->getHomepageMovies($request->query('search'));

        return view('homepage', compact('movies'));
    }

    public function show(string $id): View
    {
        $movie = $this->movieService->getMovieDetail($id);

        return view('detail', compact('movie'));
    }

    public function create(): View
    {
        $categories = $this->movieService->getCategories();

        return view('input', compact('categories'));
    }

    public function store(StoreMovieRequest $request): RedirectResponse
    {
        $this->movieService->createMovie($request->validated());

        return redirect()->route('movies.index')
            ->with('success', 'Film berhasil ditambahkan.');
    }

    public function data(): View
    {
        $movies = $this->movieService->getAdminMovies();

        return view('data-movies', compact('movies'));
    }

    public function edit(string $id): View
    {
        $movie = $this->movieService->getMovieDetail($id);
        $categories = $this->movieService->getCategories();

        return view('form-edit', compact('movie', 'categories'));
    }

    public function update(UpdateMovieRequest $request, string $id): RedirectResponse
    {
        $this->movieService->updateMovie($id, $request->validated());

        return redirect()->route('movies.data')
            ->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $this->movieService->deleteMovie($id);

        return redirect()->route('movies.data')
            ->with('success', 'Data berhasil dihapus.');
    }
}
