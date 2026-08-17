<div title="Next of Kin" style="padding:10px">
<input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <b>
                    <font color="red">Next of Kin's Details</font>
               </b>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Fullname:</label>
                    <div class="">
                         <input class="form-control" name="next_name" type="text" id="next_name" value="<?=$_SESSION['eData']['next_name'];?>" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Relationship:</label>
                    <div class="">
                         <input class="form-control" name="next_relationship" type="text" id="next_relationship" value="<?=$_SESSION['eData']['next_relationship'];?>" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Email:</label>
                    <div class="">
                         <input class="form-control" name="next_email" type="text" id="next_email" value="<?=$_SESSION['eData']['next_email'];?>" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Phone Number:</label>
                    <div class="">
                         <input class="form-control" name="next_phone_no" type="text" id="next_phone_no" value="<?=$_SESSION['eData']['next_phone_no'];?>" />
                    </div>
               </div>
               <div class="col-sm-12 x">
                    <label class="col-form-label">Address:</label>
                    <div class="">
                         <input class="form-control" name="next_address" type="text" id="next_address" value="<?=$_SESSION['eData']['next_address'];?>" />
                    </div>
               </div>
          </div>
     </fieldset>
     <br>
<div class="row">
     <div class="col-sm-6 x">
          <input type="button" name="btnNextofkin" id="btnNextofkin" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('saveNextofkin', 'nokDiv');"/>
     </div>
</div>
<br>
<div id="nokDiv">
          <?php
                    $cls->generateTable("SELECT id AS 'UID', next_name AS 'NAME', next_relationship AS 'RELATIONSHIP', next_email AS 'EMAIL', next_phone_no AS 'PHONE NO', next_address AS 'ADDRESS' FROM stafftb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", false, "saveNextofkin");
          ?>
</div>
</div>