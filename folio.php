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
//	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_c.php";
	var str;

  if(cv=='folio_section') //start putme_login
  {
	///alert(cv+" "+v+" "+a+" "+b); //exit;
  	 var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them/00vv
			  
			if(v=='search')
			 {
				 if($("#folio_code").val()=='' && $("#title").val()=='' && $("#category").val()=='')
				  {
					  alert('Enter search criteria'); $('#roll').html('').show(); exit();
				  }
			 }
			
			if(v=='save')
			 {
				 if($("#folio_code").val()=='' || $("#title").val()=='' || $("#category").val()=='')
				  {
					  alert('All fields are required'); $('#roll').html('').show(); exit();
				  }
			 }
			if(v=='save_category')
			 {
				// alert(mydata);
				if($("#folio_cat").val()=='')
				  {
					  alert('All fields are required'); $('#roll').html('').show(); exit();
				  }
			 }
			 
			$.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a, ifolio_code:b, ititle:c, icategory:d, icgroup:e},function(data){
			//alert(data);	
			//$(divid).html('').show();	
			$(divid).html(data).show();
			$('#roll').html('').show();
			if(v=='save')
			    {
					  document.getElementById('sch_code').value="";
					  document.getElementById('dept_code').value="";
					  document.getElementById('dept_name').value="";
					  document.getElementById('category').value="";
					  
				}
			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					$('#roll').html('').show();
					document.getElementById('sch_code').value="";
					  document.getElementById('dept_code').value="";
					  document.getElementById('dept_name').value="";
					  document.getElementById('category').value="";
				  }//end of refresh div i.e to refresh the data dispay previously on selection of another department
			if(v=='edit')
				  {
					  //if($('#fileno').val()=='' && a!='auto')
					   //{ alert('Enter Staff File Number');$(divid).html('').show();exit;}
						//alert('here '+ cv + " "+ v + " id:"+a); $(divid).html('').show(); exit;
						//$.post(url,$("#staff_form").serialize()+"&contentvar="+cv+"&action="+v+"&id_val="+a,function(data){
										
						var pData=jQuery.parseJSON(data); 
						alert(pData.s_detail);
						var p=jQuery.parseJSON(pData.s_detail);
						
						$("#sch_code").val(p.sch_code); $("#dept_code").val(p.dept_code); $("#dept_name").val(p.dept_name);
						$("#category").val(p.category); $("#r_id_edit").val(p.r_id); 
						
						//$(divid).html('').show();
					//	});
				  } //for edit purpose
				 });
  }
	

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
	        <h2>Chart of Account Setup</h2>
                <p>Manage account folio codes...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Folio Setup</i></h3> -->
			<p>
			<form action="#" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
			<table cellspacing="5">
				<tr>
				<th align="left" nowrap="nowrap">Folio Category:</th><td align="left"><input name="folio_cat" type="text" id="folio_cat" style="text-transform:uppercase" tabindex="1" value="<?php echo $code;?>" size="40" align="left" /></td>
				</tr>
				<tr><th colspan="2"><input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('folio_section','save_category');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('folio_section','search_category');" /></th></tr>
			</table>
            		<table cellspacing="5">
				<tr>
					<th align="left">Folio Code:</th><td nowrap="nowrap"><input name="folio_code" type="text" id="folio_code" tabindex="2" value="<?php echo $code;?>" size="40"/></td>
				</tr>
				<tr>
					<th align="left">Title:</th><td nowrap="nowrap"><input name="title" type="text" id="title" tabindex="3" value="<?php echo $code;?>" size="40"/></td>
				</tr>
				<tr>
				<th align="left" nowrap="nowrap">Folio Category:</th>
				<td nowrap="nowrap"><select name="category" id="category" tabindex="4">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "SELECT DISTINCT *  from folio_categorytb order by folio_category");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['folio_category'];$pcode=@$rcourse['id'];
							echo "<option value='$pcode'>$scourse</option>";
							
						}
					
					?>
					</select>
				</td>
				</tr>
				<tr>
				<th align="left" nowrap="nowrap">Code Grouping:</th>
				<td nowrap="nowrap">
					<select name="cgroup" id="cgroup" class="form-control select2">
					<option selected="selected" value="">---</option>
					<option value="Income">Income</option>
					<option value="Assets">Assets</option>
					<option value="Liabilities">Liabilities</option>
					<option value="Expenses">Expenses</option>
					</select>
				</td>
				</tr>
				<tr>
					<th colspan="2">
				<input type="hidden" id="id" name="id" value="">
				<input type="hidden" id="status" name="status" value="">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('folio_section','save');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('folio_section','search');" />
				<!--,$('#ccode').val()-->
				
				</th></tr>
			</table>
                <div id="folio_section" style="border:groove 1px"></div>
				<!--<div id="display"></div>
				<div id="roll"></div> -->
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