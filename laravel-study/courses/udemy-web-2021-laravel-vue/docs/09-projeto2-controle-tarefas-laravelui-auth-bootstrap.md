# Seção 13: 02_app_controle_tarefas

Vamos fazer a autenticaçâo do próprio laravel.

Vamos também usar tecnologias mais naovas:

+ Laravel 8
+ Utilzir frameworks front end com NPM/Node 
  + Utilizaremos BootStrap

```
composer create-project --prefer-dist laravel/laravel 02_app_controle_tarefas "8.5.9"
```

Depois de instalado o node/npm

```
composer require laravel/ui:^3.2
```

se fizer php artisan list você vera que temos novos comandos, o `ui`

Com o projeto criado, executamos na raiz

```
php artisan ui bootstrap --auth
```

poderia ser vue ou react

--auth: vai vir tudo para autenticaçâo

depois executon

```
nmp i
npm run dev
```

Va gerar os assets para nosso servidor laravel. NAO É UM SERVIDOR, é um buuild de assets

## Laravel UI

Ele nos permite iniciar o nosso laravel com framework front-end (react, vue, bootstrap)

com a flag `--auth` adiciona a autenitcaçao default do laravel

### O que acaontece

O vídeo tem 24 mintos

+ `Routes/web.php` Será criado várias rotas
+ `views` será criada várias views

Configruar o banco

```
create database ct;
```

executar as migrations

```
php artisan migrate
```



## Como é o Scaffold

No vídeo 207, (16 minutos) ele vai mostrando como é o esqueleto e aonde navegar.

O laravel usa bastante 'Trait' que é como um require/include do JS.

```
php artisan serve
```

para verificar as rotas e entender melhor

```
php artisan route:list
```

Muita coisa de `auth` é feita apartir de TRAI. `use RegisterUsers` e `Authentican`. Aí ele acessar um código do `vendor`

`vendor/laravel/ui/auth-back-end/`

**ENTAO LEMBRESE, SE ACHAR ALGUM `use` é TRAIT , tipo o mixini, importando código/função direto, nem memso aparece a definiçâo, mas quemc hama consegue usar normalmente**



## Como a Home é Protegida

HomeController utiliza o método Route

```
class HomeController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    
    public function index()
    {
        return view('home');
    }
}

```

em kernle.php é apelidado

```
 protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
```

Auth  manda para login

```
class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
}
```

Ele vai mandar para a rota de 'login' definida em web.php

web.php

```
<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

```

Esse Auth vem de `Illuminate\Support\Facades\Auth;` e é fedindo por

```
public static function routes(array $options = [])
    {
        if (! static::$app->providerIsLoaded(UiServiceProvider::class)) {
            throw new RuntimeException('In order to use the Auth::routes() method, please install the laravel/ui package.');
        }

        static::$app->make('router')->auth($options);
    }
```

o 'router' é defindo em  Illuminate/Routing/Router.php



aki: https://github.com/laravel/framework/blob/5.8/src/Illuminate/Routing/Router.php



## Criar uma nova tarefa

```
php artisan make:controller --resource TarefaController --model=Tarefa
```

Nosso controlador vai criar os métodos default

```
<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function show(Tarefa $tarefa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function edit(Tarefa $tarefa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tarefa $tarefa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarefa  $tarefa
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tarefa $tarefa)
    {
        //
    }
}

```

em router/web.php

```
Route::resource('tarefa', 'App\Http\Controllers\TarefaController');
```

colocando validaçâo em nosso tarefaController

```
public function __construct() {
        $this->middleware('auth');
    }
// ou pode colcoar na chamada da rota
```

## Como fazer dentro de métodos especificos do Controller

vamos por exemplo verificar a autenticaçAo de tro do métpodo index do TarefaController

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tarefa;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class TarefaController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
   
    public function index()
    {

        $id = auth()->user()->id;
        $name = auth()->user()->name;
        $email = auth()->user()->email;

        return "ID: $id | Nome: $name | Email: $email";

        /*
        if(Auth::check()) {
            $id = Auth::user()->id;
            $name = Auth::user()->name;
            $email = Auth::user()->email;

            return "ID: $id | Nome: $name | Email: $email";
        } else {
            return 'Você não está logado no sistema';
        }

        
        if(auth()->check()) {
            $id = auth()->user()->id;
            $name = auth()->user()->name;
            $email = auth()->user()->email;

            return "ID: $id | Nome: $name | Email: $email";
        } else {
            return 'Você não está logado no sistema';
        }
        */
    }
```

