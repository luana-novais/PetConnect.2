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

<div class="service-list-header">
    <span><i class="bi bi-bag me-2 text-warning"></i>Serviços cadastrados</span>
    <span class="badge bg-light text-secondary border">{{ $servicos->total() }} registros</span>
</div>

@if($servicos->isEmpty())
    <div class="service-empty text-center">
        <div style="font-size:3rem;">💼</div>
        <p class="text-muted mt-2 mb-3">Nenhum serviço cadastrado ainda.</p>
        @if(auth()->user()->role === 'PRO')
            <a href="{{ route('servicos.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Cadastrar primeiro serviço
            </a>
        @endif
    </div>
@else
    <div class="service-list">
        @foreach($servicos as $s)
            <article class="service-card">
                <div class="service-icon"><i class="bi bi-briefcase"></i></div>
                <div class="service-body">
                    <div class="service-heading">
                        <div>
                            <h6 class="service-title">{{ $s->nome }}</h6>
                            @if(auth()->user()->role === 'ADM' && $s->user)
                                <span class="service-subtitle">Oferecido por {{ $s->user->name }}</span>
                            @else
                                <span class="service-subtitle">Serviço oferecido por você</span>
                            @endif
                        </div>
                        <strong class="service-price">R$ {{ number_format($s->valor_servico, 2, ',', '.') }}</strong>
                    </div>
                    <div class="service-meta"><i class="bi bi-tag"></i> Preço individual do serviço</div>
                </div>
                <div class="service-actions">
                    <a href="{{ route('servicos.show', $s->id) }}" class="btn service-view-button">
                        <i class="bi bi-eye me-1"></i> Consultar
                    </a>
                    @if(auth()->user()->role === 'PRO')
                        <a href="{{ route('servicos.edit', $s->id) }}" class="btn service-edit-button">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                    @endif
                </div>
            </article>
        @endforeach
    </div>

    @if($servicos->hasPages())
        <div class="d-flex justify-content-between align-items-center px-3 py-3 border-top">
            <span class="text-muted" style="font-size:0.82rem;">Mostrando {{ $servicos->firstItem() }}-{{ $servicos->lastItem() }} de {{ $servicos->total() }} registros</span>
            {{ $servicos->links('pagination::bootstrap-5') }}
        </div>
    @endif
@endif

@endsection