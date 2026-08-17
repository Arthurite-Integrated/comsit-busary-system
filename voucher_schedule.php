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

	require_once("myclass_m.php");
	$bursary=new myclass_m();

//$role_cap = base64_decode($_REQUEST['r_val']);

?>
<!DOCTYPE html>
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
<link rel="stylesheet" type="text/css" href="include/colorbox.css">
<script type="text/javascript" src="include/jquery.colorbox.js"></script> 
<link rel="stylesheet" type="text/css" href="include/jquery.dataTables.min.css">
 
<script type="text/javascript" src="include/jquery.dataTables.min.js"></script>

<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
	var str;

	if(cv=='display_schedule_voucher')
	{
		$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
		$('#display_schedule_voucher').html(data).show();
		$('#display2').html('').show();
		$('#roll').html('').show();
		});
		
	} //end of display_schedule_voucher

	if(cv=='process_voucher') //start putme_login
	{
		//$('#cheque_no{$pvno}').val(), $('#pvno_paid{$pvno}').val(), $('#opt{$pvno}').val(), '{$r_vals}', $('#comment{$pvno}').val()
		////alert(v+a+b+c+d+e+f+g); exit;
		if(v=='' || a=='' || b=='' || c=='' || g==''){
			alert("Ensure all neccessary fields are entered.");
			exit;
		}
		$.post(url, {contentvar:cv, pvno:v, cheque_no:a, pvno_paid:b, opt:c, r_vals:d, comment:e, pay_date:f, acctcode:g}, function(data){  
			$("#display2").html(data).show(); 
			$("#roll").html('').show();
			document.location.reload();
		//refresh();
		});
	}//end of putme_login


	if(cv=='schedule_voucher')
	{
		$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
		$('#display2').html(data).show();
		$('#roll').html('').show();
		});
		
	} //end of schedule_voucher
  
} //end of swapcontent


