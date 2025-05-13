<?php
require_once __DIR__ . '/../../core/Conexao.php';

class Categoria
{
    public static function listarTodas()
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT * FROM categorias ORDER BY nome ASC";
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function buscarPorSlug($slug)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT * FROM categorias WHERE slug = :slug LIMIT 6";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    

    public static function listarPostsPorCategoria($idCategoria, $offset, $limite)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT 
                p.*, 
                c.nome AS categoria, 
                c.slug AS categoria_slug, 
                u.nome AS autor
            FROM posts p
            INNER JOIN categorias c ON p.id_categoria = c.id
            INNER JOIN usuarios u ON p.autor_id = u.id
            WHERE p.id_categoria = :idCategoria AND p.status = 'publicado'
            ORDER BY p.criado_em DESC
            LIMIT :offset, :limite";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':idCategoria', $idCategoria, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public static function contarPostsPorCategoria($idCategoria)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT COUNT(*) FROM posts WHERE id_categoria = :id_categoria AND status = 'publicado'";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id_categoria' => $idCategoria]);
        return $stmt->fetchColumn();
    }

    public static function listarTopCategorias($limite = 10)
    {
        $pdo = Conexao::conectar();
        $sql = "
        SELECT c.id, c.nome, c.slug, COUNT(p.id) AS total_posts
        FROM categorias c
        INNER JOIN posts p ON p.id_categoria = c.id
        WHERE p.status = 'Publicado'
        GROUP BY c.id, c.nome, c.slug
        ORDER BY total_posts DESC
        LIMIT :limite
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarPostsPorCategoriaSlug($slug, $offset, $limite)
{
    $pdo = Conexao::conectar();
    $sql = "SELECT 
                p.*, 
                c.nome AS categoria, 
                c.slug AS categoria_slug, 
                u.nome AS autor
            FROM posts p
            INNER JOIN categorias c ON p.id_categoria = c.id
            INNER JOIN usuarios u ON p.autor_id = u.id
            WHERE c.slug = :slug AND p.status = 'publicado'
            ORDER BY p.criado_em DESC
            LIMIT :offset, :limite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':slug', $slug, PDO::PARAM_STR);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
