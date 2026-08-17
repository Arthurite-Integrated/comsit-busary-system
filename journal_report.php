<?php @session_start();

if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@$_SESSION['role'];
 $staff_category=@$_SESSION['staff_category'];
 require_once ("connect.php");
 require_once ("function.php");
 require_once ("currency_convert.php");
 //echo base64_decode('T0xBREFZTw==');

?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="images/logox.png">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Voucher</title>
<style>
body {
font : 0.8em "Arial", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
</style>
 <link rel="stylesheet" type="text/css" href="include/easyui.css">
   <link rel="stylesheet" type="text/css" href="include/icon.css">
	<link rel="stylesheet" type="text/css" href="include/demo.css">
	<link rel="stylesheet" type="text/css" href="include/colorbox.css">
    <link rel="stylesheet" href="css/tinybox.css" />
     <script type="text/javascript" src="include/jquery.min.js"></script>
	<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>-->
    
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
	<script type="text/javascript" src="include/jquery.colorbox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    
	<!--<script src="datepicker/jquery-1.8.3.js"></script>
    <script src="datepicker/jquery-ui.js"></script>
	-->
	<script src="datepicker/datepicker/ui.datepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="datepicker/datepicker/ui.datepicker.css">
	<script type="text/javascript" src="include/datagrid-groupview.js"></script>
	<script type="text/javascript" src="include/accounting.js"></script>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
</head>

<body >
<?php

	$journalno=@base64_decode(@$_REQUEST['p']);
	//$pvno="FEBRUARY/2015/0001";
	
	$val_str=explode("***",get_company());
	if($journalno=='') { echo "<font color='red'><b>No search criteria provided</b></font>"; exit; }
	$res_v= mysqli_query($con, "select * from journaltb where journalno='".$journalno."'");
	$rs_v=@mysqli_fetch_array($res_v);
	
   if(@mysqli_num_rows($res_v)>=1)
    {
	//header of the voucher
		
?>

<p>
  <!--<table align='center'><tr><td rowspan='2'><img src="<?php echo $val_str[1];?>" style='float:left' width='50' height='50'/></td><td><center><b><?php echo strtoupper($val_str[0]);?></b></center></tr><tr><td><b><center>PAYMENT VOUCHER</center></b></td></tr></table>-->
  
</p>
<table width="100%" border="0" align="center">
  <tr>
    <td width="5%" colspan="3"><center><img src="<?php echo $val_str[1];?>" style='float:center' width='100' height='80'/>
      <h3><?php echo strtoupper($val_str[0]);?></h3>
    <h2><strong>JOURNAL VOUCHER</strong></h2><hr></center></td>
  </tr>
  <tr>
    <td colspan="3"><table width="100%" border="0" align="center">
      <tr>
        <td align="left" valign="top"><strong>Entry Unit:</strong>: <?php echo @get_unit_name('126', $rs_v['dept_code']);?></td>
        <td width="48%" align="left" valign="top"><strong>Journal No.</strong>: <?php echo $rs_v['journalno'];?></td>
      </tr>
      <tr>
        <td align="left" valign="top"><strong>Entry Date:</strong>: <?php echo @date('d/m/Y',strtotime($rs_v['journal_date']));?></td>
        <td align="left" valign="top"><strong>&nbsp;</strong> </td>
      </tr>
      <tr>
        <td align="left" valign="top"><strong>Narration:</strong>: <?php echo $rs_v['description']; ?></td>
        <td align="left" valign="top"><strong>&nbsp;</strong> </td>
      </tr>
      <tr>
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" border="1" cellpadding="5" cellspacing="0">
          <tr>
            <td width="21%" height="26" bgcolor="#EAEAEA"><div align="left"><strong>SN</strong></div></td>
            <td width="21%" height="26" bgcolor="#EAEAEA"><div align="left"><strong>CODE</strong></div></td>
            <td width="21%" height="26" bgcolor="#EAEAEA"><div align="left"><strong>DESCRIPTION</strong></div></td>
            <td width="20%" height="26" bgcolor="#EAEAEA"><div align="left"><strong>AMOUNT (Cr)</strong></div></td>
            <td width="20%" height="26" bgcolor="#EAEAEA"><div align="left"><strong>AMOUNT (Dr)</strong></div></td>
          </tr>
          
          <?php $jno=@$rs_v['journalno']; $sn=1; $cr_total = 0; $dr_total=0;
		        $resj=@mysqli_query($con, "select * from journal_folio_codetb where journalno='$jno' order by folio_code");
				while($rs=@mysqli_fetch_array($resj))
				{
					
		 ?>
          <tr>
            <td height="26" align="left"><?php echo $sn++;?></td>
            <td height="26" align="left"><?php echo $rs['folio_code'];?></td>
            <td height="26" align="left"><?php echo @get_folio_name($rs['folio_code']); ?></td>
            <td height="26" align="left"><?php if($rs['trans_type']=="Credit") {
				echo number_format($rs['amount'],2); 
				$cr_total += $rs['amount'];
			}
				?></td>
            <td height="26" align="left"><?php if($rs['trans_type']=="Debit") {
				echo number_format($rs['amount'],2); 
				$dr_total += $rs['amount'];
			} ?></td>
          </tr>
          <?php } //end of while ?>
          
          <tr>
            <td height="26" align="left"></td>
            <td height="26" align="left"></td>
            <td height="26" align="center"><strong>TOTAL</strong></td>
            <td height="26" align="left"><strong><?php echo number_format($cr_total, 2); ?></strong></td>
            <td height="26" align="left"><strong><?php echo number_format($dr_total, 2); ?></strong></td>
          </tr>

          <?php
			/*$exp = explode('.',number_format($rs_v['amount'],2,'.',''));
			$words = convertNum($exp[0]);
			$words2 = @ereg_replace("And","and",ucwords($words));
			$wordsKobo = convertNum($exp[1]);
			$wordsKobo2 = ucwords($wordsKobo);
			
			$amountInWords = "$words2 Naira";
			if ($wordsKobo2 != "Zero") { $amountInWords .= ", $wordsKobo2 Kobo "; }
			$amountInWords .= " Only.";*/
			//echo "<strong><em>Amount in words:</em> $amountInWords</strong>";
			?>
<!--          <tr>
            <td height="26" colspan="2"><b>Total Amount in Words: <?php echo $amountInWords;?></b></td>
            <td width="18%" height="26"><div align="right"><strong>TOTAL (<?php echo "&#8358;"; ?>)</strong></div></td>
            <td height="26" align="center"><b><?php echo number_format($rs_v['amount_paid'],2);?></b></td>
          </tr>
-->        </table></td>
      </tr>
      <tr>
        <td valign="top"><table width="89%" border="0">
          <tr>
            <td width="25%" rowspan="2">Prepared by:</td>
            <td width="75%"><u><?php echo strtoupper(@get_staff_name($rs_v['prepared_by'])); ?></u><br />
              (Name in Block Letter)</td>
          </tr>
          <tr>
            <td><img src="pictures/<?php echo strtoupper($rs_v['prepared_by'])."_sign.jpg"; ?>" width="150" height="15" /><br />              <?php echo date('d/m/Y',strtotime($rs_v['date_prepared'])); ?><br />
              (Signature and Date)<br /></td>
          </tr>
        </table></td>
        <td valign="top"><table width="89%" border="0">
          <tr>
            <td width="25%" rowspan="2">Checked by:</td>
            <td width="75%"><u><?php echo strtoupper(@get_staff_name($rs_v['checked_by'])); ?></u><br />
              (Name in Block Letter)</td>
          </tr>
          <tr>
            <td><img src="pictures/<?php echo strtoupper($rs_v['checked_by'])."_sign.jpg"; ?>" width="150" height="15" /><br />
			<?php echo date('d/m/Y',strtotime($rs_v['date_checked'])); ?><br />
              (Signature and Date)<br /></td>
          </tr>
        </table></td>
        </tr>
        <tr>
        <td valign="top"><table width="89%" border="0">
          <tr>
            <td width="25%" rowspan="2">Authorized by:</td>
            <td width="75%"><u><?php echo strtoupper(@get_staff_name($rs_v['authorized_by'])); ?></u><br />
              (Name in Block Letter)</td>
          </tr>
          <tr>
            <td><img src="pictures/<?php echo strtoupper($rs_v['authorized_by'])."_sign.jpg"; ?>" width="150" height="15" /><br />
            <?php echo date('d/m/Y',strtotime($rs_v['date_authorized'])); ?><br />
              (Signature and Date)<br /></td>
          </tr>
        </table></td>
        <td>&nbsp;</td>
      </tr>
</table>
<p align="center"><a href="javascript:window.print();">Print</a></p>
<?php
	} //end of if found
  else
    echo "<b><font color='red'>The Journal Number does not match any record. No record to display</font></b>";
?>
</body>
</html>