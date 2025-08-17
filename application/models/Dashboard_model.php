<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends CI_Model
{
    protected $table = 'chamados';

    public function __construct()
    {
        parent::__construct();
    }

    public function criarChamado($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function listarChamados($usuario_id, $limit, $offset, $tipo_usuario)
    {
        $this->db->order_by('criado_em', 'DESC')->limit($limit, $offset);

        if ($tipo_usuario !== 'prestador') {
            $this->db->where('usuario_id', $usuario_id);
        }

        return $this->db->get($this->table)->result();
    }

    public function contarChamados($usuario_id, $tipo_usuario)
    {
        if ($tipo_usuario !== 'prestador') {
            $this->db->where('usuario_id', $usuario_id);
        }

        return $this->db->count_all_results('chamados');
    }

    public function excluirChamado($id, $usuario_id)
    {
        return $this->db
            ->where('id', $id)
            ->where('usuario_id', $usuario_id)
            ->delete($this->table);
    }

    public function buscarChamadoPorId($id, $usuario_id)
    {
        return $this->db
            ->where('id', $id)
            ->where('usuario_id', $usuario_id)
            ->get($this->table)
            ->row();
    }

    public function atualizarChamado($id, $usuario_id, $data)
    {
        return $this->db
            ->where('id', $id)
            ->where('usuario_id', $usuario_id)
            ->update($this->table, $data);
    }

    public function atualizarStatus($id, $status)
    {
        return $this->db->where('id', $id)
            ->update($this->table, ['status' => $status]);
    }
}
