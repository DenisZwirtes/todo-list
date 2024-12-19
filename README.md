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

        • Acesse: http://localhost/login


✅ Testes Automatizados

    • Para rodar os testes, use o comando:
    
    • php artisan test

    Cobertura dos testes:

    • CRUD completo para Tarefas, Usuários e Categorias.

    • Validação de filtros (categorias e tarefas concluídas).

    • Validação de exclusão automática (tarefas concluídas há mais de uma semana).

    • Teste de relacionamento entre usuários e tarefas

    • Testes de autenticação e restrição de acesso.


 🕒 Configuração do Cron Job

    1. Execute o Comando Manualmente

        • php artisan tasks:delete-old-completed

    2. Configure o Cron Job no Servidor

        Adicione o seguinte comando no Crontab:

        • cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1

        Isso agendará a execução do comando a cada minuto.




    🤝 🔍  Relacionamentos e Filtros

        🤝 Relacionamentos:

        • Tarefas:

            • Uma tarefa pode pertencer a uma única categoria.

            • Uma tarefa pode ser atribuída a vários usuários.

        • Categorias:

            • Uma categoria pertence a um único usuário.

        • Usuários:

        • Cada usuário pode criar várias tarefas.

        🔍 Filtros:

        1. Filtrar Tarefas por Categorias

            • Acesse a página de tarefas e selecione uma categoria no campo de filtro.

            • Apenas as tarefas pertencentes à categoria selecionada serão exibidas.

        2. Filtrar Tarefas Concluídas

            • Marque a opção "Mostrar Concluídas" e clique em Filtrar.

            • Apenas as tarefas concluídas serão exibidas.





    🌟 Funcionalidades Específicas

        1. Autenticação e Registro de Usuários:

            • Usuários podem se registrar e autenticar para acessar o sistema.

            • Todas as ações são protegidas por middleware de autenticação.

        2. CRUD Completo:

            • Usuários podem criar, editar, excluir e marcar tarefas como concluídas.

            • CRUD completo para categorias, associando-as a tarefas e usuários.

        3. Exclusão Automática:

            • Tarefas concluídas há mais de uma semana são excluídas automaticamente.

            • Implementado via Job agendado no Laravel Scheduler.




    🔒 Validações

        • Título da tarefa obrigatório.

        • Categoria deve existir no banco de dados.

        • Usuários atribuídos devem existir.

        • Middleware: Rotas protegidas exigem autenticação.
