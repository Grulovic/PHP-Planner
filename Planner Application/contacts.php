<?php
////////////////////////////////////////////////////////////////////////////////////////////////
// Stefan Grulovic (20150280) - CS325 Final Project
////////////////////////////////////////////////////////////////////////////////////////////////
// CONTACTS PAGE - calls contacts functions
////////////////////////////////////////////////////////////////////////////////////////////////
	//include contacts functions
	include_once("contacts_functions.php");

	//if they have not refresh to login screen
	if(!isset($_SESSION['login'])){
		header("Refresh:0; url=index.php");	
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
       <title>Planner</title>
       <link rel="stylesheet" type="text/css" href="style/style.css">
       <script>
		function delete_alert(){
		  return confirm("Are you sure you want to delete this contact?");
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
					<a href="contacts.php"><li class="active"><img src="images/contacts_white.png"> CONTACTS</li></a>
					<a href="lists.php"><li><img src="images/lists_white.png"> LISTS</li></a>
					<a href="notes.php"><li><img src="images/notes_white.png"> NOTES</li></a>
					<?php
						if(isset($_SESSION['login'])){
							echo '<a class="logout" onclick="return logout_alert();" href="index.php?action=logout"><div id="logout" >LOGOUT</div></a>'; 
						}
					?>
				</ul>
			</nav>

			<div class="grid_item" id="primary_column">
				<h2>Contacts</h2>
				<?php
					//function for displaying the contact list based on the user
					contacts::display_contact_list($_SESSION['login']);
				?>
			</div>

			<div class="grid_item" id="selected_column">
				<?php
					//if action is set
					if(isset($_GET['action'])){
						//delete
						if($_GET['action'] == "display"){
							contacts::display_contact($_SESSION['login'], $_GET['contact']);
						}else if($_GET['action'] == "delete"){
							
							contacts::delete_contact($_SESSION['login'],$_GET['contact']);
						//edit
						}else if($_GET['action'] == "edit"){
							$contact = contacts::get_contact($_SESSION['login'], $_GET['contact']); //grab contact object to grab previously entered data
							?>
								<h2>Edit Contact (<?php echo $contact->get_name()." ".$contact->get_lname()?>)</h2>
								<form method="post">
										<h3>Name:</h3>
										<input type="text" name="name" placeholder="Enter name here..." value="<?php if($contact->get_name()){echo $contact->get_name();}?>" autofocus><br>

										<h3>Last Name:</h3>
										<input type="text" name="lname" placeholder="Enter last name here..." value="<?php if($contact->get_lname()){echo $contact->get_lname();}?>" ><br>

										<h3>Phone:</h3>
										<input type="number" name="phone" placeholder="Enter phone number here..." value="<?php if($contact->get_phone()){echo $contact->get_phone();}?>" ><br>

										<h3>Address:</h3>
										<input type="text" name="address" placeholder="Enter address here..." value="<?php if($contact->get_address()){echo $contact->get_address();}?>" ><br>

										<h3>Birthday:</h3>
										<input type="date" name="birthday" placeholder="Enter birthday here..." value="<?php if($contact->get_birthday()){echo $contact->get_birthday();}?>" ><br>

										<input type="submit" name="submit">
									</form>
								<?php
								//if submit is clicked
								if(isset($_POST['submit'])){
									$id = $_GET['contact'];
									$name =  !empty($_POST['name']) ? '"'.$_POST['name'].'"' : "NULL";
									$lname = !empty($_POST['lname']) ? '"'.$_POST['lname'].'"' : "NULL";
									$phone = !empty($_POST['phone']) ? $_POST['phone'] : "NULL";
									$address = !empty($_POST['address']) ? '"'.$_POST['address'].'"' : "NULL";
									$birthday = !empty($_POST['birthday']) ? '"'.$_POST['birthday'].'"' : "NULL";

									//take user input and send to edit contact function
									contacts::edit_contact($_SESSION['login'], $id, $name, $lname, $phone, $address, $birthday);
								}
						//action insert
						}else if($_GET['action'] == "insert"){
								//display a form
								?>
								<h2>New Contact</h2>
								<form method="post">
										<h3>Name:</h3>
										<input type="text" name="name" placeholder="Enter name here..." required autofocus><br>

										<h3>Last Name:</h3>
										<input type="text" name="lname" placeholder="Enter last name here..."><br>

										<h3>Phone:</h3>
										<input type="number" name="phone" placeholder="Enter phone number here..."><br>

										<h3>Address:</h3>
										<input type="text" name="address" placeholder="Enter address here..."><br>

										<h3>Birthday:</h3>
										<input type="date" name="birthday" placeholder="Enter birthday here..."><br>
										

										<input type="submit" name="submit">
									</form>
								<?php

								//if submit is clicked
								if(isset($_POST['submit'])){
									$name =  !empty($_POST['name']) ? '"'.$_POST['name'].'"' : "NULL";
									$lname = !empty($_POST['lname']) ? '"'.$_POST['lname'].'"' : "NULL";
									$phone = !empty($_POST['phone']) ? $_POST['phone'] : "NULL";
									$address = !empty($_POST['address']) ? '"'.$_POST['address'].'"' : "NULL";
									$birthday = !empty($_POST['birthday']) ? '"'.$_POST['birthday'].'"' : "NULL";

									//take user input and send to insert contact function
									contacts::insert_contact($_SESSION['login'], $name, $lname, $phone, $address, $birthday);
								}				
						}
					}
				?>
			</div>

		</div>
	</body>
</html>
