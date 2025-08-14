<?php
session_start();
require_once "config.php";
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}
if (!isset($_GET['autos_id'])) {
    die("Missing autos_id");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM autos WHERE autos_id = :id");
        $stmt->execute([':id' => $_GET['autos_id']]);
        $_SESSION['success'] = "Record deleted";
        header("Location: index.php");
        return;
    } else {
        header("Location: index.php");
        return;
    }
}
?>
<html>
<head><title>Delete Automobile</title></head>
<body>
<h1>Confirm Deletion</h1>
<form method="POST">
<input type="submit" name="delete" value="Delete">
<input type="submit" name="cancel" value="Cancel">
</form>
</body>
</html>
