<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>NUS REPORT - UTILIZATION OF IGR</title>
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
$year=$_REQUEST['sryear2']; $month=$_REQUEST['month'];
$montht=$_REQUEST['montht'];
require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<strong>NATIONAL UNIVERSITY COMMISSION<br>
UTILIZATION OF INTERNALLY GENERATED REVENUE<br/>
INSTITUTION: UNIVERSITY OF ILORIN, ILORIN<br/>
YEAR: ".strtoupper(get_month_name($month)). "&nbsp; {$year} TO ".strtoupper(get_month_name($montht)). " {$year}</strong><hr>";
  ?>
	
<center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0"><thead>
	<tr>
    <th>S/N</th>
    <th>EXPENDITURE DESCRIPTION</th>
    <th>CAPITAL<br>&#8358; : K</th>
    <th>OVERHEAD<br>&#8358; : K</th>
    <th>TOTAL<br>&#8358; : K</th>
    <th>REMARK
    </th>
	</tr>
</thead><tbody>
 <?php
$sql="SELECT DISTINCT budget_title, folio_code FROM budgettb WHERE (bursary_category = 'IGR Capital' OR bursary_category = 'FG Capital') AND budget_year = '".$year."' ORDER BY budget_title";
$q= mysqli_query($con, $sql);$sn=1;
while($p= mysqli_fetch_array($q, 3 )){
	$sql="SELECT sum(bv.amount) AS spent FROM budget_votebooktb bv WHERE bv.operation_year = '".$year."' AND budget_folio_code = '{$p['folio_code']}' GROUP BY bv.budget_folio_code";
	//$sql="SELECT sum(bv.amount) AS spent, b.bursary_category, b.budget_title FROM budgettb b INNER JOIN budget_votebooktb bv ON b.folio_code=bv.budget_folio_code  WHERE bv.operation_year = '".$year."' AND (bv.budget_category = 'IGR Capital' OR bv.budget_category = 'FG Capital') GROUP BY b.bursary_category";//(bv.budget_folio_code like '21-%' OR bv.budget_folio_code like '22-%' OR bv.budget_folio_code like '23-%' OR bv.budget_folio_code like '71-%') GROUP BY b.bursary_sub_category";
	//$sql="SELECT sum(t.amount) AS spent, f.category, fc.folio_category FROM ((transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code) INNER JOIN folio_categorytb fc ON f.category=fc.id) WHERE year(t.transdate) = '".$year."' AND (t.folio_code like '21-%' OR t.folio_code like '22-%' OR t.folio_code like '23-%' OR t.folio_code like '71-%') AND t.transtype='Debit' GROUP BY f.category";
	$qry= mysqli_query($con, $sql);	
	$totalCapital=0;
		$totalOverhead=0;
		$grandTotal=0;
	while($r =  mysqli_fetch_array($qry, 3 )){
		echo "<tr><td>".$sn++."</td>";
		echo "<td>".$p['budget_title']."</td>";
		echo "<td align='right'>".number_format($r[0], 2)."</td>";
		echo "<td>&nbsp;</td>";
		echo "<td align='right'>".number_format($r[0], 2)."</td>";
		echo "<td>&nbsp;</td></tr>";
		$totalCapital+=$r[0];
		$grandTotal+=$r[0];
	}
}
	echo "<tr><td colspan='6'>&nbsp;</td></tr>";
	echo "<tr><td colspan='6'>&nbsp;</td></tr>";


	$sql2="SELECT sum(bv.amount) AS spent, b.bursary_sub_category FROM budgettb b INNER JOIN budget_votebooktb bv ON b.folio_code=bv.budget_folio_code WHERE bv.operation_year = '".$year."' AND (b.bursary_category = 'Recurrent') AND b.bursary_sub_category != '' GROUP BY b.bursary_sub_category";
	
	//$sql2="SELECT sum(t.amount) AS spent, f.category, fc.folio_category FROM ((transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code) INNER JOIN folio_categorytb fc ON f.category=fc.id) WHERE year(t.transdate) = '".$year."' AND (t.folio_code like '09-001%') AND t.transtype='Debit' GROUP BY f.category";
	$qry2= mysqli_query($con, $sql2);	$sn=1; $n=1;
	while($r =  mysqli_fetch_array($qry2, 3 )){
		echo "<tr><td>".$sn++."</td>";
		echo "<td>".$r['bursary_sub_category']."</td>";
		echo "<td>&nbsp;</td>";
		echo "<td align='right'>".number_format($r[0], 2)."</td>";
		echo "<td align='right'>".number_format($r[0], 2)."</td>";
		$x=$n++;
		echo "<td align='center'><a href='budget_performance_2_a.php?attach=Attachment ".strtoupper(numberToRoman($x))."&pyear2=$year&cat=".$r['bursary_sub_category']."' target='_blank'>Attachment ".strtoupper(numberToRoman($x))."</a></td></tr>";
		$totalOverhead+=$r[0];
		$grandTotal+=$r[0];
	}
		
		
		  echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
		<th>GRAND TOTAL</th>
		<td><div align='right'><strong>".@number_format($totalCapital, 2)."</strong></div></td>";
		echo "<td><div align='right'><strong>".@number_format($totalOverhead, 2)."</strong></div></td>";
		echo "<td><div align='right'><strong>".@number_format($grandTotal, 2)."</div></strong></td><td></td></tr>";
				
  ?>
</table>   
<table width="80%" border="0" align="center" cellpadding="5" cellspacing="0"><thead>
	<tr>
	  <td height="59">&nbsp;</td>
	  <td>&nbsp;</td>
	  </tr>
	<tr>
    <td width="64%"><strong>University Representative</strong><br>
Name of Officer:.............................................................<br>
Designation:....................................................................<br>
Signature:........................................................................<br>
Date:...............................................................................</td>
    <td width="36%" nowrap="nowrap"><strong>NUC Representative</strong><br>
Name of Officer:.............................................................<br>
Designation:....................................................................<br>
Signature:........................................................................<br>
Date:...............................................................................</td>
	</tr>
</table>
</center>
<p>&nbsp;</p>
</body>
</html>