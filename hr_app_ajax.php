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
                                
<?php
 @session_start();
 @ini_set('max_execution_time', 60000000000);
 @ini_set("memory_limit", "51200M");
 @require_once('connect.php');
 @require_once('function.php');
 
 	@require_once('class/mysqli_class.php');
	$db = new Database();
	$db->connect();

 $id=@$_REQUEST['contentvar'];
 $contentvar=$_REQUEST['contentvar'];
 
function smsalert($msg,$phoneno){
	$msg=@rawurlencode($msg);
	$phoneno="+234".@substr($phoneno,-10);
	$sender=@rawurlencode('UNILORIN');
 $r=@file_get_contents("http://api.smartsmssolutions.com/smsapi.php?username=jmklaru&password=0712764&sender=$sender&recipient=$phoneno&message=$msg");
}

///////////////////////////////////Human Resources Management System (HRMS) /////////////////////////////////////////
if($id=="update_biodata")
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	//echo $j->state." ".$j->lga." ".$j->fileno." ".$j->sex." ".$j->date_of_birth." ".$j->nationality; exit;
	//$dob=@date('Y-m-d',@strtotime($j->dob));
	if($j->nationality=='Nigerian')
	 {
		 $country="Nigeria";
	 }
	else
	 {
		 $country=$j->country;
	 }
	
	$res_c=@mysqli_query($con, "select * from hr_applicanttb where appno='$j->fileno'");
	
	$login_id=@$_SESSION['login_id'];
	if(@mysqli_num_rows($res_c)<=0)
	 {
		 $default_password=@base64_encode('1111');
	 mysqli_query($con, "insert into hr_applicanttb set appno='$j->fileno',title='$j->title',surname='".@mysqli_real_escape_string($con, $j->surname)."',first_name='".@mysqli_real_escape_string($con, $j->first_name)."',other_name='".@mysqli_real_escape_string($con, $j->other_name)."',maiden_name='".@mysqli_real_escape_string($con, $j->maiden_name)."',sex='$j->sex',dept_code='$j->dept',unit_code='$j->unit',marital_status='$j->marital_status',religion='$j->religion',staff_status='$j->staff_status',category='$j->category',date_of_1st_appt='$j->date_of_1st_appt',date_of_assumption='$j->date_of_assumption',date_of_present_appt='$j->date_of_present_appt',initial_level='$j->level',initial_step='$j->step',level='$j->level',step='$j->step',qualification='".@mysqli_real_escape_string($con, $j->qualification)."',rank='$j->rank',employment_status='$j->employment_status',date_of_birth='$j->date_of_birth',place_of_birth='".@mysqli_real_escape_string($con, $j->place_of_birth)."',nationality='$j->nationality',state_id='$j->state',lga_id='$j->lga',country='$country',senatorial_district='".@mysqli_real_escape_string($con, $j->senatorial_district)."',contact_address='".@mysqli_real_escape_string($con, $j->contact_address)."',residential_address='".@mysqli_real_escape_string($con, $j->residential_address)."',permanent_address='".@mysqli_real_escape_string($con, $j->permanent_address)."',email='$j->email',phone_no='$j->phone_no',bank_name='".@mysqli_real_escape_string($con, $j->bank_name)."',acct_no='$j->acct_no',last_place_of_residence='".@mysqli_real_escape_string($con, $j->last_place_of_residence)."',languages_spoken='".@mysqli_real_escape_string($con, $j->languages_spoken)."',passport_number='$j->passport_number',passport_place='".@mysqli_real_escape_string($con, $j->passport_place)."',passport_date_issue='$j->passport_date_issue',hobbies='".@mysqli_real_escape_string($con, $j->hobbies)."',disability='$j->disability',disability_reason='".@mysqli_real_escape_string($con, $j->disability_reason)."',court_case='".@mysqli_real_escape_string($con, $j->court_case)."',status='$j->status',spouse_name='".@mysqli_real_escape_string($con, $j->spouse_name)."',spouse_address='".@mysqli_real_escape_string($con, $j->spouse_address)."',spouse_occupation='".@mysqli_real_escape_string($con, $j->spouse_occupation)."',guidance_name='".@mysqli_real_escape_string($con, $j->guidance_name)."',guidance_nationality='$j->guidance_nationality',guidance_state='$j->guidance_state',guidance_occupation='".@mysqli_real_escape_string($con, $j->guidance_occupation)."',guidance_address='".@mysqli_real_escape_string($con, $j->guidance_address)."',guidance_email='$j->guidance_email',guidance_phone_no='$j->guidance_phone_no',next_name='".@mysqli_real_escape_string($con, $j->next_name)."',next_address='".@mysqli_real_escape_string($con, $j->next_address)."',next_email='$j->next_email',next_phone_no='$j->next_phone_no',next_relationship='$j->next_relationship',mother_name='".@mysqli_real_escape_string($con, $j->mother_name)."',mother_nationality='$j->mother_nationality',mother_state='$j->mother_state',mother_address='".@mysqli_real_escape_string($con, $j->mother_address)."',force_no='$j->force_no',highest_force_rank='".@mysqli_real_escape_string($con, $j->highest_force_rank)."',force_period='$j->force_period',force_character='".@mysqli_real_escape_string($con, $j->force_character)."',other_info='".@mysqli_real_escape_string($con, $j->other_info)."',password='$default_password',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
	 
	 logs($login_id,'Save Applicant Record',"$login_id insert applicant record with fileno $j->fileno");
	 
	 } //end of save
	else
	 {
		 //update staff record section
		  mysqli_query($con, "update hr_applicanttb set title='$j->title',surname='".@mysqli_real_escape_string($con, $j->surname)."',first_name='".@mysqli_real_escape_string($con, $j->first_name)."',other_name='".@mysqli_real_escape_string($con, $j->other_name)."',maiden_name='".@mysqli_real_escape_string($con, $j->maiden_name)."',sex='$j->sex',marital_status='$j->marital_status',religion='$j->religion',category='$j->category',qualification='".@mysqli_real_escape_string($con, $j->qualification)."',date_of_birth='$j->date_of_birth',place_of_birth='".@mysqli_real_escape_string($con, $j->place_of_birth)."',nationality='$j->nationality',state_id='$j->state',lga_id='$j->lga',country='$country',senatorial_district='".@mysqli_real_escape_string($con, $j->senatorial_district)."',contact_address='".@mysqli_real_escape_string($con, $j->contact_address)."',residential_address='".@mysqli_real_escape_string($con, $j->residential_address)."',permanent_address='".@mysqli_real_escape_string($con, $j->permanent_address)."',email='$j->email',phone_no='$j->phone_no',bank_name='".@mysqli_real_escape_string($con, $j->bank_name)."',acct_no='$j->acct_no',last_place_of_residence='".@mysqli_real_escape_string($con, $j->last_place_of_residence)."',languages_spoken='".@mysqli_real_escape_string($con, $j->languages_spoken)."',passport_number='$j->passport_number',passport_place='".@mysqli_real_escape_string($con, $j->passport_place)."',passport_date_issue='$j->passport_date_issue',hobbies='".@mysqli_real_escape_string($con, $j->hobbies)."',disability='$j->disability',disability_reason='".@mysqli_real_escape_string($con, $j->disability_reason)."',court_case='".@mysqli_real_escape_string($con, $j->court_case)."',spouse_name='".@mysqli_real_escape_string($con, $j->spouse_name)."',spouse_address='".@mysqli_real_escape_string($con, $j->spouse_address)."',spouse_occupation='".@mysqli_real_escape_string($con, $j->spouse_occupation)."',guidance_name='".@mysqli_real_escape_string($con, $j->guidance_name)."',guidance_nationality='$j->guidance_nationality',guidance_state='$j->guidance_state',guidance_occupation='".@mysqli_real_escape_string($con, $j->guidance_occupation)."',guidance_address='".@mysqli_real_escape_string($con, $j->guidance_address)."',guidance_email='$j->guidance_email',guidance_phone_no='$j->guidance_phone_no',next_name='".@mysqli_real_escape_string($con, $j->next_name)."',next_address='".@mysqli_real_escape_string($con, $j->next_address)."',next_email='$j->next_email',next_phone_no='$j->next_phone_no',next_relationship='$j->next_relationship',mother_name='".@mysqli_real_escape_string($con, $j->mother_name)."',mother_nationality='$j->mother_nationality',mother_state='$j->mother_state',mother_address='".@mysqli_real_escape_string($con, $j->mother_address)."',force_no='$j->force_no',highest_force_rank='".@mysqli_real_escape_string($con, $j->highest_force_rank)."',force_period='$j->force_period',force_character='".@mysqli_real_escape_string($con, $j->force_character)."',other_info='".@mysqli_real_escape_string($con, $j->other_info)."' where appno='$j->fileno'") or die( mysqli_error($con));
		 
		logs($login_id,'Update Applicant Record',"$login_id updated applicant record with fileno $j->fileno");
	 } //end of update staff record
	
	
	echo "<script> alert('Applicant biodata updated sucessfully');</script>";
	
} //end of update biodata for staff

