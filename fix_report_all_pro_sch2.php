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
<title>Schedule of Asset</title>
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
$val=explode("***",get_company());
	 
	 echo "<center><img src='$val[1]' width='100' height='100' style='float:center' /><!--<img src='images/uith.png' width='110' height='100' style='float:right' />--><h2>".strtoupper($val[0])."</h2></center>";
?>

<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
<tr height="77"><td>&nbsp;</td></tr>
<tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong>SCHEDULE OF NON-CURRENT ASSET</strong></p></td>
</tr>
<tr><td colspan="2" align="center" bgcolor="#E5E5E5"><strong>REPORT AS AT <?php echo strtoupper(date('jS F, Y')); ?> </tr></table>
<?php
$color = "#E5E2C9";
$color1 = "#FFF";
$colortest = 0;
$year=$_REQUEST['year'];$from2=$_REQUEST['from'];
$to2=$_REQUEST['to'];
$cat_type=$_REQUEST['cat_type'];
$asset_cat=$_REQUEST['asset_cat'];
$from_date=date('Y-m-d',strtotime($from2));
$to_date=date('Y-m-d',strtotime($to2));

	$asset_name = $bursary->get_any_value('type_id','asset_typetb','asset_code',$asset_cat); 
	$cat_name = $bursary->get_any_value('cat_id','asset_categorytb','cat_code',$cat_type); 

	
	$type_id=@$rs_types['type_id'];$asset_code=@$rs_types['asset_code'];?>
			 <table border='0' width='100%'><tr><td></td>
	<?php $dd="select distinct a.asset_category, c.cat_title from assettb a, asset_categorytb c where a.asset_category=c.cat_code and year(acq_date) = '$year'";
	$sqq =  mysqli_query($con, $dd) or die( mysqli_error($con));
	$cn_row= mysqli_num_rows($sqq); $cat_val='';
	while($rq= mysqli_fetch_array($sqq, 3 )){
		//read asset category
		$cat_val = $rq[0];
		$sqq2= mysqli_query($con, "select a.*, u.life_percent from asset_categorytb a, useful_lifetb u where a.cat_code='$cat_val' and a.cat_code=u.asset_type"); 
		//while($rq2= mysqli_fetch_array($sqq2, 3 )){?>
			<th><?php echo $rq['cat_title']." ($rq2[life_percent]%)"; ?></th>
	<?php 	//} 
	}
	//Previous Year ?>
		</tr><tr><td>Openning Ballance B/d</td>
        <?php $year_bf = $year - 1;
		$sqq3= mysqli_query($con, "select sum(amount) as asset_cost from assettb where asset_category='$cat_val' and year(acq_date) = '$year_bf' order by acq_date"); 
		while($rq3= mysqli_fetch_array($sqq3, 3 )){ ?>
			<td align='center'><?php echo $rq3['asset_cost']; ?></td>
	<?php 
		//Current Year
		} ?>		
		</tr><tr><td>Addition of the year</td> <?php
		$sqq4= mysqli_query($con, "select sum(amount) as asset_cost from assettb where asset_category='$cat_val' and year(acq_date) = '$year' order by acq_date"); 
		while($rq4= mysqli_fetch_array($sqq4, 3 )){?>
			<td align='center'><?php echo $rq4['asset_cost']; ?></td> <?php 
		}
		//Total for both Year
		?>
		</tr><tr><td><strong>Total</strong></td> <?php 
		$sqq5= mysqli_query($con, "select sum(amount) as asset_cost from assettb where asset_category='$cat_val' and (year(acq_date) = '$year' or year(acq_date) = '$year_bf') order by acq_date"); 
		while($rq5= mysqli_fetch_array($sqq5, 3 )){
			$ar_total[] = $rq5['asset_cost']; ?>
			<td align='center'><?php echo $rq5['asset_cost']; ?></td><?php 
		}
		//Depreciation Title
		?>
		</tr><tr><td colspan='<?php echo $cn_row; ?>'><strong>Depreciations:</strong></td> 
		</tr><tr><td><strong>B/d</strong></td> <?php 
		$sqq4= mysqli_query($con, "select sum(acc_depr) as total_ac from asset_depreciation where asset_cat='$cat_val'");
		$total_ac=0;	$total_cu=0;
		while($rq4= mysqli_fetch_array($sqq4, 3 )){
			/*$dep=@mysqli_query($con, "select * from asset_depreciation where identify_string='$rq4[identify_string]'"); 
			while($de_d=@mysqli_fetch_array($dep, 3 )){
				$total_ac +=$de_d['acc_depr'];
				$total_cu +=$de_d['curr_depr'];
			}*/ ?>
			<td align='center'><?php echo $rq4['total_ac']; ?></td> <?php 
		}
		?>
		</tr><tr><td>Current Year</td><?php 
		$sqq4= mysqli_query($con, "select sum(curr_depr) as total_cr from asset_depreciation where asset_cat='$cat_val'"); 
		$total_ac=0;	$total_cu=0;
		while($rq4= mysqli_fetch_array($sqq4, 3 )){
			/*$dep=@mysqli_query($con, "select sum( from asset_depreciation where identify_string='$rq4[identify_string]'"); 
			while($de_d=@mysqli_fetch_array($dep, 3 )){
				$total_ac +=$de_d['acc_depr'];
				$total_cu +=$de_d['curr_depr'];
			}*/ ?>
			<td align='center'><?php echo $rq4['total_cr']; ?></td><?php 
		}
		?>
		
		</tr><tr><td>Cummulative</td><?php 
		$sqq4= mysqli_query($con, "select sum(curr_depr + acc_depr) as total_ac from asset_depreciation where asset_cat='$cat_val'"); 
		$total_ac=0;	$total_cu=0;
		while($rq4= mysqli_fetch_array($sqq4, 3 )){
			/*$dep=@mysqli_query($con, "select * from asset_depreciation where identify_string='$rq4[identify_string]'"); 
			while($de_d=@mysqli_fetch_array($dep, 3 )){
				$total_ac +=$de_d['acc_depr'];
				$total_cu +=$de_d['curr_depr'];
			}*/ 
			$ar_total_cum[] = $rq4['total_ac']; ?>
			<td align='center'><?php echo $rq4['total_ac']; ?></td><?php 
		}
		?>
		</tr><tr><td><strong>Net book Value B/f</strong></td><?php 
		for($i=0; $i < count($ar_total); $i++){
			$f_total = $ar_total[$i] - $ar_total_cum[$i]; ?>
			<td align='center'><strong><?php echo $f_total; ?></strong></td><?php 
		}
		//echo "</tr>";
	//}
	?>
	</tr></table>
</center>
</body>
<div id="tooplate_footer_wrapper">
	<?php // include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->
</html>