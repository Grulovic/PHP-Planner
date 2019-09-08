<?php
/*
Stefan Grulovic (20150280) -> CS325 Midterm Project
*/



//class for calendar function
class notes {
	//variables
	private $title;
	private $note;
	public static $file = "notes.txt";
	
	//constructor for a note
	public function __construct($title, $note)  {
		$this->title = $title;
		$this->note = $note;
    }
    //get and set functions for note variables
    public function get_title()	{return $this->title;}
	public function set_title($title)	{$this->title = $title;}

    public function get_note()	{return $this->note;}
	public function set_note($note)	{$this->note = $note;}

	//function for displayinh the notes information
	public function display_notes(){
		$file = "notes.txt";
		$fp = fopen($file, 'r+') or die('ERROR: Cannot open file');

		if(file_exists($file)){
			//get data from file and increment
			$file_data = file($file);

			if($file_data!=null){
				//grab the notes from the file
				foreach ($file_data as $data) {
					$note = explode('::', $data);
				 	$notes[] = new notes($note[0],$note[1]);
				}

				//Display the list with options
				echo "<ul>";
				foreach ($notes as $note) {
					echo 
					"<a ".'class="note_title"'.'href="notes.php?note='.$note->get_title().'">'
					.'<li>'.$note->get_title()
					.'<div class="note_tools">'
					.'<a href="notes_edit.php?edit='.$note->get_title().'"'.'>Edit'."</a>"
					.' <b>|</b> '
					.'<a href="notes.php?delete='.$note->get_title().'"'.'>Delete'."</a>"
					."</div></li></a>";
				}
				echo '<a href="notes_new.php"><li id="new_note">+ New Note</li></a>';
				echo "</ul>";
			}
			else{
				//Display the list with options
				echo "<ul>";
				echo '<li><a>There are no notes.</a></li>';
				echo '<a href="notes_new.php"><li id="new_note">+ New Note</li></a>';
				echo "</ul>";	
			}

		}
	}

	//function -> for dislpaying the specific notes info
	public function display_note($note){
		$file = "notes.txt";
		$file_data = file($file) or die('ERROR: Cannot find file');

		//grab the notes from the file
		foreach ($file_data as $data) {
			$note_info = explode('::', $data);
		 	$notes[] = new notes($note_info[0],$note_info[1]);
		}
		//find the specific note
		for ($i=0; $i < sizeof($notes); $i++) {
			if($note == $notes[$i]->get_title()){
				echo "<h2>Selected Note (".$notes[$i]->get_title().")</h2>";
				echo "<h3>Title: </h3><p>".$notes[$i]->get_title()."</p><br>";
				echo "<h3>Note: </h3><p>".$notes[$i]->get_note()."</p><br>";

				$check = true;
			}
		}
		//if there is no such note display an error
		if(!$check){
				echo '<h2>ERROR</h2>'.'<div class="error">There is no such note.</div>';
		}
	}

	//function -> for inserting a new note
	public function insert_note($title, $note){
		$file = "notes.txt";
		$file_data = file($file);

		if($file_data!=null){
			//go through the data and check weather there is a note with a same name
			foreach ($file_data as $data) {
				$note_info = explode('::', $data);
				if($title==$note_info[0]){
					$check = true;
					break;
				}
			}
			//if not then write the new note to the file
			if(!$check){
				$fp = fopen($file, 'a') or die('ERROR: Cannot open file');
				flock($fp, LOCK_EX) or die ('ERROR: Cannot lock file');
				fwrite($fp, "\n$title::$note") or die ('ERROR: Cannot write file');
				flock($fp, LOCK_UN) or die ('ERROR: Cannot unlock file');
				fclose($fp);	

				//refresh page to show the new inserted note 
				header("Refresh:0; url=notes.php?note=".$title);	
			}//otherwise display a message
			else{
				echo '<div class="error">There is already a note with such title.</div>';
			}
		}//if file is empty then write without a new line
		else{
			$fp = fopen($file, 'a') or die('ERROR: Cannot open file');
				flock($fp, LOCK_EX) or die ('ERROR: Cannot lock file');
				fwrite($fp, "$title::$note") or die ('ERROR: Cannot write file');
				flock($fp, LOCK_UN) or die ('ERROR: Cannot unlock file');
				fclose($fp);	

				//refresh the page to display the new inserted note
				header("Refresh:0; url=notes.php?note=".$title);	
		}	
	}

	//function -> for deleting a note
	public function delete_note($note_del){
		$file = "notes.txt";
		$file_data = file($file);

		if($file_data!=null){
			//grab the notes from the file
			foreach ($file_data as $data) {
				$note_info = explode('::', $data);
			 	$notes[] = new notes($note_info[0],$note_info[1]);
			}
			$fp = fopen("notes.txt", 'w') or die('ERROR: Cannot open file');
			flock($fp, LOCK_EX) or die ('ERROR: Cannot lock file');
			
			//grab all the notes expect the one to be deleted
			$data = null;
			for ($i=0; $i < sizeof($notes); $i++) {
				if($notes[$i]->get_title() != $note_del){
					$data .= $notes[$i]->get_title()."::".$notes[$i]->get_note();	
				}
			}
			$data = trim($data);
			//then write the new data to the file
			if($data!=null){
				fwrite($fp, $data) or die ('ERROR: Cannot write file');	
			}
			flock($fp, LOCK_UN) or die ('ERROR: Cannot unlock file');
			fclose($fp);

			//refresh the page in order to show the new list without the deleted note
			header("Refresh:0; url=notes.php");
		}

	}

	//function -> for editing a note
	public function edit_note($edit_note,$new_title, $new_note){
		$file = "notes.txt";
		$file_data = file($file) or die('ERROR: Cannot find file');

		//grab the notes from the file at the same time check if the new requested title is already used
		foreach ($file_data as $data) {
			$note_info = explode('::', $data);

			if($new_title==$note_info[0]){
				$check = true;
				break;
			}
			else{
		 		$notes[] = new notes($note_info[0],$note_info[1]);
			}
		}

		//if new title is not used
		if(!$check){
			//go through the notes and set the new info 
			for ($i=0; $i < sizeof($notes); $i++) {
				if($edit_note == $notes[$i]->get_title()){
					if($new_title!=""){
						$notes[$i]->set_title($new_title);
					}
					if($new_note!=""){
						if(sizeof($notes)-1==$i){
							$notes[$i]->set_note($new_note);	
						}else{
							$notes[$i]->set_note($new_note."\n");	
						}
					}

					//then write all the notes to the file
					$fp = fopen("notes.txt", 'w') or die('ERROR: Cannot open file');
					flock($fp, LOCK_EX) or die ('ERROR: Cannot lock file');
					$data = "";
					foreach ($notes as $note) {
						$data .= $note->get_title()."::".$note->get_note();
					}

					fwrite($fp, $data) or die ('ERROR: Cannot write file');	
					flock($fp, LOCK_UN) or die ('ERROR: Cannot unlock file');
					fclose($fp);
				}
			}

			//then refresh the page to display the new edited information
			if($new_title!=null){
				header("Refresh:0; url=notes.php?note=".$new_title);
			}
			else{
				header("Refresh:0; url=notes.php?note=".$edit_note);	
			}
			
		}//otherwise display an error
		else{
			echo '<div class="error">There is already a note with such title.</div>';
		}
		
	}
}
?>