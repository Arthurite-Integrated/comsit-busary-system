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



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Report</title>
<link rel="shortcut icon" href="images/logox.png"> <!-- put the image/logo on the browser tab -->
<style>
body {
font : "Times New Roman", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}

/* start - table */
table {
	border-collapse: collapse;
	margin: 0;
		
}
th strong {
	color: #fff;
}
th {
	background: #93BC0C;
	height: 29px;
	padding-left: 12px;
	padding-right: 12px;
	color: #FFF;
	text-align: center; /*left;*/
	border-left: 1px solid #B6D59A;
	border-right: 1px solid #B6D59A;
	border-bottom: solid 2px #FFF;
}
tr {
	height: 30px;
}
td {
	padding-left: 11px;
	padding-right: 11px;
	border-left: 1px solid #E8E8E8;
	border-right: 1px solid #E8E8E8;
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
/* end - table */
</style>
   <!-- <script type="text/javascript" src="include/jquery.min.js"></script>
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script> -->
   
</head>

<body>
<p>
  <?php
ini_set('max_execution_time', 60000000000);
ini_set("memory_limit", "51200M");
$dept=$_REQUEST['dept'];
$option=$_REQUEST['v_opt'];
$ryear=$_REQUEST['ryear']; 
$rmonth=$_REQUEST['rmonth']; 
$folio_code=$_REQUEST['folio_code']; 
$deptcode=@$_REQUEST['dept'];
/*$acctcode=@$_REQUEST['acctcode'];
function phptosqldate($dval){
	$ex=explode('/', $dval);
	return $ex[2].'-'.$ex[0].'-'.$ex[1];
}

$from=phptosqldate($_REQUEST['from']);

$to=phptosqldate($_REQUEST['to']);

$r=@mysqli_query($con, "select * from bank_accounttb where acctcode='$acctcode'");
$r_acct=@mysqli_fetch_array($r);
$bankname=@$r_acct['bankname'];*/
require_once "function_b.php";
@require_once('class/mysqli_class.php');
$db = new Database();
$db->connect();
@require_once('myclass_m.php');
$bursary = new myclass_m();
//echo "$month_code $year<br>$status $category<br>$staff $dept<br>$option $fileno MODE:".$mode;
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////

$val=explode("***",get_company());
	 
	 echo "<center><img src='$val[1]' width='100' height='100' style='float:center' /><!--<img src='images/uith.png' width='110' height='100' style='float:right' />--><h2>".strtoupper($val[0])."</h2></center>";
	

///////////////////////////////////////////////////// End of header ////////////////////////////////////////////

 $report_type = $_REQUEST['reporttype'];
 //////////**********************************************************************************************////////////////
  	?>
</p>

<p>&nbsp; </p>
<?php if($report_type == "Trial Balance"){ ?>

  <table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
    <tr>
    <td height="27" colspan="2" align="center" bgcolor="#66CC99" style="font-size:16px; color:#000"><p><strong>TRIAL BALANCE FOR THE YEAR <?php echo $ryear; ?></strong></p></td></tr>
    <!--<tr>
      <td colspan="2" align="center" bgcolor="#336699"><strong>Date Period : </strong></td>
    </tr>-->
    <?php
//echo "select distinct concat(monthname(transdate),concat(', ',year(transdate))) as monthyear, month(transdate) as m, year(transdate) as y from transtb where dept_acctcode='$deptcode' and transdate between '$from' and '$to' order by year(transdate),month(transdate)";

$rsmonth=@mysqli_query($con, "select * from transtb where year(transdate)=  '$ryear'"); //dept_acctcode='$deptcode' and 
if(@mysqli_num_rows($rsmonth)>=1)
	{
			?>
    <tr>
      <!-- Debit Column-->
      <td colspan="2" valign="top">
      <?php echo "<p style='font-size:14px'>TOTAL NUMBER OF TRANSACTIONS: ". mysqli_num_rows($rsmonth)."</p>"; ?>
      <center>
      <table width="90%" align="center">
        <tr>
          <td bgcolor="#E5E5E5" width='5%'><strong>S/N</strong></td>
          <td height="24" bgcolor="#E5E5E5" width='10%'><strong>ACCOUNT CODE</strong></td>
          <td height="24" bgcolor="#E5E5E5"><strong>ACCOUNT NAME</strong></td>
          <td height="24" bgcolor="#E5E5E5" width='10%'><strong>AMOUNT</strong></td>
         <!-- <td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td> -->
        </tr>
        <?php 
		
		$sqll2 = "select distinct folio_code from transtb WHERE year(transdate) = '$ryear' ";
	  $msql2= mysqli_query($con, $sqll2) or die( mysqli_error($con));
		  if( mysqli_num_rows($msql2) > 0){
		  
		  while($innercode= mysqli_fetch_array($msql2, 3 )){
			$folio_code =  $innercode['folio_code']; 
			
			$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
				$folio_title = $ftit."(".$rs_trans['folio_code'].")";
//load the Codes for the Bebit side

$sqll="select t.* from transtb t WHERE year(transdate) = '$ryear' and folio_code='$folio_code' and t.transtype in ('Debit','Credit') order by t.transdate";

//$sqll="select f.title, sum(t.amount) as total, t.folio_code, t.transtype from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '$ryear' and t.transtype in ('Debit','Credit') GROUP BY t.folio_code, t.transtype order by transdate"; //t.dept_acctcode='$deptcode' and 

//$sqll="select f.title, sum(t.amount) as total, t.folio_code, t.transtype from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '$ryear' and t.transtype in ('Debit','Credit') GROUP BY t.folio_code, t.transtype order by transdate"; //t.dept_acctcode='$deptcode' and 
$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));
						$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
						$debittotal=0;	
						$credittotal=0; $b_lance = 0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
							{
								++$sn;
								if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
								$transdate=@$rs_trans['transdate'];
									if($rs_trans['transtype'] == 'Credit') {
										$b_lance = $b_lance - $rs_trans['total'];
									}
									elseif($rs_trans['transtype'] == 'Debit') {
									
									$b_lance = $b_lance + $rs_trans['total'];
									}
									//$credittotal += $rs_trans['total']; 
					echo"<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$folio_title."</td><td>".number_format($b_lance, 2)."</td></tr>";
									
								//}
								
								/*
								elseif($rs_trans['transtype'] == 'Debit') {
									$transtype="PAYMENT"; 
									$b_lance = $b_lance + $rs_trans['total'];
									$debittotal += $rs_trans['total']; 
									echo"<tr class='$rowclass'><td>$sn</td><td>".$rs_trans['folio_code']."</td><td>".$rs_trans['title']."</td><td>".number_format($b_lance, 2)."</td><td></td></tr>";*/
								//}
							}
	  }
	  }
?>
        <tr>
          <td colspan="3" align="right">Total : </td>
          <td><b><?php //echo number_format($debittotal, 2); ?></b></td>
          <td><b><?php //echo number_format($credittotal, 2); ?></b></td>
        </tr>
        <!-- End of Summary of Records for Debit -->
      </table><p>&nbsp;</p>
      </center>
      <?php
	  }else {
		  echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';	
	  }
	  ?>
      </td>
      <!-- End of Debit Column-->
      <!-- Credit Column-->      <!-- End of Credit Column-->
    </tr>
    <tr>
      <td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" />
        </th>
      <td><input type="button" class="row-b" value="Close" onclick="window.close()" />
        </th>
    </tr>
  </table>
