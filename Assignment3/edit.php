<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}
if (!isset($_GET['autos_id'])) {
    die("Missing autos_id");
}
$stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id = :id");
$stmt->execute([':id' => $_GET['autos_id']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row) {
    die("Bad value for autos_id");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['make']) || empty($_POST['model']) || empty($_POST['year']) || empty($_POST['mileage'])) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?autos_id=".$_GET['autos_id']);
        return;
    }
    if (!is_numeric($_POST['year'])) {
        $_SESSION['error'] = "Year must be an integer";
        header("Location: edit.php?autos_id=".$_GET['autos_id']);
        return;
    }
    if (!is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = "Mileage must be an integer";
        header("Location: edit.php?autos_id=".$_GET['autos_id']);
        return;
    }
    $stmt = $pdo->prepare("UPDATE autos SET make = :mk, model = :md, year = :yr, mileage = :mi WHERE autos_id = :id");
    $stmt->execute([
        ':mk' => $_POST['make'],
        ':md' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage'],
        ':id' => $_GET['autos_id']
    ]);
    $_SESSION['success'] = "Record edited";
    header("Location: index.php");
    return;
}
?>
<html>
<head><title>Evan Elijah Mendonsa</title></head>
<body>
<h1>Edit Automobile</h1>
<?php
if (isset($_SESSION['error'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>");
    unset($_SESSION['error']);
}
?>
<form method="POST">
Make <input type="text" name="make" value="<?= htmlentities($row['make']) ?>"><br/>
Model <input type="text" name="model" value="<?= htmlentities($row['model']) ?>"><br/>
Year <input type="text" name="year" value="<?= htmlentities($row['year']) ?>"><br/>
Mileage <input type="text" name="mileage" value="<?= htmlentities($row['mileage']) ?>"><br/>
<input type="submit" value="Save">
</form>
</body>
</html>
