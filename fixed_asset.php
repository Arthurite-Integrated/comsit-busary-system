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
 $grn=$_REQUEST['grn'];



	//@require_once "myclass_m.php";
	//@$bursary = new myclass_m();
	
	//@$udept = $bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
	
	?>
<!DOCTYPE html>
<html><head>
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
<?php 
if ($grn == 'nil'){
echo "<script>alert('Alert: Kindly select an Option'); window.close();</script>";
exit;
}

if ($grn == 'new')
{ 
$gh = 0;			  
echo $dat = date('Y');
echo $dat2 = strtoupper(date('M'));
$res_t= mysqli_query($con, "select * from grn_sivtb where grn like '%/$dat/%'") or die ( mysqli_error($con));
 				$gh=  mysqli_num_rows($res_t);
				$gh2= $gh +1;
				$new_grn = 'GRN/'.$dat.'/'.$dat2.'/'.$gh2;
				 $new_siv = 'SIV/'.$dat.'/'.$dat2.'/'.$gh2;
 				//exit;
//  mysqli_query($con, "insert into grn_sivtb set grn='$new_grn',siv='$new_siv',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
	
	} else {
$res_t= mysqli_query($con, "select * from grn_sivtb where grn = '$grn'");
 				$rs_t=  mysqli_fetch_array($res_t);	$new_grn = 	$rs_t['grn']; $new_siv = 	$rs_t['siv'];
		}
?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_y.php";
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
  if(cv=="inmails" ) // in mails
		{
			//alert(1234567890); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			//alert('12345'); exit;
			//var addr = $('#address').val();
			// .tabs('getTabIndex',tab);
			 var test = (JSON.stringify($('#inmail').serializeObject()));
				//alert(h); //exit;
//alert(o);
			//if(a=="" || v=="" || b=="" || c=="" || d=="" || f=="" || g=="")
			if(a=="" || v=="" || b=="")
			 {
				  alert('Complete all the fields ');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  } //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#inmail').serializeObject()));
	   ////$("#show_ref").html('').show(); 
	  //alert (test);
	 //exit;
	 
		$.post(url,$("#inmail").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		
		$(divid).html(data).show(); 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		//upload_supporting_doc(document.getElementById("memo_id").value);
		//pload_supporting_doc( $("#inmail").serialize()+"&contentvar="+cv+"&tabindex="+index+"&files" );
		});
		
