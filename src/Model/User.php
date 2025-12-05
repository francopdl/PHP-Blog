<?php
namespace Model;

use Core\Database;

class User
{
    public ?int $id;
    public string $username;
    public string $password_hash;
    public bool $is_admin;   

    public function __construct(?int $id, string $username, string $password_hash, bool $is_admin = false)
    {
        $this->id          = $id;
        $this->username    = $username;
        $this->password_hash = $password_hash;
        $this->is_admin    = $is_admin;
    }

    public static function findByUsername(string $username): ?User
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
        $stmt->execute(['username' => $username]);
        $data = $stmt->fetch();

        if (!$data) return null;

        return new User(
            (int)$data['id'],
            $data['username'],
            $data['password'],
            (bool)$data['is_admin']    
        );
    }

    public static function findById(int $id): ?User
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch();

        if (!$data) return null;

        return new User(
            (int)$data['id'],
            $data['username'],
            $data['password'],
            (bool)$data['is_admin']    
        );
    }


    public static function create(string $username, string $plainPassword): bool
    {
        $pdo = Database::getConnection();

        // ¿Existe ya?
        $exists = self::findByUsername($username);
        if ($exists) return false;

        $hash = password_hash($plainPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'INSERT INTO users (username, password) VALUES (:username, :password)'
        );
        return $stmt->execute([
            'username' => $username,
            'password' => $hash
        ]);
    }

    public function changePassword(string $newPlainPassword): bool
    {
        $pdo = Database::getConnection();
        $newHash = password_hash($newPlainPassword, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare(
            'UPDATE users SET password = :password WHERE id = :id'
        );
        return $stmt->execute([
            'password' => $newHash,
            'id'       => $this->id
        ]);
    }
}
