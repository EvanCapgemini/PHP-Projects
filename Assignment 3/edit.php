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
    if (empty($_POST['title']) || empty($_POST['author']) || empty($_POST['year']) || empty($_POST['pages'])) {
        $_SESSION['error'] = "All fields are required";
        header("Location: edit.php?book_id=".$_GET['book_id']);
        return;
    }

    if (!is_numeric($_POST['year']) || !is_numeric($_POST['pages'])) {
        $_SESSION['error'] = "Year and Pages must be integers";
        header("Location: edit.php?book_id=".$_GET['book_id']);
        return;
    }

    $sql = "UPDATE books SET title = :title, author = :author, year = :year, pages = :pages WHERE book_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => htmlentities($_POST['title']),
        ':author' => htmlentities($_POST['author']),
        ':year' => $_POST['year'],
        ':pages' => $_POST['pages'],
        ':id' => $_GET['book_id']
    ]);

    $_SESSION['success'] = "Record edited";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head><title>Evan Mendonsa - Edit Book</title></head>
<body>
<h1>Edit Book</h1>
<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red">'.htmlentities($_SESSION['error'])."</p>";
    unset($_SESSION['error']);
}
?>
<form method="POST">
Title: <input type="text" name="title" value="<?= htmlentities($book['title']) ?>"><br/>
Author: <input type="text" name="author" value="<?= htmlentities($book['author']) ?>"><br/>
Year: <input type="text" name="year" value="<?= htmlentities($book['year']) ?>"><br/>
Pages: <input type="text" name="pages" value="<?= htmlentities($book['pages']) ?>"><br/>
<input type="submit" value="Save">
<a href="index.php">Cancel</a>
</form>
</body>
</html>
