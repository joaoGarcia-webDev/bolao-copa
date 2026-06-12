<?php

/**
 * Rotas do sistema - Bolão da Copa do Mundo
 */

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('User');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

// Rotas públicas — área do usuário
$routes->get('/', 'User::index');
$routes->get('palpite/(:num)', 'User::formPalpite/$1');
$routes->post('palpite/enviar', 'User::enviarPalpite');
$routes->get('palpite/confirmacao', 'User::confirmacao');

// Autenticação do administrador
$routes->get('admin/login', 'Auth::login');
$routes->post('admin/login', 'Auth::autenticar');
$routes->get('admin/logout', 'Auth::logout');

// Rotas administrativas — protegidas pelo filtro 'auth'
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('dashboard', 'Admin::dashboard');

    $routes->get('jogos', 'Admin::listarJogos');
    $routes->get('jogos/criar', 'Admin::criarJogo');
    $routes->post('jogos/salvar', 'Admin::salvarJogo');
    $routes->get('jogos/editar/(:num)', 'Admin::editarJogo/$1');
    $routes->post('jogos/atualizar/(:num)', 'Admin::atualizarJogo/$1');
    $routes->post('jogos/excluir/(:num)', 'Admin::excluirJogo/$1');

    $routes->get('palpites', 'Admin::listarPalpites');
    $routes->get('palpites/jogo/(:num)', 'Admin::palpitesPorJogo/$1');
    $routes->post('palpites/toggle/(:num)', 'Admin::togglePagamento/$1');

    $routes->get('relatorios', 'Admin::relatorios');
});

$routes->set404Override(static function () {
    return view('errors/html/error_404');
});
