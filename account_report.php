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
	$option=$_REQUEST['v_opt'];


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


	if($option=='cashbook')
	{
		?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
			<tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>CASHBOOK </h1><br><?php echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td></tr>
			<tr>
				<!-- Debit Column-->
				<td colspan="2" valign="top">
					<br>
					<table width="100%" align="center">
						<tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>REF.No</strong></td><td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td><td bgcolor="#E5E5E5"><strong>DEBIT</strong></td><td bgcolor="#E5E5E5"><strong>CREDIT</strong></td><td bgcolor="#E5E5E5"><strong>BALANCE</strong></td></tr>

						<?php
					$sqqx="SELECT * FROM transtb WHERE acctcode = '{$account}' AND transdate BETWEEN '{$from}' AND '{$to}'";
					$rsmonthx=@mysqli_query($con, $sqqx) or die( mysqli_error($con));
					$mtnumx=@mysqli_num_rows($rsmonthx);
					$accounts=array();
					if($mtnumx > 0)
					{
						if(date('m', strtotime($from)) == 1){
							$rstrans_ox= mysqli_query($con, "SELECT sum(amount) AS total_credit FROM transtb WHERE transtype='Credit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}' ") or die( mysqli_error($con));

							$rstrans_oy = mysqli_query($con, "SELECT sum(amount) AS total_debit FROM transtb WHERE transtype='Debit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}'") or die( mysqli_error($con));

							if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 )) $total_credixt=$rs_trans_ox[0];

						
							if($rs_trans_oy=@mysqli_fetch_array($rstrans_oy, 3 )) $total_debitx=$rs_trans_oy[0];
	
						}else {
							$xd = str_pad(date('m', strtotime($from))-1, 2, '0', STR_PAD_LEFT);
							$eDay = $bursary->get_any_value("month_end", "monthtb", "month_code", date('m', strtotime($from))-1);
							$leap = date('L', strtotime($from));
							if($leap && $xd=='02') $eDay='29';
							$dDate = date('Y', strtotime($from))."-01-01";
							$sDate = date('Y', strtotime($from))."-01-02";
							$eDate = date('Y', strtotime($from))."-".$xd."-".$eDay;

							$xSql = "SELECT sum(amount) AS total_credit FROM transtb WHERE transtype='Debit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}'"; 

							$xSql2 = "SELECT sum(amount) AS total_credit FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype = 'Credit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}')";
							
							$rstrans_ox1= mysqli_query($con, $xSql) or die( mysqli_error($con));
							$rstrans_ox2= mysqli_query($con, $xSql2) or die( mysqli_error($con));


							$ySql = "SELECT sum(amount) AS total_debit FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype = 'Debit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}')";
							
							//"SELECT sum(amount) AS total_debit FROM transtb WHERE transtype='Debit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}') AND folio_code = '{$account}'";
							$rstrans_oy = mysqli_query($con, $ySql) or die( mysqli_error($con));

							$rs_trans_ox1=@mysqli_fetch_array($rstrans_ox1, 3 );
							$rs_trans_ox2=@mysqli_fetch_array($rstrans_ox2, 3 );$total_credixt=$rs_trans_ox1[0] + $rs_trans_ox2[0];

						
							if($rs_trans_oy=@mysqli_fetch_array($rstrans_oy, 3 )) $total_debitx=$rs_trans_oy[0];
	
						}

						$opening_balancex = abs($total_credixt - $total_debitx);
						?>
						<tr class='$rowclass' nowrap><td></td><td><?php echo date('d-M-Y',strtotime($_REQUEST['from'])); ?></td><td>Opening Balance</td><td>&nbsp;</td><td>&nbsp;</td><td></td><td><?php echo number_format($opening_balancex, 2); ?></td><td></td><td><?php echo number_format($opening_balancex, 2); ?></td></tr>
						<?php
						$sn=0;
						$debittotal=0; $b_lance = 0;
						$debit=0;
						$credit=0;
						if(date('m', strtotime($from))==1)
							$sq="SELECT t.*, f.title FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype IN ('Debit','Credit') AND (transdate > '{$from}' AND transdate <= '{$to}') ORDER BY t.transdate, t.pvno";
						else
							$sq="SELECT t.*, f.title FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype IN ('Debit','Credit') AND (transdate >= '{$from}' AND transdate <= '{$to}') ORDER BY t.transdate, t.pvno";

						$rstrans=@mysqli_query($con, $sq) or die( mysqli_error($con));
						$sn=0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 )){
							++$sn;
							if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
							$amount = $rs_trans['amount'];
							//$paybatch = $rs_trans['paybatch'];
							$payee = $rs_trans['payee'];
							$transtype=@$rs_trans['transtype'];
							$debittotal += $rs_trans['amount']; 
							$transdate=@$rs_trans['transdate'];

							$ftit = $rs_trans['title']; 
							$folio_title = $ftit."(".$rs_trans['folio_code'].")";

							$paybatch =  @$bursary->get_any_value('batchno', 'vouchertb', 'pvno_paid', $rs_trans['pvno'], " AND (date_paid >= '{$from}' AND date_paid <= '{$to}')");

							echo "<tr class='{$rowclass}'><td>{$sn}</td><td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>{$folio_title}</td><td>{$rs_trans['folio_code']}</td><td>{$rs_trans['pvno']}{$rs_trans['receiptno']}</td><td></td>";

							if ($transtype=='Credit'){
								$credit += $amount;
								if($sn == 1 )
									$b_lance =  $opening_balancex + $amount;
								else
									$b_lance += $amount;

								echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
								echo	"<td>". number_format($b_lance, 2)."</td></tr>";
							} else {
								$debit += $amount;
								if($sn == 1 )
									$b_lance = $opening_balancex - $amount;
								else
									$b_lance -= $amount;

								echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
								echo	"<td>". number_format($b_lance, 2)."</td></tr>";
							}
						}
						?>
						<tr>
							<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b></b></td><td></td><td></td><td></td>
						</tr>
						<tr>
							<td></td><td></td><td><strong>Closing Balance : </strong></td><td ></td><td ></td><td ></td>
							<td><b><?php echo number_format($opening_balancex+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
							<td><b><?php echo number_format($b_lance, 2); ?></b></td>
						</tr>
						<!-- End of Summary of Records for Debit -->
						</table>
						</td><!-- End of Debit Column-->
						</tr>

						<?php
					}

					else
					{
						echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
					}
					?>
				</td>
			</tr>
			<tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
			</tr>
		</table>

		<?php
	} // end of Cashbook

	if($option=='cashbook_treasury_ncoa')
	{
		?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>CASHBOOK </h1><br><?php echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td></tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td><td bgcolor="#E5E5E5"><strong>PAYEE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td>
					<td bgcolor="#E5E5E5"><strong>NCOA</strong></td>
					<td bgcolor="#E5E5E5"><strong>NCOA DESC.</strong></td>
					<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
					<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
					<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td><td bgcolor="#E5E5E5"><strong>BALANCE</strong></td></tr>

					<?php
					$sqqx="SELECT * FROM transtb WHERE acctcode = '{$account}' AND transdate BETWEEN '{$from}' AND '{$to}'";
					$rsmonthx=@mysqli_query($con, $sqqx) or die( mysqli_error($con));
					$mtnumx=@mysqli_num_rows($rsmonthx);
					$accounts=array();
					//echo date('m', strtotime($from))-1; exit;
					if($mtnumx > 0)
					{
						if(date('m', strtotime($from)) == 1){
							$rstrans_ox= mysqli_query($con, "SELECT sum(amount) AS total_credit FROM transtb WHERE transtype='Credit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}' ") or die( mysqli_error($con));

							$rstrans_oy = mysqli_query($con, "SELECT sum(amount) AS total_debit FROM transtb WHERE transtype='Debit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}'") or die( mysqli_error($con));

							if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 )) $total_credixt=$rs_trans_ox[0];

						
							if($rs_trans_oy=@mysqli_fetch_array($rstrans_oy, 3 )) $total_debitx=$rs_trans_oy[0];
	
						}else {
							$xd = str_pad(date('m', strtotime($from))-1, 2, '0', STR_PAD_LEFT);
							$eDay = $bursary->get_any_value("month_end", "monthtb", "month_code", date('m', strtotime($from))-1);
							$leap = date('L', strtotime($from));
							if($leap && $xd=='02') $eDay='29';
							$dDate = date('Y', strtotime($from))."-01-01";
							$sDate = date('Y', strtotime($from))."-01-02";
							$eDate = date('Y', strtotime($from))."-".$xd."-".$eDay;

							$xSql = "SELECT sum(amount) AS total_credit FROM transtb WHERE transtype='Debit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}'"; 

							$xSql2 = "SELECT sum(amount) AS total_credit FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype = 'Credit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}')";
							
							$rstrans_ox1= mysqli_query($con, $xSql) or die( mysqli_error($con));
							$rstrans_ox2= mysqli_query($con, $xSql2) or die( mysqli_error($con));


							$ySql = "SELECT sum(amount) AS total_debit FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype = 'Debit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}')";
							
							//"SELECT sum(amount) AS total_debit FROM transtb WHERE transtype='Debit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}') AND folio_code = '{$account}'";
							$rstrans_oy = mysqli_query($con, $ySql) or die( mysqli_error($con));

							$rs_trans_ox1=@mysqli_fetch_array($rstrans_ox1, 3 );
							$rs_trans_ox2=@mysqli_fetch_array($rstrans_ox2, 3 );$total_credixt=$rs_trans_ox1[0] + $rs_trans_ox2[0];

						
							if($rs_trans_oy=@mysqli_fetch_array($rstrans_oy, 3 )) $total_debitx=$rs_trans_oy[0];
	
						}
						

						$opening_balance = abs($total_credixt - $total_debitx);
						?>
						<tr class='$rowclass' nowrap><td></td><td><?php echo date('d-M-Y',strtotime($_REQUEST['from'])); ?></td><td>Opening Balance</td><td>&nbsp;</td><td>&nbsp;</td><td></td><td></td><td></td><td><?php echo number_format($opening_balance, 2); ?></td><td></td><td><?php echo number_format($opening_balance, 2); ?></td></tr>
						<?php
						
						$sn=0;
						$debittotal=0; $b_lance = 0;
						$debit=0;
						$credit=0;
						if(date('m', strtotime($from))==1)
							$sq="SELECT t.*, f.title, f.ncoa_code, f.ncoa_title FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype IN ('Debit','Credit') AND (transdate > '{$from}' AND transdate <= '{$to}') ORDER BY t.transdate, t.pvno";
						else
							$sq="SELECT t.*, f.title, f.ncoa_code, f.ncoa_title FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype IN ('Debit','Credit') AND (transdate >= '{$from}' AND transdate <= '{$to}') ORDER BY t.transdate, t.pvno";
						$rstrans=@mysqli_query($con, $sq) or die( mysqli_error($con));
						$sn=0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 )){
							++$sn;
							if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
							$amount = $rs_trans['amount'];
							//$paybatch = $rs_trans['paybatch'];
							$payee = $rs_trans['payee'];
							$transtype=@$rs_trans['transtype'];
							$debittotal += $rs_trans['amount']; 
							$transdate=@$rs_trans['transdate'];

							$ftit = $rs_trans['title']; 
							$folio_title = $ftit."(".$rs_trans['folio_code'].")";

							if  ($rs_trans[pvno] == '') {
									$payee_name = '';
								}
								else {
									$payee_name =  @$bursary->get_any_value('payee_name', 'vouchertb', 'pvno_paid', $rs_trans['pvno'], " AND (date_paid >= '{$from}' AND date_paid <= '{$to}')");
							}
							$paybatch =  @$bursary->get_any_value('batchno', 'vouchertb', 'pvno_paid', $rs_trans['pvno'], " AND (date_paid >= '{$from}' AND date_paid <= '{$to}')");

							echo "<tr class='{$rowclass}'><td>{$sn}</td><td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>{$payee_name} {$payee}</td><td>{$folio_title}</td><td nowrap>{$rs_trans['folio_code']}</td><td>{$rs_trans['ncoa_code']}</td><td>{$rs_trans['ncoa_title']}</td><td>".strtoupper(str_replace(' ', '', $rs_trans['pvno']))."{$rs_trans['receiptno']}</td>";

							if ($transtype=='Credit'){
									$credit += $amount;
									if($sn == 1 )
										$b_lance =  $opening_balance + $amount;
									else
										$b_lance += $amount;

									echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
									echo	"<td>". number_format($b_lance, 2)."</td></tr>";
								} else {
									$debit += $amount;
									if($sn == 1 )
										$b_lance = $opening_balance - $amount;
									else
										$b_lance -= $amount;

									echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
									echo	"<td>". number_format($b_lance, 2)."</td></tr>";
							}
						}
						?>
						<tr>
							<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b></b></td><td></td><td></td><td></td><td></td>
						</tr>
						<tr>
							<td></td><td></td><td><strong>Closing Balance : </strong></td><td ></td><td ></td><td ></td><td ></td><td ></td>
							<td><b><?php echo number_format($opening_balance + $credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
							<td><b><?php echo number_format($b_lance, 2); ?></b></td>
						</tr>
						<!-- End of Summary of Records for Debit -->
						</table>
						</td><!-- End of Debit Column-->
						</tr>

						<?php

					}

						else
						{
							echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
					}
					?>
				</td>
			</tr>

			<tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
		</table>

		<?php
	} // end of Cashbook for treasury

	if($option=='cashbook_treasury')// cashbook for treasury
	{
		?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>CASHBOOK </h1><br><?php echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td></tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td><td bgcolor="#E5E5E5"><strong>PAYEE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>REF.No</strong></td><td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td><td bgcolor="#E5E5E5"><strong>BATCH ID</strong></td><td bgcolor="#E5E5E5"><strong>DEBIT</strong></td><td bgcolor="#E5E5E5"><strong>CREDIT</strong></td><td bgcolor="#E5E5E5"><strong>BALANCE</strong></td></tr>

					<?php
					$sqqx="SELECT * FROM transtb WHERE acctcode = '{$account}' AND transdate BETWEEN '{$from}' AND '{$to}'";
					$rsmonthx=@mysqli_query($con, $sqqx) or die( mysqli_error($con));
					$mtnumx=@mysqli_num_rows($rsmonthx);
					$accounts=array();
					//echo date('m', strtotime($from))-1; exit;
					if($mtnumx > 0)
					{
						if(date('m', strtotime($from)) == 1){
							$rstrans_ox= mysqli_query($con, "SELECT sum(amount) AS total_credit FROM transtb WHERE transtype='Credit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}' ") or die( mysqli_error($con));

							$rstrans_oy = mysqli_query($con, "SELECT sum(amount) AS total_debit FROM transtb WHERE transtype='Debit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}'") or die( mysqli_error($con));

							if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 )) $total_credixt=$rs_trans_ox[0];

						
							if($rs_trans_oy=@mysqli_fetch_array($rstrans_oy, 3 )) $total_debitx=$rs_trans_oy[0];
	
						}else {
							$xd = str_pad(date('m', strtotime($from))-1, 2, '0', STR_PAD_LEFT);
							$eDay = $bursary->get_any_value("month_end", "monthtb", "month_code", date('m', strtotime($from))-1);
							$leap = date('L', strtotime($from));
							if($xd=='02') {
								$eDay='28';
								if($leap) $eDay='29';
							}
							$dDate = date('Y', strtotime($from))."-01-01";
							$sDate = date('Y', strtotime($from))."-01-02";
							$eDate = date('Y', strtotime($from))."-".$xd."-".$eDay;

							$xSql = "SELECT sum(amount) AS total_credit FROM transtb WHERE transtype='Debit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}'"; 

							$xSql2 = "SELECT sum(amount) AS total_credit FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype = 'Credit' AND (transdate BETWEEN '{$dDate}' AND '{$eDate}')";
							
							$rstrans_ox1= mysqli_query($con, $xSql) or die( mysqli_error($con));
							$rstrans_ox2= mysqli_query($con, $xSql2) or die( mysqli_error($con));


							$ySql = "SELECT sum(amount) AS total_debit FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype = 'Debit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}')";
							
							//"SELECT sum(amount) AS total_debit FROM transtb WHERE transtype='Debit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}') AND folio_code = '{$account}'";
							$rstrans_oy = mysqli_query($con, $ySql) or die( mysqli_error($con));

							$rs_trans_ox1=@mysqli_fetch_array($rstrans_ox1, 3 );
							$rs_trans_ox2=@mysqli_fetch_array($rstrans_ox2, 3 );$total_credixt=$rs_trans_ox1[0] + $rs_trans_ox2[0];

						
							if($rs_trans_oy=@mysqli_fetch_array($rstrans_oy, 3 )) $total_debitx=$rs_trans_oy[0];
	
						}
						

						$opening_balance = abs($total_credixt - $total_debitx);
						?>
						<tr class='$rowclass' nowrap><td></td><td><?php echo date('d-M-Y',strtotime($_REQUEST['from'])); ?></td><td>Opening Balance</td><td>&nbsp;</td><td>&nbsp;</td><td></td><td></td><td></td><td><?php echo number_format($opening_balance, 2); ?></td><td></td><td><?php echo number_format($opening_balance, 2); ?></td></tr>
						<?php
						
						$sn=0;
						$debittotal=0; $b_lance = 0;
						$debit=0;
						$credit=0;
						if(date('m', strtotime($from))==1)
							$sq="SELECT t.*, f.title FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype IN ('Debit','Credit') AND (transdate > '{$from}' AND transdate <= '{$to}') ORDER BY t.transdate, t.pvno";
						else
							$sq="SELECT t.*, f.title FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype IN ('Debit','Credit') AND (transdate >= '{$from}' AND transdate <= '{$to}') ORDER BY t.transdate, t.pvno";
						$rstrans=@mysqli_query($con, $sq) or die( mysqli_error($con));
						$sn=0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 )){
							++$sn;
							if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
							$amount = $rs_trans['amount'];
							//$paybatch = $rs_trans['paybatch'];
							$payee = $rs_trans['payee'];
							$transtype=@$rs_trans['transtype'];
							$debittotal += $rs_trans['amount']; 
							$transdate=@$rs_trans['transdate'];

							$ftit = $rs_trans['title']; 
							$folio_title = $ftit."(".$rs_trans['folio_code'].")";
							$pyr=date('Y', strtotime($from));

							if  ($rs_trans['pvno'] == '') {
									$payee_name = $payee;
								}
								else {
									
									$payee_name =  @$bursary->get_any_value('payee_name', 'vouchertb', 'pvno_paid', $rs_trans['pvno'], " AND YEAR(date_paid) = '{$pyr}'");
							}
							$paybatch = @$bursary->get_any_value('batchno', 'vouchertb', 'pvno_paid', $rs_trans['pvno'], " AND YEAR(date_paid) = '{$pyr}'");
							if($paybatch == '') $paybatch = $rs_trans['paybatch'];

							echo "<tr class='{$rowclass}'><td>{$sn}</td><td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>{$payee_name} {$payee}</td><td>{$folio_title}</td><td nowrap>{$rs_trans['folio_code']}</td><td>".strtoupper(str_replace(' ', '', $rs_trans['pvno']))."{$rs_trans['receiptno']}</td><td></td><td>{$paybatch}</td>";

							if ($transtype=='Credit'){
									$credit += $amount;
									if($sn == 1 )
										$b_lance =  $opening_balance + $amount;
									else
										$b_lance += $amount;

									echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
									echo	"<td>". number_format($b_lance, 2)."</td></tr>";
								} else {
									$debit += $amount;
									if($sn == 1 )
										$b_lance = $opening_balance - $amount;
									else
										$b_lance -= $amount;

									echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
									echo	"<td>". number_format($b_lance, 2)."</td></tr>";
							}
						}
						?>
						<tr>
							<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b></b></td><td></td><td></td><td></td><td></td>
						</tr>
						<tr>
							<td></td><td></td><td><strong>Closing Balance : </strong></td><td ></td><td ></td><td ></td><td ></td><td ></td>
							<td><b><?php echo number_format($opening_balance + $credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
							<td><b><?php echo number_format($b_lance, 2); ?></b></td>
						</tr>
						<!-- End of Summary of Records for Debit -->
						</table>
						</td><!-- End of Debit Column-->
						</tr>

						<?php

					}

						else
						{
							echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
					}
					?>
				</td>
			</tr>

			<tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
		</table>

		<?php
	} // end of Cashbook for treasury
	////////////************ Section for JOURNAL**********************///////////

	//////////**********************************************************************************************////////////////
	if($option=='journal')
	{
		echo "journal";

	} // end of journal

	////////////************ Section for ledger per Folio **********************///////////

	//////////**********************************************************************************************////////////////
	if($option=='ledger')
	{
		if(isset($_REQUEST['fromsummary']) && $_REQUEST['WHERE'] != ''){
			$tyear = $_REQUEST['transyear'];
			$from = $_REQUEST['from'] = $tyear."-01-01";
			$to = $_REQUEST['to'] = $tyear."-12-31";
		}
		?>
		<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
			<tr>
				<td height="27" colspan="2" align="center" bgcolor="#FFCCFF"><p><strong>GENERAL LEDGER</strong></p></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#E0E0E0"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td>
			</tr>
			<?php
			//////////////////////////////////////////
			if(isset($_REQUEST['fromsummary']) && $_REQUEST['WHERE'] != ''){
				$tyear = $_REQUEST['transyear'];
				$from = $tyear."-01-01";
				$to = $tyear."-12-31";
				$sql = "SELECT DISTINCT concat(monthname(transdate), concat(', ',year(transdate))) AS monthyear, month(transdate) AS m, year(transdate) AS y FROM transtb WHERE transdate BETWEEN '{$from}' AND '{$to}' AND folio_code IN (".base64_decode($_REQUEST['WHERE']).") ORDER BY m";
			}else
				$sql = "SELECT DISTINCT concat(monthname(transdate), concat(', ',year(transdate))) AS monthyear, month(transdate) AS m, year(transdate) AS y FROM transtb WHERE transdate BETWEEN '{$from}' AND '{$to}' ORDER BY m";
			$rsmonth=@mysqli_query($con, $sql);
			if(@mysqli_num_rows($rsmonth)>=1)
			{
				while($rs_month=@mysqli_fetch_array($rsmonth))
				{
					$monthyear=@$rs_month['monthyear'];
					$month=@$rs_month['m'];
					$year=@$rs_month['y'];
					//echo "<tr><td colspan='2' align='center'><h3>$monthyear</h3></td></tr>";
				}
				?>
				<tr>
					<!-- Debit Column-->
					<td colspan="2" valign="top">
						<?php
						if(isset($_REQUEST['fromsummary']) && $_REQUEST['WHERE'] != '')
							$sqll = "SELECT DISTINCT f.title, t.folio_code FROM transtb t INNER JOIN foliotb f ON f.folio_code=t.folio_code WHERE transdate between '{$from}' and '{$to}' and t.transtype in ('Debit','Credit') AND t.folio_code IN (".base64_decode($_REQUEST['WHERE']).")";
						else
							$sqll = "SELECT DISTINCT f.title, t.folio_code FROM transtb t INNER JOIN foliotb f ON f.folio_code=t.folio_code WHERE transdate between '{$from}' and '{$to}' and t.transtype in ('Debit','Credit')";
						$msql= mysqli_query($con, $sqll) or die( mysqli_error($con));
						if( mysqli_num_rows($msql) > 0){
							$isn=0;echo "<ol>";
							while($innercode= mysqli_fetch_array($msql, 3 )){
								echo "<li><span style='font-size: 16px'>","Account Code: ", $innercode['folio_code'], " - Account Name: ", $innercode['title'], " <p>Number of Items = ";
								$c= mysqli_query($con, "SELECT * FROM transtb where transdate between '{$from}' and '{$to}' and folio_code='".$innercode['folio_code']."'");
								echo  mysqli_num_rows($c), "</p></span>";
								?>
								<center>
									<table width="90%" align="center">
										<tr>
											<td bgcolor="#E5E5E5" width='5%'>S/N</td>
											<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DATE OF ENTRY</strong></td>
											<td height="24" bgcolor="#E5E5E5"><strong>TRANSACTION</strong></td>
											<td height="24" bgcolor="#E5E5E5"><strong>NAME</strong></td>
											<td height="24" bgcolor="#E5E5E5"><strong>FOLIO CODE</strong></td>
											<td height="24" bgcolor="#E5E5E5" width='10%'><strong>REC./PV. No.</strong></td>
											<td height="24" bgcolor="#E5E5E5"><strong>DESCRIPTION</strong></td>
											<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DEBIT</strong></td>
											<td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td>
											<td bgcolor="#E5E5E5" width='10%'><strong>BALANCE</strong></td>
										</tr>
										<?php
										//load the Codes for the Bebit side
										$rstrans=@mysqli_query($con, "select t.* from transtb t WHERE transdate between '$from' and '$to' and folio_code='$innercode[folio_code]' and t.transtype in ('Debit','Credit') order by t.transdate") or die( mysqli_error($con));
										$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
										$debittotal=0;
										$credittotal=0; $b_lance = 0;
										while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
										{
											++$sn;
											if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
											$transdate=@$rs_trans['transdate'];
											$folio_code2=@$rs_trans['folio_code'];
											$reciptno = $rs_trans['receiptno'];
											if($rs_trans['transtype'] == 'Debit') {

												$amount = $rs_trans['amount'];
												/*$pvno =  explode("/",$rs_trans['pvno']);
												$pvns = substr($pvno[0],1,2); $pvns2 = substr($pvno[0],2,2);
												if ($pvns=='JV' or $pvns2 == 'JV'){
												$transtype="JOURNAL"; } else { $transtype="PAYMENT"; }
												*/
												if(stripos($rs_trans['pvno'], "JV")) $transtype="JOURNAL";
												else $transtype="PAYMENT";
												$payeen=$rs_trans['payee'];
												if($rs_trans['pvno']!=''){
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "vouchertb", "pvno_paid", $rs_trans['pvno']);
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "journalno", $rs_trans['pvno']);
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "pvno", $rs_trans['pvno']);
												}
												if ($rs_trans['transtype']=='Debit' ){
													$b_lance = $b_lance + $amount;}

													$credittotal += $rs_trans['amount'];
													echo"<tr class='$rowclass'>
													<td width='5%'>$sn</td>
													<td nowrap>".date('d-M-Y',strtotime($transdate))."</td>
													<td>$transtype</td><td>".$payeen."</td><td nowrap>$folio_code2</td><td>$reciptno $rs_trans[pvno]</td width='10%'>
													<td>".@get_folio_name($rs_trans['folio_code'])."</td>
													<td width='10%'>".number_format($rs_trans['amount'], 2)."</td>
													<td></td>
													<td width='10%'>".number_format($b_lance, 2)."</td></tr>";

												}elseif($rs_trans['transtype'] == 'Credit') {

													$amount = $rs_trans['amount'];
													if ($rs_trans['transtype']=='Credit')
													{$b_lance = $b_lance - $amount;}
													$payeen=$rs_trans['payee'];
													if($rs_trans['pvno']!=''){
														if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "vouchertb", "pvno_paid", $rs_trans['pvno']);
														if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "journalno", $rs_trans['pvno']);
														if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "pvno", $rs_trans['pvno']);
													}
													/*$pvno =  explode("/",$rs_trans['pvno']);
													$pvns =  substr($pvno[0],1,2); $pvns2 = substr($pvno[0],2,2);
													if ($pvns=='JV' or $pvns2 == 'JV'){
													$transtype="JOURNAL"; } else { $transtype="RECIEPT"; }
													*/
													if(stripos($rs_trans['pvno'], "JV")) $transtype="JOURNAL";
													else $transtype="RECIEPT";
													$debittotal += $rs_trans['amount'];
													echo"<tr class='$rowclass'><td width='5%'>$sn</td>
													<td>".date('d-M-Y',strtotime($transdate))."</td>
													<td>$transtype</td><td>".$payeen."</td><td>$folio_code2</td><td>$reciptno $rs_trans[pvno]</td>
													<td>".@get_folio_name($rs_trans['folio_code'])."</td>
													<td width='10%'></td>
													<td width='10%'>".number_format($rs_trans['amount'], 2)."</td>
													<td width='10%'>".number_format($b_lance, 2)."</td></tr>";
												}
											}
											?>
											<tr>
												<td colspan="7" align="right">Total : </td>
												<td><b><?php echo number_format($credittotal, 2); ?></b></td>
												<td><b><?php echo number_format($debittotal, 2); ?></b></td>
												<td><b><?php
												$bal = $debittotal - $credittotal;
												if($bal < 0) echo number_format(abs($b_lance), 2);
												else echo number_format($b_lance, 2);
												?></b></td>
											</tr>
											<!-- End of Summary of Records for Debit -->
									</table><p>&nbsp;</p>
								</center>
								<?php
								echo "</li>";
							}//end while
							echo "</ol>";
						}
						else {
							echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
						}
						?>
					</td>
					<!-- End of Debit Column-->
					<!-- Credit Column-->      <!-- End of Credit Column-->
				</tr>
				<?php
				//}// end of while for monthyear
			}// end of monthyear found i.e if(num_row>=1)
			else
			{
				echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
			}
			?>
			<tr>
				<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" />
				</th>
				<td><input type="button" class="row-b" value="Close" onclick="window.close()" />
				</th>
			</tr>
		</table>
		<?php
	}//end of ledger

	if($option=='ledger2')
	{
		?>
		<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
			<tr>
				<td height="27" colspan="2" align="center" bgcolor="#FFCCFF"><p><strong>GENERAL LEDGER SALARIES</strong></p></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#E0E0E0"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td>
			</tr>
			<?php
			$account=$_REQUEST['account'];
			if($account=='') $s1="SELECT distinct concat(monthname(transdate), concat(', ',year(transdate))) as monthyear, month(transdate) as m, year(transdate) as y from transtb WHERE (transdate between '$from' and '$to') AND (acctcode='' OR acctcode='---' OR acctcode IS NULL) order by m";
			else $s1="SELECT distinct concat(monthname(transdate), concat(', ',year(transdate))) as monthyear, month(transdate) as m, year(transdate) as y from transtb WHERE (transdate between '$from' and '$to') AND acctcode='{$account}' order by m";
			$rsmonth=@mysqli_query($con, $s1);
			if(@mysqli_num_rows($rsmonth)>=1)
			{
				while($rs_month=@mysqli_fetch_array($rsmonth))
				{
					$monthyear=@$rs_month['monthyear'];
					$month=@$rs_month['m'];
					$year=@$rs_month['y'];
					//echo "<tr><td colspan='2' align='center'><h3>$monthyear</h3></td></tr>";
				}
				?>
				<tr>
					<!-- Debit Column-->
					<td colspan="2" valign="top">
						<?php
						//echo "select distinct f.title, t.folio_code, transdate from transtb t INNER JOIN foliotb f ON f.folio_code = t.folio_code and t.folio_code in ('09-001-3087','02-001-3087') WHERE transdate between '$from' and '$to' and t.transtype in ('Debit','Credit') order by transdate"; exit;
						if($account=='') $sqll = "SELECT distinct f.title, t.folio_code from transtb t INNER JOIN foliotb f ON f.folio_code = t.folio_code and t.folio_code in ('09-001-3087','02-001-3087') WHERE (transdate between '{$from}' and '{$to}') and (t.acctcode='' OR t.acctcode='---' OR t.acctcode IS NULL) and t.transtype in ('Debit','Credit') order by t.folio_code";
						else $sqll = "SELECT distinct f.title, t.folio_code from transtb t INNER JOIN foliotb f ON f.folio_code = t.folio_code and t.folio_code in ('09-001-3087','02-001-3087') WHERE (transdate between '{$from}' and '{$to}') and t.acctcode='{$account}' and t.transtype in ('Debit','Credit') order by t.folio_code";
						$msql= mysqli_query($con, $sqll) or die( mysqli_error($con));
						if( mysqli_num_rows($msql) > 0){
							$isn=0;
							echo "<ol>";
							while($innercode= mysqli_fetch_array($msql, 3 )){
								echo "<li><span style='font-size: 16px'>","Account Code: ", $innercode['folio_code'], " - Account Name: ", $innercode['title'], " <p>Number of Items = ";
								$c= mysqli_query($con, "SELECT * FROM transtb where transdate between '{$from}' and '{$to}' and folio_code='".$innercode['folio_code']."'");
								echo  mysqli_num_rows($c), "</p></span>";
								?>
								<center>
									<table width="90%" align="center">
										<tr>
											<td bgcolor="#E5E5E5" width='5%'>S/N</td>
											<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DATE OF ENTRY</strong></td>
											<td height="24" bgcolor="#E5E5E5"><strong>TRANSACTION</strong></td>
											<td height="24" bgcolor="#E5E5E5"><strong>NAME</strong></td>
											<td height="24" bgcolor="#E5E5E5" nowrap><strong>FOLIO CODE</strong></td>
											<td height="24" bgcolor="#E5E5E5" width='10%'><strong>REC./PV. No.</strong></td>
											<td height="24" bgcolor="#E5E5E5"><strong>DESCRIPTION</strong></td>
											<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DEBIT</strong></td>
											<td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td>
											<td bgcolor="#E5E5E5" width='10%'><strong>BALANCE</strong></td>
										</tr>
										<?php
										//load the Codes for the Bebit side
										$rstrans=@mysqli_query($con, "SELECT t.* from transtb t WHERE t.transdate between '{$from}' and '{$to}' and t.folio_code='{$innercode['folio_code']}' and t.transtype in ('Debit','Credit') order by t.transdate") or die( mysqli_error($con));
										$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
										$debittotal=0;
										$credittotal=0; $b_lance = 0;
										while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
										{
											++$sn;
											if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
											$transdate=@$rs_trans['transdate'];
											$folio_code2=@$rs_trans['folio_code'];
											$reciptno = $rs_trans['receiptno'];
											
											$pyr=date('Y', strtotime($from));

											if($rs_trans['transtype'] == 'Debit') {

												$amount = $rs_trans['amount'];
												if(stripos($rs_trans['pvno'], "JV")) $transtype="JOURNAL";
												else $transtype="PAYMENT";
												$payeen=$rs_trans['payee'];
												if($rs_trans['pvno']!=''){
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "vouchertb", "pvno_paid", $rs_trans['pvno'], " AND YEAR(date_paid) = '{$pyr}'");
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "journalno", $rs_trans['pvno'], " AND YEAR(journal_date) = '{$pyr}'");
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "pvno", $rs_trans['pvno'], " AND YEAR(journal_date) = '{$pyr}'");
												}
												if ($rs_trans['transtype']=='Debit' ){
													$b_lance = $b_lance + $amount;
												}

												$credittotal += $rs_trans['amount'];
												echo"<tr class='$rowclass'>
												<td width='5%'>$sn</td>
												<td>".date('d-M-Y',strtotime($transdate))."</td>
												<td>$transtype</td><td>".$payeen."</td><td>$folio_code2</td><td>$reciptno $rs_trans[pvno]</td width='10%'>
												<td>".@get_folio_name($rs_trans['folio_code'])."</td>
												<td width='10%'>".number_format($rs_trans['amount'], 2)."</td>
												<td></td>
												<td width='10%'>".number_format($b_lance, 2)."</td></tr>";

											}elseif($rs_trans['transtype'] == 'Credit') {

												$amount = $rs_trans['amount'];
												if ($rs_trans['transtype']=='Credit')
												{$b_lance = $b_lance - $amount;}

												/*$pvno =  explode("/", $rs_trans['pvno']);
												$pvns =  substr($pvno[0], -1, 2); $pvns2 = substr($pvno[0], 2, 2);
												if ($pvns=='JV' or $pvns2 == 'JV'){
												$transtype="JOURNAL"; } else { $transtype="RECIEPT"; }
												*/
												if(stripos($rs_trans['pvno'], "JV")) $transtype="JOURNAL";
												else $transtype="RECIEPT";
												$payeen=$rs_trans['payee'];
												if($rs_trans['pvno']!=''){
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "vouchertb", "pvno_paid", $rs_trans['pvno'], " AND YEAR(date_paid) = '{$pyr}'");
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "journalno", $rs_trans['pvno'], " AND YEAR(journal_date) = '{$pyr}'");
													if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "pvno", $rs_trans['pvno'], " AND YEAR(journal_date) = '{$pyr}'");
												}
												$debittotal += $rs_trans['amount'];
												echo"<tr class='$rowclass'><td width='5%'>$sn</td>
												<td>".date('d-M-Y',strtotime($transdate))."</td>
												<td>$transtype</td><td>".$payeen."</td><td>$folio_code2</td><td>$reciptno $rs_trans[pvno]</td>
												<td>".@get_folio_name($rs_trans['folio_code'])."</td>
												<td width='10%'></td>
												<td width='10%'>".number_format($rs_trans['amount'], 2)."</td>
												<td width='10%'>".number_format($b_lance, 2)."</td></tr>";
											}
										}
										?>
										<tr>
											<td colspan="7" align="right">Total : </td>
											<td><b><?php echo number_format($credittotal, 2); ?></b></td>
											<td><b><?php echo number_format($debittotal, 2); ?></b></td>
											<td><b><?php
											$bal = $debittotal - $credittotal;
											if($bal < 0) echo number_format(abs($b_lance), 2);
											else echo number_format($b_lance, 2);
											?></b></td>
										</tr>
										<!-- End of Summary of Records for Debit -->
									</table><p>&nbsp;</p>
								</center>
								<?php
								echo "</li>";
							}//end while
							echo "</ol>";
			}

			else {
				echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
			}
			?>
			</td>
			<!-- End of Debit Column-->
			<!-- Credit Column-->      <!-- End of Credit Column-->
			</tr>
			<?php
			//}// end of while for monthyear
		}// end of monthyear found i.e if(num_row>=1)
		else
		{
			echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
		}
		?>
		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" />
			</th>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" />
			</th>
		</tr>
		</table>
		<?php
	}//end of ledger
