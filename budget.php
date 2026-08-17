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
	var url="scriptfile_c.php";
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

  if(cv=='budget_section') //start putme_login
  {
	//  alert(cv+" "+v+" "+a); //exit();
	//  alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
	  var mydata = (JSON.stringify($('form').serializeObject()));
	//	$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login



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
	        <h2>Heading of The page</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
        <h3><i>Budget</i></h3>
		  <p>
          </p>
          <form name="frm" id="frm">
			<table width="70%" border="0">
			  <tr>
			    <th>Department</th>
			    <td><select name="dept" id="dept" onchange="swapcontent('load_unit',this.value);">
			      <option selected="selected" value="">---</option>
			      <?php
                          $res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name</option>";
                           }
                          echo "</select>";
						 ?>
		        </select></td>
		      </tr>
			  <tr>
			    <th width="29%">Unit</th>
			    <td width="71%"><span id="load_unit"><select name="unit" id="unit">
			      <option selected="selected" value="">---</option>
			      <?php
                         /* $res_c=@mysqli_query($con, "select * from unittb order by unit_name");
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
			    <th>Folio</th>
			    <td><select name="folio" id="folio" class="txt">
			      <option selected="selected" value="">---</option>
			      <?php
                          $res_c=@mysqli_query($con, "select * from foliotb order by folio_code");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $folio_code=@$rs_c['folio_code'];
							  $folio_name=@$rs_c['title'];
                              echo "<option value='$folio_code'>$folio_name <=> ($folio_code)</option>";
                           }
                          echo "</select>";
						 ?>
		        </select></td>
		      </tr>
			  <tr>
			    <th>Budget Year</th>
			    <td><select name="b_year" id="b_year" class="txt">
			      <option selected="selected" value="">---</option>
                 <?php 
				  $dSess = @date('Y');
				for ($t= 2015; $t<=$dSess; $t++)
				{
					$tSession = "$t"; // . "/" . "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					echo "<option value='$tSession'>$tSession</option>";
				}
				?>
		        </select></td>
		      </tr>
			  <tr>
			    <th>Amount</th>
			    <td><input type="text" name="amount" id="amount" class="txt"/><input type="hidden" name="row_id" id="row_id" class="txt"/></td>
		      </tr>
			  <tr>
			    <th colspan="2"><div align="center">
			      <input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        <div id="roll"> </div>
        <div id="budget_section"> </div>
        </form>
        
			<p></p>
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