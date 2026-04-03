@extends('layout')
@extends('layout')

@section('title', 'Editar Pet')
@section('page-title', 'Editar Pet')

@section('breadcrumb')
    <li class="breadcrumb-item">
        <a href="{{ route('pets.index') }}" class="text-decoration-none">Pets</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{ route('pets.show', $pet->id) }}" class="text-decoration-none">{{ $pet->nome }}</a>
    </li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">Editar: {{ $pet->nome }}</h3>
            <div class="d-flex gap-2">
                <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-outline-secondary btn-sm">Visualizar</a>
                <a href="{{ route('pets.index') }}" class="btn btn-outline-secondary btn-sm">Voltar</a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">
                <strong>Erros:</strong>
                <ul class="mb-0 mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="post" action="/pets/{{ $pet->id }}">
            @csrf
            @METHOD('PUT')

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Informações Básicas</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome do Pet</label>
                            <input type="text" name="nome" class="form-control" value="{{ old('nome', $pet->nome) }}" required>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Dono</label>
                            <select name="id_user" class="form-select" required>
                                <option value="">Selecione...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('id_user', $pet->id_user) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Data de Nascimento</label>
                            <input type="date" name="data_nascimento" class="form-control" value="{{ old('data_nascimento', $pet->data_nascimento?->format('Y-m-d')) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Sexo</label>
                            <select name="sexo" class="form-select" required>
                                <option value="">Selecione...</option>
                                <option value="MACHO" {{ old('sexo', $pet->sexo) === 'MACHO' ? 'selected' : '' }}>Macho</option>
                                <option value="FEMEA" {{ old('sexo', $pet->sexo) === 'FEMEA' ? 'selected' : '' }}>Fêmea</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Peso (kg)</label>
                            <input type="number" name="peso" class="form-control" value="{{ old('peso', $pet->peso) }}" step="0.01">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Espécie e Raça</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Espécie</label>
                            <select name="id_especie" class="form-select" required>
                                <option value="">Selecione...</option>
                                @foreach($especie as $especie)
                                    <option value="{{ $especie->id }}" {{ old('id_especie', $pet->id_especie) == $especie->id ? 'selected' : '' }}>
                                        {{ $especie->tipo_pet }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Raça</label>
                            <select name="id_raca" class="form-select" required>
                                <option value="">Selecione...</option>
                                @foreach($racas as $raca)
                                    <option value="{{ $raca->id }}" {{ old('id_raca', $pet->id_raca) == $raca->id ? 'selected' : '' }}>
                                        {{ $raca->nome }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Observações</h6>
                </div>
                <div class="card-body">
                    <textarea name="observacoes" class="form-control" rows="4">{{ old('observacoes', $pet->observacoes) }}</textarea>
                </div>
            </div>

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('pets.show', $pet->id) }}" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
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
    const racaAtualId    = @json($pet->id_raca ?? null);

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
