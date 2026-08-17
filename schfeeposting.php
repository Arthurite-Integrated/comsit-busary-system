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
                                
 <div id="page" class="container">
	  <div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
        <h3><i>School Fees Posting</i></h3>
        <p>
        <form  name="frm" id="frm">
		  <table width="70%" border="0">
		    
			<tr>
			  <th colspan="2">Grand Amount<br />
                <input name="grandamount" type="text" id="grandamount" size="25" onkeydown="validatefrm()";"/></th>
		    </tr>
			<tr>
			  <th width="40%">Start Date <br />
                <input type="text" name="start_date" id="start_date"/></th>
			  <th width="60%">End Date <br />
                <input type="text" name="end_date" id="end_date"/></th>
		    </tr>
			<tr>
              <th>Session<br />
                <select name="session" id="session">
                  
		          
				<option value="">---</option>
                              
		          
                  <?php 
			   				 
				 $dSessYear = date('Y');//$dSess_sp[1];
				for ($t=$dSessYear; $t >= 2015; $t--)
				{
					$t2 = $t - 1; $tSession = "$t2" . "/" . "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					echo "<option value='$tSession'>$tSession</option>";
				}
				?>
                </select></th>
			  <th>Programme <br />
                <select name="programme" id="programme" class="txt" onchange="swapcontent('level_details');swapcontent('prog_dept',this.value);">
                  <option selected="selected" value="">---</option>
                  <?php
                          $res_c=@mysqli_query($con, "select * from school_programmetb order by programme");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                               $programme=@$rs_c['programme'];
                              echo "<option value='$programme'> $programme</option>";
                           }
                    ?>
                </select></th>
		    </tr>
			
		  			  
		    <tr>
		      <th>Account Dept/Unit/Centre <br />
		        <span id="prog_dept">
		        <select name="prog_dept" size="1" class="txt" id="prog_dept" onchange="swapcontent('dept_acct',this.value);">
                  <option selected="selected" value="">---</option>
                </select>
	          </span></th>
			  <th>Corresponding Account <br />
			    <span id="dept_acct">
			    <select name="corr_acct" id="corr_acct" class="txt" >
                  <option selected="selected" value="">---</option>
                </select>
		      </span></th>
		   </tr>
		   		
		   	 
	      </table>
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form>
          </p>
	  </div><!-- end of content -->

		
		<!-- ############### Side bar ###############################-->
		
			<?php// include("sidebar_main.php");?>
		<!-- end of side bar -->
	</div><!-- end of container -->
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