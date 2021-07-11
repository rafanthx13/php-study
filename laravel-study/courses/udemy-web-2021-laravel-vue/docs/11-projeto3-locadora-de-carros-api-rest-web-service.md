# Seção 17: App 03 - Locadora de Carros - API REST WebService

## Iniciar Projeto

```sh
composer create-project --prefer-dist laravel/laravel=8.5.9 03_app_locadora_carros
```

## Criando Componentes Laravel pelo artisan

```shell
php artisan make:model --migration --controller --resource Marca
```

 outra podemos simplificar como

```sh
php artisan make:model -mcr Modelo
```

Cria também seeder e factory

```sh
php artisan make:model --all Modelo
```

e simplificando

```sh
php artisan make:model -a Cliente
```

e também locação:

```sh
 php artisan make:model -a Locacao --resource
```

+ `--resource`: cria no controller os métodos default index, show, destroy

## Criar Banco de Dados

```sql
create databse lc;
```

Alterar o `.env` para por nossos dados do nosso banco de dados

**SUBSTITUIA PELAS MIGRATIONS DELE, POIS JÁ VIMOS**

### Alteração do nome plural no Laravel

É padrão do Laravel que o `Model` tenha o nome em singular e o nome da tabela seja o mesmo nome acrescido de `s`.

Acontece que em português `locacao` vira `locacaos`,

**Para alterar isso:**

1. No `Model` ponha

```php
protected $table= "new-name"
```

2. E na `Migration` mude o nome do arquivo antes de executá-la

**Executar a migração**

```sh
php artisan migrate
```

Em seguida, execute o seeder:

Executar: `atabase\Seeders\DatabaseSeeder`

```sh
php artisan db:seed
```

Executar Seeder Específico 

```sh
php artisan db:seed --class=UserSeeder
```



## Content Type : HTML x API

`Content-Type` é um tipo que fica no Header da `Response`  `HYYP`

+ `text/html` : documento da árvore DOM. cai em `web.php`

+ `application/json` : Para acessar arquivo `api.php` usamos na URL 'localhost/api/...'  sempre api. Para ser correto, devemos retornar `application/json`

 O laravel sempre vai voltar JSON desde que mande como ARRYA ASSOCIATIVO

## Route :: `resource` e `apiResource`

Lembre, o `resource` faz:
- index (GET), store (POSTT), create (GET), show (GET:ID), update (PUT/PATH,ID), destroy (DELETE,ID), edit (GET,ID)
- Agora, edit e create não precisam ser implementados ai, pois é uma API e essas paginas são para o `web.php`, seria para retornar a página para fazer a criação e edição

por isso, apara api, usamos `apiResource`
+ index, store, show, update e destroy

**A DIFERNEÇA ENTRE AMBOS É ESSA: que uma não tem `create/edit` e a outra tem.**

Vamos usá-lo e assim vai ficar:

````php
Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
Route::apiResource('carro', 'App\Http\Controllers\CarroController');
Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');
````

**Lembrando: , como evitar o namespace**

Em `app/Providers/RouteSerivceProvider.php` descomente a linha 29 que é

```
protected $namespace = 'App\\Http\\Controlers';
```

Assim de

```php
Route::apiResource('cliente', 'App\Http\Controllers\ClienteController');
Route::apiResource('carro', 'App\Http\Controllers\CarroController');
Route::apiResource('locacao', 'App\Http\Controllers\LocacaoController');
Route::apiResource('marca', 'App\Http\Controllers\MarcaController');
Route::apiResource('modelo', 'App\Http\Controllers\ModeloController');
```

Vai para

```php
Route::apiResource('cliente', 'ClienteController');
Route::apiResource('carro', 'CarroController');
Route::apiResource('locacao', 'LocacaoController');
Route::apiResource('marca', 'MarcaController');
Route::apiResource('modelo', 'ModeloController');
```

## Criando primeiros EndPoints

Em `MarcaController`

+ Observações:
  + **SIM, É TÃO SIMPLES COMO NO DJANGO**
  + Em  `show` o Laravel já faz um Bind pelo ID, então `$marca` já é o dado em si.
    + Se passar algum ID que não tem, vai voltar erro, mas isso vamos modificar depois
  + Em `update`, `Marca $marca` já faz o Bind com o ID, assim, pegamos essa `$marca` e alteramos ela, nem mesmo precisamos mandar buscar antes de atualizar

