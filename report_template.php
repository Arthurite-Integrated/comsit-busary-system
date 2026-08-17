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

<body>
<?php
$mode=base64_decode($_REQUEST['mode']);
$month_code=$_REQUEST['month']; $year=$_REQUEST['year'];
require_once "function.php";
require_once ("currency_convert.php");
//echo "$month_code $year MODE:".$mode;
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
if($mode=='bank_list_summary')
 {
	 //body for bank list summary
	 echo "<center><img src='$val[1]' width='50' height='50'/></center><b><p align='center'><u>".strtoupper($val[0])."<br/>COMPUTED STAFF SALARIES AND STAFF STRENGTH BY BANK<br/>FOR THE MONTH OF: ".get_month_name($month_code).", $year.</u></p></b>";
	 
	 $res_b=@mysqli_query($con, "select distinct bank_name from payroll_scheduletb where month='$month_code' and year='$year' order by bank_name");
	if( mysqli_num_rows($res_b)>=1)
	 {
		 ?>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
           <tr>
             <td width="20%" rowspan="4"><div align="center"><strong>Name of Bank</strong></div></td>
             <td colspan="8"><div align="center"><strong>STAFF STRENGTH AND SALARIES</strong></div></td>
             <td width="10%" rowspan="4"><div align="center"><strong>Remark</strong></div></td>
           </tr>
           <tr>
             <td colspan="2" rowspan="2"><div align="center"><strong>Junior (Non-Acad)</strong></div></td>
             <td colspan="4"><div align="center"><strong>Senior</strong></div></td>
             <td colspan="2" rowspan="2"><div align="center"><strong>TOTAL</strong></div>               <div align="center"></div></td>
           </tr>
           <tr>
             <td colspan="2"><div align="center"><strong>Non-Academic</strong></div></td>
             <td colspan="2"><div align="center"><strong>Academic</strong></div></td>
           </tr>
           <tr>
             <td width="7%"><div align="center"><strong>No</strong></div></td>
             <td width="9%"><div align="center"><strong>Amount (N)</strong></div></td>
             <td width="7%"><div align="center"><strong>No</strong></div></td>
             <td width="10%"><div align="center"><strong>Amount(N)</strong></div></td>
             <td width="9%"><div align="center"><strong>No</strong></div></td>
             <td width="10%"><div align="center"><strong>Amount(N)</strong></div></td>
             <td width="8%"><div align="center"><strong>No</strong></div></td>
             <td width="10%"><div align="center"><strong>Amount(N)</strong></div></td>
           </tr>
           <?php 
		     $sn=0;
			 //grand total variable
			 $gj_non_no=0; $gj_non_amt=0; $gs_non_no=0; $gs_non_amt=0; $gs_acad_no=0; $gs_acad_amt=0; 
			 $gtot_no=0; $gtot_amt=0;
		     while($rs_b=@mysqli_fetch_array($res_b))
		 	 {
			  ++$sn;
			  $bank_name=$rs_b['bank_name'];
			  
			  //normal total variable
			  $j_non_no=0; $j_non_amt=0; $s_non_no=0; $s_non_amt=0; $s_acad_no=0; $s_acad_amt=0; 
			  $tot_no=0; $tot_amt=0;
			  
			  //get_bank_list_summary_no($bank_name,$field_name,$field_value,$staff_category,$tb_name,$month_code,$year)
			  //get_bank_list_summary_amt($bank_name,$field_name,$field_value,$staff_category,$tb_name,$month_code,$year)
			  
			  
			  $j_non_no=get_bank_list_summary_no($bank_name,'staff_status','Junior','Non-Academic','payroll_scheduletb',$month_code,$year);
			  $j_non_amt=get_bank_list_summary_amt($bank_name,'staff_status','Junior','Non-Academic','payroll_scheduletb',$month_code,$year);
			  $s_non_no=get_bank_list_summary_no($bank_name,'staff_status','Senior','Non-Academic','payroll_scheduletb',$month_code,$year);
			  $s_non_amt=get_bank_list_summary_amt($bank_name,'staff_status','Senior','Non-Academic','payroll_scheduletb',$month_code,$year);
			  $s_acad_no=get_bank_list_summary_no($bank_name,'staff_status','Senior','Academic','payroll_scheduletb',$month_code,$year);
			  $s_acad_amt=get_bank_list_summary_amt($bank_name,'staff_status','Senior','Academic','payroll_scheduletb',$month_code,$year);
			  $tot_no=$j_non_no+$s_non_no+$s_acad_no;
			  $tot_amt=$j_non_amt+$s_non_amt+$s_acad_amt;
			  
			  //accumulate grand total
			  $gj_non_no+=$j_non_no;
			  $gj_non_amt+=$j_non_amt;
			  $gs_non_no+=$s_non_no;
			  $gs_non_amt+=$s_non_amt;
			  $gs_acad_no+=$s_acad_no;
			  $gs_acad_amt+=$s_acad_amt; 
			  $gtot_no+=$tot_no;
			  $gtot_amt+=$tot_amt;
			  
		   ?>
           
           <tr>
             <td><?php echo $bank_name;?>&nbsp;</td>
             <td align="center"><?php echo $j_non_no;?>&nbsp;</td>
             <td align="center"><?php echo @number_format($j_non_amt,2);?>&nbsp;</td>
             <td align="center"><?php echo $s_non_no;?>&nbsp;</td>
             <td align="center"><?php echo @number_format($s_non_amt,2);?>&nbsp;</td>
             <td align="center"><?php echo $s_acad_no;?>&nbsp;</td>
             <td align="center"><?php echo @number_format($s_acad_amt,2);?>&nbsp;</td>
             <td align="center"><?php echo $tot_no;?>&nbsp;</td>
             <td align="center"><?php echo @number_format($tot_amt,2);?>&nbsp;</td>
             <td align="center">&nbsp;</td>
           </tr>
           <?php } //end of while ?>
           
           <tr>
             <td><div align="right"><strong>Gross Total</strong></div></td>
             <td align="center"><?php echo $gj_non_no;?>&nbsp;</td>
             <td align="center"><?php echo @number_format($gj_non_amt,2);?>&nbsp;</td>
             <td align="center"><?php echo $gs_non_no;?>&nbsp;</td>
             <td align="center"><?php echo @number_format($gs_non_amt,2);?>&nbsp;</td>
             <td align="center"><?php echo $gs_acad_no;?>&nbsp;</td>
             <td align="center"><?php echo @number_format($gs_acad_amt,2);?>&nbsp;</td>
             <td align="center"><?php echo $gtot_no;?>&nbsp;</td>
             <td align="center"><?php echo @number_format($gtot_amt,2);?>&nbsp;</td>
             <td align="center">&nbsp;</td>
           </tr>
           
         </table>
<?php
		
	 } // end of if record found
	else
	 {
		 echo "<center><b>No record to display</b></center>";
	 }
 } //end of bank list summary report
 

