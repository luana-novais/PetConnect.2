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
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 10px;
            margin-top: 15px;
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
                                <option value="">Selecione um pet...</option>
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
                                    <option value="{{ $servico->id }}" {{ old('id_servico') == $servico->id ? 'selected' : '' }}>
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

            {{-- PASSO 3: Buscar Sitters Disponíveis na Região --}}
            <div class="card mb-4" id="searchSitterCard" style="display: none;">
                <div class="card-header">
                    <div class="step-indicator">
                        <div class="step-circle" id="step3-circle">3</div>
                        <div>
                            <i class="bi bi-geo-alt me-2 text-success"></i>Buscar Pet Sitters Disponíveis
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="search-section">
                        <p class="text-muted mb-3">Encontre pet sitters disponíveis na sua região:</p>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="search_cidade" class="form-label">Cidade</label>
                                <input type="text" id="search_cidade" class="form-control" placeholder="Digite a cidade...">
                            </div>
                            <div class="col-md-6">
                                <label for="search_estado" class="form-label">Estado</label>
                                <input type="text" id="search_estado" class="form-control" placeholder="Digite o estado (ex: SP)...">
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary mt-3" id="btnSearchSitters">
                            <i class="bi bi-search me-2"></i>Buscar Sitters
                            <span class="loading-spinner" id="searchSpinner">
                                <i class="bi bi-hourglass-split ms-2"></i>
                            </span>
                        </button>
                    </div>

                    {{-- Resultados de busca --}}
                    <div class="search-results-container" id="searchResults" style="display: none;">
                        <div id="sittersList"></div>
                    </div>

                    {{-- Mensagem de nenhum resultado --}}
                    <div id="noResults" class="no-results" style="display: none;">
                        Nenhum sitter encontrado. Tente outra localização.
                    </div>

                    {{-- Sitter selecionado (hidden select) --}}
                    <input type="hidden" id="id_user_sitter" name="id_user_sitter" value="{{ old('id_user_sitter') }}">
                </div>
            </div>

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
                        <h6 class="mb-3">Agenda semanal do sitter:</h6>
                        <div class="availability-grid" id="availabilityGrid"></div>
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
                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-check-lg me-1"></i> Confirmar Agendamento
                </button>
                <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </a>
            </div>

        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnSearch = document.getElementById('btnSearchSitters');
    const searchCidade = document.getElementById('search_cidade');
    const searchEstado = document.getElementById('search_estado');
    const sittersList = document.getElementById('sittersList');
    const searchResults = document.getElementById('searchResults');
    const noResults = document.getElementById('noResults');
    const searchSpinner = document.getElementById('searchSpinner');
    const sitterInput = document.getElementById('id_user_sitter');
    const availabilityCard = document.getElementById('availabilityCard');
    const dateTimeCard = document.getElementById('dateTimeCard');
    const confirmCard = document.getElementById('confirmCard');
    const submitButtons = document.getElementById('submitButtons');
    const availabilityGrid = document.getElementById('availabilityGrid');
    const noAvailability = document.getElementById('noAvailability');
    const servicoCard = document.getElementById('servicoCard');
    const searchSitterCard = document.getElementById('searchSitterCard');

    // PASSO 1: Pet selecionado
    window.onPetSelected = function() {
        const petId = document.getElementById('id_pet').value;
        if (petId) {
            document.getElementById('step1-circle').classList.add('completed');
            document.getElementById('step2-circle').classList.add('active');
            servicoCard.style.display = 'block';
            setTimeout(() => servicoCard.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
        }
    };

    // PASSO 2: Serviço selecionado
    window.onServiceSelected = function() {
        const serviceId = document.getElementById('id_servico').value;
        if (serviceId) {
            document.getElementById('step2-circle').classList.add('completed');
            document.getElementById('step3-circle').classList.add('active');
            searchSitterCard.style.display = 'block';
            setTimeout(() => searchSitterCard.scrollIntoView({ behavior: 'smooth', block: 'start' }), 100);
        }
    };

    // PASSO 3: Buscar sitters
    btnSearch.addEventListener('click', function() {
        const cidade = searchCidade.value;
        const estado = searchEstado.value;

        if (!cidade && !estado) {
            alert('Digite pelo menos a cidade ou estado para buscar');
            return;
        }

        searchSpinner.classList.add('active');
        btnSearch.disabled = true;

        fetch(`{{ route('agendamentos.searchSitters') }}?cidade=${cidade}&estado=${estado}`)
            .then(response => response.json())
            .then(data => {
                sittersList.innerHTML = '';

                if (data.length === 0) {
                    searchResults.style.display = 'none';
                    noResults.style.display = 'block';
                } else {
                    noResults.style.display = 'none';
                    searchResults.style.display = 'block';

                    data.forEach(sitter => {
                        const card = document.createElement('div');
                        card.className = 'sitter-card';
                        card.onclick = () => selectSitter(sitter);
                        card.innerHTML = `
                            <div>
                                <strong>${sitter.name}</strong>
                                <div class="location-badge">
                                    📍 ${sitter.cidade}, ${sitter.estado}
                                </div>
                                <small class="text-muted d-block mt-2">${sitter.endereco}</small>
                            </div>
                        `;
                        sittersList.appendChild(card);
                    });
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao buscar sitters');
            })
            .finally(() => {
                searchSpinner.classList.remove('active');
                btnSearch.disabled = false;
            });
    });

    // PASSO 4: Selecionar sitter
    function selectSitter(sitter) {
        // Atualizar hidden input
        sitterInput.value = sitter.id;

        // Atualizar cards visuais
        document.querySelectorAll('.sitter-card').forEach(card => {
            card.classList.remove('selected');
        });
        event.currentTarget.classList.add('selected');

        // Atualizar step indicators
        document.getElementById('step3-circle').classList.add('completed');
        document.getElementById('step4-circle').classList.add('active');

        // Mostrar card de disponibilidade
        availabilityCard.style.display = 'block';
        loadSitterAvailability(sitter.id);

        // Scroll para disponibilidade
        setTimeout(() => {
            availabilityCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }

    // PASSO 5: Carregar disponibilidade do sitter (F_F03)
    function loadSitterAvailability(sitterId) {
        fetch(`{{ route('agendamentos.getSitterAvailability') }}?sitter_id=${sitterId}`)
            .then(response => response.json())
            .then(data => {
                availabilityGrid.innerHTML = '';

                if (data.disponibilidades.length === 0) {
                    noAvailability.style.display = 'block';
                    dateTimeCard.style.display = 'none';
                    confirmCard.style.display = 'none';
                    submitButtons.style.display = 'none';
                } else {
                    noAvailability.style.display = 'none';

                    data.disponibilidades.forEach(disp => {
                        const item = document.createElement('div');
                        item.className = 'availability-item';
                        item.innerHTML = `
                            <strong>${disp.dia_semana}</strong><br>
                            ${disp.hora_inicio} às ${disp.hora_fim}
                        `;
                        availabilityGrid.appendChild(item);
                    });

                    // Mostrar próximos passos
                    document.getElementById('step4-circle').classList.remove('active');
                    document.getElementById('step4-circle').classList.add('completed');
                    document.getElementById('step5-circle').classList.add('active');
                    
                    dateTimeCard.style.display = 'block';
                    
                    // Scroll para o próximo passo
                    setTimeout(() => {
                        dateTimeCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao carregar disponibilidade');
            });
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
        const serviceSelect = document.getElementById('id_servico');
        const dataInicio = document.getElementById('data_inicio').value;
        const dataFim = document.getElementById('data_fim').value;
        const sitterName = document.querySelector('.sitter-card.selected strong')?.textContent || 'N/A';

        const summary = `
            <div class="col-md-6">
                <div class="mb-2">
                    <small style="color:#78716C; font-size:0.85rem;">Pet</small>
                    <div style="color:#1C1917; font-weight:600;">${petSelect.options[petSelect.selectedIndex].text}</div>
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

    // Permitir busca com Enter
    searchCidade.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            btnSearch.click();
        }
    });

    searchEstado.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            btnSearch.click();
        }
    });
});
</script>
@endpush

@endsection
