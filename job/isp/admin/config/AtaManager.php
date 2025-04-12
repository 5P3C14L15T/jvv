<?php

class AtaManager {
    private $conn;

    // Construtor - Recebe a conexão PDO como parâmetro
    public function __construct($db) {
        $this->conn = $db;
    }

    // Função para inserir uma nova ATA
    public function inserirAta($nome, $descricao, $arquivo) {
        // Validação da descrição
        if (strlen($descricao) > 150) {
            echo "<div class='dash_msg'>Erro: A descrição não pode exceder 150 caracteres.</div>";
            return false;
        }

        // Validação e tratamento do upload de arquivo PDF
        if ($arquivo['type'] != 'application/pdf') {
            echo "<div class='dash_msg'>Erro: Apenas arquivos PDF são permitidos.</div>";
            return false;
        }

        // Gerar um nome único para o arquivo PDF
        $nomeArquivo = uniqid('ata_') . '.pdf';
        $destino = 'uploads/' . $nomeArquivo;

        // Tentar mover o arquivo para o diretório de uploads
        if (!move_uploaded_file($arquivo['tmp_name'], $destino)) {
            echo "<div class='dash_msg'>Erro: Falha ao fazer upload do arquivo.</div>";
            return false;
        }

        // Armazenar a data atual em uma variável
        $dataAtual = date('Y-m-d H:i:s');

        // Inserir os dados no banco de dados
        $sql = "INSERT INTO atas (nome, descricao, data, url) VALUES (:nome, :descricao, :data, :url)";
        $stmt = $this->conn->prepare($sql);
        
        // Bind dos parâmetros
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':data', $dataAtual);
        $stmt->bindParam(':url', $destino);

        // Executar a query e verificar se foi bem-sucedida
        if ($stmt->execute()) {
            // Redirecionar para a página dashboard.php com uma mensagem de sucesso
            header("Location: dashboard.php?status=success");
            exit(); // Garanta que o script pare de executar após o redirecionamento
        } else {
            echo "<div class='dash_msg'>Erro: Falha ao cadastrar a ATA.</div>";
            return false;
        }
    }

     // Função para obter ATAs com paginação
     public function getAtas($page, $limit) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM atas ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Função para obter o número total de ATAs
    public function getTotalAtas() {
        $sql = "SELECT COUNT(*) as total FROM atas";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['total'];
    }

    // Função para pesquisar ATAs por nome com paginação
    public function searchAtas($searchTerm, $page, $limit) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM atas WHERE nome LIKE :searchTerm ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    // Função para obter o número total de ATAs correspondentes à pesquisa
    public function getTotalSearchAtas($searchTerm) {
        $sql = "SELECT COUNT(*) as total FROM atas WHERE nome LIKE :searchTerm";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result['total'];
    }

    // Método para atualizar uma ATA existente
     
     // Método para buscar uma ATA específica por ID
     public function getAtaById($id) {
        $sql = "SELECT * FROM atas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Método para atualizar uma ATA existente (já implementado)
    public function updateAta($id, $nome, $descricao, $arquivo) {
        // Primeiro, obter os dados atuais da ATA
        $ata = $this->getAtaById($id);

        if (!$ata) {
            echo "<div class='dash_msg'>Erro: ATA não encontrada.</div>";
            return false;
        }

        // Manter os dados antigos caso os novos não sejam fornecidos
        $nome = !empty($nome) ? $nome : $ata['nome'];
        $descricao = !empty($descricao) ? $descricao : $ata['descricao'];
        $url = $ata['url']; // Manter a URL atual do PDF

        // Verificar se um novo arquivo PDF foi enviado
        if (!empty($arquivo['tmp_name']) && $arquivo['type'] == 'application/pdf') {
            // Gerar um nome único para o novo arquivo PDF
            $nomeArquivo = uniqid('ata_') . '.pdf';
            $destino = 'uploads/' . $nomeArquivo;

            // Tentar mover o novo arquivo para o diretório de uploads
            if (move_uploaded_file($arquivo['tmp_name'], $destino)) {
                // Excluir o arquivo PDF antigo
                if (file_exists($ata['url'])) {
                    unlink($ata['url']);
                }
                // Atualizar a URL com o novo caminho
                $url = $destino;
            } else {
                echo "<div class='dash_msg'>Erro: Falha ao fazer upload do novo arquivo.</div>";
                return false;
            }
        }

        // Atualizar os dados no banco de dados
        $sql = "UPDATE atas SET nome = :nome, descricao = :descricao, url = :url WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // Executar a query e verificar se foi bem-sucedida
        if ($stmt->execute()) {
            // Redirecionar para a página dashboard.php com uma mensagem de sucesso
            header("Location: dashboard.php?status=updated");
            exit(); // Garanta que o script pare de executar após o redirecionamento
        } else {
            echo "<div class='dash_msg'>Erro: Falha ao atualizar a ATA.</div>";
            return false;
        }
    }

    
    public function deleteAta($id) {
        // Primeiro, obtenha os dados da ATA, incluindo a URL do arquivo
        $sql = "SELECT * FROM atas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ata = $stmt->fetch();
    
        if ($ata) {
            $arquivoUrl = $ata['url']; // URL do arquivo a ser deletado
    
            // Exclua o registro do banco de dados
            $sql = "DELETE FROM atas WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
            if ($stmt->execute()) {
                // Após excluir do banco de dados, exclua o arquivo físico
                if (file_exists($arquivoUrl)) {
                    unlink($arquivoUrl); // Exclua o arquivo
                }
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // Método para alternar o status de uma ATA
    public function toggleStatus($id) {
        // Primeiro, obter o status atual
        $sql = "SELECT status FROM atas WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $ata = $stmt->fetch();

        if ($ata) {
            // Alternar o status: 1 -> 0 ou 0 -> 1
            $newStatus = $ata['status'] == 1 ? 0 : 1;

            // Atualizar o status no banco de dados
            $sql = "UPDATE atas SET status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':status', $newStatus, PDO::PARAM_INT);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    // index
    
    public function getActiveAtas($page, $limit) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM atas WHERE status = 1 ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll();
    }
    
    public function getTotalActiveAtas() {
        $sql = "SELECT COUNT(*) as total FROM atas WHERE status = 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
    
        $result = $stmt->fetch();
        return $result['total'];
    }
    
    public function searchActiveAtas($searchTerm, $page, $limit) {
        $offset = ($page - 1) * $limit;
        $sql = "SELECT * FROM atas WHERE nome LIKE :searchTerm AND status = 1 ORDER BY id DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetchAll();
    }
    public function getTotalSearchActiveAtas($searchTerm) {
        $sql = "SELECT COUNT(*) as total FROM atas WHERE status = 1 AND nome LIKE :searchTerm";
        $stmt = $this->conn->prepare($sql);
        $searchTerm = "%$searchTerm%";
        $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
        $stmt->execute();
    
        $result = $stmt->fetch();
        return $result['total'];
    }
         
    
    

}
