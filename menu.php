<script>

		$(function() {

			// Clickable Dropdown

			$('.click-nav > ul').toggleClass('no-js js');

			$('.click-nav .js ul').hide();

			$('.click-nav .js').click(function(e) {

				$('.click-nav .js ul').slideToggle(200);

				$('.clicker').toggleClass('active');

				e.stopPropagation();

			});

			$(document).click(function() {

				if ($('.click-nav .js ul').is(':visible')) {

					$('.click-nav .js ul', this).slideUp();

					$('.clicker').removeClass('active');

				}

			});

		});

		</script>

 <?php

   @require_once "function.php";

   $val_str=explode("***",get_company());

   $val_title=explode("***",get_project_title());

 ?>


<ul>
                <li><a href="index.php" class="current">Home</a></li>
                <!--<li><a href="about.html">About Us</a></li>
                <li><a href="services.html">Services</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="template.php">Contact</a></li>
				-->
            </ul>    	