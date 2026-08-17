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
//	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_c.php";
	var str;

if(cv=='account_allocation_section') //start putme_login
  {
	//  alert(cv+" "+v+" "+a); //exit();accttype
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	if((v=='save') && ($('#dept_code').val()==''  || $('#account').val()=='') ){alert('Provide all The neccessary Fields');$(divid).html('').show();exit;}
	  var mydata = (JSON.stringify($('form').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login

    if(cv=='bank_account_section') //start putme_login
  {
	//  alert(cv+" "+v+" "+a); //exit();accttype
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	if((v=='save') && ($('#acctcode').val()==''  || $('#acctno').val()=='' || $('#acctname').val()==''  || $('#bankname').val()=='' || $('#accttype').val()=='' || $('#sortcode').val()=='') ){alert('Provide all The neccessary Fields');$(divid).html('').show();exit;}
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
	        <h2>Bank Account Setup</h2>
                <p>Manage Account Details for Dept., Centre and Units.</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
  <div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Bank Account Setup</i></h3> -->
			<p>
			<form action="#" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
			<div class="easyui-tabs" style="width:auto;height:auto"> <!-- begining of main tab -->
          
             <div title="Bank Account" style="padding:10px"> <!-- Bank Account tab  -->
            	<table>
				<tr>
				<th align="left">Folio Code:</th>
				<td><select name="acctcode" id="acctcode" tabindex="1">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct *  from foliotb where category like '%bank%' order by title,folio_code");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['title'];$pcode=@$rcourse['folio_code'];
							echo "<option value='$pcode'>$scourse <=> ($pcode)</option>";
							
						}
					
					?>
					</select>
				</td>
				</tr>
				<tr>
				<th align="left" nowrap="nowrap">Account Number:</th><td><input name="acctno" type="text" id="acctno" tabindex="2" value="<?php echo $code;?>" size="40"/></td>
				</tr>
				<tr>
				<th align="left">Account Name:</th><td><input name="acctname" type="text" id="acctname" tabindex="3" value="<?php echo $code;?>" size="40"/></td>
				</tr>
				<tr>
				<th align="left">Bank Name:</th><td><select name="bankname" id="bankname" tabindex="4">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
							echo "<option value='$pcode'>$scourse</option>";
							
						}
					
					?>
					</select></td>
				</tr>
				<tr>
				<th align="left">Account Type:</th><td>
				<select name="accttype" id="accttype" tabindex="5">
				<option selected="selected" value="">--</option>
				<option value="Current">Current</option>
				<option value="Savings">Savings</option>
				</select>
				</td>
				</tr>
				<tr>
				<th align="left">Sort Code:</th><td><input name="sortcode" type="text" id="sortcode" tabindex="6" value="<?php echo $code;?>" size="40"/></td>
				</tr>
				
				<tr><th colspan="2">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('bank_account_section','save');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('bank_account_section','search');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('bank_account_section','view_all');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('bank_account_section','refresh');" />
				<!--,$('#ccode').val()-->
				
				</th></tr>
				</table>
				</div><!-- end of Bank Account Tab-->
				<div title="Allocation of Bank Account to Centers" style="padding:10px"> <!-- Bank Account Allocation tab  -->
					<table>
						<tr>
							<th align="left" nowrap="nowrap">Department/Unit/Center:</th><td><select name="deptcode" id="deptcode" tabindex="1">
								<option selected="selected" value="">---</option>
								<?php
								$r=@mysqli_query($con, "select distinct *  from account_depttb order by deptname");
								while ($rcourse=@mysqli_fetch_array($r))
									{
										$scourse=@$rcourse['deptname'];$pcode=@$rcourse['dept_acctcode'];
										echo "<option value='$pcode'>$pcode || $scourse</option>";
										
									}
								
								?>
								</select></td>
							</tr>
						<tr>
							<th align="left" nowrap="nowrap">Account:</th><td><select name="account" id="account" tabindex="2">
								<option selected="selected" value="">---</option>
								<?php
								$r=@mysqli_query($con, "select distinct *  from bank_accounttb order by acctcode");
								while ($rcourse=@mysqli_fetch_array($r))
									{
										$scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
										$bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
										$acctname=@$rcourse['acctname'];
										echo "<option value='$pcode'>$bank || $acctno || $scourse <=> ($pcode)</option>";
										
									}
								
								?>
								</select></td>
							</tr>
							
							<tr><th colspan="2">
				<input type="button" class="btn" name="sbtn1" id="sbtn" value="Save" onclick="swapcontent('account_allocation_section','save');" />
				<input type="button" class="btn" name="chbtn1" id="chsbtn" value="Search" onclick="swapcontent('account_allocation_section','search');" />
				<input type="button" class="btn" name="chbtn1" id="chsbtn" value="View All" onclick="swapcontent('account_allocation_section','view_all');" />
				<input type="button" class="btn" name="chbtn1" id="chsbtn" value="Refresh" onclick="swapcontent('account_allocation_section','refresh');" />
				<!--,$('#ccode').val()-->
				
				</th></tr>
					</table>
				
				</div><!-- end of Bank Account Allocation Tab-->
				
				</div><!-- end of Tab-->
				<div id="display"></div>
				<div id="roll"></div>
                <div id="bank_account_section"></div>
                <div id="account_allocation_section"></div>
                
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