/*	   		$.post(url,{contentvar:cv, val:test},function(data) || c=="" || d=="" || e=="" || f=="" || g==""{
			$(divid).html(data).show();
			});
*/
//alert(data); exit;
/*
			$.post(url,{contentvar:cv, formcontent:test, tabindex:index},function(data){
			$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
			
			
			});*/
		}//end of in mails
  

  if(cv=="catdiv" ) // in mails
		{	
		//alert ("hi"); exit;
    $.post(url,{contentvar:cv,cat_type:v },function(data){
			$(divid).html(data).show(); 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		 //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
	}//end of in mails
	


 
 	if(cv=='editasset') //Edit and update memodisposal
		{
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			var test = (JSON.stringify($('#editmail').serializeObject()));
			var test = (JSON.stringify($('#editmail').serializeObject()));
			$.post(url,$("#editmail").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  
				$(divid).html(data).show(); 
			});
		}//end of outgoing
if(cv=='disposed') //Edit and update memodisposal
		{
			//alert ("hi"); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			var date_dis = $('#date_dis').datebox('getValue');
  	  	var d1 = Date.parse(date_dis);
			var test = (JSON.stringify($('#disposal').serializeObject()));
			var test = (JSON.stringify($('#disposal').serializeObject()));//sdate1:date_aq
			$.post(url,$("#disposal").serialize()+"&contentvar="+cv+"&sdate1="+date_dis+"&tabindex="+index,function(data){  
				$(divid).html(data).show(); 
			});
		}//end of outgoing
		
if(cv=='fixed_asset_save') //start of save asset
  {
	//  alert(cv+" "+v+" "+a); //exit();
	 // alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
//if((v=='save' )&&($('#asset_title').val()=='' || $('#asset_code').val()=='' )) //$('#transdate').val()=='' || || $('#fileno').val()=='' 
			/*{
				alert('All fields are required ');
				$(divid).html('').show();	
				$('#display').html('').show();
				$('#roll').html('').show();
				exit();
			}*/
		var date_aq = $('#date_aq').datebox('getValue');
  	  	var d1 = Date.parse(date_aq);
	  var mydata = (JSON.stringify($('#frm').serializeObject()));
	 
		//$.post(url,$("frm").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		//$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
			//a=
		$.post(url,{contentvar:cv,action:v,sdate1:date_aq,  mydata:mydata},function(data){
		//$.post(url, $("frm").serialize()+"&contentvar="+cv+"&action="+v, function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of save asset
  
  if(cv=='assignmail_looks') // Sharing mail
		{
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			//if(a=="" )
			//if( || v=="")
			 {
				//  alert('Complete all the fields ');
				//  $(divid).html('').show();  //stop loader from rolling
				//  exit();
			  } //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#assignmailss').serializeObject()));
	   ////$("#show_ref").html('').show();
	  
		$.post(url,$("#assignmailss").serialize()+"&contentvar="+cv+"&action="+v+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		});
		}//end of sharing mail
  }//end of swapcontent
 </script>

	<script>
	function showBio(str){
	//show Bio div
	if(str == 'No') {
		$("#qtys").show();
		$("#qtyss").show();
		//$("#degree").show();
	}else {
		$("#qtys").hide();
		$("#qtyss").hide();
		//$("#desgree").hide();
	}
}
	//$(function(){
		 //  $("#date_aqq").datepicker({dateFormat:"yy-mm-dd"});
		   //$("#end_date").datepicker({dateFormat:"yy-mm-dd"});
		 //  }
		  // );
		function getSelected(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				//alert('Memo ID:'+row.identify_string+"\nAmount:"+row.amount); exit;
				document.getElementById('tidentifystring').innerHTML=row.identify_string;
				document.getElementById('hidentifystring').value=row.identify_string;
				document.getElementById('eidentifystring').value=row.identify_string;
				//document.getElementById('acq_date').value=row.acq_date;
				document.getElementById('acq_date').innerHTML=row.acq_date;
				document.getElementById('hdescription').value=row.descritption;
				//document.getElementById('hdept').value=row.dept;
				document.getElementById('hroomno').innerHTML=row.room_no;
				document.getElementById('htypetitle').value=row.type_title;
				//alert(row.cat_title);
				document.getElementById('hcattitle').value=row.cat_title;
				//$('#hcatttitle').text(row.cat_title);
				document.getElementById('hamount').value=row.amount;
				document.getElementById('hstatus').innerHTML=row.status;
				document.getElementById('hdeleted').innerHTML=row.deleted;
				document.getElementById('hdisposal').innerHTML=row.disposal;
				document.getElementById('hunits').innerHTML=row.units;
				document.getElementById('hdept').innerHTML=row.dept;
				document.getElementById('iidentifystring').innerHTML=row.identify_string;
				
				//document.getElementById('acq_date').value=row.acq_date;
				document.getElementById('jacq_date').innerHTML=row.acq_date;
				document.getElementById('jdescription').innerHTML=row.descritption;
				//document.getElementById('hdept').value=row.dept;
				
				/*$('#idoc').attr("href", row.memo_id.replace('/', '') );
				$('#idoc').attr("href", $('#idoc').attr("href").replace('/', '') );
				$('#idoc').attr("href", "upload_files/" + $('#idoc').attr("href").replace('/', '') + ".pdf" );
				
				document.getElementById('hidentifystring').innerHTML=row.identify_string;
				document.getElementById('hdescritption').innerHTML=row.descritption;
				document.getElementById('hdept').innerHTML=row.dept;
				document.getElementById('hroomno').innerHTML=row.room_no;
				document.getElementById('htypetitle').innerHTML=row.type_title;
				document.getElementById('hcattitle').innerHTML=row.cat_title;
				document.getElementById('hamount').innerHTML=row.amount;
				document.getElementById('hstatus').innerHTML=row.status;
				document.getElementById('hunits').innerHTML=row.units;*/
				
				/*document.getElementById('vmemoid').innerHTML=row.memo_id;
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
				
				/*$('#vidoc').attr("href", row.memo_id.replace('/', '') );
				$('#vidoc').attr("href", $('#vidoc').attr("href").replace('/', '') );
				$('#vidoc').attr("href", "upload_files/" + $('#vidoc').attr("href").replace('/', '') + ".pdf" );*/
				//swapcontent('memo_movement', row.memo_id, row.amount);
				
			}
		}
	function getSelected_del(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				//alert('identify_string:'+row.identify_string+"\nAmount:"+row.amount); exit;
				document.getElementById('didentifystring').value=row.identify_string;
			}
	}
		function getSelected_treated(){
			var row = $('#dgout').datagrid('getSelected');
			if (row){
				//alert('Memo ID:'+row.memo_id+"\nAmount:"+row.amount);
				document.getElementById('iidentifystring').innerHTML=row.identify_string;
				document.getElementById('jidentifystring').value=row.identify_string;
				//document.getElementById('acq_date').value=row.acq_date;
				document.getElementById('jacq_date').innerHTML=row.acq_date;
				document.getElementById('jdescription').value=row.descritption;
				//document.getElementById('hdept').value=row.dept;
				document.getElementById('jroomno').innerHTML=row.room_no;
				document.getElementById('jtypetitle').value=row.type_title;
				//alert(row.cat_title);
				document.getElementById('jcattitle').value=row.cat_title;
				//$('#hcatttitle').text(row.cat_title);
				document.getElementById('jamount').value=row.amount;
				document.getElementById('jstatus').innerHTML=row.status;
				document.getElementById('jdeleted').innerHTML=row.deleted;
				document.getElementById('jdisposal').innerHTML=row.disposal;
				document.getElementById('junits').innerHTML=row.units;
				document.getElementById('jdept').innerHTML=row.dept;
				/*document.getElementById('tmemoid').value=row.memo_id;
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
				$('#vidoc').attr("href", "upload_files/" + $('#vidoc').attr("href").replace('/', '') + ".pdf" );*/
				//swapcontent('memo_movement', row.memo_id, row.amount);
			}
		}
		function getSelected_search(){
			var row = $('#dg_group').datagrid('getSelected');
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
				//swapcontent('memo_movement', row.memo_id, row.amount);
			}
		}
		function getSelections(){
			var ids = [];
			var rows = $('#dg').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				ids.push(rows[i].memo_id);
			}
			alert(ids.join('\n'));
			//document.getElementById('tmemoid').value=ids.join('\n');
		}
		function reload_grids(){
			$('#dg').datagrid('reload');
			$('#dgout').datagrid('reload');
			$('#dgQ').datagrid('reload');
		}
	function upload_supporting_doc($fileid){
		jQuery(function($){
			$.noConflict();
			var settings = {
				url: "scriptfile_m.php?"+$fileid,
				dragDrop:false,
				fileName: "myfile",
				allowedTypes:"pdf",	
				autoSubmit: false,
				returnType:"json",
				 onSuccess:function(files,data,xhr)
				{
				   // alert((data));
				},
				showDelete:false,
				multiple: false,
				maxFileCount: 1,
				showDone: true,
				maxFileSize: 500*1024
			}
			var uploadObj = $("#mulitplefileuploader").uploadFile(settings);
			//$('#new_memo_ok').click(function(e) {
			uploadObj.startUpload();
			//});
		});
	} //end function for upload supporting doc
		
	function upload_doc_edit($fileid){
		jQuery(function($){
			$.noConflict();
			var settings2 = {
				url: "scriptfile_m.php?mfileupload"+$fileid,
				dragDrop:false,
				fileName: "myfile2",
				allowedTypes:"pdf",	
				autoSubmit: false,
				returnType:"json",
				 onSuccess:function(files,data,xhr)
				{
				   // alert((data));
				},
				showDelete:false,
				multiple: false,
				maxFileCount: 1,
				showDone: true,
				maxFileSize: 500*1024
			}
			var uploadObj2 = $("#mulitplefileuploader2").uploadFile(settings2);
			//$('#new_memo_ok').click(function(e) {
			uploadObj2.startUpload2();
			//});
		});
	} //end function for upload supporting doc
	
		$(document).ready(function(e) {
            //$('#dlg').hide();
			//$('#dlg').dialog('close');
			
			$("#dept_unit").change(function(e) {
                //var opText = $("#dept_unit option:selected").text().slice(0, 3).toUpperCase() + "/" + $("#lastnum").val();
				//$("#dept_txt").attr("value", $("#dept_unit option:selected").text().slice(0, 3).toUpperCase() );
				var dstr=$("#dept_unit option:selected").text().split(' ');//.toUpperCase();
				$("#dept_txt").attr("value", dstr[0] );
				//alert(opText);
				//$("#dm_id").html(opText);
				//$("#memo_id").attr("value", opText);
            });
			$("#gid").click(function(e) {
				//$("#dept_txt").attr("value", $("#dept_unit option").text().slice(0, 3).toUpperCase() );
				var dstr=$("#dept_unit option:selected").text().split(' ');//.toUpperCase();
				$("#dept_txt").attr("value", dstr[0] );
            });
			$('#new_memo_ok').click(function(e) {
				$('#file_memo_id').attr("value", $('#memo_id').val() );
			});
	 	
			$(".iframe").colorbox({
				iframe:true, width:"80%", height:"80%"
			});
			$('#dg_group').datagrid({
				toolbar: '#tb_2s'
			});
			$('.sbtn .buttonx').click(function(e) {
				var a = $('#memo_from').val();
				alert(a);
				$('#dept_addr').val('');
				$('#desc').val('');
				$('#amount').val('');
				$('#dept_unit').val('');
            });
        });	
		
	</script>
    <link rel="stylesheet" type="text/css" href="include/colorbox.css">
    <script type="text/javascript" src="include/jquery.colorbox.js"></script>
