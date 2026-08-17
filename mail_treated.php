<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
$role=@$_SESSION['role']; $role=$r_vals;
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
<?php include("required_jQuery_files.php");
include "function.php";?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
    function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
    {   //swap content begins where cv means div id name
        var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
        $(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
        $("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
        var url="scriptfile_m.php";
        var str;
	
        if(cv=='load_unit')
        {
                    $.post(url,{contentvar:cv,dept_code:v,unit_code:a},function(data){
                $(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
                //$("#roll").html('').show();
                });

        }	
        if(cv=='login') //start putme_login
        {
                $.post(url,{contentvar:cv},function(data){
                                                    //alert(data);
                TINY.box.show(data,0,0,0,0);$(divid).html('').show();
                $("#roll").html('').show();
                });
        }//end of putme_login
  
        if(cv=='forget_password') //start putme_login
        {
                $.post(url,{contentvar:cv},function(data){
                                                    //alert(data);
                TINY.box.show(data,0,0,0,0);$(divid).html('').show();
                $("#roll").html('').show();
                });
        }//end of putme_login
  
        if(cv=='main_login') //start putme_login
        {
            $.post(url,{contentvar:cv,username:v,password:a},function(data){
                $(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
                $("#roll").html('').show();
            });
        }//end of putme_login
  
        if(cv=='pass_recovery_update') //start putme_login
        {
            $.post(url,{contentvar:cv, uname:v, email:a},function(data){
                $(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
                $("#roll").html('').show();
            });
        }//end of putme_login
  
        if(cv=='another') //start putme_login
        {
                $.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
                    $(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
                });
        }//end of putme_login

        if(cv=="inmails" ) // in mails
		{
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			 var test = (JSON.stringify($('#inmail').serializeObject()));
			if(a=="" || v=="" || b=="")
			 {
				  alert('Complete all the fields ');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  } //end of validation 
	        var test = (JSON.stringify($('#inmail').serializeObject()));
            $.post(url,$("#inmail").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
                $(divid).html(data).show(); 
            });
		}//end of in mails

        if(cv=="getnextmemo" ) // in mails
		{
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			 var test = (JSON.stringify($('#inmail').serializeObject()));
			if(v == "")
			 {
				  alert('You must select Dept/Unit!');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  } //end of validation 
            var test = (JSON.stringify($('#inmail').serializeObject()));
            $.post(url,$("#inmail").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
            $(divid).html(data).show(); 
            });
        }//end of in mails

        if(cv=="mailsearch" ) // in mails
        {
            var tab = $('#tt').tabs('getSelected');
            var index = $('#tt').tabs('getTabIndex', tab);
        
            $.post(url,"contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
                $(divid).html(data).show();            
            });
        }//end of in mails

        if(cv=='memo_movement') //display memo movement in tinybox
        {
            if(v > ''){
                $.post(url,"contentvar="+cv+"&memo_id="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
                    $(divid).html(data).show(); 
                });
            }else alert("No memo selected!"); 
        }//end of putme_login

        if(cv=='outmail' || cv=='out_query') // outgoing
		{
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			
            var test = (JSON.stringify($('#outmails').serializeObject()));
        
            $.post(url,$("#outmails").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
                $(divid).html(data).show(); 
            });

		}//end of outgoing

        if(cv=='out_query') // out query
		{
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
            var test = (JSON.stringify($('#outmails').serializeObject()));
        
            $.post(url,$("#outmails").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
                $(divid).html(data).show(); 
            });
		}//end of out query

 	    if(cv=='editmails') //Edit and update memo
		{
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			var test = (JSON.stringify($('#editmail').serializeObject()));
			var test = (JSON.stringify($('#editmail').serializeObject()));
			$.post(url,$("#editmail").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  
				$(divid).html(data).show(); 
			});
		}//end of outgoing
		
    }//end of swapcontent
 </script>

	<script>
		function getSelected_treated(){
			var row = $('#dgout').datagrid('getSelected');
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
				
				/*if($('#vmemoaction').val()=='Queried'){
            $('#mupdate').hide(); 
            $('#mupdate_r').hide();
          }else{
            $('#mupdate').show(); 
            $('#mupdate_r').show();
          }*/
				//swapcontent('memo_movement', row.memo_id, row.amount);
			}
		}

        function reload_grids(){
			//$('#dg').datagrid('reload');
			$('#dgout').datagrid('reload');
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
				maxFileSize: 500000*1024
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
			$('#tt').tabs('select', 2);
			$("#dept_unit").change(function(e) {
				var dstr=$("#dept_unit option:selected").text().split(' ');//.toUpperCase();
				$("#dept_txt").attr("value", dstr[0] );
            });
			$("#gid").click(function(e) {
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
		
        function loadMailGrid(){
            var fro=$('#dFrm').val();
            var to=$('#dTo').val();
            var dataOpt = "singleSelect:true,url: 'scriptfile_m.php?contentvar=incoming_mail&sdate="+fro+"&edate="+to+"',rownumbers:true,method:'get',toolbar:'#tb', pagination:true,pageSize:10,rowStyler: function(index,row){if (row.read_status == 'Unread'){return 'color:#900;font-weight:bold;';}}";
            $('#dg').attr('data-options', dataOpt);
            //alert($('#dg').attr('data-options'));
        }

        function open_window(index){
            if(index==1) window.location='mail.php';
            else if(index==2) window.location='mail_incoming.php';
        }
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
	        <h2>Mails</h2>
                <p>Use this page to adminisiter mails</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                <div style="margin:20px 0;"></div>
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:900px; height:600px" id="tt">
                <div title="<span class='tt-inner' onclick='open_window(1);'><img src='images/newmail.png'/><br>New Mail</span>" style="padding:10px"></div>
                    <div title="<span class='tt-inner' onClick='open_window(2);'><img src='images/inmail.png'/><br>Incoming Mail</span>" style="padding:10px"></div>
                    <div title="<span class='tt-inner' onClick=''><img src='images/outmail.png'/><br>Treated Mail</span>" style="padding:10px" >
                        <form name="frmFilter" id="frmFilter" method="post" action="<?=$_SERVER['PHP_SELF'];?>">
                            <p>&nbsp;</p>
                            <h3>APPLY FILTER</h3><hr>
                            <div class="row">
                                <div class="col-sm-6">
                                    <label><strong>Enter Date Range: </strong></label> <input type="date" id="dFrm" name="dFrm" value="" class="form-control">
                                - 
                                    <input type="date" id="dTo" name="dTo" value="" class="form-control"> 
                                    <input type="submit" id="btn" name="btn" value="DISPLAY" class="btn">
                                </div><hr>
                            </div>
                        </form>
                        <?php
                        $dTo=$_POST['dTo'];
                        $dFrm=$_POST['dFrm'];
                        if($dTo=='') $dTo=date('Y-m-d');
                        if($dFrm=='') $dFrm=date('Y-m-d');
                        ?>

                        <table id="dgout" title="" style="width:880px;" data-options="
                                singleSelect:true,
                                url: 'scriptfile_m.php?contentvar=outgoing_mail&sdate=<?=$dFrm;?>&edate=<?=$dTo;?>',
                                rownumbers:true,method:'get',toolbar:'#tb_2g', pagination:true,
                                pageSize:10,
                                rowStyler: function(index,row){
                                    if (row.read_status == 'Unread'){
                                        return 'color:#900;font-weight:bold;';
                                    }
                                }
                            ">
                            <thead>
                                <tr>
                                    <th data-options="field:'ck',checkbox:true"></th>
                                    <th data-options="field:'memo_id',width:80">ID</th>
                                    <th data-options="field:'memo_from',width:100">FROM</th>
                                    
                                    <th data-options="field:'address_unit',width:100,hidden:'false'">ADDRESS/UNIT</th>
                                    
                                    <th data-options="field:'description',width:180,align:'left'">DESCRIPTION</th>
                                    <th data-options="field:'amount',width:100,align:'left'">AMOUNT</th>
                                    <th data-options="field:'dept_unit',width:100,align:'left'">DEPT/UNIT</th>
                                    <th data-options="field:'datein',width:90">DATE</th>
                                    <th data-options="field:'memo_status',width:80,align:'center'">STATUS</th>
                                </tr>
                            </thead>
                        </table>
                        <div id="tb_2g" style="padding:2px 5px;">
                            <a href="#" class="easyui-linkbutton" iconCls="icon-tip" onClick="getSelected_treated(); $('#mupdate').hide(); $('#mupdate_r').hide(); $('#vwin').window('open'); ">View</a>   
                                <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onClick="getSelected_treated();           if(document.getElementById('vmemoaction').innerHTML!='Queried'){
                                $('#mupdate').hide(); 
                                $('#mupdate_r').hide();
                            }else{
                                $('#mupdate').show(); 
                                $('#mupdate_r').show();
                            } $('#vwin').window('open'); ">Re-Submit</a>  
                            
                            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected_treated(); if($('#vmemoid_x').val() == ''){ alert('No mail has been selected!'); }else{window.location='voucher.php?r_val=<?php echo $r_val; ?>&id='+btoa($('#vmemoid_x').val()); }">Raise Voucher</a>
                                    
                            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected_treated(); if($('#vmemoid_x').val() == ''){ alert('No mail has been selected!'); }else{window.location='voucher_sal.php?r_val=<?php echo $r_val; ?>&id='+btoa($('#vmemoid_x').val()); }">Voucher (PAYE)</a>
                                    
                            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected_treated(); if($('#vmemoid_x').val() == ''){ alert('No mail has been selected!'); }else{window.location='journal_entry2.php?r_val=<?php echo $r_val; ?>&id='+btoa($('#vmemoid_x').val()); }">Journal</a>

                        </div>
                        <div id="outmail"></div><div id="out_query"></div>
                        <div id="xwin" class="easyui-window" title="Memo/Mail Details" style="width:620px;height:400px;padding:10px;" 
                            data-options="
                            modal:true,
                            closed:true,
                            iconCls:'icon-tip',
                                onResize:function(){
                                    $(this).window('hcenter');
                                }">
                            <form id= 'outmails'  enctype="multipart/form-data" name="outmails">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                    <td height="33" align="left" valign="middle">Memo ID:</td>
                                    <td height="33" align="left" valign="middle"><div id="hmemoid"></div></td>
                                    </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle">From:</td>
                                    <td height="33" align="left" valign="middle"><div id="hmemofrom"></div></td>            
                                    </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle">Address/Unit:</td>
                                    <td height="33" align="left" valign="middle"><div id="haddress_unit"></div></td>            
                                    </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><label for="fdept">To: (Faculty/Dept)</label></td>
                                    <td height="33" align="left" valign="middle"><select name="fdept" id="fdept" onchange="swapcontent('load_unit',document.getElementById('fdept').value)" style="width:300px;">
                                                <option selected="selected" value="">---</option>
                                                <?php
                                                $res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
                                                while($rs_c=@mysqli_fetch_array($res_c))
                                                {
                                                    $dept_code=@$rs_c['dept_code'];
                                                    $dept_name=@$rs_c['dept_name'];
                                                    echo "<option value='$dept_code'>$dept_name</option>";
                                                }
                                                    echo "<option value='others'>Others...</option>";
                                                echo "</select>";
                                                ?>
                                            </select></td>
                                    </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><label for="unit">Unit/Address: </label></td>
                                    <td height="33" align="left" valign="middle"><span id="load_unit"><select name="unit" id="unit" style="width:300px;">
                                                <option selected="selected" value=''>---</option>
                                                <?php
                                                /*  $res_c=@mysqli_query($con, "select * from unittb order by unit_name");
                                                while($rs_c=@mysqli_fetch_array($res_c))
                                                {
                                                    $unit_code=@$rs_c['unit_code'];
                                                    $unit_name=@$rs_c['unit_name'];
                                                    echo "<option value='$unit_code'>$unit_name</option>";
                                                }
                                                echo "</select>";*/
                                                ?>
                                            </select></span></td>
                                    </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><label for="action">Action:</label></td>
                                    <td height="33" align="left" valign="middle"><select name="action" id="action" class="easyui-combobox" panelHeight="auto" style="width:300px;" >
                                        <option value="" selected>Select item...</option>
                                        <?php  $q =  mysqli_query($con, "select action from memo_actiontb order by action");
                                        while($r= mysqli_fetch_array($q, 3 )){
                                        echo '<option value="'. $r['action'] .'">'. $r['action'] .'</option>'; } ?></select></td>
                                </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><label for="remark">Comment:</label></td>
                                    <td height="33" align="left" valign="middle"><textarea id="remark" name="remark" class="easyui-textbox" style="width:300px;height:60px;"></textarea>
                                <input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id; ?>"/>
                                <input type="hidden" id="staff_category" name="staff_category" value="<?php echo $role; ?>"/>
                                <input type="hidden" id="tmemoid" name="tmemoid" value=""/>
                                <input type="hidden" id="memo_unit_code" name="memo_unit_code" value=""/></td>
                                </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle">Amount Requested:</td>
                                    <td height="33" align="left" valign="middle" nowrap><span id="hmemoamountd"></span></td>
                                </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle">Amount Approved:</td>
                                    <td height="33" align="left" valign="middle" nowrap><input type="text" class="easyui-textbox" id="hmemoamount" name="hmemoamount" style="width:300px;" /></td>
                                </tr>
                                <tr>
                                    <td height="33" align="center" valign="middle">&nbsp;</td>
                                    <td height="33" align="left" valign="middle" nowrap>
                                    <a id="idoc" class='iframe easyui-linkbutton' iconCls="icon-tip" href=""><strong><font color="#000099">View Document</font></strong></a>&nbsp;&nbsp;&nbsp;<a href="#" class="easyui-linkbutton" iconCls="icon-save" onClick="getSelected(); swapcontent('outmail',$('#unit').val(),$('#action').val(),$('#remark').val(),$('#tmemoid').val(),$('#login_id').val(),$('#staff_category').val(),$('#memo_unit_code').val(), $('#hmemoamount').val()); reload_grids();">Submit</a></td>
                                </tr>
                                <!--<tr>
                                    <td>&nbsp;</td>
                                    <td height="33" align="right"> &nbsp;
                                    <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected(); swapcontent('mark_mail',$('#tmemoid').val(),$('#login_id').val(),$('#staff_category').val(),$('#memo_unit_code').val());">Mark as Read</a></td>
                                </tr>-->
                                </table>
                            </form>
                        </div>
                        <div id="vwin" class="easyui-window" title="View/Edit Memo" style="width:600px;height:400px;padding:10px;" 
                            data-options="
                            modal:true,
                            closed:true,
                            iconCls:'icon-tip',
                                onResize:function(){
                                    $(this).window('hcenter');
                                }">
                            <form action="scriptfile_m.php?contentvar=mfileupload" method="post" enctype="multipart/form-data" target="upload_target2" onsubmit="startUpload2();" class="formx" id="editmail" name="editmail" >
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                                <tr>
                                    <td height="33" align="left" valign="middle"><strong>Memo ID:</strong></td>
                                    <td height="33" align="left" valign="middle"><div id="vmemoid"></div>
                                    <input type="hidden" id="vmemoid_x" name="vmemoid_x" value=""/>
                                    <input type="hidden" id="vlogin_id" name="vlogin_id" value="<?php echo $login_id; ?>"/></td>
                                    </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><strong>From:</strong></td>
                                    <td height="33" align="left" valign="middle"><input type="text" class="easyui-textbox" id="vmemofrom" name="vmemofrom" style="width:300px;" /></td>
                                    </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><strong>Address/Unit:</strong></td>
                                    <td height="33" align="left" valign="middle"><select name="vaddress_unit" id="vaddress_unit" style="width:300px;" >
                                                    <option value="" selected>Select item...</option>
                                                    <?php  $q =  mysqli_query($con, "select * from departmenttb order by dept_name");
                                                    while($r= mysqli_fetch_array($q, 3 )){
                                                        echo '<option value="'. $r['dept_code'] .'">'. $r['dept_name'] .'</option>';
                                                    }
                                                    ?>
                                                </select></td>            
                                    </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><strong>Memo Title/ Description:</strong></td>
                                    <td height="33" align="left" valign="middle"><textarea id="vmemodesc" name="vmemodesc" class="easyui-textbox" style="width:300px;height:60px;"></textarea></td>
                                </tr>
                                <tr>
                                    <td height="33" align="left" valign="left"><strong>Amount Requested:</strong></td>
                                    <td height="33" align="left" valign="middle"><input type="text" class="easyui-textbox" id="vmemoamount" name="vmemoamount" style="width:300px;" /></td>
                                </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><strong>Recieved into:</strong></td>
                                    <td height="33" align="left" valign="middle"><select name="vmemodept" id="vmemodept" style="width:300px;" >
                                                    <option value="" selected>Select item...</option>
                                                    <?php  $q =  mysqli_query($con, "select * from unittb order by id");
                                                    while($r= mysqli_fetch_array($q, 3 )){
                                                        echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>';
                                                    }
                                                    ?>
                                                </select></td>
                                </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><strong>Date/Time:</strong></td>
                                    <td height="33" align="left" valign="middle"><div id="vmemodate"></div></td>
                                    <td height="33" align="left" valign="middle">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td height="33" align="left" valign="middle"><strong>Status:</strong></td>
                                    <td height="33" align="left" valign="middle" nowrap><span id="vmemoaction"></span>&nbsp;&nbsp;<a id="vidoc" class='iframe easyui-linkbutton' iconCls="icon-tip" href=""><strong><font color="#000099">View Document</font></strong></a><span id="mupdate">&nbsp;&nbsp;<a href="#" class="easyui-linkbutton" iconCls="icon-save" onClick="getSelected(); swapcontent('editmails', $('#vmemodept').val(), $('#vmemofrom').val(), $('#vmemodesc').val(),$('#vmemoid_x').val(), $('#vmemoamount').val()); reload_grids();">Update</a></span></td>
                                </tr>
                                <!--<tr>
                                    <td height="33" align="center" valign="middle">&nbsp;</td>
                                    <td height="33" align="right" valign="middle">
                                    </td>
                                </tr>-->
                                <tr id="mupdate_r">
                                    <td height="33" align="left" valign="middle"><strong>Document:</strong></td>
                                    <td height="33" align="left" valign="middle">
                                        <span class="formx2" >
                                                
                                            <p id="f1_upload_form2" align="left"><br/>
                                                <!--<label class="labelx" for="myfile2">File: --> 
                                                    <input name="myfile2" type="file" size="20" />
                                                <!--</label>-->
                                                <input type="hidden" name="file_memo_id2" id="file_memo_id2" value="">
                                                <label>                         
                                                    <input type="submit" name="submitBtn2" class="sbtn2 buttonx" value="Upload" />
                                                </label>
                                            </p>
                                            <p id="f1_upload_process2">Loading...<br/><img src="images/ajax-loader.gif" /><br/></p>
                                <iframe id="upload_target2" name="upload_target2" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                                                <!--</form>-->
                                        </span>
                                    </td>
                                </tr>
                                </table>
                            </form>
                            <div id="editmails"></div>
                        </div>

                    </div><!-- END OF DIV FOR OUTGOING MAILS -->
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
                                            {field:'unit_name',title:'DEPT/UNIT',width:180,align:'left'},
                                            {field:'date',title:'DATE',width:60,align:'left'},
                                            {field:'remark',title:'REMARK',width:150,align:'left'},
                                            {field:'action',title:'ACTION',width:80,align:'left'}
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
                <strong><span style="color:#900">Not Treated mail</span> - <span style="color:#000">Treated mail</span></strong>
                </div>
                
               <div id="memo_movement"></div>
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