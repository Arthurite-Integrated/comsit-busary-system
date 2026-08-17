<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
	$memo_id=@base64_decode($_REQUEST['id']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@$_SESSION['role'];
 $staff_category=@$_SESSION['staff_category'];


?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
 <link rel="stylesheet" type="text/css" href="include/easyui.css">
   <link rel="stylesheet" type="text/css" href="include/icon.css">
	<link rel="stylesheet" type="text/css" href="include/demo.css">
	<link rel="stylesheet" type="text/css" href="include/colorbox.css">
    <link rel="stylesheet" href="css/tinybox.css" />
     <script type="text/javascript" src="include/jquery.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>
    
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
	<script type="text/javascript" src="include/jquery.colorbox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    
	<script src="datepicker/jquery-1.8.3.js"></script>
    <script src="datepicker/jquery-ui.js"></script>
	
	<script src="datepicker/datepicker/ui.datepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="datepicker/datepicker/ui.datepicker.css">
	<script type="text/javascript" src="include/datagrid-groupview.js"></script>
	<script type="text/javascript" src="include/accounting.js"></script>
	
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php //include("required_jQuery_files.php");
require_once "function.php";
?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />

<script>
$(function(){
		   $("#pay_date").datepicker({dateFormat:"yy-mm-dd"});
		   
		   $(".date").datepicker({dateFormat:"yy-mm-dd"});
		   
		   $("#folio11").easyui-combogrid({change:function(){alert('yessooo');}});
		  
		   
		   }
		   );

function sum(){
    //iterate through each textboxes and add keyup
        //handler to trigger sum event
        $(".amt").each(function() {
 
            //$(this).keyup(function(){
                calculateSum();
            //});
        });
  }
  function calculateSum() {
 
        var sum = 0, totalamt=0;
		var vamount=parseFloat($("#vamount").val());
        //iterate through each textboxes and add the values
        $(".amt").each(function() {
 
            //add only if the value is number
            if(!isNaN(this.value) && this.value.length!=0) {
                sum += parseFloat(this.value);
            }
 
        });
        //.toFixed() method will roundoff the final sum to 2 decimal places
		totalamt= vamount - sum;
        $("#total").html(totalamt.toFixed(2));
		$("#total_deduction").html('('+sum.toFixed(2)+')');
    }
function sum2(clv){
    //iterate through each textboxes and add keyup
        //handler to trigger sum event
		var classid="."+clv;
        $(classid).each(function() {
 
            //$(this).keyup(function(){
              calculateSum2(clv);
            //});
        });
  }
 function calculateSum2(clv) {
 		var classid="."+clv;
        var sum = 0, totalamt=0;
		var vamount=parseFloat($("#vamount").val());
        //iterate through each textboxes and add the values
        $(classid).each(function() {
 
            //add only if the value is number
            if(!isNaN(this.value) && this.value.length!=0) {
                sum += parseFloat(this.value);
            }
 
        });
        //.toFixed() method will roundoff the final sum to 2 decimal places
		//totalamt= vamount - sum;
		totalamt= sum;
		$("#vamount").val(sum);
      // $("#total").html(totalamt.toFixed(2));
	//	$("#total_deduction").html('('+sum.toFixed(2)+')');
    } 
function do_total(ctl,v,sn)
 {
	 //alert(ctl+" V:"+v);
	 if($("#vamount").val()=='')
	  {
		  alert("Error: Payment amount has not been entered");
		  document.getElementById('code'+sn).checked=false;
		  exit();
	  }
	  
	 var v_set=""; var folio_c=""; var rate_v=0;
	 v_set = v.split("***");
	 folio_c = v_set[0]; rate_v=parseFloat(v_set[1]);
	 //alert(ctl+" "+v+" SN:"+sn);
	 //alert("CONTROL:"+ ctl+" FOLIO CODE:"+folio_c+" Rate:"+rate_v+" CODE1 : "+document.getElementById(ctl).value);
	 if(document.getElementById('code'+sn).checked==true)
	  { document.getElementById(ctl).value=rate_v/100 * parseFloat($("#vamount").val());
	    $("#amount2"+sn).html('('+document.getElementById(ctl).value+')').show();
	  }
	 else
	   { 
	     document.getElementById(ctl).value="";
		 $("#amount2"+sn).html('').show();
	   }
	 
	 sum();
 }

function display_total()
{
	var amt=$("#vamount").val();
	$("#total").html(amt).show();
}
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
   if(cv=='refresh') //start unit
  {
  	$('#pay_date').val('');
	$('#pvno').val('');
	$("#generate_pvno").html(" <input name='pvno' type='text' id='pvno' size='25'>").show();
	$("#roll").html('').show();
	exit();
  }
  if(cv=='generate_pvno') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var pay_date=$("#pay_date").val(); 
	  var vunit=$("#voucher_unit").val();
	  if(vunit=="")
	  	{
			alert('Select the Voucher Unit first');
			$("#pay_date").val('');
			$("#roll").html('').show();
			exit();
		}
		$.post(url,{contentvar:cv,pay_date:pay_date,voucher_unit:vunit},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit
  
  if(cv=='load_voucher_fileno') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  //var pay_date=$("#pay_date").val();
		$.post(url,{contentvar:cv,type:v},function(data){
		$("#roll").html('').show();
		$("#name").val('');
		$("#act_no").val('');
		$("#bank").val('');
		$("#address").val('');		
		$("#load_voucher_fileno").html(data).show();
		
		});
  }//end of unit
  
  if(cv=='load_voucher_details_entry') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  //var pay_date=$("#pay_date").val();
		$.post(url,{contentvar:cv,type:v},function(data){
		$("#roll").html('').show();
		$("#display").html(data).show();
		
		});
  }//end of unit  
  
  if(cv=='load_unit') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var dept=$("#dept").val();
		$.post(url,{contentvar:cv,dept_code:dept},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit
  
 if(cv=='load_payee_details') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert('Payee details');
	       $.post(url,$("form").serialize()+"&contentvar="+cv,function(data){	//a is the id of rec to edit/upd
			//var pData=jQuery.parseJSON(data); 
			$("#roll").html('').show();
			
			if($("#type").val()=='Internal')
			 {
				 v_set = data.split("***");
				 $("#name").val(v_set[0]);
				 $("#act_no").val(v_set[1]);
			     $("#bank").val(v_set[2]);
				 //folio_c = v_set[0]; rate_v=parseFloat(v_set[1]);
				 //alert(data+' NAME:'+v_set[0]+' ACT NO:'+v_set[1]+' BANK:'+v_set[2]);
			 }
			
			});
  }//end of putme_login
 
  if(cv=='folio_code_breakdown') //start load budget
  {
	 // alert(cv+" "+v+" "+a);exit();
	  //alert('Payee details');
	 // if($("#pay_date").val()=='')
	  //  { alert("Please select payment data"); $("#folio").val(''); $("#roll").html('').show(); $(divid).html('').show(); exit();}
		
	       $.post(url,$("form").serialize()+"&contentvar="+cv,function(data){	//a is the id of rec to edit/upd
			//var pData=jQuery.parseJSON(data); 
			$("#roll").html('').show();
			$(divid).html(data).show();
			
			});
  }//end of load budget
 if(cv=='load_budget') //start load budget
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert('Payee details');
	  if($("#pay_date").val()=='')
	    { alert("Please select payment data"); $("#folio").val(''); $("#roll").html('').show(); $(divid).html('').show(); exit();}
		
	       $.post(url,$("form").serialize()+"&contentvar="+cv,function(data){	//a is the id of rec to edit/upd
			//var pData=jQuery.parseJSON(data); 
			$("#roll").html('').show();
			$(divid).html(data).show();
			
			});
  }//end of load budget
  
 if(cv=='search_voucher')
 {
	 //search for voucher given voucher pvno
	 
	var pvno=prompt("Enter the System Processing Number of the voucher");
	if(pvno=='')
	  { alert('No Voucher System Processing Number provided'); exit();}
			  
	$.post(url,{contentvar:cv,pvno:pvno},function(data){	
    //TINY.box.show(data,0,0,0,0);
	//document.location=data;
	window.open(data,'Voucher','width=750,height=auto');
	$(divid).html('').show();
    $('#roll').html('').show();
	});		
 } //end of search voucher
 
 if(cv=='search_voucher_by_pvno')
 {
	 //search for voucher given voucher pvno
	 
	var pvno=prompt("Enter the PV. Number of the voucher");
	
	if(pvno=='')
	  { alert('No Voucher PV. Number provided'); exit();}
			  
	$.post(url,{contentvar:cv,pvno:pvno},function(data){	
    //TINY.box.show(data,0,0,0,0);
	//document.location=data;
	window.open(data,'Voucher','width=750,height=auto');
	$(divid).html('').show();
    $('#roll').html('').show();
	});		
 } //end of search voucher
 
 if(cv=='loan_section') //start putme_login
  {
  
 //alert($("form").serialize());
  
 //alert(cv+" "+v+" "+a); exit;
  	// var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			//alert($("#pro_typ").val()); exit;
			//$.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a},function(data){	//a is the id of rec to edit/upd	
			//alert(data);	
			if(v=='save')
			 {
                /*if($('#type option:selected').val() == 'External' && $("#bank").val()==''){
					 	alert("All compulsory fields must be filled before you can proceed");
					 	$('#roll').html('').show();
					 	exit();
                     }
                
				 if($("#pay_date").val()=='' || $("#dept").val()=='' || $("#pvno").val()=='' || $("#account").val()=='' || $("#folio").val()=='' || $("#type").val()=='' || $("#desc").val()=='' || $("#vamount").val()=='' || $("#pro_typ").val()=='')
				 {
					 alert("All compulsory fields must be filled before you can proceed");
					 $('#roll').html('').show();
					 exit();
				 }*/
			 }
			
			$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
			$(divid).html('').show();	
			$('#display2').html(data).show();
			$('#roll').html('').show();
			
			 /*
			if(v=='save')
			    {
					  //swapcontent('loan_section_entry','refresh');
					$("#pay_date").val(''); $("#dept").val(''); $("#pvno").val(''); $("#account").val('');
					$("#folio").val(''); $("#type").val(''); $("#fileno").val(''); $("#name").val('');$("#act_no").val('');
					$("#bank").val(''); $("#address").val(''); $("#payee_tin_number").val(''); $("#payee_sort_code").val('');
					  
				}
			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					//$('#display2').html('').show();
					$('#load_voucher_fileno').html('').show();
					$('#generate_pvno').html('').show();
					$('#load_budget').html('').show();
					$('#roll').html('').show();
					$("#pay_date").val(''); $("#dept").val(''); $("#pvno").val(''); $("#account").val('');
					$("#folio").val(''); $("#type").val(''); $("#fileno").val(''); $("#name").val('');$("#act_no").val('');
					$("#bank").val(''); $("#address").val(''); $("#payee_tin_number").val(''); $("#payee_sort_code").val('');
					//exit();
				  }//end of refresh div i.e to refresh the data dispay previously on selection of another department
				  */
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

  if(cv=='load_dept_account') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var category=$("#funddept_head").val();
		$.post(url,{contentvar:cv,category:category},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit


  if(cv=='load_category') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var fundcenter=$("#fundsource").val();
		$.post(url,{contentvar:cv,fundcenter:fundcenter},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit

  if(cv=='load_items_code') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
      
		$.post(url,{contentvar:cv,fundcenter:fundcenter, deptcode:deptcode, category:category},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit

} //end of swapcontent


 </script>

<script>


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

function getDateDiff(time1, time2) {
var today = new Date(time2);
var form_date=new Date(time1)
var difference=form_date>today ? form_date-today : today-form_date
var diff_days=Math.floor(difference/(1000*3600*24));
  //alert(diffD);
}

function compute_loan(){
	//var d1=new Date($('#start_date').val());
	//var d2=new Date($('#end_date').val());
	
	//alert( getDateDiff(document.getElementById("start_date").value, document.getElementById("end_date").value) );
		//alert( d1 - d2 );
    
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
		var prin = $('#principal').val();	
		var inst = $('#installment').val();
		var rate = $('#interest').val();	
		/*if(inst > 0){	//COMPUTE END DATE OF DEDUCTION IF STARTDATE AND INSTALLMENT IS A VALID ENTRY
			var sdate = $('#start_date').val().split('-');
			var yr = sdate[0];	
			var mnt = sdate[1];	var day = sdate[2];
			var st_date = day + "/" + mnt + "/" + yr;
			var moment_date=moment($('#start_date').val());
			var edate = moment_date.add(inst, 'months');
			$('#end_date').val(edate.format('YYYY-MM-DD'));
		}*/
//var d1 = new Date($('#start_date').val());
//var d2 = new Date($('#end_date').val());
		
		//var diff = new Date(date2.getTime() - date1.getTime());
		
		var interest = (rate * prin) / 100.0;
		var loan = (interest * 1) + (prin * 1);
		var amount = loan / inst;
		
		//RETURN NECESSARY VALUES TO INPUT FIELDS
		$('#cinterest').val(interest);//.formatMoney(2) );
		$('#amt_approved').val(loan);//.formatMoney(2) );
		$('#vamount').val(loan);//.formatMoney(2) );
		$('#tamount').val(loan);//.formatMoney(2) );
		$('#t_amount').val(loan);//.formatMoney(2) );
		$('#amount').val(amount);//.formatMoney(2) );
		$('#d_amount').val(amount);//.formatMoney(2) );
		$('#d_principal').val(prin);
		$('#d_installment').val(inst);
		$('#d_sdate').val($('#start_date').val());
		$('#d_edate').val($('#end_date').val());

//alert(loan);
}
 
function calcDate() {
		var date1 = new Date($('#end_date').val()); //new Date()
		var date2 = new Date($('#start_date').val());; //new Date(2010,05,01) 
		//a = calcDate(today, past);

    var diff = Math.floor(date1.getTime() - date2.getTime());
    var day = 1000 * 60 * 60 * 24;

    var days = Math.floor(diff/day);
    var months = Math.floor(days/31);
    var years = Math.floor(months/12);

    var message = date2.toDateString();
    message += " was "
    message += days + " days " 
    message += months + " months "
    message += years + " years ago \n"

		$('#installment').val(months);
		$('#d_installment').val(months);
    //return message
    }

$(document).ready(function(e) {
	 $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
     $('#ppvno').hide();

});

function reload_folio(){
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
	$('#folio').combogrid('loangrid').datagrid('load', {category:category, fundcenter:fundcenter, deptcode:deptcode});
}
</script> 
</head>
<body class="subpage" onLoad="swapcontent('load_voucher_fileno', 'Internal'); $('#ptin_row').hide(); ">

<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
	<?php include_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>Loan Application</h2>
                <p>&nbsp;</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
              <div class="easyui-panel" title="New Loan Entry" style="width:800px">     
			  	<div style="padding:10px 60px 20px 60px">             
               <form enctype="multipart/form-data">
		  <table border="0" cellpadding="0" cellspacing="0" class="vch" width="100%">
			<tr>
			  <th height="50" align="left" bgcolor="#F1F1F1">Entry Unit:<br />
			<!--<input name="memo_id" type="hidden" id="memo_id" value="<?php echo $memo_id;?>" size="25" />-->
			<input name="amt_approved" type="hidden" id="amt_approved" value="<?php echo $amount_approved;?>" size="25" />
            <input name="voucher_unit" type="hidden" id="voucher_unit" value="11" size="25" />
            Salary, Loans, Advances and Procurement
			</th>
			  <th height="50" bgcolor="#F1F1F1"></th>
			  <th height="50" align="left" bgcolor="#F1F1F1">Voucher Date:
              <br />	            <input type="text" name="pay_date" style="width: 300px" class="date" id="pay_date" onchange="swapcontent('generate_pvno',this.value);"/></th></tr>
		    <tr>
		      <th height="50" align="left" bgcolor="#F1F1F1" >
              <div align="left">Account to be Debited:<br />
                <select name="account" id="account" class="txt" style="width: 300px"  >
                  <option selected="selected">---</option>
                  <?php
								$r=@mysqli_query($con, "select distinct *  from bank_accounttb where status='Active' order by acctcode");
								while ($rcourse=@mysqli_fetch_array($r))
									{
										$scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
										$bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
										$acctname=@$rcourse['acctname'];
										echo "<option value='$pcode'>$bank || $acctno || $scourse <=> ($pcode)</option>";
										
									}
								
								?>
                </select>
		      </div></th>
		      <th height="50" align="right" valign="middle" bgcolor="#F1F1F1" >&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1" >System Processing No.:<br />
<span id="generate_pvno"><input name="pvno" type="text" style="width: 300px" id="pvno" size="25" /></span></th>
	        </tr>
		    <tr bgcolor="#6BF4C7">
		      <th height="50" colspan="3" align="center" valign="middle">
		        <p>Item Code Selection<br />
		          
<select name="folio[]" id="folio" class="txt easyui-combogrid" data-options="panelWidth: 600,
            multiple: false,
            idField: 'folio_code',
            textField: 'title',
			rownumbers:true,
            url: 'scriptfile_m.php?contentvar=loangrid',
            method: 'get',
            columns: [[
                {field:'ck',checkbox:true},
                {field:'folio_code',title:'UIL CODE',width:60},
                {field:'title',title:'UIL TITLE',width:200},
                {field:'ncoa_code',title:'NCOA CODE',width:50,align:'left'}
            ]],
            fitColumns: true" style="width:620px;height:20px" >
</select>		          
		          </p>
		        <div id="load_budget">
		          <br>
            </div></th>
	        </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Type of Payee<br />
              <input name="type" type="hidden" id="type" value="Internal" />Internal/Staff</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"><div id="load_voucher_fileno" align="left"></div></th>
	        </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Account No<br />		        <input style='width:300px' name="act_no" type="text" id="act_no" size="40" maxlength="10" required /></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"><div align="left">Payee Name<br />		        
	            <input style='width:300px' name="name" type="text" id="name" size="40" />
		      </div></th>
	        </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"><div align="left">Bank Name<br />
  <select name="bank" id="bank" style="width:300px">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
							echo "<option value='$pcode'>$scourse</option>";
							
						}
					
					?>
					</select>
	          </div></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      </tr>
		    <tr id="ptin_row">
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Payee Address<br />
              <input style='width:300px' name="address" type="text" id="address" size="40" /></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"></th>
	        </tr>
          </table>
		  <br />
		 <table width="100%">
				<tr>
				  <th><table width="100%" border="0">
				    <tr>
				      <th width="50%" align="left" valign="top">Start Date:<strong style="color:#F00">*</strong> <br />
				        <input name="start_date" type="date" id="start_date" size="25" style="width:300px" onChange="calcDate();" />
				        <input type="hidden" id="d_sdate" name="d_sdate" /></th>
				      <th width="50%" align="left" valign="top">End Date:<strong style="color:#F00">*</strong> <br />
				        <input name="end_date" type="date" id="end_date" size="25" style="width:300px" onChange="calcDate();" />
				        <input type="hidden" id="d_edate" name="d_edate" /></th>
				      </tr>
				    <tr>
				      <th width="50%" align="left" valign="top">Principal Amount:<br>
				        <input name="principal" type="text" id="principal" size="25" style="width:300px"/>
				        <input type="hidden" id="d_principal" name="d_principal" /></th>
				      <th width="50%" align="left" valign="top">No of Installments:<br>
				        <input name="installment" type="text" id="installment" value="1" size="25" style="width:300px" readonly />
				        <input type="hidden" id="d_installment" name="d_installment" /></th>
				      </tr>
				    <tr>
				      <th width="50%" align="left" valign="middle">Interest Rate:<strong style="color:#F00">*</strong><br />
				        <input name="interest" type="text" id="interest" style="width:300px" size="25"/>
                        <input name="cinterest" type="hidden" id="cinterest" value="" /></th>
				      <th width="50%" align="left" valign="top">&nbsp;<br>
				        <input type="button" name="compute" id="compute" value="Compute Value" class="easyui-button" style="width:300px" onClick="compute_loan();"/></th>
				      </tr>
				    <tr>
				      <th width="50%" align="left" valign="top">Monthly Installment Payable:<strong style="color:#F00">*</strong><br />
				        <input name="amount" type="text" id="amount" style="width:300px" size="25" readonly/>
				        <input type="hidden" id="d_amount" name="d_amount" /></th>
				      <th width="50%" align="left" valign="middle">Total Loan Payable:<strong style="color:#F00">*</strong><br />
				        <input name="tamount" type="text" id="tamount" style="width:300px" size="25" readonly/>
				        <input type="hidden" id="t_amount" name="t_amount" /></th>
				      </tr>
				    <tr>
				      <th align="left" valign="top">&nbsp;</th>
				      <th align="left" valign="middle">&nbsp;</th>
				      </tr>
				    <tr>
				      <th colspan="2" align="left" valign="top"><strong>Narration:</strong><br><textarea name='desc' id='desc' cols='45' rows='3' style="width:100%"></textarea><input type='hidden' name='vamount' id='vamount' value='' ></th>
				      </tr>
				    <tr>
				      <th colspan="2" align="center" valign="top"><input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('loan_section', 'save'); " /> | 

				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('loan_section', 'refresh');" /></th>
				      </tr>
			      </table></th>
			    </tr>
</table> 
         
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>

        </form>
             </div><!-- end of <div style="padding:10px 60px 20px 60px">-->  
          </div><!-- end of <div class="easyui-panel" title="New Topic" style="width:400px">-->
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

<script>
/*$(document).ready(function(e) {
	$('#dvat_inc').click(function(e){
		alert(124233);
		if(($('#dvat').val() * 1) > 0){
            if($(this).prop("checked") == true){
				alert(1234);
				var dvat=$('#dvat').val();
				var amt=$('#vamount').val();
				var val_calc=0;
				val_calc=(amt/100)*dvat;
                $('#dvat_val').html(val_calc);
            }
            else if($(this).prop("checked") == false){
                $('#dvat_val').html('');
            }
		}
	});
});*/
</script>