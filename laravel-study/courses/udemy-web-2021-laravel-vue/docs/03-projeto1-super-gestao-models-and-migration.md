# Projeto 1 - Super gestão - Seção 8: Models e Migrations

## Criar Model

**OS MODELS DEVEM SER ESCRITOS NO SINGULAR**

```php
php artisan make:model SiteContato -m
```

+ `-m`: Vamos criar em seguida a migration. É passada se o objeto do model for ser salvo no banco de dados
+ Será criado em `app/`

## Criar Migrations

É programar o banco de dados a partir do php. Isso facilita para que muitos programadores trabalhem cada um com seu banco local.

migrations fica em `database/migrations`

Por default, todo projeto já começa com duas

Nas migrations, o nome do model `SiteContrato` é convertido para a tabela `site_contato`.

depois de executado o `make:model -m`  vai gerar a migration nessa pasta

Va até ela e digite as colunas que você quer para a sua tabela

```php
public function up()
    {
    	// // como vem por default
        // Schema::create('site_contatos', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });

        Schema::create('site_contatos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('nome', 50);
            $table->string('telefone', 20);
            $table->string('email', 80);
            $table->integer('motivo_contato');
            $table->text('mensagem');
        });
    }
```

Será executado o código convertido para SQL ao usar `migrate`

## Configurar Laravel para MySQL e executar migrate

**1 Configurar MySQL**

Instalar MySQL e MySQLWorkBench; Lembrar de ativar o mysql (no windows é em Task Manager, na parte de Serviços, MYSQL deve está iniciado)

**2. Configurar a conexão no .env**

vamos em `config/database.php`

Perceba que na parte do `mysql` ele chamar por `.env`. 

`.env` deve ser modificado para or os valores do nosso bd.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sg
DB_USERNAME=root
DB_PASSWORD=Root_7123
```

Antes de fazer a migration é necessário criar

```sql
create database sg;
use sg;
```

**3. Testar Extensão PHP pdo_mysql**

```
php -r "var_dump(extension_loaded('pdo_mysql'));"
```

verificar se está ou não habilitado a extensão `pdo_mysql` em php.ini

**4. Executar migration**

```
php artisan migrate
```

## Continuando cirando outro modelo

Vamos criar sem gerar a migration diretamente

```
php artisan make:model Fornecedor
```

Depois criamos a migration

```
php artisan make:migration create_fornecedores_table
```

e na migration vamos por

```
Schema::create('fornecedores', function (Blueprint $table) {
    $table->id();
    $table->string('nome', 50);
    $table->timestamps();
});
```

**Como é feito o migrate**

Ele não executa migrations já executadas. E ele sabe disso porque ele tem uma tabela no mysql chamada migrations que por ela, ele sabe o que executou ou não.

As migrations feita para um desenvolvimento **INCREMENTAL**

## Usando migration para add/remove colunas

Assim, se eu crio uma tabela, e depois que euro adicionar colunas, eu não preciso modificar a migration que criou a tabela. Eu posso criar uma migration que só faça exclusivamente adição ou remoção de colunas

```
php artisan make:migration alter_fornecedores_novas_colunas
```

implementados o `up()` adicionando colunas

```php
public function up()
    {
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->string('uf', 2);
            $table->string('email', 150);
        });
    }
```

Observe: ao invés de usarmos o `create` usamos `table` para apenas modificar uma tabela já existente

Executar o migrate

```
php artisan migrate
```

## Método up e down

**UP** 

Criar as coisas. É o que é executada quando se faz migrate

**DOWN**

Deve ser sempre feito para podemos fazer o ROLL-BACK.

o Método `down()` desfaz tudo o que fizermos na `up()`

para executar o rollback, utilizamos

```
php artisan migrate:rollback
```

+ É feito do arquivo mais novo par ao mais antigo
+ Você pode especificar quantos batchs deve reverter em steps

```
php artisan migrate:rollback --steps=2
```

**Exemplo para alteraçâo da tabela fornecedores**

```php
class AlterFornecedoresNovasColunas extends Migration
{
    public function up()
    {
        //
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->string('uf', 2);
            $table->string('email', 150);
        });
    }


    public function down()
    {
        Schema::table('fornecedores', function (Blueprint $table) {
            //para remover colunas
            // $table->dropColumn('uf');
            // $table->dropColumn('email');
            $table->dropColumn(['uf', 'email']);
        });
    }
}

```

## Colunas Nullable e valores default para colunas

Modificador nullable: permite que seja possível por null.

+ OBS:  Aceitar valor nulo significa que você pode criar uma linha e não inserir dados para uma coluna

valores default: se não vinher nada, ao invés de por null, põe um valor default

Criar nova migration

```
php artisan make:migration create_produtos_table
```

Migrations com colunas Nullable e valor default

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->text('descricao')->nullable();
            $table->integer('peso')->nullable();
            $table->float('preco_venda', 8, 2)->default(0.01);
            $table->integer('estoque_minimo')->default(1);
            $table->integer('estoque_maximo')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}

```

## Relacionamento : 1-1

```
php artisan make:migration create_produto_detalhes_table
```

detalhes do up para fazer FK 1-1 : Um produto tem 1 detalhe

