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

if(!isset($_REQUEST['paytype']) || $_REQUEST['paytype']=='') $_REQUEST['paytype']='Credit';
 if(isset($_REQUEST['month']) and $_REQUEST['month']!='' and isset($_REQUEST['year']) and $_REQUEST['year']!='') {
	$rmonth=$_REQUEST['month'];
	$ryear=$_REQUEST['year'];
}
if(isset($_REQUEST['productcode']) and $_REQUEST['productcode']!='' && $_REQUEST['tid']=="special_ref2") {
	$purpose=strtoupper($bursary->get_any_value('purpose', 'recon_remitatb', 'Pay', '1', " AND sha1(special_ref2) = '{$_REQUEST['productcode']}'"));
}else 
	$purpose=strtoupper($bursary->get_any_value('purpose', 'recon_remitatb', 'sha1(special_ref)', $_REQUEST['productcode']));
	if($_REQUEST['paytype']=="Debit") $purpose='';
if(isset($_REQUEST['cashbook'])) echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/></center><b><p align='center'>EXPANDED REPORT FOR REMITA/CASHBOOK RECONCILED <br>".strtoupper($rmonth).", {$ryear}</p></b><hr><p>";
elseif(isset($_REQUEST['matchedOutflow'])) echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/></center><b><p align='center'>EXPANDED REPORT FOR REMITA/BANK STATEMENT RECONCILED <br>".strtoupper($rmonth).", {$ryear}</p></b><hr><p>";
else echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/></center><b><p align='center'>EXPANDED REPORT FOR <br>{$purpose} <br>".strtoupper($rmonth).", {$ryear}</p></b><hr><p>";

///////////////////////////////////////////////////// End of header ////////////////////////////////////////////

  ?>
<center>
  <?php
//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////

//==============> ITEMS WITH DESCRIPTION FROM ITEM-CODE RECONCILLIATION <========================
if(isset($_REQUEST['month']) and $_REQUEST['month']!='' and isset($_REQUEST['year']) and $_REQUEST['year']!='' && isset($_REQUEST['noDesc2'])){
	$bx .= "<TABLE width='100%' border='1' rules='rows' cellpadding='5px'>
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td>
		</tr>";
	$sq="SELECT * FROM recon_remitatb WHERE ryear='".$ryear."' AND rmonth='".$rmonth."' AND paytype='Credit' AND (nodesc is null OR nodesc = '') AND Pay='1' ORDER BY special_ref2";
	$qq= mysqli_query($con, $sq);
	$total_b=0; 
	$sn=0; 
	$total_r=0; 
	$unremittedTotal=0;	$bankExcessTotal=0;
	while($rs= mysqli_fetch_array($qq, 3)){
		$total_r += $rs['amount'];
		++$sn;
		$bx .= "<tr {$color}><td>{$sn}</td>
		<td>{$rs['special_ref2']}</td><td align='left'>{$rs['narration']}</td><td>".number_format($rs['amount'], 2)."</td></tr>";
	}

	$total_r = number_format($total_r, 2);

	/*echo */ $bx .= "<tr><td></td><td></td><th>TOTAL:</th><th>{$total_r}</th>
		<td style='background-color:#CECECE;'></td>
		</tr></TABLE>";
		///echo "</td><td>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>ITEMS WITH DESCRIPTION FROM ITEM-CODE RECONCILLIATION FOR ".strtoupper($rmonth).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
			echo $bx;
		echo "</td><td>";
			echo $by;
		echo "<td valign='top'></tr></table><hr>";
	exit;
}

