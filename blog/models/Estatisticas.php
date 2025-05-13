<?php
require_once __DIR__ . '/../../core/Conexao.php';

class Estatisticas
{
    public static function registrarVisualizacao($postId, $contar = true)
    {
        $pdo = Conexao::conectar();

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip === '::1') {
            $ip = '127.0.0.1';
        }
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $sistemaOperacional = self::detectarSistemaOperacional($userAgent);
        $navegador = self::detectarNavegador($userAgent);
        $dispositivo = self::detectarDispositivo($userAgent);

        $pais = 'Desconhecido';
        $cidade = 'Desconhecida';

        // Geolocalização
        $geoData = @file_get_contents("http://ip-api.com/json/?fields=status,country,city");
        if ($geoData) {
            $geo = json_decode($geoData, true);
            if ($geo['status'] === 'success') {
                $pais = $geo['country'] ?? 'Desconhecido';
                $cidade = $geo['city'] ?? 'Desconhecida';
            }
        }

        // Verifica visualização existente
        $sqlCheck = "SELECT id, ultima_visualizacao, pais, cidade FROM visualizacoes 
                 WHERE post_id = :post_id AND ip = :ip LIMIT 1";
        $stmt = $pdo->prepare($sqlCheck);
        $stmt->execute([':post_id' => $postId, ':ip' => $ip]);
        $registro = $stmt->fetch(PDO::FETCH_ASSOC);

        $agora = new DateTime();

        if (!$registro) {
            // Primeira visualização
            $sqlInsert = "INSERT INTO visualizacoes 
            (post_id, ip, user_agent, sistema_operacional, navegador, dispositivo, pais, cidade, ultima_visualizacao, criado_em) 
            VALUES 
            (:post_id, :ip, :user_agent, :sistema_operacional, :navegador, :dispositivo, :pais, :cidade, NOW(), NOW())";

            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->execute([
                ':post_id' => $postId,
                ':ip' => $ip,
                ':user_agent' => $userAgent,
                ':sistema_operacional' => $sistemaOperacional,
                ':navegador' => $navegador,
                ':dispositivo' => $dispositivo,
                ':pais' => $pais,
                ':cidade' => $cidade
            ]);

            if ($contar) {
                $pdo->prepare("UPDATE posts SET visualizacoes = visualizacoes + 1 WHERE id = :id")
                    ->execute([':id' => $postId]);
            }
        } else {
            $ultimaVisualizacao = new DateTime($registro['ultima_visualizacao']);
            $diferencaSegundos = $agora->getTimestamp() - $ultimaVisualizacao->getTimestamp();

            // Atualiza localização se estava vazia
            $pdo->prepare("UPDATE visualizacoes SET 
            pais = CASE WHEN pais IS NULL OR pais = 'Desconhecido' THEN :pais ELSE pais END,
            cidade = CASE WHEN cidade IS NULL OR cidade = 'Desconhecida' THEN :cidade ELSE cidade END
            WHERE id = :id
        ")->execute([
                ':id' => $registro['id'],
                ':pais' => $pais,
                ':cidade' => $cidade
            ]);

            // Se passou mais de 60 segundos
            if ($diferencaSegundos >= 60) {
                $pdo->prepare("UPDATE visualizacoes SET ultima_visualizacao = NOW() WHERE id = :id")
                    ->execute([':id' => $registro['id']]);

                if ($contar) {
                    $pdo->prepare("UPDATE posts SET visualizacoes = visualizacoes + 1 WHERE id = :id")
                        ->execute([':id' => $postId]);
                }
            }
        }
    }






    private static function detectarSistemaOperacional($userAgent)
    {
        if (preg_match('/windows/i', $userAgent)) return 'Windows';
        if (preg_match('/macintosh|mac os x/i', $userAgent)) return 'Mac OS';
        if (preg_match('/linux/i', $userAgent)) return 'Linux';
        if (preg_match('/android/i', $userAgent)) return 'Android';
        if (preg_match('/iphone|ipad/i', $userAgent)) return 'iOS';
        return 'Desconhecido';
    }

    private static function detectarNavegador($userAgent)
    {
        if (strpos($userAgent, 'Edge') !== false) return 'Edge';
        if (strpos($userAgent, 'Chrome') !== false) return 'Chrome';
        if (strpos($userAgent, 'Firefox') !== false) return 'Firefox';
        if (strpos($userAgent, 'Safari') !== false) return 'Safari';
        if (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) return 'Internet Explorer';
        return 'Desconhecido';
    }

    private static function detectarDispositivo($userAgent)
    {
        if (preg_match('/mobile/i', $userAgent)) return 'Celular';
        if (preg_match('/tablet/i', $userAgent)) return 'Tablet';
        return 'Desktop';
    }

    public static function registrarTempoPermanencia($postId, $tempo)
    {
        $pdo = Conexao::conectar();

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        if ($ip === '::1') {
            $ip = '127.0.0.1'; // converte IPv6 local para IPv4 local
        }


        // Log para rastrear
        file_put_contents(__DIR__ . '/log.txt', "🟡 IP: $ip | PostID: $postId | Tempo: $tempo\n", FILE_APPEND);

        // Verifica se já existe visualização desse IP para esse post
        $sqlCheck = "SELECT id, tempo_segundos FROM visualizacoes WHERE post_id = :post_id AND ip = :ip LIMIT 1";
        $stmt = $pdo->prepare($sqlCheck);
        $stmt->execute([
            ':post_id' => $postId,
            ':ip' => $ip
        ]);

        $registro = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($registro) {
            $sqlUpdate = "UPDATE visualizacoes 
                      SET tempo_segundos = IFNULL(tempo_segundos, 0) + :tempo, ultima_visualizacao = NOW() 
                      WHERE id = :id";

            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $success = $stmtUpdate->execute([
                ':tempo' => $tempo,
                ':id' => $registro['id']
            ]);

            if ($success && $stmtUpdate->rowCount() > 0) {
                file_put_contents(__DIR__ . '/log.txt', "✅ Atualizado tempo_segundos (id {$registro['id']}) +$tempo segundos\n", FILE_APPEND);
            } else {
                file_put_contents(__DIR__ . '/log.txt', "❌ Falha ao atualizar tempo para id {$registro['id']}\n", FILE_APPEND);
            }
        } else {
            file_put_contents(__DIR__ . '/log.txt', "❌ Nenhum registro de visualização encontrado para IP $ip e post $postId\n", FILE_APPEND);
        }
    }
}
