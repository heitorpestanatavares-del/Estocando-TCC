<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

$sql = "SELECT 
            c.nome as nome_cadastro,
            c.email as email_cadastro,
            p.*
        FROM cadastrar c
        LEFT JOIN perfil p ON c.id = p.id_usuario
        WHERE c.id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$perfil = $resultado->fetch_assoc();
$stmt->close();

if ($perfil['id'] === null) {
    $perfil = [
        'nome_cadastro' => $perfil['nome_cadastro'],
        'email_cadastro' => $perfil['email_cadastro'],
        'nome_exibicao' => $perfil['nome_cadastro'],
        'email' => $perfil['email_cadastro'],
        'telefone' => '',
        'empresa' => '',
        'cargo' => '',
        'departamento' => '',
        'localizacao' => '',
        'bio' => '',
        'foto_perfil' => null,
        'banner_perfil' => null
    ];
}

$perfil['nome_exibicao'] = $perfil['nome_exibicao'] ?? $perfil['nome_cadastro'];
$perfil['email'] = $perfil['email'] ?? $perfil['email_cadastro'];

$foto_url = !empty($perfil['foto_perfil']) && file_exists($perfil['foto_perfil']) 
    ? $perfil['foto_perfil'] 
    : 'https://via.placeholder.com/150';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Aurora</title>
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="perfil.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="breadcrumb">
            <a href="#">Páginas</a>
            <span>/</span>
            <span>Perfil</span>
        </div>

        <div class="profile-header">
            <div class="profile-banner">
                <canvas id="bannerCanvas"></canvas>
            </div>
            
            <div class="profile-info-container">
                <div class="profile-avatar-section">
                    <div class="profile-avatar">
                        <img id="profileImage" src="<?php echo htmlspecialchars($foto_url); ?>" alt="Profile">
                    </div>
                    
                    <div class="profile-details">
                        <h1 class="profile-name">
                            <?php echo htmlspecialchars($perfil['nome_exibicao']); ?>
                        </h1>
                        <p class="profile-bio">
                            <?php echo htmlspecialchars($perfil['bio'] ?? 'Sem biografia'); ?>
                        </p>
                        <div class="profile-links">
                            <?php if (!empty($perfil['telefone'])): ?>
                            <a href="tel:<?php echo htmlspecialchars($perfil['telefone']); ?>" class="profile-link">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M3.654 1.328a.678.678 0 0 0-1.015-.063L1.605 2.3c-.483.484-.661 1.169-.45 1.77a17.568 17.568 0 0 0 4.168 6.608 17.569 17.569 0 0 0 6.608 4.168c.601.211 1.286.033 1.77-.45l1.034-1.034a.678.678 0 0 0-.063-1.015l-2.307-1.794a.678.678 0 0 0-.58-.122l-2.19.547a1.745 1.745 0 0 1-1.657-.459L5.482 8.062a1.745 1.745 0 0 1-.46-1.657l.548-2.19a.678.678 0 0 0-.122-.58L3.654 1.328z"/>
                                </svg>
                                <?php echo htmlspecialchars($perfil['telefone']); ?>
                            </a>
                            <?php endif; ?>
                            
                            <a href="mailto:<?php echo htmlspecialchars($perfil['email']); ?>" class="profile-link">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.708 2.825L15 11.105V5.383zm-.034 6.878L9.271 8.936 8 9.583l-1.271-.647-5.695 3.324A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                                </svg>
                                <?php echo htmlspecialchars($perfil['email']); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="profile-actions">
                    <button class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.708 2.825L15 11.105V5.383zm-.034 6.878L9.271 8.936 8 9.583l-1.271-.647-5.695 3.324A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                        </svg>
                        Mensagem
                    </button>
                    <button class="btn btn-icon">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                            <circle cx="8" cy="4" r="1.5"/>
                            <circle cx="8" cy="8" r="1.5"/>
                            <circle cx="8" cy="12" r="1.5"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="profile-content">
            <div class="profile-card">
                <h2 class="card-title">Foto do Perfil</h2>
                <div class="profile-photo-section">
                    <img id="previewImage" src="<?php echo htmlspecialchars($foto_url); ?>" alt="Preview" class="photo-preview">
                    <button class="btn btn-primary" onclick="document.getElementById('photoInput').click()">
                        Escolher Foto
                    </button>
                    <input type="file" id="photoInput" accept="image/*" style="display: none;">
                </div>

                <div class="form-group">
                    <label>Nome de Exibição</label>
                    <input type="text" class="form-input" id="displayName" value="<?php echo htmlspecialchars($perfil['nome_exibicao']); ?>">
                </div>

                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" class="form-input" id="email" value="<?php echo htmlspecialchars($perfil['email']); ?>">
                </div>

                <div class="form-group">
                    <label>Telefone</label>
                    <input type="tel" class="form-input" id="phone" value="<?php echo htmlspecialchars($perfil['telefone'] ?? ''); ?>" placeholder="(00) 00000-0000" maxlength="15">
                </div>
            </div>

            <div class="profile-card">
                <h2 class="card-title">Informações Pessoais</h2>
                
                <div class="form-group">
                    <label>Empresa</label>
                    <input type="text" class="form-input" id="company" value="<?php echo htmlspecialchars($perfil['empresa'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Cargo</label>
                    <input type="text" class="form-input" id="position" value="<?php echo htmlspecialchars($perfil['cargo'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Departamento</label>
                    <input type="text" class="form-input" id="department" value="<?php echo htmlspecialchars($perfil['departamento'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Localização</label>
                    <input type="text" class="form-input" id="location" value="<?php echo htmlspecialchars($perfil['localizacao'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label>Biografia</label>
                    <textarea class="form-input" id="bio" rows="4"><?php echo htmlspecialchars($perfil['bio'] ?? ''); ?></textarea>
                </div>

                <div class="form-actions">
                    <button class="btn btn-secondary" onclick="cancelChanges()">Cancelar</button>
                    <button class="btn btn-primary" onclick="saveChanges()">Salvar Alterações</button>
                </div>
            </div>
        </div>
    </main>

    <button class="personalize-btn">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
            <path d="M17.414 2.586a2 2 0 0 0-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 0 0 0-2.828z"/>
            <path fill-rule="evenodd" d="M2 6a2 2 0 0 1 2-2h4a1 1 0 0 1 0 2H4v10h10v-4a1 1 0 1 1 2 0v4a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6z"/>
        </svg>
        <span>PERSONALIZAR</span>
    </button>

    <!-- Modal de confirmação de troca de email -->
    <div id="emailModal" class="modal" style="display: none;">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3>⚠️ Confirmar alteração de e-mail</h3>
            </div>
            <div class="modal-body">
                <p>Você está prestes a alterar seu e-mail de acesso.</p>
                <p><strong>Email atual:</strong> <span id="emailAtual"></span></p>
                <p><strong>Novo email:</strong> <span id="emailNovo"></span></p>
                <p class="modal-warning">Esta ação pode afetar seu login. Tem certeza?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="cancelEmailChange()">Cancelar</button>
                <button class="btn btn-primary" onclick="confirmEmailChange()">Confirmar</button>
            </div>
        </div>
    </div>

    <script src="perfil.js"></script>
</body>
</html>