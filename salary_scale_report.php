<?php @session_start();
/*if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }*/
    $r_vals=base64_decode($_REQUEST['r_val']);
$role=$_SESSION['role'];
$login_status=$_SESSION['login_status'];
 $login_id=$_SESSION['login_id'];
 $login_id_base=base64_encode($login_id);
 //$role=$_SESSION['role'];
 $staff_category=$_SESSION['staff_category'];

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Report<?php //echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php 
//@include("required_jQuery_files.php");
//@include "function.php";?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<!--<script>
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
 </script>-->
</head>
<body>

<!--<div id="tooplate_wrapper">-->

	<!-- <div id="tooplate_sidebar">
	<?php //include_once("sidebar_main.php"); ?>
    </div> end of sidebar tooplate_sidebar-->
	
    <!--<div id="tooplate_main">-->
        <!-- <div id="tooplate_menu">
            <?php //include_once("menu_main.php"); ?>
        </div> end of tooplate_menu -->
        
        <!-- <div id="content_title_box">
	        <h2>Salary Scale Structure Report</h2>
                <p>&nbsp;</p>
        </div>end of content_title_box -->
   
       <!-- <div id="tooplate_content">
	        
        	<div class="content_box">-->
                                
<?php
$mode=base64_decode($_REQUEST['mode']);
$scalename=$_POST['scalename'];
//$category=$_REQUEST['category']; 
$cat=$_POST['cat'];
//require_once "function.php";
require_once "function_c.php";
require_once "myclass_m.php"; $br = new myclass_m();
//echo "$category $levels";
//echo get_company();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val = explode("***", get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////

$category = $br->get_any_value("category", "salary_scaletb", "scale_name", $scalename);
//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50'/></center>
<b><p align='center'><u>".strtoupper($val[0])."<br/>"."SALARY SCALE FOR  ".strtoupper($scalename)." (".$category.")</u></p></b>";
/*
if($cat == 'Monthly'){
 $mtp = 1;
 // or die( mysqli_error($con));
 //echo "No level";
 //$res_l= mysqli_query($con, "select distinct level from salary_scaletb where category='$category' and scale_name='$scalename' order by convert(level,decimal)");
}else{
	$mtp = 12;
//echo "with level";
  //$rl="select distinct level from salary_scaletb where level='$levels' and scale_name='$scalename' order by convert(level, decimal)";// or die( mysqli_error($con));
  //$res_l= mysqli_query($con, "select distinct level from salary_scaletb where level='$levels' and category='$category' and scale_name='$scalename' order by convert(level,decimal)");
}*/
$rl="select distinct level from salary_scaletb where scale_name='$scalename' order by convert(level,decimal)";
//echo $rl; exit;
  
  $res_l =  mysqli_query($con, $rl);
  if(!$res_l){
	  echo "Error:`:". mysqli_error($con); exit;
  }
  /*while($rs_l= mysqli_fetch_array($res_l, 3 ))
   {
      echo $levels=$rs_l['level'];
   }*/
  		//$rs_l['category'];
    //echo "<center><b>Staff Category: $category   &nbsp&nbsp&nbsp&nbsp&nbsp   Level:   $levels</b></center>";
	//echo "<center><b>Staff Category: $category   &nbsp&nbsp&nbsp&nbsp&nbsp   Level:   $levels</b></center>";
  
  ?>
