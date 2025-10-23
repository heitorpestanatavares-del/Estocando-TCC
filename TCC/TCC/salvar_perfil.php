<?php
// salvar_perfil.php - Salva os dados do perfil no banco
session_start();
require_once 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Receber dados JSON
$dados = json_decode(file_get_contents('php://input'), true);

// Validações
$erros = [];

if (empty($dados['nome_exibicao'])) {
    $erros[] = 'Nome de exibição é obrigatório';
}

if (empty($dados['email']) || !filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
    $erros[] = 'E-mail válido é obrigatório';
}

if (!empty($erros)) {
    echo json_encode(['erro' => implode(', ', $erros)]);
    exit;
}

// Verificar se já existe perfil
$sql_check = "SELECT id FROM perfil WHERE id_usuario = ?";
$stmt_check = $conexao->prepare($sql_check);
$stmt_check->bind_param("i", $id_usuario);
$stmt_check->execute();
$resultado = $stmt_check->get_result();

if ($resultado->num_rows > 0) {
    // Atualizar perfil existente
    $sql = "UPDATE perfil SET 
                nome_exibicao = ?,
                email = ?,
                telefone = ?,
                empresa = ?,
                cargo = ?,
                departamento = ?,
                localizacao = ?,
                bio = ?
            WHERE id_usuario = ?";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(
        "ssssssssi",
        $dados['nome_exibicao'],
        $dados['email'],
        $dados['telefone'],
        $dados['empresa'],
        $dados['cargo'],
        $dados['departamento'],
        $dados['localizacao'],
        $dados['bio'],
        $id_usuario
    );
} else {
    // Inserir novo perfil
    $sql = "INSERT INTO perfil 
                (id_usuario, nome_exibicao, email, telefone, empresa, cargo, departamento, localizacao, bio) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param(
        "issssssss",
        $id_usuario,
        $dados['nome_exibicao'],
        $dados['email'],
        $dados['telefone'],
        $dados['empresa'],
        $dados['cargo'],
        $dados['departamento'],
        $dados['localizacao'],
        $dados['bio']
    );
}

if ($stmt->execute()) {
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Perfil atualizado com sucesso!'
    ]);
} else {
    echo json_encode([
        'erro' => 'Erro ao salvar perfil: ' . $stmt->error
    ]);
}

$stmt->close();
$stmt_check->close();
$conexao->close();
?>