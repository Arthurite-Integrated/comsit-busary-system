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
                                
<form name="frmbio" id="frmbio" enctype="multipart/form-data" action="pixupload.php" method="post" target="uploadedImage">
<?php include("header_main.php");?>
<div id="page-wrapper">
	<?php //include("slider.php");?>
	
	<div id="page" class="container">
		<!--<div class="content">-->
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
      <h3><i>Staff Record Management</i></h3>
                
			  <p>
       			This section is used to complete staff biodata. Kindly supply correct information in the spaces provided and ensure that you cross-check all your entries before submission. <br/>
            			<br/>
                      
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <th><font color="red">Staff ID: </font>
                          <!--<input name="fileno" type="text" id="fileno" size="40" onblur="swapcontent('load_staff_details',this.value);swapcontent('load_pix',this.value);"/> -->
                          
                          <input name="fileno" type="text" id="fileno" size="40" <?php if(strtolower($r_vals)!='super admin' and strtolower($r_vals)!='administrator' and strtolower($r_vals)!='registry officer' and strtolower($r_vals)!='registry admin') echo "value='$login_id' readonly=''"; ?> onblur="if (this.value !='') swapcontent('load_staff_details',this.value);swapcontent('load_pix',this.value);"/>
						  <?php
						  if(strtolower($r_vals)!='super admin' and strtolower($r_vals)!='administrator' and strtolower($r_vals)!='registry officer' and strtolower($r_vals)!='registry admin')
							echo "<script>
								swapcontent('load_staff_details',$('#fileno').val());
								swapcontent('load_pix',$('#fileno').val());
							</script>";
							
						  /*echo "<script>document.getElementById('fileno').focus();</script>"; */
						  ?>
                          </th>
                        </tr>
                        <tr>
                          <th>
                               <div id="tabss"> <!-- Start main  tab div -->
                                      <!--<ul>
                                        <li><a href="#tabs-1"><b>Staff Biodata</b></a></li>
                                       <li><a href="#tabs-2"><b>Position Applying For</b></a></li>
                                        <li><a href="#tabs-3"><b>Children</b></a></li>
                                        <li><a href="#tabs-4"><b>Employment</b></a></li>
                                        <li><a href="#tabs-5"><b>Educational Records</b></a></li>
                                        <li><a href="#tabs-6"><b>Publications</b></a></li>
                                        <li><a href="#tabs-7"><b>Services</b></a></li>
                                        <li><a href="#tabs-8"><b>Research</b></a></li>
                                        <li><a href="#tabs-9"><b>Training Programme</b></a></li>
                                        <li><a href="#tabs-10"><b>Professional Membership</b></a></li>
                                        <li><a href="#tabs-11"><b>Professional Qualification</b></a></li>
                                        <li><a href="#tabs-12"><b>Honour/Recognition</b></a></li>
                                        <li><a href="#tabs-13"><b>Countries Visited</b></a></li>
                                        <li><a href="#tabs-14"><b>Referees</b></a></li>
                                        <li><a href="#tabs-15"><b>Uploads</b></a></li>
                                    </ul>-->
                                      <div class="easyui-tabs" data-options="tabWidth:200,tabHeight:40" style="width:700px;" id="tt">
                                      <!--div id="tabs-1"--> 
                                      
                                      
          <!-- tab 1-staff biodata starts --->