<table width="95%" border="1" align="center" cellpadding="2" cellspacing="0" style="border:solid 1px #000">
  <tr height="30">
    <td width="129">&nbsp;</td>
       <?php 
	    // get_folio_code_amount($levels,$step,$category,$code)
		//$sql_scale=  mysqli_query($con, "select distinct step from salary_scaletb where level='$levels' and scale_name='$scalename'");
		$sql_scale=  mysqli_query($con, "select distinct step from salary_scaletb where scale_name='$scalename' order by convert(step,decimal)");
		   $step_array=array(); 
		   while ($rst_scale=  mysqli_fetch_array($sql_scale))
		   { 
		   $step_array[]=$step=$rst_scale['step'];
		    echo "<td nowrap><strong>Step $step </strong></td>";
		   }
		   ?>
		</tr>
		<?php
		$i=0;
  while($rs_l= mysqli_fetch_array($res_l, 3 ))
   {
      $levels=$rs_l['level'];
	  echo "<tr align='left' height='25'>";
	  echo "<th nowrap> Level $levels </th> ";
	  $sql_scale=  mysqli_query($con, "select distinct step from salary_scaletb where scale_name='$scalename' order by convert(step,decimal)");
	  while ($rst_scale=  mysqli_fetch_array($sql_scale))
		   { 
		   $stepx=$rst_scale['step'];
	  $sql_scalec=  mysqli_query($con, "select sum(amount) as s_amount from salary_scaletb where level='$levels' and scale_name='$scalename' and step='$stepx' and folio_code='001'");
		   while ($rst_scalex=  mysqli_fetch_array($sql_scalec))
		   { 
		   //$step=$rst_scale['step'];
		   //$folio_amt=get_folio_code_amount($levels,$step,$category,$folio);
		   
		    //echo "<td> ".number_format($folio_amt,2)." </td>" ;
			if($rst_scalex['s_amount'] > 0) echo "<td> ".number_format( ($rst_scalex['s_amount'] * $cat),2)." </td>" ;
			else  echo "<td>&nbsp;</td>" ;
		   }
		   }
			echo "</tr>";
		}
		//exit;	 
		/*{
		$sql_folio= mysqli_query($con, "select distinct folio_code from salary_scaletb where scale_name='$scalename'");
		$folio_array=array();
		while ($rst_folio= mysqli_fetch_array($sql_folio))
		  {
			//echo "<tr>";
			 $folio_array[]=$folio=$rst_folio['folio_code'];
			 //$folio_name = get_folio_name($folio);
			 $folio_name = get_account_code_narration($folio);
			 //echo "<th> $folio_name </th> ";
	      
		  //fill folio amount for each step 
	//$sql_scale=  mysqli_query($con, "select distinct step from salary_scaletb where level='$levels' and scale_name='$scalename'");
	$sql_scale=  mysqli_query($con, "select distinct step from salary_scaletb where level='$levels' and scale_name='$scalename' order by convert(step,decimal)");
		   while ($rst_scale=  mysqli_fetch_array($sql_scale))
		   { 
		   $step=$rst_scale['step'];
		   $folio_amt=get_folio_code_amount($levels,$step,$category,$folio);
		   
		    echo "<td> ".number_format($folio_amt,2)." </td>" ;
		   }
			echo "</tr>";
		}	 
			 
		echo "<tr>";
		//fill total amount for each step 
		  echo "<th> Total </th> ";
		   $sql_scale=  mysqli_query($con, "select distinct step from salary_scaletb where level='$levels' and scale_name='$scalename'");
		   while ($rst_scale=  mysqli_fetch_array($sql_scale))
		   { 
		   $step=$rst_scale['step'];
		   $folio_gross_amt=get_gross_total($levels,$step,$category);
		   
		    echo "<th> ".number_format($folio_gross_amt,2)." </th>" ;
		   }
			echo "</tr>";  */
		?>		 
	  
</table><hr style="border-bottom:1px dashed" />

<?php //}  //end of while per distinct level
//echo "end of game!";
?>                                
           
            <!-- </div>end of content box -->

        <!-- </div> end of content tooplate_content-->
    
    <!-- </div> end of content tooplate_main-->
	
    <!-- <div class="cleaner"></div>    
</div> end of wrapper tooplate_wrapper-->

<!-- <div id="tooplate_footer_wrapper">
	<?php //include_once("footer.php"); ?>
</div>end of footer  tooplate_footer_wrapper-->

</body>
</html>