if($mode=='payslip')
{
	 $staff_cat=@$_REQUEST['staff'];
	 $fileno=@$_REQUEST['fileno'];
	 $fn=set_comma_breakdown($fileno); //file number separated with comma
	 //check if the salary has been approved
	 $res_ap=@mysqli_query($con, "select * from payroll_schedule_processtb where month='$month_code' and year='$year'");
	 $rs_ap=@mysqli_fetch_array($res_ap); $paid_action=$rs_ap['paid_action'];
	 if(@mysqli_num_rows($res_ap)<=0)
	   { echo "<center><font color='red'><b>The specified salary has not been processed</b></font></center>"; exit;   } 
	 else
	   {
		 if($paid_action!="Approved")
		  { echo "<center><font color='red'><b>The specified salary cannot be printed because it has not been approved by the concerned authority</b></font></center>"; exit;   }
		}
	 
	 if($staff_cat=='academic')
	   $sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and category='Academic' order by fileno";
	 elseif($staff_cat=='non-academic')
	   $sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' order by fileno";
	  elseif($staff_cat=='specific')
	    {
			$sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and fileno in ($fn) order by fileno"; //coming back here
		}
	  elseif($staff_cat=='non-academic_junior')
	   $sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and staff_status='Junior' order by fileno";
	  elseif($staff_cat=='non-academic_senior')
	   $sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and staff_status='Senior' order by fileno";
	  else
	    $sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' order by fileno";
	   
	  $res_all=@mysqli_query($con, $sql);
	// if(@mysqli_num_rows($res_all)>=1)
	  //{
	 while($rs_all=@mysqli_fetch_array($res_all))
	  {
		  $fileno=$rs_all['fileno'];
		  $tb=""; //refresh for new staff
	 $res_d=@mysqli_query($con, "select distinct fileno,department,category,staff_status,level,step,fullname,bank_name,acct_no from payroll_scheduletb where month='$month_code' and year='$year' and fileno='$fileno'");
	 $rs_d=@mysqli_fetch_array($res_d);
	 	 
		 //prepare the payslip so that it can be sent to staff email and phone number
  
  $tb="<table width='90%' border='0' align='center' cellpadding='0' cellspacing='0'>
      <tr>
        <td width='17%' rowspan='2'><img src='$val[1]' width='50' height='50'/></td>
        <td width='83%'><font size='+0'>".strtoupper($val[0])."</font></td>
      </tr>
      <tr>
        <td valign='top'><i><font size='+0'>SALARY ADVICE FOR:". strtoupper(get_month_name($month_code)).", $year"."</font></i></td>
      </tr>
  </table>
  
  <table width='90%' border='1' align='center' cellpadding='0' cellspacing='0'>
      <tr>
        <td width='36%'><table width='100%' border='0' align='center'>
          <tr>
            <td width='21%'><strong>STAFF NO.:</strong></td>
            <td width='20%'>".$rs_d['fileno']."&nbsp;</td>
            <td width='16%'><div align='right'><strong>STAFF NAME:</strong></div></td>
            <td width='43%'>".$rs_d['fullname']."</td>
          </tr>
          <tr>
            <td><strong>DEPARTMENT:</strong></td>
            <td>".$rs_d['department']."&nbsp;</td>
            <td><div align='right'><strong>BANK NAME:</strong></div></td>
            <td>".$rs_d['bank_name']."</td>
          </tr>
          <tr>
            <td><strong>CATEGORY:</strong></td>
            <td>".$rs_d['category']." ".$rs_d['staff_status']. " Staff"."&nbsp;</td>
            <td><div align='right'><strong>ACCOUNT NO:</strong></div></td>
            <td>".$rs_d['acct_no']."</td>
          </tr>
          <tr>
            <td><strong>CONTEDISS/CONPCASS:</strong></td>
            <td>"."Level ".sprintf('%02d',$rs_d['level']). " / Step ".$rs_d['step']."&nbsp;</td>
            <td colspan='2'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PAYMENT FOR THE MONTH OF ".strtoupper(get_month_name($month_code)).", ".$year."</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table width='100%' border='0'>
          <tr bgcolor='#000000'>
            <td width='46%'><div align='center'><strong><font color='#FFFFFF'>PAYMENT/ALLOWANCES</font></strong></div></td>
            <td width='54%'><div align='center'><strong><font color='#FFFFFF'>DEDUCTIONS</font></strong></div></td>
          </tr>
          <tr>
            <td valign='top'><table width='90%' border='1' cellpadding='0' cellspacing='0'>
              <tr>
                <td width='36%'><div align='center'><strong>ITEM</strong></div></td>
                <td width='38%'><div align='center'><strong>AMOUNT(=N=)</strong></div></td>
                <td width='26%'><div align='center'><strong>TODATE(=N=)</strong></div></td>
              </tr>";
              
			  //report the basic salary on the top first
			  $b_code=@get_folio_code("basic");
			  $res_ba=@mysqli_query($con, "select * from payroll_scheduletb where fileno='$fileno' and month='$month_code' and year='$year' and 	folio_code='$b_code'");
			  $rs_ba=@mysqli_fetch_array($res_ba);
			  
			  $bf_name=@get_folio_name($b_code);
			  $bf_amt=$rs_ba['amount'];  //this is the amount for basic salary
			  $bg_amt=@get_payroll_total_pay($fileno,$b_code,$month_code,$year);
			  
			  $tb.="<tr>
                <td><b>".$bf_name."</b>&nbsp;</td>
                <td><div align='right'><b>".@number_format($bf_amt,2)."</b>&nbsp;</div></td>
                <td><div align='right'><b>". @number_format($bg_amt,2)."</b>&nbsp;</div></td>
              </tr>";
			  
			  //display other allowances aprt from basic
              $res_s=@mysqli_query($con, "select * from payroll_scheduletb where fileno='$fileno' and month='$month_code' and year='$year' and 	payment_type='Allowance' and folio_code!='$b_code'");
			  $f_code=""; $f_amt=0; $f_name=""; $g_amt=0; $gross=0; $total_ded=0; $netpay=0;
			  
			  while($rs_s=@mysqli_fetch_array($res_s))
			   {			  
			      $f_code=$rs_s['folio_code'];
				  $f_name=@get_folio_name($f_code);
				  $f_amt=$rs_s['amount'];
				  //echo $f_code;
				  $g_amt=@get_payroll_total_pay($fileno,$f_code,$month_code,$year);
				  $gross+=$f_amt;
				  
			 
              $tb.="<tr>
                <td>".$f_name."&nbsp;</td>
                <td><div align='right'>".@number_format($f_amt,2)."&nbsp;</div></td>
                <td><div align='right'>". @number_format($g_amt,2)."&nbsp;</div></td>
              </tr>";
               } //end of while for allowance
			   $gross+=$bf_amt; //add the basic salary amount to the gross
            $tb.="</table></td>
            <td valign='top'><table width='80%' border='1' cellpadding='0' cellspacing='0'>
              <tr>
                <td><div align='center'><strong>ITEM</strong></div></td>
                <td><div align='center'><strong>AMOUNT(=N=)</strong></div></td>
                <td><div align='center'><strong>TODATE(=N=)</strong></div></td>
              </tr>";
			  
              $res_s=@mysqli_query($con, "select * from payroll_scheduletb where fileno='$fileno' and month='$month_code' and year='$year' and 	payment_type='Deduction'");
			  $f_code=""; $f_amt=0; $f_name=""; $g_amt=0; $total_ded=0; $netpay=0;
			  
			  while($rs_s=@mysqli_fetch_array($res_s))
			   {			  
			      $f_code=$rs_s['folio_code'];
				  $f_name=@get_folio_name($f_code);
				  $f_amt=$rs_s['amount'];
				  //echo $f_code;
				  $g_amt=@get_payroll_total_pay($fileno,$f_code,$month_code,$year);
				  $total_ded+=$f_amt;
				  
			  
              $tb.="<tr>
                <td><div align='left'>".$f_name."</div></td>
                <td><div align='right'>".@number_format($f_amt,2)."</div></td>
                <td><div align='right'>". @number_format($g_amt,2)."</div></td>
              </tr>";
               }  //end of while for deduction
			   $netpay=$gross-$total_ded; //compute netpay
			   //end of while for allowance               
            $tb.="</table></td>
          </tr>
          <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td><b>GROSS PAY:  =N= ". @number_format($gross,2)."</b></td>
            <td><b>TOTAL DEDUCTIONS: =N= ". @number_format($total_ded,2)."</b></td>
          </tr>
          <tr>
            <td colspan='2'><div align='center'></div></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><div align='center'><b>NET PAY: =N= ". @number_format($netpay,2)."</b></div></td>
      </tr>
  </table><br/><br/><hr style='border-style:dashed solid'/>";
  
  $email=@get_staff_email($fileno); $phone_no=@get_staff_phone($fileno);
  /////send payslip to email //////////////////////////////////
        $to = $email; $subject = "Payslip for the month of ".@get_month_name($month_code).", $year";
		//$todayDate = @date("l, F d, Y.");
		$msg = $tb;
											
		$headers = "From: bursary@kwcoedilorin.edu.ng   \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		@mail($to,$subject,$msg,$headers);
		//send SMS later
  /////End of send payslip to email and phone number //////////
  echo $tb;

	  } //end of outer while to fetch staff i.e go for another staff
	//else   
	//that is no criteria match the group query for the selected month and year
	// echo "<b><font color='red'>No record to display</font></b>";
	  
} //end of payslip

