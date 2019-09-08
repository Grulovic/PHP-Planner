<?php
////////////////////////////////////////////////////////////////////////////////////////////////
// Stefan Grulovic (20150280) - CS325 Final Project
////////////////////////////////////////////////////////////////////////////////////////////////
// LIST PAGE - calls list functions
////////////////////////////////////////////////////////////////////////////////////////////////
	//if they have not refresh to login page
	if(!isset($_SESSION['login'])){
		//header("Refresh:0; url=index.php");	
	}

	include_once("lists_functions.php");

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
       <title>Planner</title>
       <link rel="stylesheet" type="text/css" href="style/style.css">
       <script>
		function delete_alert(){
		  return confirm("Are you sure you want to delete this list?");
		}
		function logout_alert(){
		  return confirm("Are you sure you want to logout?");
		}
		</script>
	</head>
	<body>
		<div class="grid">
			<nav class="grid_item">
			<a href="index.php"><h2>Planner</h2></a>
				<ul>
					<a href="calendar.php"><li><img src="images/calendar_white.png"> CALENDAR</li></a>
					<a href="contacts.php"><li><img src="images/contacts_white.png"> CONTACTS</li></a>
					<a href="lists.php"><li class="active"><img src="images/lists_white.png"> LISTS</li></a>
					<a href="notes.php"><li><img src="images/notes_white.png"> NOTES</li></a>
					<?php
						if(isset($_SESSION['login'])){
							echo '<a class="logout" onclick="return logout_alert();" href="index.php?action=logout"><div id="logout" >LOGOUT</div></a>'; 
						}
					?>
				</ul>
			</nav>

			<div class="grid_item" id="primary_column">
			<h2>Lists</h2>
				<?php
					//function for displaying list based on user
					lists::display_lists($_SESSION['login']);
				?>
			</div>

			<div class="grid_item" id="selected_column">
				<?php
					//if an action is clicked 
					if(isset($_GET['action'])){
						//such as delete list
						if( $_GET['action'] == "display"){
							//function for displaying points
							lists::display_points($_SESSION['login'], $_GET['list']);
						}else if($_GET['action'] == "delete_list"){
								//function for deleting a list
								lists::delete_list($_SESSION['login'], $_GET["list"]);

						//editing list
						}else if($_GET['action'] == "edit_list"){
							//grab the list
							$list = lists::get_list($_SESSION['login'], $_GET['list']);
							
							//display a form
							echo '<h2>'.$list->get_name().'</h2>';
							echo '<form method="post">';
							//for each of the list points display an input
							foreach ($list->get_points() as $key => $point) {
								$key++;
								echo '<h5>Point '.$key.'</h5>';
								
								if($key == 1){
									echo '<input type="text" name="'.$point['POINT_ID'].'" value="'.$point['POINT_CONTENT'].'" autofocus>';	
								}else{
									echo '<input type="text" name="'.$point['POINT_ID'].'" value="'.$point['POINT_CONTENT'].'">';	
								}
								
							}
							echo '<input type="submit" name="submit">
								</form>';

							//if submit is clicked
							if(isset($_POST['submit'])){

								//for each of the points
								foreach ($list->get_points() as $point) {
									//function for deleting a list
									lists::edit_list($_SESSION['login'], $_GET['list'], $point['POINT_ID'], $_POST[$point['POINT_ID']]);
								}

							}
						//if action is insert
						}else if($_GET['action'] == "insert_list"){
							//display a form
							?>
							<h2>New List</h2>
							<form method="post">
								<h3>List Name:</h3>
								<input type="text" name="list_name" placeholder="Enter name here..." autofocus>
								<input type="submit" name="submit">
							</form>
							<?php

							//if submit is called
							if(isset($_POST['submit'])){
								//grab data
								$name =  !empty($_POST['list_name']) ? '"'.$_POST['list_name'].'"' : "NULL";
								//function for adding a list
								lists::add_list($_SESSION['login'], $name);
							}
						//if action insert a point
						}else if($_GET['action'] == "insert_point"){
							//function for displaying points
							lists::display_points($_SESSION['login'], $_GET['list']);
							//display a form
							?>
							<form method="post">
								<h3>Point:</h3>
								<input type="text" name="point_content" placeholder="Enter text here..." autofocus>
								<input type="submit" name="submit">
							</form>
							<?php
							//if submit is clicked
							if(isset($_POST['submit'])){
								//grab point data
								$content =  !empty($_POST['point_content']) ? '"'.$_POST['point_content'].'"' : "NULL";
								//function for adding a point
								lists::add_point($_SESSION['login'], $_GET['list'], $content);
							}
						//if action is delete a point
						}else if($_GET['action'] == "delete_point"){
							//function for deleting a point
							lists::delete_point($_SESSION['login'], $_GET['list'], $_GET['point']);

						//if action edit point
						}else if($_GET['action'] == "edit_point"){
							//function for displaying points
							lists::display_points($_SESSION['login'], $_GET['list']);
							//display form
							?>
							<form method="post">
								<h3>Edit Point (<?php $point = lists::get_point($_SESSION['login'], $_GET['point']); echo $point[0]['POINT_CONTENT']; ?>):</h3>
								<input type="text" name="point_new_content" value="<?php $point = lists::get_point('$user', $_GET['point']); echo $point[0]['POINT_CONTENT']; ?>" placeholder="Enter new text here..." autofocus>
								<input type="submit" name="submit">
							</form>
							<?php
							//if submit is clicked 
							if(isset($_POST['submit'])){
								//grab data
								$new_content =  !empty($_POST['point_new_content']) ? '"'.$_POST['point_new_content'].'"' : "NULL";
								//function for editing a point
								lists::edit_point($_SESSION['login'], $_GET['list'], $_GET['point'], $new_content);
							}
							
						}
					}
				?>
			</div>

		</div>
	</body>
</html>