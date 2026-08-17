<SCRIPT language="javascript">
$(function(){
 
    // add multiple select / deselect functionality
  $("#selectall").click(function () {
var checkAll = $("#selectall").prop('checked');
    if (checkAll) {
        $(".case").prop("checked", true);
    } else {
        $(".case").prop("checked", false);
    }
});



	$( "#tabss" ).tabs({
      collapsible: true,
	  //event: "mouseover",  
    });
	$( "#tabs" ).tabs({
      collapsible: true,
	  //event: "mouseover",  
    });
$("#date_promoted").datepicker({dateFormat:"yy-mm-dd"});
});

</SCRIPT>   

<?php
 @session_start();
 @ini_set('max_execution_time', 60000000000);
 @ini_set("memory_limit", "51200M");
 
 @require_once('connect.php');
 @require_once('function_b.php');
 @require_once('class/mysqli_class.php');
$db = new Database();
$db->connect();
// @require_once ("required_jQuery_files.php");
 $id=@$_REQUEST['contentvar'];
 $contentvar=@$_REQUEST['contentvar'];
 $log_date=date('l, F d, Y');$log_time=date('h:i:s a');$log_date2=date('Y-m-d');
 $cursession=@$_SESSION['cur_session'];
$login_id= $_SESSION['login_id'];

@require_once "myclass_m.php";
	@$bursary = new myclass_m();
/*$db->select("current_settingstb","*",null);
$j_current=@json_decode($db->getResult());$j_current_data=@json_decode($j_current->data);
$cursession=@$j_current_data->session;$cursemester=@$j_current_data->semester;
 * 
 */
function smsalert($msg,$phoneno){
	$msg=@rawurlencode($msg);
	$phoneno="+234".@substr($phoneno,-10);
	$sender=@rawurlencode('UNILORIN');
 $r=@file_get_contents("http://api.smartsmssolutions.com/smsapi.php?username=jmklaru&password=0712764&sender=$sender&recipient=$phoneno&message=$msg");
}

  
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
		  
		  
		  @mysqli_query($con, "update $table set last_login_date='$log_date',last_login_time='$log_time',online_status='Off' where $update_field='$login_id' limit 1");
	 	  @mysqli_query($con, "insert into portal_logstb (regno,log_type,log_desc,log_date,log_date_desc,log_time,entry_by) values ('$login_id','Portal Logout','Logout by $login_id on $log_date at exactly $log_time','$log_date2','$log_date','$log_time','$login_id')");
	  } //end of if staff or student logout
	  
	  
	  @session_unset(); @session_destroy();
      echo "<script> document.location='$ref';</script>";exit;
  } //end of logout
if($id=='display_spreadsheet')
{

	$matno=@$_REQUEST['matno'];
	$json_val=@array(matno=>$matno);
	$json_val=@json_encode($json_val); //encode the json data
	$json_base=@base64_encode($json_val);
	$hash=$matno;  //hash parameters
	$h_val=@hash("sha512",$hash);
	echo "<script>window.open('spreadsheet.php?id=$json_base&h_val=$h_val','_blank');</script>";
		
}// end of display Spreadsheet

/////////////////////////////////// Bursary Automation Management System ///////////////////////////////////////////
 $login_id=$_SESSION['login_id'];
 $log_date=date('Y-m-d');$log_time=date('h:i:s a');$log_date2=date('l, F d, Y');
