@extends('layout')

@section('title', 'Nova Disponibilidade')
@section('page-title', 'Nova Disponibilidade')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('disponibilidades.index') }}" class="text-decoration-none">Disponibilidades</a></li>
    <li class="breadcrumb-item active">Novo Horário</li>
@endsection

@section('content')
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">
            <form action="{{ route('disponibilidades.store') }}" method="POST" class="card">
                @csrf
                <div class="card-header">
                    <h6 class="mb-0">Adicionar Horário</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Dia da Semana</label>
                            <select name="dia_semana" class="form-select @error('dia_semana') is-invalid @enderror" required>
                                <option value="" disabled {{ old('dia_semana') ? '' : 'selected' }}>Selecione</option>
                                <option value="segunda" {{ old('dia_semana') === 'segunda' ? 'selected' : '' }}>Segunda</option>
                                <option value="terca" {{ old('dia_semana') === 'terca' ? 'selected' : '' }}>Terça</option>
                                <option value="quarta" {{ old('dia_semana') === 'quarta' ? 'selected' : '' }}>Quarta</option>
                                <option value="quinta" {{ old('dia_semana') === 'quinta' ? 'selected' : '' }}>Quinta</option>
                                <option value="sexta" {{ old('dia_semana') === 'sexta' ? 'selected' : '' }}>Sexta</option>
                                <option value="sabado" {{ old('dia_semana') === 'sabado' ? 'selected' : '' }}>Sábado</option>
                                <option value="domingo" {{ old('dia_semana') === 'domingo' ? 'selected' : '' }}>Domingo</option>
                            </select>
                            @error('dia_semana')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hora de Início</label>
                            <input type="time" name="hora_inicio" class="form-control @error('hora_inicio') is-invalid @enderror" value="{{ old('hora_inicio') }}" required>
                            @error('hora_inicio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Hora de Término</label>
                            <input type="time" name="hora_fim" class="form-control @error('hora_fim') is-invalid @enderror" value="{{ old('hora_fim') }}" required>
                            @error('hora_fim')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex gap-2 justify-content-end">
                    <a href="{{ route('disponibilidades.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Salvar Disponibilidade</button>
                </div>
            </form>
        </div>
    </div>
@endsection
