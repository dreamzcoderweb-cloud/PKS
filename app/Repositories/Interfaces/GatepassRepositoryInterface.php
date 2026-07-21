<?php

namespace App\Repositories\Interfaces;

use App\Models\Gatepass;
use Illuminate\Database\Eloquent\Collection;

interface GatepassRepositoryInterface
{
    public function all(): Collection;
    public function findForUser(int $userId): Collection;
    public function findById(int $id): ?Gatepass;
    public function create(array $data): Gatepass;
    public function update(Gatepass $gatepass, array $data): Gatepass;
    public function delete(Gatepass $gatepass): bool;
}
