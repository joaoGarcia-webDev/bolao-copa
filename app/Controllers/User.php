<?php

/**
 * =============================================================
 * CONTROLLER: User
 * =============================================================
 * Arquivo: app/Controllers/User.php
 *
 * Área pública do sistema. Exibe jogos disponíveis e
 * processa o envio de palpites pelos participantes.
 * Usuários comuns NÃO precisam criar conta.
 * =============================================================
 */

namespace App\Controllers;

use App\Models\JogoModel;
use App\Models\PalpiteModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class User extends BaseController
{
    protected JogoModel    $jogoModel;
    protected PalpiteModel $palpiteModel;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->jogoModel    = new JogoModel();
        $this->palpiteModel = new PalpiteModel();
    }

    // ================================================================
    // PÁGINA INICIAL
    // ================================================================

    /**
     * Exibe a lista de jogos disponíveis para apostas.
     * Somente jogos com data_jogo > NOW() são listados.
     */
    public function index(): string
    {
        return view('user/index', [
            'titulo' => 'Bolão da Copa do Mundo',
            'jogos'  => $this->jogoModel->getJogosDisponiveis(),
        ]);
    }

    // ================================================================
    // FORMULÁRIO DE PALPITE
    // ================================================================

    /**
     * Exibe o formulário para registrar palpite em um jogo.
     *
     * @param  int  $idJogo
     */
    public function formPalpite(int $idJogo)
    {
        $jogo = $this->jogoModel->find($idJogo);

        // Jogo existe?
        if (! $jogo) {
            return redirect()->to('/')->with('erro', 'Jogo não encontrado.');
        }

        // Ainda aceita palpites?
        if (! $this->jogoModel->jogoAceitaPalpites($idJogo)) {
            return redirect()->to('/')
                             ->with('erro', 'As apostas para este jogo já foram encerradas.');
        }

        return view('user/form_palpite', [
            'titulo' => 'Registrar Palpite',
            'jogo'   => $jogo,
        ]);
    }

    // ================================================================
    // ENVIAR PALPITE
    // ================================================================

    /**
     * Processa e salva o palpite enviado pelo formulário.
     * Aplica todas as regras de negócio e validações.
     */
    public function enviarPalpite()
    {
        // Recebe e sanitiza os dados do formulário
        $idJogo       = (int) $this->request->getPost('id_jogo');
        $nomeCompleto = $this->request->getPost('nome_completo', FILTER_SANITIZE_SPECIAL_CHARS);
        $re           = $this->request->getPost('re',            FILTER_SANITIZE_SPECIAL_CHARS);
        $palpiteStr   = $this->request->getPost('palpite',       FILTER_SANITIZE_SPECIAL_CHARS);

        // 1. Verificar se o jogo existe
        $jogo = $this->jogoModel->find($idJogo);
        if (! $jogo) {
            return redirect()->to('/')->with('erro', 'Jogo inválido.');
        }

        // 2. Regra de negócio: verificar prazo (não pode apostar após início do jogo)
        if (! $this->jogoModel->jogoAceitaPalpites($idJogo)) {
            return redirect()->to('/')
                             ->with('erro', 'As apostas para este jogo já foram encerradas.');
        }

        // 3. Campos obrigatórios
        if (empty(trim((string) $nomeCompleto)) || empty(trim((string) $re)) || empty(trim((string) $palpiteStr))) {
            return redirect()->back()
                             ->withInput()
                             ->with('erros', ['geral' => 'Todos os campos são obrigatórios.']);
        }

        // 4. Um RE por jogo — pode apostar em vários jogos, mas só uma vez em cada
        if ($this->palpiteModel->jaApostouNesteJogo($idJogo, $re)) {
            return redirect()->back()
                             ->withInput()
                             ->with('erros', ['re' => 'Você já registrou um palpite para este jogo.']);
        }

        // 5. Validação via Model (inclui regex do palpite NxN)
        $dados = [
            'id_jogo'          => $idJogo,
            'nome_completo'    => $nomeCompleto,
            're'               => $re,
            'palpite'          => strtolower(trim($palpiteStr)), // Normaliza para minúsculo
            'status_pagamento' => 'Pendente',                    // Sempre inicia como Pendente
            'data_envio'       => date('Y-m-d H:i:s'),          // Registra data/hora atual
        ];

        if (! $this->palpiteModel->insert($dados)) {
            return redirect()->back()
                             ->withInput()
                             ->with('erros', $this->palpiteModel->errors())
                             ->with('id_jogo', $idJogo);
        }

        // Guarda nome do jogo na sessão para exibir na confirmação
        session()->setFlashdata('jogo_confirmacao', $jogo['time_1'] . ' x ' . $jogo['time_2']);
        session()->setFlashdata('palpite_confirmacao', $palpiteStr);
        session()->setFlashdata('nome_confirmacao', $nomeCompleto);

        return redirect()->to('/palpite/confirmacao');
    }

    // ================================================================
    // CONFIRMAÇÃO
    // ================================================================

    /**
     * Exibe tela de confirmação após envio bem-sucedido.
     */
    public function confirmacao(): string
    {
        return view('user/confirmacao', [
            'titulo' => 'Palpite Registrado!',
        ]);
    }
}