<link href="upload.css" rel="stylesheet" type="text/css" />
<script src="file/jquery.min.js"></script>
<script src="upload.js"></script>
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
	        <h2>Fixed Asset</h2>
                <p>Use this page to Post Assets</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                <!--<h2>Tabs with Images</h2>
                <p>The tab strip can display big images.</p>-->
                <div style="margin:20px 0;"></div>
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:700px;" id="tt">
                <?php //if ((strtolower($role) == 'clerk' || strtolower($role) == "super admin") ) {?>
                    <div title="<span class='tt-inner'><img src='images/newmail.png'/><br>Post Asset</span>" style="padding:10px">
                    <form name="frm" id="frm" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-color:#369; border-width:thin;">
  <tr>
    <td align="left" valign="top"><fieldset style='border:1px solid #2A5FAA; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>
      <legend style='padding: 0.2em 0.5em; border:1px solid #2A5FAA; color:#2A5FAA; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><b>Goods Received Note</b></legend>
      <table width="50%">
        <tr>
          <td>SIV</td>
          <td><input type="text" name="siv" id="siv" style="width:200px" value="<?php echo $new_siv;?>" ></td>
          <td>GRN</td>
          <td><input type="text" name="grn" id="grn" style="width:200px" value="<?php echo $new_grn;?>" ></td>
        </tr>
        <tr>
          <td colspan="4" align="center"><a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('fixed_asset_save','searchs');">Search</a></td>
          </tr>
      </table>
    </fieldset>
    <fieldset style='border:1px solid #2A5FAA; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>
      <legend style='padding: 0.2em 0.5em; border:1px solid #2A5FAA; color:#2A5FAA; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px; -webkit-border-radius: 5px;'><b>Fill Properly</b></legend>
      <table width="50%" >
        <tr>
          <td width="45%"><label for="amount">Amount:</label></td>
          <td width="55%"><input type="text" name="amount" id="amount" style="width:200px" required></td>
          <td width="55%">Serial:</td>
          <td width="55%"><input type="text" name="serial" id="serial" style="width:200px" ></td>
        </tr>
        <tr>
          <td width="45%"><label for="barcode">Prod.No:</label></td>
          <td width="55%"><input type="text" name="identify_string" id="identify_string" style="width:200px" ></td>
          <td width="55%">Supplier Name: </td>
          <td width="55%"><input type="text" name="supplier" id="supplier" style="width:200px" ></td>
        </tr>
        <tr>
          <td>Product Name:  </td>
          <td><input type="text" name="prod_id" id="prod_id" style="width:200px" ></td>
          <td>Barcode:</td>
          <td><input type="text" name="barcode" id="barcode" style="width:200px" ></td>
        </tr>
        <tr>
          <td>Aquisition Date:</td>
          <td><input type="text" name="date_aq" id="date_aq" class="easyui-datebox" style="width:200px"  ></td>
          <td>Select</td>
          <td><select name="fix_con" id="fix_con" style="width:200px"  onchange="showBio(this.value)">
            <option value="" selected>Select ...</option>
            <option value="Yes">Fixed Asset</option>
           <!-- <option value="No">Consumables</option>-->
          </select></td>
          </tr>
        <tr >
          <td><label for="desc"> Description:</label></td>
          <td><textarea name="descs" id="descs" cols="25" rows="2" style="width:200px" required></textarea></td>
          <td id="qtysx" >Qty</td>
          <td id="qtyssx"><select name="qty" id="qty" required>
							<option value=""> Select</option>
							<?php 
							//$dat = date('Y');
							for ($i = 1; $i <= 200; $i++)
							echo '<option value="'. $i .'">'. $i .'</option>';?>
							
						  </select></td>
          </tr>
        
        <tr>
          <td>Location:</td>
          <td><select name="location" id="location" style="width:200px" >
            <option selected="selected" value="">Select item...</option>
            <?php
                          $res_c=@mysqli_query($con, "select * from locationtb order by dept,unit,room_no");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept=@$rs_c['dept'];
							  $unit=@$rs_c['unit'];
							  $room_no="RM ".$rs_c['room_no'];
							  $loc_code=@$rs_c['loc_code'];
                              echo "<option value='$loc_code'>$dept||$room_no</option>";
                           }
                          echo "</select>";
						 ?>
            </select></td>
          <td>Invoice No:</td>
          <td><input type="text" name="invoice_no" id="invoice_no" style="width:200px" required></td>
          </tr>
        <tr>
          <td>Asset Category:</td>
          <td><select name="cat_type" id="cat_type" style="width:200px"  onChange="swapcontent('catdiv', $('#cat_type').val());">
            <option selected="selected" value="">Select item...</option>
            <?php
                          $res_c=@mysqli_query($con, "select * from asset_categorytb order by cat_title");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $cat_title=@$rs_c['cat_title'];
							  $cat_id=@$rs_c['cat_id'];
							  $cat_code=@$rs_c['cat_code'];
                              echo "<option value='$cat_code'>$cat_title</option>";
                           }
                          echo "</select>";
						 ?>
            </select></td>
          <td>Asset Type:</td>
          <td><div id="catdiv">
            </div>
            <input type="hidden" id="login_id2" name="login_id2" value="<?php echo $login_id; ?>"/>
            <input type="hidden" name="dept_txt" id="dept_txt" value="">
            <input type="hidden" name="file_memo_id3" id="file_memo_id3" value=""></td>
          </tr>
        <tr>
          <td colspan="4" align="center"><a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('fixed_asset_save','save');">Save</a>&nbsp;&nbsp;&nbsp;
            </td>
          
        </table>
         </fieldset>
      <!--</form>-->
    </td>
    </tr>
        </table>
        <div id="display"> </div>
        <div id="roll"> </div>
        
        <div id="fixed_asset_save"> </div>
