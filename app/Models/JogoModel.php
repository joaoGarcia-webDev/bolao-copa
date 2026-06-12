<?php

/**
 * =============================================================
 * MODEL: JogoModel
 * =============================================================
 * Arquivo: app/Models/JogoModel.php
 *
 * Responsável por todas as operações de banco de dados
 * relacionadas à tabela 'jogos'. Utiliza o Query Builder
 * do CodeIgniter 4 para proteção contra SQL Injection.
 * =============================================================
 */

namespace App\Models;

use CodeIgniter\Model;
use App\Models\PalpiteModel;

class JogoModel extends Model
{
    // -------------------------------------------------------
    // Configuração do Model
    // -------------------------------------------------------
    protected $table         = 'jogos';
    protected $primaryKey    = 'id_jogo';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';
    protected $useSoftDeletes = false;

    // Campos que podem ser preenchidos via insert/update
    protected $allowedFields = [
        'time_1',
        'time_2',
        'data_jogo',
        'valor_palpite',
    ];

    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // -------------------------------------------------------
    // Regras de validação
    // -------------------------------------------------------
    protected $validationRules = [
        'time_1'        => 'required|min_length[2]|max_length[100]',
        'time_2'        => 'required|min_length[2]|max_length[100]',
        'data_jogo'     => 'required|valid_date',
        'valor_palpite' => 'required|decimal|greater_than[0]',
    ];

    protected $validationMessages = [
        'time_1' => [
            'required'   => 'O nome do Time 1 é obrigatório.',
            'min_length' => 'O nome do Time 1 deve ter no mínimo 2 caracteres.',
        ],
        'time_2' => [
            'required'   => 'O nome do Time 2 é obrigatório.',
            'min_length' => 'O nome do Time 2 deve ter no mínimo 2 caracteres.',
        ],
        'data_jogo' => [
            'required'   => 'A data do jogo é obrigatória.',
        ],
        'valor_palpite' => [
            'required'     => 'O valor do palpite é obrigatório.',
            'decimal'      => 'Informe um valor decimal válido.',
            'greater_than' => 'O valor deve ser maior que zero.',
        ],
    ];

    protected $skipValidation = false;

    // -------------------------------------------------------
    // Métodos personalizados
    // -------------------------------------------------------

    /**
     * Retorna apenas jogos ainda disponíveis para apostas.
     * Um jogo está disponível se data_jogo > NOW().
     *
     * @return array Lista de jogos abertos
     */
    public function getJogosDisponiveis(): array
    {
        return $this->where('data_jogo >', date('Y-m-d H:i:s'))
                    ->orderBy('data_jogo', 'ASC')
                    ->findAll();
    }

    /**
     * Retorna todos os jogos ordenados por data (mais recente primeiro).
     *
     * @return array Todos os jogos
     */
    public function getTodosJogos(): array
    {
        return $this->orderBy('data_jogo', 'ASC')->findAll();
    }

    /**
     * Retorna o total de jogos cadastrados.
     *
     * @return int Contagem total
     */
    public function getTotalJogos(): int
    {
        return $this->countAllResults();
    }

    /**
     * Verifica se o jogo ainda aceita palpites (antes do horário).
     *
     * @param  int  $idJogo
     * @return bool
     */
    public function jogoAceitaPalpites(int $idJogo): bool
    {
        $jogo = $this->find($idJogo);
        if (! $jogo) {
            return false;
        }
        return strtotime($jogo['data_jogo']) > time();
    }

    /**
     * Exclui um jogo somente se não houver palpites vinculados.
     * Retorna false caso existam palpites (integridade referencial).
     *
     * @param  int  $idJogo
     * @return bool|string True se excluiu, string de erro caso contrário
     */
    public function excluirSeguro(int $idJogo)
    {
        $palpiteModel = new PalpiteModel();
        $total = $palpiteModel->where('id_jogo', $idJogo)->countAllResults();

        if ($total > 0) {
            return "Não é possível excluir: existem {$total} palpite(s) vinculado(s) a este jogo.";
        }

        $this->delete($idJogo);
        return true;
    }
}
