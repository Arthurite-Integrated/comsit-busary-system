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
<link rel="stylesheet" type="text/css" href="include/jquery.dataTables.min.css">
 
<script type="text/javascript" src="include/jquery.dataTables.min.js"></script> 
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
		
		function getSelections(){
			var ids = [];
			var rows = $('#dg').datagrid('getSelections');
			for(var i=0; i<rows.length; i++){
				ids.push(rows[i].memo_id);
			}
			alert(ids.join('\n'));
			//document.getElementById('tmemoid').value=ids.join('\n');
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
            //$('#dlg').hide();
			//$('#dlg').dialog('close');
            
			$('#tt').tabs('select', 1);
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
			$('.sbtn .buttonx').click(function(e) {
				var a = $('#memo_from').val();
				alert(a);
				$('#dept_addr').val('');
				$('#desc').val('');
				$('#amount').val('');
				$('#dept_unit').val('');
            });
        });	
		
        function open_window(index){
            if(index==1) window.location='mail.php';
            else if(index==2) window.location='mail_treated.php';
        }
    $(document).ready(function() { //parent.jQuery.colorbox.close(); 
        $(".iframe").colorbox({iframe:true, width:"53%", height:"100%"});
        $('#MyTable').DataTable( {  
            initComplete: function () {  
                this.api().columns().every( function () {  
                    var column = this;  
                    var select = $('<select><option value=""></option></select>')  
                        .appendTo( $(column.footer()).empty() )  
                        .on( 'change', function () {  
                            var val = $.fn.dataTable.util.escapeRegex(  
                                $(this).val()  
                            );  
                    //to select and search from grid  
                            column  
                                .search( val ? '^'+val+'$' : '', true, false )  
                                .draw();  
                        } );  
    
                    column.data().unique().sort().each( function ( d, j ) {  
                        select.append( '<option value="'+d+'">'+d+'</option>' )  
                    } );  
                } );  
            }  
        } );  
        $('#MyTable2').DataTable( {  
            initComplete: function () {  
                this.api().columns().every( function () {  
                    var column = this;  
                    var select = $('<select><option value=""></option></select>')  
                        .appendTo( $(column.footer()).empty() )  
                        .on( 'change', function () {  
                            var val = $.fn.dataTable.util.escapeRegex(  
                                $(this).val()  
                            );  
                    //to select and search from grid  
                            column  
                                .search( val ? '^'+val+'$' : '', true, false )  
                                .draw();  
                        } );  
    
                    column.data().unique().sort().each( function ( d, j ) {  
                        select.append( '<option value="'+d+'">'+d+'</option>' )  
                    } );  
                } );  
            }  
        } );  
        $('#MyTable3').DataTable( {  
            initComplete: function () {  
                this.api().columns().every( function () {  
                    var column = this;  
                    var select = $('<select><option value=""></option></select>')  
                        .appendTo( $(column.footer()).empty() )  
                        .on( 'change', function () {  
                            var val = $.fn.dataTable.util.escapeRegex(  
                                $(this).val()  
                            );  
                    //to select and search from grid  
                            column  
                                .search( val ? '^'+val+'$' : '', true, false )  
                                .draw();  
                        } );  
    
                    column.data().unique().sort().each( function ( d, j ) {  
                        select.append( '<option value="'+d+'">'+d+'</option>' )  
                    } );  
                } );  
            }  
        } );  
    } );
 </script>    <link rel="stylesheet" type="text/css" href="include/colorbox.css">
    <script type="text/javascript" src="include/jquery.colorbox.js"></script>
