@extends('layout')

@section('title', 'Espécies')
@section('page-title', 'Espécies')

@section('breadcrumb')
    <li class="breadcrumb-item active">Espécies</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Lista de Espécies</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Gerencie as espécies cadastradas no sistema.</p>
    </div>
    <a href="{{ route('especies.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Nova Espécie
    </a>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul me-2 text-warning"></i>Espécies Cadastradas</span>
        <span class="badge bg-light text-secondary border">{{ $especies->count() }} registros</span>
    </div>
    <div class="card-body p-0">
        @if($especies->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:3rem;">🐾</div>
                <p class="text-muted mt-2 mb-3">Nenhuma espécie cadastrada ainda.</p>
                <a href="{{ route('especies.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Cadastrar primeira espécie
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo de Pet</th>
                            <th>Raças Cadastradas</th>
                            <th style="width:130px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($especies as $especie)
                        <tr>
                            <td>{{ $especie->id }}</td>
                            <td>{{ $especie->tipo_pet }}</td>
                            <td>{{ $especie->racas->count() }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('especies.edit', $especie->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('especies.destroy', $especie->id) }}" method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta espécie?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                </form>
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