<!--
Stefan Grulovic (20150280) -> CS325 Midterm Project
-->
<?php
session_start();

if(!isset($_SESSION['login'])){
	header("Refresh:0; url=index.php");	
}else{
	$user = $_SESSION['login'];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
       <title>Planner</title>
       <link rel="stylesheet" type="text/css" href="style/style.css">
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
       <script>/*
       	//jquery animations
       	$(document).ready(function () {
       		$("#selected_column").hide().slideDown(1000);
       	});*/
       </script>
	</head>
	<body>
		<div class="grid">
			<nav class="grid_item">
			<a href="index.php"><h2>Planner</h2></a>
				<ul>
					<a href="calendar.php"><li><img src="images/calendar_white.png"> CALENDAR</li></a>
					<a href="contacts.php"><li><img src="images/contacts_white.png"> CONTACTS</li></a>
					<a href="lists.php"><li><img src="images/lists_white.png"> LISTS</li></a>
					<a href="notes.php"><li class="active"><img src="images/notes_white.png"> NOTES</li></a>
					<?php
						if(isset($_SESSION['login'])){
							echo '<a class="logout" onclick="return logout_alert();"  href="index.php?action=logout"><div id="logout" >LOGOUT</div></a>'; 
						}
						if($_GET['action'] == "logout"){
							session_destroy();
							header("Refresh:0; url=index.php");	
						}
					?>
				</ul>
			</nav>

			<div class="grid_item" id="primary_column">
			<h2>Notes</h2>
				<?php
					//including the notes functions -> where the function are stored
					include_once("notes_functions.php");
					//display function
					notes::display_notes();
				?>
			</div>

			<div class="grid_item" id="selected_column">
			<?php
				//displayinh the respected notes header
				echo "<h2>Edit Note (".$_GET["edit"]."):</h2>";
			?>
			
			<form method="post">
        		<h3>New Title:</h3>
        		<input type="text" name="title" placeholder="Enter new title here..." autofocus><br>
        		<h3>New Note:</h3>
        		<textarea name="note" placeholder="Enter new note here..." wrap="soft"></textarea><br>

        		<input type="submit" name="submit">
    		</form>

			<?php
				//if submit is clicked
				if(isset($_POST['submit'])){
					$edit_note = $_GET["edit"];
					$new_title = $_POST['title'];
					$new_note = $_POST['note'];

					//send the new edited information for the specifc note
					notes::edit_note($edit_note, $new_title, $new_note);
				}
			?>
			</div>

		</div>
	</body>
</html>
