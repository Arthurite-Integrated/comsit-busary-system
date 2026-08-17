<div title="Referees" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Referees</b>
               </font>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Name:</label>
                    <div class="">
                         <input class="form-control" name="ref_name" type="text" id="ref_name" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Occupation:</label>
                    <div class="">
                         <input class="form-control" name="ref_occupation" type="text" id="ref_occupation" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Address:</label>
                    <div class="">
                         <textarea name="ref_address" id="ref_address" cols="25" rows="3" class="form-control"></textarea>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">How many years have you known the Referee? </label>
                    <div class="">
                         <input class="form-control" name="ref_year" type="text" id="ref_year" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Email:</label>
                    <div class="">
                         <input class="form-control" name="ref_email" type="text" id="ref_email" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Phone No</label>
                    <div class="">
                         <input class="form-control" name="ref_phone_no" type="text" id="ref_phone_no" />
                    </div>
               </div>
          </div>

     </fieldset>
     <br>
     <div class="row">
          <div class="col-sm-6 x">
               <input type="button" name="btnRef" id="btnRef" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addReferee', 'addNew');"/>
          </div>
     </div>
     <br>
     <div id="addReferee">
          <?php
                    $cls->generateTable("SELECT id AS 'UID', ref_name AS 'NAME', ref_occupation AS 'OCCUPATION', ref_address AS 'ADDRESS', ref_know_period AS 'YEARS', ref_email AS 'EMAIL', ref_phone_no AS 'PHONE NO.' FROM hr_staff_refereetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addReferee", "Delete");
          ?>
     </div>
</div>
