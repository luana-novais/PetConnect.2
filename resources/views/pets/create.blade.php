@extends('layouts.app')

@section('title', 'Novo Pet')
@section('page-title', 'Novo Pet')

@push('styles')
    <link href="{{ asset('css/pets/form.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pets.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Pets</a>
    </li>
    <li class="breadcrumb-item active">Novo</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1" style="color:#1C1917;">Cadastrar Novo Pet</h5>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Preencha as informações do pet abaixo.</p>
            </div>
            <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i> Voltar
            </a>
        </div>

        <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Erros gerais --}}
            @if($errors->any())
                <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
                    <i class="bi bi-exclamation-triangle-fill mt-1"></i>
                    <div>
                        <strong>Atenção!</strong> Corrija os erros abaixo:
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $error)
                                <li style="font-size:0.88rem;">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- Informações básicas --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-info-circle me-2 text-warning"></i>Informações Básicas
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="nome" class="form-label">Nome do Pet <span class="text-danger">*</span></label>
                            <input type="text"
                                   id="nome"
                                   name="nome"
                                   class="form-control @error('nome') is-invalid @enderror"
                                   value="{{ old('nome') }}"
                                   placeholder="Ex: Rex, Mia, Bob..."
                                   required>
                            @error('nome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="id_user" class="form-label">Dono <span class="text-danger">*</span></label>
                            <select id="id_user" name="id_user"
                                    class="form-select @error('id_user') is-invalid @enderror" required>
                                <option value="">Selecione o dono...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('id_user') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('id_user')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                            <input type="date"
                                   id="data_nascimento"
                                   name="data_nascimento"
                                   class="form-control @error('data_nascimento') is-invalid @enderror"
                                   value="{{ old('data_nascimento') }}"
                                   max="{{ date('Y-m-d') }}">
                            @error('data_nascimento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                            <select id="sexo" name="sexo"
                                    class="form-select @error('sexo') is-invalid @enderror" required>
                                <option value="">Selecione...</option>
                                <option value="MACHO" {{ old('sexo') === 'MACHO' ? 'selected' : '' }}>♂ Macho</option>
                                <option value="FEMEA" {{ old('sexo') === 'FEMEA' ? 'selected' : '' }}>♀ Fêmea</option>
                            </select>
                            @error('sexo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="peso" class="form-label">Peso (kg)</label>
                            <input type="number"
                                   id="peso"
                                   name="peso"
                                   class="form-control @error('peso') is-invalid @enderror"
                                   value="{{ old('peso') }}"
                                   step="0.01"
                                   min="0"
                                   max="999.99"
                                   placeholder="Ex: 4.50">
                            @error('peso')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Espécie e raça --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-tag me-2 text-warning"></i>Espécie e Raça
                </div>
                <div class="card-body">
                    <div class="row g-3">

                        <div class="col-md-6">
                            <label for="id_especie" class="form-label">Espécie <span class="text-danger">*</span></label>
                            <select id="id_especie" name="id_especie"
                                    class="form-select @error('id_especie') is-invalid @enderror" required>
                                <option value="">Selecione a espécie...</option>
                                @foreach($especies as $especie)
                                    <option value="{{ $especie->id }}" {{ old('id_especie') == $especie->id ? 'selected' : '' }}>
                                        {{ $especie->tipo_pet }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_especie')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="id_raca" class="form-label">Raça <span class="text-danger">*</span></label>
                            <select id="id_raca" name="id_raca"
                                    class="form-select @error('id_raca') is-invalid @enderror" required>
                                <option value="">Primeiro selecione a espécie...</option>
                                @if(old('id_especie'))
                                    @foreach($racas as $raca)
                                        @if($raca->id_especie == old('id_especie'))
                                            <option value="{{ $raca->id }}" {{ old('id_raca') == $raca->id ? 'selected' : '' }}>
                                                {{ $raca->nome }}
                                            </option>
                                        @endif
                                    @endforeach
                                @endif
                            </select>
                            @error('id_raca')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Observações --}}
            <div class="card mb-4">
                <div class="card-header">
                    <i class="bi bi-journal-text me-2 text-warning"></i>Observações
                </div>
                <div class="card-body">
                    <label for="observacoes" class="form-label">Observações</label>
                    <textarea id="observacoes"
                              name="observacoes"
                              class="form-control @error('observacoes') is-invalid @enderror"
                              rows="4"
                              placeholder="Alergias, comportamento, necessidades especiais...">{{ old('observacoes') }}</textarea>
                    @error('observacoes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Botões --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Salvar Pet
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Carrega raças dinamicamente ao selecionar espécie
    const selectEspecie = document.getElementById('id_especie');
    const selectRaca    = document.getElementById('id_raca');

    // Todas as raças passadas pelo controller
    const todasRacas = @json($racas);

    selectEspecie.addEventListener('change', function () {
        const especieId = parseInt(this.value);
        selectRaca.innerHTML = '<option value="">Selecione a raça...</option>';

        if (!especieId) return;

        const filtradas = todasRacas.filter(r => r.id_especie === especieId);

        if (filtradas.length === 0) {
            selectRaca.innerHTML = '<option value="">Nenhuma raça cadastrada</option>';
            return;
        }

        filtradas.forEach(raca => {
            const opt = document.createElement('option');
            opt.value = raca.id;
            opt.textContent = raca.nome;
            selectRaca.appendChild(opt);
        });
    });
</script>
@endpush
