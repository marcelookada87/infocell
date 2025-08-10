# 🚀 Guia de Instalação Rápida - InfoCell OS

## ⚡ Instalação em 5 Minutos

### Passo 1: Preparar o Ambiente
```bash
# 1. Certifique-se que tem PHP 8.2+ e MySQL 5.7+
php --version
mysql --version

# 2. Coloque os arquivos na pasta do seu servidor web
# XAMPP: C:\xampp\htdocs\infocell\
# WAMP: C:\wamp64\www\infocell\
# Linux: /var/www/html/infocell/
```

### Passo 2: Criar o Banco de Dados

**Opção A - Via phpMyAdmin:**
1. Abra http://localhost/phpmyadmin
2. Clique em "Novo" 
3. Nome: `infocell_os`
4. Codificação: `utf8mb4_unicode_ci`
5. Vá na aba "Importar"
6. Escolha o arquivo `database/infocell_os.sql`
7. Clique "Executar"

**Opção B - Via linha de comando:**
```bash
mysql -u root -p
CREATE DATABASE infocell_os CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE infocell_os;
SOURCE /caminho/para/database/infocell_os.sql;
```

### Passo 3: Configurar Conexão

Edite o arquivo `config/config.php`:

```php
// Para XAMPP/WAMP padrão:
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Vazio para XAMPP
define('DB_NAME', 'infocell_os');

// Ajuste a URL se necessário:
define('URLROOT', 'http://localhost/infocell');
```

### Passo 4: Testar o Sistema

1. Abra seu navegador
2. Vá para: `http://localhost/infocell`
3. Faça login com:
   - **Email:** admin@infocell.com  
   - **Senha:** admin123

### Passo 5: Configuração Inicial

1. **ALTERE A SENHA** imediatamente!
2. Vá em "Perfil" > "Alterar Senha"
3. Cadastre alguns clientes de teste
4. Crie suas primeiras ordens de serviço

## 🔧 Configurações Específicas por Ambiente

### XAMPP (Windows)
```apache
# Arquivo já configurado, só precisa:
# 1. Colocar na pasta htdocs
# 2. Configurar o banco
# 3. Acessar http://localhost/infocell
```

### WAMP (Windows)
```apache
# Mesmo processo do XAMPP
# Pasta: C:\wamp64\www\infocell\
```

### Linux (Apache)
```bash
# 1. Copiar arquivos
sudo cp -r infocell /var/www/html/

# 2. Ajustar permissões
sudo chown -R www-data:www-data /var/www/html/infocell
sudo chmod -R 755 /var/www/html/infocell

# 3. Habilitar mod_rewrite
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Docker (Opcional)
```yaml
# docker-compose.yml
version: '3.8'
services:
  web:
    image: php:8.2-apache
    ports:
      - "80:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
  
  db:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: root
      MYSQL_DATABASE: infocell_os
    ports:
      - "3306:3306"
```

## ✅ Checklist Pós-Instalação

- [ ] Sistema abre sem erros
- [ ] Login funciona
- [ ] Dashboard carrega com dados
- [ ] Consegue criar cliente
- [ ] Consegue criar ordem de serviço
- [ ] Relatórios funcionam
- [ ] Senha padrão alterada
- [ ] Backup do banco configurado

## 🚨 Problemas Comuns

### "Página não encontrada" ou erro 404
```bash
# Verificar se mod_rewrite está ativo
# Apache: sudo a2enmod rewrite
# Nginx: configurar try_files
```

### "Erro de conexão com banco"
```php
// Verificar config/config.php
// Testar conexão:
mysql -u root -p -h localhost
```

### "Erro 500"
```php
// Ativar debug em config/config.php:
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Páginas sem estilo (CSS não carrega)
```php
// Verificar URLROOT em config/config.php
// Deve apontar para pasta correta
define('URLROOT', 'http://localhost/infocell');
```

## 📱 Testando Responsividade

1. Abra o sistema no navegador
2. Pressione F12 (DevTools)
3. Clique no ícone de celular
4. Teste diferentes resoluções
5. Verifique se menu mobile funciona

## 🔐 Configurações de Segurança (Produção)

```php
// Em config/config.php para produção:

// 1. Desabilitar debug
error_reporting(0);
ini_set('display_errors', 0);

// 2. HTTPS obrigatório
ini_set('session.cookie_secure', 1);

// 3. URL de produção
define('URLROOT', 'https://seudominio.com');

// 4. Senha forte do banco
define('DB_PASS', 'senha-super-forte-aqui');
```

## 📊 Dados de Teste

O sistema já vem com:
- 1 usuário admin
- Configurações básicas
- Alguns tipos de peças comuns

Para adicionar dados de teste:
```sql
-- Execute no banco para dados de exemplo
INSERT INTO clientes (nome, telefone, email) VALUES 
('João Silva', '(11) 99999-1111', 'joao@email.com'),
('Maria Santos', '(11) 99999-2222', 'maria@email.com');
```

## 🎯 Próximos Passos

1. **Personalizar** - Altere logo, cores, nome da empresa
2. **Configurar** - Ajuste valores padrão, prazos
3. **Treinar** - Ensine sua equipe a usar
4. **Backup** - Configure rotina de backup
5. **Monitorar** - Acompanhe performance e erros

---

## 💡 Dicas Extras

- **Performance:** Use PHP OPcache em produção
- **Backup:** Configure backup automático diário
- **SSL:** Use HTTPS em produção
- **Logs:** Monitore logs de erro regularmente
- **Updates:** Mantenha PHP e MySQL atualizados

**Precisa de ajuda?** Verifique o README.md completo ou entre em contato!

🚀 **Bom trabalho com seu novo sistema de OS!**

