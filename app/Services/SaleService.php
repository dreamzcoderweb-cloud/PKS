<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\User;
use App\Repositories\Interfaces\SaleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class SaleService
{
    protected $saleRepository;

    public function __construct(SaleRepositoryInterface $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    /**
     * Retrieve all sales depending on user role.
     */
    public function getSalesForUser(User $user): Collection
    {
        if ($user->role === 'admin') {
            return $this->saleRepository->all();
        }

        return $this->saleRepository->findForUser($user->id);
    }

    /**
     * Retrieve a specific sale if authorized.
     */
    public function getSaleDetails(User $user, int $id): Sale
    {
        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            throw new ModelNotFoundException('Sale not found.');
        }

        if ($user->role !== 'admin' && $sale->created_by !== $user->id) {
            throw new AuthorizationException('You are not authorized to view this sale.');
        }

        return $sale;
    }

    /**
     * Create a sale with its details and images.
     */
    public function createSale(User $user, array $data): Sale
    {
        return DB::transaction(function () use ($user, $data) {
            $storedImages = [];
            if (isset($data['sale_images'])) {
                $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('sales') : public_path('sales');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                foreach ($data['sale_images'] as $image) {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($targetDir, $filename);
                    $storedImages[] = 'sales/' . $filename;
                }
            }

            $saleData = [
                'sale_id' => (string) Str::uuid(),
                'branch_id' => $data['branch_id'],
                'dealer_id' => $data['dealer_id'],
                'lot_number' => $data['lot_number'],
                'transporter_id' => $data['transporter_id'],
                'vehicle_id' => $data['vehicle_id'],
                'invoice_number' => $data['invoice_number'],
                'driver_number' => $data['driver_number'],
                'sale_images' => $storedImages,
                'created_by' => $user->id,
            ];

            $sale = $this->saleRepository->create($saleData);

            if (isset($data['details'])) {
                $sale->details()->createMany($data['details']);
            }

            return $sale->load(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details']);
        });
    }

    /**
     * Update a sale (Admin only).
     */
    public function updateSale(User $user, int $id, array $data): Sale
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException('Only admins are authorized to update sales.');
        }

        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            throw new ModelNotFoundException('Sale not found.');
        }

        return DB::transaction(function () use ($sale, $data) {
            $saleData = [
                'branch_id' => $data['branch_id'],
                'dealer_id' => $data['dealer_id'],
                'lot_number' => $data['lot_number'],
                'transporter_id' => $data['transporter_id'],
                'vehicle_id' => $data['vehicle_id'],
                'invoice_number' => $data['invoice_number'],
                'driver_number' => $data['driver_number'],
            ];

            // Handle images update if provided
            if (isset($data['sale_images'])) {
                // Delete old images
                foreach ($sale->sale_images ?? [] as $oldImage) {
                    if (app()->runningUnitTests()) {
                        Storage::disk('public')->delete($oldImage);
                    } else {
                        $oldPath = public_path($oldImage);
                        if (file_exists($oldPath)) {
                            @unlink($oldPath);
                        }
                    }
                }

                $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('sales') : public_path('sales');
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }

                $storedImages = [];
                foreach ($data['sale_images'] as $image) {
                    $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                    $image->move($targetDir, $filename);
                    $storedImages[] = 'sales/' . $filename;
                }
                $saleData['sale_images'] = $storedImages;
            }

            $sale = $this->saleRepository->update($sale, $saleData);

            // Re-sync details: delete old, create new
            if (isset($data['details'])) {
                $sale->details()->delete();
                $sale->details()->createMany($data['details']);
            }

            return $sale->load(['branch', 'dealer', 'transporter', 'vehicle', 'user', 'details']);
        });
    }

    /**
     * Delete a sale.
     */
    public function deleteSale(User $user, int $id): void
    {
        $sale = $this->saleRepository->findById($id);

        if (!$sale) {
            throw new ModelNotFoundException('Sale not found.');
        }

        if ($user->role !== 'admin' && $sale->created_by !== $user->id) {
            throw new AuthorizationException('You are not authorized to delete this sale.');
        }

        DB::transaction(function () use ($sale) {
            // Delete images from disk
            foreach ($sale->sale_images ?? [] as $image) {
                if (app()->runningUnitTests()) {
                    Storage::disk('public')->delete($image);
                } else {
                    $filePath = public_path($image);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }

            $sale->details()->delete();
            $this->saleRepository->delete($sale);
        });
    }
}
