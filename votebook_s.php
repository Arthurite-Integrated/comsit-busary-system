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
<script language="javascript">
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_m.php";
	var str;
	
   if(cv=="read_budget_votebook" ) // read budget by category and create droppable table cells
	{
		$.post(url,{contentvar:cv, budget_cat:v, budget_dept:a, budget_year:b, code_cat:c, r_val:d},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}//end of read_budget_votebook

  	if(cv=="folio_summary" ) // reverse budget commit
		{
		$.post(url,{contentvar:cv, folio_code:v, budget_cat:a, amount:b, budget_year:c, budget_type:d, budget_dept:e},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}//end of read_budget

}

function checkCat(){
if($('#budget_cat option:selected').val() != 'Departmental' && $('#budget_cat option:selected').val() != ''){
   	//$('tr#cdb_td').hide(); 
    $('tr#bgd_td').hide(); 
  } else { 
  	//$('tr#cdb_td').show(); 
    $('tr#bgd_td').show(); 
  }
}
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
                                
<div class="content" style="width:100%">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
		  <p>
          <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="10%" height="26" align="center" bgcolor="#D6D6D6">&nbsp;</td>
    <td width="80%" align="center" bgcolor="#D6D6D6" style="font-size:22px; font-weight:bold">VOTEBOOK REPORT FILTER</td>
    <td width="10%" align="center" bgcolor="#D6D6D6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">				 <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius: 0 0 5px 5px; border-radius: 0 0 5px 5px;
  -webkit-border-radius: 0 0 5px 5px;'>Budget Details</legend>
  <table width='100%' border='0' align='center' cellpadding='3' cellspacing='0'>
    <tr><td width='20%' height='20' align='left' valign='top' colspan='2'>
	<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-top:1px solid green; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:#003366; font-size:10px; text-align:right; -moz-border-radius: 3px 3px 3px 3px; border-radius: 3px 3px 3px 3px;
  -webkit-border-radius: 3px 3px 3px 3px;'>Budget Criteria</legend>
	<?php $tb .= '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" style="font-size:10px"><tr>';	
  $tb .= "<td width='20%' height='20' align='left' valign='middle'><strong>Budget Year:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_year' id='budget_year' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); } else { $('tr#bgd_td').hide(); } swapcontent('read_budget_votebook', $('#budget_cat').val(), $('#deptcode').val(), $('#budget_year').val(), '".$pvno."');\">
			    <option selected='selected' value=''>Select...</option>";
				  $dSess = @date('Y');
				for ($t= $dSess; $t>=2017; $t--)
				{
					$tSession = "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					$tb .= "<option value='$tSession'>$tSession</option>";
				}
				
		        $tb .= "</select></td>
	</tr>
  <tr>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Budget Category:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_cat' id='budget_cat' style='width:120px;' onchange=\"if($('#budget_cat option:selected').val() == 'Departmental'){ $('tr#cdb_td').hide(); $('tr#dep_tr').show(); } 
  else { $('tr#cdb_td').show(); $('tr#dep_tr').hide(); }
 if($('#budget_cat option:selected').val() == 'Refund'){ $('tr#cdb_td').hide(); $('tr#dep_tr').hide(); }  
 
 swapcontent('read_budget_votebook', $('#budget_cat').val(), $('#deptcode').val(), $('#budget_year').val(), '".$pvno."'); \">
			         <option selected='selected' value=''>Select...</option>";
                  $q =  mysqli_query($con, "select distinct bursary_category from budgettb where bursary_category!=''");
                  while($r= mysqli_fetch_array($q, 3 )){
                  	$tb .= "<option value='". $r['bursary_category'] ."'>". $r['bursary_category'] ."</option>"; 
				  } 
				  
		           $tb .= "</select></td>
  </tr>
	
	<tr id='cdb_td'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Budget Sub-Category:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select name='code_cat' id='code_cat' style='width:200px;' onchange=\"if($(this).val() != '') swapcontent('read_budget_votebook', $('#budget_cat').val(), $('#deptcode').val(), $('#budget_year').val(), $('#code_cat').val(), '<?php echo $r_val; ?>');\">
			         <option selected='selected' value=''>Select...</option>";
					 $q =  mysqli_query($con, "select distinct bursary_sub_category from budgettb where bursary_sub_category!='' order by bursary_sub_category");
                  while($r= mysqli_fetch_array($q, 3 )){
                  	$tb .= "<option value='". $r['bursary_sub_category'] ."'>". $r['bursary_sub_category'] ."</option>"; 
				  } 
		           $tb .= "</select> </td> 
  </tr>
  <tr id='dep_tr'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Department:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='deptcode' id='deptcode' style='width:120px;' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); $('tr#cdb_td').hide(); } else { $('tr#bgd_td').hide(); $('tr#cdb_td').hide(); swapcontent('read_budget_votebook', $('#budget_cat').val(), $('#deptcode').val(), $('#budget_year').val(), '".$pvno."'); } /**/\">
			         <option selected='selected' value=''>Select...</option>";
				  $res_c=@mysqli_query($con, "select * from account_departments order by department_category");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['department_code']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['department_name']; //$dept_name=@$rs_c['dept_name'];
                              $tb .= "<option value='$dept_code'>$dept_code - $dept_name</option>";
                           }
				
		        $tb .= "</select></td></tr></table>";
				   
				   echo $tb;
				    ?></fieldset>
	</td>
	<td width='40%' align='left' valign='top'>&nbsp;</td>
  </tr>
  
			 <tr>
			   <td height='30' colspan='3' align='center' valign='middle'>
			   <table width='100%' border='0' cellspacing='0' cellpadding='0'>
			     <tr>
			       <td height='20' align='left' valign='middle'><div id='folio_summary'></div></td>
		         </tr>
			     <tr>
			       <td align='left' valign='top' class='right'>
			         <div id='read_budget_votebook' style='width:100%'></div>
		           </td>
		         </tr>
			     <tr>
			       <td height='20' align='left' valign='middle'><input name='wvouchercode' id='wvouchercode' type='hidden' value='' />
                     <input name='wbudgetcode' id='wbudgetcode' type='hidden' value='' />
                     <input name='wvoucheramount' id='wvoucheramount' type='hidden' value='' />
                   <input name='pvno' id='pvno' type='hidden' value='$pvno' /></td>
		         </tr>
		       </table>
               </td></tr>
			 
					 </table>
		 </fieldset>
</td>
  </tr>
  <tr>
    <td width="10%" align="center">&nbsp;</td>
    <td width="80%" align="center">&nbsp;</td>
    <td width="10%" align="center">&nbsp;</td>
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