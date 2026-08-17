
<div title="Community Service" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Community Service</b>
               </font>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Type of Service:</label>
                    <div class="">
                         <select name="serv_type" id="serv_type" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Administrative">Administrative</option>
                              <option value="Community">Community Development</option>
                         </select>
                    </div>
               </div>
               <div class="col-sm-3 x">
                    <label class="col-form-label">From:</label>
                    <div class="">
                         <select class="form-control" name="serv_from" id="serv_from">
                              <option selected="selected" value="">---Select Option---</option>
                              <?php
                              for($i=date('Y');$i>=date('Y')-100; $i--)
                              {
                                   echo "<option value='$i'>$i</option>";
                              }

                              ?>
                         </select>
                    </div>
               </div>
               <div class="col-sm-3 x">
                    <label class="col-form-label">To:</label>
                    <div class="">
                         <select class="form-control" name="serv_to" id="serv_to">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="To Date">To Date</option>
                              <?php
                              for($i=date('Y');$i>=date('Y')-100; $i--)
                              {
                                   echo "<option value='$i'>$i</option>";
                              }

                              ?>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Place of Service:</label>
                    <div class="">
                         <input class="form-control" name="serv_place" type="text" id="serv_place" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Service Details:</label>
                    <div class="">
                         <textarea name="serv_detail" id="serv_detail" cols="60" rows="3" class="form-control"></textarea>
                    </div>
               </div>
          </div>
     </fieldset>
     <br>
     <div class="row">
          <div class="col-sm-6 x">
               <input type="button" name="btnSer" id="btnSer" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addService', 'addNew');"/>
          </div>
     </div>
     <br>
     <div id="addService">
          <?php
          $cls->generateTable("SELECT id AS 'UID', service_type AS 'SERVICE TYPE', from_year AS 'FROM', to_year AS 'TO', service_place AS 'PLACE OF SERVICE', service_details AS 'DETAILS/DESCRIPTION OF SERVICE' FROM hr_staff_servicetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addService", "Delete");
          ?>
     </div>
</div>