<?php } 

 if($report_type == "Statement of Comprehensive Income"){ ?>

  <table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
    <tr>
    <td height="27" colspan="2" align="center" bgcolor="#A1A1A1" style="font-size:16px"><p><strong>INCOME AND EXPENDITURE REPORT  FOR THE YEAR <?php echo $ryear; ?></strong></p></td></tr>
    <!--<tr>
      <td colspan="2" align="center" bgcolor="#336699"><strong>Date Period : </strong></td>
    </tr>-->
    <?php
//echo "select distinct concat(monthname(transdate),concat(', ',year(transdate))) as monthyear, month(transdate) as m, year(transdate) as y from transtb where dept_acctcode='$deptcode' and transdate between '$from' and '$to' order by year(transdate),month(transdate)";

$rsmonth=@mysqli_query($con, "select * from transtb where year(transdate)=  '$ryear'"); //dept_acctcode='$deptcode' and 
if(@mysqli_num_rows($rsmonth)>=1)
	{
			?>
    <tr>
      <!-- Debit Column-->
      <td colspan="2" valign="top">
      <?php echo "<p style='font-size:14px'>TOTAL NUMBER OF TRANSACTIONS: ". mysqli_num_rows($rsmonth)."</p>"; ?>
      <center>
      <table width="90%" align="center">
        <tr>
          <td bgcolor="#E5E5E5" width='5%'><strong>S/N</strong></td>
          <td height="24" bgcolor="#E5E5E5"><strong>DESCRIPTION</strong></td>
          <td height="24" bgcolor="#E5E5E5" width='10%'><strong>NOTES</strong></td>
          <td bgcolor="#E5E5E5" width='10%'><strong><?php echo $ryear." (&#8358;)";  ?></strong></td>
          <td bgcolor="#E5E5E5" width='10%'><strong><?php echo $ryear." (&#8358;)";  ?></strong></td>
          <td bgcolor="#E5E5E5" width='10%'><strong><?php echo ($ryear-1)." (&#8358;)";  ?></strong></td>
          <td bgcolor="#E5E5E5" width='10%'><strong><?php echo ($ryear-1)." (&#8358;)";  ?></strong></td>
        </tr>
        <?php 
//$sqll="select f.title, sum(t.amount) as total, t.folio_code, t.transtype from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.dept_acctcode='$deptcode' and year(transdate) = '$ryear' GROUP BY t.folio_code, t.transtype order by transdate";


//load the Codes for the Bebit side
$sqll="select f.title, sum(t.amount) as total, t.folio_code, t.transtype from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '$ryear' and transtype='Credit' and f.exp = 'Income' GROUP BY t.folio_code, t.transtype order by transdate"; //t.dept_acctcode='$deptcode' and 
$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));
						$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
						$incometotal=0;	
						$incometotal_prev=0; 
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
							{
								++$sn;
								if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
								$transdate=@$rs_trans['transdate'];
									$incometotal += $rs_trans['total']; 
									$cnt_sql= mysqli_query($con, "select * from transtb WHERE year(transdate) = '$ryear' and transtype='Credit' and folio_code='$rs_trans[folio_code]'"); //dept_acctcode='$deptcode' and 
									$note= mysqli_num_rows($cnt_sql);
									
									echo"<tr class='$rowclass'><td>$sn</td><td>".$rs_trans['title']."(".$rs_trans['folio_code'].")</td><td>$note</td><td>".number_format($rs_trans['total'], 2)."</td><td></td>";
									
									$prev_sql= mysqli_query($con, "select sum(amount) as total_prev from transtb WHERE year(transdate) = '".($ryear-1)."' and transtype='Credit' and folio_code='$rs_trans[folio_code]'"); //dept_acctcode='$deptcode' and 
								$rs_trans2=@mysqli_fetch_array($prev_sql, 3 );
									
									$incometotal_prev += $rs_trans2['total_prev']; 
									echo "<td>".number_format($rs_trans2['total_prev'], 2)."</td><td></td></tr>";
									
							}
									echo"<tr class=''><td></td><td>TOTAL INCOME FOR THE YEAR</td><td></td><td>".number_format($incometotal, 2)."</td><td></td><td>".number_format($incometotal_prev, 2)."</td><td></td></tr>";
