<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>BUDGET PERFORMANCE</title>
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
$year=$_REQUEST['pyear2']; 
$attachment=$_REQUEST['attach'];
$budget_cat=$_REQUEST['cat'];
	
	
//exit;
require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'>".strtoupper($val[0])."<br/>BURSARY DEPARTMENT<br/>".strtoupper($attachment)."<br>BUDGET PERFORMANCE FOR {$year}</p></b><hr><p>";
  ?>
	
	
	
                                                                                                                                                                                                                                                                              <center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
 <?php
	$month_code = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 	$month_name = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');
?>
	  <tr bgcolor='#ccc'>
    <th>S/N</th>
    <th align="left"><?php echo strtoupper($budget_cat); ?></th>
    <th>AMOUNT<br>&#8358;</th>
	</tr>

 <?php
	$monthly_var=array(); 

		$sql="select sum(amount) as annual_budget from budgettb where budget_year = '".$year."'";
		$res_p = @mysqli_query($con, $sql);
		$rs_p=@mysqli_fetch_array($res_p);
		$annual_budget_for_the_year=$rs_p[0];
		$totalBudgetedAmount=0;
		$totalAmountSpent=0;
		$totalVariance=0;
		
	if($budget_cat != ''){
		$sn=0;	
		
		$BudgetedAmount=0;
		$amountBudgetByCat=0;
		
		$amountSpent=0;
		$amountSpentByCat=0;
		
		$variance=0;
		$varianceByCat=0;	
		$sq="select distinct folio_code, budget_title from budgettb where budget_year = '$year' and bursary_sub_category = '".$budget_cat."' order by budget_title";
		$sql_folio=@mysqli_query($con, $sq);
		while($rst_folio=@mysqli_fetch_array($sql_folio)){
			++$sn; 
			$folio=$rst_folio['folio_code'];
			$budget_title=($rst_folio['budget_title']);
			if($yq[1]=="IGR Capital") $folio_name=$rst_folio['budget_title'];
			else
			(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
			
			$folio_budget=get_folio_budget($folio, $year);//
			$BudgetedAmount=get_folio_budget($folio, $year);
			echo "<tr>";
			echo "<td> $sn </td>
			<td>$budget_title</td>";
			$sql="select sum(amount) as amount_spent from budget_votebooktb where operation_year = ".$year." and budget_folio_code = '".$folio."'";
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
			
			echo "<td><div align='right'>".@number_format($amountSpent, 2)."</div></td> </tr>";
		}
		echo "<tr bgcolor='#ccc'>";
		echo "<td>TOTAL</td>
		<th>$budget_cat</th>";
		echo "<td><div align='right'>".@number_format($amountSpentByCat,2)."</div></td> </tr>";
	}
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>