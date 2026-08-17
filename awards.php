<div title="Award/Honour/Recognition" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Award/Honour/Recognition</b>
               </font>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Type of Honour/Award:</label>
                    <div class="">
                         <input class="form-control" name="honour_type" type="text" id="honour_type" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Date:</label>
                    <div class="">
                         <input class="form-control" name="honour_date" type="date" id="honour_date"  cols="25"/>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Description:</label>
                    <div class="">
                         <textarea name="honour_desc" id="honour_desc" cols="25" rows="3" class="form-control"></textarea>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Prize (if any):</label>
                    <div class="">
                         <textarea name="honour_prize" id="honour_prize" cols="25" rows="3" class="form-control"></textarea>
                    </div>
               </div>
          </div>

     </fieldset>
     <br>
     <div class="row">
          <div class="col-sm-6 x">
               <input type="button" name="btnHon" id="btnHon" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addHonour', 'addNew');"/>
          </div>
     </div>
     <br>
     <div id="addHonour">
          <?php
                    $cls->generateTable("SELECT id AS 'UID', award_type AS 'PROGRAMME TYPE', award_date AS 'DATE', award_description AS 'TITLE/DESCRIPTION', prize AS 'PRIZE' FROM hr_staff_recognitiontb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addHonour", "Delete");
          ?>
     </div>
</div>
