<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - Cadastro</title>
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
            padding: 20px;
        }

        .register-container {
            width: 100%;
            max-width: 500px;
        }

        .register-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .register-header {
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
        }

        .register-header p {
            margin: 0;
            font-size: 0.95rem;
            opacity: 0.95;
        }

        .register-body {
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

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--pet-primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 107, 0.25);
            outline: none;
        }

        .form-control.is-invalid:focus, .form-select.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .invalid-feedback {
            display: block;
            font-size: 0.85rem;
            color: #dc3545;
            margin-top: 5px;
        }

        .btn-register {
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

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(255, 107, 107, 0.4);
        }

        .btn-register:active {
            transform: translateY(0);
        }

        .role-selector {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 10px;
        }

        .role-option {
            position: relative;
        }

        .role-option input[type="radio"] {
            display: none;
        }

        .role-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 600;
            color: #666;
        }

        .role-option input[type="radio"]:checked + .role-label {
            border-color: var(--pet-primary);
            background-color: var(--pet-light);
            color: var(--pet-primary);
        }

        .role-label:hover {
            border-color: var(--pet-secondary);
            background-color: #f5f5f5;
        }

        .role-label i {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .password-requirements {
            background: #f5f5f5;
            border-left: 4px solid var(--pet-secondary);
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            color: #666;
        }

        .password-requirements li {
            margin-bottom: 5px;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            font-size: 0.9rem;
            color: #999;
        }

        .login-link {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
        }

        .login-link a {
            color: var(--pet-primary);
            font-weight: 600;
            text-decoration: none;
        }

        .login-link a:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        /* Responsivo */
        @media (max-width: 576px) {
            .register-container {
                padding: 0;
            }

            .register-body {
                padding: 30px 20px;
            }

            .logo-text h1 {
                font-size: 1.5rem;
            }

            .logo-icon {
                font-size: 2rem;
            }

            .role-selector {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <div class="logo-section">
                    <div class="logo-icon">🐾</div>
                    <div class="logo-text">
                        <h1>PetConnect</h1>
                    </div>
                </div>
                <p>Crie sua conta agora</p>
            </div>

            <div class="register-body">
                {{-- Erros de validação --}}
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

                <form action="{{ route('auth.register.store') }}" method="POST" id="registerForm">
                    @csrf

                    {{-- Nome Completo --}}
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-2" style="color: var(--pet-primary);"></i>Nome Completo
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               placeholder="Seu nome completo"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

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
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tipo de Usuário --}}
                    <div class="form-group">
                        <label class="form-label" style="display: block;">
                            <i class="bi bi-question-circle me-2" style="color: var(--pet-primary);"></i>Qual é seu perfil?
                        </label>
                        <div class="role-selector">
                            <div class="role-option">
                                <input type="radio" id="admin" name="role" value="ADM" 
                                       @if(old('role') === 'ADM') checked @endif required>
                                <label for="admin" class="role-label">
                                    <i class="bi bi-shield-check"></i>
                                    <span>Administrador</span>
                                    <small style="font-size: 0.75rem; font-weight: normal; margin-top: 5px;">Gerencie a plataforma</small>
                                </label>
                            </div>
                            <div class="role-option">
                                <input type="radio" id="tutor" name="role" value="CLI" 
                                       @if(old('role') === 'CLI') checked @endif required>
                                <label for="tutor" class="role-label">
                                    <i class="bi bi-heart-fill"></i>
                                    <span>Cliente</span>
                                    <small style="font-size: 0.75rem; font-weight: normal; margin-top: 5px;">Procuro pet sitter</small>
                                </label>
                            </div>
                            <div class="role-option">
                                <input type="radio" id="sitter" name="role" value="PRO" 
                                       @if(old('role') === 'PRO') checked @endif required>
                                <label for="sitter" class="role-label">
                                    <i class="bi bi-hand-thumbs-up-fill"></i>
                                    <span>Profissional</span>
                                    <small style="font-size: 0.75rem; font-weight: normal; margin-top: 5px;">Ofereço meus serviços</small>
                                </label>
                            </div>
                        </div>
                        @error('role')
                            <div class="invalid-feedback" style="display: block; margin-top: 10px;">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Senha --}}
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-2" style="color: var(--pet-primary);"></i>Senha
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

                    {{-- Confirmar Senha --}}
                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock me-2" style="color: var(--pet-primary);"></i>Confirmar Senha
                        </label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="form-control"
                               placeholder="••••••••"
                               required>
                    </div>

                    {{-- Requisitos de Senha --}}
                    <div class="password-requirements">
                        <strong>Requisitos de senha:</strong>
                        <ul class="mb-0">
                            <li>✓ Mínimo de 6 caracteres</li>
                            <li>✓ As senhas devem ser iguais</li>
                        </ul>
                    </div>

                    {{-- Botão Cadastro --}}
                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-person-plus me-2"></i>Criar Conta
                    </button>
                </form>

                {{-- Divider --}}
                <div class="divider">Já tem conta?</div>

                {{-- Link para Login --}}
                <div class="login-link">
                    <a href="{{ route('auth.login') }}" class="btn btn-outline-primary w-100">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Fazer Login
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
