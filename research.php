<div title="Research" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Research Work</b>
               </font>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Research Topic:</label>
                    <div class="">
                         <input class="form-control" name="res_topic" type="text" id="res_topic" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Research Status:</label>
                    <div class="">
                         <select name="res_status" id="res_status" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="In Progress">In Progress</option>
                              <option value="Completed">Completed</option>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Funding Source:</label>
                    <div class="">
                         <input class="form-control" name="res_funding" type="text" id="res_funding" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Amount Granted:</label>
                    <div class="">
                         <input class="form-control" name="res_amount" type="text" id="res_amount" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Value of Project:</label>
                    <div class="">
                         <input class="form-control" name="res_value" type="text" id="res_value" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Start Date:</label>
                    <div class="">
                         <input class="form-control" name="res_start_date" type="date" id="res_start_date" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">End Date:</label>
                    <div class="">
                         <input class="form-control" name="res_end_date" type="date" id="res_end_date" />
                    </div>
               </div>
          </div>
     </fieldset>
     <br>
     <div class="row">
          <div class="col-sm-6 x">
               <input type="button" name="btnRes" id="btnRes" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addResearch', 'addNew');"/>
          </div>
     </div>
     <br>
     <div id="addResearch">
          <?php
                    $cls->generateTable("SELECT id AS 'UID', topic AS 'TOPIC', status AS 'STATUS', funding_source AS 'FUNDING SOURCE', amount_granted AS 'AMOUNT GRANTED', project_value AS 'PROJECT VALUE', start_date AS 'START DATE', end_date AS 'END DATE' FROM hr_staff_researchtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addResearch", "Delete");
          ?>
     </div>
</div>
