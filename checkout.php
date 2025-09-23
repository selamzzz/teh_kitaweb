<?php
session_start();
include "koneksi.php"; // koneksi database

// ============================
// CEK LOGIN
// ============================
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// ambil id user dari session
$user_id = $_SESSION['id_user'] ?? 0;

// ============================
// AMBIL DATA USER
// ============================
$user = null;
$query = "SELECT nama, alamat FROM tb_user WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($koneksi, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $user = mysqli_fetch_assoc($result);
} else {
    // Jika user tidak ditemukan, keluar
    die("⚠️ User tidak ditemukan di database.");
}

// ============================
// AMBIL CART
// ============================
$cart_items = $_SESSION['cart'] ?? [];

if (empty($cart_items)) {
    // Jika keranjang kosong, redirect
    header("Location: cart.php");
    exit;
}

// ============================
// HITUNG TOTAL & SUBTOTAL
// ============================
$total = 0;
foreach ($cart_items as $id => &$item) {
    $harga = intval($item['harga'] ?? 0);
    $qty   = intval($item['qty'] ?? 1);
    $item['subtotal'] = $harga * $qty;
    $total += $item['subtotal'];
}
unset($item); // amanin reference

// Simpan balik ke session (opsional)
$_SESSION['cart'] = $cart_items;
?>



<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      body {
        font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f8f9fa;
      }
      .gradient-bg {
        background: linear-gradient(135deg, #00b14f 0%, #00a14f 100%);
      }
      .restaurant-card {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 12px;
      }
      .food-item {
        transition: transform 0.2s;
      }
      .food-item:hover {
        transform: translateY(-2px);
      }
      .payment-method {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        transition: all 0.2s;
      }
      .payment-method.active {
        border-color: #00b14f;
        background-color: #f0fff4;
      }
      .promo-badge {
        background: linear-gradient(135deg, #ff6b35 0%, #ff8c42 100%);
      }
      .delivery-time {
        background-color: #f0fff4;
        border: 1px solid #00b14f;
      }
    </style>
</head>
<body class="bg-gray-100">



  <!-- Header -->
  <header class="gradient-bg text-white p-4 sticky top-0 z-50">
    <div class="container mx-auto">
      <div class="flex items-center justify-between">
        <div class="flex items-center">
          <i class="fas fa-arrow-left text-xl mr-4"></i>
          <h1 class="text-xl font-bold">Checkout</h1>
        </div>
        <div class="flex items-center">
        <a href="profil2.php">
            <i class="fas fa-user text-xl mr-4 cursor-pointer"></i>
        </a>
        <i class="fas fa-bell text-xl"></i>
        </div>
      </div>
    </div>
  </header>

  <main class="container mx-auto px-4 py-6">
    <!-- Restaurant Info -->
    <div class="bg-white restaurant-card p-4 mb-4">
      <div class="flex items-center mb-3">
        <div class="flex justify-center mb-6">
                <img src="img/logo.jpg" alt="Logo TEH KITA" class="h-20">
            </div>
        <div>
          <h2 class="font-bold text-lg">Teh Kita Perumnas</h2>
          <p class="text-gray-600 text-sm">Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat </p>
          <div class="flex items-center mt-1">
            <span class="text-yellow-400">★</span>
            <span class="text-sm text-gray-600 ml-1">4.8 (2.1k ratings)</span>
          </div>
        </div>
      </div>
    </div>

  <!-- Delivery Address -->
<div class="bg-white restaurant-card p-4 mb-4">
  <h3 class="font-bold text-lg mb-3 flex items-center">
    <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
    Alamat Pengantaran
  </h3>
  <div class="flex items-center justify-between">
    <div class="flex-1">
      <p class="font-semibold">Rumah</p>
      <p class="text-gray-600 text-sm">
        <?php 
            // Tampilkan alamat jika ada, kalau tidak tampil teks default
            echo htmlspecialchars($user['alamat'] ?? "Alamat belum tersedia"); 
        ?>
      </p>
    </div>
    <a href="edit_alamat.php" class="text-green-600 font-semibold">
      <i class="fas fa-edit"></i>
    </a>
  </div>
</div>



<!-- Order Items -->
<div class="bg-white restaurant-card p-4 mb-4">
  <h3 class="font-bold text-lg mb-3">Pesanan Anda</h3>

  <?php if (!empty($cart_items)): ?>
    <?php foreach ($cart_items as $item): ?>
      <div class="food-item flex items-center justify-between py-3 border-b">
        <div class="flex items-center">
          <div class="w-16 h-16 bg-gray-200 rounded-lg mr-3 relative">
            <img
              src="img/<?= htmlspecialchars($item['foto'] ?? 'default.png') ?>"
              alt="<?= htmlspecialchars($item['nama'] ?? '') ?>"
              class="w-full h-full object-cover rounded-lg"
              onerror="this.style.display='none'" />
            <div class="absolute -top-1 -right-1 w-6 h-6 bg-green-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
              <?= $item['qty'] ?>
            </div>
          </div>
          <div>
            <p class="font-semibold"><?= htmlspecialchars($item['nama'] ?? '-') ?></p>
            <p class="text-green-600 font-semibold">
              Rp <?= number_format($item['harga'], 0, ',', '.') ?>
            </p>
          </div>
        </div>
        <p class="font-semibold">
          Rp <?= number_format($item['subtotal'], 0, ',', '.') ?>
        </p>
      </div>
    <?php endforeach; ?>

    <div class="flex justify-between font-bold text-lg mt-4">
      <span>Total</span>
      <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
    </div>

  <?php else: ?>
    <p class="text-gray-500">Keranjang masih kosong.</p>
  <?php endif; ?>
</div>      
    <!-- Payment Method -->
    <div class="bg-white restaurant-card p-4 mb-4">
      <h3 class="font-bold text-lg mb-3">Metode Pembayaran</h3>
        <!-- Cash -->
        <div class="payment-method p-3">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div
                class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3"
              >
                <i class="fas fa-money-bill-wave text-green-600"></i>
              </div>
              <div>
                <p class="font-semibold">Tunai</p>
                <p class="text-gray-600 text-sm">Bayar saat diterima</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

   <!-- Ringkasan Pembayaran -->
<div class="bg-white restaurant-card p-4 mb-20">
  <h3 class="font-bold text-lg mb-3">Ringkasan Pembayaran</h3>

  <div class="space-y-2">
    <div class="flex justify-between">
      <span class="text-gray-600">Subtotal</span>
      <span>
        Rp <?= number_format($total, 0, ',', '.') ?>
      </span>
    </div>
  </div>
</div>

<!-- Fixed Bottom Button -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t p-4">
  <div class="container mx-auto">
    <div class="flex justify-between items-center">
      <div>
        <p class="text-gray-600 text-sm">Total Pembayaran</p>
        <p class="font-bold text-xl text-green-600">
          Rp <?= number_format($total, 0, ',', '.') ?>
        </p>
      </div>
      <button 
        onclick="window.location.href='proses_checkout.php'" 
        class="gradient-bg text-white px-8 py-3 rounded-lg font-bold text-lg hover:bg-green-600 transition duration-200">
        Pesan Sekarang
      </button>
    </div>
  </div>
</div>
<script>
Swal.fire({
    toast: true,
    position: "top-end",
    icon: "success",
    title: "Pesanan berhasil dibuat!",
    text: "Driver segera menuju restoran",
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true
}).then(() => {
    window.location.href = "invoice_checkout.php?id=<?= $id_transaksi ?>";
});
</script>


</body>
</html>
