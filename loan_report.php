<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MONTHLY LOAN SCHEDULE</title>
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
$year=$_REQUEST['year']; 
$month=$_REQUEST['month'];
$montht=$_REQUEST['montht'];
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
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'>".strtoupper($val[0])."<br/>BURSARY DEPARTMENT<br/>"." ".strtoupper($loan_title)." SCHEDULE FOR ".strtoupper(get_month_name($month)). "&nbsp; {$year}</p></b><hr><p>";
  ?>
	
	
	
                                                                                                                                                                                                                                                                              <center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
 <?php
	$month_code = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
 	$month_name = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');
?>
	  <tr bgcolor='#ccc'>
    <th>S/N</th>
    <th>LOAN TYPE</th>
    <th>STAFF ID</th>
    <th>STAFF NAME</th>
    <th>AMOUNT<br>&#8358;</th>
	</tr>

 <?php
 
 if($loan!='') $sql_a = "SELECT h.*, TRIM(CONCAT(s.surname, ' ', s.first_name, ' ', s.other_name)) AS payee_name, f.title FROM ((hr_loan_apptb h INNER JOIN stafftb s ON h.fileno=s.fileno) INNER JOIN foliotb f ON h.loan_type=f.folio_code) WHERE h.process_status != 'Completed' AND h.payment_status='Paid' AND h.loan_type='{$loan}' ORDER BY loan_type, s.surname";
 else $sql_a = "SELECT h.*, TRIM(CONCAT(s.surname, ' ', s.first_name, ' ', s.other_name)) AS payee_name, f.title FROM ((hr_loan_apptb h INNER JOIN stafftb s ON h.fileno=s.fileno) INNER JOIN foliotb f ON h.loan_type=f.folio_code) WHERE h.process_status != 'Completed' AND h.payment_status='Paid' ORDER BY loan_type, s.surname";
            $sqll=mysqli_query($con, $sql_a);
		$sn=0;	
		
		$LoanAmount=0;
	while($rst_folio = mysqli_fetch_array($sqll, 3 )){
                    ++$sn; 
                    $folio=$rst_folio['loan_type'];
                    $title=$rst_folio['title'];
                    $installment = $rst_folio['installment'];
                    $amount = $rst_folio['loan_amount'];
                    $paid=0;
                    $sql =  mysqli_query($con, "SELECT sum(amountpaid) as paid FROM loanrepaymenttb WHERE loanid = '".$rst_folio['id']."'");
                    $amt= mysqli_fetch_array($sql, 3 );
                    $paid = $amt['paid'];
                    $remain = $amount - $paid;
                    if($remain < $installment) $installment = $remain;

                    echo "<tr>";
                    echo "<td> $sn </td>
                    <td>$title</td>
                    <td>{$rst_folio['fileno']}</td>
                    <td>{$rst_folio['payee_name']}</td>
                    <td><div align='right'>".@number_format($installment, 2)."</div></td></tr>";

                    $LoanAmount += $installment;
	}
		  echo "<tr bgcolor='#ccc'>";
		  echo "<td></td>
                      <td></td>
                      <td></td>
                              <th>TOTAL</th>
                              <td><div align='right'><strong>".@number_format($LoanAmount, 2)."</div></strong></td> </tr>";
				
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>