<div title="<span class='tt-inner'>Biodata</span>" style="padding:10px">
                                      <fieldset>
                                         <legend><b><font color="red">Staff Biodata</font></b></legend>
                                      <center>
                                         <table width="95%" border="0">                                           
                                            <tr><span id="load_pix"></span>
                                              <td width="15%">Title:</td>
                                              <td width="20%"><select name="title" id="title" class="txt">
                                                <option selected="selected">---</option>
                                                <option value="Mr.">Mr.</option>
                                                <option value="Miss">Miss</option>
                                                <option value="Mrs">Mrs</option>
                                                <option value="Dr.">Dr.</option>
                                                <option value="Engr.">Engr.</option>
                                                <option value="Prof.">Prof.</option>
                                                <option value="Dr.(Mrs.)">Dr.(Mrs.)</option>
                                              </select></td>
                                              <td width="16%">Surname:</td>
                                              <td width="49%"><input name="surname" type="text" id="surname" size="25"/></td>
                                            </tr>
                                            <tr>
                                              <td>First Name:</td>
                                              <td><input name="first_name" type="text" id="first_name" size="25" class="txt"/></td>
                                              <td>Othername:</td>
                                              <td><input name="other_name" type="text" id="other_name" size="25" class="txt"/></td>
                                            </tr>
                                            <tr>
                                              <td>Maiden Name:</td>
                                              <td><input name="maiden_name" type="text" id="maiden_name" size="25" class="txt"/></td>
                                              <td>Sex:</td>
                                              <td><select name="sex" id="sex" class="txt">
                                                <option selected="selected">---</option>
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                              </select></td>
                                            </tr>
                                            <tr>
                                              <td>Marital Status:</td>
                                              <td><select name="marital_status" id="marital_status" class="txt">
                                                <option selected="selected">---</option>
                                                <option value="Single">Single</option>
                                                <option value="Married">Married</option>
                                                <option value="Widow">Widow</option>
                                                <option value="Widower">Widower</option>
                                                <option value="Separated">Separated</option>
                                                <option value="Divorced">Divorced</option>
                                              </select></td>
                                              <td>Religion:</td>
                                              <td><select name="religion" id="religion" class="txt">
                                                <option selected="selected">---</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Christianity">Christianity</option>
                                              </select></td>
                                            </tr>
                                            <tr>
                                              <td> Category:</td>
                                              <td><select name="category" id="category" onchange="swapcontent('load_rank',this.value);">
                                                <option selected="selected" value="">---</option>
                                                <option value="Academic">Academic</option>
                                                <option value="Non-Academic">Non-Academic</option>
                                              </select></td>
                                              <td>Qualification:</td>
                                              <td><input name="qualification" type="text" id="qualification" size="25" /></td>
                                            </tr>
                                            <tr>
                                              <td>Date of Birth:</td>
                                              <td><input type="text" name="date_of_birth" id="date_of_birth"/></td>
                                              <td>Place of Birth:</td>
                                              <td><input name="place_of_birth" type="text" id="place_of_birth" size="25" /></td>
                                            </tr>
                                            <tr>
                                              <td>Nationality</td>
                                              <td><select name="nationality" id="nationality" onChange="swapcontent('natdiv',document.getElementById('nationality').value)">
                                                <option selected="selected" value="<?php if ($nationality=='') echo 'Nigerian'; else echo $nationality; ?>">
                                                <?php if ($nationality=='') echo 'Nigerian'; else echo $nationality; ?>
                                                </option>

                                                <option value="Nigerian">Nigerian</option>
                                                <option value="Non-Nigerian">Non-Nigerian</option>
                                                <?php
                                             
                                             ?>
                                              </select></td>
                                              <td>
                                                <p><span id="natdiv">State: 
  </span></p>
                                                <p><span> LGA: </span></p>
                                              
                                              </td>
                                              <td><p><span id="statediv">
                                               <select name="state" id="state" onChange="swapcontent('lgadiv',document.getElementById('state').value)">
                                                 <option selected="selected" value="<?php echo $state_id;?>"><?php echo $state_name;?></option>
                                                 <?php
                                                  $res_c=@mysqli_query($con, "select * from statetb order by state_name");
                                                  while($rs_c=@mysqli_fetch_array($res_c))
                                                   {
                                                      $state_id=@$rs_c['state_id'];
                                                      $state_name=@$rs_c['state_name'];
                                                      echo "<option value='$state_id'>$state_name</option>";
                                                   }
                                                 
                                                 ?>
                                              </select>
                                              </span></p>
                                                <p>
                                                  <select name="lga" id="lga">
                                                    <option selected="selected" value=''>---</option>
                                                    <?php
                        $res_c=@mysqli_query($con, "select * from lgatb order by lga_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $lga_id=@$rs_c['lga_id'];
							 $lga_name=@$rs_c['lga_name'];
							 echo "<option value='$lga_id'>$lga_name</option>";
                           }
						   ?>
                                                  </select>
                                                  <br />
                                                </p>
                                              </td>
                                            </tr>
                                            <tr>
                                              <td>Senatorial District</td>
                                              <td><input name="senatorial_district" type="text" id="senatorial_district" size="25" /></td>
                                              <td>Contact Address:</td>
                                              <td><textarea name="contact_address" id="contact_address" cols="19" rows="3" class="txt"></textarea></td>
                                            </tr>
                                            <tr>
                                              <td>Residential Address:</td>
                                              <td><textarea name="residential_address" id="residential_address" cols="19" rows="3" class="txt"></textarea></td>
                                              <td nowrap="nowrap">Permanent Address:</td>
                                              <td><textarea name="permanent_address" id="permanent_address" cols="19" rows="3" class="txt"></textarea></td>
                                            </tr>
                                            <tr>
                                              <td>Email:</td>
                                              <td><input name="email" type="text" id="email" size="25"/></td>
                                              <td>Phone Number:</td>
                                              <td><input name="phone_no" type="text" id="phone_no" size="25" /></td>
                                            </tr>
                                            <tr>
                                              <td>Bank Name:</td>
                                              <td><select name="bank_name" id="bank_name">
                                                <option selected="selected" value="">---</option>
                                                <?php
                          $res_c=@mysqli_query($con, "select * from banktb order by bankname");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $bankname=@$rs_c['bankname'];
                              echo "<option value='$bankname'>$bankname</option>";
                           }
                          echo "</select>";
						 ?>
                                              </select></td>
                                              <td nowrap="nowrap">Account Number:</td>
                                              <td><input name="acct_no" type="text" id="acct_no" size="25" /></td>
                                            </tr>
                                            <tr>
                                              <td nowrap="nowrap">Place of Residence:</td>
                                              <td><input name="last_place_of_residence" type="text" id="last_place_of_residence" size="25" /></td>
                                              <td>Languages Spoken:</td>
                                              <td><input name="languages_spoken" type="text" id="languages_spoken" size="25" /></td>
                                            </tr>
                                            <tr>
                                              <td nowrap="nowrap">Passport Number:</td>
                                              <td><input name="passport_number" type="text" id="passport_number" size="25" /></td>
                                              <td>Place of Issuance:</td>
                                              <td><input name="passport_place" type="text" id="passport_place" size="25" /></td>
                                            </tr>
                                            <tr>
                                              <td nowrap="nowrap">Passport Date Issued:</td>
                                              <td><input name="passport_date_issue" type="text" id="passport_date_issue" size="25"/></td>
                                              <td>Hobbies:</td>
                                              <td><input name="hobbies" type="text" id="hobbies" size="25" /></td>
                                            </tr>
                                            <tr>
                                              <td>Disability:</td>
                                              <td><select name="disability" id="disability" class="txt">
                                                <option selected="selected">---</option>
                                                <option value="No">No</option>
                                                <option value="Yes">Yes</option>
                                              </select></td>
                                              <td nowrap="nowrap">Nature of Disability:</td>
                                              <td><input name="disability_reason" type="text" id="disability_reason" size="25" /></td>
                                            </tr>
                                            <tr>
                                              <td>Have you ever been fined or imprisoned by a court of law/Tribunal? If yes, give details</td>
                                              <td colspan="3"><textarea name="court_case" id="court_case" cols="53" rows="3" class="txt"></textarea></td>
                                            </tr>
                                            </table>
