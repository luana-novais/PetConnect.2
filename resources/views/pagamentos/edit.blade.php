@extends('layout')

@section('title', 'Editar Pagamento')
@section('page-title', 'Editar Pagamento')

@push('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pagamentos.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Pagamentos</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1" style="color:#1C1917;">Editar Pagamento</h5>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Atualize as informações do pagamento abaixo.</p>
            </div>
            <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

        <form action="{{ route('pagamentos.update', $pagamento->id) }}" method="POST">
            @csrf
            @method('PUT')

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

            {{-- Informações do Pagamento --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-credit-card me-2 text-primary"></i>Informações do Pagamento
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="id_agendamento" class="form-label">Agendamento <span class="text-danger">*</span></label>
                            <select id="id_agendamento" name="id_agendamento"
                                    class="form-select @error('id_agendamento') is-invalid @enderror" required>
                                <option value="">Selecione o agendamento...</option>
                                @foreach($agendamentos as $agendamento)
                                    <option value="{{ $agendamento->id }}" {{ old('id_agendamento', $pagamento->id_agendamento) == $agendamento->id ? 'selected' : '' }}>
                                        #{{ $agendamento->id }} - {{ $agendamento->servico->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_agendamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="total_servico" class="form-label">Valor Total <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" id="total_servico" name="total_servico"
                                   class="form-control @error('total_servico') is-invalid @enderror"
                                   value="{{ old('total_servico', $pagamento->total_servico) }}" required>
                            @error('total_servico')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="metodo_pagamento" class="form-label">Método de Pagamento <span class="text-danger">*</span></label>
                            <select id="metodo_pagamento" name="metodo_pagamento"
                                    class="form-select @error('metodo_pagamento') is-invalid @enderror" required>
                                <option value="">Selecione o método...</option>
                                <option value="pix" {{ old('metodo_pagamento', $pagamento->metodo_pagamento) == 'pix' ? 'selected' : '' }}>PIX</option>
                                <option value="cartao" {{ old('metodo_pagamento', $pagamento->metodo_pagamento) == 'cartao' ? 'selected' : '' }}>Cartão de Crédito</option>
                                <option value="dinheiro" {{ old('metodo_pagamento', $pagamento->metodo_pagamento) == 'dinheiro' ? 'selected' : '' }}>Dinheiro</option>
                            </select>
                            @error('metodo_pagamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="status_pagamento" class="form-label">Status <span class="text-danger">*</span></label>
                            <select id="status_pagamento" name="status_pagamento"
                                    class="form-select @error('status_pagamento') is-invalid @enderror" required>
                                <option value="">Selecione o status...</option>
                                <option value="pendente" {{ old('status_pagamento', $pagamento->status_pagamento) == 'pendente' ? 'selected' : '' }}>Pendente</option>
                                <option value="pago" {{ old('status_pagamento', $pagamento->status_pagamento) == 'pago' ? 'selected' : '' }}>Pago</option>
                                <option value="estornado" {{ old('status_pagamento', $pagamento->status_pagamento) == 'estornado' ? 'selected' : '' }}>Estornado</option>
                            </select>
                            @error('status_pagamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="data_pagamento" class="form-label">Data do Pagamento</label>
                            <input type="date" id="data_pagamento" name="data_pagamento"
                                   class="form-control @error('data_pagamento') is-invalid @enderror"
                                   value="{{ old('data_pagamento', $pagamento->data_pagamento?->format('Y-m-d')) }}">
                            @error('data_pagamento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('pagamentos.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Salvar Alterações
                </button>
            </div>

        </form>

    </div>
</div>

@endsection
