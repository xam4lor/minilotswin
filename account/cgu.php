<?php
	include_once '../inc/html_inc/header/accueil_apropos_jouer_contact.php';
?>

		<!-- 1ère image transition -->
		<div class="bgimg-1 w3-display-container w3-opacity-min" id="home">
			<div class="w3-display-middle" style="white-space:nowrap;">
				<h1><span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">MINI-LOTS</span></h1>
			</div>
		</div>



		<div>
		<!-- Container -> A propos du site -->
			<div class="w3-content w3-container w3-padding-64" id="about">
				<h3 class="w3-center">CONDITIONS GENERALES D'UTILISATION</h3>

				<p>
					Les présentes Conditions Générales d'Utilisation (<i>CGU</i>) régissent les rapports entre :

					<br /><br />L'<b>Utilisateur</b> (<i>tel que défini à l'article 1 ci-après</i>)
					<br />D'une part

					<br /><br />Et
					<br />La <b>société MiniLotsWin</b> (<i>ci-après dénommée "<b>MiniLotsWin</b>"</i>)
					<br />D'autre part




					<br /><br /><br />
					<span style="text-decoration: underline; font-size: 1.225rem;">Parties :</span>
					<nav>
						<ul style="font-size: 1rem;">
							<li class="w3-hover-text-green"><a href="#preambule">Préambule</a></li>
							<li class="w3-hover-text-green"><a href="#article_1">Article 1.Inscription au Service</a></li>
							<li class="w3-hover-text-green"><a href="#article_2">Article 2.Parties quotidiennes</a></li>
							<li class="w3-hover-text-green"><a href="#article_3">Article 3.Parties payantes</a></li>
							<li class="w3-hover-text-green"><a href="#article_4">Article 4.Gains et Lots</a></li>
							<li class="w3-hover-text-green"><a href="#article_5">Article 5.Achat de tickets</a></li>
							<li class="w3-hover-text-green"><a href="#article_6">Article 6.Règles du Morpion</a></li>
							<li class="w3-hover-text-green"><a href="#article_7">Article 7.Règles du Sudoku</a></li>
						</ul>
					</nav>




					<!-- Préambule -->
					<br /><br /><br /><br id="preambule"/><br />
					<span class="cgu-title">Préambule :</span>
					<br /><b>MiniLotsWin</b> propose à l'Utilisateur une plateforme numérique intitulée <b>"MiniLotsWin"</b> (<i>ci-après dénommé <b>"le Service"</b></i>) dédiée au <b>jeu en ligne</b>, et destiné à <b>recevoir des lots</b> en jouant à des mini-jeux.

					<br /><br />Ce Service accessible sur le Site permet notamment à l'Utilisateur de :
					<br />&nbsp;&nbsp;&nbsp;- Jouer à des mini-jeux gratuitement ou en achetant des parties
					<br />&nbsp;&nbsp;&nbsp;- Gagner des lots réels on numériques

					<br /><br />Les fonctionnalités citées ci-dessus sont accessibles à l'Utilisateur sous réserve de son <b>inscription au Service</b>.




					<!-- Article 1 -->
					<br /><br /><br id="article_1"/><br />
					<span class="cgu-title">Article 1.Inscription au Service :</span>
					<br />L'inscription au service est <b>gratuit</b> et <b>sans obligation d'achat</b>.
					
					<br /><br />L'Utilisateur doit renseigner une adresse mail valide afin de pouvoir vérifier son compte par le biais d'un e-mail envoyé par <b>la Société MiniLotsWin</b>.
					
					<br /><br />Lorsque l'Utilisateur s'inscrit sur le Site, il prend en compte et <b>respecte obligatoirement</b> les règles énoncées dans les CGU.
					
					<br /><br />L'Utilisateur ne peut créer qu'<b>un seul compte</b> et n'a pas le droit d'en posséder plusieurs sous peine de <b>sanctions</b>.
					
					<br /><br />Les sanctions sont respectivement :
					<br />&nbsp;&nbsp;&nbsp;- <b>Suppression</b> de compte
					<br />&nbsp;&nbsp;&nbsp;- <b>Bannissement</b> définitif de l'Utilisateur




					<!-- Article 2 -->
					<br /><br /><br id="article_2"/><br />
					<span class="cgu-title">Article 2.Parties quotidiennes :</span>
					<br />L'Utilisateur dispose de <b><?php echo $config->getParametersConfig()['nb_parties_max'] ?> parties quotidiennes gratuites</b>. Elles sont renouvelées toutes les <b>24 heures</b>.
					
					<br /><br />Si l'Utilisateur souhaite recevoir des parties supplémentaires, celui-ci pourra en acheter via le compte Twitter du <b>Service</b>.




					<!-- Article 3 -->
					<br /><br /><br id="article_3"/><br />
					<span class="cgu-title">Article 3.Parties payantes :</span>
					<br />Les parties payantes sont <b>facultatives</b> et permettent d'accéder au <b>Sudoku</b>. 
					<br />Afin de gagner le lot, il faut <b>obligatoirement</b> réussir à compléter le Sudoku avec les bons numéros.

					<br /><br />L'Utilisateur a un <b>nombre d'essai illimité</b> afin de réussir à remplir correctement le Sudoku.




					<!-- Article 4 -->
					<br /><br /><br id="article_4"/><br />
					<span class="cgu-title">Article 4.Gains et Lots :</span>
					<br />La Société <b>MiniLotsWin</b> propose différents lots sur le Site :
					<br />&nbsp;&nbsp;&nbsp;- <b>Numériques</b> :
					<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Clés steam
					<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Comptes divers
					<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Parties supplémentaires

					<br /><br />&nbsp;&nbsp;&nbsp;- <b>Réels</b> :
					<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Casquettes
					<br />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Pulls

					<br /><br />Les lots <b>numériques</b> peuvent être gagnés <b>gratuitement</b> alors que les <b>lots réels</b> peuvent être la plupart du temps gagnés en achetant une <b>partie payante</b>.




					<!-- Article 5 -->
					<br /><br /><br id="article_5"/><br />
					<span class="cgu-title">Article 5.Achat de tickets :</span>
					<br />L'achat de tickets est <b>facultatif</b> et s'effectue grâce à <b>Paypal</b>.

					<br /><br />La démarche est <b>sécurisée</b> et vous permet d'accéder aux parties dites payantes sur le Site.

					<br /><br />Les tickets sont ajoutés à votre compte dans un délai maximum de <b>24 heures</b>, si ce n'est pas le cas, ils vous sont <b>remboursés</b>.




					<!-- Article 6 -->
					<br /><br /><br id="article_6"/><br />
					<span class="cgu-title">Article 6.Règles du Morpion :</span>
					<br /><b>L'Utilisateur</b> va cocher l'une des 9 cases de la grille. Il sera face à une <b>Intelligence Artificielle</b> (<i>IA</i>).

					<br /><br />Le <b>gagnant</b> est le premier à aligner <b>trois symboles identiques</b> horizontalement, verticalement ou en diagonale.




					<!-- Article 7 -->
					<br /><br /><br id="article_7"/><br />
					<span class="cgu-title">Article 7.Règles du Sudoku :</span>
					<br />Les règles du sudoku sont très simples. Un Sudoku classique contient <b>neuf lignes</b> et <b>neuf colonnes</b>, donc 81 cases au total.

					<br /><br />Le but du jeu est de remplir ces cases avec des chiffres allant de 1 à 9 en veillant toujours à ce qu'<b>un même chiffre</b> ne figure qu'une seule fois par colonne, une seule fois <b>par ligne</b>, et une seule fois <b>par carré</b> de neuf cases.

					<br /><br />Au début du jeu, une vingtaine de chiffres est déjà placée et il vous reste à trouver les autres. 

					<br /><br />En effet, une grille initiale de Sudoku correctement constituée ne peut aboutir qu'à une et une seule solution. Pour trouver les <b>chiffres manquants</b>, tout est une question de <b>logique</b> et d'<b>observation</b>.




					<br /><br /><br /><br /><button class="bords-ronds w3-button w3-black w3-left w3-section" onclick="document.location.href='/'"> Retour à l'accueil</button>
				</p>
			</div>
		</div>
		
	
<?php
	include_once '../inc/html_inc/contact.php';
	include_once '../inc/html_inc/footer.php';
?>