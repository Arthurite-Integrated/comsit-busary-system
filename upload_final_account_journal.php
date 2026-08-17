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
	        <h2>Final Account Upload</h2>
                <p>Use this page to Upload Final Account</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                <!--<h2>Tabs with Images</h2>
                <p>The tab strip can display big images.</p>-->
                <div style="margin:20px 0;"></div>
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:700px;" id="tt">
                  <div title="<span class='tt-inner'><img src='images/newmail.png'/><br>Upload Journal</span>" style="padding:10px">
                    <form  action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="uploadasset" id="uploadasset" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-color:#369; border-width:thin;">
  <tr>
    <td align="left" valign="top">
    <fieldset style='border:1px solid #2A5FAA; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>
      <legend style='padding: 0.2em 0.5em; border:1px solid #2A5FAA; color:#2A5FAA; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px; -webkit-border-radius: 5px;'><b>Fill Properly</b></legend>
      <table width="80%" >
        <tr>
          <td ><a href="template/journal template.csv">Journal Sample</a></td>
          <td></td>
        </tr>
        <tr>
          <td width="15%" >Upload Type:</td>
          <td width="58%"><select name="utype" id="utype" style="width:200px">
            <option selected="selected" value="">Select item...</option>
            <option value="Journal">Journal Credit</option>
             <option value="Journald">Journal Debit</option>
           
            </select></td>
         
        </tr>
        <tr>
          <td >Upload File:</td>
          <td colspan="3">
            <input name="file" type="file" class="btn" id="file" size="40" /></td>
        <tr>
          <td colspan="4" align="center"><div align="center">
             <input name="mode" type="hidden" id="mode" value="upload" /> <input name="button" type="submit" class="btn" id="button" value="Upload Journal for Final Account" />
            </div>
          
      
            </td>
          
        </table>
         </fieldset>
      <!--</form>-->
    </td>
    </tr>
        </table>
        <div id="display"> </div>
        <div id="roll"> </div>
        
  <?php
		   ////////////////////////////////Action section ////////////////////////////////////////////////////////////
		   if(isset($_REQUEST['mode']) and $_REQUEST['mode']=='upload')
		   {
			   
			 $utype=@$_REQUEST['utype'];
			$login_id=@$_SESSION['login_id'];
			   
			   $fname = @$_FILES['file']['name'];
			   $ext = @explode(".",$fname);  $ext = $ext[1];
			   $sn = 0;
			   if ($ext != "csv" and $ext != "CSV") 
			   { 
			     echo "<font color='red'>Invalid file type. CSV file should be uploaded.</font>";
			     exit;
			   } //end of check extension
			   
			   if ($utype == 'Journal')
			   {
			   $uploadDir = "upload_files/final/";
			   $upload_file_name=@date('Ymd').@date('h:s:i a').$fname;  //the file with .csv
			   $upload_file_name=@str_replace(":","",$upload_file_name);
			   $upload_file_name=@str_replace(" ","",$upload_file_name);
			   $uploadFile = $uploadDir.$upload_file_name;
			   $thead="<table><tr><th>S/NO</th><th>DATE</th><th>PAYMENT NUMBER</th><th>ACCOUNT CODE</th><th>AMOUNT</th><th>TYPE</th><th>STATUS</th></tr>";
			   if (@move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile))
					{ // file uploaded
					
					 $file_array = @file("$uploadFile");
					    while (list($line_num, $line) = each($file_array)) 
							{ // each line
						
								++$sn;
								$fileRow = @explode(",",$line);
								$folio_code = @trim($fileRow[0]);
								$transtype = @trim($fileRow[1]); 
								$date_entry = @trim($fileRow[2]);
								$amount = @trim($fileRow[3]);
								$pvno = @addslashes(@trim($fileRow[4]));
								  
								//////////////////////check folio code//////////////////////////
							$res_lf=@mysqli_query($con, "select folio_code from foliotb where folio_code='".$folio_code."'");
			  $rs_lf=@mysqli_fetch_array($res_lf);
			  	////^if(@mysqli_num_rows($res_lf)>=1) {$fon = "UPLOADED";} else {$fon = "NOT UPLOADED";}
			  				///////////////////////////////////////////////////////////////
							
				$res_l=@mysqli_query($con, "select * from transtb where folio_code='".$folio_code."' and transtype='".$transtype."' and transdate='".$date_entry."' and amount='".$amount."' and pvno='".$pvno."'");
			  $rs_l=@mysqli_fetch_array($res_l);
			  if(@mysqli_num_rows($res_l)>=1)
			  {
				  $delSql="delete from transtb where folio_code='".$folio_code."' and pvno='".$pvno."' and transtype='".$transtype."' and transdate='".$date_entry."' and amount='".$amount."'";
				  mysqli_query($con, $delSql);
				 // $tbody.="<tr><td>$sn</td><td>$date_entry</td><td>$pvno</td><td>$folio_code</td><td>$amount</td><td>$transtype</td></tr>";
			  }
			 	////let us add them to the necessary tables  ".$receiptno."
				$insSql="insert into transtb set acctcode='".$folio_code."', pvno='".$pvno."', folio_code='".$folio_code."', transtype='".$transtype."', transdate='".$date_entry."', amount=".$amount.",entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'";
				if( mysqli_query($con, $insSql)) {$fon = "UPLOADED";} else {$fon = "NOT UPLOADED";}
				////^ mysqli_query($con, $insSql);
						
									
									$tbody.="<tr><td>$sn</td><td>$date_entry</td><td>$pvno</td><td>$folio_code</td><td>$amount</td><td>$transtype</td><td><font color='#00FF00'>$fon</font></td></tr>";
									
								//} 
							} //end of file loop per row			
												
							echo $thead;
							echo $tbody;
							echo "</table>";
					} //end of move_upload file

			   
			   //echo "Yes $session $choice $mode_of_entry";
		 //  } //end of action for uploading
		   }
		   else 
			   {
			   $uploadDir = "upload_files/final/";
			   $upload_file_name=@date('Ymd').@date('h:s:i a').$fname;  //the file with .csv
			   $upload_file_name=@str_replace(":","",$upload_file_name);
			   $upload_file_name=@str_replace(" ","",$upload_file_name);
			   $uploadFile = $uploadDir.$upload_file_name;
			   $thead="<table><tr><th>S/NO</th><th>DATE</th><th>PAYMENT NUMBER</th><th>ACCOUNT CODE</th><th>AMOUNT</th><th>TYPE</th><th>STATUS</th></tr>";
			   if (@move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile))
					{ // file uploaded
					
					 $file_array = @file("$uploadFile");
					    while (list($line_num, $line) = each($file_array)) 
							{ // each line
						
								++$sn;
								$fileRow = @explode(",",$line);
								$folio_code = @trim($fileRow[0]); 
								$transtype = @trim($fileRow[1]); 
								$date_entry = @trim($fileRow[2]);
								$amount = @trim($fileRow[3]);
								$pvno = @addslashes(@trim($fileRow[4]));
								  
								//////////////////////check folio code//////////////////////////
							$res_lf=@mysqli_query($con, "select folio_code from foliotb where folio_code='".$folio_code."'");
			  $rs_lf=@mysqli_fetch_array($res_lf);
			  	////^if(@mysqli_num_rows($res_lf)>=1) {$fon = "UPLOADED";} else {$fon = "NOT UPLOADED";}
			  				///////////////////////////////////////////////////////////////
							
							 $res_l=@mysqli_query($con, "select * from transtb where folio_code='".$folio_code."' and transtype='".$transtype."' and transdate='".$date_entry."' and amount='".$amount."' and pvno='".$pvno."'");
			  $rs_l=@mysqli_fetch_array($res_l);
			  if(@mysqli_num_rows($res_l)>=1)
			  {
				  $delSql="delete from transtb where folio_code='".$folio_code."' and pvno='".$pvno."' and transtype='".$transtype."' and transdate='".$date_entry."' and amount='".$amount."'";
				  mysqli_query($con, $delSql);
				 // $tbody.="<tr><td>$sn</td><td>$date_entry</td><td>$pvno</td><td>$folio_code</td><td>$amount</td><td>$transtype</td></tr>";
			  }
			 	////let us add them to the necessary tables  ".$receiptno."
				$insSql="insert into transtb set pvno='".$pvno."', folio_code='".$folio_code."', transtype='".$transtype."', transdate='".$date_entry."', amount=".$amount.",entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'";
				if( mysqli_query($con, $insSql)) {$fon = "UPLOADED";} else {$fon = "NOT UPLOADED";}			
							////^ mysqli_query($con, $insSql);
						
									
									$tbody.="<tr><td>$sn</td><td>$date_entry</td><td>$pvno</td><td>$folio_code</td><td>$amount</td><td>$transtype</td><td><font color='#00FF00'>$fon</font></td></tr>";
									
								//} 
							} //end of file loop per row			
												
							echo $thead;
							echo $tbody;
							echo "</table>";
					} //end of move_upload file

			   
			   //echo "Yes $session $choice $mode_of_entry";
		 //  } //end of action for uploading
		   }
		}
		?>
        </p>      
