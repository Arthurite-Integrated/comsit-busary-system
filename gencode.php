<?php 
 function get_location($loc_code)
 {
	 $res_p=@mysqli_query($con, "select * from locationtb where loc_code='$loc_code' limit 1");
	 $rs_p=@mysqli_fetch_array($res_p);
	 $val=@$rs_p['dept']."||".@$rs_p['unit']."||".@$rs_p['room_no'];
	 return($val);
 }


function convertdate($b, $a)
{
	/*echo "<script>alert('$a');</script>";*/
	if($b=='display')
	{
		if(substr($a,-3,1)=='-')
	{
		//echo 'found / ';
		$date_of_birth = explode('-',$a);
		$date = $date_of_birth[1].'/'.$date_of_birth[2].'/'.				$date_of_birth[0];
	}
	/*echo "<script>alert('$date');</script>";*/
	return $date;
	}
	else if($b=='save')
	{
				if(substr($a,-5,1)=='/')
	{
		//echo 'found / ';
		$date_of_birth = explode('/',$a);
		$date = $date_of_birth[2].'-'.$date_of_birth[0].'-'.				$date_of_birth[1];
	}
	/*echo "<script>alert('$date');</script>";*/
	return $date;

	}
}
function identification_consumables($length, $nums){

	//$_SESSION['pno']='';

		$lowLet =  "ZYXWVUTSRQPONMLKJIHGFEDCBA";

		$highLet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

		$numbers = "123456789";

		$p_no = "";

		$i = 1;

		While ($i <= $length){

			$type = rand(0,1);

			if ($type == 0){

				if (($length-$i+1) > $nums){

					$type2 = rand(0, 1);

					if ($type2 == 0){

						$ran = rand(0, 25);

						$p_no .= $lowLet[$ran];

					}else{

						$ran = rand(0, 25);

						$p_no .= $highLet[$ran];

					}

				}else{

					$ran = rand(0, 8);

					$p_no .= $numbers[$ran];

					$nums--;

				}

			}else{

				if ($nums > 0){

					$ran = rand(0, 8);

					$p_no .= $numbers[$ran];

					$nums--;

				}else{

					$type2 = rand(0, 1);

					if ($type2 == 0){

						$ran = rand(0, 25);

						$p_no .= $lowLet[$ran];

					}else{

						$ran = rand(0, 25);

						$p_no .= $highLet[$ran];

					}

				}

			}

			$i++;

		}

		//echo  "<input name='gencod' type='hidden' id='gencod' value='$p_no'>";

		//echo $p_no;

		//$_SESSION['pno'] = $p_no;

		return $p_no;

}

/*require_once "riderdbcon.php";

function write_permit_no_serial($lengthofdigits){

	$ccount=0;

	$q= mysqli_query($con, "select count(recid) from riderstable_main");

	if ($r= mysqli_fetch_array($q, 3 )) $ccount = $r[0] + 1;

	if ($ccount == 0){

		return str_pad("1", $lengthofdigits, "0", STR_PAD_LEFT);

	} else {

		if ($lengthofdigits == 0){

			//no lenghgt specified

			return $ccount;

		}else{

			//lenght specified

			$ezeros = '';

			$xlen = strlen($ccount);

			$newlen = $lengthofdigits - $xlen;

			return str_pad($ccount, $lengthofdigits, "0", STR_PAD_LEFT);

			

		}

	}

}
*/
?>