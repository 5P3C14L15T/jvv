<?php
require_once __DIR__ . '/../../core/Conexao.php';


class Post
{
    public static function listarTodos($limite = 10, $offset = 0, $busca = null)
    {
        $pdo = Conexao::conectar();

        $sql = "SELECT 
                posts.*, 
                categorias.nome AS nome_categoria 
            FROM posts
            LEFT JOIN categorias ON posts.id_categoria = categorias.id
            WHERE 1";

        if ($busca) {
            $sql .= " AND posts.titulo LIKE :busca";
        }

        $sql .= " ORDER BY posts.criado_em DESC LIMIT :limite OFFSET :offset";

        $stmt = $pdo->prepare($sql);

        if ($busca) {
            $stmt->bindValue(':busca', '%' . $busca . '%', PDO::PARAM_STR);
        }

        $stmt->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }




    public static function criar($titulo, $id_categoria, $status, $resumo, $conteudo, $tags, $video_url, $leitura_min, $destacado, $imagem, $slug, $autor_id)
    {
        $pdo = Conexao::conectar();
        $sql = "INSERT INTO posts (
                titulo, slug, imagem, id_categoria, resumo, conteudo, tags, video_url,
                leitura_min, status, destacado, autor_id
            ) VALUES (
                :titulo, :slug, :imagem, :id_categoria, :resumo, :conteudo, :tags, :video_url,
                :leitura_min, :status, :destacado, :autor_id
            )";

        try {
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':titulo' => $titulo,
                ':slug' => $slug,
                ':imagem' => $imagem,
                ':id_categoria' => $id_categoria, // <- AQUI
                ':resumo' => $resumo,
                ':conteudo' => $conteudo,
                ':tags' => $tags,
                ':video_url' => $video_url,
                ':leitura_min' => $leitura_min,
                ':status' => $status,
                ':destacado' => $destacado,
                ':autor_id' => $autor_id
            ]);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }


    public static function buscarPorId($id)
    {
        $pdo = Conexao::conectar();
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function atualizar($id, $titulo, $slug, $imagem, $id_categoria, $resumo, $conteudo, $tags, $video_url, $leitura_min, $status, $destacado)
    {
        try {
            $pdo = Conexao::conectar();
            $sql = "UPDATE posts SET 
                titulo = :titulo,
                slug = :slug,
                imagem = :imagem,
                id_categoria = :id_categoria,
                resumo = :resumo,
                conteudo = :conteudo,
                tags = :tags,
                video_url = :video_url,
                leitura_min = :leitura_min,
                status = :status,
                destacado = :destacado,
                atualizado_em = NOW()
            WHERE id = :id";

            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':titulo' => $titulo,
                ':slug' => $slug,
                ':imagem' => $imagem,
                ':id_categoria' => $id_categoria, // <- CORRETO AGORA
                ':resumo' => $resumo,
                ':conteudo' => $conteudo,
                ':tags' => $tags,
                ':video_url' => $video_url,
                ':leitura_min' => $leitura_min,
                ':status' => $status,
                ':destacado' => $destacado
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar post: " . $e->getMessage());
            return false;
        }
    }


    public static function slugExiste($slug)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT COUNT(*) FROM posts WHERE slug = :slug";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        return $stmt->fetchColumn() > 0;
    }

    private static function gerarSlug($texto)
    {
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);
        $texto = strtolower($texto);
        $texto = preg_replace('/[^a-z0-9 ]/', '', $texto);
        $texto = preg_replace('/\s+/', '-', trim($texto));
        return $texto;
    }


    public static function gerarSlugUnico($titulo, $id = null)
    {
        $slugBase = self::gerarSlug($titulo);
        $slug = $slugBase;
        $contador = 1;

        $pdo = Conexao::conectar();

        do {
            $sql = "SELECT COUNT(*) FROM posts WHERE slug = :slug";
            if ($id) {
                $sql .= " AND id != :id";
            }

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':slug', $slug);
            if ($id) {
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            }
            $stmt->execute();
            $existe = $stmt->fetchColumn();

            if ($existe) {
                $slug = $slugBase . '-' . $contador++;
            }
        } while ($existe);

        return $slug;
    }


    public static function deletar($id)
    {
        $pdo = Conexao::conectar();
        $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    public static function buscarPorTitulo($busca = '')
    {
        $pdo = Conexao::conectar();

        if ($busca) {
            $sql = "SELECT * FROM posts WHERE titulo LIKE :busca ORDER BY criado_em DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':busca' => '%' . $busca . '%']);
        } else {
            $sql = "SELECT * FROM posts ORDER BY criado_em DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function contarTodos($busca = null)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT COUNT(*) FROM posts";

        if ($busca) {
            $sql .= " WHERE titulo LIKE :busca";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':busca', '%' . $busca . '%');
        } else {
            $stmt = $pdo->prepare($sql);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public static function listarPaginado($pagina = 1, $limite = 10, $busca = null)
    {
        $offset = ($pagina - 1) * $limite;
        return self::listarTodos($limite, $offset, $busca);
    }

    // contato quantos posts temos em casa status
    public static function contarPostsPorStatus()
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT status, COUNT(*) AS total FROM posts GROUP BY status";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarTopVisualizacoes($limite = 5)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT titulo, visualizacoes 
            FROM posts 
            WHERE status = 'publicado' 
            ORDER BY visualizacoes DESC 
            LIMIT :limite";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarUltimos($limite = 5)
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT id, titulo, status, criado_em FROM posts ORDER BY criado_em DESC LIMIT :limite";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function contarTotalVisualizacoes()
    {
        $pdo = Conexao::conectar();
        $sql = "SELECT SUM(visualizacoes) AS total FROM posts WHERE visualizacoes IS NOT NULL";
        $stmt = $pdo->query($sql);
        return (int) $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
