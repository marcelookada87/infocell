<?php

class ClienteController extends Controller
{
    private $clienteModel;
    private $userModel;
    
    public function __construct()
    {
        // Verificar se está logado
        $this->userModel = $this->model('User');
        
        if (!$this->userModel->isLoggedIn()) {
            redirect('auth/login');
        }
        
        $this->clienteModel = $this->model('Cliente');
    }
    
    public function index()
    {
        $clientes = $this->clienteModel->getClientes();
        
        $data = [
            'clientes' => $clientes
        ];
        
        $this->view('cliente/index', $data);
    }
    
    public function criar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePostData($_POST);
            
            $data = [
                'nome' => trim($_POST['nome']),
                'email' => trim($_POST['email']),
                'telefone' => trim($_POST['telefone']),
                'cpf' => trim($_POST['cpf']),
                'endereco' => trim($_POST['endereco']),
                'cidade' => trim($_POST['cidade']),
                'cep' => trim($_POST['cep']),
                'nome_err' => '',
                'telefone_err' => ''
            ];
            
            // Validações
            if (empty($data['nome'])) {
                $data['nome_err'] = 'Por favor, insira o nome';
            }
            
            if (empty($data['telefone'])) {
                $data['telefone_err'] = 'Por favor, insira o telefone';
            }
            
            // Verificar se email já existe
            if (!empty($data['email']) && $this->clienteModel->findClienteByEmail($data['email'])) {
                $data['email_err'] = 'Email já cadastrado';
            }
            
            // Verificar se CPF já existe
            if (!empty($data['cpf']) && $this->clienteModel->findClienteByCpf($data['cpf'])) {
                $data['cpf_err'] = 'CPF já cadastrado';
            }
            
            // Certificar que não há erros
            if (empty($data['nome_err']) && empty($data['telefone_err']) && empty($data['email_err']) && empty($data['cpf_err'])) {
                if ($this->clienteModel->criarCliente($data)) {
                    flash('cliente_message', 'Cliente cadastrado com sucesso');
                    redirect('cliente');
                } else {
                    die('Algo deu errado');
                }
            } else {
                $this->view('cliente/criar', $data);
            }
        } else {
            $data = [
                'nome' => '',
                'email' => '',
                'telefone' => '',
                'cpf' => '',
                'endereco' => '',
                'cidade' => '',
                'cep' => ''
            ];
            
            $this->view('cliente/criar', $data);
        }
    }
    
    public function visualizar($id)
    {
        $cliente = $this->clienteModel->getClienteById($id);
        
        if (!$cliente) {
            redirect('cliente');
        }
        
        // Buscar ordens de serviço do cliente
        $ordemServicoModel = $this->model('OrdemServico');
        $ordens_servico = $ordemServicoModel->getOrdensByClienteId($id);
        
        $data = [
            'cliente' => $cliente,
            'ordens_servico' => $ordens_servico
        ];
        
        $this->view('cliente/visualizar', $data);
    }
    
    public function editar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePostData($_POST);
            
            $data = [
                'id' => $id,
                'nome' => trim($_POST['nome']),
                'email' => trim($_POST['email']),
                'telefone' => trim($_POST['telefone']),
                'cpf' => trim($_POST['cpf']),
                'endereco' => trim($_POST['endereco']),
                'cidade' => trim($_POST['cidade']),
                'cep' => trim($_POST['cep']),
                'nome_err' => '',
                'telefone_err' => ''
            ];
            
            // Validações
            if (empty($data['nome'])) {
                $data['nome_err'] = 'Por favor, insira o nome';
            }
            
            if (empty($data['telefone'])) {
                $data['telefone_err'] = 'Por favor, insira o telefone';
            }
            
            if (empty($data['nome_err']) && empty($data['telefone_err'])) {
                if ($this->clienteModel->atualizarCliente($data)) {
                    flash('cliente_message', 'Cliente atualizado com sucesso');
                    redirect('cliente');
                } else {
                    die('Algo deu errado');
                }
            } else {
                $this->view('cliente/editar', $data);
            }
        } else {
            $cliente = $this->clienteModel->getClienteById($id);
            
            if (!$cliente) {
                redirect('cliente');
            }
            
            $data = [
                'cliente' => $cliente
            ];
            
            $this->view('cliente/editar', $data);
        }
    }
    
    public function deletar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->clienteModel->deletarCliente($id)) {
                flash('cliente_message', 'Cliente removido');
                redirect('cliente');
            } else {
                die('Algo deu errado');
            }
        } else {
            redirect('cliente');
        }
    }
}