```php
<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    // LIST ALL : Display a listing of the resource.
    public function index()
    {
        $marcas = Marca::all();
        return $marcas;
    }

    // POST : Store a newly created resource in storage.
    public function store(Request $request)
    {
        $marca = Marca::create($request->all());
        return $marca;
    }

    // GET ONE: Display the specified resource.
    public function show(Marca $marca)
    {
        return $marca;
    }

    // UPDATE BY ID / HTTP UPDATE or PATH : Update the specified resource in storage.
    public function update(Request $request, Marca $marca)
    {
        /*
        print_r($request->all()); //os dados atualizados
        echo '<hr>';
        print_r($marca->getAttributes()); //os dados antigos
        */

        $marca->update($request->all());
        return $marca;
    }

    // REMOVE BY ID : Remove the specified resource from storage.
    public function destroy(Marca $marca)
    {
        $marca->delete();
        return ['msg' => 'A marca foi removida com sucesso!'];
        
    }
}

```

Esse recurso do laravel de `Marca $marca` na chamada da função, que faz o bind, é chamado de **TYPE BINDING**

Há 3 formas de fazermos:

+ Métodos estáticos, como em `Maraca::all()`
+ Type Binding
+ Injeção de Modelo

### Modo de Injeção de Modelo

Iremos usar esse modo (O terceiro, 3), pois fica mais legível para quem está começando no Laravel

`MarcaControlle.php` vai ficar da seguinte forma:

+ OBS: Sem usar Métodos estático nem nenhum TypeBinding

```php
<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    // Injeção do Modelo internamente
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
   
    public function index()
    {
        //$marcas = Marca::all(); // metodo antigo
        $marcas = $this->marca->all();
        return $marcas;
    }
   
    public function store(Request $request)
    {
        //$marca = Marca::create($request->all()); // metodo antigo
        $marca = $this->marca->create($request->all());
        return $marca;
    }

    public function show($id)
    {
        $marca = $this->marca->find($id);
        return $marca;
    }

    public function update(Request $request, $id)
    {
        //$marca->update($request->all());
        $marca = $this->marca->find($id);
        $marca->update($request->all());
        return $marca;
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        $marca->delete();
        return ['msg' => 'A marca foi removida com sucesso!'];
        
    }
}

```

**A FORMA DE INJEÇÂO DE MODELO FICA MELHOR PARA UM DESENVOLVEDOR LER, E VAMOS USAR ELA, ALÉM DISSO, SSÓ ACRESCENTA ALGUMAS LINHAS A MAIS, AINDA CONTINUA BEM SIMPLES**

## Validação de Dados na API

### Caso o Id não exista

Exemplo para `show()`

```php
public function show($id) // getOne
    {
        $marca = $this->marca->find($id);
        if($marca === null) {
            return ['erro' => 'Recurso pesquisado não existe']
   
        } 
        return response()->json($marca, 200);
    }
```

Verificamos se o `find()` deu nulo ou não, se deu, enviamos mensagem de erro

### Retornando com Status Code de error

Vamos utilizar o helper `response->json()` e passar como 2 parâmetro o Status Code

```php
public function show($id)
    {
        $marca = $this->marca->find($id);
        if($marca === null) {
            // return ['erro' => 'Recurso pesquisado não existe']
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($marca, 200);
    }
```

é preferível usar sempre ` response()->json` em todos os retornos, para nos, como programadores entendermos bem que código HTTP está sendo retornado, pois laravel á consegue retornar algumas coisa corretas automaticamente

### Validando os parâmetros no POST

O Header da requisição (ou seja, quem chama) deve ter `Accept: application/json`, para que o `validate` funcione corretamente. Sem isso o laravel manda para a `home` pois vai funcionar como o `validate` de um controller de uma rota de  `web.php`, um comportamento semelhante á: você responde um formulário, enviar, dar um erro e voltar para a própria página

Em suma: **O CLIENTE DEVE IMPLEMENTAR `Accept: application/json`**

A validação é feita com 

```php
public function store(Request $request)
    {
        
        // Definindo regras
        $regras = [
            'nome' => 'required|unique:marcas',
            'imagem' => 'required'
        ];

		// definindo retorno
        $feedback = [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe'
        ];

		// validando
        $request->validate($regras, $feedback);

        $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);
    }
```

### Centralizar as regras de validação

Perceba que essas `$regaras` e `feedback` podem também ser usadas em `update`. Há uma forma melhor de implementá-las. **ENTAO, VAMOS COLOCÁ-LOS NO MODEL, assim, essa validaçôes poderão ser usadas tanto no `store()` quanto em `update()`**

