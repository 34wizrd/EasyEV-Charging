<?php

class Location {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function addLocation($description, $numberOfStations, $costPerHour) {
        $stmt = $this->db->prepare("INSERT INTO locations (description, number_of_stations, cost_per_hour) VALUES (?, ?, ?)");
        $stmt->bind_param("sid", $description, $numberOfStations, $costPerHour);
        return $stmt->execute();
    }

    public function editLocation($locationId, $description, $numberOfStations, $costPerHour) {
        $stmt = $this->db->prepare("UPDATE locations SET description = ?, number_of_stations = ?, cost_per_hour = ? WHERE location_id = ?");
        $stmt->bind_param("sidi", $description, $numberOfStations, $costPerHour, $locationId);
        return $stmt->execute();
    }

    public function deleteLocation($locationId) {
        $stmt = $this->db->prepare("DELETE FROM locations WHERE location_id = ?");
        $stmt->bind_param("i", $locationId);
        return $stmt->execute();
    }

    public function listLocations() {
        $result = $this->db->query("SELECT * FROM locations");
        if ($result === false) {
            // Query failed, return empty array or handle error
            return array();
        }
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function listAvailableLocations() {
        $stmt = $this->db->prepare("SELECT * FROM locations WHERE number_of_stations > 0");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}