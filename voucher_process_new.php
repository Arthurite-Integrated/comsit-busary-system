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
<!--[if IE 6]><link href="default_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->
<script>

function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
	var str;
	

	if(cv=='login') 
	{
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
		});
  	}//end of putme_login
  
	if(cv=='forget_password') 
	{
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
		});
	}//end of putme_login
	
	if(cv=='main_login') 
	{
		$.post(url,{contentvar:cv,username:v,password:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
	}//end of putme_login
  
	if(cv=='display_voucher_process') 
	{
		$.post(url,{contentvar:cv,pvno:v,r_vals:a},function(data){
		
		//TINY.box.show(data,0,0,0,0);
		//alert (data);
		$(divid).html('').show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#display").html(data).show();
		$("#display").window("open");
		$("#roll").html('').show();
		//$("#display").html(data).show();
		});
  	}//end of putme_login
	if(cv=='display_voucher_processpv') 
          {
		$.post(url,{contentvar:cv,pvno:v,r_vals:a},function(data){
		
		//TINY.box.show(data,0,0,0,0);
		//alert (data);
		$(divid).html('').show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#display").html(data).show();
		$("#display").window("open");
		$("#roll").html('').show();
		//$("#display").html(data).show();
		});
  }//end of putme_login
  if(cv=='generatePVNo') 
  {
	  if(v != ''){
		$.post(url,{contentvar:cv,accountCode:v, pay_date:a},function(data){
			$(divid).html(data).show();
		});
	  }
  }//end of generate pvno
  if(cv=='process_voucher') 
  {
	if($("#opt").val()=="Queried"){
		if($("#comment").val()==''){
			alert("You cannot submit a query without comment. Write a proper description of the query.");
			exit();
		}
	}
	$.post(url,$("#frmpro").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		location.reload();
		<?php //if($rv=="Checked by Officer") echo "location.reload();"; ?>
	//refresh();
	});
  }//end of putme_login
  
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
				 //document.location.reload();
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

$(document).ready(function() { //parent.jQuery.colorbox. (); 
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
	        <h2>Voucher Processing</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
		<div class="content">
		
		<form name="frm" id="frm" method="post" action="<?=$_SERVER['PHP_SELF']."?r_val=".$_REQUEST['r_val'];?>">
			<p>&nbsp;</p>
			<h3>APPLY FILTER [SELECT VOUCHER DATE]</h3><hr>
			<div class="row">
				<div class="col-sm-12">
				<label><strong>Enter Date Range: </strong></label> <input type="date" id="dFrm" name="dFrm" value="" class="form-control">
				- 
				<input type="hidden" id="r_val" name="r_val" value="<?=$_REQUEST['r_val'];?>"> 
				<input type="date" id="dTo" name="dTo" value="" class="form-control"> 
				<input type="submit" id="btn" name="btn" value="DISPLAY" class="btn">
				</div><hr>
				<p style="color: red;">This feature is introduced to this page to optimize the loading time and reduce the time spent on voucher processing. You have the priviledge of displaying vouchers from a specific date you want to process. <br><b>NOTE:</b> All Processed and Queried vouchers can now be viewed on the voucher list.</p>
			</div>
		</form>
<?php

if(isset($_POST['dFrm']) && $_POST['dFrm']!='' && isset($_POST['dTo']) && $_POST['dTo']!=''){
	$dFrm = $_POST['dFrm'];
	$dTo = $_POST['dTo'];
	echo "<h2>VOUCHER LIST FROM {$dFrm} TO {$dTo}</h2>";
	?>
			<div id="display2"></div>
            <p>
                <div class="easyui-tabs" style="width:1300px;height:auto" > <!-- begining of main tab-->
                <?php $r=@strtolower($r_vals); if($r != "budget officer"){ ?>
                       <div title="<?php if(@strtolower($r_vals)=='prepared officer') echo 'Prepared'; else echo 'Pending'; ?>" style="padding:10px">  <!-- pending tab  -->
                         <?php
			$mq=mysqli_query($con, "SELECT jvcode FROM journal_code_user WHERE fileno='{$login_id}'");
			$fac="'X'";
			while($fcode=mysqli_fetch_array($mq, 3)){
				$fac .= ", '{$fcode[0]}'";
			}
			
						 
			$r=@strtolower($r_vals);
			if($r=="super admin"  or $r=="administrator" or $r=="prepared officer")
			$sql="SELECT * from vouchertb where dept_code='".$_SESSION['userunit']."' or prepared_by = '".$login_id."' and checked_action != 'Queried' AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}' order by voucher_date desc";
				
			elseif($r=="super admin" or $r=="checked by officer" or $r=="administrator")
				$sql="SELECT * from vouchertb where (dept_code='{$_SESSION['userunit']}' or prepared_by = '{$login_id}') and (checked_action='Queried' OR checked_action='' OR checked_action Is Null OR authorized_action='Queried') AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}' OR dept_vou IN ({$fac})  order by voucher_date desc";
		
			elseif($r=="authorized officer"){
				/*if($_SESSION['userunit']=='306'){
					$sql="SELECT * from vouchertb where (dept_code='{$_SESSION['userunit']}' OR dept_code='6') and (checked_by !='' OR checked_by Is Not Null) and checked_action='Approved' and (authorized_action='Queried' OR authorized_action='' OR authorized_action Is Null OR controlled_action='Queried') AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}' OR dept_vou != ''  order by voucher_date desc";
				}else{*/
					$sql="SELECT * from vouchertb where (dept_code='{$_SESSION['userunit']}' OR dept_code='6') and (checked_by !='' OR checked_by Is Not Null) and checked_action='Approved' and (authorized_action='Queried' OR authorized_action='' OR authorized_action Is Null OR controlled_action='Queried') AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}'  order by voucher_date desc";
				//}
			}

			elseif($r=="super admin" or $r=="administrator" or $r=="expenditure control")
			$sql="SELECT * from vouchertb where (checked_by!='' OR checked_by Is Not Null) AND checked_action='Approved' and (authorized_by!='' OR authorized_by Is Not Null) and authorized_action!='Approved' and (controlled_action='Queried' OR controlled_action='' OR controlled_action Is Null OR authorized_action2='Queried') AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}'  order by voucher_date desc";

			elseif($r=="super admin" or $r=="final authorized officer")
			$sql="SELECT * from vouchertb where (checked_by!='' OR checked_by Is Not Null) AND checked_action='Approved' and (controlled_by!='' OR controlled_by Is Not Null) AND controlled_action='Approved' and (authorized_by!='' OR authorized_by Is Not Null) AND authorized_action='Approved' AND (authorized_action2='Queried' OR authorized_action2='' OR authorized_action2 Is Null OR audit_action='Queried') AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}'  order by voucher_date desc";
				
			elseif($r=="super admin" or $r=="auditor")
			$sql="SELECT * from vouchertb where (checked_by!='' OR checked_by Is Not Null) AND checked_action='Approved' and (controlled_by!='' OR controlled_by Is Not Null) AND controlled_action='Approved' and (authorized_by!='' OR authorized_by Is Not Null) AND authorized_action='Approved' AND (authorized_by2!='' OR authorized_by2 Is Not Null) AND authorized_action2='Approved' and controlled_action='Approved' and (audit_action='Queried' OR audit_action='' OR audit_action Is Null OR paid_action='Queried') AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}'  order by voucher_date";

			elseif($r=="super admin" or $r=="cash officer" or $r=="final account")
			$sql="SELECT * FROM vouchertb WHERE (paid_by='' OR paid_by Is Null) AND (paid_action='' OR paid_action Is Null) AND voucher_date BETWEEN '{$dFrm}' AND '{$dTo}' order by voucher_date";
				
			$sql;
			$res_v=@mysqli_query($con, $sql);
			$sn=0;
			$tb="<table width='100%' id='MyTable' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
			<thead> 
			<tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>NARRATION</th><!--th>PV NO</th--><th>PAYEE</th><th>PAYEE ACCT NO.</th><!--th>PAYEE BANK</th--><th>DATE</th><th>GROSS (NET)</th><th>CHECKED</th><th>CERTIFIED</th><th>CONTROLLED</th><th>AUDITED</th><th>PAID</th><th>ACTION</th></tr></thead><tbody>";
			if(@mysqli_num_rows($res_v)>=1)
			{
				while($rs_v=@mysqli_fetch_array($res_v))
                                        {
                                                  ++$sn;
                                                  $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
                                                  $desc=$rs_v['description'];
                                                  $p=base64_encode($pvno);	$r_id = $rs_v['id'];
                                                  $payee_name=$rs_v['payee_name'];
                                                  $payee_acct_no=$rs_v['payee_acct_no'];
                                                  $payee_bank_name=$rs_v['payee_bank_name'];
                                                  $voucher_date=$rs_v['voucher_date'];
                                                  $net = number_format($rs_v['amount_approved'], 2);
                                                  $pv = explode('_', $pvno);
					if(count($pv) <= 1){
						$net = number_format($rs_v['amount_paid'], 2);
					}
                                                  $res_ds=@mysqli_query($con, "select amount_approved from vouchertb where pvno='".$pv[0]."'");
                                                  while($rs_ds=@mysqli_fetch_array($res_ds)) $amnt_app = $rs_ds[0];
                                                  $gross = read_gross($pvno);
                                                  //$gross=number_format($amnt_app, 2);
                                                  
                                                  $prepared=$rs_v['prepared_by']; 	$prepared_date=$rs_v['date_prepared'];
                                                  $checked=$rs_v['checked_by']; 			$checked_action=$rs_v['checked_action'];	
                                                  $checked_date=$rs_v['date_checked'];	$checked_remark=$rs_v['checked_remark'];
                                                  $authorized=$rs_v['authorized_by'];	$authorized_action=$rs_v['authorized_action'];	
                                                  $authorized_date=$rs_v['date_authorized'];	$authorized_remark=$rs_v['authorized_remark'];
                                                  $controlled=$rs_v['controlled_by'];		$controlled_action=$rs_v['controlled_action'];	
                                                  $controlled_date=$rs_v['date_controlled'];	$controlled_remark=$rs_v['controlled_remark'];
                                                  $audited=$rs_v['audit_by'];			$audit_action=$rs_v['audit_action'];	
                                                  $audit_date=$rs_v['date_audited'];		$audit_remark=$rs_v['audit_remark'];
                                                  $paid=$rs_v['paid_by'];				$paid_action=$rs_v['paid_action'];	
                                                  $paid_date=$rs_v['date_paid'];			$paid_remark=$rs_v['paid_remark'];
                                        
                                                  $tb.="<tr><td>$sn</td><td style='font-size:10px;'>$desc</td><!--td>$pvno_paid</td--><td>$payee_name</td><td>$payee_acct_no ($payee_bank_name)</td><!--td>$payee_bank_name</td--><td>".date('d/m/Y',strtotime($voucher_date))."</td>
                                                  <td>".$gross." (".$net.")</td>
                                                  <td><a href='#' title='".$checked_date."'>".$checked_action."</a><br><span style='font-size:10px;'>".$checked_remark."</span></td>
                                                  <td><a href='#' title='".$authorized_date."'>".$authorized_action."</a><br><span style='font-size:10px;'>".$authorized_remark."</span></td>
                                                  <td><a href='#' title='".$controlled_date."'>".$controlled_action."</a><br><span style='font-size:10px;'>".$controlled_remark."</span></td>
                                                  <td><a href='#' title='".$audit_date."'>".$audit_action."</a><br><span style='font-size:10px;'>".$audit_remark."</span></td>
                                                  <td><a href='#' title='".$paid_date."'>".$paid_action."</a><br><span style='font-size:10px;'>".$paid_remark."</span></td>";
                                                  if($r=="cash officer" || $r=="final account"){
                                                            $tb.="<td nowrap><a class='iframe' href='voucher_report_y.php?p=$p&rv=$r' >VIEW/PROCESS</a>"; 
                                                  }else{
                                                  $tb.="<td nowrap><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a>";
                                                  if($r=="prepared officer" and ($checked == '' or $checked_action == 'Queried') and ($prepared == $login_id or $r=="super admin" or $r=="administrator"))
                                                  $tb.=" | <a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_entry','delete','$r_id');\">DELETE</a></td></tr>";
                                                  else $tb.=" | <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">PROCESS</a></td></tr>";
                                                  }
                                        } //end of while
                                        
                                        $tb.="</tbody></table>"; echo $tb;
			}
			else
			echo "<font color='red'><b>No pending voucher to process</b></font>";
			?>
                       </div>  
                       <?php } ?>
                   </div> <!-- end of main tab -->
				   
		<div id="display" class="easyui-window" title="Voucher Process" data-options="
			modal:true,
			closed:true,
			iconCls:'icon-save',
			onResize:function(){
				$(this).window('hcenter');
			}" style="width:600px;height:auto;padding:10px; display:none"> </div>
            </p>
	  <?php
}
?>
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