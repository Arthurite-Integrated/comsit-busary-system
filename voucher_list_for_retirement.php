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
	        <h2>Voucher List</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Voucher Processing</i></h3> -->
			<div id="display2"></div>
            <p>
               
                <?php $r=@strtolower($r_vals); ?>
                       <!-- end of pending tab-->
                       <div style="padding:10px"> <!-- QUERIED tab  -->
                        <?php
                              $r=@strtolower($r_vals);
                              $fact=$_REQUEST['fact'];
                              $paid=$_REQUEST['paid'];
                              $advance=$_REQUEST['advance'];
                              $ryear=$_REQUEST['pyear2x'];
		                        $login_id=@$_SESSION['login_id'];
                              if($paid=='Paid') {
                                        if($advance == '3087') $sql="SELECT t.id AS tid, t.pvno AS tpvno, t.transdate, t.amount as tamount, v.*, f.title, vf.folio_code AS vffolio FROM (((transtb t INNER JOIN vouchertb v ON t.pvno=v.pvno_paid) INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(t.transdate)= '{$ryear}' AND (v.dept_vou='{$fact}' OR v.dept_vou='') AND vf.folio_code LIKE '09-001-3087' ORDER BY voucher_date ASC";
                                        
                                        elseif($advance == 'No') $sql="SELECT t.id AS tid, t.pvno AS tpvno, t.transdate, t.amount as tamount, v.*, f.title, vf.folio_code AS vffolio FROM (((transtb t INNER JOIN vouchertb v ON t.pvno=v.pvno_paid) INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(t.transdate)= '{$ryear}' AND v.dept_vou='{$fact}' AND vf.folio_code NOT LIKE '09-001-3087' ORDER BY voucher_date ASC";

                                        else $sql="SELECT t.id AS tid, t.pvno AS tpvno, t.transdate, t.amount as tamount, v.*, f.title, vf.folio_code AS vffolio FROM (((transtb t INNER JOIN vouchertb v ON t.pvno=v.pvno_paid) INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(t.transdate)= '{$ryear}' AND v.dept_vou='{$fact}' ORDER BY voucher_date ASC";
                              }
                              elseif($paid=='No') {
                                        if($advance == '3087') $sql="SELECT v.*, f.title, vf.folio_code AS vffolio FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(v.voucher_date)= '{$ryear}' AND v.dept_vou='{$fact}' AND vf.folio_code LIKE '09-001-3087' AND (v.paid_action = '' OR v.paid_action Is Null) ORDER BY voucher_date ASC";

                                        elseif($advance == 'No') $sql="SELECT v.*, f.title, vf.folio_code AS vffolio FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(v.voucher_date)= '{$ryear}' AND v.dept_vou='{$fact}' AND vf.folio_code NOT LIKE '09-001-3087' AND (v.paid_action = '' OR v.paid_action Is Null) ORDER BY voucher_date ASC";

                                        else $sql="SELECT v.*, f.title, vf.folio_code AS vffolio FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(v.voucher_date)= '{$ryear}' AND v.dept_vou='{$fact}' AND (v.paid_action = '' OR v.paid_action Is Null) ORDER BY voucher_date ASC";
                              }else{
                                if($advance == '3087') $sql="SELECT v.*, f.title, vf.folio_code AS vffolio FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(v.voucher_date)= '{$ryear}' AND v.dept_vou='{$fact}' AND vf.folio_code LIKE '09-001-3087' ORDER BY voucher_date ASC";

                                elseif($advance == 'No') $sql="SELECT v.*, f.title, vf.folio_code AS vffolio FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(v.voucher_date)= '{$ryear}' AND v.dept_vou='{$fact}' AND vf.folio_code NOT LIKE '09-001-3087' ORDER BY voucher_date ASC";

                                else $sql="SELECT v.*, f.title, vf.folio_code AS vffolio FROM ((vouchertb v INNER JOIN voucher_folio_codetb vf ON v.pvno=vf.pvno) INNER JOIN foliotb f ON vf.folio_code = f.folio_code) WHERE YEAR(v.voucher_date)= '{$ryear}' AND v.dept_vou='{$fact}' ORDER BY voucher_date ASC";
                              }
                              //echo $sql;
                              $res_v=@mysqli_query($con, $sql);
                              $sn=0;
                              $tb="<table id='MyTable' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
                              <thead> 
                              <tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>PROCESS NO</th><th>PV NO</th><th>GROSS (NET)</th><th>PAYEE</th>";
                              $tb.="<th>PAYEE ACCT NO.</th><th>PAYEE BANK</th>";
                              //$tb.="<th>NARRATION</th>";
                              $tb.="<th>DATE</th><th>CHECKED</th><th>CERTIFIED</th><th>CONTROLLED</th><th>AUDITED</th><th>PAID</th><th>ACTION</th></tr></thead><tbody>";
                              //echo $tb; exit;
                              if(@mysqli_num_rows($res_v)>=1)
                              {
                                        while($rs_v=mysqli_fetch_array($res_v, 3))
                                        {
                                                  ++$sn;
                                                  $pvno=$rs_v['pvno']; 
                                                  $pvno_paid=$rs_v['pvno_paid'];
                                                  $p=base64_encode($pvno);
                                                  $tid = $rs_v['tid'];
                                                  $tp=base64_encode($pvno_paid);
                                                  $r_id = $rs_v['id'];
                                                  $fileno = $rs_v['fileno'];
                                                  $payee_name=$rs_v['payee_name'];
                                                  $payee_acct_no=$rs_v['payee_acct_no'];
                                                  $payee_bank_name=$rs_v['payee_bank_name'];
                                                  $voucher_date=$rs_v['voucher_date'];

                                                  $prepared=$rs_v['prepared_by']; 	$prepared_date=$rs_v['date_prepared'];
                                                  $checked=$rs_v['checked_by']; 	$checked_action=$rs_v['checked_action'];	$checked_date=$rs_v['date_checked'];
                                                  $authorized=$rs_v['authorized_by'];	$authorized_action=$rs_v['authorized_action'];	$authorized_date=$rs_v['date_authorized'];
                                                  $controlled=$rs_v['controlled_by'];	$controlled_action=$rs_v['controlled_action'];	$controlled_date=$rs_v['date_controlled'];
                                                  $audited=$rs_v['audit_by'];		$audit_action=$rs_v['audit_action'];		$audit_date=$rs_v['date_audited'];
                                                  $paid=$rs_v['paid_by'];		$paid_action=$rs_v['paid_action'];		$paid_date=$rs_v['date_paid'];
                                                  $net = number_format($rs_v['amount_approved'], 2);
                                                  $gross = read_gross($pvno);
                                                  if($rs_v['retired']=="Yes") $status=" | CLEARED"; else {
                                                        $status=" | <a href='journal_retirement.php?pvno={$tp}&r_val={$_REQUEST['r_val']}&tid={$tid}&rid={$r_id}&ipvno={$p}' target='_blank'>RETIRE</a>";
                                                    }
                                                    //$status = " | <a href='journal_retirement.php?pvno={$tp}&r_val={$_REQUEST['r_val']}&tid={$tid}' target='_blank'>RETIRE</a>";
					
                                                  //$tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p'>VIEW</a></tr>";

                                                            $tb.="<tr><td>$sn</td><td>$pvno</td><td>$pvno_paid</td><td>".$gross." (".$net.")</td><td>$payee_name</td>";
                                                            $tb.="<td>$payee_acct_no</td><td>$payee_bank_name</td>";
                                                            //$tb.="<td>".$rs_v['description']."</td>";
                                                            $tb.="<td>".date('d/m/Y',strtotime($voucher_date))."</td>
                                                            <td><a href='#' title='".$checked_date."'>".$checked_action."</a></td>
                                                            <td><a href='#' title='".$authorized_date."'>".$authorized_action."</a></td>
                                                            <td><a href='#' title='".$controlled_date."'>".$controlled_action."</a></td>
                                                            <td><a href='#' title='".$audit_date."'>".$audit_action."</a></td>
                                                            <td><a href='#' title='".$paid_date."'>".$paid_action."</a></td>
                                                            <td nowrap><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a>";
                                                            
                                                            //if($r=="prepared officer" or $r=="budget officer" and ($checked == '' or $checked_action == 'Queried') and ($prepared == $login_id or $r=="super admin" or $r=="administrator"))
                                                            $tb.="  | <a class='iframe' href='voucher_resubmit.php?p=$p' >RE-SUBMIT</a>{$status}</td></td></tr>";
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