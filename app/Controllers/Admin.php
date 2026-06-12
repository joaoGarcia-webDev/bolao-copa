<?php

/**
 * =============================================================
 * CONTROLLER: Admin
 * =============================================================
 * Arquivo: app/Controllers/Admin.php
 *
 * Controller principal da área administrativa.
 * Todas as rotas deste controller são protegidas pelo
 * filtro AuthFilter. O administrador NÃO pode registrar
 * palpites — apenas gerenciar jogos, palpites e relatórios.
 * =============================================================
 */

namespace App\Controllers;

use App\Models\JogoModel;
use App\Models\PalpiteModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Admin extends BaseController
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
    // DASHBOARD
    // ================================================================

    /**
     * Exibe o painel principal com totalizadores do sistema.
     */
    public function dashboard(): string
    {
        $dados = [
            'titulo'              => 'Dashboard',
            'total_jogos'         => $this->jogoModel->getTotalJogos(),
            'total_palpites'      => $this->palpiteModel->getTotalPalpites(),
            'total_arrecadado'    => $this->palpiteModel->getTotalArrecadado(),
            'total_pendentes'     => $this->palpiteModel->getTotalPorStatus('Pendente'),
            'total_pagos'         => $this->palpiteModel->getTotalPorStatus('Pago'),
            'ultimos_palpites'    => array_slice(
                                        $this->palpiteModel->getPalpitesComJogo(), 0, 5
                                    ),
        ];

        return view('admin/dashboard', $dados);
    }

    // ================================================================
    // GESTÃO DE JOGOS
    // ================================================================

    /**
     * Lista todos os jogos cadastrados.
     */
    public function listarJogos(): string
    {
        return view('admin/jogos/listar', [
            'titulo' => 'Gerenciar Jogos',
            'jogos'  => $this->jogoModel->getTodosJogos(),
        ]);
    }

    /**
     * Exibe o formulário para cadastrar novo jogo.
     */
    public function criarJogo(): string
    {
        return view('admin/jogos/form', [
            'titulo' => 'Novo Jogo',
            'jogo'   => null, // null = modo criação
        ]);
    }

    /**
     * Processa e salva um novo jogo no banco.
     */
    public function salvarJogo()
    {
        // Coleta e sanitiza os dados do formulário
        $dados = [
            'time_1'        => $this->request->getPost('time_1',        FILTER_SANITIZE_SPECIAL_CHARS),
            'time_2'        => $this->request->getPost('time_2',        FILTER_SANITIZE_SPECIAL_CHARS),
            'data_jogo'     => $this->request->getPost('data_jogo'),
            'valor_palpite' => $this->request->getPost('valor_palpite', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        ];

        // Converte data do formato HTML (datetime-local) para MySQL
        if ($dados['data_jogo']) {
            $dados['data_jogo'] = date('Y-m-d H:i:s', strtotime($dados['data_jogo']));
        }

        // Tenta salvar — o model executa as validações automaticamente
        if (! $this->jogoModel->insert($dados)) {
            return redirect()->back()
                             ->withInput()
                             ->with('erros', $this->jogoModel->errors());
        }

        return redirect()->to('/admin/jogos')
                         ->with('sucesso', 'Jogo cadastrado com sucesso!');
    }

    /**
     * Exibe o formulário de edição de um jogo existente.
     *
     * @param  int  $id
     */
    public function editarJogo(int $id)
    {
        $jogo = $this->jogoModel->find($id);

        if (! $jogo) {
            return redirect()->to('/admin/jogos')
                             ->with('erro', 'Jogo não encontrado.');
        }

        // Formata a data para o campo datetime-local do HTML
        $jogo['data_jogo_input'] = date('Y-m-d\TH:i', strtotime($jogo['data_jogo']));

        return view('admin/jogos/form', [
            'titulo' => 'Editar Jogo',
            'jogo'   => $jogo,
        ]);
    }

    /**
     * Processa a atualização de um jogo existente.
     *
     * @param  int  $id
     */
    public function atualizarJogo(int $id)
    {
        $jogo = $this->jogoModel->find($id);
        if (! $jogo) {
            return redirect()->to('/admin/jogos')->with('erro', 'Jogo não encontrado.');
        }

        $dados = [
            'time_1'        => $this->request->getPost('time_1',        FILTER_SANITIZE_SPECIAL_CHARS),
            'time_2'        => $this->request->getPost('time_2',        FILTER_SANITIZE_SPECIAL_CHARS),
            'data_jogo'     => $this->request->getPost('data_jogo'),
            'valor_palpite' => $this->request->getPost('valor_palpite', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION),
        ];

        if ($dados['data_jogo']) {
            $dados['data_jogo'] = date('Y-m-d H:i:s', strtotime($dados['data_jogo']));
        }

        if (! $this->jogoModel->update($id, $dados)) {
            return redirect()->back()
                             ->withInput()
                             ->with('erros', $this->jogoModel->errors());
        }

        return redirect()->to('/admin/jogos')
                         ->with('sucesso', 'Jogo atualizado com sucesso!');
    }

    /**
     * Exclui um jogo (somente se não tiver palpites vinculados).
     *
     * @param  int  $id
     */
    public function excluirJogo(int $id)
    {
        $resultado = $this->jogoModel->excluirSeguro($id);

        if ($resultado !== true) {
            return redirect()->to('/admin/jogos')->with('erro', $resultado);
        }

        return redirect()->to('/admin/jogos')
                         ->with('sucesso', 'Jogo excluído com sucesso!');
    }

    // ================================================================
    // GESTÃO DE PALPITES
    // ================================================================

    /**
     * Lista todos os palpites com dados do jogo relacionado.
     */
    public function listarPalpites(): string
    {
        return view('admin/palpites/listar', [
            'titulo'   => 'Gerenciar Palpites',
            'palpites' => $this->palpiteModel->getPalpitesComJogo(),
            'jogos'    => $this->jogoModel->getTodosJogos(),
            'filtro'   => null,
        ]);
    }

    /**
     * Lista palpites filtrados por um jogo específico.
     *
     * @param  int  $idJogo
     */
    public function palpitesPorJogo(int $idJogo): string
    {
        $jogo = $this->jogoModel->find($idJogo);

        return view('admin/palpites/listar', [
            'titulo'   => 'Palpites do Jogo',
            'palpites' => $this->palpiteModel->getPalpitesPorJogo($idJogo),
            'jogos'    => $this->jogoModel->getTodosJogos(),
            'filtro'   => $idJogo,
            'jogo'     => $jogo,
        ]);
    }

    /**
     * Alterna o status de pagamento de um palpite com um clique.
     * Pendente → Pago | Pago → Pendente
     *
     * @param  int  $id
     */
    public function togglePagamento(int $id)
    {
        $resultado = $this->palpiteModel->togglePagamento($id);

        if (! $resultado) {
            return redirect()->back()->with('erro', 'Palpite não encontrado.');
        }

        return redirect()->back()->with('sucesso', 'Status de pagamento atualizado!');
    }

    // ================================================================
    // RELATÓRIOS
    // ================================================================

    /**
     * Exibe relatório consolidado por jogo.
     */
    public function relatorios(): string
    {
        $relatorio = $this->palpiteModel->getRelatorioporJogo();

        // Calcula totais gerais
        $totalGeralArrecadado = array_sum(array_column($relatorio, 'arrecadado_total'));
        $totalGeralPago       = array_sum(array_column($relatorio, 'arrecadado_pago'));
        $totalGeralPendente   = array_sum(array_column($relatorio, 'arrecadado_pendente'));
        $totalGeralPalpites   = array_sum(array_column($relatorio, 'total_palpites'));

        return view('admin/relatorios/index', [
            'titulo'                => 'Relatórios',
            'relatorio'             => $relatorio,
            'total_geral_arrecadado'=> $totalGeralArrecadado,
            'total_geral_pago'      => $totalGeralPago,
            'total_geral_pendente'  => $totalGeralPendente,
            'total_geral_palpites'  => $totalGeralPalpites,
        ]);
    }
}
