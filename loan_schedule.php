<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>COMPREHENSIVE BUDGET PERFORMANCE</title>
<link rel="shortcut icon" href="images/logox.png"> <!-- put the image/logo on the browser tab -->
<style>
body {
font : "Times New Roman", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
</style>

      
</head>

<body>
<?php
$mode=base64_decode($_REQUEST['mode']);
$year=$_REQUEST['pyear'];

          $loan=$_REQUEST['pcat'];
require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
          if($loan!='')
$loan_title=get_folio_name($loan);
else
$loan_title = "LOAN";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'><u>".strtoupper($val[0])."<br/>".strtoupper($loan_title)." SCHEDULE FOR YEAR {$year}" ."</u></p></b><hr><p>";
  ?>
<center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0" class="table display"><thead>
 <?php
	$month_code = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 	$month_name = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');
?>
	  <tr>
    <th>S/N</th>
    <th>LOAN DATE</th>
    <th>STAFF ID</th>
    <th>STAFF NAME</th>
    <th>DEPARTMENT</th>
    <th>OPENING BALANCE<br>&#8358;</th>
    <th>NEW ISSUE<br>&#8358;</th>
    <th>TOTAL<br>&#8358;</th>
	  <?php foreach($month_name as $mn) echo "<th>$mn $year<br>&#8358;</th>"; ?>
    <th>TOTAL DEDUCTION<br>&#8358;</th>
    <th>CLOSING BALANCE<br>&#8358;</th>
    <th>OVER-DEDUCTION<br>&#8358;</th>
    <th>REMARK</th>
	</tr></thead><tbody>

 <?php
	$monthly_var=array(); 
          $sql_a = "SELECT h.*, TRIM(CONCAT(s.surname, ' ', s.first_name, ' ', s.other_name)) AS payee_name, f.title, d.dept_name FROM (((hr_loan_apptb h INNER JOIN stafftb s ON h.fileno=s.fileno) INNER JOIN foliotb f ON h.loan_type=f.folio_code) INNER JOIN departmenttb d ON s.dept_code=d.dept_code) WHERE h.process_status != 'Completed' AND h.payment_status='Paid' AND h.loan_type='{$loan}' ORDER BY loan_type, s.surname";
          $sqll=mysqli_query($con, $sql_a);
		$sn=0;	
		$total_closing = 0; 
                    $total_opening = 0; 	
                    $sub_total_s = 0;	
                    $sub_total_v = 0;	
                    $total_deduction = 0;
                    $total_overd = 0;

	while($rst_folio =  mysqli_fetch_array($sqll, 3 )){

                    ++$sn; 
                    $loan_year=date('Y', strtotime($rst_folio['app_date']));
                    $prev_loan = $bursary->get_any_value("loan_amount", "hr_loan_apptb", "id", $rst_folio['id'], " AND YEAR(app_date) < '{$year}'");
                    $paid_p = mysqli_query($con, "SELECT SUM(amountpaid) AS paid FROM loanrepaymenttb WHERE loanid = '".$rst_folio['id']."' AND YEAR(app_date) < '{$year}'");
                    $amt_p= mysqli_fetch_array($paid_p, 3 );
                    $amtpaid = $amt_p['paid'];
                    $op_bal = $prev_loan - $amtpaid;
                    $op_bal = $op_bal <= 0? 0:$op_bal;
                    $total_opening += $op_bal;
                    $cur_loan = $bursary->get_any_value("loan_amount", "hr_loan_apptb", "id", $rst_folio['id'], " AND YEAR(app_date) = '{$year}'");
                    $sub_total_s += $cur_loan;
                    $tloan = $op_bal + $cur_loan;
                    $sub_total_v += $tloan;
                    echo "<tr>";
                    echo "<td> $sn </td>
                    <td nowrap>".date('d-m-Y', strtotime($rst_folio['app_date']))."</td>
                    <td nowrap height='25'>{$rst_folio['fileno']}</td>
                    <td nowrap height='25'>{$rst_folio['payee_name']}</td>
                    <td nowrap height='25'>{$rst_folio['dept_name']}</td>";

                    if($op_bal<=0) echo "<td nowrap height='25'>&nbsp</td>";
                    else echo "<td nowrap height='25'><div align='right'>".number_format($op_bal, 2)."</div></td>";

                    if($cur_loan<=0) echo "<td nowrap height='25'>&nbsp</td>";
                    else echo "<td nowrap height='25'><div align='right'>".number_format($cur_loan, 2)."</div></td>";

                    if($tloan<=0) echo "<td nowrap height='25'>&nbsp</td>";
                    else echo "<td nowrap height='25'><div align='right'>".number_format($tloan, 2)."</div></td>";
                    $sub_total_b = 0;
                    for($i=1; $i<=12; $i++) {
                              $paid_p_b = mysqli_query($con, "SELECT SUM(amountpaid) AS paid FROM loanrepaymenttb WHERE loanid = '{$rst_folio['id']}' AND YEAR(period) = '{$year}' AND MONTH(period) = '{$i}'");
                              $amt_p_b= mysqli_fetch_array($paid_p_b, 3 );
                              $amtpaid_b = $amt_p_b['paid'];

                              $mint = $amtpaid_b - (($amtpaid_b/(100.0+$rst_folio['rate'])) * 100);
                              $monthly_interest[$i] += $mint;
                              $total_deduction_interest += $mint;
                              $mprin = $amtpaid_b - $mint;
                              $monthly_principal[$i] += $mprin;
                              $total_principal_deduction += $mprin;

                              if($amtpaid_b<=0) echo "<td nowrap height='25'>&nbsp</td>";
                              else echo "<td><div align='right'>".number_format($amtpaid_b, 2)."</div></td>"; 
                              $monthly_grand_total[$i] += $amtpaid_b;
                              $sub_total_b += $amtpaid_b;
                    }
                    //$total_principal_deduction += 
                    $cl_bal = $tloan - $sub_total_b;
                    $total_closing += $cl_bal;
                    $overd='';
                    if($cl_bal<0) {
                              $remark = "Overdeduction";
                              $overd = $cl_bal;
                              $total_overd += $overd;
                    }elseif($cl_bal==0) $remark = "Completed";
                    elseif($cl_bal>0) $remark = "Active";
                    echo "<td><div align='right'>".@number_format($sub_total_b, 2)."</div></td>
                    <td><div align='right'>".@number_format($cl_bal, 2)."</div></td>";

                    if($overd=='') echo "<td nowrap height='25'>&nbsp</td>";
                    else echo "<td><div align='right'>".@number_format($overd, 2)."</div></td>";

                    echo "<td nowrap height='25'>{$remark}</td></tr>";
	}
		  echo "</tbody><tfoot>";

                      echo "<tr bgcolor='#ccc'>";
		  echo "<th align='right' colspan='5'>TOTAL</th>
				<td><div align='right'><strong>".@number_format($total_opening, 2)."</div></strong></td>
				<td><div align='right'><strong>".@number_format($sub_total_s, 2)."</div></strong></td>
				<td><div align='right'><strong>".@number_format($sub_total_v, 2)."</div></strong></td>";
				
				for($i=1; $i<=12; $i++) {
					echo "<td><div align='right'><strong>".number_format($monthly_grand_total[$i], 2)."</div></strong></th>"; 
					$total_deduction += $monthly_grand_total[$i];
				}
				echo "<td><div align='right'><strong>".@number_format($total_deduction, 2)."</div></strong></td>";
				
				echo "<td><div align='right'><strong>".@number_format($total_closing, 2)."</div></strong></td>
                                        <td><div align='right'><strong>".@number_format($total_overd, 2)."</div></strong></td>
                                        <td></td>
                    </tr>";

                    echo "<tr><td colspan='24' height='15' style='border-bottom:0;'></td></tr>";

                      echo "<tr bgcolor='#e8e8e8'>";
		  echo "<td bgcolor='#fff' colspan='6' style='border-top:0; border-bottom:0;'></td>
                                        <th align='right' colspan='2'>PRINCIPAL</th>";
                                        
                                        for($i=1; $i<=12; $i++) {
                                                  echo "<td><div align='right'><strong>".number_format($monthly_principal[$i], 2)."</div></strong></th>"; 
                                                  $total_monthly_principal += $monthly_principal[$i];
                                        }
                                        echo "<td><div align='right'><strong>".@number_format($total_principal_deduction, 2)."</div></strong></td>";
                                        
				echo "<td bgcolor='#fff' colspan='3' style='border-top:0; border-bottom:0;'></td>
                    </tr>";

                    //echo "<tr><td colspan='24'></td></tr>";

                    echo "<tr bgcolor='#e8e8e8'>";
                    echo "<td bgcolor='#fff' colspan='6' style='border-top:0; border-bottom:0;'></td>
                    <th align='right' colspan='2'>INTEREST</th>";
                              
                              for($i=1; $i<=12; $i++) {
                                        echo "<td><div align='right'><strong>".number_format($monthly_interest[$i], 2)."</div></strong></th>"; 
                                        $total_monthly_interest += $monthly_interest[$i];
                              }
                              echo "<td><div align='right'><strong>".@number_format($total_deduction_interest, 2)."</div></strong></td>";
                              
                              echo "<td bgcolor='#fff' colspan='3' style='border-top:0; border-bottom:0;'></td>
                              </tr>";

                      //echo "<tr><td colspan='24'></td></tr>";

                      echo "<tr bgcolor='#e8e8e8'>";
		  echo "<td bgcolor='#fff' colspan='6' style='border-top:0; border-bottom:0;'></td>
                                        <th align='right' colspan='2'>TOTAL DEDUCTION</th>";
                                                  
				for($i=1; $i<=12; $i++) {
					echo "<td><div align='right'><strong>".number_format($monthly_grand_total[$i], 2)."</div></strong></th>"; 
					$total_deduction += $monthly_grand_total[$i];
				}
				echo "<td><div align='right'><strong>".@number_format($total_deduction, 2)."</div></strong></td>";
				
				echo "<td bgcolor='#fff' colspan='3' style='border-top:0; border-bottom:0;'></td>
                                         </tr>";
                                         
				
  ?>
</tfoot>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>