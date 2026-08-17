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
                                
<table width="641" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="10">&nbsp;</td>
    <td colspan="3"><div align="center"><strong><img src="<?php echo $val_str[1];?>" width="50" height="50"/></strong></div></td>
    <td width="11">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><div align="center"><strong><?php echo $val_str[0];?><br>
    </strong></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><div align="center"><strong><u>Curriculum Vitae</u></strong></div></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td width="361"><strong>Staff Number:
      <?php  echo $id; ?>
    </strong></td>
    <td width="76"><dd></dd> </td>
    <td width="183">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3" ><fieldset>
      <legend>Personal Details</legend>
      <br>
      <table width="642" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="38" height="30">i.</td>
          <td width="154" height="30">Fullname:</td>
          <td width="4" height="30">&nbsp;</td>
          <td width="271" height="30"><?php  echo strtoupper ($rs_staff['title']." ".$rs_staff['surname']); ?>,
          <?php  echo  ucfirst(strtolower($rs_staff['first_name'])); //ucfirst($fname); ?>
          <?php  echo  ucfirst(strtolower($rs_staff['other_name'])); //ucfirst($mname); ?></td>
          <td width="175" height="30" rowspan="12" valign="top"><img src="<?php  echo $path; ?>" alt=" " width="137" height="136" /> </td>
        </tr>
        <!--<tr>
          <td height="30">ii.</td>
          <td height="30">Post Desired and Department:</td>
          <td height="30">&nbsp;</td>
          <td height="30"><?php  echo $postdes;?>&nbsp;/
          <?php  echo $deptname;?></td>
        </tr>-->
        <tr>
          <td height="30">ii.</td>
          <td height="30"><span class="style12 style13">Date of Birth</span>:</td>
          <td height="30">&nbsp;</td>
          <td height="30"><?php 	  
		  echo @date('d/m/Y',strtotime($rs_staff['date_of_birth'])); ?></td>
        </tr>
        <tr>
          <td height="30">iii.</td>
          <td height="30"><span class="style12 style13">LGA  and State</span></td>
          <td height="30">&nbsp;</td>
          <td height="30"> <?php  echo get_lga($rs_staff['state_id'],$rs_staff['lga_id']); ?> 
            /          
          <?php  echo get_state($rs_staff['state_id']); ?></td>
        </tr>
        <tr>
          <td height="30">iv.</td>
          <td height="30">Sex</td>
          <td height="30">&nbsp;</td>
          <td height="30"><?php  echo $rs_staff['sex']; ?></td>
        </tr>
        <tr>
          <td height="30">v.</td>
          <td height="30"><span class="style12 style13">Nationality:</span></td>
          <td height="30">&nbsp;</td>
          <td height="30"><?php  echo $rs_staff['nationality']; ?></td>
        </tr>
        <tr>
          <td height="30">vi.</td>
          <td height="30"><span class="style12 style13">Permanent Home Address</span></td>
          <td height="30">&nbsp;</td>
          <td height="30"><?php  echo $rs_staff['permanent_address']; ?></td>
        </tr>
        <tr>
          <td height="30">vii.</td>
          <td height="30"><span class="style12 style13">Current Contact Address:</span></td>
          <td height="30">&nbsp;</td>
          <td height="30"><?php  echo $rs_staff['contact_address']; ?></td>
        </tr>
        <tr>
          <td height="30">viii.</td>
          <td height="30"><span class="style12 style13"> Phone</span>:</td>
          <td height="30">&nbsp;</td>
          <td height="30"><?php  echo $rs_staff['phone_no'] ; ?></td>
        </tr>
        <tr>
          <td height="30">ix.</td>
          <td height="30"><span class="style12 style13">Email Address</span>:</td>
          <td height="30">&nbsp;</td>
          <td height="30"><?php  echo $rs_staff['email']; ?></td>
        </tr>
        <tr>
          <td height="30">x.</td>
          <td height="30"><span class="style12 style13">Marital Status:</span></td>
          <td height="30">&nbsp;</td>
          <td height="30" colspan="2"><?php  echo $rs_staff['marital_status']; ?></td>
        </tr>
        <tr>
          <td height="30">xi.</td>
          <td height="30"><span class="style12 style13">Number of Children:</span></td>
          <td height="30">&nbsp;</td>
          <td height="30" colspan="2"><?php  echo get_child_no($fileno,'fileno','hr_staff_childtb') ; ?></td>
        </tr>
        <tr>
          <td colspan="5">
          <fieldset><legend>xii. Children Information</legend>
            <table width="637" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td>&nbsp;</td>
                <td><strong>Children Names</strong></td>
                <td width="199"><strong>Date of Birth</strong></td>
                <td width="215"><span class="style12 style13"><strong>Sex</strong></span></td>
              </tr>
