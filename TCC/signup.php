<?php
session_start();

// Capturar mensagens de erro
$erro = isset($_SESSION['erro_cadastro']) ? $_SESSION['erro_cadastro'] : '';

// Limpar mensagens da sessão
unset($_SESSION['erro_cadastro']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estocando - Cadastrar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body class="bg-[#1a1f2e]">
    <div class="min-h-screen flex">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <a href="signin.php" class="flex items-center gap-2 text-gray-400 hover:text-gray-300 mb-6 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <span>Voltar para o Login</span>
                </a>

                <!-- Mensagem de Erro -->
                <?php if (!empty($erro)): ?>
                <div class="mb-6 bg-red-900/20 border border-red-500 rounded-lg p-4 flex items-start gap-3 animate-fadeIn">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-400 text-sm flex-1"><?php echo $erro; ?></p>
                </div>
                <?php endif; ?>

                <h1 class="text-white text-4xl font-bold mb-2">Cadastrar-se</h1>
                <p class="text-gray-400 mb-8">Se cadastre com seu CNPJ e sua Senha!</p>

                <!-- Social Sign Up Buttons -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <button class="flex items-center justify-center gap-2 bg-[#252c3d] text-white py-3 px-4 rounded-lg hover:bg-[#2d3548] transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Entre com o Google
                    </button>
                    <button class="flex items-center justify-center gap-2 bg-[#252c3d] text-white py-3 px-4 rounded-lg hover:bg-[#2d3548] transition-colors">
                        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                        </svg>
                        Entre com o X
                    </button>
                </div>

                <div class="flex items-center gap-4 mb-6">
                    <div class="flex-1 h-px bg-gray-700"></div>
                    <span class="text-gray-400 text-sm">ou</span>
                    <div class="flex-1 h-px bg-gray-700"></div>
                </div>

                <!-- Sign Up Form -->
                <form id="signupForm" method="POST" action="processCadastro.php">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="firstName" class="block text-gray-300 mb-2">
                                Nome completo<span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="firstName"
                                name="firstName"
                                placeholder="Digite o seu Nome"
                                class="w-full bg-[#252c3d] text-white border border-gray-700 rounded-lg py-3 px-4 focus:outline-none focus:border-blue-500 transition-colors"
                                required
                            />
                        </div>
                        <div>
                            <label for="cnpj" class="block text-gray-300 mb-2">
                                CNPJ<span class="text-red-500">*</span>
                            </label>
                            <input
                                type="text"
                                id="cnpjSignup"
                                name="cnpj"
                                placeholder="00.000.000/0000-00"
                                maxlength="18"
                                class="w-full bg-[#252c3d] text-white border border-gray-700 rounded-lg py-3 px-4 focus:outline-none focus:border-blue-500 transition-colors"
                                required
                            />
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-300 mb-2">
                            Email<span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            placeholder="Digite o seu email"
                            class="w-full bg-[#252c3d] text-white border border-gray-700 rounded-lg py-3 px-4 focus:outline-none focus:border-blue-500 transition-colors"
                            required
                        />
                    </div>

                    <div class="mb-4">
                        <label for="passwordSignup" class="block text-gray-300 mb-2">
                            Senha<span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                id="passwordSignup"
                                name="password"
                                placeholder="Crie sua Senha"
                                class="w-full bg-[#252c3d] text-white border border-gray-700 rounded-lg py-3 px-4 pr-12 focus:outline-none focus:border-blue-500 transition-colors"
                                required
                            />
                            <button
                                type="button"
                                onclick="togglePassword('passwordSignup', 'eyeIconSignup')"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-300"
                            >
                                <svg id="eyeIconSignup" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg transition-colors"
                    >
                        Cadastrar-se
                    </button>
                </form>

                <p class="text-gray-400 text-center mt-6">
                    Já tem uma conta?
                    <a href="signin.php" class="text-blue-500 hover:text-blue-400">
                        Entre
                    </a>
                </p>
            </div>
        </div>

        <!-- Right Side - Branding -->
        <div class="hidden lg:flex lg:w-1/2 bg-[#1a1f2e] items-center justify-center relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-[#1a1f2e] via-[#1a1f2e] to-[#252c3d]"></div>
            <div class="absolute inset-0 grid-pattern"></div>
            <div class="relative z-10 text-center px-8">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="7" height="7" />
                            <rect x="14" y="3" width="7" height="7" />
                            <rect x="14" y="14" width="7" height="7" />
                            <rect x="3" y="14" width="7" height="7" />
                        </svg>
                    </div>
                    <h2 class="text-white text-3xl font-bold">Estocando</h2>
                </div>
                <p class="text-gray-400 text-lg">
                   O MELHOR site de estoque de baixa renda que você já viu!!!
                    <br>
                    Bom pelo menos a gente tenta...
                </p>
            </div>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>