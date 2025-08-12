-- ============================================
-- Script de Otimização para Relatórios - InfoCell
-- Este arquivo contém as novas views, tabelas e triggers
-- para melhorar a performance dos relatórios
-- ============================================

-- ============================================
-- Views otimizadas para relatórios
-- ============================================

-- View para estatísticas de ordens por status (otimizada para relatórios)
CREATE OR REPLACE VIEW vw_relatorio_ordens_por_status AS
SELECT 
    status,
    COUNT(*) as total,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM ordens_servico), 2) as percentual
FROM ordens_servico 
GROUP BY status 
ORDER BY total DESC;

-- View para estatísticas de ordens por mês (otimizada para relatórios)
CREATE OR REPLACE VIEW vw_relatorio_ordens_por_mes AS
SELECT 
    DATE_FORMAT(criado_em, '%Y-%m') as mes,
    DATE_FORMAT(criado_em, '%M/%Y') as mes_nome,
    COUNT(*) as total_ordens,
    SUM(CASE WHEN status = 'concluida' THEN 1 ELSE 0 END) as ordens_concluidas,
    SUM(CASE WHEN status = 'em_andamento' THEN 1 ELSE 0 END) as ordens_em_andamento,
    SUM(CASE WHEN status = 'aberta' THEN 1 ELSE 0 END) as ordens_abertas
FROM ordens_servico 
GROUP BY DATE_FORMAT(criado_em, '%Y-%m')
ORDER BY mes DESC;

-- View para receita por mês (otimizada para relatórios)
CREATE OR REPLACE VIEW vw_relatorio_receita_por_mes AS
SELECT 
    DATE_FORMAT(criado_em, '%Y-%m') as mes,
    DATE_FORMAT(criado_em, '%M/%Y') as mes_nome,
    COUNT(*) as total_ordens,
    SUM(COALESCE(valor_final, valor_estimado)) as receita_total,
    AVG(COALESCE(valor_final, valor_estimado)) as ticket_medio,
    SUM(CASE WHEN status = 'concluida' THEN COALESCE(valor_final, valor_estimado) ELSE 0 END) as receita_concluida
FROM ordens_servico 
WHERE status IN ('concluida', 'em_andamento')
GROUP BY DATE_FORMAT(criado_em, '%Y-%m')
ORDER BY mes DESC;

-- View para dispositivos mais reparados (otimizada para relatórios)
CREATE OR REPLACE VIEW vw_relatorio_dispositivos_mais_reparados AS
SELECT 
    dispositivo_tipo,
    COUNT(*) as quantidade,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM ordens_servico), 2) as percentual,
    AVG(COALESCE(valor_final, valor_estimado)) as valor_medio,
    SUM(COALESCE(valor_final, valor_estimado)) as valor_total
FROM ordens_servico 
GROUP BY dispositivo_tipo 
ORDER BY quantidade DESC;

-- View para clientes por cidade (otimizada para relatórios)
CREATE OR REPLACE VIEW vw_relatorio_clientes_por_cidade AS
SELECT 
    COALESCE(cidade, 'Não informado') as cidade,
    COUNT(*) as total_clientes,
    COUNT(os.id) as total_ordens,
    SUM(COALESCE(os.valor_final, os.valor_estimado)) as valor_total,
    AVG(COALESCE(os.valor_final, os.valor_estimado)) as valor_medio
FROM clientes c
LEFT JOIN ordens_servico os ON c.id = os.cliente_id
GROUP BY cidade
ORDER BY total_clientes DESC;

-- View para clientes mais ativos (otimizada para relatórios)
CREATE OR REPLACE VIEW vw_relatorio_clientes_mais_ativos AS
SELECT 
    c.id,
    c.nome,
    c.telefone,
    c.cidade,
    COUNT(os.id) as total_ordens,
    SUM(COALESCE(os.valor_final, os.valor_estimado)) as valor_total,
    AVG(COALESCE(os.valor_final, os.valor_estimado)) as valor_medio,
    MAX(os.criado_em) as ultima_ordem,
    DATEDIFF(CURRENT_DATE(), MAX(os.criado_em)) as dias_desde_ultima
FROM clientes c
LEFT JOIN ordens_servico os ON c.id = os.cliente_id
GROUP BY c.id, c.nome, c.telefone, c.cidade
HAVING total_ordens > 0
ORDER BY valor_total DESC;

-- View para estatísticas gerais do sistema (otimizada para dashboard)
CREATE OR REPLACE VIEW vw_relatorio_estatisticas_gerais AS
SELECT 
    (SELECT COUNT(*) FROM ordens_servico) as total_ordens,
    (SELECT COUNT(*) FROM ordens_servico WHERE status = 'aberta') as ordens_abertas,
    (SELECT COUNT(*) FROM ordens_servico WHERE status = 'em_andamento') as ordens_em_andamento,
    (SELECT COUNT(*) FROM ordens_servico WHERE status = 'concluida') as ordens_concluidas,
    (SELECT COUNT(*) FROM clientes) as total_clientes,
    (SELECT COUNT(*) FROM usuarios WHERE ativo = 1) as usuarios_ativos,
    (SELECT SUM(COALESCE(valor_final, valor_estimado)) FROM ordens_servico WHERE status = 'concluida') as receita_total,
    (SELECT AVG(COALESCE(valor_final, valor_estimado)) FROM ordens_servico WHERE status = 'concluida') as ticket_medio,
    (SELECT COUNT(*) FROM ordens_servico WHERE DATE(criado_em) = CURRENT_DATE()) as ordens_hoje,
    (SELECT COUNT(*) FROM ordens_servico WHERE DATE(criado_em) = DATE_SUB(CURRENT_DATE(), INTERVAL 1 DAY)) as ordens_ontem;