Em `Marca.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules() {
        return [
            'nome' => 'required|unique:marcas'|min:3',
            'imagem' => 'required'
        ];
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'nome.unique' => 'O nome da marca já existe',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres'
        ];
    }
}

```

E o Controller vai ficar da seguinte forma

```php
public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $marca = $this->marca->create($request->all());
        return response()->json($marca, 201);
    }
```

O `validate` altera a `request`, se falha, vai voltar o json com o erro, se não, volta o objeto marca criado.

### Fazer o Update

Acontece que as regras de `create` e `update` são as mesmas, mas **AS MENSAGENS DE FEED BACK DEVEM SER DIFERENTES**

Vamos voltar ao `rule` de models de `Marca`

```php
public function rules() {
        return [
            'nome' => 'required|unique:marcas|min:3',
            'imagem' => 'required'
        ];
    }
```

**Validação `unique`**

A validação `unique` tem 3 parâmetros:

```
1) tabela
2) nome da coluna que será pesquisada na tabela
3) id do registro que será desconsiderado na pesquisa
```

Vamos por por para desconsiderar o próprio ID. Isso porque. Como se está fazendo um UPDATE é claro que já existe aquele nome. O que queremos é que não repita um novo ID para o registro de um novo nome. 

Para isso vamos alterar esse trecho pondo todos os parâmetros

```php
public function rules() {
    return [
        'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
        'imagem' => 'required'
    ];
}
```

assim vai desconsiderar o próprio ID quando fizer o UPDATE e não vai dar mais erro

Assim `unique` vai pesquisar por `unique` exceto próprio dado se tiver

### Validação parcial para HTTP PATCH

Como está agora, o update só aceita se enviar todos os parâmetros.

Seria interessante se habilitarmos para usar Path, ou seja,a mandar JSIn de so os parâmetros que queremos mudar.

**Como fazer**

Vamos usar `$request->method()` para sabermos se o método utilizado for UPDATE OU PATH, e vamos percorrer o que chegou e somente aplicar a validação nos campos que chegaram. O código vai ficar assim:

```PHP
public function update(Request $request, $id)    {
        $marca = $this->marca->find($id);

        if($marca === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

    	// validaçâo para patch
        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($marca->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $marca->feedback());

        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }
        
        
        $marca->update($request->all());
        return response()->json($marca, 200);
    }
```

dessa forma: `$regrasDinamicas` terá as regras de somente os campos que tiver em `$request->all()`

## Upload de Arquivo (Imagem)

## Receber Dados

**A Requisição DO CLIENT DEVE SER `form-data`, assim fica melhor de lidar no backend com arquivos**

Vamos mandar nome e imagem. Nessa forma os parâmetros tem tipagem

Agente pega esse dados direto no request . Há 3 formas.

```php
$request->nome
$request->get('nome')
$request->input('nome')
```

Se você fizer o mesmo com imagem e 

```php
dd($request->imagem)
```

irá receber um objeto do tip `UploadFile`

## Onde Persistir Arquivos no Laravel

Para salvar, basta executar o método `store` que recebe 2 parâmetros: path (path de pasta) e disco (há 3 setores possiveis)

**Disco**

O DISCO É DEFINIDO EM `config/filesystem.php`

Por padrão temos 3 discos definidos:

+ local : `/storage/app/`
+ public: `storage/app/public`
+ aws S3: `cloud`

Por default é definido para salvar em `local`, então, por isso vamos omitir o 2 parâmetro.

OBS: Esses 2 diretórios `storage` ficam protegidos, eles não ficam expostos na web

**VAMOS SALVAR EM**: `publio` em `storage/app/public`

Assim vai ficar

```php
public function store(Request $request)    {
        $request->validate($this->marca->rules(), $this->marca->feedback());
        $imagem = $request->file('imagem'); // recupera imagem fo form-data
        $imagem_urn = $imagem->store('imagens', 'public'); 
    	// salva imagem na pasta imagens dentro do setor public que é storage/app/public
		return response()->json($marca, 201);
    }
```

O nome da imagem é gerado de forma aleatória para evitar imagens duplicadas

## Salvar dados de imagem em banco

Depois que salvamos o arquivo fisicamente, temos que recuperar seus dados. Isso é fetio no retorno do método `store()` na variável `$img_urn`

```php
public function store(Request $request)
    {
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca = $this->marca->create([
            'nome' => $request->nome,
            'imagem' => $imagem_urn //nome randomico da imagem
        ]);

        return response()->json($marca, 201);
    }
```

**Outro problema: restringir o tipo de dado**

