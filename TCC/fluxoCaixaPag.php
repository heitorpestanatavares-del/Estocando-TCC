<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: loginPag.php?erro=naologado");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluxo de Caixa - Estocando</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="css/sidebar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/fluxodecaixa.css">
</head>

<body>
    <div class="container">
        <?php include 'sidebar/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1>Fluxo de caixa</h1>
                    <div class="breadcrumb">Início / Fluxo de caixa</div>
                </div>
                <div class="header-right">
                    <svg class="notification-icon" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" />
                    </svg>
                    <button id="themeToggle" class="theme-toggle" title="Alternar tema">
                        <svg class="theme-icon" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Filters -->
            <div class="filters">
                <div class="filters-left">
                    <select class="filter-select">
                        <option>Últimos 30 dias</option>
                        <option>Últimos 7 dias</option>
                        <option>Este mês</option>
                        <option>Mês passado</option>
                    </select>
                    <select class="filter-select">
                        <option>Tipo: Todos</option>
                        <option>Entrada</option>
                        <option>Saída</option>
                    </select>
                    <select class="filter-select">
                        <option>Categoria: Todas</option>
                        <option>Vendas</option>
                        <option>Fornecedores</option>
                        <option>Serviços</option>
                    </select>
                </div>
                <div class="filters-right">
                    <button class="btn btn-secondary">Nova transação</button>
                    <button class="btn btn-primary">Exportar</button>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="summary-cards">
                <div class="summary-card">
                    <div class="card-title">Saldo atual</div>
                    <div class="card-value neutral">R$ 12.540,00</div>
                    <canvas class="mini-chart" id="saldoChart"></canvas>
                </div>
                <div class="summary-card">
                    <div class="card-title">Entradas no período</div>
                    <div class="card-value positive">R$ 23.800,00</div>
                    <canvas class="mini-chart" id="entradasChart"></canvas>
                </div>
                <div class="summary-card">
                    <div class="card-title">Saídas no período</div>
                    <div class="card-value negative">R$ 11.260,00</div>
                    <canvas class="mini-chart" id="saidasChart"></canvas>
                </div>
                <div class="summary-card">
                    <div class="card-title">Resultado do período</div>
                    <div class="card-value positive">R$ 12.540,00</div>
                    <div class="card-change">+52%</div>
                    <canvas class="mini-chart" id="resultadoChart"></canvas>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="chart-section">
                <div class="chart-header">
                    <div class="chart-title">Saldo ao longo do tempo</div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <div class="legend-color legend-entradas"></div>
                            <span>Entradas</span>
                        </div>
                        <div class="legend-item">
                            <div class="legend-color legend-saidas"></div>
                            <span>Saídas</span>
                        </div>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="mainChart"></canvas>
                </div>
            </div>

            <!-- Transactions Table -->
            <div class="transactions-section">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Forma</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>05/08</td>
                            <td>Venda online</td>
                            <td>Vendas</td>
                            <td><span class="status-badge status-entrada">Entrada</span></td>
                            <td style="color: #28a745; font-weight: 600;">R$ 1.250,00</td>
                            <td>Pix</td>
                            <td><span class="status-badge status-pago">Pago</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn" title="Ver detalhes">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Editar">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Mais opções">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>05/08</td>
                            <td>Compra de embalagens</td>
                            <td>Insumos</td>
                            <td><span class="status-badge status-saida">Saída</span></td>
                            <td style="color: #dc3545; font-weight: 600;">R$ 300,00</td>
                            <td>Cartão</td>
                            <td><span class="status-badge status-pago">Pago</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn" title="Ver detalhes">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Editar">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Mais opções">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>05/08</td>
                            <td>Assinatura plataforma</td>
                            <td>Serviços</td>
                            <td><span class="status-badge status-saida">Saída</span></td>
                            <td style="color: #dc3545; font-weight: 600;">R$ 89,90</td>
                            <td>Cartão</td>
                            <td><span class="status-badge status-pago">Pago</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn" title="Ver detalhes">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Editar">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Mais opções">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>07/08</td>
                            <td>Venda de produtos</td>
                            <td>Vendas</td>
                            <td><span class="status-badge status-entrada">Entrada</span></td>
                            <td style="color: #28a745; font-weight: 600;">R$ 2.740,00</td>
                            <td>Transferência</td>
                            <td><span class="status-badge status-pendente">Pendente</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn" title="Ver detalhes">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Editar">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Mais opções">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>07/08</td>
                            <td>Despesas com frete</td>
                            <td>Logística</td>
                            <td><span class="status-badge status-saida">Saída</span></td>
                            <td style="color: #dc3545; font-weight: 600;">R$ 450,00</td>
                            <td>Pix</td>
                            <td><span class="status-badge status-pago">Pago</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn" title="Ver detalhes">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Editar">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                        </svg>
                                    </button>
                                    <button class="action-btn" title="Mais opções">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="pagination">
                    <button class="active">1</button>
                    <button>2</button>
                    <button>3</button>
                    <button>></button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Configuração dos gráficos
        Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif';
        Chart.defaults.font.size = 12;

        // Mini charts para os cards
        function createMiniChart(canvasId, data, color) {
            const ctx = document.getElementById(canvasId).getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['', '', '', '', '', ''],
                    datasets: [{
                        data: data,
                        borderColor: color,
                        backgroundColor: color + '20',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: { x: { display: false }, y: { display: false } },
                    elements: { point: { radius: 0 } }
                }
            });
        }

        createMiniChart('saldoChart', [10, 12, 8, 15, 11, 13], '#1976d2');
        createMiniChart('entradasChart', [15, 18, 22, 19, 25, 24], '#28a745');
        createMiniChart('saidasChart', [8, 12, 10, 15, 11, 9], '#dc3545');
        createMiniChart('resultadoChart', [5, 8, 12, 10, 15, 18], '#28a745');

        // Gráfico principal
        const mainCtx = document.getElementById('mainChart').getContext('2d');
        new Chart(mainCtx, {
            type: 'line',
            data: {
                labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21'],
                datasets: [{
                    label: 'Entradas',
                    data: [8, 10, 12, 11, 13, 15, 14, 16, 18, 17, 19, 21, 20, 22, 24, 23, 25, 27, 26, 28, 30],
                    borderColor: '#1976d2',
                    backgroundColor: '#1976d220',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4
                }, {
                    label: 'Saídas',
                    data: [5, 7, 6, 8, 9, 7, 10, 8, 11, 9, 12, 10, 13, 11, 14, 12, 15, 13, 16, 14, 17],
                    borderColor: '#dc3545',
                    backgroundColor: '#dc354520',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6c757d' }
                    },
                    y: {
                        grid: { color: '#e9ecef' },
                        ticks: {
                            color: '#6c757d',
                            callback: function (value) { return value + 'k'; }
                        }
                    }
                },
                elements: { point: { radius: 3, hoverRadius: 5 } }
            }
        });

        // Tema
        document.addEventListener('DOMContentLoaded', function () {
            const themeToggle = document.getElementById('themeToggle');
            const body = document.body;

            const savedTheme = localStorage.getItem("theme");
            if (savedTheme === "dark") {
                body.classList.add("dark-theme");
                updateThemeIcon(true);
            }

            themeToggle.addEventListener('click', function () {
                body.classList.toggle('dark-theme');
                const isDark = body.classList.contains('dark-theme');
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                updateThemeIcon(isDark);
            });

            function updateThemeIcon(isDark) {
                const themeIcon = themeToggle.querySelector('.theme-icon');
                if (isDark) {
                    themeIcon.innerHTML = '<path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>';
                    themeToggle.title = 'Alternar para tema claro';
                } else {
                    themeIcon.innerHTML = '<path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>';
                    themeToggle.title = 'Alternar para tema escuro';
                }
            }
        });
    </script>
</body>

</html>