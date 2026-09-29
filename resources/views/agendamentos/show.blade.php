@extends('layout')

@section('title', 'Agendamento #' . $agendamento->id)
@section('page-title', 'Detalhes do Agendamento')

@push('styles')
    <link href="{{ asset('css/show.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('agendamentos.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Agendamentos</a>
    </li>
    <li class="breadcrumb-item active">#{{ $agendamento->id }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Agendamento #{{ $agendamento->id }}</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Informações do agendamento do pet {{ $agendamento->pet->nome }}.</p>
    </div>
    <div class="d-flex gap-2">
        @if($agendamento->status !== 'concluido')
            <a href="{{ route('agendamentos.edit', $agendamento->id) }}" class="btn btn-primary btn-sm">
                <i class="bi bi-pencil me-1"></i> Editar
            </a>
        @endif
        <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Card principal --}}
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-calendar3 me-2 text-primary"></i>Informações do Agendamento
            </div>
            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Cliente</label>
                            <div style="color:#1C1917; font-weight:600;">{{ $agendamento->cliente->name }}</div>
                            <div style="font-size:0.85rem; color:#78716C;">{{ $agendamento->cliente->email }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Pet</label>
                            <div style="color:#1C1917; font-weight:600;">{{ $agendamento->pet->nome }}</div>
                            <div style="font-size:0.85rem; color:#78716C;">{{ $agendamento->pet->especie->tipo_pet }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Sitter</label>
                            <div style="color:#1C1917; font-weight:600;">{{ $agendamento->sitter->name }}</div>
                            <div style="font-size:0.85rem; color:#78716C;">{{ $agendamento->sitter->email }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Serviço</label>
                            <div style="color:#1C1917; font-weight:600;">{{ $agendamento->servico->nome }}</div>
                            <div style="font-size:0.85rem; color:#78716C;">R$ {{ number_format($agendamento->servico->valor_servico, 2, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Data e Hora de Início</label>
                            <div style="color:#1C1917; font-weight:600;">{{ \Carbon\Carbon::parse($agendamento->data_inicio)->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Data e Hora de Término</label>
                            <div style="color:#1C1917; font-weight:600;">{{ \Carbon\Carbon::parse($agendamento->data_fim)->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Status</label>
                            <div>
                                @if($agendamento->status === 'pendente')
                                    <span class="badge bg-warning">Pendente</span>
                                @elseif($agendamento->status === 'confirmado')
                                    <span class="badge bg-success">Confirmado</span>
                                @elseif($agendamento->status === 'cancelado')
                                    <span class="badge bg-danger">Cancelado</span>
                                @else
                                    <span class="badge bg-info">Concluído</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($agendamento->observacoes)
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label" style="color:#78716C; font-size:0.85rem;">Observações</label>
                                <div style="color:#1C1917;">{{ $agendamento->observacoes }}</div>
                            </div>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- Resumo lateral --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-info-circle me-2 text-info"></i>Resumo
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div style="font-size:0.85rem; color:#78716C;">Duração</div>
                    <div style="color:#1C1917; font-weight:600;">
                        {{ \Carbon\Carbon::parse($agendamento->data_inicio)->diffInHours(\Carbon\Carbon::parse($agendamento->data_fim)) }} horas
                    </div>
                </div>

                <div class="mb-3 pb-3 border-bottom">
                    <div style="font-size:0.85rem; color:#78716C;">Criado em</div>
                    <div style="color:#1C1917; font-weight:600;">{{ $agendamento->created_at->format('d/m/Y H:i') }}</div>
                </div>

                <div>
                    <div style="font-size:0.85rem; color:#78716C;">Última atualização</div>
                    <div style="color:#1C1917; font-weight:600;">{{ $agendamento->updated_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header">
                <i class="bi bi-wallet2 me-2 text-primary"></i>Pagamento
            </div>
            <div class="card-body">
                @if($agendamento->pagamento)
                    <p class="mb-2">
                        <strong>Status:</strong>
                        @if($agendamento->pagamento->status_pagamento === 'pago')
                            <span class="badge bg-success">Pago</span>
                        @elseif($agendamento->pagamento->status_pagamento === 'pendente')
                            <span class="badge bg-warning">Pendente</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($agendamento->pagamento->status_pagamento) }}</span>
                        @endif
                    </p>
                    <p class="mb-2"><strong>Método:</strong> {{ $agendamento->pagamento->metodo_pagamento ?? '—' }}</p>
                    <p class="mb-0"><strong>Valor:</strong> R$ {{ number_format($agendamento->pagamento->valor ?? $agendamento->servico->valor_servico, 2, ',', '.') }}</p>
                @else
                    <p class="mb-0 text-muted">Ainda não há pagamento registrado para este agendamento.</p>
                @endif
            </div>
        </div>

        {{-- Ações --}}
        <div class="card mt-3">
            <div class="card-header">
                <i class="bi bi-lightning-fill me-2 text-warning"></i>Ações
            </div>
            <div class="card-body p-2 d-flex flex-column gap-2">
                {{-- Confirmar Agendamento para sitter --}}
                @if(auth()->user()->role === 'PRO' && $agendamento->status === 'pendente' && $agendamento->id_user_sitter === auth()->id())
                    <form action="{{ route('agendamentos.confirm', $agendamento->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Confirmar este agendamento?')">
                            <i class="bi bi-check-circle me-1"></i> Confirmar Agendamento
                        </button>
                    </form>
                @endif

                @if(auth()->user()->role === 'PRO' && $agendamento->status === 'confirmado' && $agendamento->id_user_sitter === auth()->id() && $agendamento->data_fim && !\Carbon\Carbon::parse($agendamento->data_fim)->isFuture())
                    <form action="{{ route('agendamentos.complete', $agendamento->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Marcar este atendimento como concluído?')">
                            <i class="bi bi-check2-all me-1"></i> Concluir Atendimento
                        </button>
                    </form>
                @endif

                {{-- Cancelar Agendamento (F_F04) --}}
                @if($agendamento->status !== 'cancelado' && $agendamento->status !== 'concluido')
                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar Agendamento
                    </button>
                @endif

                {{-- Avaliar Usuário (F_F05) --}}
                @if($agendamento->status === 'concluido' && auth()->user()->role === 'CLI' && $agendamento->id_user_cliente === auth()->id() && $agendamento->avaliacoes->isEmpty())
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#avaliacaoModal">
                        <i class="bi bi-star-fill me-1"></i> Deixar Avaliação
                    </button>
                @endif

                @if($agendamento->status === 'concluido' && $agendamento->avaliacoes->isNotEmpty())
                    <div class="alert alert-success mb-0">
                        <i class="bi bi-check-circle me-1"></i> Avaliação enviada.
                    </div>
                @endif

                {{-- Botão Editar --}}
                @if($agendamento->status === 'pendente' && auth()->user()->role !== 'PRO')
                    <a href="{{ route('agendamentos.edit', $agendamento->id) }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                @endif
            </div>
        </div>
    </div>

</div>

@if(auth()->user()->role === 'PRO' && $agendamento->id_user_sitter === auth()->id() && in_array($agendamento->status, ['confirmado', 'concluido']))
    <div class="card mt-4">
        <div class="card-header">
            <i class="bi bi-camera me-2 text-warning"></i>Fotos do atendimento
        </div>
        <div class="card-body">
            <form action="{{ route('agendamentos.fotos', $agendamento->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="fotos-atendimento" class="form-label">Adicionar fotos tiradas durante o atendimento</label>
                <input type="file" id="fotos-atendimento" name="fotos[]" multiple
                       accept="image/jpeg,image/png,image/webp"
                       class="form-control @error('fotos') is-invalid @enderror @error('fotos.*') is-invalid @enderror">
                <div class="form-text">JPG, PNG ou WEBP, até 5 MB cada. Você pode selecionar várias fotos.</div>
                @error('fotos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @error('fotos.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <button type="submit" class="btn btn-primary btn-sm mt-3">
                    <i class="bi bi-upload me-1"></i> Enviar fotos
                </button>
            </form>
        </div>
    </div>
@endif

@if($agendamento->fotos->isNotEmpty())
    <div class="card mt-4">
        <div class="card-header">
            <i class="bi bi-images me-2 text-warning"></i>Fotos do atendimento
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($agendamento->fotos as $foto)
                    <div class="col-6 col-md-3">
                        <img src="{{ asset('storage/' . $foto->url_foto) }}"
                             alt="{{ $foto->nome_arquivo }}"
                             class="img-fluid rounded w-100"
                             style="aspect-ratio: 1; object-fit: cover;">
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif

@if($agendamento->avaliacoes->isNotEmpty())
    @php $avaliacao = $agendamento->avaliacoes->first(); @endphp
    <div class="card mt-4">
        <div class="card-header">
            <i class="bi bi-star-fill me-2 text-warning"></i>Avaliação do atendimento
        </div>
        <div class="card-body">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <div class="fw-semibold">{{ $avaliacao->user->name ?? $agendamento->cliente->name }}</div>
                    <div class="text-muted" style="font-size:.82rem;">Avaliação enviada pelo cliente</div>
                </div>
                <div class="text-warning" aria-label="Nota {{ (int) $avaliacao->nota }} de 5">
                    @for($estrela = 1; $estrela <= 5; $estrela++)
                        <i class="bi {{ $estrela <= (int) $avaliacao->nota ? 'bi-star-fill' : 'bi-star' }}"></i>
                    @endfor
                    <span class="text-dark ms-1 fw-semibold">{{ (int) $avaliacao->nota }}/5</span>
                </div>
            </div>
            @if($avaliacao->comentarios)
                <p class="mb-0 mt-3" style="color:#44403C;line-height:1.7;">{{ $avaliacao->comentarios }}</p>
            @endif
        </div>
    </div>
@endif

{{-- MODAL: Cancelar Agendamento (F_F04) --}}
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Cancelar Agendamento
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-3">
                    <i class="bi bi-info-circle me-2"></i>
                    Tem certeza que deseja cancelar o agendamento <strong>#{{ $agendamento->id }}</strong>?
                </p>
                <div class="alert alert-warning mb-0">
                    <small>A data e horário serão liberados novamente para o pet sitter.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Não, manter agendamento</button>
                <form action="{{ route('agendamentos.cancel', $agendamento->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Sim, cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL: Avaliar Usuário (F_F05) --}}
<div class="modal fade" id="avaliacaoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="bi bi-star-fill me-2"></i>Deixar Avaliação
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('avaliacoes.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Avaliação para: <strong>{{ $agendamento->sitter->name }}</strong></label>
                        <input type="hidden" name="id_agendamento" value="{{ $agendamento->id }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nota (1 a 5 estrelas) <span class="text-danger">*</span></label>
                        <div class="rating" id="rating">
                            <i class="bi bi-star-fill" style="font-size:2rem; cursor:pointer; color:#ccc;" data-value="1"></i>
                            <i class="bi bi-star-fill" style="font-size:2rem; cursor:pointer; color:#ccc; margin-left:10px;" data-value="2"></i>
                            <i class="bi bi-star-fill" style="font-size:2rem; cursor:pointer; color:#ccc; margin-left:10px;" data-value="3"></i>
                            <i class="bi bi-star-fill" style="font-size:2rem; cursor:pointer; color:#ccc; margin-left:10px;" data-value="4"></i>
                            <i class="bi bi-star-fill" style="font-size:2rem; cursor:pointer; color:#ccc; margin-left:10px;" data-value="5"></i>
                        </div>
                        <input type="hidden" id="nota" name="nota" value="0" required>
                        <small class="text-muted d-block mt-2" id="notaTexto">Clique nas estrelas para avaliar</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Comentários (Opcional)</label>
                        <textarea name="comentarios" class="form-control" rows="4" placeholder="Compartilhe sua experiência..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-lg me-1"></i> Enviar Avaliação
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .rating i {
        transition: all 0.2s ease;
    }

    .rating i:hover,
    .rating i.active {
        color: #ffc107 !important;
    }
</style>

<script>
    // Sistema de rating interativo
    const stars = document.querySelectorAll('#rating i');
    const notaInput = document.getElementById('nota');
    const notaTexto = document.getElementById('notaTexto');

    const textos = {
        1: 'Muito insatisfeito',
        2: 'Insatisfeito',
        3: 'Neutro',
        4: 'Satisfeito',
        5: 'Muito satisfeito'
    };

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            notaInput.value = value;
            notaTexto.textContent = textos[value];

            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.classList.add('active');
                } else {
                    s.classList.remove('active');
                }
            });
        });

        star.addEventListener('mouseenter', function() {
            const value = this.getAttribute('data-value');
            stars.forEach(s => {
                if (s.getAttribute('data-value') <= value) {
                    s.style.color = '#ffc107';
                } else {
                    s.style.color = '#ccc';
                }
            });
        });
    });

    document.getElementById('rating').addEventListener('mouseleave', function() {
        const selectedNote = notaInput.value;
        stars.forEach(s => {
            if (selectedNote && s.getAttribute('data-value') <= selectedNote) {
                s.classList.add('active');
                s.style.color = '#ffc107';
            } else {
                s.classList.remove('active');
                s.style.color = '#ccc';
            }
        });
    });
</script>

@endsection
