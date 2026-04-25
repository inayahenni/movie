<?php

namespace App\Interfaces;

use App\Models\Movie;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MovieRepositoryInterface
{
    public function paginateLatest(?string $search = null, int $perPage = 6): LengthAwarePaginator;

    public function paginateForAdmin(int $perPage = 10): LengthAwarePaginator;

    public function findByIdOrFail(string $id): Movie;

    public function create(array $data): Movie;

    public function update(Movie $movie, array $data): bool;

    public function delete(Movie $movie): ?bool;
}
