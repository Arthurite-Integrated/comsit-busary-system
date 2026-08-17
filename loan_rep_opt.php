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
	        <h2>Loan Schedule/Report Option</h2>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
		  <p>
          <table width="100%" cellpadding="0" cellspacing="0">
             <tr>
                 <td width="50%" align="left" valign="top" bgcolor="#E5E5E5"><strong>DEDUCTION SCHEDULE</strong></td>
                 <td width="1%" align="center" valign="top">&nbsp;</td>
                 <td width="50%" align="left" valign="top" bgcolor="#E5E5E5"><strong>ANNUAL LOAN SCHEDULE</strong></td>
            </tr>
             <tr>
                 <td width="50%" align="center" valign="top" bgcolor="#D6D6D6">
                    <form name="frm" id="frm" action="loan_report.php" target="_blank" method="post">
		  <table width="70%" border="0">
		    <tr>
		      <td width="50%" align="left">Month
              <br />
              <select name="month" id="month">
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
              </select></td>
		      
		      <td width="50%" align="left">Year <br />
                <select name="year" id="year">
                  <option selected="selected" value="">---</option>
                  <?php
                          for($i=date('Y');$i>=2022; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                </select>
                <input type="hidden" name="mode5" id="mode5" value="<?php echo base64_encode($mode);?>"/></td>
	        </tr>
                  <tr>
                     <td align="center" colspan="3">Loan Type <br />
                       <select name="pcat" size="1" id="pcat" style="width:100%;">
                         <option selected="selected" value="">---</option>
                         <?php  $q =  mysqli_query($con, "SELECT DISTINCT h.loan_type, f.title FROM hr_loan_apptb h INNER JOIN foliotb f ON h.loan_type=f.folio_code WHERE h.process_status != 'Completed'");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r[0] .'">'. $r[1] .'</option>';
							  }
							  ?>
                       </select></td>
                   </tr>
		    <tr>
		      <th height="68" colspan="2"><input type="submit" name="Submit" id="button" value="Display Schedule" class="btn"/></th>
	        </tr>
	      </table>
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form>
</td>
                 <td width="1%" align="center" valign="top">&nbsp;</td>
                 <td width="50%" align="center" valign="top" bgcolor="#D6D6D6">
                 <form name="frm" id="frm2" action="loan_schedule.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left">Year <br />
                       <select name="pyear" id="pyear" style="width:90%">
                         <option selected="selected" value="">---</option>
                         <?php
                          for($i=date('Y');$i>=2022; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                       </select>
                       <input type="hidden" name="mode2" id="mode2" value="<?php echo base64_encode($mode);?>"/></td>
                   </tr>
                   <tr>
                     <td align="center" colspan="3">Loan Type <br />
                       <select name="pcat" size="1" id="pcat" style="width:100%;">
                         <option selected="selected" value="">---</option>
                         <?php  $q =  mysqli_query($con, "SELECT DISTINCT h.loan_type, f.title FROM hr_loan_apptb h INNER JOIN foliotb f ON h.loan_type=f.folio_code");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r[0] .'">'. $r[1] .'</option>';
							  }
							  ?>
                       </select></td>
                   </tr>
                   <tr>
                     <th height="29" colspan="2"><input type="submit" name="button" id="button2" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display3"></div>
                 <div id="display4"></div>
                 <div id="roll2"></div>
               </form></td>
            </tr>
            <tr>
			   <td height="10" colspan="3" align="center" valign="top"></td>
		    </tr>


          </table>
	      
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