</center>
</fieldset>
<!-- end biodata fieldset --></div>
<div title="<span class='tt-inner'>Spouse</span>" style="padding:10px">
                                              <fieldset>
                                                <legend><b><font color="red">Spouse Details</font></b>
                                              </legend>
                                                      <center><table align="center">
                                               <tr>
                                                 <td>Fullname:</td>
                                                 <td><input name="spouse_name" type="text" id="spouse_name" size="30"/></td>
                                                 <td>Occupation:</td>
                                                 <td><input name="spouse_occupation" type="text" id="spouse_occupation" size="30"/></td>
                                               </tr>
                                               <tr>
                                                 <td nowrap="nowrap">Address:</td>
                                                 <td colspan="3"><input name="spouse_address" type="text" id="spouse_address" size="70"/></td>
                                                 </tr>
                                              </table></center>
                                            </fieldset>
                                              </div>
<!--<div title="<span class='tt-inner'>Guardian</span>" style="padding:10px">
                                              <fieldset>
                                                <legend><b><font color="red">Guardian Details</font></b>
                                          </legend>
                                                  <center><table align="center">
                                           <tr>
                                             <td>Fullname</td>
                                             <td><input name="guidance_name" type="text" id="guidance_name" size="30"/></td>
                                             <td>Nationality</td>
                                             <td><input name="guidance_nationality" type="text" id="guidance_nationality" size="25"/></td>
                                           </tr>
                                           <tr>
                                             <td>State</td>
                                             <td><input name="guidance_state" type="text" id="guidance_state" size="30"/></td>
                                             <td>Occupation</td>
                                             <td><input name="guidance_occupation" type="text" id="guidance_occupation" size="25"/></td>
                                           </tr>
                                           <tr>
                                             <td>Address</td>
                                             <td><input name="guidance_address" type="text" id="guidance_address" size="30"/></td>
                                             <td>Email</td>
                                             <td><input name="guidance_email" type="text" id="guidance_email" size="30"/></td>
                                           </tr>
                                           <tr>
                                             <td colspan="4">Phone Number 
                                             <input name="guidance_phone_no" type="text" id="guidance_phone_no" size="60"/></td>
                                             </tr>
                                                </table></center>
                                        </fieldset> 
                                              </div>--><!--end of guardian fieldset -->
<div title="<span class='tt-inner'>Next of Kin</span>" style="padding:10px">                                              
                                              <fieldset>
                                                <legend><b><font color="red">Next of Kin's Details</font></b>
                                          </legend>
                                                  <center><table align="center">
                                           <tr>
                                             <td>Fullname:</td>
                                             <td><input name="next_name" type="text" id="next_name" size="30"/></td>
                                             <td>Relationship:</td>
                                             <td><input name="next_relationship" type="text" id="next_relationship" size="30"/></td>
                                           </tr>
                                           <tr>
                                             <td>Email:</td>
                                             <td><input name="next_email" type="text" id="next_email" size="30"/></td>
                                             <td nowrap="nowrap">Phone Number:</td>
                                             <td><input name="next_phone_no" type="text" id="next_phone_no" size="30"/></td>
                                           </tr>
                                           <tr>
                                             <td>Address:</td>
                                             <td colspan="3"><input name="next_address" type="text" id="next_address" size="70"/></td>
                                             </tr>
                                                </table></center>
                                        </fieldset> 
                                              <!--end of kin fieldset --></div>
<!--<div title="<span class='tt-inner'>New Mail</span>" style="padding:10px">                                            
                                            <fieldset>
                                                <legend><b><font color="red">Mother's Details</font></b>
                                                  </legend>                                        
                                                          <center><table align="center">
                                                   <tr>
                                                     <td>Fullname</td>
                                                     <td><input name="mother_name" type="text" id="mother_name" size="30"/></td>
                                                     <td>Nationality</td>
                                                     <td><input name="mother_nationality" type="text" id="mother_nationality" size="25"/></td>
                                                   </tr>
                                                   <tr>
                                                     <td>State</td>
                                                     <td colspan="3"><input name="mother_state" type="text" id="mother_state" size="60"/></td>
                                                     </tr>
                                                   <tr>
                                                     <td colspan="4">Address 
                                                       <input name="mother_address" type="text" id="mother_address" size="60"/></td>
                                                     </tr>
                                                        </table></center>
                                            </fieldset>
                                            </div>-->
