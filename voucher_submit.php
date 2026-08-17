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

if(strtolower($rv) == "cash officer") $role_cap="MAKE PAYMENT";
else $role_cap = $rv;

//$role_cap = base64_decode($_REQUEST['r_val']);

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
	  
  if(cv=='edit_posted_entry') //start putme_login
  {
	//  alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,trans_id:v,r_vals:a,action:b},function(data){
		
		//TINY.box.show(data,0,0,0,0);
		//alert (data);
		$(divid).html('').show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#display").html(data).show();
		$("#display").window("open");
		$("#roll").html('').show();
		if(b=="DELETE") document.location.reload();
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
  if(cv=='save_posted_entry') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,$("#update_trans").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		document.location.reload();
		//refresh();
		});
  }//end of putme_login
  
} //end of swapcontent
 </script>
 <script src="include/jquery.dataTables.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="include/jquery.dataTables.min.css">
 <script type="text/javascript">
 $(document).ready(function() {
    $('#dTable').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ) {
                    if(column.search() === '^'+d+'$'){
                        select.append( ''+d+'' )
                    } else {
                        select.append( ''+d+'' )
                    }
                } );
            } );
        }
    } );
} );

$(function(){
   $('#checkAll').click(function(){
      if (this.checked) {
         $(".checkboxes").prop("checked", true);
      } else {
         $(".checkboxes").prop("checked", false);
      }	
   });
});
</script>

<script>
     function sendRequest(requestID, loaderDiv='', recID=''){
          var url="script-fac.php";
          var loader='<img src="images/loader.gif" height="30" alt="loading">Workiing...';
          if(requestID=="movetoledgerXX"){
               $("#"+requestID).html(loader).show();
               $.post(url, $("#frmEdit").serialize()+"&requestID="+requestID, function(response){
                    $("#"+requestID).html(response);
               });
          }
     }
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
	        <h2>Edit Posted Entries</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Voucher Processing</i></h3> -->
	      <form method='post' id='frmEdit' name='frmEdit'>
		  <p>
          
<!-- begining of main tab-->
          <!-- pending tab  -->
  <?php
	   $r=@strtolower(base64_decode($_REQUEST['r_val']));
		//if($r=="super admin" or $r=="accountant" or $r=="administrator")
		$from = $_POST['from']; $to = $_POST['to'];
		   $sql="select * from transtb where transdate >= '$from' AND transdate <= '$to' ORDER BY transdate desc";
		   
		$res_v=@mysqli_query($con, $sql);
		$sn=0;
		$tb="<table align='left' border='1' cellpadding='5' cellspacing='5' rules='rows' frame='box' width='100%' class='display' id='dTable'> 
		<thead>
		<tr><th><input type='checkbox' id='checkAll' name='checkAll' value='{$rs_v['pvno']}'></th><th>S/NO</th><!--<th>PROJECT</th>--><th>PV./JV. NO.</th><th>CHEQUE/TRANS. ID</th><th>CR/DR ACCOUNT</th><th>ITEM DESCCRIPTION</th><th>AMOUNT</th><th>DATE</th><th>ACTION</th></tr></thead><tbody>";
	  if(@mysqli_num_rows($res_v)>=1)
	   {
			while($rs_v=@mysqli_fetch_array($res_v))
			 {
				 ++$sn;							$tid=$rs_v['id'];
				 $pvno=$rs_v['pvno'];			$project=get_dept_name($rs_v['dept_acctcode']);
				 $transid=$rs_v['chequeno'];	$p=base64_encode($pvno);
				 $acct=$rs_v['acctcode'];		$amount=$rs_v['amount'];
				 $item_desc=get_folio_name($rs_v['folio_code'])." ($rs_v[folio_code])";
				 $trans_date=$rs_v['transdate'];
				 $receiptno=$rs_v['receiptno'];
				 if($rs_v['transtype']=="Debit") $transtype="Dr"; elseif($rs_v['transtype']=="Credit") $transtype="Cr"; 
				 $tb.="<tr><td><input type='checkbox' class='checkboxes' id='pv_jv_{$rs_v['id']}' name='pv_jv[]' value='{$rs_v['id']}'></td><td>$sn</td>
				 <!--<td>$project</td>--><td>$pvno $receiptno</td>
				 <td>$transid</td>
				 <td>".get_account_name($acct, false)." ($transtype)</td>
				 <td>$item_desc</td>
				 <td>$amount</td>
				 <td>".date('d/m/Y',strtotime($trans_date))."</td>
				 <td nowrap>";
				 if($pvno!='') $tb.="<a class='iframe' href='voucher_report.php?p=$p' >VIEW PV</a> | ";
				 $tb.="<a href=\"javascript:swapcontent('edit_posted_entry','$tid','$r_vals', 'EDIT');\">EDIT</a> | ";
				 $tb.="<a href=\"javascript:swapcontent('edit_posted_entry','$tid','$r_vals', 'DELETE');\">DELETE</a></td></tr>";
				///'voucher_report.php?p=$p' target='_blank'
				
				// $tb.="<tr><td>$sn</td><td>$pvno</td><!--<td>$pvno_paid</td>--><td>$payee_name</td><td>$payee_acct_no</td><td>$payee_bank_name</td><td>".date('d/m/Y',strtotime($voucher_date))."</td><td><a href=\"javascript:swapcontent('display_voucher','$p','$r_vals');\">VIEW</a> | <a href=\"javascript:swapcontent('edit_posted_entry','$pvno','$r_vals');\">PROCESS</a></td></tr>";

			 } //end of while
			 
			 $tb.="</tbody></table>"; echo $tb;
			 ?>
			<!--hr>
			YEAR: <select id="tyear" name="tyear">
				<option value="">---</option>
				<option value="2024">2024</option>
				<option value="2025">2025</option>
				<option value="2026">2026</option>
				<option value="2027">2027</option>
				<option value="2028">2028</option>
				<option value="2029">2029</option>
				<option value="2030">2030</option>
			</select>
				<button type="button" class="btn btn-outline-primary btn-fw" name="toledger" id="toledger" onclick="sendRequest('movetoledger');">MOVE SELECTION TO GENERAL LEDGER</button>
			 <div id="movetoledger"></div-->
			 <?php
		}
		else
		   echo "<font color='red'><b>No transaction to process</b></font>";
  ?>
          <!-- end of pending tab-->                <!-- end of main tab -->
				   
	      <div id="display" class="easyui-window" title="Edit Posted Entries" data-options="
                   modal:true,
                   closed:true,
                   iconCls:'icon-save',
			onResize:function(){
				$(this).window('hcenter');
			}" style="width:600px;height:auto;padding:10px; display:none"> </div>
            </p>
	  </form>
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