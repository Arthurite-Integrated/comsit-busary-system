<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Vote Book</title>
</head>

<body>
<?php @require_once "connect.php";
@require_once "required_jQuery_files.php";
 ?>
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
		$.post(url,{contentvar:cv, budget_cat:v, budget_dept:a, budget_year:b, code_cat:c},function(data){
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
</script>
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="10%" height="26" align="center" bgcolor="#D6D6D6">&nbsp;</td>
    <td width="80%" align="center" bgcolor="#D6D6D6" style="font-size:22px; font-weight:bold">VIEW  VOTE BOOK</td>
    <td width="10%" align="center" bgcolor="#D6D6D6">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="3" align="center">				 <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius: 0 0 5px 5px; border-radius: 0 0 5px 5px;
  -webkit-border-radius: 0 0 5px 5px;'>Budget Details</legend>
  <table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>
    <tr><td width='20%' height='20' align='left' valign='top' colspan='2'>
	<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-top:1px solid green; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:#003366; font-size:10px; text-align:right; -moz-border-radius: 3px 3px 3px 3px; border-radius: 3px 3px 3px 3px;
  -webkit-border-radius: 3px 3px 3px 3px;'>Budget Criteria</legend>
	<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" style="font-size:10px"><tr>
  <td width='20%' height='20' align='left' valign='middle'><strong>Budget Year:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'>
  <select style='width:200px' name='budget_year' id='budget_year' onchange="if($('#budget_cat option:selected').val() == 'Recurrent Budget'){ $('tr#bgd_td').show(); } else { $('tr#bgd_td').hide(); } swapcontent('read_budget_votebook', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), ''); ">
			    <option selected='selected' value=''>Select...</option>
				<?php  $dSess = @date('Y');
				for ($t= 2018; $t<=$dSess; $t++)
				{
					$tSession = "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					echo "<option value='$tSession'>$tSession</option>";
				}
				?>
		        </select></td>
	</tr>	
    <tr>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Budget Category:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'>
  <select style='width:200px' name='budget_cat' id='budget_cat' onchange="
  if($('#budget_cat option:selected').val() == 'Recurrent Budget'){ 
  $('tr#bgd_td').show(); $('tr#cdb_td').hide(); 
  } else { 
  $('tr#bgd_td').hide(); $('tr#cdb_td').hide(); 
  swapcontent('read_budget_votebook', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), $('#code_cat').val()); 
  } ">
			         <option selected='selected'>Select...</option>
			      <option value='Administrative and General Expenditure'>Administrative and General Expenditure</option>
			      <option value='General Academic Expenses'>General Academic Expenses</option>
			      <option value='Students Services Cost (Over Head)'>Students Services Cost (Over Head)</option>
                  <option value='General Maintenance Cost'>General Maintenance Cost</option>
                  <option value='Finance Management Cost (Over Head)'>Finance Management Cost (Over Head)</option>
                  <option value='Other Academic Units'>Other Academic Units</option>
			         <option value='Recurrent Budget'>Administrative Expenses</option>
			         <option value='Capital Budget'>Capital Budget</option>
			         <option value='Others'>Others</option>
		           </select> </td>
  </tr>
  <tr id='cdb_td'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Code Category:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select name='code_cat' id='code_cat' style='width:200px;' onchange="swapcontent('read_budget_votebook', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), $('#code_cat').val());">
			         <option selected='selected'>Select...</option>
					 <?php $q =  mysqli_query($con, "select distinct f.category, c.folio_category from foliotb f inner join folio_categorytb c on f.category=c.id where c.status='Active' order by folio_category");
                  while($r= mysqli_fetch_array($q, 3 )){
                  	echo "<option value='". $r['category'] ."'>". $r['folio_category'] ."</option>"; 
				  } ?>
		           </select> </td> 
  </tr>
  <tr id='bgd_td'>
  <td width='20%' height='20' align='left' valign='middle'><strong>Department/Unit:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'>
  <select style='width:200px' name='budget_dept' id='budget_dept' onchange="if($('#budget_cat option:selected').val() == 'Recurrent Budget'){ $('tr#bgd_td').show(); } else { $('tr#bgd_td').hide(); } swapcontent('read_budget_votebook', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), ''); ">
			         <option value='' selected='selected'>Select...</option>
					<?php $q =  mysqli_query($con, "select * from departmenttb order by dept_name");
                  while($r= mysqli_fetch_array($q, 3 )){
                  	echo "<option value='". $r['dept_code'] ."'>". $r['dept_name'] ."</option>"; 
				  } ?>
		           </select> </td></tr></table></fieldset>
	</td>
	<td width='40%' align='left' valign='top'><div id='folio_summary'></div></td>
  </tr>
			 <tr>
			   <td height='30' colspan='3' align='center' valign='middle'>
			   <table width='100%' border='0' cellspacing='0' cellpadding='0'>
			     <tr>
			       <td height='20' align='left' valign='middle'>&nbsp;</td>
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
</body>
</html>