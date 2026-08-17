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
{   
	var divid="#"+cv;
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
	var str;

  if(cv=='display_voucher_process')
  {
		$.post(url,{contentvar:cv,pvno:v,r_vals:a},function(data){
		$(divid).html('').show(); 
		$("#display").html(data).show();
		$("#display").window("open");
		$("#roll").html('').show();
		});
  }
  if(cv=='process_voucher')
  {
		$.post(url,$("#frmpro").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); 
		$("#roll").html('').show();
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
					  //if($('#fileno').val()=='' && a!='auto')
					   //{ alert('Enter Staff File Number');$(divid).html('').show();exit;}
						//alert('here '+ cv + " "+ v + " id:"+a); $(divid).html('').show(); exit;
						//$.post(url,$("#staff_form").serialize()+"&contentvar="+cv+"&action="+v+"&id_val="+a,function(data){
										
						var pData=jQuery.parseJSON(data); 
						alert(pData.s_detail);
						var p=jQuery.parseJSON(pData.s_detail);
						
						$("#sch_code").val(p.sch_code); $("#dept_code").val(p.dept_code); $("#dept_name").val(p.dept_name);
						$("#category").val(p.category); $("#r_id_edit").val(p.r_id); 
						
						//$(divid).html('').show();
					//	});
				  } //for edit purpose
				 });
				 document.location.reload();
  } 

  if(cv=='load_dept_account')
  {
	  var category=$("#funddept_head").val();
		$.post(url,{contentvar:cv,category:category},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }

  if(cv=='load_category')
  {
	  var fundcenter=$("#fundsource").val();
		$.post(url,{contentvar:cv,fundcenter:fundcenter},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }

  if(cv=='load_items_code')
  {
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
      
		$.post(url,{contentvar:cv,fundcenter:fundcenter, deptcode:deptcode, category:category},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }

} //end of swapcontent

$(document).ready(function() { //parent.jQuery.colorbox.close(); 
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
	        <h2>Voucher List</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
	<form name="frm" id="frm" method="post" action="<?=$_SERVER['PHP_SELF'];?>?r_val=<?=$_REQUEST['r_val']?>">
	<p>&nbsp;</p>
	<h3>APPLY FILTER</h3><hr>
	<div class="row">
		<div class="col-sm-6">
			<label><strong>Enter Date Range: </strong></label> <input type="date" id="dFrm" name="dFrm" value="" class="form-control">
		- 
			<input type="date" id="dTo" name="dTo" value="" class="form-control"> 
			<input type="submit" id="btn" name="btn" value="DISPLAY" class="btn">
		</div><hr>
	</div>
</form>
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Voucher Processing</i></h3> -->
			<div id="display2"></div>
            <p>
               
                <?php $r=@strtolower($r_vals); ?>
                       <!-- end of pending tab-->
                       <div style="padding:10px"> <!-- QUERIED tab  -->
                        <?php
		    if(isset($_POST['dFrm']) && $_POST['dFrm']!='' && isset($_POST['dTo']) && $_POST['dTo']!=''){
			$dFrm = $_POST['dFrm'];
			$dTo = $_POST['dTo'];
			echo "<h2>VOUCHER LIST FROM {$dFrm} TO {$dTo}</h2>";
						   $r=@strtolower($r_vals);							
							   $yr = date('Y');
							   $sql="SELECT * FROM vouchertb WHERE voucher_date BETWEEN '{$dFrm}' AND '{$dTo}' order by voucher_date desc"; //where  prepared_by='".$login_id."'  or dept_code='".$_SESSION['userunit']."'
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table id='MyTable' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box' style='font-size:10px;'>
							<thead> 
							<tr style='border:solid 1px #000; background-color:#f2f2f2'>
								<th style='font-size:10px;'>SNO</th>
								<th>PROCESS NO</th>
								<th>PV NO</th>
								<th>GROSS (NET)</th>
								<th>PAYEE</th>
								<th>PAYEE ACCOUNT</th>
								<!--th>PAYEE BANK</th-->
								<th>DATE</th>
								<th style='font-size:10px;'>CHECKED</th>
								<th style='font-size:10px;'>CERTIFIED</th>
								<th style='font-size:10px;'>CONTROLLED</th>
								<th style='font-size:10px;'>AUDITED</th>
								<th style='font-size:10px;'>PAID</th>
								<th style='font-size:10px;'>FACULTY/ CENTER</th>
								<th>ACTION</th>
							</tr></thead><tbody>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
							while($rs_v=@mysqli_fetch_array($res_v))
							{
								++$sn;
								$pvno=$rs_v['pvno']; 
								$rs_v['pvno_paid']==''?$pvno_paid=$rs_v['pre_pvno']:$pvno_paid=$rs_v['pvno_paid'];
								$p=base64_encode($pvno);
								$payee_name=$rs_v['payee_name'];
								$payee_acct_no=$rs_v['payee_acct_no'];
								$payee_bank_name=$rs_v['payee_bank_name'];
								$voucher_date=$rs_v['voucher_date'];
								$rs_v['dept_vou']=='' ? $dept_vou='Central' : $dept_vou=get_unit_name('', $rs_v['dept_vou']);

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
								//$pvGross=explode('_', $pvno);
								$pv = explode('_', $pvno);
								if(count($pv) <= 1){
									$net = number_format($rs_v['amount_paid'], 2);
								}
											//$pvGross=$pvGross[0];
								$gross = read_gross($pvno);
								
								//$tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p'>VIEW</a></tr>";

								$tb.="<tr>
								<td>$sn</td>
								<td style='font-size:10px;'>$pvno</td>
								<td>$pvno_paid</td>
								<td>".$gross." (".$net.")</td>
								<td>$payee_name</td>
								<td style='font-size:10px;'>{$payee_bank_name}<br>{$payee_acct_no}</td>
								<!--td>$payee_bank_name</td-->
								<td>".date('d/m/Y',strtotime($voucher_date))."</td>
								<td style='font-size:10px;'><a href='#' title='".$checked_date."'>".$checked_action."</a></td>
								<td style='font-size:10px;'><a href='#' title='".$authorized_date."'>".$authorized_action."</a></td>
								<td style='font-size:10px;'><a href='#' title='".$controlled_date."'>".$controlled_action."</a></td>
								<td style='font-size:10px;'><a href='#' title='".$audit_date."'>".$audit_action."</a></td>
								<td style='font-size:10px;'><a href='#' title='".$paid_date."'>".$paid_action."</a></td>
								<td style='font-size:10px;'>{$dept_vou}</td>
								<td nowrap><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a>";
								
								//if($r=="prepared officer" or $r=="budget officer" and ($checked == '' or $checked_action == 'Queried') and ($prepared == $login_id or $r=="super admin" or $r=="administrator"))
								$tb.="  | <a class='iframe' href='voucher_resubmit.php?p={$p}&r_val={$_REQUEST['r_val']}' >EDIT</a>";
								//$tb.="  | <a href='x.php?pv=$pvno' target='_blank' >AUDIT</a> | <a href='x.php?pv2=$pvno' target='_blank' >CONTROL</a>";
								$tb.=" | <a href='voucher_reprocess.php?p={$p}' target='_blank'>PROCESS</a>";
								$tb.="</td></tr>";
								//else $tb.=" | <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">PROCESS</a></td></tr>";

							} //end of while
							
							$tb.="</tbody></table>"; echo $tb;
						   }
						   else
						    echo "<font color='red'><b>No record to display</b></font>";
		    }
						?>
                       </div> <!-- END OF QUERY VOUCHER -->
                       
                   
				   
				   <div id="display" class="easyui-window" title="Voucher Process" data-options="
                   modal:true,
                   closed:true,
                   iconCls:'icon-save',
			onResize:function(){
				$(this).window('hcenter');
			}" style="width:600px;height:auto;padding:10px; display:none"> </div>
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