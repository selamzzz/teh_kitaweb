<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lokasi Outlet TEH KITA di Cirebon</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ffffff; /* Latar belakang putih */
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
        }
        
        .search-box:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(59, 246, 65, 0.5);
        }

        .filter-btn {
            transition: background-color 0.3s, color 0.3s;
        }

        .filter-btn.active {
            background-color: #059669; /* Warna hijau saat aktif */
            color: white;
        }

        .filter-btn:not(.active):hover {
            background-color: #bbf7d0; /* Warna hijau muda saat hover */
        }

        .bg-card {
            background-color: rgba(255, 255, 255, 0.9); /* Putih transparan untuk kartu outlet */
        }
    </style>
</head>

<body class="min-h-screen">
    
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation Bar -->
        <nav class="flex justify-between items-center mb-8">
            <div class="flex space-x-4">
                <a href="index.php" class="text-gray-800 hover:text-blue-600">Home</a>
                <a href="about.php" class="text-gray-800 hover:text-blue-600">About</a>
                <a href="product.php" class="text-gray-800 hover:text-blue-600">Products</a>
                <a href="contact.php" class="text-gray-800 hover:text-blue-600">Contact</a>
            </div>
            <div class="flex space-x-4">
                <a href="profil2.php" class="text-gray-800 hover:text-blue-600">
                    <i class="fas fa-user"></i>
                </a>
                <a href="cart.php" class="text-gray-800 hover:text-blue-600">
                    <i class="fas fa-shopping-bag"></i>
                </a>
            </div>
        </nav>

        <!-- Header -->
        <header class="text-center mb-12">
            <div class="flex justify-center mb-6">
                <img src="img/logo.jpg" alt="Logo TEH KITA" class="h-20">
            </div>
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Lokasi Outlet TEH KITA Cirebon</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Temukan outlet TEH KITA yang tersebar di berbagai wilayah Cirebon.
            </p>
            
            <!-- Search Box -->
            <div class="mt-8 max-w-md mx-auto relative">
                <input type="text" id="searchInput" placeholder="Cari outlet..." 
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-blue-500 search-box transition duration-300">
                <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
            </div>
        </header>
        
        <!-- Outlet Filter -->
        <div class="mb-8 flex justify-center space-x-2 flex-wrap">
            <button data-filter="all" class="filter-btn px-4 py-2 bg-gray-200 rounded-full">Semua</button>
            <button data-filter="tengah" class="filter-btn px-4 py-2 bg-gray-200 rounded-full">Kota Cirebon</button>
            <button data-filter="barat" class="filter-btn px-4 py-2 bg-gray-200 rounded-full">Wilayah Barat</button>
            <button data-filter="timur" class="filter-btn px-4 py-2 bg-gray-200 rounded-full">Wilayah Timur</button>
            <button data-filter="utara" class="filter-btn px-4 py-2 bg-gray-200 rounded-full">Wilayah Utara</button>
            <button data-filter="selatan" class="filter-btn px-4 py-2 bg-gray-200 rounded-full">Wilayah Selatan</button>      
        </div>
        
        <!-- Outlet Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8" id="outletContainer">
            <!-- Outlet 1 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="tengah">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-1.jpg" alt="Outlet TEH KITA Perumnas" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA PERUMNAS</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Jl. Ciremai Raya, Larangan, Kec. Harjamukti, Kota Cirebon, Jawa Barat
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Kota Cirebon</span>
                        <a href="https://maps.app.goo.gl/VjPfPgVwJC75qkie6?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
            <!-- Outlet 2 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="barat">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-2.jpg" alt="Outlet TEH KITA ARUM" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA ARUMSARI</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Jl. Jembatan Merah No.18, Kecomberan, Kec. Talun, Kabupaten Cirebon, Jawa Barat
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Wilayah Barat</span>
                        <a href="https://maps.app.goo.gl/3xjh6PARRWSaagqb8?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Outlet 3 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="barat">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-3.jpg" alt="Outlet TEH KITA KLANGENAN" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA KLANGENAN</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Jl. Nyimas Endang Geulis, Jemaras Kidul, Kec. Klangenan, Kabupaten Cirebon, Jawa Barat 
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Wilayah Barat</span>
                        <a href="https://maps.app.goo.gl/9hegGvH82bn2jsYq6?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Outlet 4 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="barat">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-4.jpg" alt="Outlet TEH KITA PLUMBON" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA PLUMBON</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Jl. Mertabasah, Pamijahan, Kec. Plumbon, Kabupaten Cirebon, Jawa Barat 45155
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Wilayah Barat</span>
                        <a href="https://maps.app.goo.gl/nF4k6ktLJdXW6RuM7?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Outlet 5 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="barat">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-5.jpg" alt="Outlet TEH KITA PALIMANAN" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA PALIMANAN</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        7CMP+244, Semplo, Kec. Palimanan, Kabupaten Cirebon, Jawa Barat
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Wilayah Barat</span>
                        <a href="https://maps.app.goo.gl/u8n7GwT5KQZqPSx89?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Outlet 6 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="timur">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-6.jpg" alt="Outlet TEH KITA BUNTET" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA BUNTET</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Depan spbu buntet, Jl. Kh. Wahid Hasyim No.31, Mertapada Wetan, Kec. Astanajapura, Kabupaten Cirebon, Jawa Barat 45181
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Wilayah Timur</span>
                        <a href="https://maps.app.goo.gl/xWTvHr5Q1mge5Xyx9?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Outlet 7 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="timur">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-7.jpg" alt="Outlet TEH KITA KAROMAH" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA KAROMAH</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        5JF9+3W2, Jl. Kh. Wahid Hasyim, Cipeujeuh Wetan, Kec. Lemahabang, Kabupaten Cirebon, Jawa Barat 45183
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Wilayah Timur</span>
                        <a href="https://maps.app.goo.gl/xWTvHr5Q1mge5Xyx9?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Outlet 8 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="timur">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-8.jpg" alt="Outlet TEH KITA PABUARAN" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA PABUARAN</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        3PVJ+J4X, Pabuaran Kidul, Kec. Pabuaran, Kabupaten Cirebon, Jawa Barat 45188
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Wilayah Timur</span>
                        <a href="https://maps.app.goo.gl/oAcACi8wqiQMaoq59?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Outlet 9 -->
            <div class="bg-card rounded-xl overflow-hidden shadow-lg card-hover transition duration-300" data-region="timur">
                <div class="h-48 overflow-hidden">
                    <img src="img/outlet-9.jpg" alt="Outlet TEH KITA LEMAHABANG" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">TEH KITA LEMAHABANG</h3>
                    <p class="text-gray-600 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                        Jl. MT Haryono No.226, Cipeujeuh Wetan, Kec. Lemahabang, Kabupaten Cirebon, Jawa Barat 45183
                    </p>
                    <div class="flex justify-between items-center">
                        <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">Wilayah Timur</span>
                        <a href="https://maps.app.goo.gl/sNDs6WL5UbDFqvJ59?g_st=iw" target="_blank" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-directions mr-1"></i> Arah
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Filter Outlet by Region
    document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', () => {
            const filter = button.getAttribute('data-filter');
            
            // Update active button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white');
                btn.classList.add('bg-gray-200');
            });
            
            button.classList.add('bg-blue-600', 'text-white');
            button.classList.remove('bg-gray-200');
            
            // Filter outlets
            const outlets = document.querySelectorAll('#outletContainer > div');
            outlets.forEach(outlet => {
                if (filter === 'all' || outlet.getAttribute('data-region') === filter) {
                    outlet.style.display = 'block';
                } else {
                    outlet.style.display = 'none';
                }
            });
        });
    });
</script>
</body>
</html>