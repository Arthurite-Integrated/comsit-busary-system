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
        <h3><i>Staff Clinic Form</i></h3>
		  <p>
           <center>
                  <form name="frm" id="frm">
                  <table width="90%" border="0">
                    <tr>
                      <th width="17%">File Number</th>
                      <td width="27%"><?php echo $login_id;?>&nbsp;</td><input type="hidden" name="fileno" id="fileno" value="<?php echo $login_id;?>" />
                      <th width="15%">Fullname</th>
                      <td width="41%"><?php echo strtoupper($rs_w['surname'])." ".ucfirst(strtolower($rs_w['first_name']))." ".ucfirst(strtolower($rs_w['other_name']));?>&nbsp;</td>
                    </tr>
                    <tr>
                      <th>Department</th>
                      <td><?php echo get_dept_name($rs_w['dept_code']);?>&nbsp;</td>
                      <th>Designation</th>
                      <td><?php echo $rs_w['rank'];?>&nbsp;</td>
                    </tr>
                    <tr>
                      <th colspan="2">Date of Assumption of duty</th>
                      <!--class="easyui-datebox"-->
                      <td colspan="2"><div align="left"><?php echo @date('d/m/Y',strtotime($rs_w['date_of_assumption']));?></div></td>
                    </tr>
                    <tr>
                      <th colspan="4">Select your dependant eligible for treatment at the clinic (i.e wife/husband and maximum of five children)</th>
                    </tr>
                    <tr>
                      <th colspan="4">
                      
                      <center><table width="70%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2"><div align="center">S/N</div></td>
                          <td width="54%">FULLNAME</td>
                          <td width="20%">SEX</td>
                          <td width="21%">RELATION</td>
                        </tr>
                        <?php 
							  $sn=0;
							  
							  if($rs_w['spouse_name']!='') {
						?>
                        <tr>
                          <td width="3%"><?php echo ++$sn;?></td>
                          <td width="2%"><input type="checkbox" name="code[]" id="code<?php echo $sn;?>" value="spouse"/>&nbsp;</td>
                          <td><?php echo $rs_w['spouse_name'];?>&nbsp;</td>
                          <td><?php if($rs_w['sex']=='Male') echo "Female"; else echo "Male";?>&nbsp;</td>
                          <td><?php if($rs_w['sex']=='Male') echo "Wife"; else echo "Husband";?>&nbsp;</td>
                        </tr>
                        
                        <?php 
							  } //end of if spouse name is not null i.e he/she has spouse
							  
						$res_c=@mysqli_query($con, "select * from hr_staff_childtb where fileno='$login_id' order by date_of_birth");
							  
							  while($rs_c=@mysqli_fetch_array($res_c))
							   {
						?>
                        <tr>
                          <td width="3%"><?php echo ++$sn;?>&nbsp;</td>
                          <td width="2%"><input type="checkbox" name="code[]" id="code<?php echo $sn;?>" value="<?php echo $rs_c[id];?>"/>&nbsp;</td>
                          <td><?php echo $rs_c['name'];?>&nbsp;</td>
                          <td><?php echo $rs_c['sex'];?>&nbsp;</td>
                          <td><?php echo "Child";?>&nbsp;</td>
                        </tr>
                          <?php } //end of while ?>
                      </table></center>
                      
                      </th>
                    </tr>
                    <tr><input type="hidden" value="<?php echo $r_val_code;?>" name="r_val" id="r_val"/>
                      <td colspan="4"><div align="center">
                        <input type="button" name="cmdsave" id="cmdsave" value="Save Selected Item(s)" class="btn" onclick="swapcontent('clinic_section','save');"/>
                      </div></td>
                    </tr>
                  </table>
                 
        <div id="display"> 
           <?php 
		   $sql="select * from hr_staff_clinictb where fileno='$login_id' order by id";
		   $sn=0;
	 $res_v=@mysqli_query($con, $sql);
	 $g_total=0;
	 $tb="<table><tr><th>S/N</th><th>FULLNAME</th><th>SEX</th><th>DATE OF BIRTH</th><th>RELATION</th><th>ACTION</th></tr>";
	if(@mysqli_num_rows($res_v)>=1)
	 {
		 while($rs_v=@mysqli_fetch_array($res_v))
		  {
			  ++$sn;
			  $r_id=$rs_v['id'];
			  $tb.="<tr><td>$sn</td><td>{$rs_v['dependent_name']}</td><td>{$rs_v['dependent_sex']}</td><td>{$rs_v['dependent_dob']}</td><td>{$rs_v['dependent_relationship']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('clinic_section','delete','$r_id');\">DELETE</a></td></tr>";
		  }//end of while
		  
		  $tb.="</table>";
		  echo $tb;
	 }
	else
	  echo "<b>No record to display</b>";
	  ?>
        </div>
        <div id="roll"> </div>
        </form>
        </center>
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