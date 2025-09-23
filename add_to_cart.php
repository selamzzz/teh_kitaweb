<?php
session_start();
include "koneksi.php";

// inisialisasi cart
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id  = intval($_POST['id'] ?? 0);
    $qty = max(1, intval($_POST['qty'] ?? 1));

    if ($id > 0) {
        $query   = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id='$id'");
        $product = mysqli_fetch_assoc($query);

        if ($product) {
            // gunakan product id sebagai key
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['qty'] += $qty; // update qty
            } else {
                $_SESSION['cart'][$id] = [
                    'id'    => $product['id'],
                    'nama'  => $product['nama'],
                    'harga' => $product['harga'],
                    'foto'  => $product['foto'],
                    'qty'   => $qty
                ];
            }

            $_SESSION['success'] = "Produk <b>{$product['nama']}</b> berhasil ditambahkan ke keranjang!";
            $_SESSION['success_img'] = $product['foto'];

            header("Location: product.php");
            exit;
        } else {
            header("Location: product.php?error=produk_tidak_ditemukan");
            exit;
        }
    } else {
        header("Location: product.php?error=id_tidak_valid");
        exit;
    }
} else {
    header("Location: index.php");
    exit;
}
?>
