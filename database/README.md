# Banco de Dados - InfoCell OS

## Instruções de Instalação

### 1. Criando o Banco de Dados

Execute o arquivo `infocell_os.sql` no seu MySQL:

```bash
mysql -u root -p < infocell_os.sql
```

Ou importe através do phpMyAdmin, HeidiSQL ou outro cliente MySQL de sua preferência.

### 2. Configuração da Conexão

Edite o arquivo `config/config.php` com suas credenciais do banco:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'infocell_os');
```

### 3. Login Padrão

Após a instalação, use estas credenciais para primeiro acesso:

- **Email:** admin@infocell.com
- **Senha:** admin123

⚠️ **IMPORTANTE:** Altere a senha padrão imediatamente após o primeiro login!

## Estrutura do Banco

### Tabelas Principais

- **usuarios**: Controle de acesso (admin, técnico, atendente)
- **clientes**: Cadastro de clientes
- **ordens_servico**: Ordens de serviço completas
- **pecas**: Catálogo de peças e componentes
- **ordem_servico_pecas**: Peças utilizadas em cada OS
- **ordem_servico_historico**: Histórico de alterações
- **configuracoes**: Configurações do sistema

### Views Criadas

- **vw_ordens_completa**: Ordens com dados do cliente e usuários
- **vw_estatisticas_mensais**: Estatísticas mensais de vendas

### Triggers Automáticos

- **gerar_numero_os**: Gera número sequencial da OS automaticamente
- **historico_status_update**: Registra mudanças de status automaticamente

## Tipos de Dispositivos Suportados

- Celular
- Tablet  
- Notebook
- Desktop
- TV
- Console
- Outros

## Status das Ordens

- **Aberta**: Recém criada, aguardando diagnóstico
- **Em Andamento**: Sendo reparada pelo técnico
- **Aguardando Peça**: Aguardando chegada de componentes
- **Aguardando Cliente**: Aguardando aprovação/retirada
- **Concluída**: Finalizada e paga
- **Cancelada**: Cancelada pelo cliente ou técnico

## Níveis de Prioridade

- **Baixa**: Não urgente
- **Média**: Prazo normal
- **Alta**: Prioritário
- **Urgente**: Máxima prioridade

## Backup Recomendado

Execute backups regulares com:

```bash
mysqldump -u root -p infocell_os > backup_$(date +%Y%m%d_%H%M%S).sql
```

