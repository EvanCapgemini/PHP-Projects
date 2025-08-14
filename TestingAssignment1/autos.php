<?php
require_once "pdo.php";

if (!isset($_GET['name']) || strlen($_GET['name']) < 1) {
    die("Name parameter missing");
}

$message = false;

if (isset($_POST['logout'])) {
    header("Location: index.php");
    return;
}

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) < 1) {
        $message = "Make is required";
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $message = "Mileage and year must be numeric";
    } else {
        $stmt = $pdo->prepare('INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)');
        $stmt->execute(array(
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ));
        $message = "Record inserted";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Evan Elijah Mendonsa's Automobile Tracker</title>
</head>
<body>
<h1>Tracking Autos for <?= htmlentities($_GET['name']) ?></h1>
<?php
if ($message !== false) {
    echo('<p style="color: green;">' . htmlentities($message) . "</p>");
}
?>
<form method="post">
<p>Make: <input type="text" name="make"></p>
<p>Year: <input type="text" name="year"></p>
<p>Mileage: <input type="text" name="mileage"></p>
<p><input type="submit" value="Add">
<input type="submit" name="logout" value="Logout"></p>
</form>
<h2>Automobiles</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li>";
    echo htmlentities($row['year']) . " " . htmlentities($row['make']) . " / " . htmlentities($row['mileage']);
    echo "</li>";
}
?>
</ul>
</body>
</html>