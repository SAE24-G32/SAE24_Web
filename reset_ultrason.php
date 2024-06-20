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

    <?php
    include 'mysql.php';
    // Suppression des lignes où la clé étrangère est la salle sélectionnée
    $query = "DELETE FROM positions_ultrason";
    $result = mysqli_query($id_bd, $query);

    if ($result) {
        echo "<p class=bulle>Les positions ultrason de la salle ont été supprimées avec succès.</p>";
    } else {
        echo "<p class=bulle>Une erreur est survenue lors de la suppression des positions ultrason : " . mysqli_error($id_bd)."</p>";
    }
    ?>

        <footer>
			<p class="bulle"><a href="index.html">Retour à l'accueil</a>
            </br>
			<a href="modification_bdd.php">Choisir une autre action</a></p>
		</footer>
	</body>
</html>