if($option=='ledger3')
{
	?>
	<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
	<tr>
	<td height="27" colspan="2" align="center" bgcolor="#FFCCFF"><p><strong>INDIVIDUAL GENERAL LEDGER</strong></p></td>
	<tr>
	<td colspan="2" align="center" bgcolor="#E0E0E0"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td>
	</tr>
	<?php
	$folio_code = $_REQUEST['folio_code'];
	//////////////////////////////////////////
	$sqll="SELECT distinct concat(monthname(transdate), concat(', ',year(transdate))) as monthyear, month(transdate) as m, year(transdate) as y from transtb where transdate between '$from' and '$to' order by month(transdate)";
	$rsmonth=@mysqli_query($con, $sqll);
	if(@mysqli_num_rows($rsmonth)>=1)
	{
		while($rs_month=@mysqli_fetch_array($rsmonth))
		{
			$monthyear=@$rs_month['monthyear'];
			$month=@$rs_month['m'];
			$year=@$rs_month['y'];
			//echo "<tr><td colspan='2' align='center'><h3>$monthyear</h3></td></tr>";
		}
		?>
		<tr>
		<!-- Debit Column-->
		<td colspan="2" valign="top">
		<?php

		$sqll = "SELECT distinct f.title, t.folio_code from transtb t INNER JOIN foliotb f ON f.folio_code = t.folio_code and t.folio_code in ('$folio_code') WHERE transdate between '$from' and '$to' and t.transtype in ('Debit','Credit') order by t.folio_code";
		$msql= mysqli_query($con, $sqll) or die( mysqli_error($con));
		if( mysqli_num_rows($msql) > 0){
			$isn=0;echo "<ol>";
			while($innercode= mysqli_fetch_array($msql, 3 )){
				echo "<li><span style='font-size: 16px'>","Account Code: ", $innercode['folio_code'], " - Account Name: ", $innercode['title'], " <p>Number of Items = ";
				$c= mysqli_query($con, "SELECT * FROM transtb where transdate between '$from' and '$to' and folio_code='".$innercode['folio_code']."'");
				echo  mysqli_num_rows($c), "</p></span>";
				?>
				<center>
				<table width="90%" align="center">
				<tr>
				<td bgcolor="#E5E5E5" width='5%'>S/N</td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DATE OF ENTRY</strong></td>
				<td height="24" bgcolor="#E5E5E5"><strong>TRANSACTION</strong></td>
				<td height="24" bgcolor="#E5E5E5"><strong>NAME</strong></td>
				<td height="24" bgcolor="#E5E5E5"><strong>FOLIO CODE</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>REC./PV. No.</strong></td>
				<td height="24" bgcolor="#E5E5E5"><strong>DESCRIPTION</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DEBIT</strong></td>
				<td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td>
				<td bgcolor="#E5E5E5" width='10%'><strong>BALANCE</strong></td>
				</tr>
				<?php
				//load the Codes for the Bebit side
				$rstrans=@mysqli_query($con, "select t.* from transtb t WHERE transdate between '$from' and '$to' and folio_code='$innercode[folio_code]' and t.transtype in ('Debit','Credit') order by t.transdate") or die( mysqli_error($con));
				$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
				$debittotal=0;
				$credittotal=0; $b_lance = 0;
				while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
				{
					++$sn;
					if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
					$transdate=@$rs_trans['transdate'];
					$folio_code2=@$rs_trans['folio_code'];
					$reciptno = $rs_trans['receiptno'];
					if($rs_trans['transtype'] == 'Debit') {

						$amount = $rs_trans['amount'];
					if(stripos($rs_trans['pvno'], "JV")) $transtype="JOURNAL";
					else $transtype="PAYMENT";
					$payeen=$rs_trans['payee'];
					if($rs_trans['pvno']!=''){
						if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "vouchertb", "pvno_paid", $rs_trans['pvno']);
						if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "journalno", $rs_trans['pvno']);
						if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "pvno", $rs_trans['pvno']);
					}
					if ($rs_trans['transtype']=='Debit' ){
						$b_lance = $b_lance + $amount;}

						$credittotal += $rs_trans['amount'];
						echo"<tr class='$rowclass'>
						<td width='5%'>$sn</td>
						<td>".date('d-M-Y',strtotime($transdate))."</td>
						<td>$transtype</td><td>".$payeen."</td><td>$folio_code2</td><td>$reciptno $rs_trans[pvno]</td width='10%'>
						<td>".@get_folio_name($rs_trans['folio_code'])."</td>
						<td width='10%'>".number_format($rs_trans['amount'], 2)."</td>
						<td></td>
						<td width='10%'>".number_format($b_lance, 2)."</td></tr>";

					}elseif($rs_trans['transtype'] == 'Credit') {

						$amount = $rs_trans['amount'];
						if ($rs_trans['transtype']=='Credit')
						{$b_lance = $b_lance - $amount;}

						if(stripos($rs_trans['pvno'], "JV")) $transtype="JOURNAL";
						else $transtype="RECIEPT";
						$payeen=$rs_trans['payee'];
						if($rs_trans['pvno']!=''){
							if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "vouchertb", "pvno_paid", $rs_trans['pvno']);
							if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "journalno", $rs_trans['pvno']);
							if($payeen=='') $payeen=$bursary->get_any_value("payee_name", "journaltb", "pvno", $rs_trans['pvno']);
						}
						$debittotal += $rs_trans['amount'];

						echo"<tr class='$rowclass'><td width='5%'>$sn</td>
						<td>".date('d-M-Y',strtotime($transdate))."</td>
						<td>$transtype</td><td>".$payeen."</td><td>$folio_code2</td><td>$reciptno $rs_trans[pvno]</td>
						<td>".@get_folio_name($rs_trans['folio_code'])."</td>
						<td width='10%'></td>
						<td width='10%'>".number_format($rs_trans['amount'], 2)."</td>
						<td width='10%'>".number_format($b_lance, 2)."</td></tr>";
					}
				}
				?>
				<tr>
				<td colspan="7" align="right">Total : </td>
				<td><b><?php echo number_format($credittotal, 2); ?></b></td>
				<td><b><?php echo number_format($debittotal, 2); ?></b></td>
				<td><b><?php
				$bal = $debittotal - $credittotal;
				if($bal < 0) echo number_format(abs($b_lance), 2);
				else echo number_format($b_lance, 2);
				?></b></td>
				</tr>
				<!-- End of Summary of Records for Debit -->
				</table><p>&nbsp;</p>
				</center>
				<?php
				echo "</li>";
			}//end while
			echo "</ol>";
		}else {
			echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
		}
		?>
		</td>
		<!-- End of Debit Column-->
		<!-- Credit Column-->      <!-- End of Credit Column-->
		</tr>
		<?php
		//}// end of while for monthyear
	}// end of monthyear found i.e if(num_row>=1)
	else
	{
		echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
	}
	?>
	<tr>
	<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" />
	</th>
	<td><input type="button" class="row-b" value="Close" onclick="window.close()" />
	</th>
	</tr>
	</table>
	<?php
}//end of ledger
if($option=='summarytrialbalanceledger')
{
	$tyear = date('Y', strtotime($from));
		?>
		<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
		<td height="27" colspan="2" align="center" bgcolor="#FFCCFF"><p><strong>SUMMARY TRIBALANCE GENERAL LEDGER</strong></p></td>
		<tr>
		<td colspan="2" align="center" bgcolor="#E0E0E0"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td>
		</tr>
		<?php

		//$rsmonth=@mysqli_query($con, "SELECT distinct concat(monthname(transdate), concat(', ',year(transdate))) as monthyear, month(transdate) as m, year(transdate) as y from transtb where transdate between '{$from}' and '{$to}' order by transdate");
		$sqlFolio = "SELECT DISTINCT fundcenter, itemcode, f.title FROM trialbalancetb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transyear = '{$tyear}' AND t.transtype = 'Trans' ORDER BY fundcenter, itemcode";
		$rsmonth=@mysqli_query($con, $sqlFolio);
		if(@mysqli_num_rows($rsmonth)>=1)
		{
			/*while($rs_month=@mysqli_fetch_array($rsmonth))
			{
				$monthyear=@$rs_month['monthyear'];
				$month=@$rs_month['m'];
				$year=@$rs_month['y'];
			}
			$sqll3s = "SELECT * from stranstb order by itemcode ";
			$msql3s= mysqli_query($con, $sqll3s) or die( mysqli_error($con));
			$accs = array();
			while($accodes = mysqli_fetch_array($msql3s, 3 )){
				echo $ac_codes = $accs[] = $accodes['itemcode'];

			}*/
			while($rs_transa = mysqli_fetch_array($rsmonth, 3)){
				$fund = str_pad( $rs_transa['fundcenter'], 2, "0", STR_PAD_LEFT );
				$item = $rs_transa['itemcode'];
				
				$foliocode = $rs_transa['fundcenter']."-XXX-".$rs_transa['itemcode'];
				$ftit = $rs_transa['title'];
				$folio_title = $ftit; 

				$sq = "SELECT DISTINCT deptcode FROM foliotb WHERE fundcenter='{$fund}' AND itemcode='{$item}' AND title='".mysqli_real_escape_string($con, $ftit)."'";
				$qq = mysqli_query($con, $sq);
				$dString = "'M' ";
				while($sr = mysqli_fetch_array($qq, 3)){
					$fcode = $fund."-".$sr['deptcode']."-".$item;
					$dString .= ", '{$fcode}'";
				}

				$c= mysqli_query($con, "SELECT * FROM transtb where YEAR(transdate) = '{$tyear}' AND folio_code IN  ({$dString})");
				if ( mysqli_num_rows($c) > 0) {
					$icsx++;

					?>
					<tr>
					<!-- Debit Column-->
					<td colspan="2" valign="top">
					<?php
					echo $icsx. " <span style='font-size: 16px'>","Account Code: ", $foliocode, " - Account Name: ", $folio_title, " <p>Number of Items = ";
					echo  mysqli_num_rows($c), "</p></span>";


					?>
					<center>
					<table width="90%" align="center">
					<tr>
					<td bgcolor="#E5E5E5" width='5%'>S/N</td>
					<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DATE OF ENTRY</strong></td>
					<td height="24" bgcolor="#E5E5E5"><strong>TRANSACTION</strong></td>
					<td height="24" bgcolor="#E5E5E5"><strong>FOLIO CODE</strong></td>
					<td height="24" bgcolor="#E5E5E5" width='10%'><strong>REC./PV. No.</strong></td>
					<td height="24" bgcolor="#E5E5E5"><strong>DESCRIPTION</strong></td>
					<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DEBIT</strong></td>
					<td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td>
					<td bgcolor="#E5E5E5" width='10%'><strong>BALANCE</strong></td>
					</tr>
					<?php
					$sn=0;
					$debittotal=0;
					$credittotal=0; $b_lance = 0;
					while($rs_trans=@mysqli_fetch_array($c, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						$transdate=@$rs_trans['transdate'];
						$folio_code_u=@$rs_trans['folio_code'];

						$reciptno = $rs_trans['receiptno'];
						if($rs_trans['transtype'] == 'Debit') {

							$amount = $rs_trans['amount'];
							if(stripos($rs_trans['pvno'], "JV")) $transtype="JOURNAL";
							else $transtype="PAYMENT";
							$b_lance = $b_lance + $amount;

							$credittotal += $rs_trans['amount'];
							echo"<tr class='$rowclass'>
							<td width='5%'>$sn</td>
							<td>".date('d-M-Y',strtotime($transdate))."</td>
							<td>$transtype</td><td>$folio_code_u</td><td>$reciptno $rs_trans[pvno]</td width='10%'>
							<td>".@get_folio_name($rs_trans['folio_code'])."</td>
							<td width='10%'>".number_format($rs_trans['amount'], 2)."</td>
							<td></td>
							<td width='10%'>".number_format($b_lance, 2)."</td></tr>";

						}elseif($rs_trans['transtype'] == 'Credit') {

							$amount = $rs_trans['amount'];
							$b_lance = $b_lance - $amount;
							if(stripos($rs_trans['pvno'], "JV")) $transtype="JOURNAL";
							else $transtype="RECIEPT";
							$debittotal += $rs_trans['amount'];
							echo"<tr class='$rowclass'><td width='5%'>$sn</td>
							<td>".date('d-M-Y',strtotime($transdate))."</td>
							<td>$transtype</td><td>$folio_code_u</td><td>$reciptno $rs_trans[pvno]</td>
							<td>".@get_folio_name($rs_trans['folio_code'])."</td>
							<td width='10%'></td>
							<td width='10%'>".number_format($rs_trans['amount'], 2)."</td>
							<td width='10%'>".number_format($b_lance, 2)."</td></tr>";
						}
					}
					?>
					<tr>
					<td colspan="6" align="right">Total : </td>
					<td><b><?php echo number_format($credittotal, 2); ?></b></td>
					<td><b><?php echo number_format($debittotal, 2); ?></b></td>
					<td><b><?php
					$bal = $debittotal - $credittotal;
					if($bal < 0) echo number_format(abs($b_lance), 2);
					else echo number_format($b_lance, 2);
					?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->
					</table><p>&nbsp;</p>
					</center>
					<?php
					
				}
			}
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
	<?php
}//end of ledger

///////////////////////////////////////////////////////////////
if($option=='ExpenditureAnalysis')
{

	?>

	<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
	<tr>
	<td height="27" colspan="2" align="center" bgcolor="#66CC99"><h2><strong>EXPENDITURE ANALYSIS <br><?="{$acctname} ($account)";?></strong></h2></td>
	<tr>
	<td colspan="2" align="center" bgcolor="#E0E0E0"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td>
	</tr>

	<tr>
	<!-- Debit Column-->
	<td colspan="2" valign="top">
	<?php
	$reportTitle = "<strong>Date Period : ".date('F, Y',strtotime($_REQUEST['from']))." to ".date('F, Y',strtotime($_REQUEST['to']))."</strong>";
	$account=@$_REQUEST['account'];
	$tyear = date('Y', strtotime($_REQUEST['from']));
	if($account != ''){
		{
			$rsmonth=@mysqli_query($con, "SELECT * from transtb_final WHERE transdate between '$from' and '$to' AND acctcode='{$account}'");
			if(mysqli_num_rows($rsmonth_a)>0){
				$table = "transtb_final";
			}else{
				$table = "transtb";
				$rsmonth=@mysqli_query($con, "SELECT * from transtb WHERE transdate between '$from' and '$to' AND acctcode='{$account}'");
			}
			
			if(@mysqli_num_rows($rsmonth) >= 1)
			$sn=0;
			{
				//transtb_final
				?>
				<tr>
				<!-- Debit Column-->
				<td colspan="2" valign="top">
				<?php echo "<p style='font-size:14px'>TOTAL NUMBER OF TRANSACTIONS: ". mysqli_num_rows($rsmonth)."</p>"; 
			}?>
			<center>

				<table width="90%" align="center">
				<tr>
				<td bgcolor="#E5E5E5" width='5%'><strong>S/N</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>ACCOUNT CODE</strong></td>
				<td height="24" bgcolor="#E5E5E5"><strong>ACCOUNT NAME</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DEBIT</strong></td>
				<td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td>
				<td bgcolor="#E5E5E5" width='10%'><strong>BALANCE</strong></td>
				</tr>
				<?php
				$credittotal=0;	$debittotal=0;	$accounts=array();
				$dFrom = date('Y',strtotime($from))."-01-02";
				$aFrom = date('Y',strtotime($from))."-01-01";
				$sqll2 = "SELECT distinct t.folio_code FROM {$table} t INNER JOIN bank_accounttb b ON t.folio_code=b.acctcode WHERE t.transdate = '{$aFrom}' AND t.acctcode='{$account}'"; 
				$msql2= mysqli_query($con, $sqll2) or die( mysqli_error($con));
				$act_string="'X'";
				if( mysqli_num_rows($msql2) > 0){
					while($innercode= mysqli_fetch_array($msql2, 3 )){
						$sn++;
						$folio_code =  $innercode['folio_code'];
						$accounts[] = $folio_code;
						$act_string .= ", '{$folio_code}' ";
						//load the Codes for the Bebit side
						
						$sqll="SELECT t.*, f.title, f.exp from {$table} t INNER JOIN foliotb f ON t.folio_code=f.folio_code  WHERE t.transdate = '{$aFrom}' and t.folio_code='{$folio_code}' AND t.acctcode='{$account}' order by t.transdate";

						$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));

						$amt=0; $b_lance = 0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
						{
							
							$f_tid=$rs_trans['id'];
							$ftit=$rs_trans['title'];
							$real=$rs_trans['exp'];
							$folio_title = $ftit."(".$rs_trans['folio_code'].")";
							if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
							$transdate=@$rs_trans['transdate'];
							$amt = $rs_trans['amount'];
							$b_lance = $b_lance + $rs_trans['amount'];
						}

						echo "<tr class='{$rowclass}'><td>$sn</td><td>{$folio_code}</td><td>{$folio_title} <b>[Opening Balance]</b></td>";

						if ($b_lance < 0) //(($real == 'Expenses') or ($real == 'Assets'))
						{
							$dr = $amt; $cr='0.0';
							$nums =  $b_lance;
							$debittotal = $debittotal + $amt  ;
							echo "<td><b>".number_format($amt, 2)."</b></td><td></td>";
							}

							else {
								$dr = '0.0'; $cr=$amt;
								$nums =  $b_lance;

								echo "<td></td><td><b>".number_format($amt, 2)."</b></td>";
								$credittotal = $credittotal + $amt ;
						}
						echo "<td><b>".number_format($b_lance, 2)."</b></td>";
						echo "</tr>";
					}
				}

				$cred=0; $debt=0; $bal=0;
				$sqll2a = "SELECT DISTINCT LEFT(t.folio_code, 2) AS fund, RIGHT(t.folio_code, 4) AS item, f.title from {$table} t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$dFrom}' and '{$to}' AND t.acctcode='{$account}'";
				$rstransa=@mysqli_query($con, $sqll2a) or die( mysqli_error($con));
				while($rs_transa=@mysqli_fetch_array($rstransa, 3 )){
					$fund_string=$rs_transa['fund'];
					$item_string=$rs_transa['item'];
					$sn++;
					$folio_code = $fund_string."-XXX-".$item_string; //$rs_transa['folio_code'];
					$ftit = $rs_transa['title'];
					$folio_title = $ftit;//." (".$folio_code.")";
					echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$folio_title."</td>";
					$sqllx = mysqli_query($con, "SELECT sum(t.amount) AS amount from {$table} t WHERE t.transdate between '{$dFrom}' and '{$to}' AND (t.folio_code LIKE '{$fund_string}-%' AND t.folio_code LIKE '%-{$item_string}') AND t.acctcode='{$account}' AND t.transtype = 'Credit'") or die( mysqli_error($con));
					$rstransx=mysqli_fetch_array($sqllx, 3);
					$cred = $rstransx['amount'];
					$b_lance += $cred;

					$sqlly = mysqli_query($con, "SELECT sum(t.amount) AS amount from {$table} t WHERE t.transdate between '{$dFrom}' and '{$to}' AND (t.folio_code LIKE '{$fund_string}-%' AND t.folio_code LIKE '%-{$item_string}') AND t.acctcode='{$account}' AND t.transtype = 'Debit'") or die( mysqli_error($con));
					$rstransy=mysqli_fetch_array($sqlly, 3);
					$debt = $rstransy['amount'];
					$b_lance -= $debt;

					$bal = $cred - $debt;
					if($bal < 0){
						$cr = '0.0'; $dr=abs($bal);
						echo "<td>".number_format(abs($bal), 2)."</td>";
						echo "<td></td>";
						//echo "</tr>";
						$debittotal = $debittotal + abs($bal);
					}else{
						$cr = abs($bal); $dr='0.0';
						echo "<td></td>";
						echo "<td>".number_format(abs($bal), 2)."</td>";
						//echo "</tr>";
						$credittotal = $credittotal + abs($bal);
					}
					echo "<td>".number_format($b_lance, 2)."</td>";
					echo "</tr>";
				}

				/*$cred=0; $debt=0; $bal=0;
				$sqll2a = "SELECT DISTINCT t.folio_code, f.title from {$table} t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$from}' and '{$to}' AND t.folio_code not in ({$act_string})";
				$rstransa=@mysqli_query($con, $sqll2a) or die( mysqli_error($con));
				while($rs_transa=@mysqli_fetch_array($rstransa, 3 )){
					$sn++;
					$folio_code = $rs_transa['folio_code'];
					$ftit = $rs_transa['title'];
					$folio_title = $ftit." (".$folio_code.")";
					echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$folio_title."</td>";
					$sqllx = mysqli_query($con, "SELECT sum(t.amount) AS amount from {$table} t WHERE t.transdate between '{$from}' and '{$to}' AND t.folio_code = '{$folio_code}' AND t.transtype = 'Credit'") or die( mysqli_error($con));
					$rstransx=mysqli_fetch_array($sqllx, 3);
					$cred = $rstransx['amount'];
					$b_lance += $cred;

					$sqlly = mysqli_query($con, "SELECT sum(t.amount) AS amount from {$table} t WHERE t.transdate between '{$from}' and '{$to}' AND t.folio_code = '{$folio_code}' AND t.transtype = 'Debit'") or die( mysqli_error($con));
					$rstransy=mysqli_fetch_array($sqlly, 3);
					$debt = $rstransy['amount'];
					$b_lance -= $debt;

					$bal = $cred - $debt;
					if($bal < 0){
						$cr = '0.0'; $dr=abs($bal);
						echo "<td>".number_format(abs($bal), 2)."</td>";
						echo "<td></td>";
						//echo "</tr>";
						$credittotal = $credittotal + abs($bal);
					}else{
						$cr = abs($bal); $dr='0.0';
						echo "<td></td>";
						echo "<td>".number_format(abs($bal), 2)."</td>";
						//echo "</tr>";
						$debittotal = $debittotal + abs($bal);
					}
					echo "<td>".number_format($b_lance, 2)."</td>";
					echo "</tr>";
				}*/
				
				?>
				<tr>
				<td colspan="3" align="right">Total : </td>
				<td><b><?php echo number_format(($credittotal), 2); ?></b></td>
				<td><b><?php echo number_format($debittotal, 2); ?></b></td>
				<td><b><?php echo number_format($b_lance, 2); ?></b></td>
				</tr>
				<!-- End of Summary of Records for Debit -->
				</table><p>&nbsp;</p>
			</center>
			<?php

		}
	}
	else {
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
	<?php

}


///////////////////////////////////////////////////////////////
if($option=='trialbalance')
{

	?>

	<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
	<tr>
	<td height="27" colspan="2" align="center" bgcolor="#66CC99"><p><strong>Trial Balance</strong></p></td>
	<tr>
	<td colspan="2" align="center" bgcolor="#E0E0E0"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td>
	</tr>

	<tr>
	<!-- Debit Column-->
	<td colspan="2" valign="top">
	<?php
	$reportTitle = "<strong>Date Period : ".date('F, Y',strtotime($_REQUEST['from']))." to ".date('F, Y',strtotime($_REQUEST['to']))."</strong>";
	$account=@$_REQUEST['account'];
	$tyear = date('Y', strtotime($_REQUEST['from']));
	if($account == '---' || $account == '' || $account == Null){
		{

			$rsmonth=@mysqli_query($con, "SELECT * from transtb WHERE transdate between '{$from}' and '{$to}'");
			if(@mysqli_num_rows($rsmonth)>=1)
			$sn=0;
			{
				if(isset($_POST['vs_opt6']) && $_POST['vs_opt6']=="savetrialbalance"){
					@mysqli_query($con, "DELETE FROM trialbalancetb WHERE transyear='{$tyear}'");
					@mysqli_query($con, "DELETE FROM transtb_final WHERE year(transdate)='{$tyear}'");
				}
				?>
				<tr>
				<!-- Debit Column-->
				<td colspan="2" valign="top">
				<?php echo "<p style='font-size:14px'>TOTAL NUMBER OF TRANSACTIONS: ". mysqli_num_rows($rsmonth)."</p>"; }?>
				<center>

				<table width="90%" align="center">
				<tr>
				<td bgcolor="#E5E5E5" width='5%'><strong>S/N</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>ACCOUNT CODE</strong></td>
				<td height="24" bgcolor="#E5E5E5"><strong>ACCOUNT NAME</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DEBIT</strong></td>
				<td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td>
				</tr>
				<?php
				$credittotal=0;	$debittotal=0;	$accounts=array();
				$dFrom = date('Y',strtotime($from))."-01-02";
				$aFrom = date('Y',strtotime($from))."-01-01";
				$sqll2 = "SELECT distinct t.folio_code FROM transtb t INNER JOIN bank_accounttb b ON t.folio_code=b.acctcode WHERE t.transdate = '{$aFrom}'";
				$msql2= mysqli_query($con, $sqll2) or die( mysqli_error($con));
				$act_string="'X'";
				if( mysqli_num_rows($msql2) > 0){
					while($innercode= mysqli_fetch_array($msql2, 3 )){
						$sn++;
						$folio_code =  $innercode['folio_code'];
						$accounts[] = $folio_code;
						$act_string .= ", '{$folio_code}' ";
						//load the Codes for the Bebit side
						
						$sqll="SELECT t.*, f.title, f.exp from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code  WHERE t.transdate = '{$aFrom}' and t.folio_code='{$folio_code}' order by t.transdate";

						$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));

						$amt=0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
						{
							$b_lance = 0;
							$f_tid=$rs_trans['id'];
							$ftit=$rs_trans['title'];
							$real=$rs_trans['exp'];
							$folio_title = $ftit."(".$rs_trans['folio_code'].")";
							if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
							$transdate=@$rs_trans['transdate'];
							$amt = $rs_trans['amount'];
							
							$sqllx = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$dFrom}' and '{$to}' AND t.acctcode = '{$folio_code}' AND t.transtype = 'Credit'") or die( mysqli_error($con));
							$rstransx=mysqli_fetch_array($sqllx, 3);
							$cred = $rstransx['amount'];
							$amt += $rstransx['amount'];

							$sqlly = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$dFrom}' and '{$to}' AND t.acctcode = '{$folio_code}' AND t.transtype = 'Debit'") or die( mysqli_error($con));
							$rstransy=mysqli_fetch_array($sqlly, 3);
							$debt = $rstransy['amount'];
							$amt -= $rstransy['amount'];

							if($rs_trans['transtype'] == 'Credit') {
								$b_lance = $b_lance - $rs_trans['amount'];
							}
							elseif($rs_trans['transtype'] == 'Debit') {

								$b_lance = $b_lance + $rs_trans['amount'];
							}
							$b = substr($b_lance, 0, 1);
							if(isset($_POST['vs_opt6']) && $_POST['vs_opt6']=="savetrialbalance"){
								mysqli_query($con, "INSERT INTO `transtb_final` (`fileno`, `dept_acctcode`, `acctcode`, `folio_code`, `transtype`, `transdate`, `entry_date`, `entry_time`, `amount`, `payee`, `chequeno`, `receiptno`, `pvno`, `comment`, `entry_by`, `paybatch`, `purchase_advance`, `rev_code`)  SELECT `fileno`, `dept_acctcode`, `acctcode`, `folio_code`, `transtype`, `transdate`, `entry_date`, `entry_time`, `amount`, `payee`, `chequeno`, `receiptno`, `pvno`, `comment`, `entry_by`, `paybatch`, `purchase_advance`, `rev_code` FROM transtb WHERE id={$f_tid} LIMIT 1");
							}
						}

						echo "<tr class='{$rowclass}'><td>$sn</td><td>{$folio_code}</td><td>{$folio_title}</td>";

						//TRIAL-BALANCE ADJUSTMENT ON BANK BALANCES
						$mbal=mysqli_query($con, "SELECT * FROM trialbalance_adjust WHERE tyear='".date('Y',strtotime($from))."' AND ".date('m',strtotime($to))." >= tmonth AND folio_code='{$folio_code}' AND status='Active' AND transtype='Bank'");
						$mbr=mysqli_fetch_array($mbal, 3);
						if(mysqli_num_rows($mbal) > 0) {
							$real=$mbr['moveto'];
						}

						if (($real == 'Expenses') || ($real == 'Assets'))
						{
							$dr = $amt; $cr='0.0';
							$nums =  $b_lance;
							$credittotal = $credittotal + $amt ;
							echo "<td>".number_format($amt, 2)."</td><td></td>";
							}

							else {
								$dr = '0.0'; $cr=$amt;
								$nums =  $b_lance;

								echo "<td></td><td>".number_format($amt, 2)."</td>";
								$debittotal = $debittotal + $amt  ;
						}
						
						$tyear = date('Y', strtotime($from));
						if(isset($_POST['vs_opt6']) && $_POST['vs_opt6']=="savetrialbalance"){
							mysqli_query($con, "INSERT INTO trialbalancetb SET folio_code='{$folio_code}', title='".mysqli_real_escape_string($con, $ftit)."', debit='{$dr}', credit='{$cr}', transyear='{$tyear}', datecreated=now(), entryby='{$_SESSION['login_id']}', reporttitle='".mysqli_real_escape_string($con, $reportTitle)."', transtype='Bank'");
						}
						echo "</tr>";
					}
				}
				$act_string2 = $act_string;
				//________________________________________END OF BANKS BALANCES__________________________________________
				$sqll2 = "SELECT distinct t.folio_code FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate = '{$aFrom}' AND t.folio_code NOT IN ({$act_string2})"; 
				$msql2= mysqli_query($con, $sqll2) or die( mysqli_error($con));
				if( mysqli_num_rows($msql2) > 0){
					while($innercode= mysqli_fetch_array($msql2, 3 )){
						++$sn;
						$folio_code =  $innercode['folio_code'];
						$accounts[] = $folio_code;
						$act_string .= ", '{$folio_code}' ";
						//load the Codes for the Bebit side
						
						$sqll="SELECT t.*, f.title, f.exp from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code  WHERE t.transdate = '{$aFrom}' and t.folio_code='{$folio_code}' order by t.transdate";
						
						$crd=0; $drd=0;
						$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));

						$amt=0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
						{
							$b_lance = 0;
							$f_tid=$rs_trans['id'];
							$ftit=$rs_trans['title'];
							$real=$rs_trans['exp'];
							$folio_title = $ftit."(".$rs_trans['folio_code'].")";
							if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
							$transdate=@$rs_trans['transdate'];
							$amt = $a = $rs_trans['amount'];

							$sqllxa = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate = '{$aFrom}' AND t.folio_code = '{$folio_code}' AND t.transtype = 'Credit'") or die( mysqli_error($con));
							$rstransxa=mysqli_fetch_array($sqllxa, 3);
							$crd = $rstransxa['amount'];

							$sqllxb = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate = '{$aFrom}' AND t.folio_code = '{$folio_code}' AND t.transtype = 'Debit'") or die( mysqli_error($con));
							$rstransxb=mysqli_fetch_array($sqllxb, 3);
							$drd = $rstransxb['amount'];

							
							$sqllx = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$dFrom}' and '{$to}' AND t.folio_code = '{$folio_code}' AND t.transtype = 'Credit'") or die( mysqli_error($con));
							$rstransx=mysqli_fetch_array($sqllx, 3);
							$cred = $crd + $rstransx['amount'];
							$amt += $rstransx['amount'];

							$sqlly = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$dFrom}' and '{$to}' AND t.folio_code = '{$folio_code}' AND t.transtype = 'Debit'") or die( mysqli_error($con));
							$rstransy=mysqli_fetch_array($sqlly, 3);
							$debt = $drd + $rstransy['amount'];
							$amt -= $rstransy['amount'];

							$amt = abs($cred-$debt);
							$amt_check = $cred-$debt;

							if($rs_trans['transtype'] == 'Credit') {
								$b_lance = $b_lance - $rs_trans['amount'];
							}
							elseif($rs_trans['transtype'] == 'Debit') {

								$b_lance = $b_lance + $rs_trans['amount'];
							}
							$b = substr($b_lance, 0, 1);
							if(isset($_POST['vs_opt6']) && $_POST['vs_opt6']=="savetrialbalance"){
								mysqli_query($con, "INSERT INTO `transtb_final` (`fileno`, `dept_acctcode`, `acctcode`, `folio_code`, `transtype`, `transdate`, `entry_date`, `entry_time`, `amount`, `payee`, `chequeno`, `receiptno`, `pvno`, `comment`, `entry_by`, `paybatch`, `purchase_advance`, `rev_code`)  SELECT `fileno`, `dept_acctcode`, `acctcode`, `folio_code`, `transtype`, `transdate`, `entry_date`, `entry_time`, `amount`, `payee`, `chequeno`, `receiptno`, `pvno`, `comment`, `entry_by`, `paybatch`, `purchase_advance`, `rev_code` FROM transtb WHERE id={$f_tid} LIMIT 1");
							}
						}

						echo "<tr class='{$rowclass}'><td>$sn</td><td>{$folio_code}</td><td>{$folio_title}</td>";
						//echo date('Y',strtotime($from)), '09-002-4047' , date('m',strtotime($from)); exit;
						//TRIAL-BALANCE ADJUSTMENT ON OTHER OPENNING BALANCES
						$sqll_adj="SELECT * FROM trialbalance_adjust WHERE tyear='".date('Y',strtotime($from))."' AND ".date('m',strtotime($to))." >= tmonth AND folio_code='{$folio_code}' AND status='Active' AND transtype='Openning'";
						$mbal=mysqli_query($con, $sqll_adj);
						$mbr=mysqli_fetch_array($mbal, 3);
						if(mysqli_num_rows($mbal) > 0) {
							$real=$mbr['moveto'];
						}
						if (($real == 'Expenses') || ($real == 'Assets'))
						{
							$dr = $amt; $cr='0.0';
							$nums =  $b_lance;
							$credittotal = $credittotal + $amt ;
							echo "<td>".number_format($amt, 2)."</td><td></td>";
							}

							else {
								$dr = '0.0'; $cr=$amt;
								$nums =  $b_lance;

								echo "<td></td><td>".number_format($amt, 2)."</td>";
								$debittotal = $debittotal + $amt  ;
						}
						$tyear = date('Y', strtotime($from));
						if(isset($_POST['vs_opt6']) && $_POST['vs_opt6']=="savetrialbalance"){
							mysqli_query($con, "INSERT INTO trialbalancetb SET folio_code='{$folio_code}', title='".mysqli_real_escape_string($con, $ftit)."', debit='{$dr}', credit='{$cr}', transyear='{$tyear}', datecreated=now(), entryby='{$_SESSION['login_id']}', reporttitle='".mysqli_real_escape_string($con, $reportTitle)."', transtype='Bank'");
						}
						echo "</tr>";
					}
				}

				//___________________________________________END OF OTHER BALANCES______________________________________
				$cred=0; $debt=0; $bal=0;
				//echo $act_string; exit;
				$all_acct=$act_string;
				$sqll2a = "SELECT DISTINCT t.folio_code, f.title from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$from}' and '{$to}' AND t.folio_code not in ({$act_string})";
				$rstransa=@mysqli_query($con, $sqll2a) or die( mysqli_error($con));
				while($rs_transa=@mysqli_fetch_array($rstransa, 3 )){
					$sn++;
					$folio_code = $rs_transa['folio_code'];
					$all_acct .= ", '{$folio_code}' ";
					$ftit = $rs_transa['title'];
					$folio_title = $ftit." (".$folio_code.")";
					echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$folio_title."</td>";
					$sqllx = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$from}' and '{$to}' AND t.folio_code = '{$folio_code}' AND t.transtype = 'Credit'") or die( mysqli_error($con));
					$rstransx=mysqli_fetch_array($sqllx, 3);
					$cred = $rstransx['amount'];
					$b_lance += $cred;

					$sqlly = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$from}' and '{$to}' AND t.folio_code = '{$folio_code}' AND t.transtype = 'Debit'") or die( mysqli_error($con));
					$rstransy=mysqli_fetch_array($sqlly, 3);
					$debt = $rstransy['amount'];
					$b_lance -= $debt;

					$bal = $cred - $debt;
					if($bal < 0){
						$cr = '0.0'; $dr=abs($bal);
						echo "<td>".number_format(abs($bal), 2)."</td>";
						echo "<td></td>";
						echo "</tr>";
						$credittotal = $credittotal + abs($bal);
					}else{
						$cr = abs($bal); $dr='0.0';
						echo "<td></td>";
						echo "<td>".number_format(abs($bal), 2)."</td>";
						echo "</tr>";
						$debittotal = $debittotal + abs($bal);
					}
					if(isset($_POST['vs_opt6']) && $_POST['vs_opt6']=="savetrialbalance"){
						$iq = "SELECT * FROM transtb t WHERE t.transdate BETWEEN '{$from}' AND '{$to}' AND t.folio_code = '{$folio_code}'";
						$iqr = mysqli_query($con, $iq);	
						while($ir = mysqli_fetch_array($iqr, 3)){
							$f_tid = $ir['id'];
							mysqli_query($con, "INSERT INTO `transtb_final` (`fileno`, `dept_acctcode`, `acctcode`, `folio_code`, `transtype`, `transdate`, `entry_date`, `entry_time`, `amount`, `payee`, `chequeno`, `receiptno`, `pvno`, `comment`, `entry_by`, `paybatch`, `purchase_advance`, `rev_code`)  SELECT `fileno`, `dept_acctcode`, `acctcode`, `folio_code`, `transtype`, `transdate`, `entry_date`, `entry_time`, `amount`, `payee`, `chequeno`, `receiptno`, `pvno`, `comment`, `entry_by`, `paybatch`, `purchase_advance`, `rev_code` FROM transtb WHERE id={$f_tid} LIMIT 1");
						}
					}
					if(isset($_POST['vs_opt6']) && $_POST['vs_opt6']=="savetrialbalance"){
						mysqli_query($con, "INSERT INTO trialbalancetb SET folio_code='{$folio_code}', title='".mysqli_real_escape_string($con, $ftit)."', debit='{$dr}', credit='{$cr}', transyear='{$tyear}', datecreated=now(), entryby='{$_SESSION['login_id']}', reporttitle='".mysqli_real_escape_string($con, $reportTitle)."', transtype='Trans'");
					}
				}
				
				?>
				<tr>
				<td colspan="3" align="right">Total : </td>
				<td><b><?php echo number_format(($credittotal), 2); ?></b></td>
				<td><b><?php echo number_format($debittotal, 2); ?></b></td>
				</tr>
				<!-- End of Summary of Records for Debit -->
				</table><p>&nbsp;</p>
				</center>
				<?php
				//echo $all_acct;
		}
	}
	else {
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
	<?php

}

