@extends('layout')

@section('title', 'Raças')
@section('page-title', 'Raças')

@section('breadcrumb')
    <li class="breadcrumb-item active">Raças</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Lista de Raças</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Gerencie as raças cadastradas no sistema.</p>
    </div>
    <a href="{{ route('racas.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Nova Raça
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-list-ul me-2 text-warning"></i>Raças Cadastradas</span>
        <span class="badge bg-light text-secondary border">{{ $racas->count() }} registros</span>
    </div>
    <div class="card-body p-0">
        @if($racas->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:3rem;">🐾</div>
                <p class="text-muted mt-2 mb-3">Nenhuma raça cadastrada ainda.</p>
                <a href="{{ route('racas.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Cadastrar primeira raça
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Espécie</th>
                            <th style="width:130px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($racas as $raca)
                        <tr>
                            <td>{{ $raca->id }}</td>
                            <td>{{ $raca->nome }}</td>
                            <td>{{ $raca->especie->tipo_pet }}</td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('racas.edit', $raca->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <form action="{{ route('racas.destroy', $raca->id) }}" method="POST"
                                      onsubmit="return confirm('Tem certeza que deseja excluir esta raça?')">
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