//=================================================================================================================================================================================================================================================================================================================================================================================================

	$sqll3s = "select * from stranstb order by itemcode "; 
	  	$msql3s= mysqli_query($con, $sqll3s) or die( mysqli_error($con));
		$accs = array();
		while($accodes = mysqli_fetch_array($msql3s, 3 )){
			 $ac_codes = $accs[] = $accodes['itemcode'];
			
		}

	for($ics=0; $ics < count($accs); $ics++){
	
	$codeslipt=explode("-",$accs[$ics]);
	 $codeslipt1 = $codeslipt[0]; 
	$codeslipt2 = $codeslipt[1];
	
	$codeslipt3 = $codeslipt1."-XXX-".$codeslipt2;
	
$sqll3sx = "select * from foliotb where LEFT(folio_code , 2) IN ('$codeslipt1') AND RIGHT(folio_code,4) IN  ('$codeslipt2') limit 1 "; 
	  	$msql3sx= mysqli_query($con, $sqll3sx) or die( mysqli_error($con));
		
	$accodesx = mysqli_fetch_array($msql3sx, 3 );
			 $ac_codes_title  = $accodesx['title'];

	$sqllxx="select sum(amount) as exp_amount from transtb t WHERE year(transdate) = '$ryear' and LEFT(folio_code , 2) IN ('$codeslipt1') AND RIGHT(folio_code,4) IN  ('$codeslipt2') and t.transtype in ('Credit') order by t.transdate";
$rstransxx=@mysqli_query($con, $sqllxx) or die( mysqli_error($con));
	$accodesxx = mysqli_fetch_array($rstransxx, 3 );
	 $amount_rem  = $accodesxx['exp_amount'];
		
		$sqllxxx="select sum(amount) as exp_amount from transtb t WHERE year(transdate) = '".($ryear-1)."' and LEFT(folio_code , 2) IN ('$codeslipt1') AND RIGHT(folio_code,4) IN  ('$codeslipt2') and t.transtype in ('Credit') order by t.transdate";
$rstransxxx=@mysqli_query($con, $sqllxxx) or die( mysqli_error($con));
	$accodesxxx = mysqli_fetch_array($rstransxxx, 3 );
	 $amount_rem2  = $accodesxxx['exp_amount'];
	
	$sqll3sx = "select * from foliotb where LEFT(folio_code , 2) IN ('$codeslipt1') AND RIGHT(folio_code,4) IN  ('$codeslipt2') limit 1 "; 
	  	$msql3sx= mysqli_query($con, $sqll3sx) or die( mysqli_error($con));
		
			
	
 $sqllx="select sum(amount) as exp_amount from transtb t WHERE year(transdate) = '$ryear' and LEFT(folio_code , 2) IN ('$codeslipt1') AND RIGHT(folio_code,4) IN  ('$codeslipt2') and t.transtype in ('Debit') order by t.transdate";
$rstransx=@mysqli_query($con, $sqllx) or die( mysqli_error($con));
						//$tamt=0;$tcamt=0;$tdamt=0;
						
						// $b_lance = 0;
						while($rs_transx=@mysqli_fetch_array($rstransx, 3 ))
							{
							
			$prev_sql2= mysqli_query($con, "select sum(amount) as total_prev2 from transtb t WHERE year(transdate) = '".($ryear-1)."' and LEFT(folio_code , 2) IN ('$codeslipt1') AND RIGHT(folio_code,4) IN  ('$codeslipt2') and t.transtype in ('Debit') order by t.transdate"); 
							$rs_trans22=@mysqli_fetch_array($prev_sql2, 3 );
								  $last_amount2 = $rs_trans22['total_prev2'] ;
								
							$amtx = $rs_transx['exp_amount'] - $amount_rem;
							$exptotal += $amtx; 
							
							if (($amtx == '0' and $last_amount2 == 0) or ($amtx == NULL and $last_amount2== NULL)) {
								
							} else {
			$sn2++;  
							$c= mysqli_query($con, "SELECT * FROM transtb where year(transdate) = '$ryear' and LEFT(folio_code , 2) IN ('$codeslipt1') AND RIGHT(folio_code,4) IN  ('$codeslipt2') and transtype in ('Debit','Credit')");
			  $nots =   mysqli_num_rows($c)	;
							
								$prev_sql= mysqli_query($con, "select sum(amount) as total_prev from transtb t WHERE year(transdate) = '".($ryear-1)."' and LEFT(folio_code , 2) IN ('$codeslipt1') AND RIGHT(folio_code,4) IN  ('$codeslipt2') and t.transtype in ('Debit') order by t.transdate"); 
								$rs_trans2=@mysqli_fetch_array($prev_sql, 3 );
								  $last_amount = $rs_trans2['total_prev'] - $amount_rem2;
									$exptotal_prev += $last_amount;
		echo "<tr class='$rowclass'><td>$sn2</td><td>".$codeslipt3." (".$ac_codes_title.")</td><td>".$nots."(".$sn2.")</td><td></td><td>".number_format($amtx, 2)."</td><td></td><td>".number_format($last_amount, 2)."||".$amount_rem2."</td></tr>";
						}
						//} 
			//$b = substr($b_lance, 0, 1); 
			
							}
		  


//if($b == '-')
	//$nums =  $b_lance; 	
	 $credittotal = $credittotal + $amtx ;	
	$sn++;	 
	
	}
	
	
	/*$sqll="select f.title, f.fundcenter, sum(t.amount) as total, t.folio_code, t.transtype from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '$ryear' and transtype='Debit' and f.exp = 'Expenses' and f.folio_code not in ('99-002-4059','09-001-3087','09-701-2084') GROUP BY t.folio_code, t.transtype order by transdate"; //and f.fundcenter='Expenditure' t.dept_acctcode='$deptcode' and 
$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));
						$exptotal=0;	
						$exptotal_prev=0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
							{
								++$sn;
								if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
								$transdate=@$rs_trans['transdate'];
									$exptotal += $rs_trans['total']; 
									$cnt_sql= mysqli_query($con, "select * from transtb WHERE year(transdate) = '$ryear' and transtype='Debit' and folio_code='$rs_trans[folio_code]'"); //dept_acctcode='$deptcode' and 
									$note= mysqli_num_rows($cnt_sql);
									
									echo"<tr class='$rowclass'><td>$sn</td><td>".$rs_trans['title']."(".$rs_trans['folio_code'].")</td><td>$note</td><td></td><td>".number_format($rs_trans['total'], 2)."</td>";
									$prev_sql= mysqli_query($con, "select sum(amount) as total_prev from transtb WHERE year(transdate) = '".($ryear-1)."' and transtype='Debit' and folio_code='$rs_trans[folio_code]'"); //dept_acctcode='$deptcode' and 
									
								$rs_trans2=@mysqli_fetch_array($prev_sql, 3 );
									$exptotal_prev += $rs_trans2['total_prev']; 
									echo "<td></td><td>".number_format($rs_trans2['total_prev'], 2)."</td></tr>";
									
							}*/
									echo"<tr class=''><td></td><td>TOTAL EXPENDITURE FOR THE YEAR</td><td></td><td></td><td>".number_format($exptotal, 2)."</td><td></td><td>".number_format($exptotal_prev, 2)."</td></tr>";
									$incomedeficit = $incometotal - $exptotal;
									$incomedeficit_prev = $incometotal_prev - $exptotal_prev;
									echo"<tr class=''><td></td><td>EXCESS/(DEFICIT) OF INCOME OVER EXPENDITURE</td><td></td><td></td><td>".number_format($incomedeficit, 2)."</td><td></td><td>".number_format($incomedeficit_prev, 2)."</td></tr>";
							
