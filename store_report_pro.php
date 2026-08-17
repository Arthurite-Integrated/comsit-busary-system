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
$type=$_REQUEST['type'];$from2=$_REQUEST['from'];$to2=$_REQUEST['to'];$location=$_REQUEST['location'];
$cat_type=$_REQUEST['cat_type'];$asset_cat=$_REQUEST['asset_cat'];
$from_date=date('Y-m-d',strtotime($from2));
$to_date=date('Y-m-d',strtotime($to2));
if($type=='hi')
	 {
		echo "<script>alert('Error: You Must select Type'); window.close();</script>";
		exit;
	 }
	 if($type=='No')
	 {
		$type_name = 'Consumable';
	 } else {$type_name = 'Fixed Asset';}

	$asset_name = $bursary->get_any_value('type_id','asset_typetb','asset_code',$asset_cat);
	$cat_name = $bursary->get_any_value('cat_id','asset_categorytb','cat_code',$cat_type);
	$loc_name = $bursary->get_any_value('unit','locationtb','loc_code',$location);	

//if ($from_date != '' && $to_date != '')
//{
	$sn=0; $total1 = 0;
	//$sql="select * from assettb where acq_date between '$from_date' and '$to_date'";
	if ($type=='No')
	{
		
	$sqlc= mysqli_query($con, "select distinct prod_id from assettb where (acq_date >= '$from_date' and acq_date <= '$to_date') and fix_con = '$type' ") or die( mysqli_error($con));
	
		if( mysqli_num_rows($sqlc)<=0) {echo "<script>alert('Alert: No record to display'); window.close();</script>"; }
	
	$tb="<table border='0'  <tr><th colspan ='10'><p align='left'>ASSET REGISTER AS AT : ".date('F j, Y, g:i a')." </th></tr>";
	if($location!='') $tb.="<tr><th colspan ='10'><p align='left'>ASSETS UNDER DEPT: $loc_name </th></tr>";
	if($cat_type!='') $tb.="<tr><th colspan ='10'><p align='left'>ASSETS UNDER CATEGORY : $cat_type - $cat_name</th></tr>";
	if($asset_cat!='') $tb.="<tr><th colspan ='10'><p align='left'>ASSETS WITH ASSET TYPE: $asset_cat - $asset_name </th></tr>";
	if($type_name!='') $tb.="<tr><th colspan ='10'><p align='left'>TYPE: $type_name </th></tr>";
		$tb.="<tr><th colspan ='10'><p align='left'>ASSETS WITH ACQ. DATE: $from_date - $to_date </th></tr>";
		//if ($type_name = 'Fixed Asset'){
 $tb.="<tr><th>S/N</th><th>PRODUCT NAME</th><th>DESCRIPTION</th><th>IDENTIFICATION NUMBER</th><th>ACQ.DATE</th><th>QTY</th><th>LOCATION</th><th>IPSAS</th><th>REM.</th><th>COST </th></tr>";
		 while($rs_cv=@mysqli_fetch_array($sqlc))
		  { 
			$prod_id = $rs_cv['prod_id'];
		  
	if ($type=='No' and $cat_type!='' ) {
		
	$sql="select * from assettb where (acq_date >= '$from_date' and acq_date <= '$to_date') and fix_con = '$type' and prod_id = '$prod_id' and asset_category='$cat_type'";
	$sql.=" order by acq_date"; 
	}
	if ($type=='No' and $cat_type =='' ) {
		
	$sql="select * from assettb where (acq_date >= '$from_date' and acq_date <= '$to_date') and fix_con = '$type' and prod_id = '$prod_id'";
	$sql.=" order by acq_date"; 
	}
	/*if($cat_type!='') {$sql.=" and asset_category='$cat_type'";}
	if($asset_cat!='') {$sql.=" and asset_type='$asset_cat'";}
	if($location!='') {$sql.=" and location='$location'";}	*/ 
	if ($type!='No')
	{
	$sql="select * from assettb where (acq_date >= '$from_date' and acq_date <= '$to_date') and fix_con = '$type' ";
	
	if($cat_type!='') {$sql.=" and asset_category='$cat_type'";}
	if($asset_cat!='') {$sql.=" and asset_type='$asset_cat'";}
	if($location!='') {$sql.=" and location='$location'";}	
	$sql.=" order by acq_date"; 
	}

	$res_p= mysqli_query($con, $sql) or die( mysqli_error($con));
	
	  $qty_u = 0;
	while($rs_v=@mysqli_fetch_array($res_p))
		  { 
			  ++$sn;
			  
			  $identify_string=$rs_v['identify_string'];
			  $descritption=$rs_v['descritption'];
			  $amounts=$rs_v['amount'];
			  if ($type!='No'){
			  $prod_id=$rs_v['prod_id'];}
			  $asset_category=$rs_v['asset_category'];
			  $qty=$rs_v['qty'];
			  if ($qty == 0){
			  $qty_use = 1;} 
			  else 
			  { 
				  $qty_use  = $qty;}
			  $amount = $amounts * $qty_use;
			  $disposal=$rs_v['disposal'];
			   $acq_date=$rs_v['acq_date'];
			   $ipsas_code = $bursary->get_any_value('ipsas_code','asset_categorytb','cat_code',$asset_category);
			   $bal = $bursary->get_any_value('qty','fix_product_inflow','prod_id',$prod_id);
			    $prodname = $bursary->get_any_value('prod_name','fix_producttb','prod_id',$prod_id);
			   $all =  mysqli_query($con, "select SUM(qty) As tot from assettb where prod_id = '$prod_id'");
			          $rs_all=@mysqli_fetch_array($all); $tot=$rs_all['tot'];   
			   
			   $rem = $bal - $tot;
			   $buy_year = explode('-',$acq_date);$buy_year = $buy_year [0];
			   if ($type_name == 'Fixed Asset'){
$mov=@mysqli_query($con, "select loc_code from fix_movementtb where identification_string='$identify_string' order by identification_string desc limit 1"); 
$mo_d=@mysqli_fetch_array($mov); $loc_code=$mo_d['loc_code'];
$asst=@mysqli_query($con, "select * from locationtb where loc_code='$loc_code'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit']; $dept=$rs_d['dept'];$room_no=$rs_d['room_no'];
$loc = $unit=$rs_d['dept']." (".$unit=$rs_d['unit']." ) ".$room_no;} else {
$location=$rs_v['location'];
$asst=@mysqli_query($con, "select * from locationtb where loc_code='$location'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit']; $dept=$rs_d['dept'];
$loc = $unit=$rs_d['dept']." (".$unit=$rs_d['unit']." ) ".$room_no;
}
    $qty_u = $qty_u + $qty_use;
	  $rem_use = $bal - $qty_u; //$qty_use($bal($qty_u))
	 $tb.="<tr><td>$sn</td><td>$prodname</td><td>$descritption</td><td>$identify_string</td><td>$acq_date</td><td>$qty_use($bal)</td><td>$loc</td><td>$ipsas_code</td><td>$rem_use</td><td>".number_format($amount,2)."</td></tr>";
		 $total1 = $total1 + $amount;
		  }//end of while
		}
	//}
		  $tb.="<tr><td></td><td></td><td><b>TOTAL</p></b></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>N".@number_format($total1,2)."</b></td></tr>";
		  $tb.="</table>";
		  echo $tb;
 
	
}

///////////////////////////NOT NO

if ($type!='No')
	{
		
	$sql="select * from assettb where (acq_date >= '$from_date' and acq_date <= '$to_date') and fix_con = '$type' ";
	
	if($cat_type!='') {$sql.=" and asset_category='$cat_type'";}
	if($asset_cat!='') {$sql.=" and asset_type='$asset_cat'";}
	if($location!='') {$sql.=" and location='$location'";}	
	$sql.=" order by acq_date"; 

	$res_p= mysqli_query($con, $sql) or die( mysqli_error($con));
	
	if( mysqli_num_rows($res_p)>0)
	{
		$tb="<table border='0'  <tr><th colspan ='9'><p align='left'>ASSET REGISTER AS AT : ".date('F j, Y, g:i a')." </th></tr>";
	if($location!='') $tb.="<tr><th colspan ='9'><p align='left'>ASSETS UNDER DEPT: $loc_name </th></tr>";
	if($cat_type!='') $tb.="<tr><th colspan ='9'><p align='left'>ASSETS UNDER CATEGORY : $cat_type - $cat_name</th></tr>";
	if($asset_cat!='') $tb.="<tr><th colspan ='9'><p align='left'>ASSETS WITH ASSET TYPE: $asset_cat - $asset_name </th></tr>";
	if($type_name!='') $tb.="<tr><th colspan ='9'><p align='left'>TYPE: $type_name </th></tr>";
		$tb.="<tr><th colspan ='9'><p align='left'>ASSETS WITH ACQ. DATE: $from_date - $to_date </th></tr>";
		$tb.="<tr><th>S/N</th><th>PRODUCT NAME</th><th>DESCRIPTION</th><th>IDENTIFICATION NUMBER</th><th>ACQ.DATE</th><th>QTY</th><th>LOCATION</th><th>IPSAS </th><th>COST </th></tr>";
	
	
	while($rs_v=@mysqli_fetch_array($res_p))
		  { 
			  ++$sn;
			  
			  $identify_string=$rs_v['identify_string'];
			  $descritption=$rs_v['descritption'];
			  $amounts=$rs_v['amount'];
			 
			  $prod_id=$rs_v['prod_id'];
			  $asset_category=$rs_v['asset_category'];
			  $qty=$rs_v['qty'];
			  if ($qty == 0){
			  $qty_use = 1;} 
			  else 
			  { 
				  $qty_use  = $qty;}
			  $amount = $amounts * $qty_use;
			  $disposal=$rs_v['disposal'];
			   $acq_date=$rs_v['acq_date'];
			   $ipsas_code = $bursary->get_any_value('ipsas_code','asset_categorytb','cat_code',$asset_category);
			   $bal = $bursary->get_any_value('qty','fix_product_inflow','prod_id',$prod_id);
			    $prodname = $bursary->get_any_value('prod_name','fix_producttb','prod_id',$prod_id);
			   $all =  mysqli_query($con, "select SUM(qty) As tot from assettb where prod_id = '$prod_id'");
			          $rs_all=@mysqli_fetch_array($all); $tot=$rs_all['tot'];   
			   
			   $rem = $bal - $tot;
			   $buy_year = explode('-',$acq_date);$buy_year = $buy_year [0];
			   if ($type_name == 'Fixed Asset'){
$mov=@mysqli_query($con, "select loc_code from fix_movementtb where identification_string='$identify_string' order by identification_string desc limit 1"); 
$mo_d=@mysqli_fetch_array($mov); $loc_code=$mo_d['loc_code'];
$asst=@mysqli_query($con, "select * from locationtb where loc_code='$loc_code'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit']; $dept=$rs_d['dept'];$room_no=$rs_d['room_no'];
$loc = $unit=$rs_d['dept']." (".$unit=$rs_d['unit']." ) ".$room_no;} else {
$location=$rs_v['location'];
$asst=@mysqli_query($con, "select * from locationtb where loc_code='$location'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit']; $dept=$rs_d['dept'];
$loc = $unit=$rs_d['dept']." (".$unit=$rs_d['unit']." ) ".$room_no;
}
    $qty_u = $qty_u + $qty_use;
	  $rem_use = $bal - $qty_u; //$qty_use($bal($qty_u))
	 $tb.="<tr><td>$sn</td><td>$prodname</td><td>$descritption</td><td>$identify_string</td><td>$acq_date</td><td>$qty_use</td><td>$loc</td><td>$ipsas_code</td><td>".number_format($amount,2)."</td></tr>";
		 $total1 = $total1 + $amount;
		  }//end of while
		
	//}
		  $tb.="<tr><td></td><td><b>TOTAL</p></b></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>N".@number_format($total1,2)."</b></td></tr>";
		  $tb.="</table>";
		  echo $tb;
 
	
}
else { 
	echo "<script>alert('Alert: No record to display'); window.close();</script>";
		
		}
}

//}
?>
</center>
</body>
<div id="tooplate_footer_wrapper">
	<?php // include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->
</html>