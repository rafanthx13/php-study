# Laravel diferença entre as versões

## RouteServiceProvider no Laravael 8

"PORUQ EVOCE TEM QUE POR TODO O NAMESPACE EM ROUTE"?

Porque a linha vem comentaa

`protected $namespace = 'App\Http\\Controllers`;

## Chamando controllers de Routes

### Laravel e PHP 7

```
Route::get('/', 'PrincipalController@principal');
```

### Laravel e PHP 8

```
Route::get('/', '\App\Http\Controllers\PrincipalController@principal');
```

## Pasta Models

### Laraval PHP 7

Os Models sâo criados no diretório: app/

### Laravel PHP 8

em app/Models

## NameSpace

### Laravel PHP 7

Muita coisa no PHP 7, como os comandos `artisan`, não geram arquivos que tem um trecho que especifica o `namespace`, e no Laravel 7 não é necessário

### Laravel PHP 8

É necessário ter `namespace` em quase tudo.

O `namespace` é praticament eo diretorio onde esá o arquivo.

Exmeplo, o arquivo que fica em `database/seeders/SiteContato.php` tem o seguinte namespace

```
namespace Database\Seeders;
```



