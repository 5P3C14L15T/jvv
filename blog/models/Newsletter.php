<?php
class Newsletter
{
    public static function emailExiste($email)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT id FROM newsletter WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch() ? true : false;
    }

    public static function cadastrar($nome, $email, $postOrigem, $ip, $cidade, $pais, $userAgent)
    {
        try {
            $pdo = Conexao::conectar();
            $sql = "INSERT INTO newsletter (nome, email, post_origem, ip, cidade, pais, user_agent, criado_em)
                VALUES (:nome, :email, :post_origem, :ip, :cidade, :pais, :user_agent, NOW())";

            $stmt = $pdo->prepare($sql);
            $executado = $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':post_origem' => $postOrigem,
                ':ip' => $ip,
                ':cidade' => $cidade,
                ':pais' => $pais,
                ':user_agent' => $userAgent
            ]);

            if (!$executado) {
                $erro = $stmt->errorInfo();
                file_put_contents('log_news.txt', "❌ Erro PDO: " . print_r($erro, true), FILE_APPEND);
            }

            return $executado;
        } catch (PDOException $e) {
            file_put_contents('log_news.txt', "❌ EXCEPTION: " . $e->getMessage(), FILE_APPEND);
            return false;
        }
    }




    public static function ipEnviouRecentemente($ip, $intervaloSegundos = 60)
    {
        $pdo = Conexao::conectar();

        $sql = "SELECT criado_em FROM newsletter 
                WHERE ip = :ip 
                ORDER BY criado_em DESC 
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':ip' => $ip]);
        $ultimo = $stmt->fetchColumn();

        if (!$ultimo) {
            return false;
        }

        $ultimoTimestamp = strtotime($ultimo);
        return (time() - $ultimoTimestamp) < $intervaloSegundos;
    }
}
