 <div id="contact_form">
            
				<h4>Quick Contact Form</h4>
					<form method="post" name="contact" action="#">
					
						<label for="author">Name:</label> <input type="text" id="author" name="author" class="required input_field" />
						<div class="cleaner h10"></div>
													

						<label for="email">Email:</label> <input type="text" maxlength="40" id="email" class="input_field" name="email" />
						<div class="cleaner h10"></div>
											
						<label for="subject">Subject:</label> <input type="text" class="validate-subject required input_field" name="subject" id="subject"/>				               
						<div class="cleaner h10"></div>
							
						<label for="text">Message:</label> <textarea id="text" name="text" rows="0" cols="0" class="required"></textarea>
						<div class="cleaner h10"></div>				
												
						<input type="submit" value="Send" id="submit" name="submit" class="submit_btn float_l" />
						<input type="reset" value="Reset" id="reset" name="reset" class="submit_btn float_r" />
						
					</form>
			</div>