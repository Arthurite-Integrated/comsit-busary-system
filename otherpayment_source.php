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

	require_once "myclass_m.php";
	$cls = new myclass_m();
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
				 var folio = '';
				 if($("#folio").val()!='') folio=$("#folio").val();
				 else if($("#efolio").val()!='') folio=$("#efolio").val();
				 /*var amnt; var amntx='';
				 if(!isNaN($("#amount").val()) && $("#amount").val()!=''){
					 amnt=$("#amount").val().split(",");
					 amntx=amnt[0] + amnt[1]; 
				 }*/
				 
				 //alert($("#fileno").val() + "||" + $("#type").val() + "||" + $("#start_date").val() + "||" + $("#end_date").val() + "||" + $("#amount").val() + "||" +  folio + "||" + $("#pstatus").val()); exit; 
				 //if($("#fileno").val()=='' || $("#type").val()=='' || $("#start_date").val()=='' || $("#end_date").val()==''  || isNaN($("#amount").val()) || $("#amount").val() <= 0 || ( $("#folio").val()=='' && $("#efolio").val()=='' ) || ( $("#pstatus").val() == '' || $("#pstatus").val() == null ) )
				 if($("#fileno").val()=='' || $("#type").val()=='' || $("#start_date").val()=='' || $("#end_date").val()==''  || isNaN($("#amount").val()) || ( $("#folio").val()=='' && $("#efolio").val()=='' ) || $("#pstatus").val() == '' )
				 {
					 alert("All compulsory fields must be filled before you can proceed!");
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			 
			if(v=='search')
			 {
				 if($("#fileno").val()=='')// || $("#type").val()=='' || $("#folio").val()=='')
				 {
					 alert("Please select File Number");//, Payment Type and Folio from the list");
					 $(divid).html('').show();
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			 
			 if(v=='delete')
			 {
				 if(a==''){
					 if($("#fileno").val()=='' || $("#type").val()=='' || ( $("#folio").val()=='' && $("#efolio").val()=='' ) || $("#amount").val()=='')
					 {
						 alert("Please search the record you want to delete first before clicking on delete button");
						 $(divid).html('').show();
						 $('#roll').html('').show();
						 exit();
					 }
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
					//$('#display2').html('').show();
					$("#fileno").val('');
					//$("#type").val('');
					$("#start_date").val('');
					$("#end_date").val('');
					$("#folio").val('');
					$("#efolio").val('');
					$("#pstatus").val('');
					$("#amount").val('');
					
					$('#d_amount').val('');
					$('#d_principal').val('');	$('#principal').val('');
					$('#d_installment').val('');	$('#installment').val(1);
					$('#d_sdate').val('');	$('#d_cumulative').val('');
					$('#d_edate').val('');
					$("#get_cumulative_per_item").html('');
					//$('#roll').html('').show();
					$("#set_amt_div").html('');
					exit();
				  }//end of refresh div i.e to refresh the data dispay previously on selection of another department
				  
			//var mydata=JSON.stringify($('#frm').serializeObject());
			
			$.post(url, $("#frm").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a+"&istat="+b,function(data){
				if(v=='search')
				 {
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

                  	$('#new_code').show(); 
                    $('#existing_code').hide(); 
                    $('#efolio').val(''); 
                    $('#folio').val(''); 
                    swapcontent('set_amt_div', $('#folio').val(), 'newdefinition');
						
						$('#folio').val(p.folio_code); 
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
			 
			if(v=='save')// || v=='delete')
			    {
					  //swapcontent('other_payment_section','refresh');
					//$("#fileno").val('');
					//$("#type").val('');
					$("#start_date").val('');
					$("#end_date").val('');
					$("#folio").val('');
					$("#efolio").val('');
					$("#amount").val('');
					$("#set_amt_div").html('');

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
			$('#roll').html('').show();
  } 
  
  if(cv == 'set_amt_div'){
		$.post(url, {contentvar:cv, folio:v, action:a}, function (data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
  }
  
  if(cv=="filter_allowances"){
	$.post(url, {contentvar:cv, fileno:v}, function (data){
		$(divid).html(data).show();
		$("#roll").html('').show();
	});
  }
  if(cv=='get_cumulative_per_item'){
	  if($('#fileno').val() != '' && ($('#folio').val() != '' || $('#efolio').val() != '') ){
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
   //return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };

$(document).ready(function(jq){
	$('#existing_code').hide();
	//alert(jq.detail.toString() + "ddd" + jq.data.toString());

    $('#compute').click(function(e) {
        if($('#start_date').val()==''){
			alert('Start date requires a valid date entry!');
			exit;
		}
        if(!$.isNumeric($('#principal').val()) || $('#principal').val() < 0 ){
			alert('Principal amount must be numeric, greater than 0!');
			exit;
		}
        if(!$.isNumeric($('#installment').val()) || $('#installment').val() <= 0 ){
			alert('Installment must be numeric, greater than 0!');
			exit;
		}
		//REQUIRED BASIC VARIABLES
		var prin = $('#principal').val();	var inst = $('#installment').val();	var amount = prin / inst;
		
		if(inst > 0){	//COMPUTE END DATE OF DEDUCTION IF STARTDATE AND INSTALLMENT IS A VALID ENTRY
			var sdate = $('#start_date').val().split('-');
			var yr = sdate[0];	var mnt = sdate[1];	var day = sdate[2];
			var st_date = day + "/" + mnt + "/" + yr;
			var moment_date=moment($('#start_date').val());
			var edate = moment_date.add(inst, 'months');
			$('#end_date').val(edate.format('YYYY-MM-DD'));
		}
		
		//RETURN NECESSARY VALUES TO INPUT FIELDS
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
 <style>
 input[type="radio"] {
    -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
    -moz-appearance: checkbox;    /* Firefox */
    -ms-appearance: checkbox;     /* not currently supported */
	appearance: checkbox;
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
	        <h2>Allowance Definition (Individual)</h2>
                <p>Define allowance for a staff.</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
        <h3><i>Allowance Definition (Individual)</i></h3> -->
        <p>
        <form enctype="multipart/form-data" name="frm" id="frm">
		  <table width="80%" border="0">
		    <tr>
		      <th colspan="2" align="left" valign="top">File Number:<strong style="color:#F00">*</strong><br />
		        <select name="fileno" id="fileno" style="width:450px" onChange="
                swapcontent('other_payment_section','view','',''); 
                swapcontent('filter_allowances', $('#fileno').val()); 
                
                if($('#fileno').val() != '' && $('#folio').val() != ''){ 
                swapcontent('get_cumulative_per_item', $('#fileno').val(), $('#folio').val() ); 
				}
                
                if($('#fileno').val() != '' && $('#efolio').val() != ''){ 
                swapcontent('get_cumulative_per_item', $('#fileno').val(), $('#efolio').val() ); 
                }">
		          <option selected="selected" value="">---</option>
		          <?php
                          $res_c=@mysqli_query($con, "select * from stafftb where status='Active' and fileno not in ('Admin','Weathstone') order by fileno"); //convert(fileno,decimal)");
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
		      </tr>
		    <tr id="code_selector">
		      <td colspan="2" align="left" valign="top" nowrap>
		        <label>
		          <input type="radio" name="predef" value="pre" id="predef_0" 
                  onclick="
                  	$('#new_code').hide(); 
                    $('#existing_code').show(); 
                    $('#efolio').val(''); 
                    $('#folio').val(''); 
                    swapcontent('set_amt_div', $('#efolio').val(), 'predefine');
                  " style="appearance:checkbox;">
		          Use Pre-defined Allowances</label> 
		        &nbsp;&nbsp;
		        <label>
		          <input type="radio" name="predef" value="new" id="predef_1" 
                  onclick="
                  	$('#new_code').show(); 
                    $('#existing_code').hide(); 
                    $('#efolio').val(''); 
                    $('#folio').val(''); 
                    swapcontent('set_amt_div', $('#folio').val(), 'newdefinition');
                  " style="appearance:checkbox;">
		          New definition</label>
		        </td>
		      <!--<th width="60%" align="left" valign="top">&nbsp;</th>-->
	        </tr>
		    <tr id="new_code">
		      <th colspan="2" align="left" valign="top">Account Code:<strong style="color:#F00">*</strong><br />
		        <select name="folio" id="folio" class="txt" style="width:450px" onChange="if($('#fileno').val() != '' && $('#folio').val() != ''){ swapcontent('get_cumulative_per_item', $('#fileno').val(), $('#folio').val() ); }">
		          <option selected="selected" value="">---</option>
		          <?php
					  //$res_c=@mysqli_query($con, "select * from salary_codetb where category='ALLOWANCE' and status='Active' order by account_code");
					  $res_c=@mysqli_query($con, "select * from foliotb where not fundcenter in ('02') order by folio_code");
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
		    <tr id="existing_code">
		      <th colspan="2" align="left" valign="top">Pre-defined Allowances:<strong style="color:#F00">*</strong><br /><div id="filter_allowances">
		        <select name="efolio" id="efolio" class="txt" style="width:450px" onChange="
                swapcontent('set_amt_div', $('#efolio').val(), 'predefine'); 
                if($('#fileno').val() != '' && $('#efolio').val() != ''){ 
                    swapcontent('get_cumulative_per_item', $('#fileno').val(), $('#efolio').val() ); 
                }">
		          <option selected="selected" value="">---</option>
		          <?php
					  //$res_c=@mysqli_query($con, "select * from salary_codetb where category='ALLOWANCE' and status='Active' order by account_code");
					  $res_c=@mysqli_query($con, "select * from allowancestb order by id");
					  while($rs_c=@mysqli_fetch_array($res_c))
					   {
						  $al_id=@$rs_c['id'];
						  $d_val=explode("***", $cls->get_allowance_defined($al_id) );
						  $code=@$rs_c['folio_code'];	$fval=@$rs_c['field_value'];
						  $name=@get_folio_name($rs_c['folio_code']);
						  echo "<option value='$code***$al_id'>$code | $name | For:".$d_val[1]." | Amount:".$d_val[4]."</option>";
					   }
					  echo "</select>";
					 ?>
	            </select></div></th>
		      </tr>
		    <tr>
		      <th width="50%" align="left" valign="top">Start Date:<strong style="color:#F00">*</strong> <br />
                <input type="text" name="start_date" id="start_date" style="width:170px" />
                <input type="hidden" id="d_sdate" name="d_sdate" /></th>
		      <th width="50%" align="left" valign="top">End Date:<strong style="color:#F00">*</strong> <br />
		        <input type="text" name="end_date" id="end_date" style="width:170px" />
		        <input type="hidden" id="d_edate" name="d_edate" />
		        <input type="hidden" id="type" name="type" value="Allowance" /></th>
		      </tr>
		    <tr>
		      <th width="50%" align="left" valign="top">Principal Amount:<br>
		        <input name="principal" type="text" id="principal" size="25" style="width:170px"/>
		        <input type="hidden" id="d_principal" name="d_principal" /></th>
		      <th width="50%" align="left" valign="top">No of Installments:<br>
		        <input name="installment" type="text" id="installment" value="1" size="25" style="width:170px"/>
		        <input type="hidden" id="d_installment" name="d_installment" /></th>
		      </tr>
		    <tr>
		      <th align="left" valign="top">Monthly Installment:<strong style="color:#F00">*</strong><br />
              <div id="set_amt_div" style="width:172px; height:20px; border:groove 1px #999999; padding:2px">
		        <input name="amount" type="text" id="amount" style="width:168px; border:0" size="25" readonly />
		        <input type="hidden" id="d_amount" name="d_amount" /></div></th>
		      <th align="left" valign="middle"><input type="button" name="compute" id="compute" value="Compute Value" class="easyui-button" style="width:170px"/></th>
		      </tr>
		    <tr>
		      <th align="left" valign="top"> Cumulative Todate:<br>
		        <div align="center" id="get_cumulative_per_item" style="width:172px; padding:2px; height:20px; text-align:left; border:groove 1px #999999;"><br />
		          </div>
		        <input type="hidden" id="d_cumulative" name="d_cumulative" /></th>
		      <th align="left" valign="top">Status:<br>
		        <select name="pstatus" id="pstatus" size="4" style="width:170px">
		          <option value="" selected>Select Status...</option>
		          <option value="Constant">Constant</option>
		          <option value="Active">Active</option>
		          <option value="Suspend">Suspend</option>
		          </select></th>
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
		      </tr>
		    <tr>
		      <th colspan="2"><input type="button" name="button" id="button" value="Save / Update" class="btn" onclick="swapcontent('other_payment_section','save','','');"/>
		        <!--<input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('other_payment_section','search','');"/>
              <input type="button" name="button4" id="button4" value="Delete" class="btn" onclick="if(confirm('Are you sure you want to perform this operation')) swapcontent('other_payment_section','delete','');"/>-->
		        <input type="button" name="button5" id="button5" value="View All" class="btn" onclick="swapcontent('other_payment_section','view','','');"/>
		        <input type="button" name="button3" id="button3" value="Refresh" class="btn" onclick="swapcontent('other_payment_section','refresh','','');"/></th>
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