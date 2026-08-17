<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>IGR CAPITAL - EXTENDED</title>
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
$year=$_REQUEST['cyear'];
require_once "function_c.php";
//echo "$category $level";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'><u>".strtoupper($val[0])."<br/>REPORT OF CAPITAL PROJECTS PAID FROM INTERNALLY GENERATED REVENUE(IGR)<br>FOR ".$year." FINANCIAL YEAR</u></p></b><hr><p>";
  ?>
                                                                                                                                                                                                                                                                              <center>
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">

 <?php
 	$month_code = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 	$month_name = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');
	$monthly_var=array();
	$tbudget = 0; $tmspent = array(); $tspent = 0; $tvariance = 0;
 	//$bcat= mysqli_query($con, "SELECT DISTINCT bursary_sub_category FROM budgettb WHERE budget_year='".$year."' and bursary_sucategory != ''");
	////while($yq =  mysqli_fetch_array($bcat, 3 )){
		$category = "Capital";////$yq[0];
		echo "<tbody><tr bgcolor='#999'>
		<th>S/N</th>
		<th>CAPITAL EXPENDITURE HEADS</th>";
		foreach($month_name as $mn) echo "<th>$mn $year<br>&#8358;</th>";
		echo '<th>TOTAL<br>&#8358;</th><tr></thead><tbody>';
	  
	 $monthly_spent=array();	$nextTableFolio=array();
	 $total_b = 0; 		$total_s = 0;		$total_v = 0;
	 $sn=0;	$nextTable="<th>CAPITAL EXPENDITURE HEADS</th>";
	 $sql_folio=@mysqli_query($con, "select distinct folio_code from budgettb where budget_year = $year and bursary_category = '".$category."' order by folio_code");
	   while($rst_folio=@mysqli_fetch_array($sql_folio))
	   {
		   $sub_total_b = 0; 	$sub_total_s = 0;	$sub_total_v = 0;
			$folio=$rst_folio['folio_code'];
			$folio_name=get_folio_name($folio);
			(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
			++$sn; 
			echo "<tr>";
			echo "<td>{$sn}</td>
			<td>{$folio_name}</td>";
		   
		  	//$nextTable[$sn] .= "<th>{$folio_name}</th>";
		    $nextTableFolio[$sn] = $folio;
		   
			$i=0;	
			foreach($month_code as $mc){
				$res_p = @mysqli_query($con, "select sum(amount) as amount_spent from budget_votebooktb where operation_year = ".$year." and budget_folio_code = '".$folio."' and operation_month='".$mc."'");// and status = 'PAID'");
				$rs_p=@mysqli_fetch_array($res_p);
				$sub_s=@$rs_p[0];
				if($sub_s == 0) $sub_s='-'; else $sub_s=number_format($sub_s, 2);
				echo "<td align='right'>".$sub_s."</td>";
				$sub_total_s += $sub_s;		
				$monthly_spent[$i] += $sub_s;
				$i++;
			}
			////$sub_total_v = $sub_total_b - $sub_total_s;
			$total_v += $sub_total_v;
			$total_s += $sub_total_s;
			echo "<td bgcolor='#CCCCCC'><div align='right'><strong>".@number_format($sub_total_s,2)."</div></strong></td>
			</tr>";
	}
	echo "<tr bgcolor='#ccc'>";
	echo "<td></td>
	<th>SUB TOTAL</th>";
	$j=0;
	foreach($monthly_spent as $ms) {
		$tmspent[$j] += $ms;   $j++;
		echo "<th><div align='right'>".@number_format($ms,2)."</div></th>";
	}
	echo "<th bgcolor='#CCCCCC'><div align='right'>".@number_format($total_s,2)."</div></th>
	</tr>";
	$tbudget += $total_b; $tspent += $total_s;	$tvariance += $total_v;
////}
		  echo "<!--tr bgcolor='#999'>";
		  echo "<td></td>
				<th>GRAND TOTAL</th>
				<th><div align='right'>".@number_format($tbudget,2)."</div></th>";
		foreach($tmspent as $ts) echo "<td><div align='right'>".@number_format($ts,2)."</div></th>";
				echo "<th bgcolor='#999'><div align='right'>".@number_format($tspent,2)."</div></th>
				</tr-->";
  ?>
	  </tbody></table> 
	
	
<table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
	<tr><td colspan="<?php echo $sn+1; ?>"></td></tr>
<?php 
	$monthlyTotal=array();
	foreach($month_name as $mn){  ?>	
	<tr><td colspan="<?php echo $sn+1; ?>"></td></tr>
	<tr><?php for($i=0; $i<=$sn; $i++) if($i==0) echo "<th></th>"; else echo "<th>".$i."</th>"; ?></tr>
	<tr><?php for($i=0; $i<=$sn; $i++) {
		if($i==0) echo "<th>MONTH</th>"; else echo "<th>".$mn."</th>";
	} ?></tr>
	<tr><?php 
		echo "<th>CAPITAL EXPENDITURE HEADS</th>";
		foreach($nextTableFolio as $folio){
			echo "<th>".get_folio_name($folio)."</th>";
		}
		?></tr>
	<tr>
	<?php 
		echo "<td>".$mn."</td>"; 	$t=0;
		foreach($nextTableFolio as $folio){ $t++;
			$res_p =  mysqli_query($con, "select amount_spent from budget_votebooktb where operation_year = ".$year." and budget_folio_code = '".$folio."' and operation_month='".$mc."'");// and status = 'PAID'");
			$rs_p= mysqli_fetch_array($res_p);
			$money=$rs_p[0];	$monthlyTotal[$t] = $money;
			if($money == 0) $money = '-'; else $money=number_format($money, 2);
			echo "<td>".$money."</td>";
		}
		?>
	</tr>
	<tr><?php 
		echo "<th>TOTAL</th>";
		for($i=0; $i<$t; $i++){ //foreach($monthlyTotal as $mt){
			echo "<th>".number_format($monthlyTotal[$i], 2)."</th>";
		}
		?></tr>
<?php } ?>
</table>
	
	
</center>
<p>&nbsp;</p>
</body>
</html>