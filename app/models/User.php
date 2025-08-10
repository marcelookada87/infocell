<?php

class User
{
    private $db;
    
    public function __construct()
    {
        $this->db = new Database;
    }
    
    // Registrar usuário
    public function register($data)
    {
        $this->db->query('INSERT INTO usuarios (nome, email, senha, tipo) VALUES(:nome, :email, :senha, :tipo)');
        
        // Bind valores
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':senha', $data['senha']);
        $this->db->bind(':tipo', $data['tipo']);
        
        // Executar
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Login do usuário
    public function login($email, $password)
    {
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        $hashed_password = $row->senha;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }
    
    // Encontrar usuário por email
    public function findUserByEmail($email)
    {
        $this->db->query('SELECT * FROM usuarios WHERE email = :email');
        
        // Bind valor
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        // Verificar linha
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    // Buscar usuário por ID
    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM usuarios WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $row = $this->db->single();
        
        return $row;
    }
    
    // Atualizar perfil do usuário
    public function updateProfile($data)
    {
        $this->db->query('UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':nome', $data['nome']);
        $this->db->bind(':email', $data['email']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Alterar senha
    public function changePassword($data)
    {
        $this->db->query('UPDATE usuarios SET senha = :senha WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':senha', $data['senha']);
        
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Listar todos os usuários (admin)
    public function getUsers()
    {
        $this->db->query('SELECT id, nome, email, tipo, criado_em FROM usuarios ORDER BY criado_em DESC');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
}

