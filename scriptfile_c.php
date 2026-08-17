<?php
@session_start();
@ini_set('max_execution_time', 60000000000);
@ini_set("memory_limit", "51200M");
@require_once('connect.php');
@require_once('function_c.php');

@require_once('class/mysqli_class.php');
$db = new Database();
$db->connect();
@require_once "myclass_m.php"; $bursary=new myclass_m();
$id=@$_REQUEST['contentvar'];
$contentvar=$_REQUEST['contentvar'];
//echo  'here   is     '.$contentvar; exit;
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





if($id=='folio_section')
{
     $mydata=@$_REQUEST['mydata'];
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     //$stat = $_REQUEST['status'];
     $j=@json_decode(stripslashes($mydata)); //encode the json data
     //$dept_code=explode("***",$j->dept_code);
     //echo 'Sno    '.$mydata. $action; exit;
     $stat=$j->status;
     if($r_id != "")
     {
          $d=@mysqli_query($con, "select * from foliotb where id = '$r_id'");
          $ds= mysqli_fetch_array($d);
          $folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);
          $title=@mysqli_real_escape_string($con, $ds['title']);
          $category=@mysqli_real_escape_string($con, $ds['category']);
          $r_ids=@$ds['id'];
     }

     if($action=='save')
     {
          $f = explode("-", $j->folio_code);
          $fundcenter = $f[0]; $deptcode = $f[1]; $itemcode = $f[2];
          $res_s=@mysqli_query($con, "SELECT * from foliotb where folio_code = '{$j->folio_code}' ");
          $rs_count=@mysqli_num_rows($res_s);
          if($rs_count == 1)
          {  mysqli_query($con, "UPDATE foliotb SET title='{$j->title}', category='{$j->category}', fundcenter = '{$fundcenter}', deptcode = '{$deptcode}', itemcode = '{$itemcode}', exp='{$j->cgroup}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}' where folio_code='{$j->folio_code}'");
               echo "<script>
               alert('Record Updated successfully');</script>
               "; exit;
          }
          if( mysqli_query($con, "INSERT INTO foliotb SET folio_code='{$j->folio_code}', title='{$j->title}', category='{$j->category}', fundcenter = '{$fundcenter}', deptcode = '{$deptcode}', itemcode = '{$itemcode}', exp='{$j->cgroup}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'"))
          {
               logs($login_id,"Save Record","Insert new Folio: Folio Code :=>$j->folio_code,Title :=>$j->title ");
               echo "<script>
               alert('Record save successfully');</script>
               ";
          }
          else
          {
               //$err=@mysqli_real_escape_string($con,  mysqli_error($con));
               //echo $err=@mysqli_error($con);
               echo "<script>alert('Unable to save record');</script>";

          }

     }// end of save
     if($action=='save_category')
     {

          if( mysqli_query($con, "insert into folio_categorytb set folio_category=upper('$j->folio_cat'),entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
          {
               logs($login_id,"Save Record","Insert new Folio category: Folio Category :=>$j->folio_cat");
               echo "<script>
               alert('Record save successfully');</script>
               ";
          }
          else
          {
               //$err=@mysqli_real_escape_string($con,  mysqli_error($con));
               //$err=@mysqli_error($con);
               //echo "$err";
               echo "<script>alert('Unable to save record');</script>";

          }

     }// end of save
     if($action=='edit')
     {
          
          $ss="SELECT f.*, c.folio_category FROM foliotb f INNER JOIN folio_categorytb c ON f.category=c.id WHERE f.id='{$r_id}'";
          $qr=mysqli_query($con, $ss);
          $rec=mysqli_fetch_array($qr, 3);
          //"INSERT INTO foliotb SET folio_code='{$j->folio_code}', title='{$j->title}', category='{$j->category}', fundcenter = '{$fundcenter}', deptcode = '{$deptcode}', itemcode = '{$itemcode}', exp='{$j->cgroup}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}'"
          //$json_val=@array(folio_code=>$rec['folio_code'], title=>$rec['title'], category=>$rec['category'], cgroup=>$rec['exp'], id=>$rec['id'], status=>$rec['status']);
          //$json_val=@json_encode($json_val);
          //echo $json_val;
          //exit;
          ?>
          <span id="edit_span" style="background-color:red;">
          <hr>
          <table cellspacing="5">
				<tr>
					<th align="left">Folio Code:</th><td nowrap="nowrap"><input name="folioid" type="hidden" id="folioid" value="<?php echo $rec['id'];?>" /><input name="ifolio_code" type="text" id="ifolio_code" value="<?php echo $rec['folio_code'];?>" size="40"/></td>
				</tr>
				<tr>
					<th align="left">Title:</th><td nowrap="nowrap"><input name="ititle" type="text" id="ititle" tabindex="3" value="<?php echo $rec['title'];?>" size="40"/></td>
				</tr>
				<tr>
				<th align="left" nowrap="nowrap">Folio Category:</th>
				<td nowrap="nowrap"><select name="icategory" id="icategory" tabindex="4">
					<option selected="selected" value="<?=$rec['category'];?>"><?=$rec['folio_category'];?></option>
					<?php
					$r=@mysqli_query($con, "SELECT DISTINCT *  from folio_categorytb order by folio_category");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['folio_category'];$pcode=@$rcourse['id'];
							echo "<option value='$pcode'>$scourse</option>";
							
						}
					
					?>
					</select>
				</td>
				</tr>
				<tr>
				<th align="left" nowrap="nowrap">Code Grouping:</th>
				<td nowrap="nowrap">
					<select name="icgroup" id="icgroup" class="form-control select2">
					<option selected="selected" value="<?=$rec['exp'];?>"><?=$rec['exp'];?></option>
					<option value="Income">Income</option>
					<option value="Assets">Assets</option>
					<option value="Liabilities">Liabilities</option>
					<option value="Expenses">Expenses</option>
					</select>
				</td>
				</tr>
				<tr>
					<th colspan="2">
				<input type="button" class="btn" name="sbtne" id="sbtne" value=" UPDATE " onclick="swapcontent('folio_section', 'update_folio', '<?php echo $rec['id'];?>', $('#ifolio_code').val(), $('#ititle').val(), $('#icategory').val(), $('#icgroup').val());" />
				<input type="button" class="btn" name="chbtne" id="chsbtne" value=" CANCEL " onclick="$('#edit_span').hide();" />
				<!--,$('#ccode').val()-->
				
				</th></tr>
			</table>
                         
               <hr>
          </span>
          <?php
     }//end of edit option
     if($action=='delete_folio')
     {
          //
          if( mysqli_query($con, "delete from foliotb where id='$r_id'"))
          {
               logs($login_id,"Delete Record","Delete folio record: Folio Code :=>$folio_code,Title :=>$title ");
               echo "<script>alert('Record deleted successfully');</script>";
          }
          else
          {
               echo "<script>alert('Unable to delete record');</script>";
          }
     } // end of delete option
     if($action=='update_folio')
     {
          $f = explode("-", $_REQUEST['ifolio_code']);
          $fundcenter = $f[0]; 
          $deptcode = $f[1]; 
          $itemcode = $f[2];
          $sss = "UPDATE foliotb SET folio_code='{$_REQUEST['ifolio_code']}', title='{$_REQUEST['ititle']}', category='{$_REQUEST['icategory']}', fundcenter = '{$fundcenter}', deptcode = '{$deptcode}', itemcode = '{$itemcode}', exp='{$_REQUEST['icgroup']}', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='{$login_id}' WHERE id='{$r_id}'";
          //exit;
          if( mysqli_query($con, $sss))
          {
               logs($login_id,"Update Record","Update folio record: Folio Code :=>$folio_code, Status:=>$stat");
               echo "<script>alert('Record update successfully');</script>";
          }
          else
          {
               echo "<script>alert('Unable to update record');</script>";
          }
     } // end of update option
     if($action=='delete_category')
     {
          //echo "$r_id";
          if( mysqli_query($con, "delete from folio_categorytb where id='$r_id'"))
          {
               logs($login_id,"Delete Record","Delete folio category: Folio Category :=>$folio_category");
               echo "<script>
               alert('Record deleted successfully');</script>
               ";
          }
          else
          {
               echo "<script>alert('Unable to delete record');</script>";

          }


     } // end of delete option
     if($action=='update_category')
     {
          //echo "$r_id";
          if( mysqli_query($con, "update folio_categorytb set status='". mysqli_real_escape_string($con, $stat)."' where id='$r_id'"))
          {
               logs($login_id,"Update Record","Update folio category: Folio Category :=>$folio_category, Status:=>$stat");
               echo "<script>
               alert('Record updated successfully');</script>
               ";
          }
          else
          {
               echo "<script>alert('Unable to update record!');</script>";

          }


     } // end of update option
     /*	if($action=='search_category')
     {
     $rss=@mysqli_query($con, "select * from folio_categorytb order by folio_category");
     if( mysqli_num_rows($rss)>0)
     {
     echo "
     <table>
     <tr>
     <th>S/No</th>
     <th>Folio Category</th><th>Action</th>
     </tr>
     ";
     $sn=0;
     while($rsss= mysqli_fetch_array($rss))
     {
     $sn++;

     $category=@mysqli_real_escape_string($con, $rsss['folio_category']);
     $r_id=@$rsss['id'];
     echo"<tr>
     <td>$sn</td><td>$category</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('folio_section','delete_category',$r_id);\">Delete</a></td>
     </tr>";


}// end of while
echo "</table>";
}//end of if record found
}*/// end of if search_category
/**/	if($action=='search')
{

     //$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
     $r=@mysqli_query($con, "SELECT f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id WHERE folio_code = '{$j->folio_code}' or title = '{$j->title}' or category = '{$j->category}' order by category, folio_code");
}// end of search with criterials
else
{
     //$r=@mysqli_query($con, "select * from foliotb order by category,folio_code");
     $r=@mysqli_query($con, "SELECT f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id WHERE folio_code = '{$j->folio_code}' or title = '{$j->title}' or category = '{$j->category}' order by category, folio_code");
}// end of display all record

if($action=='save_category' || $action=='search_category'  || $action=='delete_category' || $action=='update_category'){

     //echo $action; exit;
     $rss=@mysqli_query($con, "select * from folio_categorytb order by folio_category");
     if( mysqli_num_rows($rss)>0)
     {
          echo "
          <table width='90%' align='center'>
          <tr align='left' style='background-color:lightgray'>
          <th style='border-bottom:inset 1px'>S/No</th>
          <th style='border-bottom:inset 1px'>Folio Category</th>
          <th style='border-bottom:inset 1px'>Status</th>
          <th style='border-bottom:inset 1px'>Action</th>
          </tr>
          ";
          $sn=0;
          while($rsss= mysqli_fetch_array($rss))
          {
               $sn++;
               $category=$rsss['folio_category'];
               $status=$rsss['status'];
               $r_idx=@$rsss['id'];
               echo"<tr class='ht-row' ";
               if($status=='Inactive') echo 'style="color:#F00; border-bottom:inset 1px;"';
               echo "><td style='border-bottom:inset 1px'>$sn</td><td style='border-bottom:inset 1px'>$category</td>
               <td style='border-bottom:inset 1px'>$status</td>
               <td style='border-bottom:inset 1px'>";
               echo '<a href="#" onClick="$(\'#status\').val(\'Active\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'folio_section\', \'update_category\', \''.$r_idx.'\');">Active</a> |
               <a href="#" onClick="$(\'#status\').val(\'Inactive\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'folio_section\', \'update_category\', \''.$r_idx.'\');">Inactive</a>';
               echo "<!-- | <a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('folio_section','delete_category',$r_id);\">Delete</a> -->
               </td>
               </tr>";


          }// end of while
          echo "</table>";
     }//end of if record found

     //		$r=@mysqli_query($con, "select * from folio_categorytb order by folio_category");
}
elseif($action=='search' || $action='save'  || $action=='delete_folio' || $action=='update_folio')
{
     //echo 'NOW Here 1'; echo $action; exit; exit;
     //$r= mysqli_query($con, "select * from foliotb order by category,folio_code") or die( mysqli_error($con));
     if( mysqli_num_rows($r)>0)
     {
          echo "
          <table width='100%' align='center' class='table display table-hover'>
          <tr align='left' style='background-color:lightgray'>
          <th style='border-bottom:inset 1px'>S/No</th>
          <th style='border-bottom:inset 1px'>Folio Code</th>
          <th style='border-bottom:inset 1px'>Title</th>
          <th style='border-bottom:inset 1px'>Category</th>
          <th style='border-bottom:inset 1px'>Status</th>
          <th style='border-bottom:inset 1px'>Action</th>
          </tr>
          ";
          $sno=0;
          while($rs= mysqli_fetch_array($r))
          {
               $sno++;
               $folio_code=$rs['folio_code'];
               $status=$rs['status'];
               $title=$rs['title'];
               $category=$rs['folio_category'];
               $r_ids=@$rs['id'];
               echo"<tr class='ht-row' ";
               if($status=='Inactive') echo 'style="color:#F00; border-bottom:inset 1px;"';
               echo "><td style='border-bottom:inset 1px'>$sno</td>
               <td style='border-bottom:inset 1px' nowrap>$folio_code</td>
               <td style='border-bottom:inset 1px'>$title</td>
               <td style='border-bottom:inset 1px'>$category</td>
               <td style='border-bottom:inset 1px'>$status</td>
               <td style='border-bottom:inset 1px'>";
               echo '<a href="#" onClick="$(\'#status\').val(\'Active\'); $(\'#idx\').val(\''.$rid.'\'); swapcontent(\'folio_section\', \'update_folio\', \''.$r_ids.'\');">Active</a> |
               <a href="#" onClick="$(\'#status\').val(\'Inactive\'); $(\'#idx\').val(\''.$rid.'\'); swapcontent(\'folio_section\', \'update_folio\', \''.$r_ids.'\');">Inactive</a>';
               echo " | <a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('folio_section','delete_folio',$r_id);\">Delete</a>
                | <a href='#' onclick=\"swapcontent('folio_section', 'edit', $r_ids);\">Edit</a>
               </td>
               </tr>";
          }// end of while
          echo "</table>";
     }

}


/*	else
{
echo "<script>
alert('No record to display');</script>
";
} */
//	echo "$mydata ==> $action ==> $r_id";


}// end of folio section


