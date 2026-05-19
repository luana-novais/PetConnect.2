<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('app.css') }}" rel="stylesheet">
</head>
<body class="auth-page">
    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-section">
                    <div class="logo-icon">🐾</div>
                    <div class="logo-text">
                        <h1>PetConnect</h1>
                    </div>
                </div>
                <p>Plataforma de Conexão entre Tutores e Pet Sitters</p>
            </div>

            <div class="login-body">
                {{-- Erros de validação --}}
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Erro de autenticação!</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Mensagem de sucesso --}}
                @if(session('success'))
                    <div class="alert alert-success" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('auth.login.store') }}" method="POST">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-2"></i>Email
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="seu@email.com"
                               value="{{ old('email') }}"
                               required
                               autofocus>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Senha --}}
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-2"></i>Senha
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Lembrar-me e Esqueci Senha --}}
                    <div class="remember-forgot">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Lembrar-me
                            </label>
                        </div>
                        <a href="#">Esqueci a senha</a>
                    </div>

                    {{-- Botão Login --}}
                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Entrar na Plataforma
                    </button>
                </form>

                {{-- Divider --}}
                <div class="divider">
                    <span>Não tem conta?</span>
                </div>

                {{-- Link para Registro --}}
                <div class="register-link">
                    <p>Crie uma conta para começar agora mesmo!</p>
                    <a href="{{ route('auth.register') }}" class="btn btn-outline-primary w-100 mt-2">
                        <i class="bi bi-person-plus me-2"></i>Criar Conta
                    </a>
                </div>
            </div>
        </div>
    </div>


    </body>
</html>
