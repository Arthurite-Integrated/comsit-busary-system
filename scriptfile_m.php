<?php
 @session_start();
 @require_once('connect.php');
 @require_once('function.php');
 
 	@require_once('class/mysqli_class.php');
	$db = new Database();
	$db->connect();
	
 $id=@$_REQUEST['contentvar'];
 $contentvar=$_REQUEST['contentvar'];

	@require_once "myclass_m.php";
	@$bursary = new myclass_m();
	
	@$udept = $bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
 
if($id=="process_voucher_edit"){	
	$pvno=@$_REQUEST['pvno'];
	$pre_pvno=@$_REQUEST['pre_pvno'];
	$pid=@$_REQUEST['pid']; 	
	$dept_vou=@$_REQUEST['dept_vou']; 	
	$vou_unit=@$_REQUEST['voucher_unit']; 	
	$account=@$_REQUEST['account'];
	$payee_name=@$_REQUEST['payee_name']; 
	$fileno=@$_REQUEST['fileno']; 
	$payee_address=@$_REQUEST['payee_address']; 
	$payee_tin_number=@$_REQUEST['payee_tin_number'];
	$payee_sort_code=@mysqli_real_escape_string($con, @$_REQUEST['payee_sort_code']); 
	$amount_paid=@$_REQUEST['amount_paid']; 
	$desc=@mysqli_real_escape_string($con, @$_REQUEST['desc']);
	$pay_date=@date('Y-m-d',strtotime(@$_REQUEST['pay_date'])); 
	$r_vals=$_REQUEST['r_vals'];
	$taxamount=@$_REQUEST['tax_paid']; 
	$taxid=@$_REQUEST['tax_id'];
	$payee_acct_no=@$_REQUEST['payee_acct_no']; 
	$payee_bank_name=@$_REQUEST['payee_bank_name'];
	 $login_id = @$_SESSION['login_id'];
	$action=$_REQUEST['action'];
	 /*echo "<script>alert('$action'); exit;</script>";*/
	
	if($action == "pvupdate"){
		if($pre_pvno==''){
			//clear pre_pvno
			echo "<script>if(!confirm('This operation will clear assigned PV No. Do you still want to continue?')){ exit; }</script>";
			$sql="UPDATE vouchertb SET pre_pvno = '', pre_pvtype = '', pre_pvyear = '', pre_pvserial = '0' WHERE pvno='{$pvno}'";
		}else{
			//update pvno_paid and pre_pvno
			if($_REQUEST['pre_paid']=='Yes' || $_REQUEST['pre_paid']=='Approved'){
				$tr=mysqli_query($con, "SELECT * FROM transtb WHERE pvno='{$pre_pvno}' AND YEAR(transdate)='".date('Y', strtotime($_REQUEST['pre_date']))."'");
				if(mysqli_num_rows($tr) <= 0){
					echo "<script>alert('ERROR::PV No. must exist in the cashbook before you can use it to update PV to PAID. Check entry and try again!'); exit;</script>";
					exit;
				}
			}

			if($_REQUEST['pre_paid']=='Yes'){
				if($_REQUEST['pre_date'] == ''){
					echo "<script>alert('ERROR::Payment Date is required before you can update PV to PAID. Check entry and try again!'); exit;</script>";
					exit;
				}

				$sql="UPDATE vouchertb SET pre_pvno = '{$pre_pvno}', pvno_paid = '{$pre_pvno}', paid_action = 'Approved', paid_by = '{$login_id}', date_paid='{$_REQUEST['pre_date']}' WHERE pvno='{$pvno}'";
			}elseif($_REQUEST['pre_paid']=='Approved'){
				$sql="UPDATE vouchertb SET pre_pvno = '{$pre_pvno}', pvno_paid = '{$pre_pvno}' WHERE pvno='{$pvno}'";
			}else{
				$sql="UPDATE vouchertb SET pre_pvno = '{$pre_pvno}' WHERE pvno='{$pvno}'";
			}
		}
		if(mysqli_query($con, $sql)){
			echo "<script>alert('SUCCESS::PV No. updated successfully!'); exit;</script>";
		}else{
			echo "<script>alert('ERROR::PV No. updated failed, try again!'); exit;</script>";
		}
		exit;
	}
	if($action == "code"){
		if( $_REQUEST['folio'] == '' or $_REQUEST['amount'] == '' ){
			echo "<script>alert('Item Codes does correspond with amount entries cannot be empty!'); exit;</script>";
			exit;
		}
		$folio=explode(';', @$_REQUEST['folio']);
		//$bcode=@$_REQUEST['folio'];
		$amount=explode(';', @$_REQUEST['amount']);
		if( count($folio) != count($amount) ){
			echo "<script>alert('Item Codes does correspond with amount entries'); exit;</script>";
			exit;
		}
		$asum = 0;
		for($i=0; $i < count($amount); $i++) $asum += $amount[$i];
		
		/*if( $asum != $amount_paid ){
			echo "<script>alert('Amount entries does not match gross amount.'); exit;</script>";
			exit;
		}*/
		begin();
		$flag = false;
		if(count($folio) >= 1)
		{
			
			/*/*/
			$r2[]=@mysqli_query($con, "DELETE FROM voucher_folio_codetb WHERE pvno='{$pvno}'") or die( mysqli_error($con));
			//$r2[]=@mysqli_query($con, "UPDATE voucher_folio_codetb SET amount = $asum WHERE id=$pid") or die( mysqli_error($con)); 
			$amnt = 0;
			for($i=0; $i < count($folio); $i++)
			{
				if($folio[$i] != '') $r2[]=@mysqli_query($con, "INSERT INTO voucher_folio_codetb SET pvno='{$pvno}', folio_code='$folio[$i]', amount=$amount[$i]") or die( mysqli_error($con));
				$amnt = $amnt + $amount[$i]; 

				/*$sq=@mysqli_query($con, "SELECT vf.*, v.pvno_paid, v.paid_date FROM voucher_folio_codetb vf INNER JOIN vouchertb v ON vf.pvno=v.pvno WHERE v.pvno='{$pvno}'");
				while($t=mysqli_fetch_array($sq, 3)){
					$r2[]=@mysqli_query($con, "UPDATE transtb SET folio_code='{$vou_unit}', amount_paid='{$amnt}' where  pvno='{$pvno}'");
				}*/
			}
			////$r2[]=@mysqli_query($con, "UPDATE vouchertb SET amount_approved='$amnt', amount_paid='$amnt', checked_by='', checked_action='', controlled_by='', controlled_action='', authorized_by='', authorized_action='', paid_by='', paid_action='', final_approval_by='', final_approval='', audit_by='', audit_action='' where  pvno='$pvno'");

			/////, amount_approved='{$amnt}'
			$r2[]=@mysqli_query($con, "UPDATE vouchertb SET dept_vou='{$dept_vou}', dept_code='{$vou_unit}', amount_paid='{$amnt}' where  pvno='{$pvno}'");

			//reverse controlled_by action in voucher table
			////$r2[] = "delete from `budget_votebooktb` where voucher_pvno = '".$pvno."'";
			////$r2[] = "update voucher_extra_allocation_requesttb set commit_by = '', commit_status='Not Committed' where pvno='$pvno'";/* */


		}
		foreach($r2 as $r_val2){
			if($r_val2) $flag=true;
			else {
				$flag=false;
				break;
			}
		}
		if($flag){
			commit();
			echo "<script>alert('Operation Successful!'); parent.jQuery.colorbox.close();</script>";
			exit;
		}else{
			rollback();
			//print_r($folio); exit;
			//$fo=$folio[0]; $am=$amount[0];
			echo "<script>alert('Operation Failed!'); parent.jQuery.colorbox.close();</script>";
			exit;
		}
	}//end code update segment
	/*/*/

if($action == "voucher"){
	/////, amount_paid='$amount_paid'
	/////&& mysqli_query($con, "UPDATE voucher_folio_codetb SET  amount='$amount_paid' WHERE  pvno='$pvno'")

		if( mysqli_query($con, "UPDATE vouchertb SET voucher_date='$pay_date', dept_acctcode='$account', payee_name='$payee_name', payee_acct_no='$payee_acct_no', payee_bank_name='$payee_bank_name', payee_address='$payee_address', payee_tin_number='$payee_tin_number', payee_sort_code='$payee_sort_code', description='$desc', amount_approved='$amount_paid' WHERE  pvno='$pvno' AND id='$pid'") ){
			////, checked_by='', checked_action='', controlled_by='', controlled_action='', authorized_by='', authorized_action='', paid_by='', paid_action='', final_approval_by='', final_approval='', audit_by='', audit_action=''
			echo "<script>alert('Voucher Update Successful!');</script>";
			////$action = 'budget';
			//exit;
		}else{
			echo "<script>alert('Operation Failed!'); parent.jQuery.colorbox.close();</script>";
			exit;
		}
	}// end voucher update segment
	/*echo "<script>alert('Operation Failed! Please wait for page to reload.'); parent.jQuery.colorbox.close();</script>";*/


	if($action=='budget'){
		begin();
		//process reversal of budget commit
		$flag=false;
		//delete record of voucher from budget votebook
		$qry1 = "delete from `budget_votebooktb` where voucher_pvno = '".$pvno."'";
		if( mysqli_query($con, $qry1)) $flag=true;
		//reverse controlled_by action in voucher table
		$vsql = "update vouchertb set controlled_by = '', controlled_action='', controlled_remark='' where pvno='$pvno'";
		if( mysqli_query($con, $vsql) and $flag) $flag=true;
		else $flag=false;
		//update voucher_extra_allocation_requesttb here (reset commit status)
		$vesql = "update voucher_extra_allocation_requesttb set commit_by = '', commit_status='Not Committed' where pvno='$pvno'";
		if( mysqli_query($con, $vesql) and $flag) $flag = true;
			else $flag = false;
	
		if($flag) {
			commit();
			logs("$login_id","Expenditure Control","$login_id reversed budget committment in votebook. PVNo: $pvno");
			echo "<script>alert('Budget Commit Reversal successful! Please wait for page to reload.');  parent.jQuery.colorbox.close();</script>";
		}
		else{ 
			rollback();
			echo "<script>alert('Operation Failed! Please wait for page to reload.'); parent.jQuery.colorbox.close();</script>";
		}
	}// end of reverse_budget_comit_process
	
	if($action=='deduction'){
		begin();
		//print_r($_REQUEST["tax_id"][0]); exit;
		$flag=false;
		if( count($_REQUEST["tax_id"]) > 0 ){
			for($i=0; $i < count($_REQUEST["tax_id"]); $i++){
				$r2[]=@mysqli_query($con, "UPDATE voucher_folio_codetb SET amount = ".$_REQUEST["tax_paid"][$i]." WHERE id=".$_REQUEST["tax_id"][$i]); 
				$r2[]=@mysqli_query($con, "UPDATE vouchertb SET amount_approved=".$_REQUEST["tax_paid"][$i].", amount_paid=".$_REQUEST["tax_paid"][$i].", payee_bank_name='".$_REQUEST["tax_bankk"][$i]."', payee_acct_no='".$_REQUEST["tax_acctt"][$i]."' WHERE pvno='".$_REQUEST["tax_pvno"][$i]."'");
				$r2[]=@mysqli_query($con, "UPDATE voucher_taxtb SET amount = ".$_REQUEST["tax_paid"][$i]." WHERE pvno='".$_REQUEST["tax_pvno"][$i]."'");
			}
			foreach($r2 as $r_val2){
				if($r_val2) $flag=true;
				else {
					$flag=false;
					break;
				}
			}
			if($flag){
				commit();
				echo "<script>alert('Operation Successful!'); parent.jQuery.colorbox.close();</script>";
				exit;
			}else{
				rollback();
				echo "<script>alert('Operation Failed!'); parent.jQuery.colorbox.close();</script>";
				exit;
			}
		}
	}// end of update deduction process
}
	/* */
if($id=="grid"){
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
	} 
	
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
}

if($id=="loangrid"){
	/*if(isset($_REQUEST['category']) and $_REQUEST['category']!='')*/{
		$deptcode =  mysqli_real_escape_string($con, $_REQUEST['deptcode']);
		$fundcenter =  mysqli_real_escape_string($con, $_REQUEST['fundcenter']);
		$category =  mysqli_real_escape_string($con, $_REQUEST['category']);
//select * from foliotb where title like '%loan%' and fundcenter not in ('01') order by folio_code
		$sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and  title like '%loan%' and fundcenter not in ('01') ";
		
		$r= mysqli_query($con, $sql);
		
		///$r= mysqli_query($con, "select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='".$fundcenter."' and f.deptcode='".$deptcode."' order by f.category, f.title");
	} 
	
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
}

if($id=="paygrid"){
	if(isset($_REQUEST['category']) and $_REQUEST['category']!=''){
		$deptcode =  mysqli_real_escape_string($con, $_REQUEST['deptcode']);
		$fundcenter =  mysqli_real_escape_string($con, $_REQUEST['fundcenter']);
		$category =  mysqli_real_escape_string($con, $_REQUEST['category']);

		if($deptcode != '') $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.deptcode='".$deptcode."' ";
		else{
			if($category != '') $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='".$fundcenter."' and f.deptcode='".$deptcode."' order by f.category, f.title";
		else $sql="select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.category='$category'";
		}
		//$sql .= $sql.$where." order by f.category, f.title";
		
		$r= mysqli_query($con, $sql);
		
		///$r= mysqli_query($con, "select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active' and f.fundcenter='".$fundcenter."' and f.deptcode='".$deptcode."' order by f.category, f.title");
	} 
	
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
}

if($id=='staff_grid')
{
	$r= mysqli_query($con, "select s.fileno, concat(s.title, ' ', s.surname, ' ', s.first_name, ' ', s.other_name) as fullname, concat(d.dept_name, ', ', 'University of Ilorin') as dept, s.phone_no, s.acct_no, s.bank_name from stafftb s INNER JOIN departmenttb d on s.dept_code=d.dept_code where s.status='Active'"); 		$json_response=array();
		 while ($row =  mysqli_fetch_array($r)) { 
		 $row_array['fileno'] = $row['fileno'];
		 $row_array['fullname'] = $row['fullname'];
		 $row_array['dept'] = $row['dept'];
		 $row_array['acct_no'] = $row['acct_no'];
		 $row_array['bank_name'] = $row['bank_name'];
		 $row_array['phone_no'] = $row['phone_no'];
			 array_push($json_response,$row_array);
		 }//end of while
		 
		 echo json_encode($json_response);
		 exit;
} 


