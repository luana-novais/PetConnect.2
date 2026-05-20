<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - O que é</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
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
                <p>Uma plataforma para conectar tutores, cuidadores e serviços para pets.</p>
            </div>

            <div class="login-body">
                <div class="mb-4">
                    <h2 class="h4">O que é o PetConnect?</h2>
                    <p class="text-muted">
                        O PetConnect é um ambiente seguro para que donos de pets encontrem cuidadores, agendem serviços e gerenciem o cuidado dos seus animais de estimação.
                    </p>
                </div>

                <div class="row gy-4">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border">
                            <h3 class="h6 mb-3">Para quem é?</h3>
                            <ul class="list-unstyled text-muted mb-0">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Tutores que precisam de serviços de pet sitter</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Cuidadores que oferecem hospedagem, passeios e cuidados</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Administradores que gerenciam a plataforma</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3 border">
                            <h3 class="h6 mb-3">O que você pode fazer?</h3>
                            <ul class="list-unstyled text-muted mb-0">
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Registrar perfil e acessar a plataforma</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Agendar serviços e gerenciar pets</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Visualizar disponibilidade e receber solicitações</li>
                                <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i>Avaliar serviços e acompanhar pagamentos</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-4 d-flex flex-column flex-sm-row gap-3">
                    <a href="{{ route('landing') }}" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>Voltar para a entrada
                    </a>
                    <a href="{{ route('auth.login') }}" class="btn btn-login btn-lg">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Entrar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