</form>
                        
                        
                  </div>
                    <?php // } ?>
                    <div title="<span class='tt-inner' onClick='reload_grids();'><img src='images/inmail.png'/><br>Assets</span>" style="padding:10px">
                    <table id="dg" title="" style="width:680px;" data-options="
                singleSelect:true,
                url: 'scriptfile_y.php?contentvar=incoming_asset',
                rownumbers:true,method:'get',toolbar:'#tb',pagination:true,
                pageSize:50
				
            ">
        <thead>
           <tr>
            	<th data-options="field:'ck',checkbox:true"></th>
                <th data-options="field:'identify_string',width:160">ID</th>
                <th data-options="field:'descritption',width:180">DESCRIPTION</th>
                <th data-options="field:'acq_date',width:100,hidden:'false'">DATE</th>
                <th data-options="field:'location',width:100,align:'left'">LOCATION</th>
                <th data-options="field:'amount',width:100,align:'left'">AMOUNT</th>
                <th data-options="field:'type_title',width:100,align:'left'">ASSET TYPE</th>
                
                
                 <th data-options="field:'cat_title',width:100">CATEGORY</th>
                 <th data-options="field:'status',width:100,hidden:'true'">STATUS</th>
                 <th data-options="field:'deleted',width:100,hidden:'true'">DELETED</th>
                 <th data-options="field:'disposal',width:100,hidden:'true'">DISPOSED</th>
            </tr>
        </thead>
    </table>
        <div id="tb" style="padding:2px 5px;">
         <form id= 'assignmailss'  enctype="multipart/form-data" name="assignmailss"> 
		
        <a href="#" class="easyui-linkbutton" iconCls="icon-tip" onClick="getSelected(); $('#mupdate').hide(); $('#mupdate_r').hide(); $('#vwin').window('open'); ">View</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick= "getSelected_del(); swapcontent('assignmail_looks','del',$('#didentifystring').val(),$('#login_id').val()); reload_grids();">Delete</a>
