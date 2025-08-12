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
                LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        
        try {
            $stmt = $this->pdo->prepare($sql);
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
                LIMIT ' . (int)$limit;
        
        try {
            $stmt = $this->pdo->prepare($sql);
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

    // Métodos para Relatórios
    
    // Buscar ordens por status para relatórios
    public function getOrdensPorStatusRelatorio()
    {
        $sql = 'SELECT 
                    status,
                    COUNT(*) as quantidade,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM ordens_servico)), 2) as percentual
                FROM ordens_servico 
                GROUP BY status 
                ORDER BY quantidade DESC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Erro ao buscar ordens por status: " . $e->getMessage());
            return [];
        }
    }
    
    // Buscar ordens por mês para relatórios
    public function getOrdensPorMes($ano = null)
    {
        $ano = $ano ?: date('Y');
        $sql = 'SELECT 
                    MONTH(criado_em) as mes,
                    COUNT(*) as quantidade,
                    COALESCE(SUM(valor_final), 0) as valor
                FROM ordens_servico 
                WHERE YEAR(criado_em) = :ano
                GROUP BY MONTH(criado_em)
                ORDER BY mes ASC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Erro ao buscar ordens por mês: " . $e->getMessage());
            return [];
        }
    }
    
    // Buscar receita por mês para relatórios
    public function getReceitaPorMes($ano = null)
    {
        $ano = $ano ?: date('Y');
        $sql = 'SELECT 
                    MONTH(criado_em) as mes,
                    COUNT(*) as quantidade,
                    COALESCE(SUM(valor_final), 0) as valor
                FROM ordens_servico 
                WHERE YEAR(criado_em) = :ano 
                AND status = "concluida"
                GROUP BY MONTH(criado_em)
                ORDER BY mes ASC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':ano', $ano, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Adicionar total
            $total = array_sum(array_column($result, 'valor'));
            return [
                'dados' => $result,
                'total' => $total
            ];
        } catch (PDOException $e) {
            error_log("Erro ao buscar receita por mês: " . $e->getMessage());
            return ['dados' => [], 'total' => 0];
        }
    }
    
    // Buscar dispositivos mais reparados
    public function getDispositivosMaisReparados($limit = 10)
    {
        $sql = 'SELECT 
                    dispositivo_tipo as tipo,
                    COUNT(*) as quantidade,
                    ROUND((COUNT(*) * 100.0 / (SELECT COUNT(*) FROM ordens_servico)), 2) as percentual
                FROM ordens_servico 
                GROUP BY dispositivo_tipo 
                ORDER BY quantidade DESC 
                LIMIT ' . (int)$limit;
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Erro ao buscar dispositivos mais reparados: " . $e->getMessage());
            return [];
        }
    }
    
    // Buscar receita total por período
    public function getReceitaTotal($ano = null, $mes = null)
    {
        $sql = 'SELECT COALESCE(SUM(valor_final), 0) as receita_total 
                FROM ordens_servico 
                WHERE status = "concluida"';
        $params = [];
        
        if ($ano) {
            $sql .= ' AND YEAR(criado_em) = :ano';
            $params[':ano'] = $ano;
        }
        
        if ($mes) {
            $sql .= ' AND MONTH(criado_em) = :mes';
            $params[':mes'] = $mes;
        }
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return $row->receita_total ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao calcular receita total: " . $e->getMessage());
            return 0;
        }
    }
    
    // Calcular ticket médio por período
    public function getTicketMedio($ano = null, $mes = null)
    {
        $sql = 'SELECT 
                    COUNT(*) as total_ordens,
                    COALESCE(SUM(valor_final), 0) as valor_total
                FROM ordens_servico 
                WHERE status = "concluida"';
        $params = [];
        
        if ($ano) {
            $sql .= ' AND YEAR(criado_em) = :ano';
            $params[':ano'] = $ano;
        }
        
        if ($mes) {
            $sql .= ' AND MONTH(criado_em) = :mes';
            $params[':mes'] = $mes;
        }
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            
            $totalOrdens = $row->total_ordens ?? 0;
            $valorTotal = $row->valor_total ?? 0;
            
            return $totalOrdens > 0 ? $valorTotal / $totalOrdens : 0;
        } catch (PDOException $e) {
            error_log("Erro ao calcular ticket médio: " . $e->getMessage());
            return 0;
        }
    }
    
    // Buscar ordens finalizadas por período
    public function getOrdensFinalizadas($ano = null, $mes = null)
    {
        $sql = 'SELECT COUNT(*) as total 
                FROM ordens_servico 
                WHERE status = "concluida"';
        $params = [];
        
        if ($ano) {
            $sql .= ' AND YEAR(criado_em) = :ano';
            $params[':ano'] = $ano;
        }
        
        if ($mes) {
            $sql .= ' AND MONTH(criado_em) = :mes';
            $params[':mes'] = $mes;
        }
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return $row->total ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao buscar ordens finalizadas: " . $e->getMessage());
            return 0;
        }
    }
    
    // Buscar ordens com filtros para relatórios
    public function getOrdensComFiltros($filtros = [])
    {
        $sql = 'SELECT os.*, 
                        c.nome as nome_cliente, 
                        c.telefone as telefone_cliente,
                        c.email as email_cliente,
                        d.tipo as tipo_dispositivo,
                        d.marca as marca_dispositivo,
                        d.modelo as modelo_dispositivo
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                LEFT JOIN dispositivos d ON os.dispositivo_id = d.id
                WHERE 1=1';
        
        $params = [];
        
        if (isset($filtros['data_inicio']) && !empty($filtros['data_inicio'])) {
            $sql .= ' AND DATE(os.criado_em) >= :data_inicio';
            $params[':data_inicio'] = $filtros['data_inicio'];
        }
        
        if (isset($filtros['data_fim']) && !empty($filtros['data_fim'])) {
            $sql .= ' AND DATE(os.criado_em) <= :data_fim';
            $params[':data_fim'] = $filtros['data_fim'];
        }
        
        if (isset($filtros['status']) && !empty($filtros['status'])) {
            $sql .= ' AND os.status = :status';
            $params[':status'] = $filtros['status'];
        }
        
        if (isset($filtros['dispositivo_tipo']) && !empty($filtros['dispositivo_tipo'])) {
            $sql .= ' AND d.tipo = :dispositivo_tipo';
            $params[':dispositivo_tipo'] = $filtros['dispositivo_tipo'];
        }
        
        if (isset($filtros['prioridade']) && !empty($filtros['prioridade'])) {
            $sql .= ' AND os.prioridade = :prioridade';
            $params[':prioridade'] = $filtros['prioridade'];
        }
        
        $sql .= ' ORDER BY os.criado_em DESC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Erro ao buscar ordens com filtros: " . $e->getMessage());
            return [];
        }
    }
}

