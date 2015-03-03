<?php
// Global variables - Credential for login
// Real password
$unHashedPass = 'izzuddin92';
// $unHashedPass (password) that has been hashed
$hashedPass = '$2y$09$JTOGJnHX4JZR6orTbmNvyObNMMZki2p4pcuMGMcU5ROJ9UaHMRZCm';
// User name used
$userName = 'user01';

// Function to get user IP and Encode
function encryptIP() {
	// Get user IP
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	// Encode IP with Base64 and 
	for($i = 0; $i < 5; $i++)
	{
		// Reverse a Encoded IP
		$str = strrev(base64_encode($ip));
	}
	
	return $str;
}

// Function to create a password hash
function cryptWord($pass) {
	// Salt will be generated randomly
	$options = [
    'cost' => 9,
	'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
	];
	// PASSWORD_BCRYPT will use CRYPT_BLOWFISH
	$hashed = password_hash($pass, PASSWORD_BCRYPT, $options);
	return $hashed;
}

// Function to login - verify credential
function submitLogin() {
	// Random use encryptIP to get and encrypt user IP
	$random = encryptIP();
	// Get password from post request (form)
	$pass = $_POST['password'];
	// Get user and hashed password from Global Variable
	$user = $GLOBALS['userName'];
	$hashed = $GLOBALS['hashedPass'];
	// Check user name, password
	if($user != $_POST['user']) {
		$msg = "Wrong username!";
	} else if(!password_verify($pass, $hashed)) {
		$msg = "Wrong password";
	} else {
		// If user select to Remember Me
		if (isset($_POST['remember'])) {
			// Expire time will be set to one day / 24hours
			$expire = time()+86400;
            setcookie('user', $user, $expire, '/');
            setcookie('token', $random, $expire, '/');
        } else {
			// Expire time default
            setcookie('user', $user, false, '/');
            setcookie('token', $random, false, '/');
        }
		// Start and create Session based on $random
		session_start();
		$_SESSION['token'] = $random;
		// Redirect to index.php
		header('Location: index.php');
	}
	// Display any error occurred
	echo 'Login Error: ' . $msg;
}

// Function to check Session and Cookie
function checkLogin() {
	// Start session
	session_start();
	// Check for Cookie and Session availability 
	if(isset($_COOKIE['token']) && isset($_SESSION['token']) && isset($_COOKIE['user'])) {
		$user = $_COOKIE['user'];
		$newip = encryptIP();
		$login = 'true';
		// Check Session-Cookie integrity and user IP (Encrypted)
		if($_SESSION['token'] != $_COOKIE['token']){
			$msg = 'Cookie and Session mismatched!';
		} elseif ($newip != $_COOKIE['token']){
			$msg = 'Login IP and Current IP mismatched!';
		} else {
			$msg = 'Welcome '.$user.'!';
		}
	} else {
		// User still not login or no Cookie or Session found
		$msg = 'Please login!';
		$login = 'false';
	}
	// Display error 
	echo $msg;
	// Return value if user already login or not
	return $login;
}

// Function to logout - destroy all Cookies and Session
function processLogout() {
	// Start Session
	session_start();
	// Destroy all Cookies from this site
	if(isset($_SERVER['HTTP_COOKIE'])) {
		$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
		$expire = time()+86400;
		foreach($cookies as $cookie) {
			$parts = explode('=', $cookie);
			$name = trim($parts[0]);
			setcookie($name, '', $expire);
			setcookie($name, '', $expire, '/');
		}
	}
	// Destroy all Session from this site
	$_SESSION = array();
	session_destroy();
	unset($_SESSION);
	// Redirect to login.php
	header('Location: login.php');
}


?>
