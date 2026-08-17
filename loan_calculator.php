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
   <link rel="stylesheet" type="text/css" href="include/easyui.css">
   <link rel="stylesheet" type="text/css" href="include/icon.css">
	<link rel="stylesheet" type="text/css" href="include/demo.css">
    <link rel="stylesheet" href="css/tinybox.css" />
    <script type="text/javascript" src="include/jquery.min.js"></script>
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    <script src="datepicker/jquery-1.8.3.js"></script>
    <script src="datepicker/jquery-ui.js"></script>

<?php //include("required_jQuery_files.php");
include "function.php"; ?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script src="include/moment.js"></script>
<script>
$(function(){
		   $("#start_date").datepicker({dateFormat:"yy-mm-dd"});
		   $("#end_date").datepicker({dateFormat:"yy-mm-dd"});
		   }
		   );
	
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="30" height="30" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="30" height="30" alt="loading">').show();
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
  
 if(cv=='other_payment_section') //start
  {
	  var complex_action = v.split(':');	var v_2 = complex_action[0];	var fileno_2 = complex_action[1];	var folio_2 = complex_action[2];
			if(v_2 =='searchx'){
				//$("#fileno").val(fileno_2); 
				$("#folio").val(folio_2); v='search';
				//alert(folio_2+":"+v); exit;
			}

			if(v=='save')
			 {
				 if($("#fileno").val()=='' || $("#type").val()=='' || $("#start_date").val()=='' || $("#folio").val()=='' || isNaN($("#amount").val()) || $("#amount").val() <= 0)
				 {
					 //$("#end_date").val()=='' || 
					 alert("All compulsory fields must be filled before you can proceed");
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			
			if(v=='search')
			 {
				 if($("#fileno").val()=='' || $("#type").val()=='' || $("#folio").val()=='')
				 {
					 alert("You need to select File Number and Account Code from the list");
					 $(divid).html('').show();
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			 
			 if(v=='delete')
			 {
				 if($("#fileno").val()=='' || $("#type").val()=='' || $("#folio").val()=='' || $("#amount").val()=='')
				 {
					 alert("Please search the record you want to delete first before clicking on delete button");
					 $(divid).html('').show();
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			 
			 if(v=='view')
			 {
				 if($("#fileno").val()=='')
				 {
					 alert("Select the staff File Number");
					 $(divid).html('').show();
					 $('#roll').html('').show();
					 exit();
				 }
			 }

			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					$('#display2').html('').show();
					$("#fileno").val('');
					//$("#type").val('');
					$("#start_date").val('');
					$("#end_date").val('');
					$("#folio").val('');
					$("#pstatus").val('');
					$("#amount").val('');
					
					$('#d_amount').val('');
					$('#d_principal').val('');	$('#principal').val('');
					$('#d_installment').val('');	$('#installment').val(1);
					$('#d_sdate').val('');	$('#d_cumulative').val('');
					$('#d_edate').val('');
					$("#get_cumulative_per_item").html('');
					$('#roll').html('').show();
					exit();
				  }//end of refresh div i.e to refresh the data dispay previously on selection of another department
			
			//var mydata=JSON.stringify($('#frm').serializeObject());
			
			$.post(url,$("#frm").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a+"&istat="+b,function(data){
			
			if(v=='search')
			 {
				 //alert(data); exit;
				       var pData=jQuery.parseJSON(data); 
						var p=jQuery.parseJSON(pData.s_detail);
						var m=jQuery.parseJSON(pData.msg);
						//alert(pData.s_detail+" MSG:"+m); exit;
						if(m==0)
						 {
							$("#start_date").val('');
							$("#end_date").val('');
							$("#amount").val('');
							$("#installment").val(1); 
							$("#principal").val('');
							$("#pstatus").val('');

							$("#d_sdate").val('');
							$("#d_edate").val('');
							$("#d_amount").val('');
							$("#d_installment").val(''); 
							$("#d_principal").val('');
							$("#d_cumulative").val('');
							$("#get_cumulative_per_item").html('');
							alert('Error: The specified record does not exist');
						 }
						else
						 {
							$("#start_date").val(p.start_date); 
							$("#end_date").val(p.end_date);
							$("#amount").val(p.amount);
							$("#installment").val(p.installment); 
							$("#principal").val(p.principal);
							$("#pstatus").val(p.status);

							$("#d_sdate").val(p.start_date);
							$("#d_edate").val(p.end_date);
							$("#d_amount").val(p.amount);
							$("#d_installment").val(p.installment); 
							$("#d_principal").val(p.principal);
							
							swapcontent('get_cumulative_per_item', $('#fileno').val(), $('#folio').val() );
						 }
						 
						 //$(divid).html('').show();	
						 //$('#display2').html('').show();
						 $('#roll').html('').show();
						 exit();
			 }
			 
			$(divid).html('').show();	
			$('#display2').html(data).show();
			$('#roll').html('').show();
			});
			 
			if(v=='save' || v=='delete')
			    {
					  //swapcontent('other_payment_section','refresh');
					$("#fileno").val('');
					//$("#type").val('');
					$("#start_date").val('');
					$("#end_date").val('');
					$("#folio").val('');
					$("#amount").val('');
					$('#d_amount').val('');
					$('#d_principal').val(''); $('#principal').val('');
					$('#d_installment').val(''); $('#d_installment').val(1);
					$('#d_sdate').val(''); $('#pstatus').val('');
					$('#d_edate').val(''); $('#d_cumulative').val('');
					$("#get_cumulative_per_item").html('');
				}				  
			if(v=='edit')
				  {
					  //if($('#fileno').val()=='' && a!='auto')
					   //{ alert('Enter Staff File Number');$(divid).html('').show();exit;}
						//alert('here '+ cv + " "+ v + " id:"+a); $(divid).html('').show(); exit;
						//$.post(url,$("#staff_form").serialize()+"&contentvar="+cv+"&action="+v+"&id_val="+a,function(data){
										
						var pData=jQuery.parseJSON(data); 
						//alert(pData.s_detail);
						var p=jQuery.parseJSON(pData.s_detail);
						
						$("#sch_code").val(p.sch_code); $("#dept_code").val(p.dept_code); $("#dept_name").val(p.dept_name);
						$("#category").val(p.category); $("#r_id_edit").val(p.r_id); 
						
						//$(divid).html('').show();
					//	});
				  } //for edit purpose
			
  }
  if(cv=='get_cumulative_per_item'){
	  if($('#fileno').val() != '' && $('#folio').val() != ''){
		  $.post(url, {contentvar:a, fileno:b, folio_code:c}, function(data){
			  $(divid).html(data).show();
			  $("#roll").html('').show();
		  });
	  }
  }
} //end of swapcontent

Number.prototype.formatMoney = function(c, d, t){
var n = this, 
    c = isNaN(c = Math.abs(c)) ? 2 : c, 
    d = d == undefined ? "." : d, 
    t = t == undefined ? "," : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
 
/*function AddMonth(toAdd) {
	if (!toAdd || toAdd == '' || isNaN(toAdd)) return;
	var x = $('#installment').val(); //or whatever offset
	var sdate = $('#start_date').val().split('-');
	var yr = sdate[0];	var mnt = sdate[1];	var day = sdate[2];
	var st_date = day + "/" + mnt + "/" + yr;
	var CurrentDate = new Date(st_date);
	
	//For the very rare cases like the end of a month
	//eg. May 30th - 3 months will give you March instead of February
	
	var date = CurrentDate.getDate();
	CurrentDate.setDate(1);
	CurrentDate.setMonth(CurrentDate.getMonth() + x );
	//CurrentDate.setDate(date);
	
	var d = new Date();
	d.setDate(d.getDate() + parseInt(toAdd));
	
	//return d.getDate() + "/" + d.getMonth() + "/" + d.getFullYear();
	//document.getElementById("end_date").value = d.getDate() + "/" + d.getMonth() + "/" + d.getFullYear();
	document.getElementById("end_date").value = d.getFullYear() + "-" + d.getMonth() + "-" + d.getDate();
}
*/
$(document).ready(function(e) {
    $('#compute').click(function(e) {
        if($('#start_date').val()==''){
			alert('Start date requires a valid date entry!');
			exit;
		}
        if(!$.isNumeric($('#principal').val()) || $('#principal').val() < 0 ){
			alert('Principal amount must be numeric, greater than 0!');
			exit;
		}
        if(!$.isNumeric($('#installment').val()) || $('#installment').val() < 0 ){
			alert('Installment must be numeric, greater than 0!');
			exit;
		}
        if(!$.isNumeric($('#interest').val()) || $('#interest').val() < 0 ){
			alert('Interest rate must be numeric, greater than 0!');
			exit;
		}
		//REQUIRED BASIC VARIABLES
		var prin = $('#principal').val();	var inst = $('#installment').val();
		var rate = $('#interest').val();	
		if(inst > 0){	//COMPUTE END DATE OF DEDUCTION IF STARTDATE AND INSTALLMENT IS A VALID ENTRY
			var sdate = $('#start_date').val().split('-');
			var yr = sdate[0];	var mnt = sdate[1];	var day = sdate[2];
			var st_date = day + "/" + mnt + "/" + yr;
			var moment_date=moment($('#start_date').val());
			var edate = moment_date.add(inst, 'months');
			$('#end_date').val(edate.format('YYYY-MM-DD'));
		}
		
		var interest = (rate * prin) / 100.0;
		var loan = (interest * 1) + (prin * 1);
		var amount = loan / inst;
		
		//RETURN NECESSARY VALUES TO INPUT FIELDS
		$('#tamount').val(loan);//.formatMoney(2) );
		$('#t_amount').val(loan);//.formatMoney(2) );
		$('#amount').val(amount);//.formatMoney(2) );
		$('#d_amount').val(amount);//.formatMoney(2) );
		$('#d_principal').val(prin);
		$('#d_installment').val(inst);
		$('#d_sdate').val($('#start_date').val());
		$('#d_edate').val($('#end_date').val());
		alert('Done!');
    });
	
	$('#savebutton').click(function(e) {
		$('#d_amount').val( $('#amount').val() );
		$('#d_principal').val( $('#principal').val() );
		$('#d_installment').val( $('#installment').val() );
		$('#d_sdate').val( $('#start_date').val() );
		$('#d_edate').val( $('#end_date').val() );
    });
});
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
	        <h2>LOAN CALCULATION</h2>
                <p>&nbsp;</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
        <h3><i>Other Payments (Allowance/Deduction)</i></h3> -->
        <p>
        <form enctype="multipart/form-data" name="frm" id="frm">
		  <table width="70%" border="0">
		    <tr>
		      <th colspan="2" align="left" valign="top">File Number:<strong style="color:#F00">*</strong><br />
		        <select name="fileno" id="fileno" style="width:450px" onChange="if($('#fileno').val() != '' && $('#folio').val() != ''){ swapcontent('get_cumulative_per_item', $('#fileno').val(), $('#folio').val() );  } swapcontent('other_payment_section','view');">
		          <option selected="selected" value="">---</option>
		          <?php
                          $res_c=@mysqli_query($con, "select * from stafftb where status='Active' and fileno not in ('Admin','Weathstone') order by fileno"); //convert(fileno, decimal)");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $fileno=@$rs_c['fileno'];
							  $name=$rs_c['fileno']." || ".strtoupper($rs_c['surname']).", ".ucfirst(strtolower($rs_c['first_name']))." ".ucfirst(strtolower($rs_c['other_name']));
                              echo "<option value='$fileno'>$name</option>";
                           }
                          echo "</select>";
						 ?>
		          </select>
		        <br />
		        </th>
		      <th width="20%" rowspan="6" align="left" valign="top"></th>
		      </tr>
		    <tr>
		      <th width="20%" colspan="2" align="left" valign="top">Account Code:<strong style="color:#F00">*</strong><br />
		        <select name="folio" id="folio" style="width:450px" onChange="if($('#fileno').val() != '' && $('#folio').val() != ''){ swapcontent('get_cumulative_per_item', $('#fileno').val(), $('#folio').val() ); }">
		          <option selected="selected" value="">---</option>
		          <?php
                          //$res_c=@mysqli_query($con, "select * from salary_codetb where category='DEDUCTION' and status='Active' order by account_code");
						  $res_c=@mysqli_query($con, "select * from foliotb where title like '%loan%' and fundcenter not in ('01') order by folio_code");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $code=@$rs_c['folio_code'];
							  //$code=@$rs_c['account_code'];
							  $name=@$rs_c['title'];
                              echo "<option value='$code'>$code || $name</option>";
                           }
                          echo "</select>";
						 ?>
	            </select></th>
		      </tr>
		    <tr>
		      <th align="left" valign="top">Start Date:<strong style="color:#F00">*</strong> <br />
                <input name="start_date" type="text" id="start_date" size="25" style="width:170px" />
                <input type="hidden" id="d_sdate" name="d_sdate" /></th>
		      <th align="left" valign="top">End Date:<strong style="color:#F00">*</strong> <br />
		        <input name="end_date" type="text" id="end_date" size="25" style="width:170px" />
		        <input type="hidden" id="d_edate" name="d_edate" />
		        <input type="hidden" id="type" name="type" value="Deduction" /></th>
		      </tr>
		    <tr>
		      <th align="left" valign="top">Principal Amount:<br>
		        <input name="principal" type="text" id="principal" size="25" style="width:170px"/>
		        <input type="hidden" id="d_principal" name="d_principal" /></th>
		      <th align="left" valign="top">No of Installments:<br>
		        <input name="installment" type="text" id="installment" value="1" size="25" style="width:170px"/>
		        <input type="hidden" id="d_installment" name="d_installment" /></th>
		      </tr>

		    <tr>
		      <th align="left" valign="middle">Interest Rate:<strong style="color:#F00">*</strong><br />
                <input name="interest" type="text" id="interest" style="width:170px" size="25"/></th>
		      <th align="left" valign="top">&nbsp;<br><input type="button" name="compute" id="compute" value="Compute Value" class="easyui-button" style="width:170px"/></th>
		      </tr>

		    <tr>
		      <th align="left" valign="top">Monthly Installment Payable:<strong style="color:#F00">*</strong><br />
                <input name="amount" type="text" id="amount" style="width:170px" size="25" readonly/>
                <input type="hidden" id="d_amount" name="d_amount" /></th>
		      <th align="left" valign="middle">Total Loan Payable:<strong style="color:#F00">*</strong><br />
                <input name="tamount" type="text" id="tamount" style="width:170px" size="25" readonly/>
                <input type="hidden" id="t_amount" name="t_amount" /></th>
		      </tr>
		    <tr>
		      <!--<th>Status<br />
<select name="status" id="status" class="txt">
		        <option selected="selected">---</option>
		        <option value="Active">Active</option>
		        <option value="Inactive">Inactive</option>
</select></th> -->
		      <th colspan="2" align="left" valign="top"><div align="center"><br />
		        </div></th>
		      <th align="left" valign="top">&nbsp;</th>
		      </tr>
		    <tr>
		      <th colspan="2"><input type="button" name="savebutton" id="savebutton" value="Save / Update" class="btn" onclick="swapcontent('other_payment_section','save','');"/>
	          <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('other_payment_section','search','');"/>
              <input type="button" name="button4" id="button4" value="Delete" class="btn" onclick="if(confirm('Are you sure you want to perform this operation')) swapcontent('other_payment_section','delete','');"/>
	          <input type="button" name="button5" id="button5" value="View All" class="btn" onclick="swapcontent('other_payment_section','view');"/>
	          <input type="button" name="button3" id="button3" value="Refresh" class="btn" onclick="swapcontent('other_payment_section','refresh','');"/></th>
		      <th>&nbsp;</th>
	        </tr>
	      </table>
          Note: [<strong style="color:#F00">*</strong>] Mandatory fields!
        <div id="display"></div>
         <div id="display2"></div>
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