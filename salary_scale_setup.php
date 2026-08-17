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
		//$("#roll").html('').show();
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
  if(cv=='consolidated_pay'){
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
        <?php
				if($consolidated){
					?>
	        <h2>Consolidated Pay Setup</h2>
                <p>Setup components of consolidated salary.</p>
             <?php
				}else{
					?>
	        <h2>Salary Scale Name Setup</h2>
                <p>Setup salary scale name.</p>
                    <?php
				}
				?>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Salary Scale Setup</i></h3> -->
                <?php
				//echo $consolidated;
				if($consolidated){
					//display for consolidated pay setup
					?>
			<form action="#" method="post" enctype="multipart/form-data" name="cons_frm" id="cons_frm">
            <p>
			  <table border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
				<th align="left">Consolidated Pay Code:</th>
                <td align="left"><strong>Consolidated Pay (001)</strong>
				</td>
                </tr>
				<tr>
				<th align="left">Salary Scale:</th>
                <td align="left">
				<select name="category" id="category" tabindex="2" style="width:350px">
				<option selected="selected" value="">--</option>
				<?php
					$r=@mysqli_query($con, "select distinct scale_name from scale_nametb where status='Active'");
					while ($rcourse=@mysqli_fetch_array($r))
							echo "<option value='".$rcourse[0]."'>".$rcourse[0]."</option>";
					?>
                    </select>
                    <input type="hidden" id="status" name="status" value="">
                    <input type="hidden" id="id" name="id" value="">
				</td>
                </tr>
				<tr>
				<th align="left">Additional Allowances:</th><td align="left">
				<select name="code" id="code" tabindex="2" style="width:350px">
				<option selected="selected" value="">Code <|> Naration...</option>
					<?php
					//$r=@mysqli_query($con, "select distinct * from salary_codetb where category = 'ALLOWANCE' order by account_code");
					$r=@mysqli_query($con, "select distinct * from foliotb where not fundcenter in ('02') order by folio_code");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['title'];
							//$pcode=@$rcourse['account_code'];
							$pcode=@$rcourse['folio_code'];
							echo "<option value='$pcode'>$pcode <|> $scourse</option>";
						}
					?>
					</select>
				</select>
				</td>
                </tr>
<tr><td>&nbsp;</td><td align="left">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('consolidated_pay','save', '');" />
				</td>
  </tr>
  <tr>
				<td align="left" colspan="2"><hr>
                Defined salary structure across all Grade Levels and Steps for all staff category comprise of 'The Consolidated Pay' and the following allowances
				</td></tr>
</table>
</p>
				
				<div id="consolidated_pay">
                <?php
				$q= mysqli_query($con, "select * from consolidated_paytb order by category");
				  echo '<p>
				  <table width="100%" border="1" cellspacing="0" cellpadding="2" align="left" rules="rows" frame="box">
				    <tr align="left">
				      <th>SN</th>
				      <th>Category</th>
				      <th>Folio Code</th>
				      <!--<th>Description</th>-->
				      <th>Status</th>
				      <th>Action</th>
			        </tr>';
					$sn=0;
				while($r= mysqli_fetch_array($q, 3 )){
					$rid = $r['id'];
				    echo '<tr align="left" ';
					if($r['status']=='Inactive') echo 'style="color:#F00"';
					echo '>
				      <td>'.++$sn.'</td>
				      <td>'.$r['category'].'</td>
				      <td>'.get_folio_name($r['allowance_code'])." [".$r['allowance_code']."]".'</td>
					  <!--<td>'.get_account_code_narration($r['allowance_code'])." [".$r['allowance_code']."]".'</td>
				      <td>'.$r['description'].'</td>-->
				      <td>'.$r['status'].'</td>
				      <td><a href="#" onClick="$(\'#status\').val(\'Active\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'consolidated_pay\', \'update\', \''.$rid.'\');">Active</a> | 
					  <a href="#" onClick="$(\'#status\').val(\'Inactive\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'consolidated_pay\', \'update\', \''.$rid.'\');">Inactive</a>
					  <!-- | 
					  <a href="#" onClick="$(\'#id\').val(\''.$rid.'\'); swapcontent(\'consolidated_pay\', \'delete\', \''.$rid.'\');">Delete</a>
					  -->
					  </td>
			        </tr>';
				}
			      echo '</table></p>';
				  ?>
				</div>
				<div id="display"></div>
				<div id="roll"></div>
		  </form>                   
                    <?php
				}else{
					?>
			<form action="#" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
            <p>
			  <table border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
				<th align="left">Salary Scale Name:</th><td align="left">
				<input name="scalename" id="scalename" tabindex="1" style="width:150px" />
				</td>
                </tr>
				<tr>
				<th align="left">Staff Category:</th>
                <td align="left">
				<select name="category" id="category" tabindex="2" style="width:150px">
				<option selected="selected" value="">--</option>
				<option value="Academic">Academic</option>
				<option value="Non-Academic">Non-Academic</option>
				</select><input type="hidden" id="status" name="status" value=""><input type="hidden" id="id" name="id" value="">
				</td>
                </tr>
<tr><th colspan="2">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('salary_scale_name','save', '');" />
				<!--<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('salary_scale_section','search');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('salary_scale_section','view_all');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('salary_scale_name','refresh');" /> -->
				
				
				</th>
  </tr>
</table>
</p>
				<p>
				<div id="salary_scale_name">
                <?php
				$q= mysqli_query($con, "select * from scale_nametb");
				  echo '<table width="100%" border="1" cellspacing="0" cellpadding="2" align="left" rules="rows" frame="box">
				    <tr align="left">
				      <th>SN</th>
				      <th>Scale Name</th>
				      <th>Category</th>
				      <th>Status</th>
				      <th>Action</th>
			        </tr>';
					$sn=0;
				while($r= mysqli_fetch_array($q, 3 )){
					$rid = $r['id'];
				    echo '<tr align="left" ';
					if($r['status']=='Inactive') echo 'style="color:#F00"';
					echo '>
				      <td>'.++$sn.'</td>
				      <td>'.$r['scale_name'].'</td>
				      <td>'.$r['category'].'</td>
				      <td>'.$r['status'].'</td>
				      <td><a href="#" onClick="$(\'#status\').val(\'Active\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_scale_name\', \'update\', \''.$rid.'\');">Active</a> | 
					  <a href="#" onClick="$(\'#status\').val(\'Inactive\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_scale_name\', \'update\', \''.$rid.'\');">Inactive</a>
					  <!-- | 
					  <a href="#" onClick="$(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_scale_name\', \'delete\', \''.$rid.'\');">Delete</a>-->
					  </td>
			        </tr>';
				}
			      echo '</table>';
				  ?>
				</div></p>
				<div id="display"></div>
				<div id="roll"></div>
		  </form>
            <?php
				}
				?>
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