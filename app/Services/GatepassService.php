<?php

namespace App\Services;

use App\Models\Gatepass;
use App\Models\User;
use App\Repositories\Interfaces\GatepassRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GatepassService
{
    protected $gatepassRepository;

    public function __construct(GatepassRepositoryInterface $gatepassRepository)
    {
        $this->gatepassRepository = $gatepassRepository;
    }

    /**
     * Retrieve all gatepasses depending on user role.
     */
    public function getGatepassesForUser(User $user): Collection
    {
        if ($user->role === 'admin') {
            return $this->gatepassRepository->all();
        }

        return $this->gatepassRepository->findForUser($user->id);
    }

    /**
     * Retrieve a specific gatepass if authorized.
     */
    public function getGatepassDetails(User $user, int $id): Gatepass
    {
        $gatepass = $this->gatepassRepository->findById($id);

        if (!$gatepass) {
            throw new ModelNotFoundException('Gatepass not found.');
        }

        if ($user->role !== 'admin' && $gatepass->created_by !== $user->id) {
            throw new AuthorizationException('You are not authorized to view this gatepass.');
        }

        return $gatepass;
    }

    /**
     * Create a gatepass with its details and images.
     */
    public function createGatepass(User $user, array $data): Gatepass
    {
        $uploadedImages = [];
        try {
            return DB::transaction(function () use ($user, $data, &$uploadedImages) {
                // 1. Process and upload images
                if (isset($data['gatepass_images'])) {
                    $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('gatepasses') : public_path('gatepasses');
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    foreach ($data['gatepass_images'] as $image) {
                        $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $image->move($targetDir, $filename);
                        $uploadedImages[] = 'gatepasses/' . $filename;
                    }
                }

                // Auto generate gatepass number if not provided
                $gatepassNumber = $data['gatepass_number'] ?? 'GP-' . date('Ymd') . '-' . strtoupper(Str::random(5));

                // 2. Create Gatepass Master record
                $masterData = [
                    'gatepass_number' => $gatepassNumber,
                    'gatepass_type' => $data['gatepass_type'] ?? 'outward',
                    'movement_type' => $data['movement_type'] ?? 'sale',
                    'branch_id' => $data['branch_id'],
                    'dealer_id' => $data['dealer_id'] ?? null,
                    'customer_id' => $data['customer_id'] ?? null,
                    'sale_id' => $data['sale_id'] ?? null,
                    'purchase_id' => $data['purchase_id'] ?? null,
                    'transporter_id' => $data['transporter_id'] ?? null,
                    'vehicle_id' => $data['vehicle_id'] ?? null,
                    'driver_name' => $data['driver_name'] ?? null,
                    'driver_number' => $data['driver_number'] ?? null,
                    'gatepass_date' => $data['gatepass_date'],
                    'remarks' => $data['remarks'] ?? null,
                    'status' => $data['status'] ?? 'pending',
                    'gatepass_images' => $uploadedImages,
                    'created_by' => $user->id,
                ];

                $gatepass = $this->gatepassRepository->create($masterData);

                // 3. Process Gatepass Details
                if (isset($data['details'])) {
                    foreach ($data['details'] as $detail) {
                        $gatepass->details()->create([
                            'stock_id' => $detail['stock_id'],
                            'lot_number' => $detail['lot_number'] ?? null,
                            'unit_value' => $detail['unit_value'],
                            'unit_id' => $detail['unit_id'],
                            'alternate_unit_value' => $detail['alternate_unit_value'] ?? null,
                            'alternate_unit_id' => $detail['alternate_unit_id'] ?? null,
                            'remarks' => $detail['remarks'] ?? null,
                        ]);
                    }
                }

                return $gatepass->load([
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
                ]);
            });
        } catch (\Exception $e) {
            // Cleanup newly uploaded images on transaction rollback
            foreach ($uploadedImages as $image) {
                if (app()->runningUnitTests()) {
                    Storage::disk('public')->delete($image);
                } else {
                    $filePath = public_path($image);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }
            throw $e;
        }
    }

    /**
     * Update a gatepass (Admin only).
     */
    public function updateGatepass(User $user, int $id, array $data): Gatepass
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException('Only admins are authorized to update gatepasses.');
        }

        $gatepass = $this->gatepassRepository->findById($id);

        if (!$gatepass) {
            throw new ModelNotFoundException('Gatepass not found.');
        }

        $newUploadedImages = [];
        $oldImages = $gatepass->gatepass_images ?? [];

        try {
            return DB::transaction(function () use ($gatepass, $data, &$newUploadedImages, $oldImages) {
                // Delete existing details
                $gatepass->details()->delete();

                // Process image updates if provided
                $gatepassImages = $oldImages;
                if (isset($data['gatepass_images'])) {
                    $targetDir = app()->runningUnitTests() ? Storage::disk('public')->path('gatepasses') : public_path('gatepasses');
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0755, true);
                    }

                    $newImagesList = [];
                    foreach ($data['gatepass_images'] as $image) {
                        $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                        $image->move($targetDir, $filename);
                        $newImagesList[] = 'gatepasses/' . $filename;
                        $newUploadedImages[] = 'gatepasses/' . $filename;
                    }
                    $gatepassImages = $newImagesList;
                }

                // Update Master record
                $masterData = [
                    'gatepass_type' => $data['gatepass_type'] ?? $gatepass->gatepass_type,
                    'movement_type' => $data['movement_type'] ?? $gatepass->movement_type,
                    'branch_id' => $data['branch_id'],
                    'dealer_id' => $data['dealer_id'] ?? null,
                    'customer_id' => $data['customer_id'] ?? null,
                    'sale_id' => $data['sale_id'] ?? null,
                    'purchase_id' => $data['purchase_id'] ?? null,
                    'transporter_id' => $data['transporter_id'] ?? null,
                    'vehicle_id' => $data['vehicle_id'] ?? null,
                    'driver_name' => $data['driver_name'] ?? null,
                    'driver_number' => $data['driver_number'] ?? null,
                    'gatepass_date' => $data['gatepass_date'],
                    'remarks' => $data['remarks'] ?? null,
                    'status' => $data['status'] ?? $gatepass->status,
                    'gatepass_images' => $gatepassImages,
                ];

                if (isset($data['gatepass_number'])) {
                    $masterData['gatepass_number'] = $data['gatepass_number'];
                }

                $gatepass = $this->gatepassRepository->update($gatepass, $masterData);

                // Save new details
                if (isset($data['details'])) {
                    foreach ($data['details'] as $detail) {
                        $gatepass->details()->create([
                            'stock_id' => $detail['stock_id'],
                            'lot_number' => $detail['lot_number'] ?? null,
                            'unit_value' => $detail['unit_value'],
                            'unit_id' => $detail['unit_id'],
                            'alternate_unit_value' => $detail['alternate_unit_value'] ?? null,
                            'alternate_unit_id' => $detail['alternate_unit_id'] ?? null,
                            'remarks' => $detail['remarks'] ?? null,
                        ]);
                    }
                }

                // If images were updated, delete old images after transaction succeeds
                if (isset($data['gatepass_images'])) {
                    foreach ($oldImages as $oldImage) {
                        if (app()->runningUnitTests()) {
                            Storage::disk('public')->delete($oldImage);
                        } else {
                            $oldPath = public_path($oldImage);
                            if (file_exists($oldPath)) {
                                @unlink($oldPath);
                            }
                        }
                    }
                }

                return $gatepass->load([
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
                ]);
            });
        } catch (\Exception $e) {
            foreach ($newUploadedImages as $image) {
                if (app()->runningUnitTests()) {
                    Storage::disk('public')->delete($image);
                } else {
                    $filePath = public_path($image);
                    if (file_exists($filePath)) {
                        @unlink($filePath);
                    }
                }
            }
            throw $e;
        }
    }

    /**
     * Delete a gatepass.
     */
    public function deleteGatepass(User $user, int $id, bool $force = false): void
    {
        $gatepass = $this->gatepassRepository->findById($id);

        if (!$gatepass) {
            throw new ModelNotFoundException('Gatepass not found.');
        }

        if ($user->role !== 'admin' && $gatepass->created_by !== $user->id) {
            throw new AuthorizationException('You are not authorized to delete this gatepass.');
        }

        DB::transaction(function () use ($gatepass, $force) {
            if ($force) {
                foreach ($gatepass->gatepass_images ?? [] as $image) {
                    if (app()->runningUnitTests()) {
                        Storage::disk('public')->delete($image);
                    } else {
                        $filePath = public_path($image);
                        if (file_exists($filePath)) {
                            @unlink($filePath);
                        }
                    }
                }

                $gatepass->details()->delete();
                $gatepass->forceDelete();
            } else {
                $gatepass->delete();
            }
        });
    }

    /**
     * Update status of a gatepass (Admin only).
     */
    public function updateStatus(User $user, int $id, string $status): Gatepass
    {
        if ($user->role !== 'admin') {
            throw new AuthorizationException('Only admins are authorized to update gatepass status.');
        }

        $gatepass = $this->gatepassRepository->findById($id);

        if (!$gatepass) {
            throw new ModelNotFoundException('Gatepass not found.');
        }

        $gatepass = $this->gatepassRepository->update($gatepass, ['status' => $status]);

        return $gatepass;
    }
}
