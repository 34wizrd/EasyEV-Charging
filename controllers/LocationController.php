<?php
class LocationController {
    private $locationModel;

    public function __construct($locationModel) {
        $this->locationModel = $locationModel;
    }

    public function createLocation($data) {
        // Validate and sanitize input data
        // Call the Location model to add a new location
        return $this->locationModel->addLocation($data);
    }

    public function updateLocation($locationId, $data) {
        // Validate and sanitize input data
        // Call the Location model to update the specified location
        return $this->locationModel->editLocation($locationId, $data);
    }

    public function deleteLocation($locationId) {
        // Call the Location model to delete the specified location
        return $this->locationModel->removeLocation($locationId);
    }

    public function listLocations() {
        // Call the Location model to retrieve all locations
        return $this->locationModel->getAllLocations();
    }

    public function listAvailableLocations() {
        // Call the Location model to retrieve available locations
        return $this->locationModel->getAvailableLocations();
    }
}
?>