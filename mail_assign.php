 <?php @session_start();
 require_once('connect.php');
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

	
 if($login_status=='staff')
		    {
				
			//echo $role."=$login_id=>$login_status<br>123";
				$res_r=@mysqli_query($con, "select * from users_roletb where fileno='$login_id' and status='Active' and role like '%Budget and Expenditure%' or role like '%Expenditure Control%' ") or die ( mysqli_error($con));
				while($rs_r=@mysqli_fetch_array($res_r))
				 {
					 $role_use=trim(@$rs_r['role']);
				 }
				 if ($role_use2 == 'Expenditure Control')
				 {
					 $role_use = 'Budget and Expenditure';
					 }
			}
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
			  //exit;
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
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		
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
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			//alert('12345'); exit;
			//var addr = $('#address').val();
			// .tabs('getTabIndex',tab);
	//		 var test = (JSON.stringify($('#outmails').serializeObject()));
				//alert(h); //exit;
//alert(o);
			//if(a=="" || v=="" || b=="" || c=="" || d=="" || f=="" || g=="")
			//if( v=="")
			 //{
				//  alert('Complete all the fields ');
				 // $(divid).html('').show();  //stop loader from rolling
				  //exit();
			  //} //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#outmails').serializeObject()));
	   ////$("#show_ref").html('').show(); 
	  
		$.post(url,$("#outmails").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		//alert ('123');
			//exit;
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		});
		}//end of outgoing
if(cv=='out_query') // out query
		{
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			//alert('12345'); exit;
			//var addr = $('#address').val();
			// .tabs('getTabIndex',tab);
	//		 var test = (JSON.stringify($('#outmails').serializeObject()));
				//alert(h); //exit;
//alert(o);
			//if(a=="" || v=="" || b=="" || c=="" || d=="" || f=="" || g=="")
			//if( v=="")
			 //{
				//  alert('Complete all the fields ');
				 // $(divid).html('').show();  //stop loader from rolling
				  //exit();
			  //} //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#outmails').serializeObject()));
	   ////$("#show_ref").html('').show(); 
	  
		$.post(url,$("#outmails").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		//alert ('123');
			//exit;
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		});
		}//end of out query

if(cv=='assignmail') // Sharing mail
		{
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			if(a=="" )
			//if( || v=="")
			 {
				  alert('Complete all the fields ');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  } //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#assignmails').serializeObject()));
	   ////$("#show_ref").html('').show();
	  
		$.post(url,$("#assignmails").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		});
		}//end of sharing mail

if(cv=='raise_v') // to set mail as assinged  
		{
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			if(a==""  )
			//if( || v=="")
			 {
				  alert('Complete all the fields ');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  } //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#raise_vou').serializeObject()));
	   ////$("#show_ref").html('').show();
	  
		$.post(url,$("#raise_vou").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		 //alert(test);
		 //sexit;
		
		});
		}//end of set mail as assigned
if(cv=='raise_vs') // Send mail to voucher page
		{
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			if(a==""  )
			//if( || v=="")
			 {
				  alert('Complete all the fields ');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  } //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#raise_vous').serializeObject()));
	   ////$("#show_ref").html('').show();
	  
		$.post(url,$("#raise_vous").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		 //alert(test);
		 //sexit;
		
		});
		}//end of send to voucher page

