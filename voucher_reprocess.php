<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
{
     echo "<script>location='index.php';</script>";
}
$r_vals=@base64_decode($_REQUEST['r_val']);
$memo_id=@base64_decode($_REQUEST['id']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
$login_id=@$_SESSION['login_id'];
$login_id_base=@base64_encode($login_id);
//$role=@$_SESSION['role'];
$staff_category=@$_SESSION['staff_category'];

require_once ("connect.php");
require_once ("function.php");
require_once ("currency_convert.php");


?>
<!DOCTYPE html>
<html>
<head>
     <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
     <title>
          <?php echo $_SESSION['project_title'];?>
     </title>
     <meta name="keywords" content="" />
     <meta name="description" content="" />
     <style>
		.date1 {
			color: #333333;
			background: #F7F7F7;
			border: 1px solid #CCCCCC;
			height:25px;
			width:200px;
			vertical-align:inherit;
			text-align:inherit;
			padding-left: 1px;
			font-size:12px;
			font-family:Palatino Linotype;
		}

		.delete{
			content: 'X';
			border:outset 1px red;
			border-radius:4px;
			padding:3px;
			cursor:pointer;
		}
	</style>

<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php include("required_jQuery_files.php"); ?>

<link href="tooplate_style.css" rel="stylesheet" type="text/css" />

<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{
     var divid="#"+cv;
     $("#display").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
     var url="scriptfile_a.php";
     var str;

     if(cv=='generate_pvno') //start unit
     {
          if($("#pay_date").val() != ''){
               var d1 = new Date($("#pay_date").val()).getTime();
               var d2 = new Date($("#lockdate").val()).getTime();
               let pay=parseInt(d1);	let lock=parseInt(d2);
               if(pay < lock){
                    alert("Posting is not enabled for the selected date. \nContact Final Account..");
                    $("#roll").html('').show();
                    $(divid).html('').show();
                    exit();
               }
          }
          var pay_date=$("#pay_date").val();
          var vunit=$("#voucher_unit").val();
          if(vunit=="")
          {
               alert('Select the Voucher Unit first');
               $("#pay_date").val('');
               $("#roll").html('').show();
               exit();
          }
          $.post(url,{contentvar:cv,pay_date:pay_date,voucher_unit:vunit},function(data){
               $("#roll").html('').show();
               $(divid).html(data).show();

          });
     }
     if(cv=='voucher_section_reprocess' || cv=='voucher_section_paid')
     {

          if(cv=='voucher_section_paid')
          {
               if($("#pay_date").val()=='' || $("#pvno").val()=='' || $("#paid_by").val()=='')
               {
                    alert("All compulsory fields must be filled before you can proceed");
                    $('#roll').html('').show();
                    exit();
               }
               if($("#pay_date").val() != ''){
                    var d1 = new Date($("#pay_date").val());
                    var d2 = new Date($("#lockdate").val());
                    let pay=parseInt(d1);	let lock=parseInt(d2);
                    if(pay < lock){
                         alert("Posting is not enabled for the selected date. \nContact Final Account.");
                         $("#lockdate").val("");
                         $("#roll").html('').show();
                         $(divid).html('').show();
                         exit();
                    }
               }
          }
          $('#display').html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();

          $.post(url, $("#frmpro").serialize()+"&contentvar="+cv, function(data){
               //alert(data);
               $('#display').html(data).show();
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
                    <h2>Raise Voucher</h2>
                    <p>&nbsp;</p>
               </div>
               <!-- end of content_title_box -->

               <div id="tooplate_content">

                    <div class="content_box">
                         <div class="easyui-panel" title="Re-process Voucher" style="width:1200px">
                              <div style="padding:10px 60px 20px 60px">
                                   <?php
                                   @require_once "myclass_m.php";
                                   @$bursary = new myclass_m();

                                   $pvno=@base64_decode(@$_REQUEST['p']);
                                   //$pvno="4/10/2541/13559085";//"4/07/2656/013541970";

                                   $val_str=explode("***",get_company());
                                   if($pvno=='') {
                                        echo "<font color='red'>
                                        <b>No search criteria provided</b>
                                        </font>";
                                        exit;
                                   }
                                   $res_v= mysqli_query($con, "select * from vouchertb where (pvno='$pvno' or pvno_paid='$pvno')");
                                   $rs_v=@mysqli_fetch_array($res_v);

                                   if(@mysqli_num_rows($res_v)>=1)
                                   {
                                        //header of the voucher


                                        ?>
                                        <form enctype="multipart/form-data" name='frmpro' id='frmpro'>
                                             <table border="0" cellpadding="0" cellspacing="0" class="vch">
                                                  <tr>
                                                       <td colspan="3">
                                                            <input type="hidden" id="pvno" name="pvno" value="<?=$rs_v['pvno']?>" />
                                                            <table width="100%" border="0" align="center">
                                                                 <tr>
                                                                      <td colspan="3">
                                                                           <center>
                                                                                <h2>
                                                                                     <strong>PAYMENT VOUCHER</strong>
                                                                                </h2>
                                                                                <hr>
                                                                           </center>
                                                                      </td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td colspan="3">
                                                                           <table width="100%" border="0" align="center">
                                                                                <tr>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Faculty/Dept</strong>: <?php echo @read_voucher_vote_code($rs_v['pvno']); ?>
                                                                                     </td>
                                                                                     <td width="48%" align="left" valign="top">
                                                                                          <strong>Batch No</strong>: <?php echo $rs_v['batchno'];?>
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Account to be debited</strong>: <?php  echo @get_voucher_folio_code($rs_v['pvno'], 'Title'); ?>
                                                                                     </td>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>PV No.</strong>: <?php echo $rs_v['pvno_paid']; ?>
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Payee</strong>: <?php echo strtoupper($rs_v['payee_name']);?>
                                                                                     </td>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Code</strong>: <?php echo @get_voucher_folio_code($rs_v['pvno'], 'Code'); ?>
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Payee Account:</strong> <?php echo strtoupper($rs_v['payee_acct_no']);?>
                                                                                     </td>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>File/Phone No</strong>: <?php echo $rs_v['fileno'];?>
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Address</strong>: <?php echo $rs_v['payee_address'];?>
                                                                                     </td>
                                                                                     <?php $pay_month=@date('F',@strtotime($rs_v['voucher_date'])); $pay_year=@date('Y',strtotime($rs_v['voucher_date']));?>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Payee Bank:</strong> <?php echo strtoupper($rs_v['payee_bank_name']);?>
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>TIN</strong>: <?php echo $rs_v['payee_tin_number'];?>
                                                                                     </td>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Month</strong>: <?php echo "$pay_month, $pay_year";?>
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Gross</strong>: <?php echo @read_gross($rs_v['pvno']);?>
                                                                                     </td>
                                                                                     <td align="left" valign="top">
                                                                                          <strong>Payment Date</strong>: <?php echo $rs_v['date_paid']; ?>
                                                                                     </td>
                                                                                </tr>
                                                                                <?php if($rs_v['controlled_action']=='Approved') { ?>
                                                                                     <tr style="color:#F00; background:#CCC">
                                                                                          <td align="left" valign="top">
                                                                                               <strong>Voucher Committed By</strong>: <strong>
                                                                                                    <u>
                                                                                                         <?php echo strtoupper(@get_staff_name($rs_v['controlled_by'])). " (".date('d/m/Y',strtotime($rs_v['date_controlled'])).")"; ?>
                                                                                                    </u>
                                                                                               </strong>
                                                                                          </td>
                                                                                          <td align="left" valign="top">
                                                                                               <img src="pictures/<?php echo strtoupper($rs_v['controlled_by'])."_sign.jpg"; ?>" width="100" />
                                                                                          </td>
                                                                                     </tr>
                                                                                <?php } ?>
                                                                                <tr>
                                                                                     <td colspan="2">
                                                                                          <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                                                                               <tr>
                                                                                                    <td width="21%" height="26" bgcolor="#EAEAEA">
                                                                                                         <div align="center">
                                                                                                              <strong>DATE</strong>
                                                                                                         </div>
                                                                                                    </td>
                                                                                                    <td height="26" colspan="2" bgcolor="#EAEAEA">
                                                                                                         <div align="center">
                                                                                                              <strong>DETAIL DESCRIPTION OF SERVICE(S) OR GOOD(S)</strong>
                                                                                                         </div>
                                                                                                    </td>
                                                                                                    <td width="20%" height="26" bgcolor="#EAEAEA">
                                                                                                         <div align="center">
                                                                                                              <strong>AMOUNT</strong>
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td height="26" align="center">
                                                                                                         <?php echo @date('d/m/Y',strtotime($rs_v['voucher_date']));?>
                                                                                                    </td>
                                                                                                    <td height="26" colspan="2" align="center">
                                                                                                         <?php echo $rs_v['description'];?>
                                                                                                    </td>
                                                                                                    <td height="26" align="center">
                                                                                                         <?php echo number_format($rs_v['amount_paid'],2);?>
                                                                                                    </td>
                                                                                               </tr>

                                                                                               <?php $pvno=@$rs_v['pvno'];
                                                                                               $res_tax=@mysqli_query($con, "select * from voucher_taxtb where pvno='$pvno' order by folio_code");
                                                                                               while($rs_tax=@mysqli_fetch_array($res_tax))
                                                                                               {

                                                                                                    ?>
                                                                                                    <tr>
                                                                                                         <td height="26" align="center">&nbsp;</td>
                                                                                                         <td height="26" colspan="2" align="center">
                                                                                                              <?php echo @get_folio_name($rs_tax['folio_code']); ?>
                                                                                                         </td>
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
                                                                                               ?>
                                                                                               <tr>
                                                                                               <td height="26" colspan="2">
                                                                                               <b>Total Amount in Words: <?php echo $amountInWords;?>
                                                                                               </b>
                                                                                               </td>
                                                                                               <td width="18%" height="26">
                                                                                               <div align="right">
                                                                                               <strong>TOTAL (<?php echo "&#8358;"; ?>)</strong>
                                                                                          </div>
                                                                                     </td>
                                                                                     <td height="26" align="center">
                                                                                          <b>
                                                                                               <?php echo number_format($rs_v['amount_paid'],2);?>
                                                                                          </b>
                                                                                     </td>
                                                                                </tr>
                                                                           </table>
                                                                      </td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td valign="top">
                                                                           <table width="89%" border="0">
                                                                                <tr>
                                                                                     <td width="25%" rowspan="2">Prepared by:</td>
                                                                                     <td width="75%">
                                                                                          <u>
                                                                                               <?php echo strtoupper(@get_staff_name($rs_v['prepared_by'])); ?>
                                                                                          </u>
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td>
                                                                                          <img src="pictures/<?php echo strtoupper($rs_v['prepared_by'])."_sign.jpg"; ?>" width="100" />
                                                                                          <br /> <?php echo date('d/m/Y',strtotime($rs_v['date_prepared'])); ?>
                                                                                          <br />
                                                                                          (Signature and Date)<br />
                                                                                     </td>
                                                                                </tr>
                                                                           </table>
                                                                      </td>
                                                                      <td valign="top">
                                                                           <table width="94%" border="0">
                                                                                <tr>
                                                                                     <td width="25%" rowspan="2">Checked by:</td>
                                                                                     <td width="75%">
                                                                                          <u>
                                                                                               <?php if($rs_v['checked_action']!='') echo strtoupper(@get_staff_name($rs_v['checked_by'])); ?>
                                                                                          </u>
                                                                                          <br />
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td>
                                                                                          <img src="pictures/<?php echo strtoupper($rs_v['checked_by'])."_sign.jpg"; ?>" width="100" />
                                                                                          <br />
                                                                                          <?php if($rs_v['checked_action']!='') echo date('d/m/Y',strtotime($rs_v['date_checked'])); ?>
                                                                                          <br />
                                                                                          (Signature and Date)<br />
                                                                                          <u>
                                                                                               <strong>
                                                                                                    <?php if($rs_v['checked_action']=='Approved') echo $rs_v['checked_action']; else  echo '<font color="#FF0000">'.$rs_v['checked_action'].'</font>'; ?>
                                                                                               </strong>
                                                                                          </u>
                                                                                     </td>
                                                                                </tr>
                                                                           </table>
                                                                      </td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td colspan="2">
                                                                           <table width="100%" border="1" align="center" cellpadding="0" cellspacing="0">
                                                                                <tr>
                                                                                     <td width="48%" rowspan="2" valign="top">
                                                                                          <table width="97%" border="0">
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">
                                                                                                              <strong>CERTIFICATE</strong>
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td align="justify">I certify that the above amount is correct and was incurred under the relevant contract, financial authority or other regulations quoted; that the services have been duly performed and that the rate/price charged is according to regulations/contract fair and reasonable.</td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">
                                                                                                              <u>
                                                                                                                   <?php if($rs_v['authorized_action']!='') echo strtoupper(@get_staff_name($rs_v['authorized_by'])); ?>
                                                                                                              </u>
                                                                                                              <br />
                                                                                                              (Officer Authorising Expenditure)
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">
                                                                                                              <u>
                                                                                                                   <?php if($rs_v['authorized_action']!='') echo strtoupper(@get_staff_rank($rs_v['authorized_by'])); ?>
                                                                                                              </u>
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">

                                                                                                              <br />
                                                                                                              <img src="pictures/<?php echo strtoupper($rs_v['authorized_by'])."_sign.jpg"; ?>" width="100" />
                                                                                                              <br />
                                                                                                              <?php if($rs_v['authorized_action']!='') echo date('d/m/Y',strtotime($rs_v['date_authorized'])); ?>
                                                                                                              <br />
                                                                                                              Signature and Date
                                                                                                              <p>
                                                                                                                   <u>
                                                                                                                        <strong>
                                                                                                                             <?php if($rs_v['authorized_action']=='Approved')  echo "AUTHORIZED"; else  echo '<font color="#FF0000">'.$rs_v['authorized_action'].'</font>'; ?>


                                                                                                                        </strong>
                                                                                                                   </u>
                                                                                                              </p>
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                          </table>
                                                                                     </td>
                                                                                     <td width="52%" valign="top">
                                                                                          <table width="97%" border="0">
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">
                                                                                                              <u>
                                                                                                                   <?php if($rs_v['authorized_action2']!='') echo strtoupper(@get_staff_name($rs_v['authorized_by2'])); ?>
                                                                                                              </u>
                                                                                                              <br />
                                                                                                              (Officer Controlling Expenditure)
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">
                                                                                                              <u>
                                                                                                                   <?php if($rs_v['authorized_action2']!='') echo strtoupper(@get_staff_rank($rs_v['authorized_by2'])); ?>
                                                                                                              </u>
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center"> <br />
                                                                                                              <img src="pictures/<?php echo strtoupper($rs_v['authorized_by2'])."_sign.jpg"; ?>" alt="" width="100" />
                                                                                                              <br />
                                                                                                              <?php if($rs_v['authorized_action2']!='') echo date('d/m/Y',strtotime($rs_v['date_authorized2'])); ?>
                                                                                                              <br />
                                                                                                              Signature and Date
                                                                                                              <p>
                                                                                                                   <u>
                                                                                                                        <strong>
                                                                                                                             <?php if($rs_v['authorized_action2']=='Approved')  echo "FINAL AUTHORIZED"; else  echo '<font color="#FF0000">'.$rs_v['authorized2_action'].'</font>'; ?>
                                                                                                                        </strong>
                                                                                                                   </u>
                                                                                                              </p>
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                          </table>
                                                                                     </td>
                                                                                </tr>
                                                                                <tr>
                                                                                     <td  valign="top">
                                                                                          <table width="94%" border="0">
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">
                                                                                                              <strong>
                                                                                                                   <br />
                                                                                                                   <u>
                                                                                                                        <?php if($rs_v['audit_action']!='') echo "<font color='red'>".strtoupper(@get_staff_name($rs_v['audit_by']))."</font>"; ?>
                                                                                                                   </u>
                                                                                                                   <br />
                                                                                                              </strong>
                                                                                                              (Auditor)
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">
                                                                                                              <u>
                                                                                                                   <?php if($rs_v['audit_action']!='') echo "<font color='red'>".strtoupper(@get_staff_rank($rs_v['audit_by']))."</font>"; ?>
                                                                                                              </u>
                                                                                                              <br />
                                                                                                              (Designation)
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                    </td>
                                                                                               </tr>
                                                                                               <tr>
                                                                                                    <td>
                                                                                                         <div align="center">
                                                                                                              <img src="pictures/<?php echo strtoupper($rs_v['audit_by'])."_sign.jpg"; ?>" width="100" />
                                                                                                              <br />
                                                                                                              <?php if($rs_v['audit_action']!='') echo "<font color='red'>".date('d/m/Y',strtotime($rs_v['audit_date']))."</font>"; ?>
                                                                                                              <br />
                                                                                                              Signature and Date
                                                                                                              <br />
                                                                                                              <u>
                                                                                                                   <strong>
                                                                                                                        <?php if($rs_v['audit_action']=='Approved') echo "<font color='red'>CERTIFIED</font>"; else echo $rs_v['audit_action']; ?>
                                                                                                                   </strong>
                                                                                                              </u>
                                                                                                         </div>
                                                                                                    </td>
                                                                                               </tr>
                                                                                          </table>
                                                                                     </td>
                                                                                </tr>
                                                                           </table>
                                                                      </td>
                                                                 </tr>
                                                            </table>
                                                            <?php 		        $res_tax=@mysqli_query($con, "select v.*, f.folio_code from vouchertb v INNER JOIN voucher_folio_codetb f ON v.pvno=f.pvno where v.pvno like '".$pvno."_%'");
                                                            if( mysqli_num_rows($res_tax) > 0){
                                                                 ?>
                                                                 <table width="100%" border="1" cellpadding="0" cellspacing="0">
                                                                      <tr>
                                                                           <td width="21%" height="26" bgcolor="#EAEAEA">
                                                                                <div align="center">
                                                                                     <strong>DATE</strong>
                                                                                </div>
                                                                           </td>
                                                                           <td height="26" colspan="2" bgcolor="#EAEAEA">
                                                                                <div align="center">
                                                                                     <strong>ITEM DESCRIPTION</strong>
                                                                                </div>
                                                                           </td>
                                                                           <td height="26" colspan="2" bgcolor="#EAEAEA">
                                                                                <div align="center">
                                                                                     <strong>ACCOUNT DESCRIPTION</strong>
                                                                                </div>
                                                                           </td>
                                                                           <td width="20%" height="26" bgcolor="#EAEAEA">
                                                                                <div align="center">
                                                                                     <strong>AMOUNT</strong>
                                                                                </div>
                                                                           </td>
                                                                      </tr>
                                                                      <?php //echo $pvno;
                                                                      while($rs_tax=@mysqli_fetch_array($res_tax))
                                                                      {

                                                                           ?>
                                                                           <tr>
                                                                                <td height="26" align="center">
                                                                                     <?php echo @date('d/m/Y',strtotime($rs_v['voucher_date']));?>
                                                                                </td>
                                                                                <td height="26" colspan="2" align="center">
                                                                                     <?php echo $rs_tax['description']; ?>
                                                                                </td>
                                                                                <td height="26" colspan="2" align="center">
                                                                                     <?php echo @get_folio_name($rs_tax['folio_code']); ?>
                                                                                </td>
                                                                                <td height="26" align="center">(<?php echo number_format($rs_tax['amount_paid'],2); ?>)</td>
                                                                           </tr>
                                                                           <?php
                                                                      } //end of while ?>
                                                                 </table>
                                                                 <?php
                                                            }
                                                            ?>
                                                            <?php
                                                            $sql =  mysqli_query($con, "select * from `budget_votebooktb` where voucher_pvno = '".$pvno."'");
                                                            if( mysqli_num_rows($sql) > 0){
                                                                 ?>
                                                                 <table width='100%' border='1' cellspacing='0' cellpadding='2' id='vou_bud' rules='rows' frame='box'>
                                                                      <thead>
                                                                           <tr>
                                                                                <th style='text-align:left; font-weight:bold;' align='left'>
                                                                                     
                                                                                     Voucher Item
                                                                                </th>
                                                                                <th style='text-align:left; font-weight:bold;' align='left'>Budget Description</th>
                                                                                <th style='text-align:left; font-weight:bold;' align='left' width='5%' nowrap='nowrap'>Amount</th>
                                                                           </tr>
                                                                      </thead>
                                                                      <tbody>
                                                                           <?php
                                                                           $sql =  mysqli_query($con, "select * from `budget_votebooktb` where voucher_pvno = '".$pvno."'");
                                                                           while($row =  mysqli_fetch_array($sql, 3 )){ ?>
                                                                                <tr>
                                                                                     <td style='text-align:left;' align='left'>
                                                                                          <?php echo get_folio_name($row['voucher_folio_code']); ?>
                                                                                     </td>
                                                                                     <td style='text-align:left;' align='left'>
                                                                                          <?php echo get_folio_name($row['budget_folio_code']); ?>
                                                                                     </td>
                                                                                     <td style='text-align:left;' align='left' width='5%' nowrap='nowrap'>
                                                                                          <?php echo $row['amount']; ?>
                                                                                     </td>
                                                                                </tr>
                                                                                <?php
                                                                           } ?>
                                                                      </tbody>
                                                                 </table>
                                                            <?php } ?>
                                                            <?php
                                                            $pvno_paid = $rs_v['pvno_paid']!=''?$rs_v['pvno_paid']:$rs_v['pre_pvno'];
                                                            if($pvno_paid!='') {
                                                                 $faccount=$bursary->get_any_value("acctcode", "transtb", "pvno", $pvno_paid);
                                                                 $faccount_name=$bursary->get_any_value("acctname", "bank_accounttb", "acctcode", $faccount);
                                                                 $facct=$faccount==''?$faccount:"{$faccount_name} <=> ({$faccount})";
                                                            }
                                                            ?>
                                                            <div style="background-color:#CCC">
                                                                 <h3>TREASURY PROCESSING</h3>
                                                                 <hr>
                                                                 <table width='98%' align='center'>
                                                                      <tr>
                                                                           <th align='left' width="10%">PAYMENT DATE: </th>
                                                                           <td width="40%">
                                                                                <input type='date' name='pay_date' id='pay_date' style='width: 90%' value="<?=$rs_v['date_paid']?>" />
                                                                           </td>
                                                                           <th align='left' width="10%">FUNDING ACCOUNT: </th>
                                                                           <td width="40%">
                                                                                <select name="acctcode" id="acctcode" class="txt" style="width: 90%" onchange="Xswapcontent('generatePVNo', $('#acctcode').val(), $('#pay_date').val())" >
                                                                                     <option selected="selected" value="<?=$faccount?>">
                                                                                          <?=$facct?>
                                                                                     </option>
                                                                                     <?php
                                                                                     $r=@mysqli_query($con, "SELECT distinct *  from bank_accounttb where status='Active' order by acctcode");
                                                                                     while ($rcourse=@mysqli_fetch_array($r))
                                                                                     {
                                                                                          $scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
                                                                                          $bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
                                                                                          $acctname=@$rcourse['acctname'];
                                                                                          echo "<option value='$pcode'>$scourse <=> ($pcode)</option>";
                                                                                     }
                                                                                     ?>
                                                                                </select>
                                                                                <input type='hidden' name='r_vals' id='r_vals' value='<?php echo $rv; ?>'/>
                                                                           </td>
                                                                      </tr>

                                                                      <tr>
                                                                           <th align='left'>P.V. NO.: </th>
                                                                           <td>
                                                                                <div id='generatePVNo'>
                                                                                     <input type='text' name='pvno_paid' id='pvno_paid' style='width: 90%' value='<?php echo $pvno_paid; ?>'/>
                                                                                </div>
                                                                           </td>
                                                                           <th align="left">BATCH NO:</th>
                                                                           <td>
                                                                                <input type='text' name='batchno' id='batchno' style='width:90%' value="<?=$rs_v['batchno']?>" />
                                                                           </td>
                                                                      </tr>
                                                                      <tr>
                                                                           <th align='left'>COMMENTS</th>
                                                                           <td>
                                                                                <textarea name='comment' id='comment' cols='15' rows='3' style='width:90%'>
                                                                                </textarea>
                                                                           </td>
                                                                           <th align='left'>ACTION</th>
                                                                           <td>
                                                                                <select name='opt' id='opt' style='width:90%'>
                                                                                     <option value='Approved'>Processed</option>
                                                                                     <option value='Queried'>Queried</option>
                                                                                </select>
                                                                           </td>
                                                                      </tr>
                                                                      <tr>
                                                                           <th>
                                                                           </th>
                                                                           <td colspan='2' align='left'>
                                                                                <input type='button' name='cmdpro' id='cmdpro' value='Post Payment' onclick="swapcontent('voucher_section_paid');" class='btn'/>
                                                                           </td>
                                                                      </tr>
                                                                 </table>
                                                                 <br>
                                                                 <div id='process_voucher'></div>
                                                            </div>

                                                       </td>
                                                  </tr>
                                                  <tr>
                                                       <th height="20" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</th>
                                                       <th height="20" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</th>
                                                       <th height="20" align="left" valign="middle" bgcolor="#FFFFFF">&nbsp;</th>
                                                  </tr>
                                                  <tr>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
                                                            <b>Prepared By</b> <br/>
                                                            <select class='txt' style='width:90%' name='prepared_by' id='prepared_by'>
                                                                 <option selected value='<?=$rs_v['prepared_by']?>'>
                                                                      <?=strtoupper(@get_staff_name($rs_v['prepared_by']))?>
                                                                 </option>

                                                                 <?php
                                                                 $res_s= mysqli_query($con, "select fileno, surname, first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') and dept_code='126' order by surname");
                                                                 while($rs_s =  mysqli_fetch_array($res_s))
                                                                 {
                                                                      $fileno=$rs_s['fileno'];
                                                                      $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
                                                                      echo "<option value='$fileno'>$name || $fileno</option>";
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </th>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
                                                            <b>Checked By</b> <br/>
                                                            <select class='txt' style='width:90%' name='checked_by' id='checked_by'>
                                                                 <option selected value='<?=$rs_v['checked_by']?>'>
                                                                      <?=strtoupper(@get_staff_name($rs_v['checked_by']))?>
                                                                 </option>
                                                                 <option value="">Clear Action</option>
                                                                 <?php
                                                                 $res_s= mysqli_query($con, "SELECT s.fileno, surname, first_name, other_name FROM stafftb s INNER JOIN users_roletb u ON s.fileno=u.fileno WHERE s.fileno not in ('Admin','Weathstone','School') AND s.dept_code='126' AND u.role='Checked by Officer' ORDER BY surname");
                                                                 while($rs_s =  mysqli_fetch_array($res_s))
                                                                 {
                                                                      $fileno=$rs_s['fileno'];
                                                                      $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
                                                                      echo "<option value='$fileno'>$name || $fileno</option>";
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </th>
                                                  </tr>
                                                  <tr>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
                                                            <b>Certified By</b> <br/>
                                                            <select class='txt' style='width:90%' name='authorized_by' id='authorized_by'>
                                                                 <option selected value='<?=$rs_v['authorized_by']?>'>
                                                                      <?=strtoupper(@get_staff_name($rs_v['authorized_by']))?>
                                                                 </option>
                                                                 <option value="">Clear Action</option>
                                                                 <?php
                                                                 $res_s= mysqli_query($con, "SELECT s.fileno, surname, first_name, other_name FROM stafftb s INNER JOIN users_roletb u ON s.fileno=u.fileno WHERE s.fileno not in ('Admin','Weathstone','School') AND s.dept_code='126' AND u.role='Authorized Officer' ORDER BY surname");
                                                                 while($rs_s =  mysqli_fetch_array($res_s))
                                                                 {
                                                                      $fileno=$rs_s['fileno'];
                                                                      $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
                                                                      echo "<option value='$fileno'>$name || $fileno</option>";
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </th>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
                                                            <b>Authorized By</b> <br/>
                                                            <select class='txt' style='width:90%' name='authorized_by2' id='authorized_by2'>
                                                                 <option selected value='<?=$rs_v['authorized_by2']?>'>
                                                                      <?=strtoupper(@get_staff_name($rs_v['authorized_by2']))?>
                                                                 </option>
                                                                 <option value="">Clear Action</option>
                                                                 <?php
                                                                 $res_s= mysqli_query($con, "SELECT s.fileno, surname, first_name, other_name FROM stafftb s INNER JOIN users_roletb u ON s.fileno=u.fileno WHERE s.fileno not in ('Admin','Weathstone','School') AND s.dept_code='126' AND u.role='Final Authorized Officer' ORDER BY surname");
                                                                 while($rs_s =  mysqli_fetch_array($res_s))
                                                                 {
                                                                      $fileno=$rs_s['fileno'];
                                                                      $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
                                                                      echo "<option value='$fileno'>$name || $fileno</option>";
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </th>
                                                  </tr>
                                                  <tr>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
                                                            <b>Audited By</b> <br/>
                                                            <select class='txt' style='width:90%' name='audit_by' id='audit_by'>
                                                                 <option value="">Clear Action</option>
                                                                 <option selected value='<?=$rs_v['audit_by']?>'>
                                                                      <?=strtoupper(@get_staff_name($rs_v['audit_by']))?>
                                                                 </option>
                                                                 <?php
                                                                 $res_s= mysqli_query($con, "SELECT s.fileno, surname, first_name, other_name FROM stafftb s INNER JOIN users_roletb u ON s.fileno=u.fileno WHERE s.fileno not in ('Admin','Weathstone','School') AND s.dept_code='126' AND u.role='Auditor' ORDER BY surname");
                                                                 while($rs_s =  mysqli_fetch_array($res_s))
                                                                 {
                                                                      $fileno=$rs_s['fileno'];
                                                                      $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
                                                                      echo "<option value='$fileno'>$name || $fileno</option>";
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </th>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
                                                            <b>Controlled By</b> <br/>
                                                            <select class='txt' style='width:90%' name='controlled_by' id='controlled_by'>
                                                                 <option selected value='<?=$rs_v['controlled_by']?>'>
                                                                      <?=strtoupper(@get_staff_name($rs_v['controlled_by']))?>
                                                                 </option>
                                                                 <?php
                                                                 $res_s= mysqli_query($con, "SELECT s.fileno, surname, first_name, other_name FROM stafftb s INNER JOIN users_roletb u ON s.fileno=u.fileno WHERE s.fileno not in ('Admin','Weathstone','School') AND s.dept_code='126' AND u.role='Budget Officer' ORDER BY surname");
                                                                 while($rs_s =  mysqli_fetch_array($res_s))
                                                                 {
                                                                      $fileno=$rs_s['fileno'];
                                                                      $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
                                                                      echo "<option value='$fileno'>$name || $fileno</option>";
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </th>
                                                  </tr>
                                                  <tr>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">
                                                            <b>Paid By</b> <br/>
                                                            <select class='txt' style='width:90%' name='paid_by' id='paid_by'>
                                                                 <option selected value='<?=$rs_v['paid_by']?>'>
                                                                      <?=strtoupper(@get_staff_name($rs_v['paid_by']))?>
                                                                 </option>
                                                                 <option value="">Clear Action</option>
                                                                 <?php
                                                                 $res_s= mysqli_query($con, "SELECT s.fileno, surname, first_name, other_name FROM stafftb s INNER JOIN users_roletb u ON s.fileno=u.fileno WHERE s.fileno not in ('Admin','Weathstone','School') AND s.dept_code='126' AND u.role='Cash Officer' ORDER BY surname");
                                                                 while($rs_s =  mysqli_fetch_array($res_s))
                                                                 {
                                                                      $fileno=$rs_s['fileno'];
                                                                      $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
                                                                      echo "<option value='$fileno'>$name || $fileno</option>";
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </th>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
                                                       <th height="50" align="left" valign="middle" bgcolor="#F1F1F1">&nbsp;</th>
                                                  </tr>


                                                  <tr>
                                                       <th colspan="3">
                                                            <input type="hidden" id="vamount" name="vamount" value="" />
                                                            <input type="button" class="btn" name="sbtn" id="sbtn" value=" UPDATE " onclick="swapcontent('voucher_section_reprocess', 'save');" />
                                                            <input type="button" class="btn" name="chbtn" id="chsbtn" value=" CLOSE " onclick="window.close();" />
                                                       </th>
                                                  </tr>
                                                  <tr>
                                                       <th colspan="3" align="left" valign="top" id="display" style="text-align:justify;padding:30px;">&nbsp;</th>
                                                  </tr>
                                             </table>
                                        </form>
                                        <?php
                                   } //end of if found
                                   else
                                   echo "<b>
                                   <font color='red'>The PV Number does not match any record. No record to display</font>
                                   </b>";
                                   ?>
                              </div>
                              <!-- end of <div style="padding:10px 60px 20px 60px">-->
                         </div>
                         <!-- end of <div class="easyui-panel" title="New Topic" style="width:400px">-->
                    </div>
                    <!-- end of content box -->

               </div> <!-- end of content tooplate_content-->

          </div> <!-- end of content tooplate_main-->

          <div class="cleaner"></div>
     </div> <!-- end of wrapper tooplate_wrapper-->

     <div id="tooplate_footer_wrapper">
          <?php include_once("footer.php"); ?>
     </div>
     <!-- end of footer  tooplate_footer_wrapper-->

</body>
</html>
