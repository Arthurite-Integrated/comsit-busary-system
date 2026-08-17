<?php
session_start();
require_once('../connect.php');
/*
require_once('../function.php');

require_once('../class/mysql_class.php');
$db = new Database();
$db->connect();

require_once '../myclass_m.php';
$bursary = new myclass_m();
*/
$contentvar=$_REQUEST['contentvar'];

if($contentvar=='main_login')
{
     $login_id=mysqli_real_escape_string($con, $_REQUEST['email']);
     $password=mysqli_real_escape_string($con, $_REQUEST['password']);
     $pass_base=base64_encode($password);
     $sql="SELECT s.* FROM stafftb s where s.fileno='$login_id' and s.password='$pass_base'";
     $res_l=mysqli_query($con, $sql);
     $rs_l=mysqli_fetch_array($res_l, 3);
     //print_r ($rs_l); exit;
     if(mysqli_num_rows($res_l)>=1)
     {
          $login_status=$rs_l['status'];
          if($login_status=='Active')
          {
               $_SESSION['user']=$rs_l;
               $res_p=mysqli_query($con, "SELECT * FROM project_titletb where status='Active'");
               $rs_p=mysqli_fetch_array($res_p);
               $title=$rs_p['title'];
               $_SESSION['project_title']=$title;

               $_SESSION['userunit'] = $rs_l['unit_code'];
               $_SESSION['title']=$rs_l['title'];
               $_SESSION['surname']=$rs_l['surname'];
               $_SESSION['first_name']=$rs_l['first_name']; 
               $_SESSION['other_name']=$rs_l['other_name'];

               $_SESSION['user']['fullname']=trim(ucwords(strtolower($rs_l['surname']." ".$rs_l['first_name']." ".$rs_l['other_name'])));
               $_SESSION['user']['pix']=strtolower($rs_l['fileno']).".jpg";

               $_SESSION['last_login_date']=$rs_l['last_login_date'];
               $_SESSION['last_login_time']=$rs_l['last_login_time'];
               $_SESSION['staff_category']=$rs_l['category'];

               $_SESSION['login_status']='staff'; 
               $_SESSION['role']='Personal'; 
               $_SESSION['userLogin']='ok';

               $_SESSION['login_id']=$login_id;
               $log_date=date('Y-m-d');
               $log_time=date('h:i:s a');
               $log_date2=date('l, F d, Y');
               @mysqli_query($con, "insert into portal_logstb set regno='$login_id',log_type='Portal Login',log_desc='$login_id Login',log_date='$log_date',log_date_desc='$log_date2',log_time='$log_time',entry_by='$login_id'");

               $log_date=date('l, F d, Y');$log_time=date('h:i:s a');
               $log_date2=date('Y-m-d');
               echo "<script>location='main.php';</script>";
               exit;

          } //end of active staff
          else
          {
               echo "<br/><div class='error_msg'>You are not an active user.</div>";
               exit;
          }

     } //end of staff found
     
     echo "<script>alert('Invalid login parameters');</script>";
     ////echo "<div class='error_msg'>Invalid login parameters</div>";
     exit;
} //end of main login for staff and student
?>