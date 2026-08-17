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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
	//$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
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
  if(cv=="catdiv" ) // filter types of asset
		{	
		//alert ("hi"); exit;
    $.post(url,{contentvar:cv,cat_type:v },function(data){
			$(divid).html(data).show(); 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		
		});
	}//end of filter types of asset
	
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

  if(cv=='asset_save') //start putme_login
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
if((v=='save' )&&($('#asset_title').val()=='' || $('#asset_code').val()=='' )) //$('#transdate').val()=='' || || $('#fileno').val()=='' 
			{
				alert('All fields are required ');
				$(divid).html('').show();	
				$('#display').html('').show();
				$('#roll').html('').show();
				exit();
			}
	  var mydata = (JSON.stringify($('#frm2').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login
if(cv=='category_save') //start category_save
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());

	  var mydata = (JSON.stringify($('#frm').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of category_save
if(cv=='supplier_save') //start supplier_save
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());

	  var mydata = (JSON.stringify($('#frm6').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of supplier_save
  if(cv=='prod_save') //start product_save
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());

	  var mydata = (JSON.stringify($('#frm7').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of product_save
  
  if(cv=='loc_save') //start save location
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	  var mydata = (JSON.stringify($('#frm3').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of save location
  if(cv=='life_save') //start save location
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	  var mydata = (JSON.stringify($('#frm4').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of save location
   if(cv=='asset_movement') //start save location
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	var date_aq = $('#date_aq').datebox('getValue');
  	  	var d1 = Date.parse(date_aq);
	  var mydata = (JSON.stringify($('#frm5').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a,sdate1:date_aq, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		//$('#w').html(data).show();
			//  $('#w').window('open');
		});
  }//end of save location
if(cv=='budget_capital') //start putme_login
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	  var mydata = (JSON.stringify($('#frm3').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv, action:v, r_id2:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
	}

if(cv=='prod_inflow_save') //start product_save
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
var date_aq = $('#date_aq').datebox('getValue');
  	  	var d1 = Date.parse(date_aq);
	  var mydata = (JSON.stringify($('#frm8').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a,sdate1:date_aq, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of product_save
if(cv=='budget_breakdown') //start putme_login
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	  var mydata = (JSON.stringify($('form').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id2:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
	}

  if(cv=='load_unit') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,dept_code:v},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login
if(cv=='load_folio') //start of load folio
  {
	  var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
	 // alert(12345);
	 // exit();
 		var test = (JSON.stringify($('#frm2').serializeObject()));
		
			if(v == "" || a == "")
			 {
				  //alert('You must select Dept/Unit!');
				  $(divid).html('').show();  //stop loader from rolling
				  $(divid).hide();
				  exit();
			  } //end of validation 
			  //alert (test);
			  //exit;
  	    var test = (JSON.stringify($('#frm2').serializeObject()));
		$.post(url,$("#frm2").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		//alert(data);
		//$("#amount2").attr("value", data);
		
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
  
} //end of swapcontent
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
	        <h2>Fixed Asset</h2>
                <p>Fixed Asset Setup and Management</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
                <div class="easyui-tabs" style="width:auto;height:auto" id="tt"> <!-- begining of main tab-->
                       <div title="Add Category" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form name="frm" id="frm">
			<table width="70%" border="0">
			  <tr>
			    <th align="left" valign="middle" height="33">Category Title:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="cat_title" id="cat_title" style="width:300px" class="txt easyui-textbox" /></td>
		      </tr>
			  <tr>
			    <th width="29%" align="left" valign="middle" height="33">Category Code:</th>
			    <td width="71%" align="left" valign="middle" height="33"><input type="text" name="cat_code" id="cat_code" style="width:300px" class="txt easyui-textbox" /></td>
		      </tr>
              <tr>
			    <th align="left" valign="middle" height="33">IPSAS Code:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="ipsas_code2" id="ipsas_code2" style="width:300px" class="txt easyui-textbox" /></td>
		      </tr>
              <tr>
			    <th align="left" valign="middle" height="33">IPSAS Title:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="ipsas_title2" id="ipsas_title2" style="width:300px" class="txt easyui-textbox" /></td>
		      </tr>
			  <tr>
			    
			    <input type="hidden" name="row_id" id="row_id" class="txt" /></td>
			    </tr>
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
                <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('category_save','save');">Save</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('category_save','search');">Search</a>
			      <!--<input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        <div id="roll"> </div>
        <div id="category_save"> </div>
        </form>
        </div>
          <div title="Add Asset" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form name="frm2" id="frm2">
			<table width="70%" border="0">
			  <tr>
			    <th align="left" valign="middle" height="33">Asset Name:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="asset_title" id="asset_title" style="width:300px" class="txt easyui-textbox" required/></td>
		      </tr>
			  <tr>
			    <th width="29%" align="left" valign="middle" height="33">Category Type:</th>
			    <td width="71%" align="left" valign="middle" height="33"><span id="cat_id"><select name="cat_id" id="cat_id" style="width:300px" >
			      <option selected="selected" value="">Select item...</option>
			      <?php
                          $res_c=@mysqli_query($con, "select * from asset_categorytb order by cat_title");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $cat_title=@$rs_c['cat_title'];
							  $cat_id=@$rs_c['cat_id'];
                              echo "<option value='$cat_id'>$cat_title</option>";
                           }
                          echo "</select>";
						 ?>
		        </select></span></td>
		      </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Asset Code:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="asset_code" id="asset_code" style="width:300px" class="txt easyui-textbox" /><input type="hidden" name="row_id" id="row_id" class="txt" /></td>
		      </tr>
              <tr>
			    <th align="left" valign="middle" height="33">IPSAS Code:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="ipsas_code" id="ipsas_code" style="width:300px" class="txt easyui-textbox" /></td>
		      </tr>
              <tr>
			    <th align="left" valign="middle" height="33">IPSAS Title:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="ipsas_title" id="ipsas_title" style="width:300px" class="txt easyui-textbox" /></td>
		      </tr>
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
                <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('asset_save','save');">Save</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('asset_save','search');">Search</a>
			      <!--<input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        <div id="roll"> </div>
        <div id="asset_save"> </div>
        </form>
        </div>
      <div title="Add Location" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form name="frm3" id="frm3">
			<table width="70%" border="0">
			  <tr>
			    <th align="left" valign="middle" height="33">Department:</th>
			    <td align="left" valign="middle" height="33"><select name="dept" id="dept" style="width:300px" >
			      <option selected="selected" value="">Select item...</option>
			      <?php
                          $res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_name'>$dept_name</option>";
                           }
                          echo "</select>";
						 ?>
		        </select></td>
		      </tr>
			  <tr>
			    <th width="29%" align="left" valign="middle" height="33">Unit:</th>
			    <td width="71%" align="left" valign="middle" height="33"><span id="unit"><select name="unit" id="unit" style="width:300px" >
			      <option selected="selected" value="">Select item...</option>
			      <?php
                          $res_c=@mysqli_query($con, "select * from unittb order by unit_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $unit_code=@$rs_c['unit_code'];
							  $unit_name=@$rs_c['unit_name'];
                              echo "<option value='$unit_name'>$unit_name</option>";
                           }
                          echo "</select>";
						 ?>
		        </select></span></td>
		      </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Room No:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="room_no" id="room_no" style="width:300px" class="txt easyui-textbox" /><input type="hidden" name="row_id" id="row_id" class="txt" /></td>
		      </tr>
              <tr>
			    <th align="left" valign="middle" height="33">Location Code:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="loc_code" id="loc_code" style="width:300px" class="txt easyui-textbox" /><input type="hidden" name="row_id" id="row_id" class="txt" /></td>
		      </tr>
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
                <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('loc_save','save');">Save</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('loc_save','search');">Search</a>
			      <!--<input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        <div id="roll"> </div>
        <div id="loc_save"> </div>
        </form>
        </div>
        <div title="Add Useful Life" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form name="frm4" id="frm4">
			<table width="70%" border="0">
			  <tr>
			    <th width="29%" align="left" valign="middle" height="33">Category Type:</th>
			    <td width="71%" align="left" valign="middle" height="33"><span id="cat_type"><select name="cat_type" id="cat_type" style="width:300px" >
			     <option selected="selected" value="">Select item...</option>
			      <?php
                          $res_c=@mysqli_query($con, "select * from asset_categorytb order by cat_title");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $cat_title=@$rs_c['cat_title'];
							  $cat_code=@$rs_c['cat_code'];
                              echo "<option value='$cat_code'>$cat_title</option>";
                           }
                          echo "</select>";
						 ?>
		        </select></span></td>
		      </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Useful Life:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="use_life" id="use_life" style="width:300px" class="txt easyui-textbox" /></td>
		      </tr>
              <tr>
			    <th align="left" valign="middle" height="33">Scrap Value:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="scrap_value" id="scrap_value" style="width:300px" class="txt easyui-textbox" /><input type="hidden" name="row_id" id="row_id" class="txt" /></td>
		      </tr>
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
                <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('life_save','save');">Save</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('life_save','search');">Search</a>
			      <!--<input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        <div id="roll"> </div>
        <div id="life_save"> </div>
        </form>
        </div>

<div title="Add Supplier" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form name="frm6" id="frm6">
			<table width="70%" border="0">
            <tr>
			    <th align="left" valign="middle" height="33">Supplier Name:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="sup_name" id="sup_name" style="width:300px" class="txt easyui-textbox" required/></td>
		      </tr>
			  <tr>
			    <th width="29%" align="left" valign="middle" height="33">Address:</th>
			    <td width="71%" align="left" valign="middle" height="33"><textarea name="sup_address" id="sup_address" cols="25" rows="2" style="width:200px" required></textarea><input type="hidden" name="row_id" id="row_id" class="txt" /></span></td>
		      </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Supplier Phone:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="sup_phone" id="sup_phone" style="width:300px" class="txt easyui-textbox" required /></td>
		      </tr>
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
                <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('supplier_save','save');">Save</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('supplier_save','search');">Search</a>
			     <!-- <input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        
        <div id="roll"> </div>
        <div id="supplier_save"> </div>
        </form>
        </div>
<div title="Add Product" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form name="frm7" id="frm7">
			<table width="70%" border="0">
            <tr>
			    <th align="left" valign="middle" height="33">Product Name:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="prod_name" id="prod_name" style="width:300px" class="txt easyui-textbox" required/></td>
		      </tr>
			  <tr>
			    <th width="29%" align="left" valign="middle" height="33">Specification:</th>
			    <td width="71%" align="left" valign="middle" height="33"><textarea name="description" id="description" cols="25" rows="2" style="width:200px" required></textarea><input type="hidden" name="row_id" id="row_id" class="txt" /></span></td>
		      </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Select:</th>
			    <td align="left" valign="middle" height="33"><select name="type" id="type">
			      <option selected="selected">Select</option>
			      <option value="Fix">Fixed Asset</option>
			      <option value="Consumable">Consumable</option>
			      </select></td>
			    </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Classification:</th>
			    <td align="left" valign="middle" height="33"><select name="cat_types" id="cat_types" style="width:200px"  onChange="swapcontent('catdiv', $('#cat_types').val());">
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
			    </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Type:</th>
			    <td align="left" valign="middle" height="33"><div id="catdiv">
			      
                </div></td>
		      </tr>
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
                <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('prod_save','save');">Save</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('prod_save','search');">Search</a>
			     <!-- <input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        
        <div id="roll"> </div>
        <div id="prod_save"> </div>
        </form>
        </div>
     <div title="Product Inflow" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form name="frm8" id="frm8">
			<table width="70%" border="0">
            <tr>
			    <th align="left" valign="middle" height="33">Product Name:</th>
			    <td align="left" valign="middle" height="33"><select name="prod_id" id="prod_id" style="width:200px" required="required">
			      <option selected="selected" value="">Select item...</option>
			      <?php
                          $res_c=@mysqli_query($con, "select prod_id,prod_name from fix_producttb order by prod_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              
							  $prod_id=@$rs_c['prod_id'];
							  $prod_name=@$rs_c['prod_name'];
                              echo "<option value='$prod_id'>$prod_name</option>";
                           }
                          echo "</select>";
						 ?>
			      </select></td>
		      </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Rate:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="rate" id="rate" style="width:200px" class="txt easyui-textbox" required="required"/></td>
			    </tr>
			  <tr>
			    <th width="29%" align="left" valign="middle" height="33">Quantity:</th>
			    <td width="71%" align="left" valign="middle" height="33"><select name="qty" id="qty" required="required">
			      <option value=""> Select</option>
			      <?php 
							//$dat = date('Y');
							for ($i = 1; $i <= 200; $i++)
							echo '<option value="'. $i .'">'. $i .'</option>';?>
			      </select>
			      <input type="hidden" name="row_id" id="row_id" class="txt" /></span></td>
		      </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Supplier:</th>
			    <td align="left" valign="middle" height="33"><select name="sup_id" id="sup_id" style="width:200px" required>
			      <option selected="selected" value="">Select Supplier...</option>
			      <?php
                          $res_c=@mysqli_query($con, "select * from suppliertb order by sup_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $id=@$rs_c['sup_id'];
							  $sup_name=@$rs_c['sup_name'];
							  
                              echo "<option value='$id'>$sup_name</option>";
                           }
                          echo "</select>";
						 ?>
			      </select></td>
			    </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Date Supplied:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="date_aq" id="date_aq" class="easyui-datebox" style="width:200px" required/></td>
			    </tr>
			  <tr>
			    <th align="left" valign="middle" height="33">Invoice No:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="invoice_no" id="invoice_no" style="width:200px" required="required" /></td>
		      </tr>
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
                <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('prod_inflow_save','save');">Save</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('prod_inflow_save','search');">Search</a>
			     <!-- <input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        
        <div id="roll"> </div>
        <div id="prod_inflow_save"> </div>
        </form>
        </div>   
        
        </div>
        </div>
			<!--<p></p>-->
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