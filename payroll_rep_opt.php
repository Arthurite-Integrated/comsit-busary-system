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
   <link rel="stylesheet" type="text/css" href="include/easyui.css">
   <link rel="stylesheet" type="text/css" href="include/icon.css">
	<link rel="stylesheet" type="text/css" href="include/demo.css">
    <link rel="stylesheet" href="css/tinybox.css" />
    <script type="text/javascript" src="include/jquery.min.js"></script>
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    <script src="datepicker/jquery-1.8.3.js"></script>
    <script src="datepicker/jquery-ui.js"></script>
<?php //include("required_jQuery_files.php");
include "function.php";
		$mode=@base64_decode($_REQUEST['mode']);   //"payslip";
		if($mode=='payslip')
		   $display="Payslip";
		elseif($mode=='bank_list')
		   $display="Bank List";

?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
$(function(){
		   $("#start_date").datepicker({dateFormat:"yy-mm-dd"});
		   $("#end_date").datepicker({dateFormat:"yy-mm-dd"});
		   }
		   );
	
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
 
if(cv=="specific")
{
		if(v=='specific')
			{
		$.post('scriptfile_b.php',{contentvar:cv,dept_acctcode:v},function(data){
		$(divid).html(data).show();
		$('#roll').html('').show();
		});
		}
		else
			{
			$('#specific').html('').show();
			$('#roll').html('').show();
			}
} //end of specific
 
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
	        <h2><?php echo $display;?> Report Option</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
        <h3><i><?php echo $display;?> Report Option</i></h3> -->
        <p>
        <form enctype="multipart/form-data" name="frm" id="frm" action="report_template.php" target="_blank" method="post">
		  <table width="70%" border="0" align="left" cellpadding="3" cellspacing="0">
		    <tr>
		      <th width="40%" align="left">Month
              <br />
              <select name="month" id="month" style="width:200px">
                <option selected="selected" value="">---</option>
                <?php
                          $res_c=@mysqli_query($con, "select * from monthtb order by month_code");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $month_code=@$rs_c['month_code'];
							  $month_name=@$rs_c['month_name'];
                              echo "<option value='$month_code'>$month_name</option>";
                           }
                          echo "</select>";
						 ?>
              </select></th>
  </tr>
		    <tr>
		      <th align="left">Year <br />
                <select name="year" id="year" style="width:200px">
                  <option selected="selected" value="">---</option>
                  <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                </select>
              <input type="hidden" name="mode" id="mode" value="<?php echo base64_encode($mode);?>"/></th>
  </tr>
		    <tr>
		      <th align="left">Staff Category<br />
				<select name="staff" id="staff" tabindex="3" onchange="swapcontent('specific',this.value)" style="width:200px">
					<option selected="selected" value="all">All Staff</option>
					<option value="academic">Academic Staff</option>
					<option value="non-academic">Non-Academic Staff</option>
					<option value="specific">Specific Staff</option>
					<option value="non-academic_junior">Non-Academic Staff (Junior)</option>
					<option value="non-academic_senior">Non-Academic Staff (Senior)</option>
			    </select><br />
				<span id='specific'></span>
              </th>
  </tr>
		    <tr>
		      <th align="left">Select Bank<br />
                <select name="bank" id="bank" tabindex="3" style="width:200px">
                  <option selected="selected" value="">Select...</option>
					<?php
					$r=@mysqli_query($con, "select distinct bank_name from payroll_scheduletb where bank_name != '' order by bank_name");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['bank_name'];
							echo "<option value='$scourse'>$scourse</option>";
							
						}
					
					?>
                </select>
			</th>
		      </tr>
		    <tr>
		      <th align="left"><input type="submit" name="Submit" id="button" value="Display <?php echo $display;?>" class="btn"/></th>
	        </tr>
	      </table>
        <div id="display"></div>
         <div id="display2"></div>
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