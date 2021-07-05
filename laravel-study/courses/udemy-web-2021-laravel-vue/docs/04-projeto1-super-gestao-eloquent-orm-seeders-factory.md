# Projeto 1 - Super gestão - Seção 8:  Eloquent ORM e o shell Tinker

## Eloquent ORM

ORM : Mapeamento Objeto relacional

O Eloquent segue o padrão `active record`

## Shell Tinker

```
php artisan tinker
```

+ É om CLI para podermos acessar as classes do Laravel.
+ Através dele vamos acessar nossos model e verificar as relações do ORM
+ É um shell que usaremos para testar e entender o ORM
+ **SEM O TINKER, TERIAMOS QUE TESTAR ISSO NA APLICAÇÃO. TENDO QUE POR ALGUM CÓDIGO EM UM CONTROLLER E O CHAMANDO PARA VERIFICAR O ORM**

**Comandos**

sair: `quit`

update: se você modifica algo em models, é necessário sair e voltar para pegar as atualizações


## Eloquent: `INSERT`

### Forma 1: Criar o modelo e o salva

Criando um model

```
>>> $contato = new \App\Models\SiteContato();
=> App\Models\SiteContato {#3398}
```

Inserindo atributos nesse model

```php
$contato->nome = 'Jorge';
$contato->telefone = '(11) 98888-8888';
$contato->email = "rafa@gmail.com";
$contato->motivo_contato = 1;
$contato->mensagem = "Ola gostaria de ...";
```

Vendo os atributos

```php
print_r($contato->getAttributes());
```

Persistir objeto no banco

```php
$contato->save();
=> true
```

### Forma 2: A partir do método estático `create` e fillable

Podemos usar o método estático `create`, que vem da classe model

ex: no tinker

```
\App\Models\Fornecedor::create(['nome'=>'Fornecedor ABC', 'site'=>'abc.com.br']);
```

mas não funciona, você precisa por esses atributos como fillable no model. Assim o model vai ficar da seguinte forma

```php
class Fornecedor extends Model
{
    use HasFactory;

    protected $table = 'fornecedores';
    protected $fillable = ['nome','site','uf'];
}

```

## Eloquent: Mapeamento do model para a tabela

No caso anterior a classe SiteContato salvou na tabela 'site_contato'.

Como ele faz:

+ O `CamelCase` é convertido para `snake_case`
+ Depois tudo é colocado em `lower()`
+ Adiciona um 's' no final

Quando fizemos 

```
php artisan make:migration SiteContato -m
```

+ !!!!!!! Criamos o Model 'SiteContato' e a migration para criar no banco 'site_contatos'

**E se não corresponder, como por exemplo 'fornecedor' => 'fornecedores' **

Você vai em no model e põe

```php
class Fornecedor extends Model
{
    use HasFactory;
    protected $table = 'fornecedores';
}
```

aqui estamos especificando que é para salvar esse model nessa tabela em especifico

## Eloquent: `WHERE and Others SQL Statements`

**Eloquent ORM é como a sintaxe do spring (java), do djnago (python) do serializer (node)**

**ESCREVER SQL COMO CÓDIGO DE PHP**

+ `all()` 
  
  + retorna collection (array de um tipo)
  
  + ```php
    $fornecedores = \App\Models\Fornecedor::all();			
    ```
    
  + ```php
    use \App\Models\Fornecedor;
    $fornecedores = Fornecedor::all();
    ```
  
  + Podemos imprimir com:
  
  + ```
    print_r($fornecedores->toArray());
    
    //ou
    
    foreach($fornecedores as $f) { echo $f->nome; echo '-';}
    ```
  
+ `find()`

  + Encontrar um registro pelo ID

  + Você pode passa rum array de id, ai, ele retorna uma collection

  + ```
    $fornecedor = Fornecedor::find(1); //  Busca pelo registro de id=2
    ```

