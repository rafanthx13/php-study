# Projeto 1 - Super gestão

Forma antiga de programação (front fornecido no back)

## Criar e subir projeto

`composer create-project -prefer-dist laravel/laravel 01_app_super_gestao "7.0"`

Executar php unicamente

`php -S localhost:8000` na pasta public ou `php artisan serve`

## Artisan Console

Muitas tarefas do laravel podem ser feitas com artisan.

Para executá-lo devemos está na raiz do projeto.

`php artisan list`

## Seção 5 e 6 do curso: Introdução as Rotas, Controller e Views

De inicio vamos usar o `Routes/web.php` para fornecer view, como o velho php

```php
Route::get('/', function () {
    return view('welcome');
});

```

+ Nossa rotas são gerenciados pelo objeto Routes
+ Usamos o GET do HTTP, na URL '/'.
+ Em seguida vem o callback o retorno dessa ação, que será chamara a view `welcome`

### Juntando Routes com Controllers

Defino as rotas em `web.php `

```php
Route::get('/', 'PrincipalController@principal');

Route::get('/sobre-nos', 'SobreNosController@sobreNos');

Route::get('/contato', 'ContatoController@contato');
```

Depois chamo os controller, como por exemplo o abaixo

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContatoController extends Controller
{
    public function contato() {
        echo 'Contato';
    }
}

```

SIM. em Routes, passamos apenas a string com o controller e a **action** que o php vai entender

### Unindo Controllers com Views

O Laravel usa o Blade, então, suas views devem terminar com `file.blade.php`

Em `resource/views` vamos criar mais pastas `app/` e `site/`

Por exemplo, com a parte de contato vai ficar

`resource/views/site/contato.blade.php`

```php+HTML
<h3>Contato (view)</h3>
```

`app/Http/Controllers/ContatoControler.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ContatoController extends Controller
{
    public function contato() {
        return view('site.contato');
    }
}
```

OBS: O laravel tem com default que as nossa views estão em `resource/views` , por isso não precisa de explicitar todo o caminho nem sua extensão.

### Passagem de parâmetros em Routes

### Passando parâmetros

a sequencia URL=>Parâmetros da função *não precisam ter  mesmo nome, mas é recomendável para entendermos melhor*.

**OBS**: Fazemos uma tipagem nos valores, assim, se vocÊ passar 100, poderar ser stringou int a dependenr do que por na callback

```php
Route::get(
    '/contato/{nome}/{categoria}/{assunto}/{mensagem}',
    function(string $nome, string $categoria, string $assunto, string $mensagem) {
        echo "Estamos aqui: $nome - $categoria - $assunto - $mensagem";
    }
);
```

teste com: `http://127.0.0.1:8000/contato/Rafael/FinalFantasy/Zodiac/Uqlan`

#### Parâmetros Opcionais e default

**Parâmetros Opcionais**: Os parâmetros opcionais devem ser os últimos. pra ser opcional deve ter o `?` na frente

**Valores Default**: É feito com um `=` na função de callback



Exemplo: São todos opcionais com valores default

```php
Route::get(
    '/contato/{nome?}/{categoria?}/{assunto?}/{mensagem?}',
    function(
        string $nome = 'Desconhecido',
        string $categoria = 'Informação',
        string $assunto = 'Contato',
        string $mensagem = 'mensagem não informada'
    ) {
        echo "Estamos aqui: $nome - $categoria - $assunto - $mensagem";
    }
);
```

#### Validando a roa com regex

Podemos dizer condições para que assim só aceite os parâmetros quando estiverem em um formato adequado

```php
Route::get(
    '/contato/{nome}/{categoria_id}',
    function(
        string $nome = 'Desconhecido',
        int $categoria_id = 1 // 1 - 'Informação'
    ) {
        echo "Estamos aqui: $nome - $categoria_id";
    }
)->where('categoria_id', '[0-9]+')->where('nome', '[A-Za-z]+');

```

Se, o parâmetro passado for inválido, ele não dá um erro interno, lê simplesmente diz que a 'rota não foi encontra - error 404'.

Há aqui duas regex, uma para validar numero e outra para validar uma string

### Agrupar com prefixo

Podemos colocar um prefixo para um conjunto de rotas

```php
Route::prefix('/app')->group(function(){
    Route::get('/clientes', function(){return 'Clientes';});
    Route::get('/fornecedores', function(){return 'Fornecedores';});
    Route::get('/produtos', function(){return 'produtos';});
});
```

### Nomeando Rotas

para nomear, basta adicionar `->name('nom_dela')`

Esse nomes so funcionam dentro da aplicação php. Assim, para usar elas no html temos que por entre 2 chaves.

como fica as rotas nomeadas

```php
Route::get('/login', function(){return 'Login';})->name('site.login');

Route::prefix('/app')->group(function(){
    Route::get('/clientes', function(){return 'Clientes';})->name('app.clientes');
    Route::get('/fornecedores', function(){return 'Fornecedores';})->name('app.fornecedores');
    Route::get('/produtos', function(){return 'produtos';})->name('app.produtos');
});

```

no html `principal.blade.php`

```php+HTML
<h3>Principal (view)</h3>

<ul>
    <li>
        <a href="{{ route('site.index') }}">Principal</a>
    </li>
    <li>
        <a href="{{ route('site.sobrenos') }}">Sobre nós</a>
    </li>
    <li>
        <a href="{{ route('site.contato') }}">Contato</a>
    </li>
</ul>

```

**Vantagem:** Faz com que nossos links não tenham uma dependência direta entre si, assim, eu posso mudar a url de Routes, mas como está nomeada, **NAO VOU PRECISAR MUDAR NO HTML**

### Redirecionar Rotas

Vamos criar duas rotas adicionais para fazer esse teste

**Redirect do próprio objeto Route**

```php
Route::get('/rota1', function() {
    echo 'Rota 1';
})->name('site.rota1');

Rote::redirect('/rota2', '/rota1');
```

**Redirect dentro do callback do Route ou num Controller**

```php
Route::get('/rota1', function() {
    echo 'Rota 1';
})->name('site.rota1');

Route::get('/rota2', function() {
    return redirect()->route('site.rota1');
})->name('site.rota2');
```

### Rota de NOT FOUND

`´Route::fallback` será acessada quando acessar algo inexistente

```php
Route::fallback(function() {
    echo 'A rota acessada não existe. <a href="'.route('site.index').'">clique aqui</a> para ir para página inicial';
});
```

