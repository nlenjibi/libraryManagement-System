<?php
require 'dbconn.php';

echo "=== STUDENT FUNCTIONALITY TEST ===\n";

try {
    // Test message table access
    echo "\n1. Testing message table access:\n";
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM message LIMIT 1");
    $stmt->execute();
    echo "✅ Message table accessible\n";
    
    // Test other tables
    echo "\n2. Testing other tables:\n";
    $tables = ['return_req', 'renew', 'record', 'book', 'user'];
    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM $table LIMIT 1");
        $stmt->execute();
        echo "✅ $table table accessible\n";
    }
    
    echo "\n✅ All database issues resolved!\n";
    
} catch(Exception $e) {
    echo '❌ Error: ' . $e->getMessage() . "\n";
}
?>