if($mode=='bank_list')
{
	$staff_cat=@$_REQUEST['staff'];
	 $fileno=@$_REQUEST['fileno'];
	 $bank=$_REQUEST['bank'];
	$fn=set_comma_breakdown($fileno); //file number separated with comma
	 
	 if($staff_cat=='academic')
	    {
			$sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and category='Academic' and bank_name='$bank' order by fileno";
			$sql_gtotal_all=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and category='Academic' and payment_type='Allowance' and bank_name='$bank'");
			
			$sql_gtotal_ded=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and category='Academic' and payment_type='Deduction' and bank_name='$bank'");
			
		}
	 elseif($staff_cat=='non-academic')
	   {
		   $sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and bank_name='$bank' order by fileno";
		   $sql_gtotal_all=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and payment_type='Allowance' and bank_name='$bank'");
		   
		   $sql_gtotal_ded=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and payment_type='Deduction' and bank_name='$bank'");
	   }
	  elseif($staff_cat=='specific')
	    {
			$sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and bank_name='$bank' and fileno in ($fn) order by fileno"; //coming back here
			$sql_gtotal_all=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and fileno in ($fn) and payment_type='Allowance' and bank_name='$bank'");
			
			$sql_gtotal_ded=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and fileno in ($fn) and payment_type='Deduction' and bank_name='$bank'");
		}
	  elseif($staff_cat=='non-academic_junior')
	    {
			$sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and staff_status='Junior' and bank_name='$bank' order by fileno";
			$sql_gtotal_all=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and staff_status='Junior' and payment_type='Allowance' and bank_name='$bank'");
			
			$sql_gtotal_ded=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and staff_status='Junior' and payment_type='Deduction' and bank_name='$bank'");
		}
	  elseif($staff_cat=='non-academic_senior')
	     {
			 $sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and staff_status='Senior' and bank_name='$bank' order by fileno";
			 $sql_gtotal_all=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and staff_status='Senior' and payment_type='Allowance' and bank_name='$bank'");
			 
			 $sql_gtotal_ded=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and category='Non-Academic' and staff_status='Senior' and payment_type='Deduction' and bank_name='$bank'");
		 }
	  else
	     {
			 $sql="select distinct fileno from payroll_scheduletb where month='$month_code' and year='$year' and bank_name='$bank' order by fileno";
			  $sql_gtotal_all=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and payment_type='Allowance' and bank_name='$bank'");
			  
			  $sql_gtotal_ded=@mysqli_query($con, "select sum(amount) as total_pay from payroll_scheduletb where month='$month_code' and year='$year' and payment_type='Deduction' and bank_name='$bank'");
		 }
		?>
        <table width="80%" border="0" align="center">
           <tr>
             <td colspan="4"><table width="80%" border="0" align="center">
               <tr>
                 <td width="12%" rowspan="3" valign="top"><img src="<?php echo $val[1]; ?>" width="50" height="50"/>&nbsp;</td>
                 <td width="88%"><font size="2"><b><?php echo strtoupper($val[0]);?></b></font></td>
               </tr>
               <tr>
                 <td><font size="2"><?php echo $val[2];?></font></td>
               </tr>
               <tr>
                 <td><font size="2"><u>(OFFICE OF THE BURSAR)</u></font></td>
               </tr>
             </table></td>
           </tr>
           <tr>
             <td colspan="3"><b><font size="2">OUR REF: BURS/CED/NP.80/VOL.1</font></b></td>
             <td width="38%">
              <?php
			     if($staff_cat=='academic')
				   echo "<b><font size='2'><u><i>SENIOR STAFF (ACADEMIC)</i></u></font></b>";
				 elseif($staff_cat=='non-academic')
				   echo "<b><font size='2'><u><i>NON-ACADEMIC STAFF</i></u></font></b>";
				  elseif($staff_cat=='specific')
					{
						echo "<b><font size='2'><u><i>SELECTED STAFF</i></u></font></b>";
					}
				  elseif($staff_cat=='non-academic_junior')
				   echo "<b><font size='2'><u><i>JUNIOR STAFF (NON-ACADEMIC)</i></u></font></b>";
				  elseif($staff_cat=='non-academic_senior')
				   echo "<b><font size='2'><u><i>SENIOR STAFF (NON-ACADEMIC)</i></u></font></b>";
				  else
					echo "";
			  ?>
             &nbsp;
             </td>
           </tr>
           <tr>
             <td width="22%">&nbsp;</td>
             <td colspan="2">&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td><font size="2">The Manager</font></td>
             <td colspan="2">&nbsp;</td>
             <td>DATE: <?php echo get_month_name($month_code).", $year"?></td>
           </tr>
           <tr>
             <td><font size="2"><b><?php echo $bank; ?></b></font></td>
             <td colspan="2">&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td><font size="2">Ilorin - Nigeria.</font></td>
             <td colspan="2">&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td colspan="2">&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td><font size="2">Dear Sir,</font></td>
             <td colspan="2">&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td colspan="4" align="center"><div align="center"><font size="2"><b><u>STAFF SALARY FOR THE MONTH OF : <?php echo get_month_name($month_code).", $year"?></u></b></font></div></td>
           </tr>
           <tr>
             <td colspan="2"><font size="2">Enclosed is our Cheque No:</font></td>
             <td width="11%"><div align="right"><strong><font size="2">Amount:</font></strong></div></td>
             <td><strong> <font size="2">=N=<?php 
			 $rs_gt_all=@mysqli_fetch_array($sql_gtotal_all); $rs_gt_ded=@mysqli_fetch_array($sql_gtotal_ded);
			 echo @number_format(($rs_gt_all['total_pay'] - $rs_gt_ded['total_pay']),2); ?></font></strong></td>
           </tr>
           <tr>
             <td colspan="4"><font size="2">Covering the salaries of our staff to be credited to their respective Current Accounts as detailed below:</font></td>
           </tr>
           <tr>
             <td colspan="4"><table width="100%" border="1" cellpadding="0" cellspacing="0">
               <tr>
                 <td width="9%"><strong>SERIAL NO.</strong></td>
                 <td width="17%"><strong>STAFF NUMBER</strong></td>
                 <td width="30%"><strong>NAME OF STAFF</strong></td>
                 <td width="20%"><strong>ACCOUNT NUMBER</strong></td>
                 <td width="24%"><strong>AMOUNT (=N=)</strong></td>
               </tr>
               
               <?php 
			     $sn=0; $netpay=0; $total=0;
			     $res_s=@mysqli_query($con, $sql);
				 while($rs_s=@mysqli_fetch_array($res_s))
				  {
					  ++$sn;
					  $fileno=$rs_s['fileno'];
					  //$netpay=get_staff_netpay($fileno,$month_code,$year);
					  $np= mysqli_query($con, "SELECT SUM(amount) as amount_sum from payroll_scheduletb where month='$month_code' and year='$year' AND payment_type='Allowance' AND fileno='$fileno' GROUP BY fileno");
					  $npr =  mysqli_fetch_array($np, 3 ); 	$netpay = $npr['amount_sum'];
					  $total+=$netpay;
			   ?>
               <tr>
                 <td><?php echo $sn;?>&nbsp;</td>
                 <td><?php echo $rs_s['fileno'];?>&nbsp;</td>
                 <td><?php echo get_staff_name($rs_s['fileno']);?>&nbsp;</td>
                 <td><?php echo get_staff_acctno($fileno); ?>&nbsp;</td>
                 <td>=N=<?php echo number_format($netpay,2);?>&nbsp;</td>
               </tr>
               <?php } //end of while ?>
               
               <tr>
                 <td colspan="4"><div align="right"><b>TOTAL AMOUNT:</b></div></td>
                 <td><b>=N=<?php echo number_format($total,2);?>&nbsp;</b></td>
               </tr>
             </table></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td colspan="3">&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;&nbsp;<?php
			  echo get_bursar_signature();
			 ?></td>
             <td colspan="3">&nbsp;</td>
           </tr>
           <tr>
             <td><font size="2">Authorized Signature</font></td>
             <td colspan="3">&nbsp;</td>
           </tr>
</table>
<?php
} //end of bank list module

