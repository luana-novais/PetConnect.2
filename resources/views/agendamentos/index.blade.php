@extends('layout')

@section('title', 'Agendamentos')
@section('page-title', 'Agendamentos')

@push('styles')
    <link href="{{ asset('css/agendamentos/index.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Agendamentos</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Lista de Agendamentos</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Gerencie todos os agendamentos de serviços.</p>
    </div>
    <a href="{{ route('agendamentos.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Novo Agendamento
    </a>
</div>

{{-- Tabela --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-calendar2-check me-2 text-primary"></i>Agendamentos</span>
        <span class="badge bg-light text-secondary border">{{ count($agendamentos) }} registros</span>
    </div>
    <div class="card-body p-0">
        @if($agendamentos->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:3rem;">📅</div>
                <p class="text-muted mt-2 mb-3">Nenhum agendamento cadastrado ainda.</p>
                <a href="{{ route('agendamentos.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Criar primeiro agendamento
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pet</th>
                            <th>Cliente</th>
                            <th>Sitter</th>
                            <th>Serviço</th>
                            <th>Valor</th>
                            <th>Data de Início</th>
                            <th>Status</th>
                            <th style="width:300px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($agendamentos as $agendamento)
                        <tr>
                            <td>#{{ $agendamento->id }}</td>
                            <td>{{ $agendamento->pet->nome ?? 'N/A' }}</td>
                            <td>{{ $agendamento->cliente->name ?? 'N/A' }}</td>
                            <td>{{ $agendamento->sitter->name ?? 'N/A' }}</td>
                            <td>{{ $agendamento->servico->nome ?? 'N/A' }}</td>
                            <td>R$ {{ $agendamento->servico ? number_format($agendamento->servico->valor_servico, 2, ',', '.') : '—' }}</td>
                            <td>{{ $agendamento->data_inicio ? \Carbon\Carbon::parse($agendamento->data_inicio)->format('d/m/Y H:i') : '—' }}</td>
                            <td>
                                @if($agendamento->status === 'pendente')
                                    <span class="badge bg-warning">Pendente</span>
                                @elseif($agendamento->status === 'confirmado')
                                    <span class="badge bg-success">Confirmado</span>
                                @elseif($agendamento->status === 'cancelado')
                                    <span class="badge bg-danger">Cancelado</span>
                                @else
                                    <span class="badge bg-info">Concluído</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2 flex-wrap">
                                {{-- Botão de detalhes --}}

                                
                                <a href="{{ route('agendamentos.edit', $agendamento->id) }}" 
                                   class="btn btn-sm btn-warning">
                                    Editar
                                </a>                                
                                <a href="{{ route('agendamentos.show', $agendamento->id) }}" 
                                   class="btn btn-sm btn-info">
                                    Consultar
                                </a>

                                {{-- Botão pagar só aparece se pendente e sem pagamento --}}
                                @if($agendamento->status === 'pendente' && !$agendamento->pagamento)
                                    <a href="{{ route('pagamentos.create', $agendamento->id) }}" 
                                       class="btn btn-sm btn-success">
                                        💳 Pagar
                                    </a>
                                @endif

                                {{-- Se já pagou, mostra o comprovante --}}
                                @if($agendamento->pagamento)
                                    <a href="{{ route('pagamentos.show', $agendamento->pagamento->id) }}" 
                                       class="btn btn-sm btn-secondary">
                                        🧾 Ver Pagamento
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

@endsection
