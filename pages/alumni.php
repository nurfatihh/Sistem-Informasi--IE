<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>
<main class="main">
    <div class="page-title dark-background aos-init aos-animate" data-aos="fade" style="background-image: url(assets/img/blog-page-title-bg.jpg);">
        <div class="container">
            <h1>Alumni</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="index.html">Home</a></li>
                    <li class="current">Alumni</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Blog Posts Section -->
                <section id="blog-posts" class="blog-posts section">
                    <div class="container">
                        <div class="row gy-4">
                            <!-- Alumni 1 -->
                            <div class="col-lg-6">
                                <article>
                                    <div class="post-img">
                                        <img src="assets/img/alumni/alumni-1.jpg" alt="Foto Alumni 1" class="img-fluid">
                                    </div>
                                    <h2 class="title">
                                        <a href="#">Nama: </a>
                                    </h2>
                                    <p>Tahun Kelulusan: 2020</p>
                                    <p>Prestasi: Juara 1 Olimpiade Matematika Nasional</p>
                                </article>
                            </div>

                            <!-- Alumni 2 -->
                            <div class="col-lg-6">
                                <article>
                                    <div class="post-img">
                                        <img src="assets/img/alumni/alumni-2.jpg" alt="Foto Alumni 2" class="img-fluid">
                                    </div>
                                    <h2 class="title">
                                        <a href="#">Nama: </a>
                                    </h2>
                                    <p>Tahun Kelulusan: 2018</p>
                                    <p>Prestasi: Founder Startup EduTech</p>
                                </article>
                            </div>
                        </div>
                    </div>
                </section><!-- /Blog Posts Section -->
            </div>
            <div class="col-lg-4 sidebar">
                <div class="widgets-container">
                    <div class="link-widget widget-item">
                        <h3 class="widget-title">Tambahkan Data Alumni</h3>
                        <p>Ingin menambahkan data Anda sebagai alumni? Klik tombol di bawah ini:</p>
                        <a href="https://forms.gle/your-google-form-link" target="_blank" class="btn btn-primary">Isi Formulir</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include '../components/footer.php';
    ?>