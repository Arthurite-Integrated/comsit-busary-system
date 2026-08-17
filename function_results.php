<?php
@require_once('connect.php');
//$mr->set_score(30,40); $mr->set_course_code($j->course_code); sample usage to set the value of this class
//$mr->get_total() //how to call the functions
class myresult
 {
	 var $ca,$exam,$code,$prog_id,$session;
	 
	 function __construct($c,$e,$cde,$prog_id1,$session1)
	  {
		  $this->ca=$c;
		  $this->exam=$e;
		  $this->code=$cde;
		  $this->prog_id=$prog_id1;
		  $this->session=$session1;
	  }
	  
	  
	  function get_total()
	   {
		   return $this->ca + $this->exam;
	   }
	  
	  function get_grade()
	   {
		    $final_total=$this->get_total();
		   			if($final_total>=70 and $final_total<=100)
					    $grade="A";
					  elseif ($final_total>=60 and $final_total<=69)
					    $grade="B";
					  elseif ($final_total>=50 and $final_total<=59)
					    $grade="C";
					  elseif ($final_total>=45 and $final_total<=49)
					    $grade="D";
					  elseif ($final_total>=40 and $final_total<=44)
					    $grade="E";
					  elseif ($final_total>=0 and $final_total<=39)
					    $grade="F";
			return $grade;
	   }
	   
	  function get_point()
	   {
		    $final_total=$this->get_total();
		   			if($final_total>=70 and $final_total<=100)
					    $point=5;
					  elseif ($final_total>=60 and $final_total<=69)
					    $point=4;
					  elseif ($final_total>=50 and $final_total<=59)
					    $point=3;
					  elseif ($final_total>=45 and $final_total<=49)
					    $point=2;
					  elseif ($final_total>=40 and $final_total<=44)
					    $point=1;
					  elseif ($final_total>=0 and $final_total<=39)
					    $point=0;
			return $point;
	   }
	   
	   function get_unit()
	    {
			$res_u=mysqli_query($con, "SELECT unit from deptcoursetb where course_code='$this->code' and prog_id='$this->prog_id' and session='$this->session'");
			$rs_u=mysqli_fetch_array($res_u);
			$unit=$rs_u['unit'];
			return $unit;
		}
		function get_status()
	    {
			$res_u=mysqli_query($con, "SELECT status from deptcoursetb where course_code='$this->code' and prog_id='$this->prog_id' and session='$this->session'");
			$rs_u=mysqli_fetch_array($res_u);
			$unit=$rs_u['status'];
			return $unit;
		}
		function get_wgp()
		{
			return $this->get_unit() * $this->get_point();
		}
		function get_classofdegree($final_total)
	   {
		   // $final_total=$this->get_total();
		   			if($final_total>=4.50 and $final_total<=5.00)
					    $grade="First Class Honours";
					  elseif ($final_total>=3.50 and $final_total<=4.49)
					    $grade="Second Class Honours (Upper Division)";
					  elseif ($final_total>=2.40 and $final_total<=3.49)
					    $grade="Second Class Honours (Lower Division)";
					  elseif ($final_total>=1.50 and $final_total<=2.39)
					    $grade="Third Class Honours";
					  elseif ($final_total>=1.00 and $final_total<=1.49)
					    $grade="Pass";
					  
			return $grade;
	   }
 }
?>