+ `where()`

  + Isso retorna um Filter, uma estrutura que permite encadear vários outros `where()` e similares

  + ```php
    $contatos = SiteContato::where('id', '>', '1);
    ```

  +  Para pegar o resultado utilizamos o método `get()`, assim:

  + ```php
    $contatos = SiteContato::where('id', '>', '1)->get()
    ```

+ `whereIn()` e `whereNotIn()`

  +  o método get não precisa ser chamado agora, você pode consultar ainda mais sobre o Filter, e só no final chamar o `get`

  + ```php
    $contatos = SiteContrato::whereIn('motivo_contato', [1,3])->get();
    ```

+ `whereBetween()` e `whereNotBetwen()`

  + busca por RANGE de valores numéricos

  + ```
    $contatos = SiteContato:whereNotBetween('id', [3,6])->get();
    ```

+ `where()` encadeado EQUIVALE AO **`AND`**

  + Usando em sequencia de where(AND)
    podemos fazer um where, e depois um `whereIn` e depois um `whereBetween` e só no final chamar  get (não vou escrever)

  + ```
    $contatos = SiteContato::where('nome', '<>', 'Fernando')->orWhereIn(.....)->get();
    ```

  + Equivale à: SQL `SELECT * FROM site_contatos WHERE nome != 'Fernando' AND ...`

+ `where()` como **`OR`**

  + basta adicionar um 'or' antes do método do 'where

  + ```
    $contatos = SiteContato::where('nome', '<>', 'Fernando')->orWhereIn(.....)->get();
    ```

+ `whereNull()` , `whereNotnull()`

  + buscar por registros que tenham uma certa coluna com/sem o valor null

  + ```
    $contatos = SiteContatos::whereNull('update_at');
    ```

+ Consulta sobre `Date`

  + `whereDay()`, `whereMonth()`, `whereYear()`, `whereTime()`

  + ```php
    $contatos = SiteContato:whereMonth('creat_at', '31')->get(); // Exemplo
    // na consulta acima, vai buscar pelo que foi feito no dia 31
    ```

+ `whereCollumn()`

  + Serve para comprara valores de uma mesma tabela

  + OBS: não compara valores nulos, se algum dos valores for nulo, ele não lê e prossegue na buscar

  + ```php
    $contato = SiteContato::whereColumn('creat_at', 'updated_at)->get()';
    $contato = SiteContato::whereColumn('creat_at', '<>', 'updated_at)->get()';
    ```

+ Agrupar uma sequencia logico no `where`

  + EQUIVALE AOS PARENTESIS DE UMA CONSULTA SQL

  + Isso será usado para consultas com where mais complexo. Onde a ordem dos `where` a ser usado são importantes

  + Exemplo em 1uma única linha

  + ```php
     $contato = SiteContato::where((function($query){$query->where('nome','Jorge')->orWhere('nome', 'ana'); }->where(function($query){$query->whereIn('motivo_contato', [1,2])->orWhereBetween('id', [4,6]);})->get();))
    ```

  + Mesmo exemplo MultiLine

  + ```php
    $contato = 
        SiteContato::where(
          (function($query){
              $query->where('nome','Jorge')->orWhere('nome', 'ana'); 
        }
        )->where(
            function($query){
                $query->whereIn('motivo_contato', [1,2])->orWhereBetween('id', [4,6]);
            }
        )->get();)
    ```

+ `OrderBy()`

  + pode ser 'asc' ou 'desc'

  + podemos aplicar um segundo critério de `orderBy`

  + ```
    $contatos = SiteContatos::orderBy('nome', 'asc')->get();
    ```

## Eloquent Collection




Collection é o retorno de um Eloquent ORM

https://laravel.com/docs/8.x/collections#introduction

Há vários métodos que podemos utilizar. Basicamente um array com várias funções internas que facilitam nossa vida.

Exemplo: O método abaixo retorna uma collection

````
$contatos = Sitecontatos::where('id', '>', 3);
````

Sobre essa collection podemos executar vários métodos

