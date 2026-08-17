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

<p>
  <!--<table align='center'><tr><td rowspan='2'><img src="<?php echo $val_str[1];?>" style='float:left' width='50' height='50'/></td><td><center><b><?php echo strtoupper($val_str[0]);?></b></center></tr><tr><td><b><center>PAYMENT VOUCHER</center></b></td></tr></table>-->
  
</p>
<table width="100%" border="0" align="center">
  <tr>
    <td width="5%" colspan="3"><center><img src="<?php echo $val_str[1];?>" style='float:center' width='100' height='80'/>
      <h3><?php echo strtoupper($val_str[0]);?></h3>
    <h2><strong>PAYMENT VOUCHER</strong></h2><hr></center></td>
  </tr>
  <tr>
    <td colspan="3"><table width="100%" border="0" align="center">
      <tr>
        <td align="left" valign="top"><strong>Faculty/Dept</strong>: <?php echo @read_voucher_vote_code($rs_v['pvno']); ?></td>
        <td width="48%" align="left" valign="top"><strong>Batch No</strong>: <?php echo $rs_v['batchno'];?></td>
      </tr>
      <tr>
        <td align="left" valign="top"><strong>Account to be debited</strong>: <?php  echo @get_voucher_folio_code($rs_v['pvno'], 'Title'); /*echo @get_account_name($rs_v['dept_acctcode'], false);*/ ?></td>
        <td align="left" valign="top"><strong>PV No.</strong>: <?php echo $rs_v['pvno_paid']; //$bursary->get_any_value("pvno", "transtb", "pvno", $rs_v['pvno']); ?></td>
      </tr>
      <tr>
        <td align="left" valign="top"><strong>Payee</strong>: <?php echo strtoupper($rs_v['payee_name']);?> </td>
        <td align="left" valign="top"><strong>Code</strong>: <?php echo @get_voucher_folio_code($rs_v['pvno'], 'Code'); ?></td>
      </tr>
      <tr>
        <td align="left" valign="top"><strong>Payee Account:</strong> <?php echo strtoupper($rs_v['payee_acct_no']);?></td>
        <td align="left" valign="top"><strong>File/Phone No</strong>: <?php echo $rs_v['fileno'];?></td>
      </tr>
      <tr>
        <td align="left" valign="top"><strong>Address</strong>: <?php echo $rs_v['payee_address'];?></td>
        <?php $pay_month=@date('F',@strtotime($rs_v['voucher_date'])); $pay_year=@date('Y',strtotime($rs_v['voucher_date']));?>
        <td align="left" valign="top"><strong>Payee Bank:</strong> <?php echo strtoupper($rs_v['payee_bank_name']);?></td>
      </tr>
      <tr>
        <td align="left" valign="top"><strong>TIN</strong>: <?php echo $rs_v['payee_tin_number'];?></td>
        <td align="left" valign="top"><strong>Month</strong>: <?php echo "$pay_month, $pay_year";?></td>
      </tr>
      <tr>
        <?php //echo $bursary->get_any_value("paybatch", "transtb", "pvno", $rs_v['pvno_paid']); ?>
        <td align="left" valign="top"><strong>Gross</strong>: <?php echo @read_gross($rs_v['pvno']);?></td>
        <td align="left" valign="top"><strong>Payment Date</strong>: <?php echo $rs_v['date_paid']; /*@date('d/m/Y',strtotime($rs_v['date_paid']));*/ ?></td>
      </tr>
      <?php if($rs_v['controlled_action']=='Approved') { ?>
      <tr style="color:#F00; background:#CCC">
        <td align="left" valign="top"><strong>Voucher Committed By</strong>: <strong><u>
          <?php echo strtoupper(@get_staff_name($rs_v['controlled_by'])). " (".date('d/m/Y',strtotime($rs_v['date_controlled'])).")"; ?>
        </u></strong></td>
        <td align="left" valign="top"><img src="pictures/<?php echo strtoupper($rs_v['controlled_by'])."_sign.jpg"; ?>" width="100" /></td>
      </tr> <?php } ?>
      <?php
			   	if($rs_v['memo_id'] != ''){ ?>
      <tr>
        <td colspan="2" align="left" valign="top" bgcolor="#E7E7E7"><?php
			   	
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
        <td align="left" valign="top">&nbsp;</td>
        <td align="left" valign="top">&nbsp;</td>
      </tr>
      <tr>
        <td colspan="2"><table width="100%" border="1" cellpadding="0" cellspacing="0">
          <tr>
            <td width="21%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>DATE</strong></div></td>
            <td height="26" colspan="2" bgcolor="#EAEAEA"><div align="center"><strong>DETAIL DESCRIPTION OF SERVICE(S) OR GOOD(S)</strong></div></td>
            <td width="20%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>AMOUNT</strong></div></td>
          </tr>
          <tr>
            <td height="26" align="center"><?php echo @date('d/m/Y',strtotime($rs_v['voucher_date']));?></td>
            <td height="26" colspan="2" align="center"><?php echo $rs_v['description'];?></td>
            <td height="26" align="center"><?php echo number_format($rs_v['amount_paid'],2);?></td>
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
			$exp = explode('.', number_format($rs_v['amount_paid'],2,'.',''));
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
            <td height="26" align="center"><b><?php echo number_format($rs_v['amount_paid'],2);?></b></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td valign="top"><table width="89%" border="0">
          <tr>
            <td width="25%" rowspan="2">Prepared by:</td>
            <td width="75%"><u><?php echo strtoupper(@get_staff_name($rs_v['prepared_by'])); ?></u></td>
          </tr>
          <tr>
            <td><img src="pictures/<?php echo strtoupper($rs_v['prepared_by'])."_sign.jpg"; ?>" width="100" /><br /> <?php echo date('d/m/Y',strtotime($rs_v['date_prepared'])); ?><br />
              (Signature and Date)<br /></td>
          </tr>
        </table></td>
        <td valign="top"><table width="94%" border="0">
          <tr>
            <td width="25%" rowspan="2">Checked by:</td>
            <td width="75%"><u><?php if($rs_v['checked_action']!='') echo strtoupper(@get_staff_name($rs_v['checked_by'])); ?></u><br />
              </td>
          </tr>
          <tr>
            <td><img src="pictures/<?php echo strtoupper($rs_v['checked_by'])."_sign.jpg"; ?>" width="100" /><br />
              <?php if($rs_v['checked_action']!='') echo date('d/m/Y',strtotime($rs_v['date_checked'])); ?><br />
              (Signature and Date)<br />
              <u>
              <strong>
              <?php if($rs_v['checked_action']=='Approved') echo $rs_v['checked_action']; else  echo '<font color="#FF0000">'.$rs_v['checked_action'].'</font>'; ?>
              </strong>              </u></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td colspan="2"><!--<table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="48%" valign="top"><table width="97%" border="0">
              <tr>
                <td><div align="center"><strong>CERTIFICATE</strong></div></td>
                </tr>
              <tr>
                <td align="justify">I certify that the above amount is correct and was incurred under the relevant contract, financial authority or other regulations quoted; that the services have been duly performed and that the rate/price charged is according to regulations/contract fair and reasonable.</td>
                </tr>
              <tr>
                <td><div align="center"><u><?php if($rs_v['authorized_action']=='Approved') echo strtoupper(@get_staff_name($rs_v['authorized_by'])); ?></u><br />
                  (Officer Authorising Expenditure)
                  </div></td>
                </tr>
              <tr>
                <td><div align="center"><u><?php if($rs_v['authorized_action']=='Approved') echo strtoupper(@get_staff_rank($rs_v['authorized_by'])); ?></u></div></td>
                </tr>
              <tr>
                <td><div align="center">
                
                <br />    
                    <img src="pictures/<?php echo strtoupper($rs_v['authorized_by'])."_sign.jpg"; ?>" width="100" height="15" /><br /><?php if($rs_v['authorized_action']=='Approved') echo date('d/m/Y',strtotime($rs_v['date_authorized'])); ?><br />
                    Signature and Date
                    <p>
                      <u>
                      <strong>
                      <?php if($rs_v['authorized_action']=='Approved') echo "AUTHORIZED"; ?>
                      </strong>                      </u></p>
                    </div></td>
                </tr>
              </table></td>
            <td width="52%" valign="top"><table width="94%" border="0">
              <tr>
                <td><div align="center"><strong><br />
                  <u><?php if($rs_v['controlled_action']=='Approved') echo strtoupper(@get_staff_name($rs_v['controlled_by'])); ?></u><br />
                  </strong>(Officer Commiting Voucher)</div></td>
                </tr>
              <tr>
                <td>&nbsp;</td>
                </tr>
              <tr>
                <td><div align="center"><u><?php if($rs_v['controlled_action']=='Approved') echo strtoupper(@get_staff_rank($rs_v['controlled_by'])); ?></u><br />
                  (Designation)</div></td>
                </tr>
              <tr>
                <td></td>
                </tr>
              <tr>
                <td><div align="center"><img src="pictures/<?php echo strtoupper($rs_v['controlled_by'])."_sign.jpg"; ?>" width="100" height="15" /><br />
                  <?php if($rs_v['controlled_action']=='Approved') echo date('d/m/Y',strtotime($rs_v['date_controlled'])); ?><br />
                  Signature and Date
                  <br />
                    <u>
                    <strong>
                    <?php if($rs_v['controlled_action']=='Approved') echo "COMMITED"; ?>
                    </strong>                    </u>                  
                  </div></td>
                </tr>
              </table></td>
          </tr>
          <tr>
            <td width="48%" valign="top"><table width="97%" border="0">
              <tr>
                <td><div align="center"><u>
                  <?php if($rs_v['authorized_action2']=='Approved') echo strtoupper(@get_staff_name($rs_v['authorized_by2'])); ?>
                  </u><br />
                  (Final Officer Authorising Expenditure) </div></td>
              </tr>
              <tr>
                <td><div align="center"><u>
                  <?php if($rs_v['authorized_action2']=='Approved') echo strtoupper(@get_staff_rank($rs_v['authorized_by2'])); ?>
                </u></div></td>
              </tr>
              <tr>
                <td><div align="center"> <br />
                  <img src="pictures/<?php echo strtoupper($rs_v['authorized_by2'])."_sign.jpg"; ?>" alt="" width="100" height="15" /><br />
                  <?php if($rs_v['authorized_action2']=='Approved') echo date('d/m/Y',strtotime($rs_v['date_authorized2'])); ?>
                  <br />
                  Signature and Date
                  <p> <u> <strong>
                    <?php if($rs_v['authorized_action2']=='Approved') echo "FINAL AUTHORIZED"; ?>
                  </strong></u></p>
                </div></td>
              </tr>
            </table></td>
            <td  valign="top"><table width="94%" border="0">
              <tr>
                <td><div align="center"><strong><br />
                  <u><?php if($rs_v['audit_action']=='Approved') echo "<font color='red'>".strtoupper(@get_staff_name($rs_v['audit_by']))."</font>"; ?></u><br />
                  </strong>(Auditor)</div></td>
                </tr>
              <tr>
                <td></td>
                </tr>
              <tr>
                <td><div align="center"><u><?php if($rs_v['audit_action']=='Approved') echo "<font color='red'>".strtoupper(@get_staff_rank($rs_v['audit_by']))."</font>"; ?></u><br />
                  (Designation)</div></td>
                </tr>
              <tr>
                <td></td>
                </tr>
              <tr>
                <td><div align="center">
                  <img src="pictures/<?php echo strtoupper($rs_v['audit_by'])."_sign.jpg"; ?>" width="100" height="15" /><br />
                    <?php if($rs_v['audit_action']=='Approved') echo "<font color='red'>".date('d/m/Y',strtotime($rs_v['audit_date']))."</font>"; ?><br />
                    Signature and Date
                  <br /><u>
                    <strong>
                    <?php if($rs_v['audit_action']=='Approved') echo "<font color='red'>CERTIFIED</font>"; ?>
                    </strong>                  </u>
                </div></td>
                </tr>
              </table></td>
          </tr>
          </table>
          _______________________________ -->
          <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
          <tr>
            <td width="48%" rowspan="2" valign="top"><table width="97%" border="0">
              <tr>
                <td><div align="center"><strong>CERTIFICATE</strong></div></td>
                </tr>
              <tr>
                <td align="justify">I certify that the above amount is correct and was incurred under the relevant contract, financial authority or other regulations quoted; that the services have been duly performed and that the rate/price charged is according to regulations/contract fair and reasonable.</td>
                </tr>
              <tr>
                <td><div align="center"><u><?php if($rs_v['authorized_action']!='') echo strtoupper(@get_staff_name($rs_v['authorized_by'])); ?></u><br />
                  (Officer Authorising Expenditure)
                  </div></td>
                </tr>
              <tr>
                <td><div align="center"><u><?php if($rs_v['authorized_action']!='') echo strtoupper(@get_staff_rank($rs_v['authorized_by'])); ?></u></div></td>
                </tr>
              <tr>
                <td><div align="center">
                
                <br />    
                    <img src="pictures/<?php echo strtoupper($rs_v['authorized_by'])."_sign.jpg"; ?>" width="100" /><br /><?php if($rs_v['authorized_action']!='') echo date('d/m/Y',strtotime($rs_v['date_authorized'])); ?><br />
                    Signature and Date
                    <p>
                      <u>
                      <strong>
<?php if($rs_v['authorized_action']=='Approved')  echo "AUTHORIZED"; else  echo '<font color="#FF0000">'.$rs_v['authorized_action'].'</font>'; ?>

                     
                      </strong></u></p>
                    </div></td>
                </tr>
              </table></td>
            <td width="52%" valign="top"><table width="97%" border="0">
              <tr>
                <td><div align="center"><u>
                  <?php if($rs_v['authorized_action2']!='') echo strtoupper(@get_staff_name($rs_v['authorized_by2'])); ?>
                  </u><br />
                  (Officer Controlling Expenditure) </div></td>
              </tr>
              <tr>
                <td><div align="center"><u>
                  <?php if($rs_v['authorized_action2']!='') echo strtoupper(@get_staff_rank($rs_v['authorized_by2'])); ?>
                </u></div></td>
              </tr>
              <tr>
                <td><div align="center"> <br />
                  <img src="pictures/<?php echo strtoupper($rs_v['authorized_by2'])."_sign.jpg"; ?>" alt="" width="100" /><br />
                  <?php if($rs_v['authorized_action2']!='') echo date('d/m/Y',strtotime($rs_v['date_authorized2'])); ?>
                  <br />
                  Signature and Date
                  <p> <u> <strong>
<?php if($rs_v['authorized_action2']=='Approved')  echo "FINAL AUTHORIZED"; else  echo '<font color="#FF0000">'.$rs_v['authorized2_action'].'</font>'; ?>
                  </strong></u></p>
                </div></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td  valign="top"><table width="94%" border="0">
              <tr>
                <td><div align="center"><strong><br />
                  <u><?php if($rs_v['audit_action']!='') echo "<font color='red'>".strtoupper(@get_staff_name($rs_v['audit_by']))."</font>"; ?></u><br />
                  </strong>(Auditor)</div></td>
                </tr>
              <tr>
                <td></td>
                </tr>
              <tr>
                <td><div align="center"><u><?php if($rs_v['audit_action']!='') echo "<font color='red'>".strtoupper(@get_staff_rank($rs_v['audit_by']))."</font>"; ?></u><br />
                  (Designation)</div></td>
                </tr>
              <tr>
                <td></td>
                </tr>
              <tr>
                <td><div align="center">
                  <img src="pictures/<?php echo strtoupper($rs_v['audit_by'])."_sign.jpg"; ?>" width="100" /><br />
                    <?php if($rs_v['audit_action']!='') echo "<font color='red'>".date('d/m/Y',strtotime($rs_v['audit_date']))."</font>"; ?><br />
                    Signature and Date
                  <br /><u>
                    <strong>
                    <?php if($rs_v['audit_action']=='Approved') echo "<font color='red'>CERTIFIED</font>"; else echo $rs_v['audit_action']; ?>
                    </strong>                  </u>
                </div></td>
                </tr>
              </table></td>
          </tr>
          </table>
          </td>
        </tr>
     <!-- <tr>
        <td colspan="2"><strong>RECEIPT:</strong></td>
        </tr>
      <tr>
        <td colspan="2">Received the sum of: <b><?php echo $amountInWords;?></b></td>
        </tr>
      <tr>
        <td valign="top"><table width="97%" border="0">
          <tr>
            <td><div align="left">Cash: --------------------------------------------------------<br />
            </div></td>
          </tr>
          <tr>
            <td><div align="left">Cheque No: --------------------------------------------------</div></td>
          </tr>
          <tr>
            <td><div align="left">Date: <?php echo date('d/m/Y',strtotime($rs_v['date_paid'])); ?></div></td>
          </tr>
        </table></td>
        <td valign="top"><table width="97%" border="0">
          <tr>
            <td><div align="left">Full Name: <?php echo "<u>".strtoupper($rs_v['payee_name'])."</u>"; ?><br />
            </div></td>
          </tr>
          <tr>
            <td><div align="left">Signature: ---------------------------------------------------------</div></td>
          </tr>
          </table></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    </table>
	
	</td>
  </tr>
	-->
</table>
<?php 		        $res_tax=@mysqli_query($con, "select v.*, f.folio_code from vouchertb v INNER JOIN voucher_folio_codetb f ON v.pvno=f.pvno where v.pvno like '".$pvno."_%'");
	if( mysqli_num_rows($res_tax) > 0){
?>
<table width="100%" border="1" cellpadding="0" cellspacing="0">
          <tr>
            <td width="21%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>DATE</strong></div></td>
            <td height="26" colspan="2" bgcolor="#EAEAEA"><div align="center"><strong>ITEM DESCRIPTION</strong></div></td>
            <td height="26" colspan="2" bgcolor="#EAEAEA"><div align="center"><strong>ACCOUNT DESCRIPTION</strong></div></td>
            <td width="20%" height="26" bgcolor="#EAEAEA"><div align="center"><strong>AMOUNT</strong></div></td>
          </tr>
          <?php //echo $pvno;
				while($rs_tax=@mysqli_fetch_array($res_tax))
				{
					
		 ?>
          <tr>
            <td height="26" align="center"><?php echo @date('d/m/Y',strtotime($rs_v['voucher_date']));?></td>
            <td height="26" colspan="2" align="center"><?php echo $rs_tax['description']; ?></td>
            <td height="26" colspan="2" align="center"><?php echo @get_folio_name($rs_tax['folio_code']); ?></td>
            <td height="26" align="center">(<?php echo number_format($rs_tax['amount_paid'],2); ?>)</td>
          </tr>
          <?php } //end of while ?>
</table>
<?php
	}
?>
<?php 
	$sql =  mysqli_query($con, "select * from `budget_votebooktb` where voucher_pvno = '".$pvno."'");
	if( mysqli_num_rows($sql) > 0){
	?>
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
	</tbody></table>
    <?php } ?>
<p align="center"><a href="javascript:window.print();">Print</a></p>
<?php
	} //end of if found
  else
    echo "<b><font color='red'>The PV Number does not match any record. No record to display</font></b>";
?>
</td>
</tr>
</table>
</body>
</html>