<?php
 include '../ayar.php';


if (isset($_GET['logout'])) {
    // Oturumu kapat
    session_unset();
    session_destroy();
    

    header("Location: login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <link rel="stylesheet" href="sm/summernote.css">
    <script src="sm/summernote.min.js"></script>

</head>
<body>
<div class="container text-center mt-3">
    <h2 class="text-center">Admin Paneli</h2>
    <a href="../login.php?logout" class="btn btn-danger">Çıkış Yap</a>
</div>




<?php


// Menü ekleme işlemi
if (isset($_POST['ekle'])) {
    $menu_isim = $_POST['menu_isim'];
    $menu_sira = $_POST['menu_sira'];

    $sql_menu_ekle = "INSERT INTO menu (menu_isim, menu_sira, menu_durum) VALUES ('$menu_isim', $menu_sira, 1)";

    if ($conn->query($sql_menu_ekle) === TRUE) {
        echo "Menü başarıyla eklendi.";
    } else {
        echo "Hata: " . $sql_menu_ekle . "<br>" . $conn->error;
    }
}


// Menü silme işlemi
if (isset($_POST['sil'])) {
    $sil_menu_id = $_POST['sil_menu_id'];

    // Menüyü Sil
    $sql_sil_menu = "DELETE FROM menu WHERE menu_id = $sil_menu_id";

    if ($conn->query($sql_sil_menu) === TRUE) {
        echo "Menü başarıyla silindi.";
    } else {
        echo "Hata: " . $sql_sil_menu . "<br>" . $conn->error;
    }
}



// Menü sıra değiştirme işlemi
if (isset($_POST['sira_degistir'])) {
    $sira_degistir_id1 = $_POST['sira_degistir_id1'];
    $sira_degistir_id2 = $_POST['sira_degistir_id2'];

    // İki menü sırasını değiştirme işlemi gerçekleştir
    $sql_sira_degistir = "UPDATE menu SET menu_sira = (CASE
                                WHEN menu_id = $sira_degistir_id1 THEN (SELECT menu_sira FROM menu WHERE menu_id = $sira_degistir_id2)
                                WHEN menu_id = $sira_degistir_id2 THEN (SELECT menu_sira FROM menu WHERE menu_id = $sira_degistir_id1)
                             END)
                         WHERE menu_id IN ($sira_degistir_id1, $sira_degistir_id2)";

    if ($conn->query($sql_sira_degistir) === TRUE) {
        echo "Menü sıraları başarıyla değiştirildi.";
    } else {
        echo "Hata: " . $sql_sira_degistir . "<br>" . $conn->error;
    }
}



?>


<?php


// Menü güncelleme işlemi
if (isset($_POST['guncelle'])) {
    $secilen_menu_ad = $_POST['secilen_menu_ad'];
    $yeni_menu_isim = $_POST['yeni_menu_isim'];

    // Menüyü Güncelle
    $sql_guncelle_menu = "UPDATE menu SET menu_isim = '$yeni_menu_isim' WHERE menu_isim = '$secilen_menu_ad'";

    if ($conn->query($sql_guncelle_menu) === TRUE) {
        echo "Menü başarıyla güncellendi.";
    } else {
        echo "Hata: " . $sql_guncelle_menu . "<br>" . $conn->error;
    }
}
?>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">


<div class="container">
    <h2 class="mt-5 mb-4">Menü Güncelleme Formu</h2>

    <form method="post" action="" class="mb-5">
        <div class="form-group">
            <label for="secilen_menu_ad">Güncellenecek Menü Adı:</label>
            <select name="secilen_menu_ad" class="form-control" required>
                <?php
                $sql_menu_adlari = "SELECT menu_isim FROM menu";
                $result_menu_adlari = $conn->query($sql_menu_adlari);

                if ($result_menu_adlari->num_rows > 0) {
                    while($row = $result_menu_adlari->fetch_assoc()) {
                        echo "<option value='" . $row["menu_isim"] . "'>" . $row["menu_isim"] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="yeni_menu_isim">Yeni Menü Adı:</label>
            <input type="text" name="yeni_menu_isim" class="form-control" required>
        </div>

        <button type="submit" name="guncelle" class="btn btn-primary">Menüyü Güncelle</button>
    </form>
</div>


<div class="container">
    <h2>Menü Ekle</h2>
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#menuEkleForm" aria-expanded="false" aria-controls="menuEkleForm">
        Menü Ekleme Formu
    </button>

    <div class="collapse" id="menuEkleForm">
        <form action="adminpanel.php" method="post">
            <!-- Menü Ekleme Formu -->
            <div class="form-group">
                <label for="menu_isim">Menü İsim:</label>
                <input type="text" class="form-control" name="menu_isim" required>
            </div>
            <div class="form-group">
                <label for="menu_sira">Sıra:</label>
                <input type="number" class="form-control" name="menu_sira" required>
            </div>
            <button type="submit" class="btn btn-primary" name="ekle">Menü Ekle</button>
        </form>
    </div>
</div>
<div class="container">
    <h2 class="mt-5">Menü Sil / Sıra Değiştir</h2>

    <!-- Menü Silme ve Sıra Değiştirme Formları -->
    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#silDegistirFormlar" aria-expanded="false" aria-controls="silDegistirFormlar">
        Menü Sil / Sıra Değiştir Formları
    </button>

    <div class="collapse" id="silDegistirFormlar">
        <form action="adminpanel.php" method="post">
            <!-- Menü Silme Formu -->
            <div class="form-group">
                <label for="sil_menu_id">Menü Seç:</label>
                <select class="form-control" name="sil_menu_id">
                    <?php
                    $sql_menu_sil = "SELECT menu_id, menu_isim FROM menu ORDER BY menu_sira";
                    $result_menu_sil = $conn->query($sql_menu_sil);

                    if ($result_menu_sil->num_rows > 0) {
                        while ($row_menu_sil = $result_menu_sil->fetch_assoc()) {
                            $menu_id_sil = $row_menu_sil['menu_id'];
                            $menu_isim_sil = $row_menu_sil['menu_isim'];
                            echo "<option value='$menu_id_sil'>$menu_isim_sil</option>";
                        }
                    } else {
                        echo "<option value=''>Menü Bulunamadı</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-danger" name="sil">Menü Sil</button>
        </form>

        <hr>

        <form action="adminpanel.php" method="post">
            <!-- Sıra Değiştirme Formu -->
            <div class="form-group">
                <label for="sira_degistir_id1">Menü 1:</label>
                <select class="form-control" name="sira_degistir_id1">
                    <?php
                    $sql_menu_degistir1 = "SELECT menu_id, menu_isim FROM menu ORDER BY menu_sira";
                    $result_menu_degistir1 = $conn->query($sql_menu_degistir1);

                    if ($result_menu_degistir1->num_rows > 0) {
                        while ($row_menu_degistir1 = $result_menu_degistir1->fetch_assoc()) {
                            $menu_id_degistir1 = $row_menu_degistir1['menu_id'];
                            $menu_isim_degistir1 = $row_menu_degistir1['menu_isim'];
                            echo "<option value='$menu_id_degistir1'>$menu_isim_degistir1</option>";
                        }
                    } else {
                        echo "<option value=''>Menü Bulunamadı</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="sira_degistir_id2">Menü 2:</label>
                <select class="form-control" name="sira_degistir_id2">
                    <?php
                    $sql_menu_degistir2 = "SELECT menu_id, menu_isim FROM menu ORDER BY menu_sira";
                    $result_menu_degistir2 = $conn->query($sql_menu_degistir2);

                    if ($result_menu_degistir2->num_rows > 0) {
                        while ($row_menu_degistir2 = $result_menu_degistir2->fetch_assoc()) {
                            $menu_id_degistir2 = $row_menu_degistir2['menu_id'];
                            $menu_isim_degistir2 = $row_menu_degistir2['menu_isim'];
                            echo "<option value='$menu_id_degistir2'>$menu_isim_degistir2</option>";
                        }
                    } else {
                        echo "<option value=''>Menü Bulunamadı</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-warning" name="sira_degistir">Menü Sırasını Değiştir</button>
        </form>
    </div>
</div>


<br><br>


<div class="container mt-4">
    <form id="menuSelectionForm" method="post" class="border p-4">
        <div class="form-group">
            <label for="selectedMenu">Menü Seçimi:</label>
            <select id="selectedMenu" name="selectedMenu" class="form-control">
                <?php
                $result_menu_liste = $conn->query("SELECT * FROM menu ORDER BY menu_sira");
                if ($result_menu_liste->num_rows > 0) {
                    while ($row_menu = $result_menu_liste->fetch_assoc()) {
                        echo '<option value="' . $row_menu['menu_id'] . '">' . $row_menu['menu_isim'] . '</option>';
                    }
                } else {
                    echo '<option value="" disabled>No menus available</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" name="showSelectedMenu" class="btn btn-primary">Alt Menüyü Göster</button>
    </form>
</div>



<div class="container">
<?php
if (isset($_POST['showSelectedMenu'])) {
    $selectedMenuId = isset($_POST['selectedMenu']) ? $_POST['selectedMenu'] : null;

    if ($selectedMenuId !== null) {
        echo '<h2 class="mt-5">Alt Menü Listesi - Seçilen Menü: ';
        
        $result_selected_menu = $conn->query("SELECT menu_isim FROM menu WHERE menu_id = $selectedMenuId");
        if ($result_selected_menu->num_rows > 0) {
            $row_selected_menu = $result_selected_menu->fetch_assoc();
            echo $row_selected_menu['menu_isim'];
        }

        echo '</h2>';

        
                echo '<div class="container mt-5">';
                echo '    <h2 class="mb-4">Alt Menü Ekleme</h2>';
                echo '    <form action="adminpanel.php" method="post">';
                echo '        <div class="mb-3">';
                echo '            <label for="alt_menu_adi" class="form-label">Alt Menü İsim:</label>';
                echo '            <input type="text" class="form-control" name="alt_menu_adi" required>';
                echo '        </div>';
                echo '        <div class="mb-3">';
                echo '            <label for="alt_menu_sira" class="form-label">Sıra:</label>';
                echo '            <input type="number" class="form-control" name="alt_menu_sira" required>';
                echo '        </div>';
                echo '        <input type="hidden" name="selectedMenuId" value="' . $selectedMenuId . '">';
                echo '        <input type="hidden" name="actionType" value="addSubMenu">';
                echo '        <button type="submit" class="btn btn-success" name="submit">Alt Menü Ekle</button>';
                echo '    </form>';
                echo '</div><br><br>';



        // Alt Menü Sırasını Değiştirme Formu
                echo '<form action="adminpanel.php" method="post">';
                echo '    <label for="selectedSubmenu" class="form-label">Alt Menü Seç:</label>';
                echo '    <select id="selectedSubmenu" name="selectedSubmenu" class="form-control">';
                
                // Alt menüleri çek ve listele
                $result_submenu_list = $conn->query("SELECT alt_menu_id, alt_menu_adi FROM alt_menu WHERE menu_id = $selectedMenuId ORDER BY alt_menu_sira");
                while ($row_submenu = $result_submenu_list->fetch_assoc()) {
                    echo '        <option value="' . $row_submenu['alt_menu_id'] . '">' . $row_submenu['alt_menu_adi'] . '</option>';
                }
                
                echo '    </select>';
                echo '    <label for="newOrder" class="form-label">Yeni Sıra:</label>';
                echo '    <input type="number" id="newOrder" name="newOrder" class="form-control" required>';
                echo '    <input type="hidden" name="actionType" value="changeOrder">';
                echo '  <br>  <button type="submit" class="btn btn-warning" name="submit">Sırayı Değiştir</button>';
                echo '</form>';

        // Alt Menü Taşıma Formu
        echo '<form action="adminpanel.php" method="post">';
        echo '    <label for="selectedSubmenu" class="form-label">Alt Menü Seç:</label>';
        echo '    <select id="selectedSubmenu" name="selectedSubmenu" class="form-control">';
        
        $result_submenu_list = $conn->query("SELECT alt_menu_id, alt_menu_adi FROM alt_menu WHERE menu_id = $selectedMenuId ORDER BY alt_menu_sira");
        while ($row_submenu = $result_submenu_list->fetch_assoc()) {
            echo '<option value="' . $row_submenu['alt_menu_id'] . '">' . $row_submenu['alt_menu_adi'] . '</option>';
        }
        
         echo '    </select>';
        echo '    <label for="targetMenu" class="form-label">Hedef Menü Seç:</label>';
        echo '    <select id="targetMenu" name="targetMenu" class="form-control">';
        
        $result_target_menu_list = $conn->query("SELECT menu_id, menu_isim FROM menu WHERE menu_id != $selectedMenuId ORDER BY menu_sira");
        while ($row_target_menu = $result_target_menu_list->fetch_assoc()) {
            echo '<option value="' . $row_target_menu['menu_id'] . '">' . $row_target_menu['menu_isim'] . '</option>';
        }
        
            echo '    </select>';
            echo '    <input type="hidden" name="actionType" value="moveSubMenu">';
            echo '    <button type="submit" class="btn btn-primary" name="submit">Alt Menüyü Taşı</button>';
            echo '</form>';

        // Alt Menü Listesi
        $result_alt_menu_liste = $conn->query("SELECT * FROM alt_menu WHERE menu_id = $selectedMenuId ORDER BY alt_menu_sira");
        if ($result_alt_menu_liste->num_rows > 0) {
            echo '<ul class="list-group" id="altMenuList">';
            
            while ($row_alt_menu = $result_alt_menu_liste->fetch_assoc()) {
                echo"<br><h4>ALT MENÜLER</h4>";
                echo '<li class="list-group-item d-flex justify-content-between align-items-center">
                        ' . $row_alt_menu['alt_menu_adi'] . '
                        <span class="badge badge-primary badge-pill">' . $row_alt_menu['alt_menu_sira'] . '</span>
                    </li><br>';
            }

            echo '</ul>';
        } else {
            echo '<ul class="list-group" id="altMenuList">';
            echo '<li class="list-group-item">Seçilen menüye ait alt menü bulunamadı.</li>';
            echo '</ul>';
        }
    } else {
        echo '<h2 class="mt-5">Lütfen bir menü seçiniz...</h2>';
        echo '<ul class="list-group" id="altMenuList">';
        echo '<li class="list-group-item">Lütfen bir menü seçin.</li>';
        echo '</ul>';
    }
     // Alt Menü Silme Formu
            echo '<form action="adminpanel.php" method="post">';
        echo '<div class="form-group">';
        echo '<label for="deleteSubmenu"><h4>Silinecek Alt Menü Seç:</h4></label>';
        echo '<select id="deleteSubmenu" name="deleteSubmenu" class="form-control">';

     $result_submenu_list = $conn->query("SELECT alt_menu_id, alt_menu_adi FROM alt_menu WHERE menu_id = $selectedMenuId ORDER BY alt_menu_sira");
     while ($row_submenu = $result_submenu_list->fetch_assoc()) {
         echo '<option value="' . $row_submenu['alt_menu_id'] . '">' . $row_submenu['alt_menu_adi'] . '</option>';
     }

     echo '</select>';
     echo '<input type="hidden" name="selectedMenuId" value="' . $selectedMenuId . '">';
     echo '<input type="hidden" name="actionType" value="deleteSubMenu">';
     echo '<button type="submit" class="btn btn-danger" name="submit">Alt Menüyü Sil</button>';
     echo '</form>';

 } else {
     echo '<h5 class="mt-1"> Lütfen bir menü seçiniz...</h5>';
  
 }

 if (isset($_POST['submit'])) {
    $actionType = $_POST['actionType'];

    if ($actionType === 'addSubMenu') {
        $alt_menu_adi = $_POST['alt_menu_adi'];
        $alt_menu_sira = $_POST['alt_menu_sira'];
        $selectedMenuId = $_POST['selectedMenuId'];

        $sql_alt_menu_ekle = "INSERT INTO alt_menu (alt_menu_adi, alt_menu_sira, menu_id) VALUES ('$alt_menu_adi', $alt_menu_sira, $selectedMenuId)";

        if ($conn->query($sql_alt_menu_ekle) === TRUE) {
            echo "Alt Menü başarıyla eklendi.";
        } else {
            echo "Hata: " . $sql_alt_menu_ekle . "<br>" . $conn->error;
        }
    } elseif ($actionType === 'changeOrder') {
        $selectedSubmenuId = $_POST['selectedSubmenu'];
        $newOrder = $_POST['newOrder'];

        // Alt Menü Sırasını Değiştir
        $sql_change_order = "UPDATE alt_menu SET alt_menu_sira = $newOrder WHERE alt_menu_id = $selectedSubmenuId";
        if ($conn->query($sql_change_order) === TRUE) {
            echo "Alt Menü sırası başarıyla değiştirildi.";
        } else {
            echo "Hata: " . $sql_change_order . "<br>" . $conn->error;
        }
    } elseif ($actionType === 'moveSubMenu') {
        $selectedSubmenuId = $_POST['selectedSubmenu'];
        $targetMenuId = $_POST['targetMenu'];

        // Alt Menüyü Hedef Menüye Taşı
        $sql_move_submenu = "UPDATE alt_menu SET menu_id = $targetMenuId WHERE alt_menu_id = $selectedSubmenuId";
        if ($conn->query($sql_move_submenu) === TRUE) {
            echo "Alt Menü başarıyla taşındı.";
        } else {
            echo "Hata: " . $sql_move_submenu . "<br>" . $conn->error;
        }
    } elseif ($actionType === 'deleteSubMenu') {
        $selectedSubmenuId = $_POST['deleteSubmenu'];

        // Alt Menüyü Sil
        $sql_delete_submenu = "DELETE FROM alt_menu WHERE alt_menu_id = $selectedSubmenuId";
        if ($conn->query($sql_delete_submenu) === TRUE) {
            echo "Alt Menü başarıyla silindi.";
        } else {
            echo "Hata: " . $sql_delete_submenu . "<br>" . $conn->error;
        }
    }
}



?>
</div>


<!-- Slayt İçeriği -->
<div class="container mt-5">
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['resim_ekle'])) {
    // Dosya adını al
    $dosyaAdi = $_FILES['resim']['name'];

    // Dosya uzantısını alma
    $dosyaUzantisi = strtolower(pathinfo($dosyaAdi, PATHINFO_EXTENSION));

    // İzin verilen dosya türleri
    $izinVerilenUzantilar = array('jpg', 'jpeg', 'png');

    // Dosya adını kontrol etme
    if (in_array($dosyaUzantisi, $izinVerilenUzantilar)) {
        // dosya boyutunu kontrol ediyoruz
        if ($_FILES['resim']['size'] <= 5 * 1024 * 1024) {
            // Dosyayı belirtilen klasöre taşıyoruz 
            $hedefKlasor = $_SERVER['DOCUMENT_ROOT'] . '/proje/img/';

            // Dosya yolunu belirleme
            $dosyaYolu = $hedefKlasor . $dosyaAdi;

            if (move_uploaded_file($_FILES['resim']['tmp_name'], $dosyaYolu)) {
             
                $sql_slayt_ekle = "INSERT INTO slayt (resim) VALUES ('$dosyaAdi')";
                $result = mysqli_query($conn, $sql_slayt_ekle);
                if ($result != null) {
                    echo "Resim başarıyla eklendi.";    
                 
                } else {
                    echo "Hata: " . $sql_slayt_ekle . "<br>" . $conn->error;
                
                 
                }
              
            } else {
                echo "Dosya yükleme hatası.";
            }
        } else {
            echo "Dosya boyutu çok büyük. Maksimum 5MB olmalı.";
        }
    } else {
        echo "Geçersiz dosya uzantısı. Sadece JPG, JPEG, PNG ve GIF dosyaları kabul edilir.";
    }
}



if (isset($_POST['resim_sil'])) {
    $sil_resim_url = $_POST['sil_resim_url'];

    $sql_sil = "DELETE FROM slayt WHERE resim = '$sil_resim_url'";
    if ($conn->query($sql_sil) === TRUE) {
        echo "Resim başarıyla silindi.";
    } else {
        echo "Hata: " . $sql_sil . "<br>" . $conn->error;
    }
}

?>

<<div class="container mt-5">
    <h2>Slayt Resmi Ekle</h2>
    <form action="" method="post" enctype="multipart/form-data" class="border p-4">
        <div class="form-group">
            <label for="resim">Resim Seç:</label>
            <input type="file" class="form-control-file" name="resim" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary" name="resim_ekle">Resim Ekle</button>
    </form>

    <form action="" method="post" class="border p-4 mt-4">
        <div class="form-group">
            <label for="sil_resim_url">Silinecek Resim URL'si:</label>
            <select name="sil_resim_url" class="form-control" required>
                <?php
                $sql_resimler = "SELECT resim FROM slayt";
                $result_resimler = $conn->query($sql_resimler);
                while ($row_resim = $result_resimler->fetch_assoc()) {
                    echo '<option value="' . $row_resim['resim'] . '">' . $row_resim['resim'] . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-danger" name="resim_sil">Resimi Sil</button>
    </form>
</div>



<?php

$sql_menu = "SELECT menu_id, menu_isim FROM menu";
$result_menu = $conn->query($sql_menu);

$menu_options = "";
if ($result_menu->num_rows > 0) {
    while ($row_menu = $result_menu->fetch_assoc()) {
        $menu_id = $row_menu['menu_id'];
        $menu_isim = $row_menu['menu_isim'];
        $menu_options .= "<option value='$menu_id'>$menu_isim</option>";
    }
}

$sql_alt_menu = "SELECT alt_menu_id, alt_menu_adi FROM alt_menu";
$result_alt_menu = $conn->query($sql_alt_menu);

$alt_menu_options = "";
if ($result_alt_menu->num_rows > 0) {
    while ($row_alt_menu = $result_alt_menu->fetch_assoc()) {
        $alt_menu_id = $row_alt_menu['alt_menu_id'];
        $alt_menu_adi = $row_alt_menu['alt_menu_adi'];
        $alt_menu_options .= "<option value='$alt_menu_id'>$alt_menu_adi</option>";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen verileri alLDIK POST EDİYOZ TEZAHÜRAT xd
    $alt_menu_id = intval($_POST['alt_menu_id']);
    $icerik_baslik = htmlspecialchars($_POST['icerik_baslik']);
    $icerik_yazi = htmlspecialchars($_POST['icerik_yazi']);
    $icerik_sira = intval($_POST['icerik_sira']);

    // Dosya yükleme işleminş yaptk
    $target_dir = "../img/"; // Yüklenen dosyanın kaydedileceği dizini belirliyormuşuz
    $target_file = $target_dir . basename($_FILES["resim"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Dosyayı yüklemeye yarıyo
    if (move_uploaded_file($_FILES["resim"]["tmp_name"], $target_file)) {
  

        // Göreceli dosya yolu oluşturdum 
        $relative_path = "img/" . basename($_FILES["resim"]["name"]);

        $sql_insert = "INSERT INTO icerik (icerik_baslik, icerik_yazi, resim, icerik_sira, alt_menu_id) VALUES ('$icerik_baslik', '$icerik_yazi', '$relative_path', $icerik_sira, $alt_menu_id)";

        // Sorguyu çalıştırdım
        if ($conn->query($sql_insert) === TRUE) {
            echo "";
        } else {
            echo "Hata: " . $conn->error;
        }
    } else {
        echo "Dosya yüklenirken bir hata oluştu.";
    }
}


?>

<br>

<div class="container mt-5">
    <h2>İçerik Ekleme</h2>
    <div class="container mt-5">
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Formu Göster/Gizle
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="card-body">
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="menu_id">Menü Seçimi:</label>
                            <select class="form-control" name="menu_id" id="menu_id" required>
                                <?php echo $menu_options; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="alt_menu_id">Alt Menü Seçimi:</label>
                            <select class="form-control" name="alt_menu_id" id="alt_menu_id" required>
                                <?php echo $alt_menu_options; ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="icerik_baslik">İçerik Başlığı:</label>
                            <input type="text" class="form-control" id="icerik_baslik" name="icerik_baslik" required>
                        </div>

                        <div class="form-group">
                            <label for="icerik_yazi">İçerik:</label>
                            <textarea id="icerik_yazi" name="icerik_yazi" class="summernote"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="icerik_sira">İçerik Sırası:</label>
                            <input type="number" class="form-control" id="icerik_sira" name="icerik_sira" required>
                        </div>

                        <div class="form-group">
                            <label for="resim">Resim:</label>
                            <input type="file" class="form-control-file" id="resim" name="resim" accept="image/*" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Ekle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.summernote').summernote({
            height: 300, // editör boyutunu yazdık
     
        });
    });
</script>

<br>
<div class="container mt-5">
    <div class="accordion" id="accordionExample">
        <div class="card">
            <div class="card-header" id="headingOne">
                <h2 class="mb-0">
                    <button class="btn btn-link" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Silinecek İçerik Başlığını Seç
                    </button>
                </h2>
            </div>

            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                <div class="card-body">
                    <!-- silincek başlıgı seçtim-->
                    <form action="" method="post">
                        <?php
                        // İçerik başlıgını çektim
                        $sql_basliklar = "SELECT DISTINCT icerik_baslik FROM icerik";
                        $result_basliklar = $conn->query($sql_basliklar);

                        if ($result_basliklar->num_rows > 0) {
                            echo '<div class="form-group">';
                            echo '<label for="icerik_baslik_to_delete">Silinecek İçerik Başlığı:</label>';
                            echo '<select class="form-control" name="icerik_baslik_to_delete" required>';
                            
                            while ($row_basliklar = $result_basliklar->fetch_assoc()) {
                                $icerik_baslik = $row_basliklar['icerik_baslik'];
                                echo '<option value="' . $icerik_baslik . '">' . $icerik_baslik . '</option>';
                            }

                            echo '</select>';
                            echo '</div>';
                            echo '<button type="submit" class="btn btn-danger" name="deleteContentByTitle">İçeriği Sil</button>';
                        } else {
                            echo "Hiç içerik bulunamadı.";
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Form Bitişi -->
</div>


<!-- Footer İçeriği -->
<div class="container mt-5">
   <?php
// Footer bilgilerini güncelleme işlemi
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $footer_id = $_POST['footer_id'];
    $footer_baslik = htmlspecialchars($_POST['footer_baslik']);
    $footer_adres = htmlspecialchars($_POST['footer_adres']);
    $footer_tel = htmlspecialchars($_POST['footer_tel']);
    $footer_eposta = htmlspecialchars($_POST['footer_eposta']);

    // SQL sorgusu oluşturma
    $sql_update = "UPDATE footer SET footer_baslik='$footer_baslik', footer_adres='$footer_adres', footer_tel='$footer_tel', footer_eposta='$footer_eposta' WHERE footer_id='$footer_id'";

    // Sorguyu çalıştırma
    if ($conn->query($sql_update) === TRUE) {
        echo "";
    } else {
        echo "Hata: " . $conn->error;
    }
}

$sql_select = "SELECT * FROM footer";
$result = $conn->query($sql_select);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
?>

<div class="container mt-5">
    <h2 class="mb-4">Footer Bilgilerini Güncelle</h2>

    <!-- Açılır kapanır form için collapse kullanımı -->
    <div class="accordion" id="footerFormAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="footerFormHeader">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#footerFormCollapse" aria-expanded="true" aria-controls="footerFormCollapse">
                    Footer Bilgileri
                </button>
            </h2>
            <div id="footerFormCollapse" class="accordion-collapse collapse show" aria-labelledby="footerFormHeader" data-bs-parent="#footerFormAccordion">
                <div class="accordion-body">
                    <form action="" method="post">
                        <input type="hidden" name="footer_id" value="<?php echo $row['footer_id']; ?>">

                        <div class="mb-3">
                            <label for="footer_baslik" class="form-label">Footer Başlık:</label>
                            <input type="text" class="form-control" name="footer_baslik" value="<?php echo $row['footer_baslik']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="footer_adres" class="form-label">Footer Adres:</label>
                            <input type="text" class="form-control" name="footer_adres" value="<?php echo $row['footer_adres']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="footer_tel" class="form-label">Footer Telefon:</label>
                            <input type="tel" class="form-control" name="footer_tel" value="<?php echo $row['footer_tel']; ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="footer_eposta" class="form-label">Footer E-posta:</label>
                            <input type="email" class="form-control" name="footer_eposta" value="<?php echo $row['footer_eposta']; ?>" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
} else {
    echo "Footer bilgileri bulunamadı.";
}
?>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- jQuery ve Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
<!-- Summernote -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

</body>
</html>