if($id=='save_bio')
{

     $mydata =  $_REQUEST['mydata'];
     $j=json_decode(stripslashes($mydata));
     //echo 'NOW   in'.$id; exit;
     //	echo "MYDATA: $mydata"; exit;
     $dept_str=@explode("***",$j->dept); $dept_id=trim($dept_str[0]); $fact_id=trim($dept_str[1]);
     //echo 'DEPT:   '.$j->dept; exit;
     {
          ////////////////action section /////////////////////////////////////
          $pix_fname = @$_FILES[$j->file]['name'];
          $pix_size = $_FILES[$j->file]['size'];
          $pix_ext = @explode(".",$pix_fname);  $pix_ext = $pix_ext[1];
          //echo $pix_ext;
          //exit;

          $sign_fname = @$_FILES['signature']['name'];
          $sign_size = $_FILES['signature']['size'];
          $sign_ext = @explode(".",$sign_fname);  $sign_ext = $sign_ext[1];

          $found=false;
          $upload_flag=false;
          $fileno=strtoupper($j->fileno);
          $uploadDir = "pictures/";  //upload directory for passport and signature

          if($pix_fname!='')
          {
               //////////passport uploading
               if ($pix_ext != "jpg" and $pix_ext != "JPG")
               {
                    echo "<script>alert('Invalid passport file type. JPG file should be uploaded. The file size must not be more than 30KB')</script>";
                    $found=true;
                    //exit;
               } //end of check extension
               if ($size>(1024*30)) //100KB size of image
               {
                    echo "<script>alert('The passport file size must not be more than 30KB.')</script>";
                    $found=true;
               } //end of check file size

               if($found!=true)  //all requerements met
               {
                    $pix_upload_file_name=@$fileno.".jpg";  //the file with .csv
                    $pix_upload_file_name=@str_replace("/","",$pix_upload_file_name);
                    $pix_upload_file_name=@str_replace(" ","",$pix_upload_file_name);
                    $pix_uploadFile = $uploadDir.$pix_upload_file_name;
                    if (@move_uploaded_file($_FILES['file']['tmp_name'], $pix_uploadFile))
                    { // file uploaded
                         echo "<script>alert('Pix Uploaded')</script>";
                    }
                    else
                    {echo "<script>alert('Pix NOT Uploaded')</script>";}//end of passport file uploaded
               }//end of found is not true, ready for upload passport
          } //end of picture uploading


          if($sign_fname!='')
          {
               //////////signature uploading
               if ($sign_ext != "jpg" and $sign_ext != "JPG")
               {
                    echo "<script>alert('Invalid signature file type. JPG file should be uploaded. The file size must not be more than 30KB')</script>";
                    $found=true;
                    //exit;
               } //end of check extension
               if ($size>(1024*30)) //100KB size of image
               {
                    echo "<script>alert('The signature file size must not be more than 30KB.')</script>";
                    $found=true;
               } //end of check file size

               if($found!=true)  //all requerements met
               {
                    $sign_upload_file_name=@$fileno."_sign".".jpg";  //the file with .csv
                    $sign_upload_file_name=@str_replace("/","",$sign_upload_file_name);
                    $sign_upload_file_name=@str_replace(" ","",$sign_upload_file_name);
                    $sign_uploadFile = $uploadDir.$sign_upload_file_name;
                    if (@move_uploaded_file($_FILES['signature']['tmp_name'], $sign_uploadFile))
                    { // file uploaded
                         //
                         echo "<script>alert('Sign Uploaded')</script>";
                    }
                    else
                    {echo "<script>alert('Sign NOT Uploaded')</script>";}//end of passport file uploaded
               }//end of found is not true, ready for upload passport
          } //end of picture uploading


          if($found!=true)  //all requerements met, ready for update record
          {

               $title=$j->title; $surname= mysqli_real_escape_string($con, $j->surname);

               $first_name= mysqli_real_escape_string($con, $j->first_name);
               $other_name= mysqli_real_escape_string($con, $j->other_name);
               $state=$j->state; 
               $lga=$j->lga;
               $religion=trim($j->religion); 
               $category=$j->category;

               if($j->date_appoint=='0000-00-00') $j->date_appoint='';
               else $date_appoint=date('Y-m-d',strtotime($j->date_appoint));

               if($j->date_birth=='0000-00-00') $j->date_birth='';
               else $date_of_birth=date('Y-m-d',strtotime($j->date_birth));

               if($j->date_assume=='0000-00-00') $j->date_assume='';
               else $date_assume=date('Y-m-d', strtotime($j->date_assume));

               $dept=trim($j->dept); 
               $unit=trim($j->unit); 
               $rank=trim($j->rank);
               $sex=trim($j->sex); 
               $email=trim($j->email);
               $phone_no=trim($j->phone_no); 
               $level=trim($j->level);
               $step=trim($j->step); 
               $bankname=trim($j->bankname);
               $acct_no=trim($j->acct_no);
               $marital_status=trim($j->marital_status);
               $status=trim($j->status);

               $res_sc=@mysqli_query($con, "select scale_name from scale_nametb where category='$category' and status = 'Active' limit 1");
               $res_scs =  mysqli_fetch_array($res_sc);
               $salary_scale = $res_scs["scale_name"];

               $res_s=@mysqli_query($con, "select * from stafftb where fileno='$fileno'");
               $password=base64_encode('1111'); //default password
               //	echo $date_appoint.$surname.'  requires met'.$acct_no; exit;

               if(@mysqli_num_rows($res_s)>=1)
               {
                    //update section
                    $query="update stafftb set fileno='$fileno',title='$title',surname='$surname',first_name='$first_name',other_name='$other_name',state_id='$state',lga_id='$lga',category='$category',dept_code='$dept',unit_code='$unit',sex='$sex',email='$email',phone_no='$phone_no',religion='$religion',level='$level',step='$step',rank='$rank',acct_no='$acct_no',bank_name='$bankname',date_of_1st_appt='$date_appoint',date_of_birth='$date_of_birth', status='$status', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id', salary_scale='$salary_scale' where fileno='$fileno'";
               }
               else
               {
                    //save section
                    $query="insert into stafftb set fileno='$fileno',title='$title',surname='$surname',first_name='$first_name',other_name='$other_name',state_id='$state',lga_id='$lga',category='$category',dept_code='$dept',unit_code='$unit',sex='$sex',email='$email',phone_no='$phone_no',religion='$religion',level='$level',step='$step',rank='$rank',acct_no='$acct_no',bank_name='$bankname',date_of_1st_appt='$date_appoint',date_of_birth='$date_of_birth', status='$status', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id', password='$password', salary_scale='$salary_scale'";
               }
               //echo $query;
               if(mysqli_query($con, $query)){
                    logs("$login_id","Save Record","$login_id save staff record $fileno $surname $first_name");
                    echo "<script>alert('Staff record updated successfully.')</script>";
               }else{
                    //echo mysqli_error($con);
                    echo "<script>alert('Staff record update failed.')</script>";
               }
          } //end of update record


          ////////////////end of action section /////////////////////////////////////
     }
}

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
     if($j->amount=='' ||  $j->amount==0){?> <script>alert('Invalid Ammount')</script><?php exit; }
     if($action=='save')
     {
          $res_chk=@mysqli_query($con, "select * from budgettb where folio_code='$j->folio' and dept_code='$j->dept' and unit_code='$j->unit' and budget_year='$j->b_year'");
          if(@mysqli_num_rows($res_chk)>=1)
          {
               // $row=@$j->row_id;  //row id of record to edit
               $rs_chk1 =  mysqli_fetch_array($res_chk);
               $row = $rs_chk1["id"];
               @mysqli_query($con, "update budgettb set folio_code='$j->folio',dept_code='$j->dept',unit_code='$j->unit',budget_year='$j->b_year',amount='$j->amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where id='$row'");
          }
          else
          {
               @mysqli_query($con, "insert into budgettb set folio_code='$j->folio',dept_code='$j->dept',unit_code='$j->unit',budget_year='$j->b_year',amount='$j->amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          } //end of save

          logs("$login_id","Save Record","$login_id saved budget record $j->folio $j->dept $j->unit_code $j->budget_year $j->amount");
          $sql="select * from budgettb where dept_code='$j->dept' and budget_year='$j->b_year' order by folio_code,dept_code,unit_code,budget_year";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          // echo 'delete'.'    '.$r_id;
          $res_d=@mysqli_query($con, "select * from budgettb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $log_desc=$rs_d['folio_code'].$rs_d['dept_code'].$rs_d['unit_code'].$rs_d['budget_year'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted budget record $log_desc");

          @mysqli_query($con, "delete from budgettb where id='$r_id'");
          $sql="select * from budgettb where dept_code='{$rs_d['dept_code']}' and budget_year='{$rs_d['budget_year']}' order by folio_code,dept_code,unit_code,budget_year";
          //$sql="select * from budgettb";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from budgettb where 1";
          if($j->folio!="") $sql.=" and folio_code='$j->folio'";
          if($j->dept!="") $sql.=" and dept_code='$j->dept'";
          if($j->unit!="") $sql.=" and unit_code='$j->unit'";
          if($j->b_year!="") $sql.=" and budget_year='$j->b_year'";

          $sql.=" order by folio_code,dept_code,unit_code,budget_year";
          // echo  $sql; exit;
     }

     /* if($action=='edit')
     {
     //$res_b=@mysqli_query($con, "select * from budgettb where id='$r_id'");

     //$fileno=@$_REQUEST['fileno'];
     $db->sql("select * from budgettb where id='$r_id'");
     if(get_magic_quotes_gpc())
     $t= @json_decode(stripslashes($db->getResult()));
     else
     $t= @json_decode($db->getResult());
     $s_array=array(s_detail=>"",msg=>"");

     if($t->row>=1) //found
     {
     $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
}

exit;

}*/

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
          $tb.="<tr><td>$sn</td><td>".@get_folio_name($rs_v['folio_code'])."</td><td>".@get_dept_name($rs_v['dept_code'])."</td><td>".@get_unit_name($rs_v['dept_code'],$rs_v['unit_code'])."</td><td>{$rs_v['budget_year']}</td><td>N".number_format($rs_v['amount'],2)."</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('budget_section','delete','$r_id');\">DELETE</a> </td></tr>";
          // || <a href=\"javascript:swapcontent('budget_section','edit','$r_id');\">EDIT</a>
     }//end of while

     $tb_s="<center><span align='center'><b>TOTAL AMOUNT: N". @number_format($g_total,2)."</b></span></center>";
     $tb.="<tr><td colspan='5'><b><p align='right'>TOTAL AMOUNT:</p></b></td><td colspan='2'><b>N".@number_format($g_total,2)."</b></td></tr>";
     $tb.="</table>";
     echo $tb_s.$tb;
}
else
echo "<b>No record to display</b>";

}



