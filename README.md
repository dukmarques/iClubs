## iClubs ⚽️

> Sistema de gerenciamento de clubes e associados em esquema de assinatura.

### Tecnologias
* [VueJS](https://vuejs.org/)
* [VueRouter](https://router.vuejs.org/)
* [Sass](https://sass-lang.com/)
* [Laravel 9](https://laravel.com/)
* PostgreSQL

### Executando o Projeto
#### Api
* Clone este repositório `git clone https://github.com/dukmarques/iClubs.git`
* Acesse a pasta `back-api`
* Crie um arquivo `.env`de acordo com o `.env.example` presente na pasta `back-api` e configure-o de acordo com seu banco de dados
* Execute os comandos:
````
composer install
php artisan key:generate
````
* Execute `php artisan migrate` e por fim `php artisan serve` para executar a api do projeto.

#### Front-end
* Acesse a pasta `app`
* Execute `yarn install` para instalar as dependências do projeto
* Crie um arquivo `.env` dentro da pasta app e adicione o valor `VUE_APP_ROOT_API=http://127.0.0.1:8000/api` de acordo com a url em que a API esteja rodando
* Por fim, execute `yarn serve` para executar o front-end
