<?php
session_start();
require_once "pdo.php";
?>

<!DOCTYPE html>
<html>
<head><title>Evan Mendonsa - Bookstore</title></head>
<body>
<h1>Welcome to the Bookstore</h1>

<?php
if (!isset($_SESSION['name'])) {
    echo '<p><a href="login.php">Please log in</a></p>';
    return;
}

if (isset($_SESSION['success'])) {
    echo '<p style="color:green">'.htmlentities($_SESSION['success'])."</p>";
    unset($_SESSION['success']);
}

$stmt = $pdo->query("SELECT * FROM books");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($rows) == 0) {
    echo "<p>No rows found</p>";
} else {
    echo "<table border='1'>";
    echo "<tr><th>Title</th><th>Author</th><th>Year</th><th>Pages</th><th>Action</th></tr>";
    foreach ($rows as $row) {
        echo "<tr><td>".htmlentities($row['title'])."</td>";
        echo "<td>".htmlentities($row['author'])."</td>";
        echo "<td>".htmlentities($row['year'])."</td>";
        echo "<td>".htmlentities($row['pages'])."</td>";
        echo "<td><a href='edit.php?book_id=".$row['book_id']."'>Edit</a> / ";
        echo "<a href='delete.php?book_id=".$row['book_id']."'>Delete</a></td></tr>";
    }
    echo "</table>";
}
?>

<p><a href="add.php">Add New Entry</a> | <a href="logout.php">Logout</a></p>
</body>
</html>
