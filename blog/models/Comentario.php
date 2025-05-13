<?php
class Comentario
{
    public static function cadastrar($email, $postId, $conteudo)
    {
        $pdo = Conexao::conectar();
        $sql = "INSERT INTO comentarios (newsletter_email, post_id, conteudo, curtidas, descurtidas, criado_em)
                VALUES (:email, :post_id, :conteudo, 0, 0, NOW())";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute([
            ':email' => $email,
            ':post_id' => $postId,
            ':conteudo' => $conteudo
        ]);
    }

    public static function comentouRecentemente($email, $postId, $intervaloSegundos = 1800)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT criado_em FROM comentarios 
                WHERE newsletter_email = :email AND post_id = :post_id
                ORDER BY criado_em DESC LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':post_id' => $postId
        ]);

        $ultimo = $stmt->fetchColumn();
        if (!$ultimo) return false;

        return (time() - strtotime($ultimo)) < $intervaloSegundos;
    }

    public static function totalComentariosPost($postId)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT COUNT(*) FROM comentarios WHERE post_id = :post_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':post_id' => $postId]);
        return $stmt->fetchColumn();
    }

    public static function listarAprovados($postId)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT c.id, c.conteudo, c.curtidas, c.descurtidas, c.criado_em,
                   n.nome
            FROM comentarios c
            JOIN newsletter n ON n.email = c.newsletter_email
            WHERE c.post_id = :post_id AND c.status = 'aprovado'
            ORDER BY c.criado_em DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':post_id' => $postId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public static function atualizarReacao($comentarioId, $tipo)
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ip = $ip === '::1' ? '127.0.0.1' : $ip;

        file_put_contents('log_reacoes.txt', "👉 IP: $ip | Comentário: $comentarioId | Tipo: $tipo\n", FILE_APPEND);


        $pdo = Conexao::conectar();

        $sql = "SELECT tipo FROM reacoes_comentario WHERE comentario_id = :id AND ip = :ip";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $comentarioId, ':ip' => $ip]);
        $reacaoAnterior = $stmt->fetchColumn();

        if ($tipo === 'curtir') {
            $campoAtual = 'curtidas';
            $campoInverso = 'descurtidas';
        } else {
            $campoAtual = 'descurtidas';
            $campoInverso = 'curtidas';
        }


        try {
            if (!$reacaoAnterior) {
                // Primeira vez reagindo
                $sql = "UPDATE comentarios SET {$campoAtual} = {$campoAtual} + 1 WHERE id = :id";
                $pdo->prepare($sql)->execute([':id' => $comentarioId]);

                $sql = "INSERT INTO reacoes_comentario (comentario_id, ip, tipo) 
                    VALUES (:id, :ip, :tipo)";
                $pdo->prepare($sql)->execute([
                    ':id' => $comentarioId,
                    ':ip' => $ip,
                    ':tipo' => $tipo
                ]);
            } elseif ($reacaoAnterior === $tipo) {
                // Clicou novamente no mesmo botão → desfaz
                $sql = "UPDATE comentarios SET {$campoAtual} = {$campoAtual} - 1 WHERE id = :id";
                $pdo->prepare($sql)->execute([':id' => $comentarioId]);

                $sql = "DELETE FROM reacoes_comentario WHERE comentario_id = :id AND ip = :ip";
                $pdo->prepare($sql)->execute([
                    ':id' => $comentarioId,
                    ':ip' => $ip
                ]);
            } else {
                // Mudança de ideia
                $sql = "UPDATE comentarios 
                    SET {$campoAtual} = {$campoAtual} + 1, {$campoInverso} = {$campoInverso} - 1 
                    WHERE id = :id";
                $pdo->prepare($sql)->execute([':id' => $comentarioId]);

                $sql = "UPDATE reacoes_comentario SET tipo = :tipo 
                    WHERE comentario_id = :id AND ip = :ip";
                $pdo->prepare($sql)->execute([
                    ':tipo' => $tipo,
                    ':id' => $comentarioId,
                    ':ip' => $ip
                ]);
            }

            return true;
        } catch (PDOException $e) {
            file_put_contents('log_reacoes.txt', "❌ ERRO PDO: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }

    public static function listarAprovadosPaginado($postId, $offset = 0, $limite = 1)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT c.id, c.conteudo, c.curtidas, c.descurtidas, c.criado_em,
                       c.resposta_admin, c.respondido_em,
                       n.nome
                FROM comentarios c
                JOIN newsletter n ON n.email = c.newsletter_email
                WHERE c.post_id = :post_id AND c.status = 'aprovado'
                ORDER BY c.criado_em DESC
                LIMIT :limite OFFSET :offset";

        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
