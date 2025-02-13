<?php
// admin/mahasiswa.php - Bagian 1: Inisialisasi dan fungsi dasar
require_once '../config/config.php';

// Cek login admin
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit();
}

// Fungsi untuk mendapatkan semua data mahasiswa
function getAllMahasiswa($db)
{
    try {
        $query = "SELECT * FROM mahasiswa ORDER BY angkatan DESC, nama_lengkap ASC";
        $stmt = $db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return [];
    }
}

// Fungsi untuk mendapatkan detail mahasiswa berdasarkan ID
function getMahasiswaById($db, $id)
{
    try {
        $query = "SELECT * FROM mahasiswa WHERE id = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

// Fungsi untuk menambah mahasiswa baru
function addMahasiswa($db, $data, $foto = null)
{
    try {
        $query = "INSERT INTO mahasiswa (nim, nama_lengkap, angkatan, email, 
                                       telepon, alamat, foto, status) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $db->prepare($query);
        $stmt->execute([
            $data['nim'],
            $data['nama_lengkap'],
            $data['angkatan'],
            $data['email'],
            $data['telepon'],
            $data['alamat'],
            $foto,
            $data['status']
        ]);

        return $db->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

// Fungsi untuk mengupdate data mahasiswa
function updateMahasiswa($db, $id, $data, $foto = null)
{
    try {
        $fields = [
            'nim = ?',
            'nama_lengkap = ?',
            'angkatan = ?',
            'email = ?',
            'telepon = ?',
            'alamat = ?',
            'status = ?'
        ];

        $values = [
            $data['nim'],
            $data['nama_lengkap'],
            $data['angkatan'],
            $data['email'],
            $data['telepon'],
            $data['alamat'],
            $data['status']
        ];

        if ($foto) {
            $fields[] = 'foto = ?';
            $values[] = $foto;
        }

        $values[] = $id; // Untuk WHERE clause

        $query = "UPDATE mahasiswa SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $db->prepare($query);

        return $stmt->execute($values);
    } catch (PDOException $e) {
        return false;
    }
}

// Fungsi untuk menghapus mahasiswa
function deleteMahasiswa($db, $id)
{
    try {
        // Dapatkan info foto sebelum menghapus
        $stmt = $db->prepare("SELECT foto FROM mahasiswa WHERE id = ?");
        $stmt->execute([$id]);
        $mahasiswa = $stmt->fetch();

        // Hapus foto jika ada
        if ($mahasiswa && $mahasiswa['foto']) {
            $foto_path = '../uploaded_img/mahasiswa/' . $mahasiswa['foto'];
            if (file_exists($foto_path)) {
                unlink($foto_path);
            }
        }

        // Hapus data mahasiswa
        $query = "DELETE FROM mahasiswa WHERE id = ?";
        $stmt = $db->prepare($query);
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        return false;
    }
}

// Fungsi untuk mendapatkan statistik mahasiswa
function getMahasiswaStats($db)
{
    try {
        $stats = [];

        // Total mahasiswa aktif
        $query = "SELECT COUNT(*) FROM mahasiswa WHERE status = 'aktif'";
        $stats['total_aktif'] = $db->query($query)->fetchColumn();

        // Total per angkatan
        $query = "SELECT angkatan, COUNT(*) as total FROM mahasiswa GROUP BY angkatan ORDER BY angkatan DESC";
        $stats['per_angkatan'] = $db->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);

        // Total per status
        $query = "SELECT status, COUNT(*) as total FROM mahasiswa GROUP BY status";
        $stats['per_status'] = $db->query($query)->fetchAll(PDO::FETCH_KEY_PAIR);

        return $stats;
    } catch (PDOException $e) {
        return [];
    }
}

// Fungsi untuk validasi NIM
function isNIMExists($db, $nim, $exclude_id = null)
{
    try {
        $query = "SELECT COUNT(*) FROM mahasiswa WHERE nim = ?";
        $params = [$nim];

        if ($exclude_id) {
            $query .= " AND id != ?";
            $params[] = $exclude_id;
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    } catch (PDOException $e) {
        return false;
    }
}

$error = '';
$success = '';
$mahasiswa = null;
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Proses form submit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = [
        'nim' => trim($_POST['nim']),
        'nama_lengkap' => trim($_POST['nama_lengkap']),
        'angkatan' => (int)$_POST['angkatan'],
        'email' => trim($_POST['email']),
        'telepon' => trim($_POST['telepon']),
        'alamat' => trim($_POST['alamat']),
        'status' => $_POST['status']
    ];

    // Validasi input
    if (empty($data['nim']) || empty($data['nama_lengkap']) || empty($data['email'])) {
        $error = "NIM, Nama Lengkap, dan Email wajib diisi!";
    } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid!";
    } elseif ($data['angkatan'] < 2000 || $data['angkatan'] > date('Y')) {
        $error = "Tahun angkatan tidak valid!";
    } elseif ($action == 'add' && isNIMExists($db, $data['nim'])) {
        $error = "NIM sudah terdaftar!";
    } elseif ($action == 'edit' && isNIMExists($db, $data['nim'], $id)) {
        $error = "NIM sudah terdaftar untuk mahasiswa lain!";
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
                $target_path = '../uploaded_img/mahasiswa/' . $foto;

                // Buat direktori jika belum ada
                if (!is_dir('../uploaded_img/mahasiswa')) {
                    mkdir('../uploaded_img/mahasiswa', 0777, true);
                }

                if (!move_uploaded_file($_FILES['foto']['tmp_name'], $target_path)) {
                    $error = "Gagal mengupload foto.";
                }
            }
        }

        // Jika tidak ada error, proses simpan data
        if (empty($error)) {
            if ($action == 'add') {
                // Tambah mahasiswa baru
                if (addMahasiswa($db, $data, $foto)) {
                    $success = "Data mahasiswa berhasil ditambahkan!";
                    // Redirect setelah 2 detik
                    header("refresh:2;url=mahasiswa.php");
                } else {
                    $error = "Gagal menambahkan data mahasiswa!";
                    // Hapus foto jika upload gagal
                    if ($foto && file_exists('../uploaded_img/mahasiswa/' . $foto)) {
                        unlink('../uploaded_img/mahasiswa/' . $foto);
                    }
                }
            } elseif ($action == 'edit' && $id > 0) {
                // Update data mahasiswa
                if (updateMahasiswa($db, $id, $data, $foto)) {
                    // Jika ada foto lama dan upload foto baru berhasil, hapus foto lama
                    if ($foto && isset($_POST['old_foto']) && file_exists('../uploaded_img/mahasiswa/' . $_POST['old_foto'])) {
                        unlink('../uploaded_img/mahasiswa/' . $_POST['old_foto']);
                    }

                    $success = "Data mahasiswa berhasil diperbarui!";
                    // Redirect setelah 2 detik
                    header("refresh:2;url=mahasiswa.php");
                } else {
                    $error = "Gagal memperbarui data mahasiswa!";
                    // Hapus foto jika upload gagal
                    if ($foto && file_exists('../uploaded_img/mahasiswa/' . $foto)) {
                        unlink('../uploaded_img/mahasiswa/' . $foto);
                    }
                }
            }
        }
    }
}

// Proses delete
if ($action == 'delete' && $id > 0) {
    if (deleteMahasiswa($db, $id)) {
        $success = "Data mahasiswa berhasil dihapus!";
        // Redirect setelah 2 detik
        header("refresh:2;url=mahasiswa.php");
    } else {
        $error = "Gagal menghapus data mahasiswa!";
    }
}

// Get data mahasiswa untuk edit
if ($action == 'edit' && $id > 0) {
    $mahasiswa = getMahasiswaById($db, $id);
    if (!$mahasiswa) {
        $error = "Data mahasiswa tidak ditemukan!";
        $action = 'list';
    }
}

$all_mahasiswa = [];
if ($action == 'list') {
    $all_mahasiswa = getAllMahasiswa($db);
}

$mahasiswa_stats = getMahasiswaStats($db);
