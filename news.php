<?php

    include 'dbconn.php';
    $menu = 1;
    

    if (!isset($_GET['id'])) {

        $query = "SELECT * FROM posts ORDER BY created_at DESC";
        $result = @mysqli_query($MySQL, $query);
        $prvi = @mysqli_fetch_array($result);
        print '
        <a href="index.php?id=' .$prvi['id']. '"> 
            <div class="card" >
                <h1>' . $prvi['title'] . "</h1>". '
                <h3>' . $prvi['comment'] . "</h3>" .'
				<img class="img-main" src="images/' . $prvi['imagex768'] . '" alt="' . $prvi['title'] . '" title="' . $prvi['title'] . '">
                <h2>' . $prvi['subtitle'] . "</h2>" . '<br />
                <p>' . $prvi['post_text'] . "</p>" . '
            </div>
        </a>';
		
        print '
        <div class="wrap">';
            while($row = @mysqli_fetch_array($result)) {
                $vijest = $row['id'];
                print '
                    <a class="card-container" href="index.php?id=' .$row['id']. '"> 
                        <div class="card-container">
                            <div class="card">
                                <h1>' . $row['title'] . "</h1>". '
                                <h3>' . $row['comment'] . "</h3>" . '
                                <img class="img-main" src="images/' . $row['imagex768'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
                                <h2>' . $row['subtitle'] . "</h2>" . '
                            </div>
                        </div>
                    </a>';
            }
        print '
        </div>';
    }

    	#Show news info
	else if (isset($_GET['id']) && $_GET['id'] != '') {
		$query  = "SELECT * FROM posts";
		$query .= " WHERE id=".$_GET['id'];
		$query .= " ORDER BY created_at DESC";
		$result = @mysqli_query($MySQL, $query);
		$row = @mysqli_fetch_array($result);
        echo "";
		print '
        <a href="index.php?menu=6&amp;action=2&amp;edit=' .$row['id']. '"class="img-edit""><img src="img/edit.png" alt="uredi"></a>
        <a href="index.php?menu=6&amp;action=2&amp;delete=' .$row['id']. '"class="img-edit""><img src="img/delete.png" alt="obriši"></a>
		<div class="news">
			<img src="images/' . $row['imagex768'] . '" alt="' . $row['title'] . '" title="' . $row['title'] . '">
            <h3>' . $row['comment'] . '</h3>
			<h1>' . $row['title'] . '</h1>
			<h2>' . $row['subtitle'] . '</h2><br/>
			<p>' . $row['post_text'] . "</p>" . '
			<time datetime="' . $row['created_at'] . '">' . pickerDateToMysql($row['created_at']) . '</time>
			<hr>
		</div>
		<p><a href="index.php?menu=1">Back</a></p>';
	}
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


    
    @mysqli_close($MySQL);

?>