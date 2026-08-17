<?php
 @session_start();
 @require_once('connect.php');
 
 $id=@$_REQUEST['contentvar'];
	require_once("myclass_m.php");
	$bursary=new myclass_m();
 
function begin(){
global $con;
    // mysqli_query($con, "BEGIN");
	 mysqli_query($con, "START TRANSACTION");
}

function commit(){
global $con;
     mysqli_query($con, "COMMIT");
}

function rollback(){
global $con;
     mysqli_query($con, "ROLLBACK");
}

function get_account_code_narration($code) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select title from salary_codetb where account_code='$code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['title'];
	 return($val);
 }
 
function read_voucher_vote_code($pvno)
{
global $con;

$res_d=@mysqli_query($con, "select * from voucher_folio_codetb where pvno='$pvno' LIMIT 1");
	//$rs_d=@mysqli_fetch_array($res_d);
	$v="";
while($rs_d=@mysqli_fetch_array($res_d))
	{
		$cd = explode('-', $rs_d['folio_code']);
		$res_ds=@mysqli_query($con, "select * from account_departments where department_code='".$cd[1]."'");
		while($rs_ds=@mysqli_fetch_array($res_ds)) $v =$rs_ds['department_name'];
	}
	return $v;
}

function read_gross($pvno)
{
global $con;
$pv = explode('_', $pvno);
	//return "select sum(amount) AS TSUM from voucher_folio_codetb where pvno LIKE '".$pv[0]."%' and pvno != ''";
	/*if($pv[0] != ''){
		$res_d=@mysqli_query($con, "select sum(amount) AS TSUM from voucher_folio_codetb where pvno LIKE '".$pv[0]."%' and pvno != ''");
		$rs_d=@mysqli_fetch_array($res_d, 3 );
	}else*/{
		$res_d=@mysqli_query($con, "select amount_approved from vouchertb where pvno = '".$pv[0]."'");
		$rs_d=@mysqli_fetch_array($res_d, 3 );
	}
	$v = "&#8358;".number_format($rs_d[0], 2);
	
/*	$v="";
while($rs_d=@mysqli_fetch_array($res_d))
	{
		$pv = explode('_', $pvno);
		$res_ds=@mysqli_query($con, "select amount_approved from vouchertb where pvno='".$pv[0]."'");
		while($rs_ds=@mysqli_fetch_array($res_ds)) $amnt_app = $rs_ds[0];
		$v = "&#8358;".number_format($amnt_app, 2);
	}*/
	return $v;
}

function get_voucher_folio_code($pvno, $type)
{
global $con;

$res_d=@mysqli_query($con, "select * from voucher_folio_codetb where pvno='$pvno'");
	//$rs_d=@mysqli_fetch_array($res_d);
	$v="";
while($rs_d=@mysqli_fetch_array($res_d))
	{
		if($type == 'Code') $v .= $rs_d['folio_code']."<br>"; 
		
		if($type == 'Title') $v .= @get_folio_name($rs_d['folio_code'])."<br>";
	}
	return $v;
}

/*/*/
function read_folio_ca($pvno, $type)
{
global $con;

$res_d=@mysqli_query($con, "select * from voucher_folio_codetb where pvno='$pvno'");
	//$rs_d=@mysqli_fetch_array($res_d);
	$v="";
while($rs_d=@mysqli_fetch_array($res_d))
		$v .= ";".$rs_d[$type];
	return substr($v, 1);
}
/* */

