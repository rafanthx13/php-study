## Projeto 1 - Super gestão - Seção 12: Finalizando o Projeto

**ESSA SEÇÂO POSSUI 8H, para agilizar resolver resumir pelos títulos das aulas os tópicos importantes que ela aborda**

**Sumário**

+ Cadastro de Fornecedores
  + CRUD - index, create, store, show, edit, update, destroy, listagem (validando dados)
+ Paginação de Registro
+ Eloquent ORM
  + Relacionamento 1x1 (Produto x Produto Detalhe)
  + Relacionamento 1xN (Produto x Fornecedores)
  + Relacionamento NxM 
    + `belongsToMany`, Tabela Pivô
    + Ultima Aula: Removendo o relacionamento pela PK de pedido produto
+ Lazy Loading vs Eager Loading



## Detalhes Específicos

**EU NAO VI, VOU SÓ PEGAR OS ARQUIVOS QUE MEXEM COM O QUE EU QUERO**

### Relacionamento M-N

PEDIDO X PRODUTO : 1 pedido tem vários produtos e 1 produto pode pertencer a vários pedidos,

**envolve:** Pedido, Item, PedidoProduto

Sendo Item a mesma coisa que Produto

#### Migrations

Sobe 3 tabelas e faz suas relações

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientesPedidosProdutos extends Migration
{

    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 50);
            $table->timestamps();
        });

        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->timestamps();

            $table->foreign('cliente_id')->references('id')->on('clientes');
        });

        Schema::create('pedidos_produtos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('produto_id');
            $table->timestamps();

            $table->foreign('pedido_id')->references('id')->on('pedidos');
            $table->foreign('produto_id')->references('id')->on('produtos');
        });
    }

    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('pedidos');
        Schema::dropIfExists('pedidos_produtos');
        Schema::enableForeignKeyConstraints();
    }
}

```

Em seguida, fazemos outra migration para outra alteração

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterPedidosProdutosAddQuantidade extends Migration
{

    public function up()
    {
        Schema::table('pedidos_produtos', function (Blueprint $table) {
            $table->integer('quantidade');
        });
    }

    public function down()
    {
        Schema::table('pedidos_produtos', function (Blueprint $table) {
            $table->dropColumn('quantidade');
        });
    }
}
```

#### Models

##### Item

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'produtos';
    protected $fillable = ['nome', 'descricao', 'peso', 'unidade_id', 'fornecedor_id'];

    public function itemDetalhe() {
        return $this->hasOne('App\ItemDetalhe', 'produto_id', 'id');
    }

    public function fornecedor() {
        return $this->belongsTo('App\Fornecedor');
    }

    public function pedidos() {
        return $this->belongsToMany('App\Pedido', 'pedidos_produtos', 'produto_id', 'pedido_id');

        /* ORDEM DOS PARAMETROS DE 'belongsToMany'
            3 - Representa o nome da FK da tabela mapeada pelo model na tabela de relacionamento
            4 - Representa o nome da FK da tabela mapeada pelo model utilizado
            	no relacionamento que estamos implementando
        */
    }
}

```

##### Pedido

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    public function produtos() {
        //return $this->belongsToMany('App\Produto', 'pedidos_produtos');

        return $this->belongsToMany('App\Item', 'pedidos_produtos', 'pedido_id',
                                    'produto_id')->withPivot('id', 'created_at', 'updated_at');
        /* ORDEM DOS PARAMETROS DE 'belongsToMany'
            1 - Modelo do relacionamento NxN em relação o Modelo que estamos implementando
            2 - É a tabela auxiliar que armazena os registros de relacionamento
            3 - Representa o nome da FK da tabela mapeada pelo modelo na tabela de relacionamento
            4 - Representa o nome da FK da tabela mapelada pelo model utilizado
            	no relacionamento que estamos implementando
        */
    }
}

```

##### PedidoProduto

```php
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PedidoProduto extends Model
{
    protected $table = 'pedidos_produtos';
}
```



#### Route

Vamos gerar o `Resource`, ou seja, o Controller com os métodos default do laravel de CRUD

```
php artisan make:controller --resource ClienteController
```

troca `ClienteController` por `PedidoController` e `PedidoProdutoController`

Por isso nosso `route` de `web.php` vai receber em muito lugares `::resource` pois tem esse métodos

```php
Route::resource('produto', 'ProdutoController');

    //produtos detalhes
    Route::resource('produto-detalhe', 'ProdutoDetalheController');

    Route::resource('cliente', 'ClienteController');
    Route::resource('pedido', 'PedidoController');
    //Route::resource('pedido-produto', 'PedidoProdutoController');
    Route::get('pedido-produto/create/{pedido}', 'PedidoProdutoController@create')->name('pedido-produto.create');
    Route::post('pedido-produto/store/{pedido}', 'PedidoProdutoController@store')->name('pedido-produto.store');
    //Route::delete('pedido-produto.destroy/{pedido}/{produto}', 'PedidoProdutoController@destroy')->name('pedido-produto.destroy');
    Route::delete('pedido-produto.destroy/{pedidoProduto}/{pedido_id}', 'PedidoProdutoController@destroy')->name('pedido-produto.destroy');
```