if($id=='delete_biodata')
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	@mysqli_query($con, "delete from hr_applicanttb where appno='$j->fileno'");
	echo "<script> alert('Applicant biodata delete sucessfully');</script>";
	
} //end of delete staff biodata

if($id=='load_rank')
 {
	 $category=@$_REQUEST['category'];
	 //echo "$dept_code"; ?>
	 <select name="rank" id="rank">
                        <option selected="selected" value="">---</option>
                        <?php
                          $res_c=@mysqli_query($con, "select * from hr_positiontb where category='$category' order by category");
                              while($rs_c=@mysqli_fetch_array($res_c))
                                 {
                                   $position=@$rs_c['position'];
                                   echo "<option value='$position'>$position</option>";
                                 }
						 ?>
     </select> <?php
 }
 
if($id=="add_position")  //add_position
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
	if($action=='save')
	 {
		$res_count=@mysqli_query($con, "select count(*) as total from hr_app_positiontb where appno='$j->fileno'");
		$rs_count=@mysqli_fetch_array($res_count); $total=$rs_count['total'];
		if($total<=2)
		 {
				 mysqli_query($con, "insert into hr_app_positiontb set appno='$j->fileno',dept_code='$j->app_dept',unit_code='$j->app_unit',position='$j->app_position',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
				
				$sql="select * from hr_app_positiontb where appno='$j->fileno' order by position";
				
				echo "<script> alert('The specified position has been saved');</script>";
		 } //end of not exceeeding three position/ranks
		 
		 $sql="select * from hr_app_positiontb where appno='$j->fileno' order by position";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_positiontb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_positiontb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_positiontb where appno='$fileno' order by position";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_positiontb where appno='$j->fileno' order by position";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>POSITION/RANK</th><th>DEPARTMENT</th><th>UNIT</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['position']}</td><td>".@get_dept_name($rs_v['dept_code'])."</td><td>".@get_unit_name($rs_v['dept_code'],$rs_v['unit_code'])."</td><td><a href=\"javascript:swapcontent('add_position','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add position

if($id=="add_child")  //add_children
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
	if($action=='save')
	 {
		@mysqli_query($con, "insert into hr_app_childtb set appno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->child_name)."',date_of_birth='$j->child_dob',sex='$j->child_sex',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
		
		$sql="select * from hr_app_childtb where appno='$j->fileno'";
		
		echo "<script> alert('The specified Child\'s detail has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_childtb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_childtb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_childtb where appno='$fileno' order by name";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_childtb where appno='$j->fileno' order by name";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>FULLNAME</th><th>DATE OF BIRTH</th><th>SEX</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['name']}</td><td>".date('d/m/Y',strtotime($rs_v['date_of_birth']))."</td><td>{$rs_v['sex']}</td><td><a href=\"javascript:swapcontent('add_child','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add children

if($id=="add_employment")  //add_employment
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_employmenttb set appno='$j->fileno',employer_name='".@mysqli_real_escape_string($con, $j->emp_name)."',location='".@mysqli_real_escape_string($con, $j->emp_location)."',rank='$j->emp_rank',salary='$j->emp_salary',from_year='$j->emp_year_from',to_year='$j->emp_year_to',leaving_reason='".@mysqli_real_escape_string($con, $j->emp_leaving)."',employment_type='$j->emp_type',status='$j->emp_status',duty='".@mysqli_real_escape_string($con, $j->emp_duty)."',bond_question='$j->emp_bond',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_employmenttb where appno='$j->fileno' order by from_year";
		
		echo "<script> alert('The specified employment detail has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_employmenttb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_employmenttb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_employmenttb where appno='$fileno' order by from_year";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_employmenttb where appno='$j->fileno' order by from_year";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>EMPLOYER NAME</th><th>RANK</th><th>FROM</th><th>TO</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['employer_name']}</td><td>{$rs_v['rank']}</td><td>{$rs_v['from_year']}</td><td>{$rs_v['to_year']}</td><td><a href=\"javascript:swapcontent('add_employment','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add employment


if($id=="add_education")  //add_education
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_academic_edutb set appno='$j->fileno',school_name='".@mysqli_real_escape_string($con, $j->edu_name)."',school_type='$j->edu_type',	qualification='$j->edu_qual',degree_class='$j->edu_grade',from_month='$j->edu_month_from',from_year='$j->edu_year_from',to_month='$j->edu_month_to',to_year='$j->edu_year_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_academic_edutb where appno='$j->fileno' order by from_year";
		
		echo "<script> alert('The specified academic record has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_academic_edutb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_academic_edutb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_academic_edutb where appno='$fileno' order by from_year";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_academic_edutb where appno='$j->fileno' order by from_year";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>INSTITUTION NAME</th><th>TYPE</th><th>QUALIFICATION</th><th>GRADE</th><th>FROM</th><th>TO</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['school_name']}</td><td>{$rs_v['school_type']}</td><td>{$rs_v['qualification']}</td><td>{$rs_v['degree_class']}</td><td>{$rs_v['from_year']}</td><td>{$rs_v['to_year']}</td><td><a href=\"javascript:swapcontent('add_education','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add education

if($id=="add_publication")  //add_publication
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_publicationtb set appno='$j->fileno',title='".@mysqli_real_escape_string($con, $j->pub_title)."',author='".@mysqli_real_escape_string($con, $j->pub_author)."',type='$j->pub_type',publisher='".@mysqli_real_escape_string($con, $j->pub_publisher)."',journal='".@mysqli_real_escape_string($con, $j->pub_journal)."',year_published='$j->pub_year',status='$j->pub_status',category='$j->pub_category',page_no='$j->pub_page_no',volume='$j->pub_volume',issue='$j->pub_issue',url='$j->pub_url',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_publicationtb where appno='$j->fileno' order by year_published";
		
		echo "<script> alert('The specified publication has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_publicationtb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_publicationtb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_publicationtb where appno='$fileno' order by year_published";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_publicationtb where appno='$j->fileno' order by year_published";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>TITLE</th><th>AUTHOR(S)</th><th>PUBLISHER</th><th>TYPE</th><th>CATEGORY</th><th>YEAR PUBLISHED</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['title']}</td><td>{$rs_v['author']}</td><td>{$rs_v['publisher']}</td><td>{$rs_v['type']}</td><td>{$rs_v['category']}</td><td>{$rs_v['year_published']}</td><td><a href=\"javascript:swapcontent('add_publication','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add publication

if($id=="add_service")  //add_service
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_servicetb set appno='$j->fileno',service_type='".@mysqli_real_escape_string($con, $j->serv_type)."',service_place='$j->serv_place',service_details='".@mysqli_real_escape_string($con, $j->serv_detail)."',from_year='$j->serv_from',to_year='$j->serv_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_servicetb where appno='$j->fileno' order by service_type,from_year";
		
		echo "<script> alert('The specified service has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_servicetb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_servicetb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_servicetb where appno='$fileno' order by service_type,from_year";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_servicetb where appno='$j->fileno' order by service_type,from_year";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>TYPE</th><th>SERVICE PLACE</th><th>DETAILS</th><th>FROM</th><th>TO</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['service_type']}</td><td>{$rs_v['service_place']}</td><td>{$rs_v['service_details']}</td><td>{$rs_v['from_year']}</td><td>{$rs_v['to_year']}</td><td><a href=\"javascript:swapcontent('add_service','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add service


if($id=="add_research")  //add_research
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_researchtb set appno='$j->fileno',topic='".@mysqli_real_escape_string($con, $j->res_topic)."',status='$j->res_status',funding_source='".@mysqli_real_escape_string($con, $j->res_funding)."',start_date='$j->res_start_date',end_date='$j->res_end_date',amount_granted='$j->res_amount',project_value='$j->res_value',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_researchtb where appno='$j->fileno' order by start_date";
		
		echo "<script> alert('The specified research history has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_researchtb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_researchtb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_researchtb where appno='$fileno' order by start_date";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_researchtb where appno='$j->fileno' order by start_date";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>TOPIC</th><th>STATUS</th><th>FUNDING SOURCE</th><th>AMOUNT GRANTED</th><th>PROJECT VALUE</th><th>START DATE</th><th>END DATE</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['topic']}</td><td>{$rs_v['status']}</td><td>{$rs_v['funding_source']}</td><td>{$rs_v['amount_granted']}</td><td>{$rs_v['project_value']}</td><td>".date('d/m/Y',strtotime($rs_v['start_date']))."</td><td>".date('d/m/Y',strtotime($rs_v['end_date']))."</td><td><a href=\"javascript:swapcontent('add_research','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add research

if($id=="add_training")  //add_training
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con, 
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_training_apptb set appno='$j->fileno',training_type='$j->tra_type',start_date='$j->tra_start_date',end_date='$j->tra_end_date',training_title='".@mysqli_real_escape_string($con, $j->tra_title)."',location='".@mysqli_real_escape_string($con, $j->tra_location)."',venue='".@mysqli_real_escape_string($con, $j->tra_venue)."',no_paper_read='$j->tra_no_paper_read',sponsor='".@mysqli_real_escape_string($con, $j->tra_sponsor)."',amount_granted='$j->tra_amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_training_apptb where appno='$j->fileno' order by training_type,start_date";
		
		echo "<script> alert('The specified training has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_training_apptb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_training_apptb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_training_apptb where appno='$fileno' order by training_type,start_date";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_training_apptb where appno='$j->fileno' order by training_type,start_date";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>TYPE</th><th>TITLE/THEME</th><th>LOCATION</th><th>VENUE</th><th>SPONSOR</th><th>START DATE</th><th>END DATE</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['training_type']}</td><td>{$rs_v['training_title']}</td><td>{$rs_v['location']}</td><td>{$rs_v['venue']}</td><td>{$rs_v['sponsor']}</td><td>".date('d/m/Y',strtotime($rs_v['start_date']))."</td><td>".date('d/m/Y',strtotime($rs_v['end_date']))."</td><td><a href=\"javascript:swapcontent('add_training','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add training

if($id=='load_no_paper_read')
 {
	 $type=strtolower($_REQUEST['type']);
	 if($type=='conference')
	   echo "<br/>No of Paper Read: <input type='text' name='tra_no_paper_read' id='tra_no_paper_read'/>";
	 else
	   echo "";
 }
 
if($id=="add_prof_membership")  //add_prof_membership
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con, 
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_prof_membershiptb set appno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->prof_mem_name)."',category='$j->prof_mem_category',year_honoured='$j->prof_mem_year',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_prof_membershiptb where appno='$j->fileno' order by year_honoured";
		
		echo "<script> alert('The specified Professional Membership has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_prof_membershiptb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_prof_membershiptb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_prof_membershiptb where appno='$fileno' order by year_honoured";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_prof_membershiptb where appno='$j->fileno' order by year_honoured";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>NAME</th><th>CATEGORY</th><th>YEAR HONOURED</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['name']}</td><td>{$rs_v['category']}</td><td>{$rs_v['year_honoured']}</td><td><a href=\"javascript:swapcontent('add_prof_membership','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add prof_membership

if($id=="add_prof_qual")  //add_prof_qual
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con, 
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_prof_qualificationtb set appno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->prof_qual_name)."',grade='$j->prof_qual_grade',from_year='$j->prof_qual_from',to_year='$j->prof_qual_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_prof_qualificationtb where appno='$j->fileno' order by from_year";
		
		echo "<script> alert('The specified Professional Qualification has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_prof_qualificationtb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_prof_qualificationtb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_prof_qualificationtb where appno='$fileno' order by from_year";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_prof_qualificationtb where appno='$j->fileno' order by from_year";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>NAME</th><th>GRADE</th><th>FROM</th><th>TO</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['name']}</td><td>{$rs_v['grade']}</td><td>{$rs_v['from_year']}</td><td>{$rs_v['to_year']}</td><td><a href=\"javascript:swapcontent('add_prof_qual','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add prof_qual

if($id=="add_honour")  //add_honour
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con, 
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_recognitiontb set appno='$j->fileno',award_type='$j->honour_type',award_date='$j->honour_date',award_description='".@mysqli_real_escape_string($con, $j->honour_desc)."',prize='$j->honour_prize',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_recognitiontb where appno='$j->fileno' order by award_date";
		
		echo "<script> alert('The specified Award/Honour has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_recognitiontb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_recognitiontb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_recognitiontb where appno='$fileno' order by award_date";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_recognitiontb where appno='$j->fileno' order by award_date";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>AWARD TYPE</th><th>AWARD DATE</th><th>DESCRIPTION</th><th>PRIZE</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['award_type']}</td><td>".date('d/m/Y',strtotime($rs_v['award_date']))."</td><td>{$rs_v['award_description']}</td><td>{$rs_v['prize']}</td><td><a href=\"javascript:swapcontent('add_honour','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add honour

if($id=="add_country")  //add_country
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con, 
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_country_visitedtb set appno='$j->fileno',country='$j->country_name',visit_reason='".@mysqli_real_escape_string($con, $j->country_reason)."',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_country_visitedtb where appno='$j->fileno' order by country";
		
		echo "<script> alert('The specified Country has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_country_visitedtb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_country_visitedtb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_country_visitedtb where appno='$fileno' order by country";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_country_visitedtb where appno='$j->fileno' order by country";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>COUNTRY</th><th>REASON FOR VISITING</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['country']}</td><td>{$rs_v['visit_reason']}</td><td><a href=\"javascript:swapcontent('add_country','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add honour

if($id=="add_referee")  //add_referee
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	
	$login_id=$_SESSION['login_id'];
	//echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con, 
	if($action=='save')
	 {
		 mysqli_query($con, "insert into hr_app_refereetb set appno='$j->fileno',ref_name='".@mysqli_real_escape_string($con, $j->ref_name)."',ref_address='".@mysqli_real_escape_string($con, $j->ref_address)."',ref_occupation='$j->ref_occupation',ref_know_period='$j->ref_year',ref_email='$j->ref_email',ref_phone_no='$j->ref_phone_no',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
		
		$sql="select * from hr_app_refereetb where appno='$j->fileno' order by id";
		
		echo "<script> alert('The specified Referee has been saved');</script>";
	 }
	
	if($action=='delete')
	 {
	   //
	   $res_d=@mysqli_query($con, "select * from hr_app_refereetb where id='$r_id'");
	   $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['appno'];
	   
	   @mysqli_query($con, "delete from hr_app_refereetb where id='$r_id'");
	   echo "<script> alert('Record deleted successfully');</script>";
	   $sql="select * from hr_app_refereetb where appno='$fileno' order by id";
	 }
	 
	 if($action=='view')
	 {
	   //
	   $sql="select * from hr_app_refereetb where appno='$j->fileno' order by id";
	 }
	 
	$res_v=@mysqli_query($con, $sql);
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>NAME</th><th>OCCUPATION</th><th>ADDRESS</th><th>EMAIL</th><th>PHONE NO</th><th>ACTION</th></tr>";
	
	if(@mysqli_num_rows($res_v)>=1)
	 {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;
				 $id2=@$rs_v['id'];
					$tb.="<tr><td>$sn</td><td>{$rs_v['ref_name']}</td><td>{$rs_v['ref_occupation']}</td><td>{$rs_v['ref_address']}</td><td>{$rs_v['ref_email']}</td><td>{$rs_v['ref_phone_no']}</td><td><a href=\"javascript:swapcontent('add_referee','delete','$id2')\">DELETE</a></td></tr>";
			 } //end of while
			 
			 $tb.="</table></center>";
			 echo $tb;
	 } //end of if found
	else
	 echo "<center><font color='red'><b>No record to display</b></font></center>";
} //add referee

