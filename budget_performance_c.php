<?php $budget_cat=$_POST['pcatc']; ?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BUDGET PERFORMANCE: <?php echo strtoupper($val[0]); ?></title>
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
$year=$_REQUEST['yearc']; $month=$_REQUEST['monthc'];
$montht=$_REQUEST['monthtc'];
require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'>".strtoupper($val[0])."<br/>BURSARY DEPARTMENT<br/>".strtoupper($budget_cat)." BUDGET PERFORMANCE FOR ".strtoupper(get_month_name($month)). "&nbsp;TO ".strtoupper(get_month_name($montht)). ", {$year}</p></b><hr><p>";
  ?>
<center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
 <?php
	$month_code = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 	$month_name = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');
?>
	  <tr>
    <th>S/N</th>
    <th>DESCRIPTION</th>
    <th>BUDGET (&#8358;)</th>
	<th>INCURRED TO DATE (&#8358;)</th>
    <th>AVAILABLE BALANCE (&#8358;)</th>
	</tr>

 <?php
	$monthly_var=array(); 
	$s="SELECT DISTINCT bursary_sub_category FROM budgettb WHERE budget_year='".$year."' and bursary_sub_category != '' and bursary_category = '{$budget_cat}'";
	  $bcat= mysqli_query($con, $s);// ORDER BY bursary_sub_category ASC");
		
		$sql="select sum(amount) as annual_budget from budgettb where budget_year = '".$year."'";
		$res_p = @mysqli_query($con, $sql);
		$rs_p=@mysqli_fetch_array($res_p);
		$annual_budget_for_the_year=$rs_p[0];
		$totalBudgetedAmount=0;
		$totalAmountSpent=0;
		$totalVariance=0;
		
	/*while($yq =  mysqli_fetch_array($bcat, 3 ))*/{
		/*if($bursary->get_any_value("fund_name", "account_funds", "fund_code", $yq[0]) != '') $yq_n=$bursary->get_any_value("fund_name", "account_funds", "fund_code", $yq[0]);
		else $yq_n=$yq[0];
		echo "<tr bgcolor='#999'>";
		echo "<!--th> </th-->
		<th colspan='5' align='left'>".strtoupper($yq_n)."</th>";
		echo '</thead><tbody>';
		echo "</tr>";*/
		$sn=0;	
		
		$BudgetedAmount=0;
		$amountBudgetByCat=0;
		
		$amountSpent=0;
		$amountSpentByCat=0;
		
		$variance=0;
		$varianceByCat=0;	
		
		$sql_folio=@mysqli_query($con, "select distinct folio_code from budgettb where budget_year = $year and bursary_category = '{$budget_cat}'");// order by budget_title ASC");
		while($rst_folio=@mysqli_fetch_array($sql_folio)){
			++$sn; 
			$folio=$rst_folio['folio_code'];
			//$budget_title=(($rst_folio['budget_title']));
			(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
			//($rst_folio['budget_title']!='') ? $budget_title=(($rst_folio['budget_title'])) : $budget_title=get_folio_name($folio);
			$budget_title=$folio_name;
			$folio_budget=get_folio_budget($folio, $year);//
			$BudgetedAmount=get_folio_budget($folio, $year);
			echo "<tr>";
			echo "<td> $sn </td>
			<td>$budget_title-$folio</td>
			<!--td nowrap height='25'>$folio</td-->
			<td><div align='right'>".@number_format($BudgetedAmount,2)."</div></td>";
			$sql="select sum(amount) as amount_spent from budget_votebooktb where operation_year = ".$year." and budget_folio_code = '".$folio."' and (operation_month between {$month} and {$montht})";
			$res_p = @mysqli_query($con, $sql);// and status = 'PAID'");
			$rs_p=@mysqli_fetch_array($res_p);
			$amountSpent=@$rs_p[0];
			$variance=$BudgetedAmount-$amountSpent;
			
			$amountBudgetByCat+=$BudgetedAmount;
			$amountSpentByCat+=$amountSpent;
			$varianceByCat+=$variance;
			
			$totalBudgetedAmount+=$BudgetedAmount;
			$totalAmountSpent+=$amountSpent;
			$totalVariance+=$variance;
			
			echo "<td><div align='right'>".@number_format($amountSpent, 2)."</div></td>
			<td><div align='right'><strong>".@number_format($variance,2)."</div></strong></td> </tr>";
		}
		echo "<tr bgcolor='#ccc'>";
		echo "<td></td>
		<th>SUB TOTAL</th>
		<td><div align='right'>".@number_format($amountBudgetByCat, 2)."</div></td>";
		echo "<td><div align='right'>".@number_format($amountSpentByCat,2)."</div></td>
		<td><div align='right'><strong>".@number_format($varianceByCat,2)."</div></strong></td> </tr>";
	}
		  echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
				<th>GRAND TOTAL</th>
				<td><div align='right'>".@number_format($totalBudgetedAmount, 2)."</div></td>";
				echo "<td><div align='right'>".@number_format($totalAmountSpent, 2)."</div></td>";
				echo "<td><div align='right'><strong>".@number_format($totalVariance, 2)."</div></strong></td> </tr>";
				
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>