<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

        <a href="coba.php" class="logo d-flex align-items-center">
            <h1 class="sitename">Informatics Engineering</h1>
        </a>
        <nav id="navmenu" class="navmenu">
            <ul>
                <li><a href="../coba.php" class="active">Home<br></a></li>
                <li><a href="../pages/dosen.php">Dosen</a></li>
                <li><a href="../pages/karir.php">Karir</a></li>
                <li><a href="../pages/alumni.php">Alumni</a></li>
                <li><a href="../pages/mahasiswa.php">Mahasiswa</a></li>
                <li><a href="../pages/kontak.php">Contact</a></li>
            </ul>
            <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
        </nav>

        <a href="#about" class="btn-get-started">Login Admin</a>

    </div>
</header>