<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('paginar')) {
    function paginar($config)
    {
        $CI = &get_instance();
        $CI->load->library('pagination');

        // Estilo Bootstrap 5
        $config_default = [
            'full_tag_open'   => '<nav><ul class="pagination justify-content-center">',
            'full_tag_close'  => '</ul></nav>',
            'attributes'      => ['class' => 'page-link'],
            'first_link'      => 'Primeira',
            'last_link'       => 'Última',
            'next_link'       => '›',
            'prev_link'       => '‹',
            'cur_tag_open'    => '<li class="page-item active"><span class="page-link">',
            'cur_tag_close'   => '</span></li>',
            'num_tag_open'    => '<li class="page-item">',
            'num_tag_close'   => '</li>',
            'first_tag_open'  => '<li class="page-item">',
            'first_tag_close' => '</li>',
            'last_tag_open'   => '<li class="page-item">',
            'last_tag_close'  => '</li>',
            'next_tag_open'   => '<li class="page-item">',
            'next_tag_close'  => '</li>',
            'prev_tag_open'   => '<li class="page-item">',
            'prev_tag_close'  => '</li>',
        ];

        $config = array_merge($config_default, $config);

        $CI->pagination->initialize($config);

        $segment = $CI->uri->segment($config['uri_segment']);

        // ✅ Protege corretamente contra null
        $offset = (!empty($segment) && ctype_digit((string)$segment)) ? (int) $segment : 0;

        return [$offset, $CI->pagination->create_links()];
    }
}
