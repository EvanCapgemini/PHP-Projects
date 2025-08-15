<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Evan Elijah Mendonsa</title>
</head>
<body>

<h1>Tracking Autos for <?php echo htmlentities($_SESSION['name']); ?></h1>
<?php
if (isset($_SESSION['success'])) {
    echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>");
    unset($_SESSION['success']);
}

$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
echo "<ul>";
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li>" . htmlentities($row['year']) . " " . htmlentities($row['make']) . " / " . htmlentities($row['mileage']) . "</li>";
}
echo "</ul>";
?>
<p><a href="add.php">Add New</a> | <a href="logout.php">Logout</a></p>

</body>
</html>