Da forma que está, pode-se passar qualquer dado que vai ser interpretado da mesma forma

Podemos restringir a tipagem passando ais coisa na parte de `rules`

```php
public function rules() {
    return [
        'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
        'imagem' => 'required|file|mimes:png'
    ];
}
    
public function feedback() {
    return [
        'required' => 'O campo :attribute é obrigatório',
        'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG',
        'nome.unique' => 'O nome da marca já existe',
        'nome.min' => 'O nome deve ter no mínimo 3 caracteres'
    ];
}
```

Assim, só irá aceitar `.png`

## Criando símbolo/caminho simbólico para o `storage`

quando agente faz

```sh
php artisan serve
```

agente fornece somente a pasta  `public`.

Pra fornece as imagens em `storage/app/public` basta fazermos

```sh
php artisan storage:link
```

**VAI CRIAR UM LINK LIGANDO UM STORAGE SINCRONIZADO ENTRE **: `public/storage ` e `storage/`

## Atualizar e Remover Imagens imagens

**Detalhe importante**

Quando se envia imagem com `form-data` o Laravel não vai reconhecer direito para PUT e PATCH.

`form-data` é ideal para POST, mas não queremos mudar, pois não estamos colocando um recurso novo

**COMO CORRIGIR**

passamos `no form-data`:  `_method` e passamos o verbo. Assim, basta adicionar um parâmetro da requisição.

`_method: put`

**UPDATE**

o trecho que faz o update da imagem

```php
//remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
if($request->file('imagem')) {
    Storage::disk('public')->delete($marca->imagem);
}

$imagem = $request->file('imagem');
$imagem_urn = $imagem->store('imagens', 'public');

$marca->update([
    'nome' => $request->nome,
    'imagem' => $imagem_urn
]);
```

toda a função para atualizar uma imagem

```php
public function update(Request $request, $id)
    {
        $marca = $this->marca->find($id);

        if($marca === null) {
            return response()->json(['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($marca->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas, $marca->feedback());

        } else {
            $request->validate($marca->rules(), $marca->feedback());
        }
        
        //remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
        if($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens', 'public');

        $marca->update([
            'nome' => $request->nome,
            'imagem' => $imagem_urn
        ]);

        return response()->json($marca, 200);
    }
```

## Deletar Imagem

```php
use Illuminate\Support\Facades\Storage;
```

Trecho que deleta uma imagem física

```php
Storage::disk('public')->delete($marca->imagem);        

$marca->delete();
return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
```

Trecho do código completo

```php
public function destroy($id){
    $marca = $this->marca->find($id);

    if($marca === null) {
        return response()->json(
            ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
    }

    //remove o arquivo antigo
    Storage::disk('public')->delete($marca->imagem);        

    $marca->delete();
    return response()->json(['msg' => 'A marca foi removida com sucesso!'], 200);
}
```

## ModeloController

Antes estávamos em `MarcaController` agora vamos para `ModeloController`

### Fazer Resource do ModeloController

`Models/Modelo.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules() {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5', //(1,2,3,4,5)
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean' //true, false, 1, 0, "1", "0"
        ];
    }
}
```

