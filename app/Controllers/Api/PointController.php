<?php

namespace App\Controllers\Api;

class PointController
{
    public function index()
    {
        // Mock Data for Points of Interest (Gas, EV, Restrooms)
        $points = [
            ['id' => 1, 'type' => 'gas', 'lat' => 40.7128, 'lng' => -74.0060, 'name' => 'Gas Station A'],
            ['id' => 2, 'type' => 'ev', 'lat' => 40.7580, 'lng' => -73.9855, 'name' => 'EV Charger X'],
            ['id' => 3, 'type' => 'restroom', 'lat' => 40.7829, 'lng' => -73.9654, 'name' => 'Public Restroom'],
        ];

        header('Content-Type: application/json');
        echo json_encode(['points' => $points]);
    }
}
