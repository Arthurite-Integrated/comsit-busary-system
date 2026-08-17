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
	

  if(cv=='login') //start putme_login
  {
          $.post(url,{contentvar:cv},function(data){
          TINY.box.show(data,0,0,0,0);$(divid).html('').show();
          $("#roll").html('').show();
          });
  }//end of putme_login
  
  if(cv=='forget_password') //start putme_login
  {
          $.post(url,{contentvar:cv},function(data){
                                                                                                    //alert(data);
          TINY.box.show(data,0,0,0,0);$(divid).html('').show();
          $("#roll").html('').show();
          });
  }//end of putme_login
  
 if(cv=='main_login') //start putme_login
  {
          $.post(url,{contentvar:cv,username:v,password:a},function(data){
          $(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
          $("#roll").html('').show();
          });
  }//end of putme_login
  
  if(cv=='display_voucher_process') //start putme_login
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
  if(cv=='process_voucher') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,$("#frmpro").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		document.location.reload();
		//refresh();
		});
  }//end of putme_login
  
 if(cv=='voucher_section_entry') //start putme_login
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

  if(cv=='load_dept_account') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var category=$("#funddept_head").val();
		$.post(url,{contentvar:cv,category:category},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit


  if(cv=='load_category') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var fundcenter=$("#fundsource").val();
		$.post(url,{contentvar:cv,fundcenter:fundcenter},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit

  if(cv=='load_items_code') //start unit
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
	  var fundcenter=$("#fundsource").val();
	  var deptcode=$("#funddept").val();
	  var category=$("#fundcat").val();
      
		$.post(url,{contentvar:cv,fundcenter:fundcenter, deptcode:deptcode, category:category},function(data){
		$("#roll").html('').show();
		$(divid).html(data).show();
		
		});
  }//end of unit

  if(cv=='save_pre_pvno') //start unit
  {
	  var pv_id = v;
	  var pvno=$("#schpvno"+pv_id).val();
          if(pvno=='') {
                    alert("Enter PV No.");
                    exit;
          }
          $.post(url,{contentvar:cv, pvno:pvno, id:pv_id},function(data){
                    $("#roll").html('').show();
                    $("#schdiv"+pv_id).html(data).show();
                    swapcontent('getLastPV');
          });
  }

  if(cv=='getLastPV') 
  {
          $.post(url,{contentvar:cv, type:$("#schType").val(), year:$("#schYear").val()},function(data){
                    $("#roll").html('').show();
                    $("#schLastPV").html(data).show();
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

<script>
     function sendRequest(requestID, loaderDiv='', recID=''){
          var url="script-fac.php";
          var loader='<img src="images/loader.gif" height="30" alt="loading">Workiing...';

          if(requestID=="assignPVScheddule"){
               $("#"+requestID).html(loader).show();
               $.post(url, $("#frmEdit").serialize()+"&requestID="+requestID, function(response){
                    $("#"+requestID).html(response);
                    location.reload();
               });
          }

          if(requestID=="viewPVScheddule"){
               $("#"+requestID).html(loader).show();
               $.post(url, {requestID:requestID, fileno:loaderDiv, sdate:recID}, function(response){
                    $("#"+requestID).html(response);
               });
          }

          if(requestID=="processVoucher") 
          {
                    if($("#pay_date").val()=='' || $("#acctcode").val()=='' || $("#batch_no").val()==''){
                              alert("1 or more mandatory feild(s) is/are empty!");
                              exit;
                    }
		$.post(url,$("#frmPay").serialize()+"&requestID="+requestID,function(response){ 
			$("#"+requestID).html(response);
			location.reload();
		});
          }

     }
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
	        <h2>Payment Posting</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
            <div class="content" style="width: 1200px;">
		<div id="display2"></div>
                    <p>
               
                    <?php $r=@strtolower($r_vals); ?>
                    <!-- end of pending tab-->
                    <div style="padding:10px"> 
                              <form method='post' id='frmPay' name='frmPay'>
                              <?php
                                        echo "<h2>VOUCHER PAYMENT POSTING</h2>";

                                        $sql="SELECT v.* FROM pv_pay_scheduletb p INNER JOIN vouchertb v ON p.pvid=v.id WHERE p.status = 'Pending' AND (v.pre_pvno != '') AND (v.paid_action='' OR v.paid_action Is Null) order by v.pre_pvtype, v.pre_pvserial ASC";
                                        ?>
                                        <?php
                                        //echo $sql;
                                        $res_v=@mysqli_query($con, $sql);
                                        $sn=0;
                                        $tb="<table id='MyTable' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
                                        <thead> 
                                        <tr style='border:solid 1px #000; background-color:#f2f2f2'><th colspan='2'>S/NO</th><th>PV NO</th><th>PAYEE</th><th>DESCRIPTION</th><th>CODE</th><th>AMOUNT</th><th>BANK</th><th>ACCOUNT</th><th>ACTION</th></tr></thead><tbody>";
                                        if(@mysqli_num_rows($res_v)>=1)
                                        {
                                                  while($rs_v=@mysqli_fetch_array($res_v))
                                                  {
                                                            ++$sn;
                                                            $pvno=$rs_v['pvno']; 
                                                            $pvno_paid=$rs_v['pvno_paid'];
                                                            $pre_pvno_paid=$rs_v['pre_pvno'];
                                                            $p=base64_encode($pvno);
                                                            $payee_name=strtoupper($rs_v['payee_name']);
                                                            $payee_acct_no=$rs_v['payee_acct_no'];
                                                            $payee_bank_name=strtoupper($rs_v['payee_bank_name']);
                                                            $voucher_date=$rs_v['voucher_date'];
                                                            $ds=mysqli_query($con, "SELECT folio_code FROM voucher_folio_codetb WHERE pvno='{$pvno}'");
                                                            if(mysqli_num_rows($ds)==1){
                                                                      $code=get_voucher_folio_code($rs_v['pvno'], 'Code');
                                                                      $desc=strtoupper(get_voucher_folio_code($rs_v['pvno'], 'Title'));
                                                            }else{
                                                                      $code="VARIOUS";
                                                                      $desc="REFUND";
                                                            }

                                                            $net = number_format($rs_v['amount_approved'], 2);
                                                            $gross = read_gross($pvno);             $yr = date('y', strtotime($prepared_date));
                                                            if(date('d/m/Y',strtotime($audit_date))=="01/01/1970") $au_date = '';
                                                            else $au_date = date('d/m/Y',strtotime($audit_date));
                                                            $tb.="<tr><td>$sn</td>
                                                            <td><input type='checkbox' class='checkboxes' id='pv_jv_{$rs_v['id']}' name='pv_jv[]' value='{$rs_v['id']}'></td>
                                                            <td><div id='schdiv{$rs_v['id']}'>$pre_pvno_paid</div></td>
                                                            <td>$payee_name</td>
                                                            <td>$desc</td>
                                                            <td>$code</td>
                                                            <td>".$net."</td>
                                                            <td>$payee_bank_name</td>
                                                            <td>$payee_acct_no</td>
                                                            <td><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a></td>";
                                                            $tb.="</tr>";
                                                  } //end of while
                                                  
                                                  $tb.="</tbody></table>"; echo $tb;
                                                  ?>
                                        <hr>
                                        <div style="background-color:#CCC">
	                                        <h3>CRITERIA SELECTION</h3>
                                                  <hr>
	  	                              <table width='70%' align='center'>
                                                            <tr><th align='left'>PAYMENT DATE<sup style="color:red">*</sup>: </th><td><input type='date' name='pay_date' id='pay_date' style='width: 350px' /></td></tr>
                                                            <tr>
                                                                      <th align='left'>FUNDING ACCOUNT<sup style="color:red">*</sup>: </th>
                                                                      <td>
                                                                                <select name="acctcode" id="acctcode" class="txt" style="width: 350px" onchange="swapcontent('generatePVNo', $('#acctcode').val(), $('#pay_date').val())" >
                                                                                          <option selected="selected" value="">---</option>
                                                                                          <?php $r=@mysqli_query($con, "select distinct *  from bank_accounttb where status='Active' order by acctcode");
                                                                                          while ($rcourse=@mysqli_fetch_array($r))
                                                                                          {
                                                                                                    $scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
                                                                                                    $bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
                                                                                                    $acctname=@$rcourse['acctname'];
                                                                                                    echo "<option value='{$pcode}'>{$scourse} :: {$pcode}</option>";
                                                                                          } ?>
                                                                                </select>
                                                                                <input type='hidden' name='r_vals' id='r_vals' value='<?php echo $rv; ?>'/>
                                                                                <input type='hidden' name='pvno' id='pvno' value='<?php echo $pvno; ?>'/>
                                                                      </td>
                                                            </tr>
                                                            <?php
                                                            $pvno_paid = ''; 
                                                            ?>

                                                            <tr><th align="left">BATCH NO<sup style="color:red">*</sup>:</th><td><input type='text' name='batch_no' id='batch_no' style='width:350px'/></td></tr>
                                                            <tr>
                                                                      <th align='left'>COMMENTS</th>
                                                                      <td>
                                                                                <textarea name='comment' id='comment' cols='15' rows='3' style='width:350px'></textarea>
                                                                      </td>
                                                            </tr>
                                                            <tr><th></th><td colspan='2' align='left'><input type='button' name='cmdpro' id='cmdpro' value=' PAID ' onclick="sendRequest('processVoucher');" class='btn'/></td></tr>
                                                  </table><br>
                                                  <div id='processVoucher'></div>
                                        </div>
                                                  <?php
                                        }
                                        else
                                        echo "<font color='red'><b>No pending voucher awaiting schedule.</b></font>";
                              ?>
                              </form>
                              
                    </div>

                    <hr>
                    <h3><a href="voucher_list_scheduled.php">VIEW VOUCHERS SCHEDULED FOR PAYMENT</a></h3>

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