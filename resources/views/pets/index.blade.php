@extends('layout')

@section('title', 'Pets')
@section('page-title', 'Meus Pets')

@push('styles')
    <link href="{{ asset('css/pets/index.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Pets</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Lista de Pets</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Gerencie todos os pets cadastrados no sistema.</p>
    </div>
    <a href="{{ route('pets.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Novo Pet
    </a>
</div>

{{-- Filtros --}}
<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('pets.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label mb-1">Buscar por nome</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="nome" class="form-control" placeholder="Nome do pet..." value="{{ request('nome') }}">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label mb-1">Espécie</label>
                <select name="id_especie" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    @foreach($especies as $e)
                        <option value="{{ $e->id }}" {{ request('id_especie') == $e->id ? 'selected' : '' }}>
                            {{ $e->tipo_pet }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label mb-1">Sexo</label>
                <select name="sexo" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="MACHO" {{ request('sexo') == 'MACHO' ? 'selected' : '' }}>Macho</option>
                    <option value="FEMEA" {{ request('sexo') == 'FEMEA' ? 'selected' : '' }}>Fêmea</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
                <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Tabela --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-heart me-2 text-warning"></i>Pets Cadastrados</span>
        <span class="badge bg-light text-secondary border">{{ $pets->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        @if($pets->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:3rem;">🐾</div>
                <p class="text-muted mt-2 mb-3">Nenhum pet cadastrado ainda.</p>
                <a href="{{ route('pets.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Cadastrar primeiro pet
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Pet</th>
                            <th>Espécie / Raça</th>
                            <th>Dono</th>
                            <th>Nascimento</th>
                            <th>Peso</th>
                            <th>Sexo</th>
                            <th style="width:130px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pets as $pet)
                        <tr>
                            <td>{{ $pet->id }}</td>
                            <td>{{ $pet->nome }}</td>
                            <td>{{ $pet->especie->tipo_pet }} / {{ $pet->raca->nome }}</td>
                            <td>{{ $pet->user->name }}</td>
                            <td>{{ $pet->data_nascimento ? $pet->data_nascimento->format('d/m/Y') : '—' }}</td>
                            <td>{{ $pet->peso ? number_format($pet->peso, 2, ',', '.') . ' kg' : '—' }}</td>
                            <td>{{ $pet->sexo === 'MACHO' ? '♂ Macho' : '♀ Fêmea' }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-sm btn-info">Consultar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if($pets->hasPages())
                <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                    <span class="text-muted" style="font-size:0.82rem;">
                        Mostrando {{ $pets->firstItem() }}–{{ $pets->lastItem() }} de {{ $pets->total() }} registros
                    </span>
                    {{ $pets->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection
