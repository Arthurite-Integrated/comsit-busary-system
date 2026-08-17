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



	//@require_once "myclass_m.php";
	//@$bursary = new myclass_m();
	
	//@$udept = $bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
	
	?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<?php include("required_jQuery_files.php");
include "function.php";?>

<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
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
  if(cv=="inmails" ) // in mails
		{
			//alert(1234567890); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			//alert('12345'); exit;
			//var addr = $('#address').val();
			// .tabs('getTabIndex',tab);
			 var test = (JSON.stringify($('#inmail').serializeObject()));
				//alert(h); //exit;
//alert(o);
			//if(a=="" || v=="" || b=="" || c=="" || d=="" || f=="" || g=="")
			if(a=="" || v=="" || b=="")
			 {
				  alert('Complete all the fields ');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  } //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#inmail').serializeObject()));
	   ////$("#show_ref").html('').show(); 
	  //alert (test);
	 //exit;
	 
		$.post(url,$("#inmail").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		
		$(divid).html(data).show(); 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		//upload_supporting_doc(document.getElementById("memo_id").value);
		//pload_supporting_doc( $("#inmail").serialize()+"&contentvar="+cv+"&tabindex="+index+"&files" );
		});
		
/*	   		$.post(url,{contentvar:cv, val:test},function(data) || c=="" || d=="" || e=="" || f=="" || g==""{
			$(divid).html(data).show();
			});
*/
//alert(data); exit;
/*
			$.post(url,{contentvar:cv, formcontent:test, tabindex:index},function(data){
			$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
			
			
			});*/
		}//end of in mails
  

  if(cv=="catdiv" ) // in mails
		{	
		//alert ("hi"); exit;
    $.post(url,{contentvar:cv,cat_type:v },function(data){
			$(divid).html(data).show(); 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		 //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
	}//end of in mails
	


 
 	if(cv=='editasset') //Edit and update memodisposal
		{
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			var test = (JSON.stringify($('#editmail').serializeObject()));
			var test = (JSON.stringify($('#editmail').serializeObject()));
			$.post(url,$("#editmail").serialize()+"&contentvar="+cv+"&tabindex="+index,function(data){  
				$(divid).html(data).show(); 
			});
		}//end of outgoing
if(cv=='disposed') //Edit and update memodisposal
		{
			//alert ("hi"); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			var date_dis = $('#date_dis').datebox('getValue');
  	  	var d1 = Date.parse(date_dis);
			var test = (JSON.stringify($('#disposal').serializeObject()));
			var test = (JSON.stringify($('#disposal').serializeObject()));//sdate1:date_aq
			$.post(url,$("#disposal").serialize()+"&contentvar="+cv+"&sdate1="+date_dis+"&tabindex="+index,function(data){  
				$(divid).html(data).show(); 
			});
		}//end of outgoing
		
if(cv=='fixed_asset_save') //start of save asset
  {
	//  alert(cv+" "+v+" "+a); //exit();
	 // alert($("form").serialize()); //exit();
	//  var mydata = ($("form").serialize());
//if((v=='save' )&&($('#asset_title').val()=='' || $('#asset_code').val()=='' )) //$('#transdate').val()=='' || || $('#fileno').val()=='' 
			/*{
				alert('All fields are required ');
				$(divid).html('').show();	
				$('#display').html('').show();
				$('#roll').html('').show();
				exit();
			}*/
		var date_aq = $('#date_aq').datebox('getValue');
  	  	var d1 = Date.parse(date_aq);
	  var mydata = (JSON.stringify($('#frm').serializeObject()));
	 
		//$.post(url,$("frm").serialize()+"&contentvar="+cv+"&action="+v,function(data){  //ajaxfile/scriptfile_a is called undernith
		//$.post(url,{contentvar:cv,action:v, r_id:a, mydata:mydata},function(data){
			//a=
		$.post(url,{contentvar:cv,action:v,sdate1:date_aq,  mydata:mydata},function(data){
		//$.post(url, $("frm").serialize()+"&contentvar="+cv+"&action="+v, function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of save asset
  
  if(cv=='assignmail_looks') // Sharing mail
		{
			//alert('12345'); exit;
			var tab = $('#tt').tabs('getSelected');
			var index = $('#tt').tabs('getTabIndex', tab);
			//if(a=="" )
			//if( || v=="")
			 {
				//  alert('Complete all the fields ');
				//  $(divid).html('').show();  //stop loader from rolling
				//  exit();
			  } //end of validation 
			  //exit;
	   var test = (JSON.stringify($('#assignmailss').serializeObject()));
	   ////$("#show_ref").html('').show();
	  
		$.post(url,$("#assignmailss").serialize()+"&contentvar="+cv+"&action="+v+"&tabindex="+index,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		 
		//$(divid).html('').hide();
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		});
		}//end of sharing mail
  }//end of swapcontent
 </script>


    <link rel="stylesheet" type="text/css" href="include/colorbox.css">
    <script type="text/javascript" src="include/jquery.colorbox.js"></script>
<link href="upload.css" rel="stylesheet" type="text/css" />
<script src="file/jquery.min.js"></script>
<script src="upload.js"></script>
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
	        <h2>Cashbook Upload</h2>
                <p>&nbsp;</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                <!--<h2>Tabs with Images</h2>
                <p>The tab strip can display big images.</p>-->
                <div style="margin:20px 0;"></div>
                <div class="easyui-tabs" data-options="tabWidth:100,tabHeight:60" style="width:700px;" id="tt">
                  <div title="<span class='tt-inner'><img src='images/newmail.png'/><br>Upload...</span>" style="padding:10px">
                    <form  action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="uploadasset" id="uploadasset" >
                    <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-color:#369; border-width:thin;">
  <tr>
    <td align="left" valign="top">
    <fieldset style='border:1px solid #2A5FAA; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>
      <legend style='padding: 0.2em 0.5em; border:1px solid #2A5FAA; color:#2A5FAA; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px; -webkit-border-radius: 5px;'><b>Fill Properly</b></legend>
      <table width="80%" >
        <!--tr>
          <td ><a href="template/journal template.csv">Journal Sample</a></td>
          <td></td>
        </tr-->
        <tr>
          <td width="15%" >Upload Type:</td>
          <td width="58%"><select name="utype" id="utype" style="width:200px">
            <option selected="selected" value="">Select item...</option>
               <option value="cashbook">Cashbook</option>
               <!--option value="Journald">Journal Debit</option-->           
            </select>
          </td>
         
        </tr>
        <tr>
          <td >Upload File:</td>
          <td colspan="3">
            <input name="file" type="file" class="btn" id="file" size="40" /></td>
        <tr>
          <td colspan="4" align="center"><div align="center">
             <input name="mode" type="hidden" id="mode" value="upload" /> <input name="button" type="submit" class="btn" id="button" value="Upload File" />
            </div>
          
      
            </td>
          
        </table>
         </fieldset>
      <!--</form>-->
    </td>
    </tr>
        </table>
        <div id="display"> </div>
        <div id="roll"> </div>
        
    <?php
		   ////////////////////////////////Action section ////////////////////////////////////////////////////////////
		   if(isset($_REQUEST['mode']) and $_REQUEST['mode']=='upload')
		   {
			   
			 $utype=@$_REQUEST['utype'];
			 $login_id=@$_SESSION['login_id'];
			   
			   $fname = @$_FILES['file']['name'];
			   $ext = @explode(".",$fname);  $ext = $ext[1];
			   $sn = 0;
			   if ($ext != "csv" and $ext != "CSV") 
			   { 
			     echo "<font color='red'>Invalid file type. CSV file should be uploaded.</font>";
			     exit;
			   } //end of check extension
			   
                    if ($utype == 'cashbook')
                    {
                         $uploadDir = "upload_files/final/";
                         $upload_file_name=@date('Ymd').@date('h:s:i a').$fname;  //the file with .csv
                         $upload_file_name=@str_replace(":","",$upload_file_name);
                         $upload_file_name=@str_replace(" ","",$upload_file_name);
                         $uploadFile = $uploadDir.$upload_file_name;
                         $thead="<table><tr><th>S/NO</th><th>DATE</th><th>PV NUMBER</th><th>AMOUNT</th><th>STATUS</th></tr>";
                         if (@move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile))
                         { // file uploaded
                              $file_array = @file("$uploadFile");
                              while (list($line_num, $line) = each($file_array)) 
                              { // each line
                         
                                   ++$sn;
                                   $fileRow = @explode(",",$line);
                                   $month = @trim($fileRow[0]);
                                   $year = @trim($fileRow[1]); 
                                   $pvno = @trim($fileRow[2]);
                                   $amount = @trim($fileRow[3]);
                                   
                                   ////let us add them to the necessary tables  ".$receiptno."
                                   $insSql="INSERT INTO cashbooktb SET rmonth='".$month."', pvno='".$pvno."', ryear='".$year."', amount=".$amount.", entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'";
                                   if( mysqli_query($con, $insSql)) {$fon = "UPLOADED";} else {$fon = "NOT UPLOADED";}
                                        
                                   $tbody.="<tr><td>$sn</td><td>{$month}/{$year}</td><td>$pvno</td><td>$amount</td><td><font color='#00FF00'>$fon</font></td></tr>";
                                        
                              } //end of file loop per row			
                                                       
                              echo $thead;
                              echo $tbody;
                              echo "</table>";
                         } //end of move_upload file
                    }
                    else 
			   {
			   $uploadDir = "upload_files/final/";
			   $upload_file_name=@date('Ymd').@date('h:s:i a').$fname;  //the file with .csv
			   $upload_file_name=@str_replace(":","",$upload_file_name);
			   $upload_file_name=@str_replace(" ","",$upload_file_name);
			   $uploadFile = $uploadDir.$upload_file_name;
			   $thead="<table><tr><th>S/NO</th><th>DATE</th><th>PAYMENT NUMBER</th><th>ACCOUNT CODE</th><th>AMOUNT</th><th>TYPE</th><th>STATUS</th></tr>";
			   if (@move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile))
					{ // file uploaded
					
					 $file_array = @file("$uploadFile");
					    while (list($line_num, $line) = each($file_array)) 
							{ // each line
						
								++$sn;
								$fileRow = @explode(",",$line);
								$folio_code = @trim($fileRow[0]); 
								$transtype = @trim($fileRow[1]); 
								$date_entry = @trim($fileRow[2]);
								$amount = @trim($fileRow[3]);
								$pvno = @addslashes(@trim($fileRow[4]));
								  
								//////////////////////check folio code//////////////////////////
							$res_lf=@mysqli_query($con, "select folio_code from foliotb where folio_code='".$folio_code."'");
			  $rs_lf=@mysqli_fetch_array($res_lf);
			  	////^if(@mysqli_num_rows($res_lf)>=1) {$fon = "UPLOADED";} else {$fon = "NOT UPLOADED";}
			  				///////////////////////////////////////////////////////////////
							
							 $res_l=@mysqli_query($con, "select * from transtb where folio_code='".$folio_code."' and transtype='".$transtype."' and transdate='".$date_entry."' and amount='".$amount."' and pvno='".$pvno."'");
			  $rs_l=@mysqli_fetch_array($res_l);
			  if(@mysqli_num_rows($res_l)>=1)
			  {
				  $delSql="delete from transtb where folio_code='".$folio_code."' and pvno='".$pvno."' and transtype='".$transtype."' and transdate='".$date_entry."' and amount='".$amount."'";
				  mysqli_query($con, $delSql);
				 // $tbody.="<tr><td>$sn</td><td>$date_entry</td><td>$pvno</td><td>$folio_code</td><td>$amount</td><td>$transtype</td></tr>";
			  }
			 	////let us add them to the necessary tables  ".$receiptno."
				$insSql="insert into transtb set pvno='".$pvno."', folio_code='".$folio_code."', transtype='".$transtype."', transdate='".$date_entry."', amount=".$amount.",entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'";
				if( mysqli_query($con, $insSql)) {$fon = "UPLOADED";} else {$fon = "NOT UPLOADED";}			
							////^ mysqli_query($con, $insSql);
						
									
									$tbody.="<tr><td>$sn</td><td>$date_entry</td><td>$pvno</td><td>$folio_code</td><td>$amount</td><td>$transtype</td><td><font color='#00FF00'>$fon</font></td></tr>";
									
								//} 
							} //end of file loop per row			
												
							echo $thead;
							echo $tbody;
							echo "</table>";
					} //end of move_upload file

			   
			   //echo "Yes $session $choice $mode_of_entry";
		 //  } //end of action for uploading
		   }
		}
		?>
        </p>      
    </table>
    </form>


    <form  action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data" name="uploadasset2" id="uploadasset2" >
    <hr>
    <hr>
    NEW UPLOAD
    <hr>
    <hr>
    <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-color:#369; border-width:thin;">
  <tr>
    <td align="left" valign="top">
    <fieldset style='border:1px solid #2A5FAA; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>
      <legend style='padding: 0.2em 0.5em; border:1px solid #2A5FAA; color:#2A5FAA; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px; -webkit-border-radius: 5px;'><b>Fill Properly</b></legend>
      <table width="80%" >
        <tr>
          <td >Upload File:</td>
          <td colspan="3">
            <input name="file2" type="file" class="btn" id="file2" size="40" /></td>
        <tr>
          <td colspan="4" align="center"><div align="center">
             <input name="mode2" type="hidden" id="mode2" value="upload2" /> 
             <input name="button2" type="submit" class="btn" id="button2" value="Upload File" />
            </div>
          
      
            </td>
          
        </table>
         </fieldset>
    </td>
    </tr>
        </table>
        <div id="display22"> </div>
        
<?php
		   ////////////////////////////////Action section ////////////////////////////////////////////////////////////
		   if(isset($_POST['mode2']) and $_POST['mode2']=='upload2')
		   {
			   
			 $login_id=@$_SESSION['login_id'];
			   
			   $fname = @$_FILES['file2']['name'];
			   $ext = @explode(".",$fname);  $ext = $ext[1];
			   $sn = 0;
			   if ($ext != "csv" and $ext != "CSV") 
			   { 
			     echo "<font color='red'>Invalid file type. CSV file should be uploaded...</font>";
			     exit;
			   } //end of check extension
			   
                {
                        $uploadDir = "upload_files/final/";
                        $upload_file_name=@date('Ymd').@date('h:s:i a').$fname;  //the file with .csv
                        $upload_file_name=@str_replace(":","",$upload_file_name);
                        $upload_file_name=@str_replace(" ","",$upload_file_name);
                        $uploadFile = $uploadDir.$upload_file_name;
                        $thead="<table><tr><th>S/NO</th><th>DATE</th><th>BATCH NUMBER</th><th>PVNO</th><th>PAYEE</th><th>DESCRIPTION</th><th>CODE</th><th>AMOUNT</th><th>MONTH</th><th>YEAR</th></tr>";
                        if (@move_uploaded_file($_FILES['file2']['tmp_name'], $uploadFile))
                        { // file uploaded
                            $file_array = @file("$uploadFile");
                            while (list($line_num, $line) = each($file_array)) 
                            { // each line
                        
                                ++$sn;
                                $fileRow = @explode(",",$line);

                                
                                $date1 = @trim($fileRow[1]);
                                if($date1=='') $date=$date0;
                                else {
                                    $date0 = @trim($fileRow[1]);
                                    $date=$date0;
                                }

                                
                                $batch1 = @trim($fileRow[2]);
                                if($batch1=='') $batch=$batch0;
                                else {
                                    $batch0 = @trim($fileRow[2]);
                                    $batch=$batch0;
                                }

                                $pvno = @trim($fileRow[3]);
                                $payee = @trim($fileRow[4]); 
                                $desc = @trim($fileRow[5]);
                                $code = @trim($fileRow[6]);
                                $amount = @trim($fileRow[7]);
                                $month = @trim($fileRow[8]);
                                $year = @trim($fileRow[9]); 
                                
                                ////let us add them to the necessary tables  ".$receiptno."
                                $insSql="INSERT INTO cashbook_s SET paydate='{$date}', batch='{$batch}', rmonth='".$month."', pvno='".$pvno."', ryear='".$year."', amount=".$amount.", descr='{$desc}', code='{$code}', payee='{$payee}'";
                                if( mysqli_query($con, $insSql)) {$fon = "UPLOADED";} else {$fon = "NOT UPLOADED";}
                                    
                                $tbody.="<tr><td>{$sn}</td><td>{$date}</td><td>$pvno</td><td>{$payee}</td><td>{$desc}</td><td>{$code}</td><td>{$amount}</td><td>{$month}</td><td>{$year}</td></tr>";
                                    
                            } //end of file loop per row			
                                                    
                            echo $thead;
                            echo $tbody;
                            echo "</table>";
                        } //end of move_upload file
                }
                    
		}
		?>
</form>
                        
                        
                
                  </div>
                      

            </div><!-- end of content box -->
       <!-- </div>  end of content tooplate_content-->
    <!-- </div> end of content tooplate_main-->
    <div class="cleaner"></div>    
</div> <!-- end of wrapper tooplate_wrapper-->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->
<script type="text/javascript" >
 $(document).ready(function() { 
		
            $('#photoimg').live('change', function()			{ 
			           $("#preview").html('');
			    $("#preview").html('<img src="images/ajax-loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({
						target: '#preview'
		}).submit();
		
			});
        }); 
</script>
<style>
	.preview
	{
		width:200px;
		border:solid 1px #dedede;
		padding:10px;
	}
	#preview
	{
		color:#cc0000;
		font-size:12px
	}
</style>
</body>
</html>