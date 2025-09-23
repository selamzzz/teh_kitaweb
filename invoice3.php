<?php
session_start();
include "koneksi.php";

if (!isset($_GET['id'])) {
    echo "Transaksi tidak ditemukan.";
    exit;
}

$id_transaksi = intval($_GET['id']); // amanin input

// ambil data transaksi
$transaksiQ = mysqli_query($koneksi, "SELECT t.*, u.nama 
    FROM tb_transaksi t 
    JOIN tb_user u ON t.id_pelanggan = u.id 
    WHERE t.id_transaksi='$id_transaksi'") or die(mysqli_error($koneksi));
$transaksi = mysqli_fetch_assoc($transaksiQ);

if (!$transaksi) {
    echo "Transaksi tidak ditemukan di database.";
    exit;
}

// ambil detail transaksi
$detail = mysqli_query($koneksi, "SELECT d.*, p.nama, p.harga 
    FROM tb_detail d 
    JOIN tb_produk p ON d.id_produk = p.id 
    WHERE d.id_transaksi='$id_transaksi'") or die(mysqli_error($koneksi));
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice Riwayat Saya- TEH KITA</title>
  <style>
    body {
      font-family: "Segoe UI", Tahoma, sans-serif;
      background: #f5f6fa;
      color: #333;
      margin: 0;
      padding: 20px;
    }
    .invoice-container {
      background: #fff;
      max-width: 800px;
      margin: auto;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.1);
    }
    /* Header */
    .header {
      display: flex;
      align-items: center;
      gap: 20px;
      border-bottom: 3px solid #2ecc71;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }
    .header img {
      height: 80px;
    }
    .header h1 {
      margin: 0;
      font-size: 1.8rem;
      color: #27ae60;
    }
    .header p {
      margin: 3px 0 0 0;
      font-size: 0.9rem;
      color: #555;
    }
    /* Info Pembeli */
    .info {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
    }
    .info h2 {
      font-size: 1rem;
      color: #555;
      margin: 0 0 5px 0;
    }
    .info p {
      margin: 0;
      font-weight: bold;
      color: #27ae60;
    }
    /* Tabel Produk */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 25px;
      background: #fafafa;
      border-radius: 8px;
      overflow: hidden;
    }
    table th, table td {
      padding: 12px;
      border-bottom: 1px solid #eee;
      font-size: 0.95rem;
    }
    table th {
      background: #27ae60;
      color: #fff;
      text-align: center;
    }
    table td.qty {
      text-align: center;
    }
    table td.price,
    table td.total {
      text-align: center;
    }
    /* Ringkasan */
    .summary {
      border-top: 2px solid #eee;
      padding-top: 15px;
      margin-bottom: 20px;
    }
    .summary-row {
      display: flex;
      justify-content: space-between;
      font-size: 1rem;
      margin: 5px 0;
    }
    .summary-row.total {
      font-weight: bold;
      font-size: 1.2rem;
      color: #27ae60;
    }
    /* Tombol */
    .buttons {
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      margin-top: 20px;
    }
    .buttons button {
      background: #2ecc71;
      color: #fff;
      border: none;
      padding: 10px 18px;
      border-radius: 6px;
      cursor: pointer;
      transition: 0.3s;
      font-weight: 500;
    }
    .buttons button:hover {
      background: #27ae60;
    }
    .buttons .back {
      background: #bdc3c7;
      color: #333;
    }
    .buttons .back:hover {
      background: #95a5a6;
    }
    /* Footer */
    footer {
      text-align: center;
      margin-top: 30px;
      font-size: 0.9rem;
      color: #777;
    }
  </style>
</head>
<body>
  <main class="invoice-container" role="main" aria-label="Invoice Teh Kita">
    
    <!-- Header -->
    <header class="header">
      <img src="img/logo.jpg" alt="Logo TEH KITA" />
      <div>
        <h1>TEH KITA PERUMNAS</h1>
        <p>Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat</p>
      </div>
    </header>

    <!-- Informasi Pembeli -->
    <section class="info" aria-label="Informasi pembeli dan tanggal">
      <div>
        <h2>Nama Pembeli</h2>
        <p><?= htmlspecialchars($transaksi['nama']) ?></p>
      </div>
      <div>
        <h2>Tanggal</h2>
        <p><?= htmlspecialchars($transaksi['tanggal']) ?></p>
      </div>
    </section>

    <!-- Daftar Item -->
    <table aria-label="Daftar item pembelian">
      <thead>
        <tr>
          <th>Nama Produk</th>
          <th class="qty">Jumlah</th>
          <th class="price">Harga</th>
          <th class="total">Total</th>
        </tr>
      </thead>
      <tbody>
        <?php if (mysqli_num_rows($detail) > 0): ?>
          <?php while ($item = mysqli_fetch_assoc($detail)): ?>
            <?php $subtotal = $item['harga'] * $item['jumlah']; ?>
            <tr>
              <td><?= htmlspecialchars($item['nama']) ?></td>
              <td class="qty"><?= $item['jumlah'] ?></td>
              <td class="price">Rp <?= number_format($item['harga'], 0, ',', '.') ?></td>
              <td class="total">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4">Tidak ada item pada invoice.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>

    <!-- Ringkasan -->
    <section class="summary" aria-label="Ringkasan pembayaran">
      <div class="summary-row">
        <span>Subtotal</span>
        <span>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span>
      </div>
      <div class="summary-row total">
        <span>Total</span>
        <span>Rp <?= number_format($transaksi['total_harga'], 0, ',', '.') ?></span>
      </div>
    </section>

    <!-- Tombol -->
    <section class="buttons" role="group" aria-label="Tombol aksi">
      <button type="button" onclick="window.print()" aria-label="Cetak invoice">
        Cetak Invoice
      </button>
    </section>

    <!-- Footer -->
    <footer>
      Terima kasih sudah membeli produk kami! 🌿 Selamat menikmati.
    </footer>

  </main>
</body>
</html>
