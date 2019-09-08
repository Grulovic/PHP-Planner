<?php
/*
Stefan Grulovic (20150280) -> CS325 Midterm Project
*/



//class for calendar function
class calendar{
	//function -> for grabbing the value from url used for displaying correct calendar
	public function get_date(){
		if (isset($_GET['ym'])) {
		    $selected_date = $_GET['ym'];
		} else {
		    $selected_date = date('Y-m');
		}

		$selected_date = strtotime($selected_date . '-01');
		if ($selected_date === false) {
		    $selected_date = time();
		}
		return $selected_date;
	}
	//function -> for dusplayin the clickable header of the month
	public function display_header(){
		$selected_date = calendar::get_date();

		//button dates for the previos and nect month
		$prev = date('Y-m', mktime(0, 0, 0, date('m', $selected_date)-1, 1, date('Y', $selected_date)));
		$next = date('Y-m', mktime(0, 0, 0, date('m', $selected_date)+1, 1, date('Y', $selected_date)));

		//displaying the header and href for previous and next month
		echo '<h2 id="date_toggle">'
		.'<a href="?ym='.$prev.'"> &#10094; </a>'
		.date("M | Y",$selected_date)
		.'<a href="?ym='.$next.'"> &#10095; </a>'
		.'</h2>';
	}

	//function -> for displaying the calendar of specific month (from url(get_date function))
	public function display(){
		calendar::display_header();

		//variables used in order to display the calendar
		$weeks = array();	//where the each box day is stored
		$week = "";	//string used for storing the final string variable for which to be stored in $weeks
		$selected_date =  calendar::get_date(); //grabin the information from url
		$day_count = date('t', $selected_date);	//number of days in a specific date
		$empty_count = date('w', mktime(0, 0, 0, date('m', $selected_date), 1, date('Y', $selected_date)));//count of empty days for displaying in a table format
		$today = date('Y-m-j', time()); //todays date for displaying the green box instead of regular gray
		 
		$week .= str_repeat('<td></td>', $empty_count); //putting the empty days in the week string
		
		//grabbing the dates which have a note
		$file = "dates.txt";
		$days = array();
		$file_data = file($file);
		if($file_data!=null){
			foreach ($file_data as $data) {
				$line = explode('::', $data);
				$days[] = $line[0];	
			}
		}
		
		//loop for displaying the calendar
		for($day=1; $day <= $day_count; $day++, $empty_count++) {		    
		    $date = date("Y-m", $selected_date).'-'.$day; //date for each day of the month
			$url = "calendar.php?info=".$date."&ym=".date("Y-m", $selected_date);//the specific url for the href/url for displaying the informatuon

			//checking if the date has a note or not
			if($file_data!=null){
				for($i=0;$i<sizeof($days);$i++){
					if($date == $days[$i]){
						$week.='<td class="has_data" ';
					}else{
						$week .='<td ';
					}
				}
			}else{
				$week .='<td ';
			}
			
			//if statements for different displaying version of the box, either if the day is selected, today or nothing
		    if($date == $_GET['info'] && $today == $date){
		    	$week .= ' id="selected"><a href="'.$url.'">'.$day.'</a>';
		    }else if($date == $_GET['edit']){
		    	$week .= ' id="selected"><a href="'.$url.'">'.$day.'</a>';
		    }else if($date == $_GET['add']){
		    	$week .= ' id="selected"><a href="'.$url.'">'.$day.'</a>';
		    }else if ($today == $date) {
		        $week .= ' id="today"><a href="'.$url.'">'.$day.'</a>';
		    }else if($_GET['info']==$date){
		    	$week .= ' id="selected"><a href="'.$url.'">'.$day.'</a>';
		    }else {
		        $week .= '><a href="'.$url.'">'.$day.'</a>';
		    }
		    $week .= '</td>';

		    //empty count of the end of the month in order tu fully fill the table
		    if ($empty_count % 7 == 6 || $day == $day_count) {
	        	if($day == $day_count) {
		            $week .= str_repeat('<td></td>', 6 - ($empty_count % 7));
		        }
		         
		        $weeks[] = '<tr>'.$week.'</tr>';
		        $week = '';
		    }
		}

		//displaying week by week
		echo '<table>';
		foreach ($weeks as $week) {
			echo $week;
		}
		echo '</table>';
	}

