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
<title>Fixed Asset Print</title>
<style>
body {
font : 0.6em "Arial", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
table {
	border-collapse: collapse;
	/*margin: 5px;*/
		
}


</style>
</head>

<body >
<?php
	$sn=0;
	$total = 0;
	$grn=base64_decode($_REQUEST['p']);
	//$pvno="FEBRUARY/2015/0001";
	
	//$val_str=explode("***",get_company());
	if($grn=='') { echo "<font color='red'><b>No search criteria provided</b></font>"; exit; }
	$res_v= mysqli_query($con, "select * from grn_sivtb where grn='$grn' ");
	$rs_v=@mysqli_fetch_array($res_v);
	$checked_by = $rs_v['checked_by'];
   if(@mysqli_num_rows($res_v)>=1)
    { 
	$by2 =  $rs_v['checked_by_name'];
	$by =  $rs_v['entry_by'];
	$siv =  $rs_v['siv'];
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
 <?php $grn=@$rs_v['grn'];
 $res_taxs=@mysqli_query($con, "select * from assettb where grn='$grn' limit 1");
	$rs_taxs=@mysqli_fetch_array($res_taxs);	
	 $sup_no = $rs_taxs['sup_id']; 
	$location = $rs_taxs['location'];
	$invoice_no = $rs_taxs['invoice_no'];
	$asst=@mysqli_query($con, "select * from locationtb where loc_code='$location'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit'];  $unit=$rs_d['dept'];
 $loc = $unit=$rs_d['dept']." (".$unit=$rs_d['unit']." ) ";
$supp=@mysqli_query($con, "select * from suppliertb where sup_id ='$sup_no' ");
	$supps= mysqli_fetch_array($supp);
		
 ?>
<table width="90%" border="1" cellpadding="0" cellspacing="0" align="center" >
<tr> <td  colspan="8"><table border="0" align="center" width="100%"> <tr><td align="center" colspan="8"><b> UNIVERSITY OF ILORIN, ILORIN <br>
  <img src='images/logo.jpg' width='50' hight='50'>  <br> 
  BURSARY DEPARTMENT <br> STORES UNIT <br> FIXED ASSET REGISTRATION FORM</b></td></tr>
<tr><td  colspan="8"><b>Department/Unit:</b> <?php echo $loc;?></td></tr>
<tr><td  colspan="8"><b>Cash Purchase Invoice :</b><?php echo $invoice_no;?></td></tr>
<tr><td  colspan="8"><b>Supplier:</b><?php echo $supps['sup_name'];?></td></tr>
<tr><td  colspan="8"><b>Address: </b> <?php echo $supps['sup_address'];?></td></tr>
<tr><td  colspan="8"><b>Date entered into Fixed Asset Register: </b> <?php echo @date('d/m/Y',strtotime($rs_v['entry_date']));?></td></tr>
<tr><td  colspan="3"><b>Date of Preparation:</b><?php echo @date('d/m/Y',strtotime($rs_v['entry_date']));?></td><td colspan="3"> <b>GRN:</b> <?php echo $grn;?></td><td colspan="2"><b>SIV:</b><?php echo $siv;?></td></tr></table></td></tr>
          <tr>
            <td><strong>S/NO</strong></td>
             <td ><strong>Asset Name</strong></td>
            <td ><strong>Manufacturer's Asset Serial No.</strong></td>
            <td ><strong>Acquisition Date</strong></td>
             <td ><strong>Acquisition Cost</strong></td>
            <td ><strong>Location</strong></td>
            <td ><strong>Audit Depts Remark</strong></td>
            <td ><strong>Fixed Asset No.</strong></td>
          </tr>
           <?php $grn=@$rs_v['grn'];
		        $res_tax=@mysqli_query($con, "select * from assettb where grn='$grn' and fix_con = 'Yes' order by identify_string");
				while($rs_tax=@mysqli_fetch_array($res_tax))
				{
					++$sn;
				if	($rs_tax['qty']== 0) {$qty = 1;} else {$qty = $rs_tax['qty'];}
				$amount = $rs_tax['amount'] * $qty;
				$total = $total + $amount ;
				$identify_string = $rs_tax['identify_string'];
				$prod_id = $rs_tax['prod_id'];
			$prodc=@mysqli_query($con, "select prod_name from fix_producttb where prod_id='$prod_id' limit 1"); 
				$prodc2=@mysqli_fetch_array($prodc); $prod_name=$prodc2['prod_name'];	
				$mov=@mysqli_query($con, "select loc_code from fix_movementtb where identification_string='$identify_string' order by identification_string desc limit 1"); 
				$mo_d=@mysqli_fetch_array($mov); $current=$mo_d['loc_code'];
				$asst=@mysqli_query($con, "select * from locationtb where loc_code='$current'"); 
				$rs_d=@mysqli_fetch_array($asst); 
				$loc2 = $unit=$rs_d['dept']." (".$unit=$rs_d['unit']." ) ".$rs_d['room_no'];
		 ?>
          <tr>
         	 <td  ><?php echo $sn;?></td>
             <td ><?php echo $prod_name." ".$rs_tax['descritption'];?></td>
             <td ><?php echo $rs_tax['serial'];?></td>
             <td ><?php echo @date('d/m/Y',strtotime($rs_tax['acq_date'])) ;?></td>
             <td ><?php echo number_format($rs_tax['amount'],2);?></td>
             <td ><?php echo $loc2;?></td>
             <td ><?php echo " ";?></td>
             <td ><?php echo $rs_tax['identify_string'];?></td>
            <!-- <td align="center"><?php echo number_format($amount,2);?></td> -->
    
          </tr>
          <?php } //end of while ?>
          <tr>
            <td colspan="2"><b>Total (=N=) </b></td>
            <td colspan="6" align="right"><b><?php echo number_format($total,2);?></b></td>
          </tr>
          <tr><td colspan="8"><table border="0"> <tr>
            <td colspan="4"><b>NB:</b> A form should be used for one item. To be filled in Triplicate. </td>
            <td colspan="4" ><u>CENTRAL STORES USE</u> </br> I certify that this Asset has been recorded in the Central-Store Register as received</br> 
            and issued out to DEPT/UNIT of .... The Asset eg. No... has also been inscribed on it.</br><b> ANY STORE OFFICER WHO MAKES
            FALSE CLAIM SHALL BE DISMISSED FROM SERVICE.</b></td>
          </tr>
          <?php //if ($checked_by == 'Yes') {?>
          <tr>
            <td colspan="2"><b>HOD: </b></td>
            <td colspan="3" >Name : <b><?php //echo $name2; ?></b></td>
            <td colspan="3" >Sign &amp; Date : <b><?php //echo @date('d/m/Y',strtotime($rs_v['checked_date']));?></b></td>
          </tr>
           <tr>
            <td colspan="2"><b>Chief Stores Officer: </b></td>
            <td colspan="3" >Name : <b><?php //echo $name2; ?></b></td>
            <td colspan="3" >Sign &amp; Date : <b><?php //echo @date('d/m/Y',strtotime($rs_v['checked_date']));?></b></td>
          </tr>
           <tr>
            <td colspan="2"><b>Central Stores Officer: </b></td>
            <td colspan="3" >Name : <b><?php //echo $name2; ?></b></td>
            <td colspan="3" >Sign &amp; Date : <b><?php //echo @date('d/m/Y',strtotime($rs_v['checked_date']));?></b></td>
          </tr>
          <tr>
            <td colspan="8" align="center"><a href="javascript:window.print();">Print</a> </td>
          </tr></table></td></tr>
          <?php //};?>
        </table>
        
<?php };?>
</body>
</html>