if($id=='foliocode_grid')
{
	$r= mysqli_query($con, "select f.*, c.folio_category as categoryF from foliotb f INNER JOIN folio_categorytb c on f.category=c.id where f.status='Active'"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
		 //where mm.memo_status='OUT' and mm.dept_unit='$udept'
		//$r= mysqli_query($con, "select * from memotb"); //ADD CONDITION TO FILTER 		
		$json_response=array();
		 while ($row =  mysqli_fetch_array($r)) { 
		 $row_array['folio_code'] = $row['folio_code'];
		 $row_array['title'] = $row['title'];
		 $row_array['category'] = $row['categoryF'];
		 $row_array['ncoa_code'] = $row['ncoa_code'];
		 $row_array['ncoa_title'] = $row['ncoa_title'];
		 //$row_array['categoryF'] = $row['categoryF'];
		 $row_array['exp'] = $row['exp'];
			 array_push($json_response,$row_array);
		 }//end of while
		 
		 echo json_encode($json_response);
		 exit;
} 

if($id=='load_fundsource')
{
	$r= mysqli_query($con, "select * from account_funds order by fund_code");
		$json_response=array();
		 while ($row =  mysqli_fetch_array($r)) { 
		 $row_array['fund_code'] = $row['fund_code'];
		 $row_array['fund_name'] = $row['fund_name'];
			 array_push($json_response,$row_array);
		 }//end of while
		 
		 echo json_encode($json_response);
} 

if($id=="load_category_j"){
	$res_c=@mysqli_query($con, "select distinct category from foliotb order by category"); 
	$json_response=array();
	  while($rs_c=@mysqli_fetch_array($res_c))
	   {
		   $dept_name= $bursary->get_any_value('folio_category', 'folio_categorytb', 'id', @$rs_c['category']);
		   $row_array['category'] = $rs_c['category'];
		   $row_array['categoryname'] = $dept_name;
		   array_push($json_response,$row_array);
	   }

		  echo json_encode($json_response);
		  exit;
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
   $res_cxx=@mysqli_query($con, "select * from unittb where dept_code='$dept_code' order by unit_name");
  if( mysqli_num_rows($res_cxx) > 0){
	  
	  if($unit_code==''){
	 echo '<select name="unit" id="unit" style="width:300px;">
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
  }else{
	  echo "<input type'text' name='unit' id='unit' style='width:300px;' placeholder='Enter Unit Name/Address...' />";
  }
  exit;
 }

if($id=='getnextmemo')
{
	//get next memo count for ID 
	$r= mysqli_query($con, "select * from memo_movementtb where dept_unit='". mysqli_real_escape_string($con, $_REQUEST['dept_unit'])."' and memo_status='IN'");
	//$r= mysqli_query($con, "select * from memotb ");
	$cnt =  mysqli_num_rows($r) + 1;
	//$dpt=substr(strtoupper($_REQUEST['dept_txt']),0,3);
	list($dpt) = explode(' ', strtoupper($_REQUEST['dept_txt']),1);
	$mnt=strtoupper(date('M'));
	//$opText=$dpt."/".$mnt."/".str_pad($cnt,3,'0',STR_PAD_LEFT);	//CONCATENATED WITH MONTH
	$dpt = str_replace(",", "", $dpt);
	$opText_ex = $dpt."/".str_pad($cnt,3,'0',STR_PAD_LEFT).date('s');//  str_pad($cnt,3,'0',STR_PAD_LEFT); 				//NO MONTH CONCATENATION
	$opText = str_replace("'", "", $opText_ex);
	echo $opText.'
	<input type="hidden" name="memo_id" id="memo_id" value="'.$opText.'">
	<input type="hidden" name="file_memo_id" id="file_memo_id" value="'.$opText.'">';
	exit;
}

if($id=='outgoing_mail')
{
	$sdate=$_REQUEST['sdate'];
		$edate=$_REQUEST['edate'];
		//$r= mysqli_query($con, "select mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time from memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id where mm.memo_status='OUT' order by mm.id desc"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
		$r= mysqli_query($con, "SELECT mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit, mm.deptunit_to from memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id where read_status='Read' AND m.entry_date BETWEEN '{$sdate}' AND '{$edate}' order by mm.id desc"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
		 //where mm.memo_status='OUT' and mm.dept_unit='$udept'
		//$r= mysqli_query($con, "select * from memotb"); //ADD CONDITION TO FILTER 		
		$json_response=array();
		 while ($row =  mysqli_fetch_array($r)) { 
			 $row_array['memo_id'] = $row['memo_id'];
			 $row_array['memo_from'] = $row['memo_from'];//." (".$row['address_unit'].")";
			 if(is_numeric($row['address_unit']) and $row['address_unit'] !='') $row_array['address_unit'] = $bursary->get_any_value('unit_name', 'unittb', "unit_code", $row['address_unit']); //$row['address_unit'];
			 else $row_array['address_unit'] = $row['address_unit'];
			 $row_array['description'] = $row['description'];
			 $row_array['amount'] = $row['amount'];
			 $row_array['dept_unit'] = $bursary->get_any_value('unit_name', 'unittb', "unit_code", $row['deptunit_to']); //$row['deptunit_to']; //$row['dept_unit'];
			 $row_array['datein'] = $row['datein']." ".$row['entry_time'];
			 $row_array['memo_status'] = $row['memo_status'];
			 $row_array['read_status'] = $row['read_status'];
			 array_push($json_response,$row_array);
		 }//end of while
		 
		 echo json_encode($json_response);
		 exit;
} 

if($id=='incoming_mail')
{

		//$r= mysqli_query($con, "select mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time from memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id where mm.memo_status='IN' order by mm.id desc"); //ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
	/*echo "<script>alert('".$udept."');</script>";*/
		 /*if($_SESSION['login_id'] == 'ADMIN' ) $r= mysqli_query($con, "select mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit from memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id where mm.memo_status='IN' and  mm.read_status='Unread' order by mm.id desc");
		 
$sq = "SELECT mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit, u.unit_name FROM ((memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id) INNER JOIN unittb u ON u.unit_code=mm.dept_unit) WHERE mm.date BETWEEN '{$sdate}' AND '{$edate}' AND mm.memo_status='IN' and  mm.read_status='Unread' and (mm.dept_unit='".$_SESSION['userunit']."' or m.entry_by='".$_SESSION['login_id']."') order by mm.id desc";

		else*/ 
		$sdate=$_REQUEST['sdate'];
		$edate=$_REQUEST['edate'];

		$sq = "SELECT mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit, u.unit_name FROM ((memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id) INNER JOIN unittb u ON u.unit_code=mm.dept_unit) WHERE mm.memo_status='IN' and  mm.read_status='Unread' and (mm.dept_unit='".$_SESSION['userunit']."' or m.entry_by='".$_SESSION['login_id']."') AND m.entry_date BETWEEN '{$sdate}' AND '{$edate}' order by mm.id desc";

		//$sq = "SELECT mm.memo_id, m.memo_from, m.description, m.amount, m.memo_status, m.datein, mm.read_status, mm.dept_unit, m.entry_time, m.address_unit, u.unit_name FROM ((memo_movementtb mm inner join memotb m on mm.memo_id=m.memo_id) INNER JOIN unittb u ON u.unit_code=mm.dept_unit) WHERE mm.memo_status='IN' and  mm.read_status='Unread' and (mm.dept_unit='".$_SESSION['userunit']."' or m.entry_by='".$_SESSION['login_id']."') order by mm.id desc LIMIT 15";
		$r= mysqli_query($con, $sq);
		//ADD CONDITION TO FILTER BY USER LOGIN DEPARTMENT
		$json_response=array();
		 while ($row =  mysqli_fetch_array($r, 3)) { 
			$row_array['memo_id'] = $row['memo_id'];
			$row_array['memo_from'] = $row['memo_from'];
			//$row_array['address_unit'] = $bursary->get_any_value('unit_name', 'unittb', "unit_code", $row['address_unit']); //$row['address_unit'];
			if(is_numeric($row['address_unit']) and $row['address_unit'] !='') $row_array['address_unit'] = $row['unit_name']; //$bursary->get_any_value('unit_name', 'unittb', "unit_code", $row['address_unit']); //$row['address_unit'];
				else $row_array['address_unit'] = $row['address_unit'];
						
			$row_array['description'] = $row['description'];
			$row_array['amount'] = $row['amount'];
			$row_array['dept_unit'] = $row['unit_name']; //$bursary->get_any_value('unit_name', 'unittb', "unit_code", $row['dept_unit']);// $row['dept_unit'];
			$row_array['datein'] = $row['datein']." ".$row['entry_time'];
			$row_array['memo_status'] = $row['memo_status'];
			$row_array['read_status'] = $row['read_status'];
			array_push($json_response, $row_array);
		 }//end of while
		 
		 echo json_encode($json_response);
		 exit;
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
exit;
} 
if($id=='memo_withsub') 
{	
		$r= mysqli_query($con, "select * from memotb order by id desc"); //ADD DEPARTMENT FILTER
		$json_response=array();
		 while ($row =  mysqli_fetch_array($r)) { 
		 $row_array['memo_id'] = $row['memo_id'];
		 $row_array['memo_from'] = $row['memo_from'];
		 //$row_array['address_unit'] = $row['address_unit'];
		 
		 if(is_numeric($row['address_unit']) and $row['address_unit'] !='') $row_array['address_unit'] = $bursary->get_any_value('unit_name', 'unittb', "unit_code", $row['address_unit']); //$row['address_unit'];
			 else $row_array['address_unit'] = $row['address_unit'];
			 
		 $row_array['description'] = $row['description'];
		 $row_array['amount'] = $row['amount'];
		 $row_array['dept_unit'] = $bursary->get_any_value('unit_name', 'unittb', "unit_code", $row['dept_unit']);// $row['dept_unit'];
		 $row_array['datein'] = $row['datein'];
		 $row_array['memo_status'] = $row['memo_status'];
		 array_push($json_response,$row_array);
		 }//end of while
		 
		 echo json_encode($json_response);
		 exit;
} 

if($id=='memo_sub')
{	
		$itemid =  mysqli_real_escape_string($con, $_REQUEST['memo_id']);
		/*$rs =  mysqli_query($con, "select * from memo_movementtb where memo_id='$itemid' order by id desc");*/
		$rs =  mysqli_query($con, "select m.memo_status, m.dept_unit, m.date, m.remark, m.action, u.unit_name from memo_movementtb m inner join unittb u on m.dept_unit=u.unit_code where m.memo_id='$itemid' order by m.id desc");
	$items = array();
	while($row =  mysqli_fetch_object($rs)){
		array_push($items, $row);
	}
	echo json_encode($items);
exit;
} 

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
		/*echo $_REQUEST['mdoc']; exit;*/
		 
	$memo_from =  mysqli_real_escape_string($con, $_REQUEST['memo_from']);
	$descs =  mysqli_real_escape_string($con, $_REQUEST['desc']);
	$amount =  mysqli_real_escape_string($con, $_REQUEST['amount']);
	$pvno=  mysqli_real_escape_string($con, $_REQUEST['pvno']);
	$memo_id=  mysqli_real_escape_string($con, $_REQUEST['memo_id']); //date('dmY').rand();dept_unit
	$dept_unit =  mysqli_real_escape_string($con, $_REQUEST['dept_unit']);
	$login_id =  mysqli_real_escape_string($con, $_REQUEST['login_id']);
	$dept_addr =  mysqli_real_escape_string($con, $_REQUEST['dept_addr']);
	$action = 'RECEIVED';
	//$remark = 'First Entry';
	$remark = '';
	$erro='';
	$IsError=false;
	if(!isset($_REQUEST['myfile'])) {
		if($memo_from == ""){
			echo $error="Memo source (Memo From) is required!"; $IsError=true;//exit;
		}
		if($descs == ""){
			echo $error="Memo description is required!"; $IsError=true;//exit;
		}
		/*if(!is_numeric($amount) && $amount != ""){
			echo $error="Amount on memo must be numeric data!"; $IsError=true;//exit;
		}*/
	}
	if($memo_id == ""){
		echo $error="No Memo ID generated!"; $IsError=true;//exit;
	}
//file process================================================================================================>
if($IsError) unset($_FILES["myfile"]);//
	if(isset($_FILES["myfile"]) && $_FILES["myfile"]["name"] != ''){
		
		$temp = explode(".", $_FILES["myfile"]["name"]);
		
		//$allowedExts = array("txt","htm","html","php","css","js","json","xml","swf","flv","pdf","psd",                                        "ai","eps","eps","ps","doc","rtf","ppt","odt","ods");
		$allowedExts = array("pdf");	
		$extension = end($temp);
		if( in_array($extension, $allowedExts)){		   // Edit upload location here
		   $destination_path = "upload_files/";  //getcwd().DIRECTORY_SEPARATOR;
		
		   $result = 0;
		   
		   //$target_path = $destination_path.basename( $_FILES['myfile']['name']);
		   $target_path = $destination_path.str_replace('/', '', $memo_id).".pdf";
		
		   if(@move_uploaded_file($_FILES['myfile']['tmp_name'], $target_path)) {
			  $result = 1;
		   }else{
			echo "<script>alert('Record NOT saved due to error in file upload! \nPath: ".$target_path."'); location.reload();</script>";
			exit;
		   }
		   //@mysqli_query($con, "UPDATE memotb SET file_path='". mysqli_real_escape_string($con, $target_path)."' WHERE memo_id='".$memo_id."' limit 1");
	
		   sleep(1);
		}else
		{	
			 echo $error="File Error:::Invalid document type!"; $IsError=true;//exit;
		}
	}
			echo "<script>
				window.top.window.stopUpload($result);
			</script>";
//end file process===============================================================================================>
	/*if(isset($_FILES["myfile"]) && $_FILES["myfile"]["name"] != ''){
		$temp = explode(".", $_FILES["myfile"]["name"]);
		$allowedExts = array("pdf");	
		$extension = end($temp);
		if( in_array($extension, $allowedExts)){
		}else
		{	
			 echo "File Error:::Invalid document type!"; exit;
		}
	}*/
	if(!$IsError){
		/*$res_check=@mysqli_query($con, "select * from memotb where memo_from like '%$memo_from%' and description like '%$descs%' and amount='$amount'");
		$numrow= @mysqli_num_rows($res_check);
		if($numrow == 1){
			echo "<script>alert('This Record has been added before.');</script>"; 
			exit;
		}*/
		//$rs_check = @mysqli_fetch_array($res_check);
		//if (is_numeric($dept_addr)) $dept_addr = $bursary->get_any_value('unit_name', 'unittb', "unit_code", $dept_addr);
		$bursary->begin();
		$sq="insert into memotb set memo_id='$memo_id', memo_from='$memo_from', description='$descs', amount='$amount', datein=Now(),entry_date=Now(),entry_time=Now(),entry_by='$login_id',file_path='". mysqli_real_escape_string($con, $target_path)."', address_unit='$dept_addr'";
		if( mysqli_query($con, $sq))
		{
			if(mysqli_query($con, "insert into memo_movementtb set memo_id='$memo_id', memo_status='IN', ".
			"dept_unit='$dept_unit', date=Now(),action='$action', remark ='$remark', ".
			"entry_date=Now(),entry_time=Now(),entry_by='$login_id'")){
				$bursary->commit();
				$_SESSION['memo_id']=$memo_id;
				if($pvno != ''){
					mysqli_query($con, "update vouchertb set memo_id = '$memo_id' where pvno='$pvno'");
					mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id = '$memo_id'");
				}
				echo "<script>alert('Record Saved Succesfully.'); </script>";
			}else{
				$bursary->rollback();
				echo "<script>alert('1. Record NOT saved! ".mysqli_error($con)."');</script>";
			}
		}
		else{
			$bursary->rollback();
			echo "<script>alert('2. Record NOT saved! ".mysqli_error($con)."');</script>"; /*exit;*/
		}
	}else{
		echo "<script>alert('ERROR:::$error');</script>";
	}
	exit;
}	
	
if($id=="outmail")
{
	$index = $_REQUEST['tabindex'];
	/*echo "<script>alert('am here!'); </script>"; exit;
	echo "<p style='color:red;'>am here!'</p>";
	echo $_REQUEST['contentvar']."<br>";
	*/
	if(!isset($_REQUEST['tmemoid']) || $_REQUEST['tmemoid']==''){
		echo "<script>alert('Please select a memo!'); </script>"; exit;
	}
	 "<script>
		$('#tt').tabs('select', $index);</script>";	 
	$unit_id =  mysqli_real_escape_string($con, $_REQUEST['unit']);
	$remark =  mysqli_real_escape_string($con, $_REQUEST['remark']);
	$memo_id =  mysqli_real_escape_string($con, $_REQUEST['tmemoid']);
	$login_id =  mysqli_real_escape_string($con, $_REQUEST['login_id']);
	$action =  mysqli_real_escape_string($con, $_REQUEST['action']);
	$staff_category =  mysqli_real_escape_string($con, $_REQUEST['staff_category']);
	$memo_unit_code =  mysqli_real_escape_string($con, $_REQUEST['memo_unit_code']);
	$amount_app =  mysqli_real_escape_string($con, $_REQUEST['hmemoamount']);	
	 
	if($memo_id == ""){
		echo "<script>alert('No memo selected. Memo ID is required!');</script>"; exit;
	}
	if($unit_id == ""){
		echo "<script>alert('Select Dept/Unit!');</script>"; exit;
	}
	if($action == ""){
		echo "<script>alert('Select memo action!');</script>"; exit;
	}
	/*if(!is_numeric($amount_app) && $amount_app != ""){
		echo "<script>alert('Amount on memo must be numeric data!');</script>"; exit;
	}*/
	//memo_unit_code
	/*$res_check=@mysqli_query($con, "select * from memo_movementtb where memo_id='$movement' and memo_status='OUT'");
	$numrow= @mysqli_num_rows($res_check);
	if($numrow == 1){echo "<script>alert('This Record has been added.');</script>";exit;}*/
	//$rs_check = @mysqli_fetch_array($res_check);
	//if($action == "Query"){
	$mvc =  mysqli_query($con, "select * from memo_movementtb where memo_id='$memo_id' and ".
	"memo_status='OUT'");// and entry_by='$login_id'"); 
	if( mysqli_num_rows($mvc) < 1){
	//check if memo has already be treated by same person
	//memo has not been treated before
	  if($action == "Queried"){
		if($remark == ""){
			echo "<script>alert('Comment is required for Queried memo!');</script>"; exit;
		}
		$memo_unit_codex=$bursary->get_user_data(@$_SESSION['login_id'], "unit_code");

		if( mysqli_query($con, "insert into  memo_movementtb set memo_id='$memo_id', memo_status='OUT', ".
		"dept_unit='$memo_unit_codex', deptunit_to='$unit_id', date=Now(),action='$action', remark ='$remark', ".
		"entry_date=Now(), entry_time=Now(), entry_by='$login_id', read_status = 'Read' ") or die ( mysqli_error($con)))
		{
			 mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id='$memo_id'"); 
			//update read status on memo_movementtb before inserting new record
			  mysqli_query($con, "insert into  memo_querytb set memo_id='$memo_id', dept_unit='$memo_unit_codex', ".
			 "remark ='$remark', date=Now(), time=Now(), status='Queried', ".
			 "entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con));	
	 		  mysqli_query($con, "update memotb set memo_status='Queried' where memo_id='$memo_id'") or die ( mysqli_error($con));	
			 echo "<script>alert('Done::Memo Queried successfully'); $('#xwin').window('close'); </script>";
		}
			else{echo "<script>alert('Failure::Operation failed!');</script>"; /*exit;*/}
	  }else{
		//SAVING ITEMS WITH ACTIONS ORDER THAN QUERY
		//first save memo as OUT for the active user
		  //$unit_idxx=$bursary->get_any_value('unit_code', 'unittb', "unit_name", $unit_id);
		  $memo_unit_codex=$bursary->get_user_data(@$_SESSION['login_id'], "unit_code");
		  
		 mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id='$memo_id'"); 
		//update read status on memo_movementtb before inserting new record
		if( mysqli_query($con, "insert into memo_movementtb set memo_id='$memo_id', memo_status='OUT', "
		."dept_unit='$memo_unit_codex', date=Now(),action='$action', remark ='$remark', ".
		"entry_date=Now(),entry_time=Now(),entry_by='$login_id', deptunit_to='$unit_id', read_status='Read'") or die ( mysqli_error($con)))			
		{
			 mysqli_query($con, "update memotb set amount_approved = $amount_app where memo_id='$memo_id'"); 
			//update amount approved on memotb
			//saving memo as IN for the next officer in charge
			  mysqli_query($con, "insert into memo_movementtb set memo_id='$memo_id', memo_status='IN', ".
			 "dept_unit='$unit_id', date=Now(),action='$action', remark ='$remark', ".
			 "entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con));
			echo 	"<script>alert('Done::Operation successful!'); $('#xwin').window('close'); </script>";
		} 
		else{echo "<script>alert('Failure::Operation failed!');</script>"; /*exit;*/}			
	  }//end if for action check [Queried or otherwise]
	}else{
		//memo has been treated before
		echo "<script>alert('You have treated this mail before. Duplicate entry not allowed!'); $('#xwin').window('close'); </script>"; exit;
	} // end if for multiple movement check
	exit;
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
	$memo_unit_code =  mysqli_real_escape_string($con, $_REQUEST['memo_unit_code']);
	
	$res_check2=@mysqli_query($con, "select * from memo_querytb where memo_id='$movement' and status='Queried'");
	$numrow2= @mysqli_num_rows($res_check2);
	
	if($numrow2 == 1){echo "<script>alert('This Record has been added.');</script>";
	exit;}	
	
	$mvc =  mysqli_query($con, "select * from memo_movementtb where memo_id='$movement' and ".
	"memo_status='OUT' LIMIT 50");// and entry_by='$login_id'"); 
	if( mysqli_num_rows($mvc) < 1){
		//check if memo has already be treated by same person
		//memo has not been treated before
		if( mysqli_query($con, "insert into  memo_movementtb set memo_id='$movement', memo_status='OUT',".
		" dept_unit='$memo_unit_code', date=Now(), action='$action', remark ='$remark', ".
		"entry_date=Now(), entry_time=Now(), entry_by='$login_id'") or die ( mysqli_error($con)))		
		{
			 mysqli_query($con, "update memo_movementtb set read_status = 'Read' where memo_id='$movement'"); 
			//update read status on memo_movementtb before inserting new record
			 mysqli_query($con, "insert into  memo_querytb set memo_id='$movement', dept_unit='$memo_unit_code', ".
			"remark ='$remark', date=Now(), time=Now(), status='Queried',".
			" entry_date=Now(),entry_time=Now(),entry_by='$login_id'") or die ( mysqli_error($con));	
			 mysqli_query($con, "update memotb set memo_status='Queried' where memo_id='$movement'") or die ( mysqli_error($con));	
			echo "<script>alert('Memo Queried Successfully'); </script>";
		} else{ echo "<script>alert('Memo NOT Queried Successfully');</script>"; /*exit;*/ }
	}else{
		//memo has been treated before
		echo "<script>alert('You have treated this mail before. Duplicate entry not allowed!');</script>"; exit;
	} // end if for multiple movement check
	exit;
}/////// end of outmail query

/////////////////////////////// code for query out_query
if($id=="pvfileupload")
{
	$pvno= mysqli_real_escape_string($con, $_POST['pvno3']);
  if($pvno!=''){
	$mvc =  mysqli_query($con, "SELECT * from vouchertb where pvno='$pvno'"); 
	if( mysqli_num_rows($mvc) >= 1){
		$v= mysqli_fetch_array($mvc, 3 );
		$memo_id=$v['memo_id'];
		//check if bursar has acted on the memo
	  if(isset($_FILES["myfile2"]) && $_FILES["myfile2"]["name"] != ''){
		
		$temp = explode(".", $_FILES["myfile2"]["name"]);
		
		//$allowedExts = array("txt","htm","html","php","css","js","json","xml","swf","flv","pdf","psd",                                        "ai","eps","eps","ps","doc","rtf","ppt","odt","ods");
		$allowedExts = array("pdf");	
		$extension = end($temp);
		if( in_array($extension, $allowedExts)){		   // Edit upload location here
		   $destination_path = "upload_files/";  //getcwd().DIRECTORY_SEPARATOR;
		
		   $result = 0;
		   
		   //$target_path = $destination_path.basename( $_FILES['myfile']['name']);
		   $target_path = $destination_path.str_replace('/', '', $memo_id).".pdf";
		
		   if(@move_uploaded_file($_FILES['myfile2']['tmp_name'], $target_path)) {
			  $result = 1;
		   }
		   @mysqli_query($con, "UPDATE memotb SET file_path='". mysqli_real_escape_string($con, $target_path)."' WHERE memo_id='".$memo_id."'");
			echo "<script>alert('Memo attachment update successful!');</script>";
		   sleep(1);
		}else
		{	
			 echo "File Error:::Invalid document type!"; //exit;
		}
	  }
	}else{
		//memo has been treated before
		echo "<script>alert('Sorry, update is not allowed on this memo because it has been treated!');</script>"; 
		//exit;
	} // end if for multiple movement check			
		   echo "<script>
				window.top.window.stopUpload3($result);
			</script>";
  }
  exit;
}

if($id=="mfileupload")
{
	$memo_id= mysqli_real_escape_string($con, $_POST['vmemoid_x']);
	$mvc =  mysqli_query($con, "select * from memo_movementtb where memo_id='$memo_id' LIMIT 50"); 
	if( mysqli_num_rows($mvc) >= 1){
		//check if bursar has acted on the memo
	  if(isset($_FILES["myfile2"]) && $_FILES["myfile2"]["name"] != ''){
		
		$temp = explode(".", $_FILES["myfile2"]["name"]);
		
		//$allowedExts = array("txt","htm","html","php","css","js","json","xml","swf","flv","pdf","psd",                                        "ai","eps","eps","ps","doc","rtf","ppt","odt","ods");
		$allowedExts = array("pdf");	
		$extension = end($temp);
		if( in_array($extension, $allowedExts)){		   // Edit upload location here
		   $destination_path = "upload_files/";  //getcwd().DIRECTORY_SEPARATOR;
		
		   $result = 0;
		   
		   //$target_path = $destination_path.basename( $_FILES['myfile']['name']);
		   $target_path = $destination_path.str_replace('/', '', $memo_id).".pdf";
		
		   if(@move_uploaded_file($_FILES['myfile2']['tmp_name'], $target_path)) {
			  $result = 1;
		   }
		   @mysqli_query($con, "UPDATE memotb SET file_path='". mysqli_real_escape_string($con, $target_path)."' WHERE memo_id='".$memo_id."' limit 1");
	
		   sleep(1);
		}else
		{	
			 echo "File Error:::Invalid document type!"; //exit;
		}
	  }
	}else{
		//memo has been treated before
		echo "<script>alert('Sorry, update is not allowed on this memo because it has been treated!');</script>"; 
		//exit;
	} // end if for multiple movement check			
		   echo "<script>
				window.top.window.stopUpload2($result);
			</script>";
	exit;
}

if($id=='reconProcess'){
          if($_REQUEST['recordType']=="PayCode"){
               $month= mysqli_real_escape_string($con, $_REQUEST['month']);
               $year= mysqli_real_escape_string($con, $_REQUEST['year']);
               $sqlg="SELECT * FROM recon_remitatb WHERE (Pay='0' OR Pay is null) AND ryear='{$year}' AND rmonth='{$month}' AND paytype='Credit'";
               $qryg= mysqli_query($con, $sqlg);
               $sn=0;  $n_s=0;
               $matchCode=rand(10, 999999);
               while($r= mysqli_fetch_array($qryg, 3 )){
                    $cr=explode('-', $r['special_ref']);
                    $pcr=explode('/', $cr[count($cr)-1]);
                    $pc=$pcr[0];
		$s="UPDATE recon_remitatb SET special_ref2='{$pc}' WHERE id = ".$r['id'];
		$q_s = mysqli_query($con, $s);
	     }

	     $sqlg2="SELECT DISTINCT special_ref2 FROM recon_remitatb WHERE ryear='{$year}' AND rmonth='{$month}' AND paytype='Credit'";
	     ////(Pay='0' OR Pay is null) AND 
               $qryg2= mysqli_query($con, $sqlg2);
	     mysqli_query($con, "UPDATE recon_banktb SET Pay='0' WHERE ryear='{$year}' AND rmonth='{$month}'");
	     while($rs= mysqli_fetch_array($qryg2, 3 )){
		$t="SELECT * FROM recon_banktb b WHERE (Pay='0' OR Pay is null) AND (ryear='{$year}' AND rmonth='{$month}') AND paymentid LIKE '%-{$rs['special_ref2']}/%' AND paytype='Credit'";
     
                    $qt= mysqli_query($con, $t);
                    if( mysqli_num_rows($qt) > 0 ){
			$s="UPDATE recon_remitatb SET match_code='{$matchCode}', Pay='1' WHERE special_ref2 = '{$rs['special_ref2']}' AND paytype='Credit'";
			$q_s = mysqli_query($con, $s);
			$n_s++;
			$s2="UPDATE recon_banktb SET special_ref2='{$rs['special_ref2']}', Pay='1' WHERE paymentid LIKE '%-{$rs['special_ref2']}/%' AND ryear='{$year}' AND rmonth='{$month}' AND paytype='Credit'";
			$q_s2 = mysqli_query($con, $s2);
		}
               }
          }else{
               $sqlg="SELECT * FROM recon_remitatb HAVING (Ref='0' OR Ref is Null) AND paytype='Credit'";
               $qryg= mysqli_query($con, $sqlg);
               $sn=0;  $n_s=0;
               $matchCode=rand(10, 999999);
               while($r= mysqli_fetch_array($qryg, 3 )){
		$t="SELECT * FROM recon_banktb b WHERE b.matched=0 AND b.paymentid LIKE '%".$r['special_ref']."%' AND paytype='Credit'";
		$qt= mysqli_query($con, $t);
		if( mysqli_num_rows($qt) > 0 ){
			$n_s++;
			$s="UPDATE recon_remitatb SET matched=1, match_code='{$matchCode}', Ref='1' WHERE (Ref='0' OR Ref is Null) AND special_ref = '{$r['special_ref']}' AND paytype='Credit'";
			$q_s= mysqli_query($con, $s);
			$s2="UPDATE recon_banktb SET matched=1, match_code='{$matchCode}', special_ref='{$r['special_ref']}', Ref='1' WHERE (Ref='0' OR Ref is Null) AND paymentid LIKE '%".$r['special_ref']."%' AND paytype='Credit'";
			$q_s2= mysqli_query($con, $s2);
		}

                    //$s="UPDATE recon_banktb r, recon_remitatb b SET r.matched=1, b.matched=1, r.match_code='{$matchCode}', b.match_code='{$matchCode}' WHERE b.paymentid LIKE '%".$r['paymentid']."' AND b.amount='".$r['amount']."'";
		/**** 
			$t="SELECT * FROM recon_banktb b WHERE (b.Ref='0' OR b.Ref is null) AND b.paymentid LIKE '%".$r['special_ref']."%'";
			///echo "<br>";
			///$t="SELECT * FROM recon_banktb b WHERE b.matched=0 AND b.paymentid LIKE '%".$r['paymentid']."' AND b.amount='".$r['amount']."'";
			///$t="SELECT * FROM recon_banktb WHERE matched=0 AND credit_reference LIKE '%". mysqli_real_escape_string($con, $r['credit_reference'])."' AND amount='".$r['amount']."'";

			$qt= mysqli_query($con, $t);
			if( mysqli_num_rows($qt) >0 ){
			$s="UPDATE recon_remitatb SET matched=1, match_code='{$matchCode}', Ref='1' WHERE id = ".$r['id'];
			$q_s= mysqli_query($con, $s);
			$n_s++;
			$s2="UPDATE recon_banktb SET matched=1, match_code='{$matchCode}', special_ref='{$r['special_ref']}', Ref='1' WHERE (Ref='0' OR Ref is Null) AND paymentid LIKE '%".$r['special_ref']."%'";
			//$s2="UPDATE recon_banktb SET matched=1, match_code='{$matchCode}' WHERE  matched=0 AND paymentid LIKE '%".$r['paymentid']."' AND amount='".$r['amount']."'";
			///$s2="UPDATE recon_banktb SET matched=1, match_code='{$matchCode}' WHERE  matched=0 AND credit_reference LIKE '%". mysqli_real_escape_string($con, $r['credit_reference'])."' AND amount='".$r['amount']."'";
			$q_s2= mysqli_query($con, $s2);
			}

		****/
                    /*
			if($n_s == 2){
			$q= mysqli_fetch_array($q_s, 3 );
			//echo "<tr><td>{$sn}</td><td>{$r[paymentid]}</td><td>{$r[payer]}</td><td>{$r[amount]}</td></tr>";

			$sqll="update recon_remitatb set matched = 1 where id=".$r['id'];
			if( mysqli_query($con, $sqll)){
			$sqllx="update recon_banktb set matched = 1 where id=".$q['id'];
			mysqli_query($con, $sqllx);
			.}
			.}
	     	*/
               }
     	}
     echo $_SESSION['direct_bank_debit']="<h3>RECONCILIATION COMPLETED.</h3>";
	exit;
}

if($id=="editmails")
{
	$index = $_REQUEST['tabindex'];
	echo "<script>
		$('#tt').tabs('select', $index);</script>";	
		/*echo $_REQUEST['mdoc']; exit;*/
		 
	$memo_from =  mysqli_real_escape_string($con, $_REQUEST['vmemofrom']);
	$descs =  mysqli_real_escape_string($con, $_REQUEST['vmemodesc']);
	$amount =  mysqli_real_escape_string($con, $_REQUEST['vmemoamount']);
	$memo_id=  mysqli_real_escape_string($con, $_REQUEST['vmemoid_x']); //date('dmY').rand();dept_unit
	$dept_unit =  mysqli_real_escape_string($con, $_REQUEST['vmemodept']);
	$login_id =  mysqli_real_escape_string($con, $_REQUEST['vlogin_id']);
	$vaddress_unit =  mysqli_real_escape_string($con, $_REQUEST['vaddress_unit']);

	if($memo_id == ""){
		echo "<script>alert('No Memo ID selected!'); </script>"; exit;
	}
  $mvc =  mysqli_query($con, "select * from memo_movementtb where memo_id='$memo_id'"); 
  if( mysqli_num_rows($mvc) >= 1){
		//check if bursar has acted on the memo
	if($memo_from == ""){
		echo "<script>alert('Memo source (Memo From) is required!'); </script>"; exit;
	}
	if($descs == ""){
		echo "<script>alert('Memo description is required!'); </script>"; exit;
	}
	if(!is_numeric($amount) && $amount != ""){
		echo "<script>alert('Amount on memo must be numeric data!'); </script>"; exit;
	}

	if( mysqli_query($con, "update memotb set memo_from='$memo_from', description='$descs', amount='$amount', datein=Now(), entry_date=Now(), entry_time=Now(), entry_by='$login_id', address_unit='$vaddress_unit' where memo_id='$memo_id'") or die ( mysqli_error($con)))
	{
		/* mysqli_query($con, "insert into memo_movementtb set memo_id='$memo_id', memo_status='IN', ".
		"dept_unit='$dept_unit', date=Now(),action='Re-Submitted', remark ='Memo re-entered after treating query.', ".
		"entry_date=Now(),entry_time=Now(),entry_by='$login_id'");*/
		
		echo "<script>alert('Record Saved Succesfully'); </script>"; //exit;
	}
	else{
		echo "<script>alert('Record NOT saved successfully');</script>"; /*exit;*/
		}
  }else
  {
	  //memo has been treated before
	 echo "<script>alert('Sorry, update is not allowed on this memo because it has been treated!');</script>";
		//exit;
  }// end if for multiple movement check
  exit;
}

if($id=='display_voucher_processx111')
{

	$pvno=$_REQUEST['pvno'];
	$r_vals=$_REQUEST['r_vals'];
	$res_d=@mysqli_query($con, "select * from vouchertb v,voucher_folio_codetb c where v.pvno=c.pvno and v.pvno='$pvno'");
	$rs_d=@mysqli_fetch_array($res_d);	
	  $tb='';
		$tb ="<form name='frmpro' id='frmpro'><script>$('tr#bgd_td').hide();</script>
		<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>Voucher Details</legend>
<table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>
    <tr>
  <td width='20%' height='20' align='left' valign='middle'><strong>PV No.:</strong></td>
  <td width='80%' height='20' align='left' valign='middle'>".$pvno."</td>
  <td width='80%' rowspan='5' align='center' valign='middle' bgcolor='#cc9'><label for='query_txt' style='padding: 0.2em 0.5em; border:1px solid green; color:#990000; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px; width:100%; background-color:#CCCCCC;'>Enter Query Comment:</label><br>
    <textarea name='query_txt' id='query_txt' cols='25' rows='3'></textarea>
	<input type='button' name='cmdpro' id='cmdpro' value='Query Voucher' onclick=\"swapcontent('commit_budget', $('#wvouchercode').attr('value'), $('#wbudgetcode').attr('value'), $('#wvoucheramount').attr('value'), $('#budgettype').attr('value'), $('#budgetyear').attr('value'), $('#budgetdept').attr('value'), $('#pvno').attr('value'), 'QUERY', $('#query_txt').val() );\" class='btn'/>
	</td>
  </tr>
			 <tr>
		     <td height='20' align='left' valign='middle'><strong>Payee:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_name']."</td>
		     </tr>
			 <tr>
		     <td height='20' align='left' valign='middle' nowrap><strong>Payee Acct No.:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_acct_no']."</td>

		     </tr>
			 <tr>
		     <td height='20' align='left' valign='middle'><strong>Payee Bank:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_bank_name']."</td>
		     </tr>
			 <tr>";
		     $tb .= "<td height='20' align='left' valign='middle' nowrap='nowrap'><strong>Prepared Date:</strong></td><td height='20' align='left' valign='middle'>".date('d/m/Y', strtotime($rs_d['voucher_date']))."
	         <input type='hidden' name='pvno' id='pvno' value='".$pvno."'/>
	         <input type='hidden' name='r_vals' id='r_vals' value='".$r_vals."'/></td>
		     </tr>
		 </table>
		 </fieldset>
		
				 <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius: 0 0 5px 5px; border-radius: 0 0 5px 5px;
  -webkit-border-radius: 0 0 5px 5px;'>Budget Details</legend>
  <table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>
    <tr><td width='20%' height='20' align='left' valign='top' colspan='2'>
	<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-top:1px solid green; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:#003366; font-size:10px; text-align:right; -moz-border-radius: 3px 3px 3px 3px; border-radius: 3px 3px 3px 3px;
  -webkit-border-radius: 3px 3px 3px 3px;'>Budget Criteria</legend>";
	$tb .= '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" style="font-size:10px"><tr>';	
  $tb .= "<td width='20%' height='20' align='left' valign='middle'><strong>Budget Year:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_year' id='budget_year' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); } else { $('tr#bgd_td').hide(); } swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."');\">
			    <option selected='selected' value=''>Select...</option>";
				  $dSess = @date('Y');
				for ($t= 2018; $t<=$dSess; $t++)
				{
					$tSession = "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					$tb .= "<option value='$tSession'>$tSession</option>";
				}
				
		        $tb .= "</select></td>
	</tr>
  <tr>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Budget Category:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_cat' id='budget_cat' style='width:120px;' onchange=\" $('tr#exp_tr').show(); $('tr#fund_tr').show(); $('tr#dep_tr').show(); if($('#budget_cat option:selected').val() == 'Departmental'){ $('tr#exp_tr').hide(); $('tr#fund_tr').hide(); } if($('#budget_cat option:selected').val() == 'Refund'){ $('tr#exp_tr').hide(); $('tr#fund_tr').hide(); $('tr#dep_tr').hide(); } swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); \">
			         <option selected='selected'>Select...</option>
                  <option value='Refund'>Refund</option>
                  <option value='Departmental'>Departmental</option>
			         <option value='Recurrent'>Recurrent</option>
			         <option value='Capital Budget'>Capital Budget</option>
			         <option value='Others'>Others</option>
		           </select></td>
  </tr>
	
	<tr id='fund_tr'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Fund Source:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='fundsource' id='fundsource' style='width:120px;' onchange=\"swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); \">
			         <option selected='selected'>Select...</option>";
				  $res_c=@mysqli_query($con, "select * from account_funds order by fund_code");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['fund_code'];
							  $dept_name=@$rs_c['fund_name'];
                              $tb .= "<option value='$dept_code'>$dept_code - $dept_name</option>";
                           }
				
		        $tb .= "</select></td>
  </tr>
  <tr id='dep_tr'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Department:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='deptcode' id='deptcode' style='width:120px;' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); $('tr#cdb_td').hide(); } else { $('tr#bgd_td').hide(); $('tr#cdb_td').hide(); swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); } /**/\">
			         <option selected='selected'>Select...</option>";
				  $res_c=@mysqli_query($con, "select * from account_departments order by department_category");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['department_code']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['department_name']; //$dept_name=@$rs_c['dept_name'];
                              $tb .= "<option value='$dept_code'>$dept_code - $dept_name</option>";
                           }
				
		        $tb .= "</select></td>
  </tr>
  
  
		
    <tr id='exp_tr'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Expense Code:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='itemcode' id='itemcode' style='width:120px;' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); $('tr#cdb_td').hide(); } else { $('tr#bgd_td').hide(); $('tr#cdb_td').hide(); swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); } /**/\">
			         <option selected='selected'>Select...</option>";
				  $res_c=@mysqli_query($con, "select distinct itemcode from foliotb order by itemcode");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['itemcode']; 
                              $tb .= "<option value='$dept_code'>$dept_code</option>";
                           }
				
		        $tb .= "</select></td>
  </tr>
  <!--tr id='cdb_td'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Code Category:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select name='code_cat' id='code_cat' style='width:200px;' onchange=\"swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."', $('#code_cat').val());\">
			         <option selected='selected'>Select...</option>";
					 $q =  mysqli_query($con, "select distinct category from foliotb order by category");
                  while($r= mysqli_fetch_array($q, 3 )){
                  	$tb .= "<option value='". $r['category'] ."'>". $r['category'] ."</option>"; 
				  }
		           $tb .= "</select></td>
  </tr-->
  <!--tr id='bgd_td'>
  <td width='20%' height='20' align='left' valign='middle'><strong>Department/Unit:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_dept' id='budget_dept' style='width:220px;' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); } else { $('tr#bgd_td').hide(); } swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."');\">
			         <option value='' selected='selected'>Select...</option>";
					 $q =  mysqli_query($con, "select * from departmenttb order by dept_name");
                  while($r= mysqli_fetch_array($q, 3 )){
                  	$tb .= "<option value='". $r['dept_code'] ."'>". $r['dept_name'] ."</option>"; 
				  }
		           $tb .= "</select></td></tr--></table></fieldset>
	</td>
	<td width='40%' align='left' valign='top'><div id='folio_summary'></div></td>
  </tr>
			 <tr>
			   <td height='30' colspan='3' align='center' valign='middle'>
			   <table width='100%' border='0' cellspacing='0' cellpadding='0'>
			     <tr>
			       <td width='120px' height='20' align='left' valign='middle' nowrap='nowrap' style='border-right:solid 1px green;'><strong>VOUCHER FOLIO/CODE</strong></td>
			       <!--td width='10%' align='center' valign='middle'>&nbsp;</td-->
			       <td height='20' align='left' valign='middle'><strong>BUDGET</strong></td>
		         </tr>
			     <tr>
			       <td width='120px' align='left' valign='top' class='left' style='vertical-align:text-top; border-right:solid 1px green;'>
                   <div>";
                   $tb .= "<!--table-->";
				   
				   $res_d=@mysqli_query($con, "select vfc.folio_code, vfc.amount, f.title from voucher_folio_codetb vfc inner join foliotb f on vfc.folio_code = f.folio_code where vfc.pvno = '$pvno'");
	//$rs_d=@mysqli_fetch_array($res_d);
