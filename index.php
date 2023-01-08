<?php
	// datoteka index.php
	define('__APP__', TRUE);

	session_start();
	include ("dbconn.php");
    include_once("functions.php");

    if(isset($_GET['menu'])) { $menu   = (int)$_GET['menu']; }
	if(isset($_GET['action'])) { $action   = (int)$_GET['action']; }
    if(!isset($_POST['_action_']))  { $_POST['_action_'] = FALSE;  }

	if (!isset($menu)) { $menu = 1; }


print '
<!DOCTYPE html>
<html>

	<head>
		<link rel="stylesheet" href="style.css">		
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta name="description" content="">
		<meta name="keywords" content="">
		<meta name="author" content="Karlo Vuljanko">
		
		<title>Vijesti</title>
	</head>

	<body>	
	<header>
		<nav>';
			include("menu.php");
			print'
		</nav>
	</header>

	<main>';


	if (isset($_SESSION['message'])) {
		print $_SESSION['message'];
		unset($_SESSION['message']);
	}

	if (!isset($menu) || $menu == 1) { include("news.php"); }
	else if ($menu == 2) { include("contact.php"); }
	else if ($menu == 3) { include("about.php"); }
	else if ($menu == 4) { include("register.php"); }
	else if ($menu == 5) { include("signin.php"); }
	else if ($menu == 6) { include("admin.php"); }

	print '

	</body>
</html>
';
?>




