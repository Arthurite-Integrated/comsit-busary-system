<?php
 @session_start();
 @ini_set('max_execution_time', 60000000000);
 @ini_set("memory_limit", "51200M");
 @require_once('connect.php');
 

// @require_once "required_jQuery_files.php";
 //$id=@$_REQUEST['contentvar'];
 function logs($fileno,$log_type,$log_desc)
 {
	global $con;
 @mysqli_query($con, "insert into portal_logstb set regno='$fileno',log_type='$log_type',log_desc='$log_desc',log_date=CURDATE(),log_time=CURTIME()");
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
function read_gross($pvno)
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
		$v = "&#8358;".number_format($amnt_app, 2);
	}
	return $v;
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

 function get_account_code_narration($code) 
 {
	global $con;
	 $res_p=@mysqli_query($con, "select title from salary_codetb where account_code='$code'");
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
 function get_staff_name($fileno) 
 {
	global $con;
	 $res_p=@mysqli_query($con, "select title,surname,first_name,other_name from stafftb where fileno='$fileno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=strtoupper(@$rs_p['surname'])." ".ucfirst(strtolower(@$rs_p['first_name']))." ".ucfirst(strtolower(@$rs_p['other_name']));
	 return($val);
 }
 function get_staff_previous_promotion($fileno) 
 {
	global $con;
	 $res_p=@mysqli_query($con, "select rank,level,step,date_of_present_appt from stafftb where fileno='$fileno'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['rank']."***".$rs_p['level']."***".$rs_p['step']."***".$rs_p['date_of_present_appt'];
	 return($val);
 }
 function get_staff_previous_promotion_history($r_id) 
 {
	global $con;
	 $res_p=@mysqli_query($con, "select prev_rank,prev_level,prev_step,prev_date_of_present_appt from hr_promotion_historytb where id='$r_id'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['prev_rank']."***".$rs_p['prev_level']."***".$rs_p['prev_step']."***".$rs_p['prev_date_of_present_appt'];
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
	return @mysqli_num_rows($r);
	/*if(@mysqli_num_rows($r)>0)
		return true;
	else
		return false;*/
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

function insert_into_payroll($fileno, $dept, $staffstatus, $category, $scalename, $level, $step, $fullname, $bankname, $acctno, $month, $year, $transdate, $folio_code, $paymenttype, $amount, $login_id, $skip='')
{
	global $con;
	$s_d="$year-$month-01"; 
	if($month==2) $e_d="$year-$month-28";
	elseif($month==4 or $month==6 or $month==9 or $month==11) $e_d="$year-$month-30";	
	else  $e_d="$year-$month-31";
	
	$chk_qry=@mysqli_query($con, "SELECT * FROM salary_status WHERE fileno='$fileno' AND status='Stop' AND (start_date >= $s_d AND end_date <= $e_d) AND NOT deleted='Yes'");
	$chk_num= mysqli_num_rows($chk_qry);
	if($chk_num > 0){
		//This Staff Salary has been stopped!
		//No COmputation will be done for the current month that falls within the date range
	}else{
		@mysqli_query($con, "insert into payroll_scheduletb set fileno='$fileno', department='$dept', staff_status='$staffstatus', category='$category', scale_name='$scalename', level='$level', step='$step', fullname='$fullname', bank_name='$bankname', acct_no='$acctno', month='$month', year='$year', transdate='$transdate', folio_code='$folio_code', payment_type='$paymenttype', amount='$amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id', skip='$skip'") ;
	}
}

function get_staff_status($level)
{
	global $con;
	$r=@mysqli_query($con, "select staff_status from level_categorytb where level='$level' limit 1");
	$rs=@mysqli_fetch_array($r);
	
	return($rs['staff_status']);
}
function get_deduction_defintion($code, $cat, $status, $sex, $religion, $level, $staffstatus, $staffsex, $staffrelgion)
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
function get_column_folio_total($status,$category,$month,$year,$code,$dept,$fileno='')
{
global $con;
	$sql="select sum(amount) as total_amount from payroll_scheduletb where month='$month' and year='$year' and folio_code='$code'";
	
	if($status !="")
	 	$sql .=" and staff_status='$status'";
	if($category !="")
	 	$sql .=" and category='$category'";
		
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
function get_folio_total_per_dept($dept,$month='',$year,$code,$status,$category)
{
global $con;
	$sql="select sum(amount) as total_amount from payroll_scheduletb where year='$year' and folio_code='$code'";
	if($dept !="all")
	 	$sql .=" and department='$dept'";
	 if ($category !="")
	 	{$sql .=" and category='$category'";}
	if ($status !="")
	 	{$sql .=" and staff_status='$status'";}
	if ($month !="")
	 	{$sql .=" and month='$month'";}

$res_p=@mysqli_query($con, $sql) or die( mysqli_error($con));
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['total_amount'];
	 return($val);
}

function get_salary_scale_peryear($f,$year)
{
global $con;
 $r=@mysqli_query($con, "select distinct level,step from payroll_scheduletb where year='$year' and fileno='$f' order by convert(level,decimal),convert(step,decimal)");
 if( mysqli_num_rows($r)>0)
 	{
		while($rs= mysqli_fetch_array($r))
			{
				$scale .=$rs['level'].'/'.$rs['step'].' ';
			}
	
	}
	
 return ($scale);
}
function list_present_qualification($fileno,$app_year,$no_action='')
{
global $con;

												
													$rst_edu=@mysqli_query($con, "select * from hr_promotion_academic_edutb where fileno='$fileno' and promotion_year='$app_year' order by from_year desc,from_month desc");
													$sn=0;$tb="";
													if( mysqli_num_rows($rst_edu)>0)
														$tb .="<table width='70%' border='1' cellpadding='0' cellspacing='0'>
														<tr><th colspan='8'>Promotion Year : $app_year</th></tr>
                                          				<tr>
														<th>S/No</th><th>School Name</th><th>School Type</th><th>Qualification</th><th>Class of Degree</th><th>From</th><th>To</th>"; if($no_action =="")$tb .="<th>Action</th>";$tb .="</tr>";
													while($rst_cl=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
														$rid=@$rst_cl['id'];
															$f=@$rst_cl['fileno'];$sch_name=@$rst_cl['school_name'];
															$sch_type=@$rst_cl['school_type'];$quali=@$rst_cl['qualification'];
															$degree=@$rst_cl['degree_class'];$f_month=@$rst_cl['from_month'];$f_year=@$rst_cl['from_year'];
															$t_month=@$rst_cl['to_month'];$t_year=@$rst_cl['to_year'];
															$f_m=get_month_name($f_month); $t_m=get_month_name($t_month); 
															$val="$f***$sch_name***$sch_type***$quali***$degree***$f_month***$f_year***$t_month***$t_year";
															
															$tb .= "<tr><td>$sn</td><td>$sch_name</td><td>$sch_type</td><td>$quali</td><td>$degree</td><td>$f_m, $f_year</td><td>$t_m, $t_year</td>"; if($no_action =="")$tb .="<td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('update_promotion_basic_info','delete_present_qualification',$rid);\">Delete</a></td>";$tb .="</tr>";
															
														}//end of while
													
													$tb .="</table>";
													
													return ($tb);
													
}
function list_scholarship_prize($fileno,$app_year,$no_action='')
{
global $con;
												
													$rst_edu=@mysqli_query($con, "select * from hr_promotion_recognitiontb where fileno='$fileno' and promotion_year='$app_year' order by award_date desc");
													$sn=0;$tb="";
													if( mysqli_num_rows($rst_edu)>0)
														$tb .="<table width='70%'  border='1' cellpadding='0' cellspacing='0'>
														<tr><th colspan='8'>Promotion Year : $app_year</th></tr>
                                          				<tr>
														<th>S/No</th><th>Award Type</th><th>Award Date</th><th>Award Description</th><th>Prize</th>"; if($no_action =="")$tb .="<th>Action</th>";$tb .="</tr>";
													while($rst_cls=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
														$rid=@$rst_cls['id'];
															$f=@$rst_cls['fileno'];$award_type=@$rst_cls['award_type'];
															$award_date=@$rst_cls['award_date'];$award_desc=@$rst_cls['award_description'];
															$prize=@$rst_cls['prize'];
															
															
															$tb .= "<tr><td>$sn</td><td>$award_type</td><td>$award_date</td><td>$award_desc</td><td>$prize</td>"; if($no_action =="")$tb .="<td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('update_promotion_basic_info','delete_scholarship_prize',$rid);\">Delete</a></td>";$tb .="</tr>";
															
														}//end of while
													
													$tb .="</table>";
													
													return ($tb);
													
}
function list_training_programme($fileno,$app_year,$no_action='')
{
global $con;

												
													$rst_edu=@mysqli_query($con, "select * from hr_promotion_training_apptb where fileno='$fileno' and promotion_year='$app_year' order by start_date desc");
													$sn=0;$tb="";
													if( mysqli_num_rows($rst_edu)>0)
														$tb .="<table width='70%' border='1' cellpadding='0' cellspacing='0'>
														<tr><th colspan='10'>Promotion Year : $app_year</th></tr>
                                          				<tr>
														<th>S/No</th><th>Training Type</th><th>Start Date</th><th>End Date</th><th>Traing Title/Theme</th><th>Location</th><th>Venue</th><th>Paper Read</th><th>Sponsor</th>"; if($no_action =="")$tb .="<th>Action</th>";$tb .="</tr>";
													while($rst_cl=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
														$rid=@$rst_cl['id'];
															$f=@$rst_cl['fileno'];$start_date=@$rst_cl['start_date'];
															$training_type=@$rst_cl['training_type'];$end_date=@$rst_cl['end_date'];
															$training_title=@$rst_cl['training_title'];$location=@$rst_cl['location'];	
															$venue=@$rst_cl['venue'];$paper_read=@$rst_cl['no_paper_read'];
															$sponsor=@$rst_cl['sponsor'];
															
															$tb .= "<tr><td>$sn</td><td>$training_type</td><td>$start_date</td><td>$end_date</td><td>$training_title</td><td>$location</td><td>$venue</td><td>$paper_read</td><td>$sponsor</td>"; if($no_action =="")$tb .="<td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('update_promotion_basic_info','delete_training_section',$rid);\">Delete</a></td>";$tb .="</tr>";
															
														}//end of while
													
													$tb .="</table>";
													
													return ($tb);
													
}
function list_research_interest($fileno,$app_year,$no_action='')
{
global $con;

												
													$rst_edu=@mysqli_query($con, "select * from hr_promotion_researchtb where fileno='$fileno' and promotion_year='$app_year' order by start_date desc");
													$sn=0;$tb="";
													if( mysqli_num_rows($rst_edu)>0)
														$tb .="<table width='70%' border='1' cellpadding='0' cellspacing='0'>
														<tr><th colspan='10'>Promotion Year : $app_year</th></tr>
                                          				<tr>
														<th>S/No</th><th>Research Topic</th><th>Research Status</th><th>Funding Source</th><th>Project Value</th><th>Start Date</th><th>End Date</th><th>Amount Granted</th>"; if($no_action =="")$tb .="<th>Action</th>";$tb .="</tr>";
													while($rst_cl=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
														$rid=@$rst_cl['id'];
															$f=@$rst_cl['fileno'];$start_date=@$rst_cl['start_date'];
															$topic=@$rst_cl['topic'];$end_date=@$rst_cl['end_date'];
															$status=@$rst_cl['status'];$funding_source=@$rst_cl['funding_source'];	
															$project_value=@$rst_cl['project_value'];$amount_granted=@$rst_cl['amount_granted'];
															
															$tb .= "<tr><td>$sn</td><td>$topic</td><td>$status</td><td>$funding_source</td><td>$project_value</td><td>$start_date</td><td>$end_date</td><td>$amount_granted</td>"; if($no_action =="")$tb .="<td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('update_promotion_basic_info','delete_research_interest',$rid);\">Delete</a></td>";$tb .="</tr>";
															
														}//end of while
													
													$tb .="</table>";
													
													return ($tb);
													
}

function list_publication($fileno,$app_year,$no_action='')
{
global $con;

												
													$rst_edu=@mysqli_query($con, "select * from hr_promotion_publicationtb where fileno='$fileno' and promotion_year='$app_year' order by year_published desc");
													$sn=0;$tb="";
													if( mysqli_num_rows($rst_edu)>0)
														$tb .="<table width='70%' border='1' cellpadding='0' cellspacing='0'>
														<tr><th colspan='10'>Promotion Year : $app_year</th></tr>
                                          				<tr>
														<th>S/No</th><th>TITLE</th><th>AUTHOR(S)</th><th>PUBLISHER</th><th>TYPE</th><th>CATEGORY</th><th>YEAR PUBLISHED</th>"; if($no_action =="")$tb .="<th>Action</th>";$tb .="</tr>";
													while($rst_cl=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
														$rid=@$rst_cl['id'];
															$f=@$rst_cl['fileno'];$title=@$rst_cl['title'];
															$author=@$rst_cl['author'];$type=@$rst_cl['type'];
															$publisher=@$rst_cl['publisher'];$journal=@$rst_cl['journal'];	
															$year_published=@$rst_cl['year_published'];$status=@$rst_cl['status'];
															$category=@$rst_cl['category'];$page_no=@$rst_cl['page_no'];
															$volume=@$rst_cl['volume'];$issue=@$rst_cl['issue'];
															$url=@$rst_cl['url'];
															
															$tb .= "<tr><td>$sn</td><td>$title</td><td>$author</td><td>$publisher</td><td>$type</td><td>$category</td><td>$year_published</td>"; if($no_action =="")$tb .="<td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('update_promotion_basic_info','delete_publication_section',$rid);\">Delete</a></td>";$tb .="</tr>";
															
														}//end of while
													
													$tb .="</table>";
													
													return ($tb);
													
}

function get_month_days($mont){
global $con;
	$mont= mysqli_real_escape_string($con, $mont);
	$sq= mysqli_query($con, "select month_end from monthtb where month_code='$mont'");
	$mq= mysqli_fetch_array($sq, 3 );
	return $mq[0];
}

function checkpaydate($datevalue, $type='start'){
global $con;
	$splitdate=explode('-',$datevalue);
	$splityear= $splitdate[0];
	$splitmonth=$splitdate[1];
	$splitday = $splitdate[2];
	$splitmonthdays=get_month_days($splitmonth);
	if($splityear%4 == 0 and $splitmonth == 2) $splitmonthdays += 1; //leap year
	
	$curyear=date('Y');
	$curmonth=date('m');
	$curday=date('d');
	$curmonthdays=get_month_days($curmonth);
	if($curyear%4 == 0 and $curmonth == 2) $curmonthdays += 1; //leap year
	
	if($splityear == $curyear and $splitmonth == $curmonth) {
		if($type=='start'){
			if($splitday > 1) return ($splitmonthdays - $splitday) + 1;
			else return 0;
		}elseif($type=='end'){
			if($splitday < $splitmonthdays) return $splitday;
			else return 0;
		}
	}
	else return -1;
}
 
function get_basic_pay($level, $step, $scale){
global $con;
	$level= mysqli_real_escape_string($con, $level); $step= mysqli_real_escape_string($con, $step); $scale= mysqli_real_escape_string($con, $scale);
	$sq= mysqli_query($con, "select sum(amount) as basic from salary_scaletb where item_status='Active' and level='$level' and step='$step' and scale_name='$scale' and folio_code='001'");
	$r= mysqli_fetch_array($sq, 3 );
	$basic=$r['basic'];
	return $basic;
}

?>