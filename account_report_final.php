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
	$r=@mysqli_query($con, "SELECT * FROM bank_accounttb where acctcode='{$account}'");
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


	if($option=='note10')
	{
		?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
			<tr>
				<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 10 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
			</tr>
			<tr>
				<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
			</tr>
			<tr>
				<!-- Debit Column-->
				<td colspan="2" valign="top">
					<br>
					<table width="100%" align="center">
						<tr>
							<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
							<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
							<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
							<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
							<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
							<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
							<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
							<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
							<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
						</tr>


						<?php
						$rstrans=@mysqli_query($con, "SELECT * FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND `fundcenter` != '09' AND `itemcode` LIKE '10%' AND f.category NOT IN ('02-731D', '02-731A') AND transtype in ('Debit','Credit') order by transdate") or die( mysqli_error($con));
						$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
						//$debittotal=0; $b_lance = 0;
						$debit = 0; $credit = 0;
						while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
						{
							++$sn;
							if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
							/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
							$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
							$amount = $rs_trans['amount'];
							$transtype=@$rs_trans['transtype'];
							$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

							if ($transtype=='Debit' and $sn == 1 ){
								$b_lance = $b_lance + $credit_bf-$amount;
								$debit += $amount = $rs_trans['amount'];
							}
							elseif
							($transtype=='Debit' and $sn != 1 )
							{
								$b_lance = $b_lance-$amount;
								$debit += $amount = $rs_trans['amount'];
							}
							elseif ($transtype=='Credit' and $sn == 1 )
							{
								$b_lance = $b_lance + $credit_bf+$amount;
								$credit += $amount = $rs_trans['amount'];
							}
							elseif ($transtype=='Credit' and $sn != 1 )
							{
								$b_lance = $b_lance+$amount;
								$credit += $amount = $rs_trans['amount'];
							}
							else {
								"nothing";
							}
							$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
							$folio_title = $ftit."(".$rs_trans['folio_code'].")";

							echo"<tr class='$rowclass'><td>$sn</td>
							<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

							if ($transtype=='Credit'){
								echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
								echo	"<td>". number_format($b_lance,2)."</td></tr>";
							}

							else {
								echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
								echo	"<td>". number_format($b_lance,2)."</td></tr>";
							}




						}
						/*if($mtnum == 1)*///{
						?>
						<?php
						//}//end $mtnum
						//	}// end of while for monthyear
						?>
						<tr>
							<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
						</tr>



						<tr>
							<td></td><td></td><td><strong>Closing Balance : </strong></td>
							<td ></td>
							<td ></td>
							<td ></td>
							<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
							<td><b><?php echo number_format($b_lance, 2); ?></b></td>
						</tr>
						<!-- End of Summary of Records for Debit -->





					</table>
				</td><!-- End of Debit Column-->


				<!-- Credit Column-->
				<!-- End of Credit Column-->
			</tr>

			<?php

			//}// end of monthyear found i.e if(num_row>=1)


			?>

			<tr>
				<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
				<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
			</tr>
		</table>



		<?php
	} // end of Cashbook

	if($option=='note9')
	{
		?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
			<tr>
				<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 9 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
			</tr>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '{$ryear}' AND `fundcenter` LIKE '09' AND (`itemcode` LIKE '10%' OR `itemcode` LIKE '110%') AND f.category NOT IN ('02-731D', '02-731A') AND transtype in ('Debit','Credit') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}
					}
					?>

					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->
				</table>
			</td><!-- End of Debit Column-->

			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>
		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} // end of Cashbook

if($option=='note9b')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 9B </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php


					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND `fundcenter` LIKE '09' AND `itemcode` LIKE '10%' AND f.category IN ('02-731D') and transtype in ('Debit','Credit') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}
					}
					?>

					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->
				</table>
			</td><!-- End of Debit Column-->

			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>
		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} // end of Cashbook
if($option=='note11')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 11 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php


					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND `itemcode` LIKE '2030' and transtype in ('Debit','Credit') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}
					}
					?>

					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->
				</table>
			</td><!-- End of Debit Column-->

			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>
		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} // end of Cashbook
if($option=='note12')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 12 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php


					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND f.category='06-702C' and transtype in ('Debit','Credit') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}
					}
					?>

					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->
				</table>
			</td><!-- End of Debit Column-->

			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>
		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} // end of Cashbook


