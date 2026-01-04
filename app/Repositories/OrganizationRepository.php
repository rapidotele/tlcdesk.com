<?php

namespace App\Repositories;

use App\Core\BaseRepository;

class OrganizationRepository extends BaseRepository
{
    protected $table = 'organizations';

    public function create(string $name): int
    {
        $sql = "INSERT INTO organizations (name) VALUES (?)";
        $this->db->query($sql, [$name]);
        $orgId = $this->db->lastInsertId();

        // Seed Default Categories
        $defaults = [
            ['name' => 'Fares', 'type' => 'income'],
            ['name' => 'Tips', 'type' => 'income'],
            ['name' => 'Fuel', 'type' => 'expense'],
            ['name' => 'Maintenance', 'type' => 'expense'],
            ['name' => 'TLC Fees', 'type' => 'expense'],
            ['name' => 'Insurance', 'type' => 'expense'],
        ];

        $catSql = "INSERT INTO categories (organization_id, name, type) VALUES (?, ?, ?)";
        foreach ($defaults as $cat) {
            $this->db->query($catSql, [$orgId, $cat['name'], $cat['type']]);
        }

        return $orgId;
    }

    public function getSettings($orgId)
    {
        $stmt = $this->db->query("SELECT theme_id, locale FROM organizations WHERE id = ?", [$orgId]);
        return $stmt->fetch();
    }
}