`ModeloController.php`: Vai ser muito parecido com o de `MarcaController`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }

    public function index(){
        return response()->json($this->modelo->all(), 200);
    }

    public function store(Request $request){
        $request->validate($this->modelo->rules());

        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

        $modelo = $this->modelo->create([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($modelo, 201);
    }
 
    public function show($id){
        $modelo = $this->modelo->find($id);
        if($modelo === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($modelo, 200);
    }

    public function update(Request $request, $id){
        $modelo = $this->modelo->find($id);

        if($modelo === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($modelo->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

        } else {
            $request->validate($modelo->rules());
        }
        
        //remove o arquivo antigo caso um novo arquivo tenha sido enviado no request
        if($request->file('imagem')) {
            Storage::disk('public')->delete($modelo->imagem);
        }
        
        $imagem = $request->file('imagem');
        $imagem_urn = $imagem->store('imagens/modelos', 'public');

        $modelo->update([
            'marca_id' => $request->marca_id,
            'nome' => $request->nome,
            'imagem' => $imagem_urn,
            'numero_portas' => $request->numero_portas,
            'lugares' => $request->lugares,
            'air_bag' => $request->air_bag,
            'abs' => $request->abs
        ]);

        return response()->json($modelo, 200);
    }

    public function destroy($id){
        $modelo = $this->modelo->find($id);

        if($modelo === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        //remove o arquivo antigo
        Storage::disk('public')->delete($modelo->imagem);        

        $modelo->delete();
        return response()->json(['msg' => 'O modelo foi removida com sucesso!'], 200);
        
    }
}

```

lembrando:

```
content-type: application/json
forma do body: form-data
```

### Relação 1:N (1 marca tem N Modelos)

Um modelo pertence a uma marca, e uma marca possui vários modelos

`Modelos.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modelo extends Model
{
    use HasFactory;

    protected $fillable = ['marca_id', 'nome', 'imagem', 'numero_portas', 'lugares', 'air_bag', 'abs'];

    public function rules() {
        return [
            'marca_id' => 'exists:marcas,id',
            'nome' => 'required|unique:modelos,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png,jpeg,jpg',
            'numero_portas' => 'required|integer|digits_between:1,5', //(1,2,3,4,5)
            'lugares' => 'required|integer|digits_between:1,20',
            'air_bag' => 'required|boolean',
            'abs' => 'required|boolean' //true, false, 1, 0, "1", "0"
        ];
    }

    public function marca() {
        //UM modelo PERTENCE a UMA marca
        return $this->belongsTo('App\Models\Marca');
    }
}
```

`Marca.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    use HasFactory;
    protected $fillable = ['nome', 'imagem'];

    public function rules() {
        return [
            'nome' => 'required|unique:marcas,nome,'.$this->id.'|min:3',
            'imagem' => 'required|file|mimes:png'
        ];

        /*
            1) tabela
            2) nome da coluna que será pesquisada na tabela3
            3) id do registro que será desconsiderado na pesquisa
        */
    }

    public function feedback() {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'imagem.mimes' => 'O arquivo deve ser uma imagem do tipo PNG',
            'nome.unique' => 'O nome da marca já existe',
            'nome.min' => 'O nome deve ter no mínimo 3 caracteres'
        ];
    }

    public function modelos() {
        //UMA marca POSSUI MUITOS modelos
        return $this->hasMany('App\Models\Modelo');
    }
}

```

Usando eles em index

`MarcaController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$marcas = Marca::all();
        $marcas = $this->marca->with('modelos')->get();
        return response()->json($marcas, 200);
    }
```

`ModeloController.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json($this->modelo->with('marca')->get(), 200);
        //all() -> criando um obj de consulta + get() = collection
        //get() -> modificar a consulta -> collection
    }
```

Observações

+ O método `with` está em Eager Loading e Lazy Loading
+ não podemos usar o método estático `all()` pois ele é uma consulta seguida de `get()` sendo que precisamos fazer mais coisa nele. Uma questão de tipagem e possibilidade da tipagem
  + //all() -> criando um objetode consulta + get() = collection
  + //get() -> modificar a consulta -> collection

### Mudando Update para Path

Nosso código suporta PATH, mas se não passarmos todos os parâmetros, dá erro. 

Ainda não é possível deixar de passar todos os parâmetro. Exemplo: Marc tem dois parâmetros (nome,imagem). Se enviar imagem e não enviar nome, vai fazer um `$model->nome = $nome` onde  `$nome == null`

```php
// com é: DA ERRO
$marca->update([
    'nome' => $request->nome,
    'imagem' => $imagem_urn
]);
```

**COMO SOLUCIONAR**

Vamos combinar `fill()` e `all()`, assim, vamos pegar o objeto e mudar nele só aquilo que enviou.

Assim, se por exemplo vinher sem nome, vai pegar o nome que está no banco e mondar o objeto no controller. 

```php
// DEPOIS
// Preencher o objeto $marca com os dados do request
$marca->fill($request->all());
$marca->imagem = $imagem_urn;
$marca->save();
```

O método `save()` sabe que, se foi inserir algo com id, não vai criar um novo registro e siem atualizar

### Filtros: retornar certos atributos específicos

O que queremos: que a api seja capaz de retornar certo atributos de acordo com o que agente passar para ele retornar.

Exemplo para `ModeloController` no método `index()`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Modelo;
use Illuminate\Http\Request;

class ModeloController extends Controller
{
    public function __construct(Modelo $modelo) {
        $this->modelo = $modelo;
    }
   
    public function index(Request $request)
    {
        $modelos = array();

		// checar os atributos
        if($request->has('atributos')) {
            $atributos = $request->atributos;
            $modelos = $this->modelo->selectRaw($atributos)->with('marca')->get();
        } else {
            $modelos = $this->modelo->with('marca')->get();
        }
        return response()->json($modelos, 200);

    }
```

Observações:

