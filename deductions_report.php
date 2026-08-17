<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DEDUCTIONS REPORT</title>
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
$s=date_parse($_POST['from']);
$start="{$s['year']}-{$s['month']}-{$s['day']}"; 

$e=date_parse($_POST['to']);
$end="{$e['year']}-{$e['month']}-{$e['day']}";

$dtype=$_POST['dtype'];
if($dtype=="STAMP DUTY") $suffix="SD";
elseif($dtype=="ENDOWMENT") $suffix="END";
else $suffix=$dtype;

$ACT=explode(':', $_POST['account']);
$account=$ACT[0];
$account_code=$ACT[1];

require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'>".strtoupper($val[0])."<br/>"."BURSARY DEPARTMENT,<br>SCHEDULE OF {$dtype} DEDUCTION ON {$account} PROJECTS (".$_POST['from']." TO ".$_POST['to'].")</p></b><hr><p>";
  ?>
                                                               <center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
	  <tr>
    <th>S/N</th>
    <th>DATE</th>
    <th>DESCRIPTION</th>
    <th>CONTRACTOR'S NAME</th>
    <th>GROSS AMOUNT<br>&#8358;</th>
    <th><?Php echo $dtype; ?> AMOUNT<br>&#8358;</th>
	</tr>

 <?php
 	//$sql="SELECT vp.*, v.payee_name, v.description, v.entry_date, vt.amount FROM `voucher_parent_child_taxtb` vp, vouchertb v, voucher_taxtb vt WHERE vp.child_pvno LIKE '%_{$suffix}' AND v.description LIKE '% {$suffix} %' AND v.dept_acctcode like '{$account_code}-%' AND (v.entry_date BETWEEN '{$start}' AND '{$end}') AND vp.child_pvno=vt.pvno";
	$sql="SELECT vp.*, vt.amount, vt.folio_code, vt.entry_date FROM `voucher_parent_child_taxtb` vp INNER JOIN voucher_taxtb vt ON vp.child_pvno=vt.pvno WHERE vp.child_pvno LIKE '%_{$suffix}' AND (vt.entry_date BETWEEN '{$start}' AND '{$end}')";
	
	$qry= mysqli_query($con, $sql);	$sn=1;
	$totalCSum=0;  $totalDSum=0;
	while($r =  mysqli_fetch_array($qry, 3 )){
		$folio=$r["folio_code"];
		$acctcode=$bursary->get_any_value("budget_category", "budget_votebooktb", "budget_category", $account, " AND voucher_folio_code = '".$folio."' ");
		if(strtolower($acctcode) != strtolower($account)) continue;
		$d=date_parse($r['entry_date']);
		$date="{$d['day']}/{$d['month']}/{$d['year']}";
		//$desc=$r['description'];
		//$cname=$r['payee_name'];
		$cpvno=$r["child_pvno"];
		$ppvno=$r["parent_pvno"];
		
		$cname2=strtoupper($bursary->get_any_value("payee_name", "vouchertb", "pvno", $ppvno));
		$desc2=$bursary->get_any_value("description", "vouchertb", "pvno", $ppvno);
		$dsum=$bursary->get_any_value("amount_approved", "vouchertb", "pvno", $ppvno);
		$csum=$r['amount'];
		$totalCSum += $csum;   $totalDSum += $dsum;
			echo "<tr>";
			echo "<td>".$sn++."</td>
			<td nowrap>{$date}</td>
			<td>{$desc2}</td>
			<td>{$cname2}</td>
			<td><div align='right'>".@number_format($dsum,2)."</div></td><td><div align='right'>".@number_format($csum,2)."</div></td></tr>";
	}
		echo "<tr>";
		echo "<td></td><td></td><td></td>
		<th bgcolor='#ccc'>SUB TOTAL</th>
		<td bgcolor='#ccc'><div align='right'><strong>".@number_format($totalDSum, 2)."</strong></div></td>";
		  echo "<td bgcolor='#ccc'><div align='right'><strong>".@number_format($totalCSum, 2)."</strong></div></td></tr>";
				
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>