if($id=='load_pix')
{
	$fileno=$_REQUEST['fileno'];
	$pixpath=@$db->getPix2("pictures",strtoupper($fileno),"");
	//echo $fileno." Path: $pixpath";
    if(file_exists($pixpath))
		echo "<img src='$pixpath' width='200' height='200'/>";
	else
	   echo "<img src='pictures/nopix.jpg' width='200' height='200'/>";
}

if($id=='load_images')
{
	$appno=$_REQUEST['upload_appno'];
	//echo "EHRERRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR $appno";
	
	$sql="select * from hr_app_documenttb where appno='$appno' order by id";
	$res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>DOCUMENT TYPE</th><th>ACTION</th></tr>";
	while($rs_v=@mysqli_fetch_array($res_v))
	 {
		 ++$sn;
		 $id2=@$rs_v['id'];
		 if($sn%2==0) $bgcolor="#FFFF99"; else $bgcolor="white";
		 $path=@$rs_v['doc_path'];
		 $appno=@$rs_v['appno'];
		 $tb.="<tr bgcolor='$bgcolor'><td>$sn</td><td>{$rs_v['doc_type']}</td><td><a href='$path' target='_blank'>VIEW DOCUMENT</a> | <a href=\"javascript:swapcontent('delete_upload','delete','$id2','p','$appno')\">DELETE</a></td></tr>";
	 } //end of while
	 
	 $tb.="</table></center>";
	 echo $tb;
}

