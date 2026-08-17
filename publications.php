<div title="Publication(s)" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Publication</b>
               </font>
          </legend>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Title of Publication:</label>
                    <div class="">
                         <input class="form-control" name="pub_title" type="text" id="pub_title" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Publisher:</label>
                    <div class="">
                         <input class="form-control" name="pub_publisher" type="text" id="pub_publisher" />
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Author(s):</label>
                    <div class="">
                         <input class="form-control" name="pub_author" type="text" id="pub_author" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Publication Type:</label>
                    <div class="">
                         <select name="pub_type" id="pub_type" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Chapter in a Book">Chapter in a Book</option>
                              <option value="Book">Book</option>
                              <option value="Journal">Journal</option>
                              <option value="Conference">Conference</option>
                              <option value="Edited Conference Proceedings">Edited Conference Proceedings</option>
                              <option value="Monograph">Monograph</option>
                              <option value="Technical Report">Technical Report</option>
                              <option value="Commissioned Work">Commissioned Work</option>
                         </select>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Name of Journal:</label>
                    <div class="">
                         <input class="form-control" name="pub_journal" type="text" id="pub_journal" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Publication Year:</label>
                    <div class="">
                         <select class="form-control" name="pub_year" id="pub_year">
                              <option selected="selected" value="">---Select Option---</option>
                              <!--<option value="0">To Date</option>-->
                              <?php
                              for($i=date('Y');$i>=date('Y')-100; $i--)
                              {
                                   echo "<option value='$i'>$i</option>";
                              }
                              ?>
                         </select>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Publication Status:</label>
                    <div class="">
                         <select name="pub_status" id="pub_status" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="In-Print">In-Print</option>
                              <option value="Accepted">Accepted</option>
                              <option value="Published">Published</option>
                         </select>
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Category:</label>
                    <div class="">
                         <select name="pub_category" id="pub_category" class="form-control">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Home Based">Home Based</option>
                              <option value="National">National</option>
                              <option value="International">International</option>
                         </select>
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Page Number/Range:</label>
                    <div class="">
                         <input class="form-control" name="pub_page_no" type="text" id="pub_page_no" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">URL:</label>
                    <div class="">
                         <input class="form-control" name="pub_url" type="url" id="pub_url" />
                    </div>
               </div>
          </div>

          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">Volume:</label>
                    <div class="">
                         <input class="form-control" name="pub_volume" type="text" id="pub_volume" />
                    </div>
               </div>
               <div class="col-sm-6 x">
                    <label class="col-form-label">Issue:</label>
                    <div class="">
                         <input class="form-control" name="pub_issue" type="text" id="pub_issue" />
                    </div>
               </div>
          </div>
     </fieldset>
     <br>
     <div class="row">
          <div class="col-sm-6 x">
               <input type="button" name="btnPub" id="btnPub" value=" SUBMIT " class="btn btn-outline-primary btn-fw" onClick="sendRequest('addPublication', 'addNew');"/>
          </div>
     </div>
     <br>
     <div id="addPublication">
          <?php
          $cls->generateTable("SELECT id AS 'UID', title AS 'TITLE', publisher AS 'PUBLISHER', author AS 'AUTHOR', type AS 'TYPE', journal AS 'NAME OF JOURNAL', year_published AS 'YEAR', print_status AS 'PRINT STATUS', category AS 'CATEGORY', page_no AS 'PAGE/RANGE', url AS 'URL', volume AS 'VOLUME', issue AS 'ISSUE', status AS 'STATUS' FROM hr_staff_publicationtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addPublication", "Trash,Restore");
          ?>
     </div>
</div>
