<?php include '../components/header.php'; ?>
<?php include '../components/navbar.php'; ?>
<main class="main">
    <div class="page-title dark-background aos-init aos-animate" data-aos="fade" style="background-image: url(assets/img/blog-page-title-bg.jpg);">
        <div class="container">
            <h1>Daftar Mahasiswa</h1>
            <nav class="breadcrumbs">
                <ol>
                    <li><a href="../coba.php">Home</a></li>
                    <li class="current">Mahasiswa</li>
                </ol>
            </nav>
        </div>
    </div>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Program Studi Teknik Informatika</h1>
        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari Nama Mahasiswa..." onkeyup="searchFunction()">
        <table class="table table-bordered" id="mahasiswaTable">
            <thead class="table-success">
                <tr>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2021001</td>
                    <td>Ahmad Rizky</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>2021002</td>
                    <td>Siti Aisyah</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>2021003</td>
                    <td>Budi Santoso</td>
                    <td>Cuti</td>
                </tr>
                <tr>
                    <td>2021004</td>
                    <td>Indah Permatasari</td>
                    <td>Aktif</td>
                </tr>
                <tr>
                    <td>2021005</td>
                    <td>Joko Widodo</td>
                    <td>Non-Aktif</td>
                </tr>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <!-- Pagination logic can be added here -->
            </ul>
        </nav>
    </div>   

    <?php
    include '../components/footer.php';
    ?>
