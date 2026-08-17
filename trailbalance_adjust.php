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
  
  if(cv=="account" || cv=="account2")
	{
		$.post(url,{contentvar:cv,dept_acctcode:v},function(data){
		$(divid).html(data).show();
		$('#roll').html('').show();
		});
	}
  if(cv=='bank_account_section') //start putme_login
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
					  document.getElementById('acctcode').value="";
					  document.getElementById('acctno').value="";
					  document.getElementById('bankname').value="";
					  document.getElementById('accttype').value="";
					  document.getElementById('sortcode').value="";
					  
				}
			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					$('#roll').html('').show();
					 document.getElementById('acctcode').value="";
					  document.getElementById('acctno').value="";
					  document.getElementById('bankname').value="";
					  document.getElementById('accttype').value="";
					  document.getElementById('sortcode').value="";
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
					alert('Payee field is required ');
					$(divid).html('').show();	
					$('#display').html('').show();
					$('#roll').html('').show();
					exit();
				}
			if((v=='search' || v=='view_all') && ($('#dept').val()=='' ))
				{
					alert('Select Department/Unit/Center/School first and any other searching criteria');
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
	        <h2>ADJUST TRIALBALANCE</h2>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
            <div class="content">
		         <div class="title"><h2>&nbsp;</h2></div>
                  <h3><i><?=strtoupper($report_type);?></i></h3> 
						<p>
			          	<form action="<?php echo $_SERVER['PHP_SELF']."?r_val=".$_REQUEST['r_val']; ?> " method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm" target="">
								<?php
								if(isset($_POST['myear']) && isset($_POST['folio']) && isset($_POST['account'])){
									$folioname=get_folio_name($_POST['folio']);
									if($folioname==''){
										echo "<h4><span style='color:red;'>ERROR::Check and ensure you entered a correct folio code.</span></h4>";
									}else{
										$year=date('Y', strtotime($_POST['myear']."-01"));
										$month=date('m', strtotime($_POST['myear']."-01"));
										//echo $folioname;
										$queryString="REPLACE INTO trialbalance_adjust (tyear, tmonth, folio_code, entry_by, entry_date, status, moveto, transtype) VALUES ('{$year}', '{$month}', '{$_POST['folio']}', '{$login_id}', now(), '{$_POST['status']}', '{$_POST['account']}', '{$_POST['type']}')";
										if(mysqli_query($con, $queryString)){
											echo "<h4><span style='color:green;'>SUCCESS::Adjustment for {$folioname} ({$_POST['folio']}) has been save and will take effect the next time you load the trialbalance report.</span></h4>";
										}else{
											echo "<h4><span style='color:red;'>ERROR::Adjustment for {$folioname} ({$_POST['folio']}) not saved.</span></h4>";
										}
									}
								}
								?>
								<table align="center" cellpadding="5px">
									<tr>
										<td>Month, Year: </td>
										<td><input type="month" name="myear" id="myear" style="width: 200px" required /></td>
									</tr>
									<tr>
										<td>Folio Code:</td>
										<td><input type="text" name="folio" id="folio" style="width: 200px" required /></td>
									</tr>
									<tr>
										<td>Move Entry To: </td>
										<td>
										<select name="account" id="account" class="txt" style="width: 200px" required>
											<option selected="selected" value="">---...---</option>
											<option value="Liabilities">Credit/Liabilities/Income (Right Side)</option>
											<option value="Expenses">Debit/Expenses/Assets (Left Side)</option>
										</select>
										</td>
									</tr>
									<tr>
										<td>Transaction: </td>
										<td>
										<select name="type" id="type" class="txt" style="width: 200px" required>
											<option selected="selected" value="">---...---</option>
											<option value="Bank">Bank-Balances</option>
											<option value="Openning">Other Openning Balance</option>
											<option value="Trans">Other Transactions (Payments/Receipts)</option>
										</select>
										</td>
									</tr>
									<tr>
										<td>Status: </td>
										<td>
										<select name="status" id="status" class="txt" style="width: 200px" required>
											<option selected="selected" value="">---...---</option>
											<option value="Active">Activate</option>
											<option value="Not Active">Disable/Deactivate</option>
										</select>
										</td>
									</tr>
									<tr><th colspan="2"></th></tr>
									<tr>
										<th colspan="2">
										<input type="submit"  class="btn" name="sbtn" id="sbtn" value=" ADJUST " tabindex="11" />
										</th>
									</tr>
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