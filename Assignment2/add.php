<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die('Not logged in');
}

if (isset($_POST['cancel'])) {
    header("Location: view.php");
    return;
}

if (isset($_POST['make']) && isset($_POST['year']) && isset($_POST['mileage'])) {
    if (strlen($_POST['make']) < 1) {
        $_SESSION['error'] = "Make is required";
        header("Location: add.php");
        return;
    } elseif (!is_numeric($_POST['year']) || !is_numeric($_POST['mileage'])) {
        $_SESSION['error'] = "Mileage and year must be numeric";
        header("Location: add.php");
        return;
    } else {
        $stmt = $pdo->prepare("INSERT INTO autos (make, year, mileage) VALUES (:mk, :yr, :mi)");
        $stmt->execute([
            ':mk' => $_POST['make'],
            ':yr' => $_POST['year'],
            ':mi' => $_POST['mileage']
        ]);
        $_SESSION['success'] = "Record inserted";
        header("Location: view.php");
        return;
    }
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
if (isset($_SESSION['error'])) {
    echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>");
    unset($_SESSION['error']);
}
?>
<form method="POST">
    <label for="make">Make</label>
    <input type="text" name="make" id="make"><br/>
    <label for="year">Year</label>
    <input type="text" name="year" id="year"><br/>
    <label for="mileage">Mileage</label>
    <input type="text" name="mileage" id="mileage"><br/>
    <input type="submit" value="Add">
    <input type="submit" name="cancel" value="Cancel">
</form>

</body>
</html>
