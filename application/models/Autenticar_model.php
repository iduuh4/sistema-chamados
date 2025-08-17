<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Autenticar_model extends CI_Model
{
    //tabela do db
    protected $table = 'usuarios';

    public function __construct()
    {
        parent::__construct();
    }

    public function buscarUsuario($usuario)
    {
        $query = $this->db
            ->where('usuario', $usuario)
            ->get($this->table);

        return $query->row() ?? null;
    }

    public function inserir($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function atualizar($id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->update($this->table, $data);
    }

    public function buscarPorId($id)
    {
        $query = $this->db
            ->where('id', $id)
            ->get($this->table);

        return $query->row();
    }

    public function excluir($id)
    {
        return $this->db
        ->where('id', $id)
        ->delete($this->table);
    }
}