if($id=='delete_upload')
{
	$action=$_REQUEST['action'];
	$path=$_REQUEST['p'];
	$login_id=$appno=$_REQUEST['login_id'];
	$r_id=$_REQUEST['r_id'];
	/*echo "<script>alert('$login_id ACTION:$action PATH:$path');</script>"; exit; */
	
	if($action=='delete')
	 {
		 $res_p=@mysqli_query($con, "select doc_path from hr_app_documenttb where id='$r_id'");
		 $rs_p=@mysqli_fetch_array($res_p);
		 $path=$rs_p['doc_path'];
		 @unlink($path);  //delete the file
		 @mysqli_query($con, "delete from hr_app_documenttb where id='$r_id'");
		 echo "<script>alert('The specified file has been deleted');</script>";
	 }
	
	$sql="select * from hr_app_documenttb where appno='$appno' order by id";
	$res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
	$sn=0;
	$tb="<center><table><tr><th>S/NO</th><th>DOCUMENT TYPE</th><th>ACTION</th></tr>";
	while($rs_v=@mysqli_fetch_array($res_v))
	 {
		 ++$sn;
		 $id2=@$rs_v['id'];
		 if($sn%2==0) $bgcolor="#FFFF99"; else $bgcolor="white";
		 $path=@$rs_v['doc_path'];
		 $tb.="<tr bgcolor='$bgcolor'><td>$sn</td><td>{$rs_v['doc_type']}</td><td><a href='$path' target='_blank'>VIEW DOCUMENT</a> | <a href=\"javascript:swapcontent('delete_upload','delete','$id2','p','$appno')\">DELETE</a></td></tr>";
	 } //end of while
	 
	 $tb.="</table></center>";
	 echo $tb;
}

