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

if (isset($_POST['book_title']) && isset($_POST['reviewer_name']) && isset($_POST['rating']) && isset($_POST['comments'])) {
    if (strlen($_POST['book_title']) < 1 || strlen($_POST['reviewer_name']) < 1) {
        $message = "Book title and reviewer name are required";
    } elseif (!is_numeric($_POST['rating'])) {
        $message = "Rating must be numeric";
    } else {
        $stmt = $pdo->prepare('INSERT INTO reviews (book_title, reviewer_name, rating, comments) VALUES (:bt, :rn, :rt, :cm)');
        $stmt->execute([
            ':bt' => $_POST['book_title'],
            ':rn' => $_POST['reviewer_name'],
            ':rt' => $_POST['rating'],
            ':cm' => $_POST['comments']
        ]);
        $message = "Review submitted";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Evan Mendonsa Book Reviews</title>
</head>
<body>
<h1>Book Reviews for <?= htmlentities($_GET['name']) ?></h1>

<?php
if ($message !== false) {
    echo('<p style="color: green;">' . htmlentities($message) . "</p>\n");
}
?>

<form method="POST">
<p>Book Title: <input type="text" name="book_title"></p>
<p>Reviewer Name: <input type="text" name="reviewer_name"></p>
<p>Rating (1-5): <input type="text" name="rating"></p>
<p>Comments: <textarea name="comments"></textarea></p>
<p><input type="submit" value="Submit Review">
<input type="submit" name="logout" value="Logout"></p>
</form>

<h2>All Reviews</h2>
<ul>
<?php
$stmt = $pdo->query("SELECT book_title, reviewer_name, rating, comments FROM reviews");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "<li><strong>" . htmlentities($row['book_title']) . "</strong> by " . htmlentities($row['reviewer_name']) .
         " - Rating: " . htmlentities($row['rating']) . "<br/>" .
         "Comments: " . htmlentities($row['comments']) . "</li><br/>";
}
?>
</ul>
</body>
</html>
