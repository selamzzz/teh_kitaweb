<?php
include "koneksi.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // hapus transaksi berdasarkan id
    $sql = "DELETE FROM tb_transaksi WHERE id_transaksi = '$id'";
    $result = mysqli_query($koneksi, $sql);

    if ($result) {
        echo "<script>
                alert('Transaksi berhasil dihapus!');
                window.location.href='riwayat_admin.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus transaksi!');
                window.location.href='riwayat_admin.php';
              </script>";
    }
} else {
    header("Location: riwayat_transaksi.php");
    exit;
}
?>
