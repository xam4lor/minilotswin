<hr />
<?php
	if(!$connected) {
		?>
	<a href="/account/connexion.php#about" class="w3-bar-item w3-button" onclick="toggleFunction()">CONNEXION</a>
	<a href="/account/inscription.php#about" class="w3-bar-item w3-button" onclick="toggleFunction()">INSCRIPTION</a>
		<?php
	}
	else {
		if($session->getUserSession()['admin'] == 1) {
			?>
		<a href="/admin/#about" class="w3-bar-item w3-button" onclick="toggleFunction()">INTERFACE ADMINISTRATEUR</a>
		<hr />
			<?php
		}
		?>
	<a href="/account/#about" class="w3-bar-item w3-button" onclick="toggleFunction()">MON COMPTE</a>
	<a href="/account/deconnexion.php#about" class="w3-bar-item w3-button" onclick="toggleFunction()">DECONNEXION</a>
		<?php
	}
?>