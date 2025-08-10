<?php

class Cliente
{
    public function __construct()
    {
        // Não precisa mais instanciar Database, as funções PDO são estáticas
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
        
        $result = pdo_query($sql, $params);
        
        return $result !== false;
    }
    
    // Buscar todos os clientes
    public function getClientes()
    {
        $sql = 'SELECT * FROM clientes ORDER BY nome ASC';
        
        $result = pdo_query($sql);
        
        return pdo_fetch_array($result);
    }
    
    // Buscar cliente por ID
    public function getClienteById($id)
    {
        $sql = 'SELECT * FROM clientes WHERE id = :id';
        $params = [':id' => $id];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_item($result);
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
        
        $result = pdo_query($sql, $params);
        
        return $result !== false;
    }
    
    // Deletar cliente
    public function deletarCliente($id)
    {
        $sql = 'DELETE FROM clientes WHERE id = :id';
        $params = [':id' => $id];
        
        $result = pdo_query($sql, $params);
        
        return $result !== false;
    }
    
    // Encontrar cliente por email
    public function findClienteByEmail($email)
    {
        $sql = 'SELECT * FROM clientes WHERE email = :email';
        $params = [':email' => $email];
        
        $result = pdo_query($sql, $params);
        
        $row = pdo_fetch_item($result);
        
        return !empty($row);
    }
    
    // Encontrar cliente por CPF
    public function findClienteByCpf($cpf)
    {
        $sql = 'SELECT * FROM clientes WHERE cpf = :cpf';
        $params = [':cpf' => $cpf];
        
        $result = pdo_query($sql, $params);
        
        $row = pdo_fetch_item($result);
        
        return !empty($row);
    }
    
    // Buscar clientes por nome
    public function buscarClientesPorNome($nome)
    {
        $sql = 'SELECT * FROM clientes WHERE nome LIKE :nome ORDER BY nome ASC';
        $params = [':nome' => '%' . $nome . '%'];
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
    
    // Contar total de clientes
    public function getTotalClientes()
    {
        $sql = 'SELECT COUNT(*) as total FROM clientes';
        
        $result = pdo_query($sql);
        
        $row = pdo_fetch_item($result);
        
        return $row['total'] ?? 0;
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
        
        $result = pdo_query($sql, $params);
        
        return pdo_fetch_array($result);
    }
}

