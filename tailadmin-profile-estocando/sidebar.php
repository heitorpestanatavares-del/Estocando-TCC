<?php
// sidebar.php - Componente de navegação lateral
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="sidebar.css">
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M3 3L10 3L10 10L3 10L3 3Z" fill="#00D9B5"/>
                    <path d="M14 3L21 3L21 10L14 10L14 3Z" fill="#00D9B5" opacity="0.6"/>
                    <path d="M3 14L10 14L10 21L3 21L3 14Z" fill="#00D9B5" opacity="0.3"/>
                </svg>
                <span>aurora</span>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">PÁGINA INICIAL</div>
                <a href="#" class="nav-item">
                    <i class="icon">🛒</i>
                    <span>Comércio eletrônico</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="icon">📁</i>
                    <span>Projeto</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="icon">👥</i>
                    <span>CRM</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="icon">📊</i>
                    <span>Análise</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="icon">👔</i>
                    <span>Gestão de RH</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="icon">⏱️</i>
                    <span>Rastreador de tempo</span>
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">APLICATIVOS</div>
                <a href="perfil.php" class="nav-item active">
                    <i class="icon">👤</i>
                    <span>Social</span>
                </a>
                <a href="#" class="nav-item">
                    <i class="icon">📅</i>
                    <span>Calendário</span>
                </a>
            </div>
        </nav>

        <div class="sidebar-footer">
            <div class="user-info">
                <img src="https://via.placeholder.com/32" alt="User" class="user-avatar">
                <div class="user-details">
                    <div class="user-name">Maia</div>
                    <div class="user-email">maiahelena@...</div>
                </div>
            </div>
        </div>
    </aside>
</body>
</html>