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
$rv=base64_decode($_REQUEST['r_val']);

if(strtolower($rv) == "cash officer") $role_cap="TREASURY";
else $role_cap = $rv;

//$role_cap = base64_decode($_REQUEST['r_val']);

?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<?php include("required_jQuery_files.php");
include "function.php";?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="include/colorbox.css">
<script type="text/javascript" src="include/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="include/jquery.dataTables.min.css">

<script type="text/javascript" src="include/jquery.dataTables.min.js"></script>
<!--[if IE 6]><link href="default_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->
<script>

function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
	var str;

  if(cv=='save_pre_pvno') //start unit
  {
	  var pv_id = v;
	  var pvno=$("#schpvno"+pv_id).val();
	  var ptype=$("#schType").val();
	  var pyear=$("#schYear").val();
          /*if(pvno=='') {
                    alert("Enter PV No.");
                    exit;
          }*/
          $.post(url,{contentvar:cv, pvno:pvno, id:pv_id, type:ptype, year:pyear, typeSub:$("#schTypeSub").val()},function(data){
                    $("#roll").html('').show();
                    $("#schdiv"+pv_id).html(data).show();
                    swapcontent('getLastPV');
          });
  }

  if(cv=='getLastPV') //start unit
  {
          $.post(url,{contentvar:cv, type:$("#schType").val(), year:$("#schYear").val(), typeSub:$("#schTypeSub").val()},function(data){
            $("#roll").html('').show();
            let yr = $("#schYear").val().substring(2, 4);
            $(".pre_pvyear").html(yr);
            $(".pre_pvtype").html($("#schType").val()+$("#schTypeSub").val());
            $("#schLastPV").html(data).show();
          });
  }//end of unit
} //end of swapcontent

$(document).ready(function() { //parent.jQuery.colorbox.close();
    $(".paginate_button").click(function(){
        let yr = $("#schYear").val().substring(2, 4);
        $(".pre_pvyear").html(yr);
        $(".pre_pvtype").html($("#schType").val());
    });
    $(".iframe").colorbox({iframe:true, width:"53%", height:"100%"});
    $('#MyTable').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                //to select and search from grid
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
    $('#MyTable2').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                //to select and search from grid
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
    $('#MyTable3').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
                //to select and search from grid
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );

                column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option value="'+d+'">'+d+'</option>' )
                } );
            } );
        }
    } );
} );
 </script>
 <style>
 tr:nth-child(even) {background-color: #f2f2f2;}
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
	        <h2>Voucher Schedule (Treasury)</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->

        <div id="tooplate_content">

        	<div class="content_box">

<div class="content" style="width: 1200px;">
	        <form name="frm" id="frm" method="post" action="<?=$_SERVER['PHP_SELF'];?>?r_val=<?=$_REQUEST['r_val'];?>">
                    <p>&nbsp;</p>
                    <h3>APPLY FILTER</h3><hr>
                    <div class="row">
                            <div class="col-sm-6">
                                <label><strong>Enter Date Range: </strong></label> <input type="date" id="dFrm" name="dFrm" value="" class="form-control">
                              -
                                        <input type="date" id="dTo" name="dTo" value="" class="form-control">
                                        <input type="submit" id="btn" name="btn" value="SEARCH BY DATE RAISED" class="btn">
                                        <input type="submit" id="btnX" name="btnX" value="SEARCH BY DATE AUDITED" class="btn">
                              </div><hr>
                    </div>

                <!--<div class="title"><h2>Recent Updates</h2></div>
                            <h3><i>Voucher Processing</i></h3> -->
                <div id="display2"></div>
                    <p>

                    <?php $r=@strtolower($r_vals); ?>
                    <!-- end of pending tab-->
                    <div style="padding:10px"> <!-- QUERIED tab  -->
                           <?php
                              $dFrm = $_POST['dFrm'];
                              $dTo = $_POST['dTo'];
                              $type = $_POST['schType']??'TSA';
                              $postYear = date('y', strtotime($_POST['schYear']."-01-01"));
                              $r=strtolower($r_vals);

                              $mq=mysqli_query($con, "SELECT jvcode FROM journal_code_user WHERE fileno='{$login_id}'");
                              $facUsercheck=mysqli_num_rows($mq);
                                $fac="'X'";
                                while($fcode=mysqli_fetch_array($mq, 3)){
                                    $fac .= ", '{$fcode[0]}'";
                                }
                                //echo $facUsercheck;
                              if(isset($_POST['dFrm']) && $_POST['dFrm']!='' && isset($_POST['dTo']) && $_POST['dTo']!='' && isset($_POST['btn'])){
                                        echo "<h2>LIST OF VOUCHER RAISED BETWEEN {$dFrm} AND {$dTo}</h2>";

                                        if($r=="prepared officer") {
                                            $sql="SELECT * FROM vouchertb WHERE (paid_action='Queried' OR pre_pvno = '' OR pre_pvno Is Null) AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}' AND (paid_action='' OR paid_action Is Null) AND pvno NOT LIKE '%\_%' AND dept_vou IN ({$fac}) order by voucher_date desc";
                                        }else {
                                            $sql="SELECT * FROM vouchertb WHERE (paid_action='Queried' OR pre_pvno = '' OR pre_pvno Is Null) AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}' AND (paid_action='' OR paid_action Is Null) AND pvno NOT LIKE '%\_%' order by voucher_date desc";
                                        }
                              }elseif(isset($_POST['dFrm']) && $_POST['dFrm']!='' && isset($_POST['dTo']) && $_POST['dTo']!='' && isset($_POST['btnX'])){
                                        echo "<h2>LIST OF VOUCHER AUDITED BETWEEN {$dFrm} AND {$dTo}</h2>";

                                        if($r=="prepared officer") {
                                            $sql="SELECT * FROM vouchertb WHERE (pre_pvno = '' OR pre_pvno Is Null) AND audit_date BETWEEN '{$dFrm}' AND '{$dTo}' AND (paid_action='Queried' OR paid_action='' OR paid_action Is Null) AND pvno NOT LIKE '%\_%' AND dept_vou IN ({$fac}) order by audit_date desc";
                                        }else{
                                            $sql="SELECT * FROM vouchertb WHERE (pre_pvno = '' OR pre_pvno Is Null) AND audit_date BETWEEN '{$dFrm}' AND '{$dTo}' AND (paid_action='Queried' OR paid_action='' OR paid_action Is Null) AND pvno NOT LIKE '%\_%' order by audit_date desc";
                                        }
                              }
                              ?>
                    <hr><div>
                              <b>PV TYPE:
                              <select id="schType" name="schType">
                                        <option value="<?=$type?>" selected><?=$type?></option>
                                    <?php if($r=="prepared officer" && $facUsercheck > 0){ ?>
                                        <option value="TSA">TSA</option>
                                    <?php }else{ ?>
                                        <option value="TSA">TSA</option>
                                        <option value="CBNTET">CBNTET</option>
                                        <option value="GOH">GOH</option>
                                        <option value="GCAP">GCAP</option>
                                        <option value="GPER">GPER</option>
                                        <option value="NEEDS">NEEDS</option>
                                    <?php } ?>
                              </select>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;SUB TYPE:
                              <select id="schTypeSub" name="schTypeSub">
                                    <?php if($r=="prepared officer" && $facUsercheck > 0){ ?>
                                        <option value="F">TSAF (Faculty)</option>
                                    <?php }else{ ?>
                                        <option value="" selected>None</option>
                                        <option value="F">TSAF (Faculty)</option>
                                        <option value="D">TSAD (Department)</option>
                                        <option value="C">TSAC (Centre)</option>
                                        <option value="U">TSAU (Unit)</option>
                                        <option value="BUILD">CBNTET(BUILD)</option>
                                        <option value="CONF">CBNTET(CONF)</option>
                                    <?php } ?>
                              </select>
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;YEAR: </b>
                              <select id="schYear" name="schYear">
                                        <option value="<?=date('Y')?>" selected><?=date('Y')?></option>
                                        <?php for($i=2024; $i<=date('Y'); $i++) echo "<option value='{$i}'>{$i}</option>"; ?>
                              </select>
                              <input type="button" id="bbt" value="GET LAST PVNO" onclick="swapcontent('getLastPV');">
                              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="schLastPV"></span>
                    </div><hr>
        </form>
                              <?php
                              //echo $sql;
                              $res_v=@mysqli_query($con, $sql);
                              $sn=0;
                              $tb="<table id='MyTable' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
                              <thead>
                              <tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><!--th>PROCESS NO</th--><th>PV NO</th><th>ACTION</th><th>GROSS (NET)</th><th>PAYEE</th><!--th>PAYEE ACCT NO.</th--><th>PAYEE ACCOUNT</th><th>AUDIT DATE</th><th>CHECKED</th><th>CERTIFIED</th><th>CONTROLLED</th><th>AUDITED</th><th>PAID</th></tr></thead><tbody>";
                              if(@mysqli_num_rows($res_v)>=1)
                              {
                                        while($rs_v=@mysqli_fetch_array($res_v))
                                        {
                                                  ++$sn;
                                                  $pvno=$rs_v['pvno'];
                                                  $pvno_paid=$rs_v['pvno_paid'];
                                                  $pre_pvno=$rs_v['pre_pvno'];
                                                  $pre_pvno_paid=$rs_v['pre_pvyear']."/".$rs_v['pre_pvtype'].$rs_v['pre_pvserial'];
                                                  $p=base64_encode($pvno);
                                                  $payee_name=$rs_v['payee_name'];
                                                  $payee_acct_no=$rs_v['payee_acct_no'];
                                                  $payee_bank_name=$rs_v['payee_bank_name'];
                                                  $voucher_date=$rs_v['voucher_date'];

                                                  $prepared=$rs_v['prepared_by']; 	$prepared_date=date('d-m-Y', strtotime($rs_v['date_prepared']));

                                                  $checked=$rs_v['checked_by'];	           $checked_date=date('d-m-Y', strtotime($rs_v['date_checked']));
                                                  $checked_action=$rs_v['checked_action']!=''?$rs_v['checked_action']."<br>".$checked_date:'';

                                                  $authorized=$rs_v['authorized_by'];	              $authorized_date=date('d-m-Y', strtotime($rs_v['date_authorized']));
                                                  $authorized_action=$rs_v['authorized_action']!=''?$rs_v['authorized_action']."<br>".$authorized_date:'';

                                                  $controlled=$rs_v['controlled_by'];	              $controlled_date=date('d-m-Y', strtotime($rs_v['date_controlled']));
                                                  $controlled_action=$rs_v['controlled_action']!=''?$rs_v['controlled_action']."<br>".$controlled_date:'';

                                                  $audited=$rs_v['audit_by'];		          $audit_date=date('d-m-Y', strtotime($rs_v['audit_date']));
                                                  $audit_action=$rs_v['audit_action']!=''?$rs_v['audit_action']."<br>".$audit_date:'';

                                                  $paid=$rs_v['paid_by'];		          $paid_date=date('d-m-Y', strtotime($rs_v['date_paid']));
                                                  $paid_action=$rs_v['paid_action']!=''?$rs_v['paid_action']."<br>".$paid_date:'';

                                                  $net = number_format($rs_v['amount_approved'], 2);
                                                  $pv = explode('_', $pvno);
                                                  if(count($pv) <= 1){
                                                    $net = number_format($rs_v['amount_paid'], 2);
                                                    }
                                                  $gross = read_gross($pvno);             $yr = $postYear; //date('y'); //date('y', strtotime($prepared_date));
                                                  if(date('d/m/Y',strtotime($audit_date))=="01/01/1970") $au_date = '';
                                                  else $au_date = date('d/m/Y',strtotime($audit_date));
                                                  $tb.="<tr><td>$sn</td><!--td>$pvno</td-->
                                                  <td><div id='schdiv{$rs_v['id']}'>$pre_pvno</div></td>
                                                  <td><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a>
                                                  <input type='hidden' id='schid{$rs_v['id']}' name='schid[]' value='{$rs_v['id']}'>
                                                  | <span class='pre_pvyear'>{$yr}</span>/<span class='pre_pvtype'>{$type}</span><input type='text' placeholder='PV SERIAL NO' id='schpvno{$rs_v['id']}' name='schpvno[]' value='' size='15'>
                                                  <input type='button' id='schbtn{$rs_v['id']}' name='schbtn[]' value='OK' onclick='swapcontent(\"save_pre_pvno\", \"{$rs_v['id']}\");'>
                                                  </td>
                                                  <td>".$gross." (".$net.")</td>
                                                  <td>$payee_name</td>
                                                  <!--td>$payee_acct_no</td-->
                                                  <td>$payee_bank_name<br>$payee_acct_no</td><td>{$au_date}</td>
                                                  <td><a href='#' title='".$checked_date."'>".$checked_action."</a></td>
                                                  <td><a href='#' title='".$authorized_date."'>".$authorized_action."</a></td>
                                                  <td><a href='#' title='".$controlled_date."'>".$controlled_action."</a></td>
                                                  <td><a href='#' title='".$audit_date."'>".$audit_action."</a></td>
                                                  <td><a href='#' title='".$paid_date."'>".$paid_action."</a></td>
                                                  ";

                                                  //if($r=="prepared officer" or $r=="budget officer" and ($checked == '' or $checked_action == 'Queried') and ($prepared == $login_id or $r=="super admin" or $r=="administrator"))
                                                  //$tb.="";
                                                  //$tb.="  | <a class='iframe' href='voucher_resubmit.php?p=$p' >RE-SUBMIT</a>";
                                                  //$tb.="  | <a href='x.php?pv=$pvno' target='_blank' >AUDIT</a> | <a href='x.php?pv2=$pvno' target='_blank' >CONTROL</a>";
                                                  $tb.="</tr>";
                                                  //else $tb.=" | <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">PROCESS</a></td></tr>";

                                        } //end of while

                                        $tb.="</tbody></table>"; echo $tb;
                              }
                              else
                              echo "<font color='red'><b>No record to display</b></font>";
                          ?>
                    </div> <!-- END OF QUERY VOUCHER -->

		<div id="display" class="easyui-window" title="Voucher Process" data-options="
                    modal:true,
                    closed:true,
                    iconCls:'icon-save',
			onResize:function(){
				$(this).window('hcenter');
			}" style="width:600px;height:auto;padding:10px; display:none">
                    </div>
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
