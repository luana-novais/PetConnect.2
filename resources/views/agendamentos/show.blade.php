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
        <a href="{{ route('agendamentos.edit', $agendamento->id) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
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
                            <div style="font-size:0.85rem; color:#78716C;">R$ {{ number_format($agendamento->servico->preco, 2, ',', '.') }}</div>
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

        {{-- Ações --}}
        <div class="card mt-3">
            <div class="card-body p-2">
                <form action="{{ route('agendamentos.destroy', $agendamento->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100 btn-sm" onclick="return confirm('Tem certeza que deseja deletar este agendamento?')">
                        <i class="bi bi-trash me-1"></i> Deletar Agendamento
                    </button>
                </form>
            </div>
        </div>
    </div>

</div>

@endsection
