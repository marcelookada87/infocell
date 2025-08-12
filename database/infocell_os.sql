-- ============================================
-- Sistema InfoCell - Ordem de Serviço
-- Banco de Dados MySQL
-- Criado para PHP 8.2 com MVC
-- ============================================
-- ============================================
-- Tabela de Usuários
-- ============================================
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'tecnico', 'atendente') NOT NULL DEFAULT 'tecnico',
    ativo BOOLEAN DEFAULT TRUE,
    auth_hash VARCHAR(255) NULL,
    ultimo_login TIMESTAMP NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_tipo (tipo),
    INDEX idx_auth_hash (auth_hash)
) ENGINE=InnoDB;

-- ============================================
-- Tabela de Clientes
-- ============================================
CREATE TABLE clientes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100),
    telefone VARCHAR(20) NOT NULL,
    cpf VARCHAR(14),
    endereco TEXT,
    cidade VARCHAR(50),
    cep VARCHAR(10),
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_telefone (telefone),
    INDEX idx_cpf (cpf),
    UNIQUE KEY unique_cpf (cpf)
) ENGINE=InnoDB;

-- ============================================
-- Tabela de Ordens de Serviço
-- ============================================
CREATE TABLE ordens_servico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    numero_os VARCHAR(20) UNIQUE NOT NULL,
    cliente_id INT NOT NULL,
    
    -- Dados do Dispositivo
    dispositivo_tipo ENUM('celular', 'tablet', 'notebook', 'desktop', 'tv', 'console', 'outros') NOT NULL,
    dispositivo_marca VARCHAR(50) NOT NULL,
    dispositivo_modelo VARCHAR(100) NOT NULL,
    dispositivo_serial_number VARCHAR(100),
    dispositivo_imei VARCHAR(20),
    dispositivo_cor VARCHAR(30),
    dispositivo_senha VARCHAR(50),
    
    -- Problema e Diagnóstico
    problema_relatado TEXT NOT NULL,
    problema_diagnosticado TEXT,
    solucao_aplicada TEXT,
    
    -- Status e Prioridade
    status ENUM('aberta', 'em_andamento', 'aguardando_peca', 'aguardando_cliente', 'concluida', 'cancelada') NOT NULL DEFAULT 'aberta',
    prioridade ENUM('baixa', 'media', 'alta', 'urgente') NOT NULL DEFAULT 'media',
    
    -- Valores
    valor_estimado DECIMAL(10,2) DEFAULT 0.00,
    valor_final DECIMAL(10,2) DEFAULT 0.00,
    valor_pago DECIMAL(10,2) DEFAULT 0.00,
    forma_pagamento ENUM('dinheiro', 'cartao_debito', 'cartao_credito', 'pix', 'transferencia') NULL,
    
    -- Observações
    observacoes TEXT,
    observacoes_tecnico TEXT,
    observacoes_internas TEXT,
    
    -- Controle
    usuario_criacao INT NOT NULL,
    usuario_responsavel INT,
    
    -- Datas
    data_entrada DATE NOT NULL,
    data_prevista_entrega DATE,
    data_entrega DATE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Chaves estrangeiras
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_criacao) REFERENCES usuarios(id) ON DELETE RESTRICT,
    FOREIGN KEY (usuario_responsavel) REFERENCES usuarios(id) ON DELETE SET NULL,
    
    -- Índices
    INDEX idx_numero_os (numero_os),
    INDEX idx_cliente (cliente_id),
    INDEX idx_status (status),
    INDEX idx_dispositivo_tipo (dispositivo_tipo),
    INDEX idx_data_entrada (data_entrada),
    INDEX idx_criado_em (criado_em),
    INDEX idx_dispositivo_imei (dispositivo_imei),
    INDEX idx_dispositivo_serial (dispositivo_serial_number)
) ENGINE=InnoDB;

-- ============================================
-- Tabela de Peças/Componentes
-- ============================================
CREATE TABLE pecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    codigo VARCHAR(50),
    categoria VARCHAR(50),
    marca VARCHAR(50),
    modelo_compativel VARCHAR(100),
    preco_custo DECIMAL(10,2) DEFAULT 0.00,
    preco_venda DECIMAL(10,2) DEFAULT 0.00,
    estoque_atual INT DEFAULT 0,
    estoque_minimo INT DEFAULT 0,
    observacoes TEXT,
    ativo BOOLEAN DEFAULT TRUE,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_nome (nome),
    INDEX idx_codigo (codigo),
    INDEX idx_categoria (categoria)
) ENGINE=InnoDB;

