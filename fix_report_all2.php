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
 if(cv=="fix_report")
	{
          // var opt=$('input:radio[name="v_opt"]:checked').val();
		   //var cat =  document.getElementById('studcat').value;
		 //alert ("hi"); 
               
               json_data={
       "from":a,"to":b,"year":c,"location":d,"cat_type":e,"asset_cat":f },
              link=v +".php?id="+ json_data;
    
		//if(a=="" || b=="" || c=="" || d=="" || e=="")
		//	{
				//alert('All * fields are required in their correct format.');
				//$(divid).html('').show();
				//exit();
		//	}
		
	//	if(!$("#phoneno").val().match(/^\d[11]$/))
			//{alert('Format Not Match');exit();}
		//alert($("form").serialize());
		$.post(url,$("#frm").serialize()+"&contentvar=open_report&report_name="+v,function(data){
                  
		$(divid).html(data).show();
		//TINY.box.show(data,0,0,0,0);
		$(divid).html('').show();
		
		});
		event.preventDefault();
	}	 
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
 

 if(cv=="catdiv" ) // in mails
		{	
		//alert ("hi"); exit;
    $.post(url,{contentvar:cv,cat_type:v },function(data){
			$(divid).html(data).show(); 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		
		});
	}//end of in mails 
  
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
	        <h2>Schedule of Asset        </h2>
        </div>
        <!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
                <div class="easyui-tabs" style="width:auto;height:auto" id="tt"> <!-- begining of main tab-->
                  <div title="Report" style="padding:10px">  <!-- pending tab  -->
        <!--<h3><i>Budget</i></h3>
		  <p>
          </p>-->
          <form action="fix_report_all_pro_sch.php" method="post" enctype="multipart/form-data" name="frma" target="_blank" id="frma">
			<table width="70%" border="0">
			  <!--<tr>
			    <th align="left" valign="middle" height="33">From</th>
			    <td width="51%" height="33" align="left" valign="middle"><input type="text" name="from" id="from"  class="easyui-datebox"/></td>
			    <th width="19%" align="left" valign="middle">To</th>
			    <td width="1%" align="left" valign="middle"><input type="text" name="to" id="to"  class="easyui-datebox" /></td>
		      </tr>-->
			  <tr>
			    <th width="50%" height="33" align="right" valign="middle">Select Year:</th>
			    <td width="50%" height="33" ><select name="year" id="year" style="width:150px" class="txt">
			      <?php 
				  $dSess = @date('Y');
				for ($t= $dSess; $t >= 1970; $t--)
				{
					$tSession = "$t"; // . "/" . "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					echo "<option value='$tSession'>$tSession</option>";
				}
				?>
			      </select></td> 
			  </tr>
			  <tr>
			    <th colspan="2" align="left" valign="middle" height="33"><div align="center">
			      <input type="submit"  class="btn" name="btn" id="btn"  value="Search" />
			      <!-- <a href="#" name="button" id="button" class="easyui-linkbutton" iconCls="icon-save" onclick="swapcontent('fix_report','report1',$('#from').val(),$('#to').val(),$('#year').val(),$('#location').val(),$('#cat_type').val(),$('#asset_cat').val());">Go Get Them</a>&nbsp;&nbsp;&nbsp;
			      <a href="#" name="button2" id="button2" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent('category_save','search');">Search</a>
			      <input type="button" name="button" id="button" value="Save" class="btn" onclick="swapcontent('budget_section','save');"/>
			      <input type="button" name="button2" id="button2" value="Search" class="btn" onclick="swapcontent('budget_section','search');"/>-->
			      <!--<input type="button" name="button3" id="button3" value="View All" class="btn" onclick="swapcontent('budget_section','view');"/>-->
			      </div></th>
			    </tr>
	    </table>
       <!-- <span id="major_result"></span>
        <div id="display"> </div>
        <div id="roll"> </div>-->
        <div id="fix_report"> </div>
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