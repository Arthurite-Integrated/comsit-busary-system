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
  
  if(cv=='edit_posted_entry') //start putme_login
  {
	//  alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,trans_id:v,r_vals:a},function(data){
		
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
		  <p>
          <form action="journal-edit2.php" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm" target="_blank">
			
			<table align="center">
			<!--<tr>
                <th colspan="2">Select Project<br />
                  <select name="dept" id="dept" onChange="" tabindex="1">
  <option value="" selected>--</option>
  <?php 
//$r=@mysqli_query($con, "select distinct u.dept_acctcode,d.deptname from users_roletb u, account_depttb d where u.dept_acctcode=d.dept_acctcode and u.fileno='$login_id' order by u.dept_acctcode");$n=0;
$r=@mysqli_query($con, "select distinct * from  departmenttb  order by dept_name");
$n=0;

while($rl=@mysqli_fetch_array($r))
{
	++$n;
	$deptcode=@$rl['dept_code'];$deptname=@$rl['dept_name'];
	if($n==1){$acc=$deptcode;}
	echo "<option value='$deptcode'>$deptname</option>";
	
}

?>
  </select>
                </th>
              </tr>-->
			
			<tr>
<th colspan='1'>Starting Date<br /><input type="date" name="from" id="from" size="25" tabindex="3" />
</th><th>End Date<br /><input type="date" name="to" id="to" size="25" tabindex="3" />
</th>
                </tr>
                <!--tr>
			  <th colspan='1'>Select Account</th>
			  <th><select name="account" id="account" class="txt" style="width: 200px"  >
                  <option selected="selected">---</option>
                                    <?php
								$r=@mysqli_query($con, "select distinct *  from bank_accounttb where status='Active' order by acctname");
								while ($rcourse=@mysqli_fetch_array($r))
									{
										$scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
										$bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
										$acctname=@$rcourse['acctname'];
										echo "<option value='$pcode'> $pcode || $acctno || $bank <=> ($scourse)</option>";
										
									}
								
								?>
                    <?php
                         
						 ?>
                </select></th>
			  </tr-->
				
			   <!--<tr><th colspan="2">
			   			Folio Code<br />
				<select name="folio_code" id="folio_code" tabindex="8">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct *  from foliotb where category not like '%bank%' order by title,folio_code");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['title'];$pcode=@$rcourse['folio_code'];
							echo "<option value='$pcode'>$scourse <=> ($pcode)</option>";
							
						}
					
					?>
					</select>
			   </th>
			   </tr>-->
				<tr><th colspan="2">
				<center>
				</center>
				</th>
				</tr>
				<tr><th colspan="2">
				<input type="hidden" name="reporttype" id="reporttype" value="<?php echo $report_type; ?>">
				<input type="submit"  class="btn" name="sbtn" id="sbtn" value="Display Report" tabindex="11" />
				<!--<input type="button" class="btn" name="chbtn" id="chsbtn" value="Delete Processed Salary" onclick="if(confirm('Are you sure you want to perform this operation'))swapcontent('salary_computation_section','delete');" />
				
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('salary_computation_section','refresh','Debit');" />
				-->
				<!--,$('#ccode').val()-->
				
				</th></tr>
             </table>
				<div id="display"></div>
				<div id="roll"></div>
				
					
				</form>
	      </p>           
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