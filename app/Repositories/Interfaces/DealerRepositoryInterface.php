<?php

namespace App\Repositories\Interfaces;

use App\Models\Dealer;
use Illuminate\Database\Eloquent\Collection;

interface DealerRepositoryInterface
{
    public function all(bool $activeOnly = false, ?string $search = null): Collection;
    public function findForUser(int $userId, bool $activeOnly = false, ?string $search = null): Collection;
    public function findByBranch(int $branchId, bool $activeOnly = false, ?string $search = null): Collection;
    public function findById(int $id): ?Dealer;
    public function create(array $data): Dealer;
    public function update(Dealer $dealer, array $data): Dealer;
    public function delete(Dealer $dealer): bool;
}
