<?php
require_once 'config.php';
$pdo = getPDO();

// resumo
$totalProdutos = (int)$pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
$totalItens = (int)$pdo->query("SELECT COALESCE(SUM(quantidade),0) FROM produtos")->fetchColumn();
$baixoEstoque = (int)$pdo->query("SELECT COUNT(*) FROM produtos WHERE quantidade <= estoque_minimo")->fetchColumn();
?>
<!doctype html>
<html lang="pt-br">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Estoque — Estocando</title>
<link rel="stylesheet" href="css/estoque.css">
</head>
<body>
<div class="container">
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">E</div>
            <div class="logo-text">stocando</div>
        </div>
        <nav>
            <a href="inicioPag.php" class="nav-item"><svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg> Início</a>
            <a href="relatorioPag.php" class="nav-item"><svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg> Dashboard</a>
            <a href="calendarioPag.php" class="nav-item"><svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg> Calendário</a>
            <a href="fluxoCaixaPag.php" class="nav-item"><svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8zM6 8a2 2 0 012 2v2H6V8z"/></svg> Fluxo de caixa</a>
            <a href="estoque.php" class="nav-item active"><svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg> Estoque</a>
            <a href="perfilPag.php" class="nav-item"><svg class="nav-icon" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg> Perfil</a>
        </nav>
        <div class="user-profile">
            <div class="user-avatar">H</div>
            <div class="user-info">
                <h4>Heitor</h4>
                <p>Administrador</p>
            </div>
            <button id="themeToggle" class="theme-btn" title="Tema">🌓</button>
        </div>
    </aside>

    <main class="main-content">
        <div class="topbar">
            <div class="title">
                <h1>Produtos</h1>
                <p class="muted">Gerencie todos os produtos do seu estoque</p>
            </div>
            <div class="actions">
                <button id="btnNew" class="btn btn-primary">+ Novo Produto</button>
            </div>
        </div>

        <section class="summary">
            <div class="card small">
                <div class="card-title">Total de produtos</div>
                <div class="card-value"><?= $totalProdutos ?></div>
            </div>
            <div class="card small">
                <div class="card-title">Itens totais</div>
                <div class="card-value"><?= $totalItens ?></div>
            </div>
            <div class="card small">
                <div class="card-title">Estoque baixo</div>
                <div class="card-value low"><?= $baixoEstoque ?></div>
            </div>
        </section>

        <section class="controls">
            <div class="tabs">
                <button class="tab active" data-tab="all">Todos</button>
                <button class="tab" data-tab="low">Estoque Baixo</button>
            </div>

            <div class="filters">
                <input id="q" type="search" placeholder="Buscar por nome, categoria ou código...">
                <select id="filterCategory"><option value="">Todas as categorias</option></select>
                <select id="sortBy">
                    <option value="data_criacao|DESC">Mais recentes</option>
                    <option value="nome_produto|ASC">Nome A–Z</option>
                    <option value="nome_produto|DESC">Nome Z–A</option>
                    <option value="preco|ASC">Preço asc</option>
                    <option value="preco|DESC">Preço desc</option>
                    <option value="quantidade|ASC">Quantidade asc</option>
                    <option value="quantidade|DESC">Quantidade desc</option>
                </select>
                <button id="btnFilter" class="btn btn-outline">Aplicar</button>
            </div>
        </section>

        <section class="list-area">
            <div id="tableWrapper" class="table-wrapper"></div>
            <div id="pagination" class="pagination"></div>
        </section>
    </main>
</div>

<!-- Modal Novo / Editar -->
<div id="modalForm" class="modal">
    <div class="modal-content form-modal">
        <div class="modal-header">
            <h2 id="modalTitle">Novo Produto</h2>
            <button class="close-btn" onclick="closeModal()">✕</button>
        </div>
        <form id="productForm" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
            <input type="hidden" name="id_produtos" id="id_produtos" value="">
            <div class="form-grid">
                <label>
                    <div class="label">Nome do Produto *</div>
                    <input name="nome_produto" id="nome_produto" required>
                </label>
                <label>
                    <div class="label">Categoria</div>
                    <input name="categoria" id="categoria">
                </label>
                <label>
                    <div class="label">Preço (R$)</div>
                    <input name="preco" id="preco" type="number" step="0.01">
                </label>
                <label>
                    <div class="label">Quantidade</div>
                    <input name="quantidade" id="quantidade" type="number" step="1">
                </label>
                <label>
                    <div class="label">Estoque mínimo</div>
                    <input name="estoque_minimo" id="estoque_minimo" type="number" step="1">
                </label>
                <label>
                    <div class="label">Unidade</div>
                    <input name="unidade_medida" id="unidade_medida">
                </label>
                <label class="full">
                    <div class="label">Descrição</div>
                    <textarea name="descricao" id="descricao" rows="3"></textarea>
                </label>
                <label class="full">
                    <div class="label">Imagem</div>
                    <div class="image-uploader">
                        <input type="file" name="imagem" id="imagem" accept="image/png,image/jpeg,image/webp">
                        <div id="preview" class="preview">Nenhuma imagem</div>
                        <label class="btn btn-outline" id="btnRemoveImage"><input type="checkbox" name="remove_image" value="1"> Remover imagem</label>
                    </div>
                </label>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary" id="saveBtn">Salvar</button>
            </div>
        </form>
    </div>
