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
include "function.php";?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	//$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_c.php";
	var str;
	
    if(cv=='unit_section') //start putme_login
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	if((v=='save') && ($('#dept_code').val()==''  || $('#unit_name').val()=='' || $('#unit_code').val()=='')){alert('Provide all The neccessary Fields');$(divid).html('').show();exit;}
	  var mydata = (JSON.stringify($('form').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login




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
	        <h2>Unit Setup</h2>
                <p>Manage Unit(s)...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
              <div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Unit Setup</i></h3> -->
			<p>
			<form action="#" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
            	<table width="362">
				<tr>
				<th width="92" align="left"><strong>Department:</strong></th>
				<td width="258"><select name="dept_code" id="dept_code" tabindex="1">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct *  from departmenttb order by dept_name");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['dept_name'];$pcode=@$rcourse['dept_code'];
							echo "<option value='$pcode'>$scourse</option>";
							
						}
					
					?>
					</select>
				</td>
				</tr>
				<tr>
				<th align="left"><strong>Unit Code:</strong></th><td><input name="unit_code" type="text" id="unit_code" tabindex="2" value="<?php echo $code;?>" size="40"/></td>
				</tr>
				<tr>
				<th align="left"><strong>Unit Name:</strong></th><td><input name="unit_name" type="text" id="unit_name" tabindex="3" value="<?php echo $code;?>" size="40"/></td>
				</tr>
				
				<tr><th colspan="2">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('unit_section','save');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('unit_section','search');" />
				<!--,$('#ccode').val()-->
				
				</th></tr>
				</table>
				<div id="display"></div>
				<div id="roll"></div>
                <div id="unit_section"></div>
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