	//function -> for grabbing the specific dates note and then displaying it
	public function get_day_info($day){
		//grabbing the notes from the file
		$file = "dates.txt";
		$file_data = file($file);

		//loop fot displying the specifc dates note
		foreach ($file_data as $data) {
			$line = explode('::', $data);
			if($line[0]==$day){
				echo '<h4>NOTE:</h4><div id="day_info">'.$line[1].'</div>';
				echo '<a id="edit_calendar_note" href="calendar_edit.php?ym='.date('Y-m',strtotime($day)).'&edit='.$day.'"><li>Edit Note</li></a>';
				echo '<a id="edit_calendar_note" href="calendar.php?ym='.date('Y-m',strtotime($day)).'&delete='.$day.'"><li>-Delete Note</li></a><br><br>';

				$check = true;
				break;
			}
		}

		// if there is no note dislpay a message
		if(!$check){
			echo '<div class="error">No notes for selected day.</div>';
			echo '<a id="edit_calendar_note" href="calendar_add.php?ym='.date('Y-m',strtotime($day)).'&add='.$day.'"><li>+Add Note</li></a>';	
		}
	}

	//function -> for adding a new date note
	public function add_day_info($day, $info){
		$file = "dates.txt";
		$file_data = file($file);

		//if file is not empty make a new line before writing
		if($file_data!=null){
			$fp = fopen($file, 'a') or die('ERROR: Cannot open file');
			flock($fp, LOCK_EX) or die ('ERROR: Cannot lock file');
			fwrite($fp, "\n$day::$info") or die ('ERROR: Cannot write file');
			flock($fp, LOCK_UN) or die ('ERROR: Cannot unlock file');
			fclose($fp);	
		}
		//if the file is empty then just write
		else{
			$fp = fopen($file, 'a') or die('ERROR: Cannot open file');
			flock($fp, LOCK_EX) or die ('ERROR: Cannot lock file');
			fwrite($fp, "$day::$info") or die ('ERROR: Cannot write file');
			flock($fp, LOCK_UN) or die ('ERROR: Cannot unlock file');
			fclose($fp);
		}

		//refresh in order to display the new inserted note
		header('Refresh:0; url=calendar.php?ym='.date('Y-m',strtotime($day)).'&info='.$day);
	}

	//function -> for deleting a specific dates note
	public function delete_day_info($day){
		$file = "dates.txt";
		$file_data = file($file);

		if($file_data!=null){
			//loop which goes through the file and put only the dates which are not equal to the one being deleted
			foreach ($file_data as $data) {
				$line = explode('::', $data);
				if($day != $line[0]){
					$new_data.=$line[0]."::".$line[1];
				}
			}

			//then write rest of the days to a file
			$fp = fopen("dates.txt", 'w') or die('ERROR: Cannot open file');
			flock($fp, LOCK_EX) or die ('ERROR: Cannot lock file');

			$new_data = trim($new_data);

			if($new_data!=null){
				fwrite($fp, $new_data) or die ('ERROR: Cannot write file');	
			}
			flock($fp, LOCK_UN) or die ('ERROR: Cannot unlock file');
			fclose($fp);
		}

		//refresh in order to display the the date again but now without a note
		header('Refresh:0; url=calendar.php?ym='.date('Y-m',strtotime($day)).'&info='.$day);
	}

	//function -> for editing a specifc dates note
	public function edit_day_info($day,$new_note){
		$file = "dates.txt";
		$file_data = file($file);

		if($file_data!=null){
			//loop which goes thorugh the file and if the date is same as the requested edit it replaces the string
			foreach ($file_data as $data) {
				$line = explode('::', $data);
				if($day == $line[0]){
					$new_data.=$line[0]."::".$new_note."\n";
				}else{
					$new_data.=$line[0]."::".$line[1];
				}
			}

			//then writes to the file new information
			$fp = fopen("dates.txt", 'w') or die('ERROR: Cannot open file');
			flock($fp, LOCK_EX) or die ('ERROR: Cannot lock file');

			$new_data = trim($new_data);

			if($new_data!=null){
				fwrite($fp, $new_data) or die ('ERROR: Cannot write file');	
			}
			flock($fp, LOCK_UN) or die ('ERROR: Cannot unlock file');
			fclose($fp);

		}

		//refresh in order to display the new edited note
		header('Refresh:0; url=calendar.php?ym='.date('Y-m',strtotime($day)).'&info='.$day);
	}
}
?>