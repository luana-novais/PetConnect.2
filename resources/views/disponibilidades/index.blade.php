@extends('layout')

@section('title', 'Disponibilidades')
@section('page-title', 'Disponibilidades')

@push('styles')
    <link href="{{ asset('css/disponibilidades/index.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Disponibilidades</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">
                {{ auth()->user()->role === 'ADM' ? 'Gerenciar Disponibilidades' : 'Meus Horários' }}
            </h5>
            <p class="text-muted mb-0" style="font-size:0.9rem;">
                {{ auth()->user()->role === 'ADM' ? 'Controle os dias e horários disponíveis para os atendimentos.' : 'Defina os dias e horários em que você está disponível para atendimentos.' }}
            </p>
        </div>
        @if(auth()->user()->role !== 'ADM')
            <a href="{{ route('disponibilidades.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
                <i class="bi bi-plus-lg"></i> Nova Disponibilidade
            </a>
        @endif
    </div>

    @if(auth()->user()->role === 'ADM')
        <div class="card mb-4">
            <div class="card-body py-3">
                <form action="{{ route('disponibilidades.index') }}" method="GET" class="row g-2 align-items-end">
                    <div class="col-md-4">
                        <label for="dia_semana" class="form-label mb-1">Filtrar por dia</label>
                        <select id="dia_semana" name="dia_semana" class="form-select form-select-sm">
                            <option value="">Todos os dias</option>
                            <option value="segunda" {{ request('dia_semana') === 'segunda' ? 'selected' : '' }}>Segunda-feira</option>
                            <option value="terca" {{ request('dia_semana') === 'terca' ? 'selected' : '' }}>Terça-feira</option>
                            <option value="quarta" {{ request('dia_semana') === 'quarta' ? 'selected' : '' }}>Quarta-feira</option>
                            <option value="quinta" {{ request('dia_semana') === 'quinta' ? 'selected' : '' }}>Quinta-feira</option>
                            <option value="sexta" {{ request('dia_semana') === 'sexta' ? 'selected' : '' }}>Sexta-feira</option>
                            <option value="sabado" {{ request('dia_semana') === 'sabado' ? 'selected' : '' }}>Sábado</option>
                            <option value="domingo" {{ request('dia_semana') === 'domingo' ? 'selected' : '' }}>Domingo</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                            <i class="bi bi-funnel me-1"></i> Filtrar
                        </button>
                        <a href="{{ route('disponibilidades.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bi bi-x-lg"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($disponibilidades->isEmpty())
        <div class="availability-empty text-center">
            <div class="availability-empty-icon"><i class="bi bi-clock-history"></i></div>
            <h6 class="fw-bold mb-2">Nenhuma disponibilidade cadastrada</h6>
            <p class="text-muted mt-3 mb-3">Defina os horários em que os atendimentos podem acontecer.</p>
            @if(auth()->user()->role !== 'ADM')
                <a href="{{ route('disponibilidades.create') }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-lg me-1"></i> Adicionar horário
                </a>
            @endif
        </div>
    @else
        <div class="availability-list">
            @foreach($disponibilidades as $disp)
                <article class="availability-card">
                    <div class="availability-icon"><i class="bi bi-clock"></i></div>
                    <div class="availability-body">
                        <div class="availability-heading">
                            <div>
                                <h6 class="availability-day">{{ ucfirst($disp->dia_semana) }}</h6>
                                @if(auth()->user()->role === 'ADM')
                                    <span class="availability-owner"><i class="bi bi-person me-1"></i>{{ $disp->user->name ?? 'Sitter não informado' }}</span>
                                @else
                                    <span class="availability-owner">Horário disponível para atendimentos</span>
                                @endif
                            </div>
                            <span class="availability-status"><i class="bi bi-check-circle-fill me-1"></i>Ativo</span>
                        </div>
                        <div class="availability-time">
                            <i class="bi bi-hourglass-split"></i>
                            {{ date('H:i', strtotime($disp->hora_inicio)) }} às {{ date('H:i', strtotime($disp->hora_fim)) }}
                        </div>
                    </div>
                    <div class="availability-actions">
                        <a href="{{ route('disponibilidades.edit', $disp->id) }}" class="btn availability-edit-button">
                            <i class="bi bi-pencil me-1"></i> Editar
                        </a>
                        <form action="{{ route('disponibilidades.destroy', $disp->id) }}" method="POST" onsubmit="return confirm('Deseja excluir esta disponibilidade?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn availability-delete-button">
                                <i class="bi bi-trash me-1"></i> Excluir
                            </button>
                        </form>
                    </div>
                </article>
            @endforeach
        </div>
        <div class="d-flex justify-content-end mt-3">
            {{ $disponibilidades->links() }}
        </div>
    @endif
@endsection
