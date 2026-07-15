<?php

namespace App\Repositories\Eloquent;

use App\Models\Purchase;
use App\Repositories\Interfaces\PurchaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PurchaseRepository implements PurchaseRepositoryInterface
{
    public function all(): Collection
    {
        return Purchase::with(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details'])->get();
    }

    public function findForUser(int $userId): Collection
    {
        return Purchase::with(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details'])
            ->where('created_by', $userId)
            ->get();
    }

    public function findById(int $id): ?Purchase
    {
        return Purchase::with(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details'])->find($id);
    }

    public function create(array $data): Purchase
    {
        return Purchase::create($data);
    }

    public function update(Purchase $purchase, array $data): Purchase
    {
        $purchase->update($data);
        return $purchase;
    }

    public function delete(Purchase $purchase): bool
    {
        return $purchase->delete();
    }
}
