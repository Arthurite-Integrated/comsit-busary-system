<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Report</title>
<link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
<style>
body {
font : 0.6em "Arial", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
</style>

   <!-- <script type="text/javascript" src="include/jquery.min.js"></script>
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script> -->
   
</head>

<body style="font-size:14px">
<?php

require_once "function_b.php";
@require_once('class/mysqli_class.php');
$db = new Database();
$db->connect();

ini_set('max_execution_time', 60000000000);
ini_set("memory_limit", "51200M");
$where = " WHERE id > 0 ";
$mode=base64_decode($_REQUEST['mode']);
$month_code=$month=$_REQUEST['month']; 		if($month > 0) $where .= " AND month=$month ";
$year=$_REQUEST['year'];				 	if($year > 0) $where .= " AND year=$year ";
$status=$_REQUEST['status']; 				if($status!="All") $where .= " AND staff_status='$status' ";
$category=$_REQUEST['category'];			if($category!="All") $where .= " AND category='$category' ";
$fileno=$f=$_REQUEST['fileno']; 
$staff=$_REQUEST['staff']; 					
if($staff!="all"){ 
	$fileno=@set_comma_breakdown($fileno); 
	$where .=" AND fileno in ($fileno)";
}
$dept=$_REQUEST['dept'];					if($dept!="all") $where .= " AND department='$dept' ";
$option=$_REQUEST['reportcategory'];
$folio_code=$_REQUEST['folio_code']; 		if($folio_code!="") $where .= " AND folio_code='$folio_code' ";
//echo $staff; echo  $where; exit;
//echo "$month_code $year<br>$status $category<br>$staff $dept<br>$option $fileno MODE:".$mode;
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////

$val=explode("***",get_company());
	 
	 echo "<center><img src='$val[1]' width='50' height='50' /></center><b><p align='center'><u>".strtoupper($val[0])."<br/>STAFF CATEGORY :".ucfirst(strtoupper($status))."  ".ucwords(strtoupper($category))."<br/></u></p></b>";
	

///////////////////////////////////////////////////// End of header ////////////////////////////////////////////

 $sql="SELECT DISTINCT fileno, department, fullname, level, step, bank_name, acct_no FROM payroll_scheduletb";
	 /*if($dept !="all")   WHERE  staff_status='$status' AND category='$category' AND month='$month' AND year='$year'
	 	$sql .=" and department='$dept'";
	 if ($staff !="all")
	 	{
			$fileno=@set_comma_breakdown($fileno);
	 		$sql .=" and fileno in ($fileno)";
			
		}
	$sql .=" order by department,convert(fileno,decimal)";*/
//echo $where; 

$r=@mysqli_query($con, $sql);
	 

if($option=='payroll_summary')
 {
 	 echo "<b><p align='center'><u> PAYROLL SUMMARY FOR THE MONTH OF: ".get_month_name($month_code).", $year.</u></p></b>";
	
	 $sql="SELECT folio_code, sum(amount) as amount_sum, payment_type FROM payroll_scheduletb $where GROUP BY folio_code ORDER BY payment_type";
	 $sqln="SELECT * FROM payroll_scheduletb $where ";
	 $r= mysqli_query($con, $sql);		$r2= mysqli_query($con, $sqln);
	 $total_emp= mysqli_num_rows($r2);
	 if( mysqli_num_rows($r) >= 1)
	 {
		 ?>
		<table width="90%" align="center" border="0" cellpadding="3" cellspacing="0" style="border:solid 1px #000000">
		<tr style="border:solid 1px #000000">
			<th align="left" style="border:solid 1px #000000"><strong>ITEM CODE</strong></th>
            <th align="left" style="border:solid 1px #000000"><strong>DESCRIPTION</strong></th>
            <th style="border:solid 1px #000000"><strong>DEBIT</strong></th>
          <th style="border:solid 1px #000000"><strong>CREDIT</strong></th>
		</tr>
		<?php
			$groundtotal_allowance=0;
			$groundtotal_gross=0;
			$groundtotal_deduction=0;
			$groundtotal_net=0;
			while($rs=@mysqli_fetch_array($r, 3 ))
				{
					$folio=$rs['folio_code'];
					$folio_desc=get_folio_name($folio);
					$groundtotal_net += $rs['amount_sum'];
					if(strtolower($rs['payment_type'])=="allowance") {
						$groundtotal_allowance += $rs['amount_sum'];
						echo "<tr><td align='left' style='border:solid 1px #000000'>".$folio."</td>
						<td align='left' style='border:solid 1px #000000'>".$folio_desc."</td>
						<td align='right' style='border:solid 1px #000000'>".number_format($rs['amount_sum'], 2)."</td>
						<td align='right' style='border:solid 1px #000000'></td></tr>";
					}
					if(strtolower($rs['payment_type'])=="deduction") {
						$groundtotal_deduction += $rs['amount_sum'];
						echo "<tr><td align='left' style='border:solid 1px #000000'>".$folio."</td>
						<td align='left' style='border:solid 1px #000000'>".$folio_desc."</td>
						<td align='right' style='border:solid 1px #000000'></td>
						<td align='right' style='border:solid 1px #000000'>".number_format($rs['amount_sum'], 2)."</td></tr>";
					}
				}
		?>
		
		<tr>
       	  <th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'>Net Salary For The Month</th>
        	<th align="right" style='border:solid 1px #000000'></th>
        	<th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_net, 2); ?></th>
		</tr>
		<tr>
        	<th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'>Rounding Error</th>
        	<th align="right" style='border:solid 1px #000000'></th>
        	<th align="right" style='border:solid 1px #000000'><?php echo "0.50"; ?></th>
		</tr>
		<tr>
        	<th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
        	<th align="right" style='border:solid 1px #000000'></th>
        	<th align="right" style='border:solid 1px #000000'></th>
		</tr>
		<tr>
        	<th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'>TOTALS</th>
        	<th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_allowance, 2); ?></th>
        	<th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_deduction, 2); ?></th>
		</tr>
		<tr>
        	<th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
        	<th style='border:solid 1px #000000'></th>
        	<th align="right" style='border:solid 1px #000000'></th>
		</tr>
		<tr>
        	<th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'></th>
        	<th align="left" style='border:solid 1px #000000'>Total Deductions:</th>
        	<th align="right" style='border:solid 1px #000000'><?php echo ""; ?></th>
		</tr>
		<tr>
        	<th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'></th>
        	<th align="left" style='border:solid 1px #000000'>Total Employees:</th>
        	<th align="right" style='border:solid 1px #000000'><?php echo number_format($total_emp); ?></th>
		</tr>
		<tr>
        	<th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
        	<th align="left" style='border:solid 1px #000000'></th>
        	<th align="right" style='border:solid 1px #000000'></th>
		</tr>
		<tr>
        	<th align="left" style='border:solid 1px #000000'></th>
            <th align="left" style='border:solid 1px #000000'></th>
        	<th align="left" style='border:solid 1px #000000'>Total Upfront:</th>
        	<th align="right" style='border:solid 1px #000000'><?php echo ""; ?></th>
		</tr>
		</table> 
		
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
	 
	 
 } // end of payroll_summary

