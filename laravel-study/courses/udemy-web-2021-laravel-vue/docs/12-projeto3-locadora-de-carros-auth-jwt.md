# Seção 18: App 03 - Locadora de Carros - Autenticação JWT

O que é autenticaçâo:

1. O cliente envia usuário e senha para API
2. Essa vlida os dados, se estiverem autenticados, retorna um token de autorizaçâo e armazena esse token no BD. O token retornado devera ser guardado e usado nas requisiçôes futuras
3. Com esse Token o usário pode acessar a API. 
4. (HAPPY END) Cada rota vai verificar o 'Bearer' do token, e verificar se for valido. Se sim, acessa o recruso
5. (DEAD END) tempo do token expirado, token invalido

## instalando o pacote jwt-auth

existem varios outros pacotes, mas vamos usar esse

```
composer require tymon/jwt-auth "1.0.2"
```

## COnfiigurar jwt



https://jwt-auth.readthedocs.io/en/develop/

Sao 3 arguiso

`config/app.php`

```
'providers' => [
Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]
```

`config/hwt.php`

criado com o comando

```
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

depois

```
php artisan jwt:secret
```

cria a chave no `.env` e vamos por o tempo do token

```
JWT_TTL=120
```



## Começar a usar o JWT

Por default, quando se isntla o laraelvel já vem um User no Models.

Vamos reaproveitá-lo

Vamos por os seguintes codigos

```
use Tymon\JWTAuth\Contracts\JWTSubject; // importar

class User extends Authenticatable implements JWTSubject
{ // implementar

// copiar eesse dois codigos do docs

   /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
```





```
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}

```

Agora em `config/auth.php`

vamos mudar poara usar autenticaão por token

```
api' => [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ],
```

## Rotas da autenticaçâo

login, logu, refresh, me

php artisan make:controller AuthcONTROLER

em api.php

```
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::resource('cliente', 'App\Http\Controllers\ClienteController');
Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
Route::apiResource('carro', 'App\Http\Controllers\CarroController');
Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('logout', 'App\Http\Controllers\AuthController@logout');
Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
Route::post('me', 'App\Http\Controllers\AuthController@me');
```

conroller

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login() {
        return 'login';
    }

    public function logout() {
        return 'logout';
    }

    public function refresh() {
        return 'refresh';
    }

    public function me() {
        return 'me';
    }
}

```

### aDICIONAR USER

VAMOS USAR O TINKER

PHP ARTISAN TINKER

$USER = NEW aPP\mODELS\uSER();

$user->name = 'jorge'

$user->email = 'jorge#tests'

$user->password = bcrypt('1234')

var_dump(uSER->GETaTTRIBUTES())

## LOGIN

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        
        $credenciais = $request->all(['email', 'password']); //[]

        //autenticação (email e senha)
        // USAMOS API POR CAUS DO config/auth.php que configuramo antes
        $token = auth('api')->attempt($credenciais);
        
        if($token) { //usuário autenticado com sucesso
            return response()->json(['token' => $token]);

        } else { //erro de usuário ou senha
            return response()->json(['erro' => 'Usuário ou senha inválido!'], 403);

            //401 = Unauthorized -> não autorizado
            //403 = forbidden -> proibido (login inválido)
        }

        //retornar um Json Web Token
        return 'login';
    }

    public function logout() {
        return 'logout';
    }

    public function refresh() {
        return 'refresh';
    }

    public function me() {
        return 'me';
    }
}

```

### Protegendo as rotas

Usamos middlewares, para isos começamos adicionando seu apelido em kenrnel.php de http em '$routeMiddleware '

app/Http/Kernel

```
protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'jwt.auth' => \Tymon\JWTAuth\Http\Middleware\Authenticate::class,
    ];
```

api.php

É feito duas coisa

+ Agurpar num `group`, aplicar um middlewares de jwt.auth em todas elas, adicionar um prefixo da vesao da nossa api

```
Route::prefix('v1')->middleware('jwt.auth')->group(function() {
    Route::post('me', 'App\Http\Controllers\AuthController@me');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
    Route::apiResource('carro', 'App\Http\Controllers\CarroController');
    Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
    Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
    Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');
});

Route::post('login', 'App\Http\Controllers\AuthController@login');


```

# Como usar o token

Mande login e senha por JSON e assim  aramazendar o token

ele fica na chave `Authorization`e o valor

```
Bearer o_seu_token_582y395y23958y32952938529
```

e tambem deixar `Accept: application/json`

### os outros  Metodos

me: saber quem é o usaurio e senha

me: retorna dados do usuario, tudo menos o password encriptografado: id, name, email, email_evrified_at, creat_at, update_at

refrehs: renovar a autorizaç^o(so funciona se ja estiver autenticado). Tm que passar o token no header corretamente, aí, irá retornar um novo token, e o outro token vai se anuldao



logout: ele coloca o token na blacklist, ficam proibido. Perceba, nao é retirar o token, e sim INAVALIDALO

```
public function logout() {
        auth('api')->logout();
        return response()->json(['msg' => 'Logout foi realizado com sucesso!']);
    }

    public function refresh() {
        $token = auth('api')->refresh(); //cliente  tem que encaminhar um jwt válido  no hedar
        return response()->json(['token' => $token]);
    }

    public function me() {
        return response()->json(auth()->user());
    }
```

rECOMENDAÇaO: NAO COLQOUE NO PAYLOAD RECOMENDAÇÔES SENSNIVEIS COMO USER/SENHA

## Configurar tmepo

 na variavel de ambiente

```
JWT_TTL=120 
```

Equivale a 2 hotas