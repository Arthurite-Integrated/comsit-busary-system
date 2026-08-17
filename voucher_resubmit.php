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
 require_once ("connect.php");
 require_once ("function.php");
 require_once ("currency_convert.php");
 //echo base64_decode('T0xBREFZTw==');

?>
<!DOCTYPE html>
<html>
<head>
<link rel="shortcut icon" href="images/logox.png">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Voucher</title>
<style>
body {
font : 0.8em "Arial", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
</style>
 <link rel="stylesheet" type="text/css" href="include/easyui.css">
   <link rel="stylesheet" type="text/css" href="include/icon.css">
	<link rel="stylesheet" type="text/css" href="include/demo.css">
	<link rel="stylesheet" type="text/css" href="include/colorbox.css">
    <link rel="stylesheet" href="css/tinybox.css" />
     <script type="text/javascript" src="include/jquery.min.js"></script>
	<!--<script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.min.js"></script>-->
    
    <script type="text/javascript" src="include/jquery.easyui.min.js"></script>
    <script type="text/javascript" src="include/jquery.serializeobject.js"></script>
    <script type="text/javascript" src="include/tinybox.js"></script> 
	<script type="text/javascript" src="include/jquery.colorbox.js"></script> 
   
   <link href="css/default.css" rel="stylesheet" type="text/css" />
   <link rel="shortcut icon" href="images/logo.jpg"> <!-- put the image/logo on the browser tab -->
	<link href="css/fonts.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="datepicker/jquery-ui.css" />
    
	<!--<script src="datepicker/jquery-1.8.3.js"></script>
    <script src="datepicker/jquery-ui.js"></script>
	-->
	<script src="datepicker/datepicker/ui.datepicker.js"></script>
	<link rel="stylesheet" type="text/css" href="datepicker/datepicker/ui.datepicker.css">
	<script type="text/javascript" src="include/datagrid-groupview.js"></script>
	<script type="text/javascript" src="include/accounting.js"></script>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<link href="upload.css" rel="stylesheet" type="text/css" />
<script src="file/jquery.min.js"></script>
<script src="upload.js"></script>
<script type="text/javascript">
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   
	var divid="#"+cv;
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_m.php";
	var str;
	

  
	if(cv=='display_voucher_process') 
	{
		$.post(url,{contentvar:cv,pvno:v,r_vals:a},function(data){
		$(divid).html('').show(); 
		$("#display").html(data).show();
		$("#display").window("open");
		$("#roll").html('').show();
		//$("#display").html(data).show();
		});
  	}

	if(cv=='process_voucher_edit') 
	{
		$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v,function(data){ 
		$(divid).html(data).show(); 
		//$(".iframe").colorbox().close();
		//$("#roll").html('').show();
		document.location.reload();
		//refresh();
		});
  	}
  
	if(cv=='voucher_section_entry') 
	{
		if(v=='save')
		{
                		if($('#type option:selected').val() == 'External' && $("#bank").val()==''){
				alert("All compulsory fields must be filled before you can proceed");
				$('#roll').html('').show();
				exit();
                     	}
                
			if($("#pay_date").val()=='' || $("#dept").val()=='' || $("#pvno").val()=='' || $("#account").val()=='' || $("#folio").val()=='' || $("#type").val()=='' || $("#desc").val()=='' || $("#vamount").val()=='' || $("#pro_typ").val()=='')
			{
				alert("All compulsory fields must be filled before you can proceed");
				$('#roll').html('').show();
				exit();
			}
		}
			
		$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
			$(divid).html('').show();	
			$('#display2').html(data).show();
			$('#roll').html('').show();
			
				
			if(v=='save')
			{
					//swapcontent('voucher_section_entry','refresh');
				$("#pay_date").val(''); $("#dept").val(''); $("#pvno").val(''); $("#account").val('');
				$("#folio").val(''); $("#type").val(''); $("#fileno").val(''); $("#name").val('');$("#act_no").val('');
				$("#bank").val(''); $("#address").val(''); $("#payee_tin_number").val(''); $("#payee_sort_code").val('');
					
			}
			if(v=='refresh')
			{ 
				$(divid).html('').show();
				$('#display').html('').show();
				//$('#display2').html('').show();
				$('#load_voucher_fileno').html('').show();
				$('#generate_pvno').html('').show();
				$('#load_budget').html('').show();
				$('#roll').html('').show();
				$("#pay_date").val(''); $("#dept").val(''); $("#pvno").val(''); $("#account").val('');
				$("#folio").val(''); $("#type").val(''); $("#fileno").val(''); $("#name").val('');$("#act_no").val('');
				$("#bank").val(''); $("#address").val(''); $("#payee_tin_number").val(''); $("#payee_sort_code").val('');
				//exit();
			}//end of refresh div i.e to refresh the data dispay previously on selection of another department
					
			if(v=='edit')
			{
				var pData=jQuery.parseJSON(data); 
				alert(pData.s_detail);
				var p=jQuery.parseJSON(pData.s_detail);
				
				$("#sch_code").val(p.sch_code); $("#dept_code").val(p.dept_code); $("#dept_name").val(p.dept_name);
				$("#category").val(p.category); $("#r_id_edit").val(p.r_id); 
			} //for edit purpose
		});
	} 

	if(cv=='load_dept_account') //start unit
	{
		var category=$("#funddept_head").val();
		$.post(url,{contentvar:cv,category:category},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
	}//end of unit


  if(cv=='load_category') //start unit
  {
	  var fundcenter=$("#fundsource").val();
		$.post(url,{contentvar:cv,fundcenter:fundcenter},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit

  if(cv=='load_items_code') //start unit
  {
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
      
	$.post(url,{contentvar:cv,fundcenter:fundcenter, deptcode:deptcode, category:category},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
	});
  }//end of unit

} //end of swapcontent
	
	
	function upload_doc_edit($fileid){
		jQuery(function($){
			$.noConflict();
			var settings2 = {
				url: "scriptfile_m.php?mfileupload"+$fileid,
				dragDrop:false,
				fileName: "myfile2",
				allowedTypes:"pdf",	
				autoSubmit: false,
				returnType:"json",
				 onSuccess:function(files,data,xhr)
				{
				   // alert((data));
				},
				showDelete:false,
				multiple: false,
				maxFileCount: 1,
				showDone: true,
				maxFileSize: 500*1024
			}
			var uploadObj2 = $("#mulitplefileuploader2").uploadFile(settings2);
			//$('#new_memo_ok').click(function(e) {
			uploadObj2.startUpload2();
			//});
		});
	} //end function for upload supporting doc
</script>
</head>

<body >
<?php
@require_once "myclass_m.php";
	@$bursary = new myclass_m();

	$pvno=@base64_decode(@$_REQUEST['p']);
	//$pvno="FEBRUARY/2015/0001";
	
	$val_str=explode("***",get_company());
	if($pvno=='') { echo "<font color='red'><b>No search criteria provided</b></font>"; exit; }
	$res_v= mysqli_query($con, "select * from vouchertb where (pvno='$pvno' or pvno_paid='$pvno')");
	$rs_v=@mysqli_fetch_array($res_v);
	
   if(@mysqli_num_rows($res_v)>=1)
    {
	//header of the voucher
		
?>

<form method="post" enctype="multipart/form-data" id="voucheredit">
<table width="100%" border="0" align="center">
  <tr>
    <td width="5%" colspan="3"><center>
    <h2><strong>PAYMENT VOUCHER EDIT</strong></h2><hr></center></td>
  </tr>
  <tr>
    <td colspan="3"><table width="100%" border="0" align="center" cellpadding="3">
        <?php if($r_vals=="Cash Officer") { ?>
	<tr style="background-color:#BFCDAA;">
        <td colspan="2" align="left" valign="middle"><hr>FOR TREASURY ONLY [EDIT ASSIGNED PV]</td>
        </tr>
      <tr style="background-color:#BFCDAA;">
        <td align="left" valign="middle">
	<strong>Use this section to edit PV No. assigned to a voucher</strong>:
          
        </td>
        <td align="left" valign="middle">
	<strong>EDIT ASSIGNED PV NO.</strong>: 
	<input type="text" name="pre_pvno" id="pre_pvno" value="<?php if($rs_v['pvno_paid']!='') echo $rs_v['pvno_paid']; else echo $rs_v['pre_pvno']; ?>" />
	</td>
      </tr>
      <tr style="background-color:#BFCDAA;">
        <td align="left" valign="middle">
	<strong>MARK VOUCHER AS PAID?</strong>: 
	<select name="pre_paid" id="pre_paid" style="width: 300px" >
		<option value="<?php if($rs_v['paid_action']=='Approved') echo "Approved"; else echo ''; ?>" selected><?php if($rs_v['paid_action']=='Approved') echo "Paid Already"; else echo '---...---'; ?></option>
		<option value="Yes">Yes</option>
		<option value="No">No</option>
	</select>
	</td>
        <td align="left" valign="middle">
        <strong>PAYMENT DATE</strong>: 
	<input type="date" name="pre_date" id="pre_date" value="<?php if($rs_v['pvno_paid']!='') echo $rs_v['date_paid']; else echo ''; ?>" />
          
        </td>
      </tr>
      <tr style="background-color:#BFCDAA;">
        <td colspan="2" align="left" valign="middle"><center>
          <button value=" UPDATE PV NO. " type="button" onClick="swapcontent('process_voucher_edit', 'pvupdate');" class="l-btn"> UPDATE PV NO. </button>
        </center></td>
        </tr>
        <tr style="background-color:#BFCDAA;">
        <td colspan="2" align="left" valign="middle"><hr></td>
        </tr>
      <?php } ?>
      <tr>
        <td align="left" valign="middle"><strong>Processing No</strong>:
          
          <?php echo $rs_v['pvno']; ?>
          <input type="hidden" name="pvno" id="pvno" value="<?php echo $rs_v['pvno'];?>" />
          <input type="hidden" name="pid" id="pid" value="<?php echo $rs_v['id'];?>" />
	</td>
        <td align="left" valign="middle"><strong>PV No.</strong>: <?php echo $rs_v['pvno_paid']; ?></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><strong>Faculty/Dept</strong>: 
          <br>
          <select name="dept_vou" id="dept_vou" style="width: 300px" >
            <?php if($rs_v['dept_vou']==''){ ?><option value="">-- Select --</option><?php }else{ ?>
			<option value="<?php echo $rs_v['dept_vou']; ?>"><?php echo @get_unit_name('', $rs_v['dept_vou']); ?></option>
            
            <?php 
			}
			$q =  mysqli_query($con, "SELECT * from unittb order by unit_name");
					while($r= mysqli_fetch_array($q, 3 )) echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>';  ?>
            </select></td>
        <td width="48%" align="left" valign="middle"><strong>Voucher Date.</strong>:<br>          <input type="date" name="pay_date" style="width: 300px" class="date" id="pay_date" value="<?php echo $rs_v['voucher_date'];?>" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><strong>Account to be debited</strong>: <br>
          <select name="account" id="account" class="txt" style="width: 300px"  >
            <?php if($rs_v['dept_acctcode']==''){ ?><option value="">-- Select --</option><?php }else{ ?>
			<option value="<?php echo $rs_v['dept_acctcode']; ?>"><?php echo @get_account_name($rs_v['dept_acctcode'], false); ?></option>
                  <?php
			}
				$r=@mysqli_query($con, "select distinct *  from bank_accounttb order by acctcode");
				while ($rcourse=@mysqli_fetch_array($r))
				{
					$scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
					$bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
					$acctname=@$rcourse['acctname'];
					echo "<option value='$pcode'>$bank || $acctno || $scourse <=> ($pcode)</option>";
					
				}
								
								?>
            </select></td>
        <td align="left" valign="middle"><strong>Entry Unit:</strong><br /> <select name="voucher_unit" id="voucher_unit" style="width: 300px" onchange="swapcontent('refresh',this.value);">
                  <option selected="selected" value="<?=$rs_v['dept_code']?>"><?=get_unit_name('126', $rs_v['dept_code'])?></option>
				  <?php  $q =  mysqli_query($con, "select * from unittb where dept_code='126' order by id");
							  while($r= mysqli_fetch_array($q, 3 )){
								echo '<option value="'. $r['unit_code'] .'">'. $r['unit_name'] .'</option>';
							  }
							  ?>
			</select></td>
      </tr>
      <tr>
        <td height="36" colspan="2" align="left" valign="middle" bgcolor="#BFCDDB"><strong>Code</strong>: <?php echo @read_voucher_folio_code($rs_v['pvno']);?></td>
        </tr>
      <tr>
        <td align="left" valign="middle" bgcolor="#BFCDDB"><strong>Folio Code: (Separate multiple entries with ';')</strong></td>
        <td align="left" valign="middle" bgcolor="#BFCDDB"><strong>Amount: (Separate multiple entries with ';')</strong></td>
      </tr>
      <tr>
        <td align="left" valign="middle" bgcolor="#BFCDDB"><input type="text" name="folio" style="width: 300px" class="date" id="folio" value="<?php echo @read_folio_ca($rs_v['pvno'], 'folio_code');?>" /></td>
        <td align="left" valign="middle" bgcolor="#BFCDDB"><input type="text" name="amount" style="width: 300px" class="date" id="amount" value="<?php echo @read_folio_ca($rs_v['pvno'], 'amount');?>" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle" bgcolor="#BFCDDB"><center>
          <button value="UPDATE VOUCHER CODE" type="button" onClick="swapcontent('process_voucher_edit', 'code');" class="l-btn">UPDATE VOUCHER</button>
        </center></td>
        </tr>
      <tr>
        <td align="left" valign="middle"><strong>Payee</strong>: 
          <br>          <input type="text" name="payee_name" style="width: 300px" class="date" id="payee_name" value="<?php echo strtoupper($rs_v['payee_name']); ?>" /></td>
        <td align="left" valign="middle"><strong>File/Phone No</strong>: <br>          <input type="text" name="fileno" style="width: 300px" class="date" id="fileno" value="<?php echo $rs_v['fileno']; ?>" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><strong>Address</strong>:<br>          <input type="text" name="payee_address" style="width: 300px" class="date" id="payee_address" value="<?php echo $rs_v['payee_address']; ?>" /></td>
        <?php $pay_month=@date('F',@strtotime($rs_v['voucher_date'])); $pay_year=@date('Y',strtotime($rs_v['voucher_date']));?>
        <td align="left" valign="middle"><strong>Month</strong>: <?php echo "$pay_month, $pay_year";?></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><strong>Payee Bank</strong>: 
          <br>          <select name="payee_bank_name" id="payee_bank_name" class="txt" style="width: 300px"  >
            <?php if($rs_v['payee_bank_name']==''){ ?>
            <option value="">-- Select --</option>
            <?php }else{ ?>
			<option value="<?php echo $rs_v['payee_bank_name']; ?>"><?php echo $rs_v['payee_bank_name']; ?></option>
            <?php
			}
				  
			$q=@mysqli_query($con, "select *  from banktb order by bankname");
			while ($r=@mysqli_fetch_array($q))
				{
					echo "<option value='$r[bankname]'>$r[bankname]</option>";
					
				}
			
			?>
          </select></td>
        <td align="left" valign="middle"><strong>Payee Account No.</strong>: <br>          
          <input type="number" name="payee_acct_no" style="width: 300px" id="payee_acct_no" value="<?php echo $rs_v['payee_acct_no']; ?>" /></td>
      </tr>
      <tr>
        <td align="left" valign="middle"><strong>TIN Number</strong>:<br>          <input type="text" name="payee_tin_number" style="width: 300px" class="date" id="payee_tin_number" value="<?php echo $rs_v['payee_tin_number']; ?>" /></td>
        <td align="left" valign="middle"><strong>Sort Code</strong>: <br>          <input type="text" name="payee_sort_code" style="width: 300px" class="date" id="payee_sort_code" value="<?php echo $rs_v['payee_sort_code']; ?>" /></td>
      </tr>
      <?php
			   	if($rs_v['memo_id'] != ''){ ?>
      <tr>
        <td colspan="2" align="left" valign="middle" bgcolor="#E7E7E7"><?php
			   	
					$memo_id = @base64_decode($_REQUEST['id']);
					$rs=@mysqli_query($con, "select * from memotb where memo_id='$rs_v[memo_id]'");
					$rst=@mysqli_fetch_array($rs);
					$from=@$rst['memo_from'];$description=@$rst['description'];$amount=@$rst['amount'];$amount_approved=@$rst['amount_approved'];$remark=@$rst['remark'];
					$datein=@$rst['datein'];$file_path=@$rst['file_path'];
					?>
                    <div id="" style='width:700px;' class='easyui-panel' title='MEMO DETAILS'>
                      <table width="100%">
                      <tr><td width="50%"><?php echo "Memo ID : $rs_v[memo_id] ($datein)"; ?></td><td><?php echo "From : $from"; ?></td></tr>
                    		<tr>
                              <td colspan='2'><?php echo "Description : $description"; ?></td></tr></table>
				
			   
			   
			 <p align="right"> <a class='iframe easyui-linkbutton' iconCls="icon-tip" href="<?php echo $file_path;?>"><strong><font color="#000099">View Attached Document</font></strong></a></p>
              </div>
</td>
      </tr>
			   <?php } ?>
      <tr>
        <td colspan="2" align="left" valign="middle"><div id="process_voucher_edit"></div></td>
        </tr>
      <tr>
        <td colspan="2" valign="middle"><table width="100%" border="1" cellpadding="0" cellspacing="0">
          <tr>
            <td width="21%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>DATE</strong></div></td>
            <td height="26" colspan="2" bgcolor="#EAEAEA"><div align="center"><strong>DETAIL DESCRIPTION OF SERVICE(S) OR GOOD(S)</strong></div></td>
            <td width="20%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>AMOUNT (GROSS)</strong></div></td>
          </tr>
          <tr>
            <td height="26" align="center"><?php echo @date('d/m/Y',strtotime($rs_v['voucher_date']));?></td>
            <td height="26" colspan="2" align="center"><textarea name="desc" rows="5" id="desc" style="width:90%"><?php echo $rs_v['description'];?></textarea>              </td>
            <td height="26" align="center"><input type="number" name="amount_paid" id="amount_paid" value="<?php echo $rs_v['amount_approved']; ?>" /></td>
          </tr>
          
          <?php $pvno=@$rs_v['pvno'];
		        $res_tax=@mysqli_query($con, "select * from voucher_taxtb where pvno='$pvno' order by folio_code");
				while($rs_tax=@mysqli_fetch_array($res_tax))
				{
					
		 ?>
          <tr>
            <td height="26" align="center"><?php //echo @date('d/m/Y',strtotime($rs_v['voucher_date']));?></td>
            <td height="26" colspan="2" align="center"><?php echo @get_folio_name($rs_tax['folio_code']); ?></td>
            <td height="26" align="center">(<?php echo number_format($rs_tax['amount'],2); ?>)</td>
          </tr>
          <?php } //end of while ?>
          
          <?php
			$exp = explode('.',number_format($rs_v['amount_approved'],2,'.',''));
			$words = convertNum($exp[0]);
			$words2 = @str_replace("And","and",ucwords($words));
			$wordsKobo = convertNum($exp[1]);
			$wordsKobo2 = ucwords($wordsKobo);
			
			$amountInWords = "$words2 Naira";
			if ($wordsKobo2 != "Zero") { $amountInWords .= ", $wordsKobo2 Kobo "; }
			$amountInWords .= " Only.";
			//echo "<strong><em>Amount in words:</em> $amountInWords</strong>";
			?>
          <tr>
            <td height="26" colspan="2"><b>Total Amount in Words: <?php echo $amountInWords;?></b></td>
            <td width="18%" height="26"><div align="right"><strong>TOTAL (<?php echo "&#8358;"; ?>)</strong></div></td>
            <td height="26" align="center"><b><?php echo number_format($rs_v['amount_approved'],2);?></b></td>
          </tr>
        </table><center>
        <button value="UPDATE VOUCHER ADJUSTMENT" type="button" onClick="swapcontent('process_voucher_edit', 'voucher');" class="l-btn">UPDATE VOUCHER ADJUSTMENT</button></center></td>
      </tr>
      <tr>
        <td colspan="2" valign="middle">&nbsp;</td>
      </tr>
</table>
<?php 		        $res_tax=@mysqli_query($con, "select v.*, f.folio_code from vouchertb v INNER JOIN voucher_folio_codetb f ON v.pvno=f.pvno where v.pvno like '".$pvno."_%'");
	if( mysqli_num_rows($res_tax) > 0){
?>
<strong>DEDUCTION VOUCHER(S)</strong>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
          <tr>
            <td width="21%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>DATE</strong></div></td>
            <td height="26" colspan="2" bgcolor="#EAEAEA"><div align="center"><strong>ITEM DESCRIPTION</strong></div></td>
            <!--td height="26" colspan="2" bgcolor="#EAEAEA"><div align="center"><strong>ACCOUNT DESCRIPTION</strong></div></td-->
            <td width="20%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>BANK/AMOUNT</strong></div></td>
            <!--td width="20%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>ACCOUNT NO.</strong></div></td>
            <td width="20%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>AMOUNT</strong></div></td-->
          </tr>
          <?php //echo $pvno;
				while($rs_tax=@mysqli_fetch_array($res_tax))
				{		
		 ?>
          <tr>
            <td height="26" align="center"><?php echo @date('d/m/Y',strtotime($rs_v['voucher_date']));?></td>
            <td height="26" colspan="2" align="center"><?php echo $rs_tax['description']; ?>
            <input type="hidden" name="tax_id[]" id="tax_id<?php echo $rs_tax['id']; ?>" value="<?php echo $rs_tax['id']; ?>" />
            <input type="hidden" name="tax_pvno[]" id="tax_pvno<?php echo $rs_tax['id']; ?>" value="<?php echo $rs_tax['pvno']; ?>" /></td>
            <!--td height="26" colspan="2" align="center"><?php echo @get_folio_name($rs_tax['folio_code']); ?></td-->
            <td height="26" align="center"><strong>Bank Name: </strong>
            <select type="bankkk" name="tax_bankk[]" id="tax_bankk<?php echo $rs_tax['id']; ?>" class="txt" style="width:80%;" >
			<option value="<?php echo $rs_tax['payee_bank_name']; ?>"><?php echo $rs_tax['payee_bank_name']; ?></option>
            <?php
					$q=@mysqli_query($con, "select *  from banktb order by bankname");
					while ($r=@mysqli_fetch_array($q))
						{
							echo "<option value='$r[bankname]'>$r[bankname]</option>";
							
						}
					
					?>
          </select>
            </br>
            <strong>Account No: </strong><input type="accttt" name="tax_acctt[]" id="tax_acctt<?php echo $rs_tax['id']; ?>" value="<?php echo $rs_tax['payee_acct_no']; ?>" style="width:80%; text-align:center;" /></br>
            <strong>Amount: </strong><input type="number" name="tax_paid[]" id="tax_paid<?php echo $rs_tax['id']; ?>" value="<?php echo $rs_tax['amount_paid']; ?>" style="width:80%; text-align:center;" /></td>
            
            <!--td height="26" align="center"><input type="bankkk" name="tax_bankk[]" id="tax_bankk<?php echo $rs_tax['id']; ?>" value="<?php echo $rs_tax['amount_paid']; ?>" style="width:95%; text-align:center;" /></td>
            <td height="26" align="center"><input type="accttt" name="tax_acctt[]" id="tax_acctt<?php echo $rs_tax['id']; ?>" value="<?php echo $rs_tax['amount_paid']; ?>" style="width:95%; text-align:center;" /></td>
            <td height="26" align="center"><input type="number" name="tax_paid[]" id="tax_paid<?php echo $rs_tax['id']; ?>" value="<?php echo $rs_tax['amount_paid']; ?>" style="width:95%; text-align:center;" /></td-->
          </tr>
          <?php } //end of while ?>
</table><center><input type="hidden" id="r_vals" name="r_vals" value="<?php echo $r_vals; ?>" ?>
        <button value="UPDATE DEDUCTION ADJUSTMENT" type="button" onClick="swapcontent('process_voucher_edit', 'deduction');" class="l-btn">UPDATE DEDUCTION ADJUSTMENT</button></center>
<?php
	}
?>
<?php 
	$sql =  mysqli_query($con, "select * from `budget_votebooktb` where voucher_pvno = '".$pvno."'");
	if( mysqli_num_rows($sql) > 0){
	?>
    <strong>VOTEBOOK ENTRIES</strong>

<table width='100%' border='1' cellspacing='0' cellpadding='2' id='vou_bud' rules='rows' frame='box'><thead><tr>
				   <th style='text-align:left; font-weight:bold;' align='left'><input name='pvno' id='pvno' type='hidden' value='<?php echo $pvno; ?>' />
Voucher Item</th>
				   <th style='text-align:left; font-weight:bold;' align='left'>Budget Description</th>
				   <th style='text-align:left; font-weight:bold;' align='left' width='5%' nowrap='nowrap'>Amount</th>
				   </tr></thead><tbody><?php 
	$sql =  mysqli_query($con, "select * from `budget_votebooktb` where voucher_pvno = '".$pvno."'");
	while($row =  mysqli_fetch_array($sql, 3 )){ ?>
		<tr>
	   <td style='text-align:left;' align='left'><?php echo get_folio_name($row['voucher_folio_code']); ?></td>
	   <td style='text-align:left;' align='left'><?php echo get_folio_name($row['budget_folio_code']); ?></td>
	   <td style='text-align:left;' align='left' width='5%' nowrap='nowrap'><?php echo $row['amount']; ?></td>
	   </tr><?php
	} ?>
	</tbody></table><center>
        <button value="REVERSE BUDGET ENTRIES" type="button" onClick="swapcontent('process_voucher_edit', 'budget');" class="l-btn">REVERSE BUDGET ENTRIES</button></center>
    <?php } ?>
<?php
	} //end of if found
  else
    echo "<b><font color='red'>The PV Number does not match any record. No record to display</font></b>";
?>
</td>
</tr>
	
</table>
</form>
<strong>DOCUMENT UPLOAD</strong>
<form action="scriptfile_m.php?contentvar=pvfileupload" method="post" enctype="multipart/form-data" target="upload_target2" onsubmit="startUpload3();" class="formx" id="editmail" name="editmail" >
	<input type="hidden" name="pvno3" id="pvno3" value="<?php echo $pvno;?>" />
<table width='100%' border='1' cellspacing='0' cellpadding='2' id='vou_bud' rules='rows' frame='box'>
<tr id="mupdate_r">
            <td height="33" align="left" valign="middle"><strong>Document:</strong></td>
            <td height="33" align="left" valign="middle">
                 <span class="formx2" >
                        
                     <p id="f1_upload_form2" align="left"><br/>
                         <!--<label class="labelx" for="myfile2">File: --> 
                              <input name="myfile2" id="myfile2" type="file" size="20" />
                         <!--</label>-->
                         <label>                         
                             <input type="submit" name="submitBtn2" class="sbtn2 buttonx" value="Upload" />
                         </label>
                     </p>
                     <p id="f1_upload_process2">Loading...<br/><img src="images/ajax-loader.gif" /><br/></p>
          <iframe id="upload_target2" name="upload_target2" src="#" style="width:0;height:0;border:0px solid #fff;"></iframe>
                        <!--</form>-->
                 </span>
            </td>
          </tr>
</table>
</form>
</body>
</html>