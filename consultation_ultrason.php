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
  // Connection
  $conn = mysqli_connect("192.168.103.217", "sae24", "passroot", "sae24");
  if (!$conn) {
      die("Connexion échouée: " . mysqli_connect_error());
  }

  // Retrieve room IDs for the selection form
  $sql_salles = "SELECT DISTINCT IDSalle FROM zones_ultrason";
  $result_salles = mysqli_query($conn, $sql_salles);
  $salles = [];
  while ($row = mysqli_fetch_assoc($result_salles)) {
      $salles[] = $row['IDSalle'];
  }

  // Handling POST data
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $salle_selectionnee = $_POST['salle'];
      $_SESSION['salle'] = $salle_selectionnee;
  } else {
      $salle_selectionnee = isset($_SESSION['salle']) ? $_SESSION['salle'] : $salles[0];
  }

  // Retrieve and display zone names for the selected room
  $sql_zones = "SELECT NomZone FROM zones_ultrason WHERE IDSalle = '$salle_selectionnee'";
  $result_zones = mysqli_query($conn, $sql_zones);
  $zones = [];
  while ($zone = mysqli_fetch_assoc($result_zones)) {
      $zones[] = $zone['NomZone'];
  }

  // Calculate the number of columns and initialize the color table
  $cols = count($zones);
  $rows = 2; // One row for headers and one for colors

  // Initialize the color table
  $colors = array_fill(1, $rows, array_fill(1, $cols, ''));

  // Retrieve the last position from positions_ultrason
  $sql_last_position = "
      SELECT p.IDZone_ultrason, z.NomZone 
      FROM positions_ultrason p 
      JOIN zones_ultrason z ON p.IDZone_ultrason = z.IDZone_ultrason 
      WHERE z.IDSalle = '$salle_selectionnee' 
      ORDER BY p.DateHeure DESC 
      LIMIT 1";
  $result_last_position = mysqli_query($conn, $sql_last_position);
  $last_position = mysqli_fetch_assoc($result_last_position);

  // Color the last position if it exists
  if ($last_position) {
      $last_zone = $last_position['NomZone'];
      $col_index = array_search($last_zone, $zones) + 1;
      if ($col_index !== false) {
          $colors[2][$col_index] = 'red'; //  Color for the last position
      }
  }
  ?>

  <!-- Room selection form -->
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

  <!-- Position table -->
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

  <script>
    const columns = document.querySelector("tbody").children[2].children

    setInterval(function() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let i = 0
                console.log(this.responseText)
                const zone = JSON.parse(this.responseText)
                while (i != columns.length) {
                    if (i == zone - 1) {
                        columns[i].style.backgroundColor = "red"
                    } else {
                        columns[i].style.backgroundColor = ""
                    }
                    i++
                }
            }
        };
        xmlhttp.open("GET", "./consultation_ultrason_refresh.php?salle=<?php echo $salle_selectionnee; ?>", true);
        xmlhttp.send();
    }, 1000)
  </script>

  <?php
  // Close the database connection
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
