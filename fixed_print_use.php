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
		
		});
	}//end of in mails
	


 
 	if(cv=='editasset') //Edit and update memo
		{
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			var test = (JSON.stringify($('#editmail').serializeObject()));
			var test = (JSON.stringify($('#editmail').serializeObject()));
			$.post(url,$("#editmail").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  
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
	        <h2>Fixed Asset</h2>
                <p>Use this page to print fixed assets record</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                <!--<h2>Tabs with Images</h2>
                <p>The tab strip can display big images.</p>-->
                <div style="margin:20px 0;"></div>
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:700px;" id="tt">
                <?php //if ((strtolower($role) == 'clerk' || strtolower($role) == "super admin") ) {?>
                    
                    <?php // } ?>
                    <div title="<span class='tt-inner' onClick='reload_grids();'><img src='images/inmail.png'/><br>Fix Asset Print</span>" style="padding:10px">
                    <table id="dg" title="" style="width:680px;" data-options="
                singleSelect:true,
                url: 'scriptfile_y.php?contentvar=asset_print',
                rownumbers:true,method:'get',toolbar:'#tb',pagination:true,
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
                <th data-options="field:'grn',width:180">GRN</th>
                <th data-options="field:'siv',width:180">SIV</th>
                <th data-options="field:'print',width:180,align:'left'">ACTION</th>

            </tr>
        </thead>
    </table>
        <div id="tb" style="padding:2px 5px;">
         
                
		    </div>
             <div id="assignmail_looks"></div>
            <div id="tb_2g" style="padding:2px 5px;">
     <!-- <a href="#" class="easyui-linkbutton" iconCls="icon-tip" onClick="getSelected_treated(); $('#mupdate').hide(); $('#mupdate_r').hide(); $('#vwin').window('open'); ">View</a>     -->             
	    </div>
            <div id="tb_2s" style="padding:2px 5px;">
       <!-- <a href="#" class="easyui-linkbutton" iconCls="icon-tip" onClick="getSelected_search(); $('#mupdate').hide(); $('#mupdate_r').hide(); $('#vwin').window('open'); ">View</a>              
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
            <td height="33" align="left" valign="middle"><label for="unit">To: (Dept/Unit)</label></td>
            <td height="33" align="left" valign="middle"><select name="unit" id="unit" class="easyui-combobox" panelHeight="auto" style="width:300px;" >
                <option value="" selected>Select item...</option>
                 <?php  $q =  mysqli_query($con, "select * from unittb where dept_code='Bursary' order by unit_name");
                  while($r= mysqli_fetch_array($q, 3 )){
                  echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>'; } ?> </select></td>
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
            <input type="hidden" id="hidentifystring" name="hidentifystring" value=""/>
            <input type="hidden" id="vlogin_id" name="vlogin_id" value="<?php echo $login_id; ?>"/></td>
            </tr>
             <tr>
            <td height="33" align="left" valign="middle"><strong>Aquistion Date</strong></td>
            <td height="33" align="left" valign="middle"><div id="acq_date"></div>
            </tr>
          <tr>
            <td height="33" align="left" valign="middle"><strong>Unit:</strong></td>
            <td height="33" align="left" valign="middle"><select name="hunits" id="hunits" style="width:300px;" >
							<option value="" selected>Select item...</option>
							 <?php  $q =  mysqli_query($con, "select * from unittb order by id");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>';
							  }
							  ?>
						</select></td>            
            </tr>
          <tr>
            <td height="33" align="left" valign="middle"><strong>Description:</strong></td>
            <td height="33" align="left" valign="middle"><textarea id="hdescription" name="hdescription" class="easyui-textbox" style="width:300px;height:60px;"></textarea></td>
          </tr>
          <tr>
            <td height="33" align="left" valign="left"><strong>Room No:</strong></td>
            <td height="33" align="left" valign="middle"><input type="text" class="easyui-textbox" id="hroomno" name="hroomno" style="width:300px;" /></td>
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
                    
                    <!-- END OF DIV FOR OUTGOING MAILS -->
                    <?php //if ($user == 'admin') {?>
                    <!--<div onClick="swapcontent('mailsearch');" title="<span class='tt-inner'><img src='images/searchmail.png'/><br>Search Mail</span>" style="padding:10px">
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