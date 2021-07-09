# Seção 20: App 05 - Portal de Notícias - Armazenamento em Memória Redis (Laravel Cache)

Bando de DADOS EXTREMAMENTE RÁPIDO PORQUE É EM MEMÓRIA

REDIS (Remote dictionary Server) ( Servidor de Dicionário Remoto)

É uma memória em cache com Banco De Dados. Fica na memória RAM e ele fornece Snapshot para salvar caso dê queda de energia

**Como vamos usá-lo**: Vamos usá-lo como memoria cache para evitar acessar o banco de dados físico. **AO INVEZ DE MANDAR UMA REQUEST PARA UM SGBDE FAZER UM 'SELECT' VAMOS PEGAR DIRETO DA RAM, DO CACHE**

## Start Project

```
composer create-project --prefer-dist laravel/laravel=8.5.20 05_app_portal_noticias
```

## Banco SQL

criar banco

```SQL
create database pn;
```

criar: Model, Factory, Seed e Controller no Laravel

```
php artisan make:model Noticia -a
```

## Criar Migration, Seeder e Factory

Migration de de `noticias`

````php
public function up()
    {
        Schema::create('noticias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 40);
            $table->text('noticia');
            $table->timestamps();
        });
    }
````

No Model, para permitir que se possa usar algumas funções

````php
protected $fillable = ['titulo', 'noticia']
````

`NoticiaSeeder.php` : esse seeder vai chamar a factory e vai executar vários `INSERT`

````php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class NoticiaSeeder extends Seeder
{
    public function run()
    {
        ini_set('memory_limit', '512M'); // para nao limitar a memoria, pois por default é 128MB
        \App\Models\Noticia::factory(1000)->create();
    }
}

````

`NoticiaFactory.php`: Usamos a lib `faker` para gera dados

````php
<?php

namespace Database\Factories;

use App\Models\Noticia;
use Illuminate\Database\Eloquent\Factories\Factory;

class NoticiaFactory extends Factory
{
    /
    protected $model = Noticia::class;
  
    public function definition()
    {
        return [
            'titulo' => $this->faker->text(rand(10, 40)),
            'noticia' => $this->faker->text(rand(200, 400))
        ];
    }
}

````

Executar a seeder

```
php artisan db:seed --class=NoticiaSeeder
```

E no banco MYSQL deverá ser feito vários INSERT. Lembre-se de configurar o banco mysql em `.env`

## Routes e o Noticia Controller


em `routes/web.php`


````php
Route::Resource('noticia', App\Http\Controllers\NoticiaController::class);
````

`NoticiaController` : Vamos usar só o método index

````php
public function index() 
	{
        $noticias = Noticia::orderByDesc('created_at')->limit(10)->get();
        return view('noticia', ['noticias' => $noticias]);
	}
````

com a view `noticia.blade.php`

````php+HTML
<table>
    <thead>
        <tr>
            <th>Título</th>
            <th>Notícia</th>
        </tr>
    </thead>

    <tbody>
        @foreach($noticias as $noticia)
            <tr>
                <td>{{ $noticia->titulo }}</td>
                <td>{{ $noticia->noticia }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
````

## Debugbar : Analisar melhor o Laravel

```
composer require barryvdh/laravel-debugbar=v3.6.2 --dev
```

configurara ele.

em `config/app.php` na ultima linha

```
'aliases' => [
	'Debugbar' => Barryvdh\Debugbar\Facade::class
];
```

ao subir a aplicação ``php artisan serve``/ vaia aparecer uma barra embaixo que na aba de 'Queries' vai fornecer as queries feitas pela nossa aplicação Laravel

## Pacote Predis

```
composer require predis/predis=v1.1.7
```

no .env vamos configurar as coisa do REDIS

parte que é alterado

```
CACHE_DRIVER=redis
```

configurações do Redis

```
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_CLIENT=predis
```

## Mandar e pegar dados no Redis

manda algo por 10 segundos e depois apaga no Redis. pega também esse mesmo dado de lá.

````php
use Illuminate\Support\Facades\Cache;

class NoticiaController extends Controller {
    
	public function index(){

	        $noticias = [];
        
            // criar um dado dentro do bd Redis
            // chave, valor, tempo em segundos para expirar o dado em memória
            Cache::put('site', 'jorgesantana.net.br', 10);

            // recuperar um dado dentro do bd Redis
            $site = Cache::get('site');
            echo $site;
    }
}
````

## Desafogar o BD

````php
use Illuminate\Support\Facades\Cache;
class NoticiaController extends Controller {
 	public function index() {

        $noticias = [];
        if(Cache::has('dez_primeiras_noticias')) {
            $noticias = Cache::get('dez_primeiras_noticias');
        } else {
            $noticias = Noticia::orderByDesc('created_at')->limit(10)->get();
            Cache::put('dez_primeiras_noticias', $noticias, 15);
        }       
        return view('noticia', ['noticias' => $noticias]);
    }
}
````

## Método `remember()`

É uma forma mais enxuta de fazer o que vimos antes é converter para uma forma mais simples.

Isso porque essa lógica é comum para programas que utilizam Redis  então já temos uma função do Predis preparada para isso.

````php
if(Cache::has('dez_primeiras_noticias')) {
    $noticias = Cache::get('dez_primeiras_noticias');
} else {
    $noticias = Noticia::orderByDesc('created_at')->limit(10)->get();
    Cache::put('dez_primeiras_noticias', $noticias, 15);
}             
````

para a forma com `remember`

````php
$noticias = Cache::remember('dez_primeiras_noticias', 15, function() {
    return Noticia::orderByDesc('created_at')->limit(10)->get();
});

````

## Ultimas Observações

Tentar fazer esse exemplo utilizando o redis do heroku 'archive-person-api'