
# 01 - Start

## Baixar o laravel especifico 6.0

É recomendável: Inicie o projeto na versâo na versão 6, e depois, vamos migrar para a versão 7.

Para iniciar o projeto na versão 6, vocÊ pode baixar pelo composer

````
> composer create-project --prefer-dist laravel/laravel marketplace_l6 "6.*"
````

se fizer o ``laravel new marketplace`````vai baixar o laravel mais novo instlaaod globalmente

**Testar a versâo do nosos projeto php**

````
php artisan --version
Laravel Framework 6.20.27
````

## Pastas

````
app: pasta principal do nosso projeto (cntroller, models, midewares, providers)

bootstrap: arquivo de inicializaçao do alvaral

config: congiguraçês de segurança/banco/autenticaçâo/filse system do laravel. Acesso de email

database: tem as factores, seeds e migrations

public: a pasta que sera exportada

resoruce: tem os templates da nossa pagina

routes: rotas

storage: arquivos de cahce e de logs

tests; testse unitarios

vendo: pasta das depdenceia semelahnte ao npm do node

ARQUIOS
composer.json e composer.lock: depednecias do compoder

phpunit.xml: testse do php

package.json e yarn.lcok: depedndeicas de front

````

## php artisan

semelhante ao manage.py do django

**Ele é um auxiliar para o nosso desenvolvimento**

ver todos os coamndaso do artisan: `php artisan`

o principal é gerar as coisa



Exmeplo
````
make
  make:channel         Create a new channel class
  make:command         Create a new Artisan command
  make:controller      Create a new controller class
  make:event           Create a new event class
  make:exception       Create a new custom exception class
  make:factory         Create a new model factory
  make:job             Create a new job class
  make:listener        Create a new event listener class
  make:mail            Create a new email class
  make:middleware      Create a new middleware class
  make:migration       Create a new migration file
  make:model           Create a new Eloquent model class
  make:notification    Create a new notification class
  make:observer        Create a new observer class
  make:policy          Create a new policy class
  make:provider        Create a new service provider class
  make:request         Create a new form request class
  make:resource        Create a new resource
  make:rule            Create a new validation rule
  make:seeder          Create a new seeder class
  make:test            Create a new test class
 migrate
  migrate:fresh        Drop all tables and re-run all migrations
  migrate:install      Create the migration repository
  migrate:refresh      Reset and re-run all migrations
  migrate:reset        Rollback all database migrations
  migrate:rollback     Rollback the last database migration
  migrate:status       Show the status of each migration
 notifications
 ````