//==============> ITEMS WITHOUT DESCRIPTION FROM ITEM-CODE RECONCILLIATION <========================
if(isset($_REQUEST['month']) and $_REQUEST['month']!='' and isset($_REQUEST['year']) and $_REQUEST['year']!='' && isset($_REQUEST['noDesc'])){
	$bx .= "<TABLE width='100%' border='1' rules='rows' cellpadding='5px'>
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td>
		</tr>";
	$sq="SELECT * FROM recon_remitatb WHERE ryear='".$ryear."' AND rmonth='".$rmonth."' AND paytype='Credit' AND nodesc='1' ORDER BY special_ref2";
	$qq= mysqli_query($con, $sq);
	$total_b=0; 
	$sn=0; 
	$total_r=0; 
	$unremittedTotal=0;	$bankExcessTotal=0;
	while($rs= mysqli_fetch_array($qq, 3)){
		$total_r += $rs['amount'];
		++$sn;
		$bx .= "<tr {$color}><td>{$sn}</td>
		<td>{$rs['special_ref2']}</td><td align='left'>{$rs['narration']}</td><td>".number_format($rs['amount'], 2)."</td></tr>";
	}

	$total_r = number_format($total_r, 2);

	/*echo */ $bx .= "<tr><td></td><td></td><th>TOTAL:</th><th>{$total_r}</th>
		<td style='background-color:#CECECE;'></td>
		</tr></TABLE>";
		///echo "</td><td>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>ITEMS WITHOUT DESCRIPTION FROM ITEM-CODE RECONCILLIATION FOR ".strtoupper($rmonth).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
			echo $bx;
		echo "</td><td>";
			echo $by;
		echo "<td valign='top'></tr></table><hr>";
	exit;
}

//==============> EXPANDED REPORT FOR REMITA/BANK STATEMENT RECONCILED <========================
if(isset($_REQUEST['month']) and $_REQUEST['month']!='' and isset($_REQUEST['year']) and $_REQUEST['year']!='' && (isset($_REQUEST['matchedOutflow']))){
	echo $bx = "<TABLE width='100%' border='1' rules='rows'>
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>PVNO.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td><td><strong>PAYEE</strong></td><td><strong>BATCH</strong></td></tr>";
		$sq="SELECT r.* FROM recon_remitatb r INNER JOIN recon_banktb b ON r.special_ref=b.special_ref WHERE r.matched='2' AND r.Ref='2' AND (r.rmonth='".$rmonth."' AND r.ryear='".$ryear."') AND (b.rmonth='".$rmonth."' AND b.ryear='".$ryear."') AND r.paytype='{$_REQUEST['paytype']}'";
	$qq= mysqli_query($con, $sq);
	$sn=0; 
	$total_r=0; 
	
	while($rs= mysqli_fetch_array($qq, 3)){
		++$sn;
		$total_r += ($rs['amount']);
		if(isset($_REQUEST['viewrecord'])) {
			$rec=explode('-', $rs['payer']);
			$red=explode(' ', trim($rec[1]));
			$rsspecial_ref = trim($red[0]);
		}else $rsspecial_ref=$rs['special_ref2'];
		echo "<tr><td>{$sn} </td><td>{$rs['special_ref']}</td><td align='left'>{$rsspecial_ref}</td><td>{$rs['purpose']}</td><td>".number_format($rs['amount'], 2)."</td><td>{$rmonth}/{$ryear}</td><td>{$rs['payer']}</td><td>{$rs['narration']}</td></tr>";
	}
	
	$Excess=$total_r - $total_b;
	$total_r = number_format($total_r, 2);
	echo $bx2 = "<tr><th colspan='3'>TOTAL: </th><th colspan='2'> REMITANCE<br>{$total_r}</th><td></td><td></td><th></th></tr>";
	echo "</table>";
	exit;

}

