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
-- Índices adicionais para performance
-- ============================================
CREATE INDEX idx_ordens_data_status ON ordens_servico(data_entrada, status);
CREATE INDEX idx_ordens_cliente_status ON ordens_servico(cliente_id, status);
CREATE INDEX idx_clientes_nome_telefone ON clientes(nome, telefone);

-- ============================================
-- Fim do script
-- ============================================