<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property Usuarios_model $Usuarios
 */
class Autenticar extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Autenticar_model', 'Usuarios');

        $this->load->library('session');
        $this->load->helper(['form', 'url']);
        $this->load->library('form_validation');
    }
    public function index()
    {
        if ($this->session->userdata('usuario_logado')) {
            redirect('dashboard');
        }

        $data["title"] = "Login - Desafio Técnico";
        $this->load->view('autenticacao/login', $data);
    }

    public function registrar()
    {
        if ($this->session->userdata('usuario_logado')) {
            redirect('dashboard');
        }

        $data["title"] = "Registrar Usuário - Desafio Técnico";
        $this->load->view('autenticacao/register', $data);
    }

    public function adicionarUsuario()
    {
        $this->form_validation->set_rules('usuario', 'Usuário', 'required|is_unique[usuarios.usuario]');
        $this->form_validation->set_rules('nome', 'Nome', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required|callback_validar_senha');
        $this->form_validation->set_rules('tipo', 'Tipo de Usuário', 'required|in_list[cliente,prestador]');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => validation_errors('<li>', '</li>') ?: 'Preencha todos os campos corretamente.'
            ]);
            redirect('registrar');
            return;
        }

        $usuario = $this->input->post('usuario');
        $nome = $this->input->post('nome');
        $senha = $this->input->post('senha');
        $tipo = $this->input->post('tipo');

        $data = [
            'usuario' => $usuario,
            'nome' => $nome,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'tipo' => $tipo
        ];

        if ($this->Usuarios->inserir($data)) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'success',
                'texto' => 'Usuário registrado com sucesso.'
            ]);
            redirect('autenticar');
        } else {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Erro ao registrar usuário.'
            ]);
            redirect('registrar');
        }
    }

    public function login()
    {
        $this->form_validation->set_rules('usuario', 'Usuário', 'required');
        $this->form_validation->set_rules('senha', 'Senha', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('mensagem', [
                'tipo' => 'danger',
                'texto' => 'Preencha todos os campos corretamente.'
            ]);
            redirect('autenticar');
            return;
        }

        $usuarioInput = $this->input->post('usuario');
        $senhaInput = $this->input->post('senha');

        $usuario = $this->Usuarios->buscarUsuario($usuarioInput);

        if (!$usuario || !password_verify($senhaInput, $usuario->senha)) {
            $this->session->set_flashdata('mensagem', ['tipo' => 'danger', 'texto' => 'Usuário ou senha inválidos.']);
            redirect('autenticar');
            return;
        }

        $this->session->set_userdata('usuario_logado', [
            'id' => $usuario->id,
            'usuario' => $usuario->usuario,
            'nome' => $usuario->nome,
            'tipo' => $usuario->tipo
        ]);

        redirect('dashboard');
    }

    public function logout()
    {
        $this->session->unset_userdata('usuario_logado');
        $this->session->sess_destroy();
        redirect('autenticar');
    }

    public function validar_senha($senha)
    {
        if (strlen($senha) < 8) {
            $this->form_validation->set_message('validar_senha', 'A senha deve ter pelo menos 8 caracteres.');
            return false;
        }

        return true;
    }
}
