<?php
session_start();


// hitung total item di keranjang
$cart_count = isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0;

include 'koneksi.php';

// cek notifikasi sukses
$notif = null;
$notif_img = null;
if (isset($_SESSION['success'])) {
    $notif = $_SESSION['success'];
    $notif_img = $_SESSION['success_img'] ?? null;

    // hapus biar sekali tampil aja
    unset($_SESSION['success']);
    unset($_SESSION['success_img']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Teh Kita Point</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <link href="img/favicon.ico" rel="icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Lora:wght@600;700&display=swap" rel="stylesheet"> 
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    
</head>

<body>

    <!-- Navbar Section -->
<nav class="navbar navbar-expand-lg navbar-light py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
    <a href="index.php" class="navbar-brand ms-4 ms-lg-0">
        <h1 class="fw-bold text-primary m-0">Teh<span class="text-secondary">ki</span>ta</h1>
    </a>

    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <a href="index.php" class="nav-item nav-link active">Beranda</a>
            <a href="about.php" class="nav-item nav-link">Tentang</a>
            <a href="product.php" class="nav-item nav-link">Produk</a>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Halaman</a>
                <div class="dropdown-menu m-0">
                    <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                </div>
            </div>
            <a href="contact.php" class="nav-item nav-link">Kontak</a>
        </div>

        <div class="d-none d-lg-flex ms-2">
            <!-- Search Form -->
            <form id="searchForm" action="product.php" method="get" class="d-flex align-items-center">
                <input type="text" id="searchInput" name="keyword" placeholder="Silahkan ketik produk..."
                    style="width: 0; opacity: 0; padding: 5px 0; border: 1px solid #ccc; border-radius: 20px; transition: all 0.3s ease;">
                <button type="button" id="searchBtn" class="btn-sm-square bg-white rounded-circle ms-2">
                    <small class="fa fa-search text-body"></small> 
                </button>
            </form>

            <!-- User Icon Start -->
            <?php
            if (isset($_SESSION['user'])) {
                $link_user = "profil2.php"; // Admin ke dashboard
            } else {
                $link_user = "login.php";   // Belum login
            }
            ?>
            <a class="btn-sm-square bg-white rounded-circle ms-3" href="<?= $link_user ?>">
                <small class="fa fa-user text-body"></small>
            </a>
            <!-- User Icon End -->

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
        </div>
    </div>
</nav>

<!-- Search Script -->
<script>
    const searchBtn = document.getElementById("searchBtn");
    const searchInput = document.getElementById("searchInput");
    let inputVisible = false;

    searchBtn.addEventListener("click", function () {
        inputVisible = !inputVisible;
        searchInput.style.width = inputVisible ? "200px" : "0";
        searchInput.style.opacity = inputVisible ? "1" : "0";
        if (inputVisible) searchInput.focus();
    });

    searchInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter" && searchInput.value.trim() !== "") {
            document.getElementById("searchForm").submit();
        }
    });
</script>


  <!-- Header Carousel Section -->
<div class="container-fluid p-0 mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
        <div class="carousel-inner">

            <!-- Slide 1 -->
            <div class="carousel-item active">
                <img class="w-100" src="img/carousel-1.jpg" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-lg-7">
                                <h1 class="display-2 fw-bold mb-4 text-light animated slideInDown">
                                    <span class="text-primary">Teh</span>
                                    <span class="text-warning"> Kita</span> Product
                                </h1>
                                <a href="product.php" class="btn btn-lg btn-success rounded-pill py-sm-3 px-sm-5 shadow">
                                   Semua Produk
                                </a>
                                <a href="contact.php" class="btn btn-lg btn-warning rounded-pill py-sm-3 px-sm-5 ms-3 shadow">
                                    Kontak Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide 2 -->
            <div class="carousel-item">
                <img class="w-100" src="img/carousel-2.jpg" alt="Image">
                <div class="carousel-caption">
                    <div class="container">
                        <div class="row justify-content-start">
                            <div class="col-lg-7">
                                <h1 class="display-2 fw-bold mb-4 text-light animated slideInDown">
                                    <span class="text-primary">Teh</span>
                                    <span class="text-warning"> Kita</span> Product
                                </h1>
                                <a href="#" class="btn btn-lg btn-success rounded-pill py-sm-3 px-sm-5 shadow">
                                    Semua Produk
                                </a>
                                <a href="contact.html" class="btn btn-lg btn-warning rounded-pill py-sm-3 px-sm-5 ms-3 shadow">
                                    Kontak Kami
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Navigasi -->
        <button class="carousel-control-prev" type="button" data-bs-target="#header-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#header-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>


    <!-- Products Section -->
     <style>
    /* Efek hover produk */
    .product-item {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
        background: #fff;
    }

    .product-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.15);
    }

    .product-item img {
        transition: transform 0.5s ease;
        border-bottom: 1px solid #eee;
    }

    .product-item:hover img {
        transform: scale(1.08);
    }

    /* Animasi fade-in */
    .fade-in {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.8s ease, transform 0.8s ease;
    }

    .fade-in.show {
        opacity: 1;
        transform: translateY(0);
    }
