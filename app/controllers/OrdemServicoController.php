<?php

class OrdemServicoController extends Controller
{
    private $ordemServicoModel;
    private $clienteModel;
    private $dispositivoModel;
    private $userModel;
    
    public function __construct()
    {
        // Verificar se está logado
        $this->userModel = $this->model('User');
        
        if (!$this->userModel->isLoggedIn()) {
            redirect('auth/login');
        }
        
        $this->ordemServicoModel = $this->model('OrdemServico');
        $this->clienteModel = $this->model('Cliente');
        $this->dispositivoModel = $this->model('Dispositivo');
    }
    
    public function index()
    {
        $ordens = $this->ordemServicoModel->getOrdens();
        
        $data = [
            'ordens' => $ordens
        ];
        
        $this->view('ordem_servico/index', $data);
    }
    
    public function criar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePostData($_POST);
            
            $data = [
                'cliente_id' => trim($_POST['cliente_id']),
                'dispositivo_tipo' => trim($_POST['dispositivo_tipo']),
                'dispositivo_marca' => trim($_POST['dispositivo_marca']),
                'dispositivo_modelo' => trim($_POST['dispositivo_modelo']),
                'dispositivo_serial_number' => trim($_POST['dispositivo_serial_number']),
                'dispositivo_imei' => trim($_POST['dispositivo_imei']),
                'problema_relatado' => trim($_POST['problema_relatado']),
                'observacoes' => trim($_POST['observacoes']),
                'valor_estimado' => trim($_POST['valor_estimado']),
                'prioridade' => trim($_POST['prioridade']),
                'cliente_id_err' => '',
                'dispositivo_tipo_err' => '',
                'problema_relatado_err' => ''
            ];
            
            // Validações
            if (empty($data['cliente_id'])) {
                $data['cliente_id_err'] = 'Por favor, selecione um cliente';
            }
            
            if (empty($data['dispositivo_tipo'])) {
                $data['dispositivo_tipo_err'] = 'Por favor, selecione o tipo de dispositivo';
            }
            
            if (empty($data['problema_relatado'])) {
                $data['problema_relatado_err'] = 'Por favor, descreva o problema relatado';
            }
            
            // Certificar que não há erros
            if (empty($data['cliente_id_err']) && empty($data['dispositivo_tipo_err']) && empty($data['problema_relatado_err'])) {
                if ($this->ordemServicoModel->criarOrdem($data)) {
                    flash('ordem_message', 'Ordem de serviço criada com sucesso');
                    redirect('ordem-servico');
                } else {
                    die('Algo deu errado');
                }
            } else {
                // Carregar view com erros
                $data['clientes'] = $this->clienteModel->getClientes();
                $this->view('ordem_servico/criar', $data);
            }
        } else {
            $data = [
                'cliente_id' => '',
                'dispositivo_tipo' => '',
                'dispositivo_marca' => '',
                'dispositivo_modelo' => '',
                'dispositivo_serial_number' => '',
                'dispositivo_imei' => '',
                'problema_relatado' => '',
                'observacoes' => '',
                'valor_estimado' => '',
                'prioridade' => 'media',
                'clientes' => $this->clienteModel->getClientes()
            ];
            
            $this->view('ordem_servico/criar', $data);
        }
    }
    
    public function visualizar($id)
    {
        $ordem = $this->ordemServicoModel->getOrdemById($id);
        $cliente = $this->clienteModel->getClienteById($ordem->cliente_id);
        
        if (!$ordem) {
            redirect('ordem-servico');
        }
        
        $data = [
            'ordem' => $ordem,
            'cliente' => $cliente
        ];
        
        $this->view('ordem_servico/visualizar', $data);
    }
    
    public function editar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = sanitizePostData($_POST);
            
            $data = [
                'id' => $id,
                'status' => trim($_POST['status']),
                'problema_diagnosticado' => trim($_POST['problema_diagnosticado']),
                'solucao_aplicada' => trim($_POST['solucao_aplicada']),
                'valor_final' => trim($_POST['valor_final']),
                'observacoes_tecnico' => trim($_POST['observacoes_tecnico'])
            ];
            
            if ($this->ordemServicoModel->atualizarOrdem($data)) {
                flash('ordem_message', 'Ordem de serviço atualizada com sucesso');
                redirect('ordem-servico/visualizar/' . $id);
            } else {
                die('Algo deu errado');
            }
        } else {
            $ordem = $this->ordemServicoModel->getOrdemById($id);
            $cliente = $this->clienteModel->getClienteById($ordem->cliente_id);
            
            if (!$ordem) {
                redirect('ordem-servico');
            }
            
            $data = [
                'ordem' => $ordem,
                'cliente' => $cliente
            ];
            
            $this->view('ordem_servico/editar', $data);
        }
    }
    
    public function deletar($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->ordemServicoModel->deletarOrdem($id)) {
                flash('ordem_message', 'Ordem de serviço removida');
                redirect('ordem-servico');
            } else {
                die('Algo deu errado');
            }
        } else {
            redirect('ordem-servico');
        }
    }
}