if($mode=='three_column')
 {
	 //body for bank list summary
	 echo "<center><img src='$val[1]' width='50' height='50'/></center><b><p align='center'><u>".strtoupper($val[0])."<br/>SUMMARY OF STAFF SALARY FOR THE MONTH OF <br/>".strtoupper(get_month_name($month_code)).", $year.</u></p></b>";
	 
	 $res_b=@mysqli_query($con, "select distinct department from payroll_scheduletb where month='$month_code' and year='$year' order by department");
	if( mysqli_num_rows($res_b)>=1)
	 {
		 ?>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
           <tr>
             <td width="3%" rowspan="3"><strong>S/NO</strong></td>
             <td width="12%" rowspan="3"><strong>DEPARTMENT</strong></td>
             <td colspan="3" rowspan="2"><div align="center"><strong>ACADEMIC</strong></div></td>
             <td colspan="6"><div align="center"><strong>NON-ACADEMICS</strong></div></td>
             <td colspan="3"><div align="center"><strong>STAFF POPULATION</strong></div></td>
             <td width="5%" rowspan="3"><div align="center"><strong>TOTAL</strong></div></td>
           </tr>
           <tr>
             <td colspan="3"><div align="center"><strong>SNR NON-ACADEMICS</strong></div></td>
             <td colspan="3"><div align="center"><strong>JUNIOR</strong></div></td>
             <td width="3%" rowspan="2"><div align="center"><strong>ACADEMICS</strong></div></td>
             <td colspan="2"><div align="center"><strong>NON-ACADEMICS</strong></div></td>
           </tr>
           <tr>
             <td width="8%"><div align="center"><strong>GROSS(N)</strong></div></td>
             <td width="8%"><div align="center"><strong>DEDUCTION(N)</strong></div></td>
             <td width="7%"><div align="center"><strong>NET(N)</strong></div></td>
             <td width="7%"><div align="center"><strong>GROSS(N)</strong></div></td>
             <td width="7%"><div align="center"><strong>DEDUCTION(N)</strong></div></td>
             <td width="7%"><div align="center">
               <div align="center"><strong>NET(N)</strong></div>
             </div></td>
             <td width="5%"><div align="center"><strong>GROSS(N)</strong></div></td>
             <td width="7%"><div align="center"><strong>DEDUCTION(N)</strong></div></td>
             <td width="9%"><div align="center">
               <div align="center"><strong>NET(N)</strong></div>
             </div></td>
             <td width="5%"><div align="center"><strong>SNR</strong></div></td>
             <td width="7%"><div align="center"><strong>JNR</strong></div></td>
           </tr>
           <?php 
		     $sn=0;
			 $ac_gross=0; $ac_ded=0; $ac_net=0;
			 $non_ac_s_gross=0; $non_ac_s_ded=0; $non_ac_s_net=0;
			 $non_ac_j_gross=0; $non_ac_j_ded=0; $non_ac_j_net=0;
			 
			 $ac_no=0; $non_ac_s_no=0; $non_ac_j_no=0; $total=0;
			 
			 ///grand total
			 $g_ac_gross=0; $g_ac_ded=0; $g_ac_net=0;
			 $g_non_ac_s_gross=0; $g_non_ac_s_ded=0; $g_non_ac_s_net=0;
			 $g_non_ac_j_gross=0; $g_non_ac_j_ded=0; $g_non_ac_j_net=0;
			 
			 $g_ac_no=0; $g_non_ac_s_no=0; $g_non_ac_j_no=0; $g_total=0;
			 
			 $m_gross=0; $m_ded=0; $m_net=0;
			 //get_pay_total_amt($month_code,$year,$deptname,$pay_type,$category,$status)
			 //get_pay_total_no($month_code,$year,$deptname,$pay_type,$category,$status)
		     while($rs_b=@mysqli_fetch_array($res_b))
		 	 {
			  ++$sn;
			  $deptname=$rs_b['department'];
			   $ac_gross=get_pay_total_amt($month_code,$year,$deptname,'Allowance','Academic','Senior'); 
			   $ac_ded=get_pay_total_amt($month_code,$year,$deptname,'Deduction','Academic','Senior');
			   $ac_net=$ac_gross-$ac_ded;
			   $g_ac_gross+=$ac_gross; $g_ac_ded+=$ac_ded; $g_ac_net+=$ac_net;
			   
			   $non_ac_s_gross=get_pay_total_amt($month_code,$year,$deptname,'Allowance','Non-Academic','Senior');
			   $non_ac_s_ded=get_pay_total_amt($month_code,$year,$deptname,'Deduction','Non-Academic','Senior');
			   $non_ac_s_net=$non_ac_s_gross-$non_ac_s_ded;
			   $g_non_ac_s_gross+=$non_ac_s_gross; $g_non_ac_s_ded+=$non_ac_s_ded; $g_non_ac_s_net+=$non_ac_s_net;
			   
			   $non_ac_j_gross=get_pay_total_amt($month_code,$year,$deptname,'Allowance','Non-Academic','Junior'); 
			   $non_ac_j_ded=get_pay_total_amt($month_code,$year,$deptname,'Deduction','Non-Academic','Junior');
			   $non_ac_j_net=$non_ac_j_gross-$non_ac_j_ded;
			   $g_non_ac_j_gross+=$non_ac_j_gross; $g_non_ac_j_ded+=$non_ac_j_ded; $g_non_ac_j_net+=$non_ac_j_net;
			   
			   $ac_no=get_pay_total_no($month_code,$year,$deptname,'Academic','Senior');
			   $non_ac_s_no=get_pay_total_no($month_code,$year,$deptname,'Non-Academic','Senior');
			   $non_ac_j_no=get_pay_total_no($month_code,$year,$deptname,'Non-Academic','Junior');
			   $total=$ac_no+$non_ac_s_no+$non_ac_j_no;
			   $g_ac_no+=$ac_no; $g_non_ac_s_no+=$non_ac_s_no; $g_non_ac_j_no+=$non_ac_j_no; $g_total+=$total;
			     
		   ?>
           
           <tr>
             <td><?php echo $sn;?></td>
             <td><?php echo $deptname;?></td>
             <td align="center"><?php echo @number_format($ac_gross,2);?>&nbsp;</td>
             <td align="center"><?php echo @number_format($ac_ded,2);?>&nbsp;</td>
             <td align="center"><?php echo @number_format($ac_net,2);?></td>
             <td align="center"><?php echo @number_format($non_ac_s_gross,2);?></td>
             <td align="center"><?php echo @number_format($non_ac_s_ded,2);?></td>
             <td align="center"><?php echo @number_format($non_ac_s_net,2);?>&nbsp;</td>
             <td align="center"><?php echo @number_format($non_ac_j_gross,2);?>&nbsp;</td>
             <td align="center"><?php echo @number_format($non_ac_j_ded,2);?>&nbsp;</td>
             <td align="center"><?php echo @number_format($non_ac_j_net,2);?>&nbsp;</td>
             <td align="center"><?php echo $ac_no;?>&nbsp;</td>
             <td align="center"><?php echo $non_ac_s_no;?>&nbsp;</td>
             <td align="center"><?php echo $non_ac_j_no;?></td>
             <td align="center"><?php echo $total;?></td>
           </tr>
           <?php } //end of while 
		     
			 $m_gross=$g_ac_gross+$g_non_ac_s_gross+$g_non_ac_j_gross;
			 $m_ded=$g_ac_ded+$g_non_ac_s_ded+$g_non_ac_j_ded;
			 $m_net=$g_ac_net+$g_non_ac_s_net+$g_non_ac_j_net;
		   ?>
           
           <tr>
             <td>&nbsp;</td>
             <td><div align="right"><strong>GRAND TOTAL</strong></div></td>
             <td align="center">&nbsp;<strong><?php echo @number_format($g_ac_gross,2);?></strong></td>
             <td align="center"><strong><?php echo @number_format($g_ac_ded,2);?></strong></td>
             <td align="center"><strong><?php echo @number_format($g_ac_net,2);?></strong></td>
             <td align="center"><strong><?php echo @number_format($g_non_ac_s_gross,2);?></strong></td>
             <td align="center"><strong><?php echo @number_format($g_non_ac_s_ded,2);?></strong></td>
             <td align="center"><strong><?php echo @number_format($g_non_ac_s_net,2);?></strong></td>
             <td align="center"><strong><?php echo @number_format($g_non_ac_j_gross,2);?></strong></td>
             <td align="center"><strong><?php echo @number_format($g_non_ac_j_ded,2);?></strong></td>
             <td align="center"><strong><?php echo @number_format($g_non_ac_j_net,2);?></strong></td>
             <td align="center"><strong><?php echo $g_ac_no;?></strong></td>
             <td align="center"><strong><?php echo $g_non_ac_s_no;?></strong></td>
             <td align="center"><strong><?php echo $g_non_ac_j_no;?></strong></td>
             <td align="center"><strong><?php echo $g_total;?></strong></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td colspan="4"><strong>GRAND TOTAL OF GROSS FOR THE MONTH</strong></td>
             <td colspan="6" align="center"><strong><u><?php echo @number_format($m_gross,2);?></u></strong></td>
             <td colspan="4" rowspan="3" align="center">&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td colspan="4"><strong>GRAND TOTAL OF DEDUCTIONS FOR THE MONTH</strong></td>
             <td colspan="6" align="center"><strong><u><?php echo @number_format($m_ded,2);?></u></strong></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td colspan="4"><strong>GRAND TOTAL OF NET FOR THE MONTH</strong></td>
             <td colspan="6" align="center"><strong><u><?php echo @number_format($m_net,2);?></u></strong></td>
           </tr>
           
         </table>
<?php
		
	 } // end of if record found
	else
	 {
		 echo "<center><b>No record to display</b></center>";
	 }
 } //end of three column
 
 
