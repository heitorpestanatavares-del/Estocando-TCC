// perfil.js - Script para funcionalidades da página de perfil

// Animação do banner estilo Aurora Boreal
function initBanner() {
    const canvas = document.getElementById('bannerCanvas');
    const ctx = canvas.getContext('2d');
    
    // Ajustar tamanho do canvas
    function resizeCanvas() {
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;
    }
    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);
    
    // Partículas para simular estrelas
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
    
    // Ondas de aurora
    let time = 0;
    
    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        // Gradiente de fundo
        const gradient = ctx.createLinearGradient(0, 0, canvas.width, canvas.height);
        gradient.addColorStop(0, '#1a4d4d');
        gradient.addColorStop(0.5, '#2d6a5f');
        gradient.addColorStop(1, '#3d4d3d');
        ctx.fillStyle = gradient;
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        
        // Desenhar ondas de aurora
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
        
        // Desenhar estrelas/partículas
        particles.forEach(particle => {
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.radius, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})`;
            ctx.fill();
            
            // Movimento das partículas
            particle.x += particle.vx;
            particle.y += particle.vy;
            
            // Reposicionar partículas que saem da tela
            if (particle.x < 0) particle.x = canvas.width;
            if (particle.x > canvas.width) particle.x = 0;
            if (particle.y < 0) particle.y = canvas.height;
            if (particle.y > canvas.height) particle.y = 0;
            
            // Pulsação da opacidade
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
        if (file) {
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
                
                showNotification('Foto atualizada com sucesso!', 'success');
            };
            reader.readAsDataURL(file);
        }
    });
}

// Salvar alterações
function saveChanges() {
    const data = {
        displayName: document.getElementById('displayName').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        company: document.getElementById('company').value,
        position: document.getElementById('position').value,
        department: document.getElementById('department').value,
        location: document.getElementById('location').value,
        bio: document.getElementById('bio').value
    };
    
    // Validação básica
    if (!data.displayName.trim()) {
        showNotification('Por favor, preencha o nome de exibição', 'error');
        return;
    }
    
    if (!data.email.trim() || !isValidEmail(data.email)) {
        showNotification('Por favor, insira um e-mail válido', 'error');
        return;
    }
    
    // Simular salvamento
    const btn = event.target;
    btn.disabled = true;
    btn.innerHTML = '<svg class="spinner" width="16" height="16" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none" opacity="0.25"/><path d="M12 2a10 10 0 0 1 10 10" stroke="currentColor" stroke-width="4" fill="none" stroke-linecap="round"/></svg> Salvando...';
    
    setTimeout(() => {
        btn.disabled = false;
        btn.innerHTML = 'Salvar Alterações';
        showNotification('Alterações salvas com sucesso!', 'success');
        
        // Atualizar nome no perfil
        document.querySelector('.profile-name').firstChild.textContent = data.displayName + ' ';
        document.querySelector('.profile-bio').textContent = data.bio;
    }, 1500);
}

// Cancelar alterações
function cancelChanges() {
    if (confirm('Deseja realmente cancelar as alterações?')) {
        // Restaurar valores originais (em um cenário real, você carregaria do servidor)
        document.getElementById('displayName').value = 'Maia';
        document.getElementById('email').value = 'maiahelena@gmail.com';
        document.getElementById('phone').value = '1234567';
        document.getElementById('company').value = 'empresa do malvado doofenshmirtz SA.';
        document.getElementById('position').value = 'Gerente';
        document.getElementById('department').value = 'rh';
        document.getElementById('location').value = 'Rua das Nações n°2562';
        document.getElementById('bio').value = 'eu te odeio perry o ornitorrinco!!!';
        
        showNotification('Alterações canceladas', 'info');
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
    
    // Adicionar estilos inline
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