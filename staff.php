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

//print_r(PDO::getAvailableDrivers()); exit;

?>
<!DOCTYPE html>
<html>
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
//include "function.php";
require_once "function.php";
?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<style>
	input{
		width:220px;
	}
	select{
		width:225px;
	}
</style>
<script>


$(document).ready(function(){
	//;
	/**/
	//alert('Muyi');
	$('#cmdsave').click(function(e){
		test =  $('#frmbio').form('validate');
		
/*		var file = $("#file")[0].files[0];
		var sign = $("#signature")[0].files[0];
var fileName = file.name;
var fileSize = file.size;
var fileType = file.type;
var fileTmp_name = file.tmp_name;
		
		
		alert(fileName); exit();*/
		if(test) {swapcontent('save_bio','cmdsave');}
		
		

      //  alert('OK');
    });
	
	});

function check_form()
{
	//alert('here'); return false;
	if($("#fileno").val()=='' || $("#title").val()=='' || $("#surname").val()=='' || $("#first_name").val()=='' || $("#religion").val()=='' || $("#category").val()=='' || $("#dept").val()=='' || $("#sex").val()=='' || $("#level").val()=='' || $("#step").val()=='' || $("#bankname").val()=='' || $("#acct_no").val()=='')
	{
		//compulsory field has not been filed
		alert("All compulsory fields must be supplied before you can proceed");
		return false;
	}
  else
     return true;
} //end of function check form



 function myformatter(date){
var y = date.getFullYear();
var m = date.getMonth()+1;
var d = date.getDate();
return y+'-'+(m<10?('0'+m):m)+'-'+(d<10?('0'+d):d);
}
function myparser(s){
if (!s) return new Date();
var ss = (s.split('-'));
var y = parseInt(ss[0],10);
var m = parseInt(ss[1],10);
var d = parseInt(ss[2],10);
if (!isNaN(y) && !isNaN(m) && !isNaN(d)){
return new Date(y,m-1,d);
} else {
return new Date();
}
}


function refresh_form()
{
  if($("#fileno").val() != ""){	
	$("#title").val('');$("#surname").val('');$("#first_name").val('');$("#other_name").val('');$("#religion").val('');
	$("#category").val('');$("#state").val(''); $("#lga").val('');
	$("#dept").val('');$("#unit").val('');$("#rank").val('');$("#sex").val('');$("#level").val('');$("#step").val('');
	$("#email").val('');$("#phone_no").val('');$("#bankname").val('');$("#acct_no").val(''); $("#status").val('');
	$("#roll").html('').show();$("#display").html('').show();
			$('#date_birth').datebox('setValue', '');
			$('#date_appoint').datebox('setValue', '');
			$('#date_assume').datebox('setValue', '');
			
			swapcontent('lgadiv','','');

swapcontent('load_unit','', '');
  }
}

function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	//$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show(); 
	var url="scriptfile_c.php";
	var str;


if(cv=='loadpix')
	{
				
		if(v=='pix'){
	//		alert('I reach here in pix');
		$("#upload_type").val(v);
		document.getElementById("imageform_pix").target = "preview2";
			imageform_pix.submit();
		
		
		}
		else if(v=='sign')
		{
		//	alert('I reach here in sign');
			$("#upload_type").val(v);
			document.getElementById("imageform_pix").target = "preview1";
			imageform_pix.submit();
			}
	}


if(cv=='save_bio')
{
	//alert(cv);
	/*if((v=='Update Record') && ($('#category').val()==''  || $('#level').val()=='' || $('#grade').val()=='' || $('#step').val()=='') ){alert('Provide all The neccessary Fields');$(divid).html('').show();exit;}*/
	  var mydata = (JSON.stringify($('form').serializeObject()));
	//alert(mydata);
	
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		//$("#roll").html('').show();
		//$(divid).html('').show();
		
		});
}
if(cv=='load_unit')
{
			$.post(url,{contentvar:cv,dept_code:v,unit_code:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		//$("#roll").html('').show();
		});

}	


