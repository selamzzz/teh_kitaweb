<?php
session_start();
include "koneksi.php";

// pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}

$id_pelanggan = $_SESSION['id_user'];

// ambil transaksi hanya untuk pelanggan yang login
$sql = "
    SELECT id_transaksi, tanggal, total_harga
    FROM tb_transaksi
    WHERE id_pelanggan = '$id_pelanggan'
    ORDER BY tanggal DESC
";
$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Transaksi Saya</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f8fff9, #edfdf3);
      font-family: "Segoe UI", sans-serif;
      animation: fadeIn 0.6s ease-in-out;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .card {
      border-radius: 20px;
      overflow: hidden;
      border: none;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
    }
    .card-header {
      background: linear-gradient(135deg, #16a34a, #22c55e);
      color: white;
      font-weight: 600;
      font-size: 1.2rem;
      padding: 18px 22px;
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .table thead th {
      background-color: #f0fff4;
      color: #15803d;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 13px;
      border-bottom: 2px solid #bbf7d0;
    }
    .table-striped tbody tr:hover {
      background-color: #e8fdf1;
      transition: 0.25s ease;
    }
    .badge-no {
      background: #16a34a;
      color: #fff;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 0.85rem;
      font-weight: 600;
    }
    .badge-price {
      background: #16a34a;
      color: #fff;
      font-weight: 600;
      font-size: 0.9rem;
      border-radius: 12px;
      padding: 6px 14px;
      box-shadow: 0 2px 6px rgba(22,163,74,0.25);
    }
    .action-btn {
      border: none;
      padding: 6px 14px;
      border-radius: 10px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 0.85rem;
      text-decoration: none;
      font-weight: 500;
      transition: all 0.25s ease;
    }
    .action-btn.view {
      background-color: #16a34a;
      color: white;
    }
    .action-btn.view:hover {
      background-color: #15803d;
      transform: scale(1.05);
      box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }
    /* tombol kembali */
    .btn-back {
      position: fixed;
      top: 20px;
      left: 20px;
      background: #16a34a;
      color: #fff;
      font-size: 1.3rem;
      width: 48px;
      height: 48px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      transition: all 0.3s ease;
      text-decoration: none;
      z-index: 999;
    }
    .btn-back:hover {
      background: #15803d;
      transform: scale(1.15);
      box-shadow: 0 6px 14px rgba(0,0,0,0.3);
    }
    /* Responsive mode: di HP jadi card */
    @media (max-width: 768px) {
      .table {
        display: none;
      }
      .transaction-card {
        background: white;
        border-radius: 16px;
        padding: 16px;
        margin-bottom: 14px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        animation: fadeIn 0.4s ease-in-out;
      }
      .transaction-card .header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-weight: 600;
        color: #15803d;
      }
      .transaction-card .price {
        font-size: 1rem;
        font-weight: 700;
        color: #16a34a;
        margin-bottom: 10px;
      }
      .transaction-card .btn-detail {
        background: #16a34a;
        color: white;
        padding: 6px 14px;
        border-radius: 8px;
        text-decoration: none;
        font-size: 0.85rem;
        transition: 0.2s;
      }
      .transaction-card .btn-detail:hover {
        background: #15803d;
      }
    }
  </style>
</head>
<body>

<!-- Tombol kembali -->
<a href="profil2.php" class="btn-back" title="Kembali ke Profil">
  <i class="bi bi-arrow-left"></i>
</a>

<div class="container mt-5">
  <div class="card">
    <div class="card-header">
      <i class="bi bi-clock-history"></i> Riwayat Transaksi Saya
    </div>
    <div class="card-body">
      <?php if (mysqli_num_rows($result) > 0): ?>
        
        <!-- Tampilan tabel untuk desktop -->
        <div class="table-responsive d-none d-md-block">
          <table class="table table-striped table-hover align-middle text-center">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php $no=1; while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                  <td><span class="badge-no"><?= $no++ ?></span></td>
                  <td><?= date("d M Y H:i", strtotime($row['tanggal'])) ?></td>
                  <td><span class="badge-price">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></span></td>
                  <td>
                    <a href="detail_transaksi2.php?id=<?= $row['id_transaksi'] ?>" 
                       class="action-btn view" title="Lihat Detail">
                       <i class="bi bi-eye"></i> Detail
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>

        <!-- Tampilan card untuk mobile -->
        <div class="d-block d-md-none">
          <?php mysqli_data_seek($result, 0); // ulangi loop untuk mobile ?>
          <?php $no=1; while ($row = mysqli_fetch_assoc($result)): ?>
            <div class="transaction-card">
              <div class="header">
                <span>Transaksi #<?= $no++ ?></span>
                <span><?= date("d M Y H:i", strtotime($row['tanggal'])) ?></span>
              </div>
              <div class="price">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></div>
              <a href="detail_transaksi.php?id=<?= $row['id_transaksi'] ?>" class="btn-detail">
                <i class="bi bi-eye"></i> Lihat Detail
              </a>
            </div>
          <?php endwhile; ?>
        </div>

      <?php else: ?>
        <div class="alert alert-info text-center">
          <i class="bi bi-info-circle me-2"></i> Belum ada transaksi.
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
</html>
