<?php
@session_start();
@ini_set('max_execution_time', 60000000000);
@ini_set("memory_limit", "51200M");
@require_once('connect.php');
@require_once('function.php');
@require_once('gencode.php');

@require_once('class/mysqli_class.php');
$db = new Database();
$db->connect();
@require_once "myclass_m.php";
@$bursary = new myclass_m();
$id=@$_REQUEST['contentvar'];
$contentvar=$_REQUEST['contentvar'];

////echo "yes";
function smsalert($msg,$phoneno){
     $msg=@rawurlencode($msg);
     $phoneno="+234".@substr($phoneno,-10);
     $sender=@rawurlencode('UNILORIN');
     $r=@file_get_contents("http://api.smartsmssolutions.com/smsapi.php?username=jmklaru&password=0712764&sender=$sender&recipient=$phoneno&message=$msg");
}

///////////////////####################### GENERAL PARAMETER SECTION ////////////////////////////////////////////////
//get the current post jamb session, data and money
$res_jamb=@mysqli_query($con, "select * from settingstb where parameter='post_utme_payment'");
$rs_jamb=@mysqli_fetch_array($res_jamb);
$putme_payment_type=@$rs_jamb['parameter'];
$putme_payment_desc=@$rs_jamb['parameter_desc'];
$putme_payment_item_id=@$rs_jamb['pay_item_id'];
$putme_amount=@$rs_jamb['amount'];
$putme_other_charges=@$rs_jamb['other_charges'];
$putme_session=@$rs_jamb['session'];
$putme_start_date=@$rs_jamb['start_date'];
$putme_end_date=@$rs_jamb['end_date'];
$_SESSION['putme_pay_item_id']=$putme_payment_item_id;
///////////////////####################### END OF GENERAL PARAMETER SECTION ////////////////////////////////////////////////
if($id=='getnextmemo')
{
     //get next memo count for ID
     $r= mysqli_query($con, "select * from memo_movementtb where dept_unit='". mysqli_real_escape_string($con, $_REQUEST['dept_unit'])."' and memo_status='IN'");
     //$r= mysqli_query($con, "select * from memotb ");
     $cnt =  mysqli_num_rows($r) + 1;
     $dpt=substr(strtoupper($_REQUEST['dept_txt']),0,3);
     $opText=$dpt."/".str_pad($cnt,3,'0',STR_PAD_LEFT);
     echo $opText.'
     <input type="hidden" name="memo_id" id="memo_id" value="'.$opText.'">';
}

if($id=='outgoing_mail')
{
     //$r= mysqli_query($con, "select m.memo_id, m.memo_from, m.description, m.amount, m.memo_status, mm.memo_status as move_status from memotb m inner join memo_movementtb mm on m.memo_id=mm.memo_id where mm.memo_status='OUT'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
     $r= mysqli_query($con, "select * from memotb"); //ADD CONDITION TO FILTER 		$json_response=array();
     while ($row =  mysqli_fetch_array($r)) {
          $row_array['memo_id'] = $row['memo_id'];
          $row_array['memo_from'] = $row['memo_from'];
          $row_array['description'] = $row['description'];
          $row_array['amount'] = $row['amount'];
          $row_array['datein'] = $row['datein'];
          $row_array['memo_status'] = $row['memo_status'];
          array_push($json_response,$row_array);
     }//end of while

     echo json_encode($json_response);
}

if($id=='incoming_mail')
{

     $r= mysqli_query($con, "select m.memo_id, m.memo_from, m.description, m.amount, m.memo_status, mm.memo_status as move_status from memotb m inner join memo_movementtb mm on m.memo_id=mm.memo_id where mm.memo_status='IN'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
     $json_response=array();
     while ($row =  mysqli_fetch_array($r)) {
          $row_array['memo_id'] = $row['memo_id'];
          $row_array['memo_from'] = $row['memo_from'];
          $row_array['description'] = $row['description'];
          $row_array['amount'] = $row['amount'];
          $row_array['datein'] = $row['datein'];
          $row_array['memo_status'] = $row['memo_status'];
          array_push($json_response,$row_array);
     }//end of while

     echo json_encode($json_response);
}


if($id=='mailsearch')
{
     //SEARCH WITH FILTER
     /*$index = $_REQUEST['tabindex'];
     echo "<script>
     $('#tt').tabs('select', $index);</script>";

     $rs =  mysqli_query($con, 'select * from memotb'); //REMEMBER TO ADD FILTER BY DEPT_UNIT OF THE LOGIN USER.
     $result = array();
     while($row =  mysqli_fetch_object($rs)){
     array_push($result, $row);
}

echo json_encode($result);*/

$r= mysqli_query($con, "select * from memotb"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
$json_response=array();
while ($row =  mysqli_fetch_array($r)) {
     $row_array['memo_id'] = $row['memo_id'];
     $row_array['memo_from'] = $row['memo_from'];
     $row_array['description'] = $row['description'];
     $row_array['amount'] = $row['amount'];
     $row_array['datein'] = $row['datein'];
     $row_array['memo_status'] = $row['memo_status'];
     array_push($json_response,$row_array);


}//end of while

echo json_encode($json_response);

}
if($id=='memo_withsub')
{
     $r= mysqli_query($con, "select * from memotb"); //ADD DEPARTMENT FILTER
     $json_response=array();
     while ($row =  mysqli_fetch_array($r)) {
          $row_array['memo_id'] = $row['memo_id'];
          $row_array['memo_from'] = $row['memo_from'];
          $row_array['description'] = $row['description'];
          $row_array['amount'] = $row['amount'];
          $row_array['datein'] = $row['datein'];
          $row_array['memo_status'] = $row['memo_status'];
          array_push($json_response,$row_array);
     }//end of while

     echo json_encode($json_response);
}

if($id=='memo_sub')
{
     $itemid =  mysqli_real_escape_string($con, $_REQUEST['memo_id']);
     $rs =  mysqli_query($con, "select * from memo_movementtb where memo_id='$itemid'");
     $items = array();
     while($row =  mysqli_fetch_object($rs)){
          array_push($items, $row);
     }
     echo json_encode($items);

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

}  //end of login

if($id=='forget_password')
{

     echo "<form name='frmlogin' id='frmlogin'>
     <input type='text' name='uname' id='uname' placeholder='Login ID' size='35' class='txt'><br/>
     <input type='text' name='email' id='email' placeholder='Type email address here' class='txt' size='23' onkeydown=\"if (event.keyCode == 13) swapcontent('pass_recovery_update',$('#uname').val(),$('#email').val());\"><input type='button' value='Recover' class='btn' onclick=\"swapcontent('pass_recovery_update',$('#uname').val(),$('#email').val());\"><br/>
     <div id='pass_recovery_update'></div>
     </form>";

}  //end of login

if($id=='app_main_login')
{
     $login_id=@mysqli_real_escape_string($con, @$_REQUEST['username']); //application number
     $password=@mysqli_real_escape_string($con, @$_REQUEST['password']); //surname

     $res_acad=@mysqli_query($con, "select distinct * from current_settingstb s1, semestertb s2 where s1.semester = s2.semester");
     $rs_acad=@mysqli_fetch_array($res_acad);
     if(@mysqli_num_rows($res_acad)>=1) { $_SESSION['cur_session']=$rs_acad['session']; $_SESSION['cur_semester']=$rs_acad['semester']; $_SESSION['cur_semester_desc']=$rs_acad['semester_desc']; }

     $res_l=@mysqli_query($con, "select * from candidatetb where regno='$login_id' and surname='$password'");
     $rs_l=@mysqli_fetch_array($res_l);
     if(@mysqli_num_rows($res_l)>=1)
     {
          $login_status="Active";
          $_SESSION['title']=@$rs_l['title'];
          $_SESSION['surname']=@$rs_l['surname'];
          $_SESSION['first_name']=@$rs_l['first_name']; $_SESSION['other_name']=@$rs_l['other_name'];
          $_SESSION['last_login_date']=@$rs_l['last_login_date'];$_SESSION['last_login_time']=@$rs_l['last_login_time'];
          $_SESSION['login_status']='applicant'; $_SESSION['role']='Applicant'; $_SESSION['userLogin']='ok';

          $_SESSION['login_id']=$login_id;
          $log_date=date('Y-m-d');$log_time=date('h:i:s a');$log_date2=date('l, F d, Y');
          @mysqli_query($con, "insert into portal_logstb set regno='$login_id',log_type='Portal Login',log_desc='$login_id Login',log_date='$log_date',log_date_desc='$log_date2',log_time='$log_time',entry_by='$login_id'");

          $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
          echo "<script>location='app_main.php';</script>";exit;

     }
     else
     {
          echo "<br/><font color='red'><b>Applicant details does not exist.</b></font>";exit;
     }
}

if($id=='main_login')
{
     $login_id=@mysqli_real_escape_string($con, @$_REQUEST['username']);
     $password=@mysqli_real_escape_string($con, @$_REQUEST['password']);
     $pass_base=@base64_encode($password);


     //////////////////**************************** Login Section for Staff ******///////////////////////////
     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     //$res_l=@mysqli_query($con, "select s.title,s.surname,s.first_name,s.other_name,s.status,s.category from stafftb s,users_roletb r where s.fileno=r.fileno and s.fileno='$login_id' and s.password='$pass_base'");
     $res_l=@mysqli_query($con, "select s.title,s.surname,s.first_name,s.other_name,s.status,s.category from stafftb s where s.fileno='$login_id' and s.password='$pass_base'");
     $rs_l=@mysqli_fetch_array($res_l);
     if(@mysqli_num_rows($res_l)>=1)
     {
          $login_status=@$rs_l['status'];
          if($login_status=='Active')
          {
               //////project title fetching
               $res_p=@mysqli_query($con, "select * from project_titletb where status='Active'");
               $rs_p=@mysqli_fetch_array($res_p);
               $title=@$rs_p['title'];
               $_SESSION['project_title']=$title;

               //
               $_SESSION['title']=@$rs_l['title'];
               $_SESSION['surname']=@$rs_l['surname'];
               $_SESSION['first_name']=@$rs_l['first_name']; $_SESSION['other_name']=@$rs_l['other_name'];
               $_SESSION['last_login_date']=@$rs_l['last_login_date'];$_SESSION['last_login_time']=@$rs_l['last_login_time'];
               $_SESSION['staff_category']=@$rs_l['category'];



               $_SESSION['login_status']='staff'; $_SESSION['role']='Personal'; $_SESSION['userLogin']='ok';

               $_SESSION['login_id']=$login_id;
               $log_date=date('Y-m-d');$log_time=date('h:i:s a');$log_date2=date('l, F d, Y');
               @mysqli_query($con, "insert into portal_logstb set regno='$login_id',log_type='Portal Login',log_desc='$login_id Login',log_date='$log_date',log_date_desc='$log_date2',log_time='$log_time',entry_by='$login_id'");

               $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
               //@mysqli_query($con, "update stafftb set last_login_date='$log_date',last_login_time='$log_time',online_status='On' where fileno='$login_id' limit 1");
               //@mysqli_query($con, "update stafftb set online_status='On' where fileno='$login_id' limit 1");
               echo "<script>location='main.php';</script>";exit;

          } //end of active staff
          else
          {
               echo "<br/><div class='error_msg'>You are not an active user.</div>";exit;
          }

          //echo "Fac: $fac_name Dept: $dept_name status: $login_status";
     } //end of staff found

     /////////////////////************************************ Login Section for Staff **********////////////////////////
     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     //////////////////**************************** Login Section for Applicants ******///////////////////////////
     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

     $res_l=@mysqli_query($con, "select s.title,s.surname,s.first_name,s.other_name,status from hr_applicanttb s where s.appno='$login_id' and s.password='$pass_base'");
     $rs_l=@mysqli_fetch_array($res_l);
     if(@mysqli_num_rows($res_l)>=1)
     {
          $login_status=@$rs_l['status'];
          if($login_status=='Active')
          {
               //////project title fetching
               $res_p=@mysqli_query($con, "select * from project_titletb where status='Active'");
               $rs_p=@mysqli_fetch_array($res_p);
               $title=@$rs_p['title'];
               $_SESSION['project_title']=$title;

               //
               $_SESSION['title']=@$rs_l['title'];
               $_SESSION['surname']=@$rs_l['surname'];
               $_SESSION['first_name']=@$rs_l['first_name']; $_SESSION['other_name']=@$rs_l['other_name'];
               $_SESSION['last_login_date']=@$rs_l['last_login_date'];$_SESSION['last_login_time']=@$rs_l['last_login_time'];


               $_SESSION['login_status']='applicant'; $_SESSION['role']='Applicant'; $_SESSION['userLogin']='ok';

               $_SESSION['login_id']=$login_id;
               $log_date=date('Y-m-d');$log_time=date('h:i:s a');$log_date2=date('l, F d, Y');
               @logs($login_id,"Login","$login_id Login at $log_date2");

               //$log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
               //@mysqli_query($con, "update stafftb set last_login_date='$log_date',last_login_time='$log_time',online_status='On' where fileno='$login_id' limit 1");
               //@mysqli_query($con, "update stafftb set online_status='On' where fileno='$login_id' limit 1");
               echo "<script>location='main.php';</script>";exit;

          } //end of active staff
          else
          {
               echo "<br/><div class='error_msg'>You are not an active user.</div>";exit;
          }

          //echo "Fac: $fac_name Dept: $dept_name status: $login_status";
     } //end of staff found

     /////////////////////************************************ Login Section for Applicant **********////////////////////////
     /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


     //after every login attempt
     echo "<script>alert('Invalid login parameters');</script>";
     ////echo "<div class='error_msg'>Invalid login parameters</div>";
} //end of main login for staff and student

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
          $res_c=@mysqli_query($con, "select * from stafftb where fileno='$login_id' and password='$oldpwd'");
          if( mysqli_num_rows($res_c)<=0)
          {
               echo "<font color='red'><b>Invalid old password. Please verify and try again.</b></font>"; exit;
          } //end of check for old pwd

          @mysqli_query($con, "update stafftb set password='$newpwd' where fileno='$login_id' limit 1");
          $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
          logs("$login_id","Change password","$login_id changed password");
          @session_unset(); @session_destroy();
          //header("location :$ref");

          //@mysqli_query($con, "insert into portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Change password','Password change by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
          // echo "<font color='red'><b>Password has been changed successfully. Please Logout to confirm</b></font>";
          echo "<script>alert('Password has been changed successfully. Please re-login to confirm');location='index.php';</script>";


     } //end of staff password mgt

     if($login_status=='student')
     {
          //check before comitting
          $res_c=@mysqli_query($con, "select * from studenttb where regno='$login_id' and password='$old'");  //student pawd is not encoded
          if( mysqli_num_rows($res_c)<=0)
          {
               echo "<font color='red'><b>Invalid old password. Please verify and try again.</b></font>"; exit;
          } //end of check for old pwd

          @mysqli_query($con, "update studenttb set password='$ref' where regno='$login_id' limit 1");
          $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
          @mysqli_query($con, "insert into portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Change password','Password change by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
          // echo "<font color='red'><b>Password has been changed successfully. Please Logout to confirm</b></font>";
          echo "<script>alert('Password has been changed successfully. Please re-login to confirm');swapcontent('logout','index.php');</script>";


     } //end of student management

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
          @mysqli_query($con, "insert into portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Portal Logout','Logout by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
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
          $res_c=@mysqli_query($con, "select * from countrytb where country!='Nigeria' order by country");
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
          $res_c=@mysqli_query($con, "select * from statetb order by state_name");
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
} //end of nationality
if($id=='lgadiv')
{
     $val=@$_REQUEST['val'];
     $res_l=@mysqli_query($con, "select * from lgatb where state_id='$val' order by lga_name");
     echo "<select name='lga' id='lga'><option selected value=''>---</option>";
     while($rs_l=@mysqli_fetch_array($res_l))
     {
          $lga_id=@$rs_l['lga_id'];
          $lga_name=@$rs_l['lga_name'];
          echo "<option value='$lga_id'>$lga_name</option>";
     }
     echo "</select>";

} //end of fetch state

if($id=='load_staff')
{
     $val_str=@explode("***",@$_REQUEST['val']); $dept_id=trim($val_str[0]); $fact_id=trim($val_str[1]);
     $option=@$_REQUEST['option'];
     if($option=='dept') //load staff using department only
     {
          //if($dept_id=='80' or $dept_id=='70' or $dept_id=='40' or $dept_id=='131')
          $res_s=@mysqli_query($con, "select * from stafftb where staff_category='Academic' order by surname,fileno");
          //else
          //$res_s=@mysqli_query($con, "select * from stafftb where dept_id='$dept_id' order by surname,fileno");
     }//end of load staff for course allocation
     else
     $res_s=@mysqli_query($con, "select * from stafftb where fact_id='$fact_id' order by surname,fileno");
     echo "<select id='staff' name='staff'>";
     echo "<option selected value=''>---</option>";
     while($rs_s=@mysqli_fetch_array($res_s))
     {
          $fileno=@$rs_s['fileno'];
          $fullname=@strtoupper(@$rs_s['surname'])." ".@$rs_s['first_name']." ".@$rs_s['other_name']."(".@$rs_s['title'].") - ".@$rs_s['fileno'];
          echo "<option value='$fileno'>$fullname</option>";
     } //end of load staff
     echo "</select>";
}//end of load_staff

if($id=='password_recovery')
{
     echo "<form name='passform' id='passform'><table style='margin-left:30px;border-radius:15px;'>
     <tr><th>Login ID:</th><td><input type='text' id='uname' name='uname' class='easyui-validatebox' data-options='required:true' placeholder='Login ID'/></td></tr>
     <tr><th>Email Address:</th><td><input type='text' id='email' name='email' class='easyui-validatebox' data-options='required:true' placeholder='Type email here'/></td></tr>
     <tr><th colspan='2'><input type='button' class='btn' value='Recover Password' onClick=\"swapcontent('pass_recovery_update')\"/><input type='button' class='btn' value='Close' onClick=\"swapcontent('close_dialog','w')\"/></th></tr>
     </table></form>
     <div id='pass_recovery_update'></div>";

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
          $db->sql("select * from stafftb where id='$id_val'");
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
          $res_e=@mysqli_query($con, "select * from stafftb where fileno='$j->fileno'");
          if(@mysqli_num_rows($res_e)>=1) //already exist
          {
               mysqli_query($con, "update stafftb set fileno='$j->fileno',title='$j->title',surname='$j->sname',first_name='$j->fname',other_name='$j->oname',email='$j->email',tel_no='$j->phone',staff_category='$j->staff_cat',dept_id='$dept_id',fact_id='$fact_id',status='Active',entry_by='$login_id' where fileno='$j->fileno' limit 1") or die( mysqli_error($con));
               ?>
               <script>$.messager.alert('Update Profile','Operation is successful');</script>
               <?php
          } //normal update after edit
          else
          {
               mysqli_query($con, "insert into stafftb set fileno='$j->fileno',title='$j->title',surname='$j->sname',first_name='$j->fname',other_name='$j->oname',email='$j->email',tel_no='$j->phone',staff_category='$j->staff_cat',dept_id='$dept_id',fact_id='$fact_id',password='$password',status='Active',added_date='$added_date',added_time='$added_time',entry_by='$login_id'") or die( mysqli_error($con));
               ?>
               <script>$.messager.alert('Add Staff','Operation is successful');</script>
               <?php
          } //normal save

          //load sql is used to re-display the content in the table back
          $load_sql="select s.id,s.fileno,s.title,s.surname,s.first_name,s.other_name,s.email,s.tel_no,s.staff_category,d.dept_name from stafftb s, depttb d where s.dept_id=d.dept_id and s.dept_id='$dept_id' order by s.surname,s.first_name";

          //log the activity
          $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d'); $login_id=@$_SESSION['login_id'];
          @mysqli_query($con, "insert into portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Update staff details $j->fileno','Staff details updated by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
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
          $res_chk=@mysqli_query($con, "select * from stafftb where id='$id'");  //use to fetch the record back in the criteria
          $rs_chk=@mysqli_fetch_array($res_chk);
          $d_id=@$rs_chk['dept_id'];


          @mysqli_query($con, "delete from stafftb where id='$id'");  //delete record

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
} //end of save_staff

if($id=='load_role')
{
     $fileno_str=explode("***",@$_REQUEST['fileno']);
     $fact_id=$fileno_str[0];  $dept_id=$fileno_str[1];  $fileno=$fileno_str[2];
     $res_r=@mysqli_query($con, "select * from roletb where status='Active'");
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
               $res_f=@mysqli_query($con, "select * from staff_roletb where fileno='$fileno' and role='$role_value'");
               if(@mysqli_num_rows($res_f)>=1) @mysqli_query($con, "update staff_roletb set status='Active' where fileno='$fileno' and role='$role_value'");
               else
               @mysqli_query($con, "insert into staff_roletb set fileno='$fileno',fact_id='$fact_id',dept_id='$dept_id',role='$role_value',status='Active',added_date=CURDATE(),added_time=CURTIME(),entry_by='$login_id',activity='$role_value role added by $login_id'");
          }
          echo "<p><font color='#D3E488'><b>Role updated successfully</b></font></p>";
          $sql="select * from staff_roletb where fileno='$fileno'";
     } //end of action ==update

     if($action=='update_status')
     {
          //$role_status; $id_val
          $r_name=$_REQUEST['r_name'];
          $added_date=@date('Y-m-d'); $added_time=@date('h:i:s');
          if($role_status=='Active') $a="Inactive"; else $a="Active";
          @mysqli_query($con, "update staff_roletb set status='$a',activity='$r_name role modified by $login_id on $added_date at $added_time' where fileno='$fileno' and role='$r_name'");
          echo "<p><font color='#D3E488'><b>Role updated successfully</b></font></p>";
          $sql="select * from staff_roletb where fileno='$fileno'";
     } //end of update status

     if($action=='view')
     {
          $sql="select * from staff_roletb where fileno='$fileno'";
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

} //end of update role

////////////////////////////////////////Bursary Automation Management System (BAMS) /////////////////////////////////
if($id=='school_section')
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
          @mysqli_query($con, "insert into schooltb set sch_code='$j->code',sch_name='". mysqli_real_escape_string($con, $j->name)."',status='$j->status',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          $sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from schooltb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $s_name=$rs_d['sch_name'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted school record {$rs_d['sch_name']}");

          @mysqli_query($con, "delete from schooltb where id='$r_id'");
          $sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from schooltb where 1";
          if($j->code!="") $sql.=" and sch_code='$j->code'";
          if($j->name!="") $sql.=" and sch_name='$j->name'";
          if($j->status!="") $sql.=" and status='$j->status'";

          $sql.=" order by sch_code";

     }


     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $tb="<table><tr><th>S/N</th><th>SCHOOL CODE</th><th>SCHOOL NAME</th><th>SCHOOL STATUS</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['sch_code']}</td><td>{$rs_v['sch_name']}</td><td>{$rs_v['status']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('school_section','delete','$r_id');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";
     exit;
}

if($id=='budget_section')
{
     //print_r($_REQUEST['mydata']) ; exit;
     $j=json_decode(@$_REQUEST['mydata']);
     //$code=$j->code;
     //$name=@mysqli_real_escape_string($con, $j->name);
     //$status=$j->status;
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     if($j->bcat != 'Recurrent') $j->bcat=$j->bcat2;
     //if($j->bcat == 'Recurrent') $j->folio = '';
     //echo $j->rcat."--".$_REQUEST['rcat']; exit;
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     //exit;

     if($j->bcat2 == 'Departmental') $folio = $j->deptcode;

     else  $folio = $j->budgetcode;
     if(isset($j->budget_title) and $j->budget_title !='') $btitle=$j->budget_title;
     else $btitle=get_folio_name($folio);

     //echo $j->bcat2; exit;
     if($action=='save')
     {
          if($j->bcat2 == 'Departmental') {
               if($j->deptcode == '' || $j->b_year == '' || $j->amount < 0)
               {
                    echo "<script>alert('Ensure all compulsory fields are filled.');</script>";
                    exit;
               }
               $btitle=mysqli_real_escape_string($ccon, get_dept_name_act($folio));
               $bsubcat = $j->bsubcat;
               $check=mysqli_query($con, "SELECT * FROM budgettb WHERE folio_code='{$j->deptcode}' AND budget_year='{$j->b_year}'");
               if(mysqli_num_rows($check) > 0)
                    $sql = "UPDATE budgettb SET amount=amount + {$j->amount}, dept_code='{$j->deptcode}', bursary_category='{$j->bcat2}', bursary_sub_category='{$bsubcat}', budget_title='{$btitle}' WHERE folio_code='{$j->deptcode}' AND budget_year='{$j->b_year}'";
               else
                    $sql="INSERT INTO budgettb set folio_code='{$j->deptcode}', dept_code='{$j->deptcode}', bursary_category='{$j->bcat2}', bursary_sub_category='{$bsubcat}', budget_year='{$j->b_year}', amount='{$j->amount}', budget_title='{$btitle}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
               @mysqli_query($con, $sql) or die(mysqli_error($con));
          }else{
               if($j->budgetcode=='' || $j->bcat2 == '' || $j->b_year == '' || $j->amount < 0)
               {
                    echo "<script>alert('Ensure all compulsory fields are filled!');</script>";
                    exit;
               }
               if($j->bcat2 == 'Recurrent') $bsubcat = $j->bsubcat;
               else $bsubcat = $j->bsubcat2;
               $check=mysqli_query($con, "SELECT * FROM budgettb WHERE folio_code='{$j->budgetcode}' AND budget_year='{$j->b_year}'");
               if(mysqli_num_rows($check) > 0)
                    $sql = "UPDATE budgettb SET amount=amount + {$j->amount}, dept_code='{$j->deptcode}', bursary_category='{$j->bcat2}', bursary_sub_category='{$bsubcat}', budget_title='{$btitle}' WHERE folio_code='{$j->budgetcode}' AND budget_year='{$j->b_year}'";
               else
                    $sql = "INSERT INTO budgettb set folio_code='{$j->budgetcode}', dept_code='{$j->deptcode}', bursary_category='{$j->bcat2}', bursary_sub_category='{$bsubcat}', budget_year='{$j->b_year}', amount='{$j->amount}', budget_title='{$btitle}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'";
               @mysqli_query($con, $sql);
          }

          /*
          $res_chk=@mysqli_query($con, "select * from budgettb where folio_code='$j->folio' and dept_code='$j->dept' and unit_code='$j->unit' and budget_year='$j->b_year'");
          if(@mysqli_num_rows($res_chk)>=1)
          {
          $row=@$j->row_id;  //row id of record to edit
          @mysqli_query($con, "update budgettb set folio_code='$j->folio', dept_code='$j->dept',bursary_category='$j->bcat', bursary_sub_category='$j->rcat', budget_year='$j->b_year',amount='$j->amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where id='$row'");
     }
     else
     {
     @mysqli_query($con, "insert into budgettb set folio_code='$j->folio', dept_code='$j->dept',bursary_category='$j->bcat', bursary_sub_category='$j->rcat', budget_year='$j->b_year',amount='$j->amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
} //end of save
*/
logs("$login_id","Save Record","$login_id saved budget record $j->deptcode $j->bcat2 $j->b_year $j->amount");
//$sql="select * from budgettb where dept_code='$j->deptcode' and budget_year='$j->b_year' order by dept_code,bursary_category,budget_year";
$action='search';
echo "<script>alert('Record saved successfully');</script>";//window.location.reload();
}

elseif($action=='delete')
{
     $res_d=@mysqli_query($con, "select * from budgettb where id='$r_id'");
     $rs_d=@mysqli_fetch_array($res_d);
     $log_desc=$rs_d['dept_code'].$rs_d['budget_category'].$rs_d['budget_year'];//for logs purpose
     logs("$login_id","Delete Record","$login_id deleted budget record $log_desc");

     @mysqli_query($con, "delete from budgettb where id='$r_id'");
     $sql="select * from budgettb where dept_code='{$rs_d['dept_code']}' and budget_year='{$rs_d['budget_year']}' order by dept_code,bursary_category,budget_year";
     echo "<script>alert('Record deleted successfully');</script>";
}

elseif($action=='search')
{
     $sql="select * from budgettb where budget_year='$j->b_year' and folio_code='$folio'";
     //if($j->budgetcode!="") $sql.=" and folio_code='$j->budgetcode'";
     //if($j->deptcode!="") $sql.=" and dept_code='$j->deptcode'";
     ////if($j->bcat2!="") $sql.=" and bursary_category='$j->bcat2'";
     //if($j->fundsource!="") $sql.=" and bursary_sub_category='$j->fundsource'";
     //if($j->b_year!="") $sql.=" and budget_year='$j->b_year'";

     //$sql.=" order by dept_code,bursary_category,budget_year";
}

elseif($action=='edit')
{
     //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

     //$fileno=@$_REQUEST['fileno'];
     $db->sql("select * from budgettb where id='$r_id'");
     if(get_magic_quotes_gpc())
     $t= @json_decode(stripslashes($db->getResult()));
     else
     $t= @json_decode($db->getResult());
     $s_array=array(s_detail=>"",msg=>"");

     if($t->row>=1) //fond
     {
          $s_array['s_detail']=$t->data; $s_array['msg']='1'; //echo @json_encode($s_array);
     }
     //exit;

}

/////////////////////view section ////////////////////
$sn=0;
$sql="select * from budgettb where budget_year='{$j->b_year}' and folio_code='{$folio}'";
$res_v= mysqli_query($con, $sql);
$g_total=0;
$tb="<table id='MyTable' width='100%' align='center'><head>
<tr align='left' style='background-color:lightgray'>
<th style='border-bottom:inset 1px'>S/N</th>
<th style='border-bottom:inset 1px'>FOLIO</th>
<th style='border-bottom:inset 1px'>DEPARTMENT</th>
<th style='border-bottom:inset 1px'>AMOUNT</th>
<th style='border-bottom:inset 1px'>YEAR</th>
<th style='border-bottom:inset 1px'>CATEGORY</th>
<th style='border-bottom:inset 1px'>ACTION</th></tr></head><body>";
if(@mysqli_num_rows($res_v) >= 1)
{
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $r_id=$rs_v['id'];
          $g_total+=$rs_v['amount'];
          (get_folio_name($rs_v['folio_code']) != '')? $fname=get_folio_name($rs_v['folio_code'])." (".$rs_v['folio_code'].")": $fname=get_dept_name_act($rs_v['folio_code'])." (".$rs_v['folio_code'].")";
          $tb.="<tr class='ht-row'><td>$sn</td>
          <td>".$fname."</td>
          <td>".@get_dept_name_act($rs_v['dept_code'])."</td>
          <td>N".number_format($rs_v['amount'],2)."</td>
          <td>{$rs_v['budget_year']}</td>
          <td>".$rs_v['bursary_category']."</td>
          <td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('budget_section','delete','$r_id');\">DELETE</a></td></tr>";
          // || <a href=\"javascript:swapcontent('budget_section','edit','$r_id');\">EDIT</a>
     }//end of while

     $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
     $tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
     $tb.="</body></table>";
     echo $tb_s.$tb;
}
else
echo "<b>No record to display</b>";

exit;
}

if($id=='asset_save') // Start of Save Asset
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
          $res_chk=@mysqli_query($con, "select * from asset_typetb where type_title='$j->asset_title' and asset_code='$j->asset_code' and cat_id='$j->cat_id'");
          if(@mysqli_num_rows($res_chk)>=1)
          {
               $row=@$j->row_id;  //row id of record to edit  id='$row'
               @mysqli_query($con, "update asset_typetb set type_id='$j->asset_title',type_title='$j->asset_title',asset_code='$j->asset_code',cat_id='$j->cat_id',ipsas_code='$j->ipsas_code',ipsas_title='$j->ipsas_title',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where type_title='$j->asset_title' and asset_code='$j->asset_code' and cat_id='$j->cat_id'");
          }
          else
          {
               mysqli_query($con, "insert into asset_typetb set type_id='$j->asset_title',type_title='$j->asset_title',asset_code='$j->asset_code',cat_id='$j->cat_id',ipsas_code='$j->ipsas_code',ipsas_title='$j->ipsas_title',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          } //end of save

          logs("$login_id","Save Record","$login_id saved asset record $j->asset_title $j->asset_code");
          $sql="select * from asset_typetb order by type_title";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from asset_typetb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['type_title'].$rs_d['dept_code'].$rs_d['asset_code'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted asset record $log_desc");

          @mysqli_query($con, "delete from asset_typetb where id='$r_id'");
          $sql="select * from asset_typetb where asset_code='{$rs_d['asset_code']}' and type_title='{$rs_d['asset_title']}' order by type_title";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from asset_typetb where 1";
          if($j->asset_code!="") $sql.=" and asset_code='$j->asset_code'";
          if($j->asset_title!="") $sql.=" and type_title='$j->asset_title'";

          $sql.=" order by type_title";
     }

     if($action=='edit')
     {
          //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

          //$fileno=@$_REQUEST['fileno'];
          $db->sql("select * from asset_typetb where id='$r_id'");
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
     $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>ASSET TITLE</th><th>ID</th><th>CATEGORY NAME</th><th>IPSAS CODE</th><th>IPSAS TITLE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $asset_code=$rs_v['asset_code'];
               $type_title=$rs_v['type_title'];
               $cat_id=$rs_v['cat_id'];
               $ipsas_code=$rs_v['ipsas_code'];
               $ipsas_title=$rs_v['ipsas_title'];
               //$g_total+=$rs_v['amount'];
               $tb.="<tr><td>$sn</td><td>$type_title</td><td>$asset_code</td><td>$cat_id</td><td>$ipsas_code</td><td>$ipsas_title</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('asset_save','delete','$r_id');\">DELETE</a> </td></tr>";//|| <a href=\"javascript:swapcontent('asset_save','edit','$r_id');\">EDIT</a>
          }//end of while

          //$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          //$tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

}///////////// End of Save Asset

if($id=='category_save') // Start of Save Category
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
          $res_chk=@mysqli_query($con, "select * from asset_categorytb where cat_title='$j->cat_title' and cat_code='$j->cat_code'");

          if(@mysqli_num_rows($res_chk)>=1)
          {

               $row=@$j->row_id; //row id of record to edit
               mysqli_query($con, "update asset_categorytb set cat_id='$j->cat_title',cat_title='$j->cat_title',cat_code='$j->cat_code',ipsas_code='$j->ipsas_code2',ipsas_title='$j->ipsas_title2',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where cat_title='$j->cat_title' and cat_code='$j->cat_code' limit 1") or die ( mysqli_error($con));

          }
          else
          {
               mysqli_query($con, "insert into asset_categorytb set cat_id='$j->cat_title',cat_title='$j->cat_title',cat_code='$j->cat_code',ipsas_code='$j->ipsas_code2',ipsas_title='$j->ipsas_title2',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          } //end of save

          logs("$login_id","Save Record","$login_id saved category record $j->cat_title $j->cat_code");
          $sql="select * from asset_categorytb order by cat_title";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from asset_categorytb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['cat_title'].$rs_d['cat_code'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted category record $log_desc");

          @mysqli_query($con, "delete from asset_categorytb where id='$r_id'");
          $sql="select * from asset_categorytb order by cat_title";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from asset_categorytb where 1";
          if($j->cat_code!="") $sql.=" and cat_code='$j->cat_code'";
          if($j->cat_title!="") $sql.=" and cat_title='$j->cat_title'";

          $sql.=" order by cat_title";
     }

     if($action=='edit')
     {
          //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

          //$fileno=@$_REQUEST['fileno'];
          $db->sql("select * from asset_categorytb where id='$r_id'");
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
     $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>CATEGORY TITLE</th><th>CATEGORY CODE</th><th>IPSAS CODE</th><th>IPSAS TITLE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $cat_title=$rs_v['cat_title'];
               $cat_code=$rs_v['cat_code'];
               $type_id=$rs_v['type_id'];
               $ipsas_code=$rs_v['ipsas_code'];
               $ipsas_title=$rs_v['ipsas_title'];
               //$g_total+=$rs_v['amount'];
               $tb.="<tr><td>$sn</td><td>$cat_title</td><td>$cat_code</td><td>$ipsas_code</td><td>$ipsas_title</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('category_save','delete','$r_id');\">DELETE</a> </td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
          }//end of while

          //$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          //$tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";


}/// End of Save Category



if ($id== 'budget_capital')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     //$code=$j->code;
     //$name=@mysqli_real_escape_string($con, $j->name);
     //$status=$j->status;
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id2'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
          $res_chk=@mysqli_query($con, "select * from budget_capitaltb where folio_code='$j->xfolio' and year='$j->xb_year' ");
          if(@mysqli_num_rows($res_chk)>=1)
          {
               $ch =  mysqli_fetch_array($res_chk);
               $ch2 = $ch['id'];
               //
               //echo $row=@$j->row_id;
               //exit;
               //row id of record to edit
               @mysqli_query($con, "update budget_capitaltb set folio_code='$j->xfolio',year='$j->xb_year',amount='$j->amount2',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where id='$ch2'");
          }
          else
          {
               @mysqli_query($con, "insert into budget_capitaltb set folio_code='$j->xfolio',year='$j->xb_year',amount='$j->amount2',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          } //end of save

          logs("$login_id","Save Record","$login_id saved budget record $j->xfolio $j->year $j->amount");
          $sql="select * from budget_capitaltb b, budget_capital_desctb c where b.folio_code='$j->xfolio' and b.year='$j->xb_year' and b.folio_code = c.folio_code order by b.folio_code, b.year";
          // $sql="select * from budget_capitaltb where folio_code='$j->xfolio' and year='$j->year' order by folio_code,year";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {

          echo "<script>alert('$r_id')</script>";
          $res_d=@mysqli_query($con, "select * from budget_capital_desctb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['folio_code'].$rs_d['desc_folio_code'].$rs_d['budget_year'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted budget record $log_desc");

          @mysqli_query($con, "delete from budget_capital_desctb where id='$r_id'");
          //$sql="select * from budget_capitaltb where dept_code='{$rs_d['dept_code']}' and budget_year='{$rs_d['budget_year']}' order by folio_code,dept_code,unit_code,budget_year";
          echo "<script>alert('Record deleted successfully');</script>";
          exit;
     }

     if($action=='search')
     {
          $sql="select * from budget_capitaltb where 1";
          if($j->folio!="") $sql.=" and folio_code='$j->folio'";
          if($j->dept!="") $sql.=" and dept_code='$j->dept'";
          if($j->unit!="") $sql.=" and unit_code='$j->unit'";
          if($j->b_year!="") $sql.=" and budget_year='$j->b_year'";

          $sql.=" order by folio_code,dept_code,unit_code,budget_year";
     }

     if($action=='edit')
     {
          //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

          //$fileno=@$_REQUEST['fileno'];
          $db->sql("select * from budget_capitaltb where id='$r_id'");
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
     $tb="<table><tr><th>S/N</th><th>FOLIO</th><th>YEAR</th><th>AMOUNT</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               echo   $r_id=$rs_v['id'];
               exit;
               $g_total+=$rs_v['amount'];
               $tb.="<tr><td>$sn</td><td>".@get_folio_name($rs_v['folio_code'])."</td><td>{$rs_v['year']}</td><td>N".number_format($rs_v['amount'],2)."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('budget_capital','delete','$r_id');\">DELETE</a> || <a href=\"javascript:swapcontent('budget_capital','edit','$r_id');\">EDIT</a></td></tr>";
          }//end of while

          $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          $tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

}
if ($id== 'budget_breakdown')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     //echo $code=$j->xfolio;
     $amount_breakdown=$j->amount3;
     //$name=@mysqli_real_escape_string($con, $j->name);
     //$status=$j->status;
     $action=@$_REQUEST['action'];

     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
          $res_chk2=@mysqli_query($con, "select * from budget_capitaltb where folio_code='$j->xfolio' and year='$j->xb_year' ");
          if(@mysqli_num_rows($res_chk2)<=0){
               echo "<script>alert('These folio code has not been added before !!!'); </script>";
               exit;
          }
          $res_chk3=@mysqli_query($con, "select * from budget_capitaltb where folio_code='$j->xfolio' and year='$j->xb_year' ");
          if(@mysqli_num_rows($res_chk3)>=1){
               $ch3 =  mysqli_fetch_array($res_chk3);
               $amount_capitaltb = $ch3['amount'];
               if ($amount_breakdown > $amount_capitaltb ) {
                    echo "<script>alert('The breakdown amount can not be greater than actual amount!!!'); </script>";
                    exit;
               }
          }

          $res_chk4= mysqli_query($con, "select SUM(amount) as amount_new from budget_capital_desctb where folio_code='$j->xfolio' and year='$j->xb_year' ") or die ( mysqli_error($con));
          if(@mysqli_num_rows($res_chk4)>0){
               $ch4 =  mysqli_fetch_array($res_chk4);
               $amount_capitaltb_desc = $ch4['amount_new'];
               $sum = $amount_capitaltb_desc + $amount_breakdown;
               if ($sum > $amount_capitaltb )	{
                    // echo "hi". $amount_capitaltb_desc;
                    echo "<script>alert('You have exceeded amount budgeted for !!!'); </script>";
                    exit;
               }
          }
          $res_chk=@mysqli_query($con, "select * from budget_capital_desctb where desc_folio_code='$j->xfolio2' and year='$j->xb_year' ");
          if(@mysqli_num_rows($res_chk)>=1)
          {
               $ch =  mysqli_fetch_array($res_chk);
               $ch2 = $ch['id'];
               //echo $row=@$j->row_id;
               //exit;
               //row id of record to edit
               @mysqli_query($con, "update budget_capital_desctb set desc_folio_code='$j->xfolio2',year='$j->xb_year',amount='$j->amount3',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where id='$ch2'");
          }
          else
          {
               @mysqli_query($con, "insert into budget_capital_desctb set folio_code='$j->xfolio',desc_folio_code='$j->xfolio2',year='$j->xb_year',amount='$j->amount3',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          } //end of save

          logs("$login_id","Save Record","$login_id saved budget record $j->xfolio $j->year $j->amount");
          $sql="select * from budget_capitaltb b, budget_capital_desctb c where b.folio_code='$j->xfolio' and b.year='$j->xb_year' and b.folio_code = c.folio_code order by b.folio_code, b.year";
          // $sql="select * from budget_capitaltb where folio_code='$j->xfolio' and year='$j->year' order by folio_code,year";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from budget_capital_desctb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['folio_code'].$rs_d['desc_folio_code'].$rs_d['budget_year'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted budget record $log_desc");

          @mysqli_query($con, "delete from budget_capital_desctb where id='$r_id'");
          //$sql="select * from budget_capitaltb where dept_code='{$rs_d['dept_code']}' and budget_year='{$rs_d['budget_year']}' order by folio_code,dept_code,unit_code,budget_year";
          echo "<script>alert('Record deleted successfully');</script>";
     }


     if($action=='search')
     {
          $sql="select * from budget_capitaltb where 1";
          if($j->folio!="") $sql.=" and folio_code='$j->folio'";
          if($j->dept!="") $sql.=" and dept_code='$j->dept'";
          if($j->unit!="") $sql.=" and unit_code='$j->unit'";
          if($j->b_year!="") $sql.=" and budget_year='$j->b_year'";

          $sql.=" order by folio_code,dept_code,unit_code,budget_year";
     }

     if($action=='edit')
     {
          //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

          //$fileno=@$_REQUEST['fileno'];
          $db->sql("select * from budget_capitaltb where id='$r_id'");
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
     $tb="<table><tr><th>S/N</th><th>FOLIO</th><th>YEAR</th><th>AMOUNT</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $g_total+=$rs_v['amount'];
               $tb.="<tr><td>$sn</td><td>".@get_folio_name($rs_v['desc_folio_code'])."</td><td>{$rs_v['year']}</td><td>N".number_format($rs_v['amount'],2)."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('budget_capital','delete','$r_id');\">DELETE</a> || <a href=\"javascript:swapcontent('budget_capital','edit','$r_id');\">EDIT</a></td></tr>";
          }//end of while

          $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          $tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

}


if($id=='budget_folio')
{
     $fundsource = $_REQUEST['fundsource'];
     $deptcode = $_REQUEST['deptcode'];
     $itemcode = $_REQUEST['itemcode'];
     $cat = $_REQUEST['bcat2'];
     if($cat == "Departmental") {
          $folio = $deptcode;
          ?>
          <table width="90%" border="0" style="margin-left:15px">
               <tr>
                    <th height="33" align="left" valign="middle">DEPARTMENT:</th>
                    <th align="left" valign="middle"><strong><?php echo $folio.": ".get_dept_name_act($folio); ?>
                         <input name="bfolio" id="bfolio" type="hidden" value="<?php echo $folio; ?>" /></strong></th>
                    </tr>
                    <tr>
                         <th colspan="2" align="left" valign="middle" height="33"><div align="center"> <input type="button" onclick="swapcontent('budget_section','save');" value=" SAVE ">&nbsp;&nbsp;&nbsp;
                              <input name="action2" id="action2" type="hidden" value="" />
                         </div></th>
                    </tr>
               </table>
               <?php
          }else{
               $folio = $fundsource."-".$deptcode."-".$itemcode;
               $res_c =  mysqli_query($con, "select * from foliotb where folio_code='$folio'");
               if( mysqli_num_rows($res_c) > 0){
                    while($rs_c=@mysqli_fetch_array($res_c))
                    {
                         $folio_code=@$rs_c['folio_code'];
                         $folio_name=@$rs_c['title'];

                    }
                    ?>
                    <table width="90%" border="0" style="margin-left:15px">
                         <tr>
                              <th height="33" align="left" valign="middle">FOLIO:</th>
                              <th align="left" valign="middle"><strong><?php echo $folio_code.": ".get_folio_name($folio_code); ?>
                                   <input name="bfolio" id="bfolio" type="hidden" value="<?php echo $folio_code; ?>" /></strong></th>
                              </tr>
                              <tr>
                                   <th colspan="2" align="left" valign="middle" height="33"><div align="center"> <input type="button" onclick="swapcontent('budget_section','save');" value=" SAVE ">&nbsp;&nbsp;&nbsp; <!--input type="button" class="btn" onclick="swapcontent('budget_section','search');" value=" SEARCH "-->
                                        <input name="action2" id="action2" type="hidden" value="" />
                                   </div></th>
                              </tr>
                         </table>
                         <?php
                    }else echo "Ivalid Folio Code";

               }

          }


          if($id=='load_code')
          {
               $dept_code=@$_REQUEST['dept_code'];?>
               <select name="folio" id="folio" style="width:300px">
                    <option selected="selected" value="">Select item...</option>
                    <?php
                    //echo "<option value='$dept_code'>$dept_code</option>";
                    if($dept_code=="Capital" or $dept_code=="Others") $res_c=@mysqli_query($con, "select * from foliotb order by title");
                    else $res_c=@mysqli_query($con, "select * from foliotb where deptcode='$dept_code' order by title");
                    if( mysqli_num_rows($res_c) == 0) $res_c=@mysqli_query($con, "select * from foliotb order by title");
                    while($rs_c=@mysqli_fetch_array($res_c))
                    {
                         $folio_code=@$rs_c['folio_code'];
                         $folio_name=@$rs_c['title'];
                         echo "<option value='$folio_code'>$folio_name <=> ($folio_code)</option>";
                    }
                    ?>
               </select> <?php
          }


          if($id=='load_unit')
          {
               $dept_code=@$_REQUEST['dept_code'];
               //echo "$dept_code"; ?>
               <select name="unit" id="unit">
                    <option selected="selected" value="">---</option>
                    <?php
                    $res_c=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' order by unit_name");
                    while($rs_c=@mysqli_fetch_array($res_c))
                    {
                         $unit_code=@$rs_c['unit_code'];
                         $unit_name=@$rs_c['unit_name'];
                         echo "<option value='$unit_code'>$unit_name</option>";
                    }
                    ?>
               </select> <?php
          }

          if($id=='load_folio')
          {
               $index = $_REQUEST['tabindex'];
               "<script>
               $('#tt').tabs('select', $index);</script>";
               $xfolio=@$_REQUEST['xfolio'];
               $xb_year=@$_REQUEST['xb_year'];

               $sql_f= mysqli_query($con, "select * from budget_capitaltb b where b.folio_code='$xfolio' and b.year='$xb_year'");
               $res_v2 =	 mysqli_fetch_array($sql_f);
               if(@mysqli_num_rows($sql_f)>=1){
                    $amount = $res_v2['amount'];
                    //echo "<b><font color='red'>$amount</font></b><input type='hidden' name='pvno' id='pvno' value='$pvno'/>";
                    echo "<input type='hidden' name='pvno' id='pvno' value='$pvno' />";
               }
               $sql="select * from budget_capitaltb b, budget_capital_desctb c where b.folio_code='$xfolio' and b.year='$xb_year' and b.folio_code = c.folio_code order by b.folio_code, b.year";

               $sn=0;
               $res_v=@mysqli_query($con, $sql);
               $g_total=0;
               $tb="<script> $('#amount2').attr('value', $amount); </script> <table width='100%' align='Center' cellspacing='0' cellpadding='5' border='1' rules='rows'><tr><th width='5%'>S/N</th><th width='50%'>FOLIO</th><th width='10%'>YEAR</th><th width='10%'>AMOUNT</th><th width='15%' align = 'right'>ACTION</th></tr>";//<th wi
               if(@mysqli_num_rows($res_v)>=1)
               {
                    while($rs_v=@mysqli_fetch_array($res_v))
                    {
                         ++$sn;
                         $r_id=$rs_v['id'];
                         $g_total+=$rs_v['amount'];
                         $tb.="<tr><td>$sn</td><td width='50%'>".@get_folio_name($rs_v['desc_folio_code'])."</td><td>{$rs_v['year']}</td><td>N".number_format($rs_v['amount'],2)."</td><td width='15%' align = 'right'><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true); $('#action').attr('value', 'delete'); swapcontent('budget_capital','delete','$r_id');\">DELETE</a> || <a href=\"javascript:swapcontent('load_folio','edit','$r_id');\">EDIT</a></td></tr>";
                    }//end of while

                    //$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
                    $tb.="<tr><td colspan='3' align='right'><b>TOTAL AMOUNT:</b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
                    $tb.="</table>";
                    echo $tb;
                    //echo $tb_s.$tb;
               }
               else
               echo "";
          }
          if($id=='load_staff_details')
          {
               $fileno=@$_REQUEST['fileno'];
               $db->sql("select * from stafftb where fileno='$fileno'");
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
               $month_name=@date('F',strtotime($pay_date)); $month_no=@date('m',strtotime($pay_date));
               $year=@date('Y',strtotime($pay_date));
               $res_p=@mysqli_query($con, "select count(*) as total from vouchertb where month(voucher_date)='$month_no' and year(voucher_date)='$year'");
               $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);

               $pvno=strtoupper($month_name."/".$year."/". $no); //echo $month_no;
               echo "<b><font color='red'>$pvno</font></b><input type='hidden' name='pvno' id='pvno' value='$pvno'/>";
          }

          if($id=='load_voucher_fileno')
          {
               $type=@$_REQUEST['type'];
               if($type=='Internal')
               {
                    echo "<b>File Number</b> <br/><select name='fileno' id='fileno' onchange=\"swapcontent('load_payee_details',this.value);\"><option selected value=''>---</option>";
                    $res_s=@mysqli_query($con, "select fileno,surname,first_name,other_name from stafftb where fileno not in ('Admin','Weathstone','School') order by convert(fileno,decimal)");
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
                    echo "<b>Phone Number:</b> <input type='text' name='fileno' id='fileno'/>";
               }
               else
               echo "";
          }

          if($id=='load_voucher_details')
          {
               ?><table>
                    <tr><th colspan="2">Detail Description of Goods/Services</th><th>Amount (=N=)</th></tr>

                    <?php
                    echo "<tr><th colspan='2'><textarea name='desc' id='desc' cols='45' rows='3'></textarea></th><th><input type='text' name='vamount' id='vamount' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' tabindex='$tab_index' onblur=\"javascript:display_total();\"></th></tr>";

                    $r=@mysqli_query($con, "select distinct * from tax_ratetb t,foliotb f where t.folio_code=f.folio_code order by t.folio_code");
                    $n=0;$av="";$tab_index=4; $j=0; //j takes care of matching btween checkbox and textbox amount
                    while($rl=@mysqli_fetch_array($r))
                    {
                         ++$n;$tab_index++;
                         $folio_code=@$rl['folio_code']; $title=@$rl['title']; $rate=@$rl['rate'];
                         $v=$folio_code."***".$rate."***".$j++;
                         echo "<tr><th><input name='code[]' id='code$n' class='code_checked' type='checkbox' value='$v' onclick=\"do_total('amount$n','$v','$n');\" /></th><th>$title <=> ($rate%)</th><th><input type='hidden' name='amount[]' class='amt' id='amount$n' value='' size='20' onkeydown='sum()' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' tabindex='$tab_index'><b><span id='amount2$n' name='amount2[]'></span></b></th></tr>";

                    }// end of while

                    ?>

                    <tr style="font-size: 18px;font-weight: bold;color:#174C68;"><th colspan="2">Total Deduction</th><th align="center" valign="bottom">

                         <div id="total_deduction" align="center" ><b>0.00</b></div></th></tr>
                         <tr style="font-size: 18px;font-weight: bold;color:#174C68;"><th colspan="2">Total</th><th align="center" valign="bottom">
                              <div id="total" align="center" ><b>0.00</b></div></th></tr>


                              <tr><th colspan="3">
                                   <input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('voucher_section','save');" />
                                   <!--<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('salary_scale_section','search');" />
                                   <input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('salary_scale_section','view_all');" /> -->
                                   <input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('voucher_section','refresh');" />


                              </th></tr>
                         </table>
                         <?php
                    }

                    if($id=='load_payee_details')
                    {
                         $fileno=@$_REQUEST['fileno'];
                         $type=@$_REQUEST['type'];

                         if($type=='Internal')
                         {
                              $res_s=@mysqli_query($con, "select * from stafftb where fileno='$fileno'");
                              $rs_s=@mysqli_fetch_array($res_s);
                              $name=strtoupper($rs_s['surname'])." ".strtolower(ucfirst($rs_s['first_name']))." ".strtolower(ucfirst($rs_s['other_name']));
                              $acct_no=$rs_s['acct_no'];
                              $bank_name=$rs_s['bank_name'];
                              echo "$name***$acct_no***$bank_name";
                              /*$db->sql("select * from stafftb where fileno='$fileno'");
                              if(get_magic_quotes_gpc())
                              $t= @json_decode(stripslashes($db->getResult()));
                              else
                              $t= @json_decode($db->getResult());
                              $s_array=array(s_detail=>"",msg=>"");

                              if($t->row>=1) //fond
                              {
                              $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
                         } */
                         exit;
                    }
               }


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
                         $d=@mysqli_query($con, "select * from vouchertb where id = '$r_id'");
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

                    $login_id=@$_SESSION['login_id'];

                    $vcode=@$_REQUEST['code'];$vamt=@$_REQUEST['amount'];  //code is the folio_code and rate
                    //$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
                    //echo "$vcode ==> $vamt===>$mydata";exit;
                    if($action=='save')
                    {

                         foreach($vamt as $amt)
                         {
                              if($amt!="" && !preg_match('/^\d+(\.\d+)?$/', $amt))
                              {
                                   echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
                              }
                         }
                         // End of Validation
                         $i=0;$j=0;$tamt=0;$emsg=array(); $total_tax=0; $amount=0;
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
                                        if( mysqli_query($con, "insert into voucher_taxtb set pvno='$pvno',folio_code='$tax_folio_code',amount='$amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
                                        {
                                             //
                                        }
                                        else
                                        {

                                             echo "<script>alert('Unable to save record due to duplicate record entry');</script>";

                                        }

                                   }//end of amount is not empty
                                   $i++;

                              }// end of foreach folio code

                         }// end of folio code is not empty for tax deduction

                         //now save to voucher table

                         $amount_approved=$vamount;
                         $amount_paid=$amount_approved - $total_tax;  //after tax deduction
                         $year=@date('Y',strtotime(@$_REQUEST['pay_date']));

                         $total_budget=@get_budget($folio,$year);
                         if( ($amount_paid <= $total_budget) or $total_budget=='' )
                         {
                              @mysqli_query($con, "insert into vouchertb set pvno='$pvno',folio_code='$folio',voucher_date='$pay_date',dept_code='$dept',dept_acctcode='$account',payee_type='$type',fileno='$fileno',payee_name='$name',payee_acct_no='$act_no',payee_bank_name='$bank',payee_address='$address',payee_tin_number='$payee_tin_number',payee_sort_code='$payee_sort_code',description='$desc',amount_approved='$amount_approved',total_tax='$total_tax',amount_paid='$amount_paid',prepared_by='$login_id',date_prepared=CURDATE(),time_prepared=CURTIME(),entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");


                              logs($login_id,"Save Record","Insert voucher record: $pvno $name $folio $amount_approved $amount_paid $total_tax");
                              echo "<script>alert('Payment Voucher saved successfully');</script>";
                              $sql="select * from vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
                         }
                         else
                         {
                              echo "<script>alert('Error: You have overshoot the budget for this account. Your payment voucher cannot be saved');</script>";
                              @mysqli_query($con, "delete from voucher_taxtb where pvno='$pvno'");
                              $sql="select * from vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
                         }


                    }// end of save

                    if($action=='delete')
                    {
                         $res_d=@mysqli_query($con, "select * from vouchertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['pvno'].$rs_d['folio_code'].$rs_d['voucher_date'].$rs_d['dept_code'].$rs_d['payee_name'];//for logs purpose
                         $pvno=$rs_d['pvno'];
                         logs("$login_id","Delete Record","$login_id deleted voucher record $log_desc");

                         @mysqli_query($con, "delete from vouchertb where id='$r_id'");
                         @mysqli_query($con, "delete from voucher_taxtb where pvno='$pvno'");
                         $sql="select * from vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
                         echo "<script>alert('Record deleted successfully');</script>";
                    }

                    if($action=='view')
                    {
                         $sql="select * from vouchertb where prepared_by='$login_id' and checked_by='' order by voucher_date,folio_code,pvno";
                    }

                    /////////////////////view section ////////////////////
                    $sn=0;
                    $res_v=@mysqli_query($con, $sql);
                    $g_total=0;
                    $tb="<table><tr><th>S/N</th><th>PROCESS NO.</th><th>PV NO.</th><th>FOLIO</th><th>DEPARTMENT</th><th>DATE</th><th>PAYEE</th><th>AMOUNT</th><th>ACTION</th></tr>";
                    if(@mysqli_num_rows($res_v)>=1)
                    {
                         while($rs_v=@mysqli_fetch_array($res_v))
                         {
                              ++$sn;
                              $r_id=$rs_v['id'];
                              $g_total+=$rs_v['amount_paid'];
                              $tb.="<tr><td>$sn</td><td>{$rs_v['pvno']}</td><td>{$rs_v['pvno_paid']}</td><td>".@get_folio_name($rs_v['folio_code'])."</td><td>".@get_dept_name($rs_v['dept_code'])."</td><td>{$rs_v['voucher_date']}</td><td>{$rs_v['payee_name']}</td><td>N".number_format($rs_v['amount_paid'],2)."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('voucher_section','delete','$r_id');\">DELETE</a></td></tr>";
                         }//end of while

                         $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
                         $tb.="<tr><td colspan='6'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
                         $tb.="</table>";
                         echo $tb_s.$tb;
                    }
                    else
                    echo "<b>No record to display</b>";


               }// end of voucher_section

               if($id=='search_voucher')  //search for voucher given voucher number
               {
                    $pvno=@base64_encode($_REQUEST['pvno']);
                    $url="voucher_report.php?p=$pvno";
                    echo $url;
                    /*echo "<script>location='voucher_report.php?p=$pvno';</script>"; */
               } //end of search for voucher

               if($id=='search_voucher_by_pvno')  //search for voucher given voucher real number
               {
                    $pvno=@base64_encode($_REQUEST['pvno']);
                    $url="voucher_report.php?p=$pvno";
                    echo $url;
                    /*echo "<script>location='voucher_report.php?p=$pvno';</script>"; */
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
               }

               if($id=='display_voucher_process')
               {
                    $pvno=$_REQUEST['pvno'];
                    $r_vals=$_REQUEST['r_vals'];
                    $res_d=@mysqli_query($con, "select * from vouchertb where pvno='$pvno'");
                    $rs_d=@mysqli_fetch_array($res_d);
                    if(strtolower($r_vals)!="cash officer")
                    {
                         $tb="<form name='frmpro' id='frmpro'><table><tr><th>PV NO</th><td>$pvno</td></tr>
                         <tr><th>FOLIO/CODE</th><td>". @get_folio_name($rs_d['folio_code'])."</td></tr>
                         <tr><th>PAYEE</th><td>{$rs_d['payee_name']}</td></tr>
                         <tr><th>PAYEE ACCT NO.</th><td>{$rs_d['payee_acct_no']}</td></tr>
                         <tr><th>PAYEE BANK</th><td>{$rs_d['payee_bank_name']}</td></tr>
                         <tr><th>PREPARED DATE</th><td>".date('d/m/Y',strtotime($rs_d['voucher_date']))."</td></tr>
                         <tr><th>ACTION</th><td><select name='opt' id='opt'>
                         <option selected value=''>---</option>
                         <option value='Approved'>Approved</option>
                         <!--<option value='Not Approved'>Not Approved</option>-->
                         </select><input type='hidden' name='pvno' id='pvno' value='$pvno'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>
                         <tr><th>COMMENTS</th><td><textarea name='comment' id='comment' cols='15' rows='3'></textarea></td></tr>
                         <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_voucher');\" class='btn'/></th></tr>
                         <table><div id='process_voucher'></div></form>";
                         echo $tb;
                    }
                    else
                    {
                         //section for the person to pay
                         $res_a=@mysqli_query($con, "select * from bank_accounttb order by acctname");
                         $tb="<form name='frmpro' id='frmpro'><table><tr><th>PV NO</th><td>$pvno</td></tr>
                         <tr><th>FOLIO/CODE</th><td>". @get_folio_name($rs_d['folio_code'])."</td></tr>
                         <tr><th>PAYEE</th><td>{$rs_d['payee_name']}</td></tr>
                         <tr><th>PAYEE ACCT NO.</th><td>{$rs_d['payee_acct_no']}</td></tr>
                         <tr><th>PAYEE BANK</th><td>{$rs_d['payee_bank_name']}</td></tr>
                         <tr><th>PREPARED DATE</th><td>".date('d/m/Y',strtotime($rs_d['voucher_date']))."</td></tr>
                         <tr><th>ACTION</th><td><select name='opt' id='opt'>
                         <option selected value=''>---</option>
                         <option value='Approved'>Approved</option>
                         <!--<option value='Not Approved'>Not Approved</option>-->
                         </select></td></tr>
                         <tr><th>VOTE/ACCT. TO BE DEBITED</th><td><select name='acctcode' id='acctcode'><option selected value=''>---</option>";

                         while($rs_a=@mysqli_fetch_array($res_a))
                         {
                              $acctcode=$rs_a['acctcode'];
                              $acctname=$rs_a['acctname'];
                              $tb.="<option value='$acctcode'>$acctname <=> ($acctcode)</option>";
                         }

                         $tb.="
                         </select><input type='hidden' name='pvno' id='pvno' value='$pvno'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>
                         <tr><th>CHEQUE NUMBER</th><td><input type='text' name='cheque_no' id='cheque_no'/></td></tr>
                         <tr><th>COMMENTS</th><td><textarea name='comment' id='comment' cols='15' rows='3'></textarea></td></tr>
                         <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_voucher');\" class='btn'/></th></tr>
                         <table><div id='process_voucher'></div></form>";

                         echo $tb;

                    }
               }

               if($id=='process_voucher')
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

                    if($r!='cash officer')
                    {
                         if($r=="super admin" or $r=="expenditure control")
                         $sql="update vouchertb set checked_by='$login_id',checked_action='$opt',checked_remark='$comment',date_checked=CURDATE(),time_checked=CURTIME() where pvno='$pvno'";
                         elseif($r=="super admin" or $r=="auditor")
                         $sql="update vouchertb set controlled_by='$login_id',controlled_action='$opt',controlled_remark='$comment',date_controlled=CURDATE(),time_controlled=CURTIME() where pvno='$pvno'";
                         elseif($r=="super admin" or $r=="bursar")
                         $sql="update vouchertb set authorized_by='$login_id',authorized_action='$opt',authorized_remark='$comment',date_authorized=CURDATE(),time_authorized=CURTIME() where pvno='$pvno'";
                         mysqli_query($con, $sql) or die( mysqli_error($con));

                    }
                    elseif($r=="super admin" or $r=="cash officer")
                    {
                         $acctcode=$_REQUEST['acctcode'];
                         $cheque_no=$_REQUEST['cheque_no'];
                         /////////////////////////////Generate Voucher PV real Number here ////////////////////////////////
                         $pay_date=@date('Y-m-d');                          //@$_REQUEST['pay_date'];
                         $month_name=@date('F',strtotime($pay_date)); $month_no=@date('m',strtotime($pay_date));
                         $year=@date('Y',strtotime($pay_date));
                         $res_p=@mysqli_query($con, "select count(*) as total from vouchertb where month(date_paid)='$month_no' and year(date_paid)='$year'");
                         $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);

                         $pvno_paid="PV/".strtoupper($month_name."/".$year."/". $no); //echo $month_no;
                         //echo "<b><font color='red'>$pvno</font></b><input type='hidden' name='pvno' id='pvno' value='$pvno'/>";
                         ////////////////////////////End of generate voucher PV real Number //////////////////////////////

                         $sql="update vouchertb set pvno_paid='$pvno_paid',acctcode='$acctcode',paid_by='$login_id',paid_action='$opt',paid_remark='$comment',date_paid=CURDATE(),time_paid=CURTIME(),cheque_no='$cheque_no' where pvno='$pvno'";
                         mysqli_query($con, $sql) or die( mysqli_error($con));


                         // This code has been moved to approval of voucher by the VC
                         //Code for updating other tables goes here. This code is now under voucher approval in VC/VC section/////////////
                         ///hit other tables when payment is made e.g schedule and transtb
                         /* $res_vc=@mysqli_query($con, "select * from vouchertb where pvno='$pvno'");
                         $rs_vc=@mysqli_fetch_array($res_vc);
                         if(@mysqli_num_rows($res_vc)>=1)
                         {
                         $schedule_no=@generate_schedule_no();
                         @mysqli_query($con, "insert into scheduletb set schedule_no='$schedule_no',pvno='$pvno_paid',date_prepared=CURDATE(),acctcode='$acctcode',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
                         insert_transaction($rs_vc['fileno'],$rs_vc['dept_acctcode'],$rs_vc['acctcode'],$rs_vc['folio_code'],'Credit',$rs_vc['date_paid'],$rs_vc['amount_paid'],$pvno_paid,'Voucher Payment',$login_id,$pvno_paid,$rs_vc['payee_name']);
                         // insert_transaction($rs_vc['fileno'],$rs_vc['dept_acctcode'],$rs_vc['acctcode'],$rs_vc['folio_code'],'Credit',$rs_vc['date_paid'],$rs_vc['amount_paid'],$rs_vc['pvno'],'Voucher Payment',$login_id,$pvno,$rs_vc['payee_name']);  //old one

                         //save the tax
                         $res_t=@mysqli_query($con, "select * from voucher_taxtb where pvno='$pvno'");
                         if(@mysqli_num_rows($res_t)>=1)
                         {
                         while($rs_t=@mysqli_fetch_array($res_t))
                         {
                         $tax_acct=@get_tax_account($rs_t['folio_code']);
                         insert_transaction($rs_vc['fileno'],$rs_vc['dept_acctcode'],$tax_acct,$rs_t['folio_code'],'Debit',$rs_vc['date_paid'],$rs_t['amount'],$pvno_paid,'Voucher Tax Payment',$login_id,$pvno_paid,$rs_vc['payee_name']);
                    }
               } //end of if found in voucher_tax table
          } //end of found in vouchertb

          */
          //End of Code for updating other tables goes here /////////////



     } //end of cash officer


     echo "<script>alert('Record updated successfully');</script>";
}


if($id=='display_approval_process')   ////this module is used for salary approval
{
     $str=explode("***",$_REQUEST['pvno']); $month=$str[0]; $year=$str[1];
     $pvno=$month."***".$year;
     $r_vals=$_REQUEST['r_vals'];
     $res_d=@mysqli_query($con, "select * from payroll_schedule_processtb where month='$month' and year='$year'");
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
          $res_a=@mysqli_query($con, "select * from bank_accounttb order by acctname");
          $tb="<form name='frmpro' id='frmpro'><table><tr><th>MONTH PROCESSED</th><td>".get_month_name($month)."</td></tr>
          <tr><th>YEAR</th><td>$year</td></tr>
          <tr><th>PREPARED BY</th><td>".get_staff_name($rs_d['prepared_by'])."</td></tr>
          <tr><th>DATE PREPARED</th><td>".@date('d/m/Y',strtotime($rs_d['date_prepared']))."</td></tr>
          <tr><th>ACTION</th><td><select name='opt' id='opt'>
          <option selected value=''>---</option>
          <option value='Approved'>Approved</option>
          <!--<option value='Not Approved'>Not Approved</option>-->
          </select></td></tr>
          <tr><th>VOTE/ACCT. TO BE DEBITED</th><td><select name='acctcode' id='acctcode'><option selected value=''>---</option>";

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
          $sql="update payroll_schedule_processtb set 	checked_by='$login_id',checked_action='$opt',checked_remark='$comment',date_checked=CURDATE(),time_checked=CURTIME() where month='$month' and year='$year'";
          elseif($r=="super admin" or $r=="bursar")
          $sql="update payroll_schedule_processtb set authorized_by='$login_id',authorized_action='$opt',authorized_remark='$comment',date_authorized=CURDATE(),time_authorized=CURTIME() where month='$month' and year='$year'";
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

          $res_t=@mysqli_query($con, "select * from payroll_scheduletb where month='$month' and year='$year'");
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
}

if($id=='other_payment_section')
{
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     $fileno=@$_REQUEST['fileno'];
     $type=@$_REQUEST['type'];
     $folio=$_REQUEST['folio'];
     $amount=$_REQUEST['amount'];
     $start_date=$_REQUEST['start_date'];
     $end_date=$_REQUEST['end_date'];
     $status=$_REQUEST['status'];

     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
          //i want to treat the start_date and end_date
          $start_date_day=@date('d',strtotime($start_date)); $start_date_month=@date('m',strtotime($start_date));
          $start_date_year=@date('Y',strtotime($start_date));

          $end_date_day=@date('d',strtotime($end_date)); $end_date_month=@date('m',strtotime($end_date));
          $end_date_year=@date('Y',strtotime($end_date));

          //create new start date and end date
          $start_date=$start_date_year."-".sprintf("%02d",$start_date_month)."-"."01";
          $end_date=prepare_transdate($end_date_month,$end_date_year);  //$end_date_year."-".sprintf("%02d",$end_date_month)."-".;

          $res_c=@mysqli_query($con, "select * from otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");
          if(@mysqli_num_rows($res_c)<=0)
          {
               @mysqli_query($con, "insert into otherpayment_sourcetb set fileno='$fileno',folio_code='$folio',amount='$amount',start_date='$start_date',end_date='$end_date',payment_type='$type',status='$status',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               logs("$login_id","Save Record","$login_id saved other payment source record $fileno $folio $amount $type");
               $sql="select * from otherpayment_sourcetb where fileno='$fileno' order by fileno,payment_type,end_date";
               //echo "$fileno in less";
               echo "<script>alert('Record saved successfully');</script>";

          } //update record
          else
          {
               @mysqli_query($con, "update otherpayment_sourcetb set fileno='$fileno',folio_code='$folio',amount='$amount',start_date='$start_date',end_date='$end_date',payment_type='$type',status='$status',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");
               logs("$login_id","Save Record","$login_id update other payment source record $fileno $folio $amount $type");
               $sql="select * from otherpayment_sourcetb where fileno='$fileno' order by fileno,payment_type,end_date";
               // echo "$fileno in greater";
               echo "<script>alert('Record updated successfully');</script>";
          } //save


          //$sql="select * from schooltb order by sch_code";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");
          $rs_d=@mysqli_fetch_array($res_d);
          logs("$login_id","Delete Record","$login_id deleted other payment sources record File No: {$rs_d['fileno']} Folio: {$rs_d['folio_code']} Amount: {$rs_d['amount']}");

          @mysqli_query($con, "delete from otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");//for logs purpose

          //$sql="select * from schooltb order by sch_code";
          $sql="select * from otherpayment_sourcetb where fileno='$fileno' order by fileno,payment_type,end_date";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          //$sql="select * from otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'";
          // $res_s=@mysqli_query($con, $sql)
          //$rs_s=@mysqli_fetch_array($res_s);
          //if(@mysqli_num_rows($res_s)>=1)
          //{
          $db->sql("select * from otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'");
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

          $sql="select * from otherpayment_sourcetb where fileno='$fileno' order by fileno,payment_type,end_date";
          exit;
          //} //end of found
          //else
          //{

          //} //end of not found

     }//end of search

     if($action=='view')
     {
          /////////////////////view section ////////////////////
          $sql="select * from otherpayment_sourcetb where fileno='$fileno' order by fileno,payment_type,end_date";
     }



     $sn=0;
     $res_v= mysqli_query($con, $sql); //or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>FILE NO</th><th>FULLNAME</th><th>FOLIO</th><th>START DATE</th><th>END DATE</th><th>AMOUNT</th><th>STATUS</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $tb.="<tr><td>$sn</td><td>{$rs_v[fileno]}</td><td>".@get_staff_name($rs_v[fileno])."</td><td>".@get_folio_name($rs_v[folio_code])."</td><td>".date('d/m/Y',strtotime($rs_v[start_date]))."</td><td>".date('d/m/Y',strtotime($rs_v[end_date]))."</td><td>".number_format($rs_v[amount],2)."</td><td>{$rs_v[payment_type]}</td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

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
          $res_c=@mysqli_query($con, "select * from proratatb where fileno='$fileno' and month='$month' and year='$year'");
          if(@mysqli_num_rows($res_c)<=0)
          {
               @mysqli_query($con, "insert into proratatb set fileno='$fileno',year='$year',month='$month',no_of_days='$days',remark='$comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id',transdate='$transdate'");
               logs("$login_id","Save Record","$login_id saved proration record FileNo:$fileno Month:$month Year: $year Days: $days");
               echo "<script>alert('Record saved successfully');</script>";
          } //update record
          else
          {
               @mysqli_query($con, "update proratatb set fileno='$fileno',year='$year',month='$month',no_of_days='$days',remark='$comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id',transdate='$transdate' where fileno='$fileno' and month='$month' and year='$year'");
               logs("$login_id","Save Record","$login_id update proration record FileNo:$fileno Month:$month Year: $year Days: $days");
               echo "<script>alert('Record updated successfully');</script>";
          } //save


          //$sql="select * from schooltb order by sch_code";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from proratatb where fileno='$fileno' and month='$month' and year='$year'");
          $rs_d=@mysqli_fetch_array($res_d);
          logs("$login_id","Delete Record","$login_id deleted proration record File No: {$rs_d['fileno']} Month: {$rs_d['month']} Year: {$rs_d['year']} No of Days: {$rs_d['no_of_days']}");

          @mysqli_query($con, "delete from proratatb where fileno='$fileno' and month='$month' and year='$year'");//for logs purpose

          //$sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          //$sql="select * from otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio' and payment_type='$type'";
          // $res_s=@mysqli_query($con, $sql)
          //$rs_s=@mysqli_fetch_array($res_s);
          //if(@mysqli_num_rows($res_s)>=1)
          //{
          $db->sql("select * from proratatb where fileno='$fileno' and month='$month' and year='$year'");
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
          $sql="select * from proratatb where month='$month' and year='$year' order by fileno";
          $sn=0;
          $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
          $tb="<table><tr><th>S/N</th><th>FILE NO</th><th>FULLNAME</th><th>NO OF DAYS</th><th>MONTH</th><th>YEAR</th></tr>";
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

}

