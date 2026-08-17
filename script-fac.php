<script>
     $(document).ready(function() { //parent.jQuery.colorbox.close(); 
$(".iframe").colorbox({iframe:true, width:"53%", height:"100%"});

    $('#MyTable3').DataTable( {  
        initComplete: function () {  
            this.api().columns().every( function () {  
                var column = this;  
                var select = $('<select><option value=""></option></select>')  
                    .appendTo( $(column.footer()).empty() )  
                    .on( 'change', function () {  
                        var val = $.fn.dataTable.util.escapeRegex(  
                            $(this).val()  
                        );  
                //to select and search from grid  
                        column  
                            .search( val ? '^'+val+'$' : '', true, false )  
                            .draw();  
                    } );  
   
                column.data().unique().sort().each( function ( d, j ) {  
                    select.append( '<option value="'+d+'">'+d+'</option>' )  
                } );  
            } );  
        }  
    } );  
} );
 </script>
<?php
     @session_start();
     @require_once('connect.php');
     @require_once('function.php');
     @require_once('class/mysqli_class.php');
	$db = new Database();
	$db->connect();
     $requestID=$_REQUEST["requestID"];

     if($requestID=="batchUpdate"){
          $pv_jv=$_REQUEST['pv_jv'];
          $tpv_jv=$_REQUEST['tpv_jv'];
          $hpv_jv=$_REQUEST['hpv_jv'];
          $tyear=$_REQUEST['tyear'];
          begin();$c=0; 
          foreach($pv_jv as $pv => $i){
               if($i != ''){
                    //echo $i;
                    $query[] = "UPDATE transtb SET pvno='{$tpv_jv[$pv]}' WHERE pvno='{$hpv_jv[$pv]}' AND YEAR(transdate)='{$tyear}'";
                    $query[] = "UPDATE journaltb SET journalno='{$tpv_jv[$pv]}' WHERE journalno='{$hpv_jv[$pv]}'";
                    $query[] = "UPDATE journal_folio_codetb SET journalno='{$tpv_jv[$pv]}' WHERE journalno='{$hpv_jv[$pv]}'";
               }
          }
          $flag=false;
          foreach($query as $qry){
               //echo $qry,"<br>";
                    if(mysqli_query($con, $qry))
                              $flag=true;
                    else{
                              $flag=false;
                              rollback();
                              echo "<script>alert('Operation Failed! Transaction was canceled.');</script>";
                              exit;
                              break;
                    }
          }
          
          commit();
          echo "<script>alert('Record saved successfully!');</script>";
          exit;
     }

     if($requestID=='savePostedJournal'){
          $login_id=$_SESSION['login_id'];							     
          $pvno= mysqli_real_escape_string($con, $_REQUEST['pvno']);			$acct =  mysqli_real_escape_string($con, $_REQUEST['account']);
          $trans_id= mysqli_real_escape_string($con, $_REQUEST['transid']);	$folio_code =  mysqli_real_escape_string($con, $_REQUEST['folio']);
          $r_vals=$_REQUEST['r_vals'];								     $amount22= mysqli_real_escape_string($con, $_REQUEST['amount22']);
          $r=strtolower($r_vals);										$transdate= mysqli_real_escape_string($con, $_REQUEST['transdate']);
          $amount= mysqli_real_escape_string($con, $_REQUEST['amount']); 		$pvno22= mysqli_real_escape_string($con, $_REQUEST['pvno22']);
          $folio_code22= mysqli_real_escape_string($con, $_REQUEST['folio22']); $transtype= mysqli_real_escape_string($con, $_REQUEST['transtype']);
          $payee= mysqli_real_escape_string($con, $_REQUEST['payee']);
          begin();
          if($acct=='') $acct='---';
          $transtype == '';
          if($transtype=="Credit") $transtype = " cr_amount = (cr_amount - {$amount22}) + {$amount}, ";
          if($transtype=="Debit") $transtype = " dr_amount = (dr_amount - {$amount22}) + {$amount}, ";
          $uqry="UPDATE transtb SET acctcode='{$acct}', folio_code='{$folio_code}', amount={$amount}, transdate='{$transdate}', pvno='{$pvno22}' WHERE id='{$trans_id}'";
          if( mysqli_query($con, $uqry)){
               if($pvno!=''){
                    $q2="UPDATE journal_folio_codetb SET folio_code='{$folio_code}', amount='{$amount}', journalno='{$pvno22}' WHERE journalno='$pvno' AND folio_code='{$folio_code22}' AND amount='{$amount22}'";
                    $q3="UPDATE journaltb SET $transtype journal_date='{$transdate}', acctcode='{$acct}', journalno='{$pvno22}', payee_name='{$payee}' WHERE journalno='{$pvno}'";
                    if( mysqli_query($con, $q2) &&  mysqli_query($con, $q3)){
                         commit();
                         echo "<script>alert('Journal entry update successful!');</script>";
                    }else{
                         rollback();
                         echo "<script>alert('Journal entry update failed!!');</script>";
                    }
               }
          }else{
               rollback();
               echo "<script>alert('Journal entry update failed!');</script>";
          }
          exit;
     }

     if($requestID=='savePostedJournal2'){
          $pv_jv=$_REQUEST['pv_jv'];
          $tyear=$_REQUEST['tyear'];
          begin();$c=0; 
          foreach($pv_jv as $pv){
               if($pv!=''){
                    $query = "SELECT DISTINCT * FROM journaltb WHERE id='{$pv}' AND year(journal_date) = '{$tyear}' ORDER BY journalno";
                    $cj=$pv;
                    $qry = mysqli_query($con, $query);
                    while($j=mysqli_fetch_array($qry, 3)){
                         $in=mysqli_query($con, "SELECT * FROM journal_folio_codetb WHERE journalno='{$j['journalno']}'");
                         while($ij=mysqli_fetch_array($in, 3)){
                              $s1="INSERT INTO transtb SET dept_acctcode='".$j['dept_acctcode']."', acctcode='---', folio_code='".$ij['folio_code']."', transtype='{$ij['trans_type']}', transdate='".$j['journal_date']."', amount='".$ij['amount']."', chequeno='', pvno='".$j['journalno']."', comment='".$j['description']."', entry_date='{$j['entry_date']}', entry_time='{$j['entry_time']}', entry_by='{$j['entry_by']}', paybatch='".$j['paybatch']."'";
                              $r[]=@mysqli_query($con, $s1);
                         }
                    }
               }
          }
          $flag=false;
          foreach($r as $r_val){
                    if($r_val)
                              $flag=true;
                    else{
                              $flag=false;
                              rollback();
                              echo "<script>alert('Operation Failed! Transaction was canceled.');</script>";
                              exit;
                              break;
                    }
          }
          
          commit();
          echo "<script>alert('Record saved successfully');</script>";
          exit;
     }

     if($requestID=='movePosting'){
          $pv_jv=$_REQUEST['pv_jv'];
          $tyear=$_REQUEST['month_year'];
          begin();  $c=0; 
          foreach($pv_jv as $pv){
               if($pv!=''){
                    $query = "UPDATE transtb SET transdate='{$tyear}' WHERE id='{$pv}'";

                    $cj=$pv;
                    $qry = mysqli_query($con, $query);
               }
          }
          
          $flag=false;
          foreach($r as $r_val){
                    if($r_val)
                              $flag=true;
                    else{
                              $flag=false;
                              rollback();
                              echo "<script>alert('Operation Failed! Transaction was canceled.');</script>";
                              exit;
                              break;
                    }
          }
          
          commit();
          echo "<script>alert('Record saved successfully');</script>";
          exit;
     }

     if($requestID=="editPostedJournal"){
          $trans_id=$_REQUEST['trans_id'];
          $action=$_REQUEST['action'];
          if($action=="DELETE"){
               begin();
               $m=mysqli_query($con, "SELECT * FROM transtb WHERE id='{$trans_id}'");
               $r=mysqli_fetch_array($m,3);               
               $transdate=$r['transdate'];
               $pvno=$r['pvno'];
               $q=mysqli_query($con, "SELECT * FROM transtb WHERE pvno='{$pvno}'");
               while($qr=mysqli_fetch_array($q,3)){
                    $folio_code=$qr['folio_code'];
                    if( !mysqli_query($con, "DELETE FROM transtb WHERE pvno='{$pvno}' AND transdate='{$transdate}' AND folio_code='{$folio_code}'") ){
                         rollback();
                         echo "<script>alert('Record delete failed!');</script>";
                         exit;
                    }else{
                         @mysqli_query($con, "DELETE FROM journaltb WHERE journalno='{$pvno}' AND journal_date='{$transdate}'");
                         @mysqli_query($con, "DELETE FROM journal_folio_codetb WHERE journalno='{$pvno}' AND folio_code='{$folio_code}'");
                    }
               }
               commit();
               echo "<script>alert('Selected record deleted!');</script>";
          }else{
               $r_vals=$_REQUEST['r_vals'];
               $res_d=@mysqli_query($con, "select * from transtb where id=$trans_id");
               if( mysqli_num_rows($res_d)>0){
                    $rs_d=@mysqli_fetch_array($res_d, 3 );
                    $pvno = $rs_d['pvno'];							$trans_id = $rs_d['id'];
                    $transtype = $rs_d['transtype'];
                    $acct = $rs_d['acctcode'];
                    $folio_code = $rs_d['folio_code'];					$amount = $rs_d['amount'];
                    $transdate = $rs_d['transdate'];					$date = $rs_d['transdate'];
                    $payeen=$bursary->get_any_value("payee_name", "journaltb", "journalno", $rs_d['pvno']);
                    ?>
                    <form id="update_trans" method="post" action="" enctype="multipart/form-data">
                         <table>
                              <tr>
                                   <td height="33" align="left" valign="top" nowrap="nowrap">Jounal No.</td>
                                   <td height="33" align="left" valign="top">
                                        <?php echo $pvno; ?><br><input type="hidden" id="pvno" name="pvno" class="form-control" value="<?php echo $pvno; ?>" />
                                        <input type="text"  style="width: 300px" id="pvno22" name="pvno22" value="<?php echo $pvno; ?>" />
                                        <input type="hidden" id="transid" name="transid" value="<?php echo $trans_id; ?>" />
                                        <input type="hidden" id="transtype" name="transtype" value="<?php echo $transtype; ?>" />
                                   </td>
                              </tr>
                              <tr>
                                   <td height="33" align="left" valign="middle" nowrap="nowrap">Cr/Dr Acct.:</td>
                                   <td height="33" align="left" valign="middle">
                                        <select name="account" id="account" class="txt" style="width: 300px"  >
                                        <option selected="selected" value="<?php echo $acct; ?>"><?php echo get_account_name($acct, false); ?></option>
                                             <?php
                                             $r=@mysqli_query($con, "SELECT distinct * from bank_accounttb WHERE status='Active' order by acctcode");
                                             while ($rcourse=@mysqli_fetch_array($r))
                                             {
                                                  $scourse=@$rcourse['acctname'];		$pcode=@$rcourse['acctcode'];
                                                  $bank=@$rcourse['bankname'];		     $acctno=@$rcourse['acctno'];
                                                  $acctname=@$rcourse['acctname'];
                                                  echo "<option value='$pcode'>$bank || $acctno || $scourse <=> ($pcode)</option>";

                                             }
                                             ?>
                                        </select>
                                   </td>
                              </tr>
                              <tr>
                                   <td height="33" align="left" valign="middle" nowrap="nowrap">Former Code:</td>
                                   <td height="33" align="left" valign="middle"><?php echo $folio_code."<==>"; ?><?php echo get_folio_name($folio_code); ?></td>
                              </tr>
                              <tr>
                                   <td height="33" align="left" valign="middle" nowrap="nowrap">Item Code:</td>
                                   <td height="33" align="left" valign="middle">
                                   <input type="hidden" name="folio22" id="folio22" value="<?php echo $folio_code; ?>">
                                        <input type="folio" name="folio" id="folio" value="<?php echo $folio_code; ?>" style="width: 300px">
                                   </td>
                              </tr>
                              <tr>
                                   <td height="33" align="left" valign="middle" nowrap="nowrap">Amount:</td>
                                   <td height="33" align="left" valign="middle">
                                        <input type="hidden" id="amount22" name="amount22" value="<?php echo $amount; ?>" />
                                        <input type="number" name="amount" id="amount" value="<?php echo $amount; ?>" style="width: 300px">
                                   </td>
                              </tr>
                              <tr>
                                   <td height="33" align="left" valign="middle" nowrap="nowrap">Payee Name:</td>
                                   <td height="33" align="left" valign="middle">
                                        <input type="text" name="payee" id="payee" value="<?php echo $payeen; ?>" style="width: 300px">
                                   </td>
                              </tr>
                              <tr>
                                   <td height="33" align="left" valign="middle" nowrap="nowrap">Transaction Date:</td>
                                   <td height="33" align="left" valign="middle"><input type="date" name="transdate" id="transdate" value="<?php echo $date; ?>" style="width: 300px"></td>
                              </tr>
                              <tr>
                                   <td height="33" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
                                   <td height="33" align="left" valign="middle"><input type='button' name='cmdpro' id='cmdpro' value='Save Entry' onclick="sendRequest('savePostedJournal');" class='btn'/></td>
                              </tr>
                              <tr>
                                   <td height="33" colspan="2" align="left" valign="middle" nowrap="nowrap"><div id='savePostedJournal'></div></td>
                              </tr>
                         </table>
                    </form>
                    <?php
               }else{
                    echo "<font color='red'><b>No record to display</b></font>";
               }
          }
          exit;
     }

     if($requestID=="movetoledger"){
          $pv_jv=$_REQUEST['pv_jv'];
          ////print_r($pv_jv); exit;
          $tyear=$_REQUEST['tyear'];
          begin();$c=0; 
          foreach($pv_jv as $pv){
               if($pv!=''){
                    $query = "SELECT DISTINCT pvno, transdate, amount, dept_acctcode, acctcode, comment, entry_by, transtype, id FROM transtb WHERE id='{$pv}' AND year(transdate) = '{$tyear}' ORDER BY pvno";
                    $cj=$pv;
                    $qry = mysqli_query($con, $query);
                    while($j=mysqli_fetch_array($qry, 3)){
                              $s1="UPDATE transtb SET acctcode='---' WHERE id='{$pv}' AND year(transdate) = '{$tyear}'";
                              $r[]=@mysqli_query($con, $s1);
                              $transtype='';
                              if($j['transtype']=="Credit") $transtype = " cr_amount = {$j['amount']} ";
                              if($j['transtype']=="Debit") $transtype = " dr_amount = {$j['amount']} ";
                              $q=mysqli_query($con, "SELECT * FROM journaltb WHERE journalno={$j['pvno']}'");
                              //echo "{$c}==0 && $cj=={$cj2}<br>";
                              if($c==0 && $cj!=$cj2){
                                   $transtype .= ", ";
                                   $s2 = "INSERT into journaltb SET {$transtype} journalno='{$j['pvno']}', journal_date='{$j['transdate']}', acctcode='---', dept_code='".$j['dept_acctcode']."', description='".$j['comment']."', prepared_by='{$j['entry_by']}', entry_date='{$j['transdate']}', entry_time=now(), entry_by='{$j['entry_by']}', entry_type='Originated'";
                                   $c=1;     $cj2=$j['id'];
                                   $r[]=@mysqli_query($con, $s2);
                              }else {
                                   $s22[] = "UPDATE journaltb SET {$transtype} WHERE journalno='{$j['pvno']}'";
                                   $c=0;
                              }
                              
                              //dr_amount=".$j['amount'].", cr_amount=".$j['amount'].",  
                              
                    }

                    $query2 = "SELECT DISTINCT pvno, amount, folio_code, transtype FROM transtb WHERE id='{$pv}' AND year(transdate) = '{$tyear}'";
                    $qry2 = mysqli_query($con, $query2);
                    while($j=mysqli_fetch_array($qry2, 3)){
                              $s3 = "INSERT into journal_folio_codetb set journalno='".$j['pvno']."', folio_code='".$j['folio_code']."', amount='".$j['amount']."', paid='Yes', trans_type='{$j['transtype']}'";
                              $r[]=@mysqli_query($con, $s3);
                    }
               }
          }
          $flag=false;
          foreach($r as $r_val){
                    if($r_val)
                              $flag=true;
                    else{
                              $flag=false;
                              rollback();
                              echo "<script>alert('Operation Failed! Transaction was canceled.');</script>";
                              exit;
                              break;
                    }
          }
          
          commit();
          foreach($s22 as $r_val){
               $r_val;
               @mysqli_query($con, $r_val);
          }
          echo "<script>alert('Record saved successfully');</script>";
          exit;
     }

     if($requestID=="assignPVScheddule"){
          $pv_jv=$_REQUEST['pv_jv'];
          $tdate=$_REQUEST['tdate'];
          $fileno=$_REQUEST['tstaff'];
          $login_id=$_SESSION['login_id'];
          begin();$c=0; 
          foreach($pv_jv as $pv){
               if($pv!=''){
                    $query = "SELECT * FROM vouchertb WHERE id='{$pv}' ORDER BY id";
                    $cj=$pv;
                    $qry = mysqli_query($con, $query);
                    while($j=mysqli_fetch_array($qry, 3)){
                         $chk=mysqli_query($con, "SELECT * FROM pv_pay_scheduletb WHERE pvid='{$pv}' AND status='Pending'");
                         if(mysqli_num_rows($chk)<=0){
                              $s1="INSERT INTO pv_pay_scheduletb (pvid, fileno, sdate, entryby, datecreated) VALUES ('{$pv}', '{$fileno}', '{$tdate}', '{$login_id}', now())";
                              $r[]=@mysqli_query($con, $s1);
                         }else{
                              echo $s1="UPDATE pv_pay_scheduletb SET fileno='{$fileno}', sdate='{$tdate}', entryby='{$login_id}', datecreated=now() WHERE pvid='{$pv}' AND status='Pending'";
                              $r[]=@mysqli_query($con, $s1);
                         }
                    }
               }
          }
          $flag=false;
          foreach($r as $r_val){
                    if($r_val)
                              $flag=true;
                    else{
                              $flag=false;
                              rollback();
                              echo "<script>alert('Operation Failed! Transaction was canceled.');</script>";
                              exit;
                              break;
                    }
          }
          
          commit();
          foreach($s22 as $r_val){
               $r_val;
               @mysqli_query($con, $r_val);
          }
          echo "<script>alert('Record saved successfully');</script>";
          exit;
     }

     if($requestID=="viewPVScheddule"){
          $sdate=$_REQUEST['sdate'];
          $fileno=$_REQUEST['fileno'];
          $staff_name=strtoupper(get_staff_name($fileno));
          $date=date('jS F Y', strtotime($sdate));
          echo "<hr><h4>{$staff_name} ($fileno) <br>DATE: {$date}</h4>";          

          $sql="SELECT v.* FROM pv_pay_scheduletb p INNER JOIN vouchertb v ON p.pvid=v.id WHERE p.fileno='{$fileno}' AND p.sdate='{$sdate}' ORDER BY v.pre_pvtype, v.pre_pvserial ASC";
          $res_v=@mysqli_query($con, $sql);
          $sn=0;
          echo "<table id='MyTable3' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box' with='100%'>
          <thead> 
          <tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>PV NO</th><th>PAYEE</th><th>DESCRIPTION</th><th>CODE</th><th>AMOUNT</th><th>BANK</th><th>ACCOUNT</th><th>PAY STATUS</th><th>ACTION</th></tr></thead><tbody>";
          if(@mysqli_num_rows($res_v)>=1)
          {
               while($rs_v=@mysqli_fetch_array($res_v))
               {
                    ++$sn;
                    $pvno=$rs_v['pvno']; 
                    $pvno_paid=$rs_v['pvno_paid'];
                    $pre_pvno_paid=$rs_v['pre_pvno'];
                    $p=base64_encode($pvno);
                    $payee_name=strtoupper($rs_v['payee_name']);
                    $payee_acct_no=$rs_v['payee_acct_no'];
                    $payee_bank_name=strtoupper($rs_v['payee_bank_name']);
                    $voucher_date=$rs_v['voucher_date'];
                    $ds=mysqli_query($con, "SELECT folio_code FROM voucher_folio_codetb WHERE pvno='{$pvno}'");
                    if(mysqli_num_rows($ds)==1){
                              $code=get_voucher_folio_code($rs_v['pvno'], 'Code');
                              $desc=strtoupper(get_voucher_folio_code($rs_v['pvno'], 'Title'));
                    }else{
                              $code="VARIOUS";
                              $desc="REFUND";
                    }
                    if($rs_v['paid_action']=='Approved') $paid="PAID<br>(".date('m-d-Y', strtotime($rs_v['date_paid'])).")";
                    else $paid='';

                    $net = number_format($rs_v['amount_approved'], 2);
                    $gross = read_gross($pvno);             $yr = date('y', strtotime($prepared_date));
                    if(date('d/m/Y',strtotime($audit_date))=="01/01/1970") $au_date = '';
                    else $au_date = date('d/m/Y',strtotime($audit_date));
                    echo "<tr><td>$sn</td>
                    <td>$pre_pvno_paid</td>
                    <td>$payee_name</td>
                    <td>$desc</td>
                    <td>$code</td>
                    <td>".$net."</td>
                    <td>$payee_bank_name</td>
                    <td>$payee_acct_no</td>
                    <td>$paid</td>
                    <td><a class='iframe' href='voucher_report.php?p=$p' target='_blank'>VIEW</a> | <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">RETURN</a></td>";
                    echo "</tr>";
               } //end of while
                    
               echo "</tbody></table>";
          }
          else
          echo "<font color='red'><b>No pending voucher awaiting schedule.</b></font>";
     }

     if($requestID=="processVoucher")
     {
          //TREASURY OR CASH-OFFICE
          $acctcode=$_REQUEST['acctcode'];
          $batchno=$_REQUEST['batch_no'];
          $pay_date=@$_REQUEST['pay_date'];
          $pv_jv=$_REQUEST['pv_jv'];
          $month_name=@date('F',strtotime($pay_date));
          $month_no=@date('m',strtotime($pay_date));
          $year=@date('Y',strtotime($pay_date));
          $login_id=$_SESSION['login_id'];
          //$res_p=@mysqli_query($con, "select count(*) as total from vouchertb where month(date_paid)='$month_no' and year(date_paid)='$year'");
          //$rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);
          foreach($pv_jv as $pv){
               if($pv!=''){
                    $query = "SELECT * FROM vouchertb WHERE id='{$pv}' ORDER BY id";
                    $cj=$pv;
                    $qry = mysqli_query($con, $query);
                    $j=mysqli_fetch_array($qry, 3);
                    $pvno_final=$j['pre_pvno'];
                    $pvno=$j['pvno'];
                    $d=@mysqli_query($con, "SELECT * FROM transtb WHERE pvno='{$pvno_final}' AND transdate like '%{$year}%'");
                    $countpv = @mysqli_num_rows($d);
                    if($countpv > 0)
                    {
                         echo "<script language='javascript'>alert('PV. NO. ({$pvno_final}) already Exists, try again...');</script>";
                         continue;
                    }
                    begin();
     
                    $sql="UPDATE vouchertb SET pvno_paid='{$pvno_final}', acctcode='{$acctcode}', paid_by='{$login_id}', paid_action='Approved', paid_remark='{$comment}', date_paid='{$pay_date}', time_paid=CURTIME(), batchno='{$batchno}' WHERE id='{$pv}'";
                    $isPA=$bursary->get_any_value("purchase_advance", "vouchertb", "id", $pv);
                    mysqli_query($con, $sql) or die( mysqli_error($con));

                    $sql2 = "UPDATE `budget_votebooktb` set status = 'PAID' where voucher_pvno = '".$pvno."'";
     
                    $sqq= mysqli_query($con, "SELECT * FROM vouchertb WHERE id='{$pv}'");
                    if($rr= mysqli_fetch_array($sqq, 3 )){
                         //$sql_b="UPDATE vouchertb SET audit_by='{$login_id}', audit_action='Approved', audit_remark='{$comment}', audit_date='{$pay_date}', audit_time=CURTIME() WHERE id='{$pv}'";
                         //@mysqli_query($con, $sql_b) or die( mysqli_error($con));

                         $sqq2 = mysqli_query($con, "SELECT * FROM voucher_folio_codetb WHERE pvno='{$pvno}'");
                         while($rr2= mysqli_fetch_array($sqq2, 3 )){
                              $adv = $bursary->get_any_value("folio_code", "advancetb", "folio_code", $rr2['folio_code']);
                              if($adv != '') $isPA = 'Yes';
                              $sql3 = "INSERT INTO transtb SET dept_acctcode='{$rr['dept_code']}', acctcode='{$acctcode}', folio_code='{$rr2['folio_code']}', transtype='Debit', transdate='{$pay_date}', amount='{$rr2['amount']}', paybatch='{$batchno}', pvno='{$pvno_final}', comment='{$comment}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='{$isPA}'";
                              
                              if( mysqli_query($con, $sql3)) {
                                   //check if voucher is used to pay for staff loan the update loan table
                                   $lnq= mysqli_query($con, "SELECT * FROM hr_loan_apptb WHERE loan_no = '{$pvno}'");
                                   $lnnum= mysqli_num_rows($lnq);
                                   if($lnnum > 0){
                                        $sql4 = "UPDATE hr_loan_apptb SET process_status='Processed', process_date=Now(), payment_status='Paid' WHERE loan_no = '{$pvno}'";
                                        if( mysqli_query($con, $sql4)) $ERT=true;
                                        else{
                                             rollback();
                                             echo "<script>alert('Error :: Update failed!');</script>";
                                             continue;
                                        }
                                   }
                              }else {
                                   rollback(); 
                                   echo "<script>alert('Loop:::Update failed!');</script>"; 
                                   continue;
                              }
                         }
                    }
                    if( mysqli_query($con, $sql) &&  mysqli_query($con, $sql2)) {
                         commit();
                         @mysqli_query($con, "UPDATE pv_pay_scheduletb SET status='Paid' WHERE pvid='{$pv}'");
                         echo "<script>alert('PV. NO. ({$pvno_final}) provessed successfully');</script>";
                    }
                    else {
                         rollback();
                         echo "<script>alert('Update failed!');</script>";
                         continue;
                    }
               }
          }
          //echo "<script>parent.location.reload();</script>";
     } //end of cash officer
?>
