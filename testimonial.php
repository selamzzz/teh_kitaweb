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
</head>

<body>
    <!-- Spinner Start -->
    <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
        <div class="spinner-border text-primary" role="status"></div>
    </div>
    <!-- Spinner End -->
<!-- Navbar Start -->
    <div class="container-fluid fixed-top px-0 wow fadeIn" data-wow-delay="0.1s">
        <div class="top-bar row gx-0 align-items-center d-none d-lg-flex">
            <div class="col-lg-6 px-5 text-start">
                <small><i class="fa fa-map-marker-alt me-2"></i>Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat</small>
                <small class="ms-4"><i class="fa fa-envelope me-2"></i>tehkita@point</small>
            </div>
            <div class="col-lg-6 px-5 text-end">
                <small>Follow:</small>
                <a class="text-body ms-3" href=""><i class="fab fa-facebook-f"></i></a>
                <a class="text-body ms-3" href=""><i class="fab fa-linkedin-in"></i></a>
                <a class="text-body ms-3" href="https://www.instagram.com/tehkita.point?igsh=MXEyaWIzMndqajZuag=="><i class="fab fa-instagram"></i></a>
            </div>
        </div>

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
                            <a href="404.php" class="dropdown-item">404 Page</a>
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
                                $total_items += $item['jumlah'];
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


   <!-- Page Header Start -->
<div class="container-fluid page-header wow fadeIn" data-wow-delay="0.1s">
    <div class="container text-center">
        <!-- Judul -->
        <h1 class="display-3 fw-bold mb-3 text-light text-shadow animated slideInDown">
            <span class="text-primary">Our</span> <span class="text-warning">Testimonial</span>
        </h1>

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0">
                <li class="breadcrumb-item"><a class="text-primary" href="#">Home</a></li>
                <li class="breadcrumb-item text-warning active" aria-current="page">Testimonial</li>
            </ol>
        </nav>
    </div>
</div>
<!-- Page Header End -->



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
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn btn-square btn-outline-light rounded-circle me-1" href=""><i class="fab fa-whatsapp"></i></a>
                        
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
                    <a class="btn btn-link" href="">About Us</a>
                    <a class="btn btn-link" href="">Contact</a>
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