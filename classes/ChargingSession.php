<?php

class ChargingSession {
    private $db;
    private $sessionId;
    private $userId;
    private $locationId;
    private $checkInTime;
    private $checkOutTime;
    private $totalCost;

    public function __construct($dbConnection, $userId, $locationId) {
        $this->db = $dbConnection;
        $this->userId = $userId;
        $this->locationId = $locationId;
        $this->checkInTime = null;
        $this->checkOutTime = null;
        $this->totalCost = 0.0;
    }

    public function checkIn() {
        $this->checkInTime = new DateTime();
        $checkInStr = $this->checkInTime->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare("INSERT INTO charging_sessions (user_id, location_id, check_in_time) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $this->userId, $this->locationId, $checkInStr);
        if ($stmt->execute()) {
            $this->sessionId = $this->db->insert_id;
            return true;
        }
        return false;
    }

    public function checkOut() {
        if ($this->checkInTime === null && !$this->loadLastSession()) {
            throw new Exception("User has not checked in.");
        }
        $this->checkOutTime = new DateTime();
        $this->calculateTotalCost();
        $checkOutStr = $this->checkOutTime->format('Y-m-d H:i:s');
        $stmt = $this->db->prepare("UPDATE charging_sessions SET check_out_time=?, total_cost=? WHERE session_id=?");
        $stmt->bind_param("sdi", $checkOutStr, $this->totalCost, $this->sessionId);
        return $stmt->execute();
    }

    private function calculateTotalCost() {
        if ($this->checkOutTime === null) {
            throw new Exception("User has not checked out yet.");
        }
        $duration = $this->checkOutTime->getTimestamp() - $this->checkInTime->getTimestamp();
        $hours = $duration / 3600;
        $costPerHour = $this->getCostPerHour($this->locationId);
        $this->totalCost = round($hours * $costPerHour, 2);
    }

    private function getCostPerHour($locationId) {
        $stmt = $this->db->prepare("SELECT cost_per_hour FROM locations WHERE location_id = ?");
        $stmt->bind_param("i", $locationId);
        $stmt->execute();
        $stmt->bind_result($costPerHour);
        if ($stmt->fetch()) {
            return $costPerHour;
        }
        return 0.0;
    }

    private function loadLastSession() {
        $stmt = $this->db->prepare("SELECT session_id, check_in_time FROM charging_sessions WHERE user_id = ? AND location_id = ? AND check_out_time IS NULL ORDER BY check_in_time DESC LIMIT 1");
        $stmt->bind_param("ii", $this->userId, $this->locationId);
        $stmt->execute();
        $stmt->bind_result($sessionId, $checkInTime);
        if ($stmt->fetch()) {
            $this->sessionId = $sessionId;
            $this->checkInTime = new DateTime($checkInTime);
            return true;
        }
        return false;
    }

    public function getUserChargingHistory($userId) {
        $stmt = $this->db->prepare("SELECT cs.session_id, l.description, cs.check_in_time, cs.check_out_time, cs.total_cost FROM charging_sessions cs JOIN locations l ON cs.location_id = l.location_id WHERE cs.user_id = ? ORDER BY cs.check_in_time DESC");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $history = [];
        while ($row = $result->fetch_assoc()) {
            $history[] = $row;
        }
        return $history;
    }

    public function getTotalCost() {
        return $this->totalCost;
    }

    public function getCheckInTime() {
        return $this->checkInTime;
    }

    public function getCheckOutTime() {
        return $this->checkOutTime;
    }
}