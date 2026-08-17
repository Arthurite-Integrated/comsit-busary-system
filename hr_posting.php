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
                <h3><i>Staff Posting </i></h3>
			    <p>
				
			<form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="frm" id="frm" onsubmit="return check_entry();">
            	<table>
				<tr>
				  <th colspan="2"> <div align="center"><font color="red">Staff Number: </font>
				    <!--<input name="fileno" type="text" id="fileno" size="40" onblur="swapcontent('load_staff_details',this.value);swapcontent('load_pix',this.value);"/> -->
				    
				    <input name="fileno" type="text" id="fileno" size="40" onblur="if (this.value !='') swapcontent('load_staff_details',this.value);"/>
				    <?php
						    echo "<script>document.getElementById('fileno').focus();</script>";
						  ?><br />
				    <span id="fullname"></span>
			      </div></th>
				  </tr>
				<tr>
				<th><div align="center">Department Posted</div></th> 
				<th><div align="center">Unit Posted</div></th>  
				</tr>
				<tr>
				<th><select name="dept" id="dept" onchange="swapcontent('load_unit',document.getElementById('dept').value)">
				  <option selected="selected" value="">---</option>
				  <?php
                          $res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name</option>";
                           }
                          echo "</select>";
						 ?>
				  </select></th> 
				<th>
                <span id="load_unit"><select name="unit" id="unit">
                                                <option selected="selected" value="">---</option>
                                                <?php
                          $res_c=@mysqli_query($con, "select * from unittb order by unit_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $unit_code=@$rs_c['unit_code'];
							  $unit_name=@$rs_c['unit_name'];
                              echo "<option value='$unit_code'>$unit_name</option>";
                           }
                          echo "</select>";
						 ?>
                                              </select></span>
                </th>  
				</tr>
				<tr>
				<th><div align="center">Date Posted</div></th>  
				<th><div align="center">Position/Post</div></th>
				</tr>
				<tr>
				  <th><input name="post_date" type="text" size="35" id="post_date" /></th>
				  <th> <div align="center">
				    <select name="post_position" id="post_position">
				      <option selected="selected" value="">---</option>
				      <?php
                          $res_c=@mysqli_query($con, "select * from hr_positiontb order by category,position");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
							  $position=@$rs_c['position'];
                              echo "<option value='$position'>$position</option>";
                           }
                          echo "</select>";
						 ?>
			        </select>
			      </div></th>
				  </tr>
				
				<tr>
				  <th colspan="2"><span id="load_staff_name">
			      Reference Document/Memo: 
			          <input type="file" name="file" />
				  </span></th>
				  </tr>
				<tr>
				  <th colspan="2">
				  <input type="submit" class="btn" name="sbtn" id="sbtn" value="Save"/>
				  <!--<input type="button" class="btn" name="sbtn" id="sbtn" value="Save"   />-->
                  <input type="button" class="btn" name="chbtn" id="chsbtn" value="View Posting" onclick="swapcontent('posting_section','view');"  /></th>
				  </tr>
				</table>
			<!--	//////////////////////// Action Coding for Image Upload -->
				
				
			    
			
			<!--	//////////////////////// Action Coding for Image Upload -->
			<div id="display">
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
						$uploadDir = "attachment_files/";  //upload ref document
						
						if($pix_fname!='')
						 {
							 //////////passport uploading
							  if ($pix_ext != "jpg" and $pix_ext != "JPG") 
							   { 
								 echo "<script>alert('Invalid file format. JPG file should be uploaded. The file size must not be more than 100KB')</script>";
								 $found=true;
								 //exit;
							   } //end of check extension
							   if ($size>(1024*100)) //100KB size of image 
							   { 
								 echo "<script>alert('The document size must not be more than 100KB.')</script>";
								 $found=true;
							   } //end of check file size
							   
							   if($found!=true)  //all requerements met
								  {
								   $pix_upload_file_name=@$fileno.@date('Ymd')."_postingrefdoc.jpg";  //the file with .csv
								   $pix_upload_file_name=@str_replace("/","",$pix_upload_file_name);
								   $pix_upload_file_name=@str_replace(" ","",$pix_upload_file_name);
								   $pix_uploadFile = $uploadDir.$pix_upload_file_name;
								   if (@move_uploaded_file($_FILES['file']['tmp_name'], $pix_uploadFile))
									{ // file uploaded
										//
									}//end of passport file uploaded
								  }//end of found is not true, ready for upload passport
						 } //end of picture uploading
											
						/////mmmm
						
						$fileno=@$_REQUEST['fileno'];
						$dept=@$_REQUEST['dept'];
						$unit=@$_REQUEST['unit'];
						$post_date=$_REQUEST['post_date'];
						$post_position=$_REQUEST['post_position'];
						
							$res_c=@mysqli_query($con, "select * from hr_postingtb where fileno='$fileno' and post_date=CURDATE()");
							 if(@mysqli_num_rows($res_c)<=0)
							  {
								  @mysqli_query($con, "insert into hr_postingtb set fileno='$fileno',post_dept='$dept',post_unit='$unit',post_date='$post_date',position='$position',ref_doc='$pix_uploadFile',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
							  echo "<script>alert('Record saved successfully');</script>";  
							  $sql="select * from hr_postingtb where fileno='$fileno' order by post_date desc";  
							  } 	  
						 
						$sn=0;
						 $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
						 $tb="<table><tr><th>S/N</th><th>FILE NO</th><th>STAFF NAME</th><th>DEPT. POSTED</th><th>UNIT POSTED</th><th>POSITION</th><th>DATE POSTED</th><th>ACTION</th></tr>";
						if(@mysqli_num_rows($res_v)>=1)
						 {
							 while($rs_v=@mysqli_fetch_array($res_v))
							  {
								  ++$sn;
								  $id2=@$rs_v['id'];
								  $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@get_dept_name($rs_v['post_dept'])."</td><td>".@get_unit_name($rs_v['post_dept'],$rs_v['post_unit'])."</td><td>{$rs_v['position']}</td><td>".@date('d/m/Y',strtotime($rs_v['post_date']))."</td><td><a href=\"javascript:swapcontent('posting_section','delete','$id2');\">DELETE</a></td></tr>";
							  }//end of while
							  $tb.="</table>";
							  echo $tb;
						 }
						else
						  echo "<b>No record to display</b>";
			///mmmm
} //end of action section
						
	?>
            </div>
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