<!--        <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick= "getSelected_del(); swapcontent('assignmail_looks','dis',$('#didentifystring').val(),$('#login_id').val()); reload_grids();">/Dispose</a>
-->		<?php if(strtolower($role) != "bursar"){ ?>
        <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onClick="getSelected(); $('#mupdate').show(); $('#mupdate_r').show(); $('#vwin').window('open'); ">Edit</a>
      <!--   <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected(); $('#vwin').window('open'); ">Dispose</a>-->	
      <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onClick="getSelected(); $('#xwin').window('open'); ">Dispose</a>
		<?php } ?>
        <input type="hidden" id="didentifystring" name="didentifystring" value=""/>
        <input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id; ?>"/>
        </form>
                
		    </div>
             <div id="assignmail_looks"></div>
            <div id="tb_2g" style="padding:2px 5px;">
     <a href="#" class="easyui-linkbutton" iconCls="icon-tip" onClick="getSelected_treated(); $('#mupdate').hide(); $('#mupdate_r').hide(); $('#vwin').window('open'); ">View</a>                
	    </div>
            <div id="tb_2s" style="padding:2px 5px;">
       <!-- <a href="#" class="easyui-linkbutton" iconCls="icon-tip" onClick="getSelected_search(); $('#mupdate').hide(); $('#mupdate_r').hide(); $('#vwin').window('open'); ">View</a>  -->              
		    </div>
           
          <div id="xwin" class="easyui-window" title="Disposal" style="width:620px;height:400px;padding:10px;" 
          data-options="
          modal:true,
          closed:true,
          iconCls:'icon-tip',
			onResize:function(){
				$(this).window('hcenter');
			}">
		<form id= 'disposal'  enctype="multipart/form-data" name="disposal">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td height="33" align="left" valign="middle"><strong>Identification String:</strong></td>
            <td height="33" align="left" valign="middle"><div id="iidentifystring"></div>
            <input type="hidden" id="hidentifystring" name="hidentifystring" value=""/>
            <input type="hidden" id="vlogin_id" name="vlogin_id" value="<?php echo $login_id; ?>"/></td>
            </tr>
             <tr>
            <td height="33" align="left" valign="middle"><strong>Aquistion Date</strong></td>
            <td height="33" align="left" valign="middle"><div id="jacq_date"></div>
            </tr>
         
          <tr>
            <td height="33" align="left" valign="middle"><strong>Description:</strong></td>
            <td height="33" align="left" valign="middle"><div id="jdescription"></div></td>
          </tr>
          <tr>
            <td height="33" align="left" valign="middle"><strong>Dispose Date:</strong></td>
            <td height="33" align="left" valign="middle"><input type="text" name="date_dis" id="date_dis" class="easyui-datebox" style="width:200px"  ></td>
          </tr>
          <tr>
            <td height="33" align="left" valign="left"><strong>Amount Disposed:</strong></td>
            <td height="33" align="left" valign="middle"><input type="text" class="easyui-textbox" id="damount" name="damount" style="width:300px;" /></td>
          </tr>
       
            <tr>
            <td height="33" align="left" valign="middle" nowrap><a href="#" class="easyui-linkbutton" iconCls="icon-save" onClick="getSelected(); swapcontent('disposed',$('#damount').val()); reload_grids();">Dispose Asset</a></td>
          </tr>
          <!--<tr>
            <td height="33" align="center" valign="middle">&nbsp;</td>
            <td height="33" align="right" valign="middle">
            </td>
          </tr>-->
          
        </table>
        </form>
        <div id="disposed"></div>
	</div>
    <div id="vwin" class="easyui-window" title="View/Edit Asset" style="width:600px;height:400px;padding:10px;" 
          data-options="
          modal:true,
          closed:true,
          iconCls:'icon-tip',
			onResize:function(){
				$(this).window('hcenter');
			}">
       <form  enctype="multipart/form-data"  class="formx" id="editmail" name="editmail" >
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
          <tr>
            <td height="33" align="left" valign="middle"><strong>Identification String:</strong></td>
            <td height="33" align="left" valign="middle"><div id="tidentifystring"></div>
            <input type="hidden" id="eidentifystring" name="eidentifystring" value=""/>
            <input type="hidden" id="vlogin_id" name="vlogin_id" value="<?php echo $login_id; ?>"/></td>
            </tr>
             <tr>
            <td height="33" align="left" valign="middle"><strong>Aquistion Date</strong></td>
            <td height="33" align="left" valign="middle"><div id="acq_date"></div>
            </tr>
          <!--<tr>
            <td height="33" align="left" valign="middle"><strong>Change Location:</strong></td>
            <td height="33" align="left" valign="middle"><select name="hlocation" id="hlocation" style="width:300px;" >
							<option value="" selected>Select item...</option>
							<?php /*$res_c=@mysqli_query($con, "select * from locationtb order by dept,unit,room_no");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept=@$rs_c['dept'];
							  $unit=@$rs_c['unit'];
							  $room_no="RM ".$rs_c['room_no'];
							  $loc_code=@$rs_c['loc_code'];
                              echo "<option value='$loc_code'>$dept||$unit||$room_no</option>";
                           }
                          echo "</select>";*/ ?>
						</select></td>            
            </tr>-->
            <tr>
            <td height="33" align="left" valign="middle"><strong>Unit:</strong></td>
            <td height="33" align="left" valign="middle"><div id="hunits"></div></td>
            <tr>
            <tr>
            <tr>
            <td height="33" align="left" valign="middle"><strong>Department:</strong></td>
            <td height="33" align="left" valign="middle"><div id="hdept"></div></td>
            <tr>
            <tr>
            <td height="33" align="left" valign="left"><strong>Room No:</strong></td>
            <td height="33" align="left" valign="middle"><div id="hroomno"></div></td>
          </tr>
          
          <tr>
            <td height="33" align="left" valign="middle"><strong>Description:</strong></td>
            <td height="33" align="left" valign="middle"><textarea id="hdescription" name="hdescription" class="easyui-textbox" style="width:300px;height:60px;"></textarea></td>
          </tr>
          
          <tr>
          
            <td height="33" align="left" valign="middle"><strong>Category Title:</strong></td>
            <td height="33" align="left" valign="middle"><select name="hcattitle" id="hcattitle" style="width:300px;" >
							<option value="" selected>Select item...</option>
							 <?php  $q =  mysqli_query($con, "select * from asset_categorytb");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r['cat_id'] .'">'. $r['cat_title'] .'</option>';
							  }
							  ?>
						</select></td>            
            </tr>
            <tr>
            <td height="33" align="left" valign="middle"><strong>Asset Title:</strong></td>
            <td height="33" align="left" valign="middle"><select name="htypetitle" id="htypetitle" style="width:300px;" >
							<option value="" selected>Select item...</option>
							 <?php  $q =  mysqli_query($con, "select * from asset_typetb order by type_title");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r['type_id'] .'">'. $r['type_title'] .'</option>';
							  }
							  ?>
						</select></td>            
            </tr>
          <!--<tr>
            <td height="33" align="left" valign="left"><strong>Asset Title:</strong></td>
            <td height="33" align="left" valign="middle"><input type="text" class="easyui-textbox" id="htypetitle" name="htypetitle" style="width:300px;" /></td>
          </tr>
          <tr>
            <td height="33" align="left" valign="left"><strong>Category Title:</strong></td>
            <td height="33" align="left" valign="middle"><input type="text" class="easyui-textbox" id="hcattitle" name="hcattitle" style="width:300px;" /></td>
          </tr>-->
          <tr>
            <td height="33" align="left" valign="left"><strong>Amount:</strong></td>
            <td height="33" align="left" valign="middle"><input type="text" class="easyui-textbox" id="hamount" name="hamount" style="width:300px;" /></td>
          </tr>
          
          <tr>
            <td height="33" align="left" valign="middle"><strong>Asset Status:</strong></td>
            <td height="33" align="left" valign="middle"><div id="hstatus"></div></td>
            <tr>
            <td height="33" align="left" valign="middle"><strong>Deleted Status</strong></td>
            <td height="33" align="left" valign="middle"><div id="hdeleted"></div>
            </tr>
            <tr>
            <td height="33" align="left" valign="middle"><strong>Diposed Status</strong></td>
            <td height="33" align="left" valign="middle"><div id="hdisposal"></div>
            </tr>
            <td height="33" align="left" valign="middle" nowrap><span id="vmemoaction"></span>&nbsp;&nbsp;<span id="mupdate">&nbsp;&nbsp;<a href="#" class="easyui-linkbutton" iconCls="icon-save" onClick="swapcontent('editasset', $('#hdescription').val(), $('#hcattitle').val(), $('#htypetitle').val(),$('#hamount').val());">Update</a></span></td>
          </tr>
          <!--<tr>
            <td height="33" align="center" valign="middle">&nbsp;</td>
            <td height="33" align="right" valign="middle">
            </td>
          </tr>-->
          
        </table>
        </form>
        <div id="editasset"></div>
       
	</div>

            </div><!-- END OF DIV FOR INCOMING MAILS -->
                    
                    <div title="<span class='tt-inner' onClick='reload_grids();'><img src='images/outmail.png'/><br>Archieve</span>" style="padding:10px" >
          <table id="dgout" title="" style="width:680px;" data-options="
                singleSelect:true,
                url: 'scriptfile_y.php?contentvar=archeive_asset',
                rownumbers:true,method:'get',toolbar:'#tb_2g', pagination:true,
                pageSize:50,
				rowStyler: function(index,row){
					if (row.read_status == 'Unread'){
						return 'color:#900;font-weight:bold;';
					}
				}
            ">
        <thead>
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th data-options="field:'identify_string',width:160">ID</th>
                <th data-options="field:'descritption',width:180">DESCRIPTION</th>
                <th data-options="field:'acq_date',width:100,hidden:'false'">DATE</th>
                <th data-options="field:'units',width:100,align:'left'">LOCATION</th>
                <th data-options="field:'amount',width:100,align:'left'">AMOUNT</th>
                <th data-options="field:'type_title',width:100,align:'left'">ASSET TYPE</th>
                <th data-options="field:'dept',width:100,hidden:'true'">DEPT</th>
                <th data-options="field:'room_no',width:100,hidden:'true'">ROOM</th>
                 <th data-options="field:'cat_title',width:100">CATEGORY</th>
                 <th data-options="field:'status',width:100,hidden:'true'">STATUS</th>
                 <th data-options="field:'deleted',width:100">DELETED</th>
                 <th data-options="field:'disposal',width:100">DISPOSED</th>
            </tr>
        </thead>
    </table>
                  </div><!-- END OF DIV FOR OUTGOING MAILS -->
                    <?php //if ($user == 'admin') {?>
                    <!--<div onClick="swapcontent('mailsearch');" title="<span class='tt-inner'><img src='images/searchmail.png'/><br>Search Mail</span>" style="padding:10px">
                         <table id="dg_group" style="width:650px;height:250px"
            url="scriptfile_m.php?contentvar=memo_withsub" 
            title=""
            singleSelect="true" fitColumns="true" pagination="true" 
                pageSize = "50" toolbar="tb_2s" >
        <thead>
            <tr>
            	<th data-options="field:'ck',checkbox:true"></th>
                <th field="memo_id" width="80">Memo ID</th>
                <th field="memo_from" width="100">From</th>
                <th data-options="field:'address_unit',width:100,hidden:'false'">Address/Unit</th>
                <th field="description" align="left" width="180">Description</th>
                <th field="amount" align="left" width="100">Amount</th>
                <th data-options="field:'dept_unit',width:100,align:'left',hidden:'false'">Dept/Unit</th>
                <th field="datein" width="60">Date</th>
                <th field="memo_status" width="80" align="left">Status</th>
            </tr>
        </thead>
    </table>
    <script type="text/javascript">
        $(function(){
            $('#dg_group').datagrid({
                view: detailview,
                detailFormatter:function(index,row){
                    return '<div style="padding:2px"><table class="ddv"></table></div>';
                },
                onExpandRow: function(index,row){
                    var ddv = $(this).datagrid('getRowDetail',index).find('table.ddv');
                    ddv.datagrid({
                        url:'scriptfile_m.php?contentvar=memo_sub&memo_id='+row.memo_id,
                        fitColumns:true,
                        singleSelect:true,
                        rownumbers:true,
                        loadMsg:'',
                        height:'auto',
                        columns:[[
                            {field:'memo_status',title:'STATUS',width:50},
                            {field:'dept_unit',title:'DEPT/UNIT',width:100,align:'left'},
                            {field:'date',title:'DATE',width:60,align:'left'},
                            {field:'remark',title:'REMARK',width:150,align:'left'},
                            {field:'action',title:'ACTION',width:100,align:'left'}
                        ]],
                        onResize:function(){
                            $('#dg_group').datagrid('fixDetailRowHeight',index);
                        },
                        onLoadSuccess:function(){
                            setTimeout(function(){
                                $('#dg_group').datagrid('fixDetailRowHeight',index);
                            },0);
                        }
                    });
                    $('#dg_group').datagrid('fixDetailRowHeight',index);
                }
            });
        });
    </script>
    <script type="text/javascript">
		var url;
		function open_memo(){
			/*alert(1343234);*/
			var row = $('#dg').datagrid('getSelected');
			/*$('#dlg').dialog('open').dialog('setTitle', "New Window"); exit;*/
			if (row){
				$('#dlg').dialog('open').dialog('setTitle', row.memo_id + "::" + row.memo_from);
				$('#fm').form('load',row);
				url = 'scriptfile_m.php?contentvar=memo_meovement&memo_id=1';
			}
		}
		</script>
    <div id="dlg" class="easyui-window" title="Basic Window" data-options="iconCls:'icon-tip'" style="width:500px;height:200px;padding:10px;" closed="true">
    <form id="fm" method="post" novalidate>
    <table id="dgx" title="" style="width:680px;" data-options="
                singleSelect:true,
                url: 'scriptfile_m.php?contentvar=xxx',
                rownumbers:true,method:'get'
            ">
        <thead>
            <tr>
                <th data-options="field:'memo_id',width:80">ID</th>
                <th data-options="field:'memo_from',width:100">FROM</th>
                <th data-options="field:'description',width:180,align:'left'">DESCRIPTION</th>
                <th data-options="field:'amount',width:100,align:'left'">AMOUNT</th>
                <th data-options="field:'datein',width:60">DATE</th>
                <th data-options="field:'memo_status',width:80,align:'center'">STATUS</th>
            </tr>
        </thead>
    </table>
    </form>    
    </div>
    <!--<div id="mailsearch"></div>-->
    
           
    
    <script type="text/javascript">
	//FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
        $(function(){
            var dg = $('#dg').datagrid();
			
			
            dg.datagrid('enableFilter', [{
                field:'amount',
                type:'numberbox',
                options:{precision:2},
                op:['equal','notequal','less','greater']
            },/*{
                field:'unitcost',
                type:'numberbox',
                options:{precision:1},
                op:['equal','notequal','less','greater']
            },*/{
                field:'memo_status',
                type:'combobox',
                options:{
                    panelHeight:'auto',
                    data:[{value:'',text:'All'},{value:'In Progress',text:'In Progress'},
						{value:'Queried',text:'Queried'},{value:'Completed',text:'Completed'}],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'memo_status');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'memo_status',
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
                        
    <script type="text/javascript">
	//FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
        $(function(){
            var dg = $('#dg_group').datagrid();
			
			
            dg.datagrid('enableFilter', [{
                field:'amount',
                type:'numberbox',
                options:{precision:2},
                op:['equal','notequal','less','greater']
            },/*{
                field:'unitcost',
                type:'numberbox',
                options:{precision:1},
                op:['equal','notequal','less','greater']
            },*/{
                field:'memo_status',
                type:'combobox',
                options:{
                    panelHeight:'auto',
                    data:[{value:'',text:'All'},{value:'In Progress',text:'In Progress'},
						{value:'Queried',text:'Queried'},{value:'Completed',text:'Completed'}],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'memo_status');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'memo_status',
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
                        
    <script type="text/javascript">
	//FILTER/SEARCH SCRIPT STAR5T HERE FOR GRIDS
        $(function(){
            var dg = $('#dgout').datagrid();
			
			
            dg.datagrid('enableFilter', [{
                field:'amount',
                type:'numberbox',
                options:{precision:2},
                op:['equal','notequal','less','greater']
            },/*{
                field:'unitcost',
                type:'numberbox',
                options:{precision:1},
                op:['equal','notequal','less','greater']
            },*/{
                field:'memo_status',
                type:'combobox',
                options:{
                    panelHeight:'auto',
                    data:[{value:'',text:'All'},{value:'In Progress',text:'In Progress'},
						{value:'Queried',text:'Queried'},{value:'Completed',text:'Completed'}],
                    onChange:function(value){
                        if (value == ''){
                            dg.datagrid('removeFilterRule', 'memo_status');
                        } else {
                            dg.datagrid('addFilterRule', {
                                field: 'memo_status',
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
                        
                    </div>
                    <?php // } ?>
                    
                </div>
            <!--  <strong><span style="color:#900">Not Resolved mail</span> - <span style="color:#000">Resolved mail</span></strong>   
       QUERIED MAILS TAB -->
       <!--<div title="<span class='tt-inner' onClick='reload_grids();'><img src='images/outmail.png'/><br>Queried Mail</span>" style="padding:10px" >
          <table id="dgQ" title="" style="width:680px;" data-options="
                singleSelect:true,
                url: 'scriptfile_m.php?contentvar=outgoing_mail',
                rownumbers:true,method:'get',toolbar:'#toolbarQ', pagination:true,
                pageSize:10,
				rowStyler: function(index,row){
					if (row.read_status == 'Unread'){
						return 'color:#900;font-weight:bold;';
					}
				}
            ">
        <thead>
            <tr>
                <th data-options="field:'memo_id',width:80">ID</th>
                <th data-options="field:'memo_from',width:100">FROM</th>
                <th data-options="field:'description',width:180,align:'left'">DESCRIPTION</th>
                <th data-options="field:'amount',width:100,align:'left'">AMOUNT</th>
                <th data-options="field:'dept_unit',width:100,align:'left'">DEPT/UNIT</th>
                <th data-options="field:'datein',width:90">DATE</th>
                <th data-options="field:'memo_status',width:80,align:'center'">STATUS</th>
            </tr>
        </thead>
    </table>
                  </div>--><!-- END OF DIV FOR OUTGOING MAILS -->
                    <?php //if ($user == 'admin') {?>
               <!--      <div onClick="swapcontent('mailsearch');" title="<span class='tt-inner'><img src='images/searchmail.png'/><br>Search Mail</span>" style="padding:10px">
                         <table id="dg_group" style="width:650px;height:250px"
            url="scriptfile_m.php?contentvar=memo_withsub" 
            title=""
            singleSelect="true" fitColumns="true" pagination="true" 
                pageSize = "10" >
       <thead>
            <tr>
            	<th data-options="field:'ck',checkbox:true"></th>
                <th field="memo_id" width="80">Memo ID</th>
                <th field="memo_from" width="100">From</th>
                <th field="description" align="left" width="180">Description</th>
                <th field="amount" align="left" width="100">Amount</th>
                <th field="datein" width="60">Date</th>
                <th field="memo_status" width="80" align="left">Status</th>
            </tr>
        </thead>-->
    </table>
    
    
    
    <!--<div id="mailsearch"></div>-->
                    </div>
                    <?php // } ?>
                    
                </div>
                <!-- END QUERY TAB -->
                
                <!--<div id="roll"></div>--> <div id="memo_movement"></div>
               <style scoped>
                    .tt-inner{
                        display:inline-block;
                        line-height:12px;
                        padding-top:5px;
                    }
                    .tt-inner img{
                        border:0;
                    }
                </style>                                
               <?php
			   
			   /*$opt=@$_REQUEST['mail_opt'];
			   if($opt=='new')
			   	{
					echo "<h4>New Mail</h4><br>$opt";
				}
			   elseif($opt=='in')
			   	{
					echo "<h4>Incoming Mail(s)</h4><br>$opt";
				}
			   elseif($opt=='out')
			   	{
					echo "<h4>Outgoing Mail(s)</h4><br>$opt";
				}
			elseif($opt=='search')
			   	{
					echo "<h4>Search for Mail(s)</h4><br>$opt";
				}*/
			   ?>
                
                
                                
           
            </div><!-- end of content box -->
        </div> <!-- end of content tooplate_content-->
    </div> <!-- end of content tooplate_main-->
    <div class="cleaner"></div>    
</div> <!-- end of wrapper tooplate_wrapper-->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->
<script type="text/javascript" >
 $(document).ready(function() { 
		
            $('#photoimg').live('change', function()			{ 
			           $("#preview").html('');
			    $("#preview").html('<img src="images/ajax-loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({
						target: '#preview'
		}).submit();
		
			});
        }); 
</script>
<style>
	.preview
	{
		width:200px;
		border:solid 1px #dedede;
		padding:10px;
	}
	#preview
	{
		color:#cc0000;
		font-size:12px
	}
</style>
</body>
</html>