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
<title>Fixed Asset Report</title>
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
$sne = '';
$color = "#E5E2C9";
$color1 = "#FFF";
$colortest = 0;
$dispose=$_REQUEST['dispose'];
$year=$_REQUEST['year'];$from2=$_REQUEST['from'];$to2=$_REQUEST['to'];$location=$_REQUEST['location'];
$cat_type=$_REQUEST['cat_type'];$asset_cat=$_REQUEST['asset_cat'];
$from_date=date('Y-m-d',strtotime($from2));
$to_date=date('Y-m-d',strtotime($to2)); $to_date2=strtotime($to2);


if ($dispose == 'No' || $dispose == '' )	
{
	$asset_name = $bursary->get_any_value('type_id','asset_typetb','asset_code',$asset_cat);
	$cat_name = $bursary->get_any_value('cat_id','asset_categorytb','cat_code',$cat_type);
	//$loc_name = $bursary->get_any_value('unit','locationtb','loc_code',$location);	
//$loc_name = 	

//if ($from_date != '' && $to_date != '')
//{
	$sn=0; $total1 = 0; $total2 = 0; $total3 = 0;$total4 = 0;$total5 = 0;$total6 = 0;
	//$sql="select * from assettb where acq_date between '$from_date' and '$to_date'";
	$sql="select * from assettb where (acq_date >= '$from_date' and acq_date <= '$to_date') and fix_con = 'Yes' ";
	if($cat_type!='') $sql.=" and asset_category='$cat_type'";
	if($asset_cat!='') $sql.=" and asset_type='$asset_cat'";
	if($location!='') $sql.=" and location='$location'";	
	$sql.=" order by acq_date";
	//echo $sql;
	$res_p= mysqli_query($con, $sql) or die( mysqli_error($con));
	//echo  mysqli_num_rows($res_p);
	if( mysqli_num_rows($res_p)>0)
	{
		$tb="<table border='0'  <tr><th colspan ='14'><p align='left'>ASSET REGISTER AS AT : ".date('F j, Y, g:i a')." </th></tr>";
	if($location!='') $tb.="<tr><th colspan ='14'><p align='left'>ASSETS UNDER DEPT: $loc_name </th></tr>";
	if($cat_type!='') $tb.="<tr><th colspan ='14'><p align='left'>ASSETS UNDER CATEGORY : $cat_type - $cat_name</th></tr>";
	if($asset_cat!='') $tb.="<tr><th colspan ='14'><p align='left'>ASSETS WITH ASSET TYPE: $asset_cat - $asset_name </th></tr>";
		$tb.="<tr><th colspan ='14'><p align='left'>ASSETS WITH ACQ. DATE: $from_date - $to_date </th></tr>";
 $tb.="<tr><th>S/N</th><th>DESCRIPTION</th><th>IDENTIFICATION NUMBER</th><th>ACQ.DATE</th><th>LOCATION</th><th>COST B/F</th><th>ADDITIONS</th><th>QTY</th><th>UNIT PRICE</th><th>TOTAL COST</th><th>CURR.DEPR</th><th>ACCUM.DEPR</th><th>DISPOSAL</th><th>NBV</th></tr>";
 
 			$file_path="upload_files/";
		   $fname="asset_download".date(Ymd).date('h:s:i a');
		   $fname=str_replace("/","",$fname);
		   $fname=str_replace(":","",$fname); $fname=str_replace(" ","",$fname); $fname.=".csv";
 			$file_path.=$fname;
		   $fp=@fopen($file_path,'w+');
		   $fw=@fwrite($fp,"SNO,DESCRIPTION,IDENTIFICATIONNUMBER,ACQ.DATE,LOCATION,COSTBF,ADDITIONS,QTY,UNITPRICE,TOTALCOST,CURR.DEPR,ACCUM.DEPR,DISPOSAL,NBV\n");
	while($rs_v=@mysqli_fetch_array($res_p))
		  { 
			  ++$sn;
			  $identify_string=$rs_v['identify_string'];
			  $descritption=$rs_v['descritption'];
			  $amount=$rs_v['amount']; $qty=$rs_v['qty']; $amountu=$amount/$qty;
			  $disposal=$rs_v['disposal'];
			  $qty=$rs_v['qty'];
			  $asset_category=$rs_v['asset_category'];
			  $location=$rs_v['location'];
				if ($disposal == 'Yes')
				{$dis = 'Yes';}else {$dis = 'No';}
			   $acq_date=$rs_v['acq_date'];
			   $acdate = strtotime($acq_date);
			  $buy_year = explode('-',$acq_date); $buy_year = $buy_year [0]; 
$mov=@mysqli_query($con, "select loc_code from fix_movementtb where identification_string='$identify_string' order by identification_string desc limit 1"); 
$mo_d=@mysqli_fetch_array($mov); $loc_code=$mo_d['loc_code'];
$asst=@mysqli_query($con, "select * from locationtb where loc_code='$loc_code'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit']; $dept=$rs_d['dept'];$room_no=$rs_d['room_no'];
$units= $unit." (".$dept.") ".$room_no;
$dep=@mysqli_query($con, "select * from asset_depreciation where identify_string='$identify_string'"); 
$de_d=@mysqli_fetch_array($dep); $acc_depr=$de_d['acc_depr'];   $no_years=$de_d['no_years']; 
$acc_deprm = round(($acc_depr/12),2);

$dep_a=@mysqli_query($con, "select * from useful_lifetb where asset_type='$asset_category'"); 
$de_da=@mysqli_fetch_array($dep_a); $scrap_value=$de_da['scrap_value']; 

		$scrap_values = $scrap_value * $qty; 

		$diff = abs($to_date2 - $acdate);
		$yearsss = floor($diff/(365*60*60*24)); 
		$mtotal = floor(($diff - $yearsss * 365*60*60*24)/(30*60*60*24));
		$mnts = (($yearsss * 12) + $mtotal); 
		$years = $year - $buy_year + 1;
			$months = ($no_years *12);
		if (($yearsss <= $no_years) and ($mnts<=$months))
		
		//{ $acc_d2 = $acc_depr * $years; $cur_dep = $acc_depr; } else {$acc_d2= $amount - 10; $cur_dep = 0;} ($acc_deprm * $mnts)
		{ $acc_d2 = $acc_deprm * $mnts; $cur_dep = $acc_deprm; } else {$acc_d2= $amount - $scrap_values; $cur_dep = 0;}

	if ($year == $buy_year ){
	//$amt = number_format($amount,2);
	$amt = $amount;
	$amt_add = '-';
	} else { $amt = '-';  $amt_add = $amount;  }

if ($disposal == 'Yes')
{ $nbv2 = 0; } else {$nbv = (($amount - $acc_d2));
$nbv2 = number_format($nbv,2);
$nbv3 = $nbv;
}

	//$tb.="<tr><td>$sn</td><td>$descritption</td><td>$identify_string</td><td>$acq_date</td><td>$unit</td><td>$amount</td><td>0</td><td>$acc_d2</td><td>$cur_dep</td><td>$dis</td><td>$nbv</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a> $amountu
	 $tb.="<tr><td>$sn</td><td>$descritption</td><td>$identify_string</td><td>$acq_date</td><td>$location</td><td>".number_format($amt_add,2)."</td><td>".number_format($amt,2)."</td><td>$qty</td><td>".number_format($amountu,2)."</td><td>".number_format($amount,2)."</td><td>".number_format($cur_dep,2)."</td><td>".number_format($acc_d2,2)."</td><td>$dis</td><td>$nbv2</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
		 
		$total1 = $total1 + $amt; 
		 $total2 = $total2 + $acc_d2;
		  $total3 = $total3 + $cur_dep;
		 $total4 = $total4 + $nbv;
		 $total5 = $total5 + $amt_add;
		 $total6 = $total6 + $amount;
		 
		 $fw=@fwrite($fp,"$sn,$descritption,$identify_string,$acq_date,$location,$amt_add,$amt,$qty,$amountu,$amount,$cur_dep,$acc_d2,$dis,$nbv3\n");
		  }//end of while <td><b>N".@number_format($total5,2)."</b></td>
		  $tb.="<tr><td></td><td><b>TOTAL</p></b></td><td></td><td></td><td></td><td><b>N".number_format($total5,2)."</b></td><td><b>N".number_format($total1,2)."</b></td><td></td><td></td><td><b>N".@number_format($total6,2)."</b></td><td><b>N".@number_format($total2,2)."</b></td><td><b>N".@number_format($total3,2)."</b></td><td></td><td><b>N".@number_format($total4,2)."</b></td></tr>";
		  
		  $tb.="</table>";
		  echo $tb;
		  $fw=@fwrite($fp,"$sne,$sne,$sne,$sne,$sne,$total5,$sne,$sne,$total1,$sne,$total6,$total3,$sne,$total4\n");

		  @fclose($fp);
		   
		   echo "<br/><center><font color='red'><b>Click <a href='$file_path'>here</a> to download the excel file in CSV format</b></font></center>";
	} 
	else {
		
echo "<script>alert('Alert: No record to displaysss'); window.close();</script>";
		
		}
	
}
	if ($dispose == 'Yes')	
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
{
$asset_name = $bursary->get_any_value('type_id','asset_typetb','asset_code',$asset_cat);
	$cat_name = $bursary->get_any_value('cat_id','asset_categorytb','cat_code',$cat_type);
	//$loc_name = $bursary->get_any_value('unit','locationtb','loc_code',$location);	
//$loc_name = 	

//if ($from_date != '' && $to_date != '')
//{
	$sn=1; $total1 = 0; $total2 = 0; $total3 = 0;$total4 = 0;$total5 = 0;$total6 = 0;
	//$sql="select * from assettb where acq_date between '$from_date' and '$to_date'";
	$sql="select * from assettb where (acq_date >= '$from_date' and acq_date <= '$to_date') and fix_con = 'Yes' ";
	if($cat_type!='') $sql.=" and asset_category='$cat_type'";
	if($asset_cat!='') $sql.=" and asset_type='$asset_cat'";
	if($location!='') $sql.=" and location='$location'";	
	$sql.=" order by acq_date";
	//echo $sql;
	$res_p= mysqli_query($con, $sql) or die( mysqli_error($con));
	//echo  mysqli_num_rows($res_p);
	if( mysqli_num_rows($res_p)>0)
	{
		echo "<table border='0'  <tr><th colspan ='14'><p align='left'>ASSET REGISTER AS AT : ".date('F j, Y, g:i a')." </th></tr>";
	if($location!='') echo "<tr><th colspan ='14'><p align='left'>ASSETS UNDER DEPT: $loc_name </th></tr>";
	if($cat_type!='') echo "<tr><th colspan ='14'><p align='left'>ASSETS UNDER CATEGORY : $cat_type - $cat_name</th></tr>";
	if($asset_cat!='') echo "<tr><th colspan ='14'><p align='left'>ASSETS WITH ASSET TYPE: $asset_cat - $asset_name </th></tr>";
		echo "<tr><th colspan ='14'><p align='left'>DISPOSABLE ASSETS WITH ACQ. DATE: $from_date - $to_date </th></tr>";
 echo "<tr><th>S/N</th><th>DESCRIPTION</th><th>IDENTIFICATION NUMBER</th><th>ACQ.DATE</th><th>LOCATION</th><th>COST B/F</th><th>ADDITIONS</th><th>QTY</th><th>UNIT PRICE</th><th>TOTAL COST</th><th>CURR.DEPR</th><th>ACCUM.DEPR</th><th>DISPOSAL</th><th>NBV</th></tr>";
 
 			$file_path="upload_files/";
		   $fname="asset_download".date(Ymd).date('h:s:i a');
		   $fname=str_replace("/","",$fname);
		   $fname=str_replace(":","",$fname); $fname=str_replace(" ","",$fname); $fname.=".csv";
 			$file_path.=$fname;
		   $fp=@fopen($file_path,'w+');
		   $fw=@fwrite($fp,"SNO,DESCRIPTION,IDENTIFICATIONNUMBER,ACQ.DATE,LOCATION,COSTBF,ADDITIONS,QTY,UNITPRICE,TOTALCOST,CURR.DEPR,ACCUM.DEPR,DISPOSAL,NBV\n");
	while($rs_v=@mysqli_fetch_array($res_p))
		  { 
			  //++$sn;
			  $identify_string=$rs_v['identify_string'];
			  $descritption=$rs_v['descritption'];
			  $amount=$rs_v['amount']; $qty=$rs_v['qty']; $amountu=$amount/$qty;
			  $disposal=$rs_v['disposal'];
			  $qty=$rs_v['qty'];
			  $asset_category=$rs_v['asset_category'];
			  $location=$rs_v['location'];
				if ($disposal == 'Yes')
				{$dis = 10;}else {$dis = 0;}
			   $acq_date=$rs_v['acq_date'];
			   $acdate = strtotime($acq_date);
			  $buy_year = explode('-',$acq_date); $buy_year = $buy_year [0]; 
$mov=@mysqli_query($con, "select loc_code from fix_movementtb where identification_string='$identify_string' order by identification_string desc limit 1"); 
$mo_d=@mysqli_fetch_array($mov); $loc_code=$mo_d['loc_code'];
$asst=@mysqli_query($con, "select * from locationtb where loc_code='$loc_code'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit']; $dept=$rs_d['dept'];$room_no=$rs_d['room_no'];
$units= $unit." (".$dept.") ".$room_no;
$dep=@mysqli_query($con, "select * from asset_depreciation where identify_string='$identify_string'"); 
$de_d=@mysqli_fetch_array($dep); $acc_depr=$de_d['acc_depr'];   $no_years=$de_d['no_years']; 
$acc_deprm = round(($acc_depr/12),2);

$dep_a=@mysqli_query($con, "select * from useful_lifetb where asset_type='$asset_category'"); 
$de_da=@mysqli_fetch_array($dep_a); $scrap_value=$de_da['scrap_value']; 

		$scrap_values = $scrap_value * $qty; 

		$diff = abs($to_date2 - $acdate);
		$yearsss = floor($diff/(365*60*60*24)); 
		$mtotal = floor(($diff - $yearsss * 365*60*60*24)/(30*60*60*24));
		$mnts = (($yearsss * 12) + $mtotal); 
		$years = $year - $buy_year + 1;
			$months = ($no_years *12);
		if (($yearsss <= $no_years) and ($mnts<=$months))
		
		//{ $acc_d2 = $acc_depr * $years; $cur_dep = $acc_depr; } else {$acc_d2= $amount - 10; $cur_dep = 0;} ($acc_deprm * $mnts)
		{ $acc_d2 = $acc_deprm * $mnts; $cur_dep = $acc_deprm; } else {$acc_d2= $amount - $scrap_values; $cur_dep = 0;}

	if ($year == $buy_year ){
	//$amt = number_format($amount,2);
	$amt = $amount;
	$amt_add = '-';
	} else { $amt = '-';  $amt_add = $amount;  }

if ($disposal == 'Yes')
{ $nbv2 = 0; } else {$nbv = (($amount - $acc_d2));
$nbv2 = number_format($nbv,2);
$nbv3 = $nbv;
}

if ($mnts > $months)
				{	

	 echo "<tr><td>$sn</td><td>$descritption</td><td>$identify_string</td><td>$acq_date</td><td>$location</td><td>".number_format($amt_add,2)."</td><td>".number_format($amt,2)."</td><td>$qty</td><td>".number_format($amountu,2)."</td><td>".number_format($amount,2)."</td><td>".number_format($cur_dep,2)."</td><td>".number_format($acc_d2,2)."</td><td>$dis</td><td>$nbv2</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
		 
		$total1 = $total1 + $amt; 
		 $total2 = $total2 + $acc_d2;
		  $total3 = $total3 + $cur_dep;
		 $total4 = $total4 + $nbv;
		 $total5 = $total5 + $amt_add;
		 $total6 = $total6 + $amount;
		 
		 $fw=@fwrite($fp,"$sn,$descritption,$identify_string,$acq_date,$location,$amt_add,$amt,$qty,$amountu,$amount,$cur_dep,$acc_d2,$dis,$nbv3\n");
			++$sn;	}  
				}
				
		  echo "<tr><td></td><td><b>TOTAL</p></b></td><td></td><td></td><td></td><td><b>N".number_format($total5,2)."</b></td><td><b>N".number_format($total1,2)."</b></td><td></td><td></td><td><b>N".@number_format($total6,2)."</b></td><td><b>N".@number_format($total2,2)."</b></td><td><b>N".@number_format($total3,2)."</b></td><td></td><td><b>N".@number_format($total4,2)."</b></td></tr>";
		  
		  echo "</table>";
				 
		 //echo $tb;
		  $fw=@fwrite($fp,"$sne,$sne,$sne,$sne,$sne,$total5,$sne,$sne,$total1,$sne,$total6,$total3,$sne,$total4\n");

		  @fclose($fp);
		 
		   echo "<br/><center><font color='red'><b>Click <a href='$file_path'>here</a> to download the excel file in CSV format</b></font></center>";
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