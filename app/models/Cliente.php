<?php

class Cliente
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
    
    // Criar cliente
    public function criarCliente($data)
    {
        $sql = 'INSERT INTO clientes (nome, email, telefone, cpf, endereco, cidade, cep) 
                VALUES(:nome, :email, :telefone, :cpf, :endereco, :cidade, :cep)';
        
        $params = [
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':telefone' => $data['telefone'],
            ':cpf' => $data['cpf'],
            ':endereco' => $data['endereco'],
            ':cidade' => $data['cidade'],
            ':cep' => $data['cep']
        ];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar cliente: " . $e->getMessage());
        }
    }
    
    // Buscar todos os clientes
    public function getClientes()
    {
        $sql = 'SELECT * FROM clientes ORDER BY nome ASC';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar clientes: " . $e->getMessage());
        }
    }
    
    // Buscar cliente por ID
    public function getClienteById($id)
    {
        $sql = 'SELECT * FROM clientes WHERE id = :id';
        $params = [':id' => $id];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar cliente por ID: " . $e->getMessage());
        }
    }
    
    // Atualizar cliente
    public function atualizarCliente($data)
    {
        $sql = 'UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone, 
                cpf = :cpf, endereco = :endereco, cidade = :cidade, cep = :cep 
                WHERE id = :id';
        
        $params = [
            ':id' => $data['id'],
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':telefone' => $data['telefone'],
            ':cpf' => $data['cpf'],
            ':endereco' => $data['endereco'],
            ':cidade' => $data['cidade'],
            ':cep' => $data['cep']
        ];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar cliente: " . $e->getMessage());
        }
    }
    
    // Deletar cliente
    public function deletarCliente($id)
    {
        $sql = 'DELETE FROM clientes WHERE id = :id';
        $params = [':id' => $id];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute($params);
            return $result;
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar cliente: " . $e->getMessage());
        }
    }
    
    // Encontrar cliente por email
    public function findClienteByEmail($email)
    {
        $sql = 'SELECT * FROM clientes WHERE email = :email';
        $params = [':email' => $email];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return !empty($row);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar cliente por email: " . $e->getMessage());
        }
    }
    
    // Encontrar cliente por CPF
    public function findClienteByCpf($cpf)
    {
        $sql = 'SELECT * FROM clientes WHERE cpf = :cpf';
        $params = [':cpf' => $cpf];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return !empty($row);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar cliente por CPF: " . $e->getMessage());
        }
    }
    
    // Buscar clientes por nome
    public function buscarClientesPorNome($nome)
    {
        $sql = 'SELECT * FROM clientes WHERE nome LIKE :nome ORDER BY nome ASC';
        $params = [':nome' => '%' . $nome . '%'];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar clientes por nome: " . $e->getMessage());
        }
    }
    
    // Contar total de clientes
    public function getTotalClientes()
    {
        $sql = 'SELECT COUNT(*) as total FROM clientes';
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_OBJ);
            return $row->total ?? 0;
        } catch (PDOException $e) {
            throw new Exception("Erro ao contar clientes: " . $e->getMessage());
        }
    }
    
    // Clientes mais ativos (com mais ordens de serviço)
    public function getClientesMaisAtivos($limit = 10)
    {
        $sql = 'SELECT c.nome, c.telefone, COUNT(os.id) as total_ordens 
                FROM clientes c 
                LEFT JOIN ordens_servico os ON c.id = os.cliente_id 
                GROUP BY c.id 
                ORDER BY total_ordens DESC 
                LIMIT :limit';
        $params = [':limit' => $limit];
        
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar clientes mais ativos: " . $e->getMessage());
        }
    }
}

