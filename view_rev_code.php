<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>REVENUE CODES</title>
<link rel="shortcut icon" href="images/logox.png"> <!-- put the image/logo on the browser tab -->
<style>
body {
font : "Times New Roman", Times, serif; /*helvetica, verdana, "Trebuchet MS", tahoma, arial, sans-serif;*/
/*line-height : 2em;*/
/*background : #fff url(../images/bg.gif) repeat-x;*/
}
</style>

      
</head>

<body>
<?php
$mode=base64_decode($_REQUEST['mode']);
$year=$_REQUEST['pyear'];
$from=$_REQUEST['from']; 
 $to=$_REQUEST['to']; 
require_once "function_c.php";
//echo "$category $level";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'><u>".strtoupper($val[0])."<br/>"."REVENUE CODES"."</u></p></b><hr><p>";
  ?>
                                                                                                                                                                                                                                                                              <center>
  <table width="100%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th>S/N</th>
    <th>CODE DESCRIPTION</th>
    <th>ACCOUNT CODE</th>
    <th>REVENUE NAME</th>
   
    <th> REVENUE CODE</th>
   
	</tr>

 <?php
 	$bcat= mysqli_query($con, "SELECT DISTINCT category FROM revenue_code order by ord");
	$total_b = 0; 		$total_s = 0;		$total_v = 0;
	while($yq =  mysqli_fetch_array($bcat, 3 )){
	  echo "<tr bgcolor='#999'>";
	  echo "<th> </th>
			<th colspan='4' align='left'>".$yq[0]."</th></tr>";
	 $sn=0;	
	 $sub_total_b = 0; 	$sub_total_s = 0;	$sub_total_v = 0;
	 $sql_folio=@mysqli_query($con, "select *  from revenue_code where category = '".$yq[0]."' order by folio_code");
	   while($rst_folio=@mysqli_fetch_array($sql_folio))
	   {
	  ++$sn; 
	  $folio=$rst_folio['folio_code'];
	   $reven_title=$rst_folio['reven_title'];
	    $rev_code=$rst_folio['rev_code'];
	  //$folio_name=get_folio_name($folio);
	  (get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
	  //$folio_budget=get_folio_budget($folio, $year);
	 // $sub_total_b += $folio_budget;			$total_b += $sub_total_b;
	  //$folio_budget_expences=get_folio_budget_expences($folio,$month ,$year);
	  $folio_budget_generate1=folio_budget_generate3($folio,$from);
	  
	  $folio_budget_generate2= explode('***',$folio_budget_generate1);
	  $folio_budget_generate = $folio_budget_generate2[0];
	  $folio_recipt = $folio_budget_generate2[1];
	  $sub_total_s += $folio_budget_generate;	
	 
	//  $get_var_balance= get_var_balance($folio,$year);
	 // $sub_total_v += $get_var_balance;		$total_v += $sub_total_v;
		  echo "<tr>";
		  echo "<td> $sn </td>
				<td>$folio_name</td>
				<td nowrap height='25'>$folio</td>
				<td>$reven_title</td>
				
				<td>$rev_code</td>
				</tr>";
	  }
	   $total_s += $sub_total_s;
		//  echo "<tr bgcolor='#ccc'>";
		//  echo "<td></td>
				//<th colspan='3'>SUB TOTAL</th>
				//<td><div align='right'>".@number_format($sub_total_s,2)."</div></td>
				
				//</tr>";
	}
		 // echo "<tr bgcolor='#ccc'>";
		  //echo "<td></td>
			//	<th colspan='3'>GRAND TOTAL</th>
				//<td><div align='right'>".@number_format($total_s,2)."</div></td>
				
				// </tr>";
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>