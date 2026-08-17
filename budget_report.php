<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Budget Report</title>
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
$year=$_REQUEST['year']; $month=$_REQUEST['month'];
$montht=$_REQUEST['montht'];
require_once "function_c.php";
//echo "$category $level";
//////////////////////////////////////////////////////Report header /////////////////////////////////////////////
$val=explode("***",get_company());
///////////////////////////////////////////////////// End of header ////////////////////////////////////////////


//////////////////////////////////////////////////////Report Body /////////////////////////////////////////////
?>
<?php echo "<center><img src='$val[1]' width='50' height='50' style='float:center'/><!--<img src='images/uith.png' width='50' height='50' style='float:right'/>--></center><b><p align='center'><u>".strtoupper($val[0])."<br/>BURSARY DEPARTMENT<br/>"."BUDGET PERFORMANCE FOR ".strtoupper(get_month_name($month)). "&nbsp; {$year} TO ".strtoupper(get_month_name($montht)). " {$year}</u></p></b><hr><p>";

 
$sql_folio=@mysqli_query($con, "select distinct folio_code from budgettb where budget_year = '$year' order by folio_code");
  ?>
                                                                                                                                                                                                                                                                              <center>
  <table width="70%" border="1" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th>S/N</th>
    <th>ACCOUNT CODE</th>
    <th>CODE DESCRIPTION</th>
    <th>TOTAL BUDGET</th><th>EXPENCES</th><th>BALANCE</th>
	</tr>

 <?php
 $sn=0;
   while($rst_folio=@mysqli_fetch_array($sql_folio))
   {
  ++$sn; 
  $folio=$rst_folio['folio_code'];
  //$folio_name=get_folio_name($folio);
  (get_folio_name($folio)!='') ? $folio_name=get_folio_name($folio) : $folio_name=get_dept_name_act($folio);
  $folio_budget=get_folio_budget($folio, $year);
  $folio_budget_expences=get_folio_budget_expences($folio,$month ,$year);
  $folio_budget_bal= get_budget_balance($folio,$month,$year);
      echo "<tr>";
	  echo "<td> $sn </td><td nowrap height='25'>$folio</td><td>$folio_name</td><td><div align='right'>".@number_format($folio_budget,2)."</div></td><td><div align='right'>".@number_format($folio_budget_expences,2)."</div></td><td><div align='right'>".@number_format($folio_budget_bal,2)."</div></td> </tr>";
  }
  ?>
</table>   
</center>
<p>&nbsp;</p>
</body>
</html>