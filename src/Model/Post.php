<?php
namespace Model;

use Core\Database;
use PDO;

class Post
{
    public ?int $id;
    public string $title;
    public string $slug;
    public string $content;
    public ?string $image_path;
    public int $user_id;

    public function __construct(
        ?int $id,
        string $title,
        string $slug,
        string $content,
        ?string $image_path,
        int $user_id
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->slug = $slug;
        $this->content = $content;
        $this->image_path = $image_path;
        $this->user_id = $user_id;
    }

    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT * FROM posts ORDER BY created_at DESC');
        $posts = [];
        while ($row = $stmt->fetch()) {
            $posts[] = new Post(
                (int)$row['id'],
                $row['title'],
                $row['slug'],
                $row['content'],
                $row['image_path'] ?? null,
                (int)$row['user_id']
            );
        }
        return $posts;
    }

    public static function find(int $id): ?Post
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Post(
            (int)$row['id'],
            $row['title'],
            $row['slug'],
            $row['content'],
            $row['image_path'] ?? null,
            (int)$row['user_id']
        );
    }

    public static function findBySlug(string $slug): ?Post
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM posts WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);
        $row = $stmt->fetch();
        if (!$row) return null;

        return new Post(
            (int)$row['id'],
            $row['title'],
            $row['slug'],
            $row['content'],
            $row['image_path'] ?? null,
            (int)$row['user_id']
        );
    }

    public static function create(string $title, string $content, ?string $image_path, int $user_id): bool
    {
        $pdo = Database::getConnection();
        $slug = self::generateSlug($title);

        $stmt = $pdo->prepare(
            'INSERT INTO posts (title, slug, content, image_path, user_id) 
             VALUES (:title, :slug, :content, :image_path, :user_id)'
        );
        return $stmt->execute([
            'title'      => $title,
            'slug'       => $slug,
            'content'    => $content,
            'image_path' => $image_path,
            'user_id'    => $user_id
        ]);
    }

    public static function update(
        int $id,
        string $title,
        string $content,
        ?string $image_path
    ): bool {
        $pdo = Database::getConnection();
        $slug = self::generateSlug($title);

        if ($image_path !== null) {
            $stmt = $pdo->prepare(
                'UPDATE posts
                 SET title = :title, slug = :slug, content = :content, image_path = :image_path, updated_at = NOW()
                 WHERE id = :id'
            );
            $params = [
                'title'      => $title,
                'slug'       => $slug,
                'content'    => $content,
                'image_path' => $image_path,
                'id'         => $id
            ];
        } else {
            $stmt = $pdo->prepare(
                'UPDATE posts
                 SET title = :title, slug = :slug, content = :content, updated_at = NOW()
                 WHERE id = :id'
            );
            $params = [
                'title'   => $title,
                'slug'    => $slug,
                'content' => $content,
                'id'      => $id
            ];
        }

        return $stmt->execute($params);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM posts WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    private static function generateSlug(string $title): string
    {
        $slug = strtolower($title);
        $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $slug); 
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');

        if ($slug === '') {
            $slug = 'post-' . uniqid();
        }

        return $slug;
    }
}
