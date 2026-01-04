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

    public function create(int $orgId, string $name, string $email, string $passwordHash, string $role, $status = 'active')
    {
        $sql = "INSERT INTO users (organization_id, name, email, password_hash, role, status) VALUES (?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, [$orgId, $name, $email, $passwordHash, $role, $status]);
        return $this->db->lastInsertId();
    }

    public function update($id, $data)
    {
        $fields = [];
        $params = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $params[] = $value;
        }
        $params[] = $id;

        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $this->db->query($sql, $params);
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

    public function findAllWithOrgAndProfile()
    {
        $sql = "SELECT u.*, o.name as organization_name, dp.tlc_license, dp.dmv_license, dp.dmv_expiration
                FROM users u
                LEFT JOIN organizations o ON u.organization_id = o.id
                LEFT JOIN driver_profiles dp ON u.id = dp.user_id
                ORDER BY u.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function findWithProfile($id)
    {
        $sql = "SELECT u.*, dp.tlc_license, dp.dmv_license, dp.dmv_expiration
                FROM users u
                LEFT JOIN driver_profiles dp ON u.id = dp.user_id
                WHERE u.id = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->fetch();
    }

    public function saveProfile($userId, $tlc, $dmv, $expiration)
    {
        $sql = "INSERT INTO driver_profiles (user_id, tlc_license, dmv_license, dmv_expiration)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE tlc_license=?, dmv_license=?, dmv_expiration=?";
        $this->db->query($sql, [$userId, $tlc, $dmv, $expiration, $tlc, $dmv, $expiration]);
    }

    public function updateLocale($id, $locale)
    {
        $sql = "UPDATE users SET locale = ? WHERE id = ?";
        $this->db->query($sql, [$locale, $id]);
    }
}
