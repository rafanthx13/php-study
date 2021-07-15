# 03 - Start Project and Structure of Files

## Criar projeto

O do alravel isntalado gloalmente (laravel 8)

````
> laravel new my_project
````

Criar um projeto laravel de uma versão expecifica

````
> composer create-project laravel/laravel="6.*" my_project
````

composer create-project --prefer-dist laravel/laravel=8.5.9 03_app_locadora_carros



## Executar projeto

Executado no root da pasta do projeto laravel. Abre um servidor em ``localhost:8000``.

````
> php artisan serve
````

## File/Directory Structure

````
│   .editorconfig
│   .env
│   .env.example
│   .gitattributes
│   .gitignore
│   .styleci.yml
│   artisan
│   composer.json
│   composer.lock
│   package.json
│   phpunit.xml
│   README.md
│   server.php
│   webpack.mix.js
│
├───app
│   │   User.php
│   │
│   ├───Console
│   │       Kernel.php
│   │
│   ├───Exceptions
│   │       Handler.php
│   │
│   ├───Http
│   │   │   Kernel.php
│   │   │
│   │   ├───Controllers
│   │   │   │   Controller.php
│   │   │   │
│   │   │   └───Auth
│   │   │           ConfirmPasswordController.php
│   │   │           ForgotPasswordController.php
│   │   │           LoginController.php
│   │   │           RegisterController.php
│   │   │           ResetPasswordController.php
│   │   │           VerificationController.php
│   │   │
│   │   └───Middleware
│   │           Authenticate.php
│   │           CheckForMaintenanceMode.php
│   │           EncryptCookies.php
│   │           RedirectIfAuthenticated.php
│   │           TrimStrings.php
│   │           TrustProxies.php
│   │           VerifyCsrfToken.php
│   │
│   └───Providers
│           AppServiceProvider.php
│           AuthServiceProvider.php
│           BroadcastServiceProvider.php
│           EventServiceProvider.php
│           RouteServiceProvider.php
│
├───bootstrap
│   │   app.php
│   │
│   └───cache
│           .gitignore
│           packages.php
│           services.php
│
├───config
│       app.php
│       auth.php
│       broadcasting.php
│       cache.php
│       database.php
│       filesystems.php
│       hashing.php
│       logging.php
│       mail.php
│       queue.php
│       services.php
│       session.php
│       view.php
│
├───database
│   │   .gitignore
│   │
│   ├───factories
│   │       UserFactory.php
│   │
│   ├───migrations
│   │       2014_10_12_000000_create_users_table.php
│   │       2014_10_12_100000_create_password_resets_table.php
│   │       2019_08_19_000000_create_failed_jobs_table.php
│   │
│   └───seeds
│           DatabaseSeeder.php
│
├───docs
│       udemy-laravel-6.png
│
├───public
│       .htaccess
│       favicon.ico
│       index.php
│       robots.txt
│       web.config
│
├───resources
│   ├───js
│   │       app.js
│   │       bootstrap.js
│   │
│   ├───lang
│   │   └───en
│   │           auth.php
│   │           pagination.php
│   │           passwords.php
│   │           validation.php
│   │
│   ├───sass
│   │       app.scss
│   │
│   └───views
│           welcome.blade.php
│
├───routes
│       api.php
│       channels.php
│       console.php
│       web.php
│
├───storage
│   ├───app
│   │   │   .gitignore
│   │   │
│   │   └───public
│   │           .gitignore
│   │
│   ├───framework
│   │   │   .gitignore
│   │   │
│   │   ├───cache
│   │   │   │   .gitignore
│   │   │   │
│   │   │   └───data
│   │   │           .gitignore
│   │   │
│   │   ├───sessions
│   │   │       .gitignore
│   │   │       nJiFriMfhnKBXusifkpXNBkTxKFJ43jYKSndKYxu
│   │   │
│   │   ├───testing
│   │   │       .gitignore
│   │   │
│   │   └───views
│   │           .gitignore
│   │           210f35bbdc7f010d0c79c354007bebfc5ba4ab71.php
│   │
│   └───logs
│           .gitignore
│
└───tests
    │   CreatesApplication.php
    │   TestCase.php
    │
    ├───Feature
    │       ExampleTest.php
    │
    └───Unit
            ExampleTest.php
````

### Onde fica cada oisa

Controller: App/controller
Models: app/Models
View: resource/views

routse/web.php: guardas os mapeamento da url com os tempaltes a ser mostrado

### Blade

É semçahnte ao thymeleaf, serve para trabalhar com templates.

Blade é o template engine com laravel. Nâo é o php normal, mas é compiladro e convertido em PHP.