@extends('layout')

@section('content')
<div class="container">
    <h2>Pagamento do Agendamento #{{ $agendamento->id }}</h2>

    <div class="card mb-3">
        <div class="card-body">
            <p><strong>Serviço:</strong> {{ $agendamento->servico->nome }}</p>
            <p><strong>Total:</strong> R$ {{ number_format($agendamento->servico->valor_servico, 2, ',', '.') }}</p>
        </div>
    </div>

    <form action="{{ route('pagamentos.store') }}" method="POST">
        @csrf

        <input type="hidden" name="id_agendamento" value="{{ $agendamento->id }}">

        <div class="mb-3">
            <label class="form-label">Método de Pagamento</label>
            <select name="metodo_pagamento" class="form-select" required>
                <option value="">Selecione...</option>
                <option value="pix">PIX</option>
                <option value="cartao">Cartão</option>
                <option value="dinheiro">Dinheiro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Confirmar Pagamento</button>
        <a href="{{ route('agendamentos.show', $agendamento->id) }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection