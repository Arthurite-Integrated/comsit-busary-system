<?php  @session_start();
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
          {   
                    var divid="#"+cv;
                    $(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
                    var url="scriptfile_a.php";
                    var str;
                    
                    if(cv=='lock_posting')
                    {
                              $.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  
                                        $(divid).html(data).show();
                              });
                    }
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
	        <h2>VIEW UPLOADED DATA</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
                
                <div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Accounting Department Setup</i></h3> -->
                <p>
		<form action="recuploadreport.php?sanusi" target="_blank" method="post" enctype="multipart/form-data">
		<table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
		<tr> 
		<td height="33" colspan="4" align="left" bgcolor="#CCFFFF"><h4><strong>VIEW UPLOADED DATA</strong></h4></td>
		</tr>
		<tr>
		<th height="33" align="left" bgcolor="#CCFFFF">Month/Year:<strong style="color:#F00">*</strong></th>
		<td height="33" align="left" bgcolor="#CCFFFF">
		<select name="rmonthS" id="rmonthS">
		<option selected="selected" value="">--</option>
		<option value="January">January</option>
		<option value="February">February</option>
		<option value="March">March</option>
		<option value="April">April</option>
		<option value="May">May</option>
		<option value="June">June</option>
		<option value="July">July</option>
		<option value="August">August</option>
		<option value="September">September</option>
		<option value="October">October</option>
		<option value="November">November</option>
		<option value="December">December</option>
		</select>
		/
		<select name="ryearS" id="ryearS">
		<option selected="selected" value="">--</option>
		<?php for($t=date('Y'); $t >= 2017; $t--) echo '<option value="'.$t.'">'.$t.'</option>'; ?>
		</select></td>
</tr>
                    <tr>
		<th align="left" bgcolor="#CCFFFF">Record:<strong style="color:#F00">*</strong></th>
		<td align="left" bgcolor="#CCFFFF"><select name="recordtypeS" id="recordtypeS" >
		<option selected="selected" value="">--</option>
		<option value="recon_banktb">Bank Statement</option>
		<option value="recon_remitatb">Remita Statement</option>
		</select>
		</td>
                    </tr>
                    <tr>
		<th align="left" bgcolor="#CCFFFF">Record Type:<strong style="color:#F00">*</strong></th>
		<td align="left" bgcolor="#CCFFFF"><select name="typeS" id="typeS" >
		<option selected="selected" value="">--</option>
		<option value="Credit">Credit</option>
		<option value="Debit">Debit</option>
		</select>
		</td>
		</tr>
		<tr>
		<td height="33" colspan="2" align="center" bgcolor="#CCFFFF">
		<button type="submit" class="btn btn-outline-primary btn-fw" name="sbtn_nS" id="sbtn_n2S"> VIEW RECORD </button>		<p>&nbsp;</p>
		</td>
		</tr>
		</table>
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