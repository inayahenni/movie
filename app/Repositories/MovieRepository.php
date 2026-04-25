<?php

namespace App\Repositories;

use App\Interfaces\MovieRepositoryInterface;
use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MovieRepository implements MovieRepositoryInterface
{
    public function paginateLatest(?string $search = null, int $perPage = 6): LengthAwarePaginator
    {
        return Movie::with('category')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('judul', 'like', '%' . $search . '%')
                        ->orWhere('sinopsis', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->paginate($perPage);
    }

    public function paginateForAdmin(int $perPage = 10): LengthAwarePaginator
    {
        return Movie::with('category')
            ->latest()
            ->paginate($perPage);
    }

    public function findByIdOrFail(string $id): Movie
    {
        return Movie::with('category')->findOrFail($id);
    }

    public function create(array $data): Movie
    {
        return Movie::create($data);
    }

    public function update(Movie $movie, array $data): bool
    {
        return $movie->update($data);
    }

    public function delete(Movie $movie): ?bool
    {
        return $movie->delete();
    }
}
