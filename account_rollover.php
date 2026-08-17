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
	//$ryear=$_REQUEST['year'];


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
?>
<form action="<?=$_SERVER['PHP_SELF']."?".$_REQUEST['r_val']?>" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
			
			<table align="center" width="100%">
                		  <tbody><tr>
			     <th colspan="2">
			   			Financial Year<br>
				<select style="width:200px" name="ryear" id="ryear">
                                                  <option selected="selected" value="<?=$_POST['ryear'];?>"><?=$_POST['ryear'];?></option>
                                                  <option value="2024">2024</option><option value="2025">2025</option>
                                                  <option value="2024">2026</option><option value="2025">2027</option>
                                                  <option value="2024">2028</option><option value="2025">2029</option>
                                                  <option value="2024">2030</option><option value="2025">2031</option>
                                        </select>
			   </th>
			   </tr>                
                
			               
				<tr><th colspan="2">
				<input type="submit" class="btn" name="sbtn" id="sbtn" value="Display Record"> || <input type="submit" class="btn" name="roll" id="roll" value=" Execute Rollover ">
				</th></tr>
             </tbody></table>
				<div id="display"></div>
				<div id="roll"></div>
				
					
				</form>
<?php

          if(isset($_POST['ryear']) && $_POST['ryear']!='') {
                    $ryear=$_POST['ryear'];
                    $transdate=($ryear+1)."-01-01";
          }else exit;

          if(isset($_POST['roll']) && $_POST['ryear']!='') @mysqli_query($con, "DELETE FROM transtb WHERE transdate='{$transdate}' AND pvno='LFJV001'");

