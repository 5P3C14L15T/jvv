<?php
require_once __DIR__ . '/../../core/Conexao.php';

class Categoria
{
    public static function listarTodas()
    {
        $pdo = Conexao::conectar();
        $stmt = $pdo->query("SELECT * FROM categorias ORDER BY nome");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function criar($nome)
    {
        $pdo = Conexao::conectar();
        $slug = self::gerarSlug($nome);
        $stmt = $pdo->prepare("INSERT INTO categorias (nome, slug) VALUES (:nome, :slug)");
        return $stmt->execute([':nome' => $nome, ':slug' => $slug]);
    }

    private static function gerarSlug($texto)
    {
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
        $texto = strtolower($texto);
        $texto = preg_replace('/[^a-z0-9 ]/', '', $texto);
        $texto = preg_replace('/\s+/', '-', trim($texto));
        return $texto;
    }
    public static function buscarPorId($id)
    {
        $pdo = Conexao::conectar();
        $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function atualizar($id, $nome)
    {
        $pdo = Conexao::conectar();
        $slug = self::gerarSlug($nome);
        $stmt = $pdo->prepare("UPDATE categorias SET nome = :nome, slug = :slug WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':nome' => $nome,
            ':slug' => $slug
        ]);
    }

    public static function deletar($id) {
        $pdo = Conexao::conectar();
        $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
    
}
