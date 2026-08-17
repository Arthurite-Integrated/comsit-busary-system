<?php
///&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&&& This function convert numeric amount in naira to word &&&&&&&&&&&&&&&&&&&&&
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$ones = array(
 "",
 " one",
 " two",
 " three",
 " four",
 " five",
 " six",
 " seven",
 " eight",
 " nine",
 " ten",
 " eleven",
 " twelve",
 " thirteen",
 " fourteen",
 " fifteen",
 " sixteen",
 " seventeen",
 " eighteen",
 " nineteen"
);

$tens = array(
 "",
 "",
 " twenty",
 " thirty",
 " forty",
 " fifty",
 " sixty",
 " seventy",
 " eighty",
 " ninety"
);

$triplets = array(
 "",
 " thousand",
 " million",
 " billion",
 " trillion",
 " quadrillion",
 " quintillion",
 " sextillion",
 " septillion",
 " octillion",
 " nonillion"
);

// returns the number as an anglicized string
function convertNum($num) {
 $num = (int) $num;    

 if ($num < 0)
  return "negative".convertTri(-$num, 0);

 if ($num == 0)
  return "zero";

 return convertTri($num, 0);
}

 // recursive fn, converts three digits per pass
 function convertTri($num, $tri) {
  global $ones, $tens, $triplets;

  // chunk the number, ...rxyy
  $r = (int) ($num / 1000);
  $x = ($num / 100) % 10;
  $y = $num % 100;

  // init the output string
  $str = "";

  // do hundreds
  if ($x > 0) {
   $myLen = strlen($num);
   if (substr($num,$myLen-2,2) != '00') { $str = $ones[$x] . " hundred and"; } else {$str = $ones[$x] . " hundred";}
   				}
   
  // do ones and tens
  if ($y < 20)
   $str .= $ones[$y];
  else
   $str .= $tens[(int) ($y / 10)] . $ones[$y % 10];

  // add triplet modifier only if there
  // is some output to be modified...
  if ($str != "")   {  
   $str .= $triplets[$tri]; 
   					}
   

  // continue recursing?
  if ($r > 0)
   return convertTri($r, $tri+1).$str;
  else
   return $str;
 }

?>