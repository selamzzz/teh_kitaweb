<?php 
session_start();
include 'koneksi.php';  

// Pastikan admin login
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// Validasi ID transaksi
if (!isset($_GET['id'])) {
    header('Location: riwayat_transaksi_admin.php');
    exit();
}

$id = intval($_GET['id']);

// =========================
// AMBIL DATA TRANSAKSI + USER
// =========================
$stmt = $koneksi->prepare("
    SELECT t.id_transaksi, t.tanggal, t.total_harga,
           u.nama, u.email, u.username, u.hp, u.alamat
    FROM tb_transaksi t
    JOIN tb_user u ON t.id_pelanggan = u.id
    WHERE t.id_transaksi = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$transaksi = $stmt->get_result()->fetch_assoc();

if (!$transaksi) {
    die("Transaksi tidak ditemukan");
}

// =========================
// AMBIL DETAIL PRODUK
// =========================
$stmtDetail = $koneksi->prepare("
    SELECT d.jumlah, p.nama, p.harga
    FROM tb_detail d
    JOIN tb_produk p ON d.id_produk = p.id
    WHERE d.id_transaksi = ?
");
$stmtDetail->bind_param("i", $id);
$stmtDetail->execute();
$detail = $stmtDetail->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Detail Transaksi #<?= $id ?> - Admin</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>
</head>
<body class="bg-gradient-to-br from-gray-50 to-green-50 min-h-screen flex justify-center items-start p-6">

<div class="w-full max-w-4xl bg-white shadow-lg rounded-2xl p-8">
    <!-- Header -->
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div class="flex items-center gap-3">
            <i class="fa fa-file-invoice text-green-600 text-2xl"></i>
            <h1 class="text-xl font-bold text-green-700">Detail Transaksi</h1>
        </div>
        <span class="text-sm text-gray-500"><?= date("d M Y", strtotime($transaksi['tanggal'])) ?></span>
    </div>

    <!-- Info Pelanggan -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
        <div class="bg-green-50 p-4 rounded-xl border border-green-200">
            <h2 class="text-green-700 font-semibold mb-2">Data Pelanggan</h2>
            <p><b>Nama:</b> <?= htmlspecialchars($transaksi['nama']) ?></p>
            <p><b>Email:</b> <?= htmlspecialchars($transaksi['email']) ?></p>
            <p><b>No. HP:</b> <?= htmlspecialchars($transaksi['hp']) ?></p>
            <p><b>Alamat:</b> <?= htmlspecialchars($transaksi['alamat']) ?></p>
        </div>
        <div class="bg-green-50 p-4 rounded-xl border border-green-200">
            <h2 class="text-green-700 font-semibold mb-2">Ringkasan</h2>
            <p><b>ID Transaksi:</b> <?= $transaksi['id_transaksi'] ?></p>
            <p><b>Tanggal:</b> <?= date("d M Y H:i", strtotime($transaksi['tanggal'])) ?></p>
            <p><b>Total Bayar:</b> 
                <span class="text-green-700 font-bold">Rp<?= number_format($transaksi['total_harga'],0,',','.') ?></span>
            </p>
        </div>
    </div>

    <!-- Produk -->
    <div class="overflow-x-auto">
        <table class="w-full border-collapse text-sm">
            <thead>
                <tr class="bg-green-100 text-green-700">
                    <th class="py-3 px-4 text-left rounded-tl-lg">Produk</th>
                    <th class="py-3 px-4 text-center">Jumlah</th>
                    <th class="py-3 px-4 text-center">Harga Satuan</th>
                    <th class="py-3 px-4 text-right rounded-tr-lg">Subtotal</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                <?php while($d = $detail->fetch_assoc()): ?>
                <tr class="hover:bg-green-50 transition">
                    <td class="py-3 px-4"><?= htmlspecialchars($d['nama']) ?></td>
                    <td class="py-3 px-4 text-center"><?= $d['jumlah'] ?></td>
                    <td class="py-3 px-4 text-center">Rp<?= number_format($d['harga'],0,',','.') ?></td>
                    <td class="py-3 px-4 text-right font-semibold">Rp<?= number_format($d['jumlah'] * $d['harga'],0,',','.') ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Tombol -->
    <div class="flex justify-end gap-3 mt-6">
        <a href="riwayat_admin.php" 
           class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <i class="fa fa-arrow-left"></i> Kembali
        </a>
        <a href="invoice2.php?id_transaksi=<?= $id ?>" target="_blank" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium flex items-center gap-2">
            <i class="fa fa-print"></i> Cetak Invoice
        </a>
    </div>
</div>

</body>
</html>

