<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@$_SESSION['role'];
 $staff_category=@$_SESSION['staff_category'];



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php include("required_jQuery_files.php");
include "function.php";?>
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

<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
	<?php include_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>Heading of The page</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
                <h3><i>Staff Disciplinary Records </i></h3>
			    <p>
				
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="disc_frm" id="disc_frm">
            	<table>
				<tr>
				  <th colspan="4"><div align="left">Issuance Section </div></th>
				  </tr>
				<tr>
				<th><div align="left">File Number</div></th> <td><input name="fileno" type="text" size="20" id="fileno" onblur="swapcontent('load_staff_name',this.value);"/></td>
				<th><div align="left">Reference Number</div></th>  
				<td><input name="disc_ref_no" type="text" size="30" id="disc_ref_no" /></td>
				</tr>
				<tr>
				<th>Discipline Type</th> <td><select name="disc_type" id="disc_type" >
                  <option selected="selected" value="">--</option>
                  <option value="Query">Query</option>
                  <option value="Warning">Warning</option>
                </select></td>
				<th><div align="left">
				  <div align="left">
                    <div align="left">Date Issued </div>
			      </div>
				</div></th>  <td>
				<input type="text" name="disc_date" id="disc_date"/>
				</td>
				</tr>
				<tr>
				<th><div align="left">Description</div></th>  <td><textarea name="description" cols="25" rows="2" id="description"></textarea></td>
				<th>Ref. Attachment (Issuance) </th><td><label>
				  <input type="file" name="file" />
				</label></td>
				</tr>
				<tr>
				  <th colspan="4"><div align="left">Reply Section</div></th>
				  </tr>
				<tr>
				  <th><div align="left">Reply Date</div></th>
				  <td><label>
				    <input type="text" name="reply_date" id="reply_date"/>
				  </label></td>
				  <th><div align="left">Ref. Attachment (Reply) </div></th>
				  <td><input type="file" name="file2" /></td>
				  </tr>
				<tr>
				  <th><div align="left">Reply Summary</div></th>
				 <td colspan="3" > <center>
				   <textarea name="reply" cols="60" rows="2" id="reply"></textarea>
				 </center></td>
				  </tr>
				<tr>
				  <th colspan="4"><div align="left">Final Official Remarks </div></th>
				  </tr>
				<tr>
				  <th><div align="left">Remark</div></th>
				  <td colspan="3" ><div align="center">
				    <textarea name="remark" cols="60" rows="2" id="remark"></textarea>
			      </div></td>
				  </tr>
				
				<tr>
				  <th colspan="4"><span id="load_staff_name">
				    <input name="staff_name" type="text" size="35" id="staff_name" />
				  </span></th>
				  </tr>
				<tr>
				  <th colspan="4">
				  <input type="submit" class="btn" name="sbtn" id="sbtn" value="Save"  onclick="check_entry();"/>
				  <!--<input type="button" class="btn" name="sbtn" id="sbtn" value="Save"   />-->
                  <input type="button" class="btn" name="chbtn" id="chsbtn" value="View" onclick="swapcontent('disciplinary_section','view');"  /></th>
				  </tr>
				</table>
			<!--	//////////////////////// Action Coding for Image Upload -->
				
	
			    <?php
				
				  if(isset($_REQUEST['sbtn']) and $_REQUEST['sbtn']=='Save' )
				    {
						////////////////Attachment for Issuance of Query/Warning /////////////////////////////////////
						
						$pix_fname = @$_FILES['file']['name'];
					    $pix_size = $_FILES['file']['size'];
					    $pix_ext = @explode(".",$pix_fname);  $pix_ext = $pix_ext[1];
						
						$found=false;
						$upload_flag=false;
						$fileno=strtoupper($_REQUEST['fileno']);
						$ref_no_issued=strtoupper($_REQUEST['disc_ref_no']);
						$uploadDir = "attachment_files/";  //upload directory for passport and signature
						
						if($pix_fname!='')
						 {
							 //////////passport uploading
							  if ($pix_ext != "jpg" and $pix_ext != "JPG") 
							   { 
								 echo "<script>alert('Invalid passport file type. JPG file should be uploaded. The file size must not be more than 30KB')</script>";
								 $found=true;
								 //exit;
							   } //end of check extension
							   if ($size>(1024*30)) //100KB size of image 
							   { 
								 echo "<script>alert('The passport file size must not be more than 30KB.')</script>";
								 $found=true;
							   } //end of check file size
							   
							   if($found!=true)  //all requerements met
								  {
								   $pix_upload_file_name=@$fileno."_issued".@$ref_no_issued.".jpg";  //the file with .csv
								   $pix_upload_file_name=@str_replace("/","",$pix_upload_file_name);
								   $pix_upload_file_name=@str_replace(" ","",$pix_upload_file_name);
								   $pix_uploadFile = $uploadDir.$pix_upload_file_name;
								   if (@move_uploaded_file($_FILES['file']['tmp_name'], $pix_uploadFile))
									{ // file uploaded
										//
									}//end of passport file uploaded
								  }//end of found is not true, ready for upload passport
						 } //end of picture uploading
						
						
						////////////////Attachment for Reply of Query/Warning /////////////////////////////////////
						
						$pix2_fname = @$_FILES['file2']['name'];
					    $pix2_size = $_FILES['file2']['size'];
					    $pix2_ext = @explode(".",$pix2_fname);  $pix2_ext = $pix2_ext[1];
						
						$found=false;
						$upload_flag=false;
						$fileno=strtoupper($_REQUEST['fileno']);
						$uploadDir = "attachment_files/";  //upload directory for passport and signature
						
						if($pix2_fname!='')
						 {
							 //////////passport uploading
							  if ($pix2_ext != "jpg" and $pix2_ext != "JPG") 
							   { 
								 echo "<script>alert('Invalid passport file type. JPG file should be uploaded. The file size must not be more than 30KB')</script>";
								 $found=true;
								 //exit;
							   } //end of check extension
							   if ($size>(1024*30)) //100KB size of image 
							   { 
								 echo "<script>alert('The passport file size must not be more than 30KB.')</script>";
								 $found=true;
							   } //end of check file size
							   
							   if($found!=true)  //all requerements met
								  {
								   $pix2_upload_file_name=@$fileno."_reply".@$ref_no_issued.".jpg";  //the file with .csv
								   $pix2_upload_file_name=@str_replace("/","",$pix2_upload_file_name);
								   $pix2_upload_file_name=@str_replace(" ","",$pix2_upload_file_name);
								   $pix2_uploadFile = $uploadDir.$pix2_upload_file_name;
								   if (@move_uploaded_file($_FILES['file2']['tmp_name'], $pix2_uploadFile))
									{ // file uploaded
										//
									}//end of passport file uploaded
								  }//end of found is not true, ready for upload passport
						 } //end of picture uploading
						
					//}	
						/////mmmm
						
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
	$login_id=@$_SESSION['login_id'];
	$fileno=@$_REQUEST['fileno'];
	$disc_date=@$_REQUEST['disc_date'];
	$disc_type=@$_REQUEST['disc_type'];
	$disc_ref_no=$_REQUEST['disc_ref_no'];
	$description=$_REQUEST['description'];
	$reply=$_REQUEST['reply'];
	$reply_date=$_REQUEST['reply_date'];
	$remark=$_REQUEST['remark'];
	
	    $res_c=@mysqli_query($con, "select * from hr_disciplinarytb where fileno='$fileno' and disc_ref_no='$disc_ref_no'");
		 if(@mysqli_num_rows($res_c)<=0 AND $fileno !='' AND $disc_ref_no!='')
		  {
			  @mysqli_query($con, "insert into hr_disciplinarytb set fileno='$fileno',disc_date='$disc_date',disc_type='$disc_type', disc_ref_no='$disc_ref_no',description='$description',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
		  echo "<script>alert('Record saved successfully');</script>";  
		  $sql="select * from hr_disciplinarytb where fileno='$fileno'";  
		  } 
		 else
		  {
			  if(@mysqli_num_rows($res_c)>=1)
			    @mysqli_query($con, "update hr_disciplinarytb set reply='".@mysqli_real_escape_string($con, $reply)."',remark='".@mysqli_real_escape_string($con, $remark)."',reply_date='$reply_date' where disc_ref_no='$disc_ref_no'");
			   
		      echo "<script>alert('Record updated successfully');</script>";
			  $sql="select * from hr_disciplinarytb where fileno='$fileno'";  
		  }
		  
	 
	
	 $sn=0;
	 $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
	 $tb="<table><tr><th>S/N</th><th>FILE NO</th><th>DATE ISSUED</th><th> TYPE</th><th>REF. NUMBER</th><th>ACTION</th></tr>";
	if(@mysqli_num_rows($res_v)>=1)
	 {
		 while($rs_v=@mysqli_fetch_array($res_v))
		  {
			  ++$sn;
			  $id2=@$rs_v['id'];
			  $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>{$rs_v['disc_date']}</td><td>{$rs_v['disc_type']}</td><td>{$rs_v['disc_ref_no']}</td><td><a href=\"javascript:swapcontent('disciplinary_section','delete','$id2');\">DELETE</a> | <a href=\"javascript:swapcontent('disciplinary_display','$disc_ref_no');\">EDIT</a></td></tr>";
		  }//end of while
		  $tb.="</table>";
		  echo $tb;
	 }
	else
	  echo "<b>No record to display</b>";
	 	

			///mmmm
}
						
	?>
			
			<!--	//////////////////////// Action Coding for Image Upload -->
				<div id="display"></div>
				<div id="roll"></div>
		  </form>
            </p>
		</div>                
                                
           
            </div><!-- end of content box -->

        </div> <!-- end of content tooplate_content-->
    
    </div> <!-- end of content tooplate_main-->
	
    <div class="cleaner"></div>    
</div> <!-- end of wrapper tooplate_wrapper-->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->

</body>
</html>