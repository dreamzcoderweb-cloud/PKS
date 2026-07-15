<?php

namespace App\Repositories\Eloquent;

use App\Models\Dealer;
use App\Repositories\Interfaces\DealerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class DealerRepository implements DealerRepositoryInterface
{
    public function all(): Collection
    {
        return Dealer::with(['branch', 'user'])->get();
    }

    public function findForUser(int $userId): Collection
    {
        return Dealer::with(['branch', 'user'])->where('created_by', $userId)->get();
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
