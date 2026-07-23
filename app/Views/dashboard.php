<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | <?= APP_NAME ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/css/style.css">

</head>

<body>

<div class="dashboard">

    <aside class="sidebar">

        <div class="logo">
            <span>P</span>
        </div>

        <h2><?= APP_NAME ?></h2>

        <nav>

            <a href="<?= BASE_URL ?>/dashboard">Dashboard</a>

            <a href="<?= BASE_URL ?>/users">Usuários</a>

            <a href="<?= BASE_URL ?>/companies">Empresas</a>

            <a href="<?= BASE_URL ?>/branches">Filiais</a>

            <a href="<?= BASE_URL ?>/departments">Setores</a>

            <a href="<?= BASE_URL ?>/teams">Equipes</a>

            <a href="<?= BASE_URL ?>/positions">Cargos</a>

            <a href="<?= BASE_URL ?>/employees">Colaboradores</a>

            <a href="<?= BASE_URL ?>/jobs">Vagas</a>

            <a href="<?= BASE_URL ?>/candidates">Candidatos</a>

            <a href="<?= BASE_URL ?>/surveys">Pesquisas</a>

            <a href="<?= BASE_URL ?>/questions">Perguntas</a>

            <a href="<?= BASE_URL ?>/survey-questions">Montagem de Pesquisas</a>

            <a href="<?= BASE_URL ?>/answers">Respostas</a>

            <a href="<?= BASE_URL ?>/indicators">Indicadores</a>

            <a href="<?= BASE_URL ?>/laboratory">Laboratório Organizacional</a>

            <a href="<?= BASE_URL ?>/oie">OIE</a>

            <a href="<?= BASE_URL ?>/ai">Inteligência Artificial</a>

            <a href="#">Avaliações</a>

            <a href="#">Relatórios</a>

            <a href="#">Configurações</a>

            <a href="<?= BASE_URL ?>/logout">Sair</a>

        </nav>

    </aside>

    <main class="content">

        <header>

            <h1>Dashboard</h1>

            <p>

                Bem-vindo,
                <strong><?= htmlspecialchars($user['name']) ?></strong>

            </p>

        </header>

        <section class="cards">

            <div class="card">

                <h3>Usuários</h3>

                <h2>1</h2>

            </div>

            <div class="card">

                <h3>Empresas</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Vagas</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Filiais</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Setores</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Equipes</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Cargos</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Colaboradores</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Candidatos</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Pesquisas</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Perguntas</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Perguntas em Pesquisas</h3>

                <h2>0</h2>

            </div>

            <div class="card">

                <h3>Respostas Registradas</h3>

                <h2>0</h2>

            </div>

        </section>

        <section class="card">

            <h2>Indicadores Organizacionais</h2>

            <p>
                Índice Geral, classificação, participação e indicadores por
                categoria e dimensão são calculados automaticamente pelo
                Motor de Indicadores a partir das respostas de cada pesquisa.
            </p>

            <a class="btn-primary" href="<?= BASE_URL ?>/indicators">
                Ver Indicadores
            </a>

        </section>

        <section class="card">

            <h2>Laboratório Organizacional</h2>

            <p>
                Gere ambientes sintéticos completos (empresa, colaboradores,
                pesquisas e respostas coerentes com cenários pré-definidos)
                para testar e validar o Motor de Indicadores sem usar dados reais.
            </p>

            <a class="btn-primary" href="<?= BASE_URL ?>/laboratory">
                Abrir Laboratório
            </a>

        </section>

        <section class="card">

            <h2>Organizational Intelligence Engine (OIE)</h2>

            <p>
                O OIE interpreta os indicadores do Motor de Indicadores,
                identifica padrões, tendências e riscos organizacionais
                (turnover, burnout, baixa liderança, baixa comunicação,
                baixo engajamento, conflitos, queda de clima e perda de
                satisfação) e produz recomendações — tudo por regras de
                negócio, sem uso de Inteligência Artificial nesta etapa.
            </p>

            <a class="btn-primary" href="<?= BASE_URL ?>/oie">
                Abrir OIE
            </a>

        </section>

        <section class="card">

            <h2>Inteligência Artificial</h2>

            <p>
                A IA apenas interpreta o contexto já construído pelo OIE
                (indicadores, tendências, riscos e recomendações) e nunca
                recalcula nada. Sem provedor configurado, o sistema
                continua funcionando normalmente usando somente as regras
                do OIE.
            </p>

            <a class="btn-primary" href="<?= BASE_URL ?>/ai">
                Abrir IA
            </a>

        </section>

    </main>

</div>

</body>

</html>