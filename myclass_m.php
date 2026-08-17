<?php
 @session_start();
 @require_once('connect.php');
 
 	@require_once('class/mysqli_class.php');
	$db = new Database();
	$db->connect();

	class myclass_m{
		protected $sql;
		protected $qry;
		protected $row;
		
		public function get_total_pay_per_item($fileno, $folio){
			global $con;
			if($fileno !='' and $folio != ''){
				$this->sql="select sum(amount) as sum_total from payroll_scheduletb where folio_code='". mysqli_real_escape_string($con, $folio).
				"' and fileno='". mysqli_real_escape_string($con, $fileno)."'";
				$this->qry= mysqli_query($con, $this->sql);
				if ($this->row =  mysqli_fetch_array($this->qry, 3 ))	return $this->row[0];
			}else	return '';
		}
		public function begin(){
			global $con;
			mysqli_autocommit($con, FALSE);
			// mysqli_query($con, "BEGIN");
			 //mysqli_query($con, "START TRANSACTION");
		}
		public function commit(){
			global $con;
			mysqli_commit($con);
			 //mysqli_query($con, "COMMIT");
		}
		public function rollback(){
			global $con;
			mysqli_rollback($con);
			 //mysqli_query($con, "ROLLBACK");
		}
		
		public function writeLogFile($log){
			global $con;
			$path="upload_files/qlogs.txt";	
			$char=array('-',':','+');
			$path2 = "upload_files/qlogs".str_replace($char,'_',date('c')).".txt";
			if(filesize($path) > (1024 * 50000)){
				@rename($path, $path2);
			}
			$file=@fopen("upload_files/qlogs.txt", 'a');
			$string=date('c').'|'.$log."\n";
			$fw=@fwrite($file, $string);
		
			$fw=@fclose($file);
		}
		
		public function get_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false){
			global $con;
		   if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
		   if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);
		   
		   $diff = date_diff( $dt_menor, $dt_maior, ! $relative);
		   
		   switch( $str_interval ){
			   case "y":
				   $total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
			   case "m":
				   $total= $diff->y * 12 + $diff->m; // + $diff->d/30 + $diff->h / 24;
				   break;
			   case "d":
				   $total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
				   break;
			   case "h":
				   $total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
				   break;
			   case "i":
				   $total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
				   break;
			   case "s":
				   $total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
				   break;
			  }
			   if( $diff->invert)
					   return -1 * $total;
			   else    return $total;
		}
   
		public function is_leapyear($year, $mnt) {
			global $con;
			$is_leap = date('L', strtotime($year."-".$mnt."-1"));
			return $is_leap;
		}
		
		public function get_total_monthly_pay($level, $step, $scale, $category){
			global $con;
			$sum=0; $val=array(); $i=0; $full_string='';
			$this->sql="select amount, folio_code from salary_scaletb where scale_name='". mysqli_real_escape_string($con, $scale)."' and category='". mysqli_real_escape_string($con, $category)."' and level='". mysqli_real_escape_string($con, $level)."' and step='". mysqli_real_escape_string($con, $step)."'";
			$this->qry= mysqli_query($con, $this->sql);
			while ($this->row =  mysqli_fetch_array($this->qry, 3 ))	{
				$sum =+ $this->row['amount'];
				$val[$i]=$this->row['amount']."***".$this->row['folio_code'];
				$full_string .= $this->row['amount']."***".$this->row['folio_code']."^^^"; $i++;
			}
			return $sum."~~~".$full_string;
			//return "1000"."~~~".$full_string;
		}
		
		public function get_allowance_defined($al_id){
			global $con;
			//
			$val = '';	$folio = '';	$cat = '';	$staff_status = '';	$level = '';
			$pos = '';	$scale = '';	$rank = '';	$amount = 0;	$def_for = '';
			$this->sql="select * from allowancestb where id='". mysqli_real_escape_string($con, $al_id)."'";		
			$this->qry= mysqli_query($con, $this->sql);
			if($this->qry){
				if( mysqli_num_rows($this->qry) == 1){
					if ($this->row =  mysqli_fetch_array($this->qry, 3 ))	{
						$folio = $this->row['folio_code'];
						$cat = $this->row['category'];
						$staff_status = $this->row['staff_status'];
						$field_label = $this->row['field_label'];
						$field_value = $this->row['field_value'];
						$scale = $this->row['scale'];
						$rank = $this->row['rank'];
						$amount = $this->row['value'];
					}
				}else return 'Error reading record!'. mysqli_error($con);
			}else return 'Error reading record!'. mysqli_error($con);
				//do the value test
			if($cat == "All"){
				$def_for="All Staff";
			}else{
				//for categorized definitions
				if($staff_status == "" or $staff_status == "All"){
					//for all staff category (Academic/Non-Academic)
					if($level != "" && $level != "All"){
						//defined for particular level
						$def_for = "Level ".$level." (Academic/Non-Academic)";
						$val = "Level ".$level;
					}else{
						//across all levels
						if($field_label=="Level"){
							$def_for=$field_label." ".$field_value." (Academic/Non-Academic)";
							$val = $field_label." ".$field_value;
						}
						  else {
							  $def_for=$field_value." (Academic/Non-Academic)";
							  $val = $field_value;
						  }
					}
				}else{
					//based on selected staff category
					if($level != "" && $level != "All"){
						//defined for particular level
						$def_for = "Level ".$level." (".$staff_status.")";
						$val = "Level ".$level;
					}else{
						//across all levels
						if($field_label=="Level"){
							$def_for=$field_label." ".$field_value." (".$staff_status.")";
							$val = $field_label." ".$field_value;
						}
						  else {
							  $def_for=$field_value." (".$staff_status.")";
							  $val = $field_value;
						  }
					}
				}
			}
			return $folio."***".$def_for."***".$val."***".$staff_status."***".$amount;
		} // end function