if($id=='display_load_docs')
{
	$fileno=$_REQUEST['fileno'];
	$sql="select * from hr_app_documenttb where appno='$fileno' order by id";
                                        $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
                                        $sn=0;
                                        $tb="<center><table><tr><th>S/NO</th><th>DOCUMENT TYPE</th><th>ACTION</th></tr>";
										if( mysqli_num_rows($res_v)>=1)
										{
											while($rs_v=@mysqli_fetch_array($res_v))
											 {
												 ++$sn;
												 $id2=@$rs_v['id'];
												 if($sn%2==0) $bgcolor="#FFFF99"; else $bgcolor="white";
												 $path=@$rs_v['doc_path'];
												 $appno=@$rs_v['appno'];
												 $tb.="<tr bgcolor='$bgcolor'><td>$sn</td><td>{$rs_v['doc_type']}</td><td><a href='$path' target='_blank'>VIEW DOCUMENT</a> | <a href=\"javascript:swapcontent('delete_upload','delete','$id2','p','$appno')\">DELETE</a></td></tr>";
											 } //end of while
											 
											 $tb.="</table></center>";
											 echo $tb;
										}
									  else
									    echo "<font color='red'><b>No record to display</b></font>";
}

if($id=='create_application')
{
	$surname=@$_REQUEST['surname'];
	$first_name=@$_REQUEST['first_name'];
	$other_name=@$_REQUEST['other_name'];
	$phone_no=@$_REQUEST['phone_no'];
	$email=@trim(@$_REQUEST['email']);
	$app_year=@$_REQUEST['app_year'];
	$password=@trim(@$_REQUEST['pass']);  //the name of the box is pass from initial app stage
	$base_password=@base64_encode($password);
	//echo "$surname $first_name $other_name $phone_no $email $app_year $password $base_password";
	$res_c=@mysqli_query($con, "select * from hr_applicanttb where email='$email' and app_year='$app_year'");
	if(@mysqli_num_rows($res_c)<=0)
	 {
		 $appno=@generate_appno($app_year);
		 @mysqli_query($con, "insert into hr_applicanttb set appno='$appno',surname='".@mysqli_real_escape_string($con, $surname)."',first_name='".@mysqli_real_escape_string($con, $first_name)."',other_name='".@mysqli_real_escape_string($con, $other_name)."',phone_no='$phone_no',email='$email',app_year='$app_year',password='$base_password',status='Active'");
		 
		 /////////////////Send email //////////////////////////////////////////////
		           $todayDate = @date("l, F d, Y.");
					
					$name='';
					$val_title=@explode("***",get_project_title());
					$to = $email; $subject = $val_title[0].": Vacancy Application";
					$msg_email="$surname $firstname $other_name you have successfully created an account for Vacancy application on our portal. Find below the details of your Login parameters.<br/><br/><br/><br/>Application No/Login ID: $appno<br/><br/>Password: $password<br/><br/><br/><br/>Management";
					
					$headers = "From: info@kwcoetl.com   \r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
					@mail($to,$subject,$msg_email,$headers);
		 /////////////////End of send email //////////////////////////////////////
		 
		 echo "<script>alert('You have successfully created an account. An email alert has been sent to your email address $email. Note that, your Application Number is $appno. Write this down for future use. Click on Login link to continue with your application.');</script>";
		 echo "<script>location='index.php';</script>";
	 }
	else
	  echo "<script>alert('You have already applied this year with Email: $email');</script>";
	  
}

