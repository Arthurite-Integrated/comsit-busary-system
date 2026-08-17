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
	//@require_once "myclass_m.php";
	//@$bursary = new myclass_m();
	
	//@$udept = $bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
	
	?>
<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Store Report</title>
<style>
body {
font : 0.6em "Arial", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}

/* start - table */
table {
	border-collapse: collapse;
	margin: 10px;
		
}
th strong {
	color: #fff;
}
th {
	background: #0F3252;
	height: 29px;
	padding-left: 12px;
	padding-right: 12px;
	color: #FFF;
	text-align: center; /*left;*/
	border-left: 1px solid #B6D59A;
	border-bottom: solid 2px #FFF;
}
tr {
	height: 30px;
}
td {
	padding-left: 11px;
	padding-right: 11px;
	border-left: 1px solid #E8E8E8;
	border-bottom: 1px solid #DFDFDF;
}
td.first,th.first {
	border-left: 0px;
}
tr.row-a {
	background: #F8F8F8;
}
tr.row-b {
	background: #EFEFEF;		
}
input.button { 
	font: bold 12px Arial, Sans-serif; 
	height: 24px;
	margin: 0;
	padding: 2px 3px; 
	color: #FFF;
	background: #8EB50C;
	
	border-width: 1px;
  	border-style: solid;
  	border-color: #D3FFA8 #639632 #639632 #D3FFA8;
}
</style>
<?php include("required_jQuery_files.php");
include "function.php";
@require_once('class/mysqli_class.php');
@require_once('gencode.php');
$db = new Database();
$db->connect();
@require_once "myclass_m.php";
	@$bursary = new myclass_m();
/*if($login_status!='staff')
   {
	   echo "<script>location='login.php';</script>";
   }
if(!isset($_REQUEST['id']))
{
    echo "<script>alert('Access Denied');window.close();</script>";
}*/
//$id=@json_decode(base64_decode($_REQUEST['id']));


?>
</head>
<body>
<center>
<?php
$color = "#E5E2C9";
$color1 = "#FFF";
$colortest = 0;
$prod_id=$_REQUEST['prod_id'];$from2=$_REQUEST['from'];$to2=$_REQUEST['to'];$location=$_REQUEST['location'];
$cat_type=$_REQUEST['cat_type'];$asset_cat=$_REQUEST['asset_cat'];
$from_date=date('Y-m-d',strtotime($from2));
$to_date=date('Y-m-d',strtotime($to2));
if($prod_id=='hi')
	 {
		echo "<script>alert('Error: You Must select Type'); window.close();</script>";
		exit;
	 }
	//$asset_name = $bursary->get_any_value('type_id','asset_typetb','asset_code',$asset_cat);
	//$cat_name = $bursary->get_any_value('cat_id','asset_categorytb','cat_code',$cat_type);
	//$loc_name = $bursary->get_any_value('unit','locationtb','loc_code',$location);
	// $prod_name = $bursary->get_any_value('prod_name','fix_producttb','prod_id',$prod_id);	

