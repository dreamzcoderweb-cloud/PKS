<?php

namespace App\Repositories\Interfaces;

use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;

interface SaleRepositoryInterface
{
    public function all(): Collection;
    public function findForUser(int $userId): Collection;
    public function findById(int $id): ?Sale;
    public function create(array $data): Sale;
    public function update(Sale $sale, array $data): Sale;
    public function delete(Sale $sale): bool;
}
