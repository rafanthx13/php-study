# Blade

Motor de renderizaçâo do laravel

Através do blade, podemos ter um super-set do PHP a ser interpretado pelo balde.

O blade não exclui a sintaxe default do php, ele a entende

por isso os arquivos são extensão `name_file.blade.php`

## CheatSheet

from: https://gist.github.com/CiprianSpiridon/f4d7fe0d8a51f0714b62

```
 {{ $var }} - Echo content
    {{ $var or 'default' }} - Echo content with a default value
    {{{ $var }}} - Echo escaped content
    {{-- Comment --}} - A Blade comment
    @extends('layout') - Extends a template with a layout
    @if(condition) - Starts an if block
    @else - Starts an else block
    @elseif(condition) - Start a elseif block (dentro do bloco @if/@endif)
    @endif - Ends a if block
    @unless(condition) - Starts an unless block
    @endunless - Ends an unless block
    @foreach($list as $key => $val) - Starts a foreach block
    @endforeach - Ends a foreach block
    @for($i = 0; $i < 10; $i++) - Starts a for block
    @endfor - Ends a for block
    @while(condition) - Starts a while block
    @endwhile - Ends a while block
    
    @include(file) - Includes another template
    @include(file, ['var' => $val,...]) - Includes a template, passing new variables.
    @each('file',$list,'item') - Renders a template on a collection
    @each('file',$list,'item','empty') - Renders a template on a collection or a different template if collection is empty.
    @yield('section') - Yields content of a section.
    @show - Ends section and yields its content
    @lang('message') - Outputs message from translation table
    @choice('message', $count) - Outputs message with language pluralization
    @section('name') - Starts a section
    @stop - Ends section
    @endsection - Ends section
    @append - Ends section and appends it to existing of section of same name
    @overwrite - Ends section, overwriting previous section of same name
```



## gERAL

Na sintaxe do blade nâo usamos `;` para terminanr os comandos do blade

Os blcoso começan com @if e termianm com @endif

## Extensâo VSCode



A sinteaxe blade não é detectado por defulat pelo VSCode, usaremos 'laravel-blade'

## Comentários

feito com `{{-- xxx --}}`

com `@php ... @endphp` eu fornceço trechos de php puro

Usar duas chaves do balde `{{}}` é a mesma coisa que usar  `<?=  ?>` do php

```php+HTML
<h3>Imprime na tela 3 'Teste'</h3>

{{-- Comentário que será descartado até mesmo no html --}}

{{-- Bloco de PHP--}}


@php
    // Comentário nativo do PHP
    /*
    Comentário nativo do PHP multi-lin
    */
    echo 'Teste'
@endphp

{{ 'Teste '}}
<?= 'Teste' ?>
```

### imprimier {{}} no blade

usamos `@{{}}`

assim, o que estiver dentro do bloco `@{{}}` é imprimido como stirng. Isso permite usamos chaves dentor do lade como string

```
@isset($fornecedores)

    @forelse($fornecedores as $indice => $fornecedor)
        Fornecedor: @{{ $fornecedor['nome'] }}
        <br>
        Status: @{{ $fornecedor['status'] }}
        <br>
        CNPJ: @{{ $fornecedor['cnpj'] ?? '' }}
        <br>
        Telefone: (@{{ $fornecedor['ddd'] ?? '' }}) @{{ $fornecedor['telefone'] ?? '' }}
        <hr>
    @empty
        Não existem fornecedores cadastrados!!!
    @endforelse
@endisset
```



## If e Else no Blade

```
<h3>Fornecedor</h3>

@php
	// IF e ELSE no PHP PUro
    /* 
    if() {

    } elseif() {

    } else {

    }
    */
@endphp

{{-- IF e ELSE no Blade --}}
@if(count($fornecedores) > 0 && count($fornecedores) < 10)
    <h3>Existem alguns fornecedores cadastrados</h3>
@elseif(count($fornecedores) > 10)
    <h3>Existem vários fornecedores cadastrados</h3>
@else
    <h3>Ainda não existem fornecedores cadastrados</h3>
@endif

```

### `@unless`

É um IF que entra se a condição for negativa. Serve apenas como um atalho para uma condição

```php
<h3>Fornecedor</h3>

@php
    /*
    if(!condicao) {} //enquanto executa se o retorno for true
    */
@endphp

{{-- @unless executa se o retorno for false --}}

Fornecedor: {{ $fornecedores[0]['nome'] }}
<br>
Status: {{ $fornecedores[0]['status'] }}
<br>
@if( !($fornecedores[0]['status'] == 'S') )
    Fornecedor inativo
@endif
<br>
@unless($fornecedores[0]['status'] == 'S') <!-- se o retorno da condição for false -->
    Fornecedor inativo
@endunless
<br>

```

