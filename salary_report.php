<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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

<body>
<?php
ini_set('max_execution_time', 60000000000);
ini_set("memory_limit", "51200M");
$mode=base64_decode($_REQUEST['mode']);
$month_code=$month=$_REQUEST['month']; $year=$_REQUEST['year'];
$status=$_REQUEST['status']; $category=$_REQUEST['category'];
$staff=$_REQUEST['staff']; $dept=$_REQUEST['dept'];
$option=$_REQUEST['v_opt'];$fileno=$f=$_REQUEST['fileno']; 
$folio_code=$_REQUEST['folio_code']; 

require_once "function_b.php";
@require_once('class/mysqli_class.php');
$db = new Database();
$db->connect();
//echo "$month_code $year<br>$status $category<br>$staff $dept<br>$option $fileno MODE:".$mode;
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////

$val=explode("***",get_company());
	 
	 echo "<center><img src='$val[1]' width='50' height='50' /></center><b><p align='center'><u>".strtoupper($val[0])."<br/>STAFF CATEGORY :".ucfirst(strtoupper($status))."  ".ucwords(strtoupper($category))."<br/></u></p></b>";
	

///////////////////////////////////////////////////// End of header ////////////////////////////////////////////

 $sql="select distinct fileno,department,fullname,level,step,bank_name,acct_no from payroll_scheduletb where staff_status='$status' and category='$category' and month='$month' and year='$year'";
	 if($dept !="all")
	 	$sql .=" and department='$dept'";
	 if ($staff !="all")
	 	{
			$fileno=@set_comma_breakdown($fileno);
	 		$sql .=" and fileno in ($fileno)";
			
		}
	$sql .=" order by department,convert(fileno,decimal)";
	 $r=@mysqli_query($con, $sql);

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
					$basic_code=@get_folio_code('basic salary');
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