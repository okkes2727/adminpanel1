<?php 
include("ayar.php");

$bul="SELECT*FROM menu";
$kayit=$conn->query($bul);


?>





" 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/normalize.css">

<link rel="stylesheet" href="css/style.css">
</head>
<body>
<center><h1>NAVBAR PANELİ</h1></center>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark  text-white">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><img src="img/logo.png" height="60px" alt="resim"></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav"> 
      <?php
                    if ($kayit->num_rows > 0) 
                        while ($satir = $kayit->fetch_assoc()) {
                            echo '<li class="nav-item">';
                            echo '<a class="nav-link" href="#">' . $satir["menu_isim"] . '</a>';
                            echo '</li>';
                  
                    }
                    ?>
      </ul>
    </div>
  </div>
</nav>

</body>
</html>