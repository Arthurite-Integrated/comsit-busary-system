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
  
 if(cv=='salary_staus_section') //start
  {
	  var complex_action = v.split(':');	var v_2 = complex_action[0];	var fileno_2 = complex_action[1];	var folio_2 = complex_action[2];
			if(v_2 =='searchx'){
				//$("#fileno").val(fileno_2); 
				v='search';
				//alert(folio_2+":"+v); exit;
			}

			if(v=='save')
			 {
				 if($("#fileno").val()=='' || $("#start_date").val()=='' || $("#pstatus").val()=='')
				 {
					 alert($("#frm").serialize()); exit;
					 alert("All compulsory fields must be filled before you can proceed.");
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			
			if(v=='search')
			 {
				 if($("#fileno").val()=='' && $("#pstatus").val()=='')
				 {
					 alert("You need to select File Number and/or Status to search record!");
					 $(divid).html('').show();
					 $('#roll').html('').show();
					 exit();
				 }
			 }
			 
			 if(v=='delete')
			 {
				 /*if($("#fileno").val()=='' || $("#start_date").val()=='' || $("#pstatus").val()=='')
				 {
					 alert("Please search the record you want to delete first before clicking on delete button");
					 $(divid).html('').show();
					 $('#roll').html('').show();
					 exit();
				 }*/
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
							$("#reason").text('');
							$("#pstatus").val('');
							alert('Error: The specified record does not exist');
						 }
						else
						 {
							$("#start_date").val(p.start_date); 
							$("#end_date").val(p.end_date);
							$("#reason").val(p.reason);
							$("#pstatus").val(p.status);
						 }
						 $('#roll').html('').show();
						 exit();
			 }
			 
			$(divid).html('').show();	
			$('#display2').html(data).show();
			$('#roll').html('').show();
			});
			 
			if(v=='save' || v=='delete')
			    {
					  //swapcontent('salary_staus_section','refresh');
					$("#fileno").val('');
					//$("#type").val('');
					$("#start_date").val('');
					$("#end_date").val('');
					$("#reason").val('');
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
	        <h2>Edit Salary Payment Status</h2>
                <p>Activate, Suspend or Stop specific staf salary.</p>
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
		        <select name="fileno" id="fileno" style="width:450px" onChange="if($('#fileno').val() != ''){ swapcontent('salary_staus_section','view'); } ">
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
		      <th width="20%" rowspan="4" align="left" valign="top"><p>&nbsp;</p></th>
		      </tr>
		    <tr>
		      <th width="20%" align="left" valign="top">Start From:<strong style="color:#F00">*</strong> <br />
		        <input name="start_date" type="text" id="start_date" size="25" style="width:220px" /></th>
		      <th width="20%" align="left" valign="top">To:<strong style="color:#F00">*</strong> <br />
		        <input name="end_date" type="text" id="end_date" size="25" style="width:220px" /></th>
		      </tr>
		    <tr>
		      <th colspan="2" align="left" valign="top">Reason:<br>
                <textarea name="reason" id="reason" style="width:445px" rows="3"></textarea></th>
		      </tr>
		    <tr>
		      <th align="left" valign="top">Status:<strong style="color:#F00">*</strong><br>
                <select name="pstatus" id="pstatus" size="4" style="width:220px">
                  <option>Select Status...</option>
                  <option value="Suspend">Suspend</option>
                  <option value="Stop">Stop</option>
                </select></th>
		      <th align="left" valign="middle" nowrap="nowrap"><input type="button" name="savebutton" id="savebutton" value="Save/Update" class="btn" onClick="swapcontent('salary_staus_section','save','');"/>
		        <input type="button" name="button5" id="button5" value="View All" class="btn" onClick="swapcontent('salary_staus_section','view');"/>
		        <input type="button" name="button3" id="button3" value="Refresh" class="btn" onClick="swapcontent('salary_staus_section','refresh','');"/></th>
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
		      <th colspan="2">&nbsp;</th>
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