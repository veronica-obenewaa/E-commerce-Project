<?php
/**
 * Migration script to fix corrupted specialization data
 * This fixes the issue where numeric specialization IDs were saved as specialization names
 */

require_once __DIR__ . '/settings/core.php';

// Get database connection from core
$connection = new mysqli('localhost', 'root', '', 'med_epharma');

if ($connection->connect_error) {
    echo "Connection failed: " . $connection->connect_error;
    exit;
}

echo "Starting migration to fix specialization names...\n";

// Find and delete specializations that are purely numeric (corrupted entries)
$result = $connection->query("SELECT id, name FROM specializations WHERE name REGEXP '^[0-9]+$'");

if ($result && $result->num_rows > 0) {
    echo "\nFound " . $result->num_rows . " corrupted specialization entries (numeric names):\n";
    
    $corrupted_ids = [];
    while ($row = $result->fetch_assoc()) {
        echo "  ID: {$row['id']}, Name: {$row['name']}\n";
        $corrupted_ids[] = $row['id'];
    }
    
    // Delete corrupted specializations
    foreach ($corrupted_ids as $id) {
        $delete = $connection->prepare("DELETE FROM specializations WHERE id = ?");
        $delete->bind_param("i", $id);
        if ($delete->execute()) {
            echo "  Deleted specialization ID: $id\n";
        } else {
            echo "  Failed to delete specialization ID: $id\n";
        }
        $delete->close();
    }
    
    echo "\nCorrupted specialization entries have been removed.\n";
    echo "Note: Any customer_specializations records linked to these IDs will be automatically deleted due to CASCADE constraint.\n";
} else {
    echo "\nNo corrupted specialization entries found. Database is clean!\n";
}

// Verify the fix
echo "\n--- Verification ---\n";
$verify = $connection->query("SELECT id, name FROM specializations ORDER BY id");
if ($verify && $verify->num_rows > 0) {
    echo "Remaining specializations:\n";
    while ($row = $verify->fetch_assoc()) {
        echo "  ID: {$row['id']}, Name: {$row['name']}\n";
    }
} else {
    echo "No specializations found.\n";
}

$connection->close();
echo "\nMigration complete!\n";
?>