if($option=='variance_report')
 {
	 if($month_code == 1){
		 $nmonth = 12; $nyear = $year - 1;
		 }else{ $nmonth = $month_code - 1; $nyear = $year; }
 	 echo "<b><p align='center'><u> SALARY VARIANCE FOR THE MONTH OF: ".get_month_name($month_code).", $year.</u></p></b>";
	
	 $sql="SELECT folio_code, sum(amount) as amount_sum, payment_type FROM payroll_scheduletb WHERE month=$month_code and year=$year GROUP BY folio_code ORDER BY payment_type";
	 $sql2="SELECT folio_code, sum(amount) as amount_sum, payment_type FROM payroll_scheduletb WHERE month=$nmonth and year=$nyear GROUP BY folio_code ORDER BY payment_type";
	 
	 $sqln="SELECT * FROM payroll_scheduletb $where ";
	 $r= mysqli_query($con, $sql);		$r2= mysqli_query($con, $sqln);
	 $total_emp= mysqli_num_rows($r2);
	 if( mysqli_num_rows($r) >= 1)
	 {
		 ?>
<table width="90%" align="center" border="0" cellpadding="3" cellspacing="0" style="border:solid 1px #000000">
		   <tr style="border:solid 1px #000000">
		     <th align="left" style="border:solid 1px #000000"><strong>ITEM CODE</strong></th>
		     <th align="left" style="border:solid 1px #000000"><strong>DESCRIPTION</strong></th>
		     <th style="border:solid 1px #000000"><strong><?php echo strtoupper(substr(get_month_name($nmonth), 0, 3).", ".$nyear); ?></strong></th>
		     <th style="border:solid 1px #000000"><strong><?php echo strtoupper(substr(get_month_name($month_code), 0, 3).", ".$year); ?></strong></th>
		     <th style="border:solid 1px #000000"><strong>VARIANCE</strong></th>
		     <th style="border:solid 1px #000000"><strong>VAR. %</strong></th>
	       </tr>
		   <?php
			$groundtotal_allowance=0;		$groundtotal_allowance_p=0;
			$groundtotal_variance_d=0;		$groundtotal_variance_a=0;
			$groundtotal_variance_d_p=0;	$groundtotal_variance_a_p=0;
			$groundtotal_deduction=0;		$groundtotal_deduction_p=0;
			$groundtotal_net=0;				$groundtotal_net_p=0;			
			$groundtotal_net_v=0;			$groundtotal_net_v_p=0;
			while($rs=@mysqli_fetch_array($r, 3 ))
				{
					$folio=$rs['folio_code'];
					$folio_desc=get_folio_name($folio);
					$groundtotal_net += $rs['amount_sum'];
					$sqlx= mysqli_query($con, "SELECT sum(amount) as amount_sumx FROM payroll_scheduletb WHERE month=$nmonth AND year=$nyear");
					$qr =  mysqli_fetch_array($sqlx, 3 );
					$pre_amt = $qr['amount_sumx'];	$cur_amt = $rs['amount_sum'];	$var_amt = $pre_amt - $cur_amt;
					$per_amt = ($var_amt * 100) / $cur_amt;
					if($var_amt < 0) {
						$var_amt = "(".number_format( abs($var_amt), 2).")"; $per_amt = "(".number_format(abs($per_amt), 2).")"; 
					}else{
						$var_amt = number_format( abs($var_amt), 2); $per_amt = number_format(abs($per_amt), 2); 
					}
					
					if(strtolower($rs['payment_type'])=="allowance") {
						$groundtotal_allowance += $rs['amount_sum'];
						$groundtotal_allowance_p += $pre_amt;
					}
					if(strtolower($rs['payment_type'])=="deduction") {
						$groundtotal_deduction += $rs['amount_sum'];
						$groundtotal_deduction_p += $pre_amt;
					}
					
					echo "<tr><td align='left' style='border:solid 1px #000000'>".$folio."</td>
					<td align='left' style='border:solid 1px #000000'>".$folio_desc."</td>
					<td align='right' style='border:solid 1px #000000'>".number_format($pre_amt, 2)."</td>
					<td align='right' style='border:solid 1px #000000'>".number_format($cur_amt, 2)."</td>
					<td align='right' style='border:solid 1px #000000'>".$var_amt."</td>
					<td align='right' style='border:solid 1px #000000'>".$per_amt."</td></tr>";
				}
		?>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
	       </tr>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>Total Employees:</th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($total_emp); ?></th>
		     <th align="right" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="right" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="right" style='border:solid 1px #000000'>&nbsp;</th>
	       </tr>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="right" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
	       </tr>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>Total Allowances:</th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_allowance_p, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_allowance, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'>
			 <?php 
			 $groundtotal_variance_a = $groundtotal_allowance - $groundtotal_allowance_p;
			 $groundtotal_variance_a_p = ($groundtotal_variance_a * 100) / $groundtotal_allowance;
					if($groundtotal_variance_a < 0) {
						$groundtotal_variance_a = "(".number_format( abs($groundtotal_variance_a), 2).")"; 
						$groundtotal_variance_a_p = "(".number_format( abs($groundtotal_variance_a_p), 2).")"; 
					}else{
						$groundtotal_variance_a = number_format( abs($groundtotal_variance_a), 2); 
						$groundtotal_variance_a_p = number_format( abs($groundtotal_variance_a_p), 2); 
					}
			 echo $groundtotal_variance_a; ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo $groundtotal_variance_a_p; ?></th>
	       </tr>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>Total Deductions:</th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_deduction_p, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_deduction, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'>
			 <?php 
			 $groundtotal_variance_d = $groundtotal_deduction - $groundtotal_deduction_p;
			 $groundtotal_variance_d_p = ($groundtotal_variance_d * 100) / $groundtotal_deduction;
					if($groundtotal_variance_d < 0) {
						$groundtotal_variance_d = "(".number_format( abs($groundtotal_variance_d), 2).")"; 
						$groundtotal_variance_d_p = "(".number_format( abs($groundtotal_variance_d_p), 2).")"; 
					}else{
						$groundtotal_variance_d = number_format( abs($groundtotal_variance_d), 2); 
						$groundtotal_variance_d_p = number_format( abs($groundtotal_variance_d_p), 2); 
					}
			 
			 echo $groundtotal_variance_d; ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo $groundtotal_variance_d_p; ?></th>
	       </tr>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
	       </tr>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>Net Pay:</th>
		     <th align="right" style='border:solid 1px #000000'><?php 
			 $groundtotal_net = $groundtotal_allowance - $groundtotal_deduction;
			 $groundtotal_net_p = $groundtotal_allowance_p - $groundtotal_deduction_p;
			 
			 $groundtotal_net_v = $groundtotal_net - $groundtotal_net_p;
			 $groundtotal_net_v_p = ($groundtotal_net_v * 100) / $groundtotal_net;
					if($groundtotal_net_v < 0) {
						$groundtotal_net_v = "(".number_format( abs($groundtotal_net_v), 2).")"; 
						$groundtotal_net_v_p = "(".number_format( abs($groundtotal_net_v_p), 2).")"; 
					}else{
						$groundtotal_net_v = number_format( abs($groundtotal_net_v), 2); 
						$groundtotal_net_v_p = number_format( abs($groundtotal_net_v_p), 2); 
					}
			 
			 
			 echo number_format($groundtotal_net_p, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_net, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'> <?php 
			 echo $groundtotal_net_v; ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo $groundtotal_net_v_p; ?></th>
	       </tr>
</table>
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
	 
	 
 } // end of variance_report

if($option=='pay_point_summary')
 {
 	echo "<b><p align='center'><u> PAY POINT SUMMARY LIST FOR THE MONTH OF: ".get_month_name($month_code).", $year.</u></p></b>";
	
	 $sql="SELECT bank_name, sum(amount) as amount_sum FROM payroll_scheduletb WHERE month=$month_code AND year=$year GROUP BY bank_name ORDER BY bank_name";
	 
	 $r= mysqli_query($con, $sql);

	 if( mysqli_num_rows($r) >= 1)
	 {
		 ?>
		 <table width="90%" align="center" border="0" cellpadding="3" cellspacing="0" style="border:solid 1px #000000">
		   <tr style="border:solid 1px #000000">
		     <th align="left" style="border:solid 1px #000000"><strong>S/N</strong></th>
		     <th align="left" style="border:solid 1px #000000"><strong>BANK NAME</strong></th>
		     <th style="border:solid 1px #000000"><strong>BANK CODE</strong></th>
		     <th style="border:solid 1px #000000"><strong>GROSS AMOUNT</strong></th>
		     <th style="border:solid 1px #000000"><strong>TOTAL DEDUCTION</strong></th>
		     <th style="border:solid 1px #000000"><strong>NET AMOUNT</strong></th>
		     <th style="border:solid 1px #000000"><strong>NO. OF STAFF</strong></th>
	       </tr>
		   <?php
			$groundtotal_gross = 0;			$groundtotal_deduction=0;
			$deduction=0;					$gross=0;
			$groundtotal_net=0;				$net=0;			
			$groundtotal_staff=0;			$staff=0;	$sn=0;
			while($rs=@mysqli_fetch_array($r, 3 ))
				{
					$bankname = $rs['bank_name'];		$sn++;
					$gross = $rs['amount_sum'];
					$groundtotal_gross += $rs['amount_sum'];
					
					$sql2="SELECT sum(amount) as amount_sumx FROM payroll_scheduletb WHERE month=$month_code AND year=$year AND bank_name='$bankname' AND payment_type='Deduction'";
	 				$qded =  mysqli_query($con, $sql2);			$rded= mysqli_fetch_array($qded, 3 );	
					$deduction=$rded['amount_sumx'];	$groundtotal_deduction += $deduction;
					
					$sql3="SELECT count(*) as bk_num FROM payroll_scheduletb WHERE bank_name='$bankname' ";
	 				$qbk =  mysqli_query($con, $sql3);			$rbk= mysqli_fetch_array($qbk, 3 );	
					$staff=$rbk['bk_num'];				$groundtotal_staff += $staff;
					$net = $gross - $deduction;			$groundtotal_net += $net;
					
					
					echo "<tr><td align='left' style='border:solid 1px #000000'>".$sn."</td>
					<td align='left' style='border:solid 1px #000000'>".$bankname."</td>
					<td align='left' style='border:solid 1px #000000'>".$bankcode."</td>
					<td align='right' style='border:solid 1px #000000'>".number_format($gross, 2)."</td>
					<td align='right' style='border:solid 1px #000000'>".number_format($deduction, 2)."</td>
					<td align='right' style='border:solid 1px #000000'>".number_format($net, 2)."</td>
					<td align='right' style='border:solid 1px #000000'>".$staff."</td></tr>";
				}
		?>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
	       </tr>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="right" style='border:solid 1px #000000'>TOTALS</th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_gross, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_deduction, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo number_format($groundtotal_net, 2); ?></th>
		     <th align="right" style='border:solid 1px #000000'><?php echo $groundtotal_staff; ?></th>
	       </tr>
		   <tr>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'>&nbsp;</th>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="left" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
		     <th align="right" style='border:solid 1px #000000'></th>
	       </tr>
</table>
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
	 
	 
 } // end of pay_point_summary
	 

if($option=='net_pay_summary')
 {
 	 echo "<b><p align='center'><u> NET PAY (TAKE-HOME SUMMARY) OF PAYROLL FOR THE MONTH OF: ".get_month_name($month_code).", $year.</u></p></b>";
	
	 
	 if( mysqli_num_rows($r)>=1)
	 {
		 ?>
		<table width="100%" border="1" cellpadding="0" cellspacing="0">
		<tr>
			<th>File Num</th><th>Name of Staff</th><th>Department</th><th>Salary Scale</th><th>Basic Salary</th><th>Total Allowance</th><th>Gross Pay</th><th>Total Deduction</th><th>Net Pay</th><th>Bankers</th><th>Account Number</th>
		</tr>
		<?php
			$groundtotal_basic=0;$groundtotal_allowance=0;$groundtotal_gross=0;$groundtotal_deduction=0;$groundtotal_net=0;
			while($rs=@mysqli_fetch_array($r))
				{
					$fno=@$rs['fileno'];$fullname=@$rs['fullname'];$department=@$rs['department'];$level=@sprintf("%02d",$rs['level']);$step=@sprintf("%02d",$rs['step']);
					$bank=@$rs['bank_name'];$acct_no=@$rs['acct_no'];
					$basic_code=@get_folio_code('consolidated pay');
					$basic_amount=@get_basic_amount($fno,$month,$year,$basic_code);
					$allowance=@get_total_allowance_excluding_basic($fno,$month,$year,$basic_code);
					$deduction=@get_total_payment_type($fno,$month,$year,'Deduction');
					$gross=$basic_amount+$allowance;
					$net=$gross-$deduction;
					
					$groundtotal_basic +=$basic_amount;
					$groundtotal_allowance +=$allowance;
					$groundtotal_gross +=$gross;
					$groundtotal_deduction +=$deduction;
					$groundtotal_net +=$net;
					$basic_salary=@number_format($basic_amount,2);
					$allow_salary=@number_format($allowance,2);
					$gross_pay=@number_format($gross,2);
					$net_pay=@number_format($net,2);
					$deduc_salary=@number_format($deduction,2);
					echo "
							<tr>
			<td>$fno</td><td>$fullname</td><td>$department</td><td>Level: $level / Step $step </td><td><center>$basic_salary</center></td><td><center>$allow_salary</center></td><td><center>$gross_pay</center></td><td><center>$deduc_salary</center></td><td><center>$net_pay</center></td><td>$bank</td><td>$acct_no</td>
		</tr>
						";
				}
		?>
		
		<tr>
			<th><?php echo @mysqli_num_rows($r); ?></th><th colspan="2">Records</th><th>TOTALS (=N=)</th><th><?php echo @number_format($groundtotal_basic,2); ?></th><th><?php echo @number_format($groundtotal_allowance,2); ?></th><th><?php echo @number_format($groundtotal_gross,2); ?></th><th><?php echo @number_format($groundtotal_deduction,2); ?></th><th><?php echo @number_format($groundtotal_net,2); ?></th><th>&nbsp;</th><th>&nbsp;</th>
		</tr>
		</table> 
		
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
	 
	 
 } // end of net_pay_summary

if($option=='allowance_summary' or $option=='deduction_summary')
 {
 	if($option=='allowance_summary')
		{
			$pay_type="Allowance";
		}
	elseif($option=='deduction_summary')
		{
			$pay_type="Deduction";
		}
 echo "<b><p align='center'><u> ".strtoupper($pay_type)." PART OF PAYROLL FOR THE MONTH OF: ".get_month_name($month_code).", $year.</u></p></b>";
 if( mysqli_num_rows($r)>=1)
	 {
		 ?>
		<table width="100%" border="1" cellpadding="0" cellspacing="0">
		<tr>
			<th>File Num</th><th>Name of Staff</th><th>Department</th><th>Salary Scale</th>
			<?php 
			$code=@get_folio_code('basic salary');
				$r_al=@mysqli_query($con, "select distinct p.folio_code,f.title from payroll_scheduletb p, foliotb f where f.folio_code=p.folio_code and p.month='$month' and p.year='$year' and p.folio_code not in ('$code') and p.payment_type='$pay_type' order by p.folio_code") ;$code_array=array();
				 
				while($rs_al=@mysqli_fetch_array($r_al))
					{
						$title=@$rs_al['title'];
						$folio=@$rs_al['folio_code'];
						$code_array[]=$folio;
						
						echo "<th>$title</th>";
					}
			?>
			<th>Total</th>
		</tr>
		<?php
			$groundtotal_rowtotal=0;$groundtotal_allowance=0;$groundtotal_gross=0;$groundtotal_deduction=0;$groundtotal_net=0;
			while($rs=@mysqli_fetch_array($r))
				{
					$fno=@$rs['fileno'];$fullname=@$rs['fullname'];$department=@$rs['department'];$level=@sprintf("%02d",$rs['level']);$step=@sprintf("%02d",$rs['step']);
					$bank=@$rs['bank_name'];$acct_no=@$rs['acct_no'];
					$basic_code=@get_folio_code('basic salary');
					
					echo "
							<tr>
			<td>$fno</td><td>$fullname</td><td>$department</td><td>Level: $level / Step $step </td>";
						foreach($code_array as $code)
							{
								$row_total +=@get_basic_amount($fno,$month,$year,$code);
								echo "<td><center>".number_format(get_basic_amount($fno,$month,$year,$code),2)  ."</center></td>";
							}
							$groundtotal_rowtotal +=$row_total;
			echo "<td><center>".number_format($row_total,2)."</center></td>
		</tr>
						";
				}
		?>
		
		<tr>
			<th><?php echo @mysqli_num_rows($r); ?></th><th colspan="2">Recods</th><th>TOTALS (=N=)</th>
			<?php
				foreach($code_array as $code)
					{
						echo "<th>".number_format(get_column_folio_total($status,$category,$month,$year,$code,$dept,$f),2)  ."</th>";
					}
			?>
			
			
			<th><?php echo @number_format($groundtotal_rowtotal,2); ?></th>
		</tr>
		</table> 
		
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
  
 } // end of allowance_summary and deduction_summary
 
////////////************ Section for Gross payment and Deduction summary per department **********************///////////
 
//////////**********************************************************************************************////////////////
if($option=='gross_summary' or $option=='deduction_summary_dept')
 { 
 $sql="select distinct department from payroll_scheduletb where staff_status='$status' and month='$month' and year='$year'";
	 if($dept !="all")
	 	$sql .=" and department='$dept'";
	 if ($category !="")
	 	{
			
	 		$sql .=" and category='$category'";
			
		}
	$sql .=" order by department";
	 $r=@mysqli_query($con, $sql);

 	if($option=='gross_summary')
		{
			$pay_type="Allowance";
			$pay_type2="GROSS PAYMENT SUMMARY";
		}
	elseif($option=='deduction_summary_dept')
		{
			$pay_type="Deduction";
			$pay_type2="DEDUCTION SUMMARY REPORT";
		}
 echo "<b><p align='center'><u> ".strtoupper($pay_type2)." FOR THE MONTH OF: ".get_month_name($month_code).", $year.</u></p></b>";
 if( mysqli_num_rows($r)>=1)
	 {
		 ?>
		<table width="100%" border="1" cellpadding="0" cellspacing="0">
		<tr>
			<th>Department</th>
			<?php 
			$code=@get_folio_code('basic salary');
				$r_al=@mysqli_query($con, "select distinct p.folio_code,f.title from payroll_scheduletb p, foliotb f where f.folio_code=p.folio_code and p.month='$month' and p.year='$year' and p.payment_type='$pay_type' order by p.folio_code") ;$code_array=array();
				 
				while($rs_al=@mysqli_fetch_array($r_al))
					{
						$title=@$rs_al['title'];
						$folio=@$rs_al['folio_code'];
						$code_array[]=$folio;
						
						echo "<th>$title</th>";
					}
			?>
			<th>Total</th>
		</tr>
		<?php
			$groundtotal_rowtotal=0;$groundtotal_allowance=0;$groundtotal_gross=0;$groundtotal_deduction=0;$groundtotal_net=0;
			while($rs=@mysqli_fetch_array($r))
				{
					$department=@$rs['department'];
					
					echo "
							<tr>
			<td>$department</td>";
						foreach($code_array as $code)
							{
								$row_total +=@get_folio_total_per_dept($department,$month,$year,$code,$status,$category);
								//echo "$dept,$month,$year,$code,$status,$category";
								echo "<td><center>".number_format(get_folio_total_per_dept($department,$month,$year,$code,$status,$category),2)  ."</center></td>";
							}
							$groundtotal_rowtotal +=$row_total;
			echo "<td><center>".number_format($row_total,2)."</center></td>
		</tr>
						";
				}
		?>
		
		<tr>
			<th>TOTALS (=N=)</th>
			<?php
				foreach($code_array as $code)
					{
						echo "<th>".number_format(get_folio_total_per_dept('all',$month,$year,$code,$status,$category),2)  ."</th>";
					}
			?>
			
			
			<th><?php echo @number_format($groundtotal_rowtotal,2); ?></th>
		</tr>
		</table> 
		
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
  
 } // end of allowance_summary and deduction_summary per department
 
 ////////////************ Section for Allowance / Deduction summary per Folio **********************///////////
 
 //////////**********************************************************************************************////////////////
if($option=='folio_summary')
 { 
  $sql="select distinct fileno,department,fullname,amount,level,step from payroll_scheduletb where folio_code='$folio_code' and month='$month' and year='$year'";
	 if($dept !="all")
	 	$sql .=" and department='$dept'";
	 if ($staff !="all")
	 	{
			$fileno=@set_comma_breakdown($fileno);
	 		$sql .=" and fileno in ($fileno)";
			
		}
	if($category !="")
	 	$sql .=" and category='$category'";
	if($status !="")
	 	$sql .="  and staff_status='$status'";	
		
	$sql .=" order by department,convert(fileno,decimal)";
	 $r=@mysqli_query($con, $sql);

 	
			$pay_type2="ALLOWANCE / DEDUCTION SUMMARY REPORT<br>".strtoupper(get_folio_name($folio_code));
		
 echo "<b><p align='center'><u> ".strtoupper($pay_type2)." FOR THE MONTH OF: ".get_month_name($month_code).", $year.</u></p></b>";
 if( mysqli_num_rows($r)>=1)
	 {
		 ?>
		 <table width="100%" border="1" cellpadding="0" cellspacing="0">
		<tr>
			<th>Serial No.</th><th>File Number</th><th>Name of Staff</th><th>Department</th><th>Salary Scale</th><th>Amount (=N=)</th>
		</tr>
		<?php
			$groundtotal=0;$i=0;
			while($rs=@mysqli_fetch_array($r))
				{
					$fno=@$rs['fileno'];$fullname=@$rs['fullname'];$department=@$rs['department'];$level=@sprintf("%02d",$rs['level']);$step=@sprintf("%02d",$rs['step']);
					$amount=@$rs['amount'];
					++$i;
					
					$groundtotal +=$amount;
					
					echo "
							<tr>
	<td>$i</td><td>$fno</td><td>$fullname</td><td>$department</td><td>Level: $level / Step $step </td><td><center>".number_format($amount,2)."</center></td>					
							</tr>
						";
				}// end of loop
		?>
		
		<tr>
			<th colspan="3">Number of Recods : <?php echo @mysqli_num_rows($r); ?></th><th colspan="2">TOTALS (=N=)</th><th><?php echo @number_format($groundtotal,2); ?></th>
		</tr>
		</table> 
		 
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
  
 } // end of folio summary

 ////////////************ Section for Annual Gross payment and Annual Deduction summary per department **********************///////////
 
 //////////**********************************************************************************************////////////////
if($option=='annual_gross_summary' or $option=='annual_deduction_summary')
 { 
 $sql="select distinct department from payroll_scheduletb where year='$year'";
	 if($dept !="all")
	 	$sql .=" and department='$dept'";
	 if ($category !="")
	 	{$sql .=" and category='$category'";}
	 if ($status !="")
	 	{$sql .=" and staff_status='$status'";}
	$sql .=" order by department";
	 $r=@mysqli_query($con, $sql);

 	if($option=='annual_gross_summary')
		{
			$pay_type="Allowance";
			$pay_type2="ANNUAL GROSS PAY SUMMARY";
		}
	elseif($option=='annual_deduction_summary')
		{
			$pay_type="Deduction";
			$pay_type2="ANNUAL DEDUCTION SUMMARY REPORT";
		}
 echo "<b><p align='center'><u> $year ".strtoupper($pay_type2)." .</u></p></b>";
 if( mysqli_num_rows($r)>=1)
	 {
		 ?>
		<table width="100%" border="1" cellpadding="0" cellspacing="0">
		<tr>
			<th>Department</th>
			<?php 
			$code=@get_folio_code('basic salary');
				$r_al=@mysqli_query($con, "select distinct p.folio_code,f.title from payroll_scheduletb p, foliotb f where f.folio_code=p.folio_code and p.year='$year' and p.payment_type='$pay_type' order by p.folio_code") ;$code_array=array();
				 
				while($rs_al=@mysqli_fetch_array($r_al))
					{
						$title=@$rs_al['title'];
						$folio=@$rs_al['folio_code'];
						$code_array[]=$folio;
						
						echo "<th>$title</th>";
					}
			?>
			<th>Total</th>
		</tr>
		<?php
			$groundtotal_rowtotal=0;$groundtotal_allowance=0;$groundtotal_gross=0;$groundtotal_deduction=0;$groundtotal_net=0;
			while($rs=@mysqli_fetch_array($r))
				{
					$department=@$rs['department'];
					
					echo "
							<tr>
			<td>$department</td>";
						foreach($code_array as $code)
							{
								$row_total +=@get_folio_total_per_dept($department,'',$year,$code,$status,$category);
								//echo "$dept,$month,$year,$code,$status,$category";
								echo "<td><center>".number_format(get_folio_total_per_dept($department,'',$year,$code,$status,$category),2)  ."</center></td>";
							}
							$groundtotal_rowtotal +=$row_total;
			echo "<td><center>".number_format($row_total,2)."</center></td>
		</tr>
						";
				}
		?>
		
		<tr>
			<th>TOTALS (=N=)</th>
			<?php
				foreach($code_array as $code)
					{
						echo "<th>".number_format(get_folio_total_per_dept('all','',$year,$code,$status,$category),2)  ."</th>";
					}
			?>
			
			
			<th><?php echo @number_format($groundtotal_rowtotal,2); ?></th>
		</tr>
		</table> 
		
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
  
 } // end of Annual Gross Pay_summary and Annual deduction_summary per department
 
 
 ////////////************ Section for Individual pay record card **********************///////////
 
 //////////**********************************************************************************************////////////////
if($option=='individual_prc' )
 { 
 if($year=='' or $f=='')
 	{
		echo "<script>alert('Enter File Number and select the Year');window.close();</script>";
		exit;
	}
	echo "<b><p align='center'>";
	$db->sql("select * from stafftb where fileno='$f'");
			if(get_magic_quotes_gpc()){ $t= @json_decode(stripslashes($db->getResult()));$s=@json_decode(stripslashes($t->data)); }
			else{ $t= @json_decode($db->getResult());  $s=@json_decode($t->data);}
			$scalename=@get_current_scalename();
			$fullname=@get_staff_name($f) ;
			$staffstatus=@get_staff_status($s->level);
			$department=@get_dept_name($s->dept_code);
$fileno=@set_comma_breakdown($f);
			$sscale=@get_salary_scale_peryear($f,$year);
	echo"
	<table border='1' cellpadding='0' cellspacing='0' align='center'>
	<tr>
	<th colspan='2'>EMPLOYER NO. <u>$f</u> YEAR <u>$year</u></th>
	
	</tr>
	<tr><th colspan='2'>SALARY FORM</th></tr>
	<tr><td colspan='2'>NAME : $fullname</td></tr>
	<tr><td colspan='2'>RANK : $s->rank</td></tr>
	<tr><td colspan='2'>DEPT.: $department</td></tr>
	<tr><td colspan='2'>S/SCALE : $sscale</td></tr>
	</table></p></b>";
	
	
 $sql="";
	 
	 $r=@mysqli_query($con, "select distinct payment_type from payroll_scheduletb where year='$year' and fileno in ($fileno) order by payment_type") or die( mysqli_error($con));
	  
 if(@mysqli_num_rows($r)>=1)
	 {
	 ?><table  border="1" cellpadding="0" cellspacing="0" align="center"><?php
	 	while($rs=@mysqli_fetch_array($r))
				{
					$pay_type=@$rs['payment_type'];
					?>
					
						<tr>
							<th rowspan="2"><?php if(strtolower($pay_type)=='allowance') echo "TAXABLE PAY";else echo "DEDUCTIONS"; ?></th>  <?php if(strtolower($pay_type)=='allowance')
									{ echo "<th colspan='13'>MONTHS</th>";}
									
									?>
						</tr>	
						<tr>
						<?php
							$r_month=@mysqli_query($con, "select distinct month from payroll_scheduletb where year='$year' and fileno in ($fileno) order by convert(month,decimal)");
							$month_array=array();$tr="";
							while($rs_m=@mysqli_fetch_array($r_month))
								{
									$m=@$rs_m['month'];$month_array[]=@$rs_m['month'];
									$m_name=@get_month_name($m);
									$tr .="<th>$m_name</th>";
									
									?>
									<!--<th><?php echo $m_name; ?></th>-->
									<?php
									
								}//end of loop for the distinct month in a year
								if(strtolower($pay_type)=='allowance')
									{
										echo "$tr<th>TOTAL</th>";
									}
								else
									echo "<th colspan='14'></th>";
						?></tr>
						<?php
							$r_al=@mysqli_query($con, "select distinct p.folio_code,f.title from payroll_scheduletb p, foliotb f where f.folio_code=p.folio_code and p.year='$year' and p.payment_type='$pay_type' and fileno in ($fileno) order by p.folio_code") ;$code_array=array();
				 $trow=0;
				while($rs_al=@mysqli_fetch_array($r_al))
					{
						$title=@$rs_al['title'];
						$folio=@$rs_al['folio_code'];
						$code_array[]=$folio;
						
						echo "<tr><td>$title</td>";
						
						foreach($month_array as $mon)
						{
							$trow +=get_basic_amount($f,$mon,$year,$folio);
							echo "<td><center>".number_format(get_basic_amount($f,$mon,$year,$folio),2)."</center></td>";
							
						}// end of foreach loop for folio code amount per month
						echo "<td>".number_format($trow,2)."</td></tr>";
					}// end of loop for folio code
						
						?>
					<tr>
					<th><?php if(strtolower($pay_type)=='allowance') echo "GROSS PAY";else echo "TOTAL DEDUCTIONS"; ?></th>
					<?php
					$trow=0;
					foreach($month_array as $mon)
						{
						
						$trow +=get_total_payment_type($f,$mon,$year,$pay_type);
							echo "<th>".number_format(get_total_payment_type($f,$mon,$year,$pay_type),2)."</th>";
							
						}// end of foreach loop for folio code amount per month
						
						echo "<th>".number_format($trow,2)."</th>";
					?>
					
					
					
					</tr>
					<tr><th colspan="14">&nbsp;</th></tr>
					<?php
					$trow=0;
					if(strtolower($pay_type)=='deduction')
									{
									echo "<tr><th>TOTAL NET PAY</th>";
									
									foreach($month_array as $mon)
										{
											$net_pay_per_month=get_total_payment_type($f,$mon,$year,'allowance')- get_total_payment_type($f,$mon,$year,'deduction');
										$trow +=$net_pay_per_month;
											echo "<th>".number_format($net_pay_per_month,2)."</th>";
											
										}// end of foreach loop for folio code amount per month
									
									echo "<th>".number_format($trow,2)."</th></tr>";
									}
					
				}//end of loop for payment type
				?></table>
				
	 	
		 
		 
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
		
 
 
 }//end of Individual pay record card
 
 ////////////************ Section for Group pay record card **********************///////////
 
 //////////**********************************************************************************************////////////////
if($option=='group_prc' )
 { 
 if($year=='' or $month=='')
 	{
		echo "<script>alert('Select Month and Year');window.close();</script>";
		exit;
	}
	echo "<b><p align='center'><u>PAYMENT RECORD CARD FOR THE MONTH OF " .get_month_name($month_code).", $year.</u></p></b>";
	$r=@mysqli_query($con, "select distinct payment_type from payroll_scheduletb where year='$year' and month='$month' order by payment_type") or die( mysqli_error($con));
	  
 if(@mysqli_num_rows($r)>=1)
	 { $tb="";
	 $tb .= "<table  border='1' cellpadding='0' cellspacing='0' align='center'>";
	 $pay_type_array=array();$allowance_code_array=array();$deduction_code_array=array();
	 while($rs=@mysqli_fetch_array($r))
		{
			$pay_type_array[]=$pay_type=@$rs['payment_type'];
		}//end of payment type
		$a_crow=0;$d_crow=0;
	 	foreach($pay_type_array as $pay_type)
		{
			$r_al=@mysqli_query($con, "select distinct p.folio_code,f.title from payroll_scheduletb p, foliotb f where f.folio_code=p.folio_code and p.year='$year' and p.month='$month' and p.payment_type='$pay_type' order by p.folio_code") ;$code_array=array();
								 
								while($rs_al=@mysqli_fetch_array($r_al))
									{
										if(strtolower($pay_type)=='allowance')
											{++$a_crow;
												$allowance_code_array[]=@$rs_al['title']."***".$rs_al['folio_code'];
											}
										else
											{++$d_crow;
												$deduction_code_array[]=@$rs_al['title']."***".$rs_al['folio_code'];
											}
										$title=@$rs_al['title'];
										$folio=@$rs_al['folio_code'];
										$code_array[]=$folio;
										
										//echo "<td>$title</td>";
									}// end of while for folio code
									//if(strtolower($pay_type)=='allowance') echo "<th>GROSS PAY</th>";else echo "<th >TOTAL DEDUCTIONS</th>";						
									
									}// end of foreach
		
		
					?>
					<table  border='1' cellpadding='0' cellspacing='0' align='center'>
						<tr>
							<th rowspan='2'>S/No</th>
							<th rowspan='2'>File Num.</th>
							<th rowspan='2'>Full-Name</th>
							<th rowspan='2'>Department</th>
							<th rowspan='2'>Salary Scale</th>
							<th colspan='<?php echo $a_crow;?>'>TAXABLE PAY</th>
							<th rowspan='2'>GROSS PAY</th>
							<th colspan='<?php echo $d_crow;?>'>DEDUCTIONS</th>
							<th rowspan='2'>TOTAL DEDUCTION</th>
							<th rowspan='2'>NET PAY</th>
							
						</tr>	
						<tr>
						<?php
						foreach($allowance_code_array as $all)
									{
										$folio=explode("***",$all);
										echo "<th>".$folio[0]."</th>";
									}
									
						foreach($deduction_code_array as $ded)
									{
										$folio=explode("***",$ded);
										echo "<th>".$folio[0]."</th>";
									}
						?>
						</tr>
						<?php
							$sql="select distinct fileno,department,fullname,level,step from payroll_scheduletb where month='$month' and year='$year' and fileno not in ('weathstone','admin')";
							 if($dept !="all")
								$sql .=" and department='$dept'";
							 if ($staff !="all")
								{
									$fileno=@set_comma_breakdown($f);
									$sql .=" and fileno in ($fileno)";
									
								}
							 if($category !="")
								$sql .=" and category='$category'";
							
							if($status !="")
								$sql .=" and staff_status='$status'";
								
							$sql .=" order by department,convert(level,decimal),convert(step,decimal),convert(fileno,decimal)";
							 $r=@mysqli_query($con, $sql);
							if( mysqli_num_rows($r)>0)
							{ $i=0;
								while($rs=@mysqli_fetch_array($r))
									{
										++$i;$fno=@$rs['fileno'];$fullname=@$rs['fullname'];$department=@$rs['department'];$level=@sprintf("%02d",$rs['level']);$step=@sprintf("%02d",$rs['step']);
										$bank=@$rs['bank_name'];$acct_no=@$rs['acct_no'];
										$basic_code=@get_folio_code('basic salary');
										$basic_amount=@get_basic_amount($fno,$month,$year,$basic_code);
										$allowance=@get_total_allowance_excluding_basic($fno,$month,$year,$basic_code);
										$deduction=@get_total_payment_type($fno,$month,$year,'Deduction');
										$gross=$basic_amount+$allowance;
										$net=$gross-$deduction;
										
										
										echo "
												<tr><td>$i</td>
								<td>$fno</td><td>$fullname</td><td>$department</td><td>Level: $level / Step $step </td>";
								$trow_a=0;$trow_d=0;
								foreach($allowance_code_array as $all)
									{
										$folio=explode("***",$all);
										$trow_a +=get_basic_amount($fno,$month,$year,$folio[1]);
										echo "<td><center>".number_format(get_basic_amount($fno,$month,$year,$folio[1]),2)."</center></td>";
									}
									echo "<th>".number_format($trow_a,2)."</th>";
								foreach($deduction_code_array as $ded)
									{
										$folio=explode("***",$ded);
										$trow_d +=get_basic_amount($fno,$month,$year,$folio[1]);
										echo "<td><center>".number_format(get_basic_amount($fno,$month,$year,$folio[1]),2)."</center></td>";
									}
									echo "<th>".number_format($trow_d,2)."</th>";
									$net_pay=$trow_a-$trow_d;
									echo "<th>".number_format($net_pay,2)."</th></tr>";
								
								}//end of loop for each of staff
								$trow_a=0;$trow_d=0;
								echo "<tr><th colspan='5'>GRAND TOTAL (=N=): </th>";
								foreach($allowance_code_array as $all)
								{
								
								$folio=explode("***",$all);
										$trow_a +=get_column_folio_total($status,$category,$month,$year,$folio[1],$dept,'');
										echo "<th><center>".number_format(get_column_folio_total($status,$category,$month,$year,$folio[1],$dept,''),2)."</center></th>";
								}			
								echo "<th>".number_format($trow_a,2)."</th>";
								
								foreach($deduction_code_array as $ded)
									{
										$folio=explode("***",$ded);
										$trow_d +=get_column_folio_total($status,$category,$month,$year,$folio[1],$dept,'');
										echo "<th><center>".number_format(get_column_folio_total($status,$category,$month,$year,$folio[1],$dept,''),2)."</center></th>";
									}
									echo "<th>".number_format($trow_d,2)."</th>";
									$net_pay=$trow_a-$trow_d;
									echo "<th>".number_format($net_pay,2)."</th></tr>";
					
					}//// end of record found
							 else
								echo "<script>alert('No record to display');window.close();</script>";
						
						
						
						?>
					
				
				</table>
				
	 	
		 
		 
		 <?php
	 }// end of record found
	 else
	 	echo "<script>alert('No record to display');window.close();</script>";
		
 
 
 }//end of Individual pay record card
 
 
 
 
 
 
 
 
 
///////////////////////////////////////////////////// End of Body ////////////////////////////////////////////
//////////////////////////////////////////////////////Report Footer /////////////////////////////////////////////
///////////////////////////////////////////////////// End of Footer ////////////////////////////////////////////
?>
</body>
</html>