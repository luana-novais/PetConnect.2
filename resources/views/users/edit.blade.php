@extends('layout')

@section('title', 'Editar Perfil')
@section('page-title', 'Editar Perfil')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Perfil</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')

<div class="row justify-content-center">
    <div class="col-xl-8 col-lg-10">

        <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Editar Perfil</h5>
                    <p class="text-muted mb-0" style="font-size:.85rem;">Atualize suas informações cadastradas no PetConnect.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary d-flex align-items-center gap-2">
                        <i class="bi bi-arrow-left"></i> Voltar
                    </a>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-floppy"></i> Salvar
                    </button>
                </div>
            </div>

            @if($errors->any())
            <div class="alert alert-danger mb-4">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li style="font-size:.85rem;">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Card: Dados Pessoais --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle text-warning"></i>
                    <h6 class="mb-0">Dados Pessoais</h6>
                </div>
                <div class="card-body">

                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold flex-shrink-0"
                             style="width:52px;height:52px;background:#FDE8DC;color:#C44A14;font-size:20px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="text-muted" style="font-size:.85rem;">Atualize seu nome e e-mail abaixo.</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.78rem;">Nome completo</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $user->name) }}" placeholder="Seu nome completo">
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.78rem;">E-mail</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $user->email) }}" placeholder="seuemail@exemplo.com">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.78rem;" for="foto">Foto do usuário</label>
                            <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp"
                                   class="form-control @error('foto') is-invalid @enderror">
                            <div class="form-text">JPG, PNG ou WEBP, até 5 MB.</div>
                            @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Card: Telefone --}}
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-telephone text-warning"></i>
        <h6 class="mb-0">Telefone</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" style="font-size:.78rem;">Número</label>
                <input type="text" name="numero"
                       class="form-control @error('numero') is-invalid @enderror"
                       value="{{ old('numero', $telefone?->numero) }}"
                       placeholder="(00) 00000-0000">
                @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

{{-- Card: Endereço --}}
<div class="card mb-4">
    <div class="card-header d-flex align-items-center gap-2">
        <i class="bi bi-geo-alt text-warning"></i>
        <h6 class="mb-0">Endereço</h6>
    </div>
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label" style="font-size:.78rem;">CEP</label>
                <input type="text" name="cep"
                       class="form-control @error('cep') is-invalid @enderror"
                       value="{{ old('cep', $endereco?->cep) }}"
                       placeholder="00000-000">
                @error('cep')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" style="font-size:.78rem;">Cidade</label>
                <input type="text" name="cidade"
                       class="form-control @error('cidade') is-invalid @enderror"
                       value="{{ old('cidade', $endereco?->cidade) }}"
                       placeholder="Sua cidade">
                @error('cidade')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" style="font-size:.78rem;">Estado</label>
                <input type="text" name="estado"
                       class="form-control @error('estado') is-invalid @enderror"
                       value="{{ old('estado', $endereco?->estado) }}"
                       placeholder="Ex: São Paulo">
                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label" style="font-size:.78rem;">Endereço (rua e número)</label>
                <input type="text" name="endereco"
                       class="form-control @error('endereco') is-invalid @enderror"
                       value="{{ old('endereco', $endereco?->endereco) }}"
                       placeholder="Rua, número">
                @error('endereco')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
</div>

            {{-- Card: Alterar Senha --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-lock text-warning"></i>
                    <h6 class="mb-0">Alterar Senha</h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label" style="font-size:.78rem;">Senha atual</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password') is-invalid @enderror"
                                   placeholder="Digite sua senha atual">
                            @error('current_password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.78rem;">Nova senha</label>
                            <input type="password" name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Mínimo 8 caracteres">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" style="font-size:.78rem;">Confirmar nova senha</label>
                            <input type="password" name="password_confirmation"
                                   class="form-control"
                                   placeholder="Repita a nova senha">
                        </div>
                    </div>
                    <p class="text-muted mt-3 mb-0" style="font-size:.78rem;">
                        Deixe os campos de senha em branco para manter a senha atual.
                    </p>
                </div>
            </div>

        </form>

    </div>
</div>

@endsection