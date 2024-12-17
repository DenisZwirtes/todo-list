Sistema de Tarefas (To-Do List)

Este projeto foi desenvolvido como parte do **Teste Técnico - Vaga Júnior FullStack**. Trata-se de uma aplicação web para gerenciamento de tarefas usando **Laravel 11**.


 📋 Funcionalidades

1. Gerenciamento de Tarefas
    • Registro e autenticação de usuários.
    • CRUD completo de tarefas.
    • Marcar tarefas como concluídas.
    • Usuários só podem visualizar suas próprias tarefas.

2. Filtros
    • Filtrar tarefas por categorias.
    • Filtrar tarefas concluídas.

3. Extras
    • Tarefas concluídas há mais de uma semana são deletadas automaticamente.
    • Testes automatizados para validação.


 ⚙️ Tecnologias Utilizadas

    • Laravel 11.x
    • MySQL
    • Blade Templates
    • PHPUnit  
    • Bootstrap 5


 🚀 Instalação e Configuração

1. Clone o Repositório  
    • git clone https://github.com/DenisZwirtes/todo-list.git
    • cd todo-list

2. Instale as Dependências do PHP
    • composer install

3. Instale as Dependências do Frontend
    • npm install

4. Configure o Banco de Dados 
   Crie um arquivo .env e configure as credenciais:
   DB_DATABASE=todo_list
   DB_USERNAME=seu_usuario
   DB_PASSWORD=sua_senha

5. Gere a Chave da Aplicação 
    • php artisan key:generate

6. Execute Migrations e Seeders
    • php artisan migrate --seed

7. Inicie o Servidor Local
    • php artisan serve

    •  Acesse: http://127.0.0.1:8000/login


✅ Testes Automatizados

    • Para rodar os testes, use o comando:
    • php artisan test


 🕒 Configuração do Cron Job

1. Execute o Comando Manualmente
    • php artisan tasks:delete-old-completed

2. Agende o Cron Job no Servidor
    • cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1
