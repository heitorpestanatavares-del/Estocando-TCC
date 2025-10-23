<?php
// carregar_perfil.php - Busca dados do perfil do banco
session_start();
require_once 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Buscar dados do perfil
$sql = "SELECT 
            c.nome as nome_cadastro,
            c.email as email_cadastro,
            p.*
        FROM cadastrar c
        LEFT JOIN perfil p ON c.id = p.id_usuario
        WHERE c.id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    $dados = $resultado->fetch_assoc();
    
    // Se não existe perfil, criar um novo
    if ($dados['id'] === null) {
        $sql_insert = "INSERT INTO perfil (id_usuario, nome_exibicao, email) VALUES (?, ?, ?)";
        $stmt_insert = $conexao->prepare($sql_insert);
        $stmt_insert->bind_param("iss", $id_usuario, $dados['nome_cadastro'], $dados['email_cadastro']);
        $stmt_insert->execute();
        
        // Buscar novamente
        $stmt->execute();
        $resultado = $stmt->get_result();
        $dados = $resultado->fetch_assoc();
    }
    
    // Usar valores de cadastrar se o perfil estiver vazio
    if (empty($dados['nome_exibicao'])) {
        $dados['nome_exibicao'] = $dados['nome_cadastro'];
    }
    if (empty($dados['email'])) {
        $dados['email'] = $dados['email_cadastro'];
    }
    
    // Ajustar caminhos das imagens
    if (!empty($dados['foto_perfil']) && file_exists($dados['foto_perfil'])) {
        $dados['foto_perfil_url'] = $dados['foto_perfil'];
    } else {
        $dados['foto_perfil_url'] = 'https://via.placeholder.com/150';
    }
    
    if (!empty($dados['banner_perfil']) && file_exists($dados['banner_perfil'])) {
        $dados['banner_perfil_url'] = $dados['banner_perfil'];
    } else {
        $dados['banner_perfil_url'] = null;
    }
    
    echo json_encode([
        'sucesso' => true,
        'dados' => $dados
    ]);
} else {
    echo json_encode(['erro' => 'Usuário não encontrado']);
}

$stmt->close();
$conexao->close();
?>