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
                       
<div title="Asset Movement" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form name="frm5" id="frm5">
			<table width="70%" border="0">
            <tr>
			    <th align="left" valign="middle" height="33">Asset Number:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="identify_string" id="identify_string" style="width:300px" class="txt easyui-textbox" /></td>
		      </tr>
              <tr>
			    <th align="left" valign="middle" height="33">Movement Date:</th>
			    <td align="left" valign="middle" height="33"><input type="text" name="date_aq" id="date_aq" class="easyui-datebox" style="width:200px"  ></td>
		      </tr>
			  <tr>
			    <th width="29%" align="left" valign="middle" height="33">Asset Location:</th>
			    <td width="71%" align="left" valign="middle" height="33"><span id="location"><select name="location" id="location" style="width:200px" class="txt easyui-combobox">
                                <option selected="selected" value="">Select item...</option>
                                <?php
                          $res_c=@mysqli_query($con, "select * from locationtb order by dept,unit,room_no");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept=@$rs_c['dept'];
							  $unit=@$rs_c['unit'];
							  $room_no=@$rs_c['room_no'];
							  $loc_code=@$rs_c['loc_code'];
                              echo "<option value='$loc_code'>$dept||$unit||$room_no</option>";
                           }
                          echo "</select>";
						 ?>
                              </select> <input type="hidden" name="row_id" id="row_id" class="txt" /></span></td>
		      </tr>
			  
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
                <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('asset_movement','save');">Update</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('asset_movement','search');">Search</a>
			     <!-- <input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        
        <div id="roll"> </div>
        <div id="asset_movement"> </div>
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