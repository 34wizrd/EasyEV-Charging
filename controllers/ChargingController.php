<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../classes/ChargingSession.php';

class ChargingController {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function checkIn($userId, $locationId) {
        $session = new ChargingSession($this->db, $userId, $locationId);
        return $session->checkIn();
    }

    public function checkOut($userId, $locationId) {
        $session = new ChargingSession($this->db, $userId, $locationId);
        return $session->checkOut();
    }

    public function getChargingHistory($userId) {
        $session = new ChargingSession($this->db, $userId, null);
        return $session->getUserChargingHistory($userId);
    }
}
?>