<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetConnect - Bem-vindo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('app.css') }}" rel="stylesheet">
</head>
<body class="landing-page">
    <div class="container py-5">
        <header class="landing-header d-flex flex-column flex-md-row align-items-center justify-content-between mb-5 gap-3 px-4 py-3 rounded-4 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="brand-mark">🐾</div>
                <div>
                    <a href="{{ route('home') }}" class="brand-title">PetConnect</a>
                    <p class="mb-0 text-muted small">A plataforma para cuidar dos pets com confiança.</p>
                </div>
            </div>

            <nav class="landing-nav d-none d-md-flex align-items-center gap-4">
                <a href="#hero" class="landing-nav-link">Início</a>
                <a href="#features" class="landing-nav-link">Recursos</a>
                <a href="{{ route('about') }}" class="landing-nav-link">Sobre</a>
                <a href="#contact" class="landing-nav-link">Contato</a>
            </nav>

            <div class="landing-header-actions d-flex gap-2 flex-wrap">
                <a href="{{ route('auth.login') }}" class="btn btn-link landing-login-link">Entrar</a>
                <a href="{{ route('auth.register') }}" class="btn btn-register btn-sm">Cadastrar-se</a>
            </div>
        </header>

        <section id="hero" class="landing-hero card shadow-lg overflow-hidden position-relative">
            <div class="decor-circle decor-circle-1"></div>
            <div class="decor-circle decor-circle-2"></div>
            <div class="row g-0 align-items-center">
                <div class="col-lg-6 hero-copy p-5 position-relative">
                    <span class="badge bg-orange-soft text-orange mb-3">Plataforma de cuidados para pets</span>
                    <h2 class="display-5 fw-bold mb-4">Conecte seu pet com o melhor cuidado em poucos minutos.</h2>
                    <p class="text-muted fs-5 mb-4">Descomplique agendamentos, encontre cuidadores confiáveis e gerencie todos os serviços do seu animal em um único lugar.</p>

                    <div class="d-flex flex-column flex-sm-row gap-3 mb-4">
                        <a href="{{ route('auth.register') }}" class="btn btn-login btn-lg" id='botao-comecar-agora'>Começar agora</a>
                        <a href="{{ route('about') }}" class="btn btn-outline-register btn-lg">Veja como funciona</a>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <div class="info-box p-3 rounded-3 border bg-white shadow-sm">
                                <i class="bi bi-clock-history fs-3 text-orange"></i>
                                <div>
                                    <strong>Agendamentos ágeis</strong>
                                    <p class="text-muted mb-0">Marque serviços para seu pet de forma rápida e segura.</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="info-box p-3 rounded-3 border bg-white shadow-sm">
                                <i class="bi bi-star fs-3 text-orange"></i>
                                <div>
                                    <strong>Cuidadores confiáveis</strong>
                                    <p class="text-muted mb-0">Selecione profissionais com notas e comentários verificados.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 hero-visual p-4">
                    <div class="hero-image-wrap mx-auto position-relative">
                        <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=900&q=80" alt="Pets felizes" class="img-fluid rounded-4 shadow-lg hero-img">
                        <div class="hero-card mini-card top-card">
                            <i class="bi bi-chat-left-text-fill text-orange"></i>
                            <div>
                                <strong>Feedback real</strong>
                                <small class="text-muted">Avaliações 5 estrelas de donos satisfeitos.</small>
                            </div>
                        </div>
                        <div class="hero-card mini-card bottom-card">
                            <i class="bi bi-calendar2-check-fill text-orange"></i>
                            <div>
                                <strong>Agendamentos flexíveis</strong>
                                <small class="text-muted">Marque horários conforme sua rotina.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="landing-features mt-5">
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
                            <i class="bi bi-heart-pulse fs-3 text-orange"></i>
                        </div>
                        <h3 class="h5">Cuidado transparente</h3>
                        <p class="text-muted mb-0">Acompanhe pagamentos, histórico e avaliações com clareza.</p>
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
