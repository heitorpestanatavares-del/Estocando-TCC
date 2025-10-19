<?php
session_start();
include_once('conexao.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['firstName']);
    $cnpj = preg_replace('/\D/', '', $_POST['cnpj']); // Remove formatação
    $email = trim($_POST['email']);
    $senha = $_POST['password'];
    
    // Validações
    $erros = [];
    
    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    }
    
    if (strlen($cnpj) !== 14) {
        $erros[] = "CNPJ inválido. Deve conter 14 dígitos.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "E-mail inválido.";
    }
    
    if (strlen($senha) < 6) {
        $erros[] = "A senha deve ter no mínimo 6 caracteres.";
    }
    
    // Verificar se CNPJ já existe
    if (empty($erros)) {
        $sql_check = "SELECT id FROM cadastrar WHERE cnpj = ?";
        $stmt = $conexao->prepare($sql_check);
        $stmt->bind_param("s", $cnpj);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $erros[] = "Este CNPJ já está cadastrado no sistema.";
        }
        $stmt->close();
    }
    
    // Verificar se e-mail já existe
    if (empty($erros)) {
        $sql_check = "SELECT id FROM cadastrar WHERE email = ?";
        $stmt = $conexao->prepare($sql_check);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $erros[] = "Este e-mail já está cadastrado no sistema.";
        }
        $stmt->close();
    }
    
    // Se houver erros, redireciona de volta
    if (!empty($erros)) {
        $_SESSION['erro_cadastro'] = implode("<br>", $erros);
        header("Location: signup.php");
        exit;
    }
    
    // Criptografar senha
    $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
    
    // Inserir no banco
    $sql = "INSERT INTO cadastrar (nome, email, cnpj, senha) VALUES (?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $cnpj, $senha_hash);
    
    if ($stmt->execute()) {
        $_SESSION['sucesso_cadastro'] = "Cadastro realizado com sucesso! Faça login para continuar.";
        header("Location: signin.php");
        exit;
    } else {
        $_SESSION['erro_cadastro'] = "Erro ao cadastrar. Tente novamente.";
        header("Location: signup.php");
        exit;
    }
    
    $stmt->close();
    $conexao->close();
} else {
    header("Location: signup.php");
    exit;
}
?>