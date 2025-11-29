<?php
require 'settings/core.php';
$conn = new mysqli('localhost', 'root', '', 'med_epharma');

echo "=== SPECIALIZATIONS TABLE ===\n";
$result = $conn->query('SELECT * FROM specializations ORDER BY id');
while($row = $result->fetch_assoc()) {
    echo $row['id'] . ' - ' . $row['name'] . "\n";
}

echo "\n=== CUSTOMER_SPECIALIZATIONS SAMPLE ===\n";
$result = $conn->query('SELECT cs.customer_id, cs.specialization_id, s.name, c.customer_name FROM customer_specializations cs LEFT JOIN specializations s ON cs.specialization_id = s.id LEFT JOIN customer c ON cs.customer_id = c.customer_id LIMIT 20');
while($row = $result->fetch_assoc()) {
    echo "Customer: {$row['customer_name']}, SpecID: {$row['specialization_id']}, Name: {$row['name']}\n";
}

$conn->close();
?>
