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
      <h3><i>Training/Recognition and Awards Management</i></h3>
                
			  <p>
       			This section is used to setup training programme approved for the staff as well as recognition and award given to the staff by the management. Kindly supply correct information in the spaces provided and ensure that you cross-check all your entries before submission. <br/>
            			<br/>
                      
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <th><font color="red">Staff Number: </font>
                          <!--<input name="fileno" type="text" id="fileno" size="40" onblur="swapcontent('load_staff_details',this.value);swapcontent('load_pix',this.value);"/> -->
                          
                          <input name="fileno" type="text" id="fileno" size="40" <?php if(strtolower($r_vals)!='super admin' and strtolower($r_vals)!='administrator' and strtolower($r_vals)!='registry officer' and strtolower($r_vals)!='registry admin') echo "value='$login_id' readonly=''"; ?> onblur="if (this.value !='') swapcontent('load_staff_details',this.value);swapcontent('load_pix',this.value);"/>
						  <?php
						  /*echo "<script>document.getElementById('fileno').focus();</script>"; */
						  ?>
                          <br/><br /><span id="fullname"></span></th>
                        </tr>
                        <tr>
                          <th>
                               <div id="tabss"> <!-- Start main  tab div -->
                                      <ul>
                                        
                                        <li><a href="#tabs-9"><b>Training Programme</b></a></li>
                                        <li><a href="#tabs-12"><b>Honour/Recognition</b></a></li>
                                    </ul>
                                      
                            
                                    <div id="tabs-9"> <!-- tab 9 trainig --->
                                       <font color="red"><b>Use this form to provide training/seminar/workshop/conferences approved for the staff. Cross-check your entry before submission.</b></font>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Type of Training</td>
                                            <td width="28%"><select name="tra_type" id="tra_type" class="txt" onchange="swapcontent('load_no_paper_read',this.value);">
                                              <option selected="selected" value="">---</option>
                                              <option value="Conference">Conference</option>
                                              <option value="Seminar">Seminar</option>
                                              <option value="Workshop">Workshop</option>
                                            </select><br/>
                                            <span id="load_no_paper_read"></span>
                                            </td>
                                            <td width="12%">Training Title/Theme</td>
                                            <td width="40%"><input name="tra_title" type="text" id="tra_title" size="40"/></td>
                                          </tr>
                                          <tr>
                                            <td>Location</td>
                                            <td><input name="tra_location" type="text" id="tra_location" size="30"/></td>
                                            <td>Venue</td>
                                            <td><input name="tra_venue" type="text" id="tra_venue" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Sponsor</td>
                                            <td><input name="tra_sponsor" type="text" id="tra_sponsor" size="30"/></td>
                                            <td>Start Date</td>
                                            <td><input name="tra_start_date" type="text" id="tra_start_date" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Amount Granted</td>
                                            <td><input name="tra_amount" type="text" id="tra_amount" size="30"/></td>
                                            <td>End Date</td>
                                            <td><input name="tra_end_date" type="text" id="tra_end_date" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">Date Approved: 
                                              <input name="tra_date_approved" type="text" id="tra_date_approved" size="40"/>
                                            </div></td>
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
                                    </div><!-- tab 9 ends --->
                                
                                    
                                    <div id="tabs-12"> <!-- tab 12 Honour/Recognition --->
                                       <font color="red"><b>Use this form to provide Honour/Recognition received by the staff from the management of your institution. Cross-check your entry before submission.</b></font>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Type of Honour/Award</td>
                                            <td width="28%"><input name="honour_type" type="text" id="honour_type" size="30"/>
                                            </td>
                                            <td width="12%">Date of Award/Honour</td>
                                            <td width="40%"><input name="honour_date" type="text" id="honour_date" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Award/Honour Description</td>
                                            <td><textarea name="honour_desc" id="honour_desc" cols="40" rows="3" class="txt"></textarea></td>
                                            <td>Prize (if any)</td>
                                            <td><textarea name="honour_prize" id="honour_prize" cols="40" rows="3" class="txt"></textarea></td>
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
                                       
                                    </div><!-- tab 12 ends --->
                                    
                                    
                                    
                            </div> <!-- end main tab div -->
                          
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