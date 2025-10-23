<?php
require_once 'config.php';
$pdo = getPDO();

// Helpers
function is_ajax() {
    return (
        !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) || (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false);
}

function sanitize($str) {
    return htmlspecialchars(trim((string)$str), ENT_QUOTES, 'UTF-8');
}

function json_response($data, $code = 200) {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

// upload seguro
function handle_upload($file, $old_path = null) {
    if (!isset($file) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return $old_path ?: null;
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Erro no upload.');
    }
    if ($file['size'] > MAX_UPLOAD_SIZE) {
        throw new RuntimeException('Arquivo muito grande (máx 2MB).');
    }
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    if (!in_array($mime, ALLOWED_TYPES)) {
        throw new RuntimeException('Tipo de arquivo inválido.');
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed_ext = ['jpg','jpeg','png','webp'];
    if (!in_array($ext, $allowed_ext)) {
        throw new RuntimeException('Extensão inválida.');
    }
    $new_name = uniqid('', true) . '.' . $ext;
    $dest_full = UPLOAD_DIR . $new_name;
    if (!is_dir(UPLOAD_DIR)) {
        if (!mkdir(UPLOAD_DIR, 0755, true)) {
            throw new RuntimeException('Não foi possível criar pasta de upload.');
        }
    }
    if (!move_uploaded_file($file['tmp_name'], $dest_full)) {
        throw new RuntimeException('Falha ao mover arquivo.');
    }
    // proteger permissão
    @chmod($dest_full, 0644);

    // excluir antigo se existir (old_path guarda caminho relativo tipo uploadsprodutos/aaa.png)
    if ($old_path) {
        $old_full = __DIR__ . '/' . $old_path;
        if (is_file($old_full)) {
            @unlink($old_full);
        }
    }

    return 'uploadsprodutos/' . $new_name;
}

// =================== AÇÕES ===================
$action = $_GET['action'] ?? ($_POST['action'] ?? '');

try {
    switch ($action) {
        // -------- LIST (JSON) --------
        case 'list':
            // filtros via GET
            $busca = $_GET['busca'] ?? null;
            $categoria = $_GET['categoria'] ?? null;
            $min_price = isset($_GET['min_price']) ? (float)$_GET['min_price'] : null;
            $max_price = isset($_GET['max_price']) ? (float)$_GET['max_price'] : null;
            $status = $_GET['status'] ?? null; // all | low | out
            $order = in_array($_GET['order'] ?? 'data_criacao', ['nome_produto','quantidade','preco','data_criacao']) ? $_GET['order'] : 'data_criacao';
            $dir = strtoupper($_GET['dir'] ?? 'DESC') === 'ASC' ? 'ASC' : 'DESC';
            $page = max(1, (int)($_GET['page'] ?? 1));
            $per_page = max(1, min(100, (int)($_GET['per_page'] ?? 10)));
            $offset = ($page - 1) * $per_page;

            $where = [];
            $params = [];

            if ($busca) {
                $where[] = "(nome_produto LIKE :busca OR descricao LIKE :busca OR categoria LIKE :busca)";
                $params[':busca'] = '%' . $busca . '%';
            }
            if ($categoria) {
                $where[] = "categoria = :categoria";
                $params[':categoria'] = $categoria;
            }
            if ($min_price !== null) {
                $where[] = "preco >= :min_price";
                $params[':min_price'] = $min_price;
            }
            if ($max_price !== null) {
                $where[] = "preco <= :max_price";
                $params[':max_price'] = $max_price;
            }

            if ($status === 'low') {
                $where[] = "quantidade <= estoque_minimo";
            } elseif ($status === 'out') {
                $where[] = "quantidade <= 0";
            }

            $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

            // total
            $stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM produtos $whereSQL");
            $stmtTotal->execute($params);
            $total = (int)$stmtTotal->fetchColumn();

            $stmt = $pdo->prepare("SELECT * FROM produtos $whereSQL ORDER BY $order $dir LIMIT :lim OFFSET :off");
            foreach ($params as $k => $v) $stmt->bindValue($k, $v);
            $stmt->bindValue(':lim', $per_page, PDO::PARAM_INT);
            $stmt->bindValue(':off', $offset, PDO::PARAM_INT);
            $stmt->execute();
            $produtos = $stmt->fetchAll();

            // normalizar caminho de imagem (null -> placeholder)
            foreach ($produtos as &$p) {
                if (empty($p['imagem_path']) || !file_exists(__DIR__ . '/' . $p['imagem_path'])) {
                    $p['imagem_path'] = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300"><rect width="100%" height="100%" fill="#f3f4f6"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="#9ca3af" font-family="Arial" font-size="20">sem imagem</text></svg>');
                } else {
                    $p['imagem_path'] = $p['imagem_path'];
                }
                // status
                if ((int)$p['quantidade'] <= 0) $p['status'] = 'out';
                elseif ((int)$p['quantidade'] <= (int)$p['estoque_minimo']) $p['status'] = 'low';
                else $p['status'] = 'normal';
            }
            json_response([
                'success' => true,
                'meta' => [
                    'total' => $total,
                    'page' => $page,
                    'per_page' => $per_page,
                    'total_pages' => (int)ceil($total / $per_page)
                ],
                'data' => $produtos
            ]);
            break;

        // -------- CREATE --------
        case 'create':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new RuntimeException('Método inválido.');
            }
            verify_csrf($_POST['csrf_token'] ?? '');
            $nome = sanitize($_POST['nome_produto'] ?? '');
            if ($nome === '') throw new RuntimeException('Nome obrigatório.');
            $descricao = sanitize($_POST['descricao'] ?? '');
            $categoria = sanitize($_POST['categoria'] ?? '');
            $preco = isset($_POST['preco']) ? (float)$_POST['preco'] : 0.0;
            $quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 0;
            $estoque_minimo = isset($_POST['estoque_minimo']) ? (int)$_POST['estoque_minimo'] : 0;
            $unidade = sanitize($_POST['unidade_medida'] ?? '');

            // upload
            $img_path = null;
            if (!empty($_FILES['imagem'])) {
                $img_path = handle_upload($_FILES['imagem']);
            }

            $sql = "INSERT INTO produtos (nome_produto, descricao, categoria, preco, quantidade, estoque_minimo, unidade_medida, imagem_path)
                    VALUES (:n,:d,:c,:p,:q,:m,:u,:i)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':n' => $nome, ':d' => $descricao, ':c' => $categoria, ':p' => $preco,
                ':q' => $quantidade, ':m' => $estoque_minimo, ':u' => $unidade, ':i' => $img_path
            ]);
            $id = $pdo->lastInsertId();
            $stmt2 = $pdo->prepare("SELECT * FROM produtos WHERE id_produtos = ?");
            $stmt2->execute([$id]);
            $produto = $stmt2->fetch();

            if (is_ajax()) {
                json_response(['success'=>true, 'message'=>'Produto criado.', 'product'=>$produto]);
            } else {
                header('Location: estoque.php?msg=created');
                exit;
            }
            break;

        // -------- UPDATE --------
        case 'update':
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new RuntimeException('Método inválido.');
            }
            verify_csrf($_POST['csrf_token'] ?? '');
            $id = (int)($_POST['id_produtos'] ?? 0);
            if ($id <= 0) throw new RuntimeException('ID inválido.');
            $stmtOld = $pdo->prepare("SELECT imagem_path FROM produtos WHERE id_produtos = ?");
            $stmtOld->execute([$id]);
            $old_img = $stmtOld->fetchColumn();

            $nome = sanitize($_POST['nome_produto'] ?? '');
            if ($nome === '') throw new RuntimeException('Nome obrigatório.');
            $descricao = sanitize($_POST['descricao'] ?? '');
            $categoria = sanitize($_POST['categoria'] ?? '');
            $preco = isset($_POST['preco']) ? (float)$_POST['preco'] : 0.0;
            $quantidade = isset($_POST['quantidade']) ? (int)$_POST['quantidade'] : 0;
            $estoque_minimo = isset($_POST['estoque_minimo']) ? (int)$_POST['estoque_minimo'] : 0;
            $unidade = sanitize($_POST['unidade_medida'] ?? '');

            $new_img = $old_img;
            if (!empty($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
                $new_img = handle_upload($_FILES['imagem'], $old_img);
            } elseif (isset($_POST['remove_image']) && $_POST['remove_image'] == '1') {
                // remover imagem
                if ($old_img) {
                    $fullOld = __DIR__ . '/' . $old_img;
                    if (is_file($fullOld)) @unlink($fullOld);
                }
                $new_img = null;
            }

            $sql = "UPDATE produtos SET nome_produto=:n, descricao=:d, categoria=:c, preco=:p, quantidade=:q, estoque_minimo=:m, unidade_medida=:u, imagem_path=:i WHERE id_produtos=:id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':n'=>$nome, ':d'=>$descricao, ':c'=>$categoria, ':p'=>$preco,
                ':q'=>$quantidade, ':m'=>$estoque_minimo, ':u'=>$unidade, ':i'=>$new_img,
                ':id'=>$id
            ]);
            $stmt2 = $pdo->prepare("SELECT * FROM produtos WHERE id_produtos = ?");
            $stmt2->execute([$id]);
            $produto = $stmt2->fetch();

            if (is_ajax()) {
                json_response(['success'=>true, 'message'=>'Produto atualizado.', 'product'=>$produto]);
            } else {
                header('Location: estoque.php?msg=updated');
                exit;
            }
            break;

        // -------- DELETE --------
        case 'delete':
            // support POST AJAX delete or GET redirect delete (backwards compat)
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                verify_csrf($_POST['csrf_token'] ?? '');
                $id = (int)($_POST['id'] ?? 0);
            } else {
                $id = (int)($_GET['id'] ?? 0);
            }
            if ($id <= 0) throw new RuntimeException('ID inválido.');
            $stmt = $pdo->prepare("SELECT imagem_path FROM produtos WHERE id_produtos=?");
            $stmt->execute([$id]);
            $img = $stmt->fetchColumn();
            if ($img) {
                $full = __DIR__ . '/' . $img;
                if (is_file($full)) @unlink($full);
            }
            $pdo->prepare("DELETE FROM produtos WHERE id_produtos=?")->execute([$id]);

            if (is_ajax()) {
                json_response(['success'=>true, 'message'=>'Produto removido.']);
            } else {
                header('Location: estoque.php?msg=deleted');
                exit;
            }
            break;

        default:
            throw new RuntimeException('Ação inválida.');
    }
} catch (Exception $e) {
    if (is_ajax()) {
        json_response(['success'=>false, 'message'=>$e->getMessage()], 400);
    } else {
        die('Erro: ' . $e->getMessage());
    }
}
