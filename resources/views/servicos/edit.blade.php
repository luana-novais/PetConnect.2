@extends('layout')

@section('title', 'Editar Serviço')
@section('page-title', 'Editar Serviço')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('servicos.index') }}" class="text-decoration-none">Serviços</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('servicos.show', $servicos->id) }}" class="text-decoration-none">{{ $servicos->nome }}</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Editar: {{ $servicos->nome }}</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('servicos.show', $servicos->id) }}" class="btn btn-outline-secondary btn-sm">Visualizar</a>
                <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Erros:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="/servicos/{{ $servicos->id }}">
            @csrf
            @METHOD('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informações Básicas</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome do Serviço</label>
                            <input type="text" name="nome" class="form-control" value="{{ old('nome', $servicos->nome) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Valor do Serviço</label>
                            <input type="number" step="0.01" min="0" name="valor_servico" class="form-control @error('valor_servico') is-invalid @enderror" value="{{ old('valor_servico', $servicos->valor_servico) }}" required>
                            @error('valor_servico')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Duração do Serviço</label>
                            <div class="input-group">
                                <input type="number" step="15" min="15" max="720" name="duracao_minutos" class="form-control @error('duracao_minutos') is-invalid @enderror" value="{{ old('duracao_minutos', $servicos->duracao_minutos ?? 60) }}" required>
                                <span class="input-group-text">min</span>
                            </div>
                            @error('duracao_minutos')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                    </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('servicos.show', $servicos->id) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            </div>
        </form>
    </div>
</div>

@endsection