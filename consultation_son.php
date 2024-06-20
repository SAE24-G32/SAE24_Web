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
      <h1>Consultation son</h1>
      <nav>
          <ul>
              <li><a href="index.html" class="first">Accueil</a></li>
              <li><a href="#">Consultation son</a></li>
              <li><a href="consultation_ultrason.php">Consultation ultrason</a></li>
              <li><a href="admin.php">Administration</a></li>
              <li><a href="gestion_de_projet.html">Gestion de projet</a></li>
          </ul>
      </nav>
  </header>

  <h2>Tableau de consultation des positions</h2>

  <?php
  // Connection
  $conn = mysqli_connect("localhost", "sae24", "passroot", "sae24");
  if (!$conn) {
      die("Connexion échouée: " . mysqli_connect_error());
  }

  // Retrieve room IDs for the selection form
  $sql_salles = "SELECT IDSalle FROM salles";
  $result_salles = mysqli_query($conn, $sql_salles);
  $salles = [];
  while ($row = mysqli_fetch_assoc($result_salles)) {
      $salles[] = $row['IDSalle'];
  }

  // Set table size
  $rows = 16;
  $cols = 16;

  // Initialize the color table
  $colors = array_fill(1, $rows, array_fill(1, $cols, ''));

  // Color the sensors blue
  $colors[1][1] = 'blue';
  $colors[16][1] = 'blue';
  $colors[16][16] = 'blue';

  // Handle POST data for specific color
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $salle_selectionnee = $_POST['salle'];
      $_SESSION['salle'] = $salle_selectionnee;

      if (isset($_POST['row'], $_POST['col'], $_POST['color'])) {
          $row = intval($_POST['row']);
          $col = intval($_POST['col']);
          $color = $_POST['color'];

          if ($row >= 1 && $row <= $rows && $col >= 1 && $col <= $cols) {
              $colors[$rows - $row + 1][$col] = $color;
          }
      }
  } else {
      $salle_selectionnee = isset($_SESSION['salle']) ? $_SESSION['salle'] : $salles[0];
  }

  // Retrieve data from the SQL table
  $sql_positions = "SELECT ValeurX, ValeurY, DateHeure FROM positions_son WHERE IDSalle = '$salle_selectionnee' ORDER BY DateHeure";
  $result_positions = mysqli_query($conn, $sql_positions);
  $positions = mysqli_fetch_all($result_positions, MYSQLI_ASSOC);

  // Process positions
  foreach ($positions as $index => $position) {
      $x = intval($position["ValeurX"]);
      $y = intval($position["ValeurY"]);

      if ($x >= 1 && $x <= $cols && $y >= 1 && $y <= $rows) {
          $colors[$y][$x] = $index == count($positions) - 1 ? 'red' : 'orange';
      }
  }

  mysqli_close($conn);
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

  <table>
      <?php
      for ($i = $rows; $i >= 1; $i--) {
          echo "<tr>";
          for ($j = 1; $j <= $cols; $j++) {
              $style = $colors[$i][$j] ? "style='background-color: {$colors[$i][$j]};'" : "";
              echo "<td class='tab' $style></td>";
          }
          echo "</tr>";
      }
      ?>
  </table>

  <script>
    const tbody = document.querySelector("tbody");

    setInterval(function() {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);

                let i = 0;
                const tableau = Object.values(JSON.parse(this.responseText));
                while (i != tableau.length) {
                    const ligne = Object.values(tableau[i]);
                    console.log(ligne);
                    let y = 0;

                    while (y != tableau.length) {
                        const cas = ligne[y];
                        tbody.children[tbody.children.length - i - 1].children[y].style.backgroundColor = cas;
                        console.log(cas);
                        y++;
                    }

                    i++;
                }
            }
        };
        xmlhttp.open("GET", "./consultation_son_refresh.php?salle=<?php echo $salle_selectionnee; ?>", true);
        xmlhttp.send();
    }, 1000);
  </script>

  <footer>
      <ul class="IUT">
          <li>IUT de Blagnac</li>
          <li>Département Réseaux et Télécommunications</li>
          <li><a href="mentions-légales.html">Mentions légales</a></li>
      </ul>
  </footer>
</body>
</html>
