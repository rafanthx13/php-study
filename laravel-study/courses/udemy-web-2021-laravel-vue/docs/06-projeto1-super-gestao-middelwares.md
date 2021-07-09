## Projeto 1 - Super gestão - Seção 10: Middlewares

Os Middlewares interceptam as requisições HTTP antes de chegar no controller (quando está indo para o controller) e na Response (retorno do Controller).

**Exemplo do que pode fazer no Middleware**

Ex: Verificar IP de Origem, Questionar permissão, questionar token, adicionar mais dado para request/response; Registrar os acessos ás rotas; Gerar Logs; Se aceitou ou não os termos de uso; Padronização de Cabeçalho

## Middlewares



### Criar MiddleWare

Comando

```
php artisan make:middleware LogAcessoMiddleware
```

Será gerado em app/HTTP/Middleware

```
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class LogAcessoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);
    }
}

```

### Adicionar a rotas

em routes/web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogAcessoMiddleware;

....

Route::get('/contato', 'ContatoController@contato')
    ->name('site.contato')
    ->middleware(LogAcessoMiddleware::class);
```



O método a seguir

```
return $next($request);
```

passa adiante o middleware. Se ao invez disso fizermos	

```
return Response('Chegamos no middleware e finalizamos no próprio middleware');
```

Aí, ao acessar /contato vai retornar essa response

### Teste com o model `LogAcesso`

É criado o model LogAcesso e também sua migration

`LogAcessoMigration`

```
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogAcessosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_acessos', function (Blueprint $table) {
            $table->id();
            $table->string('log', 200);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_acessos');
    }
}

```

`LogAcessoMiddleWare`

```
<?php

namespace App\Http\Middleware;

use Closure;
use App\LogAcesso;

class LogAcessoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //$request - manipular
        // return $next($request);

        $ip = $request->server->get('REMOTE_ADDR');
        $rota = $request->getRequestUri();
        LogAcesso::create(['log' => "IP $ip requisitou a rota $rota"]);

        return Response('Chegamos no middleware e finalizamos no próprio middleware');
    }
}

```

web.php

```
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LogAcessoMiddleware;

Route::middleware(LogAcessoMiddleware::class)
    ->get('/', 'PrincipalController@principal')
    ->name('site.index');

```

### Colocar Middleare nos controllers

É feito no método contrutor do controller.

`SobreContatoController.php`

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Middleware\LogAcessoMiddleware;

class SobreNosController extends Controller
{
    public function __construct() {
        $this->middleware(LogAcessoMiddleware::class);
    }
    public function sobreNos() {
        return view('site.sobre-nos');
    }
}
```

### Implementando em todas as rotas

Isso é feito no arquivo **`Kernel.php`**

Nele é lsitado os middlewares que são chamados em todas as rotas

```PHP
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    // APLICA EM TODAS AS REQUISÇÔES
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        // APLICA SÓ EM WEB.PHP
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            // ADICIONAMOS LOG ACESSO EM TODAS AS ROTAS DE WEB.PHP
            \App\Http\Middleware\LogAcessoMiddleware::class
        ],

        // APLICA SÓ EM API.PHP
        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    // APLICA EM CERTOS LUGARES
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}

```

### Apelidar middlewarse

é em `protected #routeMiddleware` do `Kernel.php`. Você adiciona a chave que será o apelido e o middleware.

```
protected $routeMiddleware = [
    
        'log.acesso' => \App\Http\Middleware\LogAcessoMiddleware::class
    ];
```

.

```
Route::get('/', 'PrincipalController@principal')->name('site.index')->middleware('log.acesso');
```

se q

```
class SobreNosController extends Controller
{
    public function __construct() {
        $this->middleware('log.acesso');
    }

    public function sobreNos() {
        return view('site.sobre-nos');
    }
}

```

### Encadeamento de MiddleWares

Criamos o Middleware de autenticação `AutenticacaoMiddleware.php`

```
<?php

namespace App\Http\Middleware;

use Closure;

class AutenticacaoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //verifica se o usuário possui acesso a rota
        if(false) {
            return $next($request);
        } else {
            return Response('Acesso negado! Rota exige autenticação!!!');
        }
    }
}

```

