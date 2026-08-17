<?php $budget_cat="IGR CAPITAL"; //$_POST['pcatc']; ?>
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
$year=$_REQUEST['sryear']; 
$month=$_REQUEST['pmonth2'];
$montht=$_REQUEST['pmontht2'];
//$budget_cat[] =  mysqli_real_escape_string($con, $_POST['pcat']);

require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'>".strtoupper($val[0])."<br/>BURSARY DEPARTMENT<br/>BUDGET, EXPENDITURE AND STORES UNIT (BEST)<br>"."$year ".strtoupper($budget_cat)." BUDGET AND PERFORMANCE</p></b><hr><p>";
  ?>
	
<center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
	  <tr>
    <th>S/N</th>
    <th>DESCRIPTION</th>
    <th>BUDGET (&#8358;)</th>
    <th>INCURRED TO DATE (&#8358;)</th>
    <th>AVAILABLE BALANCE (&#8358;)</th>
	</tr>

 <?php
	$monthly_var=array(); 
	$sql="SELECT DISTINCT budget_title FROM budgettb WHERE budget_year='".$year."' and bursary_category = '{$budget_cat}' and budget_title != ''";
	  $bcat= mysqli_query($con, $sql);
		
		$totalBudgetedAmount=0;
		$totalAmountSpent=0;
		$totalBalance=0;
		$n=1;
	while($r =  mysqli_fetch_array($bcat, 3 )){
		$sqll= mysqli_query($con, "select * from budgettb where budget_title='".$r[0]."'");
		$catCNT=FALSE;
		if( mysqli_num_rows($sqll) > 1){
			echo "<tr bgcolor='#999'>";
			echo "<!--th> </th-->
			<th colspan='5' align='left'>".$n++.". ".$r[0]."</th>";
			echo '</thead><tbody>';
			echo "</tr>";
			$catCNT=TRUE;
		}
		$sn=0;
		
		$BudgetedAmount=0;
		$amountBudgetByCat=0;
		
		$amountSpent=0;
		$amountSpentByCat=0;
		
		$balance=0;
		$varianceByCat=0;	
		$sq="select distinct folio_code, budget_title from budgettb where budget_year = '$year' AND budget_title = '".$r[0]."' AND bursary_category = '{$budget_cat}' ORDER BY folio_code";
		$sql_folio=@mysqli_query($con, $sq);
		while($rst_folio=@mysqli_fetch_array($sql_folio)){
			++$sn; 
			$folio=$rst_folio['folio_code'];
			//$folio_name=get_folio_name($folio);
			/*(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
			$folio_budget=get_folio_budget($folio, $year);//*/
			$BudgetedAmount=get_folio_budget($folio, $year);
			//$folio_name=$rst_folio['budget_title'];
			(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) :$folio_name=$rst_folio['budget_title'];
			echo "<tr>";
			if($catCNT) {
				echo "<td align='right'>".strtolower(numberToRoman($sn))."</td>";
				echo "<td>$folio_name</td>";
			}
			else {
				echo "<th align='right'>".$n++.". </th>";
				echo "<th align='left'>$folio_name</th>";
			}
			
			
			echo "<td><div align='right'>".@number_format($BudgetedAmount, 2)."</div></td>";
			$sql="select sum(amount) as amount_spent from budget_votebooktb where operation_year = ".$year." and budget_folio_code = '".$folio."'";
			$res_p = @mysqli_query($con, $sql);// and status = 'PAID'");
			$rs_p=@mysqli_fetch_array($res_p);
			$amountSpent=@$rs_p[0];
			$balance=$BudgetedAmount-$amountSpent;
			
			$amountBudgetByCat+=$BudgetedAmount;
			$amountSpentByCat+=$amountSpent;
			$varianceByCat+=$balance;
			
			$totalBudgetedAmount+=$BudgetedAmount;
			$totalAmountSpent+=$amountSpent;
			$totalBalance+=$balance;
			
			echo "<td><div align='right'>".@number_format($amountSpent, 2)."</div></td>
			<td><div align='right'><strong>".@number_format($balance,2)."</div></strong></td> </tr>";
		}
		if($sn > 1){
			echo "<strong><tr bgcolor='#ccc'>";
			echo "<td></td>
			<th>SUB TOTAL</th>
			<td><div align='right'>".@number_format($amountBudgetByCat, 2)."</div></td>";
			echo "<td><div align='right'>".@number_format($amountSpentByCat,2)."</div></td>
			<td><div align='right'><strong>".@number_format($varianceByCat,2)."</div></strong></td> </tr></strong>";
		}
	}
		  echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
				<th>GRAND TOTAL</th>
				<td><div align='right'>".@number_format($totalBudgetedAmount, 2)."</div></td>";
				echo "<td><div align='right'>".@number_format($totalAmountSpent, 2)."</div></td>";
				echo "<td><div align='right'><strong>".@number_format($totalBalance, 2)."</div></strong></td> </tr>";
				
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>