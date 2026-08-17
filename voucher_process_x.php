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
	        <h2>Voucher Processing</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Voucher Processing</i></h3> -->
			<p>
                <div class="easyui-tabs" style="width:800px;height:auto" > <!-- begining of main tab-->
                       <div title="Pending" style="padding:10px">  <!-- pending tab  -->
                         <?php
						  $r=@strtolower($r_vals);
						    if($r=="super admin" or $r=="checked by officer" or $r=="administrator")
						       $sql="select * from vouchertb where checked_by='' order by voucher_date desc";
						   elseif($r=="super admin"  or $r=="administrator" or $r=="expenditure control")//or $r=="authorized officer"
						       $sql="select * from vouchertb where checked_by!=''  and checked_action='Approved' order by voucher_date desc";
							elseif($r=="super admin" or $r=="authorized officer")
						       $sql="select * from vouchertb where checked_by!='' and authorized_by ='' and authorized_action='' and controlled_by!='' order by voucher_date desc";
							elseif($r=="super admin" or $r=="final authorized officer")
						       $sql="select * from vouchertb where checked_by!='' and controlled_by!='' and authorized_by !='' and authorized_by2='' and controlled_action='Approved' order by voucher_date desc";
							   
							   elseif($r=="super admin" or $r=="auditor")
						       $sql="select * from vouchertb where checked_by!='' and controlled_by!='' and authorized_by !='' and authorized_by2 !='' and audit_by='' and controlled_action='Approved' order by voucher_date desc";
						  // elseif($r=="super admin" or $r=="bursar")
						   //    $sql="select * from vouchertb where checked_by!='' and controlled_by!='' and authorized_by='' and controlled_action='Approved' order by voucher_date desc";
						   elseif($r=="super admin" or $r=="cash officer")
						       $sql="select * from vouchertb where checked_by!='' and controlled_by!='' and authorized_by!='' and authorized_by2!='' and audit_by!='' and paid_by='' and audit_action='Approved' order by voucher_date desc";
							   
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table align='left' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box'><tr><th>S/NO</th><th>PROCESS NO</th><!--<th>PV NO</th>--><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>DATE</th><th>ACTION</th></tr>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
									 $p=base64_encode($pvno);
									 $payee_name=$rs_v['payee_name'];
									 $payee_acct_no=$rs_v['payee_acct_no'];
									 $payee_bank_name=$rs_v['payee_bank_name'];
									 $voucher_date=$rs_v['voucher_date'];
									 $tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a> | <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">PROCESS</a></td></tr>";
									///'voucher_report.php?p=$p' target='_blank'
									
									// $tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a href=\"javascript:swapcontent('display_voucher','$p','$r_vals');\">VIEW</a> | <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">PROCESS</a></td></tr>";

								 } //end of while
								 
								 $tb.="</table>"; echo $tb;
						    }
							else
						       echo "<font color='red'><b>No pending voucher to process</b></font>";
						 ?>
                       </div>  <!-- end of pending tab-->
                       
                       <div title="Processed" style="padding:10px"> <!-- processed tab  -->
                        <?php
						   $r=@strtolower($r_vals);
						    if($r=="super admin" or $r=="checked by officer" or $r=="administrator")
						       $sql="select * from vouchertb where checked_by!='' order by voucher_date desc";
						   elseif($r=="super admin" or $r=="authorized officer")
						       $sql="select * from vouchertb where authorized_by!='' order by voucher_date desc";
							   elseif($r=="super admin" or $r=="final authorized officer")
						       $sql="select * from vouchertb where authorized_by2!='' order by voucher_date desc";
						   elseif($r=="super admin" or $r=="expenditure control")
						       $sql="select * from vouchertb where controlled_by!='' order by voucher_date desc";
						   elseif($r=="super admin" or $r=="auditor")
						       $sql="select * from vouchertb where audit_by!='' order by voucher_date desc";
						  // elseif($r=="super admin" or $r=="bursar")
						    //   $sql="select * from vouchertb where authorized_by!='' order by voucher_date desc";
						   elseif($r=="super admin" or $r=="cash officer")
						       $sql="select * from vouchertb where paid_by!='' order by voucher_date desc";
							   
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table align='left' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box'><tr><th>S/NO</th><th>PROCESS NO</th><!--<th>PV NO</th>--><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>DATE</th><th>ACTION</th></tr>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
									 $p=base64_encode($pvno);
									 $payee_name=$rs_v['payee_name'];
									 $payee_acct_no=$rs_v['payee_acct_no'];
									 $payee_bank_name=$rs_v['payee_bank_name'];
									 $voucher_date=$rs_v['voucher_date'];
									 $tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p'>VIEW</a> <!--| <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">PROCESS</a>--></td></tr>";
								 } //end of while
							 
								 $tb.="</table>"; echo $tb;
						   }
						   else
						    echo "<font color='red'><b>No record to display</b></font>";
						?>
                       </div> <!-- end of processed tab-->
                       
                       <div title="Queried" style="padding:10px"> <!-- QUERIED tab  -->
                        <?php
						   $r=@strtolower($r_vals);
						    if($r=="super admin" or $r=="checked by officer" or $r=="administrator")
						       $sql="select * from vouchertb where (checked_by!='' and checked_action = 'Queried') or (controlled_by != '' and controlled_action = 'Queried') or (audit_by != '' and audit_action = 'Queried') or (authorized_by != '' and authorized_action = 'Queried') or (paid_by != '' and paid_action = 'Queried') order by voucher_date desc";
						   elseif($r=="super admin" or $r=="authorized officer")
						       $sql="select * from vouchertb where (authorized_by != '' and authorized_action = 'Queried') or (controlled_by != '' and controlled_action = 'Queried') or (audit_by != '' and audit_action = 'Queried') or (paid_by != '' and paid_action = 'Queried') order by voucher_date desc";
							   elseif($r=="super admin" or $r=="final authorized officer")
						       $sql="select * from vouchertb where (authorized_by2 != '' and authorized_action2 = 'Queried') or (controlled_by != '' and controlled_action = 'Queried') or (audit_by != '' and audit_action = 'Queried') or (paid_by != '' and paid_action = 'Queried') order by voucher_date desc";
						   elseif($r=="super admin" or $r=="expenditure control")
						       $sql="select * from vouchertb where (controlled_by != '' and controlled_action = 'Queried') or (audit_by != '' and audit_action = 'Queried') or (paid_by != '' and paid_action = 'Queried') order by voucher_date desc";
						   elseif($r=="super admin" or $r=="auditor")
						       $sql="select * from vouchertb where (audit_by != '' and audit_action = 'Queried') or (paid_by != '' and paid_action = 'Queried') order by voucher_date desc";
						  // elseif($r=="super admin" or $r=="bursar")
						    //   $sql="select * from vouchertb where authorized_by!='' order by voucher_date desc";
						   elseif($r=="super admin" or $r=="cash officer")
						       $sql="select * from vouchertb where paid_by != '' and paid_action = 'Queried' order by voucher_date desc";
							   
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table align='left' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box'><tr><th>S/NO</th><th>PROCESS NO</th><!--<th>PV NO</th>--><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>DATE</th><th>ACTION</th></tr>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
									 $p=base64_encode($pvno);
									 $payee_name=$rs_v['payee_name'];
									 $payee_acct_no=$rs_v['payee_acct_no'];
									 $payee_bank_name=$rs_v['payee_bank_name'];
									 $voucher_date=$rs_v['voucher_date'];
									 $tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p'>VIEW</a> <!--| <a href=\"javascript:swapcontent('display_voucher_process','$pvno','$r_vals');\">PROCESS</a>--></td></tr>";
								 } //end of while
							 
								 $tb.="</table>"; echo $tb;
						   }
						   else
						    echo "<font color='red'><b>No record to display</b></font>";
						?>
                       </div> <!-- END OF QUERY VOUCHER -->
                       
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