function get_deduction_definedx($al_id){
	global $con;
	//
	$val = '';	$folio = '';	$cat = '';	$staff_status = '';	$level = '';	$rel='';	$value=0;
 	$pos = '';	$scale = '';	$rank = '';	$amount = 0;	$def_for = '';	$sex='';	$criteria='';
		
	$this->sql="select * from deductiontb where id=". mysqli_real_escape_string($con, $al_id);		
	$this->qry= mysqli_query($con, $this->sql);
	if($this->qry){
		if( mysqli_num_rows($this->qry) == 1){
			if ($this->row =  mysqli_fetch_array($this->qry, 3 ))	{
				$folio = $this->row['folio_code'];
				$cat = $this->row['category'];
				$staff_status = $this->row['staff_status'];
				$criteria = $this->row['criteria'];
				//$field_value = $this->row['field_value'];
				$scale = $this->row['scale'];
				$rank = $this->row['rank'];
				$rel = $this->row['religion'];
				$sex = $this->row['sex'];
				$pos = $this->row['position'];
				$value = $this->row['value'];
			}
		}else return 'Error reading record!1'. mysqli_error($con);
	}else return 'Error reading record!2'. mysqli_error($con);
	//do the value test
	if($cat == "All"){
		$def_for="All Staff||";
	}else{	//for categorized definitions
		if($staff_status != '' or $staff_status != "All"){
			//Make selection by staff status
			if( ($level != '' and $level != "All") and ($scale == '' or $scale == "All") ){
				//Make selection by status and level (Level only)
				$def_for=$staff_status."|Level|"; $val=$staff_status."|".$level."|";
			}
	
			if( ($scale != '' and $scale != "All") and ($level == '' or $level == "All") ){
				//Make selection by status and level (Scale Only)
				$def_for=$staff_status."|Scale|"; $val=$staff_status."|".$scale."|";
			}
			
			if( ($level != '' and $level != "All") and ($scale != '' and $scale != "All") ){
				//Make selection by status and level (Level And Scale)
				$def_for=$staff_status."|Level|Scale"; $val=$staff_status."|".$level."|".$scale;
			}
	
			if($pos != '' and $pos != "All"){
				//Make selection by Position
				$def_for=$staff_status."|Position|"; $val=$staff_status."|".$pos."|";
			}
	
			if($rank != '' and $rank != "All"){
				//Make selection by RANK
				$def_for=$staff_status."|Rank|"; $val=$staff_status."|".$rank."|";
			}
		}else{
			//DEFINITION FOR ALL/EMPTY STATUS
			if( ($scale != '' and $scale != "All") and ($level == '' or $level == "All") ){
				//Make selection by status and level (Scale Only)
				$def_for="All|Scale|"; $val="|".$scale."|";
			}
			
			if( ($level != '' and $level != "All") and ($scale != '' and $scale != "All") ){
				//Make selection by status and level (Level And Scale)
				$def_for="All|Level|Scale"; $val="|".$level."|".$scale;
			}
	
			if($pos != '' and $pos != "All"){
				//Make selection by Position
				$def_for="All|Position|"; $val="|".$pos."|";
			}
	
			if($rank != '' and $rank != "All"){
				//Make selection by RANK
				$def_for="All|Rank|"; $val="|".$rank."|";
			}
	
			if( ($sex != '' and $sex != "All") and ($scale != '' or $scale != "All") ){
				//Make selection by SEX AND SCALE
				$def_for="All|Sex|Scale"; $val="|".$level."|";
			}
	
			if( ($rel != '' and $rel != "All") and ($scale != '' or $scale != "All") ){
				//Make selection by RELIGION and SCALE (RELIGION AND SCALE)
				$def_for="All|Religion|Scale"; $val="|".$level."|";
			}
		}
	
	}//END IF if($cat == "All"){
	return $folio."***".$def_for."***".$val."***".$staff_status."***".$amount;
} // end function
		public function get_deduction_defined($al_id){
			global $con;
			//
			$val = '';	$folio = '';	$cat = '';	$staff_status = '';	$level = '';	$rel='';	$value=0;
			$pos = '';	$scale = '';	$rank = '';	$amount = 0;	$def_for = '';	$sex='';	$criteria='';
				
			$this->sql="select * from deductiontb where id=". mysqli_real_escape_string($con, $al_id);		
			$this->qry= mysqli_query($con, $this->sql);
			if($this->qry){
				if( mysqli_num_rows($this->qry) == 1){
					if ($this->row =  mysqli_fetch_array($this->qry, 3 ))	{
						$folio = $this->row['folio_code'];
						$cat = $this->row['category'];
						$staff_status = $this->row['staff_status'];
						$criteria = $this->row['criteria'];
						//$field_value = $this->row['field_value'];
						$scale = $this->row['scale'];
						$rank = $this->row['rank'];
						$scale = $this->row['religion'];
						$rank = $this->row['sex'];
						$value = $this->row['value'];
					}
				}else return 'Error reading record!1'. mysqli_error($con);
			}else return 'Error reading record!2'. mysqli_error($con);
				//do the value test
			if($cat == "All"){
				$def_for="All Staff";
			}else{	//for categorized definitions
			  if($staff_status == "" or $staff_status == "All"){	//for all staff category (Academic/Non-Academic)
				 if($level == "" && $level == "All"){	//for all level
					if($sex != "" && $sex != "All"){	//defined for particular sex
						$def_for = $sex." (Academic/Non-Academic)";		$val = $sex;
					}elseif($religion != "" && $religion != "All"){	//defined for all religion
						$def_for = $religion." (Academic/Non-Academic)";	$val = $religion;
					}
					/*
					elseif($pos != "" && $pos != "All"){	//defined for all position
						$def_for = $pos." (Academic/Non-Academic)";	$val = $pos;
					}elseif($rank != "" && $rank != "All"){	//defined for all rank
						$def_for = $rank." (Academic/Non-Academic)";	$val = $rank;
					}elseif($scale != "" && $scale != "All"){	//defined for all scale
						$def_for = $scale." (Academic/Non-Academic)";	$val = $scale;
					}*/
				 }else{	//defined for particular level
					if($sex != "" && $sex != "All"){	//defined for particular sex
						$def_for = $sex." (Level $level: Academic/Non-Academic)";		$val = $sex;
					}elseif($religion != "" && $religion != "All"){	//defined for all religion
						$def_for = $religion." (Level $level: Academic/Non-Academic)";	$val = $religion;
					}
					/*
					elseif($pos != "" && $pos != "All"){	//defined for all position
						$def_for = $pos." (Academic/Non-Academic)";	$val = $pos;
					}elseif($rank != "" && $rank != "All"){	//defined for all rank
						$def_for = $rank." (Academic/Non-Academic)";	$val = $rank;
					}elseif($scale != "" && $scale != "All"){	//defined for all scale
						$def_for = $scale." (Academic/Non-Academic)";	$val = $scale;
					}*/
				 } //end if($level == "" && $level == "All"){
			  }else{ 		  // CATEGORISED STAFF STATUS [EITHER ACADEMIC OR NON-ACADEMIC]
				 if($level == "" && $level == "All"){	//for all level
					if($sex != "" && $sex != "All"){	//defined for particular sex
						$def_for = $sex." (".$staff_status.")";		$val = $sex;
					}elseif($religion != "" && $religion != "All"){	//defined for all religion
						$def_for = $religion." (".$staff_status.")";	$val = $religion;
					}elseif($pos != "" && $pos != "All"){	//defined for all position
						$def_for = $pos." (".$staff_status.")";	$val = $pos;
					}elseif($rank != "" && $rank != "All"){	//defined for all rank
						$def_for = $rank." (".$staff_status.")";	$val = $rank;
					}elseif($scale != "" && $scale != "All"){	//defined for all scale
						$def_for = $scale." (".$staff_status.")";	$val = $scale;
					}
				 }else{	//defined for particular level
					if($sex != "" && $sex != "All"){	//defined for particular sex
						$def_for = "Level ".$level." (".$sex.": ".$staff_status.")";		$val = $sex;
					}elseif($religion != "" && $religion != "All"){	//defined for a particular  religion
						$def_for = "Level ".$level." (".$religion.": ".$staff_status.")";	$val = $religion;
					}elseif($scale != "" && $scale != "All"){	//defined for a particular scale
						$def_for = "Level ".$level." (".$staff_status.")";	$val = $scale;
					}
				 } //END IF($level == "" && $level == "All"){
			  } //END IF($staff_status == "" or $staff_status == "All"){
			}//END IF if($cat == "All"){
			return $folio."***".$def_for."***".$val."***".$staff_status."***".$amount;
		} // end function
		
		public function get_user_data($fileno, $fetchfield){
			global $con;
			//check admin account to classify administrator login from user logins
			$this->sql="select ".$fetchfield.
			" from stafftb where fileno='". mysqli_real_escape_string($con, $fileno)."' and status='Active'";		
			$this->qry= mysqli_query($con, $this->sql);
			if($this->qry){
				if( mysqli_num_rows($this->qry) == 1){
					if ($this->row =  mysqli_fetch_array($this->qry, 3 ))	{
						return $this->row[0];
					}
				}else return 'Error reading record!'. mysqli_error($con);
			}else return 'Error reading record!'. mysqli_error($con);
		} // end function get_user_data
		public function get_votebook_sum($folio_code, $year, $budget_type='', $quarter=''){
			global $con;
			//get the sum of money spent per anum for a folio code per year from the VoteBook
			$this->sql="select amount from budget_votebooktb where budget_folio_code='". mysqli_real_escape_string($con, $folio_code).
			"' and operation_year='". mysqli_real_escape_string($con, $year)."' and operation_quarter='".
			 mysqli_real_escape_string($con, $quarter)."'"; // and budget_category='". mysqli_real_escape_string($con, $budget_type)."' and status='PAID' 
			$this->qry= mysqli_query($con, $this->sql);
			$vsum = 0;
			if($this->qry){
				while ($this->row =  mysqli_fetch_array($this->qry, 3 ))	{
					$vsum =+ $this->row[0];
				}
				return $vsum;
			}else return -1;
		} //end function get_votebook_sum
		
		public function get_any_value($item, $table, $field, $value, $more=''){
			global $con;
			$this->sql="select ".$item." from ".$table." where ".$field."='". mysqli_real_escape_string($con, $value)."'".$more;
			//echo "kj".$this->sql; exit;
			$this->qry= mysqli_query($con, $this->sql);
			if($this->qry){
				if( mysqli_num_rows($this->qry)){
					if($this->row =  mysqli_fetch_array($this->qry, 3 ))	return $this->row[0];
				}else return "";
			}else
				return "Error!";
		} //end function get_votebook_sum
		
	}
	
?>