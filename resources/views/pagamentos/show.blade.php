@extends('layout')

@section('title', 'Pagamento #' . $pagamento->id)
@section('page-title', 'Detalhes do Pagamento')

@push('styles')
    <link href="{{ asset('css/show.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pagamentos.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Pagamentos</a>
    </li>
    <li class="breadcrumb-item active">#{{ $pagamento->id }}</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Pagamento #{{ $pagamento->id }}</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Informações do pagamento do agendamento #{{ $pagamento->agendamento->id }}.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Card principal --}}
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <i class="bi bi-credit-card me-2 text-primary"></i>Informações do Pagamento
            </div>
            <div class="card-body">
                <div class="row g-4">

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Agendamento</label>
                            <div style="color:#1C1917; font-weight:600;">#{{ $pagamento->agendamento->id }}</div>
                            <div style="font-size:0.85rem; color:#78716C;">
                                {{ $pagamento->agendamento->servico->nome }}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Cliente</label>
                            <div style="color:#1C1917; font-weight:600;">{{ $pagamento->agendamento->cliente->name }}</div>
                            <div style="font-size:0.85rem; color:#78716C;">{{ $pagamento->agendamento->cliente->email }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Pet</label>
                            <div style="color:#1C1917; font-weight:600;">{{ $pagamento->agendamento->pet->nome }}</div>
                            <div style="font-size:0.85rem; color:#78716C;">{{ $pagamento->agendamento->pet->especie->tipo_pet }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Valor Total</label>
                            <div style="color:#1C1917; font-weight:600; font-size:1.3rem;">R$ {{ number_format($pagamento->total_servico, 2, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Método de Pagamento</label>
                            <div>
                                @if($pagamento->metodo_pagamento === 'pix')
                                    <span class="badge bg-info" style="font-size:0.9rem; padding: 0.5rem 0.75rem;">PIX</span>
                                @elseif($pagamento->metodo_pagamento === 'cartao')
                                    <span class="badge bg-primary" style="font-size:0.9rem; padding: 0.5rem 0.75rem;">Cartão de Crédito</span>
                                @elseif($pagamento->metodo_pagamento === 'dinheiro')
                                    <span class="badge bg-success" style="font-size:0.9rem; padding: 0.5rem 0.75rem;">Dinheiro</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size:0.9rem; padding: 0.5rem 0.75rem;">{{ $pagamento->metodo_pagamento }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Status</label>
                            <div>
                                @if($pagamento->status_pagamento === 'pendente')
                                    <span class="badge bg-warning" style="font-size:0.9rem; padding: 0.5rem 0.75rem;">Pendente</span>
                                @elseif($pagamento->status_pagamento === 'pago')
                                    <span class="badge bg-success" style="font-size:0.9rem; padding: 0.5rem 0.75rem;">Pago</span>
                                @elseif($pagamento->status_pagamento === 'estornado')
                                    <span class="badge bg-danger" style="font-size:0.9rem; padding: 0.5rem 0.75rem;">Estornado</span>
                                @else
                                    <span class="badge bg-secondary" style="font-size:0.9rem; padding: 0.5rem 0.75rem;">{{ $pagamento->status_pagamento }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="mb-3">
                            <label class="form-label" style="color:#78716C; font-size:0.85rem;">Data do Pagamento</label>
                            <div style="color:#1C1917; font-weight:600;">
                                {{ $pagamento->data_pagamento ? $pagamento->data_pagamento->format('d/m/Y H:i') : '—' }}
                            </div>
                        </div>
                    </div>

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
                    <div style="font-size:0.85rem; color:#78716C;">Criado em</div>
                    <div style="color:#1C1917; font-weight:600;">{{ $pagamento->created_at ? $pagamento->created_at->format('d/m/Y H:i') : '—' }}</div>
                </div>

                <div>
                    <div style="font-size:0.85rem; color:#78716C;">Última atualização</div>
                    <div style="color:#1C1917; font-weight:600;">{{ $pagamento->updated_at ? $pagamento->updated_at->format('d/m/Y H:i') : '—' }}</div>
                </div>
            </div>
        </div>


    </div>

</div>

@endsection
