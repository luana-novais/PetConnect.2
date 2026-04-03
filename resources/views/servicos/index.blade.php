@extends('layout')

@section('title', 'Serviços')
@section('page-title', 'Meus Serviços')

@push('styles')
    <link href="{{ asset('resources/css/form.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Serviços</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Lista de Serviços</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Gerencie todos os seus serviços.</p>
    </div>
    <a href="{{ route('servicos.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
        <i class="bi bi-plus-lg"></i> Novo Serviço
    </a>
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('servicos.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col">
                <label class="form-label mb-1">Buscar por nome</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" name="nome" class="form-control" placeholder="Nome do serviço..." value="{{ request('nome') }}">
                </div>
            </div>   
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
                <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bag me-2 text-warning"></i>Serviços Cadastrados</span>
        <span class="badge bg-light text-secondary border">{{ $servicos->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        @if($servicos->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:3rem;">💼</div>
                <p class="text-muted mt-2 mb-3">Nenhum serviço cadastrado ainda.</p>
                <a href="{{ route('servicos.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg"></i> Cadastrar primeiro serviço
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Valor</th>
                            <th style="width:130px;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servicos as $s)
                        <tr>
                            <td>{{ $s->id }}</td>
                            <td>{{ $s->nome }}</td>
                            <td>{{ $s->valor_servico }}</td>
                            
                            <td class="d-flex gap-2">
                                <a href="{{ route('servicos.edit', $s->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                <a href="{{ route('servicos.show', $s->id) }}" class="btn btn-sm btn-info">Consultar</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginação --}}
            @if($servicos->hasPages())
                <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                    <span class="text-muted" style="font-size:0.82rem;">
                        Mostrando {{ $servicos->firstItem() }}-{{ $servicos->lastItem() }} de {{ $servicos->total() }} registros
                    </span>
                    {{ $servicos->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection