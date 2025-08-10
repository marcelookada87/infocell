<?php

class Dispositivo
{
    public function __construct()
    {
        // Não precisa mais instanciar Database, as funções PDO são estáticas
    }
    
    // Buscar dispositivos mais reparados
    public function getDispositivosMaisReparados($limit = 10)
    {
        $sql = 'SELECT dispositivo_tipo, dispositivo_marca, dispositivo_modelo, COUNT(*) as total_reparos
                FROM ordens_servico 
                GROUP BY dispositivo_tipo, dispositivo_marca, dispositivo_modelo 
                ORDER BY total_reparos DESC 
                LIMIT :limit';
        $params = [':limit' => $limit];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Buscar tipos de dispositivos únicos
    public function getTiposDispositivos()
    {
        $sql = 'SELECT DISTINCT dispositivo_tipo FROM ordens_servico ORDER BY dispositivo_tipo';
        
        $result = pdo_query($sql);
        
        return pdo_fetch_array($result);
    }
    
    // Buscar marcas por tipo
    public function getMarcasPorTipo($tipo)
    {
        $sql = 'SELECT DISTINCT dispositivo_marca FROM ordens_servico 
                WHERE dispositivo_tipo = :tipo 
                ORDER BY dispositivo_marca';
        $params = [':tipo' => $tipo];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Buscar modelos por marca e tipo
    public function getModelosPorMarcaTipo($tipo, $marca)
    {
        $sql = 'SELECT DISTINCT dispositivo_modelo FROM ordens_servico 
                WHERE dispositivo_tipo = :tipo AND dispositivo_marca = :marca
                ORDER BY dispositivo_modelo';
        $params = [
            ':tipo' => $tipo,
            ':marca' => $marca
        ];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Estatísticas por tipo de dispositivo
    public function getEstatisticasPorTipo()
    {
        $sql = 'SELECT dispositivo_tipo, 
                COUNT(*) as total_ordens,
                SUM(CASE WHEN status = "concluida" THEN 1 ELSE 0 END) as concluidas,
                AVG(CASE WHEN valor_final > 0 THEN valor_final ELSE NULL END) as ticket_medio
                FROM ordens_servico 
                GROUP BY dispositivo_tipo 
                ORDER BY total_ordens DESC';
        
        $result = pdo_query($sql);
        
        return pdo_fetch_array($result);
    }
    
    // Problemas mais comuns por tipo de dispositivo
    public function getProblemasMaisComuns($tipo = null, $limit = 10)
    {
        $sql = 'SELECT problema_relatado, COUNT(*) as total_ocorrencias
                FROM ordens_servico';
        
        $params = [];
        
        if ($tipo) {
            $sql .= ' WHERE dispositivo_tipo = :tipo';
            $params[':tipo'] = $tipo;
        }
        
        $sql .= ' GROUP BY problema_relatado 
                  ORDER BY total_ocorrencias DESC 
                  LIMIT :limit';
        
        $params[':limit'] = $limit;
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Tempo médio de reparo por tipo
    public function getTempoMedioReparo()
    {
        $sql = 'SELECT dispositivo_tipo,
                AVG(DATEDIFF(atualizado_em, criado_em)) as tempo_medio_dias
                FROM ordens_servico 
                WHERE status = "concluida" AND atualizado_em IS NOT NULL
                GROUP BY dispositivo_tipo 
                ORDER BY tempo_medio_dias';
        
        $result = pdo_query($sql);
        
        return pdo_fetch_array($result);
    }
}

