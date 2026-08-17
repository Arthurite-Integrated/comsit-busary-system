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
                                
<div id="featured" class="container">
<div class="box">
			
           <!-- <div class="title">
				<h3>Use the form below to create account for your application. Note that after creating your account details, you are required to login with your Application Number and Password through the Login link at the top of this page. An email alert will be sent to your email address containing your Application Number and Password for Login purpose.</h3>
		    </div> -->
            
            <?php
			       ///////////get the payment and application details
				          $app_year=@date('Y'); //can be determined later
				   		/*	$res_p=@mysqli_query($con, "select * from settingstb where parameter='application'");
							 $rs_p=@mysqli_fetch_array($res_p);
							 $application_start_date =$rs_p['start_date'];  //start date
							 $application_exp_date=$rs_p['end_date'];  //end date
							 $application_amount =$rs_p['amount'];
							 $payment_item_id=@$rs_p['pay_item_id'];  //interswitch payment item ID for hostel payment
							 $payment_desc=@$rs_p['parameter_desc'];
							 $payment_parameter=$rs_p['parameter'];
							 $session=$rs_p['session'];	 //session in the payment settings table		 
							 
							 $application_start_date2 = @str_replace("-","",$application_start_date);
							 $application_exp_date2 = @str_replace("-","",$application_exp_date);
							 $my_day = @date("Ymd"); 
							 							 
							 							 
							 $new_date=@date('l jS F, Y',$rdate);
							 if($my_day >= $application_start_date2 && $my_day <= $application_exp_date2)
								  { 
								  //echo "<strong><h2><font color='red'>".(@abs($day_to_go[0])+1)." day(s) left to make your payment.</font></h2></strong>";
								  } 
								  else { echo "<center><b><font color='red'>Application form is currently unavailable.</font></b></center>"; exit;}		   
			   
			   */
			   ?>
      <p align="center">Use the form below to create account for your application. Note that after creating your account details, you are required to login with your Application Number and Password through the Login link at the top of this page. An email alert will be sent to your email address containing your Application Number and Password for Login purpose.</p>
               <center>
               <form name="frm" id="frm">
               <table>
                <tr><td><b>Surname</b></td><td><input type="text" size="40" class="txt" name="surname" id="surname" /></td></tr>
                <tr><td><b>First Name</b></td><td><input type="text" size="40" class="txt" name="first_name" id="first_name" /></td></tr>
                <tr><td><b>Other Name</b></td><td><input type="text" size="40" class="txt" name="other_name" id="other_name" /></td></tr>
                <tr><td><b>Phone Number</b></td><td><input type="text" size="40" class="txt" name="phone_no" id="phone_no" /></td></tr>
                <tr><td><b>Email Address</b></td><td><input type="text" size="40" class="txt" name="email" id="email" /></td></tr>
                <tr><td><b>Password</b></td><td><input type="password" size="40" class="txt" name="pass" id="pass" /></td></tr>
                <tr><td><b>Confirm Password</b></td><td><input type="password" size="40" class="txt" name="conf_password" id="conf_password" /></td></tr>
                <tr><td><b>Year of Application</b></td><td><input type="text" size="10" class="txt" name="app_year" id="app_year" value="<?php echo $app_year; ?>" readonly="readonly"/></td></tr>
                <tr><td colspan="2"><center><input type="button" name="button1" id="button1" value="Create Account" class="btn" onClick="swapcontent('create_application');"/>
                </center></td></tr>
               </table>
               </form>
               <center><div id="create_application"></div> <div id="display"></div> <div id="roll"></div></center>
               
      </center>
      
 
	  </div>
		
	<?php //include("box.php");?>
	
</div>                                
           
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