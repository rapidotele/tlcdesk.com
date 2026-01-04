<?php

namespace App\Repositories;

use App\Core\BaseRepository;

class CategoryRepository extends BaseRepository
{
    protected $table = 'categories';

    public function findAllByOrg($orgId) {
        $sql = "SELECT * FROM categories WHERE organization_id = ?";
        return $this->db->query($sql, [$orgId])->fetchAll();
    }
}