</div>

<script>
// ===== utilities =====
const el = sel => document.querySelector(sel);
const els = sel => Array.from(document.querySelectorAll(sel));
const escapeHtml = s => {
    if (s === null || s === undefined) return '';
    return String(s)
        .replace(/&/g,'&amp;')
        .replace(/</g,'&lt;')
        .replace(/>/g,'&gt;')
        .replace(/"/g,'&quot;')
        .replace(/'/g,'&#39;');
};

// state
let currentTab = 'all';
let currentPage = 1;
let perPage = 8;
let currentFilter = {
    busca: '',
    categoria: '',
    order: 'data_criacao',
    dir: 'DESC',
    status: '', // '', 'low', 'out'
};

// build query string
function buildQuery() {
    const [order, dir] = el('#sortBy').value.split('|');
    const q = new URLSearchParams();
    if (currentFilter.busca) q.set('busca', currentFilter.busca);
    if (currentFilter.categoria) q.set('categoria', currentFilter.categoria);
    if (currentFilter.status) q.set('status', currentFilter.status);
    q.set('order', order);
    q.set('dir', dir);
    q.set('page', currentPage);
    q.set('per_page', perPage);
    return q.toString();
}

// fetch list
async function fetchList() {
    el('#tableWrapper').innerHTML = '<div class="loading">Carregando...</div>';
    const qs = buildQuery();
    try {
        const res = await fetch('product_actions.php?action=list&' + qs, { headers: { 'Accept':'application/json' } });
        const data = await res.json();
        if (!data.success) {
            el('#tableWrapper').innerHTML = '<div class="error">Erro ao carregar.</div>';
            return;
        }
        renderTable(data.data);
        renderPagination(data.meta);
        populateCategories(data.data);
    } catch (e) {
        el('#tableWrapper').innerHTML = '<div class="error">Erro de rede.</div>';
    }
}

// render table (cards style similar to exemplo)
function renderTable(items) {
    const container = document.createElement('div');
    container.className = 'list-cards';
    items.forEach(p => {
        const card = document.createElement('div');
        card.className = 'product-row';
        if (p.status === 'low') card.classList.add('low-stock');
        if (p.status === 'out') card.classList.add('out-stock');

        const img = document.createElement('img');
        img.className = 'prod-thumb';
        img.src = p.imagem_path;
        img.alt = p.nome_produto;

        const info = document.createElement('div');
        info.className = 'prod-info';
        const name = document.createElement('div');
        name.className = 'prod-name';
        name.innerHTML = `<strong>${escapeHtml(p.nome_produto)}</strong><div class="muted small">Cód: ${escapeHtml(p.id_produtos)}</div>`;
        const cat = document.createElement('div');
        cat.className = 'badge';
        cat.textContent = p.categoria || '—';

        const meta = document.createElement('div');
        meta.className = 'prod-meta';
        meta.innerHTML = `<div>Qtd: <strong>${p.quantidade}</strong> <span class="muted small">Min: ${p.estoque_minimo}</span></div>
                          <div class="price">R$ ${Number(p.preco).toFixed(2)}</div>`;

        const status = document.createElement('div');
        status.className = 'status';
        const st = (p.status === 'out') ? 'Esgotado' : (p.status === 'low' ? 'Estoque Baixo' : 'Normal');
        status.innerHTML = `<span class="status-badge ${p.status}">${st}</span>`;

        const actions = document.createElement('div');
        actions.className = 'prod-actions';
        const btnEdit = document.createElement('button');
        btnEdit.className = 'icon-btn';
        btnEdit.title = 'Editar';
        btnEdit.textContent = '✏️';
        btnEdit.addEventListener('click', () => openEdit(p));
        const btnDel = document.createElement('button');
        btnDel.className = 'icon-btn';
        btnDel.title = 'Excluir';
        btnDel.textContent = '🗑️';
        btnDel.addEventListener('click', () => removeProduct(p.id_produtos));
        actions.appendChild(btnEdit);
        actions.appendChild(btnDel);

        info.appendChild(name);
        info.appendChild(cat);
        info.appendChild(meta);

        card.appendChild(img);
        card.appendChild(info);
        card.appendChild(status);
        card.appendChild(actions);

        container.appendChild(card);
    });
    el('#tableWrapper').innerHTML = '';
    el('#tableWrapper').appendChild(container);
}

// pagination
function renderPagination(meta) {
    const root = el('#pagination');
    root.innerHTML = '';
    const total = meta.total_pages;
    const page = meta.page;
    const createBtn = (i, txt = null) => {
        const a = document.createElement('button');
        a.className = 'page-btn' + (i === page ? ' active' : '');
        a.textContent = txt || i;
        a.addEventListener('click', () => { currentPage = i; fetchList(); });
        return a;
    };
    if (total <= 1) return;
    const start = Math.max(1, page - 2);
    const end = Math.min(total, page + 2);
    if (page > 1) root.appendChild(createBtn(page - 1, '‹'));
    for (let i = start; i <= end; i++) root.appendChild(createBtn(i));
    if (page < total) root.appendChild(createBtn(page + 1, '›'));
}

// populate categories select
function populateCategories(items) {
    const sel = el('#filterCategory');
    // collect existing options
    const existing = new Set(Array.from(sel.options).map(o => o.value));
    items.forEach(p => {
        if (p.categoria && !existing.has(p.categoria)) {
            const opt = document.createElement('option');
            opt.value = p.categoria;
            opt.textContent = p.categoria;
            sel.appendChild(opt);
            existing.add(p.categoria);
        }
    });
}

// open new modal
function openNew() {
    el('#modalTitle').textContent = 'Novo Produto';
    el('#productForm').reset();
    el('#id_produtos').value = '';
    el('#preview').textContent = 'Nenhuma imagem';
    el('#btnRemoveImage').style.display = 'none';
    el('#modalForm').style.display = 'flex';
}

// open edit modal prefill
function openEdit(p) {
    el('#modalTitle').textContent = 'Editar Produto';
    el('#id_produtos').value = p.id_produtos;
    el('#nome_produto').value = p.nome_produto;
    el('#categoria').value = p.categoria;
    el('#preco').value = p.preco;
    el('#quantidade').value = p.quantidade;
    el('#estoque_minimo').value = p.estoque_minimo;
    el('#unidade_medida').value = p.unidade_medida;
    el('#descricao').value = p.descricao;
    if (p.imagem_path) {
        el('#preview').innerHTML = `<img src="${p.imagem_path}" alt="" style="max-width:160px;max-height:120px;border-radius:6px;">`;
        el('#btnRemoveImage').style.display = 'inline-block';
    } else {
        el('#preview').textContent = 'Nenhuma imagem';
        el('#btnRemoveImage').style.display = 'none';
    }
    el('#modalForm').style.display = 'flex';
}

// close modal
function closeModal() {
    el('#modalForm').style.display = 'none';
}

// remove product (ajax)
async function removeProduct(id) {
    if (!confirm('Excluir este produto?')) return;
    try {
        const fd = new FormData();
        fd.append('id', id);
        fd.append('csrf_token', '<?= csrf_token() ?>');
        const res = await fetch('product_actions.php?action=delete', { method:'POST', body: fd, headers:{ 'X-Requested-With':'XMLHttpRequest' } });
        const data = await res.json();
        if (data.success) {
            fetchList();
            alert(data.message);
        } else alert(data.message || 'Erro');
    } catch (e) {
        alert('Erro de rede.');
    }
}

// form submit (create/update) via fetch
el('#productForm').addEventListener('submit', async function(e){
    e.preventDefault();
    const form = e.currentTarget;
    const fd = new FormData(form);
    const id = el('#id_produtos').value;
    const action = id ? 'update' : 'create';
    try {
        const res = await fetch('product_actions.php?action=' + action, {
            method: 'POST',
            body: fd,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        if (data.success) {
            closeModal();
            fetchList();
            alert(data.message);
        } else {
            alert(data.message || 'Erro no servidor');
        }
    } catch (err) {
        alert('Erro de rede.');
    }
});

// preview image
el('#imagem').addEventListener('change', function(){
    const f = this.files[0];
    if (!f) { el('#preview').textContent = 'Nenhuma imagem'; return; }
    if (f.size > <?= MAX_UPLOAD_SIZE ?>) {
        alert('Arquivo muito grande.');
        this.value = '';
        return;
    }
    const fr = new FileReader();
    fr.onload = function(){ el('#preview').innerHTML = `<img src="${fr.result}" style="max-width:160px;max-height:120px;border-radius:6px;">`; };
    fr.readAsDataURL(f);
    el('#btnRemoveImage').style.display = 'inline-block';
});

// filters apply
el('#btnFilter').addEventListener('click', () => {
    currentFilter.busca = el('#q').value.trim();
    currentFilter.categoria = el('#filterCategory').value;
    const [o, d] = el('#sortBy').value.split('|');
    currentFilter.order = o; currentFilter.dir = d;
    currentPage = 1;
    currentFilter.status = (currentTab === 'low') ? 'low' : '';
    fetchList();
});

// tabs
els('.tab').forEach(t => t.addEventListener('click', function(){
    els('.tab').forEach(x=>x.classList.remove('active'));
    this.classList.add('active');
    currentTab = this.dataset.tab;
    currentPage = 1;
    currentFilter.status = (currentTab === 'low') ? 'low' : '';
    fetchList();
}));

// new button
el('#btnNew').addEventListener('click', openNew);

// theme toggle
const themeToggle = el('#themeToggle');
function applyTheme() {
    if (localStorage.getItem('dark') === '1') document.body.classList.add('dark-theme');
    else document.body.classList.remove('dark-theme');
}
themeToggle.addEventListener('click', () => {
    localStorage.setItem('dark', localStorage.getItem('dark') === '1' ? '0' : '1');
    applyTheme();
});
applyTheme();

// initial load
fetchList();
</script>
</body>
</html>