if($mode=='voucher_schedule')
{
	$sch_no=base64_decode(@$_REQUEST['id']); //this is the schedule number
	$amt_range=$_REQUEST['r'];$batch_no=$_REQUEST['batch'];
	echo "<center></center><b><p align='center'><font size='3'>".strtoupper($val[0])."</font><br/><img src='$val[1]' width='50' height='50'/><br/><font size='3'>TREASURY PAYMENT REPORT</font><br/></p></b>";
	
	///get the bank name
	$res_b=@mysqli_query($con, "select distinct b.acctcode,b.acctno,b.acctname,b.bankname,v.batchno from bank_accounttb b,vouchertb v where b.acctcode=v.acctcode and v.schedule_no='$sch_no'");
	$rs_b=@mysqli_fetch_array($res_b);
	$bankname=$rs_b['bankname'];
	$acctname=$rs_b['acctname'];  $acctno=$rs_b['acctno'];
	$batch_no=$rs_b['batchno'];
	
	//check if the schedule vouchers have been approved
	$res_cv=@mysqli_query($con, "select distinct final_approval from vouchertb where schedule_no='$sch_no'");
	$rs_cv=@mysqli_fetch_array($res_cv);
	if($rs_cv['final_approval']!='Approved')
	 { $copy="File Copy"; $msg="This Schedule MUST be updated on the system after approval to generate bank copy."; $copy=""; $msg=""; }
	else
	 { $copy=""; $msg=""; }

		?>
        <table width="80%" border="0" align="center">
           <tr>
             <td colspan="4"></td>
           </tr>
           <tr>
             <td width="29%">&nbsp;</td>
             <td colspan="2">&nbsp;</td>
             <td width="40%"><b><font color="red"><?php echo $copy;?></font></b>&nbsp;</td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td colspan="2">&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <tr>
             <td><font size="2"><?php if($batch_no !="") echo "BATCH NUMBER: $batch_no";?></font></td>
             <td colspan="2">&nbsp;</td>
             <td valign="top">&nbsp;</td>
           </tr>
           <tr>
             <td>ACCOUNT NAME: <?php echo $acctname; ?></td>
             <td colspan="2">&nbsp;</td>
             <td valign="top">Payment Schedule No.: <?php echo $sch_no;?> </td>
           </tr>
           <tr>
             <td>ACCOUNT NUMBER: <?php echo $acctno; ?></td>
             <td colspan="2">&nbsp;</td>
             <td>Date: <?php echo date('d/m/Y'); ?></td>
           </tr>
           <tr>
             <td>&nbsp;</td>
             <td colspan="2">&nbsp;</td>
             <td>&nbsp;</td>
           </tr>
           <!--<tr>
             <td colspan="4"><div align="center"><font size="3"><b>BANK PAYMENT MANDATE</b></font></div></td>
           </tr>-->
           <tr>
             <td colspan="4"><font size="2">Please credit the account(s) of the under listed beneficiaries and debit our account number above with the sum stated against their respective accounts</font></td>
           </tr>
           <tr>
             <td colspan="4"><table width="100%" border="1" cellpadding="0" cellspacing="0">
               <tr>
                 <td ><strong>S/NO.</strong></td>
                 <td ><div align="center"><strong>PV NO</strong></div></td>
                 <td ><div align="center"><strong>PAYEE</strong></div></td>
                 <td ><div align="center"><strong>CODE</strong></div></td>
                 <td ><div align="center"><strong>PARTICULARS</strong></div></td>
                 <td ><div align="center"><strong>BANK</strong></div></td>
                 <td ><div align="center"><strong>BRANCH</strong></div></td>
                 <td ><div align="center"><strong>TIN NUMBER</strong></div></td>
                 <td ><div align="center"><strong>ACCOUNT NO</strong></div></td>
                 <td ><div align="center"><strong>AMOUNT (N)</strong></div></td>
                 
               </tr>
               
               <?php 
			     $sn=0; $netpay=0; $total=0;
				 if($amt_range=="")
				 	$sql="select * from vouchertb where schedule_no='$sch_no' order by pvno_paid";
				 else
				 	$sql="select * from vouchertb where schedule_no='$sch_no' and $amt_range order by pvno_paid";
			     $res_s=@mysqli_query($con, $sql);
				 if(@mysqli_num_rows($res_s)<=0)
			  { echo "<script>alert('No record to display for the vouchers corresponding to the specified amount range');window.close();</script>"; exit;}
			  			$total_amount=0;
				 while($rs_s=@mysqli_fetch_array($res_s))
				  {
					  ++$sn;
					  $pvno_paid=$rs_s['pvno_paid'];//$rs_v['payee_tin_number'];
					  $total_amount +=$rs_s['amount_paid'];
			   ?>
               <tr>
                 <td><?php echo $sn;?>&nbsp;</td>
                 <td><div align="center"><?php echo $rs_s['pvno_paid']; ?>&nbsp;</div></td>
                 <td><div align="center"><?php echo $rs_s['payee_name'];?>&nbsp;</div></td>
                 <td><div align="center"><?php echo @read_voucher_folio_code($rs_s['pvno']); ?>&nbsp;</div></td>
                 <td><div align="center"><?php echo $rs_s['description']; ?>&nbsp;</div></td>
                 <td><div align="center"><?php echo $rs_s['payee_bank_name']; ?>&nbsp;</div></td>
                 <td><div align="center"><?php echo ""; ?>&nbsp;</div></td>
                 <td><div align="center"><?php echo $rs_s['payee_tin_number']; ?>&nbsp;</div></td>
                 <td><div align="center"><?php echo $rs_s['payee_acct_no']; ?>&nbsp;</div></td>
                 
                 <td><div align="center"><?php echo @number_format($rs_s['amount_paid'],2);?>&nbsp;</div></td>
                 
                 
                 <td><div align="center"></div></td>
               </tr>
               <?php } //end of while ?>
               
              <tr>
                 <td colspan="9"><div align="right"><b>TOTAL AMOUNT:</b></div></td>
                 <td><b>=N=<?php echo number_format($total_amount,2);?>&nbsp;</b></td>
               </tr>
             </table></td>
           </tr>
           <tr>
             <td colspan="4"><div align="center"><b><font color="red"><?php echo $msg;?></font></b></div></td>
           </tr>
</table>
<?php
			$exp = explode('.',number_format($total_amount,2,'.',''));
			$words = convertNum($exp[0]);
			$words2 = @ereg_replace("And","and",ucwords($words));
			$wordsKobo = convertNum($exp[1]);
			$wordsKobo2 = ucwords($wordsKobo);
			
			$amountInWords = "$words2 Naira";
			if ($wordsKobo2 != "Zero") { $amountInWords .= ", $wordsKobo2 Kobo "; }
			$amountInWords .= " Only.";
			//echo "<strong><em>Amount in words:</em> $amountInWords</strong>";
			?>
<center>

<strong><i>No of items:  <strong><?php echo ucwords(convertNum($sn))." Item(s) Only";?></strong></i></strong><br />
Amount in words: <strong><?php echo $amountInWords; ?>  </strong> <br />
<i>This mandate is authenticated and approved by the undersigned signatories:</i><br />

<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td>Name:1...............................................</td><td rowspan="4" valign="top" style="border:1px solid; width:80px; height:50px">&nbsp;</td><td>Name:2...............................................</td><td rowspan="4" valign="top"  style="border:1px solid; width:80px; height:50px">&nbsp;</td><td>Name:3...............................................</td><td rowspan="4" valign="top"  style="border:1px solid; width:80px; height:50px">&nbsp;</td>
</tr>
<tr>
<td>Signature:...........................................</td>
<td>Signature:...........................................</td>
<td>Signature:...........................................</td>
</tr>

<tr>
<td>Designation:........................................</td>
<td>Designation:.......................................</td>
<td>Designation:.......................................</td>
</tr>

<tr>
<td>Date:.................................................</td>
<td>Date:.................................................</td>
<td>Date:.................................................</td>
</tr>
</table>
</center>

<?php
} //end of voucher_schedule
///////////////////////////////////////////////////// End of Body ////////////////////////////////////////////

