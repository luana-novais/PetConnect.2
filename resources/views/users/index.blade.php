@extends('layout')

@section('title', 'Usuários')
@section('page-title', 'Usuários')

@section('breadcrumb')
    <li class="breadcrumb-item active">Usuários</li>
@endsection

@section('content')

@if(auth()->user()->role === 'ADM')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h5 class="fw-bold mb-1" style="color:#1C1917;">Lista de Usuários</h5>
            <p class="text-muted mb-0" style="font-size:0.88rem;">Gerencie todos os usuários cadastrados no sistema.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i> Novo Usuário
        </a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="bi bi-people me-2 text-warning"></i>Usuários Cadastrados</span>
            <span class="badge bg-light text-secondary border">{{ $users->count() }} registros</span>
        </div>
        <div class="card-body p-0">
            @if($users->isEmpty())
                <div class="text-center py-5">
                    <div style="font-size:3rem;">🐾</div>
                    <p class="text-muted mt-2 mb-3">Nenhum usuário cadastrado ainda.</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Cadastrar usuário
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Email</th>
                                <th>Perfil</th>
                                <th>Telefone</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->telefone->numero ?? '—' }}</td>
                                    <td class="d-flex gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">Editar</a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este usuário?');">
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
            @endif
        </div>
    </div>
@elseif(in_array(auth()->user()->role, ['PRO', 'CLI']))
    @php $user = auth()->user(); @endphp
    <div class="row justify-content-center">
        <div class="col-xl-8 col-lg-10">

            {{-- Header --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-semibold mb-1">Meu Perfil</h5>
                    <p class="text-muted mb-0" style="font-size:.85rem;">Informações da sua conta no PetConnect.</p>
                </div>
                    <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary d-flex align-items-center gap-2">
                        <i class="bi bi-pencil"></i> Editar Perfil
                    </a>
            </div>

            {{-- Card: Dados Pessoais --}}
            <div class="card mb-4">
                <div class="card-header d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle text-warning"></i>
                    <h6 class="mb-0">Dados Pessoais</h6>
                </div>
                <div class="card-body">

                    {{-- Avatar + Nome --}}
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-semibold flex-shrink-0"
                             style="width:48px;height:48px;background:#FDE8DC;color:#C44A14;font-size:18px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <div class="text-muted" style="font-size:.85rem;">{{ $user->email }}</div>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="text-muted mb-1" style="font-size:.78rem;">ID</div>
                            <div class="fw-semibold">#{{ $user->id }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted mb-1" style="font-size:.78rem;">Nome</div>
                            <div class="fw-semibold">{{ $user->name }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted mb-1" style="font-size:.78rem;">Email</div>
                            <div class="fw-semibold">{{ $user->email }}</div>
                        </div>
                        <div class="col-md-6">
                            <div class="text-muted mb-1" style="font-size:.78rem;">Tipo de Conta</div>
                            <span class="badge"
                                @style([
                                    'background:#EEEDFE; color:#3C3489;' => $user->role == 'PRO',
                                    'background:#f1f0ef; color:#5F5E5A;' => $user->role != 'PRO',
                                ])>
                                {{ $user->role == 'PRO' ? 'Profissional (PRO)' : 'Cliente (CLI)' }}
                            </span>
                        </div>
                        <div class="col-12">
                            <div class="text-muted mb-1" style="font-size:.78rem;">Data de Criação</div>
                            <div class="fw-semibold">
                                {{ $user->created_at?->format('d/m/Y \à\s H:i') ?? 'Não disponível' }}
                            </div>
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
                @if($user->telefone)
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-2 d-flex align-items-center justify-content-center flex-shrink-0"
                         style="width:36px;height:36px;background:#FDE8DC;">
                        <i class="bi bi-phone" style="color:#C44A14;font-size:15px;"></i>
                    </div>
                    <div>
                        <div class="fw-semibold" style="font-size:.9rem;">{{ $user->telefone->numero }}</div>
                    </div>
                </div>
                @else
                <p class="text-center text-muted mb-0" style="font-size:.85rem;">
                    Nenhum telefone cadastrado.
                </p>
                @endif
            </div>
        </div>

            {{-- Card: Endereço --}}
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt text-warning"></i>
                <h6 class="mb-0">Endereço</h6>
            </div>
            <div class="card-body">
                @if($user->endereco)
                <div class="row g-3">
                    <div class="col-12">
                        <div class="text-muted mb-1" style="font-size:.78rem;">Logradouro</div>
                        <div class="fw-semibold">{{ $user->endereco->endereco }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted mb-1" style="font-size:.78rem;">CEP</div>
                        <div class="fw-semibold">{{ $user->endereco->cep }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted mb-1" style="font-size:.78rem;">Cidade</div>
                        <div class="fw-semibold">{{ $user->endereco->cidade }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-muted mb-1" style="font-size:.78rem;">Estado</div>
                        <div class="fw-semibold">{{ $user->endereco->estado }}</div>
                    </div>
                </div>
                @else
                <p class="text-center text-muted mb-0" style="font-size:.85rem;">
                    Nenhum endereço cadastrado.
                </p>
                @endif
            </div>
        </div>

            {{-- Zona de Perigo --}}
            <div class="d-flex justify-content-between align-items-center p-3 rounded-3 border border-danger-subtle">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size:13px;"></i>
                        <span class="text-danger fw-semibold" style="font-size:13px;">Zona de Perigo</span>
                    </div>
                    <p class="text-muted mb-0" style="font-size:.8rem;">
                        Deletar conta — esta ação é irreversível e removerá todos os dados relacionados.
                    </p>
                </div>
                <button class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2"
                        onclick="if(confirm('Tem certeza que deseja deletar sua conta? Esta ação é irreversível.'))
                                 { document.getElementById('deleteForm').submit(); }">
                    <i class="bi bi-trash"></i> Deletar Conta
                </button>
            </div>

            <form id="deleteForm" action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>

        </div>
    </div>
@endif

@endsection