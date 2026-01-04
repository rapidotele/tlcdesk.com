<?php

namespace App\Repositories;

use App\Core\BaseRepository;

class UserRepository extends BaseRepository
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->db->query($sql, [$email]);
        return $stmt->fetch();
    }

    public function create(int $orgId, string $name, string $email, string $passwordHash, string $role)
    {
        $sql = "INSERT INTO users (organization_id, name, email, password_hash, role) VALUES (?, ?, ?, ?, ?)";
        $this->db->query($sql, [$orgId, $name, $email, $passwordHash, $role]);
        return $this->db->lastInsertId();
    }

    public function findAllByOrgAndRole($orgId, $role)
    {
        $sql = "SELECT * FROM users WHERE organization_id = ? AND role = ?";
        $stmt = $this->db->query($sql, [$orgId, $role]);
        return $stmt->fetchAll();
    }

    public function countByOrgAndRole($orgId, $role)
    {
        $sql = "SELECT COUNT(*) as total FROM users WHERE organization_id = ? AND role = ?";
        $stmt = $this->db->query($sql, [$orgId, $role]);
        return $stmt->fetch()['total'];
    }
}
