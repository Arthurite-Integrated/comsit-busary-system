<style>
     .b{
          font-weight: bold;
     }
</style>
<center><img src="pictures/<?=$_SESSION['eData']['fileno'];?>_passport.jpg" height="150"/></center>
<div title="Biodata" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <b>
                    <font color="red">Employee Bio-Data</font>
               </b>
          </legend>
          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">File Number: </label>
                    <?=$_SESSION['eData']['fileno']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Name: </label>
                    <?=$_SESSION['eData']['title']; ?> <?=$_SESSION['eData']['surname']; ?> <?=$_SESSION['eData']['first_name']; ?><?=$_SESSION['eData']['other_name']; ?>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Email: </label>
                    <?=$_SESSION['eData']['email']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Phone Number: </label>
                    <?=$_SESSION['eData']['phone_no']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Sex: </label>
                    <?=$_SESSION['eData']['sex']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b"> Category: </label>
                    <?=$_SESSION['eData']['category']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Rank: </label>
                    <?=$cls->getRecord("rank", "hr_ranktb", "id", $_SESSION['eData']['rank'] ); ?>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Institute/Centre: </label>
                    <?=$cls->getRecord("dept_name", "departmenttb", "dept_code", $_SESSION['eData']['dept_code'] ); ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Department/Unit: </label>
                    <?=$cls->getRecord("unit_name", "unittb", "unit_code", $_SESSION['eData']['unit_code'] ); ?>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Marital Status: </label>
                    <?=$_SESSION['eData']['marital_status']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Religion: </label>
                    <?=$_SESSION['eData']['religion']; ?>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Date of Birth: </label>
                    <?=$_SESSION['eData']['date_of_birth']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Place of Birth: </label>
                    <?=$_SESSION['eData']['place_of_birth']; ?>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Level/Step: </label>
                    <?=$_SESSION['eData']['level']; ?>/<?=$_SESSION['eData']['step']; ?>
               </div>
               <div class="col-sm-6 x" id="natdiv">
                    <label class="col-sm-3 col-form-label b">Country: </label>
                    <?=$_SESSION['eData']['country']; ?>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">State: </label>
                    <?=$cls->getRecord("state_name", "statetb", "state_id", $_SESSION['eData']['state_id'] ); ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">LGA: </label>
                    <?=$cls->getRecord("lga_name", "lgatb", "lga_id", $_SESSION['eData']['lga_id'] ); ?>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Maiden Name: </label>
                    <?=$_SESSION['eData']['maiden_name']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Senatorial District: </label>
                    <?=$_SESSION['eData']['senatorial_district']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Contact Address: </label>
                    <?=$_SESSION['eData']['contact_address']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Permanent Address: </label>
                    <?=$_SESSION['eData']['permanent_address']; ?>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Bank Name: </label>
                    <?=$_SESSION['eData']['bank_name']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Account Number: </label>
                    <?=$_SESSION['eData']['acct_no']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Languages Spoken: </label>
                    <?=$_SESSION['eData']['languages_spoken']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Hobbies:</label>
                    <?=$_SESSION['eData']['hobbies']; ?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Disability: </label>
                    <?=$_SESSION['eData']['disability_reason']; ?>
               </div>
          </div>
     </fieldset>
     <br>
</div>
<hr>
<div title="Next of Kin" style="padding:10px">
     <fieldset>
          <legend>
               <b>
                    <font color="red">Next of Kin's Details</font>
               </b>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Fullname:</label>
                    <?=$_SESSION['eData']['next_name'];?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Relationship:</label>
                    <?=$_SESSION['eData']['next_relationship'];?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Email:</label>
                    <?=$_SESSION['eData']['next_email'];?>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-sm-3 col-form-label b">Phone Number:</label>
                    <?=$_SESSION['eData']['next_phone_no'];?>
               </div>
               <div class="col-sm-12 x">
                    <label class="col-sm-1 col-form-label b">Address:</label>
                    <?=$_SESSION['eData']['next_address'];?>
               </div>
          </div>
     </fieldset>
</div>

