<?php
// Definisi Role User
define('ROLE_ADMIN', 'admin');
define('ROLE_DOSEN', 'dosen');
define('ROLE_MAHASISWA', 'mahasiswa');
define('ROLE_STAFF', 'staff');

// Status Records
define('STATUS_ACTIVE', 'active');
define('STATUS_INACTIVE', 'inactive');
define('STATUS_DELETED', 'deleted');
define('STATUS_PENDING', 'pending');
define('STATUS_PUBLISHED', 'published');
define('STATUS_DRAFT', 'draft');

// Jenis Kelamin
define('GENDER_MALE', 'L');
define('GENDER_FEMALE', 'P');

// Academic Constants
define('SEMESTER_GANJIL', 'ganjil');
define('SEMESTER_GENAP', 'genap');

define('JENIS_MK_WAJIB', 'wajib');
define('JENIS_MK_PILIHAN', 'pilihan');

// Upload Constants
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', [
    'image/jpeg',
    'image/png',
    'image/gif'
]);
define('ALLOWED_DOCUMENT_TYPES', [
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]);

// Pagination Constants
define('DEFAULT_PAGE_SIZE', 10);
define('MAX_PAGE_SIZE', 100);

// Time Constants
define('DATE_FORMAT', 'd F Y');
define('TIME_FORMAT', 'H:i');
define('DATETIME_FORMAT', 'd F Y H:i');

// Message Constants
define('MSG_SUCCESS', 'success');
define('MSG_ERROR', 'error');
define('MSG_WARNING', 'warning');
define('MSG_INFO', 'info');

// Error Messages
define('ERR_REQUIRED_FIELD', 'Field ini wajib diisi');
define('ERR_INVALID_EMAIL', 'Format email tidak valid');
define('ERR_INVALID_PASSWORD', 'Password minimal 8 karakter');
define('ERR_PASSWORD_MISMATCH', 'Password tidak cocok');
define('ERR_INVALID_FILE_TYPE', 'Tipe file tidak diizinkan');
define('ERR_FILE_TOO_LARGE', 'Ukuran file terlalu besar');
define('ERR_UPLOAD_FAILED', 'Upload file gagal');
define('ERR_NOT_FOUND', 'Data tidak ditemukan');
define('ERR_ACCESS_DENIED', 'Akses ditolak');
define('ERR_INVALID_TOKEN', 'Token tidak valid atau kadaluarsa');

// Success Messages
define('MSG_SAVE_SUCCESS', 'Data berhasil disimpan');
define('MSG_UPDATE_SUCCESS', 'Data berhasil diperbarui');
define('MSG_DELETE_SUCCESS', 'Data berhasil dihapus');
define('MSG_UPLOAD_SUCCESS', 'File berhasil diupload');
define('MSG_LOGIN_SUCCESS', 'Login berhasil');
define('MSG_LOGOUT_SUCCESS', 'Logout berhasil');
define('MSG_PASSWORD_CHANGED', 'Password berhasil diubah');
define('MSG_RESET_PASSWORD_SENT', 'Link reset password telah dikirim ke email Anda');

// Menu Constants
define('MENU_DASHBOARD', [
    'title' => 'Dashboard',
    'icon' => 'dashboard',
    'url' => 'dashboard.php'
]);

define('MENU_AKADEMIK', [
    'title' => 'Akademik',
    'icon' => 'school',
    'items' => [
        ['title' => 'Kurikulum', 'url' => 'kurikulum.php'],
        ['title' => 'Mata Kuliah', 'url' => 'matakuliah.php'],
        ['title' => 'Jadwal Kuliah', 'url' => 'jadwal.php']
    ]
]);

define('MENU_DOSEN', [
    'title' => 'Dosen',
    'icon' => 'person',
    'url' => 'dosen.php'
]);

define('MENU_MAHASISWA', [
    'title' => 'Mahasiswa',
    'icon' => 'people',
    'url' => 'mahasiswa.php'
]);

define('MENU_PENELITIAN', [
    'title' => 'Penelitian',
    'icon' => 'lab',
    'url' => 'penelitian.php'
]);

// API Constants
define('API_SUCCESS_CODE', 200);
define('API_ERROR_CODE', 400);
define('API_AUTH_ERROR_CODE', 401);
define('API_FORBIDDEN_CODE', 403);
define('API_NOT_FOUND_CODE', 404);
define('API_SERVER_ERROR_CODE', 500);

// Cache Constants
define('CACHE_PREFIX', 'informatika_');
define('CACHE_TTL', 3600); // 1 hour

// Security Constants
define('MIN_PASSWORD_LENGTH', 8);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_TIMEOUT', 900); // 15 minutes
define('TOKEN_LENGTH', 32);
