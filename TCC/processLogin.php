<?php
session_start();
include_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cnpj = preg_replace('/\D/', '', $_POST['cnpj']); // Remove formatação
    $senha = $_POST['password'];
    $keep_logged = isset($_POST['keep_logged']);
    
    // Validações básicas
    if (strlen($cnpj) !== 14) {
        $_SESSION['erro_login'] = "CNPJ inválido. Deve conter 14 dígitos.";
        header("Location: signin.php");
        exit;
    }
    
    if (empty($senha)) {
        $_SESSION['erro_login'] = "Por favor, insira sua senha.";
        header("Location: signin.php");
        exit;
    }
    
    // Buscar usuário no banco
    $sql = "SELECT id, nome, email, senha FROM cadastrar WHERE cnpj = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $cnpj);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        
        // Verificar senha
        if (password_verify($senha, $usuario['senha'])) {
            // Login bem-sucedido
            $_SESSION['id_usuario'] = $usuario['id'];
            $_SESSION['nome_usuario'] = $usuario['nome'];
            $_SESSION['email_usuario'] = $usuario['email'];
            
            // Se marcou "Lembre-se de mim", definir cookie
            if ($keep_logged) {
                setcookie('user_id', $usuario['id'], time() + (86400 * 30), "/"); // 30 dias
            }
            
            // Redirecionar para o perfil
            header("Location: perfilPag.php");
            exit;
        } else {
            // Senha incorreta
            $_SESSION['erro_login'] = "Essas credenciais não correspondem a nenhum registro.";
            header("Location: signin.php");
            exit;
        }
    } else {
        // CNPJ não encontrado
        $_SESSION['erro_login'] = "Essas credenciais não correspondem a nenhum registro.";
        header("Location: signin.php");
        exit;
    }
    
    $stmt->close();
    $conexao->close();
} else {
    header("Location: signin.php");
    exit;
}
?>