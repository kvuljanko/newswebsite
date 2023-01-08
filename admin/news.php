<?php 
	
	#Add news
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'add_news') {
		$_SESSION['message'] = '';
		# htmlspecialchars — Convert special characters to HTML entities
		# http://php.net/manual/en/function.htmlspecialchars.php
		$query  = "INSERT INTO posts (title, subtitle, post_text, comment, category_id, user_id, created_at, updated_at, archive)";
		$query .= " VALUES ('" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', '" . htmlspecialchars($_POST['subtitle'], ENT_QUOTES) . "', '" . htmlspecialchars($_POST['post_text'], ENT_QUOTES) . "', '" . htmlspecialchars($_POST['comment'], ENT_QUOTES) . "', " . "1" . ", " . $_SESSION['user']['id'] . ", " . "now()" . "," . "now()" . ", '" . $_POST['archive'] . "')";
		
		$result = @mysqli_query($MySQL, $query);
		
		$ID = mysqli_insert_id($MySQL);
		
		# picture
        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			# strtolower - Returns string with all alphabetic characters converted to lowercase. 
			# strrchr - Find the last occurrence of a character in a string
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
			
            $_picture = $ID . '-' . rand(1,100) . $ext;
			copy($_FILES['picture']['tmp_name'], "images/".$_picture);
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
				$_query  = "UPDATE posts SET image='" . $_picture . "'";
				$_query .= " WHERE id=" . $ID . " LIMIT 1";
				$_result = @mysqli_query($MySQL, $_query);
				$_SESSION['message'] .= '<p>You successfully added picture.</p>';
			}
        }
		
		$_SESSION['message'] .= '<p>You successfully added post!</p>';
		
		# Redirect
		header("Location: index.php?menu=6&action=2");
	}
	
	# Update news
	if (isset($_POST['_action_']) && $_POST['_action_'] == 'edit_news') {
		$query  = "UPDATE posts SET title='" . htmlspecialchars($_POST['title'], ENT_QUOTES) . "', subtitle='" . htmlspecialchars($_POST['subtitle'], ENT_QUOTES) . "', archive='" . $_POST['archive'] . "', post_text='" . htmlspecialchars($_POST['post_text'], ENT_QUOTES) . "', comment='" . htmlspecialchars($_POST['comment'], ENT_QUOTES) . "'";
		$query .= " WHERE id=" . (int)$_POST['edit'];
        $query .= " LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
		# picture
        if($_FILES['picture']['error'] == UPLOAD_ERR_OK && $_FILES['picture']['name'] != "") {
                
			# strtolower - Returns string with all alphabetic characters converted to lowercase. 
			# strrchr - Find the last occurrence of a character in a string
			$ext = strtolower(strrchr($_FILES['picture']['name'], "."));
            
			$_picture = (int)$_POST['edit'] . '-' . rand(1,100) . $ext;
			copy($_FILES['picture']['tmp_name'], "images/".$_picture);
			
			
			if ($ext == '.jpg' || $ext == '.png' || $ext == '.gif') { # test if format is picture
				$_query  = "UPDATE news SET image='" . $_picture . "'";
				$_query .= " WHERE id=" . (int)$_POST['edit'] . " LIMIT 1";
				$_result = @mysqli_query($MySQL, $_query);
				$_SESSION['message'] .= '<p>You successfully added picture.</p>';
			}
        }
		
		$_SESSION['message'] = '<p>You successfully changed post!</p>';
		echo $query;
		
		# Redirect
		header("Location: index.php?menu=6&action=2");
	}
	# End update news
	
	# Delete news
	if (isset($_GET['delete']) && $_GET['delete'] != '') {
		
		# Delete picture
        $query  = "SELECT image FROM posts";
        $query .= " WHERE id=".(int)$_GET['delete']." LIMIT 1";
        $result = @mysqli_query($MySQL, $query);
        $row = @mysqli_fetch_array($result);
        @unlink("images/".$row['picture']); 
		
		# Delete news
		$query  = "DELETE FROM posts";
		$query .= " WHERE id=".(int)$_GET['delete'];
		$query .= " LIMIT 1";
		$result = @mysqli_query($MySQL, $query);

		$_SESSION['message'] = '<p>You successfully deleted post!</p>';
		
		# Redirect
		header("Location: index.php?menu=6&action=2");
	}
	# End delete news
	
	
	#Show news info
	if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM posts";
		$query .= " WHERE id=".$_GET['id'];
		$query .= " ORDER BY created_at DESC";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		print '
		<h2>News overview</h2>
		<div class="news">
			<img src="images/' . $row['imagex768'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
			<h3>' . $row['comment'] . '</h3>
			<h1>' . $row['title'] . '</h1>
			<h2>' . $row['subtitle'] . '</h2><br/>
			<p>' . $row['post_text'] . "</p>" . '
			<time datetime="' . $row['created_at'] . '">' . pickerDateToMysql($row['created_at']) . '</time>
			<hr>
		</div>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	
	#Add news 
	else if (isset($_GET['add']) && $_GET['add'] != '') {
		
		print '
		<h2>Add news</h2>
		<form action="" id="news_form" name="news_form" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="add_news">
			
			<label for="title">Title *</label>
			<input type="text" id="title" name="title" placeholder="Post title" required>

			<label for="subtitle">Subtitle *</label>
			<textarea id="subtitle" name="subtitle" placeholder="Post subtitle" required></textarea>
				
			<label for="post_text">Post text *</label>
			<textarea id="post_text" name="post_text" placeholder="Post text" required></textarea>

			<label for="comment">Comment *</label>
			<textarea id="comment" name="comment" placeholder="Comment" required></textarea>

			<label for="image">Picture</label>
			<input type="file" id="image" name="image">
						
			<label for="archive">Archive:</label><br />
            <input type="radio" name="archive" value="Y"> YES &nbsp;&nbsp;
			<input type="radio" name="archive" value="N" checked> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	#Edit news
	else if (isset($_GET['edit']) && $_GET['edit'] != '') {
		$query  = "SELECT * FROM posts";
		$query .= " WHERE id=".$_GET['edit'];
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
		$checked_archive = false;

		print '
		<h2>Edit news</h2>
		<form action="" id="news_form_edit" name="news_form_edit" method="POST" enctype="multipart/form-data">
			<input type="hidden" id="_action_" name="_action_" value="edit_news">
			<input type="hidden" id="edit" name="edit" value="' . $row['id'] . '">
			
			<label for="title">Title *</label>
			<input type="text" id="title" name="title" value="' . $row['title'] . '" placeholder="News title.." required>

			<label for="subtitle">Subtitle *</label>
			<textarea id="subtitle" name="subtitle" placeholder="Post subtitle" required>' . $row['subtitle'] . '</textarea>
				
			<label for="post_text">Post text *</label>
			<textarea id="post_text" name="post_text" placeholder="Post text" required>' . $row['post_text'] . '</textarea>

			<label for="comment">Comment *</label>
			<textarea id="comment" name="comment" placeholder="Comment" required>' . $row['comment'] . '</textarea>

			<label for="picture">Picture</label>
			<input type="file" id="picture" name="picture">
			<br/>
			<label for="archive">Archive:</label><br />
            <input type="radio" name="archive" value="Y"'; if($row['archive'] == 'Y') { echo ' checked="checked"'; $checked_archive = true; } echo ' /> YES &nbsp;&nbsp;
			<input type="radio" name="archive" value="N"'; if($checked_archive == false) { echo ' checked="checked"'; } echo ' /> NO
			
			<hr>
			
			<input type="submit" value="Submit">
		</form>
		<p><a href="index.php?menu='.$menu.'&amp;action='.$action.'">Back</a></p>';
	}
	else {
		print '
		<h2>News</h2>
		<div id="news">
			<table>
				<thead>
					<tr>
						<th width="16"></th>
						<th width="16"></th>
						<th width="16"></th>
						<th>Title</th>
						<th>Description</th>
						<th>Date</th>
						<th width="16"></th>
					</tr>
				</thead>
				<tbody>';
				$query  = "SELECT * FROM posts";
				$query .= " ORDER BY created_at DESC";
				$result = @mysqli_query($MySQL, $query);
				while($row = @mysqli_fetch_array($result)) {
					print '
					<tr>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;id=' .$row['id']. '"><img src="img/user.png" alt="user"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;edit=' .$row['id']. '"><img src="img/edit.png" alt="uredi"></a></td>
						<td><a href="index.php?menu='.$menu.'&amp;action='.$action.'&amp;delete=' .$row['id']. '"><img src="img/delete.png" alt="obriši"></a></td>
						<td>' . $row['title'] . '</td>
						<td>';
						if(strlen($row['subtitle']) > 160) {
                            echo substr(strip_tags($row['subtitle']), 0, 160).'...';
                        } else {
                            echo strip_tags($row['subtitle']);
                        }
						print '
						</td>
						<td>' . pickerDateToMysql($row['created_at']) . '</td>
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
			<a href="index.php?menu=' . $menu . '&amp;action=' . $action . '&amp;add=true" class="AddLink">Add news</a>
		</div>';
	}
	
	# Close MySQL connection
	@mysqli_close($MySQL);
?>