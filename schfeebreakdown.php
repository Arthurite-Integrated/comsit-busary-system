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

<?php
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_c.php";
	var str;


   if(cv=='load_level') //load level
  {
	  			  
	   $.post(url,{contentvar:cv,prog:v},function(data){
																		
		//$.post(url,$("#frm").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){  //ajaxfile/scriptfile_a is called undernith
        $("#roll").html('').show();
		$(divid).html(data).show();																									
		
		});
  }//end of school fees breakdown section 

if(cv=='schfeebreakdown_section') //start school fees breakdown section
  {
	  //alert('Hello'); exit();
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  if(v=='save')
	   {
		   if($("#folio_code").val()=='' || $("#student_level").val()=='' || $("#amount").val()=='')
		    {alert('Check your entries, Folio code, Level and Amount are compulsory.'); $("#roll").html('').show(); exit;}
	   }
	  var mydata=JSON.stringify($('#frm').serializeObject());
	  
	   $.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a},function(data){
																		
		//$.post(url,$("#frm").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){  //ajaxfile/scriptfile_a is called undernith
        $("#roll").html('').show();
		$(divid).html('').show();																							
		$("#display").html(data).show();
		if (v=='save')
		{
		 $("#student_level").val(''); $("#amount").val('');
		}
		 
		
		});
  }//end of school fees breakdown section 	

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
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
                <h3><i>School Fees Breakdown</i></h3>
			<p>
          <form name="frm" id="frm">
			<table  border="0">
			  <tr>
			    <th>Programme</th>
			    <td><select name="programme" id="programme" class="txt" onchange="swapcontent('load_level',this.value);">
                    <option selected="selected" value="">---</option>
                    <?php
                          $res_c=@mysqli_query($con, "select * from school_programmetb order by programme");
						// $k =   mysqli_num_rows($res_c); echo $k; exit;
						//  if( mysqli_num_rows($res_c)> 0) echo  mysqli_num_rows($res_c); exit;
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                               $prog_id=@$rs_c['programme'];
							   $prog_name=@$rs_c['programme'];
                              echo "<option value='$prog_id'> $prog_name</option>";
                           }
                        //  echo "</select>";
						 ?>
                </select></td>
			               
		      </tr>
			  <tr>
			    <th width="20%">Session</th>
			    <td width="25%"><select name="session" id="session">
				echo "<option value="">---</option>";
                  <?php 
			    //$res_jamb=@mysqli_query($con, "select * from settingstb where parameter='post_utme_payment'");
				 //$rs_jamb=@mysqli_fetch_array($res_jamb);
				 //$dSess=@$rs_jamb['session'];
				 
				 //$dSess_sp = @split("/",$dSess); 
				 
				 $dSessYear = date('Y');//$dSess_sp[1];
				for ($t=$dSessYear; $t >= 2015; $t--)
				{
					$t2 = $t - 1; $tSession = "$t2" . "/" . "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					echo "<option value='$tSession'>$tSession</option>";
				}
				?>
                </select></td>
			    <font color="#FF9900"><span id="foliodesc"></span></font></td>
		      </tr>
			   <tr>
			    <th width="20%">Folio Code</th>
			    <td width="25%"><select name="folio_code" id="folio_code" class="txt">
                  <option selected="selected" value="">---</option>
                  <?php
                          $res_c=@mysqli_query($con, "select * from foliotb order by folio_code");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $folio_code=@$rs_c['folio_code'];
							  $title=@$rs_c['title'];
                              echo "<option value='$folio_code'>$title <=> ($folio_code)</option>";
                           }
                          echo "</select>";
						 ?>
                </select></td>
			   </tr>
			  <tr>
			    <th>Level</th>
			    <td width="25%"><span id="load_level"><select name="student_level" id="student_level" class="txt">
                  <option selected="selected" value="">---</option>
                   
                </select></span></td>
		      </tr>
			 <tr>
			    <th>Amount</th>
			    <td width="25%"><input type="text" name="amount" id="amount" class="txt"> </td>
			 </tr> 
			  <tr>
			    <th colspan="2"><div align="center">
			      <input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('schfeebreakdown_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('schfeebreakdown_section','search');"/>
			    </div></th>
		      </tr>
	    </table>
        <div id="display"> </div>
        <div id="roll"> </div>
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