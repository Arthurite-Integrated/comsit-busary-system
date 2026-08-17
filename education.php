<div title="Educational History" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Educational History</b>
               </font>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Name of Institution:</label>
                    <div class="">
                         <input class="form-control" name="edu_name" type="text" id="edu_name" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Institution Type:</label>
                    <div class="">
                         <select name="edu_type" id="edu_type" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Primary">Primary</option>
                              <option value="Secondary">Secondary</option>
                              <option value="Advanced Level">Advanced Level</option>
                              <option value="Diploma">Diploma</option>
                              <option value="College">College</option>
                              <option value="Polytechnic">Polytechnic</option>
                              <option value="University">University</option>
                         </select>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Qualification Obtained:</label>
                    <div class="">
                         <input class="form-control" name="edu_qual" type="text" id="edu_qual" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Grade/Class of Degree:</label>
                    <div class="">
                         <input class="form-control" name="edu_grade" type="text" id="edu_grade" />
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">From:</label>
                    <div class="">
                         <select class="form-control" name="edu_month_from" id="edu_month_from">
                              <option selected="selected" value="">---Select Option---</option>
                              <?php
                              $res_m=mysqli_query($con, "SELECT * FROM monthtb order by month_code");
                              while($rs_m=mysqli_fetch_array($res_m))
                              {
                                   echo "<option value='{$rs_m['month_name']}'>{$rs_m['month_name']}</option>";
                              }
                              ?>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">&nbsp;</label>
                    <div class="">
                         <select class="form-control" name="edu_year_from" id="edu_year_from">
                              <option selected="selected" value="">---Select Option---</option>
                              <?php
                              for($i=date('Y'); $i >= (date('Y')-100); $i--)
                              echo "<option value='$i'>$i</option>";
                              ?>
                         </select>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">To:</label>
                    <div class="">
                         <select class="form-control" name="edu_month_to" id="edu_month_to">
                              <option selected="selected" value="">---Select Option---</option>
                              <?php
                              $res_m=mysqli_query($con, "SELECT * FROM monthtb order by month_code");
                              while($rs_m=mysqli_fetch_array($res_m))
                              {
                                   echo "<option value='{$rs_m['month_name']}'>{$rs_m['month_name']}</option>";
                              }
                              ?>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">&nbsp;</label>
                    <div class="">
                         <select class="form-control" name="edu_year_to" id="edu_year_to">
                              <option selected="selected" value="">---Select Option---</option>
                              <?php
                              for($i=date('Y'); $i >= (date('Y')-100); $i--)
                              echo "<option value='$i'>$i</option>";
                              ?>
                         </select>
                    </div>
               </div>
          </div>
     </fieldset>
     <br>
     <div class="row">
          <div class="col-sm-6 x">
               <input type="button" name="btnEdu" id="btnEdu" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addEducation', 'addNew');"/>
          </div>
     </div>
     <br>
     <div id="addEducation">
          <?php
          $cls->generateTable("SELECT id AS 'UID', school_name AS 'SCHOOL/INSTITUTION', school_type AS 'TYPE', qualification AS 'QUALIFICATION', degree_class AS 'GRADE/CLASS OF DEGREE', concat(from_month,', ',from_year) AS 'FROM', concat(to_month,', ',to_year) AS 'TO' FROM hr_staff_academic_edutb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addEducation", "Delete");
          ?>
     </div>
</div>
