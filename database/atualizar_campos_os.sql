-- ============================================
-- Script para atualizar banco de dados existente
-- Adicionar campos Serial Number e IMEI na tabela ordens_servico
-- ============================================

USE infocell_os;

-- Adicionar campo serial_number se não existir
ALTER TABLE ordens_servico 
ADD COLUMN IF NOT EXISTS dispositivo_serial_number VARCHAR(100) AFTER dispositivo_modelo;

-- Adicionar campo imei se não existir (pode já existir, mas vamos garantir)
ALTER TABLE ordens_servico 
ADD COLUMN IF NOT EXISTS dispositivo_imei VARCHAR(20) AFTER dispositivo_serial_number;

-- Criar índices para os novos campos (se não existirem)
CREATE INDEX IF NOT EXISTS idx_dispositivo_imei ON ordens_servico(dispositivo_imei);
CREATE INDEX IF NOT EXISTS idx_dispositivo_serial ON ordens_servico(dispositivo_serial_number);

-- Verificar se as colunas foram criadas
DESCRIBE ordens_servico;

-- Mostrar mensagem de sucesso
SELECT 'Campos Serial Number e IMEI adicionados com sucesso!' as resultado;
