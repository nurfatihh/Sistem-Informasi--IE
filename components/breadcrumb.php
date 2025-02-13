<?php
function generateBreadcrumb($currentPage)
{
    $breadcrumbs = [
        'home' => 'Beranda',
        'profil' => 'Profil',
        'dosen' => 'Dosen',
        'kurikulum' => 'Kurikulum',
        'fasilitas' => 'Fasilitas',
        'gallery' => 'Galeri',
        'prestasi' => 'Prestasi',
        'kegiatan' => 'Kegiatan',
        'kontak' => 'Kontak'
    ];
?>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/"><i class="fas fa-home"></i></a></li>
            <?php if (isset($breadcrumbs[$currentPage])): ?>
                <li class="breadcrumb-item active" aria-current="page"><?php echo $breadcrumbs[$currentPage]; ?></li>
            <?php endif; ?>
        </ol>
    </nav>
<?php
}
?>