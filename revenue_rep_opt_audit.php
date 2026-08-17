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
{   //swap content begins where cv means div id name
     var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
     $(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
     $("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
     var url="scriptfile_a.php";
     var str;

     if(cv=='forget_password') 
     {
          $.post(url,{contentvar:cv},function(data){
               //alert(data);
               TINY.box.show(data,0,0,0,0);$(divid).html('').show();
               $("#roll").html('').show();
          });
     }

     if(cv=='pass_recovery_update') 
     {
          $.post(url,{contentvar:cv,uname:v,email:a},function(data){
               $(divid).html(data).show(); 
               $("#roll").html('').show();
          });
     }

     if(cv=='another') 
     {
          $.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  
               $(divid).html(data).show(); 

          });
     }

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
                    <?php if(isset($_REQUEST['query'])){ ?>
                         <h2>Queried Payment Vouchers Report Option</h2>
                    <?php }else{ ?>
                         <h2>Purchase Advance Report Option</h2>
                    <?php } ?>
                    <p>&nbsp;</p>
               </div><!-- end of content_title_box -->

               <div id="tooplate_content">

                    <div class="content_box">

                         <div class="content">
                              <!--<div class="title"><h2>Recent Updates</h2></div> -->
                              <div id="display"></div>
                              <div id="display2"></div>
                              <div id="roll"></div>
                              <p>
                                   <table width="100%">
                                        <tr>
                                             
                                             <?php if(isset($_REQUEST['query'])){ ?> 
                                                  <td align="center" bgcolor="#D6D6D6"><strong>QUERY REPORT</strong></td>
                                             <?php }else{ ?>
                                                  <td align="center" bgcolor="#D6D6D6"><strong>PURCHASE ADVANCE REPORT</strong></td>
                                             <?php } ?>
                                        </tr>
                                        <tr>
                                             <?php if(isset($_REQUEST['query'])){ ?> 
                                             <td align="center" bgcolor="#D6D6D6"><form name="frm" id="frm4" action="account_report_audit.php?id=audit_query" target="_blank" method="post">
                                                  <table width="70%" border="0">
                                                       <tr>
                                                            <td width="" align="left"><strong>Year</strong> <br />
                                                                 <select name="pyear3" id="pyear3" style="width:90%">
                                                                      <option selected="selected" value="">---</option>
                                                                      <?php
                                                                      for($i=date('Y');$i>=2015; $i--)
                                                                      {
                                                                           echo "<option value='$i'>$i</option>";
                                                                      }
                                                                      echo "</select>";
                                                                      ?>
                                                                 </select>
                                                                 <input type="hidden" name="mode2" id="mode2" value="<?php echo base64_encode($mode);?>"/></td>
                                                            </tr>
                                                            <tr>
                                                                 <th height="68" colspan="2"><input type="submit" name="button" id="button2" value="Display Query Report" class="btn"/></th>
                                                            </tr>
                                                       </table>
                                                       <div id="display7"></div>
                                                       <div id="display8"></div>
                                                       <div id="roll2"></div>
                                                  </form>
                                             </td>
                                             <?php }else{ ?>
                                             <td align="center" bgcolor="#E5E5E5">
                                                  <form name="frm" id="frm4" action="account_report_audit.php?id=pad_audit&r_val=<?=$_REQUEST['r_val']?>" target="_blank" method="post">
                                                       <table width="70%" border="0">
                                                            <tr>
                                                                 <td width="" align="left">Date Range <br />
                                                                      <!--select name="pyear2x" id="pyear2x" style="width:90%">
                                                                           <option selected="selected" value="">---</option>
                                                                           <?php
                                                                           for($i=date('Y');$i>=2015; $i--)
                                                                           {
                                                                                echo "<option value='$i'>$i</option>";
                                                                           }
                                                                           echo "</select>";
                                                                           ?>
                                                                      </select-->
                                                                      <input type="text" name="from" id="from" size="25" tabindex="3" class="easyui-datebox" /> - <input type="text" name="to" id="to" size="25" tabindex="3" class="easyui-datebox" />
                                                                      <input type="hidden" name="mode3" id="mode3" value="<?php echo base64_encode($mode);?>"/></td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td align="left">Retired :</br>
                                                                           <select name="retired" id="retired" style="width:90%">
                                                                                <option selected="selected" value="All">Total Advance</option>
                                                                                <option value="No">Unretired</option>
                                                                                <option  value="Yes">Retired</option>

                                                                           </select>
                                                                      </td>
                                                                 </tr>
                                                                 <tr>
                                                                      <td align="left">Faculty : </br>
                                                                           <select name="fact" id="fact" style="width:90%">
                                                                                <option selected="selected" value="">---...---</option>
                                                                                <?php  $q =  mysqli_query($con, "SELECT u.* FROM unittb u INNER JOIN journal_code j ON u.unit_code=j.unit_code ORDER BY u.unit_name");
                                                                                  while($r= mysqli_fetch_array($q, 3 )){
                                                                                      $unt =  $r['unit_code'];
                                                                                      echo '<option value="'. $unt.'">'. $r['unit_name'] .'</option>';
                                                                                  }
                                                                                ?>
                                                                           </select>
                                                                      </td>
                                                                 </tr>

                                                                 <th colspan="2"><input type="submit" name="button2" id="button3" value="Display Purchase Advance Register" class="btn"/></th>
                                                            </tr>
                                                       </table>
                                                       <div id="display9"></div>
                                                       <div id="display10"></div>
                                                       <div id="roll3"></div>
                                                  </form>
                                             </td>
                                             <?php } ?>
                                        </tr>
                                   </table>
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
