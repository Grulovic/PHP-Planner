<?php
/*
Stefan Grulovic (20150280) -> CS325 Midterm Project
*/
session_start();

if(!isset($_SESSION['login'])){
	header("Refresh:0; url=index.php");	
}else{
	$user = $_SESSION['login'];
}

//output buffering
ob_start( );
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
       		$("#primary_column").hide().slideDown(1000);
       		
       	});*/
       </script>
	</head>
	<body>
		<div class="grid">
			<nav class="grid_item">
			<a href="index.php"><h2>Planner</h2></a>
				<ul>
					<a href="calendar.php"><li class="active"><img src="images/calendar_white.png"> CALENDAR</li></a>
					<a href="contacts.php"><li><img src="images/contacts_white.png"> CONTACTS</li></a>
					<a href="lists.php"><li><img src="images/lists_white.png"> LISTS</li></a>
					<a href="notes.php"><li><img src="images/notes_white.png"> NOTES</li></a>
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

			<div class="grid_item" id="selected_column">
				<?php
					//including the calendar functions -> where the function are stored
					include_once("calendar_functions.php");
					//display functions
					calendar::display();
				?>				
			</div>

			<div class="grid_item" id="primary_column">
				<?php
				//grabbing the info value from the url -> displaying the respected date info/note
				if($_GET['info']!=null){
					echo '<h2>'.$_GET['info'].'</h2>';
					//function to get specific date info/note
					calendar::get_day_info($_GET['info']);

				//grabbing the delete value from the url -> deleting the respected date info/note
				}else if($_GET['delete']!=null){
					//function to delete specific date info/note
					calendar::delete_day_info($_GET['delete']);

				}
				?>
			</div>

		</div>
	</body>
</html>
<?php
//cleaning the buffer
ob_end_flush( );
?>