@extends('layout')

@section('title', 'Pagamentos')
@section('page-title', 'Pagamentos')

@push('styles')
    <link href="{{ asset('css/agendamentos/index.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Pagamentos</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Lista de Pagamentos</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Gerencie todos os pagamentos de serviços.</p>
    </div>
</div>

{{-- Tabela --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-credit-card me-2 text-primary"></i>Pagamentos</span>
        <span class="badge bg-light text-secondary border">{{ count($pagamentos) }} registros</span>
    </div>
    <div class="card-body p-0">
        @if($pagamentos->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:3rem;">💳</div>
                <p class="text-muted mt-2 mb-3">Nenhum pagamento registrado ainda.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Agendamento</th>
                            <th>Cliente</th>
                            <th>Valor</th>
                            <th>Método</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th style="width:180px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pagamentos as $pagamento)
                        <tr>
                            <td>#{{ $pagamento->id }}</td>
                            <td>#{{ $pagamento->agendamento->id ?? 'N/A' }}</td>
                            <td>{{ $pagamento->agendamento->cliente->name ?? 'N/A' }}</td>
                            <td><strong>R$ {{ number_format($pagamento->total_servico, 2, ',', '.') }}</strong></td>
                            <td>
                                @if($pagamento->metodo_pagamento === 'pix')
                                    <span class="badge bg-info">PIX</span>
                                @elseif($pagamento->metodo_pagamento === 'cartao')
                                    <span class="badge bg-primary">Cartão</span>
                                @elseif($pagamento->metodo_pagamento === 'dinheiro')
                                    <span class="badge bg-success">Dinheiro</span>
                                @else
                                    <span class="badge bg-secondary">{{ $pagamento->metodo_pagamento }}</span>
                                @endif
                            </td>
                            <td>{{ $pagamento->data_pagamento ? $pagamento->data_pagamento->format('d/m/Y') : '—' }}</td>
                            <td>
                                @if($pagamento->status_pagamento === 'pendente')
                                    <span class="badge bg-warning">Pendente</span>
                                @elseif($pagamento->status_pagamento === 'pago')
                                    <span class="badge bg-success">Pago</span>
                                @elseif($pagamento->status_pagamento === 'estornado')
                                    <span class="badge bg-danger">Estornado</span>
                                @else
                                    <span class="badge bg-secondary">{{ $pagamento->status_pagamento }}</span>
                                @endif
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('pagamentos.show', $pagamento->id) }}" class="btn btn-sm btn-info">Consultar</a>
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
