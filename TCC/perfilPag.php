<?php
// Inicia a sessão
session_start();

// Verifica login
if (!isset($_SESSION['id_usuario'])) {
    header("Location: loginPag.php?erro=naologado");
    exit;
}

// Conexão com o banco
include_once('conexao.php');

$id_usuario_logado = $_SESSION['id_usuario'];

// Busca dados do usuário
$sql = "SELECT c.nome, c.email, p.telefone, p.empresa, p.cargo, p.departamento, p.localizacao, p.bio, p.foto_perfil
        FROM cadastrar c
        LEFT JOIN perfil p ON c.id = p.id_usuario
        WHERE c.id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param('i', $id_usuario_logado);
$stmt->execute();
$resultado = $stmt->get_result();
$dados_usuario = $resultado->fetch_assoc();

// Variáveis
$nome = $dados_usuario['nome'] ?? 'Usuário';
$email = $dados_usuario['email'] ?? 'E-mail não informado';
$telefone = $dados_usuario['telefone'] ?? '';
$empresa = $dados_usuario['empresa'] ?? '';
$cargo = $dados_usuario['cargo'] ?? '';
$departamento = $dados_usuario['departamento'] ?? '';
$localizacao = $dados_usuario['localizacao'] ?? '';
$bio = $dados_usuario['bio'] ?? '';
$foto_perfil = $dados_usuario['foto_perfil'] ?? null;
$iniciais = strtoupper(substr($nome, 0, 2));

$stmt->close();
$conexao->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Estocando</title>
    <link rel="stylesheet" href="css/sidebar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/perfil.css">
</head>

<body>
    <div class="container">
        <?php include 'sidebar/sidebar.php'; ?>

        <div class="main-content">
            <div class="header">
                <div class="header-left">
                    <h1>Perfil</h1>
                    <div class="breadcrumb">Início / Perfil</div>
                </div>
                <div class="header-right">
                    <button id="themeToggle" class="theme-toggle" title="Alternar tema">
                        <svg class="theme-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                clip-rule="evenodd" />
                        </svg>
                    
                </div>
            </div>

            <!-- FORM ÚNICO -->
            <form action="salvarPerfil.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_usuario" value="<?= $id_usuario_logado ?>">

                <div class="profile-content">
                    <div class="profile-card">
                        <h2 class="card-title">Foto do Perfil</h2>
                        <div class="profile-picture-section">
                            <?php if ($foto_perfil): ?>
                                <img id="profilePicture" src="<?= htmlspecialchars($foto_perfil) ?>" alt="Foto de Perfil"
                                    class="profile-picture" style="object-fit:cover;">
                            <?php else: ?>
                                <div id="profilePicture" class="profile-picture"><?= $iniciais ?></div>
                            <?php endif; ?>

                            <input type="file" id="photoInput" name="foto_perfil" class="file-input" accept="image/*">
                            <button class="upload-btn" type="button"
                                onclick="document.getElementById('photoInput').click()">Escolher Foto</button>
                        </div>

                        <!-- Modal escondido -->
                        <div id="photoModal" class="photo-modal" role="dialog" aria-hidden="true">
                            <span class="close" aria-label="Fechar">&times;</span>
                            <img class="modal-content" id="modalImage" alt="Foto ampliada">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nome de Exibição</label>
                            <input type="text" name="nome" class="form-input" value="<?= htmlspecialchars($nome) ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-input" value="<?= htmlspecialchars($email) ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Telefone</label>
                            <input type="tel" name="telefone" class="form-input" value="<?= htmlspecialchars($telefone) ?>">
                        </div>
                    </div>

                    <div class="profile-card">
                        <h2 class="card-title">Informações Pessoais</h2>

                        <div class="form-group">
                            <label class="form-label">Empresa</label>
                            <input type="text" name="empresa" class="form-input" value="<?= htmlspecialchars($empresa) ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Cargo</label>
                            <input type="text" name="cargo" class="form-input" value="<?= htmlspecialchars($cargo) ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Departamento</label>
                            <input type="text" name="departamento" class="form-input"
                                value="<?= htmlspecialchars($departamento) ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Localização</label>
                            <input type="text" name="localizacao" class="form-input"
                                value="<?= htmlspecialchars($localizacao) ?>">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Biografia</label>
                            <textarea name="bio" class="form-textarea"><?= htmlspecialchars($bio) ?></textarea>
                        </div>

                        <div class="form-actions">
                            <button class="btn btn-outline" type="reset">Cancelar</button>
                            <button class="btn btn-primary" type="submit">Salvar Alterações</button>
                            
                        </div>
                    </div>
                </div>
                
            </form>
        </div>
    </div>

    <script>
        // Preview da foto
        (function () {
            const photoInput = document.getElementById("photoInput");
            const modal = document.getElementById("photoModal");
            const modalImg = document.getElementById("modalImage");
            const modalClose = document.querySelector(".photo-modal .close");

            if (photoInput) {
                photoInput.addEventListener("change", function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (ev) {
                        const dataURL = ev.target.result;
                        const current = document.getElementById("profilePicture");
                        if (current && current.tagName.toLowerCase() === "img") {
                            current.src = dataURL;
                        } else if (current) {
                            const img = document.createElement("img");
                            img.id = "profilePicture";
                            img.className = "profile-picture";
                            img.src = dataURL;
                            img.alt = "Foto de Perfil";
                            img.style.objectFit = "cover";
                            current.replaceWith(img);
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
            

            document.addEventListener("click", function (e) {
                if (e.target && e.target.id === "profilePicture" && e.target.tagName.toLowerCase() === "img") {
                    modalImg.src = e.target.src;
                    modal.style.display = "flex";
                }
            });

            if (modalClose) {
                modalClose.addEventListener("click", function () {
                    modal.style.display = "none";
                });
            }
            if (modal) {
                modal.addEventListener("click", function (ev) {
                    if (ev.target === modal) modal.style.display = "none";
                });
            }
            document.addEventListener("keydown", function (ev) {
                if (ev.key === "Escape") modal.style.display = "none";
            });
        })();

        // Dark Mode
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;
            const savedTheme = localStorage.getItem("theme");

            if (savedTheme === "dark") {
                body.classList.add("dark-theme");
                updateThemeIcon(true);
            }

            themeToggle.addEventListener('click', function () {
                body.classList.toggle('dark-theme');
                const isDark = body.classList.contains('dark-theme');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                updateThemeIcon(isDark);
            });

            function updateThemeIcon(isDark) {
                const themeIcon = themeToggle.querySelector('.theme-icon');
                if (isDark) {
                    themeIcon.innerHTML = '<path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>';
                    themeToggle.title = 'Alternar para tema claro';
                } else {
                    themeIcon.innerHTML = '<path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>';
                    themeToggle.title = 'Alternar para tema escuro';
                }
            }
        });
    </script>
</body>

</html>