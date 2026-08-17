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
<!DOCTYPE html>
<html>
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
$(document).ready(function(){
	
    $('#payee_acct').numberbox({
    min:0,
    precision:2,
	groupSeparator:","
    });

});

function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_m.php";
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
   
  if(cv=='month_breakdown') //start month_breakdown section
  {
	  	$.post(url,{contentvar:cv,months:v,action:a},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
  }//end of month_breakdown section 
  
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
	        <h2>Financial Year Settings</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Tax Rate Setup</i></h3> -->
			<p>
          <form name="frm_month" id="frm_month" method="post">
			<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <th width="19%" nowrap>Select Start Month:</th>
    <td width="78%"><select name="months" id="months" class="txt" onChange="swapcontent('month_breakdown', $('#months').val(), $('#action').val());">
                  <option selected="selected" value="">---</option>
                  <?php
                          $res_c= mysqli_query($con, "select * from monthtb order by month_code") or die( mysqli_error($con));
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $month_code=@$rs_c['month_code'];
							  $title=@$rs_c['month_name'];
                              echo "<option value='$month_code'>$title</option>";
                           }
                          echo "</select>";
						 ?>
                </select></td>
    <td width="3%"><input name="action" id="action" type="hidden" value="generate"></td>
  </tr>
  <tr>
    <td colspan="3" align="left" valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td width="50%" align="left" valign="top" bgcolor="#EAEAEA"><?php 
	$month_code =  mysqli_real_escape_string($con, $_REQUEST['months']);
	$action =  mysqli_real_escape_string($con, $_REQUEST['action']);
	$q = array('', '1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter');
	//var_dump($q);
	$q_c = 0; $qcount=1;
	echo "======Existing Definition ======<br>";
	echo "<table width='90%' align='left' border='1' cellspacing='0' cellpadding='3' rules='rows' frame='hsides'>
			<tr><th align='left'>Months</th><th align='left'>Quarter</th></tr>";
	$res_c= mysqli_query($con, "select * from monthtb order by quarter") or die("SQL Error!");
	while($rs_c=@mysqli_fetch_array($res_c)) {
		$q_c++; if($q_c > 3) { $q_c = 1; $qcount++; }
	  	$m_code=@$rs_c['month_code']; $title=@$rs_c['month_name']; $quarter=@$rs_c['quarter'];
	  	//echo $title."::".$month_code."<br>";
	  	echo "<tr><td align='left'>$title</td><td align='left'>".$quarter."</td></tr>";
		//if($action == "update")  mysqli_query($con, "update monthtb set quarter='". mysqli_real_escape_string($con, $q[$qcount])."' where month_code='". mysqli_real_escape_string($con, $m_code)."'");
	}
	echo "</table>";
 ?></td>
          <td width="50%" align="left" valign="top" bgcolor="#D6D6D6"><div id="month_breakdown"></div></td>
        </tr>
      </table>
    </td>
    </tr>
</table>
        <div id="roll"> </div>
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