<?php						
$res_rec_c=@mysqli_query($con, "select * from hr_staff_childtb where fileno='$id' order by name");

?>
				
						<?php 
						$num = 0;
					if(@mysqli_num_rows($res_rec_c)>=1) {
			    while($rs_rec_c=@mysqli_fetch_array($res_rec_c)) { 
?>

              <tr>
                <td width="31"><?php echo ++$num;?></td>
                <td width="192"><?php echo stripslashes($rs_rec_c['name']);?></td>
                <td><?php echo stripslashes(date('d/m/Y',strtotime($rs_rec_c['date_of_birth'])));?></td>
                <td><?php echo stripslashes($rs_rec_c['sex']);?></td>
              </tr>
            <?php }
			}
			?></table>
          </fieldset>          </td>
        </tr>
      </table>
    </fieldset>       </td>
    <td valign="top">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3" valign="top">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3" valign="top"><fieldset>
      <legend>xiii. Institutions Attended: Date starting with latest</legend>
        <table width="579" border="0" align="left" cellpadding="0" cellspacing="0">
          <tr>
            <td width="25">&nbsp;</td>
            <td width="408"><span class="style12 style13"><strong>Institution</strong></span></td>
            <td width="75"><span class="style12 style13"><strong>From</strong></span></td>
            <td width="71"><span class="style12 style13"><strong>To</strong></span></td>
          </tr>
					<?php						
					$res_rec_i=@mysqli_query($con, "select * from hr_staff_academic_edutb where fileno='$id' order by from_year desc");
					
					?>
									
											<?php  $num = 0;
										if(@mysqli_num_rows($res_rec_i)>=1) {
									while($rs_rec_i=@mysqli_fetch_array($res_rec_i)) {
					?>
          <tr>
            <td><?php echo ++$num;?></td>
            <td><?php echo stripslashes($rs_rec_i['school_name']);?></td>
            <td><?php echo stripslashes($rs_rec_i['from_year']);?></td>
            <td><?php echo stripslashes($rs_rec_i['to_year']);?></td>
          </tr>
		              <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xiv. Pure Academic Qualifications: Date starting with latest </legend>
      <table width="579" border="0" align="left" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td><strong>Qualification Title</strong></td>
          <td width="132"><span class="style12 style13"><strong>Year Obtained</strong></span></td>
        </tr>
        <?php						
					$res_rec_p=@mysqli_query($con, "select * from hr_staff_academic_edutb where fileno='$id' order by from_year desc");
					$num = 0;
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p)>=1) {
									while($rs_rec_p=@mysqli_fetch_array($res_rec_p)) {
					?>
        <tr>
          <td width="24"><?php echo ++$num; ?></td>
          <td width="423"><?php echo stripslashes($rs_rec_p['qualification']);?></td>
          <td><?php echo stripslashes($rs_rec_p['to_year']);?></td>
        </tr>
        <?php }
			}
			?>
      </table>
      </fieldset>       </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
        <legend >xv. Professional Qualification: Date starting with latest       </legend>
        <table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td>&nbsp;</td>
            <td width="138"><span class="style12 style13"><strong>Qualification Name</strong></span></td>
            <td width="107"><span class="style12 style13"><strong>Year Obtained</strong></span></td>
          </tr>
		  					<?php						
					$res_rec_p=@mysqli_query($con, "select * from hr_staff_prof_qualificationtb where fileno='$id' order by  to_year desc");
					$num = 0;
					?>
									
											<?php 
										if(@mysqli_num_rows($res_rec_p)>=1) {
									while($rs_rec_p=@mysqli_fetch_array($res_rec_p)) {
					?>

          <tr>
            <td width="23"><?php echo ++$num; ?></td>
            <td><?php echo stripslashes($rs_rec_p['name']);?></td>
            <td><?php echo stripslashes($rs_rec_p['to_year']);?></td>
          </tr>
		  		              <?php }
			}
			?>
      </table>
    </fieldset>       </td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xvi. Scholarships, Prizes, Honour, or Recognition: Date starting with latest</legend>
      <table width="636" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td>&nbsp;</td>
          <td><span class="style12 style13"><strong>Award Type</strong></span></td>
          <td width="286"><span class="style12 style13"><strong>Description</strong></span></td>
          <td width="228"><span class="style12 style13"><strong>Date Obtained</strong></span></td>
        </tr>
        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_recognitiontb where fileno='$id' order by award_date desc");
					
					?>
        <?php 
										//if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;
										/*echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Scholarship </strong> <br></td>
                                            </tr>"; */
											
										$num = 0;
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					?>
        <tr>
          <td width="22"><?php echo ++$num;?></td>
          <td width="100"><?php echo stripslashes($rs_rec_p1['award_type']);?></td>
          <td><?php echo stripslashes($rs_rec_p1['award_description']);?></td>
          <td><?php echo stripslashes(date('d/m/Y',strtotime($rs_rec_p1['award_date'])));?></td>
        </tr>
        <?php }
			//}
			?>
			        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_scholaship where applicationid='$id' and category = 'Prize' order by toyear  desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;
										echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Prize </strong> <br></td>
                                            </tr>";
										
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					?>
        <tr>
          <td width="22"><?php echo ++$num;?></td>
          <td width="100"><?php echo stripslashes($rs_rec_p1['name']);?></td>
          <td><?php echo stripslashes($rs_rec_p1['fromyear']);?></td>
          <td><?php echo stripslashes($rs_rec_p1['toyear']);?></td>
        </tr>
        <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xvii. Training/Workshop/Conference Programmes Attended: Date starting with latest</legend>
      <table width="641" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="26">&nbsp;</td>
          <td width="193"><strong>Training Type</strong></td>
          <td width="218"><span class="style12 style13"><strong>Training Title</strong></span></td>
          <td width="91"><span class="style12 style13"><strong>Location</strong></span></td>
          <td width="113"><span class="style12 style13"><strong>Start Date</strong></span></td>
          <td width="113"><span class="style12 style13"><strong>End Date </strong></span></td>
        </tr>
        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_training_apptb where fileno='$id' order by end_date desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num =0;
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					?>
        <tr>
          <td><?php echo ++$num;?></td>
          <td><?php echo stripslashes($rs_rec_p1['training_type']);?></td>
          <td><?php echo stripslashes($rs_rec_p1['training_title']);?></td>
          <td><?php echo stripslashes($rs_rec_p1['location']);?></td>
          <td><?php echo date("d/m/Y", strtotime($rs_rec_p1['start_date']));?></td>
          <td><?php echo date("d/m/Y", strtotime($rs_rec_p1['end_date']));?></td>
        </tr>
        <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xviii.  Membership of Learned Societies and Professional Bodies: Date starting with latest</legend>
      <table width="80%" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="22">&nbsp;</td>
          <td width="135"><strong>Membership Category</strong></td>
          <td width="188"><strong>Society/Professional Body</strong></td>
          <td width="111"><strong>Year Honoured</strong></td>
          </tr>
        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_prof_membershiptb where fileno='$id' order by year_honoured desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num =0;
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					?>
        <tr>
          <td><?php echo ++$num;?></td>
          <td><?php echo stripslashes($rs_rec_p1['category']);?></td>
          <td><?php echo stripslashes($rs_rec_p1['name']);?></td>
          <td><?php echo stripslashes($rs_rec_p1['year_honoured']);?></td>
          </tr>
        <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xix. Working Experience: Date starting with latest <br>
      </legend>
      <table width="636" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td width="24">&nbsp;</td>
          <td width="271"><strong>Organisation</strong></td>
          <td width="126"><span class="style12 style13"><strong>Rank</strong></span></td>
          <td width="121"><span class="style12 style13"><strong>Location </strong></span></td>
          <td width="58"><span class="style12 style13"><strong>From</strong></span></td>
          <td width="36"><span class="style12 style13"><strong>To</strong></span></td>
        </tr>
        <?php						
					$res_rec_w=@mysqli_query($con, "select * from hr_staff_employmenttb where fileno='$id' and employment_type!='Present' order by from_year desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_w)>=1) {$num = 0;
									while($rs_rec_w=@mysqli_fetch_array($res_rec_w)) {
					?>
        <tr>
          <td><?php  echo ++$num;?></td>
          <td><?php echo stripslashes($rs_rec_w['employer_name']);?></td>
          <td><?php echo stripslashes($rs_rec_w['rank']);?></td>
          <td><?php echo stripslashes($rs_rec_w['location']);?></td>
          <td><?php echo stripslashes($rs_rec_w['from_year']);?></td>
          <td><?php echo stripslashes($rs_rec_w['to_year']);?></td>
        </tr>
        <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xx. Research Interests or Commissioned Projects: Date starting with latest</legend>
      <table width="652" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
          <td colspan="2">&nbsp;</td>
          <td><strong>Title</strong></td>
          <td width="208"><strong><span class="style12 style13">Status</span></strong></td>
          <td width="208"><strong><span class="style12 style13">Start Date</span></strong></td>
          <td width="108"><strong><span class="style12 style13">End Date</span></strong></td>
        </tr>
        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_researchtb where fileno='$id' order by end_date desc" );
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					?>
        <tr>
          <td width="9">&nbsp;</td>
          <td width="21"><?php echo ++$num;?></td>
          <td width="306"><?php echo stripslashes($rs_rec_p1['topic']);?></td>
          <td><?php echo stripslashes($rs_rec_p1['status']);?></td>
          <td><?php echo stripslashes(date('d/m/Y',strtotime($rs_rec_p1['start_date'])));?></td>
          <td><?php echo stripslashes(date('d/m/Y',strtotime($rs_rec_p1['end_date'])));?></td>
        </tr>
        <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
    <legend >xxi. List of Publications</legend>
    <table width="629" border="0" align="center" cellpadding="0" cellspacing="0">
	<!--Home based publication -->

	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and status = 'In-Print' and type='Journal' " );
					
					?>
									
											<?php 
											$num=0;
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Journal Articles  In-Print </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo stripslashes($rs_rec_p1['title']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['journal'])."</i></strong>";?>&nbsp;<?php echo stripslashes($rs_rec_p1['volume']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['url']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php  }//end of while
			} //end of if
			?>
            
            <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and status = 'Published' and type='Journal' " );
					
					?>
									
											<?php 
											$num=0;
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Journal Articles</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo stripslashes($rs_rec_p1['title']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['journal'])."</i></strong>";?>&nbsp;<?php echo stripslashes($rs_rec_p1['volume']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['url']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php  }//end of while
			} //end of if
			?>
