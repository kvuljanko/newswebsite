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

	</header>


</body>
</html>

<?php 
    require 'dbconn.php';
	print '
	<h1>Sign In form</h1>
	<div id="signin">';
	
	if ($_POST['_action_'] == FALSE) {
		print '
		<form action="" name="myForm" id="myForm" method="POST">
			<input type="hidden" id="_action_" name="_action_" value="TRUE">
			<label for="username">Username:*</label>
			<input type="text" id="username" name="username" value=""  required>
            </br>
			<label for="password">Password:*</label>
			<input type="password" id="password" name="password" value=""  required>
            </br>
			<input type="submit" value="Submit">
		</form>';
	}
	else if ($_POST['_action_'] == TRUE) {
		

		$query  = "SELECT * FROM users";
		$query .= " WHERE username='" .  $_POST['username'] . "'";

		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result, MYSQLI_ASSOC);

		if (password_verify($_POST['password'], $row['password'])) {
			#password_verify https://secure.php.net/manual/en/function.password-verify.php
            echo "<br/>";

			$_SESSION['user']['valid'] = 'true';
			$_SESSION['user']['id'] = $row['id'];
			# 1 - administrator; 2 - editor; 3 - user
			$_SESSION['user']['role'] = $row['role_id']; #role_id
			$_SESSION['user']['firstname'] = $row['first_name'];
			$_SESSION['user']['lastname'] = $row['last_name'];
			$_SESSION['message'] = '<p>Dobrodošli, ' . $_SESSION['user']['firstname'] . ' ' . $_SESSION['user']['lastname'] . '</p>';
			# Redirect to admin website
            if ($_SESSION['user']['role'] == 1){
                header("Location: index.php?menu=6");
            } else if ($_SESSION['user']['role'] == 2) {
                header("Location: index.php?menu=6&action=2");
            } else {
                header("Location: index.php?menu=6");
            }
            echo $_SESSION['message'];
		}
		
		# Bad username or password
		else {
			unset($_SESSION['user']);
			header("Location: index.php?menu=5");
			$_SESSION['message'] = '<p>You entered wrong email or password!</p>';
		}
        @mysqli_close($MySQL);
	}

	print '
	</div>';
?>