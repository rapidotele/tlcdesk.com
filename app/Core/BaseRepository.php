<?php

namespace App\Core;

abstract class BaseRepository
{
    protected $db;
    protected $table;
    protected $organizationId = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function setOrganizationId($id)
    {
        $this->organizationId = $id;
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $params = [$id];

        if ($this->organizationId) {
            $sql .= " AND organization_id = ?";
            $params[] = $this->organizationId;
        }

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetch();
    }

    public function findAll($limit = 50, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if ($this->organizationId) {
            $sql .= " WHERE organization_id = ?";
            $params[] = $this->organizationId;
        }

        $sql .= " LIMIT $limit OFFSET $offset";

        $stmt = $this->db->query($sql, $params);
        return $stmt->fetchAll();
    }

    // Additional generic methods like create/update could go here
}