<!--<div title="<span class='tt-inner'>New Mail</span>" style="padding:10px">                                             
                                                <fieldset>
                                                <legend><b><font color="red">Fill this section if you have served with armed or other security forces</font></b>
                                                  </legend>
                                            
                                           <center> <table>
                                            <tr>
                                              <td colspan="4">
                                                          <center><table align="center">
                                                   <tr>
                                                     <td>Force Number</td>
                                                     <td><input name="force_no" type="text" id="force_no" size="30"/></td>
                                                     <td>Highest Rank</td>
                                                     <td><input name="highest_force_rank" type="text" id="highest_force_rank" size="25"/></td>
                                                   </tr>
                                                   <tr>
                                                     <td>Period</td>
                                                     <td><input name="force_period" type="text" id="force_period" size="30"/></td>
                                                     <td>Character entered in discharge Book</td>
                                                     <td><input name="force_character" type="text" id="force_character" size="25"/></td>
                                                   </tr>
                                                   </table></center>
                                                
                                              </td>
                                            </tr>
                                            <tr>
                                              <td colspan="4"><div align="center">Other Information (If any): 
                                              <input name="other_info" type="text" id="other_info" size="60"/>
                                              </div></td>
                                            </tr>
                                            <tr>
                                              <td colspan="4"><div align="center">
                                                <input type="button" name="button" id="button" value="Save/Update Record" class="btn" onclick="swapcontent('update_biodata');"/>
                                                 <?php if(strtolower($r_vals)!='self_service' and strtolower($r_vals)!='applicant') { ?> <input type="button" name="cmdrefresh" id="cmdrefresh" value="Delete Staff Record" class="btn" onclick="if(confirm('Are you sure you want to delete this record?')==true) swapcontent('delete_biodata');"/> <?php } ?>
                                                  
                                                <input type="reset" name="button3" id="button3" value="Refresh" class="btn"/>
                                              </div></td>
                                            </tr>
                                        </table> </center>
                                        </fieldset> 
    <div id="update_biodata"></div><div id="delete_biodata"></div>
</div>--> <!-- tab 1 end --->


