<?php 
	
	# Update user profile
	if (isset($_POST['edit']) && $_POST['_action_'] == 'TRUE') {
		$query  = "UPDATE users SET first_name='" . $_POST['firstname'] . "', last_name='" . $_POST['lastname'] . "', email='" . $_POST['email'] . "', username='" . $_POST['username'] .  "', archive='" . $_POST['archive'] . "', role_id=" . $_POST['role_id'];
        $query .= " WHERE id=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		# Close MySQL connection
		@mysqli_close($MySQL);
		
		$_SESSION['message'] = '<p>You successfully changed user profile!</p>';
		
		# Redirect
		header("Location: index.php?menu=6&action=1");
	}
	# End update user profile
	
	# Delete user profile
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
	
		$query  = "DELETE FROM users";
		$query .= " WHERE id=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>You successfully deleted user profile!</p>';
		
		# Redirect
		header("Location: index.php?menu=6&action=1");
	}
	# End delete user profile
	
	
	#Show user info
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM users INNER JOIN roles ON roles.id = users.role_id";
		$query .= " WHERE users.id=".$_GET['id'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>User profile</h2>
		<p><b>First name:</b> ' . $row['first_name'] . '</p>
		<p><b>Last name:</b> ' . $row['last_name'] . '</p>
		<p><b>Username:</b> ' . $row['username'] . '</p>
		<p><b>Date:</b> ' . pickerDateToMysql($row['created_at']) . '</p>
		<p><b>Rola:</b> ' . $row['role_name'] . '</p>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	#Edit user profile
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		if ($_SESSION['user']['role'] == 1 ) {
			$query  = "SELECT * FROM users";
			$query .= " WHERE id=".$_GET['edit'];
			$result = @mysqli_query($MySQL, $query);
			$row = @mysqli_fetch_array($result);
			$checked_archive = false;
			
			print '
			<h2>Edit user profile</h2>
			<form action="" id="registration_form" name="registration_form" method="POST">
				<input type="hidden" id="_action_" name="_action_" value="TRUE">
				<input type="hidden" id="edit" name="edit" value="' . $_GET['edit'] . '">
				
				<label for="fname">First Name *</label>
				<input type="text" id="fname" name="firstname" value="' . $row['first_name'] . '" placeholder="First name" required>

				<label for="lname">Last Name *</label>
				<input type="text" id="lname" name="lastname" value="' . $row['last_name'] . '" placeholder="Last name" required>
					
				<label for="email">Your E-mail *</label>
				<input type="email" id="email" name="email"  value="' . $row['email'] . '" placeholder="Email" required>
				
				<label for="username">Username *</small></label>
				<input type="text" id="username" name="username" value="' . $row['username'] . '"  placeholder="Username" required><br>
				
				<label for="role_id">Select a new role:</label><br>
				<select name="role_id">
				  <option value="1">Admin</option>
				  <option value="2">Urednik</option>
				  <option value="3">User</option>
				</select><br>
				

				<label for="archive">Archive:</label><br />
				<input type="radio" name="archive" value="Y"'; if($row['archive'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> YES &nbsp;&nbsp;
				<input type="radio" name="archive" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NO
				
				<hr>
				
				<input type="submit" value="Submit">
			</form>
			<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
		}
		else {
			print '<p>Zabranjeno</p>';
		}
	}
	else {
		print '
		<h2>List of users</h2>
		<div id="users">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>First name</th>
						<th>Last name</th>
						<th>E mail</th>
						<th>Rola</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT users.id AS uid, users.*, roles.* FROM users INNER JOIN roles ON roles.id = users.role_id";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['uid']. '"><img src="img/user.png" alt="user"></a></td>
						<td>';
							if ($_SESSION['user']['role'] == 1) {
								print '<a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['uid']. '"><img src="img/edit.png" alt="uredi"></a></td>';
							}
						print '
						<td>';
							if ($_SESSION['user']['role'] == 1) {
								print '<a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['uid']. '"><img src="img/delete.png" alt="obriši"></a>';
							}
						print '	
						</td>
						<td><strong>' . $row['first_name'] . '</strong></td>
						<td><strong>' . $row['last_name'] . '</strong></td>
						<td>' . $row['email'] . '</td>
						<td>' . $row['role_name'] . '</td>
						<td>';
							if ($row['archive'] == 'Y') { print '<img src="img/inactive.png" alt="" title="" />'; }
                            else if ($row['archive'] == 'N') { print '<img src="img/active.png" alt="" title="" />'; }
						print '
						</td>
					</tr>';
				}
			print '
				</tbody>
			</table>
		</div>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>