-- ============================================
-- Tabela de Peças utilizadas nas OS
-- ============================================
CREATE TABLE ordem_servico_pecas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ordem_servico_id INT NOT NULL,
    peca_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1,
    preco_unitario DECIMAL(10,2) NOT NULL,
    preco_total DECIMAL(10,2) NOT NULL,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ordem_servico_id) REFERENCES ordens_servico(id) ON DELETE CASCADE,
    FOREIGN KEY (peca_id) REFERENCES pecas(id) ON DELETE RESTRICT,
    INDEX idx_ordem_servico (ordem_servico_id),
    INDEX idx_peca (peca_id)
) ENGINE=InnoDB;

-- ============================================
-- Tabela de Histórico das OS
-- ============================================
CREATE TABLE ordem_servico_historico (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ordem_servico_id INT NOT NULL,
    usuario_id INT NOT NULL,
    status_anterior VARCHAR(50),
    status_novo VARCHAR(50) NOT NULL,
    observacoes TEXT,
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ordem_servico_id) REFERENCES ordens_servico(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE RESTRICT,
    INDEX idx_ordem_servico (ordem_servico_id),
    INDEX idx_usuario (usuario_id)
) ENGINE=InnoDB;

-- ============================================
-- Tabela de Configurações do Sistema
-- ============================================
CREATE TABLE configuracoes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) UNIQUE NOT NULL,
    valor TEXT,
    descricao TEXT,
    tipo ENUM('texto', 'numero', 'boolean', 'json') DEFAULT 'texto',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ============================================
-- Inserir dados iniciais
-- ============================================

-- Usuário administrador padrão (senha: admin123)
INSERT INTO usuarios (nome, email, senha, tipo) VALUES 
('Administrador', 'admin@infocell.com', '$2y$10$ubIQwKh2ZR7oeeEcySuTlODdLIcS.br7Acu0dT2YrPllUIV1272Ma', 'admin');

-- Configurações iniciais
INSERT INTO configuracoes (chave, valor, descricao, tipo) VALUES
('empresa_nome', 'InfoCell', 'Nome da empresa', 'texto'),
('empresa_telefone', '(11) 99999-9999', 'Telefone da empresa', 'texto'),
('empresa_email', 'contato@infocell.com', 'Email da empresa', 'texto'),
('empresa_endereco', 'Rua das Flores, 123 - Centro', 'Endereço da empresa', 'texto'),
('empresa_cnpj', '00.000.000/0001-00', 'CNPJ da empresa', 'texto'),
('os_prazo_padrao', '7', 'Prazo padrão em dias para entrega', 'numero'),
('os_garantia_padrao', '90', 'Garantia padrão em dias', 'numero'),
('sistema_versao', '1.0.0', 'Versão do sistema', 'texto');

-- Peças comuns para celulares
INSERT INTO pecas (nome, categoria, preco_venda, estoque_atual) VALUES
('Tela LCD iPhone 12', 'Tela', 250.00, 5),
('Bateria iPhone 12', 'Bateria', 80.00, 10),
('Tela LCD Samsung Galaxy S21', 'Tela', 200.00, 3),
('Bateria Samsung Galaxy S21', 'Bateria', 70.00, 8),
('Película de Vidro Universal', 'Acessório', 15.00, 50),
('Capa Transparente Universal', 'Acessório', 25.00, 30);

-- ============================================
-- Triggers para automatização
-- ============================================

-- Trigger para gerar número da OS automaticamente
DELIMITER $$
CREATE TRIGGER gerar_numero_os 
    BEFORE INSERT ON ordens_servico
    FOR EACH ROW
BEGIN
    DECLARE novo_numero VARCHAR(20);
    DECLARE proximo_id INT;
    
    SELECT AUTO_INCREMENT INTO proximo_id 
    FROM information_schema.TABLES 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'ordens_servico';
    
    SET novo_numero = CONCAT('OS', YEAR(CURRENT_DATE()), LPAD(proximo_id, 6, '0'));
    SET NEW.numero_os = novo_numero;
    SET NEW.data_entrada = CURRENT_DATE();
END$$

-- Trigger para histórico de mudanças de status
CREATE TRIGGER historico_status_update
    AFTER UPDATE ON ordens_servico
    FOR EACH ROW
BEGIN
    IF OLD.status != NEW.status THEN
        INSERT INTO ordem_servico_historico (ordem_servico_id, usuario_id, status_anterior, status_novo, observacoes)
        VALUES (NEW.id, NEW.usuario_responsavel, OLD.status, NEW.status, 'Status alterado automaticamente');
    END IF;
END$$

DELIMITER ;

-- ============================================
-- Views úteis para relatórios
-- ============================================

-- View para ordens com dados do cliente
CREATE VIEW vw_ordens_completa AS
SELECT 
    os.*,
    c.nome as cliente_nome,
    c.telefone as cliente_telefone,
    c.email as cliente_email,
    u_criacao.nome as usuario_criacao_nome,
    u_responsavel.nome as usuario_responsavel_nome,
    DATEDIFF(CURRENT_DATE(), os.data_entrada) as dias_em_aberto
