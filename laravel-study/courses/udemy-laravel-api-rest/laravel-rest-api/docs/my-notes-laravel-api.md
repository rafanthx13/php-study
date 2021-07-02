# Note of Laravael API REST

## Diferença do Laravel 8 para o curso

### NameSpace compleot

Em vez de `Api` tem que dizer tudo `App\\Http\\Controllers\\Api` em `api.php`

##  Comandos

### Criar projeto

````
laravel new first-api
````

+ Deverá então criar a pasta e baixar todas as dependências

### Subir server

````
$ php artisan serve
````

### Cria uma Migrate

php artisan make:migration create_table_products --create=products

Criar a tabelo de products.

Assim em  `database/migrations` vai gerar um arquivo e eu posso setar os seguintes dados

````php
Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('price');
            $table->string('description');
            $table->string('slug');
            $table->timestamps();
        });
````


### Fazer uma migration o BD

php artisan migrate  

Pode ser necessário criar o schema no banco antes

### Criar Model

`php artisan make:model Product`

Cria o arquivo `app/Models/Product.php`

a rota pode ser 


````php
Route::get('/products', function() {
    return \App\Models\Product::all();
});

````

### Criar controler

`php artisan make:controller Api/ProductController`

### Configuraçôes do DB

em `.env`


## Adicionar Rotas em 'routes/api.php'

````php
Route::get('/test', function() {
    return ['msg' => 'My First return on API Laravael'];
});
````
para acessar eu ponho 'api' na frente

`localhost:8000/api/test`

## Começando a api

### Controller

````php

class ProductController extends Controller
{
	private $product;

	public function __construct(Product $product)
	{
		$this->product = $product;
	}

	public function index()
	{
		$products = $this->product->all();
		return response()->json($products);
	}

	public function save(Request $request)
	{
		$data = $request->all();
		$product = $this->product->create($data);
		return response()->json($product);
	}

	public function show($id)
	{
		$product = $this->product->find($id);
		return response()->json($product);
	}

	public function update(Request $request)
	{
		$data = $request->all();

		$product = $this->product->find($data['id']);
		$product->update($data);

		return response()->json($product);
	}

	public function delete($id)
	{
		$product = $this->product->find($id);
		$product->delete();

		return response()->json(['data' => ['msg' => 'Produto foi removido com sucesso!']]);
	}
}


````

### Model Product

o fillable permite restringir os dados a serem inseridos, entao, temos que especificar para ser exatamente como os dados que queremos enviar para fazer um save.

````php
class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'price', 'description', 'slug'
    ];
}

````

## CRUD como Resource

$ php artisan make:controller Api/UserController --resource --api

--resource: inidica que será resource

--api: vai omitir vformularios de criaçâo e ediçâo, o @create e @ edit do `route:list`

ele já vai gerar um controller com os métodos neces´sarios e as associaçôes da URl com o Método HTTP com seu respectivo metodo.

Assim simplififa bastante quandi vocÊ usa controler como recuros.

````php
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
````

## listar rotas

php artisan route:list

lista todas as rotas do nosso projeto, muito legal para ocumentar

## Eloquent: SerializaçÂo do JSON para o API Resource

php artisan make:resource ProductResource

Vai adicionar em App/HTTP/Providers/ProductResource

Serve para trer mais controle do que é retornado

````php
//em vez de retornar no Controler

return response()->json($product)

// pode retonar

return new ProductResource($product)
````
a classe é:

````php
class ProductResource extends Json
````

Vamos criar outra

$ php artisan make:resource ProductCollection

ou

$ php artisan make:resource Product --collection


com o outro metodo retorna assim

````json
[
  {
    "id": 1,
    "name": "product1",
    "price": 19.99,
    "description": "Descriçâo1",
    "slug": "product1",
    "created_at": "2021-06-17T21:54:26.000000Z",
    "updated_at": "2021-06-17T21:54:26.000000Z"
  },
  {
    "id": 2,
    "name": "product2",
    "price": 19.99,
    "description": "Descriçâo2",
    "slug": "product2",
    "created_at": "2021-06-18T00:32:28.000000Z",
    "updated_at": "2021-06-18T00:32:28.000000Z"
  }
]
````

Usando Resource, retorna paginado desde que nosso Get:All seja com paginate ao invez de `all()`

````json
{
  "data": [
    {
      "id": 1,
      "name": "product1",
      "price": 19.99,
      "description": "Descriçâo1",
      "slug": "product1",
      "created_at": "2021-06-17T21:54:26.000000Z",
      "updated_at": "2021-06-17T21:54:26.000000Z"
    }
  ],
  "extra_data": "dado adicional",
  "links": {
    "first": "http:\/\/127.0.0.1:8000\/api\/products?page=1",
    "last": "http:\/\/127.0.0.1:8000\/api\/products?page=2",
    "prev": null,
    "next": "http:\/\/127.0.0.1:8000\/api\/products?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 2,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http:\/\/127.0.0.1:8000\/api\/products?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": "http:\/\/127.0.0.1:8000\/api\/products?page=2",
        "label": "2",
        "active": false
      },
      {
        "url": "http:\/\/127.0.0.1:8000\/api\/products?page=2",
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http:\/\/127.0.0.1:8000\/api\/products",
    "per_page": 1,
    "to": 1,
    "total": 2
  }
}
````
