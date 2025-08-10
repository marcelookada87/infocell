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
        try {
            error_log("Attempting login for email: " . $email);
            
            $this->db->query('SELECT * FROM usuarios WHERE email = :email');
            $this->db->bind(':email', $email);
            
            $row = $this->db->single();
            
            if ($row) {
                error_log("User found in database: " . $email);
                $hashed_password = $row->senha;
                
                if (password_verify($password, $hashed_password)) {
                    error_log("Password verified successfully for: " . $email);
                    return $row;
                } else {
                    error_log("Password verification failed for: " . $email);
                    return false;
                }
            } else {
                error_log("No user found in database for: " . $email);
                return false;
            }
        } catch (Exception $e) {
            error_log("Database error during login: " . $e->getMessage());
            throw $e;
        }
    }
    
    // Encontrar usuário por email
    public function findUserByEmail($email)
    {
        try {
            error_log("Searching for user with email: " . $email);
            
            $this->db->query('SELECT * FROM usuarios WHERE email = :email');
            
            // Bind valor
            $this->db->bind(':email', $email);
            
            $row = $this->db->single();
            
            // Verificar linha
            if ($this->db->rowCount() > 0) {
                error_log("User found with email: " . $email);
                return true;
            } else {
                error_log("No user found with email: " . $email);
                return false;
            }
        } catch (Exception $e) {
            error_log("Database error finding user by email: " . $e->getMessage());
            throw $e;
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

