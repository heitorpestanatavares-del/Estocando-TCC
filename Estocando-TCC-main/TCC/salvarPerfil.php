<?php
include "conexao.php";

$id_usuario = $_POST['id_usuario'];
$telefone     = $_POST['telefone'] ?? null;
$empresa      = $_POST['empresa'] ?? null;
$cargo        = $_POST['cargo'] ?? null;
$departamento = $_POST['departamento'] ?? null;
$localizacao  = $_POST['localizacao'] ?? null;
$bio          = $_POST['bio'] ?? null;
$nome         = $_POST['nome'] ?? null;   // ADICIONADO
$email        = $_POST['email'] ?? null;  // ADICIONADO

// Upload da foto
$foto_perfil = null;
if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] == 0) {
    $pasta = "uploads/";
    if (!is_dir($pasta)) {
        mkdir($pasta, 0777, true);
    }

    $extensao = pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION);
    $nome_arquivo = uniqid() . "." . $extensao;
    $destino = $pasta . $nome_arquivo;

    if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
        $foto_perfil = $destino;
    }
}

// Atualiza nome e email na tabela cadastrar
$sql_cadastrar = "UPDATE cadastrar SET nome=?, email=? WHERE id=?";
$stmt1 = $conexao->prepare($sql_cadastrar);
$stmt1->bind_param("ssi", $nome, $email, $id_usuario);
$stmt1->execute();
$stmt1->close();

// Verifica se já existe perfil
$sql_check = "SELECT id_usuario FROM perfil WHERE id_usuario = ?";
$stmt = $conexao->prepare($sql_check);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // Atualizar perfil
    if ($foto_perfil) {
        $sql = "UPDATE perfil SET telefone=?, empresa=?, cargo=?, departamento=?, localizacao=?, bio=?, foto_perfil=? WHERE id_usuario=?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sssssssi", $telefone, $empresa, $cargo, $departamento, $localizacao, $bio, $foto_perfil, $id_usuario);
    } else {
        $sql = "UPDATE perfil SET telefone=?, empresa=?, cargo=?, departamento=?, localizacao=?, bio=? WHERE id_usuario=?";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ssssssi", $telefone, $empresa, $cargo, $departamento, $localizacao, $bio, $id_usuario);
    }
} else {
    // Inserir perfil
    $sql = "INSERT INTO perfil (id_usuario, telefone, empresa, cargo, departamento, localizacao, bio, foto_perfil)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("isssssss", $id_usuario, $telefone, $empresa, $cargo, $departamento, $localizacao, $bio, $foto_perfil);
}

if ($stmt->execute()) {
    header("Location: perfilPag.php"); 
    exit;
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conexao->close();
?>
