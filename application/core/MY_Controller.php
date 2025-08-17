<?php
/**
 * @property CI_Session $session
 * @property CI_Upload $upload
 * @property CI_Input $input
 * @property CI_Form_validation $form_validation
 * @property CI_Session $session
 * @property CI_DB_query_builder $db
 * @property Usuarios_model $Usuarios
 * @property Chamados_model $Chamados
*/
class MY_Controller extends CI_Controller {
    protected $data = [];
    protected $usuario;

    public function __construct() {
        parent::__construct();

        if (!$this->session->userdata('usuario_logado')) {
            redirect('autenticar');
        }

        $this->usuario = $this->session->userdata('usuario_logado');
    }

    protected function render($view) {
        $this->load->view('templates/header', $this->data);
        $this->load->view($view, $this->data);
        $this->load->view('templates/footer', $this->data);
    }

    protected function salvarImagens($arquivos)
    {
        if (empty($arquivos['name'][0])) return null;

        $this->load->helper('file');
        $this->load->library('upload');
        $caminhos = [];

        $upload_path = './uploads/chamados/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config = [
            'upload_path' => $upload_path,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 2048,
            'encrypt_name' => true
        ];

        $this->upload->initialize($config);

        $quantidade = count($arquivos['name']);
        for ($i = 0; $i < $quantidade; $i++) {
            $_FILES['file'] = [
                'name' => $arquivos['name'][$i],
                'type' => $arquivos['type'][$i],
                'tmp_name' => $arquivos['tmp_name'][$i],
                'error' => $arquivos['error'][$i],
                'size' => $arquivos['size'][$i],
            ];

            if ($this->upload->do_upload('file')) {
                $upload_data = $this->upload->data();
                $caminhos[] = $upload_data['file_name'];
            }
        }

        return implode(',', $caminhos);
    }

}