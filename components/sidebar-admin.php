<div class="sidebar">
    <div class="logo-details">
        <i class="fas fa-university"></i>
        <span class="logo_name">Admin Panel</span>
    </div>
    <ul class="nav-links">
        <li>
            <a href="dashboard.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span class="link_name">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="berita.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'berita.php') ? 'active' : ''; ?>">
                <i class="fas fa-newspaper"></i>
                <span class="link_name">Berita</span>
            </a>
        </li>
        <li>
            <a href="dosen.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'dosen.php') ? 'active' : ''; ?>">
                <i class="fas fa-chalkboard-teacher"></i>
                <span class="link_name">Dosen</span>
            </a>
        </li>
        <li>
            <a href="mahasiswa.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'mahasiswa.php') ? 'active' : ''; ?>">
                <i class="fas fa-user-graduate"></i>
                <span class="link_name">Mahasiswa</span>
            </a>
        </li>
        <li>
            <a href="matakuliah.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'matakuliah.php') ? 'active' : ''; ?>">
                <i class="fas fa-book"></i>
                <span class="link_name">Mata Kuliah</span>
            </a>
        </li>
        <li>
            <a href="kurikulum.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'kurikulum.php') ? 'active' : ''; ?>">
                <i class="fas fa-tasks"></i>
                <span class="link_name">Kurikulum</span>
            </a>
        </li>
        <li>
            <a href="prestasi.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'prestasi.php') ? 'active' : ''; ?>">
                <i class="fas fa-trophy"></i>
                <span class="link_name">Prestasi</span>
            </a>
        </li>
        <li>
            <a href="gallery.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'gallery.php') ? 'active' : ''; ?>">
                <i class="fas fa-images"></i>
                <span class="link_name">Galeri</span>
            </a>
        </li>
        <li>
            <a href="settings.php" class="<?php echo (basename($_SERVER['PHP_SELF']) == 'settings.php') ? 'active' : ''; ?>">
                <i class="fas fa-cog"></i>
                <span class="link_name">Pengaturan</span>
            </a>
        </li>
        <li class="log_out">
            <a href="logout.php">
                <i class="fas fa-sign-out-alt"></i>
                <span class="link_name">Keluar</span>
            </a>
        </li>
    </ul>
</div>