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


<!-- Navbar Start -->
<div class="container-fluid fixed-top px-0 wow fadeIn" data-wow-delay="0.1s">
    <!-- Top Bar -->
    <div class="top-bar row gx-0 align-items-center d-none d-lg-flex">
        <div class="col-lg-6 px-5 text-start">
            <small><i class="fa fa-map-marker-alt me-2"></i>Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat</small>
            <small class="ms-4"><i class="fa fa-envelope me-2"></i>tehkita@point</small>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
        <!-- Brand -->
        <a href="index.php" class="navbar-brand ms-4 ms-lg-0">
            <h1 class="fw-bold text-primary m-0">Teh<span class="text-secondary">ki</span>ta</h1>
        </a>

        <!-- Mobile Toggle -->
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu -->
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

            <!-- ===== Right Icons (Search, User, Cart) ===== -->
            <div class="d-none d-lg-flex align-items-center ms-2">
                <!-- Search -->
                <form id="searchForm" action="product.php" method="get" class="d-flex align-items-center">
                    <input type="text" id="searchInput" name="keyword" placeholder="Silahkan ketik produk...">
                    <button type="button" id="searchBtn" class="icon-btn">
                        <i class="fa fa-search"></i>
                    </button>
                </form>

                <!-- User -->
                <?php
                if (isset($_SESSION['user'])) {
                    $link_user = "profil2.php"; // Sudah login
                } else {
                    $link_user = "login.php";   // Belum login
                }
                ?>
                <a class="icon-btn" href="<?= $link_user ?>">
                    <i class="fa fa-user"></i>
                </a>

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
</div>
<!-- Navbar End -->

<!-- ===== Extra CSS ===== -->
<style>
    /* Search Input */
    #searchInput {
        width: 0;
        opacity: 0;
        padding: 5px 0;
        border: 1px solid #ccc;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    #searchInput.show {
        width: 200px;
        opacity: 1;
        padding: 5px 10px;
    }

    /* Icon Buttons */
    .icon-btn {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: #fff;
        margin-left: 12px;
        position: relative;
    }
    .icon-btn i {
        font-size: 16px;
        color: #333;
    }
</style>

<!-- ===== Search Script ===== -->
<script>
    const searchBtn = document.getElementById("searchBtn");
    const searchInput = document.getElementById("searchInput");
    const searchForm = document.getElementById("searchForm");

    let inputVisible = false;

    searchBtn.addEventListener("click", function () {
        if (!inputVisible) {
            searchInput.classList.add("show");
            searchInput.focus();
            inputVisible = true;
        } else {
            if (searchInput.value.trim() !== "") {
                searchForm.submit();
            } else {
                searchInput.classList.remove("show");
                inputVisible = false;
            }
        }
    });

    searchInput.addEventListener("keypress", function (e) {
        if (e.key === "Enter" && searchInput.value.trim() !== "") {
            searchForm.submit();
        }
    });
</script>


  <!-- Carousel Start -->
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
                <span class="text-primary">Teh</span><span class="text-warning"> Kita</span>
              </h1>
              <a href="product.php" class="btn btn-lg btn-success rounded-pill py-sm-3 px-sm-5 shadow">
                semua Produk
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
                <span class="text-primary">Teh</span><span class="text-warning"> Kita</span>
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
<!-- Carousel End -->