if($id=='login')
{

     echo "<div style='font-weight:bold;margin:7px;font-family:Arial black;background-color:#D3E488; color:#2AA100;padding:10px 60px;'>USER LOGIN</div><form name='frmlogin' id='frmlogin'>
     <strong>File Number :</strong><br><input type='text' name='username' id='username' placeholder='File number' size='35' class='txt'><br/><br>
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
     $res_l=@mysqli_query($con, "select s.title,s.surname,s.first_name,s.other_name,s.status,s.category from stafftb s,users_roletb r where s.fileno=r.fileno and s.fileno='$login_id' and s.password='$pass_base'");
     $rs_l=@mysqli_fetch_array($res_l);
     if(@mysqli_num_rows($res_l)>=1)
     {
          $login_status=@$rs_l['status'];
          if($login_status=='Active')
          {
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


     //after every login attempt
     /* echo "<script>alert('Invalid login parameters');</script>"; */
     echo "<div class='error_msg'>Invalid login parameters</div>";
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
          @mysqli_query($con, "insert into portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Change password','Password change by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
          // echo "<font color='red'><b>Password has been changed successfully. Please Logout to confirm</b></font>";
          echo "<script>alert('Password has been changed successfully. Please re-login to confirm');swapcontent('logout','login.php');</script>";


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
          <br /></span>
          LGA:
          <span id='lgadiv'>
          <select name='lga' id='lga'>
          </select></span>";
     } //end of nigerian
} //end of nationality
if($id=='lgadiv')
{
     $val=@$_REQUEST['val'];
     $lga=@$_REQUEST['lga_id'];
     if($lga==''){
          echo "<select name='lga' id='lga'><option selected value=''>---</option>";
          $res_l=@mysqli_query($con, "select * from lgatb where state_id='$val' order by lga_name");
     }
     else{
          $res_l2=@mysqli_query($con, "select * from lgatb where state_id='$val' and lga_id = '$lga' order by lga_name");
          $rs_l2=@mysqli_fetch_array($res_l2);
          $lga_name=@$rs_l2['lga_name'];
          echo "<select name='lga' id='lga'><option selected value='$lga'>$lga_name</option>";
          $res_l=@mysqli_query($con, "select * from lgatb where state_id='$val' and lga_id != '$lga' order by lga_name");
     }


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

          $to = $email; $subject = "UNILORIN: Password Recovery Notification";
          $msg = "Hello <strong>$login_id  $fullname</strong> <br /><br /> You filled our password recovery form on $todayDate. <br /><br />Find below your login details: <br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Login ID: $login_id<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Password: $real_pass <br /><br /><strong>NOTE:</strong> Always keep your password safe. It should also be noted that your password is case-sensitive and must be typed as appeared in this mail. <br /><br /><strong>Best Regards.<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;UNILORIN Portal Team</strong>";

          $headers = "From: portalhelpdesk@unilorin.edu.ng   \r\n";
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

if($id=="update_biodata")
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     //echo $j->state." ".$j->lga." ".$j->appno." ".$j->sex." ".$j->dob." ".$j->nationality;
     $dob=@date('Y-m-d',@strtotime($j->dob));
     if($j->nationality=='Nigerian')
     {
          $country="Nigeria";
          $stud_cat="Local";
     }
     else
     {
          $country=$j->country;
          $stud_cat="International";
     }

     @mysqli_query($con, "update candidatetb set regno='$j->appno',title='$j->title',surname='".@mysqli_real_escape_string($con, $j->surname)."',first_name='".@mysqli_real_escape_string($con, $j->first_name)."',other_name='".@mysqli_real_escape_string($con, $j->other_name)."',mailing_address='".@mysqli_real_escape_string($con, $j->mailing_add)."',nationality='$j->nationality',country='$country',religion='$j->religion',state_id='$j->state',lga_id='$j->lga',contact_address='".@mysqli_real_escape_string($con, $j->contact_add)."',date_of_birth='$dob',place_of_birth='$j->place_of_birth',email='$j->email',tel_no='$j->phone_no',sex='$j->sex',marital_status='$j->marital_status',spouse_surname='".@mysqli_real_escape_string($con, $j->s_surname)."',spouse_first_name='".@mysqli_real_escape_string($con, $j->s_first_name)."',spouse_other_name='".@mysqli_real_escape_string($con, $j->s_other_name)."',spouse_address='".@mysqli_real_escape_string($con, $j->s_address)."',guardian_name='".@mysqli_real_escape_string($con, $j->g_name)."',guardian_address='".@mysqli_real_escape_string($con, $j->g_address)."',guardian_phone='$j->g_phone_no',guardian_email='$j->g_email',guardian_relationship='$j->g_relationship',extra_curricular='$j->extra',kin_name='".@mysqli_real_escape_string($con, $j->k_name)."',kin_address='".@mysqli_real_escape_string($con, $j->k_address)."',kin_phone='$j->k_phone_no',kin_email='$j->k_email',kin_relationship='$j->k_relationship',mode_of_study='$j->mode_of_study',nysc_number='$j->nysc_no',nysc_post='".@mysqli_real_escape_string($con, $j->nysc_place)."',nysc_place='".@mysqli_real_escape_string($con, $j->nysc_place)."',nysc_from='$j->nysc_from',nysc_to='$j->nysc_to',prize='$j->prize',thesis_title='".@mysqli_real_escape_string($con, $j->thesis)."',reg_step='Biodata',disability='$j->disability',disability_reason='$j->disable_nature',entry_by='$j->appno',studentship_category='$stud_cat' where regno='$j->appno'");

     echo "<script> alert('Your biodata has been updated sucessfully');</script>";
} //end of update biodata for pg application

if($id=="add_education")
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
     if($action=='save')
     {
          @mysqli_query($con, "insert into candidate_qualificationtb set regno='$j->edu_appno',institution_name='".@mysqli_real_escape_string($con, $j->edu_school)."',place='".@mysqli_real_escape_string($con, $j->edu_location)."',country='$j->edu_country',year_from='$j->edu_from',year_to='$j->edu_to',degree_type='$j->edu_type',class_of_degree='$j->edu_class',course='".@mysqli_real_escape_string($con, $j->edu_course)."',field_of_study='".@mysqli_real_escape_string($con, $j->edu_field)."',entry_by='$j->edu_appno'");

          $sql="select * from candidate_qualificationtb where regno='$j->edu_appno' order by year_from";

          echo "<script> alert('Your educational record has been updated sucessfully');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select regno from candidate_qualificationtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from candidate_qualificationtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from candidate_qualificationtb where regno='$regno' order by year_from";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>INSTITUTION</th><th>COURSE</th><th>FROM</th><th>TO</th><th>CLASS OF DEGREE</th><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $tb.="<tr><td>$sn</td><td>{$rs_v['institution_name']}</td><td>{$rs_v['course']}</td><td>{$rs_v['year_from']}</td><td>{$rs_v['year_to']}</td><td>{$rs_v['class_of_degree']}</td><td><a href=\"javascript:swapcontent('add_education','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;

} //add education

if($id=="add_employment")  //add_employment
{
     $j=json_decode(stripslashes($_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];

     //echo "$j->edu_school $j->edu_from $j->edu_class $action $r_id $j->edu_appno";
     if($action=='save')
     {
          @mysqli_query($con, "insert into candidate_appointmenttb set regno='$j->emp_appno',appointment='".@mysqli_real_escape_string($con, $j->emp_position)."',place='".@mysqli_real_escape_string($con, $j->emp_place)."',year_from='$j->emp_from',year_to='$j->emp_to',salary='$j->emp_salary',termination_reason='$j->emp_reason'");

          $sql="select * from candidate_appointmenttb where regno='$j->emp_appno' order by year_from";

          echo "<script> alert('Your appointment record has been updated sucessfully');</script>";
     }

     if($action=='delete')
     {
          //
          $res_d=@mysqli_query($con, "select regno from candidate_appointmenttb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from candidate_appointmenttb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from candidate_appointmenttb where regno='$regno' order by year_from";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>POSITION</th><th>PLACE</th><th>FROM</th><th>TO</th><th>SALARY</th><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $tb.="<tr><td>$sn</td><td>{$rs_v['appointment']}</td><td>{$rs_v['place']}</td><td>{$rs_v['year_from']}</td><td>{$rs_v['year_to']}</td><td>{$rs_v['salary']}</td><td><a href=\"javascript:swapcontent('add_employment','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;

} //add employment


////////////////////////////////////////Bursary Automation Management System (BAMS) /////////////////////////////////
if($id=='salary_scale_section')
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
          // echo 'now hetre';
          @mysqli_query($con, "insert into salary_scaletb set scale_name='$j->scalename', folio_code='$j->account', step='$j->step', level='$j->level', category='$j->category',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
          // logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          //$sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record saved successfully');</script>";

          $sql="select * from account_allocationtb";
     }

     /////////
     if($action=='search')
     {
          $sql="select * from account_allocationtb where 1";
          if($j->account!="") $sql.=" and acctcode='$j->account'";
          if($j->dept_code!="") $sql.=" and dept_acctcode='$j->dept_code'";
          // if($j->accttype!="") $sql.=" and accttype='$j->accttype'";

          $sql.=" order by dept_acctcode";

     }

     if($action=='delete')
     {
          //	   $r_id='$id2'
          $res_d=@mysqli_query($con, "select * from account_allocationtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          //$regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from account_allocationtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from account_allocationtb";
     }

     if($action=='view_all')
     {
          $sql="select * from account_allocationtb order by dept_acctcode";

     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>BANK NAME</th><!--<th>ENTRY DATE</th><th>ENTRY TIME</th>--><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $f_code=@$rs_v['bankname'];
          $tb.="<tr><td>$sn</td><td>{$rs_v['dept_acctcode']}</td><!--<td>{$rs_v['entry_date']}</td><td>{$rs_v['entry_time']}</td>--><td><a href=\"javascript:swapcontent('account_allocation_section','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;
     ///////////////////////////



}// end Bank section


if($id=='account_allocation_section')
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
          // echo 'now hetre';
          @mysqli_query($con, "insert into account_allocationtb set dept_acctcode='$j->deptcode', acctcode='$j->account',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
          // logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          //$sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record saved successfully');</script>";

          $sql="select * from account_allocationtb";
     }

     /////////
     if($action=='search')
     {
          $sql="select * from account_allocationtb where 1";
          if($j->account!="") $sql.=" and acctcode='$j->account'";
          if($j->dept_code!="") $sql.=" and dept_acctcode='$j->dept_code'";
          // if($j->accttype!="") $sql.=" and accttype='$j->accttype'";

          $sql.=" order by dept_acctcode";

     }

     if($action=='delete')
     {
          //	   $r_id='$id2'
          $res_d=@mysqli_query($con, "select * from account_allocationtb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          //$regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from account_allocationtb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from account_allocationtb";
     }

     if($action=='view_all')
     {
          $sql="select * from account_allocationtb order by dept_acctcode";

     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>BANK NAME</th><!--<th>ENTRY DATE</th><th>ENTRY TIME</th>--><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $f_code=@$rs_v['bankname'];
          $tb.="<tr><td>$sn</td><td>{$rs_v['dept_acctcode']}</td><!--<td>{$rs_v['entry_date']}</td><td>{$rs_v['entry_time']}</td>--><td><a href=\"javascript:swapcontent('account_allocation_section','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;
     ///////////////////////////



}// end Bank section


if($id=='bank_account_section')
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
          // echo 'now hetre';
          @mysqli_query($con, "insert into bank_accounttb set acctcode='$j->acctcode', acctname='$j->acctname',acctno='$j->acctno',bankname='$j->bankname',accttype='$j->accttype',sortcode='$j->sortcode',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
          // logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          //$sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record saved successfully');</script>";

          $sql="select * from bank_accounttb";
     }

     /////////
     if($action=='search')
     {
          $sql="select * from bank_accounttb where 1";
          if($j->acctcode!="") $sql.=" and acctcode='$j->acctcode'";
          if($j->acctname!="") $sql.=" and acctname='$j->acctname'";
          if($j->accttype!="") $sql.=" and accttype='$j->accttype'";

          $sql.=" order by acctname";

     }

     if($action=='delete')
     {
          //	   $r_id='$id2'
          $res_d=@mysqli_query($con, "select bankname from bank_accounttb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          //$regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from bank_accounttb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from bank_accounttb";
     }

     if($action=='view_all')
     {
          $sql="select * from bank_accounttb order by bankname";

     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>BANK NAME</th><!--<th>ENTRY DATE</th><th>ENTRY TIME</th>--><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $f_code=@$rs_v['bankname'];
          $tb.="<tr><td>$sn</td><td>{$rs_v['bankname']}</td><!--<td>{$rs_v['entry_date']}</td><td>{$rs_v['entry_time']}</td>--><td><a href=\"javascript:swapcontent('bank_account_section','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;
     ///////////////////////////



}// end Bank section

if($id=='bank_section')
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
          @mysqli_query($con, "insert into banktb set bankname='$j->bank_name',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          // logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          //$sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record saved successfully');</script>";

          $sql="select * from banktb";
     }

     /////////
     if($action=='delete')
     {
          //	   $r_id='$id2'
          $res_d=@mysqli_query($con, "select bankname from banktb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          //$regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from banktb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from banktb";
     }

     if($action=='view_all')
     {
          $sql="select * from banktb order by bankname";

     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>BANK NAME</th><!--<th>ENTRY DATE</th><th>ENTRY TIME</th>--><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $f_code=@$rs_v['bankname'];
          $tb.="<tr><td>$sn</td><td>{$rs_v['bankname']}</td><!--<td>{$rs_v['entry_date']}</td><td>{$rs_v['entry_time']}</td>--><td><a href=\"javascript:swapcontent('bank_section','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;
     ///////////////////////////



}// end Bank section



if($id=='unit_section')
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
          @mysqli_query($con, "INSERT INTO unittb set dept_code='".mysqli_real_escape_string($con, $j->dept_code)."',unit_name='". mysqli_real_escape_string($con, $j->unit_name)."',unit_code='$j->unit_code',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
          logs("$login_id","Save Record","$login_id saved unit record $j->unit_code $j->unit_name");
          $sql="select * from unittb order by unit_code ";
          @mysqli_query($con, "INSERT INTO departmenttb SET dept_code='".mysqli_real_escape_string($con, $j->unit_code)."', dept_name='". mysqli_real_escape_string($con, $j->unit_name)."', sch_code='University', category='Non-Academic', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'") or die( mysqli_error($con));
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          // echo 'delete'.$r_id; exit;
          $res_d=@mysqli_query($con, "select * from unittb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $s_name=$rs_d['dept_name'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted school record {$rs_d['unit_name']}");

          @mysqli_query($con, "delete from unittb where id='$r_id'");
          $sql="select * from unittb order by unit_code";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from unittb where 1";
          if($j->code!="") $sql.=" and dept_code='$j->dept_code'";
          if($j->name!="") $sql.=" and unit_code='$j->unit_code'";
          if($j->status!="") $sql.=" and unit_name='$j->unit_name'";

          $sql.=" order by unit_code";

     }


     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $tb="<table><tr><th>S/N</th><th>DEPT CODE</th><th> UNIT CODE</th><th> UNIT NAME</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['dept_code']}</td><td>{$rs_v['unit_code']}</td><td>{$rs_v['unit_name']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('unit_section','delete','$r_id');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}


if($id=='dept_section')
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
          @mysqli_query($con, "insert into departmenttb set sch_code='$j->sch_code',dept_name='". mysqli_real_escape_string($con, $j->dept_name)."',dept_code='$j->dept_code',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
          logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          $sql="select * from departmenttb order by sch_code";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          // echo 'delete'.$r_id; exit;
          $res_d=@mysqli_query($con, "select * from departmenttb where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $s_name=$rs_d['dept_name'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted school record {$rs_d['sch_name']}");

          @mysqli_query($con, "delete from departmenttb where id='$r_id'");
          $sql="select * from departmenttb order by sch_code";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from departmenttb where 1";
          if($j->code!="") $sql.=" and sch_code='$j->sch_code'";
          if($j->name!="") $sql.=" and dept_code='$j->dept_code'";
          if($j->status!="") $sql.=" and dept_name='$j->dept_name'";

          $sql.=" order by dept_code";

     }


     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $tb="<table><tr><th>S/N</th><th>SCH CODE</th><th> DEPT CODE</th><th> DEPT NAME</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['sch_code']}</td><td>{$rs_v['dept_code']}</td><td>{$rs_v['dept_name']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('dept_section','delete','$r_id');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}
if($id=='rev_section')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     //$code=$j->code;
     //$name=@mysqli_real_escape_string($con, $j->name);
     //$status=$j->status;
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     $folio_type = explode("***", $j->folio_type); $category = $folio_type[0];   $ord = $folio_type[1];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
          @mysqli_query($con, "insert into revenue_code set folio_code='$j->folio_code',category='".$category."',ord='$ord',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
          logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          $sql="select * from revenue_code order by ord";
          echo "<script>alert('Record saved successfully');</script>";
     }

     if($action=='delete')
     {
          // echo 'delete'.$r_id; exit;
          $res_d=@mysqli_query($con, "select * from revenue_code where id='$r_id'"); $rs_d=@mysqli_fetch_array($res_d); $s_name=$rs_d['folio_code'];//for logs purpose
          logs("$login_id","Delete Record","$login_id deleted school record {$rs_d['category']}");

          @mysqli_query($con, "delete from revenue_code where id='$r_id'");
          $sql="select * from revenue_code order by ord";
          echo "<script>alert('Record deleted successfully');</script>";
     }

     if($action=='search')
     {
          $sql="select * from revenue_code where 1";
          if($j->folio_code!="") $sql.=" and folio_code='$j->folio_code'";
          if($j->folio_type!="") $sql.=" and category='$category'";

          $sql.=" order by ord";

     }


     /////////////////////view section ////////////////////
     $sn=0;
     $res_v=@mysqli_query($con, $sql);
     $tb="<table><tr><th>S/N</th><th>FOLIO CODE</th><th> REVENUE TITLE</th><th> FOLIO NAME</th><th> CATEGORY</th><th>REV CODE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $r_id=$rs_v['id']; $folio_code = $rs_v['folio_code'];
               $folio_name = $bursary->get_any_value("title", "foliotb", "folio_code", $folio_code);
               $tb.="<tr><td>$sn</td><td>{$rs_v['folio_code']}</td><td>{$rs_v['reven_title']}</td><td>$folio_name</td><td>{$rs_v['category']}</td><td>{$rs_v['rev_code']}</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation')==true) swapcontent('rev_section','delete','$r_id');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}

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
     $tb="<table><tr><th>S/N</th><th>CODE</th><th> NAME</th><th> STATUS</th><th>ACTION</th></tr>";
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

}

if($id=='load_unit')
{
     $dept_code=@$_REQUEST['dept_code'];
     $unit_code=@$_REQUEST['unit_code'];
     /*if($unit_code!='' and $dept_code!=''){
     echo '<select name="unit" id="unit">';
     $res_c2=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' and unit_code = '$unit_code'");
     $rs_c2=@mysqli_fetch_array($res_c2);
     $unit_name=@$rs_c2['unit_name'];
     echo "<option selected value='$unit_code'>$unit_name</option>";
     echo '</select>';
}*/

if($unit_code==''){
     echo '<select name="unit" id="unit">
     <option selected="selected" value="">---</option>';

     $res_c=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' order by unit_name");

}
else
{
     $res_c2=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' and unit_code = '$unit_code' order by unit_name");
     $rs_c2=@mysqli_fetch_array($res_c2);
     $unit_name=@$rs_c2['unit_name'];
     echo "<select name='unit' id='unit'><option selected value='$unit_code'>$unit_name</option>";
     $res_c=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' and unit_code != '$unit_code' order by unit_name");
}



//     $res_c=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' order by unit_name");
while($rs_c=@mysqli_fetch_array($res_c))
{
     $unit_code=@$rs_c['unit_code'];
     $unit_name=@$rs_c['unit_name'];
     echo "<option value='$unit_code'>$unit_name</option>";
}
echo "</select>";
}

if($id=='load_staff_details')
{
     //echo 'bursary';
     $fileno=@$_REQUEST['fileno'];
     $db->sql("select * from stafftb where fileno='$fileno'");
     if(get_magic_quotes_gpc())
     $t= @json_decode(stripslashes($db->getResult()));
     else
     $t= @json_decode($db->getResult());
     $s_array=array(s_detail=>"",msg=>"");

     if($t->row>=1) //found
     {
          $s_array['s_detail']=$t->data; $s_array['msg']='1'; echo @json_encode($s_array);
     }
     exit;
}


if($id=='disciplinary_display')
{
     $dis_ref_no=@$_REQUEST['dis_ref_no'];
     $db->sql("select * from hr_disciplinarytb where disc_ref_no='$dis_ref_no'");
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


/////////////////////tax_rate section ////////////////////
if($id=='taxrate_section')
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
          @mysqli_query($con, "insert into tax_ratetb set folio_code='$j->folio',rate='$j->rate',acctcode='$j->acctcode',payee_name='$j->payee_name',payee_address='".@mysqli_real_escape_string($con, $j->payee_addr)."',payee_acct_no='$j->payee_acct',payee_tin_number='$j->payee_tin',payee_sort_code='$j->sort_code',payee_type='$j->payee_type',payee_bank_name='$j->payee_bank',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
          // logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          //$sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record saved successfully');</script>";

          $sql="select * from tax_ratetb";
     }

     /////////
     if($action=='delete')
     {
          //	   $r_id='$id2'
          $res_d=@mysqli_query($con, "select regno from tax_ratetb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          //$regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from tax_ratetb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from tax_ratetb";
     }

     if($action=='search')
     {
          $sql="select * from tax_ratetb where 1";
          if($j->folio!="") $sql.=" and folio_code='$j->folio'";
          if($j->rate!="") $sql.=" and rate='$j->rate'";
          $sql.=" order by folio_code";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><<tr><th>S/NO</th><th>FOLIO_CODE</th><th>FOLIO NAME</th><th>RATE</th><th>BANK ACCT</th><th>ENTRY DATE</th><th>ENTRY TIME</th><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $f_code=@$rs_v['folio_code'];
          $f_name=@get_folio_name($f_code);
          $tb.="<tr><td>$sn</td><td>{$rs_v['folio_code']}</td><td>$f_name</td><td>{$rs_v['rate']}</td><td>{$rs_v['acctcode']}</td><td>{$rs_v['entry_date']}</td><td>{$rs_v['entry_time']}</td><td><a href=\"javascript:swapcontent('taxrate_section','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;
     ///////////////////////////

}// end tax_rate section



/////////////////////Bank section ////////////////////

/////////////////////Allocaterole section ////////////////////
if($id=='allocaterole_section')
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
          @mysqli_query($con, "insert into users_roletb set fileno='{$j->fileno}',role='{$j->role}',dept_acctcode='{$j->dept_acctcode}',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='{$login_id}'");
          echo "<script>alert('Record saved successfully');</script>";

          $sql="select * from users_roletb where fileno='{$j->fileno}'";
     }

     /////////
     if($action=='delete')
     {
          //	   $r_id='$id2'
          $res_d=@mysqli_query($con, "select * from users_roletb where id='{$r_id}'");
          $rs_d=@mysqli_fetch_array($res_d);
          //$regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from users_roletb where id='{$r_id}'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from users_roletb where fileno='{$j->fileno}'";
          $action='search';
     }

     if($action=='search')
     {
          if($j->fileno=='' && $j->dept_acctcode=='' && $j->role=='') $sql="SELECT u.*, r.caption FROM users_roletb u inner join roletb r on u.role=r.role order by u.role";
          else{
               $sql="SELECT u.*, r.caption FROM users_roletb u INNER JOIN roletb r ON u.role=r.role WHERE 1=1 ";
               if($j->fileno!='') $sql .= " AND u.fileno='{$j->fileno}' ";
               if($j->dept_acctcode!='') $sql .= " AND u.dept_acctcode='{$j->dept_acctcode}' ";
               if($j->role!='') $sql .= " AND u.role='{$j->role}' ";
               $sql .= " order by u.role ";
          }
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table id='MyTable' class='table display' style='border: groove 1px #C90' border='1' frame='hsides' rules='rows' width='100%'>
     <thead>
          <tr>
               <th>S/NO</th>
               <th>FILE NO</th>
               <th>ROLE CAPTION</th>
               <th>ROLE NAME</th>
               <th>ENTRY DATE</th>
               <th>ENTRY TIME</th>
               <th>ACTION</th>
          </tr>
     </thead><tbody>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $f_code=@$rs_v['bank_name'];
          $tb.="<tr>
                    <td>$sn</td>
                    <td>{$rs_v['fileno']}: ".@get_staff_name($rs_v['fileno'])."</td>
                    <td>{$rs_v['caption']}</td>
                    <td>{$rs_v['role']}</td>
                    <td>{$rs_v['entry_date']}</td>
                    <td>{$rs_v['entry_time']}</td>
                    <td><a href=\"javascript:swapcontent('allocaterole_section','delete','$id2')\">DELETE</a> | <a href=\"javascript:swapcontent('assign_faculty', 'view', '{$rs_v['fileno']}')\">FACULTY</a></td>
               </tr>";
     } //end of while

     $tb.="</tbody></table></center>";
     echo $tb;
     ///////////////////////////
}// end allocaterole section

if($id=='assign_faculty')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $rid=@$_REQUEST['rid'];
     $unitcode=@$_REQUEST['unitcode'];
     $login_id=@$_SESSION['login_id'];
     if($action=='save')
     {
          if(mysqli_query($con, "INSERT INTO journal_code_user SET fileno='{$r_id}', jvcode='{$unitcode}', entrydate=CURDATE()")){
               echo "<script>alert('The select faculty/center has been assigned to {$r_id}.');</script>";
          }else{
               echo "<script>alert('Operation failed, try again!');</script>";
          }
     }

     if($action=='delete')
     {
          @mysqli_query($con, "DELETE from journal_code_user WHERE id='{$rid}'");
          echo "<script> alert('Record deleted successfully!');</script>";
     }

     if($r_id!='') $sql="SELECT j.*, CONCAT(s.surname, ' ', s.first_name, ' ', s.other_name) AS names, u.unit_name FROM ((journal_code_user j INNER JOIN stafftb s ON j.fileno = s.fileno) INNER JOIN unittb u ON j.jvcode=u.unit_code) WHERE j.fileno='{$r_id}' ORDER BY u.unit_name";

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $staff=strtoupper(@get_staff_name($r_id));
     $tb="<h4>FACULTY ASSIGNMENT: {$staff}</h4>
          <select name='dept_acctcode2' id='dept_acctcode2' style='width:350px'>
               <option value='' selected='selected'>--Select Item--</option>";
               $q =  mysqli_query($con, "SELECT * FROM unittb WHERE status='Active' ORDER BY unit_name");
               while($r= mysqli_fetch_array($q, 3 )){
                    $tb .= "<option value='{$r['unit_code']}'>{$r['unit_name']}</option>";
               }
               $tb .= "</select> <input type='button' name='assign' id='assign' value='ASSIGN' class='btn' onclick=\"swapcontent('assign_faculty', 'save', '{$r_id}', $('#dept_acctcode2').val());\"/>
     <center>
     <table id='MyTable' class='table display' style='border: groove 1px #C90' border='1' frame='hsides' rules='rows' width='100%'>
     <thead>
          <tr>
               <th>S/NO</th>
               <th>FILE NO</th>
               <th>NAME</th>
               <th>FACULTY/CENTER</th>
               <th>DATE</th>
               <th>ACTION</th>
          </tr>
     </thead><tbody>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $filen=@$rs_v['fileno'];
          $tb.="<tr>
                    <td>$sn</td>
                    <td>{$rs_v['fileno']}</td>
                    <td>{$rs_v['names']}</td>
                    <td>{$rs_v['unit_name']}</td>
                    <td>".date('d-M-Y', strtotime($rs_v['entrydate']))."</td>
                    <td><a href=\"javascript:swapcontent('assign_faculty','delete','$filen', '', '$id2')\">REMOVE</a></td>
               </tr>";
     } //end of while

     $tb.="</tbody></table></center>";
     echo $tb;
     ///////////////////////////
}// end allocaterole section


/////////////////////school fee breakdown section ////////////////////
if($id=='schfeebreakdown_section')
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
          @mysqli_query($con, "insert into schoolfee_breakdowntb set programme='$j->programme',session='$j->session',folio_code='$j->folio_code',student_level='$j->student_level',amount='$j->amount',entry_date=CURDATE(), entry_time=CURTIME(),entry_by='$login_id'");
          // logs("$login_id","Save Record","$login_id saved school record $j->code $j->name");
          //$sql="select * from schooltb order by sch_code";
          echo "<script>alert('Record saved successfully');</script>";

          $sql="select * from schoolfee_breakdowntb";
     }



     //////////////////////
     if($action=='delete')
     {
          //	   $r_id='$id2'
          $res_d=@mysqli_query($con, "select * from schoolfee_breakdowntb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          //$regno=@$rs_d['regno'];

          @mysqli_query($con, "delete from schoolfee_breakdowntb where id='$r_id'");
          echo "<script> alert('Record deleted successfully');</script>";
          $sql="select * from schoolfee_breakdowntb";
     }

     if($action=='search')
     {
          $sql="select * from schoolfee_breakdowntb where 1";
          if($j->programme!="") $sql.=" and programme='$j->programme'";
          if($j->session!="") $sql.=" and session='$j->session'";
          if($j->folio_code!="") $sql.=" and folio_code='$j->folio_code'";
          if($j->student_level!="") $sql.=" and student_level='$j->student_level'";
          $sql.=" order by session,folio_code,student_level";
     }

     $res_v=@mysqli_query($con, $sql);
     $sn=0;
     $tb="<center><table><tr><th>S/NO</th><th>PROGRAMME</th><th>SESSION</th><th>FOLIO CODE</th><th>STUDENT LEVEL</th><th>AMOUNT</th><th>ENTRY DATE</th><th>ENTRY TIME</th><th>ACTION</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {
          ++$sn;
          $id2=@$rs_v['id'];
          $f_code=@$rs_v['folio_code'];
          $f_name=@get_folio_name($f_code);
          $tb.="<tr><td>$sn</td><td>{$rs_v['programme']}</td><td>{$rs_v['session']}</td><td>{$rs_v['folio_code']}</td><td>{$rs_v['student_level']}</td><td>{$rs_v['amount']}</td><td>{$rs_v['entry_date']}</td><td>{$rs_v['entry_time']}</td><td><a href=\"javascript:swapcontent('schfeebreakdown_section','delete','$id2')\">DELETE</a></td></tr>";
     } //end of while

     $tb.="</table></center>";
     echo $tb;
     ///////////////////////////

}// end schfeebreakdawn section

if($id=='dept_acct')
{
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));
     //
     $deptcode=@$_REQUEST['deptcode'];
     //echo "$deptcode";
     echo "<select name='corr_acct' id='corr_acct' class='txt'>";
     echo "<option selected='selected' value=''>---</option>";

     $c_sql=@mysqli_query($con, "select distinct acctcode from account_allocationtb where dept_acctcode='$deptcode'");
     while($c_rst=@mysqli_fetch_array($c_sql))
     {
          $acctcode=@$c_rst['acctcode'];
          $acct_name=get_account_name($acctcode) ;
          echo "<option value='$acctcode'> $acct_name <=> $acctcode</option>";
     }
     echo "</select>";

}// end action = level details


if($id=='dept_acctcode') // load accounting depts
{


     $role=@$_REQUEST['role'];
     //	echo 'ROLE  '. $role; exit;
     if ($role == 'Accountant')
     {

          echo "<p><strong>Department</strong></p><select name='dept_acctcode' id='dept_acctcode' tabindex='3' style='width:350px'>";
          echo "<option value='' selected='selected'>--</option>";

          $r=@mysqli_query($con, "select * from account_depttb order by dept_acctcode");
          $n=0;

          while($rl=@mysqli_fetch_array($r))
          {
               ++$n;
               $deptcode=@$rl['dept_acctcode'];$deptname=@$rl['deptname'];
               echo "<option value='$deptcode'>$deptname</option>";

          }

          echo "</select>";

     }


}// end load accounting depts


if($id=='postfeepay')
{
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));
     $grandamount=@$_REQUEST['grandamount'];
     $grand_total=@$_REQUEST['grand_total'];
     //compare grandamount and estimated grandtotal
     if($grand_total!=$grandamount)
     {
          echo"<script>alert('Cross check your distributions, it must be equal to the Grand Amount');</script>";
          exit();
     }


     $login_id=@$_SESSION['login_id'];
     //
     $programme=@$_REQUEST['programme'];
     $dept_acctcode=@$_REQUEST['prog_dept'];
     $acctcode=@$_REQUEST['corr_acct'];
     $session=@$_REQUEST['session'];
     $start_date=@$_REQUEST['start_date'];
     $end_date=@$_REQUEST['end_date'];


     //array form items
     $studno=$_REQUEST['studno'];
     $amount=$_REQUEST['amount'];
     $level=$_REQUEST['level'];
     $total_level_amt=$_REQUEST['total_level_amt'];
     $i=0;
     foreach($studno as $studno_val)
     {
          if($studno_val!="")
          {
               // save into schoolfee_posting table
               @mysqli_query($con, "insert into schoolfee_postingtb set programme='$programme',dept_acctcode='$dept_acctcode',acctcode='$acctcode',session='$session',start_date='$start_date',end_date='$end_date',student_level='$level[$i]',no_of_student='$studno[$i]',level_amount='$total_level_amt[$i]',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");


               $brksql=@mysqli_query($con, "select * from schoolfee_breakdowntb where programme='$programme' and session='$session' and student_level='$level[$i]' ");
               while ($brkrst=@mysqli_fetch_array($brksql))
               { // save into transaction table
                    $amount=$brkrst['amount'];
                    $folio_code= $brkrst['folio_code'];

                    @mysqli_query($con, "insert into transtb set fileno='COE_ID',dept_acctcode='$dept_acctcode',acctcode='$acctcode', folio_code=$folio_code,transtype='Debit',receiptno='$programme',transdate=CURDATE(), entry_date=CURDATE(),entry_time=CURTIME(),amount= $amount*$studno_val, payee='$level[$i]',comment='$session',entry_by='$login_id'");

               }  // end while

          }	 //end if
          ++$i;
     }  //end foreach

     echo "<script>alert('Record saved successfully');</script>";




     // Display the postings to Schoolfee_Postingtb
     $sql="select * from schoolfee_postingtb where session='$session' and start_date='$start_date' and end_date='$end_date' and programme='$programme'";
     $sqlsum="select sum(level_amount) as sumamount from schoolfee_postingtb where session='$session' and start_date='$start_date' and end_date='$end_date' and programme='$programme'";
     //}

     $res_v=@mysqli_query($con, $sql);
     $res_sumamount=@mysqli_query($con, $sqlsum);
     $sumamount=@mysqli_fetch_array($res_sumamount);
     $sn=0;
     $tb="<center><table><tr><th colspan=11>Posting Payment Summary</th></tr><tr><th>Programme</th><th>Dept/Unit/Centre</th><th>Corr. Acct</th><th>Session</th><th>Start Date</th><th>End Date</th><th>Student Level</th><th> No of Student</th><th>Total Level Amount</th></tr>";
     while($rs_v=@mysqli_fetch_array($res_v))
     {

          $tb.="<tr><td>{$rs_v['programme']}</td><td>{$rs_v['dept_acctcode']}</td><td>{$rs_v['acctcode']}</td><td>{$rs_v['session']}</td><td>{$rs_v['start_date']}</td><td>{$rs_v['end_date']}</td><td>{$rs_v['student_level']}</td><td>{$rs_v['no_of_student']}</td><td>{$rs_v['level_amount']}</td></tr>";
     } //end of while
     $tb.="<tr><td colspan='8'><p align='right'>Total Amount Posted</p></td><td>".number_format($sumamount['sumamount'],2)."</td></tr>";

     $tb.="</table></center>";
     echo $tb;

}

