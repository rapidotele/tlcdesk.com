<?php

namespace App\Repositories;

use App\Core\BaseRepository;

class TransactionRepository extends BaseRepository
{
    protected $table = 'transactions';

    public function create($orgId, $userId, $categoryId, $type, $amount, $date, $desc)
    {
        $sql = "INSERT INTO transactions (organization_id, user_id, category_id, type, amount, date, description) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $this->db->query($sql, [$orgId, $userId, $categoryId, $type, $amount, $date, $desc]);
        return $this->db->lastInsertId();
    }

    public function getMonthlyTotals($orgId, $month, $year)
    {
        $sql = "SELECT type, SUM(amount) as total
                FROM transactions
                WHERE organization_id = ?
                AND MONTH(date) = ? AND YEAR(date) = ?
                GROUP BY type";

        $stmt = $this->db->query($sql, [$orgId, $month, $year]);
        $rows = $stmt->fetchAll();

        $totals = ['income' => 0, 'expense' => 0];
        foreach ($rows as $row) {
            $totals[$row['type']] = (float)$row['total'];
        }
        return $totals;
    }
}
