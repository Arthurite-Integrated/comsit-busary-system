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



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $_SESSION['project_title'];?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<!--
Template 2036 Blue Office
http://www.tooplate.com/view/2036-blue-office
-->
<?php include("required_jQuery_files.php");
include "function.php";?>
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<script>
function sum(){
    //iterate through each textboxes and add keyup
        //handler to trigger sum event
        $(".amt").each(function() {
 
            $(this).keyup(function(){
                calculateSum();
            });
        });
  }
  function calculateSum() {
 
        var sum = 0;
        //iterate through each textboxes and add the values
        $(".amt").each(function() {
 
            //add only if the value is number
            if(!isNaN(this.value) && this.value.length!=0) {
                sum += parseFloat(this.value);
            }
 
        });
        //.toFixed() method will roundoff the final sum to 2 decimal places
        $("#total").html(sum.toFixed(2));
    }
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_b.php";
	var str;
	
if(cv=='login') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='forget_password') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,{contentvar:cv},function(data){
											//alert(data);
		TINY.box.show(data,0,0,0,0);$(divid).html('').show();
		$("#roll").html('').show();
		});
  }//end of putme_login
  
 if(cv=='main_login') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,username:v,password:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='pass_recovery_update') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	 // alert($("frmlogin").serialize());exit();
		//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$.post(url,{contentvar:cv,uname:v,email:a},function(data){
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		$("#roll").html('').show();
		});
  }//end of putme_login
  
  if(cv=='another') //start putme_login
  {
	  //alert(cv+" "+v+" "+a);exit();
	  //alert($("form").serialize());exit();
		$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
		$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
		
		});
  }//end of putme_login 
  if(cv=='get_category'){
	  $.post(url,{contentvar:cv, scalname:v}, function(data){
		 $(divid).html(data).show(); 
		 $("#roll").html('').show();
	  });
  }
  if(cv=='load_allowances'){
	  $.post(url,{contentvar:cv, scalname:v}, function(data){
		 $(divid).html(data).show(); 
		 $("#roll").html('').show();
	  });
  }
   if(cv=='salary_scale_section') //start putme_login
  {
// alert(cv+" "+v+" "+a); exit;
  	 var mydata=JSON.stringify($('#dept_frm').serializeObject()); //get all the serialize object as JSON object and decode it where you need them
			  
			  if(v=='save' && ($('#category').val()=='' || $('#level').val()=='' || $('#step').val()=='') )
			  	{
					alert('Select all necessary data');
					$(divid).html('').show();
					$('#display').html('').show();
					$('#roll').html('').show();
					exit();
				}
			
			//$.post(url,{contentvar:cv,mydata:mydata,action:v,r_id:a},function(data){	//a is the id of rec to edit/upd	
			//alert(data);	
			$.post(url,$("form").serialize()+"&contentvar="+cv+"&action="+v+"&r_id="+a,function(data){
			$(divid).html('').show();	
			$('#display').html(data).show();
			$('#roll').html('').show();
			
			 
			if(v=='save')
			    {
					  document.getElementById('step').value="";
					 // document.getElementById('amount0').value="";
					  document.getElementById('amount1').value="";
					  document.getElementById('amount2').value="";
					  document.getElementById('amount3').value="";
					  document.getElementById('amount4').value="";
				}
			if(v=='refresh')
				  { 
					$(divid).html('').show();
					$('#display').html('').show();
					$('#roll').html('').show();
					  document.getElementById('category').value="";
					  document.getElementById('level').value="";
					   document.getElementById('step').value="";
					 // document.getElementById('amount0').value="";
					  document.getElementById('amount1').value="";
					  document.getElementById('amount2').value="";
					  document.getElementById('amount3').value="";
					  document.getElementById('amount4').value="";
				  }//end of refresh div i.e to refresh the data dispay previously on selection of another department
			if(v=='edit')
				  {
					  //if($('#fileno').val()=='' && a!='auto')
					   //{ alert('Enter Staff File Number');$(divid).html('').show();exit;}
						//alert('here '+ cv + " "+ v + " id:"+a); $(divid).html('').show(); exit;
						//$.post(url,$("#staff_form").serialize()+"&contentvar="+cv+"&action="+v+"&id_val="+a,function(data){
										
						var pData=jQuery.parseJSON(data); 
						alert(pData.s_detail);
						var p=jQuery.parseJSON(pData.s_detail);
						
						$("#sch_code").val(p.sch_code); $("#dept_code").val(p.dept_code); $("#dept_name").val(p.dept_name);
						$("#category").val(p.category); $("#r_id_edit").val(p.r_id); 
						
						//$(divid).html('').show();
					//	});
				  } //for edit purpose
				 });
  }
} //end of swapcontent
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
	        <h2>Salary Scale Setup</h2>
                <p>Use this page to setup salary scale structure.</p>
        </div><!-- end of content_title_box -->
   
        <div id="tooplate_content">
	        
        	<div class="content_box">
                                
