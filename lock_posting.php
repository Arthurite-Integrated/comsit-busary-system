<?php  @session_start();
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
          {   
                    var divid="#"+cv;
                    $(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
                    var url="scriptfile_a.php";
                    var str;
                    
                    if(cv=='lock_posting')
                    {
                              $.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  
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
	        <h2>Lock Account Posting</h2>
                <p>...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
                
                <div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Accounting Department Setup</i></h3> -->
			<p>
			<form action="#" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
			
            	<table cellpadding="10px">
				
				<tr>
				<th align="left" nowrap="nowrap">Lock Type:</th>
                                        <td>
                                                  <select id="locktype" name="locktype">
                                                            <option value="" selected>---...---</option>
                                                            <option value="Lock-Previous">Lock Previous Year</option>
                                                            <option value="Lock-Current">Lock Current Year (Month)</option>
                                                  </select>
                                        </td>
				</tr>
				<tr>
				<th align="left" nowrap="nowrap">Date:</th>
                                        <td>
                                                  <input type="month" name="lockdate" id="lockdate" value="" />
                                        </td>
				</tr>
				
				<tr>
                                                  <th colspan="2">
				<input type="button" class="btn" name="sbtn" id="sbtn" value=" UPDATE " onclick="swapcontent('lock_posting', 'save');" />
				</th>
                                        </tr>
				</table>
				<div id="lock_posting"></div>
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