<!--/div-->
                                      
                                      <!-- tab 2 position applied for starts -->
                                     <!--<div id="tabs-2"> 
                                       <font color="red"><b>Use this form to select the Position(s) you are applying for. Cross-check your entry before submission.</b></font>
                                       <center><table width="70%" border="0">
                                          <tr>
                                            <td width="18%">Position/Post applied for:</td>
                                            <td colspan="3"><select name="app_position" id="app_position">
                                              <option selected="selected" value="">---</option>
                                              <?php
                          $res_c=@mysqli_query($con, "select * from hr_positiontb order by category,position");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
							  $position=@$rs_c['position'];
                              echo "<option value='$position'>$position</option>";
                           }
                          echo "</select>";
						 ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td>Department</td>
                                            <td width="11%"><select name="app_dept" id="app_dept" onchange="swapcontent('load_unit_app',document.getElementById('app_dept').value)">
                                              <option selected="selected" value="">---</option>
                                              <?php
                          $res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name</option>";
                           }
                          echo "</select>";
						 ?>
                                            </select></td>
                                            <td width="6%">Unit</td>
                                            <td width="65%">
                                              <span id="load_unit_app"><select name="app_unit" id="app_unit">
                                                <option selected="selected" value="">---</option>
                                                <?php
											  $res_c=@mysqli_query($con, "select * from unittb order by unit_name");
											  while($rs_c=@mysqli_fetch_array($res_c))
											   {
												  $unit_code=@$rs_c['unit_code'];
												  $unit_name=@$rs_c['unit_name'];
												  echo "<option value='$unit_code'>$unit_name</option>";
											   }
											  echo "</select>";
											 ?>
											  </select></span>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="button2" id="button2" value="Submit" class="btn" onClick="swapcontent('add_position','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_position">
                                            
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       
</div>--><!-- tab 2 ends --->
                                    <div title="<span class='tt-inner'>Child(ren) Info.</span>" style="padding:10px">
                                    <div id="tabs-3"> <!-- tab 3 Children --->

                                       <fieldset>
                                         <legend><b><font color="red">Use this form to enter the Children Information. Cross-check your entry before submission.</font></b>
                                                  </legend>
                                    <center><table width="70%" border="0">
                                          <tr>
                                            <td width="27%" align="left">Child's Fullname:</td>
                                            <td width="73%" align="left"><input name="child_name" type="text" id="child_name" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td align="left">Date of Birth:</td>
                                            <td align="left"><input name="child_dob" type="text" id="child_dob" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td align="left">Sex:</td>
                                            <td align="left"><select name="child_sex" id="child_sex" class="txt">
                                              <option selected="selected">---</option>
                                              <option value="Male">Male</option>
                                              <option value="Female">Female</option>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2"><div align="center">
                                              <input type="button" name="button2" id="button2" value="Submit" class="btn" onClick="swapcontent('add_child','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2">
                                              <div id="add_child">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       </fieldset>
                                    </div><!-- tab 3 ends ---></div>
           <div title="<span class='tt-inner'>Employment</span>" style="padding:10px">                        
                                   <div id="tabs-4"> <!-- tab 3 Employment --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide all previous/present employment. Cross-check your entry before submission.</b></font></legend>
                                    <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Employer's Name:</td>
                                            <td width="28%"><input name="emp_name" type="text" id="emp_name" size="30"/></td>
                                            <td width="13%">Location:</td>
                                            <td width="39%"><input name="emp_location" type="text" id="emp_location" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Rank:</td>
                                            <td><input name="emp_rank" type="text" id="emp_rank" size="30"/></td>
                                            <td>Salary:</td>
                                            <td><input name="emp_salary" type="text" id="emp_salary" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>From:</td>
                                            <td><select name="emp_year_from" id="emp_year_from">
                                              <option selected="selected" value="">---</option>
                                              <?php
											  for($i=1900;$i<=date('Y'); $i++)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                            <td>To:</td>
                                            <td><select name="emp_year_to" id="emp_year_to">
                                              <option selected="selected" value="">---</option>
                                              <option value="To Date">To Date</option>
                                              <?php
											  for($i=1900;$i<=date('Y'); $i++)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td nowrap="nowrap">Employment Type:</td>
                                            <td><select name="emp_type" id="emp_type" class="txt">
                                              <option selected="selected" value="">---</option>
                                              <option value="Previous">Previous</option>
                                              <option value="Present">Present</option>
                                            </select></td>
                                            <td nowrap="nowrap">Nature of Employment:</td>
                                            <td><select name="emp_status" id="emp_status" class="txt">
                                              <option selected="selected" value="">---</option>
                                              <option value="Permanent">Permanent</option>
                                              <option value="Temporary">Temporary</option>
                                              <option value="Contract">Contract</option>
                                              <option value="Transfer">Transfer</option>
                                              <option value="Secondment">Secondment</option>
                                              <option value="Pensionable">Pensionable</option>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td valign="top">Duty:</td>
                                            <td><textarea name="emp_duty" id="emp_duty" cols="20" rows="3" class="txt"></textarea></td>
                                            <td valign="top">Reason for Leaving the Organization:</td>
                                            <td valign="top"><textarea name="emp_leaving" id="emp_leaving" cols="20" rows="3" class="txt"></textarea></td>
                                          </tr>
                                          <tr>
                                            <td>Are you Bonded?</td>
                                            <td colspan="3"><select name="emp_bond" id="emp_bond">
                                              <option selected="selected" value="">---</option>
                                              <option value="No">No</option>
                                              <option value="Yes">Yes</option>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_employment','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_employment">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                      </fieldset> 
                                 </div><!-- tab 4 ends ---></div>
          <div title="<span class='tt-inner'>Education</span>" style="padding:10px">                            
                                   <div id="tabs-5"> <!-- tab 5 Educational Records --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide educational records history. Cross-check your entry before submission.</b></font></legend>
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%" nowrap="nowrap">Name of Institution:</td>
                                            <td width="28%"><input name="edu_name" type="text" id="edu_name" size="30"/></td>
                                            <td nowrap="nowrap">Institution Type:</td>
                                            <td><select name="edu_type" id="edu_type" class="txt">
                                              <option selected="selected" value="">---</option>
                                              <option value="Primary">Primary</option>
                                              <option value="Secondary">Secondary</option>
                                              <option value="Advanced Level">Advanced Level</option>
                                              <option value="Diploma">Diploma</option>
                                              <option value="College">College</option>
                                              <option value="Polytechnic">Polytechnic</option>
                                              <option value="University">University</option>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td>Qualification Obtained:</td>
                                            <td><input name="edu_qual" type="text" id="edu_qual" size="30"/></td>
                                            <td>Grade/Class of Degree</td>
                                            <td><input name="edu_grade" type="text" id="edu_grade" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>From:</td>
                                            <td><select name="edu_month_from" id="edu_month_from">
                                              <option selected="selected" value="">---</option>
                                              <?php
                          						$res_m=@mysqli_query($con, "select * from monthtb order by convert(month_code,decimal)");
												while($rs_m=@mysqli_fetch_array($res_m))
												 {
													 echo "<option value='{$rs_m['month_code']}'>{$rs_m['month_name']}</option>";
												 }
						 					?>
                                            </select> 
                                              <select name="edu_year_from" id="edu_year_from">
                                              <option selected="selected" value="">---</option>
                                              <?php
                          for($i=1900;$i<=date('Y'); $i++)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
						 ?>
                                            </select></td>
                                            <td>To:</td>
                                            <td><select name="edu_month_to" id="edu_month_to">
                                              <option selected="selected" value="">---</option>
                                              <?php
                          						$res_m=@mysqli_query($con, "select * from monthtb order by convert(month_code,decimal)");
												while($rs_m=@mysqli_fetch_array($res_m))
												 {
													 echo "<option value='{$rs_m['month_code']}'>{$rs_m['month_name']}</option>";
												 }
						 					?>
                                            </select> 
                                              <select name="edu_year_to" id="edu_year_to">
                                              <option selected="selected" value="">---</option>
                                              <!--<option value="0">To Date</option>-->
                                              <?php
											  for($i=1900;$i<=date('Y'); $i++)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_education','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_education">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       </fieldset>
                                 </div><!-- tab 5 ends ---></div>
          <div title="<span class='tt-inner'>Publication(s)</span>" style="padding:10px">                          
                                    
                                    <div id="tabs-6"> <!-- tab 6 Publication --->
                                      <fieldset>
                                         <legend> <font color="red"><b>Use this form to provide publication(s) if any. Note that this section is meant for academic staff only. Cross-check your entry before submission.</b></font></legend>
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%" nowrap="nowrap">Title of Publication:</td>
                                            <td width="28%"><input name="pub_title" type="text" id="pub_title" size="30"/></td>
                                            <td>Publisher:</td>
                                            <td><input name="pub_publisher" type="text" id="pub_publisher" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Author(s):</td>
                                            <td><input name="pub_author" type="text" id="pub_author" size="30"/></td>
                                            <td nowrap="nowrap">Publication Type:</td>
                                            <td><select name="pub_type" id="pub_type" class="txt">
                                              <option selected="selected" value="">---</option>
                                              <option value="Chapter in a Book">Chapter in a Book</option>
                                              <option value="Book">Book</option>
                                              <option value="Journal">Journal</option>
                                              <option value="Conference">Conference</option>
                                              <option value="Edited Conference Proceedings">Edited Conference Proceedings</option>
                                              <option value="Monograph">Monograph</option>
                                              <option value="Technical Report">Technical Report</option>
                                              <option value="Commissioned Work">Commissioned Work</option>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td>Name of Journal:</td>
                                            <td><input name="pub_journal" type="text" id="pub_journal" size="30"/></td>
                                            <td>Publication Year:</td>
                                            <td><select name="pub_year" id="pub_year">
                                              <option selected="selected" value="">---</option>
                                              <!--<option value="0">To Date</option>-->
                                              <?php
											  for($i=date('Y');$i>=1900; $i--)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td nowrap="nowrap">Publication Status:</td>
                                            <td><select name="pub_status" id="pub_status" class="txt">
                                              <option selected="selected" value="">---</option>
                                              <option value="In-Print">In-Print</option>
                                              <option value="Accepted">Accepted</option>
                                              <option value="Published">Published</option>
                                            </select></td>
                                            <td>Category:</td>
                                            <td><select name="pub_category" id="pub_category" class="txt">
                                              <option selected="selected" value="">---</option>
                                              <option value="Home Based">Home Based</option>
                                              <option value="National">National</option>
                                              <option value="International">International</option>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td>Page Number/Range:</td>
                                            <td><input name="pub_page_no" type="text" id="pub_page_no" size="30"/></td>
                                            <td>URL:</td>
                                            <td><input name="pub_url" type="text" id="pub_url" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Volume:</td>
                                            <td><input name="pub_volume" type="text" id="pub_volume" size="30"/></td>
                                            <td>Issue:</td>
                                            <td><input name="pub_issue" type="text" id="pub_issue" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_publication','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_publication">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                      </table></center>
                                       </fieldset>
                                 </div><!-- tab 6 ends ---></div>
                                    
