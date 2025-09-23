<?php
session_start();
include 'koneksi.php';

// Cek apakah id produk dikirim lewat URL
if (!isset($_GET['id_produk'])) {
    die("ID produk tidak ditemukan.");
}

$id_produk = intval($_GET['id_produk']); // Amankan input

// Query ambil data produk
$query = "SELECT * FROM tb_produk WHERE id = $id_produk LIMIT 1";
$result = mysqli_query($koneksi, $query);

// Cek data ditemukan atau tidak
if (!$result || mysqli_num_rows($result) == 0) {
    die("Produk tidak ditemukan.");
}

$data = mysqli_fetch_assoc($result);

// Cek session untuk link user
if (isset($_SESSION['admin'])) {
    $link_user = "dashboard.php";
} elseif (isset($_SESSION['pelanggan'])) {
    $link_user = "profil.php";
} else {
    $link_user = "login.php";
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
    <style>
        /* ===== IMPORT FONT ===== */
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&family=Poppins:wght@400;600;700&display=swap');

        /* ===== CONTAINER ===== */
        .single-product {
            padding: 100px 5%;
            background: radial-gradient(circle at top left, #ffffff, #f5f7fb);
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-wrap: wrap;
            gap: 60px;
            align-items: center;
        }

        /* ===== IMAGE SECTION ===== */
        .single-product-img {
            flex: 1;
            display: flex;
            justify-content: center;
            perspective: 1200px;
        }

        .single-product-img img {
            width: 90%;
            max-width: 520px;
            border-radius: 22px;
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.15);
            animation: floatImage 4s ease-in-out infinite;
            transition: transform 0.6s ease, box-shadow 0.6s ease;
        }

        .single-product-img img:hover {
            transform: rotateY(8deg) scale(1.08);
            box-shadow: 0 40px 80px rgba(0, 0, 0, 0.25), 0 0 30px rgba(255, 124, 0, 0.2);
        }

        @keyframes floatImage {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-14px); }
        }

        /* ===== CONTENT SECTION ===== */
        .single-product-content {
            flex: 1;
            animation: fadeInRight 1s ease forwards;
            opacity: 0;
        }

        @keyframes fadeInRight {
            from { transform: translateX(50px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ===== JUDUL ===== */
        .single-product-content h3 {
            font-size: 48px;
            font-weight: 800;
            color: #022e49;
            margin-bottom: 20px;
            line-height: 1.2;
            letter-spacing: -0.5px;
            text-shadow: 0 3px 6px rgba(0,0,0,0.06);
        }

        /* ===== HARGA ===== */
        .single-product-pricing {
            font-size: 42px;
            font-weight: 900;
            background: linear-gradient(90deg, #ff7c00, #ffb84d, #ff7c00);
            background-size: 200% auto;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            animation: shimmer 3s linear infinite;
            margin-bottom: 20px;
            letter-spacing: 1.5px;
        }

        @keyframes shimmer {
            0% { background-position: 0% center; }
            100% { background-position: 200% center; }
        }

        .single-product-pricing span {
            font-size: 22px;
            color: #aaa;
            text-decoration: line-through;
            margin-left: 12px;
        }

        /* ===== DESKRIPSI ===== */
        .single-product-content p {
            font-family: 'Merriweather', serif;
            font-size: 22px;
            color: #4a4a4a;
            line-height: 2;
            margin-bottom: 35px;
            text-align: justify;
        }

        /* ===== INPUT ===== */
        .single-product-form input[type="number"] {
            width: 90px;
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #ccc;
            font-size: 18px;
            margin-right: 15px;
            transition: border 0.3s ease, box-shadow 0.3s ease, transform 0.2s ease;
        }

        .single-product-form input[type="number"]:focus {
            border-color: #34be28;
            box-shadow: 0 0 10px rgba(52, 190, 40, 0.4);
            transform: scale(1.05);
        }

        /* ===== BUTTON ADD TO CART ===== */
        .cart-btn {
            background: linear-gradient(135deg, #34be28, #28a71f);
            color: white;
            padding: 16px 38px;
            border-radius: 50px;
            font-size: 18px;
            font-weight: 700;
            display: inline-block;
            box-shadow: 0 10px 25px rgba(52, 190, 40, 0.4);
            transition: all 0.35s ease;
            position: relative;
            overflow: hidden;
        }

        .cart-btn:hover {
            background: linear-gradient(135deg, #28a71f, #1f8a17);
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 35px rgba(52, 190, 40, 0.5);
        }
    </style>
</head>

<body>
    <!-- Navbar Start -->
        <nav class="navbar navbar-expand-lg navbar-light py-lg-0 px-lg-5 wow fadeIn" data-wow-delay="0.1s">
            <a href="index.html" class="navbar-brand ms-4 ms-lg-0">
                <h1 class="fw-bold text-primary m-0">Teh<span class="text-secondary">ki</span>ta</h1>
            </a>
            <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <div class="navbar-nav ms-auto p-4 p-lg-0">
                    <a href="index.php" class="nav-item nav-link active">Home</a>
                    <a href="about.php" class="nav-item nav-link">About Us</a>
                    <a href="product.php" class="nav-item nav-link">Product</a>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Pages</a>
                        <div class="dropdown-menu m-0">
                            <a href="testimonial.php" class="dropdown-item">Testimonial</a>
                            
                        </div>
                    </div>
                    <a href="contact.php" class="nav-item nav-link">Contact</a>
                </div>
                <div class="d-none d-lg-flex ms-2">
                    <!-- Search Start -->
                    <style>
                        /* Efek transisi muncul/hilang */
                        #searchInput {
                            width: 0;
                            opacity: 0;
                            padding: 5px 0;
                            border: 1px solid #ccc;
                            border-radius: 20px;
                            transition: all 0.3s ease;
                            overflow: hidden;
                        }
                        #searchInput.show {
                            width: 200px; /* ukuran lebar input */
                            opacity: 1;
                            padding: 5px 10px;
                        }
                    </style>

                    <form id="searchForm" action="product.php" method="get" style="display: inline-flex; align-items: center;">
                        <input type="text" id="searchInput" name="keyword" placeholder="Silahkan ketik produk...">
                        <button type="button" id="searchBtn" class="btn-sm-square bg-white rounded-circle ms-2">
                            <small class="fa fa-search text-body"></small> 
                        </button>
                    </form>

                    <script>
                        const searchBtn = document.getElementById("searchBtn");
                        const searchInput = document.getElementById("searchInput");
                        const searchForm = document.getElementById("searchForm");

                        let inputVisible = false;

                        searchBtn.addEventListener("click", function () {
                            inputVisible = !inputVisible;
                            if (inputVisible) {
                                searchInput.classList.add("show");
                                searchInput.focus();
                            } else {
                                searchInput.classList.remove("show");
                            }
                        });

                        // Tekan Enter langsung kirim form
                        searchInput.addEventListener("keypress", function (e) {
                            if (e.key === "Enter" && searchInput.value.trim() !== "") {
                                searchForm.submit();
                            }
                        });
                    </script>
                    <!-- Search End -->

                    <!-- User Icon Start -->
                    <?php
                    if (isset($_SESSION['admin'])) {
                        $link_user = "dashboard2.php"; // Admin ke dashboard
                    } elseif (isset($_SESSION['user'])) {   // ganti 'pelanggan' jadi 'user'
                        $link_user = "profil2.php";   // User ke profil
                    } else {
                        $link_user = "login.php";     // Belum login
                    }
                    ?>
                    <a class="btn-sm-square bg-white rounded-circle ms-3" href="<?= $link_user ?>">
                        <small class="fa fa-user text-body"></small>
                    </a>
                    <!-- User Icon End -->

                    <!-- Cart Start -->
                    <a class="btn-sm-square bg-white rounded-circle ms-3 position-relative" href="cart.php">
                        <small class="fa fa-shopping-bag text-body"></small>
                        <?php 
                        $total_items = 0;
                        if (!empty($_SESSION['cart'])) {
                            foreach ($_SESSION['cart'] as $item) {
                                $total_items += $item['qty'];
                            }
                        }
                        ?>
                        <?php if ($total_items > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?= $total_items ?>
                            </span>
                        <?php endif; ?>
                    </a>
                    <!-- Cart End -->
                </div>
            </div>
        </nav>
    </div>
    <!-- Navbar End -->

    <!-- Single Product -->
<div class="single-product mt-150 mb-150">
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-5">
                <div class="single-product-img">
                    <img src="img/<?= $data['foto'] ?>" 
                         class="bd-placeholder-img card-img-top" 
                         alt="<?= htmlspecialchars($data['nama']) ?>" />
                </div>
            </div>
            <div class="col-md-7">
                <div class="single-product-content">
                    <h3><?= htmlspecialchars($data['nama']) ?></h3>
                    <p class="single-product-pricing">
                        Rp. <?= number_format($data['harga'], 0, ',', '.') ?>
                    </p>
                    <p><?= htmlspecialchars($data['deskripsi']) ?></p>
                    
                    <div class="single-product-form">
                    <form action="add_to_cart.php" method="POST">
                        <!-- ✅ pakai name="id" bukan id_produk -->
                        <input type="hidden" name="id" value="<?= $data['id'] ?>">
                        <input type="number" name="qty" min="1" value="1" required style="width: 80px;">
                        
                        <button type="submit" class="cart-btn">
                            <i class="fas fa-shopping-cart"></i> Tambahkan
                        </button>
                    </form>
                </div>  
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- jQuery -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>

  


</body>
</html>
