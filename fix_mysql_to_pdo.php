<?php
// Script to fix common MySQL to PDO conversion issues
echo "<h2>MySQL to PDO Conversion Fixer</h2>";
echo "<hr>";

$admin_dir = 'c:\\xampp\\htdocs\\libraryManagement\\admin\\';
$files_to_fix = [
    'student.php',
    'return_requests.php',
    'renew_requests.php',
    'issue_requests.php',
    'current.php'
];

$fixes_made = 0;

foreach ($files_to_fix as $filename) {
    $filepath = $admin_dir . $filename;

    if (file_exists($filepath)) {
        echo "<p><strong>Processing:</strong> $filename</p>";

        $content = file_get_contents($filepath);
        $original_content = $content;

        // Fix common patterns
        $content = preg_replace('/\$result\s*=\s*\$conn\s*->\s*query\s*\(\s*\$sql\s*\)\s*;/', '$stmt = $conn->prepare($sql); $stmt->execute();', $content);
        $content = preg_replace('/\$result\s*->\s*fetch_assoc\s*\(\s*\)/', '$stmt->fetch(PDO::FETCH_ASSOC)', $content);
        $content = preg_replace('/while\s*\(\s*\$row\s*=\s*\$result\s*->\s*fetch_assoc\s*\(\s*\)\s*\)/', 'while ($row = $stmt->fetch(PDO::FETCH_ASSOC))', $content);

        // Fix table names 
        $content = str_replace('LMS.user', 'user', $content);
        $content = str_replace('LMS.book', 'book', $content);
        $content = str_replace('LMS.record', 'record', $content);
        $content = str_replace('LMS.message', 'message', $content);
        $content = str_replace('LMS.renew', 'renew', $content);
        $content = str_replace('LMS.return_req', 'return_req', $content);

        if ($content !== $original_content) {
            file_put_contents($filepath, $content);
            echo "<p style='color: green;'>✓ Fixed $filename</p>";
            $fixes_made++;
        } else {
            echo "<p style='color: blue;'>- $filename (no changes needed)</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ $filename not found</p>";
    }
}

echo "<hr>";
echo "<h3>Summary</h3>";
echo "<p>Files processed: " . count($files_to_fix) . "</p>";
echo "<p>Files modified: $fixes_made</p>";
echo "<p><strong>Note:</strong> This is a basic automatic fix. Some files may need manual review for complex queries.</p>";
echo "<p><a href='../admin/index.php' style='background: #28a745; color: white; padding: 10px; text-decoration: none; border-radius: 5px;'>Test Admin Panel</a></p>";
?>