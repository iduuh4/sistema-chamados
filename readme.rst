# sistema-chamados

## Versões

- PHP 8.1  
- CodeIgniter 3

## Lógica do sistema

- Na criação do login, poderá escolher se é **Cliente** ou **Prestador de Serviço**.  
- Cada usuário poderá apenas visualizar suas **próprias chamadas**.  
- Usuário **Cliente** pode criar, editar e excluir seus chamados.  
- O sistema é **responsivo** para dispositivos móveis.  
- Não será possível criar um login já existente no banco de dados.  
- Usuários **Prestadores de Serviços** podem visualizar **todos os chamados** e alterar o status para **"Andamento"** e **"Finalizado"**.  
- Usuários **Prestadores de Serviços** **não podem** criar, editar nem excluir chamados.  

> ⚠️ **Linux**: pode ser necessário dar permissão de escrita à pasta `uploads`.  
> O sistema requer **PHP 8.1** para funcionar corretamente.

## Banco de dados

Dentro da pasta raiz do projeto está o arquivo `Dump.sql`.  
Ou você pode usar o seguinte código SQL:

<details>
<summary>Mostrar SQL</summary>

```sql
CREATE DATABASE IF NOT EXISTS `desafiotecnico`
  /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */
  /*!80016 DEFAULT ENCRYPTION='N' */;
USE `desafiotecnico`;

-- MySQL dump 10.13 Distrib 8.0.43, for Win64 (x86_64)
-- Host: 127.0.0.1
-- Database: desafiotecnico
-- Server version 9.1.0

-- Tabela chamados
CREATE TABLE `chamados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int NOT NULL,
  `motivo` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `whatsapp` varchar(20) NOT NULL,
  `fotos` text,
  `criado_em` datetime DEFAULT CURRENT_TIMESTAMP,
  `atualizado_em` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('aguardando','andamento','finalizado') DEFAULT 'aguardando',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Tabela usuarios
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `usuario` varchar(45) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `tipo` enum('cliente','prestador') NOT NULL DEFAULT 'cliente',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
