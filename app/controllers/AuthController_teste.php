<?php

class AuthController extends Controller
{
    public function __construct()
    {
        // Construtor vazio para teste
    }
    
    public function index()
    {
        // Redirecionar para login se não estiver autenticado
        if (!$this->isLoggedIn()) {
            $this->login();
        } else {
            redirect('dashboard');
        }
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitizar dados
            $_POST = sanitizePostData($_POST);
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Validar email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor, insira o email';
            }
            
            // Validar senha
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor, insira a senha';
            }
            
            // Login de teste (admin@infocell.com / admin123)
            if (empty($data['email_err']) && empty($data['password_err'])) {
                if ($data['email'] == 'admin@infocell.com' && $data['password'] == 'admin123') {
                    // Criar sessão de teste
                    $this->createUserSession([
                        'id' => 1,
                        'nome' => 'Administrador',
                        'email' => 'admin@infocell.com',
                        'tipo' => 'admin'
                    ]);
                } else {
                    $data['password_err'] = 'Email ou senha incorretos';
                    $this->view('auth/login', $data);
                }
            } else {
                // Carregar view com erros
                $this->view('auth/login', $data);
            }
        } else {
            // Inicializar dados
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Carregar view
            $this->view('auth/login', $data);
        }
    }
    
    public function logout()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_type']);
        session_destroy();
        redirect('auth/login');
    }
    
    private function createUserSession($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['nome'];
        $_SESSION['user_type'] = $user['tipo'];
        redirect('dashboard');
    }
    
    private function isLoggedIn()
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }
}