<!--Home based Accepted for publication -->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and  type='Journal' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Journal Articles  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['title']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['journal'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?>&nbsp;<!--Letter of Acceptance dated:-->
											  
											  <?php 	//$dob = stripslashes($rs_rec_p1['dob']);
											  		  //$date1 = explode("-",$dob);
													//	$newdate = $date1[2].'/'.$date1[1].'/'.$date1[0];
														//  echo $newdate; 
?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
<!--Home based In-Print Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and  type='Book' and status = 'In-Print' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Book  In-Print </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
            
            <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and  type='Book' and status = 'Published' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Book</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
<!--Home based Accepted for publication Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and type='Book' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Book  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->

<!--Home based In-Print Chapter in a Book-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and  type='Chapter in a Book' and status = 'In-Print' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Chapter in a Book  In-Print </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp; in &nbsp;<?php echo stripslashes($rs_rec_p1['journal']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
            
            <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and  type='Chapter in a Book' and status = 'Published' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Chapter in a Book</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp; in &nbsp;<?php echo stripslashes($rs_rec_p1['journal']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
<!--Home based Accepted for publication Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and  type='Chapter in a Book' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based  Chapter in a Book  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp; in &nbsp;<?php echo stripslashes($rs_rec_p1['journal']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?><!--Letter of Acceptance dated:--> 
											  <?php 											  			$dob = stripslashes($rs_rec_p1['dob']);
											  		  $date1 = explode("/",$dob);
														$newdate = $date1[2].'/'.$date1[1].'/'.$date1[0];
														  echo $newdate; 
?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
			<!-- Home Based Edited Conferences -->
				<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and  type='Edited Conference Proceedings'  " );
					
					?>
									
											<?php 
										    $num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based Edited Conference Proceedings   </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
//											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['authors']));
  											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));

											 // echo $rs_rec_p1['authors'];
											  $author_str1=""; 
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str1.="<b> $aValue</b>"; else $author_str1.=" $aValue";
//											   if(@eregi($sname,$aValue)) $author_str1.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php  echo $author_str1; //stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
        
			  		              <?php }
			}
			?>

			<!--Home Based Edited Conferences End-->
			<!--Home Based Technical Reports -->
	<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'Home Based'  and  type='Technical Report'  " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Home-Based Technical Report   </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
        
			  		              <?php }
			}
			?>
			
			<!--Home Based Technical Reports End-->

			
	<!--National publication --//////////////////==========================National= -->

	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and status in ('In-Print','Published') and type='Journal' " );
					
					?>
									
											<?php 
											$num=0;
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Journal Articles  <!--In-Print--> </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo stripslashes($rs_rec_p1['title']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['journal'])."</i></strong>";?>&nbsp;<?php echo stripslashes($rs_rec_p1['volume']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['url']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
<!--National Accepted for publication -->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Journal' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Journal Articles  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['title']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['journal'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?>&nbsp;<!--Letter of Acceptance dated:-->
											  
											  <?php 
											  		$dob = stripslashes($rs_rec_p1['dob']);
											  		  $date1 = explode("/",$dob);
														$newdate = $date1[2].'/'.$date1[1].'/'.$date1[0];
														  echo $newdate; 

											  ?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
<!--National In-Print Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Book' and status = 'In-Print' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Book  In-Print </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
            
            <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Book' and status = 'Published' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Book</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
<!--National Accepted for publication Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Book' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Book  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?><!--Letter of Acceptance dated:--> 
											  
											  <?php 	$dob = stripslashes($rs_rec_p1['dob']);
											  		  $date1 = explode("/",$dob);
														$newdate = $date1[2].'/'.$date1[1].'/'.$date1[0];
														  echo $newdate; 
?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->

<!--National In-Print Chapter in a Book-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Chapter in a Book' and status = 'In-Print' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Chapter in a Book  In-Print </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp; in &nbsp;<?php echo stripslashes($rs_rec_p1['journal']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
            
            <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Chapter in a Book' and status = 'Published' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Chapter in a Book</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp; in &nbsp;<?php echo stripslashes($rs_rec_p1['journal']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
<!--National Accepted for publication Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Chapter in a Book' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Chapter in a Book  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp; in &nbsp;<?php echo stripslashes($rs_rec_p1['journal']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?> <!--Letter of Acceptance dated:-->
											  
											  <?php 	$dob = stripslashes($rs_rec_p1['dob']);
											  		  $date1 = explode("/",$dob);
														$newdate = $date1[2].'/'.$date1[1].'/'.$date1[0];
														  echo $newdate; 
?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			
			<!-- -->
			<!--National Edited Conferences -->
				<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Edited Conference Proceedings'  " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Edited Conference Proceedings </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
        
			  		              <?php }
			}
			?>

						
			<!--National Edited Conferences End-->
			<!--National Technical Reports -->
				<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'National'  and  type='Technical Report'  " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>National  Technical Report </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
        
			  		              <?php }
			}
			?>

			<!--National Technical Reports -->

			<!--////////////////===========================End National
End of -->

	<!--International publication --//////////////////=========================== -->

	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and status = 'In-Print' and type='Journal' " );
					
					?>
									
											<?php 
											$num=0;
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Journal Articles  In-Print </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
													  <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo stripslashes($rs_rec_p1['title']);?>&nbsp;<?php echo ",<strong><i>".stripslashes($rs_rec_p1['journal'])."</i></strong>";?>&nbsp;<?php echo stripslashes($rs_rec_p1['volume']);?>(<?php echo stripslashes($rs_rec_p1['issue']);?>)&nbsp;<?php echo ",".stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo ", ".stripslashes($rs_rec_p1['publisher']);?>&nbsp;<?php echo ". ".stripslashes($rs_rec_p1['url']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
            
            <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and status = 'Published' and type='Journal' " );
					
					?>
									
											<?php 
											$num=0;
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International Journal Articles </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo stripslashes($rs_rec_p1['title']);?>&nbsp;<?php echo ",<strong><i>".stripslashes($rs_rec_p1['journal'])."</i></strong>";?>&nbsp;<?php echo ",".stripslashes($rs_rec_p1['volume']);?>(<?php echo stripslashes($rs_rec_p1['issue']);?>)&nbsp;<?php echo ",".stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?><?php echo ",".stripslashes($rs_rec_p1['publisher']);?><?php echo ".".stripslashes($rs_rec_p1['url']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php  }//end of while
			} //end of if
			?>
