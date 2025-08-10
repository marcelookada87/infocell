<?php

class OrdemServico
{
    public function __construct()
    {
        // Não precisa mais instanciar Database, as funções PDO são estáticas
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
        
        $result = pdo_query($sql, $params);
        
        return $result !== false;
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
        
        $result = pdo_query($sql);
        
        return pdo_fetch_array($result);
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
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_item($result);
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
        
        $result = pdo_query($sql, $params);
        
        return $result !== false;
    }
    
    // Deletar ordem
    public function deletarOrdem($id)
    {
        $sql = 'DELETE FROM ordens_servico WHERE id = :id';
        $params = [':id' => $id];
        
        $result = pdo_query($sql, $params);
        
        return $result !== false;
    }
    
    // Contar total de ordens
    public function getTotalOrdens()
    {
        $sql = 'SELECT COUNT(*) as total FROM ordens_servico';
        
        $result = pdo_query($sql);
        
        $row = pdo_fetch_item($result);
        
        return $row['total'] ?? 0;
    }
    
    // Ordens abertas
    public function getOrdensAbertas()
    {
        $sql = 'SELECT COUNT(*) as total FROM ordens_servico WHERE status = "aberta"';
        
        $result = pdo_query($sql);
        
        $row = pdo_fetch_item($result);
        
        return $row['total'] ?? 0;
    }
    
    // Ordens em andamento
    public function getOrdensAndamento()
    {
        $sql = 'SELECT COUNT(*) as total FROM ordens_servico WHERE status = "em_andamento"';
        
        $result = pdo_query($sql);
        
        $row = pdo_fetch_item($result);
        
        return $row['total'] ?? 0;
    }
    
    // Ordens concluídas
    public function getOrdensConcluidas()
    {
        $sql = 'SELECT COUNT(*) as total FROM ordens_servico WHERE status = "concluida"';
        
        $result = pdo_query($sql);
        
        $row = pdo_fetch_item($result);
        
        return $row['total'] ?? 0;
    }
    
    // Ordens recentes
    public function getOrdensRecentes($limit = 10)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome 
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                ORDER BY os.criado_em DESC 
                LIMIT :limit';
        $params = [':limit' => $limit];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Receita mensal
    public function getReceitaMensal()
    {
        $sql = 'SELECT SUM(valor_final) as total 
                FROM ordens_servico 
                WHERE status = "concluida" 
                AND MONTH(criado_em) = MONTH(CURRENT_DATE()) 
                AND YEAR(criado_em) = YEAR(CURRENT_DATE())';
        
        $result = pdo_query($sql);
        
        $row = pdo_fetch_item($result);
        
        return $row['total'] ? $row['total'] : 0;
    }
    
    // Ordens por status
    public function getOrdensPorStatus()
    {
        $sql = 'SELECT status, COUNT(*) as total 
                FROM ordens_servico 
                GROUP BY status';
        
        $result = pdo_query($sql);
        
        return pdo_fetch_array($result);
    }
    
    // Ordens por mês
    public function getOrdensPorMes()
    {
        $sql = 'SELECT DATE_FORMAT(criado_em, "%Y-%m") as mes, COUNT(*) as total 
                FROM ordens_servico 
                WHERE criado_em >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
                GROUP BY mes 
                ORDER BY mes';
        
        $result = pdo_query($sql);
        
        return pdo_fetch_array($result);
    }
    
    // Receita por mês
    public function getReceitaPorMes($ano = null)
    {
        $ano = $ano ? $ano : date('Y');
        
        $sql = 'SELECT MONTH(criado_em) as mes, SUM(valor_final) as total 
                FROM ordens_servico 
                WHERE status = "concluida" 
                AND YEAR(criado_em) = :ano
                GROUP BY mes 
                ORDER BY mes';
        $params = [':ano' => $ano];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Dispositivos mais reparados
    public function getDispositivosMaisReparados($limit = 10)
    {
        $sql = 'SELECT dispositivo_tipo, dispositivo_marca, COUNT(*) as total 
                FROM ordens_servico 
                GROUP BY dispositivo_tipo, dispositivo_marca 
                ORDER BY total DESC 
                LIMIT :limit';
        $params = [':limit' => $limit];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Ordens por cliente ID
    public function getOrdensByClienteId($cliente_id)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome 
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                WHERE os.cliente_id = :cliente_id
                ORDER BY os.criado_em DESC';
        $params = [':cliente_id' => $cliente_id];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Ordens com filtros
    public function getOrdensComFiltros($filtros)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome 
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                WHERE 1=1';
        
        $params = [];
        
        if (isset($filtros['data_inicio'])) {
            $sql .= ' AND DATE(os.criado_em) >= :data_inicio';
            $params[':data_inicio'] = $filtros['data_inicio'];
        }
        
        if (isset($filtros['data_fim'])) {
            $sql .= ' AND DATE(os.criado_em) <= :data_fim';
            $params[':data_fim'] = $filtros['data_fim'];
        }
        
        if (isset($filtros['status'])) {
            $sql .= ' AND os.status = :status';
            $params[':status'] = $filtros['status'];
        }
        
        if (isset($filtros['dispositivo_tipo'])) {
            $sql .= ' AND os.dispositivo_tipo = :dispositivo_tipo';
            $params[':dispositivo_tipo'] = $filtros['dispositivo_tipo'];
        }
        
        $sql .= ' ORDER BY os.criado_em DESC';
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
}