//=================================================================================================================================================================================================================================================================================================================================================================================================
$sqll="select f.title, f.category, sum(t.amount) as total, t.folio_code, t.transtype from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '$ryear' and transtype='Debit' and f.category='CURRENT YEAR DEPRECIATION' GROUP BY t.folio_code, t.transtype order by transdate"; //t.dept_acctcode='$deptcode' and 
$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));
						$deptotal=0;	
						$deptotal_prev=0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
							{
								++$sn;
								if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
								$transdate=@$rs_trans['transdate'];
									$deptotal += $rs_trans['total']; 
									$cnt_sql= mysqli_query($con, "select * from transtb WHERE year(transdate) = '$ryear' and transtype='Debit' and folio_code='$rs_trans[folio_code]'"); //dept_acctcode='$deptcode' and 
									$note= mysqli_num_rows($cnt_sql);
									
									echo"<tr class='$rowclass'><td>$sn</td><td>".$rs_trans['title']."</td><td>$note</td><td></td><td>".number_format($rs_trans['total'], 2)."</td>";
									$prev_sql= mysqli_query($con, "select sum(amount) as total_prev from transtb WHERE year(transdate) = '".($ryear-1)."' and transtype='Debit' and folio_code='$rs_trans[folio_code]'"); //dept_acctcode='$deptcode' and 
								$rs_trans2=@mysqli_fetch_array($prev_sql, 3 );
									$deptotal_prev += $rs_trans2['total_prev']; 
									echo "<td></td><td>".number_format($rs_trans2['total_prev'], 2)."</td></tr>";
									
							}
									echo"<tr class=''><td></td><td>TOTAL DEPRECIATION FOR THE YEAR</td><td></td><td></td><td>".number_format($deptotal, 2)."</td><td></td><td>".number_format($deptotal_prev, 2)."</td></tr>";
									$depincomedeficit = $incomedeficit - $deptotal;
									$depincomedeficit_prev = $incomedeficit_prev - $deptotal_prev;
									echo"<tr class=''><td></td><td>EXCESS/(DEFICIT) OF INCOME OVER EXPENDITURE AFTER DEPRECIATION</td><td></td><td></td><td>".number_format($depincomedeficit, 2)."</td><td></td><td>".number_format($depincomedeficit_prev, 2)."</td></tr>";
