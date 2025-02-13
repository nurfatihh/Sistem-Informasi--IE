<?php
session_start();
require_once 'config/config.php';
require_once 'config/functions.php';

$pageTitle = "Teknik Informatika - Sistem Informasi";
$metaDescription = "Portal Sistem Informasi Jurusan Teknik Informatika - Menyediakan informasi akademik, penelitian, dan kegiatan mahasiswa";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $metaDescription ?>">
    <title><?= $pageTitle ?></title>

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="C:\xampp\htdocs\SISTEM INFORMASI-IE\assets\css\style.css">
    <link href=" https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.css" rel="stylesheet">

    <!-- Icons -->
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <?php include 'components/header.php'; ?>

    <!-- Navigation -->
    <?php include 'components/navbar.php'; ?>

    <!-- Bagian Hero -->
    <section class="bg-white">
        <div class="max-w-screen-xl px-4 py-8 mx-auto lg:py-16">
            <div class="grid gap-8 lg:grid-cols-12">
                <div class="lg:col-span-7">
                    <h1 class="mb-4 text-4xl font-extrabold tracking-tight text-gray-900 md:text-5xl">
                        Jurusan Teknik Informatika
                    </h1>
                    <p class="mb-6 text-lg text-gray-600">
                        Mencetak lulusan berkualitas yang siap bersaing di era digital, dengan
                        fokus pada pengembangan teknologi informasi, kecerdasan buatan, dan
                        inovasi teknologi masa depan.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="pendaftaran.php" class="inline-flex items-center px-6 py-3 text-base font-medium text-white bg-blue-700 rounded-lg hover:bg-blue-800">
                            Daftar Sekarang
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                        <a href="kurikulum.php" class="inline-flex items-center px-6 py-3 text-base font-medium text-blue-700 border border-blue-700 rounded-lg hover:bg-gray-100">
                            Lihat Kurikulum
                        </a>
                    </div>
                </div>
                <div class="hidden lg:col-span-5 lg:flex">
                    <img src="images/hero-image.jpg" alt="Suasana Pembelajaran di Lab" class="rounded-lg shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Statistik -->
    <section class="py-8 bg-blue-700">
        <div class="max-w-screen-xl px-4 mx-auto">
            <div class="grid grid-cols-2 gap-4 md:grid-cols-4">
                <div class="text-center text-white">
                    <h4 class="text-3xl font-bold">500+</h4>
                    <p>Mahasiswa Aktif</p>
                </div>
                <div class="text-center text-white">
                    <h4 class="text-3xl font-bold">50+</h4>
                    <p>Dosen Berkualitas</p>
                </div>
                <div class="text-center text-white">
                    <h4 class="text-3xl font-bold">15+</h4>
                    <p>Laboratorium</p>
                </div>
                <div class="text-center text-white">
                    <h4 class="text-3xl font-bold">90%</h4>
                    <p>Tingkat Keterserapan</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Program Unggulan -->
    <section class="py-12 bg-gray-50">
        <div class="max-w-screen-xl px-4 mx-auto">
            <h2 class="mb-8 text-3xl font-bold text-center text-gray-900">Program Unggulan</h2>
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <!-- Program 1 -->
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <div class="mb-4 text-blue-700">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-semibold text-gray-900">Rekayasa Perangkat Lunak</h3>
                    <p class="text-gray-600">Pengembangan aplikasi modern dengan teknologi terkini dan metodologi agile.</p>
                </div>

                <!-- Program 2 -->
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <div class="mb-4 text-blue-700">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-semibold text-gray-900">Kecerdasan Buatan</h3>
                    <p class="text-gray-600">Pembelajaran mendalam tentang AI, machine learning, dan data science.</p>
                </div>

                <!-- Program 3 -->
                <div class="p-6 bg-white rounded-lg shadow-lg">
                    <div class="mb-4 text-blue-700">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>
                    </div>
                    <h3 class="mb-3 text-xl font-semibold text-gray-900">Keamanan Siber</h3>
                    <p class="text-gray-600">Pendalaman aspek keamanan sistem dan jaringan komputer modern.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Berita Terkini -->
    <section class="py-12 bg-white">
        <div class="max-w-screen-xl px-4 mx-auto">
            <h2 class="mb-8 text-3xl font-bold text-gray-900">Berita Terkini</h2>
            <?php
            $berita_terkini = getBeritaTerkini(3); // Fungsi untuk mengambil 3 berita terbaru
            if ($berita_terkini): ?>
                <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                    <?php foreach ($berita_terkini as $berita): ?>
                        <article class="overflow-hidden rounded-lg shadow transition hover:shadow-lg">
                            <img
                                src="<?= $berita['gambar'] ?>"
                                alt="<?= $berita['judul'] ?>"
                                class="h-56 w-full object-cover">
                            <div class="bg-white p-4 sm:p-6">
                                <time datetime="<?= $berita['tanggal'] ?>" class="block text-xs text-gray-500">
                                    <?= formatTanggal($berita['tanggal']) ?>
                                </time>
                                <h3 class="mt-0.5 text-lg font-medium text-gray-900 hover:text-blue-700">
                                    <a href="berita.php?id=<?= $berita['id'] ?>">
                                        <?= $berita['judul'] ?>
                                    </a>
                                </h3>
                                <p class="mt-2 line-clamp-3 text-sm/relaxed text-gray-600">
                                    <?= $berita['ringkasan'] ?>
                                </p>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
                <div class="mt-8 text-center">
                    <a href="berita.php" class="inline-flex items-center px-6 py-3 text-base font-medium text-blue-700 border border-blue-700 rounded-lg hover:bg-gray-100">
                        Lihat Semua Berita
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </section>



    <!-- Footer -->
    <?php include 'components/footer.php'; ?>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>