<?php
class AuthManager {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function login($email, $password) {
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($user && isset($user['senha']) && password_verify($password, $user['senha'])) {
            // Se a senha for válida, criar a sessão
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['usuario_logado'] = true; // Adiciona a variável 'usuario_logado'
            return true;
        } else {
            // Se a autenticação falhar
            return false;
        }
    }
    

    public function is_logged_in() {
        // session_start();
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
    }
}
