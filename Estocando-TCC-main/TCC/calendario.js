// Estado global do calendário
let currentDate = new Date();
let currentMonth = currentDate.getMonth();
let currentYear = currentDate.getFullYear();
let eventos = [
    // Exemplos de eventos (depois você vai buscar do banco)
    {
        id: 1,
        titulo: 'Reunião de equipe',
        tipo: 'reunioes',
        data: '2025-10-27',
        hora: '14:00'
    },
    {
        id: 2,
        titulo: 'Apresentação do projeto',
        tipo: 'vendas',
        data: '2025-10-29',
        hora: '10:00'
    },
    {
        id: 3,
        titulo: 'Sincronização semanal',
        tipo: 'financeiro',
        data: '2025-10-30',
        hora: '11:00'
    },
    {
        id: 4,
        titulo: 'Inventário mensal',
        tipo: 'estoque',
        data: '2025-10-25',
        hora: '09:00'
    }
];

const meses = [
    'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
    'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
];

// Inicializar calendário quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    renderCalendar();
    setupEventListeners();
});

// Configurar event listeners
function setupEventListeners() {
    // Fechar modal ao clicar fora
    document.getElementById('modalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });
}

// Renderizar calendário
function renderCalendar() {
    const calendarGrid = document.getElementById('calendarGrid');
    const monthTitle = document.getElementById('currentMonth');
    
    // Atualizar título do mês
    monthTitle.textContent = `${meses[currentMonth]} de ${currentYear}`;
    
    // Limpar calendário (manter cabeçalhos)
    const headers = calendarGrid.querySelectorAll('.calendar-day-header');
    calendarGrid.innerHTML = '';
    headers.forEach(header => calendarGrid.appendChild(header));
    
    // Obter primeiro e último dia do mês
    const firstDay = new Date(currentYear, currentMonth, 1);
    const lastDay = new Date(currentYear, currentMonth + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startDayOfWeek = firstDay.getDay();
    
    // Obter dias do mês anterior
    const prevMonthLastDay = new Date(currentYear, currentMonth, 0).getDate();
    const prevMonthDays = startDayOfWeek;
    
    // Dias do mês anterior
    for (let i = prevMonthDays - 1; i >= 0; i--) {
        const day = prevMonthLastDay - i;
        const dayElement = createDayElement(day, 'other-month', null);
        calendarGrid.appendChild(dayElement);
    }
    
    // Dias do mês atual
    for (let day = 1; day <= daysInMonth; day++) {
        const isToday = day === currentDate.getDate() && 
                       currentMonth === currentDate.getMonth() && 
                       currentYear === currentDate.getFullYear();
        
        const dayElement = createDayElement(day, isToday ? 'today' : '', day);
        calendarGrid.appendChild(dayElement);
    }
    
    // Dias do próximo mês
    const remainingDays = 42 - (prevMonthDays + daysInMonth); // 6 semanas x 7 dias
    for (let day = 1; day <= remainingDays; day++) {
        const dayElement = createDayElement(day, 'other-month', null);
        calendarGrid.appendChild(dayElement);
    }
}

// Criar elemento de dia
function createDayElement(dayNumber, className = '', actualDay = null) {
    const dayDiv = document.createElement('div');
    dayDiv.className = `calendar-day ${className}`;
    
    const dayNumberDiv = document.createElement('div');
    dayNumberDiv.className = 'day-number';
    dayNumberDiv.textContent = dayNumber;
    
    dayDiv.appendChild(dayNumberDiv);
    
    // Adicionar eventos do dia
    if (actualDay) {
        const dayEvents = getEventsForDay(actualDay);
        
        if (dayEvents.length > 0) {
            const eventsContainer = document.createElement('div');
            eventsContainer.className = 'day-events';
            
            dayEvents.forEach(evento => {
                const eventItem = document.createElement('div');
                eventItem.className = `event-item ${evento.tipo}`;
                eventItem.textContent = `${evento.hora} ${evento.titulo}`;
                eventItem.onclick = (e) => {
                    e.stopPropagation();
                    viewEvent(evento);
                };
                eventsContainer.appendChild(eventItem);
            });
            
            dayDiv.appendChild(eventsContainer);
        }
        
        // Adicionar listener para criar evento ao clicar no dia
        dayDiv.addEventListener('click', () => {
            openModal(actualDay);
        });
    }
    
    return dayDiv;
}

// Obter eventos de um dia específico
function getEventsForDay(day) {
    const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
    return eventos.filter(evento => evento.data === dateStr);
}

// Navegação do calendário
function previousMonth() {
    currentMonth--;
    if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
    }
    renderCalendar();
}

function nextMonth() {
    currentMonth++;
    if (currentMonth > 11) {
        currentMonth = 0;
        currentYear++;
    }
    renderCalendar();
}

function goToToday() {
    currentDate = new Date();
    currentMonth = currentDate.getMonth();
    currentYear = currentDate.getFullYear();
    renderCalendar();
}

// Modal functions
function openModal(day = null) {
    const modal = document.getElementById('modalOverlay');
    modal.classList.add('active');
    
    // Se um dia foi clicado, preencher a data
    if (day) {
        const dateStr = `${currentYear}-${String(currentMonth + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        // Preencher data de início no formulário de evento
        const eventoForm = document.getElementById('eventoForm');
        const dataInicio = eventoForm.querySelector('input[name="data_inicio"]');
        if (dataInicio) dataInicio.value = dateStr;
        
        // Preencher data de início no formulário de tarefa
        const tarefaForm = document.getElementById('tarefaForm');
        const dataInicioTarefa = tarefaForm.querySelector('input[name="data_inicio"]');
        if (dataInicioTarefa) dataInicioTarefa.value = dateStr;
    }
    
    // Definir horário padrão
    const now = new Date();
    const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
    
    const horaInicio = document.querySelector('#eventoForm input[name="hora_inicio"]');
    if (horaInicio && !horaInicio.value) horaInicio.value = timeStr;
    
    const horaInicioTarefa = document.querySelector('#tarefaForm input[name="hora_inicio"]');
    if (horaInicioTarefa && !horaInicioTarefa.value) horaInicioTarefa.value = timeStr;
}

function closeModal() {
    const modal = document.getElementById('modalOverlay');
    modal.classList.remove('active');
    
    // Limpar formulários
    document.getElementById('eventoForm').reset();
    document.getElementById('tarefaForm').reset();
}

function switchTab(tabName) {
    // Atualizar abas
    const tabs = document.querySelectorAll('.modal-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    event.target.classList.add('active');
    
    // Atualizar conteúdo
    const contents = document.querySelectorAll('.tab-content');
    contents.forEach(content => content.classList.remove('active'));
    
    if (tabName === 'evento') {
        document.getElementById('eventoTab').classList.add('active');
    } else {
        document.getElementById('tarefaTab').classList.add('active');
    }
}

function submitForm() {
    // Verificar qual tab está ativo
    const eventoTabActive = document.getElementById('eventoTab').classList.contains('active');
    
    if (eventoTabActive) {
        submitEvento();
    } else {
        submitTarefa();
    }
}

function submitEvento() {
    const form = document.getElementById('eventoForm');
    
    // Validar formulário
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Coletar dados do formulário
    const formData = new FormData(form);
    const evento = {
        id: Date.now(), // ID temporário
        titulo: formData.get('titulo'),
        tipo: formData.get('categoria'),
        data: formData.get('data_inicio'),
        hora: formData.get('hora_inicio'),
        dataFim: formData.get('data_fim'),
        horaFim: formData.get('hora_fim'),
        tipoEvento: formData.get('tipo_evento'),
        diaInteiro: formData.get('dia_inteiro') === 'on',
        convidados: formData.get('convidados'),
        notificacao: formData.get('notificacao'),
        nota: formData.get('nota')
    };
    
    // Adicionar evento ao array (temporário - depois vai salvar no banco)
    eventos.push(evento);
    
    // Atualizar calendário
    renderCalendar();
    
    // Fechar modal
    closeModal();
    
    // Mostrar mensagem de sucesso
    alert('Evento adicionado com sucesso!');
    
    // Aqui você vai adicionar o código para salvar no banco de dados via AJAX
    // saveEventoToDatabase(evento);
}

function submitTarefa() {
    const form = document.getElementById('tarefaForm');
    
    // Validar formulário
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Coletar dados do formulário
    const formData = new FormData(form);
    const tarefa = {
        id: Date.now(), // ID temporário
        titulo: formData.get('titulo'),
        tipo: 'tarefa', // Tipo especial para tarefas
        data: formData.get('data_inicio'),
        hora: formData.get('hora_inicio'),
        dataFim: formData.get('data_fim'),
        horaFim: formData.get('hora_fim'),
        lista: formData.get('lista'),
        diaTodo: formData.get('dia_todo') === 'on',
        descricao: formData.get('descricao')
    };
    
    // Adicionar tarefa ao array (temporário - depois vai salvar no banco)
    eventos.push(tarefa);
    
    // Atualizar calendário
    renderCalendar();
    
    // Fechar modal
    closeModal();
    
    // Mostrar mensagem de sucesso
    alert('Tarefa adicionada com sucesso!');
    
    // Aqui você vai adicionar o código para salvar no banco de dados via AJAX
    // saveTarefaToDatabase(tarefa);
}

function viewEvent(evento) {
    // Criar modal de visualização de evento
    alert(`Evento: ${evento.titulo}\nTipo: ${evento.tipo}\nData: ${evento.data}\nHora: ${evento.hora}`);
    
    // Você pode criar um modal melhor para visualizar/editar eventos
}

// Toggle de filtros
function toggleFilter(element) {
    element.classList.toggle('active');
    
    // Aqui você pode adicionar lógica para filtrar eventos
    // Por exemplo, esconder eventos online/offline
}

// Função para salvar evento no banco (implementar depois)
function saveEventoToDatabase(evento) {
    fetch('api/salvar_evento.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(evento)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Evento salvo com sucesso!');
        } else {
            console.error('Erro ao salvar evento:', data.error);
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
    });
}

// Função para salvar tarefa no banco (implementar depois)
function saveTarefaToDatabase(tarefa) {
    fetch('api/salvar_tarefa.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(tarefa)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Tarefa salva com sucesso!');
        } else {
            console.error('Erro ao salvar tarefa:', data.error);
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
    });
}

// Função para carregar eventos do banco (implementar depois)
function loadEventosFromDatabase() {
    fetch('api/carregar_eventos.php')
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            eventos = data.eventos;
            renderCalendar();
        } else {
            console.error('Erro ao carregar eventos:', data.error);
        }
    })
    .catch(error => {
        console.error('Erro na requisição:', error);
    });
}