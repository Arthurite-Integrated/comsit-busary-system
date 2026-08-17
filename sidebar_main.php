<?php
$login_status=@$_SESSION['login_status'];
$login_id=@$_SESSION['login_id'];
$login_id_base=@base64_encode($login_id);
//$role=@$_SESSION['role'];
$staff_category=@$_SESSION['staff_category'];
require_once "connect.php";
//echo base64_decode('T0xBREFZTw==');
?>
<script>
function swapcontent22(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
     var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
     $(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
     var url="scriptfile_a.php";
     var str;

     if(cv=="load_charges")
     {
          //alert('yes'); exit;
          $.post(url,{contentvar:cv},function(data){
               $('#w').html(data).show();
               $('#w').window('open');  //open the dialog to display info from ajax
          });
     }//end of load charges
     if(cv=='open_dialog')
     {
          var div_id="#"+v;
          //$(div_id).window('close');
          $(div_id).window('open');
     } //end of open dialog

     if(cv=='password_mgt') //start password mgt
     {
          var newpwd=$('#newpwd').val();
          var con_newpwd=$('#con_newpwd').val();
          var oldpwd=$('#oldpwd').val();
          if(newpwd=='' || oldpwd=='')
          {
               alert("Old and New passwords are compulsory");
               $(divid).html('').show();
               exit;
          } //end of test

          if(newpwd!=con_newpwd)
          {
               $.messager.alert('Password Error','Your new password does not match the confirm password');
               $(divid).html('').show();
               exit;
          } //end of confirm password

          if(confirm("Are you sure you want to perform this operation?"))
          {
               $.post(url,{contentvar:cv,ref:newpwd,oldpwd:oldpwd},function(data){  //ajaxfile/scriptfile_a is called undernith
                    $(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile

               });
          } //end of if confirm is true
          else
          $(divid).html('').show();

     }//end of password mgt

     /*if(cv=='logout') //start putme_logout
     {
     $.post(url,{contentvar:cv,ref:v},function(data){  //ajaxfile/scriptfile_a is called undernith
     $(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile

});
}//end of logout */

if(cv=='close_dialog')
{
     var div_id="#"+v;
     $(div_id).window('close');
     //$(div_id).window('open');
} //end of open dialog
if(cv=='print_dialog')
{
     var div_id="#"+v;
     $(div_id).printElement();
     //$(div_id).window('open');
} //end of open dialog
}
</script>
<div id="site_title"><a href="#">Uni<span>lorin</span></a></div>
<!--<div class="sidebar">-->


<div class="easyui-accordion" style="width:260px;">
     <!-- applicant accordion ---->
     <?php if($login_status=='staff') {  //applicant menu section ?>
          <div title="MY MENU" data-options="iconCls:'icon-ok'" style="padding:10px;"> <!-- first accordion -->
               <ul> <?php $role="staff"; $r_val=@base64_encode($role); ?>

                    <li><a href='change_password.php?r_val=<?php echo $r_val; ?>'>Password Management</a></li>
                    <li><a href='staff.php?r_val=<?php echo $r_val; ?>'>Staff Profile Update</a></li>


               </ul>
          </div> <!-- end of first accordion -->
     <?php } //end of MENU for applicant ?>
     <!-- applicant accordion ---->
     <?php if($login_status=='applicant') {  //applicant menu section ?>
          <div title="MY MENU" data-options="iconCls:'edit_add'" style="padding:10px;"> <!-- first accordion -->
               <ul> <?php $role="Applicant"; $r_val=@base64_encode($role); ?>

                    <li><a href='hr_app_record.php?r_val=<?php echo $r_val; ?>'>Continue Application</a></li>
                    <li><a href='hr_app_cv.php?r_val=<?php echo $r_val; ?>&fileno=<?php echo base64_encode($login_id);?>' target="_blank">Print Curriculum Vitae</a></li>

               </ul>
          </div> <!-- end of first accordion -->
     <?php } //end of MENU for applicant ?>
     <!-- End of applicant menu -->

     <!-- self service accordion -->
     <?php if($login_status=='staff' and strtolower($val_title[1])=='hr') { $role="self_service"; $r_val=@base64_encode($role); ?>
     <div title="SELF SERVICE MENU" data-options="iconCls:'icon-ok'" style="padding:10px;"> <!-- lecturer accordion -->
          <ul>
               <li><a href='hr_password_mgt.php?r_val=<?php echo $r_val; ?>'>Password Management</a></li>
               <li><a href='hr_staff_record.php?r_val=<?php echo $r_val; ?>'>Update Record</a></li>
               <li><a href='hr_staff_cv.php?r_val=<?php echo $r_val; ?>&fileno=<?php echo base64_encode($login_id);?>' target="_blank">Print Curriculum Vitae</a></li>
               <li><a href='hr_staff_promotion_application.php?r_val=<?php echo $r_val; ?>'>Apply for Promotion</a></li>
               <li><a href='hr_staff_promotion_rpt_opt.php?r_val=<?php echo $r_val; ?>'>Promotion Application Printout</a></li>
               <li><a href='hr_grievance.php?r_val=<?php echo $r_val; ?>'>Grievance Management</a></li>
               <li><a href='hr_leave_app.php?r_val=<?php echo $r_val; ?>'>Leave Application</a></li>
               <li><a href='hr_staff_clinic.php?r_val=<?php echo $r_val; ?>'>Clinic Form</a></li>
               <li><a href='hr_loan_app.php?r_val=<?php echo $r_val; ?>'>Loan Application</a></li>
               <li><a href='hr_loan_guarantor.php?r_val=<?php echo $r_val; ?>'>Loan Guarantor</a></li>
               <li><a href='hr_posting_history.php?r_val=<?php echo $r_val; ?>'>My Posting History</a></li>
          </ul>
     </div>
<?php }//end of lecturer role ?>
<!-- end of self service accordion -->

<!-- Auto-read role sidebar starts here for staff-->
<!-- /////////////////////////////////////////////////////////////////////////////////////////////// -->
<?php
// echo $login_status;
if($login_status=='staff')
{
     //echo $role."=$login_id=>$login_status<br>123";
     $res_r=@mysqli_query($con, "select * from users_roletb where fileno='$login_id' and status='Active' order by role");
     $count =  mysqli_num_rows($res_r);
     if ($count == 0){
          $res_r=@mysqli_query($con, "select * from users_roletb where fileno='All' and status='Active' order by role");

     }
     while($rs_r=@mysqli_fetch_array($res_r))
     {
          $role=trim(@$rs_r['role']);

          $res_p=@mysqli_query($con, "select caption from roletb where role='$role'");
          $rs_p=@mysqli_fetch_array($res_p);
          $val=@$rs_p['caption'];

          $role2=$role3=strtoupper(trim($val));
          //echo "ROLE: $role ROLE 2: $role2 ROLE 3: $role3";
          $r_val=@base64_encode($role);
          if (trim($role2)=="SUPER ADMIN") $role3="ADMINISTRATOR"; //else $role3=$role2;
          if (trim($role2)=="CASH OFFICER") $role3="TREASURY"; //else $role3=$role2;
          ?>

          <div title="<?php echo $role3;?>" data-options="iconCls:'icon-ok'" style="padding:10px;"> <!-- auto-create accordion -->

               <?php if($role=='Super Admin') { ?>
                    <ul class="easyui-tree" data-options="lines:true"> <!-- for creating the tree menu-->
                         <li> <!-- main tree heading -->
                              <span>MENU</span>
                              <ul> <!-- tree for the main menu first level-->


                                   <!------------------------ start of first tree menu item ------------------->
                                   <li data-options="state:'closed'"> <!-- first tree menu item -->
                                        <span>BURSARY DEPARTMENT</span>
                                        <ul>
                                             <li>
                                                  <span>Setup Menu</span>
                                                  <ul>

                                                       <!--<li><a href='school.php?r_val=<?php echo $r_val; ?>'>School Setup</a></li>-->

                                                       <li><a href='department.php?r_val=<?php echo $r_val; ?>'>Department Setup</a></li>
                                                       <li><a href='unit.php?r_val=<?php echo $r_val; ?>'>Unit Setup</a></li>
                                                       <li><a href='account_department.php?r_val=<?php echo $r_val; ?>'>Accounting Departments Setup</a></li>
                                                       <li><a href='bank.php?r_val=<?php echo $r_val; ?>'>Bank Setup</a></li>
                                                       <li><a href='bank_account.php?r_val=<?php echo $r_val; ?>'>Bank Account/Departmental Account Allocation</a></li>


                                                       <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Departmental Account Allocation</a></li>
                                                       <li><a href='#?r_val=<?php echo $r_val; ?>'>Grade Level Setup</a></li>
                                                       <li><a href='#?r_val=<?php echo $r_val; ?>'>Grade Level by Category</a></li>
                                                       <li><a href='#?r_val=<?php echo $r_val; ?>'>Staff Step Setup</a></li>-->
                                                       <li><a href='salary_scale_setup.php?r_val=<?php echo $r_val; ?>'>Salary Scale Name Setup</a></li>
                                                       <li><a href='salary_code_setup.php?r_val=<?php echo $r_val; ?>'>Salaries Account Code Setup</a></li>
                                                       <li><a href='salary_scale_setup.php?r_val=<?php echo $r_val; ?>&op_id=consolidated'>Consolidated Pay Setup</a></li>
                                                       <!--<li><a href='salary_scale.php?r_val=<?php echo $r_val; ?>'>Salary Scale/Structure</a></li>-->
                                                       <li><a href='staff.php?r_val=<?php echo $r_val; ?>'>Staff Setup</a></li>
                                                       <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Role Setup</a></li>
                                                  -->                                                  <li><a href='roles_allocation.php?r_val=<?php echo $r_val; ?>'>Staff Role Allocation</a></li>
                                                  <li><a href='folio.php?r_val=<?php echo $r_val; ?>'>Chart of Account Setup</a></li>
                                                  <!--<li><a href='schfeebreakdown.php?r_val=<?php echo $r_val; ?>'>School Fees Breakdown</a></li>
                                                  <li><a href='schfeeposting.php?r_val=<?php echo $r_val; ?>'>School Fees Posting</a></li>
                                                  <li><a href='posting_revenue.php?r_val=<?php echo $r_val; ?>'>Revenue Posting</a></li>
                                                  <li><a href='posting_expenditure.php?r_val=<?php echo $r_val; ?>'>Expenditure Posting</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Posting</a></li>-->
                                                  <li><a href='fin_year.php?r_val=<?php echo $r_val; ?>'>Financial Year Setup</a></li>
                                                  <li><a href='budget_entry.php?r_val=<?php echo $r_val; ?>'>Budget Entry</a></li>
                                                  <li><a href='budget_comit.php?r_val=<?php echo $r_val; ?>'>Commit Voucher</a></li>
                                                  <li><a href='budget.php?r_val=<?php echo $r_val; ?>'>Budget</a></li>
                                                  <li><a href='voucher.php?r_val=<?php echo $r_val; ?>'>Voucher</a></li>
                                                  <li><a href='voucher_process.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>
                                                  <li><a href='voucher_schedule.php?r_val=<?php echo $r_val; ?>'>Batch Payment Processing</a></li>
                                                  <li><a href='voucher_approval.php?r_val=<?php echo $r_val; ?>'>Approve Scheduled Voucher</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Initialization Setup</a></li>
                                                  <li><a href='taxrate.php?r_val=<?php echo $r_val; ?>'>Tax Rate Setup</a></li>
                                                  <li><a href='deduction_definition.php?r_val=<?php echo $r_val; ?>'>Deduction Definition (Group)</a></li>
                                                  <li><a href='deduction_definition_indv.php?r_val=<?php echo $r_val; ?>'>Deduction Definition (Individual)</a></li>
                                                  <li><a href='allowance_definition.php?r_val=<?php echo $r_val; ?>'>Allowance Definition (Group)</a></li>
                                                  <li><a href='otherpayment_source.php?r_val=<?php echo $r_val; ?>'>Allowance Definition (Individual)</a></li>
                                                  <li><a href='deduction_exception.php?r_val=<?php echo $r_val; ?>'>Deduction Exception</a></li>
                                                  <li><a href='prorata.php?r_val=<?php echo $r_val; ?>'>Salary Proration</a></li>
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Other Payment Skip</a></li>-->
                                                  <li><a href='annual_step_increament.php?r_val=<?php echo $r_val; ?>'>Annual Step Increament</a></li>
                                                  <li><a href='salary_computation.php?r_val=<?php echo $r_val; ?>'>Salary Computation</a></li>
                                                  <li><a href='tax_setup.php?r_val=<?php echo $r_val; ?>'>Tax Definition</a></li>
                                                  <li><a href='salary_approval.php?r_val=<?php echo $r_val; ?>'>Approval of Salary</a></li>

                                             </ul>
                                        </li>


                                        <li>
                                             <span>Account Reports</span>
                                             <ul>
                                                  <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>'>Cash Book</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Journal</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>General Ledger</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Trial Balance</a></li>
                                                  <li><a href='voucher_print.php?r_val=<?php echo $r_val; ?>'>Payment Voucher</a></li>
                                                  <li><a href='voucher_schedule_print.php?r_val=<?php echo $r_val; ?>'>Payment Scheduled Vouchers</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Mandate</a></li>
                                                  <li><a href='budget_rep_opt.php?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>

                                             </ul>
                                        </li>


                                        <li>
                                             <span>Salary Reports</span>
                                             <ul>
                                                  <li><a href='salary_scale_rep_opt.php?r_val=<?php echo $r_val; ?>'>Salary Scale Report</a></li>
                                                  <li><a href='payroll_rep_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('payslip');?>'>Payslip</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Payroll/Salary Schedule</a></li>
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Individual Annual Salary</a></li>-->
                                                  <li><a href='payroll_rep_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('bank_list');?>'>Bank List</a></li>
                                                  <li><a href='salary_report_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('bank_list_summary');?>'>Bank List Summary</a></li>
                                                  <li><a href='salary_report_opt2.php?r_val=<?php echo $r_val; ?>'>Payroll Summary/Analysis</a></li>
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Deduction Analysis</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Deduction Summary</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Monthly Payroll Analysis</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Monthly Grosspay Summary</a></li>-->
                                                  <li><a href='salary_report_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('three_column');?>'>Three Column Analysis</a></li>
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Salaries and Wages Journal</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Annual Grosspay Summary</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Annual Deduction Summary</a></li>
                                                  <li><a href='?r_val=<?php echo $r_val; ?>'>Grouped Pay Record Card (PRC)</a></li>-->

                                             </ul>
                                        </li>

                                   </ul> <!-- end of first tree menu item - bursary department-->
                              </li>
                              <!------------------------ end of first tree menu item  - bursary department ----------------->



                              <li data-options="state:'closed'"> <!-- second tree item - registry-->
                                   <span>HUMAN RESOURCES (HR)</span>
                                   <ul>
                                        <li>
                                             <span>Setup Menu</span>
                                             <ul>
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>O'Level Exam Setup</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>O'Level Subject</a></li>-->
                                                  <li><a href='hr_position.php?r_val=<?php echo $r_val; ?>'>Positions Setup</a></li>
                                                  <!--<li><a href='hr_holiday.php?r_val=<?php echo $r_val; ?>'>Public Holidays</a></li>-->
                                                  <li><a href='hr_leave.php?r_val=<?php echo $r_val; ?>'>Leave Setup</a></li>
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Application Form</a></li>-->
                                                  <li><a href='roles_allocation.php?r_val=<?php echo $r_val; ?>'>Staff Role Allocation</a></li>
                                                  <li><a href='hr_staff_appointment.php?r_val=<?php echo $r_val; ?>'>Staff Appointment</a></li>
                                                  <li><a href='hr_staff_assumption.php?r_val=<?php echo $r_val; ?>'>Staff Assumption of Duty</a></li>
                                                  <li><a href='hr_staff_record.php?r_val=<?php echo $r_val; ?>'>Staff Record Setup</a></li>

                                                  <li><a href='hr_posting.php?r_val=<?php echo $r_val; ?>'>Staff Posting</a></li>
                                                  <li><a href='hr_disciplinary.php?r_val=<?php echo $r_val; ?>'>Disciplinary Management</a></li>
                                                  <li><a href='hr_grievance_approval.php?r_val=<?php echo $r_val; ?>'>Grievance Management</a></li>
                                                  <li><a href='hr_leave_approval.php?r_val=<?php echo $r_val; ?>'>Leave Management</a></li>
                                                  <li><a href='hr_manpowerbudget.php?r_val=<?php echo $r_val; ?>'>Manpower Budgeting</a></li>
                                                  <li><a href='hr_staff_recognition.php?r_val=<?php echo $r_val; ?>'>Recognition and Awards</a></li>
                                                  <!--<li><a href='hr_staff_clinic.php?r_val=<?php echo $r_val; ?>'>Clinic Form</a></li>-->
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Staff Training Application</a></li>-->
                                                  <li><a href='hr_staff_recognition.php?r_val=<?php echo $r_val; ?>'>Staff Training Approval</a></li>
                                                  <li><a href='hr_staff_promotion_application.php?r_val=<?php echo $r_val; ?>'>Promotion Application</a></li>
                                                  <!-- <li><a href='#?r_val=<?php echo $r_val; ?>'>Promotion Appraisal</a></li>-->
                                                  <li><a href='hr_staff_promotion_approval.php?r_val=<?php echo $r_val; ?>'>Promotion Approval</a></li>
                                                  <!--<li><a href='hr_loan_app.php?r_val=<?php echo $r_val; ?>'>Loan Application</a></li>-->
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Loan Guarantor</a></li>-->
                                                  <li><a href='hr_loan_approval.php?r_val=<?php echo $r_val; ?>'>Loan Approval</a></li>
                                                  <li><a href='hr_regularization.php?r_val=<?php echo $r_val; ?>'>Regularization of Appointment</a></li>
                                                  <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Confirmation Application</a></li>
                                                  <li><a href='#?r_val=<?php echo $r_val; ?>'>Confirmation Appraisal</a></li>-->
                                                  <li><a href='hr_regularization.php?r_val=<?php echo $r_val; ?>'>Confirmation of Appointment</a></li>
                                                  <li><a href='hr_retirement.php?r_val=<?php echo $r_val; ?>'>Staff Record Update(e.g Retirement, Retrenchment, Death, Resignation)</a></li>
                                             </ul>
                                        </li>

                                        <li>
                                             <span>Report Menu</span>
                                             <ul>
                                                  <li><a href='hr_print_staff_cv.php?r_val=<?php echo $r_val; ?>'>Print Curriculum Vitae</a></li>
                                                  <li><a href='hr_staff_promotion_rpt_opt.php?r_val=<?php echo $r_val; ?>'>Promotion Application Printout</a></li>
                                                  <li><a href='hr_current_staff_opt.php?r_val=<?php echo $r_val; ?>'>List of Current Staff</a></li>
                                                  <li><a href='hr_general_report.php?r_val=<?php echo $r_val; ?>'>Report Section</a></li>
                                             </ul>
                                        </li>

                                   </ul> <!-- end of registry ul menu item -->
                              </li> <!-- end of registry tree menu item -->





                         </ul> <!-- end of tree for the main menu first level-->

                    </li> <!-- end of main tree heading -->
               </ul> <!-- end of creating tree menu -->


          <?php }//end of super admin role ?>



          <?php if($role=='Administrator') { ?>

               <ul>

                    <!--<li><a href='school.php?r_val=<?php echo $r_val; ?>'>School Setup</a></li>-->
                    <li><a href='department.php?r_val=<?php echo $r_val; ?>'>Department Setup</a></li>
                    <li><a href='unit.php?r_val=<?php echo $r_val; ?>'>Unit Setup</a></li>
                    <li><a href='bank.php?r_val=<?php echo $r_val; ?>'>Bank Setup</a></li>
                    <li><a href='account_department.php?r_val=<?php echo $r_val; ?>'>Accounting Departments Setup</a></li>
                    <li><a href='bank_account.php?r_val=<?php echo $r_val; ?>'>Bank/Departmental Account Allocation</a></li>
                    <li><a href='salary_scale_setup.php?r_val=<?php echo $r_val; ?>'>Salary Scale Name Setup</a></li>
                    <li><a href='salary_code_setup.php?r_val=<?php echo $r_val; ?>'>Salaries Account Code Setup</a></li>
                    <li><a href='salary_scale_setup.php?r_val=<?php echo $r_val; ?>&op_id=consolidated'>Consolidated Pay Setup</a></li>
                    <!--<li><a href='salary_scale.php?r_val=<?php echo $r_val; ?>'>Salary Scale/Structure</a></li>-->
                    <li><a href='staff.php?r_val=<?php echo $r_val; ?>'>Staff Setup</a></li>
                    <li><a href='lock_posting.php?r_val=<?php echo $r_val; ?>'>Lock Account Posting</a></li>
                    <!--<li><a href='hr_app_record.php?r_val=<?php echo $r_val; ?>'>Continue Application</a></li>-->
                    <li><a href='roles_allocation.php?r_val=<?php echo $r_val; ?>'>Staff Role Allocation</a></li>
                    <li><a href='folio.php?r_val=<?php echo $r_val; ?>'>Chart of Account Setup</a></li>
                    <!--<li><a href='posting_revenue.php?r_val=<?php echo $r_val; ?>'>Revenue Posting</a></li>
                    <li><a href='posting_expenditure.php?r_val=<?php echo $r_val; ?>'>Expenditure Posting</a></li>
                    <li><a href='schfeebreakdown.php?r_val=<?php echo $r_val; ?>'>School Fees Breakdown</a></li>
                    <li><a href='schfeeposting.php?r_val=<?php echo $r_val; ?>'>School Fees Posting</a></li>
                    <li><a href='budget_entry.php?r_val=<?php echo $r_val; ?>'>Budget Entry</a></li>
                    <li><a href='budget_comit.php?r_val=<?php echo $r_val; ?>'>Commit Voucher</a></li>
                    <li><a href='budget.php?r_val=<?php echo $r_val; ?>'>Budget</a></li>
                    <li><a href='voucher.php?r_val=<?php echo $r_val; ?>'>Voucher</a></li>
                    <li><a href='voucher_process.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>
                    <li><a href='voucher_schedule.php?r_val=<?php echo $r_val; ?>'>Batch Payment Processing</a></li>
                    <li><a href='voucher_approval.php?r_val=<?php echo $r_val; ?>'>Approve Scheduled Voucher</a></li>
                    <li><a href='#?r_val=<?php echo $r_val; ?>'>Initialization Setup</a></li>-->
                    <li><a href='fin_year.php?r_val=<?php echo $r_val; ?>'>Financial Year Setup</a></li>
                    <!--li><a href='salary_report_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('bank_list_summary');?>'>Bank List Summary</a></li>
                    <li><a href='taxrate.php?r_val=<?php echo $r_val; ?>'>Tax Rate Setup</a></li>
                    <li><a href='deduction_definition.php?r_val=<?php echo $r_val; ?>'>Deduction Definition (Group)</a></li>
                    <li><a href='deduction_definition_indv.php?r_val=<?php echo $r_val; ?>'>Deduction Definition (Individual)</a></li>
                    <li><a href='allowance_definition.php?r_val=<?php echo $r_val; ?>'>Allowance Definition (Group)</a></li>
                    <li><a href='otherpayment_source.php?r_val=<?php echo $r_val; ?>'>Allowance Definition (Individual)</a></li>
                    <li><a href='deduction_exception.php?r_val=<?php echo $r_val; ?>'>Deduction Exception</a></li>
                    <li><a href='prorata.php?r_val=<?php echo $r_val; ?>'>Salary Proration</a></li>
                    <li><a href='annual_step_increament.php?r_val=<?php echo $r_val; ?>'>Annual Step Increament</a></li>
                    <li><a href='salary_computation.php?r_val=<?php echo $r_val; ?>'>Salary Computation</a></li>
                    <li><a href='prorate_arrears.php?r_val=<?php echo $r_val; ?>'>Auto-Proration as Arrears</a></li>
                    <li><a href='salary_approval.php?r_val=<?php echo $r_val; ?>'>Approval of Salary</a></li>

                    <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>'>Cash Book</a></li>
                    <li><a href='#?r_val=<?php echo $r_val; ?>'>Journal</a></li>
                    <li><a href='#?r_val=<?php echo $r_val; ?>'>General Ledger</a></li>
                    <li><a href='#?r_val=<?php echo $r_val; ?>'>Trial Balance</a></li>
                    <li><a href='voucher_print.php?r_val=<?php echo $r_val; ?>'>Payment Voucher</a></li>
                    <li><a href='voucher_schedule_print.php?r_val=<?php echo $r_val; ?>'>Payment Scheduled Vouchers</a></li>
                    <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Mandate</a></li>
                    <li><a href='budget_rep_opt.php?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>

                    <li><a href='salary_scale_rep_opt.php?r_val=<?php echo $r_val; ?>'>Salary Scale Report</a></li>
                    <li><a href='payroll_rep_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('payslip');?>'>Payslip</a></li>
                    <li><a href='#?r_val=<?php echo $r_val; ?>'>Payroll/Salary Schedule</a></li>
                    <li><a href='payroll_rep_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('bank_list');?>'>Bank List</a></li>

                    <li><a href='salary_report_opt2.php?r_val=<?php echo $r_val; ?>'>Payroll Summary/Analysis</a></li>
                    <li><a href='salary_report_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('three_column');?>'>Three Column Analysis</a></li>
                    <li><a href='#?r_val=<?php echo $r_val; ?>'>Salaries and Wages Journal</a></li-->



               </ul>

          <?php }//end of admin role ?>
          <?php if($role=='Clerk' ) { ?>

               <ul>
                    <li><a href='mail.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Mails</a></li>
                    <!--<li><a href='mail.php?r_val=<?php echo $r_val; ?>&mail_opt=in'>Incoming Mail</a></li>
                    <li><a href='mail.php?r_val=<?php echo $r_val; ?>&mail_opt=out'>Outgoing Mail</a></li>
                    <li><a href='mail.php?r_val=<?php echo $r_val; ?>&mail_opt=search'>Search for Mail</a></li>-->

               </ul>

          <?php }//end of final account role ?>
          <?php if($role=='Revenue Officer' ) { ?>


               <ul>
                    <!--<li><a href='posting_revenue.php?r_val=<?php echo $r_val; ?>'>Revenue Posting</a></li>-->
                    <li><a href='posting_revenue2.php?r_val=<?php echo $r_val; ?>'>Single Revenue Posting</a></li>
                    <li><a href='revenue_rep_opt.php?r_val=<?php echo $r_val; ?>'>Revenue Report</a></li>
                    <!-- <li><a href='revenue_code_setup.php?r_val=<?php echo $r_val; ?>'>Add Category</a></li>-->
                    <li><a href='upload_final_account2.php?r_val=<?php echo $r_val; ?>'>Upload Receipt</a></li>
                    <li><a href='receipt_submit.php?r_val=<?php echo $r_val; ?>'>Print Receipt</a></li>
                    <li><a href='journal_entry2.php?r_val=<?php echo $r_val; ?>'>Raise Journal</a></li>
                    <li><a href='upload_income_budget.php?r_val=<?php echo $r_val; ?>'>Upload Income Budget</a></li>
                    <!-- <li><a href='voucher_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Entries</a></li>-->
                    <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook Treasury'>Cashbook(Treasury)</a></li>


               </ul>

          <?php }//end of final account role ?>
          <?php if($role=='Checked by Officer') { ?>

               <ul>
                    <li><a href='voucher_process_new.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>
                    <!--li><a href='voucher_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Entries</a></li-->
                    <li><a href='voucher_list_for_edit.php?r_val=<?php echo $r_val; ?>'>Voucher List</a></li>
               </ul>

          <?php }//end of final account role ?>
          <?php if($role=='Authorized Officer') { ?>

               <ul>
                    <li><a href='voucher_process_new.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>

               </ul>

          <?php }//end of final account role ?>
          <?php if($role=='Final Authorized Officer') { ?>

               <ul>
                    <li><a href='voucher_process_new.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>
                    <li><a href='journal_process.php?r_val=<?php echo $r_val; ?>'>Journal Processing</a></li>
               </ul>

          <?php }//end of final account role ?>
          <?php if($role=='Budget and Expenditure' ) { ?>

               <ul>
                    <li><a href='voucher_process.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>
                    <li><a href='mail_assign.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Assigned Mail</a></li>

               </ul>

          <?php }//end of final account role ?>
          <?php if($role=='Pay Slip'  ) { ?>

               <ul>

                    <li><a href='slip_print.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Print Pay Slip</a></li>
                    <!--<li><a href='voucher_y.php?r_val=<?php echo $r_val; ?>'>Raise Voucher</a></li>
                    <li><a href='voucher_sal.php?r_val=<?php echo $r_val; ?>'>Raise Voucher (SALARY)</a></li>-->

               </ul>

          <?php }//end of final account role ?>
          <?php if($role=='Registry Admin') { ?>

               <ul>
                    <li><a href='department.php?r_val=<?php echo $r_val; ?>'>Department Setup</a></li>
                    <li><a href='unit.php?r_val=<?php echo $r_val; ?>'>Unit Setup</a></li>
                    <li><a href='bank.php?r_val=<?php echo $r_val; ?>'>Bank Setup</a></li>
                    <li><a href='hr_position.php?r_val=<?php echo $r_val; ?>'>Positions Setup</a></li>
                    <!--<li><a href='hr_holiday.php?r_val=<?php echo $r_val; ?>'>Public Holidays</a></li>-->
                    <li><a href='hr_leave.php?r_val=<?php echo $r_val; ?>'>Leave Setup</a></li>
                    <li><a href='roles_allocation.php?r_val=<?php echo $r_val; ?>'>Staff Role Allocation</a></li>
                    <li><a href='hr_staff_appointment.php?r_val=<?php echo $r_val; ?>'>Staff Appointment</a></li>
                    <li><a href='hr_staff_assumption.php?r_val=<?php echo $r_val; ?>'>Staff Assumption of Duty</a></li>
                    <li><a href='hr_staff_record.php?r_val=<?php echo $r_val; ?>'>Staff Record Setup</a></li>
                    <li><a href='hr_posting.php?r_val=<?php echo $r_val; ?>'>Staff Posting</a></li>
                    <li><a href='hr_disciplinary.php?r_val=<?php echo $r_val; ?>'>Disciplinary Management</a></li>
                    <li><a href='hr_grievance_approval.php?r_val=<?php echo $r_val; ?>'>Grievance Management</a></li>
                    <li><a href='hr_leave_approval.php?r_val=<?php echo $r_val; ?>'>Leave Management</a></li>
                    <li><a href='hr_manpowerbudget.php?r_val=<?php echo $r_val; ?>'>Manpower Budgeting</a></li>
                    <li><a href='hr_staff_recognition.php?r_val=<?php echo $r_val; ?>'>Recognition and Awards</a></li>
                    <li><a href='hr_staff_recognition.php?r_val=<?php echo $r_val; ?>'>Staff Training Approval</a></li>
                    <li><a href='hr_staff_promotion_application.php?r_val=<?php echo $r_val; ?>'>Promotion Application</a></li>
                    <li><a href='hr_staff_promotion_approval.php?r_val=<?php echo $r_val; ?>'>Promotion Approval</a></li>
                    <li><a href='hr_loan_approval.php?r_val=<?php echo $r_val; ?>'>Loan Approval</a></li>
                    <li><a href='hr_regularization.php?r_val=<?php echo $r_val; ?>'>Regularization of Appointment</a></li
                         ><li><a href='hr_regularization.php?r_val=<?php echo $r_val; ?>'>Confirmation of Appointment</a></li>
                         <li><a href='hr_retirement.php?r_val=<?php echo $r_val; ?>'>Staff Record Update(e.g Retirement, Retrenchment, Death, Resignation)</a></li>
                         <li><a href='hr_print_staff_cv.php?r_val=<?php echo $r_val; ?>'>Print Curriculum Vitae</a></li>
                         <li><a href='hr_staff_promotion_rpt_opt.php?r_val=<?php echo $r_val; ?>'>Promotion Application Printout</a></li>
                         <li><a href='hr_current_staff_opt.php?r_val=<?php echo $r_val; ?>'>List of Current Staff</a></li>
                         <li><a href='hr_general_report.php?r_val=<?php echo $r_val; ?>'>Report Section</a></li>


                    </ul>

               <?php }//end of Registry Admin role ?>

               <?php if($role=='Registry Officer') { ?>

                    <ul>
                         <li><a href='department.php?r_val=<?php echo $r_val; ?>'>Department Setup</a></li>
                         <li><a href='unit.php?r_val=<?php echo $r_val; ?>'>Unit Setup</a></li>
                         <li><a href='bank.php?r_val=<?php echo $r_val; ?>'>Bank Setup</a></li>
                         <li><a href='hr_position.php?r_val=<?php echo $r_val; ?>'>Positions Setup</a></li>
                         <!--<li><a href='hr_holiday.php?r_val=<?php echo $r_val; ?>'>Public Holidays</a></li>-->
                         <li><a href='hr_leave.php?r_val=<?php echo $r_val; ?>'>Leave Setup</a></li>
                         <!--<li><a href='roles_allocation.php?r_val=<?php echo $r_val; ?>'>Staff Role Allocation</a></li>-->
                         <li><a href='hr_staff_appointment.php?r_val=<?php echo $r_val; ?>'>Staff Appointment</a></li>
                         <li><a href='hr_staff_assumption.php?r_val=<?php echo $r_val; ?>'>Staff Assumption of Duty</a></li>
                         <li><a href='hr_staff_record.php?r_val=<?php echo $r_val; ?>'>Staff Record Setup</a></li>
                         <li><a href='hr_posting.php?r_val=<?php echo $r_val; ?>'>Staff Posting</a></li>
                         <li><a href='hr_disciplinary.php?r_val=<?php echo $r_val; ?>'>Disciplinary Management</a></li>
                         <li><a href='hr_grievance_approval.php?r_val=<?php echo $r_val; ?>'>Grievance Management</a></li>
                         <li><a href='hr_leave_approval.php?r_val=<?php echo $r_val; ?>'>Leave Management</a></li>
                         <li><a href='hr_manpowerbudget.php?r_val=<?php echo $r_val; ?>'>Manpower Budgeting</a></li>
                         <li><a href='hr_staff_recognition.php?r_val=<?php echo $r_val; ?>'>Recognition and Awards</a></li>
                         <li><a href='hr_staff_recognition.php?r_val=<?php echo $r_val; ?>'>Staff Training Approval</a></li>
                         <li><a href='hr_staff_promotion_application.php?r_val=<?php echo $r_val; ?>'>Promotion Application</a></li>
                         <li><a href='hr_staff_promotion_approval.php?r_val=<?php echo $r_val; ?>'>Promotion Approval</a></li>
                         <li><a href='hr_loan_approval.php?r_val=<?php echo $r_val; ?>'>Loan Approval</a></li>
                         <li><a href='hr_regularization.php?r_val=<?php echo $r_val; ?>'>Regularization of Appointment</a></li
                              ><li><a href='hr_regularization.php?r_val=<?php echo $r_val; ?>'>Confirmation of Appointment</a></li>
                              <li><a href='hr_retirement.php?r_val=<?php echo $r_val; ?>'>Staff Record Update(e.g Retirement, Retrenchment, Death, Resignation)</a></li>
                              <li><a href='hr_print_staff_cv.php?r_val=<?php echo $r_val; ?>'>Print Curriculum Vitae</a></li>
                              <li><a href='hr_staff_promotion_rpt_opt.php?r_val=<?php echo $r_val; ?>'>Promotion Application Printout</a></li>
                              <li><a href='hr_current_staff_opt.php?r_val=<?php echo $r_val; ?>'>List of Current Staff</a></li>
                              <li><a href='hr_general_report.php?r_val=<?php echo $r_val; ?>'>Report Section</a></li>


                         </ul>

                    <?php }//end of Registry Admin role ?>

                    <?php if($role=='Accountant') { ?>
                         <ul>
                              <li><a href='posting.php?r_val=<?php echo $r_val; ?>'>Revenue Posting</a></li>
                              <li><a href='#?r_val=<?php echo $r_val; ?>'>Cash Book</a></li>
                              <li><a href='#?r_val=<?php echo $r_val; ?>'>Journal</a></li>
                              <li><a href='#?r_val=<?php echo $r_val; ?>'>General Ledger</a></li>
                              <li><a href='#?r_val=<?php echo $r_val; ?>'>Trial Balance</a></li>
                              <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Voucher Report</a></li>
                              <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Schedule Report</a></li>
                              <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Mandate Report</a></li>
                              <li><a href='#?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>
                         </ul>
                    <?php }//end of accountant role ?>




                    <?php if($role=='Prepared Officer') { ?>

                         <ul>
                              <li><a href='mail.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Mails</a></li>
                              <li><a href='advance_mail.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Mails Register</a></li>
                              <!--li><a href='voucher.php?r_val=<?php echo $r_val; ?>'>Raise Voucher</a></li-->
                              <li><a href='revenue_rep_opt_audit.php?r_val=<?php echo $r_val; ?>'>ADVANCES RETIREMENT</a></li>
                              <li><a href='voucher_list_for_edit.php?r_val=<?php echo $r_val; ?>'>Voucher List</a></li>
                              <li><a href='revenue_rep_opt_audit.php?r_val=<?php echo $r_val; ?>&query'>Voucher Query Report</a></li>
                              <li><a href='revenue_rep_opt_audit.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger Salary'>Purchase Advance Register</a>
                                   <!--li><a href='voucher_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Entries</a></li-->
                                   <li><a href='pa_register.php?r_val=<?php echo $r_val; ?>'>Payment Vouchers</a></li>
                                   <li><a href='voucher_entrylist.php?r_val=<?php echo $r_val; ?>'>My Voucher Entries</a></li>

                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger Salary'>General Ledger</a></li>
                                   <?php $qj=mysqli_query($con, "SELECT j.* FROM journal_code_user j INNER JOIN unittb u ON j.jvcode=u.unit_code WHERE j.fileno='{$login_id}' AND u.dept_code != '126' ");
                                   if(mysqli_num_rows($qj)>0){ ?>

                                        <li><hr></li>
                                        <li><a href='budget_entry.php?r_val=<?php echo $r_val; ?>&faculty'>Budget Entry</a></li>
                                        <li><a href='budget_comit.php?r_val=<?php echo $r_val; ?>&faculty'>Commit Voucher</a></li>
                                        <li><hr></li>
                                        <li><a href='voucher_list_for_treasury.php?r_val=<?php echo $r_val; ?>'>Update PV.NO.</a></li>
                                        <li><a href='voucher_list_for_schedule.php?r_val=<?php echo $r_val; ?>'>PV Schedule</a></li>
                                        <li><a href='voucher_list_scheduled.php?r_val=<?php echo $r_val; ?>'>PV Schedule List</a></li>
                                        <li><a href='voucher_list_for_payment.php?r_val=<?php echo $r_val; ?>'>PV Posting</a></li>
                                        <li><hr></li>
                                   <?php } ?>
                              </ul>

                         <?php }//end of budget role ?>

                         <?php if($role=='Loan Manager') { ?>

                              <ul>
                                   <li><a href='mail.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Mails</a></li>
                                   <li><a href='retirement.php?r_val=<?php echo $r_val; ?>'>VOUCHER LIST/RETIREMENT</a></li>
                                   <li><a href='loan_entry.php?r_val=<?php echo $r_val; ?>'>Loan Entry</a></li>
                                   <li><a href='loan.php?r_val=<?php echo $r_val; ?>'>Loan Application</a></li>
                                   <li><a href='loanrepayment.php?r_val=<?php echo $r_val; ?>'>Loan Repayment</a></li>
                                   <li><a href='loanrecord.php?r_val=<?php echo $r_val; ?>'>Loan Record</a></li>
                                   <li><a href='loan_rep_opt.php?r_val=<?php echo $r_val; ?>'>Loan Schedule/Report</a></li>
                                   <li><a href='voucher_list_for_edit.php?r_val=<?php echo $r_val; ?>'>Voucher List</a></li>
                              </ul>

                         <?php }//end of budget role ?>

                         <?php if($role=='Budget Officer' ) { ?>

                              <ul>
                                   <li><a href='mail.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Mails</a></li>
                                   <li><a href='budget_entry.php?r_val=<?php echo $r_val; ?>'>Budget Entry</a></li>
                                   <li><a href='journal_entry2.php?r_val=<?php echo $r_val; ?>'>Raise Journal</a></li>
                                   <!--li><a href='voucher.php?r_val=<?php echo $r_val; ?>'>Raise Voucher</a></li-->
                                   <li><a href='budget_comit.php?r_val=<?php echo $r_val; ?>'>Commit Voucher</a></li>
                                   <li><a href='budget.php?r_val=<?php echo $r_val; ?>'>Budget</a></li>
                                   <!--li><a href='#?r_val=<?php echo $r_val; ?>'>Cash Book</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Journal</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>General Ledger</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Trial Balance</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Voucher Report</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Schedule Report</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Mandate Report</a></li-->
                                   <li><a href='budget_rep_opt.php#?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>
                                   <!--li><a href='voucher_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Entries</a></li-->
                                   <li><a href='voucher_list_for_edit.php?r_val=<?php echo $r_val; ?>'>Voucher List</a></li>
                                   <li><a href='votebook_s.php?r_val=<?php echo $r_val; ?>'>Votebook</a></li>
                                   <li><a href='deduction_report_filter.php?r_val=<?php echo $r_val; ?>'>Deductions Report</a></li>
                              </ul>

                         <?php }//end of budget role ?>

                         <?php if($role=='Journal Checked by Officer') { ?>

                              <ul>
                                   <li><a href='journal_process.php?r_val=<?php echo $r_val; ?>'>Journal Processing</a></li>
                                   <li><a href='voucher_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Entries</a></li>
                              </ul>

                         <?php }//end of final account role ?>

                         <?php if($role=='Journal Authorized Officer') { ?>

                              <ul>
                                   <li><a href='journal_process.php?r_val=<?php echo $r_val; ?>'>Journal Processing</a></li>
                              </ul>

                         <?php }//end of final account role ?>

                         <?php if($role=='Final Account') { ?>

                              <ul>
                                   <li><a href='voucher_process_new.php?r_val=<?php echo $r_val; ?>'>PV Regularization Process</a></li>
                                   <li><a href='final_recon2.php?r_val=<?php echo $r_val; ?>'>Financial Reconciliation</a></li>
                                   <li><a href='recon_upload_reports.php?r_val=<?php echo $r_val; ?>'>View Uploaded Statement</a></li>
                                   <li><a href='voucher_y.php?r_val=<?php echo $r_val; ?>'> New Post Payment</a></li>
                                   <li><a href='receipt_entry.php?r_val=<?php echo $r_val; ?>'>Post Receipt</a></li>
                                   <li><a href='journal_entry2.php?r_val=<?php echo $r_val; ?>'>Raise Journal</a></li>
                                   <li><a href='journal_process.php?r_val=<?php echo $r_val; ?>'>Journal Processing</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook'>Cashbook</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook Treasury'>Cashbook(Treasury)</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook NCOA'>Cashbook NCOA</a></li>

                                   <!-- <li><a href='trailbalance_opt.php?r_val=<?php echo $r_val; ?>&rtype=Trial Balance'>Trial Balance</a></li>-->

                                   <!-- <li><a href='trailbalance_opt.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Financial Position'>Statement of Financial Position</a></li>-->
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Payment Voucher Report'>Payment Voucher Report</a></li>
                                   <li><a href='voucher_print.php?r_val=<?php echo $r_val; ?>'>Print Payment Voucher</a></li>
                                   <li><a href='budget_rep_opt.php?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>
                                   <!--li><a href='fixed_asset_register.php?r_val=<?php echo $r_val; ?>'>Fixed Register</a></li-->
                                   <li><a href='fix_report_all.php?r_val=<?php echo $r_val; ?>'>Fixed Register</a></li>
                                   <li><a href='fix_report_all2.php?r_val=<?php echo $r_val; ?>'>Schedule of Fixed</a></li>
                                   <!-- <li><a href='voucher_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Entries</a></li>-->
                                   <li><a href='upload_final_account.php?r_val=<?php echo $r_val; ?>'>Upload Payment/Receipt</a></li>
                                   <li><a href='upload_final_account_journal.php?r_val=<?php echo $r_val; ?>'>Upload Journal</a></li>
                                   <li><a href='upload_cashbook.php?r_val=<?php echo $r_val; ?>'>Upload Cashbook</a></li>
                                   <li><a href='cashbook_compare.php?r_val=<?php echo $r_val; ?>'>Cashbook Compare</a></li>
                                   <li><a href='cashbook_compare_x.php?r_val=<?php echo $r_val; ?>'>Manual Cashbook/Remita Reconciliation</a></li>
                                   <li><a href='cashbook_compare2.php?r_val=<?php echo $r_val; ?>'>Auto Cashbook/Remita Reconciliation</a></li>

                                   <li><a href='revenue_rep_opt2.php?r_val=<?php echo $r_val; ?>'>Revenue Report</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Trial Balance'>Trial Balance</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Expenditure Analysis'>Expenditure Analysis</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Summary Trial Balance'>Summary Trial Balance</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Summary Trial Balance Ledger'>Summary General Ledger</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger'>General Ledger</a></li>
                                   <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Schedule of Property Plant And Equipment'>Schedule of Property Plant And Equipment</a></li>
                                   <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Financial Performance (IPSAS)'>Statement of Financial Performance (IPSAS)</a></li>
                                   <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Financial Position'>Statement of Financial Position (IPSAS)</a></li>
                                   <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Cash Flows'>Statement of Cash Flows(IPSAS)</a></li>


                                   <!--<li><a href='upload_asset_balance.php?r_val=<?php echo $r_val; ?>'>Upload Asset Balance</a></li>-->
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Payment Voucher Report'>Payment Voucher Report</a>
                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger Salary'>General Ledger Salary</a></li>
                                        <li><a href='account_report_opt_gen.php?r_val=<?php echo $r_val; ?>&rtype=Individual Ledger'>Individual Ledger Search</a></li></li>
                                        <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Comprehensive Income'>Statement of Comprehensive Income 2</a></li>
                                        <li><a href='trailbalance_opt.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Comprehensive Income'>Statement of Financial Performance Comprehensive</a></li>
                                   </ul>

                              <?php }//end of final account role ?>
                              <?php if($role=='Final Account 2') { ?>

                                   <ul>

                                        <li><a href='account_rollover.php?r_val=<?php echo $r_val; ?>' target='_blank'>Rollover/Opening Balance</a></li>
                                        <li><a href='lock_posting.php?r_val=<?php echo $r_val; ?>'>Lock Account Posting</a></li>

                                        <li><a href='final_recon2.php?r_val=<?php echo $r_val; ?>'>Financial Reconciliation</a></li>
                                        <li><a href='finalaccountpayment.php?r_val=<?php echo $r_val; ?>'>Post Payment</a></li>
                                        <li><a href='voucher_y.php?r_val=<?php echo $r_val; ?>'> New Post Payment</a></li>
                                        <li><a href='receipt_entry.php?r_val=<?php echo $r_val; ?>'>Post Receipt</a></li>
                                        <li><a href='journal_entry2.php?r_val=<?php echo $r_val; ?>'>Raise Journal</a></li>
                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook'>Cashbook</a></li>
                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook Treasury'>Cashbook(Treasury)</a></li>
                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Payment Voucher Report'>Payment Voucher Report</a></li>
                                        <li><a href='voucher_print.php?r_val=<?php echo $r_val; ?>'>Print Payment Voucher</a></li>
                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Trial Balance'>Trial Balance</a></li>
                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Expenditure Analysis'>Expenditure Analysis</a></li>

                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger'>General Ledger</a></li>
                                        <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Schedule of Property Plant And Equipment'>Schedule of Property Plant And Equipment</a></li>
                                        <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Financial Performance (IPSAS)'>Statement of Financial Performance (IPSAS)</a></li>
                                        <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Financial Position'>Statement of Financial Position (IPSAS)</a></li>

                                   </ul>

                              <?php }//end of final account role ?>
                              <?php if($role=='Final Account 3') { ?>

                                   <ul>
                                        <li><a href='voucher_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Vouchers</a></li>
                                        <li><a href='journal_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Journals</a></li>
                                        <li><a href='journal_edit2.php?r_val=<?php echo $r_val; ?>'>Edit Posted Journals 2</a></li>
                                        <li><a href='trailbalance_adjust.php?r_val=<?php echo $r_val; ?>'>Adjust Trial Balance</a></li>

                                   </ul>

                              <?php }//end of final account role ?>

                              <?php if($role=='VC') { ?>

                                   <ul>

                                        <li><a href='voucher_approval.php?r_val=<?php echo $r_val; ?>'>Approve Scheduled Voucher</a></li>
                                   </ul>

                              <?php }//end of VC role ?>

                              <?php if($role=='Student Account') { ?>

                                   <ul>
                                        <!--<li><a href='schfeebreakdown.php?r_val=<?php echo $r_val; ?>'>School Fees Breakdown</a></li>
                                        <li><a href='schfeeposting.php?r_val=<?php echo $r_val; ?>'>School Fees Posting</a></li>-->
                                        <li><a href='posting.php?r_val=<?php echo $r_val; ?>'>Revenue Posting</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Cash Book</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Journal</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>General Ledger</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Trial Balance</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Voucher Report</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Schedule Report</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Mandate Report</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>
                                   </ul>

                              <?php }//end of student account role ?>

                              <?php if($role=='Expenditure Control') { ?>

                                   <ul>
                                        <li><a href='mail_distribution_save.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Inbox</a></li>
                                        <li><a href='mail_assign.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Assigned Mail</a></li>
                                        <!-- <li><a href='voucher.php?r_val=<?php echo $r_val; ?>'>Voucher</a></li>-->
                                        <li><a href='budget_comit.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>
                                        <li><a href='voucher_extra_allocation_request.php?r_val=<?php echo $r_val; ?>'>Voucher Extra Allocation Request</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Journal</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>General Ledger</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Trial Balance</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Voucher Report</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Schedule Report</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Mandate Report</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>
                                   </ul>

                              <?php }//end of expenditure role ?>
                              <?php if($role=='Head Expenditure Control') { ?>

                                   <ul>
                                        <li><a href='mail_look.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Inbox</a></li>
                                        <li><a href='mail_assign.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Assigned Mail</a></li>
                                        <li><a href='journal_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Journals</a></li>
                                        <li><a href='journal_edit2.php?r_val=<?php echo $r_val; ?>'>Edit Posted Journals 2</a></li>
                                        <li><a href='journal_list_for_advance.php?r_val=<?php echo $r_val; ?>'>Correct Advance Postings</a></li>
                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook Treasury'>Cashbook</a></li>
                                        <li><a href='account_report_opt_gen.php?r_val=<?php echo $r_val; ?>&rtype=Individual Ledger'>Individual Ledger Search</a></li></li>
                                        <!--   <li><a href='mail_look2.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Queried</a></li>
                                   -->

                              </ul>

                         <?php }//end of expenditure role ?>

                         <?php if($role=='Auditor') { ?>

                              <ul>
                                   <!--li><a href='taxrate.php?r_val=<?php echo $r_val; ?>'>Tax Rate Setup</a></li-->
                                   <li><a href='voucher_process_new.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>
                                   <li><a href='voucher_list_for_edit.php?r_val=<?php echo $r_val; ?>'>Voucher List</a></li>
                                   <!--li><a href='salary_approval.php?r_val=<?php echo $r_val; ?>'>Approval of Salary</a></li-->
                                   <li><a href='budget_rep_opt.php?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>

                                   <!--li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Payment Voucher Report'>Payment Voucher Report</a></li-->

                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Expenditure Analysis'>Expenditure Analysis</a></li>


                                   <!-- <li><a href='trailbalance_opt.php?r_val=<?php echo $r_val; ?>&rtype=Trial Balance'>Trial Balance</a></li>-->
                                   <li><a href='trailbalance_opt.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Comprehensive Income'>Statement of Comprehensive Income</a></li>
                                   <li><a href='revenue_rep_opt.php?r_val=<?php echo $r_val; ?>'>Revenue Report</a></li>
                                   <li><a href='fix_report1.php?r_val=<?php echo $r_val; ?>'>Asset Report</a></li>
                                   <li><hr></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger'>General Ledger</a></li>
                                   <li><a href='account_report_opt_gen.php?r_val=<?php echo $r_val; ?>&rtype=Individual Ledger'>Individual Ledger Search</a></li></li>
                                   <li><a href='revenue_rep_opt_audit.php?r_val=<?php echo $r_val; ?>&query'>Voucher Query Report</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger Salary'>General Ledger Advance</a></li>
                                   <li><a href='revenue_rep_opt_audit.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger Salary'>Purchase Advance Register</a></li>
                                   <li><hr></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook Treasury'>Cashbook(Treasury)</a></li>

                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Trial Balance'>Trial Balance</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Summary Trial Balance'>Summary Trial Balance</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Summary Trial Balance Ledger'>Summary Trial Balance Ledger</a></li>

                                   <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Schedule of Property Plant And Equipment'>Schedule of Property Plant And Equipment</a></li>
                                   <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Financial Performance (IPSAS)'>Statement of Financial Performance (IPSAS)</a></li>
                                   <li><a href='trailbalance_opt2.php?r_val=<?php echo $r_val; ?>&rtype=Statement of Financial Position'>Statement of Financial Position (IPSAS)</a></li>
                              </ul>

                         <?php }//end of auditor role ?>

                         <?php if($role=='Bursar') { ?>

                              <ul>
                                   <li><a href='mail.php?r_val=<?php echo $r_val; ?>&mail_opt=new'>Mails</a></li>
                                   <li><a href='voucher_process.php?r_val=<?php echo $r_val; ?>'>Voucher Processing</a></li>
                                   <li><a href='voucher_extra_allocation_request.php?r_val=<?php echo $r_val; ?>'>Voucher Extra Allocation Request</a></li>
                                   <li><a href='salary_approval.php?r_val=<?php echo $r_val; ?>'>Approval of Salary</a></li>
                                   <li><a href='voucher_approval.php?r_val=<?php echo $r_val; ?>'>Approve Scheduled Voucher</a></li>
                                   <!--li><a href='#?r_val=<?php echo $r_val; ?>'>Cash Book</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Journal</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>General Ledger</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Trial Balance</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Voucher Report</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Schedule Report</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Mandate Report</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>
                                   <li><a href='payroll_rep_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('payslip');?>'>Payslip</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Download Salary Schedule</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Individual Annual Salary</a></li-->
                                   <li><a href='payroll_rep_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('bank_list');?>'>Bank List</a></li>
                                   <li><a href='salary_report_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('bank_list_summary');?>'>Bank List Summary</a></li>
                                   <li><a href='salary_report_opt2.php?r_val=<?php echo $r_val; ?>'>Payroll Summary/Analysis</a></li>
                                   <li><a href='roles_allocation.php?r_val=<?php echo $r_val; ?>'>Staff Role Allocation</a></li>
                                   <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Monthly Payroll Analysis</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Monthly Grosspay Summary</a></li>-->
                                   <li><a href='salary_report_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('three_column');?>'>Three Column Analysis</a></li>
                                   <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Salaries and Wages Journal</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Annual Grosspay Summary</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Annual Deduction Summary</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Pay Record Card (PRC)</a></li>-->
                              </ul>

                         <?php }//end of bursar role ?>
                         <?php if($role=='Fixed Asset') { ?>

                              <ul>
                                   <li><a href='fixed_asset.php?r_val=<?php echo $r_val; ?>'>Post Asset</a></li>
                                   <li><a href='upload_fixed_asset.php?r_val=<?php echo $r_val; ?>'>Upload Asset</a></li>
                                   <li><a href='asset_cat_save.php?r_val=<?php echo $r_val; ?>'>Asset Setup</a></li>
                                   <li><a href='fix_report1.php?r_val=<?php echo $r_val; ?>'>Asset Report</a></li>
                                   <li><a href='asset_movemnt.php?r_val=<?php echo $r_val; ?>'>Asset Movement</a></li>
                                   <li><a href='grn_process.php?r_val=<?php echo $r_val; ?>'>GRN Signing</a></li>
                                   <li><a href='siv_process.php?r_val=<?php echo $r_val; ?>'>SIV Signing</a></li>
                                   <li><a href='fixed_print_use.php?r_val=<?php echo $r_val; ?>'>Print Fixed Asset</a></li>
                                   <li><a href='store_report.php?r_val=<?php echo $r_val; ?>'>Store Report</a></li>
                                   <li><a href='stock_card.php?r_val=<?php echo $r_val; ?>'>Stock Card</a></li>
                              </ul>

                         <?php }//end of Fixed Asset role ?>
                         <?php if($role=='Store Officer') { ?>
                              <ul>
                                   <!-- <li><a href='post1.php?r_val=<?php echo $r_val; ?>'>Post Asset</a></li> -->
                                   <li><a href='fixed_asset.php?r_val=<?php echo $r_val; ?>'>Post Asset</a></li>
                                   <li><a href='upload_fixed_asset.php?r_val=<?php echo $r_val; ?>'>Upload Asset</a></li>
                                   <li><a href='asset_cat_save.php?r_val=<?php echo $r_val; ?>'>Asset Setup</a></li>
                                   <li><a href='asset_movemnt.php?r_val=<?php echo $r_val; ?>'>Asset Movement</a></li>
                                   <li><a href='grn_process.php?r_val=<?php echo $r_val; ?>'>GRN Signing</a></li>
                                   <li><a href='siv_process.php?r_val=<?php echo $r_val; ?>'>SIV Signing</a></li>
                                   <li><a href='fixed_print_use.php?r_val=<?php echo $r_val; ?>'>Print Fixed Asset</a></li>
                                   <li><a href='store_report.php?r_val=<?php echo $r_val; ?>'>Store Report</a></li>
                                   <li><a href='stock_card.php?r_val=<?php echo $r_val; ?>'>Stock Card</a></li>
                              </ul>
                         <?php }//end of Store role ?>

                         <?php if($role=='Cash Officer') { ?>

                              <ul>
                                   <li><a href='cashbook_compare_x.php?r_val=<?php echo $r_val; ?>'>Manual Cashbook/Remita Reconciliation</a></li>
                                   <li><a href='recon_upload_reports.php?r_val=<?php echo $r_val; ?>'>View Uploaded Statement</a></li>
                                   <li><a href='voucher_list_for_treasury.php?r_val=<?php echo $r_val; ?>'>Update PV.NO.</a></li>
                                   <li><a href='voucher_list_for_schedule.php?r_val=<?php echo $r_val; ?>'>PV Schedule</a></li>
                                   <li><a href='voucher_list_scheduled.php?r_val=<?php echo $r_val; ?>'>PV Schedule List</a></li>
                                   <li><a href='voucher_list_for_payment.php?r_val=<?php echo $r_val; ?>'>PV Posting</a></li>
                                   <li><a href='voucher_process_new.php?r_val=<?php echo $r_val; ?>'>PV Regularization Process</a></li>
                                   <!--li><a href='voucher_schedule.php?r_val=<?php echo $r_val; ?>'>Batch Payment Processing</a></li-->
                                   <li><a href='voucher_payment_analysis.php?r_val=<?php echo $r_val; ?>'>Payment Analysis</a></li>

                                   <li><a href='voucher_edit.php?r_val=<?php echo $r_val; ?>'>Edit Posted Entries</a></li>
                                   <li><a href='journal_entry2.php?r_val=<?php echo $r_val; ?>'>Raise Journal</a></li>
                                   <li><a href='journal_process.php?r_val=<?php echo $r_val; ?>'>Journal Processing</a></li>
                                   <li><a href='voucher_process_pv.php?r_val=<?php echo $r_val; ?>'>Edit Voucher PV</a></li>

                                   <li><a href='salary_approval.php?r_val=<?php echo $r_val; ?>'>Approval of Salary</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Cashbook Treasury'>Cashbook(Treasury)</a></li>
                                   <li><a href='journal_entry2.php?r_val=<?php echo $r_val; ?>'>Raise Journal</a></li>
                                   <!--li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger'>General Ledger</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Trial Balance'>Trial Balance</a></li-->
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Payment Voucher Report'>Payment Voucher Report</a></li>
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Schedule Report</a></li>
                                   <!--<li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger'>General Ledger</a></li>-->
                                   <li><a href='#?r_val=<?php echo $r_val; ?>'>Payment Mandate Report</a></li>
                                   <li><a href='budget_rep_opt.php?r_val=<?php echo $r_val; ?>'>Budget Report</a></li>
                                   <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=Payment Voucher Report'>Payment Voucher Report</a>
                                        <li><a href='voucher_list_for_edit.php?r_val=<?php echo $r_val; ?>'>Voucher List</a></li>
                                        <!--<li><a href='voucher_y.php?r_val=<?php echo $r_val; ?>'>Post Payment Voucher</a></li>-->
                                   </ul>

                              <?php }//end of cash officer role ?>

                              <?php if($role=='Salary Officer' or $role=='Super Admin' ) { ?>

                                   <ul>
                                        <!--li><a href='journal_entry2.php?r_val=<?php echo $r_val; ?>'>Raise Journal</a></li-->
                                        <li><a href='deduction_definition.php?r_val=<?php echo $r_val; ?>'>Deduction Definition (Group)</a></li>
                                        <li><a href='deduction_definition_indv.php?r_val=<?php echo $r_val; ?>'>Deduction Definition (Individual)</a></li>
                                        <li><a href='allowance_definition.php?r_val=<?php echo $r_val; ?>'>Allowance Definition (Group)</a></li>
                                        <li><a href='otherpayment_source.php?r_val=<?php echo $r_val; ?>'>Allowance Definition (Individual)</a></li>
                                        <li><a href='deduction_exception.php?r_val=<?php echo $r_val; ?>'>Deduction Exception</a></li>
                                        <li><a href='edit_salary_status.php?r_val=<?php echo $r_val; ?>'>Edit Salary Status</a></li>
                                        <li><a href='loan_bank_change.php?r_val=<?php echo $r_val; ?>&pagetitle=<?php echo base64_encode("UPDATE BANK LOAN"); ?>&r=1'>Update Bank Loan</a></li>
                                        <li><a href='loan_bank_change.php?r_val=<?php echo $r_val; ?>&pagetitle=<?php echo base64_encode("CHANGE BANK DETAILS"); ?>&r=2'>Change Bank Details</a></li>
                                        <li><a href='prorata.php?r_val=<?php echo $r_val; ?>'>Salary Proration</a></li>
                                        <li><a href='salary_computation.php?r_val=<?php echo $r_val; ?>'>Salary Computation</a></li>
                                        <li><a href='prorate_arrears.php?r_val=<?php echo $r_val; ?>'>Prorata/Salary Arrears</a></li>
                                        <li><a href='salary_scale_setup.php?r_val=<?php echo $r_val; ?>'>Salary Scale Name Setup</a></li>
                                        <li><a href='salary_code_setup.php?r_val=<?php echo $r_val; ?>'>Salaries Account Code Setup</a></li>
                                        <li><a href='tax_setup.php?r_val=<?php echo $r_val; ?>'>Tax Definition</a></li>
                                        <li><a href='salary_scale_setup.php?r_val=<?php echo $r_val; ?>&op_id=consolidated'>Consolidated Pay Setup</a></li>
                                        <!--<li><a href='salary_scale.php?r_val=<?php echo $r_val; ?>'>Salary Scale/Structure</a></li>-->
                                        <li><a href='salary_scale_rep_opt.php?r_val=<?php echo $r_val; ?>'>Salary Table</a></li>
                                        <li><a href='payroll_rep_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('payslip');?>'>Payslip</a></li>
                                        <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Download Salary Schedule</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Individual Annual Salary</a></li>-->
                                        <li><a href='payroll_rep_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('bank_list');?>'>Bank List</a></li>
                                        <li><a href='salary_report_opt.php?r_val=<?php echo $r_val; ?>&mode=<?php echo base64_encode('bank_list_summary');?>'>Bank List Summary</a></li>
                                        <li><a href='salary_report_opt2.php?r_val=<?php echo $r_val; ?>'>Payroll Summary/Analysis</a></li>
                                        <li><a href='salary_report_extra.php?r_val=<?php echo $r_val; ?>'>Payroll Report</a></li>

                                        <li><a href='account_report_opt.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger Salary'>General Ledger</a></li>
                                        <li><a href='revenue_rep_opt_audit.php?r_val=<?php echo $r_val; ?>&query'>Voucher Query Report</a></li>
                                        <li><a href='revenue_rep_opt_audit.php?r_val=<?php echo $r_val; ?>&rtype=General Ledger Salary'>Purchase Advance Register</a></li>

                                        <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Deduction Analysis</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Deduction Summary</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Monthly Payroll Analysis</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Monthly Grosspay Summary</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Three Column Analysis</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Salaries and Wages Journal</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Annual Grosspay Summary</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Annual Deduction Summary</a></li>
                                        <li><a href='#?r_val=<?php echo $r_val; ?>'>Pay Record Card (PRC)</a></li>-->

                                   </ul>

                              <?php }//end of salary role ?>


                         </div>
                         <?php   //auto-create accordion
                    } //end of while for fetch roles

               } //end of if staff
               ?>

               <!-- End of Auto-read role sidebar starts here for staff-->


               <!-- student accordion -->
               <?php if($login_status=='student') { $role="Student"; $r_val=@base64_encode($role); ?>

               <div title="REGISTRATION AND PAYMENT" data-options="iconCls:'icon-edit_add'" style="padding:10px;"> <!-- lstudent accordion -->
                    <ul>
                         <?php
                         $cur_session=@$_SESSION['cur_session'];
                         $db->select("studenttb","regno,mode_of_entry,session_of_entry,fact_id,dept_id",null,"regno='$login_id'","");
                         $j= json_decode($db->getResult()); //getResult returns two params i.e row and data in which row is a value and data is another JSON data. This applies to all functions in the class exept getPix,getPix2 and exist. exist fun in the class returns only true/false as to whether the query passed return row or not.
                         $j_row=$j->row;
                         $j_data=@json_decode($j->data);

                         $fact_id=@$j_data->fact_id;
                         $dept_id=@$j_data->dept_id;
                         $session_of_entry=@$j_data->session_of_entry;
                         $mode_of_entry=strtoupper(@$j_data->mode_of_entry);
                         if($session_of_entry!=@trim($cur_session))
                         $stud_status="Returning";
                         else
                         $stud_status="Fresher";
                         ?>
                         <!-- <li><a href='registration_instruction.php?r_val=<?php echo $r_val; ?>'>Registration Procedures</a></li>
                         <li><a href='hostel_allocation.php?r_val=<?php //echo $r_val; ?>'>Hostel Application</a></li>-->
                         <li><a href="current_charges.php?r_val=<?php echo $r_val; ?>">My School Fees</a></li>
                         <li><a href='course_registration.php?r_val=<?php echo $r_val; ?>'>Course Registration</a></li>
                         <!--<li><a href='#?r_val=<?php echo $r_val; ?>'>Download Add/Drop Form</a></li>-->
                         <li><a href='print_course_form.php?id=crg&r_val=<?php echo $r_val; ?>'>Print Completed Course Form</a></li>
                         <li><a href='print_course_form.php?id=rec&r_val=<?php echo $r_val; ?>'>Print Payment Receipts</a></li>
                         <li><a href='print_course_form.php?id=result&r_val=<?php echo $r_val; ?>'>Print Results</a></li>
                         <li><a href='payment_revalidation.php?r_val=<?php echo $r_val; ?>'>ReQuery Payment</a></li>
                         <li><a href='admission_letter.php?r_val=<?php echo $r_val; ?>&id=clr'>Print Admission Letter</a></li>
                         <?php if($stud_status=="Fresher") {?>
                              <li><a href='old_matriculant.php?r_val=<?php echo $r_val; ?>&id=old'>Old Matriculant Form</a></li>

                         <?php }//end of old matriculant link?>
                         <li><a href='other_payment.php?r_val=<?php echo $r_val; ?>'>Other Payments</a></li>
                         <?php if($dept_id=='41' || $dept_id=='42' || $dept_id=='43' || $dept_id=='76' || $dept_id=='85' || $dept_id=='79' || $fact_id=='30') { ?><li><a href='changeofcourseform.php?r_val=<?php echo $r_val; ?>'>Change of Course Form</a></li> <?php } ?>
                         <li><a href='add_drop.php?r_val=<?php echo $r_val; ?>'>Add and Drop Form</a></li>

                    </ul>
               </div>

               <!--<div title="PROJECT MANAGEMENT" data-options="iconCls:'icon-edit_add'" style="padding:10px;"> <!-- lstudent accordion -/->
               <ul>
               <li><a href='main.php?id=result&r_val=<?php echo $r_val; ?>'>Manage My Project</a></li>
          </ul>
     </div>-->

<?php }//end of student role ?>

<!-- end of student accordion -->


</div> <!-- end of main accordion -->




<script>
$(function() {
     $( "#accordion" ).accordion({
          collapsible: true,
          heightStyle: "content"
     });
});
//$( "#accordion" ).accordion();
</script>

<div id="w" class="easyui-window" title="" data-options="modal:true,closed:true,iconCls:'icon-save'" style="width:600px;height:auto;padding:10px;top:10px;">
</div>

<!--</div> <!-- end of sidebar -->
