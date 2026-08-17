<?php 
//echo phpinfo();  exit;

@session_start();
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
				//swapcontent('memo_movement', row.memo_id, row.amount);
			}
		}
		
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
			$('#dgout').datagrid('reload');
		}
		
		$(document).ready(function(e) {
        });	
		
	</script>
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
                <!--<h2>Tabs with Images</h2>
                <p>The tab strip can display big images.</p>-->
                <div style="margin:20px 0;"></div>
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:900px;" id="tt">
                    <div title="<span class='tt-inner' onClick='reload_grids();'><img src='images/outmail.png'/><br>Folio Code</span>" style="padding:10px" >
          <table id="dgout" title="" style="width:880px;" data-options="
                mutipleSelect:true,
                url: 'scriptfile_m.php?contentvar=foliocode_grid',
                rownumbers:true,method:'get',toolbar:'#tb_2g', pagination:true,
                pageSize:10">
        <thead>
            <tr>
                <th data-options="field:'ck',checkbox:true"></th>
                <th data-options="field:'category',width:120">CATEGORY</th>
                <th data-options="field:'folio_code',width:80">UIL CODE</th>
                <th data-options="field:'title',width:180">UIL TITLE</th>
                <th data-options="field:'ncoa_code',width:100">NCOA CODE</th>
                <th data-options="field:'ncoa_title',width:180,align:'left'">NCOA TITLE</th>
                <th data-options="field:'exp',width:80,align:'left'">CODE CATEGORY</th>
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
        
        $(function(){
            $('#dg').datagrid({data:getData()}).datagrid('clientPaging');
        });
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
                  </div><!-- END OF DIV FOR OUTGOING MAILS -->
                    <?php //if ($user == 'admin') {?>
                    
                    <?php // } ?>
                    
                </div>
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