FROM ordens_servico os
LEFT JOIN clientes c ON os.cliente_id = c.id
LEFT JOIN usuarios u_criacao ON os.usuario_criacao = u_criacao.id
LEFT JOIN usuarios u_responsavel ON os.usuario_responsavel = u_responsavel.id;

-- View para estatísticas mensais
CREATE VIEW vw_estatisticas_mensais AS
SELECT 
    YEAR(criado_em) as ano,
    MONTH(criado_em) as mes,
    COUNT(*) as total_ordens,
    SUM(CASE WHEN status = 'concluida' THEN 1 ELSE 0 END) as ordens_concluidas,
    SUM(CASE WHEN status = 'concluida' THEN valor_final ELSE 0 END) as receita_total,
    AVG(CASE WHEN status = 'concluida' THEN valor_final ELSE NULL END) as ticket_medio
FROM ordens_servico
GROUP BY YEAR(criado_em), MONTH(criado_em)
ORDER BY ano DESC, mes DESC;

-- ============================================
-- Views otimizadas para relatórios
-- ============================================

-- View para estatísticas de ordens por status (otimizada para relatórios)
CREATE VIEW vw_relatorio_ordens_por_status AS
SELECT 
    status,
    COUNT(*) as total,
    ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM ordens_servico), 2) as percentual
FROM ordens_servico 
GROUP BY status 
ORDER BY total DESC;

-- View para estatísticas de ordens por mês (otimizada para relatórios)
CREATE VIEW vw_relatorio_ordens_por_mes AS
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
CREATE VIEW vw_relatorio_receita_por_mes AS
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
CREATE VIEW vw_relatorio_dispositivos_mais_reparados AS
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
CREATE VIEW vw_relatorio_clientes_por_cidade AS
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
CREATE VIEW vw_relatorio_clientes_mais_ativos AS
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
CREATE VIEW vw_relatorio_estatisticas_gerais AS
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
CREATE TABLE relatorio_cache_estatisticas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tipo_relatorio VARCHAR(50) NOT NULL,
    chave VARCHAR(100) NOT NULL,
    valor TEXT NOT NULL,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_tipo_chave (tipo_relatorio, chave),
    INDEX idx_data_atualizacao (data_atualizacao)
) ENGINE=InnoDB;

-- Inserir dados iniciais no cache
INSERT INTO relatorio_cache_estatisticas (tipo_relatorio, chave, valor) VALUES
('dashboard', 'total_ordens', '0'),
('dashboard', 'total_clientes', '0'),
('dashboard', 'receita_mes_atual', '0.00'),
('dashboard', 'ordens_em_andamento', '0');

-- ============================================
-- Triggers para manter estatísticas atualizadas
-- ============================================

-- Trigger para atualizar cache quando OS é criada
DELIMITER $$
CREATE TRIGGER atualizar_cache_os_criada
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
CREATE TRIGGER atualizar_cache_os_atualizada
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
CREATE TRIGGER atualizar_cache_cliente_criado
    AFTER INSERT ON clientes
    FOR EACH ROW
BEGIN
    UPDATE relatorio_cache_estatisticas 
    SET valor = (SELECT COUNT(*) FROM clientes)
    WHERE tipo_relatorio = 'dashboard' AND chave = 'total_clientes';
END$$

DELIMITER ;

-- ============================================
-- Índices adicionais para performance
-- ============================================
CREATE INDEX idx_ordens_data_status ON ordens_servico(data_entrada, status);
CREATE INDEX idx_ordens_cliente_status ON ordens_servico(cliente_id, status);
CREATE INDEX idx_clientes_nome_telefone ON clientes(nome, telefone);

-- ============================================
-- Índices adicionais para melhorar performance dos relatórios (adicionados do relatorios_otimizados.sql)
-- ============================================

CREATE INDEX idx_ordens_criado_em_status ON ordens_servico(criado_em, status);
CREATE INDEX idx_ordens_data_entrada_status ON ordens_servico(data_entrada, status);
CREATE INDEX idx_ordens_cliente_data ON ordens_servico(cliente_id, criado_em);
CREATE INDEX idx_ordens_dispositivo_status ON ordens_servico(dispositivo_tipo, status);
CREATE INDEX idx_clientes_cidade_criado ON clientes(cidade, criado_em);
CREATE INDEX idx_clientes_criado_em ON clientes(criado_em);

-- ============================================
-- Comando para atualizar estatísticas manualmente
-- ============================================

-- Para atualizar o cache manualmente, execute:
-- CALL atualizar_cache_estatisticas();
