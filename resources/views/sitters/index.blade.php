@extends('layout')

@section('title', 'Sitters')
@section('page-title', 'Sitters')

@push('styles')
    <link href="{{ asset('css/sitters/index.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item active">Sitters</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 sitter-page-heading">
    <div>
        <h5 class="fw-bold mb-1" style="color:#1C1917;">Encontre um sitter</h5>
        <p class="text-muted mb-0" style="font-size:.88rem;">Profissionais disponíveis para cuidar do seu pet.</p>
    </div>
    <span class="sitter-total"><i class="bi bi-person-check me-1"></i>{{ $sitters->count() }} disponíveis</span>
</div>

@if($sitters->isEmpty())
    <div class="sitter-empty text-center">
        <div class="sitter-empty-icon"><i class="bi bi-person-x"></i></div>
        <h6 class="fw-bold mb-2">Nenhum sitter disponível</h6>
        <p class="text-muted mb-0">Ainda não encontramos profissionais com horários cadastrados.</p>
    </div>
@else
    <div class="sitter-grid">
        @foreach($sitters as $sitter)
            <article class="sitter-card">
                <div class="sitter-card-top">
                    @if($sitter->fotoUsuario)
                        <img src="{{ asset('storage/' . $sitter->fotoUsuario->url_foto) }}"
                             alt="Foto de {{ $sitter->name }}" class="sitter-avatar">
                    @else
                        <span class="sitter-avatar sitter-avatar-fallback">{{ strtoupper(substr($sitter->name, 0, 1)) }}</span>
                    @endif
                    <span class="sitter-status"><i class="bi bi-circle-fill me-1"></i>Disponível</span>
                </div>

                <div class="sitter-card-content">
                    <h6 class="sitter-name">{{ $sitter->name }}</h6>
                    <span class="sitter-subtitle"><i class="bi bi-patch-check-fill me-1"></i>Pet sitter verificado</span>

                    <div class="sitter-rating">
                        <i class="bi bi-star-fill"></i>
                        @if($sitter->avaliacoesRecebidas->isNotEmpty())
                            {{ number_format($sitter->avaliacoesRecebidas->avg('nota'), 1, ',', '.') }}
                            <span>({{ $sitter->avaliacoesRecebidas->count() }} avaliação{{ $sitter->avaliacoesRecebidas->count() !== 1 ? 'ões' : '' }})</span>
                        @else
                            <span>Sem avaliações</span>
                        @endif
                    </div>

                    <div class="sitter-section-label">Serviços oferecidos</div>
                    <div class="sitter-services">
                        @forelse($sitter->servicos as $servico)
                            <span class="sitter-service">
                                {{ $servico->nome }}
                                @if($servico->valor_servico !== null)
                                    <small>R$ {{ number_format($servico->valor_servico, 2, ',', '.') }}</small>
                                @endif
                            </span>
                        @empty
                            <span class="text-muted" style="font-size:.82rem;">Nenhum serviço cadastrado.</span>
                        @endforelse
                    </div>

                    <div class="sitter-availability">
                        <i class="bi bi-clock"></i>
                        <span>{{ $sitter->disponibilidades->map(fn ($disponibilidade) => ucfirst($disponibilidade->dia_semana))->unique()->join(', ') }}</span>
                    </div>
                </div>

                <div class="sitter-actions">
                    <a href="{{ route('agendamentos.create', ['sitter_id' => $sitter->id]) }}" class="btn sitter-book-button">
                        <i class="bi bi-calendar-plus me-1"></i> Agendar sitter
                    </a>
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection