<?php
include "koneksi.php";

// fitur search
$search = $_GET['q'] ?? '';
$search = trim($search);

$sql = "
    SELECT t.id_transaksi, t.tanggal, t.total_harga, 
           u.nama, u.email, u.username, u.hp, u.alamat
    FROM tb_transaksi t
    JOIN tb_user u ON t.id_pelanggan = u.id
";

if ($search !== '') {
    $sql .= " WHERE t.id_transaksi LIKE '%$search%'
              OR u.nama LIKE '%$search%'
              OR u.email LIKE '%$search%'
              OR u.username LIKE '%$search%'
              OR u.hp LIKE '%$search%'
              OR u.alamat LIKE '%$search%'";
}

// urutkan transaksi dari paling lama ke paling baru
$sql .= " ORDER BY t.tanggal ASC";

$result = mysqli_query($koneksi, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Transaksi</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #f9fcf9, #eafaf0);
      font-family: "Segoe UI", sans-serif;
    }
    .card {
      border-radius: 18px;
      overflow: hidden;
    }
    .card-header {
      background: linear-gradient(135deg, #198754, #28a745);
      color: white;
      font-weight: 600;
      font-size: 1.1rem;
    }
    .table thead th {
      background-color: #f6fff6;
      color: #198754;
      font-weight: 600;
      text-transform: uppercase;
      font-size: 13px;
      border-bottom: 2px solid #d4edda;
    }
    .table-striped tbody tr:nth-of-type(odd) {
      background-color: #fdfdfd;
    }
    .table-striped tbody tr:hover {
      background-color: #f1f9f1;
      transition: 0.2s;
    }
    .badge-price {
      background: #d1e7dd;
      color: #0f5132;
      font-weight: 600;
      font-size: 0.85rem;
    }
    .action-btn {
      border: none;
      padding: 6px 9px;
      border-radius: 8px;
    }
    .action-btn.view {
      background-color: #198754;
      color: white;
    }
    .action-btn.view:hover {
      background-color: #146c43;
    }
    .action-btn.delete {
      background-color: #dc3545;
      color: white;
    }
    .action-btn.delete:hover {
      background-color: #bb2d3b;
    }
    /* tombol kembali */
    .btn-back {
      position: fixed;
      top: 20px;
      left: 20px;
      background: #198754;
      color: #fff;
      font-size: 1.3rem;
      width: 45px;
      height: 45px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      transition: all 0.3s ease;
      text-decoration: none;
      z-index: 999;
    }
    .btn-back:hover {
      background: #146c43;
      transform: scale(1.1);
    }
  </style>
</head>
<body>

<!-- Tombol kembali -->
<a href="dashboard.php" class="btn-back" title="Kembali ke Dashboard">
  <i class="bi bi-arrow-left"></i>
</a>

<div class="container mt-5">
  <div class="card shadow-lg">
    <div class="card-header d-flex justify-content-between align-items-center">
      <span><i class="bi bi-clock-history me-2"></i> Riwayat Transaksi</span>
      <form class="d-flex" role="search" method="get">
        <input class="form-control form-control-sm me-2" 
               type="search" 
               placeholder="Cari transaksi..." 
               name="q" 
               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
        <button class="btn btn-light btn-sm" type="submit">
          <i class="bi bi-search"></i>
        </button>
      </form>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
          <thead class="text-center">
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Total Harga</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Username</th>
              <th>HP</th>
              <th>Alamat</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php 
              $no = 1; 
              while ($row = mysqli_fetch_assoc($result)) : 
            ?>
              <tr>
                <td class="text-center fw-semibold"><?= $no++ ?></td>
                <td><?= date("d M Y H:i", strtotime($row['tanggal'])) ?></td>
                <td><span class="badge badge-price">Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></span></td>
                <td><?= htmlspecialchars($row['nama']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['hp']) ?></td>
                <td><?= htmlspecialchars($row['alamat']) ?></td>
                <td class="text-center">
                  <a href="detail_transaksi_admin.php?id=<?= $row['id_transaksi'] ?>" 
                     class="action-btn view me-1" title="Lihat Detail">
                     <i class="bi bi-eye"></i>
                  </a>
                  <a href="hapus_transaksi.php?id=<?= $row['id_transaksi'] ?>" 
                     class="action-btn delete" 
                     onclick="return confirm('Yakin hapus transaksi ini?')" 
                     title="Hapus">
                     <i class="bi bi-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

</body>
</html>