### @isset : verificar se uma variavel existe ou não

Testa a existencia de uma variavel. Reocmendável faze isso antes de usá-la

```php
<h3>Fornecedor</h3>

@php
    /*
    if(isset($variavel)) {} //retornar true se a variável estiver definida
    */
@endphp

@isset($fornecedores)
    Fornecedor: {{ $fornecedores[0]['nome'] }}
    <br>
    Status: {{ $fornecedores[0]['status'] }}
    <br>
    @isset($fornecedores[0]['cnpj'])
        CNPJ: {{ $fornecedores[0]['cnpj'] }}
    @endisset
@endisset

```

## empty

Retorna um valor boleando. Usadno para testar se a variavel possui algum valor

```
<h3>Fornecedor</h3>

@php
    /*
    if(empty($variavel)) {} //retornar true se a variável estiver vazia
    o empty retorna 'true' indicando que é vazio se tiver os seguintes valores
    	OS SEGUINTES VALORES sâo considerados vazios, mesmo que sejam algum valor
    - ''
    - 0
    - 0.0
    - '0'
    - null
    - false
    - array()
    - $var // declaramos mas nao tem valor
    */
@endphp

@isset($fornecedores)
    Fornecedor: {{ $fornecedores[0]['nome'] }}
    <br>
    Status: {{ $fornecedores[0]['status'] }}
    <br>
    @isset($fornecedores[0]['cnpj'])
        CNPJ: {{ $fornecedores[0]['cnpj'] }}
        @empty($fornecedores[0]['cnpj'])
            - Vazio
        @endempty
    @endisset
@endisset

```

onde fornecedor é

```
class FornecedorController extends Controller
{
    public function index() {
        $fornecedores = [
            0 => [
                'nome' => 'Fornecedor 1',
                'status' => 'N',
                'cnpj' => '00'
            ],
            1 => [
                'nome' => 'Fornecedor 2',
                'status' => 'S'
            ]
        ];

        return view('app.fornecedor.index', compact('fornecedores'));
    }
}
```

## IF ternário no PHP puro

Exmeplo no PHP Controller

Muito usado para atribuiçÂo de uma variavel (ela é condicional) como no caso amaixo

```php
public function index() {
        $fornecedores = [
            0 => [
                'nome' => 'Fornecedor 1',
                'status' => 'N',
                'cnpj' => '00'
            ],
            1 => [
                'nome' => 'Fornecedor 2',
                'status' => 'S'
            ]
        ];

        /*
        condicao ? se verdade : se falso;
        condicao ? se verdade : (condicao ? se verdade : se falso);
        */
        $msg = isset($fornecedores[0]['cnpj']) ? 'CNPJ informado' : 'CNPJ não informado';
        echo $msg;

        return view('app.fornecedor.index', compact('fornecedores'));
    }
```

### IF ternário o blade com o valor default



Pondo um valor default, vamos evitar que nosso código quebre, assim, se a variavle nâo estiver, vamos por um valor default e evitar da um erro no laravel

```php
<h3>Fornecedor</h3>


@isset($fornecedores)
    Fornecedor: {{ $fornecedores[1]['nome'] }}
    <br>
    Status: {{ $fornecedores[1]['status'] }}
    <br>
    CNPJ: {{ $fornecedores[1]['cnpj'] ?? '' }}
    <!--
        $variável testada não estiver definida (isset)
        ou
        $variável testada possui o valor null
    -->
@endisset

```

Comparandocom o que era antes , usando IF do balde

```
 @isset($fornecedores[0]['cnpj'])
        CNPJ: {{ $fornecedores[0]['cnpj'] }}
        @empty($fornecedores[0]['cnpj'])
            - Vazio
        @endempty
    @endisset
    
por .......

 CNPJ: {{ $fornecedores[1]['cnpj'] ?? '' }}

```

### @switch/case

```php
@isset($fornecedores)
    Fornecedor: {{ $fornecedores[0]['nome'] }}
    <br>
    Status: {{ $fornecedores[0]['status'] }}
    <br>
    CNPJ: {{ $fornecedores[0]['cnpj'] ?? '' }}
    <br>
    Telefone: ({{ $fornecedores[0]['ddd'] ?? '' }}) {{ $fornecedores[0]['telefone'] ?? '' }}
    @switch($fornecedores[0]['ddd'])
        @case ('11')
            São Paulo - SP
            @break
        @case ('32')
            Juiz de Fora - MG
            @break
        @case ('85')
            Fortaleza - CE
            @break
        @default
            Estado não indentificado
    @endswitch
@endisset

```

