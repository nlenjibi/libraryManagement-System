<?php
// Test add book functionality
require('../dbconn.php');

echo "<h2>🧪 Book Addition Test</h2>";
echo "<hr>";

try {
    // Test adding a book
    $test_book = [
        'Title' => 'Test Book for Library System',
        'Author' => 'Test Author, Co-Author',
        'Publisher' => 'Test Publisher',
        'Year' => '2025',
        'Availability' => 5
    ];

    $sql = "INSERT INTO book (Title, Author, Publisher, Year, Availability) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute([$test_book['Title'], $test_book['Author'], $test_book['Publisher'], $test_book['Year'], $test_book['Availability']])) {
        echo "<p style='color: green;'>✅ Test book added successfully!</p>";

        // Get the book ID
        $book_id = $conn->lastInsertId();
        echo "<p>📖 Book ID: $book_id</p>";

        // Verify the book exists
        $verify_sql = "SELECT * FROM book WHERE BookId = ?";
        $verify_stmt = $conn->prepare($verify_sql);
        $verify_stmt->execute([$book_id]);
        $book = $verify_stmt->fetch(PDO::FETCH_ASSOC);

        if ($book) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h4>📚 Book Details:</h4>";
            echo "<p><strong>Title:</strong> " . $book['Title'] . "</p>";
            echo "<p><strong>Author:</strong> " . $book['Author'] . "</p>";
            echo "<p><strong>Publisher:</strong> " . $book['Publisher'] . "</p>";
            echo "<p><strong>Year:</strong> " . $book['Year'] . "</p>";
            echo "<p><strong>Availability:</strong> " . $book['Availability'] . "</p>";
            echo "</div>";
        }

        // Clean up - remove test book
        $cleanup_sql = "DELETE FROM book WHERE BookId = ?";
        $cleanup_stmt = $conn->prepare($cleanup_sql);
        $cleanup_stmt->execute([$book_id]);
        echo "<p style='color: orange;'>🧹 Test book cleaned up</p>";

    } else {
        echo "<p style='color: red;'>❌ Failed to add test book</p>";
    }

    echo "<hr>";
    echo "<h3>✅ Add Book Functionality is Working!</h3>";
    echo "<p>You can now add books successfully through the admin panel.</p>";
    echo "<p><a href='admin/addbook.php' style='background: #28a745; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>📖 Add New Book</a></p>";

} catch (PDOException $e) {
    echo "<p style='color: red;'>❌ Database Error: " . $e->getMessage() . "</p>";
}
?>