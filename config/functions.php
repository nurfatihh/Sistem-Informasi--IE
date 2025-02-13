<?php
// Import necessary files
require_once 'database.php';

/**
 * Helper Functions untuk Website Informatika
 */

// Fungsi untuk mengamankan input
function sanitize_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

// Fungsi format tanggal ke format Indonesia
function formatTanggal($date)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );

    $split = explode('-', $date);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

// Fungsi untuk mendapatkan berita terkini
function getBeritaTerkini($limit = 5)
{
    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT id, judul, ringkasan, gambar, tanggal 
              FROM berita 
              WHERE status = 'published' 
              ORDER BY tanggal DESC 
              LIMIT :limit";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan detail berita
function getBeritaById($id)
{
    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT * FROM berita WHERE id = :id AND status = 'published'";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan daftar dosen
function getDaftarDosen($limit = null)
{
    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT id, nama, nip, jabatan, foto, bidang_keahlian 
              FROM dosen 
              WHERE status = 'aktif' 
              ORDER BY nama ASC";

    if ($limit) {
        $query .= " LIMIT :limit";
    }

    $stmt = $conn->prepare($query);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan daftar mata kuliah
function getDaftarMataKuliah($semester = null)
{
    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT * FROM mata_kuliah WHERE 1=1";
    if ($semester) {
        $query .= " AND semester = :semester";
    }
    $query .= " ORDER BY semester ASC, nama ASC";

    $stmt = $conn->prepare($query);
    if ($semester) {
        $stmt->bindParam(':semester', $semester, PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk upload file
function uploadFile($file, $destination, $allowedTypes = ['jpg', 'jpeg', 'png'])
{
    try {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new RuntimeException('Invalid parameters.');
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent.');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit.');
            default:
                throw new RuntimeException('Unknown errors.');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($fileExtension, $allowedTypes)) {
            throw new RuntimeException('Invalid file format.');
        }

        $fileName = sprintf(
            '%s.%s',
            sha1_file($file['tmp_name']),
            $fileExtension
        );

        if (!move_uploaded_file(
            $file['tmp_name'],
            sprintf(
                '%s/%s',
                $destination,
                $fileName
            )
        )) {
            throw new RuntimeException('Failed to move uploaded file.');
        }

        return $fileName;
    } catch (RuntimeException $e) {
        return ['error' => $e->getMessage()];
    }
}

// Fungsi untuk pagination
function createPagination($total_records, $records_per_page, $current_page)
{
    $total_pages = ceil($total_records / $records_per_page);

    $pagination = [];
    $pagination['total_pages'] = $total_pages;
    $pagination['current_page'] = $current_page;
    $pagination['has_previous'] = ($current_page > 1);
    $pagination['has_next'] = ($current_page < $total_pages);
    $pagination['prev_page'] = ($current_page > 1) ? $current_page - 1 : null;
    $pagination['next_page'] = ($current_page < $total_pages) ? $current_page + 1 : null;

    return $pagination;
}

// Fungsi untuk mendapatkan prestasi mahasiswa
function getPrestasiMahasiswa($limit = null)
{
    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT p.*, m.nama as nama_mahasiswa 
              FROM prestasi p 
              JOIN mahasiswa m ON p.mahasiswa_id = m.id 
              ORDER BY p.tanggal DESC";

    if ($limit) {
        $query .= " LIMIT :limit";
    }

    $stmt = $conn->prepare($query);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk mendapatkan galeri kegiatan
function getGaleriKegiatan($limit = null)
{
    $db = new Database();
    $conn = $db->getConnection();

    $query = "SELECT * FROM gallery 
              WHERE status = 'published' 
              ORDER BY tanggal DESC";

    if ($limit) {
        $query .= " LIMIT :limit";
    }

    $stmt = $conn->prepare($query);
    if ($limit) {
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fungsi untuk log aktivitas
function logActivity($user_id, $activity, $description = '')
{
    $db = new Database();
    $conn = $db->getConnection();

    $query = "INSERT INTO activity_logs (user_id, activity, description, ip_address, created_at) 
              VALUES (:user_id, :activity, :description, :ip_address, NOW())";

    $stmt = $conn->prepare($query);
    $ip_address = $_SERVER['REMOTE_ADDR'];

    $stmt->bindParam(':user_id', $user_id);
    $stmt->bindParam(':activity', $activity);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':ip_address', $ip_address);

    return $stmt->execute();
}

// Fungsi untuk mengecek role user
function checkUserRole($required_role)
{
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $required_role) {
        header('Location: ' . BASE_URL . '/login.php');
        exit();
    }
}

// Fungsi untuk generate breadcrumb
function generateBreadcrumb($items)
{
    $html = '<nav class="flex" aria-label="Breadcrumb">';
    $html .= '<ol class="inline-flex items-center space-x-1 md:space-x-3">';

    foreach ($items as $key => $item) {
        if ($key === array_key_last($items)) {
            $html .= '<li aria-current="page">';
            $html .= '<div class="flex items-center">';
            $html .= '<span class="text-gray-500 ml-1 md:ml-2 font-medium">' . $item['title'] . '</span>';
            $html .= '</div>';
            $html .= '</li>';
        } else {
            $html .= '<li>';
            $html .= '<div class="flex items-center">';
            $html .= '<a href="' . $item['url'] . '" class="text-blue-600 hover:text-blue-700">' . $item['title'] . '</a>';
            $html .= '<svg class="w-3 h-3 mx-3 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">';
            $html .= '<path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>';
            $html .= '</svg>';
            $html .= '</div>';
            $html .= '</li>';
        }
    }

    $html .= '</ol>';
    $html .= '</nav>';

    return $html;
}

// Fungsi untuk generate slug
function generateSlug($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}
