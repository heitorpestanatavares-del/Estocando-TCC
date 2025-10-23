<?php
// upload_foto.php - Faz upload da foto de perfil
session_start();
require_once 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit;
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar se foi enviado um arquivo
if (!isset($_FILES['foto']) || $_FILES['foto']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['erro' => 'Nenhum arquivo foi enviado']);
    exit;
}

$arquivo = $_FILES['foto'];

// Validar tipo de arquivo
$tipos_permitidos = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$tipo_arquivo = finfo_file($finfo, $arquivo['tmp_name']);
finfo_close($finfo);

if (!in_array($tipo_arquivo, $tipos_permitidos)) {
    echo json_encode(['erro' => 'Tipo de arquivo não permitido. Use JPG, PNG, GIF ou WEBP']);
    exit;
}

// Validar tamanho (máximo 5MB)
if ($arquivo['size'] > 5 * 1024 * 1024) {
    echo json_encode(['erro' => 'Arquivo muito grande. Máximo 5MB']);
    exit;
}

// Criar diretório se não existir
$dir_upload = 'uploads/';
if (!is_dir($dir_upload)) {
    mkdir($dir_upload, 0755, true);
}

// Gerar nome único para o arquivo
$extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
$nome_arquivo = uniqid() . '.' . $extensao;
$caminho_arquivo = $dir_upload . $nome_arquivo;

// Mover arquivo
if (!move_uploaded_file($arquivo['tmp_name'], $caminho_arquivo)) {
    echo json_encode(['erro' => 'Erro ao salvar arquivo']);
    exit;
}

// Buscar foto antiga para deletar
$sql_old = "SELECT foto_perfil FROM perfil WHERE id_usuario = ?";
$stmt_old = $conexao->prepare($sql_old);
$stmt_old->bind_param("i", $id_usuario);
$stmt_old->execute();
$resultado_old = $stmt_old->get_result();

if ($resultado_old->num_rows > 0) {
    $dados_old = $resultado_old->fetch_assoc();
    if (!empty($dados_old['foto_perfil']) && file_exists($dados_old['foto_perfil'])) {
        unlink($dados_old['foto_perfil']); // Deletar foto antiga
    }
}

// Atualizar banco de dados
$sql = "UPDATE perfil SET foto_perfil = ? WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("si", $caminho_arquivo, $id_usuario);

if ($stmt->execute()) {
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Foto atualizada com sucesso!',
        'url' => $caminho_arquivo
    ]);
} else {
    echo json_encode(['erro' => 'Erro ao atualizar banco de dados']);
}

$stmt->close();
$stmt_old->close();
$conexao->close();
?>