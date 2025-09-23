<?php
session_start();
include 'koneksi.php';

// Inisialisasi keranjang
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
// Ambil action dan ID dari GET
$action = $_GET['action'] ?? null;
$id     = intval($_GET['id'] ?? 0);
// Tambah produk ke keranjang
if ($action === "add" && $id > 0) {
    $q = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id='$id' LIMIT 1");
    if ($q && mysqli_num_rows($q) > 0) {
        $p = mysqli_fetch_assoc($q);

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty']++;   // tambah qty jika sudah ada
        } else {
            $_SESSION['cart'][$id] = [
                "id"    => $p['id'],
                "nama"  => $p['nama'],
                "harga" => $p['harga'],
                "foto"  => $p['foto'],
                "qty"   => 1
            ];
        }
    }
    header("Location: cart.php");
    exit;
}
// Hapus produk dari keranjang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $hapus_id = intval($_POST['hapus']);
    if (isset($_SESSION['cart'][$hapus_id])) {
        unset($_SESSION['cart'][$hapus_id]);
    }
    header("Location: cart.php");
    exit;
}
// Update jumlah produk di keranjang
if ($action === "update" && !empty($_POST['qty']) && is_array($_POST['qty'])) {
    foreach ($_POST['qty'] as $id_produk => $jml) {
        $id_produk = intval($id_produk);
        $jml = max(1, intval($jml));
        if (isset($_SESSION['cart'][$id_produk])) {
            $_SESSION['cart'][$id_produk]['qty'] = $jml;
        }
    }
    header("Location: cart.php");
    exit;
}
// Pastikan semua item valid (harga, qty)
foreach ($_SESSION['cart'] as $k => $item) {
    if (!isset($_SESSION['cart'][$k]['harga'])) {
        $_SESSION['cart'][$k]['harga'] = 0;
    }
    if (!isset($_SESSION['cart'][$k]['qty']) || $_SESSION['cart'][$k]['qty'] < 1) {
        $_SESSION['cart'][$k]['qty'] = 1;
    }
}
// Fungsi hitung total belanja
function total_belanja($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $harga = $item['harga'] ?? 0;
        $qty   = $item['qty'] ?? 1;
        $total += $harga * $qty;
    }
    return $total;
}
// Ambil data untuk ditampilkan di view
$cart = $_SESSION['cart'];
$subtotal = total_belanja($cart);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tehkita - Keranjang Belanja</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <style>
    :root {
      --primary-green: #4CAF00;
      --primary-orange: #ff5722;
    }
    .logo-teh {
      font-weight: 700;
      color: var(--primary-green);
      font-size: 1.5rem;
      user-select: none;
    }
    .logo-kita {
      font-weight: 700;
      color: var(--primary-orange);
      font-size: 1.5rem;
      user-select: none;
    }
    .cart-badge {
      position: absolute;
      top: -6px;
      right: -6px;
      background-color: var(--primary-green);
      color: white;
      font-size: 0.65rem;
      font-weight: 700;
      width: 18px;
      height: 18px;
      border-radius: 9999px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .cart-table thead tr th {
      background-color: var(--primary-green);
      color: white;
      font-weight: 700;
      padding: 0.75rem 1rem;
      text-align: center;
      font-size: 0.9rem;
    }
    .cart-table tbody tr td {
      padding: 0.75rem 1rem;
      text-align: center;
      vertical-align: middle;
      font-size: 0.9rem;
    }
    .cart-empty {
      color: #777;
      font-style: italic;
      text-align: center;
      padding: 1rem;
    }
    .summary-box {
      border: 1px solid #eee;
      box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
      padding: 1.5rem;
      border-radius: 0.25rem;
      background: white;
      font-size: 0.95rem;
      width: 280px;
    }
    .summary-box strong {
      font-weight: 700;
    }
    .summary-box .total-sum {
      color: var(--primary-green);
      font-weight: 700;
      font-size: 1.1rem;
      margin-top: 0.25rem;
    }
    .btn-update {
      color: var(--primary-green);
      border: 1px solid var(--primary-green);
      padding: 0.4rem 0.75rem;
      border-radius: 0.25rem;
      font-size: 0.95rem;
      font-weight: 600;
      width: 100%;
      background: transparent;
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.3rem;
      margin-bottom: 0.75rem;
      transition: background-color 0.3s, color 0.3s;
    }
    .btn-update:hover {
      background-color: var(--primary-green);
      color: white;
    }
    .btn-checkout {
      background-color: var(--primary-green);
      color: white;
      font-weight: 700;
      padding: 0.55rem 0.75rem;
      border-radius: 0.25rem;
      font-size: 1rem;
      width: 100%;
      cursor: pointer;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 0.4rem;
      border: none;
      transition: background-color 0.3s;
    }
    .btn-checkout:hover {
      background-color: #3e8e00;
    }
    .btn-remove {
      background-color: transparent;
      border: none;
      color: var(--primary-green);
      cursor: pointer;
      font-weight: 600;
      font-size: 0.9rem;
    }
    .btn-remove:hover {
      text-decoration: underline;
    }
    input.qty-input {
      width: 50px;
      text-align: center;
      border: 1px solid #ccc;
      border-radius: 0.25rem;
      font-size: 0.9rem;
      padding: 0.2rem 0.3rem;
    }
    .cart-table-container {
      overflow-x: auto;
      width: 100%; /* Pastikan kontainer mengambil lebar penuh */
    }
    .cart-table {
      width: 100%; /* Buat tabel mengambil lebar penuh */
      table-layout: fixed; /* Pastikan layout tetap untuk lebar sel yang konsisten */
    }
    .cart-table th, .cart-table td {
      word-wrap: break-word; /* Izinkan teks membungkus dalam sel */
    }
    nav {
      background: white;
      box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
      padding: 0.5rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      position: sticky;
      top: 0;
      z-index: 50;
    }
    nav ul {
      display: flex;
      gap: 1.8rem;
      font-weight: 500;
      font-size: 0.95rem;
      color: #555;
    }
    nav ul li a {
      transition: color 0.3s;
    }
    nav ul li a:hover,
    nav ul li a.active {
      color: var(--primary-green);
      font-weight: 700;
    }
    .cart-icon-wrapper {
      position: relative;
      cursor: pointer;
      font-size: 1.4rem;
      color: #444;
      display: flex;
      align-items: center;
    }
    .cart-icon-wrapper:hover {
      color: var(--primary-green);
    }
    .container {
      max-width: 1024px;
      margin: 1.5rem auto 3rem auto;
      padding: 0 1rem;
      min-height: 70vh;
      display: flex;
      flex-wrap: wrap;
      gap: 1.5rem;
    }
    .cart-left {
      flex: 1 1 60%;
      background: white;
      border-radius: 0.3rem;
      box-shadow: 0 1px 6px rgb(0 0 0 / 0.05);
      padding: 1rem 1.5rem 1.5rem;
    }
    .cart-right {
      flex: 1 1 35%;
    }
    .logo-with-cart {
      display: flex;
      align-items: center;
      gap: 0.3rem;
      font-size: 1.75rem;
      font-weight: 700;
      margin-bottom: 1rem;
      user-select: none;
    }
    .logo-cart-icon {
      color: var(--primary-green);
      font-size: 1.4rem;
      user-select: none;
      margin-left: 2px;
      margin-bottom: 2px;
    }
    #scrollTopBtn {
      position: fixed;
      bottom: 1.5rem;
      right: 1.5rem;
      background-color: var(--primary-green);
      border-radius: 9999px;
      width: 44px;
      height: 44px;
      border: none;
      color: white;
      font-size: 1.4rem;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 5px rgb(0 0 0 / 0.2);
      transition: opacity 0.3s;
      opacity: 0;
      pointer-events: none;
      z-index: 40;
    }
    #scrollTopBtn.visible {
      opacity: 1;
      pointer-events: auto;
    }
  </style>
