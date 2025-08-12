# 📊 Páginas de Relatórios - InfoCell

## 🎯 Visão Geral
Este diretório contém as páginas de relatórios do sistema InfoCell, criadas seguindo o mesmo padrão visual das demais páginas do sistema.

## 📁 Estrutura dos Arquivos

### 1. **`index.php`** - Dashboard Principal de Relatórios
- **Localização:** `app/views/relatorio/index.php`
- **Funcionalidades:**
  - Cards de estatísticas (Total de Ordens, Receita Total, Ticket Médio, Clientes Ativos)
  - Gráficos interativos (Ordens por Status, Receita por Mês)
  - Tabelas resumidas (Dispositivos Mais Reparados, Clientes Mais Ativos)
  - Links para relatórios detalhados
  - Modal de exportação
  - Filtros por ano, mês e tipo de relatório

### 2. **`ordens.php`** - Relatório de Ordens de Serviço
- **Localização:** `app/views/relatorio/ordens.php`
- **Funcionalidades:**
  - Filtros avançados (Data, Status, Tipo de Dispositivo, Prioridade)
  - Estatísticas do relatório (Total de Ordens, Valor Total, Ticket Médio, Em Andamento)
  - Tabela detalhada com todas as ordens filtradas
  - Resumo dos filtros aplicados
  - Funcionalidades de exportação e impressão
  - Integração com DataTables para paginação e busca

### 3. **`financeiro.php`** - Relatório Financeiro
- **Localização:** `app/views/relatorio/financeiro.php`
- **Funcionalidades:**
  - Filtros por ano e mês
  - Estatísticas financeiras (Receita Total, Ticket Médio, Ordens Finalizadas, Receita Média Mensal)
  - Gráficos (Evolução da Receita por Mês, Receita por Tipo)
  - Tabela detalhada com análise de tendências
  - Resumo por status com barras de progresso
  - Análise de performance com indicadores visuais

## 🎨 Características de Design

### **Padrão Visual Consistente**
- Header com título e botões de ação
- Filtros colapsáveis
- Cards de estatísticas com bordas coloridas
- Gráficos responsivos usando Chart.js
- Tabelas com DataTables
- Modal de exportação
- Botões de navegação e ações

### **Responsividade**
- Layout adaptável para dispositivos móveis
- Gráficos responsivos
- Tabelas com scroll horizontal
- Botões organizados em grupos

### **Interatividade**
- Filtros dinâmicos
- Gráficos interativos
- Dropdowns de ações
- Modais funcionais
- Navegação entre páginas

## 🔧 Funcionalidades Técnicas

### **Integração com Modelos**
- `OrdemServico` - Métodos para relatórios
- `Cliente` - Métodos para clientes ativos
- `User` - Verificação de autenticação

### **Gráficos e Visualizações**
- **Chart.js** para gráficos interativos
- Gráficos de pizza (Ordens por Status)
- Gráficos de linha (Receita por Mês)
- Gráficos de rosca (Receita por Tipo)

### **Tabelas e Dados**
- **DataTables** para funcionalidades avançadas
- Paginação automática
- Busca e filtros
- Exportação para múltiplos formatos
- Responsividade

### **Filtros e Busca**
- Filtros por período (ano/mês)
- Filtros por status
- Filtros por tipo de dispositivo
- Filtros por prioridade
- Filtros por tipo de receita

## 📊 Métodos de Relatórios Implementados

### **OrdemServico Model**
- `getOrdensPorStatus()` - Estatísticas por status
- `getOrdensPorMes()` - Ordens agrupadas por mês
- `getReceitaPorMes()` - Receita mensal
- `getDispositivosMaisReparados()` - Top dispositivos
- `getReceitaTotal()` - Receita total por período
- `getTicketMedio()` - Ticket médio por período
- `getOrdensFinalizadas()` - Contagem de ordens finalizadas
- `getOrdensComFiltros()` - Busca com filtros avançados

### **Cliente Model**
- `getClientesMaisAtivos()` - Top clientes por atividade

## 🚀 Como Usar

### **1. Acessar Relatórios**
```
URL: /relatorio
Menu: Relatórios > Dashboard
```

### **2. Navegar entre Relatórios**
- **Dashboard:** Visão geral com estatísticas principais
- **Relatório de OS:** Análise detalhada das ordens de serviço
- **Financeiro:** Análise financeira e receitas

### **3. Aplicar Filtros**
- Use os filtros colapsáveis em cada página
- Selecione períodos específicos
- Filtre por status, tipo de dispositivo, etc.
- Aplique múltiplos filtros simultaneamente

### **4. Exportar Dados**
- Use os botões de exportação
- Escolha entre PDF e Excel
- Configure período e tipo de relatório
- Imprima diretamente do navegador

## 🎯 Benefícios

### **Para Usuários**
- Interface intuitiva e familiar
- Acesso rápido às informações
- Filtros poderosos e flexíveis
- Visualizações claras e informativas

### **Para Gestores**
- Visão completa do negócio
- Análise de tendências
- Relatórios exportáveis
- Métricas de performance

### **Para Desenvolvedores**
- Código organizado e reutilizável
- Padrões consistentes
- Fácil manutenção
- Escalabilidade

## 🔮 Funcionalidades Futuras

### **Próximas Implementações**
- Exportação real para PDF/Excel
- Gráficos mais avançados
- Relatórios personalizados
- Agendamento de relatórios
- Notificações automáticas

### **Melhorias Planejadas**
- Dashboards personalizáveis
- Mais tipos de gráficos
- Relatórios comparativos
- Análise preditiva
- Integração com BI

## 📝 Notas de Desenvolvimento

### **Dependências**
- Bootstrap 5.3.0
- Chart.js
- DataTables
- Font Awesome 6.4.0
- jQuery 3.7.1

### **Compatibilidade**
- Navegadores modernos
- Dispositivos móveis
- Impressão otimizada
- Acessibilidade básica

---

**InfoCell - Sistema de Gestão de Ordens de Serviço**  
*Relatórios Profissionais e Intuitivos* 📊✨
