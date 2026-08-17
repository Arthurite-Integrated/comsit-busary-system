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
<!--<link rel="stylesheet" type="text/css" href="include/easyui.css">
   <link rel="stylesheet" type="text/css" href="include/icon.css">
	<link rel="stylesheet" type="text/css" href="include/demo.css">
	<link rel="stylesheet" type="text/css" href="include/colorbox.css">
    <link rel="stylesheet" href="css/tinybox.css" />
     <script type="text/javascript" src="include/jquery.min.js"></script>
    
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
	<script type="text/javascript" src="include/jquery.colorbox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg">
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    
	<script src="datepicker/datepicker/ui.datepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="datepicker/datepicker/ui.datepicker.css">
	<script type="text/javascript" src="include/datagrid-groupview.js"></script>
	<script type="text/javascript" src="include/accounting.js"></script>
--><style>
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
		cursor:pointer;
	}
</style>
	
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php include("required_jQuery_files.php");
include "function.php";?>

<link href="tooplate_style.css" rel="stylesheet" type="text/css" />

<script>
$(document).ready(function(){
	 ////$(".iframe").colorbox({iframe:true, width:"80%", height:"80%"});
     ////$('#ppvno').hide();
	var max_fields = 50;
	var wrapper = $(".container1");
	var add_button = $(".add_form_field");
	var x = 1;

	$('#dgout').datagrid({
		onSelect: function(index, row){
			//alert(row.folio_code);
			//$(this).datagrid('beginEdit', index);
			//var ed = $(this).datagrid('getEditor', {index:index,field:field});
			//$(ed.target).focus();
			///getSelections();
			
        //e.preventDefault();
		if(x < max_fields){
			x++;
			//$(wrapper).append('<div><input type="text" name="folio[]" /><a href="#" class="delete">Delete</a></div>'); //add input box
			//$(wrapper).append('<tr style="font-size:12px"><td><a href="#" class="delete">Delete</a> | ' + x + '</td><td nowrap><input type="search" name="bcode[]" value=""></td><td style="font-size:12px"><span id="folio_desc' + x + '"></span></td><td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td><td width="5%"><input type="number" class="amt3" name="cr_bamt[]" onblur="sum2(\'amt3\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			$(".rw").before('<tr style="font-size:12px">' +
				'<td><a href="#" class="delete"><strong>X</strong></a></td><td nowrap><input type="text" name="folio[]" value="' + row.folio_code + '" readonly size="11" maxlength="11"></td>' +
				'<td style="font-size:12px"><div id="foliodesc' + x + '">' + row.title + '<input type="hidden" name="fdesc[]" value="' + row.title + '"></div></td>' +
				'<td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			
			//.insertAfter($(this).closest('tr'));
		}else{
			alert('You reached the limits');
		}
    
		}
	});
	
	$('#dgstaff').datagrid({
		onSelect: function(index, row){
			//alert(row.fileno);
			//$(this).datagrid('beginEdit', index);
			//var ed = $(this).datagrid('getEditor', {index:index,field:field});
			//$(ed.target).focus();
			//getSelectedStaff();
			//alert('Staff ID:'+row.fileno);
				document.getElementById('fileno').value=row.fileno;
				document.getElementById('name').value=row.fullname;
				document.getElementById('act_no').value=row.acct_no;
				document.getElementById('bank').value=row.bank_name;
				document.getElementById('address').value=row.dept;
				document.getElementById('phoneno').value=row.phone_no;
				document.getElementById('load_voucher_fileno').innerHTML=row.fileno;
		}
	});

	$(add_button).click(function(e) {
        e.preventDefault();
		if(x < max_fields){
			x++;
			//$(wrapper).append('<div><input type="text" name="folio[]" /><a href="#" class="delete">Delete</a></div>'); //add input box
			//$(wrapper).append('<tr style="font-size:12px"><td><a href="#" class="delete">Delete</a> | ' + x + '</td><td nowrap><input type="search" name="bcode[]" value=""></td><td style="font-size:12px"><span id="folio_desc' + x + '"></span></td><td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td><td width="5%"><input type="number" class="amt3" name="cr_bamt[]" onblur="sum2(\'amt3\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			$(".rw").before('<tr style="font-size:12px">' +
				'<td><a href="#" class="delete"><strong>X</strong></a></td>' +
				'<td nowrap><input type="text" name="folio[]" value="" size="11" maxlength="11" readonly></td>' +
				'<td style="font-size:12px"><div id="foliodesc' + x + '"><input type="hidden" name="fdesc[]" value="null"></div></td>' + 
				'<td width="5%"><input type="number" class="amt2" name="dr_bamt[]" onblur="sum2(\'amt2\')" onChange="sum2(\'amt2\')" style="background-color: #FEFFB0;font-weight: bold;text-align: right;"></td></tr>');
			
			//.insertAfter($(this).closest('tr'));
		}else{
			alert('You reached the limits');
		}
    });
	$(wrapper).on("click", ".delete", function(e){
		e.preventDefault();
		$(this).closest('tr').remove();
		$("#dr_vamount").val(0);
		$("#vamount").val(0);
		x--;
	});


});

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
		$(".t_" + clv).val(totalamt);
      $("#total").html(totalamt.toFixed(2));
	  $("#vamount").val(totalamt);
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
	  //alert($("#pay_date").val()+" "+$("#lockdate").val()+" "+a);exit();
	if($("#pay_date").val() != ''){
		var d1 = new Date($("#pay_date").val()).getTime();
		var d2 = new Date($("#lockdate").val()).getTime();
		//var same = d1.getTime() === d2.getTime();
		//var notSame = d1.getTime() !== d2.getTime();
		let pay=parseInt(d1);	let lock=parseInt(d2);
		if(pay < lock){
			alert("Posting is not enabled for the selected date. \nContact Final Account..");
			$("#roll").html('').show();
			$(divid).html('').show();
			exit();
		}
	}
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
  
  if(cv=='load_voucher_details_entry_final') //start unit
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
 
 if(cv=='voucher_section_entry_final') //start putme_login
  {
  
	//alert($("form").serialize());
	
	//alert(cv+" "+v+" "+a); exit;
  	// var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			//alert($("#pro_typ").val()); exit;
			//$.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a},function(data){	//a is the id of rec to edit/upd	
			//alert(data);	
			if(v=='save')
			 {
				if($("#pay_date").val() != ''){
					var d1 = new Date($("#pay_date").val());
					var d2 = new Date($("#lockdate").val());
					let pay=parseInt(d1);	let lock=parseInt(d2);
					if(pay < lock){
						alert("Posting is not enabled for the selected date. \nContact Final Account.");
						$("#lockdate").val("");
						$("#roll").html('').show();
						$(divid).html('').show();
						exit();
					}
				}

                /*if($('#type option:selected').val() == 'External' && $("#bank").val()==''){
					 	alert("All compulsory fields must be filled before you can proceed");
					 	$('#roll').html('').show();
					 	exit();
                     }*/
                //$("#account").val()=='' || 
				 if($("#pay_date").val()=='' || $("#dept").val()=='' || $("#pvno").val()=='' || $("#folio").val()=='' || $("#desc").val()=='' || $("#vamount").val()=='' || $("#fileno").val()=='')
				 {
					 alert("All compulsory fields must be filled before you can proceed");
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			 $('#display2').html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
			
			$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
			$(divid).html('').show();	
			$('#display2').html(data).show();
			$('#roll').html('').show();
			
			 
			if(v=='save')
			    {
					  //swapcontent('voucher_section_entry','refresh');
					  $("#pay_date").val('');
					
					 $("#dept").val(''); $("#pvno").val(''); $("#account").val('');
					$("#folio").val(''); $("#type").val(''); $("#fileno").val('External'); $("#name").val('');$("#act_no").val('');
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
					$("#folio").val(''); $("#type").val(''); $("#fileno").val('External'); $("#name").val('');$("#act_no").val('');
					$("#bank").val(''); $("#address").val(''); $("#payee_tin_number").val(''); $("#payee_sort_code").val('');
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
  if(cv=='folio_code_breakdown_journal') //start load budget
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert('Payee details');
	 // if($("#pay_date").val()=='')
	  //  { alert("Please select payment data"); $("#folio").val(''); $("#roll").html('').show(); $(divid).html('').show(); exit();}
		//alert($("form").serialize()); exit;
	      $.post(url,{contentvar:cv,folio:v},function(data){
		   //$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){	//a is the id of rec to edit/upd
			//var pData=jQuery.parseJSON(data); 
			$("#roll").html('').show();
			$(divid).html(data).show();
			
			});
  }//end of load budget

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
<body class="subpage"><!-- onLoad="$('#fileno').combogrid('StaffGrid').datagrid('load', {filen:''});"-->

<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
	<?php include_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>Raise Voucher</h2>
                <p>&nbsp;</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
              <div class="easyui-panel" title="New Voucher Entry" style="width:800px">     
			  	<div style="padding:10px 60px 20px 60px">             
               <form enctype="multipart/form-data">
		  <table border="0" cellpadding="0" cellspacing="0" class="vch">
		    <tr>
		      <td colspan="3"><div align="righdt"><input type="button" class="easyui-linkbutton" name="chbtn1" id="chsbtn1" value="My Entries" onclick="swapcontent('voucher_section_entry','view');" /> 
			  
			  <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('search_voucher');"><strong><font color="#000099">Search by Processing No.</font></strong></a>
			  <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('search_voucher_by_pvno');"><strong><font color="#000099">Search by PV. No.</font></strong></a> <a class='iframe easyui-linkbutton' iconCls="icon-tip" href="votebook.php"><strong><font color="#000099">Check Vote Book</font></strong></a><input name="pro_typ" type="hidden" id="pro_typ" size="25" value="Pending" />
			 <!-- <input type="button" class="easyui-linkbutton btn " name="chbtn1" id="chsbtn1" value="Search by Processing Number" onclick="swapcontent('search_voucher');" />
	            <input type="button" class="btn" name="chsbtn" id="chsbtn2" value="Search by PV. Number" onclick="swapcontent('search_voucher_by_pvno');" />
				-->
		      </div><!--<br />
			   <div id="" style='width:700px;' class='easyui-panel' title='Voucher Entry'>
			  <strong><input name="autocreate" type="checkbox" value="yes" style="width:20px; height:20px;" />
			  Auto-create Deduction Voucher

  </strong>-->
<style>
input[type="radio"] {
    -webkit-appearance: checkbox; /* Chrome, Safari, Opera */
    -moz-appearance: checkbox;    /* Firefox */
    -ms-appearance: checkbox;     /* not currently supported */
	appearance: checkbox;
	width:20px; height:20px;
}
</style>
<?php
			   	if(isset($_REQUEST['id']) and $_REQUEST['id']!=''){
					$memo_id = @base64_decode($_REQUEST['id']);
					$rs=@mysqli_query($con, "select * from memotb where memo_id='$memo_id'");
					if( mysqli_num_rows($rs)==0)
						{
							echo "<script>alert('No record found for Memo ID: $memo_id');</script>";
							echo "<font color='red'>No record found for Memo ID: $memo_id</font>";
						}
					$rst=@mysqli_fetch_array($rs);
					$from=@$rst['memo_from'];$description=@$rst['description'];$amount=@$rst['amount'];
					$amount_approved=@$rst['amount_approved'];$remark=@$rst['remark'];
					$datein=@$rst['datein'];$file_path=@$rst['file_path'];
					?>
                    <div id="" style='width:700px;' class='easyui-panel' title='MEMO DETAILS'>
                    <table width="100%"><tr><td width="50%"><?php echo "Memo ID : $memo_id"; ?></td><td><?php echo "Date : $datein"; ?></td></tr>
                    		<tr><td><?php echo "From : $from"; ?></td><td><?php echo "Memo Amount : $amount"; ?></td></tr>
                            <tr><td colspan='2'><?php echo "Description : $description"; ?></td></tr></table>
				
			   
			   
			 <p align="right"> <a class='iframe easyui-linkbutton' iconCls="icon-tip" href="<?php echo $file_path;?>"><strong><font color="#000099">View Approved Document</font></strong></a></p>
              </div>
			   <?php }else{ ?>
               <!--<input type="file" id="attache" name="attache" title="Attach supporting document" accept="application/pdf">-->
				<?php }	//end if $_REQUEST['id'] ?>
			   <!--<p align="right"> <a class='iframe easyui-linkbutton' iconCls="icon-tip" href="<?php echo $file_path;?>"><strong><font color="#000099">View Approved Document</font></strong></a></p>
			   
			  
			   </div>  
			   <br />-->
               <!--div id="ppvno"><label for="pvno_paid"><strong>Payment Voucher Number:</strong>
                 <input type="text" id="pvno_paid" name="pvno_paid"></label>
               </div>
               <br-->
			  </td>
	        </tr>
			<tr>
			  <th height="50" align="left" bgcolor="#F1F1F1">Entry Unit:<br />
			<input name="memo_id" type="hidden" id="memo_id" value="<?php echo $memo_id;?>" size="25" />
			<input name="amt_approved" type="hidden" id="amt_approved" value="<?php echo $amount_approved;?>" size="25" />
			<select name="voucher_unit" id="voucher_unit" style="width: 300px" onchange="swapcontent('refresh',this.value);">
                  <option selected="selected" value="">---</option>
				  <?php  $q =  mysqli_query($con, "select * from unittb where dept_code='126' order by id");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>';
							  }
							  ?>
			</select>
			</th>
			  <th height="50" bgcolor="#F1F1F1"></th>
			  <th height="50" align="left" bgcolor="#F1F1F1">Voucher Date:
			  <?php  $q =  mysqli_query($con, "SELECT * from lock_posting WHERE locktype='Lock-Previous'");
			while($r= mysqli_fetch_array($q, 3 )){
				$lockdate=$r['lockdate'];
			}
			?>
              <br />	            <input type="hidden" name="lockdate" id="lockdate" value="<?=$lockdate; ?>" />
	    <input type="date" name="pay_date" style="width: 300px" id="pay_date" onchange="swapcontent('generate_pvno',this.value);" min="<?=$lockdate; ?>" value="" /></th></tr>
		    <tr>
		      <th height="50" align="left" bgcolor="#F1F1F1" ><input type="hidden" value="" name="account" id="account" >
              Account to be Debited:<br />
                <select name="account" id="account" class="txt" style="width: 300px"  >
                  <option selected="selected" value="">---</option>
                  <?php
								$r=@mysqli_query($con, "select distinct *  from bank_accounttb where status='Active' order by acctcode");
								while ($rcourse=@mysqli_fetch_array($r))
									{
										$scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
										$bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
										$acctname=@$rcourse['acctname'];/*$bank || $acctno || */
										echo "<option value='$pcode'>$scourse <=> ($pcode)</option>";
									}
								
								?>
                </select>
		      <!--/div--></th>
		      <th height="50" align="right" valign="middle" bgcolor="#F1F1F1" >&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1" >System Processing No.:<br />
<span id="generate_pvno"><input name="pvno" type="text" style="width: 300px" id="pvno" size="25" /></span></th>
	        </tr>
            <tr bgcolor="#FFF">
              <th height="50" align="left">
				<b>P.V. No.</b> <br/>
				  <input name="pvno_final" type="text" style="width: 300px" id="pvno_final" size="25" />
				</th>
              <th height="50" align="right" valign="middle">&nbsp;</th>
              <th height="50" align="left" valign="middle">
              <b>Payment Batch No.</b> <br/>
				  <input name="batchno" type="text" style="width: 300px" id="batchno" size="25" />
              </th>
            </tr>
            <tr bgcolor="#FFF">
		      <th height="50" align="left"><div align="left">Is this payment a 'Purchase Advance' payment voucher?<br />
                
		      </div></th>
		      <th height="50" align="right" valign="middle">&nbsp;</th>
		      <th height="50" align="left" valign="middle">
		        <label for="ispa_0">
		          <input type="radio" name="ispa" value="Yes" id="ispa_0">
		          Yes</label>
		         &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
		        <label for="ispa_1">
		          <input type="radio" name="ispa" value="No" id="ispa_1">
		          No</label></th>
	        </tr>
            
		    <tr bgcolor="#6BF4C7">
		      <th height="50" colspan="3" align="center" valign="middle">
          <script>
		function getSelected(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				//alert('Memo ID:'+row.memo_id+"\nAmount:"+row.amount);
				document.getElementById('tmemoid').value=row.memo_id;
				document.getElementById('memo_unit_code').value=row.dept_unit;
				
				$('#idoc').attr("href", row.memo_id.replace('/', '') );
				$('#idoc').attr("href", $('#idoc').attr("href").replace('/', '') );
				$('#idoc').attr("href", "upload_files/" + $('#idoc').attr("href").replace('/', '') + ".pdf" );
				
				document.getElementById('hmemoid').innerHTML=row.memo_id;
				document.getElementById('hmemofrom').innerHTML=row.memo_from;
				document.getElementById('haddress_unit').innerHTML=row.address_unit;
				
				document.getElementById('vmemoid').innerHTML=row.memo_id;
				document.getElementById('vmemoaction').innerHTML=row.memo_status;
				document.getElementById('vmemodate').innerHTML=row.datein;
				document.getElementById('hmemoamountd').innerHTML=row.amount;
				
				$("#vmemofrom").attr("value", row.memo_from);
				//$("#vaddress_unit").attr("value", row.address_unit);
				$("#vmemodesc").text(row.description);
				$("#vmemoamount").attr("value", row.amount);
				$("#hmemoamount").attr("value", row.amount);
				$("#vmemodept option:selected").attr("value", row.dept_unit);
				$("#vmemodept option:selected").text(row.dept_unit);
				$("#vaddress_unit option:selected").attr("value", row.address_unit);
				$("#vaddress_unit option:selected").text(row.address_unit);
				$("#vmemoid_x").attr("value", row.memo_id);
				
				$('#vidoc').attr("href", row.memo_id.replace('/', '') );
				$('#vidoc').attr("href", $('#vidoc').attr("href").replace('/', '') );
				$('#vidoc').attr("href", "upload_files/" + $('#vidoc').attr("href").replace('/', '') + ".pdf" );
			}
		}
		
		function getSelected_treated(){
			var row = $('#dgout').datagrid('getSelected');
			 getSelections();
			if (row){
				//alert(row.folio_code);
				swapcontent('folio_code_breakdown_journal', row.folio_code);
			}
		}
		function reload_grids(){
			$('#dgout').datagrid('reload');
			$('#dgstaff').datagrid('reload');
		}

		function getSelections(){
			var ids = [];
			var rows = $('#dgout').datagrid('getSelections');
			//alert(rows);
			for(var i=0; i<rows.length; i++){
				ids.push(rows[i].folio_code);
			}
			//alert(rows.ids);
			swapcontent('folio_code_breakdown_journal', ids);
			//alert(ids.join('\n'));
			//document.getElementById('tmemoid').value=ids.join('\n');
		}
	</script>

          <table id="dgout" title="" style="width:auto;" data-options="
                singleSelect:true,
                url: 'scriptfile_m.php?contentvar=foliocode_grid',
                rownumbers:true,method:'get',toolbar:'#tb_2g', pagination:true,
                pageSize:10">
        <thead>
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <!--th data-options="field:'category',width:120"><strong>CATEGORY</strong></th-->
                <th data-options="field:'folio_code',width:100"><strong>ITEM CODE</strong></th>
                <th data-options="field:'title',width:250"><strong>DESCRIPTION</strong></th>
                <th data-options="field:'ncoa_code',width:80"><strong>NCOA CODE</strong></th>
                <th data-options="field:'ncoa_title',width:180,align:'left'"><strong>NCOA TITLE</strong></th>
                <th data-options="field:'exp',width:80,align:'left'"><strong>CODE CATEGORY</strong></th>
            </tr>
        </thead>
    </table>
            
<script>
		//script for pagination starts
        (function($){
            function pagerFilter(data){
                if ($.isArray(data)){    // is array
                    data = {
                        total: data.length,
                        rows: data
                    }
                }
                var dg = $(this);
                var state = dg.data('datagrid');
                var opts = dg.datagrid('options');
                if (!state.allRows){
                    state.allRows = (data.rows);
                }
                var start = (opts.pageNumber-1)*parseInt(opts.pageSize);
                var end = start + parseInt(opts.pageSize);
                data.rows = $.extend(true,[],state.allRows.slice(start, end));
                return data;
            }
 
            var loadDataMethod = $.fn.datagrid.methods.loadData;
            $.extend($.fn.datagrid.methods, {
                clientPaging: function(jq){
                    return jq.each(function(){
                        var dg = $(this);
                        var state = dg.data('datagrid');
                        var opts = state.options;
                        opts.loadFilter = pagerFilter;
                        var onBeforeLoad = opts.onBeforeLoad;
                        opts.onBeforeLoad = function(param){
                            state.allRows = null;
                            return onBeforeLoad.call(this, param);
                        }
                        dg.datagrid('getPager').pagination({
                            onSelectPage:function(pageNum, pageSize){
                                opts.pageNumber = pageNum;
                                opts.pageSize = pageSize;
                                $(this).pagination('refresh',{
                                    pageNumber:pageNum,
                                    pageSize:pageSize
                                });
                                dg.datagrid('loadData',state.allRows);

                            }
                        });
                        $(this).datagrid('loadData', state.data);
                        if (opts.url){
                            $(this).datagrid('reload');
                        }
                    });
                },
                loadData: function(jq, data){
                    jq.each(function(){
                        $(this).data('datagrid').allRows = null;
                    });
                    return loadDataMethod.call($.fn.datagrid.methods, jq, data);
                },
                getAllRows: function(jq){
                    return jq.data('datagrid').allRows;
                }
            })
        })(jQuery);
 
        function getData(){
            var rows = [];
            for(var i=1; i<=800; i++){
                var amount = Math.floor(Math.random()*1000);
                var price = Math.floor(Math.random()*1000);
                rows.push({
                    inv: 'Inv No '+i,
                    date: $.fn.datebox.defaults.formatter(new Date()),
                    name: 'Name '+i,
                    amount: amount,
                    price: price,
                    cost: amount*price,
                    note: 'Note '+i
                });
            }
            return rows;
        }
        
		//script for pagination ends
</script>
    
    <script type="text/javascript">
	//FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
        $(function(){
            var dg = $('#dgout').datagrid();
			
			
            dg.datagrid('enableFilter', [
			
			{
                field:'amount',
                type:'numberbox',
                options:{precision:2},
                op:['equal','notequal','less','greater']
            },
			/*{
                field:'unitcost',
                type:'numberbox',
                options:{precision:1},
                op:['equal','notequal','less','greater']
            },*/
			{
                field:'exp',
                type:'combobox',
                options:{
                    panelHeight:'auto',
                    data:[{value:'',text:'All'},{value:'Assets',text:'Assets'},
						{value:'Expenses',text:'Expenses'},{value:'Income',text:'Income'},{value:'Liabilities',text:'Liabilities'}],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'exp');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'exp',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
            }]);
        });
    </script>
            </th>
	        </tr>
            
		    <tr bgcolor="#6BF4C7">
		      <th height="5" colspan="3" align="center" valign="middle">&nbsp;</th>
		      </tr>
              
              <tr>
		      <th height="50" colspan="3" align="center" valign="top" bgcolor="#F1F1F1"><h3>EMPLOYEE RECORD<br>
		        </h3>
		        <table id="dgstaff" title="" style="width:auto;" data-options="
                singleSelect:true,
                url: 'scriptfile_m.php?contentvar=staff_grid',
                rownumbers:true,method:'get',toolbar:'#tb_file', pagination:true,
                pageSize:10">
		          <thead>
		            <tr>
		              <th data-options="field:'ck',checkbox:true"></th>
		              <!--th data-options="field:'category',width:120"><strong>CATEGORY</strong></th-->
		              <th data-options="field:'fileno',width:80"><strong>FILE NO.</strong></th>
		              <th data-options="field:'fullname',width:250"><strong>FULL NAME</strong></th>
		              <th data-options="field:'dept',width:120"><strong>DEPARTMENT</strong></th>
		              <th data-options="field:'acct_no',width:85,align:'left'"><strong>ACCT. NO.</strong></th>
		              <th data-options="field:'bank_name',width:120,align:'left'"><strong>BANK NAME</strong></th>
		              <th data-options="field:'phone_no',width:80,align:'left'"><strong>PHONE NO.</strong></th>
		              </tr>
		            </thead>
		          </table>
		        
		        
		        
		        <script type="text/javascript">
	//FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
        $(function(){
            var dg = $('#dgstaff').datagrid();
			
			
            dg.datagrid('enableFilter', [
			
			{
                field:'amount',
                type:'numberbox',
                options:{precision:2},
                op:['equal','notequal','less','greater']
            },
			/*{
                field:'unitcost',
                type:'numberbox',
                options:{precision:1},
                op:['equal','notequal','less','greater']
            },*/
			{
                field:'exp',
                type:'combobox',
                options:{
                    panelHeight:'auto',
                    data:[{value:'',text:'All'},{value:'Assets',text:'Assets'},
						{value:'Expenses',text:'Expenses'},{value:'Income',text:'Income'},{value:'Liabilities',text:'Liabilities'}],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'exp');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'exp',
                                op: 'equal',
                                value: value
                            });
                        }
                        dg.datagrid('doFilter');
                    }
                }
            }]);
        });
    </script>
		        </th>
		      </tr>
              
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Account Number<br />		        <input style='width:300px' name="act_no" type="text" id="act_no" size="40" maxlength="10" required /></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"><div align="left">Payee Name<br />		        
		        <input style='width:300px' name="name" type="text" id="name" size="40" />
		        </div></th>
		      </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"><div align="left">Bank Name<br />
              <input style='width:300px' name="bank" type="text" id="bank" size="40" />
  <!--select name="bank" id="bank" style="width:300px">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
							echo "<option value='$pcode'>$scourse</option>";
						}
					
					?>
					</select-->
	          </div></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Phone Number<br />
		        <input style='width:300px' name="phoneno" type="text" id="phoneno" size="40" maxlength="10" required /></th>
		      </tr>
		    <tr id="ptin_row">
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Payee Address<br />		        <input style='width:300px' name="address" type="text" id="address" size="40" /></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" style="background-color:#FFF; padding:10px" bgcolor="#F1F1F1">STAFF ID: 
		        <input type="hidden" value="External" name="fileno" id="fileno" ><div id="load_voucher_fileno"></div></th>
	        </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Payee Sort Code<br />
              <input style='width:300px' name="payee_sort_code" type="text" id="payee_sort_code" size="40" /></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
              <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"><div align="left">Payee TIN Number<br />
              <input style='width:300px' name="payee_tin_number" type="text" id="payee_tin_number" size="40"  /></div></th>
		      
	        </tr>
		    <tr>
		      <th height="20" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</th>
		      <th height="20" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</th>
		      <th height="20" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</th>
		      </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
				  <b>Prepared By</b> <br/>
				  <select class='txt' style='width:300px' name='prepared_by' id='prepared_by'>
				  <option selected value=''>---</option>
				<?php
	   $res_s= mysqli_query($con, "select fileno, surname, first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') and dept_code='126' order by surname");
	   while($rs_s =  mysqli_fetch_array($res_s))
	    {
			$fileno=$rs_s['fileno'];
			$name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
			echo "<option value='$fileno'>$name || $fileno</option>";
		}
				  ?>
				</select></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
				<b>Checked By</b> <br/>
				  <select class='txt' style='width:300px' name='checked_by' id='checked_by'>
				  <option selected value=''>---</option>
				<?php
	   $res_s= mysqli_query($con, "select fileno, surname, first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') and dept_code='126' order by surname");
	   while($rs_s =  mysqli_fetch_array($res_s))
	    {
			$fileno=$rs_s['fileno'];
			$name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
			echo "<option value='$fileno'>$name || $fileno</option>";
		}
				  ?>
				</select></th>
		      </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
				<b>Certified By</b> <br/>
				  <select class='txt' style='width:300px' name='certified_by' id='certified_by'>
				  <option selected value=''>---</option>
				<?php
	   $res_s= mysqli_query($con, "select fileno, surname, first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') and dept_code='126' order by surname");
	   while($rs_s =  mysqli_fetch_array($res_s))
	    {
			$fileno=$rs_s['fileno'];
			$name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
			echo "<option value='$fileno'>$name || $fileno</option>";
		}
				  ?>
				</select>
				</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
				<!--b>Authorized By</b> <br/>
				  <select class='txt' style='width:300px' name='authorized_by' id='authorized_by'>
				  <option selected value=''>---</option>
				<?php
	   $res_s= mysqli_query($con, "select fileno, surname, first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') and dept_code='126' order by surname");
	   while($rs_s =  mysqli_fetch_array($res_s))
	    {
			$fileno=$rs_s['fileno'];
			$name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
			echo "<option value='$fileno'>$name || $fileno</option>";
		}
				  ?>
				</select-->
				</th>
		      </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
				<b>Audited By</b> <br/>
				  <select class='txt' style='width:300px' name='audited_by' id='audited_by'>
				  <option selected value=''>---</option>
				<?php
	   $res_s= mysqli_query($con, "select fileno, surname, first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') and dept_code='126' order by surname");
	   while($rs_s =  mysqli_fetch_array($res_s))
	    {
			$fileno=$rs_s['fileno'];
			$name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
			echo "<option value='$fileno'>$name || $fileno</option>";
		}
				  ?>
				</select>
				</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
				<b>Controlled By</b> <br/>
				  <select class='txt' style='width:300px' name='controlled_by' id='controlled_by'>
				  <option selected value=''>---</option>
				<?php
	   $res_s= mysqli_query($con, "select fileno, surname, first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') and dept_code='126' order by surname");
	   while($rs_s =  mysqli_fetch_array($res_s))
	    {
			$fileno=$rs_s['fileno'];
			$name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
			echo "<option value='$fileno'>$name || $fileno</option>";
		}
				  ?>
				</select>
				</th>
		      </tr>
          </table><input type="hidden" id="vamount" name="vamount" value="" />
		  <br />
		 <div id="folio_code_breakdown_post" style='width:700px;' class='easyui-panel container1' title='Folio / Code Breakdown'>
                <!--<div><input type="text" name="folio[]"></div>-->
         <font color='red'><table width='100%' border=1 rules='rows'>
         <tr class="rx"><th> X </th><th width='5%'>CODE</th><th>DESCRIPTION</th><th width='5%'>AMOUNT (&#8358;)</th></tr>         
         <tr class="rw"><th colspan="3">GROSS AMOUNT (&#8358;)</th>
		<th width='5%'><input type='number' class='t_amt2' name='dr_vamount' id='dr_vamount' value='0' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' readonly ></th>
		
		</tr>
        </table>
        </font>
         </div> 
         
         <table width="100%">
				<tr><th colspan="3">Detail Description of Goods/Services</th>
			    </tr>
				<tr><th colspan='3'>Being <br><textarea name='desc' id='desc' rows='3' style="width:98%"></textarea></th></tr>
                    
<tr><td colspan="3">
<table width="100%" cellpadding="0" align="left">
            <tr>
              <th height="28">&nbsp;</th>
              <td height="28"><strong>Ded. Rate</strong></td>
              <td height="28"><strong>Ded. Payee</strong></td>
              <td nowrap="nowrap"><strong>Acct. Number</strong></td>
              <td nowrap="nowrap"><strong>Bank Name</strong></td>
              <td height="28" nowrap="nowrap"><strong>Action</strong></td>
              <td align="center" valign="middle" nowrap="nowrap" id="dvat_val2" style=""><strong>Ded.</strong></td>
        </tr>
            <tr>
              <th width="106" height="36" align="left">VAT (%):</th>
              <td height="36"><input type="number" id="dvat" name="dvat" value="0" min="0" max="100" onChange="
				var dvat=$('#dvat').val()*1;
				var amt=$('#vamount').val()*1;
				var val_calc=0;	var total_ded=0;
				//val_calc=(amt/100) * dvat;
       if(dvat >= 0){
            if($('#dvat_inc').prop('checked') == true){
            	val_calc=(dvat/(dvat + 100))*amt; //alert(1234);
            }
            else if($('#dvat_inc').prop('checked') == false){
            	val_calc=(dvat/100)*amt;
            }
                $('#dvat_val').html(val_calc.toFixed(2));		
                total_ded = (amt - (($('#dvat_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                $('#total_deduction').html(total_ded.toFixed(2));
       }else {
                total_ded = ($('#total_deduction').html() * 1) - ($('#dvat_val').html() * 1);
                $('#dvat_val').html(0);
                $('#total_deduction').html(total_ded.toFixed(2));
       }
		"></td>
        	  <td nowrap="nowrap"><select name="dvat_payee" id="dvat_payee" style="width:100px">
				<option selected="selected" value="">---</option>
                <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                <option value="VAT Sub Account">VAT Sub Account</option>
                <option value="KWIRS Witholding">KWIRS Witholding</option>
                <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                <option value="KWIRS PAYE">KWIRS PAYE</option>
                <option value="Unilorin Endowment">Unilorin Endowment</option>
               </select>
               </td>
              <td width="79" nowrap="nowrap"><input type="number" id="dvat_acct" name="dvat_acct" value="" style="width:100px" /></td>
              <td width="79" nowrap="nowrap"><select name="dvat_bank" id="dvat_bank" style="width:100px">
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
              <td width="79" height="36" nowrap="nowrap"><label for="dvat_pv"><input type="checkbox" id="dvat_pv" name="dvat_pv" value="yes" >Create PV.</label></td>
              <td width="79" align="center" valign="middle" nowrap="nowrap" class="deduction" id="dvat_val" style="">0</td>
              </tr>
            <tr>
              <th height="36" align="left" nowrap="nowrap">Withold. Tax (%):</th>
              <td height="36"><input type="number" id="dtax" name="dtax" value="0" min="0" max="100" onChange=" 		
              var dvat=$('#dvat').val()*1;			
              	var dtax=$('#dtax').val()*1;
				var amt=$('#vamount').val()*1;
				var val_calc=0;	var total_ded=0;
                var tval = $('#dtax_val').html() * 1;
				//val_calc=(amt/100) * dtax;
                //alert(dvat + ', ' + dtax + ', ' + amt + ', ' + dtax + ', ' + tval);
                if(dtax >= 0){
                    if($('#dvat_inc').prop('checked') == true){
                        val_calc=(dtax/(dvat + 100))*amt;
                    }
                    else if($('#dtax_inc').prop('checked') == false){
                        val_calc=(dtax/100)*amt;
                    }
                    
                    $('#dtax_val').html(val_calc.toFixed(2));
                    total_ded = (amt - (($('#dvat_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                    $('#total_deduction').html(total_ded.toFixed(2));
            }else{
            	total_ded = ($('#total_deduction').html() * 1) - ($('#dtax_val').html() * 1);
                $('#dtax_val').html(0);	
                $('#total_deduction').html(total_ded.toFixed(2));
            } ">
            <input type="hidden" id="tax_code" name="tax_code" value="41030102" /></td>
            <td nowrap="nowrap"><select name="dtax_payee" id="dtax_payee" style="width:100px">
				<option selected="selected" value="">---</option>
                <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                <option value="VAT Sub Account">VAT Sub Account</option>
                <option value="KWIRS Witholding">KWIRS Witholding</option>
                <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                <option value="KWIRS PAYE">KWIRS PAYE</option>
                <option value="Unilorin Endowment">Unilorin Endowment</option>
               </select>
               </td>
              <td><input type="number" id="dtax_acct" name="dtax_acct" value="" style="width:100px" /></td>
              <td><select name="dtax_bank" id="dtax_bank" style="width:100px">
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
               <td height="36"><label for="dtax_pv"><input type="checkbox" id="dtax_pv" name="dtax_pv" value="yes" >Create PV.</label></td>
               <td align="center" valign="middle" class="deduction" id="dtax_val">0</td>
               </tr>
            <tr>
              <th height="36" align="left" nowrap="nowrap">Endowment (%):</th>
              <td height="36"><input type="number" id="dendowment" name="dendowment" value="0" min="0" max="100" onChange="
              var dvat=$('#dvat').val()*1;
              var dend=$('#dendowment').val()*1;
				var amt=$('#vamount').val()*1;
				var val_calc=0;	var total_ded=0;
				//val_calc=(amt/100) * dend;
                
                if(dend >= 0){
                    if($('#dvat_inc').prop('checked') == true){
                        val_calc=(dend/(dvat + 100))*amt;
                    }
                    else if($('#dtax_inc').prop('checked') == false){
                        val_calc=(dend/100)*amt;
                    }
                    $('#dendowment_val').html(val_calc.toFixed(2));	
                    total_ded = (amt - (($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                    $('#total_deduction').html(total_ded.toFixed(2));
            }else{
            	total_ded = ($('#total_deduction').html() * 1) - ($('#dendowment_val').html() * 1);
                $('#dendowment_val').html(0);			
                $('#total_deduction').html(total_ded.toFixed(2));
            }"></td>
              <td nowrap="nowrap"><select name="dend_payee" id="dend_payee" style="width:100px">
				<option selected="selected" value="">---</option>
                <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                <option value="VAT Sub Account">VAT Sub Account</option>
                <option value="KWIRS Witholding">KWIRS Witholding</option>
                <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                <option value="KWIRS PAYE">KWIRS PAYE</option>
                <option value="Unilorin Endowment">Unilorin Endowment</option>
               </select>
               </td>
              <td><input type="number" id="dendowment_acct" name="dendowment_acct" value="" style="width:100px" /></td>
              <td><select name="dendowment_bank" id="dendowment_bank" style="width:100px">
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
               <td height="36"><label for="dendowment_pv"><input type="checkbox" id="dendowment_pv" name="dendowment_pv" value="yes" >Create PV.</label><input type="hidden" id="end_code" name="end_code" value="41-002-4056" /></td>
               <td align="center" valign="middle" class="deduction" id="dendowment_val">0</td>
               </tr>
            <tr>
              <th height="36" align="left" nowrap="nowrap">Stamp Duty (%):</th>
              <td height="36"><input type="number" id="dstamp" name="dstamp" value="0" min="0" max="100" onChange="
              var dvat=$('#dvat').val()*1;
              var dstamp=$('#dstamp').val()*1;
				var amt=$('#vamount').val()*1;
				var val_calc=0;	var total_stamp=0;
				//val_calc=(amt/100) * dstamp;
                
                if(dstamp >= 0){
                    if($('#dvat_inc').prop('checked') == true){
                        val_calc=(dstamp/(dvat + 100))*amt;
                    }
                    else if($('#dtax_inc').prop('checked') == false){
                        val_calc=(dstamp/100)*amt;
                    }
                    $('#dstamp_val').html(val_calc.toFixed(2));	
                    total_stamp = (amt - (($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                    $('#total_deduction').html(total_stamp.toFixed(2));
            }else{
            	total_ded = ($('#total_deduction').html() *1) + ($('#dstamp_val').html() * 1);
                $('#dstamp_val').html(0);			
                $('#total_deduction').html(total_stamp.toFixed(2));
            }"></td>
              <td nowrap="nowrap"><select name="dstamp_payee" id="dstamp_payee" style="width:100px">
				<option selected="selected" value="">---</option>
                <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                <option value="VAT Sub Account">VAT Sub Account</option>
                <option value="KWIRS Witholding">KWIRS Witholding</option>
                <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                <option value="KWIRS PAYE">KWIRS PAYE</option>
                <option value="Unilorin Endowment">Unilorin Endowment</option>
               </select>
               </td>
              <td><input type="number" id="dstamp_acct" name="dstamp_acct" value="" style="width:100px" /></td>
              <td><select name="dstamp_bank" id="dstamp_bank" style="width:100px">
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
               <td height="36"><label for="dstamp_pv"><input type="checkbox" id="dstamp_pv" name="dstamp_pv" value="yes" >Create PV.</label><input type="hidden" id="stamp_code" name="stamp_code" value="41-002-4056" /></td>
               <td align="center" valign="middle" class="deduction" id="dstamp_val">0</td>
               </tr>
        </table>
</td>
</tr>
				<tr style="font-size: 18px;font-weight: bold;color:#174C68;" height="33"><td>&nbsp;</td>
				  <th>GROSS AMOUNT:</th><th align="center" valign="bottom">
<div id="total" align="center" ><b>0.00</b></div></th></tr>

				<tr style="font-size: 18px;font-weight: bold;color:#174C68;"><th><input type="checkbox" id="dvat_inc" name="dvat_inc" onChange="
            if(($('#dvat').val() * 1) >= 0){
              
				var dvat=$('#dvat').val()*1;
				var amt=$('#vamount').val()*1;
				var val_calc=0;	var total_ded=0;

				var dtax=$('#dtax').val()*1;
				var wht_calc=0;
				
              	var dend=$('#dendowment').val()*1;
				var end_calc=0;

              	var dstamp=$('#dstamp').val()*1;
				var stamp_calc=0;

            	if($(this).prop('checked') == true){
                	//compute VAT
            		val_calc=(dvat/(dvat + 100))*amt;
                    //compute WHT
                    wht_calc=((dtax/(dvat + 100))*amt);
                    //compute endowment
                    end_calc=(dend/(dvat + 100))*amt;
                    //compute Stamp Duty
                    stamp_calc=(dstamp/(dvat + 100))*amt;
            	}else if($(this).prop('checked') == false){
                	//compute VAT
            		val_calc=(dvat/100)*amt;
                    //compute WHT
                    wht_calc=(dtax/100)*amt;
                    //compute endowment
                    end_calc=(dend/100)*amt;
                    //compute Stamp Duty
                    stamp_calc=(dstamp/100)*amt;
            	}
                //VAT output
                $('#dvat_val').html(val_calc.toFixed(2));
                //WHT output
                $('#dtax_val').html(wht_calc.toFixed(2));
                //ENDOWMENT output
                $('#dendowment_val').html(end_calc.toFixed(2));	
                //STAMP DUTY output
                $('#dstamp_val').html(stamp_calc.toFixed(2));	
                
                total_ded = (amt - (($('#dvat_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                $('#total_deduction').html(total_ded.toFixed(2));
		}" value="yes">
                <strong>Incl. VAT</strong><input type="hidden" id="vat_code" name="vat_code" value="41030103" /></th>
				  <th height="33">NET AMOUNT:</th><th align="center" valign="bottom">
                
<div id="total_deduction" align="center" ><b>0.00</b></div></th></tr>
	

<tr><th colspan="3">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('voucher_section_entry_final', 'save'); " />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('voucher_section_entry_final', 'refresh'); " />
				
				
				</th></tr>
<tr>
  <th colspan="3" align="left" valign="top" id="display2" style="text-align:justify">&nbsp;</th>
</tr>
         </table>
		
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
function calculateDeducation(dedRate, principal, type, VAT){
	//var dvat=$('#dvat').val()*1;
  	//var dstamp=$('#dstamp').val()*1;
	var amt=$('#vamount').val()*1;
	var val_calc=0;	var totalDeduction=0;
	//val_calc=(amt/100) * dstamp;
	
	if(dedRate > 0 && principal > 0){
		if(type == true){
			//VAT inclusive
			val_calc=(dedRate/(VAT + 100)) * principal;
		}
		else if(type == false){
			//VAT exclusive
			val_calc=(dedRate/100) * principal;
		}
		
		$('#dstamp_val').html(val_calc.toFixed(2));	
		total_stamp = (amt - (($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
		$('#total_deduction').html(total_stamp.toFixed(2));
}else{
	total_ded = ($('#total_deduction').html() *1) + ($('#dstamp_val').html() * 1);
	$('#dstamp_val').html(0);			
	$('#total_deduction').html(total_stamp.toFixed(2));
}
}

function vatInclusive(){
	if(dedRate * 1 >= 0){			
		var dvat=$('#dvat').val()*1;
		var amt=$('#vamount').val()*1;
		var val_calc=0;	var total_ded=0;

		var dtax=$('#dtax').val()*1;
		var wht_calc=0;
		
		var dend=$('#dendowment').val()*1;
		var end_calc=0;

		var dstamp=$('#dstamp').val()*1;
		var stamp_calc=0;


            	if($(this).prop('checked') == true){
                	//compute VAT
            		val_calc=(dvat/(dvat + 100))*amt;
                    //compute WHT
                    wht_calc=((dtax/(dvat + 100))*amt);
                    //compute endowment
                    end_calc=(dend/(dvat + 100))*amt;
                    //compute Stamp Duty
                    stamp_calc=(dstamp/(dvat + 100))*amt;
            	}else if($(this).prop('checked') == false){
                	//compute VAT
            		val_calc=(dvat/100)*amt;
                    //compute WHT
                    wht_calc=(dtax/100)*amt;
                    //compute endowment
                    end_calc=(dend/100)*amt;
                    //compute Stamp Duty
                    stamp_calc=(dstamp/100)*amt;
            	}
                //VAT output
                $('#dvat_val').html(val_calc.toFixed(2));
                //WHT output
                $('#dtax_val').html(wht_calc.toFixed(2));
                //ENDOWMENT output
                $('#dendowment_val').html(end_calc.toFixed(2));	
                //STAMP DUTY output
                $('#dstamp_val').html(stamp_calc.toFixed(2));	
                
                total_ded = (amt - (($('#dvat_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                $('#total_deduction').html(total_ded.toFixed(2));
		}
}

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