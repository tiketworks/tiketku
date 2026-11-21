<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;
    private $user;

    public function __construct($db) {
        $this->db   = $db;
        $this->user = new User($db);
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email    = trim($_POST['email']    ?? '');
            $password = trim($_POST['password'] ?? '');

            $row = $this->user->login($email, $password);
            if ($row) {
                session_regenerate_id(true);
                $_SESSION['user_id']      = $row['id'];
                $_SESSION['user_name']    = $row['name'];
                $_SESSION['user_email']   = $row['email'];
                $_SESSION['role']         = $row['role'] ?? Auth::USER;
                $_SESSION['organizer_id'] = $row['organizer_id'] ?? null;
                // Kept for older views/controllers; derived from role, never from an email address.
                $_SESSION['is_admin']     = ($_SESSION['role'] === Auth::ADMIN);

                header("Location: /event_ticketing/public/index.php?controller=event&action=index&success=login");
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        }
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->user->name     = $_POST['name'];
            $this->user->email    = $_POST['email'];
            $this->user->phone    = $_POST['phone'];
            $this->user->password = $_POST['password'];

            if ($this->user->emailExists($this->user->email)) {
                $error = 'Email already registered.';
            } elseif ($this->user->create()) {
                session_regenerate_id(true);
                $_SESSION['user_id']      = $this->user->id;
                $_SESSION['user_name']    = $this->user->name;
                $_SESSION['user_email']   = $this->user->email;
                $_SESSION['role']         = Auth::USER;   // self-registration can only create normal users
                $_SESSION['organizer_id'] = null;
                $_SESSION['is_admin']     = false;

                header("Location: /event_ticketing/public/index.php?controller=event&action=index&success=registered");
                exit;
            }
        }
        require_once __DIR__ . '/../views/auth/register.php';
    }

    public function logout() {
        session_destroy();
        header("Location: /event_ticketing/public/index.php?controller=auth&action=login");
        exit;
    }
}
?>