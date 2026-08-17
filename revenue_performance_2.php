<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>REVENUE SPREADSHEET REPORT</title>
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
$year=$_REQUEST['pyear3'];
require_once "function_c.php";
//echo "$category $level";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'><u>".strtoupper($val[0])."<br/>"."REVENUE REPORT(INCOME) AS AT:   ".get_month_name($month). "&nbsp; $year" ."</u></p></b><hr><p>";
  ?>
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
 <?php
 //$category = $_REQUEST['pcat'];
 	$month_code = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 	$month_name = array('JAN', 'FEB', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC');
	$monthly_var=array();
	$tbudget = 0; $tmspent = array(); $tspent = 0; $tvariance = 0;  $tmspentx = array(); $tmspentxx = array();
	$k=true;
 	$bcat= mysqli_query($con, "SELECT DISTINCT category FROM revenue_code order by ord");
	while($yq =  mysqli_fetch_array($bcat, 3 )){
		$category = $yq[0];
	  if(!$k) echo "<tr bgcolor='#999'><th> </th>
			<th colspan='17' align='left'>".$category."</th></tr>";
	else{
		echo " <tr bgcolor='#999'>
		<th>S/N</th>
		<th>$category</th>
		<th>REVENUE CODE</th>";
		foreach($month_name as $mn) echo "<th>$mn $year<br>&#8358;</th>";
		echo '<th>TOTAL<br>&#8358;</th>
		</tr>';
	}
	$k=false;
	 $monthly_spent=array(); $monthly_spentx=array();
	 $total_b = 0; 		$total_s = 0;		$total_v = 0;$total_sx = 0;
	 $sn=0;	
	 $sql_folio=@mysqli_query($con, "select distinct rev_code,reven_title from revenue_code where category = '".$yq[0]."' order by reven_title");
	   while($rst_folio=@mysqli_fetch_array($sql_folio))
	   {
		   $sub_total_b = 0; 	$sub_total_s = 0;	$sub_total_v = 0; $sub_total_sx = 0;
			$rev_code=$rst_folio['rev_code'];
           $reven_title=$rst_folio['reven_title'];
			//$folio_name=get_folio_name($folio);
			//(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
			//$res_p = @mysqli_query($con, "select sum(amount) as amount_spent from transtb where year(transdate) = '".$year."' and rev_code = '".$rev_code."'");
			//$rs_p=@mysqli_fetch_array($res_p);
			//$sub_total_b=@$rs_p[0];
			//$total_b += $sub_total_b;
			++$sn; 
			echo "<tr>";
			echo "<td> $sn </td>
			<td>$reven_title</td>
			<td >$rev_code</td>";
			$i=0;	
			foreach($month_code as $mc){
				$res_p = @mysqli_query($con, "select sum(amount) as amount_spent from transtb where year(transdate) = '".$year."' and rev_code = '".$rev_code."' and month(transdate)='".$mc."'");// and status = 'PAID'");
				$rs_p=@mysqli_fetch_array($res_p);
				$sub_s=@$rs_p[0];
				if($sub_s == 0) $sub_s='';
				echo "<td><div align='right'>".@number_format($sub_s,2)."</div></td>";
				$sub_total_s += $sub_s;	
				$sub_total_sx += $sub_s;	
				
				$monthly_spent[$i] += $sub_s;
				
				$i++;
			}
		   
		   
			$sub_total_v = $sub_total_b - $sub_total_s;
			$total_v += $sub_total_v;
			$total_s += $sub_total_s;
		   $total_sx += $sub_total_sx;
			echo "<td bgcolor='#CCCCCC'><div align='right'><strong>".@number_format($sub_total_s,2)."</div></strong></td>
			<!--<td><div align='right'><strong>".@number_format($sub_total_v,2)."</div></strong></td>--> </tr>";
	}
	echo "<tr bgcolor='#ccc'>";
	echo "<td></td>
	<th>SUB TOTAL</th>
	<th><!--<div align='right'>".@number_format($total_b,2)."</div>--></th>";
	$j=0;
	foreach($monthly_spent as $ms) {
		$tmspent[$j] += $ms;   $j++;
		echo "<th><div align='right'>".@number_format($ms,2)."</div></th>";
	}
		if ($yq[0]=='RECURRENT')
		 {
			
			 foreach($monthly_spent as $msx) {
		$use[]=	 $tmspentx[$j] += $msx;   $j++;
				
	$total_use = $total_sx;
	}
		 }
		 
	echo "<th bgcolor='#CCCCCC'><div align='right'>".@number_format($total_s,2)."</div></th>
	<!--<th><div align='right'><strong>".@number_format($total_v,2)."</div></strong></th> --></tr>";
	$tbudget += $total_b; $tspent += $total_s;	$tvariance += $total_v;
}
		  echo "<tr bgcolor='#999'>";
		  echo "<td></td>
				<th>SUB TOTAL B</th>
				<th><div align='right'>".@number_format($tbudget,2)."</div></th>";
	  
	  	  foreach (array_combine($tmspent, $use) as $ts => $usx)
			 // echo $ts ."=".$usx;
	  		echo "<td><div align='right'>".@number_format(($ts - $usx),2)."</div></th>";
		
				echo "<th bgcolor='#999'><div align='right'>".@number_format(($tspent-$total_use),2)."</div></th>
				 </tr>";
	   echo "<tr bgcolor='#999'>";
		  echo "<td></td>
				<th>GRAND TOTAL</th>
				<th><div align='right'>".@number_format($tbudget,2)."</div></th>";
		foreach($tmspent as $ts) echo "<td><div align='right'>".@number_format($ts,2)."</div></th>";
				echo "<th bgcolor='#999'><div align='right'>".@number_format($tspent,2)."</div></th>
				 </tr>";
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>