#### Controllers

Como vai ficar os controllers no final

##### PedidoProdutoController.php

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\Produto;
use App\PedidoProduto;

class PedidoProdutoController extends Controller
{
  
    public function create(Pedido $pedido)
    {
        $produtos = Produto::all();
        //$pedido->produtos; //eager loading
        return view('app.pedido_produto.create', ['pedido' => $pedido, 'produtos' => $produtos]);
    }
 
    public function store(Request $request, Pedido $pedido)
    {
        $regras = [
            'produto_id' => 'exists:produtos,id',
            'quantidade' => 'required'
        ];

        $feedback = [
            'produto_id.exists' => 'O produto informado não existe',
            'required' => 'O campo :attribute deve possuir um valor válido'
        ];

        $request->validate($regras, $feedback);

   

        $pedido->produtos()->attach([
            $request->get('produto_id') => ['quantidade' => $request->get('quantidade')]
        ]);

        return redirect()->route('pedido-produto.create', ['pedido' => $pedido->id]);
        
    }

    //public function destroy(Pedido $pedido, Produto $produto)
    public function destroy(PedidoProduto $pedidoProduto, $pedido_id)
    {
        /*
        print_r($pedido->getAttributes());
        echo '<hr>';
        print_r($produto->getAttributes());
        */

        //echo $pedido->id.' - '.$produto->id;

        //convencional
        /*
        PedidoProduto::where([
            'pedido_id' => $pedido->id,
            'produto_id' => $produto->id
        ])->delete();
        */

        //detach (delete pelo relacionamento)
        //$pedido->produtos()->detach($produto->id);
        //produto_id

        $pedidoProduto->delete();
        
        return redirect()->route('pedido-produto.create', ['pedido' => $pedido_id]);
    }
}

```

##### ProdutoController.php

```php
<?php

namespace App\Http\Controllers;

use App\Produto;
use App\Item;
use App\ProdutoDetalhe;
use App\Unidade;
use App\Fornecedor;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{

    public function index(Request $request)
    {
        $produtos = Item::with(['itemDetalhe', 'fornecedor'])->paginate(10);

        return view('app.produto.index', ['produtos' => $produtos, 'request' => $request->all() ]);
    }

    public function create()
    {
        $unidades = Unidade::all();
        $fornecedores = Fornecedor::all();
        return view('app.produto.create', ['unidades' => $unidades, 'fornecedores' => $fornecedores]);
    }

    public function store(Request $request)
    {
        $regras = [
            'nome' => 'required|min:3|max:40',
            'descricao' => 'required|min:3|max:2000',
            'peso' => 'required|integer',
            'unidade_id' => 'exists:unidades,id',
            'fornecedor_id' => 'exists:fornecedores,id'
        ];

        $feedback = [
            'required' => 'O campo :attribute deve ser preenchido',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'nome.max' => 'O campo nome deve ter no máximo 40 caracteres',
            'descricao.min' => 'O campo descrição deve ter no mínimo 3 caracteres',
            'descricao.max' => 'O campo descrição deve ter no máximo 2000 caracteres',
            'peso.integer' => 'O campo peso deve ser um número inteiro',
            'unidade_id.exists' => 'A unidade de medida informada não existe',
            'fornecedor_id.exists' => 'O fornecedor informado não existe'
        ];

        $request->validate($regras, $feedback);
        
        Item::create($request->all());
        return redirect()->route('produto.index');
    }


    public function show(Produto $produto)
    {
        return view('app.produto.show', ['produto' => $produto]);
    }

    public function edit(Produto $produto)
    {
        $unidades = Unidade::all();
        $fornecedores = Fornecedor::all();
        return view('app.produto.edit', ['produto' => $produto, 'unidades' => $unidades, 'fornecedores' => $fornecedores]);
        //return view('app.produto.create', ['produto' => $produto, 'unidades' => $unidades]);
    }

    public function update(Request $request, Item $produto)
    {
        $regras = [
            'nome' => 'required|min:3|max:40',
            'descricao' => 'required|min:3|max:2000',
            'peso' => 'required|integer',
            'unidade_id' => 'exists:unidades,id',
            'fornecedor_id' => 'exists:fornecedores,id'
        ];

        $feedback = [
            'required' => 'O campo :attribute deve ser preenchido',
            'nome.min' => 'O campo nome deve ter no mínimo 3 caracteres',
            'nome.max' => 'O campo nome deve ter no máximo 40 caracteres',
            'descricao.min' => 'O campo descrição deve ter no mínimo 3 caracteres',
            'descricao.max' => 'O campo descrição deve ter no máximo 2000 caracteres',
            'peso.integer' => 'O campo peso deve ser um número inteiro',
            'unidade_id.exists' => 'A unidade de medida informada não existe',
            'fornecedor_id.exists' => 'O fornecedor informado não existe'
        ];

        $request->validate($regras, $feedback);

        //dd($request->all());
        $produto->update($request->all());
        return redirect()->route('produto.show', ['produto' => $produto->id ]);
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();
        return redirect()->route('produto.index');
    }
}