if($option=='note13')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 13 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '{$ryear}' AND `itemcode` LIKE '2%' AND `itemcode` != '2030' AND (t.folio_code NOT LIKE '%-701-2084') AND f.category NOT IN ('06-702C', 'AA-002-2C', 'AA-002-2CB') AND `fundcenter` != '99' AND deptcode != '743' AND f.`title` NOT LIKE '%Depreciation%' and transtype in ('Debit','Credit') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}




					}
					/*if($mtnum == 1)*///{
					?>
					<?php
					//}//end $mtnum
					//	}// end of while for monthyear
					?>
					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} // end of Cashbook


if($option=='note14')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 9 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' and transtype in ('Debit','Credit') AND `fundcenter` LIKE '99' AND deptcode='743' AND `itemcode` LIKE '21%' order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}




					}
					/*if($mtnum == 1)*///{
					?>
					<?php
					//}//end $mtnum
					//	}// end of while for monthyear
					?>
					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //


if($option=='note15')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 15 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' and transtype in ('Debit','Credit') AND `fundcenter` LIKE '09' AND `itemcode` LIKE '10%' AND f.category IN ('02-731A') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}




					}
					/*if($mtnum == 1)*///{
					?>
					<?php
					//}//end $mtnum
					//	}// end of while for monthyear
					?>
					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //

if($option=='note15cf')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 15 CF </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' and transtype in ('Debit','Credit') and folio_code in and receiptno != ''order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}




					}
					/*if($mtnum == 1)*///{
					?>
					<?php
					//}//end $mtnum
					//	}// end of while for monthyear
					?>
					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //

if($option=='note16')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 16 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' and transtype in ('Debit','Credit') AND f.category IN ('AA-002-2CB') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}




					}
					/*if($mtnum == 1)*///{
					?>
					<?php
					//}//end $mtnum
					//	}// end of while for monthyear
					?>
					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //

if($option=='note17')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 17 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' and transtype in ('Debit','Credit') AND f.category IN ('AA-002-2C')") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}




					}
					?>
					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //

if($option=='note23a')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 23A </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td>
						<td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
					</tr>
					<?php
					$sn=1;
					$sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '23a'";

					$msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
					$total=0;
					while($accode = mysqli_fetch_array($msql3, 3 )){

						$folio_code = $accode['folio_code'];

						$total += $accode['amount'];
						$fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
						echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";

						$sn++;
					}
					?>

					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td>
					</tr>

					<tr>
						<td></td><td></td><td><strong>Balance : </strong></td>
						<td><td><b><?php echo number_format($total, 2); ?></b></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //
if($option=='note23a2')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 23A2 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td>
					</tr>

					<?php
		$from = $ryear."-01-01";	$to = $ryear."-12-31";
		$b_lance_total = 0;	$sn++;
		$S="SELECT distinct t.acctcode FROM transtb_final t WHERE transdate BETWEEN '{$from}' AND '{$to}' AND (t.acctcode != '' AND t.acctcode != '---' AND t.acctcode IS NOT NULL AND t.acctcode != '01-001-3188' AND t.acctcode != '01-001-3131')";
		$sqq=mysqli_query($con, $S);
		while($sqs = mysqli_fetch_array($sqq, 3)){
			$account = $sqs[0];

			$sqqx="SELECT * FROM transtb_final WHERE acctcode = '{$account}' AND transdate BETWEEN '{$from}' AND '{$to}'";
			$rsmonthx=@mysqli_query($con, $sqqx) or die( mysqli_error($con));
			$mtnumx=@mysqli_num_rows($rsmonthx);
			$accounts=array();
			//echo date('m', strtotime($from))-1; exit;
			if($mtnumx > 0)
			{
				if(date('m', strtotime($from)) == 1){
					$rstrans_ox= mysqli_query($con, "SELECT sum(amount) AS total_credit FROM transtb_final WHERE transtype='Credit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}' ") or die( mysqli_error($con));

					$rstrans_oy = mysqli_query($con, "SELECT sum(amount) AS total_debit FROM transtb_final WHERE transtype='Debit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}'") or die( mysqli_error($con));

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

					$xSql = "SELECT sum(amount) AS total_credit FROM transtb_final WHERE transtype='Debit' AND transdate = concat(year('{$from}'), '-01-01') AND folio_code = '{$account}'"; 

					$xSql2 = "SELECT sum(amount) AS total_credit FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype = 'Credit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}')";
					
					$rstrans_ox1= mysqli_query($con, $xSql) or die( mysqli_error($con));
					$rstrans_ox2= mysqli_query($con, $xSql2) or die( mysqli_error($con));


					$ySql = "SELECT sum(amount) AS total_debit FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype = 'Debit' AND (transdate BETWEEN '{$sDate}' AND '{$eDate}')";
					
					$rstrans_oy = mysqli_query($con, $ySql) or die( mysqli_error($con));

					$rs_trans_ox1=@mysqli_fetch_array($rstrans_ox1, 3 );
					$rs_trans_ox2=@mysqli_fetch_array($rstrans_ox2, 3 );$total_credixt=$rs_trans_ox1[0] + $rs_trans_ox2[0];

					if($rs_trans_oy=@mysqli_fetch_array($rstrans_oy, 3 )) $total_debitx=$rs_trans_oy[0];

				}
				

				$opening_balance = abs($total_credixt - $total_debitx);
				
				$sn=0;
				$debittotal=0; $b_lance = 0;
				$debit=0;
				$credit=0;
				if(date('m', strtotime($from))==1)
					$sq="SELECT t.* FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype IN ('Debit','Credit') AND (transdate > '{$from}' AND transdate <= '{$to}')";
				else
					$sq="SELECT t.* FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE t.acctcode = '{$account}' AND t.transtype IN ('Debit','Credit') AND (transdate >= '{$from}' AND transdate <= '{$to}')";
				$rstrans=@mysqli_query($con, $sq) or die( mysqli_error($con));
				$sn=0;
				while($rs_trans=@mysqli_fetch_array($rstrans, 3 )){
					$amount = $rs_trans['amount'];
					$transtype=@$rs_trans['transtype'];
					$debittotal += $rs_trans['amount']; 

					if ($transtype=='Credit'){
							$credit += $amount;
							if($sn == 1 )
								$b_lance =  $opening_balance + $amount;
							else
								$b_lance += $amount;
						} else {
							$debit += $amount;
							if($sn == 1 )
								$b_lance = $opening_balance - $amount;
							else
								$b_lance -= $amount;
					}
				}
			}
			//echo $b_lance."<br>";
			if($b_lance != 0){
				$closing += ($b_lance);
				$fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $account);
				echo "<tr class='$rowclass'><td>$sn</td><td>".$account."</td><td>".$fcod."</td><td>".number_format($b_lance, 2)."</td></tr>";
			}	
		}
		//echo number_format($b_lance_total, 2);
	?>

					<?php
					
					/*$sn=1;
					$sqll3 = "SELECT distinct t.folio_code, sum(amount) AS amount FROM transtb_final t INNER JOIN bank_accounttb b ON t.folio_code=b.acctcode WHERE YEAR(transdate) = '{$ryear}' AND (t.acctcode != '' AND t.acctcode != '---' AND t.acctcode IS NOT NULL AND t.acctcode != '01-001-3188' AND t.acctcode != '01-001-3131') GROUP BY t.folio_code";

					$msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
					$closing = 0;	$total_credixt=0;   $total_debitx=0;
					$acc = array();	$acctotal = array();
					while($accode = mysqli_fetch_array($msql3, 3 )){

						$account = $acode = $acc[] = $accode['folio_code'];

						$rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_credit from transtb_final WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '{$account}' ") or die( mysqli_error($con));
						if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

						$total_credixt=+$rs_trans_ox[0];

						//get all expenses before the selected date
						$rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_debit from transtb_final WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '{$account}'") or die( mysqli_error($con));
						if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

						$total_debitx=+$rs_trans_ox[0];
						$closingbal = $acctotal[] = abs($total_credixt - $total_debitx);

						$closing = abs($closing + $closingbal);
					}
					for($ic=0; $ic < count($acc); $ic++){
						$fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
						echo"<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format($acctotal[$ic], 2)."</td></tr>";
						$sn++;
					}*/


					/*if($mtnum == 1)*///{
					?>

					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td>
					</tr>

					<tr>
						<td></td><td></td><td><strong>Balance : </strong></td>
						<td><b><?php echo number_format($closing, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //

if($option=='note24')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 9 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND transtype in ('Debit','Credit') and  folio_code like '%-001%' and RIGHT(folio_code , 4) in ('3081','3082','3083','3084','3085','3086','3087','3088','3089','3090','3091','3092','3093','3095','3096','3097','3098','3110','3111','3112','3113','3115','3116','3117','3118','3119','3120','3190','3192','3195','3199','3201','3220','3232') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}




					}
					/*if($mtnum == 1)*///{
					?>
					<?php
					//}//end $mtnum
					//	}// end of while for monthyear
					?>
					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //
if($option=='note24a')
{
	?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
		<tr>
			<td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 24 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td>
		</tr>
		<tr>
			<td colspan="2" align="center" bgcolor="#E5E5E5"></td>
		</tr>
		<tr>
			<!-- Debit Column-->
			<td colspan="2" valign="top">
				<br>
				<table width="100%" align="center">
					<tr>
						<td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td>
						<td height="24" bgcolor="#E5E5E5"><strong>DATE</strong></td>
						<td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td>
						<td bgcolor="#E5E5E5"><strong>CODE</strong></td>
						<td bgcolor="#E5E5E5"><strong>REF.No</strong></td>
						<td bgcolor="#E5E5E5"><strong>CHEQUE/TRANS. ID</strong></td>
						<td bgcolor="#E5E5E5"><strong>DEBIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>CREDIT</strong></td>
						<td bgcolor="#E5E5E5"><strong>BALANCE</strong></td>
					</tr>
					<?php

					$rstrans=@mysqli_query($con, "SELECT * FROM transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' and transtype in ('Debit','Credit') AND f.deptcode = '001' AND f.category IN ('YY-001-9C', 'YY-001-10C') AND (t.acctcode != '01-001-3188' AND t.acctcode != '01-001-3131') order by transdate") or die( mysqli_error($con));
					$sn=0;//$tamt=0;$tcamt=0;$tdamt=0;
					//$debittotal=0; $b_lance = 0;
					$debit = 0; $credit = 0;
					while($rs_trans=@mysqli_fetch_array($rstrans, 3 ))
					{
						++$sn;
						if($sn%2==1) $rowclass="row-a"; else $rowclass="row-b";
						/*$particular=@$rs_trans['fname']; $amt=@$rs_trans['amount'];
						$receiptno=@$rs_trans['receiptno'];$regn=@$rs_trans['regno'];*/
						$amount = $rs_trans['amount'];
						$transtype=@$rs_trans['transtype'];
						$debittotal += $rs_trans['amount']; $transdate=@$rs_trans['transdate'];

						if ($transtype=='Debit' and $sn == 1 ){
							$b_lance = $b_lance + $credit_bf-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif
						($transtype=='Debit' and $sn != 1 )
						{
							$b_lance = $b_lance-$amount;
							$debit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn == 1 )
						{
							$b_lance = $b_lance + $credit_bf+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						elseif ($transtype=='Credit' and $sn != 1 )
						{
							$b_lance = $b_lance+$amount;
							$credit += $amount = $rs_trans['amount'];
						}
						else {
							"nothing";
						}
						$ftit = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $rs_trans['folio_code']);
						$folio_title = $ftit."(".$rs_trans['folio_code'].")";

						echo"<tr class='$rowclass'><td>$sn</td>
						<td nowrap>".date('d-M-Y',strtotime($transdate))."</td><td>$folio_title</td><td>$rs_trans[folio_code]</td><td>$rs_trans[pvno]$rs_trans[receiptno]</td><td></td>";

						if ($transtype=='Credit'){
							echo	"<td>".number_format($rs_trans['amount'], 2)."</td><td></td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}

						else {
							echo	"<td></td><td>".number_format($rs_trans['amount'], 2)."</td>";
							echo	"<td>". number_format($b_lance,2)."</td></tr>";
						}




					}
					/*if($mtnum == 1)*///{
					?>
					<?php
					//}//end $mtnum
					//	}// end of while for monthyear
					?>
					<tr>
						<td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td><td><b><?php //echo number_format($debittotal, 2); ?></b></td><td></td><td></td><td></td>
					</tr>



					<tr>
						<td></td><td></td><td><strong>Closing Balance : </strong></td>
						<td ></td>
						<td ></td>
						<td ></td>
						<td><b><?php echo number_format($opening_balance+$credit, 2); ?></b></td><td><b><?php echo number_format($debit, 2); ?></b></td>
						<td><b><?php echo number_format($b_lance, 2); ?></b></td>
					</tr>
					<!-- End of Summary of Records for Debit -->





				</table>
			</td><!-- End of Debit Column-->


			<!-- Credit Column-->
			<!-- End of Credit Column-->
		</tr>

		<?php

		//}// end of monthyear found i.e if(num_row>=1)


		?>

		<tr>
			<td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></td>
			<td><input type="button" class="row-b" value="Close" onclick="window.close()" /></td>
		</tr>
	</table>

	<?php
} //


///////////////////////////////////////////////////// End of Body ////////////////////////////////////////////
//////////////////////////////////////////////////////Report Footer /////////////////////////////////////////////
///////////////////////////////////////////////////// End of Footer ////////////////////////////////////////////
?>
</body>
</html>