+ `get()`

  + pega o resultado. retorna 1 registro ou mais de um

  + ```
    $contatos->get();
    ```

+ `first()`, `last()`, `reverse()`

  + pega o primeiro, pegar o ultimo, reverter a lista

+ `toArray()`, `toJson()`

  + Converte a collection para o array PHP ou JSON

  + ````
    SiteContato::all()->toArray();
    SiteContato::all()->toJson();
    ````


+ `pluck()`

  - Retorna uma collection filtrada por um atributo onde a chave é o ID
    - tipo `[7 => 'helena', 8 => 'ana']`
    
  - Ex: eu quero todos os nomes do 'site_contato'

  - Posso especifica mais coisa, como mudar o index de 'id' para 'nome'
  
  - ```
    SiteContato::all()->pluck('email');
    ```


## Eloquent: `UPDATE`

**Forma direta**: Basta buscar o objeto, modifica-lo, e aplicar '`save()`'

  ```php
use \App\Fornecedor;
$fornecedor = Fornecedor::find(1);
$fornecedor->nome = 'Fornecedor renovado';
$fornecedor->site = 'xyz.com.br';
$fornecedor->save();
  ```

**Preenche com fill:** Podemos preencher de uma forma mais elegante, com fill. E isso só funciona se usarmos `$fillable` no nosso model

```php
use \App\Fornecedor;
$fornecedor = Fornecedor::find(1);
$fornecedor->fill(['nome'=>'Forncedor 5', site => 'fafsafa.com'])
$fornecedor->save();
```

**Atualizar mais de um registro usando 'where() ' e 'update()'**

é feito fazendo um where e em sequencia um update

```php
Fornecedor::whereIn('id', [1,2])->update(['nome'=>'alfa','site=>'ta']);
```


Assim vai atualizar mais de um registros, 

## Eloquent: `DELETE`

`delete()` pode ser combinado com where() ou find(), para uma collection com um ou mais elementos

```php
use \App\Fornecedor;
$fornecedor = Fornecedor::find(1);
$fornecedor->delete();
```

`destroy()` : basta passar uma série de ID OU UM UNICO

```
Sitecontato::destroy(5);
```

### Soft Delete

SoftDelete significa registrar o campo `deletet_at` na tabela inutilizando eles nas consultas do Eloquent ORM

+ Serve para manter no histórico dos dados sem deleta-los da tabela

**1. Alterar Model para usar `SoftDeletes`**

+ Chamamos `SoftDeletes` e o usamos com `use`

+ A instrução `use` é um Trait (algo semelhante ao Mixin)

```php
<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fornecedor extends Model {
    use SoftDeletes;
    protected $table = 'fornecedores';
    protected $fillable = ['nome', 'site', 'uf', 'email'];
}
```

**2. Alterar por migration a tabela**

Temos que por esse método na migration que a chama,

