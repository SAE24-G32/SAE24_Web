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

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" class="form">
        <label for="capteur">Sélectionnez un capteur ultrason:</label>
        <select name="capteur" id="capteur">
            <?php
            // Connexion à la base de données
            include 'mysql.php';

            // Récupération des salles de la table positions_son
            $query = "SELECT DISTINCT IDCapteur FROM capteurs_ultrason";
            $result = mysqli_query($id_bd, $query);

            // Boucle pour afficher les options du select
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['IDCapteur'] . "'>" . $row['IDCapteur'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="supprimer" value="Supprimer">
    </form>

    <?php
    // Vérification si le formulaire a été soumis
    if (isset($_POST['supprimer'])) {
        $capteur = $_POST['capteur'];

        // Suppression des lignes où la clé étrangère est la salle sélectionnée
        $query = "DELETE FROM capteurs_ultrason WHERE IDCapteur = '$capteur'";
        $result = mysqli_query($id_bd, $query);

        if ($result) {
            echo "<p class=bulle>Le capteur ultrason $capteur a été supprimé avec succès.</p>";
        } else {
            echo "<p class=bulle>Une erreur est survenue lors de la suppression du capteur ultrason : " . mysqli_error($id_bd)."</p>";
        }
    }
    ?>

        <footer>
			<p class="bulle"><a href="index.html">Retour à l'accueil</a>
            </br>
			<a href="modification_bdd.php">Choisir une autre action</a>
            </br>
			<a href="supprimer.php">Choisir un autre élément à supprimer</a></p>
		</footer>
	</body>
</html>