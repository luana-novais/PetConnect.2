@extends('layout')

@section('title', 'Editar Raça')
@section('page-title', 'Editar Raça')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('racas.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Raças</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-6 col-lg-8">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1" style="color:#1C1917;">Editar Raça</h5>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Altere os dados da raça abaixo.</p>
            </div>
            <a href="{{ route('racas.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

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

        <div class="card">
            <div class="card-header">
                <i class="bi bi-tag me-2 text-warning"></i>Dados da Raça
            </div>
            <div class="card-body">
                <form action="{{ route('racas.update', $raca->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome da Raça <span class="text-danger">*</span></label>
                        <input type="text"
                               id="nome"
                               name="nome"
                               class="form-control @error('nome') is-invalid @enderror"
                               value="{{ old('nome', $raca->nome) }}"
                               required>
                        @error('nome')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="id_especie" class="form-label">Espécie <span class="text-danger">*</span></label>
                        <select id="id_especie" name="id_especie"
                                class="form-select @error('id_especie') is-invalid @enderror" required>
                            <option value="">Selecione a espécie...</option>
                            @foreach($especies as $especie)
                                <option value="{{ $especie->id }}" {{ old('id_especie', $raca->id_especie) == $especie->id ? 'selected' : '' }}>
                                    {{ $especie->tipo_pet }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_especie')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('racas.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Salvar Alterações
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection