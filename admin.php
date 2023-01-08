<?php 

	if (($_SESSION['user']['role'] != 1) && ($_SESSION['user']['role'] != 2)) {
		$_SESSION['message'] = '<p>You do not have access to Admin portal!</p>';
	} else if ($_SESSION['user']['role'] == 1) {
		if ($_SESSION['user']['valid'] == 'true') {
			if (!isset($action)) { $action = 1; }
			print '
			<h1>Administration</h1>
			<div id="admin">
				<ul>
					<li><a href="index.php?menu=6&amp;action=1">Users</a></li>
					<li><a href="index.php?menu=6&amp;action=2">News</a></li>
				</ul>';
				# Admin Users
				if ($action == 1) { include("admin/users.php"); }
				
				# Admin News
				else if ($action == 2) { include("admin/news.php"); }
			print '
			</div>';
		}
		else {
			$_SESSION['message'] = '<p>Please register or login using your credentials!</p>';
			header("Location: index.php?menu=5");
		}
	} else if ($_SESSION['user']['role'] == 2) {
		if ($_SESSION['user']['valid'] == 'true') {
			if (!isset($action)) { $action = 2; }
			print '
			<h1>Administration</h1>
			<div id="admin">
				<ul>
					<li><a href="index.php?menu=6&amp;action=2">News</a></li>
				</ul>';
				# Admin News
				if ($action == 2) { include("admin/news.php"); }
			print '
			</div>';
		}
		else {
			$_SESSION['message'] = '<p>Please register or login using your credentials!</p>';
			header("Location: index.php?menu=5");
		}
	}

?>