?>
        <!--<tr>
          <td colspan="3" align="right">Total : </td>
          <td><b><?php echo number_format($debittotal, 2); ?></b></td>
          <td><b><?php echo number_format($credittotal, 2); ?></b></td>
        </tr>-->
        <!-- End of Summary of Records for Debit -->
      </table><p>&nbsp;</p>
      </center>
      <?php
	  }else {
		  echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';	
	  }
	  ?>
      </td>
      <!-- End of Debit Column-->
      <!-- Credit Column-->      <!-- End of Credit Column-->
    </tr>
    <tr>
      <td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" />
        </th>
      <td><input type="button" class="row-b" value="Close" onclick="window.close()" />
        </th>
    </tr>
  </table>
<?php } ?>  

<?php if($report_type == "Statement of Financial Position"){ ?>

  <table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
    <tr>
      <td height="27" colspan="2" align="center" bgcolor="#336699" style="font-size:16px; color:#FFF"><p><strong>STATEMENT OF FINANCIAL POSITION AS AT <?php echo strtoupper(date('d-M-Y')); ?></strong></p></td></tr>
    <!--<tr>
      <td colspan="2" align="center" bgcolor="#336699"><strong>Date Period : </strong></td>
    </tr>-->
    <?php
if($rmonth!='') $rmonth = " and month(transdate)= '$rmonth' ";

$rsmonth=@mysqli_query($con, "select * from transtb where year(transdate)= '$ryear' ".$rmonth); //dept_acctcode='$deptcode' and 
if(@mysqli_num_rows($rsmonth)>=1)
	{
			?>
    <tr>
      <!-- Debit Column-->
      <td colspan="2" valign="top">
      <center>
      <table width="90%" align="center">
        <tr>
          <td height="24" bgcolor="#E5E5E5"><strong>DESCRIPTION</strong></td>
          <td height="24" bgcolor="#E5E5E5" width='10%'><strong>NOTES</strong></td>
          <td bgcolor="#E5E5E5" width='10%'><strong><?php echo $ryear." (&#8358;)";  ?></strong></td>
          <td bgcolor="#E5E5E5" width='10%'><strong><?php echo $ryear." (&#8358;)";  ?></strong></td>
          <td bgcolor="#E5E5E5" width='10%'><strong><?php echo ($ryear-1)." (&#8358;)";  ?></strong></td>
          <td bgcolor="#E5E5E5" width='10%'><strong><?php echo ($ryear-1)." (&#8358;)";  ?></strong></td>
        </tr>
        <tr><td height="24" bgcolor="#E5E5ff" colspan="6"><strong>NON-CURRENT ASSETS:</strong></td></tr>
        <?php 
		//COMPUTATION FOR CURRENT ASSETS
$sqll="select f.title, sum(t.amount) as total, t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE f.exp = 'Assets' and year(transdate) = '$ryear' ".$rmonth." GROUP BY t.folio_code"; //t.dept_acctcode='$deptcode' and 
$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));
						$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
						$curassettotal=0;	
						$curassettotal_prev=0; 
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
							{
								++$sn;
								if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
									$curassettotal += $rs_trans['total']; 
									$cnt_sql= mysqli_query($con, "select * from transtb WHERE year(transdate) = '$ryear' and folio_code='$rs_trans[folio_code]' ".$rmonth); //dept_acctcode='$deptcode' and 
									$note= mysqli_num_rows($cnt_sql);
									
									echo"<tr class='$rowclass'><td>".$rs_trans['title']."(".$rs_trans[folio_code].")</td><td>$note</td><td>".number_format($rs_trans['total'], 2)."</td><td></td>";
									$prev_sql= mysqli_query($con, "select sum(amount) as total_prev from transtb WHERE year(transdate) = '".($ryear-1)."' and folio_code='$rs_trans[folio_code]'".$rmonth); //dept_acctcode='$deptcode' and 
								$rs_trans2=@mysqli_fetch_array($prev_sql, 3 );
									$curassettotal_prev += $rs_trans2['total_prev']; 
									echo "<td>".number_format($rs_trans2['total_prev'], 2)."</td><td></td></tr>";
									
							}
									echo"<tr class=''><td><strong>TOTAL NON-CURRENT ASSETS</strong></td><td></td><td><strong>".number_format($curassettotal, 2)."</strong></td><td></td><td><strong>".number_format($curassettotal_prev, 2)."</strong></td><td>-</td></tr>";