if(cv=='treats') // delete voucher
		{
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			if(a==""  )
			//if( || v=="")
			 {
				  alert('Complete all the fields ');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  } //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#treat_v').serializeObject()));
	   ////$("#show_ref").html('').show();
	  
		$.post(url,$("#treat_v").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		// alert(test);
		// exit;
		
		});
		}//end of delete to voucher page


  }//end of swapcontent

 </script>

	<script>
	function getSelected(){
			var row = $('#dg').datagrid('getSelected');
			if (row){
				//alert('Memo ID:'+row.memo_id+"\nAmount:"+row.amount);
				//swapcontent('memo_movement', row.memo_id, row.amount);
				document.getElementById('tmemoid').value =row.memo_id;
		
			}
	}
		function getSelected(){
			var row = $('#dgout').datagrid('getSelected');
			if (row){
				//alert('Memo ID:'+row.memo_id+"\nAmount:"+row.amount);
				//swapcontent('memo_movement', row.memo_id, row.amount);
				document.getElementById('tmemoid').value =row.memo_id;
		
			}
		}
		function getSelections(){
			var ids = [];
			var rows = $('#dg').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				ids.push(rows[i].memo_id);
			}
			//alert(ids.join('\n'));
			//exit;
			document.getElementById('memoid').value =ids.join(',');
		}
	function reloadgrid(){
		$('#dg').datagrid('reload');
		$('#dgout').datagrid('reload');
		}	
		$(document).ready(function(e) {
            //$('#dlg').hide();
			//$('#dlg').dialog('close');
			
			$("#dept_unit").change(function(e) {
                //var opText = $("#dept_unit option:selected").text().slice(0, 3).toUpperCase() + "/" + $("#lastnum").val();
				$("#dept_txt").attr("value", $("#dept_unit option:selected").text().slice(0, 3).toUpperCase() );
				//alert(opText);
				//$("#dm_id").html(opText);
				//$("#memo_id").attr("value", opText);
            });
			$("#gid").click(function(e) {
				$("#dept_txt").attr("value", $("#dept_unit option").text().slice(0, 3).toUpperCase() );
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
	        <h2>Mails</h2>
                <p>Use this page to assign Mails </p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                <!--<h2>Tabs with Images</h2>
                <p>The tab strip can display big images.</p>-->
                <div style="margin:20px 0;"></div>
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:700px;" id="tt">
               
                    
                  
                  <?php // if ($role_use == 'Budget and Expenditure') { ?>
                    <div title="<span class='tt-inner'><img src='images/outmail.png'/><br>Assigned Mail</span>" style="padding:10px" >
                    <table id="dgout" title="" style="width:680px;" data-options="
                singleSelect:true,
                url: 'scriptfile_y.php?contentvar=mail_user&user=<?php echo $login_id;?>',
                rownumbers:true,method:'get',toolbar:'#gts', pagination:true,
                pageSize:10
            ">
        <thead>
            <tr>
            	<th data-options="field:'ck',checkbox:true"></th>
                <th data-options="field:'memo_id',width:80">ID</th>
                <th data-options="field:'memo_from',width:100">FROM</th>
                <th data-options="field:'description',width:180,align:'left'">DESCRIPTION</th>
                <th data-options="field:'amount',width:100,align:'left'">AMOUNT</th>
                <th data-options="field:'datein',width:80">DATE</th>
                <th data-options="field:'status',width:80">STATUS</th>
                <th data-options="field:'surname',width:80">NAME</th>
            </tr>
        </thead>
    </table>
    <div id = 'gts' style=" padding:2px 5px">
    <form id= 'raise_vous'  enctype="multipart/form-data" name="raise_vous"> 
                    
				<a href="#" class="easyui-linkbutton" iconCls="icon-ok"  onClick= "getSelected(); swapcontent('raise_vs',$('#tmemoid').val(),$('#login_id').val()); reloadgrid();">Create Voucher </a> 
                <input type="hidden" id="tmemoid" name="tmemoid" value=""/>
                <input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id; ?>"/>
                <input type="hidden" id="unit" name="unit" value="<?php echo $role_use; ?>"/>
                  
                  </form>
    </div>
    <div id="raise_vs"></div>
    
                  </div><!-- END OF DIV FOR OUTGOING MAILS -->
                   <?php // } ;?> 
                    <?php //if ($user == 'admin') {?>
  <div title="<span class='tt-inner'><img src='images/outmail.png'/><br>Treated Mail</span>" style="padding:10px" >
                    <table id="dg" title="" style="width:680px;" data-options="
                singleSelect:true,
                url: 'scriptfile_y.php?contentvar=treat_mail&user=<?php echo $login_id;?>',
                rownumbers:true,method:'get',toolbar:'#gtss', pagination:true,
                pageSize:10
            ">
        <thead>
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th data-options="field:'memo_id',width:80">ID</th>
                <th data-options="field:'payee_type',width:80">PAYEE TYPE</th>
                <th data-options="field:'payee_name',width:180,align:'left'">PAYEE NAME</th>
                <th data-options="field:'payee_acct_no',width:80,align:'left'">ACCOUNT NO</th>
                <th data-options="field:'amount_approved',width:100">AMOUNT APPR.</th>
                
            </tr>
        </thead>
    </table>
    <div id = 'gtss' style=" padding:2px 5px">
    <form id= 'treat_v'  enctype="multipart/form-data" name="treat_v"> 
                    
				<a href="#" class="easyui-linkbutton" iconCls="icon-ok"  onClick= "getSelections(); swapcontent('treats',$('#memoid').val(),$('#login_id').val()); reloadgrid();">Delete Voucher </a> 
                <input type="hidden" id="memoid" name="memoid" value=""/>
                <input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id; ?>"/>
                <input type="hidden" id="unit" name="unit" value="<?php echo $role_use; ?>"/>
                  
                  </form>
    </div>
    <div id="treats"></div>
    
                  </div>
                    <!--<div onClick="swapcontent('mailsearch');" title="<span class='tt-inner'><img src='images/searchmail.png'/><br>Search Mail</span>" style="padding:10px">
                         <table id="dg_group" style="width:650px;height:250px"
            url="scriptfile_y.php?contentvar=memo_withsub" 
            title=""
            singleSelect="true" fitColumns="true" pagination="true" 
                pageSize = "10" >
        <thead>
            <tr>
                <th field="memo_id" width="80">Memo ID</th>
                <th field="memo_from" width="100">From</th>
                <th field="description" align="right" width="80">Description</th>
                <th field="amount" align="right" width="80">Amount</th>
                <th field="datein" width="220">Date</th>
                <th field="memo_status" width="60" align="center">Status</th>
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
                        url:'scriptfile_y.php?contentvar=memo_sub&memo_id='+row.memo_id,
                        fitColumns:true,
                        singleSelect:true,
                        rownumbers:true,
                        loadMsg:'',
                        height:'auto',
                        columns:[[
                            {field:'memo_status',title:'STATUS',width:50},
                            {field:'dept_unit',title:'DEPT/UNIT',width:100,align:'left'},
                            {field:'date',title:'DATE',width:60,align:'left'},
                            {field:'action',title:'ACTION',width:100,align:'left'},
                            {field:'remark',title:'REMARK',width:150,align:'left'}
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
				url = 'scriptfile_y.php?contentvar=memo_meovement&memo_id=1';
			}
		}
		</script>
    <div id="dlg" class="easyui-window" title="Basic Window" data-options="iconCls:'icon-tip'" style="width:500px;height:200px;padding:10px;" closed="true">
    <form id="fm" method="post" novalidate>
    <table id="dgx" title="" style="width:680px;" data-options="
                singleSelect:true,
                url: 'scriptfile_y.php?contentvar=xxx',
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
    </div>-->
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
						{value:'Query',text:'Query'},{value:'Completed',text:'Completed'}],
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
						{value:'Query',text:'Query'},{value:'Completed',text:'Completed'}],
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
						{value:'Query',text:'Query'},{value:'Completed',text:'Completed'}],
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

</body>
</html>