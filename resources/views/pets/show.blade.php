@extends('layout')

@section('title', $pet->nome)
@section('page-title', $pet->nome)

@push('styles')
    <link href="{{ asset('css/pets/show.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pets.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Pets</a>
    </li>
    <li class="breadcrumb-item active">{{ $pet->nome }}</li>
@endsection

@section('content')

<div class="pet-detail-page">
<div class="d-flex justify-content-between align-items-center mb-4 pet-detail-heading">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Detalhes do Pet</h5>
        <p class="text-muted mb-0" style="font-size:0.88rem;">Informações completas de {{ $pet->nome }}.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pets.edit', $pet->id) }}" class="btn btn-primary btn-sm">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
        <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Voltar
        </a>
    </div>
</div>

<div class="row g-4">

    {{-- Card principal --}}
    <div class="col-lg-4">
        <div class="card h-100 pet-profile-card">
            <div class="card-body text-center py-4">

                {{-- Foto principal ou avatar padrão --}}
                @if($fotos->isNotEmpty())
                    <img src="{{ asset('storage/' . $fotos->first()->url_foto) }}"
                         alt="Foto de {{ $pet->nome }}" class="pet-avatar-lg pet-avatar-image">
                @else
                    <div class="pet-avatar-lg">
                        {{ $pet->especie->tipo_pet === 'GATO' ? '🐱' : ($pet->especie->tipo_pet === 'CACHORRO' ? '🐶' : '🐾') }}
                    </div>
                @endif

                <h1 class="pet-name">{{ $pet->nome }}</h1>
                <div class="pet-type-label">{{ $pet->especie->tipo_pet }} · {{ $pet->raca->nome }}</div>

                <div class="mb-3">
                    @if($pet->sexo === 'MACHO')
                        <span class="badge-sexo macho">♂ Macho</span>
                    @else
                        <span class="badge-sexo femea">♀ Fêmea</span>
                    @endif
                </div>

                <div class="d-flex justify-content-center gap-3 mb-4 pet-species-stats">
                    <div class="text-center">
                        <div class="fw-bold" style="color:#1C1917;">{{ $pet->especie->tipo_pet }}</div>
                        <div class="text-muted" style="font-size:0.78rem;">Espécie</div>
                    </div>
                    <div class="vr"></div>
                    <div class="text-center">
                        <div class="fw-bold" style="color:#1C1917;">{{ $pet->raca->nome }}</div>
                        <div class="text-muted" style="font-size:0.78rem;">Raça</div>
                    </div>
                </div>

                <hr class="my-3 pet-rule">

                {{-- Dono --}}
                <div class="owner-card">
                    <div class="owner-avatar">
                        {{ strtoupper(substr($pet->user->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="owner-name">{{ $pet->user->name }}</div>
                        <div class="owner-email">{{ $pet->user->email }}</div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Informações detalhadas --}}
    <div class="col-lg-8">

        {{-- Dados físicos --}}
        <div class="card mb-4 pet-detail-card">
            <div class="card-header">
                <i class="bi bi-clipboard2-data me-2 text-warning"></i>Dados Físicos
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="section-divider">Data de Nascimento</div>
                        <div class="fw-semibold" style="color:#1C1917;">
                            @if($pet->data_nascimento)
                                {{ $pet->data_nascimento->format('d/m/Y') }}
                                <div class="text-muted fw-normal" style="font-size:0.82rem;">
                                    @php
                                        $idade = $pet->data_nascimento->diff(now());
                                        $idadeAnos = $idade->y;
                                        $idadeMeses = $idade->m;
                                        $textoIdade = [];

                                        if ($idadeAnos > 0) {
                                            $textoIdade[] = $idadeAnos . ' ' . ($idadeAnos === 1 ? 'ano' : 'anos');
                                        }

                                        if ($idadeMeses > 0) {
                                            $textoIdade[] = $idadeMeses . ' ' . ($idadeMeses === 1 ? 'mês' : 'meses');
                                        }

                                        $textoIdadeFinal = $textoIdade ? implode(' e ', $textoIdade) : 'Menos de 1 mês';
                                    @endphp
                                    {{ $textoIdadeFinal }}
                                </div>
                            @else
                                <span class="text-muted">Não informado</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="section-divider">Peso</div>
                        <div class="fw-semibold" style="color:#1C1917;">
                            @if($pet->peso)
                                {{ number_format($pet->peso, 2, ',', '.') }} <span class="text-muted fw-normal">kg</span>
                            @else
                                <span class="text-muted">Não informado</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="section-divider">Cadastrado em</div>
                        <div class="fw-semibold" style="color:#1C1917;">
                            {{ $pet->created_at->format('d/m/Y') }}
                            <div class="text-muted fw-normal" style="font-size:0.82rem;">
                                {{ $pet->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Observações --}}
        @if($pet->observacoes)
        <div class="card mb-4 pet-detail-card pet-notes-card">
            <div class="card-header">
                <i class="bi bi-journal-text me-2 text-warning"></i>Observações
            </div>
            <div class="card-body">
                <p class="mb-0" style="color:#44403C;line-height:1.7;">{{ $pet->observacoes }}</p>
            </div>
        </div>
        @endif

        {{-- Fotos --}}
        @if($fotos->isNotEmpty())
        <div class="card mb-4 pet-detail-card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <span><i class="bi bi-images me-2 text-warning"></i>Fotos do Pet</span>
                <span class="pet-gallery-count">{{ $fotos->count() }}</span>
            </div>
            <div class="card-body pet-gallery-body">
                <div class="row g-3 pet-gallery">
                    @foreach($fotos as $foto)
                        <div class="col-sm-6 col-md-4 pet-gallery-item">
                            <img src="{{ asset('storage/' . $foto->url_foto) }}"
                                 alt="{{ $foto->nome_arquivo }}"
                                 class="img-fluid rounded w-100"
                                 loading="lazy">
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- Histórico de agendamentos --}}
        <div class="card pet-detail-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-calendar3 me-2 text-warning"></i>Últimos Agendamentos</span>
                <span class="badge bg-light text-secondary border">{{ $pet->agendamentos->count() }}</span>
            </div>
            <div class="card-body p-0">
                @if($pet->agendamentos->isEmpty())
                    <div class="text-center py-4">
                        <p class="text-muted mb-0" style="font-size:0.88rem;">Nenhum agendamento encontrado.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Serviço</th>
                                    <th>Data</th>
                                    <th>Sitter</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pet->agendamentos->take(5) as $ag)
                                <tr>
                                    <td style="font-size:0.88rem;">{{ $ag->servico->nome }}</td>
                                    <td style="font-size:0.88rem;">{{ $ag->data_agendamento?->format('d/m/Y') }}</td>
                                    <td style="font-size:0.88rem;">{{ $ag->sitter->name }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $ag->status }}">
                                            {{ ucfirst($ag->status) }}
                                        </span>
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
</div>

{{-- Zona de perigo --}}
<div class="danger-zone">
    <div class="card-header">
        <i class="bi bi-exclamation-triangle me-2"></i>Zona de Perigo
    </div>
    <div class="card-body d-flex align-items-center justify-content-between">
        <div>
            <div class="fw-semibold" style="color:#1C1917;">Excluir este pet</div>
            <div class="text-muted" style="font-size:0.85rem;">Esta ação é irreversível e removerá todos os dados relacionados.</div>
        </div>
        <form action="{{ route('pets.destroy', $pet->id) }}" method="POST"
              onsubmit="return confirm('Tem certeza que deseja excluir {{ $pet->nome }}? Esta ação não pode ser desfeita.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm px-3">
                <i class="bi bi-trash me-1"></i> Excluir Pet
            </button>
        </form>
    </div>
</div>

</div>

@endsection