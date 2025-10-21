<?php
// perfil.php - Página de perfil do usuário
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
    
    <div class="main-wrapper">
        <header class="top-header">
            <div class="search-bar">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                    <path d="M9 17A8 8 0 1 0 9 1a8 8 0 0 0 0 16zM19 19l-4.35-4.35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </svg>
                <input type="text" placeholder="Procurar">
            </div>
            
            <div class="header-actions">
                <button class="icon-btn">
                    <img src="https://flagcdn.com/w40/gb.png" alt="EN" class="flag-icon">
                </button>
                <button class="icon-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a6 6 0 0 1 6 6v3.586l.707.707A1 1 0 0 1 16 14H4a1 1 0 0 1-.707-1.707L4 11.586V8a6 6 0 0 1 6-6zM10 18a3 3 0 0 1-3-3h6a3 3 0 0 1-3 3z"/>
                    </svg>
                </button>
                <button class="icon-btn notification-btn">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 2a6 6 0 0 1 6 6v3.586l.707.707A1 1 0 0 1 16 14H4a1 1 0 0 1-.707-1.707L4 11.586V8a6 6 0 0 1 6-6z"/>
                    </svg>
                    <span class="notification-badge"></span>
                </button>
                <div class="user-menu">
                    <img src="https://via.placeholder.com/40" alt="User" class="user-avatar">
                </div>
            </div>
        </header>

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
                            <img id="profileImage" src="https://via.placeholder.com/150" alt="Profile">
                        </div>
                        
                        <div class="profile-details">
                            <h1 class="profile-name">
                                Maia
                            </h1>
                            <p class="profile-bio">
                                    eu te odeio perry o ornitorrinco!!!</p>
                            <div class="profile-links">
                                <a href="https://maianobara.com" class="profile-link">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0zM5.78 8.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5zm3.5 0a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5z"/>
                                    </svg>
                                    maianobara.com
                                </a>
                                <a href="#" class="profile-link">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"/>
                                    </svg>
                                    MaiaNobara123
                                </a>
                                <a href="mailto:maia.nobara@gmail.com" class="profile-link">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor">
                                        <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2zm13 2.383l-4.708 2.825L15 11.105V5.383zm-.034 6.878L9.271 8.936 8 9.583l-1.271-.647-5.695 3.324A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.739zM1 11.105l4.708-2.897L1 5.383v5.722z"/>
                                    </svg>
                                    maia.nobara@gmail.com
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
                        <img id="previewImage" src="https://via.placeholder.com/150" alt="Preview" class="photo-preview">
                        <button class="btn btn-primary" onclick="document.getElementById('photoInput').click()">
                            Escolher Foto
                        </button>
                        <input type="file" id="photoInput" accept="image/*" style="display: none;">
                    </div>

                    <div class="form-group">
                        <label>Nome de Exibição</label>
                        <input type="text" class="form-input" id="displayName" value="Maia">
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" class="form-input" id="email" value="maiahelena@gmail.com">
                    </div>

                    <div class="form-group">
                        <label>Github</label>
                        <input type="text" class="form-input" id="github" value="maiahelena@gmail.com">
                    </div>

                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="tel" class="form-input" id="phone" value="1234567">
                    </div>
                </div>

                <div class="profile-card">
                    <h2 class="card-title">Informações Pessoais</h2>
                    
                    <div class="form-group">
                        <label>Empresa</label>
                        <input type="text" class="form-input" id="company" value="empresa do malvado doofenshmirtz SA.">
                    </div>

                    <div class="form-group">
                        <label>Cargo</label>
                        <input type="text" class="form-input" id="position" value="Gerente">
                    </div>

                    <div class="form-group">
                        <label>Departamento</label>
                        <input type="text" class="form-input" id="department" value="rh">
                    </div>

                    <div class="form-group">
                        <label>Localização</label>
                        <input type="text" class="form-input" id="location" value="Rua das Nações n°2562">
                    </div>

                    <div class="form-group">
                        <label>Biografia</label>
                        <textarea class="form-input" id="bio" rows="4">eu te odeio perry o ornitorrinco!!!</textarea>
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
    </div>

    <script src="perfil.js"></script>
</body>
</html>