function read_voucher_folio_code($pvno)
{
global $con;

$res_d=@mysqli_query($con, "select * from voucher_folio_codetb where pvno='$pvno'");
	//$rs_d=@mysqli_fetch_array($res_d);
	$v="";
while($rs_d=@mysqli_fetch_array($res_d))
	{
		$pv = explode('_', $pvno);
		$res_ds=@mysqli_query($con, "select amount_approved from vouchertb where pvno='".$pv[0]."'");
		while($rs_ds=@mysqli_fetch_array($res_ds)) $amnt_app = $rs_ds[0];
		//$v .=$rs_d['folio_code']." :". @get_folio_name($rs_d['folio_code'])." : <strong>GROSS::&#8358;".number_format($amnt_app, 2)."</strong><br>";
		$v .=$rs_d['folio_code']." :". @get_folio_name($rs_d['folio_code'])."<br>";
	}
	return $v;
}

 function logs($fileno,$log_type,$log_desc)
 {
global $con;
   @mysqli_query($con, "insert into portal_logstb set regno='$fileno',log_type='$log_type',log_desc='$log_desc',log_date=CURDATE(),log_time=CURTIME()");
 
 }
 function get_tax_detail($tax_folio_code)
 {
global $con;
 	$r=@mysqli_query($con, "select * from tax_ratetb where folio_code='$tax_folio_code'");
	return  mysqli_fetch_array($r);
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

function generate_appno($app_year)
{
global $con;
	$res_a=@mysqli_query($con, "select count(*) as total from hr_applicanttb where app_year='$app_year'");
	$rs_a=@mysqli_fetch_array($res_a);
	$year=$app_year;
	$total=@$rs_a['total'];
	$i=sprintf("%05d", $total+1);
	$prefix="AP/$year/$i";
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

function get_dept_name_act($dept_code) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select department_name from account_departments where department_code='$dept_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['department_name'];
	 return($val);
 }
 function get_voucher_memoid($pvno) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select memo_id from vouchertb where pvno='$pvno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['memo_id'];
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
	 if($dept_code!='') $res_p=@mysqli_query($con, "SELECT unit_name from unittb where dept_code='$dept_code' and unit_code='$unit_code'");
	 else $res_p=@mysqli_query($con, "SELECT unit_name from unittb where unit_code='$unit_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['unit_name'];
	 return($val);
 }
 
 function get_folio_name($folio_code) 
 {
	global $con;
	//return $folio_code."CODE";
	//return "select title from foliotb where folio_code='$folio_code'";
	 $res_p=@mysqli_query($con, "select title from foliotb where folio_code='$folio_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['title'];
	 if($val==''){
		 $res_p=@mysqli_query($con, "select distinct ncoa_title from foliotb where ncoa_code='$folio_code'");
		 $rs_p=@mysqli_fetch_array($res_p);
		 $val=@$rs_p['ncoa_title'];
	 }
	 return($val);
 }
 
function get_folio_code($p)
{
global $con;
$res_p=@mysqli_query($con, "select folio_code from foliotb where title like '%$p%'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['folio_code'];
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
 
 function get_applicant_name($appno) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select title,surname,first_name,other_name from hr_applicanttb where appno='$appno'");
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
	if($b_amount==0) $total=''; else $total=0;
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
	 $add=@$rs_p['company_address'];
	 $val=$name."***".$logo."***".$add;
	 return($val);
 }
 
 function get_project_title() 
 {
global $con;
	 $res_p=@mysqli_query($con, "select * from project_titletb where status='Active'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $title=@$rs_p['title'];
	 $status=@$rs_p['status']; //active or inactive
	 $project_type=@$rs_p['project_type']; //bursary, HR, Both
	 $power_by=@$rs_p['power_by'];
	 
	 $val=$title."***".$project_type."***".$status."***".$power_by;
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
 
function generate_schedule_no()
{
global $con;
	$res_a=@mysqli_query($con, "select count(distinct schedule_no) as total from scheduletb");
	$rs_a=@mysqli_fetch_array($res_a);
	$total=@$rs_a['total'];
	$month_name=@date('F',strtotime(date('Y-m-d'))); $month_no=@date('m',strtotime(date('Y-m-d')));
	$year=@date('Y',strtotime(date('Y-m-d')));
	 
	//$no=sprintf("%04d",$rs_p['total'] + 1);
	$no=$total + 1;
	$prefix=strtoupper($month_name."/".$year."/". $no);
	return ($prefix);
}

function insert_transaction($fileno,$dept_acctcode,$acctcode,$folio_code,$transtype,$transdate,$amount,$receiptno,$comment,$login_id,$pvno,$payee_name)
{
global $con;
	 mysqli_query($con, "insert into transtb set fileno='$fileno', dept_acctcode='$dept_acctcode',acctcode='$acctcode',folio_code='$folio_code',transtype='$transtype',transdate='$transdate',amount='$amount',receiptno='$receiptno',comment='$comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id',pvno='$pvno',payee='$payee_name'") or die( mysqli_error($con));
}

function get_tax_account($folio_code)
{
global $con;
	$res_p=@mysqli_query($con, "select acctcode from tax_ratetb where folio_code='$folio_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['acctcode'];
	 return($val);
}

function get_amount_spent_quarterly($quarter, $year, $budgetcode, $op='')
 {
global $con;
	 ////if($op=='') $quarter =  mysqli_real_escape_string($con, get_quarter($quarter));
	 //$code =  mysqli_real_escape_string($con, $month_code);
	 $budgetyear =  mysqli_real_escape_string($con, $year);
	 $budget_folio_code =  mysqli_real_escape_string($con, $budgetcode);
	 //return "select sum(amount) as amount_spent from budget_votebooktb where operation_quarter='". $quarter."' and operation_year = '".$budgetyear."' and budget_folio_code = '".$budget_folio_code."' and status = 'PAID'<br>";
	 if($quarter=="1st Quarter")
	 	$res = "select sum(amount) as amount_spent from budget_votebooktb where operation_quarter='".
	 	$quarter."' and operation_year = '".$budgetyear."' and budget_folio_code = '".$budget_folio_code."'";// and status = 'PAID'");
	 if($quarter=="2nd Quarter")
	 	$res = "select sum(amount) as amount_spent from budget_votebooktb where operation_quarter in ('1st Quarter', '".
	 	$quarter."') and operation_year = '".$budgetyear."' and budget_folio_code = '".$budget_folio_code."'";
	 if($quarter=="3rd Quarter")
	 	$res = "select sum(amount) as amount_spent from budget_votebooktb where operation_quarter in ('1st Quarter', '2nd Quarter', '".
	 	$quarter."') and operation_year = '".$budgetyear."' and budget_folio_code = '".$budget_folio_code."'";
	 if($quarter=="4th Quarter")
	 	$res = "select sum(amount) as amount_spent from budget_votebooktb where operation_quarter in ('1st Quarter', '2nd Quarter', '3rd Quarter', '".
	 	$quarter."') and operation_year = '".$budgetyear."' and budget_folio_code = '".$budget_folio_code."'";
	 
	 $res_p = @mysqli_query($con, $res);
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p[0];
	 if($val > 0) return($val); else return 0;
 }
 
 function get_percent_spent(){
	 
 }
 
 	function generate_budget_summary($folio_code, $budget_year, $budget_type, $amount){
global $con;
		$month = date('m');
		$quarter = get_quarter($month);
		$percent_15 = ($amount/100.0) * 15.0;
		$percent_30 = ($amount/100.0) * 30.0;
		$percent_45 = ($amount/100.0) * 45.0;
		$percent_60 = ($amount/100.0) * 60.0;
		
		$q1_spent = get_amount_spent_quarterly("1st Quarter", $budget_year, $folio_code);
		$q2_spent = get_amount_spent_quarterly("2nd Quarter", $budget_year, $folio_code);
		$q3_spent = get_amount_spent_quarterly("3rd Quarter", $budget_year, $folio_code);
		$q4_spent = get_amount_spent_quarterly("4th Quarter", $budget_year, $folio_code);
		if($quarter=="1st Quarter") {$q2_spent = 0; $q3_spent = 0; $q4_spent = 0; }
		if($quarter=="2nd Quarter") {$q3_spent = 0; $q4_spent = 0; }
		if($quarter=="3rd Quarter") {$q4_spent = 0; }
		
		$q_spent = $q1_spent + $q2_spent + $q3_spent + $q4_spent;
		$qcent1 = '15%'; $qcent2 = '30%'; $qcent3 = '45%'; $qcent4 = '60%';
		
		if($q1_spent > 0) $amt_spent1 = ($q1_spent / $amount) * 100.0; else $amt_spent1 = '0';
		if($q2_spent > 0) $amt_spent2 = ($q2_spent / $amount) * 100.0; else $amt_spent2 = '0';
		if($q3_spent > 0) $amt_spent3 = ($q3_spent / $amount) * 100.0; else $amt_spent3 = '0';
		if($q4_spent > 0) $amt_spent4 = ($q4_spent / $amount) * 100.0; else $amt_spent4 = '0';
		$amt_spent_t = $amt_spent1 + $amt_spent2 + $amt_spent3 + $amt_spent4;
		$bal1 = $percent_15 - $q1_spent;
		$bal2 = $percent_30 - $q2_spent;
		$bal3 = $percent_45 - $q3_spent;
		$bal4 = $percent_60 - $q4_spent;
		if($bal1 < 0) $bal_p1 = "<span style='color:red'>(".number_format($bal1 * -1, 2).")</span>"; 
			else $bal_p1 = number_format($bal1,2);
		if($bal2 < 0) $bal_p2 = "<span style='color:red'>(".number_format($bal2 * -1, 2).")</span>"; 
			else $bal_p2 = number_format($bal2,2);
		if($bal3 < 0) $bal_p3 = "<span style='color:red'>(".number_format($bal3 * -1, 2).")</span>"; 
			else $bal_p3 = number_format($bal3,2);
		if($bal4 < 0) $bal_p4 = "<span style='color:red'>(".number_format($bal4 * -1, 2).")</span>"; 
			else $bal_p4 = number_format($bal4,2);
		//$bal = $amount - $q_spent;
	  
		$budget_summary = '';
	$budget_summary = "<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-top:1px solid green; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:#003366; font-size:10px; text-align:right; -moz-border-radius: 3px 3px 3px 3px; border-radius: 3px 3px 3px 3px;
  -webkit-border-radius: 3px 3px 3px 3px;'>Budget Summary</legend>";
	$budget_summary .= '<table width="100%" border="1" align="center" cellpadding="1" cellspacing="0" style="font-size:10px" rules="all" frame="box">
    <tr style="background-color:#CC9;">
      <td colspan="6" align="center" valign="middle" style="color:#003366;"><strong>Current Quarter: '.get_quarter($month).'</strong></td>
    </tr>
    <tr>
      <td colspan="6" align="center" valign="middle"> Budgeted Amount = &#8358;'.number_format($amount,2).'</td>
    </tr>
    <tr style="font-size:10px; background-color:#CC9;" align="center">
      <td align="right" valign="middle">Quarter==></td>
      <th align="left" valign="middle">1st (15%)</th>
      <th align="left" valign="middle">2nd (30%)</th>
      <th align="left" valign="middle">3rd (45%)</th>
      <th align="left" valign="middle">4th (60%)</th>
      <th align="left" valign="middle">Total (60%)</th>
      </tr>
    <tr nowrap="nowrap">
      <th align="left" valign="middle" style="background-color:#CC9;">Budgeted Amount</th>
      <td align="left" valign="middle">'.number_format($percent_15,2).' ('.$qcent1.')</td>
      <td align="left" valign="middle">'.number_format($percent_30,2).' ('.$qcent2.')</td>
      <td align="left" valign="middle">'.number_format($percent_45,2).' ('.$qcent3.')</td>
      <td align="left" valign="middle">'.number_format($percent_60,2).' ('.$qcent4.')</td>
      <td align="left" valign="middle">'.number_format($percent_60,2).' ('.$qcent4.')</td>
      </tr>
    <tr>
      <th align="left" valign="middle" style="background-color:#CC9;">Amount Spent</th>
      <td align="left" valign="middle">'.number_format($q1_spent,2).' ('.$amt_spent1.'%)</td>
      <td align="left" valign="middle">'.number_format($q2_spent,2).' ('.$amt_spent2.'%)</td>
      <td align="left" valign="middle">'.number_format($q3_spent,2).' ('.$amt_spent3.'%)</td>
      <td align="left" valign="middle">'.number_format($q4_spent,2).' ('.$amt_spent4.'%)</td>
      <td align="left" valign="middle">'.number_format($q_spent, 2).' ('.$amt_spent_t.'%)</td>
      </tr>
    <tr>
      <th align="left" valign="middle" style="background-color:#CC9;">Balance</th>
      <td align="left" valign="middle">'.$bal_p1.'</td>
      <td align="left" valign="middle">'.$bal_p2.'</td>
      <td align="left" valign="middle">'.$bal_p3.'</td>
      <td align="left" valign="middle">'.$bal_p4.'</td>
      <td align="left" valign="middle">'.number_format($percent_60 - $q_spent, 2).'</td>
    </tr>
    </table></fieldset>';
	return $budget_summary;
		}

function get_amount_spent_annually($year, $budgetcode)
 {
global $con;
	 $budgetyear =  mysqli_real_escape_string($con, $year);
	 $budget_folio_code =  mysqli_real_escape_string($con, $budgetcode);
	 
	 $res_p = @mysqli_query($con, "select sum(amount) as amount_spent from budget_votebooktb where operation_year = '".$budgetyear."' and budget_folio_code = '".$budget_folio_code."'");// and status = 'PAID'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p[0];
	 if($val > 0) return($val); else return 0;
 }

function annual_budget_summary($budgetyear){
global $con;
echo '<table width="100%" border="1" align="center" cellpadding="1" cellspacing="0" rules="all" frame="box">
  <thead>
    <tr>
      <th valign="middle">SN</th>
      <!--th valign="middle">ITEM CODE</th-->
      <th valign="middle">DESCRIPTION</th>
      <!--th valign="middle">YEAR</th-->
      <th valign="middle">AMOUNT (&#8358;)</th>
      <th valign="middle">1st (15%)</th>
      <th valign="middle">SPENT (&#8358;)</th>
      <th valign="middle">2nd (30%)</th>
      <th valign="middle">SPENT (&#8358;)</th>
      <th valign="middle">3rd (45%)</th>
      <th valign="middle">SPENT (&#8358;)</th>
      <th valign="middle">4th (60%)</th>
      <th valign="middle">SPENT (&#8358;)</th>
      <th valign="middle">TOTAL SPENT (&#8358;)</th>
      <th valign="middle">BALANCE (&#8358;)</th>
    </tr>
  </thead>
  <tbody>';
		//$month = date('m');
		//$quarter = get_quarter($month);
			//$percent[0] = ($amount/100.0) * 15.0;		$percent[1] = ($amount/100.0) * 30.0;
			//$percent[2] = ($amount/100.0) * 45.0;		$percent[3] = ($amount/100.0) * 60.0;
		$cent = array(15.0, 30.0, 45.0, 60.0);
		$quarter = array("1st Quarter", "2nd Quarter", "3rd Quarter", "4th Quarter");
		$qcent = array('15%', '30%', '45%', '60%');
		//echo "SELECT DISTINCT folio_code FROM budgettb WHERE budget_year = '".$budgetyear."'";
		$sn=1;
		// and folio_code='09-701-2134'
		$qt_s = array(0,0,0,0);	$qt_b = array(0,0,0,0);
		$res_p =  mysqli_query($con, "SELECT DISTINCT folio_code, budget_title FROM budgettb WHERE budget_year = '".$budgetyear."' order by budget_title ASC") or die( mysqli_error($con));
		while($rc =  mysqli_fetch_array($res_p, 3 )){ 
			
	//echo $budget_year;
			$folio_code = $rc['folio_code'];
			$total_spent = 0;	$amt_spent_t = 0;	$cent_total = 0;
				 $r_p =  mysqli_query($con, "select sum(amount) as amount_spent from budgettb where budget_year = '".$budgetyear."' and folio_code = '".$folio_code."'");
				 $rs_p= mysqli_fetch_array($r_p);
				 $amount=$rs_p[0];
				 
				 (get_folio_name($folio_code)!='') ? $folio_name=get_folio_name($folio_code) : $folio_name=get_dept_name_act($folio_code);
				 if($folio_name=='') $folio_name=$rc['budget_title'];
				echo '<tr>
			  <th align="left" valign="middle">'.$sn++.'</th>
			  <!--th align="left" valign="middle" nowrap="nowrap">'.$folio_code.'</th-->
			  <th align="left" valign="middle">'.($folio_name).'</th>
			  <!--th align="left" valign="middle">'.$budgetyear.'</th-->
			  <th align="right" valign="middle">'.number_format($amount,2).'</th>';
			for($i = 0; $i <= 3; $i++) {
				//echo $cent[$i];
				$percent[$i] = ($amount/100.0) * $cent[$i];		//percentaage of amount spent from budgeted amount
				$qt_b[$i] += $percent[$i];

				$q_spent[$i] = get_amount_spent_quarterly($quarter[$i], $budgetyear, $folio_code, '1');
				$total_spent += $q_spent[$i];	
				$qt_s[$i] += $q_spent[$i];
				
				////if($q_spent[$i] > 0) $amt_spent[$i] = ($q_spent[$i] / $amount) * 100.0; else $amt_spent[$i] = '0';
				$amt_spent[$i] = (100.0 / $amount) * $q_spent[$i];
				
				$amt_spent_t += $amt_spent[$i];
				$bal[$i] = $percent[$i] - $q_spent[$i];
				
				if($bal[$i] < 0) $bal_p[$i] = "<span style='color:red'>(".number_format($bal[$i] * -1, 2).")</span>"; 
					else $bal_p[$i] = number_format($bal[$i],2);

				echo '<td align="right" valign="middle">'.number_format($percent[$i], 2).'</td>';
				echo '<td align="right" valign="middle">'.number_format($q_spent[$i], 2).' ('.number_format($amt_spent[$i],2).'%)</td>';
			}
			$annual_spent = get_amount_spent_annually($budgetyear, $folio_code);
			$cent_total = ($annual_spent/$amount) * 100.0;
			echo '<td align="right" valign="middle">'.number_format($annual_spent, 2).' ('.number_format($cent_total,2).'%)</td>';
			$balance = $amount - $annual_spent;
			$annual_budgeted += $amount; 
			$annual_budgeted_spent += $annual_spent;
			////$bal_pcent=(100.0/$annual_spent)*$balance;
			echo '<td align="right" valign="middle">'.number_format($balance, 2).' ('.number_format(100 - $amt_spent[$i-1],2).'%)</td>
				</tr>';
		}
echo '<tr>
			  <!--th align="left" valign="middle"></th>
			  <th align="left" valign="middle"></th>
			  <th align="left" valign="middle"></th-->
			  <th align="center" valign="middle" colspan="2">TOTAL</th>
			  <th align="right" valign="middle">'.number_format($annual_budgeted,2).'</th>
			  <th align="left" valign="middle">'.number_format($qt_b[0], 2).'</th>
			  <th align="left" valign="middle">'.number_format($qt_s[0], 2).'</th>
			  <th align="left" valign="middle">'.number_format($qt_b[0], 2).'</th>
			  <th align="left" valign="middle">'.number_format($qt_s[1], 2).'</th>
			  <th align="left" valign="middle">'.number_format($qt_b[0], 2).'</th>
			  <th align="left" valign="middle">'.number_format($qt_s[2], 2).'</th>
			  <th align="left" valign="middle">'.number_format($qt_b[0], 2).'</th>
			  <th align="left" valign="middle">'.number_format($qt_s[3], 2).'</th>
			  <th align="right" valign="middle">'.number_format($annual_budgeted_spent, 2).'</th>
			  <th align="right" valign="middle">'.number_format($annual_budgeted-$annual_budgeted_spent, 2).'</th>
				</tr>';
			  
			  echo'  </tbody>
</table>';
}

function get_quarter($month_code)
 {
	global $con;
	 $code =  mysqli_real_escape_string($con, $month_code);
	 $res_p = @mysqli_query($con, "select quarter from monthtb where month_code='$code' or month_name='$code' or month_short_name='$code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['quarter'];
	 return($val);
 }

function get_month_name($month_code) 
 {
	global $con;
	 $res_p=@mysqli_query($con, "select month_name from monthtb where month_code='$month_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['month_name'];
	 return($val);
 }
 
 function get_month_code($month_code) 
 {
	global $con;
	 $res_p=@mysqli_query($con, "select month_code from monthtb where month_name='$month_code'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['month_code'];
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
 
function generate_pvno($pay_date)
{
	global $con;
	/*$pay_date=@$_REQUEST['pay_date'];
	 $month_name=@date('F',strtotime($pay_date)); $month_no=@date('m',strtotime($pay_date));
	 $year=@date('Y',strtotime($pay_date));
	 $res_p=@mysqli_query($con, "select count(*) as total from vouchertb where month(voucher_date)='$month_no' and year(voucher_date)='$year'");
	 $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);
	 
	 $pvno=strtoupper($month_name."/".$year."/". $no); //echo $month_no; */
}

function get_bank_list_summary_no($bank_name,$field_name,$field_value,$staff_category,$tb_name,$month_code,$year) //use for bank list summary report
{
	global $con;
	 $res_p=@mysqli_query($con, "select count(distinct fileno) as total from $tb_name where month='$month_code' and year='$year' and $field_name='$field_value' and bank_name='$bank_name' and category='$staff_category'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total'];
	 return($val);
}

function get_bank_list_summary_pay($bank_name,$field_name,$field_value,$staff_category,$tb_name,$month_code,$year,$pay_type) //use for bank list summary report
{
global $con;
	 $res_p=@mysqli_query($con, "select sum(amount) as total_pay from $tb_name where month='$month_code' and year='$year' and $field_name='$field_value' and bank_name='$bank_name' and payment_type='$pay_type' and category='$staff_category'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_pay'];
	 return($val);
}

function get_bank_list_summary_amt($bank_name,$field_name,$field_value,$staff_category,$tb_name,$month_code,$year) //use for bank list summary report
{
global $con;
	 $total_allowance=get_bank_list_summary_pay($bank_name,$field_name,$field_value,$staff_category,$tb_name,$month_code,$year,'Allowance');
	 $total_deduction=get_bank_list_summary_pay($bank_name,$field_name,$field_value,$staff_category,$tb_name,$month_code,$year,'Deduction');
	 $val= $total_allowance - $total_deduction;
	 return($val);
}

function get_payroll_total_pay($fileno,$f_code,$month_code,$year)
 {
global $con;
	 //the amount they av paid for the staff $fileno since he/she started workin
	 $res_p=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where folio_code='$f_code' and fileno='$fileno' and (month<=$month_code and year<=$year)");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_pay'];
	 return($val);
 }
 
function get_staff_pay_bytype($fileno,$month_code,$year,$pay_type) //use to get total allowance or deduction of a staff 
{
global $con;
	 $res_p=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and fileno='$fileno' and payment_type='$pay_type'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_pay'];
	 return($val);
}

function get_staff_netpay($fileno,$month_code,$year) //use to get staff netpay
{
global $con;
	$total_allowance=get_staff_pay_bytype($fileno,$month_code,$year,"Allowance");
	$total_deduction=get_staff_pay_bytype($fileno,$month_code,$year,"Deduction");
	$val=$total_allowance - $total_deduction;
	 return($val);
}

function get_staff_acctno($fileno)
 {
global $con;
	 //the amount they av paid for the staff $fileno since he/she started workin
	 $res_p=@mysqli_query($con, "select acct_no from stafftb where fileno='$fileno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['acct_no'];
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

function get_bursar_signature()
{
global $con;
	$res_sig=@mysqli_query($con, "select fileno from stafftb where rank='Bursar' and status='Active'");
	$rs_sig=@mysqli_fetch_array($res_sig); $fileno_sig=strtoupper($rs_sig['fileno']); $sig_path="pictures/".str_replace("/","",$fileno_sig)."_sign.jpg";
	$val="<img src='$sig_path' width='150' height='15'/>";
	return ($val);
}

function get_signature($rank)
{
global $con;
	$res_sig=@mysqli_query($con, "select fileno from stafftb where rank='$rank' and status='Active'");
	$rs_sig=@mysqli_fetch_array($res_sig); $fileno_sig=strtoupper($rs_sig['fileno']); $sig_path="pictures/".str_replace("/","",$fileno_sig)."_sign.jpg";
	$val="<img src='$sig_path' width='150' height='15'/>";
	return ($val);
}

function get_state($state_id)
  {
global $con;
	  $res_q=@mysqli_query($con, "select state_name from statetb where state_id='$state_id'");
	  $rs_q=@mysqli_fetch_array($res_q);
	  $state_name=@$rs_q['state_name'];
	  if(is_numeric($state_id))
	   return $state_name;
	  else
	   return $state_id;
  } //end of get state function
 
  function get_lga($state_id,$lga_id)
  {
global $con;
	  $res_q=@mysqli_query($con, "select l.lga_name,s.state_id from statetb s,lgatb l where l.state_id=s.state_id and l.lga_id='$lga_id'");
	  $rs_q=@mysqli_fetch_array($res_q);
	  $lga_name=@$rs_q['lga_name'];
	  if(is_numeric($lga_id))
	   return $lga_name;
	  else
	   return $lga_id;
  }//get lga
 
 function get_staff_email($fileno) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select email from stafftb where fileno='$fileno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['email'];
	 return($val);
 }
 
 function get_staff_phone($fileno) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select phone_no from stafftb where fileno='$fileno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['phone_no'];
	 return($val);
 }
 
function get_pay_total_amt($month_code,$year,$deptname,$pay_type,$category,$status) //use to three column 
{
global $con;
	 $res_p=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and department='$deptname' and payment_type='$pay_type' and category='$category' and staff_status='$status'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_pay'];
	 return($val);
}

function get_pay_total_no($month_code,$year,$deptname,$category,$status) //use for thre column
{
global $con;
	 $res_p=@mysqli_query($con, "select count(distinct fileno) as total from payroll_scheduletb where month='$month_code' and year='$year' and department='$deptname' and category='$category' and staff_status='$status'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total'];
	 return($val);
}

 function get_scale_name() 
 {
global $con;
	 $res_p=@mysqli_query($con, "select * from scale_nametb where status='Active'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['scale_name'];
	 return($val);
 }
 
 function grade_exist($scale_name,$level,$step,$category)
 {
global $con;
	 $res_p=@mysqli_query($con, "select * from salary_scaletb where scale_name='$scale_name' and level='$level' and step='$step' and category='$category'");
	 if(@mysqli_num_rows($res_p)>=1)
	  return true;
	 else
	  return false;
 }
 
 function get_child_no($fileno,$field_name,$table_name) //use for thre column
{
global $con;
	 $res_p=@mysqli_query($con, "select count(*) as total from $table_name where $field_name='$fileno' order by name");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total'];
	 if($val==0) $val="Nil";
	 return($val);
}

function generate_loan_no($pay_date)
{
global $con;
	 $month_name=@date('F',strtotime($pay_date)); $month_no=@date('m',strtotime($pay_date));
	 $year=@date('Y',strtotime($pay_date));
	 $res_p=@mysqli_query($con, "select count(*) as total from hr_loan_apptb where month(app_date)='$month_no' and year(app_date)='$year'");
	 $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);
	 
	 $pvno=strtoupper("L/".$month_name."/".$year."/". $no); //echo $month_no;
	return ($pvno);
}

function generate_staff_number($staff_status)  //staff status is either Junior/Senior
{
global $con;
	if($staff_status=='Junior') $p="JS"; else $p="SS";
	$res_a=@mysqli_query($con, "select count(*) as total from stafftb where staff_status='$staff_status'");
	$rs_a=@mysqli_fetch_array($res_a);
	$year=$app_year;
	$total=@$rs_a['total'];
	$i=sprintf("%04d", $total+1);
	$prefix="$p$i";
	return ($prefix);
}

function get_position_category($rank_appointed) 
 {
global $con;
	 $res_p=@mysqli_query($con, "select * from hr_positiontb where position='$rank_appointed'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['category'];
	 return($val);
 }
 
 function get_position_dept($prev_appno,$prev_dept_code,$prev_position)
 {
global $con;
	 $res_p=@mysqli_query($con, "select * from hr_app_positiontb where dept_code='$prev_dept_code' and position='$prev_position' and appno='$prev_appno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['dept_code']."***".@$rs_p['unit_code'];
	 return($val);
 }
?>