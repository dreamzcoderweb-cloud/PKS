<?php

namespace App\Repositories\Interfaces;

use App\Models\Transporter;
use Illuminate\Database\Eloquent\Collection;

interface TransporterRepositoryInterface
{
    /**
     * Get all transporters.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find transporter by ID.
     *
     * @param int $id
     * @return Transporter|null
     */
    public function findById(int $id): ?Transporter;

    /**
     * Create a new transporter.
     *
     * @param array $data
     * @return Transporter
     */
    public function create(array $data): Transporter;

    /**
     * Update an existing transporter.
     *
     * @param Transporter $transporter
     * @param array $data
     * @return Transporter
     */
    public function update(Transporter $transporter, array $data): Transporter;

    /**
     * Delete a transporter.
     *
     * @param Transporter $transporter
     * @return bool
     */
    public function delete(Transporter $transporter): bool;
}
