<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
	$memo_id=@base64_decode($_REQUEST['id']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@$_SESSION['role'];
 $staff_category=@$_SESSION['staff_category'];

?>
<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<style>
.date1 {
	color: #333333;
	background: #F7F7F7;
	border: 1px solid #CCCCCC;
	height:25px;
	width:200px;
	vertical-align:inherit;
	text-align:inherit;
	padding-left: 1px;
	font-size:12px;
	font-family:Palatino Linotype;
}

.delete{
		content: 'X';
		border:outset 1px red;
		border-radius:4px;
		padding:3px;
		cursor:pointer;
	}
</style>
	
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php include("required_jQuery_files.php"); ?>

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
	//$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_m.php";
	var str;
	

 if(cv=="read_budget_votebook" ) // read budget by category and create droppable table cells
	{
		alert(12345);
		$.post(url,{contentvar:cv, budget_cat:v, budget_dept:a, budget_year:b, code_cat:c},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}//end of read_budget_votebook
  
  if(cv=='display_voucher_process_transfer') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();

		$.post(url,{contentvar:cv,pvno:v,r_vals:a,id:b},function(data){
		
		//TINY.box.show(data,0,0,0,0);
		//alert (data);
		$(divid).html('').show(); 
		//report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#display").html(data).show();
		$("#display").window("open");
		$("#roll").html('').show();
		});
  }//end of
  
  if(cv=="read_budget" ) // read budget by category and create droppable table cells
		{
			$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}//end of read_budget
  
  if(cv=="commit_budget_transfer" ) // read budget by category and create droppable table cells
		{
			$.post(url,{contentvar:cv, wvouchercode:v, wbudgetcode:a, wvoucheramount:b, budgettype:c, budgetyear:d, budgetdept:e, pvno:f, operation:g, query_txt:h, voteID:j},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
			document.location.reload(); //refresh
		});
	}//end of 

  if(cv=="folio_summary" ) 
	{
		$.post(url,{contentvar:cv, folio_code:v, budget_cat:a, amount:b, budget_year:c, budget_type:d, budget_dept:e},function(data){
			$(divid).html(data).show();
			$("#roll").html('').show();
		});
	}//end of 


  
} //end of swapcontent


$(document).ready(function() { //parent.jQuery.colorbox. (); 
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
<body class="subpage"><!-- onLoad="$('#fileno').combogrid('StaffGrid').datagrid('load', {filen:''});"-->

<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
	<?php include_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>Transfer Voucher Commitment</h2>
	        <p>&nbsp;</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
<?php
$mode=base64_decode($_REQUEST['mode']);
$year=$_REQUEST['year']; 
$folio=$_REQUEST['folio'];
$rid=$_REQUEST['rid'];

require_once "function_c.php";
	require_once("myclass_m.php");
	$bursary=new myclass_m();
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////

$amount=get_folio_budget($folio, $year);

//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<table width="100%" border="0" align="center"><tr>
<td width="" valign="bottom" align="left">
  <?php
	(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
	?>
  Department: <?php echo $folio_name; ?><br>
  Expenditure Head: <?php echo $folio_name; ?><br>
  Code: <?php echo $folio; ?><br>
  Approved Vote &#8358;: <?php echo number_format($amount, 2); ?></td>
</tr>
	</table>
<center>
  
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
	  <tr>
	    <th>S/No.</th>
	    <th>Date<br>
	      PV/LPO. Journal</th>
	    <th>Details</th>
	    <th colspan="2">Amount<br>&#8358;</th>
	    <th>Remark</th>
	    </tr>
	  <?php 
  $sql="SELECT * FROM budget_votebooktb WHERE budget_folio_code='$folio' AND operation_year='$year'";
  $qry= mysqli_query($con, $sql);	$sn=1;
	  $incurred=0;
  while($r =  mysqli_fetch_array($qry, 3 )){
	  $edate=date_create($r['entry_date']);
	  $vdate=date_create($bursary->get_any_value("entry_date", "vouchertb", "pvno", $r['voucher_pvno']));
	  $desc=$bursary->get_any_value("description", "vouchertb", "pvno", $r['voucher_pvno']);
	  $payee=$bursary->get_any_value("payee_name", "vouchertb", "pvno", $r['voucher_pvno']);
	  $liability=$bursary->get_any_value("total_tax", "vouchertb", "pvno", $r['voucher_pvno']);
	  $amount=$r['amount'];
	  $incurred += $amount;
	  $balance = get_budget($folio, $year) - $incurred;
	   $pvno=$r['voucher_pvno'];
	   $p=base64_encode($pvno);
	   $id=$r['id'];
   ?>
    
    
	  <tr>
	    <td valign="top"><?php echo $sn++; ?></td>
	    <td valign="top"><strong><?php echo date_format($vdate, 'd/m/Y'); ?></strong><br>
  <?php echo $r['voucher_pvno']; ?></td>
	    <td><?php echo "<strong>".strtoupper($payee)."</strong><br>".$desc; ?></td>
	    <td colspan="2" align="right"><?php echo number_format($amount, 2); ?></td>
	    <td><?php echo "<a class='iframe' href='voucher_report.php?p=$p' >VIEW</a> | <a href=\"javascript:swapcontent('display_voucher_process_transfer','$pvno','$r_vals', '$id');\">TRANSFER</a>"; ?></td>
	      </tr>
    <?php
  }
  ?>
</table>   
<div id="display" class="easyui-window" title="Voucher Commitment Transfer" style="width:750px;height:600px;padding:10px; display:none" 
          data-options="
          modal:true,
          closed:true,
          iconCls:'icon-tip',
			onResize:function(){
				$(this).window('hcenter');
			}" > </div>
            <div id="roll"></div>
</center>
<p><hr></p>
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