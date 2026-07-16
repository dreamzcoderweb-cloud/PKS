<?php

namespace App\Repositories\Eloquent;

use App\Models\Dealer;
use App\Repositories\Interfaces\DealerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DealerRepository implements DealerRepositoryInterface
{
    public function all(bool $activeOnly = false): Collection
    {
        $query = Dealer::with(['branch', 'user']);
        if ($activeOnly) {
            $query->active();
        }
        return $query->get();
    }

    public function findForUser(int $userId, bool $activeOnly = false): Collection
    {
        $query = Dealer::with(['branch', 'user'])->where('created_by', $userId);
        if ($activeOnly) {
            $query->active();
        }
        return $query->get();
    }

    public function findById(int $id): ?Dealer
    {
        return Dealer::find($id);
    }

    public function create(array $data): Dealer
    {
        return Dealer::create($data);
    }

    public function update(Dealer $dealer, array $data): Dealer
    {
        $dealer->update($data);
        return $dealer;
    }

    public function delete(Dealer $dealer): bool
    {
        return $dealer->delete();
    }
}
