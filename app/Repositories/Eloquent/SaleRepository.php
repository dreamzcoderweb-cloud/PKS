<?php

namespace App\Repositories\Eloquent;

use App\Models\Sale;
use App\Repositories\Interfaces\SaleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class SaleRepository implements SaleRepositoryInterface
{
    public function all(): Collection
    {
        return Sale::with(['branch', 'dealer', 'vehicle', 'user', 'details.stock', 'details.unit', 'details.alternateUnit'])->get();
    }

    public function findForUser(int $userId): Collection
    {
        return Sale::with(['branch', 'dealer', 'vehicle', 'user', 'details.stock', 'details.unit', 'details.alternateUnit'])
            ->where('created_by', $userId)
            ->get();
    }

    public function findById(int $id): ?Sale
    {
        return Sale::with(['branch', 'dealer', 'vehicle', 'user', 'details.stock', 'details.unit', 'details.alternateUnit'])->find($id);
    }

    public function create(array $data): Sale
    {
        return Sale::create($data);
    }

    public function update(Sale $sale, array $data): Sale
    {
        $sale->update($data);
        return $sale;
    }

    public function delete(Sale $sale): bool
    {
        return $sale->delete();
    }
}