<link href="upload.css" rel="stylesheet" type="text/css" />
<script src="file/jquery.min.js"></script>
<script src="upload.js"></script>
</head>
<body class="subpage" onload="">
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
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:1200px;" id="tt">
                    <div title="<span class='tt-inner' onclick='open_window(1);'><img src='images/newmail.png'/><br>New Mail</span>" style="padding:10px"></div>
                    <div title="<span class='tt-inner' onClick=''><img src='images/inmail.png'/><br>Incoming Mail</span>" style="padding:10px">
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
                        if(isset($_POST['dFrm']) && $_POST['dFrm'] != ''){
                            $sdate = $_POST['dFrm'];
                            if(isset($_POST['dTo']) && $_POST['dTo'] != '') $edate = $_POST['dTo'];
                            else $edate = $_POST['dFrm'];
                        }else{
                            $sdate = $edate = date('Y-m-d');
                        }

		                $sq = "SELECT mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit, u.unit_name FROM ((memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id) INNER JOIN unittb u ON u.unit_code=mm.dept_unit) WHERE mm.memo_status='IN' and  mm.read_status='Unread' and (mm.dept_unit='".$_SESSION['userunit']."' or m.entry_by='".$_SESSION['login_id']."') AND m.entry_date BETWEEN '{$sdate}' AND '{$edate}' order by mm.id desc";
                        ?>
                        <div style="padding:2px 5px;">
                            <?php /*if(strtolower($role) == "bursar")*/{ ?>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected(); $('#xwin').window('open'); ">Process</a>
                            <?php } ?>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-tip" onClick="getSelected(); $('#mupdate').hide(); $('#mupdate_r').hide(); $('#vwin').window('open'); ">View</a>
                            <?php /*if(strtolower($role) != "bursar")*/{ ?>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-edit" onClick="getSelected(); $('#mupdate').show(); $('#mupdate_r').show(); $('#vwin').window('open'); ">Edit</a>
                            <?php } ?>
                            <?php /*if(strtolower($r_vals) == "accountant" or strtolower($r_vals) == "administrator")*/{ ?>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected(); if($('#tmemoid').val() == ''){ alert('No mail has been selected!'); }else{window.location='voucher.php?r_val=<?php echo $r_val; ?>&id='+btoa($('#tmemoid').val()); }">Raise Voucher</a>
                            <?php } //echo $role; ?> 
                            <?php /*if(strtolower($r_vals) == "accountant" or strtolower($r_vals) == "administrator")*/{ ?>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected(); if($('#tmemoid').val() == ''){ alert('No mail has been selected!'); }else{window.location='journal_entry2.php?r_val=<?php echo $r_val; ?>&id='+btoa($('#tmemoid').val()); }">Journal</a>
                            <?php } //echo $role; ?>
                            <?php /*if(strtolower($r_vals) == "accountant" or strtolower($r_vals) == "administrator")*/{ ?>
                            <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="getSelected(); if($('#tmemoid').val() == ''){ alert('No mail has been selected!'); }else{window.location='voucher_sal.php?r_val=<?php echo $r_val; ?>&id='+btoa($('#tmemoid').val()); }">Voucher (PAYE)</a>
                            <?php } //echo $role; ?> 
                        </div><hr>
                        <table id='MyTable' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box' style='font-size:10px;'>
                            <thead>
                                <tr>
                                    <th data-options="field:'ck',checkbox:true"></th>
                                    <th data-options="field:'memo_id',width:100">ID</th>
                                    <th data-options="field:'memo_from',width:100">FROM</th>
                                    <th data-options="field:'address_unit',width:100,hidden:'false'">ADDRESS/UNIT</th>
                                    <th data-options="field:'description',width:180,align:'left'">DESCRIPTION</th>
                                    <th data-options="field:'amount',width:100,align:'left'">AMOUNT</th>
                                    <th data-options="field:'dept_unit',width:100,align:'left'">DEPT/UNIT</th>
                                    <th data-options="field:'datein',width:90">DATE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $qmail=mysqli_query($con, $sq);
                                while($row=mysqli_fetch_array($qmail, 3)){
                                    $row['dept_unit']=='' ? $dept_to='Central' : $dept_to=get_unit_name('', $row['dept_unit']);
                                    ?>
                                    <tr  style="color:#900">
                                        <td><?=++$sn?></td>
                                        <td><?=$row['memo_id']?></td>
                                        <td><?=$row['memo_from']?></td>
                                        <td><?=is_numeric($row['address_unit']) && $row['address_unit'] !=''?$row['unit_name']:$row['address_unit'];?></td>
                                        <td><?=$row['description']?></td>
                                        <td><?=$row['amount']?></td>
                                        <td><?=$dept_to;?></td>
                                        <td><?=$row['datein']." ".$row['entry_time']?></td>
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div><!-- END OF DIV FOR INCOMING MAILS -->
                    
                    <div title="<span class='tt-inner' onClick='open_window(2);'><img src='images/outmail.png'/><br>Treated Mail</span>" style="padding:10px" ></div>
    
                </div>
             <strong><span style="color:#900">Not Treated mail</span> - <span style="color:#000">Treated mail</span></strong>   
<!-- END OF DIV FOR OUTGOING MAILS -->
                    
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

</body>
</html>