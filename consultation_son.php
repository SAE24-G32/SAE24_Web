<!DOCTYPE html>
<html lang="fr">
  <head>
    <title>SAE 24</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="author" content="Groupe32" />
    <meta name="description" content="SAE 24" />
    <meta name="keywords" content="HTML, CSS" />
    <link rel="stylesheet" href="./styles/style.css" />
    <link rel="stylesheet" href="./styles/rwd.css" />
    <link rel="stylesheet" href="./styles/style2.css" />
  </head>
  <body>
    <header>
      <div class="nav">
        <input type="checkbox" id="nav-check" />
        <div class="nav-btn">
          <label for="nav-check">
            <span></span>
            <span></span>
            <span></span>
          </label>
        </div>
        <nav class="nav-links">
          <ul>
            <li><a href="index.html" class="first">Accueil</a></li>
            <li><a href="consultation.php" class="first">Consultation</a></li>
            <li><a href="connexion.php">Connexion</a></li>
            <li><a href="gestion_de_projet.html">Gestion de projet</a></li>
          </ul>
        </nav>
      </div>
    </header>

    <h1>ACCUEIL</h1>

    <h2>Quel est l'objectif de ce site ?</h2>
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
} else {
  echo "Connexion réussie à la base de données.<br>";
}

// Définir la taille du tableau
$rows = 16;
$cols = 16;

// Initialiser le tableau des couleurs
$colors = array_fill(0, $rows, array_fill(0, $cols, ''));

// Colorer les cases spécifiques en bleu
$colors[0][0] = 'blue'; // (1,1)
$colors[0][$cols-1] = 'blue'; // (1,16)
$colors[$rows-1][0] = 'blue'; // (16,1)

// Gérer les données POST pour ajouter une couleur spécifique
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $row = intval($_POST['row']) - 1;
  $col = intval($_POST['col']) - 1;
  $color = $_POST['color'];

  // Vérifier si les valeurs de ligne et de colonne sont valides
  if ($row >= 0 && $row < $rows && $col >= 0 && $col < $cols) {
      $colors[$row][$col] = $color;
  }
}

// Récupérer les données de la table SQL
$sql = "SELECT ValeurX, ValeurY FROM positions_son";
$result = mysqli_query($conn, $sql);

if (!$result) {
  die("Erreur lors de l'exécution de la requête: " . mysqli_error($conn));
} elseif (mysqli_num_rows($result) > 0) {
  echo "Données récupérées:<br>";
  // Colorer les cases en fonction des données récupérées
  while($row = mysqli_fetch_assoc($result)) {
      $x = intval($row["ValeurX"]) - 1;
      $y = intval($row["ValeurY"]) - 1;

      if ($x >= 0 && $x < $rows && $y >= 0 && $y < $cols) {
          $colors[$x][$y] = 'red';
          echo "Coloration de la case ($x, $y) en bleu.<br>";
      }
  }
} else {
  echo "Aucune donnée trouvée.<br>";
}

mysqli_close($conn);
?>

<table>
  <?php
  for ($i = 0; $i < $rows; $i++) {
      echo "<tr>";
      for ($j = 0; $j < $cols; $j++) {
          $style = $colors[$i][$j] ? "style='background-color: {$colors[$i][$j]};'" : "";
          echo "<td $style></td>";
      }
      echo "</tr>";
  }
  ?>
</table>
    

    <footer>
      <ul class="IUT">
        <li>IUT de Blagnac</li>
        <li>Département Réseaux et Télécommunications</li>
        <li><a href="mentions-légales.html">Mentions légales</a></li>
      </ul>
    </footer>
  </body>
</html>
