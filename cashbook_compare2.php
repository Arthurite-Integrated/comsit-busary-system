<?php @session_start();
if(!isset($_SESSION['userLogin']) and !isset($_SESSION['login_id']) and !isset($_SESSION['login_status']) and !isset($_SESSION['role']) and $_SESSION['userLogin']!='ok')
   {
	   echo "<script>location='index.php';</script>";
   }
    $r_vals=@base64_decode($_REQUEST['r_val']);
$role=@$_SESSION['role'];
$login_status=@$_SESSION['login_status'];
 $login_id=@$_SESSION['login_id'];
 $login_id_base=@base64_encode($login_id);
 //$role=@$_SESSION['role'];
 $staff_category=@$_SESSION['staff_category'];
$rv=base64_decode($_REQUEST['r_val']);

if(strtolower($rv) == "cash officer") $role_cap="MAKE PAYMENT";
else $role_cap = $rv;

//$role_cap = base64_decode($_REQUEST['r_val']);

?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<?php include("required_jQuery_files.php");
include "function.php";?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="include/colorbox.css">
<script type="text/javascript" src="include/jquery.colorbox.js"></script> 
<!--[if IE 6]><link href="default_ie6.css" rel="stylesheet" type="text/css" /><![endif]-->
<script>
	$(document).ready(function(){
		$(".iframe").colorbox({iframe:true, width:"53%", height:"100%"});
	});
</script>
<script src="include/jquery.dataTables.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="include/jquery.dataTables.min.css">
 <script type="text/javascript">
 $(document).ready(function() {
    $('#dTable').DataTable( {
        initComplete: function () {
            this.api().columns().every( function () {
                var column = this;
                var select = $('<select><option value=""></option></select>')
                    .appendTo( $(column.footer()).empty() )
                    .on( 'change', function () {
                        var val = $.fn.dataTable.util.escapeRegex(
                            $(this).val()
                        );
 
                        column
                            .search( val ? '^'+val+'$' : '', true, false )
                            .draw();
                    } );
 
                column.cells('', column[0]).render('display').sort().unique().each( function ( d, j ) {
                    if(column.search() === '^'+d+'$'){
                        select.append( ''+d+'' )
                    } else {
                        select.append( ''+d+'' )
                    }
                } );
            } );
        }
    } );
} );

$(function(){
   $('#checkAll').click(function(){
      if (this.checked) {
         $(".checkboxes").prop("checked", true);
      } else {
         $(".checkboxes").prop("checked", false);
      }	
   });
});
</script>

