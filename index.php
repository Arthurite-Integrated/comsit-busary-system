<?php 
@session_start();
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Home</title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<link rel="shortcut icon" href="images/logox.png"> <!-- put the image/logo on the browser tab -->
<link href="tooplate_style.css" rel="stylesheet" type="text/css" />
<?php include("required_jQuery_files.php"); ?>
<script>
function swapcontent(cv,v,a,b,c,d,e,f,g,h,i,j,k,l)
{   //swap content begins where cv means div id name
	var divid="#"+cv;//"#lga", {contentvar:cv,state:v}
	$(divid).html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	$("#roll").html('<img src="images/loader.gif" width="100" height="100" alt="loading">').show();
	var url="scriptfile_a.php";
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
	
	if(cv=='app_login') //start putme_login
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
		// alert($("form").serialize());exit();
			$.post(url,$("form").serialize()+"&contentvar="+cv,function(data){  
				//ajaxfile/scriptfile_a is called undernith
			//$.post(url,{contentvar:cv,username:v,password:a},function(data){
			$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
			$("#roll").html('').show();
			});
	}//end of putme_login
	
	if(cv=='app_main_login') //start putme_login
	{
		//alert(cv+" "+v+" "+a);exit();
		// alert($("frmlogin").serialize());exit();
			//$.post(url,$("frmlogin").serialize()+"&contentvar="+cv,function(data){  //ajaxfile/scriptfile_a is called undernith
			$.post(url,{contentvar:cv,username:v,password:a},function(data){
			$(divid).html(data).show(); //report result from the ajaxfile, the data stores the information to be displayed from ajaxfile
			$("#roll").html('').show();
			});
	}//end of app_main_login
	
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
  
} //end of swapcontent
 </script>
</head>
<body class="home">

<div id="tooplate_wrapper">

	<div id="tooplate_sidebar">
		<?php include_once("sidebar.php"); ?>
	</div> <!-- end of sidebar -->
    
    <div id="tooplate_main">
    	
        <div id="tooplate_menu">
             <?php include_once("menu.php"); ?>	
        </div> <!-- end of tooplate_menu -->
        
        <div id="featured_project">
       	  <h1>University of Ilorin</h1>
        	<img src="images/featured_project.jpg" alt="Feature 01" />
       	  <h3>Bursary Automation</h3>
            <p style="text-align:justify;">The Office of the Bursar is responsible for all financial records of the University.</p>
            <a href="#" tabindex="40">Read more</a>
        </div> <!-- end of featured project -->
        
        <div id="tooplate_content">
        	
            		<div class="service_box">
                <img src="images/onebit_15.png" alt="image" />
                <div class="right">
                  <h4>Bursary Automation System.</h4>
                    <p style="text-align:justify;">Bursar's responsibility involves sending bills and making payment plans; the   ultimate goal is to bring all student accounts to a "paid off" status.   Bursars are not necessarily involved in the financial aid process. Bursars' duties vary from one institution to another. At many   institutions, bursars deal only with student finances. At other   institutions, bursars also deal with some faculty finance issues.   Elsewhere, they also oversee accounts receivable, or the payments that   the university receives from outside organizations for which it performs   services...</p>
</div>
                <div class="cleaner"></div>
            </div>	
            <!--
            <div class="service_box">
				<img src="images/onebit_16.png" alt="image" />
                <div class="right">
                	<h4>Email Marketing Service</h4>
                    <p>Donec dictum magna ut dolor aliquam vel tempor libero commodo. Integer ornare elit et odio faucibus mattis rhoncus ipsum malesuada. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse facilisis hendrerit turpis, elementum lacinia turpis pharetra porta.</p>
                    <ol>
                        <li>Fusce fringilla, dui sed blandit luctus, arcu augue.</li>
                        <li>Sed id magna scelerisque augue consequat sagittis.</li>
                        <li>Morbi scelerisque dui in turpis euismod condimentum.</li>
                     </ol>
                </div>
                <div class="cleaner"></div> 
            </div>
            
            <div class="service_box sb_last">
                <img src="images/onebit_18.png" alt="image" />
                <div class="right">
                    <h4>Link Exchange Service</h4>
                    <p>Nam porta ante aliquet nisi eleifend sit amet malesuada sapien aliquet. Mauris consequat laoreet bibendum. Ut sit amet magna odio, eget scelerisque ligula. Curabitur aliquam lacinia fermentum. Integer eu augue sem, sit amet aliquam metus.</p>
                    <ol>
                        <li>Praesent ut ipsum in enim malesuada vestibulum ac in tortor.</li>
                        <li>Nulla a enim vel purus rhoncus tempor mollis ut magna.</li>
                        <li>Vestibulum sit amet libero quis diam pellentesque tincidunt vel vel erat.</li>
                    </ol>
                </div>
				
                <div class="cleaner"></div> 
            </div>
			-->
        </div> <!-- end of content -->
    
    </div> <!-- end of content -->
	
    <div class="cleaner"></div>    
</div> <!-- end of wrapper -->

<div id="tooplate_footer_wrapper">
	<?php include_once("footer.php"); ?>
</div>

</body>
</html>