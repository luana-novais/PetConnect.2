@extends('layout')

@section('title', $servicos->nome)
@section('page-title', $servicos->nome)

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('servicos.index') }}" class="text-decoration-none">Serviços</a>
    </li>
    <li class="breadcrumb-item active">{{ $servicos->nome }}</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1" style="color:#1C1917;">Detalhes do Serviço</h5>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Informações completas de {{ $servicos->nome }}.</p>
            </div>
            <div class="d-flex gap-2">
                @if(auth()->user()->role === 'PRO')
                    <a href="{{ route('servicos.edit', $servicos->id) }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                @endif
                <a href="{{ route('servicos.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h6 class="mb-0">Informações do Serviço</h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Nome:</strong>
                        <p class="mb-0">{{ $servicos->nome }}</p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Valor:</strong>
                        <p class="mb-0">R$ {{ number_format($servicos->valor_servico, 2, ',', '.') }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>Data de Criação:</strong>
                        <p class="mb-0">{{ $servicos->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->role === 'PRO')
            <div class="d-flex gap-2 justify-content-end">
                <button type="button" class="btn btn-danger" onclick="if(confirm('Tem certeza que deseja excluir?')) { document.getElementById('deleteForm').submit(); }">
                    <i class="bi bi-trash me-1"></i> Excluir
                </button>
            </div>

            <form id="deleteForm" action="{{ route('servicos.destroy', $servicos->id) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</div>

@endsection