//////////////////////////////////////////////////////////////////
if($option=='summarytrialbalance')
{
	$tyear = date('Y', strtotime($_REQUEST['from']));
	$tsql="SELECT DISTINCT reporttitle from trialbalancetb where transyear = '{$tyear}'";
	$rsmonth=@mysqli_query($con, $tsql);
	$rt = mysqli_fetch_array($rsmonth, 3);

	?>

	<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
	<tr>
	<td height="27" colspan="2" align="center" bgcolor="#66CC99"><p><strong>Summary Trial Balance</strong></p></td>
	<tr>
	<td colspan="2" align="center" bgcolor="#E0E0E0"><?=$rt[0];?></td>
	</tr>

	<tr>
	<!-- Debit Column-->
	<td colspan="2" valign="top">
	<?php

	$account=@$_REQUEST['account'];
	if($account == '---' || $account == '' || $account == Null){
		{
			if(@mysqli_num_rows($rsmonth)>=1)
			$sn=0;
			{
				?>
				<tr>
				<!-- Debit Column-->
				<td colspan="2" valign="top">
				
				<center>

				<table width="90%" align="center">
				<tr>
				<td bgcolor="#E5E5E5" width='5%'><strong>S/N</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>ACCOUNT CODE</strong></td>
				<td height="24" bgcolor="#E5E5E5"><strong>ACCOUNT NAME</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DEBIT</strong></td>
				<td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td>
				</tr>
				<?php
				$credittotal=0;	$debittotal=0;
				$sqll2 = "SELECT * FROM trialbalancetb WHERE transyear = '{$tyear}' AND transtype='Bank'"; 
				$msql2= mysqli_query($con, $sqll2) or die( mysqli_error($con));
				if( mysqli_num_rows($msql2) > 0){
					while($rs_trans= mysqli_fetch_array($msql2, 3 )){
						$sn++;
						$folio_code =  $rs_trans['folio_code'];
						$ftit=$rs_trans['title'];
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						$rs_trans['debit']==0?$dr='':$dr = $rs_trans['debit'];
						$rs_trans['credit']==0?$cr='':$cr = $rs_trans['credit'];
						$credittotal = $credittotal + $cr ;
						$debittotal = $debittotal + $dr;
						echo "<tr class='{$rowclass}'><td>$sn</td><td>{$folio_code}</td><td>{$folio_title}</td>";
						echo "<td>".number_format($dr, 2)."</td>";
						echo "<td>".number_format($cr, 2)."</td>";
						echo "</tr>";
					}
				}
				
				$sqlFolio = "SELECT DISTINCT fundcenter, itemcode, f.title FROM trialbalancetb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transyear = '{$tyear}' AND t.transtype = 'Trans' ORDER BY fundcenter, itemcode"; 
				$folio=mysqli_query($con, $sqlFolio);
				while($rs_transa = mysqli_fetch_array($folio, 3)){
					$fund = str_pad( $rs_transa['fundcenter'], 2, "0", STR_PAD_LEFT );
					$item = $rs_transa['itemcode'];
					
					$foliocode = $rs_transa['fundcenter']."-XXX-".$rs_transa['itemcode'];
					$ftit = $rs_transa['title'];
					$folio_title = $ftit; 

					$sq = "SELECT DISTINCT deptcode FROM foliotb WHERE fundcenter='{$fund}' AND itemcode='{$item}' AND title='".mysqli_real_escape_string($con, $ftit)."'";
					$qq = mysqli_query($con, $sq);
					$dString = "'M' ";
					while($sr = mysqli_fetch_array($qq, 3)){
						$fcode = $fund."-".$sr['deptcode']."-".$item;
						$dString .= ", '{$fcode}'";
					}

					/*$folio_code = $rs_transa['folio_code'];
					$fcode = explode('-', $folio_code);
					$fund = str_pad( $fcode[0], 2, "0", STR_PAD_LEFT );
					$item = $fcode[2];
					
					$foliocode = $fund."-XXX-".$item;
					$ftit = $rs_transa['title'];
					$folio_title = $ftit; 

					$sq = "SELECT folio_code FROM foliotb WHERE transyear = '{$tyear}' AND transtype='Trans' AND title='".mysqli_real_escape_string($con, $ftit)."' AND folio_code = '{$folio_code}'";
					$qq = mysqli_query($con, $sq);
					$dString = "'M' ";
					while($sr = mysqli_fetch_array($qq, 3)){
						$dString .= ", '{$sr['folio_code']}'";
					}*/
					
					$dSQL = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM trialbalancetb WHERE transyear = '{$tyear}' AND folio_code in ({$dString}) AND transtype='Trans'";
					$msq = mysqli_query($con, $dSQL);
					if(mysqli_num_rows($msq) > 0){
						$cred=0; $debt=0; 
						$sn++;
						echo "<tr class='{$rowclass}'><td>{$sn}</td><td>{$foliocode}</td><td>{$folio_title} <a href='".$_SERVER['PHP_SELF']."?fromsummary&WHERE=".base64_encode($dString)."&TITLE=".base64_encode($dString)."&v_opt=ledger&transyear={$tyear}' title='".str_replace(array("'","M , "), '', $dString)."' target='_blank'><b>VIEW LEDGER</b></a></td>";
						while($mr=mysqli_fetch_array($msq, 3)){
							$debt += $mr['debit'];
							$cred += $mr['credit'];
							$credittotal = $credittotal + abs($cred);
							$debittotal = $debittotal + abs($debt);

							$mr['debit']==0?$debt='':$debt = number_format(abs($mr['debit']), 2);
							$mr['credit']==0?$cred='':$cred = number_format(abs($mr['credit']), 2);

							echo "<td>{$debt}</td>";
							echo "<td>{$cred}</td>";
							echo "</tr>";
						}
						
					}
				}

				/*$sqlFolio = "SELECT DISTINCT title FROM trialbalancetb WHERE transyear = '{$tyear}' AND transtype='Trans'"; 
				$folio=mysqli_query($con, $sqlFolio);
				while($rs_transa = mysqli_fetch_array($folio, 3)){
					$folio_code = $bursary->get_any_value('folio_code', 'trialbalancetb', 'title', $rs_transa['title']);
					$fcode = explode('-', $folio_code);
					$fund = str_pad( $fcode[0], 2, "0", STR_PAD_LEFT );
					$item = $fcode[2];
					
					$foliocode = $fund."-XXX-".$item;
					$ftit = $rs_transa['title'];
					$folio_title = $ftit; 

					$sq = "SELECT folio_code FROM trialbalancetb WHERE transyear = '{$tyear}' AND transtype='Trans' AND title='".mysqli_real_escape_string($con, $ftit)."'";
					$qq = mysqli_query($con, $sq);
					$dString = "'M' ";
					while($sr = mysqli_fetch_array($qq, 3)){
						$dString .= ", '{$sr['folio_code']}'";
					}
					
					$dSQL = "SELECT SUM(credit) AS credit, SUM(debit) AS debit FROM trialbalancetb WHERE transyear = '{$tyear}' AND folio_code in ({$dString}) AND title='".mysqli_real_escape_string($con, $ftit)."' AND transtype='Trans' GROUP BY title";
					$msq = mysqli_query($con, $dSQL);
					if(mysqli_num_rows($msq) > 0){
						$cred=0; $debt=0; 
						echo "<tr class='{$rowclass}'><td>{$sn}</td><td>{$foliocode}</td><td>{$folio_title} <a href='#?WHERE={$dString}' title='".str_replace(array("'","M , "), '', $dString)."'><b>VIEW LEDGER</b></a></td>";
						while($mr=mysqli_fetch_array($msq, 3)){
							$debt += $mr['debit'];
							$cred += $mr['credit'];
						}
							//$mr['debit']==0?$debt='':$debt = $mr['debit'];
							//$mr['credit']==0?$cred='':$cred = $mr['credit'];
							$bal = $cred - $debt;
							if($bal > 0){
								echo "<td></td>";
								echo "<td>".number_format(abs($bal), 2)."</td>";$credittotal = $credittotal + abs($cred);
							}else{
								echo "<td>".number_format(abs($bal), 2)."</td>";$debittotal = $debittotal + abs($debt);
								echo "<td></td>";
							}
							echo "</tr>";
							
							
							//}
						
					}
				}*/
				?>
				<tr>
				<td colspan="3" align="right">Total : </td>
				<td><b><?php echo number_format($debittotal, 2); ?></b></td>
				<td><b><?php echo number_format(($credittotal), 2); ?></b></td>
				</tr>
				<!-- End of Summary of Records for Debit -->
				</table><p>&nbsp;</p>
				</center>
				<?php
			}
		}
	}
	else {
		echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
	}

	?>
	</td>
	</tr>
	<tr>
	<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" />
	</th>
	<td><input type="button" class="row-b" value="Close" onclick="window.close()" />
	</th>
	</tr>
	</table>
	<?php
	//echo str_replace(",", "<br>", $str);
}

