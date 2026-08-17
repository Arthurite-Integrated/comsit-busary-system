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
	        <h2>Budget Report Option</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
		  <p>
          <table width="100%" cellpadding="0" cellspacing="0">
             <tr>
                 <td width="50%" align="left" valign="top" bgcolor="#E5E5E5"><strong>BUDGET PERFORMANCE (SUMMARY)</strong></td>
                 <td width="1%" align="center" valign="top">&nbsp;</td>
                 <td width="50%" align="left" valign="top" bgcolor="#E5E5E5"><strong>BUDGET PERFORMANCE (EXTENDED)</strong></td>
            </tr>
             <tr>
                 <td width="50%" align="center" valign="top" bgcolor="#D6D6D6"><form name="frm" id="frm" action="budget_performance_s.php" target="_blank" method="post">
		  <table width="70%" border="0">
		    <tr>
		      <td width="50%" align="left">From
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
		      <td width="50%" align="left">To
              <br />
              <select name="montht" id="montht">
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
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                </select>
                <input type="hidden" name="mode5" id="mode5" value="<?php echo base64_encode($mode);?>"/></td>
	        </tr>
            <tr>
		      
		      <td align="center" colspan="3">&nbsp;</td>
              </tr>
		    <tr>
		      <th height="68" colspan="3"><input type="submit" name="Submit" id="button" value="Display Report" class="btn"/></th>
	        </tr>
	      </table>
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form></td>
                 <td width="1%" align="center" valign="top">&nbsp;</td>
                 <td width="50%" align="center" valign="top" bgcolor="#D6D6D6">
                 <form name="frm" id="frm3" action="budget_performance_2_s.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="50%" align="left">From
                       <br />
                       <select name="pmonth2" id="pmonth2">
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
                     <td width="50%" align="left">To
                       <br />
                       <select name="pmontht2" id="pmontht2">
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
                       <select name="pyear2" id="pyear2">
                         <option selected="selected" value="">---</option>
                         <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                        </select>
                      <input type="hidden" name="mode3" id="mode3" value="<?php echo base64_encode($mode);?>"/></td>
                   </tr>
                   <tr>
                     <td align="center" colspan="3">Category <br />
                       <select name="pcat[]" size="1" multiple="MULTIPLE" id="pcat" style="width:100%; height:100px">
                         <option selected="selected" value="">---</option>
                         <?php  $q =  mysqli_query($con, "SELECT DISTINCT bursary_sub_category FROM budgettb WHERE bursary_sub_category != '' and bursary_sub_category != '' and (bursary_category = 'Recurrent' OR bursary_category = 'Departmental')");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r[0] .'">'. $r[0] .'</option>';
							  }
							  ?>
                       </select></td>
                   </tr>
                   <tr>
                     <th colspan="3"><input type="submit" name="button2" id="button3" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display5"></div>
                 <div id="display6"></div>
                 <div id="roll3"></div>
               </form></td>
            </tr>
            <tr>
			   <td height="10" colspan="3" align="center" valign="top"></td>
		    </tr>
             <tr>
               <td align="left" valign="top" bgcolor="#E5E5E5"><strong>BUDGET PERFORMANCE (COMPREHENSIVE)</strong></td>
               <td width="1%" align="center" valign="top">&nbsp;</td>
               <td align="left" valign="top" bgcolor="#E5E5E5"><strong>BUDGET BREAKDOWN</strong></td>
             </tr>
             <tr>
               <td align="center" valign="top" bgcolor="#D6D6D6"><form name="frm" id="frm2" action="budget_performance.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left">Year <br />
                       <select name="pyear" id="pyear" style="width:90%">
                         <option selected="selected" value="">---</option>
                         <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                       </select>
                       <input type="hidden" name="mode2" id="mode2" value="<?php echo base64_encode($mode);?>"/></td>
                   </tr>
                   <tr>
                     <th height="29" colspan="2"><input type="submit" name="button" id="button2" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display3"></div>
                 <div id="display4"></div>
                 <div id="roll2"></div>
               </form></td>
               <td width="1%" align="center" valign="top">&nbsp;</td>
               <td align="center" valign="top" bgcolor="#D6D6D6">
                 <form name="frm" id="frm" action="budget_summary.php" target="_blank" method="post">
		  <table width="70%" border="0">
		    <tr>
		      <td width="" align="left">Year <br />
		        <select name="syear" id="syear" style="width:90%">
		          <option selected="selected" value="">---</option>
		          <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
              </select><input type="hidden" name="mode" id="mode" value="<?php echo base64_encode($mode);?>"/></td>
	        </tr>
		    <tr>
		      <th height="29" colspan="2" align="left"><input type="submit" name="Submit" id="button" value="Display Report" class="btn"/></th>
	        </tr>
	      </table>
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form></td>
            </tr>
            <tr>
			   <td height="10" colspan="3" align="center" valign="top"></td>
		    </tr>
             <tr>
               <td align="left" valign="top" bgcolor="#E5E5E5"><strong>BUDGET PROPOSAL</strong></td>
               <td width="1%" align="center" valign="top">&nbsp;</td>
               <td align="left" valign="top" bgcolor="#E5E5E5"><strong>BUDGET PERFORMANCE WITH PERCENTAGE UTILIZATION</strong></td>
             </tr>
             <tr>
               <td align="center" valign="top" bgcolor="#D6D6D6">
				<form name="frm" id="frm2" action="budget_proposal.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left">Year <br />
                       <select name="pryear" id="pryear" style="width:90%">
                         <option selected="selected" value="">---</option>
                         <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                       </select>
                       <input type="hidden" name="mode2" id="mode2" value="<?php echo base64_encode($mode);?>"/></td>
                   </tr>
                   <tr>
                     <th height="29" colspan="2"><input type="submit" name="button" id="button2" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display3"></div>
                 <div id="display4"></div>
                 <div id="roll2"></div>
               </form></td>
               <td width="1%" align="center" valign="top">&nbsp;</td>
			   <td align="center" valign="top" bgcolor="#D6D6D6">
				<form name="frmX" id="frmX" action="budget_performance_3.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left">Year <br />
                       <select name="pyearX" id="pyearX" style="width:90%">
                         <option selected="selected" value="">---</option>
                         <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                       </select>
                       <input type="hidden" name="mode2" id="mode2" value="<?php echo base64_encode($mode);?>"/></td>
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
            <tr>
               <td align="left" valign="top" bgcolor="#E5E5E5"><strong>NUC REPORT</strong></td>
               <td width="1%" align="center" valign="top">&nbsp;</td>
               <td align="left" valign="top" bgcolor="#E5E5E5"><strong>IGR CAPITAL (SUMMARY)</strong></td>
             </tr>
             
             <tr>
               <td align="center" valign="top" bgcolor="#D6D6D6">
				<form name="frm" id="frm2" action="budget_igr_utilization.php" target="_blank" method="post">
                 <table width="70%" border="0">
                 <tr>
                   <td width="50%" align="left">From
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
                   <td width="50%" align="left">To
                     <br />
                     <select name="montht" id="montht">
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
                     <select name="sryear2" id="sryear2">
                       <option selected="selected" value="">---</option>
                       <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                       </select>
                     <input type="hidden" name="mode4" id="mode4" value="<?php echo base64_encode($mode);?>"/></td>
                 </tr>
                   <tr>
                     <th height="29" colspan="3"><input type="submit" name="button" id="button2" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display3"></div>
                 <div id="display4"></div>
                 <div id="roll2"></div>
               </form></td>
               <td width="1%" align="center" valign="top">&nbsp;</td>
			   <td align="center" valign="top" bgcolor="#D6D6D6">
				<form name="frm" id="frm2" action="budget_performance_igr-capital.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left">Year <br />
                       <select name="sryear" id="sryear" style="width:90%">
                         <option selected="selected" value="">---</option>
                         <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                       </select>
                       <input type="hidden" name="mode2" id="mode2" value="<?php echo base64_encode($mode);?>"/></td>
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
            <tr>
               <td align="left" valign="top" bgcolor="#E5E5E5"><strong> BUDGET PERFORMANCE SUMMARY BY CATEGORY</strong></td>
               <td align="center" valign="top">&nbsp;</td>
               <td align="left" valign="top" bgcolor="#E5E5E5">&nbsp;</td>
            </tr>
             
             <tr>
               <td align="center" valign="top" bgcolor="#D6D6D6"><form name="frmc" id="frm2c" action="budget_performance_c.php" target="_blank" method="post">
                 <table width="70%" border="0">
                 <tr>
		      <td width="50%" align="left">From
              <br />
              <select name="monthc" id="monthc">
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
		      <td width="50%" align="left">To
              <br />
              <select name="monthtc" id="monthtc">
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
                <select name="yearc" id="yearc">
                  <option selected="selected" value="">---</option>
                  <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                </select>
                <input type="hidden" name="mode5" id="mode5" value="<?php echo base64_encode($mode);?>"/></td>
	        </tr>
                   <tr>
                     <td width="" align="left" colspan="3">Category <br />
                       <select name="pcatc" size="1" id="pcatc" style="width:100%;">
                         <option selected="selected" value="">---</option>
                         <?php  $q =  mysqli_query($con, "SELECT DISTINCT bursary_category FROM budgettb WHERE bursary_category != '' and (bursary_category != 'Recurrent' and bursary_category != 'Departmental' and bursary_category != 'IGR Capital')");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r[0] .'">'. $r[0] .'</option>';
							  }
							  ?>
                       </select></p></td>
                   </tr>
                   <tr>
                     <th height="29" colspan="2"><input type="submit" name="button" id="button2" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
               </form></td>
               <td align="center" valign="top">&nbsp;</td>
               <td align="center" valign="top" bgcolor="#D6D6D6"></td>
             </tr>
			 <tr>
			   <td height="10" colspan="3" align="center" valign="top"></td>
		    </tr>
            <!--
			 <tr align="left">
               <td valign="top" bgcolor="#E5E5E5"><strong>IGR CAPITAL (COMPREHENSIVE)</strong></td>
               <td width="1%" valign="top">&nbsp;</td>
               <td valign="top">&nbsp;</td>
             </tr>
             <tr>
               <td align="center" valign="top" bgcolor="#D6D6D6">
				<form name="frm" id="frm2" action="igr-capital.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left">Year <br />
                       <select name="cyear" id="cyear" style="width:90%">
                         <option selected="selected" value="">---</option>
                         <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                       </select>
                       <input type="hidden" name="mode2" id="mode2" value="<?php echo base64_encode($mode);?>"/></td>
                   </tr>
                   <tr>
                     <th height="29" colspan="2"><input type="submit" name="button" id="button2" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display3"></div>
                 <div id="display4"></div>
                 <div id="roll2"></div>
               </form></td>
               <td width="1%" align="center" valign="top">&nbsp;</td>
			   <td align="center" valign="top">
				</td>
            </tr> 
			  -->
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