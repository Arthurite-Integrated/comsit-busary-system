<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>RECONCILLIATION REPORT</title>
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
$from=$_REQUEST['from'];
 $to=$_REQUEST['to'];
require_once "connect.php";
require_once "function.php";

//echo "$category $level";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());


 if(isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and $_POST['ryear_2']!='') {
	$_SESSION['r_m']=$_POST['rmonth_2'];
	$_SESSION['r_y']=$_POST['ryear_2'];
}
 
echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/></center><b><p align='center'>FINANCIAL RECONCILLIATION REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION['r_y']}</p></b><hr><p>";

///////////////////////////////////////////////////// End of header ////////////////////////////////////////////

  ?>
<center>
  <?php
//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
//==============> Report [By Credit Reference] <========================
if(isset($_POST['btn_debit']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	//$_SESSION['direct_bank_debit']=
	/*echo */ //$bx = "<hr>Control Report<table><tr><td>";
	$bx .= "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><strong>REMITA STATEMENT</strong></th>
		<th colspan='3' bgcolor='#F8F8F8'><strong>BANK STATEMENT</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td bgcolor='#F8F8F8'><strong>TRANS. REF.</strong></td><td bgcolor='#F8F8F8'><strong>DESCRIPTION</strong></td><td bgcolor='#F8F8F8'><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td>UN-CREDITED</td><td>BANK EXCESS</td>
		</tr>";
	////$sq="SELECT DISTINCT special_ref FROM recon_remitatb WHERE Ref='1' AND rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND matched=1 AND paytype='Credit'";
	////$sq="SELECT DISTINCT special_ref FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Credit'";
	$sq="SELECT special_ref, SUM(amount) AS remita_amount, count(special_ref) AS sCount  FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Credit' GROUP BY special_ref";
	
	$qq= mysqli_query($con, $sq);
	$total_bank=0; 
	$sn=0; 
	$total_remita=0; 
	$unremittedTotal=0;	
	$bankExcessTotal=0;
	
	while($rs= mysqli_fetch_array($qq, 3)){
		///$sql="SELECT * FROM recon_remitatb where rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and matched=1 AND paytype='Credit'";
		////$sql="SELECT SUM(amount) AS remita_amount, special_ref, count(special_ref) AS sCount FROM recon_remitatb WHERE special_ref='{$rs['special_ref']}'"; // rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND matched=1 AND paytype='Credit' GROUP BY special_ref";
		////$qry= mysqli_query($con, $sql); 
		
		////$nx= mysqli_num_rows($qry);
		
		/*$sql2="SELECT * FROM recon_banktb where rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and matched=1 AND paytype='Credit'";
		$qry2= mysqli_query($con, $sql2);  
		
		$ny= mysqli_num_rows($qry2);*/

		$unremitted=0;
		$bankExcess=0;

		//====================>
		 
		$rmon=$_SESSION['r_m'];
		$ryea=$_SESSION['r_y'];
		$iqry=mysqli_query($con, "SELECT rrr, purpose, narration FROM recon_remitatb WHERE special_ref = '{$rs['special_ref']}' LIMIT 1");
		$inner = mysqli_fetch_array($iqry, 3);
		$rrr=$inner['rrr']; //$bursary->get_any_value('rrr', 'recon_remitatb', 'special_ref', $rs['special_ref']);
		$rpur=$inner['purpose']; //$bursary->get_any_value('purpose', 'recon_remitatb', 'special_ref', $rs['special_ref']);
		$nar=$inner['narration']; //$bursary->get_any_value('narration', 'recon_remitatb', 'special_ref', $rs['special_ref']); 

		$rbnk = mysqli_query($con, "SELECT * FROM recon_banktb WHERE paymentid LIKE '%{$rs['special_ref']}%' AND paytype='Credit'");
		$bnkc=mysqli_num_rows($rbnk);
		/*if($bnkc < 1) {
			//==========> REMITA LEFT-OVER
			$total_remita_leftover += $rs['remita_amount'];
			//$amtbl += $bank_amount;
			$total_remita += $rs['remita_amount'];
			//$total_bank += $bank_amount;
			continue;
		}*/
		$bnk = mysqli_fetch_array($rbnk, 3);

		$bank_amount=$bnk['amount'];
		$bref=$bnk['special_ref'];
		$bmonth=$bnk['rmonth'];
		$byear=$bnk['ryear'];
		$desc=$bnk['paymentid'];

		$total_remita += $rs['remita_amount'];
		if($bnkc >= 1) {
			$total_remita_matched += $rs['remita_amount'];
			$total_bank_matched += $bank_amount;
			if($rs['remita_amount'] > $bank_amount){
				$unremitted = $rs['remita_amount'] - $bank_amount;
				$unremittedTotal += $unremitted;
			}elseif($rs['remita_amount'] < $bank_amount){
				$bankExcess =  $bank_amount - $rs['remita_amount'];
				$bankExcessTotal += $bankExcess;
			}
			++$sn;
			$s2="UPDATE recon_banktb SET matched=1, special_ref='{$rs['special_ref']}', Ref='1' WHERE paymentid LIKE '%{$rs['special_ref']}%'";
			@mysqli_query($con, $s2);
			$s2b="UPDATE recon_remitatb SET matched=1, special_ref='{$rs['special_ref']}', Ref='1' WHERE special_ref = '{$rs['special_ref']}'";
                         	@mysqli_query($con, $s2b);
			//=========> REMITA INFLOW
			if($rmon==$bmonth && $ryea==$byear){	
				$bx .= "<tr><td>{$sn}</td><td>{$rs['special_ref']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
				$amtrx += $rs['remita_amount'];
				$amtbx += $bank_amount;
				$unremitax += $unremitted;
				$unbankx += $bankExcess;
			}else{	
				$by .= "<tr><td>{$sn}</td><td>{$rs['special_ref']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
				$amtry += $rs['remita_amount'];
				$amtby += $bank_amount;
				$unremitay += $unremitted;
				$unbanky += $bankExcess;
			}
			//=========> BANK STATEMENT
			if($rmon==$bmonth && $ryea==$byear)
				$bx .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'>".$desc."</td><td bgcolor='#F8F8F8'>".number_format($bank_amount, 2)."</td><td>{$rmon}/{$ryea}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
			else
				$by .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'>".$desc."</td><td bgcolor='#F8F8F8'>".number_format($bank_amount, 2)."</td><td>{$bmonth}/{$byear}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
		}else{
			$sn2++;
			//==========>LEFT-OVER REMITA
			if($sn2==1) {
				if(strpos(strtolower($rpur), "tender fee") !== false){
					$color=" style='background-color:lightyellow;' ";
					$tender += $rs['remita_amount'];
				}elseif(strpos(strtolower($rpur), "boarded") !== false){
					$color=" style='background-color:lightgreen;' ";
					$boarded += $rs['remita_amount'];
				}elseif(strpos(strtolower($nar), "split") !== false){
					$color=" style='background-color:lightblue;' ";
					$split += $rs['remita_amount'];
				}else{
					if(strpos(strtolower($nar), "split") !== false) {
						$color=" style='background-color:lightblue; color:darkgreen;' ";
						$split += $rs['remita_amount'];
					}else {
						$color="";
						$others += $rs['remita_amount'];
					}
				}
				$brl1 = "<tr {$color}><td>{$sn2}</td><td>{$rs['special_ref']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
				//$total_remita_leftover += $rs['remita_amount'];
			}else {
				////$brl2 .= "<tr><td>{$sn2}</td><td>{$rs['special_ref']}</td><td align='left'><a href='#'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
				if(strpos(strtolower($rpur), "tender fee") !== false){
					$color=" style='background-color:lightyellow;' ";
					$brl2_a .= "<tr {$color}><td>{$sn2}</td><td>{$rs['special_ref']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
					$tender += $rs['remita_amount'];
				}elseif(strpos(strtolower($rpur), "boarded") !== false){
					$color=" style='background-color:lightgreen;' ";
					$brl2_b .= "<tr {$color}><td>{$sn2}</td><td>{$rs['special_ref']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
					$boarded += $rs['remita_amount'];
				}elseif(strpos(strtolower($nar), "split") !== false){
					$color=" style='background-color:lightblue;' ";
					$brl2_d .= "<tr {$color}><td>{$sn2}</td><td>{$rs['special_ref']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
					$split += $rs['remita_amount'];
				}else{
					if(strpos(strtolower($nar), "split") !== false) {
						$color=" style='background-color:lightblue; color:darkgreen;' ";
						$brl2_d .= "<tr {$color}><td>{$sn2}</td><td>{$rs['special_ref']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
						$split += $rs['remita_amount'];
					}else {
						$color="";
						$brl2_c .= "<tr {$color}><td>{$sn2}</td><td>{$rs['special_ref']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['sCount']}) {$rpur}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
						$others += $rs['remita_amount'];
					}
				}
			}
			$total_remita_leftover += $rs['remita_amount'];
			////$brl .= "<td bgcolor='#F8F8F8'>&nbsp;</td><td bgcolor='#F8F8F8'>&nbsp;</td><td bgcolor='#F8F8F8'>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
			$brl1x = "<td bgcolor='#F8F8F8' colspan='6' rowspan='{$sn2}' valign='top'>";//&nbsp;</td></tr>";
		}
		//====================>

		/****while($r= mysqli_fetch_array($qry, 3 )){
			++$sn; $total_remita += $r['remita_amount'];
			//$ssum=$r['sCount'];
			/**** 
			$rdesc=$bursary->get_any_value('paymentid', 'recon_remitatb', 'Ref', '1', " AND special_ref = '".$r['special_ref']."'");
			$rrr=$bursary->get_any_value('credit_reference', 'recon_remitatb', 'Ref', '1', " AND special_ref = '".$r['special_ref']."'");
			$rpur=$bursary->get_any_value('purpose', 'recon_purpose', 'rrr', $rrr);
			$rmon=$bursary->get_any_value('rmonth', 'recon_banktb', 'matched', '1', " AND special_ref = '{$r['special_ref']}'");
			$ryea=$bursary->get_any_value('ryear', 'recon_banktb', 'matched', '1', " AND special_ref = '{$r['special_ref']}'");
			$bank_amount=$bursary->get_any_value('amount', 'recon_banktb', 'matched', '1', " AND special_ref = '{$r['special_ref']}'");
			****
			
			////$rdesc=$bursary->get_any_value('paymentid', 'recon_remitatb', 'Ref', '1', " AND special_ref = '".$r['special_ref']."'");
			$rrr=$bursary->get_any_value('credit_reference', 'recon_remitatb', 'Ref', '1', " AND special_ref = '".$r['special_ref']."'");
			$rpur=$bursary->get_any_value('purpose', 'recon_purpose', 'rrr', $rrr);

			$rbnk = mysqli_query($con, "SELECT * FROM recon_banktb WHERE paymentid LIKE '%{$r['special_ref']}%'");
			$bnk = mysqli_fetch_array($rbnk, 3);
			$rmon=$_SESSION['r_m'];
			$ryea=$_SESSION['r_y'];
			$bank_amount=$bnk['amount'];
			$bref=$r['special_ref'];
			$bmonth=$_SESSION['r_m'];
			$byear=$_SESSION['r_y'];
			$desc=$bnk['paymentid'];

			$total_bank += $bank_amount;
			if($r['remita_amount'] > $bank_amount){
				$unremitted = $r['remita_amount'] - $bank_amount;
				$unremittedTotal += $unremitted;
			}elseif($r['remita_amount'] < $bank_amount){
				$bankExcess =  $bank_amount - $r['remita_amount'];
				$bankExcessTotal += $bankExcess;
			}

			if($rmon===$_SESSION['r_m'] && $ryea===$_SESSION['r_y']){	
				$bx .= "<tr><td>{$sn}</td><td>{$r[special_ref]}</td><td align='left'><a href='#'>({$r['sCount']}) {$rpur}</a></td><td>".number_format($r['remita_amount'], 2)."</td><td>{$_SESSION['r_m']}/{$_SESSION['r_y']}</td>";
				$amtrx += $r['remita_amount'];
				$amtbx += $bank_amount;
				$unremitax += $unremitted;
				$unbankx += $bankExcess;
			}else{	
				$by .= "<tr><td>{$sn}</td><td>{$r[special_ref]}</td><td align='left'><a href='#'>({$r['sCount']}) {$rpur}</a></td><td>".number_format($r['remita_amount'], 2)."</td><td>{$_SESSION['r_m']}/{$_SESSION['r_y']}</td>";
				$amtry += $r['remita_amount'];
				$amtby += $bank_amount;
				$unremitay += $unremitted;
				$unbanky += $bankExcess;
			}

			//$bank_amount=$bursary->get_any_value('amount', 'recon_banktb', 'matched', '1', " AND paymentid LIKE '%{$r['paymentid']}' AND amount={$r['amount']}");
			///$bank_amount=$bursary->get_any_value('amount', 'recon_banktb', 'matched', '1', " AND credit_reference = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount={$r['amount']}");
			
			/**** 
			$bref=$bursary->get_any_value('special_ref', 'recon_banktb', 'matched', '1', " AND special_ref = '{$r['special_ref']}'");
			$bmonth=$bursary->get_any_value('rmonth', 'recon_banktb', 'matched', '1', " AND special_ref = '{$r['special_ref']}'");
			$byear=$bursary->get_any_value('ryear', 'recon_banktb', 'matched', '1', " AND special_ref = '{$r['special_ref']}'");
			///$bref=$bursary->get_any_value('credit_reference', 'recon_banktb', 'matched', '1', " AND credit_reference = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount={$r['amount']}");
			$desc=$bursary->get_any_value('paymentid', 'recon_banktb', 'matched', '1', " AND special_ref = '".$r['special_ref']."'");
			*****
			//" select paymentid from recon_banktb where matched= '1' AND credit_reference = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount={$r['amount']}".
			if($rmon===$_SESSION['r_m'] && $ryea===$_SESSION['r_y'])
				$bx .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'>".$desc."</td><td bgcolor='#F8F8F8'>".number_format($bank_amount, 2)."</td><td>{$bmonth}/{$byear}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
			else
				$by .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'>".$desc."</td><td bgcolor='#F8F8F8'>".number_format($bank_amount, 2)."</td><td>{$bmonth}/{$byear}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
		}****/
	}
	//===============> BANK LEFT-OVER 
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (Ref='0' OR Ref is Null) AND paytype='Credit'";
	$qryb= mysqli_query($con, $sqlb); $sn=0; $lefttotal=0; $nm= mysqli_num_rows($qryb);
	//<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} BANK LEFT-OVER [{$nm}]</h5></th></tr>
	$brl1x .= "<TABLE width='90%' border='1' rules='rows' align='center'>
	<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td><td>PERIOD</td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$snx; 
		$lefttotal += $r['amount'];
		$brl1x .= "<tr><td>{$snx}</td><td>".$r['credit_reference']."</td><td style='font-size:10px;'>{$r[paymentid]}</td><td>".number_format($r['amount'], 2)."</td><td>{$r['rmonth']}/{$r['ryear']}</td></tr>";
	}
	$lefttotal = number_format($lefttotal, 2);
	$brl1x .= "<tr><th colspan=3>TOTAL</th><th><h5>{$lefttotal}</h5></th><td></td></tr></TABLE>";
	//====================================================================================>
	$brl1x .= "</td></tr>";

	$total_remita_matched = number_format($total_remita_matched, 2);
	$total_remita = number_format($total_remita, 2);
	$total_bank = $bursary->get_any_value('sum(amount) AS bSum', 'recon_banktb', 'rmonth', $_SESSION['r_m'], " AND ryear = '".$_SESSION['r_y']."' AND paytype='Credit'");
	$total_bank_matched = number_format($total_bank_matched, 2);
	$total_bank = number_format($total_bank, 2);
	/////$tender = $bursary->get_any_value('sum(amount) AS bSum', 'recon_remitatb', 'Ref', '0', " purpose LIKE '%TENDER FEE%' AND month = '{$_SESSION['r_m']}' AND ryear = '".$_SESSION['r_y']."'");
	$tender = number_format($tender, 2);
	/////$boarded = $bursary->get_any_value('sum(amount) AS bSum', 'recon_remitatb', 'Ref', '0', " purpose LIKE '%BOARDED PROPERTY%' AND month = '{$_SESSION['r_m']}' AND ryear = '".$_SESSION['r_y']."'");
	$boarded = number_format($boarded, 2);
	/////$split = $bursary->get_any_value('sum(amount) AS bSum', 'recon_remitatb', 'Ref', '0', " narration LIKE '% split%' AND month = '{$_SESSION['r_m']}' AND ryear = '".$_SESSION['r_y']."'");
	$split = number_format($split, 2);
	//$others = number_format($total_remita_leftover - ($tender + $boarded), 2);
	$others = number_format($others, 2);
		
		$btx .= "<tr><td></td><td></td><th>TOTAL MATCHED IN SAME MONTH:</th><th align='left'>".number_format($amtrx, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>".number_format($amtbx, 2)."</th><td></td>
		<th align='left'>".number_format($unremitax, 2)."</th><th align='left'>".number_format($unbankx, 2)."</th>
		</tr>";

		if($amtry>0) {
			$bty .= "<tr><td></td><td></td><th>TOTAL MATCHED IN OTHER MONTH(S):</th><th align='left'>".number_format($amtry, 2)."</th><td></td>
			<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>".number_format($amtby, 2)."</th><td></td>
			<th align='left'>".number_format($unremitay, 2)."</th><th align='left'>".number_format($unbanky, 2)."</th>
			</tr>";
		}
		if($tender>0) {
			$btl_a .= "<tr><td></td><td></td><th>TOTAL LEFT-OVER (TENDER):</th><th>{$tender}</th><td></td>
			<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'></th><td></td>
			<th></th><th></th>
			</tr>";
		}
		if($boarded>0) {
			$btl_b .= "<tr><td></td><td></td><th>TOTAL LEFT-OVER (BOARDED PROPERTY):</th><th>{$boarded}</th><td></td>
			<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'></th><td></td>
			<th></th><th></th>
			</tr>";
		}
		if($others>0) {
			$btl_c .= "<tr><td></td><td></td><th>TOTAL LEFT-OVER (OTHERS):</th><th>{$others}</th><td></td>
			<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'></th><td></td>
			<th></th><th></th>
			</tr>";
		}
		if($split>0) {
			$btl_d .= "<tr><td></td><td></td><th>TOTAL LEFT-OVER (SPLIT):</th><th>{$split}</th><td></td>
			<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'></th><td></td>
			<th></th><th></th>
			</tr>";
		}
		
		$btl .= "<tr><td></td><td></td><th>TOTAL LEFT-OVER (NO MATCH TOTAL):</th><th align='left'>".number_format($total_remita_leftover, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'></th><td></td>
		<th></th><th></th>
		</tr>";

		/*$btl .= "<tr><td>".number_format($boarded, 2)."</td><td>".number_format($others, 2)."</td><th>TOTAL LEFT-OVER (NO MATCH):</th><th align='left'>".number_format($total_remita_leftover, 2)."</th><td>".number_format($tender, 2)."</td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'>{$lefttotal}</th><td></td>
		<th></th><th></th>
		</tr>";*/

		$bx2 .= "<tr><td></td><td></td><th>TOTAL (MATCHED):</th><th align='left'>{$total_remita_matched}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'>{$total_bank_matched}</th><td></td>
		<th></th><th></th>
		</tr>";

	/*echo */ $bx2 .= "<tr><td></td><td></td><th>GRAND TOTAL (STATEMENT):</th><th align='left'>{$total_remita}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'>{$total_bank}</th><td></td>
		<th align='left'>".number_format($unremittedTotal, 2)."</th><th align='left'>".number_format($bankExcessTotal, 2)."</th>
		</tr></TABLE>";

		///echo "</td><td>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr>";
			echo $bxh;
			echo "<tr style='background-color:lightgray;'><td colspan='11'>RECORD MATCHED IN SAME MONTH<hr></td></tr>";
			echo $bx;
			echo $btx;
			echo "<tr style='background-color:lightgray;'><td colspan='11'>REMITANCE FOUND IN OTHER MONTH(S)<hr></td></tr>";
			echo $by;
			echo $bty;
			echo "<tr style='background-color:lightgray;'><td colspan='5'>REMITA LEFT-OVER<hr></td><td colspan='6'>BANK LEFT-OVER<hr></td></tr>";
			echo $brl1.$brl1x;
			//echo $btl;
			echo $brl2_c;
			echo $btl_c;
			echo $brl2_b;
			echo $btl_b;
			echo $brl2_a;
			echo $btl_a;
			echo $brl2_d;
			echo $btl_d;
			echo $btl;
			echo "<tr style='background-color:lightgray;'><td colspan='11'><hr></td></tr>";
			echo $bx2;
		echo "</table><hr>";

}

//==============> REMITA LEFT-OVER [Credit Reference] <========================
if(isset($_POST['btn_rlo']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sqlb="SELECT * FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND (Ref='0' OR Ref is Null) AND paytype='Credit'";
	$qryb= mysqli_query($con, $sqlb); 
	$sn=0; 
	$total=0; 
	$nm= mysqli_num_rows($qryb);
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION['r_m']}, {$_SESSION['r_y']} REMITA LEFT-OVER [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><td>PERIOD</td><td><strong>DESCRIPTION</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$sn; 
		$total += $r['amount'];
		echo "<tr><td>{$sn}</td><td>{$r['rmonth']}/{$r['ryear']}</td><td>{$r['paymentid']}</td><td>".$r['special_ref']."</td><td>".number_format($r['amount'], 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="uncreadited lodgment";
}

//==============> BANK LEFT-OVER [Credit Reference] <========================
if(isset($_POST['btn_clo']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (Ref='0' OR Ref is Null) AND paytype='Credit'";
	$qryb= mysqli_query($con, $sqlb); $sn=0; $total=0; $nm= mysqli_num_rows($qryb);
	//$_SESSION['uncreadited_lodgment']=
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} BANK LEFT-OVER [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><td>PERIOD</td><td><strong>DESCRIPTION</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){++$sn; $total += $r['amount'];
		//$_SESSION['uncreadited_lodgment'].=
		echo "<tr><td>{$sn}</td><td>{$r['rmonth']}/{$r['ryear']}</td><td>{$r[paymentid]}</td><td>".$r['credit_reference']."</td><td>".number_format($r['amount'], 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	//$_SESSION['uncreadited_lodgment'].=
	echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="uncreadited lodgment";
}

//==============> Monthly Report [By Payment Code] <========================
if(isset($_POST['btn_pcr']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$bx .= "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><strong>REMITA STATEMENT</strong></th>
		<th colspan='4' bgcolor='#F8F8F8'><strong>BANK STATEMENT</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td>
		<td bgcolor='#F8F8F8'><strong>TRANS. REF.</strong></td><td bgcolor='#F8F8F8'><strong>AMOUNT</strong></td>
		<td>UN-CREDITED</td><td>BANK EXCESS</td>
		</tr>";
	/////$sq="SELECT DISTINCT special_ref2, purpose FROM recon_remitatb WHERE Pay='1' AND rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Credit'";
	/////$sq="SELECT DISTINCT r.special_ref2, p.purpose, sum(r.amount) AS rSum, count(r.special_ref2) AS rCount FROM recon_remitatb r INNER JOIN recon_purpose p ON r.special_ref2=p.code WHERE r.Pay='1' AND r.rmonth='".$_SESSION['r_m']."' AND r.ryear='".$_SESSION['r_y']."' AND r.paytype='Credit' AND r.special_ref2 != '' GROUP by r.special_ref2, p.purpose ORDER BY p.`purpose` ASC";
	$sq="SELECT DISTINCT special_ref2, sum(amount) AS rSum, count(special_ref2) AS rCount FROM recon_remitatb WHERE Pay='1' AND rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Credit' AND special_ref2 != '' GROUP by special_ref2 ORDER BY `recon_remitatb`.`special_ref2` ASC";
	$qq= mysqli_query($con, $sq);
	$total_bank=0; 
	$sn=0; 
	$total_remita=0; 
	$unremittedTotal=0;	
	$bankExcessTotal=0;
	$nodesc= 0;
	while($rs= mysqli_fetch_array($qq, 3)){
		$purpose=$bursary->get_any_value('purpose', 'recon_purpose', 'code', $rs['special_ref2']);
		if($purpose=='') $purpose=$bursary->get_any_value('purpose', 'recon_remitatb', 'special_ref2', $rs['special_ref2']);
		$sql="SELECT SUM(amount) AS bSum, count(special_ref2) AS bCount FROM recon_banktb WHERE Pay='1' AND special_ref2='{$rs['special_ref2']}' AND rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Credit' AND special_ref2 != ''";
		$qry= mysqli_query($con, $sql); 
		
		$unremitted=0;	
		$bankExcess=0;
		$total_remita += $rs['rSum'];
		
		while($b = mysqli_fetch_array($qry, 3 )){
			++$sn; 
			$bank_amount=$b['bSum'];
			$total_bank += $b['bSum']; 
			if($rs['rSum'] > $bank_amount){
				$unremitted = $rs['rSum'] - $bank_amount;
				$unremittedTotal += $unremitted;
			}elseif($rs['rSum'] < $bank_amount){
				$bankExcess =  $bank_amount - $rs['rSum'];
				$bankExcessTotal += $bankExcess;
			}
			if($rs['rSum']-$b['bSum']==0) $color=" style='background-color:lightblue; color:darkgreen;' ";
			else $color='';
			if(is_numeric($purpose)) {
				//$purpose .= ': No desc.';
				$nodesc += $rs['rCount'];
				mysqli_query($con, "UPDATE recon_remitatb SET nodesc='1' WHERE Pay='1' AND special_ref2='{$rs['special_ref2']}' AND rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Credit' AND special_ref2 != ''");
			}
			$bx .= "<tr {$color}><td>{$sn}</td>
				<td>{$rs['special_ref2']}</td><td align='left'><a href='recreport-mini.php?tid=special_ref2&productcode=".sha1($rs['special_ref2'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$rs['rCount']}) {$purpose}</a></td><td>".number_format($rs['rSum'], 2)."</td>"
				. "<td><a href='#'>({$b['bCount']})</a> </td><td>".number_format($b['bSum'], 2)."</td>"
				. "<td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
			
		}
	}
	$leftRemita = abs($bursary->get_any_value('sum(abs(amount)) AS rSum', 'recon_remitatb', 'rmonth', $_SESSION['r_m'], " AND ryear = '".$_SESSION['r_y']."' AND (Pay='0' OR Pay is Null) AND paytype='Credit'"));
	$leftBank = abs($bursary->get_any_value('sum(abs(amount)) AS bSum', 'recon_banktb', 'rmonth', $_SESSION['r_m'], " AND ryear = '".$_SESSION['r_y']."' AND (Pay='0' OR Pay is Null) AND paytype='Credit'"));

	$remita = $total_remita + $leftRemita;
	$bank = $total_bank + $leftBank;

	$total_remita = number_format($total_remita, 2);
	$total_bank = number_format($total_bank, 2);

	$leftRemita = number_format($leftRemita, 2);
	$leftBank = number_format($leftBank, 2);
	
	$remita = number_format($remita, 2);
	$bank = number_format($bank, 2);
	
	/*echo */ $bx .= "<tr><td></td><td></td><th align='right'>MATCHED:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='right'>{$total_remita}</th>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8' align='right'>{$total_bank}</th>
		<th align='right'>".number_format($unremittedTotal, 2)."</th><th align='right'>".number_format($bankExcessTotal, 2)."</th>
		</tr>";
		$bx .= "<tr><td></td><td></td><th align='right'>LEFT-OVER:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='right'>{$leftRemita}</th>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8' align='right'>{$leftBank}</th>
		<th></th><th></th>
		</tr>";
		$bx .= "<tr><td></td><td></td><th align='right'>TOTAL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='right'>{$remita}</th>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8' align='right'>{$bank}</th>
		<th></th><th></th>
		</tr>";
		echo "</TABLE>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
			echo $bx;
		echo "</td><td>";
			echo $by;
		echo "<td valign='top'></tr></table><hr>";
		echo "<h3><a href='recreport-mini.php?tid=nodesc&noDesc&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>({$nodesc}) ITEMS WITHOUT DISCRIPTION</a>
		 || 
		 <a href='recreport-mini.php?tid=nodesc2&noDesc2&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}' target='_blank'>ITEMS WITH DISCRIPTION</a></h3>";

}

//==============> Annual Report [By Payment Code] <========================
if(isset($_POST['btn_apcr']) and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$bx = "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><strong>REMITA STATEMENT</strong></th>
		<th colspan='4' bgcolor='#F8F8F8'><strong>BANK STATEMENT</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td>
		<td bgcolor='#F8F8F8'><strong>TRANS. REF.</strong></td><td bgcolor='#F8F8F8'><strong>AMOUNT</strong></td>
		<td>UN-CREDITED</td><td>BANK EXCESS</td>
		</tr>";
	$sq="SELECT DISTINCT special_ref2, purpose, sum(amount) AS rSum, count(special_ref2) AS rCount FROM recon_remitatb WHERE Pay='1' AND ryear='".$_SESSION['r_y']."' AND paytype='Credit' GROUP by special_ref2, purpose ORDER BY `recon_remitatb`.`purpose` ASC";
	$qq= mysqli_query($con, $sq);
	$total_bank=0; 
	$sn=0; 
	$total_remita=0; 
	$unremittedTotal=0;	$bankExcessTotal=0;
	while($rs= mysqli_fetch_array($qq, 3)){
		$sql="SELECT SUM(amount) AS bSum, count(special_ref2) AS bCount FROM recon_banktb WHERE Pay='1' AND special_ref2='{$rs['special_ref2']}' AND ryear='".$_SESSION['r_y']."'";
		$qry= mysqli_query($con, $sql); 

		$unremitted=0;	
		$bankExcess=0;
		$total_remita += $rs['rSum'];
		while($r= mysqli_fetch_array($qry, 3 )){
			++$sn; 
			$bank_amount=$r['bSum'];
			$total_bank += $r['bSum']; 
			if($rs['rSum'] > $bank_amount){
				$unremitted = $rs['rSum'] - $bank_amount;
				$unremittedTotal += $unremitted;
			}elseif($rs['rSum'] < $bank_amount){
				$bankExcess =  $bank_amount - $rs['rSum'];
				$bankExcessTotal += $bankExcess;
			}
			if($rs['rSum']-$r['bSum']==0) $color=" style='background-color:lightblue; color:darkgreen;' ";
			else $color='';
			$bx .= "<tr {$color}><td>{$sn}</td>
				<td>{$rs['special_ref2']}</td><td align='left'><a href='#'>({$rs['rCount']}) {$rs['purpose']}</a></td><td>".number_format($rs['rSum'], 2)."</td>"
				. "<td><a href='#'>({$r['bCount']})</a> </td><td>".number_format($r['bSum'], 2)."</td>"
				. "<td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
		}
	}

	$total_remita = number_format($total_remita, 2);
	$total_bank = number_format($total_bank, 2);

	/*echo */ $bx .= "<tr><td></td><td></td><th>TOTAL:</th><th align='left'>{$total_remita}</th>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'>{$total_bank}</th>
		<th align='left'>".number_format($unremittedTotal, 2)."</th><th align='left'>".number_format($bankExcessTotal, 2)."</th>
		</tr></TABLE>";
		///echo "</td><td>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
			echo $bx;
		echo "</td><td>";
			echo $by;
		echo "<td valign='top'></tr></table><hr>";

}

//==============> REMITA LEFT-OVER [By Payment Code] <========================
if(isset($_POST['btn_prlo']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sqlb="SELECT * FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (Pay='0' OR Pay is Null) AND paytype='Credit' ORDER BY purpose";
	$qryb= mysqli_query($con, $sqlb); 
	$sn=0; 
	$total=0; 
	$boarded=0;
	$tender=0;
	$split=0;
	$nm= mysqli_num_rows($qryb);
	//$_SESSION['uncreadited_lodgment']=
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} REMITA LEFT-OVER [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><!--td><strong>PERIOD</strong></td--><td><strong>PURPOSE</strong></td><td><strong>PAYER</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>RRR</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$sn; $color='';
		$total += $r['amount'];
		$rpur = $r['purpose'];
		$nar = $r['narration'];
		if(strpos(strtolower($rpur), "tender fee") !== false) {
			$color=" style='background-color:lightyellow;' ";
			$tender += $r['amount'];
		}
		elseif(strpos(strtolower($rpur), "boarded") !== false) {
			$color=" style='background-color:lightgreen;' ";
			$boarded += $r['amount'];
		}
		elseif(strpos(strtolower($nar), "split") !== false) {
			$color=" style='background-color:lightblue;' ";
			$rpur .= " - Split";
			$split += $r['amount'];
		}else $others += $r['amount'];
		echo "<tr {$color}><td>{$sn}</td><!--td>{$r['rmonth']}/{$r['ryear']}</td--><td>{$rpur}</td><td>{$r['payer']}</td><td>".$r['special_ref']."</td><td>".$r['rrr']."</td><td>".number_format($r['amount'], 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	$tender = number_format($tender, 2);
	$boarded = number_format($boarded, 2);
	$split = number_format($split, 2);
	$others = number_format($others, 2);
	echo "<tr><th colspan='5' align='right'>TENDER FEES</th><th><h5>{$tender}</h5></th></tr>";
	echo "<tr><th colspan='5' align='right'>BOARDED PROPERTY</th><th><h5>{$boarded}</h5></th></tr>";
	echo "<tr><th colspan='5' align='right'>THIRD-PARTY SPLIT</th><th><h5>{$split}</h5></th></tr>";
	echo "<tr><th colspan='5' align='right'>OTHERS</th><th><h5>{$others}</h5></th></tr>";
	echo "<tr><th colspan='5' align='right'>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="uncreadited lodgment";
}

//==============> BANK LEFT-OVER [By Payment Code] <========================
if(isset($_POST['btn_pblo']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (Pay='0' OR Pay is Null) AND paytype='Credit'";
	$qryb= mysqli_query($con, $sqlb); 
	$sn=0; 
	$total=0; 
	$nm= mysqli_num_rows($qryb);
	//$_SESSION['uncreadited_lodgment']=
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} BANK LEFT-OVER [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><td>PERIOD</td><td><strong>DESCRIPTION</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){++$sn; $total += $r['amount'];
		echo "<tr><td>{$sn}</td><td>{$r['rmonth']}/{$r['ryear']}</td><td>{$r['paymentid']}</td><td>".$r['credit_reference']."</td><td>".number_format($r['amount'], 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="uncreadited lodgment";
}

//====================> OUTFLOW - (REMITA WITH BANK STATEMENT) Monthly Report <========================
if(isset($_POST['btn_outflow']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$bxh = "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><strong>REMITA STATEMENT</strong></th>
		<th colspan='3' bgcolor='#F8F8F8'><strong>BANK STATEMENT</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>DEBIT REF.</strong></td><td><strong>FUNDING: COUNT</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td bgcolor='#F8F8F8'><strong>TRANS. REF.</strong></td><td bgcolor='#F8F8F8'><strong>DESCRIPTION</strong></td><td bgcolor='#F8F8F8'><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td>UN-CREDITED</td><td>BANK EXCESS</td>
		</tr>";
	if($_POST['inacct']=='') $fund='';
	else $fund=" AND funding='{$_POST['inacct']}' ";
	$sq="SELECT special_ref, SUM(amount) AS remita_amount, count(special_ref) AS sCount  FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND (special_ref != '' OR special_ref IS NOT NULL) {$fund} GROUP BY special_ref ORDER BY funding";
	$qq= mysqli_query($con, $sq);
	$total_bank=0; 
	$sn=0; 
	$total_remita=0; 
	$unremittedTotal=0;	$bankExcessTotal=0;
	
	while($rs= mysqli_fetch_array($qq, 3)){
		if($rs['special_ref']=='') continue;
		$unremitted=0;
		$bankExcess=0;

		$rmon=$_SESSION['r_m'];
		$ryea=$_SESSION['r_y'];
		$iqry=mysqli_query($con, "SELECT rrr, purpose, narration AS 'batchno', funding FROM recon_remitatb WHERE special_ref = '{$rs['special_ref']}' AND paytype='Debit' {$fund} LIMIT 1");
		$inner = mysqli_fetch_array($iqry, 3);
		$rrr=$inner['rrr']; 
		//$rpur=$inner['purpose']; 
		$batchno=$inner['batchno']; 
		$funding=$inner['funding'];

		$rbnk = mysqli_query($con, "SELECT * FROM recon_banktb WHERE paymentid LIKE '%{$rs['special_ref']}%' AND paytype='Debit'");
		$bnkc=mysqli_num_rows($rbnk);
		$bnk = mysqli_fetch_array($rbnk, 3);

		$bank_amount=abs($bnk['amount']);
		$bref=$bnk['special_ref'];
		$bmonth=$bnk['rmonth'];
		$byear=$bnk['ryear'];
		$desc=$bnk['paymentid'];
		

		$total_remita += $rs['remita_amount'];
		if($bnkc >= 1) {
			$total_remita_matched += $rs['remita_amount'];
			$total_bank_matched += $bank_amount;
			if($rs['remita_amount'] > $bank_amount){
				$unremitted = $rs['remita_amount'] - $bank_amount;
				$unremittedTotal += $unremitted;
			}elseif($rs['remita_amount'] < $bank_amount){
				$bankExcess =  $bank_amount - $rs['remita_amount'];
				$bankExcessTotal += $bankExcess;
			}
			++$sn;
			$s1="UPDATE recon_remitatb SET matched='2', special_ref='{$rs['special_ref']}', Ref='2' WHERE special_ref = '{$rs['special_ref']}' AND paytype='Debit'";
                         	@mysqli_query($con, $s1);
			$s2="UPDATE recon_banktb SET matched='2', special_ref='{$rs['special_ref']}', Ref='2' WHERE paymentid LIKE '%{$rs['special_ref']}%' AND paytype='Debit'";
                         	@mysqli_query($con, $s2);
			//=========> REMITA OUTFLOW
			$xTrial = false;
			if($rmon==$bmonth && $ryea==$byear){
				$bx .= "<tr><td>{$sn}</td><td>{$rs['special_ref']}</td><td nowrap align='left'>{$funding}: <a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
				$amtrx += $rs['remita_amount'];
				$amtbx += $bank_amount;
				$unremitax += $unremitted;
				$unbankx += $bankExcess;
				$rssCount += $rs['sCount'];
			}else{	
				$xTrial = true;
				$by .= "<tr><td>{$sn}</td><td>{$rs[special_ref]}</td><td nowrap align='left'>{$funding}: <a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
				$amtry += $rs['remita_amount'];
				$amtby += $bank_amount;
				$unremitay += $unremitted;
				$unbanky += $bankExcess;
			}
			//=========> BANK STATEMENT
			if($rmon==$bmonth && $ryea==$byear)
				$bx .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'>".$desc."</td><td bgcolor='#F8F8F8'>".number_format($bank_amount, 2)."</td><td>{$rmon}/{$ryea}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
			else{
				$xTrial = true;
				$by .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'>".$desc."</td><td bgcolor='#F8F8F8'>".number_format($bank_amount, 2)."</td><td>{$bmonth}/{$byear}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
			}
		}else{
			$sn2++;
			//==========>LEFT-OVER REMITA
			if($sn2==1) {
				$brl1 = "<tr {$color}><td>{$sn2}</td><td>{$rs['special_ref']}</td><td nowrap align='left'>{$funding}: <a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
			}else {
				$brl2_c .= "<tr {$color}><td>{$sn2}</td><td>{$rs['special_ref']}</td><td nowrap align='left'>{$funding}: <a href='recreport-mini.php?tid=special_ref&productcode=".sha1($rs['special_ref'])."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
			}
			$total_remita_leftover += $rs['remita_amount'];
			$brl1x = "<td bgcolor='#F8F8F8' colspan='6' rowspan='{$sn2}' valign='top'>";
		}
	}
	//===============> BANK LEFT-OVER 
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and Ref != '2' AND paytype='Debit'";
	$qryb= mysqli_query($con, $sqlb); $sn=0; $lefttotal=0; $nm= mysqli_num_rows($qryb);
	$brl1x .= "<TABLE width='90%' border='1' rules='rows' align='center'>
	<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td><td>PERIOD</td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$snx; 
		$lefttotal += abs($r['amount']);
		$brl1x .= "<tr><td>{$snx}</td><td>".$r['credit_reference']."</td><td style='font-size:10px;'>{$r[paymentid]}</td><td>".number_format(abs($r['amount']), 2)."</td><td>{$r['rmonth']}/{$r['ryear']}</td></tr>";
	}
	$lefttotal = number_format($lefttotal, 2);
	$brl1x .= "<tr><th colspan=3>TOTAL</th><th><h5>{$lefttotal}</h5></th><td></td></tr></TABLE>";
	//====================================================================================>
	$brl1x .= "</td></tr>";

	$total_remita_matched = number_format($total_remita_matched, 2);
	$total_remita = number_format($total_remita, 2);
	$total_bank = abs($bursary->get_any_value('sum(abs(amount)) AS bSum', 'recon_banktb', 'rmonth', $_SESSION['r_m'], " AND ryear = '".$_SESSION['r_y']."' AND paytype='Debit'"));
	$total_bank_matched = number_format($total_bank_matched, 2);
	$total_bank = number_format($total_bank, 2);
		
		$btx .= "<tr><td></td><th colspan='2'>TOTAL MATCHED IN THE MONTH:</th><th align='left'>".number_format($amtrx, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>".number_format($amtbx, 2)."</th><td></td>
		<th align='left'>".number_format($unremitax, 2)."</th><th align='left'>".number_format($unbankx, 2)."</th>
		</tr>";
		if($xTrial === true){
			$bty .= "<tr><td></td><th colspan='2'>IN OTHER MONTH(S):</th><th align='left'>".number_format($amtry, 2)."</th><td></td>
			<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>".number_format($amtby, 2)."</th><td></td>
			<th align='left'>".number_format($unremitay, 2)."</th><th align='left'>".number_format($unbanky, 2)."</th>
			</tr>";
		}
		$btl .= "<tr><td></td><th colspan='2'>TOTAL LEFT-OVER:</th><th align='left'>".number_format($total_remita_leftover, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'></th><td></td>
		<th></th><th></th>
		</tr>";

		$bx2 .= "<tr><td></td><th colspan='2'>TOTAL (MATCHED):</th><th align='left'>{$total_remita_matched}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'>{$total_bank_matched}</th><td></td>
		<th align='left'>".number_format($unremittedTotal, 2)."</th><th align='left'>".number_format($bankExcessTotal, 2)."</th>
		</tr>";

		$bx2 .= "<tr><td></td><th colspan='2'>GRAND TOTAL (STATEMENT):</th><th align='left'>{$total_remita}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'>{$total_bank}</th><td></td>
		<th></th><th></th>
		</tr></TABLE>";

	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr>";
			echo $bxh;
			echo "<tr style='background-color:lightgray;'><td colspan='11'>RECORD MATCHED IN SAME MONTH [ <b><a href='recreport-mini.php?matchedOutflow&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&viewrecord' target='_blank'> VIEW RECORD {$rssCount}</a> ] <hr></td></tr>";
			echo $bx;
			echo $btx;
			if($xTrial === true){
				echo "<tr style='background-color:lightgray;'><td colspan='11'>REMITANCE FOUND IN OTHER MONTH(S)<hr></td></tr>";
				echo $by;
				echo $bty;
			}
			echo "<tr style='background-color:lightgray;'><td colspan='5'>REMITA LEFT-OVER<hr></td><td colspan='6'>BANK LEFT-OVER<hr></td></tr>";
			echo $brl1.$brl1x;
			echo $brl2_c;
			echo $btl;
			echo "<tr style='background-color:lightgray;'><td colspan='11'><hr></td></tr>";
			echo $bx2;
		echo "</table><hr>";

}

//==============> REMITA LEFT-OVER [OUTFLOW - (REMITA WITH BANK STATEMENT)] <========================
if(isset($_POST['btn_orlo']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sqlb="SELECT * FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND Ref!='2' AND paytype='Debit'";
	$qryb= mysqli_query($con, $sqlb); 
	$sn=0; $total=0; 
	$nm= mysqli_num_rows($qryb);
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} REMITA LEFT-OVER [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><td>PERIOD</td><td><strong>DESCRIPTION</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$sn; 
		$total += $r['amount'];
		echo "<tr><td>{$sn}</td><td>{$r['rmonth']}/{$r['ryear']}</td><td>{$r[paymentid]}</td><td>".$r['special_ref']."</td><td>".number_format($r['amount'], 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="uncreadited lodgment";
}

//==============> BANK LEFT-OVER [OUTFLOW - (REMITA WITH BANK STATEMENT)] <========================
if(isset($_POST['btn_oblo']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND Ref!='2' AND paytype='Debit'";
	$qryb= mysqli_query($con, $sqlb); 
	$sn=0; $total=0; 
	$nm= mysqli_num_rows($qryb);
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} BANK LEFT-OVER [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><td>PERIOD</td><td><strong>DESCRIPTION</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$sn; 
		$total += abs($r['amount']);
		echo "<tr><td>{$sn}</td><td>{$r['rmonth']}/{$r['ryear']}</td><td>{$r[paymentid]}</td><td>".$r['credit_reference']."</td><td>".number_format(abs($r['amount']), 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="uncreadited lodgment";
}

//====================> OUTFLOW - (REMITA WITH CASHBOOK)  Monthly Report by Batch No. <========================
if(isset($_POST['btn_coutflow']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$bxh = "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='5'><strong>REMITA STATEMENT</strong></th>
		<th colspan='4' bgcolor='#F8F8F8'><strong>CASHBOOK STATEMENT</strong></th>
		<th colspan='2' bgcolor='#F8F8F8'><strong>DIFFERENCE</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>BATCH NO.</strong></td><td><strong>COUNT</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td bgcolor='#F8F8F8'><strong>BATCH NO.</strong></td><td bgcolor='#F8F8F8'><strong>COUNT</strong></td><td bgcolor='#F8F8F8'><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td>REMITA</td><td>CASHBOOK</td>
		</tr>";
	$month=$bursary->get_any_value('month_code', 'monthtb', 'month_name', $_SESSION['r_m']);
	$sq="SELECT narration AS 'batchno', SUM(amount) AS remita_amount, count(narration) AS sCount  FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND (Ref != '' OR Ref IS NOT NULL) AND narration Not In ('', '0') GROUP BY narration";
	$qq= mysqli_query($con, $sq);
	$total_cb=0; 
	$sn=0; 
	$total_remita=0; 
	$unremittedTotal=0;	
	$cbExcessTotal=0;
	$cString="'0'";
	while($rs= mysqli_fetch_array($qq, 3)){
		if($rs['batchno']=='') continue;
		$unremitted=0;
		$cbExcess=0;

		$rmon=$_SESSION['r_m'];
		$ryea=$_SESSION['r_y'];
		$batchno=$rs['batchno']; 
		$sql = "SELECT paybatch, SUM(amount) AS tSum, count(paybatch) AS tCount FROM transtb WHERE month(transdate)='{$month}' AND year(transdate)='{$_SESSION['r_y']}' AND (paybatch!='' AND paybatch IS NOT NULL) AND paybatch LIKE '%{$batchno}' AND transtype='Debit' GROUP BY paybatch";
		$rcb = mysqli_query($con, $sql);
		$cbc = mysqli_num_rows($rcb);
		$cb = mysqli_fetch_array($rcb, 3);
		
		$cb_amount=abs($cb['tSum']);
		$bref=$cb['paybatch'];

		$total_remita += $rs['remita_amount'];
		$total_cb += $cb_amount;
		
		if($cbc >= 1) {
			$total_remita_matched += $rs['remita_amount'];
			$total_cb_matched += $cb_amount;
			if($rs['remita_amount'] > $cb_amount){
				$unremitted = $rs['remita_amount'] - $cb_amount;
				$unremittedTotal += $unremitted;
			}elseif($rs['remita_amount'] < $cb_amount){
				$cbExcess =  $cb_amount - $rs['remita_amount'];
				$cbExcessTotal += $cbExcess;
			}
			$cString .= ", '{$cb['paybatch']}'";
			++$sn;
			////$s1="UPDATE recon_remitatb SET matched='2', Pay='2' WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND narration='{$batchno}'";
                         	////@mysqli_query($con, $s1);
			//=========> REMITA OUTFLOW
			$bx .= "<tr><td>{$sn}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&cashbook' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
			$amtrx += $rs['remita_amount'];
			$amtbx += $cb_amount;
			$unremitax += $unremitted;
			$uncbx += $cbExcess;

			//=========> CASHBOOK STATEMENT
			$bx .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&cashbook' target='_blank'>{$cb['tCount']}</a></td><td bgcolor='#F8F8F8'>".number_format($cb_amount, 2)."</td><td>{$rmon}/{$ryea}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($cbExcess, 2)."</td></tr>";
		}else{
			$sn2++;
			//==========>LEFT-OVER REMITA
			if($sn2==1) {
				$brl1 = "<tr {$color}><td>{$sn2}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&cashbook' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
			}else {
				$brl2_c .= "<tr {$color}><td>{$sn2}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&cashbook' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
			}
			$total_remita_leftover += $rs['remita_amount'];
			$brl1x = "<td bgcolor='#F8F8F8' colspan='6' rowspan='{$sn2}' valign='top'>";
		}
	}
	//===============> CASHBOOK LEFT-OVER 
	$sqlb="SELECT paybatch, SUM(amount) AS tSum, count(paybatch) AS tCount FROM transtb WHERE paybatch NOT IN ({$cString}) AND month(transdate)='{$month}' AND year(transdate)='{$_SESSION['r_y']}' AND transtype='Debit' GROUP BY paybatch";
	$qryb= mysqli_query($con, $sqlb); $sn=0; $lefttotal=0; $nm= mysqli_num_rows($qryb);
	$brl1x .= "<TABLE width='90%' border='1' rules='rows' align='center'>
	<tr><td><strong>SN</strong></td><td><strong>BATCH NO.</strong></td><td><strong>COUNT</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$snx; 
		$lefttotal += $r['tSum'];
		$brl1x .= "<tr><td>{$snx}</td><td>".$r['paybatch']."</td><td><a href='recreport-mini.php?tid=narration&productcode=".sha1($r['paybatch'])."&month={$rmon}&year={$ryea}&paytype=Debit&cashbook' target='_blank'>{$r['tCount']}</a></td><td>".number_format($r['tSum'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
	}
	$total_cb = $lefttotal + $total_cb_matched;
	$lefttotal = number_format($lefttotal, 2);
	$brl1x .= "<tr><th colspan=3>TOTAL</th><th align='left'>{$lefttotal}</th><td></td></tr></TABLE>";
	//====================================================================================>
	$brl1x .= "</td></tr>";

	$total_remita_matched = number_format($total_remita_matched, 2);
	$total_remita = number_format($total_remita, 2);
	$total_cb_matched = number_format($total_cb_matched, 2);
	//$total_cb = $lefttotal + $total_cb_matched;
	$total_cb = number_format($total_cb, 2);
	/***$tender = number_format($tender, 2);
	$boarded = number_format($boarded, 2);
	$split = number_format($split, 2);
	$others = number_format($others, 2);***/
		
		$btx .= "<tr><td></td><th colspan='2'>MATCHED:</th><th align='left'>".number_format($amtrx, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>".number_format($amtbx, 2)."</th><td></td>
		<th align='left'>".number_format($unremitax, 2)."</th><th align='left'>".number_format($uncbx, 2)."</th>
		</tr>";

		$btl .= "<tr><td></td><th colspan='2'>TOTAL LEFT-OVER:</th><th align='left'>".number_format($total_remita_leftover, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$lefttotal}</th><td></td>
		<th></th><th></th>
		</tr>";

		$bx2 .= "<tr><td></td><th colspan='2'>TOTAL (MATCHED):</th><th align='left'>{$total_remita_matched}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$total_cb_matched}</th><td></td>
		<th align='left'>".number_format($unremittedTotal, 2)."</th><th align='left'>".number_format($cbExcessTotal, 2)."</th>
		</tr>";

		$bx2 .= "<tr><td></td><th colspan='2'>GRAND TOTAL (STATEMENT):</th><th align='left'>{$total_remita}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$total_cb}</th><td></td>
		<th></th><th></th>
		</tr></TABLE>";

	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr>";
		echo $bxh;
		//echo "<tr style='background-color:lightgray;'><td colspan='11'>RECORD MATCHED IN SAME MONTH<hr></td></tr>";
			echo $bx;
			echo $btx;
			echo "<tr style='background-color:lightgray;'><td colspan='11'>REMITANCE FOUND IN OTHER MONTH(S)<hr></td></tr>";
			echo $by;
			echo $bty;
			echo "<tr style='background-color:lightgray;'><td colspan='5'>REMITA LEFT-OVER<hr></td><td colspan='6'>CASHBOOK LEFT-OVER<hr></td></tr>";
			echo $brl1.$brl1x;
			echo $brl2_c;
			echo $btl;
			echo "<tr style='background-color:lightgray;'><td colspan='11'><hr></td></tr>";
			echo $bx2;
		//echo "</td><td>";
		echo "</table><hr>";

}

//====================> OUTFLOW - (REMITA/CBN WITH CASHBOOK)  Monthly Report by Batch No. <========================
if(isset($_POST['btn_rccoutflow']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$bxh = "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='5'><strong>REMITA STATEMENT</strong></th>
		<th colspan='4' bgcolor='#F8F8F8'><strong>CASHBOOK STATEMENT</strong></th>
		<th colspan='2' bgcolor='#F8F8F8'><strong>DIFFERENCE</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>BATCH NO.</strong></td><td><strong>COUNT</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td bgcolor='#F8F8F8'><strong>BATCH NO.</strong></td><td bgcolor='#F8F8F8'><strong>COUNT</strong></td><td bgcolor='#F8F8F8'><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td>REMITA</td><td>CASHBOOK</td>
		</tr>";
	$month=$bursary->get_any_value('month_code', 'monthtb', 'month_name', $_SESSION['r_m']);
	if($_POST['inacct']=='') $fund='';
	else $fund=" AND funding='{$_POST['inacct']}' ";

	$sqX="SELECT narration AS 'batchno', special_ref FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND (special_ref != '' OR special_ref IS NOT NULL) {$fund} AND special_ref != 'No Reference Found' GROUP BY special_ref ORDER BY funding";
	$qqX= mysqli_query($con, $sqX);
	$cStringX="'X'";
	while($rsX= mysqli_fetch_array($qqX, 3)){
		if($rsX['batchno']=='') continue;
		$batchnoX=$rsX['batchno']; 
		$sqlX = "SELECT * FROM recon_banktb WHERE paymentid LIKE '%{$rsX['special_ref']}%' AND paytype='Debit'";
		$rcbX = mysqli_query($con, $sqlX);
		$cbcX = mysqli_num_rows($rcbX);
		if($cbcX >= 1) {
			$cStringX .= ", '{$batchnoX}'";
		}
	}

	$sq="SELECT narration AS 'batchno', SUM(amount) AS remita_amount, count(narration) AS sCount  FROM recon_remitatb WHERE narration IN ({$cStringX}) AND rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND (special_ref != '' OR special_ref IS NOT NULL) {$fund} GROUP BY special_ref ORDER BY funding";
	$qq= mysqli_query($con, $sq);
	$total_cb=0; 
	$sn=0; 
	$total_remita=0; 
	$unremittedTotal=0;	
	$cbExcessTotal=0;
	$cString="'0'";
	while($rs= mysqli_fetch_array($qq, 3)){
		$unremitted=0;
		$cbExcess=0;

		$rmon=$_SESSION['r_m'];
		$ryea=$_SESSION['r_y'];
		$batchno=$rs['batchno']; 
		$sql = "SELECT paybatch, SUM(amount) AS tSum, count(paybatch) AS tCount FROM transtb WHERE month(transdate)='{$month}' AND year(transdate)='{$_SESSION['r_y']}' AND (paybatch!='' AND paybatch IS NOT NULL) AND paybatch LIKE '%{$batchno}' AND transtype='Debit' GROUP BY paybatch";
		$rcb = mysqli_query($con, $sql);
		$cbc = mysqli_num_rows($rcb);
		$cb = mysqli_fetch_array($rcb, 3);
		
		$cb_amount=abs($cb['tSum']);
		$bref=$cb['paybatch'];

		$total_remita += $rs['remita_amount'];
		$total_cb += $cb_amount;
		
		if($cbc >= 1) {
			$total_remita_matched += $rs['remita_amount'];
			$total_cb_matched += $cb_amount;
			if($rs['remita_amount'] > $cb_amount){
				$unremitted = $rs['remita_amount'] - $cb_amount;
				$unremittedTotal += $unremitted;
			}elseif($rs['remita_amount'] < $cb_amount){
				$cbExcess =  $cb_amount - $rs['remita_amount'];
				$cbExcessTotal += $cbExcess;
			}
			$cString .= ", '{$cb['paybatch']}'";
			++$sn;
			////$s1="UPDATE recon_remitatb SET matched='2', Pay='2' WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND narration='{$batchno}'";
                         	////@mysqli_query($con, $s1);
			//=========> REMITA OUTFLOW
			$bx .= "<tr><td>{$sn}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&cashbook' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
			$amtrx += $rs['remita_amount'];
			$amtbx += $cb_amount;
			$unremitax += $unremitted;
			$uncbx += $cbExcess;

			//=========> CASHBOOK STATEMENT
			$bx .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&cashbook' target='_blank'>{$cb['tCount']}</a></td><td bgcolor='#F8F8F8'>".number_format($cb_amount, 2)."</td><td>{$rmon}/{$ryea}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($cbExcess, 2)."</td></tr>";
		}else{
			$sn2++;
			//==========>LEFT-OVER REMITA
			if($sn2==1) {
				$brl1 = "<tr {$color}><td>{$sn2}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&cashbook' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
			}else {
				$brl2_c .= "<tr {$color}><td>{$sn2}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit&cashbook' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
			}
			$total_remita_leftover += $rs['remita_amount'];
			$brl1x = "<td bgcolor='#F8F8F8' colspan='6' rowspan='{$sn2}' valign='top'>";
		}
	}
	//===============> CASHBOOK LEFT-OVER 
	$sqlb="SELECT paybatch, SUM(amount) AS tSum, count(paybatch) AS tCount FROM transtb WHERE paybatch NOT IN ({$cString}) AND month(transdate)='{$month}' AND year(transdate)='{$_SESSION['r_y']}' AND transtype='Debit' GROUP BY paybatch";
	$qryb= mysqli_query($con, $sqlb); $sn=0; $lefttotal=0; $nm= mysqli_num_rows($qryb);
	$brl1x .= "<TABLE width='90%' border='1' rules='rows' align='center'>
	<tr><td><strong>SN</strong></td><td><strong>BATCH NO.</strong></td><td><strong>COUNT</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$snx; 
		$lefttotal += $r['tSum'];
		$brl1x .= "<tr><td>{$snx}</td><td>".$r['paybatch']."</td><td><a href='recreport-mini.php?tid=narration&productcode=".sha1($r['paybatch'])."&month={$rmon}&year={$ryea}&paytype=Debit&cashbook' target='_blank'>{$r['tCount']}</a></td><td>".number_format($r['tSum'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
	}
	$total_cb = $lefttotal + $total_cb_matched;
	$lefttotal = number_format($lefttotal, 2);
	$brl1x .= "<tr><th colspan=3>TOTAL</th><th align='left'>{$lefttotal}</th><td></td></tr></TABLE>";
	//====================================================================================>
	$brl1x .= "</td></tr>";

	$total_remita_matched = number_format($total_remita_matched, 2);
	$total_remita = number_format($total_remita, 2);
	$total_cb_matched = number_format($total_cb_matched, 2);
	//$total_cb = $lefttotal + $total_cb_matched;
	$total_cb = number_format($total_cb, 2);
	/***$tender = number_format($tender, 2);
	$boarded = number_format($boarded, 2);
	$split = number_format($split, 2);
	$others = number_format($others, 2);***/
		
		$btx .= "<tr><td></td><th colspan='2'>MATCHED:</th><th align='left'>".number_format($amtrx, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>".number_format($amtbx, 2)."</th><td></td>
		<th align='left'>".number_format($unremitax, 2)."</th><th align='left'>".number_format($uncbx, 2)."</th>
		</tr>";

		$btl .= "<tr><td></td><th colspan='2'>TOTAL LEFT-OVER:</th><th align='left'>".number_format($total_remita_leftover, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$lefttotal}</th><td></td>
		<th></th><th></th>
		</tr>";

		$bx2 .= "<tr><td></td><th colspan='2'>TOTAL (MATCHED):</th><th align='left'>{$total_remita_matched}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$total_cb_matched}</th><td></td>
		<th align='left'>".number_format($unremittedTotal, 2)."</th><th align='left'>".number_format($cbExcessTotal, 2)."</th>
		</tr>";

		$bx2 .= "<tr><td></td><th colspan='2'>GRAND TOTAL (STATEMENT):</th><th align='left'>{$total_remita}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$total_cb}</th><td></td>
		<th></th><th></th>
		</tr></TABLE>";

	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr>";
		echo $bxh;
		//echo "<tr style='background-color:lightgray;'><td colspan='11'>RECORD MATCHED IN SAME MONTH<hr></td></tr>";
			echo $bx;
			echo $btx;
			echo "<tr style='background-color:lightgray;'><td colspan='11'>REMITANCE FOUND IN OTHER MONTH(S)<hr></td></tr>";
			echo $by;
			echo $bty;
			echo "<tr style='background-color:lightgray;'><td colspan='5'>REMITA LEFT-OVER<hr></td><td colspan='6'>CASHBOOK LEFT-OVER<hr></td></tr>";
			echo $brl1.$brl1x;
			echo $brl2_c;
			echo $btl;
			echo "<tr style='background-color:lightgray;'><td colspan='11'><hr></td></tr>";
			echo $bx2;
		//echo "</td><td>";
		echo "</table><hr>";

}

//====================> FIND BANK LEFT-OVER (INFLOW) IN REMITA BULK-CREDIT. <========================
if(isset($_POST['btn_rem_left']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	echo "<p>FIND BANK LEFT-OVER (INFLOW) IN REMITA BULK-CREDIT</p>
	<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='5'><strong>REMITA STATEMENT</strong></th>
		<th bgcolor='#F8F8F8'><strong>REMITA BULK-CREDIT STATEMENT</strong></th></tr>
		
		<tr>
			<td><strong>SN</strong></td>
			<td><strong>DATE</strong></td>
			<td><strong>PAYMENT ID</strong></td>
			<td><strong>REFERENCE</strong></td>
			<td><strong>AMOUNT</strong></td>
		</tr>";
	$month=$bursary->get_any_value('month_code', 'monthtb', 'month_name', $_SESSION['r_m']);

	$sqX="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (Ref='0' OR Ref is Null) AND paytype='Credit'";
	$qqX = mysqli_query($con, $sqX);
	$cStringX="'X'";
	while($rsX= mysqli_fetch_array($qqX, 3)){

		$payref=explode('-', $rsX['paymentid']);
		$payref=$payref[1];
		$payref=explode('/', $payref);
		$payref=trim($payref[0]);
		
		echo "<tr>
			<td valign='top'>{$sn}</td>
			<td valign='top'>{$_SESSION['r_m']}/{$_SESSION['r_y']}</td>
			<td valign='top'>{$rsX['paymentid']}</td>
			<td valign='top'>{$payref}</td>
			<td valign='top'>".number_format(abs($rsX['amount']), 2)."</td>
			<td valign='top' width='50%' align='left' colspan='5'>";
		
		$sqlX = "SELECT * FROM `recon_remitatb_detail` WHERE monthname(valuedate)='".$_SESSION['r_m']."' AND year(valuedate)='".$_SESSION['r_y']."' AND `bcref` LIKE '%{$payref}%' AND transtype='Credit'";
		$rcbX = mysqli_query($con, $sqlX);
		$cbcX = mysqli_num_rows($rcbX);
		if($cbcX>0){
			echo "<table width='100%' border='1' rules='rows'>";
			echo "<tr><td><strong>REFERENCE</strong></td>
			<td><strong>RRR</strong></td>
			<td><strong>SERVICE TYPE</strong></td>
			<td><strong>AMOUNT</strong></td>
			<td><strong>STATUS</strong></td></tr>";
			while($rs=mysqli_fetch_array($rcbX, 3)){
				
				echo "<tr>
					<td align='left'>{$rs['bcref']}</td>
					<td align='left'>{$rs['rrr']}</td>
					<td align='left'>{$rs['servicetype']}</td>
					<td align='left'>".number_format($rs['amount'], 2)."</td>
					<td align='left'>{$rs['status']}</td>
				</tr>";
			}
			echo "</table>";
		}else{
			echo "NO MATCH FOUND IN REMITA BULK_CREDIT";
		}
		echo "</td></tr>";
	}
	echo "</TABLE>";
}

//====================> FIND BANK LEFT-OVER (OUFLOW) IN REMITA BULK-CREDIT. <========================
if(isset($_POST['btn_rem_left']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	echo "<p>FIND BANK LEFT-OVER (INFLOW) IN REMITA BULK-CREDIT</p>
	<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='5'><strong>REMITA STATEMENT</strong></th>
		<th bgcolor='#F8F8F8'><strong>REMITA BULK-CREDIT STATEMENT</strong></th></tr>
		
		<tr>
			<td><strong>SN</strong></td>
			<td><strong>DATE</strong></td>
			<td><strong>PAYMENT ID</strong></td>
			<td><strong>REFERENCE</strong></td>
			<td><strong>AMOUNT</strong></td>
		</tr>";
	$month=$bursary->get_any_value('month_code', 'monthtb', 'month_name', $_SESSION['r_m']);

	$sqX="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND Ref!='2' AND paytype='Debit'";
	$qqX = mysqli_query($con, $sqX);
	$cStringX="'X'";
	while($rsX= mysqli_fetch_array($qqX, 3)){

		$payref=explode('-', $rsX['paymentid']);
		$payref=$payref[1];
		$payref=explode('/', $payref);
		$payref=trim($payref[0]);
		
		echo "<tr>
			<td valign='top'>{$sn}</td>
			<td valign='top'>{$_SESSION['r_m']}/{$_SESSION['r_y']}</td>
			<td valign='top'>{$rsX['paymentid']}</td>
			<td valign='top'>{$payref}</td>
			<td valign='top'>".number_format(abs($rsX['amount']), 2)."</td>
			<td valign='top' width='50%' align='left' colspan='5'>";
		
		$sqlX = "SELECT * FROM `recon_remitatb_detail` WHERE monthname(valuedate)='".$_SESSION['r_m']."' AND year(valuedate)='".$_SESSION['r_y']."' AND `bcref` LIKE '%{$payref}%' AND transtype='Debit'";
		$rcbX = mysqli_query($con, $sqlX);
		$cbcX = mysqli_num_rows($rcbX);
		if($cbcX>0){
			echo "<table width='100%' border='1' rules='rows'>";
			echo "<tr><td><strong>REFERENCE</strong></td>
			<td><strong>BATCH NO.</strong></td>
			<td><strong>SERVICE TYPE</strong></td>
			<td><strong>AMOUNT</strong></td>
			<td><strong>STATUS</strong></td></tr>";
			while($rs=mysqli_fetch_array($rcbX, 3)){
				
				echo "<tr>
					<td align='left'>{$rs['bcref']}</td>
					<td align='left'>{$rs['rrr']}</td>
					<td align='left'>{$rs['servicetype']}</td>
					<td align='left'>".number_format($rs['amount'], 2)."</td>
					<td align='left'>{$rs['status']}</td>
				</tr>";
			}
			echo "</table>";
		}else{
			echo "NO MATCH FOUND IN REMITA BULK_CREDIT";
		}
		echo "</td></tr>";
	}
	echo "</TABLE>";
}

//====================> COMPARE VARIOUS PAYMENTS WITH REMITA BULK-CREDIT. <========================
if(isset($_POST['btn_rem_various']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	echo "<p>COMPARE VARIOUS PAYMENTS WITH REMITA BULK-CREDIT</p>
	<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='5'><strong>REMITA STATEMENT</strong></th>
		<th bgcolor='#F8F8F8'><strong>REMITA BULK-CREDIT STATEMENT</strong></th></tr>
		
		<tr>
			<td><strong>SN</strong></td>
			<td><strong>DATE</strong></td>
			<td><strong>PAYMENT ID</strong></td>
			<td><strong>REFERENCE</strong></td>
			<td><strong>AMOUNT</strong></td>
			
			
			
		</tr>";
	$month=$bursary->get_any_value('month_code', 'monthtb', 'month_name', $_SESSION['r_m']);
	/*if($_POST['inacct']=='') $fund='';
	else $fund=" AND funding='{$_POST['inacct']}' ";*/

	$sqX="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND `paymentid` LIKE '%various%' ORDER BY id";
	$qqX = mysqli_query($con, $sqX);
	$cStringX="'X'";
	while($rsX= mysqli_fetch_array($qqX, 3)){
		//if($rsX['special_ref']=='') continue;
		$payref=explode('-', $rsX['paymentid']);
		$payref=$payref[1];
		$payref=explode('/', $payref);
		$payref=trim($payref[0]);
		//Account to AccountR-950928608/VARIOUS:98934:Remita Debit
		echo "<tr>
			<td valign='top'>{$sn}</td>
			<td valign='top'>{$_SESSION['r_m']}/{$_SESSION['r_y']}</td>
			<td valign='top'>{$rsX['paymentid']}</td>
			<td valign='top'>{$payref}</td>
			<td valign='top'>".number_format(abs($rsX['amount']), 2)."</td>
			<td valign='top' width='50%' align='left' colspan='5'>";
		
		$sqlX = "SELECT * FROM `recon_remitatb_detail` WHERE monthname(valuedate)='".$_SESSION['r_m']."' AND year(valuedate)='".$_SESSION['r_y']."' AND `bcref` LIKE '%{$payref}%' AND transtype='Debit'";
		$rcbX = mysqli_query($con, $sqlX);
		$cbcX = mysqli_num_rows($rcbX);
		if($cbcX>0){
			echo "<table width='100%' border='1' rules='rows'>";
			echo "<tr><td><strong>REFERENCE</strong></td>
			<td><strong>BATCH NO.</strong></td>
			<td><strong>SERVICE TYPE</strong></td>
			<td><strong>AMOUNT</strong></td>
			<td><strong>STATUS</strong></td></tr>";
			while($rs=mysqli_fetch_array($rcbX, 3)){
				
				echo "<tr>
					<td align='left'>{$rs['bcref']}</td>
					<td align='left'>{$rs['rrr']}</td>
					<td align='left'>{$rs['servicetype']}</td>
					<td align='left'>".number_format($rs['amount'], 2)."</td>
					<td align='left'>{$rs['status']}</td>
				</tr>
				";
			}
			echo "</table>";
		}else{
			echo "NO MATCH FOUND IN REMITA BULK_CREDIT";
		}
		echo "</td></tr>";
	}
	echo "</TABLE>";
}

//====================> OUTFLOW - (REMITA WITH CASHBOOK)  Monthly Report by PVNO <========================
if(isset($_POST['btn_poutflow']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$bxh = "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='5'><strong>REMITA STATEMENT</strong></th>
		<th colspan='4' bgcolor='#F8F8F8'><strong>CASHBOOK STATEMENT</strong></th>
		<th colspan='2' bgcolor='#F8F8F8'><strong>DIFFERENCE</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>BATCH NO.</strong></td><td><strong>COUNT</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td bgcolor='#F8F8F8'><strong>BATCH NO.</strong></td><td bgcolor='#F8F8F8'><strong>COUNT</strong></td><td bgcolor='#F8F8F8'><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td>REMITA</td><td>CASHBOOK</td>
		</tr>";
	$month=$bursary->get_any_value('month_code', 'monthtb', 'month_name', $_SESSION['r_m']);
	$sq="SELECT narration AS 'batchno', SUM(amount) AS remita_amount, count(narration) AS sCount  FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND (Ref != '' OR Ref IS NOT NULL) GROUP BY narration";
	$qq= mysqli_query($con, $sq);
	$total_cb=0; 
	$sn=0; 
	$total_remita=0; 
	$unremittedTotal=0;	
	$cbExcessTotal=0;
	$cString="'0'";
	while($rs= mysqli_fetch_array($qq, 3)){
		if($rs['batchno']=='') continue;
		$unremitted=0;
		$cbExcess=0;

		$rmon=$_SESSION['r_m'];
		$ryea=$_SESSION['r_y'];
		$batchno=$rs['batchno']; 
		$sql = "SELECT paybatch, SUM(amount) AS tSum, count(paybatch) AS tCount FROM transtb WHERE month(transdate)='{$month}' AND year(transdate)='{$_SESSION['r_y']}' AND (paybatch!='' AND paybatch IS NOT NULL) AND paybatch LIKE '%{$batchno}' AND transtype='Debit' GROUP BY paybatch";
		$rcb = mysqli_query($con, $sql);
		$cbc=mysqli_num_rows($rcb);
		$cb = mysqli_fetch_array($rcb, 3);
		
		$cb_amount=abs($cb['tSum']);
		$bref=$cb['paybatch'];

		$total_remita += $rs['remita_amount'];
		$total_cb += $cb_amount;
		
		if($cbc >= 1) {
			$total_remita_matched += $rs['remita_amount'];
			$total_cb_matched += $cb_amount;
			if($rs['remita_amount'] > $cb_amount){
				$unremitted = $rs['remita_amount'] - $cb_amount;
				$unremittedTotal += $unremitted;
			}elseif($rs['remita_amount'] < $cb_amount){
				$cbExcess =  $cb_amount - $rs['remita_amount'];
				$cbExcessTotal += $cbExcess;
			}
			$cString .= ", '{$cb['paybatch']}'";
			++$sn;
			////$s1="UPDATE recon_remitatb SET matched='2', Pay='2' WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND paytype='Debit' AND narration='{$batchno}'";
                         	////@mysqli_query($con, $s1);
			//=========> REMITA OUTFLOW
			$bx .= "<tr><td>{$sn}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
			$amtrx += $rs['remita_amount'];
			$amtbx += $cb_amount;
			$unremitax += $unremitted;
			$uncbx += $cbExcess;

			//=========> BANK STATEMENT
			$bx .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'>".$cb['tCount']."</td><td bgcolor='#F8F8F8'>".number_format($cb_amount, 2)."</td><td>{$rmon}/{$ryea}</td><td>".number_format($unremitted, 2)."</td><td>".number_format($cbExcess, 2)."</td></tr>";
		}else{
			$sn2++;
			//==========>LEFT-OVER REMITA
			if($sn2==1) {
				$brl1 = "<tr {$color}><td>{$sn2}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td>";
			}else {
				$brl2_c .= "<tr {$color}><td>{$sn2}</td><td>{$batchno}</td><td align='left'><a href='recreport-mini.php?tid=narration&productcode=".sha1($batchno)."&month={$_SESSION['r_m']}&year={$_SESSION['r_y']}&paytype=Debit' target='_blank'>{$rs['sCount']}</a></td><td>".number_format($rs['remita_amount'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
			}
			$total_remita_leftover += $rs['remita_amount'];
			$brl1x = "<td bgcolor='#F8F8F8' colspan='6' rowspan='{$sn2}' valign='top'>";
		}
	}
	//===============> BANK LEFT-OVER 
	$sqlb="SELECT paybatch, SUM(amount) AS tSum, count(paybatch) AS tCount FROM transtb WHERE paybatch NOT IN ({$cString}) AND month(transdate)='{$month}' AND year(transdate)='{$_SESSION['r_y']}' AND transtype='Debit' GROUP BY paybatch";
	$qryb= mysqli_query($con, $sqlb); $sn=0; $lefttotal=0; $nm= mysqli_num_rows($qryb);
	$brl1x .= "<TABLE width='90%' border='1' rules='rows' align='center'>
	<tr><td><strong>SN</strong></td><td><strong>BATCH NO.</strong></td><td><strong>COUNT</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){
		++$snx; 
		$lefttotal += $r['tSum'];
		$brl1x .= "<tr><td>{$snx}</td><td>".$r['paybatch']."</td><td><a href='recreport-mini.php?tid=narration&productcode=".sha1($r['paybatch'])."&month={$rmon}&year={$ryea}&paytype=Debit' target='_blank'>{$r['tCount']}</a></td><td>".number_format($r['tSum'], 2)."</td><td>{$rmon}/{$ryea}</td></tr>";
	}
	$total_cb = $lefttotal + $total_cb_matched;
	$lefttotal = number_format($lefttotal, 2);
	$brl1x .= "<tr><th colspan=3>TOTAL</th><th align='left'>{$lefttotal}</th><td></td></tr></TABLE>";
	//====================================================================================>
	$brl1x .= "</td></tr>";

	$total_remita_matched = number_format($total_remita_matched, 2);
	$total_remita = number_format($total_remita, 2);
	$total_cb_matched = number_format($total_cb_matched, 2);
	//$total_cb = $lefttotal + $total_cb_matched;
	$total_cb = number_format($total_cb, 2);
	/***$tender = number_format($tender, 2);
	$boarded = number_format($boarded, 2);
	$split = number_format($split, 2);
	$others = number_format($others, 2);***/
		
		$btx .= "<tr><td></td><th colspan='2'>MATCHED:</th><th align='left'>".number_format($amtrx, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>".number_format($amtbx, 2)."</th><td></td>
		<th align='left'>".number_format($unremitax, 2)."</th><th align='left'>".number_format($uncbx, 2)."</th>
		</tr>";

		$btl .= "<tr><td></td><th colspan='2'>TOTAL LEFT-OVER:</th><th align='left'>".number_format($total_remita_leftover, 2)."</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$lefttotal}</th><td></td>
		<th></th><th></th>
		</tr>";

		$bx2 .= "<tr><td></td><th colspan='2'>TOTAL (MATCHED):</th><th align='left'>{$total_remita_matched}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$total_cb_matched}</th><td></td>
		<th align='left'>".number_format($unremittedTotal, 2)."</th><th align='left'>".number_format($cbExcessTotal, 2)."</th>
		</tr>";

		$bx2 .= "<tr><td></td><th colspan='2'>GRAND TOTAL (STATEMENT):</th><th align='left'>{$total_remita}</th><td></td>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8' align='left'>{$total_cb}</th><td></td>
		<th></th><th></th>
		</tr></TABLE>";

	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr>";
		echo $bxh;
		//echo "<tr style='background-color:lightgray;'><td colspan='11'>RECORD MATCHED IN SAME MONTH<hr></td></tr>";
			echo $bx;
			echo $btx;
			echo "<tr style='background-color:lightgray;'><td colspan='11'>REMITANCE FOUND IN OTHER MONTH(S)<hr></td></tr>";
			echo $by;
			echo $bty;
			echo "<tr style='background-color:lightgray;'><td colspan='5'>REMITA LEFT-OVER<hr></td><td colspan='6'>CASHBOOK LEFT-OVER<hr></td></tr>";
			echo $brl1.$brl1x;
			echo $brl2_c;
			echo $btl;
			echo "<tr style='background-color:lightgray;'><td colspan='11'><hr></td></tr>";
			echo $bx2;
		//echo "</td><td>";
		echo "</table><hr>";

}

//==============> UNCREDITED LODGMENT <========================
if(isset($_POST['btn_ucr']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (matched=0 OR matched is Null) AND paytype='Credit'";
	$qryb= mysqli_query($con, $sqlb); $sn=0; $total=0; $nm= mysqli_num_rows($qryb);
	//$_SESSION['uncreadited_lodgment']=
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} UNCREDITED LODGMENT [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){++$sn; $total += $r['amount'];
		//$_SESSION['uncreadited_lodgment'].=
		echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>".str_replace('\\\\', '\\', $r['special_ref'])."</td><td>".number_format($r['amount'], 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	//$_SESSION['uncreadited_lodgment'].=
	echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="uncreadited lodgment";
}

//==============> CONTROL REPORT <========================
if(isset($_POST['btn_ctrl']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sqlr="SELECT * FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (matched=0 OR matched is Null) AND paytype='Credit'";
	$qryr= mysqli_query($con, $sqlr); $sn=0; $total=0; $nm= mysqli_num_rows($qryr);
	//$_SESSION['control_report']=
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} CONTROL REPORT [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryr, 3 )){++$sn; $total += $r['amount'];
		//$_SESSION['control_report'].=
		echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>{$r[special_ref]}</td><td>".number_format($r['amount'], 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	//$_SESSION['control_report'].=
	echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="control report";
}


if(isset($_POST['btn_ctrl_ref']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	$sql="SELECT * FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND (matched=0 OR matched is Null) AND paytype='Credit' ORDER BY special_ref";
	$qry= mysqli_query($con, $sql); $sn=0; $total_remita=0; $nx= mysqli_num_rows($qry);
	
	$sql2="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' AND ryear='".$_SESSION['r_y']."' AND (matched=0 OR matched is Null) AND paytype='Credit'";
	$qry2= mysqli_query($con, $sql2); $total_bank=0; $ny= mysqli_num_rows($qry2);

	$bx .= "<TABLE width='100%' border='1'>
	<tr><th colspan='4'><strong>REMITA STATEMENT [{$nx}]</strong></th>
	<th colspan='3' bgcolor='#F8F8F8'><strong>BANK STATEMENT [{$ny}]</strong></th></tr>
	
	<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td>
	<td bgcolor='#F8F8F8'><strong>TRANS. REF.</strong></td><td bgcolor='#F8F8F8'><strong>DESCRIPTION</strong></td><td bgcolor='#F8F8F8'><strong>AMOUNT</strong></td>
	</tr>";

	while($r= mysqli_fetch_array($qry, 3 )){
		++$sn; 
		///$bref=$bursary->get_any_value('credit_reference', 'recon_banktb', 'matched', '0', " special_ref = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount!={$r['amount']}");
		$bref=$bursary->get_any_value('special_ref', 'recon_banktb', 'matched', '0', " special_ref = '".$r['special_ref']."'");
		if($bref=='' or $bref=='Error!') continue;
		$total_remita += $r['amount'];
		$bx .= "<tr><td>{$sn}</td>
		<td>{$r[credit_reference]}</td><td>{$r[paymentid]}</td><td align='right'>".number_format($r['amount'], 2)."</td>";
		
		///$bank_amount=$bursary->get_any_value('amount', 'recon_banktb', 'matched', '0', " AND credit_reference = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount!={$r['amount']}");
		$bank_amount=$bursary->get_any_value('amount', 'recon_banktb', 'matched', '0', " AND special_ref = '".$r['special_ref']."'");
		$total_bank += $bank_amount;
		
		///$bx .= "<td bgcolor='#F8F8F8'>".str_replace('\\\\','\\',$bref)."</td><td bgcolor='#F8F8F8'>".$bursary->get_any_value('paymentid', 'recon_banktb', 'matched', '0', " AND credit_reference = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount!={$r['amount']}")."</td><td bgcolor='#F8F8F8' align='right'>".number_format($bank_amount, 2)."</td>
		$bx .= "<td bgcolor='#F8F8F8'>".$bref."</td><td bgcolor='#F8F8F8'>".$bursary->get_any_value('paymentid', 'recon_banktb', 'matched', '0', " AND special_ref = '".$r['special_ref']."'")."</td><td bgcolor='#F8F8F8' align='right'>".number_format($bank_amount, 2)."</td>
		</tr>";
	}

	$total_remita = number_format($total_remita, 2);
	$total_bank = number_format($total_bank, 2);

	/*echo */ $bx .= "<tr><td></td><td></td><th>TOTAL:</th><th align='left'>{$total_remita}</th>
		<td bgcolor='#F8F8F8'></td><th bgcolor='#F8F8F8'></th><th bgcolor='#F8F8F8'>{$total_bank}</th>
		</tr></TABLE>";
		///echo "</td><td>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>REPORT FOR SAME REFERENCE/DIFFERENT AMOUNT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
			echo $bx;
		echo "</td><td>";
			echo $by;
		echo "<td valign='top'></tr></table><hr>";

}

  ?>
</center>
<p>&nbsp;</p>
</body>
</html>
