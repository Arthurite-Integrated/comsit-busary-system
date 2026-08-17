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
<!--[if IE 6]><link href="default_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->
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

if(cv=='display_approve_schedule_voucher')
{
	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
	$('#display').html(data).show();
	$('#display2').html('').show();
	$('#roll').html('').show();
	});
	
} //end of display_schedule_voucher

if(cv=='approve_schedule_voucher')
{
	if($("#sch_no").val()=='')
	 { alert('Select schedule number from the list'); $('#roll').html('').show(); exit();}
	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
	//$('#display').html(data).show();
	$('#display2').html(data).show();
	$('#roll').html('').show();
	});
	
} //end of approve_schedule_voucher


if(cv=='schedule_voucher')
{
	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
	$('#display2').html(data).show();
	$('#roll').html('').show();
	});
	
} //end of schedule_voucher
  
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
       <center> <h3><i>Print  Scheduled/Mandate Vouchers</i>        </h3>
        
        <form enctype="multipart/form-data">
		  <table border="0">
		    <tr>
		      <td><strong>Schedule/Mandate Number</strong>
              <br />
              <select name="sch_no" id="sch_no">
                <option selected="selected" value="">---</option>
                <?php
                          $res_c=@mysqli_query($con, "select distinct schedule_no,final_approval_date from vouchertb where schedule_no!='' order by schedule_no");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $sch_no=@$rs_c['schedule_no'];
							  $final_approval_date=date('d/m/Y',strtotime(@$rs_c['final_approval_date']));
                              echo "<option value='$sch_no'>$sch_no <=>($final_approval_date)</option>";
                           }
                          echo "</select>";
						 ?>
              </select><input type="hidden" name="status" id="status" value="no" />
              </td>
	        </tr>
            <tr>
            <td><strong>
            Amount Range:</strong><br />
            <select name="amount_range" id="amount_range">
                <option selected="selected" value="">All</option>
                <option value="amount_paid <= 50000"><= N50,000.00</option>
                <option value="amount_paid > 50000 and amount_paid < 250000">Between N51,000.00 and N249,999.00</option>
                <option value="amount_paid >= 250000">N250,000.00 and Above</option>
             </select>
            </td>
            
            </tr>
            <tr>
            <td><strong>
            Batch Number (if any):</strong><br />
            <input name="batch_no" />
            
            </td>
            </tr>
		    <tr>
		      <th><br /><br /><br />
              <input type="button" name="button" id="button" value="Update Batch Number" class="btn" onclick="swapcontent('approve_schedule_voucher','update_batch_no');"/>
              <input type="button" name="button" id="button" value="Display Scheduled/Mandate Vouchers" class="btn" onclick="swapcontent('approve_schedule_voucher','print');"/>
              
              </th>
	        </tr>
          </table>
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form>
        </center>
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