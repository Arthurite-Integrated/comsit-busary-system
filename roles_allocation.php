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
     <?php include("required_jQuery_files.php");
     include "function.php";?>
     <link href="tooplate_style.css" rel="stylesheet" type="text/css" />
     <script>
     function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
     {   
          var divid="#"+cv;
          $(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
          var url="scriptfile_c.php";
          var str;

          if(cv=='allocaterole_section')
          {
               if(v=='save')
               {
                    if($("#fileno").val()=='' || $("#role").val()=='')
                    {
                         alert('Specify the Staff file number and the role assigned.');
                         $(divid).html('').show();
                         exit;
                    }
               }
               var mydata=JSON.stringify($('#frm').serializeObject());

               $.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a},function(data){
                    $(divid).html(data).show();

                    if(v=='save')
                    {
                         //$("#role").val('');
                    }
               });
          }

          if(cv=='assign_faculty')
          {
               /*if(v=='save')
               {
                    if($("#fileno").val()=='' || $("#role").val()=='')
                    {
                         alert('Specify the Staff file number and the role assigned.');
                         $(divid).html('').show();
                         exit;
                    }
               }*/
               var mydata=JSON.stringify($('#frm').serializeObject());

               $.post(url,{contentvar:cv, mydata:mydata, action:v, r_id:a, unitcode:b, rid:c},function(data){
                    $(divid).html(data).show();

                    if(v=='save')
                    {
                         //$("#role").val('');
                    }
               });
          }

          if(cv=='dept_acctcode') //load levels
          {
               $.post(url,{contentvar:cv,role:v},function(data){
                    $(divid).html(data).show();
               });
          }

          if(cv=='another')
          {
               $.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  
                    $(divid).html(data).show(); 
               });
          }

     } //end of swapcontent
     $(document).ready(function() { 
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
                    <h2>Staff Roles  Setup</h2>
                    <p>Manage staff role(s) allocation.</p>
               </div><!-- end of content_title_box -->

               <div id="tooplate_content" style="width:1200px;">

                    <div class="content_box">

                         <div id="page" class="container">
                              <div class="content">
                                   <p>
                                        <form name="frm" id="frm" width="45">
					<div style="width:50%; float:left;">
						<table width="25%"  border="0" cellpadding="5">
							<tr>
							<td width="25%">
								<p><strong>File No </strong></p>
								<select name="fileno" id="fileno" tabindex="1" style="width:350px">
								<option selected="selected" value="">---</option>
								<?php
								$r=@mysqli_query($con, "select distinct * from stafftb where fileno not like '%weathstone%' order by surname");
								while ($rcourse=@mysqli_fetch_array($r))
								{
									$scourse=@$rcourse['surname'].' '.$rcourse['first_name'].' '.$rcourse['other_name'].' ('.$rcourse['title'].')';$pcode=@$rcourse['fileno'];
									echo "<option value='$pcode'>$pcode || $scourse</option>";

								}

								?>
								</select>
							</td>
							<font color="#FF9900"><span id="foliodesc"></span></font>
							</tr>
							<tr>
							<td>
								<p><strong>Role</strong></p>
								<select name="role" id="role" class="txt" tabindex="2" style="width:350px">
								<option selected="selected" value="">---</option>
								<?php
								$res_c=@mysqli_query($con, "select * from roletb where status='Active' order by role");
								while($rs_c=@mysqli_fetch_array($res_c))
								{
									$role=@$rs_c['role'];
									$caption=@$rs_c['caption'];
									echo "<option value='$role'>$caption</option>";
								}
								echo "</select>";
								?>
								</select>
							</td>
							</tr>

							<tr>
							<td>
								<span id="dept_acctcode">
								<p><strong>Department</strong></p>
								<select name="dept_acctcode" id="dept_acctcode"  tabindex="3" style="width:350px">
									<option value="" selected="selected">--Select Item--</option>
									<?php  $q =  mysqli_query($con, "select * from departmenttb order by dept_name");
									while($r= mysqli_fetch_array($q, 3 )){
									echo '<option value="'. $r['dept_code'] .'">'. $r['dept_name'] .'</option>';
									}
									?>
								</select>
								</span>
							</td>

							</tr>
							<tr>
							<th>
								<div align="center">
								<input type="button" name="save" id="save" value="Save/Assign" class="btn" onclick="swapcontent('allocaterole_section','save');"/>
								<input type="button" name="search" id="search" value="Search" class="btn" onclick="swapcontent('allocaterole_section','search');"/>
								</div>
							</th>
							</tr>
						</table>
					</div>
					<div style="width:50%; float:right;">
						<div id="assign_faculty"> </div>
					</div><p>
					<div id="allocaterole_section"> </div></p>
                                        </form>


                                   </p>
                              </div><!-- end of content -->


                              <!-- ############### Side bar ###############################-->

                              <?php //include("sidebar_main.php");?>
                              <!-- end of side bar -->
                         </div><!-- end of container -->
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
