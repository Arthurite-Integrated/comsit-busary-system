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
                <h3><i>Current Staff Report Section</i></h3>
			    <p>
				
			<form name="frm" id="frm" action="hr_current_staff_report.php" method="post" target="_blank">
            	<table>
			<tr>
			<th>Staff Status
			<br />
			<select name="status" id="status" tabindex="1" onchange="">
					<option selected="selected" value="">--</option>
					<option value="Senior">Senior</option>
					<option value="Junior">Junior</option>
					
				  </select>
			</th>
			<th>Category<br />
			
			
			<select name="category" id="category" tabindex="2" onchange="">
					<option selected="selected" value="">--</option>
					<option value="academic">Academic Staff</option>
					<option value="non-academic">Non-Academic Staff</option>
					
				  </select>
				  </th>
			</tr>
			
			
			
				<tr>
				  <th>Department<br />
				 
				    <select name="dept" id="dept">
				      <!-- onchange="swapcontent('load_unit',document.getElementById('dept').value)" -->
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
			        </select>
				  </th>
				  <th>Position/Rank<br />
				    <select name="position" id="position">
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
			        </select>
				  </th>
				  </tr>
				 <tr>
                <th colspan="2">Staff File Number <br />
				<select name="staff" id="staff" tabindex="5" onchange="swapcontent('specific',this.value)">
					<option selected="selected" value="all">All Staff</option>
					<option value="specific">Specific Staff</option>
					
				  </select><br />
				<span id='specific'></span></th>
				</tr>
				<tr>
				  <th colspan="4"><!--<input type="button" class="btn" name="sbtn" id="sbtn" value="Save"   />-->
                  <input type="Submit" class="btn" name="chbtn" id="chsbtn" value="Display"/></th>
				  </tr>
				</table>
			
			<span id="rollback_applicant"></span>	
			<div id="display"></div>
			<div id="roll"></div>
		  </form>
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