//================================================================================================================================================================================== NON-CURRENT ASSET ================================================ =================================================================================================================================
$sqll="select f.title, sum(t.amount) as total, t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE  f.exp = 'Liabilities' and year(transdate) = '$ryear' ".$rmonth." GROUP BY t.folio_code"; //t.dept_acctcode='$deptcode' and 
$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));
						$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
						$ncurassettotal=0;	
						$ncurassettotal_prev=0; 
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
							{
								++$sn;
								if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
									$ncurassettotal += $rs_trans['total']; 
									$cnt_sql= mysqli_query($con, "select * from transtb WHERE year(transdate) = '$ryear' and folio_code='$rs_trans[folio_code]' ".$rmonth); //dept_acctcode='$deptcode' and 
									$note= mysqli_num_rows($cnt_sql);
									
									echo"<tr class='$rowclass'><td>".$rs_trans['title']."(".$rs_trans[folio_code].")</td><td>$note</td><td>".number_format($rs_trans['total'], 2)."</td><td></td>";
									$prev_sql= mysqli_query($con, "select sum(amount) as total_prev from transtb WHERE year(transdate) = '".($ryear-1)."' and folio_code='$rs_trans[folio_code]'".$rmonth); //dept_acctcode='$deptcode' and 
									
								$rs_trans2=@mysqli_fetch_array($prev_sql, 3 );
									$ncurassettotal_prev += $rs_trans2['total_prev']; 
									echo "<td>".number_format($rs_trans2['total_prev'], 2)."</td><td></td></tr>";
									
							}
			echo"<tr class=''><td><strong>TOTAL CURRENT ASSETS</strong></td><td></td><td><strong>".number_format($ncurassettotal, 2)."</strong></td><td></td><td><strong>".number_format($ncurassettotal_prev, 2)."</strong></td><td>-</td></tr>";
			$totalasset = $curassettotal + $ncurassettotal;	$totalasset_prev = $curassettotal_prev + $ncurassettotal_prev;
			echo"<tr class=''><td><strong>TOTAL ASSETS</strong></td><td></td><td>-</td><td><strong>".number_format($totalasset, 2)."</strong></td><td>-</td><td><strong>".number_format($totalasset_prev, 2)."</strong></td></tr>";

