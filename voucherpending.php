<legend>
          <font color="red">
               <b>Pending Voucher</b>
          </font>
     </legend>
<hr>
<?php $r=strtolower($r_vals); if($r != "budget officer"){ ?>
          <div title="<?php if(@strtolower($r_vals)=='prepared officer') echo 'Prepared'; else echo 'Pending'; ?>" style="padding:10px">  <!-- pending tab  -->
          <?php

          $r=strtolower($r_vals);
          //echo $_SESSION['userunit']; exit;
          //echo $sql="SELECT * FROM vouchertb where dept_code='".$_SESSION['userunit']."' and prepared_by = '".$login_id."' and (checked_by='' OR checked_by is Null) order by voucher_date desc";exit;
          if($r=="super admin"  or $r=="administrator" or $r=="prepared officer")
          $sql="SELECT * FROM vouchertb where dept_code='".$_SESSION['userunit']."' or prepared_by = '".$login_id."' and checked_action != 'Queried' order by voucher_date desc";

          elseif($r=="super admin" or $r=="checked by officer" or $r=="administrator")
          $sql="SELECT * FROM vouchertb where (dept_code='".$_SESSION['userunit']."' or prepared_by = '".$login_id."') and (checked_by='' OR checked_by is Null) order by voucher_date desc";
          elseif($r=="super admin"  or $r=="administrator" or $r=="authorized officer")//or $r=="authorized officer"
          $sql="SELECT * FROM vouchertb where dept_code='".$_SESSION['userunit']."' and (checked_by!='' OR checked_by IS NOT Null) and checked_action='Approved' and (authorized_by='' OR authorized_by IS Null) order by voucher_date desc";
          elseif($r=="super admin" or $r=="administrator" or $r=="expenditure control")
          $sql="SELECT * FROM vouchertb where (checked_by!='' OR checked_by IS NOT Null) and (authorized_by!='' OR authorized_by IS NOT Null) and authorized_action!='' and (controlled_by='' OR controlled_by IS Null) order by voucher_date desc";
          elseif($r=="super admin" or $r=="final authorized officer")
          $sql="SELECT * FROM vouchertb where (checked_by!='' OR checked_by IS NOT Null) and (controlled_by!='' OR controlled_by IS NOT Null) and (authorized_by!='' OR authorized_by IS NOT Null) and (authorized_by2='' OR authorized_by2 IS Null) and controlled_action='Approved' order by voucher_date desc";

          elseif($r=="super admin" or $r=="auditor")
          $sql="SELECT * FROM vouchertb where (checked_by!='' OR checked_by IS NOT Null) and (controlled_by!='' OR controlled_by IS NOT Null) and (authorized_by!='' OR authorized_by IS NOT Null) and (authorized_by2!='' OR authorized_by2 IS NOT Null) and (audit_by='' OR audit_by IS Null) and controlled_action='Approved' order by voucher_date desc";
          // elseif($r=="super admin" or $r=="bursar")
          //    $sql="SELECT * FROM vouchertb where (checked_by!='' OR checked_by IS NOT Null) and (controlled_by!='' OR controlled_by IS NOT Null) and (authorized_by='' OR authorized_by IS Null) and controlled_action='Approved' order by voucher_date desc";
          elseif($r=="super admin" or $r=="cash officer")
          $sql="SELECT * FROM vouchertb where (checked_by!='' OR checked_by IS NOT Null) and (controlled_by!='' OR controlled_by IS NOT Null) and (authorized_by!='' OR authorized_by IS NOT Null) and (authorized_by2!='' OR authorized_by2 IS NOT Null) and (audit_by!='' OR audit_by IS Not Null) and (paid_by='' OR paid_by IS Null) and audit_action='Approved' order by voucher_date desc";
          $sql .= "  LIMIT 500 ";
          $res_v=mysqli_query($con, $sql);
          $sn=0; ?>
          <table id='MyTable' class='table table-hover table-striped display dataTable' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
                    <thead>
                              <tr style='border:solid 1px #000; background-color:#f2f2f2'>
                                        <th>S/NO</th><th>NARRATION</th>
                                        <!--th>PV NO</th-->
                                        <th>PAYEE</th>
                                        <th>PAYEE ACCT NO.</th>
                                        <!--th>PAYEE BANK</th-->
                                        <th>DATE</th>
                                        <th>GROSS (NET)</th>
                                        <!--th>CHECKED</th><th>CERTIFIED</th>
                                        <th>CONTROLLED</th><th>AUDITED</th>
                                        <th>PAID</th-->
                                        <th>ACTION</th>
                              </tr>
                    </thead>
                    <tbody> <?php
                    if(@mysqli_num_rows($res_v)>=1)
                    {
                              while($rs_v=mysqli_fetch_array($res_v))
                              {
                                        ++$sn;
                                        $pvno=$rs_v['pvno']; 
                                        $pvno_paid=$rs_v['pvno_paid'];
                                        $desc=$rs_v['description'];
                                        $p=base64_encode($pvno);	
                                        $r_id = $rs_v['id'];
                                        $payee_name=$rs_v['payee_name'];
                                        $payee_acct_no=$rs_v['payee_acct_no'];
                                        $payee_bank_name=$rs_v['payee_bank_name'];
                                        $voucher_date=$rs_v['voucher_date'];
                                        $net = number_format($rs_v['amount_approved'], 2);
                                        $pv = explode('_', $pvno);
                                        $res_ds=mysqli_query($con, "SELECT amount_approved from vouchertb where pvno='".$pv[0]."'");
                                        while($rs_ds=mysqli_fetch_array($res_ds)) $amnt_app = $rs_ds[0];
                                        $gross = read_gross($pvno);
                                        //$gross=number_format($amnt_app, 2);

                                        $prepared=$rs_v['prepared_by']; 	          $prepared_date=$rs_v['date_prepared'];
                                        $checked=$rs_v['checked_by']; 		$checked_action=$rs_v['checked_action'];
                                        $checked_date=$rs_v['date_checked'];	          $checked_remark=$rs_v['checked_remark'];
                                        $authorized=$rs_v['authorized_by'];	          $authorized_action=$rs_v['authorized_action'];
                                        $authorized_date=$rs_v['date_authorized'];	$authorized_remark=$rs_v['authorized_remark'];
                                        $controlled=$rs_v['controlled_by'];		$controlled_action=$rs_v['controlled_action'];
                                        $controlled_date=$rs_v['date_controlled'];	$controlled_remark=$rs_v['controlled_remark'];
                                        $audited=$rs_v['audit_by'];			$audit_action=$rs_v['audit_action'];
                                        $audit_date=$rs_v['date_audited'];		$audit_remark=$rs_v['audit_remark'];
                                        $paid=$rs_v['paid_by'];			$paid_action=$rs_v['paid_action'];
                                        $paid_date=$rs_v['date_paid'];		$paid_remark=$rs_v['paid_remark'];

                                        //$tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p'>VIEW</a></tr>";
                                        ?>
                                        <tr>
                                                  <td><?=$sn; ?></td>
                                                  <td style='font-size:10px;'><?=$desc;?></td>
                                                  <!--td>$pvno_paid</td-->
                                                  <td><?=$payee_name;?></td>
                                                  <td><?=$payee_acct_no." (".$payee_bank_name.")";?></td>
                                                  <!--td>$payee_bank_name</td-->
                                                  <td><?=date('d/m/Y',strtotime($voucher_date));?></td>
                                                  <td><?=$gross." (".$net.")";?></td>
                                                  <!--td><a href='#' title='<?=$checked_date;?>'><?=$checked_action;?></a><br><span style='font-size:10px;'><?=$checked_remark;?></span></td>
                                                  <td><a href='#' title='<?=$authorized_date;?>'><?=$authorized_action;?></a><br><span style='font-size:10px;'><?=$authorized_remark;?></span></td>
                                                  <td><a href='#' title='<?=$controlled_date;?>'><?=$controlled_action;?></a><br><span style='font-size:10px;'><?=$controlled_remark;?></span></td>
                                                  <td><a href='#' title='<?=$audit_date;?>'><?=$audit_action;?></a><br><span style='font-size:10px;'><?=$audit_remark;?></span></td>
                                                  <td><a href='#' title='<?=$paid_date;?>'><?=$paid_action;?></a><br><span style='font-size:10px;'><?=$paid_remark;?></span></td-->
                                                  <?php
                                                  if($r=="cash officer"){ ?>
                                                            <td nowrap><a class='iframe' onclick="var modal = document.getElementById('myModal'); var btn = document.getElementById('myBtn');
               sendRequest('viewVoucher', 'voucherProcess', '<?=$p;?>', '<?=$r;?>'); modal.style.display = 'block'; " >VIEW/PROCESS</a><?php
                                                  }else{ ?>
                                                            <td nowrap>
                                                                 <a class='iframe' href='#' id='myBtn' onclick="var modal = document.getElementById('myModal'); var btn = document.getElementById('myBtn');
               sendRequest('viewVoucher', 'voucherProcess', '<?=$p;?>', '<?=$r;?>'); modal.style.display = 'block'; " >VIEW</a>
                                                                 <?php
                                                            if($r=="prepared officer" and ($checked == '' or $checked_action == 'Queried') and ($prepared == $login_id or $r=="super admin" or $r=="administrator")){ 
                                                                      ?>
                                                            | <a href="javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_entry','delete','<?=$r_id;?>');">DELETE</a></td></tr>
                                                            <?php } else { 
                                                                      ?> | <a class='iframe' onclick="var modal = document.getElementById('myModal'); var btn = document.getElementById('myBtn');
                                                                      swapcontent('display_voucher_process','<?=$pvno;?>','<?=$r_vals;?>'); modal.style.display = 'block'; ">PROCESS</a></td></tr><?php
                                                            }
                                                  } ?>
                                        </tr> <?php
                              } //end of while
                    }
                    ?>
                    </tbody>
                    </table> 
                    <div id="myModal" class="modal">
                         <!-- Modal content -->
                         <div class="modal-content">
                              <span class="close" onclick="var modal = document.getElementById('myModal'); modal.style.display = 'none';">&times;</span>
                              <p><div id=voucherProcess></div></p>
                         </div>
                    </div>
          </div>
<?php } ?>