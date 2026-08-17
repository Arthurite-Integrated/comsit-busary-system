<div title="Professional Membership" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />

     <fieldset>
          <legend>
               <font color="red">
                    <b>Professional Membership</b>
               </font>
          </legend>


          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Organization/Body:</label>
                    <div class="">
                         <input class="form-control" name="prof_mem_name" type="text" id="prof_mem_name" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Membership Category:</label>
                    <div class="">
                         <input class="form-control" name="prof_mem_category" type="text" id="prof_mem_category" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Reg. Number/Membership ID:</label>
                    <div class="">
                         <input class="form-control" name="prof_mem_regno" type="text" id="prof_mem_regno" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Certificate No.:</label>
                    <div class="">
                         <input class="form-control" name="prof_mem_certno" type="text" id="prof_mem_certno" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Year Honoured:</label>
                    <div class="">
                         <select class="form-control" name="prof_mem_year" id="prof_mem_year">
                              <option selected="selected" value="">---Select Option---</option>
                              <?php
                              for($i=date('Y'); $i>=date('Y')-100; $i--)
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
               <input type="button" name="btnRes" id="btnRes" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addMembership', 'addNew');"/>
          </div>
     </div>
     <br>
     <div id="addMembership">
          <?php
                    $cls->generateTable("SELECT id AS 'UID', name AS 'PROFESSIONAL BODY/INSTITUTION', category AS 'CATEGORY', reg_num AS 'MEMBERSHIP ID', cert_num AS 'CERTIFICATE NUMBER', year_honoured AS 'YEAR' FROM hr_staff_prof_membershiptb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addMembership", "Delete");
          ?>
     </div>
</div>
