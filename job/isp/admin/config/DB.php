<?php

class DB {
    private $host = 'localhost';
    private $db_name = 'u258163094_isp';
    private $username = 'u258163094_isp';
    private $password = 'U258163094_isp';
    private $conn;

    // Método para conectar ao banco de dados
    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch (PDOException $e) {
            echo 'Erro de Conexão: ' . $e->getMessage();
        }

        
        return $this->conn;
    }

    // Método para desconectar do banco de dados
    public function disconnect() {
        $this->conn = null;
    }
}
