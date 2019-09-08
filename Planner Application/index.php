<?php
////////////////////////////////////////////////////////////////////////////////////////////////
// Stefan Grulovic (20150280) - CS325 Final Project
////////////////////////////////////////////////////////////////////////////////////////////////
// INDEX PAGE - calls index functions functions
////////////////////////////////////////////////////////////////////////////////////////////////
	//session in order to check wether the user has logged in
	include_once("index_functions.php");
	
	//setcookie('login', $_POST['username'], time()-1800);

	if(isset($_COOKIE['username'])){
		$username = $_COOKIE['username'];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
       <title>Planner</title>
       <link rel="stylesheet" type="text/css" href="style/style.css">
       <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
       <script>
       	//jquery animations
       	$(document).ready(function () {
       		/*$("nav").hide().slideDown(1500);
       		$("#primary_column").hide().slideDown(1000);
       		$("#selected_column").hide().slideDown(500);*/
       		$("#register_form").hide();

       		$("#register").click(function(){
				$("#register_form").slideToggle(500);
			});
       	});
       	function logout_alert(){
		  return confirm("Are you sure you want to logout?");
		}
       </script>
	</head>
	<body>
		<div class="grid">
			<nav class="grid_item">
			<a href="index.php"><h2>PLANNER</h2></a>
				<ul>
					<a href="calendar.php"><li><img src="images/calendar_white.png"> CALENDAR</li></a>
					<a href="contacts.php"><li><img src="images/contacts_white.png"> CONTACTS</li></a>
					<a href="lists.php"><li><img src="images/lists_white.png"> LISTS</li></a>
					<a href="notes.php"><li><img src="images/notes_white.png"> NOTES</li></a>
					<?php
						if(isset($_SESSION['login'])){
							echo '<a class="logout" onclick="return logout_alert();"  href="index.php?action=logout"><div id="logout" >LOGOUT</div></a>'; 
						}
						if(isset($_GET['action'])){
							if($_GET['action']=="logout"){
								session_destroy();
								header("Refresh:0; url=index.php");	
							}
						}
					?>
				</ul>
			</nav>

			<div class="grid_item" id="selected_column">
				<h2>LOGIN</h2>
				<form method="post">
					<?php
						if (!isset($_POST['submit']) && !isset($_SESSION['login'])) {
	  						$username = (isset($_COOKIE['username'])) ? $_COOKIE['username'] : '';
					?>
					<h3>Username:</h3>
					<input type="text" name="username" placeholder="Enter username here..." value="<?php echo $username; ?>" required autofocus>
					<h3>Password:</h3>
					<input type="password" name="password" placeholder="Enter password here..." required>
					
      				<h3>Remember me<input type="checkbox" name="remember" value="Remember me:" checked/></h3>
      				

					<input type="submit" name="submit">
					</form>
					<?php

						}else if(isset($_SESSION['login'])){
							echo '<div class="message">Welcome back '.$username.'!</div>';
						}else{
							$username = $_POST['username'];
							$password = $_POST['password'];

							login_check($username, $password);
						}
					?>
			</div>

			<div class="grid_item" id="primary_column">
				<?php
					if(!isset($_POST['submit'])){
						if(!isset($_SESSION['login'])){
				?>
				<a><h2 id="register">Register</h2></a>

				<form method="post" id="register_form">
					<h4>Username:</h4>
					<input type="text" name="reg_username" placeholder="Enter username here..." required>
					<h4>Name:</h4>
					<input type="text" name="reg_name" placeholder="Enter name here..." required>
					<h4>Last Name:</h4>
					<input type="text" name="reg_last_name" placeholder="Enter last name here..." required>
					<h4>Password:</h4>
					<input type="password" name="reg_password" placeholder="Enter password here..." required>
					<input type="submit" name="submit_register" value="Register">
				</form>

				<?php
						}
					}

					if(isset($_POST['submit_register'])){
						$user = $_POST['reg_username'];
						$name = $_POST['reg_name'];
						$lname = $_POST['reg_last_name'];
						$pass = $_POST['reg_password'];

						register($user, $name, $lname, $pass);
					}
				?>
			</div>

		</div>
	</body>
</html>