+ Esse atributos vão vir do `form-data`, por isso podemos fazer `$request->atributos`
+ usamos `selectRaw` pois sem ele a string tem que se `'nome', 'nomw2'`,  e com ele pode ser `nome,nomw2` pois vai aceitar essa string único ao invés de passar n parâmetros
+ a url de chamada `loachost:8080/api/modelo?atributos=id,nome,modelo_id`

### Filtros: obtendo colunas especificas dentro do with

**with**

+ o with permite recupera certas colunas com `:`
+ Exemplo: `->with('marca:id,nome')`

Poderíamos fazer então para que a linha do `selectRaw` seja

```php
$atributos_marca
$modelos = $this->modelos->selectRaw($atributos)->with('marca:',$atributos_marca)->get()
```

Mas assim: SO VAI FUNCIONAR SE HOUVER `$atributo`

**O resolver isso**

+ Vamos separar cada parte: se houver atributos_marca, pega algumas colunas, se nao, pega tudo
+ E tudo isso feito antes do `selectRaw()`. Nesse caso, a ordem importa

```php
public function index(Request $request)
    {
        $modelos = array();

        if($request->has('atributos_marca')) { // se tiver
            $atributos_marca = $request->atributos_marca;
            $modelos = $this->modelo->with('marca:id,'.$atributos_marca); // vai recuperar certas colunas 
        } else { // se nao tiver 'atributos_marca'
            $modelos = $this->modelo->with('marca'); // vai recuperar todos os campos de marca
        }

        if($request->has('atributos')) {
            $atributos = $request->atributos;
            $modelos = $modelos->selectRaw($atributos)->get();
        } else {
            $modelos = $modelos->get();
        }

      
        return response()->json($modelos, 200);
        
    }
```

### Aplicando Pesquisa `where`

como é a url

`loachost:8080/api/modelo?atributos=id,nome,modelo_id?atributo_marca=nome?filtro=nome:=:ford Ka 20`

Vamos separar: nome de seu valor com `:=:` só por isso usamos dois pontos

```
if($request->has('filtro')) {
            $condicoes = explode(':', $request->filtro); // separadmos
            $modelos = $modelos->where($condicoes[0], $condicoes[1], $condicoes[2]);
            
        }
```

+ $condicoes[0] => nome do atributo
+ $condicoes[1] => é o char  `=` ou `like`
+ $condicoes[2] => a pesquisa em si

Exemplo do que aceita

```
numero_porta:>:4
nome:like:%Ford%
```

como vai ficar o index

```php
public function index(Request $request)
    {
        $modelos = array();

        if($request->has('atributos_marca')) {
            $atributos_marca = $request->atributos_marca;
            $modelos = $this->modelo->with('marca:id,'.$atributos_marca);
        } else {
            $modelos = $this->modelo->with('marca');
        }

        if($request->has('filtro')) {
            $condicoes = explode(':', $request->filtro);
            $modelos = $modelos->where($condicoes[0], $condicoes[1], $condicoes[2]);
        }

        if($request->has('atributos')) {
            $atributos = $request->atributos;
            $modelos = $modelos->selectRaw($atributos)->get();
        } else {
            $modelos = $modelos->get();
        }

        //$this->modelo->with('marca')->get()
        return response()->json($modelos, 200);
        //all() -> criando um obj de consulta + get() = collection
        //get() -> modificar a consulta -> collection
    }
```

### Passando múltiplos filtros de pesquisa

Usaremos um char especifico para separa cada parte `;`

**A IDEIA GERAL É CRIAR UMA SINTAXE PROPRIA A SER PROCESSADA PELO CONTROLLER. DEVERÁ SER BEM DOCUMENTADA PARA QUE OUTRA PESOAA ENTENDA**

COMO VAI FICAR

```php
if($request->has('filtro')) {
    $filtros = explode(';', $request->filtro); // explodo e para cada um, aplico um filtro
    foreach($filtros as $key => $condicao) {

        $c = explode(':', $condicao);
        $modelos = $modelos->where($c[0], $c[1], $c[2]);

    }
}
```

Isso só é possível porque é um `QueryBuilder`, só será executado mesmo quando fizermos o `get()` final

Assim eu posso passar como url

```
/api/modelo?atributos=nome,numero,numero_portas,marca_id?atributos_marca=nome?filtro=abs:=:1;numero_portas:=:4
```

**ADD**

Vamos aplicar os mesmos filtros no outro lado, em `MarcaController`

