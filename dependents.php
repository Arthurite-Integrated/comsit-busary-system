<div title="Children/Dependents Info." style="padding:10px">
<input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <b>
                    <font color="red">Children Information</font>
               </b>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Child's Fullname:</label>
                    <div class="">
                         <input class="form-control" name="child_name" type="text" id="child_name" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Date of Birth:</label>
                    <div class="">
                         <input class="form-control" name="child_dob" type="date" id="child_dob" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Sex:</label>
                    <div class="">
                         <select class="form-control" name="child_sex" id="child_sex" class="form-control">
                              <option selected="selected">---Select Option---</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                         </select>
                    </div>
               </div>
          </div>
     </fieldset>
     <br>
<div class="row">
     <div class="col-sm-6 x">
          <input type="button" name="btnChild" id="btnChild" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('saveDependent', 'addNew');"/>
     </div>
</div>
<br>
<div id="saveDependent">
          <?php
                    $cls->generateTable("SELECT id AS 'UID', name AS 'NAME', date_of_birth AS 'DATE OF BIRTH', sex AS 'GENDER', status AS 'STATUS' FROM hr_staff_childtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "saveDependent", "Trash,Restore");
          ?>
</div>
</div>