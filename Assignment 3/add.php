<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['year']) || empty($_POST['pages'])) {
        $_SESSION['error'] = "All fields are required";
        header("Location: add.php");
        return;
    }

    if (!is_numeric($_POST['year']) || !is_numeric($_POST['pages'])) {
        $_SESSION['error'] = "Year and Pages must be integers";
        header("Location: add.php");
        return;
    }

    $sql = "INSERT INTO books (title, author, year, pages) VALUES (:title, :author, :year, :pages)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => htmlentities($_POST['title']),
        ':author' => htmlentities($_POST['author']),
        ':year' => $_POST['year'],
        ':pages' => $_POST['pages']
    ]);

    $_SESSION['success'] = "Record added";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head><title>Evan Mendonsa - Add Book</title></head>
<body>
<h1>Add a New Book</h1>
<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">'.htmlentities($_SESSION['error'])."</p>";
    unset($_SESSION['error']);
}
?>
<form method="POST">
Title: <input type="text" name="title"><br/>
Author: <input type="text" name="author"><br/>
Year: <input type="text" name="year"><br/>
Pages: <input type="text" name="pages"><br/>
<input type="submit" value="Add">
<a href="index.php">Cancel</a>
</form>
</body>
</html>
