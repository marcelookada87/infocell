<?php

class OrdemServico
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database;
    }
    
    // Criar ordem de serviço
    public function criarOrdem($data)
    {
        $this->db->query('INSERT INTO ordens_servico (cliente_id, dispositivo_tipo, dispositivo_marca, 
                         dispositivo_modelo, problema_relatado, observacoes, valor_estimado, prioridade, 
                         status, usuario_criacao) 
                         VALUES(:cliente_id, :dispositivo_tipo, :dispositivo_marca, :dispositivo_modelo, 
                         :problema_relatado, :observacoes, :valor_estimado, :prioridade, :status, :usuario_criacao)');
        
        $this->db->bind(':cliente_id', $data['cliente_id']);
        $this->db->bind(':dispositivo_tipo', $data['dispositivo_tipo']);
        $this->db->bind(':dispositivo_marca', $data['dispositivo_marca']);
        $this->db->bind(':dispositivo_modelo', $data['dispositivo_modelo']);
        $this->db->bind(':problema_relatado', $data['problema_relatado']);
        $this->db->bind(':observacoes', $data['observacoes']);
        $this->db->bind(':valor_estimado', $data['valor_estimado']);
        $this->db->bind(':prioridade', $data['prioridade']);
        $this->db->bind(':status', 'aberta');
        $this->db->bind(':usuario_criacao', $_SESSION['user_id']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Buscar todas as ordens
    public function getOrdens()
    {
        $this->db->query('SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone,
                         u.nome as usuario_nome
                         FROM ordens_servico os
                         LEFT JOIN clientes c ON os.cliente_id = c.id
                         LEFT JOIN usuarios u ON os.usuario_criacao = u.id
                         ORDER BY os.criado_em DESC');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Buscar ordem por ID
    public function getOrdemById($id)
    {
        $this->db->query('SELECT os.*, c.nome as cliente_nome, c.telefone as cliente_telefone,
                         c.email as cliente_email, u.nome as usuario_nome
                         FROM ordens_servico os
                         LEFT JOIN clientes c ON os.cliente_id = c.id
                         LEFT JOIN usuarios u ON os.usuario_criacao = u.id
                         WHERE os.id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Atualizar ordem
    public function atualizarOrdem($data)
    {
        $this->db->query('UPDATE ordens_servico SET status = :status, problema_diagnosticado = :problema_diagnosticado,
                         solucao_aplicada = :solucao_aplicada, valor_final = :valor_final, 
                         observacoes_tecnico = :observacoes_tecnico, atualizado_em = NOW()
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':problema_diagnosticado', $data['problema_diagnosticado']);
        $this->db->bind(':solucao_aplicada', $data['solucao_aplicada']);
        $this->db->bind(':valor_final', $data['valor_final']);
        $this->db->bind(':observacoes_tecnico', $data['observacoes_tecnico']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Deletar ordem
    public function deletarOrdem($id)
    {
        $this->db->query('DELETE FROM ordens_servico WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Contar total de ordens
    public function getTotalOrdens()
    {
        $this->db->query('SELECT COUNT(*) as total FROM ordens_servico');
        
        $row = $this->db->single();
        
        return $row->total;
    }
    
    // Ordens abertas
    public function getOrdensAbertas()
    {
        $this->db->query('SELECT COUNT(*) as total FROM ordens_servico WHERE status = "aberta"');
        
        $row = $this->db->single();
        
        return $row->total;
    }
    
    // Ordens em andamento
    public function getOrdensAndamento()
    {
        $this->db->query('SELECT COUNT(*) as total FROM ordens_servico WHERE status = "em_andamento"');
        
        $row = $this->db->single();
        
        return $row->total;
    }
    
    // Ordens concluídas
    public function getOrdensConcluidas()
    {
        $this->db->query('SELECT COUNT(*) as total FROM ordens_servico WHERE status = "concluida"');
        
        $row = $this->db->single();
        
        return $row->total;
    }
    
    // Ordens recentes
    public function getOrdensRecentes($limit = 10)
    {
        $this->db->query('SELECT os.*, c.nome as cliente_nome 
                         FROM ordens_servico os
                         LEFT JOIN clientes c ON os.cliente_id = c.id
                         ORDER BY os.criado_em DESC 
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Receita mensal
    public function getReceitaMensal()
    {
        $this->db->query('SELECT SUM(valor_final) as total 
                         FROM ordens_servico 
                         WHERE status = "concluida" 
                         AND MONTH(criado_em) = MONTH(CURRENT_DATE()) 
                         AND YEAR(criado_em) = YEAR(CURRENT_DATE())');
        
        $row = $this->db->single();
        
        return $row->total ? $row->total : 0;
    }
    
    // Ordens por status
    public function getOrdensPorStatus()
    {
        $this->db->query('SELECT status, COUNT(*) as total 
                         FROM ordens_servico 
                         GROUP BY status');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Ordens por mês
    public function getOrdensPorMes()
    {
        $this->db->query('SELECT DATE_FORMAT(criado_em, "%Y-%m") as mes, COUNT(*) as total 
                         FROM ordens_servico 
                         WHERE criado_em >= DATE_SUB(CURRENT_DATE(), INTERVAL 12 MONTH)
                         GROUP BY mes 
                         ORDER BY mes');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Receita por mês
    public function getReceitaPorMes($ano = null)
    {
        $ano = $ano ? $ano : date('Y');
        
        $this->db->query('SELECT MONTH(criado_em) as mes, SUM(valor_final) as total 
                         FROM ordens_servico 
                         WHERE status = "concluida" 
                         AND YEAR(criado_em) = :ano
                         GROUP BY mes 
                         ORDER BY mes');
        $this->db->bind(':ano', $ano);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Dispositivos mais reparados
    public function getDispositivosMaisReparados($limit = 10)
    {
        $this->db->query('SELECT dispositivo_tipo, dispositivo_marca, COUNT(*) as total 
                         FROM ordens_servico 
                         GROUP BY dispositivo_tipo, dispositivo_marca 
                         ORDER BY total DESC 
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Ordens com filtros
    public function getOrdensComFiltros($filtros)
    {
        $sql = 'SELECT os.*, c.nome as cliente_nome 
                FROM ordens_servico os
                LEFT JOIN clientes c ON os.cliente_id = c.id
                WHERE 1=1';
        
        if (isset($filtros['data_inicio'])) {
            $sql .= ' AND DATE(os.criado_em) >= :data_inicio';
        }
        
        if (isset($filtros['data_fim'])) {
            $sql .= ' AND DATE(os.criado_em) <= :data_fim';
        }
        
        if (isset($filtros['status'])) {
            $sql .= ' AND os.status = :status';
        }
        
        if (isset($filtros['dispositivo_tipo'])) {
            $sql .= ' AND os.dispositivo_tipo = :dispositivo_tipo';
        }
        
        $sql .= ' ORDER BY os.criado_em DESC';
        
        $this->db->query($sql);
        
        if (isset($filtros['data_inicio'])) {
            $this->db->bind(':data_inicio', $filtros['data_inicio']);
        }
        
        if (isset($filtros['data_fim'])) {
            $this->db->bind(':data_fim', $filtros['data_fim']);
        }
        
        if (isset($filtros['status'])) {
            $this->db->bind(':status', $filtros['status']);
        }
        
        if (isset($filtros['dispositivo_tipo'])) {
            $this->db->bind(':dispositivo_tipo', $filtros['dispositivo_tipo']);
        }
        
        $results = $this->db->resultSet();
        
        return $results;
    }
}

