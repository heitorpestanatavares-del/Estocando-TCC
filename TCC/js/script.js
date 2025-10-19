// Função para formatar CNPJ
function formatCNPJ(value) {
    // Remove tudo que não é dígito
    const numbers = value.replace(/\D/g, '');
    
    // Aplica a máscara: 00.000.000/0000-00
    if (numbers.length <= 2) {
        return numbers;
    } else if (numbers.length <= 5) {
        return `${numbers.slice(0, 2)}.${numbers.slice(2)}`;
    } else if (numbers.length <= 8) {
        return `${numbers.slice(0, 2)}.${numbers.slice(2, 5)}.${numbers.slice(5)}`;
    } else if (numbers.length <= 12) {
        return `${numbers.slice(0, 2)}.${numbers.slice(2, 5)}.${numbers.slice(5, 8)}/${numbers.slice(8)}`;
    } else {
        return `${numbers.slice(0, 2)}.${numbers.slice(2, 5)}.${numbers.slice(5, 8)}/${numbers.slice(8, 12)}-${numbers.slice(12, 14)}`;
    }
}

// Função para alternar visibilidade da senha
function togglePassword(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(iconId);
    
    if (!input || !icon) return;
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
        `;
    } else {
        input.type = 'password';
        icon.innerHTML = `
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        `;
    }
}

// Aplicar máscara de CNPJ e outras funcionalidades
document.addEventListener('DOMContentLoaded', function() {
    
    // ============================================
    // MÁSCARA DE CNPJ - SIGNIN
    // ============================================
    const cnpjInput = document.getElementById('cnpj');
    if (cnpjInput) {
        cnpjInput.addEventListener('input', function(e) {
            e.target.value = formatCNPJ(e.target.value);
        });
        
        // Aplicar máscara no valor inicial (se houver)
        if (cnpjInput.value) {
            cnpjInput.value = formatCNPJ(cnpjInput.value);
        }
    }
    
    // ============================================
    // MÁSCARA DE CNPJ - SIGNUP
    // ============================================
    const cnpjSignupInput = document.getElementById('cnpjSignup');
    if (cnpjSignupInput) {
        cnpjSignupInput.addEventListener('input', function(e) {
            e.target.value = formatCNPJ(e.target.value);
        });
        
        // Aplicar máscara no valor inicial (se houver - quando volta com erro)
        if (cnpjSignupInput.value) {
            cnpjSignupInput.value = formatCNPJ(cnpjSignupInput.value);
        }
    }
    
    // ============================================
    // AUTO-FECHAR MENSAGENS
    // ============================================
    const alerts = document.querySelectorAll('.animate-fadeIn');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000); // 5 segundos
    });
    
    // ============================================
    // VALIDAÇÃO VISUAL DOS CAMPOS
    // ============================================
    
    // Validar CNPJ ao sair do campo
    if (cnpjSignupInput) {
        cnpjSignupInput.addEventListener('blur', function() {
            const cnpj = this.value.replace(/\D/g, '');
            if (cnpj.length > 0 && cnpj.length !== 14) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-700');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-700');
            }
        });
    }
    
    // Validar e-mail ao sair do campo
    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (this.value && !emailRegex.test(this.value)) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-700');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-700');
            }
        });
    }
    
    // Validar senha ao digitar
    const passwordSignup = document.getElementById('passwordSignup');
    if (passwordSignup) {
        passwordSignup.addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 6) {
                this.classList.add('border-red-500');
                this.classList.remove('border-gray-700');
            } else {
                this.classList.remove('border-red-500');
                this.classList.add('border-gray-700');
            }
        });
    }
});

// Função auxiliar para validar formulário (usada no signup.php)
function validarFormulario() {
    const loadingIndicator = document.getElementById('loadingIndicator');
    const submitBtn = document.getElementById('submitBtn');
    
    // Mostra indicador de carregamento
    if (loadingIndicator) {
        loadingIndicator.classList.remove('hidden');
    }
    
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';
    }
    
    return true;
}