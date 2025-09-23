<?php
session_start();
include 'koneksi.php';

// =============================
// Bikin cart kalau belum ada
// =============================
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$action = $_GET['action'] ?? null;
$id     = intval($_GET['id'] ?? 0);

// =============================
// Proses Action (Tambah via GET)
// =============================
if ($action === "add" && $id > 0) {
    $q = mysqli_query($koneksi, "SELECT * FROM tb_produk WHERE id='$id' LIMIT 1");
    $p = mysqli_fetch_assoc($q);

    if ($p) {
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty']++;   // ✅ pakai qty
        } else {
            $_SESSION['cart'][$id] = [
                "id"    => $p['id'],
                "nama"  => $p['nama'],
                "harga" => $p['harga'],
                "foto"  => $p['foto'],
                "qty"   => 1                   // ✅ pakai qty
            ];
        }
    }

    header("Location: cart.php");
    exit;
}

// =============================
// Hapus via POST (tombol ×)
// =============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus'])) {
    $hapus_id = intval($_POST['hapus']);
    if (isset($_SESSION['cart'][$hapus_id])) {
        unset($_SESSION['cart'][$hapus_id]);
    }
    header("Location: cart.php");
    exit;
}

// =============================
// Update qty via POST
// =============================
if ($action === "update" && !empty($_POST['qty'])) {
    foreach ($_POST['qty'] as $id_produk => $jml) {
        $jml = max(1, intval($jml));
        if (isset($_SESSION['cart'][$id_produk])) {
            $_SESSION['cart'][$id_produk]['qty'] = $jml;  // ✅ pakai qty
        }
    }
    header("Location: cart.php");
    exit;
}

// =============================
// Fungsi hitung total
// =============================
function total_belanja($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $harga = $item['harga'] ?? 0;
        $qty   = $item['qty']   ?? 1;
        $total += $harga * $qty;
    }
    return $total;
}

// =============================
// Ambil data cart untuk ditampilkan
// =============================
$cart = $_SESSION['cart'];

// Pastikan setiap item punya harga & qty
foreach ($cart as $k => $c) {
    if (!isset($c['harga'])) {
        $cart[$k]['harga'] = 0;
    }
    if (!isset($c['qty'])) {
        $cart[$k]['qty'] = 1;
    }
}

$subtotal = total_belanja($cart);

