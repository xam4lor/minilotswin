<?php
	$connected = $session->isUserSession();

	if(!$connected) {
		?>
	<a href="/account/inscription.php#about" class="w3-right w3-bar-item w3-button w3-hide-small">  INSCRIPTION</a>
	<a href="/account/connexion.php#about" class="w3-right w3-bar-item w3-button w3-hide-small">  CONNEXION</a>
		<?php
	}
	else {
		?>
	<a href="/account/deconnexion.php#about" class="w3-right w3-bar-item w3-button w3-hide-small">  DECONNEXION</a>
	<a href="/account/#about" class="w3-right w3-bar-item w3-button w3-hide-small">  MON COMPTE</a>
		<?php
	}

	if($connected && $session->getUserSession()['admin'] == 1) {
		?>
	<a href="/admin/#about" class="w3-right w3-bar-item w3-button w3-hide-small">  INTERFACE ADMINISTRATEUR</a>
		<?php
	}
?>