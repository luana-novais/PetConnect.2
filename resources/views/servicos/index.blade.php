@extends('layout')

@section('title', 'Serviços')
@section('page-title', auth()->user()->role === 'ADM' ? 'Gerenciar Serviços' : 'Meus Serviços')

@push('styles')
    <link href="{{ asset('css/servicos/index.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Serviços</li>
@endsection

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">
            {{ auth()->user()->role === 'ADM' ? 'Gerenciar Serviços' : 'Meus Serviços' }}
        </h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">
            {{ auth()->user()->role === 'ADM' ? 'Controle os serviços cadastrados pelos profissionais.' : 'Gerencie todos os seus serviços.' }}
        </p>
    </div>
    @if(auth()->user()->role === 'PRO')
        <a href="{{ route('servicos.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Novo Serviço
        </a>
    @endif
</div>

<div class="card mb-4">
    <div class="card-body py-3">
        <form action="{{ route('servicos.index') }}" method="GET" class="row g-2 align-items-end">
            <div class="col">
                <label for="nome" class="form-label mb-1">Buscar por nome</label>
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-muted"></i></span>
                    <input type="text" id="nome" name="nome" class="form-control"
                           placeholder="Nome do serviço..." value="{{ request('nome') }}">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">
                    <i class="bi bi-funnel"></i> Filtrar
                </button>
                <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary btn-sm" aria-label="Limpar filtros" title="Limpar filtros">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </form>
    </div>
</div>

<div class="card table-card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="bi bi-bag me-2 text-warning"></i>Serviços cadastrados</span>
        <span class="badge bg-light text-secondary border">{{ $servicos->total() }} registros</span>
    </div>
    <div class="card-body p-0">
        @if($servicos->isEmpty())
            <div class="text-center py-5">
                <div style="font-size:3rem;">💼</div>
                <p class="text-muted mt-2 mb-3">Nenhum serviço cadastrado ainda.</p>
                @if(auth()->user()->role === 'PRO')
                    <a href="{{ route('servicos.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Cadastrar primeiro serviço
                    </a>
                @endif
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Serviço</th>
                            @if(auth()->user()->role === 'ADM')
                                <th>Profissional</th>
                            @endif
                            <th>Preço</th>
                            <th>Duração</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($servicos as $s)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="service-icon flex-shrink-0"><i class="bi bi-briefcase"></i></span>
                                        <div>
                                            <div class="fw-semibold">{{ $s->nome }}</div>
                                            @if(auth()->user()->role !== 'ADM')
                                                <small class="text-muted">Serviço oferecido por você</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                @if(auth()->user()->role === 'ADM')
                                    <td>{{ $s->user?->name ?? '—' }}</td>
                                @endif
                                <td class="fw-semibold text-success">R$ {{ number_format($s->valor_servico, 2, ',', '.') }}</td>
                                <td>{{ $s->duracao_minutos ? $s->duracao_minutos . ' min' : '—' }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <a href="{{ route('servicos.show', $s->id) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye me-1"></i> Consultar
                                        </a>
                                        @if(auth()->user()->role === 'PRO')
                                            <a href="{{ route('servicos.edit', $s->id) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil me-1"></i> Editar
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($servicos->hasPages())
                <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
                    <span class="text-muted" style="font-size:0.82rem;">Mostrando {{ $servicos->firstItem() }}–{{ $servicos->lastItem() }} de {{ $servicos->total() }} registros</span>
                    {{ $servicos->links('pagination::bootstrap-5') }}
                </div>
            @endif
        @endif
    </div>
</div>

@endsection