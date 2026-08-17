<div title="Spouse" style="padding:10px">
          <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <b>
                    <font color="red">Spouse Details</font>
               </b>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Fullname:</label>
                    <div class="">
                         <input class="form-control" name="spouse_name" type="text" id="spouse_name" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Occupation:</label>
                    <div class="">
                         <input class="form-control" name="spouse_occupation" type="text" id="spouse_occupation" />
                    </div>
               </div>
          </div>
          <div class="col-sm-12 x">
               <label class="col-form-label">Address:</label>
               <div class="">
                    <input class="form-control" name="spouse_address" type="text" id="spouse_address" />
               </div>
          </div>
     </fieldset>
     <br>
<div class="row">
     <div class="col-sm-6 x">
          <input type="button" name="btnSpouse" id="btnSpouse" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('saveSpouse', 'addNew');"/>
     </div>
</div>
<br>
<div id="saveSpouse">
          <?php
                    $cls->generateTable("SELECT id AS 'UID', spouse_name AS 'NAME', spouse_occupation AS 'OCCUPATION', spouse_address AS 'ADDRESS', status AS 'STATUS' FROM hr_staff_spousetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "saveSpouse", "Trash,Restore");
          ?>
</div>
</div>