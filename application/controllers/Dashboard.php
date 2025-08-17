<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Autenticar_model', 'Usuarios');
        $this->load->model('Dashboard_model', 'Chamados');

        $this->load->library('session');
        $this->load->helper(['form', 'url']);
        $this->load->helper('pagination');
        $this->load->library('form_validation');
        $this->load->library('pagination');
    }

    public function index()
    {
        $usuario_logado = $this->session->userdata('usuario_logado');
        $usuario_id = $usuario_logado['id'];
        $tipo_usuario = $usuario_logado['tipo'];

        $total_chamados = $this->Chamados->contarChamados($usuario_id, $tipo_usuario);
        $config = [
            'base_url'     => site_url('dashboard'),
            'total_rows'   => $total_chamados,
            'per_page'     => 5,
            'uri_segment'  => 2
        ];

        list($offset, $paginacao) = paginar($config);

        $chamados = $this->Chamados->listarChamados($usuario_id, $config['per_page'], $offset, $tipo_usuario);

        $conteudo = $this->load->view('dashboard/chamados_aberto', [
            'chamados' => $chamados,
            'paginacao' => $paginacao,
            'tipo' => $tipo_usuario
        ], true);

        $data = [
            'title' => 'Meus Chamados',
            'conteudo' => $conteudo,
            'total_chamados' => $total_chamados
        ];

        $this->load->view('dashboard/index', $data);
    }

    public function novoChamado()
    {
        $usuario = $this->session->userdata('usuario_logado');

        if ($usuario['tipo'] === 'prestador') {
            show_error('Acesso não permitido.');
        }

        $data["title"] = "Novo Chamado - Desafio Técnico";
        $data['conteudo'] = $this->load->view('dashboard/novo_chamado', [], true);
        $this->load->view('dashboard/index', $data);
    }

    public function registrarChamado()
    {
        $this->form_validation->set_rules('motivo', 'Motivo', 'required');
        $this->form_validation->set_rules('descricao', 'Descrição', 'required|max_length[1000]');
        $this->form_validation->set_rules('whatsapp', 'WhatsApp', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Preencha todos os campos corretamente.'
            ]);

            redirect('dashboard/novoChamado');
            return;
        }

        $fotos = $this->salvarImagens($_FILES['fotos'] ?? null);

        $data = [
            'usuario_id' => $this->session->userdata('usuario_logado')['id'],
            'motivo' => $this->input->post('motivo', true),
            'descricao' => $this->input->post('descricao', true),
            'whatsapp' => preg_replace('/\D/', '', $this->input->post('whatsapp', true)),
            'fotos' => $fotos,
        ];

        if ($this->Chamados->criarChamado($data)) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'success',
                'texto' => 'Chamado aberto com sucesso.'
            ]);
            redirect('dashboard');
        } else {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Erro ao abrir o chamado.'
            ]);
            redirect('dashboard/novoChamado');
        }
    }

    public function editarPerfil()
    {
        $usuario_id = $this->session->userdata('usuario_logado')['id'];
        $usuario = $this->Usuarios->buscarPorId($usuario_id);

        $data["title"] = "Editar Perfil - Desafio Técnico";
        $data['conteudo'] = $this->load->view('dashboard/perfil', [
            'usuario' => $usuario
        ], true);

        $this->load->view('dashboard/index', $data);
    }

    public function salvarPerfil()
    {
        $usuario_id = $this->session->userdata('usuario_logado')['id'];

        $nome = $this->input->post('nome', true);
        $usuarioForm = $this->input->post('usuario', true);
        $senha = $this->input->post('senha', true);

        if (empty($nome) || empty($usuarioForm)) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Preencha todos os campos obrigatórios.'
            ]);
            redirect('perfil');
            return;
        }

        // Verifica se existe o usuario no banco
        $usuarioExistente = $this->Usuarios->buscarUsuario($usuarioForm);
        if ($usuarioExistente && $usuarioExistente->id != $usuario_id) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Este nome de usuário já está em uso.'
            ]);
            redirect('perfil');
            return;
        }

        // verificação senha caso foi preenchida
        if (!empty($senha) && strlen($senha) < 8) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'A senha deve ter no mínimo 8 caracteres.'
            ]);
            redirect('perfil');
            return;
        }

        $data = [
            'nome' => $nome,
            'usuario' => $usuarioForm
        ];

        if (!empty($senha)) {
            $data['senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }

        if ($this->Usuarios->atualizar($usuario_id, $data)) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'success',
                'texto' => 'Perfil atualizado com sucesso.'
            ]);

            $this->session->set_userdata('usuario_logado', array_merge(
                $this->session->userdata('usuario_logado'),
                ['nome' => $data['nome'], 'usuario' => $data['usuario']]
            ));
        } else {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Erro ao atualizar o perfil.'
            ]);
        }

        redirect('perfil');
    }

    public function excluirChamado($id)
    {
        $usuario = $this->session->userdata('usuario_logado');
        $usuario_id = $usuario['id'];

        if ($usuario['tipo'] === 'prestador') {
            show_error('Acesso não permitido.');
        }

        if ($this->Chamados->excluirChamado($id, $usuario_id)) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'success',
                'texto' => 'Chamado excluído com sucesso.'
            ]);
        } else {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Erro ao excluir o chamado.'
            ]);
        }

        redirect('dashboard');
    }

    public function editarChamado($id)
    {
        $usuario = $this->session->userdata('usuario_logado');
        $usuario_id = $usuario['id'];

        if ($usuario['tipo'] === 'prestador') {
            show_error('Acesso não permitido.');
        }

        $chamado = $this->Chamados->buscarChamadoPorId($id, $usuario_id);

        if (!$chamado) {
            show_404();
            return;
        }

        $data["title"] = "Editar Chamado - Desafio Técnico";
        $data['conteudo'] = $this->load->view('dashboard/editar_chamado', [
            'chamado' => $chamado
        ], true);

        $this->load->view('dashboard/index', $data);
    }

    public function atualizarChamado($id)
    {
        $usuario_id = $this->session->userdata('usuario_logado')['id'];

        $this->form_validation->set_rules('motivo', 'Motivo', 'required');
        $this->form_validation->set_rules('descricao', 'Descrição', 'required|max_length[1000]');
        $this->form_validation->set_rules('whatsapp', 'WhatsApp', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Preencha todos os campos corretamente.'
            ]);
            redirect("dashboard/editarChamado/$id");
            return;
        }

        $chamado = $this->Chamados->buscarChamadoPorId($id, $usuario_id);
        if (!$chamado) {
            show_404();
            return;
        }

        $fotos = $this->salvarImagens($_FILES['fotos'] ?? null);

        $data = [
            'motivo' => $this->input->post('motivo', true),
            'descricao' => $this->input->post('descricao', true),
            'whatsapp' => preg_replace('/\D/', '', $this->input->post('whatsapp', true))
        ];

        if ($fotos) {
            $data['fotos'] = $fotos;
        }

        if ($this->Chamados->atualizarChamado($id, $usuario_id, $data)) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'success',
                'texto' => 'Chamado atualizado com sucesso.'
            ]);

            redirect('dashboard');
        } else {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Erro ao atualizar chamado.'
            ]);
        }

        redirect("dashboard/editarChamado/$id");
    }

    public function alterarStatus($id, $novo_status)
    {
        $usuario = $this->session->userdata('usuario_logado');

        if ($usuario['tipo'] !== 'prestador') {
            show_error('Você não tem permissão.');
            return;
        }

        $status_permitidos = ['andamento', 'finalizado'];
        if (!in_array($novo_status, $status_permitidos)) {
            show_error('Status inválido');
            return;
        }

        if ($this->Chamados->atualizarStatus($id, $novo_status)) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'success',
                'texto' => 'Status atualizado com sucesso.'
            ]);
        } else {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Erro ao atualizar status.'
            ]);
        }

        redirect('dashboard');
    }
}