<script>
     function sendRequest(requestID, loaderDiv='', recID=''){
          var url="script-fac.php";
          var loader='<img src="images/loader.gif" height="30" alt="loading">Workiing...';
          if(requestID=="movePosting"){
               $("#"+requestID).html(loader).show();
               $.post(url, $("#frmEdit").serialize()+"&requestID="+requestID, function(response){
                    $("#"+requestID).html(response);
               });
          }
     }

     
     function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
     {   //swap content begins where cv means div id name
          var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
          $(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
          $("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
          var url="scriptfile_a.php";
          var str;
          
          if(cv=='edit_posted_entry') //start putme_login
          {
                    $.post(url,{contentvar:cv,trans_id:v,r_vals:a,action:b},function(data){
                    
                         $(divid).html('').show(); 
                         $("#display").html("<h3><center>COMPARE CASHBOOK ENTRIES</center><span class='close' id='close' onclick='modal.style.display = \"none\";'>X</span></h3><hr>" + data).show();
                         $("#display").window("open");
                         $("#roll").html('').show();
                    });
          }

          if(cv=='save_posted_entry') //start putme_login
          {
                    $.post(url,$("#update_trans").serialize()+"&contentvar="+cv,function(data){  
                         $(divid).html(data).show(); 
                         $("#roll").html('').show();
                         //document.location.reload();
                    });
          }
     }
     </script>
</head>
<body class="subpage">

<div id="tooplate_wrapper">
	<div id="tooplate_sidebar">
	<?php include_once("sidebar_main.php"); ?>
    </div> <!-- end of sidebar tooplate_sidebar-->
	
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
            <?php include_once("menu_main.php"); ?>
        </div> <!-- end of tooplate_menu -->
        
        <div id="content_title_box">
	        <h2>COMPARE MANUAL CASHBOOK AND REMITA PAYMENT ENTRIES</h2>
                <p><?php echo $role_cap; ?></p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div> -->
                <h3><i>&nbsp;</i></h3>
	      <form method='post' id='frmEdit' name='frmEdit' action="<?=$_SERVER['PHP_SELF']."?r_val=".$_REQUEST['r_val'];?>">
                    <hr>
			MONTH: <select id="rmonth" name="rmonth">
				<option value="">---</option>
				<option value="January">January</option>
				<option value="February">February</option>
				<option value="March">March</option>
				<option value="April">April</option>
				<option value="May">May</option>
				<option value="June">June</option>
				<option value="July">July</option>
				<option value="August">August</option>
				<option value="September">September</option>
				<option value="October">October</option>
				<option value="November">November</option>
				<option value="December">December</option>
			</select> / 
			YEAR: <select id="ryear" name="ryear">
				<option value="">---</option>
				<option value="2024">2024</option>
				<option value="2025">2025</option>
				<option value="2026">2026</option>
				<option value="2027">2027</option>
				<option value="2028">2028</option>
				<option value="2029">2029</option>
				<option value="2030">2030</option>
               </select>  | 
               ACCOUNT: <select name="account" id="account" class="txt" style="width: 200px"  >
                  <option selected="selected">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct * from bank_accounttb where status='Active' order by acctname");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['acctname'];$pcode=@$rcourse['acctcode'];
							$bank=@$rcourse['bankname'];$acctno=@$rcourse['acctno'];
							$acctname=@$rcourse['acctname'];
							echo "<option value='$pcode'> $pcode <=> $scourse</option>";
							
						}
					
					?>
                </select>
			|  <input name="mode" type="hidden" id="mode" value="upload" /> <input name="button" type="submit" class="btn" id="button" value="SUBMIT" />
		  <p>
          
<!-- begining of main tab-->
          <!-- pending tab  -->
  <?php
     if(isset($_POST['mode']) && $_POST['mode']=='upload'){
	     $r=@strtolower(base64_decode($_REQUEST['r_val']));
		//if($r=="super admin" or $r=="accountant" or $r=="administrator")
		$from = $_POST['rmonth']; $to = $_POST['ryear'];
		$sql="SELECT sum(amount) as amount, pvno, rmonth, ryear FROM cashbooktb WHERE rmonth = '{$from}' AND ryear = '{$to}' GROUP BY pvno, rmonth, ryear ORDER BY pvno";
		//distinct pvno, transdate, amount, dept_acctcode, acctcode
		$res_v=@mysqli_query($con, $sql);
		$sn=0;
		echo "<center><h1>{$from}/{$to} Reconciliation</h1></center>
          <table align='left' border='1' cellpadding='5' cellspacing='0' rules='rows' frame='box' width='100%' class='displayX' id='dTableX'> 
		<thead>
          <tr><th colspan='3'>MANUAL CASHBOOK</th><td style='background-color: black;'>&nbsp;</td><th colspan='3'>REMITA</th></tr>
		<tr><!--th><input type='checkbox' id='checkAll' name='checkAll' value='{$rs_v['pvno']}'></th-->
          <th>S/NO</th>
                    <th>PV. NO.</th><th>AMOUNT</th>
                    <th style='background-color: black;'>&nbsp;</th><th>AMOUNT</th><th>MONTH</th><th>DIFFERENCE</th></tr></thead><tbody>";
	     if(@mysqli_num_rows($res_v) >= 1){
               $mon=get_month_code($from);
               $pvno_array="'X' ";
			while($rs_v=@mysqli_fetch_array($res_v,3))
			{
                    ///$s=mysqli_query($con, "SELECT * FROM transtb WHERE pvno='{$rs_v['journalno']}'");
                    ///if(mysqli_num_rows($s) > 0) continue;
				++$sn;
                    $tid=$rs_v['id'];
				$pvno=str_replace('/', '', $rs_v['pvno']);
                    $p=base64_encode($pvno);
				//$acct=$rs_v['acctcode'];
                    $amount=$rs_v['amount'];
				$imonth=$rs_v['rmonth'];
				$iyear=$rs_v['ryear'];
                    $y=date('y', strtotime($iyear."-01-01"));
				$color = "";
				echo "<tr><!--td><input type='checkbox' class='checkboxes' id='pv_jv_{$rs_v['pvno']}' name='pv_jv[]' value='{$rs_v['pvno']}'></td-->";
                    echo "<td>$sn</td>
                    <td>{$pvno}</td>
                    <td>".number_format($amount, 2)."</td>
                    <td style='background-color: black;'>&nbsp;</td>
                    <td align='left' width='10%'><table width='100%'>";
                    
                    //$sq="SELECT id, pvno, amount, MONTHNAME(transdate) AS mon, YEAR(transdate) AS yea FROM transtb WHERE year(transdate)='{$iyear}' AND pvno LIKE '%{$pvno}' AND acctcode='{$_POST['account']}'";
				$sq="SELECT id, special_ref2 AS pvno, amount, rmonth AS mon, ryear AS yea FROM recon_remitatb WHERE rmonth='{$imonth}' AND ryear='{$iyear}' AND (special_ref2 LIKE '%{$pvno}' OR payer LIKE '%{$pvno}%') AND paytype='Debit'";
                    $mq=mysqli_query($con, $sq);
                    $row=mysqli_num_rows($mq);
                    $amount_auto=0;
                    while($rq=mysqli_fetch_array($mq, 3)){
                         echo "<tr><td nowrap><input type='checkbox' class='checkboxes' id='pv_jv_{$rq['id']}' name='pv_jv[]' value='{$rq['id']}'><b>{$rq['pvno']}:</b><hr></td><td align='right'>";
				     echo number_format($rq['amount'], 2);
                         echo "<hr></td></tr>";
                         $amount_auto += $rq['amount'];
                         $pvno_array .= ", '".$rq['pvno']."' ";
                    }
                    $diff = $amount - $amount_auto;
                    if($diff<0)  $color = " style='background-color: Tomato;' ";
                    if($diff>0)  $color = " style='background-color: Orange;' ";
				 echo "</table></td>
                     <td><table>";
                    
                    //$sq="SELECT id, pvno, MONTHNAME(transdate) AS mon, YEAR(transdate) AS yea FROM transtb WHERE year(transdate)='{$iyear}' AND pvno LIKE '%{$pvno}' AND acctcode='{$_POST['account']}'";
				$sq="SELECT id, special_ref2 AS pvno, amount, rmonth AS mon, ryear AS yea FROM recon_remitatb WHERE rmonth='{$imonth}' AND ryear='{$iyear}' AND (special_ref2 LIKE '%{$pvno}' OR payer LIKE '%{$pvno}%') AND paytype='Debit'";
                    $mq=mysqli_query($con, $sq);
                    $row=mysqli_num_rows($mq);
                    while($rq=mysqli_fetch_array($mq, 3)){
                         echo "<td nowrap>{$rq['mon']}/{$rq['yea']}<hr></td><td>";
                         echo "<a href=\"javascript:swapcontent('edit_posted_entry','{$rq['id']}','{$r_vals}', 'EDIT');\">EDIT</a><hr>";
    
                         echo "</td></tr>";
                    }
                     echo "</table></td>
                     <td {$color}>".number_format($diff, 2)."</td></tr>";
			} //end of while
               
               //READ TRANSACTIONS NOT IN SANUSI CASHBOOK
               //$sq="SELECT id, pvno, amount, MONTHNAME(transdate) AS mon, YEAR(transdate) AS yea FROM transtb WHERE monthname(transdate)='{$from}' AND year(transdate)='{$to}' AND pvno NOT IN ({$pvno_array}) AND acctcode='{$_POST['account']}' ORDER BY pvno";
			$sq="SELECT id, special_ref2 AS pvno, amount, rmonth AS mon, ryear AS yea FROM recon_remitatb WHERE rmonth='{$from}' AND ryear='{$to}' AND special_ref2  NOT IN ({$pvno_array}) AND paytype='Debit' ORDER BY special_ref2";
               $mq=mysqli_query($con, $sq);
               if(mysqli_num_rows($mq)>0){
                    while($rq=mysqli_fetch_array($mq, 3)){
                         echo "<tr style='background-color: Violet;'>";
                         echo "<td>".++$sn."</td>
                         <td></td>
                         <td></td>
                         <td style='background-color: black;'>&nbsp;</td>
                         <td align='left' width='10%'><table width='100%'><tr><td>";
                         
                         echo "<input type='checkbox' class='checkboxes' id='pv_jv_{$rq['id']}' name='pv_jv[]' value='{$rq['id']}'><b>{$rq['pvno']}:</b><hr></td><td align='right'>";
                         echo number_format($rq['amount'], 2);
                         echo "<hr></td></tr></table></td> <td nowrap>{$rq['mon']}/{$rq['yea']} ";
                         echo "<a href=\"javascript:swapcontent('edit_posted_entry','{$rq['id']}','{$r_vals}', 'EDIT');\" class='myBtn'>EDIT</a>";
                         echo "<hr></td> <td>".number_format($rq['amount'], 2)."</td></tr>";
                    }
               }
			 
			 echo "</tbody></table>";
			 ?>
			 <div id=""><hr>
			MONTH/YEAR: <input type="date" id="month_year" name="month_year" value="">
				<button type="button" class="btn btn-outline-primary btn-fw" name="toledger" id="toledger" onclick="sendRequest('movePosting');">MOVE SELECTION</button>
			 </div>
			 <?php
		}
		else
		   echo "<font color='red'><b>No transaction to process</b></font>";
     }
  ?>
          <!-- end of pending tab-->                <!-- end of main tab -->
				   
	      <div id="display" class="modal" title="COMPARE CASHBOOK ENTRIES" data-options="
                   modal:true,
                   closed:true,
                   iconCls:'icon-save',
			onResize:function(){
				$(this).window('hcenter');
			}" style="width:600px;height:auto;padding:10px; display:none; top:150px;"> 
          </div>
            </p>
            <div id="movePosting"></div>
	  </form>
		</div>           
            </div><!-- end of content box -->

        </div> <!-- end of content tooplate_content-->
    
    </div> <!-- end of content tooplate_main-->
	
    <div class="cleaner"></div>    
</div> <!-- end of wrapper tooplate_wrapper-->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->
<style>
     /* The Modal (background) */
     .modal {
          display: none; /* Hidden by default */
          position: fixed; /* Stay in place */
          z-index: 1; /* Sit on top */
          /*left: 0;*/
          top: 0;
          width: 100%; /* Full width */
          height: 100%; /* Full height */
          overflow: auto; /* Enable scroll if needed */
          /*background-color: rgb(0,0,0); /* Fallback color */
          background-color: whitesmoke; /* rgba(0,0,0,0.4); /* Black w/ opacity */
     }

     /* Modal Content/Box */
     .modal-content {
          background-color: #fefefe;
          margin: 15% auto; /* 15% from the top and centered */
          /*padding: 20px;*/
          border: 1px solid #888;
          width: 40%; /* Could be more or less, depending on screen size */
          float: right;
          top: 0;
     }

     /* The Close Button */
     .close {
          color: #d81919;
          float: right;
          font-size: 28px;
          font-weight: bold;
     }

     .close:hover,
     .close:focus {
          color: black;
          text-decoration: none;
          cursor: pointer;
     }
</style>
<script>
     // Get the modal
     var modal = document.getElementById("display");
     var close = document.getElementById("close");

     // Get the button that opens the modal
     var btn = document.getElementsByClassName("myBtn");

     // Get the <span> element that closes the modal
     var span = document.getElementsByClassName("close");

     // When the user clicks on the button, open the modal
     btn.onclick = function() {
          modal.style.display = "block";
     }

     // When the user clicks on <span> (x), close the modal
     span.onclick = function() {
          modal.style.display = "none";
     }

     // When the user clicks anywhere outside of the modal, close it
     window.onclick = function(event) {
          if (event.target == close) {
               modal.style.display = "none";
          }
     }
</script>
</body>
</html>