//==============> REMITA/Cashbook Report [By Batch No.] <========================
if(isset($_REQUEST['cashbook']) and isset($_REQUEST['month']) and $_REQUEST['month']!='' and isset($_REQUEST['year']) and $_REQUEST['year']!='' && (isset($_REQUEST['productcode']) and $_REQUEST['productcode']!='')){
	echo $bx = "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='6'><strong>REMITA STATEMENT</strong></th>
		<th colspan='5' style='background-color:#CECECE;'><strong>CASHBOOK STATEMENT</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>BATCH NO.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td style='background-color:#CECECE;'>
		<strong>BATCH NO.</strong></td><td style='background-color:#CECECE;'><strong>PVNO.</strong></td><td style='background-color:#CECECE;'><strong>DESCRIPTION</strong></td><td style='background-color:#CECECE;'><strong>AMOUNT</strong></td><td style='background-color:#CECECE;'><strong>PERIOD</strong></td>
		</tr>";
	$sq="SELECT * FROM recon_remitatb WHERE sha1({$_REQUEST['tid']}) = '{$_REQUEST['productcode']}' AND rmonth='".$rmonth."' AND ryear='".$ryear."' AND paytype='{$_REQUEST['paytype']}' ORDER BY {$_REQUEST['tid']}";
	$qq= mysqli_query($con, $sq);
	$cash_count = mysqli_num_rows($qq);
	$total_b=0; 
	$sn=0; 
	$total_r=0; 
	$unremittedTotal=0;	
	$bankExcessTotal=0;
	$sql = "SELECT paybatch, amount, pvno, payee, MONTHNAME(transdate) AS rmonth, YEAR(transdate) AS ryear, f.title FROM transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE MONTHNAME(transdate)='{$rmonth}' AND year(transdate)='{$_SESSION['r_y']}' AND (sha1(paybatch) LIKE '%{$_REQUEST['productcode']}' OR sha1(paybatch) LIKE '{$_REQUEST['productcode']}') AND transtype='Debit'";
	$rbnk = mysqli_query($con, $sql);
	$bnkc=mysqli_num_rows($rbnk);
	while($bnk = mysqli_fetch_array($rbnk, 3)){
		$bamount=abs($bnk['amount']);
		$total_b += abs($bnk['amount']);
		$batch=$bnk['paybatch'];
		$pvno=$bnk['pvno'];
		$desc=$bnk['title'];
		$payee=$bnk['payee'];
		//if($desc == '') $desc = $bnk['comment'];
		$bank[] = $cashtr = "<td style='background-color:#CECECE;'>".$batch."</td><td style='background-color:#CECECE;'>".$pvno."</td><td style='background-color:#CECECE;'>".$desc." Paid to {$payee}</td><td style='background-color:#CECECE;'>".number_format($bamount, 2)."</td><td style='background-color:#CECECE;'>{$bnk['rmonth']}/{$bnk['ryear']}</td></tr>";
		if($cash_count <= 0) echo "<tr><td>".++$snc." </td><td colspan='5'></td>".$cashtr;
	}
	
	while($rs= mysqli_fetch_array($qq, 3)){
		++$sn;
		$total_r += ($rs['amount']);
		$batch2=$rs['narration'];
		echo "<tr><td>{$sn} </td><td> {$rs['special_ref']}</td><td>".$batch2."</td><td align='left'>({$rs['special_ref2']}) {$rs['purpose']}</td><td>".number_format($rs['amount'], 2)."</td><td>{$rmonth}/{$ryear}</td>";
		if($sn <= count($bank)) echo $bank[$sn-1]; else echo "<td colspan='5' style='background-color:#CECECE;'></td></tr>";
	}
	
	$Excess=$total_r - $total_b;
	$total_r = number_format($total_r, 2);
	$total_b = number_format($total_b, 2);
	echo $bx2 = "<tr><th colspan='3'>TOTAL </th><th colspan='2'> PAID ON REMITA<br>{$total_r}</th><td></td><td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;' colspan='3'> IN CASHBOOK<br>{$total_b}</th></tr>";
	if($Excess > 0) 
		echo $bx2 = "<tr><th colspan='3'>REMITA EXCESS: </th><th colspan='2'>".number_format($Excess, 2)."</th><td></td><td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;' colspan='3'></th></tr>";
	elseif($Excess < 0) 
		echo $bx2 = "<tr><th colspan='3'>CASHBOOK EXCESS: </th><th colspan='2'></th><td></td><td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;' colspan='3'>".number_format(abs($Excess), 2)."</th></tr>";
	else
		echo $bx2 = "<tr><th colspan='3'>BANLANCE (NO DIFFERENCE) </th><th colspan='4'></th><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;' colspan='3'></th></tr>";
	echo "</table>";
	exit;

}

