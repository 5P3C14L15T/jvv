<?php
class Conexao {
  private static $host = "localhost";
  private static $dbname = "agenciajvv";
  private static $usuario = "root";
  private static $senha = "";

  public static function conectar() {
    try {
      // ✅ Define timezone do PHP
      date_default_timezone_set('America/Sao_Paulo');

      $pdo = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$dbname, self::$usuario, self::$senha);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      // ✅ Define timezone da sessão do MySQL
      $pdo->exec("SET time_zone = '-03:00'");

      return $pdo;
    } catch (PDOException $e) {
      die("Erro na conexão: " . $e->getMessage());
    }
  }
}
