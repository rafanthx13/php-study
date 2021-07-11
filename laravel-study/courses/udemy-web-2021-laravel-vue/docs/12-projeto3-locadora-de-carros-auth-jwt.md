# Seção 18: App 03 - Locadora de Carros - Autenticação JWT

Como é todo o processo de autenticação JWT para API:

1. O cliente envia usuário e senha para API
2. Essa válida os dados, se estiverem autenticados, retorna um token de autorização e armazena esse token no BD. O token retornado devera ser guardado e usado nas requisições futuras
3. Com esse Token o usuário pode acessar a API. 
4. (HAPPY END) Cada rota vai verificar o 'Bearer' do token, e verificar se for valido. Se sim, acessa o recurso
5. (DEAD END) tempo do token expirado, token invalido

## Instalando o pacote jwt-auth

Existem vários outros pacotes, mas vamos usar esse

```sh
composer require tymon/jwt-auth "1.0.2"
```

## Configurar jwt

Documentação para a lib `jwt-auth`: https://jwt-auth.readthedocs.io/en/develop/

São 3 arquivos que devem ser iniciados

`config/app.php`: É colocado  em `providers` a referencia a lib

```php
'providers' => [
	Tymon\JWTAuth\Providers\LaravelServiceProvider::class,
]
```

`config/jwt.php` : Esse arquivo é criado com o comando abaixo

```shell
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
```

depois geramos a chave com o comando a seguir

```shell
php artisan jwt:secret
```

Vai criar o secrete no `.env` 

## Começar a usar o JWT

Por default, quando se instala o Laravel já vem um User no Models.

Vamos reaproveita-lo

Vamos por os seguintes códigos no model de User:

OBS: Esses código vinheram da documentação

```php
<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; //H ERE

class User extends Authenticatable implements JWTSubject // HERE
{
    use HasFactory, Notifiable;


    protected $fillable = [
        'name',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() // HERE
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() // HERE
    {
        return [];
    }
}

```

Agora em `config/auth.php`

vamos mudar PARA usar autenticação por token

```php
api' => [
            'driver' => 'jwt',
            'provider' => 'users',
            'hash' => false,
        ],
```

## Rotas da autenticação

`login`, `logout`, `refresh`, me

```sh
php artisan make:controller AuthController
```

em `api.php` vai ficar

```php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

`AuthController`: Como vai começar **inicialmente**

```php
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

## Adicionar Usuário Manualmente pelo Tinker

Vamos usar o tinker e adicionar por esse shell

```sh
php artisan tinker
>> $user = new app\models\user();
>> $user->name = 'jorge'
>> $user->email = 'jorge#tests'
>> $user->password = bcrypt('1234')
>> var_dump(user->getattributes())
```

## Implementando método de login

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request) {
        
        $credenciais = $request->all(['email', 'password']); //[]

        // autenticação (email e senha)
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

## Protegendo as rotas com middleware

Usamos middlewares, para isso começamos adicionando seu apelido em `Kernel.php` de http em `'$routeMiddleware '`

`app/Http/Kernel.php` : registrar middleware para ser usado nas APIs

```php
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

`api.php`: Usando middleware registrado anteriormente e agrupando as rotas. Adicionamos o prefixo de `v1/`

```php
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

## Como usar o token

Mande login e senha por JSON e assim armazenar o token

ele fica na chave `Authorization`e o valor

```
Bearer o_seu_token_582y395y23958y32952938529
```

e também deixar `Accept: application/json`

## O resto dos métodos

+ `me()`
  +  saber quem é o usuário e senha
  + retorna dados do usuário, tudo menos o password criptografado:` (id, name, email, email_evrified_at, creat_at, update_at)`

+ `refresh()`: 
  + renovar a autorização (so funciona se já estiver autenticado). Tem que passar o token no header corretamente, aí, irá retornar um novo token, e o outro token vai se anulado

+ `logout()`
  + ele coloca o token na blacklist, ficam proibido. Perceba, não é retirar o token, e sim INAVALIDALO

```php
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

**Recomendação:** Não coloque no `paylod` dados sensíveis como user/senha, pois são facilmente decriptografados em `jwt.io`

## Configurar tempo do token

Na variável de ambiente `.env` : Equivale a 2 horas

```
JWT_TTL=120 
```