if(cv=='lgadiv')
{
			$.post(url,{contentvar:cv,val:v,lga_id:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		//$("#roll").html('').show();
		});

}	


  if(cv=='load_staff_details') //start biodata
	  {
		  	//alert('CV:'+cv+" V:"+v); //exit;
		 if(v != ""){
			$.post(url,{contentvar:cv,fileno:v},function(data){	//a is the id of rec to edit/upd
			//$(divid).html(data).show();
			$(divid).html('').show();
			//alert(data);
			var pData=jQuery.parseJSON(data); 

			if(pData != '')
			 {
			var p=jQuery.parseJSON(pData.s_detail);
				//alert(p);
			$("#fn").val(v);
			$("#title").val(p.title);
			
			$("#surname").val(p.surname);
			$("#first_name").val(p.first_name);
			$("#other_name").val(p.other_name);
			$("#state").val(p.state_id);
			

swapcontent('lgadiv',p.state_id,p.lga_id)

swapcontent('load_unit',p.dept_code, p.unit_code)

			$("#religion").val(p.religion);
			$("#category").val(p.category);
			$("#dept").val(p.dept_code);
			//$("#unit").val(p.unit_code);
			$("#rank").val(p.rank);$("#sex").val(p.sex);$("#level").val(p.level);$("#step").val(p.step);
			$("#email").val(p.email);
			$("#phone_no").val(p.phone_no);
			$("#bankname").val(p.bank_name);$("#acct_no").val(p.acct_no); $("#status").val(p.status);

			$('#date_birth').datebox('setValue', p.date_of_birth);
			$('#date_appoint').datebox('setValue', p.date_of_1st_appt);
			$('#date_assume').datebox('setValue', p.date_of_assumption);

			var v_new = v.replace("/","");
			var fileno = v_new.replace(" ","");
				
			  var pix = 'pictures/'+ fileno.toUpperCase() +'.jpg';
			  var sign = 'pictures/'+ fileno.toUpperCase() + '_sign' +'.jpg';
			 var iframe = $('#preview2');
			 iframe.attr('src', pix);

			 var iframe = $('#preview1');
			 iframe.attr('src', sign);


<?php // echo "<img src='pictures/".$actual_image_name."'  class='preview2' width='150' height='150'>";	 ?>

			//$("#display").html('<img src="<?php echo 'pictures/'.'00000001AA'.'.jpg';?>" width="100" height="100">').show();
			//$("#roll").html('<img src="<?php echo 'pictures/'.$sign.'.jpg';?>" width="100" height="100">').show();
			//$(divid).html(data).show();	
			//exit;
			 }
			else
			 {
				 refresh_form();
				 //alert('Nothing come...');
			 }/* */
			//$("#roll").html('').show();
			//$('#display').html(data).show();
			
			});
		 }
	  }//end of add biodata
  
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
	        <h2>Staff Setup</h2>
                <p>Entry and modification of staff data.</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div id="page" class="container">
	  <!--<div class="content"> -->
				<!--<div class="title"><h2>Recent Updates</h2></div> 
        <h3><i>Staff Setup</i></h3>-->
		  
          <center>
                  <form  name="frmbio" id="frmbio" method="post" enctype="multipart/form-data" action="">
                  <table width="100%" border="0">
                    <tr>
                      <th height="34" colspan="4" bgcolor="#99CC66"><div align="center"><b>Staff ID:</b>  
                          <input name="fileno" type="text" id="fileno" size="25" onblur="refresh_form(); swapcontent('load_staff_details',this.value);" required/>
                        <span style="font-size:11px">(Press 'TAB' after entry to begin search)</span></div></th>
                    </tr>
                    <tr>
                      <th width="17%" height="35" align="left">Title </th>
                      <td width="18%" height="35" align="left"><!--<select name="title" id="title" >-->
                      <select  name="title" id="title">
                        <option value="" selected="selected"></option><!---->
                        <option value="Mr.">Mr.</option>
                        <option value="Miss">Miss</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Dr.">Dr.</option>
                        <option value="Engr.">Engr.</option>
                        <option value="Prof.">Prof.</option>
                        <option value="Dr.(Mrs.)">Dr.(Mrs.)</option>
                      </select></td>
                      <th width="16%" height="35" align="left">Surname</th>
                      <td width="49%" height="35" align="left"><input name="surname" type="text" id="surname" size="25" required /></td>
                    </tr>
                    <tr>
                      <th height="35" align="left">First Name</th>
                      <td height="35" align="left"><input name="first_name" type="text" id="first_name" size="25" required/></td>
                      <th height="35" align="left">Othername</th>
                      <td height="35" align="left"><input name="other_name" type="text" id="other_name" size="25" class="easyui-validatebox"/></td>
                    </tr>
                    <tr>
                      <th height="35" align="left">State</th>
                      <td height="35" align="left"><select name="state" id="state"  onchange="swapcontent('lgadiv',document.getElementById('state').value)" >
                        <option selected="selected" value="<?php echo $state_id;?>"><?php echo $state_name;?></option>
                        <?php
                          $res_c=@mysqli_query($con, "select * from statetb order by state_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $state_id=@$rs_c['state_id'];
							  $state_name=@$rs_c['state_name'];
                              echo "<option value='$state_id'>$state_name</option>";
                           }
                          echo "</select>";
						 ?>
                      </select></td>
                      <th height="35" align="left">LGA</th>
                      <td height="35" align="left"><span id="lgadiv">
                        <select name="lga" id="lga" >
                        <option  value=''></option>
                        <?php
                      /*  $res_c=@mysqli_query($con, "select * from lgatb order by lga_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $lga_id=@$rs_c['lga_id'];
							 $lga_name=@$rs_c['lga_name'];
							 echo "<option value='$lga_id'>$lga_name</option>";
                           }*/
						   ?>
                        </select>
                      </span>&nbsp;</td>
                    </tr>
                    <tr>
                      <th height="35" align="left">Religion</th>
                      <td height="35" align="left"><select name="religion" id="religion" >
                        <option selected="selected"></option>
                        <option value="Islam">Islam</option>
                        <option value="Christianity">Christianity</option>
                      </select></td>
                      <th height="35" align="left">Staff Category</th>
                      <td height="35" align="left"><select name="category" id="category" >
                        <option selected="selected" value=""></option>
                        <option value="Academic">Academic</option>
                        <option value="Non-Academic">Non-Teaching</option>
                        
                      </select></td>
                    </tr>
                    <tr>
                      <th height="35" align="left">Date of Birth</th>
                      <td height="35" align="left"><input type="text" name="date_birth" id="date_birth" class="easyui-datebox"  data-options="formatter:myformatter,parser:myparser" />
                      
                    <!--  <input type="text" name="date_birth1" id="date_birth1" class="easyui-datebox"  data-options="formatter:myformatter,parser:myparser"  required="required" /> -->
                      </td>
                      <th height="35" align="left">Staff Status</th>
                      <td height="35" align="left"><div align="left">
                        <select name="status" id="status">
                          <option value="Active" selected="selected">Active</option>
                          <option value="Inactive">Inactive</option>
                        </select>
                      </div></td>
                    </tr>
                    <tr>
                      <th height="35" align="left">Date of first appointment</th>
                      <td height="35" align="left"><input type="text" name="date_appoint" id="date_appoint" class="easyui-datebox"  data-options="formatter:myformatter,parser:myparser" /></td> <!--class="easyui-datebox"-->
                      <th height="35" align="left">Date of Assumption</th>
                      <td height="35" align="left"><input type="text" name="date_assume" id="date_assume" class="easyui-datebox"  data-options="formatter:myformatter,parser:myparser" /></td>
                    </tr>
                    <tr>
                      <th height="35" align="left">Department</th>
                      <td height="35" align="left"><select name="dept" id="dept" onchange="swapcontent('load_unit',document.getElementById('dept').value)">
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
                      </select></td>
                      <th height="35" align="left">Unit</th>
                      <td height="35" align="left"><span id="load_unit"><select name="unit" id="unit">
                        <option selected="selected" value=''>---</option>
                        <?php
                        /*  $res_c=@mysqli_query($con, "select * from unittb order by unit_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $unit_code=@$rs_c['unit_code'];
							  $unit_name=@$rs_c['unit_name'];
                              echo "<option value='$unit_code'>$unit_name</option>";
                           }
                          echo "</select>";*/
						 ?>
                      </select></span></td>
                    </tr>
                    <tr>
                      <th height="35" align="left">Rank</th>
                      <td height="35" align="left"><input name="rank" type="text" id="rank" size="25"  required="required" /></td>
                      <th height="35" align="left">Sex</th>
                      <td height="35" align="left"><select name="sex" id="sex" >
                        <option selected="selected"></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                      </select></td>
                    </tr>
                    <tr>
                      <th height="35" align="left">Grade Level</th>
                      <td height="35" align="left"><select name="level" id="level">
                        <option selected="selected" value="" ></option>
                        <?php
                          $res_c=@mysqli_query($con, "select * from level_categorytb order by convert(level,decimal)");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $level=@$rs_c['level'];
                              echo "<option value='$level'>$level</option>";
                           }
                          echo "</select>";
						 ?>
                      </select></td>
                      <th height="35" align="left">Grade Step</th>
                      <td height="35" align="left"><select name="step" id="step" >
                        <option selected="selected" value=""></option>
                        <?php
                          $res_c=@mysqli_query($con, "select * from steptb order by convert(step,decimal)");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $step=@$rs_c['step'];
                              echo "<option value='$step'>$step</option>";
                           }
                          echo "</select>";
						 ?>
                      </select></td>
                    </tr>
                    <tr>
                      <th height="35" align="left">Email</th>
                      <td height="35" align="left"><input name="email" type="text" id="email" size="25"  data-options="validType:'email'"  required="required"/>  <!--data-options="validType:'email'" -->
                      
                      </td>
                      <th height="35" align="left">Phone Number</th>
                      <td height="35" align="left"><input name="phone_no" type="text" id="phone_no" size="25" width="11"  /></td>
                    </tr> <!--data-options="required:true class="easyui-numberbox" class="easyui-combobox"   "-->
                    <tr>
                      <th height="35" align="left">Bank Name</th>
                      <td height="35" align="left"><select name="bankname" id="bankname"> <!--class="easyui-combobox"-->
                        <option selected="selected" value=""></option>
                        <?php
                          $res_c=@mysqli_query($con, "select * from banktb order by bankname");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $bankname=@$rs_c['bankname'];
                              echo "<option value='$bankname'>$bankname</option>";
                           }
                          echo "</select>";
						 ?>
                      </select></td>
                      <th height="35" align="left">Account Number</th>
                      <td height="35" align="left"><input name="acct_no" type="text" id="acct_no" size="25"  /></td>
                    </tr>
                    <tr>
                      <th colspan="2"></th>
                      <th colspan="2"></th>
                    </tr>
                    
                 </table>
                 </form>
                 <!--<form> -->
                 <form id="imageform_pix" name="imageform_pix" method="post" enctype="multipart/form-data" action='ajaximage2.php?tablename=candidatetb&cri_field=regno&foldername=pictures' target="preview1" ><input type="hidden" id="fn" name="fn" />
                 <input type="hidden" id="upload_type" name="upload_type" />
                 <table>
                    <tr>
                      <th>Passport</th>
                      <td><!--<input type="file" name="file" id="file" class="btn"/>--><input type="file" name="photoimg_pix" id="photoimg_pix" accept="image/jpg;image/jpeg"  onchange="swapcontent('loadpix','pix','candidatetb','regno','pictures','<?php echo $pixpath ?>')"/></td>
                      <th>Signature</th>
                      <td><!--<input type="file" name="signature" id="signature" class="btn"/>--><input type="file" name="photoimg_sign" id="photoimg_sign" accept="image/jpg;image/jpeg"  onchange="swapcontent('loadpix','sign','candidatetb','regno','pictures','<?php echo $pixpath ?>')"/></td>
                    </tr>
                    <tr>
                      <th colspan="4"><div align="center"><strong><font color="#2AA100">NOTE:</font> <font color="red">Passport and Signature must be JPEG (.jpg) file and must not exceed 30KB in size.</font></strong></div></th>
                    </tr>
                    <tr><input type="hidden" value="<?php echo $r_val_code;?>" name="r_val" id="r_val"/>
                      <td colspan="4"><div align="center">
                        <input type="button" name="cmdsave" id="cmdsave" value="Update Record" class="btn" onclick="" /> <!-- -->
                        <input type="button" name="button" id="button" value="Refresh" onclick="refresh_form();" class="btn"/>
                      </div></td>
                    </tr>
                  </table>
                 <?php
				  
				 ?>
        <div id="display"> </div>
        <iframe id="preview2" name="preview2" src="<?php if(@file_exists($pixpath) && $pixpath=='') echo $pixpath1; elseif(@file_exists($pixpath) && $pixpath!='')  echo $pixpath; else echo $pixpath1; ?>" style="width:150; height:150;" frameborder="0" marginheight="0" marginwidth="0"  ></iframe>
        <iframe id="preview1" name="preview1" src="<?php if(@file_exists($pixpath) && $pixpath=='') echo $pixpath1; elseif(@file_exists($pixpath) && $pixpath!='')  echo $pixpath; else echo $pixpath1; ?>" style="width:150; height:75;" frameborder="0" marginheight="0" marginwidth="0"  ></iframe>
         <div id="update_biodata"></div>
        <div id="roll"> </div>
        <div id="load_staff_details"></div>
        <div id="save_bio"></div> 
        </form>
        </center>
        
	 </div>  <!--end of content -->

		
		<!-- ############### Side bar ###############################-->
		
			<?php //include("sidebar_main.php");?>
		<!-- end of side bar -->
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