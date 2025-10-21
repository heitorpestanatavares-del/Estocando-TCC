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