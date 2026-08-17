<?php

@session_start();
@require_once('connect.php');
@require_once('function.php');

@require_once('class/mysqli_class.php');
$db = new Database();
$db->connect();
@require_once 'myclass_m.php';
$bursary = new myclass_m();

$id=@$_REQUEST['contentvar'];
$contentvar=@$_REQUEST['contentvar'];

function smsalert($msg,$phoneno){
     $msg=@rawurlencode($msg);
     $phoneno="+234".@substr($phoneno,-10);
     $sender=@rawurlencode('UNILORIN');
     $r=@file_get_contents("http://api.smartsmssolutions.com/smsapi.php?username=jmklaru&password=0712764&sender=$sender&recipient=$phoneno&message=$msg");
}

if($id=='lock_posting'){
     $locktype=$_REQUEST['locktype'];
     $lockdate=$_REQUEST['lockdate'];
     if($locktype != ''){
          $sql="UPDATE lock_posting SET lockdate = '{$lockdate}-01' WHERE locktype = '{$locktype}'";
          if(mysqli_query($con, $sql)){
               echo "Lock updated!";
          }else{
               echo "Error, retry!";
          }
     }
}

if($id=='getLastPV')
{
     $type = $_REQUEST['type'];
     $typeSub = $_REQUEST['typeSub'];
     $year = $_REQUEST['year'];
     $yr=date('y', strtotime($year."-01-01"));
     //echo  "SELECT MAX(pre_pvno) FROM vouchertb WHERE (pre_pvno LIKE '%{$type}%' OR pvno_paid LIKE '%{$type}%') AND YEAR(date_prepared)='{$year}'";  OR pvno_paid LIKE '%{$type}%'
     $sq=mysqli_query($con, "SELECT MAX(pre_pvserial) FROM vouchertb WHERE pre_pvtype = '{$type}' AND pre_pvyear = '{$year}'"); //AND YEAR(date_prepared)='{$year}'");
     $rec=mysqli_fetch_array($sq, 3);
     //echo "<span style='font-size:18px; font-weight:bold;'> {$rec[0]} </span>";
     $pvserial = str_pad($rec[0], 4, '0', STR_PAD_LEFT);
     if($rec[0]>0) echo "<span style='font-size:18px; font-weight:bold;'> {$yr}/{$type}{$typeSub}{$pvserial} </span>";
     else echo "<span style='font-size:18px; font-weight:bold;'> No $type assigned </span>";
}

if($id=='save_pre_pvno')
{
     $vid = $_REQUEST['id'];
     $pvserial = str_pad($_REQUEST['pvno'], 4, '0', STR_PAD_LEFT);
     $type = $_REQUEST['type'];
     $typeSub = $_REQUEST['typeSub'];
     $year = $_REQUEST['year'];
     $yr=date('y', strtotime($year."-01-01"));
     $pvno = $yr."/".$type.$typeSub.$pvserial;

     if($pvserial==''){
          $sql="UPDATE vouchertb SET pre_pvno = '', pre_pvtype = '', pre_pvyear = '', pre_pvserial = '' WHERE id='{$vid}'";
          if(mysqli_query($con, $sql)){
               echo $pvno;
          }else{
               echo "Error, retry!";
          }
          exit;
     }
     
     if(!is_numeric($pvserial)){ 
          echo "<script>alert('Either the PVNO is empty or it is not in a correct format.');</script>";
          exit;
     }
     $sq=mysqli_query($con, "SELECT * FROM vouchertb WHERE pre_pvserial = '{$pvserial}' AND pre_pvtype = '{$type}' AND pre_pvyear = '{$year}'");
     if(mysqli_num_rows($sq) <= 0){
          $pv = $pvno;
          $vpvno=$bursary->get_any_value("pvno", "vouchertb", "id", $vid);
          $sqll=mysqli_query($con, "SELECT * FROM vouchertb WHERE (paid_action='' OR paid_action Is Null) AND pvno LIKE '{$vpvno}\_%' order by pvno desc");
          $v_num=mysqli_num_rows($sqll);
          if($v_num > 0) $pvno = $pvno."A";

          $sql="UPDATE vouchertb SET pre_pvno = '{$pvno}', pre_pvtype = '{$type}', pre_pvyear = '{$year}', pre_pvserial = '{$pvserial}', pre_pvdate=now() WHERE id='{$vid}'";
          if(mysqli_query($con, $sql)){
               if($v_num > 0) {
                    $sqll1=mysqli_query($con, "SELECT * FROM vouchertb WHERE (paid_action='' OR paid_action Is Null) AND pvno LIKE '{$vpvno}\_SD' order by pvno desc");
                    if(mysqli_num_rows($sqll1) > 0) {
                         $vi=mysqli_fetch_array($sqll1, 3); $vid2=$vi['id'];
                         @mysqli_query($con, "UPDATE vouchertb SET pre_pvno = '{$pv}B', pre_pvtype = '{$type}', pre_pvyear = '{$year}', pre_pvserial = '{$pvserial}', pre_pvdate=now() WHERE id='{$vid2}'");
                         $p .= "<br>".$pv."B";
                    }

                    $sqll2=mysqli_query($con, "SELECT * FROM vouchertb WHERE (paid_action='' OR paid_action Is Null) AND pvno LIKE '{$vpvno}\_VAT' order by pvno desc");
                    if(mysqli_num_rows($sqll2) > 0) {
                         $vi=mysqli_fetch_array($sqll2, 3); $vid2=$vi['id'];
                         @mysqli_query($con, "UPDATE vouchertb SET pre_pvno = '{$pv}C', pre_pvtype = '{$type}', pre_pvyear = '{$year}', pre_pvserial = '{$pvserial}', pre_pvdate=now() WHERE id='{$vid2}'");
                         $p .= "<br>".$pv."C";
                    }

                    $sqll3=mysqli_query($con, "SELECT * FROM vouchertb WHERE (paid_action='' OR paid_action Is Null) AND pvno LIKE '{$vpvno}\_WHT' order by pvno desc");
                    if(mysqli_num_rows($sqll3) > 0) {
                         $vi=mysqli_fetch_array($sqll3, 3); $vid2=$vi['id'];
                         @mysqli_query($con, "UPDATE vouchertb SET pre_pvno = '{$pv}D', pre_pvtype = '{$type}', pre_pvyear = '{$year}', pre_pvserial = '{$pvserial}', pre_pvdate=now() WHERE id='{$vid2}'");
                         $p .= "<br>".$pv."D";
                    }

                    $sqll4=mysqli_query($con, "SELECT * FROM vouchertb WHERE (paid_action='' OR paid_action Is Null) AND pvno LIKE '{$vpvno}\_PAYE' order by pvno desc");
                    if(mysqli_num_rows($sqll4) > 0) {
                         $vi=mysqli_fetch_array($sqll4, 3); $vid2=$vi['id'];
                         @mysqli_query($con, "UPDATE vouchertb SET pre_pvno = '{$pv}A', pre_pvtype = '{$type}', pre_pvyear = '{$year}', pre_pvserial = '{$pvserial}', pre_pvdate=now() WHERE id='{$vid2}'");
                         $p .= "<br>".$pv."A";
                    }
               }
               /*$sn=0;    $ext=array('A','B','C','D','E','F'); $p='';
               while($r=mysqli_fetch_array($sqll, 3)){
                    $pv=$pvno.$ext[$sn];
                    $vid2=$r['id'];
                    $p .= "<br>".$pv;
                    @mysqli_query($con, "UPDATE vouchertb SET pre_pvno = '{$pv}', pre_pvtype = '{$type}', pre_pvyear = '{$year}', pre_pvserial = '{$pvserial}', pre_pvdate=now() WHERE id='{$vid2}'");
                    $sn++;
               }*/
               echo $pvno.$p;
          }else{
               echo "Error, retry!";
          }
     }else{
          //echo $pvno;
          echo "<script>alert('The PVNO already exist.');</script>";
     }
     exit;
}

if($id=='save_pre_jvno')
{
     $jid = $_REQUEST['id'];
     $pvno = $_REQUEST['pvno'];
     $upvno = $_REQUEST['upvno'];
     $jvno = $_REQUEST['jvno'];
     $date = $_REQUEST['fdate'];
     $year = $_REQUEST['year'];
     $login_id=$_SESSION['login_id'];
     if(strlen($pvno)<=5){ 
          echo "<script>alert('Either the PVNO is empty or it is not in a correct format.');</script>";
          exit;
     }
     $sq=mysqli_query($con, "SELECT * FROM transtb WHERE pvno = '{$pvno}' AND YEAR(transdate) = '{$year}' AND folio_code='09-001-3087'");
     if(mysqli_num_rows($sq) <= 0){
          echo "<script>alert('PVNO. not found!.');</script>";
     }else{
          
          $bursary->begin();
          $trans=mysqli_fetch_array($sq, 3);

          $sql="UPDATE transtb SET retired='Yes', amount_retired = '{$trans['amount']}', date_retired = now(), time_retired = now(), retired_by='{$login_id}' WHERE pvno = '{$pvno}' AND YEAR(transdate)='{$year}'"; 
          if(mysqli_query($con, $sql)){
               $done=true;
          }else{
               $bursary->rollback();
               echo "Error, retry 1!";
               exit;
          }
          
          $sql="UPDATE vouchertb SET retired='Yes', amount_retired = '{$trans['amount']}', date_retired = now(), time_retired = now(), retired_by='{$login_id}', pvno_paid = '{$pvno}', paid_by='{$login_id}', paid_action='Approved', date_paid='{$trans['transdate']}' WHERE pvno = '{$upvno}' AND YEAR(date_paid)='{$year}'"; 
          if(mysqli_query($con, $sql)){
               $done=true;
          }else{
               $bursary->rollback();
               echo "Error, retry 2!";
               exit;
          }

          $sql="UPDATE journaltb SET pvno = '{$pvno}' WHERE id='{$jid}'"; 
          if(mysqli_query($con, $sql)){
               $done=true;
          }else{
               $bursary->rollback();
               echo "Error, retry 3!";
               exit;
          }
          
          $ss="SELECT * FROM pa_retirementtb WHERE jvno='{$jvno}'";
          $qz=mysqli_query($con, $ss);
          if(mysqli_num_rows($qz) <= 0){
               //Insert
               $sql="INSERT INTO pa_retirementtb SET jvno = '{$jvno}', pvno = '{$pvno}', amount_retired = '{$trans['amount']}', date_retired = now(), time_retired = now(), retired_by='{$login_id}'";
          }else{
               //Update
               $sql="UPDATE pa_retirementtb SET pvno = '{$pvno}', amount_retired = '{$trans['amount']}', date_retired = now(), time_retired = now(), retired_by='{$login_id}' WHERE jvno='{$jvno}'";
          }
          if(mysqli_query($con, $sql)){
               $done=true;
          }else{
               $bursary->rollback();
               echo "Error, retry!";
               exit;
          }
          if($done){
               $bursary->commit();
               echo $pvno;
          }else{
               $bursary->rollback();
               echo "Error, retry!";
          }
     }
     exit;
}

if($id=='display_voucher')
{
     $p=@$_REQUEST['p'];
     echo file_get_contents("voucher_report.php?p=$p");
}

if($id=='main_login')
{
     $login_id=@mysqli_real_escape_string($con, @$_REQUEST['username']);
     $password=@mysqli_real_escape_string($con, @$_REQUEST['password']);
     $pass_base=@base64_encode($password);


     //////////////////**************************** Login Section for Staff ******///////////////////////////
     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     //$res_l=@mysqli_query($con, "select s.title,s.surname,s.first_name,s.other_name,s.status,s.category from stafftb s,users_roletb r where s.fileno=r.fileno and s.fileno='$login_id' and s.password='$pass_base'");
     $res_l=@mysqli_query($con, "select s.* from stafftb s where s.fileno='$login_id' and s.password='$pass_base'");
     $rs_l=@mysqli_fetch_array($res_l);
     if(@mysqli_num_rows($res_l)>=1)
     {
          $login_status=@$rs_l['status'];
          if($login_status=='Active')
          {
               //////project title fetching
               $res_p=@mysqli_query($con, "SELECT * FROM project_titletb where status='Active'");
               $rs_p=@mysqli_fetch_array($res_p);
               $title=@$rs_p['title'];
               $_SESSION['project_title']=$title;

               //
               $_SESSION['userunit'] = @$rs_l['unit_code'];
               $_SESSION['title']=@$rs_l['title'];
               $_SESSION['surname']=@$rs_l['surname'];
               $_SESSION['first_name']=@$rs_l['first_name'];
               $_SESSION['other_name']=@$rs_l['other_name'];
               $_SESSION['last_login_date']=@$rs_l['last_login_date'];
               $_SESSION['last_login_time']=@$rs_l['last_login_time'];
               $_SESSION['staff_category']=@$rs_l['category'];

               $_SESSION['login_status']='staff';
               $_SESSION['role']='Personal';
               $_SESSION['userLogin']='ok';

               $_SESSION['login_id']=$login_id;
               /*
               $log_date=date('Y-m-d');
               $log_time=date('h:i:s a');
               $log_date2=date('l, F d, Y');
               @mysqli_query($con, "INSERT INTO portal_logstb set regno='$login_id',log_type='Portal Login',log_desc='$login_id Login',log_date='$log_date',log_date_desc='$log_date2',log_time='$log_time',entry_by='$login_id'");

               $log_date=date('l, F d, Y');
               $log_time=date('h:i:s a');
               $log_date2=date('Y-m-d');

               //@mysqli_query($con, "update stafftb set last_login_date='$log_date',last_login_time='$log_time',online_status='On' where fileno='$login_id' limit 1");
               //@mysqli_query($con, "update stafftb set online_status='On' where fileno='$login_id' limit 1");
               */
               echo "<script>location='main.php';</script>";
               exit;

          } //end of active staff
          else
          {
               echo "<br/><div class='error_msg'>You are not an active user.</div>";
               exit;
          }

          //echo "Fac: $fac_name Dept: $dept_name status: $login_status";
     } //end of staff found

     /////////////////////************************************ Login Section for Staff **********////////////////////////
     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     //after every login attempt
     echo "<script>alert('Invalid login parameters');</script>";
     ////echo "<div class='error_msg'>Invalid login parameters</div>";
     exit;
} //end of main login for staff and student

if($id=="load_dept_account"){
     echo '<select name="funddept" id="funddept" onChange="swapcontent(\'load_items_code\');" style="width:120px;">
     <option selected="selected" value="">---</option>';


     $res_c=@mysqli_query($con, "SELECT * FROM account_departments where department_category='". mysqli_real_escape_string($con, $_REQUEST['category'])."' order by department_code");
     while($rs_c=@mysqli_fetch_array($res_c))
     {
          $dept_code=@$rs_c['department_code']; //$dept_code=@$rs_c['dept_code'];
          $dept_name=@$rs_c['department_name']; //$dept_name=@$rs_c['dept_name'];
          echo "<option value='$dept_code'>$dept_name ($dept_code)</option>";
     }
     echo ' </select>';
     exit;
}

if($id=="load_items_code"){
     echo '<select name="funditem" id="funditem" onChange="reload_folio();" style="width:120px;">
     <option selected="selected" value="">---</option>';


     $res_c=@mysqli_query($con, "select distinct itemcode, title as codetitle from foliotb where category='". mysqli_real_escape_string($con, $_REQUEST['category'])."' order by itemcode");
     //fundcenter='". mysqli_real_escape_string($con, $_REQUEST['fundcenter'])."' and deptcode='". mysqli_real_escape_string($con, $_REQUEST['deptcode'])."'
     while($rs_c=@mysqli_fetch_array($res_c))
     {
          $dept_code=@$rs_c['itemcode']; //$dept_code=@$rs_c['dept_code'];
          $dept_name=@$rs_c['codetitle']; //$dept_name=@$rs_c['dept_name'];
          echo "<option value='$dept_code'>$dept_name ($dept_code)</option>";
     }
     echo ' </select>';
     exit;
}

if($id=="load_category" || $id=="load_category_r"){
     $fundcenter = $_REQUEST['fundcenter'];
     echo '<select name="fundcat" id="fundcat" onChange="swapcontent(\'load_items_code\');" style="width:120px;">
     <option selected="selected" value="">---</option>';
     //$res_c=@mysqli_query($con, "SELECT * FROM departmenttb order by dept_name");
     ///$res_c=@mysqli_query($con, "select distinct c.id, c.folio_category from folio_categorytb c inner join account_codes f on c.id=f.category where f.folio_code like '{$fundcenter}-%' order by folio_category");
     if($id=="load_category")
     $res_c=@mysqli_query($con, "select distinct category from foliotb where fundcenter = '".$fundcenter."' order by category");
     elseif($id=="load_category_r")
     $res_c=@mysqli_query($con, "select distinct category from foliotb where fundcenter = '".$fundcenter."' order by category"); // and category != ''in ('01-000C', '09-000C')

     while($rs_c=@mysqli_fetch_array($res_c))
     {
          $dept_code=@$rs_c['category']; //$dept_code=@$rs_c['dept_code'];
          $dept_name= $bursary->get_any_value('folio_category', 'folio_categorytb', 'id', @$rs_c['category']); //$dept_name=@$rs_c['dept_name'];
          if($dept_name != '') echo "<option value='$dept_code'>$dept_name</option>";
     }
     echo "</select>";
     exit;
}

if($id == "rgrid")
{
     if(isset($_REQUEST['category']) and $_REQUEST['category']!=''){
          $deptcode =  mysqli_real_escape_string($con, $_REQUEST['deptcode']);
          $fundcenter =  mysqli_real_escape_string($con, $_REQUEST['fundcenter']);
          $category =  mysqli_real_escape_string($con, $_REQUEST['category']);

          if($deptcode != '') $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.deptcode='".$deptcode."' AND f.itemcode LIKE '1%' ";
          else{
               if($category != '') $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='".$fundcenter."' and f.deptcode='".$deptcode."' AND f.itemcode LIKE '1%' order by f.category, f.title";
               else $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.category='$category' AND f.itemcode LIKE '1%'";
          }
          //$sql .= $sql.$where." order by f.category, f.title";

          $r= mysqli_query($con, $sql);

          ///$r= mysqli_query($con, "select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='".$fundcenter."' and f.deptcode='".$deptcode."' order by f.category, f.title");
     } /*else and category='$category'  or folio_code like concat('$fundcenter', '-XXX-%'))
     $r= mysqli_query($con, "select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' order by category, f.title");
     /*echo "<script>alert('Hahaha!');</script>"; */
     $json_response=array();
     while ($row =  mysqli_fetch_array($r, 3 )) {
          $row_array['folio_code'] = $row['folio_code'];
          $row_array['title'] = $row['title'];
          $row_array['category'] = $row['category'];
          $row_array['ncoa_code'] = $row['ncoa_code'];
          $row_array['ncoa_title'] = $row['ncoa_title'];
          $row_array['categoryF'] = $row['categoryF'];
          array_push($json_response, $row_array);
     }//end of while
     echo json_encode($json_response);
     exit;
} //end of load data into combogrid

if($id == "staffGrid")
{
     $res_s=@mysqli_query($con, "select fileno,surname,first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') order by surname");
     $json_response=array();
     while ($row =  mysqli_fetch_array($res_s, 3 )) {
          $row_array['fileno'] = $row['fileno'];
          $name=strtoupper($row['surname']).",".strtolower(ucfirst($row['first_name']))." ".strtolower(ucfirst($row['other_name']));
          $row_array['staffname'] = $name;
          array_push($json_response, $row_array);
     }//end of while
     echo json_encode($json_response);
     exit;
}

if($id == "grid")
{
     if(isset($_REQUEST['category']) and $_REQUEST['category']!=''){
          $deptcode =  mysqli_real_escape_string($con, $_REQUEST['deptcode']);
          $fundcenter =  mysqli_real_escape_string($con, $_REQUEST['fundcenter']);
          $category =  mysqli_real_escape_string($con, $_REQUEST['category']);
          if($deptcode != '') $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.deptcode='".$deptcode."' ";
          else{
               if($category == "01-000C") $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='".$fundcenter."' and f.deptcode='".$deptcode."' order by f.category, f.title";
               else $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.category='$category'";
          }
          //$sql .= $sql.$where." order by f.category, f.title";

          $r= mysqli_query($con, $sql);

          ///$r= mysqli_query($con, "select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='".$fundcenter."' and f.deptcode='".$deptcode."' order by f.category, f.title");
     } /*else and category='$category'  or folio_code like concat('$fundcenter', '-XXX-%'))
     $r= mysqli_query($con, "select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' order by category, f.title");
     /*echo "<script>alert('Hahaha!');</script>"; */
     $json_response=array();
     while ($row =  mysqli_fetch_array($r, 3 )) {
          $row_array['folio_code'] = $row['folio_code'];
          $row_array['title'] = $row['title'];
          $row_array['category'] = $row['category'];
          $row_array['ncoa_code'] = $row['ncoa_code'];
          $row_array['ncoa_title'] = $row['ncoa_title'];
          $row_array['categoryF'] = $row['categoryF'];
          array_push($json_response, $row_array);
     }//end of while
     echo json_encode($json_response);
     exit;
} //end of load data into combogrid


if($id == "receipt_grid_xxx")
{
     $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='".$fundcenter."' and f.itemcode LIKE '10%' order by f.category, f.title limit 10";

     $r= mysqli_query($con, $sql);

     $r= mysqli_query($con, "select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' order by category, f.title");
     /*echo "<script>alert('Hahaha!');</script>"; */
     $json_response=array();
     while ($row =  mysqli_fetch_array($r, 3 )) {
          $row_array['folio_code'] = $row['folio_code'];
          $row_array['title'] = $row['title'];
          $row_array['category'] = $row['category'];
          $row_array['ncoa_code'] = $row['ncoa_code'];
          $row_array['ncoa_title'] = $row['ncoa_title'];
          $row_array['categoryF'] = $row['categoryF'];
          array_push($json_response, $row_array);
     }//end of while
     echo json_encode($json_response);
     exit;
} //end of load data into combogrid

if($id=='folio_code_breakdown')
{
     $code=@$_REQUEST['folio'];
     if(count($code)>1)
     {
          echo"<font color='red'><table width='100%' border=1 rules='rows'><tr><th>SN</th><th>Code</th><th>Description</th><th width='5%'>Amount(&#8358;)</th></tr>"; $sn=1;
          foreach($code as $code_val)
          {
               $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$code_val);
               if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
               echo"<tr style='font-size:12px'><td>".$sn++."</td><td nowrap><input type='hidden' name='bcode[]' value='$code_val'> $code_val: </td><td style='font-size:12px'>".get_folio_name($code_val)." $ncoa_codex</td><td width='5%'><input type='text' class='amt2' name='bamt[]' onblur=\"sum2('amt2')\" style='background-color: #FEFFB0;font-weight: bold;text-align: right;'></td></tr>";
          }

          echo"</table></font>";
     }
     exit;
}// end of folio_code_breakdown

if($id=='foliodesc')
{
     $code_val = $_REQUEST['folio'];
     if($code_val == ''){
          echo 'Null';
          echo '<input type="hidden" name="fdesc[]" value="null">';
     }else{
          if(get_folio_name($code_val) == ''){
               echo 'Invalid Code';
               echo '<input type="hidden" name="fdesc[]" value="Invalid">';
          }
          else{
               $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$code_val);
               if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
               echo get_folio_name($code_val).$ncoa_codex;
               echo '<input type="hidden" name="fdesc[]" value="'.get_folio_name($code_val).$ncoa_codex.'">';
          }
     }
     exit;
}

if($id=='folio_code_breakdown_rec')
{
     $code=@$_REQUEST['folio'];
     echo"<font color='red'><table width='100%' border=1 rules='rows'><tr><th>SN</th><th width='5%'>Code</th><th>Description</th><th>Amount(&#8358;)</th></tr>";
     if(count($code) > 1)
     {
          $sn=1;
          foreach($code as $code_val)
          {
               $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$code_val);
               if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
               echo"<tr style='font-size:12px'><td>".$sn++."</td><td nowrap><input type='hidden' name='bcode[]' value='$code_val'> $code_val: </td><td style='font-size:12px'>".get_folio_name($code_val)." $ncoa_codex</td><td width='5%'><input type='text' class='amt2' name='bamt[]' onblur=\"sum2('amt2')\" style='background-color: #FEFFB0;font-weight: bold;text-align: right;'></td></tr>";
          }
          echo "<tr><th></th><th></th><th>TOTAL AMOUNT (&#8358;)</th><th width='5%'><input type='number' name='vamount' id='vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' ></th></tr>";
     }else{
          $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$code[0]);
          if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
          echo "<tr><td>1</td><td nowrap>".$code[0]."</td><td>".get_folio_name($code[0])." $ncoa_codex</th><th width='5%'><input type='number' name='vamount' id='vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' ></td></tr>";
     }
     echo"<tr><th colspan='4' height='33'>"; ?> <input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('receipt_section_entry','save'); " /> | <input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('receipt_section_entry','refresh');" /> <?php echo "</th></tr>";
     echo"</table></font>";
     exit;
}// end of folio_code_breakdown_rec

if($id=='receipt_section_entry')
{
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date']));
     $dept=@$_REQUEST['funddept'];
     $account=@$_REQUEST['account'];
     $folio=@$_REQUEST['folio'];
     $vamount=@$_REQUEST['vamount'];

     $voucher_unit=@$_REQUEST['voucher_unit'];
     $bcode=@$_REQUEST['bcode'];
     $bamt=@$_REQUEST['bamt'];
     //$autocreate=@$_REQUEST['autocreate'];
     $login_id=@$_SESSION['login_id'];
     //$memo_id=@$_REQUEST['memo_id'];
     $amt_approved=@$_REQUEST['amt_approved'];
     $process_type=$_REQUEST['operation'];

     $vcode=@$_REQUEST['code'];
     $vamt=@$_REQUEST['amount'];  //code is the folio_code and rate


     if($action=='save')
     {
          if($amt_approved != $vamount)
          {
               echo "<script language='javascript'>alert('Cross check your entry! Amount Approved is not the same with Amount entered');</script>";exit;
          }
          //$amt_sub_sum = 0;
          foreach($vamt as $amt)
          {
               //$amt_sub_sum += $amt;
               if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
               {
                    echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
               }
          }
          /*if($amt_approved != $amt_sub_sum)
          {
          echo "<script language='javascript'>alert('Cross check your entry! Amount Approved is not the same with Amount entered');</script>";exit;
     }
     */


     if(count($bcode)>0)
     {
          $amt_sub_sum = 0;
          foreach($bamt as $val_amt)
          {
               $amt_sub_sum += $val_amt;
               if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
               {
                    echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
               }
          }//end of foreach for bamt
          if($amt_approved != $amt_sub_sum)
          {
               echo "<script language='javascript'>alert('Cross check your entry! Amount Approved is not the same with Amount entered');</script>";exit;
          }
     }// end of bcode is not empty

     // End of Validation
     $s=0;$i=0;$j=0;$tamt=0; /*$emsg=array();$total_tax=0;  $amount=0;*/
     // transaction begins
     begin();

     $year=@date('Y',strtotime(@$_REQUEST['pay_date']));
     //echo $amt_approved; exit;
     begin();
     //$receiptno = rand(10, date('Ymds'));
     if($process_type=="payment") {
          $trans_type = "Debit";
          $receiptno = " pvno = '". $_REQUEST['pvno']. "', ";
     }
     elseif($process_type=="receipt") {
          $srec =  mysqli_query($con, "SELECT DISTINCT(receiptno) FROM transtb WHERE receiptno != ''");
          $receiptno =  " receiptno='".str_pad( mysqli_num_rows($srec), 7, '0', STR_PAD_LEFT). "', ";
          $trans_type = "Credit";
     }

     if(count($folio)==1){
          $sql[] = @mysqli_query($con, "INSERT INTO transtb set dept_acctcode='', acctcode='".$account."', folio_code='".$folio[0]."', transtype='".$trans_type."', transdate='".$pay_date."', amount=".$amt_approved.", ".$receiptno." entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'");
     }
     else
     {
          if(count($bcode)>1)
          {
               foreach($bcode as $v)
               {
                    $sql[] = @mysqli_query($con, "INSERT INTO transtb set dept_acctcode='', acctcode='".$account."', folio_code='".$v."', transtype='".$trans_type."', transdate='".$pay_date."', amount=".$bamt[$s].", ".$receiptno." entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'");
                    $s++;
               }
          }
     }
     $flag=false;
     foreach($sql as $sq){
          if($sq) $flag=true;
          else{
               $flag=false;
               break;
          }
     }
     if($flag){
          commit();
          echo "<script>alert('Record saved successfully');</script>";
     }
     else
     {
          rollback();
          echo "<script>alert('Operation Failed! Transaction was canceled. ". mysqli_error($con)."');</script>";
     }

     //logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");

     //************** Commite the Transactions
}// end of save

if($action=='delete')
{
     $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
     $pvno=$rs_d['pvno'];
     logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");
     begin();
     if( mysqli_query($con, "DELETE FROM vouchertb where id='$r_id'") and  mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_folio_codetb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_parent_child_taxtb where child_pvno='$pvno'")) {
          commit();
          echo "<script>alert('Record deleted successfully');</script>";
     }else {
          rollback();
          echo "<script>alert('Error deleting record!');</script>";
     }
     $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";

     $action="view";
}

if($action=='view')
{
     $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
}

/////////////////////view section ////////////////////
/*$sn=0;
$res_v=@mysqli_query($con, $sql);
$g_total=0;
$tb="<table border='1' rules='all' frame='box'><tr><th colspan='8' align='center'>Prepared Voucher</th></tr><tr><th>S/N</th><th>PV NO.</th><!--<th>PV NO.</th>--><th>FOLIO</th><th>DEPARTMENT</th><th>DATE</th><th>PAYEE</th><th>AMOUNT</th><th>ACTION</th></tr>";
if(@mysqli_num_rows($res_v)>=1)
{

while($rs_v=@mysqli_fetch_array($res_v))
{
++$sn;
$r_id=$rs_v['id'];
$g_total+=$rs_v['amount_paid'];
$tb.="<tr><td>$sn</td><td>{$rs_v['pvno']}</td><!--<td>{$rs_v['pvno_paid']}</td>--><td>".@get_folio_name($rs_v['item_code'])."</td><td>".@get_dept_name_act($rs_v['dept_code'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_entry','delete','$r_id');\">DELETE</a></td></tr>";
}//end of while

$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
$tb.="<tr><td colspan='6' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
$tb.="</table>";
echo $tb_s.$tb;
}
else
echo "<b>No record to display</b>";*/

exit;
}// end of voucher_section

if($id=='journal_voucher_section')
{

     //$mydata=@$_REQUEST['mydata'];
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     if($r_id !="")
     {
          $d=@mysqli_query($con, "SELECT * FROM vouchertb where id = '$r_id'");
          $ds=@mysqli_fetch_array($d);
          $d_pvno=@$ds['pvno']; $d_payee_name=@$ds['payee_name']; $d_fileno=@$ds['fileno'];

          $r_ids=@$ds['id'];
     }

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date'])); $dept=@$_REQUEST['dept']; $pvno=@$_REQUEST['pvno'];
     $account=@$_REQUEST['account']; $folio=@$_REQUEST['folio']; $type=@$_REQUEST['type'];
     $fileno=@$_REQUEST['fileno']; $name=@$_REQUEST['name']; $act_no=@$_REQUEST['act_no']; $bank=@$_REQUEST['bank'];
     $address=@mysqli_real_escape_string($con, @$_REQUEST['address']); $vamount=@$_REQUEST['vamount']; $desc=@mysqli_real_escape_string($con, @$_REQUEST['desc']);
     $payee_tin_number=@mysqli_real_escape_string($con, @$_REQUEST['payee_tin_number']);
     $payee_sort_code=@mysqli_real_escape_string($con, @$_REQUEST['payee_sort_code']);
     $voucher_unit=@$_REQUEST['voucher_unit'];
     $bcode=@$_REQUEST['bcode'];$bamt=@$_REQUEST['bamt'];
     $autocreate=@$_REQUEST['autocreate'];
     $login_id=@$_SESSION['login_id'];
     $memo_id=@$_REQUEST['memo_id'];
     $amt_approved=@$_REQUEST['amt_approved'];

     /*	foreach($folio as $v)
     echo "$v  ==><br>";
     foreach($bamt as $v)
     echo "$v<br>";
     foreach($bcode as $v)
     echo "$v<br>";

     echo $autocreate;
     //print_r ($folio);
     $tax_detail=@get_tax_detail('7303');
     echo $tax_detail['payee_type']."<=>".$tax_detail[5];
     exit();
     */
     $vcode=@$_REQUEST['code'];$vamt=@$_REQUEST['amount'];  //code is the folio_code and rate

     //$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
     //echo "$vcode ==> $vamt===>$mydata";exit;
     if($action=='save')
     {
          if($amt_approved != $vamount)
          {
               echo "<script language='javascript'>alert('Cross check your entry! Amount Approved is not the same with Amount entered');</script>";exit;
          }
          foreach($vamt as $amt)
          {
               if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
               {
                    echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
               }
          }
          if(count($bcode)>0)
          {
               foreach($bamt as $val_amt)
               {
                    if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
                    {
                         echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
                    }
               }//end of foreach for bamt
          }// end of bcode is not empty
          // End of Validation
          $s=0;$i=0;$j=0;$tamt=0;$emsg=array(); $total_tax=0; $amount=0;
          // transaction begins
          begin();


          //now save to voucher table

          $amount_approved=$vamount;
          $amount_paid=$amount_approved - $total_tax;  //after tax deduction
          $year=@date('Y',strtotime(@$_REQUEST['pay_date']));

          $total_budget=@get_budget($folio,$year);
          if( ($amount_paid <= $total_budget) or $total_budget=='' )
          {

               $r1=@mysqli_query($con, "INSERT INTO vouchertb set memo_id='$memo_id',pvno='$pvno',voucher_date='$pay_date',dept_code='$dept',dept_acctcode='$account',payee_type='$type',fileno='$fileno',payee_name='$name',payee_acct_no='$act_no',payee_bank_name='$bank',payee_address='$address',payee_tin_number='$payee_tin_number',payee_sort_code='$payee_sort_code',description='$desc',amount_approved='$amount_approved',total_tax='$total_tax',amount_paid='$amount_paid',prepared_by='$login_id',date_prepared=CURDATE(),time_prepared=CURTIME(),entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               //folio_code='$folio',
               if(count($folio)==1)
               $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$folio[0]',amount='$amount_approved', paid='No'");
               else
               {
                    if(count($bcode)>1)
                    {
                         foreach($bcode as $v)
                         {
                              $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='No'");
                              $s++;
                         }
                    }
               }

               if($autocreate=='yes')
               {
                    if(count($vcode)>0)
                    {

                         foreach($vcode as $codeval)  //code for tax
                         {
                              $line=$i+1;
                              if($codeval !="")
                              {
                                   $code=@explode("***",$codeval);
                                   $tax_folio_code=$code[0];
                                   $amount=$vamt[$code[2]];
                                   $total_tax+=$amount;
                                   $j++;
                                   $pvno2=$pvno."/$j";
                                   $tax_detail=@get_tax_detail($tax_folio_code);

                                   $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set memo_id='$memo_id',pvno='$pvno2',voucher_date='$pay_date',dept_code='$dept',dept_acctcode='$account',payee_type='$tax_detail[4]',payee_name='$tax_detail[5]',payee_acct_no='$tax_detail[6]',payee_bank_name='$tax_detail[7]',payee_address='$tax_detail[8]',payee_tin_number='$tax_detail[9]',payee_sort_code='$tax_detail[10]',description='Deduction for: $desc',amount_approved='$amount',amount_paid='$amount',prepared_by='$login_id',date_prepared=CURDATE(),time_prepared=CURTIME(),entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
                                   //folio_code='$tax_folio_code',
                                   $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb set parent_pvno='$pvno',child_pvno='$pvno2'");
                                   $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb SET pvno='{$pvno2}',folio_code='{$tax_folio_code}',amount='{$amount}', paid='No'");


                              }//end of amount is not empty
                              $i++;

                         }// end of foreach folio code

                    }// end of folio code is not empty for tax deduction
               }// end of autocreate tax record is yes
               else
               {
                    $i=0;$j=0;
                    if(count($vcode)>0)
                    {

                         foreach($vcode as $codeval)  //code for tax
                         {
                              $line=$i+1;
                              if($codeval !="")
                              {
                                   $code=@explode("***",$codeval);
                                   $tax_folio_code=$code[0];
                                   $amount=$vamt[$code[2]];
                                   $total_tax+=$amount;
                                   $j++;
                                   $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno',folio_code='$tax_folio_code',amount='$amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");


                              }//end of amount is not empty
                              $i++;

                         }// end of foreach folio code

                    }// end of folio code is not empty for tax deduction
               }//end of else part of autocreate is not ==yes

               logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");


               $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
               //************** Commite the Transactions
               $flag=false;
               //echo  "==>$flag<==$r1###$r2%%%%<br>";
               if($r1)//and $r2and $r3
               {
                    $flag=true;
                    //echo  "==>1$flag<==<br>";
                    foreach($r2 as $r_val2)
                    {
                         if($r_val2)
                         $flag=true;
                         else{
                              $flag=false;
                              break;
                         }
                    }
                    //echo  "==>2$flag<==<br>";
                    if($autocreate=="yes")
                    {

                         foreach($r4 as $r_val)
                         {
                              if($r_val)
                              $flag=true;
                              else{
                                   $flag=false;
                                   break;
                              }
                         }
                         //echo  "==>4$flag<==<br>";
                         if($flag)
                         {
                              foreach($r5 as $r_val5)
                              {
                                   if($r_val5)
                                   $flag=true;
                                   else{
                                        $flag=false;
                                        break;
                                   }
                              }
                              //echo  "==>5$flag<==<br>";
                         }


                    }// end of if($autocreate=="yes")

                    else
                    {
                         foreach($r6 as $r_val6)
                         {
                              if($r_val6)
                              $flag=true;
                              else{
                                   $flag=false;
                                   break;
                              }
                         }
                         //echo  "==>6$flag<==<br>";
                    }//end of not if($autocreate=="yes")


               }// end of if($r1 and $r2 and $r3)


               //echo  "==>7$flag<==";
               if($flag and  mysqli_query($con, "update memo_assigntb set status='Completed',datecompleted=CURDATE() WHERE memo_id='{$memo_id}'"))
               {
                    commit();
                    echo "<script>alert('Payment Voucher saved successfully');</script>";
               }
               else
               {
                    rollback();
                    echo "<script>alert('Operation Failed! Transaction was canceled');</script>";
               }


          }
          else
          {
               echo "<script>alert('Error: You have overshoot the budget for this account. Your payment voucher cannot be saved');</script>";
               //@mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'");
               $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
          }




     }// end of save




     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
          $pvno=$rs_d['pvno'];
          logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");

          @mysqli_query($con, "DELETE FROM vouchertb where id='$r_id'");
          @mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'");
          $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='view')
     {
          $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<table border='1' rules='all' frame='box'><tr><th colspan='7' align='center'>Prepared Voucher</th></tr><tr><th>S/N</th><th>PROCESS NO.</th><!--<th>PV NO.</th><th>FOLIO</th>--><th>DEPARTMENT</th><th>DATE</th><th>PAYEE</th><th>AMOUNT</th><!--th>ACTION</th--></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $g_total+=$rs_v['amount_paid'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['pvno']}</td><!--<td>{$rs_v['pvno_paid']}</td><td>".@get_folio_name($rs_v['folio_code'])."</td>--><td>".@get_dept_name($rs_v['dept_code'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><!--td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section','delete','$r_id');\">DELETE</a></td--></tr>";
          }//end of while

          $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          $tb.="<tr><td colspan='5' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";
     exit;

}// end of voucher_section

if($id=='getJVNo'){
     $junit=explode("***", $_REQUEST['junit']);
     //$accountCode=$_REQUEST['account'];
     //$uprefix=array(3=>"AJV", 4=>"BESTJV", 5=>"BJV", 7=>"SJV", 8=>"TJV", 9=>"CJV", 10=>"FJV", 11=>"SLAJV", 12=>"RJV", 13=>"CPUJV");
     $year=@date('y',strtotime(@$_REQUEST['pay_date']));
     $year2=@date('Y',strtotime(@$_REQUEST['pay_date']));
     //$month=strtoupper(@date('M',strtotime(@$_REQUEST['pay_date'])));
     $prefix = $junit[2]; //$bursary->get_any_value("jv_code", "journal_code", "unit_code", $accountCode);
     $rs="SELECT count(*) as cnum FROM journaltb WHERE YEAR(journal_date) = '{$year2}' AND dept_code = '{$junit[0]}'";// AND pvno_paid != '' AND pvno NOT LIKE '%\_%'";
     $qr= mysqli_query($con, $rs);
     //rand(1, 1000000000);
     $sn =  mysqli_fetch_array($qr); $num = rand(1, 1000000000); //$sn[0] + 1; or $_REQUEST['account']=''
     if($_REQUEST['junit']=='' or $_REQUEST['pay_date']=='') echo '';
     else {
          //echo $pvno_paid = $uprefix[$junit[0]].'/'.$year.'/'.$prefix.'/'.$month.str_pad($num, '3', '0', STR_PAD_LEFT);
          echo $pvno_paid = str_replace('//', '/', ('PAJV'.$year.'/'.$prefix.'/'.str_pad($sn['cnum']+1, 3, '0', STR_PAD_LEFT)) );
     }
     exit;
     }


if($id=='journal_section_entry_new')
{
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date']));
     $pay_year=@date('Y',strtotime(@$_REQUEST['pay_date']));
     $depts=@explode("***",$_REQUEST['journal_unit']);
     $dept = $depts[0];
     $dept_name = substr($depts[1], 0, 1);
     $account=@$_REQUEST['account'];
     $folio=@$_REQUEST['folio'];
     $dr_vamount=@$_REQUEST['dr_vamount'];
     $cr_vamount=@$_REQUEST['cr_vamount'];

     $journal_unit=@$_REQUEST['journal_unit'];
     $bcode=@$_REQUEST['bcode'];
     $payee_name=@$_REQUEST['pa_payee'];
     $fdesc=@$_REQUEST['fdesc'];
     $cr_bamt=@$_REQUEST['cr_bamt'];
     $dr_bamt=@$_REQUEST['dr_bamt'];
     $desc=@$_REQUEST['narration'];
     $login_id=@$_SESSION['login_id'];
     $pvno=@$_REQUEST['pvno']; 
     $ipvno=@$_REQUEST['ipvno']; 
     $pacode=@$_REQUEST['pa_code'];
     $amt_approved=@$_REQUEST['amt_approved'];
     $save_type=$_REQUEST['pro_type'];
     $bankpost=$_REQUEST['interbank'];
     $paid_jv_no=@$_REQUEST['jv_no'];
     $vcode=@$_REQUEST['code'];
     $vamt=@$_REQUEST['amount'];  //code is the folio_code and rate
     $batchno=@$_REQUEST['batchno'];
     $pv_paid = $_REQUEST['pv_paid'];

     if($action=='save')
     {
          if($dr_vamount - $cr_vamount != 0)
          {
               echo "<script language='javascript'>alert('Cross check your entry! Amount values entered for Cr and Dr does not match!');</script>";
               exit;
          }
          if($pv_paid == '0'){
               //TREASURY OR CASH-OFFICE
               $acctcode=$_REQUEST['account'];
               ///$payDate=$_REQUEST['pay_date'];
               $cheque_no="No Cheque";//$_REQUEST['cheque_no'];
               /////////////////////////////Generate Voucher PV real Number here ////////////////////////////////
               ////$pay_date=$paydate;                          //@date('Y-m-d');
               $month_name=@date('F',strtotime($pay_date)); $month_no=@date('m',strtotime($pay_date));
               $year=@date('Y',strtotime($pay_date));
               $res_p=@mysqli_query($con, "SELECT count(*) as total from vouchertb where month(date_paid)='$month_no' and year(date_paid)='$year'");
               $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d", $rs_p['total'] + 1);
               
               $d=@mysqli_query($con, "SELECT * FROM transtb WHERE pvno='{$pvno}' AND year(transdate)='{$pay_year}' AND amount='{$cr_vamount}'");
               $countpv = @mysqli_num_rows($d);
               if($countpv <= 0)
               {
                    
                    ////////////////////////////End of generate voucher PV real Number //////////////////////////////
                    begin();
                    
                    $sql="UPDATE vouchertb set pvno_paid='{$pvno}', acctcode='{$acctcode}', paid_by='{$login_id}', paid_action='Approved', paid_remark='From Advance', date_paid='{$pay_date}', time_paid=CURTIME(), batchno='' where pvno='{$ipvno}'";
                    $isPA=$bursary->get_any_value("purchase_advance", "vouchertb", "pvno", $ipvno);
                    mysqli_query($con, $sql) or die( mysqli_error($con));

                    $sqls="UPDATE vouchertb set dept_vou='$journal_unit' where pvno='{$ipvno}' AND dept_vou IS NULL";
                    mysqli_query($con, $sqls) or die( mysqli_error($con));
                    
                    $sql2 = "UPDATE `budget_votebooktb` set status = 'PAID' where voucher_pvno = '".$ipvno."'";
                    
                    
                    $sqq= mysqli_query($con, "SELECT * FROM vouchertb WHERE pvno='{$ipvno}'");
                    if($rr= mysqli_fetch_array($sqq, 3 )){
                         $sqq2 = mysqli_query($con, "SELECT * FROM voucher_folio_codetb WHERE pvno='{$ipvno}'");
                         while($rr2= mysqli_fetch_array($sqq2, 3 )){
                              $adv = $bursary->get_any_value("folio_code", "advancetb", "folio_code", $rr2['folio_code']);
                              if($adv != '') $isPA = 'Yes';
                              $sql3 = "INSERT INTO transtb set dept_acctcode='{$rr['dept_code']}', acctcode='{$acctcode}', folio_code='{$rr2['folio_code']}', transtype='Debit', transdate='{$pay_date}', amount='{$rr2['amount']}', paybatch='{$cheque_no}', pvno='{$pvno}', comment='{$comment}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='{$isPA}'";
                              
                              if( mysqli_query($con, $sql3)) {
                                   //check if voucher is used to pay for staff loan the update loan table
                                   $lnq= mysqli_query($con, "SELECT * FROM hr_loan_apptb WHERE loan_no = '$ipvno'");
                                   $lnnum= mysqli_num_rows($lnq);
                                   if($lnnum > 0){
                                        $sql4 = "UPDATE hr_loan_apptb SET process_status='Processed', process_date=Now(), payment_status='Paid' WHERE loan_no = '$ipvno'";
                                        if( mysqli_query($con, $sql4)) $ERT=true;
                                        else{
                                             rollback();
                                             echo "<script>alert('Error :: Payment Update failed!');</script>";
                                             exit;
                                        }
                                   }
                              }else {
                                   rollback(); 
                                   echo "<script>alert('Loop:::Payment Update failed!');</script>"; 
                                   exit;
                              }
                         }
                    }
                    if( mysqli_query($con, $sql) &&  mysqli_query($con, $sql2)) {
                         commit();
                         echo "<script>alert('Payment Record updated successfully');</script>";
                    }
                    else {
                         rollback();
                         echo "<script>alert('Payment Update failed!');</script>";
                         exit;
                    }
               }//end if for transtb duplicate check
          }

          if(count($bcode)>0)
          {
               $amt_sub_sum = 0;
               foreach($bamt as $val_amt)
               {
                    $amt_sub_sum += $val_amt;
                    if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
                    {
                         echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
                    }
               }//end of foreach for bamt
               foreach($fdesc as $fd)
               {
                    if($fd == 'Invalid') {
                         $runquery=true;
                         echo "<script>alert('Invalid item code encountered. Enter the correct code.');</script>";
                         exit;
                    }
               }
               if($dr_vamount == 0 and $cr_vamount == 0)
               {
                    echo "<script language='javascript'>alert('Cross check your entry! Amount values for Cr and/or Dr is required!');</script>";exit;
               }
          }// end of bcode is not empty

          // End of Validation
          $s=0;$i=0;$j=0;$tamt=0; 
          // transaction begins
          $year=@date('Y',strtotime(@$_REQUEST['pay_date']));
          if($bankpost=="bank"){
               //echo "interbank"; exit;
               foreach($bcode as $v){
                    $sQr = "SELECT acctcode FROM bank_accounttb WHERE acctcode='{$v}'";
                    $qSq = mysqli_query($con, $sQr);
                    if(mysqli_num_rows($qSq) <=0 ){
                         echo "<script language='javascript'>alert('{$v} is not a bank code. Kindly check!');</script>";
                         exit;
                    }
               }
          }
          begin();
          if($paid_jv_no == '' || !isset($_REQUEST['jv_no']))$jo = "".$dept_name."JV/".date('Y')."/".rand(10, date('Ymds'));
          else $jno = $paid_jv_no;

          if($payee_name=='') $payee_name=$bursary->get_any_value("payee_name", "vouchertb", "pvno", $ipvno);

          $sql_r1="INSERT INTO journaltb set journalno='$jno', pvno='$pvno', journal_date='{$pay_date}', dept_code='$dept', acctcode='".$account."', dept_acctcode='".$account."', description='".$desc."', payee_name='{$payee_name}', dr_amount=".$dr_vamount.", cr_amount=".$cr_vamount.", prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', paybatch='". mysqli_real_escape_string($con, $batchno)."'";
          
          $r1=@mysqli_query($con, $sql_r1);

          if($pvno !='') {
               $sql_r2a="UPDATE transtb SET retired='Yes', retired_by='$login_id', date_retired='{$pay_date}', time_retired=now(), amount_retired=".$cr_vamount." WHERE pvno='{$pvno}' AND year(transdate)='{$pay_year}' AND amount='{$cr_vamount}'";
               
               $r2[]=@mysqli_query($con, $sql_r2a) or die(mysqli_error($con));

               $sql_r2b="UPDATE vouchertb SET retired='Yes', retired_by='$login_id', date_retired='{$pay_date}', time_retired=now(), amount_retired=".$cr_vamount." WHERE pvno='$ipvno'";
               
               $r2[]=@mysqli_query($con, $sql_r2b) or die(mysqli_error($con));

               $r2[]=@mysqli_query($con, "INSERT INTO pa_retirementtb SET pvno='$pvno', jvno='$jno', retired_by='$login_id', date_retired='{$pay_date}', time_retired=now(), amount_retired='{$cr_vamount}'") or die(mysqli_error($con));
          }
          /*if(count($folio)==1)
          $r2[]=@mysqli_query($con, "INSERT INTO journal_folio_codetb set journalno='$jno', folio_code='$folio[0]', amount='$dr_vamount', paid='No'");
          else*/
          {
               //echo count($bcode);exit;
               if(count($bcode) > 0)
               {
                    foreach($bcode as $v)
                    {
                         if(strtolower($fdesc[$s]) != 'invalid' and strtolower($fdesc[$s]) != 'null'){
                              if(isset($cr_bamt[$s]) and $cr_bamt[$s] > 0){
                                   $sw="INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$v."', amount='".$cr_bamt[$s]."', paid='No', trans_type='Credit'";
                                   $r2[]=@mysqli_query($con, $sw);
                                   if($save_type=="final") {
                                        if($bankpost=="bank") $ss="INSERT INTO transtb set dept_acctcode='".$dept."', acctcode='".$v."', folio_code='".$v."', transtype='Debit', transdate='".$pay_date."', amount='".$cr_bamt[$s]."', chequeno='', pvno='".$jno."', comment='".$desc."', entry_date=now(), entry_time=now(), entry_by='$login_id', paybatch='". mysqli_real_escape_string($con, $batchno)."'";

                                        else $ss="INSERT INTO transtb set dept_acctcode='".$dept."', acctcode='".$account."', folio_code='".$v."', transtype='Credit', transdate='".$pay_date."', amount='".$cr_bamt[$s]."', chequeno='', pvno='".$jno."', comment='".$desc."', entry_date=now(), entry_time=now(), entry_by='$login_id', paybatch='". mysqli_real_escape_string($con, $batchno)."'";
                                        $r2[]= mysqli_query($con, $ss) or die( mysqli_error($con));
                                   }
                                   //echo "INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$v."', amount='".$cr_bamt[$s]."', paid='No', trans_type='Credit'";
                              }
                              if(isset($dr_bamt[$s]) and $dr_bamt[$s] > 0){
                                   $r2[]=@mysqli_query($con, "INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$v."', amount='".$dr_bamt[$s]."', paid='No', trans_type='Debit'") or die(mysqli_error($con));

                                   if($save_type=="final") {
                                        if($bankpost=="bank") $r2[]=@mysqli_query($con, "INSERT INTO transtb set dept_acctcode='".$dept."', acctcode='".$v."', folio_code='".$v."', transtype='Credit', transdate='".$pay_date."', amount='".$dr_bamt[$s]."', chequeno='', pvno='".$jno."', comment='".$desc."', entry_date='{$pay_date}', entry_time=now(), entry_by='$login_id', paybatch='". mysqli_real_escape_string($con, $batchno)."'") or die(mysqli_error($con));

                                        else $r2[]=@mysqli_query($con, "INSERT INTO transtb set dept_acctcode='".$dept."', acctcode='".$account."', folio_code='".$v."', transtype='Debit', transdate='".$pay_date."', amount='".$dr_bamt[$s]."', chequeno='', pvno='".$jno."', comment='".$desc."', entry_date='{$pay_date}', entry_time=now(), entry_by='$login_id', paybatch='". mysqli_real_escape_string($con, $batchno)."'") or die(mysqli_error($con));
                                        //echo "INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$v."', amount='".$dr_bamt[$s]."', paid='No', trans_type='Debit'";
                                   }
                              }
                         }
                         $s++;
                    }
               }
          }

          //logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");

          //$sql="SELECT * FROM journaltb where prepared_by='$login_id' and checked_by='' order by journal_date,folio_code,journalno";
          //************** Commite the Transactions
          $flag=false;
          //echo  "==>$flag<==$r1###$r2%%%%<br>";
          if($r1)//and $r2and $r3
          {
               $flag=true;
               //echo  "==>1$flag<==<br>";
               foreach($r2 as $r_val2)
               {
                    if($r_val2)
                    $flag=true;
                    else{
                         $flag=false;
                         /*echo "<script>alert(' ".  mysqli_error($con) ." ');</script>";*/
                         break;
                    }
               }

          }// end of if($r1 and $r2 and $r3)

          //echo  "==>7$flag<==";
          if($flag)
          {
               
               commit();
               echo "<script>alert('Record saved successfully');</script>";
          }
          else
          {
               echo mysqli_error($con);
               rollback();
               echo "<script>alert('Operation Failed! Transaction was canceled.');</script>";
          }

     }// end of save

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
          $pvno=$rs_d['pvno'];
          logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");
          begin();
          if( mysqli_query($con, "DELETE FROM vouchertb where id='$r_id'") and  mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_folio_codetb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_parent_child_taxtb where child_pvno='$pvno'")) {
               commit();
               echo "<script>alert('Record deleted successfully');</script>";
          }else {
               rollback();
               echo "<script>alert('Error deleting record!');</script>";
          }
          $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";

          $action="view";
     }

     if($action=='view')
     {
          $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
     }

     /////////////////////view section ////////////////////

     exit;
}// end of voucher_section

if($id=='journal_section_entry')
{
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date']));
     $depts=@explode("***",$_REQUEST['journal_unit']);
     $dept = $depts[0]; $dept_name = substr($depts[1], 0, 1);
     $account=@$_REQUEST['account'];
     $folio=@$_REQUEST['folio'];
     $dr_vamount=@$_REQUEST['dr_vamount'];
     $cr_vamount=@$_REQUEST['cr_vamount'];

     $journal_unit=@$_REQUEST['journal_unit'];
     $bcode=@$_REQUEST['bcode'];
     $cr_bamt=@$_REQUEST['cr_bamt'];
     $dr_bamt=@$_REQUEST['dr_bamt'];
     $desc=@$_REQUEST['narration'];
     $login_id=@$_SESSION['login_id'];
     $pvno=@$_REQUEST['pvno']; $pacode=@$_REQUEST['pa_code'];
     $amt_approved=@$_REQUEST['amt_approved'];
     //$process_type=$_REQUEST['operation'];

     $vcode=@$_REQUEST['code'];
     $vamt=@$_REQUEST['amount'];  //code is the folio_code and rate


     if($action=='save')
     {
          /*echo "<script language='javascript'>alert('".$dr_vamount."::".$cr_vamount."');</script>";exit;*/
          if($dr_vamount - $cr_vamount != 0)
          {
               echo "<script language='javascript'>alert('Cross check your entry! Amount values entered for Cr and Dr does not match!');</script>";exit;
          }
          //$amt_sub_sum = 0;
          /*foreach($vamt as $amt)
          {
          //$amt_sub_sum += $amt;
          if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
          {
          echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
     }
}
if($amt_approved != $amt_sub_sum)
{
echo "<script language='javascript'>alert('Cross check your entry! Amount Approved is not the same with Amount entered');</script>";exit;
}
*/


if(count($bcode)>0)
{
     $amt_sub_sum = 0;
     foreach($bamt as $val_amt)
     {
          $amt_sub_sum += $val_amt;
          if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
          {
               echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
          }
     }//end of foreach for bamt
     if($amt_approved != $amt_sub_sum)
     {
          echo "<script language='javascript'>alert('Cross check your entry! Amount values entered for Cr and Dr does not match!');</script>";exit;
     }
}// end of bcode is not empty

// End of Validation
$s=0;$i=0;$j=0;$tamt=0; /*$emsg=array();$total_tax=0;  $amount=0;*/
// transaction begins
$year=@date('Y',strtotime(@$_REQUEST['pay_date']));
//echo $amt_approved; exit;
begin();
$jno = "".$dept_name."JV/".date('Y')."/".rand(10, date('Ymds'));
/*if($process_type=="payment") {
$trans_type = "Debit";
$receiptno = " pvno = '". $_REQUEST['pvno']. "', ";
}
elseif($process_type=="receipt") {
$srec =  mysqli_query($con, "SELECT DISTINCT(receiptno) FROM transtb WHERE receiptno != ''");
$receiptno =  " receiptno='".str_pad( mysqli_num_rows($srec), 7, '0', STR_PAD_LEFT). "', ";
$trans_type = "Credit";
}*/

//echo "INSERT INTO journaltb set journalno='$jno', journal_date='$pay_date', dept_code='$dept', dept_acctcode='$account', description='$desc', prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
$r1=@mysqli_query($con, "INSERT INTO journaltb set journalno='$jno', pvno='$pvno', journal_date='$pay_date', dept_code='$dept', dept_acctcode='".$account."', description='".$desc."', dr_amount=".$dr_vamount.", cr_amount=".$cr_vamount.", prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'");

//folio_code='$folio',
/*echo "<script>alert('"."UPDATE transtb SET retired=\'Yes\', retired_by=\'$login_id\', date_retired=now(), time_retired=now(), amount_retired=".$pa_vamount." WHERE pvno=\'$pvno\'"."');</script>";*/
if($pvno !='') $r2[]=@mysqli_query($con, "UPDATE transtb SET retired='Yes', retired_by='$login_id', date_retired=now(), time_retired=now(), amount_retired=".$cr_vamount." WHERE pvno='{$pvno}'");
if(count($folio)==1)
$r2[]=@mysqli_query($con, "INSERT INTO journal_folio_codetb set journalno='$jno', folio_code='$folio[0]', amount='$dr_vamount', paid='No'");
else
{
     if(count($bcode)>1)
     {
          foreach($bcode as $v)
          {
               if($cr_bamt[$s] > 0){
                    $r2[]=@mysqli_query($con, "INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$v."', amount='".$cr_bamt[$s]."', paid='No', trans_type='Credit'");
                    //echo "INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$v."', amount='".$cr_bamt[$s]."', paid='No', trans_type='Credit'";
               }
               if($dr_bamt[$s] > 0){
                    $r2[]=@mysqli_query($con, "INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$v."', amount='".$dr_bamt[$s]."', paid='No', trans_type='Debit'");
                    //echo "INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$v."', amount='".$dr_bamt[$s]."', paid='No', trans_type='Debit'";
               }
               $s++;
          }
     }
}

//logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");


//$sql="SELECT * FROM journaltb where prepared_by='$login_id' and checked_by='' order by journal_date,folio_code,journalno";
//************** Commite the Transactions
$flag=false;
//echo  "==>$flag<==$r1###$r2%%%%<br>";
if($r1)//and $r2and $r3
{
     $flag=true;
     //echo  "==>1$flag<==<br>";
     foreach($r2 as $r_val2)
     {
          if($r_val2)
          $flag=true;
          else{
               $flag=false;
               break;
          }
     }

}// end of if($r1 and $r2 and $r3)


//echo  "==>7$flag<==";
if($flag)
{
     commit();
     echo "<script>alert('Record saved successfully');</script>";
}
else
{
     rollback();
     echo "<script>alert('Operation Failed! Transaction was canceled');</script>";
}




/*if(count($folio)==1){
$sql[] = @mysqli_query($con, "INSERT INTO transtb set dept_acctcode='', acctcode='".$account."', folio_code='".$folio[0]."', transtype='".$trans_type."', transdate='".$pay_date."', amount=".$amt_approved.", ".$receiptno." entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'");
}
else
{
if(count($bcode)>1)
{
foreach($bcode as $v)
{
$sql[] = @mysqli_query($con, "INSERT INTO transtb set dept_acctcode='', acctcode='".$account."', folio_code='".$v."', transtype='".$trans_type."', transdate='".$pay_date."', amount=".$bamt[$s].", ".$receiptno." entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'");
$s++;
}
}
}
$flag=false;
foreach($sql as $sq){
if($sq) $flag=true;
else{
$flag=false;
break;
}
}
if($flag){
commit();
echo "<script>alert('Record saved successfully');</script>";
}
else
{
rollback();
echo "<script>alert('Operation Failed! Transaction was canceled. ". mysqli_error($con)."');</script>";
}

//logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");

//************** Commite the Transactions
*/
}// end of save




if($action=='delete')
{
     $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
     $pvno=$rs_d['pvno'];
     logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");
     begin();
     if( mysqli_query($con, "DELETE FROM vouchertb where id='$r_id'") and  mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_folio_codetb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_parent_child_taxtb where child_pvno='$pvno'")) {
          commit();
          echo "<script>alert('Record deleted successfully');</script>";
     }else {
          rollback();
          echo "<script>alert('Error deleting record!');</script>";
     }
     $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";

     $action="view";
}

if($action=='view')
{
     $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
}

/////////////////////view section ////////////////////
/*$sn=0;
$res_v=@mysqli_query($con, $sql);
$g_total=0;
$tb="<table border='1' rules='all' frame='box'><tr><th colspan='8' align='center'>Prepared Journal</th></tr><tr><th>S/N</th><th>Jornal NO.</th><th>FOLIO</th><th>DEPARTMENT</th><th>DATE</th><th>AMOUNT</th><th>ACTION</th></tr>";
if(@mysqli_num_rows($res_v)>=1)
{

while($rs_v=@mysqli_fetch_array($res_v))
{
++$sn;
$r_id=$rs_v['id'];
$g_total+=$rs_v['amount_paid'];
$tb.="<tr><td>$sn</td><td>{$rs_v['journalno']}</td><td>".@get_folio_name($rs_v['item_code'])."</td><td>".@get_dept_name_act($rs_v['dept_code'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_entry','delete','$r_id');\">DELETE</a></td></tr>";
}//end of while

$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
$tb.="<tr><td colspan='6' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
$tb.="</table>";
echo $tb_s.$tb;
}
else
echo "<b>No record to display</b>";*/
exit;

}// end of voucher_section

if($id=='folio_code_breakdown_journal')
{
     $code=@$_REQUEST['folio'];
     $amount=@$_REQUEST['amount'];
     $x=@$_REQUEST['x'];
     
     if( (isset($_REQUEST['pa_vamount']) and $_REQUEST['pa_vamount'] > 0) and (isset($_REQUEST['pvno']) and $_REQUEST['pvno'] != '') ) {
          $isPA=true; $pacode=$_REQUEST['pa_code']; $pa_vamount=$_REQUEST['pa_vamount'];
     }else $isPA=false;

     echo"<font color='red'><table width='100%' border=1 rules='rows'><tr><th>SN</th><th width='5%'>Code</th><th>Description</th><th>Dr(&#8358;)</th><th>Cr(&#8358;)</th></tr>";
     if(count($code) > 1)
     {
          if(!$isPA){
               //NORMAL JOURNAL ENTRY
               $sn=1;
               foreach($code as $code_val)
               {
                    $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$code_val);
                    if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
                    echo"<tr style='font-size:12px'><td>".$sn++."</td><td nowrap><input type='hidden' name='bcode[]' value='$code_val'> $code_val: </td><td style='font-size:12px'>".get_folio_name($code_val)." $ncoa_codex</td>
                    <td width='5%'><input type='text' class='amt2' name='dr_bamt[]' onblur=\"sum2('amt2')\" style='background-color: #FEFFB0;font-weight: bold;text-align: right;'></td>
                    <td width='5%'><input type='text' class='amt3' name='cr_bamt[]' onblur=\"sum2('amt3')\" style='background-color: #FEFFB0;font-weight: bold;text-align: right;'></td></tr>";
               }
               /*echo "<tr><td colspan='5'><label for='pvno'><strong>(PA) PV. No.: </strong> <input type='text' id='pvno' name='pvno' style='width: 300px'>
               <input type='hidden' id='lsn' name='lsn' value='".$sn."'>
               </label> ".'<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-search" onclick="swapcontent(\'get_pv_detail_journal\');"><strong><font color="#000099">GET PV</font></strong></a>'."</td>
               </tr>";
               echo '<div id="get_pv_detail_journal"></div>';*/
               echo "<tr><th></th><th></th><th>TOTAL AMOUNT (&#8358;)</th>
               <th width='5%'><input type='number' class='t_amt2' name='dr_vamount' id='dr_vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' ></th>
               <th width='5%'><input type='number' class='t_amt3' name='cr_vamount' id='cr_vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' ></th>
               </tr>";
          }else{
               //PA RETIREMENT JOURNAL
               $sn=1;
               $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$pacode);
               if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
               echo"<tr style='font-size:12px'><td>".$sn++."</td><td nowrap><input type='hidden' name='bcode[]' value='$pacode'> $pacode: </td><td style='font-size:12px'>".get_folio_name($pacode)." $ncoa_codex</td>
               <td width='5%'></td>
               <td width='5%'><strong> [".$pa_vamount."] </strong></td></tr>";
               foreach($code as $code_val)
               {
                    $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$code_val);
                    if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
                    echo"<tr style='font-size:12px'><td>".$sn++."</td><td nowrap><input type='hidden' name='bcode[]' value='$code_val'> $code_val: </td><td style='font-size:12px'>".get_folio_name($code_val)." $ncoa_codex</td>
                    <td width='5%'><input type='text' class='amt2' name='dr_bamt[]' onblur=\"sum2('amt2')\" style='background-color: #FEFFB0;font-weight: bold;text-align: right;'></td>
                    <td width='5%'><input type='hidden' class='amt3' name='cr_bamt[]' onblur=\"sum2('amt3')\" style='background-color: #FEFFB0;font-weight: bold;text-align: right;'></td></tr>";
               }
               echo "<tr><th></th><th></th><th>TOTAL AMOUNT (&#8358;)</th>
               <th width='5%'><input type='number' class='t_amt2' name='dr_vamount' id='dr_vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' ></th>
               <th width='5%'><input type='number' class='t_amt3' name='cr_vamount' id='cr_vamount' value='".$pa_vamount."' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' ></th>
               </tr>";
          }
     }elseif(count($code) == 1){
          $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$pacode);
          if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
          echo"<tr style='font-size:12px'><td>1</td><td nowrap> $pacode: </td><td style='font-size:12px'>".get_folio_name($pacode)." $ncoa_codex</td>
          <td width='5%'></td>
          <td width='5%'><strong> [".$pa_vamount."] </strong></td></tr>";
          $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$code[0]);
          if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = "<b>(".$ncoa_code.")</b>"; }
          echo "<tr><td>2</td><td nowrap>".$code[0]."</td><td>".get_folio_name($code[0])." $ncoa_codex</th>
          <td width='5%'><input type='number' name='dr_vamount' id='dr_vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' ></td>
          <td width='5%'><input type='number' name='cr_vamount' id='cr_vamount' value='".$pa_vamount."' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' ></td></tr>";
     }else {
          echo ''; exit;
     }
     echo"<tr><th colspan='4' height='33'>"; ?> <input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('journal_section_entry','save'); " /> | <input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('journal_section_entry','refresh');" /> <?php echo "</th></tr>";
     echo"</table></font>";
     exit;
}// end of folio_code_breakdown_rec

if($id=='get_pv_detail'){
     $pvno = $_REQUEST['pvno'];
     if($pvno == ''){
          echo "<script language='javascript'>alert('Enter PV. No.');</script>";exit;
     }
     $sql =  mysqli_query($con, "SELECT t.*, v.payee_name FROM transtb t INNER JOIN vouchertb v ON t.pvno = pvno_paid  WHERE t.pvno = '".$pvno."'");
     while($rec =  mysqli_fetch_array($sql, 3 )){
          $folio = $rec['folio_code'];
          $amount= $rec['amount'];
          $pdate = $rec['transdate'];
          $pname = $rec['payee_name'];
     }
     //@get_folio_name($folio)
     echo '
     <table width="100%" border="1" rules="rows" style="border: solid 1px #000" cellspacing="0" cellpadding="10">
     <thead>
     <tr>
     <th align="left" valign="middle">Description</th>
     <th align="left" valign="middle">Code</th>
     <th align="left" valign="middle">Dr</th>
     <th align="left" valign="middle">Cr</th>
     </tr>
     </thead><tbody>
     <tr>
     <td align="left" valign="middle" colspan="2">New Folio Desc</td>
     <!--td align="left" valign="middle">New Code Select</td-->
     <td align="left" valign="middle"><input type="number" id="amount" name="amount" value="'.$amount.'" onchange="$(\'#tdr\').html( $(\'#amount\').val() )" /></td>
     <td align="left" valign="middle">&nbsp;</td>
     </tr>
     <tr>
     <td align="left" valign="middle"><strong>PA (</strong>'.$pname.'<strong>)</strong></td>
     <td align="left" valign="middle">'.$folio.'</td>
     <td align="left" valign="middle">&nbsp;</td>
     <td align="left" valign="middle">'.$amount.'</td>
     </tr>
     <tr>
     <td align="left" valign="middle">&nbsp;</td>
     <td align="left" valign="middle"><strong>Total</strong></td>
     <td align="left" valign="middle"><strong><span id="tdr"></span></strong></td>
     <td align="left" valign="middle"><strong>'.$amount.'</strong></td>
     </tr></tbody>
     </table>
     <p><strong>Narration:</strong> <br>
     <textarea name="narration" id="narration" style="width:90%"></textarea>
     </p>
     <p>P.V. No.: '.$pvno.' of '.$pdate.' As per the attached.</p>';
     echo"<table><tr><th colspan='4' height='33'>"; ?> <input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('journal_section_entry','save'); " /> | <input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('journal_section_entry','refresh');" /> <?php echo "</th></tr>";
     echo"</table>";
     exit;
}

if($id=='get_pv_detail_journal'){
     $pvno = $_REQUEST['pvno'];
     $sn = $_REQUEST['lsn'];
     $pv_paid = $_REQUEST['pv_paid'];
     $paydate = $_REQUEST['paydate'];
     if($pvno == ''){
          echo "<script language='javascript'>alert('Enter PV. No.');</script>"; exit;
     }
     
     $desc=$bursary->get_any_value("description", "vouchertb", "pvno_paid", $pvno);
     ///$sql =  mysqli_query($con, "SELECT t.*, v.payee_name FROM transtb t INNER JOIN vouchertb v ON t.pvno = v.pvno_paid  WHERE t.pvno = '".$pvno."'");
     ///enable the above code to check voucher from normal flow;
     //echo "SELECT t.* FROM transtb t WHERE t.pvno = '".$pvno."' and year(transdate) = year('$paydate')"; exit;
     $pyr=date('Y', strtotime($paydate));
     $sq="SELECT t.* FROM transtb t WHERE t.pvno = '".$pvno."' and year(transdate) = '{$pyr}'";
     $sql =  mysqli_query($con, $sq);
     
     if( mysqli_num_rows($sql) >0 ){
          while($rec =  mysqli_fetch_array($sql, 3 )){
               $folio = $rec['folio_code'];
               $amount= $rec['amount'];
               $pdate = $rec['transdate'];
               
               $payee_name =  @$bursary->get_any_value('payee_name', 'vouchertb', 'pvno_paid', $pvno, " AND year(date_paid) = '{$pyr}'");
               //$pname = '';//$rec['payee_name'];
          }
          $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$folio);
          if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = " (".$ncoa_code.")"; }
          //echo "Name: ".$pname."<br>Amount: ".$amount." | Date: ".$pdate;

          echo "<table><tr><td><strong>".$folio."</strong>: ".get_folio_name($folio)." $ncoa_codex</td></tr><tr>
          <td>Payee: ".$payee_name." | <strong>Amount: ".number_format($amount, 2)."</strong> | Date: ".$pdate."
          <input type='hidden' name='pa_vamount' id='pa_vamount' value='".$amount."' >
          <input type='hidden' name='pa_payee' id='pa_payee' value='".$payee_name."' >
          <input type='hidden' name='pa_code' id='pa_code' value='".$folio."' ></td></tr>
          <tr><td>{$desc}</td></tr></table>";
     }else
     echo "PA P.V. Details not found or not yet paid.";

     exit;
}

if($id=='get_loan_detail'){
     $pvno = $_REQUEST['pvno'];
     $sn = $_REQUEST['lsn'];
     if($pvno == ''){
          echo "<script language='javascript'>alert('Enter PV. No./Loan ID/Staff ID');</script>";exit;
     }
     $pvn=$bursary->get_any_value("pvno", "vouchertb", "pvno_paid", $pvno);
     $loanid=$bursary->get_any_value("id", "hr_loan_apptb", "loan_no", $pvn);
     //$sql="SELECT l.*, v.payee_name FROM hr_loan_apptb l INNER JOIN vouchertb v ON l.loan_no = v.pvno WHERE l.loan_no = '".$pvn."'";// AND l.process_status != 'Competed'";
     
     $sql_a = "SELECT h.*, TRIM(CONCAT(s.surname, ' ', s.first_name, ' ', s.other_name)) AS payee_name, f.title FROM ((hr_loan_apptb h INNER JOIN stafftb s ON h.fileno=s.fileno) INNER JOIN foliotb f ON h.loan_type=f.folio_code) WHERE (h.loan_no='{$pvno}' OR h.fileno='{$pvno}') AND h.payment_status='Paid'";
     //$loan =  mysqli_query($con, $sql_a);
     
     $sql =  mysqli_query($con, $sql_a);
     if( mysqli_num_rows($sql) >0 ){
          while($rec =  mysqli_fetch_array($sql, 3 )){
               $loanid = $rec['id'];
               $folio = $rec['loan_type'];
               $title = $rec['title'];
               $amount= $rec['loan_amount'];
               $pdate = $rec['app_date'];
               $pname = $rec['payee_name'];
               $duration=$rec['duration'];
               $start_date=$rec['repay_start_date'];
               $end_date=$rec['repay_end_date'];
               $interest=$rec['interest'];
               $installment=$rec['installment'];
               $rate=$rec['rate'];
               $fileno=$rec['fileno'];
               $principal=$rec['principal'];
          }
          $ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$folio);
          if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = " (".$ncoa_code.")"; }
          
          $paid=0;
          $sql =  mysqli_query($con, "SELECT sum(amountpaid) as paid FROM loanrepaymenttb WHERE loanid = '".$loanid."'");
          $amt= mysqli_fetch_array($sql, 3 );	$paid = $amt['paid'];
          $remain = $amount - $paid;
          $re_comment = " Remain: ".number_format($remain, 2);
          $LSTATUS = " ACTIVE ";
          if($remain < 0) {
               $re_comment = " <font color='red'>Over Deduction: ".number_format($remain, 2)."</font>";
               $LSTATUS = " PAYMENT COMPLETED (OVER DEDUCTION) ";
          }elseif($remain == 0) {
               $LSTATUS = " PAYMENT COMPLETED ";
          }
               

          echo "<table width='800px'><tr><td><strong>Loan Type:</strong></td><td><strong>".$folio."</strong>: {$title}</td></tr>
          <tr><td><strong>Debtor:</strong></td><td>".$pname." (".$fileno.")
          <input type='hidden' name='remain' id='remain' value='".$remain."' >
          <input type='hidden' name='payee' id='payee' value='".$pname."' >
          <input type='hidden' name='pa_vamount' id='pa_vamount' value='".$amount."' >
          <input type='hidden' name='pa_code' id='pa_code' value='".$folio."' ></td></tr>
          <tr><td><strong>Amount Loaned: </strong></td><td>".number_format($amount,2)."</td></tr>
          <tr><td><strong>Amount Paid: </strong></td><td>".number_format($paid, 2)."</td></tr>
          <tr><td><strong>Balance: </strong></td><td>{$re_comment}</td></tr>
          <tr><td><strong>Date: </strong></td><td>".$pdate."</td></tr>
          <tr><td><strong>Duration: </strong></td><td>".date_format(new DateTime($start_date), 'F, Y')." to ".date_format(new DateTime($end_date), 'F, Y')." (".$duration." Months)</td></tr>
          <tr><td><strong>Principal: </strong></td><td>".number_format($principal,2)."</td></tr>
          <tr><td><strong>Interest: </strong></td><td>".number_format($interest,2)." (@ ".$rate."%)</td></tr>
          <tr><td><strong>Monthly Repayment: </strong></td><td>".number_format($installment,2)."</td></tr>
          <tr><td><strong>LOAN STATUS: </strong></td><td>{$LSTATUS}</td></tr>
          </table>";
     }else
     echo "Loan details not found for the entry.";

     exit;
}

if($id=='get_loan_repayment'){
     $pvno = $_REQUEST['pvno'];
     $sn = $_REQUEST['lsn'];
     if($pvno == ''){
          echo "<script language='javascript'>alert('Loan ID/Staff ID');</script>";exit;
     }
     $pvn=$bursary->get_any_value("pvno", "vouchertb", "pvno_paid", $pvno);
     $loanid=$bursary->get_any_value("id", "hr_loan_apptb", "loan_no", $pvn);
     $sq="SELECT l.* FROM loanrepaymenttb l INNER JOIN hr_loan_apptb h ON l.loanid=h.id WHERE (h.loan_no='{$pvno}' OR h.fileno='{$pvno}') AND h.payment_status='Paid'";
     $sql =  mysqli_query($con, $sq);
     if( mysqli_num_rows($sql) >0 ){
          $sn=0; $tpay=0;
          echo "<h2>RE-PAYMENT HISTORY</h2><table class='table display' width='100%' border='1'><thead><tr><td><strong>SN</strong></td><td><strong>DATE PROCESSED</strong></td><td><strong>AMOUNT</strong></td><td><strong>PERIOD</strong></td></tr></thead>";
          while($rec =  mysqli_fetch_array($sql, 3 )){
               $tpay += $rec['amountpaid'];
               echo "<tr><td>".++$sn."</td><td>".date_format(new DateTime($rec['entry_date']), 'jS F, Y')."</td><td>&#8358;".number_format($rec['amountpaid'], 2)."</td><td>".date_format(new DateTime($rec['period']), 'F, Y')."</td></tr>";
          }
          //$ncoa_code=$bursary->get_any_value("ncoa_code","foliotb","folio_code",$folio);
          //if($ncoa_code == '') {$ncoa_codex = '';} else {$ncoa_codex = " (".$ncoa_code.")"; }
          //echo "Name: ".$pname."<br>Amount: ".$amount." | Date: ".$pdate;

          echo "<tr><th colspan='2'>TOTAL REPAID:</th><th>&#8358;".number_format($tpay, 2)."</th><td>&nbsp;</td></tr></table>";
     }else
     echo "Loan repayment record(s) not found for the entry.";

     exit;
}

if($id=='save_loan_repayment')
{
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     $pvno = $_REQUEST['pvno'];
     $sn = $_REQUEST['lsn'];
     if($pvno == ''){
          echo "<script language='javascript'>alert('Enter PV. No./Loan ID/Staff ID');</script>";exit;
     }
     $pvn=$bursary->get_any_value("pvno", "vouchertb", "pvno_paid", $pvno);
     $loanid=$bursary->get_any_value("id", "hr_loan_apptb", "loan_no", $pvno);

     //$sql="SELECT l.*, v.payee_name FROM hr_loan_apptb l INNER JOIN vouchertb v ON l.loan_no = v.pvno WHERE l.loan_no = '".$pvn."' AND process_status != 'Completed'";
     $sql_a = "SELECT h.*, TRIM(CONCAT(s.surname, ' ', s.first_name, ' ', s.other_name)) AS payee_name, f.title FROM ((hr_loan_apptb h INNER JOIN stafftb s ON h.fileno=s.fileno) INNER JOIN foliotb f ON h.loan_type=f.folio_code) WHERE (h.loan_no='{$pvno}' OR h.fileno='{$pvno}') AND h.payment_status='Paid'";
     //exit;
     $sql =  mysqli_query($con, $sql_a);
     if( mysqli_num_rows($sql) >0 ){
          while($rec =  mysqli_fetch_array($sql, 3 )){
               $loanid=$rec['id'];
               $folio = $rec['loan_type'];
               $amount= $rec['loan_amount'];
               $pdate = $rec['app_date'];
               $pname = $rec['payee_name'];
               $duration=$rec['duration'];
               $start_date=$rec['repay_start_date'];
               $end_date=$rec['repay_end_date'];
               $interest=$rec['interest'];
               $installment=$rec['installment'];
               $rate=$rec['rate'];
               $fileno=$rec['fileno'];
               $principal=$rec['principal'];
          }
     }else{
          echo "<script>alert('Loan details not found for the PV entry.'); </script>";
          exit;
     }

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date']));
     $depts=@explode("***",$_REQUEST['journal_unit']);
     $dept = $depts[0]; $dept_name = substr($depts[1], 0, 1);
     $pmonth=@$_REQUEST['pmonth'].date('-d',strtotime(@$_REQUEST['pay_date']));
     //$folio=@$_REQUEST['pa_code'];
     //$dr_vamount=@$_REQUEST['pa_vamount'];
     $cr_vamount=@$_REQUEST['pamount'];

     $journal_unit=@$_REQUEST['journal_unit'];
     //$bcode=@$_REQUEST['bcode'];
     $fdesc="Being loan repayment for ".date('F, Y',strtotime($pmonth)); 
     //$cr_bamt=@$_REQUEST['cr_bamt'];
     //$dr_bamt=@$_REQUEST['dr_bamt'];
     $desc="Being loan repayment for ".date('F, Y',strtotime($pmonth)); 
     $login_id=@$_SESSION['login_id'];
     $pvno=@$_REQUEST['pvno']; 
     $pacode=$folio;
     $amt_approved=@$_REQUEST['pamount'];
     $account=$_REQUEST['account'];

     if($action=='save')
     {
          /*echo "<script language='javascript'>alert('".$dr_vamount."::".$cr_vamount."');</script>";exit;*/
          if($cr_vamount <= 0)
          {
               echo "<script language='javascript'>alert('Cross check your entry! Enter amount!');</script>";exit;
          }
          if($cr_vamount!="" && !preg_match('/^\d+(\.\d+)?$/', $cr_vamount))
          {
               echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
          }

          if($pmonth == '' or $pay_date == '')
          {
               echo "<script language='javascript'>alert('Enter payment period.');</script>";exit;
          }

          // End of Validation
          $s=0;$i=0;$j=0;$tamt=0; /*$emsg=array();$total_tax=0;  $amount=0;*/
          // transaction begins
          $year=@date('Y',strtotime(@$_REQUEST['pay_date']));
          //echo $amt_approved; exit;
          

          begin();
          $jno = "".$dept_name."JV/".date('Y')."/".rand(10, date('Ymds'));
          $r1=@mysqli_query($con, "INSERT INTO journaltb set journalno='$jno', pvno='$pvno', journal_date='$pay_date', dept_code='$dept', acctcode='".$account."', dept_acctcode='".$account."', description='".$desc."', cr_amount=".$cr_vamount.", prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'");
          
          $r2[]=@mysqli_query($con, "INSERT INTO journal_folio_codetb set journalno='".$jno."', folio_code='".$folio."', amount='".$cr_vamount."', paid='Yes', trans_type='Credit'");

          $r2[]=@mysqli_query($con, "INSERT INTO loanrepaymenttb SET loanid='{$loanid}', amountpaid='{$cr_vamount}', folio_code='{$folio}', period='{$pmonth}', debtor='{$fileno}', entry_by='{$login_id}', entry_date=now(), entry_time=now()");

          $r2[]=@mysqli_query($con, "INSERT INTO transtb SET fileno='{$fileno}', payee='{$pname}', dept_acctcode='".$dept."', acctcode='".$account."', folio_code='".$folio."', transtype='Credit', transdate='".$pay_date."', amount='".$cr_vamount."', chequeno='', pvno='".$jno."', comment='".$desc."', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'");

          $paid=0;
          $sql =  mysqli_query($con, "SELECT sum(amountpaid) as paid FROM loanrepaymenttb WHERE loanid = '".$loanid."'");
          $amt= mysqli_fetch_array($sql, 3 );	$paid = $amt['paid'];
          $remain = $amount - $paid;
          if($remain <= 0) $r2[]=@mysqli_query($con, "UPDATE hr_loan_apptb SET process_status = 'Completed' WHERE id='$loanid'");

          $flag=false;
          //echo  "==>$flag<==$r1###$r2%%%%<br>";
          if($r1)//and $r2and $r3
          {
               $flag=true;
               //echo  "==>1$flag<==<br>";
               foreach($r2 as $r_val2)
               {
                    if($r_val2)
                    $flag=true;
                    else{
                         $flag=false;
                         break;
                    }
               }

          }// end of if($r1 and $r2 and $r3)

          if($flag)
          {

               commit();
               echo "<script>
               swapcontent('get_loan_detail');
               swapcontent('get_loan_repayment');
               alert('Record saved successfully');
               </script>";
          }
          else
          {
               rollback();
               echo "<script>alert('Operation Failed! Transaction was canceled');</script>";
          }
     }// end of save


     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
          $pvno=$rs_d['pvno'];
          logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");
          begin();
          if( mysqli_query($con, "DELETE FROM vouchertb where id='$r_id'") and  mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_folio_codetb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_parent_child_taxtb where child_pvno='$pvno'")) {
               commit();
               echo "<script>alert('Record deleted successfully');</script>";
          }else {
               rollback();
               echo "<script>alert('Error deleting record!');</script>";
          }
          $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";

          $action="view";
     }

     if($action=='view')
     {
          $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
     }

     /////////////////////view section ////////////////////
     /*$sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<table border='1' rules='all' frame='box'><tr><th colspan='8' align='center'>Prepared Journal</th></tr><tr><th>S/N</th><th>Jornal NO.</th><th>FOLIO</th><th>DEPARTMENT</th><th>DATE</th><th>AMOUNT</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {

     while($rs_v=@mysqli_fetch_array($res_v))
     {
     ++$sn;
     $r_id=$rs_v['id'];
     $g_total+=$rs_v['amount_paid'];
     $tb.="<tr><td>$sn</td><td>{$rs_v['journalno']}</td><td>".@get_folio_name($rs_v['item_code'])."</td><td>".@get_dept_name_act($rs_v['dept_code'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_entry','delete','$r_id');\">DELETE</a></td></tr>";
}//end of while

$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
$tb.="<tr><td colspan='6' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
$tb.="</table>";
echo $tb_s.$tb;
}
else
echo "<b>No record to display</b>";*/

exit;
}

if($id=='login')
{

     echo "<div style='font-weight:bold;margin:7px;font-family:Arial black;background-color:#D3E488; color:#2AA100;padding:10px 60px;'>USER LOGIN</div><form name='frmlogin' id='frmlogin'>
     <strong>File Number / Application No:<br/>E.g SS-007, SS-024, SS-124 </strong><br><input type='text' name='username' id='username' placeholder='File number / Application No' size='35' class='txt'><br/><br>
     <strong>Password :</strong><br><input type='password' name='password' id='password' placeholder='Password' class='txt' size='23' onkeydown=\"if (event.keyCode == 13) swapcontent('main_login',$('#username').val(),$('#password').val());\"><input type='button' value='Login' class='btn' onclick=\"swapcontent('main_login',$('#username').val(),$('#password').val());\"><br/>
     <!--	<input type='checkbox' name='remember_me' id='remember_me' value='remember_me'/> <span style='font-size:10px;
     font-family:\"Palatino Linotype\", \"Book Antiqua\", Palatino, serif;
     color:#1A5881;font-style:italic'><a>Remember Me </a>|| --><a href=\"javascript:swapcontent('forget_password');\">Forget your password</a></span>
     <div id='main_login'></div>
     </form>";
     exit;
}  //end of login

if($id=='app_login')
{

     echo "<div style='font-weight:bold;margin:7px;font-family:Arial black;background-color:#D3E488; color:#2AA100;padding:10px 35px;'>APPLICANT LOGIN</div><p><font color='#D3E488'>Fill the form below and click on Continue <br/>to access your application portal</font></p><form name='frmlogin' id='frmlogin'>
     <strong>Application Number:</strong><br><input type='text' name='username' id='username' placeholder='Application Number' size='35' class='txt'><br/><br>
     <strong>Surname:</strong><br><input type='text' name='password' id='password' placeholder='Surname' class='txt' size='35' onkeydown=\"if (event.keyCode == 13) swapcontent('app_main_login',$('#username').val(),$('#password').val());\"><br/><input type='button' value='Continue' class='btn' onclick=\"swapcontent('app_main_login',$('#username').val(),$('#password').val());\"><br/>
     <!--	<input type='checkbox' name='remember_me' id='remember_me' value='remember_me'/> <span style='font-size:10px;
     font-family:\"Palatino Linotype\", \"Book Antiqua\", Palatino, serif;
     color:#1A5881;font-style:italic'><a>Remember Me </a>|| <a href=\"javascript:swapcontent('forget_password');\">Forget your password</a></span>-->
     <div id='app_main_login'></div>
     </form>";
     exit;
}  //end of login

if($id=='forget_password')
{

     echo "<form name='frmlogin' id='frmlogin'>
     <input type='text' name='uname' id='uname' placeholder='Login ID' size='35' class='txt'><br/>
     <input type='text' name='email' id='email' placeholder='Type email address here' class='txt' size='23' onkeydown=\"if (event.keyCode == 13) swapcontent('pass_recovery_update',$('#uname').val(),$('#email').val());\"><input type='button' value='Recover' class='btn' onclick=\"swapcontent('pass_recovery_update',$('#uname').val(),$('#email').val());\"><br/>
     <div id='pass_recovery_update'></div>
     </form>";
     exit;
}  //end of login
if($id=='password_mgt')
{

     $ref=@$_REQUEST['ref']; 	//no base64
     $old=trim(@$_REQUEST['oldpwd']); //no base64
     $oldpwd=trim(@base64_encode(@$_REQUEST['oldpwd']));
     $newpwd=trim(@base64_encode($ref));


     $login_id=$_SESSION['login_id'];
     $login_status=@$_SESSION['login_status'];
     if($login_status=='staff')
     {
          //check before comitting
          $res_c=@mysqli_query($con, "SELECT * FROM stafftb where fileno='$login_id' and password='$oldpwd'");
          if( mysqli_num_rows($res_c)<=0)
          {
               echo "<font color='red'><b>Invalid old password. Please verify and try again.</b></font>"; exit;
          } //end of check for old pwd

          @mysqli_query($con, "update stafftb set password='$newpwd' where fileno='$login_id' limit 1");
          $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
          logs("$login_id","Change password","$login_id changed password");
          @session_unset(); @session_destroy();
          //header("location :$ref");

          //@mysqli_query($con, "INSERT INTO portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Change password','Password change by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
          // echo "<font color='red'><b>Password has been changed successfully. Please Logout to confirm</b></font>";
          echo "<script>alert('Password has been changed successfully. Please re-login to confirm');location='index.php';</script>";


     } //end of staff password mgt

     if($login_status=='student')
     {
          //check before comitting
          $res_c=@mysqli_query($con, "SELECT * FROM studenttb where regno='$login_id' and password='$old'");  //student pawd is not encoded
          if( mysqli_num_rows($res_c)<=0)
          {
               echo "<font color='red'><b>Invalid old password. Please verify and try again.</b></font>"; exit;
          } //end of check for old pwd

          @mysqli_query($con, "update studenttb set password='$ref' where regno='$login_id' limit 1");
          $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
          @mysqli_query($con, "INSERT INTO portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Change password','Password change by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
          // echo "<font color='red'><b>Password has been changed successfully. Please Logout to confirm</b></font>";
          echo "<script>alert('Password has been changed successfully. Please re-login to confirm');swapcontent('logout','index.php');</script>";


     } //end of student management
     exit;
} //end of password management
if($id=='logout') //logout section
{
     $ref=@$_REQUEST['ref']; //this is the page to redirect to
     $login_status=@$_SESSION['login_status'];

     if($login_status=='candidate')
     {
          $jamb_no=@$_SESSION['putme_regno'];
          @mysqli_query($con, "update candidatetb set online_status='Off' where regno='$jamb_no' limit 1");
     } //end of candidate logout

     if($login_status=='staff' or $login_status=='student')
     {

          $login_id=@$_SESSION['login_id'];

          if($login_status=='staff') {$table="stafftb"; $update_field="fileno";} elseif($login_status=='student') { $table="studenttb"; $update_field="regno"; }

          $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
          @mysqli_query($con, "update $table set last_login_date='$log_date',last_login_time='$log_time',online_status='Off' where $update_field='$login_id' limit 1");
          @mysqli_query($con, "INSERT INTO portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Portal Logout','Logout by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
     } //end of if staff or student logout


     @session_unset(); @session_destroy();
     header("location :$ref");
     /*echo "<script language='javascript'> document.location='$ref';</script>";*/
     exit;
} //end of logout


if($id=='natdiv') //nationality
{
     $val=@$_REQUEST['val']; //this is the page to redirect to
     if($val=='Non-Nigerian')
     {
          echo "Country: <select name='country' id='country'><option selected value=''>---</option>";
          $res_c=@mysqli_query($con, "SELECT * FROM countrytb where country!='Nigeria' order by country");
          while($rs_c=@mysqli_fetch_array($res_c))
          {
               $country=@$rs_c['country'];
               echo "<option value='$country'>$country</option>";
          }
          echo "</select>";
     } //end of if non-nigeria
     else
     {
          echo "<span id='statediv'>State:<select name='state' id='state' onchange=\"swapcontent('lgadiv',document.getElementById('state').value)\"><option selected value=''>---</option>";
          $res_c=@mysqli_query($con, "SELECT * FROM statetb order by state_name");
          while($rs_c=@mysqli_fetch_array($res_c))
          {
               $state_id=@$rs_c['state_id'];
               $state_name=@$rs_c['state_name'];
               echo "<option value='$state_id'>$state_name</option>";
          }

          echo"</select>
          <br /><br /></span>
          LGA:
          <span id='lgadiv'>
          <select name='lga' id='lga'>
          </select></span>";
     } //end of nigerian

     exit;
} //end of nationality
if($id=='lgadiv')
{
     $val=@$_REQUEST['val'];
     $res_l=@mysqli_query($con, "SELECT * FROM lgatb where state_id='$val' order by lga_name");
     echo "<select name='lga' id='lga'><option selected value=''>---</option>";
     while($rs_l=@mysqli_fetch_array($res_l))
     {
          $lga_id=@$rs_l['lga_id'];
          $lga_name=@$rs_l['lga_name'];
          echo "<option value='$lga_id'>$lga_name</option>";
     }
     echo "</select>";

     exit;
} //end of fetch state

if($id=='load_staff')
{
     $val_str=@explode("***",@$_REQUEST['val']); $dept_id=trim($val_str[0]); $fact_id=trim($val_str[1]);
     $option=@$_REQUEST['option'];
     if($option=='dept') //load staff using department only
     {
          //if($dept_id=='80' or $dept_id=='70' or $dept_id=='40' or $dept_id=='131')
          $res_s=@mysqli_query($con, "SELECT * FROM stafftb where staff_category='Academic' order by surname,fileno");
          //else
          //$res_s=@mysqli_query($con, "SELECT * FROM stafftb where dept_id='$dept_id' order by surname,fileno");
     }//end of load staff for course allocation
     else
     $res_s=@mysqli_query($con, "SELECT * FROM stafftb where fact_id='$fact_id' order by surname,fileno");
     echo "<select id='staff' name='staff'>";
     echo "<option selected value=''>---</option>";
     while($rs_s=@mysqli_fetch_array($res_s))
     {
          $fileno=@$rs_s['fileno'];
          $fullname=@strtoupper(@$rs_s['surname'])." ".@$rs_s['first_name']." ".@$rs_s['other_name']."(".@$rs_s['title'].") - ".@$rs_s['fileno'];
          echo "<option value='$fileno'>$fullname</option>";
     } //end of load staff
     echo "</select>";

     exit;
}//end of load_staff

if($id=='password_recovery')
{
     echo "<form name='passform' id='passform'><table style='margin-left:30px;border-radius:15px;'>
     <tr><th>Login ID:</th><td><input type='text' id='uname' name='uname' class='easyui-validatebox' data-options='required:true' placeholder='Login ID'/></td></tr>
     <tr><th>Email Address:</th><td><input type='text' id='email' name='email' class='easyui-validatebox' data-options='required:true' placeholder='Type email here'/></td></tr>
     <tr><th colspan='2'><input type='button' class='btn' value='Recover Password' onClick=\"swapcontent('pass_recovery_update')\"/><input type='button' class='btn' value='Close' onClick=\"swapcontent('close_dialog','w')\"/></th></tr>
     </table></form>
     <div id='pass_recovery_update'></div>";

     exit;
}//password mgt

if($id=='pass_recovery_update')
{
     $login_id=strtoupper(@$_REQUEST['uname']);
     $email=@$_REQUEST['email'];
     $found=false;
     ///check the student table and take the email
     $res_s=@mysqli_query($con, "select regno,email,password,surname,first_name,other_name from studenttb where regno='$login_id' and email='$email'");
     $rs_s=@mysqli_fetch_array($res_s);
     if(@mysqli_num_rows($res_s)>=1)
     {
          $found=true;
          $email=@$rs_s['email'];
          $pass=@$rs_s['password'];
          $surname=@$rs_s['surname'];
          $first_name=@$rs_s['first_name'];
          $other_name=@$rs_s['other_name'];
          $fullname=strtoupper($surname).", ".$first_name." ".$other_name;
          $real_pass=$pass;  ///student password is not encoded
     }

     ///check the staff table and take the email
     $res_s=@mysqli_query($con, "select fileno,email,password,title,surname,first_name,other_name from stafftb where fileno='$login_id' and email='$email'");
     $rs_s=@mysqli_fetch_array($res_s);
     if(@mysqli_num_rows($res_s)>=1)
     {
          $found=true;
          $email=@$rs_s['email'];
          $pass=@$rs_s['password'];
          $title=@$rs_s['title'];
          $surname=@$rs_s['surname'];
          $first_name=@$rs_s['first_name'];
          $other_name=@$rs_s['other_name'];
          $fullname=strtoupper($title)." ".strtoupper($surname).", ".$first_name." ".$other_name;
          $real_pass=@base64_decode($pass);  ///student password is not encoded
     }

     if($found==true)
     {
          ////&&&&&&&&&&&&&&& Send Email to the candidate &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&quot
          $todayDate = @date("l, F d, Y");

          $to = $email; $subject = "Password Recovery Notification";
          $msg = "Hello <strong>$login_id  $fullname</strong> <br /><br /> You filled our password recovery form on $todayDate. <br /><br />Find below your login details: <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login ID: $login_id<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password: $real_pass <br /><br /><strong>NOTE:</strong> Always keep your password safe. It should also be noted that your password is case-sensitive and must be typed as appeared in this mail. <br /><br /><strong>Best Regards.<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UNILORIN Portal Team</strong>";

          $headers = "From: info@kwcoetl.com   \r\n";
          $headers .= "MIME-Version: 1.0\r\n";
          $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
          @mail($to,$subject,$msg,$headers);
          ///&&&&&&&&&&&&&&&& End of send message &&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&
          echo "<div class='valid_msg'>Password successfully recovered. <br/>An alert has been sent to your email address <br/><font color='blue'>$email</font>.<br/>Login to your email for confirmation.</b></div>";
          exit;
     } //end of password recovery
     else
     {
          echo "<div class='error_msg'>Invalid parameters. Please verify.</div>";
          exit;
     } //end of not found as student or staff


     exit;
} //end of update recovery

if($id=='save_staff' or $id=='edit_save_staff')  //for saving,editing and deleting approve/publish results
{
     $action=@$_REQUEST['action'];
     $id=$_REQUEST['id']; //for editing and deleteing
     $mydata=$_REQUEST['mydata']; //for json data in bulk
     $j=json_decode(stripslashes($mydata));
     //echo "MYDATA: $mydata"; exit;
     $dept_str=@explode("***",$j->dept); $dept_id=trim($dept_str[0]); $fact_id=trim($dept_str[1]);
     //echo "SNAME: $j->sname FNAME: $j->fname ONAME: $j->oname DEPT_ID: $dept_id FACTID: $fact_id"; exit;
     $login_id=@$_SESSION['login_id']; $added_date=@date('Y-m-d');$added_time=@date('h:i:s a');
     $password=base64_encode("1111");

     if($action=='edit')
     {
          $id_val=@$_REQUEST['id_val'];
          $db->sql("SELECT * FROM stafftb where id='$id_val'");
          $t= @json_decode(stripslashes($db->getResult()));
          $s_array=array(s_detail=>"",msg=>"");

          if($t->row>=1) //fond
          {
               $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
          }
          exit;
     }//end of edit
     if($action=='save')
     {
          $res_e=@mysqli_query($con, "SELECT * FROM stafftb where fileno='$j->fileno'");
          if(@mysqli_num_rows($res_e)>=1) //already exist
          {
               mysqli_query($con, "update stafftb set fileno='$j->fileno',title='$j->title',surname='$j->sname',first_name='$j->fname',other_name='$j->oname',email='$j->email',tel_no='$j->phone',staff_category='$j->staff_cat',dept_id='$dept_id',fact_id='$fact_id',status='Active',entry_by='$login_id' where fileno='$j->fileno' limit 1") or die( mysqli_error($con));
               ?>
               <script>$.messager.alert('Update Profile','Operation is successful');</script>
               <?php
          } //normal update after edit
          else
          {
               mysqli_query($con, "INSERT INTO stafftb set fileno='$j->fileno',title='$j->title',surname='$j->sname',first_name='$j->fname',other_name='$j->oname',email='$j->email',tel_no='$j->phone',staff_category='$j->staff_cat',dept_id='$dept_id',fact_id='$fact_id',password='$password',status='Active',added_date='$added_date',added_time='$added_time',entry_by='$login_id'") or die( mysqli_error($con));
               ?>
               <script>$.messager.alert('Add Staff','Operation is successful');</script>
               <?php
          } //normal save

          //load sql is used to re-display the content in the table back
          $load_sql="select s.id,s.fileno,s.title,s.surname,s.first_name,s.other_name,s.email,s.tel_no,s.staff_category,d.dept_name from stafftb s, depttb d where s.dept_id=d.dept_id and s.dept_id='$dept_id' order by s.surname,s.first_name";

          //log the activity
          $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d'); $login_id=@$_SESSION['login_id'];
          @mysqli_query($con, "INSERT INTO portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Update staff details $j->fileno','Staff details updated by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
     }

     if($action=='search')
     {
          $load_sql="select s.id,s.fileno,s.title,s.surname,s.first_name,s.other_name,s.email,s.tel_no,s.staff_category,d.dept_name from stafftb s, depttb d where s.dept_id=d.dept_id ";
          if($j->fileno!='')
          $load_sql.=" and s.fileno='$j->fileno'";
          if($j->sname!='')
          $load_sql.=" and s.surname like '%$j->sname%'";
          if($dept_id!='')
          $load_sql.=" and s.dept_id='$dept_id'";

          $load_sql.=" order by s.surname,s.first_name";
     }//end of search

     if($action=='delete')
     {
          $res_chk=@mysqli_query($con, "SELECT * FROM stafftb where id='$id'");  //use to fetch the record back in the criteria
          $rs_chk=@mysqli_fetch_array($res_chk);
          $d_id=@$rs_chk['dept_id'];


          @mysqli_query($con, "DELETE FROM stafftb where id='$id'");  //delete record

          $load_sql="select s.id,s.fileno,s.title,s.surname,s.first_name,s.other_name,s.email,s.tel_no,s.staff_category,d.dept_name from stafftb s, depttb d where s.dept_id=d.dept_id and s.dept_id='$d_id' order by s.surname,s.first_name";
          ?>
          <script>$.messager.alert('Delete Staff','Record deleted successfully');</script>
          <?php
     } //end of delete action


     ////////// display the content back ///////////////////////////////////////////////////////////
     $res_load=@mysqli_query($con, $load_sql);
     $sn=0;
     if(@mysqli_num_rows($res_load)>=1)
     {
          $thead="<table><tr><th>S/N</th><th>FILE NO</th><th>NAME</th><th>DEPARTMENT</th><th>EMAIL</th><th>PHONE NO</th><th>CATEGORY</th><th>ACTION</th></tr>";
          while($rs_load=@mysqli_fetch_array($res_load))
          {
               ++$sn;
               $id=@$rs_load['id'];
               $fno=@$rs_load['fileno'];
               $title=strtoupper(@$rs_load['title']);
               $sname=@$rs_load['surname'];
               $fname=@$rs_load['first_name'];
               $oname=@$rs_load['other_name'];
               $fullname=strtoupper($sname)." ".ucfirst(strtolower($fname))." ".ucfirst(strtolower($oname))."($title)";
               $dname=@$rs_load['dept_name'];
               $email=@$rs_load['email'];
               $mobile=@$rs_load['tel_no'];
               $s_cat=@$rs_load['staff_category'];

               if ($sn%2!=0) $bgcolor='#E5C78B'; else $bgcolor='';

               $tbody="<tr bgcolor='$bgcolor'><td>$sn</td><td>$fno</td><td>$fullname</td><td>$dname</td><td>$email</td><td>$mobile</td><td>$s_cat</td><td><a href=\"javascript:swapcontent('save_staff','edit','$id');\"><b>EDIT</b></a> | <a href=\"javascript: if(confirm('Are you sure you want to delete this record?')==true) swapcontent('save_staff','delete','$id');\"><b>DELETE</b></a></td>";

               // <a href=\"javascript: if(confirm('Are you sure you want to delete this record?')==true) swapcontent('save_staff','delete','$id');\"><b>DELETE</b></a>

               $thead.=$tbody;
          } //end of while for loading

          echo $thead;
     } //record are available
     else
     {
          echo "<script>$.messager.alert('No Record','No record to display');</script>";
     } //end of no record to display

     exit;
} //end of save_staff

if($id=='load_role')
{
     $fileno_str=explode("***",@$_REQUEST['fileno']);
     $fact_id=$fileno_str[0];  $dept_id=$fileno_str[1];  $fileno=$fileno_str[2];
     $res_r=@mysqli_query($con, "SELECT * FROM roletb where status='Active'");
     $sn=0;
     $tb="<fieldset><legend><b>AVAILABLE ROLES</b></legend><center><table>";
     while($rs_r=@mysqli_fetch_array($res_r))
     {
          ++$sn;
          $role_name=@$rs_r['role'];
          //echo "$fact_id $dept_id $fileno $role_name";
          if(is_role_exist($fileno,$role_name)) $chk="checked='checked'"; else $chk="";
          $tb.="<tr><td><input type='checkbox' name='role_name[]' id='role_name$sn' value='$role_name' $chk/></td><td>$role_name</td></tr>";

     } //end of while

     $tb.="</table><input type='button' value='Update Role' class='btn' onClick=\"swapcontent('update_role','update');\"/></center></fieldset>";
     echo $tb;
}

if($id=='update_role')
{
     $fileno_str=explode("***",@$_REQUEST['fileno']);
     $fact_id=$fileno_str[0];  $dept_id=$fileno_str[1];  $fileno=$fileno_str[2];
     $role_name=$_REQUEST['role_name']; $action=$_REQUEST['action'];
     $login_id=@$_SESSION['login_id'];
     $id_val=$_REQUEST['id_val']; //for activating/deactivating role
     $role_status=$_REQUEST['role_status']; //whether active/inactive
     //echo "$role_name $action";
     if($action=='update') {
          if(count($role_name)<1) echo "<font color='red'><b>You did not select any role from the list</b></font>";
          foreach($role_name as $role_value)
          {
               $res_f=@mysqli_query($con, "SELECT * FROM staff_roletb where fileno='$fileno' and role='$role_value'");
               if(@mysqli_num_rows($res_f)>=1) @mysqli_query($con, "update staff_roletb set status='Active' where fileno='$fileno' and role='$role_value'");
               else
               @mysqli_query($con, "INSERT INTO staff_roletb set fileno='$fileno',fact_id='$fact_id',dept_id='$dept_id',role='$role_value',status='Active',added_date=CURDATE(),added_time=CURTIME(),entry_by='$login_id',activity='$role_value role added by $login_id'");
          }
          echo "<p><font color='#D3E488'><b>Role updated successfully</b></font></p>";
          $sql="SELECT * FROM staff_roletb where fileno='$fileno'";
     } //end of action ==update

     if($action=='update_status')
     {
          //$role_status; $id_val
          $r_name=$_REQUEST['r_name'];
          $added_date=@date('Y-m-d'); $added_time=@date('h:i:s');
          if($role_status=='Active') $a="Inactive"; else $a="Active";
          @mysqli_query($con, "update staff_roletb set status='$a',activity='$r_name role modified by $login_id on $added_date at $added_time' where fileno='$fileno' and role='$r_name'");
          echo "<p><font color='#D3E488'><b>Role updated successfully</b></font></p>";
          $sql="SELECT * FROM staff_roletb where fileno='$fileno'";
     } //end of update status

     if($action=='view')
     {
          $sql="SELECT * FROM staff_roletb where fileno='$fileno'";
     }

     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $tb="<center><table><tr><th>S/NO</th><th>FILE NO</th><th>ROLE</th><th>ROLE STATUS</th><th>ACTION</th></tr>";
     while($rs_v= mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id_v=$rs_v['id'];
          $f_no=$rs_v['fileno'];
          $r_name=$rs_v['role'];
          $r_status=$rs_v['status'];
          if($r_status=='Active') $opt='De-activate'; else $opt='Activate';
          $tb.="<tr><td>$sn</td><td>$f_no</td><td>$r_name</td><td>$r_status</td><td><input type='button' value='$opt' class='btn' onClick=\"swapcontent('update_role','update_status','$id_v','$r_status','$r_name');\"/></td></tr>";
     }

     $tb.="</table></center>";
     echo $tb;


     exit;
} //end of update role

if($id=='budget_section')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     //$code=$j->code;
     //$name=@mysqli_real_escape_string($con, $j->name);
     //$status=$j->status;
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
          $res_chk=@mysqli_query($con, "SELECT * FROM budgettb where folio_code='$j->folio' and dept_code='$j->dept' and unit_code='$j->unit' and budget_year='$j->b_year'");
          if(@mysqli_num_rows($res_chk)>=1)
          {
               $row=@$j->row_id;  //row id of record to edit
               @mysqli_query($con, "update budgettb set folio_code='$j->folio',dept_code='$j->dept',unit_code='$j->unit',budget_year='$j->b_year',amount='$j->amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where id='$row'");
          }
          else
          {
               @mysqli_query($con, "INSERT INTO budgettb set folio_code='$j->folio',dept_code='$j->dept',unit_code='$j->unit',budget_year='$j->b_year',amount='$j->amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          } //end of save

          logs("$login_id","Save Record","$login_id saved budget record $j->folio $j->dept $j->unit_code $j->budget_year $j->amount");
          $sql="SELECT * FROM budgettb where dept_code='$j->dept' and budget_year='$j->b_year' order by folio_code,dept_code,unit_code,budget_year";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM budgettb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['folio_code'].$rs_d['dept_code'].$rs_d['unit_code'].$rs_d['budget_year'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted budget record $log_desc");

          @mysqli_query($con, "DELETE FROM budgettb where id='$r_id'");
          $sql="SELECT * FROM budgettb where dept_code='{$rs_d['dept_code']}' and budget_year='{$rs_d['budget_year']}' order by folio_code,dept_code,unit_code,budget_year";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="SELECT * FROM budgettb where 1";
          if($j->folio!="") $sql.=" and folio_code='$j->folio'";
          if($j->dept!="") $sql.=" and dept_code='$j->dept'";
          if($j->unit!="") $sql.=" and unit_code='$j->unit'";
          if($j->b_year!="") $sql.=" and budget_year='$j->b_year'";

          $sql.=" order by folio_code,dept_code,unit_code,budget_year";
     }

     if($action=='edit')
     {
          //$res_b=@mysqli_query($con, "SELECT * FROM budgettb where id='$r_id'");

          //$fileno=@$_REQUEST['fileno'];
          $db->sql("SELECT * FROM budgettb where id='$r_id'");
          if(get_magic_quotes_gpc())
          $t= @json_decode(stripslashes($db->getResult()));
          else
          $t= @json_decode($db->getResult());
          $s_array=array(s_detail=>"",msg=>"");

          if($t->row>=1) //fond
          {
               $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
          }
          exit;

     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<table><tr><th>S/N</th><th>FOLIO</th><th>DEPARTMENT</th><th>UNIT</th><th>YEAR</th><th>AMOUNT</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $g_total+=$rs_v['amount'];
               $tb.="<tr><td>$sn</td><td>".@get_folio_name($rs_v['folio_code'])."</td><td>".@get_dept_name($rs_v['dept_code'])."</td><td>".@get_unit_name($rs_v['dept_code'],$rs_v['unit_code'])."</td><td>{$rs_v['budget_year']}</td><td>N".number_format($rs_v['amount'],2)."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('budget_section','delete','$r_id');\">DELETE</a> || <a href=\"javascript:swapcontent('budget_section','edit','$r_id');\">EDIT</a></td></tr>";
          }//end of while

          $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          $tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

     exit;
}

if($id=='load_unit')
{
     $dept_code=@$_REQUEST['dept_code'];
     //echo "$dept_code"; ?>
     <select name="unit" id="unit">
          <option selected="selected" value="">---</option>
          <?php
          $res_c=@mysqli_query($con, "SELECT * FROM unittb where dept_code='$dept_code' order by unit_name");
          while($rs_c=@mysqli_fetch_array($res_c))
          {
               $unit_code=@$rs_c['unit_code'];
               $unit_name=@$rs_c['unit_name'];
               echo "<option value='$unit_code'>$unit_name</option>";
          }
          ?>
     </select> <?php

     exit;
}

if($id=='load_staff_details')
{
     $fileno=@$_REQUEST['fileno'];
     $db->sql("SELECT * FROM stafftb where fileno='$fileno'");
     if(get_magic_quotes_gpc())
     $t= @json_decode(stripslashes($db->getResult()));
     else
     $t= @json_decode($db->getResult());
     $s_array=array(s_detail=>"",msg=>"");

     if($t->row>=1) //fond
     {
          $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
     }
     exit;
}

if($id=='generate_pvno')
{
     $pay_date=@$_REQUEST['pay_date'];
     $voucher_unit=@$_REQUEST['voucher_unit'];
     $month_name=@date('F',strtotime($pay_date));
     $month_no=@date('m',strtotime($pay_date));
     $yearx=@date('ys');//,strtotime($pay_date));
     $year=@date('Y',strtotime($pay_date));
     $res_p=@mysqli_query($con, "select count(*) as total from vouchertb where month(voucher_date)='$month_no' and year(voucher_date)='$year'");
     $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1).rand(10, 54765);

     $pvno=strtoupper($voucher_unit."/".$month_no."/".$yearx."/". $no); //echo $month_no;
     echo "<b><font color='red'>$pvno</font></b><input type='hidden' name='pvno' id='pvno' value='$pvno'/>";

     exit;
}

if($id=='load_voucher_fileno')
{
     $type=@$_REQUEST['type'];
     if($type=='Internal')
     {
          //echo "File No.(Staff): ";

          echo "<b>File Number</b> <br/><select class='txt easyui-combogrid' style='width:300px' name='fileno' id='fileno' onchange=\"swapcontent('load_payee_details',this.value);\"><option selected value=''>---</option>";
          $res_s=@mysqli_query($con, "select fileno,surname,first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') order by surname");//convert(fileno,decimal)
          while($rs_s=@mysqli_fetch_array($res_s))
          {
               $fileno=$rs_s['fileno'];
               $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
               echo "<option value='$fileno'>$fileno || $name</option>";
          }
          echo "</select>";
     }
     elseif ($type=='External')
     {
          //echo "Phone No.(Non-Staff) ";
          echo "<b>Phone Number:</b><br><input style='width:300px' type='text' name='fileno' id='fileno'/>";
     }elseif($type=='InternalLoan')
     {
          //echo "File No.(Staff): ";

          echo "<b>Staff</b> <br/><select class='txt easyui-combogrid' style='width:300px' name='fileno' id='fileno' onchange=\"swapcontent('load_payee_details',this.value);\"><option selected value=''>---</option>";
          $res_s=@mysqli_query($con, "SELECT fileno, surname, first_name, other_name from stafftb where fileno not in ('Admin','Weathstone','School') order by surname, first_name, other_name");
          while($rs_s=@mysqli_fetch_array($res_s))
          {
               $fileno=$rs_s['fileno'];
               $name=strtoupper($rs_s['surname']).",".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
               echo "<option value='$fileno'>$name || $fileno</option>";
          }
          echo "</select>";
     }
     else
     echo "";

     exit;
}


if($id=='load_voucher_details_entry_rec')
{
     ?><table width="100%">
          <!--<tr><th>&nbsp;</th><tr><th>&nbsp;</th><th>&nbsp;</th><th>&nbsp;</th><th width='5%'></th></tr>-->

          <tr><th>&nbsp;</th><tr><th>&nbsp;</th><th>&nbsp;</th><th width='85%'>TOTAL AMOUNT (&#8358;)</th><th width='5%'><input type='number' name='vamount' id='vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' tabindex='<?=$tab_index;?>' onblur="javascript:display_total();" ></th></tr>


          <!--<tr style="font-size: 18px;font-weight: bold;color:#174C68;" height="33"><td>&nbsp;</td>
          <th>GROSS AMOUNT:</th><th align="center" valign="bottom">
          <div id="total" align="center" ><b>0.00</b></div></th></tr>-->

          <tr><th colspan="3">
               <input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('
               <?php if( $id=='load_voucher_details_entry') echo 'voucher_section_entry'; else echo 'voucher_section'; ?>
               ','save'); " />
               <!--<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('salary_scale_section','search');" />
               <input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('salary_scale_section','view_all');" /> -->
               <input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('
               <?php if( $id=='load_voucher_details_entry') echo 'voucher_section_entry'; else echo 'voucher_section'; ?>
               ','refresh');" />


          </th></tr>
     </table>
     <?php
     exit;
}

if($id=='load_voucher_details' || $id=='load_voucher_details_entry' || $id=='load_voucher_details_entry_final')
{
     ?><table width="100%">
          <tr><th colspan="2">Detail Description of Goods/Services</th>
               <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GROSS AMOUNT (&#8358;)</th></tr>

               <?php
               echo "<tr><th colspan='2'>Being <textarea name='desc' id='desc' cols='45' rows='3'></textarea></th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='number' name='vamount' id='vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' tabindex='$tab_index' onblur=\"javascript:display_total();\" ></th></tr>";
               ?>

               <tr><td colspan="3">
                    <table width="100%" cellpadding="0" align="left">
                         <tr>
                              <th height="28">&nbsp;</th>
                              <td height="28"><strong>Ded. Rate</strong></td>
                              <td height="28"><strong>Ded. Payee</strong></td>
                              <td nowrap="nowrap"><strong>Acct. Number</strong></td>
                              <td nowrap="nowrap"><strong>Bank Name</strong></td>
                              <td height="28" nowrap="nowrap"><strong>Action</strong></td>
                              <td align="center" valign="middle" nowrap="nowrap" id="dvat_val2" style=""><strong>Ded.</strong></td>
                         </tr>
                         <tr>
                              <th width="106" height="36">VAT (%):</th>
                              <td height="36"><input type="number" id="dvat" name="dvat" value="0" min="0" max="100" onChange="
                                   var dvat=$('#dvat').val()*1;
                                   var amt=$('#vamount').val()*1;
                                   var val_calc=0;	var total_ded=0;
                                   //val_calc=(amt/100) * dvat;
                                   if(dvat >= 0){
                                        if($('#dvat_inc').prop('checked') == true){
                                             val_calc=(dvat/(dvat + 100))*amt; //alert(1234);
                                        }
                                        else if($('#dvat_inc').prop('checked') == false){
                                             val_calc=(dvat/100)*amt;
                                        }
                                        $('#dvat_val').html(val_calc);
                                        total_ded = (amt - (($('#dvat_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                        $('#total_deduction').html(total_ded);
                                   }else {
                                        total_ded = ($('#total_deduction').html() * 1) - ($('#dvat_val').html() * 1);
                                        $('#dvat_val').html(0);
                                        $('#total_deduction').html(total_ded);
                                   }
                                   "></td>
                                   <td nowrap="nowrap"><select name="dvat_payee" id="dvat_payee" style="width:100px">
                                        <option selected="selected" value="">---</option>
                                        <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                        <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                        <option value="VAT Sub Account">VAT Sub Account</option>
                                        <option value="KWIRS Witholding">KWIRS Witholding</option>
                                        <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                        <option value="KWIRS PAYE">KWIRS PAYE</option>
                                        <option value="Unilorin Endowment">Unilorin Endowment</option>
                                   </select>
                              </td>
                              <td width="79" nowrap="nowrap"><input type="number" id="dvat_acct" name="dvat_acct" value="" style="width:100px" /></td>
                              <td width="79" nowrap="nowrap"><select name="dvat_bank" id="dvat_bank" style="width:100px">
                                   <option selected="selected" value="">---</option>
                                   <?php
                                   $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                   while ($rcourse=@mysqli_fetch_array($r))
                                   {
                                        $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                        echo "<option value='$pcode'>$scourse</option>";

                                   }

                                   ?>
                              </select></td>
                              <td width="79" height="36" nowrap="nowrap"><label for="dvat_pv"><input type="checkbox" id="dvat_pv" name="dvat_pv" value="yes" >Create PV.</label></td>
                              <td width="79" align="center" valign="middle" nowrap="nowrap" class="deduction" id="dvat_val" style="">0</td>
                         </tr>
                         <tr>
                              <th height="36" nowrap="nowrap">Withold. Tax (%):</th>
                              <td height="36"><input type="number" id="dtax" name="dtax" value="0" min="0" max="100" onChange=" 		var dvat=$('#dvat').val()*1;
                                   var dtax=$('#dtax').val()*1;
                                   var amt=$('#vamount').val()*1;
                                   var val_calc=0;	var total_ded=0;
                                   var tval = $('#dtax_val').html() * 1;
                                   //val_calc=(amt/100) * dtax;

                                   if(dtax >= 0){
                                        if($('#dvat_inc').prop('checked') == true){
                                             val_calc=(dtax/(dvat + 100))*amt;
                                        }
                                        else if($('#dtax_inc').prop('checked') == false){
                                             val_calc=(dtax/100)*amt;
                                        }
                                        $('#dtax_val').html(val_calc);
                                        total_ded = (amt - (($('#dvat_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                        $('#total_deduction').html(total_ded);
                                   }else{
                                        total_ded = ($('#total_deduction').html() * 1) - ($('#dtax_val').html() * 1);
                                        $('#dtax_val').html(0);			$('#total_deduction').html(total_ded);
                                   } ">
                                   <input type="hidden" id="tax_code" name="tax_code" value="41030102" /></td>
                                   <td nowrap="nowrap"><select name="dtax_payee" id="dtax_payee" style="width:100px">
                                        <option selected="selected" value="">---</option>
                                        <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                        <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                        <option value="VAT Sub Account">VAT Sub Account</option>
                                        <option value="KWIRS Witholding">KWIRS Witholding</option>
                                        <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                        <option value="KWIRS PAYE">KWIRS PAYE</option>
                                        <option value="Unilorin Endowment">Unilorin Endowment</option>
                                   </select>
                              </td>
                              <td><input type="number" id="dtax_acct" name="dtax_acct" value="" style="width:100px" /></td>
                              <td><select name="dtax_bank" id="dtax_bank" style="width:100px">
                                   <option selected="selected" value="">---</option>
                                   <?php
                                   $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                   while ($rcourse=@mysqli_fetch_array($r))
                                   {
                                        $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                        echo "<option value='$pcode'>$scourse</option>";

                                   }

                                   ?>
                              </select></td>
                              <td height="36"><label for="dtax_pv"><input type="checkbox" id="dtax_pv" name="dtax_pv" value="yes" >Create PV.</label></td>
                              <td align="center" valign="middle" class="deduction" id="dtax_val">0</td>
                         </tr>
                         <tr>
                              <th height="36" nowrap="nowrap">Endowment (%):</th>
                              <td height="36"><input type="number" id="dendowment" name="dendowment" value="0" min="0" max="100" onChange="
                                   var dvat=$('#dvat').val()*1;
                                   var dend=$('#dendowment').val()*1;
                                   var amt=$('#vamount').val()*1;
                                   var val_calc=0;	var total_ded=0;
                                   //val_calc=(amt/100) * dend;

                                   if(dend >= 0){
                                        if($('#dvat_inc').prop('checked') == true){
                                             val_calc=(dend/(dvat + 100))*amt;
                                        }
                                        else if($('#dtax_inc').prop('checked') == false){
                                             val_calc=(dend/100)*amt;
                                        }
                                        $('#dendowment_val').html(val_calc);
                                        total_ded = (amt - (($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                        $('#total_deduction').html(total_ded);
                                   }else{
                                        total_ded = ($('#total_deduction').html() * 1) - ($('#dendowment_val').html() * 1);
                                        $('#dendowment_val').html(0);			$('#total_deduction').html(total_ded);
                                   }"></td>
                                   <td nowrap="nowrap"><select name="dend_payee" id="dend_payee" style="width:100px">
                                        <option selected="selected" value="">---</option>
                                        <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                        <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                        <option value="VAT Sub Account">VAT Sub Account</option>
                                        <option value="KWIRS Witholding">KWIRS Witholding</option>
                                        <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                        <option value="KWIRS PAYE">KWIRS PAYE</option>
                                        <option value="Unilorin Endowment">Unilorin Endowment</option>
                                   </select>
                              </td>
                              <td><input type="number" id="dendowment_acct" name="dendowment_acct" value="" style="width:100px" /></td>
                              <td><select name="dendowment_bank" id="dendowment_bank" style="width:100px">
                                   <option selected="selected" value="">---</option>
                                   <?php
                                   $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                   while ($rcourse=@mysqli_fetch_array($r))
                                   {
                                        $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                        echo "<option value='$pcode'>$scourse</option>";

                                   }

                                   ?>
                              </select></td>
                              <td height="36"><label for="dendowment_pv"><input type="checkbox" id="dendowment_pv" name="dendowment_pv" value="yes" >Create PV.</label><input type="hidden" id="end_code" name="end_code" value="41-002-4056" /></td>
                              <td align="center" valign="middle" class="deduction" id="dendowment_val">0</td>
                         </tr>
                         <tr>
                              <th height="36" nowrap="nowrap">Stamp Duty (%):</th>
                              <td height="36"><input type="number" id="dstamp" name="dstamp" value="0" min="0" max="100" onChange="
                                   var dvat=$('#dvat').val()*1;
                                   var dstamp=$('#dstamp').val()*1;
                                   var amt=$('#vamount').val()*1;
                                   var val_calc=0;	var total_stamp=0;
                                   //val_calc=(amt/100) * dstamp;

                                   if(dstamp >= 0){
                                        if($('#dvat_inc').prop('checked') == true){
                                             val_calc=(dstamp/(dvat + 100))*amt;
                                        }
                                        else if($('#dtax_inc').prop('checked') == false){
                                             val_calc=(dstamp/100)*amt;
                                        }
                                        $('#dstamp_val').html(val_calc);
                                        total_stamp = (amt - (($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                        $('#total_deduction').html(total_stamp);
                                   }else{
                                        total_ded = ($('#total_deduction').html() *1) + ($('#dstamp_val').html() * 1);
                                        $('#dstamp_val').html(0);
                                        $('#total_deduction').html(total_stamp);
                                   }"></td>
                                   <td nowrap="nowrap"><select name="dstamp_payee" id="dstamp_payee" style="width:100px">
                                        <option selected="selected" value="">---</option>
                                        <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                        <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                        <option value="VAT Sub Account">VAT Sub Account</option>
                                        <option value="KWIRS Witholding">KWIRS Witholding</option>
                                        <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                        <option value="KWIRS PAYE">KWIRS PAYE</option>
                                        <option value="Unilorin Endowment">Unilorin Endowment</option>
                                   </select>
                              </td>
                              <td><input type="number" id="dstamp_acct" name="dstamp_acct" value="" style="width:100px" /></td>
                              <td><select name="dstamp_bank" id="dstamp_bank" style="width:100px">
                                   <option selected="selected" value="">---</option>
                                   <?php
                                   $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                   while ($rcourse=@mysqli_fetch_array($r))
                                   {
                                        $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                        echo "<option value='$pcode'>$scourse</option>";

                                   }

                                   ?>
                              </select></td>
                              <td height="36"><label for="dstamp_pv"><input type="checkbox" id="dstamp_pv" name="dstamp_pv" value="yes" >Create PV.</label><input type="hidden" id="stamp_code" name="stamp_code" value="41-002-4056" /></td>
                              <td align="center" valign="middle" class="deduction" id="dstamp_val">0</td>
                         </tr>
                    </table>
               </td>
          </tr>
          <tr style="font-size: 18px;font-weight: bold;color:#174C68;" height="33"><td>&nbsp;</td>
               <th>GROSS AMOUNT:</th><th align="center" valign="bottom">
                    <div id="total" align="center" ><b>0.00</b></div></th></tr>

                    <tr style="font-size: 18px;font-weight: bold;color:#174C68;"><th><input type="checkbox" id="dvat_inc" name="dvat_inc" onChange="
                         if(($('#dvat').val() * 1) >= 0){

                              var dvat=$('#dvat').val()*1;
                              var amt=$('#vamount').val()*1;
                              var val_calc=0;	var total_ded=0;

                              var dtax=$('#dtax').val()*1;
                              var wht_calc=0;

                              var dend=$('#dendowment').val()*1;
                              var end_calc=0;

                              var dstamp=$('#dstamp').val()*1;
                              var stamp_calc=0;

                              if($(this).prop('checked') == true){
                                   //compute VAT
                                   val_calc=(dvat/(dvat + 100))*amt;
                                   //compute WHT
                                   wht_calc=((dtax/(dvat + 100))*amt);
                                   //compute endowment
                                   end_calc=(dend/(dvat + 100))*amt;
                                   //compute Stamp Duty
                                   stamp_calc=(dstamp/(dvat + 100))*amt;
                              }else if($(this).prop('checked') == false){
                                   //compute VAT
                                   val_calc=(dvat/100)*amt;
                                   //compute WHT
                                   wht_calc=(dtax/100)*amt;
                                   //compute endowment
                                   end_calc=(dend/100)*amt;
                                   //compute Stamp Duty
                                   stamp_calc=(dstamp/100)*amt;
                              }
                              //VAT output
                              $('#dvat_val').html(val_calc);
                              //WHT output
                              $('#dtax_val').html(wht_calc);
                              //ENDOWMENT output
                              $('#dendowment_val').html(end_calc);
                              //STAMP DUTY output
                              $('#dstamp_val').html(stamp_calc);

                              total_ded = (amt - (($('#dvat_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                              $('#total_deduction').html(total_ded);
                         }" value="yes">
                         <strong>Incl. VAT</strong><input type="hidden" id="vat_code" name="vat_code" value="41030103" /></th>
                         <th height="33">NET AMOUNT:</th><th align="center" valign="bottom">

                              <div id="total_deduction" align="center" ><b>0.00</b></div></th></tr>


                              <tr><th colspan="3">
                                   <input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('<?php if( $id=="load_voucher_details_entry_final") echo "voucher_section_entry_final"; elseif( $id=="load_voucher_details_entry") echo "voucher_section_entry"; elseif( $id=="load_voucher_details") echo "voucher_section"; ?>', 'save'); " />
                                   <!--<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('salary_scale_section','search');" />
                                   <input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('salary_scale_section','view_all');" /> -->
                                   <input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('<?php if( $id=="load_voucher_details_entry_final") echo "voucher_section_entry_final"; elseif( $id=="load_voucher_details_entry") echo "voucher_section_entry"; elseif( $id=="load_voucher_details") echo "voucher_section"; ?>', 'refresh'); " />


                              </th></tr>
                         </table>
                         <?php

                         exit;
                    }

                    if($id=='load_voucher_details_salary')
                    {
                         ?><table width="100%">
                              <tr><th colspan="2">Detail Description of Goods/Services</th>
                                   <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;GROSS AMOUNT (&#8358;)</th></tr>

                                   <?php
                                   echo "<tr><th colspan='2'>Being <textarea name='desc' id='desc' cols='45' rows='3'></textarea></th><th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='number' name='vamount' id='vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' tabindex='$tab_index' onblur=\"javascript:display_total();\" ></th></tr>";
                                   ?>

                                   <tr><td colspan="3">
                                        <table width="100%" cellpadding="0" align="left">
                                             <tr>
                                                  <th height="28">&nbsp;</th>
                                                  <td height="28"><strong>Ded. Rate</strong></td>
                                                  <td height="28"><strong>Payee</strong></td>
                                                  <td nowrap="nowrap"><strong>Acct. Number</strong></td>
                                                  <td nowrap="nowrap"><strong>Bank Name</strong></td>
                                                  <td height="28" nowrap="nowrap"><strong>Action</strong></td>
                                                  <td align="center" valign="middle" nowrap="nowrap" id="dvat_val2" style=""><strong>Ded.</strong></td>
                                             </tr>
                                             <tr>
                                                  <th width="106" height="36">VAT (%):</th>
                                                  <td height="36"><input type="number" id="dvat" name="dvat" value="0" min="0" max="100" onChange="
                                                       var dvat=$('#dvat').val()*1;
                                                       var amt=$('#vamount').val()*1;
                                                       var val_calc=0;	var total_ded=0;
                                                       //val_calc=(amt/100) * dvat;
                                                       if(dvat >= 0){
                                                            if($('#dvat_inc').prop('checked') == true){
                                                                 val_calc=(dvat/(dvat + 100))*amt; //alert(1234);
                                                            }
                                                            else if($('#dvat_inc').prop('checked') == false){
                                                                 val_calc=(dvat/100)*amt;
                                                            }
                                                            $('#dvat_val').html(val_calc);
                                                            total_ded = (amt - (($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                            $('#total_deduction').html(total_ded);
                                                       }else {
                                                            total_ded = ($('#total_deduction').html() * 1) - ($('#dvat_val').html() * 1);
                                                            $('#dvat_val').html(0);
                                                            $('#total_deduction').html(total_ded);
                                                       }
                                                       " style="width:70px"></td>
                                                       <td width="79" nowrap="nowrap"><select name="dvat_payee" id="dvat_payee" style="width:100px">
                                                            <option selected="selected" value="">---</option>
                                                            <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                                            <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                                            <option value="VAT Sub Account">VAT Sub Account</option>
                                                            <option value="KWIRS Witholding">KWIRS Witholding</option>
                                                            <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                                            <option value="KWIRS PAYE">KWIRS PAYE</option>
                                                            <option value="Unilorin Endowment">Unilorin Endowment</option>
                                                       </select>
                                                  </td>
                                                  <td width="79" nowrap="nowrap"><input type="number" id="dvat_acct" name="dvat_acct" value="" style="width:100px" /></td>
                                                  <td width="79" nowrap="nowrap"><select name="dvat_bank" id="dvat_bank" style="width:100px">
                                                       <option selected="selected" value="">---</option>
                                                       <?php
                                                       $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                                       while ($rcourse=@mysqli_fetch_array($r))
                                                       {
                                                            $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                                            echo "<option value='$pcode'>$scourse</option>";

                                                       }

                                                       ?>
                                                  </select></td>
                                                  <td width="79" height="36" nowrap="nowrap"><label for="dvat_pv"><input type="checkbox" id="dvat_pv" name="dvat_pv" value="yes" >Create PV.</label></td>
                                                  <td width="79" align="center" valign="middle" nowrap="nowrap" class="deduction" id="dvat_val" style="">0</td>
                                             </tr>
                                             <tr>
                                                  <th height="36" nowrap="nowrap">Withold. Tax (%):</th>
                                                  <td height="36"><input type="number" id="dtax" name="dtax" value="0" min="0" max="100" onChange=" 		var dvat=$('#dvat').val()*1;
                                                       var dtax=$('#dtax').val()*1;
                                                       var amt=$('#vamount').val()*1;
                                                       var val_calc=0;	var total_ded=0;
                                                       var tval = $('#dtax_val').html() * 1;
                                                       //val_calc=(amt/100) * dtax;

                                                       if(dtax >= 0){
                                                            if($('#dvat_inc').prop('checked') == true){
                                                                 val_calc=(dtax/(dvat + 100))*amt;
                                                            }
                                                            else if($('#dtax_inc').prop('checked') == false){
                                                                 val_calc=(dtax/100)*amt;
                                                            }
                                                            $('#dtax_val').html(val_calc);
                                                            total_ded = (amt - (($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                            $('#total_deduction').html(total_ded);
                                                       }else{
                                                            total_ded = ($('#total_deduction').html() * 1) - ($('#dtax_val').html() * 1);
                                                            $('#dtax_val').html(0);			$('#total_deduction').html(total_ded);
                                                       } " style="width:70px">
                                                       <input type="hidden" id="tax_code" name="tax_code" value="41030102" /></td>
                                                       <td width="79" nowrap="nowrap"><select name="dtax_payee" id="dtax_payee" style="width:100px">
                                                            <option selected="selected" value="">---</option>
                                                            <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                                            <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                                            <option value="VAT Sub Account">VAT Sub Account</option>
                                                            <option value="KWIRS Witholding">KWIRS Witholding</option>
                                                            <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                                            <option value="KWIRS PAYE">KWIRS PAYE</option>
                                                            <option value="Unilorin Endowment">Unilorin Endowment</option>
                                                       </select>
                                                  </td>
                                                  <td><input type="number" id="dtax_acct" name="dtax_acct" value="" style="width:100px" /></td>
                                                  <td><select name="dtax_bank" id="dtax_bank" style="width:100px">
                                                       <option selected="selected" value="">---</option>
                                                       <?php
                                                       $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                                       while ($rcourse=@mysqli_fetch_array($r))
                                                       {
                                                            $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                                            echo "<option value='$pcode'>$scourse</option>";

                                                       }

                                                       ?>
                                                  </select></td>
                                                  <td height="36"><label for="dtax_pv"><input type="checkbox" id="dtax_pv" name="dtax_pv" value="yes" >Create PV.</label></td>
                                                  <td align="center" valign="middle" class="deduction" id="dtax_val">0</td>
                                             </tr>

                                             <tr>
                                                  <th height="36" nowrap="nowrap">Endowment (%):</th>
                                                  <td height="36"><input type="number" id="dendowment" name="dendowment" value="0" min="0" max="100" onChange="
                                                       var dvat=$('#dvat').val()*1;
                                                       var dend=$('#dendowment').val()*1;
                                                       var amt=$('#vamount').val()*1;
                                                       var val_calc=0;	var total_ded=0;
                                                       //val_calc=(amt/100) * dend;

                                                       if(dend >= 0){
                                                            if($('#dvat_inc').prop('checked') == true){
                                                                 val_calc=(dend/(dvat + 100))*amt;
                                                            }
                                                            else if($('#dtax_inc').prop('checked') == false){
                                                                 val_calc=(dend/100)*amt;
                                                            }
                                                            $('#dendowment_val').html(val_calc);
                                                            total_ded = (amt - (($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                            $('#total_deduction').html(total_ded);
                                                       }else{
                                                            total_ded = ($('#total_deduction').html() * 1) - ($('#dendowment_val').html() * 1);
                                                            $('#dendowment_val').html(0);			$('#total_deduction').html(total_ded);
                                                       }" style="width:70px"></td>
                                                       <td width="79" nowrap="nowrap"><select name="dend_payee" id="dend_payee" style="width:100px">
                                                            <option selected="selected" value="">---</option>
                                                            <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                                            <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                                            <option value="VAT Sub Account">VAT Sub Account</option>
                                                            <option value="KWIRS Witholding">KWIRS Witholding</option>
                                                            <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                                            <option value="KWIRS PAYE">KWIRS PAYE</option>
                                                            <option value="Unilorin Endowment">Unilorin Endowment</option>
                                                       </select>
                                                  </td>
                                                  <td><input type="number" id="dendowment_acct" name="dendowment_acct" value="" style="width:100px" /></td>
                                                  <td><select name="dendowment_bank" id="dendowment_bank" style="width:100px">
                                                       <option selected="selected" value="">---</option>
                                                       <?php
                                                       $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                                       while ($rcourse=@mysqli_fetch_array($r))
                                                       {
                                                            $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                                            echo "<option value='$pcode'>$scourse</option>";

                                                       }

                                                       ?>
                                                  </select></td>
                                                  <td height="36"><label for="dendowment_pv"><input type="checkbox" id="dendowment_pv" name="dendowment_pv" value="yes" >Create PV.</label><input type="hidden" id="end_code" name="end_code" value="41-002-4056" /></td>
                                                  <td align="center" valign="middle" class="deduction" id="dendowment_val">0</td>
                                             </tr>
                                             <tr>
                                                  <th height="36" nowrap="nowrap">Stamp Duty (%):</th>
                                                  <td height="36"><input type="number" id="dstamp" name="dstamp" value="0" min="0" max="100" onChange="
                                                       var dvat=$('#dvat').val()*1;
                                                       var dstamp=$('#dstamp').val()*1;
                                                       var amt=$('#vamount').val()*1;
                                                       var val_calc=0;	var total_ded=0;
                                                       //val_calc=(amt/100) * dstamp;

                                                       if(dstamp >= 0){
                                                            if($('#dvat_inc').prop('checked') == true){
                                                                 val_calc=(dstamp/(dvat + 100))*amt;
                                                            }
                                                            else if($('#dtax_inc').prop('checked') == false){
                                                                 val_calc=(dstamp/100)*amt;
                                                            }
                                                            $('#dstamp_val').html(val_calc);
                                                            total_ded = (amt - (($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                            $('#total_deduction').html(total_stamp);
                                                       }else{
                                                            total_ded = ($('#total_deduction').html() *1) + ($('#dstamp_val').html() * 1);
                                                            $('#dstamp_val').html(0);
                                                            $('#total_deduction').html(total_ded);
                                                       }" style="width:70px"></td>
                                                       <td width="79" nowrap="nowrap"><select name="dstamp_payee" id="dstamp_payee" style="width:100px">
                                                            <option selected="selected" value="">---</option>
                                                            <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                                            <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                                            <option value="VAT Sub Account">VAT Sub Account</option>
                                                            <option value="KWIRS Witholding">KWIRS Witholding</option>
                                                            <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                                            <option value="KWIRS PAYE">KWIRS PAYE</option>
                                                            <option value="Unilorin Endowment">Unilorin Endowment</option>
                                                       </select>
                                                  </td>
                                                  <td><input type="number" id="dstamp_acct" name="dstamp_acct" value="" style="width:100px" /></td>
                                                  <td><select name="dstamp_bank" id="dstamp_bank" style="width:100px">
                                                       <option selected="selected" value="">---</option>
                                                       <?php
                                                       $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                                       while ($rcourse=@mysqli_fetch_array($r))
                                                       {
                                                            $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                                            echo "<option value='$pcode'>$scourse</option>";

                                                       }

                                                       ?>
                                                  </select></td>
                                                  <td height="36"><label for="dstamp_pv"><input type="checkbox" id="dstamp_pv" name="dstamp_pv" value="yes" >Create PV.</label><input type="hidden" id="stamp_code" name="stamp_code" value="41-002-4056" /></td>
                                                  <td align="center" valign="middle" class="deduction" id="dstamp_val">0</td>
                                             </tr>

                                             <tr>
                                                  <th height="36" nowrap="nowrap">Pension (%):</th>
                                                  <td height="36"><input type="number" id="dpension" name="dpension" value="0" min="0" max="100" onChange="
                                                       var dvat=$('#dvat').val()*1;
                                                       var dpen=$('#dpension').val()*1;
                                                       var amt=$('#vamount').val()*1;
                                                       var val_calc=0;	var total_ded=0;
                                                       if(dpen >= 0){
                                                            val_calc=(dpen/100) * amt;
                                                            $('#dpen_val').html(val_calc);
                                                            total_ded = (amt - (($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                            $('#total_deduction').html(total_ded);
                                                       }else{
                                                            total_ded = ($('#total_deduction').html() * 1) - ($('#dpen_val').html() * 1);
                                                            $('#dpen_val').html(0);			$('#total_deduction').html(total_ded);
                                                       }" style="width:70px"></td>
                                                       <td width="79" nowrap="nowrap"><select name="dpen_payee" id="dpen_payee" style="width:100px">
                                                            <option selected="selected" value="">---</option>
                                                            <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                                            <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                                            <option value="VAT Sub Account">VAT Sub Account</option>
                                                            <option value="KWIRS Witholding">KWIRS Witholding</option>
                                                            <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                                            <option value="KWIRS PAYE">KWIRS PAYE</option>
                                                            <option value="Unilorin Endowment">Unilorin Endowment</option>
                                                            <option value="CIE Expatriate Pension">CIE Expatriate Pension</option>
                                                       </select>
                                                  </td>
                                                  <td><input type="number" id="dpen_acct" name="dpen_acct" value="" style="width:100px" /></td>
                                                  <td><select name="dpen_bank" id="dpen_bank" style="width:100px">
                                                       <option selected="selected" value="">---</option>
                                                       <?php
                                                       $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                                       while ($rcourse=@mysqli_fetch_array($r))
                                                       {
                                                            $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                                            echo "<option value='$pcode'>$scourse</option>";

                                                       }

                                                       ?>
                                                  </select></td>
                                                  <td height="36"><label for="dpen_pv"><input type="checkbox" id="dpen_pv" name="dpen_pv" value="yes" >Create PV.</label><input type="hidden" id="pen_code" name="pen_code" value="01-541-2030" /></td>
                                                  <td align="center" valign="middle" class="deduction" id="dpen_val">0</td>
                                             </tr>

                                             <tr>
                                                  <th height="36" nowrap="nowrap">NHF (%):</th>
                                                  <td height="36"><input type="number" id="dnhf" name="dnhf" value="0" min="0" max="100" onChange="
                                                       var dvat=$('#dvat').val()*1;
                                                       var dnhf=$('#dnhf').val()*1;
                                                       var amt=$('#vamount').val()*1;
                                                       var val_calc=0;	var total_ded=0;
                                                       //val_calc=(amt/100) * dstamp;

                                                       if(dnhf >= 0){
                                                            val_calc = (dnhf/100) * amt;
                                                            $('#dnhf_val').html(val_calc);
                                                            total_ded = (amt - (($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                            $('#total_deduction').html(total_ded);
                                                       }else{
                                                            total_ded = ($('#total_deduction').html() *1) + ($('#dnhf_val').html() * 1);
                                                            $('#dnhf_val').html(0);
                                                            $('#total_deduction').html(total_ded);
                                                       }" style="width:70px"></td>
                                                       <td width="79" nowrap="nowrap"><select name="dnhf_payee" id="dnhf_payee" style="width:100px">
                                                            <option selected="selected" value="">---</option>
                                                            <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                                            <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                                            <option value="VAT Sub Account">VAT Sub Account</option>
                                                            <option value="KWIRS Witholding">KWIRS Witholding</option>
                                                            <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                                            <option value="KWIRS PAYE">KWIRS PAYE</option>
                                                            <option value="Unilorin Endowment">Unilorin Endowment</option>
                                                       </select>
                                                  </td>
                                                  <td><input type="number" id="dnhf_acct" name="dnhf_acct" value="" style="width:100px" /></td>
                                                  <td><select name="dnhf_bank" id="dnhf_bank" style="width:100px">
                                                       <option selected="selected" value="">---</option>
                                                       <?php
                                                       $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                                       while ($rcourse=@mysqli_fetch_array($r))
                                                       {
                                                            $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                                            echo "<option value='$pcode'>$scourse</option>";

                                                       }

                                                       ?>
                                                  </select></td>
                                                  <td height="36"><label for="dnhf_pv"><input type="checkbox" id="dnhf_pv" name="dstamp_pv" value="yes" >Create PV.</label><input type="hidden" id="nhf_code" name="nhf_code" value="41-002-4056" /></td>
                                                  <td align="center" valign="middle" class="deduction" id="dnhf_val">0</td>
                                             </tr>

                                             <tr>
                                                  <th height="36" nowrap="nowrap">Union Due (%):</th>
                                                  <td height="36"><input type="number" id="due" name="due" value="0" min="0" max="100" onChange="
                                                       var dvat=$('#dvat').val()*1;
                                                       var due=$('#due').val()*1;
                                                       var amt=$('#vamount').val()*1;
                                                       var val_calc=0;	var total_ded=0;
                                                       if(due >= 0){
                                                            val_calc=(due/100) * amt;
                                                            $('#due_val').html(val_calc);
                                                            total_ded = (amt - (($('#due_val').html() * 1) + ($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                            $('#total_deduction').html(total_ded);
                                                       }else{
                                                            total_ded = ($('#total_deduction').html() * 1) - ($('#due_val').html() * 1);
                                                            $('#due_val').html(0);			$('#total_deduction').html(total_ded);
                                                       }" style="width:70px"></td>
                                                       <td width="79" nowrap="nowrap"><select name="due_payee" id="due_payee" style="width:100px">
                                                            <option selected="selected" value="">---</option>
                                                            <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                                            <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                                            <option value="VAT Sub Account">VAT Sub Account</option>
                                                            <option value="KWIRS Witholding">KWIRS Witholding</option>
                                                            <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                                            <option value="KWIRS PAYE">KWIRS PAYE</option>
                                                            <option value="Unilorin Endowment">Unilorin Endowment</option>
                                                       </select>
                                                  </td>
                                                  <td><input type="number" id="due_acct" name="due_acct" value="" style="width:100px" /></td>
                                                  <td><select name="due_bank" id="due_bank" style="width:100px">
                                                       <option selected="selected" value="">---</option>
                                                       <?php
                                                       $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                                       while ($rcourse=@mysqli_fetch_array($r))
                                                       {
                                                            $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                                            echo "<option value='$pcode'>$scourse</option>";

                                                       }

                                                       ?>
                                                  </select></td>
                                                  <td height="36"><label for="due_pv"><input type="checkbox" id="due_pv" name="due_pv" value="yes" >Create PV.</label><input type="hidden" id="due_code" name="due_code" value="01-000-1012" /></td>
                                                  <td align="center" valign="middle" class="deduction" id="due_val">0</td>
                                             </tr>
                                             <tr>
                                                  <th height="36" nowrap="nowrap">Other Relief:</th>
                                                  <td height="36" colspan="4"><input type="number" id="oref" name="oref" value="0" min="0" max="100" onChange="" style="width:170px"></td>
                                             </tr>

                                             <tr> <!-- PAYE TAX DEDUCTION SECTION  -->
                                                  <th width="106" height="36"><input type="button" id="btndpaye" name="btndpaye" value="PAYE Tax (AUTO)" onClick="
                                                       var amt=$('#vamount').val()*1;
                                                       var dvat=$('#dvat').val()*1;
                                                       var dpaye=$('#dpaye').val()*1;
                                                       var gross = ($('#vamount').val()*1) - ($('#oref').val()*1);
                                                       var gCent1=( 1.0 / 100.0 ) * gross;
                                                       var pension = $('#dpen_val').html()*1;
                                                       var NHF = $('#dnhf_val').html()*1;
                                                       if(gCent1 < 200000) gCent1 = 200000;

                                                       var gCent20=( 20.0 / 100.0 ) * gross;
                                                       var newGross = gross - gCent1 - gCent20 - pension - NHF;
                                                       var gCent7 = 0; var gCent11 = 0; var gCent15 = 0; var gCent19 = 0; var gCent21 = 0; var gCent24 = 0;

                                                       var mod3h = newGross / 300000;
                                                       if(mod3h >= 1.0){
                                                            gCent7 = (7.0/100.0) * 300000;
                                                            var mod3h2 = (newGross - 300000) / 300000;
                                                            if(mod3h2 >= 1.0){
                                                                 gCent11 = (11.0/100.0) * 300000;
                                                                 var mod5h = (newGross - 300000 - 300000) / 500000;
                                                                 if(mod5h >= 1.0){
                                                                      gCent15 = (15.0/100.0) * 500000;
                                                                      var mod5h2 = (newGross - 300000 - 300000 - 500000) / 500000;
                                                                      if(mod5h2 >= 1.0){
                                                                           gCent19 = (19.0/100.0) * 500000;
                                                                           var mod16h = (newGross - 300000 - 300000 - 500000 - 500000) / 1600000;
                                                                           if(mod16h >= 1.0){
                                                                                gCent21 = (21.0/100.0) * 1600000;
                                                                                var mod32h = (newGross - 300000 - 300000 - 500000 - 500000 - 1600000) / 3200000;
                                                                                if(mod32h >= 1.0){
                                                                                     gCent24 = (24.0/100.0) * 3200000;
                                                                                }else{
                                                                                     gCent24 = (24.0/100.0) * (newGross - 300000 - 300000 - 500000 - 500000 - 1600000 - 3200000);
                                                                                }
                                                                           }else{
                                                                                gCent21 = (21.0/100.0) * (newGross - 300000 - 300000 - 500000 - 1600000);
                                                                           }
                                                                      }else{
                                                                           gCent19 = (19.0/100.0) * (newGross - 300000 - 300000 - 500000);
                                                                      }
                                                                 }else{
                                                                      gCent15 = (15.0/100.0) * (newGross - 300000 - 300000);
                                                                 }
                                                            }else{
                                                                 gCent11 = (11.0/100.0) * (newGross - 300000);
                                                            }
                                                       }else{
                                                            gCent7 = (11.0/100.0) * newGross;
                                                       }
                                                       var annualTax = (gCent7 + gCent11 + gCent15 + gCent19 + gCent21 + gCent24);
                                                       $('#dpaye').val(annualTax);
                                                       $('#dpaye_val').html(annualTax);

                                                       //alert(newVal);
                                                       var val_calc=0;	var total_ded=0;
                                                       //val_calc=(amt/100) * dvat;
                                                       if(dpaye >= 0){
                                                            val_calc=annualTax;
                                                            $('#dpaye_val').html(val_calc);
                                                            total_ded = (amt - (($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                            $('#total_deduction').html(total_ded);
                                                       }else {
                                                            total_ded = ($('#total_deduction').html() * 1) - ($('#dpaye_val').html() * 1);
                                                            $('#dpaye_val').html(0);
                                                            $('#total_deduction').html(total_ded);
                                                       }
                                                       ">
                                                       <input type="hidden" id="paye_code" name="paye_code" value="01-002-4039" /></th>
                                                       <td height="36"><input type="number" id="dpaye" name="dpaye" value="0" min="0" onchange="
                                                            var dpaye=$('#dpaye').val()*1;
                                                            var amt=$('#vamount').val()*1;
                                                            var val_calc=0;	var total_ded=0;
                                                            if(dpaye >= 0){
                                                                 val_calc=$('#dpaye').val();
                                                                 $('#dpaye_val').html(val_calc);
                                                                 total_ded = (amt - (($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                                 $('#total_deduction').html(total_ded);
                                                            }else {
                                                                 total_ded = ($('#total_deduction').html() * 1) - ($('#dpaye_val').html() * 1);
                                                                 $('#dpaye_val').html(0);
                                                                 $('#total_deduction').html(total_ded);
                                                            }
                                                            " style="width:70px"  /></td>
                                                            <td width="79" nowrap="nowrap"><select name="dpaye_payee" id="dpaye_payee" style="width:100px">
                                                                 <option selected="selected" value="">---</option>
                                                                 <option value="Federation Sub Account Stamp Duty">Fed. Sub Acc. Stamp Duty</option>
                                                                 <option value="Federation Sub Account Witholding">Fed. Sub Acc. WHT</option>
                                                                 <option value="VAT Sub Account">VAT Sub Account</option>
                                                                 <option value="KWIRS Witholding">KWIRS Witholding</option>
                                                                 <option value="KWIRS Stamp Duty">KWIRS Stamp Duty</option>
                                                                 <option value="KWIRS PAYE">KWIRS PAYE</option>
                                                                 <option value="Unilorin Endowment">Unilorin Endowment</option>
                                                            </select>
                                                       </td>

                                                       <td width="79" nowrap="nowrap"><input type="number" id="dpaye_acct" name="dpaye_acct" value=""  style="width:100px" /></td>
                                                       <td width="79" nowrap="nowrap"><select name="dpaye_bank" id="dpaye_bank" style="width:100px">
                                                            <option selected="selected" value="">---</option>
                                                            <?php
                                                            $r=@mysqli_query($con, "select distinct bankname  from banktb order by bankname");
                                                            while ($rcourse=@mysqli_fetch_array($r))
                                                            {
                                                                 $scourse=@$rcourse['bankname'];$pcode=@$rcourse['bankname'];
                                                                 echo "<option value='$pcode'>$scourse</option>";

                                                            }

                                                            ?>
                                                       </select></td>
                                                       <td width="79" height="36" nowrap="nowrap"><label for="dpaye_pv"><input type="checkbox" id="dpaye_pv" name="dpaye_pv" value="yes" >Create PV.</label></td>
                                                       <td width="79" align="center" valign="middle" nowrap="nowrap" class="deduction" id="dpaye_val" style="">0</td>
                                                  </tr>
                                             </table>
                                        </td>
                                   </tr>
                                   <tr style="font-size: 18px;font-weight: bold;color:#174C68;" height="33"><td>&nbsp;</td>
                                        <th>GROSS AMOUNT:</th><th align="center" valign="bottom">
                                             <div id="total" align="center" ><b>0.00</b></div></th></tr>

                                             <tr style="font-size: 18px;font-weight: bold;color:#174C68;"><th><input type="checkbox" id="dvat_inc" name="dvat_inc" onChange="
                                                  if(($('#dvat').val() * 1) >= 0){

                                                       var dvat=$('#dvat').val()*1;
                                                       var amt=$('#vamount').val()*1;
                                                       var val_calc=0;	var total_ded=0;

                                                       var dtax=$('#dtax').val()*1;
                                                       var wht_calc=0;

                                                       var dend=$('#dendowment').val()*1;
                                                       var end_calc=0;

                                                       var dstamp=$('#dstamp').val()*1;
                                                       var stamp_calc=0;

                                                       if($(this).prop('checked') == true){
                                                            //compute VAT
                                                            val_calc=(dvat/(dvat + 100))*amt;
                                                            //compute WHT
                                                            wht_calc=((dtax/(dvat + 100))*amt);
                                                            //compute endowment
                                                            end_calc=(dend/(dvat + 100))*amt;
                                                            //compute Stamp Duty
                                                            stamp_calc=(dstamp/(dvat + 100))*amt;
                                                       }else if($(this).prop('checked') == false){
                                                            //compute VAT
                                                            val_calc=(dvat/100)*amt;
                                                            //compute WHT
                                                            wht_calc=(dtax/100)*amt;
                                                            //compute endowment
                                                            end_calc=(dend/100)*amt;
                                                            //compute Stamp Duty
                                                            stamp_calc=(dstamp/100)*amt;
                                                       }
                                                       //VAT output
                                                       $('#dvat_val').html(val_calc);
                                                       //WHT output
                                                       $('#dtax_val').html(wht_calc);
                                                       //ENDOWMENT output
                                                       $('#dendowment_val').html(end_calc);
                                                       //STAMP DUTY output
                                                       $('#dstamp_val').html(stamp_calc);

                                                       total_ded = (amt - (($('#due_val').html() * 1) + ($('#dpen_val').html() * 1) + ($('#dnhf_val').html() * 1) + ($('#dpaye_val').html() * 1) + ($('#dendowment_val').html() * 1) + ($('#dtax_val').html() * 1) + ($('#dvat_val').html() * 1) + ($('#dstamp_val').html() * 1)));
                                                       $('#total_deduction').html(total_ded);
                                                  }" value="yes">
                                                  <strong>Incl. VAT</strong><input type="hidden" id="vat_code" name="vat_code" value="41030103" /></th>
                                                  <th height="33">NET AMOUNT:</th><th align="center" valign="bottom">

                                                       <div id="total_deduction" align="center" ><b>0.00</b></div></th></tr>


                                                       <tr><th colspan="3">
                                                            <input type="button" class="btn" name="sbtn" id="sbtn" value="Save Voucher" onclick="swapcontent('voucher_section_salary','save'); " />
                                                            <!--<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('voucher_section_salary','search');" />
                                                            <input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('voucher_section_salary','view_all');" /> -->
                                                            <input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('voucher_section_salary','refresh');" />


                                                       </th></tr>
                                                  </table>
                                                  <?php

                                                  exit;
                                             }

                                             if($id=='voucher_section_salary')
                                             {
                                                  /*echo "<script>alert('$paye_amount');</script>";*/
                                                  $action=@$_REQUEST['action'];
                                                  $r_id=@$_REQUEST['r_id'];
                                                  $login_id=@$_SESSION['login_id'];
                                                  //$j=@json_decode(stripslashes($mydata)); //encode the json data
                                                  //$dept_code=explode("***",$j->dept_code);

                                                  if($r_id !="")
                                                  {
                                                       $d=@mysqli_query($con, "SELECT * FROM vouchertb where id = '$r_id'");
                                                       $ds=@mysqli_fetch_array($d);
                                                       $d_pvno=@$ds['pvno']; $d_payee_name=@$ds['payee_name']; $d_fileno=@$ds['fileno'];

                                                       $r_ids=@$ds['id'];
                                                  }

                                                  //collect fields frm form
                                                  $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date'])); $dept=@$_REQUEST['funddept']; $pvno=@$_REQUEST['pvno'];
                                                  $account=@$_REQUEST['account']; 	$folio=@$_REQUEST['folio']; 	$type=@$_REQUEST['type'];
                                                  $fileno=@$_REQUEST['fileno']; $name=@$_REQUEST['name']; $act_no=@$_REQUEST['act_no']; $bank=@$_REQUEST['bank'];
                                                  $address=@mysqli_real_escape_string($con, @$_REQUEST['address']); $vamount=@$_REQUEST['vamount']; $desc=@mysqli_real_escape_string($con, @$_REQUEST['desc']);

                                                  $payee_tin_number=@mysqli_real_escape_string($con, @$_REQUEST['payee_tin_number']);
                                                  $payee_sort_code=@mysqli_real_escape_string($con, @$_REQUEST['payee_sort_code']);
                                                  $voucher_unit=@$_REQUEST['voucher_unit'];
                                                  $bcode=@$_REQUEST['bcode'];$bamt=@$_REQUEST['bamt'];
                                                  $autocreate=@$_REQUEST['autocreate'];
                                                  $login_id=@$_SESSION['login_id'];
                                                  $memo_id=@$_REQUEST['memo_id'];
                                                  $amt_approved=@$_REQUEST['amt_approved'];
                                                  $process_type=$_REQUEST['pro_typ'];
                                                  $isPA=$_REQUEST['ispa'];

                                                  $vat_incl=@$_REQUEST['dvat_inc'];
                                                  $dvat=@$_REQUEST['dvat'];		$dvat_pv=@$_REQUEST['dvat_pv'];			$vat_code=@$_REQUEST['vat_code'];
                                                  $vat_bank=@$_REQUEST['dvat_bank'];	$vat_acct=@$_REQUEST['dvat_acct'];	$vat_payee=@$_REQUEST['dvat_payee'];

                                                  $dtax=@$_REQUEST['dtax'];		$dtax_pv=@$_REQUEST['dtax_pv'];			$tax_code=@$_REQUEST['tax_code'];
                                                  $tax_bank=@$_REQUEST['dtax_bank'];	$tax_acct=@$_REQUEST['dtax_acct'];	$tax_payee=@$_REQUEST['dtax_payee'];

                                                  $dend=@$_REQUEST['dendowment'];	$dend_pv=@$_REQUEST['dendowment_pv'];	$end_code=@$_REQUEST['end_code'];
                                                  $end_bank=@$_REQUEST['dendowment_bank'];	$end_acct=@$_REQUEST['dendowment_acct'];	$end_payee=@$_REQUEST['dend_payee'];

                                                  $dstamp=@$_REQUEST['dstamp'];	$dstamp_pv=@$_REQUEST['dstamp_pv'];	$stamp_code=@$_REQUEST['stamp_code'];
                                                  $stamp_bank=@$_REQUEST['dstamp_bank'];	$stamp_acct=@$_REQUEST['dstamp_acct'];	$stamp_payee=@$_REQUEST['dstamp_payee'];

                                                  $dpen=@$_REQUEST['dpension'];	$dpen_pv=@$_REQUEST['dpen_pv'];	$pen_code=@$_REQUEST['pen_code'];
                                                  $pen_bank=@$_REQUEST['dpen_bank'];	$pen_acct=@$_REQUEST['dpen_acct'];	$pen_payee=@$_REQUEST['dpen_payee'];

                                                  $dnhf=@$_REQUEST['dnhf'];	$dnhf_pv=@$_REQUEST['dnhf_pv'];	$nhf_code=@$_REQUEST['nhf_code'];
                                                  $nhf_bank=@$_REQUEST['dnhf_bank'];	$nhf_acct=@$_REQUEST['dnhf_acct'];	$nhf_payee=@$_REQUEST['dnhf_payee'];

                                                  $dpaye=@$_REQUEST['dpaye'];	$dpaye_pv=@$_REQUEST['dpaye_pv'];	$paye_code=@$_REQUEST['paye_code'];
                                                  $paye_bank=@$_REQUEST['dpaye_bank'];	$paye_acct=@$_REQUEST['dpaye_acct'];	$paye_payee=@$_REQUEST['dpaye_payee'];

                                                  $due=@$_REQUEST['due'];	$due_pv=@$_REQUEST['due_pv'];	$due_code=@$_REQUEST['due_code'];
                                                  $due_bank=@$_REQUEST['due_bank'];	$due_acct=@$_REQUEST['due_acct'];	$due_payee=@$_REQUEST['due_payee'];

                                                  ////$other_relief=$_REQUEST['oref'];

                                                  $vcode=@$_REQUEST['code'];$vamt=@$_REQUEST['amount'];  //code is the folio_code and rate

                                                  //$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
                                                  //echo "$vcode ==> $vamt===>$mydata";exit;
                                                  if($action=='save')
                                                  {
                                                       /*if($amt_approved != $vamount)
                                                       {
                                                       echo "<script language='javascript'>alert('Cross check your entry! Amount Approved is not the same with Amount entered');</script>";exit;
                                                  }*/
                                                  foreach($vamt as $amt)
                                                  {
                                                       if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
                                                       {
                                                            echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
                                                       }
                                                  }
                                                  if(count($bcode)>0)
                                                  {
                                                       foreach($bamt as $val_amt)
                                                       {
                                                            if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
                                                            {
                                                                 echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
                                                            }
                                                       }//end of foreach for bamt
                                                  }// end of bcode is not empty

                                                  // End of Validation
                                                  $s=0;$i=0;$j=0;$tamt=0;$emsg=array(); $total_tax=0; $amount=0;
                                                  // transaction begins
                                                  begin();
                                                  //now save to voucher table

                                                  $stamp_amount = 0; $vat_amount = 0; $tax_amount = 0;  $vat_amount = 0;
                                                  $pen_amount = 0; $nhf_amount = 0; $paye_amount = 0;  $due_amount = 0;
                                                  /*if($dstamp_pv == "yes" and $dstamp > 0)	{
                                                  $stamp_amount = (($vamount/100) * $dstamp);
                                                  //$vamount = $vamount - $stamp_amount;
                                             }
                                             if($dend_pv == "yes" and $dend > 0){
                                             $end_amount = (($vamount/100) * $dend);
                                             //$vamount = $vamount - $end_amount;
                                        }
                                        if($dtax_pv == "yes" and $dtax > 0){
                                        $tax_amount = (($vamount/100) * $dtax);
                                        //$vamount = $vamount - $tax_amount;
                                   }*/

                                   if($vat_incl == "yes"){
                                        if($dvat_pv == "yes" and $dvat > 0) $vat_amount = ($dvat/($dvat + 100)) * $vamount;
                                        if($dtax_pv == "yes" and $dtax > 0) $tax_amount = ($dtax/($dvat + 100)) * $vamount;
                                        if($dend_pv == "yes" and $dend > 0) $end_amount = ($dend/($dvat + 100)) * $vamount;
                                        if($dstamp_pv == "yes" and $dstamp > 0) $stamp_amount = ($dstamp/($dvat + 100)) * $vamount;

                                   }elseif($vat_incl != "yes"){
                                        if($dvat_pv == "yes" and $dvat > 0) $vat_amount = ($dvat/100) * $vamount;
                                        if($dtax_pv == "yes" and $dtax > 0) $tax_amount = ($dtax/100) * $vamount;
                                        if($dend_pv == "yes" and $dend > 0) $end_amount = ($dend/100) * $vamount;
                                        if($dstamp_pv == "yes" and $dstamp > 0) $stamp_amount = ($dstamp/100) * $vamount;
                                   }
                                   //pension, NHF and PAYE Tax calculations
                                   if($dpen_pv == "yes" and $dpen > 0) $pen_amount = ($dpen/100) * $vamount;
                                   if($dnhf_pv == "yes" and $dnhf > 0) $nhf_amount = ($dnhf/100) * $vamount;
                                   if($due_pv == "yes" and $due > 0) $due_amount = ($due/100) * $vamount;
                                   if($dpaye_pv == "yes" and $dpaye > 0) $paye_amount = $dpaye;

                                   /*
                                   //PAYE TAX CALCULATION
                                   if($dpaye_pv == "yes" and $dpaye > 0){
                                   $gross=$vamount-$other_relief;
                                   $gCent1=( 1.0 / 100.0 ) * $gross;
                                   if($gCent1 < 200000) $gCent1 = 200000;

                                   $gCent20=( 20.0 / 100.0 ) * $gross;
                                   $newGross = $gross - $gCent1 - $gCent20 - $pen_amount - $nhf_amount;
                                   $gCent7 = 0; $gCent11 = 0; $gCent15 = 0; $gCent19 = 0; $gCent21 = 0; $gCent24 = 0;

                                   $mod3h = $newGross / 300000;
                                   if($mod3h >= 1.0){
                                   $gCent7 = (7.0/100.0) * 300000;
                                   $mod3h2 = ($newGross - 300000) / 300000;
                                   if($mod3h2 >= 1.0){
                                   $gCent11 = (11.0/100.0) * 300000;
                                   $mod5h = ($newGross - 300000 - 300000) / 500000;
                                   if($mod5h >= 1.0){
                                   $gCent15 = (15.0/100.0) * 500000;
                                   $mod5h2 = ($newGross - 300000 - 300000 - 500000) / 500000;
                                   if($mod5h2 >= 1.0){
                                   $gCent19 = (19.0/100.0) * 500000;
                                   $mod16h = ($newGross - 300000 - 300000 - 500000 - 500000) / 1600000;
                                   if($mod16h >= 1.0){
                                   $gCent21 = (21.0/100.0) * 1600000;
                                   $mod32h = ($newGross - 300000 - 300000 - 500000 - 500000 - 1600000) / 3200000;
                                   if($mod32h >= 1.0){
                                   $gCent24 = (24.0/100.0) * 3200000;
                              }else{
                              $gCent24 = (24.0/100.0) * ($newGross - 300000 - 300000 - 500000 - 500000 - 1600000 - 3200000);
                         }
                    }else{
                    $gCent21 = (21.0/100.0) * ($newGross - 300000 - 300000 - 500000 - 1600000);
               }
          }else{
          $gCent19 = (19.0/100.0) * ($newGross - 300000 - 300000 - 500000);
     }
}else{
$gCent15 = (15.0/100.0) * ($newGross - 300000 - 300000);
}
}else{
$gCent11 = (11.0/100.0) * ($newGross - 300000);
}
}else{
$gCent7 = (11.0/100.0) * $newGross;
}
$paye_amount = ($gCent7 + $gCent11 + $gCent15 + $gCent19 + $gCent21 + $gCent24);
} //end of PAYE Tax calculation
*/

$total_tax = $stamp_amount + $vat_amount + $tax_amount + $end_amount + $pen_amount + $nhf_amount + $paye_amount;
$amount_approved=$vamount;
$amount_paid=$amount_approved - $total_tax;  //after tax deduction
$year=@date('Y',strtotime(@$_REQUEST['pay_date']));

$total_budget=@get_budget($folio, $year);

//stepping down budget check at this point
////if( ($amount_paid <= $total_budget) or $total_budget=='' )
{

     if($process_type=="Pending") {
          //echo "INSERT INTO vouchertb set pvno='$pvno', voucher_date='$pay_date', dept_code='$dept', dept_acctcode='$account', payee_type='$type', fileno='$fileno', payee_name='$name', payee_acct_no='$act_no', payee_bank_name='$bank', payee_address='$address', payee_tin_number='$payee_tin_number', payee_sort_code='$payee_sort_code', description='$desc', amount_approved='{$amount_approved}', total_tax='{$total_tax}', amount_paid='{$amount_paid}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final'"; exit;
          $r1=@mysqli_query($con, "INSERT INTO vouchertb set pvno='{$pvno}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', fileno='{$fileno}', payee_name='{$name}', payee_acct_no='{$act_no}', payee_bank_name='{$bank}', payee_address='{$address}', payee_tin_number='{$payee_tin_number}', payee_sort_code='{$payee_sort_code}', description='Being $desc', amount_approved='{$amount_approved}', total_tax='{$total_tax}', amount_paid='{$amount_paid}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final', memo_id='$memo_id', purchase_advance='$isPA'") or die( mysqli_error($con));
     }
     elseif($process_type=="Final") {
          $r1=@mysqli_query($con, "INSERT INTO vouchertb set pvno='{$pvno}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', fileno='{$fileno}', payee_name='{$name}', payee_acct_no='{$act_no}', payee_bank_name='{$bank}', payee_address='{$address}', payee_tin_number='{$payee_tin_number}', payee_sort_code='{$payee_sort_code}', description='Being $desc', amount_approved='{$amount_approved}', total_tax='{$total_tax}', amount_paid='{$amount_paid}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final', checked_by='{$login_id}', date_checked=CURDATE(), time_checked=CURTIME(), checked_action='Approved', co{ntrolled_}by='$login_id', date_controlled=CURDATE(), time_controlled=CURTIME(), controlled_action='Approved',{ authoriz}ed_by='$login_id', date_authorized=CURDATE(), time_authorized=CURTIME(), authorized_action='Approved', paid_by='{$login_id}', date_paid=CURDATE(), time_paid=CURTIME(), paid_action='Approved', final_approval_by='{$login_id}', final_approval_date=CURDATE(), final_approval='Approved', audit_by='{$login_id}', audit_date=CURDATE(), audit_time=CURTIME(), audit_action='Approved', memo_id='$memo_id', purchase_advance='$isPA'") or die( mysqli_error($con));
          //echo "INSERT INTO vouchertb set pvno='$pvno',  voucher_date='$pay_date', dept_code='$dept', dept_acctcode='$account', payee_type='$type', fileno='$fileno', payee_name='$name', payee_acct_no='$act_no', payee_bank_name='$bank', payee_address='$address', payee_tin_number='$payee_tin_number', payee_sort_code='$payee_sort_code', description='Being $desc', amount_approved='{$amount_approved}', total_tax='{$total_tax}', amount_paid='{$amount_paid}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final', checked_by='{$login_id}', date_checked=CURDATE(), time_checked=CURTIME(), checked_action='Approved', controlled_by='{$login_id}', date_controlled=CURDATE(), time_controlled=CURTIME(), controlled_action='Approved', authorized_by='{$login_id}', date_authorized=CURDATE(), time_authorized=CURTIME(), authorized_action='Approved', paid_by='{$login_id}', date_paid=CURDATE(), time_paid=CURTIME(), paid_action='Approved', final_approval_by='{$login_id}', final_approval_date=CURDATE(), final_approval='Approved', audit_by='{$login_id}', audit_date=CURDATE(), audit_time=CURTIME(), audit_action='Approved'";
     }
     //folio_code='$folio',
     //echo $process_type; exit;
     if(count($folio)==1)
     if($process_type=="Pending") $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno', folio_code='$folio[0]', amount='$amount_paid', paid='No'") or die( mysqli_error($con));
     elseif($process_type=="Final") $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno', folio_code='$folio[0]', amount='$amount_paid', paid='Yes'") or die( mysqli_error($con));
     else
     {
          if(count($bcode)>1)
          {
               foreach($bcode as $v)
               {
                    if($process_type=="Pending")$r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='No'") or die( mysqli_error($con));
                    else if($process_type=="Final") $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='Yes'") or die( mysqli_error($con));
                    $s++;
               }
          }
     }
     //exit;

     /*if($vat_incl != "yes" and $dvat > 0)*/{
     if($dvat_pv == "yes" and $dvat > 0){
          $pvno_tax = $pvno."_VAT";

          /*if($vat_incl == "yes") $vat_amount = ($dvat/($dvat + 100)) * $vamount;
          else $vat_amount = ($dvat/100) * $vamount;*/

          $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set pvno='{$pvno_tax}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', payee_name='$vat_payee', payee_acct_no='$vat_acct', payee_bank_name='$vat_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of $dvat% VAT for $desc', amount_approved='{$vat_amount}', amount_paid='{$vat_amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='VAT', memo_id='$memo_id'") or die( mysqli_error($con));
          $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'") or die( mysqli_error($con));
          $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno_tax',folio_code='$folio[0]',amount='$vat_amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
          $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno_tax',folio_code='$folio[0]',amount='$vat_amount', paid='No'") or die( mysqli_error($con));
     }//end if $dvat_pv
}//end if $vat_incl

if($dtax_pv == "yes" and $dtax > 0){
     $pvno_tax = $pvno."_WHT";
     //$tax_amount = (($vamount/100) * $dtax);
     $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set pvno='{$pvno_tax}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', payee_name='{$tax_payee}', payee_acct_no='{$tax_acct}', payee_bank_name='{$tax_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being Witholding Tax Deduction for $desc', amount_approved='{$tax_amount}', amount_paid='{$tax_amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='TAX', memo_id='$memo_id'") or die( mysqli_error($con));
     $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'") or die( mysqli_error($con));
     $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno_tax',folio_code='$folio[0]',amount='$tax_amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
     $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno_tax',folio_code='$folio[0]',amount='$tax_amount', paid='No'") or die( mysqli_error($con));
}//end if $dtax_pv

if($dend_pv == "yes" and $dend > 0){
     $pvno_tax = $pvno."_END";
     //$end_amount = (($vamount/100) * $dend);
     $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set pvno='{$pvno_tax}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', payee_name='{$end_payee}', payee_acct_no='{$end_acct}', payee_bank_name='{$end_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dend}% Endowment for $desc', amount_approved='{$end_amount}', amount_paid='{$end_amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='ENDOWMENT', memo_id='$memo_id'") or die( mysqli_error($con));
     $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'") or die( mysqli_error($con));
     $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}',folio_code='{$folio[0]}',amount='{$end_amount}',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
     $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}',folio_code='{$folio[0]}',amount='{$end_amount}', paid='No'") or die( mysqli_error($con));
}//end if $dend_pv

if($dstamp_pv == "yes" and $dstamp > 0){
     $pvno_stamp = $pvno."_SD";
     //$stamp_amount = (($vamount/100) * $dstamp);
     $r4[]=@mysqli_query($con, "INSERT INTO vouchertb SET pvno='{$pvno_stamp}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', payee_name='{$stamp_payee}', payee_acct_no='{$stamp_acct}', payee_bank_name='{$stamp_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dstamp}% Stamp-Duty for $desc', amount_approved='{$stamp_amount}', amount_paid='{$stamp_amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='STAMP DUTY', memo_id='$memo_id'") or die( mysqli_error($con));
     $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_stamp}'") or die( mysqli_error($con));
     $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno_stamp',folio_code='$folio[0]',amount='$stamp_amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
     $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno_stamp',folio_code='$folio[0]',amount='$stamp_amount', paid='No'") or die( mysqli_error($con));
}//end if $dend_pv

if($dpen_pv == "yes" and $dpen > 0){
     $pvno_pen = $pvno."_PENSION";
     //$pen_amount = (($vamount/100) * $dpen);
     $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set pvno='$pvno_pen', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$pen_payee', payee_acct_no='$pen_acct', payee_bank_name='$pen_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being Pension Contribution  Deduction for $desc', amount_approved='$pen_amount', amount_paid='$pen_amount', prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='PENSION CONTRIBUTION', memo_id='$memo_id'") or die( mysqli_error($con));
     $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb set parent_pvno='$pvno',child_pvno='$pvno_pen'") or die( mysqli_error($con));
     $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno_pen',folio_code='$pen_code',amount='$pen_amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
     $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno_pen',folio_code='$pen_code',amount='$pen_amount', paid='No'") or die( mysqli_error($con));
}//end if $dpen_pv

if($dnhf_pv == "yes" and $dnhf > 0){
     $pvno_nhf = $pvno."_NHF";
     //$nhf_amount = (($vamount/100) * $dnhf);
     $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set pvno='$pvno_nhf', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$nhf_payee', payee_acct_no='$nhf_acct', payee_bank_name='$nhf_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being NHF Deduction for $desc', amount_approved='$nhf_amount', amount_paid='$nhf_amount', prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='NHF', memo_id='$memo_id'") or die( mysqli_error($con));
     $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb set parent_pvno='$pvno',child_pvno='$pvno_nhf'") or die( mysqli_error($con));
     $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno_nhf',folio_code='$folio[0]',amount='$nhf_amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
     $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno_nhf',folio_code='$folio[0]',amount='$nhf_amount', paid='No'") or die( mysqli_error($con));
}//end if $dnhf_pv

if($dpaye_pv == "yes" and $dpaye > 0){
     $pvno_paye = $pvno."_PAYE";
     //$paye_amount = (($vamount/100) * $dpaye);
     $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set pvno='$pvno_paye', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$paye_payee', payee_acct_no='$paye_acct', payee_bank_name='$paye_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Statutory deduction PAYE tax for $desc', amount_approved='$paye_amount', amount_paid='$paye_amount', prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='PAYE TAX', memo_id='$memo_id'") or die( mysqli_error($con));
     $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb set parent_pvno='$pvno',child_pvno='$pvno_paye'") or die( mysqli_error($con));
     $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno_paye',folio_code='$paye_code',amount='$paye_amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
     $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno_paye',folio_code='$paye_code',amount='$paye_amount', paid='No'") or die( mysqli_error($con));
}//end if $dpaye_pv

if($due_pv == "yes" and $due > 0){
     $pvno_due = $pvno."_UNION_DUE";
     //$due_amount = (($vamount/100) * $due);
     $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set pvno='$pvno_due', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$due_payee', payee_acct_no='$due_acct', payee_bank_name='$due_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being UNION DUE Deduction for $desc', amount_approved='$due_amount', amount_paid='$due_amount', prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='UNION DUE', memo_id='$memo_id'") or die( mysqli_error($con));
     $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb set parent_pvno='$pvno',child_pvno='$pvno_due'") or die( mysqli_error($con));
     $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno_due',folio_code='$folio[0]',amount='$due_amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
     $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno_due',folio_code='$folio[0]',amount='$due_amount', paid='No'") or die( mysqli_error($con));
}//end if $due_pv

if($autocreate=='yes')
{
     if(count($vcode)>0)
     {

          foreach($vcode as $codeval)  //code for tax
          {
               $line=$i+1;
               if($codeval !="")
               {
                    $code=@explode("***",$codeval);
                    $tax_folio_code=$code[0];
                    $amount=$vamt[$code[2]];
                    $total_tax+=$amount;
                    $j++;
                    $pvno2=$pvno."/$j";
                    $tax_detail=@get_tax_detail($tax_folio_code);

                    if($process_type=="pending") $r4[]=@mysqli_query($con, "INSERT INTO vouchertb SET pvno='{$pvno2}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$tax_detail[4]}', payee_name='{$tax_detail[5]}', payee_acct_no='{$tax_detail[6]}', payee_bank_name='{$tax_detail[7]}', payee_address='{$tax_detail[8]}', payee_tin_number='{$tax_detail[9]}', payee_sort_code='{$tax_detail[10]}', description='Deduction for: $desc', amount_approved='{$amount}', amount_paid='{$amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='Final', memo_id='$memo_id', purchase_advance='$isPA'");
                    else if($process_type=="final") $r4[]=@mysqli_query($con, "INSERT INTO vouchertb SET pvno='{$pvno2}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$tax_detail[4]}', payee_name='{$tax_detail[5]}', payee_acct_no='{$tax_detail[6]}', payee_bank_name='{$tax_detail[7]}', payee_address='{$tax_detail[8]}', payee_tin_number='{$tax_detail[9]}', payee_sort_code='{$tax_detail[10]}', description='Deduction for: $desc', amount_approved='{$amount}', amount_paid='{$amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final', checked_by='{$login_id}', date_checked=CURDATE(), time_checked=CURTIME(), checked_action='Approved', controlled_by='{$login_id}', date_controlled=CURDATE(), time_controlled=CURTIME(), controlled_action='Approved', authorized_by='{$login_id}', date_authorized=CURDATE(), time_authorized=CURTIME(), authorized_action='Approved', paid_by='{$login_id}', date_paid=CURDATE(), time_paid=CURTIME(), paid_action='Approved', final_approval_by='{$login_id}', final_approval_date=CURDATE(), final_approval='Approved', audit_by='{$login_id}', audit_date=CURDATE(), audit_time=CURTIME(), audit_action='Approved', memo_id='$memo_id', purchase_advance='$isPA'");
                    //folio_code='$tax_folio_code',
                    $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb set parent_pvno='$pvno',child_pvno='$pvno2'");
                    if($process_type=="pending") $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb SET pvno='{$pvno2}',folio_code='{$tax_folio_code}',amount='{$amount}', paid='No'");
                    else if($process_type=="final") $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb SET pvno='{$pvno2}',folio_code='{$tax_folio_code}',amount='{$amount}', paid='Yes'");

               }//end of amount is not empty
               $i++;

          }// end of foreach folio code

     }// end of folio code is not empty for tax deduction
}// end of autocreate tax record is yes
else
{
     $i=0;$j=0;
     if(count($vcode)>0)
     {

          foreach($vcode as $codeval)  //code for tax
          {
               $line=$i+1;
               if($codeval !="")
               {
                    $code=@explode("***",$codeval);
                    $tax_folio_code=$code[0];
                    $amount=$vamt[$code[2]];
                    $total_tax+=$amount;
                    $j++;
                    $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno',folio_code='$tax_folio_code',amount='$amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");


               }//end of amount is not empty
               $i++;

          }// end of foreach folio code

     }// end of folio code is not empty for tax deduction
}//end of else part of autocreate is not ==yes

logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");


$sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
//************** Commite the Transactions
$flag=false;
//echo  "==>$flag<==$r1###$r2%%%%<br>";
if($r1)//and $r2and $r3
{
     $flag=true;
     //echo  "==>1$flag<==<br>";
     foreach($r2 as $r_val2)
     {
          if($r_val2)
          $flag=true;
          else{
               $flag=false;
               break;
          }
     }
     //echo  "==>2$flag<==<br>";
     if($autocreate=="yes")
     {

          foreach($r4 as $r_val)
          {
               if($r_val)
               $flag=true;
               else{
                    $flag=false;
                    break;
               }
          }
          //echo  "==>4$flag<==<br>";
          if($flag)
          {
               foreach($r5 as $r_val5)
               {
                    if($r_val5)
                    $flag=true;
                    else{
                         $flag=false;
                         break;
                    }
               }
               //echo  "==>5$flag<==<br>";
          }


     }// end of if($autocreate=="yes")

     else
     {
          foreach($r6 as $r_val6)
          {
               if($r_val6)
               $flag=true;
               else{
                    $flag=false;
                    break;
               }
          }
          //echo  "==>6$flag<==<br>";
     }//end of not if($autocreate=="yes")


}// end of if($r1 and $r2 and $r3)


//echo  "==>7$flag<==";
if($flag and  mysqli_query($con, "update memo_assigntb set status='Completed',datecompleted=CURDATE() WHERE memo_id='{$memo_id}'"))
{
     mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id = '$memo_id'");
     commit();
     echo "<script>alert('Payment Voucher saved successfully');</script>";
}
else
{
     rollback();
     echo "<script>alert('Operation Failed! Transaction was canceled. ". mysqli_error($con)."');</script>";
}


}




}// end of save




if($action=='delete')
{
     $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
     $pvno=$rs_d['pvno'];
     logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");
     begin();
     if( mysqli_query($con, "DELETE FROM vouchertb where pvno like '$pvno%'") and  mysqli_query($con, "DELETE FROM voucher_taxtb where pvno like '$pvno%'") and  mysqli_query($con, "DELETE FROM voucher_folio_codetb where pvno like '$pvno%'") and  mysqli_query($con, "DELETE FROM voucher_parent_child_taxtb where parent_pvno='$pvno'")) {
          commit();
          echo "<script>alert('Record deleted successfully');</script>";
     }else {
          rollback();
          echo "<script>alert('Error deleting record!');</script>";
     }
     //exit;
     $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
     if($_REQUEST['v_id'] != 'fromList') $action="view";
}

if($action=='view')
{
     $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date, folio_code, pvno";
}

/////////////////////view section ////////////////////
$sn=0;
$res_v=@mysqli_query($con, $sql);
$g_total=0;
$tb="<table border='1' rules='all' frame='box'><tr><th colspan='8' align='center'>Prepared Voucher</th></tr><tr><th>S/N</th><th>PV NO.</th><!--<th>PV NO.</th>--><th>FOLIO</th><th>DEPARTMENT</th><th>DATE</th><th>PAYEE</th><th>AMOUNT</th><!--th>ACTION</th--></tr>";
if(@mysqli_num_rows($res_v)>=1)
{
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $r_id=$rs_v['id'];
          $g_total+=$rs_v['amount_paid'];
          $tb.="<tr><td>$sn</td><td>{$rs_v['pvno']}</td><!--<td>{$rs_v['pvno_paid']}</td>--><td>".@get_folio_name($rs_v['item_code'])."</td><td>".@read_voucher_vote_code($rs_v['pvno'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><!--td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_salary','delete','$r_id');\">DELETE</a></td--></tr>";
     }//end of while

     $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
     $tb.="<tr><td colspan='6' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
     $tb.="</table>";
     echo $tb_s.$tb;
}
else
echo "<b>No record to display</b>";


}// end of voucher_section



if($id=='load_payee_details')
{
     $fileno=@$_REQUEST['fileno'];
     $type=@$_REQUEST['type'];

     if($type=='Internal')
     {
          $res_s=@mysqli_query($con, "SELECT * FROM stafftb where fileno='$fileno'");
          $rs_s=@mysqli_fetch_array($res_s);
          $name=strtoupper($rs_s['surname'])." ".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
          $acct_no=$rs_s['acct_no'];
          $bank_name=$rs_s['bank_name'];
          echo "$name***$acct_no***$bank_name";
          exit;
     }

     exit;
}


if($id=='loan_section')
{
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     if($r_id !="")
     {
          $d=@mysqli_query($con, "SELECT * FROM vouchertb where id = '$r_id'");
          $ds=@mysqli_fetch_array($d);
          $d_pvno=@$ds['pvno']; $d_payee_name=@$ds['payee_name']; $d_fileno=@$ds['fileno'];

          $r_ids=@$ds['id'];
     }

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date'])); $dept=@$_REQUEST['funddept']; $pvno=@$_REQUEST['pvno'];
     $account=@$_REQUEST['account']; 	$folio=@$_REQUEST['folio']; 	$type=@$_REQUEST['type'];
     $fileno=@$_REQUEST['fileno']; $name=@$_REQUEST['name']; $act_no=@$_REQUEST['act_no']; $bank=@$_REQUEST['bank'];
     $address=@mysqli_real_escape_string($con, @$_REQUEST['address']); $vamount=@$_REQUEST['vamount']; $desc=@mysqli_real_escape_string($con, @$_REQUEST['desc']);

     $mduration = $_REQUEST['installment'];
     $start_date = $_REQUEST['start_date'];
     $end_date = $_REQUEST['end_date'];
     $principal = $_REQUEST['principal'];
     $interest = $_REQUEST['cinterest'];
     $installment = $_REQUEST['d_amount'];
     $rate = $_REQUEST['interest'];
     $voucher_unit=@$_REQUEST['voucher_unit'];
     //$bcode=@$_REQUEST['bcode'];$bamt=@$_REQUEST['bamt'];
     //$autocreate=@$_REQUEST['autocreate'];
     $login_id=@$_SESSION['login_id'];
     //$memo_id=@$_REQUEST['memo_id'];
     $amt_approved=@$_REQUEST['amt_approved'];
     $process_type=$_REQUEST['pro_typ'];

     $vcode=@$_REQUEST['code'];$vamt=@$_REQUEST['amount'];  //code is the folio_code and rate

     if($action=='save')
     {
          $s=0;$i=0;$j=0;$tamt=0;$emsg=array(); $amount=0;
          // transaction begins
          begin();
          //now save to voucher table
          $sql1="INSERT INTO vouchertb set memo_id='{$pvno}', pvno='{$pvno}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='$type', fileno='$fileno', payee_name='$name', payee_acct_no='$act_no', payee_bank_name='$bank', payee_address='$address', description='$desc', amount_approved='$principal', amount_paid='$principal', prepared_by='$login_id', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final'";
          $r1=@mysqli_query($con, $sql1) or die( mysqli_error($con));
          //----------------------------------------------------------------------------------------------------------------------------------------
          $sqll="INSERT INTO hr_loan_apptb set fileno='$fileno', loan_no='$pvno', loan_type='$folio[0]', loan_amount='$amt_approved', principal='$principal', interest='$interest', rate='$rate', installment='$installment', app_date='$pay_date', duration='$mduration', repay_start_date='$start_date', repay_end_date='$end_date', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";

          $r2[] = @mysqli_query($con, $sqll);

          //----------------------------------------------------------------------------------------------------------------------------------------

          $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno', folio_code='$folio[0]', amount='$principal', paid='No'") or die( mysqli_error($con));

          logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio[0] $amt_approved");

          //************** Commite the Transactions
          $flag=false;
          //echo  "==>$flag<==$r1###$r2%%%%<br>";
          if($r1)//and $r2and $r3
          {
               $flag=true;
               //echo  "==>1$flag<==<br>";
               foreach($r2 as $r_val2)
               {
                    if($r_val2)
                    $flag=true;
                    else{
                         $flag=false;
                         break;
                    }
               }
          }// end of if($r1 and $r2 and $r3)

          if($flag)
          {
               commit();
               echo "<script>alert('Payment Voucher saved successfully');</script>";
          }
          else
          {
               rollback();
               echo "<script>alert('Operation Failed! Transaction was canceled. ". mysqli_error($con)."');</script>";
          }

     }// end of save


     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
          $pvno=$rs_d['pvno'];
          logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");
          begin();
          @mysqli_query($con, "DELETE FROM hr_loan_apptb where loan_no='$loan_no'");
          @mysqli_query($con, "DELETE FROM hr_loan_guarantortb where loan_no='$loan_no'");

          if( mysqli_query($con, "DELETE FROM vouchertb where id='$r_id'") and  mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_folio_codetb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_parent_child_taxtb where child_pvno='$pvno'")) {
               commit();
               echo "<script>alert('Record deleted successfully');</script>";
          }else {
               rollback();
               echo "<script>alert('Error deleting record!');</script>";
          }
          $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";

          $action="view";
     }

     if($action=='view')
     {
          $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
     }

}// end of loan_section

if($id=='loan_section_entry')
{
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];

     //collect fields frm form
     $pvno=@$_REQUEST['pvno'];
     $folio=@$_REQUEST['folio']; 	
     $type=@$_REQUEST['type'];
     $fileno=@$_REQUEST['fileno']; 
     $name=@$_REQUEST['name']; 
     $act_no=@$_REQUEST['act_no']; 
     $bank=@$_REQUEST['bank'];

     $principal = $_REQUEST['principal'];
     $login_id=@$_SESSION['login_id'];
     $lyear=$_REQUEST['lyear'];

     $vcode=$folio[0]; //@$_REQUEST['code'];
     
     $vamt=@$_REQUEST['amount'];  //code is the folio_code and rate

     if($action=='save')
     {
          $s=0;$i=0;$j=0;$tamt=0;$emsg=array(); $amount=0;
          // transaction begins
          begin();
          //now save to voucher table
          $sql1="INSERT INTO loan_entry set loanid='{$pvno}', entrydate=now(), fileno='{$fileno}', amount='{$principal}', lyear='{$lyear}', entryby='{$login_id}', folio_code='{$vcode}'";
          //$r1=@mysqli_query($con, $sql1) or die( mysqli_error($con));

          if(mysqli_query($con, $sql1)){
               commit();
               echo "<script>alert('Payment Voucher saved successfully');</script>";
          }else{
               rollback();
               echo "<script>alert('Operation Failed! Transaction was canceled. ". mysqli_error($con)."');</script>";
          }
     }// end of save

     if($action=='delete')
     {
          /*$res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
          $pvno=$rs_d['pvno'];
          logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");
          begin();
          @mysqli_query($con, "DELETE FROM hr_loan_apptb where loan_no='$loan_no'");
          @mysqli_query($con, "DELETE FROM hr_loan_guarantortb where loan_no='$loan_no'");

          if( mysqli_query($con, "DELETE FROM vouchertb where id='$r_id'") and  mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_folio_codetb WHERE pvno='{$pvno}'") and  mysqli_query($con, "DELETE FROM voucher_parent_child_taxtb where child_pvno='$pvno'")) {
               commit();
               echo "<script>alert('Record deleted successfully');</script>";
          }else {
               rollback();
               echo "<script>alert('Error deleting record!');</script>";
          }
          $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";

          $action="view";*/
     }

     if($action=='view')
     {
          $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
     }

}// end of loan_section_entry


if($id=='voucher_section')
{

     //$mydata=@$_REQUEST['mydata'];
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     if($r_id !="")
     {
          $d=@mysqli_query($con, "SELECT * FROM vouchertb where id = '$r_id'");
          $ds=@mysqli_fetch_array($d);
          $d_pvno=@$ds['pvno']; $d_payee_name=@$ds['payee_name']; $d_fileno=@$ds['fileno'];

          $r_ids=@$ds['id'];
     }

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date'])); $dept=@$_REQUEST['dept']; $pvno=@$_REQUEST['pvno'];
     $account=@$_REQUEST['account']; $folio=@$_REQUEST['folio']; $type=@$_REQUEST['type'];
     $fileno=@$_REQUEST['fileno']; $name=@$_REQUEST['name']; $act_no=@$_REQUEST['act_no']; $bank=@$_REQUEST['bank'];
     $address=@mysqli_real_escape_string($con, @$_REQUEST['address']); $vamount=@$_REQUEST['vamount']; $desc=@mysqli_real_escape_string($con, @$_REQUEST['desc']);
     $payee_tin_number=@mysqli_real_escape_string($con, @$_REQUEST['payee_tin_number']);
     $payee_sort_code=@mysqli_real_escape_string($con, @$_REQUEST['payee_sort_code']);
     $voucher_unit=@$_REQUEST['voucher_unit'];
     $bcode=@$_REQUEST['bcode'];$bamt=@$_REQUEST['bamt'];
     $autocreate=@$_REQUEST['autocreate'];
     $login_id=@$_SESSION['login_id'];
     $memo_id=@$_REQUEST['memo_id'];
     $amt_approved=@$_REQUEST['amt_approved'];

     /*	foreach($folio as $v)
     echo "$v  ==><br>";
     foreach($bamt as $v)
     echo "$v<br>";
     foreach($bcode as $v)
     echo "$v<br>";

     echo $autocreate;
     //print_r ($folio);
     $tax_detail=@get_tax_detail('7303');
     echo $tax_detail['payee_type']."<=>".$tax_detail[5];
     exit();
     */
     $vcode=@$_REQUEST['code'];$vamt=@$_REQUEST['amount'];  //code is the folio_code and rate

     //$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
     //echo "$vcode ==> $vamt===>$mydata";exit;
     if($action=='save')
     {
          if($amt_approved != $vamount)
          {
               echo "<script language='javascript'>alert('Cross check your entry! Amount Approved is not the same with Amount entered');</script>";exit;
          }
          foreach($vamt as $amt)
          {
               if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
               {
                    echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
               }
          }
          if(count($bcode)>0)
          {
               foreach($bamt as $val_amt)
               {
                    if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
                    {
                         echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
                    }
               }//end of foreach for bamt
          }// end of bcode is not empty
          // End of Validation
          $s=0;$i=0;$j=0;$tamt=0;$emsg=array(); $total_tax=0; $amount=0;
          // transaction begins
          begin();


          //now save to voucher table

          $amount_approved=$vamount;
          $amount_paid=$amount_approved - $total_tax;  //after tax deduction
          $year=@date('Y',strtotime(@$_REQUEST['pay_date']));

          $total_budget=@get_budget($folio,$year);
          if( ($amount_paid <= $total_budget) or $total_budget=='' )
          {

               $r1=@mysqli_query($con, "INSERT INTO vouchertb set memo_id='$memo_id',pvno='$pvno',voucher_date='$pay_date',dept_code='$dept',dept_acctcode='$account',payee_type='$type',fileno='$fileno',payee_name='$name',payee_acct_no='$act_no',payee_bank_name='$bank',payee_address='$address',payee_tin_number='$payee_tin_number',payee_sort_code='$payee_sort_code',description='$desc',amount_approved='$amount_approved',total_tax='$total_tax',amount_paid='$amount_paid',prepared_by='$login_id',date_prepared=CURDATE(),time_prepared=CURTIME(),entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               //folio_code='$folio',
               if(count($folio)==1)
               $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$folio[0]',amount='$amount_approved', paid='No'");
               else
               {
                    if(count($bcode)>1)
                    {
                         foreach($bcode as $v)
                         {
                              $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='No'");
                              $s++;
                         }
                    }
               }

               if($autocreate=='yes')
               {
                    if(count($vcode)>0)
                    {

                         foreach($vcode as $codeval)  //code for tax
                         {
                              $line=$i+1;
                              if($codeval !="")
                              {
                                   $code=@explode("***",$codeval);
                                   $tax_folio_code=$code[0];
                                   $amount=$vamt[$code[2]];
                                   $total_tax+=$amount;
                                   $j++;
                                   $pvno2=$pvno."/$j";
                                   $tax_detail=@get_tax_detail($tax_folio_code);

                                   $r4[]=@mysqli_query($con, "INSERT INTO vouchertb set memo_id='$memo_id',pvno='$pvno2',voucher_date='$pay_date',dept_code='$dept',dept_acctcode='$account',payee_type='$tax_detail[4]',payee_name='$tax_detail[5]',payee_acct_no='$tax_detail[6]',payee_bank_name='$tax_detail[7]',payee_address='$tax_detail[8]',payee_tin_number='$tax_detail[9]',payee_sort_code='$tax_detail[10]',description='Deduction for: $desc',amount_approved='$amount',amount_paid='$amount',prepared_by='$login_id',date_prepared=CURDATE(),time_prepared=CURTIME(),entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
                                   //folio_code='$tax_folio_code',
                                   $r5[]=@mysqli_query($con, "INSERT INTO voucher_parent_child_taxtb set parent_pvno='$pvno',child_pvno='$pvno2'");
                                   $r7[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb SET pvno='{$pvno2}',folio_code='{$tax_folio_code}',amount='{$amount}', paid='No'");


                              }//end of amount is not empty
                              $i++;

                         }// end of foreach folio code

                    }// end of folio code is not empty for tax deduction
               }// end of autocreate tax record is yes
               else
               {
                    $i=0;$j=0;
                    if(count($vcode)>0)
                    {

                         foreach($vcode as $codeval)  //code for tax
                         {
                              $line=$i+1;
                              if($codeval !="")
                              {
                                   $code=@explode("***",$codeval);
                                   $tax_folio_code=$code[0];
                                   $amount=$vamt[$code[2]];
                                   $total_tax+=$amount;
                                   $j++;
                                   $r6[]=@mysqli_query($con, "INSERT INTO voucher_taxtb set pvno='$pvno',folio_code='$tax_folio_code',amount='$amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");


                              }//end of amount is not empty
                              $i++;

                         }// end of foreach folio code

                    }// end of folio code is not empty for tax deduction
               }//end of else part of autocreate is not ==yes

               logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");


               $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
               //************** Commite the Transactions
               $flag=false;
               //echo  "==>$flag<==$r1###$r2%%%%<br>";
               if($r1)//and $r2and $r3
               {
                    $flag=true;
                    //echo  "==>1$flag<==<br>";
                    foreach($r2 as $r_val2)
                    {
                         if($r_val2)
                         $flag=true;
                         else{
                              $flag=false;
                              break;
                         }
                    }
                    //echo  "==>2$flag<==<br>";
                    if($autocreate=="yes")
                    {

                         foreach($r4 as $r_val)
                         {
                              if($r_val)
                              $flag=true;
                              else{
                                   $flag=false;
                                   break;
                              }
                         }
                         //echo  "==>4$flag<==<br>";
                         if($flag)
                         {
                              foreach($r5 as $r_val5)
                              {
                                   if($r_val5)
                                   $flag=true;
                                   else{
                                        $flag=false;
                                        break;
                                   }
                              }
                              //echo  "==>5$flag<==<br>";
                         }


                    }// end of if($autocreate=="yes")

                    else
                    {
                         foreach($r6 as $r_val6)
                         {
                              if($r_val6)
                              $flag=true;
                              else{
                                   $flag=false;
                                   break;
                              }
                         }
                         //echo  "==>6$flag<==<br>";


                    }//end of not if($autocreate=="yes")


               }// end of if($r1 and $r2 and $r3)


               //echo  "==>7$flag<==";
               if($flag and  mysqli_query($con, "update memo_assigntb set status='Completed',datecompleted=CURDATE() WHERE memo_id='{$memo_id}'"))
               {
                    commit();
                    echo "<script>alert('Payment Voucher saved successfully');</script>";
               }
               else
               {
                    rollback();
                    echo "<script>alert('Operation Failed! Transaction was canceled');</script>";
               }


          }
          else
          {
               echo "<script>alert('Error: You have overshoot the budget for this account. Your payment voucher cannot be saved');</script>";
               //@mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'");
               $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
          }




     }// end of save




     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
          $pvno=$rs_d['pvno'];
          logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");

          @mysqli_query($con, "DELETE FROM vouchertb where id='$r_id'");
          @mysqli_query($con, "DELETE FROM voucher_taxtb WHERE pvno='{$pvno}'");
          $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='view')
     {
          $sql="SELECT * FROM vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<table border='1' rules='all' frame='box'><tr><th colspan='7' align='center'>Prepared Voucher</th></tr><tr><th>S/N</th><th>PROCESS NO.</th><!--<th>PV NO.</th><th>FOLIO</th>--><th>DEPARTMENT</th><th>DATE</th><th>PAYEE</th><th>AMOUNT</th><!--th>ACTION</th--></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $g_total+=$rs_v['amount_paid'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['pvno']}</td><!--<td>{$rs_v['pvno_paid']}</td><td>".@get_folio_name($rs_v['folio_code'])."</td>--><td>".@get_dept_name($rs_v['dept_code'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><!--td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section','delete','$r_id');\">DELETE</a></td--></tr>";
          }//end of while

          $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          $tb.="<tr><td colspan='5' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";


}// end of voucher_section

if($id=='voucher_section_entry')
{
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     if($r_id !="")
     {
          $d=@mysqli_query($con, "SELECT * FROM vouchertb where id = '$r_id'");
          $ds=@mysqli_fetch_array($d);
          $d_pvno=@$ds['pvno']; $d_payee_name=@$ds['payee_name']; $d_fileno=@$ds['fileno'];

          $r_ids=@$ds['id'];
     }
     /*$sr = mysqli_query($con, "SELECT dept_acctcode FROM users_roletb WHERE fileno='{$login_id}' AND role='Prepared Officer'");
     $rdept = mysqli_fetch_array($sr, 3);
     $roledept = $rdept[0];*/
     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date'])); 
     $dept=@$_REQUEST['funddept']; $pvno=@$_REQUEST['pvno'];
     $account=@$_REQUEST['account']; 	
     $folio=@$_REQUEST['folio']; 	
     $type=@$_REQUEST['type'];

     $fileno=@$_REQUEST['fileno'];
     if($fileno!='External') $type="Internal";
     else $type = $fileno;

     $name=mysqli_real_escape_string($con, $_REQUEST['name']); 
     $act_no=@$_REQUEST['act_no']; 
     $bank=mysqli_real_escape_string($con, $_REQUEST['bank']);
     $address=mysqli_real_escape_string($con, $_REQUEST['address']); 
     $vamount=@$_REQUEST['vamount']; 
     $desc=mysqli_real_escape_string($con, $_REQUEST['desc']);

     $payee_tin_number=@mysqli_real_escape_string($con, @$_REQUEST['payee_tin_number']);
     $payee_sort_code=@mysqli_real_escape_string($con, @$_REQUEST['payee_sort_code']);
     $voucher_unit=@$_REQUEST['voucher_unit'];
     $bcode=@$_REQUEST['folio'];  //@$_REQUEST['bcode'];
     $bamt=@$_REQUEST['dr_bamt'];
     //print_r($bcode); exit;
     $autocreate=@$_REQUEST['autocreate'];
     $login_id=@$_SESSION['login_id'];
     $memo_id=@$_REQUEST['memo_id'];
     $amt_approved=@$_REQUEST['amt_approved'];
     $process_type=$_REQUEST['pro_typ'];

     $isPA=$_REQUEST['ispa'];
     if($isPA!="Yes") $isPA="No";

     $vat_incl=@$_REQUEST['dvat_inc'];
     $dvat=@$_REQUEST['dvat'];		$dvat_pv=@$_REQUEST['dvat_pv'];			$vat_code=@$_REQUEST['vat_code'];
     $vat_bank=@$_REQUEST['dvat_bank'];	$vat_acct=@$_REQUEST['dvat_acct'];	$vat_payee=@$_REQUEST['dvat_payee'];

     $dtax=@$_REQUEST['dtax'];		$dtax_pv=@$_REQUEST['dtax_pv'];			$tax_code=@$_REQUEST['tax_code'];
     $tax_bank=@$_REQUEST['dtax_bank'];	$tax_acct=@$_REQUEST['dtax_acct'];	$tax_payee=@$_REQUEST['dtax_payee'];

     $dend=@$_REQUEST['dendowment'];	$dend_pv=@$_REQUEST['dendowment_pv'];	$end_code=@$_REQUEST['end_code'];
     $end_bank=@$_REQUEST['dendowment_bank'];	$end_acct=@$_REQUEST['dendowment_acct'];	$end_payee=@$_REQUEST['dend_payee'];

     $dstamp=@$_REQUEST['dstamp'];	$dstamp_pv=@$_REQUEST['dstamp_pv'];	$stamp_code=@$_REQUEST['stamp_code'];
     $stamp_bank=@$_REQUEST['dstamp_bank'];	$stamp_acct=@$_REQUEST['dstamp_acct'];	$stamp_payee=@$_REQUEST['dstamp_payee'];

     $vcode=@$_REQUEST['code'];$vamt=@$_REQUEST['amount'];  //code is the folio_code and rate

     //$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
     //echo "$vcode ==> $vamt===>$mydata";exit;
     if($action=='save')
     {
          /*if($amt_approved != $vamount){
          echo "<script language='javascript'>alert('Cross check your entry! Amount Approved is not the same with Amount entered');</script>";exit;}*/
          foreach($vamt as $amt)
          {
               if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
               {
                    echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
               }
          }
          if(count($bcode)>0)
          {
               foreach($bamt as $val_amt)
               {
                    if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
                    {
                         echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
                    }
               }//end of foreach for bamt
          }// end of bcode is not empty

          // End of Validation
          $s=0;$i=0;$j=0;$tamt=0;$emsg=array(); $total_tax=0; $amount=0;
          // transaction begins
          begin();
          //now save to voucher table

          $stamp_amount = 0; $vat_amount = 0; $tax_amount = 0;  $vat_amount = 0;
          /*if($dstamp_pv == "yes" and $dstamp > 0)	{
          $stamp_amount = (($vamount/100) * $dstamp);
          //$vamount = $vamount - $stamp_amount;}
          if($dend_pv == "yes" and $dend > 0){
          $end_amount = (($vamount/100) * $dend);
          //$vamount = $vamount - $end_amount;}
          if($dtax_pv == "yes" and $dtax > 0){
          $tax_amount = (($vamount/100) * $dtax);
          //$vamount = $vamount - $tax_amount;}*/

          if($vat_incl == "yes"){
               if($dvat_pv == "yes" and $dvat > 0) $vat_amount = ($dvat/($dvat + 100)) * $vamount;
               if($dtax_pv == "yes" and $dtax > 0) $tax_amount = ($dtax/($dvat + 100)) * $vamount;
               if($dend_pv == "yes" and $dend > 0) $end_amount = ($dend/($dvat + 100)) * $vamount;
               if($dstamp_pv == "yes" and $dstamp > 0) $stamp_amount = ($dstamp/($dvat + 100)) * $vamount;

          }elseif($vat_incl != "yes"){
               if($dvat_pv == "yes" and $dvat > 0) $vat_amount = ($dvat/100) * $vamount;
               if($dtax_pv == "yes" and $dtax > 0) $tax_amount = ($dtax/100) * $vamount;
               if($dend_pv == "yes" and $dend > 0) $end_amount = ($dend/100) * $vamount;
               if($dstamp_pv == "yes" and $dstamp > 0) $stamp_amount = ($dstamp/100) * $vamount;
          }

          $total_tax = $stamp_amount + $vat_amount + $tax_amount + $end_amount;
          $amount_approved=$vamount;
          $amount_paid=$amount_approved - $total_tax;  //after tax deduction
          $year=@date('Y',strtotime(@$_REQUEST['pay_date']));

          $total_budget=@get_budget($folio, $year);
          $flag=true;
          $bursary->begin();
          //stepping down budget check at this point
          ////if( ($amount_paid <= $total_budget) or $total_budget=='' )
          {
               ///$udept = $bursary->get_any_value("jv_code", "journal_code", "unit_code", $udept);
               $roledept = $bursary->get_any_value("jvcode", "journal_code_user", "fileno", $login_id);

               if($process_type=="Pending") {
                    $queryString="INSERT INTO vouchertb set pvno='{$pvno}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', fileno='{$fileno}', payee_name='{$name}', payee_acct_no='{$act_no}', payee_bank_name='{$bank}', payee_address='{$address}', payee_tin_number='{$payee_tin_number}', payee_sort_code='{$payee_sort_code}', description='Being {$desc}', amount_approved='{$amount_approved}', total_tax='{$total_tax}', amount_paid='{$amount_paid}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final', memo_id='{$memo_id}', purchase_advance='{$isPA}', dept_vou='{$roledept}'";
               }
               elseif($process_type=="Final") {
                    $queryString="INSERT INTO vouchertb set pvno='{$pvno}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', fileno='{$fileno}', payee_name='{$name}', payee_acct_no='{$act_no}', payee_bank_name='{$bank}', payee_address='{$address}', payee_tin_number='{$payee_tin_number}', payee_sort_code='{$payee_sort_code}', description='Being {$desc}', amount_approved='{$amount_approved}', total_tax='{$total_tax}', amount_paid='{$amount_paid}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final', checked_by='{$login_id}', date_checked=CURDATE(), time_checked=CURTIME(), checked_action='Approved', controlled_by='{$login_id}', date_controlled=CURDATE(), time_controlled=CURTIME(), controlled_action='Approved', authorized_by='{$login_id}', date_authorized=CURDATE(), time_authorized=CURTIME(), authorized_action='Approved', paid_by='{$login_id}', date_paid=CURDATE(), time_paid=CURTIME(), paid_action='Approved', final_approval_by='{$login_id}', final_approval_date=CURDATE(), final_approval='Approved', audit_by='{$login_id}', audit_date=CURDATE(), audit_time=CURTIME(), audit_action='Approved', memo_id='{$memo_id}', purchase_advance='{$isPA}', dept_vou='{$roledept}'";
               }
               $log=$queryString.";";
               //$r1=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               if(!mysqli_query($con, $queryString))
               {
                    echo "1.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea1;
               }
               //folio_code='$folio',
               //echo $process_type; exit;
               if(count($folio)==1){
                    if($process_type=="Pending") {
                         $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno', folio_code='$folio[0]', amount='$amount_paid', paid='No'";
                         //$r2[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "2.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea1;
                         }
                         $log .= $queryString;
                    }
                    elseif($process_type=="Final") {
                         $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno', folio_code='$folio[0]', amount='$amount_paid', paid='Yes'";
                         //$r2[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "3.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea1;
                         }
                         $log .= $queryString;
                    }
               }else{
                    if(count($bcode)>1){
                         foreach($bcode as $v)
                         {
                              if($process_type=="Pending"){
                                   $queryString= "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='No'";
                                   //$r2[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                                   if(!mysqli_query($con, $queryString))
                                   {
                                        echo "4.  ".mysqli_error($con);
                                        $bursary->rollback();
                                        $flag=false;
                                        goto TestArea1;
                                   }
                                   $log .= $queryString;
                              }
                              else if($process_type=="Final") {
                                   $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='Yes'";
                                   //$r2[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                                   if(!mysqli_query($con, $queryString))
                                   {
                                        echo "4.  ".mysqli_error($con);
                                        $bursary->rollback();
                                        $flag=false;
                                        goto TestArea1;
                                   }
                                   $log .= $queryString;
                              }
                              $s++;
                         }
                    }
               }
               //exit;

               /*if($vat_incl != "yes" and $dvat > 0)*/
               {
                    if($dvat_pv == "yes" and $dvat > 0){
                         $pvno_tax = $pvno."_VAT";

                         /*if($vat_incl == "yes") $vat_amount = ($dvat/($dvat + 100)) * $vamount;
                         else $vat_amount = ($dvat/100) * $vamount;*/

                         $queryString = "INSERT INTO vouchertb set pvno='{$pvno_tax}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', payee_name='{$vat_payee}', payee_acct_no='{$vat_acct}', payee_bank_name='{$vat_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of $dvat% VAT for {$desc}', amount_approved='{$vat_amount}', amount_paid='{$vat_amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='VAT', memo_id='{$memo_id}', dept_vou='{$roledept}'";
                         //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "6.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea1;
                         }
                         $log .= $queryString;

                         $queryString = "INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                         //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "7.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea1;
                         }
                         $log .= $queryString;

                         $queryString ="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$vat_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                         //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "6.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea1;
                         }
                         $log .= $queryString;

                         $queryString = "INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$vat_amount}', paid='No'";
                         //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "8.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea1;
                         }
                         $log .= $queryString;

                    }//end if $dvat_pv
               }//end if $vat_incl

               if($dtax_pv == "yes" and $dtax > 0){
                    $pvno_tax = $pvno."_WHT";
                    //$tax_amount = (($vamount/100) * $dtax);
                    $queryString = "INSERT INTO vouchertb set pvno='{$pvno_tax}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', payee_name='{$tax_payee}', payee_acct_no='{$tax_acct}', payee_bank_name='{$tax_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dtax}% WHT for {$desc}', amount_approved='{$tax_amount}', amount_paid='{$tax_amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='TAX', memo_id='{$memo_id}', dept_vou='{$roledept}'";
                    //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "9.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                    //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "10.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$tax_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                    //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "11.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$tax_amount}', paid='No'";
                    //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "12.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;
               }//end if $dtax_pv

               if($dend_pv == "yes" and $dend > 0){
                    $pvno_tax = $pvno."_END";
                    //$end_amount = (($vamount/100) * $dend);
                    $queryString = "INSERT INTO vouchertb set pvno='{$pvno_tax}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', payee_name='{$end_payee}', payee_acct_no='{$end_acct}', payee_bank_name='{$end_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dend}% Endowment for {$desc}', amount_approved='{$end_amount}', amount_paid='{$end_amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='ENDOWMENT', memo_id='{$memo_id}', dept_vou='{$roledept}'";
                    //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "13.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                    //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "14.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}',folio_code='{$folio[0]}',amount='{$end_amount}',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'";
                    //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "15.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$end_amount}', paid='No'";
                    //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "16.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;
               }//end if $dend_pv

               if($dstamp_pv == "yes" and $dstamp > 0){
                    $pvno_stamp = $pvno."_SD";
                    //$stamp_amount = (($vamount/100) * $dstamp);
                    $queryString = "INSERT INTO vouchertb SET pvno='{$pvno_stamp}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$type}', payee_name='{$stamp_payee}', payee_acct_no='{$stamp_acct}', payee_bank_name='{$stamp_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dstamp}% Stamp-Duty for {$desc}', amount_approved='{$stamp_amount}', amount_paid='{$stamp_amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='STAMP DUTY', memo_id='{$memo_id}', dept_vou='{$roledept}'";
                    //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "17.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_stamp}'";
                    //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "18.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_taxtb SET pvno='{$pvno_stamp}', folio_code='{$folio[0]}', amount='{$stamp_amount}', entry_date=CURDATE(), entry_time=CURTIME(),entry_by='{$login_id}'";
                    //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "19.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;

                    $queryString = "INSERT INTO voucher_folio_codetb SET pvno='{$pvno_stamp}', folio_code='{$folio[0]}', amount='{$stamp_amount}', paid='No'";
                    //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    if(!mysqli_query($con, $queryString))
                    {
                         echo "20.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea1;
                    }
                    $log .= $queryString;
               }//end if $dend_pv
               if($autocreate=='yes')
               {
                    if(count($vcode)>0)
                    {

                         foreach($vcode as $codeval){  //code for tax
                              $line=$i+1;
                              if($codeval !=""){
                                   $code=@explode("***",$codeval);
                                   $tax_folio_code=$code[0];
                                   $amount=$vamt[$code[2]];
                                   $total_tax+=$amount;
                                   $j++;
                                   $pvno2=$pvno."/$j";
                                   $tax_detail=@get_tax_detail($tax_folio_code);

                                   if($process_type=="pending") $queryString="INSERT INTO vouchertb SET pvno='{$pvno2}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$tax_detail[4]}', payee_name='{$tax_detail[5]}', payee_acct_no='{$tax_detail[6]}', payee_bank_name='{$tax_detail[7]}', payee_address='{$tax_detail[8]}', payee_tin_number='{$tax_detail[9]}', payee_sort_code='{$tax_detail[10]}', description='Deduction for: {$desc}', amount_approved='{$amount}', amount_paid='{$amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_type='Final', memo_id='{$memo_id}', purchase_advance='{$isPA}', dept_vou='{$roledept}'";
                                   else if($process_type=="final") $queryString = "INSERT INTO vouchertb SET pvno='{$pvno2}', voucher_date='{$pay_date}', dept_code='{$voucher_unit}', dept_acctcode='{$account}', payee_type='{$tax_detail[4]}', payee_name='{$tax_detail[5]}', payee_acct_no='{$tax_detail[6]}', payee_bank_name='{$tax_detail[7]}', payee_address='{$tax_detail[8]}', payee_tin_number='{$tax_detail[9]}', payee_sort_code='{$tax_detail[10]}', description='Deduction for: {$desc}', amount_approved='{$amount}', amount_paid='{$amount}', prepared_by='{$login_id}', date_prepared=CURDATE(), time_prepared=CURTIME(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', entry_type='Final', checked_by='{$login_id}', date_checked=CURDATE(), time_checked=CURTIME(), checked_action='Approved', controlled_by='{$login_id}', date_controlled=CURDATE(), time_controlled=CURTIME(), controlled_action='Approved', authorized_by='{$login_id}', date_authorized=CURDATE(), time_authorized=CURTIME(), authorized_action='Approved', paid_by='{$login_id}', date_paid=CURDATE(), time_paid=CURTIME(), paid_action='Approved', final_approval_by='{$login_id}', final_approval_date=CURDATE(), final_approval='Approved', audit_by='{$login_id}', audit_date=CURDATE(), audit_time=CURTIME(), audit_action='Approved', memo_id='{$memo_id}', purchase_advance='{$isPA}', dept_vou='{$roledept}'";

                                   //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                                   if(!mysqli_query($con, $queryString))
                                   {
                                        echo "21.  ".mysqli_error($con);
                                        $bursary->rollback();
                                        $flag=false;
                                        goto TestArea1;
                                   }
                                   $log .= $queryString;

                                   //folio_code='$tax_folio_code',
                                   $queryString = "INSERT INTO voucher_parent_child_taxtb set parent_pvno='$pvno',child_pvno='$pvno2'";
                                   //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                                   if(!mysqli_query($con, $queryString))
                                   {
                                        echo "22.  ".mysqli_error($con);
                                        $bursary->rollback();
                                        $flag=false;
                                        goto TestArea1;
                                   }
                                   $log .= $queryString;

                                   if($process_type=="pending") $queryString = "INSERT INTO voucher_folio_codetb SET pvno='{$pvno2}',folio_code='{$tax_folio_code}',amount='{$amount}', paid='No'";
                                   else if($process_type=="final") $queryString = "INSERT INTO voucher_folio_codetb SET pvno='{$pvno2}',folio_code='{$tax_folio_code}',amount='{$amount}', paid='Yes'";

                                   //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                                   if(!mysqli_query($con, $queryString))
                                   {
                                        echo "23.  ".mysqli_error($con);
                                        $bursary->rollback();
                                        $flag=false;
                                        goto TestArea1;
                                   }
                                   $log .= $queryString;

                              }//end of amount is not empty
                              $i++;

                         }// end of foreach folio code

                    }// end of folio code is not empty for tax deduction
               }// end of autocreate tax record is yes
               else{
                    $i=0;$j=0;
                    if(count($vcode)>0){
                         foreach($vcode as $codeval){  //code for tax
                              $line=$i+1;
                              if($codeval !=""){
                                   $code=@explode("***",$codeval);
                                   $tax_folio_code=$code[0];
                                   $amount=$vamt[$code[2]];
                                   $total_tax+=$amount;
                                   $j++;
                                   $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno}', folio_code='{$tax_folio_code}', amount='{$amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                                   //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                                   if(!mysqli_query($con, $queryString))
                                   {
                                        echo "24.  ".mysqli_error($con);
                                        $bursary->rollback();
                                        $flag=false;
                                        goto TestArea1;
                                   }
                                   $log .= $queryString;
                              }//end of amount is not empty
                              $i++;

                         }// end of foreach folio code

                    }// end of folio code is not empty for tax deduction
               }//end of else part of autocreate is not ==yes

               logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");


               $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";


               TestArea1:
               if($flag==true and  mysqli_query($con, "update memo_assigntb set status='Completed',datecompleted=CURDATE() WHERE memo_id='{$memo_id}'"))
               {
                    $queryString = "update memo_movementtb set read_status = 'Read' where memo_id = '$memo_id'";
                    mysqli_query($con, $queryString);
                    $bursary->commit();
                    echo "<script>alert('Payment Voucher saved successfully');</script>";
                    $log .= $queryString;
                    $bursary->writeLogFile($log);
               }
               else
               {
                    $bursary->rollback();
                    echo "<script>alert('Operation Failed! Transaction was canceled. ". mysqli_error($con)."');</script>";
               }

          }

     }// end of save


     if($action=='view')
     {
          $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<table border='1' rules='all' frame='box'><tr><th colspan='8' align='center'>Prepared Voucher</th></tr><tr><th>S/N</th><th>PV NO.</th><!--<th>PV NO.</th>--><th>FOLIO</th><th>DEPARTMENT</th><th>DATE</th><th>PAYEE</th><th>AMOUNT</th><!--th>ACTION</th--></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $g_total+=$rs_v['amount_paid'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['pvno']}</td><!--<td>{$rs_v['pvno_paid']}</td>--><td>".@get_folio_name($rs_v['item_code'])."</td><td>".@get_dept_name_act($rs_v['dept_code'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><!--td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_entry','delete','$r_id');\">DELETE</a></td--></tr>";
          }//end of while

          $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          $tb.="<tr><td colspan='6' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

     exit;
}// end of voucher_section

if($id=='voucher_section_entry_finalxxx')
{

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     if($r_id !="")
     {
          $d=@mysqli_query($con, "SELECT * FROM vouchertb where id = '$r_id'");
          $ds=@mysqli_fetch_array($d);
          $d_pvno=@$ds['pvno']; $d_payee_name=@$ds['payee_name']; $d_fileno=@$ds['fileno'];

          $r_ids=@$ds['id'];
     }

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date']));

     $dept=@$_REQUEST['funddept']; $pvno=@$_REQUEST['pvno'];
     $account=@$_REQUEST['account']; 	$folio=@$_REQUEST['folio'];

     $fileno=@$_REQUEST['fileno'];
     if($fileno!='External') $type="Internal";
     else $type = $fileno;

     $name=@$_REQUEST['name']; $act_no=@$_REQUEST['act_no']; $bank=@$_REQUEST['bank'];
     $address=@mysqli_real_escape_string($con, @$_REQUEST['address']); $vamount=@$_REQUEST['vamount']; $desc=@mysqli_real_escape_string($con, @$_REQUEST['desc']);

     $payee_tin_number=@mysqli_real_escape_string($con, @$_REQUEST['payee_tin_number']);
     $payee_sort_code=@mysqli_real_escape_string($con, @$_REQUEST['payee_sort_code']);
     $voucher_unit=@$_REQUEST['voucher_unit'];
     $bcode=@$_REQUEST['folio'];  //@$_REQUEST['bcode'];
     $bamt=@$_REQUEST['dr_bamt'];
     //print_r($bamt); exit;
     $autocreate=@$_REQUEST['autocreate'];
     $login_id=@$_SESSION['login_id'];
     $memo_id=@$_REQUEST['memo_id'];
     $amt_approved=@$_REQUEST['amt_approved'];
     $process_type=$_REQUEST['pro_typ'];
     $isPA=$_REQUEST['ispa'];
     if($isPA!="Yes") $isPA="No";

     $vat_incl=@$_REQUEST['dvat_inc'];
     $dvat=@$_REQUEST['dvat'];		$dvat_pv=@$_REQUEST['dvat_pv'];			$vat_code=@$_REQUEST['vat_code'];
     $vat_bank=@$_REQUEST['dvat_bank'];	$vat_acct=@$_REQUEST['dvat_acct'];	$vat_payee=@$_REQUEST['dvat_payee'];

     $dtax=@$_REQUEST['dtax'];		$dtax_pv=@$_REQUEST['dtax_pv'];			$tax_code=@$_REQUEST['tax_code'];
     $tax_bank=@$_REQUEST['dtax_bank'];	$tax_acct=@$_REQUEST['dtax_acct'];	$tax_payee=@$_REQUEST['dtax_payee'];

     $dend=@$_REQUEST['dendowment'];	$dend_pv=@$_REQUEST['dendowment_pv'];	$end_code=@$_REQUEST['end_code'];
     $end_bank=@$_REQUEST['dendowment_bank'];	$end_acct=@$_REQUEST['dendowment_acct'];	$end_payee=@$_REQUEST['dend_payee'];

     $dstamp=@$_REQUEST['dstamp'];	$dstamp_pv=@$_REQUEST['dstamp_pv'];	$stamp_code=@$_REQUEST['stamp_code'];
     $stamp_bank=@$_REQUEST['dstamp_bank'];	$stamp_acct=@$_REQUEST['dstamp_acct'];	$stamp_payee=@$_REQUEST['dstamp_payee'];

     $vcode=@$_REQUEST['code'];	$vamt=@$_REQUEST['amount'];  //code is the folio_code and rate
     $prepared_by = @$_REQUEST['prepared_by'];		$checked_by = @$_REQUEST['checked_by'];
     $authorized_by = @$_REQUEST['certified_by'];		//$authorized_by = @$_REQUEST['authorized_by'];
     $controlled_by = @$_REQUEST['controlled_by'];	$audited_by = @$_REQUEST['audited_by'];
     $pvno_final = $_REQUEST['pvno_final'];			$batchno = $_REQUEST['batchno'];

     //$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
     //echo "$vcode ==> $vamt===>$mydata";exit;
     if($action=='save')
     {

          $d=@mysqli_query($con, "SELECT * FROM transtb where pvno='$pvno_final' and transdate like '%$pay_date%'");
          $countpv = @mysqli_num_rows($d);
          if($countpv > 0)
          {
               echo "<script language='javascript'>alert('PVNO already Exists, try again...');</script>";exit;
          }

          foreach($vamt as $amt)
          {
               if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
               {
                    echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
               }
          }
          if(count($bcode)>0)
          {
               foreach($bamt as $val_amt)
               {
                    if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
                    {
                         echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
                    }
               }//end of foreach for bamt
          }// end of bcode is not empty

          // End of Validation
          $s=0;$i=0;$j=0;$tamt=0;$emsg=array(); $total_tax=0; $amount=0;
          // transaction begins
          begin();
          //now save to voucher table

          $stamp_amount = 0; $vat_amount = 0; $tax_amount = 0;  $vat_amount = 0;

          if($vat_incl == "yes"){
               if($dvat_pv == "yes" and $dvat > 0) $vat_amount = ($dvat/($dvat + 100)) * $vamount;
               if($dtax_pv == "yes" and $dtax > 0) $tax_amount = ($dtax/($dvat + 100)) * $vamount;
               if($dend_pv == "yes" and $dend > 0) $end_amount = ($dend/($dvat + 100)) * $vamount;
               if($dstamp_pv == "yes" and $dstamp > 0) $stamp_amount = ($dstamp/($dvat + 100)) * $vamount;

          }elseif($vat_incl != "yes"){
               if($dvat_pv == "yes" and $dvat > 0) $vat_amount = ($dvat/100) * $vamount;
               if($dtax_pv == "yes" and $dtax > 0) $tax_amount = ($dtax/100) * $vamount;
               if($dend_pv == "yes" and $dend > 0) $end_amount = ($dend/100) * $vamount;
               if($dstamp_pv == "yes" and $dstamp > 0) $stamp_amount = ($dstamp/100) * $vamount;
          }

          $total_tax = $stamp_amount + $vat_amount + $tax_amount + $end_amount;
          $amount_approved=$vamount;
          $amount_paid=$amount_approved - $total_tax;  //after tax deduction
          $year=@date('Y',strtotime(@$_REQUEST['pay_date']));

          $total_budget=@get_budget($folio, $year);
          $flag=true; $qr=array();
          $bursary->begin();
          //stepping down budget check at this point
          ////if( ($amount_paid <= $total_budget) or $total_budget=='' )
          {
               $queryString = "INSERT INTO vouchertb set pvno='$pvno', pvno_paid='$pvno_final', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', fileno='$fileno', payee_name='$name', payee_acct_no='$act_no', payee_bank_name='$bank', payee_address='$address', payee_tin_number='$payee_tin_number', payee_sort_code='$payee_sort_code', description='Being $desc', amount_approved='$amount_approved', total_tax='$total_tax', amount_paid='$amount_paid', prepared_by='$prepared_by', date_prepared='$pay_date', entry_date='$pay_date', entry_by='$login_id', entry_type='Final', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date', authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved', memo_id='$memo_id', purchase_advance='$isPA'";
               $qr[] = $queryString;
               //$r1=@mysqli_query($con, $queryString) or die( "1.". mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "1.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               if( count($folio)==1 ){
                    $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno', folio_code='$folio[0]', amount='$amount_paid', paid='Yes'";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r2[]=@mysqli_query($con, $queryString) or die( "2.". mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "2.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/

                    $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$amount_paid', paybatch='$batchno', pvno='$pvno_final', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$isPA'";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r8[]=@mysqli_query($con, $queryString) or die( "4.". mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "3.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/
                    
                    $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$amount_paid.", voucher_pvno = '".$pvno_final."', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r9[]=@mysqli_query($con, $queryString) or die( "5.". mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "4.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/
               }else{
                    if(count($bcode)>1){
                         foreach($bcode as $v){
                              $queryString = "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='Yes'";
                              $qr[] = $queryString;
                              //$r2[]=@mysqli_query($con, $queryString) or die( "6.". mysqli_error($con));
                              /*if(!mysqli_query($con, $queryString))
                              {
                                   echo "5.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }*/

                              $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$v', transtype='Debit', transdate='$pay_date', amount='".$bamt[$s]."', paybatch='$batchno', pvno='$pvno_final', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$isPA'";
                              $qr[] = $queryString;
                              $log=$queryString.";";
                              //$r8[]=@mysqli_query($con, $queryString) or die( "7.". mysqli_error($con));
                              /*if(!mysqli_query($con, $queryString))
                              {
                                   echo "6.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }*/

                              $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$v."', budget_folio_code = '".$v."', amount = ".$bamt[$s].", voucher_pvno = '".$pvno_final."', budget_category = 'Recurrent', operation_year = '".date('Y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                              $qr[] = $queryString;
                              $log=$queryString.";";
                              //$r9[]=@mysqli_query($con, $queryString) or die( "8.". ysqli_error($con));
                              /*if(!mysqli_query($con, $queryString))
                              {
                                   echo "7.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }*/

                              $s++;
                         }
                    }
               }
               //exit;

               /*if($vat_incl != "yes" and $dvat > 0)*/{
               if($dvat_pv == "yes" and $dvat > 0){
                    $pvno_tax = $pvno."_VAT";

                    /*if($vat_incl == "yes") $vat_amount = ($dvat/($dvat + 100)) * $vamount;
                    else $vat_amount = ($dvat/100) * $vamount;*/

                    $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}A', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$vat_payee', payee_acct_no='$vat_acct', payee_bank_name='$vat_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of $dvat% VAT for $desc', amount_approved='$vat_amount', amount_paid='$vat_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='VAT', memo_id='$memo_id',
                    prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "8.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/

                    $queryString="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "9.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/

                    $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$vat_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "10.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/

                    $queryString="INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$vat_amount}', paid='Yes'";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "11.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/

                    $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$vat_amount', paybatch='$batchno', pvno='{$pvno_final}A', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r8[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "12.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/

                    $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$vat_amount.", voucher_pvno = '".$pvno_final."A', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                    $qr[] = $queryString;
                    $log=$queryString.";";
                    //$r9[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                    /*if(!mysqli_query($con, $queryString))
                    {
                         echo "13.  ".mysqli_error($con);
                         $bursary->rollback();
                         $flag=false;
                         goto TestArea;
                    }*/
               }//end if $dvat_pv
          }//end if $vat_incl

          if($dtax_pv == "yes" and $dtax > 0){
               $pvno_tax = $pvno."_WHT";
               //$tax_amount = (($vamount/100) * $dtax);
               $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}B', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='{$tax_payee}', payee_acct_no='{$tax_acct}', payee_bank_name='{$tax_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dtax}% WHT for $desc', amount_approved='$tax_amount', amount_paid='$tax_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='TAX', memo_id='$memo_id',
               prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "14.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "15.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$tax_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "16.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno_tax',folio_code='$folio[0]',amount='$tax_amount', paid='Yes'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/


               $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$tax_amount', paybatch='$batchno', pvno='{$pvno_final}B', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r8[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "17.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$tax_amount.", voucher_pvno = '".$pvno_final."B', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r9[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "10.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

          }//end if $dtax_pv

          if($dend_pv == "yes" and $dend > 0){
               $pvno_tax = $pvno."_END";
               //$end_amount = (($vamount/100) * $dend);
               $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}C', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='{$end_payee}', payee_acct_no='{$end_acct}', payee_bank_name='{$end_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dend}% Endowment for $desc', amount_approved='$end_amount', amount_paid='$end_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='ENDOWMENT', memo_id='$memo_id',
               prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "19.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "20.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}',folio_code='{$folio[0]}',amount='{$end_amount}',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "21.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$end_amount}',  paid='Yes'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "22.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$end_amount', paybatch='$batchno', pvno='{$pvno_final}C', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r8[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "23.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$end_amount.", voucher_pvno = '".$pvno_final."C', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r9[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "24.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

          }//end if $dend_pv

          if($dstamp_pv == "yes" and $dstamp > 0){
               $pvno_stamp = $pvno."_SD";
               //$stamp_amount = (($vamount/100) * $dstamp);
               $queryString="INSERT INTO vouchertb set pvno='$pvno_stamp', pvno_paid='{$pvno_final}D', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$stamp_payee', payee_acct_no='$stamp_acct', payee_bank_name='$stamp_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dstamp}% Stamp-Duty for $desc', amount_approved='$stamp_amount', amount_paid='$stamp_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='STAMP DUTY', memo_id='$memo_id',
               prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "25.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_stamp}'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "26.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno_stamp}', folio_code='{$folio[0]}', amount='{$stamp_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "27.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno_stamp',folio_code='$folio[0]',amount='$stamp_amount', paid='Yes'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "28.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$stamp_amount', paybatch='$batchno', pvno='{$pvno_final}D', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r8[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "29.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

               $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$stamp_amount.", voucher_pvno = '".$pvno_final."D', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
               $qr[] = $queryString;
               $log=$queryString.";";
               //$r9[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
               /*if(!mysqli_query($con, $queryString))
               {
                    echo "30.  ".mysqli_error($con);
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }*/

          }//end if $dend_pv
          $p;
          foreach($qr as $query)
          {
               //echo $query."<br>";
               ++$p;
               if(!mysqli_query($con, $query)){
                    echo "{$p}.  ".mysqli_error($con).$query;
                    $bursary->rollback();
                    $flag=false;
                    goto TestArea;
               }
          }
          exit;
          

          


          $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";

          TestAreaX:
          if($flag==true)
          {
               //// mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id = '$memo_id'");
               logs($login_id, "Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");
               $bursary->commit();
               echo "<script>alert('Payment Voucher saved successfully');</script>";
               $log .= $queryString;
               $bursary->writeLogFile($log);
          }
          else
          {
               //$bursary->rollback();
               echo "<script>alert('Operation Failed! Transaction was canceled. ". mysqli_error($con)."');</script>";
          }


     }

     }// end of save





     if($action=='view')
     {
          $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<table border='1' rules='all' frame='box'><tr><th colspan='8' align='center'>Prepared Voucher</th></tr><tr><th>S/N</th><th>PV NO.</th><!--<th>PV NO.</th>--><th>FOLIO</th><th>DEPARTMENT</th><th>DATE</th><th>PAYEE</th><th>AMOUNT</th><!--th>ACTION</th--></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $g_total+=$rs_v['amount_paid'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['pvno']}</td><!--<td>{$rs_v['pvno_paid']}</td>--><td>".@get_folio_name($rs_v['item_code'])."</td><td>".@get_dept_name_act($rs_v['dept_code'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><!--td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_entry','delete','$r_id');\">DELETE</a></td--></tr>";
          }//end of while

          $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          $tb.="<tr><td colspan='6' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

     exit;
}// end of voucher_section


if($id=='voucher_section_entry_final')
{

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     //$j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);

     if($r_id !="")
     {
          $d=@mysqli_query($con, "SELECT * FROM vouchertb where id = '$r_id'");
          $ds=@mysqli_fetch_array($d);
          $d_pvno=@$ds['pvno']; $d_payee_name=@$ds['payee_name']; $d_fileno=@$ds['fileno'];

          $r_ids=@$ds['id'];
     }

     //collect fields frm form
     $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date']));

     $dept=@$_REQUEST['funddept']; $pvno=@$_REQUEST['pvno'];
     $account=@$_REQUEST['account']; 	$folio=@$_REQUEST['folio'];

     $fileno=@$_REQUEST['fileno'];
     if($fileno!='External') $type="Internal";
     else $type = $fileno;

     $name=@$_REQUEST['name']; $act_no=@$_REQUEST['act_no']; $bank=@$_REQUEST['bank'];
     $address=@mysqli_real_escape_string($con, @$_REQUEST['address']); $vamount=@$_REQUEST['vamount']; $desc=@mysqli_real_escape_string($con, @$_REQUEST['desc']);

     $payee_tin_number=@mysqli_real_escape_string($con, @$_REQUEST['payee_tin_number']);
     $payee_sort_code=@mysqli_real_escape_string($con, @$_REQUEST['payee_sort_code']);
     $voucher_unit=@$_REQUEST['voucher_unit'];
     $bcode=@$_REQUEST['folio'];  //@$_REQUEST['bcode'];
     $bamt=@$_REQUEST['dr_bamt'];
     //print_r($bamt); exit;
     $autocreate=@$_REQUEST['autocreate'];
     $login_id=@$_SESSION['login_id'];
     $memo_id=@$_REQUEST['memo_id'];
     $amt_approved=@$_REQUEST['amt_approved'];
     $process_type=$_REQUEST['pro_typ'];
     $isPA=$_REQUEST['ispa'];
     if($isPA!="Yes") $isPA="No";

     $vat_incl=@$_REQUEST['dvat_inc'];
     $dvat=@$_REQUEST['dvat'];		$dvat_pv=@$_REQUEST['dvat_pv'];			$vat_code=@$_REQUEST['vat_code'];
     $vat_bank=@$_REQUEST['dvat_bank'];	$vat_acct=@$_REQUEST['dvat_acct'];	$vat_payee=@$_REQUEST['dvat_payee'];

     $dtax=@$_REQUEST['dtax'];		$dtax_pv=@$_REQUEST['dtax_pv'];			$tax_code=@$_REQUEST['tax_code'];
     $tax_bank=@$_REQUEST['dtax_bank'];	$tax_acct=@$_REQUEST['dtax_acct'];	$tax_payee=@$_REQUEST['dtax_payee'];

     $dend=@$_REQUEST['dendowment'];	$dend_pv=@$_REQUEST['dendowment_pv'];	$end_code=@$_REQUEST['end_code'];
     $end_bank=@$_REQUEST['dendowment_bank'];	$end_acct=@$_REQUEST['dendowment_acct'];	$end_payee=@$_REQUEST['dend_payee'];

     $dstamp=@$_REQUEST['dstamp'];	$dstamp_pv=@$_REQUEST['dstamp_pv'];	$stamp_code=@$_REQUEST['stamp_code'];
     $stamp_bank=@$_REQUEST['dstamp_bank'];	$stamp_acct=@$_REQUEST['dstamp_acct'];	$stamp_payee=@$_REQUEST['dstamp_payee'];

     $vcode=@$_REQUEST['code'];	$vamt=@$_REQUEST['amount'];  //code is the folio_code and rate
     $prepared_by = @$_REQUEST['prepared_by'];		$checked_by = @$_REQUEST['checked_by'];
     $authorized_by = @$_REQUEST['certified_by'];		//$authorized_by = @$_REQUEST['authorized_by'];
     $controlled_by = @$_REQUEST['controlled_by'];	$audited_by = @$_REQUEST['audited_by'];
     $pvno_final = $_REQUEST['pvno_final'];			$batchno = $_REQUEST['batchno'];

     //$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
     //echo "$vcode ==> $vamt===>$mydata";exit;
     if($action=='save')
     {

          $d=@mysqli_query($con, "SELECT * FROM transtb where pvno='$pvno_final' and transdate like '%$pay_date%'");
          $countpv = @mysqli_num_rows($d);
          if($countpv > 0)
          {
               echo "<script language='javascript'>alert('PVNO already Exists, try again...');</script>";exit;
          }

          foreach($vamt as $amt)
          {
               if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
               {
                    echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
               }
          }
          if(count($bcode)>0)
          {
               foreach($bamt as $val_amt)
               {
                    if(!preg_match('/^\d+(\.\d+)?$/', $val_amt))//$val_amt!="" &&
                    {
                         echo "<script language='javascript'>alert('Invalid Amount. Enter Breakdown Amount correctly');</script>";exit;
                    }
               }//end of foreach for bamt
          }// end of bcode is not empty

          // End of Validation
          $s=0;$i=0;$j=0;$tamt=0;$emsg=array(); $total_tax=0; $amount=0;
          // transaction begins
          begin();
          //now save to voucher table

          $stamp_amount = 0; $vat_amount = 0; $tax_amount = 0;  $vat_amount = 0;

          if($vat_incl == "yes"){
               if($dvat_pv == "yes" and $dvat > 0) $vat_amount = ($dvat/($dvat + 100)) * $vamount;
               if($dtax_pv == "yes" and $dtax > 0) $tax_amount = ($dtax/($dvat + 100)) * $vamount;
               if($dend_pv == "yes" and $dend > 0) $end_amount = ($dend/($dvat + 100)) * $vamount;
               if($dstamp_pv == "yes" and $dstamp > 0) $stamp_amount = ($dstamp/($dvat + 100)) * $vamount;

          }elseif($vat_incl != "yes"){
               if($dvat_pv == "yes" and $dvat > 0) $vat_amount = ($dvat/100) * $vamount;
               if($dtax_pv == "yes" and $dtax > 0) $tax_amount = ($dtax/100) * $vamount;
               if($dend_pv == "yes" and $dend > 0) $end_amount = ($dend/100) * $vamount;
               if($dstamp_pv == "yes" and $dstamp > 0) $stamp_amount = ($dstamp/100) * $vamount;
          }

          $total_tax = $stamp_amount + $vat_amount + $tax_amount + $end_amount;
          $amount_approved=$vamount;
          $amount_paid=$amount_approved - $total_tax;  //after tax deduction
          $year=@date('Y',strtotime(@$_REQUEST['pay_date']));

          $total_budget=@get_budget($folio, $year);
          $flag=true;
          $queryString = "INSERT INTO vouchertb set pvno='$pvno', pvno_paid='$pvno_final', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', fileno='$fileno', payee_name='$name', payee_acct_no='$act_no', payee_bank_name='$bank', payee_address='$address', payee_tin_number='$payee_tin_number', payee_sort_code='$payee_sort_code', description='Being $desc', amount_approved='$amount_approved', total_tax='$total_tax', amount_paid='$amount_paid', prepared_by='$prepared_by', date_prepared='$pay_date', entry_date='$pay_date', entry_by='$login_id', entry_type='Final', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date', authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved', memo_id='$memo_id', purchase_advance='$isPA'";
          if(mysqli_query($con, $queryString)){
               if( count($folio)==1 ){
                    $queryStringA="INSERT INTO voucher_folio_codetb set pvno='$pvno', folio_code='$folio[0]', amount='$amount_paid', paid='Yes'";
                    $queryStringB="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$amount_paid', paybatch='$batchno', pvno='$pvno_final', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$isPA'";
                    $queryStringC="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$amount_paid.", voucher_pvno = '".$pvno_final."', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                    if(mysqli_query($con, $queryStringA) && mysqli_query($con, $queryStringB) && mysqli_query($con, $queryStringC)){
                         $flag=true;
                    } else {
                         $flag=false;
                         goto TestArea;
                    }
               }else{
                    if(count($bcode)>1){
                         foreach($bcode as $v){
                              $queryStringA = "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='Yes'";
                              $queryStringB="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$v', transtype='Debit', transdate='$pay_date', amount='".$bamt[$s]."', paybatch='$batchno', pvno='$pvno_final', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$isPA'";
                              $queryStringC="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$v."', budget_folio_code = '".$v."', amount = ".$bamt[$s].", voucher_pvno = '".$pvno_final."', budget_category = 'Recurrent', operation_year = '".date('Y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                              if(mysqli_query($con, $queryStringA) && mysqli_query($con, $queryStringB) && mysqli_query($con, $queryStringC)){
                                   $flag=true;
                              } else {
                                   $flag=false;
                                   goto TestArea;
                              }
                              $s++;
                         }
                    }
               }
          }else {
               $flag=false;
               goto TestArea;
          }

          if($flag){
               if($dvat_pv == "yes" and $dvat > 0){
                    $pvno_tax = $pvno."_VAT";

                    $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}A', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$vat_payee', payee_acct_no='$vat_acct', payee_bank_name='$vat_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of $dvat% VAT for $desc', amount_approved='$vat_amount', amount_paid='$vat_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='VAT', memo_id='$memo_id',
                    prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
                    if(mysqli_query($con, $queryString)){
                         $queryStringA="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                         $queryStringB="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$vat_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                         $queryStringC="INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$vat_amount}', paid='Yes'";
                         $queryStringD="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$vat_amount', paybatch='$batchno', pvno='{$pvno_final}A', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                         $queryStringE="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$vat_amount.", voucher_pvno = '".$pvno_final."A', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                         if(mysqli_query($con, $queryStringA) && mysqli_query($con, $queryStringB) && mysqli_query($con, $queryStringC) && mysqli_query($con, $queryStringD) && mysqli_query($con, $queryStringE)){
                              $flag=true;
                         } else {
                              $flag=false;
                              goto TestArea;
                         }
                    }else {
                         $flag=false;
                         goto TestArea;
                    }
               }//end if $dvat_pv
          }

          if($flag){
               if($dtax_pv == "yes" and $dtax > 0){
                    $pvno_tax = $pvno."_WHT";

                    $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}B', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='{$tax_payee}', payee_acct_no='{$tax_acct}', payee_bank_name='{$tax_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dtax}% WHT for $desc', amount_approved='$tax_amount', amount_paid='$tax_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='TAX', memo_id='{$memo_id}', prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
                    if(mysqli_query($con, $queryString)){
                         $queryStringA="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                         $queryStringB="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$tax_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                         $queryStringC="INSERT INTO voucher_folio_codetb set pvno='$pvno_tax',folio_code='$folio[0]',amount='$tax_amount', paid='Yes'";
                         $queryStringD="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$tax_amount', paybatch='$batchno', pvno='{$pvno_final}B', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                         $queryStringE="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$tax_amount.", voucher_pvno = '".$pvno_final."B', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                         if(mysqli_query($con, $queryStringA) && mysqli_query($con, $queryStringB) && mysqli_query($con, $queryStringC) && mysqli_query($con, $queryStringD) && mysqli_query($con, $queryStringE)){
                              $flag=true;
                         } else {
                              $flag=false;
                              goto TestArea;
                         }
                    } else {
                         $flag=false;
                         goto TestArea;
                    }
               }//end if $dvat_pv
          }

          if($flag){
               if($dend_pv == "yes" and $dend > 0){
                    $pvno_tax = $pvno."_END";

                    $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}C', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='{$end_payee}', payee_acct_no='{$end_acct}', payee_bank_name='{$end_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dend}% Endowment for $desc', amount_approved='$end_amount', amount_paid='$end_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='ENDOWMENT', memo_id='$memo_id', prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
                    if(mysqli_query($con, $queryString)){
                         $queryStringA="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                         $queryStringB="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}',folio_code='{$folio[0]}',amount='{$end_amount}',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'";
                         $queryStringC="INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$end_amount}',  paid='Yes'";
                         $queryStringD="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$end_amount', paybatch='$batchno', pvno='{$pvno_final}C', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                         $queryStringE="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$end_amount.", voucher_pvno = '".$pvno_final."C', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                         if(mysqli_query($con, $queryStringA) && mysqli_query($con, $queryStringB) && mysqli_query($con, $queryStringC) && mysqli_query($con, $queryStringD) && mysqli_query($con, $queryStringE)){
                              $flag=true;
                         } else {
                              $flag=false;
                              goto TestArea;
                         }
                    } else {
                         $flag=false;
                         goto TestArea;
                    }
               }//end if $dvat_pv
          }

          if($flag){
               if($dstamp_pv == "yes" and $dstamp > 0){
                    $pvno_stamp = $pvno."_SD";

                    $queryString="INSERT INTO vouchertb set pvno='$pvno_stamp', pvno_paid='{$pvno_final}D', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$stamp_payee', payee_acct_no='$stamp_acct', payee_bank_name='$stamp_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dstamp}% Stamp-Duty for $desc', amount_approved='$stamp_amount', amount_paid='$stamp_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='STAMP DUTY', memo_id='$memo_id', prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
                    if(mysqli_query($con, $queryString)){
                         $queryStringA="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_stamp}'";
                         $queryStringB="INSERT INTO voucher_taxtb SET pvno='{$pvno_stamp}', folio_code='{$folio[0]}', amount='{$stamp_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                         $queryStringC="INSERT INTO voucher_folio_codetb set pvno='$pvno_stamp',folio_code='$folio[0]',amount='$stamp_amount', paid='Yes'";
                         $queryStringD="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$stamp_amount', paybatch='$batchno', pvno='{$pvno_final}D', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                         $queryStringE="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$stamp_amount.", voucher_pvno = '".$pvno_final."D', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                         if(mysqli_query($con, $queryStringA) && mysqli_query($con, $queryStringB) && mysqli_query($con, $queryStringC) && mysqli_query($con, $queryStringD) && mysqli_query($con, $queryStringE)){
                              $flag=true;
                         } else {
                              $flag=false;
                              goto TestArea;
                         }
                    } else {
                         $flag=false;
                         goto TestArea;
                    }
               }//end if $dvat_pv
          }

          /*
                    $bursary->begin();
                    //stepping down budget check at this point
                    ////if( ($amount_paid <= $total_budget) or $total_budget=='' )
                    {
                         $queryString = "INSERT INTO vouchertb set pvno='$pvno', pvno_paid='$pvno_final', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', fileno='$fileno', payee_name='$name', payee_acct_no='$act_no', payee_bank_name='$bank', payee_address='$address', payee_tin_number='$payee_tin_number', payee_sort_code='$payee_sort_code', description='Being $desc', amount_approved='$amount_approved', total_tax='$total_tax', amount_paid='$amount_paid', prepared_by='$prepared_by', date_prepared='$pay_date', entry_date='$pay_date', entry_by='$login_id', entry_type='Final', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date', authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved', memo_id='$memo_id', purchase_advance='$isPA'";

                         //$r1=@mysqli_query($con, $queryString) or die( "1.". mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "1.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         if( count($folio)==1 ){
                              $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno', folio_code='$folio[0]', amount='$amount_paid', paid='Yes'";

                              $log=$queryString.";";
                              //$r2[]=@mysqli_query($con, $queryString) or die( "2.". mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "2.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }

                              $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$amount_paid', paybatch='$batchno', pvno='$pvno_final', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$isPA'";
                              $log=$queryString.";";
                              //$r8[]=@mysqli_query($con, $queryString) or die( "4.". mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "3.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }
                              ///mysqli_query($con, "UNLOCK TABLES;");
                              $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$amount_paid.", voucher_pvno = '".$pvno_final."', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                              $log=$queryString.";";
                              //$r9[]=@mysqli_query($con, $queryString) or die( "5.". mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "4.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }
                         }else{
                              if(count($bcode)>1){
                                   foreach($bcode as $v){
                                        $queryString = "INSERT INTO voucher_folio_codetb set pvno='$pvno',folio_code='$v',amount='$bamt[$s]', paid='Yes'";
                                        //$r2[]=@mysqli_query($con, $queryString) or die( "6.". mysqli_error($con));
                                        if(!mysqli_query($con, $queryString))
                                        {
                                             echo "5.  ".mysqli_error($con);
                                             $bursary->rollback();
                                             $flag=false;
                                             goto TestArea;
                                        }

                                        $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$v', transtype='Debit', transdate='$pay_date', amount='".$bamt[$s]."', paybatch='$batchno', pvno='$pvno_final', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$isPA'";
                                        $log=$queryString.";";
                                        //$r8[]=@mysqli_query($con, $queryString) or die( "7.". mysqli_error($con));
                                        if(!mysqli_query($con, $queryString))
                                        {
                                             echo "6.  ".mysqli_error($con);
                                             $bursary->rollback();
                                             $flag=false;
                                             goto TestArea;
                                        }

                                        $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$v."', budget_folio_code = '".$v."', amount = ".$bamt[$s].", voucher_pvno = '".$pvno_final."', budget_category = 'Recurrent', operation_year = '".date('Y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                                        $log=$queryString.";";
                                        //$r9[]=@mysqli_query($con, $queryString) or die( "8.". ysqli_error($con));
                                        if(!mysqli_query($con, $queryString))
                                        {
                                             echo "7.  ".mysqli_error($con);
                                             $bursary->rollback();
                                             $flag=false;
                                             goto TestArea;
                                        }

                                        $s++;
                                   }
                              }
                         }
                         //exit;

                         //if($vat_incl != "yes" and $dvat > 0)
                         {
                         if($dvat_pv == "yes" and $dvat > 0){
                              $pvno_tax = $pvno."_VAT";

                              //if($vat_incl == "yes") $vat_amount = ($dvat/($dvat + 100)) * $vamount;
                              else $vat_amount = ($dvat/100) * $vamount;

                              $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}A', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$vat_payee', payee_acct_no='$vat_acct', payee_bank_name='$vat_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of $dvat% VAT for $desc', amount_approved='$vat_amount', amount_paid='$vat_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='VAT', memo_id='$memo_id',
                              prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";

                              $log=$queryString.";";
                              //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "8.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }

                              $queryString="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                              $log=$queryString.";";
                              //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "9.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }

                              $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$vat_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                              $log=$queryString.";";
                              //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "10.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }

                              $queryString="INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$vat_amount}', paid='Yes'";
                              $log=$queryString.";";
                              //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "11.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }

                              $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$vat_amount', paybatch='$batchno', pvno='{$pvno_final}A', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                              $log=$queryString.";";
                              //$r8[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "12.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }

                              $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$vat_amount.", voucher_pvno = '".$pvno_final."A', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                              $log=$queryString.";";
                              //$r9[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                              if(!mysqli_query($con, $queryString))
                              {
                                   echo "13.  ".mysqli_error($con);
                                   $bursary->rollback();
                                   $flag=false;
                                   goto TestArea;
                              }
                         }//end if $dvat_pv
                    }//end if $vat_incl

                    if($dtax_pv == "yes" and $dtax > 0){
                         $pvno_tax = $pvno."_WHT";
                         //$tax_amount = (($vamount/100) * $dtax);
                         $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}B', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='{$tax_payee}', payee_acct_no='{$tax_acct}', payee_bank_name='{$tax_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dtax}% WHT for $desc', amount_approved='$tax_amount', amount_paid='$tax_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='TAX', memo_id='$memo_id',
                         prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
                         $log=$queryString.";";
                         //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "14.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                         $log=$queryString.";";
                         //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "15.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$tax_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                         $log=$queryString.";";
                         //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "16.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno_tax',folio_code='$folio[0]',amount='$tax_amount', paid='Yes'";
                         $log=$queryString.";";
                         //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }


                         $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$tax_amount', paybatch='$batchno', pvno='{$pvno_final}B', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                         $log=$queryString.";";
                         //$r8[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "17.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$tax_amount.", voucher_pvno = '".$pvno_final."B', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                         $log=$queryString.";";
                         //$r9[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "10.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                    }//end if $dtax_pv

                    if($dend_pv == "yes" and $dend > 0){
                         $pvno_tax = $pvno."_END";
                         //$end_amount = (($vamount/100) * $dend);
                         $queryString="INSERT INTO vouchertb set pvno='$pvno_tax', pvno_paid='{$pvno_final}C', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='{$end_payee}', payee_acct_no='{$end_acct}', payee_bank_name='{$end_bank}', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dend}% Endowment for $desc', amount_approved='$end_amount', amount_paid='$end_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='ENDOWMENT', memo_id='$memo_id',
                         prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
                         $log=$queryString.";";
                         //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "19.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_tax}'";
                         $log=$queryString.";";
                         //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "20.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno_tax}',folio_code='{$folio[0]}',amount='{$end_amount}',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'";
                         $log=$queryString.";";
                         //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "21.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_folio_codetb SET pvno='{$pvno_tax}', folio_code='{$folio[0]}', amount='{$end_amount}',  paid='Yes'";
                         $log=$queryString.";";
                         //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "22.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$end_amount', paybatch='$batchno', pvno='{$pvno_final}C', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                         $log=$queryString.";";
                         //$r8[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "23.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$end_amount.", voucher_pvno = '".$pvno_final."C', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                         $log=$queryString.";";
                         //$r9[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "24.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                    }//end if $dend_pv

                    if($dstamp_pv == "yes" and $dstamp > 0){
                         $pvno_stamp = $pvno."_SD";
                         //$stamp_amount = (($vamount/100) * $dstamp);
                         $queryString="INSERT INTO vouchertb set pvno='$pvno_stamp', pvno_paid='{$pvno_final}D', voucher_date='$pay_date', dept_code='$voucher_unit', dept_acctcode='$account', payee_type='$type', payee_name='$stamp_payee', payee_acct_no='$stamp_acct', payee_bank_name='$stamp_bank', payee_address='', payee_tin_number='{$payee_tin_number}', payee_sort_code='', description='Being remmittance of {$dstamp}% Stamp-Duty for $desc', amount_approved='$stamp_amount', amount_paid='$stamp_amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_type='STAMP DUTY', memo_id='$memo_id',
                         prepared_by='$prepared_by', date_prepared='$pay_date', entry_by='$login_id', checked_by='$checked_by', date_checked='$pay_date', checked_action='Approved', controlled_by='$controlled_by', date_controlled='$pay_date', controlled_action='Approved', authorized_by='$authorized_by', date_authorized='$pay_date',  authorized_action='Approved', authorized_by2='$authorized_by', date_authorized2='$pay_date',  authorized_action2='Approved', paid_by='$login_id', date_paid='$pay_date', paid_action='Approved', final_approval_by='$login_id', final_approval_date='$pay_date', final_approval='Approved', audit_by='$audited_by', audit_date='$pay_date', audit_action='Approved' ";
                         $log=$queryString.";";
                         //$r4[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "25.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_parent_child_taxtb SET parent_pvno='{$pvno}',child_pvno='{$pvno_stamp}'";
                         $log=$queryString.";";
                         //$r5[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "26.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_taxtb SET pvno='{$pvno_stamp}', folio_code='{$folio[0]}', amount='{$stamp_amount}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
                         $log=$queryString.";";
                         //$r6[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "27.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO voucher_folio_codetb set pvno='$pvno_stamp',folio_code='$folio[0]',amount='$stamp_amount', paid='Yes'";
                         $log=$queryString.";";
                         //$r7[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "28.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO transtb set dept_acctcode='', acctcode='$account', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$stamp_amount', paybatch='$batchno', pvno='{$pvno_final}D', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}'";
                         $log=$queryString.";";
                         //$r8[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "29.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                         $queryString="INSERT INTO `budget_votebooktb` set voucher_folio_code = '".$folio[0]."', budget_folio_code = '".$folio[0]."', amount = ".$stamp_amount.", voucher_pvno = '".$pvno_final."D', budget_category = 'Recurrent', operation_year = '".date('y', $pay_date)."', operation_month = '".date('m', $pay_date)."', operation_quarter = '".get_quarter(date('m', $pay_date))."', status = 'PAID', entry_by = '".$controlled_by."', entry_date = '$pay_date', entry_time = now()";
                         $log=$queryString.";";
                         //$r9[]=@mysqli_query($con, $queryString) or die( mysqli_error($con));
                         if(!mysqli_query($con, $queryString))
                         {
                              echo "30.  ".mysqli_error($con);
                              $bursary->rollback();
                              $flag=false;
                              goto TestArea;
                         }

                    }//end if $dend_pv
          */


         // $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' AND checked_by='' ORDER BY voucher_date,folio_code,pvno";

TestArea:
          if($flag==true){
               //// mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id = '$memo_id'");
               $bursary->commit();
               logs($login_id, "Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");
               echo "<script>alert('Payment Voucher saved successfully');</script>";
               $log .= $queryString;
               $bursary->writeLogFile($log);
          }else{
               $bursary->rollback();
               echo "<script>alert('Operation Failed! Transaction was canceled. ". mysqli_error($con)."');</script>";
          }




     }// end of save




     ///$sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno WHERE prepared_by='$login_id' ORDER BY voucher_date, folio_code, pvno DESC LIMIT 5";

     $sql="select v.*, fc.folio_code as item_code FROM vouchertb v INNER JOIN voucher_folio_codetb fc ON v.pvno=fc.pvno ORDER BY id DESC LIMIT 10 ";

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<table border='1' rules='all' frame='box'><tr><th colspan='8' align='center'>Prepared Voucher</th></tr><tr><th>S/N</th><th>PV NO.</th><!--<th>PV NO.</th>--><th>FOLIO</th><th>DEPARTMENT</th><th>DATE</th><th>PAYEE</th><th>AMOUNT</th><!--th>ACTION</th--></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $g_total+=$rs_v['amount_paid'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['pvno']}</td><!--<td>{$rs_v['pvno_paid']}</td>--><td>".@get_folio_name($rs_v['item_code'])."</td><td>".@get_dept_name_act($rs_v['dept_code'])."</td><td nowrap>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><!--td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section_entry','delete','$r_id');\">DELETE</a></td--></tr>";
          }//end of while

          $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          $tb.="<tr><td colspan='6' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

     exit;
}// end of voucher_section


     if($id=='search_voucher')  //search for voucher given voucher number
     {
          $pvno=@base64_encode($_REQUEST['pvno']);
          $url="voucher_report.php?p=$pvno";
          echo $url;
          /*echo "<script>location='voucher_report.php?p=$pvno';</script>"; */

          exit;
     } //end of search for voucher

     if($id=='edit_posted_entry')
     {
          $trans_id=$_REQUEST['trans_id'];
          $action=$_REQUEST['action'];
          if($action=="DELETE"){
               if( mysqli_query($con, "DELETE FROM transtb where id=$trans_id")){
                    echo "<script>alert('Selected record deleted!');</script>";
               }else{
                    echo "<script>alert('Record delete failed!');</script>";
               }
          }else{
               $r_vals=$_REQUEST['r_vals'];
               $res_d=@mysqli_query($con, "SELECT * FROM transtb where id=$trans_id");
               if( mysqli_num_rows($res_d)>0){
                    $rs_d=@mysqli_fetch_array($res_d, 3 );
                    $pvno = $rs_d['pvno'];								$trans_id = $rs_d['id'];
                    $project = get_dept_name($rs_d['dept_acctcode']);	$prj_code = $rs_d['dept_acctcode'];
                    $cheque = $rs_d['chequeno'];						$acct = $rs_d['acctcode'];
                    $folio_code = $rs_d['folio_code'];					$amount = $rs_d['amount'];
                    $transdate = $rs_d['transdate'];					$date = $rs_d['transdate'];
                    $batchno = $rs_d['paybatch'];
                    $receiptno = $rs_d['receiptno'];  $payee = $rs_d['payee'];
                    ?>
                    <form id="update_trans" method="post" action="" enctype="multipart/form-data">
                         <table>
                              <!--<tr>
                              <td height="33" align="left" valign="middle" nowrap="nowrap">Dept/Unit:</td><td height="33" align="left" valign="middle"><select name="dept" id="dept" style="width: 300px">
                              <option selected="selected" value="<?php echo $prj_code; ?>"><?php echo $project; ?></option>
                              <?php
                              //$res_c=@mysqli_query($con, "SELECT * FROM departmenttb order by dept_name");
                              $res_c=@mysqli_query($con, "SELECT * FROM departmenttb order by dept_name");
                              while($rs_c=@mysqli_fetch_array($res_c))
                              {
                              $dept_code=@$rs_c['dept_code']; //$dept_code=@$rs_c['dept_code'];
                              $dept_name=@$rs_c['dept_name']; //$dept_name=@$rs_c['dept_name'];
                              echo "<option value='$dept_code'>$dept_name</option>";
                         }
                         echo "</select>";
                         ?>
                    </select></td>
               </tr>-->
               <tr>
                    <td height="33" align="left" valign="middle" nowrap="nowrap">PV. No.</td>
                    <td height="33" align="left" valign="middle"><?php echo $pvno; ?><input type="hidden" id="pvno" name="pvno" value="<?php echo $pvno; ?>" />
                         <input type="text"  style="width: 300px" id="pvno22" name="pvno22" value="<?php echo $pvno; ?>" />
                         <input type="hidden" id="transid" name="transid" value="<?php echo $trans_id; ?>" /></td>
                    </tr>
                    <tr>
                         <td height="33" align="left" valign="middle" nowrap="nowrap">Batch No.</td>
                         <td height="33" align="left" valign="middle"><input type="text"  style="width: 300px" id="batchno" name="batchno" value="<?php echo $batchno; ?>" />
                         </td>
                    </tr>
                    <tr>
                         <td height="33" align="left" valign="middle" nowrap="nowrap">Payee.</td>
                         <td height="33" align="left" valign="middle"><input type="text"  style="width: 300px" id="payee" name="payee" value="<?php echo $payee; ?>" />
                         </td>
                    </tr>
                    <tr>
                         <td height="33" align="left" valign="middle" nowrap="nowrap">Receipt No.</td>
                         <td height="33" align="left" valign="middle"><input type="text"  style="width: 300px" id="batchno" name="receiptno" value="<?php echo $receiptno; ?>" />
                         </td>
                    </tr>
                    <tr>
                         <td height="33" align="left" valign="middle" nowrap="nowrap">Cheque/Trans. ID:</td>
                         <td height="33" align="left" valign="middle"><input type="text" name="cheque" id="cheque" value="<?php echo $cheque; ?>" style="width: 300px"></td>
                    </tr>
                    <tr>
                         <td height="33" align="left" valign="middle" nowrap="nowrap">Cr/Dr Acct.:</td>
                         <td height="33" align="left" valign="middle"><select name="account" id="account" class="txt" style="width: 300px"  >
                              <option selected="selected" value="<?php echo $acct; ?>"><?php echo get_account_name($acct, false); ?></option>
                              <option value="---">Empty (No Account)</option>
                              <?php
                              $r=@mysqli_query($con, "select distinct *  from bank_accounttb WHERE status='Active' order by acctcode");
                              while ($rcourse=@mysqli_fetch_array($r))
                              {
                                   $scourse=@$rcourse['acctname'];		$pcode=@$rcourse['acctcode'];
                                   $bank=@$rcourse['bankname'];		$acctno=@$rcourse['acctno'];
                                   $acctname=@$rcourse['acctname'];
                                   echo "<option value='$pcode'>$acctno || $scourse <=> ($pcode)</option>";

                              }

                              ?>
                              <?php
                              /*$res_c=@mysqli_query($con, "SELECT * FROM bank_accounttb order by bankname");
                              while($rs_c=@mysqli_fetch_array($res_c))
                              {
                              $acct_code=@$rs_c['acctno'];
                              $acct_name=@$rs_c['bankname'].": ".$rs_c['acctno'];
                              echo "<option value='$acct_code'>$acct_name</option>";
                         }
                         echo "</select>";*/
                         ?>
                    </select></td>
               </tr>
               <tr>
                    <td height="33" align="left" valign="middle" nowrap="nowrap">Former Code:</td>
                    <td height="33" align="left" valign="middle"><?php echo $folio_code."<==>"; ?><?php echo get_folio_name($folio_code); ?></td>
               </tr>
               <tr>
                    <td height="33" align="left" valign="middle" nowrap="nowrap">Item Code:</td>
                    <td height="33" align="left" valign="middle"><input type="folio" name="folio" id="folio" value="<?php echo $folio_code; ?>" style="width: 300px"></td>
               </tr>
               <tr>
                    <td height="33" align="left" valign="middle" nowrap="nowrap">Amount:</td>
                    <td height="33" align="left" valign="middle"><input type="number" name="amount" id="amount" value="<?php echo $amount; ?>" style="width: 300px"></td>
               </tr>
               <tr>
                    <td height="33" align="left" valign="middle" nowrap="nowrap">Transaction Date:</td>
                    <td height="33" align="left" valign="middle"><input type="date" name="transdate" id="transdate" value="<?php echo $date; ?>" style="width: 300px"></td>
               </tr>
               <tr>
                    <td height="33" align="left" valign="middle" nowrap="nowrap">&nbsp;</td>
                    <td height="33" align="left" valign="middle"><input type='button' name='cmdpro' id='cmdpro' value='Save Entry' onclick="swapcontent('save_posted_entry');" class='btn'/></td>
               </tr>
               <tr>
                    <td height="33" colspan="2" align="left" valign="middle" nowrap="nowrap"><div id='save_posted_entry'></div></td>
               </tr>
          </table>
     </form>
     <?php
     }else{
          echo "<font color='red'><b>No record to display</b></font>";
     }
     }
     exit;
     }

     if($id=='save_posted_entry')
     {
          $login_id=@$_SESSION['login_id'];							$prj =  mysqli_real_escape_string($con, $_REQUEST['dept']);
          $pvno= mysqli_real_escape_string($con, $_REQUEST['pvno']);			$acct =  mysqli_real_escape_string($con, $_REQUEST['account']);
          $receiptno =  mysqli_real_escape_string($con, $_REQUEST['receiptno']); $payee =  mysqli_real_escape_string($con, $_REQUEST['payee']);
          $trans_id= mysqli_real_escape_string($con, $_REQUEST['transid']);	$folio_code =  mysqli_real_escape_string($con, $_REQUEST['folio']);
          $r_vals=$_REQUEST['r_vals'];								$cheque= mysqli_real_escape_string($con, $_REQUEST['cheque']);
          $r=strtolower($r_vals);										$transdate= mysqli_real_escape_string($con, $_REQUEST['transdate']);
          $amount= mysqli_real_escape_string($con, $_REQUEST['amount']); 		$pvno22= mysqli_real_escape_string($con, $_REQUEST['pvno22']);
          $batchno= mysqli_real_escape_string($con, $_REQUEST['batchno']);
          begin();	$qFlag=false;
          $uqry="UPDATE transtb SET acctcode='$acct', folio_code='$folio_code', amount=$amount, chequeno='$cheque', transdate='$transdate', pvno='$pvno22', paybatch='$batchno',receiptno='$receiptno', payee='$payee' WHERE id=$trans_id";
          if( mysqli_query($con, $uqry)){
               $qFlag=true;
               if($pvno!=''){
                    $q1="UPDATE voucher_taxtb SET folio_code='$folio_code', amount='$amount', pvno='$pvno22' WHERE pvno='{$pvno}'";
                    $q2="UPDATE voucher_folio_codetb SET folio_code='$folio_code', amount='$amount', pvno='$pvno22' WHERE pvno='{$pvno}'";
                    $q3="UPDATE vouchertb SET amount_paid='$amount', dept_code='$prj', dept_acctcode='$acct', pvno='$pvno22' WHERE pvno='{$pvno}'";
                    if( mysqli_query($con, $q1) and  mysqli_query($con, $q2) and  mysqli_query($con, $q3)){
                         //voucher update passed
                         $qFlag=true;
                    }else{
                         //voucher update failed
                         $qFlag=false;
                    }
               }
          }else{
               $qFlag=false;
          }
          if($qFlag){
               //all passed
               commit();
               echo "<script>alert('Posted entry update successful!');</script>";
          }else{
               //all or one failed
               rollback();
               echo "<script>alert('Posted entry update failed!');</script>";
          }
          exit;
     }


     if($id=='search_voucher_by_pvno')  //search for voucher given voucher real number
     {
          $pvno=@base64_encode($_REQUEST['pvno']);
          $url="voucher_report.php?p=$pvno";
          echo $url;
          /*echo "<script>location='voucher_report.php?p=$pvno';</script>"; */
          exit;
     } //end of search for voucher

     if($id=='load_budget')
     {
          $pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date']));
          $year=@date('Y',strtotime(@$_REQUEST['pay_date']));
          $folio_code=$_REQUEST['folio'];
          $total_budget=@get_budget($folio_code,$year);
          if($total_budget=='')
          echo "";
          else
          echo "<font color='red'>BUDGET REMAIN:". $total_budget."</font>";

          exit;
     }


     if($id=='display_journal_process')
     {


          $jno=$_REQUEST['jno'];
          $r_vals=$_REQUEST['r_vals'];
          $res_d=@mysqli_query($con, "SELECT * FROM journaltb v, journal_folio_codetb c where v.journalno=c.journalno and v.journalno='$jno'");
          $rs_d=@mysqli_fetch_array($res_d);

          //echo $r_vals;
          //if(strtolower($r_vals) != "cash officer")
          {
               $tb="<form name='frmpro' id='frmpro'><table><tr><th>JOURNAL NO</th><td>$jno</td></tr>
               <tr><th>CR TOTAL: </th><td>{$rs_d['cr_amount']}</td></tr>
               <tr><th>DR TOTAL: </th><td>{$rs_d['dr_amount']}</td></tr>
               <tr><th>NARATION: </th><td>{$rs_d['description']}</td></tr>
               <tr><th>DATE PREPARED: </th><td>".date('d/m/Y',strtotime($rs_d['journal_date']))."</td></tr>
               <tr><th>ACTION</th><td><select name='opt' id='opt' style='width:200px'>
               <option selected value=''>--Select Action--</option>";

               if(strtolower($r_vals) == "journal checked by officer") $tb .= "<option value='Approved'>Checked</option>";
               elseif(strtolower($r_vals) == "journal authorized officer") $tb .= "<option value='Approved'>Authorized</option>";
               else $tb .= "<option value='Approved'>Processed</option>";

               $tb .= "<!--option value='Not Approved'>Not Approved</option-->
               <option value='Queried'>Queried</option>
               </select><input type='hidden' name='jno' id='jno' value='$jno'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>
               <tr><th>COMMENTS</th><td><textarea name='comment' id='comment' cols='15' rows='5' style='width:400px'></textarea></td></tr>
               <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_journal');\" class='btn'/></th></tr>
               </table><div id='process_journal'></div></form>";
               echo $tb;
          }

          exit;
     }

     if($id=='display_voucher_process')
     {


          $pvno=$_REQUEST['pvno'];
          $pvno_paid=$_REQUEST['pvno_paid'];
          $r_vals=$_REQUEST['r_vals'];
          $res_d=@mysqli_query($con, "SELECT v.* FROM vouchertb v INNER JOIN voucher_folio_codetb c ON v.pvno=c.pvno WHERE v.pvno='{$pvno}'");
          $rs_d=@mysqli_fetch_array($res_d);

          //CHECK IS VOUCHER HAS BEEN QUERIED BEFORE;
          $queryString="SELECT querytext, id FROM voucher_queriestb WHERE pvno='{$pvno}' AND (response='' OR response Is Null)";
          $query=mysqli_query($con, $queryString);
          if(mysqli_num_rows($query) > 0){
               $row=mysqli_fetch_array($query, 3);
               $querytext=$row[0];
          }

          //echo $r_vals;
          if(strtolower($r_vals) != "cash officer")
          {
               $tb="<form name='frmpro' id='frmpro'><table><tr><th>PROCESSING NO</th><td>$pvno</td></tr>
               <tr><th>FOLIO/CODE</th><td>".read_voucher_folio_code($pvno)."</td></tr>
               <tr><th>PAYEE</th><td>{$rs_d['payee_name']}</td></tr>
               <tr><th>PAYEE ACCOUNT</th><td>{$rs_d['payee_bank_name']} :: {$rs_d['payee_acct_no']}</td></tr>
               <!--tr><th>PAYEE BANK</th><td>{$rs_d['payee_bank_name']}</td></tr-->
               <tr><th>PREPARED DATE</th><td>".date('d/m/Y',strtotime($rs_d['voucher_date']))."</td></tr>
               <tr><th>ACTION</th><td><select name='opt' id='opt' style='width:200px'>
               <option selected value=''>--Select Action--</option>";
                    if(strtolower($r_vals) == "checked by officer") $tb .= "<option value='Approved'>Checked</option>";
                    elseif(strtolower($r_vals) == "authorized officer") $tb .= "<option value='Approved'>Authorized</option>";
                    elseif(strtolower($r_vals) == "final authorized officer") $tb .= "<option value='Approved'> Final Authoriziation</option>";
                    elseif(strtolower($r_vals) == "auditor") $tb .= "<option value='Approved'>Audited</option>";

                    else $tb .= "<option value='Approved'>Processed</option>";

               $tb .= "<!--option value='Not Approved'>Not Approved</option-->
               <option value='Queried'>Queried</option>
               </select><input type='hidden' name='pvno' id='pvno' value='{$pvno}'/><input type='hidden' name='r_vals' id='r_vals' value='{$r_vals}'/></td></tr>";
               if($querytext != ''){
                    $tb .= "<tr><th>PENDING QUERY</th><td style='color:red;'>{$querytext}</td></tr>";
               }
               $tb .= "<tr><th>COMMENTS</th><td><textarea name='comment' id='comment' cols='15' rows='5' style='width:400px'></textarea></td></tr>
               <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_voucher');\" class='btn'/></th></tr>
               </table><div id='process_voucher'></div></form>";
               echo $tb;
          }
          else
          {
               if($_REQUEST['schedule']=='Yes'){
                    $tb="<form name='frmpro' id='frmpro'><table><tr><th>PROCESSING NO</th><td>$pvno</td></tr>
                    <tr><th>PV. NO.</th><td>{$rs_d['pre_pvno']}</td></tr>
                    <tr><th>FOLIO/CODE</th><td>".read_voucher_folio_code($pvno)."</td></tr>
                    <tr><th>PAYEE</th><td>{$rs_d['payee_name']}</td></tr>
                    <tr><th>PAYEE ACCOUNT</th><td>{$rs_d['payee_bank_name']} :: {$rs_d['payee_acct_no']}</td></tr>
                    <!--tr><th>PAYEE BANK</th><td>{$rs_d['payee_bank_name']}</td></tr-->
                    <tr><th>PREPARED DATE</th><td>".date('d/m/Y',strtotime($rs_d['voucher_date']))."</td></tr>
                    <tr><th>ACTION</th><td><select name='opt' id='opt' style='width:200px'>
                    <option value='Queried'>Query Payment Voucher</option>
                    </select>
                    <input type='hidden' name='pvnoid' id='pvnoid' value='{$rs_d['id']}'/>
                    <input type='hidden' name='pvno' id='pvno' value='{$pvno}'/>
                    <input type='hidden' name='r_vals' id='r_vals' value='{$r_vals}'/></td></tr>";
                    if($querytext != ''){
                         $tb .= "<tr><th>PENDING QUERY</th><td style='color:red;'>{$querytext}</td></tr>";
                    }
                    $tb .= "<tr><th>COMMENTS</th><td><textarea name='comment' id='comment' cols='15' rows='5' style='width:400px'></textarea></td></tr>
                    <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('remove_from_schedule');\" class='btn'/></th></tr>
                    </table><div id='remove_from_schedule'></div></form>";
               }else{
                    //section for the person to pay
                    $res_a=@mysqli_query($con, "SELECT * FROM bank_accounttb order by acctname");
                    $tb="<form name='frmpro' id='frmpro'><table><tr><th>PROCESSING NO</th><td>$pvno</td></tr>
                    <tr><th>FOLIO/CODE</th><td>". @read_voucher_folio_code($pvno)."</td></tr>
                    <tr><th>PAYEE</th><td>{$rs_d['payee_name']}</td></tr>
                    <tr><th>PAYEE ACCT NO.</th><td>{$rs_d['payee_acct_no']}</td></tr>
                    <tr><th>PAYEE BANK</th><td>{$rs_d['payee_bank_name']}</td></tr>
                    <tr><th>PREPARED DATE</th><td>".date('d/m/Y',strtotime($rs_d['voucher_date']))."</td></tr>
                    <tr><th>PAYMENT DATE: </th><td><input type='date' name='pay_date' id='pay_date' style='width: 300px' /></td></tr>
                    <tr><th>FUNDING ACCOUNT: </th><td>".
                    '<select name="acctcode" id="acctcode" class="txt" style="width: 300px" onchange="swapcontent(\'generatePVNo\', $(\'#acctcode\').val(), $(\'#pay_date\').val())" >
                    <option selected="selected" value="">---</option>';
                    $r=@mysqli_query($con, "select distinct *  from bank_accounttb where status='Active' order by acctcode");
                    while ($rcourse=@mysqli_fetch_array($r))
                    {
                         $scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
                         $bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
                         $acctname=@$rcourse['acctname'];
                         $tb .= "<option value='$pcode'>$bank || $acctno || $scourse <=> ($pcode)</option>";

                    }
                    $tb .= "</select>
                    <input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/>
                    <input type='hidden' name='pvno' id='pvno' value='$pvno'/></td></tr>";

                    //$pvno_paid = $prefix.$px."/".date('Y')."/".str_pad(($bursary->get_any_value("count(*) as cnum", "vouchertb", "acctcode", $rs_d['acctcode'], " and pvno_paid != ''") + 1), '6', '0', STR_PAD_LEFT);
                    $pvno_paid = ''; ///$prefix.$pf.str_pad(($bursary->get_any_value("count(*) as cnum", "vouchertb", "acctcode", $rs_d['acctcode'], " and pvno_paid != ''") + 1), '3', '0', STR_PAD_LEFT);

                    $tb .= "
                    <tr><th>P.V. NO.: </th><td><!--input type='hidden' name='acctcode' id='acctcode' value='".$rs_d['acctcode']."'-->
                    <div id='generatePVNo'><input type='text' name='pvno_paid' id='pvno_paid' style='width: 300px' value='$pvno_paid'/></div>
                    <input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>

                    <!--tr><th>BATCH NO.</th><td><input type='text' name='cheque_no' id='cheque_no' style='width:300px'/></td></tr-->
                    <tr><th>COMMENTS</th><td>
                    <!--input type='hidden' name='cheque_no' id='cheque_no'/-->
                    <textarea name='comment' id='comment' cols='15' rows='3' style='width:300px'></textarea></td></tr>
                    <tr><th>ACTION</th><td><select name='opt' id='opt' style='width:300px'>
                         <option selected value=''>---</option>
                         <option value='Approved'>Processed</option>
                         <!--option value='Not Approved'>Not Approved</option-->
                         <option value='Queried'>Queried</option>
                    </select></td></tr>
                    <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Process for Payment' onclick=\"swapcontent('process_voucher');\" class='btn'/></th></tr>
                    </table><div id='process_voucher'></div></form>";
               }
               echo $tb;

          }

          exit;
     }

     if($id=='display_voucher_processpv')
     {


          $pvno=$_REQUEST['pvno'];
          $pvno_paid=$_REQUEST['pvno_paid'];
          $r_vals=$_REQUEST['r_vals'];
          $res_d=@mysqli_query($con, "SELECT * FROM vouchertb v,voucher_folio_codetb c where v.pvno=c.pvno and v.pvno='$pvno'");
          $rs_d=@mysqli_fetch_array($res_d);
          $pvno_paid_u = $rs_d['pvno_paid'];
          $batchno_u = $rs_d['batchno'];

          //echo $r_vals;

          $tb="<form name='frmpro' id='frmpro'><table><tr><th>PROCESSING NO</th><td>$pvno</td></tr>
          <tr><th>FOLIO/CODE</th><td>".read_voucher_folio_code($pvno)."</td></tr>
          <tr><th>PAYEE</th><td>{$rs_d['payee_name']}</td></tr>
          <tr><th>PV NO</th><td>{$rs_d['pvno_paid']}</td></tr>
          <tr><th>BATCH NO</th><td>{$rs_d['batchno']}</td></tr>
          <tr><th>PAYEE ACCOUNT</th><td>{$rs_d['payee_bank_name']} :: {$rs_d['payee_acct_no']}</td></tr>
          <!--tr><th>PAYEE BANK</th><td>{$rs_d['payee_bank_name']}</td></tr-->
          <tr><th>PREPARED DATE</th><td>".date('d/m/Y',strtotime($rs_d['voucher_date']))."</td></tr>
          <tr><input type='hidden' name='pvno' id='pvno' value='$pvno'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></tr>
          <tr><th>NEW PV NO</th><td><input name='npv' id='npv'  style='width:400px' value='$pvno_paid_u'/></td></tr>
          <tr><th>NEW BATCH NO</th><td><input name='nbatchpv' id='nbatchpv'  style='width:400px' value='$batchno_u'/></td></tr>
          <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_voucherpv');\" class='btn'/></th></tr>
          </table><div id='process_voucherpv'></div></form>";
          echo $tb;

          exit;
     }

     if($id=="generatePVNo"){
          $accountCode = $_REQUEST['accountCode'];
          $payYear = $_REQUEST['pay_date'];
          $prefix=$bursary->get_any_value("shortname", "bank_accounttb", "acctcode", $accountCode);
          $rs="SELECT count(*) as cnum FROM vouchertb WHERE YEAR(date_paid) = YEAR('{$payYear}') AND acctcode = '{$accountCode}' AND pvno_paid != '' AND pvno NOT LIKE '%\_%'";
          $qr= mysqli_query($con, $rs);
          $sn =  mysqli_fetch_array($qr); $num = $sn[0] + 1;
          $yr=@date('y',strtotime($payYear));
          $pvno_paid = $yr."/".$prefix.str_pad($num, '3', '0', STR_PAD_LEFT);
          //echo $num;
          //str_pad(($bursary->get_any_value("count(*) as cnum", "vouchertb", "acctcode", $accountCode, " and pvno_paid != ''") + 1), '3', '0', STR_PAD_LEFT);
          echo "<input type='text' name='pvno_paid' id='pvno_paid' style='width:300px' value='$pvno_paid'/>";

          exit;
     }

     if($id=='process_journal')
     {
          $login_id=@$_SESSION['login_id'];
          $jno=$_REQUEST['jno'];
          $opt=$_REQUEST['opt'];  //Approved or Not Approved
          $r_vals=$_REQUEST['r_vals'];
          $r=strtolower($r_vals);
          $comment=$_REQUEST['comment'];
          if($opt=='')
          {
          echo "<script>alert('Error: You have not selected an option from the list');</script>";
          exit;
          }
          //if($r != 'cash officer')
          {
          if($r=="super admin" or $r=="journal checked by officer" or $r=="administrator"){
          $sql="update journaltb set checked_by='$login_id',checked_action='$opt',checked_remark='{$comment}',date_checked=CURDATE(),time_checked=CURTIME() where journalno='$jno'";
          mysqli_query($con, $sql) or die( mysqli_error($con));
          }elseif($r=="super admin" or $r=="journal authorized officer" or $r=="administrator"){
          begin();
          $sql="update journaltb set authorized_by='$login_id', authorized_action='$opt', authorized_remark='{$comment}', date_authorized=CURDATE(), time_authorized=CURTIME() where journalno='$jno'";

          mysqli_query($con, $sql) or die( mysqli_error($con));

          $sqq= mysqli_query($con, "SELECT * FROM journaltb where journalno='$jno'");
          if($rr= mysqli_fetch_array($sqq, 3 )){
          $sqq2= mysqli_query($con, "SELECT * FROM journal_folio_codetb where journalno='$jno'");
          while($rr2= mysqli_fetch_array($sqq2, 3 )){
          $sql3 = "INSERT INTO transtb set dept_acctcode='$rr[dept_code]', folio_code='$rr2[folio_code]', transtype='$rr2[trans_type]', transdate=CURDATE(), amount='$rr2[amount]', pvno='$jno', comment='$comment', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
          if( mysqli_query($con, $sql3)) $ERT=true; else {
          rollback(); echo "<script>alert('Loop:::Update failed!');</script>"; exit;
          }
          }
          }
          if( mysqli_query($con, $sql)){// and  mysqli_query($con, $sql2)) {
          commit();
          echo "<script>alert('Record updated successfully');</script>";
          exit;
          }
          else {
          rollback();
          echo "<script>alert('Update failed!".$sql3 ."');</script>";
          exit;
          }

          mysqli_query($con, $sql) or die( mysqli_error($con));
          }
          }
          echo "<script>alert('Record updated successfully');</script>";

          exit;
     }

     if($id=='process_voucher')
     {
          $login_id=@$_SESSION['login_id'];
          $pvno=$_REQUEST['pvno'];
          $batchno=$_REQUEST['cheque_no'];
          $pvno_paid=$_REQUEST['pvno_paid'];
          $opt=$_REQUEST['opt'];  //Approved or Not Approved
          $r_vals=$_REQUEST['r_vals'];
          $r=strtolower($r_vals);
          $comment=mysqli_real_escape_string($con, $_REQUEST['comment']);
          /*echo "<script>alert('$pvno_paid');</script>"; exit;*/
          if(!isset($_SESSION['login_id']) or $_SESSION['login_id'] == ''){
               echo "<script>alert('Session timeout. You will have login again!'); window.location='index.php';</script>"; 
               exit;
          }
          if($opt=='' )
          {
               echo "<script>alert('Error: You have not selected an option from the list{$opt}');</script>";
               exit;
          }
          if($pvno=='' )
          {
               echo "<script>alert('Error: The selected voucher has no unique ID and therefore pose a threath to the database. This operation will abort. \nThe voucher should be re-prepared.');</script>";
               exit;
          }
          if($opt=='Queried')
          {
               if($comment==''){
                    echo "<script>alert('You cannot submit a query without comment. Write a proper description of the query.');</script>";
                    exit;
               }
               $memo_id=@get_voucher_memoid($pvno);
               @mysqli_query($con, "UPDATE memotb SET memo_status='Queried' WHERE memo_id='{$memo_id}'");
               @mysqli_query($con, "INSERT INTO voucher_queriestb SET pvno='{$pvno}', qrole='{$r}', querytext='{$comment}', query_date=CURDATE(), query_by='{$login_id}'");
               @mysqli_query($con, "INSERT INTO memo_querytb SET memo_id='{$memo_id}',remark='{$comment}',dept_unit='',status='Queried',date=CURDATE(),time=CURTIME(),entry_date=CURDATE(),entry_time=CURTIME(),entry_by='{$login_id}'");
               //@mysqli_query($con, "DELETE FROM `budget_votebooktb` WHERE voucher_pvno = '{$pvno}'");
          }

          //CHECK IS VOUCHER HAS BEEN QUERIED BEFORE;
          $queryString="SELECT * FROM voucher_queriestb WHERE pvno='{$pvno}' AND (response='' OR response Is Null)";
          $query=mysqli_query($con, $queryString);
          if(mysqli_num_rows($query) > 0){
               $row=mysqli_fetch_array($query, 3);
               $queryid=$row['id'];
               if($comment==''){
                    echo "<script>alert('This voucher has an unanswered query and therefore cannot re-submit without a written response. Write a proper description of the response to the pending query.');</script>";
                    exit;
               }
          }
          
          if($r != 'cash officer')
          {
               if($r=="checked by officer"){
                    //CHECK-BY
                    $sql="UPDATE vouchertb set checked_by='{$login_id}', checked_action='{$opt}', checked_remark='{$comment}',date_checked=CURDATE(),time_checked=CURTIME() WHERE pvno='{$pvno}'";
                    mysqli_query($con, $sql) or die( mysqli_error($con));
               }elseif($r=="authorized officer"){
                    //AUTHORIZE-BY
                    $sql="UPDATE vouchertb set authorized_by='{$login_id}', authorized_action='{$opt}', authorized_remark='{$comment}',date_authorized=CURDATE(),time_authorized=CURTIME() WHERE pvno='{$pvno}'";
                    mysqli_query($con, $sql) or die( mysqli_error($con));
               }elseif($r=="final authorized officer"){
                    //FINAL-AUTHORIZATION
                    $sql="UPDATE vouchertb set authorized_by2='{$login_id}', authorized_action2='{$opt}', authorized_remark2='{$comment}',date_authorized2=CURDATE(),time_authorized2=CURTIME() WHERE pvno='{$pvno}'";
                    mysqli_query($con, $sql) or die( mysqli_error($con));
               }elseif($r=="administrator" or $r=="expenditure control"){
                    //EXPENDITURE CONTROL
                    $sql="UPDATE vouchertb set controlled_by='{$login_id}', controlled_action='{$opt}', controlled_remark='{$comment}',date_controlled=CURDATE(),time_controlled=CURTIME() WHERE pvno='{$pvno}'";
                    mysqli_query($con, $sql) or die( mysqli_error($con));
                    //$sql="UPDATE vouchertb set checked_by='{$login_id}', checked_action='{$opt}', checked_remark='{$comment}',date_checked=CURDATE(),time_checked=CURTIME() WHERE pvno='{$pvno}'";
               }elseif($r=="auditor"){
                    //AUDITOR
                    $sql="UPDATE vouchertb set audit_by='{$login_id}', audit_action='{$opt}',  audit_remark='{$comment}', audit_date=CURDATE(),audit_time=CURTIME() where pvno LIKE '{$pvno}%'";
                    //elseif($r=="bursar")
                    //	 $sql="UPDATE vouchertb set authorized_by='{$login_id}', authorized_action='{$opt}', authorized_remark='{$comment}',date_authorized=CURDATE(),time_authorized=CURTIME() WHERE pvno='{$pvno}'";
                    mysqli_query($con, $sql) or die( mysqli_error($con));
               }
               if($queryid != '' && $opt!='Queried'){
                    mysqli_query($con, "UPDATE voucher_queriestb SET response='{$comment}', rrole='{$r}', response_date=CURDATE(), response_by='{$login_id}' WHERE id='{$queryid}'");
               }
          }
          elseif($r=="cash officer")
          {
               //TREASURY OR CASH-OFFICE
               $acctcode=$_REQUEST['acctcode'];
               ///$payDate=$_REQUEST['pay_date'];
               $cheque_no=$_REQUEST['cheque_no'];
               /////////////////////////////Generate Voucher PV real Number here ////////////////////////////////
               $pay_date=@$_REQUEST['pay_date'];                          //@date('Y-m-d');
               $month_name=@date('F',strtotime($pay_date)); $month_no=@date('m',strtotime($pay_date));
               $year=@date('Y',strtotime($pay_date));
               $res_p=@mysqli_query($con, "SELECT count(*) as total from vouchertb where month(date_paid)='{$month_no}' and year(date_paid)='$year'");
               $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);
               
               $d=@mysqli_query($con, "SELECT * FROM transtb where pvno='{$pvno_paid}' and transdate like '%{$pay_date}%'");
               $countpv = @mysqli_num_rows($d);
               if($countpv > 0)
               {
                    echo "<script language='javascript'>alert('PVNO already Exists, try again...');</script>";exit;
               }
               //if($rs_d['payee_type']=="Internal")$pf = "F"; else $pf = '';
               
               //$prefix=$bursary->get_any_value("shortname", "bank_accounttb", "acctcode", $rs_d['acctcode']);
               //$pvno_paid = $prefix.$px."/".date('Y')."/".str_pad(($bursary->get_any_value("count(*) as cnum", "vouchertb", "acctcode", $rs_d['acctcode'], " and pvno_paid != ''") + 1), '6', '0', STR_PAD_LEFT);
               
               //$prefix=$bursary->get_any_value("shortname", "bank_accounttb", "acctcode", $acctcode);
               //$pvno_paid=$prefix.$pf.str_pad(($bursary->get_any_value("count(*) as cnum", "vouchertb", "acctcode", $acctcode, " and pvno_paid != ''") + 1), '3', '0', STR_PAD_LEFT);
               //echo "<b><font color='red'>$pvno</font></b><input type='hidden' name='pvno' id='pvno' value='$pvno'/>";
               ////////////////////////////End of generate voucher PV real Number //////////////////////////////
               begin();
               ////$sql="update vouchertb set pvno_paid='$pvno_paid', acctcode='$acctcode', paid_by='$login_id', paid_action='$opt', paid_remark='$comment', date_paid=CURDATE(), time_paid=CURTIME(), cheque_no='$cheque_no' WHERE pvno='{$pvno}'";
               
               $sql="update vouchertb set pvno_paid='{$pvno_paid}', acctcode='{$acctcode}', paid_by='{$login_id}', paid_action='{$opt}', paid_remark='{$comment}', date_paid='{$pay_date}', time_paid=CURTIME(), batchno='{$batchno}' WHERE pvno='{$pvno}'";
               $isPA=$bursary->get_any_value("purchase_advance", "vouchertb", "pvno", $pvno);
               mysqli_query($con, $sql) or die( mysqli_error($con));

               //$queryStringB="INSERT INTO transtb set dept_acctcode='', acctcode='$acctcode', folio_code='$folio[0]', transtype='Debit', transdate='$pay_date', amount='$amount_paid', paybatch='$batchno', pvno='$pvno_final', comment='PAID', entry_date='$pay_date', entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$isPA'";

               $sql2 = "update `budget_votebooktb` set status = 'PAID' WHERE voucher_pvno = '{$pvno}'";
               
               
               $sqq= mysqli_query($con, "SELECT * FROM vouchertb WHERE pvno='{$pvno}'");
               if($rr= mysqli_fetch_array($sqq, 3 )){
                    /*if($rr['audit_action']!='Approved' && $opt=='Approved'){
                         $sql_b="UPDATE vouchertb SET audit_by='{$login_id}', audit_action='{$opt}', audit_remark='{$comment}', audit_date='{$pay_date}', audit_time=CURTIME() WHERE pvno='{$pvno}'";
                         @mysqli_query($con, $sql_b) or die( mysqli_error($con));
                    }*/
                    $sqq2 = mysqli_query($con, "SELECT * FROM voucher_folio_codetb WHERE pvno='{$pvno}'");
                    while($rr2= mysqli_fetch_array($sqq2, 3 )){
                         $adv = $bursary->get_any_value("folio_code", "advancetb", "folio_code", $rr2['folio_code']);
                         if($adv != '') $isPA = 'Yes';
                         $sql3 = "INSERT INTO transtb set dept_acctcode='{$rr['dept_code']}', acctcode='{$acctcode}', folio_code='{$rr2['folio_code']}', transtype='Debit', transdate='{$pay_date}', amount='{$rr2['amount']}', paybatch='{$cheque_no}', pvno='{$pvno_paid}', comment='{$comment}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='{$isPA}'";
                         
                         if( mysqli_query($con, $sql3)) {
                              //check if voucher is used to pay for staff loan the update loan table
                              $lnq= mysqli_query($con, "SELECT * FROM hr_loan_apptb WHERE loan_no = '$pvno'");
                              $lnnum= mysqli_num_rows($lnq);
                              if($lnnum > 0){
                                   $sql4 = "UPDATE hr_loan_apptb SET process_status='Processed', process_date=Now(), payment_status='Paid' WHERE loan_no = '$pvno'";
                                   if( mysqli_query($con, $sql4)) $ERT=true;
                                   else{
                                        rollback();
                                        echo "<script>alert('Error :: Update failed!');</script>";
                                        exit;
                                   }
                              }
                         }else {
                              rollback(); echo "<script>alert('Loop:::Update failed!');</script>"; exit;
                         }
                    }
               }
               if( mysqli_query($con, $sql) &&  mysqli_query($con, $sql2)) {
                    commit();
                    echo "<script>alert('Record updated successfully...'); </script>";
                    exit;
               }
               else {
                    rollback();
                    echo "<script>alert('Update failed!');</script>";
                    exit;
               }
               //End of Code for updating other tables goes here /////////////
          } //end of cash officer
          echo "<script>alert('Record updated successfully');</script>";
          exit;
     }

     if($id=='voucher_section_reprocess' || $id=='voucher_section_paid')
     {
          
          $login_id=@$_SESSION['login_id'];
          $pvno=$_REQUEST['pvno'];
          $batchno=$_REQUEST['batchno'];
          $pvno_paid=$_REQUEST['pvno_paid'];
          $opt=$_REQUEST['opt']; 
          $r_vals=$_REQUEST['r_vals'];
          $r=strtolower($r_vals);
          $comment=mysqli_real_escape_string($con, $_REQUEST['comment']); 
          if(!isset($_SESSION['login_id']) or $_SESSION['login_id'] == ''){
               echo "<script>alert('Session timeout. You will have login again!'); window.location='index.php';</script>"; 
               exit;
          }
          if($pvno=='' )
          {
               echo "<script>alert('Error: The selected voucher has no unique ID and therefore pose a threath to the database. This operation will abort. \nThe voucher should be re-prepared.');</script>";
               exit;
          }
          
          if($id=='voucher_section_reprocess'){
               if($_REQUEST['checked_by']==""){
                    //CHECK-BY
                    $sql="UPDATE vouchertb set checked_by=null, checked_action=null, date_checked=null, time_checked=null WHERE pvno='{$pvno}'";
               }else{
                    $sql="UPDATE vouchertb set checked_by='{$_REQUEST['checked_by']}', checked_action='Approved', date_checked=date_prepared, time_checked=time_prepared WHERE pvno='{$pvno}'";
               }
               mysqli_query($con, $sql) or die( mysqli_error($con));

               if($_REQUEST['authorized_by']==""){
                    //AUTHORIZE-BY
                    $sql="UPDATE vouchertb set authorized_by=null, authorized_action=null, date_authorized=null, time_authorized=null WHERE pvno='{$pvno}'";
               }else{
                    $sql="UPDATE vouchertb set authorized_by='{$_REQUEST['authorized_by']}', authorized_action='Approved', date_authorized=date_prepared, time_authorized=time_prepared WHERE pvno='{$pvno}'";
               }
               mysqli_query($con, $sql) or die( mysqli_error($con));

               if($_REQUEST['authorized_by2']==""){
                    //FINAL-AUTHORIZATION
                    $sql="UPDATE vouchertb set authorized_by2=null, authorized_action2=null, date_authorized2=null, time_authorized2=null WHERE pvno='{$pvno}'";
               }else{
                    $sql="UPDATE vouchertb set authorized_by2='{$_REQUEST['authorized_by2']}', authorized_action2='Approved', date_authorized2=date_prepared, time_authorized2=time_prepared WHERE pvno='{$pvno}'";
               }
               mysqli_query($con, $sql) or die( mysqli_error($con));

               if($_REQUEST['controlled_by']==""){
                    //EXPENDITURE CONTROL
                    $sql="UPDATE vouchertb set controlled_by=null, controlled_action=null, date_controlled=null, time_controlled=null WHERE pvno='{$pvno}'";
               }else{
                    $sql="UPDATE vouchertb set controlled_by='{$_REQUEST['controlled_by']}', controlled_action='Approved', date_controlled=date_prepared, time_controlled=time_prepared WHERE pvno='{$pvno}'";
               }
               mysqli_query($con, $sql) or die( mysqli_error($con));

               if($_REQUEST['audit_by']==""){
                    //AUDITOR
                    $sql="UPDATE vouchertb set audit_by=null, audit_action=null, audit_date=null, audit_time=null WHERE pvno='{$pvno}'";
               }else{
                    $sql="UPDATE vouchertb set audit_by='{$_REQUEST['audit_by']}', audit_action='Approved', audit_date=date_prepared, audit_time=time_prepared WHERE pvno='{$pvno}'";
               }
               mysqli_query($con, $sql) or die( mysqli_error($con));

               if($_REQUEST['paid_by']==""){
                    //PAID-TREASURY
                    $sql="UPDATE vouchertb set paid_by=null, paid_action=null, date_paid=null, time_paid=null WHERE pvno='{$pvno}'";
               }else{
                    if($_REQUEST['pvno_paid']=='' )
                    {
                         echo "<script>alert('Error: PV. NO. is empty. \nEnter PV No. before you submit voucher as paid.');</script>";
                         exit;
                    }
                    $sql="UPDATE vouchertb set pvno_paid='{$_REQUEST['pvno_paid']}', pre_pvno='{$_REQUEST['pvno_paid']}', acctcode='{$_REQUEST['acctcode']}', paid_by='{$_REQUEST['paid_by']}', paid_action='Approved', batchno='{$_REQUEST['batchno']}', date_paid='{$_REQUEST['pay_date']}', time_paid=time_prepared WHERE pvno='{$pvno}'";
               }
               mysqli_query($con, $sql) or die( mysqli_error($con));
          }

          if($id=='voucher_section_paid'){
               //PAYMENT POSTING
               $acctcode=$_REQUEST['acctcode'];
               $cheque_no=$_REQUEST['cheque_no'];
               /////////////////////////////Generate Voucher PV real Number here ////////////////////////////////
               $pay_date=@$_REQUEST['pay_date'];
               $month_name=@date('F',strtotime($pay_date)); 
               $month_no=@date('m',strtotime($pay_date));
               $year=@date('Y',strtotime($pay_date));
               $res_p=@mysqli_query($con, "SELECT count(*) as total from vouchertb where month(date_paid)='{$month_no}' and year(date_paid)='$year'");
               $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);
               
               $d=@mysqli_query($con, "SELECT * FROM transtb where pvno='{$pvno_paid}' and transdate like '%{$pay_date}%'");
               $countpv = @mysqli_num_rows($d);
               if($countpv > 0)
               {
                    echo "<script language='javascript'>alert('PVNO already Exists, try again...');</script>";exit;
               }
               begin();
               
               $sql="UPDATE vouchertb set pvno_paid='{$pvno_paid}', acctcode='{$acctcode}', paid_by='{$login_id}', paid_action='Approved', paid_remark='{$comment}', date_paid='{$pay_date}', time_paid=CURTIME(), batchno='{$batchno}' WHERE pvno='{$pvno}'";
               $isPA=$bursary->get_any_value("purchase_advance", "vouchertb", "pvno", $pvno);
               mysqli_query($con, $sql) or die( mysqli_error($con));

               $sql2 = "UPDATE `budget_votebooktb` set status = 'PAID' WHERE voucher_pvno = '{$pvno}'";
               
               
               $sqq= mysqli_query($con, "SELECT * FROM vouchertb WHERE pvno='{$pvno}'");
               if($rr= mysqli_fetch_array($sqq, 3 )){
                    $sqq2 = mysqli_query($con, "SELECT * FROM voucher_folio_codetb WHERE pvno='{$pvno}'");
                    while($rr2= mysqli_fetch_array($sqq2, 3 )){
                         $adv = $bursary->get_any_value("folio_code", "advancetb", "folio_code", $rr2['folio_code']);
                         if($adv != '') $isPA = 'Yes';
                         $sql3 = "INSERT INTO transtb set dept_acctcode='{$rr['dept_code']}', acctcode='{$acctcode}', folio_code='{$rr2['folio_code']}', transtype='Debit', transdate='{$pay_date}', amount='{$rr2['amount']}', paybatch='{$cheque_no}', pvno='{$pvno_paid}', comment='{$comment}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='{$isPA}'";
                         
                         if( mysqli_query($con, $sql3)) {
                              //check if voucher is used to pay for staff loan the update loan table
                              $lnq= mysqli_query($con, "SELECT * FROM hr_loan_apptb WHERE loan_no = '$pvno'");
                              $lnnum= mysqli_num_rows($lnq);
                              if($lnnum > 0){
                                   $sql4 = "UPDATE hr_loan_apptb SET process_status='Processed', process_date=Now(), payment_status='Paid' WHERE loan_no = '$pvno'";
                                   if( mysqli_query($con, $sql4)) $ERT=true;
                                   else{
                                        rollback();
                                        echo "<script>alert('Error :: Update failed!');</script>";
                                        exit;
                                   }
                              }
                         }else {
                              rollback(); echo "<script>alert('Loop:::Update failed!');</script>"; exit;
                         }
                    }
               }
               if( mysqli_query($con, $sql) &&  mysqli_query($con, $sql2)) {
                    commit();
                    echo "<script>alert('Record updated successfully...'); </script>";
                    exit;
               }
               else {
                    rollback();
                    echo "<script>alert('Update failed!');</script>";
                    exit;
               }
               //End of Code for updating other tables goes here
          } //end of cash officer
          echo "<script>alert('Record updated successfully');</script>";
          exit;
     }

     if($id=='remove_from_schedule')
     {
          $login_id=@$_SESSION['login_id'];
          $pvno=$_REQUEST['pvno'];
          $pvid=$_REQUEST['pvnoid'];
          $opt=$_REQUEST['opt'];  
          $r_vals=$_REQUEST['r_vals'];
          $r=strtolower($r_vals);
          $comment=$_REQUEST['comment'];
          
          if(!isset($_SESSION['login_id']) or $_SESSION['login_id'] == ''){
               echo "<script>alert('Session timeout. You will have login again!'); window.location='index.php';</script>"; 
               exit;
          }
          if($opt=='' )
          {
               echo "<script>alert('Error: You have not selected an option from the list');</script>";
               exit;
          }
          if($opt=='Queried')
          {
               if($comment==''){
                    echo "<script>alert('You cannot submit a query without comment. Write a proper description of the query.');</script>";
                    exit;
               }
               @mysqli_query($con, "DELETE FROM `pv_pay_scheduletb` where pvid = '{$pvid}'");
               @mysqli_query($con, "INSERT INTO voucher_queriestb SET pvno='{$pvno}', qrole='{$r}', querytext='{$comment}', query_date=CURDATE(), query_by='{$login_id}', response='Schedule returned', rrole='{$r}', response_date=CURDATE(), response_by='{$login_id}'");
          }
          echo "<script>alert('Payment voucher schedule returned and query lodged.');</script>";
          
          exit;
     }


     if($id=='process_voucher_batch')
     {
          $login_id = $_SESSION['login_id'];
          $pvn = $_REQUEST['pvno'];
          $batchn = $_REQUEST['cheque_no'];
          $pvno_pai = $_REQUEST['pvno_paid'];
          $op = $_REQUEST['opt'];  
          $r_vals = $_REQUEST['r_vals'];
          $r=strtolower($r_vals);
          $commen = $_REQUEST['comment'];
          $acctcod = $_REQUEST['acctcode'];
          $cheque_n = $_REQUEST['cheque_no'];
          $pay_dat = $_REQUEST['pay_date'];

          if(!isset($_SESSION['login_id']) or $_SESSION['login_id'] == ''){
               echo "<script>alert('Session timeout. You will have login again!'); window.location='index.php';</script>"; exit;
          }
          for($i=1; $i<=count($pvn); $i++) {
               $opt=$op[$i];
               $pvno = $pvn[$i];
               $batchno = strtoupper($batchn[$i]);
               $pvno_paid = strtoupper($pvno_pai[$i]);
               $comment = $commen[$i];
               $acctcode = $acctcod[$i];
               $pay_date = $pay_dat[$i];
               $errormsg='';
               if($opt != '' ){
                    if($opt=='Queried'){
                         if($comment != ''){
                              $memo_id=@get_voucher_memoid($pvno);
                              mysqli_query($con, "UPDATE memotb SET memo_status='Queried' WHERE memo_id='{$memo_id}'");
                              mysqli_query($con, "INSERT INTO memo_querytb SET memo_id='{$memo_id}', remark='{$comment}', dept_unit='',status='Queried', date=CURDATE(), time=CURTIME(),entry_date=CURDATE(),entry_time=CURTIME(),entry_by='{$login_id}'");
                              mysqli_query($con, "DELETE FROM `budget_votebooktb` WHERE voucher_pvno = '{$pvno}'");
                         }else{
                              $errormsg .= "<font color='red'>{$i} => Comment cannot be empty for queried voucher.</font><br>";
                         }
                    }elseif($opt=='Approved'){
                         if($pvno_paid !='' && $acctcode!=''){
                              begin();
                              $sql="update vouchertb set pvno_paid='{$pvno_paid}', acctcode='{$acctcode}', paid_by='{$login_id}', paid_action='{$opt}', paid_remark='{$comment}', date_paid='{$pay_date}', time_paid=CURTIME(), batchno='{$batchno}' where pvno='{$pvno}'";
                              $isPA=$bursary->get_any_value("purchase_advance", "vouchertb", "pvno", $pvno);
                              mysqli_query($con, $sql) or die( mysqli_error($con));

                              $sql2 = "update `budget_votebooktb` set status = 'PAID' WHERE voucher_pvno = '{$pvno}'";
                              if( mysqli_query($con, $sql) and  mysqli_query($con, $sql2)) {
                                   commit();
                                   $errormsg .= "<font color='green'>{$i} => Payment record updated successfully.</font><br>";
                              }
                              else {
                                   rollback();
                                   $errormsg .= "<font color='red'>{$i} => Payment update failed!.</font><br>";
                              }
                         }
                    }
               }
          }
          echo $errormsg;
          exit;
     }


     if($id=='process_voucherpv')
     {
     $login_id=@$_SESSION['login_id'];
     $pvno=$_REQUEST['pvno'];
     $nbatchpv=$_REQUEST['nbatchpv'];
     $npv=$_REQUEST['npv'];

     $opt=$_REQUEST['opt'];  //Approved or Not Approved
     $r_vals=$_REQUEST['r_vals'];
     $r=strtolower($r_vals);
     $comment=$_REQUEST['comment'];
     /*echo "<script>alert('$pvno_paid');</script>"; exit;*/
     if(!isset($_SESSION['login_id']) or $_SESSION['login_id'] == ''){
     echo "<script>alert('Session timeout. You will have login again!'); window.location='index.php';</script>"; exit;
     }

     $sql="update vouchertb set pvno_paid='$npv',batchno='$nbatchpv' WHERE pvno='{$pvno}' limit 1";
     mysqli_query($con, $sql) or die( mysqli_error($con));


     echo "<script>alert('Record updated successfully');</script>";


     exit;
     }

     if($id=='process_voucher2')
     {
     $login_id=@$_SESSION['login_id'];
     $pvno=$_REQUEST['pvno'];
     $opt=$_REQUEST['opt'];  //Approved or Not Approved
     $r_vals=$_REQUEST['r_vals'];
     $r=strtolower($r_vals);
     $comment=$_REQUEST['comment'];
     if($opt=='')
     {
     echo "<script>alert('Error: You have not selected an option from the list');</script>";
     exit;
     }
     if( mysqli_query($con, "update voucher_extra_allocation_requesttb set approval_status='$opt',approved_date=NOW(),approved_time=NOW(),approved_by='$login_id' WHERE pvno='{$pvno}'"))
     {
     echo "<script>alert('Record updated successfully.');</script>";
     }
     else
     {
     echo "<script>alert('Record update failed.');</script>";
     }


     exit;
     }

     if($id=='display_voucher_process2')////voucher extra allocation request
     {
     $pvno=$_REQUEST['pvno'];
     $r_vals=$_REQUEST['r_vals'];
     $res_d=@mysqli_query($con, "SELECT * FROM voucher_extra_allocation_requesttb WHERE pvno='{$pvno}'");
     $rs_d=@mysqli_fetch_array($res_d);

     if(strtolower($r_vals)=="bursar")
     {
     $tb="<form name='frmpro' id='frmpro'><table><tr><th>PV NO</th><td>$pvno</td></tr>
     <tr><th>FOLIO/CODE</th><td>".read_voucher_folio_code($pvno)."</td></tr>
     <tr><th>ACTION</th><td><select name='opt' id='opt'>
     <option selected value=''>---</option>
     <option value='Approved'>Approved</option>
     <option value='Not Approved'>Not Approved</option>
     </select><input type='hidden' name='pvno' id='pvno' value='$pvno'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>
     <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_voucher2');\" class='btn'/></th></tr>
     </table><div id='process_voucher2'></div></form>";
     echo $tb;
     }

     exit;
     }//end of voucher extra allocation request


     if($id=='display_approval_process')   ////this module is used for salary approval
     {
     $str=explode("***",$_REQUEST['pvno']); $month=$str[0]; $year=$str[1];
     $pvno=$month."***".$year;
     $r_vals=$_REQUEST['r_vals'];
     $res_d=@mysqli_query($con, "SELECT * FROM payroll_schedule_processtb where month='$month' and year='$year'");
     $rs_d=@mysqli_fetch_array($res_d);
     if(strtolower($r_vals)!="cash officer")
     {
     $tb="<form name='frmpro' id='frmpro'><table><tr><th>MONTH PROCESSED</th><td>".get_month_name($month)."</td></tr>
     <tr><th>YEAR</th><td>$year</td></tr>
     <tr><th>PREPARED BY</th><td>".get_staff_name($rs_d['prepared_by'])."</td></tr>
     <tr><th>DATE PREPARED</th><td>".@date('d/m/Y',strtotime($rs_d['date_prepared']))."</td></tr>
     <tr><th>ACTION</th><td><select name='opt' id='opt'>
     <option selected value=''>---</option>
     <option value='Approved'>Approved</option>
     <!--<option value='Not Approved'>Not Approved</option>-->
     </select><input type='hidden' name='pvno' id='pvno' value='$pvno'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>
     <tr><th>COMMENTS</th><td><textarea name='comment' id='comment' cols='15' rows='3'></textarea></td></tr>
     <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_approval');\" class='btn'/></th></tr>
     <table><div id='process_approval'></div></form>";
     echo $tb;
     }
     else
     {
     //section for the person to pay
     $res_a=@mysqli_query($con, "SELECT * FROM bank_accounttb order by acctname");
     $tb="<form name='frmpro' id='frmpro'><table><tr><th>MONTH PROCESSED</th><td>".get_month_name($month)."</td></tr>
     <tr><th>YEAR</th><td>$year</td></tr>
     <tr><th>PREPARED BY</th><td>".get_staff_name($rs_d['prepared_by'])."</td></tr>
     <tr><th>DATE PREPARED</th><td>".@date('d/m/Y',strtotime($rs_d['date_prepared']))."</td></tr>
     <tr><th>ACTION</th><td><select name='opt' id='opt'>
     <option selected value=''>---</option>
     <option value='Approved'>Approved</option>
     <!--<option value='Not Approved'>Not Approved</option>-->
     </select></td></tr>
     <tr><th>VOTE/ACCT. TO BE CREDITED</th><td><select name='acctcode' id='acctcode'><option selected value=''>---</option>";

     while($rs_a=@mysqli_fetch_array($res_a))
     {
     $acctcode=$rs_a['acctcode'];
     $acctname=$rs_a['acctname'];
     $tb.="<option value='$acctcode'>$acctname <=> ($acctcode)</option>";
     }

     $tb.="
     </select><input type='hidden' name='pvno' id='pvno' value='$pvno'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>
     <tr><th>COMMENTS</th><td><textarea name='comment' id='comment' cols='15' rows='3'></textarea></td></tr>
     <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_approval');\" class='btn'/></th></tr>
     <table><div id='process_approval'></div></form>";

     echo $tb;

     }

     exit;
     }

     if($id=='process_approval') //this module is used for salary approval
     {
     $login_id=@$_SESSION['login_id'];
     $str=explode("***",$_REQUEST['pvno']); $month=$str[0]; $year=$str[1];
     $pvno=$month."***".$year;
     $opt=$_REQUEST['opt'];  //Approved or Not Approved
     $r_vals=$_REQUEST['r_vals'];
     $r=strtolower($r_vals);
     $comment=$_REQUEST['comment'];
     if($opt=='')
     {
     echo "<script>alert('Error: You have not selected an option from the list');</script>";
     exit;
     }

     if($r!='cash officer')
     {
     if($r=="super admin" or $r=="auditor")
     $sql="update payroll_schedule_processtb set 	checked_by='$login_id',checked_action='$opt',checked_remark='{$comment}',date_checked=CURDATE(),time_checked=CURTIME() where month='$month' and year='$year'";
     elseif($r=="super admin" or $r=="bursar")
     $sql="update payroll_schedule_processtb set authorized_by='$login_id',authorized_action='$opt',authorized_remark='{$comment}',date_authorized=CURDATE(),time_authorized=CURTIME() where month='$month' and year='$year'";
     mysqli_query($con, $sql) or die( mysqli_error($con));

     }
     elseif($r=="super admin" or $r=="cash officer")
     {
     $acctcode=$_REQUEST['acctcode'];

     $pay_date=@date('Y-m-d');     //date the money is to be paid


     $sql="update payroll_schedule_processtb set acctcode='$acctcode',paid_by='$login_id',paid_action='$opt',date_paid=CURDATE(),time_paid=CURTIME() where month='$month' and year='$year'";
     mysqli_query($con, $sql) or die( mysqli_error($con));

     ///hit other tables when approval of salary is made e.g transtb
     if($opt=='Approved')
     @mysqli_query($con, "update payroll_scheduletb set final_approval_status='Approved',final_approval_by='$login_id' where month='$month' and year='$year'");

     //save the salary records for the month and year to transtb
     //coming back to save this per group of folio code
     $res_ad=@mysqli_query($con, "select dept_acctcode from account_depttb where deptname like '%cash%'");
     $rs_ad=@mysqli_fetch_array($res_ad); $dept_acctcode=$rs_ad['dept_acctcode'];

     $res_t=@mysqli_query($con, "SELECT * FROM payroll_scheduletb where month='$month' and year='$year'");
     if(@mysqli_num_rows($res_t)>=1)
     {
     while($rs_t=@mysqli_fetch_array($res_t))
     {
     //if($rs_t['payment_type']=='Allowance')
     //$pay_type="Credit";
     //else
     //$pay_type="Debit";

     insert_transaction($rs_t['fileno'],$dept_acctcode,$acctcode,$rs_t['folio_code'],'Credit',$pay_date,$rs_t['amount'],$pvno,'Salary Payment',$login_id,$pvno,$rs_t['fullname']);
     }
     } //end of if found in payroll_scheduletb table




     } //end of cash officer


     echo "<script>alert('Record updated successfully');</script>";

     exit;
     }

     if($id=='other_payment_section')
     {
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited

     $login_id=@$_SESSION['login_id'];

     $fileno=@$_REQUEST['fileno'];
     $type=@$_REQUEST['type'];
     $amount=@$_REQUEST['d_amount'];
     if($_REQUEST['folio'] != '') $folio=$_REQUEST['folio'];
     elseif(isset($_REQUEST['efolio']) and $_REQUEST['efolio'] != ''){
     $fval = explode("***", $_REQUEST['efolio']);
     $folio=$fval[0];
     $amount = $bursary->get_any_value("value", "allowancestb", "id", $fval[1]);
     }
     else $folio='';
     $start_date=@$_REQUEST['d_sdate'];
     $end_date=@$_REQUEST['d_edate'];
     $status=@$_REQUEST['pstatus'];
     $principal=@$_REQUEST['d_principal'];
     $installment=@$_REQUEST['d_installment'];

     $r_id=$_REQUEST['r_id'];
     //echo $folio; exit;
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
     if($fileno=='' || $type=='' || $start_date=='' || $end_date==''  || $amount <= 0 || $folio=='' || $status== '' ){
     echo "<script>alert('All compulsory fields must be filled before you can proceed!');</script>";
     exit;
     }
     //i want to treat the start_date and end_date
     $start_date_day=@date('d',strtotime($start_date)); $start_date_month=@date('m',strtotime($start_date));
     $start_date_year=@date('Y',strtotime($start_date));

     $end_date_day=@date('d',strtotime($end_date)); $end_date_month=@date('m',strtotime($end_date));
     $end_date_year=@date('Y',strtotime($end_date));

     //create new start date and end date
     ////$start_date=$start_date_year."-".sprintf("%02d",$start_date_month)."-"."01";

     ////$end_date=prepare_transdate($end_date_month,$end_date_year);  //$end_date_year."-".sprintf("%02d",$end_date_month)."-".;
     if($folio == ''){
     echo "<script>alert('No folio code submitted!');</script>"; exit; }
     //echo $folio; exit;

     $res_c=@mysqli_query($con, "SELECT * FROM otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");
     if(@mysqli_num_rows($res_c)<=0)
     {
     @mysqli_query($con, "INSERT INTO otherpayment_sourcetb set fileno='". mysqli_real_escape_string($con, $fileno).
     "', folio_code='". mysqli_real_escape_string($con, $folio)."', amount='". mysqli_real_escape_string($con, $amount).
     "', start_date='". mysqli_real_escape_string($con, $start_date)."', end_date='". mysqli_real_escape_string($con, $end_date).
     "', payment_type='". mysqli_real_escape_string($con, $type)."', status='". mysqli_real_escape_string($con, $status).
     "', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='". mysqli_real_escape_string($con, $login_id).
     "', principal='". mysqli_real_escape_string($con, $principal)."', installment='". mysqli_real_escape_string($con, $installment)."'");

     logs("$login_id", "Save Record", "$login_id saved other payment source record $fileno $folio $amount $type");
     $sql="SELECT * FROM otherpayment_sourcetb where fileno='$fileno' and payment_type='$type' order by fileno,payment_type,end_date";
     //echo "$fileno in less";
     echo "<script>alert('Record saved successfully');</script>";
     } //update record
     else
     {
     echo  "<script>if(!confirm('$type with the same folio code has already been defined for this staff. Update definition?')) exit; </script>";
     mysqli_query($con, "update otherpayment_sourcetb set fileno='". mysqli_real_escape_string($con, $fileno).
     "', folio_code='". mysqli_real_escape_string($con, $folio)."', amount='". mysqli_real_escape_string($con, $amount).
     "', start_date='". mysqli_real_escape_string($con, $start_date)."', end_date='". mysqli_real_escape_string($con, $end_date).
     "', payment_type='". mysqli_real_escape_string($con, $type)."', status='". mysqli_real_escape_string($con, $status).
     "', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='". mysqli_real_escape_string($con, $login_id).
     "' where fileno='". mysqli_real_escape_string($con, $fileno)."' and folio_code='". mysqli_real_escape_string($con, $folio).
     "' and payment_type='". mysqli_real_escape_string($con, $type)."'") or die( mysqli_error($con));
     logs("$login_id","Save Record","$login_id update other payment source record $fileno $folio $amount $type");
     $sql="SELECT * FROM otherpayment_sourcetb where fileno='". mysqli_real_escape_string($con, $fileno)."' and payment_type='$type' order by fileno, payment_type, end_date";
     // echo "$fileno in greater";
     echo "<script>alert('Record updated successfully');</script>";
     } //save


     //$sql="SELECT * FROM schooltb order by sch_code";
     }

     if($action=='delete')
     {
     //echo $r_id; exit;
     if($r_id == ''){
     //using the delete button
     $res_d=@mysqli_query($con, "SELECT * FROM otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");
     $rs_d=@mysqli_fetch_array($res_d);
     ///logs("$login_id","Delete Record","$login_id deleted other payment sources record File No: {$rs_d['fileno']} Folio: {$rs_d['folio_code']} Amount: {$rs_d['amount']}");

     //@mysqli_query($con, "DELETE FROM otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");//for logs purpose
     }else{
     //delete executed from the table link
     $newstat= mysqli_real_escape_string($con, $_REQUEST['istat']);
     ////if( mysqli_query($con, "DELETE FROM allowancestb where id='$r_id'"))
     if($newstat == '' or $newstat == 'udefined') {
     echo "<script>alert('Cannot determine payment item status. Update failed!');</script>";
     }else{
     if( mysqli_query($con, "update otherpayment_sourcetb set status='$newstat' where id=".$r_id)){
     $res_d=@mysqli_query($con, "SELECT * FROM otherpayment_sourcetb where id='$r_id'");
     $rs_d=@mysqli_fetch_array($res_d);
     ////logs("$login_id","Delete Record","$login_id deleted other payment sources record File No: {$rs_d['fileno']} Folio: {$rs_d['folio_code']} Amount: {$rs_d['amount']}");
     logs($login_id,"Update Record", "$login_id deleted other payment sources record File No: {$rs_d['fileno']} Folio: {$rs_d['folio_code']}, Status: {$rs_d['status']}");
     }// end query test
     }//end $newstat test
     ////@mysqli_query($con, "DELETE FROM otherpayment_sourcetb where id='$r_id'");//for logs purpose
     }
     //$sql="SELECT * FROM schooltb order by sch_code";
     $sql="SELECT * FROM otherpayment_sourcetb where fileno='$fileno' and payment_type='$type' order by fileno,payment_type,end_date";
     echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
     /*echo "<script>alert('I am here Dude!');</script>"; exit;*/
     //$sql="SELECT * FROM otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'";
     // $res_s=@mysqli_query($con, $sql)
     //$rs_s=@mysqli_fetch_array($res_s);
     //if(@mysqli_num_rows($res_s)>=1)
     //{
     $db->sql("SELECT * FROM otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");
     $t= @json_decode($db->getResult());
     $s_array=@array(s_detail=>"", msg=>"");

     if($t->row>=1) //fond
     {
     $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
     }else{
     $s_array['s_detail']=$t->data; $s_array['msg']='0'; echo @json_encode($s_array);
     }

     $sql="SELECT * FROM otherpayment_sourcetb where fileno='$fileno' order by fileno, payment_type, end_date";
     exit;
     //} //end of found
     //else
     //{

     //} //end of not found

     }//end of search

     if($action=='view')
     {
     /////////////////////view section ////////////////////
     $sql="SELECT * FROM otherpayment_sourcetb where fileno='$fileno' and payment_type='$type' order by fileno,payment_type,end_date";
     }
     $sn = 0;
     $res_v =  mysqli_query($con, $sql); //or die( mysqli_error($con));
     $tb="<hr><h3 style='text-align:center'>".@get_staff_name($fileno)." (".$fileno.")</h3>
     <table  border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' style='font-size:10px' width='100%'>
     <tr align='left'>
     <th>S/N</th>
     <!--<th>FILE NO</th>
     <th>FULLNAME</th>-->
     <th>FOLIO</th>
     <th>START DATE</th>
     <th>END DATE</thn>
     <th>PRIN.</th>
     <th>INST.</th>
     <th>M. INST.</th>
     <th>CUMULATIVE</thn>
     <th>STATUS</th>
     <th>ACTION</th>
     </tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
     while($rs_v=@mysqli_fetch_array($res_v))
     {
     ++$sn;
     if($rs_v['status']=="Active") $alstat="A";
     if($rs_v['status']=="Constant") $alstat="K";
     if($rs_v['status']=="Suspend") $alstat="X";

     $tb.="<tr>
     <td>$sn:</td>
     <!--<td>".$rs_v['fileno']."</td>
     <td>".@get_staff_name($rs_v['fileno'])."</td>-->
     <td>".@get_folio_name($rs_v['folio_code'])." (".$rs_v['folio_code'].")</td>
     <td>".date('d/m/Y',strtotime($rs_v['start_date']))."</td>
     <td>".date('d/m/Y',strtotime($rs_v['end_date']))."</td>
     <td>".$rs_v['principal']."</td>
     <td>".$rs_v['installment']."</td>
     <td>".number_format($rs_v['amount'],2)."</td>
     <td>".@$bursary->get_total_pay_per_item($rs_v['fileno'], $rs_v['folio_code'])."</td>
     <td align='center'>".$alstat."</td>
     <td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation?'))
     swapcontent('other_payment_section', 'delete', '".$rs_v['id']."', 'Active');\" title='Active (Pay once)'>A</a>
     |
     <a href=\"javascript:if(confirm('Are you sure you want to perform this operation?'))
     swapcontent('other_payment_section', 'delete', '".$rs_v['id']."', 'Constant');\" title='Constant (Coninous pay)'>K</a>
     |
     <a href=\"javascript:if(confirm('Are you sure you want to perform this operation?'))
     swapcontent('other_payment_section', 'delete', '".$rs_v['id']."', 'Suspend');\" title='Suspend (Stop pay)'>X</a>

     <!--a href='#' title='Delete Record' onclick=\"if(confirm('Are you sure you want to perform this operation')) swapcontent('other_payment_section', 'delete', '".$rs_v['id']."');\">Delete</a-->";

     if($type=="Deduction")  $tb.=" | <a href='#' title='Edit Record' onclick=\"swapcontent('other_payment_section','searchx:$rs_v[fileno]:$rs_v[folio_code]');\">Edit</a>";

     $tb.="</td>
     </tr>";
     }//end of while
     $tb.="</table>
     <p style='color:maroon'><strong>Footnote:</strong><br>
     INST. => NUMBER OF INSTALLMENTS<br>
     PRIN. => PRINCIPAL AMOUNT<br>
     M. INST. => MONTHLY INSTALLMENT<br>
     Status => &nbsp;&nbsp;A --> Active (Current month)<br>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     K --> Constant (Continous)<br>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     X --> Suspend";
     echo $tb;
     }
     else
     echo "<b>No record to display</b>";

     exit;
     }

     if($id=='salary_staus_section')
     {
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     $fileno=@$_REQUEST['fileno'];
     $reason=@$_REQUEST['reason'];
     $start_date=@$_REQUEST['start_date'];
     $end_date=@$_REQUEST['end_date'];
     $status=@$_REQUEST['pstatus'];

     if($action=='save')
     {
     if($fileno=='' || $start_date=='' || $end_date==''  || $status== '' ){
     echo "<script>alert('All compulsory fields must be filled before you can proceed!!');</script>";
     exit;
     }
     //i want to treat the start_date and end_date
     $start_date_day=@date('d',strtotime($start_date)); $start_date_month=@date('m',strtotime($start_date));
     $start_date_year=@date('Y',strtotime($start_date));

     $end_date_day=@date('d',strtotime($end_date)); $end_date_month=@date('m',strtotime($end_date));
     $end_date_year=@date('Y',strtotime($end_date));

     //create new start date and end date
     ////$start_date=$start_date_year."-".sprintf("%02d",$start_date_month)."-"."01";

     ////$end_date=prepare_transdate($end_date_month,$end_date_year);  //$end_date_year."-".sprintf("%02d",$end_date_month)."-".;

     $res_c=@mysqli_query($con, "SELECT * FROM salary_status where fileno='$fileno' and start_date='$start_date' and status='$status'");
     if(@mysqli_num_rows($res_c)<=0)
     {
     @mysqli_query($con, "INSERT INTO salary_status set fileno='". mysqli_real_escape_string($con, $fileno).
     "', start_date='". mysqli_real_escape_string($con, $start_date)."', end_date='". mysqli_real_escape_string($con, $end_date).
     "', status='". mysqli_real_escape_string($con, $status)."', reason='". mysqli_real_escape_string($con, $reason).
     "', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='". mysqli_real_escape_string($con, $login_id)."'");

     logs("$login_id", "Save Record", "$login_id saved salary payment status for $fileno: $start_date: $end_date: $status");
     $sql="SELECT * FROM salary_status WHERE fileno='$fileno' AND NOT deleted='Yes'";
     //echo "$fileno in less";
     echo "<script>alert('Record saved successfully');</script>";
     } //update record
     else
     {
     echo  "<script>if(!confirm('$Record with the same criteria already exist. Update definition?')) exit; </script>";
     @mysqli_query($con, "update salary_status set fileno='". mysqli_real_escape_string($con, $fileno).
     "', start_date='". mysqli_real_escape_string($con, $start_date)."', end_date='". mysqli_real_escape_string($con, $end_date).
     "', status='". mysqli_real_escape_string($con, $status)."', reason='". mysqli_real_escape_string($con, $reason).
     "', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='". mysqli_real_escape_string($con, $login_id)."'  where fileno='". mysqli_real_escape_string($con, $fileno)."' and start_date='". mysqli_real_escape_string($con, $start_date)."'") or die( mysqli_error($con));

     logs("$login_id","Save Record","$login_id update salary payment status for $fileno: $start_date: $end_date: $status");
     $sql="SELECT * FROM salary_status WHERE fileno='$fileno' AND NOT deleted='Yes'";
     // echo "$fileno in greater";
     echo "<script>alert('Record updated successfully');</script>";
     } //save


     //$sql="SELECT * FROM schooltb order by sch_code";
     }

     if($action=='delete')
     {
     //echo $r_id; exit;
     if($r_id != ''){
     //using the delete button
     //echo "UPDATE salary_status SET deleted='Yes' WHERE id=$r_id"; exit;
     $res_d=@mysqli_query($con, "UPDATE salary_status SET deleted='Yes' WHERE id=$r_id"); //fileno='$fileno' AND start_date='$start_date' AND status='$status'");
     $rs_d=@mysqli_fetch_array($res_d);
     }
     $sql="SELECT * FROM salary_status WHERE fileno='$fileno' AND NOT deleted='Yes'";
     echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
     if ($status=='' and $fileno != '') $sql="SELECT * FROM salary_status WHERE id='$r_id'";

     $t= @json_decode($db->getResult());
     $s_array=@array(s_detail=>"", msg=>"");

     if($t->row>=1) //fond
     {
     $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
     }else{
     $s_array['s_detail']=$t->data; $s_array['msg']='0'; echo @json_encode($s_array);
     }

     $sql="SELECT * FROM salary_status WHERE fileno='$fileno' AND NOT deleted='Yes'";
     exit;
     }//end of search

     if($action=='view')
     {
     /////////////////////view section ////////////////////
     if ($status=='' and $fileno != '') $sql="SELECT * FROM salary_status WHERE fileno='$fileno' AND NOT deleted='Yes'";
     elseif ($status!='' and $fileno == '') $sql="SELECT * FROM salary_status WHERE status='$status'";
     elseif ($status!='' and $fileno != '') $sql="SELECT * FROM salary_status WHERE fileno='$fileno' AND status='$status'";
     elseif ($status=='' and $fileno == '') exit;
     }
     $sn = 0;
     $res_v =  mysqli_query($con, $sql); //or die( mysqli_error($con));
     $tb="<hr><h3 style='text-align:center'>".@get_staff_name($fileno)." (".$fileno.")</h3>
     <table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' style='font-size:10px' width='100%'>
     <tr align='left'>
     <th>S/N</th>
     <th>FILE NO</th>
     <th>FULLNAME</th>
     <th>START DATE</th>
     <th>END DATE</thn>
     <th>STATUS</th>
     <th>REASON</th>
     <th>ACTION</th>
     </tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
     while($rs_v=@mysqli_fetch_array($res_v))
     {
     ++$sn;

     $tb.="<tr>
     <td>$sn:</td>
     <td>".$rs_v['fileno']."</td>
     <td>".@get_staff_name($rs_v['fileno'])."</td>
     <td>".date('d/m/Y',strtotime($rs_v['start_date']))."</td>
     <td>".date('d/m/Y',strtotime($rs_v['end_date']))."</td>
     <td align='center'>".$rs_v['status']."</td>
     <td>".$rs_v['reason']."</td>
     <td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation?'))
     swapcontent('salary_staus_section', 'delete', '".$rs_v['id']."', 'Active');\" title='Delete Record'>Delete</a>

     <!--a href='#' title='Delete Record' onclick=\"if(confirm('Are you sure you want to perform this operation')) swapcontent('salary_staus_section', 'delete', '".$rs_v['id']."');\">Delete</a-->";

     if($type=="Deduction")  $tb.=" | <a href='#' title='Edit Record' onclick=\"swapcontent('salary_staus_section','searchx:$rs_v[fileno]:$rs_v[folio_code]');\">Edit</a>";

     $tb.="</td>
     </tr>";
     }//end of while
     $tb.="</table>";
     echo $tb;
     }
     else
     echo "<b>No record to display</b>";

     exit;
     }

     if($id=='bankdetails')
     {
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     $fileno=@$_REQUEST['fileno'];
     $remark=@$_REQUEST['reason'];
     $start_date=@$_REQUEST['start_date'];
     $end_date=@$_REQUEST['end_date'];
     $status=@$_REQUEST['pstatus'];
     $bank=@$_REQUEST['bank'];
     $acct=@$_REQUEST['acct'];
     $amount=@$_REQUEST['amount'];

     if($action=='save_l')
     {
     if($fileno=='' || $start_date=='' || $end_date==''  || $status== '' || $amount== '' || $amount<=0 ){
     echo "<script>alert('All compulsory fields must be filled before you can proceed!!');</script>";
     exit;
     }
     //i want to treat the start_date and end_date
     $start_date_day=@date('d',strtotime($start_date)); $start_date_month=@date('m',strtotime($start_date));
     $start_date_year=@date('Y',strtotime($start_date));

     $end_date_day=@date('d',strtotime($end_date)); $end_date_month=@date('m',strtotime($end_date));
     $end_date_year=@date('Y',strtotime($end_date));

     $res_c=@mysqli_query($con, "SELECT * FROM bank_loan where fileno='$fileno' and start_date='$start_date' and status='$status'");
     if(@mysqli_num_rows($res_c)<=0)
     {
     @mysqli_query($con, "INSERT INTO bank_loan set fileno='". mysqli_real_escape_string($con, $fileno).
     "', start_date='". mysqli_real_escape_string($con, $start_date)."', end_date='". mysqli_real_escape_string($con, $end_date).
     "', status='". mysqli_real_escape_string($con, $status)."', amount='". mysqli_real_escape_string($con, $amount).
     "', remark='". mysqli_real_escape_string($con, $remark).
     "', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='". mysqli_real_escape_string($con, $login_id)."'");

     //echo "$fileno in less";
     echo "<script>alert('Record saved successfully');</script>";
     } //update record
     else
     {
     echo  "<script>if(!confirm('$Record with the same criteria already exist. Update definition?')) exit; </script>";
     @mysqli_query($con, "update salary_status set fileno='". mysqli_real_escape_string($con, $fileno).
     "', start_date='". mysqli_real_escape_string($con, $start_date)."', end_date='". mysqli_real_escape_string($con, $end_date).
     "', status='". mysqli_real_escape_string($con, $status)."', remark='". mysqli_real_escape_string($con, $remark).
     "', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='". mysqli_real_escape_string($con, $login_id).
     "'  where fileno='". mysqli_real_escape_string($con, $fileno).
     "' and start_date='". mysqli_real_escape_string($con, $start_date)."'") or die( mysqli_error($con));

     echo "<script>alert('Record updated successfully');</script>";
     } //save


     //$sql="SELECT * FROM schooltb order by sch_code";
     }

     if($action=='save_b')
     {
     if($fileno=='' || $bank=='' || $acct=='' ){
     echo "<script>alert('All compulsory fields must be filled before you can proceed!!');</script>";
     exit;
     }
     @mysqli_query($con, "update stafftb set acct_no='". mysqli_real_escape_string($con, $acct).
     "', bank_name='". mysqli_real_escape_string($con, $bank)."'WHERE fileno='". mysqli_real_escape_string($con, $fileno)."'")
     or die( mysqli_error($con));

     logs("$login_id","Save Record","$login_id updated account details for $fileno");
     echo "<script>alert('Record updated successfully');</script>";
     }

     if($action=='delete')
     {
     if($r_id != ''){
     //using the delete button
     $res_d=@mysqli_query($con, "UPDATE bank_loan SET deleted='Yes' WHERE id=$r_id");
     $rs_d=@mysqli_fetch_array($res_d);
     }
     echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
     if ($status=='' and $fileno != '') $sql="SELECT * FROM salary_status WHERE id='$r_id'";

     $t= @json_decode($db->getResult());
     $s_array=@array(s_detail=>"", msg=>"");

     if($t->row>=1) //fond
     {
     $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
     }else{
     $s_array['s_detail']=$t->data; $s_array['msg']='0'; echo @json_encode($s_array);
     }

     $sql="SELECT * FROM salary_status WHERE fileno='$fileno' AND NOT deleted='Yes'";
     exit;
     }//end of search

     if($action=='view')
     {
     /////////////////////view section ////////////////////
     $sql="SELECT * FROM stafftb WHERE fileno='$fileno'";
     $sql_b="SELECT * FROM bank_loan WHERE fileno='$fileno'";
     }
     $sql="SELECT * FROM stafftb WHERE fileno='$fileno'";
     $sql_b="SELECT * FROM bank_loan WHERE fileno='$fileno'";

     $sn = 0;
     $res_v =  mysqli_query($con, $sql); //or die( mysqli_error($con));
     $res_b =  mysqli_query($con, $sql_b);
     $tb="<hr><h3 style='text-align:center'>".@get_staff_name($fileno)." (".$fileno.")</h3>";

     $rs_v=@mysqli_fetch_array($res_v); $rowb= mysqli_num_rows($res_v);

     $tb .= "<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' style='font-size:10px' width='100%'>";
     $tb.="
     <tr>
     <td height='33'><strong>Bank Name:</strong></td>
     <td>".$rs_v['bank_name']."</td>
     </tr>
     <tr>
     <td height='33'><strong>Account Number:</strong></td>
     <td>".$rs_v['acct_no']."</td>
     </tr>";
     if(@mysqli_num_rows($res_b)>=1)
     {
     while($rs_b=@mysqli_fetch_array($res_b))
     {
     ++$sn;
     if($rowb > 0)
     $tb .="<tr bgcolor='#99CC99'>
     <td height='10'></td>
     <td></td>
     </tr>
     <tr>
     <td height='33'><strong>Amount Loaned:</strong></td>
     <td>".$rs_b['amount']."</td>
     </tr>
     <tr>
     <td height='33'><strong>Date:</strong></td>
     <td>".$rs_b['start_date']." To ".$rs_b['end_date']."</td>
     </tr>
     <tr>
     <td height='33'><strong>Status:</strong></td>
     <td>".$rs_b['status']."</td>
     </tr>
     <tr>
     <td height='33'><strong>Remark:</strong></td>
     <td>".$rs_b['remark']."</td>
     </tr>
     <tr>
     <td height='33'>&nbsp;</td>
     <td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation?'))
     swapcontent('bankdetails', 'delete', '".$rs_b['id']."', 'Active');\" title='Delete Record'>Delete</a></td>
     </tr>";

     }//end of while
     }
     //else
     //echo "<b>No record to display</b>";
     $tb.="</table>";
     echo $tb;

     exit;
     }

     if($id=='prorata_section')
     {
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     $fileno=@$_REQUEST['fileno'];
     $days=@$_REQUEST['days'];
     $month=$_REQUEST['month'];
     $year=$_REQUEST['year'];
     $comment=$_REQUEST['comment'];
     $end_date=$_REQUEST['end_date'];
     $transdate=prepare_transdate($month,$year); //the month_end will be the day e.g 31 for Jan

     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
     $res_c=@mysqli_query($con, "SELECT * FROM proratatb where fileno='$fileno' and month='$month' and year='$year'");
     if(@mysqli_num_rows($res_c)<=0)
     {
     @mysqli_query($con, "INSERT INTO proratatb set fileno='$fileno',year='$year',month='$month',no_of_days='$days',remark='$comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id',transdate='$transdate'");
     logs("$login_id","Save Record","$login_id saved proration record FileNo:$fileno Month:$month Year: $year Days: $days");
     echo "<script>alert('Record saved successfully');</script>";
     } //update record
     else
     {
     @mysqli_query($con, "update proratatb set fileno='$fileno',year='$year',month='$month',no_of_days='$days',remark='$comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id',transdate='$transdate' where fileno='$fileno' and month='$month' and year='$year'");
     logs("$login_id","Save Record","$login_id update proration record FileNo:$fileno Month:$month Year: $year Days: $days");
     echo "<script>alert('Record updated successfully');</script>";
     } //save
     //$sql="SELECT * FROM schooltb order by sch_code";
     }

     if($action=='delete')
     {
     $res_d=@mysqli_query($con, "SELECT * FROM proratatb where fileno='$fileno' and month='$month' and year='$year'");
     $rs_d=@mysqli_fetch_array($res_d);
     logs("$login_id","Delete Record","$login_id deleted proration record File No: {$rs_d['fileno']} Month: {$rs_d['month']} Year: {$rs_d['year']} No of Days: {$rs_d['no_of_days']}");

     @mysqli_query($con, "DELETE FROM proratatb where fileno='$fileno' and month='$month' and year='$year'");//for logs purpose

     //$sql="SELECT * FROM schooltb order by sch_code";
     echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
     //$sql="SELECT * FROM otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'";
     // $res_s=@mysqli_query($con, $sql)
     //$rs_s=@mysqli_fetch_array($res_s);
     //if(@mysqli_num_rows($res_s)>=1)
     //{
     $db->sql("SELECT * FROM proratatb where fileno='$fileno' and month='$month' and year='$year'");
     $t= @json_decode($db->getResult());
     $s_array=array(s_detail=>"",msg=>"");

     if($t->row>=1) //fond
     {
     $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
     }
     else
     {
     $s_array['s_detail']=$t->data; $s_array['msg']='0'; echo @json_encode($s_array);
     }
     exit;
     //} //end of found
     //else
     //{

     //} //end of not found

     }//end of search

     if($action=='view')
     {
     /////////////////////view section ////////////////////
     $sql="SELECT * FROM proratatb where month='$month' and year='$year' order by fileno";
     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' style='font-size:12px;' width='100%'><tr align='left'><th>S/N</th><th>FILE NO</th><th>FULLNAME</th><th>NO OF DAYS</th><th>MONTH</th><th>YEAR</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
     while($rs_v=@mysqli_fetch_array($res_v))
     {
     ++$sn;
     $tb.="<tr><td>$sn</td><td>{$rs_v[fileno]}</td><td>".@get_staff_name($rs_v[fileno])."</td><td>{$rs_v['no_of_days']}</td><td>".@get_month_name($rs_v['month'])."</td><td>".$rs_v[year]."</td></tr>";
     }//end of while
     $tb.="</table>";
     echo $tb;
     }
     else
     echo "<b>No record to display</b>";
     } //end of view


     exit;

     }

     if($id=='display_schedule_voucher')
     {
     $start_date=$_REQUEST['start_date'];  
     $end_date=$_REQUEST['end_date'];
     $account=$_REQUEST['account'];
     $sql="SELECT * FROM vouchertb WHERE (checked_by!='' OR checked_by Is Not Null) AND (controlled_by!='' OR controlled_by Is Not Null) AND (authorized_by!='' OR authorized_by Is Not Null) and (authorized_by2!='' OR authorized_by2 Is Not Null) and (audit_by!='' OR audit_by Is Not Null) AND (paid_by='' OR paid_by Is Null) AND audit_action='Approved' AND (paid_action='' OR paid_action Is Null) ORDER BY audit_date DESC LIMIT 1000";
     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<table width='100%' id='MyTable12' class='table display' align='left' border='1' cellpadding='5' cellspacing='5' rules='cols' frame='box'>
     <thead> 
     <tr style='border:solid 1px #000; background-color:#f2f2f2'><th>S/NO</th><th>NARRATION</th><!--th>PV NO</th--><th>PAYEE</th><th>PAYEE ACCT NO.</th><!--th>PAYEE BANK</th--><th>DATE</th><th>GROSS (NET)</th><th>CHECKED</th><th>CERTIFIED</th><th>CONTROLLED</th><th>AUDITED</th><th>PAID</th><th>ACTION</th></tr></thead><tbody>";
  if(@mysqli_num_rows($res_v)>=1)
   {
          while($rs_v=@mysqli_fetch_array($res_v))
           {
                ++$sn;
                $pvno=$rs_v['pvno']; $pvno_paid=$rs_v['pvno_paid'];
                $desc=$rs_v['description'];
                $p=base64_encode($pvno);	$r_id = $rs_v['id'];
                $payee_name=$rs_v['payee_name'];
                $payee_acct_no=$rs_v['payee_acct_no'];
                $payee_bank_name=$rs_v['payee_bank_name'];
                $voucher_date=$rs_v['voucher_date'];
                $net = number_format($rs_v['amount_approved'], 2);
                     $pv = explode('_', $pvno);
                    $res_ds=@mysqli_query($con, "select amount_approved from vouchertb where pvno='".$pv[0]."'");
                    while($rs_ds=@mysqli_fetch_array($res_ds)) $amnt_app = $rs_ds[0];
                    $gross = read_gross($pvno);
                    //$gross=number_format($amnt_app, 2);
                
           $prepared=$rs_v['prepared_by']; 	$prepared_date=$rs_v['date_prepared'];
           $checked=$rs_v['checked_by']; 			$checked_action=$rs_v['checked_action'];	
           $checked_date=$rs_v['date_checked'];	$checked_remark=$rs_v['checked_remark'];
           $authorized=$rs_v['authorized_by'];	$authorized_action=$rs_v['authorized_action'];	
           $authorized_date=$rs_v['date_authorized'];	$authorized_remark=$rs_v['authorized_remark'];
           $controlled=$rs_v['controlled_by'];		$controlled_action=$rs_v['controlled_action'];	
           $controlled_date=$rs_v['date_controlled'];	$controlled_remark=$rs_v['controlled_remark'];
           $audited=$rs_v['audit_by'];			$audit_action=$rs_v['audit_action'];	
           $audit_date=$rs_v['date_audited'];		$audit_remark=$rs_v['audit_remark'];
           $paid=$rs_v['paid_by'];				$paid_action=$rs_v['paid_action'];	
           $paid_date=$rs_v['date_paid'];			$paid_remark=$rs_v['paid_remark'];

                $tb.="<tr><td>$sn</td><td style='font-size:10px;'>$desc</td><!--td>$pvno_paid</td--><td>$payee_name</td><td>$payee_acct_no ($payee_bank_name)</td><!--td>$payee_bank_name</td--><td>".date('d/m/Y',strtotime($voucher_date))."</td>
                <td>".$gross." (".$net.")</td>
                <td><a href='#' title='".$checked_date."'>".$checked_action."</a><br><span style='font-size:10px;'>".$checked_remark."</span></td>
                <td><a href='#' title='".$authorized_date."'>".$authorized_action."</a><br><span style='font-size:10px;'>".$authorized_remark."</span></td>
                <td><a href='#' title='".$controlled_date."'>".$controlled_action."</a><br><span style='font-size:10px;'>".$controlled_remark."</span></td>
                <td><a href='#' title='".$audit_date."'>".$audit_action."</a><br><span style='font-size:10px;'>".$audit_remark."</span></td>
                <td><a href='#' title='".$paid_date."'>".$paid_action."</a><br><span style='font-size:10px;'>".$paid_remark."</span></td>";
                     $tb.="<td nowrap><a class='iframe' href='voucher_report.php?p=$p&rv=$r' >VIEW/PROCESS</a>"; 
           } //end of while
           
           $tb.="</tbody></table>"; echo $tb;
           ?>
           <script>
               $(document).ready(function() { 
	//parent.jQuery.colorbox. (); 
	$(".iframe").colorbox({iframe:true, width:"53%", height:"100%",
	onClosed: function () {
		////window.location.reload();
	}
});
               $('#MyTable12').DataTable( {  
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
    </script>
    <?php
    }
     else
       echo "<font color='red'><b>No pending voucher to process</b></font>";

     exit;
     } //end of display_schedule_voucher


     if($id=='schedule_voucher')
     {
     $start_date=$_REQUEST['start_date'];  $end_date=$_REQUEST['end_date'];
     $account=$_REQUEST['account'];
     $code=$_REQUEST['code'];
     $batch=$_REQUEST['batch'];
     $action=$_REQUEST['action'];
     $r_id=$_REQUEST['r_id'];
     $login_id=@$_SESSION['login_id'];
     /*foreach($code as $key => $code_val)
     {
     //echo $key."<br>";
     echo $batch[$key]."::".$code[$key]."<br>";
     }
     exit;*/
     $duse = explode('-',$start_date);
     $duse1 = $duse['0'];
     if($action=='save')
     {
     if(count($code)<=0) echo "<script>alert('Invalid PV Number selection. Please select PV Number to batch');</script>";

     $pay_date=@date('Y-m-d');                          //@$_REQUEST['pay_date'];
     $month_name=@date('F',strtotime($pay_date)); $month_no=@date('m',strtotime($pay_date));
     $year=@date('Y',strtotime($pay_date));
     $res_p=@mysqli_query($con, "select count(distinct schedule_no) as total from vouchertb where month(date_paid)='$month_no' and year(date_paid)='$year'");
     $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);

     $sch_no="SD/".strtoupper($month_name."/".$year."/". $no); //echo $month_no;
     $bursary->begin();
     foreach($code as $key => $code_val)
     {
     ////$batchno=$batch[$key];



     $mysqli_query[]="update vouchertb set schedule_no='$sch_no' where pvno_paid='$code_val'"; //, batchno='$batchno'

     $pvno = $bursary->get_any_value("pvno", "vouchertb", "pvno_paid", $code_val," and date_paid >= '$start_date'");
     $batchno = $bursary->get_any_value("batchno", "vouchertb", "pvno_paid", $code_val," and date_paid >= '%$start_date%'");
     $sqq1= mysqli_query($con, "SELECT * FROM vouchertb WHERE pvno='{$pvno}' and date_paid like '%$duse1%'");
     $rr =  mysqli_fetch_array($sqq1, 3 );


     $sqq2= mysqli_query($con, "SELECT * FROM voucher_folio_codetb WHERE pvno='{$pvno}' ");
     $rr2 =  mysqli_fetch_array($sqq2, 3 );

     //$mysqli_query[] = "INSERT INTO transtb set dept_acctcode='$rr[dept_code]', acctcode='$rr[acctcode]', folio_code='$rr2[folio_code]', transtype='Debit', transdate='$rr[date_paid]', amount='$rr2[amount]', paybatch='$batchno', pvno='$code_val', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$rr[purchase_advance]'";

     $d=@mysqli_query($con, "SELECT * FROM transtb where pvno='$code_val' and transdate like '%$rr[date_paid]%'");
     $countpv = @mysqli_num_rows($d);
     if($countpv > 0)
     {
     //echo "SELECT * FROM transtb where pvno='$code_val' and transdate like '%$rr[date_paid]%'";exit;
     echo "<script language='javascript'>alert('PVNO ($code_val) already Exists, try again...');</script>";exit;
     }

     $mysqli_query[] = "INSERT INTO transtb set dept_acctcode='$rr[dept_code]', acctcode='$account', folio_code='$rr2[folio_code]', transtype='Debit', transdate='$rr[date_paid]', amount='$rr2[amount]', paybatch='$batchno', pvno='$code_val', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}', purchase_advance='$rr[purchase_advance]'";

     $umemo = @mysqli_query($con, "select memo_id from vouchertb where pvno_paid='$code_val' and memo_id != ''");
     while($memo =  mysqli_fetch_array($umemo)){
     if($memo['memo_id']=='') echo $mysqli_query[]="update memotb set memo_status='Completed' where memo_id ='". mysqli_real_escape_string($con, $memo['memo_id'])."'";
     }


     //echo "$code_val<br/>";
     } //end of pvno_paid
     $flag=false;
     for($i=0; $i < count($mysqli_query); $i++){
     if( mysqli_query($con, $mysqli_query[$i])) $flag=true;
     else $flag=false;
     }

     if($flag==true) {
     $bursary->commit();
     echo "<script>alert('The specified Vouchers have been scheduled with Schedule Number $sch_no');</script>";
     }
     else {
     $bursary->rollback();
     echo "<script>alert('Vouchers schedule failed! Try processing again');</script>";
     }
     } //end of save

     if($action=='delete')
     {
     $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'");
     $rs_d=@mysqli_fetch_array($res_d);
     $pvno_paid=$rs_d['pvno_paid'];
     $sch_no=$rs_d['schedule_no'];
     @mysqli_query($con, "update vouchertb set schedule_no='' where pvno_paid='$pvno_paid' and final_approval!='Approved'");
     echo "<script>alert('The specified Voucher has been removed from the batch');</script>";
     }
     //load the vouchers batched with schedule no sch_no
     $res_v=@mysqli_query($con, "SELECT * FROM vouchertb where schedule_no='$sch_no' order by pvno_paid") or die( mysqli_error($con));
     $sn=0;
     $tb="<table border='1' rule='rows' frame='box' cellpadding='0' cellspacing='0' rules='rows' frame='box'><tr><td colspan='8'><b><center>SCHEDULE NUMBER: $sch_no</center></b></td></tr><tr><th>S/NO</th><th>PV NO</th><th>BATCH NO</th><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>AMOUNT</th><th>PREPARED DATE</th><!--th>ACTION</th--></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
     while($rs_v=@mysqli_fetch_array($res_v))
     {
     ++$sn;
     $id2=$rs_v['id'];
     $pvno_paid=$rs_v['pvno_paid'];
     $p=@base64_encode($pvno_paid);
     $tb.="<tr><td>$sn</td><td>$pvno_paid</td><td>{$rs_v['batchno']}</td><td>{$rs_v['payee_name']}</td><td>{$rs_v['payee_acct_no']}</td><td>{$rs_v['payee_bank_name']}</td><td>".@number_format($rs_v['amount_paid'],2)."</td><td>".date('d/m/Y',strtotime($rs_v['date_prepared']))."</td><!--td><a href=\"javascript: if(confirm('Are you sure you want to delete this record?')==true) swapcontent('schedule_voucher','delete','$id2');\">DELETE FROM SCHEDULED VOUCHERS</a></td--></tr>";

     } //end of while

     $tb.="</table>";
     echo $tb;

     //////////////load schedule report ///////////////////////////
     $p=@base64_encode($sch_no); $mode=@base64_encode('voucher_schedule');
     echo "<script>window.open('report_template.php?id=$p&mode=$mode','_blank');</script>";
     //////////////end of load schedule report /////////////////////
     }
     else
     echo "<font color='red'><b>No record to display</b></font>";

     exit;
     } //end of schedule_voucher

     if($id=='display_approve_schedule_voucher')
     {
     $sch_no=$_REQUEST['sch_no'];
     $action=$_REQUEST['action'];
     $r_id=$_REQUEST['r_id'];

     if($action=='search')
     {
     $sql="SELECT * FROM vouchertb where schedule_no='$sch_no' order by pvno_paid";
     }

     if($action=='delete')
     {
     $res_d=@mysqli_query($con, "SELECT * FROM vouchertb where id='$r_id'");
     $rs_d=@mysqli_fetch_array($res_d);
     $pvno_paid=$rs_d['pvno_paid'];
     $sch_no=$rs_d['schedule_no'];
     @mysqli_query($con, "update vouchertb set schedule_no='',final_approval='' where pvno_paid='$pvno_paid'");
     $sql="SELECT * FROM vouchertb where schedule_no='$sch_no' order by pvno_paid";
     echo "<script>alert('The specified Voucher has been removed from the batch');</script>";
     }

     $res_v=@mysqli_query($con, $sql) or die( mysqli_error($con));
     $sn=0;
     $tb="<table align='center'><tr><td colspan='8'><b><center>SCHEDULE/MANDATE NUMBER: $sch_no</center></b></td></tr><tr><th>S/NO</th><th>PV NO</th><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>AMOUNT</th><th>PREPARED DATE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
     while($rs_v=@mysqli_fetch_array($res_v))
     {
     ++$sn;
     $pvno_paid=$rs_v['pvno_paid'];
     $id2=$rs_v['id'];
     $p=@base64_encode($pvno_paid);
     $tb.="<tr><td>$sn</td><td>$pvno_paid</td><td>{$rs_v['payee_name']}</td><td>{$rs_v['payee_acct_no']}</td><td>{$rs_v['payee_bank_name']}</td><td>".@number_format($rs_v['amount_paid'],2)."</td><td>".date('d/m/Y',strtotime($rs_v['date_prepared']))."</td><td><a href=\"javascript: if(confirm('Are you sure you want to delete this record?')==true) swapcontent('display_approve_schedule_voucher','delete','$id2');\">DELETE FROM SCHEDULED VOUCHERS</a></td></tr>";

     } //end of while

     $tb.="<tr><td colspan='8'><center><input type='button' name='cmds' id='cmds' value='Approve Scheduled/Mandate Vouchers' onclick=\"swapcontent('approve_schedule_voucher','save');\" class='btn'/>  <input type='button' name='cmds2' id='cmds2' value='Print Bank Schedule' onclick=\"swapcontent('approve_schedule_voucher','print');\" class='btn'/></center></td></tr></table>";
     echo $tb;
     }
     else
     echo "<font color='red'><b>No record to display</b></font>";

     exit;
     } //end of display_approve_schedule_voucher


     if($id=='approve_schedule_voucher')
     {
     $sch_no=$_REQUEST['sch_no'];
     $action=$_REQUEST['action'];
     $r_id=$_REQUEST['r_id'];
     $amt_range=$_REQUEST['amount_range'];
     $batch_no=$_REQUEST['batch_no'];
     if($action=='save')
     {
     $login_id=@$_SESSION['login_id'];

     @mysqli_query($con, "update vouchertb set final_approval='Approved',final_approval_date=CURDATE(),final_approval_by='$login_id' where schedule_no='$sch_no'");

     //Code for updating other tables goes here /////////////
     ///hit other tables when payment is made e.g schedule and transtb
     $res_vc=@mysqli_query($con, "SELECT * FROM vouchertb where schedule_no='$sch_no'");

     if(@mysqli_num_rows($res_vc)>=1)
     {
     while($rs_vc=@mysqli_fetch_array($res_vc))
     {
     $pvno_paid=$rs_vc['pvno_paid'];  //real pvno assigned after cash officer has said pay
     $pvno=$rs_vc['pvno'];

     //@mysqli_query($con, "INSERT INTO scheduletb set schedule_no='$schedule_no',pvno='$pvno_paid',date_prepared=CURDATE(),acctcode='$acctcode',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
     insert_transaction($rs_vc['fileno'],$rs_vc['dept_acctcode'],$rs_vc['acctcode'],$rs_vc['folio_code'],'Credit',$rs_vc['date_paid'],$rs_vc['amount_paid'],$pvno_paid,'Voucher Payment',$login_id,$pvno_paid,$rs_vc['payee_name']);

     //save the tax
     $res_t=@mysqli_query($con, "SELECT * FROM voucher_taxtb WHERE pvno='{$pvno}'");
     if(@mysqli_num_rows($res_t)>=1)
     {
     while($rs_t=@mysqli_fetch_array($res_t))
     {
     $tax_acct=@get_tax_account($rs_t['folio_code']);
     insert_transaction($rs_vc['fileno'],$rs_vc['dept_acctcode'],$tax_acct,$rs_t['folio_code'],'Debit',$rs_vc['date_paid'],$rs_t['amount'],$pvno_paid,'Voucher Tax Payment',$login_id,$pvno_paid,$rs_vc['payee_name']);
     }
     } //end of if found in voucher_tax table

     } //end of while loop
     } //end of found in vouchertb

     //End of Code for updating other tables goes here /////////////
     echo "<script>alert('The specified Vouchers have been approved successfully');</script>";
     } //end of save

     if($action=='print')
     {
     $status=$_REQUEST['status']; //to know whether the below message shld be displayed or not

     if(strtolower($status)!='no')
     {
     $res_ck=@mysqli_query($con, "SELECT * FROM vouchertb where schedule_no='$sch_no' and final_approval='Approved'");
     if(@mysqli_num_rows($res_ck)<=0)
     { echo "<script>alert('The vouchers corresponding to the specified schedule number - $sch_no have not been approved');</script>"; exit;}
     } //end of check
     //////////////load schedule report ///////////////////////////
     $p=@base64_encode($sch_no); $mode=@base64_encode('voucher_schedule');
     echo "<script>window.open('report_template.php?id=$p&mode=$mode&r=$amt_range&batch=$batch_no','_blank');</script>";
     //////////////end of load schedule report /////////////////////
     }

     if($action=='update_batch_no')
     {
     if( mysqli_query($con, "update vouchertb set batchno='$batch_no' where schedule_no='$sch_no'"))
     {
     echo "<script>alert('Record updated successful');</script>";
     }
     else
     echo "<script>alert('Unable to update record.');</script>";
     }

     exit;
     }  //end of approve_schedule_voucher

     if($id=='print_payment_voucher')
     {
     $pvno=$_REQUEST['pvno'];
     $action=$_REQUEST['action'];

     if($action=='print')
     {

     //////////////load schedule report ///////////////////////////
     $p=base64_encode($pvno);
     echo "<script>window.open('voucher_report.php?p=$p','_blank');</script>";
     //////////////end of load schedule report /////////////////////
     }

     exit;

     }  //end of print_payment_voucher

     if($id=='annual_increament')
     {
     $staff_cat=@$_REQUEST['staff'];
     $fileno=@$_REQUEST['fileno'];
     $year=@$_REQUEST['year'];
     $fn=set_comma_breakdown($fileno); //file number separated with comma
     $errormsg='';
     //check if this has been done before
     /*if($staff_cat!='specific')
     {
     $res_c=@mysqli_query($con, "select year from annual_increamenttb where year='$year'");
     if(@mysqli_num_rows($res_c)>=1)
     {
     echo "<script>alert('Error: The specified annual increament for the year $year has already been carried out');</script>";
     exit;
     }
     }*/
     //echo $fn; exit;
     //echo "FILE NO: $fn YEAR: $year CAT: $staff_cat";
     if($staff_cat=='all')

     $sql="select fileno,level,step,category from stafftb where status='Active'";
     elseif($staff_cat=='specific')
     $sql="select fileno,level,step,category from stafftb where status='Active' and fileno in ($fn)";

     $res_s= mysqli_query($con, $sql) or die("SQL Error!");
     $scale_name=get_scale_name();
     $sn=0;
     while($rs_s=@mysqli_fetch_array($res_s))
     {
     $level=$rs_s['level'];
     $step=$rs_s['step'];
     $fileno=$rs_s['fileno'];
     $category=$rs_s['category'];
     if(!is_numeric($level)) continue;  ///to prevent increasing principal officers steps
     $new_step=$step + 1;
     if(grade_exist($scale_name,$level,$step,$category))
     {
     ++$sn;
     $res_c=@mysqli_query($con, "SELECT * FROM annual_increamenttb where year='$year' and fileno='$fileno'");
     if(@mysqli_num_rows($res_c) < 1){
     mysqli_query($con, "START TRANSACTION");
     $sq1= mysqli_query($con, "update stafftb set step='$new_step' where fileno='$fileno'");// or die( mysqli_error($con));
     $sq2= mysqli_query($con, "INSERT INTO annual_increamenttb set year='$year', fileno='$fileno', entrydate=now(), entry_by='$_SESSION[login_id]'");// or die( mysqli_error($con)."4");
     if($sq1 and $sq2)  mysqli_query($con, "COMMIT"); else  mysqli_query($con, "ROLLBACK");
     }else{
     $errormsg .= "Annual Step Increament for the year $year has already implemented for $fileno.<br>";
     }
     } //grade exist so update step for the staff

     } //end of while

     //update annual_increamenttb
     /*if($staff_cat!='specific')
     @mysqli_query($con, "INSERT INTO annual_increamenttb set year='$year'");*/
     if( $errormsg == '' )
     echo "<font color='green'><b>$year step increaments for $sn staff have been successfully updated</b></font>";
     else
     echo "<font color='green'><b>$year Step Increaments completed!</b><p><font color='red'>$errormsg</font></p>";

     exit;
     } //end of annual salary increament
     //////////////////////////////////////End of Bursary Automation Management System (BAMS) ////////////////////////////////





     ///////////////////////////////////Human Resources Management System (HRMS) /////////////////////////////////////////
     if($id=="update_biodata")
     {
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     //echo $j->state." ".$j->lga." ".$j->fileno." ".$j->sex." ".$j->date_of_birth." ".$j->nationality; exit;
     //$dob=@date('Y-m-d',@strtotime($j->dob));
     if($j->nationality=='Nigerian')
     {
     $country="Nigeria";
     }
     else
     {
     $country=$j->country;
     }

     $res_c=@mysqli_query($con, "SELECT * FROM stafftb where fileno='$j->fileno'");

     $login_id=@$_SESSION['login_id'];
     if(@mysqli_num_rows($res_c)<=0)
     {
     $default_password=@base64_encode('1111');
     mysqli_query($con, "INSERT INTO stafftb set appno='$j->fileno',fileno='$j->fileno',title='$j->title',surname='".@mysqli_real_escape_string($con, $j->surname)."',first_name='".@mysqli_real_escape_string($con, $j->first_name)."',other_name='".@mysqli_real_escape_string($con, $j->other_name)."',maiden_name='".@mysqli_real_escape_string($con, $j->maiden_name)."',sex='$j->sex',dept_code='$j->dept',unit_code='$j->unit',marital_status='$j->marital_status',religion='$j->religion',staff_status='$j->staff_status',category='$j->category',date_of_1st_appt='$j->date_of_1st_appt',date_of_assumption='$j->date_of_assumption',date_of_present_appt='$j->date_of_present_appt',initial_level='$j->level',initial_step='$j->step',level='$j->level',step='$j->step',qualification='".@mysqli_real_escape_string($con, $j->qualification)."',rank='$j->rank',employment_status='$j->employment_status',date_of_birth='$j->date_of_birth',place_of_birth='".@mysqli_real_escape_string($con, $j->place_of_birth)."',nationality='$j->nationality',state_id='$j->state',lga_id='$j->lga',country='$country',senatorial_district='".@mysqli_real_escape_string($con, $j->senatorial_district)."',contact_address='".@mysqli_real_escape_string($con, $j->contact_address)."',residential_address='".@mysqli_real_escape_string($con, $j->residential_address)."',permanent_address='".@mysqli_real_escape_string($con, $j->permanent_address)."',email='$j->email',phone_no='$j->phone_no',bank_name='".@mysqli_real_escape_string($con, $j->bank_name)."',acct_no='$j->acct_no',last_place_of_residence='".@mysqli_real_escape_string($con, $j->last_place_of_residence)."',languages_spoken='".@mysqli_real_escape_string($con, $j->languages_spoken)."',passport_number='$j->passport_number',passport_place='".@mysqli_real_escape_string($con, $j->passport_place)."',passport_date_issue='$j->passport_date_issue',hobbies='".@mysqli_real_escape_string($con, $j->hobbies)."',disability='$j->disability',disability_reason='".@mysqli_real_escape_string($con, $j->disability_reason)."',court_case='".@mysqli_real_escape_string($con, $j->court_case)."',status='$j->status',spouse_name='".@mysqli_real_escape_string($con, $j->spouse_name)."',spouse_address='".@mysqli_real_escape_string($con, $j->spouse_address)."',spouse_occupation='".@mysqli_real_escape_string($con, $j->spouse_occupation)."',guidance_name='".@mysqli_real_escape_string($con, $j->guidance_name)."',guidance_nationality='$j->guidance_nationality',guidance_state='$j->guidance_state',guidance_occupation='".@mysqli_real_escape_string($con, $j->guidance_occupation)."',guidance_address='".@mysqli_real_escape_string($con, $j->guidance_address)."',guidance_email='$j->guidance_email',guidance_phone_no='$j->guidance_phone_no',next_name='".@mysqli_real_escape_string($con, $j->next_name)."',next_address='".@mysqli_real_escape_string($con, $j->next_address)."',next_email='$j->next_email',next_phone_no='$j->next_phone_no',next_relationship='$j->next_relationship',mother_name='".@mysqli_real_escape_string($con, $j->mother_name)."',mother_nationality='$j->mother_nationality',mother_state='$j->mother_state',mother_address='".@mysqli_real_escape_string($con, $j->mother_address)."',force_no='$j->force_no',highest_force_rank='".@mysqli_real_escape_string($con, $j->highest_force_rank)."',force_period='$j->force_period',force_character='".@mysqli_real_escape_string($con, $j->force_character)."',other_info='".@mysqli_real_escape_string($con, $j->other_info)."',password='$default_password',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

     logs($login_id,'Save Staff Record',"$login_id insert staff record with fileno $j->fileno");

     } //end of save
     else
     {
     //update staff record section
     mysqli_query($con, "update stafftb set title='$j->title',surname='".@mysqli_real_escape_string($con, $j->surname)."',first_name='".@mysqli_real_escape_string($con, $j->first_name)."',other_name='".@mysqli_real_escape_string($con, $j->other_name)."',maiden_name='".@mysqli_real_escape_string($con, $j->maiden_name)."',sex='$j->sex',dept_code='$j->dept',unit_code='$j->unit',marital_status='$j->marital_status',religion='$j->religion',staff_status='$j->staff_status',category='$j->category',date_of_1st_appt='$j->date_of_1st_appt',date_of_assumption='$j->date_of_assumption',date_of_present_appt='$j->date_of_present_appt',level='$j->level',step='$j->step',qualification='".@mysqli_real_escape_string($con, $j->qualification)."',rank='$j->rank',employment_status='$j->employment_status',date_of_birth='$j->date_of_birth',place_of_birth='".@mysqli_real_escape_string($con, $j->place_of_birth)."',nationality='$j->nationality',state_id='$j->state',lga_id='$j->lga',country='$country',senatorial_district='".@mysqli_real_escape_string($con, $j->senatorial_district)."',contact_address='".@mysqli_real_escape_string($con, $j->contact_address)."',residential_address='".@mysqli_real_escape_string($con, $j->residential_address)."',permanent_address='".@mysqli_real_escape_string($con, $j->permanent_address)."',email='$j->email',phone_no='$j->phone_no',bank_name='".@mysqli_real_escape_string($con, $j->bank_name)."',acct_no='$j->acct_no',last_place_of_residence='".@mysqli_real_escape_string($con, $j->last_place_of_residence)."',languages_spoken='".@mysqli_real_escape_string($con, $j->languages_spoken)."',passport_number='$j->passport_number',passport_place='".@mysqli_real_escape_string($con, $j->passport_place)."',passport_date_issue='$j->passport_date_issue',hobbies='".@mysqli_real_escape_string($con, $j->hobbies)."',disability='$j->disability',disability_reason='".@mysqli_real_escape_string($con, $j->disability_reason)."',court_case='".@mysqli_real_escape_string($con, $j->court_case)."',status='$j->status',spouse_name='".@mysqli_real_escape_string($con, $j->spouse_name)."',spouse_address='".@mysqli_real_escape_string($con, $j->spouse_address)."',spouse_occupation='".@mysqli_real_escape_string($con, $j->spouse_occupation)."',guidance_name='".@mysqli_real_escape_string($con, $j->guidance_name)."',guidance_nationality='$j->guidance_nationality',guidance_state='$j->guidance_state',guidance_occupation='".@mysqli_real_escape_string($con, $j->guidance_occupation)."',guidance_address='".@mysqli_real_escape_string($con, $j->guidance_address)."',guidance_email='$j->guidance_email',guidance_phone_no='$j->guidance_phone_no',next_name='".@mysqli_real_escape_string($con, $j->next_name)."',next_address='".@mysqli_real_escape_string($con, $j->next_address)."',next_email='$j->next_email',next_phone_no='$j->next_phone_no',next_relationship='$j->next_relationship',mother_name='".@mysqli_real_escape_string($con, $j->mother_name)."',mother_nationality='$j->mother_nationality',mother_state='$j->mother_state',mother_address='".@mysqli_real_escape_string($con, $j->mother_address)."',force_no='$j->force_no',highest_force_rank='".@mysqli_real_escape_string($con, $j->highest_force_rank)."',force_period='$j->force_period',force_character='".@mysqli_real_escape_string($con, $j->force_character)."',other_info='".@mysqli_real_escape_string($con, $j->other_info)."' where fileno='$j->fileno'") or die( mysqli_error($con));

     logs($login_id,'Update Staff Record',"$login_id updated staff record with fileno $j->fileno");
     } //end of update staff record


     echo "<script> alert('Staff biodata updated sucessfully');</script>";


     exit;
     } //end of update biodata for staff

     if($id=='delete_biodata')
     {
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     @mysqli_query($con, "DELETE FROM stafftb where fileno='$j->fileno'");
     echo "<script> alert('Staff biodata delete sucessfully');</script>";

     exit;
     } //end of delete staff biodata

     if($id=='load_rank')
     {
     $category=@$_REQUEST['category'];
     //echo "$dept_code"; ?>
     <select name="rank" id="rank">
     <option selected="selected" value="">---</option>
     <?php
     $res_c=@mysqli_query($con, "SELECT * FROM hr_positiontb where category='$category' order by category");
     while($rs_c=@mysqli_fetch_array($res_c))
     {
     $position=@$rs_c['position'];
     echo "<option value='$position'>$position</option>";
     }
     ?>
     </select> <?php
     exit;
     }


     if($id=="add_child")  //add_children
     {
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
     if($action=='save')
     {
     @mysqli_query($con, "INSERT INTO hr_staff_childtb set fileno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->child_name)."',date_of_birth='$j->child_dob',sex='$j->child_sex',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");

     $sql="SELECT * FROM hr_staff_childtb where fileno='$j->fileno'";

     echo "<script> alert('The specified Child\'s detail has been saved');</script>";
}

if($action=='delete')
{
     //
     $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_childtb where id='$r_id'");
     $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

     @mysqli_query($con, "DELETE FROM hr_staff_childtb where id='$r_id'");
     echo "<script> alert('Record deleted successfully');</script>";
     $sql="SELECT * FROM hr_staff_childtb where fileno='$fileno' order by name";
}

if($action=='view')
{
     //
     $sql="SELECT * FROM hr_staff_childtb where fileno='$j->fileno' order by name";
}

$res_v=@mysqli_query($con, $sql);
$sn=0;
$tb="<center><table><tr><th>S/NO</th><th>FULLNAME</th><th>DATE OF BIRTH</th><th>SEX</th><th>ACTION</th></tr>";

if(@mysqli_num_rows($res_v)>=1)
{
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $tb.="<tr><td>$sn</td><td>{$rs_v['name']}</td><td>".date('d/m/Y',strtotime($rs_v['date_of_birth']))."</td><td>{$rs_v['sex']}</td><td><a href=\"javascript:swapcontent('add_child','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;
} //end of if found
else
echo "<center><font color='red'><b>No record to display</b></font></center>";

exit;
} //add children

if($id=="add_employment")  //add_employment
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_employmenttb set fileno='$j->fileno',employer_name='".@mysqli_real_escape_string($con, $j->emp_name)."',location='".@mysqli_real_escape_string($con, $j->emp_location)."',rank='$j->emp_rank',salary='$j->emp_salary',from_year='$j->emp_year_from',to_year='$j->emp_year_to',leaving_reason='".@mysqli_real_escape_string($con, $j->emp_leaving)."',employment_type='$j->emp_type',status='$j->emp_status',duty='".@mysqli_real_escape_string($con, $j->emp_duty)."',bond_question='$j->emp_bond',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_employmenttb where fileno='$j->fileno' order by from_year";

          echo "<script> alert('The specified employment detail has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_employmenttb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_employmenttb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_employmenttb where fileno='$fileno' order by from_year";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_employmenttb where fileno='$j->fileno' order by from_year";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>EMPLOYER NAME</th><th>RANK</th><th>FROM</th><th>TO</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['employer_name']}</td><td>{$rs_v['rank']}</td><td>{$rs_v['from_year']}</td><td>{$rs_v['to_year']}</td><td><a href=\"javascript:swapcontent('add_employment','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add employment


if($id=="add_education")  //add_education
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_academic_edutb set fileno='$j->fileno',school_name='".@mysqli_real_escape_string($con, $j->edu_name)."',school_type='$j->edu_type',	qualification='$j->edu_qual',degree_class='$j->edu_grade',from_month='$j->edu_month_from',from_year='$j->edu_year_from',to_month='$j->edu_month_to',to_year='$j->edu_year_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_academic_edutb where fileno='$j->fileno' order by from_year";

          echo "<script> alert('The specified academic record has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_academic_edutb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_academic_edutb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_academic_edutb where fileno='$fileno' order by from_year";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_academic_edutb where fileno='$j->fileno' order by from_year";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>INSTITUTION NAME</th><th>TYPE</th><th>QUALIFICATION</th><th>GRADE</th><th>FROM</th><th>TO</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['school_name']}</td><td>{$rs_v['school_type']}</td><td>{$rs_v['qualification']}</td><td>{$rs_v['degree_class']}</td><td>{$rs_v['from_year']}</td><td>{$rs_v['to_year']}</td><td><a href=\"javascript:swapcontent('add_education','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add education

if($id=="add_publication")  //add_publication
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_publicationtb set fileno='$j->fileno',title='".@mysqli_real_escape_string($con, $j->pub_title)."',author='".@mysqli_real_escape_string($con, $j->pub_author)."',type='$j->pub_type',publisher='".@mysqli_real_escape_string($con, $j->pub_publisher)."',journal='".@mysqli_real_escape_string($con, $j->pub_journal)."',year_published='$j->pub_year',status='$j->pub_status',category='$j->pub_category',page_no='$j->pub_page_no',volume='$j->pub_volume',issue='$j->pub_issue',url='$j->pub_url',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_publicationtb where fileno='$j->fileno' order by year_published";

          echo "<script> alert('The specified publication has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_publicationtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_publicationtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_publicationtb where fileno='$fileno' order by year_published";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_publicationtb where fileno='$j->fileno' order by year_published";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>TITLE</th><th>AUTHOR(S)</th><th>PUBLISHER</th><th>TYPE</th><th>CATEGORY</th><th>YEAR PUBLISHED</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['title']}</td><td>{$rs_v['author']}</td><td>{$rs_v['publisher']}</td><td>{$rs_v['type']}</td><td>{$rs_v['category']}</td><td>{$rs_v['year_published']}</td><td><a href=\"javascript:swapcontent('add_publication','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add publication

if($id=="add_service")  //add_service
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_servicetb set fileno='$j->fileno',service_type='".@mysqli_real_escape_string($con, $j->serv_type)."',service_place='$j->serv_place',service_details='".@mysqli_real_escape_string($con, $j->serv_detail)."',from_year='$j->serv_from',to_year='$j->serv_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_servicetb where fileno='$j->fileno' order by service_type,from_year";

          echo "<script> alert('The specified service has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_servicetb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_servicetb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_servicetb where fileno='$fileno' order by service_type,from_year";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_servicetb where fileno='$j->fileno' order by service_type,from_year";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>TYPE</th><th>SERVICE PLACE</th><th>DETAILS</th><th>FROM</th><th>TO</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['service_type']}</td><td>{$rs_v['service_place']}</td><td>{$rs_v['service_details']}</td><td>{$rs_v['from_year']}</td><td>{$rs_v['to_year']}</td><td><a href=\"javascript:swapcontent('add_service','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add service


if($id=="add_research")  //add_research
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_researchtb set fileno='$j->fileno',topic='".@mysqli_real_escape_string($con, $j->res_topic)."',status='$j->res_status',funding_source='".@mysqli_real_escape_string($con, $j->res_funding)."',start_date='$j->res_start_date',end_date='$j->res_end_date',amount_granted='$j->res_amount',project_value='$j->res_value',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_researchtb where fileno='$j->fileno' order by start_date";

          echo "<script> alert('The specified research history has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_researchtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_researchtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_researchtb where fileno='$fileno' order by start_date";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_researchtb where fileno='$j->fileno' order by start_date";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>TOPIC</th><th>STATUS</th><th>FUNDING SOURCE</th><th>AMOUNT GRANTED</th><th>PROJECT VALUE</th><th>START DATE</th><th>END DATE</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['topic']}</td><td>{$rs_v['status']}</td><td>{$rs_v['funding_source']}</td><td>{$rs_v['amount_granted']}</td><td>{$rs_v['project_value']}</td><td>".date('d/m/Y',strtotime($rs_v['start_date']))."</td><td>".date('d/m/Y',strtotime($rs_v['end_date']))."</td><td><a href=\"javascript:swapcontent('add_research','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add research

if($id=="add_training")  //add_training
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_training_apptb set fileno='$j->fileno',training_type='$j->tra_type',start_date='$j->tra_start_date',end_date='$j->tra_end_date',training_title='".@mysqli_real_escape_string($con, $j->tra_title)."',location='".@mysqli_real_escape_string($con, $j->tra_location)."',venue='".@mysqli_real_escape_string($con, $j->tra_venue)."',no_paper_read='$j->tra_no_paper_read',sponsor='".@mysqli_real_escape_string($con, $j->tra_sponsor)."',amount_granted='$j->tra_amount',approval_status='Approved',approval_date='$j->tra_date_approved',process_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_training_apptb where fileno='$j->fileno' order by training_type,start_date";

          echo "<script> alert('The specified training has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_training_apptb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_training_apptb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_training_apptb where fileno='$fileno' order by training_type,start_date";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_training_apptb where fileno='$j->fileno' order by training_type,start_date";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>TYPE</th><th>TITLE/THEME</th><th>LOCATION</th><th>VENUE</th><th>SPONSOR</th><th>START DATE</th><th>END DATE</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['training_type']}</td><td>{$rs_v['training_title']}</td><td>{$rs_v['location']}</td><td>{$rs_v['venue']}</td><td>{$rs_v['sponsor']}</td><td>".date('d/m/Y',strtotime($rs_v['start_date']))."</td><td>".date('d/m/Y',strtotime($rs_v['end_date']))."</td><td><a href=\"javascript:swapcontent('add_training','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add training

if($id=='load_no_paper_read')
{
     $type=strtolower($_REQUEST['type']);
     if($type=='conference')
     echo "<br/>No of Paper Read: <input type='text' name='tra_no_paper_read' id='tra_no_paper_read'/>";
     else
     echo "";

     exit;
}

if($id=="add_prof_membership")  //add_prof_membership
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_prof_membershiptb set fileno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->prof_mem_name)."',category='$j->prof_mem_category',year_honoured='$j->prof_mem_year',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_prof_membershiptb where fileno='$j->fileno' order by year_honoured";

          echo "<script> alert('The specified Professional Membership has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_prof_membershiptb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_prof_membershiptb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_prof_membershiptb where fileno='$fileno' order by year_honoured";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_prof_membershiptb where fileno='$j->fileno' order by year_honoured";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>NAME</th><th>CATEGORY</th><th>YEAR HONOURED</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['name']}</td><td>{$rs_v['category']}</td><td>{$rs_v['year_honoured']}</td><td><a href=\"javascript:swapcontent('add_prof_membership','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add prof_membership


if($id=="add_prof_qual")  //add_prof_qual
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_prof_qualificationtb set fileno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->prof_qual_name)."',grade='$j->prof_qual_grade',from_year='$j->prof_qual_from',to_year='$j->prof_qual_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_prof_qualificationtb where fileno='$j->fileno' order by from_year";

          echo "<script> alert('The specified Professional Qualification has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_prof_qualificationtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_prof_qualificationtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_prof_qualificationtb where fileno='$fileno' order by from_year";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_prof_qualificationtb where fileno='$j->fileno' order by from_year";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>NAME</th><th>GRADE</th><th>FROM</th><th>TO</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['name']}</td><td>{$rs_v['grade']}</td><td>{$rs_v['from_year']}</td><td>{$rs_v['to_year']}</td><td><a href=\"javascript:swapcontent('add_prof_qual','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add prof_qual

if($id=="add_honour")  //add_honour
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_recognitiontb set fileno='$j->fileno',award_type='$j->honour_type',award_date='$j->honour_date',award_description='".@mysqli_real_escape_string($con, $j->honour_desc)."',prize='$j->honour_prize',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_recognitiontb where fileno='$j->fileno' order by award_date";

          echo "<script> alert('The specified Award/Honour has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_recognitiontb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_recognitiontb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_recognitiontb where fileno='$fileno' order by award_date";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_recognitiontb where fileno='$j->fileno' order by award_date";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>AWARD TYPE</th><th>AWARD DATE</th><th>DESCRIPTION</th><th>PRIZE</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['award_type']}</td><td>".date('d/m/Y',strtotime($rs_v['award_date']))."</td><td>{$rs_v['award_description']}</td><td>{$rs_v['prize']}</td><td><a href=\"javascript:swapcontent('add_honour','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add honour

if($id=="add_country")  //add_country
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_country_visitedtb set fileno='$j->fileno',country='$j->country_name',visit_reason='".@mysqli_real_escape_string($con, $j->country_reason)."',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_country_visitedtb where fileno='$j->fileno' order by country";

          echo "<script> alert('The specified Country has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_country_visitedtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_country_visitedtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_country_visitedtb where fileno='$fileno' order by country";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_country_visitedtb where fileno='$j->fileno' order by country";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>COUNTRY</th><th>REASON FOR VISITING</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['country']}</td><td>{$rs_v['visit_reason']}</td><td><a href=\"javascript:swapcontent('add_country','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add honour

if($id=="add_referee")  //add_referee
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     $login_id=$_SESSION['login_id'];
     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          mysqli_query($con, "INSERT INTO hr_staff_refereetb set fileno='$j->fileno',ref_name='".@mysqli_real_escape_string($con, $j->ref_name)."',ref_address='".@mysqli_real_escape_string($con, $j->ref_address)."',ref_occupation='$j->ref_occupation',ref_know_period='$j->ref_year',ref_email='$j->ref_email',ref_phone_no='$j->ref_phone_no',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="SELECT * FROM hr_staff_refereetb where fileno='$j->fileno' order by id";

          echo "<script> alert('The specified Referee has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_refereetb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_refereetb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_staff_refereetb where fileno='$fileno' order by id";
     }

     if($action=='view')
     {
          //
          $sql="SELECT * FROM hr_staff_refereetb where fileno='$j->fileno' order by id";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>NAME</th><th>OCCUPATION</th><th>ADDRESS</th><th>EMAIL</th><th>PHONE NO</th><th>ACTION</th></tr>";

     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['ref_name']}</td><td>{$rs_v['ref_occupation']}</td><td>{$rs_v['ref_address']}</td><td>{$rs_v['ref_email']}</td><td>{$rs_v['ref_phone_no']}</td><td><a href=\"javascript:swapcontent('add_referee','delete','$id2')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     } //end of if found
     else
     echo "<center><font color='red'><b>No record to display</b></font></center>";

     exit;
} //add referee

if($id=='load_pix')
{
     $fileno=$_REQUEST['fileno'];
     $pixpath=@$db->getPix2("pictures",strtoupper($fileno),"");
     //echo $fileno." Path: $pixpath";
     if(file_exists($pixpath))
     echo "<img src='$pixpath' width='200' height='200'/>";
     else
     echo "<img src='pictures/nopix.jpg' width='200' height='200'/>";

     exit;
}

if($id=='load_images')
{
     $appno=$_REQUEST['upload_appno'];
     //echo "EHRERRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR $appno";

     $sql="SELECT * FROM hr_app_documenttb where appno='$appno' order by id";
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>DOCUMENT TYPE</th><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          if($sn%2==0) $bgcolor="#FFFF99"; else $bgcolor="white";
          $path=@$rs_v['doc_path'];
          $appno=@$rs_v['appno'];
          $tb.="<tr bgcolor='$bgcolor'><td>$sn</td><td>{$rs_v['doc_type']}</td><td><a href='$path' target='_blank'>VIEW DOCUMENT</a> | <a href=\"javascript:swapcontent('delete_upload','delete','$id2','p','$appno')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;

     exit;
}

if($id=='delete_upload')
{
     $action=$_REQUEST['action'];
     $path=$_REQUEST['p'];
     $login_id=$appno=$_REQUEST['login_id'];
     $r_id=$_REQUEST['r_id'];
     /*echo "<script>alert('$login_id ACTION:$action PATH:$path');</script>"; exit; */

     if($action=='delete')
     {
          $res_p=@mysqli_query($con, "select doc_path from hr_app_documenttb where id='$r_id'");
          $rs_p=@mysqli_fetch_array($res_p);
          $path=$rs_p['doc_path'];
          @unlink($path);  //delete the file
          @mysqli_query($con, "DELETE FROM hr_app_documenttb where id='$r_id'");
          echo "<script>alert('The specified file has been deleted');</script>";
     }

     $sql="SELECT * FROM hr_app_documenttb where appno='$appno' order by id";
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>DOCUMENT TYPE</th><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          if($sn%2==0) $bgcolor="#FFFF99"; else $bgcolor="white";
          $path=@$rs_v['doc_path'];
          $tb.="<tr bgcolor='$bgcolor'><td>$sn</td><td>{$rs_v['doc_type']}</td><td><a href='$path' target='_blank'>VIEW DOCUMENT</a> | <a href=\"javascript:swapcontent('delete_upload','delete','$id2','p','$appno')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;

     exit;
}

if($id=='display_load_docs')
{
     $fileno=$_REQUEST['fileno'];
     $sql="SELECT * FROM hr_app_documenttb where appno='$fileno' order by id";
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>DOCUMENT TYPE</th><th>ACTION</th></tr>";
     if( mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               if($sn%2==0) $bgcolor="#FFFF99"; else $bgcolor="white";
               $path=@$rs_v['doc_path'];
               $appno=@$rs_v['appno'];
               $tb.="<tr bgcolor='$bgcolor'><td>$sn</td><td>{$rs_v['doc_type']}</td><td><a href='$path' target='_blank'>VIEW DOCUMENT</a> | <a href=\"javascript:swapcontent('delete_upload','delete','$id2','p','$appno')\">DELETE</a></td></tr>";
          } //end of while

          $tb.="</table></center>";
          echo $tb;
     }
     else
     echo "<font color='red'><b>No record to display</b></font>";

     exit;
}


////////////////other modules start here apart from biodata/record /////////////////
if($id=='clinic_section')
{
     $code=$_REQUEST['code'];
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     $fileno=$_REQUEST['fileno'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
          if(count($code)<=0)
          { echo "<script>alert('You have not selected an item from the list');</script>"; exit; }

          foreach($code as $code_val)
          {
               //echo "$code_val<br/>";
               if($code_val=='spouse')
               {   $res_w=@mysqli_query($con, "SELECT * FROM stafftb where fileno='$fileno'");
                    $rs_w=@mysqli_fetch_array($res_w);
                    $d_name=$rs_w['spouse_name'];
                    if($rs_w['sex']=='Male') $d_sex="Female"; else $d_sex="Male";
                    if($rs_w['sex']=='Male') $d_relation="Wife"; else $d_relation="Husband"; $d_dob="";
               }
               elseif($code_val!='spouse')
               {   $res_w=@mysqli_query($con, "SELECT * FROM hr_staff_childtb where id='$code_val'");
                    $rs_w=@mysqli_fetch_array($res_w);
                    $d_name=$rs_w['name'];
                    $d_sex=$rs_w['sex'];
                    $d_dob=$rs_w['date_of_birth'];
                    $d_relation="Child";
               }

               @mysqli_query($con, "INSERT INTO hr_staff_clinictb set fileno='$fileno',dependent_name='$d_name',dependent_dob='$d_dob',dependent_sex='$d_sex',dependent_relationship='$d_relation',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");

               $sql="SELECT * FROM hr_staff_clinictb where fileno='$fileno' order by id";

          } //end of foreach
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM hr_staff_clinictb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_staff_clinictb where id='$r_id'");
          $sql="SELECT * FROM hr_staff_clinictb where fileno='$fileno' order by id";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<table><tr><th>S/N</th><th>FULLNAME</th><th>SEX</th><th>DATE OF BIRTH</th><th>RELATION</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['dependent_name']}</td><td>{$rs_v['dependent_sex']}</td><td>{$rs_v['dependent_dob']}</td><td>{$rs_v['dependent_relationship']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('clinic_section','delete','$r_id');\">DELETE</a></td></tr>";
          }//end of while

          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";
     exit;
}


if($id=='accept_loan_guarantor')
{
     $loan_no=$_REQUEST['loan_no'];
     @mysqli_query($con, "update hr_loan_guarantortb set accept_date=CURDATE() where loan_no='$loan_no'");
     echo "<script>alert('You have successfully accepted to serve as the Guarantor for the staff');</script>";
     exit;
}

if($id=='approve_loan')
{
     $loan_no=$_REQUEST['loan_no'];
     $login_id=$_SESSION['login_id'];
     @mysqli_query($con, "update hr_loan_apptb set process_date=CURDATE(),process_status='Approved',authorize_by='$login_id' where loan_no='$loan_no'");
     echo "<script>alert('You have successfully approve loan for the specified staff');</script>";
     exit;
}

if($id=='view_guarantor')
{
     $loan_no=$_REQUEST['loan_no'];
     $res_v=@mysqli_query($con, "SELECT * FROM hr_loan_guarantortb where loan_no='$loan_no'");
     $tb="<center><b>LIST OF GUARANTORS</b><table><tr><th>S/N</th><th>LOAN NO</th><th>GUARANTOR FILE NO</th><th>NAME OF GUARANTOR</th><th>DATE GUARANTED</th></tr>";
     $sn=0;
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $tb.="<tr><td>$sn</td><td>$loan_no</td><td>{$rs_v['guarantor_fileno']}</td><td>".@get_staff_name($rs_v['guarantor_fileno'])."</td><td>".@date('d/m/Y',strtotime($rs_v['accept_date']))."</td></tr>";
          }//end of while

          $tb.="</table></center>";
          echo $tb;
     }
     else
     echo "<center><b><font color='red'>Guarantors have not accepted this loan application</font></b></center>";

     exit;

}

if($id=='regularization_section')
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          @mysqli_query($con, "update stafftb set regularisation_status='Yes',regularisation_date='$j->reg_date',regularisation_entry_by='$login_id' where fileno='$j->fileno'");

          $sql="SELECT * FROM stafftb where fileno='$j->fileno'";

          echo "<script>alert('Staff record updated successfully');</script>";
     }

     if($action=='search')
     {

          $sql="SELECT * FROM stafftb where fileno='$j->fileno'";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM stafftb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d);
          $fileno=$rs_d['fileno'];

          @mysqli_query($con, "update stafftb set regularisation_status='No',regularisation_date='0000-00-00',regularisation_entry_by='' where fileno='$fileno'");


          $sql="SELECT * FROM stafftb where fileno='$fileno'";
          echo "<script>alert('Record successfully undo');</script>";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<center><table><tr><th>S/N</th><th>FILE NO</th><th>DATE REGULARIZED</th><th>REGULARIZED BY</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];

               if($rs_v['regularisation_date']=='0000-00-00')
               $reg_date="";
               else
               $reg_date=$rs_v['regularisation_date'];

               $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>$reg_date</td><td>".@get_staff_name($rs_v['regularisation_entry_by'])."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('regularization_section','delete','$r_id');\">UNDO</a></td></tr>";
          }//end of while

          $tb.="</table></center>";
          echo $tb;
     }
     else
     echo "<center><b><font color='red'>No record to display</font></b></center>";
     exit;
}

if($id=='confirmation_section')
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          $res_ck=@mysqli_query($con, "SELECT * FROM stafftb where fileno='$j->fileno'");
          $rs_ck=@mysqli_fetch_array($res_ck); $reg_status=$rs_ck['regularisation_status'];
          if($reg_status=='No')
          {  echo "<script>alert('Error: This staff has not been Regularized');</script>"; exit; }

          @mysqli_query($con, "update stafftb set confirmation_status='Yes',confirmation_date='$j->conf_date',confirmation_entry_by='$login_id' where fileno='$j->fileno'");

          $sql="SELECT * FROM stafftb where fileno='$j->fileno'";

          echo "<script>alert('Staff record updated successfully');</script>";
     }

     if($action=='search')
     {

          $sql="SELECT * FROM stafftb where fileno='$j->fileno'";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM stafftb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d);
          $fileno=$rs_d['fileno'];

          @mysqli_query($con, "update stafftb set confirmation_status='No',confirmation_date='0000-00-00',confirmation_entry_by='' where fileno='$fileno'");


          $sql="SELECT * FROM stafftb where fileno='$fileno'";
          echo "<script>alert('Record successfully undo');</script>";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<center><table><tr><th>S/N</th><th>FILE NO</th><th>DATE CONFIRMED</th><th>CONFIRMED BY</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];

               if($rs_v['confirmation_date']=='0000-00-00')
               $reg_date="";
               else
               $reg_date=$rs_v['confirmation_date'];

               $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>$reg_date</td><td>".@get_staff_name($rs_v['confirmation_entry_by'])."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('confirmation_section','delete','$r_id');\">UNDO</a></td></tr>";
          }//end of while

          $tb.="</table></center>";
          echo $tb;
     }
     else
     echo "<center><b><font color='red'>No record to display</font></b></center>";
     exit;
}

if($id=='display_grievance')
{
     $r_id=@$_REQUEST['r_id'];
     $res_c=@mysqli_query($con, "select issues from hr_grievancetb where id='$r_id'");
     $rs_c=@mysqli_fetch_array($res_c);
     $tb="<form name='frmpro' id='frmpro'><table>
     <center><font color='red'><b>REACTION/REPLY</b></font></center>
     <tr><th>ISSUE</th><td>{$rs_c['issues']}</td></tr>
     <tr><th>COMMENTS</th><td><textarea name='comment' id='comment' cols='30' rows='4'></textarea></td></tr>
     <tr><th colspan='2'><input type='hidden' name='r_id' id='r_id' value='$r_id'/><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_grievance');\" class='btn'/></th></tr>
     <table><div id='process_grievance'></div></form>";
     echo $tb;
     exit;
}

if($id=='process_grievance')
{
     $r_id=@$_REQUEST['r_id'];
     $comment=@$_REQUEST['comment'];
     @mysqli_query($con, "update hr_grievancetb set reaction='".@mysqli_real_escape_string($con, $comment)."',reaction_date=CURDATE() where id='$r_id'");
     echo "<script>alert('Record updated successfully');</script>";
     exit;
}

if($id=='posting_section')

{
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']))
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     $fileno=@$_REQUEST['fileno'];
     $dept=@$_REQUEST['dept'];
     $unit=@$_REQUEST['unit'];
     $post_date=$_REQUEST['post_date'];
     $post_position=$_REQUEST['post_position'];

     if($action=='save')
     {
          $res_c=@mysqli_query($con, "SELECT * FROM tb_name where fileno='$fileno' and disc_date='$disc_date' and disc_type='$disc_type'");
          if(@mysqli_num_rows($res_c)<=0)
          {
               @mysqli_query($con, "INSERT INTO hr_disciplinarytb set fileno='$fileno',disc_date='$disc_date',disc_type='$disc_type', disc_ref_no='$disc_ref_no',description='$description',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               echo "<script>alert('Record saved successfully');</script>";
               $sql="SELECT * FROM hr_disciplinarytb where fileno='$fileno'";
          }

     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM hr_postingtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno']; $ref_doc=$rs_d['ref_doc']; @unlink($ref_doc);
          @mysqli_query($con, "DELETE FROM hr_postingtb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="SELECT * FROM hr_postingtb where fileno='$fileno'";
     }


     if($action=='view')
     {
          $sql="SELECT * FROM hr_postingtb where fileno='$fileno' order by post_date desc";

     } //end of view

     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table align='left' style='border:solid 1px #0000' border='1' cellspacing='0' cellpadding='3' rules='rows' frame='hsides'><tr><th>S/N</th><th>FILE NO</th><th>STAFF NAME</th><th>DEPT. POSTED</th><th>UNIT POSTED</th><th>POSITION</th><th>DATE POSTED</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>".@get_dept_name($rs_v['post_dept'])."</td><td>".@get_unit_name($rs_v['post_dept'],$rs_v['post_unit'])."</td><td>{$rs_v['position']}</td><td>".@date('d/m/Y',strtotime($rs_v['post_date']))."</td><td><a href=\"javascript:swapcontent('posting_section','delete','$id2');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

     exit;

}

if($id=='retirement_section')
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          @mysqli_query($con, "INSERT INTO hr_status_historytb set fileno='$j->fileno',status='$j->status',description='".@mysqli_real_escape_string($con, $j->desc)."',entry_date='$j->date_updated',entry_time=CURTIME(),entry_by='$login_id'");

          //update staff table
          @mysqli_query($con, "update stafftb set status='$j->status' where fileno='$j->fileno'");

          $sql="SELECT * FROM hr_status_historytb where fileno='$j->fileno' order by id desc";

          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='search')
     {

          $sql="SELECT * FROM  hr_status_historytb where fileno='$j->fileno' order by id desc";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM hr_status_historytb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d);
          $fileno=$rs_d['fileno'];

          @mysqli_query($con, "DELETE FROM hr_status_historytb where id='$r_id'");
          @mysqli_query($con, "update stafftb set status='Active' where fileno='$fileno'");

          $sql="SELECT * FROM  hr_status_historytb where fileno='$fileno' order by id desc";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<center><table><tr><th>S/N</th><th>FILENO</th><th>FULLNAME</th><th>STATUS</th><th>DATE UPDATED</th><th>DESCRIPTION</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>".@get_staff_name($rs_v['fileno'])."</td><td>{$rs_v['status']}</td><td>".@date('d/m/Y',strtotime($rs_v['entry_date']))."</td><td>{$rs_v['description']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('retirement_section','delete','$r_id');\">DELETE</a></td></tr>";
          }//end of while

          $tb.="</table></center>";
          echo $tb;
     }
     else
     echo "<center><b><font color='red'>No record to display</font></b></center>";

     exit;
}

if($id=='display_applicant')
{
     $app_year=@$_REQUEST['app_year'];
     $dept_code=@$_REQUEST['dept'];
     $position=@$_REQUEST['position'];

     /////////////////////view section ////////////////////
     $sql="select p.position,p.dept_code,p.unit_code,a.appno from hr_app_positiontb p, hr_applicanttb a where p.appno=a.appno and a.app_year='$app_year' and p.dept_code='$dept_code' and p.position='$position' order by a.appno";
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<center><table><tr><th>S/N</th><th>APP. NUMBER</th><th>FULLNAME</th><th>DEPARTMENT</th><th>POSITION/RANK</th><th>ACTION</th></tr>";
     $g_total=@mysqli_num_rows($res_v);
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $appno=$rs_v['appno'];
               $appno_base=@base64_encode($rs_v['appno']);
               //check if the staff has already been appointed
               $res_c=@mysqli_query($con, "select appno from stafftb where appno='$appno'");
               if(@mysqli_num_rows($res_c)>=1)
               {
                    $row_color='#D3E488';
                    $roll_back=" | <a href=\"javascript:swapcontent('rollback_applicant','$appno');\">ROLLBACK</a>";
               }
               else
               {
                    $row_color=''; $roll_back=" | <a href=\"javascript:swapcontent('display_applicant_process','$appno','$app_year','$dept_code','$position');\">APPOINT</a>";
               }

               $tb.="<tr bgcolor='$row_color'><td>$sn</td><td>{$rs_v['appno']}</td><td>".@get_applicant_name($rs_v['appno'])."</td><td>".@get_dept_name($rs_v['dept_code'])."</td><td>{$rs_v['position']}</td><td><a href='hr_app_cv.php?fileno=$appno_base' target='_blank'>VIEW CV</a> $roll_back</td></tr>";
          }//end of while

          $tb.="</table></center>";
          $tb_head="<b><font color='red'>TOTAL NUMBER OF APPLICANT(S):</font> <font color='#2AA100'>$g_total</font></b>";
          echo $tb_head.$tb;
     }
     else
     echo "<center><b><font color='red'>No record to display</font></b></center>";

     exit;
}

if($id=='display_applicant_process')
{
     $appno=@$_REQUEST['appno'];
     $app_year=@$_REQUEST['app_year'];
     $dept_code=@$_REQUEST['dept_code'];
     $position=@$_REQUEST['position'];

     $tb="<form name='frmpro' id='frmpro'><table>
     <tr><th colspan='4'>APPLICATION DETAILS</th></tr>
     <tr><th>Application No</th><td colspan='3'>$appno</td></tr>
     <tr><th>Fullname</th><td>". @get_applicant_name($appno)."</td><th>Application Year</th><td>$app_year</td></tr>
     <tr><th>Department</th><td>". @get_dept_name($dept_code)."<input type='hidden' name='prev_dept_code' id='prev_dept_code' value='$dept_code'/><input type='hidden' name='prev_appno' id='prev_appno' value='$appno'/><input type='hidden' name='prev_position' id='prev_position' value='$position'/></td><th>Position/Rank</th><td>$position</td></tr>

     <tr><th colspan='4'>APPOINTMENT DETAILS</th></tr>
     <tr><th>Staff Status</th><td><select name='staff_status' id='staff_status' class='txt' onChange=\"swapcontent('generate_staff_number',this.value);\">

     <option selected='selected'>---</option>

     <option value='Junior'>Junior</option>

     <option value='Senior'>Senior</option>

     </select></td><th>Staff Number</th><td><span id='generate_staff_number'><input type='text' name='fileno' id='fileno' value=''/></span></td></tr>

     <tr><th>Appointed Position/Rank</th><td><select name='rank_appointed' id='rank_appointed'>
     <option selected='selected' value=''>---</option>";

     $res_c=@mysqli_query($con, "SELECT * FROM hr_positiontb order by category,position");
     while($rs_c=@mysqli_fetch_array($res_c))
     {
          $post=@$rs_c['position'];
          $tb.="<option value='$post'>$post</option>";
     }

     $tb.="</select></td><th>Eployment Status</th><td><select name='employment_status' id='employment_status' class='txt'>

     <option selected='selected'>---</option>

     <option value='Permanent'>Permanent</option>

     <option value='Temporary'>Temporary</option>

     <option value='Contract'>Contract</option>

     <option value='Transfer'>Transfer</option>

     </select></td></tr>

     <tr><th>Appointed Level</th><td><select name='level' id='level'>
     <option selected='selected' value=''>---</option>";

     $res_c=@mysqli_query($con, "SELECT * FROM level_categorytb order by convert(level,decimal)");

     while($rs_c=@mysqli_fetch_array($res_c))

     {

          $level=@$rs_c['level'];

          $tb.="<option value='$level'>$level</option>";

     }

     $tb.="</select></td><th>Appointed Step</th><td><select name='step' id='step'>
     <option selected='selected' value=''>---</option>";

     $res_c=@mysqli_query($con, "SELECT * FROM steptb order by convert(step,decimal)");

     while($rs_c=@mysqli_fetch_array($res_c))

     {

          $step=@$rs_c['step'];

          $tb.="<option value='$step'>$step</option>";

     }
     $tb.="</select></td></tr>

     <tr><th>Salary</th><td colspan='3'><input type='text' name='salary' id='salary' class='txt' size='60'/></td></tr>

     <tr><th colspan='4'><input type='button' name='cmdpro' id='cmdpro' value='Apply' onclick=\"swapcontent('applicant_appointment_approval');\" class='btn'/> <input type='button' name='cmdclode' id='cmdclose' value='Close' onclick=\"TINY.box.hide();\" class='btn'/></th></tr>
     <table><div id='applicant_appointment_approval'></div></form>";
     echo $tb;
     exit;
}

if($id=='generate_staff_number')
{
     $staff_status=@$_REQUEST['staff_status'];
     $fileno=@generate_staff_number($staff_status);
     echo "<input type='text' name='fileno' id='fileno' value='$fileno'/>";
     exit;
}

if($id=='applicant_appointment_approval')
{
     $prev_dept_code=@$_REQUEST['prev_dept_code']; $prev_appno=@$_REQUEST['prev_appno']; $prev_position=@$_REQUEST['prev_position'];
     $staff_status=@$_REQUEST['staff_status']; $fileno=@$_REQUEST['fileno']; $rank_appointed=@$_REQUEST['rank_appointed'];
     $employment_status=@$_REQUEST['employment_status']; $level=@$_REQUEST['level']; $step=@$_REQUEST['step']; $salary=@$_REQUEST['salary'];
     $login_id=@$_SESSION['login_id'];
     //echo "$prev_dept_code $prev_appno $prev_position $staff_status $fileno $rank_appointed $employment_status $level $step $salary";
     // Load applicant data from applicant tables to staff tables
     @mysqli_query($con, "INSERT INTO stafftb (`appno`, `fileno`, `title`, `surname`, `first_name`, `other_name`, `maiden_name`, `sex`, `marital_status`, `religion`, `spouse_name`, `spouse_address`, `spouse_occupation`, `qualification`, `date_of_birth`, `nationality`, `state_id`, `lga_id`, `country`, `place_of_birth`, `senatorial_district`, `contact_address`, `residential_address`, `permanent_address`, `email`, `phone_no`, `next_name`, `next_address`, `next_email`, `next_phone_no`, `acct_no`, `bank_name`, `next_relationship`, `guidance_name`, `guidance_state`, `guidance_nationality`, `guidance_occupation`, `guidance_address`, `guidance_email`, `guidance_phone_no`, `mother_name`, `mother_state`, `mother_nationality`, `mother_address`, `last_place_of_residence`, `passport_number`, `passport_place`, `passport_date_issue`, `languages_spoken`, `disability`, `disability_reason`, `court_case`, `hobbies`, `security_forces`, `force_no`, `highest_force_rank`, `force_period`, `force_character`, `status`, `app_year`, `password`) select  `appno`, `appno`, `title`, `surname`, `first_name`, `other_name`, `maiden_name`, `sex`, `marital_status`, `religion`, `spouse_name`, `spouse_address`, `spouse_occupation`, `qualification`, `date_of_birth`, `nationality`, `state_id`, `lga_id`, `country`, `place_of_birth`, `senatorial_district`, `contact_address`, `residential_address`, `permanent_address`, `email`, `phone_no`, `next_name`, `next_address`, `next_email`, `next_phone_no`, `acct_no`, `bank_name`, `next_relationship`, `guidance_name`, `guidance_state`, `guidance_nationality`, `guidance_occupation`, `guidance_address`, `guidance_email`, `guidance_phone_no`, `mother_name`, `mother_state`, `mother_nationality`, `mother_address`, `last_place_of_residence`, `passport_number`, `passport_place`, `passport_date_issue`, `languages_spoken`, `disability`, `disability_reason`, `court_case`, `hobbies`, `security_forces`, `force_no`, `highest_force_rank`, `force_period`, `force_character`, `status`, `app_year`, `password` from hr_applicanttb where appno='$prev_appno'");

     $staff_category=@get_position_category($rank_appointed);
     $dept_str=@explode('***',@get_position_dept($prev_appno,$prev_dept_code,$prev_position));
     $staff_dept_code=$dept_str[0]; $staff_unit_code=$dept_str[1];

     @mysqli_query($con, "update stafftb set staff_status='$staff_status',category='$staff_category',level='$level',step='$step',initial_level='$level',initial_step='$step',initial_salary='$salary',rank='$rank_appointed',post_of_1st_appt='$rank_appointed',present_salary='$salary',employment_status='$employment_status',dept_code='$staff_dept_code',unit_code='$staff_unit_code',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where appno='$prev_appno'");

     //INSERT INTO other application tables
     mysqli_query($con, "INSERT INTO hr_staff_academic_edutb (`fileno`, `school_name`, `school_type`, `qualification`, `degree_class`, `from_month`, `from_year`, `to_month`, `to_year`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `school_name`, `school_type`, `qualification`, `degree_class`, `from_month`, `from_year`, `to_month`, `to_year`, `entry_date`, `entry_time`, `entry_by` from hr_app_academic_edutb where appno='$prev_appno'") or die( mysqli_error($con));

     @mysqli_query($con, "INSERT INTO hr_staff_childtb (`fileno`, `name`, `date_of_birth`, `sex`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `name`, `date_of_birth`, `sex`, `entry_date`, `entry_time`, `entry_by` from hr_app_childtb where appno='$prev_appno'");

     mysqli_query($con, "INSERT INTO hr_staff_country_visitedtb (`fileno`, `country`, `visit_reason`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `country`, `visit_reason`, `entry_date`, `entry_time`, `entry_by` from hr_app_country_visitedtb where appno='$prev_appno'") or die( mysqli_error($con));

     @mysqli_query($con, "INSERT INTO hr_staff_employmenttb (`fileno`, `employer_name`, `location`, `rank`, `salary`, `from_year`, `to_year`, `leaving_reason`, `employment_type`, `status`, `duty`, `bond_question`, `release_question`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `employer_name`, `location`, `rank`, `salary`, `from_year`, `to_year`, `leaving_reason`, `employment_type`, `status`, `duty`, `bond_question`, `release_question`, `entry_date`, `entry_time`, `entry_by` from hr_app_employmenttb where appno='$prev_appno'");

     mysqli_query($con, "INSERT INTO hr_staff_prof_membershiptb (`fileno`, `name`, `category`, `year_honoured`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `name`, `category`, `year_honoured`, `entry_date`, `entry_time`, `entry_by` from hr_app_prof_membershiptb where appno='$prev_appno'") or die( mysqli_error($con));

     mysqli_query($con, "INSERT INTO hr_staff_prof_qualificationtb (`fileno`, `name`, `grade`, `from_year`, `to_year`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `name`, `grade`, `from_year`, `to_year`, `entry_date`, `entry_time`, `entry_by` from hr_app_prof_qualificationtb where appno='$prev_appno'") or die( mysqli_error($con));

     @mysqli_query($con, "INSERT INTO hr_staff_publicationtb (`fileno`, `title`, `author`, `type`, `publisher`, `journal`, `year_published`, `status`, `category`, `page_no`, `volume`, `issue`, `url`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `title`, `author`, `type`, `publisher`, `journal`, `year_published`, `status`, `category`, `page_no`, `volume`, `issue`, `url`, `entry_date`, `entry_time`, `entry_by` from hr_app_publicationtb where appno='$prev_appno'");

     mysqli_query($con, "INSERT INTO hr_staff_recognitiontb (`fileno`, `award_type`, `award_date`, `award_description`, `prize`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `award_type`, `award_date`, `award_description`, `prize`, `entry_date`, `entry_time`, `entry_by` from hr_app_recognitiontb where appno='$prev_appno'") or die( mysqli_error($con));

     mysqli_query($con, "INSERT INTO hr_staff_refereetb (`fileno`, `ref_name`, `ref_address`, `ref_occupation`, `ref_know_period`, `ref_email`, `ref_phone_no`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `ref_name`, `ref_address`, `ref_occupation`, `ref_know_period`, `ref_email`, `ref_phone_no`, `entry_date`, `entry_time`, `entry_by` from hr_app_refereetb where appno='$prev_appno'") or die( mysqli_error($con));

     mysqli_query($con, "INSERT INTO hr_staff_researchtb (`fileno`, `topic`, `status`, `funding_source`, `project_value`, `start_date`, `end_date`, `amount_granted`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `topic`, `status`, `funding_source`, `project_value`, `start_date`, `end_date`, `amount_granted`, `entry_date`, `entry_time`, `entry_by` from hr_app_researchtb where appno='$prev_appno'") or die( mysqli_error($con));

     mysqli_query($con, "INSERT INTO hr_staff_servicetb (`fileno`, `service_type`, `service_place`, `service_details`, `from_year`, `to_year`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `service_type`, `service_place`, `service_details`, `from_year`, `to_year`, `entry_date`, `entry_time`, `entry_by` from hr_app_servicetb where appno='$prev_appno'") or die( mysqli_error($con));

     @mysqli_query($con, "INSERT INTO hr_staff_training_apptb (`fileno`, `training_type`, `start_date`, `end_date`, `training_title`, `theme`, `location`, `venue`, `no_paper_read`, `sponsor`, `amount_granted`, `ref_doc`, `approval_status`, `approval_date`, `process_by`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `training_type`, `start_date`, `end_date`, `training_title`, `theme`, `location`, `venue`, `no_paper_read`, `sponsor`, `amount_granted`, `ref_doc`, `approval_status`, `approval_date`, `process_by`, `entry_date`, `entry_time`, `entry_by` from hr_app_training_apptb where appno='$prev_appno'");

     //update other tables with new fileno
     @mysqli_query($con, "update stafftb set fileno='$fileno' where fileno='$prev_appno'");

     @mysqli_query($con, "update hr_staff_academic_edutb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_childtb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_country_visitedtb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_employmenttb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_prof_membershiptb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_prof_qualificationtb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_publicationtb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_recognitiontb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_refereetb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_researchtb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_servicetb set fileno='$fileno' where fileno='$prev_appno'");
     @mysqli_query($con, "update hr_staff_training_apptb set fileno='$fileno' where fileno='$prev_appno'");

     @logs($login_id,"Approve appointment","$login_id approved staff appointment for staff $fileno");
     //////////////// update passport picture and signature ////////////////////

     /////////////// end of update passport/signature  ////////////////////////
     echo "<script>alert('Operation is successful');swapcontent('display_applicant');TINY.box.hide();</script>";

     exit;
}

if($id=='rollback_applicant')
{
     $appno=@$_REQUEST['appno'];
     $res_s=@mysqli_query($con, "select appno,fileno from stafftb where appno='$appno'");
     $rs_s=@mysqli_fetch_array($res_s);
     $fileno=@$rs_s['fileno'];
     //echo "APP NO: $appno FILENO: $fileno";

     ///rollback data
     @mysqli_query($con, "DELETE FROM stafftb where fileno='$fileno'");

     @mysqli_query($con, "DELETE FROM hr_staff_academic_edutb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_childtb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_country_visitedtb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_employmenttb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_prof_membershiptb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_prof_qualificationtb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_publicationtb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_recognitiontb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_refereetb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_researchtb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_servicetb where fileno='$fileno'");
     @mysqli_query($con, "DELETE FROM hr_staff_training_apptb where fileno='$fileno'");

     @logs($login_id,"Appointment Rollback","$login_id rollback appointment for staff $fileno");

     echo "<script>alert('Rollback operation is successful');swapcontent('display_applicant');</script>";

     exit;
}

if($id=='load_staff_assumption')
{
     $fileno=$_REQUEST['fileno'];
     $res_s=@mysqli_query($con, "SELECT * FROM stafftb where fileno='$fileno'");
     $rs_s=@mysqli_fetch_array($res_s);
     if(@mysqli_num_rows($res_s)>=1)
     {
          //get the staff data
          $tb="<center><table><tr><th colspan='4'>APPOINTMENT DETAILS</th></tr>
          <tr><th>Staff Number</th><td>{$rs_s['fileno']}</td><th>Fullname</th><td>".@get_staff_name($rs_s['fileno'])."</td></tr>
          <tr><th>Staff Status</th><td>{$rs_s['staff_status']}</td><th>Staff Category</th><td>{$rs_s['category']}</td></tr>

          <tr><th>Appointed Position/Rank</th><td>{$rs_s['rank']}</td><th>Employment Status</th><td>{$rs_s['employment_status']}</td></tr>

          <tr><th>Appointed Level</th><td>{$rs_s['level']}</td><th>Appointed Step</th><td>{$rs_s['step']}</td></tr>

          <tr><th>Salary</th><td colspan='3'>{$rs_s['present_salary']}</td></tr>
          <table></center>";

          echo $tb;

     }
     else
     echo "<center><b><font color='red'>Staff File Number does not exist</font></b></center>";

     exit;

}

if($id=='assumption_section')
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          @mysqli_query($con, "update stafftb set date_of_1st_appt='$j->date_assume',date_of_present_appt='$j->date_assume',date_of_assumption='$j->date_assume',acct_no='$j->acct_no',bank_name='$j->bank_name' where fileno='$j->fileno'");

          @logs($login_id,"Update staff assumption","$login_id update staff assumption for staff $j->fileno");

          $sql="SELECT * FROM stafftb where fileno='$j->fileno'";

          echo "<script>alert('Staff record updated successfully');</script>";
     }

     if($action=='search')
     {

          $sql="SELECT * FROM stafftb where fileno='$j->fileno'";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "SELECT * FROM stafftb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d);
          $fileno=$rs_d['fileno'];

          @logs($login_id,"Delete staff assumption","$login_id delete staff assumption for staff $fileno");

          @mysqli_query($con, "update stafftb set date_of_1st_appt='0000-00-00',date_of_present_appt='0000-00-00',date_of_assumption='0000-00-00',acct_no='',bank_name='' where fileno='$fileno'");


          $sql="SELECT * FROM stafftb where fileno='$fileno'";
          echo "<script>alert('Record successfully undo');</script>";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<center><table><tr><th>S/N</th><th>FILE NO</th><th>DATE OF ASSUMPTION</th><th>BANK NAME</th><th>ACCOUNT NUMBER</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];

               if($rs_v['date_of_assumption']=='0000-00-00')
               $reg_date="";
               else
               $reg_date=$rs_v['date_of_assumption'];

               $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>$reg_date</td><td>{$rs_v['bank_name']}</td><td>{$rs_v['acct_no']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('assumption_section','delete','$r_id');\">UNDO</a></td></tr>";
          }//end of while

          $tb.="</table></center>";
          echo $tb;
     }
     else
     echo "<center><b><font color='red'>No record to display</font></b></center>";

     exit;

}
//////////////////////////////////End of human resources management system /////////////////////////////////////////


if($id == 'set_amt_div'){
     $action=$_REQUEST['action'];
     $folio=$_REQUEST['folio'];
     /*echo "<script>alert($action);</script>";*/
     if($action=='newdefinition'){
          echo '<input name="amount" type="text" id="amount" size="25" value="" style="border:0; width:168px; height:20px" />
          <input type="hidden" id="d_amount" name="d_amount" value="" />';
     }elseif($action=='predefine'){
          if($folio == '') echo '';
          else{
               $val=explode("***", $folio);
               /*echo "<script>alert($val[1]);</script>";*/
               $amount=$bursary->get_any_value("value", "allowancestb", "id", $val[1]);
               echo '<input name="amount" type="hidden" id="amount" value="'.$amount.'" /><strong>&#8358;'.$amount.'</strong>
               <input type="hidden" id="d_amount" name="d_amount" value="'.$amount.'" />';
          }
     }

     exit;
}
if($id=="filter_allowances"){
     $fileno=$_REQUEST['fileno'];
     //$cat = $bursary->get_any_value("category", "stafftb", "fileno", $fileno) ;
     $staff_cat = $bursary->get_any_value("category", "stafftb", "fileno", $fileno) ;
     $level = "Level ".$bursary->get_any_value("level", "stafftb", "fileno", $fileno) ;
     ///$pos = $bursary->get_any_value("position", "stafftb", "fileno", $fileno) ; NEED MORE CLEARIFICATION!
     //$step=$bursary->get_any_value("step", "stafftb", "fileno", $fileno) ;
     $scale = $bursary->get_any_value("salary_scale", "stafftb", "fileno", $fileno) ;
     $rank = $bursary->get_any_value("rank", "stafftb", "fileno", $fileno) ;

     echo '<select name="efolio" id="efolio" class="txt" style="width:450px" onChange="swapcontent(\'set_amt_div\', $(\'#efolio\').val(), \'predefine\');">
     <option selected="selected" value="">---</option>';
     //$res_c=@mysqli_query($con, "SELECT * FROM salary_codetb where category='ALLOWANCE' and status='Active' order by account_code");
     $res_c=@mysqli_query($con, "SELECT * FROM allowancestb order by id");
     while($rs_c=@mysqli_fetch_array($res_c))
     {
          $al_id=@$rs_c['id'];
          $d_val=explode("***", $bursary->get_allowance_defined($al_id) );
          $code=@$rs_c['folio_code'];	$fval=@$rs_c['field_value'];
          $name=@get_folio_name($rs_c['folio_code']);
          $def_for = $d_val[1];
          $deff1 = explode("(", $d_val[1]); $def_f1=trim($deff1[0]); 	//category for which allowance is define for in allowancestb
          $deff2 = explode(")", $deff1[1]); $def_f2=trim($deff2[0]); 	//get category of definition from return value

          $def_amount = $d_val[4];
          if($def_f2==$staff_cat and ($def_f1 == $level or $def_f1 == $scale or $def_f1 == $rank))
          echo "<option value='$code***$al_id'>$code | $name | For:".$def_for.".$def_f1.$def_f2 | Amount:".$def_amount."</option>";
     }
     echo '</select>';

     exit;
}

if($id=="get_cumulative_per_item"){
     $fileno=$_REQUEST['fileno'];
     $folio = $_REQUEST['folio_code'];
     echo $bursary->get_total_pay_per_item($fileno, $folio);
}
?>
