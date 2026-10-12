@extends('layout')

@section('title', 'Novo Agendamento')
@section('page-title', 'Novo Agendamento')

@push('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
    <style>
        .search-section {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
        }

        .sitter-card {
            cursor: pointer;
            transition: all 0.3s ease;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .sitter-card:hover {
            border-color: var(--pet-primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .sitter-card.selected {
            border-color: var(--pet-primary);
            background-color: rgba(var(--pet-primary-rgb), 0.05);
        }

        .sitter-card.selected::before {
            content: '✓';
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            background-color: var(--pet-primary);
            color: white;
            border-radius: 50%;
            font-weight: bold;
            margin-right: 10px;
        }

        .location-badge {
            display: inline-block;
            background-color: #e9ecef;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
            color: #666;
            margin-top: 8px;
        }

        .availability-section {
            background-color: #f0f7ff;
            border-left: 4px solid var(--pet-primary);
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
        }

        .availability-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
            margin-top: 15px;
        }

        .calendar-day {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 12px;
            padding: 12px 10px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-align: center;
        }

        .calendar-day:hover {
            border-color: var(--pet-primary);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .calendar-day.selected {
            border-color: var(--pet-primary);
            background: rgba(var(--pet-primary-rgb), 0.08);
            box-shadow: inset 0 0 0 1px rgba(var(--pet-primary-rgb), 0.2);
        }

        .calendar-day small {
            display: block;
            color: #6c757d;
        }

        .calendar-day strong {
            color: var(--pet-primary);
            display: block;
            margin-top: 4px;
            font-size: 1.05rem;
        }

        .slot-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 18px;
        }

        .slot-button {
            background: white;
            border: 1px solid #d9dfe4;
            border-radius: 999px;
            color: #374151;
            font-weight: 600;
            min-width: 100px;
            padding: 8px 14px;
            transition: all 0.2s ease;
        }

        .slot-button:hover {
            border-color: var(--pet-primary);
            color: var(--pet-primary);
        }

        .slot-button.selected {
            background: var(--pet-primary);
            border-color: var(--pet-primary);
            color: white;
        }

        .availability-item {
            background: white;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            font-size: 0.9rem;
        }

        .availability-item strong {
            color: var(--pet-primary);
        }

        .no-availability {
            color: #dc3545;
            font-style: italic;
        }

        .loading-spinner {
            display: none;
            color: var(--pet-primary);
        }

        .loading-spinner.active {
            display: inline-block;
        }

        .search-results-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            background-color: white;
            margin-top: 10px;
        }

        .no-results {
            padding: 20px;
            text-align: center;
            color: #999;
        }

        .step-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            font-size: 0.9rem;
        }

        .step-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #e9ecef;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .step-circle.active {
            background-color: var(--pet-primary);
            color: white;
        }

        .step-circle.completed {
            background-color: #28a745;
            color: white;
        }
    </style>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('agendamentos.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Agendamentos</a>
    </li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1" style="color:#1C1917;">Criar Novo Agendamento</h5>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Siga os passos para criar um novo agendamento.</p>
            </div>
            <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

        {{-- Erros gerais --}}
        @if($errors->any())
            <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                <div>
                    <strong>Atenção!</strong> Corrija os erros abaixo:
                    <ul class="mb-0 mt-1 ps-3">
                        @foreach($errors->all() as $error)
                            <li style="font-size:0.88rem;">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('agendamentos.store') }}" method="POST" id="agendamentoForm">
            @csrf

            {{-- PASSO 1: Selecionar Pet do Cliente --}}
            <div class="card mb-4">
                <div class="card-header">
                    <div class="step-indicator">
                        <div class="step-circle active" id="step1-circle">1</div>
                        <div>
                            <i class="bi bi-paw-fill me-2 text-primary"></i>Selecionar Pet
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="id_pet" class="form-label">Pet <span class="text-danger">*</span></label>
                            <select id="id_pet" name="id_pet" onchange="onPetSelected()"
                                    class="form-select @error('id_pet') is-invalid @enderror" required>
                                <option value="">Selecione o pet...</option>
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->id }}" {{ old('id_pet') == $pet->id ? 'selected' : '' }}>
                                        {{ $pet->nome }} - {{ $pet->especie->tipo_pet ?? 'N/A' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- PASSO 2: Selecionar Serviço --}}
            <div class="card mb-4" id="servicoCard" style="display: none;">
                <div class="card-header">
                    <div class="step-indicator">
                        <div class="step-circle" id="step2-circle">2</div>
                        <div>
                            <i class="bi bi-briefcase-fill me-2 text-info"></i>Selecionar Serviço
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="id_servico" class="form-label">Serviço Desejado <span class="text-danger">*</span></label>
                            <select id="id_servico" name="id_servico" onchange="onServiceSelected()"
                                    class="form-select @error('id_servico') is-invalid @enderror" required>
                                <option value="">Selecione um serviço...</option>
                                @foreach($servicos as $servico)
                                    <option value="{{ $servico->id }}" data-duracao="{{ $servico->duracao_minutos ?? 60 }}" {{ old('id_servico') == $servico->id ? 'selected' : '' }}>
                                        {{ $servico->nome }} - R$ {{ number_format($servico->valor_servico, 2, ',', '.') }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_servico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sitter selecionado na rota anterior --}}
            <input type="hidden"
                   id="id_user_sitter"
                   name="id_user_sitter"
                   value="{{ old('id_user_sitter', request('sitter_id', $selectedSitter?->id)) }}"
                   data-sitter-name="{{ old('sitter_name', $selectedSitter?->name ?? '') }}">

            {{-- PASSO 4: Verificar Disponibilidade do Sitter (F_F03) --}}
            <div class="card mb-4" id="availabilityCard" style="display: none;">
                <div class="card-header">
                    <div class="step-indicator">
                        <div class="step-circle" id="step4-circle">4</div>
                        <div>
                            <i class="bi bi-calendar-check me-2 text-warning"></i>Verificar Disponibilidade
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="availability-section">
                        <h6 class="mb-3">Selecione uma data e um horário disponível:</h6>
                        <div class="availability-grid" id="availabilityGrid"></div>
                        <div id="slotContainer" class="slot-grid" style="display: none;"></div>
                        <div id="noAvailability" class="no-availability" style="display: none;">
                            Este sitter não possui disponibilidade cadastrada.
                        </div>
                    </div>
                </div>
            </div>

            {{-- PASSO 5: Selecionar Data e Horário --}}
            <div class="card mb-4" id="dateTimeCard" style="display: none;">
                <div class="card-header">
                    <div class="step-indicator">
                        <div class="step-circle" id="step5-circle">5</div>
                        <div>
                            <i class="bi bi-calendar-event me-2 text-danger"></i>Selecionar Data e Horário
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="data_inicio" class="form-label">Data e Hora de Início <span class="text-danger">*</span></label>
                            <input type="datetime-local"
                                   id="data_inicio"
                                   name="data_inicio"
                                   class="form-control @error('data_inicio') is-invalid @enderror"
                                   value="{{ old('data_inicio') }}"
                                   min="{{ date('Y-m-d\TH:i') }}"
                                   required>
                            @error('data_inicio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="data_fim" class="form-label">Data e Hora de Término <span class="text-danger">*</span></label>
                            <input type="datetime-local"
                                   id="data_fim"
                                   name="data_fim"
                                   class="form-control @error('data_fim') is-invalid @enderror"
                                   value="{{ old('data_fim') }}"
                                   required>
                            @error('data_fim')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="observacoes" class="form-label">Observações (Opcional)</label>
                            <textarea id="observacoes"
                                      name="observacoes"
                                      class="form-control @error('observacoes') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Adicione observações específicas sobre o serviço...">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- PASSO 6: Confirmar Agendamento --}}
            <div class="card mb-4" id="confirmCard" style="display: none;">
                <div class="card-header">
                    <div class="step-indicator">
                        <div class="step-circle" id="step6-circle">6</div>
                        <div>
                            <i class="bi bi-check-circle me-2 text-success"></i>Confirmar Agendamento
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-info-circle me-2"></i>
                        <strong>Resumo:</strong> Verifique os dados abaixo antes de confirmar o agendamento.
                    </div>
                    <div class="row g-3" id="confirmSummary"></div>
                </div>
            </div>

            {{-- Botões de ação --}}
            <div class="d-flex gap-2" id="submitButtons" style="display: none;">
                <button type="submit" class="btn btn-success btn-sm px-4 py-2">
                    <i class="bi bi-check-lg me-1"></i> Confirmar Agendamento
                </button>
                <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-secondary btn-sm px-4 py-2">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </a>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sitterInput = document.getElementById('id_user_sitter');
    const availabilityCard = document.getElementById('availabilityCard');
    let selectedServiceDuration = 60;
    const dateTimeCard = document.getElementById('dateTimeCard');
    const confirmCard = document.getElementById('confirmCard');
    const submitButtons = document.getElementById('submitButtons');
    const availabilityGrid = document.getElementById('availabilityGrid');
    const noAvailability = document.getElementById('noAvailability');
    const servicoCard = document.getElementById('servicoCard');

    window.onPetSelected = function() {
        const petSelect = document.getElementById('id_pet');
        if (petSelect.value) {
            document.getElementById('step1-circle').classList.add('completed');
            document.getElementById('step2-circle').classList.add('active');
            servicoCard.style.display = 'block';
            setTimeout(() => servicoCard.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
        }
    };

    window.onServiceSelected = function() {
        const serviceSelect = document.getElementById('id_servico');
        const serviceId = serviceSelect.value;
        const preferredSitterId = new URLSearchParams(window.location.search).get('sitter_id');

        if (!serviceId) {
            return;
        }

        const selectedOption = serviceSelect.selectedOptions[0];
        selectedServiceDuration = Number(selectedOption?.dataset.duracao || 60);
        document.getElementById('step2-circle').classList.add('completed');

        if (!preferredSitterId) {
            alert('Selecione um sitter na página dos profissionais para continuar o agendamento.');
            return;
        }

        sitterInput.value = preferredSitterId;
        const step3 = document.getElementById('step3-circle');
        if (step3) {
            step3.classList.add('completed');
        }
        document.getElementById('step4-circle').classList.add('active');
        availabilityCard.style.display = 'block';
        loadSitterAvailability(preferredSitterId);

        setTimeout(() => {
            availabilityCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 150);
    };

    const preferredSitterId = new URLSearchParams(window.location.search).get('sitter_id');
    if (preferredSitterId) {
        sitterInput.value = preferredSitterId;
        const step3 = document.getElementById('step3-circle');
        if (step3) {
            step3.classList.add('completed');
        }
        document.getElementById('step4-circle').classList.add('active');
        availabilityCard.style.display = 'block';
        loadSitterAvailability(preferredSitterId);
        setTimeout(() => {
            availabilityCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 150);
    }

    // PASSO 5: Carregar disponibilidade do sitter (F_F03)
    function loadSitterAvailability(sitterId) {
        fetch(`{{ route('agendamentos.getSitterAvailability') }}?sitter_id=${sitterId}`)
            .then(response => response.json())
            .then(data => {
                availabilityGrid.innerHTML = '';
                const slotContainer = document.getElementById('slotContainer');
                slotContainer.innerHTML = '';
                slotContainer.style.display = 'none';

                if (data.disponibilidades.length === 0) {
                    noAvailability.style.display = 'block';
                    dateTimeCard.style.display = 'none';
                    confirmCard.style.display = 'none';
                    submitButtons.style.display = 'none';
                    return;
                }

                noAvailability.style.display = 'none';

                const days = getNextDays(7);
                const mapByDayName = {};

                data.disponibilidades.forEach(disp => {
                    const key = normalizeDayKey(disp.dia_semana);
                    mapByDayName[key] = mapByDayName[key] || [];
                    mapByDayName[key].push(disp);
                });

                days.forEach((day) => {
                    const label = day.toLocaleDateString('pt-BR', { weekday: 'short', day: '2-digit', month: '2-digit' });
                    const dayKey = normalizeDayKey(day.toLocaleDateString('pt-BR', { weekday: 'long' }));
                    const hasSlots = mapByDayName[dayKey] || [];

                    const item = document.createElement('button');
                    item.type = 'button';
                    item.className = 'calendar-day';
                    item.innerHTML = `<small>${label.split(',')[0]}</small><strong>${day.getDate()}</strong><small>${day.toLocaleDateString('pt-BR', { month: 'short' })}</small>`;
                    item.disabled = !hasSlots.length;
                    item.style.opacity = hasSlots.length ? '1' : '0.5';

                    item.addEventListener('click', () => {
                        document.querySelectorAll('.calendar-day').forEach(btn => btn.classList.remove('selected'));
                        item.classList.add('selected');
                        renderTimeSlots(day, hasSlots);
                    });

                    availabilityGrid.appendChild(item);
                });

                document.getElementById('step4-circle').classList.remove('active');
                document.getElementById('step4-circle').classList.add('completed');
                document.getElementById('step5-circle').classList.add('active');
                dateTimeCard.style.display = 'block';

                setTimeout(() => {
                    dateTimeCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao carregar disponibilidade');
            });
    }

    function normalizeDayKey(value) {
        return String(value || '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');
    }

    function getNextDays(qtd) {
        const result = [];
        const hoje = new Date();
        for (let i = 0; i < qtd; i++) {
            const d = new Date(hoje);
            d.setDate(hoje.getDate() + i);
            result.push(d);
        }
        return result;
    }

    function renderTimeSlots(selectedDate, disponibilidades) {
        const slotContainer = document.getElementById('slotContainer');
        slotContainer.innerHTML = '';
        slotContainer.style.display = 'flex';

        const slots = [];
        disponibilidades.forEach(disp => {
            const inicio = toMinutes(disp.hora_inicio);
            const fim = toMinutes(disp.hora_fim);
            for (let current = inicio; current + selectedServiceDuration <= fim; current += selectedServiceDuration) {
                const start = minutesToTime(current);
                const end = minutesToTime(current + selectedServiceDuration);
                slots.push({
                    label: `${start} - ${end}`,
                    start,
                    end
                });
            }
        });

        if (!slots.length) {
            slotContainer.innerHTML = '<div class="text-muted">Nenhum horário disponível neste dia.</div>';
            return;
        }

        slots.forEach(slot => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'slot-button';
            btn.textContent = slot.label;
            btn.addEventListener('click', () => {
                document.querySelectorAll('.slot-button').forEach(el => el.classList.remove('selected'));
                btn.classList.add('selected');

                const dateValue = new Date(selectedDate.getTime() - selectedDate.getTimezoneOffset() * 60000)
                    .toISOString()
                    .slice(0, 10);
                const inicio = `${dateValue}T${slot.start}`;
                const fim = `${dateValue}T${slot.end}`;
                document.getElementById('data_inicio').value = inicio;
                document.getElementById('data_fim').value = fim;
                showConfirmation();
            });
            slotContainer.appendChild(btn);
        });
    }

    function toMinutes(time) {
        const [h, m] = time.split(':').map(Number);
        return h * 60 + m;
    }

    function minutesToTime(totalMinutes) {
        const hours = String(Math.floor(totalMinutes / 60)).padStart(2, '0');
        const minutes = String(totalMinutes % 60).padStart(2, '0');
        return `${hours}:${minutes}`;
    }

    // Quando data/hora são preenchidas, mostrar confirmação
    document.getElementById('data_inicio').addEventListener('change', showConfirmation);
    document.getElementById('data_fim').addEventListener('change', showConfirmation);

    function showConfirmation() {
        const dataInicio = document.getElementById('data_inicio').value;
        const dataFim = document.getElementById('data_fim').value;

        if (dataInicio && dataFim) {
            document.getElementById('step5-circle').classList.remove('active');
            document.getElementById('step5-circle').classList.add('completed');
            document.getElementById('step6-circle').classList.add('active');
            
            confirmCard.style.display = 'block';
            submitButtons.style.display = 'flex';
            
            // Gerar resumo
            generateSummary();

            setTimeout(() => {
                confirmCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
        }
    }

    function generateSummary() {
        const petSelect = document.getElementById('id_pet');
        const petName = petSelect.options[petSelect.selectedIndex]?.text || 'Nenhum pet selecionado';
        const serviceSelect = document.getElementById('id_servico');
        const dataInicio = document.getElementById('data_inicio').value;
        const dataFim = document.getElementById('data_fim').value;
        const sitterInput = document.getElementById('id_user_sitter');
        const sitterName = sitterInput?.dataset?.sitterName || document.querySelector('.sitter-card.selected strong')?.textContent || 'N/A';

        const summary = `
            <div class="col-md-6">
                <div class="mb-2">
                    <small style="color:#78716C; font-size:0.85rem;">Pet</small>
                    <div style="color:#1C1917; font-weight:600;">${petName}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <small style="color:#78716C; font-size:0.85rem;">Serviço</small>
                    <div style="color:#1C1917; font-weight:600;">${serviceSelect.options[serviceSelect.selectedIndex].text}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <small style="color:#78716C; font-size:0.85rem;">Pet Sitter</small>
                    <div style="color:#1C1917; font-weight:600;">${sitterName}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <small style="color:#78716C; font-size:0.85rem;">Data de Início</small>
                    <div style="color:#1C1917; font-weight:600;">${new Date(dataInicio).toLocaleString('pt-BR')}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <small style="color:#78716C; font-size:0.85rem;">Data de Término</small>
                    <div style="color:#1C1917; font-weight:600;">${new Date(dataFim).toLocaleString('pt-BR')}</div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-2">
                    <small style="color:#78716C; font-size:0.85rem;">Observações</small>
                    <div style="color:#1C1917; font-weight:600;">${document.getElementById('observacoes').value || 'Nenhuma'}</div>
                </div>
            </div>
        `;

        document.getElementById('confirmSummary').innerHTML = summary;
    }

});
</script>
@endpush

@endsection
