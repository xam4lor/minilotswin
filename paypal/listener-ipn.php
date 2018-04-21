<?php
	include_once '../inc/html_inc/main_php.php';
	include_once 'PaypalIPN.php';

	// vérification de l'IPN
	$ipn = new PaypalIPN();

	if($params->isPaypalUsingSandbox()) {
		$ipn->useSandbox();
	}

	$verified = $ipn->verifyIPN();



	// IPN non-vérifiée
	if (!$verified) {
		?>
			<p>Erreur dans la requête qui n'a pas pu être vérifiée.</p> 
		<?php
		exit();
	}

	// IPN vérifiée
	?>
		<p>L'IPN a bien été vérifiée.</p> 
	<?php





	// ------ RECUPERATION DES DONNEES ------
	// --- informations sur le receveur de l'achat ---
	$receveur_infos = 								
		strip_tags($_POST['receiver_email']) 					// email
		. "§§" . strip_tags($_POST['receiver_id'])			// id
		. "§§" . strip_tags($_POST['residence_country']);	// pays de résidence


	// --- informations sur la transaction ---
	$txn_id = strip_tags($_POST['txn_id']);					// id
	$txn_type = strip_tags($_POST['txn_type']);				// type


	// --- informations sur l'acheteur ---
	$payer_email = strip_tags($_POST['payer_email']);		// email
	$payer_id = strip_tags($_POST['payer_id']);				// id

	$payer_names = 
		strip_tags($_POST['first_name'])					// prénom
		. "§§" . strip_tags($_POST['last_name']);			// nom de famille


	// --- informations sur le paiement ---
	$paiement_currency = strip_tags($_POST['mc_currency']); // type de monnaie utilisée
	$paiement_fee = strip_tags($_POST['mc_fee']);			// montant de la taxe
	$paiement_gross = strip_tags($_POST['mc_gross']);		// montant brut du paiement (sans frais)
	$paiement_quantity = strip_tags($_POST['quantity']);	// quantité
	$paiement_date = strip_tags($_POST['payment_date']);	// date du paiement
	$payment_status = strip_tags($_POST['payment_status']); // status du paiement -> Completed si complété

	// --- variable custom ---
	$custom_var_raw = strip_tags($_POST['custom']);
	$custom_var = explode(":::", $custom_var_raw);





	// TEST SI LE LA TRANSACTION A ETE EFFECTUEE
	if($payment_status != "Completed") {
		?>
			<p>La transaction n'a pas été effectuée : pas d'inscription dans la base de données.</p> 
		<?php
		exit();
	}






	// AJOUT EVENTUEL D'UN TICKET
	// $custom_var = payement_id=_ID_:::user_id=_ID_
	$payement_id = explode("=", $custom_var[0]);
	$user_id = explode("=", $custom_var[1]);
	$lot_added = 0;

	// ---- ticket de sudoku ----
	if(
		strcmp($payement_id[0], "payement_id") == 0
		&& strcmp($payement_id[1], "1") == 0
		&& strcmp($user_id[0], "user_id") == 0
		&& floatval($paiement_gross) == $params->getSudokuTicketPrice()
	)
	{
		$req = $bdd->prepare("SELECT parties_sudoku_left FROM account WHERE id=:id");
		$req->execute(array('id' => $user_id[1]));
		$parties_sudoku_left = 0;

		while ($donnees = $req->fetch()) {
			$parties_sudoku_left = intval($donnees['parties_sudoku_left']);
		}

		$req2 = $bdd->prepare("UPDATE account SET parties_sudoku_left=:s_left WHERE id=:id");
		$req2->execute(array('s_left' => ($parties_sudoku_left + 1), 'id' =>$user_id[1]));

		$lot_added = 1;
	}


	// ---- ticket de morpion payant ----
	else if(
		strcmp($payement_id[0], "payement_id") == 0
		&& strcmp($payement_id[1], "2") == 0
		&& strcmp($user_id[0], "user_id") == 0
		&& floatval($paiement_gross) == $params->getMorpionTicketPrice()
	)
	{
		$req = $bdd->prepare("SELECT parties_morpion_pay_left FROM account WHERE id=:id");
		$req->execute(array('id' => $user_id[1]));
		$parties_morpion_pay_left = 0;

		while ($donnees = $req->fetch()) {
			$parties_morpion_pay_left = intval($donnees['parties_morpion_pay_left']);
		}

		$req2 = $bdd->prepare("UPDATE account SET parties_morpion_pay_left=:s_left WHERE id=:id");
		$req2->execute(array('s_left' => ($parties_morpion_pay_left + 1), 'id' =>$user_id[1]));

		$lot_added = 1;
	}


	?>
		<p>La vérification de l'achat d'un ticket et l'ajout de ce ticket si nécessaire a bien été effectué.</p> 
	<?php





	// INSCRIPTION DANS LA BDD
	$req3 = $bdd->prepare("INSERT INTO payments(receveur_infos, txn_id, txn_type, paiement_gross, paiement_currency, paiement_fee, paiement_quantity, paiement_date, payer_id, payer_email, payer_names, custom, lot_added) VALUES (:receveur_infos, :txn_id, :txn_type, :paiement_gross, :paiement_currency, :paiement_fee, :paiement_quantity, :paiement_date, :payer_id, :payer_email, :payer_names, :custom, :lot_added)");
	$req3->execute(array('receveur_infos' => $receveur_infos, 'txn_id' => $txn_id, 'txn_type' => $txn_type, 'paiement_gross' => $paiement_gross, 'paiement_currency' => $paiement_currency, 'paiement_fee' => $paiement_fee, 'paiement_quantity' => $paiement_quantity, 'paiement_date' => $paiement_date, 'payer_id' => $payer_id, 'payer_email' => $payer_email, 'payer_names' => $payer_names, 'custom' => $custom_var_raw, 'lot_added' => $lot_added));
	
	?>
		<p>La transaction a bien été inscrite dans la base de données.</p> 
	<?php





	// Renvoi d'une réponse HTTP pour confirmer le reçu
	header("HTTP/1.1 200 OK");