<div title="<span class='tt-inner'>Community Service</span>" style="padding:10px">                                                             
                                   <div id="tabs-7"> <!-- tab 7 Services --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide administrative/community development services. Cross-check your entry before submission.</b></font></legend>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Type of Service:</td>
                                            <td width="28%"><select name="serv_type" id="serv_type" class="txt">
                                              <option selected="selected" value="">---</option>
                                              <option value="Administrative">Administrative</option>
                                              <option value="Community">Community Development</option>
                                            </select></td>
                                            <td>Place of Service:</td>
                                            <td><input name="serv_place" type="text" id="serv_place" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Service Details:</td>
                                            <td colspan="3"><textarea name="serv_detail" id="serv_detail" cols="60" rows="3" class="txt"></textarea></td>
                                          </tr>
                                          <tr>
                                            <td>From:</td>
                                            <td><select name="serv_from" id="serv_from">
                                              <option selected="selected" value="">---</option>
                                              <?php
											  for($i=1900;$i<=date('Y'); $i++)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                            <td>To:</td>
                                            <td><select name="serv_to" id="serv_to">
                                              <option selected="selected" value="">---</option>
                                              <option value="To Date">To Date</option>
                                              <?php
											  for($i=1900;$i<=date('Y'); $i++)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_service','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_service">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       </fieldset>
                                 </div><!-- tab 7 ends ---></div>
          <div title="<span class='tt-inner'>Research</span>" style="padding:10px">                          
                                  <div id="tabs-8"> <!-- tab 8 research --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide research history. Note that this section is meant for academic staff only. Cross-check your entry before submission.</b></font></legend>
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Research Topic:</td>
                                            <td width="28%"><input name="res_topic" type="text" id="res_topic" size="30"/></td>
                                            <td width="12%" nowrap="nowrap">Research Status:</td>
                                            <td width="40%"><select name="res_status" id="res_status" class="txt">
                                              <option selected="selected" value="">---</option>
                                              <option value="In Progress">In Progress</option>
                                              <option value="Completed">Completed</option>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td>Funding Source:</td>
                                            <td colspan="3"><input name="res_funding" type="text" id="res_funding" size="80"/></td>
                                          </tr>
                                          <tr>
                                            <td nowrap="nowrap">Amount Granted:</td>
                                            <td><input name="res_amount" type="text" id="res_amount" size="30"/></td>
                                            <td>Value of Project:</td>
                                            <td><input name="res_value" type="text" id="res_value" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Start Date:</td>
                                            <td><input name="res_start_date" type="text" id="res_start_date" size="30"/></td>
                                            <td nowrap="nowrap">End Date:</td>
                                            <td><input name="res_end_date" type="text" id="res_end_date" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_research','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_research">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       </fieldset>
                                 </div><!-- tab 8 ends ---></div>
          <div title="<span class='tt-inner'>Conference/Seminar</span>" style="padding:10px">                          
                                    <div id="tabs-9"> <!-- tab 9 trainig --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide training/seminar/workshop/conferences attended. Cross-check your entry before submission.</b></font></legend>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Type of Training:</td>
                                            <td width="28%"><select name="tra_type" id="tra_type" class="txt" onchange="swapcontent('load_no_paper_read',this.value);">
                                              <option selected="selected" value="">---</option>
                                              <option value="Conference">Conference</option>
                                              <option value="Seminar">Seminar</option>
                                              <option value="Workshop">Workshop</option>
                                            </select><br/>
                                            <span id="load_no_paper_read"></span>
                                            </td>
                                            <td width="12%">Training Title/Theme:</td>
                                            <td width="40%"><input name="tra_title" type="text" id="tra_title" size="40"/></td>
                                          </tr>
                                          <tr>
                                            <td>Location:</td>
                                            <td><input name="tra_location" type="text" id="tra_location" size="30"/></td>
                                            <td>Venue:</td>
                                            <td><input name="tra_venue" type="text" id="tra_venue" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Sponsor:</td>
                                            <td><input name="tra_sponsor" type="text" id="tra_sponsor" size="30"/></td>
                                            <td>Start Date:</td>
                                            <td><input name="tra_start_date" type="text" id="tra_start_date" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td colspan="2">&nbsp;</td>
                                            <td>End Date:</td>
                                            <td><input name="tra_end_date" type="text" id="tra_end_date" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_training','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_training">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                        </fieldset>
                                    </div><!-- tab 9 ends ---></div>
