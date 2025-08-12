<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITENAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="<?php echo URLROOT; ?>/css/login.css" rel="stylesheet">
    
    <!-- Favicon (adicione seu favicon.ico na pasta public/img/) -->
    <!-- <link rel="icon" type="image/x-icon" href="<?php echo URLROOT; ?>/img/favicon.ico"> -->
</head>
<body class="login-page">
    <div class="container-fluid h-100">
        <div class="row h-100">
            <!-- Lado esquerdo - Imagem/Branding -->
            <div class="col-lg-5 d-none d-lg-flex login-image-side">
                <div class="login-image-content">
                    <div class="text-center">
                        <i class="fas fa-mobile-alt fa-5x text-white mb-4"></i>
                        <h2 class="text-white mb-3">InfoCell</h2>
                        <h4 class="text-white-50">Sistema de Ordem de Serviço</h4>
                        <p class="text-white-50 mt-4">
                            Gerencie suas ordens de serviço de forma eficiente e profissional. 
                            Especializado em dispositivos eletrônicos, celulares, tablets e muito mais.
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Lado direito - Formulário de login -->
            <div class="col-lg-7 d-flex align-items-center">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-lg-6">
                            <div class="login-form-container">
                                <div class="text-center mb-4 d-lg-none">
                                    <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                                    <h3 class="text-primary">InfoCell OS</h3>
                                </div>
                                
                                <div class="card shadow-lg border-0">
                                    <div class="card-body p-5">
                                        <div class="text-center mb-4">
                                            <h4 class="card-title text-dark">Bem-vindo de volta!</h4>
                                            <p class="text-muted">Faça login para acessar o sistema</p>
                                        </div>
                                        
                                        <?php flash('register_success'); ?>
                                        
                                        <form action="<?php echo URLROOT; ?>/auth/login" method="post">
                                            <div class="mb-3">
                                                <label for="email" class="form-label">
                                                    <i class="fas fa-envelope"></i> Email
                                                </label>
                                                <input type="email" 
                                                       name="email" 
                                                       class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                                       id="email"
                                                       placeholder="Digite seu email"
                                                       value="<?php echo $data['email']; ?>"
                                                       required>
                                                <div class="invalid-feedback">
                                                    <?php echo $data['email_err']; ?>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label for="password" class="form-label">
                                                    <i class="fas fa-lock"></i> Senha
                                                </label>
                                                <div class="input-group">
                                                    <input type="password" 
                                                           name="password" 
                                                           class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                                                           id="password"
                                                           placeholder="Digite sua senha"
                                                           value="<?php echo $data['password']; ?>"
                                                           required>
                                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <div class="invalid-feedback">
                                                        <?php echo $data['password_err']; ?>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="d-grid mb-3">
                                                <button type="submit" class="btn btn-primary btn-lg">
                                                    <i class="fas fa-sign-in-alt"></i> Entrar
                                                </button>
                                            </div>
                                        </form>
                                        
                                        <div class="text-center">
                                            <small class="text-muted">
                                                Esqueceu sua senha? Entre em contato com o administrador.
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Dados de teste removidos -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        $(document).ready(function() {
            // Toggle password visibility
            $('#togglePassword').click(function() {
                const passwordField = $('#password');
                const passwordFieldType = passwordField.attr('type');
                const toggleIcon = $(this).find('i');
                
                if (passwordFieldType === 'password') {
                    passwordField.attr('type', 'text');
                    toggleIcon.removeClass('fa-eye').addClass('fa-eye-slash');
                } else {
                    passwordField.attr('type', 'password');
                    toggleIcon.removeClass('fa-eye-slash').addClass('fa-eye');
                }
            });
            
            // Auto-hide flash messages
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>

