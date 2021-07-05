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

## Seção 7: Avançando sobre Views e Controllers

### Passando parâmetros para o controller

O mais importante é a ordem da tipagem do que os nomes dos parâmetros

**Routes** Os parâmetros são passados como partes da URL

```php
Route::get('/teste/{p1}/{p2}', 'TesteController@teste')->name('site.teste');
```

**Controller** são recebidos na função do controller

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TesteController extends Controller
{
    public function teste(int $p1, int $p2) {
        echo "A soma $p1 + $p2 é: ".($p1+$p2);
    }
}

```

O laravel tem inteligência de mandar os parâmetros para o controller diretamente. Os nome entre `route` e `controller` não precisam ser iguais

### Mandando do Controller para o View

Há 3 formas

+ Array associativo

  ```php
  return view('site.teste', ['p1' => $p1, 'p2' => $p2]); //array associativo
  ```
  
+ `compact()` : Cria um array associativo de forma mais simpples

  ```php
  return view('site.teste', compact('p1', 'p2')); //compact
  ```

+ `with()`

  ```
  return view('site.teste')->with('xyz', $p1)->with('zzz', $p2); //with()
  ```

+ Recebendo parâmetros do controller na view

  ```php
  P1 = {{ $xyz }}
  <br />
  P1 = {{ $zzz }}
  
  ```


### Utilização de Assets no Laravel

Assets = Significa "bem" em relação a propriedade

Serão Imagens, sons, scripts, css,  enfim, tudo que complementa a linguagem de marcação a HTML

o Laravel usa o helper `assets` para localiza-los. Ele busca da própria pasta `public/`

**Os assets da nossa aplicação são colocados na pasta public**

```php
css
<link rel="stylesheet" href="{{ asset('css/estilo_basico.css') }}">

imagem
<img src="{{ asset('img/facebook.png') }}">
```

chamamos por `/public/img/image.png`, deve ser colocada em public

## Uso de Template no Blade

### `@yield()` e `@section()`: layout para html

São modelos que permite que agente reaproveite código como header e footer.

Você cria um arquivo que possui parte de um código HTML e que tem tag onde será colocados outros trechos de php/html 

**Como funciona**

+ O HTML que tem o layout terá o método blade :`@yield`()
  + No caso, usamos em `basico.blade.php` que tem o `<header>`
  + Deve-se especificar o nome da `section a ser colocada`
+ As páginas usam esse layout com `@extends()` e `@section()`
  + `@extends()`: indica qual template a nossa view quer estender
  + `@section()`: será o trecho de código que será colocado dentro do `@yield`
    + Ele recebe um nome;  e ao abrir esse bloco, damos um nome

**OBS**

+ O `@extends` já parte de resources/views

`basico.blade.php` : ele é o layout (superior)

```php+HTML
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Super Gestão - Sobre Nós</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/estilo_basico.css') }}">
    </head>

    <body>
        @yield('conteudo')
    </body>
</html>
```

`contato.blade.php` : é a parte específica que usa um layout

```php+HTML
@extends('site.layouts.basico')

@section('conteudo')
    <div class="topo">

        <div class="logo">
            <img src="{{ asset('img/logo.png') }}">
        </div>

        <div class="menu">
            <ul>
                <li><a href="{{ route('site.index') }}">Principal</a></li>
                <li><a href="{{ route('site.sobrenos') }}">Sobre Nós</a></li>
                <li><a href="{{ route('site.contato') }}">Contato</a></li>
            </ul>
        </div>
    </div>
	....

    <div class="rodape">
        <div class="redes-sociais">
            <h2>Redes sociais</h2>
            <img src="{{ asset('img/facebook.png') }}">
            <img src="{{ asset('img/linkedin.png') }}">
            <img src="{{ asset('img/youtube.png') }}">
        </div>
        <div class="area-contato">
            <h2>Contato</h2>
            <span>(11) 3333-4444</span>
            <br>
            <span>supergestao@dominio.com.br</span>
        </div>
        <div class="localizacao">
            <h2>Localização</h2>
            <img src="{{ asset('img/mapa.png') }}">
        </div>
    </div>
@endsection

```

### Passar parâmetros dentro do `@section`

Observe`@yield('titulo')`. Vai por nesse campo a variável `$titulo` que encontrar no arquivo blade. O arquivo blade está `@section('titulo', $titulo)`. Assim, é possível passar parâmetros para essa renderização em forma de template

template: queremos que na parte do @yield use algo que estiver dentro da section com o nome de  `titulo`

```html
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Super Gestão - @yield('titulo')</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/estilo_basico.css') }}">
    </head>

    <body>
        @yield('conteudo')
    </body>
</html>

```

`contato.blade.php` : definimos que o `titulo` será o que vier da variável `$titulo`

```php
@extends('site.layouts.basico')

@section('titulo', $titulo)

