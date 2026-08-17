<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>COMPREHENSIVE BUDGET PERFORMANCE</title>
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
$year=$_REQUEST['pyear'];
require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'><u>".strtoupper($val[0])."<br/>"."COMPREHENSIVE BUDGET PERFORMANCE AS AT:   ".get_month_name($month). "&nbsp; $year" ."</u></p></b><hr><p>";
  ?>
<center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
 <?php
	$month_code = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 	$month_name = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');
?>
	  <tr>
    <th>S/N</th>
    <th>CODE DESCRIPTION</th>
    <th>ACCOUNT CODE</th>
    <th>BUDGET PROVISION<br>&#8358;</th>
	  <?php foreach($month_name as $mn) echo "<th>$mn $year<br>&#8358;</th>"; ?>
    <th>TOTAL SPENT<br>&#8358;</th>
    <th>VARIANCE<br>&#8358;</th>
	</tr>

 <?php
	$monthly_var=array(); 

	  $bcat= mysqli_query($con, "SELECT DISTINCT bursary_sub_category FROM budgettb WHERE budget_year='".$year."' and bursary_sub_category != '' and (bursary_category = 'Recurrent' OR bursary_category = 'Departmental')");
	$total_b = 0; 		$total_s = 0;		$total_v = 0;
	$monthly_grand_total = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		
		$sql="select sum(amount) as annual_budget from budgettb where budget_year = '".$year."'";
		$res_p = @mysqli_query($con, $sql);
		$rs_p=@mysqli_fetch_array($res_p);
		$annual_budget_for_the_year=$rs_p[0];
		
	while($yq =  mysqli_fetch_array($bcat, 3 )){
		if($bursary->get_any_value("fund_name", "account_funds", "fund_code", $yq[0]) != '') $yq_n=$bursary->get_any_value("fund_name", "account_funds", "fund_code", $yq[0]);
		else $yq_n=$yq[0];
		echo "<tr bgcolor='#999'>";
		echo "<th> </th>
		<th colspan='17' align='left'>".strtoupper($yq_n)."</th>";
		echo '</thead><tbody>';
		echo "</tr>";
		$sn=0;	
		$sub_total_b = 0; 	$sub_total_s = 0;	$sub_total_v = 0;	$monthly_spent=array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		
		$sql_folio=@mysqli_query($con, "select distinct folio_code, budget_title from budgettb where budget_year = $year and bursary_sub_category = '".$yq[0]."' order by budget_title");
		while($rst_folio=@mysqli_fetch_array($sql_folio)){
			++$sn; 
			$folio=$rst_folio['folio_code'];
			$budget_title=($rst_folio['budget_title']);
			(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
			$folio_budget=get_folio_budget($folio, $year);
			$sub_total_b += $folio_budget;			
			$total_b += $sub_total_b;
			
			////$folio_budget_expences=get_folio_budget_expences($folio, $month ,$year);
			$sub_total_s += $folio_budget_expences;	
			$total_s += $sub_total_s;
			$folio_budget_bal= get_budget_balance($folio,$month, $year);
			$sub_total_v += $folio_budget_bal;		
			$total_v += $sub_total_v;
			$annual_spent = 0;
			echo "<tr>";
			echo "<td> $sn </td>
			<td>$budget_title</td>
			<td nowrap height='25'>$folio</td>
			<td><div align='right'>".@number_format($folio_budget,2)."</div></td>";
			////$monthly_spent=array();
			$i=0;
			
			foreach($month_code as $mc){
				$sql="select sum(amount) as amount_spent from budget_votebooktb where operation_year = ".$year." and budget_folio_code = '".$folio."' and operation_month=".$mc."";
				$res_p = @mysqli_query($con, $sql);// and status = 'PAID'");
				$rs_p=@mysqli_fetch_array($res_p);
				$sub_s=@$rs_p[0];
				$annual_spent += $sub_s;
				$monthly_spent[$i] += $sub_s;
				$sub_total_s += $sub_s;	
				
				if($sub_s == 0) $sub_s='-'; else $sub_s=number_format($sub_s, 2);
				echo "<td align='right'>".$sub_s."</td>";
				$i++;
			}
			$folio_budget_bal = $folio_budget - $annual_spent;
			echo "<td><div align='right'>".@number_format($annual_spent, 2)."</div></td>
			<td><div align='right'><strong>".@number_format($folio_budget_bal,2)."</div></strong></td> </tr>";
		}
		echo "<tr bgcolor='#ccc'>";
		echo "<td></td>
		<th colspan='2'>SUB TOTAL</th>
		<td><div align='right'>".@number_format($sub_total_b, 2)."</div></td>";
		for($i=0; $i<12; $i++){
			$monthly_grand_total[$i] += $monthly_spent[$i];
			echo "<td>".number_format($monthly_spent[$i], 2)."</th>";
		}
		echo "<td><div align='right'>".@number_format($sub_total_s,2)."</div></td>
		<td><div align='right'><strong>".@number_format($sub_total_v,2)."</div></strong></td> </tr>";
	}
		  echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
				<th colspan='2'>GRAND TOTAL</th>
				<td><div align='right'>".@number_format($annual_budget_for_the_year, 2)."</div></td>";
				$total_spent = 0;
				for($i=0; $i<12; $i++) {
					echo "<td>".number_format($monthly_grand_total[$i], 2)."</th>"; 
					$total_spent += $monthly_grand_total[$i];
				}
				echo "<td><div align='right'>".@number_format($total_spent, 2)."</div></td>";
				$variant = $annual_budget_for_the_year - $total_spent;
				echo "<td><div align='right'><strong>".@number_format($variant, 2)."</div></strong></td> </tr>";
				
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>