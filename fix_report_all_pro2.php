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
$color = "#E5E2C9";
$color1 = "#FFF";
$colortest = 0;
$gtotal1 = 0;$gtotal2 = 0; $gtotal3 = 0; $gtotal4 = 0;$gtotal5 = 0;
$year=$_REQUEST['year'];$from2=$_REQUEST['from'];$to2=$_REQUEST['to'];
$cat_type=$_REQUEST['cat_type'];$asset_cat=$_REQUEST['asset_cat'];
$from_date=date('Y-m-d',strtotime($from2));
$to_date=date('Y-m-d',strtotime($to2));

	$asset_name = $bursary->get_any_value('type_id','asset_typetb','asset_code',$asset_cat);
	$cat_name = $bursary->get_any_value('cat_id','asset_categorytb','cat_code',$cat_type);
	$loc_name = $bursary->get_any_value('unit','locationtb','loc_code',$location);	

//if ($from_date != '' && $to_date != '')
//{
	
	//$sql="select * from assettb where acq_date between '$from_date' and '$to_date'";
		 $sql_cat="select * from asset_categorytb  ";
    // if($cat_type !="")
     //   $sql_cat .= "and cat_code='$cat_type' ";
     $sql_cat .= "order by cat_id";
     $rs_cat=@mysqli_query($con, "$sql_cat");
     while($rs_cats=   mysqli_fetch_array($rs_cat))
     {
         $cat_id=@$rs_cats['cat_id'];$cat_code=@$rs_cats['cat_code'];
		// $tb.="<tr><td colspan = '11'>$cat_code</td></tr>";
         $sql_type="select * from asset_typetb where cat_id='$cat_id' ";
     //    if($asset_cat !="")
      //  $sql_type .= "and asset_code='$asset_cat' ";
     //$sql_type .= "order by cat_id";
     $rs_type=@mysqli_query($con, "$sql_type");
     while($rs_types=   mysqli_fetch_array($rs_type))
        {
			 $type_id=@$rs_types['type_id'];$asset_code=@$rs_types['asset_code'];
			 
	$sql="select * from assettb where (acq_date >= '$from_date' and acq_date <= '$to_date') and fix_con = 'Yes' 
	and asset_category='$cat_code' and asset_type='$asset_code' ";
	$sql.=" order by acq_date";
	//echo $sql;
	$res_p= mysqli_query($con, $sql) or die( mysqli_error($con));
	//echo  mysqli_num_rows($res_p);
	$tb="<table border='0' >";
	$tb.="<tr><td colspan = '8'>$cat_id</td></tr>";
	//$tb.="<tr><td colspan = '8'>$type_id</td></tr>";
	//if( mysqli_num_rows($res_p)>0)
	//{
		
		
		//" <tr><th colspan ='11'><p align='left'>ASSET REGISTER AS AT : ".date('F j, Y, g:i a')." </th></tr>";
	//if($location!='') $tb.="<tr><th colspan ='11'><p align='left'>ASSETS UNDER DEPT: $loc_name </th></tr>";
	//if($cat_type!='') $tb.="<tr><th colspan ='11'><p align='left'>ASSETS UNDER CATEGORY : $cat_type - $cat_name</th></tr>";
	//if($asset_cat!='') $tb.="<tr><th colspan ='11'><p align='left'>ASSETS WITH ASSET TYPE: $asset_cat - $asset_name </th></tr>";
		//$tb.="<tr><th colspan ='11'><p align='left'>ASSETS WITH ACQ. DATE: $from_date - $to_date </th></tr>";
 $tb.="<tr><th>Category</th><th>Cost As At </th><th>Additions During Year</th><th>Total Cost As At</th><th>Agg.Depn As At</th><th>Depn.for the year</th><th>Agg.Depn. As At.</th><th>NBV</th></tr>";
	$sn=0; $total1 = 0; $total2 = 0; $total3 = 0;$total4 = 0;$total5 = 0; 
	
	while($rs_v=@mysqli_fetch_array($res_p))
		  { 
			  ++$sn;
			  $identify_string=$rs_v['identify_string'];
			  $descritption=$rs_v['descritption'];
			  $amount=$rs_v['amount'];
			  $disposal=$rs_v['disposal'];
				if ($disposal == 'Yes')
				{$dis = 10;}else {$dis = 0;}
			   $acq_date=$rs_v['acq_date'];
			   $buy_year = explode('-',$acq_date);$buy_year = $buy_year [0];
$mov=@mysqli_query($con, "select loc_code from fix_movementtb where identification_string='$identify_string' order by identification_string desc limit 1"); 
$mo_d=@mysqli_fetch_array($mov); $loc_code=$mo_d['loc_code'];
$asst=@mysqli_query($con, "select * from locationtb where loc_code='$loc_code'"); 
$rs_d=@mysqli_fetch_array($asst); $unit=$rs_d['unit']; $dept=$rs_d['dept'];$room_no=$rs_d['room_no'];
$units= $unit." (".$dept.") ".$room_no;
$dep=@mysqli_query($con, "select * from asset_depreciation where identify_string='$identify_string'"); 
$de_d=@mysqli_fetch_array($dep); $acc_depr=$de_d['acc_depr']; $no_years=$de_d['no_years'];

$acc_d = $year - $buy_year + 1;
 if ($acc_d == 0 && $no_years < 100 ){
	 $c= 1;
	  $acc_d2 = $c * $acc_depr;
 } 
 elseif ($acc_d == 0 && $no_years >= 100)
 {$acc_d2 = 0; }
 elseif (($acc_d <= $no_years) && ($no_years < 100)) 
 			{ $acc_d2 = $acc_d * $acc_depr;		}
	elseif ($acc_d < $no_years and $no_years >= 100) 
			 {$acc_d2 = 0; }
	
		else {$acc_d2 = $amount - 10; }

$acc_d3 = $year - $buy_year+1;
if ($acc_d3 <= 0 and $no_years < 100){
$cur_dep = $acc_depr; }
elseif ($acc_d3 < $no_years and $no_years < 100)
 {$cur_dep= $acc_depr;}
 elseif ($acc_d3 == $no_years and $no_years < 100)
 {$cur_dep= 0;}
 elseif ($acc_d3 > $no_years and $no_years < 100)
 {$cur_dep= 0;}

elseif ($acc_d3 > 0 and $no_years >= 100)
{$cur_dep= 0;}

else {$cur_dep =$acc_depr; }



if ($disposal == 'Yes')
{ $nbv2 = 0; } else {$nbv = (($amount - $acc_d2) - $cur_dep);
$nbv2 = number_format($nbv,2);
}

	//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
	 //$tb.="<tr><td>$sn</td><td>$descritption</td><td>$identify_string</td><td>$acq_date</td><td>$units</td><td>".number_format($amount,2)."</td><td>0</td><td>".number_format($acc_d2,2)."</td><td>".number_format($cur_dep,2)."</td><td>$dis</td><td>$nbv2</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
		 
		 $total1 = $total1 + $amount;
		 $total2 = $total2 + $acc_d2;
		  $total3 = $total3 + $cur_dep;
		 $total4 = $total4 + $nbv;
		  }//end of while
		$total5 = $total2 + $total3;  
	$gtotal1 = $gtotal1 + $total1;
	$gtotal2 = $gtotal2 + $total2;
	$gtotal3 = $gtotal3 + $total3;
	$gtotal4 = $gtotal4 + $total4;
	$gtotal5 = $gtotal5 + $total5;
 //$tb.="<tr><th>Category</th><th>Cost As At </th><th>Additions During Year</th><th>Total Cost As At</th><th>Agg.Depn As At</th><th>Depn.for the year</th><th>Agg.Depn. As At.</th><th>NBV</th></tr>";

$tb.="<tr><td>$type_id</td><td><b>N".@number_format($total1,2)."</b></td><td>0.00</td><td><b>N".@number_format($total1,2)."</b></td><td><b>N".@number_format($total2,2)."</b></td><td><b>N".@number_format($total3,2)."</b></td><td><b>N".@number_format($total5,2)."</b></td><td><b>N".@number_format($total4,2)."</b></td></tr>";
		  $tb.="</table>";
		  echo $tb;
	} 
	
	//else {
		//echo "No record to display";
/*echo "<script>alert('Alert: No record to display'); window.close();</script>";*/
		
		//}
}
echo "<table border='1'>";
echo "<tr><td>Ground Total</td><td><b>N".@number_format($gtotal1,2)."</b></td><td>0.00</td><td><b>N".@number_format($gtotal1,2)."</b></td><td><b>N".@number_format($gtotal2,2)."</b></td><td><b>N".@number_format($gtotal3,2)."</b></td><td><b>N".@number_format($gtotal5,2)."</b></td><td><b>N".@number_format($gtotal4,2)."</b></td></tr>";
echo "</table>";
//echo "Ground Total = ".$gtotal1;
	// }
?>
</center>
</body>
<div id="tooplate_footer_wrapper">
	<?php // include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->
</html>