<?php
/*
* @Author Rory Standley <rorystandley@gmail.com>
* @Version 1.0
* @Package Database
*/
class Database{
	/* 
	* Create variables for credentials to MySQL database
	* The variables have been declared as private. This
	* means that they will only be available with the
	* Database class
	*/
	private $db_host = "localhost";  // Change as required
	private $db_user = "root";  // Change as required
	private $db_pass = "bursary29032017";  // Change as required
	private $db_name = "uilkashdb_b";	// Change as required

	/*
	* Extra variables that are required by other function such as boolean con variable
	*/
	private $con = false; // Check to see if the connection is active
	private $result = array(); // Any results from a query will be stored here

	// Function to make connection to database
	public function connect(){
		$this->myconn = @mysqli_connect($this->db_host,$this->db_user,$this->db_pass, $this->db_name);
		// mysqli_connect() with variables defined at the start of Database class
		if($this->myconn){
			return true;
		}else{
			array_push($this->result, mysqli_connect_error());
			return false; // Problem connecting return FALSE
		}
	}

	// Function to disconnect from the database
	public function disconnect(){
		// If there is a connection to the database
		if($this->con){
			// We have found a connection, try to close it
			if(@mysqli_close($this->myconn)){
				// We have successfully closed the connection, set the connection variable to false
				$this->con = false;
				// Return true tjat we have closed the connection
				return true;
			}else{
				// We could not close the connection, return false
				return false;
			}
		}
	}

	public function sql($sql){
		global $con;
		$query = @mysqli_query($con, $sql);
		if($query){
			// If the query returns >= 1 assign the number of rows to numResults
			$this->other=$this->numResults =  mysqli_num_rows($query);
			// Loop through the query results by the number of rows returned
			for($i = 0; $i < $this->numResults; $i++){
				$r =  mysqli_fetch_array($query);
				$key = array_keys($r);
				for($x = 0; $x < count($key); $x++){
					// Sanitizes keys so only alphavalues are allowed
					if(!is_int($key[$x])){
						if( mysqli_num_rows($query) > 1){
							$this->result[$i][$key[$x]] = $r[$key[$x]];
						}else if( mysqli_num_rows($query) < 1){
							$this->result = null;
						}else{
							$this->result[$key[$x]] = $r[$key[$x]];
						}
					}
				}
			}
			return true; // Query was successful
		}else{
			array_push($this->result, mysqli_error($con));
			return false; // No rows where returned
		}
	}


	// Return number of rows
	function countrows($table, $where=''){
		global $con;
		$result = $this->Select($table,'count(*) as k',null, $where);//, '', '', false, 'AND','count(*)');
		return $result["k"];
	}



	// Function to return Num_rows from the database
	public function numrows($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null){
		global $con;
		// Create query from the variables passed to the function
		$q = 'SELECT '.$rows.' FROM '.$table;
		if($join != null){
			$q .= ' JOIN '.$join;
		}
		if($where != null){
			$q .= ' WHERE '.$where;
		}
		if($order != null){
			$q .= ' ORDER BY '.$order;
		}
		if($limit != null){
			$q .= ' LIMIT '.$limit;
		}
		// Check to see if the table exists
		if($this->tableExists($table)){
			// The table exists, run the query
			$query = @mysqli_query($con, $q);
			if($query){
				// If the query returns >= 1 assign the number of rows to numResults
				$this->other=$this->numResults =  mysqli_num_rows($query);
			}
		}
	}

	// Function to SELECT from the database
	public function select($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null){
		global $con;
		// Create query from the variables passed to the function
		$q = 'SELECT '.$rows.' FROM '.$table;
		if($join != null){
			$q .= ' JOIN '.$join;
		}
		if($where != null){
			$q .= ' WHERE '.$where;
		}
		if($order != null){
			$q .= ' ORDER BY '.$order;
		}
		if($limit != null){
			$q .= ' LIMIT '.$limit;
		}
		// Check to see if the table exists
		if($this->tableExists($table)){
			// The table exists, run the query
			$query = @mysqli_query($con, $q);
			if($query){
				// If the query returns >= 1 assign the number of rows to numResults
				$this->other=$this->numResults =  mysqli_num_rows($query);
				// Loop through the query results by the number of rows returned
				for($i = 0; $i < $this->numResults; $i++){
					$r =  mysqli_fetch_array($query);
					$key = array_keys($r);
					for($x = 0; $x < count($key); $x++){
						// Sanitizes keys so only alphavalues are allowed
						if(!is_int($key[$x])){
							if( mysqli_num_rows($query) > 1){
								$this->result[$i][$key[$x]] = $r[$key[$x]];
							}else if( mysqli_num_rows($query) < 1){
								$this->result = null;
							}else{
								$this->result[$key[$x]] = $r[$key[$x]];
							}
						}
					}
				}
				return true; // Query was successful
			}else{
				array_push($this->result, mysqli_error($con));
				return false; // No rows where returned
			}
		}else{
			return false; // Table does not exist
		}
	}