//////////////////////////////////////End of Bursary Automation Management System (BAMS) ////////////////////////////////

if($id=='prorata_computation')
{
     $fileno = $_REQUEST['fileno'];
     $level = $bursary->get_any_value("level", "stafftb", "fileno", $fileno);
     $step = $bursary->get_any_value("step", "stafftb", "fileno", $fileno);
     $category = $bursary->get_any_value("category", "stafftb", "fileno", $fileno);
     $as_date = $_REQUEST['as_date'];
     $scale = $bursary->get_any_value("salary_scale", "stafftb", "fileno", $fileno);
     $date_vals = explode("-", $as_date);
     $year = $date_vals[0];	$month = $date_vals[1];	$day = $date_vals[2];
     $as_month = $bursary->get_any_value("month_name", "monthtb", "month_code", $month);
     $curr_date = date('Y-m-d');
     //$d1=new DateTime($as_date);		$d2=new DateTime($curr_date);	$d_dif = $d1->diff($d2);	$d_month=$d_dif->m;
     $d_month = $bursary->get_datediff("m", $as_date, $curr_date);
     if($d_month < 0){
          //month selected is either not valid or ahead of current date
          echo "<script>alert('You have selected a wrong date of assumption of duty. Please check entry!');</script>";
          exit;
     }
     //if($d_month == 0){
     //}else
     $next_full_date = '';	$temp_year = $year;
     if($d_month > 0){
          echo "<strong>SALARY ARREAS: </strong><br>";
          echo '<input type="hidden" value="arreas">
          <table width="95%" align="center" frame="hsides" rules="rows" border="1" cellpadding="5" cellspacing="0">';
          $month_array = array();
          $days_array = array();
          $amount_array = array();
          for ($i=1; $i <= $d_month; $i++){ //loop through the number os months fetched from the difference computed
               if($month > 12 && $i > 1) {
                    $month = 1;		//reset month to identify new year through december
                    $temp_year += 1;
               }
               $month_next=$bursary->get_any_value("month_name", "monthtb", "month_code", $month);  //get preceeding month name
               //$next_full_date=$temp_year."-".$month."-1";
               $days_in_month = $bursary->get_any_value("month_end", "monthtb", "month_code", $month);   //get number of days in a month from monthtb
               $total_days_in_month = $bursary->get_any_value("month_end", "monthtb", "month_code", $month);
               if($i==1 and $day != 1)
               $days_in_month=($bursary->get_any_value("month_end", "monthtb", "month_code", $month) - $day) + 1; //get days for incomplete month
               if($bursary->is_leapyear($temp_year, $month) && $month_next=="February"){
                    $days_in_month += 1;	//do operation for leap year => February is 29
                    $total_days_in_month += 1;		//total days in the month. Comment this line if days is set to constant
               }
               $first_level_entry = $bursary->get_total_monthly_pay($level, $step, $scale, $category);
               $second_level_entry=explode("~~~", $first_level_entry);			//split first return into TOTAL SUM and A COMPLEX STRING
               $third_level_entry=explode("^^^", $second_level_entry[1]);		//re-split the COMPLEX STRING into array of amount and folio code
               $basic_salary = 0;	$gross_salary = 0;
               //echo "<p>".$month_next." (".$days_in_month." days) - &#8358;".$monthly_pay.", <br>";
               echo '<tr align="left" valign="top"><td>';
               if($i==1) echo '<strong><font color="#003399">MONTH/YEAR</font></strong><br>';
               echo $month_next.", ".$temp_year."</td><td>";
               if($i==1) echo "<strong><font color='#003399'>DAYS</font></strong><br>";
               echo $days_in_month."</td>";

               //start drawing table for allowances column ============================================================
               echo "<td>";
               if($i==1) echo "<strong><font color='#003399'>ALLOWANCE</font></strong><br>";
               echo "<table width='100%' rules='rows' border='1' frame='void' style='font-size:10px'>";
               //fetching basic allowances from salary scale table
               for($j = 0; $j < (count($third_level_entry) - 1); $j++){		//loop through the array formed at third_level_entry string array
                    $fouth_level_entry=explode("***", $third_level_entry[$j]);	//split third_level_entry into amount and corresponding folio code
                    $pr_amount=$fouth_level_entry[0]; $pr_folio=$fouth_level_entry[1];
                    if($pr_folio == "001") $basic_salary = $pr_amount;
                    $gross_salary += $pr_amount;
                    if($i==1 and $day != 1)			//COMPUTE AMOUNT WHERE MONTH DAYS IS NOT COMPLETE
                    //$pr_amount = ($pr_amount / 30.0) * $days_in_month;
                    $pr_amount = ($pr_amount / $total_days_in_month) * $days_in_month;
                    echo "<tr><td>".get_folio_name($pr_folio)."</td><td>".$pr_folio."</td><td>&#8358;".number_format($pr_amount, 2)."</td></tr>";
               }
               $j=0;
               //echo "<tr><td><!--1--></td><td><!--2--></td><td><!--3--></td></tr>";
               //fetching other allowances from allowances table
               $xstaff_cat = $bursary->get_any_value("category", "stafftb", "fileno", $fileno) ;	//get required parameters
               $xlevel = "Level ".$bursary->get_any_value("level", "stafftb", "fileno", $fileno) ;
               $xscale = $bursary->get_any_value("salary_scale", "stafftb", "fileno", $fileno) ;
               $xsex = $bursary->get_any_value("sex", "stafftb", "fileno", $fileno) ;
               $xreligion = $bursary->get_any_value("religion", "stafftb", "fileno", $fileno) ;
               $xrank = $bursary->get_any_value("rank", "stafftb", "fileno", $fileno) ;
               $res_c= mysqli_query($con, "select * from allowancestb order by id");						//the query
               while($rs_c= mysqli_fetch_array($res_c))
               {
                    $al_id=$rs_c['id'];
                    $d_val=explode("***", $bursary->get_allowance_defined($al_id) );
                    $fcode=$rs_c['folio_code'];	$fval=$rs_c['field_value'];
                    $fname=get_folio_name($rs_c['folio_code']);
                    $def_for = $d_val[1];
                    $deff1 = explode("(", $d_val[1]); $def_f1=trim($deff1[0]); 	//category for which allowance is define for in allowancestb
                    $deff2 = explode(")", $deff1[1]); $def_f2=trim($deff2[0]); 	//get category of definition from return value

                    $def_amount = $d_val[4];
                    if($i==1 and $day != 1)
                    $def_amount = ($def_amount / $total_days_in_month) * $days_in_month;
                    if($def_f2==$xstaff_cat and ($def_f1 == $xlevel or $def_f1 == $xscale or $def_f1 == $xrank or $def_f1=="All Staff"))
                    echo "<tr><td>".$fname."</td><td>".$fcode."</td><td>&#8358;".number_format($def_amount, 2)."</td></tr>";
                    /*echo "<option value='$code***$al_id'>$code | $name | For:".$def_for." | Amount:".$def_amount."</option>";*/
               }
               echo "</table></td>";

               //start drawing table for deductions column =============================================================
               echo "<td>";
               if($i==1) echo "<strong><font color='#003399'>PREDEFINED DEDUCTIONS</font></strong><br>";
               echo "<table width='100%' rules='rows' border='1' frame='void' style='font-size:10px'>";
               $res_c= mysqli_query($con, "select * from salary_taxtb where scale_name='". mysqli_real_escape_string($con, $scale).
               "' and category='". mysqli_real_escape_string($con, $category)."' and level='". mysqli_real_escape_string($con, $level)
               ."' and step='". mysqli_real_escape_string($con, $step)."'");						//the tax query
               if( mysqli_num_rows($res_c) > 0){
                    if($rs_c= mysqli_fetch_array($res_c)){
                         $fcode=$rs_c['folio_code'];
                         $fname=get_folio_name($rs_c['folio_code']);
                         $def_amount=$rs_c['amount'];
                         if($i==1 and $day != 1) $def_amount = ($def_amount / $total_days_in_month) * $days_in_month;
                         echo "<tr><td>".$fname."</td><td>".$fcode."</td><td>&#8358;".number_format($def_amount, 2)."</td></tr>";
                    }
               }

               $res_c= mysqli_query($con, "select * from deductiontb order by id");						//the deductions query
               while($rs_c= mysqli_fetch_array($res_c))
               {
                    $al_id=$rs_c['id'];	$criteria=$rs_c['criteria'];
                    $d_val=explode("***", $bursary->get_deduction_defined($al_id) );
                    $fcode=$rs_c['folio_code'];	$fval=$rs_c['field_value'];
                    $fname=get_folio_name($rs_c['folio_code']);
                    $def_for = $d_val[1];
                    $deff1 = explode("(", $d_val[1]); $def_f1=trim($deff1[0]); 	//category for which allowance is define for in allowancestb
                    $deff2 = explode(")", $deff1[1]); $def_f2=trim($deff2[0]); 	//get category of definition from return value
                    if($criterial=="%Basic") $def_amount = ($basic_salary * $d_val[4])/100.0;
                    elseif($criterial=="%Gross") $def_amount = ($gross_salary * $d_val[4])/100.0;
                    elseif($criterial=="Fixed") $def_amount = $d_val[4];

                    if($i==1 and $day != 1)
                    $def_amount = ($def_amount / $total_days_in_month) * $days_in_month;
                    if($def_f1 == $xlevel or $def_f1 == $xscale or $def_f1 == $xrank or $def_f1 == $xsex or $def_f1 == $xreligion or $def_f1=="All Staff")
                    echo "<tr><td>".$fname."</td><td>".$fcode."</td><td>&#8358;".number_format($def_amount, 2)."</td></tr>";
                    /*echo "<option value='$code***$al_id'>$code | $name | For:".$def_for." | Amount:".$def_amount."</option>";*/
               }
               echo "</table></td>";
               echo "</tr>";
               $month_array[$i]=$month; $days_array[$i]=$days_in_month;
               /*if($month < 12) $month++;
               elseif($month == 12) $month = 1;*/
               $month++;
          }
          echo "</table>";
     }elseif($d_month == 0){
          echo "<span style='color:maroon;'><strong>SALARY PRORATA:</strong></span>";
          if($day != 1)
          $days_in_month=($bursary->get_any_value("month_end", "monthtb", "month_code", $month) - $day) + 1;
          $month_next=$bursary->get_any_value("month_name", "monthtb", "month_code", $month);//." (".$days_in_month." days) ";

          if($bursary->is_leapyear($year, $month) && $month_next=="February")
          $days_in_month += 1;	//do operation for leap year => February is 29
          $first_level_entry = $bursary->get_total_monthly_pay($level, $step, $scale, $category);
          $second_level_entry=explode("~~~", $first_level_entry);			//split first return into TOTAL SUM and A COMPLEX STRING
          $third_level_entry=explode("^^^", $second_level_entry[1]);		//re-split the COMPLEX STRING into array of amount and folio code
          echo '<input type="hidden" value="prorata">
          <table width="95%" align="center" frame="hsides" rules="rows" border="1" cellpadding="5" cellspacing="0">';
          echo '<tr align="left" valign="top"><td><strong>';
          echo $month_next.", ".$temp_year."</strong></td><td><strong>".$days_in_month." days</strong></td><td><table width='100%' rules='rows' border='1' frame='void'>";
          for($j = 0; $j < (count($third_level_entry) - 1); $j++){		//loop through the array formed at third_level_entry string array
               $fouth_level_entry=explode("***", $third_level_entry[$j]);	//split third_level_entry into amount and corresponding folio code
               $pr_amount=$fouth_level_entry[0]; $pr_folio=$fouth_level_entry[1];
               if($day != 1)
               $pr_amount = ($pr_amount / 30.0) * $days_in_month;
               echo "<tr><td>".get_folio_name($pr_folio)."</td><td>".$pr_folio."</td><td>&#8358;".number_format($pr_amount, 2)."</td></tr>";
          }
          echo "</table></td></tr>";

          $month_array[0]=$month; $days_array[0]=$days_in_month;
          echo "</table>";
     }
}

