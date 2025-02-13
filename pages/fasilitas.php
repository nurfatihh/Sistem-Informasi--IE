<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>

<main class="main">
    <div class="page-title dark-background aos-init aos-animate" data-aos="fade" style="background-image: url(/assets/img/tanry-abeng.jpg);">
        <div class="container">
            <h1>Fasilitas Kampus</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="index.html">Home</a></li>
                    <li class="current">Fasilitas</li>
                </ol>
            </nav>
        </div>
    </div>
    <h2>Ayo, Lihat Fasilitas TAU<br><br>
        <section id="fasilitas" class="fasilitas section">
            <div class="container">
                <div class="row gy-4">
                    <?php
                    $sections = [
                        "Fasilitas Umum" => [
                            ["Big Chess Board", "Fa1.jpg"],
                            ["TAU Bridge", "Fa2.jpg"],
                            ["Lorong Kelas", "Fa3.jpg"],
                            ["Gazebo", "Fa4.jpg"],
                            ["Mini Gymnasium", "Fa5.jpg"],
                            ["Mini Gymnasium", "Fa6.jpg"],
                        ],
                        "Taman" => [
                            ["Taman Belakang", "Taman1.jpg"],
                            ["Taman Depan", "Taman2.jpg"],
                            ["Kursi Taman", "Taman3.jpg"],
                        ],
                        "Lab Komputer" => [
                            ["Lab Komputer 1", "Lab1.jpg"],
                            ["Lab Komputer 2", "Lab2.jpg"],
                            ["Lab Komputer 3", "Lab5.jpg"],
                        ],
                        "Kelas" => [
                            ["Kelas 1", "Kelas1.jpg"],
                            ["Kelas 2", "Kelas2.jpg"],
                            ["Kelas 3", "Kelas3.jpg"],
                        ],
                        "Rooftop Swimming Pool" => [
                            ["Kolam Renang 1", "Kolamrenang1.jpg"],
                            ["Kolam Renang 2", "Kolamrenang2.jpg"],
                        ],
                        "Lapangan Futsal" => [
                            ["Lapangan Futsal 1", "Lapangan Futsal.jpg"],
                            ["Lapangan Futsal 2", "Lapangan Futsal 2.jpg"],
                        ],
                        "Gedung TAU" => [
                            ["Gedung 1", "Gedung1.jpg"],
                            ["Gedung 2", "Gedung2.jpg"],
                        ],
                        "Lapangan Tenis" => [
                            ["Lapangan Tenis", "Lapangan Tenis.jpg"],
                        ]
                    ];

                    foreach ($sections as $sectionTitle => $items) {
                        echo "<div class='col-12'><h2>$sectionTitle</h2></div>";
                        foreach ($items as $index => $item) {
                            echo "<div class='col-lg-3 col-md-6 d-flex align-items-stretch aos-init aos-animate' data-aos='fade-up' data-aos-delay='" . ($index + 1) * 100 . "'>";
                            echo "<div class='fasilitas-item'>";
                            echo "<div class='item-img'>";
                            echo "<img src='Image/{$item[1]}' class='img-fluid' alt='{$item[0]}'>";
                            echo "</div>";
                            echo "<div class='item-info'>";
                            echo "<h4>{$item[0]}</h4>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>
            </div>
        </section>
</main>

<?php include '../components/footer.php'; ?>