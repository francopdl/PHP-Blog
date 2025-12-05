<?php
namespace Controller;

use Model\User;

class AuthController
{
    public function showLoginForm(): void
    {
        require __DIR__ . '/../View/layout/header.php';
        require __DIR__ . '/../View/auth/login.php';
        require __DIR__ . '/../View/layout/footer.php';
    }

    public function login(): void
{
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = User::findByUsername($username);

    if (!$user || !password_verify($password, $user->password_hash)) {
        $_SESSION['error'] = 'Usuario o contraseña incorrectos';
        header('Location: /login');
        exit;
    }

    $_SESSION['user_id']   = $user->id;
    $_SESSION['username']  = $user->username;
    $_SESSION['is_admin']  = $user->is_admin;   

    header('Location: /');
    exit;
}


    public function showRegisterForm(): void
    {
        require __DIR__ . '/../View/layout/header.php';
        require __DIR__ . '/../View/auth/register.php';
        require __DIR__ . '/../View/layout/footer.php';
    }

    public function register(): void
    {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $password2 = $_POST['password2'] ?? '';

        if ($username === '' || $password === '' || $password2 === '') {
            $_SESSION['error'] = 'Todos los campos son obligatorios';
            header('Location: /register');
            exit;
        }

        if ($password !== $password2) {
            $_SESSION['error'] = 'Las contraseñas no coinciden';
            header('Location: /register');
            exit;
        }

        $ok = User::create($username, $password);

        if (!$ok) {
            $_SESSION['error'] = 'El usuario ya existe';
            header('Location: /register');
            exit;
        }

        $_SESSION['success'] = 'Usuario registrado, ahora puedes iniciar sesión';
        header('Location: /login');
        exit;
    }

    public function logout(): void
{
    session_unset();
    session_destroy();
    header('Location: /login');
    exit;
}

}
