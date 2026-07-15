<?php

namespace App\Repositories\Interfaces;

use App\Models\Purchase;
use Illuminate\Database\Eloquent\Collection;

interface PurchaseRepositoryInterface
{
    public function all(): Collection;
    public function findForUser(int $userId): Collection;
    public function findById(int $id): ?Purchase;
    public function create(array $data): Purchase;
    public function update(Purchase $purchase, array $data): Purchase;
    public function delete(Purchase $purchase): bool;
}
