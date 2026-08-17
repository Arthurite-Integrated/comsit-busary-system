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
	<?php
	ini_set('max_execution_time', 60000000000);
	ini_set("memory_limit", "51200M");
	$dept=$_REQUEST['dept'];
	//$option=$_REQUEST['v_opt'];  note10

	$option=$_REQUEST['id'];
	$ryear=$_REQUEST['year'];


	$fileno=$f=$_REQUEST['fileno'];
	$folio_code=$_REQUEST['folio_code'];
	$deptcode=@$_REQUEST['dept'];
	//$acctcode=@$_REQUEST['acctcode'];
	$account=@$_REQUEST['account'];

	function phptosqldate($dval){
		$ex=explode('/', $dval);
		return $ex[2].'-'.$ex[0].'-'.$ex[1];
	}

	$from=phptosqldate($_REQUEST['from']);

	$to=phptosqldate($_REQUEST['to']);
	require_once "connect.php";
	$r=@mysqli_query($con, "select * from bank_accounttb where acctcode='$account'");
	$r_acct=@mysqli_fetch_array($r);
	$bankname=@$r_acct['bankname'];
	$acctname=@$r_acct['acctname'];
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

	if($option=='pad_audit')
	{
		$ryear=$_REQUEST['pyear2x'];
		$retired=$_REQUEST['retired'];
		$paid=$_REQUEST['paid'];
		$fact=$_REQUEST['fact'];
		$login_id=@$_SESSION['login_id'];

		$sq="SELECT folio_code FROM advancetb";
		$q=mysqli_query($con, $sq);
		$f_pvno = "('x'";
		while($r=mysqli_fetch_array($q, 3)){
			$f_pvno .= ", '{$r[0]}'";
		}
		$f_pvno .= ")";
		//$f_pvno=implode(',', $r[0]);
		//echo $f_pvno; exit;

		$from=$_POST['from'];
		$to=$_POST['to'];
		$from=date('Y-m-d', strtotime($from)); 
		$to=date('Y-m-d', strtotime($to));
		$ryear=date('Y', strtotime($from));

		if ($retired == 'Yes')
		{
			$tb="<table id='MyTable' class='display; align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
			<thead>
			<tr><td colspan='15' align='center' style='font-weight:bold; font-size:20px;'><P>BURSARY DEPARTMENT</P> RETIRED PURCHASE ADVANCE BETWEEN ".date('d-m-Y', strtotime($from))." TO ".date('d-m-Y', strtotime($to))."</td></tr>
			<tr style='border:solid 1px #000; background-color:#f2f2f2'>
				<th>S/NO</th>
				<th>DATE</th>
				<th>PARTICULARS</th>
				<th>PAYMENT DESCRIPTION</th>
				<th>DEPARTMENT</th>
				<th>DEBIT</th>
				<th>CREDIT</th>
				<th>CODE</th>
				<th>PV NO.</th>
				<th>PV DATE</th>
				<th>PAJV</th>
				<th>CURRENT BALANCE</th>
				<th>DATE RETIRED</th>
				<th>STATUS</th>
				<th>PHONE</th>
				</tr></thead><tbody>";
			$sn=0; 
			if($fact=='') $sqll="SELECT DISTINCT p.jvno, p.pvno FROM pa_retirementtb p INNER JOIN journaltb j ON p.jvno=j.journalno WHERE j.journal_date BETWEEN '{$from}' AND '{$to}' ORDER BY jvno";
			else $sqll="SELECT DISTINCT p.jvno, p.pvno FROM pa_retirementtb p INNER JOIN journaltb j ON p.jvno=j.journalno WHERE j.journal_date BETWEEN '{$from}' AND '{$to}' AND j.dept_code='{$fact}' ORDER BY jvno";//() OR j.dept_code='' OR j.dept_code Is Null)
			$mqr = mysqli_query($con, $sqll);
			$unret = 0;	$ret = 0;
			$count=0;
			while($q=mysqli_fetch_array($mqr, 3)){
				
				$pa_pvno = $q['pvno'];
				$jvno = $q['jvno'];
				$sql="SELECT DISTINCT t.acctcode, t.id AS tid, t.pvno AS tpvno, t.transdate, t.amount as tamount, v.*, f.title, vf.folio_code AS vffolio FROM (((transtb t INNER JOIN vouchertb v ON t.pvno=v.pvno_paid) INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE v.retired='{$retired}' AND t.transdate BETWEEN '{$from}' AND '{$to}' AND t.folio_code IN {$f_pvno} AND t.pvno='{$pa_pvno}' AND t.pvno!='' ORDER BY t.transdate ASC";

				/*if($fact=='')
					$sql="SELECT DISTINCT t.acctcode, t.id AS tid, t.pvno AS tpvno, t.transdate, t.amount as tamount, v.*, f.title, vf.folio_code AS vffolio FROM (((transtb t INNER JOIN vouchertb v ON t.pvno=v.pvno_paid) INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE t.retired='{$retired}' AND j.journal_date BETWEEN '{$from}' AND '{$to}' AND t.folio_code IN {$f_pvno} AND t.pvno='{$pa_pvno}' ORDER BY t.transdate ASC";
				else
					$sql="SELECT DISTINCT t.id AS tid, t.pvno AS tpvno, t.transdate, t.amount as tamount, v.*, f.title, vf.folio_code AS vffolio FROM (((transtb t INNER JOIN vouchertb v ON t.pvno=v.pvno_paid) INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE t.retired='{$retired}' AND j.journal_date BETWEEN '{$from}' AND '{$to}' AND v.dept_vou='{$fact}' AND t.folio_code IN {$f_pvno} AND t.pvno='{$pa_pvno}' ORDER BY t.transdate ASC";*/
				//echo $sql;
				$res_v=@mysqli_query($con, $sql);
				//$count =  mysqli_num_rows($res_v);
				$tamount = 0;
				if(@mysqli_num_rows($res_v)>=1)
				{
					
					while($rs_v=@mysqli_fetch_array($res_v))
					{
						++$sn;
						$pvno=$rs_v['pvno']; 
						$pvno_paid=$rs_v['tpvno'];
						$p=base64_encode($pvno);
						$tp=base64_encode($pvno_paid);
						$r_id = $rs_v['id'];
						$tid = $rs_v['tid'];
						$fileno = $rs_v['fileno'];
						$date_paid = strtotime($rs_v['transdate']); 
						$todate = strtotime(date('Y-m-d'));
						$diff = abs($todate - $date_paid);
						$years = floor($diff / (365*60*60*24));
						$months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));
						$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

						$cal_dif2 =  "<b>".$years." Yr ". $months." Mon " .$days." Days</b>" ;
						$payee_phone=$bursary->get_any_value("phone_no","stafftb","fileno",$fileno);
						$payee_email=$bursary->get_any_value("email","stafftb","fileno",$fileno);
						$payee_name=$rs_v['payee_name'];
						$payee_acct_no=$rs_v['payee_acct_no'];
						$payee_bank_name=$rs_v['payee_bank_name'];
						$voucher_date=$rs_v['voucher_date'];
						$folio = $rs_v['vffolio'];
						$desc = str_replace("Being being", 'Being', $rs_v['description']); get_folio_name($folio);
						/*if($rs_v['retired']=="Yes") $status="Cleared"; else {
							$status="UNRETIRED<!--a href='journal_retirement.php?pvno={$tp}&r_val={$_REQUEST['r_val']}&tid={$tid}' target='_blank'>RETIRE</a-->";
						}*/
						if($rs_v['retired']=="Yes") $status="CLEARED"; else {
							$status="<a href='journal_retirement.php?pvno={$tp}&r_val={$_REQUEST['r_val']}&tid={$tid}&rid={$r_id}&ipvno={$p}' target='_blank'>RETIRE</a>";
						}
						$amount = number_format($rs_v['amount'], 2); //number_format($rs_v['amount_paid'], 2);
						$tamount = $bursary->get_any_value(' SUM(amount) AS tamount ', 'transtb', 'pvno', $pvno_paid, " AND YEAR(transdate)= '{$ryear}' AND folio_code IN {$f_pvno}"); //$rs_v['tamount'];
						$unret += $tamount;
						$amount_retired = $rs_v['amount_retired']; 
						$ret += $rs_v['amount_retired'];
						$date_retired = ($rs_v['date_retired'] == '0000-00-00' || $rs_v['date_retired'] == null ? "" : date('d/m/Y',strtotime($rs_v['date_retired'])));
						$date_paid = ($rs_v['transdate'] == '0000-00-00' || $rs_v['transdate'] == null ? "" : date('d/m/Y',strtotime($rs_v['transdate'])));
						
						//$jvno = $bursary->get_any_value("jvno", "pa_retirementtb", "pvno", $pvno_paid);
						
						$unitcode = $bursary->get_any_value("unit_code","stafftb","fileno",$fileno);
						$dept = $bursary->get_any_value("unit_name", "unittb", "unit_code", $unitcode);
						//$jvno = $bursary->get_any_value("comment", "transtb", "pvno", $pvno_paid);
						if($dept==''){
							$dept=$rs_v['payee_address'];
							$payee_phone=$bursary->get_any_value("phone_no","stafftb","concat(surname,' ',first_name, ' ', other_name)",$payee_name);
						}

						$tb.="<tr>
							<td>$sn</td>
							<td>".date('d/m/Y',strtotime($voucher_date))."</td>
							<td>$payee_name</td>
							<td>$desc</td>
							<td>$dept</td>
							<td>".number_format($tamount, 2)."</td>

							

							<td>".number_format($amount_retired, 2)."</td>
							<td nowrap>".$folio."</td>
							<td><a class='iframe' href='voucher_report.php?p=$p' target='_blank'>$pvno_paid</a></td>
							<td>".$date_paid."</td>
							<td>".$jvno."</td>
							<td>".number_format($tamount-$amount_retired, 2)."</td>
							<td>".$date_retired."</td>
							<td>".$status."</td>
							<td>$payee_phone</td>
							<!--<td>$payee_acct_no</td><td>$payee_bank_name</td><td nowrap>$cal_dif2</td>-->
						</tr>";

					} //end of while
				}
			}
			$tb.="<tr><td></td><td></td><td></td><td></td><td></td><td>".number_format($unret, 2)."</td><td>".number_format($ret, 2)."</td><td></td><td></td><td></td><td nowrap></td>
				<td>".number_format($unret-$ret, 2)."</td>
				<td></td><td></td><td></td>
				</tr>";
			$tb.= "<tr><td colspan = '11' align = 'center'><b>Total Number of Record = $sn || Total Amount Paid = ". number_format($unret, 2)."</b></td></tr>";
			$tb.="</tbody></table>"; echo $tb;
		}
		elseif ($retired == 'No')
		{
			$tb="<table id='MyTable' class='display; align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
			<thead>
			<tr><td colspan='15' align='center' style='font-weight:bold; font-size:20px;'><P>BURSARY DEPARTMENT</P> UNRETIRED PURCHASE ADVANCE BETWEEN ".date('d-m-Y', strtotime($from))." TO ".date('d-m-Y', strtotime($to))."</td></tr>
			<tr style='border:solid 1px #000; background-color:#f2f2f2'>
				<th>S/NO</th>
				<th>DATE</th>
				<th>PARTICULARS</th>
				<th>PAYMENT DESCRIPTION</th>
				<th>DEPARTMENT</th>
				<th>DEBIT</th>
				<th>CREDIT</th>
				<th>CODE</th>
				<th>PV NO.</th>
				<th>PV DATE</th>
				<th>PAJV</th>
				<th>CURRENT BALANCE</th>
				<th>DATE RETIRED</th>
				<th>STATUS</th>
				<th>PHONE</th>
				</tr></thead><tbody>";
			$sn=0; 
			$unret = 0;	$ret = 0;
			$count=0;
			if($fact=='') $sql="SELECT v.*, f.title, t.folio_code AS vffolio, t.amount AS vfamount FROM ((vouchertb v INNER JOIN transtb t ON v.pvno_paid=t.pvno) INNER JOIN foliotb f ON t.folio_code = f.folio_code) WHERE (v.retired='{$retired}' OR v.retired Is Null OR v.retired = '') AND t.transdate BETWEEN '{$from}' AND '{$to}' AND t.folio_code IN {$f_pvno} ORDER BY v.voucher_date ASC";
			else $sql="SELECT v.*, f.title, t.folio_code AS vffolio, t.amount AS vfamount FROM ((vouchertb v INNER JOIN transtb t ON v.pvno_paid=t.pvno) INNER JOIN foliotb f ON t.folio_code = f.folio_code) WHERE (v.retired='{$retired}' OR v.retired Is Null OR v.retired = '') AND v.dept_vou='{$fact}' AND t.transdate BETWEEN '{$from}' AND '{$to}' AND t.folio_code IN {$f_pvno} ORDER BY v.voucher_date ASC"; //( OR v.dept_vou='' OR v.dept_vou Is Null) AND 
			$res_v=@mysqli_query($con, $sql);
			$tamount = 0;
			if(@mysqli_num_rows($res_v)>=1)
			{
				
				while($rs_v=@mysqli_fetch_array($res_v))
				{
					++$sn;
					$pvno=$rs_v['pvno']; 
					$pvno_paid=$rs_v['pvno_paid'];
					$p=base64_encode($pvno);
					$tp=base64_encode($pvno_paid);
					$r_id = $rs_v['id'];
					$tid = $bursary->get_any_value(' id ', 'transtb', 'pvno', "{$pvno_paid}", " AND YEAR(transdate)= '{$ryear}' AND folio_code IN {$f_pvno}");
					$fileno = $rs_v['fileno'];
					$date_paid = strtotime($rs_v['date_paid']); 
					$todate = strtotime(date('Y-m-d'));
					$diff = abs($todate - $date_paid);
					$years = floor($diff / (365*60*60*24));
					$months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));
					$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

					$cal_dif2 =  "<b>".$years." Yr ". $months." Mon " .$days." Days</b>" ;
					$payee_phone=$bursary->get_any_value("phone_no","stafftb","fileno",$fileno);
					$payee_email=$bursary->get_any_value("email","stafftb","fileno",$fileno);
					$payee_name=$rs_v['payee_name'];
					$payee_acct_no=$rs_v['payee_acct_no'];
					$payee_bank_name=$rs_v['payee_bank_name'];
					$voucher_date=$rs_v['voucher_date'];
					$folio = $rs_v['vffolio'];
					$desc = str_replace("Being being", 'Being', $rs_v['description']); get_folio_name($folio);
					/*if($rs_v['retired']=="Yes") $status="Cleared"; else {
						$status="UNRETIRED<!--a href='journal_retirement.php?pvno={$tp}&r_val={$_REQUEST['r_val']}&tid={$tid}' target='_blank'>RETIRE</a-->";
					}*/
					if($rs_v['retired']=="Yes") $status="CLEARED"; else {
						$status="<a href='journal_retirement.php?pvno={$tp}&r_val={$_REQUEST['r_val']}&tid={$tid}&rid={$r_id}&ipvno={$p}' target='_blank'>RETIRE</a>";
					}
					$amount = number_format($rs_v['vfamount'], 2); 
					$tamount = $bursary->get_any_value(' SUM(amount) AS tamount ', 'transtb', 'pvno', "{$pvno_paid}", " AND transdate BETWEEN '{$from}' AND '{$to}' AND folio_code IN {$f_pvno}");
					if($tamount==0) $tamount = $rs_v['vfamount']; 
					$unret += $tamount;
					$amount_retired = $rs_v['amount_retired']; 
					$ret += $rs_v['amount_retired'];
					$date_retired = ($rs_v['date_retired'] == '0000-00-00' || $rs_v['date_retired'] == null ? "" : date('d/m/Y',strtotime($rs_v['date_retired'])));
					$date_paid = ($rs_v['transdate'] == '0000-00-00' || $rs_v['transdate'] == null ? "" : date('d/m/Y',strtotime($rs_v['transdate'])));
					
					$unitcode = $bursary->get_any_value("unit_code","stafftb","fileno",$fileno);
					$dept = $bursary->get_any_value("unit_name", "unittb", "unit_code", $unitcode);
					if($dept==''){
						$dept=$rs_v['payee_address'];
						$payee_phone=$bursary->get_any_value("phone_no","stafftb","concat(surname,' ',first_name, ' ', other_name)",$payee_name);
					}

					$tb.="<tr>
						<td>$sn</td>
						<td>".date('d/m/Y',strtotime($voucher_date))."</td>
						<td>$payee_name</td>
						<td>$desc</td>
						<td>$dept</td>
						<td>".number_format($tamount, 2)."</td>
						<td>".number_format($amount_retired, 2)."</td>
						<td nowrap>".$folio."</td>
						<td><a class='iframe' href='voucher_report.php?p=$p' target='_blank'>$pvno_paid</a></td>
						<td>".$date_paid."</td>
						<td>".$jvno."</td>
						<td>".number_format($tamount-$amount_retired, 2)."</td>
						<td>".$date_retired."</td>
						<td>".$status."</td>
						<td>$payee_phone</td>
						<!--<td>$payee_acct_no</td><td>$payee_bank_name</td><td nowrap>$cal_dif2</td>-->
					</tr>";

				} //end of while
			}
			$tb.="<tr><td></td><td></td><td></td><td></td><td></td><td>".number_format($unret, 2)."</td><td>".number_format($ret, 2)."</td><td></td><td></td><td></td><td nowrap></td>
				<td>".number_format($unret-$ret, 2)."</td>
				<td></td><td></td><td></td>
				</tr>";
			$tb.= "<tr><td colspan = '11' align = 'center'><b>Total Number of Record = $sn || Total Amount Paid = ". number_format($unret, 2)."</b></td></tr>";
			$tb.="</tbody></table>"; echo $tb;
		}else
		{
			$tb="<table id='MyTable' class='display; align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
			<thead>
			<tr><td colspan='15' align='center' style='font-weight:bold; font-size:20px;'><P>BURSARY DEPARTMENT</P> TOTAL PURCHASE ADVANCE BETWEEN ".date('d-m-Y', strtotime($from))." TO ".date('d-m-Y', strtotime($to))."</td></tr>
			<tr style='border:solid 1px #000; background-color:#f2f2f2'>
				<th>S/NO</th>
				<th>DATE</th>
				<th>PARTICULARS</th>
				<th>PAYMENT DESCRIPTION</th>
				<th>DEPARTMENT</th>
				<th>DEBIT</th>
				<th>CREDIT</th>
				<th>CODE</th>
				<th>PV NO.</th>
				<th>PV DATE</th>
				<th>PAJV</th>
				<th>CURRENT BALANCE</th>
				<th>DATE RETIRED</th>
				<th>STATUS</th>
				<th>PHONE</th>
				</tr></thead><tbody>";
			$sn=0; 
			$unret = 0;	$ret = 0;
			$count=0;
			/*if($fact=='') $sql="SELECT v.*, f.title, vf.folio_code AS vffolio, vf.amount AS vfamount FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE v.voucher_date BETWEEN '{$from}' AND '{$to}' AND vf.folio_code IN {$f_pvno} AND v.paid_action='Approved' ORDER BY v.voucher_date ASC";
			else $sql="SELECT v.*, f.title, vf.folio_code AS vffolio, vf.amount AS vfamount FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE v.voucher_date BETWEEN '{$from}' AND '{$to}' AND vf.folio_code IN {$f_pvno} AND v.dept_vou='{$fact}' AND v.paid_action='Approved' ORDER BY v.voucher_date ASC"; //(OR v.dept_vou='' OR v.dept_vou Is Null) 
			if($fact=='') $sql="SELECT v.*, f.title, vf.folio_code AS vffolio, vf.amount AS vfamount FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE (v.retired = 'No' OR v.retired = '' OR v.retired Is Null) AND v.voucher_date BETWEEN '{$from}' AND '{$to}' AND vf.folio_code IN {$f_pvno} AND v.paid_action='Approved' AND v.pvno_paid!='' ORDER BY v.voucher_date ASC";
			else $sql="SELECT v.*, f.title, vf.folio_code AS vffolio, vf.amount AS vfamount FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE (v.retired = 'No' OR v.retired = '' OR v.retired Is Null) AND v.voucher_date BETWEEN '{$from}' AND '{$to}' AND vf.folio_code IN {$f_pvno} AND v.dept_vou='{$fact}' AND v.paid_action='Approved' AND v.pvno_paid!='' ORDER BY v.voucher_date ASC";*/
			if($fact=='') $sql="SELECT v.*, f.title, t.folio_code AS vffolio, t.amount AS vfamount FROM ((vouchertb v INNER JOIN transtb t ON v.pvno_paid=t.pvno) INNER JOIN foliotb f ON t.folio_code = f.folio_code) WHERE t.transdate BETWEEN '{$from}' AND '{$to}' AND t.folio_code IN {$f_pvno} ORDER BY v.voucher_date ASC";
			else $sql="SELECT v.*, f.title, t.folio_code AS vffolio, t.amount AS vfamount FROM ((vouchertb v INNER JOIN transtb t ON v.pvno_paid=t.pvno) INNER JOIN foliotb f ON t.folio_code = f.folio_code) WHERE v.dept_vou='{$fact}' AND t.transdate BETWEEN '{$from}' AND '{$to}' AND t.folio_code IN {$f_pvno} ORDER BY v.voucher_date ASC";
			$res_v=@mysqli_query($con, $sql);
			$tamount = 0;
			if(@mysqli_num_rows($res_v)>=1)
			{
				
				while($rs_v=@mysqli_fetch_array($res_v))
				{
					++$sn;
					$pvno=$rs_v['pvno']; 
					$pvno_paid=$rs_v['pvno_paid'];
					$p=base64_encode($pvno);
					$tp=base64_encode($pvno_paid);
					$r_id = $rs_v['id'];
					$tid = $bursary->get_any_value(' id ', 'transtb', 'pvno', "{$pvno_paid}", " AND YEAR(transdate)= '{$ryear}' AND folio_code IN {$f_pvno}");
					$fileno = $rs_v['fileno'];
					$date_paid = strtotime($rs_v['date_paid']); 
					$todate = strtotime(date('Y-m-d'));
					$diff = abs($todate - $date_paid);
					$years = floor($diff / (365*60*60*24));
					$months = floor(($diff - $years * 365*60*60*24)/ (30*60*60*24));
					$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

					$cal_dif2 =  "<b>".$years." Yr ". $months." Mon " .$days." Days</b>" ;
					$payee_phone=$bursary->get_any_value("phone_no","stafftb","fileno",$fileno);
					$payee_email=$bursary->get_any_value("email","stafftb","fileno",$fileno);
					$payee_name=$rs_v['payee_name'];
					$payee_acct_no=$rs_v['payee_acct_no'];
					$payee_bank_name=$rs_v['payee_bank_name'];
					$voucher_date=$rs_v['voucher_date'];
					$folio = $rs_v['vffolio'];
					$desc = str_replace("Being being", 'Being', $rs_v['description']); get_folio_name($folio);
					/*if($rs_v['retired']=="Yes") $status="Cleared"; else {
						$status="UNRETIRED<!--a href='journal_retirement.php?pvno={$tp}&r_val={$_REQUEST['r_val']}&tid={$tid}' target='_blank'>RETIRE</a-->";
					}*/
					if($rs_v['retired']=="Yes") $status="CLEARED"; else {
						$status="<a href='journal_retirement.php?pvno={$tp}&r_val={$_REQUEST['r_val']}&tid={$tid}&rid={$r_id}&ipvno={$p}' target='_blank'>RETIRE</a>";
					}
					$amount = number_format($rs_v['vfamount'], 2); 
					$tamount = $bursary->get_any_value(' SUM(amount) AS tamount ', 'transtb', 'pvno', "{$pvno_paid}", " AND transdate BETWEEN '{$from}' AND '{$to}' AND folio_code IN {$f_pvno}");
					if($tamount==0) $tamount = $rs_v['vfamount'];
					$unret += $tamount;
					$amount_retired = $rs_v['amount_retired']; 
					$ret += $rs_v['amount_retired'];
					$date_retired = ($rs_v['date_retired'] == '0000-00-00' || $rs_v['date_retired'] == null ? "" : date('d/m/Y',strtotime($rs_v['date_retired'])));
					$date_paid = ($rs_v['transdate'] == '0000-00-00' || $rs_v['transdate'] == null ? "" : date('d/m/Y',strtotime($rs_v['transdate'])));
					
					$unitcode = $bursary->get_any_value("unit_code","stafftb","fileno",$fileno);
					$dept = $bursary->get_any_value("unit_name", "unittb", "unit_code", $unitcode);
					if($dept==''){
						$dept=$rs_v['payee_address'];
						$payee_phone=$bursary->get_any_value("phone_no","stafftb","concat(surname,' ',first_name, ' ', other_name)",$payee_name);
					}

					$tb.="<tr>
						<td>$sn</td>
						<td>".date('d/m/Y',strtotime($voucher_date))."</td>
						<td>$payee_name</td>
						<td>$desc</td>
						<td>$dept</td>
						<td>".number_format($tamount, 2)."</td>
						<td>".number_format($amount_retired, 2)."</td>
						<td nowrap>".$folio."</td>
						<td><a class='iframe' href='voucher_report.php?p=$p' target='_blank'>$pvno_paid</a></td>
						<td>".$date_paid."</td>
						<td>".$jvno."</td>
						<td>".number_format($tamount-$amount_retired, 2)."</td>
						<td>".$date_retired."</td>
						<td>".$status."</td>
						<td>$payee_phone</td>
						<!--<td>$payee_acct_no</td><td>$payee_bank_name</td><td nowrap>$cal_dif2</td>-->
					</tr>";

				} //end of while
			}
			$tb.="<tr><td></td><td></td><td></td><td></td><td></td><td>".number_format($unret, 2)."</td><td>".number_format($ret, 2)."</td><td></td><td></td><td></td><td nowrap></td>
				<td>".number_format($unret-$ret, 2)."</td>
				<td></td><td></td><td></td>
				</tr>";
			$tb.= "<tr><td colspan = '11' align = 'center'><b>Total Number of Record = $sn || Total Amount Paid = ". number_format($unret, 2)."</b></td></tr>";
			$tb.="</tbody></table>"; echo $tb;
		}
	}

	if($option=='audit_query')
	{
		$ryear=$_REQUEST['pyear3'];



		$sql="SELECT distinct v.*, q.querytext, q.response, q.qrole, q.rrole, q.query_date, q.response_date FROM vouchertb v INNER JOIN voucher_queriestb q ON v.pvno=q.pvno WHERE v.voucher_date like '%$ryear%' and q.qrole = 'auditor' order by voucher_date desc";
		$res_v=@mysqli_query($con, $sql);
		$sn=0;
		$tb="<table id='MyTable' class='display; align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>

		<thead>
		<tr><td colspan='12' align='center'>AUDIT QUERY REPORT</td></tr>
		<tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>NARRATION</th><th>PROCESSING NO</th><th>PAYEE</th><th>PAYEE ACCT NO.</th><!--th>PAYEE BANK</th--><th>QUERY DATE</th><th>GROSS (NET)</th><!--<th>CHECKED</th><th>CERTIFIED</th><th>CONTROLLED</th>--><th>COMMENT</th><th>RESPONSE</th><th>ACTION</th></tr></thead><tbody>";

		if(@mysqli_num_rows($res_v)>=1)
		{
			while($rs_v=@mysqli_fetch_array($res_v))
			{
				++$sn;
				$pvno=$rs_v['pvno']; 
				$pvno_paid=$rs_v['pvno_paid'];
				$p=base64_encode($pvno);
				$payee_name=$rs_v['payee_name'];
				$desc3=$rs_v['description'];
				$payee_acct_no=$rs_v['payee_acct_no'];
				$payee_bank_name=$rs_v['payee_bank_name'];
				$voucher_date=$rs_v['voucher_date'];
				$query_date=date('M-d-Y', strtotime($rs_v['query_date']));
				$response_date=date('M-d-Y', strtotime($rs_v['response_date']));
				$net = number_format($rs_v['amount_approved'], 2);
				$pv = explode('_', $pvno);
				if(count($pv) <= 1){
					$net = number_format($rs_v['amount_paid'], 2);
				}
				$gross = read_gross($pvno);
				$status=$rs_v['status'];

				if ($rs_v['response'] != ''){
					$comment = 'Treated';
				} else {
					$comment = 'Not Treated';
				}

				$tb.="<tr>
				<td>$sn</td>
				<td >$desc3</td>
				<td>$pvno</td>
				<td>$payee_name</td>
				<td>$payee_acct_no ($payee_bank_name)</td>
				<!--td>$payee_bank_name</td-->
				<td>".date('d/m/Y',strtotime($query_date))."</td>
				<td>".$gross." (".$net.")</td>
				<td title='".$audit_date."'>{$rs_v['querytext']}</td>
				<td>{$rs_v['response']}<br>{$response_date}</td>
				<td nowrap><a class='iframe' href='voucher_report.php?p={$p}' target='_blank' >VIEW</a>";

				if($r=="prepared officer" or $r=="budget officer" and ($checked == '' or $checked_action == 'Queried') and ($prepared == $login_id or $r=="super admin" or $r=="administrator"))
				$tb.="  | <a class='iframe' href='voucher_resubmit.php?p={$p}' >RE-SUBMIT</a></td></td></tr>";


			} //end of while

			$tb.="</tbody></table>"; echo $tb;
		}
		else
		echo "<font color='red'><b>No record to display</b></font>";


	}
	///////////////////////////////////////////////////// End of Body ////////////////////////////////////////////
	//////////////////////////////////////////////////////Report Footer /////////////////////////////////////////////
	///////////////////////////////////////////////////// End of Footer ////////////////////////////////////////////
	?>
</body>
</html>
