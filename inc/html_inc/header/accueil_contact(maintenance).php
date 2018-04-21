<?php
	include_once realpath(dirname(__FILE__) . "/../main_php.php");

	// maintenance
	if(!$maintenance) {
		header('Location: /');
		exit();
	}

	include_once realpath(dirname(__FILE__) . "/../main_header.php");
?>

			<div class="w3-bar" id="myNavbar">
				<a class="w3-bar-item w3-button w3-hover-black w3-hide-medium w3-hide-large w3-right" href="javascript:void(0);" onclick="toggleFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
				<a href="#home" class="w3-bar-item w3-button">ACCUEIL</a>
				<a href="#contact" class="w3-bar-item w3-button w3-hide-small"><i class="fa fa-envelope"></i>  CONTACT</a>
			</div>

			<!-- Navbar sur téléphones -->
			<div id="navDemo" class="w3-bar-block w3-white w3-hide w3-hide-large w3-hide-medium">
				<a href="#contact" class="w3-bar-item w3-button" onclick="toggleFunction()">CONTACT</a>
			</div>
		</div>