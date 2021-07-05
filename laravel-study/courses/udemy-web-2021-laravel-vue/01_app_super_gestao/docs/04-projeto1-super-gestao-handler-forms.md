## Projeto 1 - Super gestão - Seção 9: Lidando com Formulário

## Recuperar Request no controller

Usamos `Request $request` na chamada da função do controller para recuperar a `HTTPRequest`

````php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteContato;

class ContatoController extends Controller
{
    public function contato(Request $request) {

        echo '<pre>';
        print_r($request->all()); // Mostara todos os atributos de $request->parameters
        echo '</pre>';
        echo $request->input('nome'); // Retorna o valor de um único parametro de $request->parameters
        echo '<br>';
        echo $request->input('email');
        
        return view('site.contato', ['titulo' => 'Contato (teste)']);
    }

}
```
### Salavar valores que vem do Request

Utilizamos o Model.

Para usarmos o `create()` os atributos devem ser setados como `$fillaable` no model.

Ex: Model de Site Contato

````php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

//Site_Contato
//site_contato
//site_contatos

class SiteContato extends Model
{
    protected $fillable = ['nome', 'telefone', 'email', 'motivo_contato', 'mensagem'];
}
````

Depois, pomdeo usar o `create` do Eloqunet ORM

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SiteContato;

class ContatoController extends Controller
{
    public function contato(Request $request) {


        // Forma 1 de Salvar: Colocando parametro por parametro
        $contato = new SiteContato();
        $contato->nome = $request->input('nome');
        $contato->telefone = $request->input('telefone');
        $contato->email = $request->input('email');
        $contato->motivo_contato = $request->input('motivo_contato');
        $contato->mensagem = $request->input('mensagem');
        $contato->save();
        
		// Forma 2 de Salvar: Usando create com $fillable no model
        $contato = new SiteContato();
        $contato->create($request->all());
     
		// Acessar a view
        return view('site.contato', ['titulo' => 'Contato (teste)']);
    }
}

```

## Validaçâo de Formulário

Em controller, vamos criar a funçâo que via validar os dados

```php
 // Chamado no post do web.php para /contados
    public function salvar(Request $request) {

        //realizar a validação dos dados do formulário recebidos no request
        $request->validate([
            'nome' => 'required',
            'telefone' => 'required',
            'email' => 'required',
            'motivo_contato' => 'required',
            'mensagem' => 'required'
        ]);
        // SiteContato::create($request->all());
    }
```

Validar max e min de caracters e eamil

```
$request->validate([
            'nome' => 'required|min:3|max:40|unique:site_contatos',
            'telefone' => 'required',
            'email' => 'email',
            'motivo_contato' => 'required',
            'mensagem' => 'required|max:2000'
        ]);
```

OBS: `unique|site_contatos` ELE VAI VALIDAR SE ESSE VALOR JÁ ESTÁ PRESENTE NO BANCO. assim somente vai aceitar se esse nome nâo esstiver no banco. O laravel faz a consulta automaticamente

## Repopulando o formulario

Imagein que você envia os dados e dá algum probela. 

Aí os dados digitados vao ser perdidos

**PARA recuperalos usaremsmos o `old()`* no blade no formulário*

em form.blade.php

```
{{ $slot }}
<form action={{ route('site.contato') }} method="post">
    @csrf
    <input name="nome" value="{{ old('nome') }}" type="text" placeholder="Nome" class="{{ $classe }}">
    <br>
    <input name="telefone" value="{{ old('telefone') }}" type="text" placeholder="Telefone" class="{{ $classe }}">
    <br>
    <input name="email" value="{{ old('email') }}" type="text" placeholder="E-mail" class="{{ $classe }}">
    <br>

    <select name="motivo_contato" class="{{ $classe }}">
        <option value="">Qual o motivo do contato?</option>

        @foreach($motivo_contatos as $key => $motivo_contato)
            <option value="{{$key}}" {{ old('motivo_contato') == $key ? 'selected' : '' }}>{{$motivo_contato}}</option>
        @endforeach
    </select>
    <br>
    <textarea name="mensagem" class="{{ $classe }}">{{ (old('mensagem') != '') ? old('mensagem') : 'Preencha aqui a sua mensagem' }}</textarea>
    <br>
    <button type="submit" class="{{ $classe }}">ENVIAR</button>
</form>

<div style="position:absolute; top:0px; width:100%; background:red">
    <pre>
    {{ print_r($errors) }}
    </pre>
</div>

```

e contato.blade.php

```
@extends('site.layouts.basico')

@section('titulo', $titulo)

@section('conteudo')
    <div class="conteudo-pagina">
        <div class="titulo-pagina">
            <h1>Entre em contato conosco</h1>
        </div>

        <div class="informacao-pagina">
            <div class="contato-principal">
                @component('site.layouts._components.form_contato', ['classe' => 'borda-preta', 'motivo_contatos' => $motivo_contatos])
                    <p>A nossa equipe analisará a sua mensagem e retornaremos o mais brevemente possível</p>
                    <p>Nosso tempo médio de resposta é de 48 horas</p>
                @endcomponent
            </div>
        </div>
    </div>

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

e nosso controller fica

```
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SiteContato;

class ContatoController extends Controller
{
    public function contato(Request $request) {

        $motivo_contatos = [
            '1' => 'Dúvida',
            '2' => 'Elogio',
            '3' => 'Reclamação'
        ];

        return view('site.contato', ['titulo' => 'Contato (teste)', 'motivo_contatos' => $motivo_contatos]);
    }

    public function salvar(Request $request) {

        //realizar a validação dos dados do formulário recebidos no request
        $request->validate([
            'nome' => 'required|min:3|max:40',
            'telefone' => 'required',
            'email' => 'email',
            'motivo_contato' => 'required',
            'mensagem' => 'required|max:2000'
        ]);
        // SiteContato::create($request->all());
    }
}

```

## Refatorando tudo

```
php artisan make:model MotivoContato -m
```



```
php artisan make:seeder MotivoContatoSeeder
```

edpois executa seeder

```
php artisan db:seed --class=MotivoContatoSeeder
```

principal controler é alterado

..

## Mensagesns de erro de validaçâo

PULEI ESSA PARTE