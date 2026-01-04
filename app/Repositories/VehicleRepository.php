<?php

namespace App\Repositories;

use App\Core\BaseRepository;

class VehicleRepository extends BaseRepository
{
    protected $table = 'vehicles';

    public function create($orgId, $plate, $year, $make, $model)
    {
        $sql = "INSERT INTO vehicles (organization_id, plate, year, make, model) VALUES (?, ?, ?, ?, ?)";
        $this->db->query($sql, [$orgId, $plate, $year, $make, $model]);
        return $this->db->lastInsertId();
    }

    public function countByOrg($orgId)
    {
        $sql = "SELECT COUNT(*) as total FROM vehicles WHERE organization_id = ?";
        $stmt = $this->db->query($sql, [$orgId]);
        return $stmt->fetch()['total'];
    }
}
