<?php
class User {
    public $id;

    public $email;
    public $password;

    public function __construct($email, $password) {
        $this->email = $email;
        $this->password = $password;
    }

    public function register($pdo) {
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT); 
        $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
        $stmt = $pdo->prepare($sql);
        $result = $stmt->execute(['email' => $this->email, 'password' => $hashedPassword]);

        if ($result) {
            return $pdo->lastInsertId();
        } else {
            throw new Exception('Kayıt işlemi başarısız!');
        }
    }

    public function login($pdo) {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
        $stmt->execute([$this->email]);
        $user = $stmt->fetch();

        if ($user && password_verify($this->password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['is_admin'] = $user['is_admin'];
            return true;
        } else {
            return false;
        }
    }

    public function getId() {
        return $this->id;
    }

    public function getEmail() {
        return $this->email;
    }
}