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
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
                <h3><i>Staff Leave Management</i></h3>
                <p>Use this section to processing staff leave request.</p>
			<p>
          <div class="easyui-tabs" style="width:auto;height:auto"> <!-- begining of main tab-->
                       <div title="Pending Leave Request" style="padding:10px">  <!-- pending tab  -->
                         <?php
						   $r=@strtolower($r_vals);
						     
							 $sql="select l.id, l.fileno, l.leave_type, l.app_date, l.app_start_date, l.app_end_date, l.approval_start_date, s.staff_status, s.category, s.dept_code from hr_leave_apptb l,stafftb s where l.fileno=s.fileno and l.approval_start_date='0000-00-00' order by l.app_date desc, s.dept_code asc";
							   
							$res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
							$sn=0;
							$tb="<table align='center'><tr><th>S/NO</th><th>FILE NO</th><th>STAFF NAME</th><th>LEAVE TYPE</th><th>PROPOSED START DATE</th><th>PROPOSED END DATE</th><th>ACTION</th></tr>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $id=$rs_v['id'];
									 $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>{$rs_v['leave_type']}</td><td>".@date('d/m/Y',strtotime($rs_v['app_start_date']))."</td><td>".@date('d/m/Y',strtotime($rs_v['app_end_date']))."</td><td><a href=\"javascript:swapcontent('process_leave','$id');\">PROCESS LEAVE</a> </td></tr>";
								 } //end of while
								 
								 $tb.="</table>"; echo $tb;
						    }
							else
						       echo "<font color='red'><b>No pending leave application for approval</b></font>";
						 ?>
                             <div id="process_leave"></div>
                       </div>  <!-- end of pending tab-->
                       
                       <div title="Approved Leave" style="padding:10px"> <!-- processed tab  -->
                        <?php
						   $r=@strtolower($r_vals);
						   
						   $sql="select l.id, l.fileno, l.leave_type, l.app_date, l.app_start_date, l.app_end_date, l.approval_start_date,l.approval_end_date, s.staff_status, s.category, s.dept_code from hr_leave_apptb l,stafftb s where l.fileno=s.fileno and l.approval_start_date!='0000-00-00' order by l.app_date desc, s.dept_code asc";
							   
							$res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
							$sn=0;
							$tb="<table align='center'><tr><th>S/NO</th><th>FILE NO</th><th>STAFF NAME</th><th>LEAVE TYPE</th><th>APPROVED START DATE</th><th>APPROVED END DATE</th><th>ACTION</th></tr>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $id=$rs_v['id'];
									 $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>{$rs_v['leave_type']}</td><td>{$rs_v['approval_start_date']}</td><td>{$rs_v['approval_end_date']}</td><td><a href=\"javascript:swapcontent('rollback_leave','$id');\">ROLLBACK LEAVE</a> </td></tr>";
								 } //end of while
								 
								 $tb.="</table>"; echo $tb;
						    }
							else
						       echo "<font color='red'><b>No record to display</b></font>";
						?>
                          <div id="rollback_leave"></div>
                       </div> <!-- end of processed tab-->
                       
          </div> <!-- end of main tab -->
            </p>
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