<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Edu Tech</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
	var str;
	

 if(cv=='login') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='forget_password') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
		});
  }//end of putme_login
  
 if(cv=='main_login') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,username:v,password:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='pass_recovery_update') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,uname:v,email:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='another') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		
		});
  }//end of putme_login
  
} //end of swapcontent
 </script>
</head>
<body class="subpage">
<?php include("required_jQuery_files.php");
		include "connect.php";
?>
<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
	<?php include_once("sidebar.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>Uploading of Questions</h2>
                <p>Use the form below to upload question. Note that the file to be specified must be a .csv (Comma Seperated Value) file.</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        <!--	<div class="content_box">-->
                                
              <h3>File Parameter</h3>
			  <p>The file must contain the following columns in the order listed: </p>
			  <ul class="tooplate_list">
			 	 	<li>Question</li>
					<li>Option <strong>A</strong></li>
					<li>Option <strong>B</strong></li>
					<li>Option <strong>C</strong></li>
					<li>Option <strong>D</strong></li>
					<li>Correct Answer</li>
					<li>Point</li>
			  </ul>
              <div class="cleaner"></div>
			   <div id="contact_form">
			    <form method="post" action="<?php echo @$_SERVER['HTTP_SELF']; ?>" enctype="multipart/form-data" name="cform" id="cform" onSubmit="return myVal()">
			   		<label for="author">Session:</label><select name="session"  id="session" class="required input_field">
          							<option value="" selected>--</option>
									<option value="2015/2016" selected>2015/2016</option>
									</select>  
						<div class="cleaner h10"></div>
					<label for="email">CSV File:</label> <input type="file" name="file" id="file" class="input_field" />
						<div class="cleaner h10"></div>
					<input type="hidden" name="mode" value="process" />
                                <input type="submit" class="submit_btn float_l" name="sbtn" id="sbtn" value="Upload Question" />
                                <input type="button" class="submit_btn float_l" name="vbtn" id="vbtn" value="View" onClick="swapcontent('result','view_courses',document.getElementById('factcode').value,document.getElementById('deptcode').value,document.getElementById('progcode').value,document.getElementById('level').value);" />
                                <input type="button" class="submit_btn float_l" name="dbtn" id="dbtn" value="Batch Delete" onClick="if( confirm('Are you sure you want to delete all  courses in the selected Department , Programme and Contact?')==true)swapcontent('result','batchdelete_courses',document.getElementById('factcode').value,document.getElementById('deptcode').value,document.getElementById('progcode').value,document.getElementById('level').value);" />	
						
			   </form>
			   </div><!-- end of form -->
					
                        		
                               <div id='uploadloader'></div>
                               
                                <script language="javascript">
                function myVal()
                    {
						//alert('yes');
                var obj = document.cform;
				
                if (obj.session.value == "") { alert("Session is compulsary"); obj.session.focus(); return false; }
			    //if (obj.deptcode.value == "") { alert("Department is compulsary"); obj.deptcode.focus(); return false; }
				///if (obj.progcode.value == "") { alert("Programme is compulsary"); obj.progcode.focus(); return false; }
				//if (obj.level.value == "") { alert("Level is compulsary"); obj.level.focus(); return false; }
                if (obj.file.value == "") { alert("Specify the file to upload (the file must be a .csv file)"); obj.file.focus(); return false; }
                var ab = obj.file.value;
                var ab2 = "";
                var strArray = ab.split(" ");
                for (var i = 0; i < strArray.length; i++) {
                //ab2 = ab.replace(" ", "");
                ab2 = ab2 + strArray[i];
                }
                var postConf = window.confirm("Are you sure you want to upload this file? \n\n Click 'Ok' to proceed if yes, otherwise click 'Cancel' to discontinue");
                if (postConf == false) { return false; } else { return true; }
                    }
                </script>
                 <br /><br /><br />
				 <div id="result">
                <?php
                //******* Upload Courses from Excel file *********************
				 @ini_set('max_execution_time', 60000000000);
 @ini_set("memory_limit", "51200M");
				$mode = @$_REQUEST["mode"];
				//$factcode=@explode("***",$_REQUEST['factcode']);
				//$deptcode=@explode("***",$_REQUEST['deptcode']);$progcode=@explode("***",$_REQUEST['progcode']);
				$session=@$_REQUEST['session'];
                if ($mode == "process")
                {  ///start processing   
				
				 $entryby=$login_id;
				 $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');

				//echo $deptcode[1].'<br>'.$progcode[1].'<br>'.$contact;
				
                $fname = @$_FILES['file']['name'];
                $ext = @explode(".",$fname);  $ext = $ext[1];
                $pgRef = @$_SERVER['HTTP_REFERER'];
                if ($ext != "txt" and $ext != "TXT") { ?> <script language="javascript">window.alert("Sorry, invalid file format! You are to specify '.csv' file only"); window.location = "<?php echo "$pgRef"; ?>"; </script> <?php exit; }
                
				echo"<script>$(function() {	$('#uploadloader').html('<img src=\"images/loader2.gif\" width=\"300\">').show();});</script>";
                $ab2 = @str_replace(" ","",$fname);
                
                $uploadDir = "upload_files/";
                $myFileDate = @date("Ymd") . "_" . @str_replace(":","",@date("H:i:s"));
                $pubFile = "$myFileDate" . "_" . "$login_id" . "_" . $fname;
                $pubFile = @str_replace("/","",$pubFile);
                $uploadFile = $uploadDir . $pubFile;
                if ($fname != "") 
                { // file upload			
                    if (@move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile))
                    { // file uploaded
                
                 $courses_table = "question_banktb";
                
                $input_file = $uploadFile;
                $sn = 0;
                $file_array = @file("$uploadFile");
                $entryConf = "<table border='1' cellpadding='3' cellspacing='0'><tr valign='top'><td>S/No</td><td>Course</td><td>Check</td><td>Status</td></tr>";
				
				$question_list="";
                while (list($line_num, $line) = each($file_array)) 
                    { // each line
                                
                $sn++;
                $myLine = @explode("\t",$line);
              //  $ccode = @strtoupper(@str_replace(" ","",@trim($myLine[0])));
				
				if(!get_magic_quotes_gpc())
					{
               			 $question = @addslashes(trim($myLine[0]));
						 $opt_a = @addslashes(trim($myLine[1]));
						 $opt_b = @addslashes(trim($myLine[2]));
						 $opt_c = @addslashes(trim($myLine[3]));
						 $opt_d = @addslashes(trim($myLine[4]));
						 $answer = @addslashes(trim($myLine[5]));
						 $point = @trim($myLine[6]);
						 
					}
				else
					{
						$question = @trim($myLine[0]);
						 $opt_a = @trim($myLine[1]);
						 $opt_b = @trim($myLine[2]);
						 $opt_c = @trim($myLine[3]);
						 $opt_d = @trim($myLine[4]);
						 $answer = @trim($myLine[5]);
						 $point = @trim($myLine[6]);
					}
				
				
                
                $added_date=@date('Y-m-d'); $added_time=@date('h:s:i a');
				$question_id = substr(md5(microtime()),rand(0,26),5);
                $courseInfo = @mysqli_query($con, "SELECT * FROM $courses_table WHERE question_id='$question_id'");
				if (@mysqli_num_rows($courseInfo) >= 1)
                    { // record found
					  $rs_info=@mysqli_fetch_array($courseInfo);
					  $staff_id="<strong>[$ccode]</strong> ".$rs_info['question_detail'];
					  $chkRes = "<font color='red'>Error</font>";
					  $probLine="<font color='red'>Record already exist in the Database</font>";
					  
				      
					  
					} //end of update allocation of courses
				 else
				  { //not already allocated
					  
					  
					  
					 if(@mysqli_query($con, "insert into $courses_table set question_id='$question_id',serial_no='$sn',question_detail='$question',correct_answer='$answer',point='$point',session='$session',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$entryby'"))
					 {
					  $question_list .="$sn. $question<br>";
					  $letter="Z";$i=0;
					  $opt="";
					  for($i=1;$i<=4;$i++)
					  	{
							$letter++;
							$letter=substr($letter,-1);
							@mysqli_query($con, "insert into question_optiontb set question_id='$question_id',option_letter='$letter',option_detail='".@trim($myLine[$i])."'") or die( mysqli_query($con, ));
							$opt .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$letter.&nbsp;&nbsp;";
							if(@trim($myLine[$i])==@trim($myLine[5]))
								$opt .="<font color='red'><strong>".@trim($myLine[$i])."</strong></font><br>";
							else
								$opt .=@trim($myLine[$i])."<br>";
								
						}
						$question_list .=$opt;
						
					}// end of insert into question Bank
					 /* $chkRes = "<font color='green'>Right</font>";
                      $probLine = "<font color='green'>Successful</font>";
					  $staff_id="<strong>[$ccode]</strong> ".$ctitle.' ['.$cunit.'] ['.$cstatus.']'.' ['.$csemester.'] ['.$cfact_course.']';
					  */
				  }//end of not already inserted
				  
                
                $entryConf .= "<tr><td>$sn</td><td>$staff_id</td><td>$chkRes</td><td>$probLine. $commentRes</td></tr>";
                
                        $chkRes = "";
                        $probLine = "";
                        $commentRes = "";
                
                    } // end of each line i.e for end of while stmt
               // $entryConf .= "</table>";
              //  $entryConf = "<strong><a href='javascript:TINY.box.hide()'><img src='images/close2.png'></a><br>Confirmation:</strong> <br /><b><font color='red'>Output File </font></b><br />$factcode[1]<br />$deptcode[1]<br />$progcode[1]<br>$level Level<br>" . $entryConf;
               // echo "<strong>Confirmation:</strong> <br />$entryConf";
                
            		
			///	@mysqli_query($con, "insert into portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Course Upload','Courses Upload for $factcode[1], $deptcode[1],$progcode[1],$level by $login_id','$log_date2','$log_date','$log_time','$login_id')");
				
				$output_file = @str_replace(".txt","",$uploadFile) . "_output.html";
				$outFile = @fopen("$output_file",w);
				@fwrite($outFile,$entryConf,70640000000);  //protect write error, the no is for length of record
				
				@mysqli_query($con, "INSERT INTO files_uploadtb (fileno,action_date, action_time, input_file, output_file, session, purpose) VALUES ('$login_id',CURDATE(),CURTIME(), '$input_file', '$output_file', '$postResultSession', 'Question Upload')")or die( mysqli_error($con));
                
                
                  } // end of file uploaded
                    else { echo "Error uploading file"; }
                } // end of file is not null
				
			/*	echo"<script>$(function() {	$('#uploadloader').html('').show();});</script>";
				echo "<script>
				var content2 = \"$entryConf\";
				//alert(\"$entryConf\");
	TINY.box.show(content2,0,0,0,0);
	//TINY.box.show({html:content2,animate:true,close:true,boxid:'success',top:5})
	//TINY.box.show({html:content2,boxid:'frameless',width:750,height:450,fixed:true,maskopacity:40,closejs:function(){closeJS()}})
	
				
				
				</script>";
			*/
				//echo $question_list; 
				
				$rs=@mysqli_query($con, "select * from question_banktb where session='$session' order by serial_no");
				$n=0;$question_list="";
				while($rst=@mysqli_fetch_array($rs))
				{
					$n++;
					$q_id=@$rst['question_id'];
					$q_d=@stripslashes($rst['question_detail']);
					$q_d=@str_replace("…",".",$q_d);
					$q_d=@str_replace('"',"",$q_d);
					$q_ans=@$rst['correct_answer'];
					$question_list .="$n. $q_d<br>";
					  $letter="Z";$i=0;
					  $opt="";
					  $rs_opt=@mysqli_query($con, "select * from question_optiontb where question_id='$q_id'");
					 while($rstopt=@mysqli_fetch_array($rs_opt))
					  	{
							$letter++;
							$letter=substr($letter,-1);
							$opt_d=@stripslashes($rstopt['option_detail']);
							
							$opt .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$letter.&nbsp;&nbsp;";
							if($opt_d==$q_ans)
								$opt .="<font color='red'><strong>".$opt_d."</strong></font><br>";
							else
								$opt .="$opt_d<br>";
								
						}
						$question_list .=$opt;
				}
				
				echo "<br>".$question_list; 
             } //end of process section
			 ?>
			</div><!-- end result div -->       
                
                
                                
           
           <!-- </div><!-- end of content box -->

        </div> <!-- end of content tooplate_content-->
    
    </div> <!-- end of content tooplate_main-->
	
    <div class="cleaner"></div>    
</div> <!-- end of wrapper tooplate_wrapper-->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->

</body>
</html>