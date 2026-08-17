<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@$_SESSION['role'];
 $staff_category=@$_SESSION['staff_category'];



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php include("required_jQuery_files.php");
include "function.php";?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
	var str;
	

 if(cv=='login') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='forget_password') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
		});
  }//end of putme_login
  
 if(cv=='main_login') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,username:v,password:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='pass_recovery_update') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,uname:v,email:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='another') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		
		});
  }//end of putme_login
  
} //end of swapcontent
 </script>
</head>
<body class="subpage">

<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
	<?php include_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>Heading of The page</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<center><strong>
<img src="<?php echo $val_str[1];?>" width="50" height="50"/><br>
<?php echo $val_str[0]."<br><u><i>$app_year Promotion Application</u></i>";?>
</strong>
<?php
$db->sql("select * from stafftb where fileno='$fileno'");
			if(get_magic_quotes_gpc()){ $t= @json_decode(stripslashes($db->getResult()));$s=@json_decode(stripslashes($t->data)); }
			else{ $t= @json_decode($db->getResult());  $s=@json_decode($t->data);}
			if($t->row <=0)
				{
					echo "<script>alert('No such File Number in the database');</script>";
					exit();
				}
			$scalename=@get_current_scalename();
			$fullname=@get_staff_name($fileno) ;
			$staffstatus=@get_staff_status($s->level);
			$department=@get_dept_name($s->dept_code); 
			
			$db->sql("select * from hr_promotion_apptb where fileno='$fileno' and application_year='$app_year'");
			if(get_magic_quotes_gpc()){ $t= @json_decode(stripslashes($db->getResult()));$p=@json_decode(stripslashes($t->data)); }
			else{ $t= @json_decode($db->getResult());  $p=@json_decode($t->data);}
			$rec_found=false;
			if($t->row >0)
				{
					$rec_found=true;
					//echo "$p->next_post, $p->grade_level";
				}
			else
				{
				?>
				<script language="javascript">window.alert("Record Not Found"); window.close();</script> 
				<?php
				}
?>
<center>
<br><table align="center" border="1" cellpadding="0" cellspacing="0">
<tr><th>Name</th><td><?php echo $fullname;?></td><th>Age Next Birthday</th><td><?php echo date("Y")-date("Y",strtotime($s->date_of_birth));?></td></tr>
<tr><th>Date of first Appointment</th><td><?php echo  date("d/m/Y",strtotime($s->date_of_1st_appt));?></td><th>Post of first Appointment</th><td><?php echo $s->post_of_1st_appt;?> <strong>Salary :</strong> =N=<?php echo @number_format($s->initial_salary,2);?> </td></tr>
<tr><th>Present Status</th><td><?php echo "Level ".sprintf("%02d",$s->level)." / Step ".sprintf("%02d",$s->step);?>  <strong>Present Salary :</strong> <?php echo $s->present_salary;?></td><th>Date of last promotion</th><td><?php echo date("d/m/Y",strtotime($s->date_of_present_appt));?></td></tr>
<tr><th>Department</th><td><?php echo $department;?></td><th>Staff Category</th><td><?php echo $s->category;?></td></tr>
<tr><th>Staff Status</th><td colspan="3"> <?php echo $staffstatus;?></td></tr>
</table>
</center>

<!--bio-->
<table width="100%" border='1' cellpadding='0' cellspacing='0'>
                                            <tr>
                                              <td >Next Post</td>
                                              <td><?php echo $p->next_post;?></td>
                                              <td >Grade Level</td>
                                              <td ><?php if($rec_found) echo $p->grade_level; ?></td>
                                            </tr>
                                            <tr>
                                              <td>Salary</td>
                                              <td><?php if($rec_found) echo $p->salary; ?></td>
                                              <td>Courses undertaken during period of report</td>
                                              <td><?php if($rec_found) echo $p->course_undertaken; else echo '';?></td>
                                            </tr>
                                            <tr>
                                              <td>Total number of days absent on sick/casual leave during period of report</td>
                                              <td><?php if($rec_found) echo $p->no_of_days_absent; else echo '';?></td>
                                              <td>Duties</td>
                                              <td><?php if($rec_found) echo $p->duties; else echo '';?></td>
                                            </tr>
                                            <tr>
                                              <td>Acting Appointment held since last report</td>
                                              <td><?php if($rec_found) echo $p->acting_appointment; else echo '';?></td>
                                              <td>Year granted study with/without pay/sandwich (if any)</td>
                                              <td><?php if($rec_found) echo $p->study_leave_year; else echo '';?></td>
                                            </tr>
                                            <tr>
                                              <td>Duration of study leave with/without pay/sandwich</td>
                                              <td><?php if($rec_found) echo $p->study_leave_duration; else echo '';?></td>
                                              <td>Qualification obtained after the course and year</td>
                                              <td><?php if($rec_found) echo $p->qualification_obtained; else echo '';?></td>
                                            </tr>
                                            <tr>
                                              <td>Present Job</td>
                                              <td><?php if($rec_found) echo $p->present_job; else echo '';?></td>
                                              <td>Job Description</td>
                                              <td><?php if($rec_found) echo $p->job_description; else echo '';?></td>
                                            </tr>
                                            <tr>
                                              <td>In the order of importance, State the main duties performed during period of report</td>
                                              <td><?php if($rec_found) echo $p->duties_performed; else echo '';?></td>
                                              <td>State any ad-hoc duties performed which are not of a continuous nature</td>
                                              <td><?php if($rec_found) echo $p->adhoc_duties_performed; else echo '';?></td>
                                            </tr>
                                           </table>
										   <br>
										   <fieldset>
											  <legend>Present Qualification with Dates</legend>
											  <?php echo list_present_qualification($fileno,$app_year,'no action');?>
											</fieldset>
											<br>
											<fieldset>
											  <legend>Scholarship and Prizes / Honours and Distinction</legend>
											  <?php echo list_scholarship_prize($fileno,$app_year,'no action');?>
											</fieldset>
											<br>
											<fieldset>
											  <legend>Training Programme / Conference Attended</legend>
											  <?php echo list_training_programme($fileno,$app_year,'no action');?>
											</fieldset>
											<br>
											<fieldset>
											  <legend><b>Research Interest</b></legend>
											  <?php echo list_research_interest($fileno,$app_year,'no action');?>
											</fieldset>
											<br>
											<fieldset>
											  <legend><b>Publication</b></legend>
											  <?php echo list_publication($fileno,$app_year,'no action');?>
											</fieldset>



<!--end of bio-->











<br><br><input name="button" type="button" onClick="window.print();exitform();return false; 
" value=" Print this page " />

</center>                                
           
            </div><!-- end of content box -->

        </div> <!-- end of content tooplate_content-->
    
    </div> <!-- end of content tooplate_main-->
	
    <div class="cleaner"></div>    
</div> <!-- end of wrapper tooplate_wrapper-->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->

</body>
</html>