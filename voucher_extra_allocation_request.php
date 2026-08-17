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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
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
  
  if(cv=='display_voucher_process2') //start putme_login
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
  if(cv=='process_voucher2') //start putme_login
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
	        <h2>Voucher Extra Allocation Request</h2>
                <p>...</p>
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
						    if($r=="super admin" or $r=="bursar" or $r=="administrator")
						       $sql="select * from voucher_extra_allocation_requesttb where approval_status='Not Approved' order by requested_date desc";
						  elseif($r=="super admin" or $r=="expenditure control")
						       $sql="select * from voucher_extra_allocation_requesttb where approval_status='Approved' and commit_status = 'Not Committed' order by approved_date desc";
							   
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table align='left' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box'><tr><th>S/NO</th><th>PROCESS NO</th><!--<th>PV NO</th>--><th>AMOUNT (=N=)</th><th>BUDGET DESCRIPTION</th><th>APPROVAL STATUS</th><th>DATE</th><th>ACTION</th></tr>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
									 $p=base64_encode($pvno);
									 $amount=$rs_v['amount'];
									 $folio=@get_folio_name($rs_v['folio_code']);
									 $approval_status=$rs_v['approval_status'];
									 $requested_date=$rs_v['requested_date'];
									 $approval_date=$rs_v['approval_date'];
									 $tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$amount</td><td>$folio</td><td>$approval_status</td>";
									 if($r=="super admin" or $r=="bursar" or $r=="administrator")
									 	{
									 		$tb .="<td>".date('d/m/Y',strtotime($requested_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a> | <a href=\"javascript:swapcontent('display_voucher_process2','$pvno','$r_vals');\">PROCESS</a></td></tr>";
										}
											
									 elseif($r=="expenditure control")
									 	{
											$r_v=base64_encode($r_vals);
											
											$tb .="<td>".date('d/m/Y',strtotime($approval_date))."</td><td><a href=\"budget_comit.php?r_val=$r_v\">Proceed</a></td></tr>";
										}
									 
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
						    if($r=="super admin" or $r=="bursar" or $r=="administrator")
						       $sql="select * from voucher_extra_allocation_requesttb where approval_status='Approved' order by approved_date desc";
						  elseif($r=="expenditure control")
						       $sql="select * from voucher_extra_allocation_requesttb where commit_status='Committed' order by commit_date desc";

							   
							$res_v=@mysqli_query($con, $sql);
							$sn=0;
							$tb="<table align='left' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box'><tr><th>S/NO</th><th>PROCESS NO</th><!--<th>PV NO</th>--><th>AMOUNT (=N=)</th><th>BUDGET DESCRIPTION</th><th>APPROVAL STATUS</th><th>DATE</th>";
							if($r=="super admin" or $r=="bursar" or $r=="administrator") $tb .= "<th>ACTION</th>";
							$tb .= "</tr>";
						  if(@mysqli_num_rows($res_v)>=1)
						   {
								while($rs_v=@mysqli_fetch_array($res_v))
								 {
									 ++$sn;
									 $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
									 $p=base64_encode($pvno);
									 $amount=$rs_v['amount'];
									 $folio=@get_folio_name($rs_v['folio_code']);
									 $approval_status=$rs_v['approval_status'];
									 $requested_date=$rs_v['requested_date'];
									 $approval_date=$rs_v['approval_date'];
									 $tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$amount</td><td>$folio</td><td>$approval_status</td>";
									 if($r=="super admin" or $r=="bursar" or $r=="administrator")
									 	{
									 		$tb .="<td>".date('d/m/Y',strtotime($approval_date))."</td><td><a class='iframe' href='voucher_report.php?p=$p' >VIEW</a> </td></tr>";
										}
											
									 elseif($r=="expenditure control")
									 	{
											$tb .="<td>".date('d/m/Y',strtotime($commit_date))."</td><td>&nbsp;</td></tr>";
										}
							
								 } //end of while
							 
								 $tb.="</table>"; echo $tb;
						   }
						   else
						    echo "<font color='red'><b>No record to display</b></font>";
						?>
                       </div> <!-- end of processed tab-->
                       
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