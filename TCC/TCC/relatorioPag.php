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
    <title>Relatórios - Estocando</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<link rel="stylesheet" href="css/sidebar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/relatorio.css">
</head>

<body>
    <div class="container">
        <?php include 'sidebar/sidebar.php'; ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1>Relatórios</h1>
                    <div class="breadcrumb">Início / Relatórios</div>
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

            <!-- Report Filters -->
            <div class="report-filters">
                <div class="filters-left">
                    <select class="filter-select">
                        <option>Período: Este mês</option>
                        <option>Últimos 7 dias</option>
                        <option>Últimos 30 dias</option>
                        <option>Últimos 3 meses</option>
                        <option>Este ano</option>
                    </select>
                    <select class="filter-select">
                        <option>Categoria: Todas</option>
                        <option>Vendas</option>
                        <option>Fornecedores</option>
                        <option>Despesas</option>
                        <option>Serviços</option>
                    </select>
                </div>
                <div class="filters-right">
                    <button class="btn btn-secondary">Personalizar</button>
                    <button class="btn btn-primary">Exportar PDF</button>
                </div>
            </div>

            <!-- Report Cards -->
            <div class="report-cards">
                <div class="report-card">
                    <div class="report-card-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                            <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
                        </svg>
                    </div>
                    <div class="report-card-title">Relatório Financeiro</div>
                    <div class="report-card-description">Análise completa das receitas, despesas e fluxo de caixa do período selecionado</div>
                </div>

                <div class="report-card">
                    <div class="report-card-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="report-card-title">Relatório de Estoque</div>
                    <div class="report-card-description">Movimentações, níveis atuais e produtos com baixo estoque</div>
                </div>

                <div class="report-card">
                    <div class="report-card-icon">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="report-card-title">Relatório de Vendas</div>
                    <div class="report-card-description">Performance de vendas, produtos mais vendidos e análise de clientes</div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="charts-section">
                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">Receitas vs Despesas</div>
                        <div class="chart-period">Últimos 30 dias</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <div class="chart-card">
                    <div class="chart-header">
                        <div class="chart-title">Vendas por Categoria</div>
                        <div class="chart-period">Este mês</div>
                    </div>
                    <div class="chart-container">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Summary Table -->
            <div class="summary-section">
                <h2 class="section-title">Resumo Mensal</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Métrica</th>
                            <th>Este Mês</th>
                            <th>Mês Anterior</th>
                            <th>Variação</th>
                            <th>Tendência</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Receita Total</td>
                            <td>R$ 45.280,00</td>
                            <td>R$ 40.350,00</td>
                            <td>+12,2%</td>
                            <td>
                                <span class="trend-indicator trend-up">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L10 4.414 4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Crescimento
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Despesas Totais</td>
                            <td>R$ 32.150,00</td>
                            <td>R$ 33.890,00</td>
                            <td>-5,1%</td>
                            <td>
                                <span class="trend-indicator trend-up">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L10 4.414 4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Redução
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Lucro Líquido</td>
                            <td>R$ 13.130,00</td>
                            <td>R$ 6.460,00</td>
                            <td>+103,3%</td>
                            <td>
                                <span class="trend-indicator trend-up">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L10 4.414 4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Excelente
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Produtos Vendidos</td>
                            <td>1.247 unidades</td>
                            <td>1.156 unidades</td>
                            <td>+7,9%</td>
                            <td>
                                <span class="trend-indicator trend-up">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L10 4.414 4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Crescimento
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Ticket Médio</td>
                            <td>R$ 36,32</td>
                            <td>R$ 34,91</td>
                            <td>+4,0%</td>
                            <td>
                                <span class="trend-indicator trend-up">
                                    <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3.293 9.707a1 1 0 010-1.414l6-6a1 1 0 011.414 0l6 6a1 1 0 01-1.414 1.414L10 4.414 4.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                    Melhoria
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
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