@section('conteudo')
```

### `@include`: incluir trecho html

Nesse caso, em básico. blade estamos incluindo o trecho de código de `topo.blade` através do  `@include`

`partials/topo.blade.php`

```php+HTML
<div class="topo">
    <div class="logo">
        <img src="{{ asset('img/logo.png') }}">
    </div>

    <div class="menu">
        <ul>
            <li><a href="{{ route('site.index') }}">Principal</a></li>
            <li><a href="{{ route('site.sobrenos') }}">Sobre Nós</a></li>
            <li><a href="{{ route('site.contato') }}">Contato</a></li>
        </ul>
    </div>
</div>

```

template :: `basico.blade.php`

```php+HTML
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <title>Super Gestão - @yield('titulo')</title>
        <meta charset="utf-8">
        <link rel="stylesheet" href="{{ asset('css/estilo_basico.css') }}">
    </head>

    <body>
        @include('site.layouts._partials.topo')
        @yield('conteudo')
    </body>
</html>

```

### `@component`:permite passagem de parâmetros

!!!! `@component` tem diferença do include

`contato.balde.php`

```php+HTML
@extends('site.layouts.basico')

@section('titulo', $titulo)

@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina">
            <h1>Entre em contato conosco</h1>
        </div>

        <div class="informacao-pagina">
            <div class="contato-principal">
                @component('site.layouts._components.form_contato')
                @endcomponent
            </div>
        </div>
    </div>
```

`layouts/_partial/form.balde.php`

```php+HTML
<form action={{ route('site.contato') }} method="post">
    @csrf
    <input name="nome" type="text" placeholder="Nome" class="borda-preta">
    <br>
    <input name="telefone" type="text" placeholder="Telefone" class="borda-preta">
    <br>
    <input name="email" type="text" placeholder="E-mail" class="borda-preta">
    <br>
    <select name="motivo_contato" class="borda-preta">
        <option value="">Qual o motivo do contato?</option>
        <option value="1">Dúvida</option>
        <option value="2">Elogio</option>
        <option value="3">Reclamação</option>
    </select>
    <br>
    <textarea name="mensagem" class="borda-preta">Preencha aqui a sua mensagem</textarea>
    <br>
    <button type="submit" class="borda-preta">ENVIAR</button>
</form>

```

**ENVIANDO PARAMETROS PARAUM `@component`**

É feita entre o bloco `@component`e `@endcomponent`

E seu conteúdo é feito partir da variável `$slot`



`form.blade` (é um componente)

Observe que `{{ $classe }}` é enviado por parâmetro no `@component`. Há duas formas de passar parâmetros

+ Por array associativo na chamada. Isso gera variáreis internas do `@component`
+ dentro do bloco, adicionando assim mais html se precisar.

```php+HTML
{{ $slot }}
<form action={{ route('site.contato') }} method="post">
    @csrf
    <input name="nome" type="text" placeholder="Nome" class="{{ $classe }}">
    <br>
    <input name="telefone" type="text" placeholder="Telefone" class="{{ $classe }}">
    <br>
    <input name="email" type="text" placeholder="E-mail" class="{{ $classe }}">
    <br>
    <select name="motivo_contato" class="{{ $classe }}">
        <option value="">Qual o motivo do contato?</option>
        <option value="1">Dúvida</option>
        <option value="2">Elogio</option>
        <option value="3">Reclamação</option>
    </select>
    <br>
    <textarea name="mensagem" class="{{ $classe }}">Preencha aqui a sua mensagem</textarea>
    <br>
    <button type="submit" class="{{ $classe }}">ENVIAR</button>
</form>

```

chamando em contato

```php+HTML
@extends('site.layouts.basico')

@section('titulo', $titulo)

@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina">
            <h1>Entre em contato conosco</h1>
        </div>

        <div class="informacao-pagina">
            <div class="contato-principal">
                @component('site.layouts._components.form_contato', ['classe' => 'borda-preta'])
                <p>A nossa equipe analisará a sua mensagem e retornaremos o mais brevemente possível</p>
                <p>Nosso tempo médio de resposta é de 48 horas</p>
                @endcomponent
            </div>
```

oura forma, passando por array associativo

em `principal.blade.php`

```php+HTML
@component('site.layouts._components.form_contato', ['classe' => 'borda-branca'])                @endcomponent
```

## Trabalhando com envio de formulário

### Enviando via get

em `contato.blade`

```php+HTML
<form action={{ route('site.contato') }} method="get">
```

e que pode ser acessado por

```
// no controller
```

### Enviando via post

**token `@csrf`**

Cross-site request forgery ou falsificação de solicitação entre sites

É um token de segurança, que é adicionado a uma página legítima. serve para segurança e é obrigatório para métodos post

Assim o formulário deve ter

```php+HTML
<form action={{ route('site.contato') }} method="post">
    @csrf
    <input name="nome" type="text" placeholder="Nome" class="borda-preta">
    <br>
    ..........
```

e é acessado no controller por `$_POST`

```php
class ContatoController extends Controller
{
    public function contato() {

        var_dump($_POST);
        return view('site.contato', ['titulo' => 'Contato (teste)']);
    }
}
```