<!-- Ucapan Sambutan Start -->
<style>
    /* Animasi Scroll Fade Slide */
    @keyframes fadeSlideUp {
        0% {
            opacity: 0;
            transform: translateY(30px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Efek berdenyut */
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .welcome-section {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(to bottom, #fff7e6, #ffffff);
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }

    .welcome-section.show {
        animation: fadeSlideUp 1s ease-out forwards;
    }

    .welcome-section h1 {
        font-size: 42px;
        font-weight: bold;
        color: #2e2e2e;
        margin-bottom: 15px;
        animation: pulse 3s infinite ease-in-out;
    }

    .welcome-section p {
        max-width: 750px;
        margin: 0 auto;
        font-size: 20px;
        line-height: 1.8;
        color: #5c5c5c;
    }

    .highlight {
        color: #d2691e;
        font-weight: bold;
    }
</style>

<section class="welcome-section" id="welcome">
    <h1>Selamat Datang di Website <span class="highlight">Teh Kita Point</span></h1>
    <p>
        Teh Kita Point menghadirkan es teh segar dari daun teh organik pilihan, 
        diproses dengan hati-hati untuk menjaga cita rasa, aroma, dan kualitas alaminya. 
        Setiap sajian dibuat untuk memberi kesegaran dan kenikmatan di setiap momen Anda.
    </p>
</section>

<script>
    // Animasi muncul saat scroll
    window.addEventListener("scroll", function () {
        let section = document.getElementById("welcome");
        let sectionPos = section.getBoundingClientRect().top;
        let screenPos = window.innerHeight / 1.3;
        if (sectionPos < screenPos) {
            section.classList.add("show");
        }
    });
</script>
<!-- Ucapan Sambutan End -->

    <!-- About Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                    <div class="about-img position-relative overflow-hidden p-5 pe-0">
                        <img class="img-fluid w-100" src="img/about.jpg">
                    </div>
                </div>
                <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                    <h1 class="display-5 mb-4">Komposisi Teh Pilihan Terbaik</h1>
                    <p class="mb-4">Kesegaran alami dalam setiap tegukan.
                                Es teh kami diracik dari bahan pilihan untuk menghadirkan rasa teh yang otentik dan menyegarkan.</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Daun Teh Pilihan</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Gula tebu asli yang manisnya pas</p>
                    <p><i class="fa fa-check text-primary me-3"></i>Sedikit sentuhan asam alami untuk keseimbangan rasa</p>
                    <a class="btn btn-primary rounded-pill py-3 px-5 mt-3" >Baca Terlebih Dahulu</a>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


    <!-- Feature Start -->
    <div class="container-fluid bg-light bg-icon my-5 py-6">
        <div class="container">
            <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <h1 class="display-5 mb-3">Teh Kita Point</h1>
                <p>Setiap cup Teh Kita dibuat dari daun teh pilihan yang dipetik langsung dari kebun. Kami olah tanpa bahan tambahan berbahaya agar rasa dan kualitasnya tetap alami.</p>
            </div>
            <div class="row g-4">
                
               <style>
    /* Animasi goyang halus */
    @keyframes smoothWiggle {
        0%, 100% { transform: rotate(0deg) scale(1); }
        25% { transform: rotate(-5deg) scale(1.05); }
        50% { transform: rotate(5deg) scale(1.05); }
        75% { transform: rotate(-3deg) scale(1.03); }
    }

    .animated-icon {
        display: inline-block;
        animation: smoothWiggle 1.8s ease-in-out infinite;
    }

    /* Delay biar goyangnya gantian */
    .delay-1 { animation-delay: 0s; }
    .delay-2 { animation-delay: 0.3s; }
    .delay-3 { animation-delay: 0.6s; }
</style>

<div class="row g-4">
    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
        <div class="bg-white text-center h-100 p-4 p-xl-5">
            <img class="img-fluid mb-4 animated-icon delay-1" src="img/icon-1.png" alt="" style="width:50px; height:auto;">
            <h4 class="mb-3">Produk Organik</h4>
            <p class="mb-4">Kami hanya memilih daun teh organik terbaik, bebas pestisida dan bahan kimia, lalu mengolahnya dengan teliti agar cita rasa, aroma, dan keaslian kualitasnya tetap terjaga.</p>
            <a class="btn btn-outline-primary border-2 py-2 px-4 rounded-pill">Aman</a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
        <div class="bg-white text-center h-100 p-4 p-xl-5">
            <img class="img-fluid mb-4 animated-icon delay-2" src="img/icon-2.png" alt="" style="width:50px; height:auto;">
            <h4 class="mb-3">Bahan Alami</h4>
            <p class="mb-4">Terbuat dari bahan alami pilihan dan gula alami berkualitas, memberikan rasa manis pas, segar, dan aman dinikmati setiap hari untuk momen santai Anda.</p>
            <a class="btn btn-outline-primary border-2 py-2 px-4 rounded-pill">Nyaman</a>
        </div>
    </div>

    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
        <div class="bg-white text-center h-100 p-4 p-xl-5">
            <img class="img-fluid mb-4 animated-icon delay-3" src="img/icon-3.png" alt="" style="width:50px; height:auto;">
            <h4 class="mb-3">Varian Milky dan Teh</h4>
            <p class="mb-4">Nikmati kombinasi unik antara teh premium dan susu segar dalam varian Milky Series kami. Rasa lembut dan creamy cocok dinikmati kapan saja.</p>
            <a class="btn btn-outline-primary border-2 py-2 px-4 rounded-pill">Menyegarkan</a>
        </div>
    </div>
</div>

    <!-- feeature end -->

    
    <!-- Product Start -->
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
                    <h1 class="display-5 mb-3">Produk Terlaris</h1>
                    <p>Menyediakan 2 kategori teh original dan milky series tea dengan rasa yang segar dan berbagai macam rasa pilihan,di bawah ini adalah beberapa produk milky series terlaris kami.</p>
                </div>
            </div>
        </div>
        
        <div class="tab-content">
<div id="tab-1" class="tab-pane fade show p-0 active">
    <div class="row g-4">

        <!-- Produk 1 -->
        <div class="col-xl-3 col-lg-4 col-md-6 fade-in">
            <div class="product-item">
                <div class="position-relative bg-light overflow-hidden">
                    <img src="img/kopisusu.jpg" class="img-fluid" alt="Coffe Signature">
                    <div class="bg-warning rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">Terlaris</div>
                </div>
                <div class="text-center p-4">
                    <h5 class="mb-2">COFFE SIGNATURE</h5>
                    <p class="small text-muted">
                        Perpaduan segar antara susu creamy dan rasa kopi yang menyegarkan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Produk 2 -->
        <div class="col-xl-3 col-lg-4 col-md-6 fade-in">
            <div class="product-item">
                <div class="position-relative bg-light overflow-hidden">
                    <img src="img/taro.jpg" class="img-fluid" alt="Milky Taro">
                    <div class="bg-warning rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">Terlaris</div>
                </div>
                <div class="text-center p-4">
                    <h5 class="mb-2">MILKY TARO</h5>
                    <p class="small text-muted">
                        Perpduan rasa susu dan nikmatnya varian taro.
                    </p>
                </div>
            </div>
        </div>

        <!-- Produk 3 -->
        <div class="col-xl-3 col-lg-4 col-md-6 fade-in">
            <div class="product-item">
                <div class="position-relative bg-light overflow-hidden">
                    <img src="img/vanilablue.jpg" class="img-fluid" alt="Vanila Blue">
                    <div class="bg-warning rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">Terlaris</div>
                </div>
                <div class="text-center p-4">
                    <h5 class="mb-2">VANILA BLUE </h5>
                    <p class="small text-muted">
                        Perpaduan rasa susu dan vanila yang dominan manis menyegarkan.
                    </p>
                </div>
            </div>
        </div>

        <!-- Produk 4 -->
        <div class="col-xl-3 col-lg-4 col-md-6 fade-in">
    <div class="product-item">
        <div class="position-relative bg-light overflow-hidden">
            <img src="img/redvelvet.jpg" class="img-fluid" alt="Redvelvet">
            <div class="bg-warning rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3">Terlaaris</div>
        </div>
        <div class="text-center p-4">
            <h5 class="mb-2">REDVELVET</h5>
            <p class="small text-muted">
                Rasa susu dan varian redvelvet yang manis dan menyegrakan
            </p>
        </div>
    </div>
</div>

<!-- Tambahkan tombol All Menu di bawah grid -->
<div class="col-12 text-center mt-4">
   <a href="product.php" class="btn btn-primary px-5 py-3 rounded-pill" style="font-size: 20px;">
    Semua Menu
</a>

</div>




<script>
    // Animasi fade-in saat scroll
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

        items.forEach(item => {
            observer.observe(item);
        });
    });
</script>
<!-- Product End -->

     <!-- informasi Start -->
    <div class="container-fluid bg-light bg-icon my-5 py-6">
    <div class="container">
        <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
            <h1 class="display-5 mb-3">Information Teh Kita Point</h1>
            <p>Informasi Teh Kita.</p>
        </div>

        <style>
            /* Gaya lingkaran icon */
            .icon-circle {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: #ffffff;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                transition: all 0.3s ease-in-out;
                box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            }

            /* Icon */
            .icon-circle img {
                width: 40px;
                height: auto;
                animation: wiggleBounceRotate 2.2s infinite ease-in-out;
                transition: transform 0.3s ease-in-out;
            }

            /* Animasi: goyang + mantul + sedikit putar */
            @keyframes wiggleBounceRotate {
                0%, 100% { transform: rotate(0deg) scale(1); }
                10% { transform: rotate(-8deg) scale(1.05); }
                20% { transform: rotate(8deg) scale(1.05); }
                30% { transform: rotate(-6deg) scale(1.06); }
                40% { transform: rotate(6deg) scale(1.06); }
                50% { transform: rotate(0deg) scale(1.1); }
                60% { transform: rotate(0deg) scale(1.04); }
                80% { transform: rotate(0deg) scale(1.02); }
            }

            /* Delay biar goyang bergantian */
            .icon-delay-1 img { animation-delay: 0s; }
            .icon-delay-2 img { animation-delay: 0.4s; }
            .icon-delay-3 img { animation-delay: 0.8s; }

            /* Hover lingkaran */
            .icon-circle:hover {
                transform: scale(1.08);
                box-shadow: 0 6px 20px rgba(0,0,0,0.12);
            }

            /* Hover icon */
            .icon-circle:hover img {
                transform: scale(1.2);
            }

            /* Rapiin kolom agar isi di tengah semua */
            .info-box {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: space-between;
                text-align: center;
                height: 100%;
            }
        </style>

        <div class="row g-4">
            <!-- Kolom 1 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-white p-4 p-xl-5 info-box">
                    <div class="icon-circle icon-delay-1">
                        <img src="img/icon-4.png" alt="">
                    </div>
                    <h4 class="mb-3">JAM BUKA TOKO</h4>
                    <div style="font-weight: bold; font-size: 18px; color: #333;">
                        <p>Senin–Jumat<br>⏰ 08.00 – 21.30</p>
                        <p>Sabtu–Minggu<br>⏰ 08.00 – 22.00</p>
                    </div>
                    <a class="btn btn-outline-primary border-2 py-2 px-4 rounded-pill">Time</a>
                </div>
            </div>

            <!-- Kolom 2 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <div class="bg-white p-4 p-xl-5 info-box">
                    <div class="icon-circle icon-delay-2">
                        <img src="img/icon-6.png" alt="">
                    </div>
                    <h4 class="mb-3">CABANG TEH KITA</h4>
                    <p style="font-weight: bold; font-size: 18px; color: #333;">
                        Klik di sini untuk mengetahui lokasi cabang Teh Kita terdekat dan temukan informasi menarik lainnya khusus untuk wilayah Cirebon
                    </p>
                    <a href="lokasi.php" class="btn btn-outline-primary border-2 py-2 px-4 rounded-pill">Location</a>
                </div>
            </div>

            <!-- Kolom 3 -->
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <div class="bg-white p-4 p-xl-5 info-box">
                    <div class="icon-circle icon-delay-3">
                        <img src="img/icon-5.png" alt="">
                    </div>
                    <h4 class="mb-3">ORDER PRODUK</h4>
                    <p style="font-weight: bold; font-size: 18px; color: #333;">
                        Ayo order produk kami dan nikmati kesegaran teh alami bersama pilihan varian rasa lainnya, buruan order supaya momen segarmu makin lengkap!
                    </p>
                    <a href="product.php" class="btn btn-outline-primary border-2 py-2 px-4 rounded-pill">Order</a>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- informasi end -->

    <!-- Testimonial Start -->
    <div class="container-fluid bg-light bg-icon py-6 mb-5">
        <div class="container">
            <div class="section-header text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 500px;">
                <h1 class="display-5 mb-3">Customer Review</h1>
                <p>Es Teh segar dengan rasa teh asli, manisnya pas dan menyegarkan. Cocok diminum kapan saja untuk menemani aktivitasmu.</p>
            </div>
            <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
                <div class="testimonial-item position-relative bg-white p-5 mt-4">
                    <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                    <p class="mb-4">Kemasan bersih, segel rapi, esnya awet dingin saat diterima. Bisa kelihatan kalau diproduksi dengan standar kebersihan yang bagus.</p>
                    <div class="d-flex align-items-center">
                        <img class="flex-shrink-0 rounded-circle" src="img/testimonial-1.jpg" alt="">
                        <div class="ms-3">
                            <h5 class="mb-1">Client Name</h5>
                            <span>Profession</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item position-relative bg-white p-5 mt-4">
                    <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                    <p class="mb-4">Jarang ada es teh instan dengan kualitas sebagus ini. Cairannya bening, gak keruh, dan rasanya konsisten tiap kali beli..</p>
                    <div class="d-flex align-items-center">
                        <img class="flex-shrink-0 rounded-circle" src="img/testimonial-2.jpg" alt="">
                        <div class="ms-3">
                            <h5 class="mb-1">Client Name</h5>
                            <span>Profession</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item position-relative bg-white p-5 mt-4">
                    <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                    <p class="mb-4">Kerasa banget ini bukan teh oplosan atau essence, tapi beneran dari teh asli. Ada body-nya, jadi gak hambar.</p>
                    <div class="d-flex align-items-center">
                        <img class="flex-shrink-0 rounded-circle" src="img/testimonial-3.jpg" alt="">
                        <div class="ms-3">
                            <h5 class="mb-1">Client Name</h5>
                            <span>Profession</span>
                        </div>
                    </div>
                </div>
                <div class="testimonial-item position-relative bg-white p-5 mt-4">
                    <i class="fa fa-quote-left fa-3x text-primary position-absolute top-0 start-0 mt-n4 ms-5"></i>
                    <p class="mb-4">Rasanya konsisten, teh asli dan manisnya pas. Seger banget diminum siang-siang, apalagi pas cuaca panas</p>
                    <div class="d-flex align-items-center">
                        <img class="flex-shrink-0 rounded-circle" src="img/testimonial-4.jpg" alt="">
                        <div class="ms-3">
                            <h5 class="mb-1">Client Name</h5>
                            <span>Profession</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Testimonial End -->
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
                    <a class="btn btn-link" href="">Tentang Kami</a>
                    <a class="btn btn-link" href="">Kontak</a>
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