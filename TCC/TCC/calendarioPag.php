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
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Calendário - Estocando</title>
    <link rel="stylesheet" href="css/sidebar.css?v=<?= time() ?>">
    <link rel="stylesheet" href="css/calendario.css">
</head>

<body>
    <div class="container">
        <?php include 'sidebar/sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="header">
                <div class="header-left">
                    <h1 class="header-title">Calendário</h1>
                    <div class="calendar-nav">
                        <button class="nav-btn" onclick="previousMonth()" title="Mês anterior">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z" />
                            </svg>
                        </button>
                        <div class="current-month" id="currentMonth">Agosto
                            2025</div>
                        <button class="nav-btn" onclick="nextMonth()" title="Próximo mês">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="header-right">
                    <div class="view-toggle">
                        <button class="view-btn active" onclick="setView(this,'month')">Mês</button>
                        <button class="view-btn" onclick="setView(this,'week')">Semana</button>
                        <button class="view-btn" onclick="setView(this,'day')">Dia</button>
                    </div>
                    <button class="btn-primary" onclick="openEventModal()">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path
                                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                        </svg>
                        Novo Evento
                    </button>
                    <button id="themeToggle" class="theme-toggle" title="Alternar tema">
                        <svg class="theme-icon" fill="currentColor" viewBox="0 0 20 20">
                            <!-- Ícone será definido via JS conforme tema -->
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Wrapper -->
            <div class="calendar-wrapper">
                <!-- Calendar Main -->
                <div class="calendar-main">
                    <div class="calendar-header">
                        <div class="calendar-header-cell">Dom</div>
                        <div class="calendar-header-cell">Seg</div>
                        <div class="calendar-header-cell">Ter</div>
                        <div class="calendar-header-cell">Qua</div>
                        <div class="calendar-header-cell">Qui</div>
                        <div class="calendar-header-cell">Sex</div>
                        <div class="calendar-header-cell">Sáb</div>
                    </div>
                    <div class="calendar-grid" id="calendarGrid">
                        <!-- Cells via JS -->
                    </div>
                </div>

                <!-- Calendar Sidebar -->
                <div class="calendar-sidebar">
                    <div class="sidebar-section">
                        <h3 class="sidebar-title">Adicionar Evento</h3>
                        <div class="quick-add-form">
                            <input type="text" class="form-input" id="eventTitle" placeholder="Título do evento" />
                            <input type="date" class="form-input" id="eventDate" />
                            <select class="form-select" id="eventType">
                                <option value="vendas">Vendas</option>
                                <option value="estoque">Estoque</option>
                                <option value="financeiro">Financeiro</option>
                                <option value="reuniao">Reunião</option>
                            </select>
                            <button class="btn-add" onclick="addQuickEvent()">Adicionar</button>
                        </div>
                    </div>

                    <div class="sidebar-section">
                        <h3 class="sidebar-title">Tipos de Evento</h3>
                        <div class="event-legend">
                            <div class="legend-item">
                                <div class="legend-color vendas"></div>
                                <span>Vendas</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color estoque"></div>
                                <span>Estoque</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color financeiro"></div>
                                <span>Financeiro</span>
                            </div>
                            <div class="legend-item">
                                <div class="legend-color reuniao"></div>
                                <span>Reuniões</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Modal -->
    <div class="modal" id="eventModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Novo Evento</h3>
                <button class="modal-close" onclick="closeEventModal()">&times;</button>
            </div>
            <div class="quick-add-form">
                <input type="text" class="form-input" id="modalEventTitle" placeholder="Título do evento" />
                <input type="date" class="form-input" id="modalEventDate" />
                <input type="time" class="form-input" id="modalEventTime" />
                <select class="form-select" id="modalEventType">
                    <option value="vendas">Vendas</option>
                    <option value="estoque">Estoque</option>
                    <option value="financeiro">Financeiro</option>
                    <option value="reuniao">Reunião</option>
                </select>
                <textarea class="form-input" id="modalEventDescription" placeholder="Descrição (opcional)"
                    rows="3"></textarea>
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button class="btn-secondary" onclick="closeEventModal()">Cancelar</button>
                    <button class="btn-add" onclick="addModalEvent()">Criar
                        Evento</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ---- Calendário ----
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let draggedEvent = null;
        let eventIdCounter = 1;

        const months = [
            "Janeiro",
            "Fevereiro",
            "Março",
            "Abril",
            "Maio",
            "Junho",
            "Julho",
            "Agosto",
            "Setembro",
            "Outubro",
            "Novembro",
            "Dezembro",
        ];

        let events = {
            "2025-08-05": [
                { id: 1, title: "Reunião Vendas", type: "vendas", time: "09:00" },
                { id: 2, title: "Relatório Mensal", type: "financeiro", time: "14:00" },
            ],
            "2025-08-12": [{ id: 3, title: "Inventário Geral", type: "estoque", time: "08:00" }],
            "2025-08-15": [{ id: 4, title: "Análise Financeira", type: "financeiro", time: "10:00" }],
            "2025-08-20": [
                { id: 5, title: "Reunião Equipe", type: "reuniao", time: "15:00" },
                { id: 6, title: "Entrega Produtos", type: "vendas", time: "11:00" },
            ],
            "2025-08-25": [{ id: 7, title: "Auditoria Estoque", type: "estoque", time: "13:00" }],
            "2025-08-28": [{ id: 8, title: "Fechamento Mensal", type: "financeiro", time: "16:00" }],
        };

        function generateCalendar(month, year) {
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const daysInPrevMonth = new Date(year, month, 0).getDate();

            const calendarGrid = document.getElementById("calendarGrid");
            calendarGrid.innerHTML = "";

            for (let i = firstDay - 1; i >= 0; i--) {
                const day = daysInPrevMonth - i;
                const cell = createCalendarCell(day, true, month === 0 ? year - 1 : year, month === 0 ? 11 : month - 1);
                calendarGrid.appendChild(cell);
            }

            for (let day = 1; day <= daysInMonth; day++) {
                const cell = createCalendarCell(day, false, year, month);
                calendarGrid.appendChild(cell);
            }

            const totalCells = calendarGrid.children.length;
            const remainingCells = 42 - totalCells;
            for (let day = 1; day <= remainingCells; day++) {
                const cell = createCalendarCell(day, true, month === 11 ? year + 1 : year, month === 11 ? 0 : month + 1);
                calendarGrid.appendChild(cell);
            }

            document.getElementById("currentMonth").textContent = `${months[month]} ${year}`;
        }

        function createCalendarCell(day, isOtherMonth, year, month) {
            const cell = document.createElement("div");
            cell.className = "calendar-cell";

            if (isOtherMonth) {
                cell.classList.add("other-month");
            }

            const today = new Date();
            if (!isOtherMonth && day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                cell.classList.add("today");
            }

            const dateStr = `${year}-${String(month + 1).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

            cell.innerHTML = `
                <div class="calendar-date">
                    <div class="date-number">${day}</div>
                    <button class="add-event-btn" onclick="quickAddEvent('${dateStr}')" title="Adicionar evento">+</button>
                </div>
                <div class="calendar-events" id="events-${dateStr}"></div>
            `;

            if (events[dateStr]) {
                const eventsContainer = cell.querySelector(".calendar-events");
                events[dateStr].forEach((event) => {
                    const eventElement = createEventElement(event, dateStr);
                    eventsContainer.appendChild(eventElement);
                });
            }

            cell.addEventListener("dragover", handleDragOver);
            cell.addEventListener("drop", handleDrop);
            cell.dataset.date = dateStr;

            return cell;
        }

        function createEventElement(event, dateStr) {
            const eventElement = document.createElement("div");
            eventElement.className = `calendar-event event-${event.type} new-event`;
            eventElement.draggable = true;
            eventElement.dataset.eventId = event.id;
            eventElement.dataset.date = dateStr;

            eventElement.innerHTML = `
                <span>${event.title}</span>
                <button class="event-delete" onclick="deleteEvent(${event.id}, '${dateStr}')" title="Excluir evento">&times;</button>
            `;

            eventElement.addEventListener("dragstart", handleDragStart);
            eventElement.addEventListener("dragend", handleDragEnd);

            return eventElement;
        }

        function handleDragStart(e) {
            draggedEvent = { id: parseInt(e.target.dataset.eventId), sourceDate: e.target.dataset.date, element: e.target };
            e.target.classList.add("dragging");
        }

        function handleDragEnd(e) {
            e.target.classList.remove("dragging");
            document.querySelectorAll(".drop-zone").forEach((cell) => cell.classList.remove("drop-zone"));
            draggedEvent = null;
        }

        function handleDragOver(e) {
            e.preventDefault();
            if (draggedEvent) {
                e.currentTarget.classList.add("drop-zone");
            }
        }

        function handleDrop(e) {
            e.preventDefault();
            e.currentTarget.classList.remove("drop-zone");

            if (draggedEvent) {
                const targetDate = e.currentTarget.dataset.date;
                const sourceDate = draggedEvent.sourceDate;

                if (targetDate !== sourceDate) {
                    moveEvent(draggedEvent.id, sourceDate, targetDate);
                }
            }
        }

        function moveEvent(eventId, sourceDate, targetDate) {
            const sourceEvents = events[sourceDate];
            const eventIndex = sourceEvents.findIndex((e) => e.id === eventId);

            if (eventIndex !== -1) {
                const event = sourceEvents.splice(eventIndex, 1)[0];

                if (!events[targetDate]) {
                    events[targetDate] = [];
                }
                events[targetDate].push(event);

                if (sourceEvents.length === 0) {
                    delete events[sourceDate];
                }

                generateCalendar(currentMonth, currentYear);
                showNotification("Evento movido com sucesso!", "success");
            }
        }

        function deleteEvent(eventId, dateStr) {
            if (confirm("Tem certeza que deseja excluir este evento?")) {
                const dateEvents = events[dateStr];
                if (dateEvents) {
                    const eventIndex = dateEvents.findIndex((e) => e.id === eventId);
                    if (eventIndex !== -1) {
                        dateEvents.splice(eventIndex, 1);
                        if (dateEvents.length === 0) delete events[dateStr];
                        generateCalendar(currentMonth, currentYear);
                        showNotification("Evento excluído com sucesso!", "success");
                    }
                }
            }
        }

        function quickAddEvent(dateStr) {
            const title = prompt("Título do evento:");
            if (title) {
                const type = prompt("Tipo do evento (vendas/estoque/financeiro/reuniao):", "vendas");
                addEvent(title, dateStr, type || "vendas");
            }
        }

        function addQuickEvent() {
            const title = document.getElementById("eventTitle").value;
            const date = document.getElementById("eventDate").value;
            const type = document.getElementById("eventType").value;

            if (title && date) {
                addEvent(title, date, type);
                document.getElementById("eventTitle").value = "";
                document.getElementById("eventDate").value = "";
            } else {
                alert("Por favor, preencha o título e a data do evento.");
            }
        }

        function addEvent(title, dateStr, type, time = "09:00") {
            if (!events[dateStr]) events[dateStr] = [];

            const newEvent = { id: eventIdCounter++, title: title, type: type, time: time };
            events[dateStr].push(newEvent);

            generateCalendar(currentMonth, currentYear);
            showNotification("Evento adicionado com sucesso!", "success");
        }

        function openEventModal() {
            document.getElementById("eventModal").classList.add("show");
            document.getElementById("modalEventDate").value = new Date().toISOString().split("T")[0];
        }

        function closeEventModal() {
            document.getElementById("eventModal").classList.remove("show");
            document.getElementById("modalEventTitle").value = "";
            document.getElementById("modalEventDate").value = "";
            document.getElementById("modalEventTime").value = "";
            document.getElementById("modalEventDescription").value = "";
        }

        function addModalEvent() {
            const title = document.getElementById("modalEventTitle").value;
            const date = document.getElementById("modalEventDate").value;
            const time = document.getElementById("modalEventTime").value;
            const type = document.getElementById("modalEventType").value;

            if (title && date) {
                addEvent(title, date, type, time || "09:00");
                closeEventModal();
            } else {
                alert("Por favor, preencha o título e a data do evento.");
            }
        }

        function previousMonth() {
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            generateCalendar(currentMonth, currentYear);
        }

        function nextMonth() {
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            generateCalendar(currentMonth, currentYear);
        }

        function setView(btn, view) {
            document.querySelectorAll(".view-btn").forEach((b) => b.classList.remove("active"));
            btn.classList.add("active");
            showNotification(`Visualização alterada para: ${view}`, "info");
        }

        function showNotification(message, type = "info") {
            const notification = document.createElement("div");
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 12px 20px;
                background: ${type === "success" ? "#4caf50" : type === "error" ? "#f44336" : "#2196f3"};
                color: white;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.2);
                z-index: 3000;
                animation: slideIn 0.3s ease-out;
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => {
                notification.style.animation = "fadeOut 0.3s ease-out";
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // ---- Tema (Dark/Light) com persistência e sincronização entre abas ----
        document.addEventListener("DOMContentLoaded", function () {
            const themeToggle = document.getElementById("themeToggle");
            const body = document.body;

            function applyThemeFromStorage() {
                const saved = localStorage.getItem("theme");
                const prefersDark = window.matchMedia && window.matchMedia("(prefers-color-scheme: dark)").matches;
                const isDark = saved ? saved === "dark" : prefersDark;
                body.classList.toggle("dark-theme", isDark);
                updateThemeIcon(isDark);
            }

            function updateThemeIcon(isDark) {
                const themeIcon = themeToggle.querySelector(".theme-icon");
                if (isDark) {
                    themeIcon.innerHTML = '<path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>';
                    themeToggle.title = "Alternar para tema claro";
                } else {
                    themeIcon.innerHTML =
                        '<path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/>';
                    themeToggle.title = "Alternar para tema escuro";
                }
            }

            // Aplica tema salvo (ou preferência do SO, caso não haja salvo)
            applyThemeFromStorage();

            // Alterna tema no clique
            themeToggle.addEventListener("click", function () {
                const isDark = !body.classList.contains("dark-theme");
                body.classList.toggle("dark-theme", isDark);
                localStorage.setItem("theme", isDark ? "dark" : "light");
                updateThemeIcon(isDark);
            });

            // Sincroniza entre várias abas/janelas
            window.addEventListener("storage", function (e) {
                if (e.key === "theme") {
                    const isDark = e.newValue === "dark";
                    body.classList.toggle("dark-theme", isDark);
                    updateThemeIcon(isDark);
                }
            });

            // Inicializa calendário e formulário
            generateCalendar(currentMonth, currentYear);
            document.getElementById("eventDate").value = new Date().toISOString().split("T")[0];
        });

        // Fechar modal clicando fora
        document.getElementById("eventModal").addEventListener("click", function (e) {
            if (e.target === this) closeEventModal();
        });
    </script>
</body>

</html>