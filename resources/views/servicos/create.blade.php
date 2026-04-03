@extends('layout')

@section('title', 'Novo Serviço')
@section('page-title', 'Novo Serviço')

@push('styles')
    <link href="{{ asset('resources/css/form.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('servicos.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Serviços</a>
    </li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1" style="color:#1C1917;">Cadastrar Novo Serviço</h5>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Preencha as informações do serviço abaixo.</p>
            </div>
            <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

        <form action="{{ route('servicos.store') }}" method="POST" enctype="multipart/form-data">
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

            {{-- Informações básicas --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2 text-warning"></i>Informações Básicas
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome do Serviço <span class="text-danger">*</span></label>
                            <input type="text" id="nome" name="nome" class="form-control @error('nome') is-invalid @enderror"
                                   value="{{ old('nome') }}" placeholder="Ex: Pet Sitter, Taxi Dog,..."
                                   required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="valor_servico" class="form-label">Valor do Serviço <span class="text-danger">*</span></label>
                            <input type="text" step="0.01" id="valor_servico" name="valor_servico" class="form-control @error('valor') is-invalid @enderror"
                                   value="{{ old('valor') }}" placeholder="Insira o valor"
                                   required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botões --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Salvar Serviço
                </button>
            </div>

        </form>
    </div>
</div>

@endsection