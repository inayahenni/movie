<?php

namespace App\Services;

use App\Interfaces\CategoryRepositoryInterface;
use App\Interfaces\MovieRepositoryInterface;
use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MovieService
{
    public function __construct(
        protected MovieRepositoryInterface $movieRepository,
        protected CategoryRepositoryInterface $categoryRepository
    ) {}

    public function getHomepageMovies(?string $search = null): LengthAwarePaginator
    {
        return $this->movieRepository->paginateLatest($search);
    }

    public function getAdminMovies(): LengthAwarePaginator
    {
        return $this->movieRepository->paginateForAdmin();
    }

    public function getMovieDetail(string $id): Movie
    {
        return $this->movieRepository->findByIdOrFail($id);
    }

    public function getCategories(): Collection
    {
        return $this->categoryRepository->getAll();
    }

    public function createMovie(array $validatedData): Movie
    {
        if (isset($validatedData['foto_sampul']) && $validatedData['foto_sampul'] instanceof UploadedFile) {
            $validatedData['foto_sampul'] = $this->storeCover($validatedData['foto_sampul']);
        }

        return $this->movieRepository->create($validatedData);
    }

    public function updateMovie(string $id, array $validatedData): bool
    {
        $movie = $this->movieRepository->findByIdOrFail($id);

        if (isset($validatedData['foto_sampul']) && $validatedData['foto_sampul'] instanceof UploadedFile) {
            $this->deleteCover($movie->foto_sampul);
            $validatedData['foto_sampul'] = $this->storeCover($validatedData['foto_sampul']);
        } else {
            unset($validatedData['foto_sampul']);
        }

        return $this->movieRepository->update($movie, $validatedData);
    }

    public function deleteMovie(string $id): ?bool
    {
        $movie = $this->movieRepository->findByIdOrFail($id);
        $this->deleteCover($movie->foto_sampul);

        return $this->movieRepository->delete($movie);
    }

    protected function storeCover(UploadedFile $file): string
    {
        $fileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $fileName);

        return $fileName;
    }

    protected function deleteCover(?string $fileName): void
    {
        if (!$fileName) {
            return;
        }

        $filePath = public_path('images/' . $fileName);

        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }
}
