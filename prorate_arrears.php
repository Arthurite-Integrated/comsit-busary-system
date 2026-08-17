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
<link rel="stylesheet" type="text/css" href="include/easyui.css">
   <link rel="stylesheet" type="text/css" href="include/icon.css">
	<link rel="stylesheet" type="text/css" href="include/demo.css">
    <link rel="stylesheet" href="css/tinybox.css" />
    <script type="text/javascript" src="include/jquery.min.js"></script>
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    <script src="datepicker/jquery-1.8.3.js"></script>
    <script src="datepicker/jquery-ui.js"></script>
<?php //include("required_jQuery_files.php");
include "function.php";?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
$(function(){
		   $("#as_date").datepicker({dateFormat:"yy-mm-dd"});
		   //$("#end_date").datepicker({dateFormat:"yy-mm-dd"});
		   }
		   );
	
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/wait.gif" width="30" height="30" alt="loading">').show();
	//$("#roll").html('<img src="images/wait.gif" width="100" height="100" alt="loading">').show();
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
  
 if(cv=='prorata_section') //start
  {

			if(v=='save' || v=='compute')
			 {
				 //var d=$("#days").val();
				 //if($("#fileno").val()=='' || $("#days").val()=='' || $("#month").val()=='' || $("#year").val()=='' || isNaN(d))
				 
				 if($("#fileno").val()=='' || $("#as_date").val()=='')// || $('#prorata_computation').html(''))
				 {
					 alert("It is mandatory to select \'File Number\' and \'Assumption of Duty Date\'!");// and Compute payment before you can proceed.");
					 exit();
				 }
			 }
			 
			if(v=='search')
			 {
				 if($("#fileno").val()=='')
				 {
					 alert("Please select File Number");
					 $(divid).html('').show();
					 //$('#roll').html('').show();
					 exit();
				 }
			 }
			 
			
			//var mydata=JSON.stringify($('#frm').serializeObject());
			
			$.post(url,$("#frm").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
				
				//if(v=='compute')
				//$("#prorata_computation").html(data).show();
				$(divid).html('').show();	
				$('#display2').html(data).show();
				$('#roll').html('').show();
			 
			if(v=='save')
			    {
					  //swapcontent('prorata_section','refresh');
					$("#fileno").val('');
					$("#get_staff_data").html('');
					$("#comment").val('');
				}
			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					$('#get_staff_data').html('').show();
					$("#fileno").val('');
					$("#comment").val('');
					$("#prorata_computation").html('');
					$("#as_date").val()('');
					//$('#roll').html('').show();
					//exit();
				  }//end of refresh div i.e to refresh the data dispay previously on selection of another departm	
			});//end serialize		
  } // End of prorata section
  
  if(cv=='prorata_computation'){
	  if(v!=''){
		  
		  $.post(url, $('#frm').serialize()+"&contentvar="+cv, function(data){
			  $(divid).html(data).show();
		  });
	  }
  }
  
  if(cv=='salary_computation_section') //start putme_login
  {
      //alert("CV:"+cv+" "+v+" "+a); 
	 //alert($('#frm').serialize());
	  //exit();
		if($('#fileno').val()=='' || $('#month').val()=='' || $('#year').val()=='')
			{
				alert('Supply the File No, Month and Year');
				$(divid).html('').show();	
				$('#display').html('').show();
				$('#roll').html('').show();
				exit();
			}
			
  	 var mydata=JSON.stringify($('#frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			
			$.post(url,{contentvar:cv,mydata:mydata,action:v},function(data){	//a is the id of rec to edit/upd	
			//alert(data); exit();	
			$(divid).html('').show();	
			$('#display').html('').show();
			$('#roll').html('').show();
			 $("#amount").val(data); 			
		});
		
  } //end of salary computation
  
  if(cv=='get_staff_data'){
	  if(v != ''){
		  $.post(url, {contentvar:cv, fileno:v}, function(data){
			  $(divid).html(data).show();
			  $('#roll').html('').show();
		  });
	  }
  }
  
} //end of swapcontent
 </script>
</head>
<body class="subpage">

<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
	<?php require_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>Salary Proration<!-- for Arrears--> </h2>
                <p>&nbsp;</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
        <h3><i>Salary Proration</i> for Arrears </h3> -->
        <p>
        <form enctype="multipart/form-data" name="frm" id="frm">
		  <table width="95%" border="1" cellpadding="3" cellspacing="0" frame="box" rules="cols">
		    <tr>
		      <th height="60" colspan="2" align="center" valign="middle"><div align="center">File Number<br />
		        <select name="fileno" id="fileno" style="width:450px" onChange="$('#get_staff_data').html(''); $('#prorata_computation').html(''); swapcontent('get_staff_data', $('#fileno').val());">
		          <option selected="selected" value="">---</option>
		          <?php
                          $res_c=@mysqli_query($con, "select * from stafftb where status='Active' and fileno not in ('weathstone','admin') order by fileno");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $fileno=@$rs_c['fileno'];
							  //$name=strtoupper($rs_c['surname']).",".ucfirst(strtolower($rs_c['first_name']))." ".ucfirst(strtolower($rs_c['other_name']))."<=>".$rs_c['fileno'];
							  $name=$rs_c['fileno']." | ".strtoupper($rs_c['surname']).", ".ucfirst(strtolower($rs_c['first_name']))." ".ucfirst(strtolower($rs_c['other_name']));
                              echo "<option value='$fileno'>$name</option>";
                           }
                          echo "</select>";
						 ?>
	            </select>
		        <br />
	          </div></th>
	        </tr>
		    <tr>
		      <td colspan="2" align="center" valign="middle"><div id="get_staff_data"></div></td>
		      </tr>
		    <!--<tr>
		      <th width="50%" align="center" valign="middle">Year <br />
                  <select name="year" id="year" style="width:120px">
                    <option selected="selected" value="">---</option>
                    <?php
                          for($i=date('Y');$i>=2015; $i--)
                           {
                              echo "<option value='$i'>$i</option>";
                           }
                          echo "</select>";
						 ?>
                  </select>
                </th>
		      <th width="50%" align="center" valign="middle">Month
              <br />
              <select name="month" id="month" style="width:120px">
                <option selected="selected" value="">---</option>
                <?php
                          $res_c=@mysqli_query($con, "select * from monthtb order by month_code");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $month_code=@$rs_c['month_code'];
							  $month_name=@$rs_c['month_name'];
                              echo "<option value='$month_code'>$month_name</option>";
                           }
                          echo "</select>";
						 ?>
              </select></th>
  </tr>-->
		    <tr>
		      <th width="50%" align="center" valign="middle">Assumption of Duty Date<br />
              <input type="text" name="as_date" id="as_date" onChange="$('#prorata_computation').html('');" /></th>
		      <th width="50%" align="center" valign="middle"><input type="button" name="button4" id="button4" value="Compute Payment" class="submit_btn" onclick="swapcontent('prorata_computation', $('#as_date').val());"/></th>
		      </tr>
		    <tr>
		      <td colspan="2" align="center" valign="middle"><p><div id="prorata_computation"></div></p>
		        <!--swapcontent('prorata_computation', $('#as_date').val()); ||| swapcontent('prorata_section','compute');
                <p>No of Days<br />
		          <input name="days" type="text" id="days" size="7" onBlur="swapcontent('salary_computation_section');" style="width:120px" />
		          <br>
		          Prorated Amount <br />
		          <input name="amount" type="text" id="amount" style="width:120px" />
	            </p>--></td>
		      </tr>
		    <tr>
		      <!--<th>Status<br />
<select name="status" id="status" class="txt">
		        <option selected="selected">---</option>
		        <option value="Active">Active</option>
		        <option value="Inactive">Inactive</option>
</select></th> -->
		      <th colspan="2" align="center" valign="middle">Remarks <br />
              <textarea name="comment" id="comment" cols="45" rows="2" style="width:450px"></textarea></th>
            </tr>
			 
		    <tr>
		      <th colspan="2" align="center" valign="middle"><input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('prorata_section','save');"/>
	          <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('prorata_section','search');"/>
             <input type="button" name="button5" id="button5" value="View All" class="btn" onclick="swapcontent('prorata_section','view');"/>
	          <input type="button" name="button3" id="button3" value="Refresh" class="btn" onclick="swapcontent('prorata_section','refresh');"/></th>
	        </tr>
	      </table>
        <div id="display"></div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form>
          </p>
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