//////////////////////////////////////////////////////////////////
if($option=='summarytrialbalanceXXXX')
{

	?>

	<table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
	<tr>
	<td height="27" colspan="2" align="center" bgcolor="#66CC99"><p><strong>Summary Trial Balance</strong></p></td>
	<tr>
	<td colspan="2" align="center" bgcolor="#E0E0E0"><strong>Date Period : <?php echo date('F, Y',strtotime($_REQUEST['from'])); ?> to <?php echo date('F, Y',strtotime($_REQUEST['to'])); ?></strong></td>
	</tr>

	<tr>
	<!-- Debit Column-->
	<td colspan="2" valign="top">
	<?php

	$account=@$_REQUEST['account'];
	if($account == '---' || $account == '' || $account == Null){
		{

			$rsmonth=@mysqli_query($con, "SELECT * from transtb where transdate between '$from' and '$to'");
			if(@mysqli_num_rows($rsmonth)>=1)
			$sn=0;
			{
				?>
				<tr>
				<!-- Debit Column-->
				<td colspan="2" valign="top">
				<?php echo "<p style='font-size:14px'>TOTAL NUMBER OF TRANSACTIONS: ". mysqli_num_rows($rsmonth)."</p>"; }?>
				<center>

				<table width="90%" align="center">
				<tr>
				<td bgcolor="#E5E5E5" width='5%'><strong>S/N</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>ACCOUNT CODE</strong></td>
				<td height="24" bgcolor="#E5E5E5"><strong>ACCOUNT NAME</strong></td>
				<td height="24" bgcolor="#E5E5E5" width='10%'><strong>DEBIT</strong></td>
				<td bgcolor="#E5E5E5" width='10%'><strong>CREDIT</strong></td>
				</tr>
				<?php
				$credittotal=0;	$debittotal=0;	$accounts=array();
				$dFrom = date('Y',strtotime($from))."-01-02";
				$aFrom = date('Y',strtotime($from))."-01-01";
				$sqll2 = "SELECT distinct t.folio_code FROM transtb t INNER JOIN bank_accounttb b ON t.folio_code=b.acctcode WHERE t.transdate = '{$aFrom}'"; 
				$msql2= mysqli_query($con, $sqll2) or die( mysqli_error($con));
				$act_string="'X'";
				if( mysqli_num_rows($msql2) > 0){
					while($innercode= mysqli_fetch_array($msql2, 3 )){
						$sn++;
						$folio_code =  $innercode['folio_code'];
						$accounts[] = $folio_code;
						$act_string .= ", '{$folio_code}' ";
						//load the Codes for the Bebit side
						
						$sqll="SELECT t.*, f.title, f.exp from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code  WHERE t.transdate = '{$aFrom}' and t.folio_code='{$folio_code}' order by t.transdate";

						$rstrans=@mysqli_query($con, $sqll) or die( mysqli_error($con));

						$amt=0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
						{
							$b_lance = 0; 
							$ftit=$rs_trans['title'];
							$real=$rs_trans['exp'];
							$folio_title = $ftit."(".$rs_trans['folio_code'].")";
							if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
							$transdate=@$rs_trans['transdate'];
							$amt = $rs_trans['amount'];
							
							$sqllx = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t WHERE t.transdate between '{$dFrom}' and '{$to}' AND t.acctcode = '{$folio_code}' AND t.transtype = 'Credit'") or die( mysqli_error($con));
							$rstransx=mysqli_fetch_array($sqllx, 3);
							$cred = $rstransx['amount'];
							$amt += $rstransx['amount'];

							$sqlly = mysqli_query($con, "SELECT sum(t.amount) AS amount from transtb t WHERE t.transdate between '{$dFrom}' and '{$to}' AND t.acctcode = '{$folio_code}' AND t.transtype = 'Debit'") or die( mysqli_error($con));
							$rstransy=mysqli_fetch_array($sqlly, 3);
							$debt = $rstransy['amount'];
							$amt -= $rstransy['amount'];

							if($rs_trans['transtype'] == 'Credit') {
								$b_lance = $b_lance - $rs_trans['amount'];
							}
							elseif($rs_trans['transtype'] == 'Debit') {

								$b_lance = $b_lance + $rs_trans['amount'];
							}
							$b = substr($b_lance, 0, 1);
						}

						echo "<tr class='{$rowclass}'><td>$sn</td><td>{$folio_code}</td><td>{$folio_title}</td>";

						if (($real == 'Expenses') or ($real == 'Assets'))
						{

							$nums =  $b_lance;
							$credittotal = $credittotal + $amt ;
							echo "<td>".number_format($amt, 2)."</td><td></td>";
							}

							else {
								$nums =  $b_lance;

								echo "<td></td><td>".number_format($amt, 2)."</td>";
								$debittotal = $debittotal + $amt  ;
						}
						echo "</tr>";
					}
				}
				
				$cred=0; $debt=0; $bal=0; $str="'M' ";
				$sqlFolio = "SELECT DISTINCT fundcenter, itemcode, title FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.transdate between '{$from}' and '{$to}' AND t.folio_code not in ({$act_string}) ORDER BY fundcenter, itemcode";// AND (t.folio_code LIKE '{$fund}-%-{$item}')";
				$folio=mysqli_query($con, $sqlFolio);
				while($rs_transa = mysqli_fetch_array($folio, 3)){
					$fund = str_pad( $rs_transa['fundcenter'], 2, "0", STR_PAD_LEFT );
					$item = $rs_transa['itemcode'];
					
					$foliocode = $rs_transa['fundcenter']."-XXX-".$rs_transa['itemcode'];
					$ftit = $rs_transa['title'];
					$folio_title = $ftit;  //."(".$folio_code.")";

					$sq = "SELECT DISTINCT deptcode FROM foliotb WHERE fundcenter='{$fund}' AND itemcode='{$item}' AND title='".mysqli_real_escape_string($con, $ftit)."' AND folio_code not in ({$act_string})";
					$qq = mysqli_query($con, $sq);
					$dString = "'M' ";
					while($sr = mysqli_fetch_array($qq, 3)){
						/*if($sr['deptcode']!='')*/ $fcode = $fund."-".$sr['deptcode']."-".$item;
						//echo $fcode.": ".$ftit,"<br>";
						$dString .= ", '{$fcode}'";
						//$str .= ", '{$fcode}'";
					}
					//echo $dString.": ".$ftit,"<br>";
					$dSQL = "SELECT SUM(amount) AS amount, transtype FROM transtb WHERE transdate between '{$from}' and '{$to}' AND folio_code IN ({$dString}) GROUP BY transtype";
					$msq = mysqli_query($con, $dSQL);
					if(mysqli_num_rows($msq) > 0){
						$cred=0; $debt=0; 
						echo "<tr class='{$rowclass}'><td>{$sn}</td><td>{$foliocode}</td><td>{$folio_title} <a href='#?WHERE={$dString}' title='".str_replace(array("'","M , "), '', $dString)."'><b>VIEW LEDGER</b></a></td>";
						while($mr=mysqli_fetch_array($msq, 3)){
							if($mr['transtype']=="Debit"){
								$debt = $mr['amount'];
							}
							if($mr['transtype']=="Credit"){
								$cred = $mr['amount'];
							}
							$bal = $cred - $debt;
						}
							if($bal < 0){
								echo "<td>".number_format(abs($bal), 2)."</td>";
								echo "<td></td>";
								echo "</tr>";
								$credittotal = $credittotal + abs($bal);
							}else{
								echo "<td></td>";
								echo "<td>".number_format(abs($bal), 2)."</td>";
								echo "</tr>";
								$debittotal = $debittotal + abs($bal);
							}
						
					}
				}
				?>
				<tr>
				<td colspan="3" align="right">Total : </td>
				<td><b><?php echo number_format(($credittotal), 2); ?></b></td>
				<td><b><?php echo number_format($debittotal, 2); ?></b></td>
				</tr>
				<!-- End of Summary of Records for Debit -->
				</table><p>&nbsp;</p>
				</center>
				<?php

		}
	}
	else {
		echo '<h2><font color="red"><b>No record to display</b></font></h2><br><input type="button" value="Close" name="btnclose" onclick="window.close()">';
	}

	?>
	</td>
	</tr>
	<tr>
	<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" />
	</th>
	<td><input type="button" class="row-b" value="Close" onclick="window.close()" />
	</th>
	</tr>
	</table>
	<?php
	//echo str_replace(",", "<br>", $str);
}

		//////////////////////////////////////////////////////////////////
		if($option=='voucher')
		{

			$total_amount = 0;
			$tdate  = explode('/', $_REQUEST['to']);	$to = $tdate[2].'-'.str_pad($tdate[0],2,'0',STR_PAD_LEFT).'-'.str_pad($tdate[1],2,'0',STR_PAD_LEFT);
			$fdate  = explode('/', $_REQUEST['from']);	$from = $fdate[2].'-'.str_pad($fdate[0],2,'0',STR_PAD_LEFT).'-'.str_pad($fdate[1],2,'0',STR_PAD_LEFT);
			$dfr=date_create($_REQUEST['from']);	$dto=date_create($_REQUEST['to']);
			$type=$_REQUEST['paymentType'];
			if($type=="all"){

				$sql="SELECT * FROM `vouchertb` WHERE voucher_date >= '".$from."' AND voucher_date <= '".$to."' ORDER BY voucher_date desc";
				//echo $sql= "SELECT * FROM `vouchertb` where voucher_date BETWEEN '".$from."' and '".$to."'"; exit;
				$tb="<center><h2>ALL PAYMENT VOUCHER REPORT <br>FROM ".strtoupper(date_format($dfr, 'jS F, Y'))." TO ".strtoupper(date_format($dto, 'jS F, Y'))."</h2></center>";
			}
			elseif($type=="paid"){
				$sql="SELECT * FROM `vouchertb` WHERE voucher_date>='".$from."' AND voucher_date<='".$to."' AND paid_action!='' ORDER BY voucher_date desc";
				$tb="<center><h2>PAID PAYMENT VOUCHER REPORT <br>FROM ".strtoupper(date_format($dfr, 'jS F, Y'))." TO ".strtoupper(date_format($dto, 'jS F, Y'))."</h2></center>";
			}
			elseif($type=="npaid"){
				$sql="SELECT * FROM `vouchertb` WHERE voucher_date>='".$from."' AND voucher_date<='".$to."' AND paid_action='' ORDER BY voucher_date desc";
				$tb="<center><h2>UNPAID PAYMENT VOUCHER REPORT <br>FROM ".strtoupper(date_format($dfr, 'jS F, Y'))." TO ".strtoupper(date_format($dto, 'jS F, Y'))."</h2></center>";
			}
			$res_v=@mysqli_query($con, $sql);
			$sn=0;
			$tb.="<table align='center' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box' width='100%'><tr><th>S/NO</th><th>PROCESS NO</th><!--<th>PV NO</th>--><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>AMOUNT</th><th>DATE</th><th>ACTION</th></tr>";
			if(@mysqli_num_rows($res_v) >= 1)
			{
				while($rs_v=@mysqli_fetch_array($res_v))
				{
					++$sn;
					$pvno=$rs_v['pvno'];
					$pvno_paid=$rs_v['pvno_paid'];
					$amount_paid=$rs_v['amount_paid'];

					$total_amount = $total_amount + $amount_paid;
					$p=base64_encode($pvno);
					$payee_name=$rs_v['payee_name'];
					$payee_acct_no=$rs_v['payee_acct_no'];
					$payee_bank_name=$rs_v['payee_bank_name'];
					$voucher_date=$rs_v['voucher_date'];
					$tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>".number_format($amount_paid,2)."</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p' target='_blank' >VIEW</a></td></tr>";

				} //end of while
				$tb.="<tr><td></td><td></td><td colspan=2><b>Total Amount</b></td><td colspan='3'><b>".number_format($total_amount,2)."</b></td></tr>";
				$tb.="</table>"; echo $tb;
			}
			else
			echo "<font color='red'><b>No pending voucher to process</b></font>";

		} //end voucher







		///////////////////////////////////////////////////// End of Body ////////////////////////////////////////////
		//////////////////////////////////////////////////////Report Footer /////////////////////////////////////////////
		///////////////////////////////////////////////////// End of Footer ////////////////////////////////////////////
		?>
		</body>
		</html>