<?php if($cls->rows("SELECT * FROM hr_staff_spousetb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Spouse" style="padding:10px">
          <fieldset>
                    <legend>
                         <b>
                              <font color="red">Spouse Detail</font>
                         </b>
                    </legend>
                    <?php
                              $cls->generateTable("SELECT id AS 'UID', spouse_name AS 'NAME', spouse_occupation AS 'OCCUPATION', spouse_address AS 'ADDRESS' FROM hr_staff_spousetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
                    ?>
          </fieldset>
     </div>
<?php } ?>

<?php if($cls->rows("SELECT * FROM hr_staff_childtb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Children/Dependents Info." style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Children Information</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', name AS 'NAME', date_of_birth AS 'DATE OF BIRTH', sex AS 'GENDER' FROM hr_staff_childtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_academic_edutb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Educational History" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Educational History</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', school_name AS 'SCHOOL/INSTITUTION', school_type AS 'TYPE', qualification AS 'QUALIFICATION', degree_class AS 'GRADE/CLASS OF DEGREE', concat(from_month,', ',from_year) AS 'FROM', concat(to_month,', ',to_year) AS 'TO' FROM hr_staff_academic_edutb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_prof_qualificationtb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Academic/Professional Qualification/Certification" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Academic/Professional Qualification/Certification</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', institution AS 'AWARDING BODY/INSTITUTION', qualification AS 'QUALIFICATION', year_onbtained AS 'YEAR OBTAINED', qual_type AS 'TYPE' FROM hr_staff_prof_qualificationtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_employmenttb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Employment History" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Employment History</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', employer_name AS 'EMPLOYER', location AS 'ADDRESS', rank AS 'RANK', salary AS 'SALARY', from_year AS 'FROM', to_year AS 'TO', employment_type AS 'EMP. TYPE', duty AS 'DUTY', leaving_reason AS 'REASON FOR LEAVING' FROM hr_staff_employmenttb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_publicationtb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Publication(s)" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Publication(s)</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', title AS 'TITLE', publisher AS 'PUBLISHER', author AS 'AUTHOR', type AS 'TYPE', journal AS 'NAME OF JOURNAL', year_published AS 'YEAR', print_status AS 'PRINT STATUS', category AS 'CATEGORY', page_no AS 'PAGE/RANGE', url AS 'URL', volume AS 'VOLUME', issue AS 'ISSUE' FROM hr_staff_publicationtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_servicetb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Community Service" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Community Service</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', service_type AS 'SERVICE TYPE', from_year AS 'FROM', to_year AS 'TO', service_place AS 'PLACE OF SERVICE', service_details AS 'DETAILS/DESCRIPTION OF SERVICE' FROM hr_staff_servicetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_researchtb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Research Work" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Research Work</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', topic AS 'TOPIC', status AS 'STATUS', funding_source AS 'FUNDING SOURCE', amount_granted AS 'AMOUNT GRANTED', project_value AS 'PROJECT VALUE', start_date AS 'START DATE', end_date AS 'END DATE' FROM hr_staff_researchtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_prof_membershiptb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Professional Membership" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Professional Membership</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', name AS 'PROFESSIONAL BODY/INSTITUTION', category AS 'CATEGORY', reg_num AS 'MEMBERSHIP ID', cert_num AS 'CERTIFICATE NUMBER', year_honoured AS 'YEAR' FROM hr_staff_prof_membershiptb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_training_apptb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Conferences/Workshops/Seminars" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Conferences/Workshops/Seminars</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', training_type AS 'PROGRAMME TYPE', training_title AS 'THEME/TITLE', location AS 'LOCATION/VENUE', sponsor AS 'SPONSOR', start_date AS 'FROM', end_date AS 'TO' FROM hr_staff_training_apptb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_recognitiontb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Award/Honour/Recognition" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Award/Honour/Recognition</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', award_type AS 'PROGRAMME TYPE', award_date AS 'DATE', award_description AS 'TITLE/DESCRIPTION', prize AS 'PRIZE' FROM hr_staff_recognitiontb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
<?php if($cls->rows("SELECT * FROM hr_staff_refereetb WHERE fileno='{$_SESSION['eData']['fileno']}'", "Q") > 0){ ?>
     <hr>
     <div title="Referees" style="padding:10px">
          <fieldset>
               <legend>
                    <b>
                         <font color="red">Referees</font>
                    </b>
               </legend>
               <?php
                    $cls->generateTable("SELECT id AS 'UID', ref_name AS 'NAME', ref_occupation AS 'OCCUPATION', ref_address AS 'ADDRESS', ref_know_period AS 'YEARS', ref_email AS 'EMAIL', ref_phone_no AS 'PHONE NO.' FROM hr_staff_refereetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id");
               ?>
          </fieldset>
     </div>
<?php } ?>
