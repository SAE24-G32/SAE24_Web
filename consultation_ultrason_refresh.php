<?php
  // Connection
  $conn = mysqli_connect("192.168.103.217", "sae24", "passroot", "sae24");
  if (!$conn) {
      die("Connexion échouée: " . mysqli_connect_error());
  }

  $salle_selectionnee = $_GET['salle'];

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
  $last_zone = $last_position['IDZone_ultrason'];

  echo json_encode($last_zone);