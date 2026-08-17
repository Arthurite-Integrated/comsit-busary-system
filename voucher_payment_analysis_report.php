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
require_once "connect.php";
require_once "function.php";

@require_once('myclass_m.php');
$bursary = new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////

                    $val=explode("***",get_company());

                    echo "<center><img src='$val[1]' width='100' height='100' style='float:center' /><!--<img src='images/uith.png' width='110' height='100' style='float:right' />--><h2>".strtoupper($val[0])."</h2></center>";

                    if(isset($_POST['dFrm']) && $_POST['dFrm']!='' && isset($_POST['dTo']) && $_POST['dTo']!=''){
                              $dFrm = $_POST['dFrm'];
                              $dTo = $_POST['dTo'];
                              echo "<center><h2>PAYMENT ANALYSIS FOR THE PERIOD OF ".strtoupper(date('jS F Y', strtotime($dFrm)))." TO ".strtoupper(date('jS F Y', strtotime($dTo)))."</h2></center>";

                              $sql="SELECT DISTINCT paybatch FROM transtb WHERE transdate BETWEEN '{$dFrm}' AND '{$dTo}' AND transtype='Debit' AND pvno NOT LIKE '%JV%' ORDER BY paybatch";
                              ?>
                              <?php
                              
                              $res_v=mysqli_query($con, $sql);
                              $sn=0;
                              echo "<table id='MyTableX' class='table displayX' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box' width='100%'>
                              <thead> 
                                        <tr style='border:solid 1px #000; background-color:#f2f2f2'>
                                                  <th>S/NO</th>
                                                  <th>PAYMENT DATE</th>
                                                  <th>BATCH NO.</th>
                                                  <th>PV NO</th>
                                                  <th>PAYEE</th>
                                                  <th>DESCRIPTION</th>
                                                  <th>CODE</th>
                                                  <th>AMOUNT</th>
                                        </tr>
                              </thead><tbody>";
                              if(mysqli_num_rows($res_v)>=1)
                              {
                                        while($rs_v=mysqli_fetch_array($res_v, 3))
                                        {
                                                  $batchAmount=0;
                                                  $sql1="SELECT sum(amount) AS amount, pvno FROM transtb WHERE transdate BETWEEN '{$dFrm}' AND '{$dTo}' AND transtype='Debit' AND pvno NOT LIKE '%JV%' AND paybatch='{$rs_v['paybatch']}' GROUP BY pvno ORDER BY paybatch, pvno";
                                                  $res_v1=mysqli_query($con, $sql1);
                                                  while($rs_v1=mysqli_fetch_array($res_v1, 3))
                                                  {
                                                            ++$sn;
                                                            $pvno=$rs_v1['pvno']; 
                                                            $batchno=$rs_v['paybatch']; 
                                                            
                                                            $p=base64_encode($pvno);
                                                            $transdate=$bursary->get_any_value('transdate', 'transtb', 'pvno', $pvno); 
                                                            $payee_name=strtoupper($bursary->get_any_value('payee_name', 'vouchertb', 'pvno_paid', $pvno));
                                                            $pv=$bursary->get_any_value('pvno', 'vouchertb', 'pvno_paid', $pvno);
                                                            //$transdate=$rs_v['transdate'];
                                                            $ds=mysqli_query($con, "SELECT folio_code FROM voucher_folio_codetb WHERE pvno='{$pv}'");
                                                            if(mysqli_num_rows($ds)==1){
                                                                      $code=get_voucher_folio_code($pv, 'Code');
                                                                      $desc=strtoupper(get_voucher_folio_code($pv, 'Title'));
                                                            }else{
                                                                      $code="VARIOUS";
                                                                      $desc="REFUND";
                                                            }

                                                            $net = number_format($rs_v1['amount'], 2);
                                                            $batchAmount += $rs_v1['amount'];
                                                            $gross = read_gross($pvno);
                                                            $yr = date('y', strtotime($prepared_date));
                                                            if(date('d/m/Y',strtotime($audit_date))=="01/01/1970") $au_date = '';
                                                            else $au_date = date('d/m/Y',strtotime($audit_date));
                                                            echo "<tr><td>$sn</td>
                                                            <td>$transdate</td>
                                                            <td>$batchno</td>
                                                            <td>$pvno</td>
                                                            <td>$payee_name</td>
                                                            <td>$desc</td>
                                                            <td nowrap>$code</td>
                                                            <td align='right'>".$net."</td>
                                                            </tr>";
                                                  }
                                                  echo "<tr><td colspan='8' align='right'><b>".number_format($batchAmount, 2)."</b></td></tr>";
                                                  echo "<tr><td colspan='8'><hr></td></tr>";
                                        } //end of while
                                        
                                        echo "</tbody></table>";
                                        ?>
                                        <hr>
                                        <?php
                              }
                              else
                              echo "<font color='red'><b>No pending voucher awaiting schedule.</b></font>";
                    }
          ?>

</body>
</html>