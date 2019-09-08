 <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
 ////////////////////////////////////////////////////////////////////////////////////////////////
 // Stefan Grulovic (20150280) - CS325 Final Project
 ////////////////////////////////////////////////////////////////////////////////////////////////
 // DATABASE CLASS + BASIC FUNCTIONS
 ////////////////////////////////////////////////////////////////////////////////////////////////
class mysqli_database{
	//variables
	private $user_name;
	private $host_name;
	private $password;
	private $database;
	private $connection;

	//constructor
	public function __construct($user_name, $host_name, $password, $database){
		$this->user_name = $user_name;
		$this->host_name = $host_name;
		$this->password = $password;
		$this->database = $database;
	}
	//establishing connection
	public function connection(){
		$this->connection = new mysqli($this->user_name, $this->host_name, $this->password, $this->database);
		
		if ($this->connection->connect_errno) {
		    echo "Failed to connect to MySQL: (" . $this->connection->connect_errno . ") " . $this->connection->connect_error."<br>";
		}
	}
	//closing connection
	public function close_connection(){
		if(isset($this->connection)){
			$this->connection->close();
		}
	}
	//running a sql statement
	public function runsql($query){
		$results = $this->connection->query($query);
		return $results;
	}

	public function get_columns($column_names, $table_name, $conditions){
		//OPEN DATABASE CONNECTION
		self::connection();
		
		$results = self::runsql("SELECT $column_names FROM $table_name $conditions");

		if($results){
			//TABLE COLUMNS
			self::runsql("CREATE TEMPORARY TABLE temp_table AS (SELECT $column_names FROM $table_name $conditions)");
			$columns = self::runsql("SHOW COLUMNS FROM temp_table");

			$table_columns = array();

    		if($columns){
			    while($row = $columns->fetch_assoc()) {
			    	$table_columns[] = $row["Field"];
			    }
			}

			return $table_columns;
		}else{
			echo "ERROR while getting columns: (" . $this->connection->errno . ") " . $this->connection->error."<br>";
		}
	
		//CLOSE DATABASE CONNECTION
		self::close_connection();
	}

	public function get_data($column_names, $table_name, $conditions){
		//OPEN DATABASE CONNECTION
		self::connection();
			
		$results = self::runsql("SELECT $column_names FROM $table_name $conditions");

		if($results){
			
			while ($row = $results->fetch_assoc()) {
		        $data[] = $row;
		    }
		    if(!empty($data)){
		    	return $data;
		    }
		}else{
			echo "ERROR while selecting: (" . $this->connection->errno . ") " . $this->connection->error."<br>";
		}

		//CLOSE DATABASE CONNECTION
		self::close_connection();
	}

	public function get_array_data($column_names, $table_name, $conditions){
		$table_columns = self::get_columns($column_names, $table_name, $conditions);

		//OPEN DATABASE CONNECTION
		self::connection();
			
		$results = self::runsql("SELECT $column_names FROM $table_name $conditions");

		if($results){
			//TABLE DATA
			$table_data = array();

		    while($row = $results->fetch_assoc()) {
		        for( $i = 0; $i<sizeof($table_columns); $i++ ) {
        			$table_data[] = $row[$table_columns[$i]];
    			}
		    }

		    return $table_data;
		}else{
			echo "ERROR while selecting: (" . $this->connection->errno . ") " . $this->connection->error."<br>";
		}

		//CLOSE DATABASE CONNECTION
		self::close_connection();
	}
	
	public function select_table($column_names, $table_name, $conditions){
		$table_columns = self::get_columns($column_names, $table_name, $conditions);
		$table_data = self::get_data($column_names, $table_name, $conditions);

		if($table_columns!= null && $table_data!=null){
			$table = array( 
							"name" => $table_name,
						    "columns" => $table_columns,
						    "data" => $table_data
						);

			return $table;
		}
		
	}
	
	public function insert($table_name, $column_values){
			//OPEN DATABASE CONNECTION
			self::connection();
			
			$results = self::runsql("INSERT INTO $table_name VALUES ($column_values)");

			if($results){
				//echo("Sucessfully inserted into $table_name <br>");
			}
			else{
				echo "ERROR while inserting: (" . $this->connection->errno . ") " . $this->connection->error."<br>";	
			}

			//CLOSE DATABASE CONNECTION
			self::close_connection();
	}

	public function delete($table_name, $condition){
			//OPEN DATABASE CONNECTION
			self::connection();
			
			$results = self::runsql("DELETE FROM $table_name WHERE $condition");

			if($results){
				//echo("Sucessfully deleted from $table_name <br>");
			}
			else{
				echo "ERROR while deleting: (" . $this->connection->errno . ") " . $this->connection->error."<br>";
			}

			//CLOSE DATABASE CONNECTION
			self::close_connection();
	}

	public function update($table_name, $column_name_value, $condition){
			//OPEN DATABASE CONNECTION
			self::connection();
			
			$results = self::runsql("UPDATE $table_name SET $column_name_value WHERE $condition");


			if($results){
				//echo("Sucessfully updated $table_name <br>");
			}
			else{
				echo "ERROR while updating: (" . $this->connection->errno . ") " . $this->connection->error."<br>";
			}

			//CLOSE DATABASE CONNECTION
			self::close_connection();
	}
}



$GLOBALS['database'] = new mysqli_database('localhost','root','mysql','CS325');


?>