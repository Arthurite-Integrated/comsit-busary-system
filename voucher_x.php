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
.deduction{
	padding:5px;
	border:solid 1px #006666;
	font-weight:bold;
	background-color:#F5F5F5;
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
                if($('#type option:selected').val() == 'External' && $("#bank").val()==''){
					 	alert("All compulsory fields must be filled before you can proceed");
					 	$('#roll').html('').show();
					 	exit();
                     }
                //$("#account").val()=='' || 
				 if($("#pay_date").val()=='' || $("#dept").val()=='' || $("#pvno").val()=='' || $("#folio").val()=='' || $("#type").val()=='' || $("#desc").val()=='' || $("#vamount").val()=='' || $("#pro_typ").val()=='')
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
					  //swapcontent('voucher_section_entry','refresh');
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
function reload_folio(){
	//alert($('#folio').attr('data-options'));
	
	//$('#folio').attr('data-options', "Kasa:'Ased'{
		//},+-();");
		/*$('#folio').attr('data-options', "panelWidth: 600,multiple: true, idField: 'folio_code',textField: 'title', rownumbers: true, view:groupview, groupField:'categoryF', groupFormatter:function(value,rows){ return value + ' - ' + rows.length + ' Item(s)'; }, url: 'scriptfile_a.php?contentvar=grid&category=20', method: 'get', columns: [[ {field:'ck',checkbox:true}, {field:'folio_code',title:'UIL CODE',width:60}, {field:'title',title:'UIL TITLE',width:200}, {field:'ncoa_code',title:'NCOA CODE',width:50,align:'left'} ]], fitColumns: true, onChange:function(){swapcontent('folio_code_breakdown',this.value);$('#vamount').val('');$('#total').html(''); $('#total_deduction').html('');$('.class_tax').html('');}");*/
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
<body class="subpage" onLoad="$('#fileno').combogrid('StaffGrid').datagrid('load', {filen:''});">

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
               <div id="ppvno"><label for="pvno_paid"><strong>Payment Voucher Number:</strong>
                 <input type="text" id="pvno_paid" name="pvno_paid"></label>
               </div>
               <br>
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
              <br />	            <input type="text" name="pay_date" style="width: 300px" class="date" id="pay_date" onchange="swapcontent('generate_pvno',this.value);"/></th></tr>
		    <tr>
		      <th height="50" align="left" bgcolor="#F1F1F1" ><input type="hidden" value="" name="account" id="account" >
              <!--<div align="left">Department:<br />
                <select name="dept" id="dept" style="width: 300px">
                  <option selected="selected" value="">---</option>
                    <?php
                          //$res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
						  $res_c=@mysqli_query($con, "select * from unittb order by unit_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['unit_code']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['unit_name']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name</option>";
                           }
						 ?>
                </select>
	          <br />
		      </div>-->
              <div align="left">Account to be Debited:<br />
                <select name="account" id="account" class="txt" style="width: 300px"  >
                  <option selected="selected">---</option>
                  <!--<option value="TSA">TSA</option>
                  <option value="GIFMIS">GIFMIS</option>-->
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
		    <!--<tr>
			
		      <th height="50" align="left" bgcolor="#F1F1F1"></th>
		      <th height="50" align="right" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="center" valign="middle" bgcolor="#F1F1F1"> </th>
	        </tr>-->
		    <tr bgcolor="#6BF4C7">
		      <th height="50" colspan="3" align="center" valign="middle"><div><table width="100%" border="0" cellspacing="0" cellpadding="0">
		          <tr>
		            <th width="25%" height="33" align="center">Vote Source</th>
		            <th width="25%" height="33" align="center">Code Category</th>
		            <th width="25%" height="33" align="center">Dept. Category</th>
		            <th width="25%" height="33" align="center">Department/Unit</th>
		            <!--<td height="33">Item</td>-->
		            
		            </tr>
		          <tr>
		            <td width="25%" height="33" align="center"><select name="fundsource" id="fundsource" onChange="swapcontent('load_category')" style="width:120px;">
		              <option selected="selected" value="">---</option>
		              <?php
						  //$res_c=@mysqli_query($con, "select * from account_funds order by fund_code");
						  $res_c=@mysqli_query($con, "select * from account_funds order by fund_code");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['fund_code']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['fund_name']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name ($dept_code)</option>";
                           }
                          echo "</select>";
						 ?>
		              </select></td>
		            <td width="25%" height="33" align="center"><div id="load_category">
                    <select name="fundcat" id="fundcat" onChange="swapcontent('load_items_code');" style="width:120px;">
		              <option selected="selected" value="">---</option>
		              <?php
                          //$res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
						  $res_c=@mysqli_query($con, "select * from folio_categorytb order by folio_category");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['id']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['folio_category']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name</option>";
                           }
                          echo "</select>";
						 ?>
		              </select></div></td>
		            <td width="25%" height="33" align="center"><select name="funddept_head" id="funddept_head" onChange="swapcontent('load_dept_account');" style="width:120px;">
		              <option selected="selected" value="">---</option>
		              <?php
                          //$res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
						  $res_c=@mysqli_query($con, "select distinct department_category from account_departments order by department_category");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['department_category']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['department_category']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name</option>";
                           }
                          echo "</select>";
						 ?>
		              </select></td>
                    <td width="25%" height="33" align="center"><div id="load_dept_account">
                    <select name="funddept" id="funddept" onChange="" style="width:120px;">
		              <option selected="selected" value="">---</option>
		              
		              </select></div>
                      </td>
		            <!--<td height="33"><div id="load_items_code">
                    <select name="funditem" id="funditem" onChange="" style="width:120px;">
		              <option selected="selected" value="">---</option>
		              </select></div></td>-->
		            
		            </tr>
		          <tr>
		            <td height="33" colspan="4" align="center"><button type="button" onClick="reload_folio()">LOAD CODES</button></td>
		            </tr>
		          <tr>
		            <td width="25%" align="center">&nbsp;</td>
		            <!--<td>&nbsp;</td>-->
		            <td colspan="2" align="center" id="c_item">&nbsp;</td>
		            <td width="25%" align="center">&nbsp;</td>
                    <!--<td>&nbsp;</td>-->
		            </tr>
		          </table>
		      </div>
		        <p>Item Code Selection<br />
		          
