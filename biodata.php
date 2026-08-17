<div title="Biodata" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <b>
                    <font color="red">Employee Bio-Data</font>
               </b>
          </legend>
          <div class="row">
               <div class="col-sm-2 x">
                    <span id="load_pix">
                    </span>

                    <label class="col-form-label">Title:</label>
                    <div class="">
                         <select name="title" id="title" class="form-control">
                              <option selected="selected" value="<?=$_SESSION['eData']['title']; ?>"><?php if($_SESSION['eData']['title'] != '') echo $_SESSION['eData']['title']; else echo "---Select Option---"; ?></option>
                              <option value="Mr.">Mr.</option>
                              <option value="Miss">Miss</option>
                              <option value="Mrs">Mrs</option>
                              <option value="Dr.">Dr.</option>
                              <option value="Engr.">Engr.</option>
                              <option value="Prof.">Prof.</option>
                              <option value="Dr.(Mrs.)">Alh.</option>
                         </select>
                    </div>
               </div>
               <div class="col-sm-5 x">
                    <label class="col-form-label">Surname:</label>
                    <div class="">
                         <input class="form-control" name="surname" type="text" id="surname"  value="<?=$_SESSION['eData']['surname']; ?>" />
                    </div>
               </div>
               <div class="col-sm-5 x">
                    <label class="col-form-label">First Name:</label>
                    <div class="">
                         <input name="first_name" type="text" id="first_name" class="form-control" value="<?=$_SESSION['eData']['first_name']; ?>" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Othername:</label>
                    <div class="">
                         <input name="other_name" type="text" id="other_name" class="form-control" value="<?=$_SESSION['eData']['other_name']; ?>" />
                    </div>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-form-label">Email:</label>
                    <div class="">
                         <input class="form-control" name="email" type="text" id="email" value="<?=$_SESSION['eData']['email']; ?>" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Phone Number:</label>
                    <div class="">
                         <input class="form-control" name="phone_no" type="text" id="phone_no" value="<?=$_SESSION['eData']['phone_no']; ?>" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Sex:</label>
                    <div class="">
                         <select name="sex" id="sex" class="form-control">
                              <option selected="selected" value="<?=$_SESSION['eData']['sex']; ?>"><?php if($_SESSION['eData']['sex'] != '') echo $_SESSION['eData']['sex']; else echo "---Select Option---"; ?></option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label"> Category:</label>
                    <div class="">
                         <select class="form-control" name="category" id="category" onchange="sendRequest('loadRank');">
                              <option selected="selected" value="<?=$_SESSION['eData']['category']; ?>"><?php if($_SESSION['eData']['category'] != '') echo $_SESSION['eData']['category']; else echo "---Select Option---"; ?></option>
                              <option value="Academic">Academic</option>
                              <option value="Non-Academic">Non-Academic</option>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Rank:</label>
                    <div class="" id="rankDiv">
                         <select class="form-control" name="rank" id="rank">
                              <?php $cls->populate_select_withID("id", "rank", "hr_ranktb", "", $_SESSION['eData']['rank'] ); ?>
                         </select>
                    </div>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-form-label">Institute/Directorate/Centre:</label>
                    <div class="">
                         <select class="form-control" name="dept_code" id="dept_code" onchange="sendRequest('loadUnit', 'unitDiv');">
                              <?php $cls->populate_select_withID("dept_code", "dept_name", "departmenttb", "", $_SESSION['eData']['dept_code'] ); ?>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Department/Unit:</label>
                    <div class="" id="unitDiv">
                         <select class="form-control" name="unit_code" id="unit_code">
                              <?php $cls->populate_select_withID("unit_code", "unit_name", "unittb", "", $_SESSION['eData']['unit_code'] ); ?>
                         </select>
                    </div>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-form-label">Marital Status:</label>
                    <div class="">
                         <select name="marital_status" id="marital_status" class="form-control">
                              <option selected="selected" value="<?=$_SESSION['eData']['marital_status']; ?>"><?php if($_SESSION['eData']['marital_status'] != '') echo $_SESSION['eData']['marital_status']; else echo "---Select Option---"; ?></option>
                              <option value="Single">Single</option>
                              <option value="Married">Married</option>
                              <option value="Widow">Widow</option>
                              <option value="Widower">Widower</option>
                              <option value="Separated">Separated</option>
                              <option value="Divorced">Divorced</option>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Religion:</label>
                    <div class="">
                         <select name="religion" id="religion" class="form-control">
                              <option selected="selected" value="<?=$_SESSION['eData']['religion']; ?>"><?php if($_SESSION['eData']['religion'] != '') echo $_SESSION['eData']['religion']; else echo "---Select Option---"; ?></option>
                              <option value="Islam">Islam</option>
                              <option value="Christianity">Christianity</option>
                         </select>
                    </div>
               </div>

               <div class="col-sm-6 x">
                    <label class="col-form-label">Date of Birth:</label>
                    <div class="">
                         <input class="form-control"  type="date" name="date_of_birth" id="date_of_birth" value="<?=$_SESSION['eData']['date_of_birth']; ?>" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Place of Birth:</label>
                    <div class="">
                         <input class="form-control" name="place_of_birth" type="text" id="place_of_birth" value="<?=$_SESSION['eData']['place_of_birth']; ?>"  />
                    </div>
               </div>

               <div class="col-sm-3 x">
                    <label class="col-form-label">Level: </label>
                    <div class="">
                         <select class="form-control" name="level" id="level">
                              <?php $cls->populate_select_noID("level", "leveltb", "", $_SESSION['eData']['level'] ); ?>
                    </select>
               </div>
          </div>
               <div class="col-sm-3 x">
                    <label class="col-form-label">Step: </label>
                    <div class="">
                         <select class="form-control" name="step" id="step">
                              <?php $cls->populate_select_noID("step", "steptb", "", $_SESSION['eData']['step'] ); ?>
                    </select>
               </div>
          </div>
          <div class="col-sm-6 x" id="natdiv">
               <label class="col-form-label">Country</label>
               <div class="">
                    <select class="form-control" name="country" id="country" onChange="swapcontent('natdiv',document.getElementById('nationality').value)">
                         <?php $cls->populate_select_noID("country", "countrytb", " ORDER BY country ", $_SESSION['eData']['country'] ); ?>
                    </select>
               </div>
          </div>

          <div class="col-sm-6 x">
               <label class="col-form-label">State:</label>
               <div class="">
                    <select class="form-control" name="state" id="state" onChange="sendRequest('loadLGA', 'LGADIV')">
                         <?php $cls->populate_select_withID("state_id", "state_name", "statetb", "", $_SESSION['eData']['state_id'] ); ?>
                    </select>
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">LGA:</label>
          <div class="" id="LGADIV">
               <select class="form-control" name="lga" id="lga">
                    <option selected="selected" value="<?=$_SESSION['eData']['lga_id']; ?>"><?php if($_SESSION['eData']['lga_id'] != '') echo $cls->getLGA($_SESSION['eData']['lga_id']); else echo "---Select Option---"; ?></option>
               </select>
          </div>
     </div>

     <div class="col-sm-6 x">
          <label class="col-form-label">Maiden Name:</label>
          <div class="">
               <input name="maiden_name" type="text" id="maiden_name"  class="form-control" value="<?=$_SESSION['eData']['maiden_name']; ?>" />
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">Senatorial District</label>
          <div class="">
               <input class="form-control" name="senatorial_district" type="text" id="senatorial_district" value="<?=$_SESSION['eData']['senatorial_district']; ?>"  />
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">Contact Address:</label>
          <div class="">
               <textarea name="contact_address" id="contact_address" rows="3" class="form-control" ><?=$_SESSION['eData']['contact_address']; ?></textarea>
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">Permanent Address:</label>
          <div class="">
               <textarea name="permanent_address" id="permanent_address" rows="3" class="form-control" ><?=$_SESSION['eData']['permanent_address']; ?></textarea>
          </div>
     </div>

     <div class="col-sm-6 x">
          <label class="col-form-label">Bank Name:</label>
          <div class="">
               <select class="form-control" name="bank_name" id="bank_name">
                    <option selected="selected" value="<?=$_SESSION['eData']['bank_name']; ?>"><?php if($_SESSION['eData'][''] != 'bank_name') echo $_SESSION['eData']['bank_name']; else echo "---Select Option---"; ?></option>
                    <?php
                    $res_c=mysqli_query($con, "SELECT * FROM banktb order by bankname");
                    while($rs_c=mysqli_fetch_array($res_c))
                    {
                         $bankname=$rs_c['bankname'];
                         echo "<option value='$bankname'>$bankname</option>";
                    }
                    echo "</select>";
                    ?>
               </select>
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">Account Number:</label>
          <div class="">
               <input class="form-control" name="acct_no" type="text" id="acct_no" value="<?=$_SESSION['eData']['acct_no']; ?>" />
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">Languages Spoken:</label>
          <div class="">
               <input class="form-control" name="languages_spoken" type="text" id="languages_spoken" value="<?=$_SESSION['eData']['languages_spoken']; ?>" />
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">Hobbies:</label>
          <div class="">
               <input class="form-control" name="hobbies" type="text" id="hobbies" value="<?=$_SESSION['eData']['hobbies']; ?>" />
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">Disability:</label>
          <div class="">
               <select name="disability" id="disability" class="form-control">
                    <option selected="selected" value="<?=$_SESSION['eData']['disability']; ?>"><?php if($_SESSION['eData']['disability'] != '') echo $_SESSION['eData']['disability']; else echo "---Select Option---"; ?></option>
                    <option value="No">No</option>
                    <option value="Yes">Yes</option>
               </select>
          </div>
     </div>
     <div class="col-sm-6 x">
          <label class="col-form-label">Nature of Disability:</label>
          <div class="">
               <input class="form-control" name="disability_reason" type="text" id="disability_reason" value="<?=$_SESSION['eData']['disability_reason']; ?>" />
          </div>
     </div>
</div>
</fieldset>
<br>
<div class="row">
     <div class="col-sm-6 x">
          <input type="button" name="button2" id="button2" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('saveEmployee', 'employeeBioDiv');"/>
     </div>
</div>
<br>
<div id="employeeBioDiv"></div>
</div>
