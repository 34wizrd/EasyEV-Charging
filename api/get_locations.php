<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../config/config.php';

$sql = 'SELECT * FROM locations';
$result = $conn->query($sql);

$locations = array();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $locations[] = $row;
    }
}

echo json_encode(['success' => true, 'data' => $locations]);
$conn->close();
?>
