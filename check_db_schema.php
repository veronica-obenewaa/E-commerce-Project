<?php
require_once __DIR__ . '/settings/db_class.php';

echo "<h1>Database Schema Check</h1>";

try {
    $db = new db_connection();
    $conn = $db->db_conn();
    
    if (!$conn) {
        echo "<p style='color: red;'>❌ Cannot connect to database</p>";
        exit;
    }
    
    echo "<h2>Orders Table Schema</h2>";
    $result = mysqli_query($conn, "DESCRIBE orders");
    
    if ($result) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p style='color: green;'>✅ Orders table exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Orders table not found: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    }
    
    echo "<h2>Payments Table Schema</h2>";
    $result = mysqli_query($conn, "DESCRIBE payments");
    
    if ($result) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p style='color: green;'>✅ Payments table exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Payments table not found: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    }
    
    echo "<h2>OrderDetails Table Schema</h2>";
    $result = mysqli_query($conn, "DESCRIBE orderdetails");
    
    if ($result) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
        
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Field']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Type']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Null']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Key']) . "</td>";
            echo "<td>" . htmlspecialchars($row['Default'] ?? 'NULL') . "</td>";
            echo "<td>" . htmlspecialchars($row['Extra']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        echo "<p style='color: green;'>✅ OrderDetails table exists</p>";
    } else {
        echo "<p style='color: red;'>❌ OrderDetails table not found: " . htmlspecialchars(mysqli_error($conn)) . "</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Exception: " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>