<!--International Accepted for publication -->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and  type='Journal' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Journal Articles  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['title']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['journal'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?>&nbsp;<!--Letter of Acceptance dated:-->
											  <?php 
											  			$dob = stripslashes($rs_rec_p1['dob']);
											  		  $date1 = explode("/",$dob);
														$newdate = $date1[2].'/'.$date1[1].'/'.$date1[0];
														  echo $newdate; 
											  ?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
<!--International In-Print Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and  type='Book' and status = 'In-Print' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Book  In-Print </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
            
            <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and  type='Book' and status = 'Published' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Book </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
            
			<!--End of -->
<!--International Accepted for publication Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and  type='Book' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Book  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?> <!--Letter of Acceptance dated:-->
											  
											  <?php 	$dob = stripslashes($rs_rec_p1['dob']);
											  		  $date1 = explode("/",$dob);
														$newdate = $date1[2].'/'.$date1[1].'/'.$date1[0];
														  echo $newdate; 
?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->

<!--International In-Print Chapter in a Book-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and  type='Chapter in a Book' and status = 'In-Print' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Chapter in a Book  In-Print </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp; in &nbsp;<?php echo stripslashes($rs_rec_p1['journal']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
        <tr>
        <td colspan="2">&nbsp;</td>
        </tr>
			  		              <?php }
			}
			?>
			<!--End of -->
<!--International Accepted for publication Books-->
	  		  					<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and  type='Chapter in a Book' and status = 'Accepted' " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Chapter in a Book  Accepted for Publication </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp; in &nbsp;<?php echo stripslashes($rs_rec_p1['journal']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?><!--Letter of Acceptance dated:--><?php 
											  
											  											  			$dob = stripslashes($rs_rec_p1['dob']);
											  		  $date1 = explode("/",$dob);
														$newdate = $date1[2].'/'.$date1[1].'/'.$date1[0];
														  echo $newdate; 
?></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
        
			  		              <?php }
			}
			?>

<!--Edited Conference International-->
	<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and  type='Edited Conference Proceedings'  " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Edited Conference Proceedings </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php //echo stripslashes($rs_rec_p1['editor']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
        
			  		              <?php }
			}
			?>

