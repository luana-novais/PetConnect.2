<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <style>
        .login-container { max-width: 580px; }

        .section-divider {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin: 1.5rem 0 1.25rem;
            font-size: 0.78rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            color: #A8A29E;
        }
        .section-divider::before,
        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #E7E5E4;
        }

        .form-group { margin-bottom: 1rem; }
        .form-group .form-label { font-size: 0.875rem; font-weight: 500; color: #44403C; margin-bottom: 0.35rem; }
        .form-control { border-radius: 0.65rem; border-color: #E7E5E4; font-size: 0.9rem; }
        .form-control:focus { border-color: var(--pet-primary); box-shadow: 0 0 0 3px rgba(249,115,22,.12); }
    </style>
</head>
<body class="auth-page">
    <div class="login-container">
        <div class="login-card">

            <div class="login-header">
                <div class="logo-section">
                    <div class="logo-icon">🐾</div>
                    <div class="logo-text"><h1>PetConnect</h1></div>
                </div>
                <p>Crie sua conta agora</p>
            </div>

            <div class="login-body">

                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Erro no cadastro!</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('auth.register.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- ── Dados da conta ── --}}
                    <div class="section-divider">Dados da conta</div>

                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i>Nome completo
                        </label>
                        <input type="text" id="name" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Seu nome completo"
                               value="{{ old('name') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>E-mail
                        </label>
                        <input type="email" id="email" name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="seu@email.com"
                               value="{{ old('email') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="bi bi-people me-1"></i>Perfil
                        </label>
                        <div class="role-selector">
                            <div class="role-option">
                                <input type="radio" id="tutor" name="role" value="CLI"
                                       @if(old('role') === 'CLI') checked @endif required>
                                <label for="tutor" class="role-label">
                                    <i class="bi bi-heart-fill"></i>
                                    <span>Cliente</span>
                                    <small>Procuro pet sitter</small>
                                </label>
                            </div>
                            <div class="role-option">
                                <input type="radio" id="sitter" name="role" value="PRO"
                                       @if(old('role') === 'PRO') checked @endif required>
                                <label for="sitter" class="role-label">
                                    <i class="bi bi-hand-thumbs-up-fill"></i>
                                    <span>Profissional</span>
                                    <small>Ofereço meus serviços</small>
                                </label>
                            </div>
                        </div>
                        @error('role')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <div class="form-group mb-0">
                                <label for="password" class="form-label">
                                    <i class="bi bi-lock me-1"></i>Senha
                                </label>
                                <input type="password" id="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="••••••••" required>
                                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group mb-0">
                                <label for="password_confirmation" class="form-label">
                                    <i class="bi bi-lock-fill me-1"></i>Confirmar senha
                                </label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                       class="form-control" placeholder="••••••••" required>
                            </div>
                        </div>
                    </div>

                    <div class="password-requirements mt-3">
                        <strong>Requisitos de senha:</strong>
                        <ul class="mb-0">
                            <li>✓ Mínimo de 6 caracteres</li>
                            <li>✓ As senhas devem ser iguais</li>
                        </ul>
                    </div>

                    {{-- ── Contato ── --}}
                    <div class="section-divider">Contato <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#C4B5A8;">(opcional)</span></div>

                    <div class="form-group">
                        <label for="numero" class="form-label">
                            <i class="bi bi-telephone me-1"></i>Telefone
                        </label>
                        <input type="text" id="numero" name="numero"
                               class="form-control @error('numero') is-invalid @enderror"
                               placeholder="(11) 99999-9999"
                               value="{{ old('numero') }}">
                        @error('numero')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="foto" class="form-label"><i class="bi bi-camera me-1"></i>Foto do usuário</label>
                        <input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/webp"
                               class="form-control @error('foto') is-invalid @enderror">
                        <small class="text-muted">JPG, PNG ou WEBP, até 5 MB.</small>
                        @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- ── Endereço ── --}}
                    <div class="section-divider">Endereço <span style="font-weight:400;text-transform:none;letter-spacing:0;color:#C4B5A8;">(opcional)</span></div>

                    <div class="form-group">
                        <label for="endereco" class="form-label">
                            <i class="bi bi-geo-alt me-1"></i>Logradouro
                        </label>
                        <input type="text" id="endereco" name="endereco"
                               class="form-control @error('endereco') is-invalid @enderror"
                               placeholder="Rua, número"
                               value="{{ old('endereco') }}">
                        @error('endereco')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group">
                        <label for="complemento" class="form-label">
                            <i class="bi bi-house-door me-1"></i>Complemento
                        </label>
                        <input type="text" id="complemento" name="complemento"
                               class="form-control @error('complemento') is-invalid @enderror"
                               placeholder="Apartamento, bloco, casa, etc."
                               value="{{ old('complemento') }}">
                        @error('complemento')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="row g-2">
                        <div class="col-5">
                            <div class="form-group mb-0">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" id="cidade" name="cidade"
                                       class="form-control @error('cidade') is-invalid @enderror"
                                       placeholder="São Paulo"
                                       value="{{ old('cidade') }}">
                                @error('cidade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group mb-0">
                                <label for="estado" class="form-label">Estado</label>
                                <input type="text" id="estado" name="estado"
                                       class="form-control @error('estado') is-invalid @enderror"
                                       placeholder="SP" maxlength="2"
                                       value="{{ old('estado') }}">
                                @error('estado')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group mb-0">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" id="cep" name="cep"
                                       class="form-control @error('cep') is-invalid @enderror"
                                       placeholder="00000-000"
                                       value="{{ old('cep') }}">
                                @error('cep')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── Botão ── --}}
                    <button type="submit" class="btn btn-login mt-4">
                        <i class="bi bi-person-plus me-2"></i>Criar Conta
                    </button>
                </form>

                <div class="divider"><span>Já tem conta?</span></div>
                <div class="register-link">
                    <a href="{{ route('auth.login') }}">Faça login aqui</a>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>