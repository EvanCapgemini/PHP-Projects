<?php
session_start();
require_once "pdo.php";
if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['make']) || empty($_POST['model']) || empty($_POST['year']) || empty($_POST['mileage'])) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    }
    if (!is_numeric($_POST['year'])) {
        $_SESSION['error'] = "Year must be an integer";
        header("Location: add.php");
        return;
    }
    if (!is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = "Mileage must be an integer";
        header("Location: add.php");
        return;
    }
    $stmt = $pdo->prepare("INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :md, :yr, :mi)");
    $stmt->execute([
        ':mk' => $_POST['make'],
        ':md' => $_POST['model'],
        ':yr' => $_POST['year'],
        ':mi' => $_POST['mileage']
    ]);
    $_SESSION['success'] = "Record added";
    header("Location: index.php");
    return;
}
?>
<html>
<head><title>Evan Elijah Mendonsa</title></head>
<body>
<h1>Add a New Automobile</h1>
<?php
if (isset($_SESSION['error'])) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>");
    unset($_SESSION['error']);
}
?>
<form method="POST">
Make <input type="text" name="make"><br/>
Model <input type="text" name="model"><br/>
Year <input type="text" name="year"><br/>
Mileage <input type="text" name="mileage"><br/>
<input type="submit" value="Add">
</form>
</body>
</html>