<!--Edited Conference International End-->
	<!--Technical Reports-International Begins-->


	<?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$id'  and category = 'International'  and  type='Technical Report'  " );
					
					?>
									
											<?php 
											$num=0;
										
										if(@mysqli_num_rows($res_rec_p1)>=1) { echo"                                            <tr>
                                              <td></td>
                                              <td><strong>International  Technical Report </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					++$num;	?>
	  											
                                            <tr>
                                              <td width="29"><?php  echo $num;?>&nbsp;</td>
                                              <?php 
											  //////////////////// get author's name for bolding
											  $authorArray=@explode(' ',stripslashes($rs_rec_p1['author']));
											  $author_str="";
											  foreach($authorArray as $aValue)
											   if(@eregi($sname,$aValue)) $author_str.="<b> $aValue</b>"; else $author_str.=" $aValue";
											   									   
											  ////////////// end of get author's name for bolding
											  ?>
                                              <td width="600"><?php echo $author_str;//stripslashes($rs_rec_p1['authors']);?>&nbsp;(<?php echo stripslashes($rs_rec_p1['year_published']);?>):&nbsp;<?php echo "<strong><i>".stripslashes($rs_rec_p1['title'])."</i></strong>";?>&nbsp;<?php echo stripslashes($rs_rec_p1['page_no']);?>&nbsp;<?php //echo stripslashes($rs_rec_p1['pub_place']);?>&nbsp;<?php echo stripslashes($rs_rec_p1['publisher']);?></td>
                                            </tr>
                                            <tr>
                                              <td colspan="2">&nbsp;</td>
                                            </tr>
        
			  		              <?php }
			}
			?>
	<!--End Technical Reports International-->
			<!--////////////////===========================End International
End of -->

			
    </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <!--
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xxii. Major Conferences and Workshops Attended: Date starting with latest</legend>
      <table width="621" border="0" align="center" cellpadding="0" cellspacing="0">

        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_conferences where applicationid='$id' and category ='Conference' order by tdate desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num =0;echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Conferences Attended </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					?>
        <tr>
          <td><?php echo ++$num;?></td>
          <td><?php echo stripslashes($rs_rec_p1['confname']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['location']);?>,&nbsp;<?php echo date("d/m/Y", strtotime($rs_rec_p1['fdate']));?>&nbsp;to&nbsp;<?php echo date("d/m/Y", strtotime($rs_rec_p1['tdate']));?>.</td>
        </tr>
        <tr>
          <td width="25">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?php }
			}
			?>
        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_conferences where applicationid='$id' and category ='Workshop' order by tdate desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num =0;echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Workshops Attended </strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					?>
        <tr>
          <td><?php echo ++$num;?></td>
          <td><?php echo stripslashes($rs_rec_p1['confname']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['location']);?>,&nbsp;<?php echo date("d/m/Y", strtotime($rs_rec_p1['fdate']));?>&nbsp;to&nbsp;<?php echo date("d/m/Y", strtotime($rs_rec_p1['tdate']));?>.</td>
        </tr>
        <tr>
          <td width="25">&nbsp;</td>
          <td>&nbsp;</td>
        </tr>
        <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xxiii. Information on Fellowship, Master's Project(s) and PhD Thesis/Theses Supervised: Date starting with latest</legend>
      <table width="621" border="0" align="center" cellpadding="0" cellspacing="0">

        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_fellowship where applicationid='$id' and category ='Fellowship Project' and type ='Completed' order by sole desc, to_year desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Fellowship Projects  Completed</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
			
					?>
        <tr>
          <td width="25"><?php echo ++$num;?></td>
          <td width="596"><?php echo stripslashes($rs_rec_p1['title']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['student']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['matric']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['from_year']);?>-<?php echo stripslashes($rs_rec_p1['to_year']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['sole']);?>.</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <?php }
			}
			?>

        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_fellowship where applicationid='$id' and category ='Fellowship Project' and type ='In Progress' order by sole desc, to_year desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Fellowship Projects In Progress</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
			
					?>
        <tr>
          <td width="25"><?php echo ++$num;?></td>
          <td width="596"><?php echo stripslashes($rs_rec_p1['title']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['student']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['matric']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['from_year']);?>-<?php echo stripslashes($rs_rec_p1['to_year']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['sole']);?>.</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <?php }
			}
			?>


        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_fellowship where applicationid='$id' and category ='Master Project' and type ='Completed' order by sole desc,  to_year desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Master's Project(s) Completed</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
			
					?>
        <tr>
          <td width="25"><?php echo ++$num;?></td>
          <td width="596"><?php echo stripslashes($rs_rec_p1['title']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['student']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['matric']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['from_year']);?>-<?php echo stripslashes($rs_rec_p1['to_year']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['sole']);?>.</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <?php }
			}
			?>

        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_fellowship where applicationid='$id' and category ='Master Project' and type ='In Progress' order by sole desc,  to_year desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Master Project In Progress</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
			
					?>
        <tr>
          <td width="25"><?php echo ++$num;?></td>
          <td width="596"><?php echo stripslashes($rs_rec_p1['title']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['student']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['matric']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['from_year']);?>-<?php echo stripslashes($rs_rec_p1['to_year']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['sole']);?>.</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <?php }
			}
			?>


        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_fellowship where applicationid='$id' and category ='Ph.D Thesis' and type ='Completed' order by  sole desc, to_year desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Ph.D Thesis Completed</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
			
					?>
        <tr>
          <td width="25"><?php echo ++$num;?></td>
          <td width="596"><?php echo stripslashes($rs_rec_p1['title']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['student']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['matric']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['from_year']);?>-<?php echo stripslashes($rs_rec_p1['to_year']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['sole']);?>.</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <?php }
			}
			?>

        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from ap_fellowship where applicationid='$id' and category ='Ph.D Thesis' and type ='In Progress' order by  sole desc, to_year desc");
					
					?>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num = 0;echo"                                            <tr>
                                              <td></td>
                                              <td><strong>Ph.D Thesis In Progress</strong> <br></td>
                                            </tr>
