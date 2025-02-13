<?php
require_once '../config/config.php';

// Cek login admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

// Fungsi untuk mendapatkan semua data dosen
function getAllDosen($db)
{
    try {
        $query = "SELECT * FROM dosen ORDER BY nama_lengkap ASC";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Fungsi untuk mendapatkan detail dosen berdasarkan ID
function getDosenById($db, $id)
{
    try {
        $query = "SELECT * FROM dosen WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

// Fungsi untuk menambah dosen baru
function addDosen($db, $data, $foto = null)
{
    try {
        $query = "INSERT INTO dosen (nip, nama_lengkap, gelar_depan, gelar_belakang, email, 
                                   telepon, bidang_keahlian, pendidikan_terakhir, foto, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->execute([
            $data['nip'],
            $data['nama_lengkap'],
            $data['gelar_depan'],
            $data['gelar_belakang'],
            $data['email'],
            $data['telepon'],
            $data['bidang_keahlian'],
            $data['pendidikan_terakhir'],
            $foto,
            $data['status']
        ]);

        return $db->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

// Fungsi untuk mengupdate data dosen
function updateDosen($db, $id, $data, $foto = null)
{
    try {
        $fields = [
            'nip = ?',
            'nama_lengkap = ?',
            'gelar_depan = ?',
            'gelar_belakang = ?',
            'email = ?',
            'telepon = ?',
            'bidang_keahlian = ?',
            'pendidikan_terakhir = ?',
            'status = ?'
        ];

        $values = [
            $data['nip'],
            $data['nama_lengkap'],
            $data['gelar_depan'],
            $data['gelar_belakang'],
            $data['email'],
            $data['telepon'],
            $data['bidang_keahlian'],
            $data['pendidikan_terakhir'],
            $data['status']
        ];

        if ($foto) {
            $fields[] = 'foto = ?';
            $values[] = $foto;
        }

        $values[] = $id; // Untuk WHERE clause

        $query = "UPDATE dosen SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $db->prepare($query);

        return $stmt->execute($values);
    } catch (PDOException $e) {
        return false;
    }
}

// Fungsi untuk menghapus dosen
function deleteDosen($db, $id)
{
    try {
        // Dapatkan info foto sebelum menghapus
        $stmt = $db->prepare("SELECT foto FROM dosen WHERE id = ?");
        $stmt->execute([$id]);
        $dosen = $stmt->fetch();

        // Hapus foto jika ada
        if ($dosen && $dosen['foto']) {
            $foto_path = '../uploaded_img/dosen/' . $dosen['foto'];
            if (file_exists($foto_path)) {
                unlink($foto_path);
            }
        }

        // Hapus data dosen
        $query = "DELETE FROM dosen WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        return false;
    }
}

$error = '';
$success = '';
$dosen = null;
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nip' => trim($_POST['nip']),
        'nama_lengkap' => trim($_POST['nama_lengkap']),
        'gelar_depan' => trim($_POST['gelar_depan']),
        'gelar_belakang' => trim($_POST['gelar_belakang']),
        'email' => trim($_POST['email']),
        'telepon' => trim($_POST['telepon']),
        'bidang_keahlian' => trim($_POST['bidang_keahlian']),
        'pendidikan_terakhir' => trim($_POST['pendidikan_terakhir']),
        'status' => $_POST['status']
    ];

    // Validasi input
    if (empty($data['nip']) || empty($data['nama_lengkap']) || empty($data['email'])) {
        $error = "NIP, Nama Lengkap, dan Email wajib diisi!";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } else {
        // Proses upload foto jika ada
        $foto = null;
        if (!empty($_FILES['foto']['name'])) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB

            if (!in_array($_FILES['foto']['type'], $allowed_types)) {
                $error = "Tipe file tidak didukung. Gunakan JPG, PNG, atau GIF.";
            } elseif ($_FILES['foto']['size'] > $max_size) {
                $error = "Ukuran file terlalu besar. Maksimal 5MB.";
            } else {
                $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                $foto = uniqid() . '.' . $extension;
                $target_path = '../uploaded_img/dosen/' . $foto;

                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_path)) {
                    $error = "Gagal mengupload foto.";
                }
            }
        }

        // Jika tidak ada error, proses simpan data
        if (empty($error)) {
            if ($action == 'add') {
                // Tambah dosen baru
                if (addDosen($db, $data, $foto)) {
                    $success = "Data dosen berhasil ditambahkan!";
                    // Redirect setelah 2 detik
                    header("refresh:2;url=dosen.php");
                } else {
                    $error = "Gagal menambahkan data dosen!";
                    // Hapus foto jika upload gagal
                    if ($foto && file_exists('../uploaded_img/dosen/' . $foto)) {
                        unlink('../uploaded_img/dosen/' . $foto);
                    }
                }
            } elseif ($action == 'edit' && $id > 0) {
                // Update data dosen
                if (updateDosen($db, $id, $data, $foto)) {
                    $success = "Data dosen berhasil diperbarui!";
                    // Redirect setelah 2 detik
                    header("refresh:2;url=dosen.php");
                } else {
                    $error = "Gagal memperbarui data dosen!";
                    // Hapus foto jika upload gagal
                    if ($foto && file_exists('../uploaded_img/dosen/' . $foto)) {
                        unlink('../uploaded_img/dosen/' . $foto);
                    }
                }
            }
        }
    }
}

// Proses delete
if ($action == 'delete' && $id > 0) {
    if (deleteDosen($db, $id)) {
        $success = "Data dosen berhasil dihapus!";
        // Redirect setelah 2 detik
        header("refresh:2;url=dosen.php");
    } else {
        $error = "Gagal menghapus data dosen!";
    }
}

// Get data dosen untuk edit
if ($action == 'edit' && $id > 0) {
    $dosen = getDosenById($db, $id);
    if (!$dosen) {
        $error = "Data dosen tidak ditemukan!";
        $action = 'list';
    }
}

// Get semua data dosen untuk list
$all_dosen = [];
if ($action == 'list') {
    $all_dosen = getAllDosen($db);
}

include '../components/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <?php include '../components/admin_sidebar.php'; ?>

        <!-- Main content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3 border-bottom">
                <h1 class="h2"><?php echo $action == 'list' ? 'Daftar Dosen' : ($action == 'add' ? 'Tambah Dosen' : 'Edit Dosen'); ?></h1>
                <?php if ($action == 'list'): ?>
                    <a href="?action=add" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Dosen
                    </a>
                <?php endif; ?>
            </div>

            <!-- Alert Messages -->
            <?php if ($error): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if ($action == 'list'): ?>
                <!-- Tabel Daftar Dosen -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover datatable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Foto</th>
                                        <th>NIP</th>
                                        <th>Nama Lengkap</th>
                                        <th>Email</th>
                                        <th>Bidang Keahlian</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($all_dosen as $index => $d): ?>
                                        <tr>
                                            <td><?php echo $index + 1; ?></td>
                                            <td>
                                                <?php if ($d['foto']): ?>
                                                    <img src="../uploaded_img/dosen/<?php echo $d['foto']; ?>"
                                                        alt="Foto <?php echo $d['nama_lengkap']; ?>"
                                                        class="rounded-circle" width="50" height="50">
                                                <?php else: ?>
                                                    <img src="../images/default-profile.jpg"
                                                        alt="Default" class="rounded-circle"
                                                        width="50" height="50">
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo $d['nip']; ?></td>
                                            <td>
                                                <?php
                                                echo $d['gelar_depan'] ? $d['gelar_depan'] . ' ' : '';
                                                echo $d['nama_lengkap'];
                                                echo $d['gelar_belakang'] ? ', ' . $d['gelar_belakang'] : '';
                                                ?>
                                            </td>
                                            <td><?php echo $d['email']; ?></td>
                                            <td><?php echo $d['bidang_keahlian']; ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo $d['status'] == 'aktif' ? 'success' : 'danger'; ?>">
                                                    <?php echo ucfirst($d['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?action=edit&id=<?php echo $d['id']; ?>"
                                                    class="btn btn-sm btn-primary" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="javascript:void(0);"
                                                    onclick="confirmDelete(<?php echo $d['id']; ?>)"
                                                    class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Form Tambah/Edit Dosen -->
                <div class="card">
                    <div class="card-body">
                        <form action="?action=<?php echo $action; ?><?php echo $action == 'edit' ? '&id=' . $id : ''; ?>"
                            method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">NIP <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nip"
                                        value="<?php echo isset($dosen['nip']) ? $dosen['nip'] : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nama_lengkap"
                                        value="<?php echo isset($dosen['nama_lengkap']) ? $dosen['nama_lengkap'] : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gelar Depan</label>
                                    <input type="text" class="form-control" name="gelar_depan"
                                        value="<?php echo isset($dosen['gelar_depan']) ? $dosen['gelar_depan'] : ''; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Gelar Belakang</label>
                                    <input type="text" class="form-control" name="gelar_belakang"
                                        value="<?php echo isset($dosen['gelar_belakang']) ? $dosen['gelar_belakang'] : ''; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email"
                                        value="<?php echo isset($dosen['email']) ? $dosen['email'] : ''; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Telepon</label>
                                    <input type="text" class="form-control" name="telepon"
                                        value="<?php echo isset($dosen['telepon']) ? $dosen['telepon'] : ''; ?>">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Bidang Keahlian</label>
                                    <input type="text" class="form-control" name="bidang_keahlian"
                                        value="<?php echo isset($dosen['bidang_keahlian']) ? $dosen['bidang_keahlian'] : ''; ?>">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pendidikan Terakhir <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="pendidikan_terakhir"
                                        value="<?php echo isset($dosen['pendidikan_terakhir']) ? $dosen['pendidikan_terakhir'] : ''; ?>" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status">
                                        <option value="aktif" <?php echo (isset($dosen['status']) && $dosen['status'] == 'aktif') ? 'selected' : ''; ?>>Aktif</option>
                                        <option value="tidak aktif" <?php echo (isset($dosen['status']) && $dosen['status'] == 'tidak aktif') ? 'selected' : ''; ?>>Tidak Aktif</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Foto</label>
                                    <?php if (isset($dosen['foto']) && $dosen['foto']): ?>
                                        <div class="mb-2">
                                            <img src="../uploaded_img/dosen/<?php echo $dosen['foto']; ?>"
                                                alt="Current Photo" class="img-thumbnail" width="100">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="foto" accept="image/*">
                                    <small class="text-muted">Upload foto baru jika ingin mengubah foto</small>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="dosen.php" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <?php echo $action == 'add' ? 'Tambah' : 'Update'; ?> Dosen
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php include '../components/admin_footer.php'; ?>

<script>
    // Konfirmasi delete
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `?action=delete&id=${id}`;
            }
        });
    }

    // Initialize DataTable
    $(document).ready(function() {
        $('.datatable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/id.json'
            }
        });
    });
</script>