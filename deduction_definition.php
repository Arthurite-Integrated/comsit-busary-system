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
	$(divid).html('<img src="images/loader.gif" width="30" height="30" alt="loading">').show();
	//$("#roll").html('<img src="images/loader.gif" width="30" height="30" alt="loading">').show();
	var url="scriptfile_b.php";
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
  
  if(cv=='category_option') //start putme_login
  {
  	if(v=='All')
		{
			$("#staff_status").val('All');
			$("#sex").val('All');
			$("#religion").val('All');
			$("#level").val('All');
			$("#criteria").val('');
			$("#amount").val('');
			$("#position").val('All');
			$("#scale").val('All');
			$("#rank").val('All');
			//$("#category_div").hide();
		}
		else
			{
				$("#staff_status").val('');
				$("#sex").val('');
				$("#religion").val('');
				$("#level").val('All');
				$("#criteria").val('');
				$("#amount").val('');
				$("#position").val('');
				$("#scale").val('');
				$("#rank").val('');
				//$("#category_div").show();
			}
	$('#roll').html('').show();	
		
  }//end of putme_login
  if(cv=="account" || cv=="account2")
	{
		$.post(url,{contentvar:cv,dept_acctcode:v},function(data){
		$(divid).html(data).show();
		$('#roll').html('').show();
		});
	}
	if(cv=="change_by_status"){
		$.post(url,{contentvar:cv, staff_status:v},function(data){
		$(divid).html(data).show();
		$('#roll').html('').show();
		});
	}
  if(cv=='deduction_definition_section') //start putme_login
  {
	// alert(cv+" "+v+" "+a); exit;
	var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
	
	if(v=='search')		
	{
		if($("#folio_code").val()=='')
		{
			alert("No search criteria provided.");
			$(divid).html('').show();	
			$('#roll').html('').show();
			exit();
		}
	}
	
	$.post(url,{contentvar:cv, mydata:mydata, action:v, r_id:a, istat:b},function(data){	//a is the id of rec to edit/upd
		//alert(data);	
		$(divid).html('').show();	
		$('#display').html(data).show();
		$('#roll').html('').show();
		
		if(v=='save')
		{
			if($("#folio_code").val() != '' && $("#category").val() != '' && $("#istat").val() !='' ){
				$("#folio_code").val('');
				$("#category").val('');
				$("#staff_status").val('');
				$("#sex").val('');
				$("#religion").val('');
				$("#level").val('All');
				$("#criteria").val('');
				$("#amount").val('');
				$("#position").val('');
				$("#scale").val('');
				$("#rank").val('');
				$("#istat").val('');
			}
		}
		if(v=='refresh')
		{ 
			$(divid).html('').show();
			$('#display').html('').show();
			$('#roll').html('').show();
			$("#folio_code").val('');
			$("#category").val('');
			$("#staff_status").val('');
			$("#sex").val('');
			$("#religion").val('');
			$("#level").val('All');
			$("#criteria").val('');
			$("#amount").val('');
			$("#position").val('');
			$("#scale").val('');
			$("#rank").val('');
			$("#istat").val('');
		}//end of refresh div i.e to refresh the data dispay previously on selection of another department
		if(v=='edit')
		{
			var pData=jQuery.parseJSON(data);
			alert(pData.s_detail);
			var p=jQuery.parseJSON(pData.s_detail);
			
			$("#sch_code").val(p.sch_code); $("#dept_code").val(p.dept_code); $("#dept_name").val(p.dept_name);
			$("#category").val(p.category); $("#r_id_edit").val(p.r_id); $("#istat").val(p.item_status);
		} //for edit purpose
	});
  }
   if(cv=='posting_section') //start putme_login
  {
// alert(cv+" "+v+" "+a); exit;
		if((v=='save' || v=='update')&&($('#dept').val()=='' || $('#acctcode').val()=='' || $('#fileno').val()=='' || $('#folio_code').val()=='' || $('#amount').val()=='')) //$('#transdate').val()=='' || 
			{
				alert('All fields are required ');
				$(divid).html('').show();	
				$('#display').html('').show();
				$('#roll').html('').show();
				exit();
			}
			if((v=='save' || v=='update')&&(a=='Credit') && ($('#payee').val()=='' ))
				{
					alert('Payee field is required!');
					$(divid).html('').show();	
					$('#display').html('').show();
					$('#roll').html('').show();
					exit();
				}
			if((v=='search' || v=='view_all') && ($('#dept').val()=='' ))
				{
					alert('Select Department/Unit/Center/School first and any other searching criteria!');
					$(divid).html('').show();	
					$('#display').html('').show();
					$('#roll').html('').show();
					exit();
				}
  	 var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			
			$.post(url,{contentvar:cv,mydata:mydata,action:v,transtype:a,r_id:b},function(data){	//a is the id of rec to edit/upd	
			//alert(data);	
			$(divid).html('').show();	
			$('#display').html(data).show();
			$('#roll').html('').show();
			
			 
			if(v=='save')
			    {
					  document.getElementById('dept').value="";
					  document.getElementById('acctcode').value="";
					  //document.getElementById('transdate').value=null; 
					  $('#transdate').val('');
					  document.getElementById('fileno').value="";
					  document.getElementById('folio_code').value="";
					  document.getElementById('amount').value="";
					  document.getElementById('comment').value="";
					  document.getElementById('receiptno').value="";
					  document.getElementById('payee').value="";
					  document.getElementById('chequeno').value="";
					  document.getElementById('pvno').value="";
				}
			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					$('#roll').html('').show();
					  document.getElementById('dept').value="";
					  document.getElementById('acctcode').value="";
					  //document.getElementById('transdate').value=null; 
					   $('#transdate').val('');
					  document.getElementById('fileno').value="";
					  document.getElementById('folio_code').value="";
					  document.getElementById('amount').value="";
					  document.getElementById('comment').value="";
					  document.getElementById('receiptno').value="";
					  document.getElementById('payee').value="";
					  document.getElementById('chequeno').value="";
					  document.getElementById('pvno').value="";				  
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
} //end of swapcontent

function show_hide_rows(){
	if($('#category').val()=="All") { 
		$(".s_cat").hide();
	} else { 
		$('.s_cat').show(); 
	}
}
$(document).ready(function(e) {
    $(".s_cat").hide();
});
 </script>
 <style>
 .s_cat{
	 background-color:#CCC;
 }
 </style>
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
	        <h2>Deduction Definition (Group)</h2>
                <p>Define allowances for groups.</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
   <div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Deduction Definition</i></h3> -->
			<p>
			<form action="#" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
			
			<table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
			<tr>
				<td colspan="2" align="left" valign="top"><strong>Item Code</strong>:<strong style="color:#F00">*</strong><br />
				<select name="folio_code" id="folio_code" tabindex="1" autofocus>
					<option selected="selected" value="">Code <|> Naration...</option>
					<?php
					//$r=@mysqli_query($con, "select distinct * from salary_codetb where category = 'DEDUCTION' and status='Active' order by account_code");
					$r=@mysqli_query($con, "select distinct * from foliotb where not fundcenter in ('01') order by folio_code");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['title'];
							//$pcode=@$rcourse['account_code'];
							$pcode=@$rcourse['folio_code'];
							echo "<option value='$pcode'>$pcode <|> $scourse</option>";
						}
					?>
					</select>
				</td>
				</tr>
			    <tr>
                <td align="left" valign="top"><strong>Category:</strong><strong style="color:#F00">*</strong><br />
                <select name="category" id="category" tabindex="2" onchange="show_hide_rows(); swapcontent('category_option',this.value);" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option><option value="Categorized">Categorized</option>
				</select>
                </td>
                <td align="left" valign="top"><strong>Payment Status</strong><strong style="color:#F00">*</strong>:<br />
                <select name="istat" id="istat" tabindex="9" style="width:150px">
					<option value="" selected>--</option>
					<option value="Active">Active (Pay Once)</option>
					<option value="Constant">Constant (Continuous)</option>
					<option value="Suspend">Suspend (Don't Pay)</option>
				</select>
				</td>
  </tr>
				<tr class="s_cat">
				  <td align="left" valign="top"><strong>Staff Status</strong>:<br />
					<select name="staff_status" id="staff_status" tabindex="3" style="width:150px" onChange="swapcontent('change_by_status',this.value);">
					<option value="" selected>--</option>
					<option value="All">All</option><option value="Academic">Academic</option>
					<option value="Non-Academic">Non-Academic</option>
				</select></td>
				  <td align="left" valign="top"><strong>Grade Level</strong>:<br />
                <select name="level" id="level" tabindex="4" style="width:150px">
				
					<option value="All"  selected>All</option>
					<!--<option value="1-5"> 1 - 5 (Junior)</option>
					<option value="6-15"> 6 - 15 (Senior)</option>
					<option value="6-9"> 6 - 9 (Senior)</option>-->
					<option value="1"> Level 1</option>
					<option value="2"> Level 2</option>
					<option value="3"> Level 3</option>
					<option value="4"> Level 4</option>
					<option value="5"> Level 5</option>
					<option value="6"> Level 6</option>
					<option value="7"> Level 7</option>
					<option value="8"> Level 8</option>
					<option value="9"> Level 9</option>
					<!--<option value="10"> Level 10</option>-->
					<option value="11"> Level 11</option>
					<option value="12"> Level 12</option>
					<option value="13"> Level 13</option>
					<option value="14"> Level 14</option>
					<option value="15"> Level 15</option>
				</select></td>
  </tr>
				<tr class="s_cat">
                <td align="left" valign="top"><strong>Religion</strong>:<br />
					<select name="religion" id="religion" tabindex="5" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option><option value="Islam">Islam</option>
					<option value="Christianity">Christianity</option>
				</select>
                </td>
                <td align="left" valign="top"><strong>Sex</strong>:<br />
                <select name="sex" id="sex" tabindex="6" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option><option value="Male">Male</option><option value="Female">Female</option>
				</select>
				</td>
  </tr></table>
  <div id="change_by_status">
  <table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
				<tr class="s_cat">
				  <td align="left" valign="top"><strong>Position:</strong><br />
                <select name="position" id="position" tabindex="7" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option>
					<?php
					$q= mysqli_query($con, "select distinct position  from hr_positiontb order by position");
					while ($r= mysqli_fetch_array($q, 3 ))
						echo "<option value='".$r['position']."'>".$r['position']."</option>";
					?>
				</select></td>
				  <td align="left" valign="top"><strong>Salary Scale</strong>:<br />
                <select name="scale" id="scale" tabindex="8" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option>
                    <?php
					$q=@mysqli_query($con, "select distinct scale_name  from scale_nametb where status='Active'");
					while ($r= mysqli_fetch_array($q, 3 ))
						echo "<option value='".$r['scale_name']."'>".$r['scale_name']."</option>";
					?>
				</select></td>
  </tr>
				<tr class="s_cat">
				  <td align="left" valign="top"><strong>Rank</strong>:<br />
                <select name="rank" id="rank" tabindex="9" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option>
                    <?php
					$q=@mysqli_query($con, "select distinct rank from hr_ranktb order by rank");
					while ($r= mysqli_fetch_array($q, 3 ))
						echo "<option value='".$r['rank']."'>".$r['rank']."</option>";
					?>
				</select></td>
				  <td align="left" valign="top">&nbsp;</td>
  </tr></table>
  </div>
  <table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
                <td width="29%" align="left" valign="top"><strong>Criteria</strong>:<strong style="color:#F00">*</strong><br />
					<select name="criteria" id="criteria" tabindex="15" style="width:150px">
					<option value="" selected>--</option>
					<option value="%Basic">% Basic</option>
					<option value="%Basic">% Consolidated</option>
                    <option value="%Gross">% Gross</option>
					<option value="Fixed">Fixed</option>
				</select>
                </td>
                <td width="71%" align="right" valign="bottom">Note: [<strong style="color:#F00">*</strong>] Mandatory fields!
				</td>
  </tr>
				
				<tr>		
				<th colspan="2" align="center">Value / Amount (=N=)<strong style="color:#F00">*</strong>: 
				<input type='text' name='amount' class='amt' id='amount' value='' size='40'  style='background-color: #FEFFB0;font-weight: bold;text-align:center;' tabindex='16'>	</th>
				</tr>
				<tr>
				<th colspan="2">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" tabindex="17" onclick="swapcontent('deduction_definition_section','save','Debit','');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('deduction_definition_section','search','Debit','');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('deduction_definition_section','view_all','Debit','');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('deduction_definition_section','refresh','Debit','');" />
	 			</th>
				</tr>
				<tr>
				  <td colspan="2" style="font-size:12px"><div id="deduction_definition_section"></div>
  <div id="display"></div>
				<div id="roll"></div></td>
			  </tr>
				<tr>
				  <td colspan="2">&nbsp;</td>
			  </tr>
                
  </table>
  
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