while($rs_d=@mysqli_fetch_array($res_d))
	{
		//$tb .= "<div class='dragitem'>".$rs_d['folio_code'].": ".$rs_d['title'].": <strong>N".$rs_d['amount']."</strong><br></div>";
		$tb .= "<!--tr>
                <td--><div class='item' id='$rs_d[folio_code]'>".$rs_d['folio_code']." [".$rs_d['title']."]: <strong>N".$rs_d['amount']."</strong></div><br><!--/td></tr-->";
	}	
		$tb .= "<!--/table-->";
		$tb .= "</div></td>
			       <!--td width='10%' align='center' valign='middle'>&nbsp;</td-->
			       <td align='left' valign='top' class='right'>
				   <div id='read_budget' style='width:100%'></div>
				   </td>
		         </tr>
			     <tr>
			       <td height='20' align='left' valign='middle' colspan='2'>
				   <input name='wvouchercode' id='wvouchercode' type='hidden' value='' />
				   <input name='wbudgetcode' id='wbudgetcode' type='hidden' value='' />
				   <input name='wvoucheramount' id='wvoucheramount' type='hidden' value='' />
				   <input name='pvno' id='pvno' type='hidden' value='$pvno' />
				   
				   <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>Transaction Log:</legend><table width='100%' border='1' cellspacing='0' cellpadding='2' id='vou_bud' rules='rows' frame='box'><thead><tr>
				   <th style='text-align:left; font-weight:bold;' align='left'>Voucher Code</th>
				   <th style='text-align:left; font-weight:bold;' align='left'>Budget Code</th>
				   <th style='text-align:left; font-weight:bold;' align='left' width='5%' nowrap='nowrap'>Voucher Amount</th>
				   </tr></thead><tbody></tbody></table>
				   </fieldset></td>
		         </tr>
		       </table>
               </td></tr>
			 <!--<tr>
		     <td height='20' align='left' valign='middle'>&nbsp;</td><td height='20' align='left' valign='middle'>&nbsp;</td></tr>-->
			 <tr><th height='20' colspan='3' align='center' valign='middle'><input type='button' name='cmdpro' id='cmdpro' value='Commit' onclick=\"swapcontent('commit_budget', $('#wvouchercode').attr('value'), $('#wbudgetcode').attr('value'), $('#wvoucheramount').attr('value'), $('#budgettype').attr('value'), $('#budgetyear').attr('value'), $('#budgetdept').attr('value'), $('#pvno').attr('value'), 'COMMIT', $('#query_txt').val() );\" class='btn'/></th></tr>
					 </table>
		 </fieldset>
