//$.noConflict();
function startUpload_PV(){
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function stopUpload_PV(success){
      var result = '';
      if (success == 1){
         result = '<span class="msg">Attachment uploaded successfully!<\/span><br/><br/>';
      }
      else {
         result = '<span class="emsg">Error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result;// + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn labelx" value="SUBMIT" /><\/label>';
      document.getElementById('f1_upload_form').style.visibility = 'visible';      
	  
	  /*document.getElementById('memo_from').value='';
	  document.getElementById('dept_addr').value='';
	  document.getElementById('desc').value='';
	  document.getElementById('amount').value='';
	  document.getElementById('dept_unit').value='';*/
	  
      return true;   
}

function startUpload(){
      document.getElementById('f1_upload_process').style.visibility = 'visible';
      document.getElementById('f1_upload_form').style.visibility = 'hidden';
      return true;
}

function stopUpload(success){
      var result = '';
      if (success == 1){
         result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
      }
      else {
         result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process').style.visibility = 'hidden';
      document.getElementById('f1_upload_form').innerHTML = result + '<label>File: <input name="myfile" type="file" size="30" /><\/label><label><input type="submit" name="submitBtn" class="sbtn labelx" value="SUBMIT" /><\/label>';
      document.getElementById('f1_upload_form').style.visibility = 'visible';      
	  
	  document.getElementById('memo_from').value='';
	  document.getElementById('dept_addr').value='';
	  document.getElementById('desc').value='';
	  document.getElementById('amount').value='';
	  document.getElementById('dept_unit').value='';
	  
      return true;   
}


function startUpload2(){
      document.getElementById('f1_upload_process2').style.visibility = 'visible';
      document.getElementById('f1_upload_form2').style.visibility = 'hidden';
      return true;
}

function stopUpload2(success){
      var result = '';
      if (success == 1){
         result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
      }
      else {
         result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process2').style.visibility = 'hidden';
      document.getElementById('f1_upload_form2').innerHTML = result + '<input name="myfile2" type="file" size="20" /><label><input type="submit" name="submitBtn2" class="sbtn2 labelx" value="Upload" /><\/label>';
      document.getElementById('f1_upload_form2').style.visibility = 'visible';
	  
	  document.getElementById('memo_from').value='';
	  document.getElementById('dept_addr').value='';
	  document.getElementById('desc').value='';
	  document.getElementById('amount').value='';
	  document.getElementById('dept_unit').value='';
	  
      return true;   
}

function startUpload3(){
      document.getElementById('f1_upload_process2').style.visibility = 'visible';
      document.getElementById('f1_upload_form2').style.visibility = 'hidden';
      return true;
}

function stopUpload3(success){
      var result = '';
      if (success == 1){
         result = '<span class="msg">The file was uploaded successfully!<\/span><br/><br/>';
      }
      else {
         result = '<span class="emsg">There was an error during file upload!<\/span><br/><br/>';
      }
      document.getElementById('f1_upload_process2').style.visibility = 'hidden';
      document.getElementById('f1_upload_form2').innerHTML = result + '<input name="myfile2" type="file" size="20" /><label><input type="submit" name="submitBtn2" class="sbtn2 labelx" value="Upload" /><\/label>';
      document.getElementById('f1_upload_form2').style.visibility = 'visible';
	  
      return true;   
} 