if($id=="get_staff_data"){
     $fileno=$_REQUEST['fileno'];
     $level=$bursary->get_any_value("level", "stafftb", "fileno", $fileno);
     $scale=$bursary->get_any_value("salary_scale", "stafftb", "fileno", $fileno);
     $step=$bursary->get_any_value("step", "stafftb", "fileno", $fileno);
     $category=$bursary->get_any_value("category", "stafftb", "fileno", $fileno);
     echo '<table width="98%" align="center" cellpadding="3" cellspacing="0" border="0">
     <tr><td nowrap width="25%"><strong>Level:</strong> </td><td width="25%">
     <input type="hidden" id="staff_level" name="staff_level" vale="'.$level.'">'.$level.'</td>
     <td nowrap width="25%"><strong>Step:</strong> </td><td width="25%">
     <input type="hidden" id="staff_step" name="staff_step" vale="'.$step.'">'.$step.'</td></tr>
     <tr><td nowrap width="25%"><strong>Category:</strong> </td><td width="25%">
     <input type="hidden" id="staff_category" name="staff_category" vale="'.$category.'">'.$category.'</td>
     <td nowrap width="25%"><strong>Salary Scale:</strong> </td><td width="25%">
     <input type="hidden" id="staff_scale" name="staff_scale" vale="'.$scale.'">'.$scale.'</td></tr>
     </table>';
}

