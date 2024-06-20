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
    // Connexion à la base de données
    include 'mysql.php';

    // Requête pour récupérer toutes les données de la table "salles"
    $sql = "SELECT * FROM capteurs_ultrason";
    $result = mysqli_query($id_bd, $sql);

    // Vérifier s'il y a des résultats
    if (mysqli_num_rows($result) > 0) {
        // Début du tableau HTML
        echo "<table class=table2>";
        echo "<tr><th class=th2>Identifiant du Capteur</th></tr>";

        // Boucle pour afficher chaque ligne de données
        while($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td class=td2>" . $row["IDCapteur"] . "</td>";
            echo "</tr>";
        }

        echo "</table>"; // Fin du tableau
    } else {
        echo "<p class=bulle>Table capteurs_ultrason vide.</p>";
    }

    // Fermer la connexion
    mysqli_close($id_bd);
    ?>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data" class="form">
        <fieldset>
            <legend>Information sur le capteur</legend>
            <label for="IDCapteur">Identifiant du capteur:</label>
            <input type="text" name="IDCapteur" id ="IDCapteur" required/>
            <label for="IDZoneG">Sélectionnez la zone à gauche du capteur :</label>
            <select name="IDZoneG" id="IDZoneG">
                <?php
                // Connexion à la base de données
                include 'mysql.php';

                // Récupération des salles de la table zones_ultrason
                $query = "SELECT DISTINCT IDZone_ultrason FROM zones_ultrason";
                $result = mysqli_query($id_bd, $query);

                // Boucle pour afficher les options du select
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['IDZone_ultrason'] . "'>" . $row['IDZone_ultrason'] . "</option>";
                }
                ?>
            </select>
            <label for="IDZoneD">Sélectionnez la zone à droite du capteur :</label>
            <select name="IDZoneD" id="IDZoneD">
                <?php
                // Connexion à la base de données
                include 'mysql.php';

                // Récupération des salles de la table zones_ultrason
                $query = "SELECT DISTINCT IDZone_ultrason FROM zones_ultrason";
                $result = mysqli_query($id_bd, $query);

                // Boucle pour afficher les options du select
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<option value='" . $row['IDZone_ultrason'] . "'>" . $row['IDZone_ultrason'] . "</option>";
                }
                ?>
            </select>
        </fieldset>
        <p>
            <input type="submit" name="ajouter" value="Validez" />
        </p>
    </form>


    <?php
        if (isset($_POST['ajouter'])) {
            $capteur = $_POST['IDCapteur'];
            $zoneg = $_POST['IDZoneG'];
            $zoned = $_POST['IDZoneD'];
            /* access to the database */
            include ("mysql.php");
            $requete_verif = "SELECT * FROM `capteurs_ultrason` WHERE `IDCapteur`='$capteur'";
            $resultat_verif = mysqli_query($id_bd, $requete_verif);
            $salle_existe = mysqli_num_rows($resultat_verif);

            if ($salle_existe > 0) {
                echo '<p class=bulle>';
                echo "<br /><strong>Erreur : Le capteur ultrason existe déjà dans la base de donnees.</strong><br />";
                echo '</p>';
            }

            elseif ($zoneg == $zoned) {
                echo '<p class=bulle>';
                echo "<br /><strong>Erreur : Les deux zones ne peuvent pas être identiques !</strong><br />";
                echo '</p>';
            }
            else {
                $requete = "INSERT INTO `capteurs_ultrason` (`IDCapteur`, `IDZoneG`, `IDZoneD`)
                VALUES('$capteur', '$zoneg', '$zoned')";
                $resultat = mysqli_query($id_bd, $requete)
                    or die("Execution de la requete impossible : $requete");
                mysqli_close($id_bd);

                echo "<ul class=bulle>
                        <li> Nom du capteur ultrason ajouté : $capteur</li>
                        <li> Zone gauche du capteur ultrason ajouté : $zoneg</li>
                        <li> Zone droite du capteur ultrason ajouté : $zoned</li>
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