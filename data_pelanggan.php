<?php
include "koneksi.php";

// ambil semua data user role pelanggan
$result = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE role = 'pelanggan'");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Data Pelanggan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    body {
      background: linear-gradient(135deg, #eafaf1, #f6fff9);
      font-family: "Segoe UI", sans-serif;
    }
    h2 {
      font-weight: 600;
      color: #2c3e50;
      margin-bottom: 20px;
    }
    .card-custom {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 4px 14px rgba(0,0,0,0.08);
      padding: 20px;
    }
    thead {
      background: #27ae60 !important;
      color: white;
    }
    tbody tr:hover {
      background: #f0faf4;
    }
    .btn-reset {
      border-radius: 6px;
      padding: 6px 12px;
      font-size: 0.85rem;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background-color: #e74c3c;
      border: none;
      color: white;
      transition: all 0.25s ease;
    }
    .btn-reset:hover {
      background-color: #c0392b;
      box-shadow: 0 2px 6px rgba(231,76,60,0.5);
    }
    .btn-back {
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #27ae60;
      color: white;
      font-size: 1.25rem;
      border: none;
      transition: all 0.25s ease;
      text-decoration: none;
      box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .btn-back:hover {
      background: #219150;
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.25);
      color: white;
    }
  </style>
</head>
<body>
<div class="container my-5">
  <div class="card-custom">
    <div class="d-flex align-items-center mb-3 gap-3">
      <a href="dashboard.php" class="btn-back">
        <i class="bi bi-arrow-left"></i>
      </a>
      <h2 class="mb-0">Data Pelanggan</h2>
    </div>
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Username</th>
            <th>No. HP</th>
            <th>Alamat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php 
          $no = 1; 
          while($row = mysqli_fetch_assoc($result)) { ?>
          <tr>
            <td><?= $no++; ?></td>
            <td class="text-start"><?= $row['nama']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['username']; ?></td>
            <td><?= $row['hp']; ?></td>
            <td class="text-start"><?= $row['alamat']; ?></td>
            <td>
              <button 
                class="btn-reset" 
                onclick="resetPassword(<?= $row['id']; ?>, '<?= $row['nama']; ?>')">
                <i class="bi bi-key-fill"></i> Reset
              </button>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script>
function resetPassword(id, nama) {
  Swal.fire({
    title: 'Konfirmasi Reset Password',
    text: "Password untuk " + nama + " akan direset.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#e74c3c',
    cancelButtonColor: '#27ae60',
    confirmButtonText: 'Ya, Reset',
    cancelButtonText: 'Batal'
  }).then((result) => {
    if (result.isConfirmed) {
      window.location.href = "reset_password.php?id=" + id;
    }
  });
}
</script>
</body>
</html>