```php
public function index(Request $request)
    {
        $marcas = array();

        if($request->has('atributos_modelos')) {
            $atributos_modelos = $request->atributos_modelos;
            $marcas = $this->marca->with('modelos:id,'.$atributos_modelos);
        } else {
            $marcas = $this->marca->with('modelos');
        }

        if($request->has('filtro')) {
            $filtros = explode(';', $request->filtro);
            foreach($filtros as $key => $condicao) {

                $c = explode(':', $condicao);
                $marcas = $marcas->where($c[0], $c[1], $c[2]);

            }
        }

        if($request->has('atributos')) {
            $atributos = $request->atributos;
            $marcas = $marcas->selectRaw($atributos)->get();
        } else {
            $marcas = $marcas->get();
        }

        //$marcas = Marca::all();
        //$marcas = $this->marca->with('modelos')->get();
        return response()->json($marcas, 200);
    }
```

## Design Pattern - Repository

Na seção anterior, copiamos toda a lógica de filtragem de `ModeloController` para `MarcaController` .

Estamos agredindo um bom principio da programação que é a reutilização de código.

Vamos utilizar o design Pattern Repository para ser uma interface entre Controller e Model, para  que essas regras e outras fiquem centralizadas e o código melhorado.

Assim, não vamos precisa mais fazer Ctrl+c + crl+v da parte de `filtro`

### Criando Repository

Repository não é algo default do laravel, vamos cria-lo do zero

`app/Repositorie/MarcaRepository.php`

```php
<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

class MarcaRepository {

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos) {
        $this->model = $this->model->with($atributos);
        //a query está sendo montada
    }

    public function filtro($filtros) {
        $filtros = explode(';', $filtros);
        
        foreach($filtros as $key => $condicao) {

            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
            //a query está sendo montada
        }
    }

    public function selectAtributos($atributos) {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado() {
        return $this->model->get();
    }

}

?>
```

Esse repository será consumido pelo seu respectivo controller

`MarcaControler.php`

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Marca;
use Illuminate\Http\Request;
use App\Repositories\MarcaRepository;

class MarcaController extends Controller
{
    public function __construct(Marca $marca) {
        $this->marca = $marca;
    }
    
    public function index(Request $request)
    {

        $marcaRepository = new MarcaRepository($this->marca);

        if($request->has('atributos_modelos')) {
            $atributos_modelos = 'modelos:id,'.$request->atributos_modelos;
            $marcaRepository->selectAtributosRegistrosRelacionados($atributos_modelos);
        } else {
            $marcaRepository->selectAtributosRegistrosRelacionados('modelos');
        }

        if($request->has('filtro')) {
            $marcaRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $marcaRepository->selectAtributos($request->atributos);
        } 

        return response()->json($marcaRepository->getResultado(), 200);
    }
```

Estamos apenas separando e reorganizando as coisa.

Perceba que o código ficou mais enxuto e o controller ficou mais legível

### Abstract Repository

Podemos copiar esse ultimo repository e fazer para os outros, mas ai estaríamos denovo duplicando código.

Então vamos criar um repository abstrato e ele para que outros repository o implementem

```php
<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository {

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function selectAtributosRegistrosRelacionados($atributos) {
        $this->model = $this->model->with($atributos);
        //a query está sendo montada
    }

    public function filtro($filtros) {
        $filtros = explode(';', $filtros);
        
        foreach($filtros as $key => $condicao) {

            $c = explode(':', $condicao);
            $this->model = $this->model->where($c[0], $c[1], $c[2]);
            //a query está sendo montada
        }
    }

    public function selectAtributos($atributos) {
        $this->model = $this->model->selectRaw($atributos);
    }

    public function getResultado() {
        return $this->model->get();
    }
}

?>
```

Assim os outros repositores ficam

`ModeloRepository.php`

```php
<?php

namespace App\Repositories;

class ModeloRepository extends AbstractRepository {
}

?>
```

`MarcaRepository.php`

```php
<?php

namespace App\Repositories;

class MarcaRepository extends AbstractRepository {

}

?>
```

Agora se quisermos fazer essa mesma lógica, basta criar um repository que implemente como os exemplo acima

## Outros Controllers e Models

todos tem um repository igual como vimos antes

### Models

`Models\Cliente.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $fillable = ['nome'];

    public function rules() {
        return [
            'nome' => 'required'
        ];
    }
}

```

`Models\Locacao.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Locacao extends Model
{
    use HasFactory;
    protected $table = 'locacoes';
    protected $fillable = [
        'cliente_id', 
        'carro_id', 
        'data_inicio_periodo', 
        'data_final_previsto_periodo',
        'data_final_realizado_periodo',
        'valor_diaria',
        'km_inicial',
        'km_final'
    ];

    public function rules() {
        return [];
    }
}

