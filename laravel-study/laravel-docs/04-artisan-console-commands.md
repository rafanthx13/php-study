# Artisan Console

Script que já cria muita coisa, como no django. Deve está na raiz do projeto laravel

## Listar todos os comandos

## Migrations

## Comandos interessantes do `artisan migrate`

```
php artisan migrate:status
```

+ Atalho para listar as migrations já executados, 

```
php artisan migrate:reset
```

+ Executa todos s rollback das migrations

```
php artisan migrate:refresh
```

+ Ele faz todos os rollbacks e em seguida todos os migrate
+ Útil para desfazer tudo e recriar tudo sem informação

```
php artisan migrate:fresh
```

+ Parecido com o *refresh* mas não executa o rollback, ele faz um `drop` dos objetos



**OBS**: Podemos usar as migrate direto do curso da udemy que elas funcionam no laravel 8

```
php artisan migrate:rollback
```

execut 1 rolback

```
php artisan migrate:rollback --steps=2
```



## Principais comandos

+ ```
  php artsian serve
  ```
  
  + Sobe a aplicação: se quiser outra porta, coloque a flag  `--port=8888`
  
+ ```
  php artisan route:list
  ```
  
  + Lista todas as rotas da nossa aplicação
  
+ ```
  php artisan tinker
  ```
  
  + O `tinker` é um shell alravle que permite usar as Models manulamente

  + É uma opçâo ao invez de testar as coisa no dófigo
  
  + Exemplo: Criar um usuário pelo tinker
  
    + ```
      php artisan tinker
      >> $user = new app\models\user();
      >> $user->name = 'jorge'
      >> $user->email = 'jorge#tests'
      >> $user->password = bcrypt('1234')
      >> var_dump(user->getattributes())
      ```
  
      
  
+ ```
  php artisan migrate
  ```
  
  + Executa as migrations, todas elas
  
+ ```
  php artisan db:seed
  ```

  + Executa todas as seeders


+ ```
  php artisan db:seed --class=UserSeeder
  ```

  + Executa uma seeder especifica

+ ```
  php artisan make:migration SiteContato -m
  ```

  + alemd e criar uma migration, cria seu model

+ ```
  php artisan make:seeder FornecedorSeeder
  ```
  + cria seeder


+ ```
  php artisan make:factory SiteContatoFactory --model=SiteContato
  ```

  + Criando uma Facoter. É necessa´rio apssad o Model dela

+ ```
  
  ```

  + .

  

## Diferentes formas de criar Controllers



```shell
php artisan make:model --migration --controller --resource Marca
```

 outra podemos simplificar como

```sh
php artisan make:model -mcr Modelo
```

Cria também seeder e factory

```sh
php artisan make:model --all Modelo
```

e simplificando

```sh
php artisan make:model -a Cliente
```

e também locação:

```sh
 php artisan make:model -a Locacao --resource
```

+ `--resource`: cria no controller os métodos default index, show, destroy

## Listar comando `php artisan list`

```

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more 
verbose output and 3 for debug

Available commands:
  clear-compiled       Remove the compiled class file
  db                   Start a new database CLI session
  down                 Put the application into maintenance / demo mode
  env                  Display the current framework environment
  help                 Display help for a command
  inspire              Display an inspiring quote
  list                 List commands
  migrate              Run the database migrations
  optimize             Cache the framework bootstrap files
  serve                Serve the application on the PHP development server
  test                 Run the application tests
  tinker               Interact with your application
  up                   Bring the application out of maintenance mode
 auth
  auth:clear-resets    Flush expired password reset tokens
 cache
  cache:clear          Flush the application cache
  cache:forget         Remove an item from the cache
  cache:table          Create a migration for the cache database table
 config
  config:cache         Create a cache file for faster configuration loading
  config:clear         Remove the configuration cache file
 db
  db:seed              Seed the database with records
  db:wipe              Drop all tables, views, and types
 event
  event:cache          Discover and cache the application's events and listeners
  event:clear          Clear all cached events and listeners
  event:generate       Generate the missing events and listeners based on registration
  event:list           List the application's events and listeners
 key
  key:generate         Set the application key
 make
  make:cast            Create a new custom Eloquent cast class
  make:channel         Create a new channel class
  make:command         Create a new Artisan command
  make:component       Create a new view component class
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
  notifications:table  Create a migration for the notifications table
 optimize
  optimize:clear       Remove the cached bootstrap files
 package
  package:discover     Rebuild the cached package manifest
 queue
  queue:batches-table  Create a migration for the batches database table
  queue:clear          Delete all of the jobs from the specified queue
  queue:failed         List all of the failed queue jobs
  queue:failed-table   Create a migration for the failed queue jobs database table
  queue:flush          Flush all of the failed queue jobs
  queue:forget         Delete a failed queue job
  queue:listen         Listen to a given queue
  queue:prune-batches  Prune stale entries from the batches database
  queue:prune-failed   Prune stale entries from the failed jobs table
  queue:restart        Restart queue worker daemons after their current job
  queue:retry          Retry a failed queue job
  queue:retry-batch    Retry the failed jobs for a batch
  queue:table          Create a migration for the queue jobs database table
  queue:work           Start processing jobs on the queue as a daemon
 route
  route:cache          Create a route cache file for faster route registration
  route:clear          Remove the route cache file
  route:list           List all registered routes
 sail
  sail:install         Install Laravel Sail's default Docker Compose file
  sail:publish         Publish the Laravel Sail Docker files
 schedule
  schedule:list        List the scheduled commands
  schedule:run         Run the scheduled commands
  schedule:test        Run a scheduled command
  schedule:work        Start the schedule worker
 schema
  schema:dump          Dump the given database schema
 session
  session:table        Create a migration for the session database table
 storage
  storage:link         Create the symbolic links configured for the application
 stub
  stub:publish         Publish all stubs that are available for customization
 vendor
  vendor:publish       Publish any publishable assets from vendor packages
 view
  view:cache           Compile all of the application's Blade templates
  view:clear           Clear all compiled view files
```