</head>
<body class="bg-gray-50">

<!-- Navbar -->
<nav>
  <a href="#" class="logo-teh inline-flex items-center select-none">
    Teh<span class="logo-kita">kita</span>
  </a>
  <ul>
    <li><a href="index.php" class="active" aria-current="page">Beranda</a></li>
    <li><a href="about.php">Tentang</a></li>
    <li><a href="product.php" id="nav-product-link">Produk</a></li>
    <li><a href="contact.php">Kontak</a></li>
  </ul>

 <!-- Cart Icon -->
            <?php
            $cart_count = 0;
            if (isset($_SESSION['cart'])) {
                // hitung jumlah item unik
                $cart_count = count($_SESSION['cart']);
            }
            ?>
            <a class="btn-sm-square bg-white rounded-circle ms-3 position-relative" href="cart.php">
                <small class="fa fa-shopping-bag text-body"></small>
                <?php if ($cart_count > 0): ?>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $cart_count ?>
                    </span>
                <?php endif; ?>
            </a>
</nav>


<!-- Main Container -->
<main class="container">
  <form method="post" action="cart.php?action=update" class="flex flex-col md:flex-row gap-6">
    
    <!-- ================= KIRI: DAFTAR KERANJANG ================= -->
    <section class="cart-left" aria-label="Daftar keranjang belanja">
      
      <!-- Logo + Judul -->
      <div class="logo-with-cart flex items-center gap-2 mb-3">
        <span class="logo-teh font-bold text-green-600">Teh</span>
        <span class="logo-kita font-bold text-yellow-600">kita</span>
        <svg class="logo-cart-icon text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" 
             viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" width="22" height="22" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" 
                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7.5M17 13l1.5 7.5M6 21a1 1 0 100-2 1 1 0 000 2zm11 0a1 1 0 100-2 1 1 0 000 2z"/>
        </svg>
        <h2 class="font-bold text-xl select-none">Keranjang Belanja</h2>
      </div>

      <!-- FORM UPDATE KERANJANG -->
