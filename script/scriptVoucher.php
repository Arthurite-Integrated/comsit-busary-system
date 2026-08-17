<?php
     
     require_once("../myclass_m.php");
     $cls=new myclass_m();
     $cls->database_con();
     @session_start();
     $requestID=$_REQUEST["requestID"];

     if($requestID=="editMails"){
          $memoID=$cls->data($_REQUEST['memoID']);
          $mData=json_decode($cls->getJSONValue("SELECT mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit FROM memo_movementtb mm INNER JOIN memotb m ON mm.memo_id=m.memo_id WHERE m.memo_id='{$memoID}' AND mm.memo_status='IN' AND mm.read_status='Unread'"));
          if($mData->memo_id == ''){
                    echo "You can only edit unread memo.";
                    exit;
          }
          //print_r($mData);
          ?>
          <p style="margin-left:50px; margin-top:-35px; color:green;"><b>EDIT MEMO</b></p>
          <hr>
          <form action="scriptfile_m.php?contentvar=mfileupload" method="post" enctype="multipart/form-data" target="upload_target2" onsubmit="startUpload2();" class="formx" id="editmail" name="editmail" >
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                         <tr>
                              <td height="33" align="left" valign="middle"><strong>Memo ID:</strong></td>
                              <td height="33" align="left" valign="middle"><div id="vmemoid"><?=$mData->memo_id;?></div>
                              <input type="hidden" id="vmemoid_x" name="vmemoid_x" value="<?=$mData->memo_id;?>"/>
                              <input type="hidden" id="vlogin_id" name="vlogin_id" value="<?php echo $_SESSION['login_id']; ?>"/></td>
                          </tr>
                           <tr>
                              <td height="33" align="left" valign="middle"><strong>From:</strong></td>
                              <td height="33" align="left" valign="middle"><input value="<?=$mData->memo_from;?>" type="text" class="easyui-textbox" id="vmemofrom" name="vmemofrom" style="width:300px;" /></td>
                            </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Address/Unit:</strong></td>
                              <td height="33" align="left" valign="middle"><select name="vaddress_unit" id="vaddress_unit" style="width:300px;" >
                                        <option value="<?=$mData->dept_unit;?>" selected><?php if(is_numeric($mData->dept_unit)) echo $cls->getRecord('dept_name', 'departmenttb', "dept_code", $mData->dept_unit);
                                             else echo $mData->dept_unit;?></option>
                                        <?php  $q = mysqli_query($con, "SELECT * FROM departmenttb order by dept_name");
                                        while($r=mysqli_fetch_array($q, MYSQLI_BOTH)){
                                                  echo '<option value="'. $r['dept_code'] .'">'. $r['dept_name'] .'</option>';
                                        }
                                        ?>
                              </select></td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Memo Title/ Description:</strong></td>
                              <td height="33" align="left" valign="middle"><textarea id="vmemodesc" name="vmemodesc" class="easyui-textbox" style="width:300px;height:60px;"><?=$mData->description;?></textarea></td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="left"><strong>Amount Requested:</strong></td>
                              <td height="33" align="left" valign="middle"><input value="<?=$mData->amount;?>" type="text" class="easyui-textbox" id="vmemoamount" name="vmemoamount" style="width:300px;" /></td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Recieved into:</strong></td>
                              <td height="33" align="left" valign="middle"><select name="vmemodept" id="vmemodept" style="width:300px;" >
                                        <option value="<?=$mData->address_unit;?>" selected><?php if(is_numeric($mData->address_unit)) echo $cls->getRecord('unit_name', 'unittb', "unit_code", $mData->address_unit);
                                             else echo $mData->address_unit;?></option>
                                        <?php  $q = mysqli_query($con, "SELECT * FROM unittb WHERE dept_code='126' order by id");
                                        while($r=mysqli_fetch_array($q, MYSQLI_BOTH)){
                                        echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>';
                                        }
                                        ?>
                              </select></td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Date/Time:</strong></td>
                              <td height="33" align="left" valign="middle"><div id="vmemodate"><?=$mData->datein." ".$mData->entry_time;?></div></td>
                              <td height="33" align="left" valign="middle">&nbsp;</td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Status:</strong></td>
                              <td height="33" align="left" valign="middle" nowrap><span id="vmemoaction">Pending</span>&nbsp;&nbsp;<a id="vidoc" class='iframe btn btn-gradient-light btn-rounded' iconCls="icon-tip" href=""><strong><font color="#000099">View Document</font></strong></a><span id="mupdate">&nbsp;&nbsp;<a href="#" class="btn btn-gradient-light btn-rounded" iconCls="icon-save" onClick="swapcontent('editmails', $('#vmemodept').val(), $('#vmemofrom').val(), $('#vmemodesc').val(),$('#vmemoid_x').val(), $('#vmemoamount').val());">Update</a></span></td>
                              </tr>
                              <tr id="mupdate_r">
                              <td height="33" align="left" valign="middle"><strong>Document:</strong></td>
                              <td height="33" align="left" valign="middle">
                                        <span class="formx2" >

                                        <p id="f1_upload_form2" align="left"><br/>
                                                  <input name="myfile2" type="file" size="20" />
                                                  <input type="hidden" name="file_memo_id2" id="file_memo_id2" value="">
                                                  <label>
                                                  <input type="submit" name="submitBtn2" class="sbtn2 buttonx" value="Upload" />
                                                  </label>
                                        </p>
                                        <p id="f1_upload_process2">Loading...<br/><img src="images/ajax-loader.gif" /><br/></p>
                                        <iframe id="upload_target2" name="upload_target2" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                                        </span>
                              </td>
                              </tr>
                    </table>
                    </form>
                    <div id="editmails"></div>
          <?php
     }

     if($requestID=="viewMails"){
          $memoID=$cls->data($_REQUEST['memoID']);
          $mData=json_decode($cls->getJSONValue("SELECT mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit FROM memo_movementtb mm INNER JOIN memotb m ON mm.memo_id=m.memo_id WHERE m.memo_id='{$memoID}'"));

          //print_r($mData);
          ?>
          <p style="margin-left:50px; margin-top:-35px; color:green;"><b>VIEW MEMO</b></p>
          <hr>
          <form action="scriptfile_m.php?contentvar=mfileupload" method="post" enctype="multipart/form-data" target="upload_target2" onsubmit="startUpload2();" class="formx" id="editmail" name="editmail" >
                     <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                         <tr>
                              <td height="33" align="left" valign="middle"><strong>Memo ID:</strong></td>
                              <td height="33" align="left" valign="middle"><div id="vmemoid"><?=$mData->memo_id;?></div>
                              <input type="hidden" id="vmemoid_x" name="vmemoid_x" value="<?=$mData->memo_id;?>"/>
                              <input type="hidden" id="vlogin_id" name="vlogin_id" value="<?php echo $_SESSION['login_id']; ?>"/></td>
                          </tr>
                           <tr>
                              <td height="33" align="left" valign="middle"><strong>From:</strong></td>
                              <td height="33" align="left" valign="middle"><?=$mData->memo_from;?></td>
                            </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Address/Unit:</strong></td>
                              <td height="33" align="left" valign="middle">
                              <?php if(is_numeric($mData->dept_unit)) echo $cls->getRecord('dept_name', 'departmenttb', "dept_code", $mData->dept_unit);
                                             else echo $mData->dept_unit;?>
                              </td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Memo Title/ Description:</strong></td>
                              <td height="33" align="left" valign="middle"><?=$mData->description;?></td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="left"><strong>Amount Requested:</strong></td>
                              <td height="33" align="left" valign="middle"><?=$mData->amount;?></td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Recieved into:</strong></td>
                              <td height="33" align="left" valign="middle"><?php if(is_numeric($mData->address_unit)) echo $cls->getRecord('unit_name', 'unittb', "unit_code", $mData->address_unit);
                                             else echo $mData->address_unit;?></td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Date/Time:</strong></td>
                              <td height="33" align="left" valign="middle"><div id="vmemodate"><?=$mData->datein." ".$mData->entry_time;?></div></td>
                              <td height="33" align="left" valign="middle">&nbsp;</td>
                              </tr>
                              <tr>
                              <td height="33" align="left" valign="middle"><strong>Status:</strong></td>
                              <td height="33" align="left" valign="middle" nowrap><span id="vmemoaction">Pending</span><br><br><br>
                              <a id="vidoc" class='iframe btn btn-gradient-light btn-rounded' iconCls="icon-tip" href=""><strong><font color="#000099">View Document</font></strong></a><span id="mupdate">&nbsp;&nbsp;<a href="#" class="btn btn-gradient-light btn-rounded" iconCls="icon-save" onClick="swapcontent('editmails', $('#vmemodept').val(), $('#vmemofrom').val(), $('#vmemodesc').val(),$('#vmemoid_x').val(), $('#vmemoamount').val());">Update</a></span></td>
                              </tr>
                    </table>
                    </form>
                    <div id="editmails"></div>
          <?php
     }

     if($requestID=="readMails"){
          $memoID=$cls->data($_REQUEST['memoID']);
          $mData=json_decode($cls->getJSONValue("SELECT * FROM memotb m WHERE m.memo_id='{$memoID}'"));
          //print_r($mData);
          ?>
          <p style="margin-left:50px; margin-top:-35px; color:green;"><b>PROCESS MEMO</b></p>
          <hr>
          <form id= 'outmails'  enctype="multipart/form-data" name="outmails">
                    <table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                         <tr>
                              <td height="33" align="left" valign="middle">Memo ID:</td>
                              <td height="33" align="left" valign="middle">
                                        <div id="hmemoid"><?=$mData->memo_id;?></div>
                              </td>
                         </tr>
                         <tr>
                              <td height="33" align="left" valign="middle">From:</td>
                              <td height="33" align="left" valign="middle"><div id="hmemofrom"><?=$mData->memo_from;?></div></td>
                         </tr>
                         <tr>
                              <td height="33" align="left" valign="middle">Address/Unit:</td>
                              <td height="33" align="left" valign="middle"><div id="haddress_unit"><?php if(is_numeric($mData->address_unit)) echo $cls->getRecord('unit_name', 'unittb', "unit_code", $mData->address_unit);
                                             else echo $mData->address_unit;?></div></td>
                         </tr>
                         <tr>
                              <td height="33" align="left" valign="middle"><label for="fdept">To: (Faculty/Dept)</label></td>
                              <td height="33" align="left" valign="middle"><select name="fdept" id="fdept" onchange="swapcontent('load_unit',document.getElementById('fdept').value)" style="width:300px;">
                                   <option selected="selected" value="">---</option>
                                   <?php
                                   $res_c=mysqli_query($con, "SELECT * FROM departmenttb order by dept_name");
                                   while($rs_c=mysqli_fetch_array($res_c))
                                   {
                                        $dept_code=$rs_c['dept_code'];
                                        $dept_name=$rs_c['dept_name'];
                                        echo "<option value='$dept_code'>$dept_name</option>";
                                   }
                                   echo "<option value='others'>Others...</option>";
                                   echo "</select>";
                                   ?>
                              </select></td>
                         </tr>
                         <tr>
                              <td height="33" align="left" valign="middle"><label for="unit">Unit/Address: </label></td>
                              <td height="33" align="left" valign="middle"><span id="load_unit">
                                   <select name="unit" id="unit" style="width:300px;">
                                        <option selected="selected" value=''>---</option>
                                   </select>
                              </span>
                         </td>
                    </tr>
                    <tr>
                         <td height="33" align="left" valign="middle"><label for="action">Action:</label></td>
                         <td height="33" align="left" valign="middle"><select name="action" id="action" class="easyui-combobox" panelHeight="auto" style="width:300px;" >
                              <option value="" selected>Select item...</option>
                              <?php  $q = mysqli_query($con, "SELECT action from memo_actiontb order by action");
                              while($r=mysqli_fetch_array($q, MYSQLI_BOTH)){
                                   echo '<option value="'. $r['action'] .'">'. $r['action'] .'</option>'; } ?></select></td>
                              </tr>
                              <tr>
                                   <td height="33" align="left" valign="middle"><label for="remark">Comment:</label></td>
                                   <td height="33" align="left" valign="middle"><textarea id="remark" name="remark" class="easyui-textbox" style="width:300px;height:60px;"></textarea>
                                        <input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id; ?>"/>
                                        <input type="hidden" id="staff_category" name="staff_category" value="<?php echo $role; ?>"/>
                                        <input type="hidden" id="tmemoid" name="tmemoid" value="<?=$mData->memo_id;?>"/>
                                        <input type="hidden" id="memo_unit_code" name="memo_unit_code" value=""/></td>
                                   </tr>
                                   <tr>
                                        <td height="33" align="left" valign="middle">Amount Requested:</td>
                                        <td height="33" align="left" valign="middle" nowrap><span id="hmemoamountd">&#8358;<?=number_format($mData->amount, 2);?></span></td>
                                   </tr>
                                   <tr>
                                        <td height="33" align="left" valign="middle">Amount Approved:</td>
                                        <td height="33" align="left" valign="middle" nowrap><input type="text" id="hmemoamount" name="hmemoamount" value="<?=$mData->amount;?>" style="width:300px;" /></td>
                                   </tr>
                                   <tr>
                              <td height="33" align="center" valign="middle">&nbsp;</td>
                              <td height="33" align="left" valign="middle" nowrap>
                                        <a id="idoc" class='iframe btn btn-gradient-light btn-rounded' iconCls="icon-tip" href=""><strong><font color="#000099">View Document</font></strong></a>&nbsp;&nbsp;&nbsp;
                                        <a href="#" class="btn btn-gradient-light btn-rounded" iconCls="icon-save" onClick="swapcontent('outmail', $('#unit').val(), $('#action').val(), $('#remark').val(), $('#tmemoid').val(), $('#login_id').val(), $('#staff_category').val(), $('#memo_unit_code').val(), $('#hmemoamount').val());">Submit</a></td>
                              </tr>
                    </table>
                    <div id="outmail"></div>
              </form>
          <?php
     }

     if($requestID=="inmails")
          {
                    $memo_from = mysqli_real_escape_string($con, $_REQUEST['memo_from']);
                    $descs = mysqli_real_escape_string($con, $_REQUEST['desc']);
                    $amount = mysqli_real_escape_string($con, $_REQUEST['amount']);
                    $pvno= mysqli_real_escape_string($con, $_REQUEST['pvno']);
                    $memo_id= mysqli_real_escape_string($con, $_REQUEST['memo_id']); 
                    $dept_unit = mysqli_real_escape_string($con, $_REQUEST['dept_unit']);
                    $login_id = mysqli_real_escape_string($con, $_REQUEST['login_id']);
                    $dept_addr = mysqli_real_escape_string($con, $_REQUEST['dept_addr']);
                    $action = 'RECEIVED';
                    $remark = '';
                    $erro='';
                    $IsError=false;
                    /*if(!isset($_REQUEST['myfile'])) {
                              if($memo_from == ""){
                                        echo $error="Memo source (Memo From) is required!"; 
                                        $IsError=true;
                              }
                              if($descs == ""){
                                        echo $error="Memo description is required!";
                                        $IsError=true;
                              }
                    }*/
                    if($memo_id == ""){
                              echo $error="No Memo ID generated!"; 
                              $IsError=true;
                    }
                    //file process================================================================================================>
                    /*if($IsError) unset($_FILES["myfile"]);//
                    if(isset($_FILES["myfile"]) && $_FILES["myfile"]["name"] != ''){
                              
                              $temp = explode(".", $_FILES["myfile"]["name"]);
                              
                              //$allowedExts = array("txt","htm","html","php","css","js","json","xml","swf","flv","pdf","psd",                                        "ai","eps","eps","ps","doc","rtf","ppt","odt","ods");
                              $allowedExts = array("pdf");	
                              $extension = end($temp);
                              if( in_array($extension, $allowedExts)){		   // Edit upload location here
                              $destination_path = "upload_files/";  //getcwd().DIRECTORY_SEPARATOR;
                              
                              $result = 0;
                              
                              //$target_path = $destination_path.basename( $_FILES['myfile']['name']);
                              $target_path = $destination_path.str_replace('/', '', $memo_id).".pdf";
                              
                              if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
                                        $result = 1;
                              }
                              //@mysqli_query($con, "UPDATE memotb SET file_path='".mysqli_real_escape_string($con, $target_path)."' WHERE memo_id='".$memo_id."' limit 1");
                    
                              sleep(1);
                              }else
                              {	
                                        echo $error="File Error:::Invalid document type!"; $IsError=true;//exit;
                              }
                    }
                    echo "<script>
                              window.top.window.stopUpload($result);
                    </script>";*/
                    if(!$IsError){
                    $res_check=mysqli_query($con, "SELECT * FROM memotb where memo_from like '%$memo_from%' and description like '%$descs%' and amount='$amount'");
                    $numrow= mysqli_num_rows($res_check);
                    if($numrow == 1){
                              echo "<script>alert('This Record has been added before.');</script>"; 
                              exit;
                    }
                    $sql="insert into  memotb set memo_id='{$memo_id}', memo_from='{$memo_from}', description='{$descs}', amount='{$amount}', datein=Now(),entry_date=Now(),entry_time=Now(),entry_by='{$login_id}', file_path='".mysqli_real_escape_string($con, $target_path)."', address_unit='{$dept_addr}'";
                    $sql2="insert into memo_movementtb set memo_id='$memo_id', memo_status='IN', dept_unit='$dept_unit', date=Now(),action='$action', remark ='$remark', entry_date=Now(),entry_time=Now(),entry_by='$login_id'";
                    if(mysqli_query($con, $sql) && mysqli_query($con, $sql2))
                    {
                              $_SESSION['memo_id']=$memo_id;
                              if($pvno != ''){
                                        mysqli_query($con, "update vouchertb set memo_id = '$memo_id' where pvno='$pvno'");
                                        mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id = '$memo_id'");
                              }
                              echo "<script>alert('Record Saved Succesfully'); </script>";
                    }
                    else{
                              echo "<script>alert('Record NOT saved!');</script>"; 
                              }
                    }else{
                              echo "<script>alert('ERROR:::$error');</script>";
                    }
          }

     if($requestID=="viewVoucherResubmit")
     {
          $p = $_REQUEST['voucherID'];
          $rv = $_REQUEST['rv'];
          ?>
          <iframe src="voucher_resubmit.php?p=<?=$p;?>&rv=<?=$rv;?>" width="100%" height="600px" title="Payment Voucher Re-Submit"></iframe>
          <?php
          exit;
     }
     
     if($requestID=="viewVoucher")
     {
          $p = $_REQUEST['voucherID'];
          $rv = $_REQUEST['rv'];
          ?>
          <iframe src="voucher_report_y.php?p=<?=$p;?>&rv=<?=$rv;?>" width="100%" height="600px" title="Payment Voucher Display"></iframe>
          <?php
          exit;
     }

?>
