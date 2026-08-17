<div title="Document Uploads" style="padding:10px">
     <input name="efileno" type="hidden" id="efileno" value="<?=$_SESSION['eData']['fileno'];?>" />
     <fieldset>
          <legend>
               <font color="red">
                    <b>Document Upload</b>
               </font>
          </legend>
          <div class="row">
               <div class="col-sm-6 x">
                    <label class="col-form-label">
                         <b>Document Type:</b>
                    </label>
                    <div class="">
                         <select class="form-control" name="doc_type" id="doc_type">
                              <option selected="selected" value="">---Select Option---</option>
                              <option value="Passport">Passport</option>
                              <option value="Signature">Signature</option>
                              <option value="OLevel">O'Level</option>
                              <option value="NCE">NCE</option>
                              <option value="OND">OND</option>
                              <option value="HND">HND</option>
                              <option value="First Degree">First Degree</option>
                              <option value="Second Degree">Second Degree</option>
                              <option value="PhD Certificate">PhD Certificate</option>
                              <option value="NYSC">NYSC</option>
                              <option value="Citizenship">Citizenship</option>
                              <option value="NIN">NIN</option>
                              <option value="Others">Others</option>
                         </select>
                    </div>
               </div>
               <input type="hidden" name="MAX_FILE_SIZE" value="200000" />
               <input name="oldImageToDelete" id="oldImageToDelete" type="hidden" />
               <input type="hidden" name="upload_appno" id="upload_appno" value=""/>
               <div class="col-sm-6 x">
                    <label class="col-form-label">
                         <b>Select File:</b>
                    </label>
                    <div class="">
                         <input name="imageToUploads" id="imageToUploads" type="file" required />
                         <input type="submit" value="submit" id="sd" name="sd">
                    </div>
               </div>
               <div class="col-sm-12 x">
                    <br>
                    <font color="red">NOTE: Document to be uploaded should not be more than 200KB. Only JPEG file (.jpg) format is allowed.</font>
               </div>
          </div>
          <div class="row">
               <div class="col-sm-12 x" id="uploadDocument"><?php if($_SESSION['uplaodMsg']!=''){
                    echo $_SESSION['uplaodMsg'];
                    $_SESSION['uplaodMsg']='';
               } ?></div>
               <div class="col-sm-12 x" id="document">
                    <iframe id="uploadedImage" name="uploadedImage" src="" class="passport" width="300" height="200">

                    </iframe>
               </div>
          </div>
     </fieldset>
</div>