```

##### PedidoController.php

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pedido;
use App\Cliente;

class PedidoController extends Controller
{
   
    public function index(Request $request)
    {
        $pedidos = Pedido::paginate(10);
        return view('app.pedido.index', ['pedidos' => $pedidos, 'request' => $request->all()] );
    }

   
    public function create()
    {
        $clientes = Cliente::all();
        return view('app.pedido.create', ['clientes' => $clientes]);
    }
   
    public function store(Request $request)
    {
        $regras = [
            'cliente_id' => 'exists:clientes,id'
        ];

        $feedback = [
            'cliente_id.exists' => 'O cliente informado não existe'
        ];

        $request->validate($regras, $feedback);

        $pedido = new Pedido();
        $pedido->cliente_id = $request->get('cliente_id');
        $pedido->save();

        return redirect()->route('pedido.index');
    }


}

```

#### Explicando todo o processo

Entre os Models:

+ É feito o `belongsToMany` entre Pedido -> Item (Produto) e inverso de Item para pedido

```php
// MODEL ITEM
public function pedidos() {
	return $this->belongsToMany('App\Pedido', 'pedidos_produtos', 'produto_id', 'pedido_id');
...}
        
// MODEL PRODUTO
public function produtos() {
	return $this->belongsToMany('App\Item', 'pedidos_produtos', 'pedido_id', 'produto_id');
...}
```

+ É necessário uma tabela Pivô tem tem que chave de ambos.
+ Se quisermos mais dados da tabela pivô temos que explicitar

```php
// MODEL PRODUTO
return $this->belongsToMany('App\Item', 'pedidos_produtos', 'pedido_id', 'produto_id')->withPivot('id', 'created_at', 'updated_at');
```

##### Como inserir nessa relação NxM : `attach`

Isso é feito e `PeididoProdutoController`

```php
<?php

namespace App\Http\Controllers;use Illuminate\Http\Request;use App\Pedido;use App\Produto;use App\PedidoProduto;

class PedidoProdutoController extends Controller {

    public function create(Pedido $pedido) {
        $produtos = Produto::all();
        //$pedido->produtos; //eager loading
        return view('app.pedido_produto.create', ['pedido' => $pedido, 'produtos' => $produtos]);
    }

    public function store(Request $request, Pedido $pedido) {
        $regras = [
            'produto_id' => 'exists:produtos,id',
            'quantidade' => 'required'
        ];

        $feedback = [
            'produto_id.exists' => 'O produto informado não existe',
            'required' => 'O campo :attribute deve possuir um valor válido'
        ];

        $request->validate($regras, $feedback);

        // FORMA 1 : Funciona até com Array de PedidoProduto
        		// 'attach' é ele que vai fazer a inserçâo em pedidoProduto corretamente
        $pedido->produtos()->attach([
            $request->get('produto_id') => ['quantidade' => $request->get('quantidade')]
        ]);
        
        // FORM2 2: Forma Unitária
        /*
        pedido->produtos()->attach(
            $request->get('produto_id'),
            [
                'quantidade' => $request->get('quantidade'),
                'coluna_1' => '',
                'coluna_2' => '',
            ]
        );
        */

        return redirect()->route('pedido-produto.create', ['pedido' => $pedido->id]);
        
    }

  	public function destroy(Pedido $pedido, Produto $produto) {

        // detach (delete pelo relacionamento)
        $pedido->produtos()->detach($produto->id);

        return redirect()->route('pedido-produto.create', ['pedido' => $pedido_id]);
	}
}

```

#####  Como remover NxM com `detach()`

Usamos `detach` no destroy de `PedidoProdutoController` para remover a relação de uma tabela NxM pelo par de ID da tabela Pivô

```php
public function destroy(Pedido $pedido, Produto $produto) {
       
    // detach (delete pelo relacionamento)
    $pedido->produtos()->detach($produto->id);

    return redirect()->route('pedido-produto.create', ['pedido' => $pedido_id]);
}
```

#####  Como remover pelo ID da tabela Pivô (sem `detach`, mais fácil)

A remoção do `detach` vista antes remove pelo `(produto_id, pedido_id)` se houver mais de um, vai remover os dois mesmo não querendo

**O QUE FOI FEITO**: só passando o id da tabela Pivô ao invés de outra coisa que tinha NA CHAMADA

dessa forma é até mais simples, é so deeltar pelo index pasado, bem mais fácil

```php
public function destroy(PedidoProduto $pedidoProduto, $pedido_id) {

        $pedidoProduto->delete();
        
        return redirect()->route('pedido-produto.create', ['pedido' => $pedido_id]);
}
```

Foi mudado, a chamada que ate esta recebendo o id de PedidoProduto