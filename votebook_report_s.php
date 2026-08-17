<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
	$memo_id=@base64_decode($_REQUEST['id']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@$_SESSION['role'];
 $staff_category=@$_SESSION['staff_category'];

?>
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
$year=$_REQUEST['year']; 
$folio=$_REQUEST['folio'];
$rid=$_REQUEST['rid'];

require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////

$amount=get_folio_budget($folio, $year);

//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<table width="90%" border="0" align="center"><tr>
<td width="40%" valign="bottom" align="left">
<?php
	(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
	?>
Department: <?php echo $folio_name; ?><br>
Expenditure Head: <?php echo $folio_name; ?><br>
Code: <?php echo $folio; ?></td>
<td valign="top" align="center">
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'>".strtoupper($val[0])."<br/>P.M.B. 1515, ILORIN, NIGERIA<br>"."BUDGET VOTE BOOK</p></b><p>";
  ?>
  </td>
  <td width="40%" valign="bottom"><div style="float:right">
  Approved Vote &#8358;: <?php echo number_format($amount, 2); ?><br>
  Additional Vote &#8358;:................................................
  <p><strong>Spending Limit:</strong><br>
  <table width="100%" align="">
  <tr><td>1st Qtr: <?php echo number_format(getQuarterlyLimit("1st Quarter", $year, $folio), 2); ?></td>
  <td>2nd Qtr: <?php echo number_format(getQuarterlyLimit("2nd Quarter", $year, $folio), 2); ?></td></tr>
  <tr><td>3rd Qtr: <?php echo number_format(getQuarterlyLimit("3rd Quarter", $year, $folio), 2); ?></td>
  <td>4th Qtr: <?php echo number_format(getQuarterlyLimit("4th Quarter", $year, $folio), 2); ?></td></tr>
  </table>
</div>
</td>
  </tr>
	</table>
<center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
	  <tr>
    <th rowspan="2">Line No.</th>
    <th rowspan="2">Date</th>
    <th rowspan="2">PV/LPO. Journal</th>
    <th rowspan="2">Details</th>
    <th colspan="2">&nbsp;</th>
    <th colspan="2">&nbsp;</th>
    <th colspan="2">LIABILITY</th>
    <th colspan="2">&nbsp;</th>
    <th colspan="2" rowspan="2">Incurred to Date<br>&#8358;</th>
    <th colspan="2" rowspan="2">Balance Available<br>&#8358;</th>
    <th rowspan="2">Signature</th>
    <th rowspan="2">Remark</th>
    </tr>
	  <tr>
	    <th colspan="2">Incurred<br>&#8358;</th>
	    <th colspan="2" valign="top">Date<br></th>
	    <th colspan="2">Payment<br>&#8358;</th>
	    <th colspan="2">Payment<br>&#8358;</th>
    </tr>
     <?php 
  $sql="SELECT * FROM budget_votebooktb WHERE budget_folio_code='$folio' AND operation_year='$year'";
  $qry= mysqli_query($con, $sql);	$sn=1;
	  $incurred=0;
  while($r =  mysqli_fetch_array($qry, 3 )){
	  $edate=date_create($r['entry_date']);
	  $vdate=date_create($bursary->get_any_value("entry_date", "vouchertb", "pvno", $r['voucher_pvno']));
	  $desc=$bursary->get_any_value("description", "vouchertb", "pvno", $r['voucher_pvno']);
	  $payee=$bursary->get_any_value("payee_name", "vouchertb", "pvno", $r['voucher_pvno']);
	  $liability=$bursary->get_any_value("total_tax", "vouchertb", "pvno", $r['voucher_pvno']);
	  $amount=$r['amount'];
	  $incurred += $amount;
	  $balance = get_budget($folio, $year) - $incurred;
   ?>
    
    
	  <tr>
	    <td><?php echo $sn++; ?></td>
	    <td><?php echo date_format($vdate, 'd/m/Y'); ?></td>
	    <td><?php echo $r['voucher_pvno']; ?></td>
	    <td><?php echo strtoupper($payee)."<br>".$desc; ?></td>
	    <td colspan="2" align="right"><?php echo number_format($amount, 2); ?></td>
	    <td colspan="2"><?php echo date_format($edate, 'd/m/Y'); ?></td>
	    <td colspan="2" align="right"><?php echo number_format($liability, 2); ?></td>
	    <td colspan="2">&nbsp;</td>
	    <td colspan="2" align="right"><?php echo  number_format($incurred, 2); ?></td>
	    <td colspan="2" align="right"><?php echo  number_format($balance, 2); ?></td>
	    <td>&nbsp;</th>
	    <td>&nbsp;</th>
    </tr>
    <?php
  }
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>