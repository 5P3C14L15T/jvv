<?php
require_once __DIR__ . '/../../core/Conexao.php';

class Comentario
{
    // Método para listar o Top 5 posts com mais comentários (status aprovado)
    public static function listarTopComentarios($limite = 5)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT p.titulo, COUNT(c.id) AS total_comentarios
                FROM comentarios c
                INNER JOIN posts p ON c.post_id = p.id
                WHERE c.status = 'aprovado'
                GROUP BY p.id, p.titulo
                ORDER BY total_comentarios DESC
                LIMIT :limite";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Você pode adicionar outros métodos depois, como contar total de comentários, deletar, etc.

    // Conta os comentários pendentes de aprovação
    public static function contarPendentes()
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT COUNT(*) AS total FROM comentarios WHERE status = 'pendente'";
        $stmt = $pdo->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return $resultado['total'] ?? 0;
    }

    // Lista os últimos 5 comentários pendentes
    public static function listarPendentes($limite = 5)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT c.id, c.newsletter_email, c.conteudo, c.criado_em, c.status, p.titulo AS titulo_post
                FROM comentarios c
                LEFT JOIN posts p ON c.post_id = p.id
                WHERE c.status = 'pendente'
                ORDER BY c.criado_em DESC
                LIMIT :limite";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function contarTodos()
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT COUNT(*) AS total FROM comentarios";
        $stmt = $pdo->query($sql);
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($resultado['total'] ?? 0);
    }

    public static function buscarTodos($filtro = '', $status = '', $limite = 10, $offset = 0)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT c.*, p.titulo AS titulo_post 
            FROM comentarios c 
            LEFT JOIN posts p ON c.post_id = p.id 
            WHERE 1";

        if (!empty($filtro)) {
            $sql .= " AND (c.newsletter_email LIKE :filtro OR c.conteudo LIKE :filtro)";
        }

        if (!empty($status)) {
            $sql .= " AND c.status = :status";
        }

        $sql .= " ORDER BY c.criado_em DESC LIMIT :limite OFFSET :offset";

        $stmt = $pdo->prepare($sql);

        if (!empty($filtro)) {
            $stmt->bindValue(':filtro', '%' . $filtro . '%', PDO::PARAM_STR);
        }

        if (!empty($status)) {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function contarTodosComFiltro($filtro = '', $status = '')
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT COUNT(*) FROM comentarios WHERE 1";

        if (!empty($filtro)) {
            $sql .= " AND (newsletter_email LIKE :filtro OR conteudo LIKE :filtro)";
        }

        if (!empty($status)) {
            $sql .= " AND status = :status";
        }

        $stmt = $pdo->prepare($sql);

        if (!empty($filtro)) {
            $stmt->bindValue(':filtro', '%' . $filtro . '%', PDO::PARAM_STR);
        }

        if (!empty($status)) {
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public static function responderComentario($id, $resposta)
    {
        $pdo = Conexao::conectar();
        $sql = "UPDATE comentarios 
            SET resposta_admin = :resposta, respondido_em = NOW() 
            WHERE id = :id AND status = 'aprovado'";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':resposta' => $resposta,
            ':id' => $id
        ]);
    }
}
