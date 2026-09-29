@extends('layout')

@section('title', 'Pagamentos')
@section('page-title', 'Pagamentos')

@push('styles')
    <link href="{{ asset('css/pagamentos/index.css') }}" rel="stylesheet">
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

@if($pagamentos->isEmpty())
    <div class="text-center py-5">
        <div style="font-size:3rem;">💳</div>
        <p class="text-muted mt-2 mb-3">Nenhum pagamento registrado ainda.</p>
    </div>
@else
    <div class="payment-list">
        @foreach($pagamentos as $pagamento)
            @php
                $statusClass = [
                    'pendente' => 'payment-pending',
                    'pago' => 'payment-paid',
                    'estornado' => 'payment-refunded',
                ][$pagamento->status_pagamento] ?? 'payment-neutral';

                $statusLabel = [
                    'pendente' => 'Pendente',
                    'pago' => 'Pago',
                    'estornado' => 'Estornado',
                ][$pagamento->status_pagamento] ?? ucfirst($pagamento->status_pagamento);

                $methodLabel = [
                    'pix' => 'PIX',
                    'cartao' => 'Cartão',
                    'dinheiro' => 'Dinheiro',
                ][$pagamento->metodo_pagamento] ?? ucfirst($pagamento->metodo_pagamento);
            @endphp

            <article class="payment-card">
                <div class="payment-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </div>

                <div class="payment-body">
                    <div class="payment-heading">
                        <div>
                            <div class="payment-title">{{ $pagamento->agendamento->servico->nome ?? 'Pagamento de serviço' }}</div>
                            <div class="payment-subtitle">{{ $pagamento->agendamento->cliente->name ?? 'Cliente não informado' }}</div>
                        </div>
                        <span class="payment-status {{ $statusClass }}">
                            <i class="bi {{ $pagamento->status_pagamento === 'pago' ? 'bi-check-circle-fill' : 'bi-clock' }}"></i>
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="payment-meta">
                        <span><i class="bi bi-cash-stack"></i>R$ {{ number_format($pagamento->total_servico, 2, ',', '.') }}</span>
                        <span><i class="bi bi-wallet2"></i>{{ $methodLabel }}</span>
                        <span><i class="bi bi-calendar3"></i>{{ $pagamento->data_pagamento ? $pagamento->data_pagamento->format('d/m/Y') : 'Data não informada' }}</span>
                        @if($pagamento->agendamento->pet)
                            <span><i class="bi bi-heart"></i>{{ $pagamento->agendamento->pet->nome }}</span>
                        @endif
                    </div>
                </div>

                <div class="payment-actions">
                    <a href="{{ route('pagamentos.show', $pagamento->id) }}" class="btn payment-details-button">
                        <i class="bi bi-eye me-1"></i> Ver detalhes
                    </a>
                </div>
            </article>
        @endforeach
    </div>
@endif

@endsection
