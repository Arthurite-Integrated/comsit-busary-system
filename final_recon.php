<?php @session_start(); error_reporting(E_ALL ^ E_NOTICE);
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@_SESSION['$role'];
 $staff_category=@$_SESSION['staff_category'];
 $consolidated=false;
// echo $_REQUEST['op_id'];
if(isset($_REQUEST['op_id']) and $_REQUEST['op_id']=='consolidated'){
	$consolidated=true;
}

require_once 'excel_reader2.php';
		include_once 'assets/Classes/PHPExcel/IOFactory.php';
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
 
 <style>
table.excel {
	border-style:ridge;
	border-width:1;
	border-collapse:collapse;
	font-family:sans-serif;
	font-size:12px;
}
table.excel thead th, table.excel tbody th {
	background:#CCCCCC;
	border-style:ridge;
	border-width:1;
	text-align: center;
	vertical-align:bottom;
}
table.excel tbody th {
	text-align:center;
	width:20px;
}
table.excel tbody td {
	vertical-align:bottom;
}
table.excel tbody td {
    padding: 0 3px;
	border: 1px solid #EEEEEE;
}
</style>


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
	        <h2>Account Reconcilation</h2>
                <p>Account ....</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Salary Scale Setup</i></h3> -->
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="consfrm" id="consfrm">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="50%" align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
				  <td height="33" colspan="2" align="left" bgcolor="#CCFFFF"><h4><strong>LOAD DATA</strong></h4></td>
				  </tr>
				<tr>
				<th height="33" align="left" bgcolor="#CCFFFF">Month/Year:<strong style="color:#F00">*</strong></th>
                <td height="33" align="left" bgcolor="#CCFFFF"><select name="rmonth" id="rmonth" tabindex="2">
                  <option selected="selected" value="">--</option>
                  <option value="January">January</option>
                  <option value="February">February</option>
                  <option value="March">March</option>
                  <option value="April">April</option>
                  <option value="May">May</option>
                  <option value="June">June</option>
                  <option value="July">July</option>
                  <option value="August">August</option>
                  <option value="September">September</option>
                  <option value="October">October</option>
                  <option value="November">November</option>
                  <option value="December">December</option>
                  </select>
                  /
                  <select name="ryear" id="ryear" tabindex="3">
                    <option selected="selected" value="">--</option>
                    <?php for($t=2017; $t <= 2025; $t++) echo '<option value="'.$t.'">'.$t.'</option>'; ?>
                    </select>
                  
                  </select><input type="hidden" id="status" name="status" value="">
                  <input type="hidden" id="id" name="id" value="">
                </td>
                </tr>
				<tr>
				<th height="33" align="left" bgcolor="#CCFFFF">Record Type:<strong style="color:#F00">*</strong></th>
                <td height="33" align="left" bgcolor="#CCFFFF"><select name="recordtype" id="recordtype" tabindex="2">
                  <option selected="selected" value="">--</option>
                  <option value="recon_banktb">Bank Statement</option>
                  <option value="recon_remitatb">Remita Statement</option>
                </select></td>
                </tr>
				<tr>
				<th height="33" align="left" bgcolor="#CCFFFF">Select File:<strong style="color:#F00">*</strong></th>
                <td height="33" align="left" bgcolor="#CCFFFF"><input type="file" name="rfile" id="rfile"></td>
                </tr>
				<tr><td height="33" bgcolor="#CCFFFF">&nbsp;</td><td height="33" align="left" bgcolor="#CCFFFF">
				  <input type="submit" class="btn" name="sbtn_n" id="sbtn_n" value="Save" onclick="swapcontent('salary_code_section','save', '');" /></td>
				  </tr>
				</table></td>
                <td width="50%" align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
				  <td height="33" colspan="2" bgcolor="#D6D6D6"><h5>REPORT</h5></td>
				  </tr>
				<tr>
				  <th height="33" align="left" bgcolor="#D6D6D6">Month/Year:<strong style="color:#F00">*</strong></th>
				  <td height="33" align="left" bgcolor="#D6D6D6"><select name="rmonth_2" id="rmonth4" tabindex="2">
				    <option selected="selected" value="">--</option>
				    <option value="January">January</option>
				    <option value="February">February</option>
				    <option value="March">March</option>
				    <option value="April">April</option>
				    <option value="May">May</option>
				    <option value="June">June</option>
				    <option value="July">July</option>
				    <option value="August">August</option>
				    <option value="September">September</option>
				    <option value="October">October</option>
				    <option value="November">November</option>
				    <option value="December">December</option>
				    </select>
				    /
				    <select name="ryear_2" id="ryear_2" tabindex="3">
				      <option selected="selected" value="">--</option>
				      <?php for($t=2017; $t <= 2025; $t++) echo '<option value="'.$t.'">'.$t.'</option>'; ?>
				      </select>
				    </select></td>
				  </tr>
				<tr>
				  <td height="33" bgcolor="#D6D6D6">&nbsp;</td>
				  <td height="33" align="left" nowrap bgcolor="#D6D6D6"><input type="button" class="btn" name="btn_debit" id="btn_debit" value="Direct Bank Debit" onClick="swapcontent('salary_code_section','search', '');" /></td>
				  </tr>
				<tr>
				  <td height="33" bgcolor="#D6D6D6">&nbsp;</td>
				  <td height="33" align="left" bgcolor="#D6D6D6"><input type="button" class="btn" name="btn_ucr" id="btn_ucr" value="Uncredited Lodgment" onClick="swapcontent('salary_code_section','search', '');" /></td>
				  </tr>
				<tr>
				  <td height="33" bgcolor="#D6D6D6">&nbsp;</td>
				  <td height="33" align="left" bgcolor="#D6D6D6"><input type="button" class="btn" name="btn_ctrl" id="btn_ctrl" value="Control Report" onClick="swapcontent('salary_code_section','search', '');" /></td>
				  </tr>
				</table></td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><hr></td>
              </tr>
              <tr>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
				  <td height="264" align="left" valign="top" bgcolor="#A5A5A5">
				    <?php //echo $_POST['rmonth'], $_POST['ryear'], $_POST['rfile'], $_POST['recordtype'];
    if(isset($_POST['sbtn_n'])){ // and isset($_POST['ryear']) and isset($_POST['recordtype'])){
			
		$excel_file_qtn = $_FILES['rfile']['name'];
		$input_method = "uploading";
		/****************/
		$import_file = $_FILES['rfile']['name'];
		$import_file_tmp_name = $_FILES['rfile']['tmp_name'];
		$import_file_path = pathinfo($_FILES['rfile']['name']);
 		$import_file_ext = $import_file_path['extension'];
	 	
		$status = false;   $sheetData = null; 
		
		/******** start processing *********/
		$nfn = $_POST['rmonth'].$_POST['ryear']."_imported_qtn_at_".time(); 
		
		
		// create a directory 
			if(!is_dir("upload_files/recon/")) mkdir("upload_files/recon/");
			 
			$fext = array('application/vnd.ms-excel','application/xls','text/xls','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			
			/************************************/
			
			foreach($fext as $mime) {
				if($mime == $_FILES['rfile']['type']) {
					  $status = true;
						// unlink("../assets/uploads/");
						$newFPath =  "upload_files/recon/".$nfn.'.'."xlsx";	
						$nfname = $nfn.'.'."xlsx";								 
						break;
					}
			}  // end foreach ...
			//echo $nfname,"<br>";
			
			/// if file type is not excel 
			//echo $status; exit;
			if(!$status){					
					echo " You are required to upload an excel file of type (.xlsx) and not the (.".$import_file_ext.") type that you uploaded";
					
					$is_excel_qtn = false; 
				} else {
				copy($import_file_tmp_name, $newFPath);					
					
				$qtn_path = $newFPath;
				$permit_delete_bulk_qtn = true; 
				
				// start excel reading 
				//echo "Assss"; exit;
				/*$data = new Spreadsheet_Excel_Reader($qtn_path,false);
				$sheet_index=0;
				$row_count = $data->rowcount(0);
				$col_count = $data->colcount(0);
				echo '<table width="100%" boredr=1>';
				for($row=1; $row <= $row_count; $row++){
					echo "<tr><td>".$row."</td>";
					for($col=1; $col <= $row_count; $col++){
						echo "<td>".$data->val($row,$col,$sheet_index)."</td>";
					} echo "</tr>";
				}
				echo "</table>";*/
				//echo $data->dump(true,true);
				
				$objPHPExcel = PHPExcel_IOFactory::load($qtn_path);	
				
				$sheetCount = $objPHPExcel->getSheetCount();
				
				$sheetNames = $objPHPExcel->getSheetNames();
				 
				// $sheetData = $objPHPExcel->getSheetByName($sheetNames[0])->toArray(null,true,true,true);
				 			 
				// var_dump($sheetData);

					//echo " About To Read Your Uploaded File ";
					$is_excel_qtn = true;  	
					$step2 = true; 
			}
			    	}
		echo "Input not set!";  /// end submit of excel file  


		?>
				    
				    
				    
				    <p>_______________________________________________</p></td>
			    </tr>
              
              </table></td>
              </tr>
              <tr>
                <td align="left" valign="top">&nbsp;</td>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
            </table>
				
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