</form>
                        
                        
                
    </table>
                  </div><!-- END OF DIV FOR OUTGOING MAILS --><!--<div onClick="swapcontent('mailsearch');" title="<span class='tt-inner'><img src='images/searchmail.png'/><br>Search Mail</span>" style="padding:10px">
                         <table id="dg_group" style="width:650px;height:250px"
            url="scriptfile_m.php?contentvar=memo_withsub" 
            title=""
            singleSelect="true" fitColumns="true" pagination="true" 
                pageSize = "10" toolbar="tb_2s" >
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
        
        $(function(){
            $('#dg').datagrid({data:getData()}).datagrid('clientPaging');
        });
		//script for pagination ends
</script>
    
        <script type="text/javascript">
        var toolbar = [{
            text:'TRACK MAIL',
            iconCls:'icon-tip',
            handler:function(){
				//alert('PUT MEMO MOVEMENT HERE!')
				getSelected()
				}
        }/*,{
            text:'Cut',
            iconCls:'icon-cut',
            handler:function(){alert('cut')}
        },'-',{
            text:'Save',
            iconCls:'icon-save',
            handler:function(){alert('save')}
        }*/];
    </script>
    
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
                  </div>--><!-- END OF DIV FOR OUTGOING MAILS --><!--      <div onClick="swapcontent('mailsearch');" title="<span class='tt-inner'><img src='images/searchmail.png'/><br>Search Mail</span>" style="padding:10px">
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
       <!-- </div>  end of content tooplate_content-->
    <!-- </div> end of content tooplate_main-->
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