                <?php if (isLoggedIn()): ?>
            </main>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo URLROOT; ?>/js/main.js"></script>
    
    <!-- Scripts específicos da página -->
    <?php if (isset($data['scripts'])): ?>
        <?php foreach ($data['scripts'] as $script): ?>
            <script src="<?php echo URLROOT; ?>/js/<?php echo $script; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Auto-hide flash messages -->
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $('#msg-flash').fadeOut('slow');
            }, 5000);
            
            // Confirmar exclusões
            $('.btn-delete').click(function(e) {
                if (!confirm('Tem certeza que deseja excluir este item?')) {
                    e.preventDefault();
                }
            });
            
            // Máscaras para campos
            if (typeof $ !== 'undefined' && $.fn.mask) {
                $('.telefone').mask('(00) 00000-0000');
                $('.cpf').mask('000.000.000-00');
                $('.cep').mask('00000-000');
                $('.valor').mask('#.##0,00', {reverse: true});
            }
        });
    </script>
</body>
</html>

