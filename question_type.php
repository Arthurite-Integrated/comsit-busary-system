<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Question Type</title>
</head>

<body>
<?php
@ini_set('max_execution_time', 60000000000);
 @ini_set("memory_limit", "51200M");

@include("connect.php");
$session=@$_REQUEST['session'];
$type=@$_REQUEST['type'];
$no_of_question=@$_REQUEST['no_of_question'];
$heading="<strong>SECTION B.</strong><br>

Answer ALL questions in this section by circling the correct options on the question paper. Write 

your names and number on the Question Paper and tuck the Question paper into the Answer Script 

before submission.";

if(isset($_REQUEST['btn_generate']))
{
//echo "$session==$type==$no_of_question";
				$rs=@mysqli_query($con, "select * from question_banktb where session='$session' ORDER BY RAND()");
				$n=0;$question_list="";
				while($rst=@mysqli_fetch_array($rs))
				{
					$n++;
					$q_id=@$rst['question_id'];
					$q_d=@stripslashes($rst['question_detail']);
					$q_d=@str_replace("�",".",$q_d);
					$q_d=@str_replace('"',"",$q_d);
					$q_ans=@$rst['correct_answer'];
					//echo $q_d;
					if(@mysqli_query($con, "insert into question_typetb set question_id='$q_id',serial_no='$n',type='$type',session='$session',entry_date=CURDATE(),entry_time=CURTIME(),entry_by='$entryby'"))
					 {
						  $question_list .="<br>$n. $q_d<br>";
						  $letter="Z";$i=0;
						  $opt="";
						  $rs_opt=@mysqli_query($con, "select * from question_optiontb where question_id='$q_id' ORDER BY RAND()");
						 while($rstopt=@mysqli_fetch_array($rs_opt))
							{
								$letter++;
								$letter=substr($letter,-1);
								$opt_d=@stripslashes($rstopt['option_detail']);
								
								$opt .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$letter.&nbsp;&nbsp;$opt_d<br>";
								if($opt_d==$q_ans)
									{
									@mysqli_query($con, "update question_typetb set correct_option_letter='$letter' where question_id='$q_id' and serial_no='$n' and type='$type' and session='$session'");
									//$opt .="<font color='red'><strong>".$opt_d."</strong></font><br>";
									}
									
								///else
									//$opt .="$opt_d<br>";
									
									@mysqli_query($con, "insert into question_type_optiontb set question_id='$q_id',type='$type',option_letter='$letter',option_detail='$opt_d',session='$session'") or die( mysqli_query($con, ));
									
							}// end of While for questions options
							$question_list .=$opt;
						}//end of insert into question type tb
				}// end of While for questions
				
				$code=base64_encode("123".substr($type,-1));
				$code=str_replace("=","",$code);
				echo "<strong><u><i>$type Questions</u></i></strong><br><strong><u><i>Questions Code : $code</u></i></strong><br><br>$heading<br><br><br>".$question_list;
}// end of isset btn_generate
if(isset($_REQUEST['btn_print']))
{

$rs=@mysqli_query($con, "select * from question_typetb t,question_banktb b where t.question_id=b.question_id and t.session='$session' and t.type='$type' order by t.serial_no")or die( mysqli_query($con, ));;
					if( mysqli_num_rows($rs)<=0)
					{
						echo "<script>alert('No record to display.');window.close();</script>";
						exit();
					}
				$n=0;$question_list="";
				while($rst=@mysqli_fetch_array($rs))
				{
					$n++;
					$q_id=@$rst['question_id'];
					$q_d=@stripslashes($rst['question_detail']);
					$q_d=@str_replace("�",".",$q_d);
					$q_d=@str_replace('"',"",$q_d);
					$q_ans=@$rst['correct_answer'];
					$question_list .="<br>$n. $q_d<br>";
					  $letter="Z";$i=0;
					  $opt="";
					  $rs_opt=@mysqli_query($con, "select * from question_type_optiontb where question_id='$q_id' and type='$type' and session='$session' order by option_letter")or die( mysqli_query($con, ));;
					 while($rstopt=@mysqli_fetch_array($rs_opt))
					  	{
							$letter++;
							$letter=substr($letter,-1);
							$opt_d=@stripslashes($rstopt['option_detail']);
							
							$opt .="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$letter.&nbsp;&nbsp;$opt_d<br>";
							/*if($opt_d==$q_ans)
								$opt .="<font color='red'><strong>".$opt_d."</strong></font><br>";
							else
								$opt .="$opt_d<br>";
							*/	
						}// end of While for questions options
						$question_list .=$opt;
				}// end of While for questions
				$code=base64_encode("123".substr($type,-1));
				$code=str_replace("=","",$code);
				echo "<strong><u><i>$type Questions</u></i></strong><br><strong><u><i>Questions Code : $code</u></i></strong><br><br>$heading<br><br><br>".$question_list; 
}//end of Re-print
if(isset($_REQUEST['btn_print_answer']))
{
$rs=@mysqli_query($con, "select * from question_typetb t,question_banktb b where t.question_id=b.question_id and t.session='$session' and t.type='$type' order by t.serial_no")or die( mysqli_query($con, ));;
				$n=0;$question_list="";
				if( mysqli_num_rows($rs)<=0)
					{
						echo "<script>alert('No record to display.');window.close();</script>";
						exit();
					}
				while($rst=@mysqli_fetch_array($rs))
				{
					$n++;
					$q_id=@$rst['question_id'];
					$q_d=@stripslashes($rst['question_detail']);
					$q_d=@str_replace("�",".",$q_d);
					$q_d=@str_replace('"',"",$q_d);
					$q_ans=@$rst['correct_answer'];
					$q_ans_letter=@$rst['correct_option_letter'];
					$question_list .="$n. ($q_ans_letter). $q_ans<br>";
					  
				}// end of While for questions
				
				$code=base64_encode("123".substr($type,-1));
				$code=str_replace("=","",$code);
				echo "<strong><u><i>$type Questions</u></i></strong><br><strong><u><i>Questions Code : $code</u></i></strong><br><br>$heading<br><br><br>".$question_list;
				 
}//end of Print Answer
if(isset($_REQUEST['btn_delete']))
{
	if(@mysqli_query($con, "delete from question_type_optiontb where type='$type' and session='$session'"))
	{
	
		@mysqli_query($con, "delete from question_typetb where type='$type' and session='$session'");
		echo "<script>alert('$session $type question(s) were deleted successfully.');window.close();</script>";
	}
	else
	{
		echo "<script>alert('No record to display.');window.close();</script>";
		exit();
	}
}//end of delete generated questions type
?>

</body>
</html>
