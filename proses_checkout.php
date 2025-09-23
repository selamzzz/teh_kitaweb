<?php
session_start();
include "koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['id_user'];
$cart    = $_SESSION['cart'] ?? [];

if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

// hitung total
$total = 0;
foreach ($cart as $item) {
    $total += $item['harga'] * $item['qty'];
}

// simpan transaksi
mysqli_query($koneksi, "INSERT INTO tb_transaksi (tanggal, id_pelanggan, total_harga)
VALUES (NOW(), '$user_id', '$total')") or die(mysqli_error($koneksi));

$id_transaksi = mysqli_insert_id($koneksi);

// simpan detail
foreach ($cart as $item) {
    $id_produk = $item['id'];
    $jumlah    = $item['qty'];
    mysqli_query($koneksi, "INSERT INTO tb_detail (id_transaksi, id_produk, jumlah)
    VALUES ('$id_transaksi', '$id_produk', '$jumlah')") or die(mysqli_error($koneksi));
}

// kosongkan keranjang
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pesanan Berhasil</title>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<script>
Swal.fire({
    icon: 'success',
    title: 'Pesanan Anda Berhasil!',
    text: 'Silahkan tunggu, pesanan anda akan segera sampai ke tujuan.',
    showConfirmButton: false,
    timer: 2000, // 2 detik
    timerProgressBar: true
}).then(() => {
    window.location.href = "invoice_checkout.php?id_transaksi=<?= $id_transaksi ?>";
});
</script>
</body>
</html>
