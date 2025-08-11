<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die("Not logged in");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Evan Mendonsa Book Reviews</title>
</head>
<body>
<h1>Book Reviews for <?= htmlentities($_SESSION['name']) ?></h1>

<?php
if (isset($_SESSION['success'])) {
    echo '<p style="color:green;">' . htmlentities($_SESSION['success']) . "</p>\n";
    unset($_SESSION['success']);
}
?>

<p><a href="add.php">Add New Review</a> | <a href="logout.php">Logout</a></p>

<ul>
<?php
$stmt = $pdo->query("SELECT book_title, reviewer_name, rating, comments FROM reviews");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li><strong>" . htmlentities($row['book_title']) . "</strong> by " . htmlentities($row['reviewer_name']) .
         " - Rating: " . htmlentities($row['rating']) . "<br/>" .
         "Comments: " . htmlentities($row['comments']) . "</li><br/>";
}
?>
</ul>
</body>
</html>
