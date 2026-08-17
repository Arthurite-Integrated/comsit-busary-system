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
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
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
                                

<center><strong>
<img src="<?php echo $val_str[1];?>" width="50" height="50"/><br>
<?php echo $val_str[0]."<br><u><i>Current Staff List as at ".date("F, Y")."</u></i>"."<br/><br/><!--Staff Category :".ucfirst(strtolower($status))."  ".ucwords(strtolower($category));?>-->
</strong>
<?php
$sql="select * from stafftb where status='Active'";

	 if($dept !="")
	 	$sql .=" and dept_code='$dept'";
	 if($position !="")
	 	$sql .=" and rank='$position'";
	 if ($staff !="all")
	 	{
			$fileno=@set_comma_breakdown($fileno);
	 		$sql .=" and fileno in ($fileno)";
			
		}
	if($status !="")
	 	$sql .=" and staff_status='$status'";
	if($category !="")
	 	$sql .=" and category='$category'";
		
		$sql .=" order by category,dept_code,level desc,surname";
	//echo '<br>',$sql;	
$db->sql("$sql");
			if(get_magic_quotes_gpc()){ $t= @json_decode(stripslashes($db->getResult()));$s=@json_decode(stripslashes($t->data)); }
			else{ $t= @json_decode($db->getResult());  $s=@json_decode($t->data);}
			if($t->row <=0)
				{
					echo "<script>alert('No record found in the database');</script>";
					exit();
				}
			$scalename=@get_current_scalename();
			$fullname=@get_staff_name($fileno) ;
			$staffstatus=@get_staff_status($s->level);
			$department=@get_dept_name($s->dept_code); 
			
			
?>
<center>
<?php echo "<b><i>Total Number of Record(s) : ",$t->row,"</b></i>";?>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
<tr>
<th>S/No</th><th>Name</th><th>File No</th><th>Department</th><th>Category</th><th>Status</th><th>Date of First Appt.</th><th>Date of Present Appt.</th><th>Qualification</th><th>Rank</th>
<th>Level</th><th>Date of Birth</th><th>Sex</th><th>State</th><th>L.G.A.</th><!--<th>Remark</th>-->
</tr>

<?php
//echo $s;

if($t->row ==1)
{

?>
<tr>
<td>&nbsp;<?php echo $key+1 ;?></td><td>&nbsp;<?php echo get_staff_name($s->fileno) ;?></td><td>&nbsp;<?php echo $s->fileno ;?></td>
<td>&nbsp;<?php echo get_dept_name($s->dept_code) ;?></td>
<td>&nbsp;<?php echo $s->category ;?></td><td>&nbsp;<?php echo get_staff_status($s->level) ;?></td>


<td>&nbsp;<?php echo $s->date_of_1st_appt ;?></td><td>&nbsp;<?php echo $s->date_of_present_appt ;?></td><td>&nbsp;<?php echo $s->qualification ;?></td><td>&nbsp;<?php echo $s->rank ;?></td><td>&nbsp;<?php echo $s->level ;?></td><td>&nbsp;<?php echo $s->date_of_birth ;?></td><td>&nbsp;<?php echo $s->sex ;?></td><td>&nbsp;<?php echo @get_state($s->state_id) ;?></td><td>&nbsp;<?php echo @get_lga($s->state_id,$s->lga_id) ;?></td><!--<td>&nbsp;</td>-->

</tr>


<?php
}
else
{
foreach($s as $key => $ss)
{
	//echo "$key==>$ss->fileno<br>";
?>
<tr>
<td>&nbsp;<?php echo $key+1 ;?></td><td>&nbsp;<?php echo get_staff_name($ss->fileno) ;?></td><td>&nbsp;<?php echo $ss->fileno ;?></td>
<td>&nbsp;<?php echo get_dept_name($ss->dept_code) ;?></td>
<td>&nbsp;<?php echo $ss->category ;?></td><td>&nbsp;<?php echo get_staff_status($ss->level) ;?></td>


<td>&nbsp;<?php echo $ss->date_of_1st_appt ;?></td><td>&nbsp;<?php echo $ss->date_of_present_appt ;?></td><td>&nbsp;<?php echo $ss->qualification ;?></td><td>&nbsp;<?php echo $ss->rank ;?></td><td>&nbsp;<?php echo $ss->level ;?></td><td>&nbsp;<?php echo $ss->date_of_birth ;?></td><td>&nbsp;<?php echo $ss->sex ;?></td><td>&nbsp;<?php echo get_state($ss->state_id) ;?></td><td>&nbsp;<?php echo get_state($ss->lga_id) ;?></td><td>&nbsp;</td>

</tr>


<?php

}// end of foreach
}//end of else

?>

</table>


<br><br><input name="button" type="button" onClick="window.print();exitform();return false; 
" value=" Print this page " />

</center>
                
                                
           
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