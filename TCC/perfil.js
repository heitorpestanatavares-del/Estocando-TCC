// perfil.js - Script para funcionalidades da página de perfil

// Animação do banner estilo Aurora Boreal
function initBanner() {
    const canvas = document.getElementById('bannerCanvas');
    const ctx = canvas.getContext('2d');
    
    function resizeCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    const particles = [];
    const particleCount = 100;
    
    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            radius: Math.random() * 2,
            vx: (Math.random() - 0.5) * 0.5,
            vy: (Math.random() - 0.5) * 0.5,
            opacity: Math.random()
        });
    }
    
    let time = 0;
    
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        gradient.addColorStop(0, '#1a4d4d');
        gradient.addColorStop(0.5, '#2d6a5f');
        gradient.addColorStop(1, '#3d4d3d');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        for (let wave = 0; wave < 3; wave++) {
            ctx.beginPath();
            ctx.moveTo(0, canvas.height);
            
            for (let x = 0; x <= canvas.width; x += 5) {
                const y = canvas.height * 0.3 + 
                         Math.sin(x * 0.01 + time + wave) * 30 +
                         Math.sin(x * 0.02 + time * 0.5 + wave * 2) * 20;
                ctx.lineTo(x, y);
            }
            
            ctx.lineTo(canvas.width, canvas.height);
            ctx.closePath();
            
            const auroraGradient = ctx.createLinearGradient(0, 0, 0, canvas.height);
            if (wave === 0) {
                auroraGradient.addColorStop(0, 'rgba(0, 217, 181, 0.3)');
                auroraGradient.addColorStop(1, 'rgba(0, 217, 181, 0)');
            } else if (wave === 1) {
                auroraGradient.addColorStop(0, 'rgba(52, 211, 153, 0.2)');
                auroraGradient.addColorStop(1, 'rgba(52, 211, 153, 0)');
            } else {
                auroraGradient.addColorStop(0, 'rgba(16, 185, 129, 0.15)');
                auroraGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
            }
            
            ctx.fillStyle = auroraGradient;
            ctx.fill();
        }
        
        particles.forEach(particle => {
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})`;
            ctx.fill();
            
            particle.x += particle.vx;
            particle.y += particle.vy;
            
            if (particle.x < 0) particle.x = canvas.width;
            if (particle.x > canvas.width) particle.x = 0;
            if (particle.y < 0) particle.y = canvas.height;
            if (particle.y > canvas.height) particle.y = 0;
            
            particle.opacity += (Math.random() - 0.5) * 0.02;
            particle.opacity = Math.max(0.2, Math.min(1, particle.opacity));
        });
        
        time += 0.02;
        requestAnimationFrame(animate);
    }
    
    animate();
}

// Upload de foto de perfil
function initPhotoUpload() {
    const photoInput = document.getElementById('photoInput');
    const previewImage = document.getElementById('previewImage');
    const profileImage = document.getElementById('profileImage');
    
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;
        
        // Validar tipo
        if (!file.type.match('image.*')) {
            showNotification('Por favor, selecione uma imagem válida', 'error');
            return;
        }
        
        // Validar tamanho (5MB)
        if (file.size > 5 * 1024 * 1024) {
            showNotification('Imagem muito grande. Máximo 5MB', 'error');
            return;
        }
        
        // Preview local
        const reader = new FileReader();
        reader.onload = function(event) {
            const imageUrl = event.target.result;
            previewImage.src = imageUrl;
            profileImage.src = imageUrl;
            
            // Animação de feedback
            previewImage.style.transform = 'scale(0.9)';
            setTimeout(() => {
                previewImage.style.transform = 'scale(1)';
            }, 200);
        };
        reader.readAsDataURL(file);
        
        // Upload para o servidor
        const formData = new FormData();
        formData.append('foto', file);
        
        fetch('upload_foto.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.sucesso) {
                showNotification('Foto atualizada com sucesso!', 'success');
                // Atualizar também na sidebar
                const sidebarAvatar = document.querySelector('.sidebar-footer .user-avatar');
                if (sidebarAvatar) {
                    sidebarAvatar.src = data.url;
                }
                const headerAvatar = document.querySelector('.user-menu .user-avatar');
                if (headerAvatar) {
                    headerAvatar.src = data.url;
                }
            } else {
                showNotification(data.erro || 'Erro ao fazer upload', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showNotification('Erro ao fazer upload da foto', 'error');
        });
    });
}

// Salvar alterações
function saveChanges() {
    const data = {
        nome_exibicao: document.getElementById('displayName').value,
        email: document.getElementById('email').value,
        telefone: document.getElementById('phone').value,
        empresa: document.getElementById('company').value,
        cargo: document.getElementById('position').value,
        departamento: document.getElementById('department').value,
        localizacao: document.getElementById('location').value,
        bio: document.getElementById('bio').value
    };
    
    // Validação básica
    if (!data.nome_exibicao.trim()) {
        showNotification('Por favor, preencha o nome de exibição', 'error');
        return;
    }
    
    if (!data.email.trim() || !isValidEmail(data.email)) {
        showNotification('Por favor, insira um e-mail válido', 'error');
        return;
    }
    
    // Animação do botão
    const btn = event.target;
    const textoOriginal = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<svg class="spinner" width="16" height="16" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round"/></svg> Salvando...';
    
    // Enviar para o servidor
    fetch('salvar_perfilPag.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        btn.disabled = false;
        btn.innerHTML = textoOriginal;
        
        if (result.sucesso) {
            showNotification('Alterações salvas com sucesso!', 'success');
            
            // Atualizar nome no perfil
            document.querySelector('.profile-name').textContent = data.nome_exibicao;
            document.querySelector('.profile-bio').textContent = data.bio;
            
            // Atualizar na sidebar
            const sidebarName = document.querySelector('.sidebar-footer .user-name');
            if (sidebarName) {
                sidebarName.textContent = data.nome_exibicao;
            }
            const sidebarEmail = document.querySelector('.sidebar-footer .user-email');
            if (sidebarEmail) {
                const emailCurto = data.email.length > 20 ? data.email.substring(0, 17) + '...' : data.email;
                sidebarEmail.textContent = emailCurto;
            }
            
            // Atualizar links de contato
            const telefoneLink = document.querySelector('.profile-links a[href^="tel"]');
            if (telefoneLink && data.telefone) {
                telefoneLink.href = 'tel:' + data.telefone;
                telefoneLink.querySelector('~ span, + span')?.remove();
                telefoneLink.innerHTML = telefoneLink.innerHTML.split('</svg>')[0] + '</svg>' + data.telefone;
            }
            
            const emailLink = document.querySelector('.profile-links a[href^="mailto"]');
            if (emailLink) {
                emailLink.href = 'mailto:' + data.email;
                const svgIcon = emailLink.querySelector('svg');
                emailLink.innerHTML = '';
                emailLink.appendChild(svgIcon);
                emailLink.appendChild(document.createTextNode(data.email));
            }
        } else {
            showNotification(result.erro || 'Erro ao salvar alterações', 'error');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = textoOriginal;
        console.error('Erro:', error);
        showNotification('Erro ao salvar alterações', 'error');
    });
}

// Cancelar alterações
function cancelChanges() {
    if (confirm('Deseja realmente cancelar as alterações?')) {
        location.reload(); // Recarregar página para restaurar valores
    }
}

// Sistema de notificações
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    
    const icon = {
        success: '✓',
        error: '✕',
        info: 'ℹ',
        warning: '⚠'
    }[type] || 'ℹ';
    
    notification.innerHTML = `
        <span class="notification-icon">${icon}</span>
        <span class="notification-message">${message}</span>
    `;
    
    Object.assign(notification.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        padding: '16px 20px',
        borderRadius: '8px',
        display: 'flex',
        alignItems: 'center',
        gap: '12px',
        fontSize: '14px',
        fontWeight: '500',
        zIndex: '1000',
        animation: 'slideIn 0.3s ease',
        boxShadow: '0 4px 12px rgba(0, 0, 0, 0.3)',
        minWidth: '250px'
    });
    
    const colors = {
        success: { bg: '#10b981', color: '#fff' },
        error: { bg: '#ef4444', color: '#fff' },
        info: { bg: '#3b82f6', color: '#fff' },
        warning: { bg: '#f59e0b', color: '#fff' }
    };
    
    notification.style.background = colors[type].bg;
    notification.style.color = colors[type].color;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Validação de e-mail
function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

// Adicionar animações CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
    
    .spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .form-input {
        transition: all 0.3s ease;
    }
    
    .photo-preview {
        transition: transform 0.3s ease;
    }
`;
document.head.appendChild(style);

// Inicializar quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    initBanner();
    initPhotoUpload();
    
    // Adicionar transições suaves aos inputs
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('focus', function() {
            this.style.transform = 'scale(1.01)';
        });
        
        input.addEventListener('blur', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Animação de entrada dos cards
    const cards = document.querySelectorAll('.profile-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.5s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, 100 * index);
    });
});