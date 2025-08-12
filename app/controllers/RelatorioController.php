<?php

class RelatorioController extends Controller
{
    private $ordemServicoModel;
    private $clienteModel;
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
    }
    
    public function index()
    {
        $data = [
            'total_ordens' => $this->ordemServicoModel->getTotalOrdens(),
            'ordens_por_status' => $this->ordemServicoModel->getOrdensPorStatusRelatorio(),
            'ordens_por_mes' => $this->ordemServicoModel->getOrdensPorMes(),
            'receita_por_mes' => $this->ordemServicoModel->getReceitaPorMes(),
            'dispositivos_mais_reparados' => $this->ordemServicoModel->getDispositivosMaisReparados(),
            'clientes_mais_ativos' => $this->clienteModel->getClientesMaisAtivos()
        ];
        
        $this->view('relatorio/index', $data);
    }
    
    public function ordens()
    {
        $filtros = [];
        
        if (isset($_GET['data_inicio']) && !empty($_GET['data_inicio'])) {
            $filtros['data_inicio'] = $_GET['data_inicio'];
        }
        
        if (isset($_GET['data_fim']) && !empty($_GET['data_fim'])) {
            $filtros['data_fim'] = $_GET['data_fim'];
        }
        
        if (isset($_GET['status']) && !empty($_GET['status'])) {
            $filtros['status'] = $_GET['status'];
        }
        
        if (isset($_GET['dispositivo_tipo']) && !empty($_GET['dispositivo_tipo'])) {
            $filtros['dispositivo_tipo'] = $_GET['dispositivo_tipo'];
        }
        
        if (isset($_GET['prioridade']) && !empty($_GET['prioridade'])) {
            $filtros['prioridade'] = $_GET['prioridade'];
        }
        
        $ordens = $this->ordemServicoModel->getOrdensComFiltros($filtros);
        
        $data = [
            'ordens' => $ordens,
            'filtros' => $filtros
        ];
        
        $this->view('relatorio/ordens', $data);
    }
    
    public function financeiro()
    {
        $ano = isset($_GET['ano']) ? $_GET['ano'] : date('Y');
        $mes = isset($_GET['mes']) ? $_GET['mes'] : null;
        
        $receitaPorMes = $this->ordemServicoModel->getReceitaPorMes($ano);
        
        $data = [
            'receita_total' => $this->ordemServicoModel->getReceitaTotal($ano, $mes),
            'receita_por_mes' => $receitaPorMes['dados'] ?? [],
            'ticket_medio' => $this->ordemServicoModel->getTicketMedio($ano, $mes),
            'ordens_pagas' => $this->ordemServicoModel->getOrdensFinalizadas($ano, $mes),
            'ano_selecionado' => $ano,
            'mes_selecionado' => $mes
        ];
        
        $this->view('relatorio/financeiro', $data);
    }
    
    public function exportarPdf($tipo)
    {
        // Implementar exportação para PDF
        switch ($tipo) {
            case 'ordens':
                $this->exportarOrdensPdf();
                break;
            case 'financeiro':
                $this->exportarFinanceiroPdf();
                break;
            default:
                redirect('relatorio');
        }
    }
    
    private function exportarOrdensPdf()
    {
        // Implementar geração de PDF das ordens
        // Usar biblioteca como TCPDF ou similar
    }
    
    private function exportarFinanceiroPdf()
    {
        // Implementar geração de PDF do relatório financeiro
    }
}