//==============> Report [By Credit Reference] <========================
if(isset($_REQUEST['month']) and $_REQUEST['month']!='' and isset($_REQUEST['year']) and $_REQUEST['year']!='' && (isset($_REQUEST['productcode']) and $_REQUEST['productcode']!='')){
	echo $bx = "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='6'><strong>REMITA STATEMENT</strong></th>
		<th colspan='4' style='background-color:#CECECE;'><strong>BANK STATEMENT</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>PURPOSE</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td>
		<td style='background-color:#CECECE;'><strong>TRANS. REF.</strong></td><td style='background-color:#CECECE;'><strong>DESCRIPTION</strong></td><td style='background-color:#CECECE;'><strong>AMOUNT</strong></td><td style='background-color:#CECECE;'><strong>PERIOD</strong></td>
		</tr>";
	$sq="SELECT * FROM recon_remitatb WHERE sha1({$_REQUEST['tid']}) = '{$_REQUEST['productcode']}' AND rmonth='".$rmonth."' AND ryear='".$ryear."' AND paytype='{$_REQUEST['paytype']}' ORDER BY {$_REQUEST['tid']}";
	if($_REQUEST['tid']=="special_ref2")
		$sq="SELECT * FROM recon_remitatb WHERE Pay='1' AND sha1({$_REQUEST['tid']}) = '{$_REQUEST['productcode']}' AND rmonth='".$rmonth."' AND ryear='".$ryear."' AND paytype='{$_REQUEST['paytype']}' ORDER BY special_ref";
	if($_REQUEST['tid']=="special_ref")
		$sq="SELECT * FROM recon_remitatb WHERE sha1({$_REQUEST['tid']}) = '{$_REQUEST['productcode']}' AND rmonth='".$rmonth."' AND ryear='".$ryear."' AND paytype='{$_REQUEST['paytype']}' ORDER BY special_ref";
	$qq= mysqli_query($con, $sq);
	$total_b=0; 
	$sn=0; 
	$total_r=0; 
	$unremittedTotal=0;	
	$bankExcessTotal=0;
	if($_REQUEST['tid']=="special_ref2")
		$rbnk = mysqli_query($con, "SELECT * FROM recon_banktb WHERE Pay='1' AND sha1({$_REQUEST['tid']}) = '{$_REQUEST['productcode']}' AND rmonth='".$rmonth."' AND ryear='".$ryear."' AND paytype='{$_REQUEST['paytype']}' ORDER BY special_ref");
	elseif($_REQUEST['tid']=="special_ref")
		$rbnk = mysqli_query($con, "SELECT * FROM recon_banktb WHERE sha1({$_REQUEST['tid']}) = '{$_REQUEST['productcode']}' AND paytype='{$_REQUEST['paytype']}' ORDER BY special_ref");
	$bnkc=mysqli_num_rows($rbnk);
	while($bnk = mysqli_fetch_array($rbnk, 3)){
		$bamount=abs($bnk['amount']);
		$total_b += abs($bnk['amount']);
		$bref=$bnk['special_ref'];
		$desc=$bnk['paymentid'];//."-".$bnk['payer'];
		$bank[] = "<td style='background-color:#CECECE;'>".$bref."</td><td style='background-color:#CECECE;'>".$desc."</td><td style='background-color:#CECECE;'>".number_format($bamount, 2)."</td><td style='background-color:#CECECE;'>{$bnk['rmonth']}/{$bnk['ryear']}</td></tr>";
	}
	
	while($rs= mysqli_fetch_array($qq, 3)){
		++$sn;
		$total_r += ($rs['amount']);
		echo "<tr><td>{$sn} </td><td> {$rs['special_ref']}</td><td align='left'>({$rs['special_ref2']}) {$rs['purpose']}</td><td>{$rs['payer']}</td><td>".number_format($rs['amount'], 2)."</td><td>{$rmonth}/{$ryear}</td>";
		if($sn <= count($bank)) echo $bank[$sn-1]; else echo "<td colspan='4'></tr>";
	}
	
	$Excess=$total_r - $total_b;
	$total_r = number_format($total_r, 2);
	$total_b = number_format($total_b, 2);
	echo $bx2 = "<tr><th colspan='3'>TOTAL ({$purpose}) </th><th colspan='2'> REMITANCE<br>{$total_r}</th><td></td><td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;' colspan='2'>IN-BANK<br>{$total_b}</th></tr>";
	if($Excess > 0) 
		echo $bx2 = "<tr><th colspan='3'>UN-CREDITTED LODGEMENT: </th><th colspan='2'>".number_format($Excess, 2)."</th><td></td><td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;' colspan='2'></th></tr>";
	elseif($Excess < 0) 
		echo $bx2 = "<tr><th colspan='3'>EXCESS BANK CREDIT: </th><th colspan='2'></th><td></td><td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;' colspan='2'>".number_format(abs($Excess), 2)."</th></tr>";
	else
		echo $bx2 = "<tr><th colspan='3'>BANLANCE (NO DIFFERENCE) </th><th colspan='4'></th><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;' colspan='2'></th></tr>";
	echo "</table>";
	exit;

}

