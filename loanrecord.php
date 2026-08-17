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
	@require_once 'myclass_m.php';
	$bursary = new myclass_m();

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
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
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
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='display_voucher_process') //start putme_login
  {
	//  alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
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
   /*if(cv=='display_voucher') //start putme_login
  {
	//  alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,p:v,r_vals:a},function(data){
		
		//TINY.box.show(data,0,0,0,0);
		alert (data);
		$(divid).html('').show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#display").html(data).show();
		$("#display").window("open");
		$("#roll").html('').show();
		});
  }//end of putme_login*/
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
  
 //alert($("form").serialize());
  
 //alert(cv+" "+v+" "+a); exit;
  	// var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			//alert($("#pro_typ").val()); exit;
			//$.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a},function(data){	//a is the id of rec to edit/upd	
			//alert(data);	
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
	        <h2>Loan Records</h2>
                <p><?php echo "&nbsp;"; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Voucher Processing</i></h3> -->
			<div id="display2"></div>
            <p>
                <div class="easyui-tabs" style="width:1200px;height:auto" > <!-- begining of main tab-->
	      <div title="PAID LOAN" style="padding:10px"><div class="table-responsive"> <!-- QUERIED tab  -->
                         <?php
						 
						  $r=@strtolower($r_vals);
						       $sql="SELECT h.*, TRIM(CONCAT(s.surname, ' ', s.first_name, ' ', s.other_name)) AS payee_name, s.phone_no, email, f.title FROM ((hr_loan_apptb h INNER JOIN stafftb s ON h.fileno=s.fileno) INNER JOIN foliotb f ON h.loan_type=f.folio_code) WHERE h.payment_status='Paid' ORDER BY h.entry_date desc";
							   
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table id='MyTable' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
							<thead> 
							<tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>LOAN ID</th><th>DEBTOR</th><th>PHONE NO.</th><th>LOAN TYPE</th><th>DATE</th><th>DURATION</th><th>AMOUNT</th><th>PRINCIPAL</th><th>INTEREST</th><th>INSTALLMENT</th><th>PAID</th><th>BALANCE</th><th>STATUS</th><th>REPAY</th></tr></thead><tbody>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rec=@mysqli_fetch_array($res_v))
								 {
									$loanid = $rec['id'];
									$pvno = $rec['pvno_paid'];
									$loanno = $rec['loan_no'];
									$folio = $rec['loan_type'];
									$amount= $rec['loan_amount'];
									$pdate = $rec['app_date'];
									$pname = $rec['payee_name'];
									$duration=$rec['duration'];
									$start_date=$rec['repay_start_date'];
									$end_date=$rec['repay_end_date'];
									$interest=$rec['interest'];
									$installment=$rec['installment'];
									$rate=$rec['rate'];
									$fileno=$rec['fileno'];
									$principal=$rec['principal'];
									$pstatus=$rec['process_status'];
									$payee_phone=$rec['phone_no'];
									$payee_email=$rec['email'];

									$paid=0;
									$sql =  mysqli_query($con, "SELECT sum(amountpaid) as paid FROM loanrepaymenttb WHERE loanid = '".$loanid."'");
									$amt= mysqli_fetch_array($sql, 3 );	$paid = $amt['paid'];
									$remain = $amount - $paid;
									$repay_link='';
									if($remain > 0){
										$repay_link = "<a href='loanrepayment.php?r_val={$_REQUEST['r_val']}&loanid={$loanno}&fileno={$fileno}' target='_blank'>REPAY</a>";
									}
									 ++$sn;
								 
									 $tb.="<tr><td>$sn</td><td>$loanno</td><td>$pname ($fileno)</td><td>$payee_phone</td><td>".get_folio_name($folio)."</td><td>".date('d/m/Y',strtotime($pdate))."</td><td>".date_format(new DateTime($start_date), 'F, Y')." to ".date_format(new DateTime($end_date), 'F, Y')." (".$duration." Months)</td><td>&#8358;".number_format($amount, 2)."</td><td>&#8358;".number_format($principal, 2)."</td><td>&#8358;".number_format($interest, 2)." (@ ".$rate."%)</td><td>&#8358;".number_format($installment, 2)."</td><td>&#8358;".number_format($paid, 2)."</td><td>&#8358;".number_format($remain, 2)."</td><td>".$pstatus."</td><td>".$repay_link." | <a href='loanrepayment.php?r_val={$_REQUEST['r_val']}&loanid={$loanno}&fileno={$fileno}&page=LOAN HISTORY' target='_blank'>HISTORY</a></td></tr>";

								 } //end of while
								 
								 $tb.="</tbody></table>"; echo $tb;
						    }
							else
						       echo "<font color='red'><b>No record found!</b></font>";
						 ?></div>
                       </div> <!-- END OF QUERY VOUCHER -->
                       <div title="PENDING LOAN" style="padding:10px">  <!-- pending tab  -->
                         <?php
						 
						  $r=@strtolower($r_vals);
						       $sql="SELECT l.*, v.payee_name, v.pvno_paid FROM hr_loan_apptb l INNER JOIN vouchertb v ON l.loan_no = v.pvno WHERE l.process_status = '' or l.process_status is null ORDER BY entry_date desc";
							   
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table id='MyTable' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
							<thead> 
							<tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>LOAN ID</th><th>DEBTOR</th><th>PHONE NO.</th><th>LOAN TYPE</th><th>DATE</th><th>AMOUNT</th><th>PRINCIPAL</th><th>INTEREST</th><th>INSTALLMENT</th><th>DURATION</th><th>BALANCE</th><th>STATUS</th></tr></thead><tbody>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rec=@mysqli_fetch_array($res_v))
								 {
									$loanid = $rec['id'];
									$pvno = $rec['pvno_paid'];
									$loanno = $rec['loan_no'];
									$folio = $rec['loan_type'];
									$amount= $rec['loan_amount'];
									$pdate = $rec['app_date'];
									$pname = $rec['payee_name'];
									$duration=$rec['duration'];
									$start_date=$rec['repay_start_date'];
									$end_date=$rec['repay_end_date'];
									$interest=$rec['interest'];
									$installment=$rec['installment'];
									$rate=$rec['rate'];
									$fileno=$rec['fileno'];
									$principal=$rec['principal'];
									$pstatus=$rec['process_status'];

									$payee_phone=$bursary->get_any_value("phone_no","stafftb","fileno",$fileno);
									$payee_email=$bursary->get_any_value("email","stafftb","fileno",$fileno);
									$paid=0;
									$sql =  mysqli_query($con, "SELECT sum(amountpaid) as paid FROM loanrepaymenttb WHERE loanid = '".$loanid."'");
									$amt= mysqli_fetch_array($sql, 3 );	$paid = $amt['paid'];
									$remain = $amount - $paid;
									
									 ++$sn;
								 
									 $tb.="<tr><td>$sn</td><td>$loanno</td><td>$pname</td><td>$payee_phone</td><td>".get_folio_name($folio)."</td><td>".date('d/m/Y',strtotime($pdate))."</td><td>&#8358;".number_format($amount, 2)."</td><td>&#8358;".number_format($principal, 2)."</td><td>&#8358;".number_format($interest, 2)." (@ ".$rate."%)</td><td>&#8358;".number_format($installment, 2)."</td><td>".date_format(new DateTime($start_date), 'F, Y')." to ".date_format(new DateTime($end_date), 'F, Y')." (".$duration." Months)</td><td>&#8358;".number_format($remain, 2)."</td><td>".$pstatus."</td></tr>";

								 } //end of while
								 
								 $tb.="</tbody></table>"; echo $tb;
						    }
							else
						       echo "<font color='red'><b>No record found!</b></font>";
						 ?>
                       </div>  <!-- end of pending tab-->
                       
                   </div> <!-- end of main tab -->
				   
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