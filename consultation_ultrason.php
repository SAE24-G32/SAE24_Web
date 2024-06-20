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
      <h1>Consultation ultrason</h1>
      <nav>
          <ul>
              <li><a href="index.html" class="first">Accueil</a></li>
              <li><a href="consultation_son.php">Consultation son</a></li>
              <li><a href="#">Consultation ultrason</a></li>
              <li><a href="admin.php">Administration</a></li>
              <li><a href="gestion_de_projet.html">Gestion de projet</a></li>
          </ul>
      </nav>
  </header>

  <h2>Tableau de consultation des positions</h2>

  <?php
  // Connexion à la base de données
  $conn = mysqli_connect("localhost", "sae24", "passroot", "sae24");
  if (!$conn) {
      die("Connexion échouée: " . mysqli_connect_error());
  }

  // Récupérer les IDSalle disponibles
  $sql_salles = "SELECT DISTINCT IDSalle FROM zones_ultrason";
  $result_salles = mysqli_query($conn, $sql_salles);
  $salles = [];
  while ($row = mysqli_fetch_assoc($result_salles)) {
      $salles[] = $row['IDSalle'];
  }

  // Gestion des données POST
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $salle_selectionnee = $_POST['salle'];
      $_SESSION['salle'] = $salle_selectionnee;
  } else {
      $salle_selectionnee = isset($_SESSION['salle']) ? $_SESSION['salle'] : $salles[0];
  }

  // Récupérer et afficher les noms des zones pour la salle sélectionnée
  $sql_zones = "SELECT NomZone FROM zones_ultrason WHERE IDSalle = '$salle_selectionnee'";
  $result_zones = mysqli_query($conn, $sql_zones);
  $zones = [];
  while ($zone = mysqli_fetch_assoc($result_zones)) {
      $zones[] = $zone['NomZone'];
  }

  // Calculer le nombre de colonnes et initialiser le tableau de couleurs
  $cols = count($zones);
  $rows = 2; // Une rangée pour les en-têtes et une pour les couleurs

  // Initialiser le tableau de couleurs
  $colors = array_fill(1, $rows, array_fill(1, $cols, ''));

  // Récupérer la dernière position depuis positions_ultrason
  $sql_last_position = "
      SELECT p.IDZone_ultrason, z.NomZone 
      FROM positions_ultrason p 
      JOIN zones_ultrason z ON p.IDZone_ultrason = z.IDZone_ultrason 
      WHERE z.IDSalle = '$salle_selectionnee' 
      ORDER BY p.DateHeure DESC 
      LIMIT 1";
  $result_last_position = mysqli_query($conn, $sql_last_position);
  $last_position = mysqli_fetch_assoc($result_last_position);

  // Colorer la dernière position si elle existe
  if ($last_position) {
      $last_zone = $last_position['NomZone'];
      $col_index = array_search($last_zone, $zones) + 1;
      if ($col_index !== false) {
          $colors[2][$col_index] = 'red'; // Couleur pour la dernière position
      }
  }
  ?>

  <!-- Formulaire de sélection de salle -->
  <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
      <label for="salle" class="salle-label">Sélectionnez une salle :</label>
      <select id="salle" name="salle">
          <?php foreach ($salles as $salle): ?>
              <option value="<?php echo $salle; ?>" <?php echo ($salle == $salle_selectionnee) ? 'selected' : ''; ?>>
                  <?php echo $salle; ?>
              </option>
          <?php endforeach; ?>
      </select>
      <button type="submit">Soumettre</button>
  </form>

  <!-- Tableau des positions -->
  <table>
      <tr>
          <th colspan="<?php echo $cols; ?>">Salle: <?php echo $salle_selectionnee; ?></th>
      </tr>
      <tr>
          <?php foreach ($zones as $zone): ?>
              <td><?php echo $zone; ?></td>
          <?php endforeach; ?>
      </tr>
      <tr>
          <?php for ($j = 1; $j <= $cols; $j++): ?>
              <?php $style = $colors[2][$j] ? "style='background-color: {$colors[2][$j]};'" : ""; ?>
              <td class='tab' <?php echo $style; ?>></td>
          <?php endfor; ?>
      </tr>
  </table>

  <?php
  // Fermer la connexion à la base de données
  mysqli_close($conn);
  ?>

  <footer>
      <ul class="IUT">
          <li>IUT de Blagnac</li>
          <li>Département Réseaux et Télécommunications</li>
          <li><a href="mentions-légales.html">Mentions légales</a></li>
      </ul>
  </footer>
</body>
</html>
