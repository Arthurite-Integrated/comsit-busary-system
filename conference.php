<div title="Conference/Workshops/Seminars" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Conference/Workshops/Seminars</b>
               </font>
          </legend>
          
          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Type of Training:</label>
                    <div class="">
                         <select name="tra_type" id="tra_type" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Conference">Conference</option>
                              <option value="Seminar">Seminar</option>
                              <option value="Workshop">Workshop</option>
                         </select>
                         <br/>
                         <span id="load_no_paper_read">
                         </div>
                    </div>
                    <div class="col-sm-6 x">
                         <label class="col-form-label">Training Title/Theme:</label>
                         <div class="">
                              <input class="form-control" name="tra_title" type="text" id="tra_title" />
                         </div>
                    </div>
                    <div class="col-sm-6 x">
                         <label class="col-form-label">Location/Venue:</label>
                         <div class="">
                              <input class="form-control" name="tra_location" type="text" id="tra_location" />
                         </div>
                    </div>
                    <div class="col-sm-6 x">
                         <label class="col-form-label">Sponsor:</label>
                         <div class="">
                              <input class="form-control" name="tra_sponsor" type="text" id="tra_sponsor" />
                         </div>
                    </div>
                    <div class="col-sm-6 x">
                         <label class="col-form-label">Start Date:</label>
                         <div class="">
                              <input class="form-control" name="tra_start_date" type="date" id="tra_start_date" />
                         </div>
                    </div>
                    <div class="col-sm-6 x">
                         <label class="col-form-label">End Date:</label>
                         <div class="">
                              <input class="form-control" name="tra_end_date" type="date" id="tra_end_date" />
                         </div>
                    </div>
               </div>

          </fieldset>
          <br>
          <div class="row">
               <div class="col-sm-6 x">
                    <input type="button" name="btnConf" id="btnConf" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addConference', 'addNew');"/>
               </div>
          </div>
          <br>
          <div id="addConference">
               <?php
                    $cls->generateTable("SELECT id AS 'UID', training_type AS 'PROGRAMME TYPE', training_title AS 'THEME/TITLE', location AS 'LOCATION/VENUE', sponsor AS 'SPONSOR', start_date AS 'FROM', end_date AS 'TO' FROM hr_staff_training_apptb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addConference", "Delete");
               ?>
          </div>
     </div>
