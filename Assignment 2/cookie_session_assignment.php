<?php
session_start();

// Set a cookie if it's not already set
if (!isset($_COOKIE['wa4e_secret_cookie'])) {
    setcookie('wa4e_secret_cookie', 'super_secret_cookie_value', time() + 3600);
}

// Get cookie and session values
$cookie_value = $_COOKIE['wa4e_secret_cookie'] ?? '';
$session_id = session_id();

$submitted_cookie = $_POST['cookie'] ?? '';
$submitted_session = $_POST['session'] ?? '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($submitted_cookie === $cookie_value && $submitted_session === $session_id) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cookie and Session Assignment</title>
</head>
<body>
<h1>Welcome Evan Elijah Mendonsa</h1>
<p>This application has stored a cookie named <strong>wa4e_secret_cookie</strong> in your browser.</p>
<p>You must also figure out your session identifier (PHPSESSID).</p>

<?php if ($success): ?>
    <p style="color: green;">✅ Success! You entered the correct values.</p>
<?php elseif ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
    <p style="color: red;">❌ Incorrect values. Try again.</p>
<?php endif; ?>

<form method="POST">
    <label for="cookie">Cookie Value:</label><br>
    <input type="text" name="cookie" id="cookie" size="50"><br><br>

    <label for="session">Session ID:</label><br>
    <input type="text" name="session" id="session" size="50"><br><br>

    <input type="submit" value="Submit">
</form>
</body>
</html>
