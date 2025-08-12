<?php

class OrdemServico
{
    // Retorna o total de ordens com status 'em_andamento'
    public function getOrdensAndamento()
    {
        return $this->getTotalOrdensPorStatus('em_andamento');
    }
    // Retorna o total de ordens com status 'aberta'
    public function getOrdensAbertas()
    {
        return $this->getTotalOrdensPorStatus('aberta');
    }
    private $pdo;
    
    public function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            throw new Exception("Erro de conexão com banco: " . $e->getMessage());
        }
    }
    
    // Criar ordem de serviço
    public function criarOrdem($data)
    {
        $sql = 'INSERT INTO ordens_servico (cliente_id, dispositivo_tipo, dispositivo_marca, 
                dispositivo_modelo, dispositivo_serial_number, dispositivo_imei, problema_relatado, 
                observacoes, valor_estimado, prioridade, status, usuario_criacao) 
                VALUES(:cliente_id, :dispositivo_tipo, :dispositivo_marca, :dispositivo_modelo, 
                :dispositivo_serial_number, :dispositivo_imei, :problema_relatado, :observacoes, 
                :valor_estimado, :prioridade, :status, :usuario_criacao)';
        
        $params = [
            ':cliente_id' => $data['cliente_id'],
            ':dispositivo_tipo' => $data['dispositivo_tipo'],
            ':dispositivo_marca' => $data['dispositivo_marca'],
            ':dispositivo_modelo' => $data['dispositivo_modelo'],
            ':dispositivo_serial_number' => $data['dispositivo_serial_number'],
            ':dispositivo_imei' => $data['dispositivo_imei'],
            ':problema_relatado' => $data['problema_relatado'],
            ':observacoes' => $data['observacoes'],
            ':valor_estimado' => $data['valor_estimado'],
            ':prioridade' => $data['prioridade'],
            ':status' => 'aberta',
            ':usuario_criacao' => $_SESSION['user_id']
        ];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar ordem de serviço: " . $e->getMessage());
        }
    }
    
    // Buscar todas as ordens
    public function getOrdens()
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone,
                u.nome as usuario_nome
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                LEFT JOIN usuarios u ON os.usuario_criacao = u.id
                ORDER BY os.criado_em DESC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar ordens: " . $e->getMessage());
        }
    }
    
    // Buscar ordem por ID
    public function getOrdemById($id)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone,
                c.email as cliente_email, u.nome as usuario_nome
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                LEFT JOIN usuarios u ON os.usuario_criacao = u.id
                WHERE os.id = :id';
        $params = [':id' => $id];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar ordem por ID: " . $e->getMessage());
        }
    }
    
    // Atualizar ordem
    public function atualizarOrdem($data)
    {
        $sql = 'UPDATE ordens_servico SET status = :status, problema_diagnosticado = :problema_diagnosticado,
                solucao_aplicada = :solucao_aplicada, valor_final = :valor_final, 
                observacoes_tecnico = :observacoes_tecnico, atualizado_em = NOW()
                WHERE id = :id';
        
        $params = [
            ':id' => $data['id'],
            ':status' => $data['status'],
            ':problema_diagnosticado' => $data['problema_diagnosticado'],
            ':solucao_aplicada' => $data['solucao_aplicada'],
            ':valor_final' => $data['valor_final'],
            ':observacoes_tecnico' => $data['observacoes_tecnico']
        ];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar ordem: " . $e->getMessage());
        }
    }
    
    // Deletar ordem
    public function deletarOrdem($id)
    {
        $sql = 'DELETE FROM ordens_servico WHERE id = :id';
        $params = [':id' => $id];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar ordem: " . $e->getMessage());
        }
    }
    
    // Buscar ordens por status
    public function getOrdensPorStatus($status)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                WHERE os.status = :status
                ORDER BY os.criado_em DESC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':status' => $status]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar ordens por status: " . $e->getMessage());
        }
    }
    
    // Buscar ordens por cliente
    public function getOrdensPorCliente($clienteId)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                WHERE os.cliente_id = :cliente_id
                ORDER BY os.criado_em DESC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':cliente_id' => $clienteId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar ordens por cliente: " . $e->getMessage());
        }
    }
    
    // Buscar ordens por usuário
    public function getOrdensPorUsuario($usuarioId)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                WHERE os.usuario_criacao = :usuario_id
                ORDER BY os.criado_em DESC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':usuario_id' => $usuarioId]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar ordens por usuário: " . $e->getMessage());
        }
    }
    
    // Buscar ordens por data
    public function getOrdensPorData($data)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                WHERE DATE(os.criado_em) = :data
                ORDER BY os.criado_em DESC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':data' => $data]);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar ordens por data: " . $e->getMessage());
        }
    }
    
    // Contar total de ordens
    public function getTotalOrdens()
    {
        $sql = 'SELECT COUNT(*) as total FROM ordens_servico';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return $row->total ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar ordens: " . $e->getMessage());
        }
    }
    
    // Contar ordens por status
    public function getTotalOrdensPorStatus($status)
    {
        $sql = 'SELECT COUNT(*) as total FROM ordens_servico WHERE status = :status';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':status' => $status]);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return $row->total ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar ordens por status: " . $e->getMessage());
        }
    }
    
    // Atualizar status da ordem
    public function atualizarStatus($id, $status, $observacoes = '')
    {
        $sql = 'UPDATE ordens_servico SET status = :status, observacoes_tecnico = :observacoes, 
                atualizado_em = NOW() WHERE id = :id';
        
        $params = [
            ':id' => $id,
            ':status' => $status,
            ':observacoes' => $observacoes
        ];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar status: " . $e->getMessage());
        }
    }
    
    // Atualizar valor da ordem
    public function atualizarValor($id, $valor)
    {
        $sql = 'UPDATE ordens_servico SET valor_final = :valor, atualizado_em = NOW() WHERE id = :id';
        
        $params = [
            ':id' => $id,
            ':valor' => $valor
        ];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar valor: " . $e->getMessage());
        }
    }
    
    // Buscar ordens com paginação
    public function getOrdensComPaginacao($page = 1, $limit = 10)
    {
        $offset = ($page - 1) * $limit;
        
        $sql = 'SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone,
                u.nome as usuario_nome
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                LEFT JOIN usuarios u ON os.usuario_criacao = u.id
                ORDER BY os.criado_em DESC
                LIMIT :limit OFFSET :offset';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar ordens com paginação: " . $e->getMessage());
        }
    }
    
    // Retorna o total de ordens com status 'concluida'
    public function getOrdensConcluidas()
    {
        return $this->getTotalOrdensPorStatus('concluida');
    }
    
    // Buscar ordens recentes
    public function getOrdensRecentes($limit = 10)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone,
                u.nome as usuario_nome
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                LEFT JOIN usuarios u ON os.usuario_criacao = u.id
                ORDER BY os.criado_em DESC
                LIMIT :limit';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result ? $result : [];
        } catch (PDOException $e) {
            // Em caso de erro, retornar array vazio em vez de lançar exceção
            error_log("Erro ao buscar ordens recentes: " . $e->getMessage());
            return [];
        }
    }
    
    // Calcular receita mensal
    public function getReceitaMensal()
    {
        $sql = 'SELECT COALESCE(SUM(valor_final), 0) as receita_mensal 
                FROM ordens_servico 
                WHERE status = "concluida" 
                AND MONTH(atualizado_em) = MONTH(CURRENT_DATE()) 
                AND YEAR(atualizado_em) = YEAR(CURRENT_DATE())';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return $row->receita_mensal ?? 0;
        } catch (PDOException $e) {
            // Em caso de erro, retornar 0 em vez de lançar exceção
            error_log("Erro ao calcular receita mensal: " . $e->getMessage());
            return 0;
        }
    }
}

