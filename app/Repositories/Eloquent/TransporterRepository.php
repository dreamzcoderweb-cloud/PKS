<?php

namespace App\Repositories\Eloquent;

use App\Models\Transporter;
use App\Repositories\Interfaces\TransporterRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class TransporterRepository implements TransporterRepositoryInterface
{
    /**
     * Get all transporters.
     *
     * @return Collection
     */
    public function all(): Collection
    {
        return Transporter::with('branch')->get();
    }

    /**
     * Find transporter by ID.
     *
     * @param int $id
     * @return Transporter|null
     */
    public function findById(int $id): ?Transporter
    {
        return Transporter::find($id);
    }

    /**
     * Create a new transporter.
     *
     * @param array $data
     * @return Transporter
     */
    public function create(array $data): Transporter
    {
        return Transporter::create($data);
    }

    /**
     * Update an existing transporter.
     *
     * @param Transporter $transporter
     * @param array $data
     * @return Transporter
     */
    public function update(Transporter $transporter, array $data): Transporter
    {
        $transporter->update($data);
        return $transporter;
    }

    /**
     * Delete a transporter.
     *
     * @param Transporter $transporter
     * @return bool
     */
    public function delete(Transporter $transporter): bool
    {
        return $transporter->delete();
    }
}