<div id='commit_budget'></div> <div id='send_voucher_to_bursar'></div> </form>";
echo $writablestyle="<style type='text/css'>
		.left{
            width:120px;
            float:left;
			vertical-align:top;
			padding:10px;
        }
        .left table{
            background:#E0ECFF;
			vertical-align:top;
        }
        .left td{
            background:#eee;
        }
		.right table{
            background:#E0ECFF;
            width:100%;
        }
        /*.right{
            float:right;
            width:370px;
        }
        
        .right td{
            background:#fafafa;
            color:#444;
            text-align:center;
            padding:2px;
        }
        .right td{
            background:#E0ECFF;
        }
        .right td.drop{
            background:#fafafa;
            width:100px;
        }
        .right td.over{
            background:#FBEC88;
        }*/
        .item{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            width:98%;
        }
		.item:hover,.item:focus{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		.apitem{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		
        .itemx{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            /*width:98%;*/
        }
		.itemx:hover,.itemx:focus{
            border:1px solid #C00;
            background:#CC9;
            color:#C00;
        }
		.apitemx{
            border:1px solid #C00;
            background:#CC9;
            color:#444;
        }
        .assigned{
            border:1px solid #BC2A4D;
        }
        .trash{
            background-color:red;
        }
		#vou_bud th{
            /*border:1px solid #C00;*/
            background:#cc9;
            color:#444;
        }
		.btn {
			  background: #3498db;
			  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
			  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
			  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
			  background-image: -o-linear-gradient(top, #3498db, #2980b9);
			  background-image: linear-gradient(to bottom, #3498db, #2980b9);
			  -webkit-border-radius: 15;
			  -moz-border-radius: 15;
			  border-radius: 15px;
			  text-shadow: 1px 1px 3px #666666;
			  font-family: Georgia;
			  color: #ffffff;
			  font-size: 12px;
			  padding: 5px 10px 5px 10px;
			  text-decoration: none;
			}
			
			.btn:hover {
			  background: #3cb0fd;
			  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
			  text-decoration: none;
			}
	</style>";
		echo $tb;
		exit;
}

if($id=='display_voucher_processx')
{

	$pvno=$_REQUEST['pvno'];
	$r_vals=$_REQUEST['r_vals'];
	$res_d=@mysqli_query($con, "select * from vouchertb v,voucher_folio_codetb c where v.pvno=c.pvno and v.pvno='$pvno'");
	$rs_d=@mysqli_fetch_array($res_d);	
	  $tb='';
		$tb ="<form name='frmpro' id='frmpro'><script>$('tr#bgd_td').hide();</script>
		<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>Voucher Details</legend>
<table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>
    <tr>
  <td width='20%' height='20' align='left' valign='middle'><strong>PV No.:</strong></td>
  <td width='80%' height='20' align='left' valign='middle'>".$pvno."</td>
  <td width='80%' rowspan='5' align='center' valign='middle' bgcolor='#cc9'><label for='query_txt' style='padding: 0.2em 0.5em; border:1px solid green; color:#990000; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px; width:100%; background-color:#CCCCCC;'>Enter Query Comment:</label><br>
    <textarea name='query_txt' id='query_txt' cols='25' rows='3'></textarea>
	<input type='button' name='cmdpro' id='cmdpro' value='Query Voucher' onclick=\"swapcontent('commit_budget', $('#wvouchercode').attr('value'), $('#wbudgetcode').attr('value'), $('#wvoucheramount').attr('value'), $('#budgettype').attr('value'), $('#budgetyear').attr('value'), $('#budgetdept').attr('value'), $('#pvno').attr('value'), 'QUERY', $('#query_txt').val() );\" class='btn'/>
	</td>
  </tr>
			 <tr>
		     <td height='20' align='left' valign='middle'><strong>Payee:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_name']."</td>
		     </tr>
			 <tr>
		     <td height='20' align='left' valign='middle' nowrap><strong>Payee Acct No.:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_acct_no']."</td>

		     </tr>
			 <tr>
		     <td height='20' align='left' valign='middle'><strong>Payee Bank:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_bank_name']."</td>
		     </tr>
			 <tr>";
		     $tb .= "<td height='20' align='left' valign='middle' nowrap='nowrap'><strong>Prepared Date:</strong></td><td height='20' align='left' valign='middle'>".date('d/m/Y', strtotime($rs_d['voucher_date']))."
	         <input type='hidden' name='pvno' id='pvno' value='".$pvno."'/>
	         <input type='hidden' name='r_vals' id='r_vals' value='".$r_vals."'/></td>
		     </tr>
		 </table>
		 </fieldset>
		
				 <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius: 0 0 5px 5px; border-radius: 0 0 5px 5px;
  -webkit-border-radius: 0 0 5px 5px;'>Budget Details</legend>
  <table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>
    <tr><td width='20%' height='20' align='left' valign='top' colspan='2'>
	<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-top:1px solid green; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:#003366; font-size:10px; text-align:right; -moz-border-radius: 3px 3px 3px 3px; border-radius: 3px 3px 3px 3px;
  -webkit-border-radius: 3px 3px 3px 3px;'>Budget Criteria</legend>";
	$tb .= '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" style="font-size:10px"><tr>';	
  $tb .= "<td width='20%' height='20' align='left' valign='middle'><strong>Budget Year:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_year' id='budget_year' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); } else { $('tr#bgd_td').hide(); } swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."');\">
			    <option selected='selected' value=''>Select...</option>";
				  $dSess = @date('Y');
				for ($t= $dSess; $t>=2017; $t--)
				{
					$tSession = "$t";
					//if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
					$tb .= "<option value='$tSession'>$tSession</option>";
				}
				
		        $tb .= "</select></td>
	</tr>
  <tr>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Budget Category:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_cat' id='budget_cat' style='width:120px;' onchange=\" if($('#budget_cat option:selected').val() == 'Departmental'){ $('tr#cdb_td').hide(); $('tr#dep_tr').show(); } 
  else { $('tr#cdb_td').show(); $('tr#dep_tr').hide(); }
 if($('#budget_cat option:selected').val() == 'Refund'){ $('tr#cdb_td').hide(); $('tr#dep_tr').hide(); } 
 swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); \">
			         <option selected='selected' value=''>Select...</option>";
					 
                  $q =  mysqli_query($con, "select distinct bursary_category from budgettb where bursary_category!=''");
                  while($r= mysqli_fetch_array($q, 3 )){
                  	$tb .= "<option value='". $r['bursary_category'] ."'>". $r['bursary_category'] ."</option>"; 
				  } 
		           $tb .= "<!--option value='Refund'>Refund</option-->
				   </select></td>
  </tr>
	<tr id='cdb_td'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Budget Sub-Category:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select name='code_cat' id='code_cat' style='width:200px;' onchange=\"if($(this).val() != '') swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); \">
			         <option selected='selected' value=''>Select...</option>";
					 $q =  mysqli_query($con, "select distinct bursary_sub_category from budgettb where bursary_sub_category!='' order by bursary_sub_category");
                  while($r= mysqli_fetch_array($q, 3 )){
                  	$tb .= "<option value='". $r['bursary_sub_category'] ."'>". $r['bursary_sub_category'] ."</option>"; 
				  } 
		           $tb .= "</select> </td> 
  </tr>
  <tr id='dep_tr'>
  <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Department:</strong></td>
  <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='deptcode' id='deptcode' style='width:120px;' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); $('tr#cdb_td').hide(); } else { $('tr#bgd_td').hide(); $('tr#cdb_td').hide(); swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); } /**/\">
			         <option selected='selected'>Select...</option>";
				  $res_c=@mysqli_query($con, "select * from account_departments order by department_category");
                          while($rs_c=@mysqli_fetch_array($res_c))
                           {
                              $dept_code=@$rs_c['department_code']; //$dept_code=@$rs_c['dept_code'];
							  $dept_name=@$rs_c['department_name']; //$dept_name=@$rs_c['dept_name'];
                              $tb .= "<option value='$dept_code'>$dept_code - $dept_name</option>";
                           }
				
		        $tb .= "</select></td>
  </tr>
</table></fieldset>
	</td>
	<td width='40%' align='left' valign='top'><div id='folio_summary'></div></td>
  </tr>
			 <tr>
			   <td height='30' colspan='3' align='center' valign='middle'>
			   <table width='100%' border='0' cellspacing='0' cellpadding='0'>
			     <tr>
			       <td width='120px' height='20' align='left' valign='middle' nowrap='nowrap' style='border-right:solid 1px green;'><strong>VOUCHER FOLIO/CODE</strong></td>
			       <!--td width='10%' align='center' valign='middle'>&nbsp;</td-->
			       <td height='20' align='left' valign='middle'><strong>BUDGET</strong></td>
		         </tr>
			     <tr>
			       <td width='120px' align='left' valign='top' class='left' style='vertical-align:text-top; border-right:solid 1px green;'>
                   <div>";
                   $tb .= "<!--table-->";
				   
				   $res_d=@mysqli_query($con, "select vfc.folio_code, vfc.amount, f.title from voucher_folio_codetb vfc inner join foliotb f on vfc.folio_code = f.folio_code where vfc.pvno = '$pvno'");
	//$rs_d=@mysqli_fetch_array($res_d);
while($rs_d=@mysqli_fetch_array($res_d))
	{
		//$tb .= "<div class='dragitem'>".$rs_d['folio_code'].": ".$rs_d['title'].": <strong>N".$rs_d['amount']."</strong><br></div>";
		$tb .= "<!--tr>
                <td--><div class='item' id='$rs_d[folio_code]'>".$rs_d['folio_code']." [".$rs_d['title']."]: <strong>N".$rs_d['amount']."</strong></div><br><!--/td></tr-->";
	}	
		$tb .= "<!--/table-->";
		$tb .= "</div></td>
			       <!--td width='10%' align='center' valign='middle'>&nbsp;</td-->
			       <td align='left' valign='top' class='right'>
				   <div id='read_budget' style='width:100%'></div>
				   </td>
		         </tr>
			     <tr>
			       <td height='20' align='left' valign='middle' colspan='2'>
				   <input name='wvouchercode' id='wvouchercode' type='hidden' value='' />
				   <input name='wbudgetcode' id='wbudgetcode' type='hidden' value='' />
				   <input name='wvoucheramount' id='wvoucheramount' type='hidden' value='' />
				   <input name='pvno' id='pvno' type='hidden' value='$pvno' />
				   
				   <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>Transaction Log:</legend><table width='100%' border='1' cellspacing='0' cellpadding='2' id='vou_bud' rules='rows' frame='box'><thead><tr>
				   <th style='text-align:left; font-weight:bold;' align='left'>Voucher Code</th>
				   <th style='text-align:left; font-weight:bold;' align='left'>Budget Code</th>
				   <th style='text-align:left; font-weight:bold;' align='left' width='5%' nowrap='nowrap'>Voucher Amount</th>
				   </tr></thead><tbody></tbody></table>
				   </fieldset></td>
		         </tr>
		       </table>
               </td></tr>
			 <!--<tr>
		     <td height='20' align='left' valign='middle'>&nbsp;</td><td height='20' align='left' valign='middle'>&nbsp;</td></tr>-->
			 <tr><th height='20' colspan='3' align='center' valign='middle'><input type='button' name='cmdpro' id='cmdpro' value='Commit' onclick=\"swapcontent('commit_budget', $('#wvouchercode').attr('value'), $('#wbudgetcode').attr('value'), $('#wvoucheramount').attr('value'), $('#budgettype').attr('value'), $('#budgetyear').attr('value'), $('#budgetdept').attr('value'), $('#pvno').attr('value'), 'COMMIT', $('#query_txt').val() );\" class='btn'/></th></tr>
					 </table>
		 </fieldset>
