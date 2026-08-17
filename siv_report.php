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
<!DOCTYPE>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>GRN</title>
<style>
body {
font : 0.6em "Arial", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
table {
	border-collapse: collapse;
	margin: 10px;
		
}


</style>
</head>

<body >
<?php
	$sn=0;
	$total = 0;
	$siv=@base64_decode(@$_REQUEST['p']);
	//$pvno="FEBRUARY/2015/0001";
	
	//$val_str=explode("***",get_company());
	if($siv=='') { echo "<font color='red'><b>No search criteria provided</b></font>"; exit; }
	$res_v= mysqli_query($con, "select * from grn_sivtb where siv='$siv' ");
	$rs_v=@mysqli_fetch_array($res_v);
	$received_by = $rs_v['received_by'];
   if(@mysqli_num_rows($res_v)>=1)
    { 
	$by2 =  $rs_v['received_by_name'];
	$by =  $rs_v['checked_by_name'];
	$grn =  $rs_v['grn'];
	
	$res_by=@mysqli_query($con, "select * from stafftb where fileno = '$by' limit 1");
	$rs_bys=@mysqli_fetch_array($res_by);	
	$name = $rs_bys['title']." ". $rs_bys['surname']." ".$rs_bys['first_name']." ".$rs_bys['other_name'];

$res_by2=@mysqli_query($con, "select * from stafftb where fileno = '$by2' limit 1");
	$rs_bys2=@mysqli_fetch_array($res_by2);	
	$name2 = $rs_bys2['title']." ". $rs_bys2['surname']." ".$rs_bys2['first_name']." ".$rs_bys2['other_name'];		
?>

<p>
  <!--<table align='center'><tr><td rowspan='2'><img src="<?php echo $val_str[1];?>" style='float:left' width='50' height='50'/></td><td><center><b><?php echo strtoupper($val_str[0]);?></b></center></tr><tr><td><b><center>PAYMENT VOUCHER</center></b></td></tr></table>-->
  
</p>
 <?php $siv=@$rs_v['siv'];
 $res_taxs=@mysqli_query($con, "select * from assettb where siv='$siv' limit 1");
	$rs_taxs=@mysqli_fetch_array($res_taxs);	
	$sup_no = $rs_taxs['sup_id'];
	$location = $rs_taxs['location'];
	$asst=@mysqli_query($con, "select * from locationtb where loc_code='$location'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit'];  $unit=$rs_d['dept'];
 $loc = $unit=$rs_d['dept']." (".$unit=$rs_d['unit']." ) ";
$supp=@mysqli_query($con, "select * from suppliertb where sup_id ='$sup_no' ");
	$supps=@mysqli_fetch_array($supp);		
 ?>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
<tr><td align="center" colspan="6"><b> UNIVERSITY OF ILORIN <br> CENTRAL STORES <br> ILORIN <br> STORES ISSUED VOUCHER</b></td></tr>
<tr><td  colspan="6"><b>STORE/SERVICE POINT:</b> <?php echo $loc;?></td></tr>
<tr><td  colspan="2"><b>Date :</b><?php echo @date('d/m/Y',strtotime($rs_v['entry_date']));?></td><td colspan="2"> <b>GRN:</b> <?php echo $grn;?></td><td colspan="2"><b>SIV:</b><?php echo $siv;?></td></tr>
          <tr>
            <td width="21%"><div align="center"><strong>S/NO</strong></div></td>
            <td width="21%"><div align="center"><strong>Quantity</strong></div></td>
             <td width="21%"><div align="center"><strong>Identification No.</strong></div></td>
             <td width="21%"><div align="center"><strong>Description</strong></div></td>
            <td ><div align="center"><strong>Rate</strong></div></td>
            <td width="20%"><div align="center"><strong>Amount</strong></div></td>
          </tr>
           <?php $siv=@$rs_v['siv'];
		        $res_tax=@mysqli_query($con, "select * from assettb where siv='$siv' order by identify_string");
				while($rs_tax=@mysqli_fetch_array($res_tax))
				{
					++$sn;
				if	($rs_tax['qty']== 0) {$qty = 1;} else {$qty = $rs_tax['qty'];}
				$amount = $rs_tax['amount'] * $qty;
				$total = $total + $amount ;
		 ?>
          <tr>
         	 <td  align="center"><?php echo $sn;?></td>
             <td align="center"><?php echo $qty;?></td>
             <td  align="center"><?php echo $rs_tax['identify_string'];?></td>
             <td align="center"><?php echo $rs_tax['descritption'];?></td>
             <td align="center"><?php echo number_format($rs_tax['amount'],2);?></td>
             <td align="center"><?php echo number_format($amount,2);?></td>
    
          </tr>
          <?php } //end of while ?>
          <tr>
            <td colspan="2"><b>Total (=N=) </b></td>
            <td colspan="4" align="right"><b><?php echo number_format($total,2);?></b></td>
          </tr>
          <?php if ($received_by == 'Yes') {?>
          <tr>
          <td colspan="6" >Account to be Debitted : <b><?php echo $rs_v['account_debit']; ?></b></td>
          </tr>
          <tr>
          <td colspan="6" >Internal Order No : <b><?php echo $rs_v['internal_no'];?></b></td>
          </tr>
          <tr>
          <td colspan="6" >Requisition No : <b><?php echo $rs_v['requisition_no']; ?></b></td>
          </tr>
          <?php };?>
          <tr>
            <td colspan="1"><b>Checked By: </b></td>
            <td colspan="2" >Name : <b><?php echo $name; ?></b></td>
            <td colspan="3" > Sign &amp; Date : <b><?php echo @date('d/m/Y',strtotime($rs_v['entry_date']));?></b></td>
          </tr>
          <?php if ($received_by == 'Yes') {?>
          <tr>
            <td colspan="1"><b>Stores Received By: </b></td>
            <td colspan="2" >Name : <b><?php echo $name2; ?></b></td>
            <td colspan="3" >Sign &amp; Date : <b><?php echo @date('d/m/Y',strtotime($rs_v['checked_date']));?></b></td>
          </tr>
          <?php };?>
          <tr>
            <td colspan="1"><b>Certified By: </b></td>
            <td colspan="2" >Name : <b></b></td>
            <td colspan="3" >Sign &amp; Date : <b></b></td>
          </tr>
        </table>
        <p align="center"><a href="javascript:window.print();">Print</a></p>
<?php };?>
</body>
</html>