<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - Bem-vindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('app.css') }}" rel="stylesheet">
    <style>
        body.landing-page {
            background: #f6f8fb;
            min-height: 100vh;
        }
        .hero-panel {
            background: linear-gradient(135deg, #ffffff 0%, #f5f7fb 100%);
            border: 1px solid rgba(249, 115, 22, .08);
            box-shadow: 0 24px 48px rgba(15, 23, 42, .08);
        }
        .hero-panel .gradient-section {
            background: linear-gradient(180deg, #F97316 0%, #EA580C 100%);
            color: #fff;
        }
        .feature-card-modern {
            background: #fff;
            border: 1px solid #e9edf6;
        }
        .badge-soft {
            background: rgba(249, 115, 22, .12);
            color: #EA580C;
        }
    </style>
</head>
<body class="landing-page">
    <div class="container py-5">
        <header class="landing-header d-flex flex-column flex-md-row align-items-center justify-content-between mb-5 gap-3 px-4 py-3 rounded-4 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="brand-mark">🐾</div>
                <div>
                    <a href="{{ route('home') }}" class="brand-title">PetConnect</a>
                </div>
                <div>
                    <p class="mb-0 text-muted small">A plataforma para cuidar dos pets com confiança.</p>
                </div>
            </div>

            <div class="landing-header-actions d-flex gap-2 flex-wrap">
                <a href="{{ route('auth.login') }}" class="btn btn-link landing-login-link">Entrar</a>
                <a href="{{ route('auth.register') }}" class="btn btn-register btn-sm">Cadastrar-se</a>
            </div>
        </header>

        <section id="hero" class="hero-panel rounded-4 overflow-hidden mb-5">
            <div class="row g-0">
                <div class="col-lg-5 gradient-section p-5 d-flex flex-column justify-content-center">
                    <div class="mb-4">
                        <div class="display-6 fw-bold">PetConnect</div>
                        <p class="lead mb-0">A plataforma que reúne tutores, cuidadores e serviços de pets em um só lugar.</p>
                    </div>
                    <div class="mt-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3 fs-4">🐾</div>
                            <div>
                                <strong>Conexões confiáveis</strong>
                                <p class="mb-0 text-white-75">Encontre cuidadores avaliados para passeios, hospedagens e cuidados diários.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <div class="me-3 fs-4">📅</div>
                            <div>
                                <strong>Agenda simplificada</strong>
                                <p class="mb-0 text-white-75">Agende serviços e acompanhe confirmações com rapidez e praticidade.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="me-3 fs-4">💬</div>
                            <div>
                                <strong>Comunicação direta</strong>
                                <p class="mb-0 text-white-75">Troque mensagens com cuidadores para organizar tudo com mais segurança.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7 p-5">
                    <div class="mb-4">
                        <span class="badge badge-soft rounded-pill px-3 py-2 mb-3">Bem-vindo ao PetConnect</span>
                        <h1 class="h2 fw-semibold">Cuidar do seu pet ficou mais fácil.</h1>
                        <p class="text-muted">Conecte seu pet com o melhor cuidado em poucos minutos. Descomplique agendamentos, encontre cuidadores confiáveis e gerencie todos os serviços em um único lugar.</p>
                    </div>
                    <div class="row g-3 mb-4">
                        <div class="col-sm-6">
                            <div class="feature-card-modern rounded-4 p-4 h-100">
                                <div class="mb-3 text-orange fs-3"><i class="bi bi-people-fill"></i></div>
                                <h3 class="h6">Para tutores</h3>
                                <p class="text-muted mb-0">Encontre cuidadores confiáveis, agende serviços e acompanhe cada etapa.</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="feature-card-modern rounded-4 p-4 h-100">
                                <div class="mb-3 text-orange fs-3"><i class="bi bi-briefcase-fill"></i></div>
                                <h3 class="h6">Para cuidadores</h3>
                                <p class="text-muted mb-0">Receba pedidos, organize sua agenda e ofereça serviços profissionais.</p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <a href="{{ route('auth.register') }}" class="btn btn-login btn-lg w-100 w-sm-auto"><i class="bi bi-pencil-square me-2"></i>Cadastrar-se</a>
                        <a href="{{ route('auth.login') }}" class="btn btn-outline-secondary btn-lg w-100 w-sm-auto"><i class="bi bi-box-arrow-in-right me-2"></i>Entrar</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="resources" class="landing-features mt-5 mb-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="feature-icon bg-orange-soft mb-3">
                            <i class="bi bi-calendar-check fs-3 text-orange"></i>
                        </div>
                        <h3 class="h5">Agenda simplificada</h3>
                        <p class="text-muted mb-0">Planeje serviços e mantenha todos os compromissos do seu pet em dia.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="feature-icon bg-orange-soft mb-3">
                            <i class="bi bi-people fs-3 text-orange"></i>
                        </div>
                        <h3 class="h5">Cuidadores confiáveis</h3>
                        <p class="text-muted mb-0">Conheça profissionais avaliados e escolha o que combina com seu pet.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="feature-card h-100 text-center p-4">
                        <div class="feature-icon bg-orange-soft mb-3">
                            <i class="bi bi-star-fill fs-3 text-orange"></i>
                        </div>
                        <h3 class="h5">Avaliações e segurança</h3>
                        <p class="text-muted mb-0">Avaliações verificadas garantem confiança e qualidade nos serviços.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="why" class="landing-benefits mt-5 mb-5">
            <div class="row g-4">
                <div class="col-sm-6">
                    <div class="feature-card-modern rounded-4 p-4 h-100">
                        <div class="mb-3 text-orange fs-3"><i class="bi bi-calendar-check-fill"></i></div>
                        <h3 class="h6">Agendamentos</h3>
                        <p class="text-muted mb-0">Agende passeios, hospedagens e cuidados em poucos passos, com confirmação rápida.</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="feature-card-modern rounded-4 p-4 h-100">
                        <div class="mb-3 text-orange fs-3"><i class="bi bi-heart-pulse"></i></div>
                        <h3 class="h6">Cuidado transparente</h3>
                        <p class="text-muted mb-0">Acompanhe pagamentos, histórico e avaliações com total clareza.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="landing-contact mt-5 py-5 rounded-4 shadow-sm bg-white">
            <div class="row align-items-center gy-4">
                <div class="col-md-8">
                    <h3 class="h4 mb-3">Quer falar com a gente?</h3>
                    <p class="text-muted mb-0">Se você estiver pronto para o próximo passo no cuidado do seu pet, abra o formulário de contato e envie sua mensagem.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <button type="button" class="btn btn-register btn-lg" data-bs-toggle="modal" data-bs-target="#contactModal">
                        Enviar mensagem
                    </button>
                </div>
            </div>
        </section>

        <!-- Modal de Contato -->
        <div class="modal fade" id="contactModal" tabindex="-1" aria-labelledby="contactModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content rounded-4 overflow-hidden border-0 shadow-lg">
                    <div class="modal-header bg-orange-soft px-4 py-3">
                        <div>
                            <h5 class="modal-title text-orange fw-bold" id="contactModalLabel">Contato PetConnect</h5>
                            <p class="mb-0 text-muted small">Envie sua dúvida ou solicitação e nós retornaremos em breve.</p>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body px-4 py-4">
                        <form>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="contactName" class="form-label">Nome</label>
                                    <input type="text" class="form-control" id="contactName" placeholder="Seu nome" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="contactEmail" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="contactEmail" placeholder="seu@email.com" required>
                                </div>
                                <div class="col-12">
                                    <label for="contactMessage" class="form-label">Mensagem</label>
                                    <textarea class="form-control" id="contactMessage" rows="5" placeholder="Escreva sua mensagem..." required></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer px-4 py-3 border-0">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        <a href="mailto:contato@petconnect.com" class="btn btn-register">Enviar email</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-5 text-muted small">PetConnect — Mais atenção, menos preocupações. Seu pet em boas mãos.</div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

    