if($id=='load_unit')
 {
	 $dept_code=@$_REQUEST['dept_code'];
	 //echo "$dept_code"; ?>
	 <select name="unit" id="unit">
                        <option selected="selected" value="">---</option>
                        <?php
                          $res_c=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' order by unit_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $unit_code=@$rs_c['unit_code'];
							  $unit_name=@$rs_c['unit_name'];
                              echo "<option value='$unit_code'>$unit_name</option>";
                           }
						 ?>
     </select> <?php
 }
 
 if($id=='load_unit_app')
 {
	 $dept_code=@$_REQUEST['dept_code'];
	 //echo "$dept_code"; ?>
	 <select name="app_unit" id="app_unit">
                        <option selected="selected" value="">---</option>
                        <?php
                          $res_c=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' order by unit_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $unit_code=@$rs_c['unit_code'];
							  $unit_name=@$rs_c['unit_name'];
                              echo "<option value='$unit_code'>$unit_name</option>";
                           }
						 ?>
     </select> <?php
 }
 
 if($id=='natdiv') //nationality
  {
	  $val=@$_REQUEST['val']; //this is the page to redirect to
	  if($val=='Non-Nigerian')
        {
		  echo "Country: <select name='country' id='country'><option selected value=''>---</option>";
		  $res_c=@mysqli_query($con, "select * from countrytb where country!='Nigeria' order by country");
		  while($rs_c=@mysqli_fetch_array($res_c))
		   {
			  $country=@$rs_c['country'];
			  echo "<option value='$country'>$country</option>";
		   }
		  echo "</select>";
		} //end of if non-nigeria
	  else  
	    {
		     echo "<span id='statediv'>State:<select name='state' id='state' onchange=\"swapcontent('lgadiv',document.getElementById('state').value)\"><option selected value=''>---</option>";
		                $res_c=@mysqli_query($con, "select * from statetb order by state_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $state_id=@$rs_c['state_id'];
							  $state_name=@$rs_c['state_name'];
                              echo "<option value='$state_id'>$state_name</option>";
                           }
					   
			 echo"</select>
                      <br /><br /></span> 
                       LGA: 
                       <span id='lgadiv'>
                       <select name='lga' id='lga'>
                     </select></span>";
		} //end of nigerian
  } //end of nationality
  
 if($id=='lgadiv')
 {
	 $val=@$_REQUEST['val'];
	 $res_l=@mysqli_query($con, "select * from lgatb where state_id='$val' order by lga_name");
	 echo "<select name='lga' id='lga'><option selected value=''>---</option>";
	 while($rs_l=@mysqli_fetch_array($res_l))
	 {
		 $lga_id=@$rs_l['lga_id'];
		 $lga_name=@$rs_l['lga_name'];
		 echo "<option value='$lga_id'>$lga_name</option>";
	 }
	 echo "</select>";
	 
 } //end of fetch state
 
 if($id=='load_staff_details')
{
	$fileno=@$_REQUEST['fileno'];
	$db->sql("select * from hr_applicanttb where appno='$fileno'");
		 if(get_magic_quotes_gpc())
		   $t= @json_decode(stripslashes($db->getResult()));
		 else
          $t= @json_decode($db->getResult());
		  $s_array=array(s_detail=>"",msg=>"");
		  
		  if($t->row>=1) //fond
          {
			  $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
		  }
		  exit;
}
//////////////////////////////////End of human resources management system /////////////////////////////////////////
?>                                
           
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