if($id=='prorata_section')
{
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     $fileno=@$_REQUEST['fileno'];
     $days=@$_REQUEST['days'];
     $amount=@$_REQUEST['amount'];
     $month=$_REQUEST['month'];
     $year=$_REQUEST['year'];
     $comment=$_REQUEST['comment'];
     $end_date=$_REQUEST['end_date'];
     $payment_type="Allowance";
     //$transdate=prepare_transdate($month,$year); //the month_end will be the day e.g 31 for Jan

     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save' || $action=='compute')
     {
          //=========================================================================================================================================
          echo "<script>alert('You have selected a wrong date of assumption of duty. Please check entry!');</script>";
          exit;
          $fileno = $_REQUEST['fileno'];
          $level = $bursary->get_any_value("level", "stafftb", "fileno", $fileno);
          $step = $bursary->get_any_value("step", "stafftb", "fileno", $fileno);
          $category = $bursary->get_any_value("category", "stafftb", "fileno", $fileno);
          $as_date = $_REQUEST['as_date'];
          $scale = $bursary->get_any_value("salary_scale", "stafftb", "fileno", $fileno);
          $date_vals = explode("-", $as_date);
          $year = $date_vals[0];	$month = $date_vals[1];	$day = $date_vals[2];
          $as_month = $bursary->get_any_value("month_name", "monthtb", "month_code", $month);
          $curr_date = date('Y-m-d');
          //$d1=new DateTime($as_date);		$d2=new DateTime($curr_date);	$d_dif = $d1->diff($d2);	$d_month=$d_dif->m;
          $d_month = $bursary->get_datediff("m", $as_date, $curr_date);
          if($d_month < 0){
               //month selected is either not valid or ahead of current date
               echo "<script>alert('You have selected a wrong date of assumption of duty. Please check entry!');</script>";
               exit;
          }
          //if($d_month == 0){
          //}else
          $next_full_date = '';	$temp_year = $year;	$commit=TRUE;
          $bursary->begin();
          if($d_month > 0){
               //=====>THIS SECTOIN COMPUTES SALARY PRORATA AREAS====================================================>
               echo "<strong>SALARY PRORATION AS ARREAS: </strong><br>";
               echo '<table width="95%" align="center" frame="hsides" rules="rows" border="1" cellpadding="5" cellspacing="0">';
               $month_array = array();	$days_array = array();	$amount_array = array();	$error = '';
               for ($i=1; $i <= $d_month; $i++){ //loop through the number os months fetched from the difference computed
                    if($month > 12) {
                         $month = 1;		//reset month to identify new year through december
                         $temp_year += 1;
                    }
                    $month_next=$bursary->get_any_value("month_name", "monthtb", "month_code", $month);  //get preceeding month name
                    //$next_full_date=$temp_year."-".$month."-1";
                    $days_in_month = $bursary->get_any_value("month_end", "monthtb", "month_code", $month);   //get number of days in a month from monthtb
                    if($i==1 and $day != 1)
                    $days_in_month=($bursary->get_any_value("month_end", "monthtb", "month_code", $month) - $day) + 1; //get days for incomplete month
                    if($bursary->is_leapyear($temp_year, $month) && $month_next=="February")
                    $days_in_month += 1;	//do operation for leap year => February is 29
                    $first_level_entry = $bursary->get_total_monthly_pay($level, $step, $scale, $category);
                    $second_level_entry=explode("~~~", $first_level_entry);			//split first return into TOTAL SUM and A COMPLEX STRING
                    $third_level_entry=explode("^^^", $second_level_entry[1]);		//re-split the COMPLEX STRING into array of amount and folio code
                    //echo "<p>".$month_next." (".$days_in_month." days) - &#8358;".$monthly_pay.", <br>";
                    echo '<tr align="left" valign="top"><td><strong>';
                    echo $month_next.", ".$temp_year."</strong></td><td><strong>".$days_in_month." days</strong></td><td><table width='100%' rules='rows' border='1' frame='void'>";
                    $amount_array[$i] = 0;
                    for($j = 0; $j < (count($third_level_entry) - 1); $j++){		//loop through the array formed at third_level_entry string array
                         $fouth_level_entry=explode("***", $third_level_entry[$j]);	//split third_level_entry into amount and corresponding folio code
                         $pr_amount=$fouth_level_entry[0]; $pr_folio=$fouth_level_entry[1];
                         if($i==1 and $day != 1)
                         $pr_amount = ($pr_amount / 30.0) * $days_in_month;
                         echo "<tr><td>".get_folio_name($pr_folio)."</td><td>".$pr_folio."</td><td>&#8358;".number_format($pr_amount, 2)."</td></tr>";
                         $amount_array[$i] += $pr_amount;

                         //.............................................................................
                         if($action=='save'){
                              $res_c=@mysqli_query($con, "select * from prorate_arrearstb where fileno='". mysqli_real_escape_string($con, $fileno).
                              "' and month='$month' and year='$year'");
                              if(@mysqli_num_rows($res_c) <= 0)
                              {
                                   $pro_qry = "insert into prorate_arrearstb set fileno='". mysqli_real_escape_string($con, $fileno)."', folio_code='$pr_folio', ".
                                   " year='$temp_year', month='$month', no_of_days='$days_in_month', amount='$pr_amount', remark='$comment', ".
                                   "transdate=CURDATE(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'";
                                   if(! mysqli_query($con, $pro_qry)){
                                        $commit = FALSE;
                                        $error .= "Error inserting prorata as areas!<br>";
                                        ////break;
                                   }
                              }
                              //.............................................................................
                              //save into otherpaymentsourcetb
                              $start_date=$temp_year."-".sprintf("%02d",$month)."-"."01";
                              $end_date=@prepare_transdate($month, $temp_year);

                              $res_c=@mysqli_query($con, "select * from otherpayment_sourcetb where fileno='". mysqli_real_escape_string($con, $fileno).
                              "' and folio_code='$pr_folio' and start_date='$start_date' and end_date='$end_date'");
                              if(@mysqli_num_rows($res_c) <= 0)
                              {
                                   $pro_qry="insert into otherpayment_sourcetb set fileno='". mysqli_real_escape_string($con, $fileno)."', folio_code='$pr_folio', ".
                                   "amount='$pr_amount', start_date='$start_date', end_date='$end_date', payment_type='Allowance', ".
                                   "entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id', is_prorata='Yes', prorata_paid_status='Not Paid'";
                                   ////ADJUST TABLE STRUCTURE ADD: is_prorata[enum-'Yes', 'No'] AND prorata_paid_status[enum-'Not Paid'(default), 'Paid']
                                   if(! mysqli_query($con, $pro_qry)){
                                        $commit = FALSE;
                                        $error .= "Error update allowances record for selected staff!<br>";
                                        break;
                                   }
                              } //update record
                         }//end if($action=='save')
                    }  // END FOR LOOP [INNER]
                    echo "</table></td></tr>";
                    $month_array[$i]=$month; $days_array[$i]=$days_in_month;
                    /*if($month < 12) $month++;
                    elseif($month == 12) $month = 1;*/
                    $month++;
               }	// END FOR LOOP [OUTER]
               echo "</table>";
               //END OF PRORATA AS ARREAS COMPUTATION
          }elseif($d_month == 0){
               //=====>THIS SECTOIN COMPUTES SALARY PRORATA PAYABLE IN THE CURRENT MONTH===========================>
               echo "<span style='color:maroon;'><strong>CURRENT MONTH PRORATION:</strong></span>";
               if($day != 1)
               $days_in_month=($bursary->get_any_value("month_end", "monthtb", "month_code", $month) - $day) + 1;
               $month_next=$bursary->get_any_value("month_name", "monthtb", "month_code", $month);//." (".$days_in_month." days) ";

               if($bursary->is_leapyear($year, $month) && $month_next=="February")
               $days_in_month += 1;	//do operation for leap year => February is 29
               $first_level_entry = $bursary->get_total_monthly_pay($level, $step, $scale, $category);
               $second_level_entry=explode("~~~", $first_level_entry);			//split first return into TOTAL SUM and A COMPLEX STRING
               $third_level_entry=explode("^^^", $second_level_entry[1]);		//re-split the COMPLEX STRING into array of amount and folio code
               echo '<table width="95%" align="center" frame="hsides" rules="rows" border="1" cellpadding="5" cellspacing="0">';
               echo '<tr align="left" valign="top"><td><strong>';
               echo $month_next.", ".$temp_year."</strong></td><td><strong>".$days_in_month." days</strong></td><td><table width='100%' rules='rows' border='1' frame='void'>";
               for($j = 0; $j < (count($third_level_entry) - 1); $j++){		//loop through the array formed at third_level_entry string array
                    $fouth_level_entry=explode("***", $third_level_entry[$j]);	//split third_level_entry into amount and corresponding folio code
                    $pr_amount=$fouth_level_entry[0]; $pr_folio=$fouth_level_entry[1];
                    if($day != 1)
                    $pr_amount = ($pr_amount / 30.0) * $days_in_month;
                    echo "<tr><td>".get_folio_name($pr_folio)."</td><td>".$pr_folio."</td><td>&#8358;".number_format($pr_amount, 2)."</td></tr>";
               }
               echo "</table></td></tr>";

               $month_array[0]=$month; $days_array[0]=$days_in_month;
               echo "</table>";
          }
          if($action=='save'){
               if($commit) {
                    $bursary->commit();
                    echo "<script>alert('Record saved successfully');</script>";
               }else{
                    $bursary->rollback();
                    echo "<script>alert('Operation failed!');</script>".$error;
               }
          }
          $sql="select * from prorate_arrearstb where fileno='". mysqli_real_escape_string($con, $fileno)."'";
          //====================================================================================================================================
          /*	     $folio_code=get_folio_code("basic");

          $res_c=@mysqli_query($con, "select * from prorate_arrearstb where fileno='$fileno' and month='$month' and year='$year'");
          if(@mysqli_num_rows($res_c)<=0)
          {
          @mysqli_query($con, "insert into prorate_arrearstb set fileno='$fileno', folio_code='$folio_code', year='$year', month='$month', no_of_days='$days', amount='$amount', remark='$comment', transdate=CURDATE(), entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
     }

     //save into otherpaymentsourcetb
     $start_date=$year."-".sprintf("%02d",$month)."-"."01";
     $end_date=@prepare_transdate($month,$year);

     $res_c=@mysqli_query($con, "select * from otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio_code' and start_date='$start_date' and end_date='$end_date'");
     if(@mysqli_num_rows($res_c)<=0)
     {
     @mysqli_query($con, "insert into otherpayment_sourcetb set fileno='$fileno',folio_code='$folio_code',amount='$amount',start_date='$start_date',end_date='$end_date',payment_type='$payment_type',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
     echo "<script>alert('Record saved successfully');</script>";
} //update record


$sql="select * from prorate_arrearstb where fileno='$fileno'";
*/
}

if($action=='delete')
{
     $res_d=@mysqli_query($con, "select * from prorate_arrearstb where id='$r_id'");
     $rs_d=@mysqli_fetch_array($res_d);
     $month=$rs_d['month']; $year=$rs_d['year'];
     $start_date=$year."-".sprintf("%02d",$month)."-"."01";
     $end_date=@prepare_transdate($month,$year);
     $fileno=$rs_d['fileno'];
     $folio_code=$rs_d['folio_code'];

     @mysqli_query($con, "delete from otherpayment_sourcetb where fileno='$fileno' and folio_code='$folio_code' and start_date='$start_date' and end_date='$end_date'");
     @mysqli_query($con, "delete from prorate_arrearstb where id='$r_id'");
     echo "<script>alert('Record deleted successfully');</script>";

     $sql="select * from prorate_arrearstb where fileno='$fileno'";
}

if($action=='search')
{
     $sql="select * from prorate_arrearstb where fileno='$fileno'";

}//end of search

if($action=='view')
{
     $sql="select * from prorate_arrearstb order by fileno";
} //end of view

/////////////////////view section ////////////////////
if($action!='compute'){
     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' width='100%'><tr><th>S/N</th><th>FILE NO</th><th>FULLNAME</th><th>NO OF DAYS</th><th>AMOUNT</th><th>MONTH</th><th>YEAR</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v[fileno]}</td><td>".@get_staff_name($rs_v[fileno])."</td><td>{$rs_v['no_of_days']}</td><td>".@number_format($rs_v['amount'],2)."</td><td>".@get_month_name($rs_v['month'])."</td><td>".$rs_v[year]."</td><td><a href=\"javascript:swapcontent('prorata_section','delete','$id2');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";
}

}


