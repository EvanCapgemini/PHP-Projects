<?php
session_start();
require_once "pdo.php";

if (!isset($_SESSION['name'])) {
    die("Not logged in");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cancel'])) {
        header("Location: view.php");
        return;
    }

    if (empty($_POST['book_title']) || empty($_POST['reviewer_name'])) {
        $_SESSION['error'] = "Book title and reviewer name are required";
        header("Location: add.php");
        return;
    } elseif (!is_numeric($_POST['rating'])) {
        $_SESSION['error'] = "Rating must be numeric";
        header("Location: add.php");
        return;
    } else {
        $stmt = $pdo->prepare("INSERT INTO reviews (book_title, reviewer_name, rating, comments) VALUES (:bt, :rn, :rt, :cm)");
        $stmt->execute([
            ':bt' => $_POST['book_title'],
            ':rn' => $_POST['reviewer_name'],
            ':rt' => $_POST['rating'],
            ':cm' => $_POST['comments']
        ]);
        $_SESSION['success'] = "Review submitted";
        header("Location: view.php");
        return;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Evan Mendonsa Add Review</title>
</head>
<body>
<h1>Add a New Book Review</h1>

<?php
if (isset($_SESSION['error'])) {
    echo '<p style="color:red;">' . htmlentities($_SESSION['error']) . "</p>\n";
    unset($_SESSION['error']);
}
?>

<form method="POST">
    <p>Book Title: <input type="text" name="book_title"></p>
    <p>Reviewer Name: <input type="text" name="reviewer_name"></p>
    <p>Rating (1-5): <input type="text" name="rating"></p>
    <p>Comments: <textarea name="comments"></textarea></p>
    <p>
        <input type="submit" value="Add">
        <input type="submit"