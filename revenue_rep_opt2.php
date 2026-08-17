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
	        <h2>Revenue Report Option</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
		  <p>
          <table width="100%">
             <tr>
                 <td width="50%" align="center" bgcolor="#E5E5E5"><strong>VIEW UPLOADED INCOME</strong></td>
                 <td width="50%" align="center" bgcolor="#D6D6D6"><strong>NUC REPORT</strong></td>
            </tr>
             <tr>
                 <td width="50%" align="center" bgcolor="#E5E5E5"><form name="frm" id="frm" action="revenue_performance3.php" target="_blank" method="post">
		  <table width="70%" border="0">
		    <tr>
		      <td width="50%" align="center">Select Date
              <br />
              <input type="date" name="from" id="from" /></td>
		      
		       <input type="hidden" name="mode" id="mode" value="<?php echo base64_encode($mode);?>"/>
	        </tr>
		    <tr>
		      <th height="68" colspan="2"><input type="submit" name="Submit" id="button" value="Display Upload Report" class="btn"/></th>
	        </tr>
	      </table>
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form></td>
                 <td width="50%" align="center" bgcolor="#D6D6D6"><form name="frm" id="frm" action="revenue_performance_nuc.php" target="_blank" method="post">
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
                     <td align="left">From: <br /><input type="date" name="from" id="from"  />
                      </td>
                   </tr>
                   <tr>
                     <td align="left">To: <br /><input type="date" name="to" id="to" />
                      </td>
                   </tr>
		    <tr>
		      <th height="68" colspan="2" align="left"><input type="submit" name="Submit" id="button" value="Display NUC Report" class="btn"/></th>
	        </tr>
	      </table>
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form></td>
            </tr>
             <tr>
               <td align="center" bgcolor="#D6D6D6"><strong>REVENUE PERFORMANCE</strong></td>
               <td align="center" bgcolor="#D6D6D6"><strong>REVENUE INCOME REPORT</strong></td>
             </tr>
             <tr>
               <td align="center" bgcolor="#D6D6D6"><form name="frm" id="frm2" action="revenue_performance.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left"><strong>Year</strong> <br />
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
                     <th height="68" colspan="2"><input type="submit" name="button" id="button2" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display3"></div>
                 <div id="display4"></div>
                 <div id="roll2"></div>
               </form></td>
               <td align="center" bgcolor="#E5E5E5"><form name="frm" id="frm3" action="revenue_performance2.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left">Year <br />
                       <select name="pyear2" id="pyear2" style="width:90%">
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
                     <td align="left">From: <br /><input type="date" name="from" id="from"  />
                      </td>
                   </tr>
                   <tr>
                     <td align="left">To: <br /><input type="date" name="to" id="to" />
                      </td>
                   </tr>
                   <tr>
                     <th colspan="2"><input type="submit" name="button2" id="button3" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display5"></div>
                 <div id="display6"></div>
                 <div id="roll3"></div>
               </form></td>
            </tr>
              <tr>
               <td align="center" bgcolor="#D6D6D6"><strong>SPREADSHEET REVENUE INCOME</strong></td>
               <td align="center" bgcolor="#D6D6D6"><strong>NUC SUMMARY REPORT</strong></td>
             </tr>
             <tr>
               <td align="center" bgcolor="#D6D6D6"><form name="frm" id="frm4" action="revenue_performance_2f.php" target="_blank" method="post">
                 <table width="70%" border="0">
                   <tr>
                     <td width="" align="left"><strong>Year</strong> <br />
                       <select name="pyear3" id="pyear3" style="width:90%">
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
                     <th height="68" colspan="2"><input type="submit" name="button" id="button2" value="Display Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display7"></div>
                 <div id="display8"></div>
                 <div id="roll2"></div>
               </form></td>
              <td align="center" bgcolor="#E5E5E5"><form name="frm" id="frm4" action="revenue_performance_nuc2.php" target="_blank" method="post">
                <table width="70%" border="0">
                   <tr>
                     <td width="" align="left">Year <br />
                       <select name="pyear2x" id="pyear2x" style="width:90%">
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
                     <td align="left">From: <br /><input type="date" name="from" id="from"  />
                      </td>
                   </tr>
                   <tr>
                     <td align="left">To: <br /><input type="date" name="to" id="to" />
                      </td>
                   </tr>
                   <tr>
                     <th colspan="2"><input type="submit" name="button2" id="button3" value="Display NUC Report" class="btn"/></th>
                   </tr>
                 </table>
                 <div id="display9"></div>
                 <div id="display10"></div>
                 <div id="roll3"></div>
               </form></td>
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