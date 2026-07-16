<?php

namespace App\Services;

use App\Models\Dealer;
use App\Models\User;
use App\Repositories\Interfaces\DealerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DealerService
{
    protected $dealerRepository;

    public function __construct(DealerRepositoryInterface $dealerRepository)
    {
        $this->dealerRepository = $dealerRepository;
    }

    public function getDealersForUser(User $user, bool $activeOnly = false): Collection
    {
        if ($user->role === 'admin') {
            return $this->dealerRepository->all($activeOnly);
        }

        return $this->dealerRepository->findForUser($user->id, $activeOnly);
    }

    public function getDealerDetails(User $user, int $id): Dealer
    {
        $dealer = $this->dealerRepository->findById($id);

        if (!$dealer) {
            throw new ModelNotFoundException('Dealer not found.');
        }

        if ($user->role !== 'admin' && $dealer->created_by !== $user->id) {
            throw new AuthorizationException('You are not authorized to view this dealer.');
        }

        return $dealer;
    }

    public function createDealer(User $user, array $data): Dealer
    {
        return Cache::lock('create_dealer_lock', 10)->block(5, function () use ($user, $data) {
            return DB::transaction(function () use ($user, $data) {
                $data['created_by'] = $user->id;
                $data['dealer_id'] = (string) Str::uuid();
                $data['dealer_code'] = $this->generateUniqueDealerCode();

                return $this->dealerRepository->create($data);
            });
        });
    }

    public function updateDealer(User $user, int $id, array $data): Dealer
    {
        $dealer = $this->dealerRepository->findById($id);

        if (!$dealer) {
            throw new ModelNotFoundException('Dealer not found.');
        }

        if ($user->role !== 'admin' && $dealer->created_by !== $user->id) {
            throw new AuthorizationException('Only admins or the owner can update this dealer.');
        }

        return DB::transaction(function () use ($dealer, $data) {
            return $this->dealerRepository->update($dealer, $data);
        });
    }

    public function deleteDealer(User $user, int $id): void
    {
        $dealer = $this->dealerRepository->findById($id);

        if (!$dealer) {
            throw new ModelNotFoundException('Dealer not found.');
        }

        if ($user->role !== 'admin' && $dealer->created_by !== $user->id) {
            throw new AuthorizationException('Only admins or the owner can delete this dealer.');
        }

        DB::transaction(function () use ($dealer) {
            $this->dealerRepository->delete($dealer);
        });
    }

     protected function generateUniqueDealerCode(): string
    {
        $lastCode = (int) Dealer::selectRaw('MAX(CAST(dealer_code AS UNSIGNED)) as max_code')->value('max_code');

        return (string) ($lastCode + 1);
    }
}
