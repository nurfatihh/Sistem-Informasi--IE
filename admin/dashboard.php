<?php
// admin/dashboard.php

require_once '../includes/config.php';
require_once '../includes/database.php';
require_once '../includes/functions.php';

// Check if admin is logged in
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: index.php');
    exit();
}

// Get statistics
$stats = [
    'total_dosen' => $db->query("SELECT COUNT(*) FROM dosen")->fetchColumn(),
    'total_mahasiswa' => $db->query("SELECT COUNT(*) FROM mahasiswa")->fetchColumn(),
    'total_penelitian' => $db->query("SELECT COUNT(*) FROM penelitian")->fetchColumn(),
    'total_prestasi' => $db->query("SELECT COUNT(*) FROM prestasi")->fetchColumn(),
    'total_berita' => $db->query("SELECT COUNT(*) FROM berita")->fetchColumn(),
    'total_gallery' => $db->query("SELECT COUNT(*) FROM gallery")->fetchColumn()
];

// Get recent activities
$recent_activities = $db->query("
    SELECT * FROM (
        SELECT 'berita' as type, judul, created_at FROM berita 
        UNION ALL
        SELECT 'prestasi' as type, judul, tanggal as created_at FROM prestasi
        UNION ALL
        SELECT 'penelitian' as type, judul, tanggal_mulai as created_at FROM penelitian
    ) AS combined
    ORDER BY created_at DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

// Get title for page
$page_title = "Dashboard";
?>

<?php include '../components/admin_header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include '../components/admin_sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <!-- Welcome Section -->
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-4 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#quickAddModal">
                        <i class="fas fa-plus"></i> Tambah Data
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row g-4 mb-4">
                <!-- Dosen Stats -->
                <div class="col-md-4 col-lg-2">
                    <div class="card text-white bg-primary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-chalkboard-teacher fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title mb-0">Total Dosen</h6>
                                    <h2 class="mt-2 mb-0"><?php echo $stats['total_dosen']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="dosen.php" class="text-white text-decoration-none small">
                                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mahasiswa Stats -->
                <div class="col-md-4 col-lg-2">
                    <div class="card text-white bg-success h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-user-graduate fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title mb-0">Total Mahasiswa</h6>
                                    <h2 class="mt-2 mb-0"><?php echo $stats['total_mahasiswa']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="mahasiswa.php" class="text-white text-decoration-none small">
                                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Penelitian Stats -->
                <div class="col-md-4 col-lg-2">
                    <div class="card text-white bg-info h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-flask fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title mb-0">Penelitian</h6>
                                    <h2 class="mt-2 mb-0"><?php echo $stats['total_penelitian']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="penelitian.php" class="text-white text-decoration-none small">
                                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Prestasi Stats -->
                <div class="col-md-4 col-lg-2">
                    <div class="card text-white bg-warning h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-trophy fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title mb-0">Prestasi</h6>
                                    <h2 class="mt-2 mb-0"><?php echo $stats['total_prestasi']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="prestasi.php" class="text-white text-decoration-none small">
                                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Berita Stats -->
                <div class="col-md-4 col-lg-2">
                    <div class="card text-white bg-danger h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-newspaper fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title mb-0">Berita</h6>
                                    <h2 class="mt-2 mb-0"><?php echo $stats['total_berita']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="berita.php" class="text-white text-decoration-none small">
                                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Gallery Stats -->
                <div class="col-md-4 col-lg-2">
                    <div class="card text-white bg-secondary h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-images fa-2x"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="card-title mb-0">Galeri</h6>
                                    <h2 class="mt-2 mb-0"><?php echo $stats['total_gallery']; ?></h2>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <a href="gallery.php" class="text-white text-decoration-none small">
                                Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities & Charts -->
            <div class="row">
                <!-- Recent Activities -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Aktivitas Terbaru</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <?php foreach ($recent_activities as $activity): ?>
                                    <div class="list-group-item">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1"><?php echo $activity['judul']; ?></h6>
                                            <small class="text-muted">
                                                <?php echo date('d M Y', strtotime($activity['created_at'])); ?>
                                            </small>
                                        </div>
                                        <small class="text-primary">
                                            <?php
                                            switch ($activity['type']) {
                                                case 'berita':
                                                    echo '<i class="fas fa-newspaper"></i> Berita';
                                                    break;
                                                case 'prestasi':
                                                    echo '<i class="fas fa-trophy"></i> Prestasi';
                                                    break;
                                                case 'penelitian':
                                                    echo '<i class="fas fa-flask"></i> Penelitian';
                                                    break;
                                            }
                                            ?>
                                        </small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart -->
                <div class="col-md-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Statistik Tahunan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="yearlyStats"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Quick Add Modal -->
<div class="modal fade" id="quickAddModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="d-grid gap-2">
                    <a href="dosen.php?action=add" class="btn btn-outline-primary">
                        <i class="fas fa-chalkboard-teacher me-2"></i> Tambah Dosen
                    </a>
                    <a href="berita.php?action=add" class="btn btn-outline-primary">
                        <i class="fas fa-newspaper me-2"></i> Tambah Berita
                    </a>
                    <a href="penelitian.php?action=add" class="btn btn-outline-primary">
                        <i class="fas fa-flask me-2"></i> Tambah Penelitian
                    </a>
                    <a href="prestasi.php?action=add" class="btn btn-outline-primary">
                        <i class="fas fa-trophy me-2"></i> Tambah Prestasi
                    </a>
                    <a href="gallery.php?action=add" class="btn btn-outline-primary">
                        <i class="fas fa-images me-2"></i> Tambah Galeri
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../components/admin_footer.php'; ?>

<!-- Initialize Charts -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Yearly Statistics Chart
        const ctx = document.getElementById('yearlyStats').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Berita',
                    data: [12, 19, 3, 5, 2, 3, 8, 12, 15, 10, 5, 7],
                    borderColor: '#dc3545',
                    tension: 0.1
                }, {
                    label: 'Prestasi',
                    data: [5, 10, 8, 15, 12, 9, 7, 11, 13, 8, 6, 9],
                    borderColor: '#ffc107',
                    tension: 0.1
                }, {
                    label: 'Penelitian',
                    data: [2, 4, 6, 8, 7, 5, 9, 11, 10, 8, 7, 5],
                    borderColor: '#0dcaf0',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Statistik Aktivitas 2024'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>