@extends('layout')

@section('title', 'Raças')
@section('page-title', 'Raças')

@push('styles')
    <link href="{{ asset('css/racas/index.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Raças</li>
@endsection

@section('content')

<div class="racas-page">
    <div class="racas-page__header">
        <div>
            <h1 class="racas-page__title">Lista de Raças</h1>
            <p class="racas-page__description">Gerencie as raças cadastradas no sistema.</p>
        </div>
        <a href="{{ route('racas.create') }}" class="btn btn-primary racas-page__add">
            <i class="bi bi-plus-lg"></i> Nova Raça
        </a>
    </div>

    @if(auth()->user()->role === 'ADM')
        <form action="{{ route('racas.index') }}" method="GET" class="card racas-filter-card mb-4">
            <div class="card-body">
                <div class="racas-filter-form">
                    <div class="racas-filter-field">
                        <label for="filter-especie" class="form-label">Filtrar por espécie</label>
                        <select name="id_especie" id="filter-especie" class="form-select">
                            <option value="">Todas as espécies</option>
                            @foreach($especies as $especie)
                                <option value="{{ $especie->id }}" @selected((string) $selectedEspecie === (string) $especie->id)>
                                    {{ $especie->tipo_pet }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="racas-filter-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-funnel me-1"></i>Filtrar
                        </button>
                        @if($selectedEspecie)
                            <a href="{{ route('racas.index') }}" class="btn btn-outline-secondary">Limpar filtro</a>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    @endif

    <div class="card table-card racas-table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-list-ul me-2 text-warning"></i>Raças Cadastradas</span>
            <span class="badge bg-light text-secondary border">{{ $racas->count() }} registros</span>
        </div>
        <div class="card-body p-0">
            @if($racas->isEmpty())
                <div class="text-center py-5">
                    <div style="font-size:3rem;">🐾</div>
                    <p class="text-muted mt-2 mb-3">
                        {{ $selectedEspecie ? 'Nenhuma raça encontrada para esta espécie.' : 'Nenhuma raça cadastrada ainda.' }}
                    </p>
                    @if($selectedEspecie)
                        <a href="{{ route('racas.index') }}" class="btn btn-outline-secondary btn-sm">Limpar filtro</a>
                    @else
                        <a href="{{ route('racas.create') }}" class="btn btn-primary btn-sm">
                            <i class="bi bi-plus-lg"></i> Cadastrar primeira raça
                        </a>
                    @endif
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 racas-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Espécie</th>
                                <th class="racas-actions-heading">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($racas as $raca)
                            <tr>
                                <td>{{ $raca->id }}</td>
                                <td>{{ $raca->nome }}</td>
                                <td>{{ $raca->especie->tipo_pet }}</td>
                                <td class="racas-actions-cell">
                                    <div class="racas-actions">
                                        <a href="{{ route('racas.edit', $raca->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('racas.destroy', $raca->id) }}" method="POST"
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta raça?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection