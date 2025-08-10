# InfoCell OS - Sistema de Ordem de Serviço

Sistema completo de ordem de serviço desenvolvido em PHP 8.2 com arquitetura MVC, focado em reparos de dispositivos eletrônicos, especialmente celulares.

## 🚀 Características Principais

- **Arquitetura MVC** - Código organizado e fácil manutenção
- **Interface Responsiva** - Bootstrap 5 + jQuery para excelente experiência mobile
- **Sistema de Login Seguro** - Autenticação com hash de senhas
- **Dashboard Intuitivo** - Visão geral completa do negócio
- **Gestão de Clientes** - Cadastro completo com busca por CEP
- **Ordens de Serviço** - CRUD completo com status e prioridades
- **Relatórios** - Estatísticas e análises do negócio
- **Offline First** - Todos os arquivos incluídos, sem dependências online

## 📋 Requisitos do Sistema

- PHP 8.2 ou superior
- MySQL 5.7 ou superior
- Apache/Nginx com mod_rewrite
- Extensões PHP: PDO, PDO_MySQL, mbstring

## 🛠️ Instalação

### 1. Clonar/Baixar o Projeto

```bash
# Se usando Git
git clone https://github.com/seu-usuario/infocell-os.git

# Ou baixe e extraia o ZIP
```

### 2. Configurar o Banco de Dados

1. Crie um banco MySQL:
```sql
CREATE DATABASE infocell_os CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Importe o arquivo SQL:
```bash
mysql -u root -p infocell_os < database/infocell_os.sql
```

3. Configure as credenciais em `config/config.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'seu_usuario');
define('DB_PASS', 'sua_senha');
define('DB_NAME', 'infocell_os');
```

### 3. Configurar o Servidor Web

#### Apache (.htaccess incluído)
```apache
<VirtualHost *:80>
    ServerName infocell.local
    DocumentRoot /caminho/para/infocell/public
    <Directory /caminho/para/infocell/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### Nginx
```nginx
server {
    listen 80;
    server_name infocell.local;
    root /caminho/para/infocell/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?url=$uri&$args;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
    }
}
```

### 4. Configurar Permissões

```bash
chmod -R 755 /caminho/para/infocell
chmod -R 777 /caminho/para/infocell/public/uploads
```

## 🔐 Primeiro Acesso

Após a instalação, acesse o sistema:

- **URL:** http://seu-dominio.com
- **Email:** admin@infocell.com
- **Senha:** admin123

⚠️ **IMPORTANTE:** Altere a senha padrão imediatamente!

## 📱 Funcionalidades

### Dashboard
- Visão geral de ordens abertas, em andamento e concluídas
- Estatísticas de receita mensal
- Lista de ordens recentes
- Dispositivos mais reparados
- Ações rápidas

### Gestão de Clientes
- Cadastro completo com validação de CPF
- Busca automática de endereço por CEP
- Histórico de ordens de serviço
- Filtros e busca avançada

### Ordens de Serviço
- Criação com dados detalhados do dispositivo
- Status: Aberta, Em Andamento, Aguardando Peça, Aguardando Cliente, Concluída, Cancelada
- Prioridades: Baixa, Média, Alta, Urgente
- Controle de valores estimados e finais
- Histórico de alterações
- Impressão de OS

### Relatórios
- Estatísticas gerais do negócio
- Relatórios por período
- Análise financeira
- Dispositivos mais reparados
- Exportação para PDF/Excel

## 🎨 Interface

O sistema possui uma interface moderna e responsiva:

- **Desktop:** Sidebar fixa com navegação completa
- **Mobile:** Menu hamburger otimizado para toque
- **Tema:** Bootstrap 5 com cores personalizadas
- **Ícones:** Font Awesome 6
- **Tipografia:** Segoe UI / System fonts

## 🔧 Estrutura do Projeto

```
infocell/
├── app/
│   ├── controllers/     # Controladores MVC
│   ├── models/         # Modelos de dados
│   ├── views/          # Views/Templates
│   └── helpers/        # Funções auxiliares
├── config/             # Configurações
├── core/               # Classes base do MVC
├── database/           # Scripts SQL
├── public/             # Arquivos públicos
│   ├── css/           # Estilos
│   ├── js/            # JavaScript
│   └── img/           # Imagens
└── README.md
```

## 🛡️ Segurança

- Senhas criptografadas com bcrypt
- Proteção contra SQL Injection (PDO)
- Validação de dados de entrada
- Controle de sessões seguras
- Proteção CSRF

## 📊 Tipos de Dispositivos Suportados

- **Celulares** - Foco principal do sistema
- **Tablets** - iPads, Android tablets
- **Notebooks** - Laptops e ultrabooks
- **Desktops** - PCs e workstations
- **TVs** - Smart TVs e monitores
- **Consoles** - PlayStation, Xbox, Nintendo
- **Outros** - Categoria genérica

## 🎯 Melhores Práticas Implementadas

- **Arquitetura MVC** - Separação clara de responsabilidades
- **Responsive Design** - Mobile-first approach
- **Progressive Enhancement** - Funciona sem JavaScript
- **Semantic HTML** - Acessibilidade e SEO
- **Clean Code** - Código legível e documentado
- **Error Handling** - Tratamento adequado de erros
- **Performance** - Otimizado para velocidade

## 🚀 Próximas Funcionalidades

- [ ] Sistema de notificações
- [ ] Integração com WhatsApp
- [ ] Controle de estoque de peças
- [ ] App mobile (PWA)
- [ ] Backup automático
- [ ] Multi-empresa
- [ ] API REST

## 🐛 Solução de Problemas

### Erro 500 - Internal Server Error
- Verificar permissões dos arquivos
- Conferir configurações do .htaccess
- Verificar logs do Apache/Nginx

### Página em branco
- Verificar configurações do PHP (display_errors)
- Conferir conexão com banco de dados
- Verificar paths nos arquivos de configuração

### Erro de conexão com banco
- Verificar credenciais em config/config.php
- Confirmar se o MySQL está rodando
- Verificar se o banco foi criado corretamente

## 📞 Suporte

Para dúvidas e suporte:

- **Email:** suporte@infocell.com
- **Documentação:** [Link para docs]
- **Issues:** [Link para GitHub Issues]

## 📄 Licença

Este projeto está licenciado sob a Licença MIT - veja o arquivo [LICENSE](LICENSE) para detalhes.

## 🙏 Agradecimentos

- Bootstrap Team pelo framework CSS
- Font Awesome pelos ícones
- jQuery Team pela biblioteca JavaScript
- Comunidade PHP pelas melhores práticas

---

**Desenvolvido com ❤️ para profissionais de assistência técnica**

*Sistema InfoCell OS - Transformando a gestão de ordens de serviço*