<div title="<span class='tt-inner'>Prof. Membership</span>" style="padding:10px">                                    
                                    <div id="tabs-10"> <!-- tab 10 professional membership --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide Professional Membership. Cross-check your entry before submission.</b></font></legend>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Organization/Body:</td>
                                            <td width="28%"><input name="prof_mem_name" type="text" id="prof_mem_name" size="30"/>
                                              <br/>
                                            <span id="load_no_paper_read"></span>
                                            </td>
                                            <td width="12%">Membership Category:</td>
                                            <td width="40%"><input name="prof_mem_category" type="text" id="prof_mem_category" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Reg. Number:</td>
                                            <td><input name="prof_mem_regno" type="text" id="prof_mem_regno" size="30"/></td>
                                            <td>Year Honoured:</td>
                                            <td><select name="prof_mem_year" id="prof_mem_year">
                                              <option selected="selected" value="">---</option>
                                              <!--<option value="0">To Date</option>-->
                                              <?php
											  for($i=1900;$i<=date('Y'); $i++)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td>Certificate No.:</td>
                                            <td><input name="prof_mem_certno" type="text" id="prof_mem_certno" size="30"/></td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_prof_membership','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_prof_membership">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       </fieldset>
                                    </div><!-- tab 10 ends ---></div>
<div title="<span class='tt-inner'>Prof. Qualification</span>" style="padding:10px">                                    
                                    <div id="tabs-11"> <!-- tab 11 Professional qualification --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide Professional Qualification. Cross-check your entry before submission.</b></font></legend>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Qualification Name</td>
                                            <td width="28%"><input name="prof_qual_name" type="text" id="prof_qual_name" size="30"/>
                                              <br/>
                                            <span id="load_no_paper_read"></span>
                                            </td>
                                            <td width="12%" nowrap="nowrap">Grade Obtained</td>
                                            <td width="40%"><input name="prof_qual_grade" type="text" id="prof_qual_grade" size="25"/></td>
                                          </tr>
                                          <tr>
                                            <td>From</td>
                                            <td><select name="prof_qual_from" id="prof_qual_from">
                                              <option selected="selected" value="">---</option>
                                              <?php
											  for($i=1900;$i<=date('Y'); $i++)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                            <td>To</td>
                                            <td><select name="prof_qual_to" id="prof_qual_to">
                                              <option selected="selected" value="">---</option>
                                              <!--<option value="0">To Date</option>-->
                                              <?php
											  for($i=1900;$i<=date('Y'); $i++)
											   {
												  echo "<option value='$i'>$i</option>";
											   }
											  
											 ?>
                                            </select></td>
                                          </tr>
                                          <tr>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td colspan="2">&nbsp;</td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_prof_qual','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_prof_qual">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       </fieldset>
                                    </div><!-- tab 11 ends ---></div>
