
		<div id="site_title"><a href="#" tabindex="43">Uni<span>lorin</span></a></div>
        
        <div id="home_service">
        	<div class="home_service_box"><span class="service1"></span><h5><a href="#" tabindex="44">Statistics</a></h5>
        	Automation
        	</div>
            <div class="home_service_box"><span class="service2"></span><h5><a href="#" tabindex="45">Help Desk</a></h5>
            Automation help desk</div>
            <div class="home_service_box"><span class="service3"></span><h5><a href="#" tabindex="46">FAQ</a></h5>Frequently asked questions.</div>
        </div>
        
        <div class="sidebar_box">
        	<h4>User Login</h4>	
			
			<div id="newsletter_box">
			<form action="#" method="post">			
			Please enter your Login ID and Password.<br />
			<input type="text" id="username" name="username" class="newsletter_email" placeholder="Login ID" autofocus="autofocus" />
			
			<input type="password" id="password" name="password" class="newsletter_email" placeholder="Password" onkeydown="if (event.keyCode == 13) swapcontent('main_login',$('#username').val(),$('#password').val());" />				           
            <input type="button" name="submit_subscribe" id="submit_subscribe" value="Login" onclick="swapcontent('main_login',$('#username').val(),$('#password').val());" />
            </form>
			</div>		
			<div id="roll"></div><div id="main_login"></div>
            <div class="cleaner"></div>
        </div>
	