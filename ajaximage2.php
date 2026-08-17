<?php
include('connect.php');
include( 'function_resize.php');
@session_start();
$session_id='1'; //$session id

$regno=strtoupper(@$_REQUEST['fn']); //value of the criteria
$status=@$_REQUEST['upload_type'];
//$status=@$_REQUEST['status'];
$tablename=@$_REQUEST['tablename'];
$foldername=@$_REQUEST['foldername'];
$cri_field=@$_REQUEST['cri_field'];

$filepath1="putme_documents/.htaccess";
$filepath2="pictures/.htaccess";
$filepath3="upload_files/.htaccess";
$filepath4="pictures/.htaccess";


/*if(@file_exists($filepath2)) echo "***";
if(@file_exists($filepath3)) echo "###";*/

@unlink($filepath4);
@unlink($filepath3);
@unlink($filepath2);
@unlink($filepath1);


	$valid_formats = array("jpg", "jpeg");
	if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")
		{
			if($status=='pix'){
			$name = $_FILES['photoimg_pix']['name'];
			$size = $_FILES['photoimg_pix']['size'];
			}
			elseif($status=='sign')
			{
			$name = $_FILES['photoimg_sign']['name'];
			$size = $_FILES['photoimg_sign']['size'];
			}
			//echo $regno.'   '.$name.'    '.$status; exit;
//$regno='S5981';
			if(strlen($name) and $regno!="")
				{
					list($txt, $ext) = explode(".", $name);
					if(in_array($ext,$valid_formats))
					{
					if($size<(1024*25) && ($status=='pix'))
						{
								$files[] = resize(140, 140, str_replace("/","",$regno),$status);
										 $actual_image_name = @str_replace("/","",$regno).".".'jpg';
								//	 echo "<img src='pictures/".$actual_image_name."'  id='display' width='150' height='150'>";	
					
					
					
					//				 echo "<img src='pictures/".$actual_image_name."'  class='preview1' width='150' height='150'>";	
					echo "<img src='pictures/".$actual_image_name."'  class='preview2' width='150' height='150'>";	
									// echo $actual_image_name; exit;
									
	
						}
						elseif($size<(1024*25) && ($status=='sign'))
						{
							$save_name = @str_replace("/","",$regno)."_sign";
							$files[] = resize(140, 70,$save_name,$status);
										 $actual_image_name = @str_replace("/","",$regno)."_sign.".'jpg';
									//	 echo $actual_image_name;exit;
									 echo "<img src='pictures/".$actual_image_name."'  class='preview1' width='150' height='75'>";	
							
						}
						else
						echo "Image file size max is 25KB";					
						}
						else
						echo "Invalid file format.Only .jpg format is allowed";	
				}

			else //else first if
				echo "Please select image..!";
				
			exit;
		}

?>
<?php //$ok = rename($fullpath, $passport);

/* var pix = 'pictures/'+fileno+'.jpg';
			  var sign = 'pictures/'+fileno+ '_sign' +'.jpg';
			 // alert(pix);


$("#display").html('<img src="'+ pix +'" width="100" height="100">').show();
$("#roll").html('<img src="'+ sign +'" width="100" height="100">').show();
*/


?>


