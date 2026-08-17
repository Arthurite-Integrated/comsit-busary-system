<?php
 @session_start();
 @ini_set('max_execution_time', 60000000000);
 @ini_set("memory_limit", "51200M");
 @require_once('connect.php');

// @require_once "required_jQuery_files.php";
 //$id=@$_REQUEST['contentvar'];
function numberToRoman($number) {
	 if($number <= 0) return '';
    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
    $returnValue = '';
    while ($number > 0) {
        foreach ($map as $roman => $int) {
            if($number >= $int) {
                $number -= $int;
                $returnValue .= $roman;
                break;
            }
        }
    }
    return $returnValue;
}
 function logs($fileno,$log_type,$log_desc)
 {
	global $con;
	  $log_date2=date('l, F d, Y');
   @mysqli_query($con, "insert into portal_logstb set regno='$fileno',log_type='$log_type',log_desc='$log_desc',log_date=CURDATE(),log_time=CURTIME(), log_date_desc='$log_date2', entry_by='$fileno'");
 
 }
function generate_new_appno($phone_no,$session)
{
global $con;
	$res_a=@mysqli_query($con, "select count(*) as total from candidatetb where session='$session'");
	$rs_a=@mysqli_fetch_array($res_a);
	$year=substr($session,0,4);
	$total=@$rs_a['total'];
	$i=sprintf("%05d", $total+1);
	$prefix="KWASU/PG$year/$i";
	return ($prefix);
}

function get_dept_name($dept_code) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select dept_name from departmenttb where dept_code='$dept_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['dept_name'];
	 return($val);
 }
 function prepare_transdate($month_code,$year) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select month_end from monthtb where month_code='$month_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=$year."-".sprintf("%02d",$month_code)."-".$rs_p['month_end'];
	 return($val);
 }
function get_dept_name_act($dept_code) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select department_name from account_departments where department_code='$dept_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['department_name'];
	 return($val);
 }
