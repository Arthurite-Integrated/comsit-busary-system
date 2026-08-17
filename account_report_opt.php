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
	        <h2><?php echo $report_type=$_REQUEST['rtype']; ?></h2>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<div class="title"><h2>&nbsp;</h2></div>
                <h3><i><?=strtoupper($report_type);?></i></h3> 
			<p>
			<form action="account_report.php" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm" target="_blank">
			
			<table align="center">
			<!--<tr>
                <th colspan="2">Select Project<br />
                  <select name="dept" id="dept" onChange="" tabindex="1">
  <option value="" selected>--</option>
  <?php 
//$r=@mysqli_query($con, "select distinct u.dept_acctcode,d.deptname from users_roletb u, account_depttb d where u.dept_acctcode=d.dept_acctcode and u.fileno='$login_id' order by u.dept_acctcode");$n=0;
$r=@mysqli_query($con, "select distinct * from  departmenttb  order by dept_name");
$n=0;

while($rl=@mysqli_fetch_array($r))
{
	++$n;
	$deptcode=@$rl['dept_code'];$deptname=@$rl['dept_name'];
	if($n==1){$acc=$deptcode;}
	echo "<option value='$deptcode'>$deptname</option>";
	
}

?>
  </select>
                </th>
              </tr>-->
			
			<tr>
<th colspan='1'>Starting Date<br /><input type="text" name="from" id="from" size="25" tabindex="3" class="easyui-datebox" />
</th><th>End Date<br /><input type="text" name="to" id="to" size="25" tabindex="3" class="easyui-datebox" />
</th>
                </tr>
                <tr>
			  <th colspan='1'>Select Account</th>
			  <th><select name="account" id="account" class="txt" style="width: 200px"  >
                  <option selected="selected" value="">---...---</option>
                                    <?php
								$r=@mysqli_query($con, "select distinct * from bank_accounttb where status='Active' order by acctname");
								while ($rcourse=@mysqli_fetch_array($r))
									{
										$scourse=@$rcourse['acctname'];
										$pcode=@$rcourse['acctcode'];
										$bank=@$rcourse['bankname'];
										$acctno=@$rcourse['acctno']!=''?$rcourse['acctno']." || ":'';
										$acctname=@$rcourse['acctname'];
										echo "<option value='$pcode'> $scourse || $acctno $bank :: $pcode</option>";
										
									}
								
								?>
                    <?php
                         
						 ?>
                </select></th>
			  </tr>
				
			   <!--<tr><th colspan="2">
			   			Folio Code<br />
				<select name="folio_code" id="folio_code" tabindex="8">
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
			   </tr>-->
				<tr><th colspan="2">
				<center>
				<table width="100%"><tr><td>
	
		<?php if ($report_type=='Cashbook'){ ?>
        <input type='radio' name='v_opt' id='v_opt7' tabindex="15" value='cashbook' checked>Cashbook Summary</input><br />
        <?php }elseif ($report_type=='Cashbook Treasury'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='cashbook_treasury' checked>Cashbook Summary Treasury<br />
        <?php }elseif ($report_type=='Cashbook NCOA'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='cashbook_treasury_ncoa' checked>Cashbook (NCOA)<br />
        <?php }elseif ($report_type=='General Ledger'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='ledger' checked>Final Ledger Summary<br />
	<?php }elseif ($report_type=='General Ledger Salary'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='ledger2' checked>Final Ledger Summary Salaries<br />
        <?php }elseif ($report_type=='Trial Balance'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='trialbalance' checked> Trial Balance <?php if($r_vals=="Final Account"){ ?>|| <label for="vs_opt6">Save Trial Balance<input type='checkbox' name='vs_opt6' id='vs_opt6' tabindex="14" value='savetrialbalance'></label> <?php } ?><br />
        <?php }elseif ($report_type=='Expenditure Analysis'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='ExpenditureAnalysis' checked> Expenditure Analysis <br />
        <?php }elseif ($report_type=='Summary Trial Balance Ledger'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='summarytrialbalanceledger' checked>Summary Trial Balance Ledger<br />
        <?php }elseif ($report_type=='Summary Trial Balance'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='summarytrialbalance' checked>Summary Trial Balance<br />
        <?php }elseif ($report_type=='Payment Voucher Report'){ ?>
		<input type='radio' name='v_opt' id='v_opt6' tabindex="14" value='voucher' checked>Payment Voucher Report<br />
        <p>
        PAYMENT TYPE: 
          <label>
            <input type="radio" name="paymentType" value="paid" id="paymentType_0">
            Paid</label>
           
          <label>
            <input type="radio" name="paymentType" value="npaid" id="paymentType_1">
            Not Paid</label>
           
          <label>
            <input type="radio" name="paymentType" value="all" id="paymentType_2">
            All</label>
          
        </p>
<?php } ?>
										 </td>
				</tr></table>
										</center>
				</th>
				</tr>
				<tr><th colspan="2">
				<input type="hidden" name="reporttype" id="reporttype" value="<?php echo $report_type; ?>">
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