//================================================================================================================================================================================== LIABILITIES ================================================ =================================================================================================================================
echo '<tr><td height="24" bgcolor="#E5E5ff" colspan="6"><strong>LESS: LIABILITIES:</strong></td></tr>';
$sqll="select f.title, sum(t.amount) as total, t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE f.exp='Liabilities' and year(transdate) = '$ryear' ".$rmonth." GROUP BY t.folio_code"; //t.dept_acctcode='$deptcode' and 
$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));
						$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
						$liabtotal=0;	
						$liabtotal_prev=0; 
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
							{
								++$sn;
								if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
									$liabtotal += $rs_trans['total']; 
									$cnt_sql= mysqli_query($con, "select * from transtb WHERE year(transdate) = '$ryear' and folio_code='$rs_trans[folio_code]' ".$rmonth); //dept_acctcode='$deptcode' and 
									$note= mysqli_num_rows($cnt_sql);
									
									echo"<tr class='$rowclass'><td>".$rs_trans['title']."(".$rs_trans[folio_code].")</td><td>$note</td><td>".number_format($rs_trans['total'], 2)."</td><td></td>";
									$prev_sql= mysqli_query($con, "select sum(amount) as total_prev from transtb WHERE year(transdate) = '".($ryear-1)."' and folio_code='$rs_trans[folio_code]'".$rmonth); //dept_acctcode='$deptcode' and 
									
									$rs_trans2=@mysqli_fetch_array($prev_sql, 3 );
									$liabtotal_prev += $rs_trans2['total_prev']; 
									echo "<td>".number_format($rs_trans2['total_prev'], 2)."</td><td></td></tr>";
									
							}
			echo"<tr class=''><td><strong>TOTAL LIABILITIES</strong></td><td></td><td>-</td><td><strong>".number_format($liabtotal, 2)."</strong></td><td>-</td><td><strong>".number_format($liabtotal_prev, 2)."</strong></td></tr>";
			
			$netasset = $ncurassettotal - $liabtotal;	$netasset_prev = $ncurassettotal_prev - $liabtotal_prev;
			echo"<tr class=''><td><strong>NET ASSETS</strong></td><td></td><td>-</td><td><strong>".number_format($netasset, 2)."</strong></td><td>-</td><td><strong>".number_format($netasset_prev, 2)."</strong></td></tr>";//================================================================================================================================================================================== EQUITY ================================================ =================================================================================================================================
