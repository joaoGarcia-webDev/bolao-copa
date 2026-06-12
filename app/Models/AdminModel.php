<?php

/**
 * =============================================================
 * MODEL: AdminModel
 * =============================================================
 * Arquivo: app/Models/AdminModel.php
 *
 * Gerencia autenticação e dados dos administradores.
 * Utiliza password_hash/password_verify do PHP para
 * segurança nas senhas armazenadas.
 * =============================================================
 */

namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model
{
    protected $table         = 'administradores';
    protected $primaryKey    = 'id_admin';
    protected $useAutoIncrement = true;
    protected $returnType    = 'array';

    protected $allowedFields = [
        'nome',
        'usuario',
        'senha_hash',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    protected $validationRules = [
        'nome'    => 'required|min_length[3]|max_length[100]',
        'usuario' => 'required|min_length[3]|max_length[50]|is_unique[administradores.usuario]',
    ];

    // -------------------------------------------------------
    // Métodos de Autenticação
    // -------------------------------------------------------

    /**
     * Autentica um administrador pelo usuário e senha.
     *
     * @param  string  $usuario  Login informado
     * @param  string  $senha    Senha em texto plano
     * @return array|false       Dados do admin ou false se inválido
     */
    public function autenticar(string $usuario, string $senha)
    {
        // Busca o admin pelo username (não pela senha — nunca compare diretamente)
        $admin = $this->where('usuario', $usuario)->first();

        if (! $admin) {
            return false; // Usuário não existe
        }

        // Verifica a senha usando password_verify (seguro contra timing attacks)
        if (! password_verify($senha, $admin['senha_hash'])) {
            return false; // Senha incorreta
        }

        // Remove o hash da senha antes de retornar (não expor em sessão)
        unset($admin['senha_hash']);

        return $admin;
    }

    /**
     * Cria um novo administrador com senha hasheada.
     *
     * @param  string  $nome
     * @param  string  $usuario
     * @param  string  $senha
     * @return int|false  ID inserido ou false em falha
     */
    public function criarAdmin(string $nome, string $usuario, string $senha)
    {
        $hash = password_hash($senha, PASSWORD_BCRYPT, ['cost' => 12]);

        return $this->insert([
            'nome'       => $nome,
            'usuario'    => $usuario,
            'senha_hash' => $hash,
        ]);
    }

    /**
     * Atualiza a senha de um administrador.
     *
     * @param  int     $idAdmin
     * @param  string  $novaSenha
     * @return bool
     */
    public function alterarSenha(int $idAdmin, string $novaSenha): bool
    {
        $hash = password_hash($novaSenha, PASSWORD_BCRYPT, ['cost' => 12]);
        return $this->update($idAdmin, ['senha_hash' => $hash]);
    }
}
