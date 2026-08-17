 <?php $login_status=@$_SESSION['login_status'];
 ?>
 <script>
function swapcontent2(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
	var str;
		//alert('Helloooooooooooo');
	 if(cv=='logout') //start putme_logout
	  {
		  //alert('here'); exit;
			$.post(url,{contentvar:cv,ref:v},function(data){  //ajaxfile/scriptfile_a is called undernith
			//alert(data);
			$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
			window.location="index.php";
			
			});
	  }//end of putme_login
	  
	  if(cv=="load_charges")
			 {
				     //alert('yes'); exit; 
					  $.post(url,{contentvar:cv},function(data){			
					  $('#w').html(data).show();
					  $('#w').window('open');  //open the dialog to display info from ajax
					  });				 
			 }//end of load charges
			 
	    if(cv=='password_mgt') //start password mgt
				  {
						var newpwd=$('#newpwd').val();
						var con_newpwd=$('#con_newpwd').val();
						var oldpwd=$('#oldpwd').val();
						if(newpwd=='' || oldpwd=='')
						  {
							  alert("Old and New passwords are compulsory");
							  $(divid).html('').show();
							  exit;
						  } //end of test
						
						if(newpwd!=con_newpwd)
						 {
							 $.messager.alert('Password Error','Your new password does not match the confirm password');
							 $(divid).html('').show();
							 exit;
						 } //end of confirm password
						
						if(confirm("Are you sure you want to perform this operation?"))
						 {
								$.post(url,{contentvar:cv,ref:newpwd,oldpwd:oldpwd},function(data){  //ajaxfile/scriptfile_a is called undernith
								$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
								
								});
						 } //end of if confirm is true
						 else
						  $(divid).html('').show();
						 
				  }//end of password mgt
				  
	   if(cv=='close_dialog')
			 {
				 var div_id="#"+v;
				  $(div_id).window('close');
				  //$(div_id).window('open');
			 } //end of open dialog
} //end of swapcontent
  </script>
<?php 
   @require_once "connect.php";
   
   $res_p=@mysqli_query($con, "select * from companytb");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $name=@$rs_p['company_name'];
	 $logo=@$rs_p['company_logo'];
	 $val=$name."***".$logo;
	 
   $val_str=explode("***",$val);
   
   //////project title fetching
     $res_p=@mysqli_query($con, "select * from project_titletb where status='Active'");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $title=@$rs_p['title'];
	 $status=@$rs_p['status']; //active or inactive
	 $project_type=@$rs_p['project_type']; //bursary, HR, Both
	 $power_by=@$rs_p['power_by'];
	 
	 $val=$title."***".$project_type."***".$status."***".$power_by;
	 $val_title=explode("***",$val);
 ?>

		<ul>
			<li ><a href="main.php" >My menu</a></li>
			<!--<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>-->
			<li><a href="#"><?php $title= strtoupper(@$_SESSION['title']); echo $title; echo " ".strtoupper(@$_SESSION['surname'])." ".@$_SESSION['first_name']." ".@$_SESSION['other_name']." (".strtoupper(@$_SESSION['login_id']).")";?></a>						</li> 
			<!--<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li><li>&nbsp;</li>
			<li><a href="javascript:if(confirm('Are you sure you want to logout?')) swapcontent2('logout','index.php');">Sign out</a></li-->
			<li><a href="index.php">Sign out</a></li>
			<!--<li><a href="#" accesskey="5" title=""><?php echo " <span class='remenber_me'>Today: ".@date('l, F jS, Y')."</span>";?></a></li>-->
			
		</ul>
	
<span id="logout"> </span>