<?php
	include_once 'main_php.php';
?>


<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />

		<meta name="description" content="Gagnez des lots gratuitement en jouant à des mini-jeux !" />
		<meta name="keywords" content="jeux lots big-lots gratuit cadeau gagner mini-jeu free gift game fun" />
		<meta name="Resource-type" content="Document">

		<link rel="icon" type="image/png" href="../imgs/favicon.ico" />
		<link rel="stylesheet" type="text/css" href="/css/main-css.css" />
		<link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css" />
		<link rel="stylesheet" type="text/css" href="/css/w3.css" />
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.1/jquery-ui.min.js"></script>


		<script>
			// Change style of navbar on scroll
			window.onscroll = function() {
				myFunction()
			};

			function myFunction() {
				var navbar = document.getElementById("myNavbar");
				if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
					navbar.className = "w3-bar" + " w3-card-2" + " w3-animate-top" + " w3-white";
				}
				else {
					navbar.className = navbar.className.replace(" w3-card-2 w3-animate-top w3-white", "");
				}
			}
				
			// Used to toggle the menu on small screens when clicking on the menu button
			function toggleFunction() {
				var x = document.getElementById("navDemo");
				
				if (x.className.indexOf("w3-show") == -1) {
					x.className += " w3-show";
				}
				else {
					x.className = x.className.replace(" w3-show", "");
				}
			}

			// Twitter
			!function(d, s, id) {
				var js, fjs = d.getElementsByTagName(s)[0],
					p = /^http:/.test(d.location) ? 'http' : 'https';
				if (!d.getElementById(id)) {
					js = d.createElement(s);
					js.id = id;
					js.src = p + '://platform.twitter.com/widgets.js';
					fjs.parentNode.insertBefore(js, fjs);
				}
			} (document, 'script', 'twitter-wjs');


			function getCookie(cname) {
				var name = cname + "=";
				var decodedCookie = decodeURIComponent(document.cookie);
				var ca = decodedCookie.split(';');

				for(var i = 0; i <ca.length; i++) {
					var c = ca[i];

					while (c.charAt(0) == ' ') {
						c = c.substring(1);
					}

					if (c.indexOf(name) == 0) {
						return c.substring(name.length, c.length);
					}
				}
				return "";
			}


			function quitCookie() {
				var d = new Date();
				d.setTime(d.getTime() + 24*60*60*1000);
				var expires = "expires=" + d.toUTCString();
				document.cookie = "displayPopup=false" + ";" + expires + ";path=/;";
				document.getElementById('element_to_display').style = 'display: none';
			}

			function checkIfDisplayCookie() {
				if(getCookie('displayPopup') == "false") {
					document.getElementById('element_to_display').style = 'display: none';
				}
				else {
					document.getElementById('element_to_display').style = 'display: block';
				}
			}

			window.onload = checkIfDisplayCookie;
		</script>

		<meta name="google-site-verification" content="3_wThmvOf0UDnhNUzTwuOgp3VZaYYNAxJqJSr728I30" />
		<meta name="google-site-verification" content="7r2mSjfDaMXl1TB1sesMkq-zIBNHLjRdWJVnDQ825x8" />
	</head>

	<body>
		<?php include_once 'analytics_tracking.php' ?>

		<div class="w3-top">
			<div class="popup_main w3-animate-top" id="element_to_display" style="display: none;">
				<div class="popup_container">
					<span class="popup_content">
						<?php echo $config->getPopupConfig()['cookie_text'] ?>
					</span>
					<span id="popup_button_quit" class="popup_button_quit" onclick="quitCookie()">
						<a href="#">OK</a>
					</span>
				</div>
			</div>

			<!-- Navbar -->

