@extends('layout')

@section('title', 'Disponibilidades')
@section('page-title', 'Disponibilidades')

@section('breadcrumb')
    <li class="breadcrumb-item active">Disponibilidades</li>
@endsection

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1">Meus Horários</h5>
            <p class="text-muted mb-0" style="font-size:0.9rem;">Defina os dias e horários em que você está disponível para atendimentos.</p>
        </div>
        <a href="{{ route('disponibilidades.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Nova Disponibilidade
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            @if($disponibilidades->isEmpty())
                <div class="text-center py-5">
                    <div class="fs-1 text-warning">⏰</div>
                    <p class="text-muted mt-3 mb-0">Nenhuma disponibilidade cadastrada.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Dia</th>
                                <th>Início</th>
                                <th>Fim</th>
                                @if(auth()->user()->role === 'ADM')
                                    <th>Pet Sitter</th>
                                @endif
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($disponibilidades as $disp)
                                <tr>
                                    <td>{{ $disp->id }}</td>
                                    <td>{{ ucfirst($disp->dia_semana) }}</td>
                                    <td>{{ date('H:i', strtotime($disp->hora_inicio)) }}</td>
                                    <td>{{ date('H:i', strtotime($disp->hora_fim)) }}</td>
                                    @if(auth()->user()->role === 'ADM')
                                        <td>{{ $disp->user->name ?? '---' }}</td>
                                    @endif
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('disponibilidades.edit', $disp->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('disponibilidades.destroy', $disp->id) }}" method="POST" onsubmit="return confirm('Deseja excluir esta disponibilidade?');">
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
                <div class="card-footer d-flex justify-content-end">
                    {{ $disponibilidades->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
