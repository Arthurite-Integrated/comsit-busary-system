<div title="Academic/Professional Qualification" style="padding:10px">
<input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
     <legend>
          <font color="red">
               <b>Academic/Professional Qualification/Certification</b>
          </font>
     </legend>


     <div class="row">
     <div class="col-sm-6 x">
                    <label class="col-form-label">Awarding Institution/Body:</label>
                    <div class="">
                    <input class="form-control" name="prof_qual_inst" type="text" id="prof_qual_inst" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Qualification/Certificate Obtained:</label>
                    <div class="">
                    <input class="form-control" name="prof_qual_name" type="text" id="prof_qual_name" />
                    </div>
               </div>
          <div class="col-sm-6 x">
               <label class="col-form-label">Year Obtained</label>
               <div class="">
                    <select class="form-control" name="prof_qual_year" id="prof_qual_year">
                         <option selected="selected" value="">---Select Option---</option>
                         <?php
                         for($i=date('Y'); $i >= (date('Y')-100); $i--)
                              echo "<option value='$i'>$i</option>";
                         ?>
                    </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Type</label>
                    <div class="">
                         <select class="form-control" name="prof_qual_type" id="prof_qual_type">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Academic">Academic</option>
                              <option value="Professional">Professional</option>
                         </select>
                         </div>
               </div>
                         </div>
          </fieldset>
          <br>
          <div class="row">
          <div class="col-sm-6 x">
                    <input type="button" name="btnQual" id="btnQual" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addQualification', 'addNew');"/>
          </div>
          </div>
          <br>
          <div id="addQualification">
                    <?php
                          $cls->generateTable("SELECT id AS 'UID', institution AS 'AWARDING BODY/INSTITUTION', qualification AS 'QUALIFICATION', year_onbtained AS 'YEAR OBTAINED', qual_type AS 'TYPE' FROM hr_staff_prof_qualificationtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addQualification", "Delete");
                    ?>
          </div>
</div>
