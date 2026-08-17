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
font : "Arial", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
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

<table align="center" width="97%" cellpadding="0" cellspacing="0" border="1">
<tr height="77"><td>&nbsp;</td></tr>
<tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong>SCHEDULE OF NON-CURRENT ASSET</strong></p></td>
</tr>
<tr><td colspan="2" align="center" bgcolor="#E5E5E5"><strong>AS AT <?php echo $_REQUEST['year']; //date('F, Y',strtotime($_REQUEST['to'])); ?> </tr></table>
<?php
$color = "#E5E2C9";
$color1 = "#FFF";
$colortest = 0;
$year=$from=$_REQUEST['year'];$from2=$_REQUEST['from'];
$to2=$_REQUEST['to'];
$cat_type=$_REQUEST['cat_type'];
$asset_cat=$_REQUEST['asset_cat'];
$from_date=date('Y-m-d',strtotime($from2));
$to_date=date('Y-m-d',strtotime($to2));

	$asset_name = $bursary->get_any_value('type_id','asset_typetb','asset_code',$asset_cat); 
	$cat_name = $bursary->get_any_value('cat_id','asset_categorytb','cat_code',$cat_type); 

	
			 $type_id=@$rs_types['type_id'];$asset_code=@$rs_types['asset_code'];
	
	$sql="select * from assettb where DATE_ADD(acq_date, INTERVAL 1 YEAR) <= CURDATE() AND YEAR(acq_date) <= '$from'";
	$res_p= mysqli_query($con, $sql) or die( mysqli_error($con));
	//$qq="select distinct a.asset_category, c.cat_title from assettb a INNER JOIN asset_categorytb c ON a.asset_category=c.cat_code where DATE_ADD(a.acq_date, INTERVAL 1 YEAR) <= CURDATE() AND YEAR(a.acq_date) <= '$from'";
	$qq="select distinct a.asset_category, c.cat_title from assettb a INNER JOIN asset_categorytb c ON a.asset_category=c.cat_code where YEAR(a.acq_date) <= '$from'";
	$cat_q= mysqli_query($con, $qq) or die( mysqli_error($con));
	if( mysqli_num_rows($cat_q) > 0){ ?>
<table width="97%" cellpadding="0" cellspacing="0">
  <tr>
    <td width="93" rowspan="2"></td>
    <td width="138" rowspan="2"><strong>Opening Balance B/d</strong></td>
    <td width="153" rowspan="2"><strong>Additional for the year </strong></td>
    <td width="63" rowspan="2"><strong>Total</strong></td>
    <td width="357" height="12" colspan="4" align="center"><strong>DEPRECIATIONS</strong></td>
  </tr>
  <tr>
    <td height="23"><strong>B/d</strong></td>
    <td height="23"><strong>Current Year</strong></td>
    <td height="23"><strong>Cummulative</strong></td>
    <td height="23"><strong>Net Book Value B/f</strong></td>
  </tr>
    
<?php	
			$sn=0; $total_addition = 0; $total_opening = 0; $total_foryear = 0;$total_cumulative = 0;
			$t_cur_dep = 0;	$t_acc_dep = 0; $cumulative=0; $nbv = 0; $total_nbv=0;
			
		while($cat_row= mysqli_fetch_array($cat_q, 3 )){
			$cat_code = $cat_row['asset_category'];		$cat_title = $cat_row['cat_title'];
			$lifepercent = $bursary->get_any_value('life_percent', 'useful_lifetb', 'asset_type', $cat_code);
		
				
			///$sql="select * from assettb where asset_category='$cat_code'"; //DATE_ADD(acq_date, INTERVAL 1 YEAR) <= CURDATE() AND YEAR(acq_date) = '$from' and
			///$res_p= mysqli_query($con, $sql) or die( mysqli_error($con));
			//while($cat_row= mysqli_fetch_array($cat_q, 3 )){
			
			/*/if($rs_v=@mysqli_fetch_array($res_p))*/{
				  ///++$sn;
				  
				  ///$descritption=$rs_v['descritption'];
				  $ssq="select * from assettb where asset_category = '$cat_code' AND YEAR(acq_date) <= '$from'"; 
				  $ttm= mysqli_query($con, $ssq); ///AND DATE_ADD(acq_date, INTERVAL 1 YEAR) <= CURDATE()
				  $t_amt=0; $cur_dep = 0; $acc_dep = 0;
				  while($tm= mysqli_fetch_array($ttm, 3 )) {
					  $identify_string=$tm['identify_string'];
					  $date_diff = $bursary->get_any_value('(datediff(curdate(), acq_date)/365)', 'assettb', 'identify_string', $identify_string);
					  $acq_year = $bursary->get_any_value('YEAR(acq_date)', 'assettb', 'identify_string', $identify_string);
					  $disposal = $bursary->get_any_value('no_years', 'asset_depreciation', 'identify_string', $identify_string);
					  $life = $bursary->get_any_value('life_percent', 'useful_lifetb', 'asset_type', $cat_code);
					  
						if ($life == 0)
						$lifepercent = 100;
						else
						$lifepercent = 100/$lifepercent;
						
						if($acq_year == $year) $t_amt += $tm['amount'];
						
					  if($date_diff < 1){
						  $cur_dep=0;	$acc_dep=0;
						  ///$t_amt += $tm['amount'];
					  }elseif($date_diff >= 1 and $date_diff < 2){
						  //current year depreciation
						  ///$t_amt += $tm['amount'];
						  $cur_dep = (($tm['amount'] - 10) /$lifepercent);
						  $t_cur_dep += $cur_dep;
					  }elseif($date_diff >= 2 and $date_diff <= $disposal){
						  //accumulated depreciation
						  ///$t_amt += $tm['amount'];
						  $cur_dep = (($tm['amount'] - 10) /$lifepercent);
						  $t_cur_dep += $cur_dep;
						  $acc_dep = $cur_dep * ($date_diff - 1);
						  $t_acc_dep += $acc_dep;
					  }elseif($date_diff >= $disposal){
						  //disposal
						  ///$t_amt += 10;
					  }
					  
				  }
				  //echo $date_diff;
				  //$amount=$bursary->get_any_value('sum(amount)', 'assettb', 'asset_category', $cat_code); //$rs_v['amount'];
				  //$disposal=$rs_v['disposal'];
					if ($disposal == 'Yes')
					{$dis = 10;}else {$dis = 0;}
					if ($disposal == 'Yes')
					{ $nbv2 = 0; } else {$nbv = (($amount - $acc_d2) - $cur_dep);
					$nbv2 = number_format($nbv,2);
					}
	
				$sql_open= mysqli_query($con, "select sum(amount) as asset_cost from assettb where asset_category='$cat_code' AND YEAR(acq_date) < '$from'");
				$opening_balance = 0; $current_year_balance = 0;
				if($obal =  mysqli_fetch_array($sql_open, 3 )) $opening_balance = $obal['asset_cost'];
				
				$total_addition += $t_amt;
				$total_for_the_year = $t_amt + $opening_balance;
				$total_opening += $opening_balance;
				$total_foryear += $total_for_the_year;
				$cumulative = $acc_dep + $cur_dep;
				$total_cumulative += $cumulative;
				$nbv = $total_for_the_year - $cumulative;
				$total_nbv += $nbv;
		?>
	  <tr>
		<td height="30"><strong><?php echo $cat_title." $lifepercent%"; ?></strong></td>
		<td height="30" align="right"><?php echo number_format($opening_balance, 2); ?></td>
		<td height="30" align="right"><?php echo number_format($t_amt, 2); ?></td>
		<td height="30" align="right"><strong><?php echo number_format($total_for_the_year, 2); ?></strong></td>
		<td height="30" align="right"><?php echo number_format($acc_dep, 2); ?></td>
		<td height="30" align="right"><?php echo number_format($cur_dep, 2); ?></td>
		<td height="30" align="right"><?php echo number_format($cumulative, 2); ?></td>
		<td height="30" align="right"><strong><?php echo number_format($nbv, 2); ?></strong></td>
	  </tr>
	<?php
			}//end while distinct category
		}
		?>
  <tr>
    <td height="30"><strong>TOTAL</strong></td>
    <td height="30" align="right"><strong><?php echo number_format($total_opening, 2); ?></strong></td>
    <td height="30" align="right"><strong><?php echo number_format($total_addition, 2); ?></strong></td>
    <td height="30" align="right"><strong><?php echo number_format($total_foryear, 2); ?></strong></td>
    <td height="30" align="right"><strong><?php echo number_format($t_acc_dep, 2); ?></strong></td>
    <td height="30" align="right"><strong><?php echo number_format($t_cur_dep, 2); ?></strong></td>
    <td height="30" align="right"><strong><?php echo number_format($total_cumulative, 2); ?></strong></td>
    <td height="30" align="right"><strong><?php echo number_format($total_nbv, 2); ?></strong></td>
  </tr>
</table>
        
<?php	}else{
		echo "<h2>No record to display!</h2>";
	}
?>
</center>
</body>
<div id="tooplate_footer_wrapper">
	<?php // include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->
</html>