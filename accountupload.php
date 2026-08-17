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

////require_once 'excel_reader2.php';
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
	        <h2>Account Reconciliation</h2>
                <p>Data entry and Report generation</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Salary Scale Setup</i></h3> -->
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="consfrm" id="consfrm">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
                  <tr>
                    <td height="33" colspan="2" align="left" bgcolor="#CCFFFF"><h4><strong>LOAD DATA</strong></h4></td>
                    </tr>
                  <tr>
                    <th height="33" align="left" bgcolor="#CCFFFF">Month/Year:<strong style="color:#F00">*</strong></th>
                    <td height="33" align="left" bgcolor="#CCFFFF"><select name="rmonth" id="rmonth" style="width: 195px">
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
                      <select name="ryear" id="ryear" style="width: 90px">
                        <option selected="selected" value="">--</option>
                        <?php for($t=2017; $t <= 2025; $t++) echo '<option value="'.$t.'">'.$t.'</option>'; ?>
                        </select>
                      
                      </select><input type="hidden" id="status" name="status" value="">
                      <input type="hidden" id="id" name="id" value="">
                      </td>
                    </tr>
                  <tr>
                    <th height="33" align="left" bgcolor="#CCFFFF">Record Type:<strong style="color:#F00">*</strong></th>
                    <td height="33" align="left" bgcolor="#CCFFFF"><select name="recordtype" id="recordtype" style="width: 300px" >
                      <option selected="selected" value="">--</option>
                      <option value="receipt">Receipt</option>
                      <option value="payment">Payment</option>
                      </select></td>
                    </tr>
                  <tr>
                    <th height="33" align="left" bgcolor="#CCFFFF">Select File:<strong style="color:#F00">*</strong></th>
                    <td height="33" align="left" bgcolor="#CCFFFF"><input type="file" name="rfile" id="rfile"></td>
                    </tr>
                  <tr>
                    <td height="33" bgcolor="#CCFFFF"><strong>Select Account:</strong><strong style="color:#F00">*</strong></td>
                    <td height="33" align="left" bgcolor="#CCFFFF"><select name="account" id="account" class="txt" style="width: 300px"  >
                      <option selected="selected">---</option>
                      <?php
								$r=@mysqli_query($con, "select distinct *  from bank_accounttb order by acctcode");
								while ($rcourse=@mysqli_fetch_array($r))
									{
										$scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
										$bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
										$acctname=@$rcourse['acctname'];
										echo "<option value='$pcode'>$bank || $scourse <=> ($pcode)</option>";
										
									}
						 ?>
                      </select></td>
                    </tr>
                  <tr><td height="33" bgcolor="#CCFFFF">&nbsp;</td><td height="33" align="left" bgcolor="#CCFFFF">
                    <button type="submit" class="btn" name="sbtn_n" id="sbtn_n"> Read Excel File </button></td>
                    </tr>
                </table></td>
              </tr>
              <tr>
                <td align="left" valign="top"><hr></td>
              </tr>
              <tr>
                <td align="left" valign="top">&nbsp;</td>
              </tr>
              <tr>
                <td align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
				  <td height="264" align="left" valign="top" bgcolor="#A5A5A5">
				    <?php //echo $_POST['rmonth'], $_POST['ryear'], $_POST['rfile'], $_POST['recordtype'];
	if( isset($_POST['sbtn_n']) and isset($_POST['rmonth']) and $_POST['rmonth']!='' and isset($_POST['ryear']) and $_POST['ryear']!='' and isset($_POST['recordtype']) and $_POST['recordtype']!='' and isset($_POST['account']) and $_POST['account']!='' ){
			$_SESSION['excel_file'] = $_FILES['rfile']['name'];
			$_SESSION['input_method'] = "uploading";
			/****************/
			$import_file = $_FILES['rfile']['name'];
			$import_file_tmp_name = $_FILES['rfile']['tmp_name'];
			$import_file_path = pathinfo($_FILES['rfile']['name']);
			$import_file_ext = $import_file_path['extension'];
			
			$_SESSION['rmonth'] = $_POST['rmonth'];
			$_SESSION['ryear'] = $_POST['ryear'];
			$_SESSION['recordtype'] = $_POST['recordtype'];
			$_SESSION['account'] = $_POST['account'];
			$_SESSION['other_msg'] = strtoupper($_SESSION['recordtype'])." ENTRY FOR ".strtoupper($_SESSION['rmonth']).", ".$_SESSION['ryear']."<br>ACCOUNT SELECTED: ".$_SESSION['account']."<br><hr>";
			
			$status = false;   $_SESSION['sheetData'] = null; 
			
			/******** start processing *********/
			$nfn = $_POST['rmonth'].$_POST['ryear']."_imported_at_".time(); 
			
			
			// create a directory 
				if(!is_dir("upload_files/")) mkdir("upload_files/");
				 
				$fext = array('application/vnd.ms-excel','application/xls','text/xls','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				
				/************************************/
				
				foreach($fext as $mime) {
					if($mime == $_FILES['rfile']['type']) {
						  $status = true;
							// unlink("../assets/uploads/");
							$newFPath =  "upload_files/".$nfn.'.'."xlsx";	
							$nfname = $nfn.'.'."xlsx";								 
							break;
						}
				}  // end foreach ...
				
				
				/// if file type is not excel 
				
				if(!$status){					
						$_SESSION['alert_msg'] = " You are required to upload an excel file of type (.xlsx) and not the (.".$import_file_ext.") type that you uploaded! ".$nfname;
						$_SESSION['alert_type'] = "bg-danger text-center bold";
						
						$_SESSION['is_excel'] = false; 
					}
					
				else {
					copy($import_file_tmp_name, $newFPath);					
						
					$_SESSION['qtn_path'] = $newFPath;
					$_SESSION['permit_delete_bulk'] = true; 
					
					// start excel reading 
					$objPHPExcel = PHPExcel_IOFactory::load($_SESSION['qtn_path']);	
					
					$_SESSION['sheetCount'] = $objPHPExcel->getSheetCount();
					
					$_SESSION['sheetNames'] = $objPHPExcel->getSheetNames();
					 
					// $sheetData = $objPHPExcel->getSheetByName($sheetNames[0])->toArray(null,true,true,true);
								 
					// var_dump($sheetData);
	
						$_SESSION['alert_msg'] = $_SESSION['other_msg']."About To Read Your Uploaded File ";
						$_SESSION['alert_type'] = "bg-success text-center bold ";				
						$_SESSION['is_excel'] = true;  	
						$_SESSION['step2'] = true; 
				}
				$_SESSION['field_select'] = '';
			}  /// end submit of excel file  
		//echo "Input not set!";  /// end submit of excel file  


		// read_sheet_content
	/*****************************/	
if(isset($_POST['read_sheet_content'])){ 
		// disallow time interrupt, use 
			set_time_limit(0);
	 	// writing data to a file, to allow any file size of data, use 
		/*** 'ini_set('memory_limit',-1);' *****/

		$objPHPExcel = PHPExcel_IOFactory::load($_SESSION['qtn_path']);	
		
		$_SESSION['sheetData'] = $_SESSION['dup'] = $_SESSION['new'] = null; //$_SESSION['new']='';
		###### now read #####
		$_SESSION['sheet_to_read'] = $_POST['sheet_to_read'];
		
		if($_SESSION['sheet_to_read']=="" || empty($_SESSION['sheet_to_read'])){
			$_SESSION['alert_msg'] = strtoupper(" please select a  worksheet to read from  ");
					$_SESSION['alert_type'] = "bg-danger bold text-center";
		}
		// $_SESSION['sheetData'] = $objPHPExcel->getSheetByName($_SESSION['sheet_to_read'])->toArray(null,true,true,true);
		
		// set index of worksheet  
		// $objPHPExcel->setActiveSheetIndex(2);
		// 
		
		else {
		
		$worksheet  = $objPHPExcel->setActiveSheetIndexByName($_SESSION['sheet_to_read']);
		//$worksheet  = $objPHPExcel->setActiveSheetIndex(1);
		// now iterate content to read 
		## foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);	
			$nrColumns = ord($highestColumn) - 64;
	
			/////////////////////////////////// 	     
			$headers = array(); 
			////////////////////////////////////////////////////
			////// get header 	
			 for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, 1);
					$headers[$col-1]= $cell->getValue();			
				}   /// headers gotten      
		 
		 // check if header contains session and papercodes 
		  /*if(!in_array('DATE',$headers) || !in_array('REMITAREF',$headers) || !in_array('PARTICULAR',$headers) 
			  || !in_array('AMOUNT',$headers) || !in_array('RRR',$headers) ) {
			$_SESSION['alert_msg'] = " Your Worksheet Must Contains At Least : This Header Information : <strong> [ &nbsp; session,&nbsp;  papercode,&nbsp; 
			papertype,&nbsp; optiontype &nbsp; num,  &nbsp; marks &nbsp; and &nbsp; question </strong> ] &nbsp;";
			$_SESSION['alert_msg'].= "   but you have &nbsp;[ &nbsp;".join(", &nbsp; ",$headers)." &nbsp;] &nbsp; in your worksheet header. ======".$headers[0]; 
			$_SESSION['alert_type'] = "bg-danger bold";
			 
		  } 
		 else*/ { 
		// get body 
			////////////////////////////////////////////////////////////////
				$_SESSION['contents'] = $fCont = array();		/// full contents
				$_SESSION['permit_delete_bulk'] = false;
				$_SESSION['alert_msg'] = $_SESSION['other_msg']." Your Uploaded File Has Been Read  Successfully. ";
				$_SESSION['alert_type'] = "bg-success bold text-center";				
				
				$select='<option value="" selected>---</option>';
						for($i=0; $i < count($headers); $i++)
						$select .=  '<option value="'.$i.'">'.$headers[$i].'</option>';
				
				  $_SESSION['field_select'] = '<hr><table width="" align="center" cellpadding="2">
				  <tr><td>ENTRY DATE</td><td>P.V. NO</td><td>ACCOUNT CODE</td><td>PAYEE NAME</td><td>AMOUNT (Dr)</td><td>AMOUNT (Cr)</td><tr>
					<tr><td><select id="entrydate" name="entrydate">'.$select.'</select></td>
						<td><select id="pvno" name="pvno">'.$select.'</select></td>
						<td><select id="folio" name="folio">'.$select.'</select></td>
						<td><select id="comment" name="comment">'.$select.'</select></td>
						<td><select id="amount" name="dr_amount">'.$select.'</select></td>
						<td><select id="amount" name="cr_amount">'.$select.'</select></td><tr>
					<tr><td><button type="submit" name="read_fields" id="read_fields"> GET CONTENTS </button></td>
						<td><button type="submit" name="cancel_read_fields" id="cancel_read_fields"> CANCEL </button></td><td></td><tr>';
		 }
		}
		}
