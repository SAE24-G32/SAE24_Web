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
        <label for="salle">Sélectionnez une salle :</label>
        <select name="salle" id="salle">
            <?php
            // Connection to the database
            include 'mysql.php';

            // Retrieving rooms from the positions_son table
            $query = "SELECT DISTINCT IDSalle FROM positions_son";
            $result = mysqli_query($id_bd, $query);

            // Loop to display the select options
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='" . $row['IDSalle'] . "'>" . $row['IDSalle'] . "</option>";
            }
            ?>
        </select>
        <input type="submit" name="supprimer" value="Supprimer">
    </form>

    <?php
    // Checking if the form has been submitted
    if (isset($_POST['supprimer'])) {
        $salle = $_POST['salle'];

        // Deleting rows where the foreign key is the selected room
        $query = "DELETE FROM positions_son WHERE IDSalle = '$salle'";
        $result = mysqli_query($id_bd, $query);

        if ($result) {
            echo "<p class=bulle>Les positions son de la salle $salle ont été supprimées avec succès.</p>";
        } else {
            echo "<p class=bulle>Une erreur est survenue lors de la suppression des positions son : " . mysqli_error($id_bd)."</p>";
        }
    }
    ?>

        <footer>
			<p class="bulle"><a href="index.html">Retour à l'accueil</a>
            </br>
			<a href="modification_bdd.php">Choisir une autre action</a></p>
		</footer>
	</body>
</html>