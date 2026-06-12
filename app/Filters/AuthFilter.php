<?php

/**
 * =============================================================
 * FILTRO DE AUTENTICAÇÃO
 * =============================================================
 * Arquivo: app/Filters/AuthFilter.php
 *
 * Intercepta todas as requisições nas rotas do grupo 'admin'
 * e redireciona para o login caso o usuário não esteja
 * autenticado. Implementa segurança por sessão.
 * =============================================================
 */

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Executado ANTES do controller.
     * Verifica se há sessão de administrador ativa.
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Se não há admin_logado na sessão, redireciona para login
        if (! $session->get('admin_logado')) {
            // Guarda a URL que o admin tentou acessar para redirecionar após login
            $session->set('url_anterior', current_url());

            return redirect()->to('/admin/login')
                             ->with('erro', 'Faça login para acessar esta área.');
        }
    }

    /**
     * Executado APÓS o controller.
     * Não precisamos fazer nada aqui.
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Sem ação necessária após a requisição
    }
}
