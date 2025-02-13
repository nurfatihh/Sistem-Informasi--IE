<?php
class Auth
{
    private $conn;
    private $table_name = "users";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Fungsi untuk login
    public function login($username, $password)
    {
        try {
            // Persiapkan query
            $query = "SELECT id, username, password, role, status 
                     FROM " . $this->table_name . " 
                     WHERE username = :username 
                     AND status = 'active'";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($password, $row['password'])) {
                    // Buat session
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['username'] = $row['username'];
                    $_SESSION['role'] = $row['role'];
                    $_SESSION['last_activity'] = time();

                    // Log aktivitas
                    $this->logLogin($row['id'], true);

                    return true;
                }
            }

            // Log percobaan login gagal
            $this->logLogin(0, false, $username);
            return false;
        } catch (PDOException $e) {
            error_log("Login Error: " . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk logout
    public function logout()
    {
        // Log aktivitas logout
        if (isset($_SESSION['user_id'])) {
            $this->logLogin($_SESSION['user_id'], true, '', 'logout');
        }

        // Hapus semua session
        session_unset();
        session_destroy();

        // Hapus cookie session
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
    }

    // Fungsi untuk mengecek apakah user sudah login
    public function isLoggedIn()
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['last_activity'])) {
            // Cek timeout session
            if (time() - $_SESSION['last_activity'] > SESSION_LIFETIME) {
                $this->logout();
                return false;
            }

            // Update last activity
            $_SESSION['last_activity'] = time();
            return true;
        }
        return false;
    }

    // Fungsi untuk mengecek role user
    public function hasRole($required_role)
    {
        if ($this->isLoggedIn() && isset($_SESSION['role'])) {
            if ($_SESSION['role'] === $required_role || $_SESSION['role'] === 'admin') {
                return true;
            }
        }
        return false;
    }

    // Fungsi untuk mengubah password
    public function changePassword($user_id, $old_password, $new_password)
    {
        try {
            // Cek password lama
            $query = "SELECT password FROM " . $this->table_name . " WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":id", $user_id);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (password_verify($old_password, $row['password'])) {
                    // Update password baru
                    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

                    $query = "UPDATE " . $this->table_name . " 
                             SET password = :password, updated_at = NOW() 
                             WHERE id = :id";

                    $stmt = $this->conn->prepare($query);
                    $stmt->bindParam(":password", $hashed_password);
                    $stmt->bindParam(":id", $user_id);

                    if ($stmt->execute()) {
                        // Log perubahan password
                        $this->logActivity($user_id, 'password_changed');
                        return true;
                    }
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("Change Password Error: " . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk reset password
    public function resetPassword($email)
    {
        try {
            $query = "SELECT id, username FROM " . $this->table_name . " 
                     WHERE email = :email AND status = 'active'";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":email", $email);
            $stmt->execute();

            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                // Generate token
                $token = bin2hex(random_bytes(32));
                $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

                // Simpan token
                $query = "INSERT INTO password_resets (user_id, token, expires_at) 
                         VALUES (:user_id, :token, :expires_at)";

                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(":user_id", $row['id']);
                $stmt->bindParam(":token", $token);
                $stmt->bindParam(":expires_at", $expires);

                if ($stmt->execute()) {
                    // Kirim email reset password
                    $reset_link = BASE_URL . "/reset-password.php?token=" . $token;
                    $to = $email;
                    $subject = "Reset Password";
                    $message = "Halo " . $row['username'] . ",\n\n";
                    $message .= "Klik link berikut untuk reset password Anda:\n";
                    $message .= $reset_link . "\n\n";
                    $message .= "Link ini akan kadaluarsa dalam 1 jam.\n";
                    $message .= "Jika Anda tidak meminta reset password, abaikan email ini.\n\n";
                    $message .= "Terima kasih,\n";
                    $message .= SITE_NAME;

                    $headers = "From: " . ADMIN_EMAIL;

                    if (mail($to, $subject, $message, $headers)) {
                        return true;
                    }
                }
            }
            return false;
        } catch (PDOException $e) {
            error_log("Reset Password Error: " . $e->getMessage());
            return false;
        }
    }

    // Fungsi untuk log aktivitas login
    private function logLogin($user_id, $success, $username = '', $type = 'login')
    {
        try {
            $query = "INSERT INTO login_logs (user_id, username, success, ip_address, user_agent, type) 
                     VALUES (:user_id, :username, :success, :ip_address, :user_agent, :type)";

            $stmt = $this->conn->prepare($query);

            $ip = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];

            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":success", $success, PDO::PARAM_BOOL);
            $stmt->bindParam(":ip_address", $ip);
            $stmt->bindParam(":user_agent", $user_agent);
            $stmt->bindParam(":type", $type);

            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Log Login Error: " . $e->getMessage());
        }
    }

    // Fungsi untuk log aktivitas umum
    private function logActivity($user_id, $activity)
    {
        try {
            $query = "INSERT INTO activity_logs (user_id, activity, ip_address) 
                     VALUES (:user_id, :activity, :ip_address)";

            $stmt = $this->conn->prepare($query);

            $ip = $_SERVER['REMOTE_ADDR'];

            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":activity", $activity);
            $stmt->bindParam(":ip_address", $ip);

            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Log Activity Error: " . $e->getMessage());
        }
    }
}
