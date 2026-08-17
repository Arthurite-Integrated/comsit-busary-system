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
	<?php // include_once("sidebar_main.php"); ?>
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
                                
<form name="frmb" id="frm">
<?php include("header_main.php");?>
<div id="page-wrapper">
	<?php //include("slider.php");?>
	
	<div id="page" class="container">
		<!--<div class="content">-->
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
      <h3><i>Staff Record Update(e.g Retirement, Retrenchment, Death, Resignation)</i></h3>
                
			  <p>
       			Use this form to update staff record. Supply correct information in the spaces provided and ensure that you cross-check all your entries before submission. <br/>
            			<br/>
                      
          <table width="100%" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                          <th><font color="red">Staff Number: </font>
                          <!--<input name="fileno" type="text" id="fileno" size="40" onblur="swapcontent('load_staff_details',this.value);swapcontent('load_pix',this.value);"/> -->
                          
                          <input name="fileno" type="text" id="fileno" size="40" <?php if(strtolower($r_vals)!='super admin' and strtolower($r_vals)!='administrator' and strtolower($r_vals)!='registry officer' and strtolower($r_vals)!='registry admin') echo "value='$login_id' readonly=''"; ?> onblur="if (this.value !='') swapcontent('load_staff_details',this.value);"/>
						  <?php
						    echo "<script>document.getElementById('fileno').focus();</script>";
						  ?>
                          <br/><br /><span id="fullname"></span></th>
                        </tr>
                        <tr>
                          <th>
                               <div id="tabss"> <!-- Start main  tab div -->
                                      <ul>
                                        
                                        <li><a href="#tabs-9"><b>Staff Record Update</b></a></li>
                                        <li><a href="#tabs-10"><b>List of Staff Dew for Retirement</b></a></li>
                                        <li><a href="#tabs-11"><b>List of Retired Staff</b></a></li>  
                                        <li><a href="#tabs-12"><b>List of Retrenched Staff</b></a></li>
                                        <li><a href="#tabs-13"><b>List of Staff Who Have Died</b></a></li>
                                        <li><a href="#tabs-14"><b>List of Staff Who Have Resigned</b></a></li>                                       
                                    </ul>
                                      
                            
                                    <div id="tabs-9"> <!-- tab 9 trainig --->
                                       <font color="red"><b>Use this form to update staff record. Cross-check your entry before submission.</b></font>
                                       
                                       <center>   <table width="80%" border="0">
                                          <tr>
                                            <td width="20%">Status</td>
                                            <td width="28%"><select name="status" id="status" class="txt">
                                              <option selected="selected">---</option>
                                              <option value="Retirement">Retirement</option>
                                              <option value="Retrenchment">Retrenchment</option>
                                              <option value="Death">Death</option>
                                              <option value="Resignation">Resignation</option>
                                            </select>
                                              <br/>
                                            <span id="load_no_paper_read"></span>
                                            </td>
                                            <td width="12%">Date updated</td>
                                            <td width="40%"><input name="date_updated" type="text" id="date_updated" size="30"/></td>
                                          </tr>
                                          <tr>
                                            <td>Description (if any)</td>
                                            <td colspan="3"><textarea name="desc" id="desc" cols="70" rows="3"></textarea></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4"><div align="center">
                                              <input type="button" name="cmdemp" id="cmdemp" value="Save" class="btn" onClick="swapcontent('retirement_section','save');"/>
                                              <input type="button" name="cmdemp2" id="cmdemp2" value="Search" class="btn" onclick="swapcontent('retirement_section','search');"/>
                                            </div></td>
                                          </tr>
                                          <tr>
                                            <td colspan="4">
                                              <div id="display">
                                              
                                            </div>
                                            </td>
                                          </tr>
                                        </table></center>
                                    </div><!-- tab 9 ends ---> 
                                    
                                    <div id="tabs-10"> <!-- tab 10 list of staff dew for retirement --->
                                       <font color="red"><b><u>STAFF DEW FOR RETIREMENT</u></b></font>
                                       
                                       <center>   
                                        <?php
										  $res_r=@mysqli_query($con, "select fileno,category,dept_code,date_of_1st_appt,date_of_birth,datediff(CURDATE(),date_of_birth) as days from stafftb order by fileno,category");
										  $tb="<table><tr><th>S/NO</th><th>FILENO</th><th>FULLNAME</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th><th>DATE OF BIRTH</th><th>DATE OF FIRST APPT.</th><th>YEAR SPENT</th></tr>";
										  $sn=0;
										  if(@mysqli_num_rows($res_r)>=1)
										  {
											  while($rs_r=@mysqli_fetch_array($res_r))
											   {
												   
												    $fileno=@$rs_r['fileno'];
													$days=$rs_r['days'];
													$year_spent=@number_format($days/365,0); //365 days make one year, from date_of_birth and current date
													$category=@$rs_r['category']; //Academic or Non-Academic
													///you can do whatever base on retirement criteria
													//check whether already retired
													$res_chk=@mysqli_query($con, "select * from hr_status_historytb where fileno='$fileno' and status='Retirement'");
													if(@mysqli_num_rows($res_chk)>=1) $r_found=true; else $r_found=false;
												  if(($category=='Academic' and $year_spent>=70 and $r_found!=true)	 or ($category=='Non-Academic' and $year_spent>=65 and $r_found!=true) )
												  {
													 ++$sn;
												   $tb.="<tr><td>$sn</td><td>{$rs_r['fileno']}</td><td>".@get_staff_name($rs_r['fileno'])."</td><td>{$rs_r['category']}</td><td>".@get_dept_name($rs_r['dept_code'])."</td><td>".@date('d/m/Y',strtotime($rs_r['date_of_birth']))."</td><td>".@date('d/m/Y',strtotime($rs_r['date_of_1st_appt']))."</td><td>$year_spent</td></tr>";
												  } //end of in the range of retirement
											   }
											   
											   $tb.="</table>";
											   echo $tb;
										   }
										 else
										   echo "<br/><br/><font color='red'><b>No record to display</b></font>";
										?>
                                       </center>
                                    </div><!-- tab 10 ends --->
                                    
                                    <div id="tabs-11"> <!-- tab 11 list of retired staff --->
                                       <font color="red"><b><u>LIST OF RETIRED STAFF</u></b></font>
                                       
                                       <center>   
                                        <?php
										  $tb="<table><tr><th>S/NO</th><th>FILENO</th><th>FULLNAME</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th><th>DATE OF FIRST APPT.</th><th>DATE RETIRED</th></tr>";
										  $res_r=@mysqli_query($con, "select h.fileno,s.category,s.dept_code,h.entry_date,s.date_of_1st_appt from hr_status_historytb h, stafftb s where h.fileno=s.fileno and s.status!='Active' and h.status='Retirement' order by h.fileno,s.category");
										  $sn=0;
										 if(@mysqli_num_rows($res_r)>=1)
										  {
											  while($rs_r=@mysqli_fetch_array($res_r))
											   {
												   ++$sn;
												   $tb.="<tr><td>$sn</td><td>{$rs_r[fileno]}</td><td>".@get_staff_name($rs_r['fileno'])."</td><td>{$rs_r['category']}</td><td>".@get_dept_name($rs_r['dept_code'])."</td><td>".@date('d/m/Y',strtotime($rs_r['date_of_1st_appt']))."</td><td>".@date('d/m/Y',strtotime($rs_r['entry_date']))."</td></tr>";
											   }
											   
											   $tb.="</table>";
											   echo $tb;
										  }
										 else
										   echo "<br/><br/><font color='red'><b>No record to display</b></font>";
										?>
                                       </center>
                                    </div><!-- tab 11 ends ---> 
                                    
                                    <div id="tabs-12"> <!-- tab 12 list of retrenched staff --->
                                       <font color="red"><b><u>LIST OF RETRENCHED STAFF</u></b></font>
                                       
                                      <center>   
                                        <?php
										  $tb="<table><tr><th>S/NO</th><th>FILENO</th><th>FULLNAME</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th><th>DATE OF FIRST APPT.</th><th>DATE RETRENCHED</th></tr>";
										  $res_r=@mysqli_query($con, "select h.fileno,s.category,s.dept_code,h.entry_date,s.date_of_1st_appt from hr_status_historytb h, stafftb s where h.fileno=s.fileno and s.status!='Active' and h.status='Retrenchment' order by h.fileno,s.category");
										  $sn=0;
										 if(@mysqli_num_rows($res_r)>=1)
										  {
											  while($rs_r=@mysqli_fetch_array($res_r))
											   {
												   ++$sn;
												   $tb.="<tr><td>$sn</td><td>{$rs_r['fileno']}</td><td>".@get_staff_name($rs_r['fileno'])."</td><td>{$rs_r['category']}</td><td>".@get_dept_name($rs_r['dept_code'])."</td><td>".@date('d/m/Y',strtotime($rs_r['date_of_1st_appt']))."</td><td>".@date('d/m/Y',strtotime($rs_r['entry_date']))."</td></tr>";
											   }
											   
											   $tb.="</table>";
											   echo $tb;
										  }
										 else
										   echo "<br/><br/><font color='red'><b>No record to display</b></font>";
										?>
                                       </center>
                                    </div><!-- tab 12 ends --->    
                                    
                                    <div id="tabs-13"> <!-- tab 13 list of dead staff --->
                                       <font color="red"><b><u>LIST OF STAFF WHO HAVE DIED</u></b></font>
                                       
                                      <center>   
                                        <?php
										  $tb="<table><tr><th>S/NO</th><th>FILENO</th><th>FULLNAME</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th><th>DATE OF FIRST APPT.</th><th>DATE UPDATED</th></tr>";
										  $res_r=@mysqli_query($con, "select h.fileno,s.category,s.dept_code,h.entry_date,s.date_of_1st_appt from hr_status_historytb h, stafftb s where h.fileno=s.fileno and s.status!='Active' and h.status='Death' order by h.fileno,s.category");
										  $sn=0;
										 if(@mysqli_num_rows($res_r)>=1)
										  {
											  while($rs_r=@mysqli_fetch_array($res_r))
											   {
												   ++$sn;
												   $tb.="<tr><td>$sn</td><td>{$rs_r['fileno']}</td><td>".@get_staff_name($rs_r['fileno'])."</td><td>{$rs_r['category']}</td><td>".@get_dept_name($rs_r['dept_code'])."</td><td>".@date('d/m/Y',strtotime($rs_r['date_of_1st_appt']))."</td><td>".@date('d/m/Y',strtotime($rs_r['entry_date']))."</td></tr>";
											   }
											   
											   $tb.="</table>";
											   echo $tb;
										  }
										 else
										   echo "<br/><br/><font color='red'><b>No record to display</b></font>";
										?>
                                       </center>
                                    </div><!-- tab 13 ends --->  
                                    
                                    <div id="tabs-14"> <!-- tab 14 resignation list --->
                                       <font color="red"><b><u>LIST OF STAFF WHO HAVE RESIGNED</u></b></font>
                                       
                                      <center>   
                                        <?php
										  $tb="<table><tr><th>S/NO</th><th>FILENO</th><th>FULLNAME</th><th>STAFF CATEGORY</th><th>DEPARTMENT</th><th>DATE OF FIRST APPT.</th><th>DATE RESIGNED</th></tr>";
										  $res_r=@mysqli_query($con, "select h.fileno,s.category,s.dept_code,h.entry_date,s.date_of_1st_appt from hr_status_historytb h, stafftb s where h.fileno=s.fileno and s.status!='Active' and h.status='Resignation' order by h.fileno,s.category");
										  $sn=0;
										 if(@mysqli_num_rows($res_r)>=1)
										  {
											  while($rs_r=@mysqli_fetch_array($res_r))
											   {
												   ++$sn;
												   $tb.="<tr><td>$sn</td><td>{$rs_r['fileno']}</td><td>".@get_staff_name($rs_r['fileno'])."</td><td>{$rs_r['category']}</td><td>".@get_dept_name($rs_r['dept_code'])."</td><td>".@date('d/m/Y',strtotime($rs_r['date_of_1st_appt']))."</td><td>".@date('d/m/Y',strtotime($rs_r['entry_date']))."</td></tr>";
											   }
											   
											   $tb.="</table>";
											   echo $tb;
										  }
										 else
										   echo "<br/><br/><font color='red'><b>No record to display</b></font>";
										?>
                                       </center>
                                    </div><!-- tab 14 ends --->                       
                                    
       
                            </div> <!-- end main tab div -->
                          
                          </th>
                        </tr>
          </table>
            
            
            
            
              <p>
         
                        
           
            
              
              </p>
              
		<!--</div> end of content -->

		
		<!-- ############### Side bar ###############################-->
		
			<?php //include("sidebar_main.php");?>
		<!-- end of side bar -->
	</div><!-- end of container -->
</div><!--  end of page-wrapper   -->
<?php //include("footer_wrapper.php");?>
<?php include("footer.php");?>
<span id="roll"></span>
</form>                                
           
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