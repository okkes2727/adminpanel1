<?php 
include("ayar.php");

$sayfa = 1;
if(isset($_GET['sayfa'])){
    $sayfa = $_GET["sayfa"];
}

// Menü sorgusu
$sql_menu = "SELECT * FROM menu ORDER BY menu_sira";
$result_menu = $conn->query($sql_menu);



$bul_icerik = "SELECT * FROM icerik WHERE menu_id = $sayfa";
$kayit_icerik = $conn->query($bul_icerik);

if (!$kayit_icerik) {
    echo "Hata: " . $conn->error;
}

$bul_footer = "SELECT * FROM footer";
$kayit_footer = $conn->query($bul_footer);

$slaytSorgusu = "SELECT * FROM slayt";
$slaytSonuclari = $conn->query($slaytSorgusu);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Optional: Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- jQuery, Popper.js, Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        /* Custom Styles */
        .custom-carousel-image {
            height: 650px; /* Slayt resim yüksekliği */
            object-fit: cover;
        }
        
    .carousel-container {
            margin-top: 56px; /* Navbar'ın yüksekliği kadar boşluk bırak */
        }
     
    /* Diğer stiller */

    .banner {
            width: 100%;
            max-width: 100%;
            overflow: hidden;
            position: relative;
            height: 70px; /* İstediğiniz yüksekliği ayarlayın */
        }

        .banner img {
            width: 100%;
            height: auto;
            display: block; /* Resimde boşlukları önlemek için */
        }


    </style>
    
</head>

<body>


    <!-- Navbar -->
       <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">FELSEFEGRAM</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <?php
        
                if ($result_menu->num_rows > 0) {
                    while ($row_menu = $result_menu->fetch_assoc()) {
                        echo "<li class='nav-item dropdown'>";
                        echo "<a class='nav-link dropdown-toggle' href='#' id='navbarDropdown{$row_menu['menu_id']}' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>{$row_menu['menu_isim']}</a>";

                        // Alt Menüler  
                        $menu_id = $row_menu['menu_id'];
                        $sql_alt_menu = "SELECT * FROM alt_menu WHERE menu_id = $menu_id ORDER BY alt_menu_sira";
                        $result_alt_menu = $conn->query($sql_alt_menu);

                        echo "<div class='dropdown-menu' aria-labelledby='navbarDropdown{$row_menu['menu_id']}'>";
                        while ($row_alt_menu = $result_alt_menu->fetch_assoc()) {
                            echo "<a class='dropdown-item' href='?alt_menu_id={$row_alt_menu['alt_menu_id']}'>{$row_alt_menu['alt_menu_adi']}</a>";
                        }
                        echo "</div>";

                        echo "</li>";
                    }
                }
                ?>
            </ul>
        </div>
    </nav>


<div class="carousel-container">
<div id="carouselExampleSlidesOnly"  class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <?php
    if ($slaytSonuclari->num_rows > 0) {
      $ilkResim = true; // İlk resmi aktif yapmak için
      while ($row = $slaytSonuclari->fetch_assoc()) {
        $resimYolu = $row["resim"];
        echo '<div class="carousel-item' . ($ilkResim ? ' active' : '') . '">';
        echo '<img src="./img/' . $resimYolu . '" class="d-block w-100 custom-carousel-image">';
        echo '</div>';
        $ilkResim = false;
      }
    } else {
      echo '<div class="carousel-item active">';
      echo '<img src="img/varsayilan_resim.jpg" class="d-block w-100 custom-carousel-image">';
      echo '</div>';
    }
    ?>
  </div>

  <!-- Manuel kontrol okları -->
  <a class="carousel-control-prev" href="#carouselExampleSlidesOnly" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden"></span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleSlidesOnly" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden"></span>
  </a>
</div>
</div>

<script>
  // Otomatik hareket etmeyen carousel için elle kontrol
  $('.carousel-control-prev').click(function() {
    $('#carouselExampleSlidesOnly').carousel('prev');
  });

  $('.carousel-control-next').click(function() {
    $('#carouselExampleSlidesOnly').carousel('next');
  });
</script>


<!-- İçerik Gösterme -->
<div class="container mt-4">
    <?php
    if (isset($_GET['alt_menu_id'])) {
        $selected_alt_menu_id = $_GET['alt_menu_id'];
        $sql_icerik = "SELECT * FROM icerik WHERE alt_menu_id = $selected_alt_menu_id ORDER BY icerik_sira";
        $result_icerik = $conn->query($sql_icerik);

        while ($row_icerik = $result_icerik->fetch_assoc()) {
            echo "<h2>{$row_icerik['icerik_baslik']}</h2>";
            echo htmlspecialchars_decode($row_icerik['icerik_yazi']);

            // Resim
            if ($row_icerik['resim']) {
                echo "<img src='{$row_icerik['resim']}' alt='Resim'>";
            }
        }
    } else {
        echo "<p>Lütfen bir alt menü seçin.</p>";
    }
    ?>
</div>






   <!-- Footer -->
   <footer class="bg-dark text-white text-center py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <?php
                    $footer_data = $kayit_footer->fetch_assoc();
                    echo $footer_data["footer_baslik"] . "<br>";
                    echo $footer_data["footer_adres"] . "<br>";
                    echo $footer_data["footer_tel"] . "<br>";
                    echo $footer_data["footer_eposta"] . "<br>";
                    ?>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>