No caso é em migraiton fornecedores, terá que pro `$table->sofSDeletes()`

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFornecedoresSoftdelete extends Migration {
    
	public function up(){
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down(){
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }

}
```

assim vai criar a coluna `deleted_at` na tabela.
AGORA eu faço um exclusão normal

```php
use \App\Fornecedor;
$fornecedor = Fornecedor::find(1);
$fornecedor->delete();
```

Se eu buscar por esse regiro pelo ORM, não vai aparecer.

MAS O DADO ESTARÁ NO BANCO, COM A COLUNA `deleted_at` preenchida.

Para devidamente deletar usamos

```
=>forceDelete()
```

para fazer as coisas normais do Eloquent ORM e CONSIDERANDO AS COISA DELETADAS COM SOFTDELETE, usamos `withTrashed()`

```
Fornecedor::withTrashed()->...........
```

para retornar aqueles removidos somente com SoftDelete

```
Fornecedor::onlyTrashed()->...........
```

Restaurar. Eu busco algo deletado e aplica `restore()`

```
$fornecedores = Forncedor::withTrashed()->get()
$fornecedores[0]->restore();
```

## Seed: Povoando o banco com HarCode

As seeds ficam em `/database/seeds/`

**Criar Seed**

```
php artisan make:seeder FornecedorSeeder
```

Vai criar o seguinte arquivo

```php
<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class FornecedorSeeder extends Seeder{
   
    public function run() {
    
    }
}
```

`FornecedorSeeder.php`: Há 3 métodos de fazer o `INSERT`

```PHP
<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Fornecedor;
use Illuminate\Support\Facades\DB;

class FornecedorSeeder extends Seeder
{

    public function run()
    {
        //instanciando o objeto
        $fornecedor = new Fornecedor();
        $fornecedor->nome = 'Fornecedor 100';
        $fornecedor->site = 'fornecedor100.com.br';
        $fornecedor->uf = 'CE';
        $fornecedor->email = 'contato@fornecedor100.com.br';
        $fornecedor->save();

        //o método create (atenção para o atributo fillable da classe)
        Fornecedor::create([
            'nome' => 'Fornecedor 200',
            'site' => 'fornecedor200.com.br',
            'uf' => 'RS',
            'email' => 'contato@fornecedor200.com.br'
        ]);

        //insert
        DB::table('fornecedores')->insert([
            'nome' => 'Fornecedor 300',
            'site' => 'fornecedor300.com.br',
            'uf' => 'SP',
            'email' => 'contato@fornecedor300.com.br'
        ]);
    }
}
```

**Executar todas as Seed**

OBS: As seeds são executados se forem chamadas em `DatabaseSeeder.php`

```
php artisan db:seed
```

O arquivo `DatabaseSeeder.php` `LARAVEL 8` deve chamar os Seeders

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call(FornecedorSeeder::class);
        $this->call(SiteContatoSeeder::class);
    }
}

```

Se quisermos executar um `seeder` especifico

```
php artisan db:seed --class=FornecedorSeeder
```

## Factory com Seeders: Usando Faker para gerara 100 rows Random

Diferença pelo Laravel8: https://stackoverflow.com/questions/63816395/laravel-call-to-undefined-function-database-seeders-factory

Muito interessante para  estes

As `Factoryelas` ficam em `database/factories`

criando uma factory (Temos que especificar o model)

```
php artisan make:factory SiteContatoFactory --model=SiteContato
```

Gera o seguinte arquivo `FACTORY` `LARAVEL 8`

```php
<?php

namespace Database\Factories;

use App\Models\SiteContato;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteContatoFactory extends Factory
{
    
    protected $model = SiteContato::class;
    
    public function definition()
    {
        return [
            //
        ];
    }
}
```

Escrevendo nossa Factory

```php
<?php

namespace Database\Factories;

use App\Models\SiteContato;
use Faker\Generator as Faker;

$factory->define(SiteContato::class, function (Faker $faker) {
    return [
        'nome' => $faker->name,
        'telefone' => $faker->tollFreePhoneNumber,
        'email' => $faker->unique()->email,
        'motivo_contato' => $faker->numberBetween(1,3),
        'mensagem' => $faker->text(200)
    ];
});

```

Agora devemos escrever nossa `SEEDER` `LARAVEL 8`

```php
<?php

namespace Database\Factories;

use App\Models\SiteContato;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteContatoFactory extends Factory
{

    protected $model = SiteContato::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->name,
            'telefone' => $this->faker->tollFreePhoneNumber,
            'email' => $this->faker->unique()->email,
            'motivo_contato' => $this->faker->numberBetween(1,3),
            'mensagem' => $this->faker->text(200)
        ];
    }
}
```

Utilizamos a biblioteca chamada faker

`fzaninotto/faker` (acesse ela no github)

 vamos usar o `$faker` para gerar dados randômicos

**Funciona tanto Seeder com Factory no Laravel 8 no dia 04/07/2021**

Foi gerado 100 registros randômico para a tabela `site_contatos`

