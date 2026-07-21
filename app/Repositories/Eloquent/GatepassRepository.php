<?php

namespace App\Repositories\Eloquent;

use App\Models\Gatepass;
use App\Repositories\Interfaces\GatepassRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class GatepassRepository implements GatepassRepositoryInterface
{
    protected array $withRelations = [
        'branch',
        'dealer',
        'customer',
        'transporter',
        'vehicle',
        'sale',
        'purchase',
        'user',
        'details.stock',
        'details.unit',
        'details.alternateUnit'
    ];

    public function all(): Collection
    {
        return Gatepass::with($this->withRelations)->latest()->get();
    }

    public function findForUser(int $userId): Collection
    {
        return Gatepass::with($this->withRelations)
            ->where('created_by', $userId)
            ->latest()
            ->get();
    }

    public function findById(int $id): ?Gatepass
    {
        return Gatepass::with($this->withRelations)->find($id);
    }

    public function create(array $data): Gatepass
    {
        return Gatepass::create($data);
    }

    public function update(Gatepass $gatepass, array $data): Gatepass
    {
        $gatepass->update($data);
        return $gatepass;
    }

    public function delete(Gatepass $gatepass): bool
    {
        return $gatepass->delete();
    }
}
