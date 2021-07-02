# Laravel diferença entre as versões



## Chamando controllers de Routes



### Laravel e PHP 7

```
Route::get('/', 'PrincipalController@principal');
```

### Laravel e PHP 8

```
Route::get('/', '\App\Http\Controllers\PrincipalController@principal');
```

