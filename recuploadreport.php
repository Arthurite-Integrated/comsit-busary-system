<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ACCOUNT STATEMENT REPORT</title>
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
$from=$_REQUEST['from'];
 $to=$_REQUEST['to'];
require_once "function_c.php";
//echo "$category $level";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php
	if(isset($_POST['sbtn_nD']) and isset($_POST['recordtypeS']) and $_POST['recordtypeS']!='' and isset($_POST['rmonthS']) and $_POST['rmonthS']!='' and isset($_POST['ryearS']) and $_POST['ryearS']!=''){
		$qr= mysqli_query($con, "SELECT * FROM {$_POST['recordtypeS']} WHERE rmonth='{$_POST['rmonthS']}' AND ryear='{$_POST['ryearS']}' AND paytype='{$_POST['typeS']}'");
		if( mysqli_num_rows($qr)>0){
			$qry="DELETE FROM {$_POST['recordtypeS']} WHERE rmonth='{$_POST['rmonthS']}' AND ryear='{$_POST['ryearS']}' AND paytype='{$_POST['typeS']}'";
	  		if( mysqli_query($con, $qry)){
				if($_POST['recordtypeS']=="recon_banktb")
					echo "<script>alert('ACCOUNT STATEMENT UPLOAD REPORT \nFOR ".strtoupper($_POST['rmonthS']).", {$_POST['ryearS']} has been deleted! \n({$_POST['typeS']})'); window.close();</script>";
				if($_POST['recordtypeS']=="recon_remitatb")
					echo "<script>alert('REMITA PAYMENT UPLOAD REPORT \nFOR ".strtoupper($_POST['rmonthS']).", {$_POST['ryearS']} has been deleted! \n({$_POST['typeS']})'); window.close();</script>";
				exit;
			}
			echo "<script>alert('No record found matching your selected filter.'); window.close();</script>";
			exit;
		}else{
			echo "<script>alert('No record found matching your selected filter.'); window.close();</script>";
			exit;
		}
	}
if($_POST['recordtypeS']=="recon_banktb")
echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/></center><b><p align='center'>ACCOUNT STATEMENT UPLOAD REPORT<br>FOR ".strtoupper($_POST['rmonthS']).", {$_POST['ryearS']}<br>({$_POST['typeS']})</p></b><hr><p>";
if($_POST['recordtypeS']=="recon_remitatb")
echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/></center><b><p align='center'>REMITA PAYMENT UPLOAD REPORT<br>FOR ".strtoupper($_POST['rmonthS']).", {$_POST['ryearS']}<br>({$_POST['typeS']})</p></b><hr><p>";

  ?>
<center>
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th>S/N</th>
    <th>DESCRIPTION</th>
    <th>PAYMENT REFERENCE</th>
    <th>AMOUNT <br>&#8358;</th>
    <th>RECORD TYPE</th>
    <?php if($_POST['recordtypeS']=="recon_remitatb" && $_POST['typeS'] == "Debit") { ?>
	<th>REFERENCE NO.</th>
	<th>BATCH NO.</th>
	<th>PVNO.</th>
    <?php } ?>

    <!--th>DATE/TIME</th-->

	</tr>

 <?php
	  $qry="SELECT * FROM {$_POST['recordtypeS']} WHERE rmonth='{$_POST['rmonthS']}' AND ryear='{$_POST['ryearS']}' AND paytype='{$_POST['typeS']}'";
	  $sq= mysqli_query($con, $qry);
	  $total=0;
	  while($r= mysqli_fetch_array($sq, 3 )){
		  $sn++; 
		  echo "<tr ";
      	  /*if($r['paytype']=='Debit'){ 
		  echo " style='background-color: #f5b7be;' ";
		  $total -= $r['amount'];
	  }elseif($r['paytype']=='Credit'){
		  $total += $r['amount'];
	  }*/
	  $total += abs($r['amount']);
      echo "><td>{$sn}</td>
		  <td>{$r['paymentid']}</td>
		  <td>".str_replace('\\\\','\\',$r['credit_reference'])."</td>
		  <td align='right'>".number_format($r['amount'], 2)."</td>
		  <td>{$r['paytype']}</td>";
		  if($_POST['recordtypeS']=="recon_remitatb" && $_POST['typeS'] == "Debit") { 
			$s1=explode('-', $r['paymentid']);
			$batch=trim($s1[1]);
			if(strlen($batch) > 7){
				$batch='X';
				$s2=explode(" ", trim($s1[1]));
				if(is_numeric(substr(trim($s2[1]), 0, 3))) $s3=trim($s2[1]);
				else $s3='';
			}else{
				$s2=explode(" ", trim($s1[2]));
				if(is_numeric(substr(trim($s2[1]), 0, 3))) $s3=trim($s2[1]);
				else $s3='';
			}
			$s4=trim($s2[0]).$s3;

			echo "<th>{$r['special_ref2']}</th>
			<th>{$batch}</th>";
			echo "<th>{$s4}</th>";
			@mysqli_query($con, "UPDATE recon_remitatb SET batchno='{$batch}', pvno='{$s4}' WHERE id={$r['id']}");
		  } 
		  echo "<!--td nowrap>{$r['entry_date']} {$r['entry_time']}</td-->
		  </tr>";
	  }
	  echo "<tr><td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td><strong>TOTAL</strong></td>
		  <td align='right'><strong>".number_format($total, 2)."</strong></td>
		  <td>&nbsp;</td>
		  <!--td nowrap>&nbsp;</td--></tr>";
  ?>
</table>
</center>
<p>&nbsp;</p>
</body>
</html>
