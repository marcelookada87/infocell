<?php

class HomeController extends Controller
{
    public function index()
    {
        // Verificar se está logado
        $userModel = $this->model('User');
        
        if ($userModel->isLoggedIn()) {
            // Se logado, redirecionar para dashboard
            redirect('dashboard');
        } else {
            // Se não logado, redirecionar para login
            redirect('auth/login');
        }
    }
}
