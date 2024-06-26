<?php
session_start();

include("ayar.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen kullanıcı adı ve şifre
    $kullaniciAdi = $_POST["username"];
    $sifre = $_POST["password"];

    // SQL sorgusu ile kullanıcıyı bul
    $sorgu = "SELECT * FROM kullanici WHERE kullanici_adi = '$kullaniciAdi'";
    $result = $conn->query($sorgu);

    // Kullanıcı var mı kontrol et
    if ($result->num_rows > 0) {
        $kullanici = $result->fetch_assoc();

        // Şifre kontrolü
        if ($sifre == $kullanici["kullanici_sifre"]) {
            // Giriş başarılı
            $_SESSION["kullanici"] = $kullanici["kullanici_adi"];
            header("Location: admin/adminpanel.php");
            exit();
        } else {
            $hata = "Hatalı şifre!";
        }
    } else {
        $hata = "Hatalı kullanıcı adı!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="login.php" method="post" class="border p-4 rounded">
                    <h2 class="mb-4">Admin Girişi</h2>
                    <?php if (isset($hata)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $hata; ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="username">Kullanıcı Adı:</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Şifre:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Giriş Yap</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>