if($id=='department_section')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	$sch_code=explode("***",$j->sch_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from departmenttb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$sch_code=@mysqli_real_escape_string($con, $ds['sch_code']);$dept_code=@mysqli_real_escape_string($con, $ds['dept_code']);
				$dept_name=@mysqli_real_escape_string($con, $ds['dept_name']);$category=@mysqli_real_escape_string($con, $ds['category']);
				//$r_id=@$ds['id'];
				//echo $r_id;
				$db->sql("select * from departmenttb where id = '$r_id'");
				$s=@json_decode(stripslashes($db->getResult()));
				//$s_data=@json_decode(stripslashes($s->data));
				//$s_array=array(s_detail=>"",msg=>"");
				//echo "yes====>$s->row====> $->data $sch_code $dept_name";
				if($s->row>=1)
				{
					
					$s_array['s_detail']=$s->data;$s_array['msg']='1';
				}
		}
	
	if($action=='save')
		{
			
			if( mysqli_query($con, "insert into departmenttb set sch_code='$sch_code[0]', dept_code='$j->dept_code',dept_name='$j->dept_name',category='$j->category',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
			{
				 mysqli_query($con, "insert into unittb set dept_code='$j->dept_code', unit_code='$j->dept_code',unit_name='$j->dept_name',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
				logs($login_id,"Save Record","Insert new Department: $j->dept_code,$j->dept_name ");
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
		
			//$json_val=@array(sch_code=>$sch_code,dept_code=>$dept_code,dept_name=>$dept_name,category=>$category,r_id=>$r_id);
			echo @json_encode($s_array); 
			exit;
			
		}//end of edit option
	if($action=='delete')
		{
			//
			if( mysqli_query($con, "delete from departmenttb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete record of a department: $dept_code,$dept_name ");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from departmenttb where sch_code = '$sch_code[0]' or dept_code = '$j->dept_code' or dept_name = '$j->dept_name' or category ='$j->category' order by sch_code,category,dept_name");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from departmenttb order by sch_code,category,dept_name");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "
				<table>
				<tr>
				<th>S/No</th>
				<th>School Code</th><th>Department Code</th><th>Department Name</th><th>Category</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$sch_code=@mysqli_real_escape_string($con, $rs['sch_code']);$dept_code=@mysqli_real_escape_string($con, $rs['dept_code']);
				$dept_name=@mysqli_real_escape_string($con, $rs['dept_name']);$category=@mysqli_real_escape_string($con, $rs['category']);
				$r_id=@$rs['id'];
				echo"<tr>
					<td>$sno</td><td>$sch_code</td><td>$dept_code</td><td>$dept_name</td><td>$category</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('department_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
//	echo "$mydata ==> $action ==> $r_id";
	
	
}// end of Department Section

if($id=='unit_section')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	$dept_code=explode("***",$j->dept_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from unittb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$dept_code=@mysqli_real_escape_string($con, $ds['dept_code']);$unit_code=@mysqli_real_escape_string($con, $ds['unit_code']);
				$unit_name=@mysqli_real_escape_string($con, $ds['unit_name']);
				$r_id=@$ds['id'];
		}
	
	if($action=='save')
		{
			
			if( mysqli_query($con, "insert into unittb set dept_code='$dept_code[0]', unit_code='$j->unit_code',unit_name='$j->unit_name',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
			{
				logs($login_id,"Save Record","Insert new Departmental Unit: Unit Code=>$j->unit_code,Unit Name =>$j->unit_name ");
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
		
			$json_val=@array(sch_code=>$sch_code,dept_code=>$dept_code,dept_name=>$dept_name,category=>$category,r_id=>$r_id);
			$json_val=@json_encode($json_val); 
			echo "s_detail=$json_val";
		}//end of edit option
	if($action=='delete')
		{
			//
			if( mysqli_query($con, "delete from unittb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete record of a departmental Unit: Unit Code :=>$unit_code,Unit Name :=>$unit_name ");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from unittb where dept_code = '$dept_code[0]' or unit_code = '$j->unit_code' or unit_name = '$j->unit_name' order by dept_code,unit_name");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from unittb order by dept_code,unit_name");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "
				<table>
				<tr>
				<th>S/No</th>
				<th>Department Code</th><th>Unit Code</th><th>Unit Name</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$dept_code=@mysqli_real_escape_string($con, $rs['dept_code']);$unit_code=@mysqli_real_escape_string($con, $rs['unit_code']);
				$unit_name=@mysqli_real_escape_string($con, $rs['unit_name']);
				$r_id=@$rs['id'];
				echo"<tr>
					<td>$sno</td><td>$dept_code</td><td>$unit_code</td><td>$unit_name</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('unit_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
//	echo "$mydata ==> $action ==> $r_id";
	
	
}// end of unit section
if($id=='folio_section')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$dept_code=explode("***",$j->dept_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from foliotb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);$title=@mysqli_real_escape_string($con, $ds['title']);
				$category=@mysqli_real_escape_string($con, $ds['category']);
				$r_ids=@$ds['id'];
		}
	
	if($action=='save')
		{
			
			if( mysqli_query($con, "insert into foliotb set folio_code='$j->folio_code', title='$j->title',category='$j->category',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
			{
				logs($login_id,"Save Record","Insert new Folio: Folio Code :=>$j->folio_code,Title :=>$j->title ");
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
		if($action=='save_category')
		{
			
			if( mysqli_query($con, "insert into folio_categorytb set folio_category='$j->folio_cat',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
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
		
			$json_val=@array(sch_code=>$sch_code,dept_code=>$dept_code,dept_name=>$dept_name,category=>$category,r_id=>$r_id);
			$json_val=@json_encode($json_val); 
			echo "s_detail=$json_val";
		}//end of edit option
	if($action=='delete')
		{
			//
			if( mysqli_query($con, "delete from foliotb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete folio record: Folio Code :=>$folio_code,Title :=>$title ");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
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
		if($action=='search_category')
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
		}// end of if search_category
		
		
		
		
		
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from foliotb where folio_code = '$j->folio_code' or title = '$j->title' or category = '$j->category' order by category,folio_code");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from foliotb order by category,folio_code");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "
				<table>
				<tr>
				<th>S/No</th>
				<th>Folio Code</th><th>Title</th><th>Category</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$folio_code=@mysqli_real_escape_string($con, $rs['folio_code']);$title=@mysqli_real_escape_string($con, $rs['title']);
				$category=@mysqli_real_escape_string($con, $rs['category']);
				$r_id=@$rs['id'];
				echo"<tr>
					<td>$sno</td><td>$folio_code</td><td>$title</td><td>$category</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('folio_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
//	echo "$mydata ==> $action ==> $r_id";
	
	
}// end of folio section
if($id=='bank_account_section')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	$sch_code=explode("***",$j->sch_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from bank_accounttb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$acctcode=@mysqli_real_escape_string($con, $ds['acctcode']);$acctno=@mysqli_real_escape_string($con, $ds['acctno']);
				$bankname=@mysqli_real_escape_string($con, $ds['bankname']);$accttype=@mysqli_real_escape_string($con, $ds['accttype']);
				$sortcode=@mysqli_real_escape_string($con, $ds['sortcode']);$acctname=@mysqli_real_escape_string($con, $ds['acctname']);
				//$r_id=@$ds['id'];
				//echo $r_id;
				$db->sql("select * from bank_accounttb where id = '$r_id'");
				$s=@json_decode(stripslashes($db->getResult()));
				//$s_data=@json_decode(stripslashes($s->data));
				$s_array=array(s_detail=>"",msg=>"");
				//echo "yes====>$s->row====> $->data $sch_code $dept_name";
				if($s->row>=1)
				{
					
					$s_array['s_detail']=$s->data;$s_array['msg']='1';
				}
		}
	
	if($action=='save')
		{
			
			if( mysqli_query($con, "insert into bank_accounttb set acctcode='$j->acctcode', acctno='$j->acctno',acctname='$j->acctname',bankname='$j->bankname',accttype='$j->accttype',sortcode='$j->sortcode',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
			{
				logs($login_id,"Save Record","Insert new Bank Account: Folio code :$j->accctcode,Account No:$j->acctno,Bank Name:$j->bankname ");
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
		
			//$json_val=@array(sch_code=>$sch_code,dept_code=>$dept_code,dept_name=>$dept_name,category=>$category,r_id=>$r_id);
			echo @json_encode($s_array); 
			exit;
			
		}//end of edit option
	if($action=='delete')
		{
			//
			if( mysqli_query($con, "delete from bank_accounttb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete Bank Account record : Folio code :$accctcode,Account No:$acctno,Bank Name:$bankname");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from bank_accounttb where acctcode = '$j->acctcode' or acctno = '$j->acctno' or acctname = '$j->acctname' or bankname = '$j->bankname' or accttype ='$j->accttype' or sortcode='$j->sortcode' order by acctcode,bankname");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from bank_accounttb order by acctcode,bankname");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "
				<table>
				<tr>
				<th>S/No</th>
				<th>Folio Code</th><th>Account Number</th><th>Account Name</th><th>Bank Name</th><th>Account Type</th><th>Sort Code</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$acctcode=@mysqli_real_escape_string($con, $rs['acctcode']);$acctno=@mysqli_real_escape_string($con, $rs['acctno']);
				$bankname=@mysqli_real_escape_string($con, $rs['bankname']);$accttype=@mysqli_real_escape_string($con, $rs['accttype']);
				$sortcode=@mysqli_real_escape_string($con, $rs['sortcode']);$acctname=@mysqli_real_escape_string($con, $rs['acctname']);
				$r_id=@$rs['id'];
				echo"<tr>
					<td>$sno</td><td>$acctcode</td><td>$acctno</td><td>$acctname</td><td>$bankname</td><td>$accttype</td><td>$sortcode</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('bank_account_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
	
	
}// end of Bank Accout Section

if($id=='accout_dept_section')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$dept_code=explode("***",$j->dept_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from account_depttb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$deptcode=@mysqli_real_escape_string($con, $ds['dept_acctcode']);$deptname=@mysqli_real_escape_string($con, $ds['deptname']);
				
				$r_ids=@$ds['id'];
		}
	
	if($action=='save')
		{
			
		if( mysqli_query($con, "insert into account_depttb set dept_acctcode='$j->deptcode', deptname='$j->deptname',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
			{
				logs($login_id,"Save Record","Insert new Account Department: Deptartment code: $j->deptcode,Department Name :=>$j->deptname ");
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
		
			
		}//end of edit option
	if($action=='delete')
		{
			//
			if( mysqli_query($con, "delete from account_depttb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete Account Department record: Dept Code :=>$deptcode,Dept Name :=>$deptname ");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
				
		
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from account_depttb where dept_acctcode = '$j->deptcode' or deptname = '$j->deptname' order by deptname");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from account_depttb order by deptname");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "
				<table>
				<tr>
				<th>S/No</th>
				<th>Department Code</th><th>Name</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$deptcode=@mysqli_real_escape_string($con, $rs['dept_acctcode']);$deptname=@mysqli_real_escape_string($con, $rs['deptname']);
				
				$r_id=@$rs['id'];
				echo"<tr>
					<td>$sno</td><td>$deptcode</td><td>$deptname</td><td> <a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('accout_dept_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
	
	
}// end of accout_dept_section
if($id=='account_allocation_section')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$dept_code=explode("***",$j->dept_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from account_allocationtb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$deptcode=@mysqli_real_escape_string($con, $ds['dept_acctcode']);$acctcode=@mysqli_real_escape_string($con, $ds['acctcode']);
				
				$r_ids=@$ds['id'];
		}
	
	if($action=='save')
		{
			
if( mysqli_query($con, "insert into account_allocationtb set dept_acctcode='$j->deptcode', acctcode='$j->account',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
			{
				logs($login_id,"Save Record","Insert new Account Allocation: Deptartment code: $j->deptcode,Account Code :=>$j->account ");
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
		
			
		}//end of edit option
	if($action=='delete')
		{
			//
			if( mysqli_query($con, "delete from account_allocationtb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete Account Allocation record: Dept Code :=>$deptcode,Account Code :=>$acctcode ");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
				
		
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from account_allocationtb where dept_acctcode = '$j->deptcode' or acctcode = '$j->account' order by deptcode");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from account_allocationtb order by dept_acctcode");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "
				<table>
				<tr>
				<th>S/No</th>
				<th>Department Code</th><th>Account Name</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$deptcode=@mysqli_real_escape_string($con, $rs['dept_acctcode']);$acctcode=@mysqli_real_escape_string($con, $rs['acctcode']);
				
				$r_id=@$rs['id'];
				echo"<tr>
					<td>$sno</td><td>$deptcode</td><td>".get_folio_name($acctcode)."<=>(".$acctcode.")</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('account_allocation_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
	
	
}// end of accout_allocation_section

if($id=='salary_scale_section')
{
	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$dept_code=explode("***",$j->dept_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from salary_scaletb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$level=@mysqli_real_escape_string($con, $ds['level']);$steps=@$ds['step'];
				$folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);$amount=@mysqli_real_escape_string($con, $ds['amount']);
				$category=@mysqli_real_escape_string($con, $ds['category']);
				$r_ids=@$ds['id'];
		}
	$vcode=@$_REQUEST['code'];$vamt=@$_REQUEST['amount'];
	$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
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
	$i=0;$j=0;$tamt=0;$emsg=array();
				if(count($vcode)>0)
					{
						
						foreach($vcode as $codeval)
							{
								$line=$i+1;
								if($vamt[$i] != "")
									{
										$code=$codeval;
										$amount=$vamt[$i];
										$j++;
										if( mysqli_query($con, "insert into salary_scaletb set scale_name='$scalename', level='$level',category='$category',step='$step',folio_code='$code',amount='$amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
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
							if($j>0)
								{
									logs($login_id,"Save Record","Insert Salary scale: level:$level,step :$step, Category :$category");
									echo "<script>alert('Record save successfully');</script>";
								}
							else{echo "<script>alert('Unable to save record');</script>";}
					}// end of folio code is not empty
		
			
			
			
			//@@@@@@@@@@@@

			
		}// end of save 
	
	if($action=='edit')
		{
		
			
		}//end of edit option
	if($action=='delete')
		{
			if( mysqli_query($con, "delete from salary_scaletb where id='$r_id'") or die( mysqli_error($con)))
			{
				logs($login_id,"Delete Record","Delete Salary Scale record: Folio Code :$folio_code, Amount :$amount, level :$level, Step: $steps, Category :$category ");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
				
		
	if($action=='search')
		{
			/*
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	//$r=@mysqli_query($con, "select * from salary_scaletb where deptcode = '$j->deptcode' or acctcode = '$j->account' order by deptcode");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select s.id,f.title,s.folio_code,s.amount from salary_scaletb s, foliotb f where f.folio_code=s.folio_code and s.scale_name='$scalename' and s.category='$category' and s.level='$level' and s.step='$step' order by s.folio_code");
		}// end of display all record
	if(@mysqli_num_rows($r)>0)
		{
			$tb= "
				<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' width='100%'>
				<tr>
				<th colspan='4'>($scalename)<br> Grade Level: $level Step: $step</th>
				</tr>
				<tr>
				<th>S/No</th>
				<th>Particulars</th><th>Amount (=N=)</th><th>Action</th>
				</tr>
			";
			$sno=0;$tamt=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$folio_code=@mysqli_real_escape_string($con, $rs['folio_code']);$amt=@mysqli_real_escape_string($con, $rs['amount']);
				$title=@$rs['title'];
				$r_id=@$rs['id'];
				$tamt +=$rs['amount'];
				$tb .="<tr>
					<td>$sno</td><td>$title <=> $folio_code</td><td>$amt</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('salary_scale_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			$tb .= "
			<tr><th colspan='2'>Total (=N=)</th><td colspan='2'><strong>".number_format($tamt,2)."</strong></td></tr>
			</table>";
			echo $tb;*/
			
		}// end of search with criterials
	if($action=='view_all')
		{
			//
			//$r= mysqli_query($con, "select s.id, f.title, s.folio_code, s.amount from salary_scaletb s inner join salary_codetb f on f.account_code = s.folio_code where s.scale_name='$scalename' and s.category='$category' and s.level='$level' and s.step='$step' order by s.folio_code") or die( mysqli_error($con));
			$r= mysqli_query($con, "select s.id, f.title, s.folio_code, s.amount from salary_scaletb s inner join foliotb f on f.folio_code = s.folio_code where s.scale_name='$scalename' and s.category='$category' and s.level='$level' and s.step='$step' order by s.folio_code") or die( mysqli_error($con));
		if( mysqli_num_rows($r) > 0)
			{
				//echo $step.$level.$scalename.$category;
				$tb= "
					<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' width='100%'>
					<tr>
					<th colspan='4' align='center'>$scalename $level Step: $step</th>
					</tr>
					<tr align='left'>
					<th>S/No</th>
					<th>Particulars</th><th>Amount (&#8358;)</th><th>Action</th>
					</tr>
				";
				$sno=0;$tamt=0;
				while($rs= mysqli_fetch_array($r))
				{
					$sno++;
					$folio_code=@mysqli_real_escape_string($con, $rs['folio_code']);$amt=@mysqli_real_escape_string($con, $rs['amount']);
					$title=@$rs['title'];
					$r_id=@$rs['id'];
					$tamt +=$rs['amount'];
					$tb .="<tr>
						<td>$sno</td><td>$title <=> $folio_code</td><td>$amt</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('salary_scale_section','delete',$r_id);\">Delete</a></td>
					</tr>";
					
					
				}// end of while
				$tb .= "
				<tr><th colspan='2'>Total (&#8358;)</th><td colspan='2'><strong>".number_format($tamt,2)."</strong></td></tr>
				</table>";
				echo $tb;
			}
		}
	
	
}// end of salary_scale_section

if($id=="salary_scale_name"){
	$action = $_REQUEST['action'];
	$sc = $_REQUEST['scalename'];
	$cat=$_REQUEST['category'];
	$r_id = $_REQUEST['r_id'];
	$s_id = $_REQUEST['id'];
	$stat = $_REQUEST['status'];
	$read=true;
	if($action=="save"){
		if($sc =='' or $cat == ''){
			echo "<script>alert('Scale name and category cannot be empty!');</script>";
			$read=false;
		}
		$sq = "insert into scale_nametb set scale_name='". mysqli_real_escape_string($con, $sc)."', category='". mysqli_real_escape_string($con, $cat)."', status='Active'";
	}elseif($action=="delete"){
		$sq = "delete from scale_nametb where id=". mysqli_real_escape_string($con, $r_id);
	}
	elseif($action=="update"){
		$sq = "update scale_nametb set status = '". mysqli_real_escape_string($con, $stat)."' where id=". mysqli_real_escape_string($con, $r_id);
	}
	if($read){
		if( mysqli_query($con, $sq)) echo "<script>alert('Operation successful!');</script>";
		else echo "<script>alert('Operation failed!');</script>";
	}
	{
		$q= mysqli_query($con, "select * from scale_nametb");
		  echo '<table width="100%" border="1" cellspacing="0" cellpadding="2" align="left" rules="rows" frame="box">
			<tr align="left">
			  <th>SN</th>
				      <th>Scale Name</th>
				      <th>Category</th>
				      <th>Status</th>
				      <th>Action</th>
			</tr>';
			$sn=0;
		while($r= mysqli_fetch_array($q, 3 )){
			$rid = $r['id'];
			echo '<tr align="left" ';
			if($r['status']=='Inactive') echo 'style="color:#F00"';
			echo '>
			  <td>'.++$sn.'</td>
			  <td>'.$r['scale_name'].'</td>
			  <td>'.$r['category'].'</td>
			  <td>'.$r['status'].'</td>
			  <td><a href="#" onClick="$(\'#status\').val(\'Active\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_scale_name\', \'update\', \''.$rid.'\');">Active</a> | 
			  <a href="#" onClick="$(\'#status\').val(\'Inactive\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_scale_name\', \'update\', \''.$rid.'\');">Inactive</a>
			  <!-- | 
			  <a href="#" onClick="$(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_scale_name\', \'delete\', \''.$rid.'\');">Delete</a>
			  -->
			  </td>
			</tr>';
		}
		  echo '</table>';
	}
}

if($id=='tax_section')
{
	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	//$amount=@$_REQUEST['amount'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$dept_code=explode("***",$j->dept_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from salary_taxtb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$level=@mysqli_real_escape_string($con, $ds['level']);$steps=@$ds['step'];
				$folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);$amount=@mysqli_real_escape_string($con, $ds['amount']);
				$category=@mysqli_real_escape_string($con, $ds['category']);
				$r_ids=@$ds['id'];
		}
	$vcode=@$_REQUEST['code'];$vamt=@$_REQUEST['amount'];
	$scalename=@$_REQUEST['scalename'];$category=@$_REQUEST['category'];$level=@$_REQUEST['level'];$step=@$_REQUEST['step'];
	//echo "$vcode ==> $vamt===>$mydata";exit;
	if($action=='save')
		{
			$r= mysqli_query($con, "select * from salary_taxtb where scale_name='". mysqli_real_escape_string($con, $scalename).
			"' and category='". mysqli_real_escape_string($con, $category)."' and level='". mysqli_real_escape_string($con, $level).
			"' and step='". mysqli_real_escape_string($con, $step)."'") or die( mysqli_error($con));
			if( mysqli_num_rows($r) > 0){
				$action='view_all';
				echo "<script>alert('Unable to save record due to duplicate record entry');</script>";
			}else{
				if( mysqli_query($con, "insert into salary_taxtb set scale_name='". mysqli_real_escape_string($con, $scalename).
				"', level='". mysqli_real_escape_string($con, $level)."', category='". mysqli_real_escape_string($con, $category).
				"', step='". mysqli_real_escape_string($con, $step)."', folio_code='". mysqli_real_escape_string($con, $code).
				"', amount='". mysqli_real_escape_string($con, $amount)."', entry_date=CURDATE(), entry_time=CURTIME(), ".
				"entry_by='". mysqli_real_escape_string($con, $login_id)."'"))
				{
					logs($login_id, "Save Record","Insert Salary scale: level:$level,step :$step, Category :$category");
					echo "<script>alert('Record save successfully');</script>";
				}else
					echo "<script>alert('Unable to save record');</script>";
			}
		}// end of save 
	
	if($action=='edit')
		{
		
			
		}//end of edit option
	if($action=='delete')
		{
			if( mysqli_query($con, "delete from salary_taxtb where id='$r_id'") or die( mysqli_error($con)))
			{
				logs($login_id,"Delete Record","Delete Salary Scale record: Folio Code :$folio_code, Amount :$amount, level :$level, Step: $steps, Category :$category ");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
				
		
	if($action=='search')
		{
			/*
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	//$r=@mysqli_query($con, "select * from salary_scaletb where deptcode = '$j->deptcode' or acctcode = '$j->account' order by deptcode");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select s.id,f.title,s.folio_code,s.amount from salary_scaletb s, foliotb f where f.folio_code=s.folio_code and s.scale_name='$scalename' and s.category='$category' and s.level='$level' and s.step='$step' order by s.folio_code");
		}// end of display all record
	if(@mysqli_num_rows($r)>0)
		{
			$tb= "
				<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' width='100%'>
				<tr>
				<th colspan='4'>($scalename)<br> Grade Level: $level Step: $step</th>
				</tr>
				<tr>
				<th>S/No</th>
				<th>Particulars</th><th>Amount (=N=)</th><th>Action</th>
				</tr>
			";
			$sno=0;$tamt=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$folio_code=@mysqli_real_escape_string($con, $rs['folio_code']);$amt=@mysqli_real_escape_string($con, $rs['amount']);
				$title=@$rs['title'];
				$r_id=@$rs['id'];
				$tamt +=$rs['amount'];
				$tb .="<tr>
					<td>$sno</td><td>$title <=> $folio_code</td><td>$amt</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('salary_scale_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			$tb .= "
			<tr><th colspan='2'>Total (=N=)</th><td colspan='2'><strong>".number_format($tamt,2)."</strong></td></tr>
			</table>";
			echo $tb;*/
			
		}// end of search with criterials
	if($action=='view_all')
		{
			$r= mysqli_query($con, "select s.id, f.title, s.folio_code, s.amount from salary_taxtb s inner join foliotb f on f.folio_code = s.folio_code where s.scale_name='$scalename' and s.category='$category' and s.level='$level' and s.step='$step' order by s.folio_code") or die( mysqli_error($con));
		if( mysqli_num_rows($r) > 0)
			{
				//echo $step.$level.$scalename.$category;
				$tb= "
					<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' width='100%'>
					<tr>
					<th colspan='4' align='center'>$scalename $level Step: $step</th>
					</tr>
					<tr align='left'>
					<th>S/No</th>
					<th>Particulars</th><th>Amount (&#8358;)</th><th>Action</th>
					</tr>
				";
				$sno=0;$tamt=0;
				while($rs= mysqli_fetch_array($r))
				{
					$sno++;
					$folio_code=@mysqli_real_escape_string($con, $rs['folio_code']);$amt=@mysqli_real_escape_string($con, $rs['amount']);
					$title=@$rs['title'];
					$r_id=@$rs['id'];
					$tamt +=$rs['amount'];
					$tb .="<tr>
						<td>$sno</td><td>$title <=> $folio_code</td><td>$amt</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('salary_scale_section','delete',$r_id);\">Delete</a></td>
					</tr>";
					
					
				}// end of while
				$tb .= "
				<tr><th colspan='2'>Total (&#8358;)</th><td colspan='2'><strong>".number_format($tamt,2)."</strong></td></tr>
				</table>";
				echo $tb;
			}
		}
	
	
}// end of salary_scale_section

if($id=="consolidated_pay"){
	$action = $_REQUEST['action'];
	$sc = $_REQUEST['code'];
	$cat=$_REQUEST['category'];
	$r_id = $_REQUEST['r_id'];
	$s_id = $_REQUEST['id'];
	$stat = $_REQUEST['status'];
	$read=true;
	if($action=="save"){
		if($sc =='' or $cat == ''){
			echo "<script>alert('Scale name and Account Code cannot be empty!');</script>";
			$read=false;
		}
		$w= mysqli_query($con, "select * from consolidated_paytb where allowance_code='". mysqli_real_escape_string($con, $sc)."' and category='". mysqli_real_escape_string($con, $cat)."'");
		if( mysqli_num_rows($w) == 0)
			$sq = "insert into consolidated_paytb set allowance_code='". mysqli_real_escape_string($con, $sc).
			"', category='". mysqli_real_escape_string($con, $cat)."', status='Active', entry_by='".
			 mysqli_real_escape_string($con, $login_id)."', entry_date=now(), entry_time=now()";
		else
			$sq = "update consolidated_paytb set status='Active', entry_by='".
			 mysqli_real_escape_string($con, $login_id)."', entry_date=now(), entry_time=now() ".
			" where allowance_code='". mysqli_real_escape_string($con, $sc)."' and category='". mysqli_real_escape_string($con, $cat)."'";
	}elseif($action=="delete"){
		$sq = "delete from consolidated_paytb where id=". mysqli_real_escape_string($con, $r_id);
	}
	elseif($action=="update"){
		$sq = "update consolidated_paytb set status = '". mysqli_real_escape_string($con, $stat)."' where id=". mysqli_real_escape_string($con, $r_id);
	}
	if($read){
		if( mysqli_query($con, $sq)) echo "<script>alert('Operation successful!');</script>";
		else echo "<script>alert('Operation failed!');</script>". mysqli_error($con);
	}
	{
		$q= mysqli_query($con, "select * from consolidated_paytb order by category");
				  echo '<p>
				  <table width="100%" border="1" cellspacing="0" cellpadding="2" align="left" rules="rows" frame="box">
				    <tr align="left">
				      <th>SN</th>
				      <th>Category</th>
				      <th>Folio Code</th>
				      <!--<th>Description</th>-->
				      <th>Status</th>
				      <th>Action</th>
			        </tr>';
					$sn=0;
				while($r= mysqli_fetch_array($q, 3 )){
					$rid = $r['id'];
				    echo '<tr align="left" ';
					if($r['status']=='Inactive') echo 'style="color:#F00"';
					echo '>
				      <td>'.++$sn.'</td>
				      <td>'.$r['category'].'</td>
				      <td>'.get_folio_name($r['allowance_code'])." [".$r['allowance_code']."]".'</td>
					  <!--<td>'.get_account_code_narration($r['allowance_code'])." [".$r['allowance_code']."]".'</td>
				      <td>'.$r['description'].'</td>-->
				      <td>'.$r['status'].'</td>
				      <td><a href="#" onClick="$(\'#status\').val(\'Active\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'consolidated_pay\', \'update\', \''.$rid.'\');">Active</a> | 
					  <a href="#" onClick="$(\'#status\').val(\'Inactive\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'consolidated_pay\', \'update\', \''.$rid.'\');">Inactive</a>
					  <!-- | 
					  <a href="#" onClick="$(\'#id\').val(\''.$rid.'\'); swapcontent(\'consolidated_pay\', \'delete\', \''.$rid.'\');">Delete</a>
					  -->
					  </td>
			        </tr>';
				}
			      echo '</table></p>';
	}
}

if($id=="salary_code_section"){
	$action = $_REQUEST['action'];
	$code = strtoupper($_REQUEST['code']);
	$cat=strtoupper($_REQUEST['category']);
	$title=strtoupper($_REQUEST['title']);

	$ncode = strtoupper($_REQUEST['ncode']);
	$ntitle=strtoupper($_REQUEST['ntitle']);

	$r_id = $_REQUEST['r_id'];
	$s_id = $_REQUEST['id'];
	$stat = $_REQUEST['status'];
	$read=true;
	if($action=="save"){
		if($code =='' or $cat == '' or $title == ''){
			echo "<script>alert('Please fill and select all mandatory fields!');</script>Please fill and select all mandatory fields!"; exit;
			//$read=false;
		}
		//$w= mysqli_query($con, "select * from salary_codetb where account_code='". mysqli_real_escape_string($con, $code)."'");
		$w= mysqli_query($con, "select * from foliotb where folio_code='". mysqli_real_escape_string($con, $code)."'");
		if( mysqli_num_rows($w) == 0){
			/*
			$sq = "insert into salary_codetb set account_code='". mysqli_real_escape_string($con, $code).
			"', category='". mysqli_real_escape_string($con, $cat)."', title='". mysqli_real_escape_string($con, $title)."', status='Active', entry_by='".
			 mysqli_real_escape_string($con, $login_id)."', entry_date=now(), entry_time=now()";
			*/
			//b_unit='SLA'
			$sq = "insert into foliotb set folio_code='". mysqli_real_escape_string($con, $code).
			"', category='". mysqli_real_escape_string($con, $cat)."', 
			title='". mysqli_real_escape_string($con, $title)."', ncoa_code='". mysqli_real_escape_string($con, $ncode)."', 
			ncoa_title='". mysqli_real_escape_string($con, $ntitle)."', entry_by='".
			 mysqli_real_escape_string($con, $login_id)."', entry_date=now(), entry_time=now()";
		}else{
			/*
			$sq = "update salary_codetb set category='". mysqli_real_escape_string($con, $cat).
			"', title='". mysqli_real_escape_string($con, $title)."', entry_by='".
			 mysqli_real_escape_string($con, $login_id)."', entry_date=now(), entry_time=now() ".
			" where account_code='". mysqli_real_escape_string($con, $code)."'";
			*/
			/*$sq = "update foliotb set category='". mysqli_real_escape_string($con, $cat).
			"', title='". mysqli_real_escape_string($con, $title)."', entry_by='".
			 mysqli_real_escape_string($con, $login_id)."', entry_date=now(), entry_time=now() ".
			" where folio_code='". mysqli_real_escape_string($con, $code)."'";*/
		}
				if( mysqli_query($con, $sq)) echo "<script>alert('Operation successful!');</script>";
		else echo "<script>alert('Operation failed!');</script>";//. mysqli_error($con);
		goto searchpoint;
		//$q = "select * from salary_codetb where category = '". mysqli_real_escape_string($con, $cat)."' order by account_code, category";
		//$q = "select * from foliotb where category = '". mysqli_real_escape_string($con, $cat)."' and b_unit='SLA' order by folio_code, category";
	}elseif($action=="delete"){
		/*$sq = "delete from salary_codetb where id=". mysqli_real_escape_string($con, $r_id);
		$q = "select * from salary_codetb order by account_code, category";*/
		$sq = "delete from foliotb where id=". mysqli_real_escape_string($con, $r_id);
				if( mysqli_query($con, $sq)) echo "<script>alert('Operation successful!');</script>";
		else echo "<script>alert('Operation failed!');</script>";//. mysqli_error($con);
		goto searchpoint;
		//$q = "select * from foliotb where b_unit='SLA' order by folio_code, category";
	}
	elseif($action=="update"){
		$sq = "update foliotb set status = '". mysqli_real_escape_string($con, $stat)."' where id=". mysqli_real_escape_string($con, $r_id);
				if( mysqli_query($con, $sq)) echo "<script>alert('Operation successful!');</script>";
		else echo "<script>alert('Operation failed!');</script>";//. mysqli_error($con);

		goto searchpoint;
		/*$q = "select * from salary_codetb order by account_code, category";
		$sq = "update foliotb set status = '". mysqli_real_escape_string($con, $stat)."' where id=". mysqli_real_escape_string($con, $r_id);
		$q = "select * from foliotb order by folio_code, category";*/
	}
	elseif($action=="search") {
		//search here!
		}
		searchpoint:
		{
		//$read=false;
		/*if($code !='' and $cat != '' and $title != '')
		$q = "select * from salary_codetb where account_code='". mysqli_real_escape_string($con, $code)."' or title like '%". mysqli_real_escape_string($con, $title)."%' or category = '". mysqli_real_escape_string($con, $cat)."' order by account_code, category";
		elseif($code !='' and $cat != '' and $title == '')
		$q = "select * from salary_codetb where account_code='". mysqli_real_escape_string($con, $code)."' or category = '". mysqli_real_escape_string($con, $cat)."' order by account_code, category";
		elseif($code !='' and $cat == '' and $title == '')
		$q = "select * from salary_codetb where account_code='". mysqli_real_escape_string($con, $code)."' order by account_code, category";
		elseif($code =='' and $cat != '' and $title != '')
		$q = "select * from salary_codetb where title like '%". mysqli_real_escape_string($con, $title)."%' or category = '". mysqli_real_escape_string($con, $cat)."' order by account_code, category";
		elseif($code =='' and $cat != '' and $title == '')
		$q = "select * from salary_codetb where category = '". mysqli_real_escape_string($con, $cat)."' order by account_code, category";
		elseif($code =='' and $cat == '' and $title != '')
		$q = "select * from salary_codetb where title like '%". mysqli_real_escape_string($con, $title)."%' order by account_code, category";
		elseif($code =='' and $cat == '' and $title == '')
		$q = "select * from salary_codetb order by account_code, category";*/
		if($code !='' and $cat != '' and $title != '')
		$q = "select f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id where folio_code='". mysqli_real_escape_string($con, $code)."' or title like '%". mysqli_real_escape_string($con, $title)."%' or category = '". mysqli_real_escape_string($con, $cat)."' order by folio_code, category";
		elseif($code !='' and $cat != '' and $title == '')
		$q = "select f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id where folio_code='". mysqli_real_escape_string($con, $code)."' or category = '". mysqli_real_escape_string($con, $cat)."' order by folio_code, category";
		elseif($code !='' and $cat == '' and $title == '')
		$q = "select f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id where folio_code='". mysqli_real_escape_string($con, $code)."' order by folio_code, category";
		elseif($code =='' and $cat != '' and $title != '')
		$q = "select f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id where title like '%". mysqli_real_escape_string($con, $title)."%' or category = '". mysqli_real_escape_string($con, $cat)."' order by folio_code, category";
		elseif($code =='' and $cat != '' and $title == '')
		$q = "select f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id where category = '". mysqli_real_escape_string($con, $cat)."' order by folio_code, category";
		elseif($code =='' and $cat == '' and $title != '')
		$q = "select f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id where and title like '%". mysqli_real_escape_string($con, $title)."%' order by folio_code, category";
		elseif($code =='' and $cat == '' and $title == '')
		$q = "select f.*, c.folio_category from foliotb f INNER JOIN folio_categorytb c ON f.category=c.id where order by folio_code, category";
	}
	
	/*if($read){
		if( mysqli_query($con, $sq)) echo "<script>alert('Operation successful!');</script>";
		else echo "<script>alert('Operation failed!');</script>";//. mysqli_error($con);
	}*/
	{
		//$q= mysqli_query($con, "select * from consolidated_paytb order by category");
		$ccat=$bursary->get_any_value('folio_category','folio_categorytb','id',$cat);
				  echo '<p><table width="100%" border="1" cellspacing="0" cellpadding="2" align="left" rules="rows" frame="box">
				 <tr>
				<td align="center" colspan="7" height="35" style="font-size:18px; font-weight:bold;">'.$ccat.'</td></tr>
				    <tr align="left">
				      <th>SN</th>
				      <th>UIL Code</th>
				      <th>UIL Title</th>
				      <th>NCOA Code</th>
				      <th>NCOA Title</th>
				      <!--th>Category</th-->
				      <th>Status</th>
				      <th>Action</th>
			        </tr>';
					$sn=0;
					$query= mysqli_query($con, $q);
				while($r= mysqli_fetch_array($query, 3 )){
					$rid = $r['id'];
				    echo '<tr align="left" ';
					if($r['status']=='Inactive') echo 'style="color:#F00"';
					echo '>
				      <td>'.++$sn.'</td>
				      <td nowrap>'.$r['folio_code'].'</td>
				      <td>'.$r['title'].'</td>
				      <td nowrap>'.$r['ncoa_code'].'</td>
				      <td>'.$r['ncoa_title'].'</td>
				      <!--td>'.$r['folio_category'].'</td-->
				      <td>'.$r['status'].'</td>
				      <td><a href="#" onClick="$(\'#status\').val(\'Active\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_code_section\', \'update\', \''.$rid.'\');">Active</a> | 
					  <a href="#" onClick="$(\'#status\').val(\'Inactive\'); $(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_code_section\', \'update\', \''.$rid.'\');">Inactive</a>
					  <!-- | 
					  <a href="#" onClick="$(\'#id\').val(\''.$rid.'\'); swapcontent(\'salary_code_section\', \'delete\', \''.$rid.'\');">Delete</a>
					  -->
					  </td>
			        </tr>';
				}
			      echo '</table></p>';
	}
}

if($id=='account')
{
	$acc=$_POST['dept_acctcode'];
	?>

<select name="acctcode" id="acctcode" tabindex="2">
<option value="" selected>--</option>
<?php 
$r=@mysqli_query($con, "select distinct a.acctcode,b.bankname,b.acctname,b.acctno from account_allocationtb a, bank_accounttb b where a.acctcode=b.acctcode and a.dept_acctcode='$acc' order by a.acctcode");

while($rl=@mysqli_fetch_array($r))
{
	++$n;
	$acctcode=@$rl['acctcode'];$bankname=@$rl['bankname'];$acctname=@$rl['acctname'];$acctno=@$rl['acctno'];
	
	echo "<option value='$acctcode'>$bankname || $acctname || $acctno</option>";
	
}

?>
</select>
<?php 
} // End of Account  
if($id=='posting_section')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$transtype=@$_REQUEST['transtype'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$sch_code=explode("***",$j->sch_code);
	
	$tdate=@date('Y-m-d', strtotime($j->transdate));
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from bank_accounttb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$acctcode=@mysqli_real_escape_string($con, $ds['acctcode']);$acctno=@mysqli_real_escape_string($con, $ds['acctno']);
				$bankname=@mysqli_real_escape_string($con, $ds['bankname']);$accttype=@mysqli_real_escape_string($con, $ds['accttype']);
				$sortcode=@mysqli_real_escape_string($con, $ds['sortcode']);$acctname=@mysqli_real_escape_string($con, $ds['acctname']);
				//$r_id=@$ds['id'];
				//echo $r_id;
				$db->sql("select * from bank_accounttb where id = '$r_id'");
				$s=@json_decode(stripslashes($db->getResult()));
				//$s_data=@json_decode(stripslashes($s->data));
				$s_array=array(s_detail=>"",msg=>"");
				//echo "yes====>$s->row====> $->data $sch_code $dept_name";
				if($s->row>=1)
				{
					
					$s_array['s_detail']=$s->data;$s_array['msg']='1';
				}
		}
	
	if($action=='save')
		{
			if($j->amount !="" && !preg_match('/^\d+(\.\d+)?$/', $j->amount))
					{
						echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
					}
			
			if($j->transdate =="" || $j->transdate=='01-01-1970')
					{
						echo "<script language='javascript'>alert('Invalid Date. Select correct Date');</script>";exit;
					}
		
			if($transtype=="Debit")
			{
				if( mysqli_query($con, "insert into transtb set fileno='$j->fileno', dept_acctcode='$j->dept',acctcode='$j->acctcode',folio_code='$j->folio_code',transtype='$transtype',transdate='$tdate',amount='$j->amount',receiptno='$j->receiptno',comment='$j->comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
					{
						logs($login_id,"Save Record","Insert new record into transaction  as Debit : Dept AcctCode:$j->dept, AcctCode:$j->acctcode,Folio code :$j->folio_code,Date:$tdate,Amount:$j->amount");
						echo "<script>
								alert('Record save successfully');</script>
								";
					}
					else{echo "<script>alert('Unable to save record');</script>";}
			}// end of Debit Saving
			else if($transtype=="Credit")
			{
				if( mysqli_query($con, "insert into transtb set fileno='$j->fileno', dept_acctcode='$j->dept',acctcode='$j->acctcode',folio_code='$j->folio_code',transtype='$transtype',transdate='$tdate',amount='$j->amount',receiptno='$j->receiptno',payee='$j->payee',chequeno='$j->chequeno',pvno='$j->pvno',comment='$j->comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
					{
						logs($login_id,"Save Record","Insert new record into transaction  as Credit : Dept AcctCode:$j->dept, AcctCode:$j->acctcode,Folio code :$j->folio_code,Date:$tdate,Amount:$j->amount,Payee:$j->payee");
						echo "<script>
								alert('Record save successfully');</script>
								";
					}
					else{echo "<script>alert('Unable to save record');</script>";}
			}// end of Credit Saving
			
			
			
		}// end of save 
	if($action=='edit')
		{
		
			//$json_val=@array(sch_code=>$sch_code,dept_code=>$dept_code,dept_name=>$dept_name,category=>$category,r_id=>$r_id);
			echo @json_encode($s_array); 
			exit;
			
		}//end of edit option
	if($action=='delete')
		{
			//
			//echo $r_id;exit;
			if( mysqli_query($con, "delete from transtb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete transaction record : Folio code:$folio_code,Dept AcctCode:$dept Trans Dat :$transdate,Amount:$amount,Trans Type :$trans_type");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo  mysqli_error($con);
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from transtb where (transtype='$transtype' and dept_acctcode = '$j->dept') and (fileno='$j->fileno' or acctcode = '$j->acctcode' or folio_code = '$j->folio_code' or transdate = '$j->transdate' or amount ='$j->amount' or receiptno='$j->receiptno' or payee='$j->payee' or chequeno='$j->chequeno' or pvno='$j->pvno') order by transdate desc");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from transtb where transtype='$transtype' and dept_acctcode='$j->dept' order by transdate desc");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "<p>&nbsp;</p>
				<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' style='font-size:12px' width='100%'>
				<tr style='text-align:left'>
				<th>S/No</th><th>File No</th>
				<th>Folio Code</th><th>Account Code</th><th>Dept Code</th><th>Trans. Date</th><th>Amount</th><th>Receipt No</th><th>Payee</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$acctcode=@mysqli_real_escape_string($con, $rs['acctcode']);$dept=@mysqli_real_escape_string($con, $rs['dept_acctcode']);
				$fileno=@mysqli_real_escape_string($con, $rs['fileno']);$folio_code=@mysqli_real_escape_string($con, $rs['folio_code']);
				$transtype=@mysqli_real_escape_string($con, $rs['transtype']);$transdate=@mysqli_real_escape_string($con, $rs['transdate']);
				$r_id=@$rs['id'];$amount=@$rs['amount'];$payee=@$rs['payee'];$receiptno=@$rs['receiptno'];$chequeno=@$rs['chequeno'];
				$pvno=@$rs['pvno'];$comment=@$rs['comment'];$amt=@number_format($rs['amount'],2);
				echo"<tr>
					<td>$sno</td><td>$fileno</td><td>$folio_code</td><td>$acctcode</td><td>$dept</td><td>$transdate</td><td>$amt</td><td>$receiptno</td><td>$payee</td><td> <a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('posting_section','delete','$transtype',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
	
	
}// end of Posting Section
if($id=='posting_section2')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$transtype=@$_REQUEST['transtype'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$sch_code=explode("***",$j->sch_code);
	$codes=explode("***",$j->folio_code);
	$fcodes = $codes[0]; $rcodes = $codes[1];
	$tdate=@date('Y-m-d', strtotime($j->transdate));
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from bank_accounttb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$acctcode=@mysqli_real_escape_string($con, $ds['acctcode']);$acctno=@mysqli_real_escape_string($con, $ds['acctno']);
				$bankname=@mysqli_real_escape_string($con, $ds['bankname']);$accttype=@mysqli_real_escape_string($con, $ds['accttype']);
				$sortcode=@mysqli_real_escape_string($con, $ds['sortcode']);$acctname=@mysqli_real_escape_string($con, $ds['acctname']);
				//$r_id=@$ds['id'];
				//echo $r_id;
				$db->sql("select * from bank_accounttb where id = '$r_id'");
				$s=@json_decode(stripslashes($db->getResult()));
				//$s_data=@json_decode(stripslashes($s->data));
				$s_array=array(s_detail=>"",msg=>"");
				//echo "yes====>$s->row====> $->data $sch_code $dept_name";
				if($s->row>=1)
				{
					
					$s_array['s_detail']=$s->data;$s_array['msg']='1';
				}
		}
	
	if($action=='save')
		{
			if($j->amount !="" && !preg_match('/^\d+(\.\d+)?$/', $j->amount))
					{
						echo "<script language='javascript'>alert('Invalid Amount. Enter Amount correctly');</script>";exit;
					}
			
			if($j->transdate =="" || $j->transdate=='01-01-1970')
					{
						echo "<script language='javascript'>alert('Invalid Date. Select correct Date');</script>";exit;
					}
		
			if($transtype=="Debit")
			{
				if( mysqli_query($con, "insert into transtb set fileno='$j->fileno', dept_acctcode='$j->dept',acctcode='$j->acctcode',folio_code='$j->folio_code',transtype='$transtype',transdate='$tdate',amount='$j->amount',receiptno='$j->receiptno',comment='$j->comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
					{
						logs($login_id,"Save Record","Insert new record into transaction  as Debit : Dept AcctCode:$j->dept, AcctCode:$j->acctcode,Folio code :$j->folio_code,Date:$tdate,Amount:$j->amount");
						echo "<script>
								alert('Record save successfully');</script>
								";
					}
					else{echo "<script>alert('Unable to save record');</script>";}
			}// end of Debit Saving
			else if($transtype=="Credit")
			{
				if( mysqli_query($con, "insert into transtb set fileno='$j->fileno', dept_acctcode='$j->dept',acctcode='$j->acctcode',folio_code='$fcodes',transtype='$transtype',transdate='$tdate',amount='$j->amount',receiptno='$j->receiptno',payee='$j->payee',chequeno='$j->chequeno',pvno='$j->pvno',comment='$j->comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id',rev_code='$rcodes'"))
					{
						logs($login_id,"Save Record","Insert new record into transaction  as Credit : Dept AcctCode:$j->dept, AcctCode:$j->acctcode,Folio code :$j->folio_code,Date:$tdate,Amount:$j->amount,Payee:$j->payee");
						echo "<script>
								alert('Record save successfully');</script>
								";
					}
					else{echo "<script>alert('Unable to save record');</script>";}
			}// end of Credit Saving
			
			
			
		}// end of save 
	if($action=='edit')
		{
		
			//$json_val=@array(sch_code=>$sch_code,dept_code=>$dept_code,dept_name=>$dept_name,category=>$category,r_id=>$r_id);
			echo @json_encode($s_array); 
			exit;
			
		}//end of edit option
	if($action=='delete')
		{
			//
			//echo $r_id;exit;
			if( mysqli_query($con, "delete from transtb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete transaction record : Folio code:$folio_code,Dept AcctCode:$dept Trans Dat :$transdate,Amount:$amount,Trans Type :$trans_type");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo  mysqli_error($con);
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from transtb where (transtype='$transtype' and dept_acctcode = '$j->dept') and (fileno='$j->fileno' or acctcode = '$j->acctcode' or folio_code = '$fcode' or transdate = '$j->transdate' or amount ='$j->amount' or receiptno='$j->receiptno' or payee='$j->payee' or chequeno='$j->chequeno' or pvno='$j->pvno') order by transdate desc");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from transtb where transtype='$transtype' and dept_acctcode='$j->dept' order by transdate desc");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "<p>&nbsp;</p>
				<table border='1' rule='rows' frame='box' cellpadding='3' cellspacing='0' rules='rows' frame='box' style='font-size:12px' width='100%'>
				<tr style='text-align:left'>
				<th>S/No</th><th>File No</th>
				<th>Folio Code</th><th>Account Code</th><th>Dept Code</th><th>Trans. Date</th><th>Amount</th><th>Receipt No</th><th>Payee</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$acctcode=@mysqli_real_escape_string($con, $rs['acctcode']);$dept=@mysqli_real_escape_string($con, $rs['dept_acctcode']);
				$fileno=@mysqli_real_escape_string($con, $rs['fileno']);$folio_code=@mysqli_real_escape_string($con, $rs['folio_code']);
				$transtype=@mysqli_real_escape_string($con, $rs['transtype']);$transdate=@mysqli_real_escape_string($con, $rs['transdate']);
				$r_id=@$rs['id'];$amount=@$rs['amount'];$payee=@$rs['payee'];$receiptno=@$rs['receiptno'];$chequeno=@$rs['chequeno'];
				$pvno=@$rs['pvno'];$comment=@$rs['comment'];$amt=@number_format($rs['amount'],2);
				echo"<tr>
					<td>$sno</td><td>$fileno</td><td>$folio_code</td><td>$acctcode</td><td>$dept</td><td>$transdate</td><td>$amt</td><td>$receiptno</td><td>$payee</td><td> <a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('posting_section','delete','$transtype',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
	
	
}// end of Posting Section

if($id=="change_by_status"){
	$status = $_REQUEST['staff_status'];
	if($status=="All"){
		$where="";
		$where2="";
	}else{
		$where=" where category = '". mysqli_real_escape_string($con, $status)."'";
		$where2=" and category = '". mysqli_real_escape_string($con, $status)."'";
	}
				echo '<table width="90%" border="0" align="left" cellpadding="3" cellspacing="0"><tr class="s_cat">
				  <td align="left" valign="top"><strong>Position:</strong><br />
                <select name="position" id="position" tabindex="7" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option>';
					
					$q= mysqli_query($con, "select distinct position from hr_positiontb ".$where." order by position");
					while ($r= mysqli_fetch_array($q, 3 ))
						echo "<option value='".$r['position']."'>".$r['position']."</option>";
				echo '	
				</select></td>
				  <td align="left" valign="top"><strong>Salary Scale</strong>:<br />
                <select name="scale" id="scale" tabindex="8" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option>';
                    
					$q=@mysqli_query($con, "select distinct scale_name  from scale_nametb where status='Active' ".$where2."");
					while ($r= mysqli_fetch_array($q, 3 ))
						echo "<option value='".$r['scale_name']."'>".$r['scale_name']."</option>";
					echo '
				</select></td>
  </tr>
				<tr class="s_cat">
				  <td align="left" valign="top"><strong>Rank</strong>:<br />
                <select name="rank" id="rank" tabindex="9" style="width:150px">
					<option value="" selected>--</option>
					<option value="All">All</option>';
                    
					$q=@mysqli_query($con, "select distinct rank from hr_ranktb ".$where." order by rank");
					while ($r= mysqli_fetch_array($q, 3 ))
						echo "<option value='".$r['rank']."'>".$r['rank']."</option>";
						
					echo '
				</select></td>
				  <td align="left" valign="top">&nbsp;</td>
  </tr></table>';
}

if($id=='deduction_definition_section')
{
	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$dept_code=explode("***",$j->dept_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from deductiontb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);$category=@mysqli_real_escape_string($con, $ds['category']);
				$staff_status=@mysqli_real_escape_string($con, $ds['staff_status']);$level=@mysqli_real_escape_string($con, $ds['level']);
				$criteria=@mysqli_real_escape_string($con, $ds['criteria']);
				$r_ids=@$ds['id'];
		}
	
	if($action=='save')
		{
			if($j->criteria=='' or $j->amount=='' or $j->amount < 0 or $j->category == ''){
				echo "<script>alert('Please ensure all mandatory fields are correctly filled/selected!');</script>"; exit;
			}
			if($j->category == "ALL"){
				$j->staff_status = "ALL";	$j->sex = "ALL";	$j->religion = "ALL";	$j->level = "ALL";
				$j->position = "ALL";		$j->scale = "ALL";	$j->rank = "ALL";
			}
			if( mysqli_query($con, "insert into deductiontb set folio_code='$j->folio_code', category='$j->category', staff_status='$j->staff_status', sex='$j->sex', religion='$j->religion', level='$j->level', position='$j->position', scale='$j->scale', rank='$j->rank', criteria='$j->criteria', value='$j->amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id', item_status='$j->istat'"))
			{
				logs($login_id,"Save Record","Insert new Deduction Definition: Folio code: $j->folio_code, Category: $j->category, Staff Status: $j->staff_status, Criteria: $j->criteria, Level: $j->level");
				echo "<script>alert('Record save successfully');</script>";
			}
			else
			{
				//$err=@mysqli_real_escape_string($con,  mysqli_error($con));
				//$err=@mysqli_error($con);
			//echo "$err";
				echo "<script>alert('Unable to save record');</script>";
				
			}
			
			/*if($j->level !="All")
			{
				$level=explode('-',$j->level);
				for ($i=$level[0];$i<=$level[1];$i++)
					{
						if($i !=10)// Jump Level 10 has is not exist in the salary scale
						{
						@mysqli_query($con, "insert into deductiontb set folio_code='$j->folio_code', category='$j->category',staff_status='$j->staff_status',sex='$j->sex',religion='$j->religion',level='$i',criteria='$j->criteria',value='$j->amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'");
						}// end of jump level 10
					}//end of for loop
					logs($login_id,"Save Record","Insert new Deduction Definition: Folio code: $j->folio_code,Category :$j->category ,Staff Status :$j->staff_status , Criteria :$j->criteria level:$j->level");
				echo "<script>
						alert('Record save successfully');</script>
						";
					
			}
			else{
			$level=$j->level;
if( mysqli_query($con, "insert into deductiontb set folio_code='$j->folio_code', category='$j->category',staff_status='$j->staff_status',sex='$j->sex',religion='$j->religion',level='$level',criteria='$j->criteria',value='$j->amount',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
			{
				logs($login_id,"Save Record","Insert new Deduction Definition: Folio code: $j->folio_code,Category :$j->category ,Staff Status :$j->staff_status , Criteria :$j->criteria level:$j->level");
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
			}// end of level is All*/
		}// end of save 
	
	if($action=='edit')
		{
		
			
		}//end of edit option
	if($action=='delete')
		{
			//
			$newstat= mysqli_real_escape_string($con, $_REQUEST['istat']);
			////if( mysqli_query($con, "delete from allowancestb where id='$r_id'"))
		  if($newstat == '' or $newstat == 'udefined') {
			  echo "<script>alert('Cannot determine payment item status. Update failed!');</script>";
			  //exit;
		  }else{
			if( mysqli_query($con, "update deductiontb set item_status='$newstat' where id=".$r_id))
			{
			////if( mysqli_query($con, "delete from deductiontb where id='$r_id'"))
			//{
				////logs($login_id,"Delete Record","Delete Deduction Definition record: Folio Code :$folio_code,Category :$category,Staff Status :$staff_status, Level :$level ,Criteria :$criteria");
				logs($login_id,"Update Record", "Update Deduction Definition record: Folio Code :$folio_code, Category :$category, Item Status :$newstat, Level :$level");
				echo "<script>alert('Record updated successfully');</script>";
				}else{
					echo "<script>alert('Unable to update record');</script>";
				}
			}
		} // end of delete option
				
		
	if($action=='search')
		{
			//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
			$r=@mysqli_query($con, "select * from deductiontb where folio_code = '$j->folio_code'
			 or category = '$j->category' or criteria='$j->criteria' order by folio_code,convert(level,decimal)");
		}// end of search with criterials
		else
		{
			$r=@mysqli_query($con, "select * from deductiontb order by folio_code");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "<table align='left' border='1' cellspacing='0' cellpadding='2' rules='rows' frame='box' style='font-size:11px'>
				<tr align='left'>
				<th>SN</th>
				<th>Folio</th>
				<th>Cat.</th>
				<th>S. Status</th>
				<th>Sex</th>
				<th>Rel.</th>
				<th>Level</th>
				<th>Pos.</th>
				<th>Scale</th>
				<th>Rank</th>
				<th>Criteria</th>
				<th>Val./Amt.</th>
				<th>Type</th>
				<th>Action</th>
				</tr>";
			$sno=0;
			while($ds= mysqli_fetch_array($r))
			{
				$sno++;
				$folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);
				$category=@mysqli_real_escape_string($con, $ds['category']);
				$folio_code .=': '.get_account_code_narration($folio_code) ;
				$staff_status=@mysqli_real_escape_string($con, $ds['staff_status']);
				$level=@mysqli_real_escape_string($con, $ds['level']);
				$criteria=@mysqli_real_escape_string($con, $ds['criteria']);
				$sex=@mysqli_real_escape_string($con, $ds['sex']);
				$religion=@mysqli_real_escape_string($con, $ds['religion']);
				$value=@mysqli_real_escape_string($con, $ds['value']);
				$position=@mysqli_real_escape_string($con, $ds['position']);
				$rank=@mysqli_real_escape_string($con, $ds['rank']);
				$scale=@mysqli_real_escape_string($con, $ds['scale']);
				$al_stat=@mysqli_real_escape_string($con, $ds['item_status']);
				
				if($al_stat=="Active") $alstat="A";
				if($al_stat=="Constant") $alstat="K";
				if($al_stat=="Suspend") $alstat="X";

				$r_id=@$ds['id'];
				echo"<tr>
					<td>$sno</td>
					<td>$folio_code</td>
					<td>$category</td>
					<td>$staff_status</td>
					<td>$sex</td>
					<td>$religion</td>
					<td>$level</td>
					<td>$position</td>
					<td>$scale</td>
					<td>$rank</td>
					<td>$criteria</td>
					<td>$value</td>
					<td align='center'>$alstat</td>
					<td nowrap><a href=\"javascript:if(confirm('Are you sure you want to perform this operation?')) 
					swapcontent('deduction_definition_section','delete',$r_id, 'Active');\" title='Active (Pay once)'>A</a>
					 | 
					<a href=\"javascript:if(confirm('Are you sure you want to perform this operation?')) 
					swapcontent('deduction_definition_section','delete',$r_id, 'Constant');\" title='Constant (Coninous pay)'>K</a>
					 | 
					<a href=\"javascript:if(confirm('Are you sure you want to perform this operation?')) 
					swapcontent('deduction_definition_section','delete',$r_id, 'Suspend');\" title='Suspend (Stop pay)'>X</a>
					
					</td>
				</tr>";
				//<!--td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('deduction_definition_section','delete',$r_id);\">Delete</a-->
			}// end of while
			echo "<tr style='color:maroon; font-size:10px'><td colspan='7' valign='top'><strong>Footnote:</strong><br>
				Folio => Folio Code<br>
				Cat. => Criteria Category
				<br>Scale => Salary Scale<br>
				Type => Payment Item Type :::&nbsp;&nbsp;A --> Active (Current month),</td>
				
				<td colspan='6'>S. Status => Staff Status<br>
				Rel. => Religion <br>
				Val./Amt. => Value (%)/Amount(&#8358;)
				<br>Pos. => Position<br>
				K --> Constant (Continous), X --> Suspend
				</td></tr></table>";
		}
		else
		{
			echo "<script>alert('No record to display');</script>";
		}
}// end of deduction_definition_section

if($id=='allowance_definition_section')
{
	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$dept_code=explode("***",$j->dept_code);
	
	if($r_id != "")
		{
			$d=@mysqli_query($con, "select * from allowancestb where id = '$r_id'");
			$ds= mysqli_fetch_array($d);
			$folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);
			$category=@mysqli_real_escape_string($con, $ds['category']);
			$staff_status=@mysqli_real_escape_string($con, $ds['staff_status']);
			$level=@mysqli_real_escape_string($con, $ds['level']);
			$r_ids=@$ds['id'];
		}
	
	if($action=='save')
		{
			if($j->amount=='' or $j->amount < 0 or $j->category == '' or $j->istat == ''){
				echo "<script>alert('Please ensure all mandatory fields are correctly filled/selected!');</script>"; exit;
			}
			if($j->category == "All"){
				$j->staff_status = "All";	$j->level = "All";
				$j->position = "All";		$j->scale = "All";	$j->rank = "All";
			}else{
				if(
					( 
					($j->position != '' and $j->scale != '' and $j->rank != '')  
					|| ($j->position != '' and $j->scale != '') 
					|| ($j->position != '' and $j->rank != '')
					|| ($j->scale != '' and $j->rank != '')
					) && $j->level != ''
				  ){
					  echo "<script>alert('You selected wrong criteria combination. Please check!');</script>"; exit;
				}else{
					if($j->level != '') { $field_value = $j->level; $field_label = "Level"; }
					if($j->position != '') { $field_value = $j->position; $field_label = "Position"; }
					if($j->scale != '') { $field_value = $j->scale; $field_label = "Scale"; }
					if($j->rank != '') { $field_value = $j->rank; $field_label = "Rank"; }
				}
			}
			if( mysqli_query($con, "insert into allowancestb set folio_code='$j->folio_code', category='$j->category', staff_status='$j->staff_status', level='$j->level', position='$j->position', scale='$j->scale', rank='$j->rank', value='$j->amount', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id', field_value='$field_value', field_label='$field_label', item_status='$j->istat' "))
			{
				logs($login_id,"Save Record", "Insert new Allowance Definition: Folio code: $j->folio_code, Category: $j->category, Staff Status: $j->staff_status, Level: $j->level");
				echo "<script>alert('Record save successfully');</script>";
			}
			else
			{
				echo "<script>alert('Unable to save record');</script>";
				//exit;
			}
		}// end of save
	
	if($action=='edit')
		{
		
			
		}//end of edit option
	if($action=='delete')
		{
			//echo $r_id; exit;
			$newstat= mysqli_real_escape_string($con, $_REQUEST['istat']);
			////if( mysqli_query($con, "delete from allowancestb where id='$r_id'"))
		  if($newstat == '' or $newstat == 'udefined') {
			  echo "<script>alert('Cannot determine payment item status. Update failed!');</script>";
			  //exit;
		  }else{
			if( mysqli_query($con, "update allowancestb set item_status='$newstat' where id=".$r_id))
			{
				////logs($login_id,"Delete Record", "Delete Allowance Definition record: Folio Code :$folio_code, Category :$category, Staff Status :$staff_status, Level :$level");
				logs($login_id,"Update Record", "Update Allowance Definition record: Folio Code :$folio_code, Category :$category, Item Status :$newstat, Level :$level");
				echo "<script>alert('Record updated successfully');</script>";
				//exit;
			}
			else
			{
				echo "<script>alert('Unable to update record');</script>";
				//exit;
			}
		  }//end $newstat test
		} // end of delete option
				
		
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from allowancestb where folio_code = '$j->folio_code' or category = '$j->category' order by folio_code, convert(level, decimal)");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from allowancestb order by folio_code, convert(level, decimal)");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "
				<table align='left' border='1' cellspacing='0' cellpadding='2' rules='rows' frame='box' width='100%' style='font-size:11px;'>
				<tr align='left'>
				<th>S/No</th>
				<th>Folio</th>
				<th>Cat.</th>
				<th>S. Status</th>
				<th>Level</th>
				<th>Pos.</th>
				<th>Scale</th>
				<th>Rank</th>
				<th>Amount</th>
				<th>Type</th>
				<th>Action</th>
				</tr>";
			$sno=0;
			while($ds= mysqli_fetch_array($r))
			{
				$sno++;
				$folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);
				$category=@mysqli_real_escape_string($con, $ds['category']);
				$folio_code .=': '.get_account_code_narration($folio_code) ;
				$staff_status=@mysqli_real_escape_string($con, $ds['staff_status']);
				$level=@mysqli_real_escape_string($con, $ds['level']);
				$value=@mysqli_real_escape_string($con, $ds['value']);
				$position=@mysqli_real_escape_string($con, $ds['position']);
				$rank=@mysqli_real_escape_string($con, $ds['rank']);
				$scale=@mysqli_real_escape_string($con, $ds['scale']);
				$al_stat=@mysqli_real_escape_string($con, $ds['item_status']);
				
				if($al_stat=="Active") {
					$alstat="A";
					//$select="<select name='al_action$r_id' id='al_action$r_id' onChange=\"javascript:if(confirm('Are you sure you want to perform this operation?')) swapcontent('allowance_definition_section', 'delete',$r_id, $('#al_action$r_id').val());\"><option value='Active' selected>A</option><option value='Constant'>K</option><option value='Suspend'>X</option></select>";
				}
				if($al_stat=="Constant") {
					$alstat="K";
					//$select="<select name=\"al_action$r_id\" id=\"al_action$r_id\" onChange=\"javascript:if(confirm(\'Are you sure you want to perform this operation?\')) swapcontent(\'allowance_definition_section\', \'delete\',\'$r_id\', \'\');\"><option value=\"Active\">A</option><option value=\"Constant\" selected>K</option><option value=\"Suspend\">X</option></select>";
				}
				if($al_stat=="Suspend") {
					$alstat="X";
					//$select='<select name="al_action$r_id" id="al_action$r_id" onChange="javascript:if(confirm(\'Are you sure you want to perform this operation?\')) swapcontent(\'allowance_definition_section\', \'delete\',\'$r_id\', \'\');"><option value="Active">A</option><option value="Constant">K</option><option value="Suspend" selected>X</option></select>';
				} 
				$r_id=@$ds['id'];
				
				echo"<tr>
					<td>$sno</td>
					<td>$folio_code</td>
					<td>$category</td>
					<td>$staff_status</td>
					<td>$level</td>
					<td>$position</td>
					<td>$scale</td>
					<td>$rank</td>
					<td>$value</td>
					<td align='center'>$alstat</td>
					<td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation?')) 
					swapcontent('allowance_definition_section', 'delete',$r_id, 'Active');\" title='Active (Pay once)'>A</a>
					 | 
					<a href=\"javascript:if(confirm('Are you sure you want to perform this operation?')) 
					swapcontent('allowance_definition_section', 'delete',$r_id, 'Constant');\" title='Constant (Coninous pay)'>K</a>
					 | 
					<a href=\"javascript:if(confirm('Are you sure you want to perform this operation?')) 
					swapcontent('allowance_definition_section', 'delete',$r_id, 'Suspend');\" title='Suspend (Stop pay)'>X</a></td>
				</tr>";
			}// end of while
			echo "</table><p style='color:maroon'>
			<strong>Footnote:</strong><br>
					Folio => Folio Code<br>
					Cat. => Criteria Category<br>
					S. Status => Staff Status<br>
					Pos. => Position<br>
					Type => Payment Item Type :::&nbsp;&nbsp;A --> Active (Current month)<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					K --> Constant (Continous)<br>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					X --> Suspend</p>";
		}
		else
		{
			echo "<script>alert('No record to display');</script>";
		}
}// end of allowance_definition_section

if($id=='deduction_exception_section')
{

	$mydata=@$_REQUEST['mydata'];
	$action=@$_REQUEST['action'];
	$r_id=@$_REQUEST['r_id'];
	$j=@json_decode(stripslashes($mydata)); //encode the json data
	//$sch_code=explode("***",$j->sch_code);
	
	if($r_id !="")
		{
			$d=@mysqli_query($con, "select * from deduction_exceptiontb where id = '$r_id'");
				$ds= mysqli_fetch_array($d);
				$folio_code=@mysqli_real_escape_string($con, $ds['folio_code']);$fileno=@mysqli_real_escape_string($con, $ds['fileno']);
				$month=@mysqli_real_escape_string($con, $ds['month']);$year=@mysqli_real_escape_string($con, $ds['year']);
				//$r_id=@$ds['id'];
				//echo $r_id;
				//$db->sql("select * from departmenttb where id = '$r_id'");
				//$s=@json_decode(stripslashes($db->getResult()));
				//$s_data=@json_decode(stripslashes($s->data));
				//$s_array=array(s_detail=>"",msg=>"");
				//echo "yes====>$s->row====> $->data $sch_code $dept_name";
				if($s->row>=1)
				{
					
					$s_array['s_detail']=$s->data;$s_array['msg']='1';
				}
		}
	
	if($action=='save')
		{
			
			if( mysqli_query($con, "insert into deduction_exceptiontb set folio_code='$j->folio_code', fileno='$j->fileno',month='$j->month',year='$j->year',remark='$j->comment',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'"))
			{
				
				logs($login_id,"Save Record","Insert Deduction Exception: Folio Code : $j->folio_code, File No : $j->fileno, Month/Year: $j->month / $j->year ");
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
		
			//$json_val=@array(sch_code=>$sch_code,dept_code=>$dept_code,dept_name=>$dept_name,category=>$category,r_id=>$r_id);
			echo @json_encode($s_array); 
			exit;
			
		}//end of edit option
	if($action=='delete')
		{
			//
			if( mysqli_query($con, "delete from deduction_exceptiontb where id='$r_id'"))
			{
				logs($login_id,"Delete Record","Delete record of a Deduction Exception : Folio code :$folio_code,File No:$fileno_name, Month/Year :$month/$year ");
				echo "<script>
						alert('Record deleted successfully');</script>
						";
			}
			else
			{
				echo "<script>alert('Unable to delete record');</script>";
			
			}
			
		
		} // end of delete option
	if($action=='search')
		{
		
	//$r=@mysqli_query($con, "select * from departmenttb where sch_code like '%$sch_code[0]%' or dept_code like '%$j->dept_code%' or dept_name like '%$j->dept_name%' or category ='$j->category' order by sch_code,category,dept_name");
	$r=@mysqli_query($con, "select * from deduction_exceptiontb where folio_code = '$j->folio_code' or fileno = '$j->fileno' or month = '$j->month' or year ='$j->year' order by year desc,month,fileno,folio_code");
		}// end of search with criterials
		else
		{
		$r=@mysqli_query($con, "select * from deduction_exceptiontb order by year desc,month,fileno,folio_code");
		}// end of display all record
	if( mysqli_num_rows($r)>0)
		{
			echo "
				<table border='1' cellpadding='3' cellspacing='0' rules='rows' frame='box'>
				<tr align='left'> 
				<th>S/No</th>
				<th>File Number</th><th>Folio Code</th><th>Month / Year</th><th>Remark</th><th>Action</th>
				</tr>
			";
			$sno=0;
			while($rs= mysqli_fetch_array($r))
			{
				$sno++;
				$folio_code=@mysqli_real_escape_string($con, $rs['folio_code']);$fileno=@mysqli_real_escape_string($con, $rs['fileno']);
				$month=@mysqli_real_escape_string($con, $rs['month']);$year=@mysqli_real_escape_string($con, $rs['year']);
				$remark=@mysqli_real_escape_string($con, $rs['remark']);
				$folio_code ="($folio_code) ".get_account_code_narration($folio_code) ;
				$fileno ="($fileno) ".get_staff_name($fileno) ;
				$month=@get_month_name($month);
				$r_id=@$rs['id'];
				echo"<tr>
					<td>$sno</td><td>$fileno</td><td>$folio_code</td><td>$month/$year</td><td>$remark</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('deduction_exception_section','delete',$r_id);\">Delete</a></td>
				</tr>";
				
				
			}// end of while
			echo "</table>";
		}
		else
		{
			echo "<script>
						alert('No record to display');</script>
						";
		}
//	echo "$mydata ==> $action ==> $r_id";
	
	
}// end of Deduction_exception Section

if($id=='specific')
{
echo "File Number (If more than one seprate with comma (,))<br><textarea name='fileno' cols='35' rows='2' id='fileno' tabindex='4' ></textarea> ";
}


if($id=='salary_computation_section')
{
$mydata=@$_REQUEST['mydata'];
$action=@$_REQUEST['action'];
$r_id=@$_REQUEST['r_id'];
$j=@json_decode(stripslashes($mydata)); //encode the json data
//echo "Month :$j->month<br>Year:$j->year<br>Type:$j->staff<br>File No:$j->fileno";
if($action=='compute')
{
//****** Fetch staff list from staff Table for salary computation
if($j->staff=='all')
{
	$sql="select * from stafftb where status='Active' and fileno not in ('weathstone','admin') order by fileno";
}// end of all staff
if($j->staff=='academic')
{
	$sql="select * from stafftb where status='Active' and category='Academic' and fileno not in ('weathstone','admin') order by fileno";
}// end of all Academic staff
if($j->staff=='non-academic')
{
	$sql="select * from stafftb where status='Active' and category='Non-Academic' and fileno not in ('weathstone','admin') order by fileno";
}// end of all Non-Academic staff

if($j->staff=='specific')
{
	$fileno=@set_comma_breakdown($j->fileno);
	
	//echo $fileno;
	$sql="select * from stafftb where status='Active' and fileno in ($fileno) order by fileno";
}// end of specific  staff
if($j->staff=='non-academic_junior')
{
	$sql="select * from stafftb where status='Active' and fileno not in ('weathstone','admin') and category='Non-Academic' and convert(level,decimal) between 1 and 5 order by fileno";
}// end of all Non-Academic staff

if($j->staff=='non-academic_senior')
{
	$sql="select * from stafftb where status='Active' and fileno not in ('weathstone','admin') and category='Non-Academic' and convert(level,decimal) between 6 and 15 order by fileno";
}// end of all Non-Academic staff

$r=@mysqli_query($con, $sql);
$trow=@mysqli_num_rows($r);

if($trow > 0)
{
	while($rs=@mysqli_fetch_array($r))
		{
			$fileno=@$rs['fileno'];
			$db->sql("select * from stafftb where fileno='$fileno'");
			if(get_magic_quotes_gpc()){ 
				$t= @json_decode(stripslashes($db->getResult()));
				$s=@json_decode(stripslashes($t->data)); 
				//echo "Here!1"; exit;
			}
			else{ 
				$t= @json_decode($db->getResult());  
				$s=@json_decode($t->data);
				//echo "Here!2"; exit;
			}
			//echo $s->dept_code; exit;
			$scalename=@get_current_scalename();
			$fullname=@get_staff_name($fileno) ;
			$staffstatus=@get_staff_status($s->level);
			$department=@get_dept_name($s->dept_code); 
			$transdate=@prepare_transdate($j->month,$j->year) ;
			///********** look into salary scale tb
			$r_salary_scale= mysqli_query($con, "select folio_code, amount from salary_scaletb where level='$s->level' and step='$s->step' and category='$s->category' and scale_name='$scalename'") or die( mysqli_error($con));
			
			while($rs_scale = @mysqli_fetch_array($r_salary_scale, 3 ))
			{	
				$code=@$rs_scale['folio_code'];
				$amount=@$rs_scale['amount'];
				$paymenttype="Allowance";
				//Check for excption from deduction_exctiontb 
				//echo excepted($fileno, $code, $j->month, $j->year); exit;
				//echo $prorata=$days=prorata($fileno,$j->month,$j->year); exit;
				if(!excepted($fileno, $code, $j->month, $j->year) or excepted($fileno, $code, $j->month, $j->year) == 0)
					{
						//check Prorata tb
						  //echo $fileno,".",$department,".",$staffstatus,".",$s->category,".",$scalename,".",$s->level,".",$s->step,".",$fullname,".",$s->bank_name,".",$s->acct_no,".",$j->month,".",$j->year,".",$transdate,".",$code,".",$paymenttype,".",$amount,".",$login_id,"!"; 
						$prorata=$days=prorata($fileno,$j->month,$j->year);
						if($prorata != "")
						{
							$amount=$amount/30 * $days;
							
							insert_into_payroll($fileno,$department,$staffstatus,$s->category,$scalename,$s->level,$s->step,$fullname,$s->bank_name,$s->acct_no,$j->month,$j->year,$transdate,$code,$paymenttype,$amount,$login_id);
						}
						else
							insert_into_payroll($fileno,$department,$staffstatus,$s->category,$scalename,$s->level,$s->step,$fullname,$s->bank_name,$s->acct_no,$j->month,$j->year,$transdate,$code,$paymenttype,$amount,$login_id);
						
					}// end of if not excepted
			}//end of loop for folio code in salary scale
			
			//***************************************************************************************************************
			//************ look into otherpaymentsource tb for INDIVIDUAL ALLOWANCES AND DEDUCTIONS *************************
			//***************************************************************************************************************

			//$rs_otherpay=@mysqli_query($con, "select * from otherpayment_sourcetb where fileno='$fileno' and ((month(start_date)>='$j->month' and year(start_date)>='$j->year')and (month(end_date)>='$j->month' and year(end_date)>='$j->year'))");
			$my="$j->month-$j->year";
			$sdate="$j->year-".sprintf("%02d",$j->month)."-01";
			$edate=$transdate;
			////$rs_otherpay=@mysqli_query($con, "select * from otherpayment_sourcetb where fileno='$fileno' and end_date >= '$edate' and start_date <= '$sdate' and status != 'Suspend'");
			////$rs_otherpay=@mysqli_query($con, "select * from otherpayment_sourcetb where fileno='$fileno' and status != 'Suspend'");
			$rs_otherpay=@mysqli_query($con, "select * from otherpayment_sourcetb where fileno='$fileno' and ((end_date >= '$edate' and start_date <= '$sdate') or status = 'Constant')");
			if(@mysqli_num_rows($rs_otherpay) > 0)
				{
					while($rs_other=@mysqli_fetch_array($rs_otherpay))
						{
							$skip='';
							$code=@$rs_other['folio_code'];
							$paymenttype=@$rs_other['payment_type'];
						  if($rs_other['status'] == "Constant"){
							  //IGNORE DATE CHECK FOR CONSTANT
							  $amount=@$rs_other['amount'];
						  }else{
							if(checkpaydate($rs_other['start_date']) > 0)
								$amount=@$rs_other['amount'] * checkpaydate($rs_other['start_date']); //compute by days FROM START
							elseif(checkpaydate($rs_other['start_date']) == 0)
								$amount=@$rs_other['amount'];					//full month payment
							elseif(checkpaydate($rs_other['start_date']) == -1){
								//DATE RANGE EXCEED START DATE, CHECK END DATE
								if(checkpaydate($rs_other['end_date'], 'end') > 0)
									$amount=@$rs_other['amount'] * checkpaydate($rs_other['end_date'], 'end'); //compute by days TO END DATE
								elseif(checkpaydate($rs_other['end_date'], 'end') == 0)
									$amount=@$rs_other['amount'];					//full month payment
								else{
									//date not in range
									$amount=@$rs_other['amount'];					//full month payment
									$skip='yes';
								}
							}
						  }//end if for CONSTANT TEST
							//Check for excption from deduction_exctiontb 
							if(!excepted($fileno,$code,$j->month,$j->year))
								{
									insert_into_payroll($fileno,$department,$staffstatus,$s->category,$scalename,$s->level,$s->step,$fullname,$s->bank_name,$s->acct_no,$j->month,$j->year,$transdate,$code,$paymenttype,$amount,$login_id, $skip);
									
								}// end of if not excepted
						}// end of loop for all valid folio code in other payment source tb in the selected month and year
				}// end of record found in the other payment
				
				
			//***************************************************************************************************************
			//************* General ALLOWANCES********************//
			//***************************************************************************************************************
			////$rs_otherpay=@mysqli_query($con, "select * from allowancestb where fileno='$fileno' and item_status != 'Suspend'");// and end_date >= '$edate' and start_date <= '$sdate'");
			$rs_otherpay=@mysqli_query($con, "select * from allowancestb where fileno='$fileno' and (item_status = 'Constant' or (end_date >= '$edate' and start_date <= '$sdate')) ");
			if(@mysqli_num_rows($rs_otherpay)>0)
				{
					while($rs_other=@mysqli_fetch_array($rs_otherpay))
						{
							$code=@$rs_other['folio_code'];
						  if($rs_other['item_status'] == "Constant"){
							  //IGNORE DATE CHECK FOR CONSTANT
							  $amount=@$rs_other['amount'];
						  }else{
							if(checkpaydate($rs_other['start_date']) > 0)
								$amount=@$rs_other['amount'] * checkpaydate($rs_other['start_date']); //compute by days
							elseif(checkpaydate($rs_other['start_date']) == 0)
								$amount=@$rs_other['amount'];					//full month payment
							elseif(checkpaydate($rs_other['start_date']) == -1){
								if(checkpaydate($rs_other['end_date'], 'end') > 0)
									$amount=@$rs_other['amount'] * checkpaydate($rs_other['end_date'], 'end'); //compute by days
								elseif(checkpaydate($rs_other['end_date'], 'end') == 0)
									$amount=@$rs_other['amount'];					//full month payment
								else{
									//date not in range
									$amount=@$rs_other['amount'];					//full month payment
									$skip='yes';
								}
							}
						  }// END SATUS TEST
							$paymenttype=@$rs_other['payment_type'];
							//Check for excption from deduction_exctiontb 
							if(!excepted($fileno,$code,$j->month,$j->year))
								{
									insert_into_payroll($fileno,$department,$staffstatus,$s->category,$scalename,$s->level,$s->step,$fullname,$s->bank_name,$s->acct_no,$j->month,$j->year,$transdate,$code,$paymenttype,$amount,$login_id,$skip);
									
								}// end of if not excepted
						}// end of loop for all valid folio code in other payment source tb in the selected month and year
				}// end of record found in the other payment

			//************* General Deductions ********************//
			//************* check for distinct folio code in  deductiontb i.e deduction definition
			
			////$rs_ded=@mysqli_query($con, "select distinct folio_code from deductiontb where item_status != 'Suspend'");
			////if(@mysqli_num_rows($rs_ded)>0){
					////while($r_ded=@mysqli_fetch_array($rs_ded)){
								////$code=@$r_ded['folio_code'];
								////$paymenttype='Deduction';
								////$rs_four=@mysqli_query($con, "select distinct category, staff_status, sex, religion, level, position, scale, rank, criteria, value from deductiontb");
			$rs_four=@mysqli_query($con, "select * from deductiontb where item_status != 'Suspend'");
			if(@mysqli_num_rows($rs_four)>0)
			{
				while($r_four=@mysqli_fetch_array($rs_four))
				{
					$paymenttype='Deduction';
					$code=@$r_four['folio_code'];
					$cat=@$r_four['category'];
					$tstatus=@$r_four['staff_status'];
					$tsex=@$r_four['sex'];
					$trel=@$r_four['religion'];
					$tlevel=@$r_four['level'];
					$tposition=@$r_four['position'];
					$tscale=@$r_four['scale'];
					$trank=@$r_four['rank'];
					$tcriteria=@$r_four['criteria'];
					$tvalue=@$r_four['value'];
					
					////echo $bursary->get_deduction_definedx($r_four['id'])."<br>"; 
					
					////$definition=@explode("***",get_deduction_defintion($code,$cat,$s->category,$s->sex,$s->religion,$s->level,$tstatus,$tsex,$trel));
					
					
					//echo "$code : $staffstatus: ".get_deduction_defintion($code,$cat,$s->category,$s->sex,$s->religion,$s->level,$tstatus,$tsex,$trel)."<br>";
					/*if($definition[2] > 0)
						{
							$criteria=$definition[0];
							$value=$definition[1];
							if(strtolower($criteria)=='%basic')
								{
									$basic_code=@get_folio_code('basic salary');
									$basic_amount=@get_folio_code_amount($s->level,$s->step,$s->category,$basic_code);
									$amount=$basic_amount*$value/100;
									
								
								}// end of %basic as criteria
							elseif(strtolower($criteria)=='%gross')
								{
									$gross_amount=@get_gross_total($s->level,$s->step,$s->category);
									$amount=$gross_amount*$value/100;
								}// end of %gross  as criteria
							elseif(strtolower($criteria)=='fixed')
								{
									$amount=$value;
								}// end of fixed  as criteria															
							//Check for excption from deduction_exctiontb 
									if(!excepted($fileno,$code,$j->month,$j->year))
										{
											//check Prorata tb
											$prorata=$days=prorata($fileno,$j->month,$j->year);
											if($prorata !="")
											{
												$amount=$amount/30 *$days;
												
												insert_into_payroll($fileno,$department,$staffstatus,$s->category,$scalename,$s->level,$s->step,$fullname,$s->bank_name,$s->acct_no,$j->month,$j->year,$transdate,$code,$paymenttype,$amount,$login_id);
											}
											else
												insert_into_payroll($fileno,$department,$staffstatus,$s->category,$scalename,$s->level,$s->step,$fullname,$s->bank_name,$s->acct_no,$j->month,$j->year,$transdate,$code,$paymenttype,$amount,$login_id);
											
										}// end of if not excepted
						}//end of record found in deduction definition tb
						*/
					$definition=@explode("***",$bursary->get_deduction_definedx($r_four['id'])); 									
					list($defcat1, $defcat2, $defcat3) = @explode( "|", $definition[1] );
					$amount=0;
					if($defcat1 == "All Staff"){
						//THIS DEDUCTION IS DEFINED FOR ALL STAFF 
						if(strtolower($tcriteria)=='%Basic')
						{
							//$basic_code=@get_folio_code('basic salary');
							$basic_amount=@get_basic_pay($s->level, $s->step, $s->salary_scale);
							$amount=$basic_amount * $tvalue/100;
						}// end of %basic as criteria
						elseif(strtolower($tcriteria)=='%gross')
						{
							$gross_amount=@get_gross_total($s->level, $s->step, $s->category);
							$amount=$gross_amount * $tvalue/100;
						}// end of %gross  as criteria
					elseif(strtolower($tcriteria)=='fixed')
						{
							$amount = $tvalue;
						}// end of fixed as criteria															
						//echo @get_basic_pay($s->level, $s->step, $s->salary_scale);
					}else{
						//THIS DEDUCTION IS DEFINED BY STAFF STATUS
						list($defval1, $defval2, $defval3) = @explode( "|", $definition[2] );
					//echo  $bursary->get_any_value($defcat2, "stafftb", $defcat2, $defval2),"<br>";
					///echo $amount;
					//echo $defcat1,$defcat2, $defcat3,"<br>";
					//echo $defval1,$defval2, $defval3;
					$defval2="Nurse/Midwive";
					$computeval=false;
						if($s->category == $defval1){
							//STAFF STATUS MATCH DEDUCTION DEFINITION
							if(strtolower($defval2) == "sex" and $s->sex == $defval2){
								//Check Staff Sex
								$computeval=true;
							}elseif(strtolower($defval2) == "religion" and $s->religion == $defval2){
								//Check Staff religion
								$computeval=true;
							}elseif(strtolower($defval2) == "level" and $s->level == $defval2){
								//Check Staff Level
								$computeval=true;
							}elseif(strtolower($defval2) == "position" and $s->post == $defval2){
								//Check Staff Position
								$computeval=true;
							}elseif(strtolower($defval2) == "scale" and $s->salary_scale == $defval2){
								//Check Staff Scale
								$computeval=true;
							}elseif(strtolower($defval2) == "rank" and $s->rank == $defval2){
								//Check Staff Rank
								$computeval=true;
							}
						}
						if($computeval){
							if(strtolower($tcriteria)=='%Basic')
							{
								//$basic_code=@get_folio_code('basic salary');
								$basic_amount=@get_basic_pay($s->level, $s->step, $s->salary_scale);
								$amount=$basic_amount * $tvalue/100;
							}// end of %basic as criteria
							elseif(strtolower($tcriteria)=='%gross')
							{
								$gross_amount=@get_gross_total($s->level, $s->step, $s->category);
								$amount=$gross_amount * $tvalue/100;
							}// end of %gross  as criteria
						elseif(strtolower($tcriteria)=='fixed')
							{
								$amount = $tvalue;
							}// end of fixed as criteria															
						}
					}
					//echo $basic_code=@get_folio_code('basic salary'),"|",$basic_amount=@get_folio_code_amount($s->level,$s->step,$s->category,$basic_code);exit;
				//Check for excption from deduction_exctiontb 
						if(!excepted($fileno,$code,$j->month,$j->year))
							{
								//check Prorata tb
							  if($amount > 0){	
								$prorata=$days=prorata($fileno,$j->month,$j->year);
								if($prorata !="")
								{
									$amount=$amount/30 *$days;
									
									insert_into_payroll($fileno,$department,$staffstatus,$s->category,$scalename,$s->level,$s->step,$fullname,$s->bank_name,$s->acct_no,$j->month,$j->year,$transdate,$code,$paymenttype,$amount,$login_id);
								}
								else
									insert_into_payroll($fileno,$department,$staffstatus,$s->category,$scalename,$s->level,$s->step,$fullname,$s->bank_name,$s->acct_no,$j->month,$j->year,$transdate,$code,$paymenttype,$amount,$login_id);
							  }// END AMOUNT TEST
							}// end of if not excepted

					
				}// end of loop for distinct category,staff_status,sex,religion from deductiontb
			}// end of record found in distinct category,staff_status,sex,religion from deductiontb
						
						////}// end of loop for distinct folio_code from deductiontb
				////}// end of record found in distinct folio_code from deductiontb
		}// end of while for list of record of staff
		
		echo "Salary computation for the month of ".date("F, Y",strtotime($transdate))." has been processed sucessfully";
		$sal_sql="insert into payroll_schedule_processtb set month='$j->month',year='$j->year',transdate='$transdate',prepared_by='$login_id',date_prepared=CURDATE(),time_prepared=CURTIME(),entry_by='$login_id'";
		
		@mysqli_query($con, $sal_sql);// or die( mysqli_error($con));

}// end of if($trow>0)
else
{
	echo "<script>alert('No record found in the database for the selected criteria')";
	exit;
}// end of no record of staff found


}//end of action==compute
if($action=='delete')
{
//****** Fetch staff list from staff Table for salary computation
if($j->staff=='all')
{
	$sql="delete from payroll_scheduletb where month='$j->month' and year='$j->year' and final_approval_status !='approved'";
}// end of all staff
if($j->staff=='academic')
{
	$sql="delete from payroll_scheduletb where month='$j->month' and year='$j->year' and category='$j->staff'  and final_approval_status !='approved'";
}// end of all Academic staff
if($j->staff=='non-academic')
{
	$sql="delete from payroll_scheduletb where month='$j->month' and year='$j->year' and category='$j->staff'  and final_approval_status !='approved'";
}// end of all Non-Academic staff

if($j->staff=='specific')
{
	$fileno=@set_comma_breakdown($j->fileno);
	
	//echo $fileno;
	
	$sql="delete from payroll_scheduletb where month='$j->month' and year='$j->year' and fileno in ($fileno) and final_approval_status !='approved'";
}// end of specific  staff
if($j->staff=='non-academic_junior')
{
	
	$sql="delete from payroll_scheduletb where month='$j->month' and year='$j->year' and category='$j->staff' and staff_status='junior' and final_approval_status !='approved'";
}// end of all Non-Academic staff

if($j->staff=='non-academic_senior')
{
	$sql="delete from payroll_scheduletb where month='$j->month' and year='$j->year' and category='$j->staff' and staff_status='senior' and final_approval_status !='Approved'";
}// end of all Non-Academic staff

$r=@mysqli_query($con, $sql);
$trow= mysqli_affected_rows();

if($trow>0)
	echo "Records deleted successfully";
else
	echo "No record found for the operation ";
}// end of action==delete

}//end of salary Computation section
/////////////////////////////////// End Bursary Automation Management System ////////////////////////////////////////

//################################ HUMAN RESOURCES MANAGEMENT SYSTEM (HRMS) ########################################//

if($id=='promotion_basic_info')
{
$fileno=@$_REQUEST['fileno'];$app_year=@$_REQUEST['app_year'];$approval_status=@$_REQUEST['approval_status'];
$db->sql("select * from stafftb where fileno='$fileno'");
			if(get_magic_quotes_gpc()){ $t= @json_decode(stripslashes($db->getResult()));$s=@json_decode(stripslashes($t->data)); }
			else{ $t= @json_decode($db->getResult());  $s=@json_decode($t->data);}
			if($t->row <=0)
				{
					echo "<script>alert('No such File Number in the database');</script>";
					exit();
				}
			$scalename=@get_current_scalename();
			$fullname=@get_staff_name($fileno) ;
			$staffstatus=@get_staff_status($s->level);
			$department=@get_dept_name($s->dept_code); 
			
			$db->sql("select * from hr_promotion_apptb where fileno='$fileno' and application_year='$app_year'");
			if(get_magic_quotes_gpc()){ $t= @json_decode(stripslashes($db->getResult()));$p=@json_decode(stripslashes($t->data)); }
			else{ $t= @json_decode($db->getResult());  $p=@json_decode($t->data);}
			$rec_found=false;
			if($t->row >0)
				{
					$rec_found=true;
					//echo "$p->next_post, $p->grade_level";
				}
?><center>
<table align="center">
<tr><th>Name</th><td><?php echo $fullname;?></td><th>Age Next Birthday</th><td><?php echo date("Y")-date("Y",strtotime($s->date_of_birth));?></td></tr>
<tr><th>Date of first Appointment</th><td><?php echo  date("d/m/Y",strtotime($s->date_of_1st_appt));?></td><th>Post of first Appointment</th><td><?php echo $s->post_of_1st_appt;?> <strong>Salary :</strong> =N=<?php echo @number_format($s->initial_salary,2);?> </td></tr>
<tr><th>Present Status</th><td><?php echo "Level ".sprintf("%02d",$s->level)." / Step ".sprintf("%02d",$s->step);?>  <strong>Present Salary :</strong> <?php echo $s->present_salary;?></td><th>Date of last promotion</th><td><?php echo date("d/m/Y",strtotime($s->date_of_present_appt));?></td></tr>
<tr><th>Department</th><td><?php echo $department;?></td><th>Staff Category</th><td> <input name="category" type="text" id="category" size="25" value="<?php echo $s->category;?>" readonly/></td></tr>
<tr><th>Staff Status</th><td colspan="3"> <input name="staff_status" type="text" id="staff_status" size="25" value="<?php echo $staffstatus;?>" readonly/></td></tr>
<?php

if(isset($_REQUEST['approval_status']))
{
?>
<tr><th colspan='4'>
<?php
	if(!$rec_found)
		echo "<font color='red'><b>It seems the staff did not apply for promotion for the selected year ($app_year)</b></font>";
	else
	{
	?>
	<input type="button" name="button" id="button" value="view promotion application" class="btn" onClick="fn()"/>
	
	
	<?php
	
		$res_c=@mysqli_query($con, "select * from hr_promotion_historytb where fileno='$fileno' and application_year='$app_year'") or die( mysqli_error($con));
		if( mysqli_num_rows($res_c)>0)
			{
		?><center>
		
		<table><tr><th>S/No</th><th>Rank</th><th>Level/Step</th><th>Date Promoted</th><th>Status</th><th>Junior Staff File no</th><th>Action</th></tr>
																		
																		
		<?php
						while($rst_cl= mysqli_fetch_assoc($res_c))
						{
																									++$sn;
																$f=@$rst_cl['fileno'];$rank=@$rst_cl['rank'];
																$level=@$rst_cl['level'];$step=@$rst_cl['step'];
																$p_date=@$rst_cl['promotion_date'];$status=@$rst_cl['promotion_status'];
																$jfileno=@$rst_cl['junior_fileno'];$r_id=@$rst_cl['id'];
																		echo "<tr bgcolor='white'><td>$sn</td><td>$rank</td><td>$level / $step</td><td>$p_date</td><td>$status</td><td>$jfileno</td><td><a href=\"javascript:if(confirm('Are you sure you want to perform this operation'))swapcontent('promotion_approval','delete',$r_id);\">Delete</a></td></tr>";
																			
						}// end of while
						?></table></center><?php
		}//end of if record found
	
	?>
	<center>
	
	
	
	<table>
	     <tr>
                                              <td >Rank</td>
                                              <td><select name="rank" id="rank" class="txt">
                                                <option selected="selected" value="">---</option>
                                                <?php
												  $res_c=@mysqli_query($con, "select position from hr_positiontb where category='$s->category' order by position");
												  while($rs_c=@mysqli_fetch_array($res_c))
												   {
													  $dept_code=@$rs_c['position'];
													  $dept_name=@$rs_c['position'];
													  echo "<option value='$dept_code'>$dept_name</option>";
												   }
												  
												 ?>
                                              </select></td>
                                              <td >Level</td>
                                              <td ><select name="level" id="level" class="txt">
                                                <option selected="selected" value="">---</option>
                                                <?php
												  $res_c=@mysqli_query($con, "select level from level_categorytb order by convert(level,decimal)");
												  while($rs_c=@mysqli_fetch_array($res_c))
												   {
													  $dept_code=@$rs_c['level'];
													  $dept_name=@$rs_c['level'];
													  echo "<option value='$dept_code'>$dept_name</option>";
												   }
												  
												 ?>
                                              </select></td>
											  
											  <td >Step</td>
                                              <td ><select name="step" id="step" class="txt">
                                                <option selected="selected" value="<?php if($rec_found2) echo $p->step; else echo '';?>"><?php if($rec_found2) echo $p->step; else echo '---';?></option>
                                                <?php
												  $res_c=@mysqli_query($con, "select step from steptb order by convert(step,decimal)");
												  while($rs_c=@mysqli_fetch_array($res_c))
												   {
													  $dept_code=@$rs_c['step'];
													  $dept_name=@$rs_c['step'];
													  echo "<option value='$dept_code'>$dept_name</option>";
												   }
												  
												 ?>
                                              </select></td>
                                            </tr>
                                            <tr>
											 
                                              <td>Date Promoted</td>
                                              <td><input name="date_promoted" type="text" id="date_promoted" size="20" class="txt" value=""/></td>
                                              <td>Promotion Status</td>
											   <td><select name="promotion_status" id="promotion_status" class="txt" onchange="swapcontent('junior_fileno',this.value)">
                                                <option selected="selected" value="">---</option>
                                                <option value="Normal">Normal</option>
												 <option value="Junior - Senior">Junior to Senior</option>
												
                                              </select></td>
											  <td colspan="2"><span id='junior_fileno'></span></td>
                                            </tr>
											<tr>
													<td colspan="8" align="center"><center>
													<input type="button" name="button" id="button" value="save" class="btn" onClick="swapcontent('promotion_approval','save');"/></center>
													</td>
													</tr>
	
	</table>
	</center>
	
	<?php
	}// end of staff apply for promotion for the selected year.


?>



</th></tr>


<?php
echo "</table>";
exit();
}// end of approval status is set

?>
  
</table>
</center>
 <div id="tabss"> <!-- Start main  tab div -->
                                      <ul>
                                        <li><a href="#tabs-1"><b>Basic Application</b></a></li>
                                      
                                    </ul>
                                      
                                      <div id="tabs-1"> <!-- tab 1-staff biodata starts --->
                                         <table width="100%" border="0">
                                            <tr>
                                              <td >Next Post</td>
                                              <td><select name="next_post" id="next_post" class="txt">
                                                <option selected="selected" value="<?php if($rec_found) echo $p->next_post; else echo '';?>"><?php if($rec_found) echo $p->next_post; else echo '---';?></option>
                                                <?php
												  $res_c=@mysqli_query($con, "select position from hr_positiontb where category='$s->category' order by position");
												  while($rs_c=@mysqli_fetch_array($res_c))
												   {
													  $dept_code=@$rs_c['position'];
													  $dept_name=@$rs_c['position'];
													  echo "<option value='$dept_code'>$dept_name</option>";
												   }
												  
												 ?>
                                              </select></td>
                                              <td >Grade Level</td>
                                              <td ><select name="grade_level" id="grade_level" class="txt">
                                                <option selected="selected" value="<?php if($rec_found) echo $p->grade_level; else echo '';?>"><?php if($rec_found) echo $p->grade_level; else echo '---';?></option>
                                                <?php
												  $res_c=@mysqli_query($con, "select level from level_categorytb order by convert(level,decimal)");
												  while($rs_c=@mysqli_fetch_array($res_c))
												   {
													  $dept_code=@$rs_c['level'];
													  $dept_name=@$rs_c['level'];
													  echo "<option value='$dept_code'>$dept_name</option>";
												   }
												  
												 ?>
                                              </select></td>
                                            </tr>
                                            <tr>
                                              <td>Salary</td>
                                              <td><input name="salary" type="text" id="salary" size="40" class="txt" value="<?php if($rec_found) echo $p->salary; else echo '';?>"/></td>
                                              <td>Courses undertaken during period of report</td>
                                              <td><textarea name="course_undertaken" id="course_undertaken" cols="40" rows="3" class="txt" ><?php if($rec_found) echo $p->course_undertaken; else echo '';?></textarea></td>
                                            </tr>
                                            <tr>
                                              <td>Total number of days absent on sick/casual leave during period of report</td>
                                              <td><input name="no_of_days_absent" type="text" id="no_of_days_absent" size="40" class="txt" value="<?php if($rec_found) echo $p->no_of_days_absent; else echo '';?>"/></td>
                                              <td>Duties</td>
                                              <td><textarea name="duties" id="duties" cols="40" rows="3" class="txt"><?php if($rec_found) echo $p->duties; else echo '';?></textarea></td>
                                            </tr>
                                            <tr>
                                              <td>Acting Appointment held since last report</td>
                                              <td><textarea name="acting_appointment" id="acting_appointment" cols="40" rows="3" class="txt"><?php if($rec_found) echo $p->acting_appointment; else echo '';?></textarea></td>
                                              <td>Year granted study with/without pay/sandwich (if any)</td>
                                              <td><input name="study_leave_year" type="text" id="study_leave_year" size="40" class="txt" value="<?php if($rec_found) echo $p->study_leave_year; else echo '';?>"/></td>
                                            </tr>
                                            <tr>
                                              <td>Duration of study leave with/without pay/sandwich</td>
                                              <td><input name="study_leave_duration" type="text" id="study_leave_duration" size="40" class="txt" value="<?php if($rec_found) echo $p->study_leave_duration; else echo '';?>"/></td>
                                              <td>Qualification obtained after the course and year</td>
                                              <td><input name="qualification_obtained" type="text" id="qualification_obtained" size="40" class="txt" value="<?php if($rec_found) echo $p->qualification_obtained; else echo '';?>"/></td>
                                            </tr>
                                            <tr>
                                              <td>Present Job</td>
                                              <td><input name="present_job" type="text" id="present_job" size="40" class="txt" value="<?php if($rec_found) echo $p->present_job; else echo '';?>"/></td>
                                              <td>Job Description</td>
                                              <td><textarea name="job_description" id="job_description" cols="40" rows="3" class="txt"><?php if($rec_found) echo $p->job_description; else echo '';?></textarea></td>
                                            </tr>
                                            <tr>
                                              <td>In the order of importance, State the main duties performed during period of report</td>
                                              <td><textarea name="duties_performed" id="duties_performed" cols="40" rows="3" class="txt"><?php if($rec_found) echo $p->duties_performed; else echo '';?></textarea></td>
                                              <td>State any ad-hoc duties performed which are not of a continuous nature</td>
                                              <td><textarea name="adhoc_duties_performed" id="adhoc_duties_performed" cols="40" rows="3" class="txt"><?php if($rec_found) echo $p->adhoc_duties_performed; else echo '';?></textarea></td>
                                            </tr>
                                           
                                            
                                            <tr>
                                              <td colspan="4"><div align="center">
                                                <input type="button" name="button" id="button" value="Save/Update Record" class="btn" onClick="swapcontent('update_promotion_basic_info','basic_info');"/>
                                              </div></td>
                                            </tr>
											<tr>
											<td colspan="4">
											 <div id="tabs"> <!-- Start main  tab div -->
											  	<ul>
													<li><a href='#tabs-f1'><b>Present Qualification with Dates</b></a></li>	
													<?php
													if(strtolower($s->category)=='academic')
														{
														echo "<li><a href='#tabs-f2'><b>Scholarship and Prizes / Honours and Distinction</b></a></li>";
														echo "<li><a href='#tabs-f3'><b>Training Programme / Conference Attended</b></a></li>";
														//echo "<li><a href='#tabs-f4'><b>Honours and Distinction</b></a></li>";
														echo "<li><a href='#tabs-f5'><b>Research Interest</b></a></li>";
														echo "<li><a href='#tabs-f6'><b>Publication</b></a></li>";
														//echo "<li><a href='#tabs-f7'><b>Conference Attended</b></a></li>";
														}
													?>
												</ul>
                                      
												 <div id="tabs-f1">
												  <font color="red"><b>Deselect/untick any of your qualifications not to be used for this promotion. Cross-check your entry before submission.</b></font>
                                   				 <center>
												    <table width="70%" border="0">
                                          				<tr><th colspan="2">S/No</th><th>School Name</th><th>School Type</th><th>Qualification</th><th>Class of Degree</th><th>From</th><th>To</th></tr>
													<?php
													$rst_edu=@mysqli_query($con, "select * from hr_staff_academic_edutb where fileno='$fileno' order by from_year desc,from_month desc");
													$sn=0;
													while($rst_cl=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
															$f=@$rst_cl['fileno'];$sch_name=@$rst_cl['school_name'];
															$sch_type=@$rst_cl['school_type'];$quali=@$rst_cl['qualification'];
															$degree=@$rst_cl['degree_class'];$f_month=@$rst_cl['from_month'];$f_year=@$rst_cl['from_year'];
															$t_month=@$rst_cl['to_month'];$t_year=@$rst_cl['to_year'];
															$f_m=get_month_name($f_month); $t_m=get_month_name($t_month); 
															$val="$f***$sch_name***$sch_type***$quali***$degree***$f_month***$f_year***$t_month***$t_year";
															
															echo "<tr><td>$sn</td><td><input name='edu' type='checkbox' id='edu$sn' value='$val' checked='checked' rel='' /></td><td>$sch_name</td><td>$sch_type</td><td>$quali</td><td>$degree</td><td>$f_m, $f_year</td><td>$t_m, $t_year</td></tr>";
															
														}//end of while
													
													
													?>
													<tr>
													<td colspan="8" align="center"><center>
													<input type="button" name="button" id="button" value="save selected item(s)" class="btn" onClick="swapcontent('update_promotion_basic_info','present_qualification');"/></center>
													</td>
													</tr>
													</table>
													<div id="present_qualification">
													<?php 
													//echo "$j->fileno,$j->app_year<br>";
														echo list_present_qualification($fileno,$app_year);
													 ?>
													</div>
												</center>
												 </div><!-- tab f1 ends --->
												 <?php
												 if(strtolower($s->category)=='academic')
														{?>
												 <div id="tabs-f2"><!-- tab f2 Scholarship and Prizes --->
												 	
															<font color="red"><b>Deselect/untick any of your scholarship and prizes not to be used for this promotion. Cross-check your entry before submission.</b></font>
                                   				 <center>
												    <table width="70%" border="0">
                                          				<tr><th colspan="2">S/No</th><th>Award Type</th><th>Award Date</th><th>Award Description</th><th>Prize</th></tr>
													<?php
													$rst_sch=@mysqli_query($con, "select * from hr_staff_recognitiontb where fileno='$fileno' order by award_date desc");
													$sn=0;
													while($rst_cls=@mysqli_fetch_array($rst_sch))
														{
														++$sn;
															$f=@$rst_cls['fileno'];$award_type=@$rst_cls['award_type'];
															$award_date=@$rst_cls['award_date'];$award_desc=@$rst_cls['award_description'];
															$prize=@$rst_cls['prize'];
															$val="$f***$award_type***$award_date***$award_desc***$prize";
															
															echo "<tr><td>$sn</td><td><input name='scholar' type='checkbox' id='scholar$sn' value='$val' checked='checked' rel='' /></td><td>$award_type</td><td>$award_date</td><td>$award_desc</td><td>$prize</td></tr>";
															
														}//end of while
													
													
													?>
													<tr>
													<td colspan="8" align="center"><center>
													<input type="button" name="button" id="button" value="save selected item(s)" class="btn" onClick="swapcontent('update_promotion_basic_info','scholarship_prize');"/></center>
													</td>
													</tr>
													</table>
													<div id="scholarship_prize">
													<?php 
													//echo "$j->fileno,$j->app_year<br>";
														echo list_scholarship_prize($fileno,$app_year);
													 ?>
													</div>
												</center>
											
											
											</div><!-- tab f2 ends (Scholarship and Prizes) --->
															
												<div id="tabs-f3"><!-- tab f3 (Training Programme) --->
												
												<font color="red"><b>Deselect/untick any of your training programmes not to be used for this promotion. Cross-check your entry before submission.</b></font>
                                   				 <center>
												    <table width="70%" border="0">
                                          				<tr><th colspan="2">S/No</th><th>Training Type</th><th>Start Date</th><th>End Date</th><th>Traing Title/Theme</th><th>Location</th><th>Venue</th><th>Paper Read</th><th>Sponsor</th></tr>
													<?php
													$rst_edu=@mysqli_query($con, "select * from hr_staff_training_apptb where fileno='$fileno' order by start_date desc");
													$sn=0;
													while($rst_cl=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
															$f=@$rst_cl['fileno'];$start_date=@$rst_cl['start_date'];
															$training_type=@$rst_cl['training_type'];$end_date=@$rst_cl['end_date'];
															$training_title=@$rst_cl['training_title'];$location=@$rst_cl['location'];	
															$venue=@$rst_cl['venue'];$paper_read=@$rst_cl['no_paper_read'];
															$sponsor=@$rst_cl['sponsor'];$amount=@$rst_cl['amount_granted'];
									$val="$f***$training_type***$start_date***$end_date***$training_title***$location***$venue***$paper_read***$sponsor***$amount";
															
															echo "<tr><td>$sn</td><td><input name='training' type='checkbox' id='training$sn' value='$val' checked='checked' rel='' /></td><td>$training_type</td><td>$start_date</td><td>$end_date</td><td>$training_title</td><td>$location</td><td>$venue</td><td>$paper_read</td><td>$sponsor</td></tr>";
															
														}//end of while
													
													
													?>
													<tr>
													<td colspan="10" align="center"><center>
													<input type="button" name="button" id="button" value="save selected item(s)" class="btn" onClick="swapcontent('update_promotion_basic_info','training_section');"/></center>
													</td>
													</tr>
													</table>
													<div id="training_section">
													<?php 
													//echo "$j->fileno,$j->app_year<br>";
														echo list_training_programme($fileno,$app_year);
													 ?>
													</div>
												</center>
								
									 			</div><!-- tab f3 ends (Training Programme) --->
											<!--				
												<div id="tabs-f4"><!-- Honours and Distinction--/>
												</div><!-- tab f4 ends (Honours and Distinction)---/>
											-->			
												<div id="tabs-f5"><!-- Research Interest -->
														<font color="red"><b>Deselect/untick any of your research interest not to be used for this promotion. Cross-check your entry before submission.</b></font>
                                   				 <center>
												    <table width="70%" border="0">
                                          				<tr><th colspan="2">S/No</th><th>Research Topic</th><th>Research Status</th><th>Funding Source</th><th>Project Value</th><th>Start Date</th><th>End Date</th><th>Amount Granted</th></tr>
													<?php
													$rst_edu=@mysqli_query($con, "select * from hr_staff_researchtb where fileno='$fileno' order by start_date desc");
													$sn=0;
													while($rst_cl=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
															$f=@$rst_cl['fileno'];$start_date=@$rst_cl['start_date'];
															$topic=@$rst_cl['topic'];$end_date=@$rst_cl['end_date'];
															$status=@$rst_cl['status'];$funding_source=@$rst_cl['funding_source'];	
															$project_value=@$rst_cl['project_value'];$amount_granted=@$rst_cl['amount_granted'];
															
									$val="$f***$topic***$status***$funding_source***$project_value***$start_date***$end_date***$amount_granted";
															
															echo "<tr><td>$sn</td><td><input name='research' type='checkbox' id='research$sn' value='$val' checked='checked' rel='' /></td><td>$topic</td><td>$status</td><td>$funding_source</td><td>$project_value</td><td>$start_date</td><td>$end_date</td><td>$amount_granted</td></tr>";
															
														}//end of while
													
													
													?>
													<tr>
													<td colspan="10" align="center"><center>
													<input type="button" name="button" id="button" value="save selected item(s)" class="btn" onClick="swapcontent('update_promotion_basic_info','research_interest');"/></center>
													</td>
													</tr>
													</table>
													<div id="research_interest">
													<?php 
													//echo "$j->fileno,$j->app_year<br>";
														echo list_research_interest($fileno,$app_year);
													 ?>
													</div>
												</center>		
															
															
												</div><!-- tab f5 ends (Research Interest ) --->
															
												<div id="tabs-f6"><!-- Publication -->
												<font color="red"><b>Deselect/untick any of your publications not to be used for this promotion. Cross-check your entry before submission.</b></font>
                                   				 <center>
												    <table width="70%" border="0">
                                          				<tr><th colspan="2">S/No</th><th>TITLE</th><th>AUTHOR(S)</th><th>PUBLISHER</th><th>TYPE</th><th>CATEGORY</th><th>YEAR PUBLISHED</th></tr>
													<?php
													$rst_edu=@mysqli_query($con, "select * from hr_staff_publicationtb where fileno='$fileno' order by year_published desc");
													$sn=0;
													while($rst_cl=@mysqli_fetch_array($rst_edu))
														{
														++$sn;
															$f=@$rst_cl['fileno'];$title=@$rst_cl['title'];
															$author=@$rst_cl['author'];$type=@$rst_cl['type'];
															$publisher=@$rst_cl['publisher'];$journal=@$rst_cl['journal'];	
															$year_published=@$rst_cl['year_published'];$status=@$rst_cl['status'];
															$category=@$rst_cl['category'];$page_no=@$rst_cl['page_no'];
															$volume=@$rst_cl['volume'];$issue=@$rst_cl['issue'];
															$url=@$rst_cl['url'];
															
															
									$val="$f***$title***$author***$type***$publisher***$journal***$year_published***$status***$category***$page_no***$volume***$issue***$url";
															
															echo "<tr><td>$sn</td><td><input name='publication' type='checkbox' id='publication$sn' value='$val' checked='checked' rel='' /></td><td>$title</td><td>$author</td><td>$publisher</td><td>$type</td><td>$category</td><td>$year_published</td></tr>";
															
														}//end of while
													
													
													?>
													<tr>
													<td colspan="10" align="center"><center>
													<input type="button" name="button" id="button" value="save selected item(s)" class="btn" onClick="swapcontent('update_promotion_basic_info','publication_section');"/></center>
													</td>
													</tr>
													</table>
													<div id="publication_section">
													<?php 
													//echo "$j->fileno,$j->app_year<br>";
														echo list_publication($fileno,$app_year);
													 ?>
													</div>
												</center>		
												
												
												</div><!-- tab f6 ends (Publication)--->
											
											<!--				
												<div id="tabs-f7"><!-- Conference Attended --\>
												</div><!-- tab f7 ends (Conference Attended)---\>
											-->
														<?php }//end of category is Academic
												 ?>
											  </div> <!-- end main tab div -->
											  
											</td>
											</tr>
                                          </table>
                                          <div id="update_biodata"></div>
</div> <!-- tab 1 end --->
                                      
                                      
                                      
                                      
                              </div> <!-- end main tab div -->
<?php

}// end of promotion_basic_info



if($id=="update_promotion_basic_info")
{
	$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	
	//echo $j->state." ".$j->lga." ".$j->fileno." ".$j->sex." ".$j->date_of_birth." ".$j->nationality; exit;
	//$dob=@date('Y-m-d',@strtotime($j->dob));
	
if($action=='basic_info')
{	
	$res_c=@mysqli_query($con, "select * from hr_promotion_apptb where fileno='$j->fileno' and application_year='$j->app_year'") or die( mysqli_error($con));
	
	$login_id=@$_SESSION['login_id'];
	if(@mysqli_num_rows($res_c)<=0)
	 {
	 
		 $default_password=@base64_encode('1111');
	 mysqli_query($con, "insert into hr_promotion_apptb set fileno='$j->fileno',next_post='$j->next_post',salary='".@trim( mysqli_real_escape_string($con, $j->salary))."',course_undertaken='".@trim( mysqli_real_escape_string($con, $j->course_undertaken))."',no_of_days_absent='".@trim( mysqli_real_escape_string($con, $j->no_of_days_absent))."',duties='".@trim( mysqli_real_escape_string($con, $j->duties))."',acting_appointment='".@trim( mysqli_real_escape_string($con, $j->acting_appointment))."',grade_level='$j->grade_level',application_year='$j->app_year',study_leave_year='".@trim( mysqli_real_escape_string($con, $j->study_leave_year))."',study_leave_duration='".@trim( mysqli_real_escape_string($con, $j->study_leave_duration))."',staff_status='$j->staff_status',category='$j->category',qualification_obtained='".@trim( mysqli_real_escape_string($con, $j->qualification_obtained))."',present_job='".@trim( mysqli_real_escape_string($con, $j->present_job))."',job_description='".@trim( mysqli_real_escape_string($con, $j->job_description))."',duties_performed='".@trim( mysqli_real_escape_string($con, $j->duties_performed))."',adhoc_duties_performed='".@trim( mysqli_real_escape_string($con, $j->adhoc_duties_performed))."',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
	 
	 logs($login_id,'Save Promotion Record',"$login_id insert promotion record with fileno $j->fileno and Application Year is $j->app_year");
	 
	 } //end of save
	else
	 {
	
		 //update staff record section
		  mysqli_query($con, "update hr_promotion_apptb set fileno='$j->fileno',next_post='$j->next_post',salary='".@trim( mysqli_real_escape_string($con, $j->salary))."',course_undertaken='".@trim( mysqli_real_escape_string($con, $j->course_undertaken))."',no_of_days_absent='".@trim( mysqli_real_escape_string($con, $j->no_of_days_absent))."',duties='".@trim( mysqli_real_escape_string($con, $j->duties))."',acting_appointment='".@trim( mysqli_real_escape_string($con, $j->acting_appointment))."',grade_level='$j->grade_level',application_year='$j->app_year',study_leave_year='".@trim( mysqli_real_escape_string($con, $j->study_leave_year))."',study_leave_duration='".@trim( mysqli_real_escape_string($con, $j->study_leave_duration))."',staff_status='$j->staff_status',category='$j->category',qualification_obtained='".@trim( mysqli_real_escape_string($con, $j->qualification_obtained))."',present_job='".@trim( mysqli_real_escape_string($con, $j->present_job))."',job_description='".@trim( mysqli_real_escape_string($con, $j->job_description))."',duties_performed='".@trim( mysqli_real_escape_string($con, $j->duties_performed))."',adhoc_duties_performed='".@trim( mysqli_real_escape_string($con, $j->adhoc_duties_performed))."',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id' where fileno='$j->fileno' and application_year='$j->app_year'") or die( mysqli_error($con));
		 
		logs($login_id,'Update Promotion Record',"$login_id updated promotion record with fileno $j->fileno and Application Year is $j->app_year");
	 } //end of update staff record
	
	
	echo "<script> 
		alert('Promotion Basic Info. updated sucessfully');
		</script>";
}//end of action == basic_info


if($action=='delete_present_qualification')
{
	$rid=@$_REQUEST['r_id'];
	 mysqli_query($con, "delete from hr_promotion_academic_edutb where id='$rid'");
	echo "<script>alert('Record deleted successfully');</script>";
	echo list_present_qualification($j->fileno,$j->app_year);
}
if($action=='present_qualification')
{
	if(count($j->edu)==0)
		{
			echo "<script>alert('You have not select any Present Qualification');</script>";exit();
		}
	if(count($j->edu)==1)
		{
			$v=explode("***",$j->edu);
			 mysqli_query($con, "INSERT INTO hr_promotion_academic_edutb set fileno='$v[0]',promotion_year='$j->app_year', school_name='$v[1]', school_type='$v[2]', qualification='$v[3]', degree_class='$v[4]', from_month='$v[5]', from_year='$v[6]', to_month='$v[7]', to_year='$v[8]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
		}
	else
		{
			foreach($j->edu as $val)
			{
				$v=explode("***",$val);
				
				 mysqli_query($con, "INSERT INTO hr_promotion_academic_edutb set fileno='$v[0]',promotion_year='$j->app_year', school_name='$v[1]', school_type='$v[2]', qualification='$v[3]', degree_class='$v[4]', from_month='$v[5]', from_year='$v[6]', to_month='$v[7]', to_year='$v[8]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
				
			}// end of foreach
		}//end of selectio is more than one (else)
	echo "<script>alert('Present Qualification saved successfully');</script>";
	echo list_present_qualification($j->fileno,$j->app_year);
}//end of action == present_qualification

if($action=='delete_scholarship_prize')
{
	$rid=@$_REQUEST['r_id'];
	 mysqli_query($con, "delete from hr_promotion_recognitiontb where id='$rid'");
	echo "<script>alert('Record deleted successfully');</script>";
	echo list_scholarship_prize($j->fileno,$j->app_year);
}// end of delete_scholarship_prize


if($action=='scholarship_prize')
{
	if(count($j->scholar)==0)
		{
			echo "<script>alert('You have not select any scholarship and prizes');</script>";exit();
		}
	if(count($j->scholar)==1)
		{
			$v=explode("***",$j->scholar);
			 mysqli_query($con, "INSERT INTO hr_promotion_recognitiontb set fileno='$v[0]',promotion_year='$j->app_year', award_type='$v[1]', award_date='$v[2]', award_description='$v[3]', prize='$v[4]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
		}
	else
		{
			foreach($j->scholar as $val)
			{
				$v=explode("***",$val);
				
				 mysqli_query($con, "INSERT INTO hr_promotion_recognitiontb set fileno='$v[0]',promotion_year='$j->app_year', award_type='$v[1]', award_date='$v[2]', award_description='$v[3]', prize='$v[4]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
				
			}// end of foreach
		}//end of selectio is more than one (else)
	echo "<script>alert('Scholarship and Prize saved successfully');</script>";
	echo list_scholarship_prize($j->fileno,$j->app_year);
}//end of action == scholarship_prize

if($action=='delete_training_section')
{
	$rid=@$_REQUEST['r_id'];
	 mysqli_query($con, "delete from hr_promotion_training_apptb where id='$rid'");
	echo "<script>alert('Record deleted successfully');</script>";
	echo list_training_programme($j->fileno,$j->app_year);
}
if($action=='training_section')
{
	if(count($j->training)==0)
		{
			echo "<script>alert('You have not select any training programme');</script>";exit();
		}
	if(count($j->training)==1)
		{
			$v=explode("***",$j->training);
			 mysqli_query($con, "INSERT INTO hr_promotion_training_apptb set fileno='$v[0]',promotion_year='$j->app_year', training_type='$v[1]', start_date='$v[2]', end_date='$v[3]', training_title='$v[4]', location='$v[5]', venue='$v[6]', no_paper_read='$v[7]', sponsor='$v[8]', amount_granted='$v[9]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
		}
	else
		{
			foreach($j->training as $val)
			{
				$v=explode("***",$val);
				
				 mysqli_query($con, "INSERT INTO hr_promotion_training_apptb set fileno='$v[0]',promotion_year='$j->app_year', training_type='$v[1]', start_date='$v[2]', end_date='$v[3]', training_title='$v[4]', location='$v[5]', venue='$v[6]', no_paper_read='$v[7]', sponsor='$v[8]',amount_granted='$v[9]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
				
			}// end of foreach
		}//end of selectio is more than one (else)
	echo "<script>alert('Training Programme saved successfully');</script>";
	echo list_training_programme($j->fileno,$j->app_year);
}//end of action == present_qualification

if($action=='delete_research_interest')
{
	$rid=@$_REQUEST['r_id'];
	 mysqli_query($con, "delete from hr_promotion_researchtb where id='$rid'");
	echo "<script>alert('Record deleted successfully');</script>";
	echo list_research_interest($j->fileno,$j->app_year);
}
if($action=='research_interest')
{
	if(count($j->research)==0)
		{
			echo "<script>alert('You have not select any research interest');</script>";exit();
		}
	if(count($j->research)==1)
		{
			$v=explode("***",$j->research);
			 mysqli_query($con, "INSERT INTO hr_promotion_researchtb set fileno='$v[0]',promotion_year='$j->app_year', topic='$v[1]', status='$v[2]', funding_source='$v[3]', project_value='$v[4]', start_date='$v[5]', end_date='$v[6]', amount_granted='$v[7]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
		}
	else
		{
			foreach($j->research as $val)
			{
				$v=explode("***",$val);
				
				 mysqli_query($con, "INSERT INTO hr_promotion_researchtb set fileno='$v[0]',promotion_year='$j->app_year', topic='$v[1]', status='$v[2]', funding_source='$v[3]', project_value='$v[4]', start_date='$v[5]', end_date='$v[6]', amount_granted='$v[7]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
				
			}// end of foreach
		}//end of selectio is more than one (else)
	echo "<script>alert('Research interest saved successfully');</script>";
	echo list_research_interest($j->fileno,$j->app_year);
}//end of action == present_qualification


if($action=='delete_publication_section')
{
	$rid=@$_REQUEST['r_id'];
	 mysqli_query($con, "delete from hr_promotion_publicationtb where id='$rid'");
	echo "<script>alert('Record deleted successfully');</script>";
	echo list_publication($j->fileno,$j->app_year);
}
if($action=='publication_section')
{
	if(count($j->publication)==0)
		{
			echo "<script>alert('You have not select any publication');</script>";exit();
		}
	if(count($j->publication)==1)
		{
			$v=explode("***",$j->publication);
			 mysqli_query($con, "INSERT INTO hr_promotion_publicationtb set fileno='$v[0]',promotion_year='$j->app_year', title='$v[1]', author='$v[2]', type='$v[3]', publisher='$v[4]', journal='$v[5]', year_published='$v[6]', status='$v[7]', category='$v[8]', page_no='$v[9]', volume='$v[10]', issue='$v[11]', url='$v[12]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
		}
	else
		{
			foreach($j->publication as $val)
			{
				$v=explode("***",$val);
				
				 mysqli_query($con, "INSERT INTO hr_promotion_publicationtb set fileno='$v[0]',promotion_year='$j->app_year', title='$v[1]', author='$v[2]', type='$v[3]', publisher='$v[4]', journal='$v[5]', year_published='$v[6]', status='$v[7]', category='$v[8]', page_no='$v[9]', volume='$v[10]', issue='$v[11]', url='$v[12]', entry_date=CURDATE(), entry_time=CURTIME(), entry_by='$login_id'");
				
			}// end of foreach
		}//end of selectio is more than one (else)
	echo "<script>alert('Publication saved successfully');</script>";
	echo list_publication($j->fileno,$j->app_year);
}//end of action == present_qualification


} //end of update promotion basic info for staff

if($id=='junior_fileno')
{
echo '<center><input name="jfileno" type="text" id="jfileno" size="20" value="" /><br><font color="red"><b>Junior Staff File No<b></font></center>';

}
if($id=='promotion_approval')
{
$j=json_decode(stripslashes($_REQUEST['mydata']));
	$action=@$_REQUEST['action'];
	
	//echo $j->state." ".$j->lga." ".$j->fileno." ".$j->sex." ".$j->date_of_birth." ".$j->nationality; exit;
	//$dob=@date('Y-m-d',@strtotime($j->dob));
	
if($action=='save')
{	
$res_c=@mysqli_query($con, "select * from hr_promotion_historytb where fileno='$j->fileno' and application_year='$j->app_year'") or die( mysqli_error($con));
	
	$login_id=@$_SESSION['login_id'];
	if(@mysqli_num_rows($res_c)<=0)
	 {
	 
		 $default_password=@base64_encode('1111');
		 $prev=explode("***",get_staff_previous_promotion($j->fileno));
	 mysqli_query($con, "insert into hr_promotion_historytb set fileno='$j->fileno',rank='$j->rank',level='$j->level',application_year='$j->app_year',step='$j->step',promotion_date='".@trim( mysqli_real_escape_string($con, $j->date_promoted))."',promotion_status='$j->promotion_status',junior_fileno='$j->jfileno',prev_rank='$prev[0]',prev_level='$prev[1]',prev_step='$prev[2]',prev_date_of_present_appt='$prev[3]',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$login_id'") or die( mysqli_error($con));
	 
	 mysqli_query($con, "update stafftb set rank='$j->rank',level='$j->level',step='$j->step',date_of_present_appt='".@trim( mysqli_real_escape_string($con, $j->date_promoted))."' where fileno='$j->fileno'") or die( mysqli_error($con)); 
	 logs($login_id,'Save Promotion Approval',"$login_id insert promotion approval record with fileno $j->fileno and Application Year is $j->app_year");
	 echo "<script>alert('Record saved successfully');</script>";
	 
	 } //end of record not found
}// end of action is save
if($action=='delete')
{
	$rid=@$_REQUEST['r_id'];
	$prev=explode("***",get_staff_previous_promotion_history($rid));
	  mysqli_query($con, "update stafftb set rank='$prev[0]',level='$prev[1]',step='$prev[2]',date_of_present_appt='".@trim( mysqli_real_escape_string($con, $prev[3]))."' where fileno='$j->fileno'") or die( mysqli_error($con)); 
	 mysqli_query($con, "delete from hr_promotion_historytb where id='$rid'");
	echo "<script>alert('Record deleted successfully');</script>";
	
}//end of delete

}// end of Promotion _approval

if($id=="get_category"){
	$sc=$_REQUEST['scalname'];
	if($sc != ''){
		$q= mysqli_query($con, "select category from scale_nametb where scale_name='". mysqli_real_escape_string($con, $sc)."'");
		if($r= mysqli_fetch_array($q, 3 )){
			echo '<input id="category" name="category" value="'.$r[0].'" type="hidden" /><strong>'.$r[0].'</strong>';
		}
	}
}


if($id=="load_allowances"){
	$sc=$_REQUEST['scalname'];
	if($sc != ''){
		$w= mysqli_query($con, "select allowance_code from consolidated_paytb where category='". mysqli_real_escape_string($con, $sc)."' and status='Active' order by allowance_code") or die( mysqli_error($con));
		$in_var="'XxX_XxX'";
		while($s =  mysqli_fetch_array($w, 3 )) $in_var .= ",'".$s['allowance_code']."'";
		echo '<table border="0" align="left" cellpadding="3" cellspacing="0">';
		//$r=@mysqli_query($con, "select distinct * from salary_codetb where account_code in ( $in_var ) order by account_code ");
		$r=@mysqli_query($con, "select distinct * from foliotb where b_unit='SLA' and folio_code in ( $in_var ) order by folio_code ");
		 $n=0;$av="";$tab_index=4;
		 while($rl=@mysqli_fetch_array($r))
				{
					++$n;$tab_index++;
					//$acctcode=@$rl['account_code'];
					$acctcode=@$rl['folio_code'];
					$bankname=@$rl['title'];
					$v=$acctcode;
					echo "<tr><th nowrap align='left' width='40%'>
					<input name='code[]' id='code$n' class='code_checked' type='checkbox' value='$v' checked='checked' />
					<!--</th><th>-->$bankname</th>
					<th align='left'><input type='text' name='amount[]' class='amt' id='amount$n' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' tabindex='$tab_index' onkeydown='sum()' ></th></tr>";
				}// end of while
				echo '</table>';
	}
}

?>