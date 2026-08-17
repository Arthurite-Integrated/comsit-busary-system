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
 <link rel="stylesheet" type="text/css" href="include/easyui.css">
   <link rel="stylesheet" type="text/css" href="include/icon.css">
	<link rel="stylesheet" type="text/css" href="include/demo.css">
	<link rel="stylesheet" type="text/css" href="include/colorbox.css">
    <link rel="stylesheet" href="css/tinybox.css" />
     <script type="text/javascript" src="include/jquery.min.js"></script>
    
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
	<script type="text/javascript" src="include/jquery.colorbox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
	<script src="datepicker/datepicker/ui.datepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="datepicker/datepicker/ui.datepicker.css">
	<script type="text/javascript" src="include/datagrid-groupview.js"></script>
	<script type="text/javascript" src="include/accounting.js"></script>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<style>
body {
font : 1.0em "Arial", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
</style>
</head>

<body >
<?php
@require_once "myclass_m.php";
	@$bursary = new myclass_m();

	$tid=$_REQUEST['tid'];
	
	$val_str=explode("***", get_company());
	if($tid=='') { echo "<font color='red'><b>No search criteria provided</b></font>"; exit; }
	$res_v= mysqli_query($con, "SELECT * from transtb where id='{$tid}'");
	$rs_v=@mysqli_fetch_array($res_v, 3);
	
   if(@mysqli_num_rows($res_v)>=1)
    {
	//header of the voucher
	
		
?>

<table width="100%" border="0" frame="box" align="center" cellpadding="10px">
          <tr style="border-top: 1px solid #0000;">
          <td width="5%" colspan="2"><center><img src="<?php echo $val_str[1];?>" style='float:left' width='70' height='80'/>
          <h3><?php echo strtoupper($val_str[0]);?><br>P.M.B. 1515, ILORIN NIGERIA</h3>
          <h4><strong>GENERAL RECEIPT</strong></h4><hr></center></td>
          </tr>
      <tr>
        <td align="left" valign="top"><strong>Date</strong>: <?php echo date('d-m-Y', strtotime($rs_v['transdate'])); ?></td>
        <td width="48%" align="right" valign="top" nowrap><strong>No.</strong>: <?php echo $rs_v['receiptno'];?></td>
      </tr>
      <tr>
        <td align="left" valign="top" colspan="2"><strong>Received From</strong>: <?php echo @get_folio_name($rs_v['folio_code']); ?></td>
      </tr>
      <tr>
        <td align="left" valign="top" colspan="2" style="line-height: 1.5em; text-align:justify;">
          <strong>The sum of</strong>: 
          <?php
          $exp = explode('.', number_format($rs_v['amount'],2,'.',''));
          $words = convertNum($exp[0]);
          $words2 = @str_replace("And","and",ucwords($words));
          $wordsKobo = convertNum($exp[1]);
          $wordsKobo2 = ucwords($wordsKobo);
          
          $amountInWords = "$words2 Naira";
          if ($wordsKobo2 != "Zero") { $amountInWords .= ", $wordsKobo2 Kobo "; }
          $amountInWords .= " Only.";
          ?>
          <?php echo $amountInWords; ?>
        </td>
      </tr>
      <tr>
        <td align="left" valign="top" colspan="2"><strong>Amount : </strong> &#8358;<?php echo number_format($rs_v['amount'], 2);?></td>
      </tr>
      <tr>
        <td align="left" valign="top" width="90%"><strong>Being</strong>: <?php echo @get_folio_name($rs_v['folio_code']); ?></td>
        <td align="left" valign="top" width="10%" nowrap><strong>Code</strong>: <?php echo $rs_v['folio_code']; ?></td>
      </tr>
      <tr>
        <td align="center" valign="top" colspan="2">
          <p><br><br><img src="pictures/<?php echo strtoupper($rs_v['entry_by'])."_sign.jpg"; ?>" width="100" /><br><hr width="30%"></p>
         </td>
      </tr>
        </table>
<p align="center"><a href="javascript:window.print();">Print</a></p>
<?php
	} //end of if found
  else
    echo "<b><font color='red'>The Receipt Number does not match any record. No record to display</font></b>";
?>
</td>
</tr>
</table>
</body>
</html>