### @for

No exemplo abaixo, estamos usando o valor default do ternário para nâo quebar nada

```php
@isset($fornecedores)
    @for($i = 0; isset($fornecedores[$i]); $i++)
        Fornecedor: {{ $fornecedores[$i]['nome'] }}
        <br>
        Status: {{ $fornecedores[$i]['status'] }}
        <br>
        CNPJ: {{ $fornecedores[$i]['cnpj'] ?? '' }}
        <br>
        Telefone: ({{ $fornecedores[$i]['ddd'] ?? '' }}) {{ $fornecedores[$i]['telefone'] ?? '' }}
        <hr>
    @endfor
@endisset
```

### @while

OBS: Nâo existe um comando incremental no balde, entâo usamos o do php com  `    @php $i = 0 @endphp` e `@php $i++ @endphp`

```
@isset($fornecedores)

    @php $i = 0 @endphp
    @while(isset($fornecedores[$i]))
        Fornecedor: {{ $fornecedores[$i]['nome'] }}
        <br>
        Status: {{ $fornecedores[$i]['status'] }}
        <br>
        CNPJ: {{ $fornecedores[$i]['cnpj'] ?? '' }}
        <br>
        Telefone: ({{ $fornecedores[$i]['ddd'] ?? '' }}) {{ $fornecedores[$i]['telefone'] ?? '' }}
        <hr>
        @php $i++ @endphp
    @endwhile
@endisset


```

### @foreach

```
@isset($fornecedores)

    @foreach($fornecedores as $indice => $fornecedor)
	    {{-- $indice será o valor numérico do index--}}
        Fornecedor: {{ $fornecedor['nome'] }}
        <br>
        Status: {{ $fornecedor['status'] }}
        <br>
        CNPJ: {{ $fornecedor['cnpj'] ?? '' }}
        <br>
        Telefone: ({{ $fornecedor['ddd'] ?? '' }}) {{ $fornecedor['telefone'] ?? '' }}
        <hr>
    @endforeach
    
@endisset
```

### @forelse

OBS: Ele nâo tem no php normal

o forelse é como o foreach, mas, se o array vinher vazio ele executa um outro comando.

Nos desviamos o fluxo apartir de `@empty` no meio do bloco `@forelse`

```
@isset($fornecedores)

    @forelse($fornecedores as $indice => $fornecedor)
        Fornecedor: {{ $fornecedor['nome'] }}
        <br>
        Status: {{ $fornecedor['status'] }}
        <br>
        CNPJ: {{ $fornecedor['cnpj'] ?? '' }}
        <br>
        Telefone: ({{ $fornecedor['ddd'] ?? '' }}) {{ $fornecedor['telefone'] ?? '' }}
        <hr>
    @empty
        Não existem fornecedores cadastrados!!!
    @endforelse
@endisset

```

### $loop no foreach e forelse

quando usamos foreach ou foerlse, é gerado uma variavel chamada `$loop` que contem dados da nossa iteraçao

Dados do `$loop`

+ `$loop->iteration`
+ `$loop->first`: retorna true se for a primeira interaçÂo do array
+ `$loop->last`: se for a ultima

Se quiser acessar todos os dados use `@dd($loop)`

```
@isset($fornecedores)

    @forelse($fornecedores as $indice => $fornecedor)

        <br>
        Fornecedor: {{ $fornecedor['nome'] }}
        <br>
        Status: {{ $fornecedor['status'] }}
        <br>
        CNPJ: {{ $fornecedor['cnpj'] ?? '' }}
        <br>
        Telefone: ({{ $fornecedor['ddd'] ?? '' }}) {{ $fornecedor['telefone'] ?? '' }}
        <br>
        
        Iteração atual: {{ $loop->iteration }} {-- imprime index numerico, começa de 1 ao invez do 0 --}
        
        @if($loop->first)
            Primeira iteração no loop
            <br>
            Total de registros: {{ $loop->count }}
        @endif

        @if($loop->last)
            Última iteração no loop
        @endif
        
        @dd($loop)
        
        <hr>
    @empty
        Não existem fornecedores cadastrados!!!
    @endforelse
@endisset


```

