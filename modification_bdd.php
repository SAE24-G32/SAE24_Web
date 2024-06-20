<?php 
	session_start(); 
	if ($_SESSION["auth"]!=TRUE)
		header("Location:login_admin_error.php");
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

    <p class="bulle">
        <br />
	    <em><strong>Quelle action voulez-vous effectuer ?</strong></em>
	    <br />
    </p>

    <section>
        <button onclick="location.href='./ajouter.php'">Ajouter un élément </button>
        <button onclick="location.href='./supprimer.php'">Supprimer un élément</button>
        <button onclick="location.href='./reset_son.php'">Réinitialiser les positions pour la partie son</button>
        <button onclick="location.href='./reset_ultrason.php'">Réinitialiser les positions pour la partie ultrason</button>
    </section>

    <footer>
			<p class="bulle"><a href="index.html">Retour à l'accueil</a>
            </br>
			<a href="admin.php">Déconnexion</a></p>
		</footer>
	</body>
</html>