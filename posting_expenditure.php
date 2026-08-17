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

function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	//$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
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
	        <h2>Heading of The page</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
                <h3><i>Expenditure Posting</i></h3>
			<p>
			<form action="#" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
			<center>
			<table>
              <tr>
                <th>Department/Unit/Center/School<br />
                <select name="dept" id="dept" onChange="javascript:swapcontent('account',this.value);" tabindex="1">
<option value="" selected>--</option>
<?php 
$r=@mysqli_query($con, "select distinct dept_acctcode,deptname from  account_depttb  order by dept_acctcode");$n=0;

while($rl=@mysqli_fetch_array($r))
{
	++$n;
	$deptcode=@$rl['dept_acctcode'];$deptname=@$rl['deptname'];
	if($n==1){$acc=$deptcode;}
	echo "<option value='$deptcode'>$deptname</option>";
	
}

?>
</select>
                </th>
                <th>Account<br /><span id="account"><select name="acctcode" id="acctcode" tabindex="2">
<option value="" selected>--</option>

</select></span></th>
</tr><tr>
<th colspan='1'>Date<br /><input type="text" name="transdate" id="transdate" size="25" tabindex="3" class="easyui-datebox"  data-options="formatter:myformatter,parser:myparser"  required="required" />
</th><th>File Number<br />
<select name="fileno" id="fileno" tabindex="4">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct *  from stafftb where fileno not like '%weathstone%' order by convert(fileno,decimal)");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['surname'].' '.$rcourse['first_name'].' '.$rcourse['other_name'].' ('.$rcourse['title'].')';$pcode=@$rcourse['fileno'];
							echo "<option value='$pcode'>$pcode || $scourse</option>";
							
						}
					
					?>
					</select>
</th>
                </tr>
				<tr>
				<th>Folio Code<br />
				<select name="folio_code" id="folio_code" tabindex="5">
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
				<th>Amount (=N=)<br />
				<input type='text' name='amount' class='amt' id='amount' value='' size='20'  style='background-color: #FEFFB0;font-weight: bold;text-align: right;' tabindex='6'>
				</th>
				
				</tr>
				<tr>
				<th colspan="2">Remark<br />
				  <textarea name="comment" cols="55" rows="3" id="comment" tabindex="7" ></textarea>				</th>
				</tr>
             </table>
			</center>
			<div class="easyui-tabs" style="width:auto;height:auto"> <!-- begining of main tab -->
          <!--
             <div title="Revenue" style="padding:10px"> <!-- Bank Account tab  --\>
			 <center>
            	<table>
				<tr>
				<th>Receipt Number</th><td><input name="receiptno" type="text" id="receiptno" tabindex="8" value="<?php echo $code;?>" size="40"/></td>
				</tr>
				
				
				<tr><th colspan="2">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" tabindex="9" onclick="swapcontent('posting_section','save','Debit');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('posting_section','search','Debit');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('posting_section','view_all','Debit');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('posting_section','refresh','Debit');" />
				<!--,$('#ccode').val()--\>
				
				</th></tr>
				</table></center>
				</div><!-- end of Bank Account Tab--\>
				-->
				<div title="Expenditure" style="padding:10px"> <!-- Bank Account Allocation tab  -->
					<center><table>
						<tr>
							<th>Payee</th><td><input name="payee" type="text" id="payee" tabindex="10" value="<?php echo $code;?>" size="40"/></td>
						</tr>
						<tr>
							<th>PV Number</th><td><input name="pvno" type="text" id="pvno" tabindex="11" value="<?php echo $code;?>" size="40"/></td>
						</tr>
						<tr>
							<th>Cheque Number</th><td><input name="chequeno" type="text" id="chequeno" tabindex="12" value="<?php echo $code;?>" size="40"/></td>
						</tr>
							<tr><th colspan="2">
				<input type="button" class="btn" name="sbtn1" id="sbtn" value="Save" onclick="swapcontent('posting_section','save','Credit');" />
				<input type="button" class="btn" name="chbtn1" id="chsbtn" value="Search" onclick="swapcontent('posting_section','search','Credit');" />
				<input type="button" class="btn" name="chbtn1" id="chsbtn" value="View All" onclick="swapcontent('posting_section','view_all','Credit');" />
				<input type="button" class="btn" name="chbtn1" id="chsbtn" value="Refresh" onclick="swapcontent('posting_section','refresh','Credit');" />
				<!--,$('#ccode').val()-->
				
				</th></tr>
					</table>
				</center>
				</div><!-- end of Bank Account Allocation Tab-->
				
				</div><!-- end of Tab-->
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