if($id=='display_schedule_voucher')
{
     $start_date=$_REQUEST['start_date'];  $end_date=$_REQUEST['end_date'];
     $account=$_REQUEST['account'];
     $res_v=@mysqli_query($con, "select * from vouchertb where date_paid>='$start_date' and date_paid<='$end_date' and acctcode='$account' and schedule_no=''") or die( mysqli_error($con));
     $sn=0;
     $tb="<table align='center'><tr><th>S/NO</th><th>PV NO</th><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>AMOUNT</th><th>PREPARED DATE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $pvno_paid=$rs_v['pvno_paid'];
               $p=@base64_encode($pvno_paid);
               $tb.="<tr><td>$sn</td><td>$pvno_paid</td><td>{$rs_v['payee_name']}</td><td>{$rs_v['payee_acct_no']}</td><td>{$rs_v['payee_bank_name']}</td><td>".@number_format($rs_v['amount_paid'],2)."</td><td>".date('d/m/Y',strtotime($rs_v['date_prepared']))."</td><td><input type='checkbox' name='code[]' id='code$sn' value='$pvno_paid'/>| <a href='voucher_report.php?p=$p' target='_blank'>VIEW</a></td></tr>";

          } //end of while

          $tb.="<tr><td colspan='8'><center><input type='button' name='cmds' id='cmds' value='Schedule Vouchers' onclick=\"swapcontent('schedule_voucher','save');\" class='btn'/></center></td></tr></table>";
          echo $tb;
     }
     else
     echo "<font color='red'><b>No record to display</b></font>";
} //end of display_schedule_voucher

if($id=='schedule_voucher')
{
     $start_date=$_REQUEST['start_date'];  $end_date=$_REQUEST['end_date'];
     $account=$_REQUEST['account'];
     $code=$_REQUEST['code'];
     $action=$_REQUEST['action'];
     $r_id=$_REQUEST['r_id'];

     if($action=='save')
     {
          if(count($code)<=0) echo "<script>alert('Invalid PV Number selection. Please select PV Number to batch');</script>";

          $pay_date=@date('Y-m-d');                          //@$_REQUEST['pay_date'];
          $month_name=@date('F',strtotime($pay_date)); $month_no=@date('m',strtotime($pay_date));
          $year=@date('Y',strtotime($pay_date));
          $res_p=@mysqli_query($con, "select count(distinct schedule_no) as total from vouchertb where month(date_paid)='$month_no' and year(date_paid)='$year'");
          $rs_p=@mysqli_fetch_array($res_p); $no=sprintf("%04d",$rs_p['total'] + 1);

          $sch_no="SD/".strtoupper($month_name."/".$year."/". $no); //echo $month_no;

          foreach($code as $code_val)
          {
               @mysqli_query($con, "update vouchertb set schedule_no='$sch_no' where pvno_paid='$code_val'");
               //echo "$code_val<br/>";
          } //end of pvno_paid

          echo "<script>alert('The specified Vouchers have been scheduled with Schedule Number $sch_no');</script>";

     } //end of save

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from vouchertb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          $pvno_paid=$rs_d['pvno_paid'];
          $sch_no=$rs_d['schedule_no'];
          @mysqli_query($con, "update vouchertb set schedule_no='' where pvno_paid='$pvno_paid' and final_approval!='Approved'");
          echo "<script>alert('The specified Voucher has been removed from the batch');</script>";
     }
     //load the vouchers batched with schedule no sch_no
     $res_v=@mysqli_query($con, "select * from vouchertb where schedule_no='$sch_no' order by pvno_paid") or die( mysqli_error($con));
     $sn=0;
     $tb="<table align='center'><tr><td colspan='8'><b><center>SCHEDULE NUMBER: $sch_no</center></b></td></tr><tr><th>S/NO</th><th>PV NO</th><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>AMOUNT</th><th>PREPARED DATE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=$rs_v['id'];
               $pvno_paid=$rs_v['pvno_paid'];
               $p=@base64_encode($pvno_paid);
               $tb.="<tr><td>$sn</td><td>$pvno_paid</td><td>{$rs_v['payee_name']}</td><td>{$rs_v['payee_acct_no']}</td><td>{$rs_v['payee_bank_name']}</td><td>".@number_format($rs_v['amount_paid'],2)."</td><td>".date('d/m/Y',strtotime($rs_v['date_prepared']))."</td><td><a href=\"javascript: if(confirm('Are you sure you want to delete this record?')==true) swapcontent('schedule_voucher','delete','$id2');\">DELETE FROM SCHEDULED VOUCHERS</a></td></tr>";

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


} //end of schedule_voucher

if($id=='display_approve_schedule_voucher')
{
     $sch_no=$_REQUEST['sch_no'];
     $action=$_REQUEST['action'];
     $r_id=$_REQUEST['r_id'];

     if($action=='search')
     {
          $sql="select * from vouchertb where schedule_no='$sch_no' order by pvno_paid";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from vouchertb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          $pvno_paid=$rs_d['pvno_paid'];
          $sch_no=$rs_d['schedule_no'];
          @mysqli_query($con, "update vouchertb set schedule_no='',final_approval='' where pvno_paid='$pvno_paid'");
          $sql="select * from vouchertb where schedule_no='$sch_no' order by pvno_paid";
          echo "<script>alert('The specified Voucher has been removed from the batch');</script>";
     }

     $res_v=@mysqli_query($con, $sql) or die( mysqli_error($con));
     $sn=0;
     $tb="<table align='center'><tr><td colspan='8'><b><center>SCHEDULE NUMBER: $sch_no</center></b></td></tr><tr><th>S/NO</th><th>PV NO</th><th>PAYEE</th><th>PAYEE ACCT NO.</th><th>PAYEE BANK</th><th>AMOUNT</th><th>PREPARED DATE</th><th>ACTION</th></tr>";
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

          $tb.="<tr><td colspan='8'><center><input type='button' name='cmds' id='cmds' value='Approve Scheduled Vouchers' onclick=\"swapcontent('approve_schedule_voucher','save');\" class='btn'/>  <input type='button' name='cmds2' id='cmds2' value='Print Bank Schedule' onclick=\"swapcontent('approve_schedule_voucher','print');\" class='btn'/></center></td></tr></table>";
          echo $tb;
     }
     else
     echo "<font color='red'><b>No record to display</b></font>";
} //end of display_approve_schedule_voucher


if($id=='approve_schedule_voucher')
{
     $sch_no=$_REQUEST['sch_no'];
     $action=$_REQUEST['action'];
     $r_id=$_REQUEST['r_id'];
     if($action=='save')
     {
          $login_id=@$_SESSION['login_id'];
          @mysqli_query($con, "update vouchertb set final_approval='Approved',final_approval_date=CURDATE(),final_approval_by='$login_id' where schedule_no='$sch_no'");

          //Code for updating other tables goes here /////////////
          ///hit other tables when payment is made e.g schedule and transtb
          $res_vc=@mysqli_query($con, "select * from vouchertb where schedule_no='$sch_no'");

          if(@mysqli_num_rows($res_vc)>=1)
          {
               while($rs_vc=@mysqli_fetch_array($res_vc))
               {
                    $pvno_paid=$rs_vc['pvno_paid'];  //real pvno assigned after cash officer has said pay
                    $pvno=$rs_vc['pvno'];

                    //@mysqli_query($con, "insert into scheduletb set schedule_no='$schedule_no',pvno='$pvno_paid',date_prepared=CURDATE(),acctcode='$acctcode',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
                    insert_transaction($rs_vc['fileno'],$rs_vc['dept_acctcode'],$rs_vc['acctcode'],$rs_vc['folio_code'],'Credit',$rs_vc['date_paid'],$rs_vc['amount_paid'],$pvno_paid,'Voucher Payment',$login_id,$pvno_paid,$rs_vc['payee_name']);

                    //save the tax
                    $res_t=@mysqli_query($con, "select * from voucher_taxtb where pvno='$pvno'");
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
               $res_ck=@mysqli_query($con, "select * from vouchertb where schedule_no='$sch_no' and final_approval='Approved'");
               if(@mysqli_num_rows($res_ck)<=0)
               { echo "<script>alert('The vouchers corresponding to the specified schedule number - $sch_no have not been approved');</script>"; exit;}
          } //end of check
          //////////////load schedule report ///////////////////////////
          $p=@base64_encode($sch_no); $mode=@base64_encode('voucher_schedule');
          echo "<script>window.open('report_template.php?id=$p&mode=$mode','_blank');</script>";
          //////////////end of load schedule report /////////////////////
     }

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

}  //end of print_payment_voucher

if($id=='annual_increament')
{
     $staff_cat=@$_REQUEST['staff'];
     $fileno=@$_REQUEST['fileno'];
     $year=@$_REQUEST['year'];
     $fn=set_comma_breakdown($fileno); //file number separated with comma

     //check if this has been done before
     if($staff_cat!='specific')
     {
          $res_c=@mysqli_query($con, "select year from annual_increamenttb where year='$year'");
          if(@mysqli_num_rows($res_c)>=1)
          {
               echo "<script>alert('Error: The specified annual increament for the year $year has already been carried out');</script>";
               exit;
          }
     }

     //echo "FILE NO: $fn YEAR: $year CAT: $staff_cat";
     if($staff_cat=='all')
     $sql="select fileno,level,step,category from stafftb where status='Active'";
     elseif($staff_cat=='specific')
     $sql="select fileno,level,step,category from stafftb where status='Active' and fileno in ($fn)";

     $res_s= mysqli_query($con, $sql) or die( mysqli_error($con));
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
               @mysqli_query($con, "update stafftb set step='$new_step' where fileno='$fileno'");
          } //grade exist so update step for the staff

     } //end of while

     //update annual_increamenttb
     if($staff_cat!='specific')
     @mysqli_query($con, "insert into annual_increamenttb set year='$year'");

     echo "<font color='red'><b>$year step increaments for $sn staff have been successfully updated</b></font>";

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

     $res_c=@mysqli_query($con, "select * from stafftb where fileno='$j->fileno'");

     $login_id=@$_SESSION['login_id'];
     if(@mysqli_num_rows($res_c)<=0)
     {
          $default_password=@base64_encode('1111');
          mysqli_query($con, "insert into stafftb set appno='$j->fileno',fileno='$j->fileno',title='$j->title',surname='".@mysqli_real_escape_string($con, $j->surname)."',first_name='".@mysqli_real_escape_string($con, $j->first_name)."',other_name='".@mysqli_real_escape_string($con, $j->other_name)."',maiden_name='".@mysqli_real_escape_string($con, $j->maiden_name)."',sex='$j->sex',dept_code='$j->dept',unit_code='$j->unit',marital_status='$j->marital_status',religion='$j->religion',staff_status='$j->staff_status',category='$j->category',date_of_1st_appt='$j->date_of_1st_appt',date_of_assumption='$j->date_of_assumption',date_of_present_appt='$j->date_of_present_appt',initial_level='$j->level',initial_step='$j->step',level='$j->level',step='$j->step',qualification='".@mysqli_real_escape_string($con, $j->qualification)."',rank='$j->rank',employment_status='$j->employment_status',date_of_birth='$j->date_of_birth',place_of_birth='".@mysqli_real_escape_string($con, $j->place_of_birth)."',nationality='$j->nationality',state_id='$j->state',lga_id='$j->lga',country='$country',senatorial_district='".@mysqli_real_escape_string($con, $j->senatorial_district)."',contact_address='".@mysqli_real_escape_string($con, $j->contact_address)."',residential_address='".@mysqli_real_escape_string($con, $j->residential_address)."',permanent_address='".@mysqli_real_escape_string($con, $j->permanent_address)."',email='$j->email',phone_no='$j->phone_no',bank_name='".@mysqli_real_escape_string($con, $j->bank_name)."',acct_no='$j->acct_no',last_place_of_residence='".@mysqli_real_escape_string($con, $j->last_place_of_residence)."',languages_spoken='".@mysqli_real_escape_string($con, $j->languages_spoken)."',passport_number='$j->passport_number',passport_place='".@mysqli_real_escape_string($con, $j->passport_place)."',passport_date_issue='$j->passport_date_issue',hobbies='".@mysqli_real_escape_string($con, $j->hobbies)."',disability='$j->disability',disability_reason='".@mysqli_real_escape_string($con, $j->disability_reason)."',court_case='".@mysqli_real_escape_string($con, $j->court_case)."',status='$j->status',spouse_name='".@mysqli_real_escape_string($con, $j->spouse_name)."',spouse_address='".@mysqli_real_escape_string($con, $j->spouse_address)."',spouse_occupation='".@mysqli_real_escape_string($con, $j->spouse_occupation)."',guidance_name='".@mysqli_real_escape_string($con, $j->guidance_name)."',guidance_nationality='$j->guidance_nationality',guidance_state='$j->guidance_state',guidance_occupation='".@mysqli_real_escape_string($con, $j->guidance_occupation)."',guidance_address='".@mysqli_real_escape_string($con, $j->guidance_address)."',guidance_email='$j->guidance_email',guidance_phone_no='$j->guidance_phone_no',next_name='".@mysqli_real_escape_string($con, $j->next_name)."',next_address='".@mysqli_real_escape_string($con, $j->next_address)."',next_email='$j->next_email',next_phone_no='$j->next_phone_no',next_relationship='$j->next_relationship',mother_name='".@mysqli_real_escape_string($con, $j->mother_name)."',mother_nationality='$j->mother_nationality',mother_state='$j->mother_state',mother_address='".@mysqli_real_escape_string($con, $j->mother_address)."',force_no='$j->force_no',highest_force_rank='".@mysqli_real_escape_string($con, $j->highest_force_rank)."',force_period='$j->force_period',force_character='".@mysqli_real_escape_string($con, $j->force_character)."',other_info='".@mysqli_real_escape_string($con, $j->other_info)."',password='$default_password',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          logs($login_id,'Save Staff Record',"$login_id insert staff record with fileno $j->fileno");

     } //end of save
     else
     {
          //update staff record section
          mysqli_query($con, "update stafftb set title='$j->title',surname='".@mysqli_real_escape_string($con, $j->surname)."',first_name='".@mysqli_real_escape_string($con, $j->first_name)."',other_name='".@mysqli_real_escape_string($con, $j->other_name)."',maiden_name='".@mysqli_real_escape_string($con, $j->maiden_name)."',sex='$j->sex',dept_code='$j->dept',unit_code='$j->unit',marital_status='$j->marital_status',religion='$j->religion',staff_status='$j->staff_status',category='$j->category',date_of_1st_appt='$j->date_of_1st_appt',date_of_assumption='$j->date_of_assumption',date_of_present_appt='$j->date_of_present_appt',level='$j->level',step='$j->step',qualification='".@mysqli_real_escape_string($con, $j->qualification)."',rank='$j->rank',employment_status='$j->employment_status',date_of_birth='$j->date_of_birth',place_of_birth='".@mysqli_real_escape_string($con, $j->place_of_birth)."',nationality='$j->nationality',state_id='$j->state',lga_id='$j->lga',country='$country',senatorial_district='".@mysqli_real_escape_string($con, $j->senatorial_district)."',contact_address='".@mysqli_real_escape_string($con, $j->contact_address)."',residential_address='".@mysqli_real_escape_string($con, $j->residential_address)."',permanent_address='".@mysqli_real_escape_string($con, $j->permanent_address)."',email='$j->email',phone_no='$j->phone_no',bank_name='".@mysqli_real_escape_string($con, $j->bank_name)."',acct_no='$j->acct_no',last_place_of_residence='".@mysqli_real_escape_string($con, $j->last_place_of_residence)."',languages_spoken='".@mysqli_real_escape_string($con, $j->languages_spoken)."',passport_number='$j->passport_number',passport_place='".@mysqli_real_escape_string($con, $j->passport_place)."',passport_date_issue='$j->passport_date_issue',hobbies='".@mysqli_real_escape_string($con, $j->hobbies)."',disability='$j->disability',disability_reason='".@mysqli_real_escape_string($con, $j->disability_reason)."',court_case='".@mysqli_real_escape_string($con, $j->court_case)."',status='$j->status',spouse_name='".@mysqli_real_escape_string($con, $j->spouse_name)."',spouse_address='".@mysqli_real_escape_string($con, $j->spouse_address)."',spouse_occupation='".@mysqli_real_escape_string($con, $j->spouse_occupation)."',guidance_name='".@mysqli_real_escape_string($con, $j->guidance_name)."',guidance_nationality='$j->guidance_nationality',guidance_state='$j->guidance_state',guidance_occupation='".@mysqli_real_escape_string($con, $j->guidance_occupation)."',guidance_address='".@mysqli_real_escape_string($con, $j->guidance_address)."',guidance_email='$j->guidance_email',guidance_phone_no='$j->guidance_phone_no',next_name='".@mysqli_real_escape_string($con, $j->next_name)."',next_address='".@mysqli_real_escape_string($con, $j->next_address)."',next_email='$j->next_email',next_phone_no='$j->next_phone_no',next_relationship='$j->next_relationship',mother_name='".@mysqli_real_escape_string($con, $j->mother_name)."',mother_nationality='$j->mother_nationality',mother_state='$j->mother_state',mother_address='".@mysqli_real_escape_string($con, $j->mother_address)."',force_no='$j->force_no',highest_force_rank='".@mysqli_real_escape_string($con, $j->highest_force_rank)."',force_period='$j->force_period',force_character='".@mysqli_real_escape_string($con, $j->force_character)."',other_info='".@mysqli_real_escape_string($con, $j->other_info)."' where fileno='$j->fileno'") or die( mysqli_error($con));

          logs($login_id,'Update Staff Record',"$login_id updated staff record with fileno $j->fileno");
     } //end of update staff record


     echo "<script> alert('Staff biodata updated sucessfully');</script>";

} //end of update biodata for staff

if($id=='delete_biodata')
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     @mysqli_query($con, "delete from stafftb where fileno='$j->fileno'");
     echo "<script> alert('Staff biodata delete sucessfully');</script>";

} //end of delete staff biodata

