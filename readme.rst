# sistema-chamados

## Versões

- PHP 8.1  
- CodeIgniter 3
- Bootstrap 5
- Javascript ECMAScript 5.1

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