echo '<tr><td height="24" bgcolor="#E5E5ff" colspan="6"><strong>FINANCED BY:</strong></td></tr>';
$sqll="select f.title, sum(t.amount) as total, t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE f.category='INCOME' and year(transdate) = '$ryear' ".$rmonth." GROUP BY t.folio_code"; //t.dept_acctcode='$deptcode' and 
$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));
						$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
						$eqtotal=0;	
						$eqtotal_prev=0; 
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
							{
								++$sn;
								if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
									$eqtotal += $rs_trans['total']; 
									$cnt_sql= mysqli_query($con, "select * from transtb WHERE year(transdate) = '$ryear' and folio_code='$rs_trans[folio_code]' ".$rmonth); //dept_acctcode='$deptcode' and 
									$note= mysqli_num_rows($cnt_sql);
									
									echo"<tr class='$rowclass'><td>".$rs_trans['title']."</td><td>$note</td><td></td><td>".number_format($rs_trans['total'], 2)."</td>";
									$prev_sql= mysqli_query($con, "select sum(amount) as total_prev from transtb WHERE year(transdate) = '".($ryear-1)."' and folio_code='$rs_trans[folio_code]'".$rmonth); //dept_acctcode='$deptcode' and 
								$rs_trans2=@mysqli_fetch_array($prev_sql, 3 );
									$eqtotal_prev += $rs_trans2['total_prev']; 
									echo "<td></td><td>".number_format($rs_trans2['total_prev'], 2)."</td></tr>";
									
							}
			echo"<tr class=''><td><strong>TOTAL FINANCE</strong></td><td></td><td>-</td><td><strong>".number_format($eqtotal, 2)."</strong></td><td>-</td><td><strong>".number_format($eqtotal_prev, 2)."</strong></td></tr>";
			
			//$netasset = $ncurassettotal - $liabtotal;	$netasset_prev = $ncurassettotal_prev - $liabtotal_prev;
			//echo"<tr class=''><td><strong>NET ASSETS</strong></td><td></td><td>-</td><td><strong>".number_format($netasset, 2)."</strong></td><td>-</td><td><strong>".number_format($netasset_prev, 2)."</strong></td></tr>";//=================================================================================================================================================================================================================================================================================================================================================================================================
?>
        <!--<tr>
          <td colspan="3" align="right">Total : </td>
          <td><b><?php echo number_format($debittotal, 2); ?></b></td>
          <td><b><?php echo number_format($credittotal, 2); ?></b></td>
        </tr>-->
        <!-- End of Summary of Records for Debit -->
      </table><p>&nbsp;</p>
      </center>
      <?php
	  }else {
		  echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';	
	  }
	  ?>
      </td>
      <!-- End of Debit Column-->
      <!-- Credit Column-->      <!-- End of Credit Column-->
    </tr>
    <tr>
      <td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" />
        </th>
      <td><input type="button" class="row-b" value="Close" onclick="window.close()" />
        </th>
    </tr>
  </table>
<?php }   

 
 
///////////////////////////////////////////////////// End of Body ////////////////////////////////////////////
//////////////////////////////////////////////////////Report Footer /////////////////////////////////////////////
///////////////////////////////////////////////////// End of Footer ////////////////////////////////////////////
?>
</body>
</html>