"; 
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
			
					?>
        <tr>
          <td width="25"><?php echo ++$num;?></td>
          <td width="596"><?php echo stripslashes($rs_rec_p1['title']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['student']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['matric']);?>,&nbsp;<?php echo stripslashes($rs_rec_p1['from_year']);?>-<?php echo stripslashes($rs_rec_p1['to_year']);?>,&nbsp;&nbsp;<?php echo stripslashes($rs_rec_p1['sole']);?>.</td>
        </tr>
        <tr>
          <td colspan="2">&nbsp;</td>
        </tr>
        <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  -->
  
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xxiv. Details of Administrative  Experience: Date starting with latest</legend>
      <table width="644" border="0" align="center" cellpadding="0" cellspacing="0">
        <?php						
					$res_rec_s=@mysqli_query($con, "select * from hr_staff_servicetb where fileno='$id' and service_type = 'Administrative' order by from_year desc ");
					
					?>
					<tr>
          <td>&nbsp;</td>
          <td><strong>Type of Service</strong></td>
          <td><strong>Detail of Service</strong></td>
          <td><strong>From</strong></td>
          <td><strong>To</strong></td>
        </tr>
        <?php 
										if(@mysqli_num_rows($res_rec_s)>=1) {$num =0;
									while($rs_rec_s=@mysqli_fetch_array($res_rec_s)) {
					?>
        
        <tr>
          <td width="30"><?php echo ++$num;?></td>
          <td width="151"><?php echo stripslashes($rs_rec_s['service_type']);?></td>
          <td width="274"><?php echo stripslashes($rs_rec_s['service_details']);?></td>
          <td width="100"><?php echo stripslashes($rs_rec_s['from_year']);?></td>
          <td width="89"><?php echo stripslashes($rs_rec_s['to_year']);?></td>
        </tr>
        <?php }
			}
			?>
      </table>
      <br>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  <!--
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
      <legend >xxv. Details of Teaching  Experience: Date starting with latest</legend>
      <table width="644" border="0" align="center" cellpadding="0" cellspacing="0">
        <?php						
					$res_rec_s=@mysqli_query($con, "select * from ap_teaching where applicationid='$id'  order by to_date desc ");
					
					?>
					<tr>
          <td>&nbsp;</td>
          <td><strong>Institution</strong></td>
          <td><strong>Course Taken</strong></td>
          <td><strong>Level</strong></td>
          <td><strong>From</strong></td>
          <td><strong>To</strong></td>
        </tr>
        <?php 
										if(@mysqli_num_rows($res_rec_s)>=1) {$num =0;
									while($rs_rec_s=@mysqli_fetch_array($res_rec_s)) {
					?>
        
        <tr>
          <td width="30"><?php echo ++$num;?></td>
          <td width="151"><?php echo stripslashes($rs_rec_s['organisation']);?></td>
          <td width="274"><?php echo stripslashes($rs_rec_s['course']);?></td>
          <td width="100"><?php echo stripslashes($rs_rec_s['level']);?></td>
          <td width="100"><?php echo stripslashes($rs_rec_s['from_date']);?></td>
          <td width="89"><?php echo stripslashes($rs_rec_s['to_date']);?></td>
        </tr>
        <?php }
			}
			?>
      </table>
      <br>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  
  -->
  
  <tr>
    <td>.</td>
    <td colspan="3" rowspan="2"><fieldset >
      <legend > xxvi Service to the Community: Date starting with latest</legend>
      <table width="642" border="0" align="center" cellpadding="0" cellspacing="0">

        <?php						
					$res_rec_p1=@mysqli_query($con, "select * from hr_staff_servicetb where fileno='$id' and service_type != 'Administrative'  order by from_year desc"  );
					
					?>
					<tr>
          <td>&nbsp;</td>
          <td><strong>Type of Service</strong></td>
          <td><strong>Detail of Service</strong></td>
          <td><strong>From</strong></td>
          <td><strong>To</strong></td>
        </tr>
        <?php 
										if(@mysqli_num_rows($res_rec_p1)>=1) {$num=0;
									while($rs_rec_p1=@mysqli_fetch_array($res_rec_p1)) {
					?>
        
        <tr>
        <td width="29"><?php echo ++$num;?></td>
        <td width="200"><?php echo stripslashes($rs_rec_p1['service_type']);?></td>
        <td width="220"><?php echo stripslashes($rs_rec_p1['service_details']);?></td>
        <td width="105"><?php echo stripslashes($rs_rec_p1['from_year']);?></td>
        <td width="88"><?php echo stripslashes($rs_rec_p1['to_year']);?></td>
        </tr>
        <?php }
			}
			?>
      </table>
    </fieldset></td>
    <td rowspan="2">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
    <legend >xxvii. Present Employment, Status, Salary and Employer</legend>
    <table width="648" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="2"><strong>Present Employer </strong></td>
        <td width="126"><strong>Rank/Status</strong></td>
        <td width="121"><span class="style13 style12"><strong>Salary </strong></span></td>
        <td width="58"><strong>From</strong></td>
        <td width="48"><strong>To</strong></td>
      </tr>
      <?php						
					$res_rec_e=@mysqli_query($con, "select * from hr_staff_employmenttb where fileno='$id' and employment_type='Present'");
					
					?>
      <?php 
										if(@mysqli_num_rows($res_rec_e)>=1) {
									while($rs_rec_e=@mysqli_fetch_array($res_rec_e)) {$num=0;
					?>
      <tr>
        <td width="26"><?php //echo ++$num;?></td>
        <td width="269"><?php echo stripslashes($rs_rec_e['employer_name']);?></td>
        <td><?php echo stripslashes($rs_rec_e['rank']);?></td>
        <td><?php echo stripslashes($rs_rec_e['salary']);?></td>
        <td><?php echo stripslashes($rs_rec_e['from_year']);?></td>
        <td><?php echo stripslashes($rs_rec_e['to_year']);?></td>
      </tr>
      <?php }
			}
			?>
    </table>
    <br>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
    <legend >xxviii. Extra Curricular Activities</legend>
    <br>
    <table width="621" border="0" align="center" cellpadding="0" cellspacing="0">
      <tr>
        <td width="237"><?php echo stripslashes($rs_staff['hobbies']);?></td>
        <td width="10">&nbsp;</td>
        <td width="120">&nbsp;</td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><fieldset >
    <legend >xxix. Names and Addresses of Three Referees</legend>
    <table width="558" border="0" align="left" cellpadding="0" cellspacing="0">
      <tr>
	        <?php						
					$res_rec_r=@mysqli_query($con, "select * from hr_staff_refereetb where fileno='$id' order by ref_name limit 0,3");
					
					?>
      <?php 
										if(@mysqli_num_rows($res_rec_r)>=1) {$num =0;
									while($rs_rec_r=@mysqli_fetch_array($res_rec_r)) {
					?>
        <td width="159"><strong>Referee Name </strong></td>
        <td width="262"><?php echo stripslashes($rs_rec_r['ref_name']);?></td>
        </tr>

      <tr>
        <td>Occupation</td>
        <td><?php echo stripslashes($rs_rec_r['ref_occupation']);?></td>
        </tr>
      <tr>
        <td>Address</td>
        <td><?php echo stripslashes($rs_rec_r['ref_address']);?></td>
        </tr>
      <tr>
        <td>Phone Number</td>
        <td><?php echo stripslashes($rs_rec_r['ref_phone_no']);?></td>
        </tr>
      <tr>
        <td>Email</td>
        <td><?php echo stripslashes($rs_rec_r['ref_email']);?></td>
        </tr>
      <!--<tr>
        <td>Period</td>
        <td><?php echo stripslashes($rs_rec_r['ref_know_period']);?></td>
        </tr>-->
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
      <?php }
			}
			?>
    </table>
    <br>
    </fieldset></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td >&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><span class="style12 style13">Date</span><strong>
      <?php $signdate = @date("d/m/Y",time()); echo "$signdate";?>
      </strong></td>
    <td><span class="style12 style13">Signature</span></td>
    <td><img src="<?php  echo $path_sign; ?>" alt=" " width="90" height="45" /></td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3">&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan="3"><input name="button" type="button" onClick="window.print();exitform();return false; 
" value=" Print this page " /></td>
    <td>&nbsp;</td>
  </tr>
</table>           
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