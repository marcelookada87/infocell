<?php

class AuthController extends Controller
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = $this->model('User');
    }
    
    public function index()
    {
        // Redireciona para login se não estiver autenticado
        if (!$this->isLoggedIn()) {
            $this->view('auth/login');
        } else {
            redirect('dashboard');
        }
    }
    
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitizar dados
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
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
            
            // Verificar se usuário existe
            if ($this->userModel->findUserByEmail($data['email'])) {
                // Usuário encontrado
            } else {
                $data['email_err'] = 'Usuário não encontrado';
            }
            
            // Certificar-se que não há erros
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validado
                $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                
                if ($loggedInUser) {
                    // Criar sessão
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Senha incorreta';
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
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->nome;
        $_SESSION['user_type'] = $user->tipo;
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