//if($option=='note23a')
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
                                                            if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Debit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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
	</table>

	<?php
} //
//if($option=='note24b')
          {
          ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 24B </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                        <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                              </tr>
                              <?php
                              $sn=1;
                              $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '24b'";

                              $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                              $total=0;
                              while($accode = mysqli_fetch_array($msql3, 3 )){

                                        $folio_code = $accode['folio_code'];

                                        $total += $accode['amount'];
                                        $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                        echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                        if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Debit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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
                    </td><!-- End of Debit Column-->
                    </tr>
                    
                    </table>
                    <?php
          } //
//if($option=='note25')
          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 25 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                   $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '25'";

                                   $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                   $total=0;
                                   while($accode = mysqli_fetch_array($msql3, 3 )){

                                        $folio_code = $accode['folio_code'];

                                        $total += $accode['amount'];
                                        $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                        echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                        if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Debit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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
                         </td><!-- End of Debit Column-->
                    </tr>
                    
                    </table>
               </td><!-- End of Debit Column-->
          </tr>
          
          </table>

          <?php
     } //
     //if($option=='note26')
     {
          ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
               <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 26 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                   <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                              </tr>
                              <?php
                              $sn=1;
                              $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '26'";

                              $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                              $total=0;
                              while($accode = mysqli_fetch_array($msql3, 3 )){

                                   $folio_code = $accode['folio_code'];

                                   $total += $accode['amount'];
                                   $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                   echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                   if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Debit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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
               
               </table>
               <?php
          } //
          //if($option=='note27')
          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 27 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                   <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                              </tr>
                              <?php
                              $sn=1;
                              $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '27'";

                              $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                              $total=0;
                              while($accode = mysqli_fetch_array($msql3, 3 )){

                                   $folio_code = $accode['folio_code'];

                                   $total += $accode['amount'];
                                   $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                   echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                   if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Debit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

                    
                    </table>

                    <?php
               } //
          //if($option=='note28')
          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 28 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                   <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                              </tr>
                              <?php
                              $sn=1;
                              $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '28'";

                              $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                              $total=0;
                              while($accode = mysqli_fetch_array($msql3, 3 )){

                                   $folio_code = $accode['folio_code'];

                                   $total += $accode['amount'];
                                   $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                   echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                   if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Debit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

                    
                    </table>

                    <?php
               } //
          //if($option=='note29')
          {
          ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 29 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                              <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                    </tr>
                    <?php
                    $sn=1;
                    $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '29'";

                    $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                    $total=0;
                    while($accode = mysqli_fetch_array($msql3, 3 )){

                              $folio_code = $accode['folio_code'];

                              $total += $accode['amount'];
                              $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                              echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                              if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Debit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

                    
                    </table>

                    <?php
          } //

          //if($option=='note31')
          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 31 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                        <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                                   </tr>
                                   <?php
                                   $sn=1;
                                   $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '31'";

                                   $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                   $total=0;
                                   while($accode = mysqli_fetch_array($msql3, 3 )){

                                        $folio_code = $accode['folio_code'];

                                        $total += $accode['amount'];
                                        $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                        echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                        if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Credit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

                    
                    </table>

                    <?php
               } //
               //if($option=='note32')
               {
                    ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                         <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 32 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                                  <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                                        </tr>
                                        <?php
                                        $sn=1;
                                        $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '32'";

                                        $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                        $total=0;
                                        while($accode = mysqli_fetch_array($msql3, 3 )){

                                                  $folio_code = $accode['folio_code'];

                                                  $total += $accode['amount'];
                                                  $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                                  echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                                  if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Credit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

                    
                    </table>

                    <?php
          } //

          //if($option=='note33')
          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 33 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                        <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                                   </tr>
                                   <?php
                                   $sn=1;
                                   $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '33'";

                                   $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                   $total=0;
                                   while($accode = mysqli_fetch_array($msql3, 3 )){

                                        $folio_code = $accode['folio_code'];

                                        $total += $accode['amount'];
                                        $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                        echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                        if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Credit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

                    
                    </table>

                    <?php
               } //
          {
          ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
               <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 34 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                   <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                              </tr>
                              <?php
                              $sn=1;
                              $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '34'";

                              $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                              $total=0;
                              while($accode = mysqli_fetch_array($msql3, 3 )){

                                   $folio_code = $accode['folio_code'];

                                   $total += $accode['amount'];
                                   $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                   echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                   if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Credit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

               
               </table>

               <?php
          } //

          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 38 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                        <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                                   </tr>
                                   <?php
                                   $sn=1;
                                   $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '38'";

                                   $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                   $total=0;
                                   while($accode = mysqli_fetch_array($msql3, 3 )){

                                        $folio_code = $accode['folio_code'];

                                        $total += $accode['amount'];
                                        $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                        echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                        if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Credit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

                    
                    </table>

                    <?php
          } //

          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 40 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                        <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                                   </tr>
                                   <?php
                                   $sn=1;
                                   $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '40'";

                                   $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                   $total=0;
                                   while($accode = mysqli_fetch_array($msql3, 3 )){

                                        $folio_code = $accode['folio_code'];

                                        $total += $accode['amount'];
                                        $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                        echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";
                                        if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Credit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='{$accode['amount']}', pvno='LFJV001'");

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

                    
                    </table>

                    <?php
          } //

          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 42 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                        <td bgcolor="#E5E5E5"><strong>TOTAL</strong></td>
                                   </tr>
                                   <?php
                                   $sn=1;
                                   $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '42' AND folio_code='99-002-4059'";

                                   $msql3 = mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                   $total=0;
                                   while($accode = mysqli_fetch_array($msql3, 3 )){

                                        $folio_code = $accode['folio_code'];

                                        $total += $accode['amount'];
                                        $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $folio_code);
                                        echo "<tr class='$rowclass'><td>$sn</td><td>".$folio_code."</td><td>".$fcod."</td><td>".number_format($accode['amount'], 2)."</td><td>".number_format($total, 2)."</td></tr>";

                                        if(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']>0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Credit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='".abs($accode['amount'])."', pvno='LFJV001'");
                                        elseif(isset($_POST['roll']) && $_POST['ryear']!='' && ($accode['amount']<0 && $accode['amount']!='')) @mysqli_query($con, "INSERT INTO transtb SET acctcode='{$folio_code}', folio_code='{$folio_code}', transtype='Debit', transdate='{$transdate}', entry_date=now(), entry_time=now(), amount='".abs($accode['amount'])."', pvno='LFJV001'");


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

                    
                    </table>

                    <?php
          } //
          
///////////////////////////////////////////////////// End of Body ////////////////////////////////////////////
//////////////////////////////////////////////////////Report Footer /////////////////////////////////////////////
///////////////////////////////////////////////////// End of Footer ////////////////////////////////////////////
?>
</body>
</html>