//==============> REMITA LEFT-OVER [Credit Reference] <========================
if(isset($_POST['btn_rlo']) and isset($rmonth) and $rmonth!='' and isset($ryear) and $ryear!='' ){
	$sqlb="SELECT * FROM recon_remitatb WHERE rmonth='".$rmonth."' and ryear='".$ryear."' and (Ref='0' OR Ref is Null) AND paytype='Credit'";
	$qryb= mysqli_query($con, $sqlb); $sn=0; $total=0; $nm= mysqli_num_rows($qryb);
	//$_SESSION['uncreadited_lodgment']=
	echo "<TABLE width='100%' border='1' rules='rows'>
	<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} REMITA LEFT-OVER [{$nm}]</h5></th></tr>
	<tr><td><strong>SN</strong></td><td>PERIOD</td><td><strong>DESCRIPTION</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
	while($r= mysqli_fetch_array($qryb, 3 )){++$sn; $total += $r['amount'];
		//$_SESSION['uncreadited_lodgment'].=
		echo "<tr><td>{$sn}</td><td>{$r['rmonth']}/{$r['ryear']}</td><td>{$r[paymentid]}</td><td>".$r['special_ref']."</td><td>".number_format($r['amount'], 2)."</td></tr>";
	}
	$total = number_format($total, 2);
	//$_SESSION['uncreadited_lodgment'].=
	echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
	$_SESSION['input_method']="uncreadited lodgment";
}

