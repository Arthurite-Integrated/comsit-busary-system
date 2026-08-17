<?php
     
     require_once("../myclass_m.php");
     $cls=new myclass_m();
     $cls->database_con();
     @session_start();
     $requestID=$_REQUEST["requestID"];

     if($requestID=="uploadDocument")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $doc=$cls->data($_REQUEST['doc_type'], 'l');
          $search=array('.', ' ', '../');
          $f=explode('.', $_FILES['imageToUploads']['name'], 2);
          $ext = $f[1];  $fn = $f[0];
          $fileExt = array('jpg', 'jpeg', 'png', 'pdf');
          if($_FILES['imageToUploads']['tmp_name'] != ''){
               $dir='';
               if($doc=="passport" || $doc=="signature") {
                    if($ext != 'jpg') {
                         $_SESSION['uplaodMsg']="<span class='badge badge-danger'>File extension not acceptable for the selected document type.</span>";
                         goto stopUpload;
                    }
                    $dir = "../pictures";
                    $destination_path = $dir."/".$fileno."_".$doc.".".$ext;
               }
               else {
                    if(!in_array($ext, $fileExt)) {
                         $_SESSION['uplaodMsg']="<span class='badge badge-danger'>File extension not acceptable for the selected document type.</span>";
                         goto stopUpload;
                    }
                    $dir = "../upload_files/".str_replace($search, '', $doc);
                    if(!is_dir($dir)) mkdir($dir, 0777);
                    $rnd = rand(123, 99999);
                    $file=str_replace($search, '', $fileno."_".$doc."_".$fn.$rnd);
                    $destination_path = $dir."/".$file.".".$ext;
               }
               
               if(move_uploaded_file($_FILES['imageToUploads']['tmp_name'], $destination_path)) {
                    $_SESSION['uplaodMsg']="<span class='badge badge-success'>File upload successful.</span>";
               }
               //sleep(1);
          }
          stopUpload:
          @header("location:".$_SERVER['HTTP_REFERER']); ?>
          <script>window.location='<?=$_SERVER['HTTP_REFERER'];?>'</script>
          <?php
          exit;
     }
     if($requestID=="addReferee")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $ref_name=$cls->data($_REQUEST['ref_name']);
          $ref_occupation=$cls->data($_REQUEST['ref_occupation']);
          $ref_address=$cls->data($_REQUEST['ref_address']);
          $ref_year=$cls->data($_REQUEST['ref_year']);
          $ref_email=$cls->data($_REQUEST['ref_email']);
          $ref_phone_no=$cls->data($_REQUEST['ref_phone_no']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_refereetb SET ref_name='{$ref_name}', ref_occupation='{$ref_occupation}', ref_address='{$ref_address}', ref_know_period='{$ref_year}', ref_email='{$ref_email}', ref_phone_no='{$ref_phone_no}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_refereetb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', ref_name AS 'NAME', ref_occupation AS 'OCCUPATION', ref_address AS 'ADDRESS', ref_know_period AS 'YEARS', ref_email AS 'EMAIL', ref_phone_no AS 'PHONE NO.' FROM hr_staff_refereetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addReferee", "Delete");
          exit;
     }

     if($requestID=="addHonour")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $honour_type=$cls->data($_REQUEST['honour_type']);
          $honour_date=$cls->data($_REQUEST['honour_date']);
          $honour_desc=$cls->data($_REQUEST['honour_desc']);
          $honour_prize=$cls->data($_REQUEST['honour_prize']);
          $tra_start_date=$cls->data($_REQUEST['tra_start_date']);
          $tra_end_date=$cls->data($_REQUEST['tra_end_date']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_recognitiontb SET award_type='{$honour_type}', award_date='{$honour_date}', award_description='{$honour_desc}', prize='{$honour_prize}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_recognitiontb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', award_type AS 'PROGRAMME TYPE', award_date AS 'DATE', award_description AS 'TITLE/DESCRIPTION', prize AS 'PRIZE' FROM hr_staff_recognitiontb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addHonour", "Delete");
          exit;
     }

     if($requestID=="addHonour")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $honour_type=$cls->data($_REQUEST['honour_type']);
          $honour_date=$cls->data($_REQUEST['honour_date']);
          $honour_desc=$cls->data($_REQUEST['honour_desc']);
          $honour_prize=$cls->data($_REQUEST['honour_prize']);
          $tra_start_date=$cls->data($_REQUEST['tra_start_date']);
          $tra_end_date=$cls->data($_REQUEST['tra_end_date']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_recognitiontb SET award_type='{$honour_type}', award_date='{$honour_date}', award_description='{$honour_desc}', prize='{$honour_prize}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_recognitiontb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', award_type AS 'PROGRAMME TYPE', award_date AS 'DATE', award_description AS 'TITLE/DESCRIPTION', prize AS 'PRIZE' FROM hr_staff_recognitiontb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addHonour", "Delete");
          exit;
     }

     if($requestID=="addConference")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $tra_type=$cls->data($_REQUEST['tra_type']);
          $tra_title=$cls->data($_REQUEST['tra_title']);
          $tra_location=$cls->data($_REQUEST['tra_location']);
          $tra_sponsor=$cls->data($_REQUEST['tra_sponsor']);
          $tra_start_date=$cls->data($_REQUEST['tra_start_date']);
          $tra_end_date=$cls->data($_REQUEST['tra_end_date']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_training_apptb SET training_type='{$tra_type}', training_title='{$tra_title}', location='{$tra_location}', sponsor='{$tra_sponsor}', start_date='{$tra_start_date}', end_date='{$tra_end_date}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_training_apptb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', training_type AS 'PROGRAMME TYPE', training_title AS 'THEME/TITLE', location AS 'LOCATION/VENUE', sponsor AS 'SPONSOR', start_date AS 'FROM', end_date AS 'TO' FROM hr_staff_training_apptb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addConference", "Delete");
          exit;
     }

     if($requestID=="addMembership")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $prof_mem_name=$cls->data($_REQUEST['prof_mem_name']);
          $prof_mem_category=$cls->data($_REQUEST['prof_mem_category']);
          $prof_mem_regno=$cls->data($_REQUEST['prof_mem_regno']);
          $prof_mem_certno=$cls->data($_REQUEST['prof_mem_certno']);
          $prof_mem_year=$cls->data($_REQUEST['prof_mem_year']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_prof_membershiptb SET name='{$prof_mem_name}', category='{$prof_mem_category}', reg_num='{$prof_mem_regno}', cert_num='{$prof_mem_certno}', year_honoured='{$prof_mem_year}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_prof_membershiptb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', name AS 'PROFESSIONAL BODY/INSTITUTION', category AS 'CATEGORY', reg_num AS 'MEMBERSHIP ID', cert_num AS 'CERTIFICATE NUMBER', year_honoured AS 'YEAR' FROM hr_staff_prof_membershiptb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addMembership", "Delete");
          exit;
     }

     if($requestID=="addResearch")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $res_topic=$cls->data($_REQUEST['res_topic']);
          $res_status=$cls->data($_REQUEST['res_status']);
          $res_funding=$cls->data($_REQUEST['res_funding']);
          $res_amount=$cls->data($_REQUEST['res_amount']);
          $res_value=$cls->data($_REQUEST['res_value']);
          $res_start_date=$cls->data($_REQUEST['res_start_date']);
          $res_end_date=$cls->data($_REQUEST['res_end_date']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_researchtb SET topic='{$res_topic}', status='{$res_status}', funding_source='{$res_funding}', project_value='{$res_value}', amount_granted='{$res_amount}', start_date='{$res_start_date}', end_date='{$res_end_date}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_researchtb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', topic AS 'TOPIC', status AS 'STATUS', funding_source AS 'FUNDING SOURCE', amount_granted AS 'AMOUNT GRANTED', project_value AS 'PROJECT VALUE', start_date AS 'START DATE', end_date AS 'END DATE' FROM hr_staff_researchtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addResearch", "Delete");
          exit;
     }

     if($requestID=="addService")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $serv_type=$cls->data($_REQUEST['serv_type']);
          $serv_from=$cls->data($_REQUEST['serv_from']);
          $serv_to=$cls->data($_REQUEST['serv_to']);
          $serv_place=$cls->data($_REQUEST['serv_place']);
          $serv_detail=$cls->data($_REQUEST['serv_detail']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_servicetb SET service_type='{$serv_type}', from_year='{$serv_from}', to_year='{$serv_to}', service_place='{$serv_place}', service_details='{$serv_detail}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_servicetb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', service_type AS 'SERVICE TYPE', from_year AS 'FROM', to_year AS 'TO', service_place AS 'PLACE OF SERVICE', service_details AS 'DETAILS/DESCRIPTION OF SERVICE' FROM hr_staff_servicetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addService", "Delete");
          exit;
     }

     if($requestID=="addPublication")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $pub_title=$cls->data($_REQUEST['pub_title']);
          $pub_publisher=$cls->data($_REQUEST['pub_publisher']);
          $pub_author=$cls->data($_REQUEST['pub_author']);
          $pub_type=$cls->data($_REQUEST['pub_type']);
          $pub_journal=$cls->data($_REQUEST['pub_journal']);
          $pub_year=$cls->data($_REQUEST['pub_year']);
          $pub_status=$cls->data($_REQUEST['pub_status']);
          $pub_category=$cls->data($_REQUEST['pub_category']);
          $pub_page_no=$cls->data($_REQUEST['pub_page_no']);
          $pub_url=$cls->data($_REQUEST['pub_url']);
          $pub_volume=$cls->data($_REQUEST['pub_volume']);
          $pub_issue=$cls->data($_REQUEST['pub_issue']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_publicationtb SET title='{$pub_title}', publisher='{$pub_publisher}', author='{$pub_author}', type='{$pub_type}', journal='{$pub_journal}', year_published='{$pub_year}', print_status='{$pub_status}', category='{$pub_category}', page_no='{$pub_page_no}', url='{$pub_url}', volume='{$pub_volume}', issue='{$pub_issue}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Trash")
               $queryString="UPDATE hr_staff_publicationtb SET status = 'Not Active' WHERE id={$recID}";
          if($operation=="Restore")
               $queryString="UPDATE hr_staff_publicationtb SET status = 'Active' WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', title AS 'TITLE', publisher AS 'PUBLISHER', author AS 'AUTHOR', type AS 'TYPE', journal AS 'NAME OF JOURNAL', year_published AS 'YEAR', print_status AS 'PRINT STATUS', category AS 'CATEGORY', page_no AS 'PAGE/RANGE', url AS 'URL', volume AS 'VOLUME', issue AS 'ISSUE', status AS 'STATUS' FROM hr_staff_publicationtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addPublication", "Trash,Restore");
          exit;
     }

     if($requestID=="addQualification")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $prof_qual_inst=$cls->data($_REQUEST['prof_qual_inst']);
          $prof_qual_name=$cls->data($_REQUEST['prof_qual_name']);
          $prof_qual_year=$cls->data($_REQUEST['prof_qual_year']);
          $prof_qual_type=$cls->data($_REQUEST['prof_qual_type']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_prof_qualificationtb SET institution='{$prof_qual_inst}', qualification='{$prof_qual_name}', year_onbtained='{$prof_qual_year}', qual_type='{$prof_qual_type}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_prof_qualificationtb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', institution AS 'AWARDING BODY/INSTITUTION', qualification AS 'QUALIFICATION', year_onbtained AS 'YEAR OBTAINED', qual_type AS 'TYPE' FROM hr_staff_prof_qualificationtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addQualification", "Delete");
          exit;
     }

     if($requestID=="addEducation")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $edu_name=$cls->data($_REQUEST['edu_name']);
          $edu_type=$cls->data($_REQUEST['edu_type']);
          $edu_qual=$cls->data($_REQUEST['edu_qual']);
          $edu_grade=$cls->data($_REQUEST['edu_grade']);
          $edu_month_from=$cls->data($_REQUEST['edu_month_from']);
          $edu_year_from=$cls->data($_REQUEST['edu_year_from']);
          $edu_month_to=$cls->data($_REQUEST['edu_month_to']);
          $edu_year_to=$cls->data($_REQUEST['edu_year_to']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_academic_edutb SET school_name='{$edu_name}', school_type='{$edu_type}', qualification='{$edu_qual}', degree_class='{$edu_grade}', from_month='{$edu_month_from}', from_year='{$edu_year_from}', to_month='{$edu_month_to}', to_year='{$edu_year_to}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_academic_edutb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', school_name AS 'SCHOOL/INSTITUTION', school_type AS 'TYPE', qualification AS 'QUALIFICATION', degree_class AS 'GRADE/CLASS OF DEGREE', concat(from_month,', ',from_year) AS 'FROM', concat(to_month,', ',to_year) AS 'TO' FROM hr_staff_academic_edutb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addEducation", "Delete");
          exit;
     }

     if($requestID=="addEmployment")
     {
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $emp_name=$cls->data($_REQUEST['emp_name']);
          $emp_location=$cls->data($_REQUEST['emp_location']);
          $emp_rank=$cls->data($_REQUEST['emp_rank']);
          $emp_salary=$cls->data($_REQUEST['emp_salary']);
          $emp_year_from=$cls->data($_REQUEST['emp_year_from']);
          $emp_year_to=$cls->data($_REQUEST['emp_year_to']);
          $emp_type=$cls->data($_REQUEST['emp_type']);
          $emp_status=$cls->data($_REQUEST['emp_status']);
          $emp_duty=$cls->data($_REQUEST['emp_duty']);
          $emp_leaving=$cls->data($_REQUEST['emp_leaving']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_employmenttb SET employer_name='{$emp_name}', location='{$emp_location}', rank='{$emp_rank}', salary='{$emp_salary}', from_year='{$emp_year_from}', to_year='{$emp_year_to}', employment_type='{$emp_type}', status='{$emp_status}', duty='{$emp_duty}', leaving_reason='{$emp_leaving}', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Delete")
               $queryString="DELETE FROM hr_staff_employmenttb WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', employer_name AS 'EMPLOYER', location AS 'ADDRESS', rank AS 'RANK', salary AS 'SALARY', from_year AS 'FROM', to_year AS 'TO', employment_type AS 'EMP. TYPE', duty AS 'DUTY', leaving_reason AS 'REASON FOR LEAVING', status AS 'STATUS' FROM hr_staff_employmenttb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "addEmployment", "Delete");
          exit;
     }

     if($requestID=="saveDependent"){
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $child_name=$cls->data($_REQUEST['child_name']);
          $child_dob=$cls->data($_REQUEST['child_dob']);
          $child_sex=$cls->data($_REQUEST['child_sex']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_childtb SET name='{$child_name}', date_of_birth='{$child_dob}', sex='{$child_sex}', status = 'Active', fileno='{$fileno}', entry_date=now(), entry_by='{$_SESSION['login_id']}'";
          if($operation=="Trash")
               $queryString="UPDATE hr_staff_childtb SET status = 'Not Active' WHERE id={$recID}";
          if($operation=="Restore")
               $queryString="UPDATE hr_staff_childtb SET status = 'Active' WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', name AS 'NAME', date_of_birth AS 'DATE OF BIRTH', sex AS 'GENDER', status AS 'STATUS' FROM hr_staff_childtb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "saveDependent", "Trash,Restore");
          exit;
     }

     if($requestID=="saveNextofkin"){
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $next_name=$cls->data($_REQUEST['next_name']);
          $next_relationship=$cls->data($_REQUEST['next_relationship']);
          $next_email=$cls->data($_REQUEST['next_email']);
          $next_phone_no=$cls->data($_REQUEST['next_phone_no']);
          $next_address=$cls->data($_REQUEST['next_address']);
          $queryString="UPDATE stafftb SET next_name='{$next_name}', next_relationship='{$next_relationship}', next_email='{$next_email}', next_phone_no='{$next_phone_no}', next_address='{$next_address}' WHERE fileno='{$fileno}'";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', next_name AS 'NAME', next_relationship AS 'RELATIONSHIP', next_email AS 'EMAIL', next_phone_no AS 'PHONE NO', next_address AS 'ADDRESS' FROM stafftb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", false, "saveNextofkin");
          exit;
     }

     if($requestID=="saveSpouse"){
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $spouse_occupation=$cls->data($_REQUEST['spouse_occupation']);
          $spouse_name=$cls->data($_REQUEST['spouse_name']);
          $spouse_address=$cls->data($_REQUEST['spouse_address']);
          $operation=$_REQUEST['operation'];
          $recID=$cls->data($_REQUEST['recID']);
          if($operation=="addNew")
               $queryString="INSERT INTO hr_staff_spousetb SET spouse_name='{$spouse_name}', spouse_occupation='{$spouse_occupation}', spouse_address='{$spouse_address}', status = 'Active', fileno='{$fileno}', entrydate=now(), entryby='{$_SESSION['login_id']}'";
          if($operation=="Trash")
               $queryString="UPDATE hr_staff_spousetb SET status = 'Not Active' WHERE id={$recID}";
          if($operation=="Restore")
               $queryString="UPDATE hr_staff_spousetb SET status = 'Active' WHERE id={$recID}";
          if(mysqli_query($con, $queryString)){
                    echo "<p style='color:green;'>Record updated successfully.</p>";
          }else{
                    echo "<p style='color:red;'>Record updated failed.</p>";
          }
          $cls->generateTable("SELECT id AS 'UID', spouse_name AS 'NAME', spouse_occupation AS 'OCCUPATION', spouse_address AS 'ADDRESS', status AS 'STATUS' FROM hr_staff_spousetb WHERE fileno='{$_SESSION['eData']['fileno']}' ORDER BY id", "Yes", true, "saveSpouse", "Trash,Restore");
          exit;
     }

     if($requestID=="saveEmployee"){
               
          $fileno = $cls->data($_REQUEST['efileno'], 'u');
          $title=$_REQUEST['title'];
          $surname=$cls->data($_REQUEST['surname']);
          $first_name=$cls->data($_REQUEST['first_name']);
          $other_name=$cls->data($_REQUEST['other_name']);
          $state=$_REQUEST['state'];
          $lga=$_REQUEST['lga'];
          $religion=trim($_REQUEST['religion']); 
          $category=$_REQUEST['category'];
          $maiden_name=$cls->data($_REQUEST['maiden_name']);
          $date_of_birth=date('Y-m-d', strtotime($_REQUEST['date_birth']));
          $place_of_birth=$cls->data($_REQUEST['place_of_birth']);
          //$nationality=$cls->data($_REQUEST['nationality'];
          $country=$cls->data($_REQUEST['country']);
          $senatorial_district=$cls->data($_REQUEST['senatorial_district']);
          $permanent_address=$cls->data($_REQUEST['permanent_address']);
          $contact_address=$cls->data($_REQUEST['contact_address']);
          $languages_spoken=$cls->data($_REQUEST['languages_spoken']);
          $hobbies=$cls->data($_REQUEST['hobbies']);
          $disability=$cls->data($_REQUEST['disability']);
          $disability_reason=$cls->data($_REQUEST['disability_reason']);
          $dept=trim($_REQUEST['dept_code']);
          $unit=trim($_REQUEST['unit_code']);
          $rank=trim($_REQUEST['rank']);
          $sex=trim($_REQUEST['sex']); 
          $email=trim($_REQUEST['email']);
          $phone_no=trim($_REQUEST['phone_no']); 
          $level=trim($_REQUEST['level']);
          $step=trim($_REQUEST['step']);
          $bankname=trim($_REQUEST['bank_name']);
          $acct_no=trim($_REQUEST['acct_no']); 
          $marital_status=trim($_REQUEST['marital_status']);
          //$status=trim($_REQUEST['status']; x

          $res_sc=mysqli_query($con, "SELECT scale_name from scale_nametb where category='{$category}' and status = 'Active' limit 1");
          $res_scs = mysqli_fetch_array($res_sc);
          $salary_scale = $res_scs["scale_name"];

          $res_s=mysqli_query($con, "SELECT * FROM stafftb where fileno='{$fileno}'");
          $password=base64_encode('KP#pass1'); //default password

          if(mysqli_num_rows($res_s) > 0)
               {
                         //update section
                         $queryString="UPDATE stafftb SET fileno='{$fileno}', title='{$title}', surname='{$surname}', first_name='{$first_name}', other_name='{$other_name}', state_id='{$state}', lga_id='{$lga}', category='{$category}', dept_code='{$dept}', unit_code='{$unit}', sex='{$sex}', email='{$email}', phone_no='{$phone_no}', religion='{$religion}', level='{$level}', step='{$step}', rank='{$rank}', acct_no='{$acct_no}', bank_name='{$bankname}', date_of_birth='{$date_of_birth}', entry_date=CURDATE(),entry_time=CURTIME(), entry_by='{$_SESSION['user']['fileno']}', salary_scale='{$salary_scale}', marital_status='{$marital_status}', disability_reason='{$disability_reason}', disability='{$disability}', hobbies='{$hobbies}', languages_spoken='{$languages_spoken}', contact_address='{$contact_address}', permanent_address='{$permanent_address}', senatorial_district='{$senatorial_district}', country='{$country}', place_of_birth='{$place_of_birth}', maiden_name='{$maiden_name}' WHERE fileno='$fileno'";
               }
               else
               {
                         //save section
                         $queryString="INSERT INTO stafftb SET fileno='$fileno', fileno='{$fileno}', title='{$title}', surname='{$surname}', first_name='{$first_name}', other_name='{$other_name}', state_id='{$state}', lga_id='{$lga}', category='{$category}', dept_code='{$dept}', unit_code='{$unit}', sex='{$sex}', email='{$email}', phone_no='{$phone_no}', religion='{$religion}', level='{$level}', step='{$step}', rank='{$rank}', acct_no='{$acct_no}', bank_name='{$bankname}', date_of_birth='{$date_of_birth}', entry_date=CURDATE(),entry_time=CURTIME(), entry_by='{$_SESSION['user']['fileno']}', salary_scale='{$salary_scale}', marital_status='{$marital_status}', disability_reason='{$disability_reason}', disability='{$disability}', hobbies='{$hobbies}', languages_spoken='{$languages_spoken}', contact_address='{$contact_address}', permanent_address='{$permanent_address}', senatorial_district='{$senatorial_district}', country='{$country}', place_of_birth='{$place_of_birth}', maiden_name='{$maiden_name}', password='$password', salary_scale='$salary_scale'";
               }
          //, status='{$status}', nationality='{$nationality}'

          if(mysqli_query($con, $queryString)){
               echo "<script>alert('Staff record updated successfully')</script>";
               } else {
                         echo "<script>alert('Staff record updated failed!');</script>";
                         echo mysqli_error($con);
          }
     exit;
     }

?>