function get_account_name($acctcode) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select acctname from bank_accounttb where acctcode='$acctcode'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['acctname'];
	 return($val);
 }
 
 function get_unit_name($dept_code,$unit_code) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select unit_name from unittb where dept_code='$dept_code' and unit_code='$unit_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['unit_name'];
	 return($val);
 }
 
 function get_folio_name($folio_code) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select title from foliotb where folio_code='$folio_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['title'];
	 return($val);
 }
 function get_month_name($month) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select month_name from monthtb where month_code='$month'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['month_name'];
	 return($val);
 }
 function get_staff_name($fileno) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select title,surname,first_name,other_name from stafftb where fileno='$fileno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['title']." ".strtoupper(@$rs_p['surname'])." ".ucfirst(strtolower(@$rs_p['first_name']))." ".ucfirst(strtolower(@$rs_p['other_name']));
	 return($val);
 }
 
 function get_staff_rank($fileno) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select rank from stafftb where fileno='$fileno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['rank'];
	 return($val);
 }
 
 function get_budget($folio_code,$year)
 {
global $con;
	$res_b=@mysqli_query($con, "select sum(amount) as total from budgettb where budget_year='$year' and folio_code='$folio_code'");
	$rs_b=@mysqli_fetch_array($res_b);
	$b_amount=$rs_b['total'];
	//get the amount that have been spent on from the transaction table
	$res_s=@mysqli_query($con, "select sum(amount) as total_spent from transtb where folio_code='$folio_code' and year(transdate)='$year' and transtype='Credit'");
	$rs_s=@mysqli_fetch_array($res_s); $amount_spent=$rs_s['total_spent'];
	if($amount_spent< $b_amount)
		$total=$b_amount - $amount_spent;
	return $total;
 }
 
 function get_company() 
 {
global $con;
	 $res_p=@mysqli_query($con, "select * from companytb");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $name=@$rs_p['company_name'];
	 $logo=@$rs_p['company_logo'];
	 $val=$name."***".$logo;
	 return($val);
 }
 
 function get_role_caption($role)
 {
global $con;
	 $res_p=@mysqli_query($con, "select caption from roletb where role='$role'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['caption'];
	 return($val);
 }
function set_comma_breakdown($fno)
{
global $con;
	$ff=explode(",",$fno);
foreach($ff as $f)
	{
		$fileno .="'$f',";
	}
		$len=strlen($fileno)-1;
	$fileno=substr($fileno,0,$len);
	return ($fileno);
}
function get_current_scalename()
{
global $con;
	$r=@mysqli_query($con, "select scale_name from scale_nametb where status='Active' limit 1");
	$rs=@mysqli_fetch_array($r);
	
	return($rs['scale_name']);
}
function excepted($fileno,$code,$month,$year)
{
global $con;
	$r=@mysqli_query($con, "select * from deduction_exceptiontb where fileno='$fileno' and folio_code='$code' and month='$month' and year='$year'");
	if(@mysqli_num_rows($r)>0)
		return true;
	else
		return false;
}
function prorata($fileno,$month,$year)
{
global $con;
	$r=@mysqli_query($con, "select no_of_days from proratatb where fileno='$fileno' and month='$month' and year='$year' limit 1");
	if(@mysqli_num_rows($r)>0)
		{
			$rs=@mysqli_fetch_array($r);
			$days=@$rs['no_of_days'];
			
		}
	else
	$days="";
	return ($days);

}
function insert_into_payroll($fileno,$dept,$staffstatus,$category,$scalename,$level,$step,$fullname,$bankname,$acctno,$month,$year,$transdate,$folio_code,$paymenttype,$amount,$login_id)
{
global $con;
	@mysqli_query($con, "insert into payroll_scheduletb set fileno='$fileno',department='$dept',staff_status='$staffstatus',category='$category',scale_name='$scalename',level='$level',step='$step',fullname='$fullname',bank_name='$bankname',acct_no='$acctno',month='$month',year='$year',transdate='$transdate',folio_code='$folio_code',payment_type='$paymenttype',amount='$amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") ;
}
function get_staff_status($level)
{
global $con;
$r=@mysqli_query($con, "select staff_status from level_categorytb where level='$level' limit 1");
	$rs=@mysqli_fetch_array($r);
	
	return($rs['staff_status']);
}
function get_deduction_defintion($code,$cat,$status,$sex,$religion,$level,$staffstatus,$staffsex,$staffrelgion)
{
global $con;
	if(strtolower($cat)=="all")
		{
			$r=@mysqli_query($con, "select * from deductiontb where category='$cat' and folio_code='$code'");
			if(@mysqli_num_rows($r)>0)
				{
					$rs=@mysqli_fetch_array($r);
					$val=@$rs['criteria']."***".$rs['value']."***1";
				
				}// end of record found in deductiontb with criteria category='$cat' and folio_code='$code'
			else
				$val="0***0***0";
				
		}// end of category is All
		else
		{
		
			// Note param 1=Staff_status, param 2=Sex, param 3=Religion
		/*	$array=array(			row0=>array('value','value','value'),
									row1=>array('value','value','all'),
									row2=>array('value','all','value'),
									row3=>array('value','all','all'),
									row4=>array('all','value','value'),
									row5=>array('all','value','all'),
									row6=>array('all','all','value'),
									row7=>array('all','all','all')
								); */
			//foreach ($array as $k=>$a)
			//	{
					//$staffstatus=$a[0];$staffsex=$a[1];$staffrelgion=$a[2];
					if($staffstatus=='value' and $staffsex=='value' and $staffrelgion=='value')
						$sql="select * from deductiontb where category='$cat' and folio_code='$code' and staff_status='$status' and sex='$sex' and religion='$religion' and level='$level'";
					elseif($staffstatus=='value' and $staffsex=='value' and $staffrelgion=='all')
						$sql="select * from deductiontb where category='$cat' and folio_code='$code' and staff_status='$status' and sex='$sex' and religion='All' and level='$level'";
					elseif($staffstatus=='value' and $staffsex=='all' and $staffrelgion=='value')
					$sql="select * from deductiontb where category='$cat' and folio_code='$code' and staff_status='$status' and sex='All' and religion='$religion' and level='$level'";
					elseif($staffstatus=='value' and $staffsex=='all' and $staffrelgion=='all')
					$sql="select * from deductiontb where category='$cat' and folio_code='$code' and staff_status='$status' and sex='All' and religion='All' and level='$level'";
					elseif($staffstatus=='all' and $staffsex=='value' and $staffrelgion=='value')
					$sql="select * from deductiontb where category='$cat' and folio_code='$code' and staff_status='All' and sex='$sex' and religion='$religion' and level='$level'";
					elseif($staffstatus=='all' and $staffsex=='value' and $staffrelgion=='all')
					$sql="select * from deductiontb where category='$cat' and folio_code='$code' and staff_status='All' and sex='$sex' and religion='All' and level='$level'";
					elseif($staffstatus=='all' and $staffsex=='all' and $staffrelgion=='value')
					$sql="select * from deductiontb where category='$cat' and folio_code='$code' and staff_status='All' and sex='All' and religion='$religion' and level='$level'";
					elseif($staffstatus=='all' and $staffsex=='all' and $staffrelgion=='all')
					$sql="select * from deductiontb where category='$cat' and folio_code='$code' and staff_status='All' and sex='All' and religion='All' and level='$level'";
				//  }// end of  first Array
				
				
				$r=@mysqli_query($con, $sql);
			if(@mysqli_num_rows($r)>0)
				{
					$rs=@mysqli_fetch_array($r);
					$val=@$rs['criteria']."***".$rs['value']."***1";
				
				}// end of record found in deductiontb with criteria category='$cat' and folio_code='$code'
			else
				$val="0***0***0";
		}// end of category is categorized
		
		
		return ($val);
}
function get_folio_code($p)
{
global $con;
$res_p=@mysqli_query($con, "select folio_code from foliotb where title like '%$p%'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['folio_code'];
	 return($val);
}
function get_folio_code_amount($level,$step,$category,$code)
{
global $con;
$res_p=@mysqli_query($con, "select amount from salary_scaletb where level='$level' and step='$step' and category='$category' and folio_code='$code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['amount'];
	 return($val);
}
function get_gross_total($level,$step,$category)
{
global $con;
$res_p=@mysqli_query($con, "select sum(amount) as total FROM salary_scaletb where level='$level' and step='$step' and category='$category'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total'];
	 return($val);
}
function get_basic_amount($fno,$month,$year,$code)
{
global $con;
$res_p=@mysqli_query($con, "select amount from payroll_scheduletb where fileno='$fno' and month='$month' and year='$year' and folio_code='$code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['amount'];
	 return($val);
}
function get_total_allowance_excluding_basic($fno,$month,$year,$code)
{
global $con;
$res_p=@mysqli_query($con, "select sum(amount) as total_amount from payroll_scheduletb where fileno='$fno' and month='$month' and year='$year' and folio_code not in ('$code') and payment_type='Allowance'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_amount'];
	 return($val);
}
function get_total_payment_type($fno,$month,$year,$type)
{
global $con;
$res_p=@mysqli_query($con, "select sum(amount) as total_amount from payroll_scheduletb where fileno='$fno' and month='$month' and year='$year' and payment_type='$type'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_amount'];
	 return($val);
}
function getQuarterlyLimit($quarter, $year, $budgetcode)
 {
global $con;
	 $budgetyear =  mysqli_real_escape_string($con, $year);
	 $budget_folio_code =  mysqli_real_escape_string($con, $budgetcode);
	 $budgetAmount=get_budget($budget_folio_code, $budgetyear);

	 if($quarter=="1st Quarter")
	 	$percent = ($budgetAmount/100.0)*15.0;
	 if($quarter=="2nd Quarter")
	 	$percent = ($budgetAmount/100.0)*30.0;
	 if($quarter=="3rd Quarter")
	 	$percent = ($budgetAmount/100.0)*45.0;
	 if($quarter=="4th Quarter")
	 	$percent = ($budgetAmount/100.0)*60.0;
	 return $percent;
 }
function get_column_folio_total($status,$category,$month,$year,$code,$dept,$fileno='')
{
global $con;
	$sql="select sum(amount) as total_amount from payroll_scheduletb where month='$month' and year='$year' and staff_status='$status' and category='$category' and folio_code='$code'";
	if($dept !="all")
	 	$sql .=" and department='$dept'";
	 if ($fileno !="")
	 	{
			$fileno=@set_comma_breakdown($fileno);
	 		$sql .=" and fileno in ($fileno)";
			
		}

$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_amount'];
	 return($val);
}
function get_folio_total_per_dept($dept,$month,$year,$code,$status,$category)
{
global $con;
	$sql="select sum(amount) as total_amount from payroll_scheduletb where month='$month' and year='$year' and staff_status='$status' and folio_code='$code'";
	if($dept !="all")
	 	$sql .=" and department='$dept'";
	 if ($category !="")
	 	{$sql .=" and category='$category'";
		}

$res_p=@mysqli_query($con, $sql) or die( mysqli_error($con));
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_amount'];
	 return($val);
}

function get_folio_budget($folio, $year)
{
global $con;
$res_p=@mysqli_query($con, "select sum(amount) as total_budget from budgettb where folio_code='$folio' and budget_year='$year' ");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_budget'];
	 return($val);
}
function get_folio_budget_inc($rev_code, $year)
{
global $con;
$res_p=@mysqli_query($con, "select sum(amount) as total_budget from budget_income where rev_code='$rev_code' and year='$year' ");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_budget'];
	 return($val);
}

function get_folio_budget_expences($folio, $month, $year)
{
global $con;
///$sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";
$sql="select sum(amount) as total_expences from budget_votebooktb where budget_folio_code='$folio' and operation_year='$year'";
if ($month!="")
{
$sql.= "and operation_month <= '$month'";
}
$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_expences'];
	 return($val);
}
function folio_budget_generate2($folio,$from,$to)
{
global $con;
///$sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";

$sql="select sum(amount) as total_income from transtb
 where folio_code='$folio' and transtype = 'Credit' and transdate between '$from' and '$to'";
$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_income'];
	 return($val);
}
function folio_budget_generate5($rev_code,$from,$to)
{
global $con;
///$sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";

$sql="select sum(amount) as total_income from transtb
 where rev_code='$rev_code' and transtype = 'Credit' and transdate between '$from' and '$to'";
$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_income'];
	 return($val);
}
function folio_budget_generate3($folio,$from)
{
global $con;
///$sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";

$sql="select * from transtb
 where folio_code='$folio' and transtype = 'Credit' and transdate = '$from'";
$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val1=@$rs_p['amount'];
	  $val2=@$rs_p['receiptno'];
	 $val =  $val1.'***'.$val2;
	 return($val);
}
function folio_budget_generate4($rev_code,$from)
{
global $con;
///$sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";

$sql="select * from transtb
 where rev_code='$rev_code' and transtype = 'Credit' and transdate = '$from'";
$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val1=@$rs_p['amount'];
	  $val2=@$rs_p['receiptno'];
	 $val =  $val1.'***'.$val2;
	 return($val);
}
function folio_budget_generate6($folio_code,$year)
{
global $con;

$sql="select sum(amount) as amount_use from transtb where folio_code = '$folio_code'  and transtype = 'Credit' and year(transdate)='$year' and rev_code != ''";
$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val1=@$rs_p['amount_use'];
	  //$val2=@$rs_p['receiptno'];
	 $val =  $val1;
	 return($val);
}

function folio_budget_generate($folio,$year)
{
global $con;
///$sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";
$sql="select sum(amount) as total_income from transtb
 where folio_code='$folio' and transtype = 'Credit' and year(transdate)='$year'";

$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_income'];
	 return($val);
}
function folio_budget_generater($rev_code,$year)
{
global $con;
///$sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";
$sql="select sum(amount) as total_income from transtb
 where rev_code='$rev_code' and transtype = 'Credit' and year(transdate)='$year'";

$res_p=@mysqli_query($con, $sql);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_income'];
	 return($val);
}

function get_budget_balance($folio,$month,$year)
 {
global $con;
	$balance = get_folio_budget($folio, $year) - get_folio_budget_expences($folio, $month, $year);
	/*$res_b=@mysqli_query($con, "select sum(amount) as total from budgettb where budget_year='$year' and folio_code='$folio'");
	$rs_b=@mysqli_fetch_array($res_b);
	$b_amount=$rs_b['total'];
	//get the amount that have been spent on from the transaction table
	 $sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";
if ($month!="")
{
$sql.= "and month(transdate)<='$month'";
}
$res_p=@mysqli_query($con, $sql);
$rs_p=@mysqli_fetch_array($res_p);
$amount_spent=$rs_p['total_expences'];
	//if($amount_spent< $b_amount)
		$total=$b_amount - $amount_spent;*/
	return $balance;
 }

function get_var_balance($folio,$year)
 {
global $con;
	$balance = get_folio_budget($folio, $year) - folio_budget_generate($folio, $year);
	/*$res_b=@mysqli_query($con, "select sum(amount) as total from budgettb where budget_year='$year' and folio_code='$folio'");
	$rs_b=@mysqli_fetch_array($res_b);
	$b_amount=$rs_b['total'];
	//get the amount that have been spent on from the transaction table
	 $sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";
if ($month!="")
{
$sql.= "and month(transdate)<='$month'";
}
$res_p=@mysqli_query($con, $sql);
$rs_p=@mysqli_fetch_array($res_p);
$amount_spent=$rs_p['total_expences'];
	//if($amount_spent< $b_amount)
		$total=$b_amount - $amount_spent;*/
	return $balance;
 }
function get_var_balancer($rev_code,$year)
 {
global $con;
	$balance = get_folio_budget($folio, $year) - folio_budget_generater($rev_code, $year);
	/*$res_b=@mysqli_query($con, "select sum(amount) as total from budgettb where budget_year='$year' and folio_code='$folio'");
	$rs_b=@mysqli_fetch_array($res_b);
	$b_amount=$rs_b['total'];
	//get the amount that have been spent on from the transaction table
	 $sql="select sum(amount) as total_expences from transtb where folio_code='$folio' and year(transdate)='$year'";
if ($month!="")
{
$sql.= "and month(transdate)<='$month'";
}
$res_p=@mysqli_query($con, $sql);
$rs_p=@mysqli_fetch_array($res_p);
$amount_spent=$rs_p['total_expences'];
	//if($amount_spent< $b_amount)
		$total=$b_amount - $amount_spent;*/
	return $balance;
 }


?>

 
 
 
 
 
  