tipo do ID: *unsignedBigInteger*: Ou seja, aceita valores grande e não aceita valores negativos

```php
public function up()
    {
        Schema::create('produto_detalhes', function (Blueprint $table) {
            //colunas
            $table->id();
            $table->unsignedBigInteger('produto_id');
            $table->float('comprimento', 8, 2);
            $table->float('largura', 8, 2);
            $table->float('altura', 8, 2);
            $table->timestamps();

            //constraint
            $table->foreign('produto_id')->references('id')->on('produtos');
            $table->unique('produto_id');
        });
    }
```

## Relacionamento : 1-M

```
php artisan make:migration create_unidades_table
```

O que estamos fazendo: 1. Criando uma nova tabela; 2. Estabelecendo a relaçâo 1:M

Observe que o `donw()` tem mais coisas para desfazer os relacionamentos.

```php
class CreateUnidadesTable extends Migration
{

    {
        Schema::create('unidades', function (Blueprint $table) {
            $table->id();
            $table->string('unidade', 5); //cm, mn, kg
            $table->string('descricao', 30);
            $table->timestamps();
        });

        //adicionar o relacionamento com a tabela produtos
        Schema::table('produtos', function(Blueprint $table) {
            $table->unsignedBigInteger('unidade_id');
            $table->foreign('unidade_id')->references('id')->on('unidades');
        });

        //adicionar o relacionamento com a tabela produto_detalhes
        Schema::table('produto_detalhes', function(Blueprint $table) {
            $table->unsignedBigInteger('unidade_id');
            $table->foreign('unidade_id')->references('id')->on('unidades');
        });
    }

    public function down()
    {
        //remover o relacionamento com a tabela produto_detalhes
        Schema::table('produto_detalhes', function(Blueprint $table) {
            //remover a fk
            $table->dropForeign('produto_detalhes_unidade_id_foreign'); //[table]_[coluna]_foreign
            //remover a coluna unidade_id
            $table->dropColumn('unidade_id');
        });

        //remover o relacionamento com a tabela produtos
        Schema::table('produtos', function(Blueprint $table) {
            //remover a fk
            $table->dropForeign('produtos_unidade_id_foreign'); //[table]_[coluna]_foreign
            //remover a coluna unidade_id
            $table->dropColumn('unidade_id');
        });

        Schema::dropIfExists('unidades');
    }
}
```

## Relacionamento : M-N

```
php artisan make:migration ajuste_produtos_filiais
```

código da migration

```php
class AjusteProdutosFiliais extends Migration
{
  
    public function up()
    {
        //criando a tabela filiais
        Schema::create('filiais', function (Blueprint $table) {
            $table->id();
            $table->string('filial', 30);
            $table->timestamps();
        });

        //criando a tabela produto_filiais
        Schema::create('produto_filiais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('filial_id');
            $table->unsignedBigInteger('produto_id');
            $table->decimal('preco_venda', 8, 2);
            $table->integer('estoque_minimo');
            $table->integer('estoque_maximo');
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('filial_id')->references('id')->on('filiais');
            $table->foreign('produto_id')->references('id')->on('produtos');
        });

        //removendo colunas da tabela produtos
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn(['preco_venda', 'estoque_minimo', 'estoque_maximo']);
        });
    }

  
    public function down()
    {
        //adicionar colunas da tabela produtos
        Schema::table('produtos', function (Blueprint $table) {
            $table->decimal('preco_venda', 8, 2);
            $table->integer('estoque_minimo');
            $table->integer('estoque_maximo');
        });

        Schema::dropIfExists('produto_filiais');

        Schema::dropIfExists('filiais');
    }
}

```

## Modificador After em migrations: posição das colunas

Quando adicionamos uma coluna em migrations, essas colunas **FICAM NO FIM DA SEUQENCIA DE COLUNAS DE UMA TABELA**. 

Visualmente e talvez até semanticamente isso não fica bom.

Ex: (id, nome, `creat_at`, `update_at`). Se eu adiciona mais features, ficam a direita de tudo isso (,..., uf, email).

Seria interessante que uf e emai, nesse caso, fica-sem antes de `creat_at` e `update_at`.

Para isso usamos o **AFTER** : Apenas altera a ordem das colunas

```
php artisan make:migration alter_fornecedores_coluna_site_com_after
```

Alterar a ordem de uma coluna já existente

```php
class AlterFornecedoresColunaSiteComAfter extends Migration
{

    public function up()
    {
        //
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->string('site', 150)->after('nome')->nullable();
        });
    }

  
    public function down()
    {
        Schema::table('fornecedores', function (Blueprint $table) {
            $table->dropColumn('site');
        });
    }
}

```

## Comandos interessantes do `artisan migrate`

```
php artisan migrate:status
```

+ Atalho para listar as migrations já executados, 

```
php artisan migrate:reset
```

+ Executa todos s rollback das migrations

```
php artisan migrate:refresh
```

+ Ele faz todos os rollbacks e em seguida todos os migrate
+ Útil para desfazer tudo e recriar tudo sem informação

```
php artisan migrate:fresh
```

+ Parecido com o *refresh* mas não executa o rollback, ele faz um `drop` dos objetos



**OBS**: Podemos usar as migrate direto do curso da udemy que elas funcionam no laravel 8

