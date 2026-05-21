@extends('layout')

@section('title', 'Agendamentos')
@section('page-title', 'Agendamentos')

@push('styles')
<style>
    .ag-card {
        background: #fff;
        border: 1px solid #E7E5E4;
        border-radius: 1rem;
        padding: 20px 24px;
        margin-bottom: 14px;
        transition: box-shadow .2s;
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .ag-card:hover { box-shadow: 0 4px 18px rgba(0,0,0,.07); }

    /* Avatar do pet */
    .ag-avatar {
        width: 52px; height: 52px;
        border-radius: 50%;
        background: #FFF7ED;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    /* Corpo principal */
    .ag-body { flex: 1; min-width: 0; }
    .ag-title { font-weight: 700; font-size: .98rem; color: #1C1917; margin-bottom: 2px; }
    .ag-sub   { font-size: .82rem; color: #78716C; }

    /* Metadados em linha */
    .ag-meta {
        display: flex; flex-wrap: wrap; gap: 16px;
        margin-top: 10px;
    }
    .ag-meta-item { display: flex; align-items: center; gap: 5px; font-size: .82rem; color: #57534E; }
    .ag-meta-item i { color: #A8A29E; font-size: .9rem; }

    /* Badges */
    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 2rem;
        font-size: .78rem; font-weight: 600;
    }
    .status-pendente   { background:#FEF9C3; color:#854D0E; }
    .status-confirmado { background:#DCFCE7; color:#166534; }
    .status-cancelado  { background:#FEE2E2; color:#991B1B; }
    .status-concluido  { background:#DBEAFE; color:#1E40AF; }
    .pay-pago          { background:#DCFCE7; color:#166534; }
    .pay-pendente      { background:#FEF9C3; color:#854D0E; }
    .pay-estornado     { background:#F3F4F6; color:#6B7280; }
    .pay-none          { background:#F3F4F6; color:#9CA3AF; }

    /* Ações à direita */
    .ag-actions {
        display: flex; flex-direction: column;
        gap: 5px; align-items: stretch;
        flex-shrink: 0; width: 140px;
    }
    .ag-actions .btn { font-size: .8rem; padding: 5px 12px; white-space: nowrap; text-align:center; }
    .ag-actions .btn-ghost {
        background: none; border: 1px solid #E7E5E4;
        color: #57534E; border-radius: .5rem;
    }
    .ag-actions .btn-ghost:hover { background: #F5F5F4; }

    .ag-badges { display: flex; gap: 6px; align-items: center; flex-wrap: wrap; }

    /* Separador entre grupos de ação */
    .ag-divider { width: 100%; height: 1px; background: #F3F4F6; margin: 2px 0; }

    @media (max-width: 768px) {
        .ag-card   { flex-direction: column; align-items: flex-start; }
        .ag-actions { flex-direction: row; flex-wrap: wrap; align-items: flex-start; width: 100%; }
    }
</style>
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Agendamentos</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Meus Agendamentos</h5>
        <p class="text-muted mb-0" style="font-size:.88rem;">
            {{ count($agendamentos) }} agendamento{{ count($agendamentos) !== 1 ? 's' : '' }} encontrado{{ count($agendamentos) !== 1 ? 's' : '' }}
        </p>
    </div>
    @if(auth()->user()->role === 'CLI')
        <a href="{{ route('agendamentos.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Novo Agendamento
        </a>
    @endif
</div>

@if($agendamentos->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:3.5rem;">📅</div>
        <p class="text-muted mt-3 mb-3">Nenhum agendamento encontrado.</p>
        @if(auth()->user()->role === 'CLI')
            <a href="{{ route('agendamentos.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Criar primeiro agendamento
            </a>
        @endif
    </div>
@else
    @foreach($agendamentos as $ag)
    @php
        $statusClass = [
            'pendente'   => 'status-pendente',
            'confirmado' => 'status-confirmado',
            'cancelado'  => 'status-cancelado',
            'concluido'  => 'status-concluido',
        ][$ag->status] ?? 'status-pendente';

        $statusLabel = [
            'pendente'   => 'Pendente',
            'confirmado' => 'Confirmado',
            'cancelado'  => 'Cancelado',
            'concluido'  => 'Concluído',
        ][$ag->status] ?? ucfirst($ag->status);

        $statusIcon = [
            'pendente'   => 'bi-clock',
            'confirmado' => 'bi-check-circle-fill',
            'cancelado'  => 'bi-x-circle-fill',
            'concluido'  => 'bi-check-all',
        ][$ag->status] ?? 'bi-clock';

        $petEmoji = match($ag->pet?->especie?->tipo_pet ?? '') {
            'Cachorro' => '🐶', 'Gato' => '🐱',
            'Pássaro'  => '🐦', 'Coelho' => '🐰',
            default    => '🐾'
        };

        $canEdit   = $ag->status === 'pendente';
        $canCancel = !in_array($ag->status, ['cancelado', 'concluido']);
        $canConfirm = auth()->user()->role === 'PRO' && $ag->status === 'pendente';
        $canPay    = auth()->user()->role === 'CLI' && $ag->status === 'confirmado' && !$ag->pagamento;
    @endphp

    <div class="ag-card">

        {{-- Avatar --}}
        <div class="ag-avatar">{{ $petEmoji }}</div>

        {{-- Corpo --}}
        <div class="ag-body">
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <span class="ag-title">{{ $ag->pet->nome ?? 'Pet' }}</span>
                <span class="ag-sub">·</span>
                <span class="ag-sub">{{ $ag->servico->nome ?? '—' }}</span>
                <div class="ag-badges ms-1">
                    <span class="status-badge {{ $statusClass }}">
                        <i class="bi {{ $statusIcon }}"></i> {{ $statusLabel }}
                    </span>
                    @if($ag->pagamento)
                        @php
                            $pgClass = ['pago'=>'pay-pago','pendente'=>'pay-pendente','estornado'=>'pay-estornado'][$ag->pagamento->status_pagamento] ?? 'pay-none';
                            $pgLabel = ['pago'=>'Pago','pendente'=>'Pag. pendente','estornado'=>'Estornado'][$ag->pagamento->status_pagamento] ?? '—';
                        @endphp
                        <span class="status-badge {{ $pgClass }}">
                            <i class="bi bi-credit-card"></i> {{ $pgLabel }}
                        </span>
                    @else
                        <span class="status-badge pay-none">
                            <i class="bi bi-credit-card"></i> Sem pagamento
                        </span>
                    @endif
                </div>
            </div>

            <div class="ag-meta">
                @if($ag->data_inicio)
                    <div class="ag-meta-item">
                        <i class="bi bi-calendar3"></i>
                        {{ \Carbon\Carbon::parse($ag->data_inicio)->format('d/m/Y') }}
                    </div>
                    <div class="ag-meta-item">
                        <i class="bi bi-clock"></i>
                        {{ \Carbon\Carbon::parse($ag->data_inicio)->format('H:i') }}
                        @if($ag->data_fim)
                            → {{ \Carbon\Carbon::parse($ag->data_fim)->format('H:i') }}
                        @endif
                    </div>
                @endif
                <div class="ag-meta-item">
                    <i class="bi bi-person"></i>
                    {{ $ag->cliente->name ?? '—' }}
                </div>
                <div class="ag-meta-item">
                    <i class="bi bi-person-badge"></i>
                    {{ $ag->sitter->name ?? '—' }}
                </div>
                @if($ag->servico)
                    <div class="ag-meta-item">
                        <i class="bi bi-tag"></i>
                        R$ {{ number_format($ag->servico->valor_servico, 2, ',', '.') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Ações --}}
        <div class="ag-actions">

            <a href="{{ route('agendamentos.show', $ag->id) }}"
               class="btn btn-ghost btn-sm w-100">
                <i class="bi bi-eye me-1"></i> Ver detalhes
            </a>

            @if($canEdit)
                <a href="{{ route('agendamentos.edit', $ag->id) }}"
                   class="btn btn-sm w-100"
                   style="background:#FFF7ED;color:var(--pet-primary);border:1px solid #FDDCB5;">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
            @endif

            @if($canConfirm)
                <form method="POST" action="{{ route('agendamentos.confirm', $ag->id) }}" class="w-100">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-sm btn-success w-100"
                            onclick="return confirm('Confirmar este agendamento?')">
                        <i class="bi bi-check-lg me-1"></i> Confirmar
                    </button>
                </form>
            @endif

            @if($canPay)
                <a href="{{ route('pagamentos.create', $ag->id) }}"
                   class="btn btn-sm btn-success w-100">
                    <i class="bi bi-credit-card me-1"></i> Pagar
                </a>
            @endif

            @if($ag->pagamento)
                <a href="{{ route('pagamentos.show', $ag->pagamento->id) }}"
                   class="btn btn-ghost btn-sm w-100">
                    <i class="bi bi-receipt me-1"></i> Comprovante
                </a>
            @endif

            @if($canCancel)
                <form method="POST" action="{{ route('agendamentos.cancel', $ag->id) }}" class="w-100">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-sm btn-outline-danger w-100"
                            onclick="return confirm('Cancelar este agendamento?')">
                        <i class="bi bi-x-lg me-1"></i> Cancelar
                    </button>
                </form>
            @endif

        </div>
    </div>
    @endforeach
@endif

@endsection