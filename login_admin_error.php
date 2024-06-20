<?php
	// Démarrage de la session
	session_start();
?>

<!DOCTYPE html>
<html lang="fr">
 <head>
  <title>SAE 24</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="author" content="Groupe 32" />
  <meta name="description" content="Site web de presentation de la SAE 24" />
  <meta name="keywords" content="son, ultrason, iot, capteur" />
  <link rel="stylesheet" type="text/css" href="styles/style.css" media="screen"/>
 </head>
 <body>
    <header>
      <h1>Administration</h1>
      <nav>
        <ul>
               <li><a href="index.html" class="first">Accueil</a></li>
               <li><a href="consultation_son.php">Consultation son</a></li>
               <li><a href="consultation_son.php">Consultation ultrason</a></li>
               <li><a href="admin.php">Administration</a></li>
               <li><a href="gestion_de_projet.html">Gestion de projet</a></li>
        </ul>
       </nav>
      </nav>
    </header>

		<?php 
			$_SESSION = array(); // Reset of the session array
			session_destroy();   // Session destruction
			unset($_SESSION);    // Array destruction
		?>
		<section class="bulle">
			<p>
				<br />
				<em><strong>Administration de la base : Acc&egrave;s limit&eacute; aux personnes autoris&eacute;es</strong></em>
				<br />
			</p>
			<br />
			<p>Mot de passe non saisi ou erron&eacute; !!!</p>
			<br />
			<hr />
		</section>
		<footer>
			<p class="bulle"><a href="index.html">Retour à l'accueil</a>
            </br>
			<a href="admin.php">Retour à l'identification</a></p>
		</footer>
	</body>
</html>