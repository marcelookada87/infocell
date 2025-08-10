<?php

class Cliente
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database;
    }
    
    // Criar cliente
    public function criarCliente($data)
    {
        $this->db->query('INSERT INTO clientes (nome, email, telefone, cpf, endereco, cidade, cep) 
                         VALUES(:nome, :email, :telefone, :cpf, :endereco, :cidade, :cep)');
        
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':telefone', $data['telefone']);
        $this->db->bind(':cpf', $data['cpf']);
        $this->db->bind(':endereco', $data['endereco']);
        $this->db->bind(':cidade', $data['cidade']);
        $this->db->bind(':cep', $data['cep']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Buscar todos os clientes
    public function getClientes()
    {
        $this->db->query('SELECT * FROM clientes ORDER BY nome ASC');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Buscar cliente por ID
    public function getClienteById($id)
    {
        $this->db->query('SELECT * FROM clientes WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Atualizar cliente
    public function atualizarCliente($data)
    {
        $this->db->query('UPDATE clientes SET nome = :nome, email = :email, telefone = :telefone, 
                         cpf = :cpf, endereco = :endereco, cidade = :cidade, cep = :cep 
                         WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':telefone', $data['telefone']);
        $this->db->bind(':cpf', $data['cpf']);
        $this->db->bind(':endereco', $data['endereco']);
        $this->db->bind(':cidade', $data['cidade']);
        $this->db->bind(':cep', $data['cep']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Deletar cliente
    public function deletarCliente($id)
    {
        $this->db->query('DELETE FROM clientes WHERE id = :id');
        $this->db->bind(':id', $id);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Encontrar cliente por email
    public function findClienteByEmail($email)
    {
        $this->db->query('SELECT * FROM clientes WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Encontrar cliente por CPF
    public function findClienteByCpf($cpf)
    {
        $this->db->query('SELECT * FROM clientes WHERE cpf = :cpf');
        $this->db->bind(':cpf', $cpf);
        
        $row = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Buscar clientes por nome
    public function buscarClientesPorNome($nome)
    {
        $this->db->query('SELECT * FROM clientes WHERE nome LIKE :nome ORDER BY nome ASC');
        $this->db->bind(':nome', '%' . $nome . '%');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
    
    // Contar total de clientes
    public function getTotalClientes()
    {
        $this->db->query('SELECT COUNT(*) as total FROM clientes');
        
        $row = $this->db->single();
        
        return $row->total;
    }
    
    // Clientes mais ativos (com mais ordens de serviço)
    public function getClientesMaisAtivos($limit = 10)
    {
        $this->db->query('SELECT c.nome, c.telefone, COUNT(os.id) as total_ordens 
                         FROM clientes c 
                         LEFT JOIN ordens_servico os ON c.id = os.cliente_id 
                         GROUP BY c.id 
                         ORDER BY total_ordens DESC 
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        
        $results = $this->db->resultSet();
        
        return $results;
    }
}