<select name="folio[]" id="folio" class="txt easyui-combogrid" data-options="panelWidth: 600,
            multiple: true,
            idField: 'folio_code',
            textField: 'title',
			rownumbers:true,
			view:groupview,
            groupField:'categoryF',
            groupFormatter:function(value,rows){
                    return value + ' - ' + rows.length + ' Item(s)';
                },
            url: 'scriptfile_a.php?contentvar=grid',
            method: 'get',
            columns: [[
                {field:'ck',checkbox:true},
                {field:'folio_code',title:'UIL CODE',width:60},
                {field:'title',title:'UIL TITLE',width:200},
                {field:'ncoa_code',title:'NCOA CODE',width:50,align:'left'}
            ]],
            fitColumns: true,
			
			onChange:function(){swapcontent('folio_code_breakdown',this.value);$('#vamount').val('');$('#total').html('');
		$('#total_deduction').html('');$('.class_tax').html('');}" style="width:620px;height:20px" >
  <!-- onblur="swapcontent('folio_code_breakdown',this.value)" onchange="swapcontent('folio_code_breakdown',this.value);swapcontent('load_budget');">
			
			onSelect:function(){swapcontent('folio_code_breakdown',this.value);$('#vamount').val('');$('#total').html('');
		$('#total_deduction').html('');$('.class_tax').html('');},
			 onUnselect:function(){swapcontent('folio_code_breakdown',this.value);$('#vamount').val('');$('#total').html('');
		$('#total_deduction').html('');$('.class_tax').html('');}
			
			-->
  
</select>		          
		          </p>
		        <div id="load_budget">
		          <br>
            </div></th>
	        </tr>
		    <tr>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Type of Payee<br />
<select name="type" id="type" class="txt" style="width: 300px" onchange="swapcontent('load_voucher_fileno',this.value);swapcontent('load_voucher_details_entry_final',this.value); if($('#type option:selected').val() == 'Internal'){ $('#ptin_row').hide(); }else{ $('#ptin_row').show(); } ">
		        <option selected="selected" value="">---</option>
		        <option value="Internal">Internal/Staff</option>
		        <option value="External">External/Non-Staff</option>
	          </select></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"> <div id='load_voucher_fileno' align='left'></div>
			  <!--b><span id='load_voucher_fileno' align='left'>File No.(Staff): </span></b><br/>
              <!--select name="fileno" id="fileno" class="txt easyui-combogrid" data-options="panelWidth: 300,
                    idField: 'fileno',
                    textField: 'staffname',
                    rownumbers:true,
                    url: 'scriptfile_a.php?contentvar=staffGrid',
                    method: 'get',
                    columns: [[
                        {field:'fileno',title:'Staff ID',width:80},
                        {field:'staffname',title:'Staff Name',width:120}
                    ]],
                    fitColumns: true,
                    label: 'Select Item:',
                    labelPosition: 'top'
				" style="width:300px;height:20px" >
</select-->
	
            	<!--select class="easyui-combobox" name="fileno" id="fileno" style="width:300px;">
                	<option value=""></option>
                    <?php
                    $res_s=@mysqli_query($con, "select fileno,surname,first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') order by surname");//convert(fileno,decimal)
	   while($rs_s=@mysqli_fetch_array($res_s))
	    {
			$fileno=$rs_s['fileno'];
			$name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
			echo "<option value='$fileno'>$fileno || $name</option>";
		}
        ?>
            	</select-->
    
    
              </th>
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
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">Payee Address<br />		        <input style='width:300px' name="address" type="text" id="address" size="40" /></th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
		      <th height="50" align="left" valign="middle" bgcolor="#F1F1F1"></th>
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
          </table>
		  <br />
		 <div id="folio_code_breakdown" style='width:700px;' class='easyui-panel' title='Folio / Code Breakdown'></div> 
         
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