<!DOCTYPE html>
<html>
<body>
<?php
// Require function.php for function
include 'function.php';
// Call checkLogin to check Cookies and Session
$login = checkLogin();

if ($login == 'true') {
	// If user login
	?>
	<p><a href="logout.php">Logout</a>?</p>
	<?php
} else {
	// If user not login
	?>
	<p><a href="login.php">Login</a>?</p>
	<?php
}
?>
</body>
</html>