<div title="<span class='tt-inner'>Award/Honour</span>" style="padding:10px">                                    
                                    <div id="tabs-12"> <!-- tab 12 Honour/Recognition --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide Honour/Recognition received. Cross-check your entry before submission.</b></font></legend>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Type of Honour/Award:</td>
                                            <td width="28%"><input name="honour_type" type="text" id="honour_type" size="30"/>
                                              <br/>
                                            <span id="load_no_paper_read"></span>
                                            </td>
                                            <td width="12%">Date:</td>
                                            <td width="40%"><input name="honour_date" type="text" id="honour_date" size="30" cols="25"/></td>
                                          </tr>
                                          <tr>
                                            <td>Description:</td>
                                            <td><textarea name="honour_desc" id="honour_desc" cols="25" rows="3" class="txt"></textarea></td>
                                            <td nowrap="nowrap">Prize (if any):</td>
                                            <td><textarea name="honour_prize" id="honour_prize" cols="25" rows="3" class="txt"></textarea></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_honour','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_honour">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       </fieldset>
                                    </div><!-- tab 12 ends ---></div>
               <!-- tab 13 Countries Visited --->
               <!--<div title="<span class='tt-inner'>New Mail</span>" style="padding:10px">                     
                                    <div id="tabs-13"> 
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide Countries Visited. Cross-check your entry before submission.</b></font></legend>
                                       
                                       <center>   <table width="50%" border="0">
                                          <tr>
                                            <td width="20%">Name of Country</td>
                                            <td width="28%"><select name="country_name" id="country_name">
                                              <option selected="selected" value="">---</option>
                                              <?php
                          $res_c=@mysqli_query($con, "select * from countrytb order by country");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
							  $country=@$rs_c['country'];
                              echo "<option value='$country'>$country</option>";
                           }
                          echo "</select>";
						 ?>
                                            </select>
                                              <br/>
                                            <span id="load_no_paper_read"></span>
                                            </td>
                                          </tr>
                                          <tr>
                                            <td>Reason for visiting</td>
                                            <td><textarea name="country_reason" id="country_reason" cols="40" rows="3" class="txt"></textarea></td>
                                            
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_country','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_country">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                      </table></center>
                                       </fieldset>
                                    </div></div>--><!-- tab 13 ends --->
    <div title="<span class='tt-inner'>Refrees</span>" style="padding:10px">                     
                                    <div id="tabs-14"> <!-- tab 14 Referees --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to provide Refrees details. Cross-check your entry before submission.</b></font></legend>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Name:</td>
                                            <td width="28%"><input name="ref_name" type="text" id="ref_name" size="30"/>
                                              <br/>
                                            <span id="load_no_paper_read"></span>
                                            </td>
                                            <td width="12%">Occupation:</td>
                                            <td width="40%"><input name="ref_occupation" type="text" id="ref_occupation" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Address:</td>
                                            <td><textarea name="ref_address" id="ref_address" cols="25" rows="3" class="txt"></textarea></td>
                                            <td>&nbsp;</td>
                                            <td>How many years have you known the Referee?                                              <br />                                              <input name="ref_year" type="text" id="ref_year" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Email:</td>
                                            <td><input name="ref_email" type="text" id="ref_email" size="30"/></td>
                                            <td>Phone No</td>
                                            <td><input name="ref_phone_no" type="text" id="ref_phone_no" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Submit" class="btn" onClick="swapcontent('add_referee','save');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="add_referee">
                                            <?php
											  
                                              
                                             ?>
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                       </fieldset>
                                    </div><!-- tab 14 ends ---></div>
              <div title="<span class='tt-inner'>Uploads</span>" style="padding:10px">                      
                                    <div id="tabs-15"> <!-- tab 15 Uploads --->
                                       <fieldset>
                                         <legend><font color="red"><b>Use this form to upload Passport, Signature and other neccessary documents.</b></font>                                      </legend>
                                         <center>
                                      <!--<form id="imageForm" name="imageForm" enctype="multipart/form-data" action="pixupload.php" method="post" target="uploadedImage">-->
                                    <!-- Next field limits the maximum size of the selected file to 2MB.
                                       If exceded the size, an error will be sent with the file. -->
                                    <table>
                                     <tr>
                                       <td><b>Document Type:</b></td><td><select name="doc_type" id="doc_type">
                                       <option selected="selected" value="">---</option>
                                       <option value="Passport">Passport</option>
                                       <option value="Signature">Signature</option>
                                       <option value="OLevel_1st_Sitting">O'Level 1st Sitting</option>
                                       <option value="OLevel_2nd_Sitting">O'Level 2nd Sitting (If any)</option>
                                       <option value="OND">OND</option>
                                       <option value="HND">HND</option>
                                       <option value="First Degree">First Degree</option>
                                       <option value="Second Degree">Second Degree</option>
                                       <option value="PhD Certificate">PhD Certificate</option>
                                       <option value="NYSC">NYSC</option>
                                       <option value="Citizenship">Citizenship</option>
                                       <option value="NCE">NCE</option>
                                       <option value="Confidential Secretary">Confidential Secretary</option>
                                       <option value="Driving Certificate">Driving Certificate</option>
                                     </select></td></tr>
                                     <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
                                     <input name="oldImageToDelete" id="oldImageToDelete" type="hidden"
                                        size="20" />
                                        <input type="hidden" name="upload_appno" id="upload_appno" value=""/>
                                     <tr>
                                       <td><b>Select File:</b></td><td><input name="imageToUpload" id="imageToUpload" type="file"   onchange= " uploadImage();swapcontent('load_images');"  size="20" /></td></tr>
                                     <tr>
                                       <td colspan="2"><center><div id="document">
                                       <iframe id="uploadedImage" name="uploadedImage" src="" class="passport" width="300" height="200">
                                  
                                       </iframe>
                                       </div></center></td>
                                      </tr>
                                     <tr><td colspan="2"><br/><font color="red">NOTE: Document to be uploaded should not be more than 200KB. Only JPEG file (.jpg) format is allowed.</font><br/></td></tr>
                                    </table>
                                    
                                    <div id="display">
                                     
                                                                            
                                    </div>
                                 <!-- </form>-->
                                 </center>
                                      </fieldset>
                                    </div><!-- tab 15 ends --->
                          </div>
</div>
                             </div>
                            <!-- end main tab div -->
                          
                          </th>
                        </tr>
          </table>
            
            
            
            
              <p>
         
                        
           
            
              
              </p>
              
		<!--</div> end of content -->

		
		<!-- ############### Side bar ###############################-->
		
			<?php //include("sidebar_main.php");?>
		<!-- end of side bar -->
	</div><!-- end of container -->
</div><!--  end of page-wrapper   -->
<?php //include("footer_wrapper.php");?>
<?php include("footer.php");?>
<span id="roll"></span>
</form>                
                
                                
           
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