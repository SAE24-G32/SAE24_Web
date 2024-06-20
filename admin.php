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
               <li><a href="#">Administration</a></li>
               <li><a href="gestion_de_projet.html">Gestion de projet</a></li>
        </ul>
       </nav>
      </nav>
    </header>

    <p class="bulle">
        <br />
	    Administration de la base : Acc&egrave;s limit&eacute; aux personnes autoris&eacute;es
	    <br />
    </p>

    <section>
		<form action="login_admin.php" method="post" enctype="multipart/form-data" class="form">
			<fieldset>
				<legend>Saisissez le mot de passe administrateur</legend>
				<label for="mdp">Mot de passe : </label>
				<input type="password" name="mdp" id="mdp"/>
			</fieldset>
			<p>
				<input type="submit" value="Valider" />
			</p>
		</form>

    </section>