<?php

class DashboardController extends Controller
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
        
        // Inicializar models apenas se necessário
        try {
            $this->ordemServicoModel = $this->model('OrdemServico');
            $this->clienteModel = $this->model('Cliente');
            $this->dispositivoModel = $this->model('Dispositivo');
        } catch (Exception $e) {
            // Se houver erro com banco, usar dados mock
            $this->ordemServicoModel = null;
            $this->clienteModel = null;
            $this->dispositivoModel = null;
        }
    }
    
    public function index()
    {
        // Obter dados do usuário logado
        $loggedInUser = $this->userModel->isLoggedIn();
        
        // Buscar dados para dashboard ou usar dados mock se não há conexão
        if ($this->ordemServicoModel && $this->clienteModel && $this->dispositivoModel) {
            $data = [
                'total_ordens' => $this->ordemServicoModel->getTotalOrdens(),
                'ordens_abertas' => $this->ordemServicoModel->getOrdensAbertas(),
                'ordens_andamento' => $this->ordemServicoModel->getOrdensAndamento(),
                'ordens_concluidas' => $this->ordemServicoModel->getOrdensConcluidas(),
                'total_clientes' => $this->clienteModel->getTotalClientes(),
                'ordens_recentes' => $this->ordemServicoModel->getOrdensRecentes(10),
                'dispositivos_mais_reparados' => $this->dispositivoModel->getDispositivosMaisReparados(5),
                'receita_mensal' => $this->ordemServicoModel->getReceitaMensal(),
                'user_name' => $loggedInUser->nome ?? 'Usuário'
            ];
        } else {
            // Dados mock para demonstração
            $data = [
                'total_ordens' => 0,
                'ordens_abertas' => 0,
                'ordens_andamento' => 0,
                'ordens_concluidas' => 0,
                'total_clientes' => 0,
                'ordens_recentes' => [],
                'dispositivos_mais_reparados' => [],
                'receita_mensal' => 0,
                'user_name' => $loggedInUser->nome ?? 'Usuário'
            ];
        }
        
        $this->view('dashboard/index', $data);
    }
    
    public function perfil()
    {
        $loggedInUser = $this->userModel->isLoggedIn();
        
        $data = [
            'user' => $loggedInUser
        ];
        
        $this->view('dashboard/perfil', $data);
    }
}

