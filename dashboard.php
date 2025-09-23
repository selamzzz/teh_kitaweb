<?php
include 'koneksi.php';
session_start();

// ======================= CEK LOGIN ADMIN =======================
if (!isset($_SESSION['admin'])) {
    header('Location: login.php');
    exit();
}

// ======================= AMBIL DATA ADMIN =======================
$username_admin = $_SESSION['admin'];
$adminQ = mysqli_query($koneksi, "SELECT * FROM tb_user WHERE username='$username_admin' LIMIT 1");
$admin  = mysqli_fetch_assoc($adminQ);
$foto_profil = !empty($admin['foto_profil']) ? $admin['foto_profil'] : 'img/default_profile.png';

// ======================= HANDLE PRODUK =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan_produk'])) {
    $id = $_POST['id'] ?? null;
    $nama = $_POST['nama'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $id_kategori = $_POST['id_kategori'];
    $deskripsi = $_POST['deskripsi'];

    // Handle foto
    if (!empty($_FILES['foto']['name'])) {
        $foto = time() . '_' . $_FILES['foto']['name'];
        if (!is_dir('img/')) mkdir('img/', 0777, true);
        move_uploaded_file($_FILES['foto']['tmp_name'], 'img/' . $foto);
    } else {
        $foto = $_POST['foto_lama'] ?? '';
    }

    if ($id) {
        mysqli_query($koneksi, "UPDATE tb_produk 
            SET nama='$nama', harga='$harga', stok='$stok', id_kategori='$id_kategori', deskripsi='$deskripsi', foto='$foto' 
            WHERE id='$id'");
        header("Location: dashboard.php?aksi=barang&notif=update");
        exit;
    } else {
        mysqli_query($koneksi, "INSERT INTO tb_produk (nama, harga, stok, id_kategori, deskripsi, foto) 
            VALUES ('$nama',$harga,$stok,$id_kategori,'$deskripsi','$foto')");
        header("Location: dashboard.php?aksi=barang&notif=tambah");
        exit;
    }
}

// ======================= HAPUS PRODUK =======================
if (isset($_GET['aksi']) && $_GET['aksi'] === 'hapus' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT foto FROM tb_produk WHERE id='$id'"));
    if ($produk['foto'] && file_exists("img/" . $produk['foto'])) unlink("img/" . $produk['foto']);
    mysqli_query($koneksi, "DELETE FROM tb_produk WHERE id='$id'");
    header("Location: dashboard.php?aksi=barang&notif=hapus");
    exit;
}

// ======================= AMBIL PRODUK =======================
$produkAll = mysqli_query($koneksi, "
    SELECT p.*, k.nama_kategori 
    FROM tb_produk p 
    JOIN tb_kategori k ON p.id_kategori = k.id_kategori
");

// ======================= EDIT DATA =======================
$editMode = false;
$nama = $harga = $stok = $id_kategori = $foto = $deskripsi = '';
if (isset($_GET['aksi']) && $_GET['aksi'] === 'edit' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id='$id'"));
    if ($produk) {
        $nama = $produk['nama'];
        $harga = $produk['harga'];
        $stok = $produk['stok'];
        $id_kategori = $produk['id_kategori'];
        $foto = $produk['foto'];
        $deskripsi = $produk['deskripsi'];
        $editMode = true;
    }
}

// ======================= NOTIFIKASI SWEETALERT =======================
$notif = $_GET['notif'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
body { font-family:'Segoe UI', sans-serif; background:#f8f9fa; overflow-x:hidden; }
.sidebar { width:240px; height:100vh; background:#fff; border-right:1px solid #e0e0e0; padding-top:20px; position:fixed; top:0; left:0; transition:.3s ease; z-index:1000; }
.sidebar.collapsed { margin-left:-240px; }
.sidebar .username-box { display:flex; align-items:center; justify-content:center; margin-bottom:25px; }
.sidebar .username-box i { font-size:28px; color:#2e7d32; margin-right:10px; }
.sidebar .username { font-size:18px; font-weight:700; color:#333; }
.sidebar a { display:flex; align-items:center; color:#333; padding:12px 20px; text-decoration:none; border-radius:6px; margin:4px 12px; font-weight:500; transition:.3s; }
.sidebar a:hover { background:#e8f5e9; color:#2e7d32; }
.sidebar a.active { background:#c8e6c9; color:#2e7d32; font-weight:600; }
.sidebar a i { margin-right:10px; width:18px; text-align:center; }
.content { margin-left:240px; padding:20px; transition:.3s ease; }
.content.expanded { margin-left:0; }
.card { border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,.05); }
.toggle-btn { font-size:20px; cursor:pointer; color:#2e7d32; margin-right:15px; }
img.thumb { width:50px; height:50px; object-fit:cover; border-radius:6px; border:1px solid #ddd; }
</style>
</head>
<body>
  
<div class="sidebar" id="sidebar">
    <div class="username-box">
        <i class="fas fa-user-circle"></i>
        <div class="username"><?= htmlspecialchars($_SESSION['admin']); ?></div>
    </div>
    <a href="#" class="active"><i class="fas fa-home"></i> Dashboard</a>
    <a href="#"><i class="fas fa-plus-square"></i> Tambah Produk</a>
    <a href="#"><i class="fas fa-box"></i> Data Barang</a>
    <a href="riwayat_admin.php"><i class="fas fa-receipt"></i> Riwayat Transaksi</a>
    <a href="data_pelanggan.php"><i class="fas fa-users"></i> Data Pelanggan</a>
    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
</div>

<div class="content" id="content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-bars toggle-btn" id="toggleBtn"></i>
            <h2 class="mb-0">Dashboard</h2>
        </div>
        <input type="text" class="form-control w-25" placeholder="Cari...">
    </div>

    <!-- Form Produk -->
    <div class="card mb-4 p-4">
        <h5><?= $editMode ? 'Edit Produk' : 'Tambah Produk' ?></h5>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $editMode ? $id : '' ?>">
            <input type="hidden" name="foto_lama" value="<?= $editMode ? $foto : '' ?>">
            <div class="mb-3">
                <label class="form-label">Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($nama) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Harga</label>
                <input type="number" name="harga" class="form-control" value="<?= htmlspecialchars($harga) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= htmlspecialchars($stok) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Kategori</label>
                <select name="id_kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $kategoriQ = mysqli_query($koneksi, "SELECT * FROM tb_kategori");
                    while ($k = mysqli_fetch_assoc($kategoriQ)) {
                        $sel = ($id_kategori == $k['id_kategori']) ? 'selected' : '';
                        echo "<option value='{$k['id_kategori']}' $sel>" . htmlspecialchars($k['nama_kategori']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Foto Produk</label>
                <input type="file" name="foto" class="form-control">
                <?php if ($editMode && $foto): ?>
                    <img src="img/<?= htmlspecialchars($foto) ?>" class="thumb mt-2">
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3" required><?= htmlspecialchars($deskripsi) ?></textarea>
            </div>
            <button type="submit" name="simpan_produk" class="btn btn-success"><?= $editMode ? 'Update' : 'Tambah' ?></button>
        </form>
    </div>

    <!-- Tabel Produk -->
    <div class="card p-3">
        <h5>Data Barang</h5>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>No</th><th>Nama Produk</th><th>Harga</th><th>Stok</th><th>Kategori</th><th>Foto</th><th>Deskripsi</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no=1; while($data = mysqli_fetch_assoc($produkAll)): ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($data['nama']) ?></td>
                    <td>Rp <?= number_format($data['harga'],0,',','.') ?></td>
                    <td><?= htmlspecialchars($data['stok']) ?></td>
                    <td><?= htmlspecialchars($data['nama_kategori']) ?></td>
                    <td>
                        <?php if ($data['foto']): ?>
                            <img src="img/<?= htmlspecialchars($data['foto']) ?>" class="thumb">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/50" class="thumb">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($data['deskripsi']) ?></td>
                    <td>
                        <a href="?aksi=edit&id=<?= $data['id'] ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                        <a href="?aksi=hapus&id=<?= $data['id'] ?>" class="btn btn-sm btn-danger btn-hapus" data-id="<?= $data['id'] ?>"><i class="fas fa-trash-alt"></i></a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
const toggleBtn = document.getElementById("toggleBtn");
const sidebar = document.getElementById("sidebar");
const content = document.getElementById("content");
toggleBtn.addEventListener("click", () => {
    sidebar.classList.toggle("collapsed");
    content.classList.toggle("expanded");
});

// ======================= SweetAlert Notifikasi =======================
<?php if($notif==='tambah'): ?>
Swal.fire({icon:'success',title:'Berhasil',text:'Produk berhasil ditambahkan',timer:2000,showConfirmButton:false});
<?php elseif($notif==='update'): ?>
Swal.fire({icon:'success',title:'Berhasil',text:'Produk berhasil diperbarui',timer:2000,showConfirmButton:false});
<?php elseif($notif==='hapus'): ?>
Swal.fire({icon:'success',title:'Berhasil',text:'Produk berhasil dihapus',timer:2000,showConfirmButton:false});
<?php endif; ?>

// ======================= SweetAlert Konfirmasi Hapus =======================
document.querySelectorAll('.btn-hapus').forEach(btn=>{
    btn.addEventListener('click', function(e){
        e.preventDefault();
        let id = this.dataset.id;
        Swal.fire({
            title: 'Yakin ingin hapus?',
            text: "Data yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result)=>{
            if(result.isConfirmed){
                window.location.href='?aksi=hapus&id='+id;
            }
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
