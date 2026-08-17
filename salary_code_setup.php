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
 $consolidated=false;
// echo $_REQUEST['op_id'];
if(isset($_REQUEST['op_id']) and $_REQUEST['op_id']=='consolidated'){
	$consolidated=true;
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
function sum(){
    //iterate through each textboxes and add keyup
        //handler to trigger sum event
        $(".amt").each(function() {
 
            $(this).keyup(function(){
                calculateSum();
            });
        });
  }
  function calculateSum() {
 
        var sum = 0;
        //iterate through each textboxes and add the values
        $(".amt").each(function() {
 
            //add only if the value is number
            if(!isNaN(this.value) && this.value.length!=0) {
                sum += parseFloat(this.value);
            }
 
        });
        //.toFixed() method will roundoff the final sum to 2 decimal places
        $("#total").html(sum.toFixed(2));
    }
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	//$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_b.php";
	var str;
	
if(cv=='login') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		//$("#roll").html('').show();
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
		//$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='pass_recovery_update') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,uname:v,email:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		//$("#roll").html('').show();
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
  if(cv=='salary_scale_name'){
	  $.post(url, $("#dept_frm").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a, function(data){
		 $(divid).html(data).show(); 
	  });
  }
  if(cv=='salary_code_section'){
	  $.post(url, $("#cons_frm").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a, function(data){
		 $(divid).html(data).show(); 
	  });
  }
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
	        <h2>Account Code Setup</h2>
                <p>Account codes</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Salary Scale Setup</i></h3> -->
			<form action="#" method="post" enctype="multipart/form-data" name="cons_frm" id="cons_frm">
            <p>
			  <table border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
				<th align="left">Category:<strong style="color:#F00">*</strong></th>
                <td align="left"><select name="category" id="category" tabindex="2" style="width:350px">
				<option selected="selected" value="">--</option>
                <!--<option value='ALLOWANCE'>ALLOWANCE</option>
                <option value='DEDUCTION'>DEDUCTION</option>-->
                                    <?php
                          //$res_c=@mysqli_query($con, "select * from departmenttb order by dept_name");
						  $res_c=@mysqli_query($con, "select DISTINCT id, folio_category from folio_categorytb WHERE status='Active' order by folio_category");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['id']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['folio_category']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name</option>";
                           }
                          echo "</select>";
						 ?>

                </select><input type="hidden" id="status" name="status" value="">
                    <input type="hidden" id="id" name="id" value="">
				</td>
                </tr>
				<tr>
				<th align="left">University Code:<strong style="color:#F00">*</strong></th>
                <td align="left"><input type="text" id="code" name="code" value="" style="width:350px" />
				</td>
                </tr>
				<tr>
				<th align="left">University Title:<strong style="color:#F00">*</strong></th>
                <td align="left"><input type="text" id="title" name="title" value="" style="width:350px" />
				</td>
                </tr>
				<tr>
				  <th align="left">NCOA Code:</th>
				  <td align="left"><input type="text" id="ncode" name="ncode" value="" style="width:350px" /></td>
			    </tr>
				<tr>
				  <th align="left">NCOA Title:</th>
				  <td align="left"><input type="text" id="ntitle" name="ntitle" value="" style="width:350px" /></td>
			    </tr>
				<tr><td>&nbsp;</td><td align="left">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('salary_code_section','save', '');" /> || 
                <input type="button" class="btn" name="sbtn" id="sbtn" value="Search" onclick="swapcontent('salary_code_section','search', '');" />
				</td>
              </tr>
              
</table>
</p>
				
			  <div id="salary_code_section" style="width:100%"></div>
				<div id="display"></div>
				<div id="roll"></div>
		  </form>
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