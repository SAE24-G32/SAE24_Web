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
    // Access to the database
    include 'mysql.php';

    // Query to retrieve all data from the “salles” table
    $sql = "SELECT * FROM salles";
    $result = mysqli_query($id_bd, $sql);

    // Check if there are results
    if (mysqli_num_rows($result) > 0) {
        // Beginning of the table
        echo "<table class=table2>";
        echo "<tr><th class=th2>Nom de la Salle</th><th class=th2>Nom du Batiment</th><th class=th2>Capacite</th></tr>";

        // Loop to display each row of data
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td class=td2>" . $row["IDSalle"] . "</td>";
            echo "<td class=td2>" . $row["NomBatiment"] . "</td>"; 
            echo "<td class=td2>" . $row["Capacite"] . "</td>";
            echo "</tr>";
        }

        echo "</table>"; // End of the table
    } else {
        echo "Table salles vide.";
    }

    // close the connection
    mysqli_close($id_bd);
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="form">
        <fieldset>
            <legend>Information sur la salle</legend>
            <label for="IDSalle">Nom de la salle:</label>
            <input type="text" name="IDSalle" id ="IDSalle" required/>
            <label for="Capacite">Capacite :</label>
            <input type="number" name="Capacite" id ="Capacite" />
            <label for="NomBatiment">Batiment :</label>
            <input type="text" name="NomBatiment" id ="NomBatiment" required/>
        </fieldset>
        <p>
            <input type="submit" name="ajouter" value="Validez" />
        </p>
    </form>


    <?php
        if (isset($_POST['ajouter'])) {
            $salle = $_POST['IDSalle'];
            $capacite = $_POST['Capacite'];
            $batiment = $_POST['NomBatiment'];
            /* access to the database */
            include ("mysql.php");
            $requete_verif = "SELECT * FROM `salles` WHERE `IDSalle`='$salle'";
            $resultat_verif = mysqli_query($id_bd, $requete_verif);
            $salle_existe = mysqli_num_rows($resultat_verif);

            if ($salle_existe > 0) {
                echo '<p class=bulle>';
                echo "<br /><strong>Erreur : La salle existe déjà dans la base de donnees.</strong><br />";
                echo '</p>';
            }
            else {
                $requete = "INSERT INTO `salles` (`IDSalle`, `Capacite`, `NomBatiment`)
                VALUES('$salle','$capacite','$batiment')";
                $resultat = mysqli_query($id_bd, $requete)
                    or die("Execution de la requete impossible : $requete");
                mysqli_close($id_bd);

                echo "<ul class=bulle>
                        <li> Nom de la salle ajoutée : $salle</li>
                        <li> Capacite de la salle ajoutée : $capacite</li>
                        <li> Batiment : $batiment</li>
                        </ul>";
            }
        }
    ?>

        <footer>
			<p class="bulle"><a href="index.html">Retour à l'accueil</a>
            </br>
			<a href="modification_bdd.php">Choisir une autre action</a>
            </br>
			<a href="ajouter.php">Choisir un autre élément à ajouter</a></p>
		</footer>
	</body>
</html>