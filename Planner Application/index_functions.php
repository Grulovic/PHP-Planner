<?php
////////////////////////////////////////////////////////////////////////////////////////////////
// Stefan Grulovic (20150280) - CS325 Final Project
////////////////////////////////////////////////////////////////////////////////////////////////
// INDEX FUNCTIONS - login check + register
////////////////////////////////////////////////////////////////////////////////////////////////

//database
include_once("mysqli_database.php");

//function for checking user login credentials with ones stored in database
function login_check($username, $password){
	//database
	$database = $GLOBALS['database'];

	//check if user exists
	$login_check = $database->get_data("COUNT(*)","USERS",'WHERE USER_LOGIN = "'.$username.'"');
	
	if($login_check[0]["COUNT(*)"]==1){
			//check if the password is same
			$db_password = $database->get_data("USER_PASSWORD","USERS", "WHERE USER_LOGIN = \"$username\"");

			if($db_password[0]["USER_PASSWORD"] === crypt($password, $db_password[0]["USER_PASSWORD"])){

				//if remember me is checked
				if ($_POST['remember']) {
					//store cookie to remember user login name
					setcookie('username', $_POST['username'], time()+86400 * 30);
				}
					//storing information that the user has logged in
					$_SESSION['login'] = $_POST['username'];

				echo '<div class="message">Successfully logged in.<br>Welcome back '.$username.'!</div>';
				header("Refresh:1; url=index.php");

			}else{
				echo '<div class="message">Password Incorrect</div>';
				header("Refresh:1; url=index.php");	
			}
	}else{
		echo '<div class="message">Username Incorrect</div>';
		header("Refresh:1; url=index.php");	
	}
}

//function for registering a user
function register($user, $name, $lname, $password){
	//database
	$database = $GLOBALS['database'];

	$password = crypt($password);
	//check if the username is taken
	$user_taken = $database->get_data("USER_LOGIN", "USERS", "WHERE USER_LOGIN = \"$user\"");

	if($user_taken==null){
		//calculate the new id
		$id = $database->get_data("USER_ID", "USERS", "ORDER BY USER_ID DESC LIMIT 1");
		$id = $id[0]["USER_ID"] + 1;

		//database function for inserting a user
		$database->insert("USERS", "$id , \"$user\" , \"$name\" \"$lname\" , \"$password\", \"User\"");

		echo '<div class="message">Successfully registered as '.$user.'!</div>';
		header("Refresh:1; url=index.php");	
	}else{
		echo '<div class="message">Username is taken!</div>';
		header("Refresh:1; url=index.php");	
	}

}
?>