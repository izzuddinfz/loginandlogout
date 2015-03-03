<!DOCTYPE html>
<html>
<body>
<?php
// Require function.php for function
include 'function.php';

// If user Submit form
if(isset($_POST['login'])) {
	// Call submitLogin to check credential 
	submitLogin();
}
?>
<!-- Credential for login can be found at function.php -->
<h1>Login Form</h1>
<form method="post" autocomplete="off" novalidate>
User name:<br>
<input type="text" name="user" value="user01">
<br>
Password:<br>
<input type="password" name="password" value="izzuddin92">
<br>
<!-- Remember me will keep cookies for one day -->
<input type="checkbox" name="remember">Remember me
<br><br>
<input type="submit" value="Login" name="login">
</form>
<a href="index.php">Back to home</p>
</body>
</html>
