<?php

class HomeController extends Controller
{
    public function index()
    {
        // Verificar se está logado
        if (isset($_SESSION['user_id'])) {
            // Se logado, redirecionar para dashboard
            redirect('dashboard');
        } else {
            // Se não logado, redirecionar para login
            redirect('auth/login');
        }
    }
}
