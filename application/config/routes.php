<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'autenticar/index';
$route['registrar'] = 'autenticar/registrar';
$route['adicionar'] = 'autenticar/adicionarUsuario';
$route['logout'] = 'autenticar/logout';

$route['dashboard'] = 'dashboard/index';
$route['dashboard/(:num)'] = 'dashboard/index/$1';
$route['abrirchamado'] = 'dashboard/novoChamado';
$route['perfil'] = 'dashboard/editarPerfil';
$route['perfil/salvar'] = 'dashboard/salvarPerfil';
$route['registrar/chamado'] = 'dashboard/registrarChamado';
$route['editar/chamado/(:num)'] = 'dashboard/editarChamado/$1';



$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
