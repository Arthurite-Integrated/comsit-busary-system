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
	<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>-->
    
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
	<script type="text/javascript" src="include/jquery.colorbox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    
	<!--<script src="datepicker/jquery-1.8.3.js"></script>
    <script src="datepicker/jquery-ui.js"></script>
	-->
	<script src="datepicker/datepicker/ui.datepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="datepicker/datepicker/ui.datepicker.css">
	<script type="text/javascript" src="include/datagrid-groupview.js"></script>
	<script type="text/javascript" src="include/accounting.js"></script>
<style>
	.date1 {
		color: #333333;
		background: #F7F7F7;
		border: 1px solid #CCCCCC;
		height:25px;
		width:200px;
		vertical-align:inherit;
		text-align:inherit;
		padding-left: 1px;
		font-size:12px;
		font-family:Palatino Linotype;
	}
	
	.delete{
		content: 'X';
		border:outset 1px red;
		border-radius:4px;
		padding:3px;
	}
	
</style>
	
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php //include("required_jQuery_files.php");
require_once "function.php";
?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function(){
	 $(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
     $('#ppvno').hide();

	var max_fields = 50;
	var wrapper = $(".container1");
	var add_button = $(".add_form_field");
	var x = 1;
	$(add_button).click(function(e) {
        e.preventDefault();
		if(x < max_fields){
			x++;
			//$(wrapper).append('<div><input type="text" name="folio[]" /><a href="#" class="delete">Delete</a></div>'); //add input box
			//$(wrapper).append('<tr style="font-size:12px"><td><a href="#" class="delete">Delete</a> | ' + x + '</td><td nowrap><input type="search" name="bcode[]" value=""></td><td style="font-size:12px"><span id="folio_desc' + x + '"></span></td><td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td><td width="5%"><input type="number" class="amt3" name="cr_bamt[]" onblur="sum2(\'amt3\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			$(".rw").before('<tr style="font-size:12px"><td><a href="#" class="delete"><strong>X</strong></a></td><td nowrap><input type="search" name="bcode[]"  onBlur="swapcontent(\'foliodesc\', this.value, ' + x + ');" value="" size="11" maxlength="11"></td><td style="font-size:12px"><div id="foliodesc' + x + '"><input type="hidden" name="fdesc[]" value="null"></div></td><td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td><td width="5%"><input type="number" class="amt3" name="cr_bamt[]" onblur="sum2(\'amt3\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			
			//.insertAfter($(this).closest('tr'));
		}else{
			alert('You reached the limits');
		}
    });
	$(wrapper).on("click", ".delete", function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
		x--;
	});
});

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
		$(".t_" + clv).val(sum);
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

  if(cv=='foliodesc') //start unit
  {
	  //alert(cv+" "+v+" "+a);//exit();
	  //if(v != "")
	  	{
			$.post(url,{contentvar:cv, folio:v},function(data){
				//alert(data);
				$("#"+cv+a).html(data).show();
				$("#roll").html('').show();
			});
		}
  }//end of unit

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
  
  if(cv=='load_voucher_details_entry_rec') //start unit
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
 
  if(cv=='folio_code_breakdown_journal') //start load budget
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
 
 if(cv=='journal_section_entry_new') //start putme_login
  {
  
 //alert($("form").serialize());
  
 //alert(cv+" "+v+" "+a); exit;
  	// var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			//alert($("#pro_typ").val()); exit;
			//$.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a},function(data){	//a is the id of rec to edit/upd	
			//alert(data);	
			if(v=='save')
			 {
				 if($("#pay_date").val()=='' || $("#vamount").val()=='' )
				 {
					 alert("All compulsory fields must be filled before you can proceed");
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			
			$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
			$(divid).html('').show();	
			$('#display2').html(data).show();
			$('#roll').html('').show();
			
			 
			if(v=='save')
			    {
					  //swapcontent('journal_section_entry_new','refresh');
					$("#pay_date").val(''); $("#account").val(''); 
					$("#folio").val(''); $("#amt_approved").val('');
                    //$("#folio_code_breakdown_journal").html('').show();
                    $("#get_pv_detail_journal").html('').show();
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
                    //$("#folio_code_breakdown_journal").html('').show();
					$("#pay_date").val(''); $("#account").val(''); 
					$("#folio").val(''); $("#amt_approved").val('');
                    $("#get_pv_detail_journal").html('').show();
					//exit();
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


  if(cv=='load_category_r') //start unit
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

  if(cv=='get_pv_detail_journal'){
	var pvno=$("#pvno").val();
	var sn=$("#lsn").val();
    
	if(pvno=='')
	  { 
	  	alert('Enter PV. No.'); 
		exit();
	  }
		//alert(cv);	  
	$.post(url,{contentvar:cv,pvno:pvno, sn:sn},function(data){	
		$(divid).html(data).show();
		$('#roll').html('').show();
	});		
 } //end of search voucher
} //end of swapcontent


 </script>

<script>
function reload_folio(){
	//alert($('#folio').attr('data-options'));
	
	//$('#folio').attr('data-options', "Kasa:'Ased'{
		//},+-();");
		/*$('#folio').attr('data-options', "panelWidth: 600,multiple: true, idField: 'folio_code',textField: 'title', rownumbers: true, view:groupview, groupField:'categoryF', groupFormatter:function(value,rows){ return value + ' - ' + rows.length + ' Item(s)'; }, url: 'scriptfile_a.php?contentvar=grid&category=20', method: 'get', columns: [[ {field:'ck',checkbox:true}, {field:'folio_code',title:'UIL CODE',width:60}, {field:'title',title:'UIL TITLE',width:200}, {field:'ncoa_code',title:'NCOA CODE',width:50,align:'left'} ]], fitColumns: true, onChange:function(){swapcontent('folio_code_breakdown_journal',this.value);$('#vamount').val('');$('#total').html(''); $('#total_deduction').html('');$('.class_tax').html('');}");*/
		//alert($('#folio').attr('data-options'));
		
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
	  //$('#c_item').html("select f.*, c.folio_category as categoryF from account_codes f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='"+fundcenter+"' and f.deptcode='"+deptcode+"' order by f.category, f.title");//
	  //$('#c_item').html("[" + fundcenter + "-" + deptcode + "-XXXX] / [" + fundcenter + "-XXX-XXXX ]");
	$('#folio').combogrid('grid').datagrid('load', {category:category, fundcenter:fundcenter, deptcode:deptcode});
	//document.getElementById('folio').
}
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
	        <h2><i>Journal Voucher Entry</i></h2>
                <p>Use this form to post journal voucher entry</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
              <div class="easyui-panel" title="Journal Voucher Entry" style="width:800px">     
			  	<div style="padding:10px 60px 20px 60px">             
               <form enctype="multipart/form-data">
		  <table border="0" cellpadding="0" cellspacing="0" class="vch">
			<tr>
			  <th height="50" align="left" bgcolor="#F1F1F1">Entry Unit:<br />
			<select name="journal_unit" id="journal_unit" style="width: 300px" onchange="swapcontent('refresh',this.value);">
                  <option selected="selected" value="">---</option>
				  <?php  $q =  mysqli_query($con, "select * from unittb where dept_code='126' order by id");
							  while($r= mysqli_fetch_array($q, 3 )){
								  $unt =  $r['unit_code']."***".$r['unit_name'];
								echo '<option value="'. $unt.'">'. $r['unit_name'] .'</option>';
							  }
							  ?>
			</select>
			</th>
			  <th height="50" bgcolor="#F1F1F1"></th>
			  <th height="50" align="left" bgcolor="#F1F1F1">Entry Date:
              <br />	            <input type="text" name="pay_date" style="width: 300px" class="date" id="pay_date" onchange="swapcontent('generate_pvno',this.value);"/></th></tr>
              
              <tr>
			  <th height="50" align="left" bgcolor="#F1F1F1">PA P.V. No. <font size="1" color="#FF0000">(For retirement only)</font>:<br />
			<input type='text' id='pvno' name='pvno' style='width: 190px'> <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('get_pv_detail_journal');"><strong><font color="#000099">GET PV</font></strong></a>
            <input type='hidden' id='lsn' name='lsn' value='0'>
			</th>
			  <th height="50" bgcolor="#F1F1F1"></th>
			  <th height="50" align="left" bgcolor="#F1F1F1"><div align="left">Account to be Debited:<br />
                <select name="account" id="account" class="txt" style="width: 300px"  >
                  <option selected="selected">---</option>
                  <!--<option value="TSA">TSA</option>
                  <option value="GIFMIS">GIFMIS</option>-->
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
                    <?php
                          /*$res_c=@mysqli_query($con, "select * from bank_accounttb"); // order by deptname");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $acct_code=@$rs_c['acctcode'];
							  $acct_name=@$rs_c['acctname'];
                              echo "<option value='$acct_code'>$acct_name</option>";
                           }
                          //echo "</select>";*/
						 ?>
                </select>
		      </div></th></tr>
              <tr>
                <th height="27" colspan="3" align="left" bgcolor="#F1F1F1"></th>
                </tr>
              
		    <tr bgcolor="#6BF4C7">
		      <th height="50" colspan="3" align="center" valign="middle"><div id="get_pv_detail_journal"></div></th>
	        </tr>

          </table>
		  <br />
		 <div id="folio_code_breakdown_journal" style='width:700px;' class='easyui-panel container1' title='Folio / Code Breakdown'>
                <!--<div><input type="text" name="folio[]"></div>-->
         <font color='red'><table width='100%' border=1 rules='rows'>
         <tr class="rx"><th>&nbsp;</th><th width='5%'>Code</th><th>Description</th><th>Dr(&#8358;)</th><th>Cr(&#8358;)</th></tr>
         
         <tr style="font-size:12px"><td>&nbsp;</td><td nowrap><input name="bcode[]" type="search" onBlur="swapcontent('foliodesc', this.value, 1);" value="" size="11" maxlength="11"></td><td style="font-size:12px"><div id="foliodesc1"><input type="hidden" name="fdesc[]" value="null"></div></td><td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2('amt2')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td><td width="5%"><input type="number" class="amt3" name="cr_bamt[]" onblur="sum2('amt3')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>
         
         <tr class="rw"><th></th><th></th><th>TOTAL AMOUNT (&#8358;)</th>
		<th width='5%'><input type='number' class='t_amt2' name='dr_vamount' id='dr_vamount' value='0' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' readonly ></th>
		<th width='5%'><input type='number' class='t_amt3' name='cr_vamount' id='cr_vamount' value='0' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' readonly ></th>
		</tr>
        </table>
        </font>
         </div> 
         		<button class="add_form_field">Add Row &nbsp;
                <span style="font-size:16px; font-weight:bold;">+ </span>
                </button>
         <br>
         <p><strong>Narration:</strong> <br>
		          <textarea name="narration" id="narration" style="width:100%"></textarea>
		        </p>
         <table border="0" cellpadding="0" cellspacing="0" class="vch">
        <tr><th height='33'> <input type="checkbox" name="pro_type" value="final" id="pro_type" style="appearance:radio-button; height:30px; width:30px; border:outset 1px red;">
    </th>
          <th height='33'><label for="pro_type" style="font-size:18px">Final Account Entry? </label></th>
          <th height='33'>&nbsp;</th>
          <th height='33'><input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onClick="swapcontent('journal_section_entry_new','save'); " /></th>
          <th height='33'>|</th>
          <th height='33'><input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onClick="swapcontent('journal_section_entry_new','refresh');" /></th>
          <th height='33'>&nbsp;</th>
          <th height='33'>&nbsp;</th>
          <th height='33'>&nbsp;</th>
          <th height='33'>&nbsp;</th>
          <th height='33'>&nbsp;</th>
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