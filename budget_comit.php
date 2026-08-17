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

?>
<!DOCTYPE html>
<html>
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
$(document).ready(function(){
	 $(".iframe").colorbox({iframe:true, width:"53%", height:"100%"});
});
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_m.php";
	var str;
	 
  if(cv=='display_voucher_processx') //start putme_login
  {
	//  alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,pvno:v,r_vals:a},function(data){
		
		//TINY.box.show(data,0,0,0,0);
		//alert (data);
		$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#display").html(data).show();
		$("#display").window("open");
		$("#roll").html('').show();
		});
  }//end of putme_login
  if(cv=='reverse_budget_comit') //start reverse_budget_comit
  {
	  //alert(cv+" "+v+" "+a+ " " +b);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,pvno:v,r_vals:a,rev_action:b},function(data){
		
		//TINY.box.show(data,0,0,0,0);
		//alert (data);
		$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#display").html(data).show();
			if(b=="undo") 	window.location.reload();
			else			$("#display").window("open");
			$("#roll").html('').show();
		});
  }//end of reverse_budget_comit
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

  if(cv=="send_voucher_to_bursar" ) // send_voucher_to_bursar
		{
		$.post(url,{contentvar:cv, pvno:v, folio_code:a, budget_type:b, budget_year:c, budget_dept:d},function(data){
		
		//$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$(divid).html(data).show();
		//$("#display").html(data).show();
		//$("#display").window("open");
		$("#roll").html('').show();
		});
}//end of send_voucher_to_bursar

  if(cv=="read_budget" ) // read budget by category and create droppable table cells
		{
			/*if(v == "")
			 {
				  alert('You must select Dept/Unit!');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  }*/ //end of validation 
		//alert($("form").serialize());exit();
		//$.post(url,{contentvar:cv, budget_cat:v, budget_dept:a, budget_year:b, pvno:c, code_cat:d},function(data){
		$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){
		//$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$(divid).html(data).show();
		//$("#display").html(data).show();
		//$("#display").window("open");
		$("#roll").html('').show();
		});
}//end of read_budget
  
  if(cv=="commit_budget" ) // read budget by category and create droppable table cells
		{
			/*if(v == "")
			 {
				  alert('You must select Dept/Unit!');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  }*/ //end of validation 
			  //alert(h); exit();
		$.post(url,{contentvar:cv, wvouchercode:v, wbudgetcode:a, wvoucheramount:b, budgettype:c, budgetyear:d, budgetdept:e, pvno:f, operation:g, query_txt:h},function(data){
		
		//$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$(divid).html(data).show();
		//$("#display").html(data).show();
		//$("#display").window("open");
		$("#roll").html('').show();
		document.location.reload(); //refresh
		});
}//end of read_budget

  if(cv=="reverse_budget_comit_process" ) // reverse budget commit
		{
			//alert(b); exit;
		$.post(url,{contentvar:cv, pvno:v, rev_action:a},function(data){
		
		//$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$(divid).html(data).show();
		//$("#display").html(data).show();
		//$("#display").window("open");
		$("#roll").html('').show();
		document.location.reload(); //refresh
		});
}//end of read_budget

  if(cv=="folio_summary" ) // reverse budget commit
		{
		$.post(url,{contentvar:cv, folio_code:v, budget_cat:a, amount:b, budget_year:c, budget_type:d, budget_dept:e},function(data){
		
		//$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$(divid).html(data).show();
		//$("#display").html(data).show();
		//$("#display").window("open");
		$("#roll").html('').show();
		});
}//end of read_budget

  if(cv=="query_budget" ) //read budget by category and create droppable table cells
		{
			/*if(v == "")
			 {
				  alert('You must select Dept/Unit!');
				  $(divid).html('').show();  //stop loader from rolling
				  exit();
			  }*/ //end of validation 
			  //alert(h); exit();
		$.post(url,{contentvar:cv, query_txt:v, wbudgetcode:a, wvoucheramount:b, budgettype:c, budgetyear:d, budgetdept:e, pvno:f, operation:g},function(data){
		//$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$(divid).html(data).show();
		//$("#display").html(data).show();
		//$("#display").window("open");
		$("#roll").html('').show();
		document.location.reload(); //refresh page
		});
    }//end of query budget

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
	        <h2>Commit Voucher</h2>
                <p>Commit voucher into vote-book...</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Voucher Processing</i></h3> -->
			<p>
                <div class="easyui-tabs" style="width:800px;height:auto" > <!-- begining of main tab-->
                       <div title="Pending Voucher" style="padding:10px">  <!-- pending tab  -->
                         <?php
			$r=@strtolower($r_vals);
			if($r=="super admin" || $r=="budget officer" || $r=="administrator" || isset($_REQUEST['faculty']))
			if(isset($_REQUEST['faculty'])) $sql="SELECT v.* from vouchertb v INNER JOIN unittb u ON v.dept_vou=u.unit_code where (checked_by != '' OR checked_by Is Not Null) and checked_action = 'Approved'  and (authorized_by!='' OR authorized_by Is Not NULL) and authorized_action='Approved' and (controlled_by = '' OR controlled_by Is Null OR controlled_action = '' OR controlled_action Is Null) AND v.dept_vou IN (SELECT jvcode FROM journal_code_user WHERE fileno='{$login_id}') order by voucher_date desc";
			else $sql="SELECT * from vouchertb where (checked_by != '' OR checked_by Is Not Null) and checked_action = 'Approved'  and (authorized_by!='' OR authorized_by Is Not NULL) and authorized_action='Approved' and (controlled_by = '' OR controlled_by Is Null OR controlled_action = '' OR controlled_action Is Null) order by voucher_date desc LIMIT 1000";
			/*if($r=="super admin" or $r=="expenditure control" or $r=="administrator")
			$sql="select * from vouchertb where checked_by!='' and authorized_by!='' and authorized_action='Approved' and controlled_by=''  order by voucher_date desc";*/
				
			$res_v=@mysqli_query($con, $sql);
			$sn=0;
			$tb="<table id='MyTable' width='98%' align='center' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box'>
			<thead>
			<tr><th>S/NO</th><th>NARRATION</th><!--th>PROCESS NO</th--><!--<th>PV NO</th>--><th>AMOUNT</th><th>PAYEE</th><th>PAYEE BANK<br>ACCT NO.</th><th>DEPARTMENT</th><th>DATE</th><th>ACTION</th></tr>
			</thead><tbody>";
			if(@mysqli_num_rows($res_v)>=1)
			{
				while($rs_v=@mysqli_fetch_array($res_v))
				{
					++$sn;
					$pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
					$desc=$rs_v['description'];
					$p=base64_encode($pvno);
					$payee_name=$rs_v['payee_name'];
					$amount=$rs_v['amount_approved'];
					$payee_acct_no=$rs_v['payee_acct_no'];
					$payee_bank_name=$rs_v['payee_bank_name'];
					$voucher_date=$rs_v['voucher_date'];
					$vdept=@read_voucher_vote_code($pvno); 
					$tb.="<tr><td>$sn</td><td>$desc</td><!--td>$pvno</td--><!--<td>$pvno_paid</td>--><td>".number_format($amount, 2)."</td><td>$payee_name</td><td>$payee_bank_name<br>$payee_acct_no</td><td>$vdept</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a> | <a href=\"javascript:swapcontent('display_voucher_processx','$pvno','$r_vals');\">PROCESS</a></td></tr>";
					///'voucher_report.php?p=$p' target='_blank'
					
					// $tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a href=\"javascript:swapcontent('display_voucher','$p','$r_vals');\">VIEW</a> | <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">PROCESS</a></td></tr>";

				} //end of while
					
					$tb.="</tbody></table>"; echo $tb;
			}
			else
			echo "<font color='red'><b>No pending voucher to process</b></font>";
			?>
                       </div>  <!-- end of pending tab-->
                       
                       <div title="Processed Voucher" style="padding:10px">  <!-- processed tab  -->
                         <?php
						   $r=@strtolower($r_vals);
						   if($r=="super admin" or $r=="budget officer" or $r=="administrator")
						       $sql="select * from vouchertb where (controlled_by != '' OR controlled_by Is Not Null) and controlled_action = 'Approved' order by voucher_date desc LIMIT 500";
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table id='MyTable2' width='98%' align='center' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box'>
							<thead>
							<tr><th>S/NO</th><th>NARRATION</th><!--th>PROCESS NO</th--><!--<th>PV NO</th>--><th>AMOUNT</th><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>DATE</th><th>ACTION</th></tr>
							</thead><tbody>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
									 $p=base64_encode($pvno);
									 $payee_name=$rs_v['payee_name'];
									 $amount=$rs_v['amount_approved'];
									 $payee_acct_no=$rs_v['payee_acct_no'];
									 $payee_bank_name=$rs_v['payee_bank_name'];
									 $voucher_date=$rs_v['voucher_date'];
									$vdept=@read_voucher_vote_code($pvno);
									 $tb.="<tr><td>$sn</td><td>$desc</td><!--td>$pvno</td--><!--<td>$pvno_paid</td>--><td>".number_format($amount, 2)."</td><td>$payee_name</td><td>$payee_bank_name<br>$payee_acct_no</td><td>$vdept</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a>";
									 ////if($rs_v['audit_by'] == '')
									 $tb .= " | <a href=\"javascript:swapcontent('reverse_budget_comit','$pvno','$r_vals','processed');\">REVERSE</a>";
									 $tb .= "</td></tr>";

								 } //end of while
								 
								 $tb.="</tbody></table>"; echo $tb;
						    }
							else
						       echo "<font color='red'><b>No proccessed voucher</b></font>";
						 ?>
                       </div> <!-- End of proccessed tab -->
                       
                       <div title="Queried Voucher" style="padding:10px">  <!-- Queried tab  -->
                         <?php
						   $r=@strtolower($r_vals);
						   if($r=="super admin" or $r=="budget officer" or $r=="administrator")
						       $sql="SELECT * from vouchertb where controlled_action = 'Queried' OR audit_action = 'Queried' order by voucher_date desc LIMIT 100";
							   
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table id='MyTable3' width='98%' align='center' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box'>
							<thead>
							<tr><th>S/NO</th><th>NARRATION</th><!--th>PROCESS NO</th--><!--<th>PV NO</th>--><th>AMOUNT</th><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>DATE</th><th>ACTION</th></tr></thead><tbody>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
									 $p=base64_encode($pvno);
									 $payee_name=$rs_v['payee_name'];
									 $amount=$rs_v['amount_approved'];
									 $payee_acct_no=$rs_v['payee_acct_no'];
									 $payee_bank_name=$rs_v['payee_bank_name'];
									 $voucher_date=$rs_v['voucher_date'];
									$vdept=@read_voucher_vote_code($pvno);
									 $tb.="<tr><td>$sn</td><td>$desc</td><!--td>$pvno</td--><!--<td>$pvno_paid</td>--><td>".number_format($amount, 2)."</td><td>$payee_name</td><td>$payee_bank_name<br>$payee_acct_no</td><td>$vdept</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a>";
									 $tb .= " | <a href=\"javascript:swapcontent('reverse_budget_comit','$pvno','$r_vals','queried');\">REVERSE</a>";
									 $tb .= " | <a href=\"javascript:swapcontent('reverse_budget_comit','$pvno','$r_vals','undo');\">UNDO QUERY</a>";
									 $tb .= "</td></tr>";

								 } //end of while
								 
								 $tb.="</tbody></table>"; echo $tb;
						    }
							else
						       echo "<font color='red'><b>No querried voucher</b></font>";
						 ?>
                       </div> <!-- end of Queried tab -->
                   </div> <!-- end of main tab -->
				   
				   <div id="display" class="easyui-window" title="Voucher Process" style="width:750px;height:600px;padding:10px; display:none" 
          data-options="
          modal:true,
          closed:true,
          iconCls:'icon-tip',
			onResize:function(){
				$(this).window('hcenter');
			}" > </div>
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