if($id=='salary_computation_section')
{

     //include "function_b.php";
     //echo "Hereeeeeeeee"; exit;
     $mydata=@$_REQUEST['mydata'];
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];
     $j=@json_decode(stripslashes($mydata)); //encode the json data
     //echo "Month :$j->month<br>Year:$j->year<br>Type:$j->staff<br>File No:$j->fileno";
     //echo $fileno;
     $sql="select * from stafftb where status='Active' and fileno='$j->fileno'";

     $r=@mysqli_query($con, $sql);
     $trow=@mysqli_num_rows($r);

     if($trow>0)
     {
          $total_allowance=0; $total_ded=0; $netpay=0;

          while($rs=@mysqli_fetch_array($r))
          {
               $fileno=@$rs['fileno'];
               $db->sql("select * from stafftb where fileno='$fileno'");

               if(get_magic_quotes_gpc()){ $t= @json_decode(stripslashes($db->getResult()));$s=@json_decode(stripslashes($t->data)); }
               else{ $t= @json_decode($db->getResult());  $s=@json_decode($t->data);}
               $scalename=@get_current_scalename();
               $fullname=@get_staff_name($fileno) ;
               $staffstatus=@get_staff_status($s->level);
               $department=@get_dept_name($s->dept_code);
               $transdate=@prepare_transdate($j->month,$j->year) ;

               ///********** look into salary scale tb
               $r_salary_scale=@mysqli_query($con, "select folio_code,amount from salary_scaletb where level='$s->level' and step='$s->step' and category='$s->category' and scale_name='$scalename'");
               while($rs_scale=@mysqli_fetch_array($r_salary_scale))
               {
                    $code=@$rs_scale['folio_code'];	$amount=@$rs_scale['amount'];
                    $paymenttype="Allowance";
                    //Check for excption from deduction_exctiontb
                    if(!excepted($fileno,$code,$j->month,$j->year))
                    {
                         //check Prorata tb
                         $total_allowance += $amount;

                    }// end of if not excepted
               }//end of loop for folio code in salary scale


               //************* General Deductions ********************//
               //************* check for distinct folio code in  deductiontb i.e deduction definition

               $rs_ded=@mysqli_query($con, "select distinct folio_code from deductiontb");
               if(@mysqli_num_rows($rs_ded)>0)
               {
                    while($r_ded=@mysqli_fetch_array($rs_ded))
                    {
                         $code=@$r_ded['folio_code'];
                         $paymenttype='Deduction';
                         $rs_four=@mysqli_query($con, "select distinct category,staff_status,sex,religion from deductiontb");
                         if(@mysqli_num_rows($rs_four)>0)
                         {
                              while($r_four=@mysqli_fetch_array($rs_four))
                              {
                                   $cat=@$r_four['category'];$tstatus=@$r_four['staff_status'];$tsex=@$r_four['sex'];
                                   $trel=@$r_four['religion'];
                                   if(strtolower($tstatus) !="all")$tstatus='value';else $tstatus='all';
                                   if(strtolower($tsex) !="all")$tsex='value';else $tsex='all';
                                   if(strtolower($trel) !="all")$trel='value';else $trel='all';
                                   $definition=@explode("***",get_deduction_defintion($code,$cat,$s->category,$s->sex,$s->religion,$s->level,$tstatus,$tsex,$trel));


                                   //echo "$code : $staffstatus: ".get_deduction_defintion($code,$cat,$s->category,$s->sex,$s->religion,$s->level,$tstatus,$tsex,$trel)."<br>";
                                   if($definition[2]>0)
                                   {
                                        $criteria=$definition[0];
                                        $value=$definition[1];
                                        if(strtolower($criteria)=='%basic')
                                        {
                                             $basic_code=@get_folio_code('basic salary');
                                             $basic_amount=@get_folio_code_amount($s->level,$s->step,$s->category,$basic_code);
                                             $amount=$basic_amount*$value/100;
                                             $total_ded+=$amount;


                                        }// end of %basic as criteria
                                        elseif(strtolower($criteria)=='%gross')
                                        {
                                             $gross_amount=@get_gross_total($s->level,$s->step,$s->category);
                                             $amount=$gross_amount*$value/100;
                                             $total_ded+=$amount;
                                        }// end of %gross  as criteria
                                        elseif(strtolower($criteria)=='fixed')
                                        {
                                             $amount=$value;
                                             $total_ded+=$amount;
                                        }// end of fixed  as criteria

                                   }//end of record found in deduction definition tb

                              }// end of loop for distinct category,staff_status,sex,religion from deductiontb
                         }// end of record found in distinct category,staff_status,sex,religion from deductiontb

                    }// end of loop for distinct folio_code from deductiontb
               }// end of record found in distinct folio_code from deductiontb
          }// end of while for list of record of staff


          ///compute netpay and send it back
          $netpay=$total_allowance - $total_ded;
          echo ($netpay/30) * $j->days; //netpay per day

     }// end of if($trow>0)
     else
     {
          echo "<script>alert('No record found in the database for the selected criteria')";
          exit;
     }// end of no record of staff found


}//end of salary_computation_section


/////////////////////////// HR Modules Begin //////////////////////

if($id=='load_staff_name')
{

     $fileno=$_REQUEST['fileno'];
     $name=@get_staff_name($fileno);
     echo $name;
}

if($id=='disciplinary_section')