</style>
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-0 gx-5 align-items-end">
            <div class="col-lg-6">
                <div class="section-header text-start mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                    <h1 class="display-5 mb-3">Produk</h1>
                    <p>Menyediakan 2 kategori teh original dan milky series tea dengan rasa yang segar dan berbagai macam rasa pilihan.</p>
                </div>
            </div>
            <div class="col-lg-6 text-start text-lg-end wow slideInRight" data-wow-delay="0.1s">
                <ul class="nav nav-pills d-inline-flex justify-content-end mb-5">
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary border-2 active" data-bs-toggle="pill" href="#tab-1">Milky Series Varian Rasa</a>
                    </li>
                    <li class="nav-item me-2">
                        <a class="btn btn-outline-primary border-2" data-bs-toggle="pill" href="#tab-2">Original Tea - Varian Tea</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="tab-content">
            <!-- Tab 1: Milky Series -->
<div id="tab-1" class="tab-pane fade show p-0 active">
    <div class="row g-4">
        <?php
        $query = "SELECT p.*, k.nama_kategori 
                  FROM tb_produk p
                  JOIN tb_kategori k ON p.id_kategori = k.id_kategori
                  WHERE k.nama_kategori = 'Milky Series Varian Rasa'";
        $tampil = mysqli_query($koneksi, $query);
        while ($data = mysqli_fetch_array($tampil)) {
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 fade-in">
            <div class="product-item">
                <div class="position-relative bg-light overflow-hidden">
                    <img src="img/<?= htmlspecialchars($data['foto']) ?>" class="img-fluid" alt="<?= htmlspecialchars($data['nama']) ?>">
                </div>
                <div class="text-center p-4">
                    <a class="d-block h5 mb-2" href="#"><?= htmlspecialchars($data['nama']) ?></a>
                    <span class="text-primary fw-bold">Rp. <?= number_format($data['harga'], 0, ',', '.') ?></span>
                </div>
                <div class="d-flex border-top">
                    <small class="w-50 text-center border-end py-2">
                        <a class="text-body" href="detail.php?id_produk=<?= $data['id'] ?>">
                            <i class="fa fa-eye text-primary me-2"></i> Lihat detail
                        </a>
                    </small>
                    <small class="w-50 text-center py-2">
                        <form action="add_to_cart.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                            <input type="hidden" name="nama" value="<?= htmlspecialchars($data['nama']) ?>">
                            <input type="hidden" name="harga" value="<?= $data['harga'] ?>">
                            <input type="hidden" name="foto" value="<?= $data['foto'] ?>">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="btn btn-link text-body p-0">
                                <i class="fa fa-shopping-bag text-primary me-2"></i> tambahkan 
                            </button>
                        </form>
                    </small>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<!-- Tab 2: Original Tea -->
<div id="tab-2" class="tab-pane fade show p-0">
    <div class="row g-4">
        <?php
        $query = "SELECT p.*, k.nama_kategori 
                  FROM tb_produk p
                  JOIN tb_kategori k ON p.id_kategori = k.id_kategori
                  WHERE k.nama_kategori = 'Original Tea-Varian Tea'";
        $tampil = mysqli_query($koneksi, $query);
        while ($data = mysqli_fetch_array($tampil)) {
        ?>
        <div class="col-xl-3 col-lg-4 col-md-6 fade-in">
            <div class="product-item">
                <div class="position-relative bg-light overflow-hidden">
                    <img src="img/<?= htmlspecialchars($data['foto']) ?>" class="img-fluid" alt="<?= htmlspecialchars($data['nama']) ?>">
                </div>
                <div class="text-center p-4">
                    <a class="d-block h5 mb-2" href="#"><?= htmlspecialchars($data['nama']) ?></a>
                    <span class="text-primary fw-bold">Rp. <?= number_format($data['harga'], 0, ',', '.') ?></span>
                </div>
                <div class="d-flex border-top">
                    <small class="w-50 text-center border-end py-2">
                        <a class="text-body" href="detail.php?id_produk=<?= $data['id'] ?>">
                            <i class="fa fa-eye text-primary me-2"></i> Lihat detail
                        </a>
                    </small>
                    <small class="w-50 text-center py-2">
                        <form action="add_to_cart.php" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?= $data['id'] ?>">
                            <input type="hidden" name="nama" value="<?= htmlspecialchars($data['nama']) ?>">
                            <input type="hidden" name="harga" value="<?= $data['harga'] ?>">
                            <input type="hidden" name="foto" value="<?= $data['foto'] ?>">
                            <input type="hidden" name="qty" value="1">
                            <button type="submit" class="btn btn-link text-body p-0">
                                <i class="fa fa-shopping-bag text-primary me-2"></i> Tambahkan
                            </button>
                        </form>
                    </small>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
</div>


<!-- Fade-in Effect for Products -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const items = document.querySelectorAll(".fade-in");
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add("show");
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.2 });
    items.forEach(item => observer.observe(item));
});
</script>

<!-- Back to Top Button -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>

<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="lib/wow/wow.min.js"></script>
<script src="lib/easing/easing.min.js"></script>
<script src="lib/waypoints/waypoints.min.js"></script>
<script src="lib/owlcarousel/owl.carousel.min.js"></script>
<script src="js/main.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php if (!empty($notif)): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
Swal.fire({
  toast: true,
  position: 'top-end',
  iconHtml: '<img src="img/<?= $notif_img ?>" style="width:30px;height:30px;border-radius:50%;">',
  title: <?= json_encode(strip_tags($notif)) ?>,
  showConfirmButton: false,
  timer: 2500,
  timerProgressBar: true,
  background: '#f0fff4',
  color: '#155724',
});
</script>
<?php endif; ?>

</body>
</html>
