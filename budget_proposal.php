<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>COMPREHENSIVE BUDGET PROPOSAL</title>
<link rel="shortcut icon" href="images/logox.png"> <!-- put the image/logo on the browser tab -->
<style>
body {
font : "Times New Roman", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
</style>

      
</head>

<body>
<?php
$mode=base64_decode($_REQUEST['mode']);
$year=$_REQUEST['pryear'];
$yearNext = $year+1;
require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
	
//echo "$category $level";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/logo.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'><u>".strtoupper($val[0])."<br/>BURSARY DEPARTMENT<br/>"."$year BUDGET PROPOSAL</u></p></b><hr><p>";
  ?>
<center>
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th>S/N</th>
    <th>CODE DESCRIPTION</th>
    <th><?php echo $year; ?> FINANCIAL YEAR  BUDGET PROVISION<br>&#8358;</th>
    <th>JAN - DEC. <?php echo $year; ?> TOTAL<br>&#8358;</th>
    <th><?php echo $yearNext; ?> PROPOSED BUDGET <br>&#8358;</th>
	</tr>

 <?php
 	$bcat= mysqli_query($con, "SELECT DISTINCT bursary_sub_category FROM budgettb WHERE budget_year='".$year."' and bursary_sub_category != '' and (bursary_category = 'Recurrent' OR bursary_category = 'Departmental')"); 
	$total_b = 0; 		$total_s = 0;		$total_v = 0;$sn=0;	
	while($yq =  mysqli_fetch_array($bcat, 3 )){
		if($bursary->get_any_value("fund_name", "account_funds", "fund_code", $yq[0]) != '') $yq_n=$bursary->get_any_value("fund_name", "account_funds", "fund_code", $yq[0]);
		else $yq_n=$yq[0];
		
	  echo "<tr bgcolor='#999'>";
	  echo "<th colspan='5' align='left'>".strtoupper($yq_n)."</th></tr>";
	 
	 $sub_total_b = 0; 	$sub_total_s = 0;	$sub_total_v = 0;
	 $sql_folio=@mysqli_query($con, "select distinct folio_code, budget_title from budgettb where budget_year = $year and bursary_sub_category = '".$yq[0]."' order by budget_title");
	   while($rst_folio=@mysqli_fetch_array($sql_folio))
	   {
	  ++$sn; 
	  $folio=$rst_folio['folio_code'];
	  $budget_title=($rst_folio['budget_title']);
	  (get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
	  $folio_budget=get_folio_budget($folio, $year);
	  $sub_total_b += $folio_budget;			$total_b += $sub_total_b;
	  
	  ////$folio_budget_expences=get_folio_budget_expences($folio, $month ,$year);
	  $sql="select sum(amount) as amount_spent from budget_votebooktb where operation_year = ".$year." and budget_folio_code = '".$folio."'";
		$res_p = @mysqli_query($con, $sql);
		$rs_p=@mysqli_fetch_array($res_p);
		$folio_budget_expences=@$rs_p[0];
		
	  $sub_total_s += $folio_budget_expences;	$total_s += $sub_total_s;
	  $folio_budget_bal= get_budget_balance($folio,$month,$year);
	  $sub_total_v += $folio_budget_bal;		$total_v += $sub_total_v;
		  echo "<tr>";
		  echo "<td> $sn </td>
				<td>$budget_title</td>
				<td><div align='right'>".@number_format($folio_budget, 2)."</div></td>
				<td><div align='right'>".@number_format($folio_budget_expences,2)."</div></td>
				<td><div align='right'></td> </tr>"; 
	  }
		 /* echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
				<th colspan='2'>SUB TOTAL</th>
				<td><div align='right'>".@number_format($sub_total_b,2)."</div></td>
				<td><div align='right'>".@number_format($sub_total_s,2)."</div></td>
				<td><div align='right'><strong>".@number_format($sub_total_v,2)."</div></strong></td> </tr>";*/
	}
		  echo "<tr bgcolor='#ccc'>";
		  echo "<td></td><th>GRAND TOTAL</th>
				<td><div align='right'><strong>".@number_format($total_b,2)."</strong></div></td>
				<td><div align='right'><strong>".@number_format($total_s,2)."</strong></div></td>
				<td><div align='right'></td> </tr>";
  ?>
</table>   



</center>
<p>&nbsp;</p>
</body>
</html>