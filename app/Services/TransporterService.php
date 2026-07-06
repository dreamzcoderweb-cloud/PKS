<?php

namespace App\Services;

use App\Models\Transporter;
use App\Repositories\Interfaces\TransporterRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TransporterService
{
    protected $transporterRepository;

    public function __construct(TransporterRepositoryInterface $transporterRepository)
    {
        $this->transporterRepository = $transporterRepository;
    }

    /**
     * Get all transporters.
     *
     * @return Collection
     */
    public function getAllTransporters(): Collection
    {
        return $this->transporterRepository->all();
    }

    /**
     * Get details of a transporter.
     *
     * @param int $id
     * @return Transporter
     * @throws ModelNotFoundException
     */
    public function getTransporterDetails(int $id): Transporter
    {
        $transporter = $this->transporterRepository->findById($id);

        if (!$transporter) {
            throw new ModelNotFoundException("Transporter not found.");
        }

        return $transporter;
    }

    /**
     * Create a new transporter.
     *
     * @param array $data
     * @return Transporter
     */
    public function createTransporter(array $data): Transporter
    {
        return DB::transaction(function () use ($data) {
            return $this->transporterRepository->create($data);
        });
    }

    /**
     * Update an existing transporter.
     *
     * @param int $id
     * @param array $data
     * @return Transporter
     * @throws ModelNotFoundException
     */
    public function updateTransporter(int $id, array $data): Transporter
    {
        return DB::transaction(function () use ($id, $data) {
            $transporter = $this->transporterRepository->findById($id);

            if (!$transporter) {
                throw new ModelNotFoundException("Transporter not found.");
            }

            return $this->transporterRepository->update($transporter, $data);
        });
    }

    /**
     * Delete a transporter.
     *
     * @param int $id
     * @return void
     * @throws ModelNotFoundException
     */
    public function deleteTransporter(int $id): void
    {
        DB::transaction(function () use ($id) {
            $transporter = $this->transporterRepository->findById($id);

            if (!$transporter) {
                throw new ModelNotFoundException("Transporter not found.");
            }

            $this->transporterRepository->delete($transporter);
        });
    }
}
