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
	

  if(cv=='forget_password') 
  {
          $.post(url,{contentvar:cv},function(data){
          TINY.box.show(data,0,0,0,0);$(divid).html('').show();
          $("#roll").html('').show();
          });
  }
  
  if(cv=='display_voucher_process') 
  {
          $.post(url,{contentvar:cv, pvno:v, r_vals:'<?=$r_vals?>', schedule:'Yes'},function(data){
            $(divid).html('').show(); 
            $("#display").html(data).show();
            $("#display").window("open");
            $("#roll").html('').show();
          });
  }
  if(cv=='remove_from_schedule') 
  {
		$.post(url,$("#frmpro").serialize()+"&contentvar="+cv,function(data){ 
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

  if(cv=='getLastPV') //start unit
  {
          $.post(url,{contentvar:cv, type:$("#schType").val(), year:$("#schYear").val()},function(data){
                    $("#roll").html('').show();
                    $("#schLastPV").html(data).show();
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
               $.post(url, {requestID:requestID, fileno:loaderDiv, sdate:recID, r_vals:'<?=$r_vals?>'}, function(response){
                    $("#"+requestID).html(response);
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
	        <h2>Voucher Schedule (Treasury)</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
            <div class="content" style="width: 1200px;">
		<div id="display2"></div>
                    <p>
               
                    <?php $r=@strtolower($r_vals); ?>
                    <div style="padding:10px"> 
                    <hr>
                    <h3><a href="voucher_list_for_schedule.php">SCHEDULE VOUCHERS FOR PAYMENT</a></h3><hr>
                              <form method='post' id='frmEdit2' name='frmEdit2'>
                              <?php
                                        echo "<h1>VOUCHERS SCHEDULED FOR PAYMENT</h1>";

                                        $sql="SELECT fileno, sdate, count(fileno) AS cnt FROM pv_pay_scheduletb GROUP BY fileno, sdate ORDER BY sdate desc";
                                        $res_v=@mysqli_query($con, $sql);
                                        $sn=0;
                                        echo "<table id='MyTable2' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
                                        <thead> 
                                        <tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>FILE NO</th><th>STAFF NAME</th><th>SCHEDULE DATE</th><th>NOs.</th><th>ACTION</th></tr></thead><tbody>";
                                        if(@mysqli_num_rows($res_v)>=1)
                                        {
                                                  while($rs_v=@mysqli_fetch_array($res_v))
                                                  {
                                                            ++$sn;
                                                            $fileno=$rs_v['fileno']; 
                                                            $sdate=date('d/m/Y', strtotime($rs_v['sdate']));
                                                            $staff_name=strtoupper(get_staff_name($rs_v['fileno']));
                                                            echo "<tr><td>$sn</td>
                                                            <td>$fileno</td>
                                                            <td>$staff_name</td>
                                                            <td>$sdate</td><td>{$rs_v['cnt']}</td>
                                                            <td><a class='btn' href='#' onclick='sendRequest(\"viewPVScheddule\", \"{$fileno}\", \"{$rs_v['sdate']}\");'>SHOW LIST</a></td>";
                                                            echo "</tr>";
                                                  } //end of while
                                                  
                                                  echo "</tbody></table>";
                                        }
                                        else
                                        echo "<font color='red'><b>No voucher(s) scheduled yet.</b></font>";
                              ?>
                              <p>&nbsp;</p>
                              </form>
                    </div>
                    <div id="viewPVScheddule"></div>
		<div id="display" class="easyui-window" title="Query Voucher" data-options="
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