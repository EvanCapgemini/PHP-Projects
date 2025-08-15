<?php
require_once "pdo.php";
$salt = 'XyZzy12*_';
$stored_hash = hash('md5', $salt . 'php123');
$failure = false;

if (isset($_POST['who']) && isset($_POST['pass'])) {
    if (strlen($_POST['who']) < 1 || strlen($_POST['pass']) < 1) {
        $failure = "Email and password are required";
    } elseif (strpos($_POST['who'], '@') === false) {
        $failure = "Email must have an at-sign (@)";
    } else {
        $check = hash('md5', $salt . $_POST['pass']);
        if ($check == $stored_hash) {
            error_log("Login success " . $_POST['who']);
            header("Location: autos.php?name=" . urlencode($_POST['who']));
            return;
        } else {
            error_log("Login fail " . $_POST['who'] . " $check");
            $failure = "Incorrect password";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Evan Elijah Mendonsa</title>
</head>
<body>
<h1>Please Log In</h1>
<?php
if ($failure !== false) {
    echo('<p style="color: red;">' . htmlentities($failure) . "</p>");
}
?>
<form method="POST">
<label for="who">Email</label>
<input type="text" name="who" id="who"><br/>
<label for="pass">Password</label>
<input type="text" name="pass" id="pass"><br/>
<input type="submit" value="Log In">
</form>
<p><!-- Hint: The password is php123 --></p>
</body>
</html>