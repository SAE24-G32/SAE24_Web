<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SAE 24</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>
    <header>
        <h1>ACCUEIL</h1>
        <nav>
            <ul>
                <li><a href="#" class="first">Accueil</a></li>
                <li><a href="consultation_son.php">Consultation son</a></li>
                <li><a href="consultation_son.php">Consultation ultrason</a></li>
                <li><a href="admin.php">Administration</a></li>
                <li><a href="gestion_de_projet.html">Gestion de projet</a></li>
            </ul>
        </nav>
    </header>

    <h2>Objectif de la SAE 24 :</h2>

    <section class="bulle">
        <p>
            Nous avons créé ce site pour simplifier la consultation des mesures des
            différents capteurs, que ce soit pour les élèves de l'IUT ou pour les
            professeurs. La gestion du site est principalement assurée par
            l'administrateur, ainsi que par les gestionnaires de chaque bâtiment.
        </p>
    </section>

    <?php
    $conn = mysqli_connect("localhost", "root", "", "sae24");
    if (!$conn) {
        die("Connexion échouée: " . mysqli_connect_error());
    }

    // Récupérer les IDs des salles pour le formulaire de sélection
    $sql_salles = "SELECT IDSalle FROM salles";
    $result_salles = mysqli_query($conn, $sql_salles);
    $salles = [];
    if ($result_salles) {
        while ($row = mysqli_fetch_assoc($result_salles)) {
            $salles[] = $row['IDSalle'];
        }
    } else {
        die("Erreur lors de l'exécution de la requête des salles: " . mysqli_error($conn));
    }

    // Définir la taille du tableau
    $rows = 16;
    $cols = 16;

    // Initialiser le tableau des couleurs
    $colors = array_fill(1, $rows, array_fill(1, $cols, ''));

    // Colorer les cases spécifiques en bleu
    $colors[16][1] = 'blue'; // (1, 1)
    $colors[1][1] = 'blue'; // (16, 1)
    $colors[1][16] = 'blue'; // (16, 16)

    // Gérer les données POST pour ajouter une couleur spécifique
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['salle'])) {
            $salle_selectionnee = $_POST['salle'];
            $_SESSION['salle'] = $salle_selectionnee; // Stocker la salle sélectionnée dans la session
        }

        // Traiter les autres données POST pour ajouter une couleur spécifique
        if (isset($_POST['row'], $_POST['col'], $_POST['color'])) {
            $row = intval($_POST['row']);
            $col = intval($_POST['col']);
            $color = $_POST['color'];

            // Vérifier si les valeurs de ligne et de colonne sont valides
            if ($row >= 1 && $row <= $rows && $col >= 1 && $col <= $cols) {
                $colors[$row][$col] = $color;
            }
        }
    }

    // Utiliser la salle stockée dans la session si disponible
    $salle_selectionnee = isset($_SESSION['salle']) ? $_SESSION['salle'] : $salles[0];

    // Récupérer les données de la table SQL pour les positions des sons en fonction de la salle sélectionnée
    $sql_positions = "SELECT ValeurX, ValeurY, DateHeure FROM positions_son WHERE IDSalle = '$salle_selectionnee' ORDER BY DateHeure";
    $result_positions = mysqli_query($conn, $sql_positions);

    if (!$result_positions) {
        die("Erreur lors de l'exécution de la requête: " . mysqli_error($conn));
    } elseif (mysqli_num_rows($result_positions) > 0) {
        echo "Données récupérées pour la salle $salle_selectionnee:<br>";

        // Récupérer toutes les positions dans un tableau
        $positions = mysqli_fetch_all($result_positions, MYSQLI_ASSOC);

        // Parcourir toutes les positions
        for ($i = 0; $i < count($positions); $i++) {
            $x = intval($positions[$i]["ValeurX"]);
            $y = intval($positions[$i]["ValeurY"]);

            if ($x >= 1 && $x <= $rows && $y >= 1 && $y <= $cols) {
                if ($i == count($positions) - 1) {
                    // Dernière position en rouge
                    $colors[$x][$y] = 'red';
                } else {
                    // Positions précédentes en orange
                    $colors[$x][$y] = 'orange';
                }
            }
        }
    } else {
        echo "Aucune donnée trouvée pour la salle $salle_selectionnee.<br>";
    }

    mysqli_close($conn);
    ?>

    <!-- Formulaire de sélection de salle -->
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        <label for="salle">Sélectionnez une salle :</label>
        <select id="salle" name="salle">
            <?php foreach ($salles as $salle): ?>
                <option value="<?php echo $salle; ?>" <?php echo ($salle == $salle_selectionnee) ? 'selected' : ''; ?>>
                    <?php echo $salle; ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Soumettre</button>
    </form>

    <table>
        <?php
        for ($i = 1; $i <= $rows; $i++) {
            echo "<tr>";
            for ($j = 1; $j <= $cols; $j++) {
                $class = "class='tab'";
                $style = $colors[$i][$j] ? "style='background-color: {$colors[$i][$j]};'" : "";
                echo "<td $class $style></td>";
            }
            echo "</tr>";
        }
        ?>
    </table>

    <!-- Bouton Reset -->
    <section>
        <button onclick="location.href='./supprimer.php'">Reset</button>
    </section>

    <footer>
        <ul class="IUT">
            <li>IUT de Blagnac</li>
            <li>Département Réseaux et Télécommunications</li>
            <li><a href="mentions-legales.html">Mentions légales</a></li>
        </ul>
    </footer>
</body>
</html>
