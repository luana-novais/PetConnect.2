<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --pet-primary: #FF6B6B;
            --pet-primary-dark: #E63946;
            --pet-light: #FFE5E5;
            --pet-secondary: #4ECDC4;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, var(--pet-primary) 0%, var(--pet-secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--pet-primary) 0%, var(--pet-primary-dark) 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }

        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .logo-icon {
            font-size: 2.5rem;
        }

        .logo-text h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .login-header p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .login-body {
            padding: 40px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--pet-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
            outline: none;
        }

        .form-control.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group-text {
            border: 2px solid #e0e0e0;
            border-left: none;
            background: white;
            color: #666;
        }

        .form-control:focus + .input-group-text,
        .input-group .form-control:focus + .input-group-text {
            border-color: var(--pet-primary);
        }

        .invalid-feedback {
            display: block;
            font-size: 0.85rem;
            color: #dc3545;
            margin-top: 5px;
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, var(--pet-primary) 0%, var(--pet-primary-dark) 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 107, 107, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            font-size: 0.9rem;
        }

        .remember-forgot a {
            color: var(--pet-primary);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .remember-forgot a:hover {
            color: var(--pet-primary-dark);
            text-decoration: underline;
        }

        .form-check-input {
            border: 2px solid #e0e0e0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--pet-primary);
            border-color: var(--pet-primary);
        }

        .form-check-input:focus {
            border-color: var(--pet-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            font-size: 0.9rem;
            color: #999;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            width: 100%;
            height: 2px;
            background: #e0e0e0;
            z-index: 0;
        }

        .divider span {
            background: white;
            padding: 0 10px;
            position: relative;
            z-index: 1;
        }

        .register-link {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }

        .register-link a {
            color: var(--pet-primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .register-link a:hover {
            color: var(--pet-primary-dark);
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        /* Loading spinner */
        .btn-login.loading {
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid #ffffff;
            border-radius: 50%;
            border-top-color: transparent;
            animation: spinner 0.6s linear infinite;
        }

        @keyframes spinner {
            to {
                transform: rotate(360deg);
            }
        }

        /* Responsivo */
        @media (max-width: 576px) {
            .login-container {
                padding: 15px;
            }

            .login-body {
                padding: 30px 20px;
            }

            .logo-text h1 {
                font-size: 1.5rem;
            }

            .logo-icon {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
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

                <form action="{{ route('auth.login.store') }}" method="POST" id="loginForm">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-2" style="color: var(--pet-primary);"></i>Email
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
                            <i class="bi bi-lock me-2" style="color: var(--pet-primary);"></i>Senha
                        </label>
                        <div class="input-group">
                            <input type="password"
                                   id="password"
                                   name="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="••••••••"
                                   required>
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
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
                    <button type="submit" class="btn btn-login" id="submitBtn">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Senha
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });

        // Validação do formulário
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>