-- ============================================
-- Tabela de cache para estatísticas (opcional - para melhorar performance)
-- ============================================

-- Tabela para armazenar estatísticas em cache (atualizada via trigger ou job)
CREATE TABLE IF NOT EXISTS relatorio_cache_estatisticas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_relatorio VARCHAR(50) NOT NULL,
    chave VARCHAR(100) NOT NULL,
    valor TEXT NOT NULL,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tipo_chave (tipo_relatorio, chave),
    INDEX idx_data_atualizacao (data_atualizacao)
) ENGINE=InnoDB;

-- Inserir dados iniciais no cache
INSERT IGNORE INTO relatorio_cache_estatisticas (tipo_relatorio, chave, valor) VALUES
('dashboard', 'total_ordens', '0'),
('dashboard', 'total_clientes', '0'),
('dashboard', 'receita_mes_atual', '0.00'),
('dashboard', 'ordens_em_andamento', '0');

-- ============================================
-- Triggers para manter estatísticas atualizadas
-- ============================================

-- Trigger para atualizar cache quando OS é criada
DELIMITER $$
CREATE TRIGGER IF NOT EXISTS atualizar_cache_os_criada
    AFTER INSERT ON ordens_servico
    FOR EACH ROW
BEGIN
    -- Atualizar total de ordens
    UPDATE relatorio_cache_estatisticas 
    SET valor = (SELECT COUNT(*) FROM ordens_servico)
    WHERE tipo_relatorio = 'dashboard' AND chave = 'total_ordens';
    
    -- Atualizar ordens em andamento
    UPDATE relatorio_cache_estatisticas 
    SET valor = (SELECT COUNT(*) FROM ordens_servico WHERE status = 'em_andamento')
    WHERE tipo_relatorio = 'dashboard' AND chave = 'ordens_em_andamento';
END$$

-- Trigger para atualizar cache quando OS é atualizada
CREATE TRIGGER IF NOT EXISTS atualizar_cache_os_atualizada
    AFTER UPDATE ON ordens_servico
    FOR EACH ROW
BEGIN
    -- Atualizar ordens em andamento se status mudou
    IF OLD.status != NEW.status THEN
        UPDATE relatorio_cache_estatisticas 
        SET valor = (SELECT COUNT(*) FROM ordens_servico WHERE status = 'em_andamento')
        WHERE tipo_relatorio = 'dashboard' AND chave = 'ordens_em_andamento';
    END IF;
    
    -- Atualizar receita se valor mudou
    IF OLD.valor_final != NEW.valor_final OR OLD.valor_estimado != NEW.valor_estimado THEN
        UPDATE relatorio_cache_estatisticas 
        SET valor = (SELECT SUM(COALESCE(valor_final, valor_estimado)) FROM ordens_servico WHERE status = 'concluida')
        WHERE tipo_relatorio = 'dashboard' AND chave = 'receita_mes_atual';
    END IF;
END$$

-- Trigger para atualizar cache quando cliente é criado
CREATE TRIGGER IF NOT EXISTS atualizar_cache_cliente_criado
    AFTER INSERT ON clientes
    FOR EACH ROW
BEGIN
    UPDATE relatorio_cache_estatisticas 
    SET valor = (SELECT COUNT(*) FROM clientes)
    WHERE tipo_relatorio = 'dashboard' AND chave = 'total_clientes';
END$$

DELIMITER ;

-- ============================================
-- Índices adicionais para melhorar performance dos relatórios
-- ============================================

-- Índices para consultas de relatórios por data
CREATE INDEX IF NOT EXISTS idx_ordens_criado_em_status ON ordens_servico(criado_em, status);
CREATE INDEX IF NOT EXISTS idx_ordens_data_entrada_status ON ordens_servico(data_entrada, status);
CREATE INDEX IF NOT EXISTS idx_ordens_cliente_data ON ordens_servico(cliente_id, criado_em);
CREATE INDEX IF NOT EXISTS idx_ordens_dispositivo_status ON ordens_servico(dispositivo_tipo, status);

-- Índices para consultas de clientes
CREATE INDEX IF NOT EXISTS idx_clientes_cidade_criado ON clientes(cidade, criado_em);
CREATE INDEX IF NOT EXISTS idx_clientes_criado_em ON clientes(criado_em);

-- ============================================
-- Comando para atualizar estatísticas manualmente
-- ============================================

-- Para atualizar o cache manualmente, execute:
-- CALL atualizar_cache_estatisticas();

-- ============================================
-- Fim do script de otimização
-- ============================================
