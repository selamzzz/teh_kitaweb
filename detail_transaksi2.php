<?php
session_start();
include "koneksi.php";

// Pastikan ada id transaksi
if (!isset($_GET['id'])) {
    header("Location: riwayat_transaksi_user.php");
    exit;
}

$id_transaksi = $_GET['id'];

// Ambil data transaksi + user
$sql_transaksi = "
    SELECT t.id_transaksi, t.tanggal, t.total_harga, u.nama 
    FROM tb_transaksi t
    JOIN tb_user u ON t.id_pelanggan = u.id
    WHERE t.id_transaksi = '$id_transaksi'
";
$result_transaksi = mysqli_query($koneksi, $sql_transaksi);
$transaksi = mysqli_fetch_assoc($result_transaksi);

// Ambil detail produk
$sql_detail = "
    SELECT d.jumlah, p.nama, p.harga, p.foto
    FROM tb_detail d
    JOIN tb_produk p ON d.id_produk = p.id
    WHERE d.id_transaksi = '$id_transaksi'
";
$result_detail = mysqli_query($koneksi, $sql_detail);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Transaksi</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
</head>
<body class="bg-gradient-to-br from-gray-50 to-green-50 min-h-screen flex justify-center items-start p-6">

<div class="w-full max-w-5xl bg-white shadow-lg rounded-2xl p-8">
    <!-- Header -->
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div class="flex items-center gap-3">
            <i class="fa fa-receipt text-green-600 text-2xl"></i>
            <h1 class="text-xl font-bold text-green-700">Detail Transaksi</h1>
        </div>
        <?php if ($transaksi): ?>
        <span class="text-sm text-gray-500"><?= date("d M Y", strtotime($transaksi['tanggal'])) ?></span>
        <?php endif; ?>
    </div>

    <?php if ($transaksi): ?>
    <!-- Info Transaksi -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-green-50 p-4 rounded-xl border border-green-200">
            <h2 class="text-green-700 font-semibold mb-2">Informasi Transaksi</h2>
            <p><b>No. Transaksi:</b> <?= $transaksi['id_transaksi'] ?></p>
            <p><b>Tanggal:</b> <?= date("d M Y H:i", strtotime($transaksi['tanggal'])) ?></p>
            <p><b>Pelanggan:</b> <?= htmlspecialchars($transaksi['nama']) ?></p>
            <p><b>Total Bayar:</b> 
                <span class="text-green-700 font-bold">Rp<?= number_format($transaksi['total_harga'],0,',','.') ?></span>
            </p>
        </div>
    </div>

    <!-- Produk -->
    <?php if (mysqli_num_rows($result_detail) > 0): ?>
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-green-100 text-green-700">
                    <th class="py-3 px-4 text-left rounded-tl-lg">No</th>
                    <th class="py-3 px-4 text-left">Produk</th>
                    <th class="py-3 px-4 text-center">Foto</th>
                    <th class="py-3 px-4 text-center">Harga</th>
                    <th class="py-3 px-4 text-center">Jumlah</th>
                    <th class="py-3 px-4 text-right rounded-tr-lg">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($result_detail)): ?>
                <tr class="hover:bg-green-50 transition">
                    <td class="py-3 px-4"><?= $no++ ?></td>
                    <td class="py-3 px-4"><?= htmlspecialchars($row['nama']) ?></td>
                    <td class="py-3 px-4 text-center">
                        <?php if (!empty($row['foto'])): ?>
                            <img src="img/<?= $row['foto'] ?>" class="w-16 h-16 object-cover rounded-lg border" alt="<?= $row['nama'] ?>">
                        <?php else: ?>
                            <span class="text-gray-400">-</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-4 text-center">Rp<?= number_format($row['harga'],0,',','.') ?></td>
                    <td class="py-3 px-4 text-center"><?= $row['jumlah'] ?></td>
                    <td class="py-3 px-4 text-right font-semibold">Rp<?= number_format($row['harga'] * $row['jumlah'],0,',','.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="text-center text-red-600 bg-red-50 border border-red-200 rounded-lg p-4 mt-4">
            Tidak ada detail produk untuk transaksi ini.
        </div>
    <?php endif; ?>

    <!-- Tombol -->
    <div class="flex justify-end gap-3 mt-6">
        <a href="riwayat_transaksi_user.php" 
           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <a href="invoice3.php?id=<?= $transaksi['id_transaksi'] ?>" target="_blank" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <i class="fa fa-print"></i> Cetak Invoice
        </a>
    </div>

    <?php else: ?>
    <div class="text-center text-red-600 bg-red-50 border border-red-200 rounded-lg p-4">
        Transaksi tidak ditemukan.
    </div>
    <?php endif; ?>
</div>

</body>
</html>
