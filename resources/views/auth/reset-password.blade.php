<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - Nova senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="{{ asset('app.css') }}" rel="stylesheet">
</head>
<body class="auth-page">
    <main class="login-container">
        <section class="login-card">
            <header class="login-header">
                <div class="logo-section">
                    <div class="logo-icon">🐾</div>
                    <div class="logo-text"><h1>PetConnect</h1></div>
                </div>
                <p>Crie uma nova senha para sua conta</p>
            </header>

            <div class="login-body">
                <h2 class="h4 mb-2">Redefinir senha</h2>
                <p class="text-secondary mb-4">Escolha uma senha nova e segura.</p>

                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form action="{{ route('password.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-2"></i>E-mail
                        </label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               placeholder="seu@email.com"
                               value="{{ old('email', $email) }}"
                               autocomplete="email"
                               required
                               autofocus>
                    </div>

                    <div class="form-group mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-2"></i>Nova senha
                        </label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               autocomplete="new-password"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-2"></i>Confirme a nova senha
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               autocomplete="new-password"
                               required>
                    </div>

                    <button type="submit" class="btn btn-login">
                        <i class="bi bi-check2-circle me-2"></i>Salvar nova senha
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('auth.login') }}" class="text-decoration-none" style="color: var(--pet-primary)">
                        <i class="bi bi-arrow-left me-1"></i>Voltar para o login
                    </a>
                </div>
            </div>
        </section>
    </main>
</body>
</html>
