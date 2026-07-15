<?php

namespace App\Services;

use App\Models\Dealer;
use App\Models\User;
use App\Repositories\Interfaces\DealerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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

    public function getDealersForUser(User $user): Collection
    {
        if ($user->role === 'admin') {
            return $this->dealerRepository->all();
        }

        return $this->dealerRepository->findForUser($user->id);
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
        return DB::transaction(function () use ($user, $data) {
            $data['created_by'] = $user->id;
            $data['dealer_id'] = (string) Str::uuid();
            $data['dealer_code'] = $this->generateUniqueDealerCode($user);

            return $this->dealerRepository->create($data);
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
    $lastDealer = Dealer::orderBy('dealer_code', 'desc')->first();

    if ($lastDealer) {
        $nextNumber = ((int) $lastDealer->dealer_code) + 1;
    } else {
        $nextNumber = 1;
    }

    return (string) $nextNumber;
}
}
