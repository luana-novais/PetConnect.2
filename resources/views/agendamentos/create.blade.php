@extends('layout')

@section('title', 'Novo Agendamento')
@section('page-title', 'Novo Agendamento')

@push('styles')
    <link href="{{ asset('css/form.css') }}" rel="stylesheet">
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
                <p class="text-muted mb-0" style="font-size:0.88rem;">Preencha as informações do agendamento abaixo.</p>
            </div>
            <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

        <form action="{{ route('agendamentos.store') }}" method="POST">
            @csrf

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

            {{-- Informações do Agendamento --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-calendar3 me-2 text-primary"></i>Informações do Agendamento
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="id_user_cliente" class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select id="id_user_cliente" name="id_user_cliente"
                                    class="form-select @error('id_user_cliente') is-invalid @enderror" required>
                                <option value="">Selecione o cliente...</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ old('id_user_cliente') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user_cliente')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="id_pet" class="form-label">Pet <span class="text-danger">*</span></label>
                            <select id="id_pet" name="id_pet"
                                    class="form-select @error('id_pet') is-invalid @enderror" required>
                                <option value="">Selecione o pet...</option>
                                @foreach($pets as $pet)
                                    <option value="{{ $pet->id }}" {{ old('id_pet') == $pet->id ? 'selected' : '' }}>
                                        {{ $pet->nome }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_pet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="id_user_sitter" class="form-label">Sitter <span class="text-danger">*</span></label>
                            <select id="id_user_sitter" name="id_user_sitter"
                                    class="form-select @error('id_user_sitter') is-invalid @enderror" required>
                                <option value="">Selecione o sitter...</option>
                                @foreach($sitters as $sitter)
                                    <option value="{{ $sitter->id }}" {{ old('id_user_sitter') == $sitter->id ? 'selected' : '' }}>
                                        {{ $sitter->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user_sitter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="id_servico" class="form-label">Serviço <span class="text-danger">*</span></label>
                            <select id="id_servico" name="id_servico"
                                    class="form-select @error('id_servico') is-invalid @enderror" required>
                                <option value="">Selecione o serviço...</option>
                                @foreach($servicos as $servico)
                                    <option value="{{ $servico->id }}" {{ old('id_servico') == $servico->id ? 'selected' : '' }}>
                                        {{ $servico->nome }}
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

            {{-- Data e Horário --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-calendar-event me-2 text-success"></i>Data e Horário
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
                            <label for="observacoes" class="form-label">Observações</label>
                            <textarea id="observacoes"
                                      name="observacoes"
                                      class="form-control @error('observacoes') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Adicione observações sobre o agendamento...">{{ old('observacoes') }}</textarea>
                            @error('observacoes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Botões de ação --}}
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Salvar Agendamento
                </button>
                <a href="{{ route('agendamentos.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-1"></i> Cancelar
                </a>
            </div>

        </form>
    </div>
</div>

@endsection
