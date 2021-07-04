<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });


// PHP 7
// Route::get('/', 'PrincipalController@principal');
// Route::get('/sobre-nos', 'SobreNosController@sobreNos');
// Route::get('/contato', 'ContatoController@contato');

// PHP 8
Route::get('/', '\App\Http\Controllers\PrincipalController@principal')->name('site.index');
Route::get('/sobre-nos', '\App\Http\Controllers\SobreNosController@sobrenos')->name('site.sobrenos');
Route::get('/contato', '\App\Http\Controllers\ContatoController@contato')->name('site.contato');

// // Testando vários parametros
// Route::get(
//     '/contato/{nome}/{categoria}/{assunto}/{mensagem}',
//     function(string $nome, string $categoria, string $assunto, string $mensagem) {
//         echo "Estamos aqui: $nome - $categoria - $assunto - $mensagem";
//     }
// );

// // Parametros adicionais, valores default e validaçâo de parametros
// Route::get(
//     '/contato/{nome}/{categoria_id}',
//     function(
//         string $nome = 'Desconhecido',
//         int $categoria_id = 1 // 1 - 'Informação'
//     ) {
//         echo "Estamos aqui: $nome - $categoria_id";
//     }
// )->where('categoria_id', '[0-9]+')->where('nome', '[A-Za-z]+');

Route::get('/login', function(){return 'Login';})->name('site.login');

Route::prefix('/app')->group(function(){
    Route::get('/clientes', function(){return 'Clientes';})->name('app.clientes');
    // Route::get('/fornecedores', function(){return 'Fornecedores';})->name('app.fornecedores');
    Route::get('/fornecedores', '\App\Http\Controllers\FornecedorController@index')->name('app.fornecedores');
    Route::get('/produtos', function(){return 'produtos';})->name('app.produtos');
});

// Route::get('/clientes', function(){return 'Clientes';});
// Route::get('/fornecedores', function(){return 'Fornecedores';});
// Route::get('/produtos', function(){return 'produtos';});

// // TESTANDO REDIRECT
// Route::get('/rota1', function() {
//     echo 'Rota 1';
// })->name('site.rota1');

// Route::get('/rota2', function() {
//     return redirect()->route('site.rota1');
// })->name('site.rota2');

// Rote::redirect('/rota2', '/rota1');

// ROTA DE FALLBACK :: CONTIGENCIA :: NOT FOUND
Route::fallback(function() {
    echo 'A rota acessada não existe. <a href="'.route('site.index').'">clique aqui</a> para ir para página inicial';
});

Route::get('/teste/{p1}/{p2}', '\App\Http\Controllers\TesteController@teste')->name('site.teste');
