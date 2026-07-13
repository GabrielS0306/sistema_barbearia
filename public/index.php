<?php 

    // Carrega o autoload do Composer (DOMPDF e outras dependências)
    if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
        require_once __DIR__ . '/../vendor/autoload.php';
    }

    // public/index.php

    // carrega classes de infraestrutura
    require_once __DIR__ . '/../core/Database.php';
    require_once __DIR__ . '/../core/Router.php';
    require_once __DIR__ . '/../core/Upload.php';

    // Autoload simples: carrega Models e Controllers automaticamente
    spl_autoload_register(function (string $class) {
        $caminhos = [
            __DIR__ . "/../app/models/{$class}.php",
            __DIR__ . "/../app/controllers/{$class}.php",
        ];

        foreach ($caminhos as $caminho) {
            if (file_exists($caminho)) {
                require_once $caminho;
                return;
            }
        }
    });

    session_start();

    $router = new Router();

    // Rotas -> Irá sendo adicionado as rotas conforme o projeto for crescendo

    // Rotas públicas
    $router->get('/login', 'AuthController::login');
    $router->post('/login', 'AuthController::login');
    $router->get('/register', 'AuthController::register');
    $router->post('/register', 'AuthController::register');
    $router->get('/logout', 'AuthController::logout');

    // Rotas protegidas — cliente
    $router->get('/agendamento/novo', 'AgendamentoController::novo', ['cliente', 'admin']);
    $router->post('/agendamento/novo', 'AgendamentoController::novo', ['cliente', 'admin']);
    $router->get('/agendamento/meus', 'AgendamentoController::meus', ['cliente', 'admin']);
    $router->get('/agendamento/comprovante', 'AgendamentoController::comprovante', ['cliente']);
    $router->get('/agendamento/pagamento', 'AgendamentoController::pagamento', ['cliente']);
    $router->post('/agendamento/confirmar-pagamento', 'AgendamentoController::confirmarPagamento', ['cliente']);

    // Rotas protegidas — barbeiro
    $router->get('/barbeiro/agenda', 'BarbeiroController::agenda', ['barbeiro', 'admin']);
    $router->post('/barbeiro/status', 'BarbeiroController::atualizarStatus', ['barbeiro', 'admin']);
    $router->get('/barbeiro/agenda/pdf', 'BarbeiroController::agendaPdf', ['barbeiro', 'admin']);

   // Rotas protegidas — admin
    $router->get('/admin/dashboard', 'AdminController::dashboard', ['admin']);

    $router->get('/admin/barbeiros', 'BarbeiroAdminController::index', ['admin']);
    $router->get('/admin/barbeiros/novo-usuario', 'BarbeiroAdminController::novoUsuario', ['admin']);
    $router->post('/admin/barbeiros/novo-usuario', 'BarbeiroAdminController::novoUsuario', ['admin']);
    $router->get('/admin/barbeiros/novo-perfil', 'BarbeiroAdminController::novoPerfil', ['admin']);
    $router->post('/admin/barbeiros/novo-perfil', 'BarbeiroAdminController::novoPerfil', ['admin']);
    $router->get('/admin/barbeiros/editar', 'BarbeiroAdminController::editar', ['admin']);
    $router->post('/admin/barbeiros/editar', 'BarbeiroAdminController::editar', ['admin']);
    $router->post('/admin/barbeiros/deletar', 'BarbeiroAdminController::deletar', ['admin']);

    $router->get('/admin/servicos', 'ServicoController::index', ['admin']);
    $router->get('/admin/servicos/novo', 'ServicoController::form', ['admin']);
    $router->post('/admin/servicos/novo', 'ServicoController::form', ['admin']);
    $router->get('/admin/servicos/editar', 'ServicoController::form', ['admin']);
    $router->post('/admin/servicos/editar', 'ServicoController::form', ['admin']);
    $router->post('/admin/servicos/deletar', 'ServicoController::deletar', ['admin']);

    $router->get('/admin/agendamentos', 'AdminController::agendamentos', ['admin']);
    $router->get('/admin/relatorio', 'AdminController::relatorio', ['admin']);

    // Pega a URL vinda do .htaccess e remove a barra inicial/final
    $uri = $_GET['url'] ?? '';
    $uri = '/' . trim($uri, '/');
    
    $router->dispatch($_SERVER['REQUEST_METHOD'], $uri);