{
     //$j=json_decode(stripslashes(@$_REQUEST['mydata']));

     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     $fileno=@$_REQUEST['fileno'];
     $disc_date=@$_REQUEST['disc_date'];
     $disc_type=@$_REQUEST['disc_type'];
     $disc_ref_no=$_REQUEST['disc_ref_no'];
     $description=$_REQUEST['description'];
     $reply=$_REQUEST['reply'];
     $reply_date=$_REQUEST['reply_date'];
     $remark=$_REQUEST['remark'];

     if($action=='save')
     {
          $res_c=@mysqli_query($con, "select * from hr_disciplinarytb where fileno='$fileno' and disc_date='$disc_date' and disc_type='$disc_type'");
          if(@mysqli_num_rows($res_c)<=0)
          {
               @mysqli_query($con, "insert into hr_disciplinarytb set fileno='$fileno',disc_date='$disc_date',disc_type='$disc_type', disc_ref_no='$disc_ref_no',description='$description',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               echo "<script>alert('Record saved successfully');</script>";
               $sql="select * from hr_disciplinarytb where fileno='$fileno'";
          }

     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_disciplinarytb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          @mysqli_query($con, "delete from hr_disciplinarytb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="select * from hr_disciplinarytb where fileno='$fileno'";
     }


     if($action=='view')
     {
          $sql="select * from hr_disciplinarytb where fileno='$fileno'order by fileno,disc_date desc";

     } //end of view

     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>FILE NO</th><th>DATE ISSUED</th><th> TYPE</th><th>REF. NUMBER</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $disc_ref_no=@$rs_v['disc_ref_no'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>{$rs_v['disc_date']}</td><td>{$rs_v['disc_type']}</td><td>{$rs_v['disc_ref_no']}</td><td><a href=\"javascript:swapcontent('disciplinary_section','delete','$id2');\">DELETE</a> | <a href=\"javascript:swapcontent('disciplinary_display','$disc_ref_no');\">EDIT</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}
if($id=='grievance_section')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     //$code=$j->code;
     //$name=@mysqli_real_escape_string($con, $j->name);//$status=$j->status;
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];
     //echo "COde: $code Name: $name Status:$status Action: $action  R_ID: $r_id";
     if($action=='save')
     {
          $res_c=@mysqli_query($con, "select * from hr_grievancetb where fileno='$j->fileno' and grieve_type='$j->grieve_type' and grieve_date='$j->grieve_date'");
          if(@mysqli_num_rows($res_c)<=0)
          {  @mysqli_query($con, "insert into hr_grievancetb set fileno='$j->fileno',grieve_type='$j->grieve_type',grieve_date='$j->grieve_date', issues='$j->issues',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
               echo "<script>alert('Record saved successfully');</script>";
               $sql="select * from hr_grievancetb where fileno='$j->fileno'";
          }

     }


     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_grievancetb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=@$rs_d['fileno'];
          @mysqli_query($con, "delete from hr_grievancetb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="select * from hr_grievancetb where fileno='$fileno'";
     }

     if($action=='search')
     {
          $sql="select * from hr_grievancetb where fileno='$j->fileno'order by fileno,grieve_date desc";

     }

     if($action=='edit')
     {
          $res_c=@mysqli_query($con, "select * from hr_grievancetb where id='$r_id'");
          if(@mysqli_num_rows($res_c)>=1)
          {
               echo "Hurrrayfffffffff";
          }

     } //end of view

     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>FILE NO</th><th>GRIEVE TYPE</th><th>GRIEVE DATE</th><th>REACTION</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>{$rs_v['grieve_type']}</td><td>{$rs_v['grieve_date']}</td><td>{$rs_v['reaction']}</td><td><a href=\"javascript:swapcontent('grievance_section','delete','$id2');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}

if($id=='holiday_section')
{

     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     if($action=='save')
     {
          $res_c=@mysqli_query($con, "select * from hr_holidaytb where month='$j->month' and day='$j->day'");
          if(@mysqli_num_rows($res_c)<=0)
          {   mysqli_query($con, "insert into hr_holidaytb set month='$j->month',day='$j->day',description='$j->description',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
               echo "<script>alert('Record saved successfully');</script>";
               $sql="select * from hr_holidaytb";
          }

     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_holidaytb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          @mysqli_query($con, "delete from hr_holidaytb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="select * from hr_holidaytb";
     }

     if($action=='view_all')
     {
          $sql="select * from hr_holidaytb";
     }
     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>MONTH</th><th>DAY</th><th> DESCRIPTION</th><th>DELETE</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['month']}</td><td>{$rs_v['day']}</td><td>{$rs_v['description']}</td><td><a href=\"javascript:swapcontent('holiday_section','delete','$id2');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}


if($id=='position_section')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     if($action=='save')
     {
          $res_c=@mysqli_query($con, "select * from hr_positiontb where position='$j->position'");
          if(@mysqli_num_rows($res_c)<=0)
          {   mysqli_query($con, "insert into hr_positiontb set position='$j->position',category='$j->category',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
               echo "<script>alert('Record saved successfully');</script>";
               $sql="select * from hr_positiontb";
          }

     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_positiontb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          @mysqli_query($con, "delete from hr_positiontb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="select * from hr_positiontb";
     }

     if($action=='view_all')
     {
          $sql="select * from hr_positiontb";
     }
     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>POSITION</th><th>CATEGORY</th><th>DELETE</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['position']}</td><td>{$rs_v['category']}</td><td><a href=\"javascript:swapcontent('position_section','delete','$id2');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}
if($id=='leave_section')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     if($action=='save')
     {
          $res_c=@mysqli_query($con, "select * from hr_leavetb where leave_type='$j->leave_type'");
          if(@mysqli_num_rows($res_c)<=0)
          {   mysqli_query($con, "insert into hr_leavetb set leave_type='$j->leave_type',no_of_days='$j->no_of_days', category='$j->category',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
               echo "<script>alert('Record saved successfully');</script>";
               $sql="select * from hr_leavetb";
          }

     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_leavetb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          @mysqli_query($con, "delete from hr_leavetb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="select * from hr_leavetb";
     }

     if($action=='view_all')
     {
          $sql="select * from hr_leavetb";
     }
     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>LEAVE TYPE</th><th>NO. OF DAYS</th><th>CATEGORY</th><th>DELETE</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['leave_type']}</td><td>{$rs_v['no_of_days']}</td><td>{$rs_v['category']}</td><td><a href=\"javascript:swapcontent('leave_section','delete','$id2');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}
if($id=='manpowerbudget_section')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     if($action=='save')
     {
          $res_c=@mysqli_query($con, "select * from hr_manpowerbudgettb where dept_code='$j->dept_code' and unit_code='$j->unit' and position='$j->position'");
          if(@mysqli_num_rows($res_c)<=0)
          {   mysqli_query($con, "insert into hr_manpowerbudgettb set dept_code='$j->dept_code',unit_code='$j->unit', position='$j->position',capacity='$j->capacity',no_available='$j->no_available',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
               echo "<script>alert('Record saved successfully');</script>";
               $sql="select * from hr_manpowerbudgettb";
          }
     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_manpowerbudgettb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d);
          @mysqli_query($con, "delete from hr_manpowerbudgettb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="select * from hr_manpowerbudgettb";
     }

     if($action=='view_all')
     {
          $sql="select * from hr_manpowerbudgettb";
     }
     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>DEPARTMENT</th><th>UNIT</th><th>POSITION</th><th>CAPACITY</th><th>NO. AVAILABLE</th><th>DELETE</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               $tb.="<tr><td>$sn</td><td>{$rs_v['dept_code']}</td><td>{$rs_v['unit_code']}</td><td>{$rs_v['position']}</td><td>{$rs_v['capacity']}</td><td>{$rs_v['no_available']}</td><td><a href=\"javascript:swapcontent('manpowerbudget_section','delete','$id2');\">DELETE</a></td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}

if($id=='leave_app_section')
{
     $j=json_decode(stripslashes(@$_REQUEST['mydata']));
     $action=@$_REQUEST['action'];
     $r_id=@$_REQUEST['r_id'];  //for row id to be deleted/edited
     $login_id=@$_SESSION['login_id'];

     if($action=='save')
     {
          $res_c=@mysqli_query($con, "select * from hr_leave_apptb where fileno='$j->fileno' and leave_type='$j->leave_type' and app_start_date='$j->app_start_date'");
          if(@mysqli_num_rows($res_c)<=0)
          {   mysqli_query($con, "insert into hr_leave_apptb set fileno='$j->fileno',leave_type='$j->leave_type',app_date='$j->app_date', app_start_date='$j->app_start_date',app_end_date='$j->app_end_date',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
               echo "<script>alert('Record saved successfully');</script>";
               $sql="select * from hr_leave_apptb where fileno='$j->fileno' order by app_date desc";
          }

     }

     if($action=='delete')
     {
          $res_d=@mysqli_query($con, "select * from hr_leave_apptb where id='$r_id'");
          $rs_d=@mysqli_fetch_array($res_d); $fileno=$rs_d['fileno'];
          @mysqli_query($con, "delete from hr_leave_apptb where id='$r_id'");
          echo "<script>alert('Record deleted successfully');</script>";
          $sql="select * from hr_leave_apptb where fileno='fileno' order by app_date desc";
     }

     if($action=='view_all')
     {
          $sql="select * from hr_leave_apptb where fileno='$j->fileno' order by app_date desc";
     }
     /////////////////////view section ////////////////////

     $sn=0;
     $res_v= mysqli_query($con, $sql) or die( mysqli_error($con));
     $tb="<table><tr><th>S/N</th><th>FILE NO.</th><th>LEAVE TYPE</th><th>APPLICATION DATE</th><th>PROPOSED START DATE</th><th>PROPOSED END DATE</th><th>APPROVED START DATE</th><th>APPROVED END DATE</th><th>ACTION</th></tr>";
     if(@mysqli_num_rows($res_v)>=1)
     {
          while($rs_v=@mysqli_fetch_array($res_v))
          {
               ++$sn;
               $id2=@$rs_v['id'];
               if(@$rs_v['approval_start_date']=='0000-00-00')
               { $approved_s_date="Under process"; $approved_e_date="Under process"; $delete="<a href=\"javascript:swapcontent('leave_app_section','delete','$id2');\">DELETE</a>";}
               else
               { $approved_s_date=@date('d/m/Y',strtotime($rs_v['approval_start_date'])); $approved_e_date=@date('d/m/Y',strtotime($rs_v['approval_end_date'])); $delete="<font color='red'><b>Processed</b></font>"; }
               $tb.="<tr><td>$sn</td><td>{$rs_v['fileno']}</td><td>{$rs_v['leave_type']}</td><td>".@date('d/m/Y',strtotime($rs_v['app_date']))."</td><td>".@date('d/m/Y',strtotime($rs_v['app_start_date']))."</td><td>".@date('d/m/Y',strtotime($rs_v['app_end_date']))."</td><td>$approved_s_date</td><td>$approved_e_date</td><td>$delete</td></tr>";
          }//end of while
          $tb.="</table>";
          echo $tb;
     }
     else
     echo "<b>No record to display</b>";

}

if($id=='process_leave')
{
     $r_id=$_REQUEST['r_id'];
     $res_l=@mysqli_query($con, "select * from hr_leave_apptb l, stafftb s where l.fileno=s.fileno and l.id='$r_id'");
     $rs_l=@mysqli_fetch_array($res_l);
     $tb="<form name='frmpro' id='frmpro'><center><b>STAFF LEAVE PROCESSING</b></center><table>
     <tr><th>Staff Number</th><td>{$rs_l['fileno']}<input type='hidden' name='r_id' id='r_id' value='$r_id'/></td></tr>
     <tr><th>Fullname</th><td>".@get_staff_name($rs_l['fileno'])."</td></tr>
     <tr><th>Proposed Start Date</th><td>".@date('d/m/Y',strtotime($rs_l['app_start_date']))."</td></tr>
     <tr><th>Proposed End Date</th><td>".@date('d/m/Y',strtotime($rs_l['app_end_date']))."</td></tr>
     <tr><th>Approved Start Date</th><td><input type='text' name='approved_start_date' id='approved_start_date' class='txt'/></td></tr>
     <tr><th>Approved End Date</th><td><input type='text' name='approved_end_date' id='approved_end_date' class='txt'/></td></tr>
     <tr><th colspan='2'><input type='button' name='cmdpro' id='cmdpro' value='Save Approval' onclick=\"swapcontent('process_leave_approval');\" class='btn'/><input type='button' name='cmdpro1' id='cmdpro1' value='Close' onclick=\"javascript:TINY.box.hide();\" class='btn'/></th></tr>
     <table><div id='process_leave_approval'></div></form>";
     echo $tb;
}

if($id=='process_leave_approval')
{
     $r_id=$_REQUEST['r_id'];
     $approved_start_date=$_REQUEST['approved_start_date'];
     $approved_end_date=$_REQUEST['approved_end_date'];
     $login_id=@$_SESSION['login_id'];
     //update leave approval
     @mysqli_query($con, "update hr_leave_apptb set approval_start_date='$approved_start_date',approval_end_date='$approved_end_date',approval_date=CURDATE() where id='$r_id'");

     echo "<script>alert('Leave approved successfully');</script>";

}

if($id=='rollback_leave')
{
     $r_id=$_REQUEST['r_id'];
     //update leave approval
     @mysqli_query($con, "update hr_leave_apptb set approval_start_date='0000-00-00',approval_end_date='0000-00-00',approval_date='0000-00-00' where id='$r_id'");

     echo "<script>alert('Leave approval rollback successfully');</script>";

}
?>