//if ($from_date != '' && $to_date != '')
//{
	$sn=0; $total1 = 0; $qtys= 0;
	//$sql="select * from assettb where acq_date between '$from_date' and '$to_date'";
	$sql= mysqli_query($con, "select * from fix_product_inflow where prod_id = '$prod_id' order by supply_date") or die( mysqli_error($con));
	 $prod_name = $bursary->get_any_value('prod_name','fix_producttb','prod_id',$prod_id);	
	if( mysqli_num_rows($sql)>0)
	{
		$tb="<table border='0' > <tr><th colspan ='7'><p align='centre'>STOCK CARD</th></tr>";
		$tb.="<tr><th colspan ='7'><p align='left'>Details For $prod_name Inflow</th></tr>";
 
 $tb.="<tr><th>S/N</th><th>Product Name</th><th>Supplier</th><th>Rate</th><th>Quantity</th><th>Date Supply</th><th>Total</th></tr>";
		//} elseif ($type_name = 'Consumable') {
 //$tb.="<tr><th>S/N</th><th>DESCRIPTION</th><th>IDENTIFICATION NUMBER</th><th>ACQ.DATE</th><th>QTY</th><th>LOCATION</th><th>COST </th></tr>";
	//	}
	while($rs_v=@mysqli_fetch_array($sql))
		  { 
			  ++$sn;
			  $prod_id=$rs_v['prod_id'];
			 $prod_name = $bursary->get_any_value('prod_name','fix_producttb','prod_id',$prod_id);	
			  $rate=$rs_v['rate'];
			  $qty=$rs_v['qty'];
			   $sup_id=$rs_v['sup_id'];
			   $qtys = $qtys+$qty;
			  $amount = $rate * $qty;
		 $sup_name = $bursary->get_any_value('sup_name','suppliertb','sup_id',$sup_id);		 
			   $supply_date=$rs_v['supply_date'];
			  
//if ($type_name = 'Fixed Asset'){
	// $tb.="<tr><td>$sn</td><td>$descritption</td><td>$identify_string</td><td>$acq_date</td><td>$loc</td><td>".number_format($amount,2)."</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
//} else {
	 $tb.="<tr><td>$sn</td><td>$prod_name</td><td>$sup_name</td><td>".number_format($rate,2)."</td><td>$qty</td><td>$supply_date</td><td>".number_format($amount,2)."</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
//} 
		 $total1 = $total1 + $amount;
		
		  }//end of while
		  $tb.="<tr><td></td><td><b>TOTAL</p></b></td><td></td><td></td><td><b>$qtys</b></td><td></td><td><b>N".@number_format($total1,2)."</b></td></tr>";
		  $tb.="</table>";
		  echo $tb;
	} 
	else { 
	echo "<script>alert('Alert: No Record for OutFlow'); window.close();</script>";
		
		}
///////////////////////////////////to find the used places of this product///////////////////
$sn2=0; $total2 = 0; $rem = 0;
	//$sql="select * from assettb where acq_date between '$from_date' and '$to_date'";
	$outfl= mysqli_query($con, "select * from fix_product_outflow where prod_id = '$prod_id' order by supply_date") or die( mysqli_error($con));
	 $prod_name = $bursary->get_any_value('prod_name','fix_producttb','prod_id',$prod_id);	
	if( mysqli_num_rows($outfl)>0)
	{
		$tb2="<table border='0' > <tr><th colspan ='8'><p align='left'>Details For $prod_name OutFlow</th></tr>";
 $tb2.="<tr><th>S/N</th><th>Product Name</th><th>Invoice No</th><th>Location</th><th>Rate</th><th>Quantity</th><th>Date Collected</th><th>Total</th></tr>";
	while($rs_v=@mysqli_fetch_array($outfl))
		  { 
			  ++$sn2;
			  $prod_id=$rs_v['prod_id'];
			 $prod_name = $bursary->get_any_value('prod_name','fix_producttb','prod_id',$prod_id);	
			  $rate=$rs_v['rate'];
			  $qty=$rs_v['qty'];
			 $rem = $rem + $qty;
			  $invoice_no=$rs_v['invoice_no'];
			   $identification_string=$rs_v['identification_string'];
			  $amount = $rate * $qty;
$mov=@mysqli_query($con, "select loc_code from fix_movementtb where identification_string='$identification_string' order by identification_string desc limit 1"); 
$mo_d=@mysqli_fetch_array($mov); $loc_code=$mo_d['loc_code'];
$location_name = get_location($loc_code);
		 $sup_name = $bursary->get_any_value('sup_name','suppliertb','sup_id',$sup_id);		 
			   $supply_date=$rs_v['supply_date'];
	 $tb2.="<tr><td>$sn2</td><td>$prod_name</td><td>$invoice_no</td><td>$location_name</td><td>".number_format($rate,2)."</td><td>$qty</td><td>$supply_date</td><td>".number_format($amount,2)."</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
//} 
		 $total2 = $total2 + $amount;
		  
		$remain = $qtys - $rem;
		  }//end of while
		  $tb2.="<tr><td></td><td></td><td><b>TOTAL</p></b></td><td></td><td></td><td></td><td></td><td><b>N".@number_format($total2,2)."</b></td></tr>";
		   $tb2.="<tr><td colspan = 3><b>Total No of Product Remains</p></b></td><td></td><td></td><td></td><td></td><td><b>$remain</b></td></tr>";
		  $tb2.="</table>";
		  echo $tb2;
	} 
	else { 
	echo "<b>No Record for OutFlow</b>";
		exit;
		}		


//}
?>
</center>
</body>
<div id="tooplate_footer_wrapper">
	<?php // include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->
</html>