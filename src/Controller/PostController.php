<?php
namespace Controller;

use Model\Post;

class PostController
{
    public function index(): void
    {
        $posts = Post::all();
        require __DIR__ . '/../View/layout/header.php';
        require __DIR__ . '/../View/post/index.php';
        require __DIR__ . '/../View/layout/footer.php';
    }

    public function createForm(): void
    {
        $this->requireLogin();
        require __DIR__ . '/../View/layout/header.php';
        require __DIR__ . '/../View/post/create.php';
        require __DIR__ . '/../View/layout/footer.php';
    }

    public function store(): void
    {
        $this->requireLogin();

        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($title === '' || $content === '') {
            $_SESSION['error'] = 'Título y contenido son obligatorios';
            header('Location: /post/create');
            exit;
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $imagePath = $this->handleUpload($_FILES['image']);
            if ($imagePath === null) {
                $_SESSION['error'] = 'Error al subir la imagen';
                header('Location: /post/create');
                exit;
            }
        }

        Post::create($title, $content, $imagePath, $_SESSION['user_id']);
        header('Location: /');
        exit;
    }

    public function show(): void
    {
        // Versión por id: /post?id=1
        $id = (int)($_GET['id'] ?? 0);
        $post = Post::find($id);
        if (!$post) {
            http_response_code(404);
            echo "Post no encontrado";
            return;
        }

        require __DIR__ . '/../View/layout/header.php';
        require __DIR__ . '/../View/post/show.php';
        require __DIR__ . '/../View/layout/footer.php';
    }

    public function editForm(): void
    {
        $this->requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $post = Post::find($id);

        if (
            !$post ||
            (
                !$this->isAdmin() &&
                $post->user_id !== $_SESSION['user_id']
            )
        ) {
            http_response_code(403);
            echo "No tienes permiso para editar este post";
            return;
        }

        require __DIR__ . '/../View/layout/header.php';
        require __DIR__ . '/../View/post/edit.php';
        require __DIR__ . '/../View/layout/footer.php';
    }

    public function update(): void
    {
        $this->requireLogin();

        $id = (int)($_POST['id'] ?? 0);
        $post = Post::find($id);

        if (
            !$post ||
            (
                !$this->isAdmin() &&
                $post->user_id !== $_SESSION['user_id']
            )
        ) {
            http_response_code(403);
            echo "No tienes permiso para editar este post";
            return;
        }

        $title   = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');

        if ($title === '' || $content === '') {
            $_SESSION['error'] = 'Título y contenido son obligatorios';
            header('Location: /post/edit?id=' . $id);
            exit;
        }

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            $imagePath = $this->handleUpload($_FILES['image']);
            if ($imagePath === null) {
                $_SESSION['error'] = 'Error al subir la imagen';
                header('Location: /post/edit?id=' . $id);
                exit;
            }
        }

        Post::update($id, $title, $content, $imagePath);
        header('Location: /post?id=' . $id);
        exit;
    }

    public function destroy(): void
    {
        $this->requireLogin();

        $id = (int)($_POST['id'] ?? 0);
        $post = Post::find($id);

        if (
            !$post ||
            (
                !$this->isAdmin() &&
                $post->user_id !== $_SESSION['user_id']
            )
        ) {
            http_response_code(403);
            echo "No tienes permiso para borrar este post";
            return;
        }

        Post::delete($id);
        header('Location: /');
        exit;
    }

    private function handleUpload(array $file): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) return null;

        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($file['type'], $allowed, true)) return null;

        if ($file['size'] > 2 * 1024 * 1024) {
            return null;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('post_', true) . '.' . $ext;

        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $destination = $uploadDir . $fileName;
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return null;
        }

        return '/uploads/' . $fileName;
    }

    private function requireLogin(): void
    {
        if (empty($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    private function isAdmin(): bool
    {
        
        return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
    }
}
