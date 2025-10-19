<?php
session_start();
include_once('conexao.php');

// Função para validar se o e-mail realmente existe (verificação DNS MX)
function validarEmailReal($email) {
    // Verifica formato básico
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return false;
    }
    
    // Extrai o domínio do e-mail
    $domain = substr(strrchr($email, "@"), 1);
    
    // Verifica se o domínio tem registros MX (servidores de e-mail)
    if (checkdnsrr($domain, "MX")) {
        return true;
    }
    
    // Se não tem MX, verifica se tem registro A (alguns domínios usam)
    if (checkdnsrr($domain, "A")) {
        return true;
    }
    
    return false;
}

// Função para validar CNPJ matematicamente (algoritmo oficial)
function validarCNPJ($cnpj) {
    $cnpj = preg_replace('/\D/', '', $cnpj);
    
    // Verifica se tem 14 dígitos
    if (strlen($cnpj) != 14) {
        return false;
    }
    
    // Verifica se todos os dígitos são iguais
    if (preg_match('/(\d)\1{13}/', $cnpj)) {
        return false;
    }
    
    // Validação do primeiro dígito verificador
    $soma = 0;
    $peso = 5;
    for ($i = 0; $i < 12; $i++) {
        $soma += $cnpj[$i] * $peso;
        $peso = ($peso == 2) ? 9 : $peso - 1;
    }
    $resto = $soma % 11;
    $digito1 = ($resto < 2) ? 0 : 11 - $resto;
    
    if ($cnpj[12] != $digito1) {
        return false;
    }
    
    // Validação do segundo dígito verificador
    $soma = 0;
    $peso = 6;
    for ($i = 0; $i < 13; $i++) {
        $soma += $cnpj[$i] * $peso;
        $peso = ($peso == 2) ? 9 : $peso - 1;
    }
    $resto = $soma % 11;
    $digito2 = ($resto < 2) ? 0 : 11 - $resto;
    
    if ($cnpj[13] != $digito2) {
        return false;
    }
    
    return true;
}

// Função para consultar CNPJ na Receita Federal (via API pública)
function consultarCNPJReceita($cnpj) {
    $cnpj = preg_replace('/\D/', '', $cnpj);
    
    // Primeiro valida o CNPJ matematicamente
    if (!validarCNPJ($cnpj)) {
        return ['sucesso' => false, 'mensagem' => 'CNPJ inválido'];
    }
    
    // Tenta consultar na Brasil API
    $url = "https://brasilapi.com.br/api/cnpj/v1/" . $cnpj;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200 && $response) {
        $data = json_decode($response, true);
        
        if (isset($data['razao_social'])) {
            return [
                'sucesso' => true,
                'razao_social' => $data['razao_social'],
                'situacao' => $data['descricao_situacao_cadastral'] ?? 'Ativa'
            ];
        }
    }
    
    // Se a API falhar, tenta a ReceitaWS como backup
    $url2 = "https://www.receitaws.com.br/v1/cnpj/" . $cnpj;
    
    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $url2);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch2, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);
    
    $response2 = curl_exec($ch2);
    curl_close($ch2);
    
    if ($response2) {
        $data2 = json_decode($response2, true);
        
        if (isset($data2['nome']) && $data2['status'] != 'ERROR') {
            return [
                'sucesso' => true,
                'razao_social' => $data2['nome'],
                'situacao' => $data2['situacao'] ?? 'Ativa'
            ];
        }
    }
    
    // Se ambas APIs falharem mas o CNPJ é válido matematicamente
    // retorna sucesso (pode ser problema temporário da API)
    return [
        'sucesso' => true,
        'mensagem' => 'CNPJ válido (verificação matemática)'
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['firstName']);
    $cnpj = preg_replace('/\D/', '', $_POST['cnpj']); // Remove formatação
    $email = trim($_POST['email']);
    $senha = $_POST['password'];
    
    // Array para armazenar erros
    $erros = [];
    
    // ============================================
    // VALIDAÇÕES BÁSICAS
    // ============================================
    
    if (empty($nome)) {
        $erros[] = "O nome é obrigatório.";
    }
    
    if (strlen($cnpj) !== 14) {
        $erros[] = "CNPJ inválido. Deve conter 14 dígitos.";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Formato de e-mail inválido.";
    }
    
    if (strlen($senha) < 6) {
        $erros[] = "A senha deve ter no mínimo 6 caracteres.";
    }
    
    // ============================================
    // VALIDAÇÃO DE E-MAIL REAL (DNS)
    // ============================================
    
    if (empty($erros) && !empty($email)) {
        if (!validarEmailReal($email)) {
            $erros[] = "O e-mail informado não existe ou o domínio é inválido. Verifique e tente novamente.";
        }
    }
    
    // ============================================
    // VALIDAÇÃO DE CNPJ REAL (RECEITA FEDERAL)
    // ============================================
    
    if (empty($erros) && !empty($cnpj)) {
        $resultadoCNPJ = consultarCNPJReceita($cnpj);
        
        if (!$resultadoCNPJ['sucesso']) {
            $erros[] = "O CNPJ informado é inválido. Verifique os dígitos e tente novamente.";
        } else {
            // Verifica se a empresa está ativa
            if (isset($resultadoCNPJ['situacao']) && 
                stripos($resultadoCNPJ['situacao'], 'baixada') !== false) {
                $erros[] = "O CNPJ informado pertence a uma empresa com situação cadastral irregular (baixada).";
            }
        }
    }
    
    // ============================================
    // VERIFICAÇÃO DE DUPLICIDADE NO BANCO
    // ============================================
    
    // Verificar se CNPJ já existe
    if (empty($erros)) {
        $sql_check = "SELECT id FROM cadastrar WHERE cnpj = ?";
        $stmt = $conexao->prepare($sql_check);
        $stmt->bind_param("s", $cnpj);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $erros[] = "Este CNPJ já está cadastrado no sistema. Faça login ou recupere sua senha.";
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
            $erros[] = "Este e-mail já está cadastrado no sistema. Faça login ou recupere sua senha.";
        }
        $stmt->close();
    }
    
    // ============================================
    // PROCESSAR RESULTADO
    // ============================================
    
    // Se houver erros, redireciona de volta
    if (!empty($erros)) {
        $_SESSION['erro_cadastro'] = implode("<br>", $erros);
        // Salvar dados do formulário para reexibir
        $_SESSION['form_nome'] = $nome;
        $_SESSION['form_cnpj'] = $_POST['cnpj']; // Com formatação
        $_SESSION['form_email'] = $email;
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
        $_SESSION['erro_cadastro'] = "Erro ao cadastrar no banco de dados. Tente novamente.";
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
