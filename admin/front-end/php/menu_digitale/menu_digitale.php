<?php
session_start();
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Gestionale Gelateria - Menu digitale</title>
  <link rel="stylesheet" href="../../../front-end/css/menu/menu_digitale.css">
</head>
<body>
  
  <main>
    <div class="center_column max_width">
      <div class="center_row max_width" style="height: 18vh; background-color: transparent;">
          <img class="logo_menu" src="../../../../img/david/david_logo_testo_2.png">
      </div>
      <div class="divider_menu"></div>
      <div class="center_column max_width" style="height: 40vh; background-color: transparent;" onclick="location.href = 'dashboard_menu_digitale.php?lang=it';">
          <img class="flag" src="../../../../img/david/menu_ita.png">
      </div>
      <div class="divider_menu"></div>
      <div class="center_column max_width" style="height: 40vh; background-color: transparent;" onclick="location.href = 'dashboard_menu_digitale.php?lang=en';">
          <img class="flag" src="../../../../img/david/menu_inglese.png">
      </div>
    </div>
  </main>
  
  <script src="../../../js/dashboard.js"></script>
  <script src="../../../js/menu/menu_digitale.js"></script>
</body>
</html>