if($mode=='hr_general_report')
{
	$report_opt=@explode('***',@$_REQUEST['report_opt']); $report_table=$report_opt[0]; $report_title=$report_opt[1];
	$start_date=@$_REQUEST['start_date']; $end_date=@$_REQUEST['end_date'];
	$dept_code=@$_REQUEST['dept']; $position=@$_REQUEST['position']; $fileno=@$_REQUEST['fileno'];
	
	echo "<center><img src='$val[1]' width='50' height='50'/></center><b><p align='center'><font size='3'>".strtoupper($val[0])."</font><br/><font size='3'><u>".strtoupper($report_title)."</u></font><br/></p></b>";
	
	$tb_head="";
	if($start_date!='' and $end_date!='')
	  $tb_head.="<center><b><p align='center'><font size='2'>FROM: ".@date('d/m/Y',strtotime($start_date)). "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;TO: ".@date('d/m/Y',strtotime($end_date))."</font><br/></p></b>";
	if($dept_code!='' and $position!='')
	   $tb_head.="<center><b><font size='2'>DEPARTMENT: ".@get_dept_name($dept_code). "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;POSITION: ".$position."</font><br/></b>";
	if($dept_code!='' and $position=='')
	   $tb_head.="<center><b><font size='2'>DEPARTMENT: ".@get_dept_name($dept_code). "</font><br/></b>";
	if($position!='' and $dept_code=='')
	   $tb_head.="<center><b><font size='2'>POSITION: ".$position."</font><br/></b>";
	if($fileno!='')
	   $tb_head.="<br/><center><b><font size='2'>STAFF NUMBER: ".$fileno."</font><br/></b>";
	
	//////report table heading
	if($report_title=='Retirement Report' or $report_title=='Retrenchment Report' or $report_title=='Death Report' or $report_title=='Resignation Report')
	  $tb="<center><table border='1' cellspacing='0' cellpadding='0'><tr><th>S/NO</th><th>STAFF NUMBER</th><th>FULLNAME</th><th>DATE UPDATED</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th></tr>";
	if($report_title=='Appointment Report' or $report_title=='Confirmation Report' or $report_title=='Regularization Report')
	   $tb="<center><table border='1' cellspacing='0' cellpadding='0'><tr><th>S/NO</th><th>STAFF NUMBER</th><th>FULLNAME</th><th>DATE UPDATED</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th></tr>";
	if($report_title=='Promotion Report')
	  $tb="<center><table border='1' cellspacing='0' cellpadding='0'><tr><th>S/NO</th><th>STAFF NUMBER</th><th>FULLNAME</th><th>DATE PROMOTED</th><th>GRADE LEVEL</th><th>STEP</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th></tr>";
	if($report_title=='Leave Report')
	  $tb="<center><table border='1' cellspacing='0' cellpadding='0'><tr><th>S/NO</th><th>STAFF NUMBER</th><th>FULLNAME</th><th>DATE APPROVED</th><th>LEAVE TYPE</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th></tr>";
	if($report_title=='Loan Report')
	  $tb="<center><table border='1' cellspacing='0' cellpadding='0'><tr><th>S/NO</th><th>STAFF NUMBER</th><th>FULLNAME</th><th>APPLICATION DATE</th><th>LOAN TYPE</th><th>REPAYMENT DURATION</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th></tr>";
	if($report_title=='Staff Training Report')
	  $tb="<center><table border='1' cellspacing='0' cellpadding='0'><tr><th>S/NO</th><th>STAFF NUMBER</th><th>FULLNAME</th><th>TRAINING TYPE</th><th>START DATE</th><th>END DATE</th><th>TITLE/THEME</th><th>LOCATION</th><th>AMOUNT GRANTED</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th></tr>";
	
	
	/////////////////report SQL
	$sql="";
	if($report_title=='Retirement Report' or $report_title=='Retrenchment Report' or $report_title=='Death Report' or $report_title=='Resignation Report')
		{
			$sql="select h.fileno,h.entry_date,s.dept_code,s.category from hr_status_historytb h,stafftb s where h.fileno=s.fileno and h.status in ('Retirement','Retrenchment','Death','Resignation') and s.fileno not in ('Admin','Weathstone','COED','Hamdala','School')";
			if($start_date!='' and $end_date!='') $sql.=" and h.entry_date between '$start_date' and '$end_date'";
			if($dept_code!='') $sql.=" and s.dept_code='$dept_code'";
			if($position!='') $sql.=" and s.rank='$position'";
			if($fileno!='') $sql.=" and h.fileno='$fileno'";
		}
	if($report_title=='Appointment Report' or $report_title=='Confirmation Report' or $report_title=='Regularization Report')
	   {
		  if($report_title=='Appointment Report')
		    {
				$sql="select * from stafftb s where 1 and s.fileno not in ('Admin','Weathstone','COED','Hamdala','School')";
				if($start_date!='' and $end_date!='') $sql.=" and s.date_of_1st_appt between '$start_date' and '$end_date'";
			}
		   
		   if($report_title=='Confirmation Report')
		    {
				$sql="select * from stafftb s where 1 and s.fileno not in ('Admin','Weathstone','COED','Hamdala','School')";
				if($start_date!='' and $end_date!='') $sql.=" and s.confirmation_date between '$start_date' and '$end_date'";
			}
		
		   if($report_title=='Regularization Report')
		    {
				$sql="select * from stafftb s where 1 and s.fileno not in ('Admin','Weathstone','COED','Hamdala','School')";
				if($start_date!='' and $end_date!='') $sql.=" and s.regularisation_date between '$start_date' and '$end_date'";
			}
			
			    if($dept_code!='') $sql.=" and s.dept_code='$dept_code'";
				if($position!='') $sql.=" and s.rank='$position'";
				if($fileno!='') $sql.=" and s.fileno='$fileno'";
			
	   }
	if($report_title=='Promotion Report')
	  {
		  $sql="select * from hr_promotion_historytb h,stafftb s where h.fileno=s.fileno and s.fileno not in ('Admin','Weathstone','COED','Hamdala','School')";
			if($start_date!='' and $end_date!='') $sql.=" and h.promotion_date between '$start_date' and '$end_date'";
			if($dept_code!='') $sql.=" and s.dept_code='$dept_code'";
			if($position!='') $sql.=" and s.rank='$position'";
			if($fileno!='') $sql.=" and h.fileno='$fileno'";
	  } 
	if($report_title=='Leave Report')
	  {
		  $sql="select * from hr_leave_apptb h,stafftb s where h.fileno=s.fileno and s.fileno not in ('Admin','Weathstone','COED','Hamdala','School')";
			if($start_date!='' and $end_date!='') $sql.=" and h.approval_date between '$start_date' and '$end_date'";
			if($dept_code!='') $sql.=" and s.dept_code='$dept_code'";
			if($position!='') $sql.=" and s.rank='$position'";
			if($fileno!='') $sql.=" and h.fileno='$fileno'";
	  } 
	if($report_title=='Loan Report')
	  {
		  $sql="select * from hr_loan_apptb h,stafftb s where h.fileno=s.fileno and s.fileno not in ('Admin','Weathstone','COED','Hamdala','School')";
			if($start_date!='' and $end_date!='') $sql.=" and h.process_date between '$start_date' and '$end_date'";
			if($dept_code!='') $sql.=" and s.dept_code='$dept_code'";
			if($position!='') $sql.=" and s.rank='$position'";
			if($fileno!='') $sql.=" and h.fileno='$fileno'";
	  }
	if($report_title=='Staff Training Report')
	  {
		  $sql="select * from hr_staff_training_apptb h,stafftb s where h.fileno=s.fileno and s.fileno not in ('Admin','Weathstone','COED','Hamdala','School')";
			if($start_date!='' and $end_date!='') $sql.=" and h.approval_date between '$start_date' and '$end_date'";
			if($dept_code!='') $sql.=" and s.dept_code='$dept_code'";
			if($position!='') $sql.=" and s.rank='$position'";
			if($fileno!='') $sql.=" and h.fileno='$fileno'";
	  }
	
	
	
		/////////////////report body
	    $sn=0;
	 $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
	 $g_total=0;
	 //echo "SQL: $sql Total:". mysqli_num_rows($res_v); exit;
	if(@mysqli_num_rows($res_v)>=1)
	 {
		 while($rs_v=@mysqli_fetch_array($res_v))
		  {
			  ++$sn;
				if($report_title=='Retirement Report' or $report_title=='Retrenchment Report' or $report_title=='Death Report' or $report_title=='Resignation Report')
		  $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@date('d/m/Y',strtotime($rs_v['entry_date']))."</td><td>{$rs_v['category']}</td><td>".@get_dept_name(@$rs_v['dept_code'])."</td></tr>";
		  
		if($report_title=='Appointment Report' or $report_title=='Confirmation Report' or $report_title=='Regularization Report')
		    { 
			   if($report_title=='Appointment Report')
			     $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@date('d/m/Y',strtotime($rs_v['date_of_1st_appt']))."</td><td>{$rs_v['category']}</td><td>".@get_dept_name(@$rs_v['dept_code'])."</td></tr>";
				 if($report_title=='Confirmation Report')
			     $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@date('d/m/Y',strtotime($rs_v['confirmation_date']))."</td><td>{$rs_v['category']}</td><td>".@get_dept_name(@$rs_v['dept_code'])."</td></tr>";
				 if($report_title=='Regularization Report')
			     $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@date('d/m/Y',strtotime($rs_v['regularisation_date']))."</td><td>{$rs_v['category']}</td><td>".@get_dept_name(@$rs_v['dept_code'])."</td></tr>";
			}
		if($report_title=='Promotion Report')
		  $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@date('d/m/Y',strtotime($rs_v['promotion_date']))."</td><td>{$rs_v['level']}</td><td>{$rs_v['step']}</td><td>{$rs_v['category']}</td><td>".@get_dept_name(@$rs_v['dept_code'])."</td></tr>";
		if($report_title=='Leave Report')
		  $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@date('d/m/Y',strtotime($rs_v['approval_date']))."</td><td>{$rs_v['leave_type']}</td><td>{$rs_v['category']}</td><td>".@get_dept_name(@$rs_v['dept_code'])."</td></tr>";
		if($report_title=='Loan Report')
		  $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@date('d/m/Y',strtotime($rs_v['app_date']))."</td><td>{$rs_v['loan_type']}</td><td>{$rs_v['duration']}</td><td>{$rs_v['category']}</td><td>".@get_dept_name(@$rs_v['dept_code'])."</td></tr>";
		if($report_title=='Staff Training Report')
		  $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>{$rs_v['training_type']}</td><td>".@date('d/m/Y',strtotime($rs_v['start_date']))."</td><td>".@date('d/m/Y',strtotime($rs_v['end_date']))."</td><td>{$rs_v['training_title']}</td><td>{$rs_v['location']}</td><th>{$rs_v['amount_granted']}</td><td>{$rs_v['category']}</td><td>".@get_dept_name(@$rs_v['dept_code'])."</td></tr>";
		  }//end of while
		  
		  $tb.="</table><center>";  
		  echo $tb_head."<br/><br/>".$tb;
	 }
	else
	  echo "<center><b><font color='red'>No record to display</font></b></center>";

}

//////////////////////////////////////////////////////Report Footer /////////////////////////////////////////////

///////////////////////////////////////////////////// End of Footer ////////////////////////////////////////////
?>
</body>
</html>