// simpan balik ke session
$_SESSION['cart'] = $cart;
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Teh Kita Point</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500&family=Lora:wght@600;700&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"/>
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->

    <!-- Navbar Section -->
    <nav class="navbar navbar-expand-lg navbar-light py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <a href="index.html" class="navbar-brand ms-4 ms-lg-0">
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
                        <a href="testimonial.php" class="dropdown-item">Testimoni</a>
                    </div>
                </div>
                <a href="contact.php" class="nav-item nav-link">Kontak</a>
            </div>
            <div class="d-none d-lg-flex ms-2">
                <!-- Search Form -->
                <form id="searchForm" action="product.php" method="get" style="display: inline-flex; align-items: center;">
                    <input type="text" id="searchInput" name="keyword" placeholder="Silahkan ketik produk..." style="width: 0; opacity: 0; padding: 5px 0; border: 1px solid #ccc; border-radius: 20px; transition: all 0.3s ease; overflow: hidden;">
                    <button type="button" id="searchBtn" class="btn-sm-square bg-white rounded-circle ms-2">
                        <small class="fa fa-search text-body"></small> 
                    </button>
                </form>

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

                <!-- User Icon Start -->
                    <?php
                    if (isset($_SESSION['user'])) {
                        $link_user = "profil2.php"; // Admin ke dashboard
                    } else {
                        $link_user = "login.php";     // Belum login
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
<!-- Carousel Start -->
<div class="container-fluid p-0 mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000" data-bs-pause="hover">
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
                                    <span class="text-warning"> Kita</span> Kontak
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
                                    <span class="text-warning"> Kita</span> Contact
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

        </div>
    </div>
</div>
<!-- Carousel End -->


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
<!-- Carousel End -->




    <!-- Contact Start -->
    <div class="container-xxl py-6">
        <div class="container">
            <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <h1 class="display-5 mb-3">Hubungi Kami!</h1>
                <p>Punya pertanyaan, saran, atau sekadar ingin ngobrol soal teh? Kami siap mendengarkan kamu kapan saja!</p>
            </div>
            <div class="row g-5 justify-content-center">
                <div class="col-lg-5 col-md-12 wow fadeInUp" data-wow-delay="0.1s">
                    <div class="bg-primary text-white d-flex flex-column justify-content-center h-100 p-5">
                        <h5 class="text-white">Hubungi Kami</h5>
                        <p class="mb-5"><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                        <h5 class="text-white">Email Kami</h5>
                        <p class="mb-5"><i class="fa fa-envelope me-3"></i>tehkita@point.com</p>
                        <h5 class="text-white">Alamat</h5>
                        <p class="mb-5"><i class="fa fa-map-marker-alt me-3"></i>Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat</p>
                        <h5 class="text-white">Follow </h5>
                        <div class="d-flex pt-2">
                            <!-- Instagram -->
                            <a class="btn btn-square btn-outline-light rounded-circle me-1" href="https://www.instagram.com/tehkita.point?igsh=MXEyaWIzMndqajZuag==" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>

                            <!-- TikTok -->
                          <a class="btn btn-square btn-outline-light rounded-circle me-1" href="https://www.tiktok.com/@teh.kita.perum2?_t=ZS-8zeXOwxstOS&_r=1" target="_blank"><i class="fab fa-tiktok"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 col-md-12 wow fadeInUp" data-wow-delay="0.5s">
                    <p class="mb-4">Form kontak akan segera aktif. Sementara itu, kamu bisa hubungi kami lewat email atau media sosial ya!.</p>
                    <form>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control" id="name" placeholder="Your Name">
                                    <label for="name">Nama</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control" id="email" placeholder="Your Email">
                                    <label for="email">Email</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <textarea class="form-control" placeholder="Leave a message here" id="message" style="height: 200px"></textarea>
                                    <label for="message">Pesan</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary rounded-pill py-3 px-5" type="submit">Kirim</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact End -->


   <!-- Google Map Start -->
<div class="container-xxl px-0 wow fadeIn" data-wow-delay="0.1s" style="margin-bottom: -6px;">
  <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3950.350196993868!2d108.56652487480283!3d-6.745927466326089!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e6f18d4a0e5e79f%3A0xb3a0e8c7bfc0635!2sTeh%20Kita%20Perumnas!5e0!3m2!1sid!2sid!4v1724491400000!5m2!1sid!2sid" 
    width="100%" 
    height="450" 
    style="border:0;" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
  </iframe>
</div>
<!-- Google Map End -->



<!-- Footer Start -->
    <div class="container-fluid bg-dark footer pt-5 wow fadeIn" data-wow-delay="0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <h1 class="fw-bold text-primary mb-4">Teh<span class="text-secondary">ki</span>ta</h1>
                    <p>Follow Sosmed Kami</p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href="https://www.instagram.com/tehkita.point?igsh=MXEyaWIzMndqajZuag=="><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href="https://www.tiktok.com/@teh.kita.perum2?_t=ZS-8zeXOwxstOS&_r=1" target="_blank"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4">Alamat</h4>
                    <p><i class="fa fa-map-marker-alt me-3"></i>Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat</p>
                    <p><i class="fa fa-phone-alt me-3"></i>+012 345 67890</p>
                    <p><i class="fa fa-envelope me-3"></i>tehkita@point</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h4 class="text-light mb-4"> Link Kami</h4>
                    <a class="btn btn-link" href="about.php">Tentang Kami</a>
                    <a class="btn btn-link" href="contact.php">Kontak</a>
                </div>
                
        
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square rounded-circle back-to-top"><i class="bi bi-arrow-up"></i></a>


    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>