Vamos alterar o LogAcessoMiddleware

```
<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\LogAcesso;

class LogAcessoMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        $ip = $request->server->get('REMOTE_ADDR');
        $rota = $request->getRequestUri();
        LogAcesso::create(['log' => "IP $ip requisitou a rota $rota"]);

        return $next($request);

    }
```

Vamos por 2 apelidos para eles em Kernel.php

```
 protected $routeMiddleware = [
        'log.acesso' => \App\Http\Middleware\LogAcessoMiddleware::class,
        'autenticacao' => \App\Http\Middleware\AutenticacaoMiddleware::class
    ];
```

Depois encadeamos junto do log acesso em web.php

```
Route::prefix('/app')->group(function() {
    Route::middleware('autenticacao')
            ->get('/clientes', function(){return 'Clientes';})
            ->name('app.clientes');

    Route::middleware('autenticacao')
            ->get('/fornecedores', 'FornecedorController@index')
            ->name('app.fornecedores');

    Route::middleware('autenticacao')
            ->get('/produtos', function(){return 'produtos';})
            ->name('app.produtos');
});
```

### Adicionando a um grupo de rotas

Basta colocar na frente de `Route`

```
Route::middleware('autenticacao')->prefix('/app')->group(function() {
    Route::get('/clientes', function(){return 'Clientes';})->name('app.clientes');
    Route::get('/fornecedores', 'FornecedorController@index')->name('app.fornecedores');
    Route::get('/produtos', function(){return 'produtos';})->name('app.produtos');
});
```

### Passando parametros

Altermaos o middleware para receber parameros

AutenticacaoMiddlewares.php

```
<?php

namespace App\Http\Middleware;

use Closure;

class AutenticacaoMiddleware
{
                                                    // padrao, visitante, p3, p4
    public function handle($request, Closure $next, $metodo_autenticacao, $perfil, $param3, $param4)
    {
        //verifica se o usuário possui acesso a rota
        echo $metodo_autenticacao.' - '.$perfil.'<br>';

        if($metodo_autenticacao == 'padrao') {
            echo 'Verificar o usuário e senha no banco de dados'.$perfil.'<br>';
        }

        if($metodo_autenticacao == 'ldap') {
            echo 'Verificar o usuário e senha no AD'.$perfil.'<br>';
        }

        if($perfil == 'visitante') {
            echo 'Exibir apenas alguns recursos';
        } else {
            echo 'Carregar o perfil no banco de dados';
        }

        if(false) {
            return $next($request);
        } else {
            return Response('Acesso negado! Rota exige autenticação!!!');
        }
    }
}

```



web.php

```
Route::middleware('autenticacao:padrao,visitante,p3,p4')->prefix('/app')->group(function() {
    Route::get('/clientes', function(){return 'Clientes';})->name('app.clientes');
    Route::get('/fornecedores', 'FornecedorController@index')->name('app.fornecedores');
    Route::get('/produtos', function(){return 'produtos';})->name('app.produtos');
});
```

ou seja, estamos pasadno as string: 'padrao', 'visitante, 'p3', 'p4',

## Middleware para a Response

Temos que fazer algo como : `$resposta = $next($request);`

Ou seja, passa pelo Middleware e na volta,volta a ele denovo, como uma cebol

```
<?php

namespace App\Http\Middleware;

use Closure;
use App\LogAcesso;

class LogAcessoMiddleware
{
 
    public function handle($request, Closure $next)
    {

        $ip = $request->server->get('REMOTE_ADDR');
        $rota = $request->getRequestUri();
        LogAcesso::create(['log' => "IP $ip requisitou a rota $rota"]);

		// Vai proseguir no encadeamento, e na volta, passar por aqui
		// Assim, esse méotodo pega tanto request quanto response.
		//	=> é por isso que nâo é chamado repsonse, porque já é o retorno de $mext($request)
        $resposta = $next($request);

        $resposta->setStatusCode(201, 'O status da resposta e o texto da resposta foram modificados!!!');

        return $resposta;

    }
}

```