<form method="post" action="cart.php?action=update">

  <!-- TABEL KERANJANG -->
  <div class="cart-table-container">
    <table class="cart-table w-full border border-gray-200 rounded-lg overflow-hidden text-sm" role="table">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-2">Hapus</th>
          <th class="p-2">Foto</th>
          <th class="p-2">Nama</th>
          <th class="p-2">Harga</th>
          <th class="p-2">Jumlah</th>
          <th class="p-2">Total</th>
        </tr>
      </thead>
      <tbody id="cart-tbody">
        <?php 
        $subtotal = 0;
        if (empty($cart)): ?>
          <tr>
            <td colspan="6" class="text-center py-4 text-gray-500">Keranjang kosong</td>
          </tr>
        <?php else: ?>
          <?php foreach ($cart as $id => $item): 
            $harga = intval($item['harga'] ?? 0);
            $qty   = intval($item['qty'] ?? 1);
            $total = $harga * $qty;
            $subtotal += $total;
          ?>
            <tr id="row-<?= $id ?>" class="border-t">
              
              <!-- Kolom Hapus -->
              <td class="p-2 text-center">
                <button type="button" class="btn-hapus text-red-500 font-bold text-xl" data-id="<?= $id ?>" title="Hapus item">&times;</button>
              </td>
              
              <!-- Kolom Foto -->
              <td class="p-2 text-center">
                <img src="img/<?= htmlspecialchars($item['foto'] ?? 'default.png') ?>" 
                     alt="<?= htmlspecialchars($item['nama'] ?? '') ?>" 
                     class="w-16 h-16 object-cover rounded">
              </td>
              
              <!-- Kolom Nama -->
              <td class="p-2 font-medium">
                <?= htmlspecialchars($item['nama'] ?? '-') ?>
              </td>
              
              <!-- Kolom Harga -->
              <td class="p-2">
                Rp <?= number_format($harga, 0, ',', '.') ?>
              </td>
              
              <!-- Kolom Jumlah -->
              <td class="p-2 text-center">
                <input 
                  type="number" 
                  name="qty[<?= $id ?>]" 
                  value="<?= $qty ?>" 
                  min="1" 
                  class="w-16 border rounded px-2 py-1 text-center">
              </td>
              
              <!-- Kolom Total -->
              <td class="p-2 font-semibold">
                Rp <?= number_format($total, 0, ',', '.') ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <!-- RINGKASAN PESANAN -->
  <section class="cart-right mt-5 md:mt-0" aria-label="Ringkasan pesanan">
    <div class="summary-box p-4 bg-gray-50 border rounded-lg shadow">
      
      <h3 class="font-bold text-lg mb-3 select-none">Ringkasan Pesanan</h3>
      
      <dl class="mb-3">
        <div class="flex justify-between">
          <dt>Subtotal:</dt>
          <dd id="cart-subtotal">Rp <?= number_format($subtotal, 0, ',', '.') ?></dd>
        </div>
        <div class="flex justify-between font-bold">
          <dt>Total:</dt>
          <dd id="cart-total">Rp <?= number_format($subtotal, 0, ',', '.') ?></dd>
        </div>
      </dl>

      <?php if (!empty($cart)): ?>
        <!-- Tombol Update -->
        <button type="submit" 
          class="btn-update bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded w-full mb-2 shadow">
          Update Keranjang
        </button>

        <!-- Tombol Checkout -->
        <a href="checkout.php" class="block">
          <button type="button" 
            class="btn-checkout bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded w-full shadow">
            Lanjut ke Pembayaran
          </button>
        </a>
      <?php endif; ?>

    </div>
  </section>