<div id='commit_budget'></div> <div id='send_voucher_to_bursar'></div> </form>";
echo $writablestyle="<style type='text/css'>
		.left{
            width:120px;
            float:left;
			vertical-align:top;
			padding:10px;
        }
        .left table{
            background:#E0ECFF;
			vertical-align:top;
        }
        .left td{
            background:#eee;
        }
		.right table{
            background:#E0ECFF;
            width:100%;
        }
        /*.right{
            float:right;
            width:370px;
        }
        
        .right td{
            background:#fafafa;
            color:#444;
            text-align:center;
            padding:2px;
        }
        .right td{
            background:#E0ECFF;
        }
        .right td.drop{
            background:#fafafa;
            width:100px;
        }
        .right td.over{
            background:#FBEC88;
        }*/
        .item{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            width:98%;
        }
		.item:hover,.item:focus{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		.apitem{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		
        .itemx{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            /*width:98%;*/
        }
		.itemx:hover,.itemx:focus{
            border:1px solid #C00;
            background:#CC9;
            color:#C00;
        }
		.apitemx{
            border:1px solid #C00;
            background:#CC9;
            color:#444;
        }
        .assigned{
            border:1px solid #BC2A4D;
        }
        .trash{
            background-color:red;
        }
		#vou_bud th{
            /*border:1px solid #C00;*/
            background:#cc9;
            color:#444;
        }
		.btn {
			  background: #3498db;
			  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
			  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
			  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
			  background-image: -o-linear-gradient(top, #3498db, #2980b9);
			  background-image: linear-gradient(to bottom, #3498db, #2980b9);
			  -webkit-border-radius: 15;
			  -moz-border-radius: 15;
			  border-radius: 15px;
			  text-shadow: 1px 1px 3px #666666;
			  font-family: Georgia;
			  color: #ffffff;
			  font-size: 12px;
			  padding: 5px 10px 5px 10px;
			  text-decoration: none;
			}
			
			.btn:hover {
			  background: #3cb0fd;
			  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
			  text-decoration: none;
			}
	</style>";
		echo $tb;
}

if($id=='display_voucher_process_transfer')
{
	$voteID=$_REQUEST['id'];
	$pvno=$_REQUEST['pvno'];
	$r_vals=$_REQUEST['r_vals'];
	$res_d=@mysqli_query($con, "select * from vouchertb v,voucher_folio_codetb c where v.pvno=c.pvno and v.pvno='$pvno'");
	$rs_d=@mysqli_fetch_array($res_d);	
	  $tb='';
		$tb =" <form name='frmpro' id='frmpro'><script>$('tr#bgd_td').hide();</script>
		<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>Voucher Details</legend>
<table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>
    <tr>
  <td width='20%' height='20' align='left' valign='middle'><strong>PV No.:</strong></td>
  <td width='80%' height='20' align='left' valign='middle'>".$pvno."</td>
  </tr>
			 <tr>
		     <td height='20' align='left' valign='middle'><strong>Payee:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_name']."</td>
		     </tr>
			 <tr>
		     <td height='20' align='left' valign='middle' nowrap><strong>Payee Acct No.:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_acct_no']."</td>

		     </tr>
			 <tr>
		     <td height='20' align='left' valign='middle'><strong>Payee Bank:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_bank_name']."</td>
		     </tr>
			 <tr>";
		     $tb .= "<td height='20' align='left' valign='middle' nowrap='nowrap'><strong>Prepared Date:</strong></td><td height='20' align='left' valign='middle'>".date('d/m/Y', strtotime($rs_d['voucher_date']))."
	         <input type='hidden' name='pvno' id='pvno' value='".$pvno."'/>
	         <input type='hidden' name='r_vals' id='r_vals' value='".$r_vals."'/></td>
		     </tr>
		 </table>
		 </fieldset>
		
		  <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius: 0 0 5px 5px; border-radius: 0 0 5px 5px;
  -webkit-border-radius: 0 0 5px 5px;'>Budget Details</legend>
  <table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>
<tr>
      <td height='20' align='left' valign='top'><div id='folio_summary'></div></td>
      </tr>
    <tr><td height='20' align='left' valign='top'>
      <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-top:1px solid green; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:#003366; font-size:10px; text-align:right; -moz-border-radius: 3px 3px 3px 3px; border-radius: 3px 3px 3px 3px;
  -webkit-border-radius: 3px 3px 3px 3px;'>Budget Criteria</legend>";
        $tb .= '<table width="100%" border="0" align="center" cellpadding="1" cellspacing="0" style="font-size:10px"><tr>';	
          $tb .= "<td width='20%' height='20' align='left' valign='middle'><strong>Budget Year:</strong></td>
          <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_year' id='budget_year' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); } else { $('tr#bgd_td').hide(); } swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."');\">
            <option selected='selected' value=''>Select...</option>";
            $dSess = @date('Y');
            for ($t= 2018; $t<=$dSess; $t++)
            {
            $tSession = "$t";
            //if ($tSession == $postResultSession) { $tChked = "selected='$selected'"; } else { $tChked = ""; }
            $tb .= "<option value='$tSession'>$tSession</option>";
            }
            
            $tb .= "</select></td>
          </tr>
          <tr>
            <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Budget Category:</strong></td>
            <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='budget_cat' id='budget_cat' style='width:120px;' onchange=\" $('tr#exp_tr').show(); $('tr#fund_tr').show(); $('tr#dep_tr').show(); if($('#budget_cat option:selected').val() == 'Departmental'){ $('tr#exp_tr').hide(); $('tr#fund_tr').hide(); } if($('#budget_cat option:selected').val() == 'Refund'){ $('tr#exp_tr').hide(); $('tr#fund_tr').hide(); $('tr#dep_tr').hide(); } swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); \">
              <option selected='selected'>Select...</option>
              <option value='Refund'>Refund</option>
              <option value='Departmental'>Departmental</option>
              <option value='Recurrent'>Recurrent</option>
              <option value='IGR Capital'>IGR Capital</option>
              <option value='FG Capital'>FG Capital</option>
              <option value='TETFund Capital'>TETFund Capital</option>
              <option value='TETFund Research'>TETFund Research</option>
              <option value='NEEDS Assessment'>NEEDS Assessment</option>
              <option value='Others'>Others</option>
              </select></td>
            </tr>
          
          <tr id='fund_tr'>
            <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Fund Source:</strong></td>
            <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='fundsource' id='fundsource' style='width:120px;' onchange=\"swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); \">
              <option selected='selected'>Select...</option>";
              $res_c=@mysqli_query($con, "select * from account_funds order by fund_code");
              while($rs_c=@mysqli_fetch_array($res_c))
              {
              $dept_code=@$rs_c['fund_code'];
              $dept_name=@$rs_c['fund_name'];
              $tb .= "<option value='$dept_code'>$dept_code - $dept_name</option>";
              }
              
              $tb .= "</select></td>
            </tr>
          <tr id='dep_tr'>
            <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Department:</strong></td>
            <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='deptcode' id='deptcode' style='width:120px;' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); $('tr#cdb_td').hide(); } else { $('tr#bgd_td').hide(); $('tr#cdb_td').hide(); swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); } \">
              <option selected='selected'>Select...</option>";
              $res_c=@mysqli_query($con, "select * from account_departments order by department_category");
              while($rs_c=@mysqli_fetch_array($res_c))
              {
              $dept_code=@$rs_c['department_code'];               $dept_name=@$rs_c['department_name'];               $tb .= "<option value='$dept_code'>$dept_code - $dept_name</option>";
              }
              
              $tb .= "</select></td>
            </tr>
          
          
          
          <tr id='exp_tr'>
            <td width='20%' height='20' align='left' valign='middle' nowrap='nowrap'><strong>Expense Code:</strong></td>
            <td width='40%' height='20' align='left' valign='middle'><select style='width:200px' name='itemcode' id='itemcode' style='width:120px;' onchange=\"if($('#budget_cat option:selected').val() == 'Recurrent'){ $('tr#bgd_td').show(); $('tr#cdb_td').hide(); } else { $('tr#bgd_td').hide(); $('tr#cdb_td').hide(); swapcontent('read_budget', $('#budget_cat').val(), $('#budget_dept').val(), $('#budget_year').val(), '".$pvno."'); } /**/\">
              <option selected='selected'>Select...</option>";
              $res_c=@mysqli_query($con, "select distinct itemcode from foliotb order by itemcode");
              while($rs_c=@mysqli_fetch_array($res_c))
              {
              $dept_code=@$rs_c['itemcode']; 
              $tb .= "<option value='$dept_code'>$dept_code</option>";
              }
              
              $tb .= "</select></td>
            </tr>
  </table></fieldset>
    </td>
	</tr>
			 <tr>
			   <td height='30' align='center' valign='middle'>
			   <table width='100%' border='0' cellspacing='0' cellpadding='0'>
			     <tr>
			       <td width='120px' height='20' align='left' valign='middle' nowrap='nowrap' style='border-right:solid 1px green;'><strong>VOUCHER FOLIO/CODE</strong></td>
			       <td height='20' align='left' valign='middle'><strong>BUDGET</strong></td>
		         </tr>
			     <tr>
			       <td width='120px' align='left' valign='top' class='left' style='vertical-align:text-top; border-right:solid 1px green;'>
                   <div>";
				   $res_d=@mysqli_query($con, "select vfc.folio_code, vfc.amount, f.title from voucher_folio_codetb vfc inner join foliotb f on vfc.folio_code = f.folio_code where vfc.pvno = '$pvno'");
	//$rs_d=@mysqli_fetch_array($res_d);
while($rs_d=@mysqli_fetch_array($res_d))
	{
		//$tb .= "<div class='dragitem'>".$rs_d['folio_code'].": ".$rs_d['title'].": <strong>N".$rs_d['amount']."</strong><br></div>";
		$tb .= "<!--tr>
                <td--><div class='item' id='$rs_d[folio_code]'>".$rs_d['folio_code']." [".$rs_d['title']."]: <strong>N".$rs_d['amount']."</strong></div><br><!--/td></tr-->";
	}
		$tb .= "</div></td>
			       <td align='left' valign='top' class='right'>
				   <div id='read_budget' style='width:100%'></div>
				   </td>
		         </tr>
			     <tr>
			       <td height='20' align='left' valign='middle' colspan='2'>
				   <input name='wvouchercode' id='wvouchercode' type='hidden' value='' />
				   <input name='wbudgetcode' id='wbudgetcode' type='hidden' value='' />
				   <input name='wvoucheramount' id='wvoucheramount' type='hidden' value='' />
				   <input name='pvno' id='pvno' type='hidden' value='$pvno' />
				   
				   <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>Transaction Log:</legend><table width='100%' border='1' cellspacing='0' cellpadding='2' id='vou_bud' rules='rows' frame='box'><thead><tr>
				   <th style='text-align:left; font-weight:bold;' align='left'>Voucher Code</th>
				   <th style='text-align:left; font-weight:bold;' align='left'>Budget Code</th>
				   <th style='text-align:left; font-weight:bold;' align='left' width='5%' nowrap='nowrap'>Voucher Amount</th>
				   </tr></thead><tbody></tbody></table>
				   </fieldset></td>
		         </tr>
		       </table>
               </td></tr>
			 <!--<tr>
		     <td height='20' align='left' valign='middle'>&nbsp;</td><td height='20' align='left' valign='middle'>&nbsp;</td></tr>-->
			 <tr><th height='20' align='center' valign='middle'><input type='button' name='cmdpro' id='cmdpro' value='COMMIT TRANSFER' onclick=\"swapcontent('commit_budget_transfer', $('#wvouchercode').attr('value'), $('#wbudgetcode').attr('value'), $('#wvoucheramount').attr('value'), $('#budgettype').attr('value'), $('#budgetyear').attr('value'), $('#budgetdept').attr('value'), $('#pvno').attr('value'), 'COMMIT', $('#query_txt').val(), '', '".$voteID."');\" class='btn'/></th></tr>
			  </table>
		 </fieldset>
