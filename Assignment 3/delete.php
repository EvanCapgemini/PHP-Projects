<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die("ACCESS DENIED");
}

if (!isset($_GET['book_id'])) {
    $_SESSION['error'] = "Missing book_id";
    header("Location: index.php");
    return;
}

$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id = :id");
$stmt->execute([':id' => $_GET['book_id']]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    $_SESSION['error'] = "Book not found";
    header("Location: index.php");
    return;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete'])) {
        $stmt = $pdo->prepare("DELETE FROM books WHERE book_id = :id");
        $stmt->execute([':id' => $_GET['book_id']]);
        $_SESSION['success'] = "Record deleted";
    }
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head><title>Evan Mendonsa - Delete Book</title></head>
<body>
<h1>Confirm Delete</h1>
<p>Are you sure you want to delete: <strong><?= htmlentities($book['title']) ?></strong> by <?= htmlentities($book['author']) ?>?</p>
<form method="POST">
<input type="submit" name="delete" value="Delete">
<a href="index.php">Cancel</a>
</form>
</body>
</html>
