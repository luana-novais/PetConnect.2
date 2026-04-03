@extends('app')

@section('title', 'Editar Pet')
@section('page-title', 'Editar Pet')

@push('styles')
    <link href="{{ asset('css/pets/form.css') }}" rel="stylesheet">
@endpush

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pets.index') }}" class="text-decoration-none" style="color:var(--pet-primary)">Pets</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('pets.show', $pet->id) }}" class="text-decoration-none" style="color:var(--pet-primary)">{{ $pet->nome }}</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5 class="fw-bold mb-1" style="color:#1C1917;">Editar: {{ $pet->nome }}</h5>
                <p class="text-muted mb-0" style="font-size:0.88rem;">Atualize as informações do pet.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-eye me-1"></i> Visualizar
                </a>
                <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>

        <form action="{{ route('pets.update', $pet->id) }}" method="POST">
            @csrf
            @method('PUT')

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
                                   value="{{ old('nome', $pet->nome) }}"
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
                                    <option value="{{ $user->id }}"
                                        {{ old('id_user', $pet->id_user) == $user->id ? 'selected' : '' }}>
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
                                   value="{{ old('data_nascimento', $pet->data_nascimento?->format('Y-m-d')) }}"
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
                                <option value="MACHO" {{ old('sexo', $pet->sexo) === 'MACHO' ? 'selected' : '' }}>♂ Macho</option>
                                <option value="FEMEA" {{ old('sexo', $pet->sexo) === 'FEMEA' ? 'selected' : '' }}>♀ Fêmea</option>
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
                                   value="{{ old('peso', $pet->peso) }}"
                                   step="0.01" min="0" max="999.99">
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
                                    <option value="{{ $especie->id }}"
                                        {{ old('id_especie', $pet->id_especie) == $especie->id ? 'selected' : '' }}>
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
                                <option value="">Selecione a raça...</option>
                                @foreach($racas as $raca)
                                    @if($raca->id_especie == old('id_especie', $pet->id_especie))
                                        <option value="{{ $raca->id }}"
                                            {{ old('id_raca', $pet->id_raca) == $raca->id ? 'selected' : '' }}>
                                            {{ $raca->nome }}
                                        </option>
                                    @endif
                                @endforeach
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
                              rows="4">{{ old('observacoes', $pet->observacoes) }}</textarea>
                    @error('observacoes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- Botões --}}
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-outline-secondary px-4">Cancelar</a>
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-check-lg me-1"></i> Salvar Alterações
                </button>
            </div>

        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const selectEspecie  = document.getElementById('id_especie');
    const selectRaca     = document.getElementById('id_raca');
    const todasRacas     = @json($racas);
    const racaAtualId    = {{ $pet->id_raca }};

    selectEspecie.addEventListener('change', function () {
        const especieId = parseInt(this.value);
        selectRaca.innerHTML = '<option value="">Selecione a raça...</option>';

        if (!especieId) return;

        const filtradas = todasRacas.filter(r => r.id_especie === especieId);

        filtradas.forEach(raca => {
            const opt = document.createElement('option');
            opt.value = raca.id;
            opt.textContent = raca.nome;
            if (raca.id === racaAtualId) opt.selected = true;
            selectRaca.appendChild(opt);
        });
    });
</script>
@endpush
