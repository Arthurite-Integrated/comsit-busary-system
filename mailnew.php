<div title="New Mail" style="padding:10px">
     <legend>
          <font color="red">
               <b>New Mail</b>
          </font>
     </legend>
     <p>&nbsp;</p>
     <form action="scriptfile_m.php?contentvar=inmails&files" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" class="formx" id="inmail" name="inmail" >
          <table width="100%" border="1" cellspacing="0" cellpadding="10" style="border-color:#369; border-width:thin;">
               <tr>
                    <td width="50%" align="left" valign="top">
                         <table width="50%" >
                              <tr>
                                   <td width="45%"><label for="memo_from">From:</label></td>
                                   <td width="55%"><input type="text" name="memo_from" id="memo_from" style="width:200px" required></td>
                              </tr>
                              <tr>
                                   <td>Address/Unit:</td>
                                   <td><select name="dept_addr" id="dept_addr" style="width:200px;" >
                                        <option value="" selected>Select item...</option>
                                        <?php  $q = mysqli_query($con, "SELECT * FROM departmenttb order by dept_name");
                                        while($r=mysqli_fetch_array($q, MYSQLI_BOTH)){
                                             echo '<option value="'. $r['dept_code'] .'">'. $r['dept_name'] .'</option>';
                                        }
                                        ?>
                                   </select></td>
                              </tr>
                              <tr>
                                   <td><label for="desc">Memo Title/ Description:</label></td>
                                   <td><textarea name="desc" id="desc" cols="25" rows="2" style="width:200px" required></textarea></td>
                              </tr>
                              <tr>
                                   <td><label for="amount">Amount Requested/Recieved:</label></td>
                                   <td><input type="text" name="amount" id="amount" style="width:200px" ></td>
                              </tr>
                              <tr>
                                   <td><label for="dept_unit">Recieved intox:</label></td>
                                   <td><select name="dept_unit" id="dept_unit" style="width:200px;" onChange="sendRequest('getnextmemo', $('#dept_unit').val());" required>
                                        <option value="" selected>Select Dept/Unit...</option>
                                        <?php  $q = mysqli_query($con, "SELECT * FROM unittb where dept_code='126' order by id");
                                        while($r=mysqli_fetch_array($q, MYSQLI_BOTH)){
                                             echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>';
                                        }
                                        ?>
                                   </select>
                                   <input type="hidden" name="dept_txt" id="dept_txt" value="">
                                   <input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id; ?>"/>
                              </td>
                         </tr>
                         <tr>
                              <td>PV. No.:</td>
                              <td><input type="text" name="pvno" id="pvno" style="width:200px" ></td>
                         </tr>
                         <tr>
                              <td width="45%">Memo ID:</td>
                              <td width="55%">
                                   <a href="#" name="gid" onClick="sendRequest('getnextmemo', $('#dept_unit').val());" style="float:left;" class="btn btn-success">Get Memo ID</a>
                              </td>
                         </tr>
                         <tr>
                              <td>&nbsp;</td>
                              <td><div id="getnextmemo" style="width:100%; text-align:left; padding:10px;" class="badge badge-outline-success">&nbsp;<input type="hidden" name="memo_id" id="memo_id"></div></td>
                         </tr>
                         <tr>
                              <td colspan="2">

                              </td>
                         </tr>
                    </table>
               </td>
               <td width="50%" align="left" valign="top">
                    <div id="container" style="margin: auto; width: 95%; border-top-width: 0px; border-width: 1px; border-style: solid; border-color: #000033; background-color: #FFFFFF;">
                         <div id="header" style="padding: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; background-image: url(images/header_bg.gif); background-repeat: repeat-x; height: 42px;">
                              <div id="header_left" style="float: left; background-image: url(images/header_left.gif); background-repeat: no-repeat; height: 42px; width: 45px;"></div>
                              <div id="header_main" style="float: left; padding: 5px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #FFFFFF; margin-top: 5px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">Upload supporting document...</div>
                              <div id="header_right" style="background-image: url(images/header_right.gif); background-repeat: no-repeat; height: 42px; width: 6px; float: right;"></div></div>
                              <div id="content" style="padding: 5px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #666666;">
                                   <span class="formx" >
                                        <p id="f1_upload_process">Loading...<br/><img src="images/ajax-loader.gif" /><br/></p>
                                        <p id="f1_upload_form" align="center"><br/>
                                             <label class="labelx" for="myfile">
                                                  <input name="myfile" id="myfile" type="file" size="30" accept="application/pdf" class="form-control" />
                                             </label>
                                             <input type="hidden" name="file_memo_id" id="file_memo_id" value="">
                                             
                                        </p>
                                        <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                                        [Document Type: pdf]
                                   </span><br><label>
                                                  <input type="button" name="submitBtn" id="submitBtn" class="btn btn-success" value="SUBMIT" onclick="if($('#myfile').val()==''){
                sendRequest('inmails');
            }else{
                $('#inmail').submit();
            }"; />
                                             </label>
                              </div>
                         </div>
                    </td>
               </tr>
          </table>
          <hr>
          <div id="inmails"></div>

     </form>

     <p>&nbsp;</p>
</div>
