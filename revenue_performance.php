<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>COMPREHENSIVE REVENUE PERFORMANCE</title>
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
//echo "$category $level";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'><u>".strtoupper($val[0])."<br/>"."SCHEDULE OF EXPECTED REVENUE AS AT:   ".get_month_name($month). "&nbsp; $year" ."</u></p></b><hr><p>";
  ?>
                                                                                                                                                                                                                                                                              <center>
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th>S/N</th>
    <th>CODE DESCRIPTION</th>
    <th>ACCOUNT CODE</th>
    <th><?php echo $year ?>  PROJECTION<br>&#8358;</th>
    <th><?php echo $year ?> ACTUAL<br>&#8358;</th>
    <th>VARIANCE<br>&#8358;</th>
	  <th>% OF ACTUAL TO BUGETED<br>&#8358;</th>
	</tr>

 <?php
 	$bcat= mysqli_query($con, "SELECT DISTINCT category FROM revenue_code order by ord");
	$total_b = 0; 		$total_s = 0;		$total_v = 0; $total_bx = 0; 		$total_sx = 0;		$total_vx = 0;
	while($yq =  mysqli_fetch_array($bcat, 3 )){
	  echo "<tr bgcolor='#999'>";
	  echo "<th> </th>
			<th colspan='6' align='left'>".$yq[0]."</th></tr>";
	 $sn=0;	
	 $sub_total_b = 0; 	$sub_total_s = 0;	$sub_total_v = 0; $sub_total_bx = 0; 	$sub_total_sx = 0;	$sub_total_vx = 0;
	 $sql_folio=@mysqli_query($con, "select distinct rev_code,reven_title from revenue_code where category = '".$yq[0]."' order by folio_code");
	   while($rst_folio=@mysqli_fetch_array($sql_folio))
	   {
	  ++$sn; 
		    $reven_title=$rst_folio['reven_title'];
           $rev_code=$rst_folio['rev_code'];
	 // $folio=$rst_folio['folio_code'];
	  //$folio_name=get_folio_name($folio);
	  //(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
	  $folio_budget=get_folio_budget_inc($rev_code, $year);
	  $sub_total_b += $folio_budget;	$sub_total_bx += $folio_budget;		
	  //$folio_budget_expences=get_folio_budget_expences($folio,$month ,$year);
	  $folio_budget_generate=folio_budget_generater($rev_code,$year);
	  $sub_total_s += $folio_budget_generate; $sub_total_sx += $folio_budget_generate;	//$total_s += $sub_total_s;
	  //$get_var_balance= get_var_balancer($rev_code,$year);
	$get_var_balance= $folio_budget - $folio_budget_generate;
	  $sub_total_v += $get_var_balance;	$sub_total_vx += $get_var_balance;	
		   //echo $folio_budget_generate; echo "||". $folio_budget; exit;
		     $perc = round((($folio_budget_generate/$folio_budget)*100),2);
		  echo "<tr>";
		  echo "<td> $sn </td>
				<td>$reven_title</td>
				<td nowrap height='25'>$rev_code</td>
				<td><div align='right'>".@number_format($folio_budget,2)."</div></td>
				<td><div align='right'>".@number_format($folio_budget_generate,2)."</div></td>
				<td><div align='right'><strong>".@number_format($get_var_balance,2)."</div></strong></td>
				<td><div align='right'><strong>".@number_format($perc,2)."</div></strong></td></tr>";
	  }
		if ($yq[0]=='RECURRENT')
		{
		 $total_sx += $sub_total_sx;
	  $total_vx += $sub_total_v;
	  $total_bx += $sub_total_b;
		}
	  $total_s += $sub_total_s;
	  $total_v += $sub_total_v;
	  $total_b += $sub_total_b;
		  echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
				<th colspan='2'>SUB TOTAL</th>
				<td><div align='right'>".@number_format($sub_total_b,2)."</div></td>
				<td><div align='right'>".@number_format($sub_total_s,2)."</div></td>
				<td><div align='right'><strong>".@number_format($sub_total_v,2)."</div></strong></td>
				<td><div align='right'><strong></div></strong></td></tr>";
	}
		  echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
				<th colspan='2'>SUB TOTAL B</th>
				<td><div align='right'>".@number_format(($total_b-$total_bx),2)."</div></td>
				<td><div align='right'>".@number_format(($total_s-$total_sx),2)."</div></td>
				<td><div align='right'><strong>".@number_format(($total_v-$total_vx),2)."</div></strong></td>
				<td><div align='right'><strong></div></strong></td></tr>";
		echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
				<th colspan='2'>GRAND TOTAL(A+B)</th>
				<td><div align='right'>".@number_format($total_b,2)."</div></td>
				<td><div align='right'>".@number_format($total_s,2)."</div></td>
				<td><div align='right'><strong>".@number_format($total_v,2)."</div></strong></td>
				<td><div align='right'><strong></div></strong></td></tr>";
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>