if($id=='load_rank')
{
     $category=@$_REQUEST['category'];
     //echo "$dept_code"; ?>
     <select name="rank" id="rank">
          <option selected="selected" value="">---</option>
          <?php
          $res_c=@mysqli_query($con, "select * from hr_positiontb where category='$category' order by category");
          while($rs_c=@mysqli_fetch_array($res_c))
          {
               $position=@$rs_c['position'];
               echo "<option value='$position'>$position</option>";
          }
          ?>
     </select> <?php
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
          @mysqli_query($con, "insert into hr_staff_childtb set fileno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->child_name)."',date_of_birth='$j->child_dob',sex='$j->child_sex',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");

          $sql="select * from hr_staff_childtb where fileno='$j->fileno'";

          echo "<script> alert('The specified Child\'s detail has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_childtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_childtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_childtb where fileno='$fileno' order by name";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_childtb where fileno='$j->fileno' order by name";
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
          mysqli_query($con, "insert into hr_staff_employmenttb set fileno='$j->fileno',employer_name='".@mysqli_real_escape_string($con, $j->emp_name)."',location='".@mysqli_real_escape_string($con, $j->emp_location)."',rank='$j->emp_rank',salary='$j->emp_salary',from_year='$j->emp_year_from',to_year='$j->emp_year_to',leaving_reason='".@mysqli_real_escape_string($con, $j->emp_leaving)."',employment_type='$j->emp_type',status='$j->emp_status',duty='".@mysqli_real_escape_string($con, $j->emp_duty)."',bond_question='$j->emp_bond',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_employmenttb where fileno='$j->fileno' order by from_year";

          echo "<script> alert('The specified employment detail has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_employmenttb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_employmenttb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_employmenttb where fileno='$fileno' order by from_year";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_employmenttb where fileno='$j->fileno' order by from_year";
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
          mysqli_query($con, "insert into hr_staff_academic_edutb set fileno='$j->fileno',school_name='".@mysqli_real_escape_string($con, $j->edu_name)."',school_type='$j->edu_type',	qualification='$j->edu_qual',degree_class='$j->edu_grade',from_month='$j->edu_month_from',from_year='$j->edu_year_from',to_month='$j->edu_month_to',to_year='$j->edu_year_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_academic_edutb where fileno='$j->fileno' order by from_year";

          echo "<script> alert('The specified academic record has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_academic_edutb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_academic_edutb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_academic_edutb where fileno='$fileno' order by from_year";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_academic_edutb where fileno='$j->fileno' order by from_year";
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
          mysqli_query($con, "insert into hr_staff_publicationtb set fileno='$j->fileno',title='".@mysqli_real_escape_string($con, $j->pub_title)."',author='".@mysqli_real_escape_string($con, $j->pub_author)."',type='$j->pub_type',publisher='".@mysqli_real_escape_string($con, $j->pub_publisher)."',journal='".@mysqli_real_escape_string($con, $j->pub_journal)."',year_published='$j->pub_year',status='$j->pub_status',category='$j->pub_category',page_no='$j->pub_page_no',volume='$j->pub_volume',issue='$j->pub_issue',url='$j->pub_url',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_publicationtb where fileno='$j->fileno' order by year_published";

          echo "<script> alert('The specified publication has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_publicationtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_publicationtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_publicationtb where fileno='$fileno' order by year_published";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_publicationtb where fileno='$j->fileno' order by year_published";
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
          mysqli_query($con, "insert into hr_staff_servicetb set fileno='$j->fileno',service_type='".@mysqli_real_escape_string($con, $j->serv_type)."',service_place='$j->serv_place',service_details='".@mysqli_real_escape_string($con, $j->serv_detail)."',from_year='$j->serv_from',to_year='$j->serv_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_servicetb where fileno='$j->fileno' order by service_type,from_year";

          echo "<script> alert('The specified service has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_servicetb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_servicetb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_servicetb where fileno='$fileno' order by service_type,from_year";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_servicetb where fileno='$j->fileno' order by service_type,from_year";
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
          mysqli_query($con, "insert into hr_staff_researchtb set fileno='$j->fileno',topic='".@mysqli_real_escape_string($con, $j->res_topic)."',status='$j->res_status',funding_source='".@mysqli_real_escape_string($con, $j->res_funding)."',start_date='$j->res_start_date',end_date='$j->res_end_date',amount_granted='$j->res_amount',project_value='$j->res_value',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_researchtb where fileno='$j->fileno' order by start_date";

          echo "<script> alert('The specified research history has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_researchtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_researchtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_researchtb where fileno='$fileno' order by start_date";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_researchtb where fileno='$j->fileno' order by start_date";
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
          mysqli_query($con, "insert into hr_staff_training_apptb set fileno='$j->fileno',training_type='$j->tra_type',start_date='$j->tra_start_date',end_date='$j->tra_end_date',training_title='".@mysqli_real_escape_string($con, $j->tra_title)."',location='".@mysqli_real_escape_string($con, $j->tra_location)."',venue='".@mysqli_real_escape_string($con, $j->tra_venue)."',no_paper_read='$j->tra_no_paper_read',sponsor='".@mysqli_real_escape_string($con, $j->tra_sponsor)."',amount_granted='$j->tra_amount',approval_status='Approved',approval_date='$j->tra_date_approved',process_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_training_apptb where fileno='$j->fileno' order by training_type,start_date";

          echo "<script> alert('The specified training has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_training_apptb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_training_apptb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_training_apptb where fileno='$fileno' order by training_type,start_date";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_training_apptb where fileno='$j->fileno' order by training_type,start_date";
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
} //add training

if($id=='load_no_paper_read')
{
     $type=strtolower($_REQUEST['type']);
     if($type=='conference')
     echo "<br/>No of Paper Read: <input type='text' name='tra_no_paper_read' id='tra_no_paper_read'/>";
     else
     echo "";
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
          mysqli_query($con, "insert into hr_staff_prof_membershiptb set fileno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->prof_mem_name)."',category='$j->prof_mem_category',year_honoured='$j->prof_mem_year',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_prof_membershiptb where fileno='$j->fileno' order by year_honoured";

          echo "<script> alert('The specified Professional Membership has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_prof_membershiptb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_prof_membershiptb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_prof_membershiptb where fileno='$fileno' order by year_honoured";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_prof_membershiptb where fileno='$j->fileno' order by year_honoured";
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
          mysqli_query($con, "insert into hr_staff_prof_qualificationtb set fileno='$j->fileno',name='".@mysqli_real_escape_string($con, $j->prof_qual_name)."',grade='$j->prof_qual_grade',from_year='$j->prof_qual_from',to_year='$j->prof_qual_to',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_prof_qualificationtb where fileno='$j->fileno' order by from_year";

          echo "<script> alert('The specified Professional Qualification has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_prof_qualificationtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_prof_qualificationtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_prof_qualificationtb where fileno='$fileno' order by from_year";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_prof_qualificationtb where fileno='$j->fileno' order by from_year";
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
          mysqli_query($con, "insert into hr_staff_recognitiontb set fileno='$j->fileno',award_type='$j->honour_type',award_date='$j->honour_date',award_description='".@mysqli_real_escape_string($con, $j->honour_desc)."',prize='$j->honour_prize',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_recognitiontb where fileno='$j->fileno' order by award_date";

          echo "<script> alert('The specified Award/Honour has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_recognitiontb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_recognitiontb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_recognitiontb where fileno='$fileno' order by award_date";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_recognitiontb where fileno='$j->fileno' order by award_date";
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
          mysqli_query($con, "insert into hr_staff_country_visitedtb set fileno='$j->fileno',country='$j->country_name',visit_reason='".@mysqli_real_escape_string($con, $j->country_reason)."',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_country_visitedtb where fileno='$j->fileno' order by country";

          echo "<script> alert('The specified Country has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_country_visitedtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_country_visitedtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_country_visitedtb where fileno='$fileno' order by country";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_country_visitedtb where fileno='$j->fileno' order by country";
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
          mysqli_query($con, "insert into hr_staff_refereetb set fileno='$j->fileno',ref_name='".@mysqli_real_escape_string($con, $j->ref_name)."',ref_address='".@mysqli_real_escape_string($con, $j->ref_address)."',ref_occupation='$j->ref_occupation',ref_know_period='$j->ref_year',ref_email='$j->ref_email',ref_phone_no='$j->ref_phone_no',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));

          $sql="select * from hr_staff_refereetb where fileno='$j->fileno' order by id";

          echo "<script> alert('The specified Referee has been saved');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select * from hr_staff_refereetb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_refereetb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from hr_staff_refereetb where fileno='$fileno' order by id";
     }

     if($action=='view')
     {
          //
          $sql="select * from hr_staff_refereetb where fileno='$j->fileno' order by id";
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
}

if($id=='load_images')
{
     $appno=$_REQUEST['upload_appno'];
     //echo "EHRERRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRRR $appno";

     $sql="select * from hr_app_documenttb where appno='$appno' order by id";
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
          @mysqli_query($con, "delete from hr_app_documenttb where id='$r_id'");
          echo "<script>alert('The specified file has been deleted');</script>";
     }

     $sql="select * from hr_app_documenttb where appno='$appno' order by id";
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
}

if($id=='display_load_docs')
{
     $fileno=$_REQUEST['fileno'];
     $sql="select * from hr_app_documenttb where appno='$fileno' order by id";
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
               {   $res_w=@mysqli_query($con, "select * from stafftb where fileno='$fileno'");
                    $rs_w=@mysqli_fetch_array($res_w);
                    $d_name=$rs_w['spouse_name'];
                    if($rs_w['sex']=='Male') $d_sex="Female"; else $d_sex="Male";
                    if($rs_w['sex']=='Male') $d_relation="Wife"; else $d_relation="Husband"; $d_dob="";
               }
               elseif($code_val!='spouse')
               {   $res_w=@mysqli_query($con, "select * from hr_staff_childtb where id='$code_val'");
                    $rs_w=@mysqli_fetch_array($res_w);
                    $d_name=$rs_w['name'];
                    $d_sex=$rs_w['sex'];
                    $d_dob=$rs_w['date_of_birth'];
                    $d_relation="Child";
               }

               @mysqli_query($con, "insert into hr_staff_clinictb set fileno='$fileno',dependent_name='$d_name',dependent_dob='$d_dob',dependent_sex='$d_sex',dependent_relationship='$d_relation',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");

               $sql="select * from hr_staff_clinictb where fileno='$fileno' order by id";

          } //end of foreach
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_staff_clinictb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_staff_clinictb where id='$r_id'");
          $sql="select * from hr_staff_clinictb where fileno='$fileno' order by id";
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

}

if($id=='loan_section')
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $login_id=$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id"; ".@mysqli_real_escape_string($con,
     if($action=='save')
     {
          $loan_no=@generate_loan_no($j->loan_date);
          @mysqli_query($con, "insert into hr_loan_apptb set fileno='$j->fileno',loan_no='$loan_no',loan_type='$j->loan_type',loan_amount='$j->loan_amount',app_date='$j->loan_date',duration='$j->loan_duration',repay_start_date='$j->loan_start_date',repay_end_date='$j->loan_end_date',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");

          //insert into guarantor table
          @mysqli_query($con, "insert into hr_loan_guarantortb set loan_no='$loan_no',guarantor_fileno='$j->loan_1st_guarantor',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");

          @mysqli_query($con, "insert into hr_loan_guarantortb set loan_no='$loan_no',guarantor_fileno='$j->loan_2nd_guarantor',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");

          $sql="select * from  hr_loan_apptb where fileno='$j->fileno' order by id desc";

          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='search')
     {

          $sql="select * from  hr_loan_apptb where fileno='$j->fileno' order by id desc";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_loan_apptb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $loan_no=$rs_d['loan_no'];
          $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_loan_apptb where loan_no='$loan_no'");
          @mysqli_query($con, "delete from hr_loan_guarantortb where loan_no='$loan_no'");

          $sql="select * from  hr_loan_apptb where fileno='$fileno' order by id desc";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $g_total=0;
     $tb="<center><table><tr><th>S/N</th><th>TYPE OF LOAN</th><th>AMOUNT</th><th>APP. DATE</th><th>REPAYMENT START FROM</th><th>REPAYMENT END ON</th><th>APPROVAL STATUS</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['loan_type']}</td><td>{$rs_v['loan_amount']}</td><td>".@date('d/m/Y',strtotime($rs_v['app_date']))."</td><td>".@date('d/m/Y',strtotime($rs_v['repay_start_date']))."</td><td>".@date('d/m/Y',strtotime($rs_v['repay_end_date']))."</td><td>{$rs_v['process_status']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('loan_section','delete','$r_id');\">DELETE</a></td></tr>";
          }//end of while

          $tb.="</table></center>";
          echo $tb;
     }
     else
     echo "<center><b><font color='red'>No record to display</font></b></center>";

}

if($id=='accept_loan_guarantor')
{
     $loan_no=$_REQUEST['loan_no'];
     @mysqli_query($con, "update hr_loan_guarantortb set accept_date=CURDATE() where loan_no='$loan_no'");
     echo "<script>alert('You have successfully accepted to serve as the Guarantor for the staff');</script>";
}

if($id=='approve_loan')
{
     $loan_no=$_REQUEST['loan_no'];
     $login_id=$_SESSION['login_id'];
     @mysqli_query($con, "update hr_loan_apptb set process_date=CURDATE(),process_status='Approved',authorize_by='$login_id' where loan_no='$loan_no'");
     echo "<script>alert('You have successfully approve loan for the specified staff');</script>";
}

if($id=='view_guarantor')
{
     $loan_no=$_REQUEST['loan_no'];
     $res_v=@mysqli_query($con, "select * from hr_loan_guarantortb where loan_no='$loan_no'");
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

          $sql="select * from stafftb where fileno='$j->fileno'";

          echo "<script>alert('Staff record updated successfully');</script>";
     }

     if($action=='search')
     {

          $sql="select * from stafftb where fileno='$j->fileno'";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from stafftb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d);
          $fileno=$rs_d['fileno'];

          @mysqli_query($con, "update stafftb set regularisation_status='No',regularisation_date='0000-00-00',regularisation_entry_by='' where fileno='$fileno'");


          $sql="select * from stafftb where fileno='$fileno'";
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
          $res_ck=@mysqli_query($con, "select * from stafftb where fileno='$j->fileno'");
          $rs_ck=@mysqli_fetch_array($res_ck); $reg_status=$rs_ck['regularisation_status'];
          if($reg_status=='No')
          {  echo "<script>alert('Error: This staff has not been Regularized');</script>"; exit; }

          @mysqli_query($con, "update stafftb set confirmation_status='Yes',confirmation_date='$j->conf_date',confirmation_entry_by='$login_id' where fileno='$j->fileno'");

          $sql="select * from stafftb where fileno='$j->fileno'";

          echo "<script>alert('Staff record updated successfully');</script>";
     }

     if($action=='search')
     {

          $sql="select * from stafftb where fileno='$j->fileno'";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from stafftb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d);
          $fileno=$rs_d['fileno'];

          @mysqli_query($con, "update stafftb set confirmation_status='No',confirmation_date='0000-00-00',confirmation_entry_by='' where fileno='$fileno'");


          $sql="select * from stafftb where fileno='$fileno'";
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
}

if($id=='process_grievance')
{
     $r_id=@$_REQUEST['r_id'];
     $comment=@$_REQUEST['comment'];
     @mysqli_query($con, "update hr_grievancetb set reaction='".@mysqli_real_escape_string($con, $comment)."',reaction_date=CURDATE() where id='$r_id'");
     echo "<script>alert('Record updated successfully');</script>";
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
          $res_c=@mysqli_query($con, "select * from tb_name where fileno='$fileno' and disc_date='$disc_date' and disc_type='$disc_type'");
          if(@mysqli_num_rows($res_c)<=0)
          {
               @mysqli_query($con, "insert into hr_disciplinarytb set fileno='$fileno',disc_date='$disc_date',disc_type='$disc_type', disc_ref_no='$disc_ref_no',description='$description',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               echo "<script>alert('Record saved successfully');</script>";
               $sql="select * from hr_disciplinarytb where fileno='$fileno'";
          }

     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_postingtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno']; $ref_doc=$rs_d['ref_doc']; @unlink($ref_doc);
          @mysqli_query($con, "delete from hr_postingtb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="select * from hr_postingtb where fileno='$fileno'";
     }


     if($action=='view')
     {
          $sql="select * from hr_postingtb where fileno='$fileno' order by post_date desc";

     } //end of view

     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>FILE NO</th><th>STAFF NAME</th><th>DEPT. POSTED</th><th>UNIT POSTED</th><th>POSITION</th><th>DATE POSTED</th><th>ACTION</th></tr>";
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
          @mysqli_query($con, "insert into hr_status_historytb set fileno='$j->fileno',status='$j->status',description='".@mysqli_real_escape_string($con, $j->desc)."',entry_date='$j->date_updated',entry_time=CURTIME(),entry_by='$login_id'");

          //update staff table
          @mysqli_query($con, "update stafftb set status='$j->status' where fileno='$j->fileno'");

          $sql="select * from hr_status_historytb where fileno='$j->fileno' order by id desc";

          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='search')
     {

          $sql="select * from  hr_status_historytb where fileno='$j->fileno' order by id desc";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_status_historytb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d);
          $fileno=$rs_d['fileno'];

          @mysqli_query($con, "delete from hr_status_historytb where id='$r_id'");
          @mysqli_query($con, "update stafftb set status='Active' where fileno='$fileno'");

          $sql="select * from  hr_status_historytb where fileno='$fileno' order by id desc";
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

     $res_c=@mysqli_query($con, "select * from hr_positiontb order by category,position");
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

     $res_c=@mysqli_query($con, "select * from level_categorytb order by convert(level,decimal)");

     while($rs_c=@mysqli_fetch_array($res_c))

     {

          $level=@$rs_c['level'];

          $tb.="<option value='$level'>$level</option>";

     }

     $tb.="</select></td><th>Appointed Step</th><td><select name='step' id='step'>
     <option selected='selected' value=''>---</option>";

     $res_c=@mysqli_query($con, "select * from steptb order by convert(step,decimal)");

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

}

if($id=='generate_staff_number')
{
     $staff_status=@$_REQUEST['staff_status'];
     $fileno=@generate_staff_number($staff_status);
     echo "<input type='text' name='fileno' id='fileno' value='$fileno'/>";
}

if($id=='applicant_appointment_approval')
{
     $prev_dept_code=@$_REQUEST['prev_dept_code']; $prev_appno=@$_REQUEST['prev_appno']; $prev_position=@$_REQUEST['prev_position'];
     $staff_status=@$_REQUEST['staff_status']; $fileno=@$_REQUEST['fileno']; $rank_appointed=@$_REQUEST['rank_appointed'];
     $employment_status=@$_REQUEST['employment_status']; $level=@$_REQUEST['level']; $step=@$_REQUEST['step']; $salary=@$_REQUEST['salary'];
     $login_id=@$_SESSION['login_id'];
     //echo "$prev_dept_code $prev_appno $prev_position $staff_status $fileno $rank_appointed $employment_status $level $step $salary";
     // Load applicant data from applicant tables to staff tables
     @mysqli_query($con, "insert into stafftb (`appno`, `fileno`, `title`, `surname`, `first_name`, `other_name`, `maiden_name`, `sex`, `marital_status`, `religion`, `spouse_name`, `spouse_address`, `spouse_occupation`, `qualification`, `date_of_birth`, `nationality`, `state_id`, `lga_id`, `country`, `place_of_birth`, `senatorial_district`, `contact_address`, `residential_address`, `permanent_address`, `email`, `phone_no`, `next_name`, `next_address`, `next_email`, `next_phone_no`, `acct_no`, `bank_name`, `next_relationship`, `guidance_name`, `guidance_state`, `guidance_nationality`, `guidance_occupation`, `guidance_address`, `guidance_email`, `guidance_phone_no`, `mother_name`, `mother_state`, `mother_nationality`, `mother_address`, `last_place_of_residence`, `passport_number`, `passport_place`, `passport_date_issue`, `languages_spoken`, `disability`, `disability_reason`, `court_case`, `hobbies`, `security_forces`, `force_no`, `highest_force_rank`, `force_period`, `force_character`, `status`, `app_year`, `password`) select  `appno`, `appno`, `title`, `surname`, `first_name`, `other_name`, `maiden_name`, `sex`, `marital_status`, `religion`, `spouse_name`, `spouse_address`, `spouse_occupation`, `qualification`, `date_of_birth`, `nationality`, `state_id`, `lga_id`, `country`, `place_of_birth`, `senatorial_district`, `contact_address`, `residential_address`, `permanent_address`, `email`, `phone_no`, `next_name`, `next_address`, `next_email`, `next_phone_no`, `acct_no`, `bank_name`, `next_relationship`, `guidance_name`, `guidance_state`, `guidance_nationality`, `guidance_occupation`, `guidance_address`, `guidance_email`, `guidance_phone_no`, `mother_name`, `mother_state`, `mother_nationality`, `mother_address`, `last_place_of_residence`, `passport_number`, `passport_place`, `passport_date_issue`, `languages_spoken`, `disability`, `disability_reason`, `court_case`, `hobbies`, `security_forces`, `force_no`, `highest_force_rank`, `force_period`, `force_character`, `status`, `app_year`, `password` from hr_applicanttb where appno='$prev_appno'");

     $staff_category=@get_position_category($rank_appointed);
     $dept_str=@explode('***',@get_position_dept($prev_appno,$prev_dept_code,$prev_position));
     $staff_dept_code=$dept_str[0]; $staff_unit_code=$dept_str[1];

     @mysqli_query($con, "update stafftb set staff_status='$staff_status',category='$staff_category',level='$level',step='$step',initial_level='$level',initial_step='$step',initial_salary='$salary',rank='$rank_appointed',post_of_1st_appt='$rank_appointed',present_salary='$salary',employment_status='$employment_status',dept_code='$staff_dept_code',unit_code='$staff_unit_code',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where appno='$prev_appno'");

     //insert into other application tables
     mysqli_query($con, "insert into hr_staff_academic_edutb (`fileno`, `school_name`, `school_type`, `qualification`, `degree_class`, `from_month`, `from_year`, `to_month`, `to_year`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `school_name`, `school_type`, `qualification`, `degree_class`, `from_month`, `from_year`, `to_month`, `to_year`, `entry_date`, `entry_time`, `entry_by` from hr_app_academic_edutb where appno='$prev_appno'") or die( mysqli_error($con));

     @mysqli_query($con, "insert into hr_staff_childtb (`fileno`, `name`, `date_of_birth`, `sex`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `name`, `date_of_birth`, `sex`, `entry_date`, `entry_time`, `entry_by` from hr_app_childtb where appno='$prev_appno'");

     mysqli_query($con, "insert into hr_staff_country_visitedtb (`fileno`, `country`, `visit_reason`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `country`, `visit_reason`, `entry_date`, `entry_time`, `entry_by` from hr_app_country_visitedtb where appno='$prev_appno'") or die( mysqli_error($con));

     @mysqli_query($con, "insert into hr_staff_employmenttb (`fileno`, `employer_name`, `location`, `rank`, `salary`, `from_year`, `to_year`, `leaving_reason`, `employment_type`, `status`, `duty`, `bond_question`, `release_question`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `employer_name`, `location`, `rank`, `salary`, `from_year`, `to_year`, `leaving_reason`, `employment_type`, `status`, `duty`, `bond_question`, `release_question`, `entry_date`, `entry_time`, `entry_by` from hr_app_employmenttb where appno='$prev_appno'");

     mysqli_query($con, "insert into hr_staff_prof_membershiptb (`fileno`, `name`, `category`, `year_honoured`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `name`, `category`, `year_honoured`, `entry_date`, `entry_time`, `entry_by` from hr_app_prof_membershiptb where appno='$prev_appno'") or die( mysqli_error($con));

     mysqli_query($con, "insert into hr_staff_prof_qualificationtb (`fileno`, `name`, `grade`, `from_year`, `to_year`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `name`, `grade`, `from_year`, `to_year`, `entry_date`, `entry_time`, `entry_by` from hr_app_prof_qualificationtb where appno='$prev_appno'") or die( mysqli_error($con));

     @mysqli_query($con, "insert into hr_staff_publicationtb (`fileno`, `title`, `author`, `type`, `publisher`, `journal`, `year_published`, `status`, `category`, `page_no`, `volume`, `issue`, `url`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `title`, `author`, `type`, `publisher`, `journal`, `year_published`, `status`, `category`, `page_no`, `volume`, `issue`, `url`, `entry_date`, `entry_time`, `entry_by` from hr_app_publicationtb where appno='$prev_appno'");

     mysqli_query($con, "insert into hr_staff_recognitiontb (`fileno`, `award_type`, `award_date`, `award_description`, `prize`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `award_type`, `award_date`, `award_description`, `prize`, `entry_date`, `entry_time`, `entry_by` from hr_app_recognitiontb where appno='$prev_appno'") or die( mysqli_error($con));

     mysqli_query($con, "insert into hr_staff_refereetb (`fileno`, `ref_name`, `ref_address`, `ref_occupation`, `ref_know_period`, `ref_email`, `ref_phone_no`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `ref_name`, `ref_address`, `ref_occupation`, `ref_know_period`, `ref_email`, `ref_phone_no`, `entry_date`, `entry_time`, `entry_by` from hr_app_refereetb where appno='$prev_appno'") or die( mysqli_error($con));

     mysqli_query($con, "insert into hr_staff_researchtb (`fileno`, `topic`, `status`, `funding_source`, `project_value`, `start_date`, `end_date`, `amount_granted`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `topic`, `status`, `funding_source`, `project_value`, `start_date`, `end_date`, `amount_granted`, `entry_date`, `entry_time`, `entry_by` from hr_app_researchtb where appno='$prev_appno'") or die( mysqli_error($con));

     mysqli_query($con, "insert into hr_staff_servicetb (`fileno`, `service_type`, `service_place`, `service_details`, `from_year`, `to_year`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `service_type`, `service_place`, `service_details`, `from_year`, `to_year`, `entry_date`, `entry_time`, `entry_by` from hr_app_servicetb where appno='$prev_appno'") or die( mysqli_error($con));

     @mysqli_query($con, "insert into hr_staff_training_apptb (`fileno`, `training_type`, `start_date`, `end_date`, `training_title`, `theme`, `location`, `venue`, `no_paper_read`, `sponsor`, `amount_granted`, `ref_doc`, `approval_status`, `approval_date`, `process_by`, `entry_date`, `entry_time`, `entry_by`) select `appno`, `training_type`, `start_date`, `end_date`, `training_title`, `theme`, `location`, `venue`, `no_paper_read`, `sponsor`, `amount_granted`, `ref_doc`, `approval_status`, `approval_date`, `process_by`, `entry_date`, `entry_time`, `entry_by` from hr_app_training_apptb where appno='$prev_appno'");

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


}

if($id=='rollback_applicant')
{
     $appno=@$_REQUEST['appno'];
     $res_s=@mysqli_query($con, "select appno,fileno from stafftb where appno='$appno'");
     $rs_s=@mysqli_fetch_array($res_s);
     $fileno=@$rs_s['fileno'];
     //echo "APP NO: $appno FILENO: $fileno";

     ///rollback data
     @mysqli_query($con, "delete from stafftb where fileno='$fileno'");

     @mysqli_query($con, "delete from hr_staff_academic_edutb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_childtb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_country_visitedtb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_employmenttb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_prof_membershiptb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_prof_qualificationtb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_publicationtb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_recognitiontb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_refereetb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_researchtb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_servicetb where fileno='$fileno'");
     @mysqli_query($con, "delete from hr_staff_training_apptb where fileno='$fileno'");

     @logs($login_id,"Appointment Rollback","$login_id rollback appointment for staff $fileno");

     echo "<script>alert('Rollback operation is successful');swapcontent('display_applicant');</script>";

}

if($id=='load_staff_assumption')
{
     $fileno=$_REQUEST['fileno'];
     $res_s=@mysqli_query($con, "select * from stafftb where fileno='$fileno'");
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

          $sql="select * from stafftb where fileno='$j->fileno'";

          echo "<script>alert('Staff record updated successfully');</script>";
     }

     if($action=='search')
     {

          $sql="select * from stafftb where fileno='$j->fileno'";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from stafftb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d);
          $fileno=$rs_d['fileno'];

          @logs($login_id,"Delete staff assumption","$login_id delete staff assumption for staff $fileno");

          @mysqli_query($con, "update stafftb set date_of_1st_appt='0000-00-00',date_of_present_appt='0000-00-00',date_of_assumption='0000-00-00',acct_no='',bank_name='' where fileno='$fileno'");


          $sql="select * from stafftb where fileno='$fileno'";
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

}
//////////////////////////////////End of human resources management system /////////////////////////////////////////

if($id=="inmails")
{
     /*echo "<script>alert('am here!'); </script>";
     echo "<p style='color:red;'>am here!'</p>";
     echo $_REQUEST['contentvar']."<br>";
     echo $_REQUEST['memo_from']."<br>";
     echo $_REQUEST['desc']."<br>";
     echo $_REQUEST['amount'];*/
     //exit;

     $index = $_REQUEST['tabindex'];
     //$fno=@$_REQUEST['regno'];
     //echo $fno; exit;
     //$formcontent=@$_REQUEST['formcontent'];
     //$referee = json_decode($formcontent);
     /*$town=@$_REQUEST['town'];
     $from_date=@$_REQUEST['p_fromdate'];
     $to_date=@$_REQUEST['p_todate'];
     $category=@$_REQUEST['category'];
     $added_date=@date('Y-m-d'); $added_time=@date('h:s:i a');
     $session=@$_SESSION['putme_session'];*/
     echo "<script>
     $('#tt').tabs('select', $index);</script>";


     $memo_from =  mysqli_real_escape_string($con, $_REQUEST['memo_from']);
     $descs =  mysqli_real_escape_string($con, $_REQUEST['desc']);
     $amount =  mysqli_real_escape_string($con, $_REQUEST['amount']);
     $memo_id=  mysqli_real_escape_string($con, $_REQUEST['memo_id']); //date('dmY').rand();dept_unit
     $dept_unit =  mysqli_real_escape_string($con, $_REQUEST['dept_unit']);
     $login_id =  mysqli_real_escape_string($con, $_REQUEST['login_id']);
     $action = 'Recieved from Bursary';
     $remark = 'First Entry';
     if($memo_from == ""){
          echo "Memo source (Memo From) is required!"; exit;
     }
     if($descs == ""){
          echo "Memo description is required!"; exit;
     }
     if(!is_numeric($amount) && $amount != ""){
          echo "Amount on memo must be numeric data!"; exit;
     }
     if($memo_id == ""){
          echo "No Memo ID generated!"; exit;
     }

     $res_check=@mysqli_query($con, "select * from memotb where memo_from like '%$memo_from%' and description like '%$descs%' and amount='$amount'");
     $numrow= @mysqli_num_rows($res_check);
     if($numrow == 1){echo "<script>alert('This Record has been added before.');</script>";exit;}
     //$rs_check = @mysqli_fetch_array($res_check);

     if( mysqli_query($con, "insert into  memotb set memo_id='$memo_id', memo_from='$memo_from', description='$descs', amount='$amount', datein=Now(),entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con)))
     {
          mysqli_query($con, "insert into  memo_movementtb set memo_id='$memo_id', memo_status='IN', dept_unit='$dept_unit', date=Now(),action='$action', remark ='$remark', entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con));

          echo 	"<script>alert('Record Saved Succesfully'); </script>";
     }
else{echo "<script>alert('Record NOT saved successfully');</script>"; /*exit;*/}
}
// or die( mysqli_error($con));
//@mysqli_query($con, "update candidatetb set reg_step='Institution' where regno='$fno' limit 1");
//echo $index; exit;


//++++++++++++++$('#tt').tabs('enableTab', 'Preliminary'); $('#tt').tabs('select', 1);++++++++++++++
/*	echo "<script>$('#tt').tabs('enableTab', 'Preliminary'); $('#tt').tabs('select', 1);</script>";*/


if($id=="outmail")
{
     $index = $_REQUEST['tabindex'];
     /*echo "<script>alert('am here!'); </script>";
     echo "<p style='color:red;'>am here!'</p>";
     echo $_REQUEST['contentvar']."<br>";
     */
     "<script>
     $('#tt').tabs('select', $index);</script>";
     $unit_id =  mysqli_real_escape_string($con, $_REQUEST['unit']);
     $remark =  mysqli_real_escape_string($con, $_REQUEST['remark']);
     $movement =  mysqli_real_escape_string($con, $_REQUEST['movement']);
     $login_id =  mysqli_real_escape_string($con, $_REQUEST['login_id']);
     $action =  mysqli_real_escape_string($con, $_REQUEST['action']);
     $staff_category =  mysqli_real_escape_string($con, $_REQUEST['staff_category']);

     $res_check=@mysqli_query($con, "select * from memo_movementtb where memo_id='$movement' and memo_status='OUT'");
     $numrow= @mysqli_num_rows($res_check);
     if($numrow == 1){echo "<script>alert('This Record has been added.');</script>";exit;}
     //$rs_check = @mysqli_fetch_array($res_check);

     if( mysqli_query($con, "insert into  memo_movementtb set memo_id='$movement', memo_status='OUT', dept_unit='$staff_category', date=Now(),action='$action', remark ='$remark', entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con)))

     {
          mysqli_query($con, "insert into  memo_movementtb set memo_id='$movement', memo_status='IN', dept_unit='$unit_id', date=Now(),action='$action', remark ='$remark', entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con));

          echo 	"<script>alert('Memo Sent'); </script>";
     }
else{echo "<script>alert('Memo NOT sent !!!');</script>"; /*exit;*/}
}/////// end of outmail query

/////////////////////////////// code for query out_query
if($id=="out_query")
{

     /*echo "<script>alert('am here!'); </script>";
     echo "<p style='color:red;'>am here!'</p>";
     exit;
     echo $_REQUEST['contentvar']."<br>";*/

     $index = $_REQUEST['tabindex'];
     "<script>
     $('#tt').tabs('select', $index);</script>";
     $unit_id =  mysqli_real_escape_string($con, $_REQUEST['unit']);
     $remark =  mysqli_real_escape_string($con, $_REQUEST['remark']);
     $movement =  mysqli_real_escape_string($con, $_REQUEST['movement']);
     $login_id =  mysqli_real_escape_string($con, $_REQUEST['login_id']);
     $action =  mysqli_real_escape_string($con, $_REQUEST['action']);
     $staff_category =  mysqli_real_escape_string($con, $_REQUEST['staff_category']);


     $res_check2=@mysqli_query($con, "select * from memo_querytb where memo_id='$movement' and status='Query' ");
     $numrow2= @mysqli_num_rows($res_check2);

     if($numrow2 == 1){echo "<script>alert('This Record has been added.');</script>";
          exit;}


          if( mysqli_query($con, "insert into  memo_movementtb set memo_id='$movement', memo_status='OUT', dept_unit='$staff_category', date=Now(),action='$action', remark ='$remark', entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con)))

          {
               mysqli_query($con, "insert into  memo_querytb set memo_id='$movement', dept_unit='$staff_category', remark ='$remark', date=Now(), time=Now(), status='Query', entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con));

               mysqli_query($con, "update memotb set memo_status='Query' where memo_id='$movement'") or die ( mysqli_error($con));

               echo 	"<script>alert('Memo Queried Successfully'); </script>";
          }
     else{echo "<script>alert('Memo NOT Queried Successfully');</script>"; /*exit;*/}
}/////// end of outmail query

if($id=='assign_memo')
{
     //if(!is_numeric($_REQUEST['dept_unit']))
     $dept_unit=$bursary->get_user_data(@$_SESSION['login_id'], "unit_code");// $_REQUEST['dept_unit'];


     //exit;
     //exit;
     //$users = '973';
     //$users = $_REQUEST['user'];

     $res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein,m.amount_approved from memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and mv.head_b = '1' and m.memo_status='In Progress' order by mv.memo_id desc");
     //echo  mysqli_error($con); exit;
     //$res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein, from (inner join memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id (inner join memo_assigntb ma on ma.memo_id = m.memo_id)where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' and ma.memo_assigntb='Pending')");
     $json_response=array();
     while($rs_c2=@mysqli_fetch_array($res_c))
     {
          $mm =  mysqli_escape_string($rs_c2['memo_id']);
          $ch =  mysqli_query($con, "select memo_id from  memo_assigntb where memo_id = '$mm'");
          $count =  mysqli_num_rows($ch);
          if ($count>0)
          continue;

          $row_array['memo_from']=@$rs_c2['memo_from'];
          $row_array['memo_id']=@$rs_c2['memo_id'];
          $row_array['description']=@$rs_c2['description'];
          $row_array['amount']=@$rs_c2['amount'];
          $row_array['datein']=@$rs_c2['datein'];
          $row_array['amount_approved']=@$rs_c2['amount_approved'];
          array_push($json_response,$row_array);
     }
     echo json_encode($json_response);


}
if($id=='assign_memo2')
{
     $dept_unit=$bursary->get_user_data(@$_SESSION['login_id'], "unit_code");//$dept_unit= $_REQUEST['dept_unit'];
     //$users = '973';
     //$users = $_REQUEST['user'];

     $res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein, ma.status, ma.assignto, s.surname from memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id inner join memo_assigntb ma on mv.memo_id=ma.memo_id inner join stafftb s on ma.assignto=s.fileno where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' order by m.datein desc");
     //echo  mysqli_error($con); exit;
     //$res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein, from (inner join memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id (inner join memo_assigntb ma on ma.memo_id = m.memo_id)where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' and ma.memo_assigntb='Pending')");
     $json_response=array();
     while($rs_c2=@mysqli_fetch_array($res_c))
     {
          $row_array['memo_from']=@$rs_c2['memo_from'];
          $row_array['memo_id']=@$rs_c2['memo_id'];
          $row_array['description']=@$rs_c2['description'];
          $row_array['amount']=@$rs_c2['amount'];
          $row_array['datein']=@$rs_c2['datein'];
          $row_array['status']=@$rs_c2['status'];
          $row_array['surname']=@$rs_c2['surname'];
          $row_array['fileno']=@$rs_c2['assignto'];
          array_push($json_response,$row_array);
     }
     echo json_encode($json_response);


}
if($id=="assignmail") //////////////////// begin of mail distribution
{
     /*echo "<script>alert('am here!'); </script>";
     echo "<p style='color:red;'>am here!'</p>";
     exit;*/
     //echo $_REQUEST['contentvar']."<br>";

     $index = $_REQUEST['tabindex'];
     "<script>
     $('#tt').tabs('select', $index);</script>";
     $staff_id =  mysqli_real_escape_string($con, $_REQUEST['staff']);
     $memoid =  mysqli_real_escape_string($con, $_REQUEST['memoid']);
     if ($staff_id=='' or $memoid== ''){
          echo "<script>alert('Select Staff and Memo to Assign !!!'); </script>";
          exit;
     }
     $unit = $bursary->get_user_data($staff_id, "unit_code"); // mysqli_real_escape_string($con, $_REQUEST['unit']);
     // $unit= 'Expenditure';
     $read= 'Read';
     $login_id =  mysqli_real_escape_string($con, $_REQUEST['login_id']);

     $ids = explode(',', $memoid);

     //insert request record to database
     foreach ($ids as $movements)
     {

          mysqli_query($con, "insert into memo_assigntb set memo_id='$movements', assignto='$staff_id', date_assign =Now(), entrydate=Now(), entrytime=Now(), entryby='$login_id', unit='$unit'") or die ( mysqli_error($con));
          mysqli_query($con, "update memo_movementtb set read_status= '$read',read_date=Now(),read_time=Now() where memo_id='$movements'  ")or die ( mysqli_error($con));

     }
     // mysqli_query($con, "update memo_movementtb set read_status= Read ")or die ( mysqli_error($con));
     echo 	"<script>alert('Memo Assigned Successfully'); </script>";
     exit;

}/////// end of mail distribution
if($id=='mail_user')// start of mail user page
{

     $users = $_REQUEST['user'];
     $res_c=@mysqli_query($con, "select ma.memo_id,ma.status, m.memo_from, m.description, m.amount, m.datein, s.surname from memo_assigntb ma inner join memotb m  on ma.memo_id=m.memo_id inner join stafftb s on ma.assignto=s.fileno where ma.assignto = '$users' and m.memo_status='In Progress' and ma.status='Pending' order by ma.id desc");
     //echo  mysqli_error($con); exit;
     //$res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein, from (inner join memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id (inner join memo_assigntb ma on ma.memo_id = m.memo_id)where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' and ma.memo_assigntb='Pending')");
     $json_response=array();
     while($rs_c2=@mysqli_fetch_array($res_c))
     {
          $row_array['memo_from']=@$rs_c2['memo_from'];
          $row_array['memo_id']=@$rs_c2['memo_id'];
          $row_array['description']=@$rs_c2['description'];
          $row_array['amount']=@$rs_c2['amount'];
          $row_array['datein']=@$rs_c2['datein'];
          $row_array['status']=@$rs_c2['status'];
          $row_array['surname']=@$rs_c2['surname'];
          array_push($json_response,$row_array);
     }
     echo json_encode($json_response);
}


if($id=="raise_v") //////////////////// begin of setting to assigned mail
{
     /*	echo "<script>alert('am here!'); </script>";
     echo "<p style='color:red;'>am here!'</p>";
     exit;
     //echo $_REQUEST['contentvar']."<br>"; */

     $index = $_REQUEST['tabindex'];
     "<script>
     $('#tt').tabs('select', $index);</script>";

     $memoid =  mysqli_real_escape_string($con, $_REQUEST['tmemoid']);

     $ids = explode(',', $memoid);
     if ($ids== ''){
          echo "<script>alert('Select Memo to Unassign !!!'); </script>";
          exit;
     }
     foreach ($ids as $movements) {
          mysqli_query($con, "delete from memo_assigntb where  memo_id='$movements'") or die ( mysqli_error($con));
     }
     echo "<script>alert('Memo Unassigned Successfully !!!'); </script>";

     //echo "Memo Unassigned Successfully";
     exit;

}/////// end of set mail as assinged

if($id=="raise_vs") //////////////////// begin of sending to voucher
{
     /*	echo "<script>alert('am here!'); </script>";
     echo "<p style='color:red;'>am here!'</p>";
     exit;
     //echo $_REQUEST['contentvar']."<br>"; */

     $index = $_REQUEST['tabindex'];
     "<script>
     $('#tt').tabs('select', $index);</script>";

     $memoid =  mysqli_real_escape_string($con, $_REQUEST['tmemoid']);
     if ($memoid== ''){
          echo "<script>alert('Select Memo to Raise Vourcher for !!!'); </script>";
          exit;
     }
     $memoid2 = base64_encode($memoid);

     echo "<script>location='voucher.php?id=$memoid2'; </script>";
     //header("Location: http://bursary.unilorin.edu.ng/voucher.php?id='$memoid2'");
     //echo "Rasie Vouche for : ";
     //echo $memoid =  mysqli_real_escape_string($con, $_REQUEST['tmemoid']);
     exit;


     //insert request record to database
     foreach ($ids as $movements)
     {

          mysqli_query($con, "insert into  memo_assigntb set memo_id='$movements', assignto='$staff_id', date_assign =Now(), entrydate=Now(), entrytime=Now(), entryby='$login_id', unit='$unit'") or die ( mysqli_error($con));

     }
     echo 	"<script>alert('Memo Assigned Successfully'); </script>";
     exit;

}/////// end of sending to voucher

if($id=='treat_mail')// start of mail user page
{

     $users = $_REQUEST['user'];
     $res_c=@mysqli_query($con, "select ma.memo_id,v.payee_type,v.payee_name,v.payee_acct_no,v.amount_approved from memo_assigntb ma inner join vouchertb v  on ma.memo_id=v.memo_id where ma.assignto = '$users' and ma.status='Completed' order by ma.id desc");
     //echo  mysqli_error($con); exit;
     //$res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein, from (inner join memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id (inner join memo_assigntb ma on ma.memo_id = m.memo_id)where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' and ma.memo_assigntb='Pending')");
     $json_response=array();
     while($rs_c2=@mysqli_fetch_array($res_c))
     {
          $row_array['payee_type']=@$rs_c2['payee_type'];
          $row_array['memo_id']=@$rs_c2['memo_id'];
          $row_array['payee_name']=@$rs_c2['payee_name'];
          $row_array['payee_acct_no']=@$rs_c2['payee_acct_no'];
          $row_array['amount_approved']=@$rs_c2['amount_approved'];
          array_push($json_response,$row_array);
     }
     echo json_encode($json_response);
}

if($id=="treats") //////////////////// delete vourcher created by Expenditure
{
     /*	echo "<script>alert('am here!'); </script>";
     echo "<p style='color:red;'>am here!'</p>";
     exit;
     //echo $_REQUEST['contentvar']."<br>"; */

     $index = $_REQUEST['tabindex'];
     "<script>
     $('#tt').tabs('select', $index);</script>";

     $memoid =  mysqli_real_escape_string($con, $_REQUEST['memoid']);
     //exit;
     $ids = explode(',', $memoid);
     //exit;
     if ($memoid== ''){
          echo "<script>alert('Select Vourcher to Delete !!!'); </script>";
          exit;
     }
     //insert request record to database
     //echo $memoid;
     foreach ($ids as $movements) {

          $res_check2= mysqli_query($con, "select pvno from vouchertb where memo_id = '$movements' and (checked_by !='' or checked_remark !='') ") or die ( mysqli_error($con));
          $numrow2=  mysqli_num_rows($res_check2);
          //exit;
          if($numrow2 == 0)
          {
               $rs_jamb= mysqli_fetch_array($res_check2);
               $pvno = $rs_jamb['pvno'];

               mysqli_query($con, "delete from voucher_taxtb where  pvno='$pvno'") or die ( mysqli_error('Could not delete from voucher_taxtb'));
               mysqli_query($con, "delete from voucher_parent_child_taxtb where  parent_pvno='$pvno'") or die ( mysqli_error('Could not delete from voucher_parent_child_taxtb'));
               mysqli_query($con, "delete from voucher_folio_codetb where  pvno like '$pvno%'") or die ( mysqli_error('Could not delete from voucher_folio_codetb'));
               mysqli_query($con, "delete from vouchertb where  pvno like '$pvno%'") or die ( mysqli_error('Could not delete from vouchertb'));
               mysqli_query($con, "update memo_assigntb set status= 'Pending' where memo_id='$movements'  ")or die ( mysqli_error($con));

               echo "<script>alert('Record Deleted and Updated Successfully')</script>";
               exit;}
               else{
                    echo "<script>alert('This Record can not be deleted because it has been worked upon !!!')</script>";
                    exit;
               }
          }

     }/////// end delete vourcher created by Expenditure
     if($id=='assign_look') ///////
     {
          //$dept_unit= $_REQUEST['dept_unit'];
          @$xdept_unit = $bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
          //exit;
          //exit;
          //$users = '973';
          //$users = $_REQUEST['user'];

          $res_c=@mysqli_query($con, "select mv.memo_id, mv.dept_unit, m.memo_from, m.description, m.amount, m.datein,m.amount_approved from memo_movementtb mv inner join memotb m on mv.memo_id=m.memo_id where mv.dept_unit = '$xdept_unit' and mv.head_b = '0' and (mv.memo_status = 'IN' or mv.memo_status = 'RECEIVED') and m.memo_status='In Progress' order by m.id desc") or die("Unknown Error!");
          //echo  mysqli_error($con); exit;
          //$res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein, from (inner join memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id (inner join memo_assigntb ma on ma.memo_id = m.memo_id)where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' and ma.memo_assigntb='Pending')");
          $json_response=array();
          while($rs_c2=@mysqli_fetch_array($res_c))
          {
               $ch =  mysqli_query($con, "select memo_id from memo_assigntb where memo_id = '".$rs_c2['memo_id']."'");
               if ( mysqli_num_rows($ch)>0) continue;

               $row_array['memo_from']=@$rs_c2['memo_from'];
               $row_array['memo_id']=@$rs_c2['memo_id'];
               $row_array['description']=@$rs_c2['description'];
               $row_array['amount']=@$rs_c2['amount'];
               $row_array['datein']=@$rs_c2['datein'];
               $row_array['amount_approved']=@$rs_c2['amount_approved'];
               $row_array['dept_unit']=@$rs_c2['dept_unit'];
               array_push($json_response,$row_array);
          }
          echo json_encode($json_response);


     }
     if($id=="assignmail_look") //////////////////// begin of mail distribution by head to sub head
     {
          /*echo "<script>alert('am here!'); </script>";
          echo "<p style='color:red;'>am here!'</p>";
          exit;*/
          //echo $_REQUEST['contentvar']."<br>";

          $index = $_REQUEST['tabindex'];
          "<script>
          $('#tt').tabs('select', $index);</script>";
          //$staff_id =  mysqli_real_escape_string($con, $_REQUEST['staff']);
          $memoid =  mysqli_real_escape_string($con, $_REQUEST['memoid']);
          if ($memoid== ''){
               echo "<script>alert('Select Memo to Assign !!!'); </script>";
               exit;
          }
          //$unit =  mysqli_real_escape_string($con, $_REQUEST['unit']);
          @$unit = $bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
          // $unit= 'Expenditure';
          $read= 'Read';
          $login_id =  mysqli_real_escape_string($con, $_REQUEST['login_id']);

          $ids = explode(',', $memoid);

          //insert request record to database
          foreach ($ids as $movements)
          {

               // mysqli_query($con, "insert into  memo_assigntb set memo_id='$movements', assignto='$staff_id', date_assign =Now(), entrydate=Now(), entrytime=Now(), entryby='$login_id', unit='$unit'") or die ( mysqli_error($con));
               mysqli_query($con, "update memo_movementtb set head_b = '1', read_status= '$read',read_date=Now(),read_time=Now() where memo_id='$movements' and dept_unit='$unit' and memo_status='IN' ")or die ( mysqli_error($con));

          }
          // mysqli_query($con, "update memo_movementtb set read_status= Read ")or die ( mysqli_error($con));
          echo 	"<script>alert('Memo successfully posted to Unit Chief Accountant!'); </script>";
          exit;

     }/////// end of mail distribution by head to sub head

     if($id=='assign_memo_look')
     {
          //$dept_unit= $_REQUEST['dept_unit'];
          @$dept_unit = $bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
          //$users = '973';
          //$users = $_REQUEST['user'];

          $res_c=@mysqli_query($con, "select mv.memo_id, mv.dept_unit, m.memo_from, m.description, m.amount, m.datein, m.amount_approved from memo_movementtb mv inner join memotb m on mv.memo_id=m.memo_id where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' and mv.head_b='1' order by m.id desc");
          //echo  mysqli_error($con); exit;
          //$res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein, from (inner join memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id (inner join memo_assigntb ma on ma.memo_id = m.memo_id)where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' and ma.memo_assigntb='Pending')");
          $json_response=array();
          while($rs_c2=@mysqli_fetch_array($res_c))
          {
               $row_array['memo_from']=@$rs_c2['memo_from'];
               $row_array['memo_id']=@$rs_c2['memo_id'];
               $row_array['description']=@$rs_c2['description'];
               $row_array['amount']=@$rs_c2['amount'];
               $row_array['datein']=@$rs_c2['datein'];
               $row_array['amount_approved']=@$rs_c2['amount_approved'];
               $row_array['dept_unit']=@$rs_c2['dept_unit'];

               array_push($json_response,$row_array);
          }
          echo json_encode($json_response);


     }

     if($id=='read_memo_query')
     {
          //$dept_unit= $_REQUEST['dept_unit'];
          @$dept_unit = $bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
          //$users = '973';
          //$users = $_REQUEST['user'];
          //$res_c=  mysqli_query($con, "select * from memotb where memo_id = 'BURSARS/009' ");
          $res_c=@mysqli_query($con, "select mq.memo_id, mq.dept_unit, m.memo_from, m.description, m.amount, m.datein, m.amount_approved from memotb m inner join memo_querytb mq on m.memo_id = mq.memo_id where mq.dept_unit = '$dept_unit' and mq.status = 'Queried' order by mq.date desc");
          $json_response=array();
          while($rs_c2=@mysqli_fetch_array($res_c, 3 ))
          {
               $row_array['memo_from']=@$rs_c2['memo_from'];
               $row_array['memo_id']=@$rs_c2['memo_id'];
               $row_array['description']=@$rs_c2['description'];
               $row_array['amount']=@$rs_c2['amount'];
               $row_array['datein']=@$rs_c2['datein'];
               $row_array['amount_approved']=@$rs_c2['amount_approved'];
               $row_array['dept_unit']=@$rs_c2['dept_unit'];

               array_push($json_response,$row_array);
          }
          echo json_encode($json_response);
     }

     if($id=="query_memo"){ //START MEMO QUERY
          $index = $_REQUEST['tabindex'];
          /*echo "<script>alert('Please select a memo!".$_REQUEST['ope_action']."'); </script>"; exit;*/
          if(!isset($_REQUEST['tmemoidq']) || $_REQUEST['tmemoidq']==''){
               echo "<script>alert('Please select a memo!'); </script>"; exit;
          }
          "<script>$('#tt').tabs('select', $index);</script>";
          /*	echo "<br>".$unit_id =  mysqli_real_escape_string($con, $_REQUEST['unitx']);
          echo "<br>".$remark =  mysqli_real_escape_string($con, $_REQUEST['remark']);
          echo "<br>".$memo_id =  mysqli_real_escape_string($con, $_REQUEST['tmemoidq']);
          echo "<br>".$login_id =  mysqli_real_escape_string($con, $_REQUEST['login_idq']);
          echo "<br>".$action =  mysqli_real_escape_string($con, $_REQUEST['action']);
          echo "<br>".$staff_category =  mysqli_real_escape_string($con, $_REQUEST['staff_category']);
          echo "<br>".$memo_unit_code =  mysqli_real_escape_string($con, $_REQUEST['qmemo_unit_code']);
          echo "<br>".$amount_app =  mysqli_real_escape_string($con, $_REQUEST['qmemoamountapp']);
          echo "<br>".$amount_app =  mysqli_real_escape_string($con, $_REQUEST['qmemoamountreq']);
          exit;
          */
          $unit_id =  mysqli_real_escape_string($con, $_REQUEST['unitx']);
          $remark =  mysqli_real_escape_string($con, $_REQUEST['remark']);
          $memo_id =  mysqli_real_escape_string($con, $_REQUEST['tmemoidq']);
          $login_id =  mysqli_real_escape_string($con, $_REQUEST['login_idq']);
          $action =  mysqli_real_escape_string($con, $_REQUEST['ope_action']);
          $staff_category =  mysqli_real_escape_string($con, $_REQUEST['staff_category']);
          $memo_unit_code =  mysqli_real_escape_string($con, $_REQUEST['qmemo_unit_code']);
          $amount_app =  mysqli_real_escape_string($con, $_REQUEST['qmemoamountapp']);
          $amount_req =  mysqli_real_escape_string($con, $_REQUEST['qmemoamountreq']);

          if($memo_id == ""){
               echo "<script>alert('No memo selected. Memo ID is required!');</script>"; exit;
          }
          if(!is_numeric($amount_app) && $amount_app != ""){
               echo "<script>alert('Amount on memo must be numeric data!');</script>"; exit;
          }
          if($action == "Queried"){
               //process for quering memo
               if($remark == ""){
                    echo "<script>alert('Comment is required for Queried memo!');</script>"; exit;
               }
               echo "<script>alert('Comment is required for Queried memo!');</script>";
               if( mysqli_query($con, "insert into  memo_movementtb set memo_id='$memo_id', memo_status='OUT', ".
               "dept_unit='$memo_unit_code', date=Now(),action='$action', remark ='$remark', ".
               "entry_date=Now(), entry_time=Now(), entry_by='$login_id'") or die ( mysqli_error($con)))
               {
                    mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id='$memo_id'");
                    //update read status on memo_movementtb before inserting new record
                    mysqli_query($con, "insert into memo_querytb set memo_id='$memo_id', dept_unit='$memo_unit_code', ".
                    "remark ='$remark', date=Now(), time=Now(), status='Queried', ".
                    "entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con));
                    mysqli_query($con, "update memotb set memo_status='Queried' where memo_id='$memo_id'") or die ( mysqli_error($con));
                    echo "<script>alert('Done::Memo Queried successfully'); $('#xwinQ').window('close'); </script>";
               }
          else{echo "<script>alert('Failure::Operation failed!');</script>"; /*exit;*/}
     }else if($action == "Update"){
          //process for updating ammount approved on memo by unit head
          if($amount_app > $amount_req){
               echo "<script>alert('Alert:: You are not authorised to enter an Amount above the approved value of the memo(N$amount_req).'); </script>";
               exit;
          }
          mysqli_query($con, "update memotb set amount_approved=$amount_app where memo_id='$memo_id'") or die ( mysqli_error($con));
          logs("$login_id", "Change Amount Approved", "$login_id changed amount approved on memo from $amount_req to $amount_app on MemoID: $memo_id");
          echo "<script>alert('Done::Amount approved updated successfully'); $('#xwinQ').window('close'); </script>";
     }
}
if($id=='pay_slip') ///////
{
     $fileno = $_REQUEST['dept_unit'];
     //exit;
     $res_c=  mysqli_query($con, "select * from payroll_scheduletb where fileno = '996' order by entry_date desc") or die( mysqli_error($con));
     //echo  mysqli_error($con); exit;
     //$res_c=@mysqli_query($con, "select mv.memo_id, m.memo_from, m.description, m.amount, m.datein, from (inner join memo_movementtb mv inner join memotb m  on mv.memo_id=m.memo_id (inner join memo_assigntb ma on ma.memo_id = m.memo_id)where mv.dept_unit = '$dept_unit' and mv.memo_status = 'IN' and m.memo_status='In Progress' and ma.memo_assigntb='Pending')");
     $json_response=array();
     while($rs_c2=@mysqli_fetch_array($res_c))
     {
          $row_array['id']=@$rs_c2['id'];
          $row_array['fullname']=@$rs_c2['fullname'];
          $row_array['staff_status']=@$rs_c2['staff_status'];
          $row_array['year']=@$rs_c2['year'];
          $row_array['month']=@$rs_c2['month'];
          $row_array['amount']=@$rs_c2['amount'];
          $row_array['level']=@$rs_c2['level'];
          $row_array['step']=@$rs_c2['step'];
          array_push($json_response,$row_array);
     }
     echo json_encode($json_response);
}//END OF PAY SLIP

if($id=='loc_save') // Start of Save Category
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

          if ($j->dept == '' || $j->unit =='' || $j->room_no == '' || $j->loc_code == '' )
          {
               echo "<script>alert('Alert:: All Fields are compulsory'); </script>";
               exit;
          }
          $res_chk=@mysqli_query($con, "select * from locationtb where dept='$j->dept' and unit='$j->unit' and room_no='$j->room_no' and loc_code='$j->loc_code'");
          if(@mysqli_num_rows($res_chk)>=1)
          {
               $row=@$j->row_id;  //row id of record to edit
               @mysqli_query($con, "update locationtb set dept='$j->dept',unit='$j->unit',room_no='$j->room_no',loc_code='$j->loc_code',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where id='$row'");
          }
          else
          {
               mysqli_query($con, "insert into locationtb set dept='$j->dept',unit='$j->unit',room_no='$j->room_no',loc_code='$j->loc_code',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          } //end of save

          logs("$login_id","Save Record","$login_id saved category record $j->dept $j->unit");
          $sql="select * from locationtb order by dept";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from locationtb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['dept'].$rs_d['unit'].$rs_d['code'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted location record $log_desc");

          @mysqli_query($con, "delete from locationtb where id='$r_id'");
          $sql="select * from locationtb order by dept";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from locationtb where 1";
          if($j->dept!="") $sql.=" and dept='$j->dept'";
          if($j->unit!="") $sql.=" and unit='$j->unit'";
          if($j->room_no!="") $sql.=" and room_no='$j->room_no'";
          if($j->loc_code!="") $sql.=" and loc_code='$j->loc_code'";

          $sql.=" order by dept";
     }

     if($action=='edit')
     {
          //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

          //$fileno=@$_REQUEST['fileno'];
          $db->sql("select * from loc_code where id='$r_id'");
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
     $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>DEPARTMENT</th><th>UNIT</th><th>ROOM NO</th><th>LOCATION CODE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $dept=$rs_v['dept'];
               $unit=$rs_v['unit'];
               $room_no=$rs_v['room_no'];
               $loc_code=$rs_v['loc_code'];
               //$g_total+=$rs_v['amount'];
               $tb.="<tr><td>$sn</td><td>$dept</td><td>$unit</td><td>$room_no</td><td>$loc_code</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('loc_save','delete','$r_id');\">DELETE</a> </td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
          }//end of while

          //$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          //$tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

}/// End of Save Location
if($id=='life_save') // Start of Save Category
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

          if ($j->cat_type == '' || $j->use_life =='' || $j->scrap_value =='' )
          {
               echo "<script>alert('Alert:: All Fields are compulsory'); </script>";
               exit;
          }
          $res_chk=@mysqli_query($con, "select * from useful_lifetb where asset_type='$j->cat_type' and life_percent='$j->use_life'");
          if(@mysqli_num_rows($res_chk)>=1)
          {
               $row=@$j->row_id;  //row id of record to edit
               @mysqli_query($con, "update useful_lifetb set asset_type='$j->cat_type',life_percent='$j->use_life',scrap_value='$j->scrap_value',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where id='$row'");
          }
          else
          {
               mysqli_query($con, "insert into useful_lifetb set asset_type='$j->cat_type',life_percent='$j->use_life',scrap_value='$j->scrap_value',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          } //end of save

          logs("$login_id","Save Record","$login_id saved useful life record $j->cat_type $j->use_life");
          $sql="select * from useful_lifetb order by asset_type";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from useful_lifetb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['useful_lifetb'].$rs_d['life_percent'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted useful life record $log_desc");

          @mysqli_query($con, "delete from useful_lifetb where id='$r_id'");
          $sql="select * from useful_lifetb order by asset_type";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from useful_lifetb where 1";
          if($j->cat_type!="") $sql.=" and asset_type='$j->cat_type'";
          if($j->use_life!="") $sql.=" and life_percent='$j->use_life'";
          if($j->scrap_value!="") $sql.=" and scrap_value='$j->scrap_value'";

          $sql.=" order by asset_type";
     }

     if($action=='edit')
     {
          //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

          //$fileno=@$_REQUEST['fileno'];
          $db->sql("select * from useful_lifetb where id='$r_id'");
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
     $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>ASSET TYPE</th><th>LIFE PERCENT(%)</th><th>SCRAP VALUE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $asset_type=$rs_v['asset_type'];
               $life_percent=$rs_v['life_percent'];
               $scrap_value=$rs_v['scrap_value'];
               $asst=@mysqli_query($con, "select * from asset_categorytb where cat_code='$asset_type'"); $rs_d=@mysqli_fetch_array($asst); $asset_name=$rs_d['cat_title'];
               $tb.="<tr><td>$sn</td><td>$asset_name</td><td>$life_percent</td><td>$scrap_value</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('life_save','delete','$r_id');\">DELETE</a> </td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
          }//end of while

          //$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
          //$tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
          $tb.="</table>";
          echo $tb_s.$tb;
     }
     else
     echo "<b>No record to display</b>";

}/// End of save of useful life

if($id=='asset_movement') // Start of update movement
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     //$code=$j->code;
     //$name=@mysqli_real_escape_string($con, $j->name);
     //$status=$j->status;
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     $days = $_REQUEST['sdate1'];
     if($action=='save')
     {

          $s_date = convertdate('save',$days);
          if ($j->identify_string == '' || $j->location =='' || $days =='' )
          {
               echo "<script>alert('Alert:: All Fields are compulsory'); </script>";
               exit;
          }

          $res_chk=@mysqli_query($con, "select * from assettb where identify_string='$j->identify_string' ");
          // mysqli_num_rows($res_chk);
          if(@mysqli_num_rows($res_chk)==0)
          {
               echo "<script>alert('Alert:: Asset Number not Found'); </script>";
               exit;

          }
          else
          {
               $rs_c=@mysqli_fetch_array($res_chk);
               $descritption = $rs_c['descritption'];
               $identify_string = $rs_c['identify_string'];
               $acq_date = $rs_c['acq_date'];
               // $location = $rs_c['location'];

               /* echo "<script>alert('Asset Decription:: $asset_name || Asset Location:: $loc_name'); </script>";*/
               //exit;
               $res_chk3=@mysqli_query($con, "select * from fix_movementtb where identify_string='$j->identify_string' and loc_code='$j->location' and entry_date=CURDATE()  ");
               if( mysqli_num_rows($res_chk3)> 0)
               {
                    echo "<script>alert('Alert:: Asset has already been moved to that location'); </script>";
                    exit;

               }
               else {
                    mysqli_query($con, "insert into fix_movementtb set identification_string='$j->identify_string',loc_code='$j->location',mov_date='$s_date',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               }
          } //end of save
          // mysqli_query($con, "insert into useful_lifetb set asset_type='$j->asset_type',life_percent='$j->life_percent',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");

          logs("$login_id","Update Record","$login_id update asset movement record $j->identify_string $j->location");
          // $sql="select * from useful_lifetb,asset_typetb where asset_typetb.asset_code = useful_lifetb.asset_type order by asset_type";
          /////////////////////view section ////////////////////
          $asst=@mysqli_query($con, "select * from fix_movementtb where identification_string='$j->identify_string' order by entry_date desc ");
          //$asst= mysqli_query($con, "select * from assettb where identify_string='$j->identify_string'");
          $sn=0;
          $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>ASSET NO</th><th>DESCRIPTION</th><th>ACQ DATE</th><th>LOCATION</th><th>MOVE.DATE</th></tr>";
          while($rs_v=@mysqli_fetch_array($asst))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $pres_loc=$rs_v['loc_code'];
               $mov_date=$rs_v['mov_date'];
               $pres_locs = get_location($pres_loc);
               $move_d = convertdate('display',$mov_date);
               $tb.="<tr><td>$sn</td><td>$identify_string</td><td>$descritption</td><td>$acq_date</td><td>$pres_locs</td><td>$move_d</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
          }//end of while
          $tb.="</table>";
          echo $tb;
          //echo $tb_s.$tb;
          echo "<script>alert('Record Updated successfully');</script>";
     }
     elseif ($action=='search')
     {
          if ($j->identify_string == '' and $j->location =='' and $days =='' ){
               echo "<script>alert('No Record to Display');</script>";
               exit;
          }
          $sql="select * from fix_movementtb where 1";
          if($j->identify_string!="") $sql.=" and identification_string='$j->identify_string'";
          if($j->location!="") $sql.=" and loc_code='$j->location'";
          if($days!="") $sql.=" and mov_date='$days'";

          $sql.=" order by entry_date desc";
          $res_v= mysqli_query($con, $sql);
          if( mysqli_num_rows($res_v)<=0)
          {
               echo "<b>No record to display</b>"; }
               else {
                    //$asst=@mysqli_query($con, "select * from fix_movementtb where identification_string='$j->identify_string' order by entry_date desc ");
                    //$asst= mysqli_query($con, "select * from assettb where identify_string='$j->identify_string'");
                    $sn=0;
                    $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>ASSET NO</th><th>DESCRIPTION</th><th>ACQ DATE</th><th>LOCATION</th><th>MOVE.DATE</th></tr>";
                    while($rs_v=@mysqli_fetch_array($res_v))
                    {
                         ++$sn;
                         $r_id=$rs_v['id'];
                         $pres_loc=$rs_v['loc_code'];
                         $mov_date=$rs_v['mov_date'];
                         $identification_string=$rs_v['identification_string'];
                         // $assts= mysqli_query($con, "select identify_string,descritption,acq_date from assettb where identify_string='$j->identify_string'");
                         $assts= mysqli_query($con, "select identify_string,descritption,acq_date from assettb where identify_string='$identification_string'");
                         $rs_c2= mysqli_fetch_array($assts);
                         $identify_string = $rs_c2['identify_string'];$descritption = $rs_c2['descritption'];$acq_date = $rs_c2['acq_date'];
                         $pres_locs = get_location($pres_loc);
                         $move_d = convertdate('display',$mov_date);
                         $acq_dates = convertdate('display',$acq_date);
                         $tb.="<tr><td>$sn</td><td>$identify_string</td><td>$descritption</td><td>$acq_dates</td><td>$pres_locs</td><td>$move_d</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
                    }//end of while
                    $tb.="</table>";
                    echo $tb;
               }
          }
     }/// End of update movement

     if($id=='catdiv')
     {
          $val=@$_REQUEST['cat_type'];

          $type_id = $bursary->get_any_value('cat_id','asset_categorytb','cat_code',$val);

          $res_l=@mysqli_query($con, "select * from asset_typetb where cat_id='$type_id' order by type_title");
          echo "<select name='asset_cat' id='asset_cat'><option selected value=''>---</option>";
          while($rs_l=@mysqli_fetch_array($res_l))
          {
               $type_title=@$rs_l['type_title'];
               $asset_code=@$rs_l['asset_code'];
               echo "<option value='$asset_code'>$type_title</option>";
          }
          echo "</select>";

     } //end of fetch state
     if($id=='fixed_asset_save') // Start of Save fixed asset and consumables
     {
          //echo 5443535; exit;
          $j=json_decode(stripslashes(@$_REQUEST['mydata']));
          $action= $_REQUEST['action'];
          $days = $_REQUEST['sdate1'];
          $s_date = convertdate('save',$days);
          $identify_string = $j->identify_string;
          //exit;

          $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
          $login_id=@$_SESSION['login_id'];
          $yea = date('Y');
          //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
          if($action=='save')
          {
               if ($j->qty == '' || $j->identify_string == '' || $j->amount == '' || $days == '' || $j->location == '' || $j->cat_type == '' || $j->asset_cat== '' || $j->prod_id== '')
               {
                    echo "<script>alert('Alert:: All Fields are compulsory'); </script>";
                    exit;
               }
               $check2=  mysqli_query($con, "select * from grn_sivtb where grn='$j->grn' and siv='$j->siv'");
               if( mysqli_num_rows($check2) == 0){
                    mysqli_query($con, "insert into grn_sivtb set grn='$j->grn',siv='$j->siv',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               }

               $check=  mysqli_query($con, "select * from assettb where location='$j->location' and asset_type='$j->asset_cat' and asset_category='$j->cat_type' and serial='$j->serial' and barcode='$j->barcode' and grn='$j->grn' and identify_string='$j->identify_string' ");
               if( mysqli_num_rows($check) > 0){
                    echo "<script>alert ('Alert:: This record has already been addeds.');</script>";
                    exit;}
                    if 	($j->fix_con == 'No')
                    { 	 $check2=  mysqli_query($con, "select * from assettb where location='$j->location' and asset_type='$j->asset_cat' and asset_category='$j->cat_type' and amount='$j->amount' and grn='$j->grn' and descritption='$j->descs'");
                         if( mysqli_num_rows($check2) > 0){
                              echo "<script>alert ('Alert:: This record has already been added.');</script>";
                              exit;} else {
                                   $identify_string = identification_consumables(6,4);
                                   mysqli_query($con, "insert into assettb set prod_id='$j->prod_id',descritption='$j->descs',identify_string='$identify_string',acq_date='$s_date',
                                   location='$j->location',amount='$j->amount',asset_type='$j->asset_cat',asset_category='$j->cat_type',serial='$j->serial',
                                   barcode='$j->barcode',grn='$j->grn',siv='$j->siv',sup_id='$j->supplier',fix_con='$j->fix_con',qty='$j->qty',invoice_no='$j->invoice_no',entry_by='$login_id',
                                   entry_date=CURDATE(),entry_time=CURTIME()");
                                   mysqli_query($con, "insert into fix_product_outflow set identification_string='$identify_string', prod_id='$j->prod_id',rate='$j->amount',qty='$j->qty',sup_id='$j->supplier',
                                   invoice_no='$j->invoice_no',supply_date='$s_date',entry_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME()");
                                   mysqli_query($con, "insert into fix_movementtb set identification_string='$identify_string',loc_code='$j->location',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
                                   $asst= mysqli_query($con, "select * from assettb where grn='$j->grn' order by acq_date desc ");
                                   //$asst= mysqli_query($con, "select * from assettb where identify_string='$j->identify_string'");
                                   $sn=0;
                                   $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>ASSET NO</th><th>DESCRIPTION</th><th>ACQ DATE</th><th>AMOUNT</th></tr>";
                                   while($rs_v=@mysqli_fetch_array($asst))
                                   {
                                        ++$sn;
                                        $r_id=$rs_v['id'];
                                        $identify_string=$rs_v['identify_string'];
                                        $descritption=$rs_v['descritption'];
                                        $acq_date=$rs_v['acq_date'];
                                        $amount=$rs_v['amount'];
                                        $acq_dates = convertdate('display',$acq_date);
                                        $tb.="<tr><td>$sn</td><td>$identify_string</td><td>$descritption</td><td>$acq_dates</td><td>".number_format($amount,2)."</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
                                   }//end of while
                                   $tb.="</table>";
                                   echo $tb;

                                   echo "<script>alert ('Success:: Record Saved.');</script>";
                                   exit;
                              }
                         }
                         $qty =	$j->qty;
                         $amount= $j->amount * $qty;
                         //for ($d= 1; $d <= $qty; $d++ ) //start of the for loop to add fixed asset
                         //{
                         //$next = 0;
                         //$res_d=  mysqli_query($con, "select count(*) as total from assettb where location='$j->location' and asset_type='$j->asset_cat' and asset_category='$j->cat_type' and fix_con = 'Yes'");
                         //$rs_d= mysqli_fetch_array($res_d); $tot=$rs_d['total'];
                         //if ($tot > 0 )
                         // {
                         //$next = $tot + 1; } else { $next = $next + 1; }
                         //$identify_string = 'UIL/'.$yea.'/'.$j->location.'/'.$j->cat_type.'/'.$j->asset_cat.'/'.$next;
                         $identify_string = $j->identify_string;
                         mysqli_query($con, "insert into assettb set prod_id='$j->prod_id', descritption='$j->descs',identify_string='$identify_string',acq_date='$s_date',location='$j->location',amount='$amount',asset_type='$j->asset_cat',asset_category='$j->cat_type',serial='$j->serial',barcode='$j->barcode',grn='$j->grn',siv='$j->siv',sup_id='$j->supplier',fix_con='$j->fix_con',qty='$qty',invoice_no='$j->invoice_no',entry_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME()");
                         mysqli_query($con, "insert into fix_movementtb set identification_string='$identify_string',loc_code='$j->location',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
                         mysqli_query($con, "insert into fix_product_outflow set identification_string='$identify_string', prod_id='$j->prod_id',rate='$j->amount',qty='1',sup_id='$j->supplier',
                         invoice_no='$j->invoice_no',supply_date='$s_date',entry_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME()");

                         // $res_d=@mysqli_query($con, "select * from assettb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['dept'].$rs_d['unit'].$rs_d['code'];

                         $res_d2=  mysqli_query($con, "select * from useful_lifetb where asset_type='$j->cat_type'");
                         $rs_d2= mysqli_fetch_array($res_d2); $perc=$rs_d2['life_percent']; $scrap_value=$rs_d2['scrap_value'];
                         if ($perc == 0)
                         {$percs = 100;
                         }else{
                              $percs = 100/$perc;
                         }
                         $scrap_values = $scrap_value * $qty;

                         //$depreciation = (($j->amount - $scrap_values) /$percs);
                         //$depreciation = (($amount - $scrap_values) /$percs);
                         $depreciation = ($amount/$percs);
                         mysqli_query($con, "insert into asset_depreciation set identify_string='$identify_string',cost_bf='$amount',acc_depr='$depreciation',no_years='$percs',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
                    }///end of the for loop to add fixed asset


                    $asst= mysqli_query($con, "select * from assettb where grn='$j->grn' order by acq_date desc ");

                    //$asst= mysqli_query($con, "select * from assettb where identify_string='$j->identify_string'");
                    $sn=0;
                    $tb="<table rules='rows' frame='box' width='100%'><tr><th>S/N</th><th>ASSET NO</th><th>DESCRIPTION</th><th>ACQ DATE</th><th>AMOUNT</th></tr>";
                    while($rs_v=@mysqli_fetch_array($asst))
                    {
                         ++$sn;
                         $r_id=$rs_v['id'];
                         $identify_string=$rs_v['identify_string'];
                         $descritption=$rs_v['descritption'];
                         $acq_date=$rs_v['acq_date'];
                         $amount=$rs_v['amount'];
                         $acq_dates = convertdate('display',$acq_date);
                         $tb.="<tr><td>$sn</td><td>$identify_string</td><td>$descritption</td><td>$acq_dates</td><td>".number_format($amount,2)."</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
                    }//end of while
                    $tb.="</table>";
                    echo $tb;

                    echo "<script>alert('Record Saved Successfully'); </script>";
               }
               if($action=='searchs') {

                    $asst= mysqli_query($con, "select * from assettb where grn='$j->grn' order by acq_date desc ");
                    //$asst= mysqli_query($con, "select * from assettb where identify_string='$j->identify_string'");
                    $sn=0;
                    $tb="<table rules='rows' frame='box' width='100%'><tr><th>S/N</th><th>ASSET NO</th><th>DESCRIPTION</th><th>ACQ DATE</th><th>AMOUNT</th></tr>";
                    while($rs_v=@mysqli_fetch_array($asst))
                    {
                         ++$sn;
                         $r_id=$rs_v['id'];
                         $identify_string=$rs_v['identify_string'];
                         $descritption=$rs_v['descritption'];
                         $acq_date=$rs_v['acq_date'];
                         $amount=$rs_v['amount'];
                         $acq_dates = convertdate('display',$acq_date);
                         $tb.="<tr><td>$sn</td><td>$identify_string</td><td>$descritption</td><td>$acq_dates</td><td>".number_format($amount,2)."</td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
                    }//end of while
                    $tb.="</table>";
                    echo $tb;
               }

               if($id=='incoming_asset')
               {

                    //$r= mysqli_query($con, "select m.memo_id, m.memo_from, m.description, m.amount, m.memo_status, mm.memo_status as move_status from memotb m inner join memo_movementtb mm on m.memo_id=mm.memo_id where mm.memo_status='IN'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    $r= mysqli_query($con, "select a.disposal,a.deleted,a.identify_string,a.acq_date,a.descritption,a.amount,a.status,a.location,t.type_title,c.cat_title from assettb a,asset_typetb t, asset_categorytb c where a.asset_type = t.asset_code and a.asset_category = c.cat_code and a.status='Active' and a.disposal='No' and a.deleted='No'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    //$r= mysqli_query($con, "select * from assettb where status='Active'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    $json_response=array();
                    while ($row =  mysqli_fetch_array($r)) {
                         $row_array['identify_string'] = $row['identify_string'];
                         $row_array['acq_date'] = $row['acq_date'];
                         $row_array['descritption'] = $row['descritption'];
                         $row_array['location'] = $row['location'];
                         $row_array['amount'] = $row['amount'];
                         $row_array['type_title'] = $row['type_title'];
                         $row_array['cat_title'] = $row['cat_title'];
                         $row_array['status'] = $row['status'];
                         $row_array['disposal'] = $row['disposal'];
                         $row_array['deleted'] = $row['deleted'];
                         array_push($json_response,$row_array);
                    }//end of while

                    echo json_encode($json_response);
               }
               if($id=='archeive_asset')
               {
                    //$r= mysqli_query($con, "select m.memo_id, m.memo_from, m.description, m.amount, m.memo_status, mm.memo_status as move_status from memotb m inner join memo_movementtb mm on m.memo_id=mm.memo_id where mm.memo_status='IN'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    $r= mysqli_query($con, "select a.disposal,a.deleted,a.identify_string,a.acq_date,a.descritption,a.amount,a.status,l.dept,l.unit,l.room_no,t.type_title,c.cat_title from assettb a, locationtb l,asset_typetb t, asset_categorytb c where a.location = l.loc_code and a.asset_type = t.asset_code and a.asset_category = c.cat_code and a.status='Active' and (a.disposal='Yes' or a.deleted='Yes')"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    //$r= mysqli_query($con, "select * from assettb where status='Active'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    $json_response=array();
                    while ($row =  mysqli_fetch_array($r)) {
                         $row_array['identify_string'] = $row['identify_string'];
                         $row_array['acq_date'] = $row['acq_date'];
                         $row_array['descritption'] = $row['descritption'];
                         $row_array['units'] = $row['unit'];
                         $row_array['dept'] = $row['dept'];
                         $row_array['room_no'] = $row['room_no'];
                         $row_array['amount'] = $row['amount'];
                         // $row_array['type_title'] = '<a href="folio.php">'.$row['type_title'].'</a>';
                         $row_array['type_title'] = $row['type_title'];
                         $row_array['cat_title'] = $row['cat_title'];
                         $row_array['status'] = $row['status'];
                         $row_array['disposal'] = $row['disposal'];
                         $row_array['deleted'] = $row['deleted'];
                         array_push($json_response,$row_array);
                    }//end of while

                    echo json_encode($json_response);
               }
               if($id=="assignmail_looks") //////////////////// start of deleting fixed asset
               {
                    $index = $_REQUEST['tabindex'];
                    "<script>
                    $('#tt').tabs('select', $index);</script>";
                    $action = $_REQUEST['action'];
                    $login_id =  mysqli_real_escape_string($con, $_REQUEST['login_id']);
                    $identify_string =  mysqli_real_escape_string($con, $_REQUEST['didentifystring']);
                    if ($identify_string== ''){
                         echo "<script>alert('Select Asset to Delete !!!'); </script>";
                         exit;
                    }

                    if ($action == 'del')	{
                         if ($identify_string== ''){
                              echo "<script>alert('Select Asset to Delete !!!'); </script>";
                              exit;
                         }
                         mysqli_query($con, "insert into fix_asset_deletetb(descritption,identify_string,acq_date,location,amount,asset_type,asset_category,serial,barcode,grn,siv,sup_id,fix_con,qty,invoice_no,status,disposal,disposal_date,disposal_time,deleted,deleted_date,deleted_time,additions,entry_by,entry_date,entry_time) select descritption,identify_string,acq_date,location,amount,asset_type,asset_category,serial,barcode,grn,siv,sup_id,fix_con,qty,invoice_no,status,disposal,disposal_date_time,disposal_date_time,'Yes',CURDATE(),CURTIME(),additions,entry_by,entry_date,entry_time from assettb where identify_string='$identify_string'") or die( mysqli_error($con));
                         mysqli_query($con, "delete from assettb where identify_string='$identify_string'");
                         mysqli_query($con, "delete from asset_depreciation where identify_string='$identify_string'");
                         // mysqli_query($con, "update assettb set deleted = 'Yes', deleted_date_time= Now() where identify_string='$identify_string' ")or die ( mysqli_error($con));
                         logs("$login_id","Delete Asset","$login_id deleted asset record $identify_string");
                         echo 	"<script>alert('Asset Deleted Successfully !!!'); </script>";
                         exit;
                    }

                    if ($action == 'dis')	{
                         if ($identify_string== ''){
                              echo "<script>alert('Select Asset to Dispose !!!'); </script>";
                              exit;
                         }
                         mysqli_query($con, "update assettb set disposal = 'Yes', disposal_date_time= Now() where identify_string='$identify_string' ")or die ( mysqli_error($con));
                         logs("$login_id","Delete Asset","$login_id deleted asset record $identify_string");
                         echo 	"<script>alert('Asset Disposed Successfully !!!'); </script>";
                         exit;
                    }
               }/////// end of deleting fixed asset

               if($id=="editasset")
               {
                    $index = $_REQUEST['tabindex'];
                    /*echo "<script>
                    $('#tt').tabs('select', $index);</script>";
                    /*echo $_REQUEST['mdoc']; exit;*/
                    echo "hi";
                    $hdescription =  mysqli_real_escape_string($con, $_REQUEST['hdescription']);
                    $htypetitle =  mysqli_real_escape_string($con, $_REQUEST['htypetitle']);
                    $type_id = $bursary->get_any_value('asset_code','asset_typetb','type_id',$htypetitle);
                    $hcattitle =  mysqli_real_escape_string($con, $_REQUEST['hcattitle']);
                    $cat_id = $bursary->get_any_value('cat_code','asset_categorytb','cat_id',$hcattitle);
                    $hamount=  mysqli_real_escape_string($con, $_REQUEST['hamount']); //date('dmY').rand();dept_unit
                    $hidentifystring =  mysqli_real_escape_string($con, $_REQUEST['eidentifystring']);

                    $login_id =  mysqli_real_escape_string($con, $_REQUEST['vlogin_id']);
                    if($hidentifystring == ""){
                         echo "<script>alert('No Asset ID selected!'); </script>"; exit;
                    }
                    $mvc =  mysqli_query($con, "select fix_con from assettb where identify_string='$hidentifystring'");
                    if( mysqli_num_rows($mvc) >= 1){
                         $rs_mvc=  mysqli_fetch_array($mvc);
                         $fix_con = $rs_mvc['fix_con'];
                         //check if bursar has acted on the memo
                         if($htypetitle == ""){
                              echo "<script>alert('Asset Type is required!'); </script>"; exit;
                         }
                         if($hcattitle == ""){
                              echo "<script>alert('Asset Category is required!'); </script>"; exit;
                         }
                         if($hdescription == ""){
                              echo "<script>alert('Asset description is required!'); </script>"; exit;
                         }
                         if(!is_numeric($hamount) && $hamount != ""){
                              echo "<script>alert('Asset Amount must be numeric data and must not be empty!'); </script>"; exit;
                         }
                         if ($fix_con == 'Yes') {
                              mysqli_query($con, "update assettb set descritption='$hdescription', asset_type='$type_id', asset_category='$cat_id',amount='$hamount' where identify_string='$hidentifystring'") or die ( mysqli_error($con));

                              $res_d2=  mysqli_query($con, "select life_percent from useful_lifetb where asset_type='$cat_id'");
                              $rs_d2= mysqli_fetch_array($res_d2); $perc=$rs_d2['life_percent'];
                              if ($perc == 0)
                              {$percs = 100;
                              }else{
                                   $percs = 100/$perc;
                              }

                              $depreciation = (($hamount - 10) /$percs);
                              mysqli_query($con, "update asset_depreciation set cost_bf='$hamount',acc_depr='$depreciation',no_years='$percs',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where identify_string='$hidentifystring'");
                              $descss = $identify_string.$hdescription.$hamount.$depreciation.$percs;
                              logs("$login_id","Updated Asset","$login_id Updated asset record $descss");
                              echo "<script>alert('Record Update Succesfully'); </script>";
                              exit;
                         }

                         else {

                              mysqli_query($con, "update assettb set descritption='$hdescription', asset_type='$type_id', asset_category='$cat_id',amount='$hamount' where identify_string='$hidentifystring'") or die ( mysqli_error($con));
                              $descss = $identify_string.$hdescription.$hamount.$depreciation.$percs;
                              logs("$login_id","Updated Asset","$login_id Updated asset record $descss");
                              echo "<script>alert('Record Updated Succesfully'); </script>";
                              exit;
                         }


                    }else
                    {
                         //memo has been treated before
                         echo "<script>alert('Sorry, Asset ID not Available');</script>";
                         exit;
                    }// end if for multiple movement check
               }

               if($id=='supplier_save') // Start of Save Category
               {
                    $j=json_decode(stripslashes(@$_REQUEST['mydata']));
                    //$code=$j->code;
                    //$name=@mysqli_real_escape_string($con, $j->name);
                    //$status=$j->status;
                    $action=@$_REQUEST['action'];
                    $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
                    $login_id=@$_SESSION['login_id'];
                    //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
                    $res_t= mysqli_query($con, "select * from suppliertb") or die ( mysqli_error($con));
                    $gh=  mysqli_num_rows($res_t);
                    $gh2= $gh +1;
                    $sup_id = 'UIL/SUP'.$gh2;
                    if($action=='save')
                    {
                         $res_chk=@mysqli_query($con, "select * from suppliertb where sup_name='$j->sup_name' and sup_address='$j->sup_address' and sup_phone='$j->sup_phone'");
                         if(@mysqli_num_rows($res_chk)>=1)
                         {
                              $row=@$j->row_id;  //row id of record to edit
                              @mysqli_query($con, "update suppliertb set sup_name='$j->sup_name',sup_address='$j->sup_address',sup_phone='$j->sup_phone',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where id='$row'");
                         }
                         else
                         {
                              mysqli_query($con, "insert into suppliertb set sup_id='$sup_id',sup_name='$j->sup_name',sup_address='$j->sup_address',sup_phone='$j->sup_phone',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
                         } //end of save

                         logs("$login_id","Save Record","$login_id saved supplier record $j->sup_name $j->sup_phone");
                         $sql="select * from suppliertb order by sup_name";
                         echo "<script>alert('Record saved successfully');</script>";
                    }

                    if($action=='delete')
                    {
                         $res_d=@mysqli_query($con, "select * from suppliertb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['sup_name'].$rs_d['sup_phone'];//for logs purpose
                         logs("$login_id","Delete Record","$login_id deleted supplier record $log_desc");

                         @mysqli_query($con, "delete from suppliertb where id='$r_id'");
                         $sql="select * from suppliertb order by sup_name";
                         echo "<script>alert('Record deleted successfully');</script>";
                    }

                    if($action=='search')
                    {
                         $sql="select * from suppliertb where 1";
                         if($j->sup_name!="") $sql.=" and sup_name='$j->sup_name'";
                         if($j->sup_address!="") $sql.=" and sup_address='$j->sup_address'";
                         if($j->sup_phone!="") $sql.=" and sup_phone='$j->sup_phone'";

                         $sql.=" order by sup_id";
                    }

                    if($action=='edit')
                    {
                         //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

                         //$fileno=@$_REQUEST['fileno'];
                         $db->sql("select * from suppliertb where id='$r_id'");
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
                    $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>SUPPLIER ID</th><th>SUPPLIER NAME</th><th>SUPPLIER ADDRESS</th><th>SUPPLIER PHONE</th><th>ACTION</th></tr>";
                    if(@mysqli_num_rows($res_v)>=1)
                    {
                         while($rs_v=@mysqli_fetch_array($res_v))
                         {
                              ++$sn;
                              $r_id=$rs_v['id'];
                              $sup_name2= strtolower($rs_v['sup_name']);
                              $sup_name = ucfirst($sup_name2);
                              $sup_address=$rs_v['sup_address'];
                              $sup_id=$rs_v['sup_id'];
                              $sup_phone=$rs_v['sup_phone'];
                              //$g_total+=$rs_v['amount'];
                              $tb.="<tr><td>$sn</td><td>$sup_id</td><td>$sup_name</td><td>$sup_address</td><td>$sup_phone</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('supplier_save','delete','$r_id');\">DELETE</a> </td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
                         }//end of while

                         //$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
                         //$tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
                         $tb.="</table>";
                         echo $tb_s.$tb;
                    }
                    else
                    echo "<b>No record to display</b>";

               }/// End of Save Category
               if($id=='display_grn_process')
               {
                    $grn=$_REQUEST['grn'];
                    $r_vals=$_REQUEST['r_vals'];
                    $res_d=@mysqli_query($con, "select * from grn_sivtb  where grn='$grn' ");
                    $rs_d=@mysqli_fetch_array($res_d);
                    $siv = $rs_d['siv'];
                    if(strtolower($r_vals)!="cash officer")
                    {
                         $tb="<form name='frmpro' id='frmpro'><table><tr><th>GRN</th><td>$grn</td></tr>
                         <tr><th>SIV</th><td>$siv</td></tr>
                         <tr><th>ENTRY DATE</th><td>".date('d/m/Y',strtotime($rs_d['entry_date']))."</td></tr>
                         <tr><th>APPROVED</th><td><select name='opt' id='opt'>
                         <option selected value=''>---</option>
                         <option value='Yes'>Yes</option>
                         <option value='No'>No</option>
                         </select><input type='hidden' name='grn' id='grn' value='$grn'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>
                         <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_grn');\" class='btn'/></th></tr>
                         </table><div id='process_grn'></div></form>";
                         echo $tb;
                    }
               }

               if($id=='process_grn')
               {
                    $login_id=@$_SESSION['login_id'];
                    $grn=$_REQUEST['grn'];
                    $opt=$_REQUEST['opt'];  //Approved or Not Approved
                    $r_vals=$_REQUEST['r_vals'];
                    $r=strtolower($r_vals);
                    $comment=$_REQUEST['comment'];
                    if($opt=='')
                    {
                         echo "<script>alert('Error: You have not selected an option from the list');</script>";
                         exit;
                    }
                    if($opt=='No')
                    {
                         echo "<script>alert('Error: You cannot Select No');</script>";
                         exit;
                    }
                    if($r == 'fixed asset' or $r=="super admin" or  $r=="administrator")
                    {
                         // if($r=="super admin" or $r=="checked by officer" or $r=="administrator" or $r=="fixed asset")
                         $sql="update grn_sivtb set checked_by_name='$login_id',checked_by='$opt',checked_date=CURDATE(),checked_time=CURTIME() where grn='$grn'";
                         mysqli_query($con, $sql) or die( mysqli_error($con));
                    }
                    echo "<script>alert('Record updated successfully');</script>";
               }

               if($id=='display_siv_process')
               {
                    $siv=$_REQUEST['siv'];
                    $r_vals=$_REQUEST['r_vals'];
                    $res_d=@mysqli_query($con, "select * from grn_sivtb  where siv='$siv' ");
                    $rs_d=@mysqli_fetch_array($res_d);
                    $grn = $rs_d['grn'];
                    if(strtolower($r_vals)!="cash officer")
                    {
                         $tb="<form name='frmpro' id='frmpro'><table><tr><th>SIV</th><td>$siv</td></tr>
                         <tr><th>GRN</th><td>$grn</td></tr>
                         <tr><th>ENTRY DATE</th><td>".date('d/m/Y',strtotime($rs_d['entry_date']))."</td></tr>
                         <tr><th>APPROVED</th><td><select name='opt' id='opt'>
                         <option selected value=''>---</option>
                         <option value='Yes'>Yes</option>
                         <option value='No'>No</option>
                         </select><input type='hidden' name='siv' id='siv' value='$siv'/><input type='hidden' name='r_vals' id='r_vals' value='$r_vals'/></td></tr>

                         <tr><th>Account to be Debited</th><td><input type='text' name='account_debit' id='account_debit'></td></tr>
                         <tr><th>Internal Order No</th><td><input type='text' name='internal_no' id='internal_no'></td></tr>
                         <tr><th>Requistion No</th><td><input type='text' name='requisition_no' id='requisition_no'></td></tr>
                         <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save' onclick=\"swapcontent('process_siv');\" class='btn'/></th></tr>
                         </table><div id='process_siv'></div></form>";
                         echo $tb;
                    }
               }

               if($id=='process_siv')
               {
                    $login_id=@$_SESSION['login_id'];
                    $siv=$_REQUEST['siv'];
                    $opt=$_REQUEST['opt'];  //Approved or Not Approved
                    $r_vals=$_REQUEST['r_vals'];
                    $r=strtolower($r_vals);
                    $account_debit=$_REQUEST['account_debit'];
                    $internal_no=$_REQUEST['internal_no'];
                    $requisition_no=$_REQUEST['requisition_no'];

                    if($opt=='')
                    {
                         echo "<script>alert('Error: You have not selected an option from the list');</script>";
                         exit;
                    }
                    if($opt=='No')
                    {
                         echo "<script>alert('Error: You cannot Select No');</script>";
                         exit;
                    }
                    if($r == 'fixed asset' or $r=="super admin" or  $r=="administrator")
                    {
                         // if($r=="super admin" or $r=="checked by officer" or $r=="administrator" or $r=="fixed asset")
                         $sql="update grn_sivtb set received_by_name='$login_id',received_by='$opt',received_date=CURDATE(),received_time=CURTIME(),
                         account_debit='$account_debit',internal_no='$internal_no',requisition_no='$requisition_no' where siv='$siv'";
                         mysqli_query($con, $sql) or die( mysqli_error($con));
                    }
                    echo "<script>alert('Record updated successfully');</script>";
               }
               if($id=='asset_print')
               {
                    //$r= mysqli_query($con, "select m.memo_id, m.memo_from, m.description, m.amount, m.memo_status, mm.memo_status as move_status from memotb m inner join memo_movementtb mm on m.memo_id=mm.memo_id where mm.memo_status='IN'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    $r= mysqli_query($con, "select distinct grn, siv from assettb where fix_con = 'Yes' order by grn"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    //$r= mysqli_query($con, "select * from assettb where status='Active'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
                    $json_response=array();
                    while ($row =  mysqli_fetch_array($r)) {
                         $row_array['grn'] = $row['grn'];
                         $row_array['siv'] = $row['siv'];
                         $row_array['print'] = '<a href="fix_print_report.php?p='.base64_encode($row[grn]).'" target="_blank">Print</a>';
                         // $row_array['type_title'] = '<a href="folio.php">'.$row['type_title'].'</a>';

                         array_push($json_response,$row_array);
                    }//end of while

                    echo json_encode($json_response);
               }
               if($id=="disposed")
               {
                    $index = $_REQUEST['tabindex'];
                    /*echo "<script>
                    $('#tt').tabs('select', $index);</script>";
                    /*echo $_REQUEST['mdoc']; exit;*/
                    //echo "am here";
                    $days = $_REQUEST['sdate1'];
                    $s_date = convertdate('save',$days);
                    $hidentifystring =  mysqli_real_escape_string($con, $_REQUEST['hidentifystring']);
                    $login_id =  mysqli_real_escape_string($con, $_REQUEST['vlogin_id']);
                    $damount=  mysqli_real_escape_string($con, $_REQUEST['damount']); //date('dmY').rand();dept_unit

                    if($hidentifystring == ""){
                         echo "<script>alert('No Asset ID selected!'); </script>"; exit;
                    }
                    $mvc =  mysqli_query($con, "select identify_string from assettb where identify_string='$hidentifystring' and disposal='No'");
                    if( mysqli_num_rows($mvc) >= 1){

                         if(!is_numeric($damount) && $damount != ""){
                              echo "<script>alert('Asset Amount must be numeric data and must not be empty!'); </script>"; exit;
                         }
                         mysqli_query($con, "update assettb set disposal='Yes' where identify_string='$hidentifystring'");

                         mysqli_query($con, "insert into fix_disposetb set identify_string='$hidentifystring',dis_amount='$damount',dis_date='$s_date',entry_date=CURDATE(),dis_time=CURTIME(),dis_by='$login_id'");
                         $descss = $identify_string.$damount;
                         logs("$login_id","Dipose Asset","$login_id Updated asset record $descss");
                         echo "<script>alert('Record Disposed Succesfully'); </script>";
                         exit;
                    }

                    else{
                         echo "<script>alert('Sorry, Asset ID not Available');</script>";
                    }
                    //end of dispose asset
               }

               if($id=='prod_save') // Start of Product Save
               {
                    $j=json_decode(stripslashes(@$_REQUEST['mydata']));
                    //$code=$j->code;
                    //$name=@mysqli_real_escape_string($con, $j->name);
                    //$status=$j->status;
                    $action=@$_REQUEST['action'];
                    $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
                    $login_id=@$_SESSION['login_id'];
                    //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
                    $res_t= mysqli_query($con, "select * from fix_producttb") or die ( mysqli_error($con));
                    $gh=  mysqli_num_rows($res_t);
                    $gh2= $gh +1;
                    $prod_id = 'UIL/PROD'.$gh2;
                    if($action=='save')
                    {
                         $res_chk=@mysqli_query($con, "select * from fix_producttb where prod_name='$j->prod_name' and type='$j->type' and asset_category='$j->cat_types' and asset_type='$j->asset_cat'");
                         if(@mysqli_num_rows($res_chk)>=1)
                         {
                              $row=@$j->row_id;  //row id of record to edit
                              @mysqli_query($con, "update fix_producttb set prod_name='$j->prod_name',description='$j->description',type='$j->type',asset_category='$j->cat_types',asset_type='$j->asset_cat',entry_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME() where id='$row'");
                         }
                         else
                         {
                              mysqli_query($con, "insert into fix_producttb set prod_id='$prod_id',prod_name='$j->prod_name',description='$j->description',type='$j->type',asset_category='$j->cat_types',asset_type='$j->asset_cat',entry_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME()");
                         } //end of save

                         logs("$login_id","Save Record","$login_id saved supplier record $j->prod_name $j->description");
                         $sql="select * from fix_producttb order by prod_name";
                         echo "<script>alert('Record saved successfully');</script>";
                    }

                    if($action=='delete')
                    {
                         $res_d=@mysqli_query($con, "select * from fix_producttb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['prod_name'].$rs_d['description'];//for logs purpose
                         logs("$login_id","Delete Record","$login_id deleted Product record $log_desc");

                         @mysqli_query($con, "delete from fix_producttb where id='$r_id'");
                         $sql="select * from fix_producttb order by prod_name";
                         echo "<script>alert('Record deleted successfully');</script>";
                    }

                    if($action=='search')
                    {
                         $sql="select * from fix_producttb where 1";
                         if($j->prod_name!="") $sql.=" and prod_name='$j->prod_name'";
                         if($j->description!="") $sql.=" and description='$j->description'";
                         if($j->type!="") $sql.=" and type='$j->type'";
                         if($j->cat_types!="") $sql.=" and asset_category='$j->cat_types'";
                         if($j->asset_cat!="") $sql.=" and asset_type='$j->asset_cat'";
                         $sql.=" order by prod_id";
                    }

                    if($action=='edit')
                    {
                         //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

                         //$fileno=@$_REQUEST['fileno'];
                         $db->sql("select * from fix_producttb where id='$r_id'");
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
                    $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>PROD ID</th><th>PROD NAME</th><th>DESCRIPTION</th><th>TYPE</th><th>ACTION</th></tr>";
                    if(@mysqli_num_rows($res_v)>=1)
                    {
                         while($rs_v=@mysqli_fetch_array($res_v))
                         {
                              ++$sn;
                              $r_id=$rs_v['id'];
                              $prod_name2= strtolower($rs_v['prod_name']);
                              $prod_name = ucfirst($prod_name2);
                              $description=$rs_v['description'];
                              $prod_id=$rs_v['prod_id'];
                              $type=$rs_v['type'];
                              //$g_total+=$rs_v['amount'];
                              $tb.="<tr><td>$sn</td><td>$prod_id</td><td>$prod_name</td><td>$description</td><td>$type</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('prod_save','delete','$r_id');\">DELETE</a> </td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
                         }//end of while

                         //$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
                         //$tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
                         $tb.="</table>";
                         echo $tb_s.$tb;
                    }
                    else
                    echo "<b>No record to display</b>";

               }/// End of Product Save
               if($id=='prod_inflow_save') // Start of Product Save
               {
                    $j=json_decode(stripslashes(@$_REQUEST['mydata']));
                    //$code=$j->code;
                    //$name=@mysqli_real_escape_string($con, $j->name);
                    //$status=$j->status;
                    echo $days = $_REQUEST['sdate1'];
                    $action=@$_REQUEST['action'];
                    $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
                    $login_id=@$_SESSION['login_id'];
                    //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";

                    if($action=='save')
                    {
                         $s_date = convertdate('save',$days);
                         if ($j->prod_id == '' || $days == '' || $j->rate == '' || $j->qty == '' || $j->sup_id == '' || $j->invoice_no== '')
                         {
                              echo "<script>alert('Alert:: All Fields are compulsory'); </script>";
                              exit;
                         }

                         $res_chk=@mysqli_query($con, "select * from fix_product_inflow where prod_id='$j->prod_id' and rate='$j->rate' and qty='$j->qty' and sup_id='$j->sup_id' and invoice_no='$j->invoice_no' and supply_date='$s_date' ");
                         if(@mysqli_num_rows($res_chk)>=1)
                         {
                              $row=@$j->row_id;  //row id of record to edit
                              @mysqli_query($con, "update fix_product_inflow set prod_id='$j->prod_id',rate='$j->rate',qty='$j->qty',sup_id='$j->sup_id',invoice_no='$j->invoice_no',supply_date='$s_date',entry_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME() where id='$row'");
                         }
                         else
                         {
                              mysqli_query($con, "insert into fix_product_inflow set prod_id='$j->prod_id',rate='$j->rate',qty='$j->qty',sup_id='$j->sup_id',invoice_no='$j->invoice_no',supply_date='$s_date',entry_by='$login_id',entry_date=CURDATE(),entry_time=CURTIME()");
                         } //end of save

                         logs("$login_id","Save Record","$login_id saved supplier record $j->prod_id $j->rate $j->qty");
                         $sql="select * from fix_product_inflow order by supply_date";
                         echo "<script>alert('Record saved successfully');</script>";
                    }

                    if($action=='delete')
                    {
                         $res_d=@mysqli_query($con, "select * from fix_product_inflow where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['prod_id'].$rs_d['rate'].$rs_d['invoice_no'];//for logs purpose
                         logs("$login_id","Delete Record","$login_id deleted Product Inflow record $log_desc");

                         @mysqli_query($con, "delete from fix_product_inflow where id='$r_id'");
                         $sql="select * from fix_product_inflow order by supply_date";
                         echo "<script>alert('Record deleted successfully');</script>";
                    }

                    if($action=='search')
                    {

                         $sql="select * from fix_product_inflow where 1";
                         if($j->prod_id!="") $sql.=" and prod_id='$j->prod_id'";
                         if($j->rate!="") $sql.=" and rate='$j->rate'";
                         if($j->qty!="") $sql.=" and qty='$j->qty'";
                         if($j->sup_id!="") $sql.=" and sup_id='$j->sup_id'";
                         if($j->invoice_no!="") $sql.=" and invoice_no='$j->invoice_no'";
                         if($j->supply_date!="") $sql.=" and supply_date='$s_date'";
                         $sql.=" order by supply_date";
                    }

                    if($action=='edit')
                    {
                         //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

                         //$fileno=@$_REQUEST['fileno'];
                         $db->sql("select * from fix_product_inflow where id='$r_id'");
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
                    $tb="<table rules='rows' frame='box'><tr><th>S/N</th><th>PROD ID</th><th>PROD NAME</th><th>RATE</th><th>QTY</th><th>ACTION</th></tr>";
                    if(@mysqli_num_rows($res_v)>=1)
                    {
                         while($rs_v=@mysqli_fetch_array($res_v))
                         {
                              ++$sn;
                              $r_id=$rs_v['id'];

                              $prod_id=$rs_v['prod_id'];
                              $rate=$rs_v['rate'];
                              $qty=$rs_v['qty'];
                              $q= mysqli_query($con, "select prod_name from fix_producttb where prod_id='$prod_id'");
                              if($st= mysqli_fetch_array($q, 3 )) $prod_name2=$st[0];
                              $prod_name = ucfirst($prod_name2);
                              //$g_total+=$rs_v['amount'];
                              $tb.="<tr><td>$sn</td><td>$prod_id</td><td>$prod_name</td><td>$rate</td><td>$qty</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('prod_inflow_save','delete','$r_id');\">DELETE</a> </td></tr>";//|| <a href=\"javascript:swapcontent('category_save','edit','$r_id');\">EDIT</a>
                         }//end of while

                         //$tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
                         //$tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
                         $tb.="</table>";
                         echo $tb_s.$tb;
                    }
                    else
                    echo "<b>No record to display</b>";

               }/// End of Product Save
               ?>
