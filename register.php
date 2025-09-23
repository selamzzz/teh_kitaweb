<?php
include 'koneksi.php';

if (isset($_POST['daftar'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $hp = $_POST['hp'];
    $alamat = $_POST['alamat'];

    if ($nama && $email && $username && $password) {
        $daftar = mysqli_query(
            $koneksi,
            "INSERT INTO tb_user(nama,email,username,password,hp,alamat,role)
            VALUES('$nama','$email','$username','$password','$hp','$alamat','pelanggan')"
        );

        if ($daftar) {
            header('Location: login.php');
        }
    } else {
        //alert user harus mengisi semua inputan
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Akun</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #f8f9fa;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .card {
      border: none;
      border-radius: 16px;
      box-shadow: 0px 6px 20px rgba(0,0,0,0.08);
    }
    .card-body {
      padding: 2.5rem;
    }
    h4 {
      font-weight: 700;
      color: #2c3e50;
      margin-bottom: 0.3rem;
      text-align: center;
    }
    .subtitle {
      text-align: center;
      color: #6c757d;
      margin-bottom: 2rem;
      font-size: 0.95rem;
    }
    .form-label {
      font-weight: 500;
      color: #333;
    }
    .input-group-text {
      background: #fff;
      border-right: none;
      border-radius: 8px 0 0 8px;
      color: #6c757d;
    }
    .form-control {
      border-radius: 0 8px 8px 0;
      padding: 0.65rem 1rem;
      border: 1px solid #ccc;
      transition: all 0.2s ease;
    }
    .form-control:focus {
      border-color: #28a745;
      box-shadow: 0 0 0 0.15rem rgba(40,167,69,0.25);
    }
    .btn-success {
      background: #28a745;
      border: none;
      border-radius: 10px;
      padding: 0.8rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }
    .btn-success:hover {
      background: #218838;
      transform: translateY(-1px);
      box-shadow: 0px 5px 14px rgba(40,167,69,0.35);
    }
    .text-muted a {
      color: #28a745;
      font-weight: 500;
      text-decoration: none;
    }
    .text-muted a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-6 col-lg-5">
        <div class="card">
          <div class="card-body">
            <h4>Daftar Akun</h4>
            <p class="subtitle">Silakan isi data Anda dengan benar</p>
            <form action="" method="POST">
              
              <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person"></i></span>
                  <input type="text" name="nama" class="form-control" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Email</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                  <input type="email" name="email" class="form-control" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Username</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                  <input type="text" name="username" class="form-control" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-lock"></i></span>
                  <input type="password" name="password" class="form-control" required>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">No HP</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-phone"></i></span>
                  <input type="text" name="hp" class="form-control">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Alamat</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                  <textarea name="alamat" class="form-control" rows="3"></textarea>
                </div>
              </div>

              <button type="submit" name="daftar" class="btn btn-success w-100">Daftar</button>
            </form>
            <div class="text-center mt-3 text-muted">
              <small>Sudah punya akun? <a href="login.php">Login</a></small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
