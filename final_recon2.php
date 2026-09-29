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
	var url="scriptfile_m.php";
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

  if(cv=='reconProcess')
          {
               $.post(url,{contentvar:cv, recordType:v, month:$("#pcmonth").val(), year:$("#pcyear").val()},function(data){
                    $(divid).html(data).show();
               });
          }//end of

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
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" name="consfrm" id="consfrm">
                                             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                  <tr>
                                                       <td width="50%" align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
                                                            <tr>
                                                                 <td height="33" colspan="2" align="left" bgcolor="#CCFFFF"><h4><strong>LOAD DATA</strong></h4></td>
                                                            </tr>
                                                            <tr>
                                                                 <th height="33" align="left" bgcolor="#CCFFFF">Month/Year:<strong style="color:#F00">*</strong></th>
                                                                 <td height="33" align="left" bgcolor="#CCFFFF"><select name="rmonth" id="rmonth">
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
                                                                 <select name="ryear" id="ryear">
                                                                      <option selected="selected" value="">--</option>
                                                                      <?php for($t=date('Y'); $t >= 2017; $t--) echo '<option value="'.$t.'">'.$t.'</option>'; ?>
                                                                 </select>

                                                                 <input type="hidden" id="status" name="status" value="">
                                                                 <input type="hidden" id="id" name="id" value="">
                                                            </td>
                                                       </tr>
                                                       <tr>
                                                            <th height="33" align="left" bgcolor="#CCFFFF">Select File:<strong style="color:#F00">*</strong></th>
                                                            <td height="33" align="left" bgcolor="#CCFFFF"><input type="file" name="rfile" id="rfile"></td>
                                                       </tr>
                                                  </table>
                                             </td>
                                             <td width="50%" align="left" valign="top"><table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
                                                  <tr>
                                                       <td height="33" colspan="2" align="left" bgcolor="#CCFFFF"><h4>&nbsp;</h4></td>
                                                  </tr>
                                                  <tr>
                                                       <th height="33" align="left" nowrap="nowrap" bgcolor="#CCFFFF">Record Type:<strong style="color:#F00">*</strong></th>
                                                       <td height="33" align="left" bgcolor="#CCFFFF"><select name="recordtype" id="recordtype" >
                                                            <option selected="selected" value="">--</option>
                                                            <option value="recon_banktb">Bank Statement</option>
                                                            <option value="recon_remitatb">Remita Statement</option>
                                                       </select>
                                                  </td>
                                             </tr>
                                             <tr>
                                                  <td height="33" bgcolor="#CCFFFF">&nbsp;</td><td height="33" align="left" bgcolor="#CCFFFF">
                                                       <button type="submit" class="btn btn-outline-primary btn-fw" name="sbtn_n" id="sbtn_n"> Read Excel File </button></td>
                                                  </tr>
                                             </table>
                                        </td>
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
                                                       if( isset($_POST['sbtn_n']) and isset($_POST['rmonth']) and $_POST['rmonth']!='' and isset($_POST['ryear']) and $_POST['ryear']!='' and isset($_POST['recordtype']) and $_POST['recordtype']!='' ){
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

                                                            $status = false;   
						$_SESSION['sheetData'] = null;

                                                            /******** start processing *********/
                                                            $nfn = $_POST['rmonth'].$_POST['ryear']."_imported_at_".time();

                                                            // create a directory
                                                            if(!is_dir("upload_files/recon/")) mkdir("upload_files/recon/");

                                                            $fext = array('application/vnd.ms-excel','application/xls','text/xls','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
						
                                                            /************************************/

                                                            foreach($fext as $mime) {
                                                                 if($mime == $_FILES['rfile']['type']) {
                                                                      $status = true;
                                                                      // unlink("../assets/uploads/");
                                                                      $newFPath =  "upload_files/recon/".$nfn.'.'."xls";
                                                                      $nfname = $nfn.'.'."xls";
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
						     ///echo "Here. ".$_SESSION['qtn_path']; //exit;
                                                                 // start excel reading
                                                                 $objPHPExcel = PHPExcel_IOFactory::load($_SESSION['qtn_path']);
						     ///echo "Here 2"; exit;
                                                                 $_SESSION['sheetCount'] = $objPHPExcel->getSheetCount();

                                                                 $_SESSION['sheetNames'] = $objPHPExcel->getSheetNames();

                                                                 // $sheetData = $objPHPExcel->getSheetByName($sheetNames[0])->toArray(null,true,true,true);

                                                                 // var_dump($sheetData);

                                                                 $_SESSION['alert_msg'] = " About To Read Your Uploaded File ";
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

                                                                 //}
                                                                 else*/ {
                                                                 // get body
                                                                 ////////////////////////////////////////////////////////////////
                                                                 $_SESSION['contents'] = $fCont = array();		/// full contents
                                                                 $_SESSION['permit_delete_bulk'] = false;
                                                                 $_SESSION['alert_msg'] = " Your Uploaded File Has Been Read  Successfully. ";
                                                                 $_SESSION['alert_type'] = "bg-success bold text-center";

                                                                 $select='<option value="" selected>---</option>';
                                                                 for($i=0; $i < count($headers); $i++)
                                                                 $select .=  '<option value="'.$i.'">'.$headers[$i].'</option>';

                                                                 /*if($_SESSION['recordtype']=="recon_tb")*/ $_SESSION['field_select'] = '<hr><table width="" align="center" cellpadding="2">
                                                                 <tr><td>PAYMENT DESC.</td><td>REFERENCE</td><td>DEBIT</td><td>CREDIT</td></tr>
                                                                 <tr><td><select id="pid" name="pid">'.$select.'</select></td>
                                                                 <td><select id="cref" name="cref">'.$select.'</select></td>
                                                                 <td><select id="pay" name="pay">'.$select.'</select></td>
                                                                 <td><select id="amt" name="amt">'.$select.'</select></td></tr>
                                                                 <tr><td colspan="2">
						     <select id="inacctx" name="inacctx" class="form-control">
						     <option value="">---</option>';
						     
						     $qr=mysqli_query($con, "SELECT DISTINCT funding FROM recon_remitatb WHERE paytype='Debit' AND funding!=''");
						     while($r=mysqli_fetch_array($qr,3)){
							$_SESSION['field_select'] .= '<option value="'.$r['0'].'">'.$r['0'].'</option>';
						     }
						     
						     $_SESSION['field_select'] .= '</select>
						     <button type="submit" name="read_fields" id="read_fields"> GET CONTENTS </button></td><td><button type="submit" name="cancel_read_fields" id="cancel_read_fields"> CANCEL </button></td></tr>';


                                                                 if($_SESSION['recordtype']=="recon_remitatb") $_SESSION['field_select'] = '<hr><table width="" align="center" cellpadding="2">
                                                                 <tr><td><u>INFLOW&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>OUTFLOW</td><td><u>PAYER&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>/PAYEE</td><td>PURPOSE<br>&nbsp;</td><td><u>CREDIT REF.</u><br>/DEBIT REF.</td><td><u>NET AMOUNT</u><br>AMOUNT</td><td><u>NARRATION</u><br>/BATCH NO.</td><td><u>RRR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br>/REMITA REF.</td></tr>
                                                                 <tr><td><select id="pay" name="pay" style="width:100px;">
                                                                 <option value="" selected>---</option>
                                                                 <option value="Credit">Credit (Inflow)</option>
                                                                 <option value="Debit">Debit (Outflow)</option>
                                                                 </select></td>
						     <td><select id="payer" name="payer" style="width:100px;">'.$select.'</select></td>
						     <td><select id="pid" name="pid" style="width:100px;">'.$select.'</select></td>
                                                                 <td><select id="cref" name="cref" style="width:100px;">'.$select.'</select></td>
                                                                 <td><select id="amt" name="amt" style="width:100px;">'.$select.'</select></td>
						     <td><select id="nar" name="nar" style="width:100px;">'.$select.'</select></td>
						     <td><select id="rrr" name="rrr" style="width:100px;">'.$select.'</select></td>
                                                                 </tr>
                                                                 <tr><td>ACCOUNT:<br><select id="inacctx" name="inacctx" class="form-control">
						     <option value="">---</option>';

						     $qr=mysqli_query($con, "SELECT DISTINCT funding FROM recon_remitatb WHERE paytype='Debit' AND funding!=''");
						     while($r=mysqli_fetch_array($qr,3)){
							$_SESSION['field_select'] .= '<option value="'.$r['0'].'">'.$r['0'].'</option>';
						     }

						     $_SESSION['field_select'] .= '</select></td></tr>

                                                                 <tr><td><button type="submit" name="read_fields" id="read_fields" value="GET CONTENTS"> GET CONTENTS </button></td><td><button type="submit" name="cancel_read_fields" id="cancel_read_fields"> CANCEL </button></td><td></td></tr>';
                                                            }
                                                       }
                                                  }
                                                  //=======================SEPARATION POINT FROM PREVIOUS READ_SHEET====================

                                                  if(isset($_POST['read_fields']) and isset($_POST['pid']) and $_POST['pid']!=''){

                                                       //echo $_POST['pid'],"++++++++++++++++++";
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
                                                                 $sRow[] =  mysqli_real_escape_string($con, trim(iconv("UTF-8","ISO-8859-1", $cell->getFormattedValue())," \t\n\r\0\x0B\xA0") );
                                                                 //," \t\n\r\0\x0B\xA0"); // to remove white spaces
                                                                 /*echo "<script>alert($sRow);</script>";	*/
                                                                 // $dataType = PHPExcel_Cell_DataType::dataTypeForValue($val);
                                                                 //$sRow[] = $cell->getValue();
                                                            }
                                                            $_SESSION['contents'][] = $fCont[] = array_combine($headers, $sRow); // merge each column head with cell values
                                                       }	 ### end for loop
                                                       ///

                                                       // now save the record to database
                                                       $_SESSION['dup'] = null;
                                                       $_SESSION['new'] = null;

                                                       ///begin();
                                                       //$query = array();
                                                       $credCol='';
                                                       foreach($_SESSION['contents'] as $rows){
                                                            //$fd = $dbm->getFields($dbm->select("questions",array("session"=>$rows['session'],"question"=>$rows['question'],"option1"=>$rows['option1'],"option2"=>$rows['option2'])),array_keys($rows));
                                                            //////echo "Write Select Query!";
                                                            //$query1="SELECT * FROM ".$_SESSION['recordtype']." WHERE paymentid='".$rows[$headers[$_POST['pid']]]."' and amount=".$rows[$headers[$_POST['amt']]]." and rmonth='".$_SESSION['rmonth']."' and ryear='".$_SESSION['ryear']."'";
                                                            /*
							$query1="SELECT * FROM ".$_SESSION['recordtype']." WHERE paymentid='".$rows[$headers[$_POST['pid']]]."' and amount=".$rows[$headers[$_POST['amt']]];
							$qr= mysqli_query($con,  $query1);// or die( mysqli_error($con));
							$fd= mysqli_num_rows($qr);
							//$str = $rows['session'].",".$rows['question'].",".$rows['option1'].",".$rows['option2'];
							//if(!empty($fd)){
							if($fd > 0){
							$_SESSION['dup'][] = $rows;
							$dup++;
							//}

							else
                                                            */
                                                            {
                                                                 //if($rows[$headers[$_POST['pid']]] != '' and $rows[$headers[$_POST['amt']]] != '')
                                                                 {
                                                                      //$cref = str_replace('\\\\', '\\', $rows[$headers[$_POST['cref']]]);
                                                                      //$cref = $rows[$headers[$_POST['cref']]];
							//==============> INSERTING BANK STATEMENT <==================
                                                                      if($_SESSION['recordtype']=="recon_banktb"){
                                                                           if(trim($rows[$headers[$_POST['amt']]]) == '' and $rows[$headers[$_POST['cref']]] != '') {
                                                                                $paytype='Debit';
                                                                                $amount= mysqli_real_escape_string($con,  str_replace(',','',$rows[$headers[$_POST['pay']]])); //$rows[$headers[$_POST['amt']]];
                                                                           }
                                                                           elseif(trim($rows[$headers[$_POST['pay']]]) == '' and $rows[$headers[$_POST['cref']]] != '') {
                                                                                $paytype='Credit';
                                                                                $amount= mysqli_real_escape_string($con,  str_replace(',','',$rows[$headers[$_POST['amt']]])); //$rows[$headers[$_POST['pay']]];
                                                                           }
                                                                           $desc = str_replace('\\\\', '\\', $rows[$headers[$_POST['pid']]]);
                                                                           if(trim($rows[$headers[$_POST['cref']]]) != ''){
                                                                                $cref = $rows[$headers[$_POST['cref']]];

                                                                                $qury="INSERT INTO ".$_SESSION['recordtype']." (paymentid, paytype, amount, rmonth, ryear, entry_by, entry_date, entry_time, credit_reference) VALUES ('{$desc}', '". mysqli_real_escape_string($con,  $paytype)."', ".$amount.", '". mysqli_real_escape_string($con,  $_SESSION['rmonth'])."', '". mysqli_real_escape_string($con,  $_SESSION['ryear'])."', '". mysqli_real_escape_string($con,  $_SESSION['login_id'])."', now(), now(), '{$cref}')";
                                                                           }else{
                                                                                //$desc = trim($rows[$headers[$_POST['pid']]]);
                                                                                if($desc != '' && trim($desc) != "Balance At Period Start :") $qury="UPDATE ".$_SESSION['recordtype']." SET paymentid = concat(paymentid, '{$desc}') WHERE credit_reference='{$cref}' AND ryear='". mysqli_real_escape_string($con,  $_SESSION['ryear'])."' AND rmonth='". mysqli_real_escape_string($con,  $_SESSION['rmonth'])."'";
                                                                           }
							     @mysqli_query($con,  $qury);
                                                                      }

							//==============> INSERTING REMITA INFLOW <==================
                                                                      if($_SESSION['recordtype']=="recon_remitatb"){
                                                                           $amount= mysqli_real_escape_string($con,  str_replace(',','',$rows[$headers[$_POST['amt']]]));
                                                                           $narration= mysqli_real_escape_string($con, $rows[$headers[$_POST['nar']]]);
                                                                           $rrr= mysqli_real_escape_string($con, $rows[$headers[$_POST['rrr']]]);
                                                                           $paytype= mysqli_real_escape_string($con,  $_POST['pay']);
                                                                           $payer = mysqli_real_escape_string($con,  $rows[$headers[$_POST['payer']]]);
                                                                           $purpose = $desc = $rows[$headers[$_POST['pid']]];
							     $pur=explode('-', $purpose);
							     $purpose = trim($pur[0]);
							     /*if($paytype=="Credit"){
								if(strstr(strtolower($desc), "credit")){
									$cr = explode(':', $desc);
									$crs = $cr[count($cr)-1];
									$credCol = trim(str_replace('"', '', $crs));
									$desc='';
								}
							     }else*/if($paytype=="Debit"){
								$mm=mysqli_query($con, "SELECT * FROM banktb WHERE '".strtolower($purpose)."' in ('services charge', 'service charge', 'wvalue added tax', 'value added tax', 'withholding tax via taxpro', 'withholding tax')");
								//echo mysqli_num_rows($mm),"!!!";
								if(mysqli_num_rows($mm) < 1){
									$pur=explode(' ', strtoupper($purpose));
									$tsa=$pur[0];
									//$niddle=array("cbntet", "tender fee");
									//echo strpos(strtolower($pur[0]), "cbttet");
									if(strpos(strtolower($pur[0]), "cbntet") !== false) {
										$tsa=$pur[0];
									}else{
										if(strlen($pur[0]) < 8){
											$a=explode("of", strtolower($purpose));
											$b=explode(' ', trim(strtolower($a[1])));
											$tsa = strtoupper($b[0]);
										}elseif(strlen($pur[0]) >= 11){
											$a=explode('TSA', $pur[0]);
											$y=$a[1][0];
											//if($y!='0') $y='';
											$x = intval($a[1]);
											$mid="TSA";
											if($x==0) {
												$a=explode('TSAF', $pur[0]);
												$x = intval($a[1]);
												$mid="TSAF";
											}
											$x=str_pad($x, 3, '0', STR_PAD_LEFT); 
											$b=$a[0].$mid.$x;
											$a=explode($b, $pur[0]);
											$c=$a[1];

											$s=array("A", "B", "C", "D");
											$words=array("catering", "communication", "conference");
											if(in_array($c[0], $s) && !in_array(strtolower($c), $words)){
												$tsa = $b.$c[0];
											}else{
												$tsa = $b;
											}
											//echo $tsa,"||",$purpose,"<br>";
											$p = str_replace(trim($tsa), '', $purpose);
											$purpose=$tsa." ".$p;
											$purpose = str_replace('CATERING', ' CATERING', $purpose);					
										}else $tsa=$pur[0];
									}
									$search=array("nysc", "bal.");
									$tsa = strtoupper(str_replace($search, '', strtolower($tsa)));
									//echo $tsa,"<br>";
								}else{
									//echo $tsa,"<br>";
									//echo $purpose,"<br>";
								}
								////echo $tsa,"::",$purpose,"<br>";
							     }
                                                                           if($desc != ''){
                                                                                /////$st="SELECT * FROM ".$_SESSION['recordtype']." WHERE paymentid='{$desc}' AND ryear='". mysqli_real_escape_string($con,  $_SESSION['ryear'])."' AND rmonth='". mysqli_real_escape_string($con,  $_SESSION['rmonth'])."'";
                                                                                /////$q= mysqli_query($con,  $st);
                                                                                /////$cn= mysqli_num_rows($q);

								$crf = explode(':', $rows[$headers[$_POST['cref']]]);
								$ind=count($crf)-1;
								$cref = trim(str_replace('"', '', $crf[$ind]));
								//Special
								$cref = $rows[$headers[$_POST['cref']]]; 
								$funding = $_POST['inacctx'];
								if($cref=='') $cref='No Reference Found';

                                                                                if($paytype=="Credit") $qury="INSERT INTO ".$_SESSION['recordtype']." (paymentid, paytype, amount, rmonth, ryear, entry_by, entry_date, entry_time, credit_reference, special_ref, purpose, narration, payer, rrr, funding) VALUES ('{$desc}', '".$paytype."', ".$amount.", '". mysqli_real_escape_string($con,  $_SESSION['rmonth'])."', '". mysqli_real_escape_string($con,  $_SESSION['ryear'])."', '". mysqli_real_escape_string($con,  $_SESSION['login_id'])."', now(), now(), '{$cref}', '{$cref}', '{$purpose}', '{$narration}', '{$payer}', '{$rrr}', '{$funding}')";
								
								elseif($paytype=="Debit") $qury="INSERT INTO ".$_SESSION['recordtype']." (paymentid, paytype, amount, rmonth, ryear, entry_by, entry_date, entry_time, credit_reference, special_ref, purpose, narration, payer, rrr, special_ref2, funding) VALUES ('{$desc}', '".$paytype."', ".$amount.", '". mysqli_real_escape_string($con,  $_SESSION['rmonth'])."', '". mysqli_real_escape_string($con,  $_SESSION['ryear'])."', '". mysqli_real_escape_string($con,  $_SESSION['login_id'])."', now(), now(), '{$cref}', '{$cref}', '{$purpose}', '{$narration}', '{$payer}', '{$rrr}', '{$tsa}', '{$funding}')";
								@mysqli_query($con,  $qury) or die(mysqli_error());


								/*
									if($cn <= 0){
									$cref = $rows[$headers[$_POST['cref']]];

									$qury="INSERT INTO ".$_SESSION['recordtype']." (paymentid, paytype, amount, rmonth, ryear, entry_by, entry_date, entry_time, credit_reference, special_ref) VALUES ('{$desc}', '".$paytype."', ".$amount.", '". mysqli_real_escape_string($con,  $_SESSION['rmonth'])."', '". mysqli_real_escape_string($con,  $_SESSION['ryear'])."', '". mysqli_real_escape_string($con,  $_SESSION['login_id'])."', now(), now(), '{$cref}', '{$credCol}')";
									}elseif($cn > 0){
									$amt = str_replace(',', '',  mysqli_real_escape_string($con,  $rows[$headers[$_POST['amt']]]));
									if($desc != '') $qury="UPDATE ".$_SESSION['recordtype']." SET amount = amount + {$amt} WHERE paymentid='{$desc}' AND ryear='". mysqli_real_escape_string($con,  $_SESSION['ryear'])."' AND rmonth='". mysqli_real_escape_string($con,  $_SESSION['rmonth'])."'";
									}
								*/
                                                                           }
                                                                      }
                                                                      /*****if($desc != ''){
                                                                           if(@mysqli_query($con,  $qury)) $_SESSION['new'][] = $rows;
                                                                      }*****/
                                                                      $ins++;
                                                                 }
                                                            }
                                                       } //end foreach
					     /////$_SESSION['new'] = $ins;
					     $_SESSION['alert_msg'] = "<h4>{$ins} records inserted for {$_SESSION['rmonth']}, {$_SESSION['ryear']}!</h4";
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
                                                  $matchCode=rand(10, 99999);

					//==============> PROCESSING RECONCILLATION <==================
                                                  if(isset($_POST['btn_cr_ref']) and isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and $_POST['ryear_2']!='' ){
                                                       $_SESSION['r_m']=$_POST['rmonth_2']; $_SESSION['r_y']=$_POST['ryear_2'];

                                                       ///$sqlg="SELECT sum(amount) as cr_sum_r, credit_reference FROM recon_remitatb where matched=0 group by credit_reference";
                                                       ////$sqlg="SELECT sum(amount) as cr_sum_r, special_ref FROM recon_remitatb where matched=0 OR matched is Null group by special_ref";
					     $sqlg="SELECT special_ref FROM recon_remitatb where matched=0 OR matched is Null";
                                                       $qryg= mysqli_query($con,  $sqlg); //echo  mysqli_num_rows($qryg); 
                                                       $sn=0;
                                                       while($r= mysqli_fetch_array($qryg, 3 )){
						$ss1="UPDATE recon_banktb set matched=1, match_code='{$matchCode}', special_ref='{$r['special_ref']}' WHERE paymentid LIKE '%".$r['special_ref']."%'";
						mysqli_query($con,  $ss1);
						$ss2="UPDATE recon_remitatb set matched=1, match_code='{$matchCode}' WHERE special_ref='".$r['special_ref']."'";
						mysqli_query($con,  $ss2);
						
                                                            ///$s="SELECT sum(amount) as cr_sum_b, credit_reference from recon_banktb where credit_reference='".$r['credit_reference']."' and matched=0";
                                                            ////$s="SELECT sum(amount) as cr_sum_b from recon_banktb where paymentid LIKE '%{$r['special_ref']}%' and (matched=0 OR matched is Null)";
						/*$s="SELECT * from recon_banktb where paymentid LIKE '%{$r['special_ref']}%' and (matched=0 OR matched is Null)";
                                                            $q_s= mysqli_query($con,  $s);// or die( mysqli_error($con));
                                                            $n_s= mysqli_num_rows($q_s);
                                                            if($n_s > 0){
							while ($q= mysqli_fetch_array($q_s, 3 )) {
								echo $ss1="UPDATE recon_banktb set matched=1, match_code='{$matchCode}', special_ref='{$r['special_ref']}' WHERE paymentid LIKE '%".$r['special_ref']."%'";
								mysqli_query($con,  $ss1);
								echo $ss2="UPDATE recon_remitatb set matched=1, match_code='{$matchCode}' WHERE special_ref='".$r['special_ref']."'";
								mysqli_query($con,  $ss2);
							}
							*/
                                                                 /*$q= mysqli_fetch_array($q_s, 3 );
						     
                                                                 if($r['cr_sum_r'] == $q['cr_sum_b']){

                                                                      /// mysqli_query($con,  "update recon_banktb r, recon_remitatb b set r.matched=1, b.matched=1, r.match_code=1, b.match_code=1 where (r.credit_reference='".$r['credit_reference']."' and b.credit_reference='".$r['credit_reference']."')");
                                                                      $ss1="UPDATE recon_banktb set matched=1, match_code='{$matchCode}', special_ref='{$r['special_ref']}' WHERE paymentid LIKE '%".$r['special_ref']."%'";
                                                                      $ss2="UPDATE recon_remitatb set matched=1, match_code='{$matchCode}' WHERE special_ref='".$r['special_ref']."'";
                                                                      $bursary->begin();
                                                                      if( mysqli_query($con,  $ss1) &&  mysqli_query($con,  $ss2)) $bursary->commit();
                                                                      else $bursary->rollback();

                                                                      ///$sqll="update recon_remitatb set matched = 1 where credit_reference='".$r['credit_reference']."'";
                                                                      ///if( mysqli_query($con,  $sqll)){
                                                                      ///$sqllx="update recon_banktb set matched = 1 where credit_reference='".$r['credit_reference']."'";
                                                                      /// mysqli_query($con,  $sqllx);
                                                                      ///}
                                                                 } //end if($r['cr_sum_r'] == $r['cr_sum_b']){
							*/
						////}

					}
					echo $_SESSION['direct_bank_debit']="<h3>RECONCILIATION COMPLETED FOR ".strtoupper($_POST['rmonth_2']).", ".$_POST['ryear_2']."</h3>";
				}

				if(isset($_POST['btn_process'])) { //and isset($_POST['ryear_2']) and $_POST['ryear_2']!='' ){
				//isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and
				$_SESSION['r_m']=$_POST['rmonth_2']; $_SESSION['r_y']=$_POST['ryear_2'];

				echo $sqlg="SELECT * FROM recon_remitatb where (matched=0 OR matched is Null) AND ryear='{$_POST['ryear_2']}'";
				exit; $qryg= mysqli_query($con,  $sqlg); //echo  mysqli_num_rows($qryg);
				$sn=0;

				while($r= mysqli_fetch_array($qryg, 3 )){//$sn++;
					////$s="SELECT * FROM recon_banktb where paymentid='".$r['paymentid']."' and amount=".$r['amount']." and matched=0";

					//$s="UPDATE recon_banktb r, recon_remitatb b SET r.matched=1, b.matched=1, r.match_code='{$matchCode}', b.match_code='{$matchCode}', b.special_ref='{$r['special_ref']}' WHERE b.paymentid LIKE '%".$r['paymentid']."' AND b.amount='".$r['amount']."'"; //(r.paymentid='".$r['paymentid']."' AND b.paymentid LIKE '%".$r['paymentid']."') AND (r.amount='".$r['amount']."' AND b.amount='".$r['amount']."')";
					$s="UPDATE recon_banktb SET matched=1, match_code='{$matchCode}', special_ref='{$r['special_ref']}' WHERE b.paymentid LIKE '%".$r['paymentid']."'";
					$s2="UPDATE recon_remitatb SET matched=1, match_code='{$matchCode}' WHERE special_ref='{$r['special_ref']}'";

					//$s="SELECT b.paymentid FROM recon_banktb b WHERE b.paymentid LIKE '%".$r['paymentid']."' AND b.amount='".$r['amount']."'";
					$bursary->begin();
					if( mysqli_query($con,  $s) &&  mysqli_query($con,  $s2)) $bursary->commit();
					else $bursary->rollback();
				}//".strtoupper($_POST['rmonth_2']).",
				echo $_SESSION['direct_bank_debit']="<h3>RECONCILIATION COMPLETED FOR  ".$_POST['ryear_2']."</h3>";
			}

			if(isset($_POST['btn_process_name']) and isset($_POST['rmonth_2']) and $_POST['rmonth_2']!='' and isset($_POST['ryear_2']) and $_POST['ryear_2']!='' ){
			$_SESSION['r_m']=$_POST['rmonth_2']; $_SESSION['r_y']=$_POST['ryear_2'];

			$sqlg="SELECT * FROM recon_remitatb where rmonth='". mysqli_real_escape_string($con,  $_POST['rmonth_2'])."' and ryear='". mysqli_real_escape_string($con,  $_POST['ryear_2'])."' and (matched=0 OR matched is Null)";

			$qryg= mysqli_query($con,  $sqlg); //echo  mysqli_num_rows($qryg);
			$sn=0;
			while($r= mysqli_fetch_array($qryg, 3 )){//$sn++;
				$xp=explode(" ", trim($r['payer']));
				$s="SELECT * FROM recon_banktb where payer like '%".trim($xp[0])."%' and amount=".$r['amount']." and rmonth='".$_POST['rmonth_2']."' and ryear='".$_POST['ryear_2']."' and (matched=0 OR matched is Null) limit 1";
				$q_s= mysqli_query($con,  $s);// or die( mysqli_error($con));
				$n_s= mysqli_num_rows($q_s);
				if($n_s > 0){
				$q= mysqli_fetch_array($q_s, 3 );
				//echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>{$r[payer]}</td><td>{$r[amount]}</td></tr>";

				$sqll="update recon_remitatb set matched = 1, match_code='3' where id=".$r['id'];
				if( mysqli_query($con,  $sqll)){
					$sqllx="update recon_banktb set matched = 1, match_code='3' where id=".$q['id'];
					 mysqli_query($con,  $sqllx);
				}
			}

		}
		$_SESSION['direct_bank_debit']="<h3>RECOUNCILIATION COMPLETED FOR ".strtoupper($_POST['rmonth_2']).", ".$_POST['ryear_2']."</h3>";
	}

	?>



	<center><hr></center>

	<?php

	if(isset($_SESSION['step2']) && $_SESSION['input_method']=="uploading") {

	?>
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
					<h5><?=$_SESSION['rmonth'].", ".$_SESSION['ryear'];?><br>Your file contains <?php echo $_SESSION['sheetCount'] ?> worksheet(s), select active worksheet.</h5></div>

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
					<h5><?php echo "SELECT Fields<br>".$_SESSION['field_select']; ?></h5>
				<?php } ### end if sheet is > 0  ?>

				<p>&nbsp;   </p> <!-- display content of worksheet here -->

				</div> <!-- ./ col-md-10 -->

				<div class="col-md-12 ">
				<?php
				if(isset($_POST['read_fields']) and isset($_POST['pid']) and $_POST['pid']!=''){															if(!is_null($_SESSION['new'])) {
					## diaplay all new record in a table ?>
					<div class="table">
					<table class="table table-responsive table-bordered">
						<tr class="bg-success" style="color:white;"><th colspan="6"> <?php echo count($_SESSION['new']); ?> new records processed! </th> </tr>
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
		$qry= mysqli_query($con,  $sql); $sn=0; $total_r=0; $nx= mysqli_num_rows($qry);

		$sql2="SELECT * FROM recon_banktb where rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and matched=1";
		$qry2= mysqli_query($con,  $sql2); $total_b=0; $ny= mysqli_num_rows($qry2);

		$bx .= "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><strong>REMITA STATEMENT [{$nx}]</strong></th>
		<th colspan='3' bgcolor='white'><strong>BANK STATEMENT [{$ny}]</strong></th></tr>

		<tr><td><strong>SN</strong></td><td><strong>RRR</strong></td><td><strong>CREDIT REFERENCE</strong></td><td><strong>AMOUNT</strong></td>
		<td bgcolor='white'><strong>TRANS. REF.</strong></td><td bgcolor='white'><strong>CREDIT REFERENCE</strong></td><td bgcolor='white'><strong>AMOUNT</strong></td>
		</tr>";

		while($r= mysqli_fetch_array($qry, 3 )){
		++$sn; $total_r += $r['amount'];
		$bx .= "<tr><td>{$sn}</td>
		<td>{$r[credit_reference]}</td><td>{$r[paymentid]}</td><td>".number_format($r['amount'])."</td>";

		$bamount=$bursary->get_any_value('amount', 'recon_banktb', 'matched', '1', " AND paymentid LIKE '%{$r['paymentid']}' AND amount={$r['amount']}");
		$total_b += $bamount;
		$bref=$bursary->get_any_value('credit_reference', 'recon_banktb', 'matched', '1', " AND paymentid LIKE '%{$r['paymentid']}' AND amount={$r['amount']}");
		$bx .= "<td bgcolor='white'>".str_replace('\\\\','\\',$bref)."</td><td bgcolor='white'>".$bursary->get_any_value('paymentid', 'recon_banktb', 'matched', '1', " AND paymentid LIKE '%{$r['paymentid']}' AND amount={$r['amount']}")."</td><td bgcolor='white'>".number_format($bamount, 2)."</td>
		</tr>";
		}

		$total_r = number_format($total_r, 2);

		/*echo */ $bx .= "<tr><td></td><td></td><th>TOTAL:</th><th>{$total_r}</th>
		<td bgcolor='white'></td><th bgcolor='white'></th><th bgcolor='white'>{$total_r}</th>
		</tr></TABLE>";
		///echo "</td><td>";
		$_SESSION['input_method']="direct bank debit";

		echo "<center><h5>CONTROL REPORT FOR ".strtoupper($_SESSION['r_m']).", {$_SESSION[r_y]}</h5></center><hr><table><tr><td valign='top'>";
		echo $bx;
		echo "</td><td>";
		echo $by;
		echo "<td valign='top'></tr></table><hr>";

	}

	if(isset($_POST['btn_ucr']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
		$sqlb="SELECT * FROM recon_banktb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (matched=0 OR matched is Null)";
		$qryb= mysqli_query($con,  $sqlb); $sn=0; $total=0; $nm= mysqli_num_rows($qryb);
		//$_SESSION['uncreadited_lodgment']=
		echo "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} UNCREDITED LODGMENT [{$nm}]</h5></th></tr>
		<tr><td><strong>SN</strong></td><td><strong>CREDIT REFERENCE</strong></td><td><strong>TRANS. REF.</strong></td><td><strong>AMOUNT</strong></td></tr>";
		while($r= mysqli_fetch_array($qryb, 3 )){++$sn; $total += $r['amount'];
		//$_SESSION['uncreadited_lodgment'].=
		echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>".str_replace('\\\\', '\\', $r['credit_reference'])."</td><td>".number_format($r['amount'], 2)."</td></tr>";
		}
		$total = number_format($total, 2);
		//$_SESSION['uncreadited_lodgment'].=
		echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
		$_SESSION['input_method']="uncreadited lodgment";
	}


	if(isset($_POST['btn_ctrl']) and isset($_SESSION['r_m']) and $_SESSION['r_m']!='' and isset($_SESSION['r_y']) and $_SESSION['r_y']!='' ){
		$sqlr="SELECT * FROM recon_remitatb WHERE rmonth='".$_SESSION['r_m']."' and ryear='".$_SESSION['r_y']."' and (matched=0 OR matched is Null)";
		$qryr= mysqli_query($con,  $sqlr); $sn=0; $total=0; $nm= mysqli_num_rows($qryr);
		//$_SESSION['control_report']=
		echo "<TABLE width='100%' border='1' rules='rows'>
		<tr><th colspan='4'><h5>{$_SESSION[r_m]}, {$_SESSION[r_y]} CONTROL REPORT [{$nm}]</h5></th></tr>
		<tr><td><strong>SN</strong></td><td><strong>CREDIT REFERENCE</strong></td><td><strong>RRR</strong></td><td><strong>AMOUNT</strong></td></tr>";
		while($r= mysqli_fetch_array($qryr, 3 )){++$sn; $total += $r['amount'];
		//$_SESSION['control_report'].=
		echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>{$r[credit_reference]}</td><td>".number_format($r['amount'], 2)."</td></tr>";
		}
		$total = number_format($total, 2);
		//$_SESSION['control_report'].=
		echo "<tr><th colspan=3>TOTAL</th><th><h5>{$total}</h5></th></tr></TABLE>";
		$_SESSION['input_method']="control report";
	}

	}
	/*if(isset($_SESSION['direct_bank_debit']) && $_SESSION['input_method']=="direct bank debit") { echo $_SESSION['direct_bank_debit'];
	//}else if(isset($_SESSION['uncreadited_lodgment']) && $_SESSION['input_method']=="uncreadited lodgment") { echo $_SESSION['uncreadited_lodgment'];
		//}else if(isset($_SESSION['control_report']) && $_SESSION['input_method']=="control report") { echo $_SESSION['control_report'];
		//}*/
		?>
		<!-- END STEP 2  of form uploading -- -->


		</td>
		</tr>

		</table>
	</td>
		</tr>
		<!--<tr>
		<td align="left" valign="top">&nbsp;</td>
		<td align="left" valign="top">&nbsp;</td>
		</tr>-->
		</table>

		<div id="salary_code_section" style="width:100%"></div>
		<div id="display"></div>
		<div id="roll"></div>
		</form>
		<p>
		<form action="recreports.php" target="_blank" method="post" enctype="multipart/form-data">
		<table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
		<tr>
		<td height="3" colspan="2" align="center" bgcolor="#D6D6D6"><button type="button" class="btn btn-outline-primary btn-fw" name="btn_process" id="btn_process" onClick="swapcontent('reconProcess', '');"> RECONCILE BY CREDIT REFERENCE </button>
		<br><hr>
		<table width="" border="0" align="center" cellpadding="3" cellspacing="0">
		<tr>
		<td height="33" align="left" nowrap>
		<select name="pcmonth" id="pcmonth" class="form-control col-sm-5">
		<option selected="selected" value="">MONTH</option>
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

		<select name="pcyear" id="pcyear" class="form-control col-sm-5">
		<option selected="selected" value="">YEAR</option>
		<?php for($t=date('Y'); $t >= 2017; $t--) echo '<option value="'.$t.'">'.$t.'</option>'; ?>
		</select>
		</td>
		</tr>
		<tr>
		<th height="3" align="left"><button type="button" class="btn btn-outline-primary btn-fw" name="btn_process" id="btn_process" onClick="swapcontent('reconProcess', 'PayCode');"> RECONCILE BY INCOME CODE </button></th>

		</tr>
		</table>
		</td>
		</tr>
		<tr>
		<td height="3" colspan="2" align="center" bgcolor="#D6D6D6"><div id="reconProcess"></div><hr></td>
		</tr>
		<tr>
		<td height="3" colspan="2" bgcolor="#f1f1f1" align="center" style="font-size:16px;"><strong>REPORT</strong></td>
		</tr>
		<tr>
		
		<td height="3" align="center" bgcolor="#f1f1f1" nowrap colspan="2">Month/Year:<strong style="color:#F00">*</strong>
		<select name="rmonth_2" id="rmonth_2">
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
		<select name="ryear_2" id="ryear_2">
		<option selected="selected" value="">--</option>
		<?php for($t=date('Y'); $t >= 2017; $t--) echo '<option value="'.$t.'">'.$t.'</option>'; ?>
		</select>
		</td>
		</tr>
		<!--tr>
		<td height="33" colspan="2" align="center" bgcolor="#D6D6D6"><button type="submit" class="btn btn-outline-primary btn-fw" name="btn_cr_ref" id="btn_cr_ref"> RECONCILIATION BY CREDIT REFERENCE</button></td>
		</tr-->
		<!--tr>
		<td height="33" colspan="2" align="center" bgcolor="#D6D6D6"><button type="submit" class="btn btn-outline-primary btn-fw" name="btn_process_name" id="btn_process_name"> RECONCILIATION BY NAME </button></td>
		</tr>
		<tr>
		<th height="33" colspan="2" bgcolor="#D6D6D6">View Report<?php echo " for ".$_SESSION['r_m'].", ".$_SESSION['r_y']; ?></th>
		</tr-->
		<tr>
		<td height="33" colspan="2" align="left" nowrap bgcolor="#f1f1f1">
			<center><strong> [ INFLOW ] </strong></center>
			<hr><strong>By Credit Reference</strong>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_debit" id="btn_debit">Monthly Report</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_rlo" id="btn_rlo">REMITA Left-Over</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_clo" id="btn_clo">BANK Left-Over</button>
			<hr><strong>By Income Code</strong>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_pcr" id="btn_pcr">Monthly Report</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_apcr" id="btn_apcr">Annual Report</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_prlo" id="btn_prlo">REMITA Left-Over</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_pblo" id="btn_pblo">BANK Left-Over</button><hr>
			<center><strong> [ OUTFLOW ] </strong>
				<br>
				<select id="inacct" name="inacct" class="form-control">
					<option value="">---</option>
					<?php
					$qr=mysqli_query($con, "SELECT DISTINCT funding FROM recon_remitatb WHERE paytype='Debit' AND funding!=''");
					while($r=mysqli_fetch_array($qr,3)){
						echo "<option value='{$r[0]}'>{$r[0]}</option>";
					}
					?>
				</select>
			</center>

			<hr>
			<strong>REMITA/Bank Statement</strong>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_outflow" id="btn_outflow">Monthly Report</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_orlo" id="btn_orlo">REMITA Left-Over</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_oblo" id="btn_oblo">BANK Left-Over</button>

			<hr>
			<strong>REMITA/Cashbook </strong>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_coutflow" id="btn_coutflow">Monthly Report by Batch No.</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_poutflow" id="btn_poutflow">Monthly Report by PVNO</button>
			<hr><center>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_rccoutflow" id="btn_rccoutflow">REMITA/BANK/CASHBOOK</button>

			<hr>
			<strong> EXTRAS... </strong><br>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_rem_various" id="btn_rem_various">COMPARE VARIOUS PAYMENTS WITH REMITA BULK-CREDIT</button>
			<br><br>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_rem_left" id="btn_rem_left">FIND BANK LEFT-OVER (OUFLOW) IN REMITA BULK-CREDIT</button>
			<br><br>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_rem_left" id="btn_rem_left">FIND BANK LEFT-OVER (INFLOW) IN REMITA BULK-CREDIT</button>
			<hr>
			<!--button type="submit" class="btn btn-outline-primary btn-fw" name="btn_corlo" id="btn_corlo">REMITA Left-Over</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_coblo" id="btn_coblo">CASHBOOK Left-Over</button>

			<br>
			<strong> [BY PV NUMBER] </strong>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_poutflow" id="btn_poutflow">Monthly Report</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_porlo" id="btn_porlo">REMITA Left-Over</button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_poblo" id="btn_poblo">CASHBOOK Left-Over</button>

			<!--button type="submit" class="btn btn-outline-primary btn-fw" name="btn_ucr" id="btn_ucr"> Uncredited Lodgment </button>
			<button type="submit" class="btn btn-outline-primary btn-fw" name="btn_ctrl" id="btn_ctrl"> Direct Bank Credit</button> <button type="submit" class="btn btn-outline-primary btn-fw" name="btn_ctrl_ref" id="btn_ctrl_ref"> Same Credit Ref./Diff. Amount</button-->
		</td>
		</tr>
		</table>
		</form>
		</p>

		<p>
		<form action="recuploadreport.php" target="_blank" method="post" enctype="multipart/form-data">
		<table width="100%" border="0" align="left" cellpadding="3" cellspacing="0">
		<tr>
		<td height="33" colspan="6" align="left" bgcolor="#CCFFFF"><h4><strong>VIEW UPLOADED DATA</strong></h4></td>
		</tr>
		<tr>
		<th height="33" align="left" bgcolor="#CCFFFF">Month/Year:<strong style="color:#F00">*</strong></th>
		<td height="33" align="left" bgcolor="#CCFFFF">
		<select name="rmonthS" id="rmonthS">
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
		<select name="ryearS" id="ryearS">
		<option selected="selected" value="">--</option>
		<?php for($t=date('Y'); $t >= 2017; $t--) echo '<option value="'.$t.'">'.$t.'</option>'; ?>
		</select></td>
		<th align="left" bgcolor="#CCFFFF">Record:<strong style="color:#F00">*</strong></th>
		<td align="left" bgcolor="#CCFFFF"><select name="recordtypeS" id="recordtypeS" >
		<option selected="selected" value="">--</option>
		<option value="recon_banktb">Bank Statement</option>
		<option value="recon_remitatb">Remita Statement</option>
		</select>
		</td>
		<th align="left" bgcolor="#CCFFFF">Type:<strong style="color:#F00">*</strong></th>
		<td align="left" bgcolor="#CCFFFF"><select name="typeS" id="typeS" >
		<option selected="selected" value="">--</option>
		<option value="Credit">Credit</option>
		<option value="Debit">Debit</option>
		</select>
		</td>
		</tr>
		<tr>
		<td height="33" colspan="6" align="center" bgcolor="#CCFFFF">
		<button type="submit" class="btn btn-outline-primary btn-fw" name="sbtn_nS" id="sbtn_n2S"> VIEW RECORD </button> |
		<button type="submit" class="btn btn-outline-primary btn-fw" name="sbtn_nD" id="sbtn_nD"> DELETE RECORD </button>
		<p>&nbsp;</p>
		</td>
		</tr>
		</table>
		</form>
		</p>
		</div><p>&nbsp;</p>
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
