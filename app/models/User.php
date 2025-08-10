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
            
            $this->db->query('SELECT * FROM usuarios WHERE email = :email AND ativo = 1');
            $this->db->bind(':email', $email);
            
            $row = $this->db->single();
            
            if ($row) {
                error_log("User found in database: " . $email);
                $hashed_password = $row->senha;
                
                if (password_verify($password, $hashed_password)) {
                    error_log("Password verified successfully for: " . $email);
                    
                    // Gerar novo hash de autenticação
                    $authHash = $this->generateAndStoreAuthHash($row->id, $email);
                    
                    if ($authHash) {
                        $row->auth_hash = $authHash;
                        return $row;
                    } else {
                        error_log("Failed to generate auth hash for user: " . $email);
                        return false;
                    }
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
            throw new Exception("Erro no banco de dados durante login: " . $e->getMessage());
        }
    }
    
    // Gerar e armazenar hash de autenticação
    private function generateAndStoreAuthHash($userId, $email)
    {
        try {
            $authHash = generateAuthHash($userId, $email);
            
            $this->db->query('UPDATE usuarios SET auth_hash = :auth_hash, ultimo_login = NOW() WHERE id = :id');
            $this->db->bind(':auth_hash', $authHash);
            $this->db->bind(':id', $userId);
            
            if ($this->db->execute()) {
                error_log("Auth hash stored successfully for user ID: " . $userId);
                return $authHash;
            } else {
                error_log("Failed to store auth hash for user ID: " . $userId);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error generating/storing auth hash: " . $e->getMessage());
            throw new Exception("Erro ao gerar/armazenar hash de autenticação: " . $e->getMessage());
        }
    }
    
    // Validar hash de autenticação
    public function validateAuthHash($userId, $authHash)
    {
        try {
            $this->db->query('SELECT id, nome, email, tipo, ativo FROM usuarios WHERE id = :id AND auth_hash = :auth_hash AND ativo = 1');
            $this->db->bind(':id', $userId);
            $this->db->bind(':auth_hash', $authHash);
            
            $row = $this->db->single();
            
            if ($row && $this->db->rowCount() > 0) {
                error_log("Auth hash validated successfully for user ID: " . $userId);
                return $row;
            } else {
                error_log("Invalid auth hash for user ID: " . $userId);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error validating auth hash: " . $e->getMessage());
            throw new Exception("Erro ao validar hash de autenticação: " . $e->getMessage());
        }
    }
    
    // Invalidar hash de autenticação (logout)
    public function invalidateAuthHash($userId)
    {
        try {
            $this->db->query('UPDATE usuarios SET auth_hash = NULL WHERE id = :id');
            $this->db->bind(':id', $userId);
            
            if ($this->db->execute()) {
                error_log("Auth hash invalidated successfully for user ID: " . $userId);
                return true;
            } else {
                error_log("Failed to invalidate auth hash for user ID: " . $userId);
                return false;
            }
        } catch (Exception $e) {
            error_log("Error invalidating auth hash: " . $e->getMessage());
            throw new Exception("Erro ao invalidar hash de autenticação: " . $e->getMessage());
        }
    }
    
    // Verificar se o usuário está logado via cookie
    public function isLoggedIn()
    {
        // Primeiro verificar se há sessão ativa
        if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
            error_log("User logged in via session: " . $_SESSION['user_id']);
            return true;
        }
        
        // Se não há sessão, verificar cookie
        $cookieData = getLoggedInUser();
        
        if (!$cookieData) {
            error_log("No valid cookie found");
            return false;
        }
        
        // Validar o hash no banco
        $user = $this->validateAuthHash($cookieData['user_id'], $cookieData['hash']);
        
        if (!$user) {
            // Hash inválido, limpar cookie
            clearAuthCookie();
            return false;
        }
        
        // Se o cookie é válido, definir a sessão
        setUserSession($user);
        error_log("User logged in via cookie: " . $user->id);
        
        return $user;
    }
    
    // Verificar se o usuário logado é admin
    public function isAdmin()
    {
        $user = $this->isLoggedIn();
        return $user && $user->tipo === 'admin';
    }
    
    // Encontrar usuário por email
    public function findUserByEmail($email)
    {
        try {
            error_log("Searching for user with email: " . $email);
            
            $this->db->query('SELECT * FROM usuarios WHERE email = :email AND ativo = 1');
            
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
            throw new Exception("Erro ao buscar usuário por email: " . $e->getMessage());
        }
    }
    
    // Buscar usuário por ID
    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM usuarios WHERE id = :id AND ativo = 1');
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
        $this->db->query('SELECT id, nome, email, tipo, criado_em FROM usuarios WHERE ativo = 1 ORDER BY criado_em DESC');
        
        $results = $this->db->resultSet();
        
        return $results;
    }
}

