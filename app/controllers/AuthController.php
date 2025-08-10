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
        error_log("AuthController index method called");
        
        // Redireciona para login se não estiver autenticado
        if (!$this->userModel->isLoggedIn()) {
            error_log("User not logged in, showing login page");
            $this->view('auth/login');
        } else {
            error_log("User already logged in, redirecting to dashboard");
            redirect('dashboard');
        }
    }
    
    public function login()
    {
        error_log("AuthController login method called");
        error_log("Request method: " . $_SERVER['REQUEST_METHOD']);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Debug: Verificar se $_POST tem dados
            if (empty($_POST)) {
                $data = [
                    'email' => '',
                    'password' => '',
                    'email_err' => 'Erro: Nenhum dado recebido',
                    'password_err' => ''
                ];
                $this->view('auth/login', $data);
                return;
            }
            
            // Debug: Log dos dados recebidos
            error_log("POST data received: " . print_r($_POST, true));
            
            // Sanitizar dados
            $_POST = sanitizePostData($_POST);
            
            // Debug: Log dos dados após sanitização
            error_log("POST data after sanitization: " . print_r($_POST, true));
            
            // Debug: Verificar dados após sanitização
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $data = [
                    'email' => $_POST['email'] ?? '',
                    'password' => '',
                    'email_err' => 'Erro: Dados inválidos após sanitização',
                    'password_err' => ''
                ];
                $this->view('auth/login', $data);
                return;
            }
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            
            // Debug: Log dos dados processados
            error_log("Processed data: " . print_r($data, true));
            
            // Validar email
            if (empty($data['email'])) {
                $data['email_err'] = 'Por favor, insira o email';
            }
            
            // Validar senha
            if (empty($data['password'])) {
                $data['password_err'] = 'Por favor, insira a senha';
            }
            
            // Verificar se usuário existe
            try {
                if ($this->userModel->findUserByEmail($data['email'])) {
                    error_log("User found: " . $data['email']);
                } else {
                    error_log("User not found: " . $data['email']);
                    $data['email_err'] = 'Usuário não encontrado';
                }
            } catch (Exception $e) {
                error_log("Error finding user: " . $e->getMessage());
                $data['email_err'] = 'Erro ao verificar usuário: ' . $e->getMessage();
            }
            
            // Certificar-se que não há erros
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validado
                try {
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);
                    
                    if ($loggedInUser) {
                        error_log("Login successful for user: " . $data['email']);
                        
                        // Definir dados da sessão
                        setUserSession($loggedInUser);
                        
                        // Criar cookie de autenticação
                        $this->createUserCookie($loggedInUser);
                        
                        // Redirecionar para dashboard
                        redirect('dashboard');
                    } else {
                        error_log("Login failed for user: " . $data['email'] . " - Invalid password");
                        $data['password_err'] = 'Senha incorreta';
                        $this->view('auth/login', $data);
                    }
                } catch (Exception $e) {
                    error_log("Error during login: " . $e->getMessage());
                    $data['password_err'] = 'Erro durante login: ' . $e->getMessage();
                    $this->view('auth/login', $data);
                }
            } else {
                // Carregar view com erros
                error_log("Validation errors: " . print_r($data, true));
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
        $userId = getLoggedInUserId();
        
        if ($userId) {
            // Invalidar hash no banco
            $this->userModel->invalidateAuthHash($userId);
        }
        
        // Limpar sessão
        clearSession();
        
        // Limpar cookie
        clearAuthCookie();
        
        error_log("User logged out successfully");
        redirect('auth/login');
    }
    
    private function createUserCookie($user)
    {
        try {
            error_log("Creating user cookie for user ID: " . $user->id);
            
            // Criar cookie de autenticação
            $authHash = createAuthCookie($user->id, $user->email);
            
            if ($authHash) {
                error_log("Cookie created successfully for user: " . $user->email);
                return $authHash;
            } else {
                error_log("Failed to create cookie for user: " . $user->email);
                throw new Exception("Erro ao criar cookie de autenticação");
            }
        } catch (Exception $e) {
            error_log("Error creating user cookie: " . $e->getMessage());
            throw $e;
        }
    }
}