<div class="content">
				<!--<div class="title"><h2>Recent Updates</h2></div>
                <h3><i>Salary Scale Setup</i></h3> -->
			<p>
			<form action="#" method="post" enctype="multipart/form-data" name="dept_frm" id="dept_frm">
				<table border="0" align="left" cellpadding="3" cellspacing="0">
				<tr>
				<th align="left" width='40%'>Salary Scale Name:</th><td align="left">
				<select name="scalename" id="scalename" tabindex="1" style="width:150px" onChange="swapcontent('get_category', $('#scalename').val()); swapcontent('load_allowances', $('#scalename').val());">
				<option selected="selected" value="">---</option>
                <?php
					$r=@mysqli_query($con, "select distinct scale_name from scale_nametb where status='Active'");
					while ($rec=@mysqli_fetch_array($r))
						{
							$sname = @$rec[0];
							echo "<option value='$sname'>$sname</option>";
							
						}
					
					?>
				</select> <a href="salary_scale_setup.php?r_val=<?php echo $r_val; ?>" title="Open scale name setup page!">Edit Scale Name</a>
				</td>
                </tr>
				<tr>
				<th align="left">Staff Category:</th>
                <td align="left"><div id="get_category"><input id="category" name="category" value="" type="hidden" /></div>
				<!--<select name="category" id="category" tabindex="2" style="width:150px">
				<option selected="selected" value="">--</option>
				<option value="Academic">Academic</option>
				<option value="Non-Academic">Non-Academic</option>
				</select>-->
				</td>
                </tr>
				<tr>
				<th align="left">Grade Level:</th>
				<td align="left"><select name="level" id="level" tabindex="3" style="width:150px">
					<option selected="selected" value="">---</option>
					<?php
					$r=@mysqli_query($con, "select distinct level from level_categorytb order by convert(level,decimal)");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['level'];$pcode=@$rcourse['level'];
							echo "<option value='$pcode'>$scourse</option>";
						}
					?>
					</select>
				</td>
                </tr>
				<!--</table>
				<table>-->
				<tr>
				<th align="left">Step: 
				</th>
				<th align="left"><select name="step" id="step" tabindex="4" onchange="swapcontent('salary_scale_section','view_all');" style="width:150px">
				  <option selected="selected" value="">---</option>
				  <?php
					$r=@mysqli_query($con, "select distinct step from steptb order by convert(step, decimal)");
					while ($rcourse=@mysqli_fetch_array($r))
						{
							$scourse=@$rcourse['step'];$pcode=@$rcourse['step'];
							echo "<option value='$pcode'>$scourse</option>";
						}
					
					?>
				  </select></th>
				</tr>
				<tr><th align="left" width="40%">Particulars</th>
				  <th align="left">Amount (<?php echo "&#8358;"; ?>)</th>
			    </tr>
				</table>
				<?php
			 /*$r=@mysqli_query($con, "select distinct * from foliotb where folio_code in ('01-000-1013', '01-101-2012', '01-101-2013', '01-101-2008', '01-101-2022') order by folio_code");
			 $n=0;$av="";$tab_index=4;
			 while($rl=@mysqli_fetch_array($r))
					{
						++$n;$tab_index++;
						$acctcode=@$rl['folio_code'];$bankname=@$rl['title'];
						$v=$acctcode;
						echo "<tr><th nowrap align='left'>
						<input name='code[]' id='code$n' class='code_checked' type='checkbox' value='$v' checked='checked' />
						<!--</th><th>-->$bankname</th>
						<th align='left'><input type='text' name='amount[]' class='amt' id='amount$n' value='' size='20' style='background-color: #FEFFB0;font-weight: bold;text-align: right;' tabindex='$tab_index' onkeydown='sum()' ></th></tr>";
					}// end of while*/
					
					?>
				<div id="load_allowances"></div>
                <table border="0" align="left" cellpadding="3" cellspacing="0" width="100%">
				<tr style="font-size: 18px;font-weight: bold;color:#174C68;">
                <th align="center" width="40%">Total</th>
				  <td align="left">
  <div id="total" align="right" style="width:100px" ><b>0.00</b></div></td>
				  </tr>
                <tr>
                <td colspan="2" align="center">
				<input type="button" class="btn" name="sbtn" id="sbtn" value="Save" onclick="swapcontent('salary_scale_section', 'save');" /> | 
				<!--<input type="button" class="btn" name="chbtn" id="chsbtn" value="Search" onclick="swapcontent('salary_scale_section','search');" />
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="View All" onclick="swapcontent('salary_scale_section','view_all');" /> -->
				<input type="button" class="btn" name="chbtn" id="chsbtn" value="Refresh" onclick="swapcontent('salary_scale_section', 'refresh');" />
				
				
				</td>
  				</tr>
                <!--<tr style="font-size: 18px;font-weight: bold;color:#174C68;" height="35">
                <td align="left"></td>
				  </tr>-->
                <tr>
                	<td align="left" colspan="2">
                    <!--<hr><br>-->
                    <div id="display"></div><br>
                    <hr>
				<div id="roll"></div></td>
                </tr>
</table>
				
				</form>
            </p>
		</div>           
            </div><!-- end of content box -->

        </div> <!-- end of content tooplate_content-->
    
    </div> <!-- end of content tooplate_main-->
	
    <div class="cleaner"></div>    
</div> <!-- end of wrapper tooplate_wrapper-->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div><!-- end of footer  tooplate_footer_wrapper-->

</body>
</html>