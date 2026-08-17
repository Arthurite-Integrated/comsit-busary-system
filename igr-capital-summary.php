<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>EXTENDED PERFORMANCE</title>
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
		$category = "Capital";////$yq[0];
		echo "<tbody><tr bgcolor='#999'>
		<th>S/N</th>
		<th>CAPITAL EXPENDITURE HEADS</th>";
		"<th>GROSS AMOUNT<br>&#8358;</th>";
		echo '<th>TOTAL<br>&#8358;</th><tr></thead><tbody>';
	  $spent = 0;		$total = 0;
	 $sn=0;	
	 $sql_folio=@mysqli_query($con, "select distinct folio_code from budgettb where budget_year = $year and bursary_category = '".$category."' order by folio_code");
	   while($rst_folio=@mysqli_fetch_array($sql_folio))
	   {
		   $sub_total_b = 0; 	$sub_total_s = 0;	$sub_total_v = 0;
			$folio=$rst_folio['folio_code'];
			$folio_name=get_folio_name($folio);
			(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
			$res_p = @mysqli_query($con, "select sum(amount) as amount_spent from budget_votebooktb where operation_year = ".$year." and budget_folio_code = '".$folio."'");
			$rs_p=@mysqli_fetch_array($res_p);
			$spent=@$rs_p[0];
			$total += $spent;
			++$sn; 
			echo "<tr>";
			echo "<td>{$sn}</td>
			<td>{$folio_name}</td>
			<td>".number_format($spent, 2)."</td>";
	}
	echo "</tr><tr>";
	echo "<td></td>
	<th>GRAND TOTAL</th>";
	echo "<th>".number_format($total, 2)."</th></tr>";
  ?>
	  </tbody></table> 	
	
</center>
<p>&nbsp;</p>
</body>
</html>