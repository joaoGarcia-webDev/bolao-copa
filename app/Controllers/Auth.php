<?php

/**
 * =============================================================
 * CONTROLLER: Auth
 * =============================================================
 * Arquivo: app/Controllers/Auth.php
 *
 * Gerencia login e logout dos administradores.
 * Utiliza sessão segura e validação de credenciais
 * com password_verify via AdminModel.
 * =============================================================
 */

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    /**
     * Exibe o formulário de login.
     * Redireciona para o dashboard se já estiver logado.
     */
    public function login()
    {
        // Se já está autenticado, não precisa ver o login
        if (session()->get('admin_logado')) {
            return redirect()->to('/admin/dashboard');
        }

        return view('admin/login');
    }

    /**
     * Processa as credenciais enviadas pelo formulário.
     * Utiliza validação CSRF automática do CodeIgniter.
     */
    public function autenticar()
    {
        // Validação básica dos campos
        $regras = [
            'usuario' => 'required|min_length[3]',
            'senha'   => 'required|min_length[4]',
        ];

        if (! $this->validate($regras)) {
            return redirect()->back()
                             ->withInput()
                             ->with('erros', $this->validator->getErrors());
        }

        $usuario = $this->request->getPost('usuario', FILTER_SANITIZE_SPECIAL_CHARS);
        $senha   = $this->request->getPost('senha');

        $adminModel = new AdminModel();
        $admin = $adminModel->autenticar($usuario, $senha);

        if (! $admin) {
            // Aguarda 1 segundo para mitigar ataques de força bruta (brute force delay)
            sleep(1);
            return redirect()->back()
                             ->withInput()
                             ->with('erro', 'Usuário ou senha inválidos.');
        }

        // Credenciais válidas — registra a sessão
        $session = session();
        $session->regenerate(true); // Regenera ID da sessão para prevenir session fixation

        $session->set([
            'admin_logado' => true,
            'admin_id'     => $admin['id_admin'],
            'admin_nome'   => $admin['nome'],
            'admin_usuario'=> $admin['usuario'],
        ]);

        // Redireciona para a URL que o admin tentou acessar antes (ou dashboard)
        $urlAnterior = $session->get('url_anterior') ?? '/admin/dashboard';
        $session->remove('url_anterior');

        return redirect()->to($urlAnterior)->with('sucesso', 'Bem-vindo, ' . $admin['nome'] . '!');
    }

    /**
     * Encerra a sessão do administrador.
     */
    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/admin/login')->with('sucesso', 'Sessão encerrada com sucesso.');
    }
}
