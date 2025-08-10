<?php

class Dispositivo
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database;
    }
    
    // Buscar dispositivos mais reparados
    public function getDispositivosMaisReparados($limit = 10)
    {
        $this->db->query('SELECT dispositivo_tipo, dispositivo_marca, dispositivo_modelo, COUNT(*) as total_reparos
                         FROM ordens_servico 
                         GROUP BY dispositivo_tipo, dispositivo_marca, dispositivo_modelo 
                         ORDER BY total_reparos DESC 
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Buscar tipos de dispositivos únicos
    public function getTiposDispositivos()
    {
        $this->db->query('SELECT DISTINCT dispositivo_tipo FROM ordens_servico ORDER BY dispositivo_tipo');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Buscar marcas por tipo
    public function getMarcasPorTipo($tipo)
    {
        $this->db->query('SELECT DISTINCT dispositivo_marca FROM ordens_servico 
                         WHERE dispositivo_tipo = :tipo 
                         ORDER BY dispositivo_marca');
        $this->db->bind(':tipo', $tipo);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Buscar modelos por marca e tipo
    public function getModelosPorMarcaTipo($tipo, $marca)
    {
        $this->db->query('SELECT DISTINCT dispositivo_modelo FROM ordens_servico 
                         WHERE dispositivo_tipo = :tipo AND dispositivo_marca = :marca
                         ORDER BY dispositivo_modelo');
        $this->db->bind(':tipo', $tipo);
        $this->db->bind(':marca', $marca);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Estatísticas por tipo de dispositivo
    public function getEstatisticasPorTipo()
    {
        $this->db->query('SELECT dispositivo_tipo, 
                         COUNT(*) as total_ordens,
                         SUM(CASE WHEN status = "concluida" THEN 1 ELSE 0 END) as concluidas,
                         AVG(CASE WHEN valor_final > 0 THEN valor_final ELSE NULL END) as ticket_medio
                         FROM ordens_servico 
                         GROUP BY dispositivo_tipo 
                         ORDER BY total_ordens DESC');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Problemas mais comuns por tipo de dispositivo
    public function getProblemasMaisComuns($tipo = null, $limit = 10)
    {
        $sql = 'SELECT problema_relatado, COUNT(*) as total_ocorrencias
                FROM ordens_servico';
        
        if ($tipo) {
            $sql .= ' WHERE dispositivo_tipo = :tipo';
        }
        
        $sql .= ' GROUP BY problema_relatado 
                  ORDER BY total_ocorrencias DESC 
                  LIMIT :limit';
        
        $this->db->query($sql);
        
        if ($tipo) {
            $this->db->bind(':tipo', $tipo);
        }
        
        $this->db->bind(':limit', $limit);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Tempo médio de reparo por tipo
    public function getTempoMedioReparo()
    {
        $this->db->query('SELECT dispositivo_tipo,
                         AVG(DATEDIFF(atualizado_em, criado_em)) as tempo_medio_dias
                         FROM ordens_servico 
                         WHERE status = "concluida" AND atualizado_em IS NOT NULL
                         GROUP BY dispositivo_tipo 
                         ORDER BY tempo_medio_dias');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
}

