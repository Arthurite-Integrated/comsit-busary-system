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


     if($option=='note9')
     {
          ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
               <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 9 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
               <tr>
                    <!-- Debit Column-->
                    <td colspan="2" valign="top">
                         <br>
                         <table width="100%" align="center">
                              <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                              <?php
                              //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                              $sn=1;
                              $sqll3 = "SELECT distinct t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '{$ryear}' AND `fundcenter` LIKE '09' AND (`itemcode` LIKE '10%' OR `itemcode` LIKE '110%') AND f.category NOT IN ('02-731D', '02-731A') AND transtype in ('Debit','Credit')";
                              $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                              $closing = 0;	$total_credixt=0;   $total_debitx=0;
                              $acc = array();	$acctotal = array();
                              while($accode = mysqli_fetch_array($msql3, 3 )){

                                   $account = $acode = $acc[] = $accode['folio_code'];

                                   $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '{$ryear}' and folio_code = '{$account}' ") or die( mysqli_error($con));
                                   if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                   $total_credixt=+$rs_trans_ox[0];

                                   //get all expenses before the selected date
                                   $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '{$ryear}' and folio_code = '{$account}'") or die( mysqli_error($con));
                                   if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                   $total_debitx=+$rs_trans_ox[0];
                                   $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                   $closing = $closing + $closingbal;
                              }
                              for($ic=0; $ic < count($acc); $ic++){
                                   $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                   echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                   $sn++;
                              }

                              ?>
                              <tr>
                                   <td colspan="4" align="right">&nbsp;</td>
                              </tr>

                              <tr>
                                   <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                              </tr>

                         </table>
                    </td>
               </tr>

               <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
               </table>

               <?php
          } //
          if($option=='note11')
          {
               ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                    <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 11 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                    <tr>
                         <!-- Debit Column-->
                         <td colspan="2" valign="top">
                              <br>
                              <table width="100%" align="center">
                                   <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>


                                   <?php
                                   //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                   $sn=1;
                                   $sqll3 = "select distinct t.folio_code from transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND `itemcode` LIKE '2030'";
                                   $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                   $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                   $acc = array();	$acctotal = array();
                                   while($accode = mysqli_fetch_array($msql3, 3 )){

                                        $account = $acode = $acc[] = $accode['folio_code'];

                                        $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb_final WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                        if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                        $total_credixt=+$rs_trans_ox[0];

                                        //get all expenses before the selected date
                                        $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb_final WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                        if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                        $total_debitx=+$rs_trans_ox[0];
                                        $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                        $closing = $closing + $closingbal;
                                   }
                                   for($ic=0; $ic < count($acc); $ic++){
                                        $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                        echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                        $sn++;
                                   }
                                   ?>


                                   <tr>
                                        <td colspan="4" align="right">&nbsp;</td>
                                   </tr>

                                   <tr>
                                        <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                   </tr>
                              </table>
                         </td>
                    </tr>

                    <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                    </table>

                    <?php
               } //


               if($option=='note12')
               {
                    ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                         <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 12 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                         <tr>
                              <!-- Debit Column-->
                              <td colspan="2" valign="top">
                                   <br>
                                   <table width="100%" align="center">
                                        <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>


                                        <?php
                                        //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                        $sn=1;
                                        $sqll3 = "select distinct t.folio_code from transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND f.category='06-702C'";
                                        $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                        $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                        $acc = array();	$acctotal = array();
                                        while($accode = mysqli_fetch_array($msql3, 3 )){

                                             $account = $acode = $acc[] = $accode['folio_code'];

                                             $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb_final WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                             if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                             $total_credixt=+$rs_trans_ox[0];

                                             //get all expenses before the selected date
                                             $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb_final WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                             if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                             $total_debitx=+$rs_trans_ox[0];
                                             $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                             $closing = $closing + $closingbal;
                                        }
                                        for($ic=0; $ic < count($acc); $ic++){
                                             $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                             echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                             $sn++;
                                        }
                                        ?>
                                        <tr>
                                             <td colspan="4" align="right">&nbsp;</td>
                                        </tr>

                                        <tr>
                                             <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                        </tr>
                                   </table>
                              </td>
                         </tr>

                         <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                         </table>

                         <?php
                    } //

                    if($option=='note13')
                    {
                         ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                              <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 13 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                              <tr>
                                   <!-- Debit Column-->
                                   <td colspan="2" valign="top">
                                        <br>
                                        <table width="100%" align="center">
                                             <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                             <?php
                                             //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                             $sn=1;
                                             $sqll3 = "SELECT distinct t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '{$ryear}' AND `itemcode` LIKE '2%' AND `itemcode` != '2030' AND (t.folio_code NOT LIKE '%-701-2084') AND f.category NOT IN ('06-702C', 'AA-002-2C', 'AA-002-2CB') AND `fundcenter` != '99' AND deptcode != '743' AND f.`title` NOT LIKE '%Depreciation%'";
                                             $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                             $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                             $acc = array();	$acctotal = array();
                                             while($accode = mysqli_fetch_array($msql3, 3 )){

                                                  $account = $acode = $acc[] = $accode['folio_code'];

                                                  $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '{$ryear}' and folio_code = '{$account}' ") or die( mysqli_error($con));
                                                  if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                  $total_credixt=+$rs_trans_ox[0];

                                                  //get all expenses before the selected date
                                                  $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '{$ryear}' and folio_code = '{$account}'") or die( mysqli_error($con));
                                                  if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                  $total_debitx=+$rs_trans_ox[0];
                                                  $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                  $closing = $closing + $closingbal;
                                             }
                                             for($ic=0; $ic < count($acc); $ic++){
                                                  $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                  echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                  $sn++;
                                             }
                                             ?>
                                             <tr>
                                                  <td colspan="4" align="right">&nbsp;</td>
                                             </tr>

                                             <tr>
                                                  <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                             </tr>
                                        </table>
                                   </td>
                              </tr>

                              <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                              </table>

                              <?php
                         } //

                         if($option=='note14')
                         {
                              ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                   <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 14 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                   <tr>
                                        <!-- Debit Column-->
                                        <td colspan="2" valign="top">
                                             <br>
                                             <table width="100%" align="center">
                                                  <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                                  <?php
                                                  //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                  $sn=1;
                                                  $sqll3 = "select distinct t.folio_code from transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND `fundcenter` LIKE '99' AND deptcode='743' AND `itemcode` LIKE '21%'";
                                                  $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                  $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                  $acc = array();	$acctotal = array();
                                                  while($accode = mysqli_fetch_array($msql3, 3 )){

                                                       $account = $acode = $acc[] = $accode['folio_code'];

                                                       $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb_final WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                       if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                       $total_credixt=+$rs_trans_ox[0];

                                                       //get all expenses before the selected date
                                                       $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb_final WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                       if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                       $total_debitx=+$rs_trans_ox[0];
                                                       $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                       $closing = $closing + $closingbal;
                                                  }
                                                  for($ic=0; $ic < count($acc); $ic++){
                                                       $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                       echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                       $sn++;
                                                  }
                                                  ?>
                                                  <tr>
                                                       <td colspan="4" align="right">&nbsp;</td>
                                                  </tr>

                                                  <tr>
                                                       <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                  </tr>
                                             </table>
                                        </td>
                                   </tr>

                                   <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                   </table>

                                   <?php
                              } //


                              if($option=='note15')
                              {
                                   ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                        <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 15 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                        <tr>
                                             <!-- Debit Column-->
                                             <td colspan="2" valign="top">
                                                  <br>
                                                  <table width="100%" align="center">
                                                       <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                                       <?php
                                                       //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                       $sn=1;
                                                       $sqll3 = "select distinct t.folio_code from transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND `fundcenter` LIKE '09' AND `itemcode` LIKE '10%' AND f.category IN ('02-731A')";
                                                       $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                       $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                       $acc = array();	$acctotal = array();
                                                       while($accode = mysqli_fetch_array($msql3, 3 )){

                                                            $account = $acode = $acc[] = $accode['folio_code'];

                                                            $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb_final WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                            if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                            $total_credixt=+$rs_trans_ox[0];

                                                            //get all expenses before the selected date
                                                            $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb_final WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                            if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                            $total_debitx=+$rs_trans_ox[0];
                                                            $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                            $closing = $closing + $closingbal;
                                                       }
                                                       for($ic=0; $ic < count($acc); $ic++){
                                                            $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                            echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                            $sn++;
                                                       }
                                                       ?>
                                                       <tr>
                                                            <td colspan="4" align="right">&nbsp;</td>
                                                       </tr>

                                                       <tr>
                                                            <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                       </tr>
                                                  </table>
                                             </td>
                                        </tr>

                                        <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                        </table>

                                        <?php
                                   } //


                                   if($option=='note9b')
                                   {
                                        ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                             <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 9b </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                             <tr>
                                                  <!-- Debit Column-->
                                                  <td colspan="2" valign="top">
                                                       <br>
                                                       <table width="100%" align="center">
                                                            <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                                            <?php
                                                            //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                            $sn=1;
                                                            $sqll3 = "select distinct t.folio_code from transtb_final t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND `fundcenter` LIKE '09' AND `itemcode` LIKE '10%' AND f.category IN ('02-731D')";
                                                            $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                            $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                            $acc = array();	$acctotal = array();
                                                            while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                 $account = $acode = $acc[] = $accode['folio_code'];

                                                                 $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb_final WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                                 if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                 $total_credixt=+$rs_trans_ox[0];

                                                                 //get all expenses before the selected date
                                                                 $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb_final WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                                 if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                 $total_debitx=+$rs_trans_ox[0];
                                                                 $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                 $closing = $closing + $closingbal;
                                                            }
                                                            for($ic=0; $ic < count($acc); $ic++){
                                                                 $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                 echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                                 $sn++;
                                                            }
                                                            ?>
                                                            <tr>
                                                                 <td colspan="4" align="right">&nbsp;</td>
                                                            </tr>

                                                            <tr>
                                                                 <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                            </tr>
                                                       </table>
                                                  </td>
                                             </tr>

                                             <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                             </table>

                                             <?php
                                        } //



                                        if($option=='note16')
                                        {
                                             ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                  <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 16 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                  <tr>
                                                       <!-- Debit Column-->
                                                       <td colspan="2" valign="top">
                                                            <br>
                                                            <table width="100%" align="center">
                                                                 <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                                                 <?php
                                                                 //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                 $sn=1;
                                                                 $sqll3 = "SELECT distinct t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND f.category IN ('AA-002-2CB')";
                                                                 $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                 $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                                 $acc = array();	$acctotal = array();
                                                                 while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                      $account = $acode = $acc[] = $accode['folio_code'];

                                                                      $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '{$account}' ") or die( mysqli_error($con));
                                                                      if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                      $total_credixt=+$rs_trans_ox[0];

                                                                      //get all expenses before the selected date
                                                                      $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '{$account}'") or die( mysqli_error($con));
                                                                      if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                      $total_debitx=+$rs_trans_ox[0];
                                                                      $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                      $closing = $closing + $closingbal;
                                                                 }
                                                                 for($ic=0; $ic < count($acc); $ic++){
                                                                      $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                      echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                                      $sn++;
                                                                 }
                                                                 ?>
                                                                 <tr>
                                                                      <td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                                 </tr>
                                                            </table>
                                                       </td>
                                                  </tr>

                                                  <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                  </table>

                                                  <?php
                                             } //
                                             if($option=='note17')
                                             {
                                                  ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                       <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 16 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                       <tr>
                                                            <!-- Debit Column-->
                                                            <td colspan="2" valign="top">
                                                                 <br>
                                                                 <table width="100%" align="center">
                                                                      <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                                                      <?php
                                                                      //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                      $sn=1;
                                                                      $sqll3 = "SELECT distinct t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND f.category IN ('AA-002-2C')";
                                                                      $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                      $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                                      $acc = array();	$acctotal = array();
                                                                      while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                           $account = $acode = $acc[] = $accode['folio_code'];

                                                                           $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '{$account}' ") or die( mysqli_error($con));
                                                                           if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                           $total_credixt=+$rs_trans_ox[0];

                                                                           //get all expenses before the selected date
                                                                           $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '{$account}'") or die( mysqli_error($con));
                                                                           if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                           $total_debitx=+$rs_trans_ox[0];
                                                                           $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                           $closing = $closing + $closingbal;
                                                                      }
                                                                      for($ic=0; $ic < count($acc); $ic++){
                                                                           $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                           echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                                           $sn++;
                                                                      }
                                                                      ?>
                                                                      <tr>
                                                                           <td colspan="4" align="right">&nbsp;</td><td>&nbsp;</td>
                                                                      </tr>
                                                                      <tr>
                                                                           <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                                      </tr>
                                                                 </table>
                                                            </td>
                                                       </tr>
                                                       <?php
                                                       //}// end of monthyear found i.e if(num_row>=1)
                                                       ?>
                                                       <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                       </table>
                                                       <?php

                                                  } //

                                                  if($option=='note23a')
                                                  {
                                                       ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                            <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 23A </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                            <tr>
                                                                 <!-- Debit Column-->
                                                                 <td colspan="2" valign="top">
                                                                      <br>
                                                                      <table width="100%" align="center">
                                                                           <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>


                                                                           <?php
                                                                           //echo "select distinct concat(monthname(transdate),concat(', ',year(transdate))) as monthyear, month(transdate) as m, year(transdate) as y from transtb where dept_acctcode='$deptcode' and transdate between '$from' and '$to' order by year(transdate),month(transdate)";
                                                                           //echo "<tr><td colspan='2' align='center'><h3>$monthyear</h3></td></tr>";

                                                                           ?>

                                                                           <?php
                                                                           //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                           $sn=1;
                                                                           $sqll3 = "select distinct acctcode from transtb_final WHERE year(transdate) = '".($ryear)."' and acctcode != '' ";
                                                                           $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                           $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                                           $acc = array();	$acctotal = array();
                                                                           while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                                $account = $acode = $acc[] = $accode['acctcode'];

                                                                                $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and acctcode = '$account' ") or die( mysqli_error($con));
                                                                                if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                $total_credixt=+$rs_trans_ox[0];

                                                                                //get all expenses before the selected date
                                                                                $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and acctcode = '$account'") or die( mysqli_error($con));
                                                                                if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                $total_debitx=+$rs_trans_ox[0];
                                                                                $closingbal = $acctotal[] = ($total_credixt - $total_debitx);

                                                                                $closing = $closing + $closingbal;
                                                                           }
                                                                           for($ic=0; $ic < count($acc); $ic++){
                                                                                $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                                echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                                                $sn++;
                                                                           }
                                                                           ?>

                                                                           <tr>
                                                                                <td colspan="4" align="right">&nbsp;</td>
                                                                           </tr>

                                                                           <tr>
                                                                                <td></td><td></td><td><strong>Balance : </strong><td><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                                           </tr>
                                                                      </table>
                                                                 </td>
                                                            </tr>

                                                            <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                            </table>

                                                            <?php
                                                       } //

                                                       if($option=='note24b')
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
                                                                 <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                 </table>
                                                                 <?php
                                                            } //

                                                            if($option=='note24')
                                                            {
                                                                 ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                                      <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 24 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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
                                                                                     $sqll3 = "SELECT * FROM trans_notetb WHERE transyear = '{$ryear}' AND note = '24'";

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
                                                                           </td><!-- End of Debit Column-->
                                                                      </tr>
                                                                      <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                      </table>

                                                                      <?php
                                                                 } //
                                                                 if($option=='note25')
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
                                                                           <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                           </table>
                                                                      </td><!-- End of Debit Column-->
                                                                 </tr>
                                                                 <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                 </table>

                                                                 <?php
                                                            } //
                                                            if($option=='note26')
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
                                                                      <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                      </table>
                                                                      <?php
                                                                 } //
                                                                 if($option=='note27')
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

                                                                           <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                           </table>

                                                                           <?php
                                                                      } //
                                                                      if($option=='note29')
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

                                                                                <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                </table>

                                                                                <?php
                                                                           } //
                                                                           if($option=='note34')
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

                                                                                     <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                     </table>

                                                                                     <?php
                                                                                } //
                                                                                if($option=='note33')
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

                                                                                          <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                          </table>

                                                                                          <?php
                                                                                     } //
                                                                                if($option=='note31')
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

                                                                                          <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                          </table>

                                                                                          <?php
                                                                                     } //
                                                                                     if($option=='note32')
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

                                                                                               <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                               </table>

                                                                                               <?php
                                                                                          } //
                                                                                          if($option=='note40')
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

                                                                                               <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                               </table>

                                                                                               <?php
                                                                                               } //
                                                                                               if($option=='note40cf')
                                                                                               {
                                                                                                    ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                                                                         <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 40 CF </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                                                                         <tr>
                                                                                                              <!-- Debit Column-->
                                                                                                              <td colspan="2" valign="top">
                                                                                                                   <br>
                                                                                                                   <table width="100%" align="center">
                                                                                                                        <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td>
                                                                                                                             <td bgcolor="#E5E5E5"><strong>OPENING BALANCE</strong></td></tr>

                                                                                                                             <?php
                                                                                                                             //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                                                                             $sn=1;
                                                                                                                             $sqll3 = "select distinct t.folio_code from transtb_final t WHERE year(transdate) = '".($ryear)."' and  folio_code in ('01-002-4048',
                                                                                                                             '02-002-4048',
                                                                                                                             '03-002-4048',
                                                                                                                             '04-002-4048',
                                                                                                                             '05-002-4048',
                                                                                                                             '06-002-4048',
                                                                                                                             '07-002-4048',
                                                                                                                             '08-002-4048',
                                                                                                                             '09-002-4048',
                                                                                                                             '21-002-4048',
                                                                                                                             '22-002-4048',
                                                                                                                             '23-002-4048',
                                                                                                                             '31-002-4048',
                                                                                                                             '32-002-4048',
                                                                                                                             '33-002-4048',
                                                                                                                             '41-002-4048',
                                                                                                                             '51-002-4048',
                                                                                                                             '71-002-4048',
                                                                                                                             '99-002-4048',
                                                                                                                             '51-002-4067',
                                                                                                                             '99-002-4069',
                                                                                                                             '05-002-4071',
                                                                                                                             '04-002-4072',
                                                                                                                             '71-002-4082',
                                                                                                                             '06-002-4075',
                                                                                                                             '21-002-4051',
                                                                                                                             '21-002-4052',
                                                                                                                             '22-002-4053',
                                                                                                                             '31-002-4054',
                                                                                                                             '32-002-4055',
                                                                                                                             '41-002-4056',
                                                                                                                             '51-002-4060',
                                                                                                                             '23-002-4065',
                                                                                                                             '21-002-4066',
                                                                                                                             '21-002-4068',
                                                                                                                             '71-002-4075',
                                                                                                                             '09-002-4047',
                                                                                                                             '08-002-5062',
                                                                                                                             '01-002-4028',
                                                                                                                             '02-002-4028',
                                                                                                                             '03-002-4028',
                                                                                                                             '04-002-4028',
                                                                                                                             '05-002-4028',
                                                                                                                             '06-002-4028',
                                                                                                                             '07-002-4028',
                                                                                                                             '08-002-4028',
                                                                                                                             '09-002-4028',
                                                                                                                             '21-002-4028',
                                                                                                                             '22-002-4028',
                                                                                                                             '23-002-4028',
                                                                                                                             '31-002-4028',
                                                                                                                             '32-002-4028',
                                                                                                                             '33-002-4028',
                                                                                                                             '41-002-4028',
                                                                                                                             '51-002-4028',
                                                                                                                             '71-002-4028',
                                                                                                                             '99-002-4028') ";
                                                                                                                             $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                                                                             $closing = 0;	$total_credixt=0;   $total_debitx=0; $total_credixp=0; $closing_op = 0;
                                                                                                                             $acc = array();	$acctotal = array();
                                                                                                                             while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                                                                                  $account = $acode = $acc[] = $accode['folio_code'];

                                                                                                                                  $rstrans_op= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype in ('Credit','Debit') and year(transdate) = '".($ryear)."' and folio_code = '$account' and pvno = 'LFJV001' ") or die( mysqli_error($con));
                                                                                                                                  if($rs_trans_op=@mysqli_fetch_array($rstrans_op, 3 ))

                                                                                                                                  $acctotal2[]=$rs_trans_op[0];
                                                                                                                                  $total_credixp =+ $rs_trans_op[0];


                                                                                                                                  $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                                                                                                  if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                  $total_credixt=+$rs_trans_ox[0];

                                                                                                                                  //get all expenses before the selected date
                                                                                                                                  $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                                                                                                  if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                  $total_debitx=+$rs_trans_ox[0];
                                                                                                                                  $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                                                                                  $closing = $closing + $closingbal;
                                                                                                                                  $closing_op = $closing_op + $total_credixp;
                                                                                                                             }
                                                                                                                             for($ic=0; $ic < count($acc); $ic++){
                                                                                                                                  $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                                                                                  echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td><td>".number_format($acctotal2[$ic], 2)."</td></tr>";
                                                                                                                                  $sn++;
                                                                                                                             }
                                                                                                                             ?>

                                                                                                                             <tr>
                                                                                                                                  <td colspan="5" align="right">&nbsp;</td>
                                                                                                                             </tr>

                                                                                                                             <tr>
                                                                                                                                  <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td><td><b><?php echo number_format($closing_op, 2); ?></b></td>
                                                                                                                             </tr>
                                                                                                                             <tr>
                                                                                                                                  <td></td><td></td><td></td><td><strong>Final Balance : </strong><td><b><?php echo number_format(($closing+$closing_op), 2); ?></b></td>
                                                                                                                             </tr>
                                                                                                                        </table>
                                                                                                                   </td>
                                                                                                              </tr>

                                                                                                              <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                                              </table>

                                                                                                              <?php
                                                                                                         } //
                                                                                                         if($option=='note35')
                                                                                                         {
                                                                                                              ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                                                                                   <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 35 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
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

                                                                                                                   <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                                                   </table>

                                                                                                                   <?php
                                                                                                              } //
                                                                                                              if($option=='note36')
                                                                                                              {
                                                                                                                   ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                                                                                        <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 36 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                                                                                        <tr>
                                                                                                                             <!-- Debit Column-->
                                                                                                                             <td colspan="2" valign="top">
                                                                                                                                  <br>
                                                                                                                                  <table width="100%" align="center">
                                                                                                                                       <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                                                                                                                       <?php
                                                                                                                                       //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                                                                                       $sn=1;
                                                                                                                                       $sqll3 = "select distinct t.folio_code from transtb_final t WHERE year(transdate) = '".($ryear)."' and  folio_code in ('01-002-4029',
                                                                                                                                       '02-002-4029',
                                                                                                                                       '03-002-4029',
                                                                                                                                       '04-002-4029',
                                                                                                                                       '05-002-4029',
                                                                                                                                       '06-002-4029',
                                                                                                                                       '07-002-4029',
                                                                                                                                       '08-002-4029',
                                                                                                                                       '09-002-4029',
                                                                                                                                       '21-002-4029',
                                                                                                                                       '22-002-4029',
                                                                                                                                       '23-002-4029',
                                                                                                                                       '31-002-4029',
                                                                                                                                       '32-002-4029',
                                                                                                                                       '33-002-4029',
                                                                                                                                       '41-002-4029',
                                                                                                                                       '51-002-4029',
                                                                                                                                       '71-002-4029',
                                                                                                                                       '99-002-4029',
                                                                                                                                       '01-002-4035',
                                                                                                                                       '02-002-4035',
                                                                                                                                       '03-002-4035',
                                                                                                                                       '04-002-4035',
                                                                                                                                       '05-002-4035',
                                                                                                                                       '06-002-4035',
                                                                                                                                       '07-002-4035',
                                                                                                                                       '08-002-4035',
                                                                                                                                       '09-002-4035',
                                                                                                                                       '21-002-4035',
                                                                                                                                       '22-002-4035',
                                                                                                                                       '23-002-4035',
                                                                                                                                       '31-002-4035',
                                                                                                                                       '32-002-4035',
                                                                                                                                       '33-002-4035',
                                                                                                                                       '41-002-4035',
                                                                                                                                       '51-002-4035',
                                                                                                                                       '71-002-4035',
                                                                                                                                       '99-002-4035',
                                                                                                                                       '06-002-4062',
                                                                                                                                       '01-002-5063',
                                                                                                                                       '01-002-4064',
                                                                                                                                       '01-002-4000',
                                                                                                                                       '02-002-4000',
                                                                                                                                       '03-002-4000',
                                                                                                                                       '04-002-4000',
                                                                                                                                       '05-002-4000',
                                                                                                                                       '06-002-4000',
                                                                                                                                       '07-002-4000',
                                                                                                                                       '08-002-4000',
                                                                                                                                       '09-002-4000',
                                                                                                                                       '21-002-4000',
                                                                                                                                       '22-002-4000',
                                                                                                                                       '23-002-4000',
                                                                                                                                       '31-002-4000',
                                                                                                                                       '32-002-4000',
                                                                                                                                       '33-002-4000',
                                                                                                                                       '41-002-4000',
                                                                                                                                       '51-002-4000',
                                                                                                                                       '71-002-4000',
                                                                                                                                       '99-002-4000',
                                                                                                                                       '01-002-4049',
                                                                                                                                       '02-002-4049',
                                                                                                                                       '03-002-4049',
                                                                                                                                       '04-002-4049',
                                                                                                                                       '05-002-4049',
                                                                                                                                       '06-002-4049',
                                                                                                                                       '07-002-4049',
                                                                                                                                       '08-002-4049',
                                                                                                                                       '09-002-4049',
                                                                                                                                       '21-002-4049',
                                                                                                                                       '22-002-4049',
                                                                                                                                       '23-002-4049',
                                                                                                                                       '31-002-4049',
                                                                                                                                       '32-002-4049',
                                                                                                                                       '33-002-4049',
                                                                                                                                       '41-002-4049',
                                                                                                                                       '51-002-4049',
                                                                                                                                       '71-002-4049',
                                                                                                                                       '99-002-4049')";
                                                                                                                                       $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                                                                                       $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                                                                                                       $acc = array();	$acctotal = array();
                                                                                                                                       while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                                                                                            $account = $acode = $acc[] = $accode['folio_code'];

                                                                                                                                            $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                                                                                                            if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                            $total_credixt=+$rs_trans_ox[0];

                                                                                                                                            //get all expenses before the selected date
                                                                                                                                            $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                                                                                                            if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                            $total_debitx=+$rs_trans_ox[0];
                                                                                                                                            $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                                                                                            $closing = $closing + $closingbal;
                                                                                                                                       }
                                                                                                                                       for($ic=0; $ic < count($acc); $ic++){
                                                                                                                                            $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                                                                                            echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                                                                                                            $sn++;
                                                                                                                                       }
                                                                                                                                       ?>

                                                                                                                                       <tr>
                                                                                                                                            <td colspan="4" align="right">&nbsp;</td>
                                                                                                                                       </tr>

                                                                                                                                       <tr>
                                                                                                                                            <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                                                                                                       </tr>
                                                                                                                                  </table>
                                                                                                                             </td>
                                                                                                                        </tr>

                                                                                                                        <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                                                        </table>

                                                                                                                        <?php
                                                                                                                   } //
                                                                                                                   if($option=='note42')
                                                                                                                   {
                                                                                                                        ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                                                                                             <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 42 <br>(Accumulated Surplus /(Deficit))</h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                                                                                             <tr>
                                                                                                                                  <!-- Debit Column-->
                                                                                                                                  <td colspan="2" valign="top">
                                                                                                                                       <br>
                                                                                                                                       <table width="100%" align="center">
                                                                                                                                            <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                                                                                                                            <?php
                                                                                                                                            //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                                                                                            $def_code = '99-002-4059'; $def_code2 = '99-002-4058';
                                                                                                                                            $sn=1;
                                                                                                                                            $sqll3 = "SELECT DISTINCT t.folio_code FROM transtb t WHERE year(transdate) = '{$ryear}' and  folio_code in ('99-002-4058','99-701-2084','02-701-2084','51-002-4063','09-701-2084')";
                                                                                                                                            $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                                                                                            $closing = 0;	$total_credixt=0;   $total_debitx=0;

                                                                                                                                            $rstrans_oxx= mysqli_query($con, "SELECT * FROM final_acc_transtb WHERE `name` = 'Net' AND `year` = '{$ryear}' ") or die( mysqli_error($con));
                                                                                                                                            if($rs_trans_oxx=@mysqli_fetch_array($rstrans_oxx, 3 ))

                                                                                                                                            $def_amount=$rs_trans_oxx['amount'];


                                                                                                                                            $acc = array();	$acctotal = array();
                                                                                                                                            while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                                                                                                 $account = $acode = $acc[] = $accode['folio_code'];


                                                                                                                                                 $rstrans_oxa= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype in ('Debit') and year(transdate) = '".($ryear)."' and folio_code = '99-002-4059' ") or die( mysqli_error($con));
                                                                                                                                                 if($rs_trans_oxa=@mysqli_fetch_array($rstrans_oxa, 3 ))

                                                                                                                                                 $total_credixta=$rs_trans_oxa[0];

                                                                                                                                                 $rstrans_oxa1= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype in ('Credit') and year(transdate) = '".($ryear)."' and folio_code = '99-002-4059' ") or die( mysqli_error($con));
                                                                                                                                                 if($rs_trans_oxa1=@mysqli_fetch_array($rstrans_oxa1, 3 ))

                                                                                                                                                 $total_credixta1=$rs_trans_oxa1[0];

                                                                                                                                                 $total_accum = $total_credixta - $total_credixta1;

                                                                                                                                                 if ($total_accum < 0)
                                                                                                                                                 {
                                                                                                                                                      $total_accum_use = abs($total_accum);
                                                                                                                                                 } else {
                                                                                                                                                      $total_accum_use = -$total_accum;
                                                                                                                                                 }



                                                                                                                                                 $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                                                                                                                 if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                                 $total_credixt=+$rs_trans_ox[0];

                                                                                                                                                 //get all expenses before the selected date
                                                                                                                                                 $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                                                                                                                 if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                                 $total_debitx=+$rs_trans_ox[0];
                                                                                                                                                 $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                                                                                                 if ($closingbal < 0)
                                                                                                                                                 {
                                                                                                                                                      $total_close = $acctotal2[]=abs($closingbal);
                                                                                                                                                 } else {
                                                                                                                                                      $total_close =  $acctotal2[] = -$closingbal;
                                                                                                                                                 }

                                                                                                                                                 $closing = $closing + $total_close;
                                                                                                                                            }


                                                                                                                                            $closing2 = $closing + $def_amount + $total_accum_use;
                                                                                                                                            for($ic=0; $ic < count($acc); $ic++){

                                                                                                                                                 $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);

                                                                                                                                                 echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format($acctotal2[$ic], 2)."</td></tr>";
                                                                                                                                                 $sn++;
                                                                                                                                            }
                                                                                                                                            $sn2 = $sn+1;
                                                                                                                                            $fcod2 = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $def_code);
                                                                                                                                            $fcod3 = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $def_code2);
                                                                                                                                            echo "<tr class='$rowclass'><td>$sn</td><td>$def_code2</td><td>$fcod3</td><td>".number_format($def_amount, 2)."</td></tr>";
                                                                                                                                            echo "<tr class='$rowclass'><td>$sn2</td><td>".$def_code."</td><td>".$fcod2."</td><td>".number_format($total_accum_use, 2)."</td></tr>";

                                                                                                                                            ?>

                                                                                                                                            <tr>
                                                                                                                                                 <td colspan="4" align="right">&nbsp;</td>
                                                                                                                                            </tr>

                                                                                                                                            <tr>
                                                                                                                                                 <td></td><td></td><td><strong>Accumulated Balance at year End : </strong><td><b><?php echo number_format($closing2, 2); ?></b></td>
                                                                                                                                            </tr>
                                                                                                                                       </table>
                                                                                                                                  </td>
                                                                                                                             </tr>

                                                                                                                             <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                                                             </table>

                                                                                                                             <?php
                                                                                                                        } //
                                                                                                                        if($option=='note37')
                                                                                                                        {
                                                                                                                             ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                                                                                                  <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 37 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                                                                                                  <tr>
                                                                                                                                       <!-- Debit Column-->
                                                                                                                                       <td colspan="2" valign="top">
                                                                                                                                            <br>
                                                                                                                                            <table width="100%" align="center">
                                                                                                                                                 <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>


                                                                                                                                                 <?php
                                                                                                                                                 //echo "select distinct concat(monthname(transdate),concat(', ',year(transdate))) as monthyear, month(transdate) as m, year(transdate) as y from transtb where dept_acctcode='$deptcode' and transdate between '$from' and '$to' order by year(transdate),month(transdate)";
                                                                                                                                                 //echo "<tr><td colspan='2' align='center'><h3>$monthyear</h3></td></tr>";

                                                                                                                                                 ?>

                                                                                                                                                 <?php
                                                                                                                                                 //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                                                                                                 $sn=1;
                                                                                                                                                 $sqll3 = "select distinct t.folio_code from transtb_final t WHERE year(transdate) = '".($ryear)."' and  folio_code in ('08-002-5071')";
                                                                                                                                                 $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                                                                                                 $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                                                                                                                 $acc = array();	$acctotal = array();
                                                                                                                                                 while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                                                                                                      $account = $acode = $acc[] = $accode['folio_code'];

                                                                                                                                                      $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                                                                                                                      if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                                      $total_credixt=+$rs_trans_ox[0];

                                                                                                                                                      //get all expenses before the selected date
                                                                                                                                                      $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                                                                                                                      if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                                      $total_debitx=+$rs_trans_ox[0];
                                                                                                                                                      $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                                                                                                      $closing = $closing + $closingbal;
                                                                                                                                                 }
                                                                                                                                                 for($ic=0; $ic < count($acc); $ic++){
                                                                                                                                                      $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                                                                                                      echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                                                                                                                      $sn++;
                                                                                                                                                 }
                                                                                                                                                 ?>

                                                                                                                                                 <tr>
                                                                                                                                                      <td colspan="4" align="right">&nbsp;</td>
                                                                                                                                                 </tr>

                                                                                                                                                 <tr>
                                                                                                                                                      <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                                                                                                                 </tr>
                                                                                                                                            </table>
                                                                                                                                       </td>
                                                                                                                                  </tr>

                                                                                                                                  <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                                                                  </table>

                                                                                                                                  <?php
                                                                                                                             } //
                                                                                                                             if($option=='note38')
                                                                                                                             {
                                                                                                                                  ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                                                                                                       <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 38 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                                                                                                       <tr>
                                                                                                                                            <!-- Debit Column-->
                                                                                                                                            <td colspan="2" valign="top">
                                                                                                                                                 <br>
                                                                                                                                                 <table width="100%" align="center">
                                                                                                                                                      <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>

                                                                                                                                                      <?php
                                                                                                                                                      //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                                                                                                      $sn=1;
                                                                                                                                                      $sqll3 = "select distinct t.folio_code from transtb_final t WHERE year(transdate) = '".($ryear)."' and  folio_code in ('01-002-5064')";
                                                                                                                                                      $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                                                                                                      $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                                                                                                                      $acc = array();	$acctotal = array();
                                                                                                                                                      while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                                                                                                           $account = $acode = $acc[] = $accode['folio_code'];

                                                                                                                                                           $rstrans_ox= mysqli_query($con, "select sum(amount) as total_credit from transtb WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                                                                                                                           if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                                           $total_credixt=+$rs_trans_ox[0];

                                                                                                                                                           //get all expenses before the selected date
                                                                                                                                                           $rstrans_ox= mysqli_query($con, "select sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                                                                                                                           if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                                           $total_debitx=+$rs_trans_ox[0];
                                                                                                                                                           $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                                                                                                           $closing = $closing + $closingbal;
                                                                                                                                                      }
                                                                                                                                                      for($ic=0; $ic < count($acc); $ic++){
                                                                                                                                                           $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                                                                                                           echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                                                                                                                           $sn++;
                                                                                                                                                      }
                                                                                                                                                      ?>

                                                                                                                                                      <tr>
                                                                                                                                                           <td colspan="4" align="right">&nbsp;</td>
                                                                                                                                                      </tr>

                                                                                                                                                      <tr>
                                                                                                                                                           <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                                                                                                                      </tr>
                                                                                                                                                 </table>
                                                                                                                                            </td>
                                                                                                                                       </tr>

                                                                                                                                       <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                                                                       </table>

                                                                                                                                       <?php
                                                                                                                                  } //
                                                                                                                                  if($option=='note10')
                                                                                                                                  {
                                                                                                                                       ?><table align="center" width="100%" cellpadding="0" cellspacing="0" border="1">
                                                                                                                                            <tr><td height="27" colspan="2" align="center" bgcolor="#FFFFCC"><p><strong><h1>NOTE 10 </h1><br><?php //echo "<h2>".$acctname; echo "($account)</h2>";?> </strong></p></td><tr><td colspan="2" align="center" bgcolor="#E5E5E5"></td></tr>
                                                                                                                                            <tr>
                                                                                                                                                 <!-- Debit Column-->
                                                                                                                                                 <td colspan="2" valign="top">
                                                                                                                                                      <br>
                                                                                                                                                      <table width="100%" align="center">
                                                                                                                                                           <tr><td height="24" bgcolor="#E5E5E5"><strong>S/NO</strong></td><td bgcolor="#E5E5E5"><strong>CODE</strong></td><td bgcolor="#E5E5E5"><strong>PARTICULARS</strong></td><td bgcolor="#E5E5E5"><strong>AMOUNT</strong></td></tr>
                                                                                                                                                           <?php
                                                                                                                                                           //echo "select sum(amount) as total_debit from transtb WHERE dept_acctcode='$deptcode' and transtype='Debit' and transdate <= '$from'";
                                                                                                                                                           $sn=1;
                                                                                                                                                           $sqll3 = "SELECT distinct t.folio_code from transtb t INNER JOIN foliotb f ON t.folio_code=f.folio_code WHERE year(transdate) = '".($ryear)."' AND `fundcenter` != '09' AND `itemcode` LIKE '10%' AND f.category NOT IN ('02-731D', '02-731A')";
                                                                                                                                                           $msql3= mysqli_query($con, $sqll3) or die( mysqli_error($con));
                                                                                                                                                           $closing = 0;	$total_credixt=0;   $total_debitx=0;
                                                                                                                                                           $acc = array();	$acctotal = array();
                                                                                                                                                           while($accode = mysqli_fetch_array($msql3, 3 )){

                                                                                                                                                                $account = $acode = $acc[] = $accode['folio_code'];

                                                                                                                                                                $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_credit from transtb_final WHERE transtype='Credit' and year(transdate) = '".($ryear)."' and folio_code = '$account' ") or die( mysqli_error($con));
                                                                                                                                                                if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                                                $total_credixt=+$rs_trans_ox[0];

                                                                                                                                                                //get all expenses before the selected date
                                                                                                                                                                $rstrans_ox= mysqli_query($con, "SELECT sum(amount) as total_debit from transtb WHERE transtype='Debit' and year(transdate) = '".($ryear)."' and folio_code = '$account'") or die( mysqli_error($con));
                                                                                                                                                                if($rs_trans_ox=@mysqli_fetch_array($rstrans_ox, 3 ))

                                                                                                                                                                $total_debitx=+$rs_trans_ox[0];
                                                                                                                                                                $closingbal = $acctotal[] = ($total_debitx - $total_credixt);

                                                                                                                                                                $closing = $closing + $closingbal;
                                                                                                                                                           }
                                                                                                                                                           for($ic=0; $ic < count($acc); $ic++){
                                                                                                                                                                $fcod = @$bursary->get_any_value('title', 'foliotb', 'folio_code', $acc[$ic]);
                                                                                                                                                                echo "<tr class='$rowclass'><td>$sn</td><td>".$acc[$ic]."</td><td>".$fcod."</td><td>".number_format(abs($acctotal[$ic]), 2)."</td></tr>";
                                                                                                                                                                $sn++;
                                                                                                                                                           }
                                                                                                                                                           ?>																													<tr>
                                                                                                                                                                <td colspan="4" align="right">&nbsp;</td>
                                                                                                                                                           </tr>

                                                                                                                                                           <tr>
                                                                                                                                                                <td></td><td></td><td><strong>Balance : </strong><td><b><?php echo number_format(abs($closing), 2); ?></b></td>
                                                                                                                                                           </tr>
                                                                                                                                                      </table>
                                                                                                                                                 </td>
                                                                                                                                            </tr>

                                                                                                                                            <tr><td align="right"><input type="button" class="row-a" value="Print" onclick="window.print()" /></th><td><input type="button" class="row-b" value="Close" onclick="window.close()" /></th></tr>
                                                                                                                                            </table>

                                                                                                                                            <?php
                                                                                                                                       } //





                                                                                                                                       ///////////////////////////////////////////////////// End of Body ////////////////////////////////////////////
                                                                                                                                       //////////////////////////////////////////////////////Report Footer /////////////////////////////////////////////
                                                                                                                                       ///////////////////////////////////////////////////// End of Footer ////////////////////////////////////////////
                                                                                                                                       ?>
                                                                                                                                  </body>
                                                                                                                                  </html>
