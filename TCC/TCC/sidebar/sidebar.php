<?php
// Detecta a página atual dinamicamente
$current_page = basename($_SERVER['PHP_SELF']);

// Inicializa variáveis padrão
$nome_sidebar = 'Usuário';
$email_sidebar = 'usuario@email.com';
$foto_perfil_sidebar = null;
$iniciais_sidebar = 'US';

// Se estiver em sessão, busca dados do usuário
if (isset($_SESSION['id_usuario'])) {
    try {
        // Cria nova conexão apenas para a sidebar
        $conexao_sidebar = new mysqli("localhost", "root", "", "estocando");
        
        if (!$conexao_sidebar->connect_error) {
            $id_usuario_sidebar = $_SESSION['id_usuario'];
            
            $sql_sidebar = "SELECT c.nome, c.email, p.foto_perfil
                    FROM cadastrar c
                    LEFT JOIN perfil p ON c.id = p.id_usuario
                    WHERE c.id = ?";
            
            if ($stmt_sidebar = $conexao_sidebar->prepare($sql_sidebar)) {
                $stmt_sidebar->bind_param('i', $id_usuario_sidebar);
                
                if ($stmt_sidebar->execute()) {
                    $resultado_sidebar = $stmt_sidebar->get_result();
                    
                    if ($dados_sidebar = $resultado_sidebar->fetch_assoc()) {
                        $nome_sidebar = !empty($dados_sidebar['nome']) ? $dados_sidebar['nome'] : 'Usuário';
                        $email_sidebar = !empty($dados_sidebar['email']) ? $dados_sidebar['email'] : 'usuario@email.com';
                        $foto_perfil_sidebar = $dados_sidebar['foto_perfil'];
                        $iniciais_sidebar = strtoupper(substr($nome_sidebar, 0, 2));
                    }
                }
                
                $stmt_sidebar->close();
            }
            
            $conexao_sidebar->close();
        }
    } catch (Exception $e) {
        // Em caso de erro, usa valores padrão já inicializados
    }
}
?>

<div class="sidebar">
    <div class="logo">
        <div class="logo-icon">E</div>
        <div class="logo-text">stocando</div>
    </div>
    
    <nav>
        <a href="inicioPag.php" class="nav-item <?= ($current_page == 'inicioPag.php') ? 'active' : '' ?>">
            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
            Início
        </a>
        
        <a href="relatorioPag.php" class="nav-item <?= ($current_page == 'relatorioPag.php' || $current_page == 'relatorio.php') ? 'active' : '' ?>">
            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
            </svg>
            Dashboard
        </a>
        
        <a href="calendarioPag.php" class="nav-item <?= ($current_page == 'calendarioPag.php') ? 'active' : '' ?>">
            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
            Calendário
        </a>
        
        <a href="fluxoCaixaPag.php" class="nav-item <?= ($current_page == 'fluxoCaixaPag.php') ? 'active' : '' ?>">
            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8zM6 8a2 2 0 012 2v2H6V8z"/>
            </svg>
            Fluxo de caixa
        </a>
        
        <a href="estoque.php" class="nav-item <?= ($current_page == 'estoque.php') ? 'active' : '' ?>">
            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
            </svg>
            Estoque
        </a>

        <a href="perfilPag.php" class="nav-item <?= ($current_page == 'perfilPag.php') ? 'active' : '' ?>">
            <svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
            Perfil
        </a>
    </nav>
    
    <div class="user-profile">
        <?php if ($foto_perfil_sidebar): ?>
            <img src="<?= htmlspecialchars($foto_perfil_sidebar) ?>" alt="Foto de Perfil" class="user-avatar" style="object-fit:cover;">
        <?php else: ?>
            <div class="user-avatar"><?= $iniciais_sidebar ?></div>
        <?php endif; ?>

        <div class="user-info">
            <h4><?= htmlspecialchars($nome_sidebar) ?></h4>
            <p title="<?= htmlspecialchars($email_sidebar) ?>">
                <?php
                // Resume o email de forma inteligente
                if (strlen($email_sidebar) > 18) {
                    $email_parts = explode('@', $email_sidebar);
                    if (count($email_parts) == 2) {
                        $username = $email_parts[0];
                        $domain = $email_parts[1];
                        
                        // Se o username for muito longo, corta
                        if (strlen($username) > 10) {
                            $username = substr($username, 0, 10);
                        }
                        
                        // Mostra apenas parte do domínio se necessário
                        if (strlen($username . '@' . $domain) > 18) {
                            echo htmlspecialchars($username . '@...');
                        } else {
                            echo htmlspecialchars($username . '@' . $domain);
                        }
                    } else {
                        echo htmlspecialchars(substr($email_sidebar, 0, 15) . '...');
                    }
                } else {
                    echo htmlspecialchars($email_sidebar);
                }
                ?>
            </p>
        </div>
    </div>
</div>