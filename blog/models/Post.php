<?php
require_once __DIR__ . '/../../core/Conexao.php';

class Post
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Conexao::conectar();
    }

    public function buscarDestaques()
    {
        $pdo = Conexao::conectar();
        $sql = "
        SELECT 
            p.*, 
            c.nome AS categoria,
            u.nome AS autor
        FROM 
            posts p
        INNER JOIN 
            categorias c ON p.id_categoria = c.id
        INNER JOIN 
            usuarios u ON p.autor_id = u.id
        WHERE 
            p.status = 'publicado' AND p.destacado = 1
        ORDER BY 
            p.criado_em DESC
        LIMIT 4
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorSlug($slug)
    {
        $pdo = Conexao::conectar();
        $sql = "
    SELECT 
        p.*, 
        c.nome AS categoria, 
        c.slug AS categoria_slug,  
        u.nome AS autor, 
        u.foto AS autor_foto
    FROM posts p
    INNER JOIN categorias c ON p.id_categoria = c.id
    INNER JOIN usuarios u ON p.autor_id = u.id
    WHERE p.slug = :slug AND p.status = 'Publicado'
    LIMIT 1
    ";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':slug', $slug, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public static function buscarRelacionados($categoria_id, $slugAtual)
    {
        $pdo = Conexao::conectar();

        $sql = "
        SELECT p.*, c.nome AS categoria, u.nome AS autor
        FROM posts p
        INNER JOIN categorias c ON p.id_categoria = c.id
        INNER JOIN usuarios u ON p.autor_id = u.id
        WHERE p.id_categoria = :categoria_id
          AND p.slug != :slug
          AND p.status = 'Publicado'
        ORDER BY p.visualizacoes DESC
        LIMIT 3
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':categoria_id' => $categoria_id,
            ':slug' => $slugAtual
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarMaisPopulares($limite = 4)
{
    $pdo = Conexao::conectar();
    $sql = "SELECT 
                p.*, 
                c.nome AS categoria, 
                c.slug AS categoria_slug, 
                u.nome AS autor
            FROM posts p
            LEFT JOIN categorias c ON p.id_categoria = c.id
            LEFT JOIN usuarios u ON p.autor_id = u.id
            WHERE p.status = 'Publicado'
            ORDER BY p.visualizacoes DESC
            LIMIT :limite";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    public function buscarPostPorPosicao($offset, $limite = 1)
    {
        $sql = "
    SELECT p.*, 
           c.nome AS categoria, 
           c.slug AS categoria_slug, 
           u.nome AS autor
    FROM posts p
    INNER JOIN categorias c ON p.id_categoria = c.id
    INNER JOIN usuarios u ON p.autor_id = u.id
    WHERE p.status = 'publicado'
    ORDER BY p.criado_em DESC
    LIMIT :offset, :limite
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $limite === 1 ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function listarUltimosPorCategoriaUnica($limite = 3)
    {
        $pdo = Conexao::conectar();
        $sql = "
        SELECT p.*, c.nome AS categoria, c.slug AS categoria_slug, u.nome AS autor
        FROM posts p
        INNER JOIN categorias c ON p.id_categoria = c.id
        INNER JOIN usuarios u ON p.autor_id = u.id
        WHERE p.status = 'publicado'
        AND p.id IN (
            SELECT MIN(sub.id)
            FROM posts sub
            WHERE sub.status = 'publicado'
            GROUP BY sub.id_categoria
        )
        ORDER BY p.criado_em DESC
        LIMIT :limite
    ";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorTermo($termo, $offset, $limite)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT p.*, c.nome AS categoria, u.nome AS autor
            FROM posts p
            INNER JOIN categorias c ON p.id_categoria = c.id
            INNER JOIN usuarios u ON p.autor_id = u.id
            WHERE p.titulo LIKE :termo AND p.status = 'Publicado'
            ORDER BY p.criado_em DESC
            LIMIT :offset, :limite";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function contarPorTermo($termo)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT COUNT(*) FROM posts WHERE titulo LIKE :termo AND status = 'Publicado'";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':termo', "%$termo%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}
