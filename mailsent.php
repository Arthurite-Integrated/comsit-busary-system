
<!-- The Modal -->
<div id="myModal" class="modal">

     <!-- Modal content -->
     <div class="modal-content">
          <span class="close" onclick="var modal = document.getElementById('myModal'); modal.style.display = 'none';">&times;</span>
          <p><div id=mailProcess></div></p>
     </div>

</div>
<legend>
          <font color="red">
               <b>Outgoing Mail</b>
          </font>
     </legend>
<hr>
<div title="Treated Mail" style="padding:10px">

     <input type="hidden" id="selectedMemoId" name="selectedMemoId" value="">
     <div id="tb" style="padding:2px 5px;">
          <a href="#" id="myBtn3" class="btn btn-gradient-light btn-rounded" iconCls="icon-tip" onClick="var modal = document.getElementById('myModal'); var btn = document.getElementById('myBtn3');
          sendRequest('viewMails', 'mailProcess'); modal.style.display = 'block'; ">View</a>
     </div>
     <hr>
     <table id="dg" title="" style="width:100%;" class="table table-hover table-striped display dataTable">
          <thead>
               <tr>
                    <th>SN</th>
                    <th>ID</th>
                    <th>FROM</th>
                    <th>ADDRESS/UNIT</th>
                    <th>DESCRIPTION</th>
                    <th>AMOUNT</th>
                    <th>DEPT/UNIT</th>
                    <th>DATE</th>
                    <th>STATUS</th>
               </tr>
          </thead>
          <tbody>
               <?php
               $r_vals=base64_decode($_REQUEST['r_val']);

               $sql="SELECT mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit, mm.deptunit_to FROM memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id where read_status='Read'  order by mm.id desc LIMIT 500";
               $r=mysqli_query($con, $sql);
               while ($row = mysqli_fetch_array($r, 3)) { ?>
               <tr>
               <td><?=++$sn; ?></td>
               <td><?=$row['memo_id']; ?></td>
               <td><?=$row['memo_from']; ?></td>
               <td><?php  if(is_numeric($row['address_unit'])) echo $cls->getRecord('dept_name', 'departmenttb', "dept_code", $row['address_unit']);
               else echo $row['address_unit']; ?></td>
               <td><?=$row['description']; ?></td>
               <td><?=$row['amount']; ?></td>
               <td><?php if(is_numeric($row['dept_unit'])) echo $cls->getRecord('unit_name', 'unittb', "unit_code", $row['dept_unit']);
               else echo $row['dept_unit']; ?></td>
               <td><?=$row['datein']." ".$row['entry_time']; ?></td>
               <td><?=$row['memo_status']; ?></td>
               </tr>
               <?php } ?>
               </tbody>
               </table>

               <div id="outmail"></div>
               <div id="out_query"></div>
               </div>
