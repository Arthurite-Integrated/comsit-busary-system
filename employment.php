<div title="Employment History" style="padding:10px">
<input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Employment History</b>
               </font>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Employer's Name:</label>
                    <div class="">
                         <input class="form-control" name="emp_name" type="text" id="emp_name" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Location:</label>
                    <div class="">
                         <input class="form-control" name="emp_location" type="text" id="emp_location" />
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Rank:</label>
                    <div class="">
                         <input class="form-control" name="emp_rank" type="text" id="emp_rank" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Salary:</label>
                    <div class="">
                         <input class="form-control" name="emp_salary" type="number" id="emp_salary" />
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">From:</label>
                    <div class="">
                         <select class="form-control" name="emp_year_from" id="emp_year_from">
                              <option selected="selected" value="">---Select Option---</option>
                              <?php
                              for($i=date('Y'); $i >= (date('Y')-75); $i--)
                                   echo "<option value='$i'>$i</option>";
                              ?>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">To:</label>
                    <div class="">
                         <select class="form-control" name="emp_year_to" id="emp_year_to">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="To Date">To Date</option>
                              <?php
                              for($i=date('Y'); $i >= (date('Y')-75); $i--)
                                   echo "<option value='$i'>$i</option>";
                              ?>
                         </select>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Employment Type:</label>
                    <div class="">
                         <select name="emp_type" id="emp_type" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Previous">Previous</option>
                              <option value="Present">Present</option>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Nature of Employment:</label>
                    <div class="">
                         <select name="emp_status" id="emp_status" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Permanent">Permanent</option>
                              <option value="Temporary">Temporary</option>
                              <option value="Contract">Contract</option>
                              <option value="Transfer">Transfer</option>
                              <option value="Secondment">Secondment</option>
                              <option value="Pensionable">Pensionable</option>
                         </select>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Duty:</label>
                    <div class="">
                         <textarea name="emp_duty" id="emp_duty" cols="20" rows="3" class="form-control"></textarea>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Reason for Leaving the Organization:</label>
                    <div class="">
                         <textarea name="emp_leaving" id="emp_leaving" cols="20" rows="3" class="form-control"></textarea>
                    </div>
               </div>
          </div>
     </fieldset>
     <br>
<div class="row">
     <div class="col-sm-6 x">
          <input type="button" name="cmdemp" id="cmdemp" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addEmployment', 'addNew');"/>
     </div>
</div>
<br>
<div id="addEmployment">
          <?php
               $cls->generateTable("SELECT id AS 'UID', employer_name AS 'EMPLOYER', location AS 'ADDRESS', rank AS 'RANK', salary AS 'SALARY', from_year AS 'FROM', to_year AS 'TO', employment_type AS 'EMP. TYPE', duty AS 'DUTY', leaving_reason AS 'REASON FOR LEAVING', status AS 'STATUS' FROM hr_staff_employmenttb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addEmployment", "Delete");
          ?>
</div>
</div>