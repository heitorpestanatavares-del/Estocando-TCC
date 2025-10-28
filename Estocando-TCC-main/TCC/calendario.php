<?php
session_start();
require_once 'conexao.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: signin.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Buscar dados do usuário
$sql = "SELECT 
            c.nome as nome_cadastro,
            c.email as email_cadastro,
            p.nome_exibicao,
            p.email,
            p.foto_perfil
        FROM cadastrar c
        LEFT JOIN perfil p ON c.id = p.id_usuario
        WHERE c.id = ?";

$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$resultado = $stmt->get_result();
$usuario = $resultado->fetch_assoc();

// Usar valores de cadastro se perfil estiver vazio
if ($usuario) {
    $usuario['nome_exibicao'] = $usuario['nome_exibicao'] ?? $usuario['nome_cadastro'];
    $usuario['email'] = $usuario['email'] ?? $usuario['email_cadastro'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário - Sistema</title>
    <link rel="stylesheet" href="sidebar/sidebar.css">
    <link rel="stylesheet" href="calendario.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'sidebar/sidebar.php'; ?>

    <div class="main-content">
        <div class="calendar-header">
            <h1 class="calendar-title">Calendário</h1>
            <div class="header-actions">
                <button class="btn-today" onclick="goToToday()">Hoje</button>
                <button class="btn-add-event" onclick="openModal()">
                    <i class="fas fa-plus"></i>
                    Adicionar evento/tarefa
                </button>
            </div>
        </div>

        <div class="calendar-container">
            <!-- Sidebar de Filtros -->
            <div class="calendar-sidebar">
                <div class="filter-section">
                    <h3 class="filter-title">Filtros de Pesquisa</h3>
                    <div class="filter-toggle">
                        <span>Somente eventos online</span>
                        <div class="toggle-switch" onclick="toggleFilter(this)">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                    <div class="filter-toggle">
                        <span>Eventos repetidos</span>
                        <div class="toggle-switch" onclick="toggleFilter(this)">
                            <div class="toggle-slider"></div>
                        </div>
                    </div>
                </div>

                <div class="filter-section">
                    <h3 class="filter-title">Tipos de Evento</h3>
                    <div class="event-types">
                        <div class="event-type-item">
                            <div class="event-color vendas"></div>
                            <span>Vendas</span>
                        </div>
                        <div class="event-type-item">
                            <div class="event-color estoque"></div>
                            <span>Estoque</span>
                        </div>
                        <div class="event-type-item">
                            <div class="event-color financeiro"></div>
                            <span>Financeiro</span>
                        </div>
                        <div class="event-type-item">
                            <div class="event-color reunioes"></div>
                            <span>Reuniões</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendário Principal -->
            <div class="calendar-main">
                <div class="calendar-navigation">
                    <h2 class="nav-month" id="currentMonth">Outubro de 2025</h2>
                    <div class="nav-buttons">
                        <button class="nav-btn" onclick="previousMonth()">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="nav-btn" onclick="nextMonth()">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    </div>
                </div>

                <div class="calendar-grid" id="calendarGrid">
                    <!-- Cabeçalhos dos dias -->
                    <div class="calendar-day-header">DOM</div>
                    <div class="calendar-day-header">SEG</div>
                    <div class="calendar-day-header">TER</div>
                    <div class="calendar-day-header">QUA</div>
                    <div class="calendar-day-header">QUI</div>
                    <div class="calendar-day-header">SEX</div>
                    <div class="calendar-day-header">SÁB</div>
                    
                    <!-- Os dias serão gerados via JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Criar Evento/Tarefa -->
    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Criar evento</h2>
                <button class="modal-close" onclick="closeModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-tabs">
                <button class="modal-tab active" onclick="switchTab('evento')">Evento</button>
                <button class="modal-tab" onclick="switchTab('tarefa')">Tarefa</button>
            </div>

            <div class="modal-body">
                <!-- Tab de Evento -->
                <div class="tab-content active" id="eventoTab">
                    <form id="eventoForm">
                        <div class="form-group">
                            <label class="form-label">Título do evento</label>
                            <input type="text" class="form-input" name="titulo" placeholder="Digite o título do evento" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Categoria do evento</label>
                            <select class="form-select" name="categoria" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="vendas">Vendas</option>
                                <option value="estoque">Estoque</option>
                                <option value="financeiro">Financeiro</option>
                                <option value="reunioes">Reuniões</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-checkbox">
                                <input type="checkbox" class="checkbox-input" name="dia_inteiro">
                                <span>Evento de dia inteiro</span>
                            </label>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Data de início</label>
                                <input type="date" class="form-input" name="data_inicio" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Hora de início</label>
                                <input type="time" class="form-input" name="hora_inicio" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Data de término</label>
                                <input type="date" class="form-input" name="data_fim" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Fim dos tempos</label>
                                <input type="time" class="form-input" name="hora_fim" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Tipo de evento</label>
                            <select class="form-select" name="tipo_evento">
                                <option value="">Selecione o tipo</option>
                                <option value="presencial">Presencial</option>
                                <option value="online">Online</option>
                                <option value="hibrido">Híbrido</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Adicionar convidados</label>
                            <input type="text" class="form-input" name="convidados" placeholder="ID do usuário">
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notificação</label>
                            <select class="form-select" name="notificacao">
                                <option value="">Não notificar</option>
                                <option value="5">5 minutos antes</option>
                                <option value="15">15 minutos antes</option>
                                <option value="30">30 minutos antes</option>
                                <option value="60">1 hora antes</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Adicionar nota</label>
                            <textarea class="form-textarea" name="nota" placeholder="Digite uma descrição para o evento"></textarea>
                        </div>
                    </form>
                </div>

                <!-- Tab de Tarefa -->
                <div class="tab-content" id="tarefaTab">
                    <form id="tarefaForm">
                        <div class="form-group">
                            <label class="form-label">Task Title</label>
                            <input type="text" class="form-input" name="titulo" placeholder="Digite o título da tarefa" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Select List</label>
                            <select class="form-select" name="lista">
                                <option value="">Selecione uma lista</option>
                                <option value="pessoal">Pessoal</option>
                                <option value="trabalho">Trabalho</option>
                                <option value="urgente">Urgente</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Repeat Task</label>
                            <label class="form-checkbox">
                                <input type="checkbox" class="checkbox-input" name="dia_todo">
                                <span>All Day Task</span>
                            </label>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <input type="date" class="form-input" name="data_inicio" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Start Time</label>
                                <input type="time" class="form-input" name="hora_inicio" required>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <input type="date" class="form-input" name="data_fim" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Time</label>
                                <input type="time" class="form-input" name="hora_fim" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Add description</label>
                            <textarea class="form-textarea" name="descricao" placeholder="Digite uma descrição para a tarefa"></textarea>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-actions">
                <button class="btn-cancel" onclick="closeModal()">Descartar</button>
                <button class="btn-submit" onclick="submitForm()">Adicionar evento</button>
            </div>
        </div>
    </div>

    <script src="calendario.js"></script>
</body>
</html>