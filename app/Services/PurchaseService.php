<?php

namespace App\Services;

use App\Models\Purchase;
use App\Models\User;
use App\Repositories\Interfaces\PurchaseRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PurchaseService
{
    protected $purchaseRepository;

    public function __construct(PurchaseRepositoryInterface $purchaseRepository)
    {
        $this->purchaseRepository = $purchaseRepository;
    }

    /**
     * Retrieve all purchases depending on user role.
     */
    public function getPurchasesForUser($user): Collection
    {
        if ($user->role === 'admin') {
            return $this->purchaseRepository->all();
        }

        return $this->purchaseRepository->findForUser($user->getOwnerId());
    }

    /**
     * Retrieve a specific purchase if authorized.
     */
    public function getPurchaseDetails($user, int $id): Purchase
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            throw new ModelNotFoundException('Purchase not found.');
        }

        if ($user->role !== 'admin' && (int)$purchase->created_by !== (int)$user->getOwnerId()) {
            throw new AuthorizationException('You are not authorized to view this purchase.');
        }

        return $purchase;
    }

    /**
     * Create a purchase with its details and images.
     */
    public function createPurchase($user, array $data): Purchase
    {
        $data['branch_id'] = $data['branch_id'] ?? $user->branch_id;
        return DB::transaction(function () use ($user, $data) {
            $storedImages = [];
            if (isset($data['purchase_images'])) {
                $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('purchases') : public_path('purchases');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                foreach ($data['purchase_images'] as $image) {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($targetDir, $filename);
                    $storedImages[] = 'purchases/' . $filename;
                }
            }

            $purchaseData = [
                'purchase_id' => (string) Str::uuid(),
                'branch_id' => $data['branch_id'],
                'dealer_id' => $data['dealer_id'],
                'lot_number' => $data['lot_number'],
                'transporter_id' => $data['transporter_id'],
                'vehicle_id' => $data['vehicle_id'],
                'driver_number' => $data['driver_number'],
                'purchase_images' => $storedImages,
                'created_by' => $user->getOwnerId(),
            ];

            $purchase = $this->purchaseRepository->create($purchaseData);

            if (isset($data['details'])) {
                $purchase->details()->createMany($data['details']);
            }

            return $purchase->load(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details']);
        });
    }

    /**
     * Update a purchase (Admin only).
     */
    public function updatePurchase($user, int $id, array $data): Purchase
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException('Only admins are authorized to update purchases.');
        }

        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            throw new ModelNotFoundException('Purchase not found.');
        }

        return DB::transaction(function () use ($purchase, $data) {
            $purchaseData = [
                'branch_id' => $data['branch_id'],
                'dealer_id' => $data['dealer_id'],
                'lot_number' => $data['lot_number'],
                'transporter_id' => $data['transporter_id'],
                'vehicle_id' => $data['vehicle_id'],
                'driver_number' => $data['driver_number'],
            ];

            // Handle images update if provided
            if (isset($data['purchase_images'])) {
                // Delete old images
                foreach ($purchase->purchase_images ?? [] as $oldImage) {
                    if (app()->runningUnitTests()) {
                        Storage::disk('public')->delete($oldImage);
                    } else {
                        $oldPath = public_path($oldImage);
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                }

                $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('purchases') : public_path('purchases');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $storedImages = [];
                foreach ($data['purchase_images'] as $image) {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($targetDir, $filename);
                    $storedImages[] = 'purchases/' . $filename;
                }
                $purchaseData['purchase_images'] = $storedImages;
            }

            $purchase = $this->purchaseRepository->update($purchase, $purchaseData);

            // Re-sync details: delete old, create new
            if (isset($data['details'])) {
                $purchase->details()->delete();
                $purchase->details()->createMany($data['details']);
            }

            return $purchase->load(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details']);
        });
    }

    /**
     * Delete a purchase.
     */
    public function deletePurchase($user, int $id): void
    {
        $purchase = $this->purchaseRepository->findById($id);

        if (!$purchase) {
            throw new ModelNotFoundException('Purchase not found.');
        }

        if ($user->role !== 'admin' && (int)$purchase->created_by !== (int)$user->getOwnerId()) {
            throw new AuthorizationException('You are not authorized to delete this purchase.');
        }

        DB::transaction(function () use ($purchase) {
            // Delete images from disk
            foreach ($purchase->purchase_images ?? [] as $image) {
                if (app()->runningUnitTests()) {
                    Storage::disk('public')->delete($image);
                } else {
                    $filePath = public_path($image);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            $purchase->details()->delete();
            $this->purchaseRepository->delete($purchase);
        });
    }
}
