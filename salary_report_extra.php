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
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
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
  
  if(cv=='another') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		
		});
  }//end of putme_login
  if(cv=="specific")
	{
		if(v=='specific')
			{
		$.post(url,{contentvar:cv,dept_acctcode:v},function(data){
		$(divid).html(data).show();
		$('#roll').html('').show();
		});
		}
		else
			{
			$('#specific').html('').show();
			$('#roll').html('').show();
			}
	}
  if(cv=='deduction_exception_section') //start putme_login
  {
// alert(cv+" "+v+" "+a); exit;
  	 var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			
			$.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a},function(data){	//a is the id of rec to edit/upd	
			//alert(data);	
			$(divid).html('').show();	
			$('#display').html(data).show();
			$('#roll').html('').show();
			
			 
			if(v=='save')
			    {
					  document.getElementById('folio_code').value="";
					  document.getElementById('fileno').value="";
					  document.getElementById('month').value="";
					  document.getElementById('year').value="";
					  document.getElementById('comment').value="";
					  
				}
			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					$('#roll').html('').show();
					 document.getElementById('folio_code').value="";
					  document.getElementById('fileno').value="";
					  document.getElementById('month').value="";
					  document.getElementById('year').value="";
					  document.getElementById('comment').value="";
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
   if(cv=='salary_computation_section') //start putme_login
  {
// alert(cv+" "+v+" "+a); exit;
		if($('#month').val()=='' || $('#year').val()=='')
			{
				alert('All fields are required ');
				$(divid).html('').show();	
				$('#display').html('').show();
				$('#roll').html('').show();
				exit();
			}
			if($('#staff').val()=='specific' && $('#fileno').val()=='')
				{
					alert('File Number field is required ');
					$(divid).html('').show();	
					$('#display').html('').show();
					$('#roll').html('').show();
					exit();
				}
			
  	 var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			
			$.post(url,{contentvar:cv,mydata:mydata,action:v},function(data){	//a is the id of rec to edit/upd	
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
					  document.getElementById('pvno').value="";				  }//end of refresh div i.e to refresh the data dispay previously on selection of another department
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
	        <h2>Payroll Summary / Analysis</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Payroll Summary / Analysis</i></h3> -->
			<p>
			<form action="salary_report_output.php" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm" target="_blank">
			
			<table border="0" align="left" cellpadding="3" cellspacing="0">
			<tr>
<th colspan='1' align="left">Month<br />
<select name="month" id="month" tabindex="3" style="width:200px">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct *  from monthtb order by convert(month_code,decimal)");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['month_name'];$pcode=@$rcourse['month_code'];
							echo "<option value='$pcode'>$scourse</option>";
							
						}
					
					?>
	  </select>
</th><th align="left">Year <br />
<select name="year" id="year" tabindex="4" style="width:200px">
					<option selected="selected" value="">---</option>
					<?php
					for($i=date('Y');$i>=2015;$i--)
					{
							//$scourse=@$rcourse['month_name'];$pcode=@$rcourse['month_code'];
							echo "<option value='$i'>$i</option>";
							
						}
					
					?>
	  </select>

</th>
  </tr>
			<tr>
			<th align="left">Staff Status
			<br />
			<select name="status" id="status" tabindex="1" onchange="" style="width:200px">
			  <option value="All" selected>All</option>
					<option value="Senior">Senior</option>
					<option value="Junior">Junior</option>
					
				  </select>
			</th>
			<th align="left">Category<br />
			
			
			<select name="category" id="category" tabindex="2" onchange="" style="width:200px">
			  <option value="All" selected>All</option>
					<option value="academic">Academic Staff</option>
					<option value="non-academic">Non-Academic Staff</option>
					
				  </select>
  </tr>
              <tr>
                <th colspan="1" align="left">Staff <br />
				<select name="staff" id="staff" tabindex="5" onchange="swapcontent('specific',this.value)" style="width:200px">
					<option selected="selected" value="all">All Staff</option>
					<option value="specific">Specific Staff</option>
					
				  </select><br />
				<span id='specific'></span></th>
				<th align="left">Department<br />
				<select name="dept" id="dept" tabindex="7" style="width:200px">
					<option selected="selected" value="all">All</option>
					<?php
					$r=@mysqli_query($con, "select distinct department  from payroll_scheduletb order by department");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['department'];$pcode=@$rcourse['department'];
							echo "<option value='$pcode'>$scourse</option>";
							
						}
					
					?>
				  </select>
</th>
  </tr>
			   <tr><th colspan="2" align="left">
			   			Folio Code<br />
				<select name="folio_code" id="folio_code" tabindex="8" style="width:420px">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct *  from foliotb where category not like '%bank%' order by title,folio_code");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['title'];$pcode=@$rcourse['folio_code'];
							echo "<option value='$pcode'>$scourse <=> ($pcode)</option>";
							
						}
					
					?>
				 </select>
			   </th>
			   </tr>
				<tr><th colspan="2" align="left"><label for="reportcategory"></label>
				      Report Type:<br>
<select name="reportcategory" id="reportcategory" style="width:420px">
  <option value="" selected="selected">Select Item...</option>
  <option value="payroll_summary">Payroll Summary</option>
  <option value="variance_report">Variance Report</option>
  <option value="pay_point_summary">Pay Point Summary List</option>
  <option value="net_pay_summary">Net Pay Summary</option>
				        </select>
				
				</th>
				</tr>
				<tr><th colspan="2">
				<input type="submit"  class="btn" name="sbtn" id="sbtn" value="Display Report" tabindex="11" />
				<!--<input type="button" class="btn" name="chbtn" id="chsbtn" value="Delete Processed Salary" onclick="if(confirm('Are you sure you want to perform this operation'))swapcontent('salary_computation_section','delete');" />
				
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('salary_computation_section','refresh','Debit');" />
				-->
				<!--,$('#ccode').val()-->
				
				</th></tr>
          </table>
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