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
  if(cv=='generate_jno') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var pay_date=$("#pay_date").val(); 
	  var vunit=$("#journal_unit").val();
	  if(vunit=="")
	  	{
			alert('Select the Journal Entry Unit first');
			$("#pay_date").val('');
			$("#roll").html('').show();
			exit();
		}
		$.post(url,{contentvar:cv,pay_date:pay_date,journal_unit:vunit},function(data){
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
   
 if(cv=='search_voucher')
 {
	 //search for voucher given voucher pvno
	 
	var pvno=prompt("Enter the System Processing Number of the voucher");
	if(pvno=='')
	  { alert('No Journal System Processing Number provided'); exit();}
			  
	$.post(url,{contentvar:cv,pvno:pvno},function(data){	
    //TINY.box.show(data,0,0,0,0);
	//document.location=data;
	window.open(data,'Journal','width=750,height=auto');
	$(divid).html('').show();
    $('#roll').html('').show();
	});		
 } //end of search voucher
 
 if(cv=='search_journal_by_jno')
 {
	 //search for voucher given voucher pvno
	 
	var pvno=prompt("Enter the Journal No.");
	
	if(pvno=='')
	  { alert('No Journal Number provided'); exit();}
			  
	$.post(url,{contentvar:cv,pvno:pvno},function(data){	
    //TINY.box.show(data,0,0,0,0);
	//document.location=data;
	window.open(data,'Journal','width=750,height=auto');
	$(divid).html('').show();
    $('#roll').html('').show();
	});		
 } //end of search voucher
 
  if(cv=='get_pv_detail'){
	var pvno=$("#pvno").val();
	
	if(pvno=='')
	  { 
	  	alert('Enter PV. No.'); 
		exit();
	  }
		//alert(cv);	  
	$.post(url,{contentvar:cv,pvno:pvno},function(data){	
		$(divid).html(data).show();
		$('#roll').html('').show();
	});		
 } //end of search voucher


 if(cv=='journal_section_entry') //start putme_login
  {
			if(v=='save')
			 {
				 if($("#pay_date").val()=='' || $("#dept").val()=='' || $("#jno").val()=='' || $("#account").val()=='' || $("#folio").val()=='' || $("#type").val()=='' || $("#desc").val()=='' || $("#vamount").val()=='' || $("#pro_typ").val()=='')
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
					  //swapcontent('journal_section_entry','refresh');
					$("#pay_date").val(''); $("#dept").val(''); $("#jno").val(''); $("#account").val('');
					$("#folio").val(''); $("#type").val(''); $("#fileno").val(''); $("#name").val('');$("#act_no").val('');
					$("#bank").val(''); $("#address").val(''); $("#payee_tin_number").val(''); $("#payee_sort_code").val('');
					  
				}
			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					//$('#display2').html('').show();
					$('#load_journal_fileno').html('').show();
					$('#generate_jno').html('').show();
					$('#load_budget').html('').show();
					$('#roll').html('').show();
					$("#pay_date").val(''); $("#dept").val(''); $("#jno").val(''); $("#account").val('');
					$("#folio").val(''); $("#type").val(''); $("#fileno").val(''); $("#name").val('');$("#act_no").val('');
					$("#bank").val(''); $("#address").val(''); $("#payee_tin_number").val(''); $("#payee_sort_code").val('');
					//exit();
				  }//end of refresh div i.e to refresh the data dispay previously on selection of another department
				  
			if(v=='edit')
				  {
						var pData=jQuery.parseJSON(data); 
						alert(pData.s_detail);
						var p=jQuery.parseJSON(pData.s_detail);
						
						$("#sch_code").val(p.sch_code); $("#dept_code").val(p.dept_code); $("#dept_name").val(p.dept_name);
						$("#category").val(p.category); $("#r_id_edit").val(p.r_id); 
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

  if(cv=='load_items_code')
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
  }//end of load_items_code

if(cv=='reload_folio')
  {
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
	alert(category+"-"+fundcenter+"-"+deptcode);
	
		$.post(url,{contentvar:cv,fundcenter:fundcenter, deptcode:deptcode, category:category},function(data){
			$('#folio').combogrid('grid').datagrid('load', {category:category, fundcenter:fundcenter, deptcode:deptcode});
			$("#roll").html('').show();
			//$(divid).html(data).show();		
		});
	//document.getElementById('folio').

  }
} //end of swapcontent


 </script>

<script>
function reload_foliox(){
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
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
	        <h2>Raise Journal</h2>
                <p>&nbsp;</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
              <div class="easyui-panel" title="New Journal Entry" style="width:800px">     
			  	<div style="padding:10px 60px 20px 60px">             
               <form enctype="multipart/form-data">
		  <table border="0" cellpadding="0" cellspacing="0" class="vch">
		    <tr>
		      <td colspan="3"><div align="righdt"><input type="button" class="easyui-linkbutton" name="chbtn1" id="chsbtn1" value="My Entries" onclick="swapcontent('journal_section_entry','view');" />
		          <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('search_journal_by_jno');"><strong><font color="#000099">Search by Journal No.</font></strong></a>
		          <input name="pro_typ" type="hidden" id="pro_typ" size="25" value="Pending" />
		      </div>
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
					$from=@$rst['memo_from'];$description=@$rst['description'];$amount=@$rst['amount'];$amount_approved=@$rst['amount_approved'];$remark=@$rst['remark'];
					$datein=@$rst['datein'];$file_path=@$rst['file_path'];
					?>
                    <div id="" style='width:700px;' class='easyui-panel' title='MEMO DETAILS'>
                    <table width="100%"><tr><td width="50%"><?php echo "Memo ID : $memo_id"; ?></td><td><?php echo "Date : $datein"; ?></td></tr>
                    		<tr><td><?php echo "From : $from"; ?></td><td><?php echo "Memo Amount : $amount"; ?></td></tr>
                            <tr><td colspan='2'><?php echo "Description : $description"; ?></td></tr></table>
				
			   
			   
			 <p align="right"> <a class='iframe easyui-linkbutton' iconCls="icon-tip" href="<?php echo $file_path;?>"><strong><font color="#000099">View Attached Document</font></strong></a></p>
              </div>
			   <?php }?>
               <br>
			  </td>
	        </tr>

			<tr>
			  <th height="40" align="left" bgcolor="#F1F1F1">Entry Unit:<br />
			<input name="memo_id" type="hidden" id="memo_id" value="<?php echo $memo_id;?>" size="25" />
			<input name="amt_approved" type="hidden" id="amt_approved" value="<?php echo $amount_approved;?>" size="25" />
			<select name="journal_unit" id="journal_unit" style="width: 300px" onchange="swapcontent('refresh',this.value);">
                  <option selected="selected" value="">---</option>
				  <option value="SLA">Salary, Loans, Advances and Procurement</option>
				  <option value="BEC">Budget, Expenditure Control and Stores</option>
                  <option value="FAC">Final Account</option>
			</select>
			</th>
			  <th height="40" bgcolor="#F1F1F1"></th>
			  <th height="40" align="left" bgcolor="#F1F1F1">Transaction Date:
              <br />	            <input type="text" name="pay_date" style="width: 300px" class="date" id="pay_date" /></th></tr>
		    <tr>
		      <th height="40" colspan="3" align="center" ><div id="div">
		        <label for="pvno"><strong>PV. No.:</strong>
		          <input type="text" id="pvno" name="pvno">
		          </label>
		        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('get_pv_detail');"><strong><font color="#000099">GET PV</font></strong></a> </div></th>
		      </tr>
		    <tr>
		      <td height="40" colspan="3" align="center" ><div id="get_pv_detail"></div></td>
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