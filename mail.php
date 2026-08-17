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
            $('#tt').tabs('select', 0);
			
			$("#dept_unit").change(function(e) {
				var dstr=$("#dept_unit option:selected").text().split(' ');//.toUpperCase();
				$("#dept_txt").attr("value", dstr[0] );
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
		
        function loadMailGrid(){
            var fro=$('#dFrm').val();
            var to=$('#dTo').val();
            var dataOpt = "singleSelect:true,url: 'scriptfile_m.php?contentvar=incoming_mail&sdate="+fro+"&edate="+to+",rownumbers:true,method:'get',toolbar:'#tb', pagination:true,pageSize:10,rowStyler: function(index,row){if (row.read_status == 'Unread'){return 'color:#900;font-weight:bold;';}}";
            $('#dg').attr('data-options', dataOpt);
            //alert($('#dg').attr('data-options'));
        }

        function open_window(index){
            if(index==1) window.location='mail_incoming.php';
            else if(index==2) window.location='mail_treated.php';
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
                <!--<h2>Tabs with Images</h2>
                <p>The tab strip can display big images.</p>-->
                <div style="margin:20px 0;"></div>
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:700px;" id="tt">
                <?php /*if ((strtolower($role) == 'clerk' || strtolower($role) == "super admin") || (strtolower($role) == 'fixed asset') ) if (strtolower($r_vals) != 'bursar')*/ {?>
                    <div title="<span class='tt-inner'><img src='images/newmail.png'/><br>New Mail</span>" style="padding:10px">
                    <form action="scriptfile_m.php?contentvar=inmails&files" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" class="formx" id="inmail" name="inmail" >
                    <table width="100%" border="1" cellspacing="0" cellpadding="10" style="border-color:#369; border-width:thin;">
                    <tr>
                        <td width="50%" align="left" valign="top">
                        <!--<form id= 'inmail'  enctype="multipart/form-data" name="inmail"> class="easyui-combobox"-->
                          <table width="50%" >
                            <tr>
                              <td width="45%"><label for="memo_from">From:</label></td>
                              <td width="55%"><input type="text" name="memo_from" id="memo_from" style="width:200px" required></td>
                            </tr>
                            <tr>
                              <td>Address/Unit:</td>
                              <td><select name="dept_addr" id="dept_addr" style="width:200px;" >
                                <option value="" selected>Select item...</option>
                                <?php  $q =  mysqli_query($con, "select * from departmenttb order by dept_name");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r['dept_code'] .'">'. $r['dept_name'] .'</option>';
							  }
							  ?>
                              </select></td>
                            </tr>
                            <tr>
                              <td><label for="desc">Memo Title/ Description:</label></td>
                              <td><textarea name="desc" id="desc" cols="25" rows="2" style="width:200px" required></textarea></td>
                            </tr>
                            <tr>
                              <td><label for="amount">Amount Requested/Recieved:</label></td>
                              <td><input type="text" name="amount" id="amount" style="width:200px" ></td>
                            </tr>
                            <tr>
                              <td><label for="dept_unit">Recieved intox:</label></td>
                              <td><select name="dept_unit" id="dept_unit" style="width:200px;" onChange="var dstr=$('#dept_unit option:selected').text().split(' ');
				$('#dept_txt').attr('value', dstr[0] )
                              swapcontent('getnextmemo',$('#dept_unit').val(), $('#dept_txt').val());" required>
							<option value="" selected>Select Dept/Unit...</option>
							 <?php  $q =  mysqli_query($con, "select * from unittb where dept_code='126' order by id");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>';
							  }
							  ?>
						</select><input type="hidden" name="dept_txt" id="dept_txt" value=""><input type="hidden" id="login_id" name="login_id" value="<?php echo $login_id; ?>"/></td>
                            </tr>
                            <tr>
                              <td>PV. No.:</td>
                              <td><input type="text" name="pvno" id="pvno" style="width:200px" ></td>
                            </tr>
                            <tr>
                              <td width="45%">Memo ID:</td>
                              <td width="55%"><!--<div id="dm_id" style="width:250px; background-color:#CCC; border:solid 1px;">&nbsp;</div>--><!--<input value="Get Memo ID" name="gid" type="button" onClick="swapcontent('getnextmemo',$('#dept_unit').val(), $('#dept_txt').val());" style="float:left;" class="btn"><div id="getnextmemo" style="width:180px; text-align:right;">&nbsp;<input type="hidden" name="memo_id" id="memo_id">-->
                              <a href="#" name="gid" onClick="swapcontent('getnextmemo',$('#dept_unit').val(), $('#dept_txt').val());" style="float:left;" class="easyui-linkbutton">Get Memo ID</a><div id="getnextmemo" style="width:220px; text-align:right;" class="easyui-textbox">&nbsp;<input type="hidden" name="memo_id" id="memo_id">
                              </div></td>
                            </tr>
                            <tr>
                              <td>&nbsp;</td>
                              <td><!--<input type="button" value="Recieve" class="btn" onClick="swapcontent('inmails',$('#memo_from').val(),$('#desc').val(),$('#amount').val(),$('#memo_id').val(),$('#dept_unit').val(),$('#login_id').val());"/><input type="reset" name="new_memo_ok" id="new_memo_ok" value="Reset" class="btn">-->
                              <!--<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onClick="swapcontent('inmails',$('#memo_from').val(),$('#desc').val(),$('#amount').val(),$('#memo_id').val(),$('#dept_unit').val(),$('#login_id').val());" id="new_memo_ok">Recieve</a>-->
                              <!--<input type="reset" name="new_memo_ok" id="new_memo_ok" value="Reset" class="btn">-->
                              </td>
                            </tr>
                            <tr>
                              <td colspan="2"><!--<div id="inmails"></div>--></td>
                            </tr>
                          </table>
                          <!--</form>-->
                    </td>
    <td width="50%" align="left" valign="top">
       <div id="container" style="margin: auto; width: 95%; border-top-width: 0px; border-width: 1px; border-style: solid; border-color: #000033; background-color: #FFFFFF;">
            <div id="header" style="padding: 0px; margin-top: 0px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px; background-image: url(images/header_bg.gif); background-repeat: repeat-x; height: 42px;">
            <div id="header_left" style="float: left; background-image: url(images/header_left.gif); background-repeat: no-repeat; height: 42px; width: 45px;"></div>
            <div id="header_main" style="float: left; padding: 5px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: bold; color: #FFFFFF; margin-top: 5px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">Upload supporting document...</div>
            <div id="header_right" style="background-image: url(images/header_right.gif); background-repeat: no-repeat; height: 42px; width: 6px; float: right;"></div></div>
            <div id="content" style="padding: 5px; font-family: Geneva, Arial, Helvetica, sans-serif; font-size: 12px; font-weight: normal; color: #666666;">
						<!--<form action="upload.php" method="post" enctype="multipart/form-data" target="upload_target" onsubmit="startUpload();" class="formx" >-->
                 <span class="formx" >
                        <p id="f1_upload_process">Loading...<br/><img src="images/ajax-loader.gif" /><br/></p>
                     <p id="f1_upload_form" align="center"><br/>
                         <label class="labelx" for="myfile">File:  
                              <input name="myfile" type="file" size="30" accept="application/pdf" />
                         </label>
                         <input type="hidden" name="file_memo_id" id="file_memo_id" value="">
                         <label>
                             <input type="submit" name="submitBtn" class="sbtn buttonx" value="SUBMIT" />
                         </label>
                     </p>
          <iframe id="upload_target" name="upload_target" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
          [Document Type: pdf]
          <!--</form>-->
                 </span>
             </div>
           </div>
           <div id="inmails"></div>
               </td>
          </tr>
        </table>
</form>
                        
                        <p>&nbsp;</p>
                  </div>
                    <?php  } ?>
                  <div title="<span class='tt-inner' onClick='open_window(1);'><img src='images/inmail.png'/><br>Incoming Mail</span>" style="padding:10px"></div>
                  <div title="<span class='tt-inner' onClick='open_window(2);'><img src='images/outmail.png'/><br>Treated Mail</span>" style="padding:10px" ></div>


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