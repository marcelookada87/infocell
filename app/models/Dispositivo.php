<?php

class Dispositivo
{
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
    
    // Buscar dispositivos mais reparados
    public function getDispositivosMaisReparados($limit = 10)
    {
        $sql = 'SELECT dispositivo_tipo, dispositivo_marca, dispositivo_modelo, COUNT(*) as total_reparos
                FROM ordens_servico 
                GROUP BY dispositivo_tipo, dispositivo_marca, dispositivo_modelo 
                ORDER BY total_reparos DESC 
                LIMIT :limit';
        $params = [':limit' => $limit];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            return $result ? $result : [];
        } catch (PDOException $e) {
            // Em caso de erro, retornar array vazio em vez de lançar exceção
            error_log("Erro ao buscar dispositivos mais reparados: " . $e->getMessage());
            return [];
        }
    }
    
    // Buscar tipos de dispositivos únicos
    public function getTiposDispositivos()
    {
        $sql = 'SELECT DISTINCT dispositivo_tipo FROM ordens_servico ORDER BY dispositivo_tipo';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar tipos de dispositivos: " . $e->getMessage());
        }
    }
    
    // Buscar marcas por tipo
    public function getMarcasPorTipo($tipo)
    {
        $sql = 'SELECT DISTINCT dispositivo_marca FROM ordens_servico 
                WHERE dispositivo_tipo = :tipo 
                ORDER BY dispositivo_marca';
        $params = [':tipo' => $tipo];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar marcas por tipo: " . $e->getMessage());
        }
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
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar modelos por marca e tipo: " . $e->getMessage());
        }
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
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar estatísticas por tipo: " . $e->getMessage());
        }
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
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar problemas mais comuns: " . $e->getMessage());
        }
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
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar tempo médio de reparo: " . $e->getMessage());
        }
    }
}

