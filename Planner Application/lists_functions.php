<?php
////////////////////////////////////////////////////////////////////////////////////////////////
//Stefan Grulovic (20150280) - CS325 Final Project
////////////////////////////////////////////////////////////////////////////////////////////////
//LIST CLASS + FUNCTIONS
////////////////////////////////////////////////////////////////////////////////////////////////
//database clas with basic functions
include_once("mysqli_database.php");

//class for the list object which holds the points
class lists {
	//variables
	private $id;
	private $name;
	private $points = array();

	//constructor
	public function __construct($id, $name, $point)  {
		$this->id = $id;
		$this->name = $name;
		$this->points = $point;
    }

    //get and set functions
    public function get_id()	{return $this->id;}
	//public function set_id($id)	{$this->id = $id;}

    public function get_name()	{return $this->name;}
	public function set_name($name)	{$this->name = $name;}

    public function get_points()	{return $this->points;}
	public function set_points($points)	{$this->points = $points;}

////////////////////////////////////////////////////////////////////////////////////////////////
///////LIST FUNCTIONS
////////////////////////////////////////////////////////////////////////////////////////////////

//function for getting a list
function get_lists($user){
	//database
	$database = $GLOBALS['database'];

	//sql statement
	$column_names = "LIST_ID, LIST_NAME";
	$table_name = "LISTS";
	$conditions = "WHERE USER_LISTS = \"$user\" ORDER BY LIST_NAME";

	//database function get data
	$list_info = $database->get_data($column_names, $table_name, $conditions);

	if($list_info!=null){
		//for each of the lists grab the points
		foreach ($list_info as $list) {
			$column_names = "POINT_ID, POINT_CONTENT";
			$table_name = "LIST_POINTS";
			$conditions = 'WHERE LIST_ID = '.$list['LIST_ID'];

			//database function for getting the data
			$points = $database->get_data($column_names, $table_name, $conditions);
			//store in array list objects 
			$lists[]= new lists($list['LIST_ID'], $list['LIST_NAME'], $points);
		}

		return $lists;
	}	
	
}

//function for grabbing a list
function get_list($user, $list_id){
	//grab the lists using previous functions
	$lists = lists::get_lists($user);

	//look for the requested one
	foreach ($lists as $list) {
		if($list->get_id() == $list_id ){
			$return_list = $list;
		}
	}

	return $return_list;
}

//function for displaying a list
function display_lists($user){
	//grab the lists using previous function
	$lists  = lists::get_lists($user);
	$count = 0;

	if($lists!=null){
		echo "<ul>";
		//for each of the lists display and element made of 4 points: body, title, edit link, delete link
		foreach ($lists as $list) {
			echo 
			"<a ".'class="note_title"'.'href="lists.php?action=display&list='.$list->get_id().'">'
			.'<li>'.$list->get_name()
			.'<div class="note_tools">'
			.'<a href="lists.php?action=edit_list&list='.$list->get_id().'">Edit</a>'
			.' <b>|</b> '
			.'<a href="lists.php?action=delete_list&list='.$list->get_id().'" onclick="return delete_alert();">Delete</a>'
			."</div></li></a>";
		}
		echo '<a href="lists.php?action=insert_list"><li id="new_note">+ New List</li></a>';
		echo "</ul>";	
	}else{
		echo "<ul>";
		echo '<li><a>There are no lists.</a></li>';
		echo '<a href="lists.php?action=insert_list"><li id="new_note">+ New List</li></a>';
		echo "</ul>";	
	}
}


//function for adding a list
function add_list($user, $name){
	//database
	$database = $GLOBALS['database'];

	//grabbing the latest it
	$id = $database->get_data("LIST_ID", "LISTS", "ORDER BY LIST_ID DESC LIMIT 1");
	$id = $id[0]["LIST_ID"] + 1;
	
	//database function for inserting a list
	$database->insert("LISTS", "$id , $name , \"$user\"");

	header("Refresh:0; url=lists.php?action=display&list=$id");
}

//function for deleting a list
function delete_list($user, $list){
	//database
	$database = $GLOBALS['database'];
	
	//delete the list points
	$database->delete("LIST_POINTS",'LIST_ID = "'.$list.'"');
	//delete the list 
	$database->delete("LISTS",'LIST_ID = "'.$list.'" AND USER_LISTS = "'.$user.'"');

	header("Refresh:0; url=lists.php");	
}

//function for editing a list
function edit_list($user, $list, $point, $new_content){
	//database
	$database = $GLOBALS['database'];

	if($point != null){
		//database function for updating
		$database->update("LIST_POINTS", "POINT_CONTENT = \"$new_content\"", "POINT_ID = $point");
	}
	
	header("Refresh:0; url=lists.php?action=display&list=$list");
}

//function for displaying a list
function display_points($user,$list_id){
	//grabbing the list using previous function
	$list = lists::get_list($user,$list_id);

	if($list!=null){
		if($list->get_id() == $list_id){
			if($list->get_points()!=null){
				echo "<ul>";
				echo "<h2>".$list->get_name()."</h2>";
				foreach ($list->get_points() as $key => $point) {
					echo 
					"<a ".'class="note_title"'.'href="lists.php?action=display&list='.$list_id.'&point='.$point["POINT_ID"].'">'
					.'<li>'.$key.". ".$point["POINT_CONTENT"]
					.'<div class="note_tools">'
					.'<a href="lists.php?action=edit_point&list='.$list_id.'&point='.$point["POINT_ID"].'"'.'>Edit'."</a>"
					.' <b>|</b> '
					.'<a href="lists.php?action=delete_point&list='.$list_id.'&point='.$point["POINT_ID"].'">Delete'."</a>"
					."</div></li></a>";

				}
				echo '<a href="lists.php?action=insert_point&list='.$list_id.'"><li id="new_note_white">+ New Point</li></a>';
				echo "</ul>";
			}else{
				echo "<h2>".$list->get_name()."</h2>";
				echo "<ul>";
				echo '<li><a>There are no points.</a></li>';
				echo '<a href="lists.php?action=insert_point&list='.$list_id.'"><li id="new_note_white">+ New Point</li></a>';
				echo "</ul>";	
			}
			
		}
	}
	
}

////////////////////////////////////////////////////////////////////////////////////////////////
////POINT FUNCTIONS
////////////////////////////////////////////////////////////////////////////////////////////////

//function for grabbing a specific point
function get_point($user, $point){
	$database = $GLOBALS['database'];

	$column_names = "POINT_ID, POINT_CONTENT, LIST_ID";
	$table_name = "LIST_POINTS";
	$conditions = 'WHERE POINT_ID = '.$point;

	$point = $database->get_data($column_names, $table_name, $conditions);
	
	return $point;
}

//function for adding a point
function add_point($user, $list, $content){
	//database
	$database = $GLOBALS['database'];

	//grabbing the latest id
	$id = $database->get_data("POINT_ID", "LIST_POINTS", "ORDER BY POINT_ID DESC LIMIT 1");
	$id = $id[0]["POINT_ID"] + 1;

	//database for inserting data
	$database->insert("LIST_POINTS", "$id , $content , $list");

	header("Refresh:0; url=lists.php?action=display&list=$list");
}

//function for deleting a point
function delete_point($user, $list, $point){
	//database
	$database = $GLOBALS['database'];
	
	//databse function for deleting an object
	$database->delete("LIST_POINTS", "POINT_ID = \"$point\"");

	header("Refresh:0; url=lists.php?action=display&list=$list");	
}

//function for editing a point
function edit_point($user, $list, $point, $new_content){
	//database
	$database = $GLOBALS['database'];
	
	if($new_content != null){
		//database function for updating 
		$database->update("LIST_POINTS", "POINT_CONTENT = $new_content", "POINT_ID = $point");
	}
	
	header("Refresh:0; url=lists.php?action=display&list=$list");
}
}
?>