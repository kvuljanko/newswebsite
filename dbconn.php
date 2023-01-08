<?php

	$host = 'host';
	$username = 'user';
	$password = 'pass';
	$dbname = 'db';
	# Connect to MySQL database
	$MySQL = mysqli_connect($host, $username, $password, $dbname) or die('Error connecting to MySQL server.');
?>