$(document).ready(function() { 
	//parent.jQuery.colorbox. (); 
	$(".iframe").colorbox({iframe:true, width:"53%", height:"100%",
	onClosed: function () {
		////window.location.reload();
	}
});
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
} );
 </script>
 <style>
 tr:nth-child(even) {background-color: #f2f2f2;}
 </style></head>
<body class="subpage">

<div id="tooplate_wrapper" style="width:98%">

	<!--div id="tooplate_sidebar">
	<?php //include_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main" style="width:98%">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	      <h2>Post Payment Voucher</h2>
                <p>Posting of payment voucher(s) after successful payment.</p>
        </div> <!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
        <h3><i>Schedule Vouchers for Payment</i></h3> -->
        <p> 
        <form enctype="multipart/form-data" id="frmpro" name="frmpro">
		  <!--table border="0">
		    <tr>
		      <th width="40%">Audit Start Date
              <br />	            <input type="date" name="start_date" id="start_date"/></th>
		      <th width="60%"><div align="center">End Date<br />
	            <input type="date" name="end_date" id="end_date"/>
	            <br />
		      </div></th>
	        
	        <?php $r=@strtolower($r_vals);
	if($r=="super admin" or $r=="cash officer"){ ?>
		   
		      <td colspan="2" height="33"><div align="">&nbsp;<br><input class="btn" type="button" onclick="swapcontent('display_schedule_voucher');" id="btn" value=" FETCH "></div></td>
	        
	        <?php } ?></tr>
          </table><hr-->
	<div id="display_schedule_voucher"><?php $sql="SELECT * FROM vouchertb WHERE (checked_by!='' OR checked_by Is Not Null) AND (controlled_by!='' OR controlled_by Is Not Null) AND (authorized_by!='' OR authorized_by Is Not Null) and (authorized_by2!='' OR authorized_by2 Is Not Null) and (audit_by!='' OR audit_by Is Not Null) AND (paid_by='' OR paid_by Is Null) AND audit_action='Approved' AND (paid_action='' OR paid_action Is Null) AND  (YEAR(voucher_date) >= YEAR(now())-1)  ORDER BY audit_date DESC LIMIT 5000";
     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     echo "<table width='98%' id='MyTable' class='display; align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
     <thead> 
     <tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>NARRATION</th><th>PAYEE</th><th>PAYEE ACCT.</th><th>DATE</th><th>GROSS (NET)</th><th>PAYMENT DATE</th><th>FUNDING ACCOUNT</th><th>PV. NO.</th><th>BATCH NO.</th><th>COMMENT</th><th>ACTION</th><th>&nbsp;</th></tr></thead><tbody>";
  if(@mysqli_num_rows($res_v)>=1)
   {
          while($rs_v=@mysqli_fetch_array($res_v))
           {
                ++$sn;
                $pvno=$rs_v['pvno']; 
	      $pvno_paid=$rs_v['pvno_paid'];
                $desc=$rs_v['description'];
                $p=base64_encode($pvno);	
	      $r_id = $rs_v['id'];
                $payee_name=$rs_v['payee_name'];
                $payee_acct_no=$rs_v['payee_acct_no'];
                $payee_bank_name=$rs_v['payee_bank_name'];
                $voucher_date=$rs_v['voucher_date'];
                $net = number_format($rs_v['amount_approved'], 2);
                     $pv = explode('_', $pvno);
                    $res_ds=@mysqli_query($con, "select amount_approved from vouchertb where pvno='".$pv[0]."'");
                    while($rs_ds=@mysqli_fetch_array($res_ds)) $amnt_app = $rs_ds[0];
                    $gross = read_gross($pvno);
                    //$gross=number_format($amnt_app, 2);
                
		$prepared=$rs_v['prepared_by']; 		$prepared_date=$rs_v['date_prepared'];
		$checked=$rs_v['checked_by']; 		$checked_action=$rs_v['checked_action'];	
		$checked_date=$rs_v['date_checked'];		$checked_remark=$rs_v['checked_remark'];
		$authorized=$rs_v['authorized_by'];		$authorized_action=$rs_v['authorized_action'];	
		$authorized_date=$rs_v['date_authorized'];	$authorized_remark=$rs_v['authorized_remark'];
		$controlled=$rs_v['controlled_by'];		$controlled_action=$rs_v['controlled_action'];	
		$controlled_date=$rs_v['date_controlled'];	$controlled_remark=$rs_v['controlled_remark'];
		$audited=$rs_v['audit_by'];			$audit_action=$rs_v['audit_action'];	
		$audit_date=$rs_v['date_audited'];		$audit_remark=$rs_v['audit_remark'];
		$paid=$rs_v['paid_by'];			$paid_action=$rs_v['paid_action'];	
		$paid_date=$rs_v['date_paid'];		$paid_remark=$rs_v['paid_remark'];
		$pvnox = str_replace('/', '', $pvno);

                echo "<tr><td>$sn</td><td style='font-size:10px;'>$desc</td><td>$payee_name</td><td>$payee_acct_no ($payee_bank_name)</td><td>".date('d/m/Y',strtotime($voucher_date))."</td>
                <td>".$gross." (".$net.")</td>
	      <td><input type='date' name='pay_date[{$sn}]' id='pay_date{$pvnox}' value='".date('Y-m-d')."' ></td>
	      <td>";
	      ?>
	      <select name="acctcode[<?=$sn;?>]" id="acctcode<?=$pvnox;?>" style="width:150px" class="txt" >
	      	<option selected="selected" value="">---</option>
		<?php 
		$rx=@mysqli_query($con, "select distinct *  from bank_accounttb where status='Active' order by acctname");
		while ($rcourse=@mysqli_fetch_array($rx))
		{
			$scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
			$bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
			$acctname=@$rcourse['acctname'];
			echo "<option value='$pcode'>$scourse <=> ($pcode)</option>";   
		} 
		
	    echo "</select></td>
                <td><input type='hidden' name='pvno[{$sn}]' id='pvno{$pvnox}' value='{$pvno}' >
	      <input type='text' name='pvno_paid[{$sn}]' id='pvno_paid{$pvnox}' value='' ></td>
                <td><input type='text' name='cheque_no[{$sn}]' id='cheque_no{$pvnox}' value='' ></td>
                <td><textarea name='comment[{$sn}]' id='comment{$pvnox}' cols='10' rows='3' ></textarea></td>
                <td><select name='opt[{$sn}]' id='opt{$pvnox}'>
	      <option selected value='Approved'>Pay</option>
	      <!--option value='Not Approved'>Not Approved</option-->
	      <option value='Queried'>Queried</option>
       		</select></td>";
                     echo "<td nowrap><a class='iframe' href='voucher_report.php?p=$p&rv=$r' >VIEW</a> | "; ?>
		 <input type='button' name='cmdpro' id='cmdpro' value='Process' onclick="swapcontent('process_voucher', $('#pvno<?=str_replace('/', '', $pvno);?>').val(), $('#cheque_no<?=str_replace('/', '', $pvno);?>').val(), $('#pvno_paid<?=str_replace('/', '', $pvno);?>').val(), $('#opt<?=str_replace('/', '', $pvno);?>').val(), '<?=$r_vals;?>', $('#comment<?=str_replace('/', '', $pvno);?>').val(), $('#pay_date<?=str_replace('/', '', $pvno);?>').val(), $('#acctcode<?=str_replace('/', '', $pvno);?>').val());" class='btn'/>
		 <?php 
           } //end of while
           
           	echo "</tbody></table>"; 
	}
	  ?></div>
	 <?php $r=@strtolower($r_vals);
	if($r=="super admin" or $r=="cash officer"){ ?> 
	  <hr>
	  <center><input type='button' name='cmdpro' id='cmdpro' value='Process for Payment' onclick="swapcontent('process_voucher_batch');" class='btn'/>
	  <hr>
	  <?php } ?>
	  <div id='process_voucher_batch'></center>
	<div id="display" class="easyui-window" title="Voucher Process" data-options="
                   modal:true,
                   closed:true,
                   iconCls:'icon-save',
			onResize:function(){
				$(this).window('hcenter');
			}" style="width:600px;height:auto;padding:10px; display:none"> </div>
            </p>
		</div>
         <div id="display2"></div>
        <div id="roll"></div>
        </form>
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