</form>

<script>
document.querySelectorAll('.btn-hapus').forEach(button => {
  button.addEventListener('click', function() {
    const id = this.getAttribute('data-id');
    const row = document.getElementById("row-" + id);

    Swal.fire({
      title: "Hapus produk ini?",
      text: "Produk akan dihapus dari keranjang belanja.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#e02424",
      cancelButtonColor: "#6c757d",
      confirmButtonText: '<i class="fa fa-trash"></i> Ya, hapus',
      cancelButtonText: "Batal",
      reverseButtons: true,
      backdrop: `rgba(0,0,0,0.4)`,
      showClass: { popup: 'animate__animated animate__zoomIn' },
      hideClass: { popup: 'animate__animated animate__fadeOut' }
    }).then((result) => {
      if (result.isConfirmed) {
        fetch('cart.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: 'hapus=' + encodeURIComponent(id)
        })
        .then(response => response.text())
        .then(data => {
          // animasi hilang row
          row.style.transition = "opacity 0.5s ease, transform 0.5s ease";
          row.style.opacity = "0";
          row.style.transform = "translateX(-50px)";
          
          setTimeout(() => {
            row.remove();

            Swal.fire({
              title: "Berhasil!",
              text: "Produk sudah dihapus dari keranjang.",
              icon: "success",
              timer: 1500,
              showConfirmButton: false,
              timerProgressBar: true,
              backdrop: `rgba(0,0,0,0.2)`
            });

            // kalau keranjang jadi kosong → refresh biar ringkasan subtotal update
            if (document.querySelectorAll("tbody tr").length === 0) {
              setTimeout(() => window.location.reload(), 1600);
            }

          }, 500);
        })
        .catch(() => {
          Swal.fire({
            title: "Oops...",
            text: "Gagal menghapus produk.",
            icon: "error",
            confirmButtonColor: "#d33"
          });
        });
      }
    });
  });
});
</script>


<!-- 🔹 Tombol Scroll To Top -->
<button id="scrollTopBtn" aria-label="Scroll to top" title="Scroll to top">
  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
       stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
    <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7" />
  </svg>
</button>

<style>
  /* 🔹 Styling tombol scroll to top */
  #scrollTopBtn {
    display: none;
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 100;
    background-color: #333;
    color: #fff;
    border: none;
    padding: 12px;
    border-radius: 50%;
    cursor: pointer;
    transition: 0.3s ease;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
  }
  #scrollTopBtn:hover {
    background-color: #555;
  }
</style>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

  // 🔹 Update jumlah produk (qty) - Hanya UI lokal, tanpa AJAX
  $(".qty-input").on("change", function() {
    let jml = $(this).val();
    let pid = $(this).data("id");

    // Update subtotal lokal (contoh: hanya di UI)
    let itemPrice = parseInt($(this).data("price")); // pastikan ada data-price
    $("#item-total-"+pid).text("Rp " + (itemPrice * jml).toLocaleString("id-ID"));
    
    // Opsional: update subtotal / total jika ada perhitungan lokal
  });

  // 🔹 Hapus produk dari keranjang - Hanya UI lokal
  $(".btn-delete").on("click", function(){
    let pid = $(this).data("id");
    $("#row-"+pid).remove();

    // Opsional: update subtotal / total jika ada perhitungan lokal
  });

});

// 🔹 Scroll To Top Button
const scrollTopBtn = document.getElementById("scrollTopBtn");
window.addEventListener("scroll", () => {
  if (document.documentElement.scrollTop > 200) {
    scrollTopBtn.style.display = "block";
  } else {
    scrollTopBtn.style.display = "none";
  }
});
scrollTopBtn.addEventListener("click", () => {
  window.scrollTo({ top: 0, behavior: "smooth" });
});
</script>

</body>
</html>