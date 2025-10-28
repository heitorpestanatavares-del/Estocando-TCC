<?php
session_start();

// Destroi todas as variáveis de sessão
$_SESSION = array();

// Se houver cookie de sessão, apaga
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destrói a sessão
session_destroy();

// Redireciona para a página de login
header("Location: loginPag.php?logout=sucesso");
exit;
?>