	// Function to insert into the database
	public function insert($table,$params=array()){
		global $con;
		// Check to see if the table exists
		if($this->tableExists($table)){
			$sql='INSERT INTO `'.$table.'` (`'.implode('`, `',array_keys($params)).'`) VALUES (\'' . implode('\', \'', $params) . '\')';
			// Make the query to insert to the database
			if($ins = @mysqli_query($con, $sql)){

				array_push($this->result, mysqli_insert_id());
				$this->other=1;
				return true; // The data has been inserted
			}else{
				array_push($this->result, mysqli_error($con));
				$this->other=0;
				return false; // The data has not been inserted
			}
		}else{
			$this->other=0;
			return false; // Table does not exist
		}
	}

	//Function to delete table or row(s) from database
	public function delete($table,$where = null){
		global $con;
		// Check to see if table exists
		if($this->tableExists($table)){
			// The table exists check to see if we are deleting rows or table
			if($where == null){
				$delete = 'DELETE '.$table; // Create query to delete table
			}else{
				$delete = 'DELETE FROM '.$table.' WHERE '.$where; // Create query to delete rows
			}
			// Submit query to database
			if($del = @mysqli_query($con, $delete)){
				$this->other=1;
				array_push($this->result, mysqli_affected_rows());
				return true; // The query exectued correctly
			}else{
				$this->other=0;
				array_push($this->result, mysqli_error($con));
				return false; // The query did not execute correctly
			}
		}else{
			$this->other=0;
			return false; // The table does not exist
		}
	}

	// Function to update row in database
	public function update($table,$params=array(),$where){
		global $con;
		// Check to see if table exists
		if($this->tableExists($table)){
			// Create Array to hold all the columns to update
			$args=array();
			foreach($params as $field=>$value){
				// Seperate each column out with it's corresponding value
				$args[]=$field.'="'.$value.'"';
			}
			// Create the query
			$sql='UPDATE '.$table.' SET '.implode(',',$args).' WHERE '.$where;
			// Make query to database
			if($query = @mysqli_query($con, $sql)){
				$this->other=1;
				array_push($this->result, mysqli_affected_rows());
				return true; // Update has been successful
			}else{
				$this->other=0;
				array_push($this->result, mysqli_error($con));
				return false; // Update has not been successful
			}
		}else{
			$this->other=0;
			return false; // The table does not exist
		}
	}

	// Private function to check if table exists for use with queries
	private function tableExists($table){
		global $con;
		$tablesInDb = @mysqli_query($con, 'SHOW TABLES FROM '.$this->db_name.' LIKE "'.$table.'"');
		if($tablesInDb){
			if( mysqli_num_rows($tablesInDb)==1){
				return true; // The table exists
			}else{
				array_push($this->result,$table." does not exist in this database");
				return false; // The table does not exist
			}
		}
	}

	// Public function to return the data to the user
	public function getResult(){
		global $con;
		$val = json_encode($this->result);
		$other=$this->other;
		//$this->result = array();
		$val_a=@array(
			data=>$val,
			row=>$other
		);
		return json_encode($val_a);
	}

	public function exist($table, $rows = '*', $join = null, $where = null, $order = null, $limit = null){
		global $con;
		$this->select($table, $rows, $join, $where, $order, $limit);// pay_item_id=113 means change of course
		$res = $this->getResult();
		$j= json_decode($res);
		//echo $j->row."<br>";
		if($j->row>0)
		{
			return true;

		}
		else {return false;}
	}
	public function getPix($mat){
		global $con;
		//  get the picture
		$picpath="pictures/".@strtoupper(@trim(@str_replace("/","",$mat))).".jpg";
		if(!file_exists($picpath))
		$picpath="pictures/".@strtolower(@trim(@str_replace("/","",$mat))).".jpg";
		if(!file_exists($picpath))
		$picpath="pictures/".@strtolower(@trim(@str_replace("/","",$mat))).".JPG";
		if(!file_exists($picpath))
		$picpath="pictures/".@strtoupper(@trim(@str_replace("/","",$mat))).".JPG";
		//end of get picture

		return $picpath;
	}

	public function getPix2($foldername,$mat,$status){
		global $con;
		if($status=="")
		{
			//  get the picture
			$picpath="$foldername/".@strtoupper(@trim(@str_replace("/","",$mat))).".jpg";
			if(!file_exists($picpath))
			$picpath="$foldername/".@strtolower(@trim(@str_replace("/","",$mat))).".jpg";
			if(!file_exists($picpath))
			$picpath="$foldername/".@strtolower(@trim(@str_replace("/","",$mat))).".JPG";
			if(!file_exists($picpath))
			$picpath="$foldername/".@strtoupper(@trim(@str_replace("/","",$mat))).".JPG";
			//end of get picture
		}
		else
		{
			// get the signature
			$picpath="$foldername/".@strtoupper(@trim(@str_replace("/","",$mat)))."_sign.jpg";
			if(!file_exists($picpath))
			$picpath="$foldername/".@strtolower(@trim(@str_replace("/","",$mat)))."_sign.jpg";
			if(!file_exists($picpath))
			$picpath="$foldername/".@strtolower(@trim(@str_replace("/","",$mat)))."_sign.JPG";
			if(!file_exists($picpath))
			$picpath="$foldername/".@strtoupper(@trim(@str_replace("/","",$mat)))."_sign.JPG";
			//end of get signature
		}

		return $picpath;
	} //end of getpix2
}
