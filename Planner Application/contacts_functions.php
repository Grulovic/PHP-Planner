<?php
////////////////////////////////////////////////////////////////////////////////////////////////
// Stefan Grulovic (20150280) - CS325 Final Project
////////////////////////////////////////////////////////////////////////////////////////////////
// CONTACT CLASS + FUNCTIONS
////////////////////////////////////////////////////////////////////////////////////////////////
//class database with basic functions
include_once("mysqli_database.php");

//class contact
class contacts {
	//variables
	private $id;
	private $name;
	private $lname;
	private $phone;
	private $address;
	private $birthday;

	//constructor
	public function __construct($id, $name, $lname, $phone, $address, $birthday)  {
		$this->id = $id;
		$this->name = $name;  
	    $this->lname = $lname;
	    $this->phone = $phone;
	    $this->address = $address;
	    $this->birthday = $birthday;

    }

    //get and set functions
    public function get_id()	{return $this->id;}
	//public function set_id($name)	{$this->id = $id;}

    public function get_name()	{return $this->name;}
	public function set_name($name)	{$this->name = $name;}

	public function get_lname()	{return $this->lname;}
	public function set_lname($lname)	{$this->lname = $lname;}

	public function get_phone()	{return $this->phone;}
	public function set_phone($phone)	{$this->phone = $phone;}

	public function get_address()	{return $this->address;}
	public function set_address($address)	{$this->address = $address;}

	public function get_birthday()	{return $this->birthday;}
	public function set_birthday($birthday)	{$this->birthday = $birthday;}

//function for grabbing contacts from database
function get_contacts($user){
	$database = $GLOBALS['database'];

	//sql statement
	$column_names = "CONTACT_ID, CONTACT_NAME, CONTACT_LNAME, CONTACT_PHONE, CONTACT_ADDRESS, CONTACT_BIRTHDAY";
	$table_name = "CONTACTS";
	$conditions = 'WHERE USER_CONTACTS = "'.$user.'" ORDER BY CONTACT_NAME';

	//database function for grabbing data
	$data = $database->get_data($column_names, $table_name, $conditions);

	if($data!=null){
		//convert the data into objects
		foreach ($data as $key => $contact) {
			$contacts[] = new contacts(
						$contact["CONTACT_ID"]
						,$contact["CONTACT_NAME"]
						,$contact["CONTACT_LNAME"]
						,$contact["CONTACT_PHONE"]
						,$contact["CONTACT_ADDRESS"]
						,$contact["CONTACT_BIRTHDAY"]
					);
		}	

		return $contacts;
	}
}

//function for grabbing a specific contact
function get_contact($user, $contact){
	$database = $GLOBALS['database'];

	//sql statement
	$column_names = "CONTACT_ID, CONTACT_NAME, CONTACT_LNAME, CONTACT_PHONE, CONTACT_ADDRESS, CONTACT_BIRTHDAY";
	$table_name = "CONTACTS";
	$conditions = 'WHERE USER_CONTACTS = "'.$user.'" AND CONTACT_ID = "'.$contact.'"';

	//database function for grabbing data
	$data = $database->get_data($column_names, $table_name, $conditions);

	$contact = new contacts(
						$data[0]["CONTACT_ID"]
						,$data[0]["CONTACT_NAME"]
						,$data[0]["CONTACT_LNAME"]
						,$data[0]["CONTACT_PHONE"]
						,$data[0]["CONTACT_ADDRESS"]
						,$data[0]["CONTACT_BIRTHDAY"]
					);

	return $contact;
}

//function for displaying the contact list
function display_contact_list($user){
	//grab the data with previous function
	$contacts = contacts::get_contacts($user);

	//display as a list, each element out of 4 elements: body, title, edit link, delete link
	if($contacts!=null){
		echo "<ul>";
		foreach ($contacts as $contact) {
			echo 
			"<a ".'class="note_title"'.'href="contacts.php?action=display&contact='.$contact->get_id().'">'
			.'<li>'.$contact->get_name()
			.'<div class="note_tools">'
			.'<a href="contacts.php?action=edit&contact='.$contact->get_id().'"'.'>Edit'."</a>"
			.' <b>|</b> '
			.'<a href="contacts.php?action=delete&contact='.$contact->get_id().'" onclick="return delete_alert();" >Delete'."</a>"
			."</div></li></a>";
		}
		echo '<a href="contacts.php?action=insert"><li id="new_note">+ New Contact</li></a>';
		echo "</ul>";	
	}else{
		echo "<ul>";
		echo '<li><a>There are no contacts.</a></li>';
		echo '<a href="contacts.php?action=insert"><li id="new_note">+ New Contact</li></a>';
		echo "</ul>";	
	}
	
}

//function for displaying contacts
function display_contact($user, $contact_show){
	//grab data
	$contact = contacts::get_contact($user,$contact_show);

	//display each element of contact object
	if($contact!=null) {
		echo "<h2>".$contact->get_name()." ".$contact->get_lname()."</h2>";
		
		echo "<h3>Name: </h3><p>".$contact->get_name()."</p><br>";
		echo "<h3>Last Name: </h3><p>".$contact->get_lname()."</p><br>";
		echo "<h3>Phone: </h3><p>".$contact->get_phone()."</p><br>";
		echo "<h3>Address: </h3><p>".$contact->get_address()."</p><br>";
		echo "<h3>Birthday: </h3><p>".$contact->get_birthday()."</p><br>";	
	}else{
		echo '<h2>ERROR</h2>'.'<div class="error">There is no such contact.</div>';
	}
	
}

//function for deleting a contact into database
function delete_contact($user, $contact){
	$database = $GLOBALS['database'];

	//database function for deleting
	$database->delete("CONTACTS","CONTACT_ID = \"$contact\" AND USER_CONTACTS = \"$user\"");

	header("Refresh:0; url=contacts.php");	
}

//function for inserting a contact into database
function insert_contact($user, $name, $lname, $phone, $address, $birthday){
	$database = $GLOBALS['database'];

	//calculating the new id
	$id = $database->get_data("CONTACT_ID", "CONTACTS", "ORDER BY CONTACT_ID DESC LIMIT 1");
	$id = $id[0]["CONTACT_ID"] + 1;
	
	$database->insert("CONTACTS", "$id , $name , $lname , $phone , $address , $birthday , \"$user\"");

	header("Refresh:0; url=contacts.php?action=display&contact=$id");
}

//function for editing a contact
function edit_contact($user, $id, $name, $lname, $phone, $address, $birthday){
	$database = $GLOBALS['database'];
	
	//using database update function
	if($name != null){
		$database->update("CONTACTS", "CONTACT_NAME = $name", "CONTACT_ID = $id");
	}
	if($lname != null){
		$database->update("CONTACTS", "CONTACT_LNAME = $lname ", "CONTACT_ID = $id");
	}
	if($phone != null){
		$database->update("CONTACTS", "CONTACT_PHONE = $phone ", "CONTACT_ID = $id");
	}
	if($address != null){
		$database->update("CONTACTS", "CONTACT_ADDRESS = $address", "CONTACT_ID = $id");
	}
	if($birthday != null){
		$database->update("CONTACTS", "CONTACT_BIRTHDAY = $birthday", "CONTACT_ID = $id");	
	}
	
	header("Refresh:0; url=contacts.php?action=display&contact=$id");
}
}
?>