```

`Models\Carro.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carro extends Model
{
    use HasFactory;
    protected $fillable = ['modelo_id', 'placa', 'disponivel', 'km'];

    public function rules() {
        return [
            'modelo_id' => 'exists:modelos,id',
            'placa' => 'required',
            'disponivel' => 'required',
            'km' => 'required'
        ];
    }

    public function modelo() {
        return $this->belongsTo('App\Models\Modelo');
    }
}

```

### Controllers

`ClienteController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{
    public function __construct(Cliente $cliente) {
        $this->cliente = $cliente;
    }

    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        if($request->has('filtro')) {
            $clienteRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $clienteRepository->selectAtributos($request->atributos);
        } 

        return response()->json($clienteRepository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->cliente->rules());

        $cliente = $this->cliente->create([
            'nome' => $request->nome
        ]);

        return response()->json($cliente, 201);
    }

    public function show($id)
    {
        $cliente = $this->cliente->find($id);
        if($cliente === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($cliente, 200);
    }

    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($cliente->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

        } else {
            $request->validate($cliente->rules());
        }
        
        $cliente->fill($request->all());
        $cliente->save();
        
        return response()->json($cliente, 200);
    }


    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if($cliente === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $cliente->delete();
        return response()->json(
            ['msg' => 'O cliente foi removido com sucesso!'], 200);
        
    }
}

```

`LocacaoController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use Illuminate\Http\Request;
use App\Repositories\LocacaoRepository;

class LocacaoController extends Controller
{
    public function __construct(Locacao $locacao) {
        $this->locacao = $locacao;
    }

    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        if($request->has('filtro')) {
            $locacaoRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        } 

        return response()->json($locacaoRepository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->locacao->rules());

        $locacao = $this->locacao->create([
            'cliente_id' => $request->cliente_id,
            'carro_id' => $request->carro_id,
            'data_inicio_periodo' => $request->data_inicio_periodo,
            'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
            'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
            'valor_diaria' => $request->valor_diaria,
            'km_inicial' => $request->km_inicial,
            'km_final' => $request->km_final
        ]);

        return response()->json($locacao, 201);
    }


    public function show($id)
    {
        $locacao = $this->locacao->find($id);
        if($locacao === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($locacao, 200);
    }


    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($locacao->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

        } else {
            $request->validate($locacao->rules());
        }
        
        $locacao->fill($request->all());
        $locacao->save();
        
        return response()->json($locacao, 200);
    }


    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if($locacao === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $locacao->delete();
        return response()->json(
            ['msg' => 'A locação foi removida com sucesso!'], 200);
        
    }
}

```

`CarroController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use Illuminate\Http\Request;
use App\Repositories\CarroRepository;

class CarroController extends Controller
{
    public function __construct(Carro $carro) {
        $this->carro = $carro;
    }

    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        if($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelo:id,'.$request->atributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        if($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }

        if($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        } 

        return response()->json($carroRepository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->carro->rules());

        $carro = $this->carro->create([
            'modelo_id' => $request->modelo_id,
            'placa' => $request->placa,
            'disponivel' => $request->disponivel,
            'km' => $request->km
        ]);

        return response()->json($carro, 201);
    }


    public function show($id)
    {
        $carro = $this->carro->with('modelo')->find($id);
        if($carro === null) {
            return response()->json(['erro' => 'Recurso pesquisado não existe'], 404) ;
        } 

        return response()->json($carro, 200);
    }

    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if($carro === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a atualização. O recurso solicitado não existe'], 404);
        }

        if($request->method() === 'PATCH') {

            $regrasDinamicas = array();

            //percorrendo todas as regras definidas no Model
            foreach($carro->rules() as $input => $regra) {
                
                //coletar apenas as regras aplicáveis aos parâmetros parciais da requisição PATCH
                if(array_key_exists($input, $request->all())) {
                    $regrasDinamicas[$input] = $regra;
                }
            }
            
            $request->validate($regrasDinamicas);

        } else {
            $request->validate($carro->rules());
        }
        
        $carro->fill($request->all());
        $carro->save();
        
        return response()->json($carro, 200);
    }


    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if($carro === null) {
            return response()->json(
                ['erro' => 'Impossível realizar a exclusão. O recurso solicitado não existe'], 404);
        }

        $carro->delete();
        return response()->json(
            ['msg' => 'O carro foi removido com sucesso!'], 200);
        
    }
}

```