//==============> BANK LEFT-OVER [Credit Reference] <========================
if(isset($_POST['btn_clo']) and isset($rmonth) and $rmonth!='' and isset($ryear) and $ryear!='' ){
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$rmonth."' and ryear='".$ryear."' and (Ref='0' OR Ref is Null) AND paytype='Credit'";
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
if(isset($_POST['btn_pcr']) and isset($rmonth) and $rmonth!='' and isset($ryear) and $ryear!='' ){
	$bx .= "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><strong>REMITA STATEMENT</strong></th>
		<th colspan='4' style='background-color:#CECECE;'><strong>BANK STATEMENT</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td>
		<td style='background-color:#CECECE;'><strong>TRANS. REF.</strong></td><td style='background-color:#CECECE;'><strong>AMOUNT</strong></td>
		<td>UN-CREDITED</td><td>BANK EXCESS</td>
		</tr>";
	/////$sq="SELECT DISTINCT special_ref2, purpose FROM recon_remitatb WHERE Pay='1' AND rmonth='".$rmonth."' AND ryear='".$ryear."' AND paytype='Credit'";
	$sq="SELECT DISTINCT special_ref2, purpose, sum(amount) AS rSum, count(special_ref2) AS rCount FROM recon_remitatb WHERE Pay='1' AND rmonth='".$rmonth."' AND ryear='".$ryear."' AND paytype='Credit' GROUP by special_ref2, purpose ORDER BY `recon_remitatb`.`purpose` ASC";
	$qq= mysqli_query($con, $sq);
	$total_b=0; 
	$sn=0; 
	$total_r=0; 
	$unremittedTotal=0;	$bankExcessTotal=0;
	while($rs= mysqli_fetch_array($qq, 3)){
		$sql="SELECT SUM(amount) AS bSum, count(special_ref2) AS bCount FROM recon_banktb WHERE Pay='1' AND special_ref2='{$rs['special_ref2']}' AND rmonth='".$rmonth."' AND ryear='".$ryear."'";
		$qry= mysqli_query($con, $sql); 
		
		/////$nx= mysqli_num_rows($qry);

		$unremitted=0;	
		$bankExcess=0;
		$total_r += $rs['rSum'];
		while($r= mysqli_fetch_array($qry, 3 )){
			++$sn; 
			$bamount=$r['bSum'];
			$total_b += $r['bSum']; ////$bamount;
			if($rs['rSum'] > $bamount){
				$unremitted = $rs['rSum'] - $bamount;
				$unremittedTotal += $unremitted;
			}elseif($rs['rSum'] < $bamount){
				$bankExcess =  $bamount - $rs['rSum'];
				$bankExcessTotal += $bankExcess;
			}
			if($rs['rSum']-$r['bSum']==0) $color=" style='background-color:lightblue; color:darkgreen;' ";
			else $color='';
			$bx .= "<tr {$color}><td>{$sn}</td>
				<td>{$rs['special_ref2']}</td><td align='left'><a href='#'>({$rs['rCount']}) {$rs['purpose']}</a></td><td>".number_format($rs['rSum'], 2)."</td>"
				. "<td><a href='#'>({$r['bCount']})</a> </td><td>".number_format($r['bSum'], 2)."</td>"
				. "<td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";
			
			////$bamount=$bursary->get_any_value('amount', 'recon_banktb', 'Pay', '1', " AND special_ref2 = '{$r['special_ref2']}'");
			


			/*$bref=$bursary->get_any_value('special_ref2', 'recon_banktb', 'Pay', '1', " AND special_ref2 = '{$r['special_ref2']}'");
			$desc=$bursary->get_any_value('paymentid', 'recon_banktb', 'Pay', '1', " AND special_ref2 = '".$r['special_ref2']."'");
			$bmonth=$bursary->get_any_value('rmonth', 'recon_banktb', 'Pay', '1', " AND special_ref2 = '".$r['special_ref2']."'");
			$byear=$bursary->get_any_value('ryear', 'recon_banktb', 'Pay', '1', " AND special_ref2 = '".$r['special_ref2']."'");
			
			$bx .= "<td>".number_format($unremitted, 2)."</td><td>".number_format($bankExcess, 2)."</td></tr>";*/
		}
	}

	$total_r = number_format($total_r, 2);
	$total_b = number_format($total_b, 2);

	/*echo */ $bx .= "<tr><td></td><td></td><th>TOTAL:</th><th>{$total_r}</th>
		<td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'>{$total_b}</th>
		<th>".number_format($unremittedTotal, 2)."</th><th>".number_format($bankExcessTotal, 2)."</th>
		</tr></TABLE>";
		///echo "</td><td>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($rmonth).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
			echo $bx;
		echo "</td><td>";
			echo $by;
		echo "<td valign='top'></tr></table><hr>";

}

//==============> Annual Report [By Payment Code] <========================
if(isset($_POST['btn_apcr']) and isset($ryear) and $ryear!='' ){
	$bx .= "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><strong>REMITA STATEMENT</strong></th>
		<th colspan='4' style='background-color:#CECECE;'><strong>BANK STATEMENT</strong></th></tr>
		
		<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td>
		<td style='background-color:#CECECE;'><strong>TRANS. REF.</strong></td><td style='background-color:#CECECE;'><strong>AMOUNT</strong></td>
		<td>UN-CREDITED</td><td>BANK EXCESS</td>
		</tr>";
	$sq="SELECT DISTINCT special_ref2, purpose, sum(amount) AS rSum, count(special_ref2) AS rCount FROM recon_remitatb WHERE Pay='1' AND ryear='".$ryear."' AND paytype='Credit' GROUP by special_ref2, purpose ORDER BY `recon_remitatb`.`purpose` ASC";
	$qq= mysqli_query($con, $sq);
	$total_b=0; 
	$sn=0; 
	$total_r=0; 
	$unremittedTotal=0;	$bankExcessTotal=0;
	while($rs= mysqli_fetch_array($qq, 3)){
		$sql="SELECT SUM(amount) AS bSum, count(special_ref2) AS bCount FROM recon_banktb WHERE Pay='1' AND special_ref2='{$rs['special_ref2']}' AND ryear='".$ryear."'";
		$qry= mysqli_query($con, $sql); 

		$unremitted=0;	
		$bankExcess=0;
		$total_r += $rs['rSum'];
		while($r= mysqli_fetch_array($qry, 3 )){
			++$sn; 
			$bamount=$r['bSum'];
			$total_b += $r['bSum']; 
			if($rs['rSum'] > $bamount){
				$unremitted = $rs['rSum'] - $bamount;
				$unremittedTotal += $unremitted;
			}elseif($rs['rSum'] < $bamount){
				$bankExcess =  $bamount - $rs['rSum'];
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

	$total_r = number_format($total_r, 2);
	$total_b = number_format($total_b, 2);

	/*echo */ $bx .= "<tr><td></td><td></td><th>TOTAL:</th><th>{$total_r}</th>
		<td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'>{$total_b}</th>
		<th>".number_format($unremittedTotal, 2)."</th><th>".number_format($bankExcessTotal, 2)."</th>
		</tr></TABLE>";
		///echo "</td><td>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($rmonth).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
			echo $bx;
		echo "</td><td>";
			echo $by;
		echo "<td valign='top'></tr></table><hr>";

}

//==============> REMITA LEFT-OVER [By Payment Code] <========================
if(isset($_POST['btn_prlo']) and isset($rmonth) and $rmonth!='' and isset($ryear) and $ryear!='' ){
	$sqlb="SELECT * FROM recon_remitatb WHERE rmonth='".$rmonth."' and ryear='".$ryear."' and (Pay='0' OR Pay is Null) AND paytype='Credit' ORDER BY purpose";
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
if(isset($_POST['btn_pblo']) and isset($rmonth) and $rmonth!='' and isset($ryear) and $ryear!='' ){
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$rmonth."' and ryear='".$ryear."' and (Pay='0' OR Pay is Null) AND paytype='Credit'";
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

//==============> UNCREDITED LODGMENT <========================
if(isset($_POST['btn_ucr']) and isset($rmonth) and $rmonth!='' and isset($ryear) and $ryear!='' ){
	$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$rmonth."' and ryear='".$ryear."' and (matched=0 OR matched is Null) AND paytype='Credit'";
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
if(isset($_POST['btn_ctrl']) and isset($rmonth) and $rmonth!='' and isset($ryear) and $ryear!='' ){
	$sqlr="SELECT * FROM recon_remitatb WHERE rmonth='".$rmonth."' and ryear='".$ryear."' and (matched=0 OR matched is Null) AND paytype='Credit'";
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


if(isset($_POST['btn_ctrl_ref']) and isset($rmonth) and $rmonth!='' and isset($ryear) and $ryear!='' ){
	$sql="SELECT * FROM recon_remitatb WHERE rmonth='".$rmonth."' AND ryear='".$ryear."' AND (matched=0 OR matched is Null) AND paytype='Credit' ORDER BY special_ref";
	$qry= mysqli_query($con, $sql); $sn=0; $total_r=0; $nx= mysqli_num_rows($qry);
	
	$sql2="SELECT * FROM recon_banktb WHERE rmonth='".$rmonth."' AND ryear='".$ryear."' AND (matched=0 OR matched is Null) AND paytype='Credit'";
	$qry2= mysqli_query($con, $sql2); $total_b=0; $ny= mysqli_num_rows($qry2);

	$bx .= "<TABLE width='100%' border='1'>
	<tr><th colspan='4'><strong>REMITA STATEMENT [{$nx}]</strong></th>
	<th colspan='3' style='background-color:#CECECE;'><strong>BANK STATEMENT [{$ny}]</strong></th></tr>
	
	<tr><td><strong>SN</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>DESCRIPTION</strong></td><td><strong>AMOUNT</strong></td>
	<td style='background-color:#CECECE;'><strong>TRANS. REF.</strong></td><td style='background-color:#CECECE;'><strong>DESCRIPTION</strong></td><td style='background-color:#CECECE;'><strong>AMOUNT</strong></td>
	</tr>";

	while($r= mysqli_fetch_array($qry, 3 )){
		++$sn; 
		///$bref=$bursary->get_any_value('credit_reference', 'recon_banktb', 'matched', '0', " special_ref = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount!={$r['amount']}");
		$bref=$bursary->get_any_value('special_ref', 'recon_banktb', 'matched', '0', " special_ref = '".$r['special_ref']."'");
		if($bref=='' or $bref=='Error!') continue;
		$total_r += $r['amount'];
		$bx .= "<tr><td>{$sn}</td>
		<td>{$r[credit_reference]}</td><td>{$r[paymentid]}</td><td align='right'>".number_format($r['amount'], 2)."</td>";
		
		///$bamount=$bursary->get_any_value('amount', 'recon_banktb', 'matched', '0', " AND credit_reference = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount!={$r['amount']}");
		$bamount=$bursary->get_any_value('amount', 'recon_banktb', 'matched', '0', " AND special_ref = '".$r['special_ref']."'");
		$total_b += $bamount;
		
		///$bx .= "<td style='background-color:#CECECE;'>".str_replace('\\\\','\\',$bref)."</td><td style='background-color:#CECECE;'>".$bursary->get_any_value('paymentid', 'recon_banktb', 'matched', '0', " AND credit_reference = '".addcslashes($r['credit_reference'], '\\\\')."' AND amount!={$r['amount']}")."</td><td style='background-color:#CECECE;' align='right'>".number_format($bamount, 2)."</td>
		$bx .= "<td style='background-color:#CECECE;'>".$bref."</td><td style='background-color:#CECECE;'>".$bursary->get_any_value('paymentid', 'recon_banktb', 'matched', '0', " AND special_ref = '".$r['special_ref']."'")."</td><td style='background-color:#CECECE;' align='right'>".number_format($bamount, 2)."</td>
		</tr>";
	}

	$total_r = number_format($total_r, 2);
	$total_b = number_format($total_b, 2);

	/*echo */ $bx .= "<tr><td></td><td></td><th>TOTAL:</th><th>{$total_r}</th>
		<td style='background-color:#CECECE;'></td><th style='background-color:#CECECE;'></th><th style='background-color:#CECECE;'>{$total_b}</th>
		</tr></TABLE>";
		///echo "</td><td>";
	$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>REPORT FOR SAME REFERENCE/DIFFERENT AMOUNT FOR ".strtoupper($rmonth).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
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