<div id='commit_budget_transfer'></div> <div id='send_voucher_to_bursar'></div> </form>";
echo $writablestyle="<style type='text/css'>
		.left{
            width:120px;
            float:left;
			vertical-align:top;
			padding:10px;
        }
        .left table{
            background:#E0ECFF;
			vertical-align:top;
        }
        .left td{
            background:#eee;
        }
		.right table{
            background:#E0ECFF;
            width:100%;
        }
        .item{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            width:98%;
        }
		.item:hover,.item:focus{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		.apitem{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		
        .itemx{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
        }
		.itemx:hover,.itemx:focus{
            border:1px solid #C00;
            background:#CC9;
            color:#C00;
        }
		.apitemx{
            border:1px solid #C00;
            background:#CC9;
            color:#444;
        }
        .assigned{
            border:1px solid #BC2A4D;
        }
        .trash{
            background-color:red;
        }
		#vou_bud th{
            background:#cc9;
            color:#444;
        }
		.btn {
			  background: #3498db;
			  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
			  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
			  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
			  background-image: -o-linear-gradient(top, #3498db, #2980b9);
			  background-image: linear-gradient(to bottom, #3498db, #2980b9);
			  -webkit-border-radius: 15;
			  -moz-border-radius: 15;
			  border-radius: 15px;
			  text-shadow: 1px 1px 3px #666666;
			  font-family: Georgia;
			  color: #ffffff;
			  font-size: 12px;
			  padding: 5px 10px 5px 10px;
			  text-decoration: none;
			}
			
			.btn:hover {
			  background: #3cb0fd;
			  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
			  text-decoration: none;
			}
	</style>";
		echo $tb;
		exit;
}

if($id=='reverse_budget_comit')
{
	$pvno=$_REQUEST['pvno'];
	$r_vals=$_REQUEST['r_vals'];
	$revAction=$_REQUEST['rev_action'];
  if($revAction == "undo"){
	  $qr =  mysqli_query($con, "select * from budget_votebooktb where voucher_pvno = '$pvno'");
	  if( mysqli_num_rows($qr) <= 0)
	  	$vsql = "update vouchertb set controlled_action='', controlled_remark='', controlled_by='' where pvno='$pvno'";
	  else $vsql = "update vouchertb set controlled_action='Approved', controlled_remark='' where pvno='$pvno'";
	  
	  if( mysqli_query($con, $vsql)){
		  echo "<script>alert('Operation successful!')</script>";
	  }else echo "<script>alert('Operation failed!')</script>";
  }else{
	$res_d=@mysqli_query($con, "select * from vouchertb v,voucher_folio_codetb c where v.pvno=c.pvno and v.pvno='$pvno'");
	$rs_d=@mysqli_fetch_array($res_d);	
	  $tb='';
		$tb ="<form name='frmpro' id='frmpro'><script>$('tr#bgd_td').hide();</script>
		<fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'>Voucher Details</legend>
<table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>
    <tr>
  <td width='20%' height='20' align='left' valign='middle'><strong>PV No.:</strong></td>
  <td width='80%' height='20' align='left' valign='middle'>".$pvno."</td>
  <td width='80%' rowspan='5' align='center' valign='middle'>&nbsp;</td>
  </tr>
	 <tr>
	 <td height='20' align='left' valign='middle'><strong>Payee:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_name']."</td>
	 </tr>
	 <tr>
	 <td height='20' align='left' valign='middle' nowrap><strong>Payee Acct No.:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_acct_no']."</td>
	 </tr>
	 <tr>
	 <td height='20' align='left' valign='middle'><strong>Payee Bank:</strong></td><td height='20' align='left' valign='middle'>".$rs_d['payee_bank_name']."</td>
	 </tr>
	 <tr>";
	 $tb .= "<td height='20' align='left' valign='middle' nowrap='nowrap'><strong>Prepared Date:</strong></td><td height='20' align='left' valign='middle'>".date('d/m/Y', strtotime($rs_d['voucher_date']))."
	 <input type='hidden' name='pvno' id='pvno' value='".$pvno."'/>
	 <input type='hidden' name='r_vals' id='r_vals' value='".$r_vals."'/></td>
	 </tr>
	</table>
	</fieldset>
		
				 <fieldset style='border:1px solid green; -moz-border-radius:5px; border-radius: 5px;
  -webkit-border-radius: 5px;'><legend style='padding: 0.2em 0.5em; border-right:1px solid green; border-left:1px solid green; border-bottom:1px solid green; color:green; font-size:100%; text-align:right; -moz-border-radius: 0 0 5px 5px; border-radius: 0 0 5px 5px;
  -webkit-border-radius: 0 0 5px 5px;'>Budget Details</legend>
  <table width='98%' border='0' align='center' cellpadding='3' cellspacing='0'>";
			 $tb .= "<tr>
			   <td height='30' colspan='2' align='center' valign='middle'>
			   <table width='100%' border='0' cellspacing='0' cellpadding='0'>
			     <tr>
			       <td height='20' align='left' valign='middle' colspan='2'>
				   <input name='pvno' id='pvno' type='hidden' value='".$pvno."' />
				   
<table width='100%' border='1' cellspacing='0' cellpadding='2' id='vou_bud' rules='rows' frame='box'><thead><tr>
				   <th style='text-align:left; font-weight:bold;' align='left'>Voucher Item</th>
				   <th style='text-align:left; font-weight:bold;' align='left'>Budget Description</th>
				   <th style='text-align:left; font-weight:bold;' align='left' width='5%' nowrap='nowrap'>Amount</th>
				   </tr></thead><tbody>";
	$sql =  mysqli_query($con, "select * from `budget_votebooktb` where voucher_pvno = '".$pvno."'");
	while($row =  mysqli_fetch_array($sql, 3 )){
		$tb .= "<tr>
	   <td style='text-align:left;' align='left'>".get_folio_name($row['voucher_folio_code'])."</td>
	   <td style='text-align:left;' align='left'>".get_folio_name($row['budget_folio_code'])."</td>
	   <td style='text-align:left;' align='left' width='5%' nowrap='nowrap'>".$row['amount']."</td>
	   </tr>";
	}
				   $tb .= "</tbody></table>
				   </td>
		         </tr>
		       </table>
               </td></tr>
			 <tr><th height='20' colspan='2' align='center' valign='middle'><input type='button' name='cmdpro' id='cmdpro' value='Reverse Budget Commit' onclick=\"swapcontent('reverse_budget_comit_process', $('#pvno').val(), '{$revAction}' );\" class='btn'/></th></tr>
					 </table>
		 </fieldset>
<div id='reverse_budget_comit_process'></div></form>";
echo $writablestyle="<style type='text/css'>
		#vou_bud th{
            /*border:1px solid #C00;*/
            background:#cc9;
            color:#444;
        }
		.btn {
			  background: #3498db;
			  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
			  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
			  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
			  background-image: -o-linear-gradient(top, #3498db, #2980b9);
			  background-image: linear-gradient(to bottom, #3498db, #2980b9);
			  -webkit-border-radius: 15;
			  -moz-border-radius: 15;
			  border-radius: 15px;
			  text-shadow: 1px 1px 3px #666666;
			  font-family: Georgia;
			  color: #ffffff;
			  font-size: 12px;
			  padding: 5px 10px 5px 10px;
			  text-decoration: none;
			}
			
			.btn:hover {
			  background: #3cb0fd;
			  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
			  text-decoration: none;
			}
	</style>";
		echo $tb;
  }
  exit;
}

if($id=='reverse_budget_comit_process'){
	//process reversal of budget commit
	$pvno=$_REQUEST['pvno'];	$r_vals=$_REQUEST['r_vals'];
	$flag=false;				$login_id = @$_SESSION['login_id'];
	$revAction=$_REQUEST['rev_action'];
	begin();
	//delete record of voucher from budget votebook
	$qry1 = "delete from `budget_votebooktb` where voucher_pvno = '".$pvno."'";
	if( mysqli_query($con, $qry1)) $flag=true;
	//reverse controlled_by action in voucher table
	
	if($revAction == "processed")
		$vsql = "update vouchertb set controlled_by = '', controlled_action='', controlled_remark='' where pvno='$pvno'";
	elseif($revAction == "queried")
		$vsql = "update vouchertb set controlled_by = '', controlled_action='', controlled_remark='', audit_by = '', audit_action='', controlled_remark='', paid_by = '', paid_action='', paid_remark='' where pvno='$pvno'";
		
	if( mysqli_query($con, $vsql) and $flag) $flag=true;
	else $flag=false;
	//update voucher_extra_allocation_requesttb here (reset commit status)
	$vesql = "update voucher_extra_allocation_requesttb set commit_by = '', commit_status='Not Committed' where pvno='$pvno'";
	if( mysqli_query($con, $vesql) and $flag) $flag = true;
		else $flag = false;

	if($flag) {
		commit();
		logs("$login_id","Expenditure Control","$login_id reversed budget committment in votebook. PVNo: $pvno");
		echo "<script>alert('Operation successful! Please wait for page to reload.');</script>";
	}
	else{ 
		rollback();
		echo "<script>alert('Operation Failed! Please wait for page to reload.');</script>";
	}
}// end of reverse_budget_comit_process

if($id=='process_voucher')
{
	$login_id=@$_SESSION['login_id'];
	$pvno=$_REQUEST['pvno'];
	$opt=$_REQUEST['opt'];  //Approved or Not Approved
	$r_vals=$_REQUEST['r_vals'];
	$r=strtolower($r_vals);
	$comment=$_REQUEST['comment'];
	if(!isset($_SESSION['login_id']) or $_SESSION['login_id'] == ''){
		echo "<script>alert('Session timeout. You will have login again!'); window.location='index.php';</script>"; exit;
	}
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
		  
		  //End of Code for updating other tables goes here /////////////
		  
		  
		  
	  } //end of cash officer
	
		
	echo "<script>alert('Record updated successfully');</script>";
	exit;
}

if($id == 'read_budget111')
{

	$writable="";
	$budget_cat =  mysqli_real_escape_string($con, $_REQUEST['budget_cat']);
	$budget_year =  mysqli_real_escape_string($con, $_REQUEST['budget_year']);
	$budget_dept =  mysqli_real_escape_string($con, $_REQUEST['budget_dept']);
	$pvno =  mysqli_real_escape_string($con, $_REQUEST['pvno']);
	$code_cat =  mysqli_real_escape_string($con, $_REQUEST['code_cat']);
	//echo  "select f.title, f.folio_code, b.id, b.amount from budget_capitaltb b inner join foliotb f on b.folio_code = f.folio_code where b.year='$budget_year' and f.category='$code_cat' and bursary_category='Capital' order by f.folio_code"; exit;
	$extra_voucher = $bursary->get_any_value('approval_status', 'voucher_extra_allocation_requesttb', 'pvno', $pvno);
		echo "<input name='budgettype' id='budgettype' type='hidden' value='$budget_cat' />";
	   	echo "<input name='budgetyear' id='budgetyear' type='hidden' value='$budget_year' />";
	   	echo "<input name='budgetdept' id='budgetdept' type='hidden' value='$budget_dept' />";
		echo "<input name='pvno' id='pvno' type='hidden' value='$pvno' />";
		echo "<input name='extra_voucher' id='extra_voucher' type='hidden' value='$extra_voucher' />";

	/*echo "<script>alert('$budget_dept');</script>";
	
	echo "Dept: ".$budget_dept."<br>";
	echo "Category: ".$budget_cat."<br>";
	echo "Year: ".$budget_year."<br>";
	*/
	$sql="";
	if(strtolower(trim($budget_cat)) == "xxrefund" && $budget_year != ''){
		//section for department budget
		if($budget_dept != ''){
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <!--td style='text-align:left; font-weight:bold;' align='left'>Target Area</td-->
				   <td style='text-align:left; font-weight:bold;' align='left'>Budgeted Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Voucher Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   //use this if folio code in budgettb must match folio codes in foliotb
				   //$sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.unit_code='$budget_dept' and b.budget_year='$budget_year'");

				   //use this to folio code in budgettb without deending on folio codes in foliotb
				   $sql =  mysqli_query($con, "select folio_code, id, amount, dept_code from budgettb where dept_code='$budget_dept' and budget_year='$budget_year'");
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	//<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>
					".$rec['dept_code'].": ".get_dept_name($rec['dept_code'])."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['dept_code']."' /></td>
					<!--td class='drop' id='".$rec['id']."' name='".$rec['id']."'></td-->
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<!--<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$rec['amount']."</td>-->
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left' id='vamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '".$budget_dept."' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
		}
	}
	elseif(strtolower(trim($budget_cat)) == "refund"){
		//section for capital budget
		//echo "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and f.category='$code_cat' and bursary_category='Capital' order by f.folio_code"; exit;
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <!--td style='text-align:left; font-weight:bold;' align='left'>Target Area</td-->
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Voucher Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   $sqll = "select f.title, f.folio_code, f.id from foliotb f  WHERE f.folio_code LIKE '09-000-%' AND f.status='Active' ORDER BY f.folio_code";
				   $sql =  mysqli_query($con, $sqll);
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   ///$budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<!--td class='drop' id='".$rec['id']."' name='".$rec['id']."'></td-->
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>0</td>
					<!--<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>0</td>-->
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='vamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '0', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	elseif(strtolower(trim($budget_cat)) == "xothers" && $budget_year != ''){
		//section for administrative budget
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <!--td style='text-align:left; font-weight:bold;' align='left'>Target Area</td-->
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Voucher Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   $sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and bursary_category='Others'");
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<!--td class='drop' id='".$rec['id']."' name='".$rec['id']."'></td-->
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<!--<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$rec['amount']."</td>-->
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left' id='vamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	else{
		$bcat=strtolower(trim($budget_cat));
		if($bcat == "departmental") $bcode = $_REQUEST['deptcode'];
		else $bcode = $_REQUEST['fundsource']."-".$_REQUEST['deptcode']."-".$_REQUEST['itemcode'];
		//echo $bcode;
		//section for capital budget
		//echo "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and f.category='$code_cat' and bursary_category='Capital' order by f.folio_code"; exit;
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <!--td style='text-align:left; font-weight:bold;' align='left'>Target Area</td-->
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Spent</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Voucher Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   if($bcat == "departmental")
				   $ssql="select d.department_name AS title, d.department_code AS folio_code, b.id, b.amount from budgettb b inner join account_departments d on b.folio_code = d.department_code where b.budget_year='$budget_year' and folio_code='$bcode' order by d.department_code";
				   else
				   $ssql="select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and bursary_category='$budget_cat' order by f.folio_code";
				   ///echo $ssql;
				   $sql =  mysqli_query($con, $ssql);
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $amountSpent = $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')));
					   $budget_amount_left = $rec['amount'] - $amountSpent; ///(($rec['amount']/100.0) * 15.0) - $amountSpent ;
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<!--td class='drop' id='".$rec['id']."' name='".$rec['id']."'></td-->
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<!--<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".number_format($rec['amount'], 2)."</td>-->
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".number_format($amountSpent, 2)."</td>
					<td style='text-align:left;' align='left' id='vamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".number_format($budget_amount_left, 2)."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	
       $writablestyle="<style type='text/css'>
		.left{
            width:120px;
            float:left;
			vertical-align:top;
			padding:10px;
        }
        .left table{
            background:#E0ECFF;
			vertical-align:top;
        }
        .left td{
            background:#eee;
        }
		.right table{
            background:#E0ECFF;
            width:100%;
        }
        /*.right{
            float:right;
            width:370px;
        }
        
        .right td{
            background:#fafafa;
            color:#444;
            text-align:center;
            padding:2px;
        }
        .right td{
            background:#E0ECFF;
        }
        .right td.drop{
            background:#fafafa;
            width:100px;
        }
        .right td.over{
            background:#FBEC88;
        }*/
        .item{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            width:98%;
        }
		.item:hover,.item:focus{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		.apitem{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		
        .itemx{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            /*width:98%;*/
        }
		.itemx:hover,.itemx:focus{
            border:1px solid #C00;
            background:#CC9;
            color:#C00;
        }
		.apitemx{
            border:1px solid #C00;
            background:#CC9;
            color:#444;
        }
        .assigned{
            border:1px solid #BC2A4D;
        }
        .trash{
            background-color:red;
        }
		#vou_bud th{
            /*border:1px solid #C00;*/
            background:#cc9;
            color:#444;
        }
		.btn {
			  background: #3498db;
			  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
			  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
			  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
			  background-image: -o-linear-gradient(top, #3498db, #2980b9);
			  background-image: linear-gradient(to bottom, #3498db, #2980b9);
			  -webkit-border-radius: 15;
			  -moz-border-radius: 15;
			  border-radius: 15px;
			  text-shadow: 1px 1px 3px #666666;
			  font-family: Georgia;
			  color: #ffffff;
			  font-size: 12px;
			  padding: 5px 10px 5px 10px;
			  text-decoration: none;
			}
			
			.btn:hover {
			  background: #3cb0fd;
			  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
			  text-decoration: none;
			}
	</style>";
	$writablescript="<script>
	        $(function(){
            $('.left .item').draggable({
                revert:true,
                proxy:'clone',
				onStopDrag:function(){
					//alert(123);
				}
            });
            $('.right td.drop').droppable({
                accept: '.item',
				revert:true,
                onDragEnter:function(){
                    $(this).addClass('over');
                },
                onDragLeave:function(){
				  /*if($(this).text() == '' ){
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var v_amt = $('#vamount_' + cur_id).text();
					var l_amt = $('#lamount_' + cur_id).text();
					var lx_amt = (l_amt * 1) + (v_amt * 1);
					$('#lamount_' + cur_id).text(lx_amt);
					$('#vamount_' + cur_id).text('');
				  }*/
                  $(this).removeClass('over');
                },
                onDrop:function(e,source){
				  if($(this).text() == ''){
                    $(this).removeClass('over');
					//alert($(this).attr('id'));
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var dstr=$(source).text().split(':');
					var dstr2=dstr[1].split('N');
					var v_amt=dstr2[1];
					
					//var v_amt = $('#vamount_' + cur_id).text();
					if((b_amt * 1) >= (v_amt * 1)){
						$('#vamount_' + cur_id).text(v_amt);
						var l_amt = b_amt - v_amt;
						$('#lamount_' + cur_id).text(l_amt);
						
						if ($(source).hasClass('assigned')){
							$(this).append(source);
						} else {
							var c = $(source).clone().addClass('assigned');
							$(this).empty().append(c);
							c.draggable({
								revert:false
							});
						}
					}else {
						$(this).removeClass('over');
						alert('Invalid Transaction: Voucher amount is above budget balance!');
					}//end if for amount check
				  }else {
					  $(this).removeClass('over');
					  alert('Invalid Transaction: Multiple transaction not allowed on same folio/code!');
				  }//end if for text='' check
                }
            });
            $('.left').droppable({
                accept:'.assigned',
                onDragEnter:function(e,source){
                    $(source).addClass('trash');
                },
                onDragLeave:function(e,source){
                    $(source).removeClass('trash');
                },
                onDrop:function(e,source){
					//var ids=document.getElementsByClassName('drop');
					var ids=document.getElementsByTagName('td')[0].id;
					alert(ids);
					//for (var i = 0; i < ids.length; i++) {
  //alert(ids[i].id);
//}
				   //alert($(source).attr('name'));
				  /*if($(this).text() == '' ){
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var v_amt = $('#vamount_' + cur_id).text();
					var l_amt = $('#lamount_' + cur_id).text();
					var lx_amt = (l_amt * 1) + (v_amt * 1);
					$('#lamount_' + cur_id).text(lx_amt);
					$('#vamount_' + cur_id).text('');
				  }*/
                    $(source).remove();
                }
            });
        });
	</script>";
	$writablescript="<script>
		$(document).ready(function(e) {
			var item_clicked = 'false';
			var item_text = '';
			var item_id = '';
			$('#wvouchercode').val('');
			$('#wbudgetcode').val('');
			$('#wvoucheramount').val('');
			//$('#budgettype').val($budget_cat);
			//$('#budgetyear').val($budget_year);
			
			$('.item').click(function(e) {
				$('.item').removeClass('apitem');
				item_id = $(this).attr('id');
				$(this).addClass('apitem');
				item_clicked = 'true';
				item_text = $(this).text();
				//alert( $(this).attr('id') );
			});
			
			$('.itemx').click(function(e) {
				$('.itemx').removeClass('apitemx');
				var itemx_id = $(this).attr('id');
				$(this).addClass('apitemx');
				var folio_code = $('#flcode_' + itemx_id).val();
				//alert( $('#flcode_' + itemx_id).val() ); 

				  if(item_clicked == 'true'){
					  if(confirm('Are you sure you want to use this item?')){
						var cur_id=itemx_id; 						//get id of current budget item
						var b_amt=$('#bamount_' + cur_id).text();	//get amount left for budget item
						var dstr = item_text.split(':');			//naira value extraction
						var dstr2=dstr[1].split('N');
						var v_amt=dstr2[1];
						//var amt_left = $('#bamount_' + cur_id).text();	//get amount left
						var amt_left = $('#lamount_' + cur_id).text();
						//alert($('#extra_voucher').val());
						//if( (v_amt * 1) <= (amt_left * 1) || $('#extra_voucher').val() == 'Approved')
						{				//compare amount values
							//extra_voucher
							var v_amt_temp = $('#vamount_' + cur_id).text();   	//write voucher amount
							$('#vamount_' + cur_id).text(v_amt_temp = (v_amt_temp * 1) + (v_amt * 1));
							var l_amt = amt_left - v_amt;				//do the subration - temp
							$('#lamount_' + cur_id).text(l_amt);
							$('#vou_bud > tbody:last-child').append('<tr><td id=\"log_' + cur_id + '\">' + item_text + '</td><td id=\"log_' + item_id + '\">' + $(this).text() + '</td><td>' + v_amt + '</td></tr>');
							
							//$('#wvouchercode').val( $('#wvouchercode').val() + '^' + item_text);
							$('#wbudgetcode').val( $('#wbudgetcode').val() + '^' + $(this).text());
							$('#wvouchercode').val( $('#wvouchercode').val() + '^' + item_id);
							//$('#wbudgetcode').val( $('#wbudgetcode').val() + '^' + item_id );
							$('#wvoucheramount').val( $('#wvoucheramount').val() + '^' + v_amt);
							
							$('#' + item_id).remove();
							item_clicked = 'false';
							item_text = '';
							item_id = '';
							itemx_id = '';
							$('.itemx').removeClass('apitemx');
							$('.item').removeClass('apitem');
							//alert( $('#wvoucheramount').val() );
						}
						
						/*else {
							if(confirm('INSUFFICIENT FUND! Click \'OK\' button to request extra budget allocation from Bursar.')){
								swapcontent('send_voucher_to_bursar', $('#pvno').val(), folio_code, $('#budgettype').val(), $('#budgetyear').val(), $('#budgetdept').val());
								//alert('Voucher sent to Bursar!');
							}else{ alert('Invalid Transaction: Voucher amount is above budget balance!'); }
						}//end if for amount check*/
						
						
					  }//end if for text='' check
				  }
			});
		});
	</script>";
	echo $writable.$writablestyle.$writablescript;
	//echo $writable.$writablescript;	
exit;
}

if($id == 'read_budget')
{

	$writable="";
	$budget_cat =  mysqli_real_escape_string($con, $_REQUEST['budget_cat']);
	$budget_year =  mysqli_real_escape_string($con, $_REQUEST['budget_year']);
	$budget_dept =  mysqli_real_escape_string($con, $_REQUEST['deptcode']);
	$pvno =  mysqli_real_escape_string($con, $_REQUEST['pvno']);
	$code_cat =  mysqli_real_escape_string($con, $_REQUEST['code_cat']);
	//echo "select f.title, f.folio_code, b.id, b.amount from budget_capitaltb b inner join foliotb f on b.folio_code = f.folio_code where b.year='$budget_year' and f.category='$code_cat' and bursary_category='Capital' order by f.folio_code"; exit;
	$extra_voucher = $bursary->get_any_value('approval_status', 'voucher_extra_allocation_requesttb', 'pvno', $pvno);
		echo "<input name='budgettype' id='budgettype' type='hidden' value='$budget_cat' />";
	   	echo "<input name='budgetyear' id='budgetyear' type='hidden' value='$budget_year' />";
	   	echo "<input name='budgetdept' id='budgetdept' type='hidden' value='$budget_dept' />";
		echo "<input name='pvno' id='pvno' type='hidden' value='$pvno' />";
		echo "<input name='extra_voucher' id='extra_voucher' type='hidden' value='$extra_voucher' />";

if($budget_cat == "Departmental"){
		$qry="SELECT f.department_name AS title, f.department_code AS folio_code, b.id, sum(b.amount) as amt FROM budgettb b INNER JOIN account_departments f on b.folio_code = f.department_code WHERE b.budget_year='$budget_year'";
		if($budget_dept != '') $qry .= " AND dept_code='$budget_dept' ";
		
		$code_cat='';
	}else
		$qry="SELECT f.title, f.folio_code, sum(b.amount) AS amt, b.id FROM budgettb b inner join foliotb f on b.folio_code = f.folio_code WHERE b.budget_year='$budget_year' ";			   

	if($budget_cat != '') $qry .= " AND bursary_category='$budget_cat' ";
	if($code_cat != '') $qry .= " AND bursary_sub_category='$code_cat' ";

	
	/*echo "<script>alert('$budget_dept');</script>";
	
	echo "Dept: ".$budget_dept."<br>";
	echo "Category: ".$budget_cat."<br>";
	echo "Year: ".$budget_year."<br>";*/
	
	//$sql="";
	/*if(strtolower(trim($budget_cat)) == "xxrefund" && $budget_year != ''){
		//section for department budget
		if($budget_dept != ''){
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <!--td style='text-align:left; font-weight:bold;' align='left'>Target Area</td-->
				   <td style='text-align:left; font-weight:bold;' align='left'>Budgeted Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Voucher Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   //use this if folio code in budgettb must match folio codes in foliotb
				   //$sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.unit_code='$budget_dept' and b.budget_year='$budget_year'");

				   //use this to folio code in budgettb without deending on folio codes in foliotb
				   $sql =  mysqli_query($con, "SELECT folio_code, SUM(amount), dept_code from budgettb WHERE dept_code='$budget_dept' and budget_year='$budget_year' GROUP BY dept_code, budget_year");
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	//<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>
					".$rec['dept_code'].": ".get_dept_name($rec['dept_code'])."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['dept_code']."' /></td>
					<!--td class='drop' id='".$rec['id']."' name='".$rec['id']."'></td-->
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<!--<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$rec['amount']."</td>-->
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left' id='vamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '".$budget_dept."' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
		}
	}
	else*/if(strtolower(trim($budget_cat)) == "refund"){
		//section for capital budget
		//echo "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and f.category='$code_cat' and bursary_category='Capital' order by f.folio_code"; exit;
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <!--td style='text-align:left; font-weight:bold;' align='left'>Target Area</td-->
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Voucher Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   $sqll = "select f.title, f.folio_code, f.id from foliotb f  WHERE f.folio_code LIKE '09-000-%' AND f.status='Active' ORDER BY f.folio_code";
				   $sql =  mysqli_query($con, $sqll);
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   ///$budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<!--td class='drop' id='".$rec['id']."' name='".$rec['id']."'></td-->
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>0</td>
					<!--<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>0</td>-->
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='vamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '0', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	elseif(strtolower(trim($budget_cat)) == "xothers" && $budget_year != ''){
		//section for administrative budget
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <!--td style='text-align:left; font-weight:bold;' align='left'>Target Area</td-->
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Voucher Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   $sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and bursary_category='Others'");
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<!--td class='drop' id='".$rec['id']."' name='".$rec['id']."'></td-->
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<!--<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$rec['amount']."</td>-->
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left' id='vamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	else{
		$bcat=strtolower(trim($budget_cat));
		if($bcat == "departmental") $bcode = $_REQUEST['budget_dept'];
		//else $bcode = $_REQUEST['fundsource']."-".$_REQUEST['deptcode']."-".$_REQUEST['itemcode'];
		//echo $bcode;
		//section for capital budget
		//echo "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and f.category='$code_cat' and bursary_category='Capital' order by f.folio_code"; exit;
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <!--td style='text-align:left; font-weight:bold;' align='left'>Target Area</td-->
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Spent</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Voucher Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   /*if($bcat == "departmental")
				   $ssql="select d.department_name AS title, d.department_code AS folio_code, b.id, sum(b.amount) as amt from budgettb b inner join account_departments d on b.folio_code = d.department_code where b.budget_year='$budget_year' and folio_code='$bcode' group by folio_code order by d.department_code";
				   else
				   $ssql="select f.title, f.folio_code, sum(b.amount) as amt, b.id from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and bursary_category='$budget_cat' group by f.folio_code order by f.folio_code";
				   echo $qry;*/
				   
				   if($budget_cat == "Departmental") $qry .= " GROUP BY f.department_name, f.department_code, b.id ";
					else $qry .= "  GROUP BY f.title, f.folio_code, b.id ";
				   //echo $qry;
				   $sql =  mysqli_query($con, $qry);
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $amountSpent = $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')));
					   $budget_amount_left = $rec['amt'] - $amountSpent; ///(($rec['amount']/100.0) * 15.0) - $amountSpent ;
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<!--td class='drop' id='".$rec['id']."' name='".$rec['id']."'></td-->
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".number_format($rec['amt'], 2)."</td>
					<!--<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".number_format($rec['amt'], 2)."</td>-->
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".number_format($amountSpent, 2)."</td>
					<td style='text-align:left;' align='left' id='vamount_".$rec['id']."'>0</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".number_format($budget_amount_left, 2)."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amt']."', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	
       $writablestyle="<style type='text/css'>
		.left{
            width:120px;
            float:left;
			vertical-align:top;
			padding:10px;
        }
        .left table{
            background:#E0ECFF;
			vertical-align:top;
        }
        .left td{
            background:#eee;
        }
		.right table{
            background:#E0ECFF;
            width:100%;
        }
        /*.right{
            float:right;
            width:370px;
        }
        
        .right td{
            background:#fafafa;
            color:#444;
            text-align:center;
            padding:2px;
        }
        .right td{
            background:#E0ECFF;
        }
        .right td.drop{
            background:#fafafa;
            width:100px;
        }
        .right td.over{
            background:#FBEC88;
        }*/
        .item{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            width:98%;
        }
		.item:hover,.item:focus{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		.apitem{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		
        .itemx{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            /*width:98%;*/
        }
		.itemx:hover,.itemx:focus{
            border:1px solid #C00;
            background:#CC9;
            color:#C00;
        }
		.apitemx{
            border:1px solid #C00;
            background:#CC9;
            color:#444;
        }
        .assigned{
            border:1px solid #BC2A4D;
        }
        .trash{
            background-color:red;
        }
		#vou_bud th{
            /*border:1px solid #C00;*/
            background:#cc9;
            color:#444;
        }
		.btn {
			  background: #3498db;
			  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
			  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
			  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
			  background-image: -o-linear-gradient(top, #3498db, #2980b9);
			  background-image: linear-gradient(to bottom, #3498db, #2980b9);
			  -webkit-border-radius: 15;
			  -moz-border-radius: 15;
			  border-radius: 15px;
			  text-shadow: 1px 1px 3px #666666;
			  font-family: Georgia;
			  color: #ffffff;
			  font-size: 12px;
			  padding: 5px 10px 5px 10px;
			  text-decoration: none;
			}
			
			.btn:hover {
			  background: #3cb0fd;
			  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
			  text-decoration: none;
			}
	</style>";
	$writablescript="<script>
	        $(function(){
            $('.left .item').draggable({
                revert:true,
                proxy:'clone',
				onStopDrag:function(){
					//alert(123);
				}
            });
            $('.right td.drop').droppable({
                accept: '.item',
				revert:true,
                onDragEnter:function(){
                    $(this).addClass('over');
                },
                onDragLeave:function(){
				  /*if($(this).text() == '' ){
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var v_amt = $('#vamount_' + cur_id).text();
					var l_amt = $('#lamount_' + cur_id).text();
					var lx_amt = (l_amt * 1) + (v_amt * 1);
					$('#lamount_' + cur_id).text(lx_amt);
					$('#vamount_' + cur_id).text('');
				  }*/
                  $(this).removeClass('over');
                },
                onDrop:function(e,source){
				  if($(this).text() == ''){
                    $(this).removeClass('over');
					//alert($(this).attr('id'));
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var dstr=$(source).text().split(':');
					var dstr2=dstr[1].split('N');
					var v_amt=dstr2[1];
					
					//var v_amt = $('#vamount_' + cur_id).text();
					if((b_amt * 1) >= (v_amt * 1)){
						$('#vamount_' + cur_id).text(v_amt);
						var l_amt = b_amt - v_amt;
						$('#lamount_' + cur_id).text(l_amt);
						
						if ($(source).hasClass('assigned')){
							$(this).append(source);
						} else {
							var c = $(source).clone().addClass('assigned');
							$(this).empty().append(c);
							c.draggable({
								revert:false
							});
						}
					}else {
						$(this).removeClass('over');
						alert('Invalid Transaction: Voucher amount is above budget balance!');
					}//end if for amount check
				  }else {
					  $(this).removeClass('over');
					  alert('Invalid Transaction: Multiple transaction not allowed on same folio/code!');
				  }//end if for text='' check
                }
            });
            $('.left').droppable({
                accept:'.assigned',
                onDragEnter:function(e,source){
                    $(source).addClass('trash');
                },
                onDragLeave:function(e,source){
                    $(source).removeClass('trash');
                },
                onDrop:function(e,source){
					//var ids=document.getElementsByClassName('drop');
					var ids=document.getElementsByTagName('td')[0].id;
					alert(ids);
					//for (var i = 0; i < ids.length; i++) {
  //alert(ids[i].id);
//}
				   //alert($(source).attr('name'));
				  /*if($(this).text() == '' ){
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var v_amt = $('#vamount_' + cur_id).text();
					var l_amt = $('#lamount_' + cur_id).text();
					var lx_amt = (l_amt * 1) + (v_amt * 1);
					$('#lamount_' + cur_id).text(lx_amt);
					$('#vamount_' + cur_id).text('');
				  }*/
                    $(source).remove();
                }
            });
        });
	</script>";
	$writablescript="<script>
		$(document).ready(function(e) {
			var item_clicked = 'false';
			var item_text = '';
			var item_id = '';
			$('#wvouchercode').val('');
			$('#wbudgetcode').val('');
			$('#wvoucheramount').val('');
			//$('#budgettype').val($budget_cat);
			//$('#budgetyear').val($budget_year);
			
			$('.item').click(function(e) {
				$('.item').removeClass('apitem');
				item_id = $(this).attr('id');
				$(this).addClass('apitem');
				item_clicked = 'true';
				item_text = $(this).text();
				//alert( $(this).attr('id') );
			});
			
			$('.itemx').click(function(e) {
				$('.itemx').removeClass('apitemx');
				var itemx_id = $(this).attr('id');
				$(this).addClass('apitemx');
				var folio_code = $('#flcode_' + itemx_id).val();
				//alert( $('#flcode_' + itemx_id).val() ); 

				  if(item_clicked == 'true'){
					  if(confirm('Are you sure you want to use this item?')){
						var cur_id=itemx_id; 						//get id of current budget item
						var b_amt=$('#bamount_' + cur_id).text();	//get amount left for budget item
						var dstr = item_text.split(':');			//naira value extraction
						var dstr2=dstr[1].split('N');
						var v_amt=dstr2[1];
						//var amt_left = $('#bamount_' + cur_id).text();	//get amount left
						var amt_left = $('#lamount_' + cur_id).text();
						//alert($('#extra_voucher').val());
						//if( (v_amt * 1) <= (amt_left * 1) || $('#extra_voucher').val() == 'Approved')
						{				//compare amount values
							//extra_voucher
							var v_amt_temp = $('#vamount_' + cur_id).text();   	//write voucher amount
							$('#vamount_' + cur_id).text(v_amt_temp = (v_amt_temp * 1) + (v_amt * 1));
							var l_amt = amt_left - v_amt;				//do the subration - temp
							$('#lamount_' + cur_id).text(l_amt);
							$('#vou_bud > tbody:last-child').append('<tr><td id=\"log_' + cur_id + '\">' + item_text + '</td><td id=\"log_' + item_id + '\">' + $(this).text() + '</td><td>' + v_amt + '</td></tr>');
							
							//$('#wvouchercode').val( $('#wvouchercode').val() + '^' + item_text);
							$('#wbudgetcode').val( $('#wbudgetcode').val() + '^' + $(this).text());
							$('#wvouchercode').val( $('#wvouchercode').val() + '^' + item_id);
							//$('#wbudgetcode').val( $('#wbudgetcode').val() + '^' + item_id );
							$('#wvoucheramount').val( $('#wvoucheramount').val() + '^' + v_amt);
							
							$('#' + item_id).remove();
							item_clicked = 'false';
							item_text = '';
							item_id = '';
							itemx_id = '';
							$('.itemx').removeClass('apitemx');
							$('.item').removeClass('apitem');
							//alert( $('#wvoucheramount').val() );
						}
						
						/*else {
							if(confirm('INSUFFICIENT FUND! Click \'OK\' button to request extra budget allocation from Bursar.')){
								swapcontent('send_voucher_to_bursar', $('#pvno').val(), folio_code, $('#budgettype').val(), $('#budgetyear').val(), $('#budgetdept').val());
								//alert('Voucher sent to Bursar!');
							}else{ alert('Invalid Transaction: Voucher amount is above budget balance!'); }
						}//end if for amount check*/
						
						
					  }//end if for text='' check
				  }
			});
		});
	</script>";
	echo $writable.$writablestyle.$writablescript;
	//echo $writable.$writablescript;	
exit;
}

if($id == 'read_budget_votebook')
{

	$writable="";
	$budget_cat =  mysqli_real_escape_string($con, $_REQUEST['budget_cat']);
	$budget_year =  mysqli_real_escape_string($con, $_REQUEST['budget_year']);
	$budget_dept =  mysqli_real_escape_string($con, $_REQUEST['budget_dept']);
	$pvno =  mysqli_real_escape_string($con, $_REQUEST['pvno']);
	$code_cat =  mysqli_real_escape_string($con, $_REQUEST['code_cat']);
	$r_val = $_REQUEST['r_val'];
	
	$extra_voucher = $bursary->get_any_value('approval_status', 'voucher_extra_allocation_requesttb', 'pvno', $pvno);
		echo "<input name='budgettype' id='budgettype' type='hidden' value='$budget_cat' />";
	   	echo "<input name='budgetyear' id='budgetyear' type='hidden' value='$budget_year' />";
	   	echo "<input name='budgetdept' id='budgetdept' type='hidden' value='$budget_dept' />";
		echo "<input name='pvno' id='pvno' type='hidden' value='$pvno' />";
		echo "<input name='extra_voucher' id='extra_voucher' type='hidden' value='$extra_voucher' />";


	$qry="SELECT f.title, f.folio_code, b.id, SUM(b.amount) AS amount FROM budgettb b INNER JOIN foliotb f ON b.folio_code = f.folio_code WHERE b.budget_year='$budget_year' ";
	
	if($budget_cat == "Departmental"){
		$qry="SELECT f.department_name AS title, f.department_code AS folio_code, sum(b.amount) as amount FROM budgettb b INNER JOIN account_departments f on b.folio_code = f.department_code WHERE b.budget_year='$budget_year' ";
		if($budget_dept != '') $qry .= " AND dept_code='$budget_dept' ";
	}else
		$qry="SELECT f.title, f.folio_code, sum(b.amount) as amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code WHERE b.budget_year='$budget_year' ";			   

	if($budget_cat != '') $qry .= " AND bursary_category='$budget_cat' ";
	if($code_cat != '') $qry .= " AND bursary_sub_category='$code_cat' ";
	//if($code_cat != '') $qry .= " AND bursary_category='$code_cat' ";
	//if($budget_cat != '') $qry .= " AND bursary_category='$budget_cat' ";
	if($budget_cat == "Departmental"){
		$qry .= " GROUP BY f.department_name, f.department_code";
	}else
		$qry .= " GROUP BY f.title, f.folio_code ";			   

	//echo $qry;
	if($qry != ''){
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Item Description</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Budgeted Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Spent</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Action</td></tr>";
				   //use this if folio code in budgettb must match folio codes in foliotb
				   //$sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.unit_code='$budget_dept' and b.budget_year='$budget_year'");

				   //use this to folio code in budgettb without deending on folio codes in foliotb
				   /*if($budget_cat == "Departmental") $sql =  mysqli_query($con, $qry." GROUP BY f.department_code ");
				   else $sql =  mysqli_query($con, $qry." GROUP BY f.folio_code ");
				   */
				  $sql = mysqli_query($con, $qry);
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   $folio=$rec['folio_code'];
					   $rid=$folio; //$rec['id'];
					   
					   $sq="select sum(amount) as spent from budget_votebooktb where budget_folio_code='". mysqli_real_escape_string($con, $folio).
			"' and operation_year='". mysqli_real_escape_string($con, $budget_year)."'"; 
						$qr=@mysqli_query($con, $sq); $amountSpent=0;
						if($row =  mysqli_fetch_array($qr, 3 )) $amountSpent = $row[0];

					   $budget_amount_left = $rec['amount'] - $amountSpent;
					   //$budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	//<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					(get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
					
					$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$folio."' name='".$folio."'>
					".$folio.": ".$folio_name."<input name='flcode_".$folio."' id='flcode_".$folio."' type='hidden' value='".$rec['dept_code']."' /></td>
					<td style='text-align:left;' align='left' id='bbamount_".$rid."'>".number_format($rec['amount'], 2)."</td>
					<td style='text-align:left;' align='left' id='bamount_".$rid."'>".number_format($amountSpent, 2)."</td>
					<td style='text-align:left;' align='left' id='lamount_".$rid."'>".number_format($budget_amount_left, 2)."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '".$budget_dept."' );\">Summary</a> | <a href='votebook_report_s.php?folio={$folio}&rid={$rid}&year={$budget_year}' target='_blank'>Expand</a> | <a href='votebook_report_t.php?folio={$folio}&rid={$rid}&year={$budget_year}&r_val={$r_val}' target='_blank'>View Vouchers</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
		}
	/***********************************************************************************************************************************
	if(strtolower(trim($budget_cat)) == "recurrent" && $budget_year != ''){
		//section for department budget
		if($budget_dept != ''){
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Budgeted Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   //use this if folio code in budgettb must match folio codes in foliotb
				   //$sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.unit_code='$budget_dept' and b.budget_year='$budget_year'");

				   //use this to folio code in budgettb without deending on folio codes in foliotb
				   $sql =  mysqli_query($con, "select folio_code, id, amount, dept_code from budgettb where dept_code='$budget_dept' and budget_year='$budget_year'");
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	//<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					
					$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>
					".$rec['dept_code'].": ".get_dept_name($rec['dept_code'])."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['dept_code']."' /></td>
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '".$budget_dept."' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
		}
	}
	elseif(strtolower(trim($budget_cat)) == "capital budget" && $budget_year != ''){
		//section for capital budget
		//echo "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.year='$budget_year' and bursary_category='Capital' order by f.folio_code"; exit;
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   $sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and bursary_category='Capital' order by f.folio_code");
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	elseif(strtolower(trim($budget_cat)) == "others" && $budget_year != ''){
		//section for administrative budget
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   $sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and bursary_category='Others'");
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	else if(strtolower(trim($budget_cat)) == "capital budget" && $budget_year != ''){
		//section for capital budget
		//echo "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.year='$budget_year' and bursary_category='Capital' order by f.folio_code"; exit;
			$writable = "
				   <table width='100%' cellspacing='0' cellpadding='0' border='1' align='center'>
				   <tr>
				   <td style='text-align:left; font-weight:bold;' align='left'>Folio/Code</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Budget Amount</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Amount Left</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>Balance</td>
				   <td style='text-align:left; font-weight:bold;' align='left'>View Summary</td></tr>";
				   $sql =  mysqli_query($con, "select f.title, f.folio_code, b.id, b.amount from budgettb b inner join foliotb f on b.folio_code = f.folio_code where b.budget_year='$budget_year' and bursary_category='Recurrent' and bursary_sub_category='$budget_cat' order by f.folio_code");
				   
				   while($rec= mysqli_fetch_array($sql, 3 )){
					   //$budget_amount_left = $rec['amount'] - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat) );
					   $budget_amount_left = (($rec['amount']/100.0) * 15.0) - $bursary->get_votebook_sum($rec['folio_code'], $budget_year, trim($budget_cat), get_quarter(date('m')) );
				   	$writable .= "<tr>
					<td class='itemx' style='text-align:left;' align='left' id='".$rec['id']."' name='".$rec['id']."'>".$rec['folio_code'].": ".$rec['title']."<input name='flcode_".$rec['id']."' id='flcode_".$rec['id']."' type='hidden' value='".$rec['folio_code']."' /></td>
					<td style='text-align:left;' align='left' id='bbamount_".$rec['id']."'>".$rec['amount']."</td>
					<td style='text-align:left;' align='left' id='bamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left' id='lamount_".$rec['id']."'>".$budget_amount_left."</td>
					<td style='text-align:left;' align='left'><a href='#' onclick=\"swapcontent('folio_summary', '".$rec['folio_code']."', '".$budget_cat."', '".$rec['amount']."', '".$budget_year."', '".$budget_cat."', '' );\">Summary</a></td>
					</tr>";
				   }
				   $writable .= "
				   </table>
				";   
	}
	************************************************************************************************************************************/
	
       $writablestyle="<style type='text/css'>
		.left{
            width:120px;
            float:left;
			vertical-align:top;
			padding:10px;
        }
        .left table{
            background:#E0ECFF;
			vertical-align:top;
        }
        .left td{
            background:#eee;
        }
		.right table{
            background:#E0ECFF;
            width:100%;
        }
        /*.right{
            float:right;
            width:370px;
        }
        
        .right td{
            background:#fafafa;
            color:#444;
            text-align:center;
            padding:2px;
        }
        .right td{
            background:#E0ECFF;
        }
        .right td.drop{
            background:#fafafa;
            width:100px;
        }
        .right td.over{
            background:#FBEC88;
        }*/
        .item{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            width:98%;
        }
		.item:hover,.item:focus{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		.apitem{
            border:1px solid #C00;
            background:#cc9;
            color:#444;
        }
		
        .itemx{
            text-align:center;
            border:1px solid #499B33;
            background:#fafafa;
            color:#444;
            /*width:98%;*/
        }
		.itemx:hover,.itemx:focus{
            border:1px solid #C00;
            background:#CC9;
            color:#C00;
        }
		.apitemx{
            border:1px solid #C00;
            background:#CC9;
            color:#444;
        }
        .assigned{
            border:1px solid #BC2A4D;
        }
        .trash{
            background-color:red;
        }
		#vou_bud th{
            /*border:1px solid #C00;*/
            background:#cc9;
            color:#444;
        }
		.btn {
			  background: #3498db;
			  background-image: -webkit-linear-gradient(top, #3498db, #2980b9);
			  background-image: -moz-linear-gradient(top, #3498db, #2980b9);
			  background-image: -ms-linear-gradient(top, #3498db, #2980b9);
			  background-image: -o-linear-gradient(top, #3498db, #2980b9);
			  background-image: linear-gradient(to bottom, #3498db, #2980b9);
			  -webkit-border-radius: 15;
			  -moz-border-radius: 15;
			  border-radius: 15px;
			  text-shadow: 1px 1px 3px #666666;
			  font-family: Georgia;
			  color: #ffffff;
			  font-size: 12px;
			  padding: 5px 10px 5px 10px;
			  text-decoration: none;
			}
			
			.btn:hover {
			  background: #3cb0fd;
			  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
			  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
			  text-decoration: none;
			}
	</style>";
	$writablescript="<script>
	        $(function(){
            $('.left .item').draggable({
                revert:true,
                proxy:'clone',
				onStopDrag:function(){
					//alert(123);
				}
            });
            $('.right td.drop').droppable({
                accept: '.item',
				revert:true,
                onDragEnter:function(){
                    $(this).addClass('over');
                },
                onDragLeave:function(){
				  /*if($(this).text() == '' ){
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var v_amt = $('#vamount_' + cur_id).text();
					var l_amt = $('#lamount_' + cur_id).text();
					var lx_amt = (l_amt * 1) + (v_amt * 1);
					$('#lamount_' + cur_id).text(lx_amt);
					$('#vamount_' + cur_id).text('');
				  }*/
                  $(this).removeClass('over');
                },
                onDrop:function(e,source){
				  if($(this).text() == ''){
                    $(this).removeClass('over');
					//alert($(this).attr('id'));
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var dstr=$(source).text().split(':');
					var dstr2=dstr[1].split('N');
					var v_amt=dstr2[1];
					
					//var v_amt = $('#vamount_' + cur_id).text();
					if((b_amt * 1) >= (v_amt * 1)){
						$('#vamount_' + cur_id).text(v_amt);
						var l_amt = b_amt - v_amt;
						$('#lamount_' + cur_id).text(l_amt);
						
						if ($(source).hasClass('assigned')){
							$(this).append(source);
						} else {
							var c = $(source).clone().addClass('assigned');
							$(this).empty().append(c);
							c.draggable({
								revert:false
							});
						}
					}else {
						$(this).removeClass('over');
						alert('Invalid Transaction: Voucher amount is above budget balance!');
					}//end if for amount check
				  }else {
					  $(this).removeClass('over');
					  alert('Invalid Transaction: Multiple transaction not allowed on same folio/code!');
				  }//end if for text='' check
                }
            });
            $('.left').droppable({
                accept:'.assigned',
                onDragEnter:function(e,source){
                    $(source).addClass('trash');
                },
                onDragLeave:function(e,source){
                    $(source).removeClass('trash');
                },
                onDrop:function(e,source){
					//var ids=document.getElementsByClassName('drop');
					var ids=document.getElementsByTagName('td')[0].id;
					alert(ids);
					//for (var i = 0; i < ids.length; i++) {
  //alert(ids[i].id);
//}
				   //alert($(source).attr('name'));
				  /*if($(this).text() == '' ){
					var cur_id=$(this).attr('id');
					var b_amt=$('#bamount_' + cur_id).text();
					var v_amt = $('#vamount_' + cur_id).text();
					var l_amt = $('#lamount_' + cur_id).text();
					var lx_amt = (l_amt * 1) + (v_amt * 1);
					$('#lamount_' + cur_id).text(lx_amt);
					$('#vamount_' + cur_id).text('');
				  }*/
                    $(source).remove();
                }
            });
        });
	</script>";
	$writablescript="<script>
		$(document).ready(function(e) {
			var item_clicked = 'false';
			var item_text = '';
			var item_id = '';
			$('#wvouchercode').val('');
			$('#wbudgetcode').val('');
			$('#wvoucheramount').val('');
			//$('#budgettype').val($budget_cat);
			//$('#budgetyear').val($budget_year);
			
			$('.item').click(function(e) {
				$('.item').removeClass('apitem');
				item_id = $(this).attr('id');
				$(this).addClass('apitem');
				item_clicked = 'true';
				item_text = $(this).text();
				//alert( $(this).attr('id') );
			});
			
			$('.itemx').click(function(e) {
				$('.itemx').removeClass('apitemx');
				var itemx_id = $(this).attr('id');
				$(this).addClass('apitemx');
				var folio_code = $('#flcode_' + itemx_id).val();
				//alert( $('#flcode_' + itemx_id).val() ); 

				  if(item_clicked == 'true'){

					  if(confirm('Are you sure you want to use this item?')){
						var cur_id=itemx_id; 						//get id of current budget item
						var b_amt=$('#bamount_' + cur_id).text();	//get amount left for budget item
						var dstr = item_text.split(':');			//naira value extraction
						var dstr2=dstr[1].split('N');
						var v_amt=dstr2[1];
						//var amt_left = $('#bamount_' + cur_id).text();	//get amount left
						var amt_left = $('#lamount_' + cur_id).text();
						//alert($('#extra_voucher').val());
						if( (v_amt * 1) <= (amt_left * 1) || $('#extra_voucher').val() == 'Approved'){				//compare amount values
							//extra_voucher
							var v_amt_temp = $('#vamount_' + cur_id).text();   	//write voucher amount
							$('#vamount_' + cur_id).text(v_amt_temp = (v_amt_temp * 1) + (v_amt * 1));
							var l_amt = amt_left - v_amt;				//do the subration - temp
							$('#lamount_' + cur_id).text(l_amt);
							$('#vou_bud > tbody:last-child').append('<tr><td id=\"log_' + cur_id + '\">' + item_text + '</td><td id=\"log_' + item_id + '\">' + $(this).text() + '</td><td>' + v_amt + '</td></tr>');
							
							//$('#wvouchercode').val( $('#wvouchercode').val() + '^' + item_text);
							$('#wbudgetcode').val( $('#wbudgetcode').val() + '^' + $(this).text());
							$('#wvouchercode').val( $('#wvouchercode').val() + '^' + item_id);
							//$('#wbudgetcode').val( $('#wbudgetcode').val() + '^' + item_id );
							$('#wvoucheramount').val( $('#wvoucheramount').val() + '^' + v_amt);
							
							$('#' + item_id).remove();
							item_clicked = 'false';
							item_text = '';
							item_id = '';
							itemx_id = '';
							$('.itemx').removeClass('apitemx');
							$('.item').removeClass('apitem');
							//alert( $('#wvoucheramount').val() );
						}else {
							if(confirm('INSUFFICIENT FUND! Click \'OK\' button to request extra budget allocation from Bursar.')){
								swapcontent('send_voucher_to_bursar', $('#pvno').val(), folio_code, $('#budgettype').val(), $('#budgetyear').val(), $('#budgetdept').val());
								//alert('Voucher sent to Bursar!');
							}else{ alert('Invalid Transaction: Voucher amount is above budget balance!'); }
						}//end if for amount check
					  }//end if for text='' check
				  }
			});
		});
	</script>";
	echo $writable.$writablestyle.$writablescript;
	//echo $writable.$writablescript;	
exit;
}

if($id=='commit_budget')
{
	$wvouchercode = $_REQUEST['wvouchercode'];
	$wbudgetcode = $_REQUEST['wbudgetcode'];
	$wvoucheramount = $_REQUEST['wvoucheramount'];
	$budgettype = $_REQUEST['budgettype'];
	$budgetyear = $_REQUEST['budgetyear'];
	$budgetdept = $_REQUEST['budgetdept'];
	$pvno = $_REQUEST['pvno'];
	$login_id=@$_SESSION['login_id'];
	$operation = $_REQUEST['operation'];
	$comment = $_REQUEST['query_txt'];
	//$test = "Sample Output::: <br>".$wvouchercode .'<br>'. $wbudgetcode .'<br>'. $wvoucheramount .'<br>'. $budgettype .'<br>'. $budgetyear;
	
	/*echo "<script>alert( $('#wvouchercode').val() );</script>";*/
	//$('#display').window('close');
	//echo $test;
	if(!isset($_SESSION['login_id']) or $_SESSION['login_id'] == ''){
		echo "<script>alert('Session timeout. You will have login again!'); window.location='index.php';</script>"; exit;
	}
 if($operation == "COMMIT"){
  $s= mysqli_query($con, "SELECT * FROM budget_votebooktb WHERE voucher_pvno='' AND operation_year=''");
  if( mysqli_num_rows($s) <= 0){
	$v_code = explode('^', $wvouchercode);		//CONVERT STRING TO ARRAY
	$b_code = explode('^', $wbudgetcode);
	$v_amnt = explode('^', $wvoucheramount);
	$controlFLAG = false;
	begin();	
	 for($i = 1; $i < count($v_code); $i++){
		//READ BUDGET CODE FOR DEDUCTION

		$budget_folio_code = explode(':', $b_code[$i]);
		//echo "B: ".$budget_folio_code[0];
		$sql[$i] = "insert into `budget_votebooktb` set voucher_folio_code = '".$v_code[$i].
		"', budget_folio_code = '".$budget_folio_code[0]."', amount = ".$v_amnt[$i].", voucher_pvno = '".
		$pvno."', budget_category = '".$budgettype."', operation_year = '".$budgetyear.
		"', operation_month = '".date('m')."', operation_quarter = '".get_quarter(date('m')).
		"', status = 'PAID', entry_by = '".$login_id."', entry_date = now(), entry_time = now()";
		if( mysqli_query($con, $sql[$i])) $controlFLAG = true;
		else {
			$controlFLAG = false;
			break;
		}
	}
	//update vouchertb here
	$vsql = "update vouchertb set controlled_by = '$login_id', controlled_action='Approved', controlled_remark='$comment', date_controlled=CURDATE(), time_controlled=CURTIME() where pvno='$pvno'";
	//update voucher_extra_allocation_requesttb here
	$vesql = "update voucher_extra_allocation_requesttb set commit_by = '$login_id', commit_status='Committed', commit_date=CURDATE(), commit_time=CURTIME() where pvno='$pvno'";
	 
	if( mysqli_query($con, $vsql) and  mysqli_query($con, $vesql) and $controlFLAG) $controlFLAG = true;
		else $controlFLAG = false;

	if($controlFLAG){
		commit();
		logs("$login_id","Expenditure Control","$login_id controlled voucher expenditure. PVNo: $pvno");
		echo "<script>alert('Operation Successful!'); $('#display').window('close');</script>";
	}else{
		rollback();
		echo "<script>alert('Operation Failed! Transaction was canceled.'); $('#display').window('close');</script>";
	}
  }else{
	  echo "<script>alert('Operation Successful!'); $('#display').window('close');</script>";
  }
 }else if($operation == "QUERY"){
	  //QUERRIED VOUCHER SECTION
	  echo $comment;
	if($comment != ''){
	  $vsql =  mysqli_query($con, "update vouchertb set controlled_by = '$login_id', controlled_action='Queried', controlled_remark='$comment', date_controlled=CURDATE(), time_controlled=CURTIME() where pvno='$pvno'");
	  logs("$login_id","Voucher Queried", "$login_id Queried voucher at expenditure control stage. PVNo: $pvno");
	  echo "<script>alert('Operation Successful! Voucher Queried.'); $('#display').window('close');</script>";
	}else{
		echo "<script>alert('Comment required for Queried Voucher.');</script>";
	}
  }
  exit;
}

if($id=='commit_budget_transfer')
{
	$wvouchercode = $_REQUEST['wvouchercode'];
	$wbudgetcode = $_REQUEST['wbudgetcode'];
	$wvoucheramount = $_REQUEST['wvoucheramount'];
	$budgettype = $_REQUEST['budgettype'];
	$budgetyear = $_REQUEST['budgetyear'];
	$budgetdept = $_REQUEST['budgetdept'];
	$pvno = $_REQUEST['pvno'];
	$login_id=@$_SESSION['login_id'];
	$operation = $_REQUEST['operation'];
	$comment = $_REQUEST['query_txt'];
	$voteID = $_REQUEST['voteID'];
	//$test = "Sample Output::: <br>".$wvouchercode .'<br>'. $wbudgetcode .'<br>'. $wvoucheramount .'<br>'. $budgettype .'<br>'. $budgetyear;
	
	/*echo "<script>alert( $('#wvouchercode').val() );</script>";*/
	//$('#display').window('close');
	//echo $test;
 if($operation == "COMMIT"){
	$v_code = explode('^', $wvouchercode);		//CONVERT STRING TO ARRAY
	$b_code = explode('^', $wbudgetcode);
	$v_amnt = explode('^', $wvoucheramount);
	$controlFLAG = false;
	begin();	
	 for($i = 1; $i < count($v_code); $i++){
		//READ BUDGET CODE FOR DEDUCTION

		$budget_folio_code = explode(':', $b_code[$i]);
		//echo "B: ".$budget_folio_code[0];
		$sql[$i] = "UPDATE `budget_votebooktb` SET voucher_folio_code = '".$v_code[$i].
		"', budget_folio_code = '".$budget_folio_code[0]."', amount = ".$v_amnt[$i].", voucher_pvno = '".
		$pvno."', budget_category = '".$budgettype."', operation_year = '".$budgetyear.
		"' WHERE id={$voteID}";
		if( mysqli_query($con, $sql[$i])) $controlFLAG = true;
		else {
			$controlFLAG = false;
			break;
		}
	}

	if($controlFLAG){
		commit();
		logs("$login_id","Voucher Commit Transfer","$login_id transfered voucher expenditure. PVNo: $pvno");
		echo "<script>alert('Operation Successful!'); $('#display').window('close');</script>";
	}else{
		rollback();
		echo "<script>alert('Operation Failed! Transaction was canceled.'); $('#display').window('close');</script>";
	}
  }
  exit;
}


if($id=='month_breakdown')
{
	$month_code =  mysqli_real_escape_string($con, $_REQUEST['months']);
	$action =  mysqli_real_escape_string($con, $_REQUEST['action']);
	$q = array('', '1st Quarter', '2nd Quarter', '3rd Quarter', '4th Quarter');
	//var_dump($q);
	$q_c = 0; $qcount=1;
	echo "====== New  Definition Preview ======<br>";
	echo "<table width='90%' align='left' border='1' cellspacing='0' cellpadding='3' rules='rows' frame='hsides'>
			<tr><th align='left'>Months</th><th align='left'>Quarter</th></tr>";
	$res_c= mysqli_query($con, "select * from monthtb where month_code >= $month_code order by month_code") or die( mysqli_error($con));
	while($rs_c=@mysqli_fetch_array($res_c)) {
		$q_c++; if($q_c > 3) { $q_c = 1; $qcount++; }
	  	$m_code=@$rs_c['month_code']; $title=@$rs_c['month_name'];
	  	//echo $title."::".$month_code."<br>";
	  	echo "<tr><td align='left'>$title</td><td align='left'>".$q[$qcount]."</td></tr>";
		if($action == "update")  mysqli_query($con, "update monthtb set quarter='". mysqli_real_escape_string($con, $q[$qcount])."' where month_code='". mysqli_real_escape_string($con, $m_code)."'");
	}
	$res_c= mysqli_query($con, "select * from monthtb where month_code < $month_code order by month_code") or die( mysqli_error($con));
	while($rs_c=@mysqli_fetch_array($res_c))	{
		$q_c++; if($q_c > 3) { $q_c = 1; $qcount++; }
	  	$m_code=@$rs_c['month_code']; $title=@$rs_c['month_name'];
	  	//echo $title."::".$month_code."<br>";
	  	echo "<tr><td align='left'>$title</td><td align='left'>".$q[$qcount]."</td></tr>";
		if($action == "update")  mysqli_query($con, "update monthtb set quarter='". mysqli_real_escape_string($con, $q[$qcount])."' where month_code='". mysqli_real_escape_string($con, $m_code)."'");
	}
	echo "</table>
	<p>
	<input class='easyui-linkbutton' iconCls='icon-ok' type='button' value='Update' onclick=\"if(confirm('Do you want to continue with this update?')){ swapcontent('month_breakdown', '".$month_code."', 'update'); }\"></p>";
	if($action == "update") echo "<script>alert('Done!')</script>";
	exit;
}

if($id=='folio_summary'){	//folio_summary
	$folio_code = $_REQUEST['folio_code'];
	$amount = $_REQUEST['amount'];
	$month = date('m');
	$percent_15 = ($amount/100.00) * 15.00;
	$budget_year = $_REQUEST['budget_year'];
	$budget_type = $_REQUEST['budget_type'];
	
	echo generate_budget_summary($folio_code, $budget_year, $budget_type, $amount);
	exit;
} 

if($id=='send_voucher_to_bursar'){	//send_voucher_to_bursar if cannot commit to votebook
	$pvno =  mysqli_real_escape_string($con, $_REQUEST['pvno']);
	$memo_id =  mysqli_real_escape_string($con,  $bursary->get_any_value("memo_id", "vouchertb", "pvno", $pvno) );
	$folio_code =  mysqli_real_escape_string($con, $_REQUEST['folio_code']);
	$requested_by =  mysqli_real_escape_string($con, $_SESSION['login_id']);
	$budget_type =  mysqli_real_escape_string($con, $_REQUEST['budget_type']);
	$budget_dept =  mysqli_real_escape_string($con, $_REQUEST['budget_dept']);
	$operation_quarter =  mysqli_real_escape_string($con, get_quarter(date('m')));
	$operation_year =  mysqli_real_escape_string($con, $_REQUEST['budget_year']);
	$amount =  mysqli_real_escape_string($con,  $bursary->get_any_value("amount_approved", "vouchertb", "pvno", $pvno) );
	if($bursary->get_any_value('pvno', 'voucher_extra_allocation_requesttb', 'pvno', $pvno) == ''){
		$sql = "insert into voucher_extra_allocation_requesttb set memo_id='".$memo_id."', pvno='".
		$pvno."', folio_code='".$folio_code."', amount=".$amount.", requested_by='".$requested_by.
		"', budget_type='".$budget_type."', budget_dept='".$budget_dept."', operation_quarter='".
		$operation_quarter."', operation_year='".$operation_year."', requested_date=now(), requested_time=now()";
		//echo $sql;
		if( mysqli_query($con, $sql)){
			echo "<script>alert('Operation successful!'); $('#display').window('close');</script>";
		}else{
			echo "<script>alert('Operation failed!'); $('#display').window('close');</script>";
		}
	}else{
		//echo "<strong style='color:red;font-size:18px;'>Duplicate!</strong>";
		echo "<script>alert('Duplicate entry!'); $('#display').window('close');</script>";
	}
	exit;
} //end send_voucher_to_bursar
	?>
	