//=======================SEPARATION POINT FROM PREVIOUS READ_SHEET====================

		 if(isset($_POST['read_fields']) and isset($_POST['folio']) and $_POST['folio']!=''){
//echo $_POST['pid'],"++++++++++++++++++";
			echo "<script>alert();</script>";
			set_time_limit(0);
			$objPHPExcel = PHPExcel_IOFactory::load($_SESSION['qtn_path']);	
			
			$_SESSION['sheetData'] = $_SESSION['dup'] = $_SESSION['new'] = null;
			
			$worksheet  = $objPHPExcel->setActiveSheetIndexByName($_SESSION['sheet_to_read']);
			//$worksheet  = $objPHPExcel->setActiveSheetIndex(1);
			// now iterate content to read 
			## foreach ($objPHPExcel->getWorksheetIterator() as $worksheet) {
			
			$highestRow         = $worksheet->getHighestRow(); // e.g. 10
			$highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
			$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);	
			$nrColumns = ord($highestColumn) - 64;
	
			/////////////////////////////////// 	     
			$headers = array(); 
			////////////////////////////////////////////////////
			////// get header 	
			 for ($col = 0; $col < $highestColumnIndex; ++ $col) {
					$cell = $worksheet->getCellByColumnAndRow($col, 1);
					$headers[$col-1]= $cell->getValue();			
				}   /// headers gotten      

				$_SESSION['contents'] = $fCont = array();		/// full contents
				$_SESSION['permit_delete_bulk'] = false;
				$_SESSION['alert_msg'] = " Your Uploaded File Has Been Read Successfully. ";

			for ($row = 2; $row <= $highestRow; ++ $row) {	
				$sRow=array();		/// single row
				for ($col = 0; $col < $highestColumnIndex; ++ $col) {
				   $cell = $worksheet->getCellByColumnAndRow($col, $row); 
				  
				  /****  previous data was: $sRow[] = $cell->getFormattedValue(); but now, we use the code below: ***/
				   // $sRow[] = trim(iconv("UTF-8","ISO-8859-1",$cell->getValue())," \t\n\r\0\x0B\xA0"); // to remove white spaces				   
				  $sRow[] =  mysqli_real_escape_string($con,  trim(iconv("UTF-8","ISO-8859-1", $cell->getFormattedValue())," \t\n\r\0\x0B\xA0") ); 
				  //," \t\n\r\0\x0B\xA0"); // to remove white spaces	
				   /*echo "<script>alert($sRow);</script>";	*/		   
				   // $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
				   //$sRow[] = $cell->getValue();		   
				}
				 $_SESSION['contents'][] = $fCont[] = array_combine($headers,$sRow);// merge each column head with cell values  
			}	 ### end for loop 
			///
			
			// now save the record to database 
			 $_SESSION['dup'] = null; $_SESSION['new'] = null;
			 ///begin();
			 //$query = array();
			foreach($_SESSION['contents'] as $rows){
				//$fd = $dbm->getFields($dbm->select("questions",array("session"=>$rows['session'],"question"=>$rows['question'],"option1"=>$rows['option1'],"option2"=>$rows['option2'])),array_keys($rows));	
				 //////echo "Write Select Query!";	
				 //$query1="SELECT * FROM ".$_SESSION['recordtype']." WHERE paymentid='".$rows[$headers[$_POST['pid']]]."' and amount=".$rows[$headers[$_POST['amt']]]." and rmonth='".$_SESSION['rmonth']."' and ryear='".$_SESSION['ryear']."'"; 
				 $query1="SELECT * FROM ".$_SESSION['recordtype']." WHERE paymentid='".$rows[$headers[$_POST['pid']]]."' and amount=".$rows[$headers[$_POST['amt']]]; 
				 $qr=@mysqli_query($con, $query1);// or die( mysqli_error($con));
				 $fd= mysqli_num_rows($qr);
				//$str = $rows['session'].",".$rows['question'].",".$rows['option1'].",".$rows['option2'];
				//if(!empty($fd)){
				if($fd > 0){
					 $_SESSION['dup'][] = $rows;
					$dup++;	
				}
					 
				else
				{
					if($rows[$headers[$_POST['pid']]] != '' and $rows[$headers[$_POST['amt']]] != ''){
					 $qury="INSERT DELAYED INTO ".$_SESSION['recordtype']." (paymentid, payer, amount, rmonth, ryear, entry_by, entry_date, entry_time, credit_reference) VALUES ('". mysqli_real_escape_string($con, $rows[$headers[$_POST['pid']]])."', '". mysqli_real_escape_string($con, $rows[$headers[$_POST['pay']]])."', ". mysqli_real_escape_string($con, str_replace(',','',$rows[$headers[$_POST['amt']]])).", '". mysqli_real_escape_string($con, $_SESSION['rmonth'])."', '". mysqli_real_escape_string($con, $_SESSION['ryear'])."', '". mysqli_real_escape_string($con, $_SESSION['login_id'])."', now(), now(), '". mysqli_real_escape_string($con, $rows[$headers[$_POST['cref']]])."')";
					 //$qury="INSERT DELAYED INTO ".$_SESSION['recordtype']." SET paymentid='". mysqli_real_escape_string($con, $rows[$headers[$_POST['pid']]])."', payer='". mysqli_real_escape_string($con, $rows[$headers[$_POST['pay']]])."', amount=". mysqli_real_escape_string($con, str_replace(',','',$rows[$headers[$_POST['amt']]])).", rmonth='". mysqli_real_escape_string($con, $_SESSION['rmonth'])."', ryear='". mysqli_real_escape_string($con, $_SESSION['ryear'])."', entry_by='". mysqli_real_escape_string($con, $_SESSION['login_id'])."', entry_date=now(), entry_time=now(), credit_reference='". mysqli_real_escape_string($con, $rows[$headers[$_POST['cref']]])."';";	exit;
					 //echo $query,"<br>";// "Write Insert Query where duplicate not found!";
					 if(@mysqli_query($con, $qury)) $_SESSION['new'][] = $rows; 
					$ins++;
					}
				}
			} //end foreach
		  }
		// } // end a worksheet to read from  
		
	//} // end importing 


	if(isset($_POST['cancel_read_sheet_content']) or isset($_POST['cancel_read_fields'])){
		
		if($_SESSION['permit_delete_bulk']) @unlink($_SESSION['qtn_path']);
		 
		unset($_SESSION['sheetCount']);
		unset($_SESSION['sheetNames']);
		unset($_SESSION['is_excel']);
		unset($_SESSION['alert_msg']);
		unset($_SESSION['alert_type']); 
		unset($_SESSION['alert_type']); 
		unset($_SESSION['input_method']); 
		unset($_SESSION['step2']); 
	}

	if(isset($_POST['btn_cr_ref']) and isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and $_POST['ryear_2']!='' ){
		$_SESSION['r_m']=$_POST['rmonth_2']; $_SESSION['r_y']=$_POST['ryear_2'];
		
		$sqlg="SELECT sum(amount) as cr_sum_r, credit_reference FROM recon_remitatb where matched=0 group by credit_reference";
		$qryg= mysqli_query($con, $sqlg); //echo  mysqli_num_rows($qryg);
		$sn=0;
		while($r= mysqli_fetch_array($qryg, 3 )){
			$s="select sum(amount) as cr_sum_b, credit_reference from recon_banktb where credit_reference='".$r['credit_reference']."' and matched=0";
			$q_s= mysqli_query($con, $s);// or die( mysqli_error($con)); 
			$n_s= mysqli_num_rows($q_s); 
			if($n_s > 0){
				$q= mysqli_fetch_array($q_s, 3 );
				if($r['cr_sum_r'] == $q['cr_sum_b']){
					 mysqli_query($con, "update recon_banktb r, recon_remitatb b set r.matched=1, b.matched=1, r.match_code=1, b.match_code=1 where (r.credit_reference='".$r['credit_reference']."' and b.credit_reference='".$r['credit_reference']."')");
					
					/*$sqll="update recon_remitatb set matched = 1 where credit_reference='".$r['credit_reference']."'";
					if( mysqli_query($con, $sqll)){ 
						$sqllx="update recon_banktb set matched = 1 where credit_reference='".$r['credit_reference']."'";
						 mysqli_query($con, $sqllx);
					}*/
				} //end if($r['cr_sum_r'] == $r['cr_sum_b']){
			}
			
		}
			$_SESSION['direct_bank_debit']="<h3>RECONCILIATION COMPLETED FOR ".strtoupper($_POST['rmonth_2']).", ".$_POST['ryear_2']."</h3>";
	}
	
	if(isset($_POST['btn_process']) and isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and $_POST['ryear_2']!='' ){
		$_SESSION['r_m']=$_POST['rmonth_2']; $_SESSION['r_y']=$_POST['ryear_2'];
		
		$sqlg="SELECT * FROM recon_remitatb where matched=0";
		$qryg= mysqli_query($con, $sqlg); //echo  mysqli_num_rows($qryg);
		$sn=0;
		while($r= mysqli_fetch_array($qryg, 3 )){//$sn++;
			////$s="select * from recon_banktb where paymentid='".$r['paymentid']."' and amount=".$r['amount']." and matched=0";
			$s="update recon_banktb r, recon_remitatb b set r.matched=1, b.matched=1, r.match_code=2, b.match_code=2 where (r.paymentid='".$r['paymentid']."' and b.paymentid='".$r['paymentid']."') and (r.amount='".$r['amount']."' and b.amount='".$r['amount']."')";
			$q_s= mysqli_query($con, $s);// or die( mysqli_error($con)); 
			/*$n_s= mysqli_affected_rows(); 
			if($n_s == 2){
				$q= mysqli_fetch_array($q_s, 3 );
				//echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>{$r[payer]}</td><td>{$r[amount]}</td></tr>";
				
				$sqll="update recon_remitatb set matched = 1 where id=".$r['id'];
				if( mysqli_query($con, $sqll)){ 
					$sqllx="update recon_banktb set matched = 1 where id=".$q['id'];
					 mysqli_query($con, $sqllx);
				}
			}*/			
		}
			$_SESSION['direct_bank_debit']="<h3>RECONCILIATION COMPLETED FOR ".strtoupper($_POST['rmonth_2']).", ".$_POST['ryear_2']."</h3>";
	}
	
	if(isset($_POST['btn_process_name']) and isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and $_POST['ryear_2']!='' ){
		$_SESSION['r_m']=$_POST['rmonth_2']; $_SESSION['r_y']=$_POST['ryear_2'];
		
		$sqlg="SELECT * FROM recon_remitatb where rmonth='". mysqli_real_escape_string($con, $_POST['rmonth_2'])."' and ryear='". mysqli_real_escape_string($con, $_POST['ryear_2'])."' and matched=0";
		
		$qryg= mysqli_query($con, $sqlg); //echo  mysqli_num_rows($qryg);
		$sn=0;
		while($r= mysqli_fetch_array($qryg, 3 )){//$sn++;
			$xp=explode(" ", trim($r['payer']));
			$s="select * from recon_banktb where payer like '%".trim($xp[0])."%' and amount=".$r['amount']." and rmonth='".$_POST['rmonth_2']."' and ryear='".$_POST['ryear_2']."' and matched=0 limit 1";
			$q_s= mysqli_query($con, $s);// or die( mysqli_error($con)); 
			$n_s= mysqli_num_rows($q_s); 
			if($n_s > 0){
				$q= mysqli_fetch_array($q_s, 3 );
				//echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>{$r[payer]}</td><td>{$r[amount]}</td></tr>";
				
				$sqll="update recon_remitatb set matched = 1, match_code=3 where id=".$r['id'];
				if( mysqli_query($con, $sqll)){ 
					$sqllx="update recon_banktb set matched = 1, match_code=3 where id=".$q['id'];
					 mysqli_query($con, $sqllx);
				}
			}
			
		}
			$_SESSION['direct_bank_debit']="<h3>RECOUNCILIATION COMPLETED FOR ".strtoupper($_POST['rmonth_2']).", ".$_POST['ryear_2']."</h3>";
	}

		?>
				    
				    
				    
				    <center><hr></center>
									
									<?php 
									
									if(isset($_SESSION['step2']) && $_SESSION['input_method']=="uploading") {?>
										<div class="col-md-12 col-md-offset-0" style="margin-top:0em ; padding-top:0em ; ">
											<div class="panel panel-primary">
												<div class="panel-body">
													<div class="col-md-10 col-md-offset-1">
													
													<div class="<?php echo $_SESSION['alert_type'];?>">	
														<h4 class="bold"> <?php  echo $_SESSION['alert_msg'];?> 
                                                        </h4>
													</div> 
																		
														<!--  when excel file has been uploaded  -->
															<?php 
																if($_SESSION['is_excel']){ 
																	### check if sheetCount is > 1 ?
																	if($_SESSION['sheetCount'] > 0){  ?>
																	 <!-- query the user to select which file to load  -->
																	<div class="col-sm-offset-0 col-md-12 text-capitalize text-center font-16 bold" style="color:black; ">
                                                                    <h5>Your file contains<?php echo $_SESSION['sheetCount'] ?> worksheets, select active worksheet.</h5></div>
																	
																	  <div class="col-md-12 col-md-offset-0">
																		 <center>
																		  <div class="checkbox-radios">
																			   <div class="form-group input-field radio font-20" style="line-height:30px;"> 						
																					<?php 
																			   // list all sheets found
																				foreach($_SESSION['sheetNames'] as $sheets){ ?>
																					<label> <input type="radio" name="sheet_to_read" value="<?php echo $sheets; ?>"  <?php echo ($_SESSION['sheet_to_read']==$sheets)?"checked":""; ?> class="radio with-gap"  /> <?php echo $sheets; ?>  </label> &nbsp; &nbsp; 
																					<?php } 
																					### end foreach  
																					?> 														
																			</div> <!-- ./ form-group -->
																		  </div> <!-- ./ checkbox-radios -->
                                                                          
																		</center>	
																	</div>  <!-- ./ col-md-10 -->
																	  
																	<div class="col-md-12 col-md-offset-0"> 
																		<center>
																			<button type="submit" class="btn btn-primary" name="read_sheet_content" id="read_sheet_content"> READ SHEET </button>
																			&nbsp;&nbsp;
																			<button type="submit" class="btn btn-danger" name="cancel_read_sheet_content" id="cancel_read_sheet_content"> CANCEL READ SHEET </button>
																		</center>	
																	</div>
																 	<h5><?php echo "Select Fields<br>".$_SESSION['field_select']; ?></h5>
																	<?php } ### end if sheet is > 0  ?>
														 
																<p>&nbsp;   </p> <!-- display content of worksheet here -->
																
												  </div> <!-- ./ col-md-10 -->
																
														<div class="col-md-12 ">
															<?php 
if(isset($_POST['read_fields']) and isset($_POST['pid']) and $_POST['pid']!=''){															if(!is_null($_SESSION['new'])) {
																## diaplay all new record in a table ?>
															 <div class="table">
																<table class="table table-responsive table-bordered">
																	<tr class="bg-success"><th colspan="6"> <?php echo count($_SESSION['new']); ?> new records inserted </th> </tr>
																	<?php //$i = 0; foreach($_SESSION['new'] as $rows){?>
																	<!--<tr>
																	<td align="center"> <?php echo $i+1;?></td>
																	 <td>  <?php echo $rows[$headers[$_POST['pid']]]; ?> </td>  
																	 <td>  <?php echo $rows[$headers[$_POST['pay']]]; ?> </td>  
																	<td>   <?php echo $rows[$headers[$_POST['amt']]]; ?></td>
																	</tr>-->
																	<?php //$i++; }?>
																</table>
															 </div>
															<?php }

															if(!is_null($_SESSION['dup'])) {
																## diaplay all duplicate record in a table ?>
															 <div class="table">
																<table class="table table-responsive table-bordered">
																	<tr class="bg-danger"><th colspan="6"> <?php echo count($_SESSION['dup']); ?> duplicate records found </th> </tr>
																	<?php //$i = 0; foreach($_SESSION['dup'] as $rows){?>
																	<!--<tr class="text-danger">
																	<td align="center"> <?php echo $rows['num'];?></td>
																	 <td>  <?php echo $rows[$headers[$_POST['pid']]]; ?> </td>  
																	 <td>  <?php echo $rows[$headers[$_POST['pay']]]; ?> </td>  
																	<td>   <?php echo $rows[$headers[$_POST['amt']]]; ?></td>
																	</tr>-->
																	<?php //$i++; }?>
																</table>
															 </div>
															<?php } ####  end print all duplicate students 

} //end if(isset($_POST['read_fields']) and isset($_POST['pid']) and $_POST['pid']!='')

?>
														
														</div>  <!-- ./ col-md-12 -->								
														
													<?php }  ### end is_excel ?> 
														
					  
												</div> <!-- /. panel-body -->	
											</div> <!-- /. panel-primary -->	
						</div> <!-- /. col-md-12 -->	
									
									
									<?php }else{
										
if(isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and $_POST['ryear_2']!='') {
	$_SESSION['r_m']=$_POST['rmonth_2']; 
	$_SESSION['r_y']=$_POST['ryear_2'];
}

	if(isset($_POST['btn_debit']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
		//$_SESSION['direct_bank_debit']=
		/*echo */ //$bx = "<hr>Control Report<table><tr><td>";
		
		$sql="SELECT * FROM recon_remitatb where rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and matched=1";
		$qry= mysqli_query($con, $sql); $sn=0; $total_r=0; $nx= mysqli_num_rows($qry);
		
		/*echo */ $bx .= "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='5'><strong>REMITA STATEMENT [{$nx}]</strong></th></tr>
		<tr><td>SN</td><td>REMITA REF.</td><td>RRR</td><td>TRANSACTION NARATION</td><td>AMOUNT</td></tr>";
		
		while($r= mysqli_fetch_array($qry, 3 )){
			++$sn; $total_r += $r['amount'];
			/*echo */ $bx .= "<tr><td>{$sn}</td>
			<td>{$r[credit_reference]}</td><td>{$r[paymentid]}</td><td>{$r[payer]}</td><td>{$r[amount]}</td></tr>";
		}
		
		$total_r = number_format($total_r, 2);
		
		/*echo */ $bx .= "<tr><td>{$sn}</td>
			<td></td><td></td><th>TOTAL:</th><th>{$total_r}</th></tr></TABLE>";
			///echo "</td><td>";
		//=============================================================================================================================================================================================================================
		$sql="SELECT * FROM recon_banktb where rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and matched=1";
		$qry= mysqli_query($con, $sql); $sn=0; $total_b=0; $ny= mysqli_num_rows($qry);
		
		/*echo */ $by = "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='5'><strong>BANK STATEMENT [{$ny}]</strong></th></tr>
		<tr><td>SN</td><td>CREDIT REF.</td><td>RRR</td><td>TRANSACTION NARATION</td><td>AMOUNT</td>
		</tr>";
		
		while($b= mysqli_fetch_array($qry, 3 )){
			++$sn; $total_b += $b['amount'];
			/*echo */ $by .=  "<tr><td>{$sn}</td><td>{$b[credit_reference]}</td><td>{$b[paymentid]}</td><td>{$b[payer]}</td><td>{$b[amount]}</td>
			</tr>";
		}
		
		$total_b = number_format($total_b, 2); 
		
		/*echo */ $by .= "<tr><td>{$sn}</td><td></td><td></td><th>TOTAL:</th><th>{$total_b}</th></tr></TABLE>";
		/*echo */ //$bx .= "<td></tr></table>";
		
		/*echo "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} DIRECT BANK DEBIT [{$nm}]</h5></th></tr>
		<tr><td>SN</td><td>REMITA REF.</td><td>REMITA RRR</td><td>TRANSACTION NARATION (Remita)</td><td>AMOUNT (Remita)</td>
		<td>CREDIT REF.</td><td>BANK RRR</td><td>TRANSACTION NARATION (Bank)</td><td>AMOUNT (Bank)</td>
		</tr>";
		
		$sql="SELECT * FROM recon_remitatb where rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and matched=1";
		$qry= mysqli_query($con, $sql); $sn=0; $total_r=$total_b=0; $nm= mysqli_num_rows($qry);
		while($r= mysqli_fetch_array($qry, 3 )){
			++$sn; $total_r += $r['amount'];
			$a= mysqli_query($con, "select * from recon_banktb where paymentid='". mysqli_real_escape_string($con, $r['paymentid'])."' and amount='". mysqli_real_escape_string($con, $r['amount'])."'");
			$b=@mysqli_fetch_array($a, 3 ); $total_b += $b['amount'];
			//$_SESSION['direct_bank_debit'].=
			echo "<tr><td>{$sn}</td>
			<td>{$r[credit_reference]}</td><td>{$r[paymentid]}</td><td>{$r[payer]}</td><td>{$r[amount]}</td>
			<td>{$b[credit_reference]}</td><td>{$b[paymentid]}</td><td>{$b[payer]}</td><td>{$b[amount]}</td>
			</tr>";
		}
		
		$total_b = number_format($total_b, 2); $total_r = number_format($total_r, 2);
		//$_SESSION['direct_bank_debit'].=
		echo "<tr><td>{$sn}</td>
			<td></td><td></td><th>BANK TOTAL:</th><th>{$total_r}</th>
			<td></td><td></td><th>REMITA TOTAL:</th><th>{$total_b}</th></tr></TABLE>";*/ //<th colspan=3>TOTAL</th><th><h5>{$total}</h5></th>
			
		$_SESSION['input_method']="direct bank debit";
		
			echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
				echo $bx;
			echo "</td><td>";
				echo $by;
			echo "<td valign='top'></tr></table><hr>";

	}

	if(isset($_POST['btn_ucr']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
	//if(isset($_POST['']) and isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and $_POST['ryear_2']!='' ){
		//$sql="SELECT b.* FROM recon_banktb b LEFT OUTER JOIN recon_remitatb r ON b.paymentid=r.paymentid AND b.amount=r.amount WHERE r.paymentid IS NULL AND r.amount IS NULL";
		
		$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and matched=0";
		$qryb= mysqli_query($con, $sqlb); $sn=0; $total=0; $nm= mysqli_num_rows($qryb);
		//$_SESSION['uncreadited_lodgment']=
		echo "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} UNCREDITED LODGMENT [{$nm}]</h5></th></tr>
		<tr><td>SN</td><td>PAYMENT ID</td><td>PAYEE PARTICULARS</td><td>AMOUNT</td></tr>";
		while($r= mysqli_fetch_array($qryb, 3 )){++$sn; $total += $r['amount'];
			//$_SESSION['uncreadited_lodgment'].=
			echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>{$r[payer]}</td><td>{$r[amount]}</td></tr>";
		}
		$total = number_format($total, 2);
		//$_SESSION['uncreadited_lodgment'].=
		echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
		$_SESSION['input_method']="uncreadited lodgment";
	}
	

	if(isset($_POST['btn_ctrl']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
		$sqlr="SELECT * FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and matched=0";
		$qryr= mysqli_query($con, $sqlr); $sn=0; $total=0; $nm= mysqli_num_rows($qryr);
		//$_SESSION['control_report']=
		echo "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} CONTROL REPORT [{$nm}]</h5></th></tr>
		<tr><td>SN</td><td>PAYMENT ID</td><td>PAYEE PARTICULARS</td><td>AMOUNT</td></tr>";
		while($r= mysqli_fetch_array($qryr, 3 )){++$sn; $total += $r['amount'];
			//$_SESSION['control_report'].=
			echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>{$r[payer]}</td><td>{$r[amount]}</td></tr>";
		}
		$total = number_format($total, 2);
		//$_SESSION['control_report'].=
		echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
		$_SESSION['input_method']="control report";
	}
										
									}
									/*if(isset($_SESSION['direct_bank_debit']) && $_SESSION['input_method']=="direct bank debit") { echo $_SESSION['direct_bank_debit']; 
									}else if(isset($_SESSION['uncreadited_lodgment']) && $_SESSION['input_method']=="uncreadited lodgment") { echo $_SESSION['uncreadited_lodgment'];
									}else if(isset($_SESSION['control_report']) && $_SESSION['input_method']=="control report") { echo $_SESSION['control_report'];
									}*/ ?>
									<!-- END STEP 2  of form uploading -- -->
                    
                    
                    </td>
			    </tr>
              
              </table></td>
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
	<?php include_once("footer.php"); 
	 unset($_SESSION['alert_msg']);
	 unset($_SESSION['alert_type']);
     ?>
</div><!-- end of footer  tooplate_footer_wrapper-->

</body>
</html>